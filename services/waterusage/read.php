<?php
require_once('../../Editor/Include/Public.php');

$number = Request::getString('number');

$year = 2010-1;
$sql = "select DATE_FORMAT(date, '%d-%m-%Y') as `date`,`value` from waterusage where year=".Database::int($year)." and number = ".Database::text($number);
$row = Database::selectFirst($sql);

header('Content-Type: text/xml;');
echo '<?xml version="1.0"?>'.
'<result>'.
'<value>'.$row['value'].'</value>'.
'<date>'.$row['date'].'</date>'.
'</result>';
?>