<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jsondata extends CI_Controller {

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
		
		ini_set('max_execution_time', 123456);
		ini_set("memory_limit","1256M");
		error_reporting(0);
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
			
		} else {
			
		}
	}

	public function datauser(){
		$menu = 'panel/user';
		$data_aksess = $this->query->getAkses($this->profile,$menu);
		//foreach ($data_aksess as $shift) { $akses = $shift['akses']; }
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qUser				= "
							select a.*, b.nama_role, b.desc_role from user a LEFT JOIN role b on a.id_role=b.id_role ORDER BY userid DESC
							";
		$dataUsr			= $this->query->getDatabyQ($qUser);

		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qUser)->num_rows();

		if ($cek>0) {
			foreach($dataUsr as $data) { 
				$no++;

				$id = $data['userid'];
				$filefound = $data['picture'];
				$url = base_url()."images/user/".$filefound;
				$exis = file_exists(FCPATH."images/user/".$filefound);
				/*if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}*/
				
				if ($data['id_role']==0) {
					$namarole	= 'No Access';
				} else {
					$namarole	= $data['nama_role'];
				}

				if ($data['picture']=='') {
					$picture 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['name'],0,1).'</span></center>';
				} else {
					$picture 		= '
								<div class="kt-user-card-v2">
	                                <div class="kt-user-card-v2__pic" style="margin: 0 auto;">
	                                    <center><img src="'.base_url().'images/user/'.$filefound.'" class="m-img-rounded kt-marginless" alt="photo"></center>
	                                </div>
	                            </div>';
				}
				
				
				$buttonupdate 	= getRoleUpdate($akses,'update',$id);
				$buttondelete 	= getRoleDelete($akses,'delete',$id);
				
				
				$row = array(
					"picture"		=> $picture,
					"name"			=> $data['name'],
					"username"		=> $data['username'],
					"email"			=> $data['email'],
					"role"			=> $namarole,
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataroles(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/roles');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					select
						a.*,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu='Manage Role' AND xa.data = a.id_role ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu='Manage Role' AND xa.data = a.id_role ORDER BY xa.date_time DESC limit 1)as last_update
					from
					role a
					ORDER BY a.nama_role ASC
				";
				//echo $qRole;
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_role'];
				
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"nama_role"		=> $data['nama_role'],
					"desc_role"		=> $data['desc_role'],
					"update_by"		=> $data['update_by'],
					"last_update"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datamenus(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/menus');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "

					select
						a.*,
						(case 
							when style='basic' then 'Article' 
							when style='blog' then 'Berita' 
							when style='document' then 'Document' 
							when style='gallery' then 'Gallery' 
							when style='link' then 'External Link'
							when style='ourteam' then 'Our Team'
						else 'Article' end)as stylename,
						 (SELECT nama FROM config_web where id_web = a.flag_website) as flag_web,
						  (SELECT nama FROM config_web where id_web = a.flag_core) as flag_core,
						(case when parent='0' then 'Parent Menu' else (select menu from menu_site where id_menu=a.parent) end )as parentname,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Menus' AND xa.data = a.id_menu ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Menus' AND xa.data = a.id_menu ORDER BY xa.date_time DESC limit 1)as last_update
					from
					menu_site a
					ORDER BY a.id_menu desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$filefound = $data['background'];
				$url = base_url()."images/content/".$filefound;
				$exis = file_exists(FCPATH."images/content/".$filefound);
				if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}

				if ($data['type']=='0') {
					$id = $data['id_menu'];
				} else {
					$id = '0';
				}

				if ($data['background']=='') {
					$background 	= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['menu'],0,1).'</span></center>';
				} else {
					$background 	= '
								<div class="kt-user-card-v2">
	                                <div class="" style="margin: 0 auto; max-width: 150px;">
	                                    <center><img src="'.base_url().'images/content/'.$filefound.'" class="img-responsive" style="max-width:150px;" alt="photo"></center>
	                                </div>
	                            </div>';
				}
				if($data['flag_website']==1){
					$flag_web = 'Web Fakultas';
				}else if($data['flag_website']==2){
					$flag_web = 'Web Prodi S1 & S2';
				}else if($data['flag_website']==3){
					$flag_web = 'Web Alumni & Mitra';					
				}
				 
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array( 
					"flag_web"		=> '<strong>'.$data['flag_web'].'</strong>',
					"flag_core"		=> '<strong>'.$data['flag_core'].'</strong>',
					"menu"			=> $data['menu'], 
					"parent"		=> $data['parentname'],
					"sort"			=> $data['sort'],
					"link"			=> $data['link'],
					"style"			=> $data['stylename'],
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datacontent(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/content');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(select menu from menu_site where id_menu=a.id_menu) as menu,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Content' AND xa.data = a.id_content ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Content' AND xa.data = a.id_content ORDER BY xa.date_time DESC limit 1)as last_update
					from
					content a
					ORDER BY a.id_content desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_content'];
				
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"title"			=> $data['title'],
					"menu"			=> $data['menu'],
					"headline"		=> $data['headline'],
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}
	
	public function dataconfigweb(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/content');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*, 
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Config Web' AND xa.data = a.id_web ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Config Web' AND xa.data = a.id_web ORDER BY xa.date_time DESC limit 1)as last_update
					from
					config_web a
					ORDER BY a.id_web desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_web'];
				
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);
				if($id ==1 or $id ==4){
					$idx = '';
				}else{
					$idx = $id;
				}
				 
				$row = array(
					"nama"			=> $data['nama'],
					"deskripsi"		=> $data['deskripsi'],  
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $idx
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datalog(){
		error_reporting(0);
		
		$menu = 'panel/log';
		$data_aksess = $this->query->getAkses($this->profile,$menu);
		//foreach ($data_aksess as $shift) { $akses = $shift['akses']; }
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select * from data_log where menu not in ('Manage Menus','Manage Role') order by id_log desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_log'];

				$getdatausers			= $this->query->getData('user','*','WHERE userid="'.$data['userid'].'"');
				
				$datauser 	= array_shift($getdatausers);
				$user = $datauser['name']; 
				$filefound = $datauser['picture'];
				$url = base_url()."images/user/".$filefound;
				$exis = file_exists(FCPATH."images/user/".$filefound);

				/*if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}*/

				if ($datauser['picture']=='') {
					$picture 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($datauser['name'],0,1).'</span></center>';
				} else {
					$picture 		= '
								<div class="kt-user-card-v2">
	                                <div class="kt-user-card-v2__pic" style="margin: 0 auto;">
	                                    <center><img src="'.base_url().'images/user/'.$filefound.'" class="m-img-rounded kt-marginless" alt="photo"></center>
	                                </div>
	                            </div>';
				}
				
				if(trim($data['menu'])=='Manage Role'){
					$tables = 'role';
					$parameter = 'nama_role as datass';
					$condition = 'where id_role="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				if(trim($data['menu'])=='Manage User'){
					$tables = 'user';
					$parameter = 'username as datass';
					$condition = 'where userid="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				if(trim($data['menu'])=='Manage Menu'){
					$tables = 'menu';
					$parameter = 'menu as datass';
					$condition = 'where id_menu="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				if(trim($data['menu'])=='Manage Content'){
					$tables = 'content';
					$parameter = 'title as datass';
					$condition = 'where id_content="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				if(trim($data['menu'])=='Manage Berita'){
					$tables = 'blog';
					$parameter = 'title as datass';
					$condition = 'where id_blog="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				if(trim($data['menu'])=='Manage Link'){
					$tables = 'link';
					$parameter = 'title as datass';
					$condition = 'where id_link="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				if(trim($data['menu'])=='Manage Mail Site'){
					$tables = 'mail_site';
					$parameter = 'email as datass';
					$condition = 'where id_email="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				if(trim($data['menu'])=='Manage Banner'){
					$tables = 'banner';
					$parameter = 'title as datass';
					$condition = 'where id_banner="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}

				if(trim($data['menu'])=='User Profile'){
					$tables = 'user';
					$parameter = 'name as datass';
					$condition = 'where userid="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}

				if(trim($data['menu'])=='Site Configuration'){
					$tables = 'configsite';
					$parameter = "name_site as datass";
					$condition = 'where id_site="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}

				if(trim($data['menu'])=='Manage Document'){
					$tables = 'document';
					$parameter = "title as datass";
					$condition = 'where id_doc="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}

				if(trim($data['menu'])=='Manage Videos'){
					$tables = 'videos';
					$parameter = "'Video Youtube' as datass";
					$condition = 'where id_video="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}

				if(trim($data['menu'])=='Manage Photos'){
					$tables = 'photos';
					$parameter = "'Photos' as datass";
					$condition = 'where id_photo="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}

				if(trim($data['menu'])=='INBOX'){
					$tables = 'inbox';
					$parameter = "email as datass";
					$condition = 'where id_inbox="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
					}
				}
				
				
				$buttonupdate 	= getRoleUpdate($akses,'update',$id);
				$buttondelete 	= getRoleDelete($akses,'delete',$id);
				
				
				$row = array(
					"date"		=> $data['date_time'],
					"photo"		=> $picture,
					"user"		=> $user,
					"activity"	=> $data['activity'],
					"id"		=> $data['data'],
					"data"		=> $dat,
					"menu"		=> $data['menu']
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datablog(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/blog');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(select menu from menu_site where id_menu=a.id_menu) as menu,
						(select category from category_blog where id=a.id_category) as category,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Berita' AND xa.data = a.id_blog ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Berita' AND xa.data = a.id_blog ORDER BY xa.date_time DESC limit 1)as last_update
					from
					blog a
					ORDER BY a.id_blog desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_blog'];

				$filefound = $data['picture'];
				$url = base_url()."images/content/".$filefound;
				$exis = file_exists(FCPATH."images/content/".$filefound);
				if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}
				
				if ($data['picture']=='') {
					$picture 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['title'],0,1).'</span></center>';
				} else {
					$picture 		= '
								<div class="kt-user-card-v2">
	                                <div class="" style="margin: 0 auto; max-width: 150px;">
	                                    <center><img src="'.base_url().'images/content/'.$filefound.'" class="img-responsive" style="max-width:150px;" alt="photo"></center>
	                                </div>
	                            </div>';
				}
				
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"picture"		=> $picture,
					"title"			=> $data['title'],
					"menu"			=> $data['menu'],
					"headline"		=> $data['headline'],
					"category"		=> $data['category'],
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datalink(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/link');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Link' AND xa.data = a.id_link ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Link' AND xa.data = a.id_link ORDER BY xa.date_time DESC limit 1)as last_update
					from
					link a
					ORDER BY a.id_link desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_link'];

				$filefound = $data['picture'];
				$url = base_url()."images/link/".$filefound;
				$exis = file_exists(FCPATH."images/link/".$filefound);
				if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}
				
				if ($data['picture']=='') {
					$picture 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['title'],0,1).'</span></center>';
				} else {
					$picture 		= '
								<div class="kt-user-card-v2">
	                                <div class="" style="margin: 0 auto; max-width: 150px;">
	                                    <center><img src="'.base_url().'images/link/'.$filefound.'" class="img-responsive" style="max-width:150px;" alt="photo"></center>
	                                </div>
	                            </div>';
				}
				
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"picture"		=> $picture,
					"title"			=> $data['title'],
					"link"			=> $data['link'],
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}
	
	public function datourteam(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/link');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Our Team' AND xa.data = a.id ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Our Team' AND xa.data = a.id ORDER BY xa.date_time DESC limit 1)as last_update
					from
					ourteam a
					ORDER BY a.id desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id'];

				$filefound = $data['picture'];
				$url = base_url()."images/ourteam/".$filefound;
				$exis = file_exists(FCPATH."images/ourteam/".$filefound);
				if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}
				
				if ($data['picture']=='') {
					$picture 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['title'],0,1).'</span></center>';
				} else {
					$picture 		= '
								<div class="kt-user-card-v2">
	                                <div class="" style="margin: 0 auto; max-width: 150px;">
	                                    <center><img src="'.base_url().'images/ourteam/'.$filefound.'" class="img-responsive" style="max-width:150px;" alt="photo"></center>
	                                </div>
	                            </div>';
				}
				
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"picture"		=> $picture,
					"name"			=> $data['name'],
					"email"			=> $data['email'],
					"position"		=> $data['position'],
					"department"	=> $data['department'],
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datamail(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/mailsite');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Mail Site' AND xa.data = a.id_email ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Mail Site' AND xa.data = a.id_email ORDER BY xa.date_time DESC limit 1)as last_update
					from
					mail_site a
					ORDER BY a.id_email desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_email'];

				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"email"			=> $data['email'],
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function databanner(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/banner');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Banner' AND xa.data = a.id_banner ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Banner' AND xa.data = a.id_banner ORDER BY xa.date_time DESC limit 1)as last_update
					from
					banner a
					ORDER BY a.id_banner desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_banner'];

				$filefound = $data['img'];
				$url = base_url()."images/slides/".$filefound;
				$exis = file_exists(FCPATH."images/slides/".$filefound);
				if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}
				
				if ($data['img']=='') {
					$picture 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['title'],0,1).'</span></center>';
				} else {
					$picture 		= '
								<div class="kt-user-card-v2">
	                                <div class="" style="margin: 0 auto; max-width: 150px;">
	                                    <center><img src="'.base_url().'images/slides/'.$filefound.'" class="img-responsive" style="max-width:150px;" alt="photo"></center>
	                                </div>
	                            </div>';
				}
				$filefound_en = $data['img_en'];
				$url_en = base_url()."images/slides/".$filefound_en;
				$exis_en = file_exists(FCPATH."images/slides/".$filefound_en);
				if($exis_en==1 AND $filefound_en !=''){
					
				}else{
					$filefound='default.png';
				}
				if ($data['img_en']=='') {
					$picture_en 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['title_en'],0,1).'</span></center>';
				} else {
					$picture_en 		= '
								<div class="kt-user-card-v2">
	                                <div class="" style="margin: 0 auto; max-width: 150px;">
	                                    <center><img src="'.base_url().'images/slides/'.$filefound_en.'" class="img-responsive" style="max-width:150px;" alt="photo"></center>
	                                </div>
	                            </div>';
				}
				if($data['flag_website']==1){
					$flag_web = '<b>Web Fakultas</b>';
				}else if($data['flag_website']==2){
					$flag_web = '<b>Web Prodi S1 & S2</b>';
				}else if($data['flag_website']==3){
					$flag_web = '<b>Web Alumni & Mitra</b>';					
				}
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"website"		=> $flag_web,
					"picture"		=> $picture,
					"picture_en"	=> $picture_en, 
					"link"			=> $data['link'], 
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datadoc(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/document');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(select menu from menu_site where id_menu=a.id_menu) as menu,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Document' AND xa.data = a.id_doc ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Document' AND xa.data = a.id_doc ORDER BY xa.date_time DESC limit 1)as last_update
					from
					document a
					ORDER BY a.id_doc desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_doc'];
				
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"document"		=> $data['title'],
					"menu"			=> $data['menu'],
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datainboxhome(){
		$qUser				= "
							select * from inbox order by 1 desc
							";
		$dataUsr			= $this->query->getDatabyQ($qUser);

		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qUser)->num_rows();

		if ($cek>0) {
			foreach($dataUsr as $data) { 
				$no++;

				$id = $data['id_inbox'];
				
				$inbox 		= '
							<div class="kt-user-card-v2">
								<div class="kt-user-card-v2__pic">
									<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> 
									'.substr($data['name'],0,1).'
									</span>
								</div>
								<div class="kt-user-card-v2__details">
									<a class="kt-user-card-v2__name" href="'.base_url().'panel/detailinbox/'.$data['id_inbox'].'">'.$data['name'].'</a>
									<span class="kt-user-card-v2__email"><i class="fa fa-clock"></i> '.$this->formula->nicetime($data['created']).'</span>
								</div>
							</div>';
				$date 		= $data['created'];
				
				$row = array(
					"inbox"		=> $inbox,
					"dateentry"	=> $date
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataservices(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/services');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(select menu from menu_site where id_menu=a.id_menu) as menu,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Services' AND xa.data = a.id_services ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Services' AND xa.data = a.id_services ORDER BY xa.date_time DESC limit 1)as last_update
					from
					services a
					ORDER BY a.id_services desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_services'];
				
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$filefound = $data['picture'];
				$url = base_url()."images/content/".$filefound;
				$exis = file_exists(FCPATH."images/content/".$filefound);
				if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}
				
				if ($data['picture']=='') {
					$picture 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['title'],0,1).'</span></center>';
				} else {
					$picture 		= '
								<div class="kt-user-card-v2">
	                                <div class="" style="margin: 0 auto; max-width: 150px;">
	                                    <center><img src="'.base_url().'images/content/'.$filefound.'" class="img-responsive" style="max-width:150px;" alt="photo"></center>
	                                </div>
	                            </div>';
				}

				$row = array(
					"icon"			=> $picture,
					"title"			=> $data['title'],
					"menu"			=> $data['menu'],
					"headline"		=> $data['headline'],
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataalbum(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/album');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(select menu from menu_site where id_menu=a.id_menu) as menu,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Album' AND xa.data = a.id_album ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Album' AND xa.data = a.id_album ORDER BY xa.date_time DESC limit 1)as last_update
					from
					album a
					ORDER BY a.id_album desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_album'];
				
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$filefound = $data['icon'];
				$url = base_url()."images/gallery/".$filefound;
				$exis = file_exists(FCPATH."images/gallery/".$filefound);
				if($exis==1 AND $filefound !=''){
					
				}else{
					$filefound='default.png';
				}
				
				if ($data['icon']=='') {
					$picture 		= '<center><span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"> '.substr($data['title'],0,1).'</span></center>';
				} else {
					$picture 		= '
								<div class="kt-user-card-v2">
	                                <div class="" style="margin: 0 auto; max-width: 150px;">
	                                    <center><img src="'.base_url().'images/gallery/'.$filefound.'" class="img-responsive" style="max-width:150px;" alt="photo"></center>
	                                </div>
	                            </div>';
				}

				$row = array(
					"icon"			=> $picture,
					"title"			=> $data['title'],
					"works"			=> $data['menu'],
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataevent(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/event');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Event' AND xa.data = a.id_event ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Manage Event' AND xa.data = a.id_event ORDER BY xa.date_time DESC limit 1)as last_update
					from
					event a
					ORDER BY a.id_event desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_event'];

				$row = array(
					"event"			=> $data['nama'],
					"location"		=> $data['lokasi'],
					"date"			=> $data['tanggal'],
					"expired"		=> $data['expired'],
					"updateby"		=> $data['update_by'],
					"lastupdate"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

}
