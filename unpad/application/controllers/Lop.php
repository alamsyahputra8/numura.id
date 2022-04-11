<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lop extends CI_Controller {

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
		$session	 	 = $this->session->userdata('sesselop'); 
		$this->profile 	 = $session['id_role'];
    }
	
	public function index(){
		if(checkingsessionelop()) {
			$data['akses']= $this->akses;
			$this->load->view('panel/lop/lop_serverside',$data);
		} else {
			redirect('/panel');
		}
	}
	
	public function getDataLop(){
		ini_set('max_execution_time', 123456);
        ini_set("memory_limit","1256M");
		$data_aksess = $this->query->getAkses($this->profile,'panel/lop');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];
		$list = $this->lop_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $datalop) {
			$id = $datalop->id_lop;
			
			
			$buttonupdate = getRoleUpdate($akses,'update',$id);
			$buttondelete = getRoleDelete($akses,'delete',$id);
			
			
			if($datalop->file_kb==''){
				@$button[$datalop->id_lop] ="";					
			}else{
				$data_file = explode(',',$datalop->file_kb);
				for($xxi = 0;$xxi < count($data_file);$xxi++){
					$buttondelete2 = getRoleDelete2($akses,'delete',$data_file[$xxi]);
					//@$button[$datalop->id_lop]  .="<center><a class='btn btn-xs btn-success btnFilePreview' data-toggle='modal' data-target='#FilePreview' data-file='".$data_file[$xxi]."' data-ext='".pathinfo($data_file[$xxi], PATHINFO_EXTENSION)."' data-nomor='".$data_file[$xxi]."' ><i data-toggle='tooltip' title='".$data_file[$xxi]."' class='glyphicon glyphicon-fullscreen'></i>&nbsp; View File</a>&nbsp;<a class='btn btn-xs btn-danger btnDeleteImage' data-toggle='modal' data-target='#deleteImage' alt='Delete File Kontrak' data-id='".$data_file[$xxi]."'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a></center>";																
					@$button[$datalop->id_lop]  .=$buttondelete2;																
				
				}
				 
			}
			$pem[$datalop->id_lop] = "";
			$data_pemenang = explode(',',$datalop->id_pemenang);
			for($xs = 0;$xs < count($data_pemenang);$xs++){
				$getDataPemenang 	= $this->query->getData('pemenang','*','WHERE id_pemenang="'.$data_pemenang[$xs].'"');
				$arr	 			= array_shift($getDataPemenang);
				if($arr['pemenang']==''){
					$pem[$datalop->id_lop]			= "";
				}else{
					$pem[$datalop->id_lop]			.= "<li>".$arr['pemenang']."</li>";						
				}
			}
			
			if ($datalop->PPN=='0') {
				$PPN	= 'NO';
			} else {
				$PPN	= 'YES';
			}
			
			if($datalop->sustain_dari !='0' and $datalop->sustain_dari !='' and $datalop->sustain_dari != NULL and $datalop->sustain_dari != '-'){
				// $sustain = "<center><input type='checkbox' checked id='sustain' disabled> YA</center>";
				$sustain = "<center class='text-success'><i class='fa fa-circle'></i> YA</center>";
			}else{
				// $sustain = "<center><input type='checkbox' id='sustain' disabled> TIDAK</center>";
				$sustain = "<center class='text-danger'><i class='fa fa-circle'></i> TIDAK</center>";
			}
			
			
			
			
			// $getdatapm 	= $this->query->getData('lop a','(select nama_pm from pm_lop where FIND_IN_SET("'.$datalop->id_lop.'",id_lop) > 0) as namapmlop','WHERE id_lop ='.$datalop->id_lop.'');
			// $arr_p		= array_shift($getdatapm);
			
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $datalop->tahun;
            $row[] = $datalop->id_lo;
            $row[] = $datalop->id_lop;
            $row[] = $datalop->id_sirup;
            $row[] = $datalop->id_lelang;
            $row[] = $datalop->k_l_d_i;
            $row[] = $datalop->satker_lo;
			$row[] = $datalop->nama_am;
			$row[] = $datalop->nama_pkt;
			$row[] = $sustain;
			$row[] = (int)$datalop->hps;
			$row[] = (int)$datalop->nilai_win;
			$row[] = (int)$datalop->ach;
			$row[] = $PPN;
			$row[] = $datalop->sr;
			$row[] = $datalop->kode_raisa;
			$row[] = $datalop->status;
			$row[] = $datalop->portfolio;
			$row[] = $datalop->nama_subs;
			$row[] = $pem[$datalop->id_lop];
			$row[] = $datalop->metode;
			$row[] = $datalop->waktu;
			$row[] = $datalop->tanggal;
			$row[] = $datalop->kategori;
			$row[] = $datalop->nama_divisi;
			$row[] = $datalop->nama_segmen;
			$row[] = $datalop->treg;
			$row[] = $datalop->nama_witel;
			$row[] = $datalop->nomor_kontrak;
			$row[] = $datalop->tanggal_kb;
			$row[] = $button[$datalop->id_lop] ;
			$row[] = $datalop->note;
			$row[] = $datalop->nomor_order;
			$row[] = $datalop->namapmlop;
			$row[] = $datalop->last_update;
			$row[] = $datalop->update_by;
			$row[] = $buttonupdate.$buttondelete;
			
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->lop_model->count_all(),
                        "recordsFiltered" => $this->lop_model->count_filtered(),
                        "data" => $data,
                );
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
		$session	 		= $this->session->userdata('sesselop'); 
		$profile 	 		= $session['id_role'];
		
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
		
		if (fixURL($divisi)=='ALLDIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
		if (fixURL($segmen)=='ALLSEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
		if (fixURL($treg)=='ALLTREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
		if (fixURL($witel)=='ALLWITEL' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and cid_witel='".$witel."'"; }
		if (fixURL($am)=='ALLAM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
		
		$where = $wheredivisi.$wheresegmen.$wheretreg.$wherewitel.$wheream;
		$cond_where = "";
		$this->db->select('* FROM(
			SELECT 
			*,
			CASE WHEN sustain_dari !=0 THEN "YA" ELSE "TIDAK" END as sustaining FROM (
				SELECT 
					CASE WHEN a.PPN="1" THEN "YES" ELSE "NO" END as ALIAS ,
					i.nama_divisi,
					j.nama_segmen,
					a.id_lo,
					a.id_lop,
					a.id_sirup,
					a.id_lelang,r.sr,d.nama_gc as k_l_d_i,b.satker_lo,e.nama_am,a.nama_pkt,a.pagu_proj,a.nilai_win,CASE WHEN a.PPN =1 THEN ((100/110)* a.nilai_win) ELSE a.nilai_win END ach,a.PPN,a.kode_raisa,a.status,a.portfolio,f.nama_subs,a.id_pemenang,a.sustain_dari,a.pagu_proj as hps,a.id_pm,(SELECT pemenang FROM pemenang WHERE id_pemenang=a.id_pemenang)as pemenang,a.metode,DATE_FORMAT(a.tanggal, "%d-%b-%y")as tanggal,DATE_FORMAT(a.waktu, "%b-%y")as waktu,a.kategori,c.treg,h.nama_witel,a.nomor_kontrak,DATE_FORMAT(a.tanggal_kb, "%d-%b-%y")as tanggal_kb,(SELECT GROUP_CONCAT(DISTINCT(xx.file))as file_kb FROM file_lop xx WHERE xx.id_lop = a.id_lop)as file_kb,a.ket as note,(SELECT GROUP_CONCAT(xxd.nomor_order)as nomor_order from  `order` xxd WHERE xxd.id_lop=a.id_lop)as nomor_order,DATE_FORMAT(a.last_update, "%d-%b-%y %H:%i:%s")as last_update,(SELECT xxc.name FROM user xxc WHERE xxc.userid=a.update_by)as update_by,`c`.`id_divisi`,`c`.`id_segmen`,`c`.`id_witel`,`c`.`nik_am`,`c`.`tahun`');
		$this->db->from('lop a');
		$this->db->join('lo b', 'a.id_lo=b.id_lo','left');
		$this->db->join('map c', 'b.id_map = c.id_map ','left');
		$this->db->join('gc d', 'd.nipnas = c.nipnas','left');
		$this->db->join('am e', 'c.nik_am = e.nik_am','left');
		$this->db->join('subs f', 'a.subs = f.id_subs','left');
		$this->db->join('witel h', 'c.id_witel=h.id_witel','left');
		$this->db->join('divisi i', 'c.id_divisi=i.id_divisi','left');
		$this->db->join('segmen j', 'c.id_segmen=j.id_segmen','left');
		$this->db->join('status_raisa r', 'r.id_sr=a.id_sr','left');
		$this->db->where('a.id_lop is not null)as master)as master2 WHERE id_lop is not null '.$cond_where.$uam);
		$query = $this->db->get();
        $list = $query->result();
        $data = array();
		$tbody='';
		$no =0;
        foreach ($list as $datalop) {
			$no++;
			$id = $datalop->id_lop;
			if($datalop->file_kb==''){
				@$button[$datalop->id_lop] ="";					
			}else{
				$data_file = explode(',',$datalop->file_kb);
				for($xxi = 0;$xxi < count($data_file);$xxi++){
					$buttondelete2 = getRoleDelete2($akses,'delete',$data_file[$xxi]);
					//@$button[$datalop->id_lop]  .="<center><a class='btn btn-xs btn-success btnFilePreview' data-toggle='modal' data-target='#FilePreview' data-file='".$data_file[$xxi]."' data-ext='".pathinfo($data_file[$xxi], PATHINFO_EXTENSION)."' data-nomor='".$data_file[$xxi]."' ><i data-toggle='tooltip' title='".$data_file[$xxi]."' class='glyphicon glyphicon-fullscreen'></i>&nbsp; View File</a>&nbsp;<a class='btn btn-xs btn-danger btnDeleteImage' data-toggle='modal' data-target='#deleteImage' alt='Delete File Kontrak' data-id='".$data_file[$xxi]."'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a></center>";																
					@$button[$datalop->id_lop]  .=$buttondelete2;																
				
				}
				 
			}
			$pem[$datalop->id_lop] = "";
			$data_pemenang = explode(',',$datalop->id_pemenang);
			for($xs = 0;$xs < count($data_pemenang);$xs++){
				$getDataPemenang 	= $this->query->getData('pemenang','*','WHERE id_pemenang="'.$data_pemenang[$xs].'"');
				$arr	 			= array_shift($getDataPemenang);
				if($arr['pemenang']==''){
					$pem[$datalop->id_lop]			= "";
				}else{
					$pem[$datalop->id_lop]			.= "<li>".$arr['pemenang']."</li>";						
				}
			}
			
			if ($datalop->PPN=='0') {
				$PPN	= 'NO';
			} else {
				$PPN	= 'YES';
			}
			
			if($datalop->sustain_dari !='0' and $datalop->sustain_dari !='' and $datalop->sustain_dari != NULL and $datalop->sustain_dari != '-'){
				// $sustain = "<center><input type='checkbox' checked id='sustain' disabled> YA</center>";
				$sustain = "<center class='text-success'><i class='fa fa-circle'></i> YA</center>";
			}else{
				// $sustain = "<center><input type='checkbox' id='sustain' disabled> TIDAK</center>";
				$sustain = "<center class='text-danger'><i class='fa fa-circle'></i> TIDAK</center>";
			}
			
			$getdatapm 	= $this->query->getData('lop a','(select group_concat(nama_pm) from pm_lop where FIND_IN_SET("'.$datalop->id_lop.'",id_lop) > 0) as namapmlop','WHERE id_lop ='.$datalop->id_lop.'');
			$arr_p		= array_shift($getdatapm);
			
            $tbody .= "<tr><td>".$no."</td>";
            $tbody .= "<td>".$datalop->tahun."</td>";
            $tbody .= "<td>".$datalop->id_lo."</td>";
            $tbody .= "<td>".$datalop->id_lop."</td>";
            $tbody .= "<td>".$datalop->id_sirup."</td>";
            $tbody .= "<td>".$datalop->id_lelang."</td>";
            $tbody .= "<td>".$datalop->k_l_d_i."</td>";
            $tbody .= "<td>".$datalop->satker_lo."</td>";
			$tbody .= "<td>".$datalop->nama_am."</td>";
			$tbody .= "<td>".$datalop->nama_pkt."</td>";
			$tbody .= "<td>".$sustain."</td>";
			$tbody .= "<td>".(int)$datalop->hps."</td>";
			$tbody .= "<td>".(int)$datalop->nilai_win."</td>";
			$tbody .= "<td>".(int)$datalop->ach."</td>";
			$tbody .= "<td>".$PPN."</td>";
			$tbody .= "<td>".$datalop->sr."</td>";
			$tbody .= "<td>".$datalop->kode_raisa."</td>";
			$tbody .= "<td>".$datalop->status."</td>";
			$tbody .= "<td>".$datalop->portfolio."</td>";
			$tbody .= "<td>".$datalop->nama_subs."</td>";
			$tbody .= "<td>".$pem[$datalop->id_lop]."</td>";
			$tbody .= "<td>".$datalop->metode."</td>";
			$tbody .= "<td>".$datalop->waktu."</td>";
			$tbody .= "<td>".$datalop->tanggal."</td>";
			$tbody .= "<td>".$datalop->kategori."</td>";
			$tbody .= "<td>".$datalop->nama_divisi."</td>";
			$tbody .= "<td>".$datalop->nama_segmen."</td>";
			$tbody .= "<td>".$datalop->treg."</td>";
			$tbody .= "<td>".$datalop->nama_witel."</td>";
			$tbody .= "<td>".$datalop->nomor_kontrak."</td>";
			$tbody .= "<td>".$datalop->tanggal_kb."</td>";
			$tbody .= "<td>".$datalop->file_kb."</td>";
			$tbody .= "<td>".$datalop->note."</td>";
			$tbody .= "<td>".$datalop->nomor_order."</td>";
			$tbody .= "<td>".$arr_p['namapmlop']."</td>";
			$tbody .= "<td>".$datalop->last_update."</td>";
			$tbody .= "<td>".$datalop->update_by."</td></tr>";
        }
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: attachment; filename=DATA_LOP.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
       echo '
	   <table border="1px" style="width: 320%!important;">
			<thead class="bg-gray-dark text-white">
				<tr>
					<th>No</th>
					<th class="text-center">Tahun</th>
					<th class="text-center" style="max-width:70px !important;">ID LO</th>
					<th class="text-center" style="max-width:70px !important;">ID LOP</th>
					<th class="text-center" style="max-width:70px !important;">ID Sirup</th>
					<th class="text-center" style="max-width:70px !important;">ID Lelang</th>
					<th class="text-center" style="max-width:150px !important;">K/L/D/I</th>
					<th class="text-center" style="max-width:150px !important;">Nama Satker</th>
					<th class="text-center" style="max-width:150px !important;">Nama AM</th>
					<th class="text-center" style="max-width:150px !important;">Nama Paket</th>
					<th class="text-center">Sustain</th>
					<th class="text-center">HPS</th>
					<th class="text-center">Nilai Win</th>
					<th class="text-center">Achievement</th>
					<th class="text-center">PPN</th>
					<th class="text-center">Status RAISA</th>
					<th class="text-center">Kode RAISA</th>
					<th class="text-center">Status</th>
					<th class="text-center">Portofolio</th>
					<th class="text-center">Subsidaries</th>
					<th class="text-center" style="max-width:150px !important;">Pemenang</th>
					<th class="text-center">Metode Pemilihan</th>
					<th class="text-center">Waktu Pelaksanaan</th>
					<th class="text-center">Tanggal Transaksi</th>
					<th class="text-center">Kategori</th>
					<th class="text-center">DIVISI</th>
					<th class="text-center">SEGMEN</th>
					<th class="text-center">TREG</th>
					<th class="text-center">WITEL</th>
					<th class="text-center">Nomor KB</th>
					<th class="text-center">Tanggal KB</th>
					<th class="text-center" style="max-width:97px !important;">File KB</th>
					<th class="text-center">Note</th>
					<th class="text-center">Nomor Order</th>
					<th class="text-center" style="max-width:100px !important;">PM</th>
					<th class="text-center" style="max-width:120px !important;">Last Update</th>
					<th class="text-center">Update By</th>
				</tr>
			</thead>
			<tbody>
				'.$tbody.'
			</tbody>
		</table>
	   ';
	}
	
	//GET DATAMAIL
	public function getdatamail(){
		if(checkingsessionelop()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/mailweekly');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];

			$getData = $this->query->getData('mail_weekly a LEFT JOIN user b ON a.username = b.username','a.id_mailweekly,a.username,b.email,b.name','ORDER BY a.id_mailweekly DESC');

			$no=0;
			foreach($getData as $data) { 
				$no++;
				$id = $data['id_mailweekly'];
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					$no,
					$data['username'],
					$data['name'],
					$data['email'],
					$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
				$json['data']=	'';
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function insertmail(){
		if(checkingsessionelop()){
			$userdata	= $this->session->userdata('sesselop'); 
			date_default_timezone_set("Asia/Bangkok");
			
			$user		= trim(strip_tags(stripslashes($this->input->post('user',true))));
			$getData	= $this->query->getData('mail_weekly','COUNT(*) as JUMLAH','WHERE username="'.$user.'" ORDER BY id_mailweekly DESC');
			$data 		= array_shift($getData);
			if($data['JUMLAH']>0){
				print json_encode(array('success'=>true,'total'=>3));
			}else{
				$rows 		= $this->query->insertData('mail_weekly', "id_mailweekly,username,created_date", "'','$user','".date('Y-m-d h:i:s')."'");
				$id			= $this->db->insert_id();
				$url 		= "Add Mail Receive";
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
	public function modalmail(){
		$background		= '';
		if(checkingsessionelop()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$getData = $this->query->getData('mail_weekly a LEFT JOIN user b ON a.username = b.username','a.id_mailweekly,a.username,b.email,b.name','WHERE a.id_mailweekly="'.$id.'" ORDER BY a.id_mailweekly DESC');

			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($getData as $data) {
					
					
					$row = array(
						'id_mailweekly'		=> $data['id_mailweekly'],
						'username'	=> $data['username']
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
	public function deletemail(){
		if(checkingsessionelop()){
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('mail_weekly','id_mailweekly',$cond);
			$userdata	= $this->session->userdata('sesselop'); 
			$url 		= "Add Receive Email";
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
	
	//MANAGE Latest Transaction
	public function getdatalt(){
		if(checkingsessionelop()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/latest_transaction');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			
			$getData			= $this->query->getData('latest_transaction','*','ORDER BY id_lt DESC');
			
			$no=0;
			foreach($getData as $data) { 
				
				if ($data['segmen']=='0') {
					$type	= 'TREG';
				} else {
					$type	= 'LGS';
				}
				
				$no++;
				$id = $data['id_lt'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					$data['id_lt'],
					"",
					$data['metode'],
					$data['nama_pkt'],
					$data['nilai_win'],
					$type,
					$data['treg'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
					$json['data'] ='';
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function delall(){
		if(checkingsessionelop()){
			$this->load->model('query');
			$cond	= $_POST['id'];
			$jumdata = count($cond);
			$userdata	= $this->session->userdata('sesselop'); 
			$url 		= "Manage Latest Transaction";
			$activity 	= "DELETE";
			$del=0;
			for($i=0;$i<$jumdata;$i++){
				$rows = $this->query->deleteData('latest_transaction','id_lt',$cond[$i]);
				if($rows){
					$del++;
					$log = $this->query->insertlog($activity,$url,$cond[$i]);
				}
			}
						
			if($del > 0) {
				echo "success";
			} else {
				echo "";
			}
		}else{
            redirect('/login');
        }
		
	}
	public function modallt(){
		$background		= '';
		if(checkingsessionelop()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('latest_transaction','*',"WHERE id_lt='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $data) {
					
					
					$row = array(
						'id_lt'		=> $data['id_lt'],
						'metode'	=> $data['metode'],
						'nama_pkt'	=> $data['nama_pkt'],
						'segmen'	=> $data['segmen'],
						'treg'		=> $data['treg'],
						'nilai_win'	=> $data['nilai_win']
						);
					$json = $row;
				}
				
				echo json_encode($json);
			}
		} else {
			redirect('/panel');
		}
	}
	public function insertlt(){
		if(checkingsessionelop()){
			$userdata	= $this->session->userdata('sesselop'); 
			date_default_timezone_set("Asia/Bangkok");
			$metode		= trim(strip_tags(stripslashes($this->input->post('metode',true))));
			$nama_pkt	= trim(strip_tags(stripslashes($this->input->post('nama_pkt',true))));
			$nilai_win	= trim(strip_tags(stripslashes($this->input->post('nilai_win',true))));
			// $typedash	= trim(strip_tags(stripslashes($this->input->post('typedash',true))));
			$typedash	= trim(strip_tags(stripslashes($this->input->post('datatreg',true))));
			$treg		= trim(strip_tags(stripslashes($this->input->post('treg',true))));
			
			$explType	= explode(',',$typedash);
			$cekType	= count($explType);
			
			if ($cekType>1) {
				// MULTI INSERT
				for ($i=0;$i<$cekType;$i++) {
					// echo $explType[$i]."<br>";
					
					$rows = $this->query->insertData('latest_transaction', "id_lt,metode,nama_pkt,nilai_win,segmen,treg", "'','$metode','$nama_pkt','$nilai_win','$explType[$i]','$treg'");
				}
			} else {
				// SINGLE INSERT
				// if ($typedash=='0') {
					// $treg	= $gtreg;
				// } else { $treg	= ''; }
				
				$rows = $this->query->insertData('latest_transaction', "id_lt,metode,nama_pkt,nilai_win,segmen,treg", "'','$metode','$nama_pkt','$nilai_win','$typedash','$treg'");
			}
			$id			= $this->db->insert_id();
			$url 		= "Manage Latest Transaction";
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
	public function updatelt(){
		if(checkingsessionelop()){
			$userdata	= $this->session->userdata('sesselop'); 
				
			$id_lt 		= trim(strip_tags(stripslashes($this->input->post('ed_id_lt',true))));;
			$metode		= trim(strip_tags(stripslashes($this->input->post('ed_metode',true))));
			$nama_pkt	= trim(strip_tags(stripslashes($this->input->post('ed_nama_pkt',true))));
			$nilai_win	= trim(strip_tags(stripslashes($this->input->post('ed_nilai_win',true))));
			$typedash	= trim(strip_tags(stripslashes($this->input->post('ed_typedash',true))));
			$treg		= trim(strip_tags(stripslashes($this->input->post('ed_treg',true))));
			
			// if ($typedash=='0') {
				// $treg	= $gtreg;
			// } else { $treg	= ''; }
			
			$rows = $this->query->updateData('latest_transaction',"metode='$metode', nama_pkt='$nama_pkt',nilai_win='$nilai_win',segmen='$typedash',treg='$treg'","WHERE id_lt='$id_lt'");
				$url 		= "Manage Latest Transaction";
				$activity 	= "UPDATE";
				if($rows) {
					$log = $this->query->insertlog($activity,$url,$id_lt);
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
		} else {
			redirect('/panel');
		}
	}	
	public function deletelt(){
		if(checkingsessionelop()){
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('latest_transaction','id_lt',$cond);
			$userdata	= $this->session->userdata('sesselop'); 
			$url 		= "Manage Latest Transaction";
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
	public function remaping_file(){
		$this->load->model('query');
		$query = "
			SELECT id_file,file as old_name,CONCAT (divisi,'_',segmen,'_',treg,'_',witel,'_',id_lop,'_',id_file,'.pdf') AS new_filename from(
				SELECT a.*, b.id_sirup, c.id_lo, d.id_map,
							(select nama_divisi from divisi where id_divisi=d.id_divisi) as divisi, 
							(select nama_segmen from segmen where id_segmen=d.id_segmen) as segmen, 
							d.treg,
							(select nama_witel from witel where id_witel=d.id_witel) as witel, d.nipnas,
							(select  count(id_lop)as id_lop from file_lop where id_lop=a.id_lop group by a.id_lop)as count FROM `file_lop` a
							left join lop b
							on a.id_lop=b.id_lop
							left join lo c
							on b.id_lo=c.id_lo
							left join map d
							on c.id_map=d.id_map  
				ORDER BY `count`  DESC
				)as remaping
		";
		$data = $this->db->query($query)->result_array();
		$num = $this->db->query($query)->num_rows();
		$previousValue = null;
		foreach($data as $row){
			if($num !=0){
				$name_Old = $row['old_name'];
				$name_New = $row['new_filename'];
				
				$dirname_Old = "../files/kontrak/".$name_Old;
				$dirname_New = "../files/kontrak/".$name_New;
				echo $dirname_Old." : <small>rename_to</small> :".$dirname_New."<br>";
			}
		}
	}

	//MANAGE Status Raisa
	public function getdatasr(){
		if(checkingsessionelop()){
			$data_aksess = $this->query->getAkses($this->profile,'panel/status_raisa');
			$shift = array_shift($data_aksess);
			$akses = $shift['akses'];
			
			$getData = $this->query->getData('`status_raisa` sx','sx.*,(select DATE_FORMAT(date_time, "%d-%b-%y %H:%i:%s")as last_update from data_log WHERE data=sx.id_sr AND menu ="Manage Status RAISA")as last_update,(select usr.name from data_log dl LEFT JOIN user usr ON dl.userid = usr.userid WHERE dl.data=sx.id_sr AND dl.menu = "Manage Status RAISA")as update_by','');
			
			$no=0;
			foreach($getData as $data) { 
				$no++;
				$id = $data['id_sr'];
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);
				$row = array(
					'',
					$data['sr'],
					$data['kode_raisa'],
					$data['status'],
					$data['last_update'],
					$data['update_by'],
					$buttonupdate.$buttondelete
					);
				$json['data'][] = $row;
			}
			if(!isset($json)){
					$json['data'] ='';
			}
			echo json_encode($json);
		} else {
			redirect('/panel');
		}
	}
	public function insertsr(){
		if(checkingsessionelop()){
			$userdata	= $this->session->userdata('sesselop'); 
			date_default_timezone_set("Asia/Bangkok");
			$sr				= trim(strip_tags(stripslashes($this->input->post('sr',true))));
			$kode_raisa		= trim(strip_tags(stripslashes($this->input->post('kode_raisa',true))));
			$status			= trim(strip_tags(stripslashes($this->input->post('status',true))));
			$rows = $this->query->insertData('status_raisa', "id_sr,sr,kode_raisa,status", "'','$sr','$kode_raisa','$status'");
			$id			= $this->db->insert_id();
			$url 		= "Manage Status RAISA";
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
	public function modalsr(){
		$background		= '';
		if(checkingsessionelop()){
			date_default_timezone_set("Asia/Bangkok");
			
			$id					= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$dataCat			= $this->query->getData('status_raisa','*',"WHERE id_sr='".$id."'");
			
			header('Content-type: application/json; charset=UTF-8');
			
			if (isset($id) && !empty($id)) {
				foreach($dataCat as $data) {
					
					
					$row = array(
						'id_sr'		=> $data['id_sr'],
						'sr'	=> $data['sr'],
						'kode_raisa'	=> $data['kode_raisa'],
						'status'	=> $data['status']
						);
					$json = $row;
				}
				
				echo json_encode($json);
			}
		} else {
			redirect('/panel');
		}
	}
	public function updatesr(){
		if(checkingsessionelop()){
			$userdata	= $this->session->userdata('sesselop'); 
				
			$id_sr 		= trim(strip_tags(stripslashes($this->input->post('ed_id_sr',true))));;
			$sr			= trim(strip_tags(stripslashes($this->input->post('ed_sr',true))));
			$kode_raisa	= trim(strip_tags(stripslashes($this->input->post('ed_kode_raisa',true))));
			$status		= trim(strip_tags(stripslashes($this->input->post('ed_status',true))));
			
			// if ($typedash=='0') {
				// $treg	= $gtreg;
			// } else { $treg	= ''; }
			
			$rows = $this->query->updateData('status_raisa',"sr='$sr',kode_raisa='$kode_raisa',status='$status'","WHERE id_sr='$id_sr'");
				$url 		= "Manage Status RAISA";
				$activity 	= "UPDATE";
				if($rows) {
					$log = $this->query->insertlog($activity,$url,$id_lt);
					print json_encode(array('success'=>true,'total'=>1));
				} else {
					echo "";
				}
		} else {
			redirect('/panel');
		}
	}	
	public function deletesr(){
		if(checkingsessionelop()){
			$this->load->model('query');
			
			$cond	= trim(strip_tags(stripslashes($this->input->post('id',true))));
			
			$rows = $this->query->deleteData('status_raisa','id_sr',$cond);
			$userdata	= $this->session->userdata('sesselop'); 
			$url 		= "Manage Status RAISA";
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
	public function getsr(){
		if(checkingsessionelop()){
			$id	 = trim(strip_tags(stripslashes($this->input->post('id',true))));
			$getdatapagu = $this->query->getData('`status_raisa`','kode_raisa,status',"WHERE id_sr ='".$id."'");
			header('Content-type: application/json; charset=UTF-8');
			$json="";
			if (isset($id) && !empty($id)) {
				foreach($getdatapagu as $data) {
					$row = array(
						'kode_raisa' => $data['kode_raisa'],
						'status' 	 => $data['status']
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
}