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
		
		ini_set('max_execution_time', 123456);
		ini_set("memory_limit","1256M");
			
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

	public function insertproduktivitas(){
		// error_reporting(0);
		
		$convertName				= time().$_FILES['file_upload']['name'];
		$fileName 					= str_replace(' ','_',$convertName);
        $config['upload_path'] 		= './files/prod/'; //buat folder dengan nama assets di root folder
        $config['file_name'] 		= $fileName;
        $config['allowed_types'] 	= 'xls|xlsx|csv';
        $config['max_size'] = 10000;
         
        $this->load->library('upload');
        $this->upload->initialize($config);
         
        if(! $this->upload->do_upload('file_upload') )
        $this->upload->display_errors();
             
        $media			 	= $this->upload->data('file_upload');
        $inputFileName 		= './files/prod/'.$fileName;
        // $inputFileName 		= './files/'.$media['file_name'];
        // $inputFileName = './assets/file.xls';
         
        try {
                $inputFileType 	= IOFactory::identify($inputFileName);
                $objReader 		= IOFactory::createReader($inputFileType);
                $objPHPExcel 	= $objReader->load($inputFileName);
		} catch(Exception $e) {
				//die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
				echo "<script>
						alert('Terjadi Kesalahan , File yang di ijinkan csv | xls | xlxs !');
						window.location.href = '".base_url()."page/excel';
					  </script>";
		}
 
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            
			// echo $highestRow;
			
            for ($row = 2; $row <= $highestRow; $row++) {
				//  Read a row of data into an array                 
               $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE) ;
				
				// $start_date = date('d-M-Y', PHPExcel_Shared_Date::ExcelToPHP($rowData[0][9])); 
				
				$nik		= trim($rowData[0][0]);
				$dcg		= trim($rowData[0][1]);
				$rss		= trim($rowData[0][2]);
				$sumbudcg	= trim($rowData[0][3]);
				$sumburss	= trim($rowData[0][4]);
				$periode	= trim($rowData[0][5]);
				$kuadran	= trim($rowData[0][6]); 
				$idsegmen	= trim($rowData[0][7]);
				
				
				$rows 	= $this->query->insertData('productivity_am', "ID,NIK,DCG,RSS,SUMBU_DCG,SUMBU_RSS,PERIODE,KUADRAN,id_segmen", "'','$nik','$dcg','$rss','$sumbudcg','$sumburss','$periode','$kuadran','$idsegmen'");
				
            }
        // echo var_dump($rowData);
		// delete_files($media['file_path']);
		$url 		= "Manage Productivity";
		$activity 	= "IMPORT";
		
		$log = $this->query->insertlog($activity,$url,'');
		// print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
        echo "done";
	}
	
	public function deleteproduktivitas(){
		if(checkingsessionpwt()){
			$this->load->model('query');
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$url 		= "Manage Productivity AM";
			$activity 	= "DELETE";
			$rows = $this->query->deleteData('productivity_am','id',$cond);
			
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
	public function deletepots(){
		if(checkingsessionpwt()){
			$this->load->model('query');
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$url 		= "Manage POTS";
			$activity 	= "DELETE";
			$rows = $this->query->deleteData('pots','id',$cond);
			
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
	public function deletepots_rev(){
		if(checkingsessionpwt()){
			$this->load->model('query');
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$url 		= "Manage POTS";
			$activity 	= "DELETE";
			$rows = $this->query->deleteData('pots','id',$cond);
			
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
	
	public function modalproduktivitas(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataproduktivitas = $this->query->getData('productivity_am','*',"WHERE id='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataproduktivitas as $data) {
					
					
					$row = array(
						'ID'			=> $data['ID'],
						'NIK'			=> $data['NIK'],
						'DCG'			=> $data['DCG'],
						'RSS'			=> $data['RSS'],
						'SUMBU_DCG'		=> $data['SUMBU_DCG'],
						'SUMBU_RSS'		=> $data['SUMBU_RSS'],
						'PERIODE'		=> $data['PERIODE'],
						'KUADRAN'		=> $data['KUADRAN'],
						'id_segmen'		=> $data['id_segmen']
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
	
	public function modalpots(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataproduktivitas = $this->query->getData('pots p LEFT JOIN segmen s ON p.id_segmen = s.id_segmen LEFT JOIN gc g ON g.nipnas = p.nipnas LEFT JOIN witel w ON w.id_witel = p.id_witel','s.nama_segmen,g.nama_gc,w.nama_witel,p.*',"WHERE p.id='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataproduktivitas as $data) {
					
					
					$row = array(
						'ID'			=> $data['id'],
						'ID_SEGMEN'		=> $data['id_segmen'],
						'NIPNAS'		=> $data['nipnas'],
						'NAME_NIPNAS'	=> $data['nama_gc'],
						'WITEL'			=> $data['nama_witel'],
						'SEGMEN'		=> $data['nama_segmen'],
						'TREG'			=> $data['treg'],
						'ID_WITEL'		=> $data['id_witel'],
						'NILAI_POTS'	=> $data['nilai_pots'],
						'PERIODE'		=> $data['periode'],
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
	public function modalpots_rev(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataproduktivitas = $this->query->getData('pots','*',"WHERE id='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataproduktivitas as $data) {
					
					
					$row = array(
						'ID'			=> $data['id'],
						'ID_MAP'		=> $data['id_map'],
						'NILAI_POTS'	=> $data['nilai_pots'],
						'PERIODE'		=> $data['periode'],
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
	
	public function insertoneproduktivitas(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			
			//$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));;
			$nik_am 		= trim(strip_tags(stripslashes($this->input->post('nik_am',true))));;
			$segmen 		= trim(strip_tags(stripslashes($this->input->post('segmen',true))));;
			$dcg 			= trim(strip_tags(stripslashes($this->input->post('dcg',true))));;
			$rss 			= trim(strip_tags(stripslashes($this->input->post('rss',true))));;
			$sumbu_dcg 		= trim(strip_tags(stripslashes($this->input->post('sumbu_dcg',true))));;
			$sumbu_rss		= trim(strip_tags(stripslashes($this->input->post('sumbu_rss',true))));
			$periode		= trim(strip_tags(stripslashes($this->input->post('periode',true))));
			$kuadran		= trim(strip_tags(stripslashes($this->input->post('kuadran',true))));
			
			$rows = $this->query->insertData('productivity_am', "ID,NIK,DCG,RSS,SUMBU_DCG,SUMBU_RSS,PERIODE,KUADRAN,id_segmen", "'','$nik_am','$dcg','$rss','$sumbu_dcg','$sumbu_rss','$periode','$kuadran','$segmen'");
			$id			= $this->db->insert_id();
			$url 		= "Manage Productivity AM";
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
	public function insertpots(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			
			//$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));;
			$id_segmen 		= trim(strip_tags(stripslashes($this->input->post('id_segmen',true))));;
			$nipnas 		= trim(strip_tags(stripslashes($this->input->post('nipnas',true))));;
			$treg 			= trim(strip_tags(stripslashes($this->input->post('treg',true))));;
			$witel 			= trim(strip_tags(stripslashes($this->input->post('witel',true))));;
			$nilai_pots		= trim(strip_tags(stripslashes($this->input->post('nilai_pots',true))));
			$periode		= trim(strip_tags(stripslashes($this->input->post('periode',true))));
			
			
			$rows = $this->query->insertData('pots', "id,id_segmen,nipnas,treg,id_witel,periode,nilai_pots,dateinput", "'','$id_segmen','$nipnas','$treg','$witel','$periode','$nilai_pots','".date('Y-m-d h:i:s')."'");
			$id			= $this->db->insert_id();
			$url 		= "Manage POTS";
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
	public function insertpots_rev(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			
			//$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));;
			$id_map 		= trim(strip_tags(stripslashes($this->input->post('id_map',true))));
			$nilai_pots		= trim(strip_tags(stripslashes($this->input->post('nilai_pots',true))));
			$periode		= trim(strip_tags(stripslashes($this->input->post('periode',true))));
			
			
			$rows = $this->query->insertData('pots', "id,id_map,periode,nilai_pots,dateinput", "'','$id_map','$periode','$nilai_pots','".date('Y-m-d h:i:s')."'");
			$id			= $this->db->insert_id();
			$url 		= "Manage POTS";
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
	public function updateproduktivitas(){
		if(checkingsessionpwt()){
			$userdata		= $this->session->userdata('sesspwt'); 
				
			$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));;
			$nik_am 		= trim(strip_tags(stripslashes($this->input->post('ed_nik_am',true))));;
			$segmen 		= trim(strip_tags(stripslashes($this->input->post('ed_segmen',true))));;
			$dcg 			= trim(strip_tags(stripslashes($this->input->post('ed_dcg',true))));;
			$rss 			= trim(strip_tags(stripslashes($this->input->post('ed_rss',true))));;
			$ed_sumbu_dcg 	= trim(strip_tags(stripslashes($this->input->post('ed_sumbu_dcg',true))));;
			$sumbu_rss		= trim(strip_tags(stripslashes($this->input->post('ed_sumbu_rss',true))));
			$periode		= trim(strip_tags(stripslashes($this->input->post('ed_periode',true))));
			$kuadran		= trim(strip_tags(stripslashes($this->input->post('ed_kuadran',true))));
			$rows 			= $this->query->updateData('productivity_am',"NIK='$nik_am', DCG='$dcg',RSS='$rss',SUMBU_DCG='$ed_sumbu_dcg',SUMBU_RSS='$sumbu_rss',PERIODE='$periode',KUADRAN='$kuadran',id_segmen='$segmen'","WHERE ID='$id'");
			$url 			= "Manage Productivity AM";
			$activity 		= "UPDATE";
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
	public function updatepots(){
		if(checkingsessionpwt()){
			$userdata		= $this->session->userdata('sesspwt'); 
				
			$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));;
			$id_segmen 		= trim(strip_tags(stripslashes($this->input->post('ed_id_segmen',true))));;
			$nipnas 		= trim(strip_tags(stripslashes($this->input->post('ed_nipnas',true))));;
			$treg 			= trim(strip_tags(stripslashes($this->input->post('ed_treg',true))));;
			$witel 			= trim(strip_tags(stripslashes($this->input->post('ed_witel',true))));;
			$nilai_pots		= trim(strip_tags(stripslashes($this->input->post('ed_nilai_pots',true))));
			$periode		= trim(strip_tags(stripslashes($this->input->post('ed_periode',true))));
			$rows 			= $this->query->updateData('pots',"id_segmen='$id_segmen', nipnas='$nipnas',treg='$treg',id_witel='$witel',nilai_pots='$nilai_pots',periode='$periode'","WHERE id='$id'");
			$url 			= "Manage POTS";
			$activity 		= "UPDATE";
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
	public function updatepots_rev(){
		if(checkingsessionpwt()){
			$userdata		= $this->session->userdata('sesspwt'); 
				
			$id 			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$id_map 		= trim(strip_tags(stripslashes($this->input->post('ed_id_map',true))));
			$nilai_pots		= trim(strip_tags(stripslashes($this->input->post('ed_nilai_pots',true))));
			$periode		= trim(strip_tags(stripslashes($this->input->post('ed_periode',true))));
			$rows 			= $this->query->updateData('pots',"id_map='$id_map',nilai_pots='$nilai_pots',periode='$periode'","WHERE id='$id'");
			$url 			= "Manage POTS";
			$activity 		= "UPDATE";
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
	
	public function deletelop(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('lop','id_lop',$cond);
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid		= $userdata['userid'];
			$datelog	= date('Y-m-d H:i:s');
			$ipaddr		= $_SERVER['REMOTE_ADDR'];
			$url 		= "Manage Lop";
			$activity 	= "DELETE";
			$query = "
			SELECT b.treg FROM lo a LEFT JOIN map b ON a.id_map = b.id_map WHERE a.id_lo = $cond
			";
			$data = $this->db->query($query)->result_array();
			$row =array_shift($data);
			$treg = $row['treg'];
			
			if(isset($rows)) {
				$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip,treg", "'','$userid','$datelog','$activity','$url',	'$cond','$ipaddr','$treg'");
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}
	
	
	public function deletemap(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('map','id_map',$cond);
			$userid		= $userdata['userid'];
			$datelog	= date('Y-m-d H:i:s');
			$ipaddr		= $_SERVER['REMOTE_ADDR'];
			$url 		= "Manage Mapping";
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
	
	
	public function getdataEksFac($id){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$dataEksFacBasAll		= $this->query->getData('facility','*',"WHERE type='1'");
			$dataEksFacRomAll		= $this->query->getData('facility','*',"WHERE type='2'");
			$dataEksFacBatAll		= $this->query->getData('facility','*',"WHERE type='3'");
			
			
			$dataEksFacBat			= $this->query->getData('room_facility','*',"a LEFT JOIN facility b on a.id_facility=b.id_facility WHERE id_room='$id' and type='3'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			echo '<h4>Basic Facilities</h4><hr>';
			foreach($dataEksFacBasAll as $dataFb) {
				$getdataEksFacBas	= $this->query->getData('room_facility','*',"a LEFT JOIN facility b on a.id_facility=b.id_facility WHERE id_room='$id' and a.id_facility='".$dataFb['id_facility']."'");
				$dataEksFacBas		= array_shift($getdataEksFacBas);
				if ($dataEksFacBas!='') { $ceked = "checked"; } else { $ceked = ""; }
				echo '
					<div class="col-xs-4">
						<div class="checkbox">
							<input '.$ceked.' value="'.$dataFb['id_facility'].'" id="ed_checkbox'.$dataFb['id_facility'].'" type="checkbox" name="ed_fac[]">
							<label for="ed_checkbox'.$dataFb['id_facility'].'">'.$dataFb['name'].'</label>
						</div>
					</div>
				';
			}
			echo '<div class="clearfix"></div><br>';
			
			echo '<h4>Room Amenities</h4><hr>';
			foreach($dataEksFacRomAll as $dataRa) {
				$getdataEksFacRom	= $this->query->getData('room_facility','*',"a LEFT JOIN facility b on a.id_facility=b.id_facility WHERE id_room='$id' and a.id_facility='".$dataRa['id_facility']."'");
				$dataEksFacRom		= array_shift($getdataEksFacRom);
				if ($dataEksFacRom!='') { $ceked = "checked"; } else { $ceked = ""; }
				echo '
					<div class="col-xs-4">
						<div class="checkbox">
							<input '.$ceked.' value="'.$dataRa['id_facility'].'" id="ed_checkbox'.$dataRa['id_facility'].'" type="checkbox" name="ed_fac[]">
							<label for="ed_checkbox'.$dataRa['id_facility'].'">'.$dataRa['name'].'</label>
						</div>
					</div>
				';
			}
			echo '<div class="clearfix"></div><br>';
			
			echo '<h4>Bathroom Amenities</h4><hr>';
			foreach($dataEksFacBatAll as $dataBa) {
				$getdataEksFacBat	= $this->query->getData('room_facility','*',"a LEFT JOIN facility b on a.id_facility=b.id_facility WHERE id_room='$id' and a.id_facility='".$dataBa['id_facility']."'");
				$dataEksFacBat		= array_shift($getdataEksFacBat);
				if ($dataEksFacBat!='') { $ceked = "checked"; } else { $ceked = ""; }
				echo '
					<div class="col-xs-4">
						<div class="checkbox">
							<input '.$ceked.' value="'.$dataBa['id_facility'].'" id="ed_checkbox'.$dataBa['id_facility'].'" type="checkbox" name="ed_fac[]">
							<label for="ed_checkbox'.$dataBa['id_facility'].'">'.$dataBa['name'].'</label>
						</div>
					</div>
				';
			}
			echo '<div class="clearfix"></div>';
		} else {
			redirect('/panel');
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
	
	// MANAGE LOP
	public function insertlop(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			
			$id_lop 		= trim(strip_tags(stripslashes($this->input->post('id_lop',true))));
			$id_lo			= trim(strip_tags(stripslashes($this->input->post('id_lo',true))));
			$id_sirup		= trim(strip_tags(stripslashes($this->input->post('id_sirup',true))));
			$id_lelang		= trim(strip_tags(stripslashes($this->input->post('id_lelang',true))));
			$nama_paket		= trim(strip_tags(stripslashes($this->input->post('nama_paket',true))));
			$pagu_project	= trim(strip_tags(stripslashes($this->input->post('pagu_project',true))));
			$nilai_win		= trim(strip_tags(stripslashes($this->input->post('nilai_win',true))));
			$metode			= trim(strip_tags(stripslashes($this->input->post('metode',true))));
			$getwaktu		= trim(strip_tags(stripslashes($this->input->post('waktu',true))));
			$waktu			= $getwaktu."-01 00:00:00";
			$gettanggal		= trim(strip_tags(stripslashes($this->input->post('tanggal',true))));
			$tanggal		= $gettanggal." 00:00:00";
			$kategori		= trim(strip_tags(stripslashes($this->input->post('kategori',true))));
			$status			= trim(strip_tags(stripslashes($this->input->post('status',true))));
			$kode_raisa		= trim(strip_tags(stripslashes($this->input->post('kode_raisa',true))));
			$portofolio		= trim(strip_tags(stripslashes($this->input->post('portofolio',true))));
			$id_subs		= trim(strip_tags(stripslashes($this->input->post('id_subs',true))));
			$treg			= '';
			$keterangan		= trim(strip_tags(stripslashes($this->input->post('keterangan',true))));
			$last_update 	= date('Y-m-d h:i:s');
			$nomor_kontrak	= trim(strip_tags(stripslashes($this->input->post('nomor_kontrak',true))));
			$sustain_dari 	= trim(strip_tags(stripslashes($this->input->post('sustain_dari',true))));
			$nama_pm	 	= trim(strip_tags(stripslashes($this->input->post('nama_pm',true))));
			$telephone	 	= trim(strip_tags(stripslashes($this->input->post('telephone',true))));
			
			//file_renaming
			$pdfhilang 		= str_replace('.pdf','',time().$_FILES['pict']['name']);
			$spasi_filename = str_replace(' ','_',time().$pdfhilang);
			$titikhilang	= str_replace('.','_',time().$spasi_filename);
			$komahilang		= str_replace(',','_',time().$titikhilang);
			$fileName		= $komahilang.".pdf";
			
			$gettanggal_kb		= trim(strip_tags(stripslashes($this->input->post('tanggal_kb',true))));
			$tanggal_kb		= $gettanggal_kb." 00:00:00";
			
			$config['upload_path'] = './files/kontrak/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			// $config['allowed_types'] = 'pdf|jpg|png|jpeg';
			$config['allowed_types'] = 'pdf';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') ){
				$this->upload->display_errors();
			}
				 
			$media = $this->upload->data('pict');
			
			if($waktu == NULL or $waktu == '0000-00-00 00:00:00' or $waktu == ''){
				$waktus = 'NULL';
			}else{
				$waktus = $waktu;
			}
			
			if($tanggal == NULL or $tanggal == '0000-00-00 00:00:00' or $tanggal == ''){
				$tanggals = 'NULL';
			}else{
				$tanggals = $tanggal;
			}
			
			if($tanggal_kb == NULL or $tanggal_kb == '0000-00-00 00:00:00' or $tanggal_kb ==''){
				$tanggals_kb = 'NULL';
			}else{
				$tanggals_kb = $tanggal_kb;
			}
			$cekinglogo		= $_FILES['pict']['name'];
			if ($cekinglogo!='') {
				$fileName_ex = $fileName;
			}else{
				$fileName_ex ="";
			}
			if ($kode_raisa=='R2' and $status=='WIN' and $nilai_win>1000 or $kode_raisa=='R2' and $status=='NEW-GTMA' and $nilai_win>1000) {
				$rows = $this->query->insertData('lop', "id_lop,id_lo,id_sirup,id_lelang,nama_pkt,pagu_proj,nilai_win,metode,waktu,tanggal,kategori,status,kode_raisa,portfolio,subs,treg,ket,last_update,nomor_kontrak,file_kontrak,tanggal_kb", "'','$id_lo','$id_sirup','$id_lelang','$nama_paket','$pagu_project','$nilai_win','$metode','$waktus','$tanggals','$kategori','$status','$kode_raisa','$portfolio','$id_subs','$treg','$keterangan','$last_update','$nomor_kontrak','$fileName_ex','$tanggals_kb'");
				
				
				
				$userid		= $userdata['userid'];
				$datelog	= date('Y-m-d H:i:s');
				$ipaddr		= $_SERVER['REMOTE_ADDR'];
				$url 		= "Manage Lop";
				$activity 	= "INSERT";
		
				if($rows) {
					$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$id_lop','$ipaddr'");
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}
	// MANAGE LOP
	public function insertmap(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			
		
			$id_map			= trim(strip_tags(stripslashes($this->input->post('id_map',true))));
			$nipnas			= trim(strip_tags(stripslashes($this->input->post('nipnas',true))));
			$nik_am			= trim(strip_tags(stripslashes($this->input->post('nik_am',true))));
			$segmen			= trim(strip_tags(stripslashes($this->input->post('segmen',true))));
			$witel			= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$treg			= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$target_rev		= trim(strip_tags(stripslashes($this->input->post('target_rev',true))));
			$bulan_mapping1	= trim(strip_tags(stripslashes($this->input->post('bulan_mapping',true))));
			
			$map = $bulan_mapping1."-01";
			$date=date_create($map);
			$bulan_mapping = date_format($date,"M-y");
			
			$rows = $this->query->insertData('map', "id_map,nipnas,nik_am,segmen,witel,treg,bulan_mapping,target_rev", "'','$nipnas','$nik_am','$segmen','$witel','$treg','$bulan_mapping','$target_rev'");
			
			$userid		= $userdata['userid'];
			$datelog	= date('Y-m-d H:i:s');
			$ipaddr		= $_SERVER['REMOTE_ADDR'];
			$url 		= "Manage Mapping";
			$activity 	= "INSERT";
			if($rows) {
				$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$id_map','$ipaddr'");
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
	public function updatelop(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$id_lop 		= trim(strip_tags(stripslashes($this->input->post('ed_id_lop',true))));
			$id_lo			= trim(strip_tags(stripslashes($this->input->post('ed_id_lo',true))));
			$id_sirup		= trim(strip_tags(stripslashes($this->input->post('ed_id_sirup',true))));
			$id_lelang		= trim(strip_tags(stripslashes($this->input->post('ed_id_lelang',true))));
			$nama_paket		= trim(strip_tags(stripslashes($this->input->post('ed_nama_paket',true))));
			$pagu_project	= trim(strip_tags(stripslashes($this->input->post('ed_pagu_project',true))));
			$nilai_win		= trim(strip_tags(stripslashes($this->input->post('ed_nilai_win',true))));
			$metode			= trim(strip_tags(stripslashes($this->input->post('ed_metode',true))));
			$getwaktu		= trim(strip_tags(stripslashes($this->input->post('ed_waktu',true))));
			$waktus			= $getwaktu."-01 00:00:00";
			$gettanggal		= trim(strip_tags(stripslashes($this->input->post('ed_tanggal',true))));
			$tanggals		= $gettanggal." 00:00:00";
			$kategori		= trim(strip_tags(stripslashes($this->input->post('ed_kategori',true))));
			$status			= trim(strip_tags(stripslashes($this->input->post('ed_status',true))));
			$kode_raisadef	= trim(strip_tags(stripslashes($this->input->post('ed_kode_raisadef',true))));
			$kode_raisa		= trim(strip_tags(stripslashes($this->input->post('ed_kode_raisa',true))));
			$portofolio		= trim(strip_tags(stripslashes($this->input->post('ed_portofolio',true))));
			$id_subs		= trim(strip_tags(stripslashes($this->input->post('ed_id_subs',true))));
			$treg			= trim(strip_tags(stripslashes($this->input->post('ed_treg',true))));
			$keterangan		= trim(strip_tags(stripslashes($this->input->post('ed_keterangan',true))));
			$last_update 	= date('Y-m-d h:i:s');
			$nomor_kontrak 	= trim(strip_tags(stripslashes($this->input->post('ed_nomor_kontrak',true))));
			$gettanggal_kb		= trim(strip_tags(stripslashes($this->input->post('ed_tanggal_kb',true))));
			$tanggal_kbs		= $gettanggal_kb." 00:00:00";
			
			if($waktus == NULL or $waktus == '0000-00-00 00:00:00' or $waktus == ''){
				$waktu = 'NULL';
			}else{
				$waktu = $waktus;
			}
			
			if($tanggals == NULL or $tanggals == '0000-00-00 00:00:00' or $tanggals == ''){
				$tanggal = 'NULL';
			}else{
				$tanggal = $tanggals;
			}
			
			if($tanggal_kbs == NULL or $tanggal_kbs == '0000-00-00 00:00:00' or $tanggal_kbs ==''){
				$tanggal_kb = 'NULL';
			}else{
				$tanggal_kb = $tanggal_kbs;
			}
			
			
			$cekinglogo		= $_FILES['ed_pict']['name'];
			if ($cekinglogo!='') {
				//delete eksisting
				$coba = $this->query->getData('lop','file_kontrak','WHERE id_lop='.$id_lop.'');
				foreach ($coba as $dataex) {
					$dataexis = 'files/kontrak/'.$dataex['file_kontrak'];
				}
				unlink($dataexis);
				$spasi_fileName = str_replace(' ','_',time().$_FILES['ed_pict']['name']);
				$fileName = str_replace(',','_',time().$spasi_fileName);
				$config['upload_path'] = './files/kontrak/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'pdf';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('ed_pict') ){
					$this->upload->display_errors();					
				}
					 
				$media = $this->upload->data('ed_pict');
				
				$rows = $this->query->updateData('lop',"id_lo='$id_lo', id_sirup='$id_sirup',id_lelang='$id_lelang', nama_pkt='$nama_paket', pagu_proj='$pagu_project', nilai_win='$nilai_win', metode='$metode', waktu='$waktu', tanggal='$tanggal', kategori='$kategori', status='$status', kode_raisa='$kode_raisa', portfolio='$portofolio', subs='$id_subs', treg='$treg', ket='$keterangan', last_update='$last_update', nomor_kontrak='$nomor_kontrak',file_kontrak='$fileName',tanggal_kb='$tanggal_kb'","WHERE id_lop='$id_lop'");
			
				$userid		= $userdata['userid'];
				$datelog	= date('Y-m-d H:i:s');
				$ipaddr		= $_SERVER['REMOTE_ADDR'];
				$url 		= "Manage Lop";
				if ($kode_raisadef=='R2') { $activity 	= "UPDATE R2"; } else { $activity 	= "UPDATE"; }
				
				if($rows) {
					$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$id_lop','$ipaddr'");
					print json_encode(array('success'=>true,'total'=>1));
					// echo "id_sirup='$id_sirup',id_lelang='$id_lelang', nama_pkt='$nama_paket', pagu_proj='$pagu_project', nilai_win='$nilai_win', metode='$metode', waktu='$waktu', tanggal='$tanggal', kategori='$kategori', status='$status', kode_raisa='$kode_raisa', portfolio='$portofolio', subs='$id_subs', treg='$treg', ket='$keterangan', last_update='$last_update', nomor_kontrak='$nomor_kontrak',file_kontrak='$fileName',tanggal_kb='$tanggal_kb' WHERE id_lop='$id_lop";
				} else {
					echo "";
				}
			}else{
				// echo "id_sirup='$id_sirup',id_lelang='$id_lelang', nama_pkt='$nama_paket', pagu_proj='$pagu_project', nilai_win='$nilai_win', metode='$metode', waktu='$waktu', tanggal='$tanggal', kategori='$kategori', status='$status', kode_raisa='$kode_raisa', portfolio='$portofolio', subs='$id_subs', treg='$treg', ket='$keterangan', last_update='$last_update', nomor_kontrak='$nomor_kontrak',tanggal_kb='$tanggal_kb' WHERE id_lop='$id_lop";
				$rows = $this->query->updateData('lop',"id_lo='$id_lo', id_sirup='$id_sirup',id_lelang='$id_lelang', nama_pkt='$nama_paket', pagu_proj='$pagu_project', nilai_win='$nilai_win', metode='$metode', waktu='$waktu', tanggal='$tanggal', kategori='$kategori', status='$status', kode_raisa='$kode_raisa', portfolio='$portofolio', subs='$id_subs', treg='$treg', ket='$keterangan', last_update='$last_update', nomor_kontrak='$nomor_kontrak',tanggal_kb='$tanggal_kb'","WHERE id_lop='$id_lop'");
			
				$userid		= $userdata['userid'];
				$datelog	= date('Y-m-d H:i:s');
				$ipaddr		= $_SERVER['REMOTE_ADDR'];
				$url 		= "Manage Lop";
				if ($kode_raisadef=='R2') { $activity 	= "UPDATE R2"; } else { $activity 	= "UPDATE"; }
				if($rows) {
					$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$id_lop','$ipaddr'");
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
			}
		} else {
			redirect('/panel');
		}
	}
	
	
	public function updatemap(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$id_map			= trim(strip_tags(stripslashes($this->input->post('ed_id_map',true))));
			$nipnas			= trim(strip_tags(stripslashes($this->input->post('ed_nipnas',true))));
			$nik_am			= trim(strip_tags(stripslashes($this->input->post('ed_nik_am',true))));
			$segmen			= trim(strip_tags(stripslashes($this->input->post('ed_segmen',true))));
			$witel			= trim(strip_tags(stripslashes($this->input->post('ed_witel',true))));
			$treg			= trim(strip_tags(stripslashes($this->input->post('ed_treg',true))));
			$target_rev		= trim(strip_tags(stripslashes($this->input->post('ed_target_rev',true))));
			$bulan_mapping1	= trim(strip_tags(stripslashes($this->input->post('ed_bulan_mapping',true))));
			
			$map = $bulan_mapping1."-01";
			$date=date_create($map);
			$bulan_mapping = date_format($date,"M-y");
			
			
			$rows = $this->query->updateData('map',"nipnas='$nipnas', nik_am='$nik_am',segmen='$segmen', witel='$witel', treg='$treg', bulan_mapping='$bulan_mapping', target_rev='$target_rev'","WHERE id_map='$id_map'");
				$userid		= $userdata['userid'];
				$datelog	= date('Y-m-d H:i:s');
				$ipaddr		= $_SERVER['REMOTE_ADDR'];
				$url 		= "Manage Mapping";
				$activity 	= "UPDATE";

				if($rows) {
					$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$id_map','$ipaddr'");
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
			$fileName 	= str_replace(' ','_',time().$_FILES['pict']['name']);
			$item		= $_POST['item'];
			
			$config['upload_path'] = './images/promotion/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media = $this->upload->data('pict');
			
			$rows = $this->query->insertData('promo', "kode_promo,name,sub,price,pict,date_start,date_end,status", "'$kode','$name','$sub','$price','$fileName','$start','$end','$status'");
			
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
	
	public function dataamcachdetail_export(){
		if(checkingsessionpwt()){
			$type = $_GET['type'];
			$divisi = $_GET['divisi'];
			$segmen = $_GET['segmen'];
			$treg = $_GET['treg'];
			$witel = $_GET['witel'];
			$am = $_GET['am'];
			$tahun = $_GET['periode'];
			
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			
			// date_default_timezone_set("Asia/Bangkok");
			if ($divisi=='ALL DIVISI') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL SEGMEN') { $wheresegmen	= ""; } else { $wheresegmen = "and segmen='".$segmen."'";}
			if ($treg=='ALL TREG') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL Witel') { $wherewitel	= ""; } else { $wherewitel = "and witel='".$witel."'"; }
			if ($am=='ALL AM') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			if ($tahun=='') 	{ $wheretahun	= "and tahun='".date('Y')."'"; } else { $wheretahun = "and tahun='".$tahun."'"; }
			if ($type=='newgtma') {
				$condgtma	= "and status='NEW-GTMA'";
				$gtma		= 'NEW-GTMA';
			} else {
				$condgtma	= '';
				$gtma		= '';
			}
			$row="";
			$q			= "
						select
						treg, segmen, witel, am, nama_am, (select posisi from am where nik_am=am) as posisi, target, 
						round((((N_ACH+pots)/target)*100),2) as persen, (target-(N_ACH+pots)) as sisatarget, 
						N_PROSPECT, N_ONPROGRESS, N_SUBMISSION, N_WIN, N_LOSE, N_TIDAKIKUT, N_POTENSIGTMA, N_WINGTMA, N_BOOKED, N_BILLCOMP, N_ACH,
						pots,
						((N_ACH+pots)-target) as achyesno, 
						(case when N_ACH>0 and (N_ACH+pots)-target > 0 then 'ACHIEVED' else 'NOT ACHIEVED' end) as status
						from (
							select
								target, N_ACH, N_PROSPECT, N_ONPROGRESS, N_SUBMISSION, N_WIN, N_LOSE, N_TIDAKIKUT, N_POTENSIGTMA, N_WINGTMA, N_BOOKED, N_BILLCOMP, treg, segmen, witel, am, nama_am, (case when pots is not null then pots else 0 end) as pots 
							from (
								select
								am, (select nama_am from am where nik_am=res.am) as nama_am, sum(target) as target, 
								sum(N_ACH) as N_ACH,
								sum(N_PROSPECT) as N_PROSPECT,
								sum(N_ONPROGRESS) as N_ONPROGRESS,
								sum(N_SUBMISSION) as N_SUBMISSION,
								sum(N_WIN) as N_WIN,
								sum(N_LOSE) as N_LOSE,
								sum(N_TIDAKIKUT) as N_TIDAKIKUT,
								sum(N_POTENSIGTMA) as N_POTENSIGTMA,
								sum(N_WINGTMA) as N_WINGTMA,
								sum(N_BOOKED) as N_BOOKED,
								sum(N_BILLCOMP) as N_BILLCOMP,
								(select sum(nilai_pots) from pots xa left join map xb on xa.id_map=xb.id_map where xb.nik_am=res.am $wheredivisi $wheresegmen $wherewitel $wheretreg $wheretahun) as pots,
								group_concat(distinct treg) as treg,
								group_concat(distinct segmen) as segmen,
								group_concat(distinct witel) as witel
								from (
									select nik_am as am, sum(DISTINCT target_rev) as target, 
									sum(case when PPN='1' then (100/110)*N_WIN else N_WIN end) as N_ACH,
									sum(N_PROSPECT) as N_PROSPECT,
									sum(N_ONPROGRESS) as N_ONPROGRESS,
									sum(N_SUBMISSION) as N_SUBMISSION,
									sum(N_WIN) as N_WIN,
									sum(N_LOSE) as N_LOSE,
									sum(N_TIDAKIKUT) as N_TIDAKIKUT,
									sum(N_POTENSIGTMA) as N_POTENSIGTMA,
									sum(N_WINGTMA) as N_WINGTMA,
									sum(N_BOOKED) as N_BOOKED,
									sum(N_BILLCOMP) as N_BILLCOMP,
									group_concat(distinct treg) as treg, group_concat(distinct segmen) as segmen, group_concat(distinct witel) as witel,
									tahun
									from (
										select filter.* from (
											select nik_am, nama_am, 
											case when kode_raisa='R0' then pagu_proj else 0 end as N_PROSPECT, 
											case when kode_raisa='R1+' then pagu_proj else 0 end as N_ONPROGRESS, 
											case when kode_raisa='R1++' then pagu_proj else 0 end as N_SUBMISSION,
											case when kode_raisa='R2' and status in ('WIN') then nilai_win else 0 end as N_WIN, 
											case when kode_raisa='R2' and status not in ('WIN') then pagu_proj else 0 end as N_LOSE,
											case when kode_raisa='R2' and status='TIDAK IKUT' then pagu_proj else 0 end as N_TIDAKIKUT,
											case when kode_raisa='R1+' and status='NEW-GTMA' then pagu_proj else 0 end as N_POTENSIGTMA,
											case when kode_raisa='R1++' and status='NEW-GTMA' then nilai_win else 0 end as N_WINGTMA,
											case when kode_raisa='R2' and status='NEW-GTMA' then nilai_win else 0 end as N_BOOKED,
											case when kode_raisa='R3' then pagu_proj else 0 end as N_BILLCOMP,
											PPN, id_divisi, id_segmen, id_witel, (select nama_witel from witel where id_witel=master.id_witel) as witel, treg, (select nama_segmen from segmen where id_segmen=master.id_segmen) as segmen, target_rev,nipnas,tahun from (
												select d.id_lop, d.kode_raisa, d.status, d.PPN, a.nik_am, b.id_map, nama_am, id_divisi, id_segmen, b.treg, id_witel, nilai_win, pagu_proj, target_rev,b.nipnas,b.tahun
												from am a
												left join map b
												on a.nik_am=b.nik_am
												left join lo c 
												on b.id_map=c.id_map
												left join lop d
												on c.id_lo=d.id_lo
												where a.raisa=1
											) as master
										) as filter
									) as calc
									where nik_am!='' $wheredivisi $wheresegmen $wheretreg $wherewitel $wheream $wheretahun $uam 
									group by nik_am,nipnas
								) as res
								group by am
							) as pts
						) as final
						order by treg,nama_am asc
						";
			
			$dataCat			= 	$this->query->getDatabyQ($q);
			$no=0;
			
			foreach($dataCat as $data) { 
				$no++;
				$id	= $data['am'];
				
				if ($data['status']=='ACHIEVED') { 
					$color='success';
					$returntstat	= '
						<div class="checkbox checkbox-info checkbox-circle">
							<input id="selectAll" type="checkbox" readonly checked>
							<label for="selectAll"><b>ACHIEVED</b></label>
						</div>
					';
				} else { 
					$color='danger'; 
					$returntstat	= '
						<div class="progress">
							<div class="progress-bar progress-bar-success progress-bar-striped progress-animated active" role="progressbar" aria-valuenow="'.number_format($data['persen'],2).'" aria-valuemin="0" aria-valuemax="100">
								<span  class="popOver" data-toggle="tooltip" data-placement="top" title="'.number_format($data['persen'],2).'%"> </span>  
							</div>
						</div>
						<script>
						$(document).ready(function () {
							$(".progress-animated").each(function () {
								each_bar_width = $(this).attr("aria-valuenow");
								$(this).width(each_bar_width + "%");
							});
						});
						</script>
					';
				}
	
				@$row .= 
					"<tr>
					<td>".$data['treg']."</td>
					<td>".str_replace(',',' / ',$data['segmen'])."</td>
					<td>".str_replace(',',' / ',$data['witel'])."</td>
					<td>".$data['am']."</td>
					<td>"."<a class='btndetailamgc' data-toggle='modal' data-target='#detailamgc' data-id='$id' style='color: #1e1e1e; cursor:pointer;'><b>".$data['nama_am']."</b></a>"."</td>
					<td>".$data['posisi']."</td>
					<td>".$data['persen'].""."</td>
					<td>"."%"."</td>
					<td>".$this->formula->rupiahM($data['target'])."</td>
					<td>".$this->formula->rupiahM($data['sisatarget'])."</td>
					<td>".$this->formula->rupiahM($data['N_PROSPECT'])."</td>
					<td>".$this->formula->rupiahM($data['N_ONPROGRESS'])."</td>
					<td>".$this->formula->rupiahM($data['N_SUBMISSION'])."</td>
					<td>".$this->formula->rupiahM($data['N_TIDAKIKUT'])."</td>
					<td>".$this->formula->rupiahM($data['N_LOSE'])."</td>
					<td>"."<b>".$this->formula->rupiahM($data['N_WIN'])."</b>"."</td>
					<td>"."<b>".$this->formula->rupiahM($data['N_ACH'])."</b>"."</td>
					<td>".$returntstat."</td>
					</tr>";
			}
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=Achievement ".$gtma." ".$segmen." (".$treg.").xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '
			<table border="1px" style="width: 100%;">
				<thead class="bg-gray-dark text-white">
					<tr>
						<th class="text-center" style="">TREG</th>
						<th class="text-center" style="">SEGMEN</th>
						<th class="text-center" style="">WITEL</th>
						<th class="text-center" style="">NIK</th>
						<th class="text-center" style="">NAMA AM</th>
						<th class="text-center" style="">JABATAN</th>
						<th class="text-center" style="">ACH T%</th>
						<th class="text-center" style=""></th>
						<th class="text-center">VALUASI PROJECTS (Rp M)</th>
						<th class="text-center">TARGET</th>
						<th class="text-center">SISA TARGET</th>
						<th class="text-center">PROSPECT</th>
						<th class="text-center">ON PROGRESS</th>
						<th class="text-center">SUBMISSION</th>
						<th class="text-center">TIDAK IKUT</th>
						<th class="text-center">LOSE</th>
						<th class="text-center">WIN</th>
						<th class="text-center">ACH</th>
						<th class="text-center">STATUS</th>
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
	public function datasegcachdetail($type,$divisi,$segmen,$treg,$witel,$am,$tahun){
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			
			if ($divisi=='ALL%20DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL%20SEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL%20TREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL%20Witel' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL%20AM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			if ($tahun=='') 	{ $wheretahun	= ""; } else { $wheretahun = "and tahun='".$tahun."'"; }
			
			if ($type=='newgtma') {
				$condgtma	= "and status='NEW-GTMA'";
			}else if ($type=='sustain') {
				$condgtma	= "and kategori='Sustain'";
			}else if ($type=='scalling') {
				$condgtma	= "and kategori='Smart'";
			} else {
				$condgtma	= '';
			}
			
			$dataCat			= 	$this->query->getData(
									"
									(
									select nik_am as am, (select nama_am from am where nik_am=calc.nik_am) as nama_am, nama_gc as kdli,nipnas,id_map, sum(DISTINCT target_rev) as target, sum(
										case When id_sr = '15'
										then nilai_win else 0 End
									) as nilai,id_divisi,divisi,id_segmen,segmen,treg,id_witel,witel,tahun  from (
									select filter.* from (
									select * from (
										select nik_am, nama_am, raisa, nilai_win, id_divisi, (select nama_divisi from divisi where id_divisi=basic.id_divisi) as divisi, id_segmen, (select nama_segmen from segmen where id_segmen=basic.id_segmen) as segmen, id_witel, (select nama_witel from witel where id_witel=basic.id_witel) as witel, treg, target_rev,nama_gc,nipnas,id_map,kode_raisa,id_sr,status,kategori,sustain_dari,tahun from (
										select d.id_lop, a.nik_am, a.raisa, b.id_map, nama_am, id_divisi, id_segmen, b.treg, id_witel, 
										case when d.PPN='1' then (100/110)*d.nilai_win else d.nilai_win end as nilai_win,
										target_rev,b.nipnas,e.nama_gc,d.kode_raisa,d.id_sr,d.status,d.kategori,d.sustain_dari,b.tahun from am a left join map b on a.nik_am=b.nik_am left join lo c on b.id_map=c.id_map left join lop d on c.id_lo=d.id_lo left join gc e on b.nipnas = e.nipnas
										) as basic
									) as master
									where nik_am!='' and raisa='1' $condgtma $uam
									) as filter
									) as calc
									where nik_am!='' $wheredivisi $wheresegmen $wheretreg $wherewitel $wheream $wheretahun
									group by nik_am,nama_gc,nipnas,id_map
									) as final
									",
									"
									final.*, (nilai-target) as achyesno, round(((nilai/target)*100),2) as persen,
									case when nilai-target > 0 then 'ACHIEVED' else 'NOT ACHIEVED' end as status
									",
									"ORDER BY kdli ASC"
									);
			$no=0;
			
			foreach($dataCat as $data) { 
				$no++;
				$id	= $data['am'];
				
				if ($data['status']=='ACHIEVED') { 
					$color='success';
					$returntstat	= '
						<div class="checkbox checkbox-info checkbox-circle">
							<input id="selectAll" type="checkbox" readonly checked>
							<label for="selectAll"><b>ACHIEVED</b></label>
						</div>
					';
				} else { 
					$color='danger'; 
					$returntstat	= '
						<div class="progress">
							<div class="progress-bar progress-bar-success progress-bar-striped progress-animated active" role="progressbar" aria-valuenow="'.number_format($data['persen'],2).'" aria-valuemin="0" aria-valuemax="100">
								<span  class="popOver" data-toggle="tooltip" data-placement="top" title="'.number_format($data['persen'],2).'%"> </span>  
							</div>
						</div>
						<script>
						$(document).ready(function () {
							$(".progress-animated").each(function () {
								each_bar_width = $(this).attr("aria-valuenow");
								$(this).width(each_bar_width + "%");
							});
						});
						</script>
					';
				}
	
				$row = array(
					$data['segmen'],
					$data['treg'],
					$data['witel'],
					"<a class='btndetailprojam' data-toggle='modal' data-target='#datadetailachsegsub' data-id='$id' style='color: #1e1e1e; cursor:pointer;'><b>".$data['kdli']."</b></a>",
					$data['am'],
					$data['nama_am'],
					$this->formula->rupiah2($data['target']),
					$this->formula->rupiah2($data['nilai']),
					$data['persen']."",
					"%",
					$returntstat
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$json['data'] = '';
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
	public function dataamcachdetail($type,$divisi,$segmen,$treg,$witel,$am,$tahun){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			if ($divisi=='ALL%20DIVISI') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL%20SEGMEN') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL%20TREG') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".str_replace('%20',' ',$treg)."'"; }
			if ($witel=='ALL%20Witel') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL%20AM') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			if ($tahun=='') 	{ $wheretahun	= ""; } else { $wheretahun = "and tahun='".$tahun."'"; }
			
			//GET UAM
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			
			$q			= "
						select
						treg, segmen, witel, am, nama_am, (select posisi from am where nik_am=am) as posisi, target, 
						round((((N_ACH+pots)/target)*100),2) as persen, (target-(N_ACH+pots)) as sisatarget, 
						N_PROSPECT, N_ONPROGRESS, N_SUBMISSION, N_WIN, N_LOSE, N_TIDAKIKUT, N_POTENSIGTMA, N_WINGTMA, N_BOOKED, N_BILLCOMP, N_ACH,
						pots,
						((N_ACH+pots)-target) as achyesno, 
						(case when N_ACH>0 and (N_ACH+pots)-target > 0 then 'ACHIEVED' else 'NOT ACHIEVED' end) as status
						from (
							select
								target, N_ACH, N_PROSPECT, N_ONPROGRESS, N_SUBMISSION, N_WIN, N_LOSE, N_TIDAKIKUT, N_POTENSIGTMA, N_WINGTMA, N_BOOKED, N_BILLCOMP, treg, segmen, witel, am, nama_am, (case when pots is not null then pots else 0 end) as pots 
							from (
								select
								am, (select nama_am from am where nik_am=res.am) as nama_am, sum(target) as target, 
								sum(N_ACH) as N_ACH,
								sum(N_PROSPECT) as N_PROSPECT,
								sum(N_ONPROGRESS) as N_ONPROGRESS,
								sum(N_SUBMISSION) as N_SUBMISSION,
								sum(N_WIN) as N_WIN,
								sum(N_LOSE) as N_LOSE,
								sum(N_TIDAKIKUT) as N_TIDAKIKUT,
								sum(N_POTENSIGTMA) as N_POTENSIGTMA,
								sum(N_WINGTMA) as N_WINGTMA,
								sum(N_BOOKED) as N_BOOKED,
								sum(N_BILLCOMP) as N_BILLCOMP,
								(select sum(nilai_pots) from pots xa left join map xb on xa.id_map=xb.id_map where xb.nik_am=res.am $wheredivisi $wheresegmen $wherewitel $wheretreg $wheretahun) as pots,
								group_concat(distinct treg) as treg,
								group_concat(distinct segmen) as segmen,
								group_concat(distinct witel) as witel
								from (
									select nik_am as am, sum(DISTINCT target_rev) as target, 
									sum(case when PPN='1' then (100/110)*N_WIN else N_WIN end) as N_ACH,
									sum(N_PROSPECT) as N_PROSPECT,
									sum(N_ONPROGRESS) as N_ONPROGRESS,
									sum(N_SUBMISSION) as N_SUBMISSION,
									sum(N_WIN) as N_WIN,
									sum(N_LOSE) as N_LOSE,
									sum(N_TIDAKIKUT) as N_TIDAKIKUT,
									sum(N_POTENSIGTMA) as N_POTENSIGTMA,
									sum(N_WINGTMA) as N_WINGTMA,
									sum(N_BOOKED) as N_BOOKED,
									sum(N_BILLCOMP) as N_BILLCOMP,
									group_concat(distinct treg) as treg, group_concat(distinct segmen) as segmen, group_concat(distinct witel) as witel,
									tahun
									from (
										select filter.* from (
											select nik_am, nama_am, 
											case when kode_raisa='R0' then pagu_proj else 0 end as N_PROSPECT, 
											case when kode_raisa='R1+' then pagu_proj else 0 end as N_ONPROGRESS, 
											case when kode_raisa='R1++' then pagu_proj else 0 end as N_SUBMISSION,
											case when kode_raisa='R2' and status in ('WIN') then nilai_win else 0 end as N_WIN, 
											case when kode_raisa='R2' and status not in ('WIN') then pagu_proj else 0 end as N_LOSE,
											case when kode_raisa='R2' and status='TIDAK IKUT' then pagu_proj else 0 end as N_TIDAKIKUT,
											case when kode_raisa='R1+' and status='NEW-GTMA' then pagu_proj else 0 end as N_POTENSIGTMA,
											case when kode_raisa='R1++' and status='NEW-GTMA' then nilai_win else 0 end as N_WINGTMA,
											case when kode_raisa='R2' and status='NEW-GTMA' then nilai_win else 0 end as N_BOOKED,
											case when kode_raisa='R3' then pagu_proj else 0 end as N_BILLCOMP,
											PPN, id_divisi, id_segmen, id_witel, (select nama_witel from witel where id_witel=master.id_witel) as witel, treg, (select nama_segmen from segmen where id_segmen=master.id_segmen) as segmen, target_rev,nipnas,tahun from (
												select d.id_lop, d.kode_raisa, d.status, d.PPN, a.nik_am, b.id_map, nama_am, id_divisi, id_segmen, b.treg, id_witel, nilai_win, pagu_proj, target_rev,b.nipnas,b.tahun
												from am a
												left join map b
												on a.nik_am=b.nik_am
												left join lo c 
												on b.id_map=c.id_map
												left join lop d
												on c.id_lo=d.id_lo
												where a.raisa=1
											) as master
										) as filter
									) as calc
									where nik_am!='' $wheredivisi $wheresegmen $wheretreg $wherewitel $wheream $wheretahun $uam 
									group by nik_am,nipnas
								) as res
								group by am
							) as pts
						) as final
						order by treg,nama_am asc
						";
			
			$dataCat			= 	$this->query->getDatabyQ($q);
			$no=0;
			
			foreach($dataCat as $data) { 
				$no++;
				$id	= $data['am'];
				
				// if ($data['status']=='ACHIEVED') { $color='success'; } else { $color='danger'; }
				if ($data['status']=='ACHIEVED') { 
					$color='success';
					$returntstat	= '
						<div class="checkbox checkbox-info checkbox-circle">
							<input id="selectAll" type="checkbox" readonly checked>
							<label for="selectAll"><b>ACHIEVED</b></label>
						</div>
					';
				} else { 
					$color='danger'; 
					$returntstat	= '
						<div class="progress">
							<div class="progress-bar progress-bar-success progress-bar-striped progress-animated active" role="progressbar" aria-valuenow="'.number_format($data['persen'],2).'" aria-valuemin="0" aria-valuemax="100">
								<span  class="popOver" data-toggle="tooltip" data-placement="top" title="'.number_format($data['persen'],2).'%"> </span>  
							</div>
						</div>
						<script>
						$(document).ready(function () {
							$(".progress-animated").each(function () {
								each_bar_width = $(this).attr("aria-valuenow");
								$(this).width(each_bar_width + "%");
							});
						});
						</script>
					';
				}
	
				$row = array(
					$data['treg'],
					str_replace(',',' / ',$data['segmen']),
					str_replace(',',' / ',$data['witel']),
					$data['am'],
					"<a class='btndetailamgc' data-toggle='modal' data-target='#detailamgc' data-id='$id' style='color: #3283ab; cursor:pointer;'><b>".$data['nama_am']."</b></a>",
					$data['posisi'],
					$data['persen']."",
					"%",
					$this->formula->rupiahM($data['target']),
					$this->formula->rupiahM($data['sisatarget']),
					$this->formula->rupiahM($data['N_PROSPECT']),
					$this->formula->rupiahM($data['N_ONPROGRESS']),
					$this->formula->rupiahM($data['N_SUBMISSION']),
					$this->formula->rupiahM($data['N_TIDAKIKUT']),
					$this->formula->rupiahM($data['N_LOSE']),
					"<b>".$this->formula->rupiahM($data['N_WIN'])."</b>",
					"<b>".$this->formula->rupiahM($data['N_ACH'])."</b>",
					"<b>".$this->formula->rupiahM($data['pots'])."</b>",
					$returntstat
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$json['data'] = '';
			}
			echo json_encode($json);
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
	
	public function dataamgcdetail($type,$segmen,$treg,$witel,$am,$tahun){
		if(checkingsessionpwt()){
			// error_reporting(0);
			
			if ($segmen=='ALL%20SEGMEN') { $wheresegmen	= ""; } else { $wheresegmen = "and segmen='".$segmen."'";}
			if ($treg=='ALL%20TREG') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL%20Witel') { $wherewitel	= ""; } else { $wherewitel = "and witel='".$witel."'"; }
			if ($am=='ALL%20AM') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			if ($tahun=='') 	{ $wheretahun	= "and tahun='".date('Y')."'"; } else { $wheretahun = "and tahun='".$tahun."'"; }
			
			$dataCat			= 	$this->query->getData(
									"
									(
									select b.nipnas, d.id_lop, d.kode_raisa, d.id_sr, d.status, a.nik_am, b.id_map, nama_am, id_segmen as segmen, b.treg, b.tahun, id_witel as witel, case when PPN='1' then (100/110)*nilai_win else nilai_win end as nilai_win, target_rev from am a
									left join map b
									on a.nik_am=b.nik_am
									left join lo c 
									on b.id_map=c.id_map
									left join lop d
									on c.id_lo=d.id_lo
									) as master
									) as filter
									) as calc
									where nik_am='$type' $wheresegmen $wheretreg $wherewitel $wheretahun
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
				$id = $type;
				// if ($data['status']=='ACHIEVED') { $color='success'; } else { $color='danger'; }
				
				if ($data['status']=='ACHIEVED') { 
					$color='success';
					$returntstat	= '
						<div class="checkbox checkbox-info checkbox-circle">
							<input id="selectAll" type="checkbox" readonly checked>
							<label for="selectAll"><b>ACHIEVED</b></label>
						</div>
					';
				} else { 
					$color='danger'; 
					$returntstat	= '
						<div class="progress">
							<div class="progress-bar progress-bar-success progress-bar-striped progress-animated active" role="progressbar" aria-valuenow="'.number_format($data['persen'],2).'" aria-valuemin="0" aria-valuemax="100">
								<span  class="popOver" data-toggle="tooltip" data-placement="top" title="'.number_format($data['persen'],2).'%"> </span>  
							</div>
						</div>
						<script>
						$(document).ready(function () {
							$(".progress-animated").each(function () {
								each_bar_width = $(this).attr("aria-valuenow");
								$(this).width(each_bar_width + "%");
							});
						});
						</script>
					';
				}
	
				$row = array(
					$no,
					$data['nip'],
					"<a class='btndetailprojam' data-toggle='modal' data-target='#datadetailachsegsub' data-id='$id' style='color: #1e1e1e; cursor:pointer;'><b>".$data['namagc']."</b></a>",
					$this->formula->rupiah2($data['target']),
					$this->formula->rupiah2($data['nilai']),
					$data['persen']."%",
					$returntstat
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	
	//==================RAISA V2========================//
	//START PROJECT 	: 30 July 2018					//
	//DEVELOPER TEAM	: - Alamsyah S Putra			//
	//					  - Panji Pujianto				//
	//==================================================//
	//MANAGE DIVISI
	public function getdatadivisi(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/divisi');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];

			$getData			= $this->query->getData('divisi','*','ORDER BY id_divisi DESC');

			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_divisi'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					"",
					$data['nama_divisi'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){	
				$json['data'] = "";				
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function modaldivisi(){
		if(checkingsessionpwt()){
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('divisi','*',"WHERE id_divisi='".$id."' ORDER BY id_divisi DESC");
			
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
	public function deletedivisi(){
		if(checkingsessionpwt()){
			$this->load->model('query');
			$url 		= "Manage Divisi";
			$activity 	= "Delete";
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$rows = $this->query->deleteData2('divisi','id_divisi',$cond);
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
	public function insertdivisi(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			$url 		= "Manage Divisi";
			$activity 	= "INSERT";
			$nama_divisi= trim(strip_tags(stripslashes($this->input->post('nama_divisi',true))));
			$rows 		= $this->query->insertData('divisi', "id_divisi,nama_divisi", "'','$nama_divisi'");
			$id			= $this->db->insert_id();
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
	public function updatedivisi(){
		if(checkingsessionpwt()){
			$url 			= "Manage Divisi";
			$activity 		= "UPDATE";
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_id_divisi',true))));
			$nama_divisi	= trim(strip_tags(stripslashes($this->input->post('ed_nama_divisi',true))));
			$rows 			= $this->query->updateData('divisi',"nama_divisi='$nama_divisi'","WHERE id_divisi='".$id."'");
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
	
	//MANAGE LO
	public function getdatalo(){
		if(checkingsessionpwt()){
			$menu = 'panel/lo';
			$data_aksess = $this->query->getAkses($this->profile,$menu);
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid 			= $userdata['userid'];
			$uam				= $this->formula->getUAM($userid);
			
			@$divisi = $_GET['divisi'];
			@$segmen = $_GET['segmen'];
			@$treg = $_GET['treg'];
			@$witel = $_GET['witel'];
			@$am = $_GET['am'];
			
			if (fixURL($divisi)=='ALLDIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if (fixURL($segmen)=='ALLSEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if (fixURL($treg)=='ALLTREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if (fixURL($witel)=='ALLWITEL' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if (fixURL($am)=='ALLAM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			
			// $getData			= $this->query->getData('lo','*',"WHERE id_lo is not null $wheresegmen $wheretreg $wherewitel $wheream ORDER BY ID_LO ASC");
			// $getData			= $this->db->query("SELECT lo.*,map.id_segmen as segmen,am.nama_am,(SELECT DISTINCT(lop.treg) From lop WHERE lop.id_lo=lo.id_lo)as treg FROM lo,map,am WHERE lo.id_map = map.id_map AND map.nik_am = am.nik_am $wheresegmen $wheretreg $wherewitel $wheream")->result_array();
			$getData			= $this->query->getDatabyQ("select * from (SELECT a.*,b.id_segmen,b.id_witel,b.treg,b.nipnas,b.nik_am,c.nama_gc,b.id_divisi FROM lo a LEFT JOIN map b ON a.id_map = b.id_map LEFT JOIN gc c ON b.nipnas = c.nipnas) as master where id_lo!='' $wheredivisi $wheresegmen $wheretreg $wherewitel $wheream $uam");

			$no=0;
			$sum =0;
			foreach($getData as $data) { 
				$getDatapagu			= $this->query->getData('lop','SUM(pagu_proj)AS pagu_lo','WHERE id_lo="'.$data['id_lo'].' ORDER id_lo DESC"');
				foreach($getDatapagu as $datapagu) { 
					$pagu_lo = number_format((int)$datapagu['pagu_lo'],0); 
				}
				$no++;
				$id = $data['id_lo'];
				
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				//$pagu_lo = number_format((int)$data['pagu_lo'],0);
				$row = array(
					"",
					$data['id_lo'],
					$data['id_map'],
					$data['satker_lo'],
					$data['nama_gc'],
					$data['nama_keg'],
					$pagu_lo,
					$data['ta'],
					$data['sumber_dana'],
					$buttonupdate.''.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$json['data']="";
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function modallo(){
		$background		= '';
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('lo','*',"WHERE id_lo='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $data) {
					
					
					$row = array(
						'id_lo'			=> $data['id_lo'],
						'id_map'		=> $data['id_map'],
						'satker_lo'		=> $data['satker_lo'],
						'nama_keg'		=> $data['nama_keg'],
						'ta'			=> $data['ta'],
						'sumber_dana'	=> $data['sumber_dana'],
						'nilai_pagu'	=> $data['nilai_pagu']
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
	public function deletelo(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt');
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('lo','id_lo',$cond);
			$url 		= "Manage Lo";
			$activity 	= "DELETE";
			if(isset($rows)) {
				$log = $this->query->insertlog($activity,$url,$cond);
				$ins_log = $this->query->insertData('data_log', "id_log,userid,date_time,activity,menu,data,ip", "'','$userid','$datelog','$activity','$url',	'$cond','$ipaddr'");
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}	
	public function insertlo(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			
			//$id_lo			= trim(strip_tags(stripslashes($this->input->post('id_lo',true))));
			$id_map			= trim(strip_tags(stripslashes($this->input->post('id_map',true))));
			$satker_lo		= trim(strip_tags(stripslashes($this->input->post('satker_lo',true))));
			$nama_kegiatan	= trim(strip_tags(stripslashes($this->input->post('nama_kegiatan',true))));
			$tahun_anggaran	= trim(strip_tags(stripslashes($this->input->post('tahun_anggaran',true))));
			$sumber_dana	= trim(strip_tags(stripslashes($this->input->post('sumber_dana',true))));
			$nilai_pagu		= trim(strip_tags(stripslashes($this->input->post('pagu_projek',true))));
			
			$rows = $this->query->insertData('lo', "id_lo,id_map,satker_lo,nama_keg,ta,sumber_dana,nilai_pagu", "'','$id_map','$satker_lo','$nama_kegiatan','$tahun_anggaran','$sumber_dana','$nilai_pagu'");
			$id_lo = $this->db->insert_id();
			$url 		= "Manage Lo";
			$activity 	= "INSERT";
			$query = "
				SELECT b.treg FROM lo a LEFT JOIN map b ON a.id_map = b.id_map WHERE a.id_lo = $id_lo
			";
			$data = $this->db->query($query)->result_array();
			$row =array_shift($data);
			$treg = $row['treg'];
			
			if($rows) {
				$log = $this->query->insertlog($activity,$url,$id_lo);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}
	public function updatelo(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
				
			$id_lo			= trim(strip_tags(stripslashes($this->input->post('ed_id_lo',true))));
			$id_map			= trim(strip_tags(stripslashes($this->input->post('ed_id_map',true))));
			$satker_lo		= trim(strip_tags(stripslashes($this->input->post('ed_satker_lo',true))));
			$nama_kegiatan	= trim(strip_tags(stripslashes($this->input->post('ed_nama_kegiatan',true))));
			$tahun_anggaran	= trim(strip_tags(stripslashes($this->input->post('ed_tahun_anggaran',true))));
			$sumber_dana	= trim(strip_tags(stripslashes($this->input->post('ed_sumber_dana',true))));
			$nilai_pagu		= trim(strip_tags(stripslashes($this->input->post('ed_pagu_projek',true))));
			
			$rows = $this->query->updateData('lo',"id_map='$id_map',nilai_pagu='$nilai_pagu', satker_lo='$satker_lo',nama_keg='$nama_kegiatan',ta='$tahun_anggaran',sumber_dana='$sumber_dana'","WHERE id_lo='$id_lo'");
				
			$url 		= "Manage Lo";
			$activity 	= "UPDATE";
			$query = "
			SELECT b.treg FROM lo a LEFT JOIN map b ON a.id_map = b.id_map WHERE a.id_lo = $id_lo
			";
			$data = $this->db->query($query)->result_array();
			$row =array_shift($data);
			$treg = $row['treg'];
				
			if($rows) {
				$log = $this->query->insertlog($activity,$url,$id_lo);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}
	
	
	//MANAGE SEGMEN	
	public function getdatasegmen(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/segmen');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			
			$getData			= $this->query->getData('segmen a LEFT JOIN divisi b ON a.id_divisi = b.id_divisi','a.id_segmen,a.nama_segmen,a.id_divisi,b.nama_divisi,(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage Segmen" AND xa.data = a.id_segmen ORDER BY xa.date_time DESC limit 1)as update_by,(SELECT DATE_FORMAT(xa.date_time, "%d-%b-%y %H:%i:%s") as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage Segmen" AND xa.data = a.id_segmen ORDER BY xa.date_time DESC limit 1)as last_update','ORDER BY a.id_segmen DESC');
			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_segmen'];
				
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					"",
					$data['nama_segmen'],
					$data['nama_divisi'],
					$data['update_by'],
					$data['last_update'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){	
				$json['data'] = "";				
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function modalsegmen(){
		if(checkingsessionpwt()){
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('segmen','*',"WHERE id_segmen='".$id."' ORDER BY id_segmen DESC");
			
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
	public function deletesegmen(){
		if(checkingsessionpwt()){
			$url 		= "Manage Segmen";
			$activity 	= "Delete";
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$rows = $this->query->deleteData2('segmen','id_segmen',$cond);
			
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
	public function insertsegmen(){
		if(checkingsessionpwt()){
			$url 		= "Manage Segmen";
			$activity 	= "INSERT";
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			$nama_segmen	= trim(strip_tags(stripslashes($this->input->post('nama_segmen',true))));
			$divisi			= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$rows			= $this->query->insertData('segmen', "id_segmen,nama_segmen,id_divisi", "'','$nama_segmen','$divisi'");
			$id				= $this->db->insert_id();
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
	public function updatesegmen(){
		if(checkingsessionpwt()){
			$url 		= "Manage Segmen";
			$activity 	= "UPDATE";
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_id_segmen',true))));
			$nama_segmen	= trim(strip_tags(stripslashes($this->input->post('ed_nama_segmen',true))));
			$divisi			= trim(strip_tags(stripslashes($this->input->post('ed_divisi',true))));
			$rows 			= $this->query->updateData('segmen',"nama_segmen='$nama_segmen',id_divisi='$divisi'","WHERE id_segmen='".$id."'");
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
	
	//MANAGE WITEL	
	public function getdatawitel(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/witel');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];

			$getData			= $this->query->getData('witel a','a.*,
(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage Witel" AND xa.data = a.id_witel ORDER BY xa.date_time DESC limit 1)as update_by,
(SELECT DATE_FORMAT(xa.date_time, "%d-%b-%y %H:%i:%s") as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage Witel" AND xa.data = a.id_witel ORDER BY xa.date_time DESC limit 1)as last_update			
			','ORDER BY a.id_witel DESC');
			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_witel'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					"",
					$data['nama_witel'],
					$data['treg'],
					$data['update_by'],
					$data['last_update'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){	
				$json['data'] = "";				
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function modalwitel(){
		if(checkingsessionpwt()){
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('witel','*',"WHERE id_witel='".$id."' ORDER BY id_witel DESC");
			
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
	public function deletewitel(){
		if(checkingsessionpwt()){
			$url 		= "Manage Witel";
			$activity 	= "Delete";
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$rows = $this->query->deleteData2('witel','id_witel',$cond);
			
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
	public function insertwitel(){
		if(checkingsessionpwt()){
			$url 		= "Manage Witel";
			$activity 	= "INSERT";
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			$nama_witel		= trim(strip_tags(stripslashes($this->input->post('nama_witel',true))));
			$treg			= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$rows			= $this->query->insertData('witel', "id_witel,nama_witel,treg", "'','$nama_witel','$treg'");
			$id				= $this->db->insert_id();
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
	public function updatewitel(){
		if(checkingsessionpwt()){
			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id_witel',true))));
			$nama_witel	= trim(strip_tags(stripslashes($this->input->post('ed_nama_witel',true))));
			$treg		= trim(strip_tags(stripslashes($this->input->post('ed_treg',true))));
			$rows		= $this->query->updateData('witel',"nama_witel='$nama_witel',treg='$treg'","WHERE id_witel='".$id."'");
			$url 		= "Manage Witel";
			$activity 	= "UPDATE";
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
	
	//PEMENANG		
	public function getdatapemenang(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/pemenang');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			$getData			= $this->query->getData('pemenang a','a.*,(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage Pemenang" AND xa.data = a.id_pemenang ORDER BY xa.date_time DESC limit 1)as update_by,(SELECT DATE_FORMAT(xa.date_time, "%d-%b-%y %h:%i:%s %p") as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage Pemenang" AND xa.data = a.id_pemenang ORDER BY xa.date_time DESC limit 1)as last_update','ORDER BY id_pemenang DESC');
			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_pemenang'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					"",
					$data['pemenang'],
					$data['NPWP'],
					$data['alamat'],
					$data['update_by'],
					$data['last_update'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){	
				$json['data'] = "";				
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function modalpemenang(){
		if(checkingsessionpwt()){
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('pemenang','*',"WHERE id_pemenang='".$id."' ORDER BY id_pemenang DESC");
			
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
	public function deletepemenang(){
		if(checkingsessionpwt()){
			$this->load->model('query');
			$url 			= "Manage Pemenang";
			$activity 		= "DELETE";
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$rows = $this->query->deleteData2('pemenang','id_pemenang',$cond);
			
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
	public function insertpemenang(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			$url 			= "Manage Pemenang";
			$activity 		= "INSERT";
			$pemenang		= trim(strip_tags(stripslashes($this->input->post('pemenang',true))));
			$NPWP			= trim(strip_tags(stripslashes($this->input->post('npwp',true))));
			$alamat			= trim(strip_tags(stripslashes($this->input->post('alamat',true))));
			$rows			= $this->query->insertData('pemenang', "id_pemenang,pemenang,npwp,alamat", "'','$pemenang','$NPWP','$alamat'");
			$id			 	= $this->db->insert_id();
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
	public function insertpemenanglop(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			$url 			= "Manage Pemenang";
			$activity 		= "INSERT";
			$pemenang		= $_GET['pemenang'];
			$NPWP			= $_GET['npwp'];
			$alamat			= $_GET['alamat'];
			$rows			= $this->query->insertData('pemenang', "id_pemenang,pemenang,npwp,alamat", "'','$pemenang','$NPWP','$alamat'");
			$id			 	= $this->db->insert_id();
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
	public function updatepemenang(){
		if(checkingsessionpwt()){
			$url 			= "Manage Pemenang";
			$activity 		= "UPDATE";
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_id_pemenang',true))));
			$pemenang		= trim(strip_tags(stripslashes($this->input->post('ed_pemenang',true))));
			$NPWP			= trim(strip_tags(stripslashes($this->input->post('ed_npwp',true))));
			$alamat			= trim(strip_tags(stripslashes($this->input->post('ed_alamat',true))));
			$rows = $this->query->updateData('pemenang',"pemenang='$pemenang',NPWP='$NPWP',alamat='$alamat'","WHERE id_pemenang='".$id."'");
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
	
	//MANAGE ORDER		
	public function getdataorder(){
		if(checkingsessionpwt()){
			$data_aksess 		= $this->query->getAkses($this->profile,'panel/order');
			$shift 				= array_shift($data_aksess);
			$akses 				= $shift['akses'];
			$getData			= $this->query->getData('`order` a LEFT JOIN lop b ON a.id_lop=b.id_lop LEFT JOIN lo c ON b.id_lo = c.id_lo LEFT JOIN map d ON c.id_map = d.id_map LEFT JOIN segmen e ON e.id_segmen = d.id_segmen LEFT JOIN witel f ON f.id_witel = d.id_witel','a.*,e.nama_segmen,d.treg,f.nama_witel,d.nipnas, (select nama_gc from gc where nipnas=d.nipnas) as kldi,(SELECT us.name from data_log xx ,user us WHERE xx.userid = us.userid and xx.data=a.id_order ORDER BY date_time DESC LIMIT 1)as editby,(SELECT DATE_FORMAT(xx.date_time, "%d-%b-%y %H:%i:%s") from data_log xx ,user us WHERE xx.userid = us.userid and xx.data=a.id_order ORDER BY date_time DESC LIMIT 1)as edit_date','ORDER BY a.id_order DESC');
			$no					=0;
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_order'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					"",
					$data['nama_segmen'],
					$data['treg'],
					$data['nama_witel'],
					$data['kldi'],
					$data['id_lop'],
					$data['nomor_order'],
					$data['edit_date'],
					$data['editby'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){	
				$json['data'] = "";				
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function modalorder(){
		if(checkingsessionpwt()){
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('`order` a LEFT JOIN lop b ON a.id_lop=b.id_lop LEFT JOIN lo c ON b.id_lo = c.id_lo LEFT JOIN map d ON c.id_map = d.id_map LEFT JOIN segmen e ON e.id_segmen = d.id_segmen LEFT JOIN witel f ON f.id_witel = d.id_witel','a.*,e.id_segmen,d.treg,f.id_witel',"WHERE a.id_order='".$id."' ORDER BY a.id_order DESC");
			
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
	public function deleteorder(){
		if(checkingsessionpwt()){
			$this->load->model('query');
			$url 		= "Manage Order";
			$activity 	= "DELETE";
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$rows = $this->query->deleteData2('`order`','id_order',$cond);
			
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
	public function insertorder(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$url 		= "Manage Order";
			$activity 	= "INSERT";
			
			date_default_timezone_set("Asia/Bangkok");
			$id_lop			= trim(strip_tags(stripslashes($this->input->post('id_lop',true))));
			$nomor_order	= $_POST['nomor_order'];
			$n=0;
			for($i=0;$i<count($nomor_order);$i++){
				$rows			 = $this->query->insertData('order', "id_order,id_lop,nomor_order", "'','$id_lop','$nomor_order[$i]'");							
				$id				 = $this->db->insert_id();
				if($rows){
					$log = $this->query->insertlog($activity,$url,$id);
					$n++;
				}
			}
			
			if($n>0) {
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	
	public function updateorder(){
		if(checkingsessionpwt()){
			$url 		= "Manage Order";
			$activity 	= "UPDATE";
			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id_order',true))));
			$id_lop		= trim(strip_tags(stripslashes($this->input->post('ed_id_lop',true))));
			$nomor_order= trim(strip_tags(stripslashes($this->input->post('ed_nomor_order',true))));
			$rows = $this->query->updateData('order',"id_lop='$id_lop',nomor_order='$nomor_order'","WHERE id_order='".$id."'");
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
	public function getSelect2LOP(){
		if(checkingsessionpwt()){
			@$segmen 	 = $_GET['segmen'];
			@$treg 	 = $_GET['treg'];
			@$witel 	 = $_GET['witel'];
			
			$getData = $this->query->getData('lop a LEFT JOIN lo b ON a.id_lo=b.id_lo LEFT JOIN map c ON b.id_map = c.id_map','a.id_lop,a.nama_pkt',"WHERE c.id_segmen = '".$segmen."' and c.treg = '".$treg."' and c.id_witel = '".$witel."'");
			
			header('Content-type: application/json; charset=UTF-8');
			$row[] = array('id' => '','text' => '- Pilih LOP -' );
			
			foreach($getData as $data) {					
				$row[] = array(
					'id'		=> $data['id_lop'],
					'text'		=> $data['id_lop']."-".$data['nama_pkt'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = array('id' => '','text' => 'LOP Not Found' );
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	//AM
	public function getdataam(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/am');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			
			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid 			= $userdata['userid'];
			$getAksesUAM		= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
			$dataAksesUAM		= array_shift($getAksesUAM);
			$amUAM 				= str_replace(",","','",$dataAksesUAM['akses_am']);
			if ($amUAM=='all') {
				$valamUAM			= "";
			} else {
				$valamUAM			= "where nik_am in ('$amUAM')";
			}
			
			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid 			= $userdata['userid'];
			$uam				= $this->formula->getUAM($userid);
			
			
			$query				= "
								select * from (
								select 
								basic.*, 
								(select GROUP_CONCAT(DISTINCT akses_divisi) from user_organization where userid=basic.userid) as id_divisi,
								(select nama_divisi from divisi where id_divisi in (select GROUP_CONCAT(DISTINCT akses_divisi) from user_organization where userid=basic.userid)) as nama_divisi,
								(select GROUP_CONCAT(DISTINCT akses_segmen) from user_organization where userid=basic.userid) as id_segmen,
								(select GROUP_CONCAT(DISTINCT nama_segmen) from segmen where id_segmen in (select GROUP_CONCAT(DISTINCT akses_segmen) from user_organization where userid=basic.userid)) as nama_segmen,
								(select GROUP_CONCAT(DISTINCT akses_treg) from user_organization where userid=basic.userid) as treg,
								(select GROUP_CONCAT(DISTINCT akses_witel) from user_organization where userid=basic.userid) as id_witel,
								(select nama_witel from witel where id_witel in (select GROUP_CONCAT(DISTINCT akses_witel) from user_organization where userid=basic.userid)) as nama_witel,
								(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu='Manage AM' AND xa.data = basic.id_am ORDER BY xa.date_time DESC limit 1)as last_update,
								(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu='Manage AM' AND xa.data = basic.id_am ORDER BY xa.date_time DESC limit 1)as update_by
								from (
								SELECT 
								am.*, 
								(select GROUP_CONCAT(DISTINCT userid) from user where username=am.nik_am) as userid
								from am $valamUAM ORDER BY id_am DESC
								) as basic
								) as filuam
								where 1=1 $uam
								";
			
			$getData			= $this->query->getDatabyQ($query);

			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_am'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$ekssegmen	  = str_replace(',',"','",$data['id_segmen']);
				$ekswitel	  = str_replace(',',"','",$data['id_witel']);
				
				// $qNameSeg		= "select  from segmen ";
				$gNameSeg		= $this->query->getData('segmen','GROUP_CONCAT(DISTINCT nama_segmen) as nama_segmen',"where id_segmen in ('$ekssegmen')");
				$nameSeg		= array_shift($gNameSeg);
				
				$gNameWit		= $this->query->getData('witel','GROUP_CONCAT(DISTINCT nama_witel) as nama_witel',"where id_witel in ('$ekswitel')");
				$nameWit		= array_shift($gNameWit);
				
				if ($data['treg']=='all') {
					$treg	= 'ALL TREG';
				} else {
					$treg	= $data['treg'];
				}
				
				if ($data['id_witel']=='all') {
					$witel	= 'ALL WITEL';
				} else {
					$witel	= $nameWit['nama_witel'];
				}
				
				if ($data['raisa']=='1') { $raisa	= "<span class='text-center text-success'><i class='fa fa-circle'></i> YA</span>"; } else { $raisa	= "<span class='text-center text-danger'><i class='fa fa-circle'></i> TIDAK</span>"; }
				if ($data['productivity']=='1') { $productivity	= "<span class='text-center text-success'><i class='fa fa-circle'></i> YA</span>"; } else { $productivity	= "<span class='text-center text-danger'><i class='fa fa-circle'></i> TIDAK</span>"; }
				if ($data['pots']=='1') { $pots	= "<span class='text-center text-success'><i class='fa fa-circle'></i> YA</span>"; } else { $pots	= "<span class='text-center text-danger'><i class='fa fa-circle'></i> TIDAK</span>"; }
				if ($data['tamara']=='1') { $tamara	= "<span class='text-center text-success'><i class='fa fa-circle'></i> YA</span>"; } else { $tamara	= "<span class='text-center text-danger'><i class='fa fa-circle'></i> TIDAK</span>"; }
				
				$row = array(
					"",
					$data['nik_am'],
					$data['nama_am'],
					$data['posisi'],
					$data['band'],
					$data['nama_divisi'],
					$nameSeg['nama_segmen'],
					$treg,
					$witel,
					$raisa,
					$productivity,
					$pots,
					$tamara,
					$data['update_by'],
					$data['last_update'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function modalam(){
		$background		= '';
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q					= "
								select * from am a
								left join user b
								on a.nik_am=b.username
								left join user_organization c
								on b.userid=c.userid
								WHERE id_am='$id'
								";
			
			$dataCat			= $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $data) {
					
					
					$row = array(
						'id_am'		=> $data['id_am'],
						'nik_am'	=> $data['nik_am'],
						'nama_am'	=> $data['nama_am'],
						'posisi'	=> $data['posisi'],
						'band'		=> $data['band'],
						'raisa'		=> $data['raisa'],
						'prod'		=> $data['productivity'],
						'pots'		=> $data['pots'],
						'tamara'	=> $data['tamara'],
						'akses_divisi'	=> $data['akses_divisi'],
						'akses_segmen'	=> $data['akses_segmen'],
						'akses_treg'	=> $data['akses_treg'],
						'akses_witel'	=> $data['akses_witel']
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
	public function insertam(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt');
			date_default_timezone_set("Asia/Bangkok");
			
			$id_am 		= trim(strip_tags(stripslashes($this->input->post('id_am',true))));;
			$nik_am		= trim(strip_tags(stripslashes($this->input->post('nik_am',true))));
			$nama_am	= trim(strip_tags(stripslashes($this->input->post('nama_am',true))));
			$posisi		= trim(strip_tags(stripslashes($this->input->post('posisi',true))));
			$band		= trim(strip_tags(stripslashes($this->input->post('band',true))));
			$divisi		= trim(strip_tags(stripslashes($this->input->post('data_divisi',true))));
			$segmen		= trim(strip_tags(stripslashes($this->input->post('data_segmen',true))));
			$treg		= trim(strip_tags(stripslashes($this->input->post('data_treg',true))));
			$witel		= trim(strip_tags(stripslashes($this->input->post('data_witel',true))));
			
			$raisaakses	= trim(strip_tags(stripslashes($this->input->post('raisaakses',true))));
			$prodakses	= trim(strip_tags(stripslashes($this->input->post('prodakses',true))));
			$potsakses	= trim(strip_tags(stripslashes($this->input->post('potsakses',true))));
			$tamaraakses= trim(strip_tags(stripslashes($this->input->post('tamaraakses',true))));
			
			$rows 		= $this->query->insertData('am', "id_am,nik_am,nama_am,posisi,band,raisa,productivity,pots,tamara", "'$id_am','$nik_am','$nama_am','$posisi','$band','$raisaakses','$prodakses','$potsakses','$tamaraakses'");
			$id			= $this->db->insert_id();
			$url 		= "Manage AM";
			$activity 	= "INSERT";
			
			if($rows) {
				$log = $this->query->insertlog($activity,$url,$id);
				
				$getuserEks		= $this->query->getNumRows('user','*',"WHERE username='$nik_am'")->num_rows();
				if ($getuserEks>0) {
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					// UPDATE USER ORGANISATION
					$useridup	= $userdata['userid'];
					$getEksUAM	= $this->query->getData('user_organization','akses_am',"where userid='$useridup'");
					$EksUAM		= array_shift($getEksUAM);
					if ($EksUAM['akses_am']!='all') {
						$dataUAMX	= $EksUAM['akses_am'];
						$newUAM		= $dataUAMX.",".$nik_am;
						$updateUAM	= $this->query->updateData('user_organization',"akses_am='$newUAM'","WHERE userid='$useridup'");
					}
					
					// INSERT NEW USER AM
					$getMaxUID	= $this->db->query("SELECT max(userid)as max_id_user FROM user")->result_array();
					$maxUID		= array_shift($getMaxUID);
					$userid		= $maxUID['max_id_user']+1;
					$password	= md5($nik_am);
					
					$insUser 	= $this->query->insertData('user', "userid,username,name,password,picture,id_role,email,role_tamara", "'$userid','$nik_am','$nama_am','$password','','13','','2'");
					$org 		= $this->query->insertData('user_organization', "id_user_org,userid,akses_divisi,akses_segmen,akses_treg,akses_witel,akses_am", "'','$userid','$divisi','$segmen','$treg','$witel','$nik_am'");
					
					print json_encode(array('success'=>true,'total'=>1));
				}
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}
	public function updateam(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$id_am 		= trim(strip_tags(stripslashes($this->input->post('ed_id_am',true))));
			$nik_am		= trim(strip_tags(stripslashes($this->input->post('ed_nik_am',true))));
			$nama_am	= trim(strip_tags(stripslashes($this->input->post('ed_nama_am',true))));
			$posisi		= trim(strip_tags(stripslashes($this->input->post('ed_posisi',true))));
			$band		= trim(strip_tags(stripslashes($this->input->post('ed_band',true))));
			$divisi		= trim(strip_tags(stripslashes($this->input->post('ed_data_divisi',true))));
			$segmen		= trim(strip_tags(stripslashes($this->input->post('ed_data_segmen',true))));
			$treg		= trim(strip_tags(stripslashes($this->input->post('ed_data_treg',true))));
			$witel		= trim(strip_tags(stripslashes($this->input->post('ed_data_witel',true))));
			
			$raisaakses	= trim(strip_tags(stripslashes($this->input->post('ed_raisaakses',true))));
			$prodakses	= trim(strip_tags(stripslashes($this->input->post('ed_prodakses',true))));
			$potsakses	= trim(strip_tags(stripslashes($this->input->post('ed_potsakses',true))));
			$tamaraakses= trim(strip_tags(stripslashes($this->input->post('ed_tamaraakses',true))));
			
			// GET EKSIS AM DATA
			$getEksNA	= $this->query->getData('am','nik_am',"where id_am='$id_am'");
			$EksNA		= array_shift($getEksNA);
			$na			= $EksNA['nik_am'];
			
			// GET EKSIS USER DATA
			$getEksUID	= $this->query->getData('user','userid',"where username='$na'");
			$EksUID		= array_shift($getEksUID);
			$uid		= $EksUID['userid'];
			
			// GET EKSIS USORG DATA
			$getEksUAM	= $this->query->getData('user_organization','*',"where userid='$uid'");
			$EksUAM		= array_shift($getEksUAM);
			$uamAM		= $EksUAM['akses_am'];
			$repUAMAM	= str_replace($na,$nik_am,$uamAM);
			
			if ($na!=$nik_am) {
				// UPDATE MAP
				$updateMAP	= $this->query->updateData('map',"nik_am='$nik_am'","WHERE nik_am='$na'");
				
				// UPDATE USERNAME
				$updateUNAME	= $this->query->updateData('user',"username='$nik_am'","WHERE userid='$uid'");
			}
			
			// UPDATE AM
			$rows = $this->query->updateData('am',"nik_am='$nik_am', nama_am='$nama_am',posisi='$posisi',band='$band', raisa='$raisaakses', productivity='$prodakses', pots='$potsakses', tamara='$tamaraakses'","WHERE id_am='$id_am'");
			$url 		= "Manage AM";
			$activity 	= "UPDATE";
			
			if($rows) {
				// UPDATE USER ORGANISATION
				$updateUAM	= $this->query->updateData('user_organization',"akses_divisi='$divisi', akses_segmen='$segmen', akses_treg='$treg', akses_witel='$witel', akses_am='$repUAMAM'","WHERE userid='$uid'");
				
				$log = $this->query->insertlog($activity,$url,$id_am);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}
	public function deleteam(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('am','id_am',$cond);
			$url 		= "Manage AM";
			$activity 	= "DELETE";
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

	//MANAGE GC
	public function getdatagc(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/gc');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			
			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid 			= $userdata['userid'];
			$getAksesUAM		= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
			$dataAksesUAM		= array_shift($getAksesUAM);
			$divisiUAMD			= str_replace(",","','",$dataAksesUAM['akses_divisi']);
			$divisiUAMS			= str_replace(",","','",$dataAksesUAM['akses_segmen']);
			$divisiUAMT			= str_replace(",","','",$dataAksesUAM['akses_treg']);
			$divisiUAMW			= str_replace(",","','",$dataAksesUAM['akses_witel']);
			
			if ($dataAksesUAM['akses_divisi']=='all') { $condDiv = ''; } else { $condDiv = "and b.id_divisi in ('$divisiUAMD')"; }
			if ($dataAksesUAM['akses_segmen']=='all') { $condSeg = ''; } else { $condSeg = "and b.id_segmen in ('$divisiUAMS')"; }
			if ($dataAksesUAM['akses_treg']=='all')   { $condTreg = ''; } else { $condTreg = "and b.treg in ('$divisiUAMT')"; }
			if ($dataAksesUAM['akses_witel']=='all')  { $condWit = ''; } else { $condWit = "and b.id_witel in ('$divisiUAMW')"; }

			// $getData			= $this->query->getData('gc','*','ORDER BY id_gc DESC');
			$q					= "
								select * from (
									select 
									id_gc, nipnas, nama_gc, pic, 
									GROUP_CONCAT(DISTINCT id_divisi) as id_divisi,
									GROUP_CONCAT(DISTINCT nama_divisi) as divisi,
									GROUP_CONCAT(DISTINCT id_segmen) as id_segmen,
									GROUP_CONCAT(DISTINCT nama_segmen) as segmen,
									GROUP_CONCAT(DISTINCT treg) as treg,
									GROUP_CONCAT(DISTINCT id_witel) as id_witel,
									GROUP_CONCAT(DISTINCT nama_witel) as witel
									from (
										select a.*, 
										b.id_divisi, c.nama_divisi, 
										b.id_segmen, d.nama_segmen,
										b.treg, 
										b.id_witel, e.nama_witel
										from gc a
										left join map b
										on a.nipnas=b.nipnas
										left join divisi c
										on b.id_divisi=c.id_divisi
										left join segmen d
										on b.id_segmen=d.id_segmen
										left join witel e
										on b.id_witel=e.id_witel
										WHERE b.id_segmen is not null $condDiv $condSeg $condTreg $condWit
									) as basic
									group by id_gc
								) as master
								";
			$getData			= $this->query->getDatabyQ($q);

			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_gc'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					"",
					$data['nipnas'],
					$data['nama_gc'],
					$data['pic'],
					$data['divisi'],
					$data['segmen'],
					$data['treg'],
					$data['witel'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function modalgc(){
		$background		= '';
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('gc','*',"WHERE id_gc='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $data) {
					
					
					$row = array(
						'id_gc'		=> $data['id_gc'],
						'nipnas'	=> $data['nipnas'],
						'nama_gc'	=> $data['nama_gc'],
						'pic'		=> $data['pic']
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
	public function insertgc(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			
			$id_gc 		= trim(strip_tags(stripslashes($this->input->post('id_gc',true))));
			$nipnas		= trim(strip_tags(stripslashes($this->input->post('nipnas',true))));
			$nama_gc	= trim(strip_tags(stripslashes($this->input->post('nama_gc',true))));
			$pic		= trim(strip_tags(stripslashes($this->input->post('pic',true))));
			
			$rows = $this->query->insertData('gc', "id_gc,nipnas,nama_gc,pic", "'$id_gc','$nipnas','$nama_gc','$pic'");
			$id			= $this->db->insert_id();
			$url 		= "Manage GC";
			$activity 	= "INSERT";
			if($rows) {
				$log = $this->query->insertlog($activity,$url,$id);
				print json_encode(array('success'=>true,'total'=>1,'message'=>''));
			} else {
				print json_encode(array('success'=>false,'total'=>1,'message'=>$this->db->error()));
				//var_dump($this->db->error());
				// try{
					// $rows;die("success");
				// }
				// catch(Exception $e)
				// {
					// echo $this->db->error();die("error");
				// }
			}
		} else {
			redirect('/panel');
		}
	}		
	public function updategc(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
				
			$id_gc 		= trim(strip_tags(stripslashes($this->input->post('ed_id_gc',true))));;
			$nipnas		= trim(strip_tags(stripslashes($this->input->post('ed_nipnas',true))));
			$nama_gc	= trim(strip_tags(stripslashes($this->input->post('ed_nama_gc',true))));
			$pic		= trim(strip_tags(stripslashes($this->input->post('ed_pic',true))));
			
			$rows = $this->query->updateData('gc',"nipnas='$nipnas', nama_gc='$nama_gc',pic='$pic'","WHERE id_gc='$id_gc'");
				$url 		= "Manage GC";
				$activity 	= "UPDATE";
				if($rows) {
					$log = $this->query->insertlog($activity,$url,$id_gc);
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
		} else {
			redirect('/panel');
		}
	}	
	public function deletegc(){
		if(checkingsessionpwt()){
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('gc','id_gc',$cond);
			$userdata	= $this->session->userdata('sesspwt'); 
			$url 		= "Manage GC";
			$activity 	= "DELETE";
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
	
	//MANAGE SUBSIDARIES
	public function getdatasubsidaries(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/subs');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			
			$getData = $this->query->getData('subs','*','ORDER BY id_subs DESC');
			
			$no=0;
			$buttonupdate = "";
			$buttondelete = "";
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_subs'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					"",
					$data['id_subs'],
					$data['nama_subs'],
					$data['cfu'],
					$data['pic_subs'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function modalsubsidaries(){
		$background		= '';
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('subs','*',"WHERE id_subs='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $data) {
					
					
					$row = array(
						'id_subs'		=> $data['id_subs'],
						'nama_subs'		=> $data['nama_subs'],
						'cfu'			=> $data['cfu'],
						'pic_subs'		=> $data['pic_subs']
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
	public function insertsubsidaries(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$id_subs 		= trim(strip_tags(stripslashes($this->input->post('id_subs',true))));
			$nama_subs		= trim(strip_tags(stripslashes($this->input->post('nama_subs',true))));
			$cfu			= trim(strip_tags(stripslashes($this->input->post('cfu',true))));
			$pic_subs		= trim(strip_tags(stripslashes($this->input->post('pic_subs',true))));
			
			$rows		= $this->query->insertData('subs', "id_subs,nama_subs,cfu,pic_subs", "'','$nama_subs','$cfu','$pic_subs'");
			$id			= $this->db->insert_id();
			$url 		= "Manage Subsidaries";
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
	public function updatesubsidaries(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$id_subs 		= trim(strip_tags(stripslashes($this->input->post('ed_id_subs',true))));
			$nama_subs	= trim(strip_tags(stripslashes($this->input->post('ed_nama_subs',true))));
			$cfu		= trim(strip_tags(stripslashes($this->input->post('ed_cfu',true))));
			$pic_subs		= trim(strip_tags(stripslashes($this->input->post('ed_pic_subs',true))));
			
			$rows = $this->query->updateData('subs',"nama_subs='$nama_subs', cfu='$cfu',pic_subs='$pic_subs'","WHERE id_subs='$id_subs'");
			$url 		= "Manage Subsidaries";
			$activity 	= "UPDATE";
			if($rows) {
				$log = $this->query->insertlog($activity,$url,$id_subs);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
	} else {
			redirect('/panel');
		}
	}	
	public function deletesubsidaries(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('subs','id_subs',$cond);
			$url 		= "Manage Subsidaries";
			$activity 	= "DELETE";
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
	
	//DATA LOG
	public function getdatalogex(){
		if(checkingsessionpwt()){
			@$start = $_GET['start'];
			@$end = $_GET['end'];
			@$treg = $_GET['treg'];
			
			if ($treg=='All TREG' or $treg=='') 	{ $wheretreg	= "";$wheretreg2 = ""; } else { $wheretreg = "and c.treg='".$treg."' AND c.treg !=''";$wheretreg2 = "and treg='".$treg."'"; }
			if ($start=='' and $end=='') 	{ $wheredaterange	= ""; } else { $wheredaterange = "and DATE_FORMAT(date_time,'%Y-%m-%d') between '".$start."' AND '".$end."'"; }
			
			//$getData			= $this->query->getData('data_log','*','WHERE id_log != "" $wheredaterange $wheretreg ORDER BY date_time DESC');
			$getData			= $this->db->query("SELECT * FROM data_log WHERE id_log !='' $wheredaterange $wheretreg2 ORDER BY `id_log` DESC")->result_array();
			$no=0;
			$dat="";
			$satker_lo="";
			$kldi="";
			
			foreach($getData as $data) { 
				$getdatausers			= $this->query->getData('user','*','WHERE userid="'.$data['userid'].'"');
				foreach($getdatausers as $datauser) { 
					$user = $datauser['name']; 
					$pic = "<center><img src='".base_url()."images/user/".$datauser['picture']."' class='user'></center>";
				}
				
				if(trim($data['menu'])=='Manage role'){
					$tables = 'role';
					$parameter = 'nama_role as datass';
					$condition = 'where id_role="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass']; 
						$satker_lo = "";
						$kldi = "";
						$treg = "";
					}
				}
				if(trim($data['menu'])=='Manage User'){
					$tables = 'user';
					$parameter = 'username as datass';
					$condition = 'where userid="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass']; 
						$satker_lo = "";
						$kldi = "";
						$treg = "";
					}
				}
				if(trim($data['menu'])=='Manage Menu'){
					$tables = 'menu';
					$parameter = 'menu as datass';
					$condition = 'where id_menu="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
						$satker_lo = "";
						$kldi = "";	
						$treg = "";
					}
				}
				if(trim($data['menu'])=='Manage Mapping'){
					$tables = 'map';
					$parameter = 'id_map as datass';
					$condition = 'where id_map="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass']; 
						$satker_lo = "";
						$kldi = "";
						$treg = "";
					}
				}
				if(trim($data['menu'])=='Manage Subsidaries'){
					$tables = 'subs';
					$parameter = 'nama_subs as datass';
					$condition = 'where id_subs="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass'];
						$satker_lo = "";
						$kldi = "";		
						$treg = "";
					}
				}
				if(trim($data['menu'])=='Manage GC'){
					$tables = 'gc';
					$parameter = 'nama_gc as datass';
					$condition = 'where id_gc	="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass']; 
						$satker_lo = "";
						$kldi = "";	
						$treg = "";
					}
				}
				if(trim($data['menu'])=='Manage AM'){
					$tables = 'am';
					$parameter = 'nama_am as datass';
					$condition = 'where id_am="'.$data['data'].'"';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass']; 
						$satker_lo = "";
						$kldi = "";		
						$treg = "";
					}
				}
				if(trim($data['menu'])=='Manage Lop'){
					$tables = '`lop` a LEFT JOIN lo b ON a.id_lo = b.id_lo LEFT JOIN map c ON b.id_map = c.id_map LEFT JOIN gc d ON c.nipnas = d.nipnas';
					$parameter = 'a.id_lop,a.nama_pkt as datass,b.id_lo,b.satker_lo,d.nama_gc,c.treg';
					$condition = 'where id_lop="'.$data['data'].'" '.$wheretreg.'';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass']; 
						$satker_lo = $data_data['satker_lo']; 
						$kldi = $data_data['nama_gc'];		
						$treg = $data_data['treg'];
					}
				}
				if(trim($data['menu'])=='Manage Lo'){
					$tables = 'lo a LEFT JOIN map c ON a.id_map = c.id_map';
					$parameter = 'a.nama_keg as datass,c.treg';
					$condition = 'where a.id_lo="'.$data['data'].'" '.$wheretreg.'';
					$get_data = $this->query->getData($tables,$parameter,$condition);
					foreach($get_data as $data_data) { 
						$dat = $data_data['datass']; 
						$satker_lo = "";
						$kldi = "";		
						$treg = $data_data['treg'];								
					}
				}
				
				
				$no++;
				// $id = $data['id_lo'];
				//$pagu_lo = number_format((int)$data['pagu_lo'],0);
				$row = array(
					"",
					$data['date_time'],
					$pic,
					$user,
					$data['activity'],
					$data['data'],
					$dat,
					$satker_lo,
					$kldi,
					$treg,
					$data['menu'],
					$data['ip']
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$row = array("","","","","","","","","","","","");
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	//PENYEDIA
	public function getdatapenyedia(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/subs');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];			
			$datarole			= $this->query->getData('penyedia','*','ORDER BY id_penyedia DESC');
			$no=0;
			foreach($datarole as $data) { 
				$no++;
				$id = $data['id_penyedia'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					$no,
					$data['id_lo'],
					$data['kode_lelang'],
					$data['waktu_lelang'],
					$data['penyedia'],
					$data['alamat'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$json['data'] = "";
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function modalpenyedia(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$datarole			= $this->query->getData('penyedia','*',"WHERE id_penyedia='".$id."' ORDER BY id_penyedia DESC");
			
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
	public function insertpenyedia(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			$id_penyedia		= trim(strip_tags(stripslashes($this->input->post('id_penyedia',true))));
			$id_lo				= trim(strip_tags(stripslashes($this->input->post('id_lo',true))));
			$kode_lelang		= trim(strip_tags(stripslashes($this->input->post('kode_lelang',true))));
			$penyedia			= trim(strip_tags(stripslashes($this->input->post('penyedia',true))));
			$waktu_lelang		= trim(strip_tags(stripslashes($this->input->post('waktu_lelang',true))));
			$alamat				= trim(strip_tags(stripslashes($this->input->post('alamat',true))));
			
			
			
			
			if($waktu_lelang == NULL){
				$waktus = 'NULL';
			}else{
				$waktus = $waktu_lelang;
			}
			
			$rows = $this->query->insertData('penyedia', "id_penyedia,kode_lelang,waktu_lelang,penyedia,alamat,id_lo", "'','$kode_lelang','$waktu_lelang','$penyedia','$alamat','$id_lo'");
			
			$id			= $this->db->insert_id();
			$url 		= "Manage Penyedia";
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
	public function updatepenyedia(){
		if(checkingsessionpwt()){
			$userdata		= $this->session->userdata('sesspwt'); 
			$id_penyedia 		= trim(strip_tags(stripslashes($this->input->post('ed_id_penyedia',true))));
			$id_lo			= trim(strip_tags(stripslashes($this->input->post('ed_id_lo',true))));
			$kode_lelang	= trim(strip_tags(stripslashes($this->input->post('ed_kode_lelang',true))));
			$waktu_lelang	= trim(strip_tags(stripslashes($this->input->post('ed_waktu_lelang',true))));
			$penyedia		= trim(strip_tags(stripslashes($this->input->post('ed_penyedia',true))));
			$alamat		= trim(strip_tags(stripslashes($this->input->post('ed_alamat',true))));
			$rows = $this->query->updateData('penyedia',"id_lo='$id_lo', kode_lelang='$kode_lelang',waktu_lelang='$waktu_lelang', penyedia='$penyedia', alamat='$alamat'","WHERE id_penyedia='$id_penyedia'");
			$url 		= "Manage Penyedia";
			$activity 	= "UPDATE";
				if($rows) {
					$log = $this->query->insertlog($activity,$url,$id_penyedia);
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
		} else {
			redirect('/panel');
		}
	}
	public function deletepenyedia(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('penyedia','id_penyedia',$cond);
			$url 		= "Manage Subsidaries";
			$activity 	= "DELETE";
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
	
	//MANAGE MAPPING
	public function getdatamapv2(){
		if(checkingsessionpwt()){
			$data_aksess 		= $this->query->getAkses($this->profile,'panel/map');
			$shift				= array_shift($data_aksess);
			$akses 				= $shift['akses'];
			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid 			= $userdata['userid'];
			$uam				= $this->formula->getUAM($userid);
			
			@$divisi = $_GET['divisi'];
			@$segmen = $_GET['segmen'];
			@$treg = $_GET['treg'];
			@$witel = $_GET['witel'];
			@$am = $_GET['am'];
			
			if (fixURL($divisi)=='ALLDIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if (fixURL($segmen)=='ALLSEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if (fixURL($treg)=='ALLTREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if (fixURL($witel)=='ALLWITEL' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if (fixURL($am)=='ALLAM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			
			$getData			= $this->query->getData('map a LEFT JOIN segmen b ON a.id_segmen = b.id_segmen LEFT JOIN witel c ON a.id_witel = c.id_witel LEFT JOIN divisi d ON a.id_divisi = d.id_divisi LEFT JOIN gc e ON a.nipnas = e.nipnas LEFT JOIN am f ON a.nik_am = f.nik_am','* FROM (SELECT a.*,e.nama_gc as K_L_D_I,f.nama_am as am,b.nama_segmen as segmen,c.nama_witel as witel,d.nama_divisi as divisi,(SELECT DATE_FORMAT(xa.date_time, "%d-%b-%y %H:%i:%s") as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage Mapping" AND xa.data = a.id_map ORDER BY xa.date_time DESC limit 1)as last_update,(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage Mapping" AND xa.data = a.id_map ORDER BY xa.date_time DESC limit 1)as update_by','ORDER BY a.id_map DESC )as basic WHERE id_map IS NOT NULL '.$wheredivisi.' '.$wheresegmen.' '.$wheretreg.' '.$wherewitel.' '.$wheream.' '.$uam.'');
			$no=0;
			foreach($getData as $data) { 
				//echo var_dump($data);
				$no++;
				$id = $data['id_map'];
				$dates = $data['bulan_mapping']."-".date('Y');
				$timestamp = strtotime($dates);
				$newDate = date('M-y', $timestamp); 
				//$buttonupdate = getRoleUpdate_Custom($akses,'update',$id,$data['tahun']);
				//$buttondelete = getRoleDelete_Custom($akses,'delete',$id,$data['tahun']);
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$target_rev = number_format((int)$data['target_rev'],0);
				$row = array(
					"",
					$data['id_map'],
					$data['nipnas'],
					$data['K_L_D_I'],
					$data['nik_am']."-".$data['am'],
					$data['divisi'],
					$data['segmen'],
					$data['witel'],
					$data['treg'],
					$newDate,
					$target_rev,
					$data['note'],
					$data['tahun'],
					$data['update_by'],
					$data['last_update'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function insertmapv2(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");			
			$url 			= "Manage Mapping";
			$activity 		= "INSERT";
			//$id_map			= trim(strip_tags(stripslashes($this->input->post('id_map',true))));
			$nipnas			= trim(strip_tags(stripslashes($this->input->post('nipnas',true))));
			$divisi			= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$nik_am			= trim(strip_tags(stripslashes($this->input->post('nik_am',true))));
			$segmen			= trim(strip_tags(stripslashes($this->input->post('segmen',true))));
			$witel			= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$treg			= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$target_rev		= trim(strip_tags(stripslashes($this->input->post('target_rev',true))));
			$note			= trim(strip_tags(stripslashes($this->input->post('note',true))));
			$bulan_mapping1	= trim(strip_tags(stripslashes($this->input->post('bulan_mapping',true))));
			$tahun			= trim(strip_tags(stripslashes($this->input->post('tahun',true))));
			$map 			= $bulan_mapping1."-01";
			$date			= date_create($map);
			$bulan_mapping 	= date_format($date,"M-y");			
			$rows = $this->query->insertData('map', "id_map,nipnas,nik_am,id_segmen,id_witel,treg,bulan_mapping,target_rev,id_divisi,note,tahun", "'','$nipnas','$nik_am','$segmen','$witel','$treg','$bulan_mapping','$target_rev','$divisi','$note','$tahun'");
			$id_map = $this->db->insert_id();
			if($rows) {
				$log = $this->query->insertlog($activity,$url,$id_map);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}
	public function modalmapv2(){
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
						'segmen'			=> $data['id_segmen'],
						'witel'				=> $data['id_witel'],
						'treg'				=> $data['treg'],
						'divisi'			=> $data['id_divisi'],
						'bulan_mapping'		=> $data['bulan_mapping'],
						'target_rev'		=> $data['target_rev'],
						'note'				=> $data['note'],
						'tahun'				=> $data['tahun']
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
	public function deletemapv2(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$this->load->model('query');
			$url 		= "Manage Mapping";
			$activity 	= "DELETE";
			$cond		= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('map','id_map',$cond);
			
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
	public function updatemapv2(){
		if(checkingsessionpwt()){
			$userdata		= $this->session->userdata('sesspwt'); 
			$url 			= "Manage Mapping";
			$activity 		= "UPDATE";
			$id_map			= trim(strip_tags(stripslashes($this->input->post('ed_id_map',true))));
			$nipnas			= trim(strip_tags(stripslashes($this->input->post('ed_nipnas',true))));
			$nik_am			= trim(strip_tags(stripslashes($this->input->post('ed_nik_am',true))));
			$divisi			= trim(strip_tags(stripslashes($this->input->post('ed_divisi',true))));
			$segmen			= trim(strip_tags(stripslashes($this->input->post('ed_segmen',true))));
			$witel			= trim(strip_tags(stripslashes($this->input->post('ed_witel',true))));
			$treg			= trim(strip_tags(stripslashes($this->input->post('ed_treg',true))));
			$target_rev		= trim(strip_tags(stripslashes($this->input->post('ed_target_rev',true))));
			$note			= trim(strip_tags(stripslashes($this->input->post('ed_note',true))));
			$bulan_mapping1	= trim(strip_tags(stripslashes($this->input->post('ed_bulan_mapping',true))));
			$tahun			= trim(strip_tags(stripslashes($this->input->post('ed_tahun',true))));
			$map = $bulan_mapping1."-01";
			$date=date_create($map);
			$bulan_mapping = date_format($date,"M-y");
			
			
			$rows = $this->query->updateData('map',"nipnas='$nipnas', nik_am='$nik_am',id_divisi='$divisi',id_segmen='$segmen', id_witel='$witel', treg='$treg', bulan_mapping='$bulan_mapping', target_rev='$target_rev', note='$note' , tahun='$tahun'","WHERE id_map='$id_map'");
				
				if($rows) {
					$log = $this->query->insertlog($activity,$url,$id_map);
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
		} else {
			redirect('/panel');
		}
	}
	
	public function getSelect2Segmen(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			$id 	 = $_GET['id'];
			$segmen	 = $this->segmenUAM;
			
			if (isset($id) && !empty($id)) {
				$cond	= "WHERE id_segmen!='' $segmen and id_divisi = '$id'";
			} else {
				$cond	= "";
			}
			
			$getData = $this->query->getData('segmen','*',"$cond ORDER BY id_segmen DESC");
			
			header('Content-type: application/json; charset=UTF-8');
			$row[] = array('id' => '','text' => '- Pilih Segmen -' );
			
			foreach($getData as $data) {				
				$row[] = array(
					'id'		=> $data['id_segmen'],
					'text'		=> $data['nama_segmen'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function getSelect2SegmenD(){
		if(checkingsessionpwt()){
			
			$getid 	 = $_GET['id'];
			$segmen	 = $this->segmenUAM;
			if ($getid=='ALL DIVISI') { $id = ''; } else { $id = "and id_divisi = '$getid'"; }
			
			if (isset($id) && !empty($id)) {
				$cond	= "WHERE id_segmen!='' $segmen $id";
			} else {
				$cond	= "";
			}
			
			$getData = $this->query->getData('segmen','*',"$cond ORDER BY id_segmen DESC");
			
			header('Content-type: application/json; charset=UTF-8');
			
			$row[] = array('id' => 'ALL SEGMEN','text' => 'ALL SEGMEN' );
			
			foreach($getData as $data) {					
				$row[] = array(
					'id'		=> $data['id_segmen'],
					'text'		=> $data['nama_segmen'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function getSelect2Treg(){
		if(checkingsessionpwt()){
			
			$id 	 = $_GET['id'];
			$treg	 = $this->tregUAM;
			
			if (isset($id) && !empty($id)) {
				$cond	= "WHERE treg!='' $treg and id_segmen = '$id'";
			} else {
				$cond	= "";
			}
			
			$getData = $this->query->getData('map','DISTINCT(treg)as treg',"$cond ORDER BY treg ASC");
			
			header('Content-type: application/json; charset=UTF-8');
			
			$row[] = array('id' => '','text' => '- Pilih TREG -' );
			
			foreach($getData as $data) {					
				$row[] = array(
					'id'		=> $data['treg'],
					'text'		=> $data['treg'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function getSelect2TregD(){
		if(checkingsessionpwt()){
			
			@$getid 	= $_GET['id'];
			@$idsegmen	= $_GET['idsegmen'];
			if ($getid=='ALL DIVISI') { $id = ''; } else { $id = "and id_divisi = '$getid'"; }
			if ($idsegmen=='ALL SEGMEN') { $ids = ''; } else { $ids = "and id_segmen = '$idsegmen'"; }
			
			$segmen	 = $this->segmenUAM;
			$treg	 = $this->tregUAM;
			
			
			if (isset($id) && !empty($id)) { $cond	= $id; } else { $cond	= ""; }
			if (isset($idsegmen) && !empty($idsegmen)) { $condseg	= $ids; } else { $condseg	= ""; }
			
			$getData = $this->query->getData('map','DISTINCT(treg)as treg',"WHERE treg!='' $treg $segmen $cond $condseg ORDER BY treg ASC");
			
			header('Content-type: application/json; charset=UTF-8');
			
			$row[] = array('id' => 'ALL TREG','text' => 'ALL TREG' );
			
			foreach($getData as $data) {					
				$row[] = array(
					'id'		=> $data['treg'],
					'text'		=> $data['treg'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function getSelect2Witel(){
		if(checkingsessionpwt()){
			$id 	 = str_replace('%20',' ',$_GET['id']);
			$witel	 = $this->witelUAM;
			
			if (isset($id) && !empty($id)) { $cond	= "WHERE treg = '$id' $witel"; } else { $cond	= ""; }
			
			$getData = $this->query->getData('witel','*',"$cond ORDER BY nama_witel ASC");
			
			header('Content-type: application/json; charset=UTF-8');
			$row[] = array('id' => '','text' => '- Pilih Witel -' );
			
			foreach($getData as $data) {					
				$row[] = array(
					'id'		=> $data['id_witel'],
					'text'		=> $data['nama_witel'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function getSelect2WitelP(){
		if(checkingsessionpwt()){
			$id 	 = $_GET['id'];
			$witel	 = $this->witelUAM;
			
			if (isset($id) && !empty($id)) { $cond	= "WHERE treg = '$id' $witel"; } else { $cond	= "where 1=1"; }
			
			$witelUAM	 = $this->witelUAM;
			
			$getData = $this->query->getData('witel','*',"$cond $witelUAM ORDER BY nama_witel ASC");
			
			header('Content-type: application/json; charset=UTF-8');
			$row[] = array('id' => '','text' => '- ALL Witel -' );
			
			foreach($getData as $data) {					
				$row[] = array(
					'id'		=> $data['id_witel'],
					'text'		=> $data['nama_witel'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function getSelect2WitelD(){
		if(checkingsessionpwt()){
			
			$getid 	 	= $_GET['id'];
			@$idsegmen	= $_GET['idsegmen'];
			@$treg		= $_GET['treg'];
			
			if ($getid=='ALL DIVISI') { $id = ''; } else { $id = "and id_divisi = '$getid'"; }
			if ($idsegmen=='ALL SEGMEN') { $ids = ''; } else { $ids = "and id_segmen = '$idsegmen'"; }
			if ($treg=='ALL TREG') { $idt = ''; } else { $idt = "AND treg = '$treg'"; }
			
			$segmenUAM	 = $this->segmenUAM;
			$tregUAM	 = $this->tregUAM;
			$witelUAM	 = $this->witelUAM;
			
			if (isset($id) && !empty($id)) { $cond	= $id; } else { $cond	= ""; }
			if (isset($idsegmen) && !empty($idsegmen)) { $condseg	= $ids; } else { $condseg	= ""; }
			if (isset($treg) && !empty($treg)) { $condtreg	= $idt; } else { $condtreg	= ""; }
			
			$q		 = "
						select id_witel, nama_witel, GROUP_CONCAT(DISTINCT id_divisi) id_divisi, GROUP_CONCAT(DISTINCT id_segmen) id_segmen
						from (
							select * from (
								SELECT a.id_witel, a.nama_witel, a.treg, b.id_divisi, b.id_segmen FROM `witel` a left join map b on a.id_witel=b.id_witel
							) as basic
							where id_witel!='' $witelUAM $tregUAM $segmenUAM $cond $condseg $condtreg
						) as MASTER
						group by id_witel
						order by nama_witel
			";
			
			$getData = $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			
			$row[] = array('id' => 'ALL Witel','text' => 'ALL Witel' );
			
			foreach($getData as $data) {					
				$row[] = array(
					'id'		=> $data['id_witel'],
					'text'		=> $data['nama_witel'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function getSelect2Am(){
		if(checkingsessionpwt()){
			$id 	 = $_GET['id'];
			$nikam	 = $this->amUAM;
			
			if (isset($id) && !empty($id)) { $cond = "WHERE a.id_witel='$id' $nikam"; } else { $cond= ""; }
			
			$getData = $this->query->getData('map a LEFT JOIN am b ON a.nik_am = b.nik_am','a.nik_am,b.nama_am',"$cond GROUP BY a.nik_am,b.nama_am ORDER BY a.nik_am ASC");
			
			header('Content-type: application/json; charset=UTF-8');
			$row[] = array('id' => '','text' => '- Pilih AM -' );
			
			foreach($getData as $data) {					
				$row[] = array(
					'id'		=> $data['nik_am'],
					'text'		=> $data['nik_am']." - ".$data['nama_am'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function getSelect2AmD(){
		if(checkingsessionpwt()){
			
			$getid 	 	= $_GET['id'];
			@$idsegmen	= $_GET['idsegmen'];
			@$treg		= $_GET['treg'];
			@$idwitel	= $_GET['idwitel'];
			
			if ($getid=='ALL DIVISI') { $id = ''; } else { $id = "and id_divisi = '$getid'"; }
			if ($idsegmen=='ALL SEGMEN') { $ids = ''; } else { $ids = "and id_segmen = '$idsegmen'"; }
			if ($treg=='ALL TREG') { $idt = ''; } else { $idt = "AND treg = '$treg'"; }
			if ($idwitel=='ALL Witel') { $idw = ''; } else { $idw = "AND id_witel = '$idwitel'"; }
			
			$segmenUAM	 = $this->segmenUAM;
			$tregUAM	 = $this->tregUAM;
			$witelUAM	 = $this->witelUAM;
			$amUAM	 	 = $this->amUAM;
			
			if (isset($id) && !empty($id)) { $cond	= $id; } else { $cond	= ""; }
			if (isset($idsegmen) && !empty($idsegmen)) { $condseg	= $ids; } else { $condseg	= ""; }
			if (isset($treg) && !empty($treg)) { $condtreg	= $idt; } else { $condtreg	= ""; }
			if (isset($idwitel) && !empty($idwitel)) { $condwitel	= $idw; } else { $condwitel	= ""; }
			
			$q		 = "
						select nik_am, nama_am, GROUP_CONCAT(DISTINCT id_divisi) as id_divisi, GROUP_CONCAT(DISTINCT id_segmen) as id_segmen, GROUP_CONCAT(DISTINCT id_witel) as id_witel
						from (
							select * from (
								select 
								a.nik_am,a.nama_am,b.id_divisi,b.id_segmen,b.id_witel,b.treg
								from am a 
								LEFT JOIN map b ON a.nik_am = b.nik_am where a.raisa=1
							) as basic
							where nik_am!='' $amUAM $witelUAM $tregUAM $segmenUAM $cond $condseg $condtreg $condwitel
						) as MASTER
						GROUP BY nik_am,nama_am 
						ORDER BY nik_am asc
					 ";
			
			$getData = $this->query->getDatabyQ($q);
			
			header('Content-type: application/json; charset=UTF-8');
			$row[] = array('id' => 'ALL AM','text' => 'ALL AM' );
			
			foreach($getData as $data) {					
				$row[] = array(
					'id'		=> $data['nik_am'],
					'text'		=> $data['nik_am']." - ".$data['nama_am'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	//MANAGE LOP
	public function getdatalopv2(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/lop');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid 			= $userdata['userid'];
			$uam				= $this->formula->getUAM($userid);
			
			// echo $uam;
			// exit();
			
			$adjust_divisi = str_replace('id_divisi','c.id_divisi',$uam);
			$adjust_segmen = str_replace('id_segmen','c.id_segmen',$adjust_divisi);
			$adjust_witel = str_replace('id_witel','c.id_witel',$adjust_segmen);
			$adjust_am = str_replace('nik_am','c.nik_am',$adjust_witel);
			$adjust_treg = str_replace('treg','c.treg',$adjust_am);
			$final_adjust = $adjust_treg;
			
			@$divisi = $_GET['divisi'];
			@$segmen = $_GET['segmen'];
			@$treg = $_GET['treg'];
			@$witel = $_GET['witel'];
			@$am = $_GET['am'];
			
			if (fixURL($divisi)=='ALLDIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and c.id_divisi='".$divisi."'";}
			if (fixURL($segmen)=='ALLSEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and c.id_segmen='".$segmen."'";}
			if (fixURL($treg)=='ALLTREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and c.treg='".$treg."'"; }
			if (fixURL($witel)=='ALLWITEL' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and c.id_witel='".$witel."'"; }
			if (fixURL($am)=='ALLAM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and c.nik_am='".getnik($am)."'"; }
			
			$getData = $this->query->getData('`lop` a LEFT JOIN lo b ON a.id_lo=b.id_lo LEFT JOIN map c ON b.id_map = c.id_map LEFT JOIN gc d ON d.nipnas = c.nipnas LEFT JOIN am e ON c.nik_am = e.nik_am LEFT JOIN subs f ON a.subs = f.id_subs LEFT JOIN witel h ON c.id_witel=h.id_witel',' a.id_lo,a.id_lop,a.id_sirup,a.id_lelang,d.nama_gc as k_l_d_i,b.satker_lo,e.nama_am,a.nama_pkt,a.PPN,a.pagu_proj,a.nilai_win,a.kode_raisa,a.status,a.portfolio,f.nama_subs,a.id_pemenang,a.metode,a.tanggal,a.waktu,a.kategori,c.treg,h.nama_witel,a.nomor_kontrak,a.tanggal_kb,(SELECT GROUP_CONCAT(DISTINCT(xx.file))as file_kb FROM file_lop xx WHERE xx.id_lop = a.id_lop)as file_kb,a.ket as note,(SELECT GROUP_CONCAT(xxd.nomor_order)as nomor_order from  `order` xxd WHERE xxd.id_lop=a.id_lop)as nomor_order,a.last_update,(SELECT xxc.name FROM user xxc WHERE xxc.username=a.update_by)as update_by ',"WHERE a.id_lop is not null $wheredivisi $wheresegmen $wheretreg $wherewitel $wheream $final_adjust");
			
			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_lop'];
				
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				if($data['waktu']== 0 OR $data['waktu']==NULL){
					$waktus = "";
				}else{
					$tgl3=date_create($data['waktu']);
					$tgl4 = date_format($tgl3,"M-y");
					$waktus = $tgl4;
				}
				if($data['tanggal']== 0 OR $data['tanggal']==NULL){
					$tanggals = "";
				}else{
					$tgl=date_create($data['tanggal']);
					$tgl2 = date_format($tgl,"d-M-y");
					// $tgl2 = date_format($tgl,"j F Y, g:i a");
					$tanggals = $tgl2;
				}
				
				if($data['file_kb']==''){
					@$button[$no]="";					
				}else{
					$data_file = explode(',',$data['file_kb']);
					for($xxi = 0;$xxi < count($data_file);$xxi++){
						
						@$button[$no] .="<center><a class='btn btn-xs btn-success btnFilePreview' data-toggle='modal' data-target='#FilePreview' data-file='".$data_file[$xxi]."' data-ext='".pathinfo($data_file[$xxi], PATHINFO_EXTENSION)."' data-nomor='".$data_file[$xxi]."' ><i data-toggle='tooltip' title='".$data_file[$xxi]."' class='glyphicon glyphicon-fullscreen'></i>&nbsp; View File</a>&nbsp;<a class='btn btn-xs btn-danger btnDeleteImage' data-toggle='modal' data-target='#deleteImage' alt='Delete File Kontrak' data-id='".$data_file[$xxi]."'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a></center>";																
					}
					 
				}
				$tgl5=date_create($data['last_update']);
				$tgl12 = date_format($tgl5,"d-M-y, g:i a");
				if ($data['last_update']=='' or $data['last_update']=='NULL') {
					$last = '';
				} else {
					$last = $tgl12;
				}
				if($data['tanggal_kb'] == NULL or $data['tanggal_kb'] =="" or $data['tanggal_kb']== 0){
					$last1 = "";										
				}else{
					$last1 = date("d-M-y", strtotime($data['tanggal_kb']));					
				}
				if($data['PPN']==0){
					$ach = $data['nilai_win'];
				}else{
					$ach = ((100/110) * $data['nilai_win']);
				}
				
				$pem[$no] = "";
				$data_pemenang = explode(',',$data['id_pemenang']);
				for($xs = 0;$xs < count($data_pemenang);$xs++){
					$getDataPemenang 	= $this->query->getData('pemenang','*','WHERE id_pemenang="'.$data_pemenang[$xs].'"');
					$arr	 			= array_shift($getDataPemenang);
					if($arr['pemenang']==''){
						$pem[$no]			.= "";
					}else{
						$pem[$no]			.= "<li>".$arr['pemenang']."</li>";						
					}
				}
				$row = array(
					$no,
					$data['id_lo'],
					$data['id_lop'],
					$data['id_sirup'],
					$data['id_lelang'],
					$data['k_l_d_i'],
					$data['satker_lo'],
					$data['nama_am'],
					$data['nama_pkt'],
					number_format((int)$data['pagu_proj'],0),
					number_format((int)$data['nilai_win'],0),
					number_format((int)$ach,0),
					$data['kode_raisa'],
					$data['status'],
					$data['portfolio'],
					$data['nama_subs'],
					$pem[$no],
					$data['metode'],
					$waktus,
					$tanggals,
					$data['kategori'],
					$data['treg'],
					$data['nama_witel'],
					$data['nomor_kontrak'],
					$last1,
					$button[$no],
					$data['note'],
					$data['nomor_order'],
					$last,
					$data['update_by'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$json['data']="";
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function insertlopv2(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			date_default_timezone_set("Asia/Bangkok");
			$url 		= "Manage Lop";
			$activity 	= "INSERT";
			$id_lop 		= trim(strip_tags(stripslashes($this->input->post('id_lop',true))));
			$id_lo			= trim(strip_tags(stripslashes($this->input->post('id_lo',true))));
			$id_sirup		= trim(strip_tags(stripslashes($this->input->post('id_sirup',true))));
			$id_lelang		= trim(strip_tags(stripslashes($this->input->post('id_lelang',true))));
			$nama_paket		= trim(strip_tags(stripslashes($this->input->post('nama_paket',true))));
			$hps			= trim(strip_tags(stripslashes($this->input->post('hps',true))));
			$nilai_win		= trim(strip_tags(stripslashes($this->input->post('nilai_win',true))));
			$kode_raisa		= trim(strip_tags(stripslashes($this->input->post('kode_raisa',true))));
			$status			= trim(strip_tags(stripslashes($this->input->post('status',true))));
			$portfolio		= trim(strip_tags(stripslashes($this->input->post('portfolio',true))));
			$id_subs		= trim(strip_tags(stripslashes($this->input->post('id_subs',true))));
			$pemenang		= trim(strip_tags(stripslashes($this->input->post('pemenang',true))));
			$metode			= trim(strip_tags(stripslashes($this->input->post('metode',true))));
			$getwaktu		= trim(strip_tags(stripslashes($this->input->post('waktu',true))));
			$waktu			= $getwaktu."-01 00:00:00";
			$gettanggal		= trim(strip_tags(stripslashes($this->input->post('tanggal',true))));
			$tanggal		= $gettanggal." 00:00:00";
			$kategori		= trim(strip_tags(stripslashes($this->input->post('kategori',true))));
			$nomor_kontrak	= trim(strip_tags(stripslashes($this->input->post('nomor_kontrak',true))));
			$gettanggal_kb	= trim(strip_tags(stripslashes($this->input->post('tanggal_kb',true))));
			$tanggal_kb		= $gettanggal_kb." 00:00:00";
			$keterangan		= trim(strip_tags(stripslashes($this->input->post('keterangan',true))));
			$ppn			= trim(strip_tags(stripslashes($this->input->post('ppn',true))));
			$sustain_dari	= trim(strip_tags(stripslashes($this->input->post('sustain_dari_data',true))));
			//$nama_pm		= trim(strip_tags(stripslashes($this->input->post('nama_pm',true))));
			$status_raisa	= trim(strip_tags(stripslashes($this->input->post('status_raisa',true))));
			// $telephone		= trim(strip_tags(stripslashes($this->input->post('telephone',true))));
			
			if ($kode_raisa=='R2' and $status=='WIN' and $nilai_win<1000 or $kode_raisa=='R2' and $status=='NEW-GTMA' and $nilai_win<1000) {
				echo "";
			} else {
				$sess	 	= $this->session->userdata('sesspwt');
				$userid 	= $sess['userid'];
				
				$file	 = $_FILES;
				$waktus = fix_date($waktu);
				$tanggals = fix_date($tanggal);
				$tanggals_kb = fix_date($tanggal_kb);
				if($ppn!='' OR $ppn!=0){$ppn=1;}else{$ppn=0;}
				$rows	 = $this->query->insertData('lop', "id_lop,id_lo,id_sirup,id_lelang,nama_pkt,pagu_proj,nilai_win,metode,waktu,tanggal,kategori,status,kode_raisa,portfolio,subs,ket,last_update,nomor_kontrak,tanggal_kb,ppn,id_pemenang,update_by,sustain_dari,id_sr", "'','$id_lo','$id_sirup','$id_lelang','$nama_paket','$hps','$nilai_win','$metode','$waktus','$tanggals','$kategori','$status','$kode_raisa','$portfolio','$id_subs','$keterangan','".date('Y-m-d h:i:s')."','$nomor_kontrak','$tanggals_kb','$ppn','$pemenang','$userid','$sustain_dari','$status_raisa'");
				$id		 = $this->db->insert_id();
				$id_new_lop = $this->db->insert_id();
				
				
				//get_treg_and_witel
				$treg_witel = $this->query->getData('lo a LEFT JOIN map b ON a.id_map=b.id_map LEFT JOIN witel c ON b.id_witel=c.id_witel LEFT JOIN segmen d ON b.id_segmen = d.id_segmen','a.id_lo,b.treg,b.nipnas,c.nama_witel,d.nama_segmen','WHERE a.id_lo = "'.$id_lo.'"');
				$data_tregwitel = array_shift($treg_witel);
				$n=0;
				//config file
				$config['upload_path'] = './files/kontrak/';
				$config['allowed_types'] = 'pdf';			
				$files = $_FILES;
				$cpt = count($_FILES['pict']['name']);
				for($i=0; $i<$cpt; $i++)
				{         
					$n++;
					$namawitelreplace	= str_replace('&','',$data_tregwitel['nama_witel']);
					$fileName	= $data_tregwitel['nama_segmen']."_".str_relplace(' ','_',$data_tregwitel['treg'])."_".str_replace(' ','_',$namawitelreplace)."_".$data_tregwitel['nipnas']."_".$id_lop."_".$n.".pdf";
					
					$_FILES['pict']['name']= $files['pict']['name'][$i];
					$_FILES['pict']['type']= $files['pict']['type'][$i];
					$_FILES['pict']['tmp_name']= $files['pict']['tmp_name'][$i];
					$_FILES['pict']['error']= $files['pict']['error'][$i];
					$_FILES['pict']['size']= $files['pict']['size'][$i];    
					$config['file_name'] = $fileName;

					$this->upload->initialize($config);
					$upl = $this->upload->do_upload('pict');
					if($upl){ 
						$insert_image = $this->query->insertData('file_lop','id_file,id_lop,file','"","'.$id_new_lop.'","'.$fileName.'"');
					}
				}
				
		
				if($rows) {
					$log = $this->query->insertlog($activity,$url,$id_new_lop);
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
			}
		} else {
			redirect('/panel');
		}
	}
	public function deletelopv2(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$this->load->model('query');
			
			$cond		= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$url 		= "Manage Lop";
			$activity 	= "DELETE";
			$rows 		= $this->query->deleteData('lop','id_lop',$cond);
			
			if(isset($rows)) {
				//delete files
				$getdatafile = $this->query->getData('file_lop','*','WHERE id_lop = "'.$cond.'"');
				foreach($getdatafile as $datafile){
					unlink('files/kontrak/'.$datafile['file']);
				}
				$this->query->deleteData('file_lop','id_lop',$cond);
				
				$log = $this->query->insertlog($activity,$url,$cond);
				print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
	}
	public function getpagulop(){
		if(checkingsessionpwt()){
			$id	 = trim(strip_tags(stripslashes($this->input->post('id',true))));
			$getdatapagu = $this->query->getData('`lo`','nilai_pagu',"WHERE id_lo ='".$id."'");
			header('Content-type: application/json; charset=UTF-8');
			$json="";
			if (isset($id) && !empty($id)) {
				foreach($getdatapagu as $data) {
					$row = array(
						'nilai_pagu'			=> $data['nilai_pagu']
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
	public function modallopv2(){
		$background		= '';
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			//$dataCat			= $this->query->getData('lop','*',"WHERE id_lop='".$id."'");
			$getdatalop = $this->query->getData('`lop` a LEFT JOIN lo b ON a.id_lo=b.id_lo LEFT JOIN map c ON b.id_map = c.id_map LEFT JOIN gc d ON d.nipnas = c.nipnas LEFT JOIN am e ON c.nik_am = e.nik_am LEFT JOIN subs f ON a.subs = f.id_subs LEFT JOIN pemenang g ON a.id_pemenang = g.id_pemenang LEFT JOIN witel h ON c.id_witel=h.id_witel',' a.id_lo,a.id_lop,a.id_sirup,a.subs,a.id_pemenang,a.id_lelang,d.nama_gc as k_l_d_i,b.satker_lo,e.nama_am,a.nama_pkt,a.PPN,a.pagu_proj as hps,a.nilai_win,a.kode_raisa,a.status,a.portfolio,a.sustain_dari,a.id_pm,a.id_sr,(select nama_pm from pm_lop where id_pm=a.id_pm) as nama_pm,(select phone from pm_lop where id_pm=a.id_pm) as phone,f.nama_subs,g.pemenang,a.metode,a.tanggal,a.waktu,a.kategori,c.treg,h.nama_witel,a.nomor_kontrak,a.tanggal_kb,(SELECT GROUP_CONCAT(DISTINCT(xx.file))as file_kb FROM file_lop xx WHERE xx.id_lop = a.id_lop)as file_kb,a.ket as note,(SELECT GROUP_CONCAT(xxd.nomor_order)as nomor_order from  `order` xxd WHERE xxd.id_lop=a.id_lop)as nomor_order,a.last_update,(SELECT xxc.name FROM user xxc WHERE xxc.username=a.update_by)as update_by ',"WHERE a.id_lop ='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getdatalop as $data) {
					$sustaindat = str_replace(' ', '', $data['sustain_dari']);
					$row = array(
						'id_lo'			=> $data['id_lo'],
						'id_lop'		=> $data['id_lop'],
						'id_sirup'		=> $data['id_sirup'],
						'id_lelang'		=> $data['id_lelang'],
						'nama_pkt'		=> $data['nama_pkt'],
						'hps'			=> $data['hps'],
						'nilai_win'		=> $data['nilai_win'],
						'ppn'			=> $data['PPN'],
						'kode_raisa'	=> $data['kode_raisa'],
						'status'		=> $data['status'],
						'portfolio'		=> $data['portfolio'],
						'subs'			=> $data['subs'],
						'pemenang'		=> $data['id_pemenang'],
						'metode'		=> $data['metode'],
						'waktu'			=> substr($data['waktu'],0,7),
						'tanggal'		=> substr($data['tanggal'],0,10),
						'kategori'		=> $data['kategori'],
						'ket'			=> $data['note'],
						'last_update'	=> $data['last_update'],
						'nomor_kontrak' => $data['nomor_kontrak'],
						'tanggal_kb'	=> substr($data['tanggal_kb'],0,10),
						'file_kb'		=> $data['file_kb'],
						'sustain_dari'	=> $sustaindat,
						'nama_pm'		=> $data['nama_pm'],
						'phone'			=> $data['phone'],
						'id_sr'			=> $data['id_sr']
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
	public function modalfilev2(){
		$background		= '';
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$getdatafile		= $this->query->getData('file_lop','*',"WHERE file LIKE '%".$id."%'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getdatafile as $data) {
					$row = array(
						'id_file'		=> $data['id_file'],
						'id_lop'		=> $data['id_lop'],
						'file'			=> $data['file']
					);
					$json = $row;
				}
				echo json_encode($json);
			}
		} else {
			redirect('/panel');
		}
	}
	public function deletefilev2(){
		if(checkingsessionpwt()){
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$file	= trim(strip_tags(stripslashes($this->input->post('file',true))));
			$url 		= "Manage Lop";
			$activity 	= "DELETE";
			$getData	= $this->query->getDatabyQ("SELECT * FROM file_lop WHERE id_file ='".$cond."'");
			foreach($getData as $data_files) { 
				$sess	 	= $this->session->userdata('sesspwt');
				$userid 	= $sess['userid'];
				$update_by_query = $this->query->updateData('lop',"last_update='".date('Y-m-d h:i:s')."',update_by='$userid'","WHERE id_lop='".$data_files['id_lop']."'");
			}
			$rows 		= $this->query->deleteData('file_lop','id_file',$cond);
			
			if($rows) {
				$log = $this->query->insertlog($activity,$url,$cond);
				//delete eksisting
				$dataexis = 'files/kontrak/'.$file;
				unlink($dataexis);
				print json_encode(array('success'=>true,'total'=>1));
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}
	public function updatelopv2(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$id_lop 		= trim(strip_tags(stripslashes($this->input->post('ed_id_lop',true))));
			$id_lo			= trim(strip_tags(stripslashes($this->input->post('ed_id_lo',true))));
			$id_sirup		= trim(strip_tags(stripslashes($this->input->post('ed_id_sirup',true))));
			$id_lelang		= trim(strip_tags(stripslashes($this->input->post('ed_id_lelang',true))));
			$nama_paket		= trim(strip_tags(stripslashes($this->input->post('ed_nama_paket',true))));
			$hps			= trim(strip_tags(stripslashes($this->input->post('ed_hps',true))));
			$nilai_win		= trim(strip_tags(stripslashes($this->input->post('ed_nilai_win',true))));
			$kode_raisadef	= trim(strip_tags(stripslashes($this->input->post('ed_kode_raisadef',true))));
			$kode_raisa		= trim(strip_tags(stripslashes($this->input->post('ed_kode_raisa',true))));
			$status			= trim(strip_tags(stripslashes($this->input->post('ed_status',true))));
			$portofolio		= trim(strip_tags(stripslashes($this->input->post('ed_portofolio',true))));
			$id_subs		= trim(strip_tags(stripslashes($this->input->post('ed_id_subs',true))));
			$pemenang		= trim(strip_tags(stripslashes($this->input->post('ed_pemenang',true))));
			$metode			= trim(strip_tags(stripslashes($this->input->post('ed_metode',true))));
			$getwaktu		= trim(strip_tags(stripslashes($this->input->post('ed_waktu',true))));
			$waktus			= $getwaktu."-01 00:00:00";
			$gettanggal		= trim(strip_tags(stripslashes($this->input->post('ed_tanggal',true))));
			$tanggals		= $gettanggal." 00:00:00";
			$kategori		= trim(strip_tags(stripslashes($this->input->post('ed_kategori',true))));
			$nomor_kontrak 	= trim(strip_tags(stripslashes($this->input->post('ed_nomor_kontrak',true))));
			$gettanggal_kb	= trim(strip_tags(stripslashes($this->input->post('ed_tanggal_kb',true))));
			$tanggal_kbs	= $gettanggal_kb." 00:00:00";
			$keterangan		= trim(strip_tags(stripslashes($this->input->post('ed_keterangan',true))));
			$ppn			= trim(strip_tags(stripslashes($this->input->post('ed_ppn',true))));
			$sustain_dari	= trim(strip_tags(stripslashes($this->input->post('ed_sustain_dari_data',true))));
			//$nama_pm		= trim(strip_tags(stripslashes($this->input->post('ed_nama_pm',true))));
			// $telephone		= trim(strip_tags(stripslashes($this->input->post('ed_telephone',true))));
			$exist			= trim(strip_tags(stripslashes($this->input->post('existing',true))));
			$status_raisa	= trim(strip_tags(stripslashes($this->input->post('ed_status_raisa',true))));
			
			
			if ($kode_raisa=='R2' and $status=='WIN' and $nilai_win<1000 or $kode_raisa=='R2' and $status=='NEW-GTMA' and $nilai_win<1000) {
				echo "";
			} else {
				if($waktus == NULL or $waktus == '0000-00-00 00:00:00' or $waktus == ''){
					$waktusx = 'NULL';
				}else{
					$waktusx = $waktus;
				}
				
				if($tanggals == NULL or $tanggals == '0000-00-00 00:00:00' or $tanggals == ''){
					$tanggalsx = 'NULL';
				}else{
					$tanggalsx = $tanggals;
				}
				
				if($tanggal_kbs == NULL or $tanggal_kbs == '0000-00-00 00:00:00' or $tanggal_kbs ==''){
					$tanggals_kbx = 'NULL';
				}else{
					$tanggals_kbx = $tanggal_kbs;
				}
				$file	 = $_FILES;
				// $waktusx = fix_date($waktu);
				// $tanggalsx = fix_date($tanggal);
				// $tanggals_kbx = fix_date($tanggal_kb);
				if($ppn!='' OR $ppn!=0){$ppn=1;}else{$ppn=0;}
				$sess	 	= $this->session->userdata('sesspwt');
				$userid 	= $sess['userid'];
				$rows = $this->query->updateData('lop',"
				id_lo='$id_lo', 
				id_sirup='$id_sirup',
				id_lelang='$id_lelang', 
				nama_pkt='$nama_paket', 
				pagu_proj='$hps', 
				nilai_win='$nilai_win', 
				metode='$metode', 
				waktu='$waktusx', 
				tanggal='$tanggalsx', 
				kategori='$kategori', 
				status='$status', 
				kode_raisa='$kode_raisa', 
				portfolio='$portofolio', 
				subs='$id_subs', 
				ket='$keterangan', 
				last_update='".date('Y-m-d h:i:s')."', 
				update_by='$userid',
				nomor_kontrak='$nomor_kontrak',
				id_pemenang='$pemenang',
				PPN='$ppn',
				tanggal_kb='$tanggals_kbx',
				sustain_dari ='$sustain_dari',
				id_sr='$status_raisa'
				","WHERE id_lop='$id_lop'");
				
				//get_treg_and_witel
				$treg_witel = $this->query->getData('lo a LEFT JOIN map b ON a.id_map=b.id_map LEFT JOIN witel c ON b.id_witel=c.id_witel LEFT JOIN segmen d ON b.id_segmen = d.id_segmen','a.id_lo,b.treg,b.nipnas,c.nama_witel,d.nama_segmen','WHERE a.id_lo = "'.$id_lo.'"');
				$data_tregwitel = array_shift($treg_witel);
				//jumlah file exist
				$existfile = $this->query->getData('file_lop','count(id_lop)as jml','WHERE id_lop = "'.$id_lop.'"');
				$data_jml = array_shift($existfile);
				$n=$data_jml['jml']+1;
				//config file
				$config['upload_path'] = './files/kontrak/';
				$config['allowed_types'] = 'pdf';			
				$files = $_FILES;
				$cpt = count($_FILES['ed_pict']['name']);
				for($i=0; $i<$cpt; $i++)
				{         
					$namawitelreplace	= str_replace('&','',$data_tregwitel['nama_witel']);
					$fileNames	= $data_tregwitel['nama_segmen']."_".$data_tregwitel['treg']."_".str_replace(' ','_',$namawitelreplace)."_".$data_tregwitel['nipnas']."_".$id_lop."_".$n.".pdf";
					
					$_FILES['ed_pict']['name']= $files['ed_pict']['name'][$i];
					$_FILES['ed_pict']['type']= $files['ed_pict']['type'][$i];
					$_FILES['ed_pict']['tmp_name']= $files['ed_pict']['tmp_name'][$i];
					$_FILES['ed_pict']['error']= $files['ed_pict']['error'][$i];
					$_FILES['ed_pict']['size']= $files['ed_pict']['size'][$i];    
					$config['file_name'] = $fileNames;

					$this->upload->initialize($config);
					$this->upload->do_upload('ed_pict');
					if($_FILES['ed_pict']['name']!=''){
						$insert_image = $this->query->insertData('file_lop','id_file,id_lop,file','"","'.$id_lop.'","'.$fileNames.'"');					
					}else{
						
					}
					$n++;
				}
				
				$url 		= "Manage Lop";
				if ($kode_raisadef=='R2') { $activity 	= "UPDATE R2"; } else { $activity 	= "UPDATE"; }
				if($rows) {
					$log = $this->query->insertlog($activity,$url,$id_lop);
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
			}
		} else {
			redirect('/panel');
		}
	}
	public function refreshPemenang(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			
			$getData = $this->query->getData('pemenang','*',"ORDER BY id_pemenang DESC");
			
			header('Content-type: application/json; charset=UTF-8');
			$row[] = array('id' => '','text' => '- Pilih Pemenang -' );
			
			foreach($getData as $data) {				
				$row[] = array(
					'id'		=> $data['id_pemenang'],
					'text'		=> $data['pemenang'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = "";
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function refreshGC(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			
			$getData = $this->query->getData('gc','*',"ORDER BY nipnas DESC");
			
			header('Content-type: application/json; charset=UTF-8');
			$row[] = array('id' => '','text' => '- PILIH NIPNAS -' );
			
			foreach($getData as $data) {				
				$row[] = array(
					'id'		=> $data['nipnas'],
					'text'		=> $data['nipnas']." - ".$data['nama_gc'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = "";
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function refreshWITEL(){
		if(checkingsessionpwt()){
			date_default_timezone_set("Asia/Bangkok");
			
			
			$getData = $this->query->getData('witel','*',"ORDER BY id_witel DESC");
			
			header('Content-type: application/json; charset=UTF-8');
			$row[] = array('id' => '','text' => '- PILIH WITEL -' );
			
			foreach($getData as $data) {				
				$row[] = array(
					'id'		=> $data['id_witel'],
					'text'		=> $data['nama_witel'],
					);
				$json = $row;
				
			}
			if(!isset($json)){
				$json = "";
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function cekNIKAM(){
		if(checkingsessionpwt()){
			$getnikam	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			if ($getnikam=='') {
				$valnik		= "";
			} else {
				$valnik		= "WHERE nik_am ='".$getnikam."'";
			}
			
			$datAM		= $this->query->getData('am','nik_am',"$valnik");
			$exec		= array_shift($datAM);
			$nik		= $exec['nik_am'];
			if ($nik!='') {
				echo '';
			} else {
				echo 'sukses';
			}
		} else {
			redirect('/panel');
		}
	}
	
	public function getdataproduktivitasM(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/am');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			
			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid 			= $userdata['userid'];
			$getAksesUAM		= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
			$dataAksesUAM		= array_shift($getAksesUAM);
			$amUAM 				= str_replace(",","','",$dataAksesUAM['akses_am']);
			if ($amUAM=='all') {
				$valamUAM			= "";
			} else {
				$valamUAM			= "where nik_am in ('$amUAM')";
			}
			
			$uam				= $this->formula->getUAM($userid);
			
			$query				= "
								select basic.*, (select nama_segmen from segmen where id_segmen=basic.id_segmen) as segmen, 
   								(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu='Manage Productivity AM' AND xa.data = basic.ID ORDER BY xa.date_time DESC limit 1)as UPDATE_BY,
								(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu='Manage Productivity AM' AND xa.data = basic.ID ORDER BY xa.date_time DESC limit 1)as LAST_UPDATE 
								from (
								SELECT a.*,b.nama_am,c.userid,c.picture,d.akses_divisi,
								SUBSTRING(d.akses_segmen, 1, 1) as akses_segmen,
								d.akses_treg,d.akses_witel,d.akses_am,
								(select sum(DISTINCT target_rev) from map where nik_am=a.nik) as target
								FROM `productivity_am` a
								left join am b
								on a.nik=b.nik_am
								left join user c
								on a.nik=c.username
								left join user_organization d
								on c.userid=d.userid
								ORDER BY ID desc
								)".$uam." as basic 
								";
			$getData			= $this->query->getDatabyQ($query);

			$no=0;
			foreach($getData as $data) { 
				$no++;
				$id 		  = $data['ID'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				
				$row = array(
					"",
					$data['NIK'],
					$data['nama_am'],
					$data['segmen'],
					$data['DCG'],
					$data['RSS'],
					$data['SUMBU_DCG'],
					$data['SUMBU_RSS'],
					$data['PERIODE'],
					$data['KUADRAN'],
					$data['UPDATE_BY'],
					$data['LAST_UPDATE'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	
	public function getdatapots(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/am');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			
			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid 				= $userdata['userid'];
			//$getAksesUAM			= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
			//$dataAksesUAM			= array_shift($getAksesUAM);
			//$amUAM 				= str_replace(",","','",$dataAksesUAM['akses_am']);
			//if ($amUAM=='all') {
			//	$valamUAM			= "";
			//} else {
			//	$valamUAM			= "where nik_am in ('$amUAM')";
			//}
			//
			$uam				= $this->formula->getUAMPOTS($userid);
			
			$query				= "
								select * from (
									SELECT s.nama_segmen,g.nama_gc,w.nama_witel,p.* FROM pots p LEFT JOIN segmen s ON p.id_segmen = s.id_segmen LEFT JOIN gc g ON g.nipnas = p.nipnas LEFT JOIN witel w ON w.id_witel = p.id_witel
								)as basic  where 1=1 ".$uam." 
								";
			
			$getData			= $this->query->getDatabyQ($query);

			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id 		  = $data['id'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				
				$row = array(
					"",
					$data['nama_segmen'],
					$data['nipnas'],
					$data['nama_gc'],
					$data['treg'],
					$data['nama_witel'],
					$data['periode'],
					$this->formula->rupiah3($data['nilai_pots']),
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$json['data'] = "";
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function getdatapots_rev(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/am');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			
			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid 				= $userdata['userid'];
			//$getAksesUAM			= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
			//$dataAksesUAM			= array_shift($getAksesUAM);
			//$amUAM 				= str_replace(",","','",$dataAksesUAM['akses_am']);
			//if ($amUAM=='all') {
			//	$valamUAM			= "";
			//} else {
			//	$valamUAM			= "where nik_am in ('$amUAM')";
			//}
			//
			$uam				= $this->formula->getUAMPOTS($userid);
			
			$query				= "
								select * from (
									SELECT a.*,c.nipnas,c.nik_am,c.id_segmen,cc.nama_gc,c.treg,c.id_witel,s.nama_segmen,w.nama_witel,dv.nama_divisi,nam.nama_am,
									(SELECT DATE_FORMAT(date_time, '%d-%b-%y %H:%i:%s')  FROM data_log WHERE data = a.id AND menu='Manage POTS' GROUP BY date_time ORDER BY date_time DESC limit 1)as last_update,
									(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu='Manage POTS' AND xa.data = a.id ORDER BY xa.date_time DESC limit 1)as update_by
									FROM `pots` a LEFT JOIN map c ON a.id_map = c.id_map LEFT JOIN segmen s ON c.id_segmen = s.id_segmen LEFT JOIN witel w ON w.id_witel = c.id_witel LEFT JOIN divisi dv ON dv.id_divisi = c.id_divisi LEFT JOIN am nam ON nam.nik_am = c.nik_am LEFT JOIN gc cc ON cc.nipnas = c.nipnas
								)as basic  where 1=1 ".$uam." 
								";
			//echo $query;
			$getData			= $this->query->getDatabyQ($query);

			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id 		  = $data['id'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				
				$row = array(
					"",
					$data['id_map'],
					$data['nama_segmen'],
					$data['nipnas'],
					$data['nama_gc'],
					$data['treg'],
					$data['nama_witel'],
					$data['nik_am'].' - '.$data['nama_am'],
					$data['periode'],
					$this->formula->rupiah3($data['nilai_pots']),
					$data['update_by'],
					$data['last_update'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$json['data'] = "";
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function getdatakuadran($kuad,$treg,$witel,$periode,$am){
		if(checkingsessionpwt()){
			$data['kuad']		= $kuad;
			$data['treg']		= $treg;
			$data['witel']		= $witel;
			$data['periode']	= $periode;
			$data['am']			= $am;
			
			$this->load->view('panel/produktivitas/detailperkuadran',$data);
		} else {
			redirect('/panel');
		}
	}
	
	public function getdataproduktivitasMOVV(){
		if(checkingsessionpwt()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/am');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			
			$userdata 			= $this->session->userdata('sesspwt'); 
			$userid 			= $userdata['userid'];
			$getAksesUAM		= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
			$dataAksesUAM		= array_shift($getAksesUAM);
			$amUAM 				= str_replace(",","','",$dataAksesUAM['akses_am']);
			if ($amUAM=='all') {
				$valamUAM			= "";
			} else {
				$valamUAM			= "where nik_am in ('$amUAM')";
			}
			
			$uam				= $this->formula->getUAM($userid);
			
			$segmenUAM 			= str_replace(",","','",$dataAksesUAM['akses_segmen']);
			if ($dataAksesUAM['akses_segmen']=='all')   { $UAMSEGMEN = ''; } else { $UAMSEGMEN = "and id_segmen in ('$segmenUAM')"; }
			
			// kondisi uam "and EXISTS (select id_segmen from segmen where id_segmen=productivity_rss_dcg.id_segmen $UAMSEGMEN)"
			
			$query				= "
								SELECT 
								case 
									when id_segmen=1 then 1
									when id_segmen=2 then 3
									when id_segmen=3 then 4
									when id_segmen=4 then 2
									else 0
								end as urutan,
								(select nama_segmen from segmen where id_segmen=productivity_rss_dcg.id_segmen) as nama_segmen,
								productivity_rss_dcg.* FROM productivity_rss_dcg where 1=1
								order by periode desc
								";
			$getData			= $this->query->getDatabyQ($query);

			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id 		  = $data['id_prd'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				
				if ($data['id_segmen']==0) {
					$namasegmen = 'DGS';
				} else {
					$namasegmen = $data['nama_segmen'];
				}
				
				$row = array(
					"",
					$this->formula->TanggalIndoMY($data['periode']),
					$namasegmen,
					$data['dcg'],
					$data['rss'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function insertoneproduktivitasOVV(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$qDS	= $this->query->getData('segmen',
											"
											case 
												when id_segmen=1 then 1
												when id_segmen=2 then 3
												when id_segmen=3 then 4
												when id_segmen=4 then 2
												else 0
											end as urutan,
											segmen.*
											"
											,"WHERE id_divisi='1' order by urutan");
			$periode0		= trim(strip_tags(stripslashes($this->input->post('periode',true))));
			$dcg0 			= trim(strip_tags(stripslashes($this->input->post('dcg0',true))));
			$rss0 			= trim(strip_tags(stripslashes($this->input->post('rss0',true))));
			$rowsdgs		= $this->query->insertData('productivity_rss_dcg', "id_prd,periode,id_segmen,rss,dcg", "'','$periode0','0','$rss0','$dcg0'");
											
			foreach ($qDS as $dataDS) {
				$ids			= $dataDS['id_segmen'];
				
				$periode		= trim(strip_tags(stripslashes($this->input->post('periode',true))));
				$dcg 			= trim(strip_tags(stripslashes($this->input->post('dcg'.$ids,true))));
				$rss 			= trim(strip_tags(stripslashes($this->input->post('rss'.$ids,true))));
				$segmen			= trim(strip_tags(stripslashes($this->input->post('segmennya'.$ids,true))));
				
				// echo $periode."----".$dcg."----".$rss."----".$segmen."<br>";
				$rows = $this->query->insertData('productivity_rss_dcg', "id_prd,periode,id_segmen,rss,dcg", "'','$periode','$segmen','$rss','$dcg'");
				$id			= $this->db->insert_id();
				$url 		= "Manage RST dan DCG";
				$activity 	= "INSERT";
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
	
	public function modalproduktivitasOVV(){
		if(checkingsessionpwt()){
			$id	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataproduktivitas = $this->query->getData('productivity_rss_dcg','productivity_rss_dcg.*, (select nama_segmen from segmen where id_segmen=productivity_rss_dcg.id_segmen) as nama_segmen',"WHERE id_prd='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataproduktivitas as $data) {
					
					if ($data['id_segmen']==0) {
						$namasegmen = 'DGS';
					} else {
						$namasegmen = $data['nama_segmen'];
					}
					
					$row = array(
						'id_prd'		=> $data['id_prd'],
						'periode'		=> $data['periode'],
						'id_segmen'		=> $data['id_segmen'],
						'title'			=> $data['periode']." - ".$data['nama_segmen'],
						'nama_segmen'	=> $namasegmen,
						'rss'			=> $data['rss'],
						'dcg'			=> $data['dcg']
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
	
	public function deleteproduktivitasOVV(){
		if(checkingsessionpwt()){
			$this->load->model('query');
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$url 		= "Manage RSS dan DCG";
			$activity 	= "DELETE";
			$rows = $this->query->deleteData('productivity_rss_dcg','id_prd',$cond);
			
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
	
	public function updateproduktivitasOVV(){
		if(checkingsessionpwt()){
			$userdata		= $this->session->userdata('sesspwt'); 
				
			$id 			= trim(strip_tags(stripslashes($this->input->post('ed_idprd',true))));
			$dcg 			= trim(strip_tags(stripslashes($this->input->post('ed_dcg',true))));
			$rss 			= trim(strip_tags(stripslashes($this->input->post('ed_rss',true))));
			$segmen			= trim(strip_tags(stripslashes($this->input->post('ed_segmennya',true))));
			$periode		= trim(strip_tags(stripslashes($this->input->post('ed_periode',true))));
			
			$rows 			= $this->query->updateData('productivity_rss_dcg',"periode='$periode', dcg='$dcg',rss='$rss'","WHERE id_prd='$id'");
			$url 			= "Manage RST dan DCG";
			$activity 		= "UPDATE";
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
	
	public function updateproduktivitassumbu(){
		if(checkingsessionpwt()){
			$userdata		= $this->session->userdata('sesspwt'); 
				
			$sumbu_dcg 	= trim(strip_tags(stripslashes($this->input->post('edsum_sumbu_dcg',true))));;
			$sumbu_rss		= trim(strip_tags(stripslashes($this->input->post('edsum_sumbu_rss',true))));
			$periode		= trim(strip_tags(stripslashes($this->input->post('edsum_periode',true))));
			
			$rows 			= $this->query->updateData('productivity_am',"SUMBU_DCG='$sumbu_dcg',SUMBU_RSS='$sumbu_rss'","WHERE PERIODE='$periode'");
			$url 			= "Manage Productivity AM";
			$activity 		= "UPDATE SUMBU";
			
			$id				= $periode;
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
	
	public function importpots_rev(){
		error_reporting(0);
		
		$convertName				= time().$_FILES['file']['name'];
		$fileName 					= str_replace(' ','_',$convertName);
        $config['upload_path'] 		= './files/pots/'; //buat folder dengan nama assets di root folder
        $config['file_name'] 		= $fileName;
        $config['allowed_types'] 	= 'xls|xlsx|csv';
        $config['max_size'] = 10000;
         
        $this->load->library('upload');
        $this->upload->initialize($config);
         
        if(! $this->upload->do_upload('file') )
        $this->upload->display_errors();
             
        $media			 	= $this->upload->data('file');
        $inputFileName 		= './files/pots/'.$fileName;
        // $inputFileName 		= './files/'.$media['file_name'];
        // $inputFileName = './assets/file.xls';
         
        try {
                $inputFileType 	= IOFactory::identify($inputFileName);
                $objReader 		= IOFactory::createReader($inputFileType);
                $objPHPExcel 	= $objReader->load($inputFileName);
		} catch(Exception $e) {
				//die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
				echo "<script>
						alert('Terjadi Kesalahan , File yang di ijinkan csv | xls | xlxs !');
						window.location.href = '".base_url()."page/excel';
					  </script>";
		}
 
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            
			// echo $highestRow;
			
            for ($row = 2; $row <= $highestRow; $row++) {
				//  Read a row of data into an array                 
               $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE) ;
				
				// $start_date = date('d-M-Y', PHPExcel_Shared_Date::ExcelToPHP($rowData[0][9])); 
				
				$idmap 			= trim($rowData[0][0]);
				$periode 		= trim($rowData[0][1]);
				$nilaipots	 	= trim($rowData[0][2]);
				$dateinput		= date('Y-m-d H:i:s');
				
				
				$rows 	= $this->query->insertData('pots', "id,id_map,periode,nilai_pots,dateinput", "'','$idmap','$periode','$nilaipots','$dateinput'");
				
            }
        echo var_dump($rows);
		// delete_files($media['file_path']);
		$url 		= "Manage POTS";
		$activity 	= "IMPORT";
		
		$log = $this->query->insertlog($activity,$url,'');
		// print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
        echo "done";
    }
	
	public function importpots(){
		error_reporting(0);
		
		$convertName				= time().$_FILES['file']['name'];
		$fileName 					= str_replace(' ','_',$convertName);
        $config['upload_path'] 		= './files/pots/'; //buat folder dengan nama assets di root folder
        $config['file_name'] 		= $fileName;
        $config['allowed_types'] 	= 'xls|xlsx|csv';
        $config['max_size'] = 10000;
         
        $this->load->library('upload');
        $this->upload->initialize($config);
         
        if(! $this->upload->do_upload('file') )
        $this->upload->display_errors();
             
        $media			 	= $this->upload->data('file');
        $inputFileName 		= './files/pots/'.$fileName;
        // $inputFileName 		= './files/'.$media['file_name'];
        // $inputFileName = './assets/file.xls';
         
        try {
                $inputFileType 	= IOFactory::identify($inputFileName);
                $objReader 		= IOFactory::createReader($inputFileType);
                $objPHPExcel 	= $objReader->load($inputFileName);
		} catch(Exception $e) {
				//die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
				echo "<script>
						alert('Terjadi Kesalahan , File yang di ijinkan csv | xls | xlxs !');
						window.location.href = '".base_url()."page/excel';
					  </script>";
		}
 
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            
			// echo $highestRow;
			
            for ($row = 2; $row <= $highestRow; $row++) {
				//  Read a row of data into an array                 
               $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE) ;
				
				// $start_date = date('d-M-Y', PHPExcel_Shared_Date::ExcelToPHP($rowData[0][9])); 
				
				$getsegmen 		= trim($rowData[0][0]);
				$nipnas 		= trim($rowData[0][1]);
				$namenipnas 	= trim($rowData[0][2]);
				$treg 			= trim($rowData[0][3]);
				$getwitel		= trim($rowData[0][4]);
				$periode		= trim($rowData[0][5]);
				$nilaipots		= trim($rowData[0][6]);
				$dateinput		= date('Y-m-d H:i:s');
				
				$qIDSegmen		= $this->query->getData('segmen','id_segmen',"WHERE upper(nama_segmen)=upper('$getsegmen')");
				$getIDSegmen	= array_shift($qIDSegmen);
				$idsegmen		= $getIDSegmen['id_segmen'];
				
				// $cekWitel		= $this->query->getNumRows('witel','*',"WHERE upper(nama_witel)=upper('$getwitel')")->num_rows();
				
				// if ($cekWitel<1) {
					// $getMaxWID	= $this->db->query("SELECT max(id_witel)as max_id_witel FROM witel")->result_array();
					// $maxWID		= array_shift($getMaxWID);
					// $idwitel	= $maxWID['max_id_witel']+1;
					
					// INSERT WITEL BARU
					// $insertW	= $this->query->insertData('witel', "id_witel,nama_witel,treg", "'$idwitel','$getwitel','$treg'");
				// } else {
					// $qIDWitel		= $this->query->getData('witel','id_witel',"WHERE upper(nama_witel)=upper('$getwitel')");
					// $getIDWitel		= array_shift($qIDWitel);
					// $idwitel		= $getIDWitel['id_witel'];
				// }
				
				// $cekNipnas		= $this->query->getNumRows('gc','*',"WHERE nipnas='$nipnas'")->num_rows();
				// if ($cekNipnas<1) {
					// INSERT GC BARU
					// $insertG	= $this->query->insertData('gc', "id_gc,nipnas,nama_gc,pic", "'','$nipnas','$namenipnas','POTS'");
				// }
				
				$rows 	= $this->query->insertData('pots', "id,id_segmen,nipnas,treg,id_witel,periode,nilai_pots,dateinput", "'','$idsegmen','$nipnas','$treg','$idwitel','$periode','$nilaipots','$dateinput'");
            }
        echo json_encode($idwitel);
		// delete_files($media['file_path']);
		$url 		= "Manage POTS";
		$activity 	= "IMPORT";
		
		$log = $this->query->insertlog($activity,$url,'');
		// print json_encode(array('success'=>true,'rows'=>$rows, 'id'=>$id ,'total'=>1));
        echo "done";
    }
	
	public function modaldatadetailpots(){
		if(checkingsessionpwt()){
			
			$id		= trim(strip_tags(stripslashes($this->input->post('type',true))));
			$gdivisi= trim(strip_tags(stripslashes($this->input->post('divisi',true))));
			$gsegmen= trim(strip_tags(stripslashes($this->input->post('segmen',true))));
			$gtreg	= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			$gwitel	= trim(strip_tags(stripslashes($this->input->post('witel',true))));
			$gam	= trim(strip_tags(stripslashes($this->input->post('am',true))));
			$title	= 'ACHIEVEMENT SEGMEN';
			
			if ($gdivisi=='ALL DIVISI' or $gdivisi=='') { $divisi	= "all"; } else { $divisi = $gdivisi;}
			if ($gsegmen=='ALL SEGMEN' or $gsegmen=='') { $segmen	= "all"; } else { $segmen = $gsegmen;}
			if ($gtreg=='ALL TREG' or $gtreg=='') 	{ $treg	= "all"; } else { $treg = $gtreg; }
			if ($gwitel=='ALL Witel' or $gwitel=='') { $witel	= "all"; } else { $witel = $gwitel; }
			if ($gam=='ALL AM' or $gam=='') 		{ $am	= "all"; } else { $am = $gam; }
			
			$data	= array(
				'id'	=> $id,
				'divisi'=> $divisi,
				'segmen'=> $segmen,
				'treg'	=> $treg,
				'witel'	=> $witel,
				'am'	=> $am,
				'title'	=> $title
			);
			
			header('Content-type: application/json; charset=UTF-8');
			
			echo json_encode($data);
		} else {
			redirect('/panel');
		}
	}
	public function datadetailpots_export($divisi,$segmen,$treg,$witel,$am){
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			
			$yfirst		= date('Y').'-01';
			$ynow		= date('Y-m');
			
			if ($divisi=='ALL%20DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL%20SEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL%20TREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL%20Witel' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL%20AM' or $am=='') 		{ $wheream	= ""; } else { $wheream = " AND nik_am='".$am."' "; }
			
			// $q			= "
						// select * from (
							// SELECT s.nama_segmen,g.nama_gc,w.nama_witel,p.* FROM pots p LEFT JOIN segmen s ON p.id_segmen = s.id_segmen LEFT JOIN gc g ON g.nipnas = p.nipnas LEFT JOIN witel w ON w.id_witel = p.id_witel
						// )as basic  where periode BETWEEN '$yfirst' and '$ynow' $wheresegmen $wheretreg $wherewitel ".$uam." 
						// ";
						// ECHO $wheredivisi;
			$q			= "
						select basic.*, 
						(select nama_segmen from segmen where id_segmen=basic.id_segmen) as nama_segmen, 
						(select nama_witel from witel where id_witel=basic.id_witel) as nama_witel,
						(select nama_gc from gc where nipnas=basic.nipnas) as nama_gc
						from (
							SELECT a.*, b.nipnas, b.nik_am, b.id_segmen, b.id_witel, b.treg, b.id_divisi FROM pots a left join map b on a.id_map=b.id_map
						) as basic
						where periode BETWEEN '$yfirst' and '$ynow'
						$wheredivisi $wheresegmen $wheretreg $wherewitel $wheream ".$uam." 
						";
						
			$dataCat	= 	$this->query->getDatabyQ($q);
			$no=0;
			
			foreach($dataCat as $data) { 
				$no++;
				$id	= $data['id'];
				
				@$row .= 
					"<tr>
						<td>".$no."
						<td>".$data['nama_segmen']."</td>
						<td>".$data['treg']."</td>
						<td>".$data['nama_witel']."</td>
						<td>".$data['nama_gc']."</td>
						<td>".$data['periode']."</td>
						<td><b>".$this->formula->rupiah3($data['nilai_pots'])."</b></td>
					</tr>";
			}
			$judulfile = 'Data_POTS';
			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=".$judulfile.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo '			
			<table border="1px">
				<thead>
					<tr>
						<th class="text-center">NO</th>
						<th class="text-center">SEGMEN</th>
						<th class="text-center">TREG</th>
						<th class="text-center">WITEL</th>
						<th class="text-center" style="min-width: 250px!important;">STANDARD NAME NIPNAS</th>
						<th class="text-center">PERIODE</th>
						<th class="text-center">NILAI POTS</th>
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
	public function datadetailpots($type,$divisi,$segmen,$treg,$witel,$am,$periode){
		if(checkingsessionpwt()){
			ini_set('max_execution_time', 123456);
			ini_set("memory_limit","1256M");
			
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			
			if ($periode=='') {
				$yfirst		= date('Y').'-01';
				$ynow		= date('Y-m');
			} else {
				$yfirst		= $periode.'-01';
				$ynow		= $periode.'-12';
			}
			
			if ($divisi=='ALL%20DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL%20SEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL%20TREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL%20Witel' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL%20AM' or $am=='') 		{ $wheream	= ""; } else { $wheream = " AND nik_am='".$am."' "; }
			
			// $q			= "
						// select * from (
							// SELECT s.nama_segmen,g.nama_gc,w.nama_witel,p.* FROM pots p LEFT JOIN segmen s ON p.id_segmen = s.id_segmen LEFT JOIN gc g ON g.nipnas = p.nipnas LEFT JOIN witel w ON w.id_witel = p.id_witel
						// )as basic  where periode BETWEEN '$yfirst' and '$ynow' $wheresegmen $wheretreg $wherewitel ".$uam." 
						// ";
			
			$q			= "
						select basic.*, 
						(select nama_divisi from divisi where id_divisi=basic.id_divisi) as nama_divisi, 
						(select nama_segmen from segmen where id_segmen=basic.id_segmen) as nama_segmen, 
						(select nama_witel from witel where id_witel=basic.id_witel) as nama_witel,
						(select nama_gc from gc where nipnas=basic.nipnas) as nama_gc
						from (
							SELECT a.*, b.nipnas, b.nik_am, b.id_divisi, b.id_segmen, b.id_witel, b.treg FROM pots a left join map b on a.id_map=b.id_map
						) as basic
						where periode BETWEEN '$yfirst' and '$ynow'
						$wheredivisi $wheresegmen $wheretreg $wherewitel $wheream ".$uam." 
						";
						
			$dataCat	= 	$this->query->getDatabyQ($q);
			$no=0;
			//$json['data'] ="";
			foreach($dataCat as $data) { 
				$no++;
				$id	= $data['id'];
				
				$row = array(
					$data['nama_segmen'],
					$data['treg'],
					$data['nama_witel'],
					$data['nama_gc'],
					$data['periode'],
					'<b>'.$this->formula->rupiah3($data['nilai_pots']).'</b>'
					);
				$json['data'][] = $row;
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	
	public function datadetaildashboardsustain($type,$divisi,$segmen,$treg,$witel,$am,$bulan,$status,$spec,$td,$tahun){
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			$cekuam1	= str_replace('id_divisi','c.id_divisi',$uam);
			$cekuam2	= str_replace('id_segmen','c.id_segmen',$cekuam1);
			$cekuam3	= str_replace('treg','c.treg',$cekuam2);
			$cekuam4	= str_replace('id_witel','c.id_witel',$cekuam3);
			$cekuam		= str_replace('nik_am','c.nik_am',$cekuam4);
			
			$wherecond	= "a.sustain_dari!=0 and a.sustain_dari!='' and a.sustain_dari is not null";
			
			if ($divisi=='ALL%20DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL%20SEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL%20TREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL%20Witel' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL%20AM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
			if ($bulan=='ALL' or $bulan=='') 	{ $wherebulan	= ""; } else { $wherebulan = "and LEFT(tanggal, 7)='".$bulan."'"; }
			if ($status=='ALL' or $status=='') 	{ $wherestatus	= ""; } else { $wherestatus = "and id_sr='".$status."'"; }
			if ($tahun=='') 	{ $wheretahun	= "and tahun='".date('Y')."'"; } else { $wheretahun = "and tahun='".$tahun."'"; }
			
			if ($td=='treg') {
				$wherespec = "and treg='".str_replace("%20"," ",$spec)."'";
			} else {
				$wherespec = "and id_segmen='".$spec."'";
			}
			
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
									",
									'
									a.id_lelang as kode_lelang,
									a.id_lop,
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
									a.status as status,
									(select sr from status_raisa where id_sr=a.id_sr) as status_raisa,
									(select id_sr from status_raisa where id_sr=a.id_sr) as id_sr,
									a.nomor_kontrak,
									(select group_concat(file) as file_kontrak from file_lop where id_lop=a.id_lop) as file_kontrak,
									a.tanggal,
									a.tanggal_kb,
									a.sustain_dari,
									c.tahun
									',
									"
									WHERE $wherecond $cekuam $wheredivisi $wherespec $wherewitel $wheream $wherestatus $wherebulan $wheretahun order by k_l_d_i asc
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
					$btn = "<a class='btn btn-xs btn-success btnFilePreview' data-toggle='modal' data-target='#FilePreview' data-file='".$data['file_kontrak']."' data-ext='".pathinfo($data['file_kontrak'], PATHINFO_EXTENSION)."' data-nomor='".$data['nomor_kontrak']."' ><i data-toggle='tooltip' title='".$data['file_kontrak']."' class='glyphicon glyphicon-fullscreen'></i></a>";
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
					$data['tahun'],
					$data['nama_segmen'],
					$data['treg'],
					$witel,
					$data['namaam'],
					$data['k_l_d_i'],
					$satker_2,
					'<a class="btndetsusbef" data-toggle="modal" data-target="#detaildatasustainbefore" data-id="'.$data['id_lop'].'" style="color:#337ab7!important; cursor:pointer;">'.$data['nama_paket'].'</a>',
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
	
	public function modaldatadetailsustain(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('type',true))));
			$am					= trim(strip_tags(stripslashes($this->input->post('am',true))));
				
			$title	= "LOP SUSTAIN 2018";
			
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

	public function modaldatadetailsustainN(){
		if(checkingsessionpwt()){
			
			$id		= trim(strip_tags(stripslashes($this->input->post('id',true))));

			$getNamaPaket 	= $this->query->getDatabyQ("SELECT nama_pkt,sustain_dari from lop where id_lop='$id'");
			$dataPaket		= array_shift($getNamaPaket);
			$title 			= $dataPaket['nama_pkt'];
			$idsustain		= $dataPaket['sustain_dari'];
				
			//$title	= "LOP SUSTAIN 2018";
			
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
	
	public function mlmisustain($type,$divisi,$segmen,$treg,$witel,$am,$spec,$td,$tahun){
		if(checkingsessionpwt()){
			$data['divisi']		= $divisi;
			$data['segmen']		= $segmen;
			$data['treg']		= $treg;
			$data['witel']		= $witel;
			$data['am']			= $am;
			$data['spec']		= $spec;
			$data['td']			= $td;
			$data['tahun']		= $tahun;
			
			$this->load->view('panel/dashboard/mlmisustain', $data);
		} else {
			redirect('/panel');
		}
	}
	public function linesustain($type,$divisi,$segmen,$treg,$witel,$am,$spec,$td,$tahun){
		if(checkingsessionpwt()){
			$data['divisi']		= $divisi;
			$data['segmen']		= $segmen;
			$data['treg']		= $treg;
			$data['witel']		= $witel;
			$data['am']			= $am;
			$data['spec']		= $spec;
			$data['td']			= $td;
			$data['tahun']		= $tahun;
			
			$this->load->view('panel/dashboard/linesustain', $data);
		} else {
			redirect('/panel');
		}
	}
	
	public function datadetaildashboardsustain_export(){
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
			$tahun = $_GET['tahun'];
			
			$wherecond	= "a.sustain_dari!=0 and a.sustain_dari is not null";
			$judulfile	= "SUSTAIN 2018";
			
			if ($divisi=='ALL DIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
			if ($segmen=='ALL SEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
			if ($treg=='ALL TREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
			if ($witel=='ALL Witel' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and id_witel='".$witel."'"; }
			if ($am=='ALL AM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
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
									a.status as status,
									a.nomor_kontrak,
									(select group_concat(file) as file_kontrak from file_lop where id_lop=a.id_lop) as file_kontrak,
									a.tanggal_kb,
									a.last_update,
									c.tahun,
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
					<td>".$data['kode_raisa']."</td>
					<td>".$data['status']."</td>
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
            <th>ID LO</th>
            <th>ID LOP</th>
            <th>ID Mapping</th>
            <th>ID Sirup</th>
            <th>ID Lelang</th>
            <th>Nama K/L/D/I</th>
            <th>Nama Satker</th>
            <th>Nama AM</th>
            <th>Nama Paket</th>
            <th>Pagu Paket</th>
            <th>Nilai Win</th>
            <th>Achievement</th>
            <th>%</th>
            <th>Kode RAISA</th>
            <th>Status</th>
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
	
	public function getDataRoletm($id){
		if(checkingsessionpwt()){
			
			$dataroleAll		= $this->query->getData('menu_tamara','*',"WHERE parent='0' ORDER BY sort ASC");
			
			// header('Content-type: application/json; charset=UTF-8');
			
			
				echo '
					<div class="">
					<div class="col-sm-12 table-responsive" style="padding-top:10px;">
						<table class="smalltable nowrap table" width=100%>
						<thead class="bg-gray-dark text-white">
							<th>Menu Name</th>
							<th class="text-right">Fitur</th>
						</thead>
						<tbody>';
						foreach($dataroleAll as $data) {
						$getdataEksrole	= $this->query->getData('role_menu_tamara','*',"WHERE id_role='$id' and id_menu='".$data['id_menu']."'");
						$dataEksrole		= array_shift($getdataEksrole);
						if ($dataEksrole!='') { $ceked = "checked"; } else { $ceked = ""; }
						echo "
						<tr>
							<td>
								<div class='checkbox checkbox-info checkbox-circle'>
									<input onclick='return false;' readonly value='".$data['id_menu']."' id='ed_checkbox".$data['id_menu']."' type='checkbox' name='ed_menu[]' ".$ceked.">
									<label for='ed_checkbox".$data['id_menu']."'><b>".$data['menu']."</b></label>
								</div>
							</td>
							<td class='text-right'>
						";
							$data_fitur = explode_fitur($data['fitur']);
							for($x=0;$x<count($data_fitur);$x++)
							{
								$dataEksFitur = explode_fitur($dataEksrole['akses']);
								if(in_array($data_fitur[$x],$dataEksFitur)){ $ceked_fitur[$x] = 'checked'; } else {   $ceked_fitur[$x] = ''; }
								
								$CekSub	= $this->query->getNumRows('menu_tamara','*',"WHERE parent='".$data['id_menu']."'")->num_rows();
								if ($CekSub>0) { echo ''; } else {
									echo "
										<div class='checkbox checkbox-success checkbox-circle checkbox-inline text-center' style='margin-right:15px!important;'>
											<input onclick='return false;' readonly value='".$data_fitur[$x]."' id='ed_checkbox".$data['id_menu'].$data_fitur[$x]."' type='checkbox' name='ed_fitur[".$data['id_menu']."][]' ".$ceked_fitur[$x].">
											<label for='checkbox".$data['id_menu'].$data_fitur[$x]."' style='text-transform:capitalize;'>".$data_fitur[$x]."</label>
										</div>
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
							$getSubMenu 	= $this->query->getData('menu_tamara','*',"WHERE parent='".$data['id_menu']."' order by sort asc");
							foreach ($getSubMenu as $data) {
								$getdataEksrole	= $this->query->getData('role_menu_tamara','*',"WHERE id_role='$id' and id_menu='".$data['id_menu']."'");
								$dataEksrole		= array_shift($getdataEksrole);
								if ($dataEksrole!='') { $ceked = "checked"; } else { $ceked = ""; }
								echo "
								<tr style='background: rgba(0,0,0,0.22)!important;'>
									<td style='padding-left: 30px!important;'>
										<div class='checkbox checkbox-info checkbox-circle'>
											<input onclick='return false;' readonly value='".$data['id_menu']."' id='ed_checkbox".$data['id_menu']."' type='checkbox' name='ed_menu[]' ".$ceked.">
											<label for='ed_checkbox".$data['id_menu']."'><b>".$data['menu']."</b></label>
										</div>
									</td>
									<td class='text-right'>
								";
									$data_fitur = explode_fitur($data['fitur']);
									for($x=0;$x<count($data_fitur);$x++)
									{
										$dataEksFitur = explode_fitur($dataEksrole['akses']);
										if(in_array($data_fitur[$x],$dataEksFitur)){ $ceked_fitur[$x] = 'checked'; } else {   $ceked_fitur[$x] = ''; }
										echo "
											<div class='checkbox checkbox-success checkbox-circle checkbox-inline text-center' style='margin-right:15px!important;'>
												<input onclick='return false;' readonly value='".$data_fitur[$x]."' id='ed_checkbox".$data['id_menu'].$data_fitur[$x]."' type='checkbox' name='ed_fitur[".$data['id_menu']."][]' ".$ceked_fitur[$x].">
												<label for='checkbox".$data['id_menu'].$data_fitur[$x]."' style='text-transform:capitalize;'>".$data_fitur[$x]."</label>
											</div>
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

	public function datadetailsustainmodal($idlop){
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			$cekuam1	= str_replace('id_divisi','c.id_divisi',$uam);
			$cekuam2	= str_replace('id_segmen','c.id_segmen',$cekuam1);
			$cekuam3	= str_replace('treg','c.treg',$cekuam2);
			$cekuam4	= str_replace('id_witel','c.id_witel',$cekuam3);
			$cekuam		= str_replace('nik_am','c.nik_am',$cekuam4);

			$getSustainID	= $this->query->getDatabyQ("SELECT sustain_dari from lop where id_lop='$idlop'");
			$dataSustainID	= array_shift($getSustainID);
			$idsustain		= $dataSustainID['sustain_dari'];
			
			$wherecond = "a.id_lop IN ($idsustain)";
			
			
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
									WHERE $wherecond order by k_l_d_i asc
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
					$data['tahun'],
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

	public function getdatasustain() {
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			$cekuam1	= str_replace('id_divisi','c.id_divisi',$uam);
			$cekuam2	= str_replace('id_segmen','c.id_segmen',$cekuam1);
			$cekuam3	= str_replace('treg','c.treg',$cekuam2);
			$cekuam4	= str_replace('id_witel','c.id_witel',$cekuam3);
			$cekuam		= str_replace('nik_am','c.nik_am',$cekuam4);
			$search		= @$_GET['q'];
			
			$qCust		= "
						select * from (
							SELECT a.* FROM lop a left join lo b ON a.id_lo = b.id_lo LEFT JOIN map c ON c.id_map = b.id_map 
							WHERE c.tahun=2018 ORDER BY id_lop ASC
						) as base
						where (id_lop like '%$search%' or id_lo like '%$search%' or nama_pkt like '%$search%')
						";
			$getCust	= $this->query->getDatabyQ($qCust);
			$no=0;
			
			header('Content-type: application/json; charset=UTF-8');
			
			foreach($getCust as $datacust) { 
				$row = array(
					'id'	=> $datacust['id_lop'],
					'text'	=> $datacust['id_lo'].' - '.$datacust['nama_pkt']
					);
				$page = array(
					'more'	=> 'true'
				);
				
				$json['items'][] 		= $row;
				$json['pagination'][] 	= $page;
			}
			echo json_encode($json);
		}else{
            redirect('/login');
        }
	}

	public function getdatasustainedit($idlop) {
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			$cekuam1	= str_replace('id_divisi','c.id_divisi',$uam);
			$cekuam2	= str_replace('id_segmen','c.id_segmen',$cekuam1);
			$cekuam3	= str_replace('treg','c.treg',$cekuam2);
			$cekuam4	= str_replace('id_witel','c.id_witel',$cekuam3);
			$cekuam		= str_replace('nik_am','c.nik_am',$cekuam4);
			$search		= @$_GET['q'];
			
			$qCust		= "
						select * from (
							SELECT a.* FROM lop a left join lo b ON a.id_lo = b.id_lo LEFT JOIN map c ON c.id_map = b.id_map 
							WHERE c.tahun=2018 ORDER BY id_lop ASC
						) as base
						where id_lop in ($idlop)
						";
						//echo $qCust;
			$getCust	= $this->query->getDatabyQ($qCust);
			$no=0;
			
			header('Content-type: application/json; charset=UTF-8');
			
			foreach($getCust as $datacust) { 
				$row = array(
					'id'	=> $datacust['id_lop'],
					'text'	=> $datacust['id_lo'].' - '.$datacust['nama_pkt']
					);
				$page = array(
					'more'	=> 'true'
				);
				
				$json 		= $row;
				//$json['pagination'][] 	= $page;
			}
			echo json_encode($json);
		}else{
            redirect('/login');
        }
	}

	public function getdataloselect2() {
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			$cekuam1	= str_replace('id_divisi','c.id_divisi',$uam);
			$cekuam2	= str_replace('id_segmen','c.id_segmen',$cekuam1);
			$cekuam3	= str_replace('treg','c.treg',$cekuam2);
			$cekuam4	= str_replace('id_witel','c.id_witel',$cekuam3);
			$cekuam		= str_replace('nik_am','c.nik_am',$cekuam4);
			$search		= @$_GET['q'];
			
			$qCust		= "
						select * from (
							SELECT * FROM (
								SELECT a.id_lo,a.id_map,a.nama_keg,a.satker_lo,b.id_divisi,b.id_segmen,b.id_witel,b.treg,b.nik_am,b.tahun 
								FROM lo a LEFT JOIN map b ON a.id_map = b.id_map order by a.id_lo desc
							)as Basic WHERE id_lo IS NOT NULL $uam ORDER BY id_lo ASC
						) as base
						where (id_lo like '%$search%' or id_map like '%$search%' or upper(nama_keg) like upper('%$search%') or satker_lo like '%$search%' or tahun like '%$search%')
						";
			$getCust	= $this->query->getDatabyQ($qCust);
			$no=0;
			
			header('Content-type: application/json; charset=UTF-8');
			
			foreach($getCust as $datacust) { 
				$row = array(
					'id'	=> $datacust['id_lo'],
					'text'	=> $datacust['id_map'].' / '.$datacust['nama_keg'].' / '.$datacust['satker_lo'].' / '.$datacust['tahun']
					);
				$page = array(
					'more'	=> 'true'
				);
				
				$json['items'][] 		= $row;
				$json['pagination'][] 	= $page;
			}
			echo json_encode($json);
		}else{
            redirect('/login');
        }
	}

	public function getdataloedit($idlo) {
		if(checkingsessionpwt()){
			$userdata 	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];
			$uam		= $this->formula->getUAM($userid);
			$cekuam1	= str_replace('id_divisi','c.id_divisi',$uam);
			$cekuam2	= str_replace('id_segmen','c.id_segmen',$cekuam1);
			$cekuam3	= str_replace('treg','c.treg',$cekuam2);
			$cekuam4	= str_replace('id_witel','c.id_witel',$cekuam3);
			$cekuam		= str_replace('nik_am','c.nik_am',$cekuam4);
			$search		= @$_GET['q'];
			
			$qCust		= "
						select * from (
							SELECT * FROM (
								SELECT a.id_lo,a.id_map,a.nama_keg,a.satker_lo,b.id_divisi,b.id_segmen,b.id_witel,b.treg,b.nik_am,b.tahun 
								FROM lo a LEFT JOIN map b ON a.id_map = b.id_map order by a.id_lo desc
							)as Basic WHERE id_lo IS NOT NULL $uam ORDER BY id_lo ASC
						) as final
						where id_lo='$idlo'
						";
						//echo $qCust;
			$getCust	= $this->query->getDatabyQ($qCust);
			$no=0;
			
			header('Content-type: application/json; charset=UTF-8');
			
			foreach($getCust as $datacust) { 
				$row = array(
					'id'	=> $datacust['id_lo'],
					'text'	=> $datacust['id_map'].' / '.$datacust['nama_keg'].' / '.$datacust['satker_lo'].' / '.$datacust['tahun']
					);
				$page = array(
					'more'	=> 'true'
				);
				
				$json 		= $row;
				//$json['pagination'][] 	= $page;
			}
			echo json_encode($json);
		}else{
            redirect('/login');
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

	public function generateDatatable ($columnsDefault,$jsonfile) {
		error_reporting(0);

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = true;
			}
		}

		// get all raw data
		$alldata = json_decode( file_get_contents( $jsonfile ), true );

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

	public function getdatakontak($type){
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
			$jsonfile	= base_url().'jsondata/datakontak/'.$type.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatakontakgroup(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'nama'			=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datakontakgroup';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalkontak(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$qKontak 	= "
						select
							base.*, 
							case when type_kontak like '%Pelanggan%' then '1' else '0' end as pelanggan,
							case when type_kontak like '%Karyawan%' then '1' else '0' end as karyawan,
							case when type_kontak like '%Supplier%' then '1' else '0' end as supplier,
							case when type_kontak like '%Lainnya%' then '1' else '0' end as lainnya
						from(
							select
								a.*,
								(select GROUP_CONCAT(nama_type) from kontak_type where id_kt in (select type from kontak_detail_type where id_kontak=a.id_kontak)) as type_kontak,
								(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Kontak' AND xa.data = a.id_kontak ORDER BY xa.date_time DESC limit 1)as update_by,
								(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Kontak' AND xa.data = a.id_kontak ORDER BY xa.date_time DESC limit 1)as last_update
							from
							kontak a
						) as base
						where 1=1 and id_kontak='$id'
						ORDER BY id_kontak desc
						";
			$gKontak 	= $this->query->getDatabyQ($qKontak);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($gKontak as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}

	public function insertkontak(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid		= $userdata['userid'];

			// INFO KONTAK
			$dn 		= trim(strip_tags(stripslashes($this->input->post('nama_panggilan',true))));
			$type 		= $_POST['tipe'];
			$grup 		= trim(strip_tags(stripslashes($this->input->post('grup',true))));

			// INFORMASI UMUM
			$gender 	= trim(strip_tags(stripslashes($this->input->post('gender',true))));
			$fn 		= trim(strip_tags(stripslashes($this->input->post('namaawal',true))));
			$mn 		= trim(strip_tags(stripslashes($this->input->post('namatengah',true))));
			$ln 		= trim(strip_tags(stripslashes($this->input->post('namaakhir',true))));
			$mobile 	= trim(strip_tags(stripslashes($this->input->post('handphone',true))));
			$card 		= trim(strip_tags(stripslashes($this->input->post('kartuidentitas',true))));
			$cardno 	= trim(strip_tags(stripslashes($this->input->post('noidentitas',true))));
			$email 		= trim(strip_tags(stripslashes($this->input->post('email',true))));
			$infolain 	= trim(strip_tags(stripslashes($this->input->post('infolain',true))));
			$company 	= trim(strip_tags(stripslashes($this->input->post('namaperusahaan',true))));
			$tlp 		= trim(strip_tags(stripslashes($this->input->post('tlp',true))));
			$fax 		= trim(strip_tags(stripslashes($this->input->post('fax',true))));
			$npwp 		= trim(strip_tags(stripslashes($this->input->post('npwp',true))));
			$bAddress 	= trim(strip_tags(stripslashes($this->input->post('alamatbayar',true))));
			$bNo 		= trim(strip_tags(stripslashes($this->input->post('nomor',true))));
			$bRT 		= trim(strip_tags(stripslashes($this->input->post('rt',true))));
			$bRW 		= trim(strip_tags(stripslashes($this->input->post('rw',true))));
			$bPOS 		= trim(strip_tags(stripslashes($this->input->post('kodepos',true))));
			$bKel 		= trim(strip_tags(stripslashes($this->input->post('kelurahan',true))));
			$bKec 		= trim(strip_tags(stripslashes($this->input->post('kecamatan',true))));
			$bKota 		= trim(strip_tags(stripslashes($this->input->post('kota',true))));
			$bProv 		= trim(strip_tags(stripslashes($this->input->post('provinsi',true))));
			$sAddress 	= trim(strip_tags(stripslashes($this->input->post('alamatkirim',true))));

			// AKUN BANK
			$bank 		= trim(strip_tags(stripslashes($this->input->post('namabank',true))));
			$kacbank 	= trim(strip_tags(stripslashes($this->input->post('kacabbank',true))));
			$nameB 		= trim(strip_tags(stripslashes($this->input->post('pemegangakunbank',true))));
			$norek 		= trim(strip_tags(stripslashes($this->input->post('nomorrek',true))));

			// PEMETAAN AKUN
			$gpiutang 	= trim(strip_tags(stripslashes($this->input->post('akunpiutang',true))));
			if($gpiutang=='') { $piutang = '1-10100'; } else { $piutang = $gpiutang; }
			$ghutang 	= trim(strip_tags(stripslashes($this->input->post('akunhutang',true))));
			if($ghutang=='') { $hutang = '2-20100'; } else { $hutang = $ghutang; }

			$aktifhut 	= trim(strip_tags(stripslashes($this->input->post('aktifhutangmax',true))));
			$maxhutang 	= trim(strip_tags(stripslashes($this->input->post('maxhutang',true))));
			$syarat 	= trim(strip_tags(stripslashes($this->input->post('syaratbayar',true))));
				
			$id			= $this->db->insert_id();
			$url 		= "Kontak";
			$activity 	= "INSERT";

			$rows = $this->query->insertData('kontak',
						"
						id_kontak,display_name,company_name,title,first_name,middle_name,last_name,email,mobile,phone,fax,billing_address,billing_no,
						billing_rt,billing_rw,billing_postcode,billing_kelurahan,billing_kecamatan,billing_city,billing_province,shipping_address,tax_number,
						identity_type,identity_number,other_detail,contact_group,id_bank,kacbank,pemegangakunbank,norek,akunpiutang,akunhutang,aktifhutang,
						maxhutang,syaratbayar
						",
						"
						'','$dn','$company','$gender','$fn','$mn','$ln','$email','$mobile','$tlp','$fax','$bAddress','$bNo','$bRT','$bRW','$bPOS','$bKel','$bKec'
						,'$bKota','$bProv','$sAddress','$npwp','$card','$cardno','$infolain','$grup','$bank','$kacbank','$nameB','$norek','$piutang','$hutang','$aktifhut',
						'$maxhutang','$syarat'
						");
			if($rows) {
				$jmlType 		= count($type);
				for ($i=0;$i<$jmlType;$i++) {
					$insType 	= $this->query->insertData('kontak_detail_type', "id_kdt,id_kontak,type", "'','$id','$type[$i]'");
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

	public function updatekontak(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid		= $userdata['userid'];

			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			// INFO KONTAK
			$dn 		= trim(strip_tags(stripslashes($this->input->post('ed_nama_panggilan',true))));
			$type 		= $_POST['ed_tipe'];
			$grup 		= trim(strip_tags(stripslashes($this->input->post('ed_grup',true))));

			// INFORMASI UMUM
			$gender 	= trim(strip_tags(stripslashes($this->input->post('ed_gender',true))));
			$fn 		= trim(strip_tags(stripslashes($this->input->post('ed_namaawal',true))));
			$mn 		= trim(strip_tags(stripslashes($this->input->post('ed_namatengah',true))));
			$ln 		= trim(strip_tags(stripslashes($this->input->post('ed_namaakhir',true))));
			$mobile 	= trim(strip_tags(stripslashes($this->input->post('ed_handphone',true))));
			$card 		= trim(strip_tags(stripslashes($this->input->post('ed_kartuidentitas',true))));
			$cardno 	= trim(strip_tags(stripslashes($this->input->post('ed_noidentitas',true))));
			$email 		= trim(strip_tags(stripslashes($this->input->post('ed_email',true))));
			$infolain 	= trim(strip_tags(stripslashes($this->input->post('ed_infolain',true))));
			$company 	= trim(strip_tags(stripslashes($this->input->post('ed_namaperusahaan',true))));
			$tlp 		= trim(strip_tags(stripslashes($this->input->post('ed_tlp',true))));
			$fax 		= trim(strip_tags(stripslashes($this->input->post('ed_fax',true))));
			$npwp 		= trim(strip_tags(stripslashes($this->input->post('ed_npwp',true))));
			$bAddress 	= trim(strip_tags(stripslashes($this->input->post('ed_alamatbayar',true))));
			$bNo 		= trim(strip_tags(stripslashes($this->input->post('ed_nomor',true))));
			$bRT 		= trim(strip_tags(stripslashes($this->input->post('ed_rt',true))));
			$bRW 		= trim(strip_tags(stripslashes($this->input->post('ed_rw',true))));
			$bPOS 		= trim(strip_tags(stripslashes($this->input->post('ed_kodepos',true))));
			$bKel 		= trim(strip_tags(stripslashes($this->input->post('ed_kelurahan',true))));
			$bKec 		= trim(strip_tags(stripslashes($this->input->post('ed_kecamatan',true))));
			$bKota 		= trim(strip_tags(stripslashes($this->input->post('ed_kota',true))));
			$bProv 		= trim(strip_tags(stripslashes($this->input->post('ed_provinsi',true))));
			$sAddress 	= trim(strip_tags(stripslashes($this->input->post('ed_alamatkirim',true))));

			// AKUN BANK
			$bank 		= trim(strip_tags(stripslashes($this->input->post('ed_namabank',true))));
			$kacbank 	= trim(strip_tags(stripslashes($this->input->post('ed_kacabbank',true))));
			$nameB 		= trim(strip_tags(stripslashes($this->input->post('ed_pemegangakunbank',true))));
			$norek 		= trim(strip_tags(stripslashes($this->input->post('ed_nomorrek',true))));

			// PEMETAAN AKUN
			$gpiutang 	= trim(strip_tags(stripslashes($this->input->post('ed_akunpiutang',true))));
			if($gpiutang=='') { $piutang = '1-10100'; } else { $piutang = $gpiutang; }
			$ghutang 	= trim(strip_tags(stripslashes($this->input->post('ed_akunhutang',true))));
			if($ghutang=='') { $hutang = '2-20100'; } else { $hutang = $ghutang; }

			$aktifhut 	= trim(strip_tags(stripslashes($this->input->post('ed_aktifhutangmax',true))));
			$maxhutang 	= trim(strip_tags(stripslashes($this->input->post('ed_maxhutang',true))));
			$syarat 	= trim(strip_tags(stripslashes($this->input->post('ed_syaratbayar',true))));
				
			$url 		= "Kontak";
			$activity 	= "UPDATE";

			$rows = $this->query->updateData('kontak',
						"display_name='$dn', company_name='$company',title='$gender', first_name='$fn', middle_name='$mn' , last_name='$ln',
						email='$email', mobile='$mobile', phone='$tlp', fax='$fax', billing_address='$bAddress', billing_no='$bNo', billing_rt='$bRT',
						billing_rw='$bRW', billing_postcode='$bPOS', billing_kelurahan='$bKel', billing_kecamatan='$bKec', billing_city='$bKota',
						billing_province='$bProv', shipping_address='$sAddress', tax_number='$npwp', identity_type='$card', identity_number='$cardno',
						other_detail='$infolain', contact_group='$grup', id_bank='$bank', kacbank='$kacbank', pemegangakunbank='$nameB', norek='$norek',
						akunpiutang='$piutang', akunhutang='$hutang', aktifhutang='$aktifhut', maxhutang='$maxhutang', syaratbayar='$syarat'
						"
						,"WHERE id_kontak='$id'");

			if($rows) {
				$deletefirst 	= $this->query->deleteData('kontak_detail_type','id_kontak',$id);

				$jmlType 		= count($type);
				for ($i=0;$i<$jmlType;$i++) {
					$insType 	= $this->query->insertData('kontak_detail_type', "id_kdt,id_kontak,type", "'','$id','$type[$i]'");
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

	public function insertkontakgroup(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$namagroup	= trim(strip_tags(stripslashes($this->input->post('namagroup',true))));
			
			$rows 		= $this->query->insertData('kontak_group', "id_group,nama,status", "'','$namagroup',''");
			$id			= $this->db->insert_id();
			$url 		= "Kontak Group";
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

	public function deletekontak(){
		if(checkingsessionpwt()){
			$url 		= "Kontak";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$rows 	= $this->query->deleteData('kontak','id_kontak',$cond);
			$rows2 	= $this->query->deleteData('kontak_detail_type','id_kontak',$cond);
			
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

	public function deletekontakgroup(){
		if(checkingsessionpwt()){
			$url 		= "Kontak Group";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddelgroup',true))));
			
			$rows = $this->query->deleteData('kontak_group','id_group',$cond);
			
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

	public function modalkontakgroup(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$qKontak 	= "
						select * from(
							select
								a.*,
								(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Kontak Group' AND xa.data = a.id_group ORDER BY xa.date_time DESC limit 1)as update_by,
								(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Kontak Group' AND xa.data = a.id_group ORDER BY xa.date_time DESC limit 1)as last_update
							from
							kontak_group a
						) as base
						where 1=1 and id_group='$id'
						ORDER BY id_group desc
						";
			$gKontak 	= $this->query->getDatabyQ($qKontak);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($gKontak as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}

	public function getdatamenus(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'menu'			=> true,
				'description'	=> true,
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
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$menu		= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			$desc		= trim(strip_tags(stripslashes($this->input->post('desc',true))));
			$fileName 	= str_replace(' ','_',time().$_FILES['pict']['name']);
			$link		= trim(strip_tags(stripslashes($this->input->post('link',true))));
			$style		= trim(strip_tags(stripslashes($this->input->post('menutype',true))));
			$parent		= trim(strip_tags(stripslashes($this->input->post('parent',true))));
			$sort		= trim(strip_tags(stripslashes($this->input->post('sort',true))));

			$config['upload_path'] = './images/content/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media = $this->upload->data('pict');
			
			$rows 		= $this->query->insertData('menu_site', "id_menu,menu,description,background,link,style,parent,sort", 
													"'','$menu','$desc','$fileName','$link','$style','$parent','$sort'");
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
			$link			= trim(strip_tags(stripslashes($this->input->post('ed_link',true))));
			$style			= trim(strip_tags(stripslashes($this->input->post('ed_menutype',true))));
			$sort			= trim(strip_tags(stripslashes($this->input->post('ed_sort',true))));
			$desc			= trim(strip_tags(stripslashes($this->input->post('ed_desc',true))));
			$cekinglogo		= $_FILES['upl']['name'];

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
				
				$rows = $this->query->updateData('menu_site',"menu='$menu', parent='$parent', link='$link', style='$style', background='$fileName', sort='$sort', description='$desc'","WHERE id_menu='".$id."'");
				
				if($rows) {
					$log = $this->query->insertlog($activity,$url,$id);
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}	
			} else {
				$rows = $this->query->updateData('menu_site',"menu='$menu', parent='$parent',link='$link', style='$style', sort='$sort' , description='$desc'","WHERE id_menu='$id'");

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
			$menu		= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			$headline	= trim(strip_tags(stripslashes($this->input->post('headline',true))));
			$content	= str_replace("'",'`',$_POST['content']);

			$q 			= "
						insert into content (title,sub,id_menu,headline,content) values ('$title','',$menu,'$headline','$content')
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
			$menu		= trim(strip_tags(stripslashes($this->input->post('ed_menu',true))));
			$headline	= trim(strip_tags(stripslashes($this->input->post('ed_headline',true))));
			$content	= str_replace("'",'`',$_POST['ed_content']);

			$userid		= $userdata['userid'];
				
			$url 		= "Manage Content";
			$activity 	= "UPDATE";
			
			$rows = $this->query->updateData('content',"id_menu='$menu', title='$title',headline='$headline', content='$content'","WHERE id_content='$id'");

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
			$jsonfile	= $this->query->getdatalog();
			$this->datatable->generateDatatable($arraynya,$jsonfile);
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
			$fileName 	= str_replace(' ','_',time().$_FILES['pict']['name']);
			$date 		= date('Y-m-d');

			$link		= $this->formula->clean(strtolower($title));
				
			$config['upload_path'] = './images/content/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media = $this->upload->data('pict');

			$q 			= "
						insert into blog (title,sub,id_menu,headline,link,content,picture,create_by,create_date) values ('$title','',$menu,'$headline','$link','$content','$fileName','$userid','$date')
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
			$menu		= trim(strip_tags(stripslashes($this->input->post('ed_menu',true))));
			$headline	= trim(strip_tags(stripslashes($this->input->post('ed_headline',true))));
			$content	= str_replace("'",'`',$_POST['ed_content']);

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

				$rows = $this->query->updateData('blog',"id_menu='$menu', title='$title', link='$link',headline='$headline', content='$content', picture='$fileName'","WHERE id_blog='$id'");
			} else {
				$rows = $this->query->updateData('blog',"id_menu='$menu', title='$title', link='$link',headline='$headline', content='$content'","WHERE id_blog='$id'");
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

			$title		= trim(strip_tags(stripslashes($this->input->post('title',true))));
			$link		= trim(strip_tags(stripslashes($this->input->post('link',true))));
			$fileName 	= str_replace(' ','_',time().$_FILES['pict']['name']);
			$date 		= date('Y-m-d');
				
			$config['upload_path'] = './images/link/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media = $this->upload->data('pict');

			$q 			= "
						insert into link (title,link,picture) values ('$title','$link','$fileName')
						";
						//echo $q;
			$rows 		= $this->query->insertDatabyQ($q);

			if($rows) {
				$filedir 	= 'link';
				$this->makeThumbnails($filedir,$fileName);

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
			
				$fileName = str_replace(' ','_',time().$_FILES['upl']['name']);
				$config['upload_path'] = './images/link/'; //buat folder dengan nama assets di root folder
				$config['file_name'] = $fileName;
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('upl');

				$rows = $this->query->updateData('link',"title='$title', link='$link', picture='$fileName'","WHERE id_link='$id'");
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
				'picture'		=> true,
				'title'			=> true,
				'sub'			=> true,
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
			$fileName 	= str_replace(' ','_',time().$_FILES['pict']['name']);
				
			$config['upload_path'] = './images/slides/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media = $this->upload->data('pict');

			$q 			= "
						insert into banner (title,sub,img,thumb) values ('$title','$sub','$fileName','blur_thumb.png')
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
			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			/*$title		= trim(strip_tags(stripslashes($this->input->post('ed_title',true))));
			$sub		= trim(strip_tags(stripslashes($this->input->post('ed_subtitle',true))));*/
			$title		= $this->input->post('ed_title',true);
			$sub		= $this->input->post('ed_subtitle',true);

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

				$rows = $this->query->updateData('banner',"title='$title', sub='$sub', img='$fileName'","WHERE id_banner='$id'");
			} else {
				$rows = $this->query->updateData('banner',"title='$title', sub='$sub'","WHERE id_banner='$id'");
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
						'name_site'	=> $data['name_site'],
						'logo'		=> base_url()."images/".$data['logo'],
						'favicon'	=> $data['favicon'],
						'mail_site'	=> $data['mail_site'],
						'mailbase'	=> $data['mailbase'],
						'alamat'	=> $data['alamat'],
						'phone'		=> $data['phone'],
						'maps'		=> $data['maps'],
						'facebook'	=> $data['facebook'],
						'twitter'	=> $data['twitter'],
						'instagram'	=> $data['instagram'],
						'youtube'	=> $data['youtube'],
						'showreel'	=> $data['showreel'],
						'updateby'	=> $data['update_by'],
						'updatedate'=> $data['last_update']
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
				$config['allowed_types'] = 'gif|jpg|png|jpeg';
				$config['max_size'] = 10000000;
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('upl') )
				$this->upload->display_errors();
					 
				$media = $this->upload->data('upl');
				
				$rows = $this->query->updateData('configsite',"name_site='$name', logo='$fileName', mail_site='$mail', alamat='$alamat', phone='$phone', maps='$maps', facebook='$facebook', twitter='$twitter', youtube='$youtube', instagram='$instagram', showreel='$showreel'","WHERE id_site='1'");
				
				
			} else {
				$rows = $this->query->updateData('configsite',"name_site='$name', mail_site='$mail', alamat='$alamat', phone='$phone', maps='$maps', facebook='$facebook', twitter='$twitter', youtube='$youtube', instagram='$instagram', showreel='$showreel'","WHERE id_site='1'");
				
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
		'smtp_user' => 'noreply@parwatha.com', // change it to yours
		'smtp_pass' => 'PwtEmail01', // change it to yours
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
		$this->email->from('noreply@parwatha.com'); // change it to yours
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
			$menu		= trim(strip_tags(stripslashes($this->input->post('menu',true))));
			$fileName 	= str_replace(' ','_',time().$_FILES['pict']['name']);
			$content	= str_replace("'",'`',$_POST['content']);
			$headline	= trim(strip_tags(stripslashes($this->input->post('content',true))));
			$date 		= date('Y-m-d');

			$link		= $this->formula->clean(strtolower($title));

			$config['upload_path'] = './images/content/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media = $this->upload->data('pict');
				
			$q 			= "
						insert into services (title,sub,id_menu,headline,picture,link,content) values ('$title','',$menu,'$headline','$fileName','$link','$content')
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

			$cekinglogo	= $_FILES['upl']['name'];
			$id			= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$title		= trim(strip_tags(stripslashes($this->input->post('ed_title',true))));
			$menu		= trim(strip_tags(stripslashes($this->input->post('ed_menu',true))));
			$headline	= trim(strip_tags(stripslashes($this->input->post('ed_content',true))));
			$content	= str_replace("'",'`',$_POST['ed_content']);

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

				$rows = $this->query->updateData('services',"id_menu='$menu', title='$title', link='$link',headline='$headline', content='$content'","WHERE id_services='$id'");
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

			$title		= trim(strip_tags(stripslashes($this->input->post('title',true))));
			$works		= trim(strip_tags(stripslashes($this->input->post('works',true))));
			$services	= trim(strip_tags(stripslashes($this->input->post('service',true))));
			$fileName 	= str_replace(' ','_',time().$_FILES['pict']['name']);
			$date 		= date('Y-m-d');

			$link		= $this->formula->clean(strtolower($title));

			$config['upload_path'] = './images/gallery/'; //buat folder dengan nama assets di root folder
			$config['file_name'] = $fileName;
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			 
			$this->load->library('upload');
			$this->upload->initialize($config);
			 
			if(! $this->upload->do_upload('pict') )
			$this->upload->display_errors();
				 
			$media = $this->upload->data('pict');
				
			$q 			= "
						insert into album (title,id_menu,icon,id_services,link) values ('$title',$works,'$fileName','$services','$link')
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
			$title		= trim(strip_tags(stripslashes($this->input->post('ed_title',true))));
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

	public function generateNoAkun() {
		if(checkingsessionpwt()){
			$id 	 = $_GET['id'];
			
			$cond	= "WHERE category_id='$id'";
			
			$getData = $this->query->getDatabyQ("SELECT max(SUBSTRING(number, 3))+1 as last_no, (select default_code from account_category where id_category= a.category_id ) default_code FROM account a $cond");
			
			header('Content-type: application/json; charset=UTF-8');

			$cek 	= $this->query->getNumRowsbyQ("SELECT max(SUBSTRING(number, 3))+1 as last_no, (select default_code from account_category where id_category=a.category_id ) default_code FROM account a $cond")->num_rows();
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $data) {
					
					
					$row = array(
						'id'		=> $data['default_code'],
						'nomorakun'	=> $data['last_no']
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

	public function getFormAkunDetail(){
		if(checkingsessionpwt()){
			
			$id 	 = $_GET['id'];
			
			$cond	= "WHERE category_id='$id'";
			
			$getData = $this->query->getData('account','*',"$cond ORDER BY number asc");
			
			header('Content-type: application/json; charset=UTF-8');

			$cek 	= $this->query->getNumRowsbyQ("select * from account $cond order by number asc")->num_rows();

			if ($cek>0) {
				// $row[] 	= array('id' => '','text' => '-- Pilih Akun --' );

				foreach($getData as $data) {
					$text 	= '('.$data['number'].') '.$data['name'];
					$row[] 	= array(
						'id'		=> $data['id'],
						'text'		=> $text,
						);
					$json = $row;
					
				}
				$json = $row;
			} else {
				$row  = '';

				$json = $row;
			}
			
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}

	public function getFormAkunDetailParent(){
		if(checkingsessionpwt()){
			
			$kategori 	 = $_GET['id'];
			$eksisid 	 = $_GET['eksisid'];
			
			$cond	= "WHERE category_id='$kategori' and number!='$eksisid'";
			
			$getData = $this->query->getData('account','*',"$cond ORDER BY number asc");
			
			header('Content-type: application/json; charset=UTF-8');

			$cek 	= $this->query->getNumRowsbyQ("select * from account $cond order by number asc")->num_rows();

			if ($cek>0) {
				$row[] 	= array('id' => '0','text' => 'None' );

				foreach($getData as $data) {
					$text 	= '('.$data['number'].') '.$data['name'];
					$row[] 	= array(
						'id'		=> $data['id'],
						'text'		=> $text,
						);
					$json = $row;
					
				}
				$json = $row;
			} else {
				$row  = '';

				$json = $row;
			}
			
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}

	public function modalakun(){
		if(checkingsessionpwt()){
			
			$id				= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$q 				= "
							select * from account where id='".$id."'
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

	public function insertakun(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$id			= $this->db->insert_id();

			$kategori	= trim(strip_tags(stripslashes($this->input->post('kategori',true))));
			$nomor		= trim(strip_tags(stripslashes($this->input->post('nomor_akun',true))));
			$nama		= trim(strip_tags(stripslashes($this->input->post('nama',true))));
			$deskripsi	= trim(strip_tags(stripslashes($this->input->post('deskripsi',true))));
			$detail		= trim(strip_tags(stripslashes($this->input->post('detail',true))));
			$dateCreate = date("Y-m-d H:i:s");
			
			if ($detail == 0) {
				$parent 	= '0';
				$rows 		= $this->query->insertData(
							'account',
							"name,number,description,archive,is_lock,system_or_product_link,indent,is_parent,category_id,balance,balance_amount,created_at", 
							"'$nama','$nomor','$deskripsi','FALSE','FALSE','FALSE','0','TRUE','$kategori','Rp. 0','0','$dateCreate'
							");
			} else if ($detail == 1) {
				$parent 	= $this->input->post('datadetail',true);
				$rows 		= $this->query->insertData(
							'account',
							"name,number,description,archive,is_lock,system_or_product_link,indent,is_parent,parent_id,category_id,balance,balance_amount,created_at", 
							"'$nama','$nomor','$deskripsi','FALSE','FALSE','FALSE','0','FALSE','".$parent[0]."','$kategori','Rp. 0','0','$dateCreate'
							");
			} else {
				$parent 	= $this->input->post('datadetail',true);
				$jmlParent 	= count($parent);
				for($i=0; $i<$jmlParent; $i++) {
					// echo $parent[$i];
					$updateParent = $this->query->updateData('account',"parent_id='$id'","WHERE id='".$parent[$i]."'");
				}
				$rows 		= $this->query->insertData(
							'account',
							"name,number,description,archive,is_lock,system_or_product_link,indent,is_parent,category_id,balance,balance_amount,created_at", 
							"'$nama','$nomor','$deskripsi','FALSE','FALSE','FALSE','0','TRUE','$kategori','Rp. 0','0','$dateCreate'
							");
			}

			$url 		= "Daftar Akun";
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

	public function deleteakun(){
		if(checkingsessionpwt()){
			$url 		= "Daftar Akun";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			$cekParent = $this->query->getNumRows('account','*',"WHERE parent_id='".$cond."'")->num_rows();
			if ($cekParent>0) {
				$coba = $this->query->getData('account','*',"WHERE parent_id='".$cond."'");
				foreach ($coba as $dataex) {
					$updateAkun = $this->query->updateData('account',"parent_id='0'","WHERE parent_id='$cond'");
				}
			}
			
			$rows = $this->query->deleteData2('account','id',$cond);
			
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

	public function updateakun(){
		if(checkingsessionpwt()){
			$id			= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$kategori	= trim(strip_tags(stripslashes($this->input->post('kategori',true))));
			$nomor		= trim(strip_tags(stripslashes($this->input->post('nomor_akun',true))));
			$nama		= trim(strip_tags(stripslashes($this->input->post('nama',true))));
			$deskripsi	= trim(strip_tags(stripslashes($this->input->post('deskripsi',true))));
			$datadetail	= trim(strip_tags(stripslashes($this->input->post('datadetail',true))));
				
			$url 		= "Daftar Akun";
			$activity 	= "UPDATE";
			
			$rows = $this->query->updateData('account',
					"category_id='$kategori', number='$nomor', name='$nama', description='$deskripsi', parent_id='$datadetail'",
					"WHERE id='$id'");

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

	public function getdataakundetail($nomorakun){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'nomor'			=> true,
				'kontak'		=> true,
				'debit'			=> true,
				'kredit'		=> true,
				'saldo'			=> true,
				'updateby'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataakundetail/'.$nomorakun.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdataproduk(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'kode'				=> true,
				'nama'				=> true,
				'qty'				=> true,
				'minimum'			=> true,
				'unit'				=> true,
				'hargabeli_terakhir'=> true,
				'hargabeli'			=> true,
				'harga_jual_sales'	=> true,
				'harga_jual_toko'	=> true,
				'harga_jual_tukang'	=> true,
				'harga_jual_kasir'	=> true,
				'kategori'			=> true,
				'actions'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataproduk';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatatransaksi($type,$id){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'			=> true,
				'nomor'				=> true,
				'tanggaljatuhtempo'	=> true,
				'status'			=> true,
				'jumlah'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datatransaksi/'.$type.'/'.$id.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function apidatatags(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/tags/",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function getapidatatags(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apidatatags();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['tags'] AS $d){
			$id				= $d['id'];
			$name			= $d['name'];

		    $insertData = $this->query->insertData('tags', "id_tags,tags", "'$id','$name'");
			if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
		   	// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function apiakundata(){
		// echo 'asdfa';
		// header('Content-disposition: attachment; filename=account.json');
		// header('Content-type: application/json');
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/accounts?include_archive=true",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apiakunget($id){
		// echo 'asdfa';
		// header('Content-disposition: attachment; filename=account.json');
		// header('Content-type: application/json');
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/accounts/$id",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function cekcompany(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/companies/64264",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apiakungetsingle(){
		// echo 'asdfa';
		// header('Content-disposition: attachment; filename=account.json');
		// header('Content-type: application/json');
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/accounts/7257268",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatacontact(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://api.jurnal.id/core/api/v1/customers?include_archive=true&page_size=978',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatacontactgroup(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/contact_groups",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apigetpurchaseinvoicelist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/purchase_invoices?page_size=711&page=3",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  ECHO $response;
		}
	}

	public function getlistdatapurchaseinvbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpurchaseinvoicelist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['purchase_invoices'] AS $d){ $i++;
			$id 				= $d['id'];
			$type 				= '9';
			$transaction_no 	= str_replace('#','',$d['transaction_no']);
			$selected_po_id 	= $d['selected_po_id'];
			$selected_pq_id 	= $d['selected_pq_id'];
			$email 				= $d['email'];
			$address 			= $d['address'];
			$message 			= $d['message'];
			$memo 				= $d['memo'];
			$remaining 			= $d['remaining'];
			$original_amount 	= $d['original_amount'];
			$shipping_price 	= $d['shipping_price'];
			$shipping_address 	= $d['shipping_address'];
			$is_shipped 		= $d['is_shipped'];
			$ship_via 			= $d['ship_via'];
			$reference_no 		= $d['reference_no'];
			$tracking_no 		= $d['tracking_no'];
			$custom_id 			= $d['custom_id'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	= date('Y-m-d', strtotime($date));
			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			= date('Y-m-d', strtotime($date2));
			$var3 				= $d['shipping_date'];
			$date3 				= str_replace('/', '-', $var3);
			$shipping_date 		= date('Y-m-d', strtotime($date3));
			$has_credit_memos 	= $d['has_credit_memos'];
			$credit_memo_balance= $d['credit_memo_balance'];
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$warehouse 			= $d['warehouse']['id'];
			$term 				= $d['term']['id'];
			$has_payments 		= $d['has_payments'];
			$has_deliveries 	= $d['has_deliveries'];
			$purchase_quote 	= @$d['purchase_quote'][0]['id'];
			$purchase_returns 	= @$d['purchase_returns'][0]['id'];
			// $purchase_invoice 	= @$d['purchase_invoice'][0]['id'];
			$purchase_deliveries= @$d['purchase_deliveries'][0]['id'];
			$credit_memos 		= @$d['credit_memos'][0]['id'];
			$is_reconciled 		= $d['is_reconciled'];
			$created_at 		= $d['created_at'];
			$updated_at 		= $d['updated_at'];

		    $insertData = $this->query->insertData('purchase',
						"
						id,type,transaction_no,selected_po_id,selected_pq_id,email,address,message,memo,remaining,original_amount,shipping_price,shipping_address,is_shipped,ship_via,reference_no,tracking_no,custom_id,deleted_at,deletable,editable,audited_by,transaction_date,due_date,shipping_date,has_credit_memos,credit_memo_balance,transaction_status,person,warehouse,term,has_payments,has_deliveries,purchase_quote,purchase_returns,purchase_deliveries,credit_memos,is_reconciled,created_at,updated_at
						",
						"
						 '$id', '$type', '$transaction_no', '$selected_po_id', '$selected_pq_id', '$email', '$address', '$message', '$memo', 
						 '$remaining', '$original_amount', '$shipping_price', '$shipping_address', '$is_shipped', '$ship_via', '$reference_no', 
						 '$tracking_no', '$custom_id', '$deleted_at', '$deletable', '$editable', '$audited_by', '$transaction_date', '$due_date', 
						 '$shipping_date', '$has_credit_memos', '$credit_memo_balance', '$transaction_status', '$person', '$warehouse', '$term', 
						 '$has_payments', '$has_deliveries', '$purchase_quote', '$purchase_returns', '$purchase_deliveries', 
						 '$credit_memos', '$is_reconciled', '$created_at', '$updated_at'
						");

			// $insertData = $this->query->insertData('transactions',
			// 			"
			// 			id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
			// 			",
			// 			"'','$id','$transaction_no','$type','$transaction_date','$due_date','7257268','$remaining','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatapurchaseinvbasedetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpurchaseinvoicelist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['purchase_invoices'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_lines_attributes'] AS $dd){
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				$amount			= $dd['amount'];
				$discount		= $dd['discount'];
				$rate			= $dd['rate'];
				$tax 			= $dd['tax'];
				$line_tax		= $dd['line_tax']['id'];
				$has_return_line= $dd['has_return_line'];
				$quantity 		= $dd['quantity'];
				$product		= $dd['product']['id'];

				// $rows 	= $this->query->deleteData('purchase_detail','id',$id);
			    $insertData = $this->query->insertData('purchase_detail',
						"
						id,transaction_id,description,amount,discount,rate,tax,line_tax,has_return_line,quantity,product
						",
						"'$id','$transaction_id','$description','$amount','$discount','$rate','$tax','$line_tax','$has_return_line','$quantity','$product'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetpurchaseorderlist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/purchase_orders",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function getlistdatapurchaseorderbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpurchaseorderlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['purchase_orders'] AS $d){ $i++;
			$id 				= $d['id'];
			$type 				= '16';
			$transaction_no 	= str_replace('#','',$d['transaction_no']);
			// $selected_po_id 	= $d['selected_po_id'];
			// $selected_pq_id 	= $d['selected_pq_id'];
			$email 				= $d['email'];
			$address 			= $d['address'];
			$message 			= $d['message'];
			$memo 				= $d['memo'];
			$remaining 			= $d['remaining'];
			$original_amount 	= $d['original_amount'];
			$shipping_price 	= $d['shipping_price'];
			$shipping_address 	= $d['shipping_address'];
			$is_shipped 		= $d['is_shipped'];
			$ship_via 			= $d['ship_via'];
			$reference_no 		= $d['reference_no'];
			$tracking_no 		= $d['tracking_no'];
			$custom_id 			= $d['custom_id'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	= date('Y-m-d', strtotime($date));
			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			= date('Y-m-d', strtotime($date2));
			$var3 				= $d['shipping_date'];
			$date3 				= str_replace('/', '-', $var3);
			$shipping_date 		= date('Y-m-d', strtotime($date3));
			// $has_credit_memos 	= $d['has_credit_memos'];
			// $credit_memo_balance= $d['credit_memo_balance'];
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$warehouse 			= $d['warehouse']['id'];
			$term 				= $d['term']['id'];
			// $has_payments 		= $d['has_payments'];
			// $has_deliveries 	= $d['has_deliveries'];
			$purchase_quote 	= @$d['purchase_quote'][0]['id'];
			// $purchase_returns 	= @$d['purchase_returns']['id'];
			$purchase_invoice 	= @$d['invoices'][0]['id'];
			$purchase_deliveries= @$d['deliveries'][0]['id'];
			$credit_memos 		= @$d['credit_memos'][0]['id'];
			$is_reconciled 		= $d['is_reconciled'];
			$created_at 		= $d['created_at'];
			$updated_at 		= $d['updated_at'];
			$tags 				= @$d['tags'][0]['id'];

		    $insertData = $this->query->insertData('purchase',
						"
						id,type,transaction_no,email,address,message,memo,remaining,original_amount,shipping_price,shipping_address,is_shipped,ship_via,reference_no,tracking_no,custom_id,deleted_at,deletable,editable,audited_by,transaction_date,due_date,shipping_date,transaction_status,person,warehouse,term,purchase_invoice,purchase_quote,purchase_deliveries,credit_memos,is_reconciled,created_at,updated_at,tags
						",
						"
						 '$id', '$type', '$transaction_no', '$email', '$address', '$message', '$memo', 
						 '$remaining', '$original_amount', '$shipping_price', '$shipping_address', '$is_shipped', '$ship_via', '$reference_no', 
						 '$tracking_no', '$custom_id', '$deleted_at', '$deletable', '$editable', '$audited_by', '$transaction_date', '$due_date', 
						 '$shipping_date', '$transaction_status', '$person', '$warehouse', '$term', 
						 '$purchase_invoice','$purchase_quote', '$purchase_deliveries', 
						 '$credit_memos', '$is_reconciled', '$created_at', '$updated_at', '$tags'
						");


			// $insertData = $this->query->insertData('transactions',
			// 			"
			// 			id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
			// 			",
			// 			"'','$id','$transaction_no','$type','$transaction_date','$due_date','7257268','$remaining','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatapurchaseorderdetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpurchaseorderlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['purchase_orders'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_lines_attributes'] AS $dd){
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				$amount			= $dd['amount'];
				$discount		= $dd['discount'];
				$rate			= $dd['rate'];
				$tax 			= $dd['tax'];
				$line_tax		= $dd['line_tax']['id'];
				$has_return_line= $dd['has_return_line'];
				$quantity 		= $dd['quantity'];
				$product		= $dd['product']['id'];

			    $insertData = $this->query->insertData('purchase_detail',
						"
						id,transaction_id,description,amount,discount,rate,tax,line_tax,has_return_line,quantity,product
						",
						"'$id','$transaction_id','$description','$amount','$discount','$rate','$tax','$line_tax','$has_return_line','$quantity','$product'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetpurchasedeliverylist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/purchase_deliveries",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function getlistdatapurchasedelivbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpurchasedeliverylist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['purchase_deliveries'] AS $d){ $i++;
			$id 				= $d['id'];
			$type 				= '11';
			$transaction_no 	= str_replace('#','',$d['transaction_no']);
			$selected_po_id 	= $d['selected_po_id'];
			// $selected_pq_id 	= $d['selected_pq_id'];
			$email 				= $d['email'];
			$address 			= $d['address'];
			$message 			= $d['message'];
			$memo 				= $d['memo'];
			// $remaining 			= $d['remaining'];
			// $original_amount 	= $d['original_amount'];
			$shipping_price 	= $d['shipping_price'];
			$shipping_address 	= $d['shipping_address'];
			$is_shipped 		= $d['is_shipped'];
			$ship_via 			= $d['ship_via'];
			$reference_no 		= $d['reference_no'];
			$tracking_no 		= $d['tracking_no'];
			$custom_id 			= $d['custom_id'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	= date('Y-m-d', strtotime($date));
			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			= date('Y-m-d', strtotime($date2));
			$var3 				= $d['shipping_date'];
			$date3 				= str_replace('/', '-', $var3);
			$shipping_date 		= date('Y-m-d', strtotime($date3));
			// $has_credit_memos 	= $d['has_credit_memos'];
			// $credit_memo_balance= $d['credit_memo_balance'];
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$warehouse 			= $d['warehouse']['id'];
			// $term 				= $d['term']['id'];
			// $has_payments 		= $d['has_payments'];
			// $has_deliveries 	= $d['has_deliveries'];
			$purchase_quote 	= @$d['purchase_quote'][0]['id'];
			// $purchase_returns 	= @$d['purchase_returns']['id'];
			$purchase_invoice 	= @$d['purchase_invoice']['id'];
			$purchase_deliveries= @$d['purchase_deliveries'][0]['id'];
			$credit_memos 		= @$d['credit_memos'][0]['id'];
			// $is_reconciled 		= $d['is_reconciled'];
			$created_at 		= $d['created_at'];
			$updated_at 		= $d['updated_at'];
			$tags 				= @$d['tags'][0]['id'];

		    $insertData = $this->query->insertData('purchase',
						"
						id,type,transaction_no,selected_po_id,email,address,message,memo,shipping_price,shipping_address,is_shipped,ship_via,reference_no,tracking_no,custom_id,deleted_at,deletable,editable,audited_by,transaction_date,due_date,shipping_date,transaction_status,person,warehouse,purchase_invoice,purchase_quote,purchase_deliveries,credit_memos,created_at,updated_at, tags
						",
						"
						 '$id', '$type', '$transaction_no', '$selected_po_id', '$email', '$address', '$message', '$memo', 
						 '$shipping_price', '$shipping_address', '$is_shipped', '$ship_via', '$reference_no', 
						 '$tracking_no', '$custom_id', '$deleted_at', '$deletable', '$editable', '$audited_by', '$transaction_date', '$due_date', 
						 '$shipping_date', '$transaction_status', '$person', '$warehouse',
						 '$purchase_invoice','$purchase_quote', '$purchase_deliveries', 
						 '$credit_memos', '$created_at', '$updated_at', '$tags'
						");


			// $insertData = $this->query->insertData('transactions',
			// 			"
			// 			id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
			// 			",
			// 			"'','$id','$transaction_no','$type','$transaction_date','$due_date','7257268','$shipping_price','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatapurchasedelivdetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpurchasedeliverylist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['purchase_deliveries'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_lines_attributes'] AS $dd){
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				// $amount			= $dd['amount'];
				// $discount		= $dd['discount'];
				// $rate			= $dd['rate'];
				// $tax 			= $dd['tax'];
				// $line_tax		= $dd['line_tax']['id'];
				$has_return_line= $dd['has_return_line'];
				$quantity 		= $dd['quantity'];
				$product		= $dd['product']['id'];

			    $insertData = $this->query->insertData('purchase_detail',
						"
						id,transaction_id,description,has_return_line,quantity,product
						",
						"'$id','$transaction_id','$description','$has_return_line','$quantity','$product'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetpurchasequotelist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/purchase_quotes",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apigetpurchasereturnlist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/purchase_returns",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function getlistdatapurchasereturnbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpurchasereturnlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['purchase_returns'] AS $d){ $i++;
			$id 				= $d['id'];
			$type 				= '18';
			$transaction_no 	= str_replace('#','',$d['transaction_no']);
			// $selected_po_id 	= $d['selected_po_id'];
			// $selected_pq_id 	= $d['selected_pq_id'];
			$email 				= $d['email'];
			$address 			= $d['address'];
			$message 			= $d['message'];
			$memo 				= $d['memo'];
			// $remaining 			= $d['remaining'];
			$original_amount 	= $d['return_amount'];
			// $shipping_price 	= $d['shipping_price'];
			$shipping_address 	= $d['shipping_address'];
			// $is_shipped 		= $d['is_shipped'];
			// $ship_via 			= $d['ship_via'];
			// $reference_no 		= $d['reference_no'];
			// $tracking_no 		= $d['tracking_no'];
			$custom_id 			= $d['custom_id'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	= date('Y-m-d', strtotime($date));
			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			= date('Y-m-d', strtotime($date2));
			$var3 				= $d['shipping_date'];
			$date3 				= str_replace('/', '-', $var3);
			$shipping_date 		= date('Y-m-d', strtotime($date3));
			// $has_credit_memos 	= $d['has_credit_memos'];
			// $credit_memo_balance= $d['credit_memo_balance'];
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$warehouse 			= $d['warehouse']['id'];
			// $term 				= $d['term']['id'];
			// $has_payments 		= $d['has_payments'];
			// $has_deliveries 	= $d['has_deliveries'];
			// $purchase_quote 	= @$d['purchase_quote']['id'];
			// $purchase_returns 	= @$d['purchase_returns']['id'];
			$purchase_invoice 	= @$d['invoice_id'];
			// $purchase_deliveries= @$d['deliveries']['id'];
			// $credit_memos 		= @$d['credit_memos']['id'];
			$is_reconciled 		= $d['is_reconciled'];
			$created_at 		= $d['created_at'];
			$updated_at 		= $d['updated_at'];

		    // $insertData = $this->query->insertData('purchase',
						// "
						// id,type,transaction_no,email,address,message,memo,original_amount,shipping_address,custom_id,deleted_at,deletable,editable,audited_by,transaction_date,due_date,shipping_date,transaction_status,person,warehouse,purchase_invoice,created_at,updated_at
						// ",
						// "
						//  '$id', '$type', '$transaction_no', '$email', '$address', '$message', '$memo', '$original_amount',
						//  '$shipping_address', 
						//  '$custom_id', '$deleted_at', '$deletable', '$editable', '$audited_by', '$transaction_date', '$due_date', 
						//  '$shipping_date', '$transaction_status', '$person', '$warehouse',
						//  '$purchase_invoice', 
						//  '$created_at', '$updated_at'
						// ");


			$insertData = $this->query->insertData('transactions',
						"
						id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
						",
						"'','$id','$transaction_no','$type','$transaction_date','$due_date','7257268','$original_amount','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatapurchasereturndetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpurchasereturnlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['purchase_returns'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_return_lines_attributes'] AS $dd){

				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				// $description	= str_replace("'","`",$dd['description']);
				$amount			= $dd['line_return_amount'];
				// $discount		= $dd['discount'];
				// $rate			= $dd['rate'];
				// $tax 			= $dd['tax'];
				// $line_tax		= $dd['line_tax']['id'];
				// $has_return_line= $dd['has_return_line'];
				$quantity 		= $dd['return_quantity'];
				$product		= $dd['transaction_line_id'];

			    $insertData = $this->query->insertData('purchase_detail',
						"
						id,transaction_id,amount,quantity,product
						",
						"'$id','$transaction_id','$amount','$quantity','$product'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetpurchasepaymentlist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/purchase_payments?page_size=874",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  ECHO $response;
		}
	}

	public function getlistdatapurchasepaymentbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpurchasepaymentlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['purchase_payments'] AS $d){ $i++;
			$id   				= $d['id'];
			$transaction_no   	= $d['transaction_no'];
			$type   			= @$d['transaction_type']['id'];
			$token    			= $d['token'];
			$memo   			= $d['memo'];
			$source   			= $d['source'];
			$custom_id    		= $d['custom_id'];
			$transaction_status = $d['transaction_status']['id'];
			$deleted_at   		= $d['deleted_at'];
			$deletable    		= $d['deletable'];
			$editable   		= $d['editable'];
			$audited_by   		= $d['audited_by'];
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	= date('Y-m-d', strtotime($date));
			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			= date('Y-m-d', strtotime($date2));
			$person   			= $d['person']['id'];
			$payment_method   	= $d['payment_method']['id'];
			$refund_from    	= $d['refund_from']['id'];
			$is_draft   		= $d['is_draft'];
			$witholding   		= $d['witholding']['account_id'];
			$original_amount    = $d['original_amount'];
			$total    			= $d['total'];
			$attachments    	= @$d['attachments']['id'];
			$is_reconciled    	= $d['is_reconciled'];
			$tags   			= @$d['tags']['id'];
			$created_at   		= $d['created_at'];
			$updated_at   		= $d['updated_at'];

			$cekdata 	= $this->query->getNumRowsbyQ("select * from purchase_payment where id=$id")->num_rows();

			if ($cekdata>0) {
				$q 			= "
							update purchase_payment set 
							id='$id',type='$type', transaction_no='$transaction_no', token='$token', memo='$memo', source='$source', custom_id='$custom_id', transaction_status='$transaction_status', deleted_at='$deleted_at', deletable='$deletable', editable='$editable', audited_by='$audited_by', transaction_date='$transaction_date', due_date='$due_date', person='$person', payment_method='$payment_method', refund_from='$refund_from', is_draft='$is_draft', witholding='$witholding', original_amount='$original_amount', total='$total', attachments='$attachments', is_reconciled='$is_reconciled', tags='$tags', created_at='$created_at', updated_at='$updated_at'	
							where id=$id
							";
				$insertData 		= $this->query->insertDatabyQ($q);
			} else {
				$insertData = $this->query->insertData('purchase_payment',
						"
						id,type, transaction_no, token, memo, source, custom_id, transaction_status, deleted_at, deletable, editable, audited_by, transaction_date, due_date, person, payment_method, refund_from, is_draft, witholding, original_amount, total, attachments, is_reconciled, tags, created_at, updated_at
						",
						"
						 '$id', '$type', '$transaction_no', '$token', '$memo', '$source', '$custom_id', '$transaction_status', '$deleted_at', '$deletable', '$editable', '$audited_by', '$transaction_date', '$due_date', '$person', '$payment_method', '$refund_from', '$is_draft', '$witholding', '$original_amount', '$total', '$attachments', '$is_reconciled', '$tags', '$created_at', '$updated_at'
						");
			}


			// $insertData = $this->query->insertData('transactions',
			// 			"
			// 			id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
			// 			",
			// 			"'','$id','$transaction_no','$type','$transaction_date','$due_date','7257268','$original_amount','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatapurchasepaymentdetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpurchasepaymentlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['purchase_payments'] AS $d){
			$payment_id 	= $d['id'];
			foreach ($d['records'] AS $dd){

				$id						= $dd['id'];
				$payment_id				= $payment_id;
				$transaction_id			= $dd['transaction_id'];
				$description			= str_replace("'","`",$dd['description']);
				$amount					= $dd['amount'];
				$transaction_type_id	= $dd['transaction_type_id'];
				$transaction_no			= str_replace("#","",$dd['transaction_no']);
				$var 					= $dd['transaction_due_date'];
				$date 					= str_replace('/', '-', $var);
				$transaction_due_date 	= date('Y-m-d', strtotime($date));
				$transaction_total 		= $dd['transaction_total'];
				$transaction_balance_due= $dd['transaction_balance_due'];

				$cekdata 	= $this->query->getNumRowsbyQ("select * from purchase_payment_detail where id=$id")->num_rows();

				if ($cekdata>0) {
					$q 			= "
								update purchase_payment_detail set 
								id='$id', payment_id='$payment_id', transaction_id='$transaction_id', description='$description', amount='$amount', transaction_type_id='$transaction_type_id', transaction_no='$transaction_no', transaction_due_date='$transaction_due_date', transaction_total='$transaction_total', transaction_balance_due='$transaction_balance_due'
								where id=$id
								";
					$insertData = $this->query->insertDatabyQ($q);
				} else {
					$insertData = $this->query->insertData('purchase_payment_detail',
						"
						id,payment_id,transaction_id,description,amount,transaction_type_id,transaction_no,transaction_due_date,transaction_total,transaction_balance_due
						",
						"
						'$id', '$payment_id', '$transaction_id', '$description', '$amount', '$transaction_type_id', '$transaction_no', '$transaction_due_date', '$transaction_total', '$transaction_balance_due'
						");	
				}
			    
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetpurchaseorderpaymentlist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/purchase_order_payments?page_size=868",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apigetreceivepaymentslist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/receive_payments?page_size=1387&page=5",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function getlistdatareceivepaymentbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetreceivepaymentslist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['receive_payments'] AS $d){ $i++;
			$id     			= $d['id'];
			$transaction_no     = $d['transaction_no'];
			$token      		= $d['token'];
			$memo     			= $d['memo'];
			$source     		= $d['source'];
			$custom_id      	= $d['custom_id'];
			$transaction_status = $d['transaction_status']['id'];
			$deleted_at     	= $d['deleted_at'];
			$deletable      	= $d['deletable'];
			$editable     		= $d['editable'];
			$audited_by     	= $d['audited_by'];
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	= date('Y-m-d', strtotime($date));
			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			= date('Y-m-d', strtotime($date2));
			$person     		= $d['person']['id'];
			$transaction_type   = $d['transaction_type']['id'];
			$payment_method     = $d['payment_method']['id'];
			$deposit_to     	= $d['deposit_to']['id'];
			$is_draft     		= $d['is_draft'];
			$witholding     	= $d['witholding']['account_id'];
			$original_amount    = $d['original_amount'];
			$total      		= $d['total'];
			$is_reconciled      = $d['is_reconciled'];
			$attachments      	= @$d['attachments']['id'];
			$tags     			= @$d['tags'][0]['id'];
			$created_at     	= $d['created_at'];
			$updated_at     	= $d['updated_at'];

		    $insertData = $this->query->insertData('receive_payment',
						"
						id, transaction_no, token, memo, source, custom_id, transaction_status, deleted_at, deletable, editable, audited_by, transaction_date, due_date, person, transaction_type, payment_method, deposit_to, is_draft, witholding, original_amount, total, is_reconciled, attachments, tags, created_at, updated_at
						",
						"
						 '$id', '$transaction_no', '$token', '$memo', '$source', '$custom_id', '$transaction_status', '$deleted_at', '$deletable', '$editable', '$audited_by', '$transaction_date', '$due_date', '$person', '$transaction_type', '$payment_method', '$deposit_to', '$is_draft', '$witholding', '$original_amount', '$total', '$is_reconciled', '$attachments', '$tags', '$created_at', '$updated_at'
						");
		    // echo "insert into receive_payment
		    // ()
		    // values
		    // ()<br><br>";


			// $insertData = $this->query->insertData('transactions',
			// 			"
			// 			id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
			// 			",
			// 			"'','$id','$transaction_no','$type','$transaction_date','$due_date','7257268','$original_amount','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatareceivepaymentdetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetreceivepaymentslist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['receive_payments'] AS $d){
			$receive_id 	= $d['id'];
			foreach ($d['records'] AS $dd){
				$id						= $dd['id'];
				$payment_id				= $receive_id;
				$transaction_id			= $dd['transaction_id'];
				$description			= str_replace("'","`",$dd['description']);
				$amount					= $dd['amount'];
				$transaction_type_id	= $dd['transaction_type_id'];
				$transaction_no			= $dd['transaction_no'];
				$var 					= $dd['transaction_due_date'];
				$date 					= str_replace('/', '-', $var);
				$transaction_due_date 	= date('Y-m-d', strtotime($date));
				$transaction_total 		= $dd['transaction_total'];
				$transaction_balance_due= $dd['transaction_balance_due'];

				// $rows 	= $this->query->deleteData('receive_payment_detail','id',$id);
			    $insertData = $this->query->insertData('receive_payment_detail',
						"
						id,receive_id,transaction_id,description,amount,transaction_type_id,transaction_no,transaction_due_date,transaction_total,transaction_balance_due
						",
						"
						'$id', '$payment_id', '$transaction_id', '$description', '$amount', '$transaction_type_id', '$transaction_no', '$transaction_due_date', '$transaction_total', '$transaction_balance_due'
						");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetsalesquoteslist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/sales_quotes",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function getlistdatasalesquotebase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetsalesquoteslist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['sales_quotes'] AS $d){ $i++;
			$id 				= $d['id'];
			$type 				= '20';
			$transaction_no 	= str_replace('#','',$d['transaction_no']);
			$selected_po_id 	= $d['selected_po_id'];
			$selected_pq_id 	= $d['selected_pq_id'];
			$email 				= $d['email'];
			$address 			= str_replace("'",'`',$d['address']);
			$message 			= str_replace("'",'`',$d['message']);
			$memo 				= str_replace("'",'`',$d['memo']);
			$remaining 			= $d['remaining'];
			$original_amount 	= $d['original_amount'];
			$shipping_price 	= $d['shipping_price'];
			$shipping_address 	= $d['shipping_address'];
			$is_shipped 		= $d['is_shipped'];
			$ship_via 			= $d['ship_via'];
			$reference_no 		= $d['reference_no'];
			$tracking_no 		= $d['tracking_no'];
			$custom_id 			= $d['custom_id'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	= date('Y-m-d', strtotime($date));
			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			= date('Y-m-d', strtotime($date2));
			$var3 				= $d['shipping_date'];
			$date3 				= str_replace('/', '-', $var3);
			$shipping_date 		= date('Y-m-d', strtotime($date3));
			$has_credit_memos 	= $d['has_credit_memos'];
			$credit_memo_balance= $d['credit_memo_balance'];
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$warehouse 			= round($d['warehouse']['id']);
			$term 				= $d['term']['id'];
			$has_payments 		= $d['has_payments'];
			$has_deliveries 	= $d['has_deliveries'];
			$quote 				= @round($d['sales_quote']['id']);
			$returns 			= @round($d['sales_returns'][0]['id']);
			// $purchase_invoice 	= @$d['purchase_invoice']['id'];
			$deliveries			= @round($d['sales_deliveries'][0]['id']);
			$credit_memos 		= @round($d['credit_memos'][0]['id']);
			$is_reconciled 		= $d['is_reconciled'];
			$tags 				= @round($d['tags'][0]['id']);
			$created_at 		= str_replace('.000Z','',$d['created_at']);
			$updated_at 		= str_replace('.000Z','',$d['updated_at']);

		    $insertData = $this->query->insertData('sales',
						"
						id,type,transaction_no,selected_po_id,selected_pq_id,email,address,message,memo,remaining,original_amount,shipping_price,shipping_address,is_shipped,ship_via,reference_no,tracking_no,custom_id,deleted_at,deletable,editable,audited_by,transaction_date,due_date,shipping_date,has_credit_memos,credit_memo_balance,transaction_status,person,warehouse,term,has_payments,has_deliveries,quote,returns,deliveries,credit_memos,is_reconciled,created_at,updated_at,tags
						",
						"
						 '$id', '$type', '$transaction_no', '$selected_po_id', '$selected_pq_id', '$email', '$address', '$message', '$memo', 
						 '$remaining', '$original_amount', '$shipping_price', '$shipping_address', '$is_shipped', '$ship_via', '$reference_no', 
						 '$tracking_no', '$custom_id', '$deleted_at', '$deletable', '$editable', '$audited_by', '$transaction_date', '$due_date', 
						 '$shipping_date', '$has_credit_memos', '$credit_memo_balance', '$transaction_status', '$person', '$warehouse', '$term', 
						 '$has_payments', '$has_deliveries', '$quote', '$returns', '$deliveries', 
						 '$credit_memos', '$is_reconciled', '$created_at', '$updated_at', '$tags'
						");

			// $insertData = $this->query->insertData('transactions',
			// 			"
			// 			id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
			// 			",
			// 			"'','$id','$transaction_no','$type','$transaction_date','$due_date','7257268','$remaining','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatasalesquotedetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetsalesquoteslist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['sales_quotes'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_lines_attributes'] AS $dd){
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				$amount			= $dd['amount'];
				$discount		= $dd['discount'];
				$rate			= $dd['rate'];
				$tax 			= $dd['tax'];
				$line_tax		= $dd['line_tax']['id'];
				$has_return_line= $dd['has_return_line'];
				$quantity 		= $dd['quantity'];
				$product		= $dd['product']['id'];

			    $insertData = $this->query->insertData('sales_detail',
						"
						id,transaction_id,description,amount,discount,rate,tax,line_tax,has_return_line,quantity,product
						",
						"'$id','$transaction_id','$description','$amount','$discount','$rate','$tax','$line_tax','$has_return_line','$quantity','$product'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetsalesinvlist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/sales_invoices?page_size=461&page=1",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function getlistdatasalesinvbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetsalesinvlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['sales_invoices'] AS $d){ $i++;
			$id 				= $d['id'];
			$gtype 				= @$d['transaction_type']['id'];
			if (!empty($gtype)) {
				$type 			= @$d['transaction_type']['id'];
			} else {
				$type 			= '1';
			}
			$transaction_no 	= str_replace('#','',$d['transaction_no']);
			$selected_po_id 	= $d['selected_po_id'];
			$selected_pq_id 	= $d['selected_pq_id'];
			$email 				= $d['email'];
			$address 			= str_replace("'",'`',$d['address']);
			$message 			= str_replace("'",'`',$d['message']);
			$memo 				= str_replace("'",'`',$d['memo']);
			$remaining 			= $d['remaining'];
			$original_amount 	= $d['original_amount'];
			$shipping_price 	= $d['shipping_price'];
			$shipping_address 	= $d['shipping_address'];
			$is_shipped 		= $d['is_shipped'];
			$ship_via 			= $d['ship_via'];
			$reference_no 		= $d['reference_no'];
			$tracking_no 		= $d['tracking_no'];
			$custom_id 			= $d['custom_id'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	= date('Y-m-d', strtotime($date));
			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			= date('Y-m-d', strtotime($date2));
			$var3 				= $d['shipping_date'];
			$date3 				= str_replace('/', '-', $var3);
			$shipping_date 		= date('Y-m-d', strtotime($date3));
			$has_credit_memos 	= $d['has_credit_memos'];
			$credit_memo_balance= $d['credit_memo_balance'];
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$warehouse 			= round($d['warehouse']['id']);
			$term 				= $d['term']['id'];
			$has_payments 		= $d['has_payments'];
			$has_deliveries 	= $d['has_deliveries'];
			$quote 				= @round($d['sales_quote']['id']);
			$returns 			= @round($d['sales_returns'][0]['id']);
			// $purchase_invoice 	= @$d['purchase_invoice']['id'];
			$deliveries			= @round($d['sales_deliveries'][0]['id']);
			$credit_memos 		= @round($d['credit_memos'][0]['id']);
			$is_reconciled 		= $d['is_reconciled'];
			$tags 				= @round($d['tags'][0]['id']);
			$created_at 		= str_replace('.000Z','',$d['created_at']);
			$updated_at 		= str_replace('.000Z','',$d['updated_at']);

			$cekdata 	= $this->query->getNumRowsbyQ("select * from sales where id=$id")->num_rows();

			if ($cekdata>0) {
				$q 			= "
							update sales set 
							id='id', type='type', transaction_no='transaction_no', selected_po_id='selected_po_id', selected_pq_id='selected_pq_id', email='email', address='address', message='message', memo='memo', remaining='remaining', original_amount='original_amount', shipping_price='shipping_price', shipping_address='shipping_address', is_shipped='is_shipped', ship_via='ship_via', reference_no='reference_no', tracking_no='tracking_no', custom_id='custom_id', deleted_at='deleted_at', deletable='deletable', editable='editable', audited_by='audited_by', transaction_date='transaction_date', due_date='due_date', shipping_date='shipping_date', has_credit_memos='has_credit_memos', credit_memo_balance='credit_memo_balance', transaction_status='transaction_status', person='person', warehouse='warehouse', term='term', has_payments='has_payments', has_deliveries='has_deliveries', quote='quote', returns='returns', deliveries='deliveries', credit_memos='credit_memos', is_reconciled='is_reconciled', created_at='created_at', updated_at='updated_at', tags='$tags'
							where id=$id
							";
			    $insertData = $this->query->insertDatabyQ($q);
			} else {
			 	$insertData = $this->query->insertData('sales',
							"
							id,type,transaction_no,selected_po_id,selected_pq_id,email,address,message,memo,remaining,original_amount,shipping_price,shipping_address,is_shipped,ship_via,reference_no,tracking_no,custom_id,deleted_at,deletable,editable,audited_by,transaction_date,due_date,shipping_date,has_credit_memos,credit_memo_balance,transaction_status,person,warehouse,term,has_payments,has_deliveries,quote,returns,deliveries,credit_memos,is_reconciled,created_at,updated_at,tags
							",
							"
							 '$id', '$type', '$transaction_no', '$selected_po_id', '$selected_pq_id', '$email', '$address', '$message', '$memo', 
							 '$remaining', '$original_amount', '$shipping_price', '$shipping_address', '$is_shipped', '$ship_via', '$reference_no', 
							 '$tracking_no', '$custom_id', '$deleted_at', '$deletable', '$editable', '$audited_by', '$transaction_date', '$due_date', 
							 '$shipping_date', '$has_credit_memos', '$credit_memo_balance', '$transaction_status', '$person', '$warehouse', '$term', 
							 '$has_payments', '$has_deliveries', '$quote', '$returns', '$deliveries', 
							 '$credit_memos', '$is_reconciled', '$created_at', '$updated_at', '$tags'
							");
			}

			$transaction_id 	= $d['id'];

			foreach ($d['transaction_lines_attributes'] AS $dd){
				$id2				= $dd['id'];
				$transaction_id2	= $transaction_id;
				$description2		= str_replace("'","`",$dd['description']);
				$amount2			= $dd['amount'];
				$discount2			= $dd['discount'];
				$rate2				= $dd['rate'];
				$tax2 				= $dd['tax'];
				$line_tax2			= $dd['line_tax']['id'];
				$has_return_line2	= $dd['has_return_line'];
				$quantity2 			= $dd['quantity'];
				$product2			= $dd['product']['id'];

				$cekdata2 			= $this->query->getNumRowsbyQ("select * from sales_detail where id=$id2")->num_rows();

				if ($cekdata2>0) {
					$q 				= "
									update sales_detail set
									id='$id2', transaction_id='$transaction_id2', description='$description2', amount='$amount2', discount='$discount2', rate='$rate2', tax='$tax2', line_tax='$line_tax2', has_return_line='$has_return_line2', quantity='$quantity2', product='$product2'
									where id=$id2
									";
					$insertDataD 	= $this->query->insertDatabyQ($q);
				} else {
					$insertDataD 	= $this->query->insertData('sales_detail',
						"
						id,transaction_id,description,amount,discount,rate,tax,line_tax,has_return_line,quantity,product
						",
						"'$id2','$transaction_id2','$description2','$amount2','$discount2','$rate2','$tax2','$line_tax2','$has_return_line2','$quantity2','$product2'");
				}
				if ($insertDataD) { echo 'sukesD = '.$id2.'<br>'; } else { echo 'gagalD = '.$id2.'<br>'; }
			}


			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatasalesinvdetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetsalesinvlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['sales_invoices'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_lines_attributes'] AS $dd){
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				$amount			= $dd['amount'];
				$discount		= $dd['discount'];
				$rate			= $dd['rate'];
				$tax 			= $dd['tax'];
				$line_tax		= $dd['line_tax']['id'];
				$has_return_line= $dd['has_return_line'];
				$quantity 		= $dd['quantity'];
				$product		= $dd['product']['id'];

				// $rows 	= $this->query->deleteData('sales_detail','id',$id);
			    $insertData = $this->query->insertData('sales_detail',
						"
						id,transaction_id,description,amount,discount,rate,tax,line_tax,has_return_line,quantity,product
						",
						"'$id','$transaction_id','$description','$amount','$discount','$rate','$tax','$line_tax','$has_return_line','$quantity','$product'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetsalesorderlist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/sales_orders",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  ECHO $response;
		}
	}

	public function getlistdatasalesorderbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetsalesorderlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['sales_orders'] AS $d){ $i++;
			$id 				= $d['id'];
			$type 				= '3';
			$transaction_no 	= str_replace('#','',$d['transaction_no']);
			$email 				= $d['email'];
			$address 			= str_replace("'",'`',$d['address']);
			$message 			= str_replace("'",'`',$d['message']);
			$memo 				= str_replace("'",'`',$d['memo']);
			$remaining 			= $d['remaining'];
			$original_amount 	= $d['original_amount'];
			$shipping_price 	= $d['shipping_price'];
			$shipping_address 	= $d['shipping_address'];
			$is_shipped 		= $d['is_shipped'];
			$ship_via 			= $d['ship_via'];
			$reference_no 		= $d['reference_no'];
			$tracking_no 		= $d['tracking_no'];
			$custom_id 			= $d['custom_id'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	= date('Y-m-d', strtotime($date));
			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			= date('Y-m-d', strtotime($date2));
			$var3 				= $d['shipping_date'];
			$date3 				= str_replace('/', '-', $var3);
			$shipping_date 		= date('Y-m-d', strtotime($date3));
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$warehouse 			= round($d['warehouse']['id']);
			$term 				= $d['term']['id'];
			$deliveries 		= @round($d['deliveries'][0]['id']);
			$quote 				= @round($d['sales_quote'][0]['id']);
			$invoices 			= @round($d['invoices'][0]['id']);
			$is_reconciled 		= $d['is_reconciled'];
			$tags 				= @round($d['tags'][0]['id']);
			$created_at 		= str_replace('.000Z','',$d['created_at']);
			$updated_at 		= str_replace('.000Z','',$d['updated_at']);

		    $insertData = $this->query->insertData('sales',
						"
						id,type,transaction_no,email,address,message,memo,original_amount,shipping_price,shipping_address,is_shipped,ship_via,reference_no,tracking_no,custom_id,deleted_at,deletable,editable,audited_by,transaction_date,due_date,shipping_date,transaction_status,person,warehouse,term,quote,invoice,deliveries,is_reconciled,created_at,updated_at,tags
						",
						"
						 '$id', '$type', '$transaction_no', '$email', '$address', '$message', '$memo', 
						 '$original_amount', '$shipping_price', '$shipping_address', '$is_shipped', '$ship_via', '$reference_no', 
						 '$tracking_no', '$custom_id', '$deleted_at', '$deletable', '$editable', '$audited_by', '$transaction_date', '$due_date', 
						 '$shipping_date', '$transaction_status', '$person', '$warehouse', '$term', 
						 '$quote', '$invoices', '$deliveries',
						 '$is_reconciled', '$created_at', '$updated_at', '$tags'
						");

			// $insertData = $this->query->insertData('transactions',
			// 			"
			// 			id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
			// 			",
			// 			"'','$id','$transaction_no','$type','$transaction_date','$due_date','7257268','$remaining','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatasalesorderdetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetsalesorderlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['sales_orders'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_lines_attributes'] AS $dd){
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				$amount			= $dd['amount'];
				$discount		= $dd['discount'];
				$rate			= $dd['rate'];
				$tax 			= $dd['tax'];
				$line_tax		= $dd['line_tax']['id'];
				$has_return_line= $dd['has_return_line'];
				$quantity 		= $dd['quantity'];
				$product		= $dd['product']['id'];

			    $insertData = $this->query->insertData('sales_detail',
						"
						id,transaction_id,description,amount,discount,rate,tax,line_tax,has_return_line,quantity,product
						",
						"'$id','$transaction_id','$description','$amount','$discount','$rate','$tax','$line_tax','$has_return_line','$quantity','$product'");
			    // echo "insert into sales_detail (id,transaction_id,description,amount,discount,rate,tax,line_tax,has_return_line,quantity,product) values ('$id','$transaction_id','$description','$amount','$discount','$rate','$tax','$line_tax','$has_return_line','$quantity','$product')<br><br>";
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetsalesreturnlist(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/sales_returns",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function getlistdatasalesreturnbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetsalesreturnlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['sales_returns'] AS $d){ $i++;
			$id 				= $d['id'];
			$type 				= '12';
			$transaction_no 	= str_replace('#','',$d['transaction_no']);
			$email 				= $d['email'];
			$address 			= str_replace("'",'`',$d['address']);
			$message 			= str_replace("'",'`',$d['message']);
			$memo 				= str_replace("'",'`',$d['memo']);
			$original_amount 	= $d['return_amount'];
			$shipping_price 	= $d['shipping_price'];
			$shipping_address 	= $d['shipping_address'];
			$is_shipped 		= $d['is_shipped'];
			$ship_via 			= $d['ship_via'];
			$reference_no 		= $d['reference_no'];
			$tracking_no 		= $d['tracking_no'];
			$custom_id 			= $d['custom_id'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	= date('Y-m-d', strtotime($date));
			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			= date('Y-m-d', strtotime($date2));
			$var3 				= $d['shipping_date'];
			$date3 				= str_replace('/', '-', $var3);
			$shipping_date 		= date('Y-m-d', strtotime($date3));
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$warehouse 			= round($d['warehouse']['id']);
			$term 				= $d['term']['id'];
			$invoice 			= @round($d['invoice_id']);
			$is_reconciled 		= $d['is_reconciled'];
			$tags 				= @round($d['tags'][0]['id']);
			$created_at 		= str_replace('.000Z','',$d['created_at']);
			$updated_at 		= str_replace('.000Z','',$d['updated_at']);

		    $insertData = $this->query->insertData('sales',
						"
						id,type,transaction_no,email,address,message,memo,original_amount,shipping_price,shipping_address,is_shipped,ship_via,reference_no,tracking_no,custom_id,deleted_at,deletable,editable,audited_by,transaction_date,due_date,shipping_date,transaction_status,person,warehouse,term,invoice,is_reconciled,created_at,updated_at,tags
						",
						"
						 '$id', '$type', '$transaction_no', '$email', '$address', '$message', '$memo', 
						 '$original_amount', '$shipping_price', '$shipping_address', '$is_shipped', '$ship_via', '$reference_no', 
						 '$tracking_no', '$custom_id', '$deleted_at', '$deletable', '$editable', '$audited_by', '$transaction_date', '$due_date', 
						 '$shipping_date', '$transaction_status', '$person', '$warehouse', '$term', 
						 '$invoice', 
						 '$is_reconciled', '$created_at', '$updated_at', '$tags'
						");

			// $insertData = $this->query->insertData('transactions',
			// 			"
			// 			id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
			// 			",
			// 			"'','$id','$transaction_no','$type','$transaction_date','$due_date','7257268','$remaining','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatasalesreturndetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetsalesreturnlist();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['sales_returns'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_return_lines_attributes'] AS $dd){

				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['transaction_line']['description']);
				$amount			= $dd['line_return_amount'];
				$discount		= $dd['transaction_line']['discount'];
				$rate			= $dd['transaction_line']['rate'];
				// $tax 			= $dd['tax'];
				// $line_tax		= $dd['line_tax']['id'];
				// $has_return_line= $dd['has_return_line'];
				$quantity 		= $dd['return_quantity'];
				$product		= $dd['transaction_line']['product']['id'];
				// $rows 	= $this->query->deleteData('sales_detail','id',$id);
			    $insertData = $this->query->insertData('sales_detail',
						"
						id,transaction_id,amount,discount,rate,quantity,product
						",
						"'$id','$transaction_id','$amount','$discount','$rate','$quantity','$product'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetterm(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/select2_resources/get_term?page=0",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apicontactprofile(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/contacts/7133021",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatavendor(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/vendors?include_archive=true&page_size=115",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatacust(){
		ini_set('max_execution_time', 1234567892342342);
		ini_set("memory_limit","125612314324M");

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/customers?include_archive=true&page_size=382&page=5",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatatax(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/taxes",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatabankwd(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/bank_withdrawals?page_size=805&page=1",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatabankwddetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apidatabankwd();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['bank_withdrawals'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_account_lines'] AS $dd){
			   //  $row 	= array(
			   //  			'id'				=> $dd['id'],
						// 	'transaction_id'	=> $id,
						// 	'description'		=> $dd['description'],
						// 	'debit'				=> $dd['debit'],
						// 	'credit'			=> $dd['credit'],
						// 	'line_tax'			=> $dd['line_tax']['id'],
						// 	'account'			=> $dd['account']['id'],
						// 	'expense'			=> $dd['expense']['id'],
						// );
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				$debit			= $dd['debit'];
				$credit			= $dd['credit'];
				$line_tax		= $dd['line_tax']['id'];
				$account		= $dd['account']['id'];
				$expense		= $dd['expense']['id'];

			    $insertData = $this->query->insertData('bank_transactions_detail',
						"
						id,transaction_id,description,debit,credit,line_tax,account,expense
						",
						"'$id','$transaction_id','$description','$debit','$credit','$line_tax','$account','$expense'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function getlistdatabankwdbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apidatabankwd();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['bank_withdrawals'] AS $d){ $i++;
		   //  $row 	= array(
					// 	'id'				=> $d['id'],
					// 	'transaction_no'	=> $d['transaction_no'],
					// 	'token'				=> $d['token'],
					// 	'memo'				=> $d['memo'],
					// 	'custom_id'			=> $d['custom_id'],
					// 	'source'			=> $d['source'],
					// 	'tax_amount'		=> $d['tax_amount'],
					// 	'remaining'			=> $d['remaining'],
					// 	'witholding_type'	=> $d['witholding_type'],
					// 	'created_at'		=> $d['created_at'],
					// 	'updated_at'		=> $d['updated_at'],
					// 	'deleted_at'		=> $d['deleted_at'],
					// 	'deletable'			=> $d['deletable'],
					// 	'editable'			=> $d['editable'],
					// 	'audited_by'		=> $d['audited_by'],
					// 	'transaction_date'	=> $d['transaction_date'],
					// 	'transaction_status'=> $d['transaction_status']['id'],
					// 	'person'			=> $d['person']['id'],
					// 	'refund_from'		=> $d['refund_from']['id'],
					// 	'original_amount'	=> $d['original_amount'],
					// 	'attachments'		=> $d['attachments'],
					// 	'tax_details'		=> $d['tax_details'],
					// 	'use_tax_inclusive'	=> $d['use_tax_inclusive'],
					// 	'tags'				=> $d['tags'],
					// 	'locked'			=> $d['locked'],
					// 	'is_reconciled'		=> $d['is_reconciled'],
					// 	'subtotal'			=> $d['subtotal'],
					// 	'witholding_value'	=> $d['witholding_value'],
					// 	'witholding_amount'	=> $d['witholding_amount'],
					// 	'witholding_account'=> $d['witholding_account']['id'],

					// );
			$id 				= $d['id'];
			$transaction_no 	= $d['transaction_no'];
			$token 				= $d['token'];
			$memo 				= $d['memo'];
			$custom_id 			= $d['custom_id'];
			$source 			= $d['source'];
			$tax_amount 		= $d['tax_amount'];
			$remaining 			= $d['remaining'];
			$witholding_type 	= $d['witholding_type'];
			$created_at 		= $d['created_at'];
			$updated_at 		= $d['updated_at'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var = $d['transaction_date'];
			$date = str_replace('/', '-', $var);
			$transaction_date 	=  date('Y-m-d', strtotime($date));
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$refund_from 		= $d['refund_from']['id'];
			$original_amount 	= $d['original_amount'];
			$attachments 		= '';
			$tax_details 		= '';
			$use_tax_inclusive 	= $d['use_tax_inclusive'];
			if ($d['tags'][0]['id']) {
				$tags 				= $d['tags'][0]['id'];
			} else {
				$tags 				= '';
			}
			$locked 			= $d['locked'];
			$is_reconciled 		= $d['is_reconciled'];
			$subtotal 			= $d['subtotal'];
			$witholding_value 	= $d['witholding_value'];
			$witholding_amount 	= $d['witholding_amount'];
			$witholding_account = $d['witholding_account']['id'];

		    // $insertData = $this->query->insertData('bank_transactions',
						// "
						// id,transaction_no,token,memo,custom_id,source,tax_amount,remaining,witholding_type,created_at,updated_at,deleted_at,deletable,editable,audited_by,transaction_date,transaction_status,person,refund_from,original_amount,attachments,tax_details,use_tax_inclusive,tags,locked,is_reconciled,subtotal,witholding_value,witholding_amount,witholding_account,type,has_child
						// ",
						// "'$id','$transaction_no','$token','$memo','$custom_id','$source','$tax_amount','$remaining','$witholding_type','$created_at','$updated_at','$deleted_at','$deletable','$editable','$audited_by','$transaction_date','$transaction_status','$person','$refund_from','$original_amount','$attachments','$tax_details','$use_tax_inclusive','$tags','$locked','$is_reconciled','$subtotal','$witholding_value','$witholding_amount','$witholding_account','4','1'");

			$insertData = $this->query->insertData('transactions',
						"
						id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
						",
						"'','$id','$transaction_no','8','$transaction_date','','$refund_from','$remaining','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'--'.$i.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// echo "'$id','$transaction_no','$token','$memo','$custom_id','$source','$tax_amount','$remaining','$witholding_type','$created_at','$updated_at','$deleted_at','$deletable','$editable','$audited_by','$transaction_date','$transaction_status','$person','$refund_from','$original_amount','$attachments','$tax_details','$use_tax_inclusive','$tags','$locked','$is_reconciled','$subtotal','$witholding_value','$witholding_amount','$witholding_account'<br>";
			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function apidatabanktf(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/bank_transfers?page_size=1482",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatabanktfbase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apidatabanktf();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['bank_transfers'] AS $d){
			$transaction_id 	= $d['id'];
			   //  $row 	= array(
			   //  			'id'  				=> $d['id'],
						// 	'transfer_amount' 	=> $d['transfer_amount'],
						// 	'transaction_no'  	=> $d['transaction_no'],
						// 	'token' 			=> $d['token'],
						// 	'memo'  			=> $d['memo'],
						// 	'source'  			=> $d['source'],
						// 	'custom_id' 		=> $d['custom_id'],
						// 	'created_at'  		=> $d['created_at'],
						// 	'updated_at'  		=> $d['updated_at'],
						// 	'deleted_at'  		=> $d['deleted_at'],
						// 	'deletable' 		=> $d['deletable'],
						// 	'editable'  		=> $d['editable'],
						// 	'audited_by'  		=> $d['audited_by'],
						// 	'transaction_date'  => $d['transaction_date'],
						// 	'transaction_status'=> $d['transaction_status']['id'],
						// 	'refund_from' 		=> $d['refund_from']['id'],
						// 	'original_amount' 	=> $d['original_amount'],
						// 	'deposit_to'  		=> $d['deposit_to']['id'],
						// 	'attachments' 		=> '',
						// 	'tags'  			=> $d['tags'],
						// 	'locked'  			=> $d['locked'],
						// 	'is_reconciled' 	=> $d['is_reconciled'],
						// );
				$id 				= $d['id'];
				$transfer_amount  	= $d['transfer_amount'];
				$transaction_no 	= $d['transaction_no'];
				$token  			= $d['token'];
				$memo 				= $d['memo'];
				$source 			= $d['source'];
				$custom_id  		= $d['custom_id'];
				$created_at 		= $d['created_at'];
				$updated_at 		= $d['updated_at'];
				$deleted_at 		= $d['deleted_at'];
				$deletable  		= $d['deletable'];
				$editable 			= $d['editable'];
				$audited_by 		= $d['audited_by'];
				$var = $d['transaction_date'];
				$date = str_replace('/', '-', $var);
				$transaction_date 	=  date('Y-m-d', strtotime($date));
				$transaction_status = $d['transaction_status']['id'];
				$refund_from  		= $d['refund_from']['id'];
				$original_amount  	= $d['original_amount'];
				$deposit_to 		= $d['deposit_to']['id'];
				$attachments  		= '';
				$tags 				= $d['tags'];
				$locked 			= $d['locked'];
				$is_reconciled  	= $d['is_reconciled'];

			    
			    $insertData = $this->query->insertData('bank_transactions',
						"
						id,remaining,transaction_no,token,memo,source,custom_id,created_at,updated_at,deleted_at,deletable,editable,audited_by,
						transaction_date,transaction_status,refund_from,original_amount,deposit_to,attachments,tags,locked,is_reconciled,type,has_child
						",
						"
						'$id','$transfer_amount','$transaction_no','$token','$memo','$source','$custom_id','$created_at','$updated_at','$deleted_at',
						'$deletable','$editable','$audited_by','$transaction_date','$transaction_status','$refund_from','$original_amount',
						'$deposit_to','$attachments','$tags','$locked','$is_reconciled','2','0'
						");

				$insertData = $this->query->insertData('transactions',
						"
						id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
						",
						"'','$id','$transaction_no','5','$transaction_date','','$refund_from','$transfer_amount','','','$tags','$transaction_status'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function apigetdatabankdepo(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/bank_deposits?page_size=391",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatabankdepodetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetdatabankdepo();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['bank_deposits'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_account_lines'] AS $dd){
			   //  $row 	= array(
			   //  			'id'				=> $dd['id'],
						// 	'transaction_id'	=> $id,
						// 	'description'		=> $dd['description'],
						// 	'debit'				=> $dd['debit'],
						// 	'credit'			=> $dd['credit'],
						// 	'line_tax'			=> $dd['line_tax']['id'],
						// 	'account'			=> $dd['account']['id'],
						// 	'expense'			=> $dd['expense']['id'],
						// );
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				$debit			= $dd['debit'];
				$credit			= $dd['credit'];
				$line_tax		= $dd['line_tax']['id'];
				$account		= $dd['account']['id'];
				$expense		= $dd['expense']['id'];

			    $insertData = $this->query->insertData('bank_transactions_detail',
						"
						id,transaction_id,description,debit,credit,line_tax,account,expense
						",
						"'$id','$transaction_id','$description','$debit','$credit','$line_tax','$account','$expense'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function getlistdatabankdepobase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetdatabankdepo();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['bank_deposits'] AS $d){ $i++;
		   //  $row 	= array(
					// 	'id'				=> $d['id'],
					// 	'transaction_no'	=> $d['transaction_no'],
					// 	'token'				=> $d['token'],
					// 	'memo'				=> $d['memo'],
					// 	'custom_id'			=> $d['custom_id'],
					// 	'source'			=> $d['source'],
					// 	'tax_amount'		=> $d['tax_amount'],
					// 	'remaining'			=> $d['remaining'],
					// 	'created_at'		=> $d['created_at'],
					// 	'updated_at'		=> $d['updated_at'],
					// 	'deleted_at'		=> $d['deleted_at'],
					// 	'deletable'			=> $d['deletable'],
					// 	'editable'			=> $d['editable'],
					// 	'audited_by'		=> $d['audited_by'],
					// 	'transaction_date'	=> $d['transaction_date'],
					// 	'transaction_status'=> $d['transaction_status']['id'],
					// 	'person'			=> $d['person']['id'],
					// 	'original_amount'	=> $d['original_amount'],
					// 	'attachments'		=> $d['attachments'],
					// 	'tax_details'		=> $d['tax_details'],
					// 	'use_tax_inclusive'	=> $d['use_tax_inclusive'],
					// 	'tags'				=> $d['tags'],
					// 	'locked'			=> $d['locked'],
					// 	'is_reconciled'		=> $d['is_reconciled'],
					// 	'subtotal'			=> $d['subtotal'],
					// 	'witholding_amount'	=> $d['witholding_amount'],
					// 	'witholding_account'=> $d['witholding_account']['id'],

					// );
			$id 				= $d['id'];
			$transaction_no 	= $d['transaction_no'];
			$token 				= $d['token'];
			$memo 				= $d['memo'];
			$custom_id 			= $d['custom_id'];
			$source 			= $d['source'];
			$tax_amount 		= $d['tax_amount'];
			$remaining 			= $d['remaining'];
			$witholding_type 	= '';
			$created_at 		= $d['created_at'];
			$updated_at 		= $d['updated_at'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var = $d['transaction_date'];
			$date = str_replace('/', '-', $var);
			$transaction_date 	=  date('Y-m-d', strtotime($date));
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			// $refund_from 		= $d['refund_from']['id'];
			$deposit_to 		= $d['deposit_to']['id'];
			$original_amount 	= $d['original_amount'];
			$attachments 		= '';
			$tax_details 		= '';
			$use_tax_inclusive 	= $d['use_tax_inclusive'];
			if ($d['tags'][0]['id']) {
				$tags 				= $d['tags'][0]['id'];
			} else {
				$tags 				= '';
			}
			$locked 			= $d['locked'];
			$is_reconciled 		= $d['is_reconciled'];
			$subtotal 			= $d['subtotal'];
			// $witholding_value 	= $d['witholding_value'];
			$witholding_amount 	= $d['witholding_amount'];
			$witholding_account = $d['witholding_account']['id'];

		    // $insertData = $this->query->insertData('bank_transactions',
						// "
						// id,transaction_no,token,memo,custom_id,source,tax_amount,remaining,witholding_type,created_at,updated_at,deleted_at,deletable,editable,audited_by,transaction_date,transaction_status,person,deposit_to,original_amount,attachments,tax_details,use_tax_inclusive,tags,locked,is_reconciled,subtotal,witholding_amount,witholding_account,type,has_child
						// ",
						// "'$id','$transaction_no','$token','$memo','$custom_id','$source','$tax_amount','$remaining','','$created_at','$updated_at','$deleted_at','$deletable','$editable','$audited_by','$transaction_date','$transaction_status','$person','$deposit_to','$original_amount','$attachments','$tax_details','$use_tax_inclusive','$tags','$locked','$is_reconciled','$subtotal','$witholding_amount','$witholding_account','3','1'");

			$insertData = $this->query->insertData('transactions',
						"
						id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
						",
						"'','$id','$transaction_no','3','$transaction_date','','$deposit_to','$remaining','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// echo "'$id','$transaction_no','$token','$memo','$custom_id','$source','$tax_amount','$remaining','$witholding_type','$created_at','$updated_at','$deleted_at','$deletable','$editable','$audited_by','$transaction_date','$transaction_status','$person','$refund_from','$original_amount','$attachments','$tax_details','$use_tax_inclusive','$tags','$locked','$is_reconciled','$subtotal','$witholding_value','$witholding_amount','$witholding_account'<br>";
			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function apidatacreditmemo(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/credit_memos",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apiapplycreditmemo(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/customer_apply_credit_memos",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function getlistcreditmemobase(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apidatacreditmemo();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['credit_memos'] AS $d){ $i++;
		    $row 	= array(
						'id'				=> $d['id'],
						'transaction_no'	=> $d['transaction_no'],
						'token'				=> $d['token'],
						'memo'				=> $d['memo'],
						'custom_id'			=> $d['custom_id'],
						'source'			=> '',
						'tax_amount'		=> $d['tax_amount'],
						'remaining'			=> $d['remaining'],
						'created_at'		=> $d['created_at'],
						'updated_at'		=> $d['updated_at'],
						'deleted_at'		=> $d['deleted_at'],
						'deletable'			=> $d['deletable'],
						'editable'			=> $d['editable'],
						'audited_by'		=> $d['audited_by'],
						'transaction_date'	=> $d['transaction_date'],
						'transaction_status'=> $d['transaction_status']['id'],
						'person'			=> $d['person']['id'],
						'deposit_to' 		=> $d['transaction_account_lines_attributes'][0]['account']['id'],
						'original_amount'	=> $d['original_amount'],
						'attachments'		=> $d['attachments'],
						'tax_details'		=> $d['tax_details'],
						'use_tax_inclusive'	=> $d['use_tax_inclusive'],
						'tags'				=> $d['tags'],
						'locked'			=> '',
						'is_reconciled'		=> $d['is_reconciled'],
						'subtotal'			=> $d['subtotal'],
						'witholding_amount'	=> '',
						'witholding_account'=> '',

					);
			$id 				= $d['id'];
			$transaction_no 	= $d['transaction_no'];
			$token 				= $d['token'];
			$memo 				= $d['memo'];
			$custom_id 			= $d['custom_id'];
			$source 			= '';
			$tax_amount 		= $d['tax_amount'];
			$remaining 			= $d['remaining'];
			$witholding_type 	= '';
			$created_at 		= $d['created_at'];
			$updated_at 		= $d['updated_at'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$var = $d['transaction_date'];
			$date = str_replace('/', '-', $var);
			$transaction_date 	=  date('Y-m-d', strtotime($date));
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$transtype 			= $d['transaction_type']['id'];
			$deposit_to 		= $d['transaction_account_lines_attributes'][0]['account']['id'];
			$original_amount 	= $d['original_amount'];
			$attachments 		= '';
			$tax_details 		= '';
			$use_tax_inclusive 	= $d['use_tax_inclusive'];
			if ($d['tags'][0]['id']) {
				$tags 				= $d['tags'][0]['id'];
			} else {
				$tags 				= '';
			}
			$locked 			= '';
			$is_reconciled 		= $d['is_reconciled'];
			$subtotal 			= $d['subtotal'];
			// $witholding_value 	= $d['witholding_value'];
			$witholding_amount 	= '';
			$witholding_account = '';

		    // $insertData = $this->query->insertData('bank_transactions',
						// "
						// id,transaction_no,token,memo,custom_id,source,tax_amount,remaining,witholding_type,created_at,updated_at,deleted_at,deletable,editable,audited_by,transaction_date,transaction_status,person,deposit_to,original_amount,attachments,tax_details,use_tax_inclusive,tags,locked,is_reconciled,subtotal,witholding_amount,witholding_account,type,has_child
						// ",
						// "'$id','$transaction_no','$token','$memo','$custom_id','$source','$tax_amount','$remaining','','$created_at','$updated_at','$deleted_at','$deletable','$editable','$audited_by','$transaction_date','$transaction_status','$person','$deposit_to','$original_amount','$attachments','$tax_details','$use_tax_inclusive','$tags','$locked','$is_reconciled','$subtotal','$witholding_amount','$witholding_account','$transtype','1'");

			$insertData = $this->query->insertData('transactions',
						"
						id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
						",
						"'','$id','$transaction_no','$transtype','$transaction_date','','$deposit_to','$remaining','','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function cekpayment(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/sales_invoices/104935566/transaction_no/null/receive_payments",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatacreditmemodetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apidatacreditmemo();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['credit_memos'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_account_lines_attributes'] AS $dd){
			   //  $row 	= array(
			   //  			'id'				=> $dd['id'],
						// 	'transaction_id'	=> $id,
						// 	'description'		=> $dd['description'],
						// 	'debit'				=> $dd['debit'],
						// 	'credit'			=> $dd['credit'],
						// 	'line_tax'			=> $dd['line_tax']['id'],
						// 	'account'			=> $dd['account']['id'],
						// 	'expense'			=> $dd['expense']['id'],
						// );
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				$debit			= $dd['debit'];
				$credit			= $dd['credit'];
				$line_tax		= $dd['line_tax']['id'];
				$account		= $dd['account']['id'];
				$expense		= $dd['expense']['id'];

			    $insertData = $this->query->insertData('bank_transactions_detail',
						"
						id,transaction_id,description,debit,credit,line_tax,account,expense
						",
						"'$id','$transaction_id','$description','$debit','$credit','$line_tax','$account','$expense'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apigetpaymenttype(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/payment_methods",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function getlistpaymentmethod(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apigetpaymenttype();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['payment_methods'] AS $d){ $i++;
		   //  $row 	= array(
					// 	'id'				=> $d['id'],
					// 	'transaction_no'	=> $d['transaction_no'],
					// 	'token'				=> $d['token'],
					// 	'memo'				=> $d['memo'],
					// 	'custom_id'			=> $d['custom_id'],
					// 	'source'			=> '',
					// 	'tax_amount'		=> $d['tax_amount'],
					// 	'remaining'			=> $d['remaining'],
					// 	'created_at'		=> $d['created_at'],
					// 	'updated_at'		=> $d['updated_at'],
					// 	'deleted_at'		=> $d['deleted_at'],
					// 	'deletable'			=> $d['deletable'],
					// 	'editable'			=> $d['editable'],
					// 	'audited_by'		=> $d['audited_by'],
					// 	'transaction_date'	=> $d['transaction_date'],
					// 	'transaction_status'=> $d['transaction_status']['id'],
					// 	'person'			=> $d['person']['id'],
					// 	'deposit_to' 		=> $d['transaction_account_lines_attributes'][0]['account']['id'],
					// 	'original_amount'	=> $d['original_amount'],
					// 	'attachments'		=> $d['attachments'],
					// 	'tax_details'		=> $d['tax_details'],
					// 	'use_tax_inclusive'	=> $d['use_tax_inclusive'],
					// 	'tags'				=> $d['tags'],
					// 	'locked'			=> '',
					// 	'is_reconciled'		=> $d['is_reconciled'],
					// 	'subtotal'			=> $d['subtotal'],
					// 	'witholding_amount'	=> '',
					// 	'witholding_account'=> '',

					// );
			$id 				= $d['id'];
			$name 				= $d['name'];
			$created_at 		= $d['created_at'];
			$updated_at 		= $d['updated_at'];
			$deleted_at 		= $d['deleted_at'];
			$custom_id 			= $d['custom_id'];

			$insertData = $this->query->insertData('payment_method',
						"
						id,name,created_at,updated_at,deleted_at,custom_id
						",
						"'$id','$name','$created_at','$updated_at','$deleted_at','$custom_id'");
			if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function apidataexpense(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/expenses?page_size=1913",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function getlistdataexpense(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apidataexpense();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['expenses'] AS $d){ $i++;
			$id 				= $d['id'];
			$transaction_no 	= $d['transaction_no'];
			$token 				= $d['token'];
			$email 				= $d['email'];
			$address 			= $d['address'];
			$memo 				= $d['memo'];
			$custom_id 			= $d['custom_id'];
			$source 			= $d['source'];
			$tax_amount 		= $d['tax_amount'];
			$remaining 			= $d['remaining'];
			$witholding_type 	= $d['witholding_type'];
			$created_at 		= $d['created_at'];
			$updated_at 		= $d['updated_at'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			$is_payable			= $d['is_payable'];
			
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	=  date('Y-m-d', strtotime($date));

			$var2 				= $d['due_date'];
			$date2 				= str_replace('/', '-', $var2);
			$due_date 			=  date('Y-m-d', strtotime($date2));
			$transaction_type 	= $d['transaction_type']['id'];
			$amount_receive  	= $d['amount_receive'];
			$has_payments 		= $d['has_payments'];
			$term 				= $d['term']['id'];
			
			$transaction_status = $d['transaction_status']['id'];
			$person 			= $d['person']['id'];
			$original_amount 	= $d['original_amount'];
			$attachments 		= '';
			$tax_details 		= '';
			$pay_from 		 	= $d['pay_from']['id'];
			$use_tax_inclusive 	= $d['use_tax_inclusive'];
			if ($d['tags'][0]['id']) {
				$tags 				= $d['tags'][0]['id'];
			} else {
				$tags 				= '';
			}
			$is_reconciled 		= $d['is_reconciled'];
			$subtotal 			= $d['subtotal'];
			$witholding_value 	= $d['witholding_value'];
			$witholding_amount 	= $d['witholding_amount'];
			$witholding_account = $d['witholding_account']['id'];

			$insertData = $this->query->insertData('expense',
						"
						id,transaction_no,token,email,address,memo,custom_id,source,tax_amount,remaining,witholding_type,created_at,updated_at,deleted_at,deletable,editable,audited_by,is_payable,transaction_date,due_date,transaction_type,amount_receive,has_payments,term,transaction_status,person,original_amount,attachments,tax_details,pay_from,use_tax_inclusive,tags,is_reconciled,subtotal,witholding_value,witholding_amount,witholding_account
						",
						"
						'$id','$transaction_no','$token','$email','$address','$memo','$custom_id','$source','$tax_amount','$remaining','$witholding_type',
						'$created_at','$updated_at','$deleted_at','$deletable','$editable','$audited_by','$is_payable','$transaction_date','$due_date',
						'$transaction_type','$amount_receive','$has_payments','$term','$transaction_status','$person','$original_amount',
						'$attachments','$tax_details','$pay_from','$use_tax_inclusive','$tags','$is_reconciled','$subtotal','$witholding_value',
						'$witholding_amount','$witholding_account'
						");
			
			if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdataexpensedetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apidataexpense();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['expenses'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_account_lines_attributes'] AS $dd){
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				$debit			= $dd['debit'];
				$line_tax		= $dd['line_tax']['id'];
				$account		= $dd['account']['id'];

			    $insertData = $this->query->insertData('expense_detail',
						"
						id,transaction_id,description,debit,line_tax,account
						",
						"'$id','$transaction_id','$description','$debit','$line_tax','$account'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apidatareceivepayment(){
		ini_set('max_execution_time', 1234567892342342);
		ini_set("memory_limit","125612314324M");

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/receive_payments?page_size=1385",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{\"receive_payments\":[{\"id\":\"integer (optional)\",\"transaction_date\":\"string (optional)\",\"transaction_no\":\"string (optional)\",\"memo\":\"string (optional)\",\"person\":{\"id\":\"integer (optional)\",\"name\":\"string (optional)\"},\"transaction_type\":{\"id\":\"integer (optional)\",\"name\":\"string (optional)\"},\"payment_method\":{\"id\":\"integer (optional)\",\"name\":\"string (optional)\"},\"deposit_to\":{\"id\":\"integer (optional)\",\"name\":\"string (optional)\"},\"records\":[{\"id\":\"integer (optional)\",\"transaction_id\":\"integer (optional)\",\"amount\":\"string (optional)\"}]}],\"total_count\":\"integer (optional)\"}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function getdetailakunapi(){
		error_reporting(0);
		$getData 	= $this->query->getDatabyQ("select * from account where accounts_id>7257362 order by 1 asc");
		header('Content-type: application/json; charset=UTF-8');
			
		$i = 0;
		foreach($getData as $data) { $i++;
			// $json['data'] 	= $this->apiakunget($data['accounts_id']);
			$id 		= $data['accounts_id'];
			$jsondata 	= $this->apiakunget($id);
			$data 		= json_decode($jsondata, true);

			foreach ($data AS $d){
			    $row 	= array(
							'account_id'	=> $d['id'],
							'transactions'	=> $d['transactions'],
						);
				$json[] 	= $row;
			}

			// $json 			= str_replace('"account":','"account'.$i.'":',$this->apiakunget($data['accounts_id']));
			// $data 			= json_decode($json);
			// echo json_encode($data);
			// exit;
		}
		echo json_encode($json);
	}

	public function getakunbalanceapi(){
		error_reporting(0);
		$getData 	= $this->query->getDatabyQ("select * from account order by 1 asc");
		// header('Content-type: application/json; charset=UTF-8');
			
		$i = 0;
		foreach($getData as $data) { $i++;
			$id 		= $data['accounts_id'];
			$jsondata 	= $this->apiakunget($id);
			$data 		= json_decode($jsondata, true);

			foreach ($data AS $d){
			 //    $row 	= array(
				// 			'id'			=> $d['id'],
				// 			'balance'		=> $d['balance'],
				// 			'balance_string'=> $d['balance_string']
				// 		);
				// $json[] 	= $row;
				// echo "update account set balance='".str_replace('.',',',$d['balance'])."', balance_string='".$d['balance_string']."' where id='".$d['id']."'<br>";
				$rows = $this->query->updateData('account',
						"balance='".str_replace('.',',',$d['balance'])."', balance_amount='".$d['balance_string']."'
						"
						,"WHERE id='".$d['id']."'");
				echo "sukses<br>";
			}
		}
		// echo json_encode($json);
	}

	public function getdetailakunapi_cekjml(){
		error_reporting(0);
		$getData 	= $this->query->getDatabyQ("select * from account order by 1 asc");
		header('Content-type: application/json; charset=UTF-8');
			
		$i = 0;
		foreach($getData as $data) { $i++;
			// $json['data'] 	= $this->apiakunget($data['accounts_id']);
			$id 		= $data['accounts_id'];
			$jsondata 	= $this->apiakunget($id);
			$data 		= json_decode($jsondata, true);

			foreach ($data AS $d){
			    $row 	= array(
							'account_id'	=> $d['id']
						);
				$json[] 	= $row;
			}

			// $json 			= str_replace('"account":','"account'.$i.'":',$this->apiakunget($data['accounts_id']));
			// $data 			= json_decode($json);
			// echo json_encode($data);
			// exit;
		}
		echo json_encode($json);
	}

	public function apiakunexport(){
		// echo 'asdfa';
		// header('Content-disposition: attachment; filename=account.json');
		// header('Content-type: application/json');
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/accounts/export?export_type=xlsx",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apiakunbankstatement(){
		// echo 'asdfa';
		// header('Content-disposition: attachment; filename=account.json');
		// header('Content-type: application/json');
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/accounts/7257263/bank_statements?start_date=2014-09-11&end_date=2020-01-31",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apiakunbankstatementget($id){
		// echo 'asdfa';
		// header('Content-disposition: attachment; filename=account.json');
		// header('Content-type: application/json');
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/accounts/$id/bank_statements?start_date=2016-09-11&end_date=2020-01-31",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function getdetailbankstatapi(){
		$getData 	= $this->query->getDatabyQ("SELECT * FROM `account` where accounts_category_id=3");
		header('Content-type: application/json; charset=UTF-8');
			
		$i = 0;
		foreach($getData as $data) { $i++;
			// $json['data'] 	= $this->apiakunget($data['accounts_id']);
			$id 		= $data['accounts_id'];
			$jsondata 	= $this->apiakunbankstatementget($id);
			$data 		= json_decode($jsondata, true);

			foreach ($data AS $d){
			   //  $row 	= array(
						// 	'account_id'	=> $d['id'],
						// 	'transactions'	=> $d['transactions'],
						// );
				$json[] 	= $d;
			}

			// $json 			= str_replace('"account":','"account'.$i.'":',$this->apiakunget($data['accounts_id']));
			// $data 			= json_decode($json);
			// echo json_encode($data);
			// exit;
		}
		echo json_encode($json);
	}

	public function apiasetaktifdata(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/asset_managements/",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apitransactiontag(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/tags/",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apijurnalentry(){
		// echo 'asdfa';
		// header('Content-disposition: attachment; filename=account.json');
		// header('Content-type: application/json');
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/journal_entries?page_size=366",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 99,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function getlistdatajurnalentry(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apijurnalentry();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['journal_entries'] AS $d){ $i++;
			$id 				= $d['id'];
			$transaction_no 	= $d['transaction_no'];
			$token 				= $d['token'];
			$memo 				= $d['memo'];
			$custom_id 			= $d['custom_id'];
			$source 			= $d['source'];
			$created_at 		= $d['created_at'];
			$updated_at 		= $d['updated_at'];
			$deleted_at 		= $d['deleted_at'];
			$deletable 			= $d['deletable'];
			$editable 			= $d['editable'];
			$audited_by 		= $d['audited_by'];
			
			$var 				= $d['transaction_date'];
			$date 				= str_replace('/', '-', $var);
			$transaction_date 	=  date('Y-m-d', strtotime($date));

			$transaction_type 	= '14';
			$total_debit 		= $d['total_debit'];
			$total_credit 		= $d['total_credit'];
			$transaction_status = $d['transaction_status']['id'];

			$attachments 		= '';
			
			if ($d['tags'][0]['id']) {
				$tags 				= $d['tags'][0]['id'];
			} else {
				$tags 				= '';
			}
			$is_reconciled 		= $d['is_reconciled'];
			$account 			= $d['transaction_account_lines'][0]['account']['id'];
			// echo $account."<br>";

			// $insertData = $this->query->insertData('jurnal_entry',
			// 			"
			// 			id,transaction_no,token,memo,custom_id,source,created_at,updated_at,deleted_at,deletable,editable,audited_by,transaction_date,total_debit,total_credit,transaction_status,attachments,tags,is_reconciled
			// 			",
			// 			"
			// 			'$id','$transaction_no','$token','$memo','$custom_id','$source','$created_at','$updated_at','$deleted_at','$deletable','$editable',
			// 			'$audited_by','$transaction_date','$total_debit','$total_credit','$transaction_status','$attachments',
			// 			'$tags','$is_reconciled'
			// 			");
			
			$insertData = $this->query->insertData('transactions',
						"
						id,transaction_id,transaction_no,transaction_type,date,due_date,account_id,debit,credit,balance,tags,status
						",
						"'','$id','$transaction_no','$transaction_type','$transaction_date','','$account','$total_debit','$total_credit','','$tags','$transaction_status'");
			if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }

			// $json[] 	= $row;
		}
		// echo json_encode($json);
	}

	public function getlistdatajurnalentrydetail(){
		// header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apijurnalentry();
		$data 		= json_decode($jsondata, true);
		$i 			= 0;

		foreach ($data['journal_entries'] AS $d){
			$transaction_id 	= $d['id'];
			foreach ($d['transaction_account_lines'] AS $dd){
			    $row 	= array(
			    			'id'				=> $dd['id'],
							'transaction_id'	=> $transaction_id,
							'description'		=> $dd['description'],
							'debit'				=> $dd['debit'],
							'credit'			=> $dd['credit'],
							'line_tax'			=> $dd['line_tax']['id'],
							'account'			=> $dd['account']['id'],
							'expense'			=> $dd['expense']['id'],
						);
				$id				= $dd['id'];
				$transaction_id	= $transaction_id;
				$description	= str_replace("'","`",$dd['description']);
				$debit			= $dd['debit'];
				$credit			= $dd['credit'];
				$line_tax		= $dd['line_tax']['id'];
				$account		= $dd['account']['id'];
				$expense		= $dd['expense']['id'];

			    $insertData = $this->query->insertData('jurnal_entry_detail',
						"
						id,transaction_id,description,debit,credit,line_tax,account,expense
						",
						"'$id','$transaction_id','$description','$debit','$credit','$line_tax','$account','$expense'");
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			   	// $json[] 	= $row;
		   }
		}
		// echo json_encode($json);
	}

	public function apidatakontak(){
		// ini_set('max_execution_time', 123456);
		// ini_set("memory_limit","1256M");

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/customers?page_size=1895&include_archive=true",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apitransrescue(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/select2_resources/get_transaction?type=1",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apiexportkontak(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/customers/export?selected=2047&export_type=xlsx",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apiprodukunit(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/product_units",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatagudang(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/warehouses/",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidatacategory(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/product_categories",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidataprodukbyid(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/products/2785887",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidataproduk(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/products?include_archive=true&page_size=527&page=4",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 999,
		  CURLOPT_TIMEOUT => 990,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function apitransfergudang(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/warehouse_transfers?page_size=1128",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 90,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apigetdatatfgudang(){
		header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apitransfergudang();
		$data 		= json_decode($jsondata, true);

		foreach ($data['warehouse_transfers'] AS $d){
		    $row 	= array(
						'id'				=> $d['id'],
						'memo'				=> $d['memo'],
						'transaction_date'	=> $d['transaction_date'],
						'transaction_no'	=> $d['transaction_no'],
						'from_warehouse'	=> $d['from_warehouse']['id'],
						'to_warehouse'		=> $d['to_warehouse']['id'],
					);
			$json[] 	= $row;
		}
		echo json_encode($json);
	}

	public function apigetdatatfgudangdetail(){
		header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apitransfergudang();
		$data 		= json_decode($jsondata, true);

		foreach ($data['warehouse_transfers'] AS $d){
			$id 	= $d['id'];
			foreach ($d['warehouse_transfer_line_attributes'] AS $dd){
			    $row 	= array(
			    			'idtransfer'		=> $id,
			    			'iddetail'			=> $dd['id'],
							'idproduct'			=> $dd['product']['id'],
							'quantity'			=> $dd['quantity'],
						);
				$json[] 	= $row;
			}
		}
		echo json_encode($json);
	}

	public function apidataadjstock(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/stock_adjustments?page_size=127&page=5",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 90,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function unit_conversions(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/unit_conversions",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apigetdataadjstok(){
		header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apidataadjstock();
		$data 		= json_decode($jsondata, true);

		foreach ($data['stock_adjustments'] AS $d){
		    $row 	= array(
						'id'				=> $d['id'],
						'memo'				=> $d['memo'],
						'date'				=> $d['date'],
						'adjustmen_type'	=> $d['adjustment_type'],
						'adjustment_type_id'=> $d['stock_adjustment_type_id'],
						'account'			=> $d['account']['id'],
						'warehouse'			=> $d['warehouse']['id'],
						'warehouse'			=> $d['warehouse']['id'],
						'entry'				=> $d['entry']['transaction_number'],
						'tag'				=> $d['tag'],
						'editable'			=> $d['editable'],
						'deletable'			=> $d['deletable']
					);
			$json[] 	= $row;
		}
		echo json_encode($json);
	}

	public function apigetdataadjstokdetail(){
		header('Content-type: application/json; charset=UTF-8');
		
		$jsondata 	= $this->apidataadjstock();
		$data 		= json_decode($jsondata, true);

		foreach ($data['stock_adjustments'] AS $d){
			$id 	= $d['id'];
			foreach ($d['stock_adjustment_lines_attributes'] AS $dd){
			    $row 	= array(
			    			'id_adjustment'		=> $id,
			    			'iddetail'			=> $dd['id'],
							'idproduct'			=> $dd['product']['id'],
							'recorded_quantity'	=> $dd['recorded_quantity'],
							'actual_quantity'	=> $dd['actual_quantity'],
							'difference'		=> $dd['difference'],
							'average_price'		=> $dd['average_price'],
						);
				$json[] 	= $row;
			}
		}
		echo json_encode($json);
	}

	public function apidatainventorysum(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/inventory_summary?detail=true",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apitestdataproduct(){
		header('Content-type: application/json; charset=UTF-8');

		$jsondata 	= $this->apidataproduk();
		$data 		= json_decode($jsondata, true);

		foreach ($data['products'] AS $d){
			if($d['is_bundle']=='true') {
				$bundle 	= $d['product_bundles'];
			} else {
				$bundle 	= '';
			}
		    $row 	= array(
						'id'				=> $d['id'],
						'name'				=> $d['name'],
						'product_code'		=> $d['product_code'],
						'description'		=> $d['description'],
						'created_at'		=> $d['created_at'],
						'updated_at'		=> $d['updated_at'],
						'deleted_at'		=> $d['deleted_at'],
						'archive'			=> $d['archive'],
						'track_inventory'	=> $d['track_inventory'],
						'gudang' 			=> '',
						'unit_id'			=> $d['unit']['id'],
						'image_name'		=> $d['image']['name'],
						'buy_account_id'	=> $d['buy_account']['id'],
						'sell_account_id'	=> $d['sell_account']['id'],
						'inventory_asset_account_id'	=> $d['inventory_asset_account']['id'],
						'last_buy_price'	=> $d['last_buy_price'],
						'buffer_quantity'	=> $d['buffer_quantity'],
						'buy_price_per_unit'=> $d['buy_price_per_unit'],
						'sell_price_per1'	=> $d['sell_price_per_unit'],
						'sell_price_per2'	=> $d['sell_price_per_unit'],
						'sell_price_per3'	=> $d['sell_price_per_unit'],
						'sell_price_per4'	=> $d['sell_price_per_unit'],
						'product_categories'=> $d['product_categories'][0]['id'],
						'is_bundle'			=> $d['is_bundle'],
						// 'product_bundles'	=> $bundle
					);
			$json[] 	= $row;
		}

		echo json_encode($json);
		// var_dump($data);
	}

	public function apigetalldataproduk(){
		$jsondata 	= $this->apidataproduk();
		$data 		= json_decode($jsondata, true);

		foreach ($data['products'] AS $d){
			$id 				= $d['id'];
			$name				= str_replace("'",'`',$d['name']);
			$product_code		= $d['product_code'];
			$description		= $d['description'];
			$created_at			= $d['created_at'];
			$updated_at			= $d['updated_at'];
			$deleted_at			= $d['deleted_at'];
			if ($d['archive']=='true') {
				$archive			= 'TRUE';
			} else {
				$archive			= 'FALSE';
			}
			if ($d['track_inventory']=='true') {
				$track_inventory	= 'TRUE';
			} else {
				$track_inventory	= 'FALSE';
			}
			$qty				= $d['quantity'];
			$unit				= $d['unit']['id'];
			$image				= $d['image']['name'];
			$buy_account		= $d['buy_account']['id'];
			$sell_account		= $d['sell_account']['id'];
			$inventory_account	= $d['inventory_asset_account']['id'];
			$average_price		= $d['last_buy_price'];
			$minimum_stock		= $d['buffer_quantity'];
			$harga_beli			= $d['buy_price_per_unit'];
			$harga_sales		= $d['sell_price_per_unit'];
			$harga_toko			= $d['sell_price_per_unit'];
			$harga_tukangbor	= $d['sell_price_per_unit'];
			$harga_kasir		= $d['sell_price_per_unit'];
			if ($d['is_bundle']=='true') {
				$is_bundle			= 'TRUE';
			} else {
				$is_bundle			= 'FALSE';
			}
			
			$insertData = $this->query->insertData('produk',
						"id,name,product_code,description,created_at,updated_at,deleted_at,archive,track_inventory,qty,unit,image,buy_account,sell_account,inventory_account,average_price,minimum_stock,harga_beli,harga_sales,harga_toko,harga_tukangbor,harga_kasir,is_bundle",
						"'$id','$name','$product_code','$description','$created_at','$updated_at','$deleted_at','$archive','$track_inventory','$qty','$unit','$image','$buy_account','$sell_account','$inventory_account','$average_price','$minimum_stock','$harga_beli','$harga_sales','$harga_toko','$harga_tukangbor','$harga_kasir','$is_bundle'");
			if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
		}

		// echo json_encode($json);
		// var_dump($data);
	}

	public function apiupdatelastbuyprice(){

		$jsondata 	= $this->apidataproduk();
		$data 		= json_decode($jsondata, true);

		foreach ($data['products'] AS $d){
			$rows = $this->query->updateData('produk',
						"average_price='".$d['last_buy_price']."'"
						,"WHERE id='".$d['id']."'");
			echo "update product set <br>";
		 //    $row 	= array(
			// 			'id'				=> $d['id'],
			// 			'average_price'		=> $d['last_buy_price'],
			// 		);
			// $json[] 	= $row;
		}

		// echo json_encode($json);
		// var_dump($data);
	}

	public function apidatastokproduk(){
		error_reporting(0);

		// header('Content-type: application/json; charset=UTF-8');

		$jsondata 	= $this->apidataproduk();
		$data 		= json_decode($jsondata, true);

		foreach ($data['products'] AS $d){
			$id 	= $d['id'];
			$lb 	= $d['last_buy_price'];
			$tgl 	= $d['last_updated_inventory'];
			// if ($d['warehouses']) {
			// 	var_dump($d['warehouses']);
			// } else {
			// 	echo '';
			// }
			foreach ($d['warehouses'] AS $dd){
				if ($dd['name']=='Unassigned') {
					$gudang 	= '-1';
				} else if ($dd['name']=='Display Toko PP') {
					$gudang 	= '14717';
				} else if ($dd['name']=='Gudang Soreang') {
					$gudang 	= '16257';
				} else {
					$gudang 	= '21333';
				}
				$qty 	= $dd['quantity'];
			   //  $row 	= array(
			   //  			'id_product'	=> $id,
			   //  			'qty'			=> $dd['quantity'],
			   //  			'tanggal'		=> $tgl,
			   //  			'harga_beli'	=> $lb,
						// 	'id_gudang'		=> $gudang,
						// );
				// $json[] 	= $row;
				// var_dump($dd);
				$insertData = $this->query->insertData('product_detail_stock',
						"id_product,qty,tanggal,harga_beli,id_gudang",
						"'$id','$qty','$tgl','$lb','$gudang'");
				// echo "insert into product_detail_stock (id_product,qty,tanggal,harga_beli,id_gudang) values ()";
				if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
			}
		}

		// echo json_encode($json);
		// var_dump($data);
	}

	public function apidataasettertunda(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/asset_managements/",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidataasetaktif(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/asset_managements/",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
		$curl = curl_init();
	}

	public function apidataasetlepas(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/asset_managements/",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apidataasetpenyusutan(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/asset_managements/depreciation_schedules",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apigetdetailaset($id){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/asset_managements/".$id."",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  return $response;
		}
	}

	public function apigetdetailasetsingle($id){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/asset_managements/".$id."",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function getlistasetdata(){
		$jsondata 	= $this->apidataasetaktif();
		$data 		= json_decode($jsondata, true);

		foreach ($data['assets'] AS $d){
			$id 		= $d['id'];
			$jsondata2 	= $this->apigetdetailaset($id);
			$datadetail = json_decode($jsondata2, true);
			
			foreach ($datadetail AS $dd){
			    // $row 	= array(
							$id				= $dd['id'];
							$name			= str_replace("'",'`',$dd['name']);
							$asset_number	= $dd['asset_number'];
						    $description	= $dd['description'];
						    $status		= $dd['status'];
						    if ($dd['acquisition_date']=='') {
						    	$acquisition_date 	= '';
						    } else {
						    	$tacquisition_date 	= strtotime(str_replace('/','-',$dd['acquisition_date']));
						    	$acquisition_date 	= date('Y-m-d',$tacquisition_date);
						    }
						    if ($dd['deletable']=='true') {
						    	$deletable		= 'TRUE';
						    } else {
						    	$deletable		= 'FALSE';
						    }
						    if ($dd['editable']=='true') {
						    	$editable 		= 'TRUE';
						    } else {
						    	$editable 		= 'FALSE';
						    }
						    $custom_id		= $dd['custom_id'];
						    if ($dd['revertable']=='true') {
						    	$revertable 	= 'TRUE';
						    } else {
						    	$revertable 	= 'FALSE';
						    }
						    $book_value 	= $dd['book_value'];
						    $acquisition_cost	= $dd['acquisition_cost'];
						    if ($dd['depreceable']=='true') {
						    	$depreceable	= 'TRUE';
						    } else {
						    	$depreceable	= 'FALSE';
						    }
						    $useful_life	= $dd['useful_life'];
						    $useful_life_detail	= $dd['useful_life_detail'];
						    $depreciation_method	= $dd['depreciation_method'];
						    $rate_value 	= $dd['rate_value'];
						    $accumulated_depreciation	= $dd['accumulated_depreciation'];
						    if ($dd['last_applied_depreciation_date']=='') {
						    	$last_applied_depreciation_date 	= '';
						    } else {
						    	$tlast_applied_depreciation_date 	= strtotime(str_replace('/','-',$dd['last_applied_depreciation_date']));
						    	$last_applied_depreciation_date 	= date('Y-m-d',$tlast_applied_depreciation_date);
						    }
						    $tags			= $dd['tags'][0]['id'];
						    $asset_account	= $dd['asset_account']['id'];
						    $depreciation_account 	= $dd['depreciation_account']['id'];
						    $credited_account		= $dd['credited_account']['id'];
						    $depreciation_and_amortization_account 	= $dd['depreciation_and_amortization_account']['id'];
						    if ($dd['applied_depreciation']=='true') {
						    	$applied_depreciation	= 'TRUE';
						    } else {
						    	$applied_depreciation	= 'FALSE';
						    }

						    // echo $id."----".$acquisition_date."----".$last_applied_depreciation_date."<br>";
		 //    $insertData = $this->query->insertData('assets',
			// 			"id,name,asset_number,description,status,acquisition_date,deletable,editable,custom_id,revertable,book_value,acquisition_cost,depreceable,useful_life,useful_life_detail,depreciation_method,rate_value,accumulated_depreciation,last_applied_depreciation_date,tags,asset_account,depreciation_account,credited_account,depreciation_and_amortization_account,applied_depreciation",
			// 			"'$id','$name','$asset_number','$description','$status','$acquisition_date','$deletable','$editable','$custom_id','$revertable','$book_value','$acquisition_cost','$depreceable','$useful_life','$useful_life_detail','$depreciation_method','$rate_value','$accumulated_depreciation','$last_applied_depreciation_date','$tags','$asset_account','$depreciation_account','$credited_account','$depreciation_and_amortization_account','$applied_depreciation'");
			// if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
						    // echo $tags."<br>";
						    if (@$dd['invoice']['id']) {
						    $update 	= $this->query->updateData('assets',"purchase_id='".@$dd['invoice']['id']."'","WHERE id='".$id."'");
						    // echo $id.'---'.@$dd['invoice']['id']."<br><br>";
							}
						// );
				// $json[] 	= $row;
			}
		}

		// echo json_encode($json);
		// var_dump($data);
	}

	public function getlistasetdatahistory(){
		$jsondata 	= $this->apidataasetaktif();
		$data 		= json_decode($jsondata, true);

		foreach ($data['assets'] AS $d){
			$id 		= $d['id'];
			$jsondata2 	= $this->apigetdetailaset($id);
			$datadetail = json_decode($jsondata2, true);
			
			foreach ($datadetail AS $dd){
				$id 	= $dd['id'];

				foreach($dd['transaction_history'] as $dt) {
				 //    $row 	= array(
					// 			"idasset"				=> $id,
					// 			"transaction_date"		=> $dt['transaction_date'],
					// 			"action"				=> $dt['action'],
					// 			"transaction_id"		=> $dt['transaction']['id'],
					// 			"transaction_no"		=> $dt['transaction']['transaction_no'],
					// 			"transaction_type_id"	=> $dt['transaction']['transaction_type_id'],
					// 			"transaction_name"		=> $dt['transaction']['transaction_name'],
					// 			"account"				=> $dt['transaction']['account']['id'],
					// 			"debit"					=> $dt['transaction']['debit'],
					// 			"credit"				=> $dt['transaction']['credit'],
					// 		);
					// $json[] 	= $row;

					$id_history 			= $dt['transaction']['id'];
					$id_asset 				= $id;
					$ttransaction_date 		= strtotime(str_replace('/','-',$dt['transaction_date']));
			    	$transaction_date 		= date('Y-m-d',$ttransaction_date);
					$action 				= $dt['action'];
					$transaction_no 		= $dt['transaction']['transaction_no'];
					$transaction_type_id	= $dt['transaction']['transaction_type_id'];
					$transaction_name 		= $dt['transaction']['transaction_name'];
					$account 				= $dt['transaction']['account']['id'];
					$debit 					= $dt['transaction']['debit'];
					$kredit 				= $dt['transaction']['credit'];

					$insertData = $this->query->insertData('assets_history',
						"id_history,
						id_asset,
						transaction_date,
						action,
						transaction_no,
						transaction_type_id,
						transaction_name,
						account,
						debit,
						kredit",
						"'$id_history','$id_asset','$transaction_date','$action','$transaction_no','$transaction_type_id','$transaction_name','$account','$debit','$kredit'");
					if ($insertData) { echo 'sukes = '.$id.'<br>'; } else { echo 'gagal = '.$id.'<br>'; }
				}
			}
		}

		// echo json_encode($json);
		// var_dump($data);
	}

	public function apidefaultinputproduk(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/products/new",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apiexportproduk(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/products/export?export_type=xlsx",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function defaultinputproduct(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/products/new",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 29e136ae6c464a86ae9af85ccd0fa73a"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apiaddproduct(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/products",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => '
		  {
			  "product": {
			    "name": "Alat Tulis Baru",
			    "taxable_buy": true,
			    "unit_name": "bln",
			    "sell_price_per_unit": "500000",
			    "custom_id": "PRD-002",
			    "track_inventory": "true",
			    "description": "Kumpulan alat tulis",
			    "buy_price_per_unit": "100000",
			    "product_code": "PRC-004",
			    "is_bought": true,
			    "buy_account_number": "5-50000",
			    "buy_account_name": "Beban Pokok Pendapatan",
			    "is_sold": true,
			    "sell_account_number": "4-40000",
			    "sell_account_name": "Pendapatan",
			    "taxable_sell": true,
			    "inventory_asset_account_id": 18144659,
			    "inventory_asset_account_name": "Persediaan Barang",
			    "inventory_asset_account_number": "1-10200",
			    "buffer_quantity": 10.0
			  }
			}',
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 29e136ae6c464a86ae9af85ccd0fa73a",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apibukubesar(){
		ini_set('max_execution_time', 12345678903234234);
		ini_set("memory_limit","125613241241M");

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/general_ledger?start_date=01/01/2020&end_date=18/01/2020",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apibukubesarexport(){
		ini_set('max_execution_time', 12345678903234234);
		ini_set("memory_limit","125613241241M");

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/general_ledger/export?export_type=xlsx&start_date=01/01/2019&end_date=31/01/20",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 726285eed77abbca586eba1269d16f46"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function apigetdetailproduct(){
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://api.jurnal.id/core/api/v1/products/9144613",
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "GET",
		  CURLOPT_POSTFIELDS => "{}",
		  CURLOPT_HTTPHEADER => array(
		    "apikey: 29e136ae6c464a86ae9af85ccd0fa73a",
		    "content-type: application/json"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}

	public function insertgudang(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid		= $userdata['userid'];

			// INFO GUDANG
			$nama 		= trim(strip_tags(stripslashes($this->input->post('namagudang',true))));
			$kode 		= trim(strip_tags(stripslashes($this->input->post('kodegudang',true))));
			$alamat 	= trim(strip_tags(stripslashes($this->input->post('alamatgudang',true))));
			$keterangan = trim(strip_tags(stripslashes($this->input->post('keterangangudang',true))));
				
			$id			= $this->db->insert_id();
			$url 		= "Gudang";
			$activity 	= "INSERT";

			$rows = $this->query->insertData('gudang',
						"id_gudang,nama_gudang,kode,alamat,keterangan",
						"'','$nama','$kode','$alamat','$keterangan'");
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

	public function getdatagudang(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'kode'		=> true,
				'nama'		=> true,
				'alamat'	=> true,
				'keterangan'=> true,
				'actions'	=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datagudang';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalgudang(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$qKontak 	= "
						select * from gudang where id_gudang='$id'
						";
			$gKontak 	= $this->query->getDatabyQ($qKontak);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($gKontak as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}

	public function updategudang(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid		= $userdata['userid'];

			$id			= trim(strip_tags(stripslashes($this->input->post('ed_idgudang',true))));
			// INFO GUDANG
			$nama 		= trim(strip_tags(stripslashes($this->input->post('ed_namagudang',true))));
			$kode 		= trim(strip_tags(stripslashes($this->input->post('ed_kodegudang',true))));
			$alamat 	= trim(strip_tags(stripslashes($this->input->post('ed_alamatgudang',true))));
			$keterangan = trim(strip_tags(stripslashes($this->input->post('ed_keterangangudang',true))));
				
			$url 		= "Gudang";
			$activity 	= "UPDATE";

			$rows = $this->query->updateData('gudang',
						"nama_gudang='$nama', kode='$kode',alamat='$alamat', keterangan='$keterangan'
						"
						,"WHERE id_gudang='$id'");

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

	public function deletegudang(){
		if(checkingsessionpwt()){
			$url 		= "Gudang";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddelGudang',true))));
			
			$rows 	= $this->query->deleteData('gudang','id_gudang',$cond);
			
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

	public function getjmlgudang(){
		if(checkingsessionpwt()){
			$gGudang	= $this->query->getDatabyQ("select count(*) jmlGudang from gudang");
			$dGudang 	= array_shift($gGudang);
			$jmlGudang 	= $dGudang['jmlGudang'];

			echo $jmlGudang;
		}else{
            redirect('/login');
        }
	}

	public function getjmlproductTersedia(){
		if(checkingsessionpwt()){
			$g		= $this->query->getDatabyQ("
						select sum(jmlTersedia) jml from (
						    select base.*,
						    case when stok!='' and stok>(minimum_stock) then 1 else 0 end jmlTersedia,
						    case when stok!='' and stok<=(minimum_stock) and stok>0 then 1 else 0 end jmlHampirHabis,
						    case when stok='' and stok<=0 then 1 else 0 end jmlHabis
						    from (
						        SELECT id, minimum_stock, (select sum(qty) jml from product_detail_stock where id_product=a.id) stok FROM `produk` a
						    ) as base
						) as final
					");
			$d 		= array_shift($g);
			$jml 	= $d['jml'];

			echo $jml;
		}else{
            redirect('/login');
        }
	}

	public function getjmlproductHampirHabis(){
		if(checkingsessionpwt()){
			$g		= $this->query->getDatabyQ("
						select sum(jmlHampirHabis) jml from (
						    select base.*,
						    case when stok!='' and stok>(minimum_stock) then 1 else 0 end jmlTersedia,
						    case when stok!='' and stok<=(minimum_stock) and stok>0 then 1 else 0 end jmlHampirHabis,
						    case when stok='' and stok<=0 then 1 else 0 end jmlHabis
						    from (
						        SELECT id, minimum_stock, (select sum(qty) jml from product_detail_stock where id_product=a.id) stok FROM `produk` a
						    ) as base
						) as final
					");
			$d 		= array_shift($g);
			$jml 	= $d['jml'];

			echo $jml;
		}else{
            redirect('/login');
        }
	}

	public function getjmlproductHabis(){
		if(checkingsessionpwt()){
			$g		= $this->query->getDatabyQ("
						select sum(jmlHabis) jml from (
						    select base.*,
						    case when stok!='' and stok>(minimum_stock) then 1 else 0 end jmlTersedia,
						    case when stok!='' and stok<=(minimum_stock) and stok>0 then 1 else 0 end jmlHampirHabis,
						    case when stok='' and stok<=0 then 1 else 0 end jmlHabis
						    from (
						        SELECT id, minimum_stock, (select sum(qty) jml from product_detail_stock where id_product=a.id) stok FROM `produk` a
						    ) as base
						) as final
					");
			$d 		= array_shift($g);
			$jml 	= $d['jml'];

			echo $jml;
		}else{
            redirect('/login');
        }
	}

	public function getproductbygudang($id){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'kode'			=> true,
				'nama'			=> true,
				'kuantitas'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/productbygudang/'.$id.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function gettransactionbygudang($id){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'tipe'			=> true,
				'jumlah'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/transactionbygudang/'.$id.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdataprodukkategori(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'nama'			=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataprodukkategori';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalkategoriproduk(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$qKontak 	= "
						select * from(
							select
								a.*,
								(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Kategori Produk' AND xa.data = a.id_category ORDER BY xa.date_time DESC limit 1)as update_by,
								(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Kategori Produk' AND xa.data = a.id_category ORDER BY xa.date_time DESC limit 1)as last_update
							from
							produk_category a
						) as base
						where 1=1 and id_category='$id'
						ORDER BY id_category desc
						";
			$gKontak 	= $this->query->getDatabyQ($qKontak);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($gKontak as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}

	public function insertkategoriproduk(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$namagroup	= trim(strip_tags(stripslashes($this->input->post('namagroup',true))));
			
			$rows 		= $this->query->insertData('produk_category', "id_category,category", "'','$namagroup'");
			$id			= $this->db->insert_id();
			$url 		= "Kategori Produk";
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

	public function deletekategoriproduk(){
		if(checkingsessionpwt()){
			$url 		= "Kategori Produk";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddelgroup',true))));
			
			$rows = $this->query->deleteData('produk_category','id_category',$cond);
			
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

	public function getdataprodukunit(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'nama'			=> true,
				'updateby'		=> true,
				'lastupdate'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataprodukunit';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function modalunitproduk(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$qKontak 	= "
						select * from(
							select
								a.*,
								(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Unit Produk' AND xa.data = a.id_unit ORDER BY xa.date_time DESC limit 1)as update_by,
								(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Unit Produk' AND xa.data = a.id_unit ORDER BY xa.date_time DESC limit 1)as last_update
							from
							produk_unit a
						) as base
						where 1=1 and id_unit='$id'
						ORDER BY id_unit desc
						";
			$gKontak 	= $this->query->getDatabyQ($qKontak);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($gKontak as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}

	public function insertunitproduk(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$namagroup	= trim(strip_tags(stripslashes($this->input->post('namaunit',true))));
			
			$rows 		= $this->query->insertData('produk_unit', "id_unit,unit", "'','$namagroup'");
			$id			= $this->db->insert_id();
			$url 		= "Unit Produk";
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

	public function deleteunitproduk(){
		if(checkingsessionpwt()){
			$url 		= "Unit Produk";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddelunit',true))));
			
			$rows = $this->query->deleteData('produk_unit','id_unit',$cond);
			
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

	public function insertproduk(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$query = $this->query->getData('produk','max(id)+1 as id','');
			$getID = array_shift($query);
			if ($getID['id']=='') {
				$id = '1';
			} else {
				$id = $getID['id'];
			}

			$name			= trim(strip_tags(stripslashes($this->input->post('nama',true))));
			$kode			= trim(strip_tags(stripslashes($this->input->post('kode',true))));
			$kategori		= $this->input->post('kategori',true);
			$unit			= trim(strip_tags(stripslashes($this->input->post('unit',true))));
			$deskripsi 		= trim(strip_tags(stripslashes($this->input->post('deskripsi',true))));
			$akunpembelian 	= trim(strip_tags(stripslashes($this->input->post('akunpembelian',true))));
			$akunpenjualan 	= trim(strip_tags(stripslashes($this->input->post('akunpenjualan',true))));
			$akunpersediaan = trim(strip_tags(stripslashes($this->input->post('akunpersediaan',true))));
			$maxstok 		= trim(strip_tags(stripslashes($this->input->post('maxstok',true))));
			$date 			= date('Y-m-d H:i:s');

			$hargabeli 		= trim(strip_tags(stripslashes($this->input->post('hargabeli',true))));
			$hargajualsales = trim(strip_tags(stripslashes($this->input->post('hargajualsales',true))));
			$hargajualtoko 	= trim(strip_tags(stripslashes($this->input->post('hargajualtoko',true))));
			$hargajualtb 	= trim(strip_tags(stripslashes($this->input->post('hargajualtb',true))));
			$hargajualkasir = trim(strip_tags(stripslashes($this->input->post('hargajualkasir',true))));

			$cekfile 		= $_FILES['file']['name'];
			
			if ($cekFile!='') {
				$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['file']['name']);
					
				$config['upload_path'] 		= './images/product/'; //buat folder dengan nama assets di root folder
				$config['file_name'] 		= $kode.$fileName;
				$config['allowed_types'] 	= 'gif|jpg|png|jpeg|JPG';
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('file') )
				$this->upload->display_errors();
					 
				$media 			= $this->upload->data();
				$fileNamePost 	= $media['file_name'];
				$this->makeThumbnails('product',$fileNamePost);
			} else {
				$fileNamePost 	= '';
			}

			$q 			= "
						insert into produk (id,name,product_code,description,created_at,gudang,unit,image,buy_account,sell_account,inventory_account,average_price,minimum_stock,harga_beli,harga_sales,harga_toko,harga_tukangbor,harga_kasir) values 
						('$id','$name','$kode', '$deskripsi','$date','','$unit','$fileNamePost','$akunpembelian','$akunpenjualan','$akunpersediaan','0','$maxstok','$hargabeli','$hargajualsales','$hargajualtoko','$hargajualtb','$hargajualkasir')
						";
			$rows 		= $this->query->insertDatabyQ($q);

			if($rows) {
				// PRODUCT CATEGORY
				$jmlCat 	= count($kategori);
				for ($i=0;$i<$jmlCat;$i++) {
					$qCat 		= "
								insert into produk_detail_category (id_pdc,id_category,id_product) values 
								('','$kategori[$i]','$id')
								";
					$rowsCat	= $this->query->insertDatabyQ($qCat);
				}

				// PRODUCT STOCK
				// $qStok 		= "insert into product_detail_stock 
				// 			(id_pds,id_product,qty,tanggal,harga_beli,id_gudang) values 
				// 			('','$id','0','$date','$hargabeli','')";
				// $rowStok 	= $this->query->insertDatabyQ($qStok);

				$url 		= "Manage Product";
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

	public function modalproduk(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$qKontak 	= "
						select base.*, 
							(select unit from produk_unit where id_unit=base.unit) unitname,
							(select GROUP_CONCAT(id_category) from produk_detail_category where id_product=base.id) kategori,
							(select GROUP_CONCAT(b.category) from produk_detail_category a left join produk_category b on a.id_category=b.id_category where id_product=base.id) namakategori,
							(select sum(qty) qty from product_detail_stock where id_product=base.id) qty 
						from(
							select
								a.*,
								(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Manage Product' AND xa.data = a.id ORDER BY xa.date_time DESC limit 1)as update_by,
								(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Manage Product' AND xa.data = a.id ORDER BY xa.date_time DESC limit 1)as last_update
							from
							produk a
						) as base
						where 1=1 and id='$id'
						ORDER BY last_update desc
						";
			$gKontak 	= $this->query->getDatabyQ($qKontak);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($gKontak as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}

	public function updateproduk(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			$userid 	= $userdata['userid'];

			$id				= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('ed_nama',true))));
			$kode			= trim(strip_tags(stripslashes($this->input->post('ed_kode',true))));
			$kategori		= $this->input->post('ed_kategori',true);
			$unit			= trim(strip_tags(stripslashes($this->input->post('ed_unit',true))));
			$deskripsi 		= trim(strip_tags(stripslashes($this->input->post('ed_deskripsi',true))));
			$akunpembelian 	= trim(strip_tags(stripslashes($this->input->post('ed_akunpembelian',true))));
			$akunpenjualan 	= trim(strip_tags(stripslashes($this->input->post('ed_akunpenjualan',true))));
			$akunpersediaan = trim(strip_tags(stripslashes($this->input->post('ed_akunpersediaan',true))));
			$maxstok 		= trim(strip_tags(stripslashes($this->input->post('ed_maxstok',true))));
			$date 			= date('Y-m-d H:i:s');

			$hargabeli 		= trim(strip_tags(stripslashes($this->input->post('ed_hargabeli',true))));
			$hargajualsales = trim(strip_tags(stripslashes($this->input->post('ed_hargajualsales',true))));
			$hargajualtoko 	= trim(strip_tags(stripslashes($this->input->post('ed_hargajualtoko',true))));
			$hargajualtb 	= trim(strip_tags(stripslashes($this->input->post('ed_hargajualtb',true))));
			$hargajualkasir = trim(strip_tags(stripslashes($this->input->post('ed_hargajualkasir',true))));

			$cekfile 		= @$_FILES['ed_file']['name'];
			
			if ($cekfile!='') {
				$fileName 	= preg_replace("/[^a-zA-Z]/", "", time().$_FILES['ed_file']['name']);
					
				$config['upload_path'] 		= './images/product/'; //buat folder dengan nama assets di root folder
				$config['file_name'] 		= $kode.$fileName;
				$config['allowed_types'] 	= 'gif|jpg|png|jpeg|JPG';
				 
				$this->load->library('upload');
				$this->upload->initialize($config);
				 
				if(! $this->upload->do_upload('ed_file') )
				$this->upload->display_errors();
					 
				$media 			= $this->upload->data();
				$fileNamePost 	= $media['file_name'];
				$this->makeThumbnails('product',$fileNamePost);

				$updFile 		= "image='$fileNamePost',";
			} else {
				$updFile 	= '';
			}

			$rows 			= $this->query->updateData('produk',
							"name='$name', product_code='$kode',description='$deskripsi',updated_at='$date',unit='$unit', $updFile buy_account='$akunpembelian',
							sell_account='$akunpenjualan', inventory_account='$akunpersediaan', minimum_stock='$maxstok', harga_beli='$hargabeli', 
							harga_sales='$hargajualsales', harga_toko='$hargajualtoko', harga_tukangbor='$hargajualtb', harga_kasir='$hargajualkasir'",
							"WHERE id='$id'"
							);
			// 
			if($rows) {
				// PRODUCT CATEGORY
				$delFirst 	= $this->query->deleteData('produk_detail_category','id_product',$id);
				$jmlCat 	= count($kategori);
				for ($i=0;$i<$jmlCat;$i++) {
					$qCat 		= "
								insert into produk_detail_category (id_pdc,id_category,id_product) values 
								('','$kategori[$i]','$id')
								";
					$rowsCat	= $this->query->insertDatabyQ($qCat);
				}

				// PRODUCT STOCK
				// $qStok 		= "insert into product_detail_stock 
				// 			(id_pds,id_product,qty,tanggal,harga_beli,id_gudang) values 
				// 			('','$id','0','$date','$hargabeli','')";
				// $rowStok 	= $this->query->insertDatabyQ($qStok);

				$url 		= "Manage Product";
				$activity 	= "Update";

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

	public function deleteproduk(){
		if(checkingsessionpwt()){
			$url 		= "Manage Product";
			$activity 	= "DELETE";

			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));

			$coba = $this->query->getData('produk','image',"WHERE id='".$cond."'");
			
			foreach ($coba as $dataex) {
				$dataexis = 'images/product/'.$dataex['image'];
				@unlink($dataexis);
			}
			
			$rows 	= $this->query->deleteData('produk','id',$cond);
			$rows2 	= $this->query->deleteData('produk_detail_category','id_product',$cond);
			$rows3 	= $this->query->deleteData('product_detail_stock','id_product',$cond);
			
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

	public function insertbilion(){
		for ($i=0;$i<1000000;$i++) {
			$q 			= "
						INSERT INTO `sample_big` (`a`, `b`, `c`, `d`, `e`, `f`, `g`, `h`, `i`, `j`, `k`, `l`, `m`, `n`, `o`, `p`, `q`, `r`, `s`, `t`, `u`, `v`, `w`, `x`, `y`, `z`, `a1`, `b1`, `c1`, `d1`, `e1`, `f1`, `g1`, `h1`, `i1`, `j1`, `k1`, `l1`, `m1`, `n1`, `o1`, `p1`, `q1`, `r1`, `s1`, `t1`, `u1`, `v1`, `w1`, `x1`, `y1`, `z1`, `a2`, `b2`, `c2`, `d2`, `e2`, `f2`, `g2`, `h2`, `i2`, `j2`, `k2`, `l2`, `m2`, `n2`, `o2`, `p2`, `q2`, `r2`, `s2`, `t2`, `u2`, `v2`, `w2`, `x2`, `y2`, `z2`, `a3`, `b3`) VALUES ('0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0')
						";
			$rows 		= $this->query->insertDatabyQDB2($q);
		}
		if ($rows) {
			echo $i."<br>";
		}
	}

	public function modalstockadjustment(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$qKontak 	= "
						select * from(
							select
								a.*,
						    	case when warehouse='-1' then 'Unassigned' else (select nama_gudang from gudang where id_gudang=a.warehouse) end warehouse_name,
						    	(select name from adjustment_type where id_type=a.type_id) adjustment_type,
						    	(select name from account where id=a.account) account_name,
								(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Penyesuaian Stok' AND xa.data = a.id_adjustment ORDER BY xa.date_time DESC limit 1)as update_by,
								(SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
								WHERE xa.menu='Penyesuaian Stok' AND xa.data = a.id_adjustment ORDER BY xa.date_time DESC limit 1)as last_update
							from
							adjustment_stock a
						) as base
						where 1=1 and id_adjustment='$id'
						ORDER BY last_update desc
						";
			$gKontak 	= $this->query->getDatabyQ($qKontak);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($gKontak as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}

	public function getdatastok(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'no_transaksi'	=> true,
				'tipe'			=> true,
				'akun'			=> true,
				'gudang'		=> true,
				'tag'			=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datastok';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatatransfer(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'notransaksi'	=> true,
				'dari'			=> true,
				'ke'			=> true,
				'memo'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datatransfer';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatastokbyproduct($id){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'tipe'			=> true,
				'jumlahproduct'	=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datastokbyproduct/'.$id;

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatastokbygudang($id){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'tipe'			=> true,
				'jumlahproduct'	=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datastokbygudang/'.$id;

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatastokadjustmentdetail($id){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'produk'			=> true,
				'kode_produk'		=> true,
				'jumlah_tercatat'	=> true,
				'jumlah_sebenarnya'	=> true,
				'perbedaan'			=> true,
				'average_price'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datastokadjustmentdetail/'.$id;

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatatransferdetail($id){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'produk'			=> true,
				'kode_produk'		=> true,
				'qty'				=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datatransferdetail/'.$id;

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdataasettertunda(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'	=> true,
				'barang'	=> true,
				'faktur'	=> true,
				'biaya'		=> true,
				'actions'	=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataasettertunda/';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdataasetaktif(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'	=> true,
				'detail'	=> true,
				'akun'		=> true,
				'biaya'		=> true,
				'nilai'		=> true,
				'actions'	=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataasetaktif/';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdataasetdijual(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'detail'		=> true,
				'notransaksi'	=> true,
				'harga'			=> true,
				'untung'		=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataasetdijual/';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdataasetpenyusutan(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'detail'		=> true,
				'periode'		=> true,
				'nilai'			=> true,
				'metode'		=> true,
				'penyusutan'	=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataasetpenyusutan/';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function gettransactionbytag($id){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'nomor'			=> true,
				'deskripsi'		=> true,
				'user'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datatransaksi/tags/'.$id.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatatransaksiproduk($id){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'tipe'			=> true,
				'jumlahproduct'	=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datatransaksi/produk/'.$id.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function gettransaksibygudang($id){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'tipe'			=> true,
				'jumlahproduct'	=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datatransaksi/warehouse/'.$id.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function gettransaksiinvoice($type){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'nomor'			=> true,
				'pelanggan'		=> true,
				'jatuhtempo'	=> true,
				'status'		=> true,
				'sisa'			=> true,
				'total'			=> true,
				'tags'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/transaksisales/penjualan/'.$type.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function gettransaksideliveries($type){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'nomor'			=> true,
				'pelanggan'		=> true,
				'status'		=> true,
				'tags'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/transaksisales/penjualan/'.$type.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function gettransaksiorder($type){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'nomor'			=> true,
				'pelanggan'		=> true,
				'jatuhtempo'	=> true,
				'status'		=> true,
				'total'			=> true,
				'tags'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/transaksisales/penjualan/'.$type.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function gettransaksiinvoice_purchase($type){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'nomor'			=> true,
				'pelanggan'		=> true,
				'jatuhtempo'	=> true,
				'status'		=> true,
				'sisa'			=> true,
				'total'			=> true,
				'tags'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/transaksipurchase/pembelian/'.$type.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function gettransaksideliveries_purchase($type){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'nomor'			=> true,
				'pelanggan'		=> true,
				'status'		=> true,
				'tags'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/transaksipurchase/pembelian/'.$type.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function gettransaksiorder_purchase($type){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'nomor'			=> true,
				'pelanggan'		=> true,
				'jatuhtempo'	=> true,
				'status'		=> true,
				'total'			=> true,
				'tags'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/transaksipurchase/pembelian/'.$type.'';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function gettransaksiexpense(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'nomor'			=> true,
				'kategori'		=> true,
				'penerima'		=> true,
				'status'		=> true,
				'sisatagihan'	=> true,
				'total'			=> true,
				'tags'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/transaksiexpense';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	// TAGS 
	public function gettaglist(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'nama'			=> true,
				'penjualan'		=> true,
				'pembelian'		=> true,
				'pengeluaran'	=> true,
				'lainnya'		=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/taglist';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}
	public function inserttags(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$name		= trim(strip_tags(stripslashes($this->input->post('name',true))));
			
			$rows 		= $this->query->insertData('tags', "tags", "'$name'");

			$id			= $this->db->insert_id();
			$url 		= "Tag List";
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

	public function modaltag(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataUsr			= $this->query->getData('tags','*',"WHERE id_tags='".$id."'");
			
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
	public function updatetags(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('ed_name',true))));

			$rows = $this->query->updateData('tags',"tags='$name'","WHERE id_tags='$id'");
			if($rows) {
				print json_encode(array('success'=>true,'total'=>1));
				$log = $this->query->insertlog($activity,$url,$id);
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	
	public function deletetags(){
		if(checkingsessionpwt()){
			$url 		= "Tag List";
			$activity 	= "DELETE";
			
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$rows = $this->query->deleteData('tags','id_tags',$cond);
			
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

	// PAYMENT METHOD 
	public function getpaymentmethod(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'nama'			=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datapaymentmethod';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}
	public function insertpaymentmethod(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$name		= trim(strip_tags(stripslashes($this->input->post('name',true))));
			
			$rows 		= $this->query->insertData('payment_method', "name", "'$name'");

			$id			= $this->db->insert_id();
			$url 		= "Payment Method";
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

	public function modalpaymentmethod(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataUsr			= $this->query->getData('payment_method','*',"WHERE id='".$id."'");
			
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
	public function updatepaymentmethod(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('ed_name',true))));

			$rows = $this->query->updateData('payment_method',"name='$name'","WHERE id='$id'");

			$url 		= "Payment Method";
			$activity 	= "UPDATE";
			if($rows) {
				print json_encode(array('success'=>true,'total'=>1));
				$log = $this->query->insertlog($activity,$url,$id);
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	
	public function deletepaymentmethod(){
		if(checkingsessionpwt()){
			$url 		= "Payment Method";
			$activity 	= "DELETE";
			
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$rows = $this->query->deleteData('payment_method','id',$cond);
			
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

	// TERM RESOURCE
	public function getterm(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'nama'			=> true,
				'waktu'			=> true,
				'actions'		=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/dataterm';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}
	public function insertterm(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 

			$name		= trim(strip_tags(stripslashes($this->input->post('name',true))));
			$waktu		= trim(strip_tags(stripslashes($this->input->post('waktu',true))));
			
			$rows 		= $this->query->insertData('term_resource', "name,longetivity", "'$name','$waktu'");

			$id			= $this->db->insert_id();
			$url 		= "Term Resource";
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

	public function modalterm(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataUsr			= $this->query->getData('term_resource','*',"WHERE id_term='".$id."'");
			
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
	public function updateterm(){
		if(checkingsessionpwt()){
			$userdata	= $this->session->userdata('sesspwt'); 
			
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_id',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('ed_name',true))));
			$waktu			= trim(strip_tags(stripslashes($this->input->post('ed_waktu',true))));

			$rows = $this->query->updateData('term_resource',"name='$name', longetivity='$waktu'","WHERE id_term='$id'");

			$url 		= "Term Resource";
			$activity 	= "UPDATE";
			if($rows) {
				print json_encode(array('success'=>true,'total'=>1));
				$log = $this->query->insertlog($activity,$url,$id);
			} else {
				echo "";
			}
		} else {
			redirect('/panel');
		}
	}	
	public function deleteterm(){
		if(checkingsessionpwt()){
			$url 		= "Term Resource";
			$activity 	= "DELETE";
			
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('iddel',true))));
			
			$rows = $this->query->deleteData('term_resource','id_term',$cond);
			
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

	public function gettransactionall(){
		if(checkingsessionpwt()){

			$columnsDefault = [
				'tanggal'		=> true,
				'nomor'			=> true,
				'memo'			=> true,
				'user'			=> true,
				'duedate'		=> true,
				'status'		=> true,
				'sisa'			=> true,
				'total'			=> true,
				'tags'			=> true,
			];
			$arraynya	= $columnsDefault;
			$jsonfile	= base_url().'jsondata/datatransaksiall';

			$this->generateDatatable($arraynya,$jsonfile);
		} else {
			redirect('/panel');
		}
	}

	public function getdatailkontak(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$qKontak 	= "
						select * from kontak where id_kontak='$id'
						";
			$gKontak 	= $this->query->getDatabyQ($qKontak);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($gKontak as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}

	public function getlastinvtransaksino(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));

			$qKontak 	= "
						SELECT MAX(cek) max from (
							SELECT cast(REPLACE(REPLACE(upper(transaction_no),'PA',''),'O','') as integer) cek FROM sales WHERE type=$id order by 1 desc
						) as coba
						";
			$gKontak 	= $this->query->getDatabyQ($qKontak);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($gKontak as $row) {
					echo json_encode($row);
					exit;
				}
			}
		} else {
			redirect('/panel');
		}
	}

	public function getduedate(){
		if(checkingsessionpwt()){
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			$tanggal			= trim(strip_tags(stripslashes($this->input->post('tanggal',true))));

			$qKontak 	= "
						SELECT * from term_resource where id_term='$id'
						";
			$gKontak 	= $this->query->getDatabyQ($qKontak);
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($gKontak as $row) {
					$termin 	= $row['longetivity'];
					$date 		= date('Y-m-d',strtotime('+'.$termin.' days',strtotime($tanggal)));
					$rows 		= array(
									'duedate' 	=> $date
								);
					$json 		= $rows;
				}
				echo json_encode($json);
			}
		} else {
			redirect('/panel');
		}
	}


}
