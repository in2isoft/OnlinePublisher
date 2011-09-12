<?
/**
 * @package OnlinePublisher
 * @subpackage Templates.Document
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
if (($event=='publish' || $event=='delete') && $type=='object' && $subType=='image') {
	$sql = "select distinct page_id from document_section,part_image where document_section.part_id=part_image.part_id and part_image.image_id=".$id;
	$result = Database::select($sql);
	while ($row=Database::next($result)) {
		$sql = "update page set changed=now() where id=".$row['page_id'];
		Database::update($sql);
	}
	Database::free($result);
} elseif (($event=='publish' || $event=='delete') && $type=='object' && $subType=='person') {
	$sql = "select distinct page_id from document_section,part_person where document_section.part_id=part_person.part_id and part_person.person_id=".$id;
	$result = Database::select($sql);
	while ($row=Database::next($result)) {
		$sql = "update page set changed=now() where id=".$row['page_id'];
		Database::update($sql);
	}
	Database::free($result);
} elseif (($event=='publish' || $event=='delete' || $event=='update') && $type=='object' && $subType=='file') {
	$sql = "select distinct page_id from document_section,part_file where document_section.part_id=part_file.part_id and part_file.file_id=".$id;
	$result = Database::select($sql);
	while ($row=Database::next($result)) {
		$sql = "update page set changed=now() where id=".$row['page_id'];
		Database::update($sql);
	}
	Database::free($result);
}
?>