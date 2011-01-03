<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Projects
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Database.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/PageBlueprint.php';

$id = Request::getInt('id');

$blueprint = PageBlueprint::load($id);
$blueprint->remove();

Response::redirect('Blueprints.php');
?>