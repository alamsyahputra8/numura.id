<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Einvit extends CI_Controller {

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

        $this->dbw = $this->load->database('dbw', TRUE);

        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->model('query'); 
		$this->load->model('formula'); 
		$this->load->model('datatable'); 
		$this->load->model('einvit_handler');
		
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
				'orderid'		=> true,
				'link'			=> true,
				'pria'			=> true,
				'wanita'		=> true,
				'tgl'			=> true,
				'createddate'	=> true,
				'status'		=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;

			// $jsonfile	= base_url().'user/data';
			$jsonfile	= $this->einvit_handler->data();

			$this->datatable->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function ceklink(){
		if(checkingsessionpwt()){
			$ceklink		= trim(strip_tags(stripslashes($this->input->post('ceklink',true))));
			
			$data			= $this->dbw->query("
							SELECT 
								a.*
							from person_order a where a.name ='$ceklink'
							")->num_rows();
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($ceklink) && !empty($ceklink)) {
				$row 		= array(
								'isavail' 	=> $data
							);
				echo json_encode($row);
				exit;
			}
		} else {
			redirect('/panel');
		}
	}	

	public function testdir(){
		$nextyear 			= date('Y')+1;
		$expired 			= $nextyear.'-'.date('m-d H:i:00');
		echo $expired;
	}

	public function insert(){
		if(checkingsessionpwt()){
			$url 				= "Digital Invitation";
			$activity 			= "INSERT";

			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid				= $userdata['userid'];

			$nowdate	= date('Ym');
			$nowoid		= date('Ymd');
			$qoid 		= $this->dbw->query("
						SELECT count(*)+1 nexid FROM person_order where orderid like '$nowdate%'
						")->result_array();
			$goid 		= array_shift($qoid);
			$invID		= $goid['nexid'];
			$noid 		= str_pad($invID, 4, '0', STR_PAD_LEFT);
			$orderid 	= $nowoid.$noid;

			$link				= trim(strip_tags(stripslashes($this->input->post('link',true))));
			$theme 				= trim(strip_tags(stripslashes($this->input->post('theme',true))));
			$wddatetext 		= trim(strip_tags(stripslashes($this->input->post('wddatetext',true))));
			$wddate 			= trim(strip_tags(stripslashes($this->input->post('wddate',true))));
			
			$modlive 			= trim(strip_tags(stripslashes($this->input->post('modlive',true))));
			$acciglive 			= trim(strip_tags(stripslashes($this->input->post('acciglive',true))));
			$livetime 			= trim(strip_tags(stripslashes($this->input->post('livetime',true))));

			$rsvpno 			= trim(strip_tags(stripslashes($this->input->post('rsvpno',true))));

			$bankampau 			= trim(strip_tags(stripslashes($this->input->post('bankampau',true))));
			$banknorek 			= trim(strip_tags(stripslashes($this->input->post('banknorek',true))));
			$bankan 			= trim(strip_tags(stripslashes($this->input->post('bankan',true))));

			$giftnama 			= trim(strip_tags(stripslashes($this->input->post('giftnama',true))));
			$gifthp 			= trim(strip_tags(stripslashes($this->input->post('gifthp',true))));
			$giftalamat 		= trim(strip_tags(stripslashes($this->input->post('giftalamat',true))));

			$backsound 			= $_FILES['backsound']['name'];

			$prokes 			= str_replace("'",'`',trim(strip_tags(stripslashes($this->input->post('prokes',true)))));
			$price 				= trim(strip_tags(stripslashes($this->input->post('price',true))));
			$status 			= trim(strip_tags(stripslashes($this->input->post('status',true))));

			$modig	 			= trim(strip_tags(stripslashes($this->input->post('modig',true))));

			$king	 			= trim(strip_tags(stripslashes($this->input->post('king',true))));
			$nickm	 			= trim(strip_tags(stripslashes($this->input->post('nickm',true))));
			$sonof	 			= trim(strip_tags(stripslashes($this->input->post('sonof',true))));
			$pictm 				= $_FILES['pictm']['name'];
			$igm	 			= trim(strip_tags(stripslashes($this->input->post('igm',true))));

			$queen	 			= trim(strip_tags(stripslashes($this->input->post('queen',true))));
			$nickf	 			= trim(strip_tags(stripslashes($this->input->post('nickf',true))));
			$daughterof	 		= trim(strip_tags(stripslashes($this->input->post('daughterof',true))));
			$pictf 				= $_FILES['pictf']['name'];
			$igf	 			= trim(strip_tags(stripslashes($this->input->post('igf',true))));

			$akaddate	 		= trim(strip_tags(stripslashes($this->input->post('akaddate',true))));
			$akadstart	 		= trim(strip_tags(stripslashes($this->input->post('akadstart',true))));
			$akadto	 			= trim(strip_tags(stripslashes($this->input->post('akadto',true))));
			$akadat	 			= trim(strip_tags(stripslashes($this->input->post('akadat',true))));

			$resepsidate	 	= trim(strip_tags(stripslashes($this->input->post('resepsidate',true))));
			$resepsistart	 	= trim(strip_tags(stripslashes($this->input->post('resepsistart',true))));
			$resepsito	 		= trim(strip_tags(stripslashes($this->input->post('resepsito',true))));
			$resepsiat	 		= trim(strip_tags(stripslashes($this->input->post('resepsiat',true))));

			$embedmap	 		= $_POST['embedmap'];
			$linkmap	 		= trim(strip_tags(stripslashes($this->input->post('linkmap',true))));

			$quotes	 			= str_replace("'",'`',trim(strip_tags(stripslashes($this->input->post('quotes',true)))));
			$qby	 			= trim(strip_tags(stripslashes($this->input->post('qby',true))));
			$qbg 				= $_FILES['qbg']['name'];

			$banner 			= $_FILES['banner']['name'];
			$gallery 			= $_FILES['gallery']['name'];

			$nextyear 			= date('Y')+1;
			$expired 			= $nextyear.'-'.date('m-d H:i:00');

			$nowcreate			= date('Y-m-d H:i:s');

			mkdir('../images/wedding/'.$link, 0777, TRUE);

			$dirfile 				= '../images/wedding/'.$link.'/';

			// UPLOAD BACKSOUND
			$lokasi_file    		= $_FILES['backsound']['tmp_name'];
			$tipe_file      		= $_FILES['backsound']['type'];
			$nama_file  	     	= $_FILES['backsound']['name'];
			$filenamemusic 	 		= str_replace(' ','_',$nama_file);
			$extension 				= pathinfo($nama_file, PATHINFO_EXTENSION);
			if (!empty($lokasi_file)){
				$vfile_upload 		= $dirfile . $filenamemusic;
				move_uploaded_file($lokasi_file, $vfile_upload);
			}

			// UPLOAD BG QUOTES
			$lokasi_fileQ    		= $_FILES['qbg']['tmp_name'];
			$tipe_fileQ      		= $_FILES['qbg']['type'];
			$nama_fileQ  	     	= $_FILES['qbg']['name'];
			$filequotes 	 		= str_replace(' ','_',$nama_fileQ);
			$extensionQ 			= pathinfo($nama_fileQ, PATHINFO_EXTENSION);
			if (!empty($lokasi_fileQ)){
				$vfile_uploadQ 		= $dirfile . $filequotes;
				move_uploaded_file($lokasi_fileQ, $vfile_uploadQ);
			}

			$rows 				= $this->dbw->query("
								INSERT INTO person_order (orderid, name, weddingdate, theday, package, expired, music, theme, prokes, module_ig, rsvp_number, module_live, account_ig, time_live, angpau_bank, angpau_norek, angpau_an, gift_penerima, gift_alamat, gift_hp, img_quotes, status, price, createddate) values 
								('$orderid', '$link', '$wddatetext', '$wddate', '1', '$expired', '$filenamemusic', '$theme', '$prokes', '$modig', '$rsvpno', '$modlive', '$acciglive', '$livetime', '$bankampau', '$banknorek', '$bankan', '$giftnama', '$giftalamat', '$gifthp', '$filequotes', '$status', '$price', '$nowcreate')
								");
			$id					= $this->dbw->insert_id();

			// UPLOAD BANNER
			$jmlBanner 		= count($banner);
			for($jb=0;$jb<$jmlBanner;$jb++) {
				$lokasi_file[$jb]    	= $_FILES['banner']['tmp_name'][$jb];
				$tipe_file[$jb]      	= $_FILES['banner']['type'][$jb];
				$nama_file[$jb]      	= $_FILES['banner']['name'][$jb];
				$nama_file_unik[$jb] 	= str_replace(' ','_',$nama_file[$jb]);

				echo $banner;

				$extension 				= pathinfo($nama_file[$jb], PATHINFO_EXTENSION);
				if (!empty($lokasi_file[$jb])){
					$vfile_upload[$jb] = $dirfile . $nama_file_unik[$jb];
					move_uploaded_file($lokasi_file[$jb], $vfile_upload[$jb]);

					$insBanner 			= $this->dbw->query("
										INSERT INTO detail_banner (orderid,pict,sort,flag_bg) values 
										('$id','$nama_file_unik[$jb]','$jb','0')
										");
				}
			}

			// UPLOAD GALLERY
			$jmlGallery 				= count($gallery);
			for($jg=0;$jg<$jmlGallery;$jg++) {
				$lokasi_file[$jg]    	= $_FILES['gallery']['tmp_name'][$jg];
				$tipe_file[$jg]      	= $_FILES['gallery']['type'][$jg];
				$nama_file[$jg]      	= $_FILES['gallery']['name'][$jg];
				$nama_file_unik[$jg] 	= str_replace(' ','_',$nama_file[$jg]);
				$extension 				= pathinfo($nama_file[$jg], PATHINFO_EXTENSION);
				if (!empty($lokasi_file[$jg])){
					$vfile_upload[$jg] = $dirfile . $nama_file_unik[$jg];
					move_uploaded_file($lokasi_file[$jg], $vfile_upload[$jg]);

					$insGallery 		= $this->dbw->query("
										INSERT INTO detail_gallery (orderid,pict,thumb,sort) values 
										('$id','$nama_file_unik[$jg]','$nama_file_unik[$jg]','$jg')
										");
				}
			}
			
			if($rows) {
				// UPLOAD PROFILE KING
				$lokasi_fileM    		= $_FILES['pictm']['tmp_name'];
				$tipe_fileM      		= $_FILES['pictm']['type'];
				$nama_fileM  	     	= $_FILES['pictm']['name'];
				$filem 	 				= str_replace(' ','_',$nama_fileM);
				$extensionM 			= pathinfo($nama_fileM, PATHINFO_EXTENSION);
				if (!empty($lokasi_fileM)){
					$vfile_uploadM 		= $dirfile . $filem;
					move_uploaded_file($lokasi_fileM, $vfile_uploadM);
				}

				// UPLOAD PROFILE QUEEN
				$lokasi_fileF    		= $_FILES['pictf']['tmp_name'];
				$tipe_fileF      		= $_FILES['pictf']['type'];
				$nama_fileF  	     	= $_FILES['pictf']['name'];
				$filef 	 				= str_replace(' ','_',$nama_fileF);
				$extensionF 			= pathinfo($nama_fileF, PATHINFO_EXTENSION);
				if (!empty($lokasi_fileF)){
					$vfile_uploadF 		= $dirfile . $filef;
					move_uploaded_file($lokasi_fileF, $vfile_uploadF);
				}

				// INSERT DETAIL
				$insDetail 		= $this->dbw->query("
								INSERT INTO detail_person (orderid, man, nicknamem, igm, sonof, pictm, woman, nicknamew, igw, daughterof, pictw, akadat, akaddate, akadtime, akadto, reseptionat, reseptiondate, reseptiontime, reseptionto, maps, maplink, quotes, quotesby)
								values
								('$id', '$king', '$nickm', '$igm', '$sonof', '$filem', '$queen', '$nickf', '$igf', '$daughterof', '$filef', '$akadat', '$akaddate', '$akadstart', '$akadto', '$resepsiat', '$resepsidate', '$resepsistart', '$resepsito', '$embedmap', '$linkmap', '$quotes', '$qby')
								");

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

			$dataRoles			= $this->dbw->query("
								SELECT 
									a.*
								from person_order a where a.id ='$id'
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
			$userdata 		= $this->session->userdata('sesspwt'); 
			
			$userid			= $userdata['userid'];

			$tgl			= trim(strip_tags(stripslashes($this->input->post('ed_tgl',true))));
			$label 			= trim(strip_tags(stripslashes($this->input->post('ed_label',true))));
			$jml 			= trim(strip_tags(stripslashes($this->input->post('ed_totalpcs',true))));
			$total 			= trim(strip_tags(stripslashes($this->input->post('ed_total',true))));

			$rows 			= $this->dbw->query("
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
				$getColor 		= $this->dbw->query("
								SELECT * from color order by 1
								")->result_array();

				$getSize 		= $this->dbw->query("
								SELECT * from size order by sort
								")->result_array();

				foreach ($getColor as $color) {
					$colid 	= $color['id'];
					foreach($getSize as $size) {
						$sizeid 		= $size['id_size']; 
						$price 			= $size['hpp_pjg'];

						$pcs			= trim(strip_tags(stripslashes($this->input->post('ed_pcscol'.$colid.'siz'.$sizeid,true))));

						$insertDetail 	= $this->dbw->query("
										INSERT INTO stok_order_detail (id_order,size,color,jml_order,harga,status,type) values 
										('$id','$sizeid','$colid','$pcs','$price','1','2')
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
			$url 		= "Digital Invitation";
			$activity 	= "DELETE";

			$cond		= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			// GET INFO
			$q 			= $this->dbw->query("
						SELECT * FROM person_order where id='$cond'
						")->result_array();
			$d 			= array_shift($q);

			$link 		= $d['name'];

			if (!empty($link)) {
				$dirPath 	= '../images/wedding/'.$link.'/';
				
				// DELETE FOLDER AND FILES FIRST
				$dir = '..' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'wedding'  . DIRECTORY_SEPARATOR . $link;
				$it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
				$files = new RecursiveIteratorIterator($it,
				             RecursiveIteratorIterator::CHILD_FIRST);
				foreach($files as $file) {
				    if ($file->isDir()){
				        rmdir($file->getRealPath());
				    } else {
				        unlink($file->getRealPath());
				    }
				}
				rmdir($dir);
			}

			$rows 	= $this->dbw->query("DELETE FROM person_order where id='$cond'");
			$rows2 	= $this->dbw->query("DELETE FROM detail_person where orderid='$cond'");
			$rows3 	= $this->dbw->query("DELETE FROM detail_banner where orderid='$cond'");
			$rows4 	= $this->dbw->query("DELETE FROM detail_gallery where orderid='$cond'");
			
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

			$rows 			= $this->dbw->query("
							INSERT INTO stok_order_payment (id_order,total,createddate) values 
							('$id','$jmlbayar','$tgl')
							");
			
			if($rows) {
				// GET DATA ORDER
				$getOrder 	= $this->dbw->query("
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

				$updSaldo 	= $this->dbw->query("
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

	public function cektotal(){
		if(checkingsessionpwt()){
			$getColor 	= $this->dbw->query("
						SELECT * from color order by 1
						")->result_array();

			$getSize 	= $this->dbw->query("
						SELECT * from size order by sort
						")->result_array();

			foreach ($getColor as $color) {
				$colid 	= $color['id'];
				foreach($getSize as $size) {
					$sizeid 	= $size['id_size']; 
					$price 		= $size['hpp_pjg'];

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
			$getColor 	= $this->dbw->query("
						SELECT * from color order by 1
						")->result_array();

			$getSize 	= $this->dbw->query("
						SELECT * from size order by sort
						")->result_array();

			foreach ($getColor as $color) {
				$colid 	= $color['id'];
				foreach($getSize as $size) {
					$sizeid 	= $size['id_size']; 
					$price 		= $size['hpp_pjg'];

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

			$getColor 	= $this->dbw->query("
						SELECT * from color order by 1
						")->result_array();

			$getSize 	= $this->dbw->query("
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

						$getJml 	= $this->dbw->query("
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
