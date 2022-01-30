<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengiriman extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	private $profile;
	private $divisiUAM;
	private $segmenUAM;
	private $tregUAM;
	private $witelUAM;
	private $amUAM;
	
	public function __construct(){
		date_default_timezone_set("Asia/Bangkok");
        parent::__construct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->model('query'); 
		$this->load->model('formula'); 
		$this->load->model('datatable'); 
		$this->load->model('pengiriman_handler');
		
		ini_set('max_execution_time', 123456);
		ini_set("memory_limit","1256M");
			
		// $session = checkingsessionpwt();
		$session	 = $this->session->userdata('sesspwt'); 
    }
	
	public function index(){
		if(checkingsessionpwt()){
			$this->load->view('panel/dashboard');
		} else {
			// redirect('/panel');
		}
	}

	public function getdata($status){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'name'			=> true,
				'phone'			=> true,
				'alamat'		=> true,
				'ongkir'		=> true,
				'status'		=> true,
				'createdby'		=> true,
				'jml'			=> true,
				'eks'			=> true,
				'createddate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;

			// $jsonfile	= base_url().'user/data';
			$jsonfile	= $this->pengiriman_handler->data($status);

			$this->datatable->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function insert(){
		if(checkingsessionpwt()){
			$url 				= "Pengiriman";
			$activity 			= "INSERT";

			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid				= $userdata['userid'];
			$date				= date('Y-m-d');

			$pengirim			= trim(strip_tags(stripslashes($this->input->post('pengirim',true))));
			$phonepengirim		= trim(strip_tags(stripslashes($this->input->post('phonepengirim',true))));
			$name				= trim(strip_tags(stripslashes($this->input->post('name',true))));
			$phone 				= trim(strip_tags(stripslashes($this->input->post('phone',true))));
			$alamat 			= trim(strip_tags(stripslashes($this->input->post('alamat',true))));
			$ekspedisi			= trim(strip_tags(stripslashes($this->input->post('ekspedisi',true))));
			$idpesanan 			= $this->input->post('pes',true);
			
			@$jmlPes 			= count($idpesanan);
			if ($jmlPes<1 or empty($idpesanan)) {
				echo '';
				exit();
			}
			
			$rows 				= $this->db->query("
								INSERT INTO pengiriman (nama_penerima,hp_penerima,alamat,userid,tgl_buat,pengirim,hp_pengirim,ekspedisi) values 
								('$name','$phone','$alamat','$userid','$date','$pengirim','$phonepengirim','$ekspedisi')
								");
			
			if($rows) {
				$id				= $this->db->insert_id();
				
				if ($jmlPes>0) {
					for($i=0;$i<$jmlPes;$i++) {
						// INSERT DETAIL
						$insDet 	= $this->db->query("
									INSERT INTO pengiriman_detail (id_pesanan,id_pengiriman) values 
									('$idpesanan[$i]','$id')
									");	

						// UPDATE PESANAN 
						$updPes 	= $this->db->query("
									UPDATE pesanan SET 
										flag_pengiriman = '1'
									WHERE id_pesanan='$idpesanan[$i]'
									");
					}
				}
				$log = $this->query->insertlog($activity,$url,$id);

				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	

	public function modal(){
		if(checkingsessionpwt()){
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataRoles			= $this->db->query("
								SELECT 
								a.*
								from pengiriman a where a.id_pengiriman ='$id'
								")->result_array();
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataRoles as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function update(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$url 		= "Pesanan";
			$activity 	= "UPDATE";
			
			$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$userdata 		= $this->session->userdata('sesspwt'); 
			
			$userid			= $userdata['userid'];
			$date			= date('Y-m-d');

			$name				= trim(strip_tags(stripslashes($this->input->post('ed_name',true))));
			$ukuran 			= trim(strip_tags(stripslashes($this->input->post('ed_ukuran',true))));
			$warna 				= trim(strip_tags(stripslashes($this->input->post('ed_warna',true))));
			$karakter 			= trim(strip_tags(stripslashes($this->input->post('ed_karakter',true))));

			$getSize 			= $this->db->query("
								SELECT * FROM size where id_size='$ukuran'
								")->result_array();
			$dSize 				= array_shift($getSize);
			$price 				= $dSize['harga'];
			$normprice 			= $dSize['harga_normal'];

			$rows 			= $this->db->query("
							UPDATE pesanan SET 
								custom_nama 	= '$name',
								ukuran			= '$ukuran',
								warna			= '$warna',
								karakter		= '$karakter',
								harga			= '$price',
								harga_normal	= '$normprice'
							WHERE id_pesanan='$id'
							");
			
			if($rows) {
				$log = $this->query->insertlog($activity,$url,$id);
				
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	

	public function delete(){
		if(checkingsessionpwt()){
			$url 		= "Pesanan";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$rows = $this->query->deleteData('pesanan','id_pesanan',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);

				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$cond ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function approve(){
		if(checkingsessionpwt()){
			$url 		= "Pengiriman";
			$activity 	= "SEND";

			$id			= trim(strip_tags(stripslashes($this->input->post('idapp',true))));
			
			$rows 			= $this->db->query("
							UPDATE pengiriman SET 
								status	= '1',
								is_paid = '1'
							WHERE id_pengiriman='$id'
							");

			if(isset($rows)) {
				$getPrd 	= $this->db->query("
							SELECT * from pesanan a left join pengiriman_detail b on a.id_pesanan=b.id_pesanan where b.id_pengiriman='$id'
							")->result_array();
				foreach ($getPrd as $prd) {
					$idpes 	= $prd['id_pesanan'];
					$updPrd = $this->db->query("
							UPDATE pesanan SET 
								status	= '3'
							WHERE id_pesanan='$idpes'
							");
				}
				$log = $this->query->insertlog($activity,$url,$id);

				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function setongkir(){
		if(checkingsessionpwt()){
			$url 		= "Pengiriman";
			$activity 	= "SET ONGKIR";

			$id			= trim(strip_tags(stripslashes($this->input->post('idpengongkir',true))));
			$ongkir		= trim(strip_tags(stripslashes($this->input->post('ongkirval',true))));
			
			$rows 			= $this->db->query("
							UPDATE pengiriman SET 
								ongkir	= '$ongkir'
							WHERE id_pengiriman='$id'
							");

			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$id);

				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function getpesanan(){
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$role 		= $userdata['id_role'];

			echo '
			<div class="row">
			';
				if ($role==1) {
					$condpes 	= '';
				} else {
					$condpes 	= "and userid='$userid'";
				}
				$qPes 		= "
							SELECT a.*,
								(SELECT label from size where id_size=a.ukuran) label_size,
								(SELECT label from color where id=a.warna) label_color,
								(SELECT code_color from color where id=a.warna) codecolor,
								(SELECT file from karakter where id_karakter=a.karakter) pictchar
							FROM pesanan a where flag_pengiriman not in (1) and status in (1,2) $condpes
							";
				$getPes 	= $this->db->query($qPes)->result_array();
				$cekPes 	= $this->db->query($qPes)->num_rows();
				if ($cekPes>0) {

				foreach ($getPes as $pes) { 
					$idpes 	= $pes['id_pesanan'];
					if ($pes['status']=='2') {
						if ($pes['flag_restok']==1) {
							$status 	= '<b class="text-warning"><i class="flaticon2-delivery-package"></i> Dikemas</b>';
						} else {
							$status 	= '<b class="text-danger"><i class="flaticon2-delivery-package"></i> Diproses</b>';
						}
					} else if ($pes['status']=='3') {
						$status 	= '<b class="text-success"><i class="flaticon2-rocket-1"></i> Dikirim</b>';
					} else {
						$status 	= '<b class="text-default"><i class="flaticon-time-1"></i> Menunggu Proses</b>';
					}
					echo '
					<div class="col-lg-4 col-md-6 col-sm-12" style="border: 1px solid rgba(0,0,0,.1);">
						<div class="row">
							<div class="col-6">
								<label class="kt-checkbox kt-checkbox--success">
									<input type="checkbox" name="pes[]" value="'.$idpes.'" class="pes">
									<span></span>
								</label>	
								<img src="'.base_url().'images/char/'.$pes['pictchar'].'" style="width: 100%; margin-top:-1rem;">
							</div>
							<div class="col-6" style="padding-top: 1rem">
								<b>'.$pes['custom_nama'].'</b><br>
								Size : '.$pes['label_size'].'<br>
								<i class="fa fa-circle" style="color: '.$pes['codecolor'].'"></i> '.$pes['label_color'].'<br>
								'.$status.'
							</div>
						</div>
					</div>';
				}

				} else {
					echo '
					<div class="col-sm-12" style="padding: 10px;">
						Mohon maaf belum ada data pesanan.<br>
						Silahkan membuat pesanan terlebih dahulu di menu Pesanan, atau bisa klik 
						<b><a href="'.base_url().'pesanan">disini</a></b>.
					</div>
					';
				}
			echo '</div>';
		}else{
            redirect('/login');
        }
	}

	public function getpesanandetail(){
		if(checkingsessionpwt()){
			$id 		= trim(strip_tags(stripslashes($this->input->post('id',true))));

			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$role 		= $userdata['id_role'];

			echo '
			<style>
			.labtypekaos {
			    font-weight: bold;
			    white-space: nowrap;
			}
			</style>
			<div class="row">
			';
				if ($role==1) {
					$condpes 	= '';
				} else {
					$condpes 	= "and userid='$userid'";
				}
				$qPes 		= "
							SELECT a.*,
								(SELECT label from size where id_size=a.ukuran) label_size,
								(SELECT label from color where id=a.warna) label_color,
								(SELECT code_color from color where id=a.warna) codecolor,
								(SELECT label from kaos_type where id=a.kaos_type) label_type_kaos,
								(SELECT file from karakter where id_karakter=a.karakter) pictchar
							FROM pengiriman_detail b left join pesanan a on b.id_pesanan=a.id_pesanan where b.id_pengiriman='$id'
							";
				$getPes 	= $this->db->query($qPes)->result_array();
				$cekPes 	= $this->db->query($qPes)->num_rows();
				if ($cekPes>0) {

				foreach ($getPes as $pes) { 
					$idpes 	= $pes['id_pesanan'];
					if ($pes['status']=='2') {
						$status 	= '<b class="text-warning"><i class="flaticon2-delivery-package"></i> Diproses</b>';
					} else if ($pes['status']=='3') {
						$status 	= '<b class="text-success"><i class="flaticon2-rocket-1"></i> Dikirim</b>';
					} else {
						$status 	= '<b class="text-default"><i class="flaticon-time-1"></i> Menunggu Proses</b>';
					}
					echo '
					<div class="col-lg-4 col-md-6 col-sm-12" style="border: 1px solid rgba(0,0,0,.1);">
						<div class="row">
							<div class="col-6">	
								<img src="'.base_url().'images/char/'.$pes['pictchar'].'" style="width: 100%;">
							</div>
							<div class="col-6" style="padding-top: 1rem">
								<b>'.$pes['custom_nama'].'</b><br>
								Size : '.$pes['label_size'].'<br>
								<i class="fa fa-circle" style="color: '.$pes['codecolor'].'"></i> '.$pes['label_color'].'<br>
								'.$status.'
								<br>
								<div class="labtypekaos">('.str_replace('LENGAN ','',$pes['label_type_kaos']).')</div>
							</div>
						</div>
					</div>';
				}

				} else {
					echo '
					<div class="col-sm-12" style="padding: 10px;">
						Mohon maaf belum ada data pesanan.<br>
						Silahkan membuat pesanan terlebih dahulu di menu Pesanan, atau bisa klik 
						<b><a href="<?PHP echo base_url(); ?>pesanan">disini</a></b>.
					</div>
					';
				}
			echo '</div>';
		}else{
            redirect('/login');
        }
	}
}
