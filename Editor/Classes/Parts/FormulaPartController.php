<?php
/**
 * @package OnlinePublisher
 * @subpackage Classes.Part
 */
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
require_once($basePath.'Editor/Classes/Parts/PartController.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');

class FormulaPartController extends PartController
{
	function FormulaPartController() {
		parent::PartController('formula');
	}
	
	function createPart() {
		$part = new FormulaPart();
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function editor($part,$context) {
		global $baseUrl;
		return
		$this->buildHiddenFields(array(
			"receiverName" => $part->getReceiverName(),
			"receiverEmail" => $part->getReceiverEmail(),
			"recipe" => $part->getRecipe()
		)).
		'<div id="part_formula_container">'.
		$this->render($part,$context).
		'</div>'.
		'<script src="'.$baseUrl.'Editor/Parts/formula/formula_editor.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function getFromRequest($id) {
		$part = FormulaPart::load($id);
		$part->setReceiverName(Request::getString('receiverName'));
		$part->setReceiverEmail(Request::getString('receiverEmail'));
		$part->setRecipe(Request::getString('recipe'));
		return $part;
	}
	
	function buildSub($part,$context) {
		$valid = DOMUtils::isValidFragment(StringUtils::toUnicode($part->getRecipe()));
		return '<formula xmlns="'.$this->getNamespace().'">'.
			($valid ? '<recipe>'.$part->getRecipe().'</recipe>' : '<invalid/>').
			'</formula>';
	}
	
	function importSub($node,$part) {
		$recipe = DOMUtils::getFirstDescendant($node,'recipe');
		$xml = DOMUtils::getInnerXML($recipe);
		$xml = DOMUtils::stripNamespaces($xml);
		$part->setRecipe($xml);
	}
	
	function getToolbars() {
		return array(
			'Formular' => '
			<script source="../../Parts/formula/toolbar.js"/>
			<icon icon="file/text" overlay="edit" text="{Show source;da:Vis kilde}" name="showSource"/>
			<divider/>
			<grid>
				<row>
					<cell label="Modtager navn:" width="180">
						<text-input adaptive="true" name="receiverName"/>
					</cell>
				</row>
				<row>
					<cell label="Modtager e-mail:" width="180">
						<text-input adaptive="true" name="receiverEmail"/>
					</cell>
				</row>
			</grid>
		');
	}
	
	function editorGui($part,$context) {
		$gui='
			<window title="Kilde" name="sourceWindow" width="600">
				<formula name="sourceFormula">
					<code-input key="recipe"/>
				</formula>			
			</window>
			';
		return In2iGui::renderFragment($gui);
	}
}
?>