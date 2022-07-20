<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

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
		
		ini_set('max_execution_time', 123456789);
		ini_set("memory_limit","2256M");
			
		// $session = checkingsessionpwt();
		$session	 = $this->session->userdata('sesspwt'); 
		// $this->profile = $session['profile'];
		$this->profile = $session['id_role'];
		// $menu 		 = uri_string();
		// $data_akses  = $this->query->getAkses($profile,$menu);
		// $shift 		 = array_shift($data_akses);
		// $this->akses = $shift['akses'];
		
		// echo $session['id_role'];
		$userid 	 	= $session['userid'];
		
		$getAksesUAM		= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
		$dataAksesUAM		= array_shift($getAksesUAM);
		
		$segmen	 = str_replace(",","','",$dataAksesUAM['akses_segmen']);
		$treg	 = str_replace(",","','",$dataAksesUAM['akses_treg']);
		$witel	 = str_replace(",","','",$dataAksesUAM['akses_witel']);
		$am	 	 = str_replace(",","','",$dataAksesUAM['akses_am']);
		
		if ($dataAksesUAM['akses_divisi']=='all') { $this->divisiUAM 	= ''; } else { $this->divisiUAM 	= $dataAksesUAM['akses_divisi']; }
		if ($dataAksesUAM['akses_segmen']=='all') { $this->segmenUAM 	= ''; } else { $this->segmenUAM 	= "and id_segmen in ('$segmen')"; }
		if ($dataAksesUAM['akses_treg']=='all')   { $this->tregUAM 		= ''; } else { $this->tregUAM 		= "and treg in ('$treg')"; }
		if ($dataAksesUAM['akses_witel']=='all')  { $this->witelUAM 	= ''; } else { $this->witelUAM 		= "and id_witel in ('$witel')"; }
		if ($dataAksesUAM['akses_am']=='all') 	  { $this->amUAM 		= ''; } else { $this->amUAM 		= "and nik_am in ('$am')"; }
    }
	
	public function index(){
		if(checkingsessionpwt()){
			$this->load->view('panel/dashboard');
		} else {
			redirect('/panel');
		}
	}

	public function loadData($record=0) {
		$recordPerPage = 12;
		if($record != 0){
			$record = ($record-1) * $recordPerPage;
		}      	
      	$recordCount = $this->getRecordCount();
      	$empRecord = $this->getRecord($record,$recordPerPage);
      	$config['base_url'] = base_url().'blog/loadData';
      	$config['use_page_numbers'] = FALSE;
		$config['next_link'] = '<i class="fa fa-angle-right"></i>';
		$config['prev_link'] = '<i class="fa fa-angle-left"></i>';
		$config['total_rows'] = $recordCount;
		$config['per_page'] = $recordPerPage;
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();
		$data['empData'] = $empRecord;
		echo json_encode($data);		
	}

	public function getRecord($rowno,$rowperpage) {			
		$this->db->select("a.*,
                                (select menu from menu_site where id_menu=a.id_menu) as menu,
                                (select name from user where userid=a.create_by) as createby,
                                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                                WHERE xa.menu='Manage Berita' AND xa.data = a.id_blog ORDER BY xa.date_time DESC limit 1)as update_by,
                                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                                WHERE xa.menu='Manage Berita' AND xa.data = a.id_blog ORDER BY xa.date_time DESC limit 1)as last_update");
		$this->db->from('blog a');
		$this->db->order_by('id_blog', 'DESC'); 
        $this->db->limit($rowperpage, $rowno);  
		$query = $this->db->get();       	
		return $query->result_array();
	}
	public function getRecordCount() {
    	$this->db->select('count(*) as allcount');
      	$this->db->from('blog');
      	$this->db->order_by('id_blog', 'DESC');  
      	$query = $this->db->get();
      	$result = $query->result_array();      
      	return $result[0]['allcount'];
    }

	public function deletemen(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('menu','id_menu',$cond);
			$userid		= $userdata['userid'];
			$datelog	= date('Y-m-d H:i:s');
			$ipaddr		= $_SERVER['REMOTE_ADDR'];
			$url 		= "Manage Menu";
			$activity 	= "DELETE";
			if(isset($rows)) {
				$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$cond','$ipaddr'");
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}
}
