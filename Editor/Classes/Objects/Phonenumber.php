<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
require_once($basePath.'Editor/Classes/Object.php');

Object::$schema['phonenumber'] = array(
	'number' => array('type'=>'string'),
	'context' => array('type'=>'string'),
	'containingObjectId'   => array('type'=>'int','column'=>'containing_object_id')
);
class Phonenumber extends Object {
	var $number;
	var $context;
	var $containingObjectId=0;

	function Phonenumber() {
		parent::Object('phonenumber');
	}

	function load($id) {
		return Object::get($id,'phonenumber');
	}

	function toUnicode() {
		parent::toUnicode();
		$this->number = mb_convert_encoding($this->number, "UTF-8","ISO-8859-1");
	}
	
	function setNumber($number) {
		$this->title = $number;
		$this->number = $number;
	}

	function getNumber() {
		return $this->number;
	}
	
	function setContext($context) {
		$this->context = $context;
	}

	function getContext() {
		return $this->context;
	}

	function setContainingObjectId($id) {
		$this->containingObjectId = $id;
	}

	function getContainingObjectId() {
		return $this->containingObjectId;
	}

    /////////////////////////// Persistence ////////////////////////

	function sub_publish() {
		$data =
		'<phonenumber xmlns="'.parent::_buildnamespace('1.0').'">'.
		'</phonenumber>';
		return $data;
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Element/PhoneNumber';
	}
	
	function getIn2iGuiIcon() {
		return "common/phone";
	}
}
?>