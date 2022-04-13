<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Core extends CI_Controller {

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
		
		ini_set('max_execution_time', 123456789);
		ini_set("memory_limit","2256M");
			
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
			$this->load->view('panel/dashboard');
		} else {
			redirect('/panel');
		}
	}

	public function deletemen(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('menu','id_menu',$cond);
			$userid		= $userdata['userid'];
			$datelog	= date('Y-m-d H:i:s');
			$ipaddr		= $_SERVER['REMOTE_ADDR'];
			$url 		= "Manage Menu";
			$activity 	= "DELETE";
			if(isset($rows)) {
				$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$cond','$ipaddr'");
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}
	
	public function getdataEksImg($id) {
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$dataEksImg		= $this->query->getData('room_image','*',"WHERE id_room='$id'");
			
			foreach($dataEksImg as $data) {
				echo '
					<div class="col-sm-6 col-md-4">
						<div class="bgdeletepict">
							<a data-toggle="modal" data-target="#deleteimg" data-id="'.$data['id_img'].'" class="btn btn-xs btn-danger btndeleteimg"><i data-toggle="tooltip" title="Delete Images" class="glyphicon glyphicon-trash"></i></a>
						</div>
						<a class="lightbox">
							<img src="'.base_url().'images/rooms/'.$data['img'].'" alt="Images">
						</a>
					</div>
				';
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function deleteimages(){
		if(checkingsessionpwt()){
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$coba = $this->query->getData('room_image','img',"WHERE id_img='".$cond."'");
			
			foreach ($coba as $dataex) {
				$dataexis = 'images/rooms/'.$dataex['img'];
				unlink($dataexis);
			}
			
			$rows = $this->query->deleteData('room_image','id_img',$cond);
			
			if(isset($rows)) {
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}
	
	public function modalimg(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataRoom			= $this->query->getData('room_image','*',"WHERE id_img='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataRoom as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function insertnewimages($id){
		if(checkingsessionpwt()){
			
			$jmlImg = count($_FILES['newimages']['name']);
			for($y = 0; $y < $jmlImg; $y++){
				$direktori[$y] 			= './images/rooms/';
				$lokasi_file[$y]    	= $_FILES['newimages']['tmp_name'][$y];
				$tipe_file[$y]      	= $_FILES['newimages']['type'][$y];
				$nama_file[$y]      	= $_FILES['newimages']['name'][$y];
				$acak[$y]           	= rand(000000,999999);
				$nama_file_unik[$y] 	= str_replace(' ','_',time().''.$nama_file[$y]);
				
				// echo 'filenya'.$nama_file[$y];
				
				$allowed = array('png','jpg','gif','jpeg','bmp');
				
				$extension = pathinfo($nama_file[$y], PATHINFO_EXTENSION);

				if(!in_array(strtolower($extension), $allowed)){
					echo '{"status":"error"}';
					exit;
				}
				
				if (!empty($lokasi_file[$y])){
					//direktori gambar
					$vfile_upload[$y] = $direktori[$y] . $nama_file_unik[$y];

					//Simpan gambar dalam ukuran sebenarnya
					move_uploaded_file($lokasi_file[$y], $vfile_upload[$y]);
					
					// INSERT IMAGE Room
					$insertImg 	= $this->query->insertData('room_image', "id_room,img", "'$id','$nama_file_unik[$y]'");
				}
			}
			echo "sukses";
		}else{
            redirect('/login');
        }
	}
	
	public function upavail() {
		if(checkingsessionpwt()){
			$roomid	= $_POST['roomid'];
			$jmlRa 		= count($roomid);
			for($x = 0 ;$x < $jmlRa; $x++){
				$updateRoom 	= $this->query->updateData('room',"availroom='0'","WHERE id_room='".$roomid[$x]."'");
			}
			print json_encode(array('success'=>true,'total'=>1));
		} else {
			redirect('/panel');
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
	public function getDatSeg(){
		if(checkingsessionpwt()){
			$modify 		= str_replace(",","','",$_GET['id']);
			$getvaldiv		= $_GET['id'];
			if ($getvaldiv=='' or $getvaldiv=='all' or $getvaldiv=='null') {
				$valdiv		= "";
			} else {
				$valdiv		= "WHERE id_divisi IN ('".$modify."')";
			}
			
			$dataDivisi		= $this->query->getData('segmen','id_segmen,nama_segmen',"$valdiv GROUP BY id_segmen,nama_segmen ORDER BY nama_segmen ASC");
			echo '<div class="col-sm-6">';
			echo '<select multiple class="multipleselect2 form-control" id="segmen" name="segmen">';
			foreach($dataDivisi as $data) {
				echo "<option value='".$data['id_segmen']."'>".$data['nama_segmen']."</option>";
			}
			echo '</select>';
			echo "<script>
					$(document).ready(function() {
						$('.multipleselect2').select2();
					});
					
					$('#segmen').change(function() {
					var id_segmen = $(this).val();
						if(id_segmen == ''){
							$('#showtreg').html('');
						}else{
							$.ajax({
								url: '".base_url()."core/getDataT',
								type: 'GET',
								data: 'id='+id_segmen
							}).done(function(data){
								// console.log(data);
								$('#showtreg').html(data);	
								$('#data_segmen').val(id_segmen);
							})			
						}
					});
					</script>";
			echo '</div>';
			echo '
			<div class="col-sm-4">
				<div class="checkbox checkbox-info checkbox-circle">
					<input id="selectallS" type="checkbox">
					<label for="selectallS"><b>Select All Segmen</b></label>
				</div>
			</div>
			<script>
			$("#selectallS").click(function(){
				if($("#selectallS").is(":checked") ){
					$("#segmen").attr("disabled","disabled");
					$("#data_segmen").val("all");
					$("#segmen").append($("<option>", {
						value: "all",
						text: "ALL SEGMEN"
					}));
					$("#segmen").val("all");
					$("#segmen").select2();
					$("#segmen").trigger("change");
					
					$("#selectallT").prop("checked", false);
					$("#selectallW").prop("checked", false);
					$("#selectallA").prop("checked", false);
					$("#treg").trigger("change");
					$("#witel").trigger("change");
					$("#am").trigger("change");
				}else{
					$("#segmen").attr("disabled",false);
					$("#data_segmen").val("");
					$("#segmen option[value=all]").remove();
					$("#segmen").select2();
					$("#segmen").trigger("change");
					
					$("#selectallT").prop("checked", false);
					$("#selectallW").prop("checked", false);
					$("#selectallA").prop("checked", false);
					$("#treg").trigger("change");
					$("#witel").trigger("change");
					$("#am").trigger("change");
				}
			});
			</script>
			';
		} else {
			redirect('/panel');
		}
	}
	
	public function getDatSeg_edit(){
		if(checkingsessionpwt()){
			$modify 		= str_replace(",","','",$_GET['id']);
			$getvaldiv		= $_GET['id'];
			if ($getvaldiv=='' or $getvaldiv=='all' or $getvaldiv=='null') {
				$valdiv		= "";
			} else {
				$valdiv		= "WHERE id_divisi IN ('".$modify."')";
			}
			
			$dataDivisi		= $this->query->getData('segmen','id_segmen,nama_segmen',"$valdiv GROUP BY id_segmen,nama_segmen ORDER BY nama_segmen ASC");
			// echo '<select multiple class="multipleselect2 form-control" id="ed_segmen" name="ed_segmen">';
			foreach($dataDivisi as $data) {
				echo "<option value='".$data['id_segmen']."'>".$data['nama_segmen']."</option>";
			}
			// echo '</select>';
			// echo "<script>
					// $(document).ready(function() {
						// $('.multipleselect2').select2();
					// });
					// $('#ed_segmen').change(function() {
					// var id_segmen = $(this).val();
						// if(id_segmen == ''){
							// $('#ed_showtreg').html('');
						// }else{
							// $.ajax({
								// url: '".base_url()."core/getDataT_edit',
								// type: 'GET',
								// data: 'id='+id_segmen
							// }).done(function(data){
								// console.log(data);
								// $('#ed_showtreg').html(data);	
								// $('#ed_data_segmen').val(id_segmen);
							// })			
						// }
					// });
					// </script>";
			
		} else {
			redirect('/panel');
		}
	}
	
	public function getDataT(){
		if(checkingsessionpwt()){
			$modify 		= str_replace(",","','",$_GET['id']);
			$getvalseg		= $_GET['id'];
			if ($getvalseg=='' or $getvalseg=='all' or $getvalseg=='null') {
				$valseg		= "";
			} else {
				$valseg		= "WHERE id_segmen IN ('".$modify."')";
			}
			
			$dataDivisi		= $this->query->getData('map','treg',"$valseg GROUP BY treg ORDER BY treg ASC");
			
			echo '<div class="col-md-6">';
			echo '<select multiple class="multipleselect2 form-control" id="treg" name="treg">';
			foreach($dataDivisi as $data) {
				echo "<option value='".$data['treg']."'>".$data['treg']."</option>";
			}
			echo '</select>';
			echo "<script>
				$(document).ready(function() {
					$('.multipleselect2').select2();
				});
				$('#treg').change(function() {
					var id_treg = $(this).val();
					if(id_treg == ''){
						$('#showitel').html('');
					}else{
						$.ajax({
							url: '".base_url()."core/getDataWit',
							type: 'GET',
							data: 'id='+id_treg
						}).done(function(data){
							// console.log(data);
							$('#showitel').html(data);	
							$('#data_treg').val(id_treg);
						})			
					}
				});
				</script>";
			echo '</div>';
			echo '
			<div class="col-sm-4">
				<div class="checkbox checkbox-info checkbox-circle">
					<input id="selectallT" type="checkbox">
					<label for="selectallT"><b>Select All TREG</b></label>
				</div>
			</div>
			<script>
			$("#selectallT").click(function(){
				if($("#selectallT").is(":checked") ){
					$("#treg").attr("disabled","disabled");
					$("#data_treg").val("all");
					$("#treg").append($("<option>", {
						value: "all",
						text: "ALL TREG"
					}));
					$("#treg").val("all");
					$("#treg").select2();
					$("#treg").trigger("change");
					
					$("#selectallW").prop("checked", false);
					$("#selectallA").prop("checked", false);
					$("#witel").trigger("change");
					$("#am").trigger("change");
				}else{
					$("#treg").attr("disabled",false);
					$("#data_treg").val("");
					$("#treg option[value=all]").remove();
					$("#treg").select2();
					$("#treg").trigger("change");
					
					$("#selectallW").prop("checked", false);
					$("#selectallA").prop("checked", false);
					$("#witel").trigger("change");
					$("#am").trigger("change");
				}
			});
			</script>
			';
		} else {
			redirect('/panel');
		}
	}
	public function getDataT_edit(){
		if(checkingsessionpwt()){
			$modify 		= str_replace(",","','",$_GET['id']);
			$getvalseg		= $_GET['id'];
			if ($getvalseg=='' or $getvalseg=='all' or $getvalseg=='null') {
				$valseg		= "";
			} else {
				$valseg		= "WHERE id_segmen IN ('".$modify."')";
			}
			
			$dataDivisi		= $this->query->getData('map','treg',"$valseg GROUP BY treg ORDER BY treg ASC");
			
			// echo '<select multiple class="multipleselect2 form-control" id="ed_treg" name="ed_treg">';
			foreach($dataDivisi as $data) {
				echo "<option value='".$data['treg']."'>".$data['treg']."</option>";
			}
			// echo '</select>';
			// echo "<script>
				// $(document).ready(function() {
					// $('.multipleselect2').select2();
				// });
				// $('#ed_treg').change(function() {
					// var id_treg = $(this).val();
					// if(id_treg == ''){
						// $('#ed_showitel').html('');
					// }else{
						// $.ajax({
							// url: '".base_url()."core/getDataWit_edit',
							// type: 'GET',
							// data: 'id='+id_treg
						// }).done(function(data){
							// console.log(data);
							// $('#ed_showitel').html(data);	
							// $('#ed_data_treg').val(id_treg);
						// })			
					// }
				// });
				// </script>";
		} else {
			redirect('/panel');
		}
	}
	
	public function getDataWit(){
		if(checkingsessionpwt()){
			$modify 		= str_replace(",","','",$_GET['id']);
			$getvalwit		= $_GET['id'];
			if ($getvalwit=='' or $getvalwit=='all' or $getvalwit=='null') {
				$valwit		= "";
			} else {
				$valwit		= "WHERE treg IN ('".$modify."')";
			}
			
			$dataDivisi		= $this->query->getData('witel','id_witel,nama_witel',"$valwit ORDER BY nama_witel ASC");
			
			echo '<div class="col-md-6">';
			echo '<select multiple class="multipleselect2 form-control" id="witel" name="witel">';
			foreach($dataDivisi as $data) {
				echo "<option value='".$data['id_witel']."'>".$data['nama_witel']."</option>";
			}
			echo '</select>';
			echo "<script>
					$(document).ready(function() {
						$('.multipleselect2').select2();
					});
					$('#witel').change(function() {
						var id_witel = $(this).val();
						if(id_witel == ''){
							$('#showam').html('');
						}else{
							$.ajax({
								url: '".base_url()."core/get_DataAm',
								type: 'GET',
								data: 'id='+id_witel
							}).done(function(data){
								// console.log(data);
								$('#showam').html(data);	
								$('#data_witel').val(id_witel);	
							})			
						}
					});
					</script>";
			echo '</div>';
			echo '
			<div class="col-sm-4">
				<div class="checkbox checkbox-info checkbox-circle">
					<input id="selectallW" type="checkbox">
					<label for="selectallW"><b>Select All Witel</b></label>
				</div>
			</div>
			<script>
			$("#selectallW").click(function(){
				if($("#selectallW").is(":checked") ){
					$("#witel").attr("disabled","disabled");
					$("#data_witel").val("all");
					$("#witel").append($("<option>", {
						value: "all",
						text: "ALL WITEL"
					}));
					$("#witel").val("all");
					$("#witel").select2();
					$("#witel").trigger("change");
					
					$("#selectallA").prop("checked", false);
					$("#am").trigger("change");
				}else{
					$("#witel").attr("disabled",false);
					$("#data_witel").val("");
					$("#witel option[value=all]").remove();
					$("#witel").select2();
					$("#witel").trigger("change");
					
					$("#selectallA").prop("checked", false);
					$("#am").trigger("change");
				}
			});
			</script>
			';
			
		} else {
			redirect('/panel');
		}
	}
	public function getDataWit_edit(){
		if(checkingsessionpwt()){
			$modify 		= str_replace(",","','",$_GET['id']);
			$getvalwit		= $_GET['id'];
			if ($getvalwit=='' or $getvalwit=='all' or $getvalwit=='null') {
				$valwit		= "";
			} else {
				$valwit		= "WHERE treg IN ('".$modify."')";
			}
			
			$dataDivisi		= $this->query->getData('witel','id_witel,nama_witel',"$valwit ORDER BY nama_witel ASC");
			// echo '<select multiple class="multipleselect2 form-control" id="ed_witel" name="ed_witel">';
			foreach($dataDivisi as $data) {
				echo "<option value='".$data['id_witel']."'>".$data['nama_witel']."</option>";
			}
			// echo '</select>';
			// echo "<script>
					// $(document).ready(function() {
						// $('.multipleselect2').select2();
					// });
					// $('#ed_witel').change(function() {
						// var id_witel = $(this).val();
						// if(id_witel == ''){
							// $('#ed_showam').html('');
						// }else{
							// $.ajax({
								// url: '".base_url()."core/get_DataAm_edit',
								// type: 'GET',
								// data: 'id='+id_witel
							// }).done(function(data){
								// console.log(data);
								// $('#ed_showam').html(data);	
								// $('#ed_data_witel').val(id_witel);	
							// })			
						// }
					// });
					// </script>";
			
		} else {
			redirect('/panel');
		}
	}
	
	public function get_DataAm_edit(){
		if(checkingsessionpwt()){
			$modify 		= str_replace(",","','",$_GET['id']);
			$getvalam		= $_GET['id'];
			if ($getvalam=='' or $getvalam=='all' or $getvalam=='null') {
				$valam		= "";
			} else {
				$valam		= "WHERE id_witel IN ('".$modify."') or id_witel='".$_GET['id']."'";
			}
			
			// $dataam		= $this->query->getData('map','nik_am,(select nama_am from am where nik_am=map.nik_am) as nama_am',"$cond GROUP BY nik_am ORDER BY nik_am ASC");
			
			$q				= "
							select * from (
							select basic.*, (select GROUP_CONCAT(akses_witel) from user_organization where userid=basic.userid) as id_witel from (
								select nik_am, nama_am, (select GROUP_CONCAT(DISTINCT userid) from user where username=am.nik_am) as userid
								from am
								order by nik_am asc
							) as basic
							) as MASTER
							$valam
							";
			$dataam			= $this->query->getDatabyQ($q);
			
			// echo '<select multiple class="multipleselect2 form-control" id="ed_am" name="ed_am">';
			foreach($dataam as $data) {
				echo "<option value='".$data['nik_am']."'>".$data['nik_am']." - ".$data['nama_am']."</option>";
			}
			// echo '</select>';
			// echo "<script>
					// $(document).ready(function() {
						// $('.multipleselect2').select2();
					// });
					// $('#ed_am').change(function() {
						// var id_am = $(this).val();
						// $('#ed_data_am').val(id_am);
						
					// });
					// </script>";
			
		} else {
			redirect('/panel');
		}
	}
	
	public function get_DataAm(){
		if(checkingsessionpwt()){
			$modify 		= str_replace(",","','",$_GET['id']);
			$getvalam		= $_GET['id'];
			if ($getvalam=='' or $getvalam=='all' or $getvalam=='null') {
				$valam		= "";
			} else {
				$valam		= "WHERE id_witel IN ('".$modify."')";
			}
			
			$dataam		= $this->query->getData('map','nik_am, (select nama_am from am where nik_am=map.nik_am) as nama_am',"$valam GROUP BY nik_am ORDER BY nik_am ASC");
			
			echo '<div class="col-md-6">';
			echo '<select multiple class="multipleselect2 form-control" id="am" name="am">';
			foreach($dataam as $data) {
				echo "<option value='".$data['nik_am']."'>".$data['nik_am']." - ".$data['nama_am']."</option>";
			}
			echo '</select>';
			echo "<script>
					$(document).ready(function() {
						$('.multipleselect2').select2();
					});
					$('#am').change(function() {
						var id_am = $(this).val();
						$('#data_am').val(id_am);
						
					});
					</script>";
			echo '</div>';
			echo '
			<div class="col-sm-4">
				<div class="checkbox checkbox-info checkbox-circle">
					<input id="selectallA" type="checkbox">
					<label for="selectallA"><b>Select All AM</b></label>
				</div>
			</div>
			<script>
			$("#selectallA").click(function(){
				if($("#selectallA").is(":checked") ){
					$("#am").attr("disabled","disabled");
					$("#data_am").val("all");
					$("#am").append($("<option>", {
						value: "all",
						text: "ALL AM"
					}));
					$("#am").val("all");
					$("#am").select2();
					$("#am").trigger("change");
				}else{
					$("#am").attr("disabled",false);
					$("#data_am").val("");
					$("#am option[value=all]").remove();
					$("#am").select2();
					$("#am").trigger("change");
				}
			});
			</script>
			';
			
		} else {
			redirect('/panel');
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
	

	
	// NOTIFICATION MESSAGES
	public function notifmsg(){
		$getJmlNotifMsg	= $this->query->getData('notifications','count(*) as jml',"WHERE type='msg' and baca='N' ORDER BY id_notif DESC");
		$jmlNotifMsg	= array_shift($getJmlNotifMsg);
		
		if ($jmlNotifMsg['jml']>0) {
			$labelMsg	= '<span id="notifmsg" class="label label-success">'.$jmlNotifMsg['jml'].'</span>';
		} else {
			$labelMsg	= '';
		}
		
		$getNotifMsg	= $this->query->getData('notifications','*',"WHERE type='msg' ORDER BY id_notif DESC");
		
		
		echo '
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<i class="pe-7s-mail"></i>
				'.$labelMsg.'
			</a>
		';
			
		echo '
			<ul class="dropdown-menu">
				<li class="header">You have '.$jmlNotifMsg['jml'].' new messages</li>
				<li>
					<div class="slimScrollDiv">
						<ul class="menu">
		';
						if ($jmlNotifMsg['jml']>0) {
							foreach($getNotifMsg as $datamsg) {
								echo'
								<li><!-- start message -->
									<a href="#">
										<h4 style="margin:0px;">Support Team<small><i class="fa fa-clock-o"></i> 5 mins</small></h4>
										<p style="margin:0px;">Why not buy a new awesome theme?</p>
									</a>
								</li>';
							}
						}
		echo '
					</ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
				</li>
				<li class="footer"><a href="'.base_url().'inbox">See All Messages</a></li>
			</ul>
		';
	}
	
	// NOTIFICATION All
	public function notification(){
		$getJmlNotif	= $this->query->getData('notifications','count(*) as jml',"WHERE type!='msg' and baca='N' ORDER BY id_notif DESC");
		$jmlNotif		= array_shift($getJmlNotif);
		
		if ($jmlNotif['jml']>0) {
			$label	= '<span id="notifother" class="label label-warning">'.$jmlNotif['jml'].'</span>';
		} else {
			$label	= '';
		}
		
		$getNotif		= $this->query->getData('notifications','*',"WHERE type!='msg' ORDER BY id_notif DESC");
		
		echo '
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
				<i class="pe-7s-speaker"></i>
				'.$label.'
			</a>
		';
			
		echo '
			<ul class="dropdown-menu">
				<li class="header">You have '.$jmlNotif['jml'].' new notification</li>
				<li>
					<div class="slimScrollDiv">
						<ul class="menu">
		';
						foreach($getNotif as $data) {
							$time		= $this->formula->nicetime($data['date']);
							
							if ($data['type']=='reservation') {
								$getDataR	= $this->query->getData('booking','a.*,b.*',"a LEFT JOIN notifications b on a.id_notif=b.id_notif WHERE b.id_notif='".$data['id_notif']."'");
								$dataR		= array_shift($getDataR);
								$link		= 'detail/'.$dataR['kode_booking'];
							} else if ($data['type']=='payment') {
								$getDataP	= $this->query->getData('payment','a.*,b.*',"a LEFT JOIN notifications b on a.id_notif=b.id_notif WHERE b.id_notif='".$data['id_notif']."'");
								$dataP		= array_shift($getDataP);
								$link		= 'detail/'.$dataP['kode_booking'];
							} else if ($data['type']=='testimonial') {
								$getDataP	= $this->query->getData('testimonial','a.*,b.*',"a LEFT JOIN notifications b on a.id_notif=b.id_notif WHERE b.id_notif='".$data['id_notif']."'");
								$dataP		= array_shift($getDataP);
								$link		= '';
							}
							
							echo'
							<li><!-- start message -->
								<a href="'.base_url().'panel/'.$data['type'].'/'.$link.'">
									<h4 style="margin:0px;text-transform:capitalize;">'.$data['type'].'<small><i class="fa fa-clock-o"></i> '.$time.'</small></h4>
									<p style="margin:0px;">'.$data['notifications'].'</p>
								</a>
							</li>';
						}
		echo '
					</ul><div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 3px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px;"></div><div class="slimScrollRail" style="width: 3px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
				</li>
				<li class="footer"><a href="'.base_url().'inbox">See All Messages</a></li>
			</ul>
		';
	}
	
	
	// MANAGE MENUS FRONT
	public function insertmenu(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			
			$id_menu		= trim(strip_tags(stripslashes($this->input->post('id_menu',true))));
			$menu			= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			$parent			= trim(strip_tags(stripslashes($this->input->post('parent',true))));
			$url			= trim(strip_tags(stripslashes($this->input->post('url',true))));
			$sort			= trim(strip_tags(stripslashes($this->input->post('sort',true))));
			$type			= trim(strip_tags(stripslashes($this->input->post('type',true))));
			$icon			= trim(strip_tags(stripslashes($this->input->post('icon',true))));
			
			
			
			$rows = $this->query->insertData('menu', "id_menu,menu,parent,url,sort,type,icon", "'$id_menu','$menu','$parent','$url','$sort','$type','$icon'");
			$userid		= $userdata['userid'];
			$datelog	= date('Y-m-d H:i:s');
			$ipaddr		= $_SERVER['REMOTE_ADDR'];
			$url 		= "Manage Menu";
			$activity 	= "INSERT";
			if($rows) {
				$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$id_menu','$ipaddr'");
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function getdatamenu(){
		if(checkingsessionpwt()){
			
			$getData			= $this->query->getData('menu','*','ORDER BY sort ASC');

			$no=0;
			foreach($getData as $data) { 
				if($data['parent']=='0'){
					$parent_menu = "Top Menu";
				}else{
					$query_parent = $this->db->query("SELECT menu FROM menu WHERE id_menu='".$data['parent']."'")->result_array();
					foreach($query_parent as $row_parent) {
						$parent_menu = $row_parent['menu'];
					}
				}
				if($data['type']=='0'){
					$position = "Main Menu";
				}else{
					$position = "Configuration";
				}
				$no++;
				$id = $data['id_menu'];
	
				$row = array(
					$no,
					$data['menu'],
					$parent_menu,
					$data['url'],
					$position,
					$data['icon'],
					"
					<a class='btn btn-xs btn-primary btnupdateM' data-toggle='modal' data-target='#update' data-id='$id' style='margin-right: 10px; padding:5px'><i data-toggle='tooltip' title='Edit' class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-danger btndeleteMenu' data-toggle='modal' data-target='#delete' data-id='$id' style=' padding:5px 8px'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a>
					"
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function getdatalop(){
		if(checkingsessionpwt()){
			@$segmen = $_GET['segmen'];
			@$treg = $_GET['treg'];
			@$witel = $_GET['witel'];
			@$am = $_GET['am'];
			
			if ($segmen=='ALL%20SEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and c.id_segmen as segmen='".$segmen."'";}
			if ($treg=='ALL TREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and c.treg='".$treg."'"; }
			if ($witel=='ALL WITEL' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and c.id_witel='".$witel."'"; }
			if ($am=='ALL AM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and c.nik_am='".getnik($am)."'"; }
			
			$getData = $this->query->getData('`lop` a LEFT JOIN lo b ON a.id_lo=b.id_lo LEFT JOIN map c ON b.id_map = c.id_map',' a.*,c.treg,c.id_witel as witel,c.nik_am,c.id_segmen as segmen ',"WHERE a.id_lop is not null $wheresegmen $wheretreg $wherewitel $wheream");
			
			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_lop'];
				if($data['waktu']== 0 OR $data['waktu']==NULL){
					$waktus = "";
				}else{
					$tgl3=date_create($data['waktu']);
					// $tgl4 = date_format($tgl3,"j F Y, g:i a");
					$tgl4 = date_format($tgl3,"F Y");
					$waktus = $tgl4;
				}
				if($data['tanggal']== 0 OR $data['tanggal']==NULL){
					$tanggals = "";
				}else{
					$tgl=date_create($data['tanggal']);
					$tgl2 = date_format($tgl,"j F Y");
					// $tgl2 = date_format($tgl,"j F Y, g:i a");
					$tanggals = $tgl2;
				}
				if($data['file_kontrak']==''){
					$button="";					
				}else{
					$button="<center><a class='btn btn-xs btn-success btnFilePreview' data-toggle='modal' data-target='#FilePreview' data-file='".$data['file_kontrak']."' data-ext='".pathinfo($data['file_kontrak'], PATHINFO_EXTENSION)."' data-nomor='".$data['nomor_kontrak']."' ><i data-toggle='tooltip' title='".$data['file_kontrak']."' class='glyphicon glyphicon-fullscreen'></i>&nbsp; View File</a>&nbsp;<a class='btn btn-xs btn-danger btnDeleteImage' data-toggle='modal' data-target='#deleteImage' alt='Delete File Kontrak' data-id='".$data['id_lop']."'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a></center>";										
				}
				$tgl5=date_create($data['last_update']);
				$tgl12 = date_format($tgl5,"j F Y, g:i a");
				if ($data['last_update']=='' or $data['last_update']=='NULL') {
					$last = '';
				} else {
					$last = $tgl12;
				}
				if($data['tanggal_kb'] == NULL or $data['tanggal_kb'] =="" or $data['tanggal_kb']== 0){
					$last1 = "";										
				}else{
					$last1 = date("j F Y", strtotime($data['tanggal_kb']));					
				}
				$row = array(
					"",
					$data['id_lo'],
					$data['id_lop'],
					$data['id_sirup'],
					$data['id_lelang'],
					$data['nama_pkt'],
					number_format((int)$data['pagu_proj'],0),
					number_format((int)$data['nilai_win'],0),
					$data['metode'],
					$waktus,
					$tanggals,
					$data['kategori'],
					$data['status'],
					$data['kode_raisa'],
					$data['portfolio'],
					$data['subs'],
					$data['treg'],
					$data['nomor_kontrak'],
					$last1,
					$button,
					$data['ket'],
					$last,
					"
					<a class='btn btn-xs btn-primary btnupdateM' data-toggle='modal' data-target='#update' data-id='$id' style='margin-right: 10px; padding:5px'><i data-toggle='tooltip' title='Edit' class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-danger btndeleteMenu' data-toggle='modal' data-target='#delete' data-id='$id' style=' padding:5px 8px'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a>
					"
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$json['data'][] = array('','','','','','','','','','','','','','','','','','','','','','');
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function filter_lop2(){
		if(checkingsessionpwt()){
			$srcsegment = trim(strip_tags(stripslashes($this->input->post('s_segment',true))));
			$srctreg = trim(strip_tags(stripslashes($this->input->post('s_treg',true))));
			$srcwitel = trim(strip_tags(stripslashes($this->input->post('s_witel',true))));
			$srcam = trim(strip_tags(stripslashes($this->input->post('s_am',true))));
			
			if($srcsegment != NULL){
				$seg = "AND c.id_segmen as segmen='".$srcsegment."'";
			}else{
				$seg = "";
			}

			if($srctreg != NULL){
				$tre = "AND c.treg='".$srctreg."'";
			}else{
				$tre = "";
			}

			if($srcwitel != NULL){
				$wit = "AND c.id_witel as witel='".$srcwitel."'";
			}else{
				$wit = "";
			}

			if($srcam != NULL){
				$amm = "AND c.nik_am='".$srcam."'";
			}else{
				$amm = "";
			}
			$draw = $_REQUEST['draw'];
			$length = $_REQUEST['length'];
			$start = $_REQUEST['start'];
			$search = $_REQUEST['search'];
			if($search['value']!=""){
				$src = " AND a.nama_pkt LIKE '%".$search['value']."%'";
			}else{
				$src ="";
			}
			
			$limit = ' limit '.$start.','.$length;
			if($length=="" && $start==""){
				$limit = " ";
			}else{
			}
			$query = '
				SELECT a.*,b.id_map,c.id_segmen as segmen,d.nama_am FROM lop a LEFT JOIN lo b ON a.id_lo=b.id_lo LEFT JOIN map c ON b.id_map=c.id_map LEFT JOIN am d ON c.nik_am = d.nik_am WHERE a.treg!="" '.$seg.' '.$tre.' '.$wit.' '.$amm.' '.$src.' '.$limit.'
			';
			$query2 = '
				SELECT a.*,b.id_map,c.id_segmen as segmen,d.nama_am FROM lop a LEFT JOIN lo b ON a.id_lo=b.id_lo LEFT JOIN map c ON b.id_map=c.id_map LEFT JOIN am d ON c.nik_am = d.nik_am WHERE a.treg!="" '.$seg.' '.$tre.' '.$wit.' '.$amm.' '.$src.'
				
			';
			
			
			
			$total = $this->db->query($query2)->num_rows();
			//$total1 = $this->db->query($query)->num_rows();
			
			$getData = $this->db->query($query)->result_array();
			$dataNum = $this->db->query($query)->num_rows();
			
			$no=0;
			
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_lop'];
				if($data['waktu']== 0 OR $data['waktu']==NULL){
					$waktus = "";
				}else{
					$tgl3=date_create($data['waktu']);
					$tgl4 = date_format($tgl3,"j F Y, g:i a");
					$waktus = $tgl4;
				}
				if($data['tanggal']== 0 OR $data['tanggal']==NULL){
					$tanggals = "";
				}else{
					$tgl=date_create($data['tanggal']);
					$tgl2 = date_format($tgl,"j F Y, g:i a");
					$tanggals = $tgl2;
				}
				
				$tgl5=date_create($data['last_update']);
				$tgl12 = date_format($tgl5,"j F Y, g:i a");
				$last = $tgl12;
				if($data['file_kontrak']==''){
					$button="";					
				}else{
					$button="<center><a class='btn btn-xs btn-success btnFilePreview' data-toggle='modal' data-target='#FilePreview' data-file='".$data['file_kontrak']."' data-ext='".pathinfo($data['file_kontrak'], PATHINFO_EXTENSION)."' data-nomor='".$data['nomor_kontrak']."' ><i data-toggle='tooltip' title='".$data['file_kontrak']."' class='glyphicon glyphicon-fullscreen'></i>&nbsp; View File</a>&nbsp;<a class='btn btn-xs btn-danger btnDeleteImage' data-toggle='modal' data-target='#deleteImage' alt='Delete File Kontrak' data-id='".$data['id_lop']."'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a></center>";										
				}
				$row[] = array(
					$no,
					$data['id_lo'],
					$data['id_lop'],
					$data['id_sirup'],
					$data['id_lelang'],
					$data['nama_pkt'],
					number_format((int)$data['pagu_proj'],0),
					number_format((int)$data['nilai_win'],0),
					$data['metode'],
					$waktus,
					$tanggals,
					$data['kategori'],
					$data['status'],
					$data['kode_raisa'],
					$data['portfolio'],
					$data['subs'],
					$data['treg'],
					$data['nomor_kontrak'],
					$button,
					$data['ket'],
					$last,
					"
					<a class='btn btn-xs btn-primary btnupdateM' data-toggle='modal' data-target='#update' data-id='$id' style='margin-right: 10px; padding:5px'><i data-toggle='tooltip' title='Edit' class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-danger btndeleteMenu' data-toggle='modal' data-target='#delete' data-id='$id' style=' padding:5px 8px'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a>
					"
				);
				
			}
			if(!isset($row)){
				$row = "";
			}
			$output = array(
				"draw" => $draw,
				"recordsTotal" => $total,
				"recordsFiltered" => $total,
				"data" => $row,
				"debug"=>$query
			);
			echo json_encode($output);
		} else {
			redirect('/panel');
		}
	}
	public function filter_lo2(){
		if(checkingsessionpwt()){
			$srcsegment = trim(strip_tags(stripslashes($this->input->post('s_segment',true))));
			$srctreg = trim(strip_tags(stripslashes($this->input->post('s_treg',true))));
			$srcwitel = trim(strip_tags(stripslashes($this->input->post('s_witel',true))));
			$srcam = trim(strip_tags(stripslashes($this->input->post('s_am',true))));
			
			if($srcsegment != NULL){
				$seg = "AND map.id_segmen as segmen='".$srcsegment."'";
			}else{
				$seg = "";
			}

			if($srctreg != NULL){
				$tre = "AND treg='".$srctreg."'";
			}else{
				$tre = "";
			}

			if($srcwitel != NULL){
				$wit = "AND map.id_witel as witel='".$srcwitel."'";
			}else{
				$wit = "";
			}

			if($srcam != NULL){
				$amm = "AND am.nik_am='".$srcam."'";
			}else{
				$amm = "";
			}
			$draw = $_REQUEST['draw'];
			$length = $_REQUEST['length'];
			$start = $_REQUEST['start'];
			$search = $_REQUEST['search'];

			if($search['value']!=""){
				$src = " AND lo.nama_keg LIKE '%".$search['value']."%' or lo.id_map LIKE '%".$search['value']."%' or lo.id_lo LIKE '%".$search['value']."%'";
			}else{
				$src ="";
			}
			$query = '
				SELECT lo.*,map.id_segmen as segmen,am.nama_am,(SELECT DISTINCT(lop.treg) From lop WHERE lop.id_lo=lo.id_lo)as treg FROM lo,map,am WHERE lo.id_map = map.id_map AND map.nik_am = am.nik_am '.$seg.' '.$tre.' '.$wit.' '.$amm.' '.$src.' limit '.$start.','.$length.'
			';
			$query2 = '
				SELECT lo.*,map.id_segmen as segmen,am.nama_am,(SELECT DISTINCT(lop.treg) From lop WHERE lop.id_lo=lo.id_lo)as treg FROM lo,map,am WHERE lo.id_map = map.id_map AND map.nik_am = am.nik_am '.$seg.' '.$tre.' '.$wit.' '.$amm.' '.$src.'
				
			';
			
			
			
			$total = $this->db->query($query2)->num_rows();
			
			$getData = $this->db->query($query)->result_array();
			$dataNum = $this->db->query($query)->num_rows();
			
			$no=0;
			
			foreach($getData as $data) { 
				$getDatapagu			= $this->query->getData('lop','SUM(pagu_proj)AS pagu_lo','WHERE id_lo="'.$data['id_lo'].'"');
				foreach($getDatapagu as $datapagu) { 
					$pagu_lo = number_format((int)$datapagu['pagu_lo'],0); 
				}
				$no++;
				$id = $data['id_lo'];
				//$pagu_lo = number_format((int)$data['pagu_lo'],0);
				$row[] = array(
					$no,
					$data['id_lo'],
					$data['id_map'],
					$data['satker_lo'],
					$data['nama_keg'],
					$pagu_lo,
					$data['ta'],
					$data['sumber_dana'],
					"
					<a class='btn btn-xs btn-primary btnupdateM' data-toggle='modal' data-target='#update' data-id='$id' style='margin-right: 10px; padding:5px'><i data-toggle='tooltip' title='Edit' class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-danger btndeleteMenu' data-toggle='modal' data-target='#delete' data-id='$id' style=' padding:5px 8px'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a>
					"
					);
			}
			if(!isset($row)){
				$row = "";
			}
			$output = array(
				"draw" => $draw,
				"recordsTotal" => $total,
				"recordsFiltered" => $total,
				"data" => $row
			);
			echo json_encode($output);
		} else {
			redirect('/panel');
		}
	}
	
		
	public function getdatamap(){
		if(checkingsessionpwt()){
			
			$getData			= $this->query->getData('map','*','ORDER BY id_map DESC');

			$no=0;
			foreach($getData as $data) { 
				//echo var_dump($data);
				$no++;
				$id = $data['id_map'];
				$target_rev = number_format((int)$data['target_rev'],0);
				$row = array(
					$no,
					$data['id_map'],
					$data['nipnas'],
					$data['nik_am'],
					$data['segmen'],
					$data['witel'],
					$data['treg'],
					$data['bulan_mapping'],
					$target_rev,
					"
					<a class='btn btn-xs btn-primary btnupdateM' data-toggle='modal' data-target='#update' data-id='$id' style='margin-right: 10px; padding:5px'><i data-toggle='tooltip' title='Edit' class='glyphicon glyphicon-pencil'></i></a><a class='btn btn-xs btn-danger btndeleteMenu' data-toggle='modal' data-target='#delete' data-id='$id' style=' padding:5px 8px'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a>
					"
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	
	public function modalmenu(){
		$background		= '';
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('menu','*',"WHERE id_menu='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $data) {
					
					
					$row = array(
						'id_menu'		=> $data['id_menu'],
						'menu'			=> $data['menu'],
						'parent'	=> $data['parent'],
						'url'		=> $data['url'],
						'type'			=> $data['type'],
						'sort'			=> $data['sort'],
						'icon'			=> $data['icon']
						);
					$json = $row;
					// echo json_encode($row);
					// exit;
				}
				echo json_encode($json);
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function getselect2value(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			$id = $_GET['id'];
			if(isset($id)){
				if(trim($id)=='ALL SEGMEN'){
					$filter = "";
				}else{
					$filter = "WHERE segmen = '".$id."'";					
				}
			}else{
				$filter = "";
			}
			$query = '
				SELECT * FROM (
					SELECT a.nik_am,a.nama_am,b.id_segmen as segmen from am a LEFT JOIN map b ON a.nik_am = b.nik_am
					) as T '.$filter.'
					 GROUP BY nik_am,nama_am,segmen  
					ORDER BY `T`.`nik_am` DESC
			';
			
			$getData = $this->db->query($query)->result_array();
			
			header('Content-type: application/json; charset=UTF-8');
			$row[] = array('id' => '','text' => 'ALL AM' );
			if (isset($id) && !empty($id)) {
				foreach($getData as $data) {
					
					
					$row[] = array(
						'id'			=> $data['nik_am'],
						'text'		=> $data['nik_am']." - ".$data['nama_am'],
						);
					$json = $row;
					// echo json_encode($row);
					// exit;
				}
				echo json_encode($json);
			}
		} else {
			redirect('/panel');
		}
	}
	public function modallop(){
		$background		= '';
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('lop','*',"WHERE id_lop='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $data) {
					
					
					$row = array(
						'id_lo'			=> $data['id_lo'],
						'id_lop'		=> $data['id_lop'],
						'id_sirup'		=> $data['id_sirup'],
						'id_lelang'		=> $data['id_lelang'],
						'nama_pkt'		=> $data['nama_pkt'],
						'pagu_proj'		=> $data['pagu_proj'],
						'nilai_win'		=> $data['nilai_win'],
						'metode'		=> $data['metode'],
						'waktu'			=> $data['waktu'],
						'tanggal'		=> $data['tanggal'],
						'kategori'		=> $data['kategori'],
						'status'		=> $data['status'],
						'kode_raisa'	=> $data['kode_raisa'],
						'portfolio'		=> $data['portfolio'],
						'subs'			=> $data['subs'],
						'treg'			=> $data['treg'],
						'ket'			=> $data['ket'],
						'last_update'	=> $data['last_update'],
						'nomor_kontrak' => $data['nomor_kontrak'],
						'file_kontrak'	=> $data['file_kontrak'],
						'tanggal_kb'	=> $data['tanggal_kb'],
						'id_sr'			=> $data['id_sr']
						);
					$json = $row;
					// echo json_encode($row);
					// exit;
				}
				echo json_encode($json);
			}
		} else {
			redirect('/panel');
		}
	}
	
	
	public function modalmap(){
		$background		= '';
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('map','*',"WHERE id_map='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $data) {
					
					
					$row = array(
						'id_map'			=> $data['id_map'],
						'nipnas'			=> $data['nipnas'],
						'nik_am'			=> $data['nik_am'],
						'segmen'			=> $data['segmen'],
						'witel'				=> $data['witel'],
						'treg'				=> $data['treg'],
						'bulan_mapping'		=> $data['bulan_mapping'],
						'target_rev'		=> $data['target_rev']
						);
					$json = $row;
					// echo json_encode($row);
					// exit;
				}
				echo json_encode($json);
			}
		} else {
			redirect('/panel');
		}
	}
	
	
	
	public function updatemenu(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_idmenu',true))));
			$menu			= trim(strip_tags(stripslashes($this->input->post('ed_menu',true))));
			$parent			= trim(strip_tags(stripslashes($this->input->post('ed_parent_menu',true))));
			$url			= trim(strip_tags(stripslashes($this->input->post('ed_url',true))));
			$sort			= trim(strip_tags(stripslashes($this->input->post('ed_sort',true))));
			$type			= trim(strip_tags(stripslashes($this->input->post('ed_type',true))));
			$icon			= trim(strip_tags(stripslashes($this->input->post('ed_icon',true))));
			
			$rows = $this->query->updateData('menu',"menu='$menu', parent='$parent',url='$url', sort='$sort' , type='$type' ,icon='$icon'","WHERE id_menu='$id'");
				$userid		= $userdata['userid'];
				$datelog	= date('Y-m-d H:i:s');
				$ipaddr		= $_SERVER['REMOTE_ADDR'];
				$url 		= "Manage Menu";
				$activity 	= "UPDATE";
				if($rows) {
					$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$id','$ipaddr'");
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
		} else {
			redirect('/panel');
		}
	}
	
	public function filterlatest(){
		if(checkingsessionpwt()){
		  $data['segmen']    = trim(strip_tags(stripslashes($this->input->post('segment',true))));
		  $data['treg']    = trim(strip_tags(stripslashes($this->input->post('treg',true))));
		  $data['witel']    = trim(strip_tags(stripslashes($this->input->post('witel',true))));
		  $data['am']      = trim(strip_tags(stripslashes($this->input->post('am',true))));
		  
		  $valtable = $this->load->view('panel/dashboard/latesttrans', $data);
		} else {
		  redirect('/panel');
		}
	}
	public function deletefile(){
		if(checkingsessionpwt()){
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$userid		= $userdata['userid'];
			$datelog	= date('Y-m-d H:i:s');
			$ipaddr		= $_SERVER['REMOTE_ADDR'];
			$url 		= "Manage Lop";
			$activity 	= "DELETE FILE KONTRAK";
			
			$rows = $this->query->updateData('lop',"file_kontrak='$fileName'","WHERE id_lop='$cond'");
			if($rows) {
				$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$cond','$ipaddr'");
				//delete eksisting
				$id_lop 		= trim(strip_tags(stripslashes($this->input->post('ed_id_lop',true))));
				$coba = $this->query->getData('lop','file_kontrak','WHERE id_lop='.$cond.'');
				foreach ($coba as $dataex) {
					$dataexis = 'files/kontrak/'.$dataex['file_kontrak'];
				}
				unlink($dataexis);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}

	public function update(){
		if(checkingsessionpwt()){
			
			$id_subs 		= trim(strip_tags(stripslashes($this->input->post('ed_id_subs',true))));
			$nama_subs		= trim(strip_tags(stripslashes($this->input->post('ed_nama_subs',true))));
			$cfu			= trim(strip_tags(stripslashes($this->input->post('ed_cfu',true))));
			$pic_subs		= trim(strip_tags(stripslashes($this->input->post('ed_pic_subs',true))));
			
			$rows = $this->query->updateData('subs',"id_subs='$id_subs', nama_subs='$nama_subs',cfu='$cfu', pic_subs='$pic_subs'","WHERE id_subs='$id_subs'");
			
				if($rows) {
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
		} else {
			redirect('/panel');
		}
	}
	
	public function deletemenu(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt');
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('facility','id_facility',$cond);
			$userid		= $userdata['userid'];
			$datelog	= date('Y-m-d H:i:s');
			$ipaddr		= $_SERVER['REMOTE_ADDR'];
			$url 		= "Manage Menu";
			$activity 	= "DELETE";
			if(isset($rows)) {
				$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$cond','$ipaddr'");
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}
	
	// MANAGE TESTIMONIAL
	public function getdatatesti(){
		if(checkingsessionpwt()){
			
			$dataUsr			= $this->query->getData('testimonial','*','ORDER BY date DESC');
			$no=0;
			foreach($dataUsr as $data) { 
				$no++;
				$id = $data['id_testi'];
				
				if ($data['status']=='1') { $dis = 'Disable'; $status = 'Aktif'; $icdis = 'glyphicon-ban-circle'; } else { $dis= 'Enable'; $status= 'Non Aktif'; $icdis= 'glyphicon-ok-circle'; }
	
				$row = array(
					$no,
					$data['nama'],
					$data['email'],
					$data['pesan'],
					$status,
					"
					<center>
						<a class='btn btn-xs btn-warning btnstatusTesti' data-toggle='modal' data-target='#upstatus' data-id='$id' style='margin-right: 10px; padding:5px 8px'><i data-toggle='tooltip' title='".$dis."' class='glyphicon ".$icdis."'></i></a>
						<a class='btn btn-xs btn-primary btnupdateTesti' data-toggle='modal' data-target='#update' data-id='$id' style='margin-right: 10px; padding:5px 8px'><i data-toggle='tooltip' title='Edit' class='glyphicon glyphicon-pencil'></i></a>
						<a class='btn btn-xs btn-danger btndeleteTesti' data-toggle='modal' data-target='#delete' data-id='$id' style=' padding:5px 8px'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a>
					</center>
					"
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function modaltesti(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$getdata			= $this->query->getData('testimonial','*',"WHERE id_testi='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getdata as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function updatetesti(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_idtesti',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('ed_name',true))));
			$email			= trim(strip_tags(stripslashes($this->input->post('ed_email',true))));
			$status			= trim(strip_tags(stripslashes($this->input->post('ed_status',true))));
			$testi			= trim(strip_tags(stripslashes($this->input->post('ed_testi',true))));
				
			$rows = $this->query->updateData('testimonial',"nama='$name', email='$email', status='$status', pesan='$testi'","WHERE id_testi='$id'");
			if($rows) {
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function upstattesti(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$coba = $this->query->getData('testimonial','*','WHERE id_testi='.$id.'');
			
			foreach ($coba as $dataex) {
				if ($dataex['status']==1) {
					$rows = $this->query->updateData('testimonial',"status='0'","WHERE id_testi='$id'");
					if($rows) {
						print json_encode(array('success'=>true,'total'=>1));
					} else {
						echo "";
					}
				} else {
					$rows = $this->query->updateData('testimonial',"status='1'","WHERE id_testi='$id'");
					if($rows) {
						print json_encode(array('success'=>true,'total'=>1));
					} else {
						echo "";
					}
				}
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function deletetesti(){
		if(checkingsessionpwt()){
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$gNotif 	= $this->query->getData('testimonial','id_notif',"WHERE id_testi='".$cond."'");
			$datanotif 	= array_shift($gNotif);
			$bynotif 	= $datanotif['id_notif'];
			$delSubs  	= $this->query->deleteData('notification','id_notif',"'$bynotif'");
			
			$rows = $this->query->deleteData('testimonial','id_testi',$cond);
			
			if(isset($rows)) {
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}
	
	// MANAGE PROMOTION
	public function insertpromo(){
		if(checkingsessionpwt()){
			$kode		= trim(strip_tags(stripslashes($this->input->post('kode',true))));
			$name		= trim(strip_tags(stripslashes($this->input->post('name',true))));
			$sub		= trim(strip_tags(stripslashes($this->input->post('sub',true))));
			$start		= trim(strip_tags(stripslashes($this->input->post('start',true))));
			$end		= trim(strip_tags(stripslashes($this->input->post('end',true))));
			$price		= trim(strip_tags(stripslashes($this->input->post('price',true))));
			$status		= trim(strip_tags(stripslashes($this->input->post('type',true))));
			$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['pict']['name']);
			$item		= $_POST['item'];
			
			$config['upload_path'] = './images/promotion/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media 			= $this->upload->data();
			$fileNamePost 	= $media['file_name'];
			
			$rows = $this->query->insertData('promo', "kode_promo,name,sub,price,pict,date_start,date_end,status", "'$kode','$name','$sub','$price','$fileNamePosst','$start','$end','$status'");
			
			if($rows) {
				$jmlItem	= count($item);
				for($i=0;$i<$jmlItem;$i++) {
					$insItem = $this->query->insertData('promo_detail', "kode_promo,item", "'$kode','".$item[$i]."'");
				}
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function getdatapromo(){
		if(checkingsessionpwt()){
			
			$dataPro			= $this->query->getData('promo','*','ORDER BY date_start DESC');
			$no=0;
			foreach($dataPro as $data) { 
				if ($data['status']=='1') {
					$type = 'Public Promotion';
				} else if ($data['status']=='2') {
					$type = 'Special Promotion';
				}
				
				$no++;
				$id = $data['id_promo'];
	
				$row = array(
					$no,
					$data['kode_promo'],
					$data['name'],
					$data['sub'],
					$this->formula->TanggalIndo($data['date_start']),
					$this->formula->TanggalIndo($data['date_end']),
					$this->formula->rupiah($data['price']),
					$type,
					"
					<a class='btn btn-xs btn-primary btnupdatePro' data-toggle='modal' data-target='#update' data-id='$id' style='margin-right: 10px; padding:5px'><i data-toggle='tooltip' title='Edit' class='glyphicon glyphicon-pencil'></i></a>
					<a class='btn btn-xs btn-danger btndeletePro' data-toggle='modal' data-target='#delete' data-id='$id' style=' padding:5px'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a>
					"
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function modalpromo(){
		if(checkingsessionpwt()){
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('promo','*',"WHERE id_promo='".$id."' ORDER BY id_promo DESC");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function updatepromo(){
		if(checkingsessionpwt()){
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_idpro',true))));
			$kode			= trim(strip_tags(stripslashes($this->input->post('ed_kode',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('ed_name',true))));
			$sub			= trim(strip_tags(stripslashes($this->input->post('ed_sub',true))));
			$start			= trim(strip_tags(stripslashes($this->input->post('ed_start',true))));
			$end			= trim(strip_tags(stripslashes($this->input->post('ed_end',true))));
			$price			= trim(strip_tags(stripslashes($this->input->post('ed_price',true))));
			$cekpict		= $_FILES['ed_pict']['name'];
			$status			= trim(strip_tags(stripslashes($this->input->post('ed_type',true))));
			$item			= $_POST['ed_item'];
				
			if ($cekpict!='') {
				//delete eksisting
				$coba = $this->query->getData('promo','pict','WHERE id_promo='.$id.'');
				foreach ($coba as $dataex) {
					$dataexis = 'images/promotion/'.$dataex['pict'];
				}
				unlink($dataexis);
			
				$fileName = str_replace(' ','_',time().$_FILES['ed_pict']['name']);
				$config['upload_path'] = './images/promotion/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('ed_pict') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('ed_pict');
				
				$rows = $this->query->updateData('promo',"name='$name', sub='$sub', price='$price', pict='$fileName', date_start='$start', date_end='$end', status='$status'","WHERE id_promo='".$id."'");
				
				if($rows) {
					$deletefirst 	= $this->query->deleteData2('promo_detail','kode_promo',$kode);
				
					$jmlItem 		= count($item);
					for($x = 0 ;$x < $jmlItem; $x++){
						$insertItem = $this->query->insertData('promo_detail','kode_promo,item',"'$kode','".$item[$x]."'");
					}
					
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}	
			} else {
				$rows = $this->query->updateData('promo',"name='$name', sub='$sub', price='$price', date_start='$start', date_end='$end', status='$status'","WHERE id_promo='".$id."'");
				if($rows) {
					$deletefirst 	= $this->query->deleteData2('promo_detail','kode_promo',$kode);
				
					$jmlItem 		= count($item);
					for($x = 0 ;$x < $jmlItem; $x++){
						$insertItem = $this->query->insertData('promo_detail','kode_promo,item',"'$kode','".$item[$x]."'");
					}
					
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function getdataEksItem($id){
		if(checkingsessionpwt()){
			$dateEksItem			= $this->query->getData('promo_detail','*',"WHERE kode_promo='$id'");
			
			foreach($dateEksItem as $dataItem) {
				echo '<div id="eksData'.$dataItem['id_detail'].'"><input type="text" name="ed_item[]" class="form-control" placeholder="Item" required value="'.$dataItem['item'].'" style="float: left; width: 90%; margin-right: 10px;"/>
				<button style="line-height: 10px; padding: 8px 10px;" class="btn btn-danger" type="button" id="RemoveListEks'.$dataItem['id_detail'].'""><i class="fa fa-close"></i></button>
				<hr>
				</div>
				';
				
				echo "
				<script>
					$('#RemoveListEks".$dataItem['id_detail']."').click(function() {
						$('#eksData".$dataItem['id_detail']."').remove();
						return false;
					});
				</script>
				";
			}
		}else{
            redirect('/login');
        }
	}
	
	public function deletepromo(){
		if(checkingsessionpwt()){
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			//delete eksisting
			$coba = $this->query->getData('promo','pict',"WHERE kode_promo='".$cond."'");
			foreach ($coba as $dataex) {
				$dataexis = 'images/promotion/'.$dataex['pict'];
			}
			unlink($dataexis);
			
			$rows = $this->query->deleteData2('promo','kode_promo',$cond);
			
			if(isset($rows)) {
				$rows2 = $this->query->deleteData2('promo_detail','kode_promo',$cond);
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}
	
	// FUNCTION
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
	
	public function UploadGallery($fupload_name){

		//direktori gambar
		$vdir_upload = "./images/rooms/";
		$vfile_upload = $vdir_upload . $fupload_name;

		//Simpan gambar dalam ukuran sebenarnya
		move_uploaded_file($_FILES["upl"]["tmp_name"], $vfile_upload);

		//identitas file asli
		$im_src = imagecreatefromjpeg($vfile_upload);
		$src_width = imageSX($im_src);
		$src_height = imageSY($im_src);

		//Simpan dalam versi small 300 pixel
		//Set ukuran gambar hasil perubahan
		$dst_width = 300;
		$dst_height = ($dst_width/$src_width)*$src_height;

		//proses perubahan ukuran
		$im = imagecreatetruecolor($dst_width,$dst_height);
		imagecopyresampled($im, $im_src, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

		//Simpan gambar
		imagejpeg($im,$vdir_upload . "kecil_" . $fupload_name);

		//Hapus gambar di memori komputer
		// imagedestroy($im_src);
		// imagedestroy($im);
	}
	
	public function filtertable(){
		if(checkingsessionpwt()){
			$data['divisi']		= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$data['segmen']		= trim(strip_tags(stripslashes($this->input->post('segment',true))));
			$data['treg']		= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$data['witel']		= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$data['am']			= trim(strip_tags(stripslashes($this->input->post('am',true))));
			$data['periode']	= trim(strip_tags(stripslashes($this->input->post('periode',true))));
			
			$this->load->view('panel/dashboard/table', $data);
		} else {
			redirect('/panel');
		}
	}
	
	public function filterdivtitlename(){
		if(checkingsessionpwt()){
			$data['divisi']		= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$data['segmen']		= trim(strip_tags(stripslashes($this->input->post('segment',true))));
			$data['treg']		= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$data['witel']		= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$data['am']			= trim(strip_tags(stripslashes($this->input->post('am',true))));
			
			$this->load->view('panel/dashboard/titlediv', $data);
			// $json['data'][] = $data;
			// echo json_encode($data);
		} else {
			redirect('/panel');
		}
	}
	
	public function filterR2(){
		if(checkingsessionpwt()){
			$data['divisi']		= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$data['segmen']		= trim(strip_tags(stripslashes($this->input->post('segment',true))));
			$data['treg']		= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$data['witel']		= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$data['am']			= trim(strip_tags(stripslashes($this->input->post('am',true))));
			$data['periode']	= trim(strip_tags(stripslashes($this->input->post('periode',true))));
			
			$valtable = $this->load->view('panel/dashboard/r2', $data);
		} else {
			redirect('/panel');
		}
	}
	
	public function filterSisaAnggaran(){
		if(checkingsessionpwt()){
			$data['divisi']		= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$data['segmen']		= trim(strip_tags(stripslashes($this->input->post('segment',true))));
			$data['treg']		= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$data['witel']		= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$data['am']			= trim(strip_tags(stripslashes($this->input->post('am',true))));
			$data['periode']	= trim(strip_tags(stripslashes($this->input->post('periode',true))));
			
			$valtable = $this->load->view('panel/dashboard/sisaanggaran', $data);
		} else {
			redirect('/panel');
		}
	}
	
	public function filterAchSeg(){
		if(checkingsessionpwt()){
			$data['divisi']		= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$data['segmen']		= trim(strip_tags(stripslashes($this->input->post('segment',true))));
			$data['treg']		= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$data['witel']		= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$data['am']			= trim(strip_tags(stripslashes($this->input->post('am',true))));
			$data['periode']	= trim(strip_tags(stripslashes($this->input->post('periode',true))));
			
			$valtable = $this->load->view('panel/dashboard/achseg', $data);
		} else {
			redirect('/panel');
		}
	}
	
	public function filteramach(){
		if(checkingsessionpwt()){
			$data['divisi']		= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$data['segmen']		= trim(strip_tags(stripslashes($this->input->post('segment',true))));
			$data['treg']		= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$data['witel']		= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$data['am']			= trim(strip_tags(stripslashes($this->input->post('am',true))));
			$data['periode']	= trim(strip_tags(stripslashes($this->input->post('periode',true))));
			
			$valtable = $this->load->view('panel/dashboard/amach', $data);
		} else {
			redirect('/panel');
		}
	}
	
	public function filtersisalo(){
		if(checkingsessionpwt()){
			$data['divisi']		= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$data['segmen']		= trim(strip_tags(stripslashes($this->input->post('segment',true))));
			$data['treg']		= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$data['witel']		= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$data['am']			= trim(strip_tags(stripslashes($this->input->post('am',true))));
			$data['periode']	= trim(strip_tags(stripslashes($this->input->post('periode',true))));
			
			$valtable = $this->load->view('panel/dashboard/sisalo', $data);
		} else {
			redirect('/panel');
		}
	}
	
	public function filterlatesttrans(){
		if(checkingsessionpwt()){
			$data['divisi']		= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$data['segmen']		= trim(strip_tags(stripslashes($this->input->post('segment',true))));
			$data['treg']		= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$data['witel']		= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$data['am']			= trim(strip_tags(stripslashes($this->input->post('am',true))));
			
			$vallatest = $this->load->view('panel/dashboard/latesttrans', $data);
		} else {
			redirect('/panel');
		}
	}
	
	public function modaldatadetail(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('type',true))));
			$am					= trim(strip_tags(stripslashes($this->input->post('am',true))));
			if ($id=='r2hijau') {
				$title	= 'WIN';
			} else if ($id=='r2merah') {
				$title	= 'LOSE';
			} else if ($id=='newgtma') {
				$title	= 'POTENSI NEW-GTMA';
			} else if ($id=='saall') {
				$title	= 'SISA ANGGARAN';
			} else if ($id=='sapagu') {
				$title	= 'PAGU R2 HIJAU';
			} else if ($id=='sapaguHPS') {
				$title	= 'HPS WIN';
			} else if ($id=='sapaguHPS2') {
				$title	= 'PAGU WIN';
			} else if ($id=='sawin') {
				$title	= 'WIN R2 HIJAU';
			} else if ($id=='saper') {
				$title	= '%WIN/PAGU';
			} else if ($id=='saperHPS') {
				$title	= 'RASIO WIN/HPS';
			} else if ($id=='saperHPS2') {
				$title	= 'RASIO WIN/PAGU';
			} else if ($id=='achtotal') {
				$title	= 'TOTAL TARGET';
			} else if ($id=='achach') {
				$title	= 'ACHIEVEMENT';
			} else if ($id=='nonpots') {
				$title	= 'ACHIEVEMENT NON POTS';
			} else if ($id=='sustain') {
				$title	= 'ACHIEVEMENT SUSTAIN';
			} else if ($id=='smart') {
				$title	= 'ACHIEVEMENT SMART';
			} else if ($id=='sisatarget') {
				$title	= 'SISA TARGET';
			} else if ($id=='newgtma') {
				$title	= 'POTENSI NEW-GTMA';
			} else if ($id=='achsisa') {
				$title	= 'SISA TARGET ACHIEVEMENT SEGMEN';
			} else if ($id=='slall') {
				$title	= 'KECUKUPAN SISA LO TERHADAP TARGET';
			} else if ($id=='sllo') {
				$title	= 'SISA LO';
			} else if ($id=='slst') {
				$title	= 'SISA TARGET';
			} else if( $id=='tabletotal'){
				$title	= "TOTAL";
			} else if( $id=='tabler1'){
				$title	= "R1";
			} else if( $id=='tabler1plus'){
				$title	= "ON PROGRESS";
			} else if( $id=='tabler1plusplus'){
				$title	= "SUBMISSION";
			} else if( $id=='tabler0'){
				$title	= "PROSPECT";
			} else if( $id=='tabler2'){
				$title	= "R2 TOTAL";
			} else if( $id=='sisalop'){
				$title	= "SISA LOP";
			} else if( $id=='amachieved'){
				$title	= "AM ACHIEVED";
			} else if( $id=='sustain'){
				$title	= "Sustain";
			} else if( $id=='scalling'){
				$title	= "Smart";
			} else if( $id=='tabletotalperam'){
				$getNama	= $this->query->getData('am','*',"WHERE nik_am='$am'");
				$dNama		= array_shift($getNama);
				$title	= $dNama['nik_am']." - ".$dNama['nama_am']." (".$dNama['posisi'].")";
			}else{
				$getSR	= $this->query->getData('status_raisa','*',"WHERE id_sr='$id'");
				$dSR		= array_shift($getSR);
				$title	= $dSR['sr'];
			}
			
			$data	= array(
				'id'	=> $id,
				'title'	=> $title
			);
			
			header('Content-type: application/json; charset=UTF-8');
			
			echo json_encode($data);
		} else {
			redirect('/panel');
		}
	}
	
	public function datadetaildashboard($type,$divisi,$segmen,$treg,$witel,$am,$tahun){
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			$cekuam1	= str_replace('id_divisi','c.id_divisi',$uam);
			$cekuam2	= str_replace('id_segmen','c.id_segmen',$cekuam1);
			$cekuam3	= str_replace('treg','c.treg',$cekuam2);
			$cekuam4	= str_replace('id_witel','c.id_witel',$cekuam3);
			$cekuam		= str_replace('nik_am','c.nik_am',$cekuam4);
			
			if ($type=='r2hijau') {
				// $wherecond	= "a.kode_raisa='R2' and a.status in ('WIN','NEW-GTMA')";
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='sisalop') {
				// $wherecond	= "a.kode_raisa!='R2'";
				$wherecond	= "a.id_sr!='15'";
			} else if ($type=='newgtma') {
				$wherecond	= "upper(a.status)='NEW-GTMA'";
			} else if ($type=='r2merah') {
				// $wherecond	= " a.kode_raisa='R2' and a.status not in ('WIN','NEW-GTMA')";
				$wherecond	= " a.id_sr='16'";
			} else if ($type=='saall') {
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='sapagu') {
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='sapaguHPS') {
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='sapaguHPS2') {
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='sawin') {
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='saper') {
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='saperHPS') {
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='saperHPS2') {
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='achtotal') {
				$wherecond	= "a.kode_raisa!=''";
				// $wherecond	= "";
			} else if ($type=='achach') {
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='nonpots') {
				$wherecond	= "a.id_sr='15'";
			} else if ($type=='sustain') {
				$wherecond	= "a.sustain_dari!='' and a.sustain_dari!='0' and a.sustain_dari is not null";
			} else if ($type=='smart') {
				$wherecond	= "a.kategori='Smart' and a.id_sr='15'";
			} else if ($type=='sisatarget') {
				$wherecond	= "a.id_sr not in ('15')";
			} else if ($type=='achsisa') {
				$wherecond	= "a.id_sr='15'";
				// $wherecond	= "a.kode_raisa in ('R0','R1','R1+','R1++')";
			} else if ($type=='slall') {
				$wherecond	= "a.kode_raisa in ('R0','R1','R1+','R1++')";
			} else if ($type=='sllo') {
				$wherecond	= "a.kode_raisa in ('R0','R1','R1+','R1++')";
			} else if ($type=='slst') {
				$wherecond	= "a.kode_raisa in ('R0','R1','R1+','R1++')";
			} else if( $type=='tabletotal'){
				$wherecond	= "a.kode_raisa !=''";
			} else if( $type=='tabletotalperam'){
				$wherecond	= "a.kode_raisa !=''";
			} else if( $type=='tabler1'){
				$wherecond	= "a.kode_raisa = 'R1-'";
			} else if( $type=='tabler1plus'){
				$wherecond	= "a.kode_raisa = 'R1+'";
			} else if( $type=='tabler1plusplus'){
				$wherecond	= "a.kode_raisa = 'R1++'";
			} else if( $type=='tabler0'){
				$wherecond	= "a.kode_raisa = 'R0'";
			} else if( $type=='tabler2'){
				$wherecond	= "a.kode_raisa = 'R2'";
			}else {
				$type2 = str_replace("_",",",$type);
				$wherecond = "f.id_sr IN (".$type2.")";
			}
			
			if ($divisi=='ALL%20DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL%20SEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL%20TREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL%20Witel' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL%20AM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			if ($tahun=='') 	{ $wheretahun	= "and tahun='".date('Y')."'"; } else { $wheretahun = "and tahun='".$tahun."'"; }
			$dataDetail			= $this->query->getData(
									"
									lop a 
									left join lo b 
									on a.id_lo=b.id_lo 
									left join map c 
									on c.id_map=b.id_map
									left join gc d
									on c.nipnas=d.nipnas
									left join penyedia e
									on e.id_lo=b.id_lo
									left join status_raisa f
									on f.id_sr = a.id_sr
									",
									'
									a.id_lelang as kode_lelang,
									a.PPN,
									a.id_sirup as sirup,
									b.ta as tahun_anggaran,
									d.nama_gc AS k_l_d_i,
									b.satker_lo AS satker,
									a.nama_pkt AS nama_paket,
									a.pagu_proj AS pagu,
									a.metode AS metode,
									a.kategori,
									(select pemenang from pemenang where id_pemenang=a.id_pemenang) as pemenang,
									(select alamat from pemenang where id_pemenang=a.id_pemenang) as alamat_pemenang,
									a.waktu as waktu_pemilihan,
									b.sumber_dana AS sumber_dana,
									a.portfolio AS portofolio,
									e.penyedia as penyedia,
									a.nilai_win as nilai,
									e.waktu_lelang as waktu,
									e.alamat as alamat,
									c.id_divisi as id_divisi,
									c.id_segmen as id_segmen,
									(select nama_segmen from segmen where id_segmen=c.id_segmen) as nama_segmen,
									c.treg as treg,
									c.id_witel as id_witel,
									(a.nilai_win/a.pagu_proj)*100 as persen,
									a.ket as note,
									(select nama_subs from subs where id_subs=a.subs) as namasub,
									(select nama_am from am where nik_am=c.nik_am) as namaam,
									c.nik_am,
									a.subs as subs,
									a.kode_raisa as kode_raisa,
									a.id_sr as id_sr,
									a.status as status,
									(select sr from status_raisa where id_sr=a.id_sr) as status_raisa,
									a.nomor_kontrak,
									(select group_concat(file) as file_kontrak from file_lop where id_lop=a.id_lop) as file_kontrak,
									a.tanggal,
									a.tanggal_kb,
									f.id_sr,
									c.tahun
									',
									"
									WHERE $wherecond $cekuam $wheredivisi $wheresegmen $wheretreg $wherewitel $wheream $wheretahun order by k_l_d_i asc
									"
								);
			$no=0;
			foreach($dataDetail as $data) { 
				$no++;
				$satker_1 = str_replace("["," ",$data['satker']);
				$satker_2 = str_replace("]"," ",$satker_1);
				
				$getWitel	= $this->query->getData('witel','nama_witel',"where id_witel='".$data['id_witel']."'");
				$dataWitel	= array_shift($getWitel);
				$witel		= $dataWitel['nama_witel'];
				
				$nama_pkt = preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $data['nama_paket']);			
				if($data['file_kontrak']==''){
					$btn = "";
				}else{
					$btn = "<a class='btn btn-xs btn-success btnFilePreview' data-toggle='modal' data-target='#FilePreview' data-file='".$data['file_kontrak']."' data-ext='".pathinfo($data['file_kontrak'], PATHINFO_EXTENSION)."' data-nomor='".$data['nomor_kontrak']."' ><i data-toggle='tooltip' title='".$data['file_kontrak']."' class='glyphicon glyphicon-fullscreen'></i>&nbsp;</a>";
				}
				if($data['waktu_pemilihan'] == NULL or $data['waktu_pemilihan'] =="" or $data['waktu_pemilihan']== 0){
					$waktupemilihan = "";										
				}else{
					$wkt=date_create($data['waktu_pemilihan']);
					$waktupemilihan = date_format($wkt,"m-y");				
				}
				if($data['tanggal_kb'] == NULL or $data['tanggal_kb'] =="" or $data['tanggal_kb']== 0){
					$tanggalkb = "";										
				}else{
					$wkt2=date_create($data['tanggal_kb']);
					$tanggalkb = date_format($wkt2,"d-m-y");				
				}
				
				if($data['tanggal'] == NULL or $data['tanggal'] =="" or $data['tanggal']== 0){
					$tanggaltrans = "";										
				}else{
					$wkt22=date_create($data['tanggal']);
					$tanggaltrans = date_format($wkt22,"d-m-Y");				
				}
				
				// if ($data['pagu']=='' or $data['pagu']=='0') {
					// $perwinpag	= 0;
				// } else {
					@$perwinpag	= ($data['nilai']/$data['pagu'])*100;
				// }
				
				if ($data['PPN']=='0') { $achnilpag	= $data['nilai']; } else { $achnilpag	= (100/110)*$data['nilai']; }
				
				$row = array(
					$data['nama_segmen'],
					$data['treg'],
					$witel,
					$data['namaam'],
					$data['k_l_d_i'],
					$satker_2,
					$data['nama_paket'],					
					$data['kode_lelang'],					
					$this->formula->rupiah3($data['pagu']),
					$this->formula->rupiah3($data['nilai']),
					$this->formula->rupiah3($achnilpag),
					$this->formula->rupiah3($perwinpag).' %',
					$data['kategori'],
					$data['status_raisa'],
					// $data['status'],
					$tanggaltrans,
					$data['pemenang'],
					$data['alamat_pemenang'],
					$data['nomor_kontrak'],
					$tanggalkb,
					$btn,
					$data['note'],
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$row = array("","","","","","","","","","","","","","","","","","","","","","","","","","","","","","");
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function datadetaildashboard_export(){
		if(checkingsessionpwt()){
			ini_set('max_execution_time', 123456);
			ini_set("memory_limit","1256M");
			
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			
			$cekuam1	= str_replace('id_divisi','c.id_divisi',$uam);
			$cekuam2	= str_replace('id_segmen','c.id_segmen',$cekuam1);
			$cekuam3	= str_replace('treg','c.treg',$cekuam2);
			$cekuam4	= str_replace('id_witel','c.id_witel',$cekuam3);
			$cekuam		= str_replace('nik_am','c.nik_am',$cekuam4);
			
			$type = $_GET['type'];
			$divisi = $_GET['divisi'];
			$segmen = $_GET['segmen'];
			$treg = $_GET['treg'];
			$witel = $_GET['witel'];
			$am = $_GET['am'];
			$tahun = $_GET['periode'];
			
			
			if ($type=='r2hijau') {
				// $wherecond	= "a.kode_raisa='R2' and a.status in ('WIN','NEW-GTMA')";
				$wherecond	= "a.id_sr='15'";
				$judulfile	= "WIN";
			} else if ($type=='r2merah') {
				// $wherecond	= " a.kode_raisa='R2' and a.status not in ('WIN','NEW-GTMA')";
				$wherecond	= "a.id_sr='16'";
				$judulfile	= "LOSE";
			} else if ($type=='saall') {
				$wherecond	= "a.id_sr='15'";
				$judulfile	= "Sisa Anggaran";
			} else if ($type=='sapagu') {
				$wherecond	= "a.id_sr='15'";
				$judulfile	= "PAGU WIN";
			} else if ($type=='sawin') {
				$wherecond	= "a.id_sr='15'";
				$judulfile	= "WIN WIN";
			} else if ($type=='saper') {
				$wherecond	= "a.id_sr='15'";
				$judulfile	= "WIN/PAGU";
			} else if ($type=='achtotal') {
				$wherecond	= "a.kode_raisa!=''";
				$judulfile	= "ACHIEVEMENT TOTAL";
			} else if ($type=='achach') {
				$wherecond	= "a.id_sr='15'";
				$judulfile	= "ACHIEVEMENT";
			} else if ($type=='nonpots') {
				$wherecond	= "a.id_sr='15'";
				$judulfile	= "ACHIEVEMENT NONPOTS";
			} else if ($type=='sustain') {
				$wherecond	= "a.sustain_dari!='' and a.sustain_dari!='0' and a.sustain_dari is not null";
				$judulfile	= "ACHIEVEMENT SUSTAIN";
			} else if ($type=='smart') {
				$wherecond	= "a.kategori='Smart' and a.id_sr='15'";
				$judulfile	= "ACHIEVEMENT SMART";
			} else if ($type=='sisatarget') {
				$wherecond	= "a.id_sr not in ('15')";
				$judulfile	= "SISA TARGET";
			} else if ($type=='newgtma') {
				$wherecond	= "a.status in ('NEW-GTMA')";
				$judulfile	= "POTENSI NEW GTMA";
			} else if ($type=='achsisa') {
				$wherecond	= "a.id_sr='15'";
				$judulfile	= "";
			} else if ($type=='slall') {
				$wherecond	= "a.kode_raisa in ('R0','R1','R1+','R1++')";
				$judulfile	= "SISA LO";
			} else if ($type=='sllo') {
				$wherecond	= "a.kode_raisa in ('R0','R1','R1+','R1++')";
				$judulfile	= "SISA LO";
			} else if ($type=='slst') {
				$wherecond	= "a.kode_raisa in ('R0','R1','R1+','R1++')";
				$judulfile	= "SISA LO";
			} else if( $type=='tabletotal'){
				$wherecond	= "a.kode_raisa IS NOT NULL";
				$judulfile	= "Total";
			} else if( $type=='tabletotalperam'){
				$wherecond	= "a.kode_raisa IS NOT NULL";
				$getNama	= $this->query->getData('am','nama_am',"WHERE nik_am='$am'");
				$dNama		= array_shift($getNama);
				$judulfile	= "LOP Achieved ".$am." - ".$dNama['nama_am']."";
			} else if( $type=='tabler1'){
				$wherecond	= "a.kode_raisa = 'R1-'";
				$judulfile	= "TIDAK IKUT";
			} else if( $type=='tabler1plus'){
				$wherecond	= "a.kode_raisa = 'R1+'";
				$judulfile	= "PROGRESS";
			} else if( $type=='tabler1plusplus'){
				$wherecond	= "a.kode_raisa = 'R1++'";
				$judulfile	= "SUBMISSION";
			} else if( $type=='tabler0'){
				$wherecond	= "a.kode_raisa = 'R0'";
				$judulfile	= "PROSPECT";
			} else if( $type=='tabler2'){
				$wherecond	= "a.kode_raisa = 'R2'";
				$judulfile	= "R2";
			} else if( $type=='sisalop'){
				$wherecond	= "a.kode_raisa != 'R2'";
				$judulfile	= "SISA LOP";
			} else {
				$type2 = str_replace("_",",",$type);
				$wherecond = "a.id_sr IN (".$type2.")";
				$judulfile	= "PROGRESS";
			}
			
			if ($divisi=='ALL DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL SEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL TREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL Witel' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL AM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			if ($tahun==' ' or $tahun=='') 	{ $wheretahun	= "and tahun='".date('Y')."'"; } else { $wheretahun = "and tahun='".$tahun."'"; }
			$row = "";
			$dataDetail			= $this->query->getData(
									"
									lop a 
									left join lo b 
									on a.id_lo=b.id_lo 
									left join map c 
									on c.id_map=b.id_map
									left join gc d
									on c.nipnas=d.nipnas
									left join pemenang e
									on e.id_pemenang=a.id_pemenang
									left join witel w 
									on w.id_witel = c.id_witel
									",
									'
									a.id_lop,
									b.id_lo, 
									c.id_map,
									a.id_lelang as kode_lelang,
									a.PPN,
									a.id_sirup as sirup,
									a.tanggal,
									b.ta as tahun_anggaran,
									d.nama_gc AS k_l_d_i,
									b.satker_lo AS satker,
									a.nama_pkt AS nama_paket,
									a.pagu_proj AS pagu,
									a.metode AS metode,
									a.kategori,
									a.waktu as waktu_pemilihan,
									b.sumber_dana AS sumber_dana,
									b.ta AS ta,
									a.portfolio AS portofolio,
									e.pemenang as pemenang,
									e.alamat as alamat,
									a.nilai_win as nilai,
									c.id_divisi as id_divisi,
									c.id_segmen as id_segmen,
									(select nama_segmen from segmen where id_segmen=c.id_segmen) as nama_segmen,
									c.treg as treg,
									c.id_witel as id_witel,
									w.nama_witel as witel,
									(a.nilai_win/a.pagu_proj)*100 as persen,
									a.ket as note,
									(select nama_subs from subs where id_subs=a.subs) as namasub,
									(select nama_am from am where nik_am=c.nik_am) as namaam,
									c.nik_am,
									a.subs as subs,
									a.kode_raisa as kode_raisa,
									a.id_sr as id_sr,
									a.status as status,
									(select sr from status_raisa where id_sr=a.id_sr) as status_raisa,
									a.nomor_kontrak,
									(select group_concat(file) as file_kontrak from file_lop where id_lop=a.id_lop) as file_kontrak,
									a.tanggal_kb,
									a.last_update,
									(select group_concat(distinct nomor_order) from `order` where id_lop=a.id_lop) as nomor_order,
									(select name from user where userid=a.update_by) as update_by
									',
									"
									WHERE $wherecond $cekuam $wheredivisi $wheresegmen $wheretreg $wherewitel $wheream $wheretahun order by k_l_d_i asc
									"
								);
			$no=0;
			foreach($dataDetail as $data) { 
				$no++;
				if($data['waktu_pemilihan'] == NULL or $data['waktu_pemilihan'] =="" or $data['waktu_pemilihan'] ==0){
					$waktupemilihan = "";										
				}else{
					$wkt=date_create($data['waktu_pemilihan']);
					$waktupemilihan = date_format($wkt,"m-y");				
				}
				if($data['tanggal_kb'] == NULL or $data['tanggal_kb'] =="" or $data['tanggal_kb'] ==0){
					$tanggalkb = "";										
				}else{
					$wkt2=date_create($data['tanggal_kb']);
					$tanggalkb = date_format($wkt2,"d-m-y");				
				}
				if($data['tanggal'] == NULL or $data['tanggal'] =="" or $data['tanggal'] ==0){
					$tangal = "";										
				}else{
					$wkt4=date_create($data['tanggal']);
					$tangal = date_format($wkt4,"d-m-y");				
				}
				
				@$perwinpag	= ($data['nilai']/$data['pagu'])*100;
				
				if ($data['PPN']=='0') { $achnilpag	= $data['nilai']; } else { $achnilpag	= (100/110)*$data['nilai']; }
				
				@$row .= 
					"<tr>
					<td>".$data['nama_segmen']."</td>
					<td>".$data['id_lo']."</td>
					<td>".$data['id_lop']."</td>
					<td>".$data['id_map']."</td>
					<td>".$data['sirup']."</td>
					<td>".$data['kode_lelang']."</td>
					<td>".$data['k_l_d_i']."</td>
					<td>".$data['satker']."</td>
					<td>".$data['namaam']."</td>
					<td>".$data['nama_paket']."</td>
					<td>".$this->formula->rupiah3($data['pagu'])."</td>
					<td>".$this->formula->rupiah3($data['nilai'])."</td>
					<td>".$this->formula->rupiah3($achnilpag)."</td>
					<td>".$this->formula->rupiah3($perwinpag)." %</td>
					<td>".$data['status_raisa']."</td>
					<td>".$data['portofolio']."</td>
					<td>".$data['namasub']."</td>
					<td>".$data['pemenang']."</td>
					<td>".$data['alamat']."</td>
					<td>".$data['metode']."</td>
					<td>".$waktupemilihan."</td>					
					<td>".$tangal."</td>					
					<td>".$data['kategori']."</td>					
					<td>".$data['treg']."</td>
					<td>".$data['witel']."</td>
					<td>".$data['nomor_kontrak']."</td>
					<td>".$tanggalkb."</td>
					<td>".$data['note']."</td>
					<td>".$data['update_by']."</td>
					<td>".$data['last_update']."</td>
					<td>".$data['nomor_order']."</td>
					<td>".$data['sumber_dana']."</td>
					<td>".$data['ta']."</td>
				</tr>";
					
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=".$judulfile.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '
			
			<table border="1px">
				<thead>
<tr>
			<th>SEGMEN</th>
            <th>ID LO</th>
            <th>ID LOP</th>
            <th>ID Mapping</th>
            <th>ID Sirup</th>
            <th>ID Lelang</th>
            <th>Nama K/L/D/I</th>
            <th>Nama Satker</th>
            <th>Nama AM</th>
            <th>Nama Paket</th>
            <th>HPS</th>
            <th>Nilai Win</th>
            <th>Achievement</th>
            <th>% HPS</th>
            <th>Status RAISA</th>
            <th>Portofolio</th>
            <th>Subsidiaries</th>
            <th>Pemenang</th>
            <th>Alamat Pemenang</th>
            <th>Metode Pemilihan</th>
            <th>Waktu Pelaksanaan</th>
            <th>Tanggal Transaksi</th>
            <th>Kategori</th>
            <th>TREG</th>
            <th>WITEL</th>
            <th>Nomor KB</th>
            <th>Tanggal KB</th>
            <th>Note</th>
            <th>Update By</th>
            <th>Last Update</th>
            <th>Nomor Order</th>
            <th>Sumber Dana</th>
            <th>TA</th>
          </tr>
			</thead>
				<tbody>'.$row.'
				</tbody>
			</table>
			';
		} else {
			redirect('/panel');
		}
	}
	
	public function datadetaildashboard_exportachseg2(){
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			
			$cekuam1	= str_replace('id_divisi','c.id_divisi',$uam);
			$cekuam2	= str_replace('id_segmen','c.id_segmen',$cekuam1);
			$cekuam3	= str_replace('treg','c.treg',$cekuam2);
			$cekuam4	= str_replace('id_witel','c.id_witel',$cekuam3);
			$cekuam		= str_replace('nik_am','c.nik_am',$cekuam4);
			
			$type 		= $_GET['type'];
			$divisi 	= $_GET['divisi'];
			$segmen 	= $_GET['segmen'];
			$treg 		= $_GET['treg'];
			$witel 		= $_GET['witel'];
			$am 		= $_GET['am'];
			$tahun 		= $_GET['periode'];
						
			$wherecond	= "a.kode_raisa IS NOT NULL";
			$getNama	= $this->query->getData('am','nama_am',"WHERE nik_am='$am'");
			$dNama		= array_shift($getNama);
			$judulfile	= "LOP Achieved ".$am." - ".$dNama['nama_am']."";
			
			if ($divisi=='ALL DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL SEGMEN') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL TREG') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL Witel') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL AM') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			if ($tahun=='') 	{ $wheretahun	= "and tahun='".date('Y')."'"; } else { $wheretahun = "and tahun='".$tahun."'"; }
			
			$dataDetail			= $this->query->getData(
									'
									lop a 
									left join lo b 
									on a.id_lo=b.id_lo 
									left join map c 
									on c.id_map=b.id_map
									left join gc d
									on c.nipnas=d.nipnas
									left join penyedia e
									on e.id_lo=b.id_lo
									',
									'
									a.id_lo,
									a.id_lop,
									c.id_map,
									a.id_lelang as kode_lelang,
									a.id_sirup as sirup,
									b.ta as tahun_anggaran,
									d.nama_gc AS k_l_d_i,
									b.satker_lo AS satker,
									a.nama_pkt AS nama_paket,
									a.pagu_proj AS pagu,
									a.metode AS metode,
									a.waktu as waktu_pemilihan,
									a.tanggal ,
									a.kategori,
									b.sumber_dana AS sumber_dana,
									a.portfolio AS portofolio,
									e.penyedia as penyedia,
									a.nilai_win as nilai,
									e.waktu_lelang as waktu,
									e.alamat as alamat,
									c.target_rev as target,
									c.treg as treg,
									c.id_divisi,
									c.id_segmen,
									c.id_witel,
									(select nama_witel from witel where id_witel=c.id_witel) as witel,
									(a.nilai_win/a.pagu_proj)*100 as persen,
									a.ket as note,
									(select nama_subs from subs where id_subs=a.subs) as namasub,
									(select nama_am from am where nik_am=c.nik_am) as namaam,
									a.subs as subs,
									a.kode_raisa as kode_raisa,
									(select sr from status_raisa where id_sr=a.id_sr) as status_raisa,
									a.status as status,
									a.nomor_kontrak,
									(select group_concat(file) as file_kontrak from file_lop where id_lop=a.id_lop) as file_kontrak,
									a.tanggal_kb
									',
									"
									WHERE $wherecond $wheredivisi $wheresegmen $wheretreg $wherewitel $wheream $wheretahun
									"
								);
			$no=0;
			foreach($dataDetail as $data) { 
				$no++;
				if($data['waktu_pemilihan'] == NULL or $data['waktu_pemilihan'] =="" or $data['waktu_pemilihan'] ==0){
					$waktupemilihan = "";										
				}else{
					$wkt=date_create($data['waktu_pemilihan']);
					$waktupemilihan = date_format($wkt,"j F");				
				}
				if($data['tanggal_kb'] == NULL or $data['tanggal_kb'] =="" or $data['tanggal_kb'] ==0){
					$tanggalkb = "";										
				}else{
					$wkt2=date_create($data['tanggal_kb']);
					$tanggalkb = date_format($wkt2,"j F");				
				}
				@$row .= 
					"<tr>
					<td>".$data['kode_lelang']."</td>
					<td>".$data['tahun_anggaran']."</td>
					<td>".$data['k_l_d_i']."</td>
					<td>".$satker_2."</td>
					<td>".$data['nama_paket']."</td>
					<td>".$this->formula->rupiah2($data['pagu'])."</td>
					<td>".$this->formula->rupiah2($data['nilai'])."</td>
					<td>".number_format(($data['nilai']/$data['pagu'])*100,2)." %"."</td>
					<td>".$data['status_raisa']."</td>
					<td>".$data['portofolio']."</td>
					<td>".$data['treg']."</td>
					<td>".$data['witel']."</td>
					<td>".$data['nomor_kontrak']."</td>
					<td>".$tanggalkb."</td>
					<td>".$data['note']."</td>
				</tr>";
					
			}
			// header("Content-type: application/vnd.ms-excel; name='excel'");
			// header("Content-Disposition: attachment; filename=".$judulfile.".xls");
			// header("Pragma: no-cache");
			// header("Expires: 0");
			echo '
			
			<table border="1px">
				<thead>
<tr>
            <th class="text-center">Kode Lelang</th>
								<th class="text-center">Tahun Anggaran</th>
								<th class="text-center" style="min-width: 250px!important;">Nama K/L/D/I</th>
								<th class="text-center">Satker</th>
								<th class="text-center">Nama Paket</th>
								<th class="text-center">HPS</th>
								<th class="text-center">Nilai Win</th>
								<th class="text-center">% HPS</th>
								<th class="text-center">Status RAISA</th>
								<th class="text-center">Portofolio</th>
								<th class="text-center">TREG</th>
								<th class="text-center">WITEL</th>
								<th class="text-center">Nomor KB</th>
								<th class="text-center">Tanggal KB</th>
								<th class="text-center">Note</th>
          </tr>
			</thead>
				<tbody>'.$row.'
				</tbody>
			</table>
			';
		} else {
			redirect('/panel');
		}
	}
	
	public function datadetaildashboardsegsub2($type,$divisi,$segmen,$treg,$witel,$am,$tahun){
		if(checkingsessionpwt()){
			error_reporting(0);
			
			$wherecond	= "a.kode_raisa !=''";
			
			if ($divisi=='ALL%20DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and c.id_divisi='".$divisi."'";}
			if ($segmen=='ALL%20SEGMEN') { $wheresegmen	= ""; } else { $wheresegmen = "and c.id_segmen='".$segmen."'";}
			if ($treg=='ALL%20TREG') 	{ $wheretreg	= ""; } else { $wheretreg = "and c.treg='".$treg."'"; }
			if ($witel=='ALL%20Witel') { $wherewitel	= ""; } else { $wherewitel = "and c.id_witel='".$witel."'"; }
			if ($am=='ALL%20AM') 	{ $wheream	= ""; } else { $wheream = "and c.nik_am='".getnik($am)."'"; }
			if ($tahun=='') 	{ $wheretahun	= "and tahun='".date('Y')."'"; } else { $wheretahun = "and tahun='".$tahun."'"; }
			
			$dataDetail			= $this->query->getData(
									'
									lop a 
									left join lo b 
									on a.id_lo=b.id_lo 
									left join map c 
									on c.id_map=b.id_map
									left join gc d
									on c.nipnas=d.nipnas
									left join penyedia e
									on e.id_lo=b.id_lo
									',
									'a.id_lelang as kode_lelang,
									c.target_rev as target,
									a.id_sirup as sirup,
									b.ta as tahun_anggaran,
									d.nama_gc AS k_l_d_i,
									b.satker_lo AS satker,
									a.nama_pkt AS nama_paket,
									a.pagu_proj AS pagu,
									a.metode AS metode,
									a.kategori,
									a.waktu as waktu_pemilihan,
									b.sumber_dana AS sumber_dana,
									a.portfolio AS portofolio,
									e.penyedia as penyedia,
									a.nilai_win as nilai,
									e.waktu_lelang as waktu,
									e.alamat as alamat,
									c.treg as treg,
									c.id_divisi as divisi,
									c.id_segmen as segmen,
									c.id_witel as witel,
									(a.nilai_win/a.pagu_proj)*100 as persen,
									a.ket as note,
									(select nama_subs from subs where id_subs=a.subs) as namasub,
									(select nama_am from am where nik_am=c.nik_am) as namaam,
									a.subs as subs,
									a.kode_raisa as kode_raisa,
									(select sr from status_raisa where id_sr=a.id_sr) as status_raisa,
									a.status as status,
									a.nomor_kontrak,
									(select group_concat(file) as file_kontrak from file_lop where id_lop=a.id_lop) as file_kontrak,
									a.tanggal_kb
									',
									"
									WHERE $wherecond $wheredivisi $wheresegmen $wheretreg $wherewitel $wheretahun $wheream order by k_l_d_i asc
									"
								);
			$no=0;
			foreach($dataDetail as $data) { 
				$no++;
				$satker_1 = str_replace("["," ",$data['satker']);
				$satker_2 = str_replace("]"," ",$satker_1);
				
				$nama_pkt = preg_replace('/[^A-Za-z0-9\-\(\) ]/', '', $data['nama_paket']);			
				if($data['file_kontrak']==''){
					$btn = "";
				}else{
					$btn = "<a class='btn btn-xs btn-success btnFilePreview' data-toggle='modal' data-target='#FilePreview' data-file='".$data['file_kontrak']."' data-ext='".pathinfo($data['file_kontrak'], PATHINFO_EXTENSION)."' data-nomor='".$data['nomor_kontrak']."' ><i data-toggle='tooltip' title='".$data['file_kontrak']."' class='glyphicon glyphicon-fullscreen'></i>&nbsp; View File</a>";
				}
				if($data['waktu_pemilihan'] == NULL or $data['waktu_pemilihan'] =="" or $data['waktu_pemilihan']== 0){
					$waktupemilihan = "";										
				}else{
					$wkt=date_create($data['waktu_pemilihan']);
					$waktupemilihan = date_format($wkt,"m-Y");				
				}
				if($data['tanggal_kb'] == NULL or $data['tanggal_kb'] =="" or $data['tanggal_kb']== 0){
					$tanggalkb = "";										
				}else{
					$wkt2=date_create($data['tanggal_kb']);
					$tanggalkb = date_format($wkt2,"d-m-Y");				
				}
				
				$row = array(
					$data['kode_lelang'],
					$data['tahun_anggaran'],
					$data['k_l_d_i'],
					$satker_2,
					$data['nama_paket'],
					$this->formula->rupiah2($data['pagu']),
					$this->formula->rupiah2($data['nilai']),
					number_format(($data['nilai']/$data['pagu'])*100,2)." %",
					$data['status_raisa'],
					// $data['kode_raisa'],
					// $data['status'],
					$data['portofolio'],
					$data['treg'],
					$data['witel'],
					$data['nomor_kontrak'],
					$tanggalkb,
					$data['note']
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$row = array("","","","","","","","","","","","","","","","","","","","","","","","","","","","");
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function datasegcachdetail_export(){
		if(checkingsessionpwt()){
			
			$type 		= $_GET['type'];
			$divisi 	= @$_GET['divisi'];
			$segmen 	= $_GET['segmen'];
			$treg 		= $_GET['treg'];
			$witel 		= $_GET['witel'];
			$am 		= $_GET['am'];
			$tahun 		= $_GET['periode'];
			//GET UAM
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			
			if ($divisi=='ALL DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL SEGMEN') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL TREG') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL Witel') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL AM') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			if ($tahun=='') 	{ $wheretahun	= "and tahun='".date('Y')."'"; } else { $wheretahun = "and tahun='".$tahun."'"; }
			if ($type=='newgtma') {
				$condgtma	= "and status='NEW-GTMA'";
				$gtma		= 'NEW-GTMA';
			} else {
				$condgtma	= '';
				$gtma		= '';
			}
			$row = "";
			$dataCat			= 	$this->query->getData(
									"
									(
									select nik_am as am, (select nama_am from am where nik_am=calc.nik_am) as nama_am, nama_gc as kdli,nipnas,id_map, sum(DISTINCT target_rev) as target, sum(
										case When id_sr = '15'
										then nilai_win else 0 End
									) as nilai,id_divisi,id_segmen,treg,id_witel,tahun  from (
									select filter.* from (
									select nik_am, nama_am, nilai_win, id_divisi, id_segmen, id_witel, treg, target_rev,nama_gc,nipnas,id_map,id_sr,status,tahun from (
									select d.id_lop, a.nik_am, b.id_map, nama_am, id_divisi, id_segmen, b.treg, id_witel, nilai_win, target_rev,b.nipnas,e.nama_gc,d.kode_raisa,d.id_sr,d.status,b.tahun from am a left join map b on a.nik_am=b.nik_am left join lo c on b.id_map=c.id_map left join lop d on c.id_lo=d.id_lo left join gc e on b.nipnas = e.nipnas
									) as master
									) as filter
									) as calc
									where nik_am!='' $condgtma $wheredivisi $wheredivisi $wheresegmen $wheretreg $wherewitel $wheream $wheretahun $uam
									group by nik_am,nama_gc,nipnas,id_map
									) as final
									",
									"
									final.*, (nilai-target) as achyesno, round(((nilai/target)*100),2) as persen,
									case when nilai-target > 0 then 'ACHIEVED' else 'NOT ACHIEVED' end as status
									",
									"ORDER BY persen desc"
									);
			$no=0;
			
			foreach($dataCat as $data) { 
				$no++;
				$id	= $data['am'];
				
				if ($data['status']=='ACHIEVED') { $color='success'; } else { $color='danger'; }
				
				$qNS	= $this->query->getData('segmen','nama_segmen',"where id_segmen='".$data['id_segmen']."'");
				$gNS	= array_shift($qNS);
				$Nsegmen= $gNS['nama_segmen'];
				
				$qNW	= $this->query->getData('witel','nama_witel',"where id_witel='".$data['id_witel']."'");
				$gNW	= array_shift($qNW);
				$Nwitel	= $gNW['nama_witel'];
	
				@$row .= 
					"<tr>
					<td>".$Nsegmen."</td>
					<td>".$data['treg']."</td>
					<td>".$Nwitel."</td>
					<td>".$data['nama_am']."</td>
					<td>".$data['am']."</td>
					<td>".$data['kdli']."</td>
					<td>".$this->formula->rupiah2($data['target'])."</td>
					<td>".$this->formula->rupiah2($data['nilai'])."</td>
					<td>".$data['persen']."%</td>
					<td>".$data['status']."</td></tr>";
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=Achievement ".$gtma." ".$segmen." (".$treg.").xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '
			<table border="1px" style="width: 100%;">
				<thead class="bg-gray-dark text-white">
					<tr>
						<th class="text-center">Segmen</th>
						<th class="text-center">TREG</th>
						<th class="text-center">WITEL</th>
						<th class="text-center">Nama AM</th>
						<th class="text-center">NIK AM</th>
						<th class="text-center">Nama K/L/D/I</th>
						<th class="text-center">Target</th>
						<th class="text-center">Achievement</th>
						<th class="text-center">Persentase</th>
						<th class="text-center">Status</th>
					</tr>
				</thead>
				<tbody>
				'.$row.'
				</tbody>
			</table>
			';
		} else {
			redirect('/panel');
		}
	}
	
	public function modaldatadetailgc(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id				= trim(strip_tags(stripslashes($this->input->post('type',true))));
			$getName		= $this->query->getData('am','nama_am',"WHERE nik_am='$id'");
			$dataname		= array_shift($getName);
			$name			= $dataname['nama_am'];
			
			$data	= array(
				'id'	=> $id,
				'title' => $name
			);
			
			header('Content-type: application/json; charset=UTF-8');
			
			echo json_encode($data);
		} else {
			redirect('/panel');
		}
	}
	
	public function mappingLGS(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$qR0 = "SELECT lo_temp.id_lo , gc.nipnas FROM `lo_temp` ,gc where lo_temp.nama_gc = gc.nama_gc";
			$xR0 = $this->db->query($qR0)->result_array();
			$nR0 = $this->db->query($qR0)->num_rows();
			$data['total'] = $nR0;
			foreach($xR0 as $dataname){
				$q_map = "SELECT * FROM map WHERE nipnas='".$dataname['nipnas']."' limit 1";
				$x_map = $this->db->query($q_map)->result_array();
				$map		= array_shift($x_map);
				$data['data'][]	= array(
					'id_lo'	=> $dataname['id_lo'],
					'nipnas' => $dataname['nipnas'],
					'id_map' => $map['id_map']
				);
			}
			header('Content-type: application/json; charset=UTF-8');
			
			echo json_encode($data);
		} else {
			redirect('/panel');
		}
	}
	
	public function dataamgcdetail_export(){
		if(checkingsessionpwt()){
			$type = $_GET['type'];
			$name = $_GET['name'];
			$divisi = $_GET['divisi'];
			$segmen = $_GET['segmen'];
			$treg = $_GET['treg'];
			$witel = $_GET['witel'];
			$am = $_GET['am'];
			
			date_default_timezone_set("Asia/Bangkok");
			
			if ($divisi=='ALL%20DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL%20SEGMEN') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL TREG') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL WITEL') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL AM') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			if ($tahun=='') 	{ $wheretahun	= "and tahun='".date('Y')."'"; } else { $wheretahun = "and tahun='".$tahun."'"; }
			
			$dataCat			= 	$this->query->getData(
									"
									(
									select b.nipnas, d.id_lop, d.kode_raisa, d.id_sr, d.status, a.nik_am, b.id_map, nama_am, id_divisi, id_segmen, b.treg, id_witel, nilai_win, target_rev, b.tahun from am a
									left join map b
									on a.nik_am=b.nik_am
									left join lo c 
									on b.id_map=c.id_map
									left join lop d
									on c.id_lo=d.id_lo
									) as master
									) as filter
									) as calc
									where nik_am='$type' $wheredivisi $wheresegmen $wheretreg $wherewitel
									) as final
									group by nip
									) as finish
									",
									"
									finish.*, round(((nilai/target)*100),2) as persen,
									case when nilai-target > 0 then 'ACHIEVED' else 'NOT ACHIEVED' end as status from (
									select nipnas as nip, nama_gc as namagc, sum(DISTINCT target_rev) as target, sum(nilai_win) as nilai from (
									select calc.*, (select nama_gc from gc where nipnas=calc.nipnas) as nama_gc from (
									select filter.* from (
									select nik_am, nama_am, case when id_sr='15' then nilai_win else 0 end as nilai_win, segmen, witel, treg, target_rev,nipnas,tahun
									",
									""
									);
			$no=0;
			
			foreach($dataCat as $data) { 
				$no++;
				
				if ($data['status']=='ACHIEVED') { $color='success'; } else { $color='danger'; }
	
				@$row .=
					"<tr><td>".$no."</td>
					<td>".$data['nip']."</td>
					<td>".$data['namagc']."</td>
					<td>".$this->formula->rupiah2($data['target'])."</td>
					<td>".$this->formula->rupiah2($data['nilai'])."</td>
					<td>".$data['persen']."%</td>
					<td>".$data['status']."</td></tr>";
				
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=".$type."-".$name.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '
			<table border="1px" style="width: 100%;">
				<thead class="bg-gray-dark text-white">
					<tr>
						<th>No</th>
						<th>Nipnas</th>
						<th>GC</th>
						<th>Target</th>
						<th>Achieved</th>
						<th>Persentase</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
				'.$row.'
				</tbody>
			</table>
			';
			
		} else {
			redirect('/panel');
		}
	}
	
	
	//==================RAISA V2========================//
	//START PROJECT 	: 30 July 2018					//
	//DEVELOPER TEAM	: - Alamsyah S Putra			//
	//					  - Panji Pujianto				//
	//==================================================//
	
	// MANAGE USER
	public function insertuser(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$iduser		= trim(strip_tags(stripslashes($this->input->post('id_user',true))));
			$name		= trim(strip_tags(stripslashes($this->input->post('name',true))));
			$email		= trim(strip_tags(stripslashes($this->input->post('email',true))));
			$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['pict']['name']);
			$username	= trim(strip_tags(stripslashes($this->input->post('user',true))));
			$password	= md5($_POST['pass']);
			$role		= trim(strip_tags(stripslashes($this->input->post('role',true))));
			
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
				
			$config['upload_path'] = './images/user/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media 			= $this->upload->data();
			$fileNamePost 	= $media['file_name'];
			
			$rows 		= $this->query->insertData('user', "userid,name,username,email,password,picture,id_role", "'$iduser','$name','$username','$email','$password','$fileNamePosst','$role'");
			$id			= $this->db->insert_id();
			$url 		= "Manage User";
			$activity 	= "INSERT";
			if($rows) {
				$org = $this->query->insertData('user_organization', "id_user_org,userid,akses_divisi,akses_segmen,akses_treg,akses_witel,akses_am", "'','$iduser','$divisi','$segmen','$treg','$witel','$am'");
				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	

	public function modaluser(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataUsr			= $this->query->getData('user a LEFT JOIN user_organization b ON a.userid = b.userid','a.userid,a.username,a.name,a.password,a.picture,a.id_role,a.email,b.akses_divisi,b.akses_segmen,b.akses_witel,b.akses_treg,b.akses_am',"WHERE a.userid='".$id."' ORDER BY a.userid DESC");
			
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
	public function updateuser(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$cekinglogo		= $_FILES['upl']['name'];
			$cekingpass		= $_POST['ed_pass'];
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_iduser',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('ed_name',true))));
			$email			= trim(strip_tags(stripslashes($this->input->post('ed_email',true))));
			$user			= trim(strip_tags(stripslashes($this->input->post('ed_user',true))));
			$role			= trim(strip_tags(stripslashes($this->input->post('ed_role',true))));
			
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
				
				$rows = $this->query->updateData('user',"name='$name', email='$email', username='$user', picture='$fileName', id_role='$role' $upPass","WHERE userid='".$id."'");
				
				if($rows) {
					$deletefirst 	= $this->query->deleteData('user_organization','userid',$id);
					$insertNew 		= $this->query->insertData('user_organization', "id_user_org,userid,akses_divisi,akses_segmen,akses_treg,akses_witel,akses_am", "'','$id','$divisi','$segmen','$treg','$witel','$am'");
					
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}	
			} else {
				$rows = $this->query->updateData('user',"name='$name', email='$email', username='$user', id_role='$role' $upPass","WHERE userid='$id'");
				if($rows) {
					$deletefirst 	= $this->query->deleteData('user_organization','userid',$id);
					$insertNew 		= $this->query->insertData('user_organization', "id_user_org,userid,akses_divisi,akses_segmen,akses_treg,akses_witel,akses_am", "'','$id','$divisi','$segmen','$treg','$witel','$am'");
				
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
	public function deleteuser(){
		if(checkingsessionpwt()){
			$url 		= "Manage User";
			$activity 	= "DELETE";
			
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$coba = $this->query->getData('user','picture',"WHERE userid='".$cond."'");
			
			foreach ($coba as $dataex) {
				$dataexis = 'images/user/'.$dataex['picture'];
				unlink($dataexis);
			}
			
			$rows = $this->query->deleteData('user','userid',$cond);
			
			if(isset($rows)) {
				$rows = $this->query->deleteData('user_organization','userid',$cond);
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}
	
	//MANAGE ROLE
	public function insertroles(){
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
	public function updaterole(){
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
	public function deleterole(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt');
			$cond		= trim(strip_tags(stripslashes($this->input->post('iddelroles',true))));
			
			$rows = $this->query->deleteData('role','id_role',$cond);
			$rows = $this->query->deleteData('role_menu','id_role',$cond);
			$url 		= "Manage Role";
			$activity 	= "DELETE";
			
			$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$cond','$ipaddr'");
			
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
	public function modalrole(){
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
	
	
	//USER PROFILE
	public function getProfile(){
		if(checkingsessionpwt()){

			$id			= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$getdatalop = $this->query->getData('user','*','WHERE userid ="'.$id.'"');

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
	
	public function cekincldata() {
		$columnsDefault = [
			'RecordID'     => true,
			'OrderID'      => true,
			'Country'      => true,
			'ShipCity'     => true,
			'ShipAddress'  => true,
			'CompanyAgent' => true,
			'CompanyName'  => true,
			'ShipDate'     => true,
			'Status'       => true,
			'Type'         => true,
			'Actions'      => true,
		];
		$arraynya	= $columnsDefault;
		$jsonfile	= base_url().'default.json';

		$this->generateDatatable($arraynya,$jsonfile);
	}

	public function generateDatatable($columnsDefault,$jsonfile) {
		error_reporting(0);

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = true;
			}
		}

		// get all raw data
		// $arrContextOptions=array(
			// "ssl"=>array(
				// "verify_peer"=>false,
				// "verify_peer_name"=>false,
			// ),
		// );  
		
		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		);   
 
		//$get = file_get_contents( $jsonfile ); 
		$get = file_get_contents( $jsonfile, false, stream_context_create($arrContextOptions)); 
		
		$alldata = json_decode( $get , true );   
		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			$data[] = $this->datatable->filterArray( $d, $columnsDefault );
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = $this->datatable->filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = $this->datatable->filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			$dir    = $_REQUEST['order'][0]['dir'];
			usort( $data, function ( $a, $b ) use ( $column, $dir ) {
				$a = array_slice( $a, $column, 1 );
				$b = array_slice( $b, $column, 1 );
				$a = array_pop( $a );
				$b = array_pop( $b );

				if ( $dir === 'asc' ) {
					return $a > $b ? true : false;
				}

				return $a < $b ? true : false;
			} );
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$secho = 0;
		if ( isset( $_REQUEST['sEcho'] ) ) {
			$secho = intval( $_REQUEST['sEcho'] );
		}

		$result = [
			'iTotalRecords'        => $totalRecords,
			'iTotalDisplayRecords' => $totalDisplay,
			'sEcho'                => $secho,
			'sColumns'             => '',
			'aaData'               => $data,
		];

		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

		echo json_encode( $result, JSON_PRETTY_PRINT );
	}

	public function getdatauser(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'picture'		=> true,
				'name'			=> true,
				'username'		=> true,
				'email'			=> true,
				'role'			=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datauser';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdataroles(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'nama_role'		=> true,
				'desc_role'		=> true,
				'update_by'		=> true,
				'last_update'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataroles';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatakontak(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'nama'			=> true,
				'perusahaan'	=> true,
				'alamat'		=> true,
				'email'			=> true,
				'phone'			=> true,
				'group'			=> true,
				'saldo'			=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datakontak';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatamenus(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'flag_web'		=> true,
				'menu'			=> true, 
				'parent'		=> true,
				'sort'			=> true,
				'link'			=> true,
				'style'			=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datamenus'; 
			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalmenus(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from (
								SELECT 
									base.*, 
								    (case when parent='0' then 'Parent Menu' else (select menu from menu_site where id_menu=base.parent) end )as parentname 
								FROM `menu_site` as base
							) as final
							where id_menu='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function getMenuAfter(){
		if(checkingsessionpwt()){
			
			$id 	 = $_GET['id'];
			
			$cond	= "WHERE parent='$id'";
			
			$getData = $this->query->getData('menu_site','*',"$cond ORDER BY id_menu DESC");
			
			header('Content-type: application/json; charset=UTF-8');

			$cek 	= $this->query->getNumRowsbyQ("select * from menu_site $cond order by id_menu desc")->num_rows();

			if ($cek>0) {
				$row[] 	= array('id' => '','text' => '-- Choose Menu --' );

				foreach($getData as $data) {
					$sort 	= $data['sort']+1;
					$row[] 	= array(
						'id'		=> $sort,
						'text'		=> 'After '.$data['menu'],
						);
					$json = $row;
					
				}
				$json = $row;
			} else {
				$row[] 	= array('id' => '1','text' => 'At beginning of Menu' );

				$json = $row;
			}
			
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}

	public function insertmenus(){
		if(checkingsessionpwt()){
			$userdata		= $this->session->userdata('sesspwt'); 
			$userid 		= $userdata['userid'];

			$menu			= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			$menu_en		= trim(strip_tags(stripslashes($this->input->post('menu_en',true))));
			$kategori_website = trim(strip_tags(stripslashes($this->input->post('kategori_website',true))));
			$desc			= trim(strip_tags(stripslashes($this->input->post('desc',true))));
			$desc_en		= trim(strip_tags(stripslashes($this->input->post('desc_en',true))));
			$fileName 		= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['pict']['name']);
			$link			= trim(strip_tags(stripslashes($this->input->post('link',true))));
			$style			= trim(strip_tags(stripslashes($this->input->post('menutype',true))));
			$parent			= trim(strip_tags(stripslashes($this->input->post('parent',true))));
			$sort			= trim(strip_tags(stripslashes($this->input->post('sort',true))));
			$depart 		= implode($_POST['department_show']);
			
			$config['upload_path'] = './images/content/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media 			= $this->upload->data();
			$fileNamePost 	= $media['file_name'];
			
			$rows 		= $this->query->insertData('menu_site', "id_menu,menu,description,background,link,style,parent,sort,menu_en,description_en,flag_website,ourteam_flag", 
													"'','$menu','$desc','$fileNamePosst','$link','$style','$parent','$sort','$menu_en','$desc_en','$kategori_website','$depart'");
			$id			= $this->db->insert_id();
			$url 		= "Manage Menus";
			$activity 	= "INSERT";
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

	public function deletemenus(){
		if(checkingsessionpwt()){
			$url 		= "Manage Menus";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			$coba = $this->query->getData('menu_site','background',"WHERE id_menu='".$cond."'");
			
			foreach ($coba as $dataex) {
				$dataexis = 'images/content/'.$dataex['background'];
				unlink($dataexis);
			}
			
			$rows = $this->query->deleteData('menu_site','id_menu',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function updatemenus(){
		if(checkingsessionpwt()){
			$userdata		= $this->session->userdata('sesspwt'); 

			$id				= trim(strip_tags(stripslashes($this->input->post('ed_idmenu',true))));
			$menu			= trim(strip_tags(stripslashes($this->input->post('ed_menu',true))));
			$parent			= trim(strip_tags(stripslashes($this->input->post('ed_parent',true))));
			$menu_en		= trim(strip_tags(stripslashes($this->input->post('ed_menu_en',true))));
			$parent_en		= trim(strip_tags(stripslashes($this->input->post('ed_parent_en',true))));
			$link			= trim(strip_tags(stripslashes($this->input->post('ed_link',true))));
			$style			= trim(strip_tags(stripslashes($this->input->post('ed_menutype',true))));
			$sort			= trim(strip_tags(stripslashes($this->input->post('ed_sort',true))));
			$desc			= trim(strip_tags(stripslashes($this->input->post('ed_desc',true))));
			$kategori		= trim(strip_tags(stripslashes($this->input->post('ed_kategori_website',true))));
			@$cekinglogo	= $_FILES['upl']['name'];
			$jumd = count($_POST['ed_department_show']);
			if($jumd > 1){
				$depart 		= implode($_POST['ed_department_show']);				
			}else{
				$depart 		= trim(strip_tags(stripslashes($this->input->post('ed_department_show',true))));
			}
			 
			$userid		= $userdata['userid'];
				
			$url 		= "Manage Menus";
			$activity 	= "UPDATE";
			
			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('menu_site','background','WHERE id_menu='.$id.'');
				foreach ($coba as $dataex) {
					$dataexis = 'images/content/'.$dataex['background'];
				}
				unlink($dataexis);
			
				$fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/content/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('upl');
				
				$rows = $this->query->updateData('menu_site',"menu='$menu', parent='$parent', link='$link', style='$style', background='$fileName', sort='$sort', description='$desc',menu_en='$menu_en',description_en='$description_en',flag_website='$kategori',ourteam_flag='$depart'","WHERE id_menu='".$id."'");
				
				if($rows) {
					$log = $this->query->insertlog($activity,$url,$id);
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}	
			} else {
				$rows = $this->query->updateData('menu_site',"menu='$menu', parent='$parent',link='$link', style='$style', sort='$sort' , description='$desc',menu_en='$menu_en',description_en='$description_en',flag_website='$kategori',ourteam_flag='$depart'","WHERE id_menu='$id'");

				if($rows) {
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

	public function getdatacontent(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'title'			=> true,
				'menu'			=> true,
				'headline'		=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datacontent'; 
			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalcontent(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from content
							where id_content='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function insertcontent(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$title		= trim(strip_tags(stripslashes($this->input->post('title',true))));
			$title_en		= trim(strip_tags(stripslashes($this->input->post('title_en',true))));
			$menu		= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			$headline	= trim(strip_tags(stripslashes($this->input->post('headline',true))));
			$content	= str_replace("'",'`',$_POST['content']);
			$headline_en	= trim(strip_tags(stripslashes($this->input->post('headline_en',true))));
			$content_en	= str_replace("'",'`',$_POST['content_en']);


			$q 			= "
						insert into content (title,sub,id_menu,headline,content,title_en,headline_en,content_en) values ('$title','',$menu,'$headline','$content','$title_en','$headline_en','$content_en')
						";
			$rows 		= $this->query->insertDatabyQ($q);

			if($rows) {
				$id			= $this->db->insert_id();
				$url 		= "Manage Content";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
				//echo "insert into content (title,sub,headline,content,id_menu) values ('$title','',$menu,'$headline','$content')";
			}
		} else {
			redirect('/panel');
		}
	}

	public function deletecontent(){
		if(checkingsessionpwt()){
			$url 		= "Manage Content";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			$rows = $this->query->deleteData('content','id_content',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function updatecontent(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$title		= trim(strip_tags(stripslashes($this->input->post('ed_title',true))));
			$title_en		= trim(strip_tags(stripslashes($this->input->post('ed_title_en',true))));
			$menu		= trim(strip_tags(stripslashes($this->input->post('ed_menu',true))));
			$headline	= trim(strip_tags(stripslashes($this->input->post('ed_headline',true))));
			$headline_en	= trim(strip_tags(stripslashes($this->input->post('ed_headline_en',true))));
			$content	= str_replace("'",'`',$_POST['ed_content']);
			$content_en	= str_replace("'",'`',$_POST['ed_content_en']);

			$userid		= $userdata['userid'];
				
			$url 		= "Manage Content";
			$activity 	= "UPDATE";
			
			$rows = $this->query->updateData('content',"id_menu='$menu', title='$title',headline='$headline', content='$content', title_en='$title_en',headline_en='$headline_en', content_en='$content_en'","WHERE id_content='$id'");

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

	public function cekAvailMenu(){
		if(checkingsessionpwt()){
			
			$id		= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$cond	= "WHERE id_menu='$id'";
			
			header('Content-type: application/json; charset=UTF-8');
			//$getData = $this->query->getData('content','count(*)',"$cond");
			
			$cek 	= $this->query->getNumRowsbyQ("select * from content $cond")->num_rows();

			if ($cek<1) {
				$row 	= array('status' => 'available');
				$json 	= $row;
			} else {
				$row 	= array('status' => 'not');
				$json 	= $row;
			}
			
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}

	public function getdatalog(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'date'		=> true,
				'photo'		=> true,
				'user'		=> true,
				'activity'	=> true,
				'id'		=> true,
				'data'		=> true,
				'menu'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datalog';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatablog(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'picture'		=> true,
				'title'			=> true,
				'menu'			=> true,
				'headline'		=> true,
				'category' 		=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datablog';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalblog(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from blog
							where id_blog='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function insertblog(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$title		= trim(strip_tags(stripslashes($this->input->post('title',true))));
			$menu		= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			$headline	= trim(strip_tags(stripslashes($this->input->post('headline',true))));
			$content	= str_replace("'",'`',$_POST['content']);
			$title_en	= trim(strip_tags(stripslashes($this->input->post('title_en',true))));
			$headline_en= trim(strip_tags(stripslashes($this->input->post('headline_en',true))));
			$content_en	= str_replace("'",'`',$_POST['content_en']);
			$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['pict']['name']);
			$date 		= date('Y-m-d');
			$category	= trim(strip_tags(stripslashes($this->input->post('category',true))));

			$link		= $this->formula->clean(strtolower($title));
				
			$config['upload_path'] = './images/content/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media 			= $this->upload->data();
			$fileNamePost 	= $media['file_name'];

			$q 			= "
						insert into blog (title,sub,id_menu,headline,link,content,picture,create_by,create_date,title_en,headline_en,content_en,id_category) values ('$title','',$menu,'$headline','$link','$content','$fileNamePosst','$userid','$date','$title_en','$headline_en','$content_en','$category')
						";
						//echo $q;
			$rows 		= $this->query->insertDatabyQ($q);

			if($rows) {
				$id			= $this->db->insert_id();
				$url 		= "Manage Berita";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
				//echo "insert into content (title,sub,headline,content,id_menu) values ('$title','',$menu,'$headline','$content')";
			}
		} else {
			redirect('/panel');
		}
	}

	public function deleteblog(){
		if(checkingsessionpwt()){
			$url 		= "Manage Berita";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			//delete eksisting
			$coba = $this->query->getData('blog','picture','WHERE id_blog='.$cond.'');
			foreach ($coba as $dataex) {
				$dataexis = 'images/content/'.$dataex['picture'];
			}
			@unlink($dataexis);

			$rows = $this->query->deleteData('blog','id_blog',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function updateblog(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$cekinglogo	= $_FILES['upl']['name'];
			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$title		= trim(strip_tags(stripslashes($this->input->post('ed_title',true))));
			$title_en		= trim(strip_tags(stripslashes($this->input->post('ed_title_en',true))));
			$menu		= trim(strip_tags(stripslashes($this->input->post('ed_menu',true))));
			$headline	= trim(strip_tags(stripslashes($this->input->post('ed_headline',true))));
			$headline_en	= trim(strip_tags(stripslashes($this->input->post('ed_headline_en',true))));
			$category	= trim(strip_tags(stripslashes($this->input->post('ed_category',true))));
			$content	= str_replace("'",'`',$_POST['ed_content']);
			$content_en	= str_replace("'",'`',$_POST['ed_content_en']);
			$link		= $this->formula->clean(strtolower($title));

			$userid		= $userdata['userid'];
				
			$url 		= "Manage Berita";
			$activity 	= "UPDATE";

			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('blog','picture','WHERE id_blog='.$id.'');
				foreach ($coba as $dataex) {
					$dataexis = 'images/content/'.$dataex['picture'];
				}
				@unlink($dataexis);
			
				$fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/content/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('upl');

				$rows = $this->query->updateData('blog',"id_menu='$menu', title='$title', link='$link',headline='$headline', content='$content', picture='$fileName' , title_en='$title_en', link_en='$link_en',headline_en='$headline_en', content_en='$content_en', id_category='$category'","WHERE id_blog='$id'");
			} else {
				$rows = $this->query->updateData('blog',"id_menu='$menu', title='$title', link='$link',headline='$headline', content='$content', id_category='$category'","WHERE id_blog='$id'");
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

	public function getdatalink(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'picture'		=> true,
				'title'			=> true,
				'link'			=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datalink';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}
	public function getdataourteam(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'picture'		=> true,
				'name'			=> true,
				'email'			=> true,
				'position'		=> true,
				'departemen'	=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datourteam';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modallink(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from link
							where id_link='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function insertlink(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$gtitle		= trim(strip_tags(stripslashes($this->input->post('title',true))));
			$title 		= str_replace("'", '`', $gtitle);
			$link		= trim(strip_tags(stripslashes($this->input->post('link',true))));
			$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['pict']['name']);
			$date 		= date('Y-m-d');
				
			$config['upload_path'] = './images/link/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media 			= $this->upload->data();
			$fileNamePost 	= $media['file_name'];

			$q 			= "
						insert into link (title,link,picture) values ('$title','$link','$fileNamePost')
						";
						//echo $q;
			$rows 		= $this->query->insertDatabyQ($q);

			if($rows) {
				print json_encode($media);
				$filedir 	= 'link';
				//$this->makeThumbnails($filedir,$fileName);

				$id			= $this->db->insert_id();
				$url 		= "Manage Link";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
				//echo "insert into content (title,sub,headline,content,id_menu) values ('$title','',$menu,'$headline','$content')";
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function deletelink(){
		if(checkingsessionpwt()){
			$url 		= "Manage Link";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			//delete eksisting
			$coba = $this->query->getData('link','picture','WHERE id_link='.$cond.'');
			foreach ($coba as $dataex) {
				$dataexis 		= 'images/link/'.$dataex['picture'];
				$dataexisthumb 	= 'images/link/thumb_'.$dataex['picture'];
				@unlink($dataexis);
				@unlink($dataexisthumb);
			}

			$rows = $this->query->deleteData('link','id_link',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function updatelink(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$cekinglogo	= $_FILES['upl']['name'];
			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$title		= trim(strip_tags(stripslashes($this->input->post('ed_title',true))));
			$link		= trim(strip_tags(stripslashes($this->input->post('ed_link',true))));
			
			$userid		= $userdata['userid'];
				
			$url 		= "Manage Link";
			$activity 	= "UPDATE";

			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('link','picture','WHERE id_link='.$id.'');
				foreach ($coba as $dataex) {
					$dataexis 		= 'images/link/'.$dataex['picture'];
					$dataexisthumb	= 'images/link/thumb_'.$dataex['picture'];
					unlink($dataexis);
					unlink($dataexisthumb);
				}
			
				// $fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/link/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				// $media = $this->upload->data('upl');
				$media 			= $this->upload->data();
				$fileNamePost 	= $media['file_name'];

				$rows = $this->query->updateData('link',"title='$title', link='$link', picture='$fileNamePost'","WHERE id_link='$id'");
			} else {
				$rows = $this->query->updateData('link',"title='$title', link='$link'","WHERE id_link='$id'");
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
	public function insertourteam(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$gname			= trim(strip_tags(stripslashes($this->input->post('name',true))));
			$name 			= str_replace("'", '`', $gname);
			$sort			= trim(strip_tags(stripslashes($this->input->post('sort',true))));
			$email			= trim(strip_tags(stripslashes($this->input->post('email',true))));
			$position		= trim(strip_tags(stripslashes($this->input->post('position',true))));
			$position_en	= trim(strip_tags(stripslashes($this->input->post('position_en',true))));
			$department		= trim(strip_tags(stripslashes($this->input->post('department',true))));
			$department_en	= trim(strip_tags(stripslashes($this->input->post('department_en',true))));
			$fileName 		= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['pict']['name']);
			$date 			= date('Y-m-d');
				
			$config['upload_path'] = './images/ourteam/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media 			= $this->upload->data();
			$fileNamePost 	= $media['file_name'];

			$q 			= "
						insert into ourteam (name,email,position,position_en,department,department_en,sort,picture) values ('$name','$email','$position','$position_en','$department','$department_en','$sort','$fileNamePost')
						";
						//echo $q;
			$rows 		= $this->query->insertDatabyQ($q);

			if($rows) {
				print json_encode($media);
				$filedir 	= 'link';
				//$this->makeThumbnails($filedir,$fileName);

				$id			= $this->db->insert_id();
				$url 		= "Manage Our Team";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
				//echo "insert into content (title,sub,headline,content,id_menu) values ('$title','',$menu,'$headline','$content')";
			}
		} else {
			redirect('/panel');
		}
	}
	public function deleteourteam(){
		if(checkingsessionpwt()){
			$url 		= "Manage Our Team";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			//delete eksisting
			$coba = $this->query->getData('ourteam','picture','WHERE id='.$cond.'');
			foreach ($coba as $dataex) {
				$dataexis 		= 'images/ourteam/'.$dataex['picture'];
				$dataexisthumb 	= 'images/ourteam/thumb_'.$dataex['picture'];
				@unlink($dataexis);
				@unlink($dataexisthumb);
			}

			$rows = $this->query->deleteData('ourteam','id',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function updateourteam(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$cekinglogo	= $_FILES['upl']['name'];
			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$sort		= trim(strip_tags(stripslashes($this->input->post('ed_sort',true))));
			$name		= trim(strip_tags(stripslashes($this->input->post('ed_name',true))));
			$email		= trim(strip_tags(stripslashes($this->input->post('ed_email',true))));
			$position	= trim(strip_tags(stripslashes($this->input->post('ed_position',true))));
			$position_en	= trim(strip_tags(stripslashes($this->input->post('ed_position_en',true))));
			$department		= trim(strip_tags(stripslashes($this->input->post('ed_department',true))));
			$department_en		= trim(strip_tags(stripslashes($this->input->post('ed_department_en',true))));
			
			
			$userid		= $userdata['userid'];
				
			$url 		= "Manage Our Team";
			$activity 	= "UPDATE";

			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('ourteam','picture','WHERE id='.$id.'');
				foreach ($coba as $dataex) {
					$dataexis 		= 'images/ourteam/'.$dataex['picture'];
					$dataexisthumb	= 'images/ourteam/thumb_'.$dataex['picture'];
					unlink($dataexis);
					unlink($dataexisthumb);
				}
			
				// $fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/ourteam/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				// $media = $this->upload->data('upl');
				$media 			= $this->upload->data();
				$fileNamePost 	= $media['file_name'];

				$rows = $this->query->updateData('ourteam',"name='$name', email='$email',position='$position', position_en='$position_en',department='$department', department_en='$department_en', sort='$sort', picture='$fileNamePost'","WHERE id='$id'");
			} else {
				$rows = $this->query->updateData('ourteam',"name='$name', email='$email',position='$position', position_en='$position_en',department='$department', sort='$sort', department_en='$department_en'","WHERE id='$id'");
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
	public function getdatamail(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'email'			=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datamail';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalmail(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from mail_site
							where id_email='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function insertmail(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$email		= trim(strip_tags(stripslashes($this->input->post('email',true))));
			$fwd		= $_POST['fwd'];
			
			$query = $this->query->getData('mail_site','max(id_email)+1 as id_email','');
			$getID = array_shift($query);
			if ($getID['id_email']=='') {
				$id = '1';
			} else {
				$id = $getID['id_email'];
			}

			$q 			= "
						insert into mail_site (id_email,email) values ('$id','$email')
						";
						//echo $q;
			$rows 		= $this->query->insertDatabyQ($q);
			
			if($rows) {
				$url 		= "Manage Mail Site";
				$activity 	= "INSERT";

				$jmlFwd = count($fwd);
				for($x = 0 ;$x < $jmlFwd; $x++){
					$qFwd 		= "insert into mail_fwd (email,id_email) values ('".$fwd[$x]."','$id')";
					$insertFwd 	= $this->query->insertDatabyQ($qFwd);
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

	public function deletemail(){
		if(checkingsessionpwt()){
			$url 		= "Manage Mail Site";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			$rows = $this->query->deleteData('mail_site','id_email',$cond);
			
			if(isset($rows)) {
				$row = $this->query->deleteData('mail_fwd','id_email',$cond);
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function getdataEksFwd($id){
		if(checkingsessionpwt()){
			
			$dataRolesAll		= $this->query->getData('mail_fwd','*',"WHERE id_email='$id' ORDER BY id_email DESC");
			$x 					= 0;
			foreach($dataRolesAll as $data) { $x++;
				echo '
				<div class="form-group row bgeditnew" id="exed_newrow'.$x.'">
                    <label class="col-form-label col-lg-3 col-sm-12"></label>
                    <div class="col-lg-4 col-md-9 col-sm-12">
                        <div class="input-group">
                            <input type="email" name="ed_fwd[]" class="form-control" id="ed_fwd" placeholder="Forward To" value="'.$data['email'].'">
                            <button type="button" class="btn btn-sm btn-danger text-white ed_deleterow" data-toggle="kt-tooltip" title="Remove Email" data-id="#exed_newrow'.$x.'">
                            	<i class="fa fa-times text-white"></i>
                            </button>
                        </div>
                    </div>
                </div>
				';
			}
			echo '<input type="hidden" name="eksisdatarow" id="eksisdatarow" value="'.$x.'">';
		} else {
			redirect('/panel');
		}
	}

	public function updatemail(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid		= $userdata['userid'];

			$id				= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$email			= trim(strip_tags(stripslashes($this->input->post('ed_email',true))));
			$fwd			= $_POST['ed_fwd'];
				
			$url 		= "Manage Mail Site";
			$activity 	= "UPDATE";

			$rows = $this->query->updateData('mail_site',"email='$email'","WHERE id_email='$id'");

			if($rows) {
				$deletefirst 	= $this->query->deleteData('mail_fwd','id_email',$id);

				$jmlFwd = count($fwd);
				for($x = 0 ;$x < $jmlFwd; $x++){
					$qFwd 		= "insert into mail_fwd (email,id_email) values ('".$fwd[$x]."','$id')";
					$insertFwd 	= $this->query->insertDatabyQ($qFwd);
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

	public function getdatabanner(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'website'		=> true,
				'picture'		=> true,
				'picture_en'	=> true, 
				'link'			=> true, 
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/databanner';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalbanner(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from banner
							where id_banner='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function insertbanner(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			/*$title		= trim(strip_tags(stripslashes($this->input->post('title',true))));
			$sub		= trim(strip_tags(stripslashes($this->input->post('subtitle',true))));*/
			$title		= $this->input->post('title',true);
			$sub		= $this->input->post('subtitle',true);
			$title_en	= $this->input->post('title_en',true);
			$sub_en		= $this->input->post('subtitle_en',true);
			$website	= $this->input->post('kategori_website',true);
			$link		= $this->input->post('link',true);
			$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['pict']['name']);
			$fileName_en 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['pict_en']['name']);
				
			$config['upload_path'] = './images/slides/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg'; 
			
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
		
			$media 			= $this->upload->data();
			$fileNamePost 	= $media['file_name'];
			
			$config_en['upload_path'] = './images/slides/'; //buat folder dengan nama assets di root folder
			$config_en['file_name'] = $fileName_en;
			$config_en['allowed_types'] = 'gif|jpg|png|jpeg';
			
			$this->load->library('upload');
			$this->upload->initialize($config_en);
			 
			if(! $this->upload->do_upload('pict_en') )
			$this->upload->display_errors();
		
			$media_en 			= $this->upload->data();
			$fileNamePost_en 	= $media_en['file_name'];
				 
			

			$q 			= "
						insert into banner (title,sub,title_en,sub_en,img,img_en,thumb,thumb_en,flag_website,link) values ('$title','$sub','$title_en','$sub_en','$fileNamePost','blur_thumb.png','$fileNamePost_en','blur_thumb.png','$website','$link')
						";
						//echo $q;
			$rows 		= $this->query->insertDatabyQ($q);

			if($rows) {
				$id			= $this->db->insert_id();
				$url 		= "Manage Banner";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
				//echo "insert into content (title,sub,headline,content,id_menu) values ('$title','',$menu,'$headline','$content')";
			}
		} else {
			redirect('/panel');
		}
	}

	public function deletebanner(){
		if(checkingsessionpwt()){
			$url 		= "Manage Banner";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			$rows = $this->query->deleteData('banner','id_banner',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function updatebanner(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$cekinglogo	= $_FILES['upl']['name'];
			$cekinglogo_en	= $_FILES['upl_en']['name'];
			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			/*$title	= trim(strip_tags(stripslashes($this->input->post('ed_title',true))));
			$sub		= trim(strip_tags(stripslashes($this->input->post('ed_subtitle',true))));*/
			$title		= $this->input->post('ed_title',true);
			$sub		= $this->input->post('ed_subtitle',true);
			$title_en	= $this->input->post('ed_title_en',true);
			$sub_en		= $this->input->post('ed_subtitle_en',true);
			$website	= $this->input->post('ed_kategori_website',true);
			$link		= $this->input->post('ed_link',true);
			$userid		= $userdata['userid'];
				
			$url 		= "Manage Banner";
			$activity 	= "UPDATE";

			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('banner','img','WHERE id_banner='.$id.'');
				foreach ($coba as $dataex) {
					$dataexis = 'images/slides/'.$dataex['img'];
				}
				unlink($dataexis);
			
				$fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/slides/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('upl');

				$rows = $this->query->updateData('banner',"title='$title', sub='$sub',title_en='$title_en', sub_en='$sub_en', img='$fileName', flag_website='$website', link='$link'","WHERE id_banner='$id'");
			} else if($cekinglogo_en !=''){
				//delete eksisting
				$coba_en = $this->query->getData('banner','img_en','WHERE id_banner='.$id.'');
				foreach ($coba_en as $dataex_en) {
					$dataexis_en = 'images/slides/'.$dataex_en['img_en'];
				}
				unlink($dataexis_en);
			
				$fileName_en = str_replace(' ','_',time().$_FILES['upl_en']['name']);
				$config_en['upload_path'] = './images/slides/'; //buat folder dengan nama assets di root folder
				$config_en['file_name'] = $fileName_en;
				$config_en['allowed_types'] = 'gif|jpg|png';
				$config_en['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config_en);
				 
				if(! $this->upload->do_upload('upl_en') )
				$this->upload->display_errors();
					 
				$media_en = $this->upload->data('upl_en');

				$rows = $this->query->updateData('banner',"title='$title', sub='$sub',title_en='$title_en', sub_en='$sub_en', img_en='$fileName_en', flag_website='$website', link='$link'","WHERE id_banner='$id'");
			} else {
				$rows = $this->query->updateData('banner',"title='$title', sub='$sub',title_en='$title_en', sub_en='$sub_en', flag_website='$website', link='$link'","WHERE id_banner='$id'");
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

	public function getSiteConfig(){
		if(checkingsessionpwt()){

			$id			= '1';

			$q 		= "
						select
							a.*,
							(select email from mail_site where id_email=a.mail_site) as mailbase,
							(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Site Configuration' AND xa.data = a.id_site ORDER BY xa.date_time DESC limit 1)as update_by,
							(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
							WHERE xa.menu='Site Configuration' AND xa.data = a.id_site ORDER BY xa.date_time DESC limit 1)as last_update
						from
						configsite a
						where id_site='1'
						ORDER BY a.id_site desc
					";
			$getdatalop = $this->query->getDatabyQ($q);

			header('Content-type: application/json; charset=UTF-8');			
			if (isset($id) && !empty($id)) {
				foreach($getdatalop as $data) {
					$row = array(
						'name_site'		=> $data['name_site'],
						'logo'			=> base_url()."images/".$data['logo'],
						'favicon'		=> $data['favicon'],
						'mail_site'		=> $data['mail_site'],
						'mailbase'		=> $data['mailbase'],
						'alamat'		=> $data['alamat'],
						'phone'			=> $data['phone'],
						'maps'			=> $data['maps'],
						'facebook'		=> $data['facebook'],
						'twitter'		=> $data['twitter'],
						'instagram'		=> $data['instagram'],
						'youtube'		=> $data['youtube'],
						'showreel'		=> $data['showreel'],
						'whatsapp_no'	=> $data['whatsapp_no'],
						'whatsapp_text'	=> $data['whatsapp_text'],
						'updateby'		=> $data['update_by'],
						'updatedate'	=> $data['last_update']
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
	public function updateSiteConfig(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$cekinglogo		= $_FILES['upl']['name'];
			$name			= trim(strip_tags(stripslashes($this->input->post('name',true))));
			$mail			= trim(strip_tags(stripslashes($this->input->post('mail',true))));
			$phone			= trim(strip_tags(stripslashes($this->input->post('phone',true))));
			$alamat			= trim(strip_tags(stripslashes($this->input->post('alamat',true))));
			$maps			= $_POST['maps'];
			$facebook		= trim(strip_tags(stripslashes($this->input->post('facebook',true))));
			$twitter		= trim(strip_tags(stripslashes($this->input->post('twitter',true))));
			$youtube		= trim(strip_tags(stripslashes($this->input->post('youtube',true))));
			$instagram		= trim(strip_tags(stripslashes($this->input->post('instagram',true))));
			$showreel		= trim(strip_tags(stripslashes($this->input->post('showreel',true))));
			$whatsapp_no	= trim(strip_tags(stripslashes($this->input->post('whatsapp_no',true))));
			$whatsapp_text	= trim(strip_tags(stripslashes($this->input->post('whatsapp_text',true))));
			
			$url 		= "Site Configuration";
			$activity 	= "UPDATE";
			
			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('configsite','logo',"WHERE id_site='1'");
				foreach ($coba as $dataex) {
					$dataexis = 'images/'.$dataex['logo'];
				}
				unlink($dataexis);
			
				$fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png|jpeg|svg';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('upl');
				
				$rows = $this->query->updateData('configsite',"name_site='$name', logo='$fileName', mail_site='$mail', alamat='$alamat', phone='$phone', maps='$maps', facebook='$facebook', twitter='$twitter', youtube='$youtube', instagram='$instagram', showreel='$showreel', whatsapp_no='$whatsapp_no', whatsapp_text='$whatsapp_text'","WHERE id_site='1'");
				
				
			} else {
				$rows = $this->query->updateData('configsite',"name_site='$name', mail_site='$mail', alamat='$alamat', phone='$phone', maps='$maps', facebook='$facebook', twitter='$twitter', youtube='$youtube', instagram='$instagram', showreel='$showreel', whatsapp_no='$whatsapp_no', whatsapp_text='$whatsapp_text'","WHERE id_site='1'");
				
			}
			if($rows) {
				$id 	= '1';
				$log 	= $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	

	public function getdatadoc(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'document'		=> true,
				'menu'			=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datadoc';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modaldoc(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from document
							where id_doc='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function insertdoc(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$doc		= trim(strip_tags(stripslashes($this->input->post('document',true))));
			$menu		= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			$fdName		= $_POST['fdname'];

			$link		= $this->formula->clean(strtolower($doc));
			
			$query = $this->query->getData('document','max(id_doc)+1 as id_doc','');
			$getID = array_shift($query);
			if ($getID['id_doc']=='') {
				$id = '1';
			} else {
				$id = $getID['id_doc'];
			}

			$q 			= "
						insert into document (id_doc,title,id_menu,link) values ('$id','$doc','$menu','$link')
						";
						//echo $q;
			$rows 		= $this->query->insertDatabyQ($q);
			
			if($rows) {
				$url 		= "Manage Document";
				$activity 	= "INSERT";

				//$jmlImg = count($_FILES['upl']['name']);
				$jmlImg = count($_FILES['upl']['name']);
				for($y = 0; $y < $jmlImg; $y++){
					$direktori[$y] 			= './images/document/';
					$lokasi_file[$y]    	= $_FILES['upl']['tmp_name'][$y];
					$tipe_file[$y]      	= $_FILES['upl']['type'][$y];
					$nama_file[$y]      	= $_FILES['upl']['name'][$y];
					$acak[$y]           	= rand(000000,999999);
					$nama_file_unik[$y] 	= str_replace(' ','_',time().''.$nama_file[$y]);
					
					// echo 'filenya'.$nama_file[$y];
					
					$allowed = array('pdf','doc','docx');
					
					$extension = pathinfo($nama_file[$y], PATHINFO_EXTENSION);

					if(!in_array(strtolower($extension), $allowed)){
						echo '{"status":"error"}';
						exit;
					}
					
					if (!empty($lokasi_file[$y])){
						//direktori gambar
						$vfile_upload[$y] = $direktori[$y] . $nama_file_unik[$y];

						//Simpan gambar dalam ukuran sebenarnya
						move_uploaded_file($lokasi_file[$y], $vfile_upload[$y]);
						
						$qFD 		= "insert into file_doc (name_doc,file_doc,id_doc) values ('".$fdName[$y]."','$nama_file_unik[$y]','$id')";
						$insertFD 	= $this->query->insertDatabyQ($qFD);
					}
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

	public function deletedoc(){
		if(checkingsessionpwt()){
			$url 		= "Manage Document";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			$coba = $this->query->getData('file_doc','file_doc','WHERE id_doc='.$cond.'');
			foreach ($coba as $dataex) {
				$dataexis = 'images/document/'.$dataex['file_doc'];
				@unlink($dataexis);
			}

			$rows = $this->query->deleteData('document','id_doc',$cond);
			
			if(isset($rows)) {
				$row = $this->query->deleteData('file_doc','id_doc',$cond);

				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function deletefiledoc(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$coba = $this->query->getData('file_doc','file_doc','WHERE id_file='.$id.'');
			foreach ($coba as $dataex) {
				$dataexis = 'images/document/'.$dataex['file_doc'];
				@unlink($dataexis);
			}

			$rows = $this->query->deleteData('file_doc','id_file',$id);
			
			if(isset($rows)) {
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
			
		} else {
			redirect('/panel');
		}
	}	

	public function getdataEksDoc($id){
		if(checkingsessionpwt()){
			
			$dataRolesAll		= $this->query->getData('file_doc','*',"WHERE id_doc='$id' ORDER BY id_doc DESC");
			$x 					= 0;
			foreach($dataRolesAll as $data) { $x++;
				echo '
				<div class="form-group row bgeditnew" id="exed_newrow'.$x.'">
                    <label class="col-form-label col-lg-3 col-sm-12"></label>
                    <div class="col-lg-4 col-md-9 col-sm-12">
                        <div class="input-group">
                            <input type="text" name="ed_fwd[]" class="form-control" id="ed_fwd" readonly placeholder="Forward To" value="'.$data['name_doc'].'">
                            <button type="button" class="btn btn-sm btn-danger text-white ed_deleterow" data-toggle="kt-tooltip" title="Remove File" data-id="#exed_newrow'.$x.'" data-idfile="'.$data['id_file'].'">
                            	<i class="fa fa-times text-white"></i>
                            </button>
                        </div>
                    </div>
                </div>
				';
			}
			echo '<input type="hidden" name="eksisdatarow" id="eksisdatarow" value="'.$x.'">';
		} else {
			redirect('/panel');
		}
	}

	public function updatedoc(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid		= $userdata['userid'];

			$id				= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$doc			= trim(strip_tags(stripslashes($this->input->post('ed_document',true))));
			$menu			= trim(strip_tags(stripslashes($this->input->post('ed_menu',true))));

			$link		= $this->formula->clean(strtolower($doc));
				
			$url 		= "Manage Document";
			$activity 	= "UPDATE";

			$rows = $this->query->updateData('document',"title='$doc',id_menu='$menu',link='$link'","WHERE id_doc='$id'");

			if($rows) {
				$fdName		= $_POST['ed_fdname'];
				//$jmlImg = count($_FILES['upl']['name']);
				$jmlImg = count($_FILES['ed_upl']['name']);
				if ($jmlImg>0) {
					for($y = 0; $y < $jmlImg; $y++){
						$direktori[$y] 			= './images/document/';
						$lokasi_file[$y]    	= $_FILES['ed_upl']['tmp_name'][$y];
						$tipe_file[$y]      	= $_FILES['ed_upl']['type'][$y];
						$nama_file[$y]      	= $_FILES['ed_upl']['name'][$y];
						$acak[$y]           	= rand(000000,999999);
						$nama_file_unik[$y] 	= str_replace(' ','_',time().''.$nama_file[$y]);
						
						// echo 'filenya'.$nama_file[$y];
						
						$allowed = array('pdf','doc','docx');
						
						$extension = pathinfo($nama_file[$y], PATHINFO_EXTENSION);

						if(!in_array(strtolower($extension), $allowed)){
							echo '{"status":"error"}';
							exit;
						}
						
						if (!empty($lokasi_file[$y])){
							//direktori gambar
							$vfile_upload[$y] = $direktori[$y] . $nama_file_unik[$y];

							//Simpan gambar dalam ukuran sebenarnya
							move_uploaded_file($lokasi_file[$y], $vfile_upload[$y]);
							
							$qFD 		= "insert into file_doc (name_doc,file_doc,id_doc) values ('".$fdName[$y]."','$nama_file_unik[$y]','$id')";
							$insertFD 	= $this->query->insertDatabyQ($qFD);
						}
					}
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

	public function modalphotos(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from photos
							where id_photo='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function getdataEksPhotos(){
		if(checkingsessionpwt()){
			$dataRolesAll		= $this->query->getData('photos','*',"ORDER BY id_photo DESC");
			$x 					= 0;

			$cek				= $this->query->getNumRows('photos','*',"")->num_rows();

			if ($cek>0) {
				//echo '<div id="portfolio" class="grid-layout portfolio-4-columns" data-margin="20">';
				foreach($dataRolesAll as $data) { $x++;
					$id 		= $data['id_photo'];
					$picture 	= $data['picture'];

					echo '
	                    <div class="portfolio-item img-zoom ct-foto">
	                        <div class="portfolio-item-wrap">
	                            <div class="portfolio-image">
	                                <a href="#"><img src="'.base_url().'images/gallery/'.$picture.'" alt=""></a>
	                            </div>
	                            <div class="portfolio-description">
	                                <!--a title="Sample Photo" data-lightbox="image" href="'.base_url().'images/gallery/'.$picture.'">
	                                    <i class="fa fa-expand"></i>
	                                </a-->
	                                <a title="Delete" class="btndeleteMenu" data-toggle="modal" data-target="#delete" data-id="'.$id.'">
	                                    <i class="fa fa-times"></i>
	                                </a>
	                            </div>
	                        </div>
	                    </div>
					';
				}
				//echo '</div>';
			} else {
				echo '
					<div class="row" style="padding: 20px;">
                        <div class="col-sm-12">
                            <div><center><img src="'.base_url().'images/icon/notfound.png"></center></div><br>
                            <h5 class="text-center">Anda Belum Memiliki Data Tersimpan Di Website Anda</h5>
                            </h6><center>Silahkan buat data baru</center></h6><br>
                        </div>
                    </div>
                ';
			}
		} else {
			redirect('/panel');
		}
	}

	public function insertphoto(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			//$menu		= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			$album		= trim(strip_tags(stripslashes($this->input->post('album',true))));
			
			$jmlImg = count($_FILES['upl']['name']);
			for($y = 0; $y < $jmlImg; $y++){
				$direktori[$y] 			= './images/gallery/';
				$lokasi_file[$y]    	= $_FILES['upl']['tmp_name'][$y];
				$tipe_file[$y]      	= $_FILES['upl']['type'][$y];
				$nama_file[$y]      	= $_FILES['upl']['name'][$y];
				$acak[$y]           	= rand(000000,999999);
				$nama_file_unik[$y] 	= str_replace(' ','_',time().''.$nama_file[$y]);
				
				// echo 'filenya'.$nama_file[$y];
				
				$allowed = array('png','jpg','gif','jpeg','bmp');
				
				$extension = pathinfo($nama_file[$y], PATHINFO_EXTENSION);

				if(!in_array(strtolower($extension), $allowed)){
					echo '{"status":"error"}';
					exit;
				}
				
				if (!empty($lokasi_file[$y])){
					//direktori gambar
					$vfile_upload[$y] = $direktori[$y] . $nama_file_unik[$y];

					//Simpan gambar dalam ukuran sebenarnya
					move_uploaded_file($lokasi_file[$y], $vfile_upload[$y]);
					
					$q 			= "insert into photos (id_photo,id_album,picture) values ('','$album','$nama_file_unik[$y]')";
					$rows 		= $this->query->insertDatabyQ($q);
				}
			}
			
			if($rows) {
				$id			= $this->db->insert_id();
				$url 		= "Manage Photos";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}

	public function deletephoto(){
		if(checkingsessionpwt()){
			$url 		= "Manage Photos";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			$coba = $this->query->getData('photos','picture','WHERE id_photo='.$cond.'');
			foreach ($coba as $dataex) {
				$dataexis = 'images/gallery/'.$dataex['picture'];
				@unlink($dataexis);
			}

			$rows = $this->query->deleteData('photos','id_photo',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function modalvideos(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from videos
							where id_video='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function getdataEksVideos(){
		if(checkingsessionpwt()){
			$dataRolesAll		= $this->query->getData('videos','*',"ORDER BY id_photo DESC");
			$x 					= 0;

			$cek				= $this->query->getNumRows('videos','*',"")->num_rows();

			if ($cek>0) {
				//echo '<div id="portfolio" class="grid-layout portfolio-4-columns" data-margin="20">';
				foreach($dataRolesAll as $data) { $x++;
					$id 		= $data['id_video'];
					$video 		= $data['video'];

					echo '
	                    <div class="portfolio-item large-width img-zoom ct-video">
	                       <div class="portfolio-item-wrap">
	                            <div class="portfolio-image">
	                                <a href="#">
	                                    <iframe width="1280" height="720" src="'.$video.'?rel=0&amp;showinfo=0" allowfullscreen></iframe>
	                                </a>
	                            </div>
	                            <div class="portfolio-description">
	                                <a title="Video Youtube" data-lightbox="iframe" href="'.$video.'"><i class="fa fa-play"></i></a>
	                                <a href="'.$video.'" target="_blank"><i class="fa fa-link"></i></a>
	                                <a title="Delete" class="btndeleteMenu" data-toggle="modal" data-target="#delete" data-id="'.$id.'">
	                                    <i class="fa fa-times"></i>
	                                </a>
	                            </div>
	                        </div>
	                    </div>
					';
				}
				//echo '</div>';
			} else {
				echo '
					<div class="row" style="padding: 20px;">
                        <div class="col-sm-12">
                            <div><center><img src="'.base_url().'images/icon/notfound.png"></center></div><br>
                            <h5 class="text-center">Anda Belum Memiliki Data Tersimpan Di Website Anda</h5>
                            </h6><center>Silahkan buat data baru</center></h6><br>
                        </div>
                    </div>
                ';
			}
		} else {
			redirect('/panel');
		}
	}

	public function insertvideos(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			//$menu		= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			//$service	= trim(strip_tags(stripslashes($this->input->post('service',true))));
			$album		= trim(strip_tags(stripslashes($this->input->post('album',true))));
			$video		= $_POST['videos'];
			
			$jmlImg = count($video);
			for($y = 0; $y < $jmlImg; $y++){
				$q 			= "insert into videos (id_video,id_album,video) values ('','$album','$video[$y]')";
				$rows 		= $this->query->insertDatabyQ($q);
			}
			
			if($rows) {
				$id			= $this->db->insert_id();
				$url 		= "Manage Videos";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}

	public function deletevideos(){
		if(checkingsessionpwt()){
			$url 		= "Manage Videos";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			$rows = $this->query->deleteData('videos','id_video',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function insertinbox(){
		//session_cache_limiter('nocache');
		header('Expires: ' . gmdate('r', 0));
		header('Content-type: application/json');

		// Form Fields
		$name		= trim(strip_tags(stripslashes($this->input->post('widget-contact-form-name',true))));
		$email		= trim(strip_tags(stripslashes($this->input->post('widget-contact-form-email',true))));
		$subject	= trim(strip_tags(stripslashes($this->input->post('widget-contact-form-subject',true))));
		$message	= trim(strip_tags(stripslashes($this->input->post('widget-contact-form-message',true))));
		$created 	= date('Y-m-d H:i:s');


		if($email != '') {
			$q 			= "
						insert into inbox (name,email,subject,message,created,readnotif,reply) values ('$name','$email','$subject','$message','$created','N','N')
						";
			$rows 		= $this->query->insertDatabyQ($q);

			if($rows) {
			 	$qCE 		= "select * from mail_site where status='1'";
			 	$getCE 		= $this->query->getDatabyQ($qCE);
			 	$dataCE 	= array_shift($getCE);

			 	$qSendTo	= "
			 				select id_email,email from mail_site where status=1 
			 				UNION select id_fwd as id_email, email from mail_fwd where id_email=".$dataCE['id_email']."
			 				";
			 	$gSendTo	= $this->query->getDatabyQ($qSendTo);
			 	foreach ($gSendTo as $datasendto) {
			 		$emailto 	= $datasendto['email'];	
			 		$this->sendMailActtoAdmin($name,'Pesan Baru',$emailto,$subject,'memberikan');
			 	}
	 			
			    $response = array ('response'=>'success');
			} else {
				$response = array ('response'=>'error input inbox');
			}
		} else {
			$response = array ('response'=>'error email');     
		}
		echo json_encode($response);
	}

	public function sendMailActtoAdmin($name,$act,$email,$subject,$sifat) {
		$getSiteData 	= $this->query->getData('configsite','*',"");
		$datasite		= array_shift($getSiteData);
		
		$config = Array(
		'protocol' => 'smtp',
		'smtp_host' => 'smtp.hostinger.co.id',
		'smtp_port' => 587,
		'smtp_user' => 'noreply@sarangvisuell.com', // change it to yours
		'smtp_pass' => 'SarangVisuellnoreply', // change it to yours
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
		$this->email->from('noreply@sarangvisuell.com'); // change it to yours
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

	public function getdatainboxhome(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'inbox'		=> true,
				'dateentry'	=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datainboxhome';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function sendreplyinbox(){
		//session_cache_limiter('nocache');
		header('Expires: ' . gmdate('r', 0));
		header('Content-type: application/json');

		// Form Fields
		$id			= trim(strip_tags(stripslashes($this->input->post('idinbox',true))));
		$message	= $_POST['content'];
		$created 	= date('Y-m-d H:i:s');

		$getInbox 	= $this->query->getDatabyQ("select * from inbox where id_inbox='$id'");
		$dataInbox 	= array_shift($getInbox);

		$emailto 	= $dataInbox['email'];
		$name 		= $dataInbox['name'];
		$subject 	= 'Reply : '.$dataInbox['subject'];

		$userdata	= $this->session->userdata('sesspwt'); 
		$userid		= $userdata['userid'];
		$replyby 	= $userid;
		$replydate 	= date('Y-m-d H:i:s');


		if($emailto != '') {
			$rows 			= $this->query->updateData('inbox',"reply='Y',replyby='$replyby',replydate='$replydate'","WHERE id_inbox='$id'");
			if($rows) {
				$qOut 	= "
						insert into outbox (id_inbox,userid,replydate,messages) values ('$id','$replyby','$replydate','$message')
						";
				$insOutBox 	= $this->query->insertDatabyQ($qOut);

				$activity 	= 'REPLY INBOX';
				$url 		= 'INBOX';

				$log 		= $this->query->insertlog($activity,$url,$id);

				$this->sendMailtoUser($name,$emailto,$subject,$message);
				$response = array ('response'=>'success');
			} else {
				$response = "";
			}
		} else {
			$response = "";
		}
		echo json_encode($response);
	}

	public function sendMailtoUser($name,$emailto,$subject,$messages) {
		$getSiteData 	= $this->query->getData('configsite','*',"");
		$datasite		= array_shift($getSiteData);
		
		$config = Array(
		'protocol' 	=> 'smtp',
		'smtp_host' => 'smtp.hostinger.co.id',
		'smtp_port' => 587,
		'smtp_user' => 'info@parwatha.com', // change it to yours
		'smtp_pass' => 'PwtEmail02', // change it to yours
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
			<body>
				<div class="bg" style="background: #fff; color: #222; width: 95%; padding: 30px; margin: 0 auto;">
					<div id="logo" style="padding: 40px; background: #1d1d1d;"><center><img src="'.base_url().'images/'.$datasite['logo'].'"></center></div>
					<div id="confirmation-message" style="padding: 30px; text-align: left;">
						<div class="ravis-title-t-2" style="text-align: left;">
							<div class="title" style="color: #222; font-size: 34px;"><span>Hello,</span></div>
							<div style="">'.$name.'</div>
						</div>
						<div class="desc" style="color: #222; margin-top:20px; text-align:left;">
							<div>
							'.$messages.'
							</div>
						</div>
					</div>
				</div>
			</body>
			</html>
		';

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
		$this->email->from('info@parwatha.com'); // change it to yours
		$this->email->to($emailto);// change it to yours
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

	public function cektemplate(){
		$getSiteData 	= $this->query->getData('configsite','*',"");
		$datasite		= array_shift($getSiteData);

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
			<body>
				<div class="bg" style="background: #fff; color: #222; width: 95%; padding: 30px; margin: 0 auto;">
					<div id="logo" style="padding: 40px; background: #1d1d1d;">
						<center>
							<img src="'.base_url().'images/'.$datasite['logo'].'">
							<div style="text-align:center; color: #FFF;">
							'.$datasite['alamat'].'<br>
							'.$datasite['phone'].'
							</div>
						</center>
					</div>
					<div id="confirmation-message" style="padding: 30px; text-align: left;">
						<div class="ravis-title-t-2" style="text-align: left;">
							<div class="title" style="color: #222; font-size: 34px;"><span>Hello,</span></div>
							<div style="">'.$name.'</div>
						</div>
						<div class="desc" style="color: #222; margin-top:20px; text-align:left;">
							<div>
							'.$messages.'
							</div>
						</div>
					</div>
				</div>
			</body>
			</html>
		';
		echo $htmlContent;
	}

	public function getdataservices(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'icon'			=> true,
				'title'			=> true,
				'menu'			=> true,
				'headline'		=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataservices';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalservices(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from services
							where id_services='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function insertservices(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$title		= trim(strip_tags(stripslashes($this->input->post('title',true))));
			$title_en		= trim(strip_tags(stripslashes($this->input->post('title_en',true))));
			$menu		= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['pict']['name']);
			$content	= str_replace("'",'`',$_POST['content']);
			$headline	= trim(strip_tags(stripslashes($this->input->post('content',true))));
			$headline_en	= trim(strip_tags(stripslashes($this->input->post('content_en',true))));
			$date 		= date('Y-m-d');

			$link		= $this->formula->clean(strtolower($title));

			$config['upload_path'] = './images/content/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media 			= $this->upload->data();
			$fileNamePost 	= $media['file_name'];
				
			$q 			= "
						insert into services (title,sub,id_menu,headline,picture,link,content,title_en,content_en) values ('$title','',$menu,'$headline','$fileNamePosst','$link','$content','$title_en','$headline_en')
						";
						//echo $q;
			$rows 		= $this->query->insertDatabyQ($q);

			if($rows) {
				$id			= $this->db->insert_id();
				$url 		= "Manage Services";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
				//echo "insert into content (title,sub,headline,content,id_menu) values ('$title','',$menu,'$headline','$content')";
			}
		} else {
			redirect('/panel');
		}
	}

	public function deleteservices(){
		if(checkingsessionpwt()){
			$url 		= "Manage Services";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			//delete eksisting
			$coba = $this->query->getData('services','picture','WHERE id_services='.$cond.'');
			foreach ($coba as $dataex) {
				$dataexis = 'images/content/'.$dataex['picture'];
			}
			@unlink($dataexis);

			$rows = $this->query->deleteData('services','id_services',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function updateservices(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$cekinglogo	= @$_FILES['upl']['name'];
			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$title		= trim(strip_tags(stripslashes($this->input->post('ed_title',true))));
			$title_en		= trim(strip_tags(stripslashes($this->input->post('ed_title_en',true))));
			$menu		= trim(strip_tags(stripslashes($this->input->post('ed_menu',true))));
			$content	= str_replace("'",'`',$_POST['ed_content']);
			$content_en	= str_replace("'",'`',$_POST['ed_content_en']);
			$headline	= trim(strip_tags(stripslashes($content)));
			$headline_en	= trim(strip_tags(stripslashes($content_en)));

			$link		= $this->formula->clean(strtolower($title));

			$userid		= $userdata['userid'];
				
			$url 		= "Manage Services";
			$activity 	= "UPDATE";

			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('services','picture','WHERE id_services='.$id.'');
				foreach ($coba as $dataex) {
					$dataexis = 'images/content/'.$dataex['picture'];
				}
				@unlink($dataexis);
			
				$fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/content/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('upl');

				$rows = $this->query->updateData('services',"id_menu='$menu', title='$title', link='$link',headline='$headline', content='$content', picture='$fileName'","WHERE id_services='$id'");
			} else {

				$rows = $this->query->updateData('services',"id_menu='$menu', title='$title', link='$link',headline='$headline', content='$content',title_en='$title_en',content_en='$headline_en'","WHERE id_services='$id'");
			}

			if($rows) {
				$log = $this->query->insertlog($activity,$url,$id);
				echo $headline;
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}

	public function regeneratethumb() {
		$getData 	= $this->query->getDatabyQ("select * from event_detail where id_event='3' and id_event_detail>1902");
		foreach ($getData as $data) {
			// echo $data['picture'].'<br>';
			$this->makeThumbnails('event',$data['picture']);
		}
	}

	public function makeThumbnails($filedir,$img) {
		$updir 	= FCPATH.'images/'.$filedir.'/';
		$id 	= '1';
	    $thumbnail_width = 500;
	    $thumbnail_height = 500;
	    $thumb_beforeword = "thumb";
	    $arr_image_details = getimagesize("$updir"."$img"); // pass id to thumb name
	    //echo 'asdf'.$arr_image_details;

	    $original_width = $arr_image_details[0];
	    $original_height = $arr_image_details[1];
	    if ($original_width > $original_height) {
	        $new_width = $thumbnail_width;
	        $new_height = intval($original_height * $new_width / $original_width);
	    } else {
	        $new_height = $thumbnail_height;
	        $new_width = intval($original_width * $new_height / $original_height);
	    }
	    $dest_x = intval(($thumbnail_width - $new_width) / 2);
	    $dest_y = intval(($thumbnail_height - $new_height) / 2);
	    if ($arr_image_details[2] == IMAGETYPE_GIF) {
	        $imgt = "ImageGIF";
	        $imgcreatefrom = "ImageCreateFromGIF";
	    }
	    if ($arr_image_details[2] == IMAGETYPE_JPEG) {
	        $imgt = "ImageJPEG";
	        $imgcreatefrom = "ImageCreateFromJPEG";
	    }
	    if ($arr_image_details[2] == IMAGETYPE_PNG) {
	        $imgt = "ImagePNG";
	        $imgcreatefrom = "ImageCreateFromPNG";
	    }
	    if ($imgt) {
	        $old_image = $imgcreatefrom("$updir"."$img");
	        // $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
	        $new_image = imagecreatetruecolor($new_width, $new_height);
	        // imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
	        imagecopyresized($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);
	        $imgt($new_image, "$updir" . "$thumb_beforeword".'_'. "$img");
	    }
	}

	public function cekthumbnail(){
		$getData 	= $this->query->getDatabyQ("select * from event_detail order by 1");
		foreach ($getData as $data) {
			$this->makeThumbnails('event',$data['picture']);
		}
		// $this->makeThumbnails('event','TelDGSjpg.jpg');
		// echo'a';
	}

	public function getMenubyServices(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('services','*',"WHERE id_services='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $data) {
					
					
					$row = array(
						'id_menu'		=> $data['id_menu']
						);
					$json = $row;
					// echo json_encode($row);
					// exit;
				}
				echo json_encode($json);
			}
		} else {
			redirect('/panel');
		}
	}

	public function getdataalbum(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'icon'			=> true,
				'title'			=> true,
				'works'			=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataalbum';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalalbum(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from album
							where id_album='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function insertalbum(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$gtitle		= trim(strip_tags(stripslashes($this->input->post('title',true))));
			$title		= str_replace("'","`",$gtitle);
			$works		= trim(strip_tags(stripslashes($this->input->post('works',true))));
			$services	= trim(strip_tags(stripslashes($this->input->post('service',true))));
			$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['pict']['name']);
			$date 		= date('Y-m-d');

			$link		= $this->formula->clean(strtolower($title));

			$config['upload_path'] = './images/gallery/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media 			= $this->upload->data();
			$fileNamePost 	= $media['file_name'];
				
			$q 			= "
						insert into album (title,id_menu,icon,id_services,link) values ('$title',$works,'$fileNamePosst','$services','$link')
						";
						//echo $q;
			$rows 		= $this->query->insertDatabyQ($q);

			if($rows) {
				$id			= $this->db->insert_id();
				$url 		= "Manage Album";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
				//echo "insert into content (title,sub,headline,content,id_menu) values ('$title','',$menu,'$headline','$content')";
			}
		} else {
			redirect('/panel');
		}
	}

	public function deletealbum(){
		if(checkingsessionpwt()){
			$url 		= "Manage Album";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			//delete eksisting
			$coba = $this->query->getData('album','icon','WHERE id_album='.$cond.'');
			foreach ($coba as $dataex) {
				$dataexis = 'images/gallery/'.$dataex['icon'];
			}
			@unlink($dataexis);

			$rows = $this->query->deleteData('album','id_album',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function updatealbum(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$cekinglogo	= $_FILES['upl']['name'];
			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$gtitle		= trim(strip_tags(stripslashes($this->input->post('ed_title',true))));
			$title		= str_replace("'","`",$gtitle);
			$works		= trim(strip_tags(stripslashes($this->input->post('ed_works',true))));
			$service	= trim(strip_tags(stripslashes($this->input->post('ed_service',true))));
			$content	= str_replace("'",'`',$_POST['ed_content']);

			$link		= $this->formula->clean(strtolower($title));

			$userid		= $userdata['userid'];
				
			$url 		= "Manage Album";
			$activity 	= "UPDATE";

			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('album','icon','WHERE id_album='.$id.'');
				foreach ($coba as $dataex) {
					$dataexis = 'images/gallery/'.$dataex['picture'];
				}
				@unlink($dataexis);
			
				$fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/gallery/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('upl');

				$rows = $this->query->updateData('album',"id_menu='$works', title='$title', link='$link', id_services='$service', icon='$fileName'","WHERE id_album='$id'");
			} else {

				$rows = $this->query->updateData('album',"id_menu='$works', title='$title', link='$link', id_services='$service'","WHERE id_album='$id'");
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

	public function modaldetailworks(){
		//if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from album
							where id_album='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		//} else {
		//	redirect('/panel');
		//}
	}

	public function getdatadownloadfront(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'event'			=> true,
				'lokasi'		=> true,
				'tanggal'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datadownloadfront';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdataevent(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'event'			=> true,
				'location'		=> true,
				'date'			=> true,
				'expired'		=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataevent';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalevent(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from event
							where id_event='".$id."'
							";
			$getData		= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}	

	public function insertevent(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$nama		= trim(strip_tags(stripslashes($this->input->post('title',true))));
			$lokasi		= trim(strip_tags(stripslashes($this->input->post('location',true))));
			$tanggal	= $this->input->post('date',true);
			$expired	= $this->input->post('expired',true);

			$link		= $this->formula->clean(strtolower($nama));
			
			$query = $this->query->getData('event','max(id_event)+1 as id_event','');
			$getID = array_shift($query);
			if ($getID['id_event']=='') {
				$id = '1';
			} else {
				$id = $getID['id_event'];
			}

			$q 			= "
						insert into event (id_event,nama,link,lokasi,tanggal,expired) values ('$id','$nama','$link','$lokasi','$tanggal','$expired')
						";
			$rows 		= $this->query->insertDatabyQ($q);
			
			if($rows) {
				$url 		= "Manage Event";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}

	public function insermorephototevent(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$id			= trim(strip_tags(stripslashes($this->input->post('ad_id',true))));

			// $q 			= "
			// 			insert into event (id_event,nama,link,lokasi,tanggal,expired) values ('$id','$nama','$link','$lokasi','$tanggal','$expired')
			// 			";
			// $rows 		= $this->query->insertDatabyQ($q);
			$rows = $this->query->updateData('event_detail',"id_event='$id'","WHERE id_event='0'");
			
			if($rows) {
				$url 		= "Manage Event";
				$activity 	= "INSERT";

				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}

			// print json_encode(array('success'=>true,'total'=>1));
		} else {
			redirect('/panel');
		}
	}

	public function uploadEventImg(){
		if(checkingsessionpwt()){
			$ds          = DIRECTORY_SEPARATOR;
	 
			$storeFolder = 'images/event';
			 
			if (!empty($_FILES)) {
				$query = $this->query->getData('event','max(id_event)+1 as id_event','');
				$getID = array_shift($query);
				if ($getID['id_event']=='') {
					$id = '1';
				} else {
					$id = $getID['id_event'];
				}

				$fileName 					= preg_replace("/[^\w]/", "", time().$_FILES['file']['name']);

			    $config['upload_path'] 		= './images/event/'; //buat folder dengan nama assets di root folder
				$config['file_name'] 		= $fileName;
				$config['allowed_types'] 	= 'gif|jpg|png|JPG|JPEG|jpeg';
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('file') )
				$this->upload->display_errors();
					 
				$media 			= $this->upload->data();
				$fileNamePost 	= $media['file_name'];
				$this->makeThumbnails('event',$fileNamePost);

				$qED 		= "insert into event_detail (id_event,picture) values ('$id','$fileNamePost')";
				$insertED 	= $this->query->insertDatabyQ($qED);
			     
			}
		} else {
			redirect('/panel');
		}
	}

	public function uploadEventImgMore(){
		if(checkingsessionpwt()){
			$ds          = DIRECTORY_SEPARATOR;
	 
			$storeFolder = 'images/event';
			 
			if (!empty($_FILES)) {
				// $id 						= $_POST['ad_id'];
				$id 						= 0;

				$fileName 					= preg_replace("/[^\w]/", "", time().$_FILES['file']['name']);

			    $config['upload_path'] 		= './images/event/'; //buat folder dengan nama assets di root folder
				$config['file_name'] 		= $fileName;
				$config['allowed_types'] 	= 'gif|jpg|png|JPG|JPEG|jpeg';
				// echo $fileName;
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('file') )
				$this->upload->display_errors();
					 
				$media 			= $this->upload->data();
				$fileNamePost 	= $media['file_name'];
				$this->makeThumbnails('event',$fileNamePost);

				$qED 		= "insert into event_detail (id_event,picture) values ('$id','$fileNamePost')";
				$insertED 	= $this->query->insertDatabyQ($qED);
			     
			}
		} else {
			redirect('/panel');
		}
	}

	public function updateevent(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$title		= trim(strip_tags(stripslashes($this->input->post('ed_title',true))));
			$location	= trim(strip_tags(stripslashes($this->input->post('ed_location',true))));
			$date		= $this->input->post('ed_date',true);
			$expired	= $this->input->post('ed_expired',true);

			$link		= $this->formula->clean(strtolower($title));
			
			$userid		= $userdata['userid'];
				
			$url 		= "Manage Event";
			$activity 	= "UPDATE";
			
			$rows = $this->query->updateData('event',"nama='$title', lokasi='$location', tanggal='$date', expired='$expired', link='$link'","WHERE id_event='$id'");

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

	public function deleteevent(){
		if(checkingsessionpwt()){
			$url 		= "Manage Event";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			//delete eksisting
			$coba = $this->query->getData('event_detail','picture','WHERE id_event='.$cond.'');
			foreach ($coba as $dataex) {
				$dataexis 		= 'images/event/'.$dataex['picture'];
				$dataexisthumb 	= 'images/event/thumb_'.$dataex['picture'];
				@unlink($dataexis);
				@unlink($dataexisthumb);
			}

			$rows = $this->query->deleteData('event','id_event',$cond);
			$rows2 = $this->query->deleteData('event_detail','id_event',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function loadmoreEvent(){
		$group_no 			= $this->input->post('group_no');
		$id 				= $this->input->post('id');
        $content_per_page 	= 9;
        $start 				= ceil($group_no * $content_per_page);

        $q 					= "
        					SELECT * FROM  event_detail where id_event='$id' order by 1 desc LIMIT $start,$content_per_page
        					";
        $all_content 		= $this->query->getDatabyQ($q);
        if(isset($all_content) && is_array($all_content) && count($all_content)) : 
    	
	    	// $this->load->view('theme/polo/plugin1');
	    	echo '
			<div class="container">';
	        echo '<div id="portfolio" class="grid-layout portfolio-3-columns" data-margin="20">';
	            foreach ($all_content as $data) :
	            	$file = $data['picture'];
	            	echo ' <div class="portfolio-item img-zoom ct-foto">
		                        <div class="portfolio-item-wrap">
		                            <div class="portfolio-image">
		                                <a href="#"><img src="'.base_url().'images/event/thumb_'.$file.'" alt=""></a>
		                            </div>
		                            <div class="portfolio-description">
		                                <a title="" data-lightbox="image" href="'.base_url().'images/event/'.$file.'">
		                                    <i class="fa fa-expand"></i>
		                                </a>
		                                <a title="" href="'.base_url().'images/event/'.$file.'">
		                                    <i class="fa fa-download"></i>
		                                </a>
		                            </div>
		                        </div>
		                    </div>';
	            endforeach;
	        echo '</div>
			<div class="clearfix"></div>
			</div>

			<!--Template functions-->
			<!--Template functions-->
			<script src="https://parwatha.com/polo/js/product.js"></script>
			<script>
			$(".mainMenu-trigger button").on("click touchend", function (e) {
                $body.toggleClass("mainMenu-open");
                $(this).toggleClass("toggle-active");
                if ($body.hasClass("mainMenu-open")) {
                    $header.find("#mainMenu").css("max-height", $window.height() - $header.height());
                } else {
                    $header.find("#mainMenu").css("max-height", 0);
                }
                return false;
            });
			</script>
			';
			// $this->load->view('theme/polo/footer');
        endif; 
	}

	public function modalourteam(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$getdata			= $this->query->getData('ourteam','*',"WHERE id='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getdata as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}
}
