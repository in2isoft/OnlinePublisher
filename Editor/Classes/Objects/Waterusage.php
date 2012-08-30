<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

Object::$schema['waterusage'] = array(
	'watermeterId'   => array('type'=>'int','column'=>'watermeter_id'),
	'value'  => array('type'=>'int'),
	'date'  => array('type'=>'datetime'),
	'status'  => array('type'=>'int'),
	'source'  => array('type'=>'int')
);
class Waterusage extends Object {
	
	static $ADMIN = 0;
	static $IMPORT = 1;
	static $CLIENT = 2;
	
	static $UNKNOWN = 0;
	static $VALIDATED = 1;
	static $REJECTED = -1;
	
	var $watermeterId;
	var $value;
	var $date;
	var $status;
	var $source;

	function Waterusage() {
		parent::Object('waterusage');
	}

	function load($id) {
		return Object::get($id,'waterusage');
	}
	
	function getIcon() {
		return "common/water";
	}
	
	function setWatermeterId($watermeterId) {
	    $this->watermeterId = $watermeterId;
	}

	function getWatermeterId() {
	    return $this->watermeterId;
	}
	
	function setValue($value) {
	    $this->value = $value;
		$this->title = $value;
	}

	function getValue() {
	    return $this->value;
	}
	
	function setDate($date) {
	    $this->date = $date;
	}

	function getDate() {
	    return $this->date;
	}
	
	function setStatus($status) {
	    $this->status = $status;
	}

	function getStatus() {
	    return $this->status;
	}
	
	function setSource($source) {
	    $this->source = $source;
	}

	function getSource() {
	    return $this->source;
	}
	
	
	function sub_index() {
		$words = array($this->number);
		if ($meter = Watermeter::load($this->watermeterId)) {
			return $meter->getNumber();
		}
		return StringUtils::buildIndex($words);
	}
}
?>