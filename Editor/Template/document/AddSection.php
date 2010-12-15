<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/Response.php';
require_once '../../Classes/Templates/DocumentTemplateEditor.php';

$columnId = Request::getInt('column');
$index = Request::getInt('index');
$part = Request::getString('part');

$sectionId = DocumentTemplateEditor::addSection($columnId,$index,$part);

Response::redirect('Editor.php?section='.$sectionId);
?>