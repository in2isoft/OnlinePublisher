<?
/**
 * @package OnlinePublisher
 * @subpackage Classes.Services
 */
require_once($basePath.'Editor/Classes/Database.php');
require_once($basePath.'Editor/Classes/Log.php');

class SchemaService {
	
	function buildSqlSetters($obj,$schema) {
		$sql = '';
		foreach ($schema['fields'] as $field => $info) {
			$column = $field;
			if (isset($info['column'])) {
				$column = $info['column'];
			}
			if (strlen($sql)>0) {
				$sql.=',';
			}
			$sql.="`".$column."`=";
			$getter = "get".ucfirst($field);
			if (!method_exists($obj,$getter)) {
				Log::warn($getter.' does not exist');
			}
			$value = $obj->$getter();
			if ($info['type']=='text') {
				$sql.=Database::text($value);
			} else if ($info['type']=='int') {
				$sql.=Database::int($value);
			} else if ($info['type']=='boolean') {
				$sql.=Database::boolean($value);
			} else if ($info['type']=='datetime') {
				$sql.=Database::datetime($value);
			}
		}
		return $sql;
	}

	function buildSqlColumns($schema) {
		$sql = '';
		foreach ($schema['fields'] as $field => $info) {
			$column = $field;
			if (isset($info['column'])) {
				$column = $info['column'];
			}
			if (strlen($sql)>0) {
				$sql.=',';
			}
			$sql.='`'.$column.'`';
		}
		return $sql;
	}
	
	function buildSqlValues($obj,$schema) {
		$sql = '';
		foreach ($schema['fields'] as $field => $info) {
			$column = $field;
			if (isset($info['column'])) {
				$column = $info['column'];
			}
			if (strlen($sql)>0) {
				$sql.=',';
			}
			$getter = "get".ucfirst($field);
			if (!method_exists($obj,$getter)) {
				Log::warn($getter.' does not exist');
			}
			$value = $obj->$getter();
			if ($info['type']=='text') {
				$sql.=Database::text($value);
			} else if ($info['type']=='int') {
				$sql.=Database::int($value);
			} else if ($info['type']=='boolean') {
				$sql.=Database::boolean($value);
			} else if ($info['type']=='datetime') {
				$sql.=Database::datetime($value);
			}
		}
		return $sql;
	}
}