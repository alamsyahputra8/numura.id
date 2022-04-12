<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query extends CI_Model {
	
	function getData($table,$field,$condition) {
		$query = "
			SELECT $field from $table $condition
		";
		return $this->db->query($query)->result_array();
	}
	
	function getNumRows($table,$field,$condition) {
		$query = "
			SELECT $field from $table $condition
		";
		return $this->db->query($query);
	}
	
	function insertData($table,$field,$value) {
		$query = "
			INSERT INTO `$table` ($field) VALUES ($value);
		";
		return $this->db->query($query);
	}
	
	function updateData($table,$field,$cond) {
		$query = "
			update `$table` set $field $cond;
		";
		return $this->db->query($query);
	}
	
	function deleteData($table,$field,$cond) {
		$query = "DELETE FROM $table WHERE $field=$cond";
		return $this->db->query($query);
	}
	
	function deleteData2($table,$field,$cond) {
		$query = "DELETE FROM $table WHERE $field='$cond'";
		return $this->db->query($query);
	}
}
