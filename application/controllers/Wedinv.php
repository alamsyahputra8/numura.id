<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wedinv extends CI_Controller {

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
		// if(checkingsessionpwt()){
			// $this->load->view('panel/dashboard');
		// } else {
			// redirect('/panel');
		// }
	}

	public function insert(){
		$url 				= "Wedding Invitation";
		$activity 			= "INSERT";

		$orderid			= trim(strip_tags(stripslashes($this->input->post('oid',true))));
		$gsname				= trim(strip_tags(stripslashes($this->input->post('gsname',true))));
		$gsmsg 				= trim(strip_tags(stripslashes($this->input->post('gsmsg',true))));
		$now 				= date('Y-m-d');

		$rows 				= $this->db->query("
							INSERT INTO detail_ucapan (orderid,name,msg,createddate) values 
							('$orderid','$gsname','$gsmsg','$now')
							");
		
		if($rows) {
			$id				= $this->db->insert_id();
			$log 			= $this->query->insertlog($activity,$url,$orderid);

			print json_encode(array('success'=>true,'total'=>1));
		} else {
			echo "";
		}
	}	

	public function reload($id){
		$dataRoles	= $this->db->query("
					SELECT 
							a.*
						from detail_ucapan a where a.orderid ='$id' order by id desc
					")->result_array();

		if (isset($id) && !empty($id)) {
			foreach($dataRoles as $ucp) {
				echo '
				<div class="user-guestbook">
					<div>
						<img src="'.base_url().'assets/themepw1/wp-content/uploads/2020/12/07-2.png" title="07.png" alt="07.png" />
					</div>

					<div class="guestbook">
						<div class="guestbook-name">'.$ucp['name'].'</div>
						<div class="guestbook-message">'.$ucp['msg'].'</div>
					</div>
				</div>
				';
			}
		}
	}	

	public function update(){
		if(checkingsessionpwt()){
			$userdata		= $this->session->userdata('sesspwt'); 
			$url 			= "Stok";
			$activity 		= "UPDATE";
			
			$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
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
								SELECT * from color order by 1
								")->result_array();

				$getSize 		= $this->db->query("
								SELECT * from size order by sort
								")->result_array();

				foreach ($getColor as $color) {
					$colid 	= $color['id'];
					foreach($getSize as $size) {
						$sizeid 		= $size['id_size']; 
						$price 			= $size['hpp'];

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
			$getColor 	= $this->db->query("
						SELECT * from color order by 1
						")->result_array();

			$getSize 	= $this->db->query("
						SELECT * from size order by sort
						")->result_array();

			foreach ($getColor as $color) {
				$colid 	= $color['id'];
				foreach($getSize as $size) {
					$sizeid 	= $size['id_size']; 
					$price 		= $size['hpp'];

					$jml		= trim(strip_tags(stripslashes($this->input->post('pcscol'.$colid.'siz'.$sizeid,true))));
					@$subtotal	+= $jml*$price;
					@$subjml	+= $jml;
				}
			}
			
			if(@$subtotal>0) {
				print json_encode(array('total'=>$subtotal, 'jml'=>$subjml));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function cektotaledit(){
		if(checkingsessionpwt()){
			$getColor 	= $this->db->query("
						SELECT * from color order by 1
						")->result_array();

			$getSize 	= $this->db->query("
						SELECT * from size order by sort
						")->result_array();

			foreach ($getColor as $color) {
				$colid 	= $color['id'];
				foreach($getSize as $size) {
					$sizeid 	= $size['id_size']; 
					$price 		= $size['hpp'];

					$jml		= trim(strip_tags(stripslashes($this->input->post('ed_pcscol'.$colid.'siz'.$sizeid,true))));
					@$subtotal	+= $jml*$price;
					@$subjml	+= $jml;
				}
			}
			
			if(@$subtotal>0) {
				print json_encode(array('total'=>$subtotal, 'jml'=>$subjml));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function getorder(){
		if(checkingsessionpwt()){
			$id 		= trim(strip_tags(stripslashes($this->input->post('id',true))));

			$getColor 	= $this->db->query("
						SELECT * from color order by 1
						")->result_array();

			$getSize 	= $this->db->query("
						SELECT * from size order by sort
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

}
