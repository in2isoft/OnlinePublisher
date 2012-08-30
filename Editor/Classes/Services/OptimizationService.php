<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class OptimizationService {
	
	function addControlWord($word) {
		
	}
	
	function getSettings() {
		$value = SettingService::getSetting('system','optimization','settings');
		$value = StringUtils::fromJSON($value);
		$value = StringUtils::fromUnicode($value);
		return $value;
	}
	
	function setSettings($value) {
		$value = StringUtils::toUnicode($value);
		$value = StringUtils::toJSON($value);
		SettingService::setSetting('system','optimization','settings',$value);
	}
}