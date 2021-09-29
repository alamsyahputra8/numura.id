<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Viewpanel extends CI_Controller {

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
	private $akses;
	private $userid;
	// private $divisiUAM;
	// private $segmenUAM;
	// private $tregUAM;
	// private $witelUAM;
	// private $amUAM;
	
	public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Bangkok");
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		$this->load->model('auth'); 
		$this->load->model('query');
		$this->load->model('formula');
		$this->load->model('datatable');
		// if(checkingsessionpwt()){
			// $session = checkingsessionpwt();
		$session	 	= $this->session->userdata('sesspwt'); 
		$userid 	 	= $session['userid'];
		$profile 	 	= $session['id_role'];
		$menu 		 	= uri_string();
		$data_akses  	= $this->query->getAkses($profile,$menu);
		$shift 		 	= array_shift($data_akses);
		$this->akses 	= $shift['akses'];
		$this->userid 	= $userid;

		$now 			= date('Y-m-d');
		$yesterday 		= date("Y-m-d",strtotime("-1 day"));
	
		// $getAksesUAM		= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
		// $dataAksesUAM		= array_shift($getAksesUAM);
		// $this->divisiUAM 	= $dataAksesUAM['akses_divisi'];
		// $this->segmenUAM 	= $dataAksesUAM['akses_segmen'];
		// $this->tregUAM 		= $dataAksesUAM['akses_treg'];
		// $this->witelUAM 	= $dataAksesUAM['akses_witel'];
		// $this->amUAM 		= $dataAksesUAM['akses_am'];
		// } else {
			
		// }
    }
	
	public function index(){
		if(checkingsessionpwt()){

			// $data['divisiUAM'] 	= str_replace(",","','",$this->divisiUAM);
			// $data['segmenUAM'] 	= str_replace(",","','",$this->segmenUAM);
			// $data['tregUAM'] 	= str_replace(",","','",$this->tregUAM);
			// $data['witelUAM'] 	= str_replace(",","','",$this->witelUAM);
			// $data['amUAM'] 		= str_replace(",","','",$this->amUAM);
			$session	 		= $this->session->userdata('sesspwt'); 
			$userid 	 		= $session['userid'];
			$role 	 			= $session['id_role'];
			$qCUAMM				= "
								SELECT a.userid,a.username,b.nama_role,c.id_menu,d.menu,d.url,d.sort FROM user a
								left join role b
								on a.id_role=b.id_role
								left join role_menu c
								on b.id_role=c.id_role
								left join menu d
								on c.id_menu=d.id_menu
								where userid='$userid' and url!='#'
								order by id_role_menu
								limit 1
								";
			$qcekUAMMENU		= $this->query->getDataByQ($qCUAMM);
			$cekUAMMENU			= array_shift($qcekUAMMENU);

			$data['akses']			= $this->akses;
			$data['userid']			= $this->userid;
			
			if ($cekUAMMENU['id_menu']=='1') {
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				if ($role==1) {
					$this->load->view('panel/dashboard/admin');
				} else {
					$this->load->view('panel/dashboard/index');
				}

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			} else {
				$url			= $cekUAMMENU['url'];
				// echo "urlnya".$url;
				redirect(''.$url.'');
			}
		} else {
			// $this->load->view('panel/login');
		}
	}

	public function setpassword(){
		if(checkingsessionpwt()){
			redirect('/panel/userprofile');
		} else {
			$this->load->view('panel/user/setpassword');
		}
	}

	public function log(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/log/log', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}
	
	public function roles(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']		= $this->akses;
				$data['userid']		= $this->userid;
				$data['getMenus'] 	= $this->query->getData('menu','*',"where parent='0' ORDER BY sort ASC");

				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/user/roles', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2');

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function workflow(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']		= $this->akses;
				$data['userid']		= $this->userid;
				$data['getMenus'] 	= $this->query->getData('level_user','*',"ORDER BY level ASC");
				$valcrud 			= $this->query->getData('sbrdoc','count(*)as valcrud',"where status not in(0,4,5)");
				$data['validation'] 	= array_shift($valcrud);
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/workflow/workflow', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2');

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}
	
	public function user(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				$data['getDataRole'] 	= $this->query->getData('role','*',"ORDER BY nama_role ASC");
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/user/user', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}
	public function customer(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				$data['getDataRole'] 	= $this->query->getData('role','*',"ORDER BY nama_role ASC");
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/customer/customer', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function workgroup(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				$data['getDataRole'] 	= $this->query->getData('role','*',"ORDER BY nama_role ASC");
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT view
				$this->load->view('panel/workgroup/index', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function kategori(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				$data['getDataRole'] 	= $this->query->getData('role','*',"ORDER BY nama_role ASC");
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT view
				$this->load->view('panel/kategori/index', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function produk(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				$data['getDataRole'] 	= $this->query->getData('role','*',"ORDER BY nama_role ASC");
				$data['getDataKategori'] 	= $this->query->getData('kategori','*',"ORDER BY kategori_name ASC");
				$data['getDataProduk'] 	= $this->query->getData('produk','*',"ORDER BY nama_produk ASC");
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT view
				$this->load->view('panel/produk/index', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	
	public function error(){
		$this->load->view('panel/error');
	}

	public function userprofile(){
		if(checkingsessionpwt()){
			$sess	 		= $this->session->userdata('sesspwt'); 
			$uid 	 		= $sess['userid'];
			$data['uid'] 	= $uid;

			$data['akses']	= 'ada';
			$data['userid']	= 'ada';

			// CORE 1
			$this->load->view('/theme/metronic/base1');

			// CONTENT
			$this->load->view('panel/user/userprofile', $data);

			// CORE2
			$this->load->view('/theme/metronic/base2', $data);

			// PLUGIN JS
			$this->load->view('/theme/metronic/pluginjs');
		} else {
			
		}
	}

	public function config(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;

			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$sess	 		= $this->session->userdata('sesspwt'); 
				$uid 	 		= $sess['userid'];
				$data['uid'] 	= $uid;

				$data['akses']	= 'ada';
				$data['userid']	= 'ada';

				$data['getDataSite'] 	= $this->query->getData('configsite','*',"where id_site='1'");
				$data['getDataEmail'] 	= $this->query->getData('mail_site','*',"ORDER BY email ASC");
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/config/config', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function pesanan(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/pesanan/index', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2');

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function detailpesanan($id){
		if(checkingsessionpwt()){
			$cekAkses				= $this->akses;
			$data['akses']			= $this->akses;
			$data['id']				= $id;
			
			// CORE 1
			$this->load->view('/theme/metronic/base1');

			// CONTENT
			$this->load->view('panel/pesanan/detail', $data);

			// CORE2
			$this->load->view('/theme/metronic/base2');

			// PLUGIN JS
			$this->load->view('/theme/metronic/pluginjs');
		} else {
			
		}
	}

	public function printpesanan($id){
		if(checkingsessionpwt()){
			$cekAkses				= $this->akses;
			$data['akses']			= $this->akses;
			$data['id']				= $id;
			
			// CORE 1
			// $this->load->view('/theme/metronic/base1');

			// CONTENT
			$this->load->view('panel/pesanan/print', $data);

			// CORE2
			// $this->load->view('/theme/metronic/base2');

			// PLUGIN JS
			// $this->load->view('/theme/metronic/pluginjs');
		} else {
			
		}
	}

	public function printkaos($id){
		if(checkingsessionpwt()){
			$cekAkses				= $this->akses;
			$data['akses']			= $this->akses;
			$data['id']				= $id;
			
			// CORE 1
			// $this->load->view('/theme/metronic/base1');

			// CONTENT
			$this->load->view('panel/pesanan/printkaos', $data);

			// CORE2
			// $this->load->view('/theme/metronic/base2');

			// PLUGIN JS
			// $this->load->view('/theme/metronic/pluginjs');
		} else {
			
		}
	}

	public function printpaper($id){
		if(checkingsessionpwt()){
			$cekAkses				= $this->akses;
			$data['akses']			= $this->akses;
			$data['id']				= $id;
			
			// CORE 1
			// $this->load->view('/theme/metronic/base1');

			// CONTENT
			$this->load->view('panel/pesanan/printpaper', $data);

			// CORE2
			// $this->load->view('/theme/metronic/base2');

			// PLUGIN JS
			// $this->load->view('/theme/metronic/pluginjs');
		} else {
			
		}
	}

	public function printcard($id){
		if(checkingsessionpwt()){
			$cekAkses				= $this->akses;
			$data['akses']			= $this->akses;
			$data['id']				= $id;
			
			// CORE 1
			// $this->load->view('/theme/metronic/base1');

			// CONTENT
			$this->load->view('panel/pesanan/printcard', $data);

			// CORE2
			// $this->load->view('/theme/metronic/base2');

			// PLUGIN JS
			// $this->load->view('/theme/metronic/pluginjs');
		} else {
			
		}
	}

	public function printpengiriman($id){
		if(checkingsessionpwt()){
			$cekAkses				= $this->akses;
			$data['akses']			= $this->akses;
			$data['id']				= $id;
			
			// CORE 1
			// $this->load->view('/theme/metronic/base1');

			// CONTENT
			$this->load->view('panel/pengiriman/print', $data);

			// CORE2
			// $this->load->view('/theme/metronic/base2');

			// PLUGIN JS
			// $this->load->view('/theme/metronic/pluginjs');
		} else {
			
		}
	}

	public function pengiriman(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/pengiriman/index', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2');

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function detailpengiriman($id){
		if(checkingsessionpwt()){
			$cekAkses				= $this->akses;
			$data['akses']			= $this->akses;
			$data['id']				= $id;
			
			// CORE 1
			$this->load->view('/theme/metronic/base1');

			// CONTENT
			$this->load->view('panel/pengiriman/detail', $data);

			// CORE2
			$this->load->view('/theme/metronic/base2');

			// PLUGIN JS
			$this->load->view('/theme/metronic/pluginjs');
		} else {
			
		}
	}

	public function payment(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/payment/index', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2');

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function stok(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/stok/index', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2');

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function detailstok($id){
		if(checkingsessionpwt()){
			$cekAkses				= $this->akses;
			$data['akses']			= $this->akses;
			$data['id']				= $id;
			
			// CORE 1
			$this->load->view('/theme/metronic/base1');

			// CONTENT
			$this->load->view('panel/stok/detail', $data);

			// CORE2
			$this->load->view('/theme/metronic/base2');

			// PLUGIN JS
			$this->load->view('/theme/metronic/pluginjs');
		} else {
			
		}
	}

	public function stokpjg(){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			if ($cekAkses=='') {
				redirect('panel/error');
			} else {
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/stok/indexpjg', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2');

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function widget($type,$pengaju,$noreq,$status){
		$data['pengaju'] = $pengaju;
		$data['noreq'] = $noreq;
		$data['status'] = $status;

		if($type=='dashboardStatus'){
			// CONTENT Dashboard Status
			$this->load->view('panel/dashboard/widget/dashboardStatus',$data);
		}else if($type=='dashboardDonut'){
			// CONTENT Dashboard Donut
			$this->load->view('panel/dashboard/widget/dashboardDonut',$data);
		}else if($type=='dashboardAm'){
			$this->load->view('panel/dashboard/widget/dashboardAM',$data);
		}
	}

	public function resultadmin($userid){
		$data['userid'] 	= $userid;

		$this->load->view('panel/dashboard/resultadmin',$data);
	}

	public function cekkk(){
		$this->load->view('cek');
	}
}
