<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pesanan_handler extends CI_Model {
	private $profile;

	public function __construct(){
		parent::__construct();
		// $this->db2 = $this->load->database('dblicense', TRUE);
		// $this->db2->get('siswa');
	} 
	function data($status){
		$session	 	= $this->session->userdata('sesspwt'); 
		$userid 	 	= $session['userid'];
		$profile 	 	= $session['id_role'];
		$menu 		 	= 'pesanan';
		$data_akses  	= $this->query->getAkses($profile,$menu);
		$shift 		 	= array_shift($data_akses);
		$akses 			= $shift['akses'];

		if($status=='send') {
			$condstat 	= "and status='3'";
		} else if ($status=='proses') {
			$condstat 	= "and status='2'";
		} else {
			$condstat 	= "and status='1'";
		}

		if ($profile==1) {
			$cond		= '';
		} else {
			$cond 		= "AND userid='$userid'";
		}

		$qUser				= "
							SELECT 
								a.*,
								(SELECT label from size where id_size=a.ukuran) label_size,
								(SELECT detail from size where id_size=a.ukuran) detail_size,
								(SELECT label from color where id=a.warna) label_color,
								(SELECT code_color from color where id=a.warna) code,
								(SELECT label from kaos_type where id=a.kaos_type) label_type_kaos,
								(SELECT file from karakter where id_karakter=a.karakter) pict_char,
								(SELECT kode from karakter where id_karakter=a.karakter) pictcode,
								(SELECT nama from karakter where id_karakter=a.karakter) pictname,
								(SELECT name from user where userid=a.userid) createdby
							from pesanan a where 1=1 $condstat $cond
							";
		$dataUsr			= $this->db->query($qUser)->result_array();

		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->db->query($qUser)->num_rows();

		if ($cek>0) {
			foreach($dataUsr as $data) { 
				$no++;
				$id = $data['id_pesanan'];
				
				$cekupdate 		= getRoleUpdate($akses,'update',$userid);
				$cekdelete 		= getRoleDelete($akses,'delete',$userid);
				$cekapproval 	= getRoleAction($akses,'approval','approval',$userid);

				if ($cekupdate=='ada') {
					if ($data['status']=='1') {
						$btnUpdate 	= '
									<a class="btn btn-sm btn-clean btn-icon btn-icon-md btnupdateM" title="Edit" data-toggle="modal" data-target="#update" data-id="'.$id.'">
		                                <i data-toggle="tooltip" title="Update" class="la la-edit"></i>
		                            </a>
									';
					} else {
						$btnUpdate 	= '';	
					}
				} else {
					$btnUpdate 	= '';
				}

				if ($cekdelete=='ada') {
					if ($data['status']=='1') {
						$btnDelete 	= '
									<a title="Delete" class="btn btn-sm btn-clean btn-icon btn-icon-md btndeleteMenu" data-toggle="modal" data-target="#delete" data-id="'.$id.'">
		                                <i class="la la-trash"></i>
		                            </a>
		                            ';
					} else {
						$btnDelete 	= '';	
					}
				} else {
					$btnDelete 	= '';
				}

				if ($cekapproval=='ada') {
					if ($data['status']=='1') {
						$btnApprove = '
									<a title="Proses Pesanan" class="btn btn-sm btn-clean btn-icon btn-icon-md btnProses" data-toggle="modal" data-target="#proses" data-id="'.$id.'">
		                                <i class="la la-check-circle"></i>
		                            </a>
		                            ';
					} else {
						$btnApprove = '';
					}
				} else {
					$btnApprove 	= '';
				}

				$btnact			= $btnUpdate.$btnDelete.$btnApprove;

				$komisi 		= $data['harga_normal']-$data['harga'];

				if ($data['status']=='2') {
					$status 	= '<b class="text-warning"><i class="flaticon2-delivery-package"></i> Diproses</b>';
				} else if ($data['status']=='3') {
					$status 	= '<b class="text-success"><i class="flaticon2-rocket-1"></i> Dikirim</b>';
				} else {
					$status 	= '<b class="text-default"><i class="flaticon-time-1"></i> Menunggu Proses</b>';
				}

				$pictcode 		= $data['pictcode'].'-'.$data['pictname'];

				$row = array(
					"char"				=> '<img src="'.base_url().'images/char/'.$data['pict_char'].'" style="max-height: 100px; max-width: 100px;">',
					"name"				=> $data['custom_nama'],
					"size"				=> $data['label_size'].' '.$data['detail_size'],
					"color"				=> '<i class="fa fa-circle" style="color: '.$data['code'].'"></i> '.$data['label_color'],
					"price"				=> $this->formula->rupiah($data['harga']),
					"komisi"			=> $this->formula->rupiah($komisi),
					"typekaos" 			=> $data['label_type_kaos'],
					"tglpesan"			=> $data['tanggal_pesan'],
					"createdby"			=> $data['createdby'],
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
