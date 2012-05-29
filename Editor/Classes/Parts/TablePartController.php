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
require_once($basePath.'Editor/Classes/Parts/TablePart.php');
require_once($basePath.'Editor/Classes/Utilities/StringUtils.php');
require_once($basePath.'Editor/Classes/Services/SettingService.php');

class TablePartController extends PartController
{
	function TablePartController() {
		parent::PartController('table');
	}
	
	function createPart() {
		$part = new TablePart();
		$part->setHtml('<table><thead><tr><th>Header</th><th>Header</th></tr></thead><tbody><tr><td>Cell</td><td>Cell</td></tr><tr><td>Cell</td><td>Cell</td></tr></tbody></table>');
		$part->save();
		return $part;
	}
	
	function display($part,$context) {
		return $this->render($part,$context);
	}
	
	function getIndex($part) {
		return StringUtils::removeTags($part->getHtml());
	}
		
	function editor($part,$context) {
		global $baseUrl;
		return
		'<div id="part_table" class="part_table common_font">'.$part->getHtml().'</div>'.
		'<input type="hidden" name="html" value="'.StringUtils::escapeXML(StringUtils::fromUnicode($part->getHtml())).'"/>'.
		'<script src="'.$baseUrl.'Editor/Parts/table/script.js" type="text/javascript" charset="utf-8"></script>';
	}
	
	function editorGui($part,$context) {
		$gui='
		<window title="Kilde" name="sourceWindow" width="500">
			<formula name="sourceFormula">
				<group labels="above">
					<field>
						<text-input multiline="true" key="source" max-height="500"/>
					</field>
				</group>
				<buttons>
					<button name="applySource" title="OK" click="sourceWindow.hide()"/>
				</buttons>
			</formula>
		</window>

		<window title="Egenskaber" name="propertiesWindow" icon="monochrome/info" width="300" padding="10">
			<formula name="propertiesFormula">
				<fieldset legend="Tabel">
					<group labels="before">
						<!--
						<field label="Variant">
							<dropdown key="variant">
								<item text="Moderne"/>
								<item text="Markant"/>
							</dropdown>
						</field>
						-->
						<field label="Hoved">
							<dropdown key="head" name="tableHead">
								<item text="Ingen" value="0"/>
								<item text="1 række" value="1"/>
								<item text="2 rækker" value="2"/>
								<item text="3 rækker" value="3"/>
								<item text="4 rækker" value="4"/>
								<item text="5 rækker" value="5"/>
							</dropdown>
						</field>
						<field label="Bund">
							<dropdown key="foot" name="tableFoot">
								<item text="Ingen" value="0"/>
								<item text="1 række" value="1"/>
								<item text="2 rækker" value="2"/>
								<item text="3 rækker" value="3"/>
								<item text="4 rækker" value="4"/>
								<item text="5 rækker" value="5"/>
							</dropdown>
						</field>
						<field label="Width">
							<style-length-input key="width"/>
						</field>
					</group>
				</fieldset>
				<!--
				<space height="10"/>
				<fieldset legend="Celle">
					<group labels="before">
						<field label="Baggrund">
							<text-input key="cellBackground"/>
						</field>
					</group>
				</fieldset>
				-->
			</formula>
		</window>
		
		<menu name="tableMenu">
			<item text="Remove row" value="removeRow"/>
		</menu>
		';
		return In2iGui::renderFragment($gui);
	}

	function getToolbars() {
		return array(
			'Tabel' => '
				<icon icon="common/clean" text="Ryd op" name="clean"/>
				<icon icon="common/info" text="Info" name="showInfo"/>
				<icon icon="file/text" text="Kilde" overlay="edit" name="editSource"/>
				<divider/>
				<icon icon="table/row" text="Ny række" overlay="new" name="addRow"/>
				<icon icon="table/column" text="Ny kolonne" overlay="new" name="addColumn"/>
				'
			);
	}
	
	function getFromRequest($id) {
		$part = TablePart::load($id);
		$html = Request::getUnicodeString('html');
		$html = str_replace('contenteditable="true"', '', $html);
		$part->setHtml($html);
		return $part;
	}
	
	function buildSub($part,$context) {
		$html = $part->getHtml();
		if (DOMUtils::isValidFragment($html)) {
			$html = $this->insertLinks($part,$context);
			//$html = $context->decorateForBuild($html,$part->getId());
			return '<table xmlns="'.$this->getNamespace().'" valid="true">'.
			$html.
			'</table>';
		} else {
			return 
			'<table xmlns="'.$this->getNamespace().'" valid="false">'.
			'<![CDATA['.$html.']]>'.
			'</table>';
		}
	}
	
	function insertLinks($part,$context) {
		$html = $part->getHtml();
		preg_match_all("/<[^>]+>/u",$html,$matches,PREG_OFFSET_CAPTURE);
		$out = '';
		$index = 0;
		foreach ($matches[0] as $found) {
			if ($found[1]-$index > 0) {
				$str = substr($html,$index,$found[1]-$index);
				$str = $context->decorateForBuild($str,$part->getId());
				$out.=$str;
			}
			$index = $found[1] + strlen($found[0]);
			$out.= $found[0];
		}
		return $out;
	}
	
	function importSub($node,$part) {
		if ($table = DOMUtils::getFirstDescendant($node,'table')) {
			if ($table->getAttribute('valid')=='false') {
				$part->setHtml(DOMUtils::getText($table));
			} else {
				$str = DOMUtils::getInnerXML($table);
				$str = DOMUtils::stripNamespaces($str);
				$part->setHtml($str);
			}
		}
		
	}
}
?>