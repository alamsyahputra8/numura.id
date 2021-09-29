<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

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
		$this->load->model('user_handler');
		
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

	public function getdata(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'picture'		=> true,
				'name'			=> true,
				'username'		=> true,
				'email'			=> true,
				'role'			=> true,
				'ldap'			=> true,
				'phone'			=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;

			// $jsonfile	= base_url().'user/data';
			$jsonfile	= $this->user_handler->data();

			$this->datatable->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function insert(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$iduser		= trim(strip_tags(stripslashes($this->input->post('id_user',true))));
			$ldap		= trim(strip_tags(stripslashes($this->input->post('ldap',true))));
			$name		= trim(strip_tags(stripslashes($this->input->post('name',true))));
			$email		= trim(strip_tags(stripslashes($this->input->post('email',true))));
			$username	= trim(strip_tags(stripslashes($this->input->post('user',true))));
			$phone		= trim(strip_tags(stripslashes($this->input->post('phone',true))));
			$role		= trim(strip_tags(stripslashes($this->input->post('role',true))));

			$cekDataU 	= $this->query->getNumRowsbyQ("SELECT * FROM user where username='$username'")->num_rows();
			$cekDataE 	= $this->query->getNumRowsbyQ("SELECT * FROM user where email='$email'")->num_rows();

			if ($cekDataU>0 or $cekDataE>0) {
				echo "";
			} else {
				if($ldap==1) {
					$fileName 	= $username.'.jpg';
					$password 	= '';
				} else {
					$password	= md5($_POST['pass']);
					$fileName 	= str_replace(' ','_',time().$_FILES['pict']['name']);
				}
				
				/*$divisi		= trim(strip_tags(stripslashes($this->input->post('data_divisi',true))));
				$segmen		= trim(strip_tags(stripslashes($this->input->post('data_segmen',true))));
				$treg		= trim(strip_tags(stripslashes($this->input->post('data_treg',true))));
				$witel		= trim(strip_tags(stripslashes($this->input->post('data_witel',true))));
				$am			= trim(strip_tags(stripslashes($this->input->post('data_am',true))));*/

				$divisi		= 'all';
				$segmen		= 'all';
				$treg		= 'all';
				$witel		= 'all';
				$am			= 'all';
				
				if ($ldap!=1) {
					$config['upload_path'] = './images/user/'; //buat folder dengan nama assets di root folder
					$config['file_name'] = $fileName;
					$config['allowed_types'] = 'gif|jpg|png|jpeg';
					 
					$this->load->library('upload');
					$this->upload->initialize($config);
					 
					if(! $this->upload->do_upload('pict') )
					$this->upload->display_errors();
						 
					$media = $this->upload->data('pict');
				}
				
				$rows 		= $this->query->insertData('user', "name,username,email,password,picture,id_role,ldap,phone", "'$name','$username','$email','$password','$fileName','$role','$ldap','$phone'");
				$id			= $this->db->insert_id();
				$url 		= "Manage User";
				$activity 	= "INSERT";
				if($rows) {
					$gRN 		= $this->query->getDatabyQ("SELECT nama_role from role where id_role='$role'");
					$RN 		= array_shift($gRN);
					$rolename 	= $RN['nama_role'];

					// $gCI 		= $this->query->getDatabyQ("SELECT clientid from auth where authid='$clientid'");
					// $CI 		= array_shift($gCI);
					// $CIstr 		= $CI['clientid'];
					// $this->sendMail($name,$username,$password,$email,$rolename,$CIstr,$ldap);

					$org = $this->query->insertData('user_organization', "userid,akses_divisi,akses_segmen,akses_treg,akses_witel,akses_am", "'$iduser','$divisi','$segmen','$treg','$witel','$am'");
					$log = $this->query->insertlog($activity,$url,$id);
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function modal(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataUsr			= $this->query->getData('user a LEFT JOIN user_organization b ON a.userid = b.userid','a.userid,a.username,a.name,a.password,a.picture,a.id_role,a.email,a.phone,b.akses_divisi,b.akses_segmen,b.akses_witel,b.akses_treg,b.akses_am,a.ldap',"WHERE a.userid='".$id."' ORDER BY a.userid DESC");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataUsr as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function update(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$cekinglogo		= @$_FILES['upl']['name'];
			$cekingpass		= $_POST['ed_pass'];
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_iduser',true))));
			$ldap			= trim(strip_tags(stripslashes($this->input->post('ed_ldap',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('ed_name',true))));
			$email			= trim(strip_tags(stripslashes($this->input->post('ed_email',true))));
			$user			= trim(strip_tags(stripslashes($this->input->post('ed_user',true))));
			$phone			= trim(strip_tags(stripslashes($this->input->post('ed_phone',true))));
			$role			= trim(strip_tags(stripslashes($this->input->post('ed_role',true))));
			
			$cekDataU 	= $this->query->getNumRowsbyQ("SELECT * FROM user where username='$user'")->num_rows();
			$cekDataE 	= $this->query->getNumRowsbyQ("SELECT * FROM user where email='$email'")->num_rows();

			/*$divisi			= trim(strip_tags(stripslashes($this->input->post('ed_data_divisi',true))));
			$segmen			= trim(strip_tags(stripslashes($this->input->post('ed_data_segmen',true))));
			$witel			= trim(strip_tags(stripslashes($this->input->post('ed_data_witel',true))));
			$treg			= trim(strip_tags(stripslashes($this->input->post('ed_data_treg',true))));
			$am				= trim(strip_tags(stripslashes($this->input->post('ed_data_am',true))));*/

			$divisi		= 'all';
			$segmen		= 'all';
			$treg		= 'all';
			$witel		= 'all';
			$am			= 'all';
			
			$url 		= "Manage User";
			$activity 	= "UPDATE";
			
			if ($cekingpass!='') {
				$pass			= md5($_POST['ed_pass']);
				$upPass = ", password='$pass'";
			} else {
				$upPass = '';
			}
			
			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('user','picture','WHERE userid='.$id.'');
				foreach ($coba as $dataex) {
					$dataexis = 'images/user/'.$dataex['picture'];
				}
				unlink($dataexis);
			
				$fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/user/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('upl');
				
				$rows = $this->query->updateData('user',"name='$name', email='$email', username='$user', picture='$fileName', id_role='$role', ldap='$ldap', phone='$phone' $upPass","WHERE userid='".$id."'");
				
				if($rows) {
					$deletefirst 	= $this->query->deleteData('user_organization','userid',$id);
					$insertNew 		= $this->query->insertData('user_organization', "userid,akses_divisi,akses_segmen,akses_treg,akses_witel,akses_am", "'$id','$divisi','$segmen','$treg','$witel','$am'");
					
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}	
			} else {
				$rows = $this->query->updateData('user',"name='$name', email='$email', username='$user', id_role='$role', ldap='$ldap', phone='$phone' $upPass","WHERE userid='$id'");
				if($rows) {
					$deletefirst 	= $this->query->deleteData('user_organization','userid',$id);
					$insertNew 		= $this->query->insertData('user_organization', "userid,akses_divisi,akses_segmen,akses_treg,akses_witel,akses_am", "'$id','$divisi','$segmen','$treg','$witel','$am'");
				
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
			}
			$log = $this->query->insertlog($activity,$url,$id);
		} else {
			redirect('/panel');
		}
	}	

	public function delete(){
		if(checkingsessionpwt()){
			$url 		= "Manage User";
			$activity 	= "DELETE";
			
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$coba = $this->query->getData('user','picture',"WHERE userid='".$cond."'");
			
			foreach ($coba as $dataex) {
				$dataexis = 'images/user/'.$dataex['picture'];
				@unlink($dataexis);
			}
			
			$rows = $this->query->deleteData('user','userid',$cond);
			
			if(isset($rows)) {
				$rows = $this->query->deleteData('user_organization','userid',$cond);
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'rows'=>$rows, 'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function getDataRole($id){
		if(checkingsessionpwt()){
			
			$dataroleAll		= $this->query->getData('menu','*',"WHERE parent='0' ORDER BY sort ASC");
			
			// header('Content-type: application/json; charset=UTF-8');
			
			
				echo '
					<div class="">
					<div class="col-sm-12 table-responsive" style="padding-top:10px;">
						<table class="smalltable nowrap table" width=100%>
						<thead class="bg-gray-dark">
							<th><b>Menu Name</b></th>
							<th class="text-right"><b>Fitur</b></th>
						</thead>
						<tbody>';
						foreach($dataroleAll as $data) {
						$getdataEksrole	= $this->query->getData('role_menu','*',"WHERE id_role='$id' and id_menu='".$data['id_menu']."'");
						$dataEksrole		= array_shift($getdataEksrole);
						if ($dataEksrole!='') { $ceked = "checked"; } else { $ceked = ""; }
						echo "
						<tr>
							<td>
								<label class='kt-checkbox kt-checkbox--bold kt-checkbox--brand'>
									<input onclick='return false;' readonly value='".$data['id_menu']."' id='ed_checkbox".$data['id_menu']."' type='checkbox' name='ed_menu[]' ".$ceked."> ".$data['menu']."
									<span></span>
								</label>
							</td>
							<td class='text-right'>
						";
							$data_fitur = explode_fitur($data['fitur']);
							for($x=0;$x<count($data_fitur);$x++)
							{
								$dataEksFitur = explode_fitur($dataEksrole['akses']);
								if(in_array($data_fitur[$x],$dataEksFitur)){ $ceked_fitur[$x] = 'checked'; } else {   $ceked_fitur[$x] = ''; }
								
								$CekSub	= $this->query->getNumRows('menu','*',"WHERE parent='".$data['id_menu']."'")->num_rows();
								if ($CekSub>0) { echo ''; } else {
									echo "
										<label class='kt-checkbox kt-checkbox--bold kt-checkbox--brand'>
											<input onclick='return false;' readonly value='".$data_fitur[$x]."' id='ed_checkbox".$data['id_menu'].$data_fitur[$x]."' type='checkbox' name='ed_fitur[".$data['id_menu']."][]' ".$ceked_fitur[$x]."> ".$data_fitur[$x]."
											<span></span>
										</label>
									";
								}
								
								echo "<script>
										$('#ed_checkbox".$data['id_menu']."').change(function() {
											if($('#ed_checkbox".$data['id_menu']."').prop( 'checked' )){
												$('#ed_checkbox".$data['id_menu'].$data_fitur[$x]."').prop('checked', true);	
											}else{
												$('#ed_checkbox".$data['id_menu'].$data_fitur[$x]."').prop('checked', false);	
											}
										});
									</script>";
							}
							echo '<script>
									$("#ed_selectAll").change(function() {
										if($("#ed_selectAll").prop( "checked" )){
											$("#ed_checkbox'.$data['id_menu'].'").prop("checked", true);
											$( "#ed_checkbox'.$data['id_menu'].'" ).trigger( "change" );
										}else{
											$("#ed_checkbox'.$data['id_menu'].'").prop("checked", false);
											$( "#ed_checkbox'.$data['id_menu'].'" ).trigger( "change" );
										}
									});
								</script></td></tr>';
							
							// GET SUBMENU
							$getSubMenu 	= $this->query->getData('menu','*',"WHERE parent='".$data['id_menu']."' order by sort asc");
							foreach ($getSubMenu as $data) {
								$getdataEksrole	= $this->query->getData('role_menu','*',"WHERE id_role='$id' and id_menu='".$data['id_menu']."'");
								$dataEksrole		= array_shift($getdataEksrole);
								if ($dataEksrole!='') { $ceked = "checked"; } else { $ceked = ""; }
								echo "
								<tr style='background: rgba(0,0,0,.04)!important;'>
									<td style='padding-left: 30px!important;'>
										<label class='kt-checkbox kt-checkbox--bold kt-checkbox--brand'>
											<input onclick='return false;' readonly value='".$data['id_menu']."' id='ed_checkbox".$data['id_menu']."' type='checkbox' name='ed_menu[]' ".$ceked."> ".$data['menu']."
											<span></span>
										</label>
									</td>
									<td class='text-right'>
								";
									$data_fitur = explode_fitur($data['fitur']);
									for($x=0;$x<count($data_fitur);$x++)
									{
										$dataEksFitur = explode_fitur($dataEksrole['akses']);
										if(in_array($data_fitur[$x],$dataEksFitur)){ $ceked_fitur[$x] = 'checked'; } else {   $ceked_fitur[$x] = ''; }
										echo "
											<label class='kt-checkbox kt-checkbox--bold kt-checkbox--brand'>
												<input onclick='return false;' readonly value='".$data_fitur[$x]."' id='ed_checkbox".$data['id_menu'].$data_fitur[$x]."' type='checkbox' name='ed_fitur[".$data['id_menu']."][]' ".$ceked_fitur[$x].">
												".$data_fitur[$x]."
												<span></span>
											</label>
											<script>
												$('#ed_checkbox".$data['id_menu']."').change(function() {
													if($('#ed_checkbox".$data['id_menu']."').prop( 'checked' )){
														$('#ed_checkbox".$data['id_menu'].$data_fitur[$x]."').prop('checked', true);	
													}else{
														$('#ed_checkbox".$data['id_menu'].$data_fitur[$x]."').prop('checked', false);	
													}
												});
											</script>";
									}
									echo '<script>
											$("#ed_selectAll").change(function() {
												if($("#ed_selectAll").prop( "checked" )){
													$("#ed_checkbox'.$data['id_menu'].'").prop("checked", true);
													$( "#ed_checkbox'.$data['id_menu'].'" ).trigger( "change" );
												}else{
													$("#ed_checkbox'.$data['id_menu'].'").prop("checked", false);
													$( "#ed_checkbox'.$data['id_menu'].'" ).trigger( "change" );
												}
											});
										</script></td></tr>';
							}
						}
						echo '
						</tbody>
						</table>													
					</div>
				</div>						
				';
			
		} else {
			redirect('/panel');
		}
	}

	public function getProfile(){
		if(checkingsessionpwt()){

			$id			= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$getdatalop = $this->query->getData('user','*',"WHERE userid ='".$id."'");

			header('Content-type: application/json; charset=UTF-8');			
			if (isset($id) && !empty($id)) {
				foreach($getdatalop as $data) {
					$row = array(
						'userid'	=> $data['userid'],
						'username'	=> $data['username'],
						'name'		=> $data['name'],
						'justpict'	=> $data['picture'],
						'picture'	=> base_url()."images/user/".$data['picture'],
						'email'		=> $data['email']
						);
					$json = $row;
				}
				//echo var_dump($json);
				echo json_encode($json);
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function updateProfile(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$cekinglogo		= $_FILES['upl']['name'];
			$cekingpass		= $_POST['password'];
			$id				= trim(strip_tags(stripslashes($this->input->post('userid',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('name',true))));
			$email			= trim(strip_tags(stripslashes($this->input->post('email',true))));
			$user			= trim(strip_tags(stripslashes($this->input->post('username',true))));
			
			$url 		= "User Profile";
			$activity 	= "UPDATE";
			
			if ($cekingpass!='') {
				$pass	= md5($_POST['password']);
				$upPass = ", password='$pass'";
			} else {
				$upPass = '';
			}
			
			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('user','picture','WHERE userid='.$id.'');
				foreach ($coba as $dataex) {
					$dataexis = 'images/user/'.$dataex['picture'];
				}
				unlink($dataexis);
			
				$fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/user/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('upl');
				
				$rows = $this->query->updateData('user',"name='$name', email='$email', username='$user', picture='$fileName' $upPass","WHERE userid='".$id."'");
				
				
			} else {
				$rows = $this->query->updateData('user',"name='$name', email='$email', username='$user' $upPass","WHERE userid='$id'");
				
			}
			if($rows) {
				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}		

	public function savepassword(){
		$username	= trim(strip_tags(stripslashes($this->input->post('username',true))));
		$pass		= $this->input->post('password',true);
		$token		= trim(strip_tags(stripslashes($this->input->post('token',true))));
		$pbs		= trim(strip_tags(stripslashes($this->input->post('pbs',true))));
		$cektoken 	= md5($username.$pbs);

		if ($token==$cektoken) {
			$gID 	= $this->query->getDatabyQ("SELECT * FROM user where username='$username' and password='$pbs'");
			$dID 	= array_shift($gID);
			$id 	= $dID['userid'];
			$email 	= $dID['email'];
			$name 	= $dID['name'];

			$newpass= md5($pass);

			$rows = $this->query->updateData('user',"password='$newpass'","WHERE userid='$id'");
			if($rows) {
				$this->sendMailSetPass($name,$username,$newpass,$email);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			echo "";
		}
	}

	public function getprofileLDAP(){
		$username 	= trim(strip_tags(stripslashes($this->input->post('username',true))));
		$url2 		= 'https://auth.telkom.co.id/api/call/'.$username.'';
		$JSON2 		= file_get_contents($url2);
		
		$gdata2 	= json_decode(json_encode($JSON2), True);
		$data2		= json_decode($gdata2);

		$user		= $data2->username;
		$name		= $data2->name;
		$email		= $data2->email;
		
		if (!empty($user)) {
			// $url 		= 'https://myworkbook.telkom.co.id/mwb/api/index.php?r=api/photo&nik='.$user;
			$url 		= 'https://pwb.telkom.co.id/index.php?r=pwbPhoto/profilePhoto&nik='.$user;
			/* Extract the filename */
			$gfilename 	= substr($url, strrpos($url, '/') + 1);
			$filename 	= str_replace('profilePhoto&nik=','',$gfilename);
			/* Save file wherever you want */
			@file_put_contents('images/user/'.$filename.'.jpg', file_get_contents($url));

			$ret = array(
				"name" 		=> $name,
				"username" 	=> $user,
				"email" 	=> $email
			);
		} else {
			echo "";
		}
		
		echo json_encode($ret);
	}

	public function sendMail($name,$username,$pass,$email,$role,$clientid,$ldap) {
		$subject 	= 'ISR-M2M Application';

		$getSiteData 	= $this->query->getData('configsite','*',"");
		$datasite		= array_shift($getSiteData);
		$token 			= md5($username.$pass);
		$pbs 			= $pass;
		
		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'smtp.hostinger.co.id',
		'smtp_port' => 587,
		'smtp_user' => 'isrm2m@parwatha.com', // change it to yours
		'smtp_pass' => 'b1sm1llah', // change it to yours
		'mailtype'  => 'html',
		'charset'   => 'iso-8859-1'
		);
		
		if ($ldap!=1) {
			$fornonldap = '
				<div style="border-bottom: 1px dashed #efefef; padding-bottom:10px;">
					<div style="text-align: center;">Please set your new password <a href="'.base_url().'setpassword?token='.$token.'&pbs='.$pbs.'" target="_blank" style="color: #5d78ff;">here</a>:</div>
					<center>
						<a href="'.base_url().'setpassword?token='.$token.'&pbs='.$pbs.'" target="_blank">
							<button style="cursor: pointer; box-shadow:0px 4px 16px 0px rgba(93, 120, 255, 0.15); color: #fff; background-color: #5d78ff; border-color: #5d78ff; border-radius: 3px; padding: 8px 15px; margin-top: 10px; font-size: 16px; font-weight: bold; margin-bottom: 5px;">
								Set Password
							</button>
						</a>
					</center>
					<div><center>Thanks!</center></div>
				</div>
			';
		} else {
			$fornonldap = '';
		}

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
							<div class="title" style="color: #1e1e1e; font-size: 24px;"><span>Dear, '.$name.'</span></div>
						</div>
						<div class="desc" style="color: #1e1e1e; margin-top:20px; font-siz: 14px;">
							<div style="border-bottom: 1px dashed #efefef; padding-bottom: 10px;">
								Congratulations! You have been registered as M2M-ISR application User. Please find below your account information:
							</div>
							
							<div style="margin-top: 20px; border-bottom: 1px dashed #efefef; margin-bottom: 10px; padding-bottom: 10px;">
								<table style="font-size: 14px;">
								 <tbody>
								 	<tr>
								 		<td>Username</td>
								 		<td>:</td>
								 		<td><b>'.$username.'</b></td>
								 	</tr>
								 	<tr>
								 		<td>Email</td>
								 		<td>:</td>
								 		<td><b>'.$email.'</b></td>
								 	</tr>
								 	<tr>
								 		<td>Role</td>
								 		<td>:</td>
								 		<td><b>'.$role.'</b></td>
								 	</tr>
								 	<tr>
								 		<td>Client ID</td>
								 		<td>:</td>
								 		<td><b>'.$clientid.'</b></td>
								 	</tr>
								 </tbody>
								</table>
							</div>

							'.$fornonldap.'

							<div>
								<div style="padding-top: 20px; padding-bottom: 10px;">Best Regards,</div>

								<div>
									<b style="font-size: 16px; padding-bottom:0px; margin-bottom: 0px;">
										ISR-<span style="color: #b63127;">M2M</style>
									</b>
								</div>
								<div><b>Solid, Speed, Smart!<b></div>
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
		$this->email->to($email);// change it to yours
		$this->email->subject($subject);
		$this->email->set_mailtype("html");
		$this->email->message($htmlContent);
		
		if($this->email->send()) {
			echo '';
		} else {
			show_error($this->email->print_debugger());
		}
		// echo $htmlContent;
	}

	public function sendMailSetPass($name,$username,$pass,$email) {
		$subject 	= 'ISR-M2M Application - Set Password Success';

		$getSiteData 	= $this->query->getData('configsite','*',"");
		$datasite		= array_shift($getSiteData);
		$token 			= md5($username.$pass);
		$pbs 			= $pass;
		
		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'smtp.hostinger.co.id',
		'smtp_port' => 587,
		'smtp_user' => 'isrm2m@parwatha.com', // change it to yours
		'smtp_pass' => 'b1sm1llah', // change it to yours
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
							<div class="title" style="color: #1e1e1e; font-size: 24px;"><span>Dear, '.$name.'</span></div>
						</div>
						<div class="desc" style="color: #1e1e1e; margin-top:20px; font-siz: 14px;">
							<div style="border-bottom: 1px dashed #efefef; padding-bottom: 10px;">
								The password for your <b>ISR-<span style="color: #b63127;">M2M</span></b> application user (<b>'.$username.'</b>) has been successfully reset.
								If you did not make this change or you believe an unauthorised person has accessed your account, click <a href="'.base_url().'setpassword?token='.$token.'&pbs='.$pbs.'" target="_blank" style="color: #5d78ff;">here</a> to reset your password without delay. 
								<br><br>

								If you need additional help, please contact Admin.<br><br>
							</div>
							
							<div>
								<div style="padding-top: 20px; padding-bottom: 10px;">Sincerely,</div>
								<div>
									<b style="font-size: 16px; padding-bottom:0px; margin-bottom: 0px;">
										ISR-<span style="color: #b63127;">M2M</style>
									</b>
								</div>
								<div><b>Solid, Speed, Smart!<b></div>
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
		$this->email->to($email);// change it to yours
		$this->email->subject($subject);
		$this->email->set_mailtype("html");
		$this->email->message($htmlContent);
		
		if($this->email->send()) {
			echo '';
		} else {
			show_error($this->email->print_debugger());
		}
		// echo $htmlContent;
	}
}
