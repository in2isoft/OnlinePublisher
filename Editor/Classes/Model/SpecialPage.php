<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Model
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Services/CacheService.php');

class SpecialPage {
        
	var $id;
	var $pageId;
	var $language;
	var $type;

    function SpecialPage() {
    }

	function setId($id) {
	    $this->id = $id;
	}

	function getId() {
	    return $this->id;
	}
	
	function setPageId($pageId) {
	    $this->pageId = $pageId;
	}

	function getPageId() {
	    return $this->pageId;
	}
	
	function setLanguage($language) {
	    $this->language = $language;
	}

	function getLanguage() {
	    return $this->language;
	}
	
	function setType($type) {
	    $this->type = $type;
	}

	function getType() {
	    return $this->type;
	}
	

	function search() {
		$list = array();
		
		$sql = "select * from specialpage order by `type`,language,id";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$item = new SpecialPage();
			$item->setId($row['id']);
			$item->setPageId(intval($row['page_id']));
			$item->setType($row['type']);
			$item->setLanguage($row['language']);
			$list[] = $item;
		}
		Database::free($result);
		return $list;
	}
	

	function load($id) {
		$sql = "select * from specialpage where id=".Database::int($id);
		if ($row = Database::selectFirst($sql)) {
			$item = new SpecialPage();
			$item->setId($row['id']);
			$item->setPageId(intval($row['page_id']));
			$item->setType($row['type']);
			$item->setLanguage($row['language']);
			return $item;
		}
		return null;
	}
	
	function remove() {
		$sql = "delete from specialpage where id = ".Database::int($this->id);
		$result = Database::delete($sql);
		CacheService::clearCompletePageCache();
		return $result;
	}
	
	function save() {
		if ($this->id>0) {
			$sql="update specialpage set".
			" `type`=".Database::text($this->type).
			",language=".Database::text($this->language).
			",page_id=".Database::int($this->pageId).
			" where id=".Database::int($this->id);
			Database::update($sql);
		} else {
			$sql="insert into specialpage (`type`,language,page_id) values (".
			Database::text($this->type).",".
			Database::text($this->language).",".
			Database::int($this->pageId).
			")";
			$this->id = Database::insert($sql);
		}
		CacheService::clearCompletePageCache();
	}
}