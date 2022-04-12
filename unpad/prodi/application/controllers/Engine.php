<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Engine extends CI_Controller {

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
	public function __construct(){
		date_default_timezone_set("Asia/Bangkok");
        parent::__construct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->model('auth'); 
		$this->load->model('query'); 
		$this->load->model('formula'); 
    }
	
	public function index(){
		// if(checkingsessionelopM()){
			// $this->load->view('panel/dashboard');
		// } else {
			// redirect('/panel');
		// }
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
	}
	
	public function testbook(){
		if(checkingbasket()){
			$databasket = $this->session->userdata('basket');
			print json_encode($databasket);
		} else {
			echo "kosong";
		}
	}
	
	public function booking(){
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
		
		$data['idroom']		= trim(strip_tags(stripslashes($this->input->post('idroom',true))));
		$data['start']		= trim(strip_tags(stripslashes($this->input->post('start',true))));
		$data['end']		= trim(strip_tags(stripslashes($this->input->post('end',true))));
		$data['adult']		= trim(strip_tags(stripslashes($this->input->post('adult',true))));
		$data['child']		= trim(strip_tags(stripslashes($this->input->post('child',true))));
		
		$data['getData']		= $this->query->getData('room','a.*, b.name_cat, b.description','a LEFT JOIN category_room b on a.id_cat=b.id_cat ORDER BY a.name ASC');
		
		$this->load->view('theme/front/plugin', $data);
		$this->load->view('theme/front/header', $data);
		
		$this->load->view('front/booking/search', $data);
		
		$this->load->view('theme/front/footer', $data);
		$this->load->view('theme/front/plugin_js', $data);
	}
	
	public function addroom(){
		$id			= trim(strip_tags(stripslashes($this->input->post('id',true))));
		$getData	= $this->query->getData('room','a.*, b.name_cat, b.description',"a LEFT JOIN category_room b on a.id_cat=b.id_cat WHERE a.id_room='".$id."'");
		$room		= array_shift($getData);
		echo '
			<div class="selected-room-container" style="margin: 0px;">
				<div class="selected-room-box active">
					<div class="room-title">
						<input type="hidden" id="idrooms" name="idrooms" value="'.$id.'">
						<div class="title" style="width: auto; font: 24px/45px;">
							'.$room['name'].'
						</div>
						<div class="value"></div>
					</div>
					<select name="adult" class="adult-field" style="background: #1e1e1e!important; margin: 0 0px; width: 49%;">
						<option value="1">1 Adult</option>
						<option value="2">2 Adults</option>
						<option value="3">3 Adults</option>
						<option value="4">4 Adults</option>
						<option value="5">5 Adults</option>
					</select>
					<select name="child" style="background: #1e1e1e!important; margin: 0 0px; width: 49%;">
						<option value="0">No Child</option>
						<option value="1">1 Children</option>
						<option value="2">2 Children</option>
						<option value="3">3 Children</option>
						<option value="4">4 Children</option>
						<option value="5">5 Children</option>
					</select>
					<div class="field-row" style="margin-top: 10px;">
						<select name="qty" style="background: #1e1e1e!important; margin: 0 0px; width: 100%;" required>
							<option value="">No. of rooms</option>
							<option value="1">1 Room</option>
							<option value="2">2 Rooms</option>
							<option value="3">3 Rooms</option>
							<option value="4">4 Rooms</option>
							<option value="6">6 Rooms</option>
							<option value="7">7 Rooms</option>
						</select>
					</div>
				</div>
			</div>
		';
	}
	
	public function insertbook(){
		// error_reporting(0);
		// $id			= trim(strip_tags(stripslashes($this->input->post('idrooms',true))));
		$rooms		= $_POST['rooms'];
		$start		= trim(strip_tags(stripslashes($this->input->post('start',true))));
		$end		= trim(strip_tags(stripslashes($this->input->post('end',true))));
		$adult		= $_POST['adult'];
		$child		= $_POST['child'];
		$qty		= $_POST['qty'];
		
		$countRoom	= count($rooms);
		
		for($r=0;$r<$countRoom;$r++){
			$databasket[]	= array(
				'idroom'	=> $rooms[$r],
				'start'		=> $start,
				'end'		=> $end,
				'adult'		=> $adult[$r],
				'child'		=> $child[$r],
				'qty'		=> $qty[$r]
			);
			$json['data']	= $databasket;
			$coba = $this->session->set_userdata('basket', $databasket);
		}
		// for($r=0;$r<$countRoom;$r++){
			// $databasket	= array(
				// 'idroom'	=> $rooms,
				// 'start'		=> $start,
				// 'end'		=> $end,
				// 'adult'		=> $adult,
				// 'child'		=> $child,
				// 'qty'		=> $qty
			// );
		// }
		// print json_encode($json);
		
		// INSERT TO BASKET
		$coba = $this->session->set_userdata('basket', $databasket);
		// $coba = $this->session->set_userdata('basket', $databasket);
		
		if(checkingbasket()){
			echo "ada";
		} else {
			echo "";
		}
	}
	
	public function cekbasket(){
		$basket 	= $this->session->userdata('basket'); 
		print json_encode($basket);
	}
	
	public function registration(){
		$name		= trim(strip_tags(stripslashes($this->input->post('name',true))));
		$phone		= trim(strip_tags(stripslashes($this->input->post('phone',true))));
		$email		= trim(strip_tags(stripslashes($this->input->post('email',true))));
		$pass		= md5($_POST['password']);
		$alamat		= trim(strip_tags(stripslashes($this->input->post('alamat',true))));
		$fileName 	= str_replace(' ','_',time().$_FILES['pict']['name']);
		$date		= date('Y-m-d');
		
		$config['upload_path'] = './images/member/'; //buat folder dengan nama assets di root folder
		$config['file_name'] = $fileName;
		$config['allowed_types'] = 'gif|jpg|png';
		 
		$this->load->library('upload');
		$this->upload->initialize($config);
		 
		if(! $this->upload->do_upload('pict') )
		$this->upload->display_errors();
			 
		$media = $this->upload->data('pict');
		
		// INSERT MEMBER
		$rows = $this->query->insertData('member', "nama,email,password,phone,alamat,picture,status", "'$name','$email','$pass','$phone','$alamat','$fileName','0'");
		
		if($rows){
			// INSERT SUBSCRIBER
			$subscriber = $this->query->insertData('subscriber', "email,date_subs", "'$email','$date'");
			
			// SEND EMAIL
			$this->sendMailVer($name,$email,$pass);
			
			print json_encode(array('success'=>true,'total'=>1));
		} else {
			echo "";
		}
	}
	
	public function sendMailVer($name,$email,$pass) {
		$getSiteData 	= $this->query->getData('configsite','*',"");
		$datasite		= array_shift($getSiteData);
		
		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'ssl://mail.atriumcilacap.com',
		'smtp_port' => 465,
		'smtp_user' => 'noreply@atriumcilacap.com', // change it to yours
		'smtp_pass' => 'atrium@123', // change it to yours
		'mailtype'  => 'html',
		'charset'   => 'iso-8859-1'
		);
		
		//Email content
		$htmlContent = '';
		$htmlContent .= '
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8" />
			  <title>Verification Email - Atrium Premiere Hotel</title>
			  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
			  <style>
			  </style>
			</head>
			<body>
				<div class="bg" style="background: #222222; color: #FFF; width: 75%; padding: 30px; margin: 0 auto;">
					<div id="logo" style="padding: 40px; background: #1d1d1d;"><center><img src="'.base_url().'images/'.$datasite['logo'].'"></center></div>
					<div id="confirmation-message" style="padding: 30px; text-align: center;">
						<div class="ravis-title-t-2" style="text-align: center;">
							<div class="title" style="color: #FFFFFF; font-size: 34px;"><span>Dear, '.$name.'</span></div>
						</div>
						<div class="desc" style="color: #FFF; margin-top:20px;">
							In order to help maintain the security of your AtriumPremiere account, please verify your email address.<br><br>
							<a href="'.base_url().'verification/'.$email.'/'.$pass.'" style="color: #d2bd7f;
							-webkit-transition: all 0.3s ease;
							-o-transition: all 0.3s ease;
							transition: all 0.3s ease;
							text-decoration:none;">Click here to verify your email address.</a>
						</div>
						<div class="desc" style="margin-top:20px;color: #FFF;">
							Thanks for helping us maintain the security of your account.
						</div>
					</div>
				</div>
			</body>
			</html>
		';

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('noreply@atriumcilacap.com'); // change it to yours
		$this->email->to($email);// change it to yours
		$this->email->subject('AtriumPremiere email verification');
		$this->email->set_mailtype("html");
		$this->email->message($htmlContent);
		
		if($this->email->send()) {
			echo 'Email sent.';
		} else {
			show_error($this->email->print_debugger());
		}
		// echo $htmlContent;
	}
	
	public function verification($email,$pass){
		$cekMail	= $this->query->getData('member','*',"WHERE email='$email' and password='$pass'");
		$valcek		= array_shift($cekMail);
		$id			= $valcek['id_member'];
		
		if (!empty($valcek)) {
			$data['status']	= '1';
			$rows = $this->query->updateData('member',"status='1'","WHERE id_member='".$id."'");
		} else {
			$data['status']	= '2';
		}
		$data['getSiteData'] 	= $this->query->getData('configsite','*',"");
		$data['getRoomCat'] 	= $this->query->getData('category_room','*',"order by id_cat ASC");
		$this->load->view('front/member/verification',$data);
	}
	
	public function loginreserv() {
		$email		= $_POST['email'];
		$password	= md5($_POST['password']);

		$row = $this->auth->getmember($email,$password)->row_array();
		
		if(isset($row['id_member'])) {
			if ($row['status']=='1') {
				$rows = array('data'=>$row);
				$this->session->set_userdata('member', $row);
				$coba = $this->session->userdata('member');
				
				echo "ada";
			} else {
				echo "";
			}
		}else{
			echo "";
		}
	}
	
	public function bookingfinish() {
		// DATA BOOKING
		$jatuhtempo	= date('Y-m-d', strtotime($harini. ' + 2 days'));
		$idmember	= trim(strip_tags(stripslashes($this->input->post('idmember',true))));
		$name		= trim(strip_tags(stripslashes($this->input->post('name',true))));
		$phone		= trim(strip_tags(stripslashes($this->input->post('phone',true))));
		$email		= trim(strip_tags(stripslashes($this->input->post('email',true))));
		$alamat		= trim(strip_tags(stripslashes($this->input->post('alamat',true))));
		$totalprice	= trim(strip_tags(stripslashes($this->input->post('grandtotal',true))));
		$date		= date('Y-m-d');
		$today 		= date("Ymd");
		
		// SETUP JATUH TEMPO
		$dateCreate = date_create(date('Y-m-d H:i:s'));
		$addtime 	= date_add($dateCreate, date_interval_create_from_date_string('6 hours'));
		$jatuhtempo	= date_format($addtime, 'Y-m-d H:i:s');
		
		// DATA DETAIL
		$idroom		= $_POST['idroom'];
		$start		= $_POST['startdate'];
		$end		= $_POST['enddate'];
		$adult		= $_POST['adult'];
		$child		= $_POST['child'];
		$price		= $_POST['price'];
		$qty		= $_POST['qty'];
		
		// SETUP NOTIFICATION
		$getID		= $this->query->getData('notifications','max(id_notif) AS last',"WHERE id_notif LIKE '$today%'");
		$fID		= array_shift($getID);
		$lastNoOrder 	= $fID['last'];
		if(isset($getID)){
			$lastNoUrut = substr($lastNoOrder, 8, 4);		
			$nextNoUrut = $lastNoUrut + 1;		
			$notif 		= $today.sprintf('%04s', $nextNoUrut);
		} else {		
			$notif		= $today."0001";	
		}	
		$pesan 			= $name." melakukan reservasi";
		
		// CREAT ID BOOKING
		$today2 	= date("Ymd");
		$harini 	= date("Y-m-d");
		$sekarang 	= date('md');
		$getIDB		= $this->query->getData('booking','kode_booking',"WHERE kode_booking LIKE '%".$sekarang."%' ORDER BY kode_booking DESC LIMIT 0,1");
		$dataKB		= array_shift($getIDB);
		$kodeawal	= substr($dataKB['kode_booking'],6,2)+1;
		if($kodeawal<10){
			$kode	= '0'.$kodeawal;
		}else{
			$kode	= $kodeawal;
		}
		$tgl 		= date('d'); 
		$bulan 		= date('m');
		$kwitansi 	= "AP".$bulan.$tgl.$kode;
		
		// INSERT BOOKING DATA
		$rows 	= $this->query->insertData('booking', "kode_booking,id_member,nama,email,alamat,phone,book_date,total_price,jatuhtempo,id_notif,baca", "'$kwitansi','$idmember','$name','$email','$alamat','$phone','$date','$totalprice','$jatuhtempo','$notif','N'");
		
		// SETUP AND INSERT DETAIL DATA
		$countRoom	= count($idroom);
		if (isset($rows)) {
			$insNotif	= $this->query->insertData('notifications',"id_notif,notifications,type,baca,date","'$notif','$pesan','reservation','N','".date("Y-m-d H:i:s")."'");
			for($r=0;$r<$countRoom;$r++){
				// PENGURANGAN AVAIL ROOM
				$getExA		= $this->query->getData('room','*',"WHERE id_room='".$idroom[$r]."'");
				$dataExA	= array_shift($getExA);
				$exis		= $dataExA['availroom'];
				$newAvail	= $exis-$qty[$r];
				$min		= $this->query->updateData('room',"availroom='$newAvail'","WHERE id_room='".$idroom[$r]."'");
				
				$insDetail	= $this->query->insertData('booking_detail',"kode_booking,id_room,adult,child,qty,date_checkin,date_checkout,price,discount","'$kwitansi','".$idroom[$r]."','".$adult[$r]."','".$child[$r]."','".$qty[$r]."','".$start."','".$end."','".$price[$r]."','0'");
			}
			
			// SEND EMAIL
			$this->sendMailReserv($kwitansi,$idmember,$email);
			
			$getMailSite 	= $this->query->getData('configsite','b.id_email,b.email',"a LEFT JOIN mail_site b on a.mail_site=b.id_email");
			$datamailsite	= array_shift($getMailSite);
			$getDataFwd		= $this->query->getData('mail_fwd','*',"WHERE id_email='".$datamailsite['id_email']."'");
			foreach($getDataFwd as $dataFwd){
				$emailfwd	= $dataFwd['email'];
				$act		= 'Reservation';
				$subject	= 'Notifications AtriumPremiere Website';
				
				//SEND EMAIL NOTIFICATION TO ADMIN
				$this->sendMailReservtoAdmin($idmember,$act,$emailfwd,$subject);
			}
			
			$this->session->unset_userdata('basket');

			echo "done";
		} else {
			echo "";
		}
	}
	
	public function sendMailReserv($kode,$idmember,$email) {
		$getSiteData 	= $this->query->getData('configsite','*',"");
		$datasite		= array_shift($getSiteData);
		
		$getDataBook 	= $this->query->getData('booking','*',"WHERE kode_booking='$kode'");
		$databook		= array_shift($getDataBook);
		
		$getDataMember 	= $this->query->getData('member','*',"WHERE id_member='$idmember'");
		$datamember		= array_shift($getDataMember);
		
		$getDataBank 	= $this->query->getData('bank','*',"ORDER BY nama_bank ASC");
		$bank			.= '';
		foreach ($getDataBank as $databank){
			$bank		.= '
				<tr>
					<td style="text-align: right;">'.$databank['nama_bank'].'</td>
					<td style="text-align: left;">: '.$databank['no_rekening'].' a/n '.$databank['atas_nama'].'</td>
				</tr>
			';
		}
		
		$getDataRoom 	= $this->query->getData('booking_detail','a.*,b.name as nameroom',"a LEFT JOIN room b on a.id_room=b.id_room WHERE kode_booking='$kode' ORDER BY id_room DESC");
		$datarows		.= '';
		foreach ($getDataRoom as $dataroom){
			// COUNT JML HARI
			$date1 		= new DateTime($dataroom['date_checkin']);
			$date2 		= new DateTime($dataroom['date_checkout']);
			$jmlHari	= $date2->diff($date1)->format("%a");
			
			$datarows		.= '
				<tr>
					<td style="padding:5px; font-size:10px; border: 1px solid #1d1d1d; color: #000;">'.$dataroom['nameroom'].'</td>
					<td style="padding:5px; font-size:10px; border: 1px solid #1d1d1d; color: #000;">'.$this->TanggalIndo($dataroom['date_checkin']).'</td>
					<td style="padding:5px; font-size:10px; border: 1px solid #1d1d1d; color: #000;">'.$this->TanggalIndo($dataroom['date_checkout']).'</td>
					<td style="padding:5px; font-size:10px; border: 1px solid #1d1d1d; color: #000;">'.$dataroom['adult'].' Person</td>
					<td style="padding:5px; font-size:10px; border: 1px solid #1d1d1d; color: #000;">'.$dataroom['child'].' Person</td>
					<td style="padding:5px; font-size:10px; border: 1px solid #1d1d1d; color: #000;">'.$this->rupiah($dataroom['price']).'</td>
					<td style="padding:5px; font-size:10px; border: 1px solid #1d1d1d; color: #000;">'.$dataroom['qty'].' Rooms</td>
					<td style="padding:5px; font-size:10px; border: 1px solid #1d1d1d; text-align: center; color: #000;">'.$this->rupiah(($dataroom['price']*$dataroom['qty'])*$jmlHari).'</td>
				</tr>
			';
		}
		
		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'ssl://mail.atriumcilacap.com',
		'smtp_port' => 465,
		'smtp_user' => 'noreply@atriumcilacap.com', // change it to yours
		'smtp_pass' => 'atrium@123', // change it to yours
		'mailtype'  => 'html',
		'charset'   => 'iso-8859-1'
		);
		
		//Email content
		$htmlContent = '';
		$htmlContent .= '
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8" />
			  <title>Bukti Reservasi - Atrium Premiere Hotel</title>
			  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
			  <style>
			  </style>
			</head>
			<body>
				<div class="bg" style="background: #222222; color: #FFF; width: 95%; padding: 30px; margin: 0 auto;">
					<div id="logo" style="padding: 40px; background: #1d1d1d;"><center><img src="'.base_url().'images/'.$datasite['logo'].'"></center></div>
					<div id="confirmation-message" style="padding: 30px; text-align: center;">
						<div class="ravis-title-t-2" style="text-align: center;">
							<div class="title" style="color: #FFFFFF; font-size: 28px;"><span>Dear, '.$databook['nama'].'</span></div>
						</div>
						<div class="desc" style="color: #FFF; margin-top:20px;">
							Terimakasih atas pemesanan Anda melalui Internet Reservation Hotel Atrium Premiere<br>
							Kode Booking adalah : '.$kode.'<br>
							Anda harus membayar pemesanan ini paling lambat pada : '.$databook['jatuhtempo'].'
							<br><br>
							
							<table style="width: 100%; background:#FFF;" cellspacing="0" cellpadding="0">
								<thead>
									<tr>
										<th colspan="8" style="font-size: 14px; border: 1px solid #d2bd7f; background: #d2bd7f; color: #1e1e1e; padding: 10px;">Detail Reservasi</th>
									</tr>
									<tr>
										<th style="font-size: 14px; padding: 5px; text-align: left; border: 1px solid #1d1d1d; background: #9b8647; color: #FFF;">Room</th>
										<th style="font-size: 14px; padding: 5px; text-align: left; border: 1px solid #1d1d1d; background: #9b8647; color: #FFF;">CheckIn</th>
										<th style="font-size: 14px; padding: 5px; text-align: left; border: 1px solid #1d1d1d; background: #9b8647; color: #FFF;">CheckOut</th>
										<th style="font-size: 14px; padding: 5px; text-align: left; border: 1px solid #1d1d1d; background: #9b8647; color: #FFF;">Adult</th>
										<th style="font-size: 14px; padding: 5px; text-align: left; border: 1px solid #1d1d1d; background: #9b8647; color: #FFF;">Child</th>
										<th style="font-size: 14px; padding: 5px; text-align: left; border: 1px solid #1d1d1d; background: #9b8647; color: #FFF;">Price</th>
										<th style="font-size: 14px; padding: 5px; text-align: left; border: 1px solid #1d1d1d; background: #9b8647; color: #FFF;">No. of Rooms</th>
										<th style="font-size: 14px; padding: 5px; text-align: left; border: 1px solid #1d1d1d; background: #9b8647; color: #FFF; text-align: center;">Total</th>
									</tr>
								</thead>
								<tbody>
									'.$datarows.'
									<tr>
										<td colspan="7" style="padding:10px; font-size:12px; border: 1px solid #1d1d1d; background: #828181; color: #FFF; text-align:right; font-weight: bold;">
											Grand Total
										</td>
										<td style="padding:10px; font-size:16px; border: 1px solid #1d1d1d; background: #828181; color: #FFF; font-weight: bold; text-align: center;">
											'.$this->rupiah($databook['total_price']).'
										</td>
									</tr>
								</tbody>
							</table><br><br>
							<a href="javascript:void(0);" style="color: #d2bd7f;
							-webkit-transition: all 0.3s ease;
							-o-transition: all 0.3s ease;
							transition: all 0.3s ease;
							text-decoration:none;">
								Pembayaran dapat dilakukan di ATM Bank dan Payment Point (* Untuk beberapa Bank dapat melakukan pembayaran melalui Internet Banking, SMS Banking, dan layanan lainnya yang dimiliki).<br><br>
								No. Rekening tujuan pembayaran ke :
								<center>
								<table style="padding:10px;color: #FFF;">
									'.$bank.'
								</table>
								</center>
							</a>
						</div>
						<div class="desc" style="margin-top:20px;color: #FFF;">
							Untuk informasi pembayaran atau informasi lainnya bisa hubungi Call Center kami di : '.$datasite['phone'].'
						</div>
					</div>
				</div>
			</body>
			</html>
		';

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('noreply@atriumcilacap.com'); // change it to yours
		$this->email->to($email);// change it to yours
		$this->email->subject('Bukti Pemesanan Internet Atrium Premiere');
		$this->email->set_mailtype("html");
		$this->email->message($htmlContent);
		
		if($this->email->send()) {
			echo 'Email sent.';
		} else {
			show_error($this->email->print_debugger());
		}
		// echo $htmlContent;
	}
	
	public function logout(){
		$this->session->unset_userdata('member');
		$this->session->unset_userdata('basket');
        redirect(base_url());
	}
	
	public function getdatahistory(){
		if(checkingmember()) {
			$chaceMember			= $this->session->userdata('member');
			$getMember 				= $this->query->getData('member','*',"WHERE id_member='".$chaceMember['id_member']."'");
			$member 				= array_shift($getMember);
			$sekarang				= date('Y-m-d H:i:s');
			
			$getData			= $this->query->getData('booking','*',"WHERE id_member='".$member['id_member']."' ORDER BY book_date DESC");
			$no=0;
			foreach($getData as $data) {
				if ($data['jatuhtempo']>$sekarang and $data['status']=='0') {
					$updateStat		= $this->query->updateData('booking',"status='3'","WHERE kode_booking='".$data['kode_booking']."'");
				}
				
				if ($data['status']=='0') {
					$status = 'Pending Payment';
				} else if ($data['status']=='1') {
					$status = 'Waiting Confirmation';
				} else if ($data['status']=='2') {
					$status = 'Paid';
				} else {
					$status = 'Canceled';
				}
				$no++;
				$id 	= "<a title='See Detail' data-id='".$data['kode_booking']."' class='seedetailbook' style='cursor:pointer'>".$data['kode_booking']."</a>";
				$date 	= $this->TanggalIndo($data['book_date']);
				$total 	= $this->rupiah($data['total_price']);
	
				$row = array(
					$no,
					$id,
					$date,
					$total,
					$status
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/login');
		}
	}
	
	public function detailhistory(){
		if(checkingmember()) {
			$getSiteData 	= $this->query->getData('configsite','*',"");
			$datasite		= array_shift($getSiteData);
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$chaceMember		= $this->session->userdata('member');
			$getMember 			= $this->query->getData('member','*',"WHERE id_member='".$chaceMember['id_member']."'");
			$member 			= array_shift($getMember);
			
			$getDataBook 	= $this->query->getData('booking','*',"WHERE kode_booking='$id'");
			$databook		= array_shift($getDataBook);
			
			$getData			= $this->query->getData('booking_detail','a.*,b.name as nameroom',"a LEFT JOIN room b on a.id_room=b.id_room WHERE kode_booking='$id' ORDER BY id_room DESC");
			$no=0;
			
			if ($databook['status']==0) {
				$note	= '<center>Jika pembayaran masih belum dilakukan setelah jatuh tempo, maka reservasi dianggam batal.</center>';
				$notice = '
					<div class="desc" style="color: #d2bd7f;">
						<center>Anda harus membayar pemesanan ini paling lambat pada : <br>
						'.$this->formula->indonesian_date($databook['jatuhtempo']).'</center><br>
					</div>';
			} else {
				$note	= '';
				$notice = '';
			}
			
			echo '
				<div id="logo" style="padding: 40px; background: #1d1d1d;"><center><img src="'.base_url().'images/'.$datasite['logo'].'"></center></div>
				<div class="ravis-title-t-2">
					<div class="title custitle"><span>Invoice: '.$id.'</span></div>
				</div>
				'.$notice.'
				<div class="table-responsive">
					<table id="" class="table table-bordered dataTableExample2 table-striped table-hover" style="width: 100%; background: #FFF;">
						<thead class="bg-gray-dark text-white" style="font-size: 12px;">
							<tr>
								<th class="text-center" style="width: 5%;">No</th>
								<th class="text-center">Room</th>
								<th class="text-center">CheckIn</th>
								<th class="text-center">CheckOut</th>
								<th class="text-center">Adult</th>
								<td class="text-center">Child</td>
								<td class="text-center">Price</td>
								<td class="text-center">No. of Rooms</td>
								<td class="text-center">Total</td>
							</tr>
						</thead>
						<tbody style="font-size:10px;">
			';
							foreach($getData as $dataroom) {
								$no++;
								$date1 		= new DateTime($dataroom['date_checkin']);
								$date2 		= new DateTime($dataroom['date_checkout']);
								$jmlHari	= $date2->diff($date1)->format("%a");
								echo '
								<tr>
									<td>'.$no.'</td>
									<td>'.$dataroom['nameroom'].'</td>
									<td>'.$this->TanggalIndo($dataroom['date_checkin']).'</td>
									<td>'.$this->TanggalIndo($dataroom['date_checkout']).'</td>
									<td>'.$dataroom['adult'].' Person</td>
									<td>'.$dataroom['child'].' Person</td>
									<td>'.$this->rupiah($dataroom['price']).'</td>
									<td>'.$dataroom['qty'].' Rooms</td>
									<td>'.$this->rupiah(($dataroom['price']*$dataroom['qty'])*$jmlHari).'</td>
								</tr>
								';
							}
			echo '
						</tbody>
						<tfoot style="font-size: 12px;">
							<tr>
								<th colspan="8" class="text-right"><b>Grand Total</b></th>
								<th>'.$this->rupiah($databook['total_price']).'</th>
							</tr>
						</tfoot>
					</table>
					'.$note.'
				</div>
			';
		} else {
			redirect('/login');
		}
	}
	
	public function confirmation(){
		if(checkingmember()) {
			$chaceMember		= $this->session->userdata('member');
			$getMember 			= $this->query->getData('member','*',"WHERE id_member='".$chaceMember['id_member']."'");
			$member 			= array_shift($getMember);
			$idmember			= $member['id_member'];
			$name				= $member['nama'];
			
			// SETUP NOTIFICATION
			$today 		= date("Ymd");
			$getID		= $this->query->getData('notifications','max(id_notif) AS last',"WHERE id_notif LIKE '$today%'");
			$fID		= array_shift($getID);
			$lastNoOrder 	= $fID['last'];
			if(isset($getID)){
				$lastNoUrut = substr($lastNoOrder, 8, 4);		
				$nextNoUrut = $lastNoUrut + 1;		
				$notif 		= $today.sprintf('%04s', $nextNoUrut);
			} else {		
				$notif		= $today."0001";	
			}	
			$pesanNotif		= $name." melakukan pembayaran";
			
			$kode		= trim(strip_tags(stripslashes($this->input->post('kode',true))));
			$norek		= trim(strip_tags(stripslashes($this->input->post('norek',true))));
			$bankmem	= trim(strip_tags(stripslashes($this->input->post('bankmem',true))));
			$an			= trim(strip_tags(stripslashes($this->input->post('an',true))));
			$bank		= trim(strip_tags(stripslashes($this->input->post('bank',true))));
			$getnominal	= trim(strip_tags(stripslashes($this->input->post('nominal',true))));
			$nominal	= str_replace('.','',$getnominal);
			$pesan		= trim(strip_tags(stripslashes($this->input->post('pesan',true))));
			$date		= date('Y-m-d');
			
			// INSERT CONFIRMATION
			$rows = $this->query->insertData('payment', "kode_booking,id_bank,no_rekening,atas_nama,nominal,date,pesan,baca,id_notif", "'$kode','$bank','$norek','$an','$nominal','$date','$pesan','N','$notif'");
			
			if($rows){
				// INSERT NOTIFICATION
				$insNotif	= $this->query->insertData('notifications',"id_notif,notifications,type,baca,date","'$notif','$pesanNotif','payment','N','".date("Y-m-d H:i:s")."'");
				
				// UPDATE STATUS
				$updStatus	= $this->query->updateData('booking',"status='1'","WHERE kode_booking='$kode'");
				
				$getMailSite 	= $this->query->getData('configsite','b.id_email,b.email',"a LEFT JOIN mail_site b on a.mail_site=b.id_email");
				$datamailsite	= array_shift($getMailSite);
				$getDataFwd		= $this->query->getData('mail_fwd','*',"WHERE id_email='".$datamailsite['id_email']."'");
				foreach($getDataFwd as $dataFwd){
					$emailfwd	= $dataFwd['email'];
					$act		= 'Payment';
					$subject	= 'Notifications AtriumPremiere Website';
					
					//SEND EMAIL NOTIFICATION TO ADMIN
					$this->sendMailReservtoAdmin($idmember,$act,$emailfwd,$subject);
				}
				
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/login');
		}
	}
	
	public function sendMailReservtoAdmin($idmember,$act,$email,$subject) {
		$getSiteData 	= $this->query->getData('configsite','*',"");
		$datasite		= array_shift($getSiteData);
		
		$getDataMember 	= $this->query->getData('member','*',"WHERE id_member='$idmember'");
		$datamember		= array_shift($getDataMember);
		$name			= $datamember['nama'];
		
		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'ssl://mail.atriumcilacap.com',
		'smtp_port' => 465,
		'smtp_user' => 'noreply@atriumcilacap.com', // change it to yours
		'smtp_pass' => 'atrium@123', // change it to yours
		'mailtype'  => 'html',
		'charset'   => 'iso-8859-1'
		);
		
		//Email content
		$htmlContent = '';
		$htmlContent .= '
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8" />
			  <title>Verification Email - Atrium Premiere Hotel</title>
			  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
			  <style>
			  </style>
			</head>
			<body>
				<div class="bg" style="background: #222222; color: #FFF; width: 75%; padding: 30px; margin: 0 auto;">
					<div id="logo" style="padding: 40px; background: #1d1d1d;"><center><img src="'.base_url().'images/'.$datasite['logo'].'"></center></div>
					<div id="confirmation-message" style="padding: 30px; text-align: center;">
						<div class="ravis-title-t-2" style="text-align: center;">
							<div class="title" style="color: #FFFFFF; font-size: 34px;"><span>Hello,</span></div>
						</div>
						<div class="desc" style="color: #FFF; margin-top:20px;">
							Member dengan akun:<br>
							<span style="color:#d2bd7f;font-size:24px;font-weight:bold;">'.$name.'</span><br>
							telah melakukan<br>
							<span style="color:#d2bd7f;font-size:18px;">'.$act.'.</span><br><br><br>
							<a href="'.base_url().'panel" style="color: #d2bd7f;
							-webkit-transition: all 0.3s ease;
							-o-transition: all 0.3s ease;
							transition: all 0.3s ease;
							text-decoration:none;">Klik disini untuk melihat detail.</a>
						</div>
					</div>
				</div>
			</body>
			</html>
		';

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('noreply@atriumcilacap.com'); // change it to yours
		$this->email->to($email);// change it to yours
		$this->email->subject($subject);
		$this->email->set_mailtype("html");
		$this->email->message($htmlContent);
		
		if($this->email->send()) {
			echo 'Email sent.';
		} else {
			show_error($this->email->print_debugger());
		}
		// echo $htmlContent;
	}
	
	public function jatuhtempo() {
		$dateCreate = date_create(date('Y-m-d H:i:s'));
		$addtime 	= date_add($dateCreate, date_interval_create_from_date_string('6 hours'));
		echo date_format($addtime, 'Y-m-d H:i:s');
	}
	
	public function cekidnotif() {
		// SETUP NOTIFICATION
		$today 		= date("Ymd");
		$getID		= $this->query->getData('notifications','max(id_notif) AS last',"WHERE id_notif LIKE '$today%'");
		$fID		= array_shift($getID);
		$lastNoOrder 	= $fID['last'];
		if(isset($getID)){
			$lastNoUrut = substr($lastNoOrder, 8, 4);		
			$nextNoUrut = $lastNoUrut + 1;		
			$notif 		= $today.sprintf('%04s', $nextNoUrut);
		} else {		
			$notif		= $today."0001";	
		}	
		$pesanNotif		= " melakukan pembayaran";
		
		echo $notif;
	}
	
	public function inserttesti() {
		$nama		= trim(strip_tags(stripslashes($this->input->post('name',true))));
		$email		= trim(strip_tags(stripslashes($this->input->post('email',true))));
		$msg		= trim(strip_tags(stripslashes($this->input->post('msg',true))));
		$date		= date('Y-m-d');
		
		// SETUP NOTIFICATION
		$today 		= date("Ymd");
		$getID		= $this->query->getData('notifications','max(id_notif) AS last',"WHERE id_notif LIKE '$today%'");
		$fID		= array_shift($getID);
		$lastNoOrder 	= $fID['last'];
		if(isset($getID)){
			$lastNoUrut = substr($lastNoOrder, 8, 4);		
			$nextNoUrut = $lastNoUrut + 1;		
			$notif 		= $today.sprintf('%04s', $nextNoUrut);
		} else {		
			$notif		= $today."0001";	
		}	
		$pesanNotif		= $nama." memberikan testimonial";
		
		$insertTesti	= $this->query->insertData('testimonial',"nama,email,pesan,date,status,baca,id_notif","'$nama','$email','$msg','$date','0','N','$notif'");
		
		if (isset($insertTesti)) {
			// INSERT NOTIFICATION
			$insNotif	= $this->query->insertData('notifications',"id_notif,notifications,type,baca,date","'$notif','$pesanNotif','testimonial','N','".date("Y-m-d H:i:s")."'");
			
			$getMailSite 	= $this->query->getData('configsite','b.id_email,b.email',"a LEFT JOIN mail_site b on a.mail_site=b.id_email");
			$datamailsite	= array_shift($getMailSite);
			$getDataFwd		= $this->query->getData('mail_fwd','*',"WHERE id_email='".$datamailsite['id_email']."'");
			foreach($getDataFwd as $dataFwd){
				$emailfwd	= $dataFwd['email'];
				$act		= 'Testimonial';
				$sifat		= 'memberikan';
				$subject	= 'Notifications AtriumPremiere Website';
				
				// SEND EMAIL NOTIFICATION TO ADMIN
				$this->sendMailTestitoAdmin($nama,$act,$emailfwd,$subject,$sifat);
			}
			
			print json_encode(array('success'=>true,'total'=>1));
		}
	}
	
	public function insertsubs() {
		$email		= trim(strip_tags(stripslashes($this->input->post('email',true))));
		$date		= date('Y-m-d');
		
		$insertSubs	= $this->query->insertData('subscriber',"email,date_subs","'$email','$date'");
		
		if (isset($insertSubs)) {
			print json_encode(array('success'=>true,'total'=>1));
		}
	}
	
	public function sendMailActtoAdmin($name,$act,$email,$subject,$sifat) {
		$getSiteData 	= $this->query->getData('configsite','*',"");
		$datasite		= array_shift($getSiteData);
		
		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'ssl://mail.atriumcilacap.com',
		'smtp_port' => 465,
		'smtp_user' => 'noreply@atriumcilacap.com', // change it to yours
		'smtp_pass' => 'atrium@123', // change it to yours
		'mailtype'  => 'html',
		'charset'   => 'iso-8859-1'
		);
		
		//Email content
		$htmlContent = '';
		$htmlContent .= '
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8" />
			  <title>Verification Email - Atrium Premiere Hotel</title>
			  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
			  <style>
			  </style>
			</head>
			<body>
				<div class="bg" style="background: #222222; color: #FFF; width: 75%; padding: 30px; margin: 0 auto;">
					<div id="logo" style="padding: 40px; background: #1d1d1d;"><center><img src="'.base_url().'images/'.$datasite['logo'].'"></center></div>
					<div id="confirmation-message" style="padding: 30px; text-align: center;">
						<div class="ravis-title-t-2" style="text-align: center;">
							<div class="title" style="color: #FFFFFF; font-size: 34px;"><span>Hello,</span></div>
						</div>
						<div class="desc" style="color: #FFF; margin-top:20px;">
							Member dengan akun:<br>
							<span style="color:#d2bd7f;font-size:24px;font-weight:bold;">'.$name.'</span><br>
							telah '.$sifat.'<br>
							<span style="color:#d2bd7f;font-size:18px;">'.$act.'.</span><br><br><br>
							<a href="'.base_url().'panel" style="color: #d2bd7f;
							-webkit-transition: all 0.3s ease;
							-o-transition: all 0.3s ease;
							transition: all 0.3s ease;
							text-decoration:none;">Klik disini untuk melihat detail.</a>
						</div>
					</div>
				</div>
			</body>
			</html>
		';

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('noreply@atriumcilacap.com'); // change it to yours
		$this->email->to($email);// change it to yours
		$this->email->subject($subject);
		$this->email->set_mailtype("html");
		$this->email->message($htmlContent);
		
		if($this->email->send()) {
			echo 'Email sent.';
		} else {
			show_error($this->email->print_debugger());
		}
		// echo $htmlContent;
	}
	
	public function loadmoreevent() {
		$limit 	= $this->input->post('limit');
		$offset = $this->input->post('offset');
		// $limit 	= '3';
		// $offset = '3';
		
		$result  = $this->query->getData('event','*',"ORDER BY date DESC LIMIT $offset, $limit");
		
		if (!empty($result)) {
			foreach($result as $past) {
				$getImg 		= $this->query->getData('event_image','*',"WHERE id_event='".$past['id_event']."' order by id_img ASC LIMIT 1");
				$img			= array_shift($getImg);
				$title			= $past['title'];
				$sub			= $past['sub'];
				$hl				= substr($past['headline'], 0, 50) . '...';
				$date			= $this->formula->TanggalIndo($past['date']);
				$link = strtolower(str_replace(' ','-',$past['title']));
				
				@$hasil	.= '';
				$hasil	.= '
					<li class="item col-xs-6 col-md-4"  style="max-height: 270px; overflow: hidden;">
						<figure>
							<a href="'.base_url().'post/'.$link.'" class="more-details">
								<img src="'.base_url().'images/event/'.$img['img'].'" alt="'.$title.'"/>
							</a>
							<figcaption>
								<a href="'.base_url().'post/'.$link.'">
									<span class="title-box">
										<span class="title">'.$title.'</span>
										<span class="sub-title">'.$sub.'</span>
										<span class="date">'.$date.'</span>
									</span>
									<span class="desc">
										'.$hl.'
									</span>
								</a>
							</figcaption>
						</figure>
					</li>
				';
			}
			
			$data['view'] 	= $hasil;
			// $data['offset'] = $offset +3;
			// $data['limit'] 	= $limit;
			echo $hasil;
		}
	}
	
	public function rupiah($val){
		$result = "Rp " . number_format($val,0,',','.');
		return $result;
	}
	
	public function TanggalIndo($date){
		$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl   = substr($date, 8, 2);

		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;		
		return($result);
	}
	
	public function cekSendMailVer() {
		$name			= 'alamsyah';
		$email			= 'aspsyahputra@gmail.com';
		$pass			= 'pass';
		$getSiteData 	= $this->query->getData('configsite','*',"");
		$datasite		= array_shift($getSiteData);
		
		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'ssl://mail.atriumcilacap.com',
		'smtp_port' => 465,
		'smtp_user' => 'noreply@atriumcilacap.com', // change it to yours
		'smtp_pass' => 'atrium@123', // change it to yours
		'mailtype'  => 'html',
		'charset'   => 'iso-8859-1'
		);
		
		//Email content
		$htmlContent = '';
		$htmlContent .= '
			<!DOCTYPE html>
			<html>
			<head>
			  <meta charset="utf-8" />
			  <title>Verification Email - Atrium Premiere Hotel</title>
			  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
			  <style>
			  </style>
			</head>
			<body>
				<div class="bg" style="background: #222222; color: #FFF; width: 75%; padding: 30px; margin: 0 auto;">
					<div id="logo" style="padding: 40px; background: #1d1d1d;"><center><img src="'.base_url().'images/'.$datasite['logo'].'"></center></div>
					<div id="confirmation-message" style="padding: 30px; text-align: center;">
						<div class="ravis-title-t-2" style="text-align: center;">
							<div class="title" style="color: #FFFFFF; font-size: 34px;"><span>Dear, '.$name.'</span></div>
						</div>
						<div class="desc" style="color: #FFF; margin-top:20px;">
							In order to help maintain the security of your AtriumPremiere account, please verify your email address.<br><br>
							<a href="'.base_url().'verification/'.$email.'/'.$pass.'" style="color: #d2bd7f;
							-webkit-transition: all 0.3s ease;
							-o-transition: all 0.3s ease;
							transition: all 0.3s ease;
							text-decoration:none;">Click here to verify your email address. </a>
						</div>
						<div class="desc" style="margin-top:20px;color: #FFF;">
							Thanks for helping us maintain the security of your account.
						</div>
					</div>
				</div>
			</body>
			</html>
		';

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('noreply@atriumcilacap.com'); // change it to yours
		$this->email->to($email);// change it to yours
		$this->email->subject('AtriumPremiere email verification');
		$this->email->set_mailtype("html");
		$this->email->message($htmlContent);
		
		if($this->email->send()) {
			echo 'Email sent.';
		} else {
			show_error($this->email->print_debugger());
		}
		// echo $htmlContent;
	}
	
	public function cekimg2(){
		echo "<img src='".$this->cekimg()."'>";
	}
	public function cekimg($img){
		$sImage = 'http://13.67.54.108/raisa/v2/images/user/'.$img;
		// error_reporting(E_ALL);
		// ini_set('display_errors', '1');

		$filename = $sImage;


		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		if ($ext=="jpg" || $ext=="jpeg") {
		$image_s = imagecreatefromjpeg($filename);
		} else if ($ext=="png") {
		$image_s = imagecreatefrompng($filename);
		}

		$width = imagesx($image_s);
		$height = imagesy($image_s);


		$newwidth = 285;
		$newheight = 232;


		$image = imagecreatetruecolor($newwidth, $newheight);
		imagealphablending($image,true);
		imagecopyresampled($image,$image_s,0,0,0,0,$newwidth,$newheight,$width,$height);

		// create masking
		$mask = imagecreatetruecolor($width, $height);
		$mask = imagecreatetruecolor($newwidth, $newheight);



		$transparent = imagecolorallocate($mask, 255, 0, 0);
		imagecolortransparent($mask, $transparent);



		imagefilledellipse($mask, $newwidth/2, $newheight/2, $newwidth, $newheight, $transparent);



		$red = imagecolorallocate($mask, 0, 0, 0);
		imagecopy($image, $mask, 0, 0, 0, 0, $newwidth, $newheight);
		imagecolortransparent($image, $red);
		imagefill($image,0,0, $red);

		// output and free memory
		header('Content-type: image/png');
		imagepng($image);
		
		$filename = "0any_name"; 
		$directory = "http://13.67.54.108/raisa/v2/images/user/".$filename.".png";
		 
		// set the directory with 0755 permission
		chmod($directory,0777);
		 
		// this will save your image 
		imagepng($image, $directory, 0, NULL);
		// $save = "http://13.67.54.108/raisa/v2/images/user/cekcircle.png";chmod($save,0755);
		
		// imagedestroy($image);
		// imagedestroy($mask);
	}
	
	public function getdataprodam($id,$periode,$treg,$witel,$am){
		if(checkingsessionelop()) {
			
			$data['segmen']		= $id;
			$data['periode']	= $periode;
			$data['treg']		= $treg;
			$data['witel']		= $witel;
			$data['am']			= $am;
			
			$this->load->view('panel/produktivitas/dataproduktivitas2',$data);
		} else {
			redirect('/panel');
		}
	}
	
	public function getdataoverview($type,$periode,$treg,$witel){
		if(checkingsessionelop()) {
			
			$data['type']		= $type;
			$data['periode']	= $periode;
			$data['treg']		= $treg;
			$data['witel']		= $witel;
			
			$this->load->view('panel/produktivitas/dataproduktivitasov',$data);
		} else {
			redirect('/panel');
		}
	}
	
	public function getdataprodamkuad($periode,$treg,$witel,$am){
		if(checkingsessionelop()) {
			
			// $data['segmen']		= $id;
			$data['periode']	= $periode;
			$data['treg']		= $treg;
			$data['witel']		= $witel;
			$data['am']			= $am;
			
			$this->load->view('panel/produktivitas/dataproduktivitaskuad',$data);
		} else {
			redirect('/panel');
		}
	}
}
