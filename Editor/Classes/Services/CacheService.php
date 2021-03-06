<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class CacheService {

	static function clearPageCache($id) {
		$sql = "delete from page_cache where page_id=".Database::int($id);
		Database::delete($sql);
	}

	static function getNumberOfCachedPages() {
		$sql = "select count(page_id) as num from page_cache";
		$row = Database::selectFirst($sql);
		return intval($row['num']);
	}

	static function clearCompletePageCache() {
		$sql = "delete from page_cache";
		Database::delete($sql);
	}

	static function sendCachedPage($id,$path) {
    if (!ConfigurationService::isCachePages()) {
  		return false;
    }
		if (Request::getBoolean('viewsource') || Request::getBoolean('mini') || Request::getString('design') || @$_SESSION['debug.design']) {
			return false;
		}
		$sql = "select page_cache.html,UNIX_TIMESTAMP(page.published) as published from page_cache,page,frame where page.secure=0 and page.dynamic=0 and page.id=page_cache.page_id and page.frame_id=frame.id and frame.dynamic=0";
		$sql.= " and page_cache.version=".Database::int(ConfigurationService::getDeploymentTime());
    if ($id > 0) {
			$sql.=" and page.id=".Database::int($id);
		} else {
			$sql.=" and page_cache.path = " . Database::text($_SERVER['REQUEST_URI']);
		}
		if ($row = Database::selectFirst($sql)) {
			header("Last-Modified: " . gmdate("D, d M Y H:i:s",$row['published']) . " GMT");
			header("Cache-Control: public");
			header("Expires: " . gmdate("D, d M Y H:i:s",time()+604800) . " GMT");
			header("Content-Type: text/html; charset=UTF-8");
			header("X-Humanise-Editor-Cache: true");
			echo $row['html'];
			return true;
		}
		return false;
	}

	static function createPageCache($id,$path,$html) {
    if (!ConfigurationService::isCachePages()) {
  		return false;
    }
		if (Request::getBoolean('viewsource') || Request::getString('design') || @$_SESSION['debug.design']) {
			return;
		}
		$html = Database::text($html);
		if (strlen($html)>49900) {
			return; // Be sure not to cache incomplete html
		}
		$sql = "delete from page_cache where page_id=".Database::int($id);
		if (strlen($path)>0) {
			$sql.=" and page_cache.path=".Database::text($path);
		}
		Database::delete($sql);
		$sql = "insert into page_cache (page_id,path,html,stamp,version) values (".Database::int($id).",".Database::text($_SERVER['REQUEST_URI']).",".$html.",now(),".Database::int(ConfigurationService::getDeploymentTime()).")";
		Database::insert($sql);
	}

	////// Images //////

  static function clearCompleteImageCache() {
    global $basePath;
    $dir = $basePath.'local/cache/images/';
    $files = FileSystemService::listFiles($dir);
    foreach ($files as $file) {
      @unlink($dir.$file);
    }
  }

  static function getImageCacheInfo() {
    global $basePath;
    $dir = $basePath.'local/cache/images/';
    $files = FileSystemService::listFiles($dir);
		$info = array('count'=>count($files),'size'=>0);
    foreach ($files as $file) {
      $info['size']+=filesize($dir.$file);
    }
		return $info;
  }

	////// Temp //////

  static function clearCompleteTempCache() {
    global $basePath;
    $dir = $basePath.'local/cache/temp/';
    $files = FileSystemService::listFiles($dir);
    foreach ($files as $file) {
      @unlink($dir.$file);
    }
  }

  static function getTempCacheInfo() {
    global $basePath;
    $dir = $basePath.'local/cache/temp/';
    $files = FileSystemService::listFiles($dir);
		$info = array('count'=>count($files),'size'=>0);
    foreach ($files as $file) {
      $info['size']+=filesize($dir.$file);
    }
		return $info;
  }

	////// URLs //////

  static function clearCompleteUrlCache() {
    global $basePath;
    $dir = $basePath.'local/cache/urls/';
    $files = FileSystemService::listFiles($dir);
    foreach ($files as $file) {
      @unlink($dir.$file);
    }
  }

  static function getUrlCacheInfo() {
    global $basePath;
    $dir = $basePath.'local/cache/urls/';
    $files = FileSystemService::listFiles($dir);
		$info = array('count'=>count($files),'size'=>0);
    foreach ($files as $file) {
      $info['size']+=filesize($dir.$file);
    }
		return $info;
  }
}