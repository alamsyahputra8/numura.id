<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pm extends CI_Controller {

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
	private $akses;
	private $profile;
	public function __construct(){
		date_default_timezone_set("Asia/Bangkok");
        parent::__construct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-chace');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
		$this->load->library(array('PHPExcel','PHPExcel/IOFactory'));
		$this->load->model('query'); 
		$this->load->model('lop_model');
		$this->load->model('formula'); 
		$session	 = $this->session->userdata('sesselop'); 
		$this->profile 	 = $session['id_role'];
    }
	
	public function index(){
		if(checkingsessionelop()) {
			$data['akses']= $this->akses;
			$this->load->view('panel/pm/manage',$data);
		} else {
			redirect('/panel');
		}
	}
	public function test(){
		function removeFromString($str, $item) {
			$parts = explode(',', $str);

			while(($i = array_search($item, $parts)) !== false) {
				unset($parts[$i]);
			}

			return implode(',', $parts);
		}
		echo removeFromString('one', 'one');
	}
	public function modalpmlop(){
		if(checkingsessionelop()){
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('pm_lop','id_pm,nama_pm,phone,id_lop as idlop',"WHERE id_pm='".$id."' ORDER BY id_pm DESC");
			
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
	public function deletepmlop(){
		if(checkingsessionelop()){
			$this->load->model('query');
			$url 			= "Manage PM LOP";
			$activity 		= "DELETE";
			$idlop			= trim(strip_tags(stripslashes($this->input->post('idlop',true))));
			$cond			= trim(strip_tags(stripslashes($this->input->post('id',true))));
			// $datalop			= $this->query->getData('lop','id_lop,id_pm',"WHERE id_lop IN (".$idlop.")");
			// function removeFromString($str, $item) {
				// $parts = explode(',', $str);

				// while(($i = array_search($item, $parts)) !== false) {
					// unset($parts[$i]);
				// }

				// return implode(',', $parts);
			// }
			// foreach($datalop as $row) {
				// $newvalue = removeFromString($row['id_pm'], $cond);
				
				// $updateLOP = $this->query->updateData('lop',"id_pm='$newvalue'","WHERE id_lop='".$row['id_lop']."'");
			// }
			$rows 			= $this->query->deleteData2('pm_lop','id_pm',$cond);
			
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
	public function insertpmlop(){
		if(checkingsessionelop()){
			$userdata	= $this->session->userdata('sesselop'); 
			date_default_timezone_set("Asia/Bangkok");
			$url 			= "Manage PM LOP";
			$activity 		= "INSERT";
			$name			= trim(strip_tags(stripslashes($this->input->post('name',true))));
			$phone			= trim(strip_tags(stripslashes($this->input->post('phone',true))));
			$idlop			= trim(strip_tags(stripslashes($this->input->post('idlop_data',true))));
			$rows			= $this->query->insertData('pm_lop', "id_pm,nama_pm,phone,id_lop", "'','$name','$phone','$idlop'");
			$id			 	= $this->db->insert_id();
			
			//CEK VALUE YANG ADA
			// $datalop			= $this->query->getData('lop','id_lop,id_pm',"WHERE id_lop IN (".$idlop.")");
			// foreach($datalop as $row) {
				// if($row['id_pm']=='0' OR $row['id_pm']==NULL OR $row['id_pm']==''){
					// $dataid=$id;
				// }else{
					// $dataid=$row['id_pm'].",".$id;
				// }
				// $updateLOP = $this->query->updateData('lop',"id_pm='$dataid'","WHERE id_lop='".$row['id_lop']."'");
			// }
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
	
	public function updatepmlop(){
		if(checkingsessionelop()){
			$url 			= "Manage PM LOP";
			$activity 		= "UPDATE";
			$id				= trim(strip_tags(stripslashes($this->input->post('ed_idpm',true))));
			$name			= trim(strip_tags(stripslashes($this->input->post('ed_name',true))));
			$phone			= trim(strip_tags(stripslashes($this->input->post('ed_phone',true))));
			$idlop			= trim(strip_tags(stripslashes($this->input->post('ed_idlop_data',true))));
			$rows = $this->query->updateData('pm_lop',"nama_pm='$name',phone='$phone',id_lop='$idlop'","WHERE id_pm='".$id."'");
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
	
	public function getdatapmlop(){
		if(checkingsessionelop()){
			$data_aksess 	= $this->query->getAkses($this->profile,'panel/pm');
			$shift 			= array_shift($data_aksess);
			$akses 			= $shift['akses'];
			// $getData		= $this->query->getData('pm_lop a','a.*,(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage PM LOP" AND xa.data = a.id_pm ORDER BY xa.date_time DESC limit 1)as update_by,(SELECT DATE_FORMAT(xa.date_time, "%d-%b-%y %H:%i:%s") as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage PM LOP" AND xa.data = a.id_pm ORDER BY xa.date_time DESC limit 1) as last_update','ORDER BY id_pm DESC');
			$getData		= $this->query->getData('pm_lop a','a.*,(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage PM LOP" AND xa.data = a.id_pm ORDER BY xa.date_time DESC limit 1)as update_by,(SELECT DATE_FORMAT(xa.date_time, "%d-%b-%y %H:%i:%s") as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage PM LOP" AND xa.data = a.id_pm ORDER BY xa.date_time DESC limit 1) as last_update','ORDER BY id_pm DESC');
			$no=0;
			foreach($getData as $data) { 
				
				$no++;
				$id = $data['id_pm'];
				//echo 'SELECT GROUP_CONCAT(id_lop)as idlop FROM lop WHERE id_pm "%'.$id.'%"'
				// $getlop		= $this->query->getDatabyQ('SELECT GROUP_CONCAT(id_lop)as idlop FROM lop WHERE id_pm LIKE "%'.$id.'%"');
			    // $datalop 	= array_shift($getlop);
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					"",
					$data['nama_pm'],
					$data['phone'],
					$data['id_lop'],
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
}