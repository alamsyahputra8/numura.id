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

	public function dataapiauth(){
		$menu = 'panel/user';
		$data_aksess = $this->query->getAkses($this->profile,$menu);
		//foreach ($data_aksess as $shift) { $akses = $shift['akses']; }
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qapiauth				= "SELECT * FROM auth";
		$dataapiauth			= $this->query->getDatabyQ($qapiauth);

		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qapiauth)->num_rows();

		if ($cek>0) {
			foreach($dataapiauth as $data) { 
				$no++;
				$id = $data['authid'];				
				$buttonupdate 	= getRoleUpdate($akses,'update',$id);
				$buttondelete 	= getRoleDelete($akses,'delete',$id);				
				$row = array(
					"authid"		=> $id,
					"clientid"		=> $data['clientid'],
					"username"		=> $data['username'],
					"password"		=> $data['password'],
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

	public function datakontak($type){
		$data_aksess = $this->query->getAkses($this->profile,'panel/kontak');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT base.* ,
					(
						select 
							sum(case when type_kontak='Supplier' then hutangblm-debitmemo else piutangblm-kreditmemo end) saldo
						from (
							SELECT
									person,
									(select GROUP_CONCAT(nama_type) from kontak_type where id_kt in (select type from kontak_detail_type where id_kontak=final.person)) as type_kontak,
									case when transaction_status in (1,4,5) and type in (1) then remaining else 0 end as piutangblm,
									case when transaction_status in (1,4,5) and type in (9) then remaining else 0 end as hutangblm,
				                    case when type in (13) and transaction_status in (1,4,5) then remaining else 0 end as kreditmemo,
				                    case when type in (30) and transaction_status in (1,4,5) then remaining else 0 end as debitmemo
							FROM (
								SELECT basedata.*, 
									(select name from transactions_type where id=basedata.type) transaction_type,
								    (select link from transactions_type where id=basedata.type) transaction_link,
								    (select name from transactions_status where id=basedata.transaction_status) transaction_status_name,
								    (select color from transactions_status where id=basedata.transaction_status) transaction_status_color,
								    (original_amount-remaining) sisa
								FROM (
								    SELECT id,type,transaction_no,transaction_date,due_date,transaction_status,remaining,original_amount, person FROM `purchase`
								    UNION
								    SELECT id,type,transaction_no,transaction_date,due_date,transaction_status,remaining,original_amount, person FROM `sales`
								    UNION
								    SELECT id,type,transaction_no,transaction_date,'-' due_date,transaction_status,remaining,original_amount, person FROM `bank_transactions`
								    UNION
								    SELECT id,transaction_type as type,transaction_no,transaction_date,due_date,transaction_status,remaining,original_amount, person FROM `expense`
								    UNION
								    SELECT id,transaction_type as type,transaction_no,transaction_date,due_date,transaction_status,0 remaining,original_amount, person FROM `receive_payment`
								    UNION
								    SELECT id,type,transaction_no,transaction_date,due_date,transaction_status,0 remaining,original_amount, person FROM `purchase_payment`
								) as basedata
								order by transaction_date desc
							) as final 
						) as cek
						where person in (base.id_kontak)
					) saldo
					from(
						select
							a.*,
							(select GROUP_CONCAT(nama_type) from kontak_type where id_kt in (select type from kontak_detail_type where id_kontak=a.id_kontak)) as type_kontak,
							(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Kontak' AND xa.data = a.id_kontak ORDER BY xa.date_time DESC limit 1)as update_by,
							(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Kontak' AND xa.data = a.id_kontak ORDER BY xa.date_time DESC limit 1)as last_update
						from
						kontak a
					) as base
					where 1=1 and type_kontak like '%$type%'
					ORDER BY id_kontak desc
				";
				//echo $qRole;
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_kontak'];
				
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);

				if($data['saldo']<0) {
					$saldo 		= '('.$this->formula->rupiah(abs($data['saldo'])).')';
				} else {
					$saldo 		= $this->formula->rupiah($data['saldo']);
				}

				$row = array(
					"nama"		=> '<a title="'.$data['display_name'].'" href="'.base_url().'panel/detailkontak/'.$id.'">'.$data['display_name'].'</a>',
					"perusahaan"=> $data['company_name'],
					"alamat"	=> $data['billing_address'],
					"email"		=> $data['email'],
					"phone"		=> $data['mobile'],
					"group"		=> '',
					"saldo"		=> $saldo,
					"actions"	=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datakontakgroup(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/kontak');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					select * from(
						select
							a.*,
							(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Kontak Group' AND xa.data = a.id_group ORDER BY xa.date_time DESC limit 1)as update_by,
							(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Kontak Group' AND xa.data = a.id_group ORDER BY xa.date_time DESC limit 1)as last_update
						from
						kontak_group a
					) as base
					where 1=1
					ORDER BY last_update desc
				";
				//echo $qRole;
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_group'];
				
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"nama"		=> $data['nama'],
					"updateby"	=> $data['update_by'],
					"lastupdate"=> $data['last_update'],
					"actions"	=> $id
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
						(case when style='basic' then 'Article' when style='blog' then 'Berita' when style='document' then 'Document' when style='gallery' then 'Gallery' else 'Article' end)as stylename,
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
				
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"background"	=> $background,
					"menu"			=> $data['menu'],
					"description"	=> $data['description'],
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
				
				//$buttonupdate = getRoleUpdate($akses,'update',$id);
				//$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"picture"		=> $picture,
					"title"			=> $data['title'],
					"sub"			=> $data['sub'],
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

	public function dataakundetail($nomorkontak){
		// $data_aksess = $this->query->getAkses($this->profile,'panel/daftar-akun');
		// $shift = array_shift($data_aksess);
		// $akses = $shift['akses'];

		// $qRole 	= "
		// 			select * from(
		// 				select
		// 					a.*,
		// 					(select GROUP_CONCAT(nama_type) from kontak_type where id_kt in (select type from kontak_detail_type where id_kontak=a.id_kontak)) as type_kontak,
		// 					(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
		// 					WHERE xa.menu='Kontak' AND xa.data = a.id_kontak ORDER BY xa.date_time DESC limit 1)as update_by,
		// 					(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
		// 					WHERE xa.menu='Kontak' AND xa.data = a.id_kontak ORDER BY xa.date_time DESC limit 1)as last_update
		// 				from
		// 				akun_history a
		// 			) as base
		// 			where 1=1 and type_kontak like '%$type%'
		// 			ORDER BY id_kontak desc
		// 		";
		// 		//echo $qRole;
		// $datarole			= $this->query->getDatabyQ($qRole);
		
		// $no=0;
		// header('Content-type: application/json; charset=UTF-8');

		// $cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		// if ($cek>0) {

		// 	foreach($datarole as $data) {
		// 		$no++;
		// 		$id = $data['id_kontak'];
				
		// 		$buttonupdate = getRoleUpdate($akses,'update',$id);
		// 		$buttondelete = getRoleDelete($akses,'delete',$id);

		// 		$row = array(
		// 			"nama"		=> '<a href="'.base_url().'panel/detailkontak/'.$id.'">'.$data['display_name'].'</a>',
		// 			"perusahaan"=> $data['company_name'],
		// 			"alamat"	=> $data['billing_address'],
		// 			"email"		=> $data['email'],
		// 			"phone"		=> $data['mobile'],
		// 			"group"		=> '',
		// 			"saldo"		=> '',
		// 			"actions"	=> $id
		// 			);
		// 		$json[] = $row;
		// 	}
		// 	echo json_encode($json);
		// } else {
		// 	$json ='';
		// 	echo json_encode($json);
		// }
	}

	public function datagudang(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/album');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select
						a.*,
						(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Gudang' AND xa.data = a.id_gudang ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
						WHERE xa.menu='Gudang' AND xa.data = a.id_gudang ORDER BY xa.date_time DESC limit 1)as last_update
					from
					gudang a
					ORDER BY a.id_gudang desc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_gudang'];

				$row = array(
					"kode"			=> $data['kode'],
					"nama"			=> '<a href="'.base_url().'panel/warehouses/'.$id.'">'.$data['nama_gudang'].'</a>',
					"alamat"		=> $data['alamat'],
					"keterangan"	=> $data['keterangan'],
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

	public function productbygudang($id){
		$data_aksess = $this->query->getAkses($this->profile,'panel/produk');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$q 		= "
					select id_product,product_code, product_name, sum(qty) qty, max(tanggal) tanggal from (
					    SELECT a.*,  
					        (select product_code from produk where id=id_product) product_code,
					        (select name from produk where id=id_product) product_name
					    FROM `product_detail_stock` a
					    where id_gudang='$id'
					) as base
					group by id_product
					order by product_name asc
				";
		$getdata= $this->query->getDatabyQ($q);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($q)->num_rows();

		if ($cek>0) {
			foreach($getdata as $data) {
				$no++;

				$id = $data['id_product'];

				$row = array(
					"kode"			=> $data['product_code'],
					"nama"			=> '<a href="'.base_url().'panel/produk-detail/'.$id.'">'.$data['product_name'].'</a>',
					"kuantitas"		=> $data['qty']
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataprodukkategori(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/produk');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					select * from(
						select
							a.*,
							(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Kategori Produk' AND xa.data = a.id_category ORDER BY xa.date_time DESC limit 1)as update_by,
							(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Kategori Produk' AND xa.data = a.id_category ORDER BY xa.date_time DESC limit 1)as last_update
						from
						produk_category a
					) as base
					where 1=1
					ORDER BY last_update desc
				";
				//echo $qRole;
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_category'];

				$row = array(
					"nama"		=> $data['category'],
					"updateby"	=> $data['update_by'],
					"lastupdate"=> $data['last_update'],
					"actions"	=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataprodukunit(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/produk');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					select * from(
						select
							a.*,
							(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Unit Produk' AND xa.data = a.id_unit ORDER BY xa.date_time DESC limit 1)as update_by,
							(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Unit Produk' AND xa.data = a.id_unit ORDER BY xa.date_time DESC limit 1)as last_update
						from
						produk_unit a
					) as base
					where 1=1
					ORDER BY last_update desc
				";
				//echo $qRole;
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_unit'];

				$row = array(
					"nama"		=> $data['unit'],
					"updateby"	=> $data['update_by'],
					"lastupdate"=> $data['last_update'],
					"actions"	=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataproduk(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/produk');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					select base.*, 
						(select unit from produk_unit where id_unit=base.unit) unitname,
						(select GROUP_CONCAT(b.category) from produk_detail_category a left join produk_category b on a.id_category=b.id_category where id_product=base.id) namakategori,
						(select sum(qty) qty from product_detail_stock where id_product=base.id) qty 
					from(
						select
							a.*,
							(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Manage Product' AND xa.data = a.id ORDER BY xa.date_time DESC limit 1)as update_by,
							(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Manage Product' AND xa.data = a.id ORDER BY xa.date_time DESC limit 1)as last_update
						from
						produk a
					) as base
					where 1=1
					ORDER BY last_update desc
				";
				//echo $qRole;
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];

				$row = array(
					'kode'				=> $data['product_code'],
					'nama'				=> '<a title ="'.$data['name'].'" href="'.base_url().'panel/product-detail/'.$id.'">'.$data['name'].'</a>',
					'qty'				=> $data['qty'],
					'minimum'			=> $data['minimum_stock'],
					'unit'				=> $data['unitname'],
					'hargabeli_terakhir'=> $this->formula->rupiah($data['average_price']),
					'hargabeli'			=> $this->formula->rupiah($data['harga_beli']),
					'harga_jual_sales'	=> $this->formula->rupiah($data['harga_sales']),
					'harga_jual_toko'	=> $this->formula->rupiah($data['harga_toko']),
					'harga_jual_tukang'	=> $this->formula->rupiah($data['harga_tukangbor']),
					'harga_jual_kasir'	=> $this->formula->rupiah($data['harga_kasir']),
					'kategori'			=> $data['namakategori'],
					'actions'			=> $id
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datastok(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/produk');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					select * from(
						select
							a.*,
					    	case when warehouse='-1' then 'Unassigned' else (select nama_gudang from gudang where id_gudang=a.warehouse) end warehouse_name,
					    	(select name from adjustment_type where id_type=a.type_id) adjustment_type,
					    	(select name from account where id=a.account) account_name,
							(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Penyesuaian Stok' AND xa.data = a.id_adjustment ORDER BY xa.date_time DESC limit 1)as update_by,
							(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Penyesuaian Stok' AND xa.data = a.id_adjustment ORDER BY xa.date_time DESC limit 1)as last_update
						from
						adjustment_stock a
					) as base
					where 1=1
					ORDER BY last_update desc
				";
				//echo $qRole;
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_adjustment'];
				
				if($data['tags']==0) {
					$tags 	= '';
				} else {
					$tags 	= $data['tags'];
				}
				
				$gdate 	= date_create($data['date']);
				$date 	= date_format($gdate,"Y/m/d");

				$row = array(
					"tanggal"		=> $date,
					"no_transaksi"	=> '<a href="'.base_url().'panel/stock_adjustment/'.$id.'">'.$data['entry'].'</a>',
					"tipe"			=> $data['adjustment_type'],
					"akun"			=> $data['account_name'],
					"gudang"		=> $data['warehouse_name'],
					"tag"			=> $tags,
					"actions"		=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datastokadjustmentdetail($id){
		$data_aksess = $this->query->getAkses($this->profile,'panel/produk');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					select * from(
						select
							a.*, b.name as product_name, b.product_code
						from
						adjustment_stock_detail a
						left join produk b on a.id_product=b.id
					) as base
					where 1=1 and id_adjustment='$id'
					order by id_detail
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_product'];
				
				$row = array(
					"produk"			=> '<a href="'.base_url().'panel/product-detail/'.$id.'">'.$data['product_name'].'</a>',
					"kode_produk"		=> $data['product_code'],
					"jumlah_tercatat"	=> $data['recorded_quantity'],
					"jumlah_sebenarnya"	=> $data['actual_quantity'],
					"perbedaan"			=> $data['difference'],
					"average_price"		=> $this->formula->rupiah($data['average_price']),
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datastokbygudang($id){
		$data_aksess = $this->query->getAkses($this->profile,'panel/produk');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT a.* ,
					(select count(DISTINCT(id_product)) qty from gudang_transfer_detail where id_transfer=a.id) jml_product
					FROM `gudang_transfer` a where to_warehouse='$id'
					order by transaction_date desc
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];

				$gdate 	= date_create($data['transaction_date']);
				$date 	= date_format($gdate,"Y/m/d");
				
				$row = array(
					"tanggal"		=> $date,
					"tipe"			=> '<a href="'.base_url().'panel/warehouse_transfers/'.$id.'">Transfer Gudang #'.$data['transaction_no'].'</a>',
					"jumlahproduct"	=> $data['jml_product']
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datatransfer(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/produk');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					select a.*,
						case when from_warehouse='-1' then 'Unassigned' else (select nama_gudang from gudang where id_gudang=a.from_warehouse) end as gudang_dari,
						case when to_warehouse='-1' then 'Unassigned' else (select nama_gudang from gudang where id_gudang=a.to_warehouse) end as gudang_ke
					from gudang_transfer a
					where 1=1
					ORDER BY transaction_date desc
				";
				//echo $qRole;
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];
				
				$gdate 	= date_create($data['transaction_date']);
				$date 	= date_format($gdate,"Y/m/d");

				$row = array(
					"tanggal"		=> $date,
					"notransaksi"	=> '<a href="'.base_url().'panel/warehouse_transfers/'.$id.'">'.$data['transaction_no'].'</a>',
					"dari"			=> $data['gudang_dari'],
					"ke"			=> $data['gudang_ke'],
					"memo"			=> $data['memo'],
					"actions"		=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datatransferdetail($id){
		$data_aksess = $this->query->getAkses($this->profile,'panel/produk');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT a.*, (select name from produk where id=a.id_product) product_name, (select product_code from produk where id=a.id_product) product_code FROM `gudang_transfer_detail` a
					where id_transfer='$id' order by id_detail
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_product'];
				
				$row = array(
					"produk"			=> '<a href="'.base_url().'panel/product-detail/'.$id.'">'.$data['product_name'].'</a>',
					"kode_produk"		=> $data['product_code'],
					"qty"				=> $data['qty'],
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataasettertunda(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/pengaturan-aset');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT a.*,
					(select transaction_no from purchase where id=purchase_id) transaction_no
					FROM assets a where status='pending' order by acquisition_date desc
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];
				
				$gdate 	= date_create($data['acquisition_date']);
				$date 	= date_format($gdate,"Y/m/d");

				$row = array(
					"tanggal"		=> $date,
					"barang"		=> $data['name'],
					"faktur"		=> '<a href="'.base_url().'panel/purchases/'.$data['purchase_id'].'">Purchase Invoice #'.$data['transaction_no'].'</a>',
					"biaya"			=> $this->formula->rupiah($data['acquisition_cost']),
					"actions"		=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataasetaktif(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/pengaturan-aset');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT a.*, (select name from account where id=a.asset_account) account_name 
					FROM assets a where status='active' order by acquisition_date desc
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];
				
				$gdate 	= date_create($data['acquisition_date']);
				$date 	= date_format($gdate,"Y/m/d");

				$row = array(
					"tanggal"		=> $date,
					"detail"		=> '<a href="'.base_url().'panel/assets_managements/'.$id.'">('.$data['asset_number'].') '.$data['name'].'</a>',
					"akun"			=> '<a href="'.base_url().'panel/akun/'.$data['asset_account'].'">'.$data['account_name'].'</a>',
					"biaya"			=> $this->formula->rupiah($data['acquisition_cost']),
					"nilai"			=> $this->formula->rupiah($data['book_value']),
					"actions"		=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataasetdijual(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/pengaturan-aset');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT a.*,
					(select name from account where id=a.asset_account) account_name,
					(
					    select transaction_date from jurnal_entry where id in (select transaction_id from jurnal_entry_detail where SUBSTRING(description,2,5)=a.asset_number)
					    order by id desc limit 1
					) transaction_date,
					(
					    select id from jurnal_entry where id in (select transaction_id from jurnal_entry_detail where SUBSTRING(description,2,5)=a.asset_number)
					    order by id desc limit 1
					) transaction_id,
					(
					    select transaction_no from jurnal_entry where id in (select transaction_id from jurnal_entry_detail where SUBSTRING(description,2,5)=a.asset_number)
					    order by id desc limit 1
					) transaction_no,
					(
					    select total_debit from jurnal_entry where id in (select transaction_id from jurnal_entry_detail where SUBSTRING(description,2,5)=a.asset_number)
					    order by id desc limit 1
					) harga_jual
					FROM assets a where status='disposed' and
					asset_number in (
						select SUBSTRING(description,2,5) as cek
					    from jurnal_entry_detail
					)
					order by acquisition_date desc
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];
				
				$gdate 	= date_create($data['transaction_date']);
				$date 	= date_format($gdate,"Y/m/d");

				$calc 	= $data['book_value']-$data['harga_jual'];
				if ($calc<0) {
					$untungrugi 	= '('.$this->formula->rupiah(abs($calc)).')';
				} else {
					$untungrugi 	= $this->formula->rupiah($calc);
				}

				$row = array(
					"tanggal"		=> $date,
					"detail"		=> '<a href="'.base_url().'panel/assets_managements/'.$id.'">('.$data['asset_number'].') '.$data['name'].'</a>',
					"notransaksi"	=> '<a href="'.base_url().'panel/journal_entries/'.$data['transaction_id'].'">Journal Entry #'.$data['transaction_no'].'</a>',
					"harga"			=> $this->formula->rupiah($data['harga_jual']),
					"untung"		=> $untungrugi,
					"actions"		=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataasetpenyusutan(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/pengaturan-aset');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$a_date = date('Y-m-d');
		$now 	= date("Y-m-t", strtotime($a_date));

		$qRole 	= "
					SELECT * FROM `assets` where depreceable='TRUE' and status='active' and last_applied_depreciation_date<'$now'
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];
				
				$gdate 	= date_create($data['last_applied_depreciation_date']);
				$date 	= date_format($gdate,"Y/m/d");

				$date1			= date_create($data['last_applied_depreciation_date']);
				$date2			= date_create($now);
				$diff 			= date_diff($date1,$date2);
				$difference 	= $diff->format("%m");

				$penyusutan 	= ($data['acquisition_cost']/($data['useful_life']*12))*$difference;

				$row = array(
					"detail"		=> '<a href="'.base_url().'panel/assets_managements/'.$id.'">('.$data['asset_number'].') '.$data['name'].'</a>',
					"periode"		=> $date.' - '.$now,
					"nilai"			=> $data['rate_value'].'%',
					"metode"		=> $data['depreciation_method'],
					"penyusutan"	=> $this->formula->rupiah($penyusutan),
					"actions"		=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datatransaksi($type,$id){
		$data_aksess = $this->query->getAkses($this->profile,'panel/$type');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		if ($type=='kontak') {
			$condwhere 	= "and person in ($id)";
		} else if ($type=='tags') {
			$condwhere 	= "and tags in ($id)";
		} else if ($type=='produk') {
			$condwhere 	= "
						and id in (
							select id from (
								select transaction_id id, product
							    from purchase_detail
							    UNION
							    select transaction_id id, product
							    from sales_detail
							    UNION
								select id_adjustment id, id_product
							    from adjustment_stock_detail
							    UNION
							    select id_transfer id, id_product
							    from gudang_transfer_detail
							) as cek
						    where product='$id'
						)
						";
		} else if ($type=='warehouse') {
			$condwhere 	= "and warehouse in ($id)";
		}

		$qRole 	= "
					SELECT basedata.*, 
						(select name from transactions_type where id=basedata.type) transaction_type,
					    (select link from transactions_type where id=basedata.type) transaction_link,
					    (select name from transactions_status where id=basedata.transaction_status) transaction_status_name,
					    (select color from transactions_status where id=basedata.transaction_status) transaction_status_color,
					    (select display_name from kontak where id_kontak=person) user
					FROM (
					    SELECT id,type,memo,transaction_no,transaction_date,due_date,transaction_status,original_amount, person, '' tags, warehouse,
					    (select quantity from purchase_detail where transaction_id=purchase.id and product=$id) qtyproduct
					    FROM `purchase`
					    UNION
					    SELECT id,type,memo,transaction_no,transaction_date,due_date,transaction_status,original_amount, person, tags, warehouse,
					    (select quantity from sales_detail where transaction_id=sales.id and product=$id) qtyproduct
					    FROM `sales`
					    UNION
					    SELECT id,type,memo,transaction_no,transaction_date,'-' due_date,transaction_status,original_amount, person, tags, 0 warehouse,
					    0 qtyproduct
					    FROM `bank_transactions`
					    UNION
					    SELECT id,transaction_type as type,memo,transaction_no,transaction_date,due_date,transaction_status,original_amount, person, tags, 0 warehouse,
					    0 qtyproduct
					    FROM `expense`
					    UNION
					    SELECT id,transaction_type as type,memo,transaction_no,transaction_date,due_date,transaction_status,original_amount, person, tags, 0 warehouse,
					    0 qtyproduct 
					    FROM `receive_payment`
					    UNION
					    SELECT id,type,memo,transaction_no,transaction_date,due_date,transaction_status,original_amount, person, '' tags, 0 warehouse,
					    0 qtyproduct 
					    FROM `purchase_payment`
					    UNION
					    SELECT id_adjustment id,'26' as type,memo,transaction_no,date transaction_date,'' due_date,2 transaction_status,0 original_amount, warehouse person, tags, warehouse,
					    (select difference from adjustment_stock_detail where id_adjustment=adjustment_stock.id_adjustment and id_product=$id) qtyproduct 
					    FROM `adjustment_stock`
					    UNION
					    SELECT id,'25' as type,memo,transaction_no,transaction_date,'' due_date,2 transaction_status,0 original_amount, from_warehouse person, '' tags, from_warehouse warehouse,
					    (select qty from gudang_transfer_detail where id_transfer=gudang_transfer.id and id_product=$id) qtyproduct  
					    FROM `gudang_transfer`
					) as basedata
					where 1=1 $condwhere
					order by transaction_date desc
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];
				
				$gdate 	= date_create($data['transaction_date']);
				@$date 	= date_format($gdate,"Y/m/d");

				$gdate2 = date_create($data['due_date']);
				@$tdate2 = date_format($gdate2,"Y/m/d");
				$date2 	= str_replace('1970/01/01', '-', $tdate2);

				$status = '<span style="color: '.$data['transaction_status_color'].';">'.$data['transaction_status_name'].'</span>';

				if ($data['transaction_link']=='invoices') {
					$qtyproduct 	= '-'.$data['qtyproduct'];
				} else {
					$qtyproduct 	= $data['qtyproduct'];
				}

				$row = array(
					"tanggal"			=> $date,
					"nomor"				=> '<a title="'.$id.'" href="'.base_url().'panel/'.$data['transaction_link'].'/'.$id.'">'.$data['transaction_type'].' #'.$data['transaction_no'].'</a>',
					"tipe"				=> '<a title="'.$id.'" href="'.base_url().'panel/'.$data['transaction_link'].'/'.$id.'">'.$data['transaction_type'].' #'.$data['transaction_no'].'</a>',
					"tanggaljatuhtempo" => $date2,
					"status"			=> $status,
					"jumlah"			=> $this->formula->rupiah($data['original_amount']),
					"deskripsi" 		=> $data['memo'],
					"user" 				=> '<a href="'.base_url().'panel/detailkontak/'.$data['person'].'">'.$data['user'].'</a>',
					"jumlahproduct"		=> $qtyproduct,
					"actions"			=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datatransaksiall(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/daftar-transaksi');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT basedata.*, 
						(select name from transactions_type where id=basedata.type) transaction_type,
					    (select link from transactions_type where id=basedata.type) transaction_link,
					    (select name from transactions_status where id=basedata.transaction_status) transaction_status_name,
					    (select color from transactions_status where id=basedata.transaction_status) transaction_status_color,
					    (select display_name from kontak where id_kontak=person) user,
					    (select tags from tags where id_tags=basedata.tags) tags_name
					FROM (
					    SELECT id,type,memo,transaction_no,transaction_date,due_date,transaction_status,remaining,original_amount, person, '' tags, warehouse
					    FROM `purchase`
					    UNION
					    SELECT id,type,memo,transaction_no,transaction_date,due_date,transaction_status,remaining,original_amount, person, tags, warehouse
					    FROM `sales`
					    UNION
					    SELECT id,type,memo,transaction_no,transaction_date,'-' due_date,transaction_status,remaining,original_amount, person, tags, 0 warehouse
					    FROM `bank_transactions`
					    UNION
					    SELECT id,transaction_type as type,memo,transaction_no,transaction_date,due_date,transaction_status,remaining,original_amount, person, tags, 0 warehouse
					    FROM `expense`
					    UNION
					    SELECT id,transaction_type as type,memo,transaction_no,transaction_date,due_date,transaction_status,0 remaining,original_amount, person, tags, 0 warehouse
					    FROM `receive_payment`
					    UNION
					    SELECT id,type,memo,transaction_no,transaction_date,due_date,transaction_status,0 remaining,original_amount, person, '' tags, 0 warehouse
					    FROM `purchase_payment`
					    UNION
					    SELECT id_adjustment id,'26' as type,memo,transaction_no,date transaction_date,'' due_date,2 transaction_status,0 remaining,0 original_amount, warehouse person, tags, warehouse
					    FROM `adjustment_stock`
					    UNION
					    SELECT id,'25' as type,memo,transaction_no,transaction_date,'' due_date,2 transaction_status,0 remaining,0 original_amount, from_warehouse person, '' tags, from_warehouse warehouse
					    FROM `gudang_transfer`
					) as basedata
					where 1=1 
					order by transaction_date desc
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];
				
				$gdate 	= date_create($data['transaction_date']);
				@$date 	= date_format($gdate,"Y/m/d");

				$gdate2 = date_create($data['due_date']);
				@$tdate2 = date_format($gdate2,"Y/m/d");
				$date2 	= str_replace('1970/01/01', '-', $tdate2);

				$status = '<span style="color: '.$data['transaction_status_color'].';">'.$data['transaction_status_name'].'</span>';

				$row = array(
					"tanggal"			=> $date,
					"nomor"				=> '<a title="'.$id.'" href="'.base_url().'panel/'.$data['transaction_link'].'/'.$id.'">'.$data['transaction_type'].' #'.$data['transaction_no'].'</a>',
					"tipe"				=> '<a title="'.$id.'" href="'.base_url().'panel/'.$data['transaction_link'].'/'.$id.'">'.$data['transaction_type'].' #'.$data['transaction_no'].'</a>',
					"duedate" 			=> $date2,
					"status"			=> $status,
					"sisa"				=> $this->formula->rupiah($data['remaining']),
					"total"				=> $this->formula->rupiah($data['original_amount']),
					"memo" 				=> $data['memo'],
					"user" 				=> '<a href="'.base_url().'panel/detailkontak/'.$data['person'].'">'.$data['user'].'</a>',
					"tags" 				=> '<a href="'.base_url().'panel/tags/'.$data['tags'].'">'.$data['tags_name'].'</a>',
					"actions"			=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function transaksisales($type,$id){
		$data_aksess = $this->query->getAkses($this->profile,'panel/$type');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT basedata.*, 
						(select name from transactions_type where id=basedata.type) transaction_type,
					    (select link from transactions_type where id=basedata.type) transaction_link,
					    (select name from transactions_status where id=basedata.transaction_status) transaction_status_name,
					    (select color from transactions_status where id=basedata.transaction_status) transaction_status_color,
					    (select tags from tags where id_tags=basedata.tags) tag_name,
					    (select display_name from kontak where id_kontak=person) user
					FROM (
					    SELECT id,type,memo,transaction_no,transaction_date,due_date,transaction_status, remaining, original_amount, person, tags
					    FROM `sales`
					) as basedata
					where 1=1 and type in ($id)
					order by transaction_date desc
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];
				
				$gdate 	= date_create($data['transaction_date']);
				@$date 	= date_format($gdate,"Y/m/d");

				$gdate2 = date_create($data['due_date']);
				@$tdate2 = date_format($gdate2,"Y/m/d");
				$date2 	= str_replace('1970/01/01', '-', $tdate2);

				$status = '<span style="color: '.$data['transaction_status_color'].';">'.$data['transaction_status_name'].'</span>';

				$row = array(
					"tanggal"			=> $date,
					"nomor"				=> '<a title="'.$data['transaction_no'].'" href="'.base_url().'panel/'.$data['transaction_link'].'/'.$id.'">'.$data['transaction_type'].' #'.$data['transaction_no'].'</a>',
					"tipe"				=> '<a title="'.$data['transaction_no'].'" href="'.base_url().'panel/'.$data['transaction_link'].'/'.$id.'">'.$data['transaction_type'].' #'.$data['transaction_no'].'</a>',
					"pelanggan" 		=> '<a title="'.$id.'" href="'.base_url().'panel/detailkontak/'.$data['person'].'">'.$data['user'].'</a>',
					"jatuhtempo" 		=> $date2,
					"status"			=> $status,
					"sisa" 				=> $this->formula->rupiah($data['remaining']),
					"total"				=> $this->formula->rupiah($data['original_amount']),
					"deskripsi" 		=> $data['memo'],
					"user" 				=> '<a href="'.base_url().'panel/detailkontak/'.$data['person'].'">'.$data['user'].'</a>',
					"tags" 				=> '<a title="'.$id.'" href="'.base_url().'panel/tags/'.$data['tags'].'">'.$data['tag_name'].'</a>',
					"actions"			=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function transaksipurchase($type,$id){
		$data_aksess = $this->query->getAkses($this->profile,'panel/$type');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT basedata.*, 
						(select name from transactions_type where id=basedata.type) transaction_type,
					    (select link from transactions_type where id=basedata.type) transaction_link,
					    (select name from transactions_status where id=basedata.transaction_status) transaction_status_name,
					    (select color from transactions_status where id=basedata.transaction_status) transaction_status_color,
					    (select tags from tags where id_tags=basedata.tags) tag_name,
					    (select display_name from kontak where id_kontak=person) user
					FROM (
					    SELECT id,type,memo,transaction_no,transaction_date,due_date,transaction_status, remaining, original_amount, person, tags
					    FROM `purchase`
					) as basedata
					where 1=1 and type in ($id)
					order by transaction_date desc
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];
				
				$gdate 	= date_create($data['transaction_date']);
				@$date 	= date_format($gdate,"Y/m/d");

				$gdate2 = date_create($data['due_date']);
				@$tdate2 = date_format($gdate2,"Y/m/d");
				$date2 	= str_replace('1970/01/01', '-', $tdate2);

				$status = '<span style="color: '.$data['transaction_status_color'].';">'.$data['transaction_status_name'].'</span>';

				$row = array(
					"tanggal"			=> $date,
					"nomor"				=> '<a title="'.$data['transaction_no'].'" href="'.base_url().'panel/'.$data['transaction_link'].'/'.$id.'">'.$data['transaction_type'].' #'.$data['transaction_no'].'</a>',
					"tipe"				=> '<a title="'.$data['transaction_no'].'" href="'.base_url().'panel/'.$data['transaction_link'].'/'.$id.'">'.$data['transaction_type'].' #'.$data['transaction_no'].'</a>',
					"pelanggan" 		=> '<a title="'.$id.'" href="'.base_url().'panel/detailkontak/'.$data['person'].'">'.$data['user'].'</a>',
					"jatuhtempo" 		=> $date2,
					"status"			=> $status,
					"sisa" 				=> $this->formula->rupiah($data['remaining']),
					"total"				=> $this->formula->rupiah($data['original_amount']),
					"deskripsi" 		=> $data['memo'],
					"user" 				=> '<a href="'.base_url().'panel/detailkontak/'.$data['person'].'">'.$data['user'].'</a>',
					"tags" 				=> '<a title="'.$id.'" href="'.base_url().'panel/tags/'.$data['tags'].'">'.$data['tag_name'].'</a>',
					"actions"			=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function transaksiexpense(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/biaya');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT basedata.*, 
						(select name from transactions_type where id=basedata.transaction_type) transaction_type_name,
					    (select link from transactions_type where id=basedata.transaction_type) transaction_link,
					    (select name from transactions_status where id=basedata.transaction_status) transaction_status_name,
					    (select color from transactions_status where id=basedata.transaction_status) transaction_status_color,
					    (select tags from tags where id_tags=basedata.tags) tag_name,
					    (select name from account where id=basedata.pay_from) kategori,
					    CASE 
					    	when jmlakun >1 then '' else (select distinct(account) from expense_detail where transaction_id=basedata.id)
					    END kategori_expense_id,
					    CASE 
					    	when jmlakun >1 then '-Terbagi-' else 
					    	(select distinct(select name from account where id=bx.account) from expense_detail bx where transaction_id=basedata.id)
					    END kategori_expense_name,
					    (select display_name from kontak where id_kontak=person) user
					FROM (
					    SELECT a.*, (SELECT count(DISTINCT(account)) from expense_detail where transaction_id=a.id) jmlakun
					    FROM `expense` a
					) as basedata
					where 1=1
					order by transaction_date desc
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];
				
				$gdate 	= date_create($data['transaction_date']);
				@$date 	= date_format($gdate,"Y/m/d");

				$gdate2 = date_create($data['due_date']);
				@$tdate2 = date_format($gdate2,"Y/m/d");
				$date2 	= str_replace('1970/01/01', '-', $tdate2);

				$status = '<span style="color: '.$data['transaction_status_color'].';">'.$data['transaction_status_name'].'</span>';

				if ($data['kategori_expense_id']=='') {
					$linkkategori 	= '<a>'.$data['kategori_expense_name'].'</a>';
				} else {
					$linkkategori 	= '<a href="'.base_url().'panel/akun/'.$data['kategori_expense_id'].'">'.$data['kategori_expense_name'].'</a>';
				}

				if ($data['transaction_status']==2) {
					$remaining 	= $this->formula->rupiah(0);
				} else {
					$remaining 	= $this->formula->rupiah($data['remaining']);
				}

				$row = array(
					"tanggal"			=> $date,
					"nomor"				=> '<a href="'.base_url().'panel/expenses/'.$id.'">Expense #'.$data['transaction_no'].'</a>',
					"kategori"			=> $linkkategori,
					"penerima" 			=> '<a title="'.$id.'" href="'.base_url().'panel/detailkontak/'.$data['person'].'">'.$data['user'].'</a>',
					"status"			=> $status,
					"sisatagihan" 		=> $remaining,
					"total"				=> $this->formula->rupiah($data['original_amount']),
					"deskripsi" 		=> $data['memo'],
					"tags" 				=> '<a title="'.$id.'" href="'.base_url().'panel/tags/'.$data['tags'].'">'.$data['tag_name'].'</a>',
					"actions"			=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function taglist(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/taglist');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT a.*,
					(select count(*) from sales where tags=a.id_tags) penjualan,
					(select count(*) from purchase where tags=a.id_tags) pembelian,
					(select count(*) from expense where tags=a.id_tags) pengeluaran,
					(select count(*) from bank_transactions where tags=a.id_tags) bank,
					(select count(*) from adjustment_stock where tags=a.id_tags) stok,
					(select count(*) from gudang_transfer where tags=a.id_tags) gudang,
					(select count(*) from receive_payment where tags=a.id_tags) receive,
					(select count(*) from purchase_payment where tags=a.id_tags) pay
					FROM `tags` a
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_tags'];
				
				$lainnya 	= $data['bank']+$data['stok']+$data['gudang']+$data['receive']+$data['pay'];

				$row = array(
					"nama"				=> '<a title="'.$data['tags'].'" href="'.base_url().'panel/tags/'.$id.'">'.$data['tags'].'</a>',
					"penjualan"			=> $data['penjualan'],
					"pembelian"			=> $data['pembelian'],
					"pengeluaran" 		=> $data['pengeluaran'],
					"lainnya"			=> $lainnya,
					"actions"			=> $id,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function datapaymentmethod(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/cara-pembayaran');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT *
					FROM `payment_method`
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id'];

				if($data['is_lock']==1){
					$id_fromakses = '';
				} else {
					$id_fromakses = $id;
				}
				
				$row = array(
					"nama"				=> $data['name'],
					"actions"			=> $id_fromakses,
					);
				$json[] = $row;
			}
			echo json_encode($json);
		} else {
			$json ='';
			echo json_encode($json);
		}
	}

	public function dataterm(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/syarat-pembayaran');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					SELECT *
					FROM `term_resource`
				";
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_term'];

				if($data['is_default']=='TRUE'){
					$id_fromakses = '';
				} else {
					$id_fromakses = $id;
				}
				
				$row = array(
					"nama"				=> $data['name'],
					"waktu"				=> $data['longetivity'],
					"actions"			=> $id_fromakses,
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
