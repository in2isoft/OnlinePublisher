<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['testphrase'] = array();
class Testphrase extends Object {

	function Testphrase() {
		parent::Object('testphrase');
	}
	
	function load($id) {
		return Object::get($id,'testphrase');
	}
		
	/////////////////////////// GUI /////////////////////////
	
	function getIn2iGuiIcon() {
	    return 'monochrome/globe';
	}
}
?>