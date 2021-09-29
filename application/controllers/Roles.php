<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends CI_Controller {

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
		$this->load->model('roles_handler');
		
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
				'nama_role'		=> true,
				'desc_role'		=> true,
				'update_by'		=> true,
				'last_update'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			// $jsonfile	= base_url().'roles/data';
			$jsonfile	= $this->roles_handler->data();

			$this->datatable->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function insert(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt');
			$role		= trim(strip_tags(stripslashes($this->input->post('role',true))));
			$desc_role	= trim(strip_tags(stripslashes($this->input->post('role_description',true))));
			$menu		= $_POST['menu'];
			$query = $this->query->getData('role','max(id_role)+1 as id_role','');
			$getID = array_shift($query);
			if ($getID['id_role']=='') {
				$id = '1';
			} else {
				$id = $getID['id_role'];
			}
			
			$rows = $this->query->insertData('role', "id_role,nama_role,desc_role", "'$id','$role','$desc_role'");
			$id			= $this->db->insert_id();
			$url 		= "Manage role";
			$activity 	= "INSERT";
			$log = $this->query->insertlog($activity,$url,$id);
			if($rows) {
				$jmlMod = count($menu);
				for($x = 0 ;$x < $jmlMod; $x++){
					$p_fitur = $_POST['fitur'][$menu[$x]];
					$fitur = implode(",", $p_fitur);
					$insertFac = $this->query->insertData('role_menu','id_menu,id_role,akses',"'".$menu[$x]."','$id','$fitur'");
				}
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	

	public function modal(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$datarole			= $this->query->getData('role','*',"WHERE id_role='".$id."' ORDER BY id_role DESC");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($datarole as $row) {
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
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_idroles',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('ed_roles',true))));
			$desc_role			= trim(strip_tags(stripslashes($this->input->post('ed_role_description',true))));
			$menu			= $_POST['ed_menu'];
				
			$updaterole 	= $this->query->updateData('role',"nama_role='$name',desc_role='$desc_role'","WHERE id_role='$id'");
			$url 			= "Manage Role";
			$activity 		= "UPDATE";
			if($updaterole) {
				$deletefirst 	= $this->query->deleteData('role_menu','id_role',$id);
				
				$jmlMod 		= count($menu);
				for($x = 0 ;$x < $jmlMod; $x++){
					$p_fitur = $_POST['ed_fitur'][$menu[$x]];
					$fitur = implode(",", $p_fitur);
					$insertFac = $this->query->insertData('role_menu','id_menu,id_role,akses',"'".$menu[$x]."','$id','$fitur'");
				}
				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	

	public function delete(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt');
			$cond		= trim(strip_tags(stripslashes($this->input->post('iddelroles',true))));
			
			$rows = $this->query->deleteData('role','id_role',$cond);
			$rows = $this->query->deleteData('role_menu','id_role',$cond);
			$url 		= "Manage Role";
			$activity 	= "DELETE";
			
			$ins_log = $this->query->insertData('data_log', "userid,date_time,activity,menu,data,ip", "'$userid','$datelog','$activity','$url',	'$cond','$ipaddr'");
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function getdataEksMod($id){
		if(checkingsessionpwt()){
			
			$dataroleAll		= $this->query->getData('menu','*',"where parent='0' ORDER BY sort ASC");
			
			// header('Content-type: application/json; charset=UTF-8');
			
			
				echo '
					<style>
					.checkbox input[type=checkbox], .checkbox-inline input[type=checkbox] {z-index:2;}
					</style>
					<div class="form-group row">
					<label for="menu_fitur" class="col-sm-2 col-form-label">Module</label>
					<div class="col-sm-12" style="padding-top:10px;">
						<table class="smalltabl nowrap table" width=100%>
						<thead class="bg-gray-dark">
							<th>
								<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
									<input id="ed_selectAll" type="checkbox"> <b>Menu Name</b>
									<span></span>
								</label>
							</th>
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
									<input value='".$data['id_menu']."' id='ed_checkbox".$data['id_menu']."' type='checkbox' name='ed_menu[]' ".$ceked.">
									".$data['menu']."
									<span></span>
								</label>
							</td>
							<td class='text-right kt-checkbox-inline'>
						";
							$data_fitur = explode_fitur($data['fitur']);
							for($x=0;$x<count($data_fitur);$x++)
							{
								$dataEksFitur = explode_fitur($dataEksrole['akses']);
								if(in_array($data_fitur[$x],$dataEksFitur)){ $ceked_fitur[$x] = 'checked'; } else {   $ceked_fitur[$x] = ''; }
								
								$CekSub	= $this->query->getNumRows('menu','*',"WHERE parent='".$data['id_menu']."'")->num_rows();
								// if ($CekSub>0) { echo ''; } else {
								echo "
									<label class='kt-checkbox kt-checkbox--bold kt-checkbox--brand'>
										<input value='".$data_fitur[$x]."' id='ed_checkbox".$data['id_menu'].$data_fitur[$x]."' type='checkbox' name='ed_fitur[".$data['id_menu']."][]' ".$ceked_fitur[$x].">
										".$data_fitur[$x]."
										<span></span>
									</label>
								";
								echo "
									<script>
										$('#ed_checkbox".$data['id_menu']."').change(function() {
											if($('#ed_checkbox".$data['id_menu']."').prop( 'checked' )){
												$('#ed_checkbox".$data['id_menu'].$data_fitur[$x]."').prop('checked', true);	
											}else{
												$('#ed_checkbox".$data['id_menu'].$data_fitur[$x]."').prop('checked', false);	
											}
										});
									</script>";
								// }
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
								</script>';
							
							// GET SUB MENU
							$getSubMenu 	= $this->query->getData('menu','*',"WHERE parent='".$data['id_menu']."' order by sort asc");
							foreach ($getSubMenu as $data) {
								$getdataEksrole	= $this->query->getData('role_menu','*',"WHERE id_role='$id' and id_menu='".$data['id_menu']."'");
								$dataEksrole		= array_shift($getdataEksrole);
								if ($dataEksrole!='') { $ceked = "checked"; } else { $ceked = ""; }
								echo "
								<tr style='background: rgba(0,0,0,.04)!important;'>
									<td style='padding-left: 30px!important;'>
										<label class='kt-checkbox kt-checkbox--bold kt-checkbox--brand'>
											<input value='".$data['id_menu']."' id='ed_checkbox".$data['id_menu']."' type='checkbox' name='ed_menu[]' ".$ceked.">
											".$data['menu']."
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
												<input value='".$data_fitur[$x]."' id='ed_checkbox".$data['id_menu'].$data_fitur[$x]."' type='checkbox' name='ed_fitur[".$data['id_menu']."][]' ".$ceked_fitur[$x]."> ".$data_fitur[$x]."
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
										</script>';
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
}
