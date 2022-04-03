<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Karakter extends CI_Controller {

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
		// $this->load->model('payment_handler');
		
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

	public function getdata(){
		if(checkingsessionpwt()){
			$getCharA 	= $this->db->query("
						SELECT 
							base.*, 
							(stok_bayi-jml_pes_bayi) as sisa_bayi,
							(stok_anak-jml_pes_anak) as sisa_anak
						FROM (
							SELECT a.*,
								(select count(*) from pesanan where karakter=a.id_karakter and ukuran in (5,6,7,9,10,11)) jml_pes_bayi,
								(select count(*) from pesanan where karakter=a.id_karakter and ukuran not in (5,6,7,9,10,11)) jml_pes_anak,
								(select count(*) from pesanan where karakter=a.id_karakter) jml_tot_pes
							FROM karakter a where is_active!=0 and type!=5
						) as base
						order by jml_tot_pes desc
						")->result_array();
			foreach ($getCharA as $data) {
				$idk 	= $data['id_karakter'];
				echo '
				<div class="col-lg-4">
					<div class="card-body pt-0">
						<div class="d-flex align-items-center mb-8">
							
							<div class="symbol mr-5 pt-1">
								<div class="symbol-label min-w-85px min-h-100px" style="background-image: url('.base_url().'images/char/'.$data['file'].'); height: 130px;"></div>
							</div>
							
							<div class="d-flex flex-column">
								<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg">'.$data['nama'].'</a>
								<span class="text-muted font-weight-bold font-size-sm pb-4"><i class="la la-shopping-cart"></i> '.$data['jml_tot_pes'].'</span>
								<div>
								';
									if ($data['sisa_bayi']<0) { $colbay = 'danger'; } else if ($data['sisa_bayi']==0) { $colbay ='dark'; } else if ($data['sisa_bayi']>0 and $data['sisa_bayi']<=4) { $colbay = 'warning'; } else { $colbay = 'success'; }
									if ($data['sisa_anak']<0) { $colan = 'danger'; } else if ($data['sisa_anak']==0) { $colan ='dark'; } else if ($data['sisa_anak']>0 and $data['sisa_anak']<=2) { $colan = 'warning'; } else { $colan = 'success'; }
									echo '
									<button type="button" class="btn btn-'.$colbay.' btn-sm font-weight-bolder font-size-sm py-2">
										BAYI : '.$data['sisa_bayi'].'
									</button>
									<button type="button" class="btn btn-'.$colan.' btn-sm font-weight-bolder font-size-sm py-2">
										ANAK : '.$data['sisa_anak'].'
									</button>
								</div>
								<div class="mt-5">
									<a class="btn btn-outline-primary font-weight-bolder font-size-sm btn-sm btn-pill btnupdateM" title="Tambah Stok" data-toggle="modal" data-target="#update" data-id="'.$idk.'">
										<i data-toggle="tooltip" title="Tambah Stok" class="la la-cart-plus"></i>
									</a>
									<a class="btn btn-outline-danger font-weight-bolder font-size-sm btn-sm btn-pill btndeleteMenu" data-toggle="modal" data-target="#delete" data-id="'.$idk.'">
										<i class="la la-trash"></i>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
			}
		} else {
			redirect('/panel');
		}
	}

	public function insert(){
		if(checkingsessionpwt()){
			$url 				= "Karakter";
			$activity 			= "INSERT";

			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid				= $userdata['userid'];

			$kat				= trim(strip_tags(stripslashes($this->input->post('kat',true))));
			$nama 				= trim(strip_tags(stripslashes($this->input->post('nama',true))));
			$kode 				= trim(strip_tags(stripslashes($this->input->post('kode',true))));
			$fileName 			= str_replace(' ','_',$_FILES['file']['name']);

			$config['upload_path'] 	= './images/char/';
			$config['file_name'] 	= $fileName;
			$config['allowed_types']= 'gif|jpg|png|jpeg';
			$this->load->library('upload');
			$this->upload->initialize($config);
			if(! $this->upload->do_upload('file') )
			$this->upload->display_errors();
			$media = $this->upload->data('file');

			$config['upload_path'] 	= './images/charprint/';
			$config['file_name'] 	= $fileName;
			$config['allowed_types']= 'gif|jpg|png|jpeg';
			$this->load->library('upload');
			$this->upload->initialize($config);
			if(! $this->upload->do_upload('file') )
			$this->upload->display_errors();
			$media = $this->upload->data('file');

			$rows 				= $this->db->query("
								INSERT INTO karakter (nama,kode,file,type,is_active,stok_bayi,stok_anak) values 
								('$nama','$kode','$fileName','$kat','1','0','0')
								");
			
			if($rows) {
				$id				= $this->db->insert_id();
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
								SELECT base.*,
									(stok_bayi-jml_pes_bayi) as sisa_bayi,
									(stok_anak-jml_pes_anak) as sisa_anak
								FROM (
									select a.*,
										(select count(*) from pesanan where karakter=a.id_karakter and ukuran in (5,6,7,9,10,11)) jml_pes_bayi,
										(select count(*) from pesanan where karakter=a.id_karakter and ukuran 
										not in (5,6,7,9,10,11)) jml_pes_anak,
										(select count(*) from pesanan where karakter=a.id_karakter) jml_tot_pes
									from karakter a where id_karakter='$id'
								) as base
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
			$url 		= "Karakter";
			$activity 	= "UPDATE";
			
			$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$userdata 		= $this->session->userdata('sesspwt'); 
			
			$userid			= $userdata['userid'];

			$sbplus				= trim(strip_tags(stripslashes($this->input->post('ed_b_plus',true))));
			$saplus 			= trim(strip_tags(stripslashes($this->input->post('ed_a_plus',true))));

			$rows 			= $this->db->query("
							UPDATE karakter SET 
								stok_bayi 		= stok_bayi+$sbplus,
								stok_anak		= stok_anak+$saplus
							WHERE id_karakter='$id'
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
			$url 		= "Karakter";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$rows 		= $this->db->query("
						UPDATE karakter set is_active='0' where id_karakter='$cond'
						");
			
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

}
