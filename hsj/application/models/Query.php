<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query extends CI_Model {
	public function __construct(){
		parent::__construct();
		// $this->db2 = $this->load->database('dblicense', TRUE);
		// $this->db2->get('siswa');
	}
	
	function getData($table,$field,$condition) {
		$query = "
			SELECT $field from $table $condition
		";
		return $this->db->query($query)->result_array();
	}
	function getAkses($profile,$menu){
		$query = "
			SELECT a.id_role,c.url,b.akses FROM role a LEFT JOIN role_menu b ON a.id_role = b.id_role LEFT JOIN menu c ON b.id_menu=c.id_menu WHERE a.id_role='$profile' and c.url = '$menu'
		";
		return $this->db->query($query)->result_array();
	}
	function getDatabyQ($query) {
		$query = "
			$query
		";
		return $this->db->query($query)->result_array();
	}
	
	function getNumRows($table,$field,$condition) {
		$query = "
			SELECT $field from $table $condition
		";
		return $this->db->query($query);
	}
	
	function getNumRowsbyQ($query) {
		$query = "
			$query
		";
		return $this->db->query($query);
	}
	
	function insertData($table,$field,$value) {
		$query = "
			INSERT INTO `$table` ($field) VALUES ($value);
		";
		return $this->db->query($query);
	}

	function insertDatabyQ($query) {
		$query = "
			$query
		";
		return $this->db->query($query);
	}
	
	function updateData($table,$field,$cond) {
		$query = "
			update `$table` set $field $cond;
		";
		return $this->db->query($query);
	}

	function updateDatabyQ($query) {
		$query = "
			$query
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
	
	function insertlog($activity,$url,$id) {
		//parameter
		$userdata	= $this->session->userdata('sesspwt');
		$userid		= $userdata['userid'];
		$datelog	= date('Y-m-d H:i:s');
		$ipaddr		=$_SERVER['REMOTE_ADDR'];
		$field 		="id_log,userid,date_time,activity,menu,data,ip";
		$value 		="'','$userid','$datelog','$activity','$url',	'$id','$ipaddr'";
		
		
		$query 		="INSERT INTO `data_log` ($field) VALUES ($value)";
		return $this->db->query($query);		
	}

	function getMaxID($table,$idfield){
		$query = "SELECT MAX($idfield)as maxid FROM $table";
		return $this->db->query($query);		
	}

}
