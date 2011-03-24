<?php
/**
 * @package OnlinePublisher
 * @subpackage Tests.Model
 */

class TestCalendarsource extends AbstractObjectTest {
    
	function TestCalendarsource() {
		parent::AbstractObjectTest('calendarsource');
	}

	function testProperties() {
		$obj = new Calendarsource();
		$obj->setTitle('My source');
		$obj->save();
		
		$loaded = Calendarsource::load($obj->getId());
		$this->assertEqual($loaded->getTitle(),'My source');
		
		$loaded->remove();
	}
}
?>