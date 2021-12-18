<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends CI_Controller {

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
		$this->load->model('stok_handler');
		
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
				'keterangan'	=> true,
				'suplier'		=> true,
				'jml'			=> true,
				'total'			=> true,
				'bayar'			=> true,
				'tgl'			=> true,
				'status'		=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;

			// $jsonfile	= base_url().'user/data';
			$jsonfile	= $this->stok_handler->data();

			$this->datatable->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function insert(){
		if(checkingsessionpwt()){
			$url 				= "Stok";
			$activity 			= "INSERT";

			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid				= $userdata['userid'];

			$suplier			= trim(strip_tags(stripslashes($this->input->post('suplier',true))));
			$tgl				= trim(strip_tags(stripslashes($this->input->post('tgl',true))));
			$label 				= trim(strip_tags(stripslashes($this->input->post('label',true))));
			$jml 				= trim(strip_tags(stripslashes($this->input->post('totalpcs',true))));
			$total 				= trim(strip_tags(stripslashes($this->input->post('total',true))));

			$rows 				= $this->db->query("
								INSERT INTO stok_order (label,jml,total_harga,createddate,status,type,id_suplier) values 
								('$label','$jml','$total','$tgl','1','1','$suplier')
								");
			
			if($rows) {
				$id				= $this->db->insert_id();

				// INSERT DETAIL
				$getColor 		= $this->db->query("
								SELECT * from color where type='1' order by 1
								")->result_array();

				$getSize 		= $this->db->query("
								SELECT * from size order by sort
								")->result_array();

				foreach ($getColor as $color) {
					$colid 	= $color['id'];
					foreach($getSize as $size) {
						$sizeid 		= $size['id_size']; 
						$price 			= $size['hpp'];

						$pcs			= trim(strip_tags(stripslashes($this->input->post('pcscol'.$colid.'siz'.$sizeid,true))));

						$insertDetail 	= $this->db->query("
										INSERT INTO stok_order_detail (id_order,size,color,jml_order,harga,status,type) values 
										('$id','$sizeid','$colid','$pcs','$price','1','1')
										");
					}
				}

				$log 			= $this->query->insertlog($activity,$url,$id);

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
			
			$dataRoles			= $this->db->query("
								SELECT 
									a.*
								from stok_order a where a.id_order ='$id'
								")->result_array();
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataRoles as $row) {
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
			$userdata		= $this->session->userdata('sesspwt'); 
			$url 			= "Stok";
			$activity 		= "UPDATE";
			
			$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$idsuplier 		= trim(strip_tags(stripslashes($this->input->post('ed_suplier',true))));
			$userdata 		= $this->session->userdata('sesspwt'); 
			
			$userid			= $userdata['userid'];

			$tgl			= trim(strip_tags(stripslashes($this->input->post('ed_tgl',true))));
			$label 			= trim(strip_tags(stripslashes($this->input->post('ed_label',true))));
			$jml 			= trim(strip_tags(stripslashes($this->input->post('ed_totalpcs',true))));
			$total 			= trim(strip_tags(stripslashes($this->input->post('ed_total',true))));

			$rows 			= $this->db->query("
							UPDATE stok_order SET 
								label 			= '$label',
								jml				= '$jml',
								total_harga		= '$total',
								createddate		= '$tgl'
							WHERE id_order='$id'
							");
			
			if($rows) {
				// DELETE FIRST
				$delFirst 	= $this->query->deleteData('stok_order_detail','id_order',$id);

				// INSERT DETAIL
				$getColor 		= $this->db->query("
								SELECT * from color where type='1' order by 1
								")->result_array();

				$getSize 		= $this->db->query("
								SELECT * from size order by sort
								")->result_array();

				foreach ($getColor as $color) {
					$colid 	= $color['id'];
					foreach($getSize as $size) {
						$sizeid 		= $size['id_size']; 
						$getPrice 		= $this->db->query("
										SELECT * from suplier_harga where id_suplier='$idsuplier' and id_size='$sizeid'
										")->result_array();
						$sprice 		= array_shift($getPrice);
						$price 			= $sprice['harga'];
						// $price 			= $size['hpp'];

						$pcs			= trim(strip_tags(stripslashes($this->input->post('ed_pcscol'.$colid.'siz'.$sizeid,true))));

						$insertDetail 	= $this->db->query("
										INSERT INTO stok_order_detail (id_order,size,color,jml_order,harga,status,type) values 
										('$id','$sizeid','$colid','$pcs','$price','1','1')
										");
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

	public function delete(){
		if(checkingsessionpwt()){
			$url 		= "Stok";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$rows 	= $this->query->deleteData('stok_order','id_order',$cond);
			$rows2 	= $this->query->deleteData('stok_order_detail','id_order',$cond);
			$rows3 	= $this->query->deleteData('stok_order_payment','id_order',$cond);
			
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);

				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$cond ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function payment(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$url 		= "Stok";
			$activity 	= "PAYMENT";
			
			$id 			= trim(strip_tags(stripslashes($this->input->post('idorder',true))));
			$jmlbayar 		= trim(strip_tags(stripslashes($this->input->post('jmlbayar',true))));
			$tgl 			= trim(strip_tags(stripslashes($this->input->post('tglbayar',true))));

			$userdata 		= $this->session->userdata('sesspwt'); 
			
			$userid			= $userdata['userid'];

			$rows 			= $this->db->query("
							INSERT INTO stok_order_payment (id_order,total,createddate) values 
							('$id','$jmlbayar','$tgl')
							");
			
			if($rows) {
				// GET DATA ORDER
				$getOrder 	= $this->db->query("
							SELECT * FROM stok_order where id_order='$id'
							")->result_array();
				$dOrder 	= array_shift($getOrder);
				$valbef 	= $dOrder['bayar'];

				$valafter 	= $valbef+$jmlbayar;

				if ($valafter>=$dOrder['total_harga']) {
					$upstatus 	= "status = 2,";
				} else {
					$upstatus 	= '';
				}

				$updSaldo 	= $this->db->query("
							UPDATE stok_order SET 
								$upstatus
								bayar 			= '$valafter'
							WHERE id_order='$id'
							");
				$log 		= $this->query->insertlog($activity,$url,$id);
				
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	

	public function setfinish(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$url 		= "Stok";
			$activity 	= "FINISH";
			
			$id 			= trim(strip_tags(stripslashes($this->input->post('idorderf',true))));

			$userdata 		= $this->session->userdata('sesspwt'); 
			
			$userid			= $userdata['userid'];

			$rows 			= $this->db->query("
							UPDATE stok_order set is_finish=1 where id_order='$id'
							");
			
			if($rows) {
				$log 		= $this->query->insertlog($activity,$url,$id);
				
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}

	public function cektotal(){
		if(checkingsessionpwt()){
			$sup		= trim(strip_tags(stripslashes($this->input->post('suplier',true))));
			$getColor 	= $this->db->query("
						SELECT * from color where type='1' order by 1
						")->result_array();

			$getSize 	= $this->db->query("
						SELECT * from size order by sort
						")->result_array();

			foreach ($getColor as $color) {
				$colid 	= $color['id'];
				foreach($getSize as $size) {
					$sizeid 	= $size['id_size']; 
					$getPrice 	= $this->db->query("
								SELECT * from suplier_harga where id_suplier='$sup' and id_size='$sizeid'
								")->result_array();
					$sprice 	= array_shift($getPrice);
					$price 		= $sprice['harga'];
					// $price 		= $size['hpp'];

					$jml		= trim(strip_tags(stripslashes($this->input->post('pcscol'.$colid.'siz'.$sizeid,true))));
					@$subtotal	+= $jml*$price;
					@$subjml	+= $jml;
				}
			}
			
			if(@$subtotal>0) {
				print json_encode(array('total'=>$subtotal, 'jml'=>$subjml));
			} else {
				print json_encode(array('total'=>$subtotal, 'jml'=>$subjml));
			}
		}else{
            redirect('/login');
        }
	}

	public function cektotaledit(){
		if(checkingsessionpwt()){
			$sup		= trim(strip_tags(stripslashes($this->input->post('ed_suplier',true))));
			$getColor 	= $this->db->query("
						SELECT * from color where type='1' order by 1
						")->result_array();

			$getSize 	= $this->db->query("
						SELECT * from size order by sort
						")->result_array();

			foreach ($getColor as $color) {
				$colid 	= $color['id'];
				foreach($getSize as $size) {
					$sizeid 	= $size['id_size']; 
					$getPrice 	= $this->db->query("
								SELECT * from suplier_harga where id_suplier='$sup' and id_size='$sizeid'
								")->result_array();
					$sprice 	= array_shift($getPrice);
					$price 		= $sprice['harga'];
					// $price 		= $size['hpp'];

					$jml		= trim(strip_tags(stripslashes($this->input->post('ed_pcscol'.$colid.'siz'.$sizeid,true))));
					@$subtotal	+= $jml*$price;
					@$subjml	+= $jml;
				}
			}
			
			if(@$subtotal>0) {
				print json_encode(array('total'=>$subtotal, 'jml'=>$subjml));
			} else {
				print json_encode(array('total'=>$subtotal, 'jml'=>$subjml));
			}
		}else{
            redirect('/login');
        }
	}

	public function getorder(){
		if(checkingsessionpwt()){
			$id 		= trim(strip_tags(stripslashes($this->input->post('id',true))));

			$getDetail 	= $this->db->query("SELECT * FROM stok_order where id_order='$id'")->result_array();
			$dDetail 	= array_shift($getDetail);
			$idsuplier 	= $dDetail['id_suplier'];

			$getColor 	= $this->db->query("
						SELECT * from color where type=1 order by 1
						")->result_array();

			$getSize 	= $this->db->query("
						SELECT * from size where 
						id_size in (
							SELECT id_size from suplier_harga where id_suplier='$idsuplier'
						)
						order by sort
						")->result_array();

			echo '
			<table class="table table-striped- table-bordered table-hover table-sm">
				<thead>
					<tr>
						<th style="width: 150px!important;">UKURAN</th>';
						// foreach($getSize as $size) {
						foreach ($getColor as $color) {
						echo '<th style="max-width: 50px!important;" class="text-center">'.$color['label'].'</th>';
						}
					echo '
					</tr>
				</thead>
				<tbody>
			';
			foreach($getSize as $size) {
				$sizeid 	= $size['id_size'];

				echo '
				<tr>
					<td>'.$size['label'].'</td>';
					foreach ($getColor as $color) {
						$colid 	= $color['id'];

						$getJml 	= $this->db->query("
									SELECT * FROM stok_order_detail where id_order='$id' and size='$sizeid' and color='$colid'
									")->result_array();
						$dJml 		= array_shift($getJml);
						$jml 		= $this->formula->rupiah3($dJml['jml_order']);
						$price 		= $dJml['harga'];
					
					echo '
					<td class="text-center">
						<input type="number" name="ed_pcscol'.$colid.'siz'.$sizeid.'" class="form-control ed_pcsval" id="ed_pcs" placeholder="0" style="width: 100%;" value="'.$jml.'">
					</td>';
					}
				echo '</tr>';
			}
			echo '
			</tbody></table>
			';
		}else{
            redirect('/login');
        }
	}

	public function getorderdet(){
		if(checkingsessionpwt()){
			$id 		= trim(strip_tags(stripslashes($this->input->post('id',true))));

			$getDetail 	= $this->db->query("
						SELECT a.*, 
							(SELECT nama_suplier from suplier where id=a.id_suplier) suplier
						FROM stok_order a where id_order='$id'
						")->result_array();
			$dDetail 	= array_shift($getDetail);
			$idsuplier 	= $dDetail['id_suplier'];
			$suplier 	= $dDetail['suplier'];
			$tgl 		= $dDetail['createddate'];
			$ket 		= $dDetail['label'];
			$jmlP 		= $dDetail['jml'];
			$total 		= $dDetail['total_harga'];
			$jmlbayar 	= $dDetail['bayar'];
			$sisaB 		= $total-$jmlbayar;

			$getColor 	= $this->db->query("
						SELECT * from color where type=1 order by 1
						")->result_array();

			$getSize 	= $this->db->query("
						SELECT a.*, (select label_size from suplier_harga where id_suplier='$idsuplier' and id_size=a.id_size) label_size from size a where 
						id_size in (
							SELECT id_size from suplier_harga where id_suplier='$idsuplier'
						)
						order by sort
						")->result_array();

			echo '

			<div class="row">
				<label for="role" class="col-lg-2 col-sm-12 col-form-label">Suplier</label>
				<label for="role" class="col-lg-10 col-sm-12 col-form-label">: <b>'.$suplier.'</b></label>
			</div>

			<div class="row">
				<label for="role" class="col-lg-2 col-sm-12 col-form-label">Tgl. Transaksi</label>
				<label for="role" class="col-lg-10 col-sm-12 col-form-label">: <b>'.$tgl.'</b></label>
			</div>

			<div class="row">
				<label for="role" class="col-lg-2 col-sm-12 col-form-label">Keterangan</label>
				<label for="role" class="col-lg-10 col-sm-12 col-form-label">: <b>'.$ket.'</b></label>
			</div>

			<table class="table table-striped- table-bordered table-hover table-checkable">
				<thead>
					<tr>
						<th style="width: 150px!important;">UKURAN</th>';
						foreach($getColor as $color) {
						echo '
						<th style="max-width: 50px!important;" class="text-center"><i class="fa fa-circle" style="color: '.$color['code_color'].';"></i> '.$color['label'].'</th>';
						}
					echo '
					</tr>
				</thead>
				<tbody>
					';
					foreach ($getSize as $size) {
						$sizeid = $size['id_size'];
						echo '
						<tr>
							<td>'.$size['label_size'].'</td>';
							foreach($getColor as $color) {
								$colid 		= $color['id']; 

								$getJml 	= $this->db->query("
											SELECT * FROM stok_order_detail where id_order='$id' and size='$sizeid' and color='$colid'
											")->result_array();
								$dJml 		= array_shift($getJml);
								$jml 		= $this->formula->rupiah3($dJml['jml_order']);
								$price 		= $dJml['harga'];

								$sisastokfin = $jml;
								if ($sisastokfin<1) {
									$colte	= 'style="color: #bdbcbc;"';	
								} else if ($sisastokfin>0 and $sisastokfin<5) {
									$colte	= 'style="color: #cf5555;"';	
								} else {
									$colte	= '';
								}
							echo '
							<td class="text-center" '.$colte.'>
								<b>'.$sisastokfin.'</b> pcs
							</td>';
							}
						echo '
						</tr>';
					}
					echo '
				</tbody>
			</table>

			<div class="row">
				<div class="col-lg-9 col-7 text-right">
					<h5>Jumlah :</h5>
				</div>
				<div class="col-lg-3 col-5 text-right">
					<h5>'.round($jmlP).' PCS</h5>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-9 col-7 text-right">
					<h3>Grand Total :</h3>
				</div>
				<div class="col-lg-3 col-5 text-right">
					<h3>'.$this->formula->rupiah($total).'</h3>	
				</div>
			</div>
			<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

			<div class="row">
				<div class="col-lg-9 col-7 text-right">
					<h5>Total Pembayaran :</h5>
				</div>
				<div class="col-lg-3 col-5 text-right">
					<h5 class="text-success">'.$this->formula->rupiah($jmlbayar).'</h5>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-9 col-7 text-right">
					<h5>Sisa Pembayaran :</h5>
				</div>
				<div class="col-lg-3 col-5 text-right">
					<h5 class="text-danger">'.$this->formula->rupiah($sisaB).'</h5>	
				</div>
			</div>
			';
		}else{
            redirect('/login');
        }
	}

	public function getlist(){
		if(checkingsessionpwt()){
			$id 		= trim(strip_tags(stripslashes($this->input->post('id',true))));		

			$getColor 	= $this->db->query("
						SELECT * from color where type=1 order by 1
						")->result_array();

			$getSize 	= $this->db->query("
						SELECT * from size where 
						id_size in (
							SELECT id_size from suplier_harga where id_suplier='$id'
						)
						order by sort
						")->result_array();

			echo '
			<table class="table table-striped- table-bordered table-hover table-sm">
				<thead>
					<tr>
						<th style="width: 150px!important;">WARNA</th>';
						foreach($getSize as $size) {
						echo '<th style="max-width: 50px!important;" class="text-center">'.$size['label'].'</th>';
						}
					echo '
					</tr>
				</thead>
				<tbody>
			';
			foreach ($getColor as $color) {
				$colid 	= $color['id'];

				echo '
				<tr>
					<td>'.$color['label'].'</td>';

					foreach($getSize as $size) {
						$sizeid 	= $size['id_size'];

						$getPrice 	= $this->db->query("
									SELECT * from suplier_harga where id_suplier='$id' and id_size='$sizeid'
									")->result_array();
						$sprice 	= array_shift($getPrice);
						// $prc 	= $size['hpp'];
						$prc 		= $sprice['harga'];
						@$tjml 		+= $value;
						@$tprc 		+= $value*$prc;
					
					echo '
					<td class="text-center">
						<input type="number" name="pcscol'.$colid.'siz'.$sizeid.'" class="form-control pcsval" id="pcs" placeholder="0" style="width: 100%;" value="0">
					</td>';
					}
				echo '</tr>';
			}
			echo '
			</tbody></table>
			';
		}else{
            redirect('/login');
        }
	}

}
