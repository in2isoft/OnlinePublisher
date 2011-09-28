<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Model/Object.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class Product extends Object {
	var $number;
	var $productTypeId;
	var $imageId;
	var $allowOffer;
	
	function Product() {
		parent::Object('product');
		$this->productTypeId=0;
		$this->imageId=0;
	}
	
	function setNumber($number) {
		$this->number = $number;
	}
	
	function getNumber() {
		return $this->number;
	}
	
	function setProductTypeId($id) {
		$this->productTypeId = $id;
	}
	
	function getProductTypeId() {
		return $this->productTypeId;
	}
	
	function setImageId($id) {
		$this->imageId = $id;
	}
	
	function getImageId() {
		return $this->imageId;
	}
	
	function setAllowOffer($allow) {
		$this->allowOffer = $allow;
	}
	
	function isAllowOffer() {
		return $this->allowOffer;
	}
	
	function load($id) {
		$sql = "select number,image_id,producttype_id,allow_offer from product where object_id=".$id;
		$row = Database::selectFirst($sql);
		if ($row) {
			$obj = new Product();
			$obj->_load($id);
			$obj->number=$row['number'];
			$obj->imageId=$row['image_id'];
			$obj->productTypeId=$row['producttype_id'];
			$obj->allowOffer=$row['allow_offer']==1;
			return $obj;
		} else {
			return null;
		}
	}
	
	function sub_create() {
		$sql = "insert into product (object_id,number,image_id,producttype_id,allow_offer) values (".$this->id.",".Database::text($this->number).",".$this->imageId.",".$this->productTypeId.",".Database::boolean($this->allowOffer).")";
		Database::insert($sql);
	}
	
	function sub_update() {
		$sql = "update product set ".
		"number=".Database::text($this->number).
		",image_id=".$this->imageId.
		",producttype_id=".$this->productTypeId.
		",allow_offer=".Database::boolean($this->allowOffer).
		" where object_id=".$this->id;
		Database::update($sql);
	}
	
	function sub_publish() {
		$data = '<product xmlns="'.parent::_buildnamespace('1.0').'">'.
		'<allow-offer>'.($this->allowOffer ? 'true' : 'false').'</allow-offer>'.
		'<number>'.StringUtils::escapeXML($this->number).'</number>'.
		'<attributes>';
		$sql="select * from productattribute where product_id=".$this->id." order by `index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$data.='<attribute name="'.StringUtils::escapeXML($row['name']).'">'.
			StringUtils::escapeXMLBreak($row['value'],'<break/>').
			'</attribute>';
		}
		Database::free($result);
		$data.='</attributes>'.
		'<prices>';
		$sql="select * from productprice where product_id=".$this->id." order by `index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$data.='<price'.
				' amount="'.StringUtils::escapeXML($row['amount']).'"'.
				' type="'.StringUtils::escapeXML($row['type']).'"'.
				' price="'.StringUtils::escapeXML($row['price']).'"'.
				' currency="'.StringUtils::escapeXML($row['currency']).'"'.
				'/>';
		}
		Database::free($result);
		$data.='</prices>';
		$data.= Object::getObjectData($this->imageId);
		$data.='</product>';
		return $data;
	}
	
	function sub_remove() {
		$sql="delete from productgroup_product where product_id=".$this->id;
		Database::delete($sql);
		$sql="delete from productattribute where product_id=".$this->id;
		Database::delete($sql);
		$sql="delete from productprice where product_id=".$this->id;
		Database::delete($sql);
		$sql="delete from productoffer where product_id=".$this->id;
		Database::delete($sql);
		$sql = "delete from product where object_id=".$this->id;
		Database::delete($sql);
	}
	
	/////////////////////////////// Special ///////////////////////
	
	function getAttributes() {
		$atts = array();
		$sql="select * from productattribute where product_id=".$this->id." order by `index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$atts[] = array('name' => $row['name'],'value' => $row['value']);
		}
		Database::free($result);
		return $atts;
	}
	
	function getPrices() {
		$atts = array();
		$sql="select * from productprice where product_id=".$this->id." order by `index`";
		$result = Database::select($sql);
		while ($row = Database::next($result)) {
			$atts[] = array('amount' => $row['amount'],'type' => $row['type'],'price' => floatval($row['price']),'currency' => $row['currency']);
		}
		Database::free($result);
		return $atts;
	}
	
	function updateGroupIds($ids) {
		$ids = Object::getValidIds($ids);
		$sql = "delete from productgroup_product where product_id=".$this->id;
		Database::delete($sql);
		foreach ($ids as $id) {
			$sql = "insert into productgroup_product (productgroup_id,product_id) values (".$id.",".$this->id.")";
			Database::insert($sql);
		}
	}
	
	function getGroupIds() {
		$sql = "select productgroup_id as id from productgroup_product where product_id=".$this->id;
		return Database::getIds($sql);
	}
	
	function updateAttributes($attributes) {
		$sql = "delete from productattribute where product_id=".$this->id;
		Database::delete($sql);
		for ($i=0;$i<count($attributes);$i++) {
			$att = $attributes[$i];
			$sql = "insert into productattribute (product_id,`name`,`value`,`index`) values (".$this->id.",".Database::text($att['name']).",".Database::text($att['value']).",".$i.")";
			Database::insert($sql);			
		}
	}
	
	function updatePrices($prices) {
		$sql = "delete from productprice where product_id=".$this->id;
		Database::delete($sql);
		for ($i=0;$i<count($prices);$i++) {
			$att = $prices[$i];
			$sql = "insert into productprice (product_id,`amount`,`type`,price,currency,`index`) values (".
			$this->id.",".
			Database::float($att['amount']).",".
			Database::text($att['type']).",".
			Database::float($att['price']).",".
			Database::text($att['currency']).",".
			$i.
			")";
			Database::insert($sql);			
		}
	}

    function find($query = array()) {
    	$parts = array();
		$parts['columns'] = 'object.id';
		$parts['tables'] = 'product,object';
		$parts['limits'] = 'object.id=product.object_id';
		$parts['ordering'] = 'object.title';
		if (isset($query['productgroup'])) {
			$parts['tables'] .= ',productgroup_product';
		}
		if (isset($query['producttype'])) {
			$parts['limits'] .= " and product.producttype_id=".$query['producttype'];
		}
		if (isset($query['productgroup'])) {
			$parts['limits'] .= " and productgroup_product.product_id = object.id and productgroup_product.productgroup_id=".$query['productgroup'];
		}
		$list = ObjectService::_find($parts,$query);
		$list['result'] = array();
		foreach ($list['rows'] as $row) {
			$list['result'][] = Product::load($row['id']);
		}
		return $list;
	}
	
	/////////////////////////// GUI /////////////////////////
	
	function getIcon() {
	    return 'Element/Product';
	}
	
	function getIn2iGuiIcon() {
		return 'common/product';
	}
}
?>