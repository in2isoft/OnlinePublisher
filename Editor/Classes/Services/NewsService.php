<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}

class NewsService {
	
	static function synchronizeSource($id,$force=false) {
		// TODO: Dont remove old items, only update existing
		$source = Newssource::load($id);
		if (!$source) return;
		if ($source->isInSync() && $force==false) {
			return;
		}
		NewsService::clearSource($id);
		$data = RemoteDataService::getRemoteData($source->getUrl(),30);
		if ($data->isHasData()) {
			$parser = new FeedParser();
			$feed = $parser->parseURL($data->getFile());
			if ($feed) {
				$items = $feed->getItems();
				foreach ($items as $item) {
					$srcItem = new Newssourceitem();
					$srcItem->setTitle(trim($item->getTitle()));
					$srcItem->setText(trim($item->getDescription()));
					$srcItem->setNewssourceId($source->getId());
					$srcItem->setGuid($item->getGuid());
					$srcItem->setDate($item->getPubDate());
					$srcItem->setUrl($item->getLink());
					$srcItem->save();
					$srcItem->publish();
				}
			}
		}
		$sql = 'update newssource set synchronized=now() where object_id='.Database::int($id);
		Database::update($sql);
	}
	
	static function clearSource($id) {
		$items = Query::after('newssourceitem')->withProperty('newssource_id',$id)->get();
		foreach ($items as $item) {
			$item->remove();
		}
	}
	
	static function createArticle($article) {
		$blueprint = Pageblueprint::load($article->getPageBlueprintId());
		if (!$blueprint) {
			Log::debug('Unable to load blueprint with id='.$article->getPageBlueprintId());
			return;
		}
		$page = new Page();
		$page->setTemplateId($blueprint->getTemplateId());
		$page->setDesignId($blueprint->getDesignId());
		$page->setFrameId($blueprint->getFrameId());
		$page->setTitle($article->getTitle());
		$page->save();
		
		$news = new News();
		$news->setTitle($article->getTitle());
		$news->setNote($article->getSummary());
		$news->setStartdate($article->getStartdate());
		$news->setEnddate($article->getEnddate());
		$news->save();
		$news->updateGroupIds($article->getGroupIds());
		
		$header = new HeaderPart();
		$header->setLevel(1);
		$header->setText($article->getTitle());
		$header->save();
		DocumentTemplateEditor::addPartAtEnd($page->getId(),$header);

		$text = new TextPart();
		$text->setText($article->getText());
		$text->save();
		DocumentTemplateEditor::addPartAtEnd($page->getId(),$text);
		
		$page->publish();
		
		ObjectLinkService::addPageLink($news,$page,$article->getLinkText());
		
		return array('page' => $page, 'news' => $news);
	}
}