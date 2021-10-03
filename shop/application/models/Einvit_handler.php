<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Einvit_handler extends CI_Model {
	private $profile;

	public function __construct(){
		parent::__construct();

		$this->dbw 		= $this->load->database('dbw', TRUE);
	} 
	function data(){
		$session	 	= $this->session->userdata('sesspwt'); 
		$userid 	 	= $session['userid'];
		$profile 	 	= $session['id_role'];
		$menu 		 	= 'digitalinv';
		$data_akses  	= $this->query->getAkses($profile,$menu);
		$shift 		 	= array_shift($data_akses);
		$akses 			= $shift['akses'];

		$qUser				= "
							SELECT 
								a.*, b.man, b.woman
							from person_order a
							left join detail_person b on a.id=b.orderid
							where 1=1 order by a.id desc
							";
		$dataUsr			= $this->dbw->query($qUser)->result_array();

		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->dbw->query($qUser)->num_rows();

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

				$btnDetail 		= '
								<a href="'.base_url().'detailstok/'.$id.'" title="Lihat Detil" class="btn btn-sm btn-clean btn-icon btn-icon-md">
	                                <i class="la la-search"></i>
	                            </a>
	                            ';

				if ($data['status']==1) {
					$status 	= '<b class="text-success"><i class="fas fa-tshirt"></i> PAID</b>';
				} else {
					$status 	= '<b class="text-warning"><i class="fas fa-tshirt"></i> NOT PAID</b>';
				}

				$btnact			= $btnUpdate.$btnDelete.$btnDetail;

				
				$row = array(
					"orderid"			=> $data['orderid'],
					"link"				=> $data['name'],
					"pria"				=> $data['man'],
					"wanita"			=> $data['woman'],
					"tgl"				=> $data['weddingdate'],
					"createddate"		=> $data['createddate'],
					"status"			=> $status,
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
