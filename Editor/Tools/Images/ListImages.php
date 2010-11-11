<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Classes/In2iGui.php';
require_once '../../Classes/Image.php';
require_once '../../Classes/Services/FileService.php';
require_once '../../Classes/Request.php';
require_once '../../Classes/UserInterface.php';
require_once '../../Classes/GuiUtils.php';
require_once '../../Classes/DateUtil.php';
require_once '../../Classes/Log.php';

$main = Request::getString('main');
$group = Request::getInt('group');
$type = Request::getString('type');
$queryString = Request::getUnicodeString('query');
$windowSize = Request::getInt('windowSize',30);
$windowPage = Request::getInt('windowPage',0);
$sort = Request::getString('sort');
$direction = Request::getString('direction');
if ($sort=='') $sort='title';
if ($direction=='') $direction='ascending';

$query = array('windowSize' => $windowSize,'windowPage' => $windowPage,'sort' => $sort,'direction' => $direction);

//if ($type!='') $query['type'] = $type;
if ($queryString!='') $query['query'] = $queryString;

if ($group>0) {
	$query['filegroup'] = $group;
}
if ($type) {
	$query['mimetypes'] = FileService::kindToMimeTypes($type);
}
if ($main=='latest') {
	$query['createdMin']=DateUtil::addDays(mktime(),-1);
}


$list = Image::search();
$objects = $list;

$writer = new ListWriter();

$writer->startList();
$writer->sort($sort,$direction);
$writer->window(array( 'total' => $list['total'], 'size' => $windowSize, 'page' => $windowPage ));
$writer->startHeaders();
$writer->header(array('title'=>'Titel','width'=>40));
$writer->header(array('title'=>'Type'));
$writer->header(array('title'=>'Størrelse'));
$writer->header(array('title'=>'Ændringsdato'));
$writer->endHeaders();

foreach ($objects as $object) {
	$writer->
	startRow(array('kind'=>'file','id'=>$object->getId(),'icon'=>$object->getIn2iGuiIcon(),'title'=>$object->getTitle()))->
		startCell(array('icon'=>$object->getIn2iGuiIcon()))->
			startLine()->text($object->getTitle())->endLine()->
		endCell()->
		startCell()->
			startLine()->text(FileService::mimeTypeToLabel($object->getMimeType()))->endLine()->
			//startLine(array('dimmed'=>true))->text($object->getFilename())->endLine()->
		endCell()->
		startCell()->text(GuiUtils::bytesToString($object->getSize()))->endCell()->
		startCell()->text(UserInterface::presentDateTime($object->getCreated()))->endCell()->
	endRow();
}
$writer->endList();
?>