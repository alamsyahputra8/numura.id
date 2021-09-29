<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Query2 extends CI_Model {
	
	function getDataTREG_lo($id) {
		$treg = "";
		$query = "
			SELECT b.treg FROM lo a LEFT JOIN map b ON a.id_map = b.id_map WHERE a.id_lo = $id
		";
		$data = $this->db->query($query)->result();
		foreach($data as $row){
			if($row->treg !=''){
				$treg = $row->treg;				
			}else{
				$treg = NULL;
			}
		}
		return $treg;
	}
	
}
