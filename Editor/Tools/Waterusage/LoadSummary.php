<?php
/**
 * @package OnlinePublisher
 * @subpackage Tools.Waterusage
 */
require_once '../../Include/Private.php';

$data = Request::getObject('data');

$summary = WaterusageService::getSummaryById($data->id);

Response::sendObject($summary);
?>