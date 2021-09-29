<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_handler extends CI_Model {
	private $profile;

	public function __construct(){
		parent::__construct();
		// $this->db2 = $this->load->database('dblicense', TRUE);
		// $this->db2->get('siswa');
	} 
	function data(){
		$qUser				= "
							SELECT a.*, b.nama_role, b.desc_role
							from user a 
							LEFT JOIN role b on a.id_role=b.id_role ORDER BY userid DESC
							";
		$dataUsr			= $this->db->query($qUser)->result_array();

		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->db->query($qUser)->num_rows();

		if ($cek>0) {
			foreach($dataUsr as $data) { 
				$no++;

				$id 		= $data['userid'];
				$filefound 	= $data['picture'];
				$url 		= base_url()."images/user/".$filefound;
				$exis 		= file_exists(FCPATH."images/user/".$filefound);
				/*if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}*/
				
				if ($data['id_role']==0) {
					$namarole	= 'No Access';
				} else {
					$namarole	= $data['nama_role'];
				}

				if ($data['picture']=='') {
					$picture 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['name'],0,1).'</span></center>';
				} else {
					$picture 		= '
								<div class="kt-user-card-v2">
	                                <div class="kt-user-card-v2__pic" style="margin: 0 auto;">
	                                    <center><img src="'.base_url().'images/user/'.$filefound.'" class="m-img-rounded kt-marginless" alt="photo"></center>
	                                </div>
	                            </div>';
				}
				
				if ($data['ldap']==1) {
					$ldap 	= '<span class="text-success"><i class="fa fa-check-circle"></i> YES</span>';
				} else {
					$ldap 	= '<span class="text-danger"><i class="fa fa-times-circle"></i> NO</span>';
				}

				$row = array(
					"picture"		=> $picture,
					"name"			=> $data['name'],
					"username"		=> $data['username'],
					"email"			=> $data['email'],
					"role"			=> $namarole,
					"ldap"			=> $ldap,
					"phone"			=> $data['phone'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			return json_encode($json);
		} else {
			$json ='';
			return json_encode($json);
		}
	}
}
