<?
require_once($basePath.'Editor/Classes/Services/XslService.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/TemplateController.php');
require_once($basePath.'Editor/Classes/InternalSession.php');
require_once($basePath.'Editor/Classes/ExternalSession.php');

class RenderingService {
	
	function displayError($message,$path="") {
		$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>';
		$xml.= '<message xmlns="http://uri.in2isoft.com/onlinepublisher/publishing/error/1.0/">';
		$xml.= $message;
		$xml.= '</message>';
		header('Content-type: text/html');
		header("HTTP/1.0 404 Not Found");
		echo RenderingService::applyStylesheet($xml,"basic","error",$path,$path,$path,'',false);
	}
	
	function buildPageContext($id,$nextPage,$previousPage) {
		$output='<context>';
		// Front pages
		$sql="select specialpage.*,page.path from specialpage,page where page.disabled=0 and specialpage.page_id = page.id";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			if ($row['type']=='home') {
				$output.='<home page="'.$row['page_id'].'"'.($row['path']!='' ? ' path="'.StringUtils::escapeXML($row['path']).'"' : '').($row['language']!='' ? ' language="'.StringUtils::escapeXML(strtolower($row['language'])).'"' : '').'/>';
			}
		}
		Database::free($result);
		// Translations
		$sql="select page.id,page.language,page.path from page_translation,page".
		" where page.id=page_translation.translation_id and page.disabled=0".
		" and page_translation.page_id=".$id." order by language";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$output.='<translation page="'.$row['id'].'"'.($row['path']!='' ? ' path="'.StringUtils::escapeXML($row['path']).'"' : '').($row['language']!='' ? ' language="'.StringUtils::escapeXML(strtolower($row['language'])).'"' : '').'/>';
		}
		Database::free($result);
		if ($nextPage>0) {
			$output.='<next id="'.$nextPage.'"/>';
		}
		if ($previousPage>0) {
			$output.='<previous id="'.$previousPage.'"/>';
		}
		$output.='</context>';
		return $output;
	}
	
	function applyStylesheet(&$xmlData,$design,$template,$path,$urlPath,$navigationPath,$pagePath,$preview) {
		global $basePath;

		$agent='xslt';
		if (function_exists('xslt_create')) {
			$incPath='../../';
		}
		else {
			$incPath=$path;
		}
		if (file_exists($basePath.'style/'.$design.'/xslt/'.$template.'.xsl')) {
			$contentDesign=$design;
			$agent='xslt';
		} else if (file_exists($basePath.'style/basic/xslt/'.$template.'.xsl')) {
			$contentDesign='basic';
			$agent='xslt';
		} else {
			$contentDesign='basic';
		}
		if (file_exists($basePath.'style/'.$design.'/'.$agent.'/stylesheet.xsl')) {
			$mainFile='stylesheet';
			$mainDesign=$design;
		} else if (file_exists($basePath.'style/'.$design.'/xslt/main.xsl')) {
			$mainFile='main';
			$mainDesign=$design;
		} else if (file_exists($basePath.'style/'.$design.'/others/stylesheet.xsl')) {
			$mainFile='stylesheet';
			$mainDesign=$design;
		} else if (file_exists($basePath.'style/'.$design.'/xslt/stylesheet.xsl')) {
			$mainFile='stylesheet';
			$mainDesign=$design;
		} else {
			$mainFile='stylesheet';
			$mainDesign='basic';
		}
		$userId=0;
		$userName='';
		$userTitle='';
		if ($user = ExternalSession::getUser()) {
			$userId=$user['id'];
			$userName=$user['username'];
			$userTitle=$user['title'];
		}
		$mainPath = $incPath.'style/'.$mainDesign.'/xslt/'.$mainFile.'.xsl';
		if (Request::getBoolean('print')) {
			$mainPath = $incPath.'style/'.$mainDesign.'/xslt/print.xsl';
		}
		$templatePath = $incPath.'style/'.$contentDesign.'/xslt/'.$template.'.xsl';
	
		$xslData='<?xml version="1.0" encoding="ISO-8859-1"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="html" indent="no" encoding="ISO-8859-1"/>'.
		'<xsl:include href="'.$mainPath.'"/>'.
		'<xsl:include href="'.$templatePath.'"/>'.
		'<xsl:variable name="design">'.$design.'</xsl:variable>'.
		'<xsl:variable name="path">'.$urlPath.'</xsl:variable>'.
		'<xsl:variable name="navigation-path">'.$navigationPath.'</xsl:variable>'.
		'<xsl:variable name="page-path">'.$pagePath.'</xsl:variable>'.
		'<xsl:variable name="template">'.$template.'</xsl:variable>'.
		'<xsl:variable name="agent">'.StringUtils::escapeXML($_SERVER['HTTP_USER_AGENT']).'</xsl:variable>'.
		'<xsl:variable name="userid">'.StringUtils::escapeXML($userId).'</xsl:variable>'.
		'<xsl:variable name="username">'.StringUtils::escapeXML($userName).'</xsl:variable>'.
		'<xsl:variable name="usertitle">'.StringUtils::escapeXML($userTitle).'</xsl:variable>'.
		'<xsl:variable name="preview">'.($preview ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="editor">false</xsl:variable>'.
		'<xsl:variable name="highquality">'.(requestGetBoolean('print') ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
	
		return XslService::transform($xmlData,$xslData);
	}
	
	function applyFrameDynamism($id,&$data) {
		$sql = "select id,maxitems,sortdir,sortby,timetype,timecount,UNIX_TIMESTAMP(startdate) as startdate,UNIX_TIMESTAMP(enddate) as enddate from frame_newsblock where frame_id=".$id;
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$blockId = $row['id'];
			$maxitems = $row['maxitems'];
			$sortBy = 'news.'.$row['sortby'];
			// Find sort direction
			if ($row['sortdir']=='descending') {
				$sortDir = 'DESC';
			}
			else {
				$sortDir = 'ASC';
			}
			$timetype = $row['timetype'];
			if ($timetype=='always') {
				$timeSql=''; // no time managing for always
			}
			else if ($timetype=='now') {
				// Create sql for active news
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate<=now() and news.enddate>=now()) or (news.startdate<=now() and news.enddate is null) or (news.startdate is null and news.enddate>=now()))";
			}
			else {
				$count=$row['timecount'];
				if ($timetype=='interval') {
					$start = intval($row['startdate']);
					$end = intval($row['enddate']);
				}
				else if ($timetype=='hours') {
					$start = mktime(date("H")-$count,date("i"),date("s"),date("m"),date("d"),date("Y"));
					$end = mktime();
				}
				else if ($timetype=='days') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-$count,date("Y"));
					$end = mktime();
				}
				else if ($timetype=='weeks') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d")-($count*7),date("Y"));
					$end = mktime();
				}
				else if ($timetype=='months') {
					$start = mktime(date("H"),date("i"),date("s"),date("m")-$count,date("d"),date("Y"));
					$end = mktime();
				}
				else if ($timetype=='years') {
					$start = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")-$count);
					$end = mktime();
				}
				$timeSql=" and ((news.startdate is null and news.enddate is null) or (news.startdate>=".Database::datetime($start)." and news.startdate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.enddate<=".Database::datetime($end).") or (news.enddate>=".Database::datetime($start)." and news.startdate is null) or (news.startdate<=".Database::datetime($end)." and news.enddate is null))";
			}
			$newsData = '';
			$sql = "select distinct object.data from object, news, newsgroup_news, frame_newsblock_newsgroup, frame_newsblock where object.id = news.object_id and news.object_id=newsgroup_news.news_id and newsgroup_news.newsgroup_id=frame_newsblock_newsgroup.newsgroup_id and frame_newsblock_newsgroup.frame_newsblock_id=".$blockId.$timeSql." order by ".$sortBy." ".$sortDir;
			$resultNews = Database::select($sql);
			while ($rowNews = Database::next($resultNews)) {
				$newsData.=$rowNews['data'];
				$maxitems--;
				if ($maxitems==0) break;
			}
			Database::free($resultNews);
			$data=str_replace("<!--newsblock#".$blockId."-->", $newsData, $data);
		}
		Database::free($result);
		return $data;
	}
	
	function applyContentDynamism($id,$template,&$data) {
		global $basePath,$baseUrl;
		$state = array('data' => $data,'redirect' => false,'override' => false);
		$controller = TemplateController::getController($template,$id);
		if (method_exists($controller,'dynamic')) {
			$controller->dynamic($state);
		}
		return $state;
	}
	
	function buildPage($id,$allowDisabled=true,$path=null) {
		$sql="select page.id,page.secure,UNIX_TIMESTAMP(page.published) as published,".
		" page.title,page.description,page.language,page.keywords,page.data,page.dynamic,page.next_page,page.previous_page,".
		" template.unique as template,frame.id as frameid,frame.title as frametitle,".
		" frame.data as framedata,frame.dynamic as framedynamic,design.`unique` as design,".
		" design.parameters,".
		" hierarchy.data as hierarchy from page,template,frame,design,hierarchy".
		" where page.frame_id=frame.id and page.template_id=template.id".
		" and page.design_id=design.object_id and frame.hierarchy_id=hierarchy.id";
		if ($path==null) {
			$sql.=" and page.id=".$id;
		} else {
			$sql.=" and page.path=".Database::text($path);
		}
		// If disabled pages are not allowed
		if (!$allowDisabled) {
		    $sql.=" and page.disabled=0";
		}
	
		if ($row = Database::selectFirst($sql)) {
			if (Request::getBoolean('ajax')) {
				$ctrl = TemplateController::getController($row['template'],$id);
				$ctrl->ajax();
				exit;
			}

			$pageNS = 'http://uri.in2isoft.com/onlinepublisher/publishing/page/1.0/';
			$frameNS = 'http://uri.in2isoft.com/onlinepublisher/publishing/frame/1.0/';

			$data = $row['data'];
			$template = $row['template'];
			$redirect = false;
		
			if ($row['dynamic']) {
				$content = RenderingService::applyContentDynamism($row['id'],$template,$data);
				$data = $content['data'];
				$redirect = $content['redirect'];
			}
			$framedata = $row['framedata'];
			if ($row['framedynamic']) {
				$framedata = RenderingService::applyFrameDynamism($row['frameid'],$framedata);
			}
			$xml = '<?xml version="1.0" encoding="ISO-8859-1"?>'.
			'<page xmlns="'.$pageNS.'" id="'.$row['id'].'" title="'.StringUtils::escapeXML($row['title']).'">'.
			'<meta>'.
			($row['description'] ? '<description>'.StringUtils::escapeXML(preg_replace('/\s+/', ' ', $row['description'])).'</description>' : '').
			'<keywords>'.StringUtils::escapeXML($row['keywords']).'</keywords>'.
			RenderingService::buildDateTag('published',$row['published']).
			'<language>'.StringUtils::escapeXML(strtolower($row['language'])).'</language>'.
			'</meta>'.
			RenderingService::buildPageContext($row['id'],$row['next_page'],$row['previous_page']).
			'<design>'.
			$row['parameters'].
			'</design>'.
			'<frame xmlns="'.$frameNS.'" title="'.StringUtils::escapeXML($row['frametitle']).'">'.
			$row['hierarchy'].
			$framedata.
			'</frame>'.
			'<content>'.
			$data.
			'</content>'.
			'</page>';
			return array(
			    'xml' => $xml,
			    'design' => $row['design'],
			    'template' => $template,
			    'published' => $row['published'],
			    'secure' => $row['secure'],
			    'redirect' => $redirect
			);
		}
		else {
			return false;
		}
	}
	
	function findPage($type,$path=-1) {
		$sql="select page_id from specialpage where type='".$type."'";
		$row = Database::selectFirst($sql);
		if ($row) {
			return $row['page_id'];
		} else if ($path!=-1) {
			$error = '<title>Ingen forside!</title>'.
			'<note>Der er ikke opsat en forside til dette website.
			Hvis du er redaktør på siden bør du logge ind i redigeringsværktøjet
			og opsætte hvilken side der skal være forsiden.
			</note>';
			RenderingService::displayError($error,$path);
			exit;
		} else {
			return -1;
		}
	}
	

	function buildDateTag($tag,$stamp) {
		return '<'.$tag.' unix="'.$stamp.'" day="'.date('d',$stamp).'" weekday="'.date('d',$stamp).'" yearday="'.date('z',$stamp).'" month="'.date('m',$stamp).'" year="'.date('Y',$stamp).'" hour="'.date('H',$stamp).'" minute="'.date('i',$stamp).'" second="'.date('s',$stamp).'" offset="'.date('Z',$stamp).'" timezone="'.date('T',$stamp).'"/>';
	}
	


	// Returns true if the user has access to the page
	function userHasAccessToPage($user,$page) {
		$sql = "select * from securityzone_page,securityzone_user where securityzone_page.securityzone_id=securityzone_user.securityzone_id and page_id=".$page." and user_id=".$user;
		if ($row = Database::selectFirst($sql)) {
			return true;
		}
		else {
			return false;
		}
	}
	
	// finds and redirects to the appropriate authentication page for the provided page
	// displays error otherwise
	function goToAuthenticationPage($id) {
		if ($authId = RenderingService::findAuthenticationPageForPage($id)) {
			redirect('./?id='.$authId.'&page='.$id);
		}
		else {
			echo 'could not find auth page!';
		}
	}

	// Finds the appropriate authentication page for a given page
	// returns the id of the authentication page
	// returns false otherwise
	function findAuthenticationPageForPage($id) {
		$sql = "select authentication_page_id,page.id from securityzone, securityzone_page,page,page as authpage where securityzone.object_id=securityzone_page.securityzone_id and page.id= securityzone_page.page_id and authpage.id = authentication_page_id and page.id=".$id;
		if ($row = Database::selectFirst($sql)) {
			return $row['authentication_page_id'];
		}
		else {
			return false;
		}
	}
}