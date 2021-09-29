<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crontab extends CI_Controller {

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
    }
	
	public function index(){
		if(checkingsessionpwt()){
			$this->load->view('panel/dashboard');
		} else {
			// redirect('/panel');
		}
	}

	public function mailnotification(){
		 $query = "
		 	SELECT 
			 *
			FROM (
			 SELECT a.*,
			  (SELECT status from sbrteknis.sbrdoc where no_request=a.no_request) status,
			  (SELECT name from sbrteknis.user where userid=a.created_by) created_byname,
			  (SELECT name from sbrteknis.user where userid=a.send_to) sendto_name,
			  (SELECT email from sbrteknis.user where userid=a.send_to) email_to,
			  DATE_PART('day', now()::TIMESTAMP - created_at) as selisih,
			  (SELECT value from sbrteknis.param where name='mail_notif') max_days
			 from sbrteknis.sbrhistory a 
			) AS base
			where status in (1,2,3)
			and selisih::int > max_days::int;
		 ";
		 $execute = $this->db->query($query)->result_array();
		 $numrow = $this->db->query($query)->num_rows();

		 if($numrow < 1){
		 	echo "NO EMAIL NOTIFICATION";
		 }else{
			foreach($execute as $row){
				$norequest = str_replace('/', '-', $row['no_request']);
				$created_byname = $row['created_byname'];
				$created_at = $row['created_at'];
				$action = $row['action'];
				$sendto_name = $row['sendto_name'];
				$email_to = $row['email_to']; 		 	
				$this->sendMail($norequest,$created_byname,$created_at,$action,$sendto_name,$email_to);
			}		 	
		}
		$qmax = "
			select max(id)as maxid from sbrteknis.crontab_loging
		";
		$exec_max = $this->db->query($qmax)->result_array();
		$rmax = array_shift($exec_max);
		$id = $rmax['maxid']+1;
		$data = array( 
				'id' => $id,
		        'tanggal' => ''.date('Y-m-d h:i:s').'',
		        'jumlah_sendmail' => $numrow
		); 
		$this->db->insert('sbrteknis.crontab_loging', $data);
	}	
	function sendMail($norequest,$created_byname,$created_at,$action,$sendto_name,$email_to) {

		$subject 	= 'SBR Online Application';
		$norequestSTR  = str_replace('-', '/', $norequest);
		
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'smtp.hostinger.co.id',
			'smtp_port' => 587,
			'smtp_user' => 'isrm2m@parwatha.com', // change it to yours
			'smtp_pass' => 'b1sm1llah', // change it to yours
			'mailtype'  => 'html',
			'charset'   => 'iso-8859-1'
		);
		$fornonldap = ''; 
		//Email content
		$htmlContent = '';
		$htmlContent .= '
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8" />
			  <title>No Reply</title>
			  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
			  <style>
			  </style>
			</head>
			<body style="font-family: verdana; font-size: 14px;">
				<div class="bg" style="background: #FFF; width: 70%; margin: 0 auto;">
					<div id="logo" style="background: #FFF;"><img src="'.base_url().'images/logotel.png" style="max-height: 70px; margin-top: 20px;"></div>
					<div id="confirmation-message">
						<div class="ravis-title-t-2" style="text-align: left; margin-top: 20px;">
							<div class="title" style="color: #1e1e1e; font-size: 24px;"><span>Dear, '.$sendto_name.'</span></div>
						</div>
						<div class="desc" style="color: #1e1e1e; margin-top:20px; font-siz: 14px;">
							<div style="border-bottom: 1px dashed #efefef; padding-bottom: 10px;">
								Alert! You have 1 Document SBR waiting your Review . Please find below information:
							</div>
							
							<div style="margin-top: 20px; border-bottom: 1px dashed #efefef; margin-bottom: 10px; padding-bottom: 10px;">
								<table style="font-size: 14px;">
								 <tbody>
								 	<tr>
								 		<td>No Request</td>
								 		<td>:</td>
								 		<td><b>'.$norequestSTR.'</b></td>
								 	</tr>
								 	<tr>
								 		<td>Name AM</td>
								 		<td>:</td>
								 		<td><b>'.$created_byname.'</b></td>
								 	</tr>
								 	<tr>
								 		<td>Status</td>
								 		<td>:</td>
								 		<td><b>'.$action.'</b></td>
								 	</tr>
								 	<tr>
								 		<td>Create Date</td>
								 		<td>:</td>
								 		<td><b>'.$created_at.'</b></td>
								 	</tr>
								 </tbody>
								</table>
							</div>

							'.$fornonldap.'

							<div>
								<div style="padding-top: 20px; padding-bottom: 10px;">Best Regards,</div>

								<div>
									<b style="font-size: 16px; padding-bottom:0px; margin-bottom: 0px;">
										SBR-<span style="color: #b63127;">ONLINE</style>
									</b>
								</div>
								<div><b><b></div>
							</div>
						</div>
					</div>
				</div>
			</body>
			</html>
		';
	
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('isrm2m@parwatha.com'); // change it to yours
		$this->email->to($email_to);// change it to yours
		$this->email->subject($subject);
		$this->email->set_mailtype("html");
		$this->email->message($htmlContent);
		$send = $this->email->send();
		//echo $send;
		if($send == true){
			echo '';
			echo "Success";
		} else {
			//var_dump($this->email->print_debugger());
			show_error($this->email->print_debugger());
		}
		// echo $htmlContent;
	}


}
