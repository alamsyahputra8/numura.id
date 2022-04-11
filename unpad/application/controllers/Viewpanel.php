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

		ini_set('max_execution_time', 123456789);
		ini_set("memory_limit","2256M");
		
		// if(checkingsessionpwt()){
			// $session = checkingsessionpwt();
		$session	 	= $this->session->userdata('sesspwt'); 
		@$userid 	 	= $session['userid'];
		@$profile 	 	= $session['id_role'];
		$menu 		 	= uri_string();
		$data_akses  	= $this->query->getAkses($profile,$menu);
		$shift 		 	= array_shift($data_akses);
		@$this->akses 	= $shift['akses'];
		$this->userid 	= $userid;
			
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
	 public function set_to($language) {
		   if(strtolower($language) === 'english') {
				$lang = 'en';
		   } else {
				$lang = 'in';
		   }
		   set_cookie(
				array(
				 'name' => 'lang_is',
				 'value' => $lang,
				 'expire'  => '8650',
				 'prefix'  => ''
				)
		   );
	   
		   if($this->input->server('HTTP_REFERER')){
			redirect($this->input->server('HTTP_REFERER'));
		   }
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
			$qCUAMM				= "
								SELECT a.userid,a.username,b.nama_role,c.id_menu,d.menu,d.url,d.sort FROM `user` a
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
				$this->load->view('panel/dashboard/index');

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
	
	public function menus(){
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
				$this->load->view('panel/menus/menus', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function content(){
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
				$this->load->view('panel/content/basic', $data);

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
	
	public function logout(){
		$this->session->sess_destroy();
        redirect('./panel');
	}
	
	public function ceklogin() {
		// ambil cookie

		$username	= trim(strip_tags(stripslashes($this->input->post('username',true))));
		$password	= md5(trim(strip_tags(stripslashes($this->input->post('password',true)))));
		$remember	= trim(strip_tags(stripslashes($this->input->post('remember',true))));

		$row = $this->auth->getuser($username)->row_array();
		
		if(isset($row['userid'])) {
			if ($row['id_role']==0) {
				echo "";
			} else {
				if ($remember=='on') {
					if($row['password']==$password){
						$rows = array('data'=>$row);
						$this->session->set_userdata('sesspwt', $row);
						$coba = $this->session->userdata('sesspwt');
						print $row['name'];
					}else{
						echo "";
					}
				} else {
					if($row['password']==$password){
						$rows = array('data'=>$row);
						$this->session->set_userdata('sesspwt', $row);
						$coba = $this->session->userdata('sesspwt');
						print $row['name'];
					}else{
						echo "";
					}
				}
			}
		}else{
			echo "";
		}
	}
	
	public function mailweekly(){
		if(checkingsessionpwt()){
			$data['akses']= $this->akses;
			$this->load->view('panel/user/mailweekly.php',$data);
		} else {
			
		}
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

	public function kontak(){
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
				$this->load->view('panel/kontak/kontak', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2');

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
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/produk/produk', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2');

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function blog(){
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
				$this->load->view('panel/content/blog', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function link(){
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
				$this->load->view('panel/link/link', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function mailsite(){
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
				$this->load->view('panel/config/mail', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function banner(){
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
				$this->load->view('panel/content/banner', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
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

	public function document(){
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
				$this->load->view('panel/document/document', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function photos(){
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
				$this->load->view('panel/gallery/photos', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function videos(){
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
				$this->load->view('panel/gallery/videos', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function detailinbox($id){
		if(checkingsessionpwt()){
			$cekAkses			= $this->akses;
			/*if ($cekAkses=='') {
				redirect('panel/error');
			} else {*/
				$data['akses']			= $this->akses;
				$data['userid']			= $this->userid;
				$data['idinbox']		= $id;
				
				// CORE 1
				$this->load->view('/theme/metronic/base1');

				// CONTENT
				$this->load->view('panel/inbox/detail', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			//}
		} else {
			
		}
	}

	public function services(){
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
				$this->load->view('panel/content/services', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function works(){
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
				$this->load->view('panel/content/works', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function album(){
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
				$this->load->view('panel/gallery/album', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}

	public function event(){
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
				$this->load->view('panel/event/event', $data);

				// CORE2
				$this->load->view('/theme/metronic/base2', $data);

				// PLUGIN JS
				$this->load->view('/theme/metronic/pluginjs');
			}
		} else {
			
		}
	}
}
