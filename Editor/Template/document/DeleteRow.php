<?php
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once '../../Classes/InternalSession.php';
require_once 'Functions.php';

$pageId = InternalSession::getPageId();
$rowId = requestGetNumber('row',0);

$sql="select * from document_row where id=".$rowId;
$row = Database::selectFirst($sql);
$index=$row['index'];

$latestRow=0;

$sql="select * from document_row where page_id=".$pageId." and `index`>".$index;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$sql="update document_row set `index`=".($row['index']-1)." where id=".$row['id'];
	Database::update($sql);
	$latestRow=$row['id'];
}
Database::free($result);

//$sql="select document_section.*,part.type as part_type from document_section,document_column left join part on part.id=document_section.part_id where document_section.column_id=document_column.id and document_column.row_id=".$rowId;
$sql="select document_section.*,part.type as part_type from document_section left join part on part.id=document_section.part_id left join document_column on document_section.column_id=document_column.id where document_column.row_id=".$rowId;
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$type=$row['type'];
	$sectionId=$row['id'];
	$partId=$row['part_id'];
	$partType=$row['part_type'];
	if ($type=='part') {
		if ($part = Part::load($partType,$partId)) {
			$part->remove();
		}
	}
	$sql="delete from document_section where id=".$sectionId;
	Database::delete($sql);
}
Database::free($result);

$sql="delete from document_column where row_id=".$rowId;
Database::delete($sql);

$sql="delete from document_row where id=".$rowId;
Database::delete($sql);

$sql="update page set changed=now() where id=".$pageId;
Database::update($sql);


setDocumentColumn(0);
setDocumentSection(0);
redirect('Editor.php');
?>