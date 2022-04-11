<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lo extends CI_Controller {

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
		$this->load->model('lo_model');
		$this->load->model('formula'); 
		$session	 = $this->session->userdata('sesselop'); 
		$this->profile 	 = $session['id_role'];
    }
	
	public function index(){
		if(checkingsessionelop()) {
			$data['akses']= $this->akses;
			$this->load->view('panel/lo/lo_serverside',$data);
		} else {
			redirect('/panel');
		}
	}
	public function getDatalo(){
		ini_set('max_execution_time', 123456);
        ini_set("memory_limit","1256M");
		$data_aksess = $this->query->getAkses($this->profile,'panel/lo');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];
		$list = $this->lo_model->get_datatables();
		
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $datalo) {
			$id = $datalo->id_lo;
			// $buttonupdate = getRoleUpdate_Custom($akses,'update',$id,$datalo->tahun);
			// $buttondelete = getRoleDelete_Custom($akses,'delete',$id,$datalo->tahun);
			$buttonupdate = getRoleUpdate($akses,'update',$id);
			$buttondelete = getRoleDelete($akses,'delete',$id);
			
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $datalo->tahun;
            $row[] = $id;
			$row[] = $datalo->id_map;
			$row[] = $datalo->nama_gc;
			$row[] = $datalo->satker_lo;
			$row[] = cleanstring($datalo->nama_keg);
			$row[] = (int)$datalo->nilai_pagu;
			$row[] = $datalo->ta;
			$row[] = $datalo->sumber_dana;
			$row[] = $datalo->nama_divisi;
			$row[] = $datalo->nama_segmen;
			$row[] = $datalo->treg;
			$row[] = $datalo->nama_witel;
			$row[] = $datalo->last_update;
			$row[] = $datalo->update_by;
			$row[] = $buttonupdate.$buttondelete;
			
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->lo_model->count_all(),
                        "recordsFiltered" => $this->lo_model->count_filtered(),
                        "data" => $data,
                );
		//echo $this->db->last_query(); 
        echo json_encode($output);
	}
	public function exportexcel(){
		ini_set('max_execution_time', 123456);
        ini_set("memory_limit","1256M");
		$data_aksess = $this->query->getAkses($this->profile,'panel/lop');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];
		$userdata 			= $this->session->userdata('sesselop'); 
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
		if (fixURL($witel)=='ALLWITEL' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and cid_witel='".$witel."'"; }
		if (fixURL($am)=='ALLAM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
		
		$cond_where = $wheredivisi.$wheresegmen.$wheretreg.$wherewitel.$wheream;
		
		//$this->db->from($this->table);
		$this->db->select('* FROM (SELECT a.*,(SELECT SUM(pagu_proj)as pagu_lo FROM lop WHERE id_lo=a.id_lo)as pagu_lo,b.id_segmen,(select nama_segmen from segmen where id_segmen = b.id_segmen)as nama_segmen,b.id_witel,(select nama_witel from witel WHERE id_witel=b.id_witel)as nama_witel,b.treg,b.nipnas,b.nik_am,c.nama_gc,b.id_divisi,(select nama_divisi from divisi where id_divisi=b.id_divisi)as nama_divisi,(SELECT DATE_FORMAT(date_time, "%d-%b-%y %H:%i:%s")  FROM data_log WHERE data = a.id_lo AND menu="Manage Lo" GROUP BY date_time ORDER BY date_time DESC limit 1)as last_update,(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage Lo" AND xa.data = a.id_lo ORDER BY xa.date_time DESC limit 1)as update_by,(SELECT tahun from map where id_map=a.id_map group by tahun) tahun');
		$this->db->from('lo a');
		$this->db->join('map b', 'a.id_map=b.id_map','left');
		$this->db->join('gc c', 'b.nipnas = c.nipnas ','left');
		$this->db->where('a.id_lo!="")as master WHERE id_lo!="" '.$cond_where.$uam);
		$query = $this->db->get();
		$list = $query->result();
        $data = array();
        $no = 0;
		$tbody='';
        foreach ($list as $datalo) {
			
            $no++;
           
             $tbody .= "<tr><td>".$no."</td>";
             $tbody .= "<td>".$datalo->tahun."</td>";
             $tbody .= "<td>".$datalo->id_lo."</td>";
			 $tbody .= "<td>".$datalo->id_map."</td>";
			 $tbody .= "<td>".$datalo->nama_gc."</td>";
			 $tbody .= "<td>".$datalo->satker_lo."</td>";
			 $tbody .= "<td>".cleanstring($datalo->nama_keg)."</td>";
			 $tbody .= "<td>".(int)$datalo->nilai_pagu."</td>";
			 $tbody .= "<td>".$datalo->ta."</td>";
			 $tbody .= "<td>".$datalo->sumber_dana."</td>";
			 $tbody .= "<td>".$datalo->nama_divisi."</td>";
			 $tbody .= "<td>".$datalo->nama_segmen."</td>";
			 $tbody .= "<td>".$datalo->treg."</td>";
			 $tbody .= "<td>".$datalo->nama_witel."</td>";
			 $tbody .= "<td>".$datalo->last_update."</td>";
			 $tbody .= "<td>".$datalo->update_by."</td></tr>";
        }
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: attachment; filename=DATA_LO.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo '
		<table id="tblo2" class="table dataTableExample2 table-hover" style="width: 150%;" border="1px">
			<thead class="bg-gray-dark text-white">
				<tr>
					<th>No</th>
					<th class="text-center">TAHUN</th>
					<th class="text-center">ID LO</th>
					<th class="text-center">ID MAPPING</th>
					<th class="text-center" style="max-width:150px !important;">K/L/D/I</th>
					<th class="text-center" style="max-width:150px !important;">SATKER</th>
					<th class="text-center" style="max-width:150px !important;">NAMA KEGIATAN</th>
					<th class="text-center">PAGU LO</th>
					<th class="text-center">TA</th>
					<th class="text-center">SUMBER DANA</th>
					<th class="text-center">DIVISI</th>
					<th class="text-center">SEGMEN</th>
					<th class="text-center">TREG</th>
					<th class="text-center">WITEL</th>
					<th class="text-center">LAST UPDATE</th>
					<th class="text-center">UPDATE BY</th>
					
				</tr>
			</thead>
			<tbody>
				'.$tbody.'
			</tbody>
		</table>
		';
	}
	
}