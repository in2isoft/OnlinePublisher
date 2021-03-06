<?php
if (!isset($GLOBALS['basePath'])) {
	header('HTTP/1.1 403 Forbidden');
	exit;
}
class HtmlTableParser {
	
	var $hey = 'hep';
	
	static function parse($html) {
		$parsed = array();
		$tables = Strings::extract($html,'<table','table>');
		
		foreach ($tables as $table) {
			$parsedTable = array();
			$rows = Strings::extract($table,'<tr','tr>');
			foreach ($rows as $row) {
				$parsedRow = array();
				$cells = Strings::extract($row,'<td','td>');
				foreach ($cells as $cell) {
					$parsedRow[] = Strings::removeTags($cell);
				}
				$parsedTable[] = $parsedRow;
			}
			$parsed[] = $parsedTable;
		}
		return $parsed;
	}
	
	static function parseUsingHeader($html) {
		$out = array();
		$parsed = HtmlTableParser::parse($html);
		foreach ($parsed as $rows) {
			$table = array();
			for ($i=1; $i < count($rows); $i++) { 
				$row = array();
				for ($j=0; $j < count($rows[$i]); $j++) { 
					if (isset($rows[0][$j])) {
						$row[$rows[0][$j]] = $rows[$i][$j];
					}
				}
				$table[] = $row;
			}
			$out[] = $table;
		}
		return $out;
	}
}