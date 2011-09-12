<?
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class TemporaryFolder {

	var $dir;
	
	function TemporaryFolder() {
	}
	
	function make() {
		global $basePath;
		$this->dir = $basePath.'local/cache/temp/'.time();
		if (mkdir($this->dir)) {
			return $this->dir.'/';
		} else {
			return false;
		}
	}
	
	function remove() {
		return rmdir($this->dir);
	}
}
?>