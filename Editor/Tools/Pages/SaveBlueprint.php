<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Pages
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Pageblueprint.php';
require_once '../../Classes/Request.php';

$id = Request::getInt('id');
$title = Request::getString('title');
$frame = Request::getInt('frame');
$design = Request::getInt('design');
$template = Request::getInt('template');

if ($id>0) {
	$blueprint = PageBlueprint::load($id);
} else {
	$blueprint = new PageBlueprint();
}
$blueprint->setTitle($title);
$blueprint->setFrameId($frame);
$blueprint->setDesignId($design);
$blueprint->setTemplateId($template);
$blueprint->save();
$blueprint->publish();

Response::redirect('Blueprints.php');
?>