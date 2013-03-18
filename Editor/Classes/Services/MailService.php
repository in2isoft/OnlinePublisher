<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Libraries/Zend.php');
require_once($basePath.'Editor/Libraries/Zend/Mail.php');
require_once($basePath.'Editor/Libraries/Zend/Mail/Transport/Smtp.php');

class MailService {
	
	function sendToStandard($subject,$body) {
		return MailService::send(MailService::getStandardEmail(), MailService::getStandardName(),$subject,$body);
	}
	
	function sendToFeedback($subject,$body) {
		return MailService::send(MailService::getFeedbackEmail(), MailService::getFeedbackName(),$subject,$body);
	}
	
	function send($email,$name,$subject,$body,$html=null) {
		$username = MailService::getUsername();
		$password = MailService::getPassword();
		$port = MailService::getPort();
		if (strlen($username)>0 && strlen($password)>0) {
			$config = array('auth' => 'login', 'username' => $username, 'password' => $password, 'ssl' => 'tls');
			if ($port) {
				$config['port'] = $port;
			}
			if (MailService::getServer()=='smtp.gmail.com') {
				$config['ssl']='ssl';
				$config['port']='465';
			}
			// 'ssl' => 'ssl', 'port' => '995'
			Log::debug('Sending mail with config...');
			Log::debug($config);
			$tr = new Zend_Mail_Transport_Smtp(MailService::getServer(),$config);
			Zend_Mail::setDefaultTransport($tr);
		}

		$mail = new Zend_Mail('UTF-8');
		$mail->setBodyText($body);
		if ($html!=null) {
			$mail->setBodyHtml($html);
		}
		$mail->setFrom(MailService::getStandardEmail(), MailService::getStandardName());
		$mail->addTo($email, $name);
		$mail->setSubject($subject);
		try {
			$mail->send();
			return true;
		} catch (Zend_Exception $e) {  
			Log::debug($e->getMessage());
			return false;
		}
		
	}
	
	function getEnabled() {
		return SettingService::getSetting('system','mail','enabled')=='true';
	}
	
	function setEnabled($value) {
		SettingService::setSetting('system','mail','enabled',$value ? 'true' : 'false');
	}
	
	function getServer() {
		return SettingService::getSetting('system','mail','server');
	}
	
	function setServer($value) {
		SettingService::setSetting('system','mail','server',$value);
	}
	
	function getPort() {
		return SettingService::getSetting('system','mail','port');
	}
	
	function setPort($value) {
		SettingService::setSetting('system','mail','port',$value);
	}
	
	function getUsername() {
		return SettingService::getSetting('system','mail','username');
	}
	
	function setUsername($value) {
		SettingService::setSetting('system','mail','username',$value);
	}
	
	function getPassword() {
		return SettingService::getSetting('system','mail','password');
	}
	
	function setPassword($value) {
		SettingService::setSetting('system','mail','password',$value);
	}
	
	function getStandardEmail() {
		return SettingService::getSetting('system','mail','standard-email');
	}
	
	function setStandardEmail($value) {
		SettingService::setSetting('system','mail','standard-email',$value);
	}
	
	function getStandardName() {
		return SettingService::getSetting('system','mail','standard-name');
	}
	
	function setStandardName($value) {
		SettingService::setSetting('system','mail','standard-name',$value);
	}
	
	function getFeedbackEmail() {
		return SettingService::getSetting('system','mail','feedback-email');
	}
	
	function setFeedbackEmail($value) {
		SettingService::setSetting('system','mail','feedback-email',$value);
	}
	
	function getFeedbackName() {
		return SettingService::getSetting('system','mail','feedback-name');
	}
	
	function setFeedbackName($value) {
		SettingService::setSetting('system','mail','feedback-name',$value);
	}
}