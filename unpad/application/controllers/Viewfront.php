<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewfront extends CI_Controller {

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
	private $perPage = 5;

	public function __construct(){
        parent::__construct();
		date_default_timezone_set("Asia/Bangkok");
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->model('query');
		$this->load->model('formula');

		ini_set('max_execution_time', 123456789);
		ini_set("memory_limit","2256M");
		
		$ip   		= $_SERVER['REMOTE_ADDR'];
		$tanggal 	= date("Ymd");
		$waktu  	= time();
		$bln		= date("m");
		$tgl		= date("d");
		$blan		= date("Y-m");
		$thn		= date("Y");
		$tglk		= $tgl-1;
		
		$s			= $this->query->getNumRows('konter','*',"WHERE ip='$ip' AND tanggal='$tanggal'")->num_rows();
		
		if($s == 0) {
			$this->query->insertData('konter',"ip, tanggal, hits, online","'$ip','$tanggal','1','$waktu'");
		} 
		else{
			$this->query->updateData('konter',"hits=hits+1, online='$waktu'","WHERE ip='$ip' AND tanggal='$tanggal'");
		}
		if($tglk=='1' | $tglk=='2' | $tglk=='3' | $tglk=='4' | $tglk=='5' | $tglk=='6' | $tglk=='7' | $tglk=='8' | $tglk=='9'){
			$kemarin = $this->query->getData('konter','*',"WHERE tanggal='$thn-$bln-0$tglk'");
		} else {
			$kemarin = $this->query->getData('konter','*',"WHERE tanggal='$thn-$bln-$tglk'");
		}
    }
	
	public function cekcounter(){
		$ip   		= $_SERVER['REMOTE_ADDR'];
		$tanggal 	= date("Ymd");
		$waktu  	= time();
		$bln		= date("m");
		$tgl		= date("d");
		$blan		= date("Y-m");
		$thn		= date("Y");
		$tglk		= $tgl-1;
		
		$s			= $this->query->getNumRows('konter','*',"WHERE ip='$ip' AND tanggal='$tanggal'")->num_rows();
		
		if($s == 0){
			$this->query->insertData('konter',"ip, tanggal, hits, online","'$ip','$tanggal','1','$waktu'");
		} 
		else{
			$this->query->updateData('konter',"hits=hits+1, online='$waktu'","WHERE ip='$ip' AND tanggal='$tanggal'");
		}
		if($tglk=='1' | $tglk=='2' | $tglk=='3' | $tglk=='4' | $tglk=='5' | $tglk=='6' | $tglk=='7' | $tglk=='8' | $tglk=='9'){
			$kemarin = $this->query->getData('konter','*',"WHERE tanggal='$thn-$bln-0$tglk'");
		} else {
			$kemarin = $this->query->getData('konter','*',"WHERE tanggal='$thn-$bln-$tglk'");
		}
		// $bulan				= mysql_query("SELECT * FROM konter WHERE tanggal LIKE '%$blan%'");
		// $bulan1				= mysql_num_rows($bulan);
		// $tahunini			= mysql_query("SELECT * FROM konter WHERE tanggal LIKE '%$thn%'");
		// $tahunini1			= mysql_num_rows($tahunini);
		// $pengunjung       	= mysql_num_rows(mysql_query("SELECT * FROM konter WHERE tanggal='$tanggal' GROUP BY ip"));
		// $totalpengunjung  	= mysql_result(mysql_query("SELECT COUNT(hits) FROM konter"), 0); 
		// $hits             	= mysql_fetch_assoc(mysql_query("SELECT SUM(hits) as hitstoday FROM konter WHERE tanggal='$tanggal' GROUP BY tanggal")); 
		// $totalhits        	= mysql_result(mysql_query("SELECT SUM(hits) FROM konter"), 0); ;
		// $pengunjungonline 	= mysql_num_rows(mysql_query("SELECT * FROM konter WHERE online = '$waktu'"));
		// $kemarin1 			= mysql_num_rows($kemarin);
	}
	
	public function index(){
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$qPage 		= "select * from menu_site where id_menu='1'";
		$gPage 		= $this->query->getDatabyQ($qPage);
		$dPage		= array_shift($gPage);
		$stylepage	= $dPage['style'];
		$background	= $dPage['background'];
		$coreid		= $dPage['flag_core'];

		if(get_cookie('lang_is') === 'en'){
			$menu		= $dPage['menu_en'];
		} else {
			$menu		= $dPage['menu'];
		}

		$data['idmenu']			= $dPage['id_menu'];
		$data['coreid']			= $coreid;
		$data['background']		= $background;
		$data['menu']			= $menu;
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");

		$data['coreid']			= $coreid;

		$getCore 		= $this->db->query("SELECT * FROM config_web where id_web='$coreid'")->result_array();
		$dcore 			= array_shift($getCore);
		$data['cblog'] 	= $dcore['blog'];
		$data['cbase'] 	= $dcore['basic_content'];
		$data['cour'] 	= $dcore['ourteam'];
		$data['ccont']  = $dcore['contact'];

		$this->load->view('/front/home/home',$data);
	}
	
	public function about(){
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
		
		$this->load->view('/front/about/about',$data);
	}
	
	public function category($id){
		$idcat					= str_replace('-',' ',$id);
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
		$data['getRoomCatBy'] 	= $this->query->getData('category_room','*',"WHERE LOWER(name_cat)='$idcat' order by id_cat ASC");
		
		$this->load->view('/front/rooms/rooms',$data);
	}
	
	public function post($id){
		$name					= str_replace('-',' ',$id);
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
		$data['getEventDet'] 	= $this->query->getData('event','*',"WHERE LOWER(title)='$name'");
		
		$this->load->view('/front/event/detail',$data);
	}
	
	public function gallery(){
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
		
		$this->load->view('/front/gallery/all',$data);
	}
	
	public function contact(){
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
		
		$this->load->view('/front/about/contact',$data);
	}
	
	public function reservation(){
		if(checkingbasket()){
			$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
			$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
			$data['reserv'] 		= $this->session->userdata('basket');
			$data['member'] 		= $this->session->userdata('member');
			
			// $reserv  = $this->session->userdata('basket');
			// print json_encode($reserv);
			
			$this->load->view('/front/booking/reserv',$data);
		} else {
			redirect('/engine/booking');
		}
	}
	
	public function thankyou(){
		if(checkingmember()) {
			if(checkingbasket()){
				redirect('/engine/reservation');
			} else {
				$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
				$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
				$data['reserv'] 		= $this->session->userdata('basket');
				$data['member'] 		= $this->session->userdata('member');
				
				$this->load->view('/front/booking/finish',$data);
			}
		} else {
			redirect('/login');
		}
	}
	
	public function login(){
		if(checkingmember()) {
			redirect('/profile');
		} else {
			$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
			$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
				
			$this->load->view('/front/member/login',$data);
		}
	}
	
	public function profile(){
		if(checkingmember()) {
			$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
			$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
			$chaceMember			= $this->session->userdata('member');
			$getMember 				= $this->query->getData('member','*',"WHERE id_member='".$chaceMember['id_member']."'");
			$data['member'] 		= array_shift($getMember);
			
			$sekarang	= date('Y-m-d H:i:s');
			$getData	= $this->query->getData('booking','*',"WHERE jatuhtempo<'".$sekarang."' AND status='0' AND id_member='".$chaceMember['id_member']."'");
			foreach($getData as $data){
				$updateBook		= $this->query->updateData('booking',"status='3'","WHERE kode_booking='".$data['kode_booking']."'");
			}
				
			$this->load->view('/front/member/profile',$data);
		} else {
			redirect('/login');
		}
	}
	
	public function history(){
		if(checkingmember()) {
			$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
			$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
			$chaceMember			= $this->session->userdata('member');
			$getMember 				= $this->query->getData('member','*',"WHERE id_member='".$chaceMember['id_member']."'");
			$data['member'] 		= array_shift($getMember);
			
			$sekarang	= date('Y-m-d H:i:s');
			$getData	= $this->query->getData('booking','*',"WHERE jatuhtempo<'".$sekarang."' AND status='0' AND id_member='".$chaceMember['id_member']."'");
			foreach($getData as $data){
				$updateBook		= $this->query->updateData('booking',"status='3'","WHERE kode_booking='".$data['kode_booking']."'");
			}
				
			$this->load->view('/front/member/history',$data);
		} else {
			redirect('/login');
		}
	}
	
	public function confirmation(){
		if(checkingmember()) {
			$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
			$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
			
			$chaceMember			= $this->session->userdata('member');
			$getMember 				= $this->query->getData('member','*',"WHERE id_member='".$chaceMember['id_member']."'");
			$data['member'] 		= array_shift($getMember);
			
			$data['getDataBook']	= $this->query->getData('booking','*',"WHERE id_member='".$chaceMember['id_member']."' and status='0'");
			$data['getDataBank']	= $this->query->getData('bank','*',"ORDER BY nama_bank ASC");
			
			$sekarang	= date('Y-m-d H:i:s');
			$getData	= $this->query->getData('booking','*',"WHERE jatuhtempo<'".$sekarang."' AND status='0' AND id_member='".$chaceMember['id_member']."'");
			foreach($getData as $data){
				$updateBook		= $this->query->updateData('booking',"status='3'","WHERE kode_booking='".$data['kode_booking']."'");
			}
				
			$this->load->view('/front/member/confirmation',$data);
		} else {
			redirect('/login');
		}
	}

	public function page($id){

		$qPage 		= "select * from menu_site where link='$id'";
		$gPage 		= $this->query->getDatabyQ($qPage);
		$dPage		= array_shift($gPage);
		$stylepage	= $dPage['style'];
		$background	= $dPage['background'];
		$coreid		= $dPage['flag_core'];

		if(get_cookie('lang_is') === 'en'){
			$menu		= $dPage['menu_en'];
		} else {
			$menu		= $dPage['menu'];
		}

		$data['idmenu']			= $dPage['id_menu'];
		$data['coreid']			= $coreid;
		$data['background']		= $background;
		$data['menu']			= $menu;
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");

		$getCore 		= $this->db->query("SELECT * FROM config_web where id_web='$coreid'")->result_array();
		$dcore 			= array_shift($getCore);
		$data['cblog'] 	= $dcore['blog'];
		$data['cbase'] 	= $dcore['basic_content'];
		$data['cour'] 	= $dcore['ourteam'];
		$data['ccont']  = $dcore['contact'];
		
		if ($stylepage=='download') {
			$this->load->database();
			$count = $this->db->get('album')->num_rows();
			if(!empty($this->input->get("page"))){
				$start = ceil($this->input->get("page") * $this->perPage);
				$query = $this->db->limit($start, $this->perPage)->get("album");
				$data['posts'] = $query->result();
				$result = $this->load->view('front/content/downloadmore', $data);
				// echo json_encode($result);
			}else{
		      	$query = $this->db->limit(9, $this->perPage)->get("album");
	    	  	$data['posts'] = $query->result();
			  	$this->load->view('front/content/download', $data);
			}
		} else if ($stylepage=='home') {
			$this->load->view('/front/home/home' ,$data);
		} else {
			$this->load->view('/front/content/'.$stylepage.'' ,$data);
		}
	}

	public function content($id){

		$qPage 		= "select a.*, b.flag_core from content a left join menu_site b on a.id_menu=b.id_menu where id_content='$id'";
		$gPage 		= $this->query->getDatabyQ($qPage);
		$dPage		= array_shift($gPage);
		
		if(get_cookie('lang_is') === 'en'){
			$menu		= $dPage['title_en'];
		} else {
			$menu		= $dPage['title'];
		}

		$data['coreid']			= $dPage['flag_core'];
		$data['id']				= $dPage['id_content'];
		$data['background']		= $background;
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		
		$this->load->view('/front/content/detailcontent' ,$data);
	}

	public function blog($id){
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['id'] 			= $id;
		
		$this->load->view('/front/content/detailblog' ,$data);
	}

	public function doc($id){
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");

		$qPage 		= "
					select
		                a.*,
		                (select link from menu_site where id_menu=a.id_menu) as menulink,
		                (select menu from menu_site where id_menu=a.id_menu) as menuname,
		                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
		                WHERE xa.menu='Manage Document' AND xa.data = a.id_doc ORDER BY xa.date_time DESC limit 1)as update_by,
		                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
		                WHERE xa.menu='Manage Document' AND xa.data = a.id_doc ORDER BY xa.date_time DESC limit 1)as last_update
		            from
		            document a
		            where link='$id'
					";
		$gPage 		= $this->query->getDatabyQ($qPage);
		$dPage		= array_shift($gPage);
		$folder		= $dPage['title'];

		$data['link']			= $id;
		$data['doclink']		= $dPage['menulink'];
		$data['menuname']		= $dPage['menuname'];
		$data['id_doc']			= $dPage['id_doc'];
		$data['folder']			= $folder;
		$data['update_by']		= $dPage['update_by'];
		$data['last_update']	= $dPage['last_update'];
		
		$this->load->view('/front/content/detaildoc' ,$data);
	}

	public function works($id){
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['id'] 			= $id;
		
		$this->load->view('/front/content/worksdetail' ,$data);
	}

	public function event($id){
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['id'] 			= $id;

		$qPage      			= "select * from event where link='$id'";
		$gPage				    = $this->query->getDatabyQ($qPage);
		$dPage      			= array_shift($gPage);
		$idevent				= $dPage['id_event'];
		$data['idevent']		= $idevent;

		$gtotal_data 			= $this->query->getDatabyQ("select count(*) total from event_detail where id_event='$idevent'");
		$dtotal_data 			= array_shift($gtotal_data);
		$total_data 			= $dtotal_data['total'];
        $content_per_page 		= 9; 
        $data['total_data'] 	= ceil($total_data/$content_per_page);

		
		$this->load->view('/front/content/eventdetail' ,$data);
	}
	
}
