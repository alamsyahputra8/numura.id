<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengiriman_handler extends CI_Model {
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
		$menu 		 	= 'pengiriman';
		$data_akses  	= $this->query->getAkses($profile,$menu);
		$shift 		 	= array_shift($data_akses);
		$akses 			= $shift['akses'];

		if ($status=='pending') {
			$condstatus = "AND status='0' AND id_pengiriman in 
						(
							SELECT id_pengiriman from pengiriman_detail where id_pesanan in (
								SELECT id_pesanan from pesanan where status in (1,2) and flag_restok=0
							)
						)
						";
		} else if ($status=='proses') {
			$condstatus = "AND status='0' AND id_pengiriman in 
						(
							SELECT id_pengiriman from pengiriman_detail where id_pesanan in (
								SELECT id_pesanan from pesanan where status=2 and flag_restok=1
							)
						)
						";
		} else {
			$condstatus = "AND status='1'";
		}

		if ($profile==1) {
			$cond		= '';
		} else {
			$cond 		= "AND userid='$userid'";
		}

		$qUser				= "
							SELECT 
								a.*,
								(select count(id_pesanan) from pengiriman_detail where id_pengiriman=a.id_pengiriman) jml,
								(select nama_ekspedisi from ekspedisi where id=a.ekspedisi) eks,
								(SELECT name from user where userid=a.userid) createdby
							from pengiriman a where 1=1 $condstatus $cond
							";
		$dataUsr			= $this->db->query($qUser)->result_array();

		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->db->query($qUser)->num_rows();

		if ($cek>0) {
			foreach($dataUsr as $data) { 
				$no++;
				$id = $data['id_pengiriman'];
				
				$cekupdate 		= getRoleUpdate($akses,'update',$userid);
				$cekdelete 		= getRoleDelete($akses,'delete',$userid);
				$cekapproval 	= getRoleAction($akses,'send','send',$userid);
				$cekupongkir 	= getRoleAction($akses,'ongkir','ongkir',$userid);

				if ($cekupdate=='ada') {
					if ($data['status']=='0') {
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
					if ($data['status']=='0') {
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
					if ($data['status']=='0') {
						$btnApprove = '
									<a title="Proses Pengiriman" class="btn btn-sm btn-clean btn-icon btn-icon-md btnProses" data-toggle="modal" data-target="#proses" data-id="'.$id.'">
		                                <i class="la la-check-circle"></i>
		                            </a>
		                            ';
					} else {
						$btnApprove = '';
					}
				} else {
					$btnApprove 	= '';
				}

				if ($cekupongkir=='ada') {
					if ($data['status']=='0') {
						$btnOngkir 	= '
									<a title="Update Ongkir" class="btn btn-sm btn-clean btn-icon btn-icon-md btnOngkir" data-toggle="modal" data-target="#ongkir" data-id="'.$id.'">
		                                <i class="la la-truck"></i>
		                            </a>
		                            ';
					} else {
						$btnOngkir 	= '';
					}
				} else {
					$btnOngkir 	= '';
				}

				$btnact			= $btnUpdate.$btnDelete.$btnApprove.$btnOngkir;

				if ($data['status']=='1') {
					$status 	= '<b class="text-success"><i class="la la-truck"></i> Dikirim</b>';
				} else {
					$status 	= '<b class="text-danger"><i class="flaticon-time-1"></i> Menunggu Proses</b>';
				}

				$ongkir 	= $this->formula->rupiah($data['ongkir']);

				$row = array(
					"name"				=> '<a href="'.base_url().'detailpengiriman/'.$id.'" title="Lihat Detail">'.$data['nama_penerima'].'</a>',
					"phone"				=> $data['hp_penerima'],
					"alamat"			=> $data['alamat'],
					"status"			=> $status,
					"ongkir"			=> $ongkir,
					"createdby"			=> $data['createdby'],
					"jml"				=> $data['jml'].' pcs',
					"eks"				=> $data['eks'],
					"createddate"		=> $data['tgl_buat'],
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
