<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	public function index()
	{
		echo date('Y');
		//$this->load->view('welcome_message');
	}
	
	public function mapping()
	{
			$query = '
				SELECT id_lop FROM lop 
			';
			$getData = $this->db->query($query)->result();
			foreach($getData as $row){
				$query2 = '
				SELECT b.treg FROM lo a LEFT JOIN lop c ON a.id_lo = c.id_lo LEFT JOIN map b ON a.id_map = b.id_map WHERE c.id_lop = "'.$row->id_lop.'"
				';
				$getData2 = $this->db->query($query2)->result();
				$num = $this->db->query($query2)->num_rows();
				foreach($getData2 as $row2){
					if($num !=0){
						// echo $row->id_proj."|".$row2->treg;
						$query = "
						update lop_temp set treg='".$row2->treg."' WHERE id_lop ='".$row->id_lop."'
						";
						$this->db->query($query);											
					}
				}
			}
	}
	public function mapping_log()
	{
			$query = '
				SELECT data FROM data_log WHERE menu="Manage Lop" 
			';
			$getData = $this->db->query($query)->result();
			foreach($getData as $row){
				$query2 = '
				SELECT b.treg FROM lo a LEFT JOIN lop c ON a.id_lo = c.id_lo LEFT JOIN map b ON a.id_map = b.id_map WHERE c.id_proj = "'.$row->data.'"
				';
				$getData2 = $this->db->query($query2)->result();
				$num = $this->db->query($query2)->num_rows();
				foreach($getData2 as $row2){
					if($num !=0){
						// echo $row->id_proj."|".$row2->treg;
						$query = "
						update data_log set treg='".$row2->treg."' WHERE data ='".$row->data."'
						";
						$this->db->query($query);											
					}
				}
			}
	}
	public function getlo(){
		$this->load->model('query'); 
		$id = 100001;
		$query = "
			SELECT b.treg FROM lo a LEFT JOIN map b ON a.id_map = b.id_map WHERE a.id_lo = $id
		";
		$data = $this->db->query($query)->result_array();
		$row =array_shift($data);
		echo $row['treg'];
	}
	
	public function remap_sr(){
		ini_set('max_execution_time', 123456);
        ini_set("memory_limit","1256M");
		$this->load->model('query'); 
		$query = "select id_lop,kode_raisa,status from lop WHERE id_sr=0";
		$data = $this->db->query($query)->result_array();
		foreach($data as $row){
			$query2 = "select id_sr,sr from status_raisa WHERE kode_raisa='".$row['kode_raisa']."' AND status='".$row['status']."'";
			$data2 = $this->db->query($query2)->result_array();
			$rr = array_shift($data2);
			//".$rr['id_sr']."echo $row['id_lop']."-".$row['kode_raisa']."-".$row['status']."-".$rr['id_sr']."-".$rr['sr']."<br>";
			$update	= $this->query->updateData('lop',"id_sr='".$rr['id_sr']."'","WHERE id_lop='".$row['id_lop']."'");
		}
		
		
	}
	public function remap_sr2(){
		$i=0;
		ini_set('max_execution_time', 123456);
        ini_set("memory_limit","1256M");
		$this->load->model('query'); 
		$query = "select id_lop,id_sr FROM lop limit 10";
		$data = $this->db->query($query)->result_array();
		foreach($data as $row){
			$update	= $this->query->updateData('lop_temp',"id_sr='".$row['id_sr']."'","WHERE id_proj='".$row['id_lop']."'");
			if($update){
				$i++;
			}
		}
		
		echo $i;
	}
	
	
	public function renameFile(){
		$this->load->model('query');
		$query = "
			SELECT a.*, b.id_lop, c.id_lo, d.id_map,
			(select nama_divisi from divisi where id_divisi=d.id_divisi) as divisi, 
			(select nama_segmen from segmen where id_segmen=d.id_segmen) as segmen, 
			d.treg,
			(select nama_witel from witel where id_witel=d.id_witel) as witel, d.nipnas FROM `file_lop` a
			left join lop b
			on a.id_lop=b.id_lop
			left join lo c
			on b.id_lo=c.id_lo
			left join map d
			on c.id_map=d.id_map
		";
		$n=0;
		$data = $this->db->query($query)->result_array();
		$num = $this->db->query($query)->num_rows();
		foreach($data as $row){
			if($num !=0){
				// $nama_file_New = $row['id_lo']."_".$row['id_sirup']."_".$row['treg']."_".$row['witel'].".pdf";
				
				$name_Old = $row['file'];
				$name_New = $row['divisi']."_".$row['segmen']."_".$row['treg']."_".$row['witel']."_".$row['nipnas']."_".$row['id_lop'].".pdf";
				
				$dirname_Old = "../files/kontrak/".$row['file'];
				$dirname_New = "../files/kontrak/".$row['divisi']."_".$row['segmen']."_".$row['treg']."_".$row['witel']."_".$row['nipnas']."_".$row['id_lop'].".pdf";
				
				if(file_exists($dirname_Old)=='1'){
					
				}else{
					$n++;
					echo $row['id_lop'].",";
				}
				
				// $update	= $this->query->updateData('file_lop',"file='$name_New'","WHERE id_lop='".$row['id_lop']."'");
				// if ($update) {
					// echo $row['id_lop']."--------".$name_Old." => ".$name_New."<hr>";
					// rename($dirname_Old, $dirname_New) ;
				// }
			}
		}
		echo $n;
	}
	public function datamenus(){
		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		);  

		$response = file_get_contents("http://localhost/amarindomandiri/jsondata/datamenus", false, stream_context_create($arrContextOptions)); 
		var_dump($response);
	}
}
