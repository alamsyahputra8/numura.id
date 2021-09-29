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
		$profile 	= round($profile);
		$query = "
			SELECT a.id_role,c.url,b.akses 
			FROM role a 
			LEFT JOIN role_menu b ON a.id_role = b.id_role 
			LEFT JOIN menu c ON b.id_menu=c.id_menu 
			WHERE a.id_role='$profile' and c.url = '$menu'
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
			INSERT INTO $table ($field) VALUES ($value);
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
			update $table set $field $cond;
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
		$field 		="userid,date_time,activity,menu,data,ip";
		$value 		="'$userid','$datelog','$activity','$url',	'$id','$ipaddr'";
		
		
		$query 		="INSERT INTO data_log ($field) VALUES ($value)";
		return $this->db->query($query);		
	}

	function getMaxID($table,$idfield){
		$query = "SELECT MAX($idfield)as maxid FROM $table";
		return $this->db->query($query);		
	}

	function getdatalog(){
		//error_reporting(0);
		
		//$menu = 'panel/log';
		//$data_aksess = $this->query->getAkses($this->profile,$menu);
		//foreach ($data_aksess as $shift) { $akses = $shift['akses']; }
		//$shift = array_shift($data_aksess);
		//$akses = $shift['akses'];

		$q 		= "
					SELECT a.*,
					(SELECT name from user where userid=a.userid) nameuser,
					(SELECT picture from user where userid=a.userid) picture
					from 
					data_log a where menu not in ('Manage Menus','Manage Role') order by id_log desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_log'];

				// $getdatausers			= $this->query->getData('user','*',"WHERE userid='".$data['userid']."'");
				// $quser 			= "SELECT * FROM user WHERE userid='".$data['userid']."'";
				// $getdatausers	= $this->query->getDatabyQ($quser);
				// $datauser 	= array_shift($getdatausers);

				$user = $data['nameuser']; 
				$filefound = $data['picture'];
				$url = base_url()."images/user/".$filefound;
				$exis = file_exists(FCPATH."images/user/".$filefound);

				/*if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}*/
				$dat ='';
				if ($data['picture']=='') {
					$picture 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['nameuser'],0,1).'</span></center>';
				} else {
					$picture 		= '
								<div class="kt-user-card-v2">
	                                <div class="kt-user-card-v2__pic" style="margin: 0 auto;">
	                                    <center><img src="'.base_url().'images/user/'.$filefound.'" class="m-img-rounded kt-marginless" alt="photo"></center>
	                                </div>
	                            </div>';
				}
				
				if(trim($data['menu'])=='Manage Customer'){
					$tables = 'customer';
					$parameter = 'nama_pelanggan as datass';
					$condition = "where id='".$data['data']."'";
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				if(trim($data['menu'])=='Pengajuan'){
					$tables = 'sbrdoc';
					$parameter = 'no_request as datass';
					$condition = "where id='".$data['data']."'";
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				if(trim($data['menu'])=='Config Mail Notification'){
					$tables = 'param';
					$parameter = "name || ' -> value :' || value as datass";
					$condition = "where id='".$data['data']."'";
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				} 

				if(trim($data['menu'])=='Manage Role'){
					$tables = 'role';
					$parameter = 'nama_role as datass';
					$condition = "where id_role='".$data['data']."'";
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				// if(trim($data['menu'])=='Manage User'){
				// 	$tables = 'user';
				// 	$parameter = 'username as datass';
				// 	$condition = "where userid='".$data['data']."'";
				// 	$get_data = $this->query->getData($tables,$parameter,$condition);
				// 	foreach($get_data as $data_data) { 
				// 		$dat = $data_data['datass'];
				// 	}
				// }

				// if(trim($data['menu'])=='User Profile'){
				// 	$tables = 'user';
				// 	$parameter = 'name as datass';
				// 	$condition = "where userid='".$data['data']."'";
				// 	$get_data = $this->query->getData($tables,$parameter,$condition);
				// 	foreach($get_data as $data_data) { 
				// 		$dat = $data_data['datass'];
				// 	}
				// }
 
				
				
				
				
				$row = array(
					"date"		=> $data['date_time'],
					"photo"		=> $picture,
					"user"		=> $user,
					"activity"	=> strtoupper($data['activity']),
					"id"		=> $data['data'],
					"data"		=> $dat?:'-',
					"menu"		=> $data['menu']
					);
				$json[] = $row;
			}
			return json_encode($json);
		} else {
			$json ='';
			return json_encode($json);
		}
	}
	// function insertDatabyQDB2($query) {
	// 	$query = "
	// 		$query
	// 	";
	// 	return $this->db2->query($query);
	// }

}
