<?php
/**
 * @package OnlinePublisher
 * @subpackage Services.Start
 */
require_once '../../../Include/Private.php';

$url = 'http://www.humanise.dk/services/news/rss/?group=373';

$data = RemoteDataService::getRemoteData($url,60*30); // 30 minutes
if (!$data->isHasData()) {
	Response::badGateway();
	exit;	
}
$parser = new FeedParser();
$feed = $parser->parseURL($data->getFile());

if (!$feed) {
	Response::badGateway();
	exit;	
}

$writer = new ListWriter();

if (ConfigurationService::isUnicode()) {
  //Strings::toUnicode($feed);
}

$writer->startList(array('unicode'=>true));

foreach($feed->getItems() as $item) {
	$writer->startRow()->
		startCell(array('class'=>'news'))->startLine()->startStrong()->text($item->getTitle())->endStrong()->endLine()->
			startLine(array('minor'=>true))->text($item->getDescription())->endline()->
			startLine(array('dimmed'=>true,'mini'=>true))->text(Dates::formatFuzzy($item->getPubDate()))->endLine()->
		endCell()->
		startCell(array('class'=>'news'));
		if (Strings::isNotBlank($item->getLink())) {
			$writer->startIcons()->
				icon(array('icon'=>'monochrome/round_arrow_right','action'=>true,'revealing'=>true,'data'=>array('url'=>$item->getLink())))->
			endIcons();
			//$writer->button(array('text'=>Strings::fromUnicode('Læs'),'data'=>array('url'=>$item->getLink())));
		}
		$writer->endCell()->
	endRow();
		
}
$writer->endList();
?>