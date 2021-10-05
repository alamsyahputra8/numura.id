<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesanan extends CI_Controller {

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
		$this->load->model('pesanan_handler');
		
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

	public function getdata($status){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'char'			=> true,
				'name'			=> true,
				'size'			=> true,
				'color'			=> true,
				'price'			=> true,
				'typekaos'		=> true,
				'tglpesan'		=> true,
				'createdby' 	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;

			// $jsonfile	= base_url().'user/data';
			$jsonfile	= $this->pesanan_handler->data($status);

			$this->datatable->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function insert(){
		if(checkingsessionpwt()){
			$url 				= "Pesanan";
			$activity 			= "INSERT";

			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid				= $userdata['userid'];
			$date				= date('Y-m-d');

			$name				= str_replace("'",'`',trim(strip_tags(stripslashes($this->input->post('name',true)))));
			$ukuran 			= trim(strip_tags(stripslashes($this->input->post('ukuran',true))));
			$type 				= trim(strip_tags(stripslashes($this->input->post('type',true))));
			$warna 				= trim(strip_tags(stripslashes($this->input->post('warna',true))));
			$typedesign 		= trim(strip_tags(stripslashes($this->input->post('typedesign',true))));
			$karakter 			= trim(strip_tags(stripslashes($this->input->post('karakter',true))));

			$getSize 			= $this->db->query("
								SELECT * FROM size where id_size='$ukuran'
								")->result_array();
			$dSize 				= array_shift($getSize);
			if ($type==1) {
				$gprice 		= $dSize['harga'];
				$normprice 		= $dSize['harga'];
			} else {
				$gprice 		= $dSize['harga_pjg'];
				$normprice 		= $dSize['harga_pjg'];
			}

			$getAddPrice		= $this->db->query("
								SELECT * FROM design_type where id='$typedesign'
								")->result_array();
			$dAddPrice 			= array_shift($getAddPrice);
			$addprice 			= $dAddPrice['harga_add'];

			$price 				= $gprice+$addprice;

			$getChar 			= $this->db->query("
								SELECT * FROM karakter where id_karakter='$karakter'
								")->result_array();
			$dChar 				= array_shift($getChar);
			$typeChar 			= $dChar['type'];
			
			// if($userid==288) {
				// echo "";
				// exit();
			// }

			$cekLimit 			= $this->db->query("SELECT * FROM pesanan where status=1")->num_rows();
			if ($cekLimit>300) {
				echo "";
			} else {
				$rows 				= $this->db->query("
									INSERT INTO pesanan (userid,custom_nama,ukuran,warna,karakter,harga,harga_normal,tanggal_pesan,status,kaos_type,design_type) values 
									('$userid','$name','$ukuran','$warna','$karakter','$price','$normprice','$date','1','$type','$typeChar')
									");
				
				if($rows) {
					$id				= $this->db->insert_id();
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
			
			$dataRoles			= $this->db->query("
								SELECT 
								a.*
								from pesanan a where a.id_pesanan ='$id'
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
			$userdata	= $this->session->userdata('sesspwt'); 
			$url 		= "Pesanan";
			$activity 	= "UPDATE";
			
			$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$userdata 		= $this->session->userdata('sesspwt'); 
			
			$userid			= $userdata['userid'];
			$date			= date('Y-m-d');

			$name				= str_replace("'",'`',trim(strip_tags(stripslashes($this->input->post('ed_name',true)))));
			$ukuran 			= trim(strip_tags(stripslashes($this->input->post('ed_ukuran',true))));
			$type 				= trim(strip_tags(stripslashes($this->input->post('ed_type',true))));
			$warna 				= trim(strip_tags(stripslashes($this->input->post('ed_warna',true))));
			$karakter 			= trim(strip_tags(stripslashes($this->input->post('ed_karakter',true))));
			$typedesign 		= trim(strip_tags(stripslashes($this->input->post('ed_typedesign',true))));

			$getSize 			= $this->db->query("
								SELECT * FROM size where id_size='$ukuran'
								")->result_array();
			$dSize 				= array_shift($getSize);
			if ($type==1) {
				$gprice 		= $dSize['harga'];
				$normprice 		= $dSize['harga_normal'];
			} else {
				$gprice 		= $dSize['harga_pjg'];
				$normprice 		= $dSize['harga_pjg'];
			}

			$getAddPrice		= $this->db->query("
								SELECT * FROM design_type where id='$typedesign'
								")->result_array();
			$dAddPrice 			= array_shift($getAddPrice);
			$addprice 			= $dAddPrice['harga_add'];

			$price 				= $gprice+$addprice;

			$getChar 			= $this->db->query("
								SELECT * FROM karakter where id_karakter='$karakter'
								")->result_array();
			$dChar 				= array_shift($getChar);
			$typeChar 			= $dChar['type'];

			$rows 			= $this->db->query("
							UPDATE pesanan SET 
								custom_nama 	= '$name',
								ukuran			= '$ukuran',
								warna			= '$warna',
								karakter		= '$karakter',
								harga			= '$price',
								harga_normal	= '$normprice',
								kaos_type		= '$type',
								design_type		= '$typeChar'
							WHERE id_pesanan='$id'
							");
			
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

	public function delete(){
		if(checkingsessionpwt()){
			$url 		= "Pesanan";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$rows = $this->query->deleteData('pesanan','id_pesanan',$cond);
			
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

	public function updateprint(){
		if(checkingsessionpwt()){
			$url 		= "Pesanan";
			
			$cond		= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows 		= $this->db->query("UPDATE pesanan set flag_print='1' where id_pesanan='$cond'");
			
			if(isset($rows)) {
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$cond ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function approve(){
		if(checkingsessionpwt()){
			$url 		= "Pesanan";
			$activity 	= "PROSES";

			$id			= trim(strip_tags(stripslashes($this->input->post('idapp',true))));
			
			$rows 			= $this->db->query("
							UPDATE pesanan SET 
								status	= '2'
							WHERE id_pesanan='$id'
							");

			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$id);

				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}

	public function getSizeAvail(){
		if(checkingsessionpwt()){
			$sizeid		= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$getColor 	= $this->db->query("
						SELECT * from color order by 1
						")->result_array();
			
			foreach ($getColor as $color) {
				$colid 		= $color['id'];

				$getJml 	= $this->db->query("
							SELECT sum(jml_order) jml_order FROM stok_order_detail a left join stok_order b
							on a.id_order=b.id_order
							where a.size='$sizeid' and a.color='$colid' and a.type='1' and b.is_finish=1
							")->result_array();
				$dJml 		= array_shift($getJml);
				$jml 		= $this->formula->rupiah3($dJml['jml_order']);

				$qJml 		= "
							SELECT * FROM pesanan where status not in (3) and ukuran='$sizeid' 
							and warna='$colid' and kaos_type='1'
							";
				$cekJml 	= $this->db->query($qJml)->num_rows();

				$qSend 		= "
							SELECT * from pesanan where kaos_type='1' and id_pesanan in (
								select id_pesanan from pengiriman_detail where id_pengiriman in (
							    	select id_pengiriman from pengiriman where id_pengiriman in (
							            select data from data_log where menu='pengiriman' and activity='send' and date_time>='2020-10-25'
							        )
							    )
							) and ukuran='$sizeid' and warna='$colid'
							";
				$cekSend 	= $this->db->query($qSend)->num_rows();

				$availstok 	= ($jml-$cekJml)-$cekSend;

				if ($availstok>0) {
					echo '<option value="'.$color['id'].'">'.$color['label'].'</option>';
				} else {
					echo '';
				}
			}
		}else{
            redirect('/login');
        }
	}

	public function getCharacter(){
		if(checkingsessionpwt()){
			$idtype		= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$getChar 	= $this->db->query("
						SELECT * from karakter where type='$idtype' and is_active='1' order by nama
						")->result_array();
			
			foreach ($getChar as $char) {
				$id 		= $char['id_karakter'];
				echo '<option value="'.$id.'">'.$char['nama'].'</option>';
			}
		}else{
            redirect('/login');
        }
	}

	public function getImgChar(){
		if(checkingsessionpwt()){
			$idchar		= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$getChar 	= $this->db->query("
						SELECT * from karakter where id_karakter='$idchar'
						")->result_array();
			
			foreach ($getChar as $char) {
				$id 		= $char['id_karakter'];
				echo '<img src="'.base_url().'images/char/'.$char['file'].'" style="width: 300px;">';
			}
		}else{
            redirect('/login');
        }
	}
}
