<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class ToolService {

	static function getInstalled() {
		$arr = array();
		$sql = "select id,`unique` from `tool`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$arr[] = $row['unique'];
		}
		Database::free($result);
		return $arr;
	}
	
	static function getAvailable() {
		global $basePath;
		$arr = FileSystemService::listDirs($basePath."Editor/Tools/");
		for ($i=0;$i<count($arr);$i++) {
			if (substr($arr[$i],0,3)=='CVS') {
				unset($arr[$i]);
			}
		}
		return $arr;
	}
	
	static function getCategorized() {
		$categorized = array();
		$installed = ToolService::getInstalled();
		foreach ($installed as $key) {
			$info = ToolService::getInfo($key);
			if ($info) {
				if (!isset($categorized[$info->category])) {
					$categorized[$info->category] = array();
				}
				$categorized[$info->category][$key] = $info;
			} else {
				Log::warn('Tool not found: '.$key);
			}
		}
		foreach ($categorized as $key => &$value) {
			usort($value,array('ToolService','_priorityComparator'));
		}
		return $categorized;
	}
	
	static function _priorityComparator($toolA, $toolB) {
		$a = $toolA->priority;
		$b = $toolB->priority;
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	}

	static function getInfo($key) {
		global $basePath;
		$path = $basePath."Editor/Tools/".$key."/info.json";
		return Strings::toUnicode(JsonService::readFile($path));
	}
	
	static function install($key) {
		$sql = "select id from `tool` where `unique`=".Database::text($key);
		if (Database::isEmpty($sql)) {
			$sql="insert into tool (`unique`) values (".Database::text($key).")";
			Database::insert($sql);
		}
	}
	
	static function uninstall($key) {
		$sql = "delete from `tool` where `unique`=".Database::text($key);
		Database::delete($sql);
	}
}