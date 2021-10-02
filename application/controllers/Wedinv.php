<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wedinv extends CI_Controller {

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
		
		$this->dbw = $this->load->database('dbw', TRUE);

        parent::__construct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->model('query'); 
		$this->load->model('formula'); 
		$this->load->model('datatable'); 
		$this->load->model('stok_handler');
		
		ini_set('max_execution_time', 123456);
		ini_set("memory_limit","1256M");
			
		// $session = checkingsessionpwt();
		$session	 = $this->session->userdata('sesspwt'); 
    }
	
	public function index(){
		// if(checkingsessionpwt()){
			// $this->load->view('panel/dashboard');
		// } else {
			// redirect('/panel');
		// }
	}

	public function insert(){
		$url 				= "Wedding Invitation";
		$activity 			= "INSERT";

		$orderid			= trim(strip_tags(stripslashes($this->input->post('oid',true))));
		$gsname				= trim(strip_tags(stripslashes($this->input->post('gsname',true))));
		$gsmsg 				= trim(strip_tags(stripslashes($this->input->post('gsmsg',true))));
		$now 				= date('Y-m-d');

		$rows 				= $this->dbw->query("
							INSERT INTO detail_ucapan (orderid,name,msg,createddate) values 
							('$orderid','$gsname','$gsmsg','$now')
							");
		
		if($rows) {
			$id				= $this->dbw->insert_id();
			$log 			= $this->query->insertlog($activity,$url,$orderid);

			print json_encode(array('success'=>true,'total'=>1));
		} else {
			echo "";
		}
	}	

	public function reload($id){
		$dataRoles	= $this->dbw->query("
					SELECT 
							a.*
						from detail_ucapan a where a.orderid ='$id' order by id desc
					")->result_array();

		if (isset($id) && !empty($id)) {
			foreach($dataRoles as $ucp) {
				echo '
				<div class="user-guestbook">
					<div>
						<img src="'.base_url().'assets/themepw1/wp-content/uploads/2020/12/07-2.png" title="07.png" alt="07.png" />
					</div>

					<div class="guestbook">
						<div class="guestbook-name">'.$ucp['name'].'</div>
						<div class="guestbook-message">'.$ucp['msg'].'</div>
					</div>
				</div>
				';
			}
		}
	}	

	public function confirm(){
		$url 				= "Wedding Invitation";
		$activity 			= "CONFIRMATION";

		$name				= trim(strip_tags(stripslashes($this->input->post('rname',true))));
		$address 			= trim(strip_tags(stripslashes($this->input->post('raddr',true))));
		$confirm 			= trim(strip_tags(stripslashes($this->input->post('konfhadir',true))));
		
		$json				= array(
								'name'		=> $name,
								'address'	=> $address,
								'confirm'	=> $confirm
							);
		print json_encode($json);
	}
}
