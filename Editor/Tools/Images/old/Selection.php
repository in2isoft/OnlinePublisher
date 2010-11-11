<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Images
 */
require_once '../../../Config/Setup.php';
require_once '../../Include/Security.php';
require_once '../../Include/Functions.php';
require_once '../../Include/XmlWebGui.php';
require_once 'ImagesController.php';

$value = ImagesController::getViewType();
if ($value=='group') {
	$value=ImagesController::getGroupId();
}

$sql = "select count(object_id) as `count` from image";
$row = Database::selectFirst($sql);
$totalCount = $row['count'];
$sql = "select count(object_id) as `count` from object,image left join imagegroup_image on imagegroup_image.image_id=image.object_id where object.id = image.object_id and imagegroup_image.imagegroup_id is null";
$row = Database::selectFirst($sql);
$noGroupCount = $row['count'];
$notUsedCount = ImagesController::getUnusedImagesCount();

$gui='<xmlwebgui xmlns="uri:XmlWebGui"><configuration path="../../../"/>'.
'<interface>'.
'<selection xmlns="uri:Selection" object="Selection" value="'.$value.'">'.
'<item icon="Tool/Images" title="Alle billeder" value="all" badge="'.$totalCount.'"/>'.
'<item icon="Basic/Time" title="Seneste billeder" value="lastadded"/>'.
'<item icon="Element/Album" title="Oversigt over grupper" value="groups"/>'.
'<title>Grupper</title>';
$sql="select object.id,object.title,object.note,count(image.object_id) as imagecount,sum(image.size) as totalsize from imagegroup, imagegroup_image, image,object  where imagegroup_image.imagegroup_id=imagegroup.object_id and imagegroup_image.image_id = image.object_id and object.id=imagegroup.object_id group by imagegroup.object_id union select object.id,object.title,object.note,'0','0' from object left join imagegroup_image on imagegroup_image.imagegroup_id=object.id where object.type='imagegroup' and imagegroup_image.image_id is null order by title";
$result = Database::select($sql);
while ($row = Database::next($result)) {
	$gui.='<item icon="Element/Album" title="'.encodeXML(shortenString($row['title'],20)).'" value="'.$row['id'].'" badge="'.$row['imagecount'].'"/>';
}
Database::free($result);

$gui.=
'<title>Oprydning</title>'.
'<item icon="Basic/Warning" title="Billeder ikke i gruppe" value="nogroup" badge="'.$noGroupCount.'"/>'.
'<item icon="Basic/Stop" title="Billeder ikke i brug" value="notused" badge="'.$notUsedCount.'"/>'.
'</selection>'.
'<refresh xmlns="uri:Script" source="HierarchyUpdateCheck.php" interval="2000"/>'.
'<script xmlns="uri:Script">
var delegate = {
    valueDidChange : function(event,obj) {
        var link = "";
        if (obj.getValue()=="all") {
            link="Library.php";
        } else if (obj.getValue()=="nogroup") {
            link="NoGroup.php";
        } else if (obj.getValue()=="notused") {
            link="NotUsed.php";
        } else if (obj.getValue()=="groups") {
            link="Groups.php";
		} else if (obj.getValue()=="lastadded") {
            link="LastAdded.php?id="+obj.getValue();
        } else {
            link="Group.php?id="+obj.getValue();
		}
		parent.frames["Right"].location.href = link;
    }
};
Selection.setDelegate(delegate);
</script>'.
'</interface>'.
'</xmlwebgui>';

$elements = array("Selection","Script");
writeGui($xwg_skin,$elements,$gui);
?>