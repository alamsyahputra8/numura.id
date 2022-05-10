<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pemetaan_handler extends CI_Model {
	private $profile;

	public function __construct(){
		parent::__construct();
		// $this->db2 = $this->load->database('dblicense', TRUE);
		// $this->db2->get('siswa');
	} 
	function data(){
		$session	 	= $this->session->userdata('sesspwt'); 
		$userid 	 	= $session['userid'];
		$profile 	 	= $session['id_role'];
		$menu 		 	= 'pemetaan-ukuran';
		$data_akses  	= $this->query->getAkses($profile,$menu);
		$shift 		 	= array_shift($data_akses);
		$akses 			= $shift['akses'];

		$qUser				= "
							SELECT 
								a.*,
								(SELECT label from size where id_size=a.size) sizename,
								(SELECT label from color where id=a.color) colname
							from mapping_shopee a where 1=1 
							";
		$dataUsr			= $this->db->query($qUser)->result_array();

		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->db->query($qUser)->num_rows();

		if ($cek>0) {
			foreach($dataUsr as $data) { 
				$no++;
				$id = $data['id'];
				
				$cekupdate 		= getRoleUpdate($akses,'update',$userid);
				$cekdelete 		= getRoleDelete($akses,'delete',$userid);

				if ($cekupdate=='ada') {
					$btnUpdate 	= '
								<a class="btn btn-sm btn-clean btn-icon btn-icon-md btnupdateM" title="Edit" data-toggle="modal" data-target="#update" data-id="'.$id.'">
	                                <i data-toggle="tooltip" title="Update" class="la la-edit"></i>
	                            </a>
								';
				} else {
					$btnUpdate 	= '';
				}

				if ($cekdelete=='ada') {
					$btnDelete 	= '
								<a title="Delete" class="btn btn-sm btn-clean btn-icon btn-icon-md btndeleteMenu" data-toggle="modal" data-target="#delete" data-id="'.$id.'">
	                                <i class="la la-trash"></i>
	                            </a>
	                            ';
				} else {
					$btnDelete 	= '';
				}

				$btnact			= $btnUpdate.$btnDelete;

				
				$row = array(
					"size"				=> $data['sizename'],
					"color"				=> $data['colname'],
					"code"				=> $data['code'],
					"actions"			=> $btnact
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
