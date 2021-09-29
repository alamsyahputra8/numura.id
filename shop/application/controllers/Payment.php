<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

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
		$this->load->model('payment_handler');
		
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

			$columnsDefault = [
				'name'			=> true,
				'masuk'			=> true,
				'keluar'		=> true,
				'tgl'			=> true,
				'type'			=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;

			// $jsonfile	= base_url().'user/data';
			$jsonfile	= $this->payment_handler->data();

			$this->datatable->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function insert(){
		if(checkingsessionpwt()){
			$url 				= "Pembayaran";
			$activity 			= "INSERT";

			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid				= $userdata['userid'];

			$user				= trim(strip_tags(stripslashes($this->input->post('user',true))));
			$masuk 				= trim(strip_tags(stripslashes($this->input->post('masuk',true))));
			$keluar 			= trim(strip_tags(stripslashes($this->input->post('keluar',true))));
			$tgl 				= trim(strip_tags(stripslashes($this->input->post('tgl',true))));
			$type 				= trim(strip_tags(stripslashes($this->input->post('type',true))));

			$rows 				= $this->db->query("
								INSERT INTO payment (userid,masuk,keluar,tgl_paid,type) values 
								('$user','$masuk','$keluar','$tgl','$type')
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
								SELECT 
									a.*,
									(SELECT name from user where userid=a.userid) title
								from payment a where a.id_payment ='$id'
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
			$url 		= "Pembayaran";
			$activity 	= "UPDATE";
			
			$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$userdata 		= $this->session->userdata('sesspwt'); 
			
			$userid			= $userdata['userid'];

			$user				= trim(strip_tags(stripslashes($this->input->post('ed_user',true))));
			$masuk 				= trim(strip_tags(stripslashes($this->input->post('ed_masuk',true))));
			$keluar 			= trim(strip_tags(stripslashes($this->input->post('ed_keluar',true))));
			$tgl 				= trim(strip_tags(stripslashes($this->input->post('ed_tgl',true))));
			$type 				= trim(strip_tags(stripslashes($this->input->post('ed_type',true))));

			$rows 			= $this->db->query("
							UPDATE payment SET 
								userid 			= '$user',
								masuk			= '$masuk',
								keluar			= '$keluar',
								tgl_paid		= '$tgl',
								type			= '$type'
							WHERE id_payment='$id'
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
			$url 		= "Pembayaran";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$rows = $this->query->deleteData('payment','id_payment',$cond);
			
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
