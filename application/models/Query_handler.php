<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query_handler extends CI_Model {
	public function __construct(){
		parent::__construct();
		// $this->db2 = $this->load->database('dblicense', TRUE);
		// $this->db2->get('siswa');
	} 
	function insert($table,$data){ 
		$this->db->insert($table, $data); 
		return $this->db->insert_id();
	}
	function getMaxID($max,$table){
		$query = "
			SELECT COALESCE(MAX($max),0)as maxid FROM $table
		";
		$execute = $this->db->query($query)->result_array();
		$arr = array_shift($execute);
		return $arr['maxid']+1;
	}
	function delete($table,$field,$value){
		$query = "
			Delete from ".$table." where ".$field." = ".$value."
		";
		$execute = $this->db->query($query);
		header('Content-type: application/json; charset=UTF-8');
		return json_encode($execute);
	}

}
