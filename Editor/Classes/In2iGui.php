<?
require_once($basePath.'Editor/Classes/Response.php');
require_once($basePath.'Editor/Classes/SystemInfo.php');
require_once($basePath.'Editor/Classes/InternalSession.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class In2iGui {

	function display($elements,&$xmlData) {
		global $basePath;
		$skin = 'In2ition';
		$xmlData='<?xml version="1.0" encoding="ISO-8859-1"?>'.$xmlData;
		$xslData='<?xml version="1.0" encoding="ISO-8859-1"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="html" indent="no" encoding="ISO-8859-1"/>'.
		'<xsl:include href="'.$basePath.'XmlWebGui/Skins/'.$skin.'/Main.xsl"/>';
		for ($i=0;$i<sizeof($elements);$i++) { 
			$xslData.='<xsl:include href="'.$basePath.'XmlWebGui/Skins/'.$skin.'/Include/'.$elements[$i].'.xsl"/>';
		}
		$xslData.='<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
	
		if (function_exists('xslt_create')) {
			$arguments = array('/_xml' => &$xmlData,'/_xsl' => &$xslData);
			$xp = xslt_create();
			header('Content-Type: text/html; charset=iso-8859-1');
			echo xslt_process($xp, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments );
	    	xslt_free($xp);
		}
		else {
			function xslErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
				header('Content-Type: text/xml');
				echo $vars['xmlData'];
				exit;
			}
			$xslt = new xsltProcessor;
			$xslt->importStyleSheet(DomDocument::loadXML($xslData));
			header('Content-Type: text/html; charset=iso-8859-1');
			echo $xslt->transformToXML(DomDocument::loadXML($xmlData));
		}
	}

	function render(&$gui) {
		global $basePath,$baseUrl;
		$xhtml = strpos($_SERVER['HTTP_ACCEPT'],'application/xhtml+xml')!==false;
		if ($_GET['xhtml']=='false') {
			$xhtml=false;
		}
		$dev = $_GET['dev']=='true' ? 'true' : 'false';
		//$dev='true';
		$xmlData='<?xml version="1.0" encoding="UTF-8"?>'.In2iGui::localize($gui,InternalSession::getLanguage());
		$xslData='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="'.($xhtml ? 'xml' : 'html').'"/>'.
		'<xsl:variable name="dev">'.$dev.'</xsl:variable>'.
		'<xsl:variable name="profile">'.($_GET['profile']=='true' ? 'true' : 'false').'</xsl:variable>'.
		'<xsl:variable name="version">'.SystemInfo::getDate().'</xsl:variable>'.
		'<xsl:variable name="context">'.substr($baseUrl,0,-1).'</xsl:variable>'.
		'<xsl:include href="'.$basePath.'In2iGui/xslt/gui.xsl"/>';
		$xslData.='<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
	
		if (function_exists('xslt_create')) {
			$arguments = array('/_xml' => &$xmlData,'/_xsl' => &$xslData);
			$xp = xslt_create();
			header('Content-Type: '.($xhtml ? 'application/xhtml+xml' : 'text/html'));
			echo xslt_process($xp, 'arg:/_xml', 'arg:/_xsl', NULL, $arguments );
	    	xslt_free($xp);
		}
		else {
			function xslErrorHandler($errno, $errmsg, $filename, $linenum, $vars) {
				header('Content-Type: text/xml');
				echo $vars['gui'];
				exit;
			}
			header('Content-Type: '.($xhtml ? 'application/xhtml+xml' : 'text/html'));
			$xslt = new xsltProcessor;
			$xslt->importStyleSheet(DomDocument::loadXML($xslData));
			echo $xslt->transformToXML(DomDocument::loadXML($xmlData));
		}
	}
	
	function localize($xml,$language='en') {
		
		$pattern = "/({[^}]+})/mi";
		preg_match_all($pattern, $xml, $matches,PREG_OFFSET_CAPTURE);
		$diff = 0;
		for ($i=0;$i<count($matches[0]);$i++) {
			$pos = $matches[0][$i][1];
			$old = $matches[0][$i][0];
			$parts = In2iGui::extract($old);
			$new = array_key_exists($language,$parts) ? $parts[$language] : $parts['any'];
			$xml = substr_replace ( $xml , $new , $pos+$diff ,strlen($old));
			
			$diff = $diff + strlen($new)-strlen($old);
		}
		return $xml;
	}
	
	function extract($str) {
		$parsed = array();
		$str = substr($str,1,-1);
		$parts = explode(';',$str);
		foreach ($parts as $part) {
			$pos = strpos($part,':');
			if ($pos===false) {
				$parsed['any'] = trim($part);
			} else {
				$lang = trim(substr($part,0,$pos));
				$text = substr($part,$pos+1);
				$parsed[$lang] = trim($text);
			}
		}
		return $parsed;
	}
	
	function renderFragment($gui) {
		global $basePath,$baseUrl;
		$gui='<?xml version="1.0" encoding="UTF-8"?><subgui xmlns="uri:In2iGui">'.In2iGui::localize($gui).'</subgui>';
		$xsl='<?xml version="1.0" encoding="UTF-8"?>'.
		'<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">'.
		'<xsl:output method="xml"/>'.
		'<xsl:variable name="dev">false</xsl:variable>'.
		'<xsl:variable name="version">'.SystemInfo::getDate().'</xsl:variable>'.
		'<xsl:variable name="context">'.substr($baseUrl,0,-1).'</xsl:variable>'.
		'<xsl:include href="'.$basePath.'In2iGui/xslt/gui.xsl"/>'.
		'<xsl:template match="/"><xsl:apply-templates/></xsl:template>'.
		'</xsl:stylesheet>';
		$result = XslService::transform($gui,$xsl);
		$result = str_replace(
			array('<!DOCTYPE div PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">','xmlns="http://www.w3.org/1999/xhtml"','xmlns:html="http://www.w3.org/1999/xhtml"')
			,'',$result);
		return $result;
	}
	
	function sendObject($obj) {
		header('Content-Type: text/plain; charset=utf-8');
		echo In2iGui::toJSON($obj);
	}
	
	function sendUnicodeObject($obj) {
		foreach ($obj as $key => $value) {
			if (is_string($value)) {
				if (is_array($obj)) {
					$obj[$key] = Request::toUnicode($value);
				} else {
					$obj->$key = Request::toUnicode($value);
				}
			}
		}
		In2iGui::sendObject($obj);
	}
	
	function toJSON($obj) {
		global $basePath;
		if (function_exists('json_encode')) {
			return json_encode($obj);
		}
		require_once($basePath.'Editor/Libraries/json/JSON2.php');
		$json = new Services_JSON();
		return $json->encode($obj);
	}
	
	function respondSuccess() {
		header('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8"?><success/>';
	}

	function respondFailure() {
		Response::badRequest();
		header('Content-Type: text/xml');
		echo '<?xml version="1.0" encoding="UTF-8"?><failure/>';
	}

	function respondUploadSuccess() {
		header('Content-Type: text/plain');
		echo 'SUCCESS';
	}

	function respondUploadFailure() {
		Response::badRequest();
		header('Content-Type: text/plain');
		echo 'FAILURE';
	}
	
	function toDateTime($stamp) {
		return date("YmdHis",$stamp);
	}
	
	function buildOptions($objects,$selected=array()) {
		$gui='';
		foreach ($objects as $object) {
			$gui.='<option title="'.StringUtils::escapeXML($object->getTitle()).'" value="'.StringUtils::escapeXML($object->getId()).'" selected="'.(in_array($object->getId(), $selected) ? 'true' : 'false').'"/>';
		}
		return $gui;
	}
	
	function escape($input) {
		error_log('In2iGui::escape is deprecated');
		return StringUtils::escapeXML($input);
	}
	
	function escapeUnicode($input) {
		$output = str_replace('<', '&#60;', $input);
		$output = str_replace('>', '&#62;', $output);
		return $output;
	}
	
	function presentDate($timestamp) {
		if ($timestamp==null) return '';
		setlocale(LC_TIME, "da_DK");
		return strftime("%e. %b %Y",$timestamp);
	}
	
	function _xmlEntities($string) 
    { 
        $string = preg_replace('/[^\x09\x0A\x0D\x20-\x7F]/e', 'In2iGui::_privateXMLEntities("$0")', $string); 
        return $string; 
    } 

    function _privateXMLEntities($num) 
    { 
	if ($num==3) {return '';}
    $chars = array( 
        128 => '&#8364;', 
        130 => '&#8218;', 
        131 => '&#402;', 
        132 => '&#8222;', 
        133 => '&#8230;', 
        134 => '&#8224;', 
        135 => '&#8225;', 
        136 => '&#710;', 
        137 => '&#8240;', 
        138 => '&#352;', 
        139 => '&#8249;', 
        140 => '&#338;', 
        142 => '&#381;', 
        145 => '&#8216;', 
        146 => '&#8217;', 
        147 => '&#8220;', 
        148 => '&#8221;', 
        149 => '&#8226;', 
        150 => '&#8211;', 
        151 => '&#8212;', 
        152 => '&#732;', 
        153 => '&#8482;', 
        154 => '&#353;', 
        155 => '&#8250;', 
        156 => '&#339;', 
        158 => '&#382;', 
        159 => '&#376;'); 
        $num = ord($num); 
        return (($num > 127 && $num < 160) ? $chars[$num] : "&#".$num.";" ); 
    } 

	function _htmlnumericentities(&$str){
	  return preg_replace('/[^!-%\x27-;=?-~ ]/e', '"&#".ord("$0").chr(59)', $str);
	}
	
	function toLinks($links) {
		$out = array();
		foreach ($links as $link) {
			$link->toUnicode();
			$out[] = array(
				'id' => $link->getId(), 
				'text' => $link->getText(), 
				'kind' => $link->getType(), 
				'value' => $link->getValue(), 
				'info' => $link->getInfo(), 
				'icon' => $link->getIcon()
			);
		}
		return $out;
	}
	
	function fromLinks($links) {
		if (!is_array($links)) return;
		global $basePath;
		require_once($basePath.'Editor/Classes/ObjectLink.php');
		$out = array();
		foreach ($links as $link) {
			$objectLink = new ObjectLink();
			$objectLink->setText(Request::fromUnicode($link->text));
			$objectLink->setType($link->kind);
			$objectLink->setValue($link->value);
			$out[] = $objectLink;
		}
		return $out;
	}
}

class ArticlesWriter {
	function startArticles() {
		header('Content-Type: text/xml; charset=UTF-8');
		echo '<?xml version="1.0" encoding="UTF-8"?><articles>';
		return $this;
	}

	function endArticles() {
		echo '</articles>';
		return $this;
	}

	function startArticle($options=array()) {
		echo '<article value="'.StringUtils::escapeXML($options['value']).'" kind="'.$options['kind'].'">';
		return $this;
	}

	function endArticle() {
		echo '</article>';
		return $this;
	}

	function startTitle() {
		echo '<title>';
		return $this;
	}

	function endTitle() {
		echo '</title>';
		return $this;
	}
	
	function text($text) {
		echo In2iGui::escapeUnicode($text);
		return $this;
	}

	function startParagraph($options=array()) {
		echo '<paragraph';
		if ($options['dimmed']==true) {
			echo ' dimmed="true"';
		}
		echo '>';
		return $this;
	}

	function endParagraph() {
		echo '</paragraph>';
		return $this;
	}
}
?>