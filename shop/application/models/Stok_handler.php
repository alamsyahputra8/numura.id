<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stok_handler extends CI_Model {
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
		$menu 		 	= 'stok';
		$data_akses  	= $this->query->getAkses($profile,$menu);
		$shift 		 	= array_shift($data_akses);
		$akses 			= $shift['akses'];

		if ($profile==1) {
			$cond		= '';
		} else {
			$cond 		= "AND userid='$userid'";
		}

		$qUser				= "
							SELECT 
								a.*,
								(select nama_suplier from suplier where id=a.id_suplier) suplier
							from stok_order a where 1=1 and type=1 $cond
							";
		$dataUsr			= $this->db->query($qUser)->result_array();

		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->db->query($qUser)->num_rows();

		if ($cek>0) {
			foreach($dataUsr as $data) { 
				$no++;
				$id = $data['id_order'];
				
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
					$status 	= '<b class="text-danger"><i class="fas fa-tshirt"></i> Belum Lunas</b>';
				} else {
					if ($data['is_finish']==1) {
						$status 	= '<b class="text-success"><i class="fas fa-tshirt"></i> Selesai</b>';
					} else {
						$status 	= '<b class="text-warning"><i class="fas fa-tshirt"></i> Lunas</b>';
					}
				}

				if ($data['is_finish']==1) {
					$finish 	= '';
				} else {
					$finish 	= '
								<a title="Selesai" class="btn btn-sm btn-clean btn-icon btn-icon-md btnfinishM" data-toggle="modal" data-target="#finish" data-id="'.$id.'">
	                                <i class="la la-check-circle"></i>
	                            </a>
								';
				}

				if ($data['total_harga']>$data['bayar']) {
					$btnBayar 	= '
								<a title="Bayar" class="btn btn-sm btn-clean btn-icon btn-icon-md btnbayarM" data-toggle="modal" data-target="#bayar" data-id="'.$id.'">
	                                <i class="la la-money"></i>
	                            </a>
								';
				} else {
					$btnBayar 	= '';
				}

				$btnact			= $btnUpdate.$btnDelete.$btnBayar.$btnDetail;

				
				$row = array(
					"keterangan"		=> $data['label'],
					"suplier"			=> $data['suplier'],
					"jml"				=> $this->formula->rupiah3($data['jml']).' pcs',
					"total"				=> $this->formula->rupiah($data['total_harga']),
					"bayar"				=> $this->formula->rupiah($data['bayar']),
					"tgl"				=> $data['createddate'],
					"status"			=> $status,
					"actions"			=> $btnact.$finish
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
