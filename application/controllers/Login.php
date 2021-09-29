<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

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
    }
	
	public function index(){
		if(checkingsessionpwt()){
			$this->load->view('panel/dashboard');
		} else {
			// redirect('/panel');
		}
	}

	public function ceklogin() {
		// ambil cookie

		$username	= trim(strip_tags(stripslashes($this->input->post('username',true))));
		$password	= trim(strip_tags(stripslashes($this->input->post('password',true))));
		$epassword	= md5(trim(strip_tags(stripslashes($this->input->post('password',true)))));
		$remember	= trim(strip_tags(stripslashes($this->input->post('remember',true))));

		$row 		= $this->auth->getuser($username)->row_array();
		$id 		= $row['userid'];

		if(isset($row['userid'])) {
			if($row['ldap']!=1){
				if ($row['id_role']==0) {
					echo "";
				} else {
					if ($remember=='on') {
						if($row['password']==$epassword){
							$rows = array('data'=>$row);
							$this->session->set_userdata('sesspwt', $row);
							$coba = $this->session->userdata('sesspwt');
							print $row['name'];
						}else{
							echo "";
						}
					} else {
						if($row['password']==$epassword){
							$rows = array('data'=>$row);
							$this->session->set_userdata('sesspwt', $row);
							$coba = $this->session->userdata('sesspwt');
							print $row['name'];
						}else{
							echo "";
						}
					}
				}
			} else {
				if ($row['id_role']==0) {
					echo "";
				} else {
					$CGCauth = $this->auth_ldap($username, $password);
					if($CGCauth!='null'){
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

	public function auth_ldap($username, $password){
		// function auth_ldap2(){
		// $username 	= '400624';
		// $password 	= '30SolCrea';
		$url 		= 'https://auth.telkom.co.id/services/auth?username='.$username.'&password='.$password.'';
		$JSON 		= file_get_contents($url);
		
		$gdata 		= json_decode(json_encode($JSON), True);
		$data		= json_decode($gdata);
		$sukses		= $data->login;
		
		if ($sukses=='1') {
			$url2 = 'https://auth.telkom.co.id/api/call/'.$username.'';
			$JSON2 = file_get_contents($url2);
			
			$gdata2 	= json_decode(json_encode($JSON2), True);
			$data2		= json_decode($gdata2);
			$user		= $data2->username;
			$name		= $data2->name;
			$email		= $data2->email;
			
			// echo $JSON2;
			$ret = array(
				"name" 		=> $name,
				"username" 	=> $username,
				"email" 	=> $email
			);
		} else {
			$ret = null;
		}
		return json_encode($ret);
    }

    public function logout(){
		$this->session->sess_destroy();
        redirect('./panel');
	}
}
