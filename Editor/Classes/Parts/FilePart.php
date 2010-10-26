<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
require_once($basePath.'Editor/Classes/Parts/Part.php');

Part::$schema['file'] = array(
	'fields' => array(
		'fileId' => array( 'type' => 'int', 'column' => 'file_id' )
	)
);

class FilePart extends Part
{
	var $fileId;
	
	function FilePart() {
		parent::Part('file');
	}
	
	function load($id) {
		return Part::load('file',$id);
	}
	
	function setFileId($fileId) {
	    $this->fileId = $fileId;
	}

	function getFileId() {
	    return $this->fileId;
	}
	
}
?>