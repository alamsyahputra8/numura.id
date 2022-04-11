<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lop_model extends CI_Model {
	var $table = 'lop';
	private $profile;
    var $column_order = array(null,'id_lo','id_lop','id_sirup','id_lelang','k_l_d_i','satker_lo','nama_am','nama_pkt','pagu_proj','nilai_win','ach','PPN','kode_raisa','status','portfolio','nama_subs',NULL,'id_pemenang','metode','waktu','tanggal','kategori','treg','nama_witel','nomor_kontrak','tanggal_kb',NULL,'note',NULL,'last_update'); //set column field database for datatable orderable
	var $column_search = array('id_lo','id_lop','id_sirup','id_lelang','k_l_d_i','satker_lo','nama_am','nama_pkt','pagu_proj','nilai_win','ach','PPN','kode_raisa','status','portfolio','nama_subs','id_pemenang','metode','waktu','tanggal','kategori','treg','nama_witel','nomor_kontrak','tanggal_kb','file_kb','note','nomor_order','last_update'); //set column field database for datatable searchable 
    var $order = array('pagu_proj' => 'desc'); // default order 
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query()
    {
    	ini_set('max_execution_time', 123456);
        ini_set("memory_limit","1256M");
		$this->load->model('formula');
        $data_aksess		= $this->query->getAkses($this->profile,'panel/lop');
		$shift				= array_shift($data_aksess);
		$akses 				= $shift['akses'];
		$userdata 			= $this->session->userdata('sesselop'); 
		$userid 			= $userdata['userid'];
		$uam				= $this->formula->getUAM($userid);
		$session	 		= $this->session->userdata('sesselop'); 
		$profile 	 		= $session['id_role'];
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
		
		if (fixURL($divisi)=='ALLDIVISI' or $divisi=='') { $wheredivisi	= ""; } else { $wheredivisi = "and id_divisi='".$divisi."'";}
		if (fixURL($segmen)=='ALLSEGMEN' or $segmen=='') { $wheresegmen	= ""; } else { $wheresegmen = "and id_segmen='".$segmen."'";}
		if (fixURL($treg)=='ALLTREG' or $treg=='') 	{ $wheretreg	= ""; } else { $wheretreg = "and treg='".$treg."'"; }
		if (fixURL($witel)=='ALLWITEL' or $witel=='') { $wherewitel	= ""; } else { $wherewitel = "and cid_witel='".$witel."'"; }
		if (fixURL($am)=='ALLAM' or $am=='') 	{ $wheream	= ""; } else { $wheream = "and nik_am='".getnik($am)."'"; }
		
		$where = $wheredivisi.$wheresegmen.$wheretreg.$wherewitel.$wheream;
		$cond_where = "";
		//$this->db->from($this->table);
		$this->db->select('* FROM(SELECT *,CASE WHEN sustain_dari !=0 THEN "YA" ELSE "TIDAK" END as sustaining FROM (SELECT CASE WHEN a.PPN="1" THEN "YES" ELSE "NO" END as ALIAS ,i.nama_divisi,j.nama_segmen,a.id_lo,a.id_lop,a.id_sirup,a.id_lelang,r.sr,d.nama_gc as k_l_d_i,b.satker_lo,e.nama_am,a.nama_pkt,a.pagu_proj,a.nilai_win,CASE WHEN a.PPN =1 THEN ((100/110)* a.nilai_win) ELSE a.nilai_win END ach,a.PPN,a.kode_raisa,a.status,a.portfolio,f.nama_subs,a.id_pemenang,a.sustain_dari,a.pagu_proj as hps,a.id_pm,(select phone from pm_lop where id_pm=a.id_pm) as phone_pm,(SELECT pemenang FROM pemenang WHERE id_pemenang=a.id_pemenang)as pemenang,a.metode,DATE_FORMAT(a.tanggal, "%d-%b-%y")as tanggal,DATE_FORMAT(a.waktu, "%b-%y")as waktu,a.kategori,c.treg,h.nama_witel,a.nomor_kontrak,DATE_FORMAT(a.tanggal_kb, "%d-%b-%y")as tanggal_kb,(SELECT GROUP_CONCAT(DISTINCT(xx.file))as file_kb FROM file_lop xx WHERE xx.id_lop = a.id_lop)as file_kb,a.ket as note,(SELECT GROUP_CONCAT(xxd.nomor_order)as nomor_order from  `order` xxd WHERE xxd.id_lop=a.id_lop)as nomor_order,DATE_FORMAT(a.last_update, "%d-%b-%y %H:%i:%s")as last_update,(SELECT xxc.name FROM user xxc WHERE xxc.userid=a.update_by)as update_by,`c`.`id_divisi`,`c`.`id_segmen`,`c`.`id_witel`,`c`.`nik_am`,`c`.`tahun`,(select GROUP_CONCAT(nama_pm) from pm_lop where FIND_IN_SET(a.id_lop,id_lop) > 0) as namapmlop');
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
        $i = 0;
		
        foreach ($this->column_search as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                 
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
			
			
            $i++;
        }
        $cekjmlcol	= count($_POST['columns']);
		for ($xo=0;$xo<$cekjmlcol;$xo++) {
			if ($_POST['columns'][$xo]['search']['value'] AND $_POST['columns'][$xo]['search']['value'] !='') {
				if($xo==1){ $custom_item = 'tahun'; 
				}else if($xo==2){ $custom_item = 'id_lo';
				}else if($xo==3){ $custom_item = 'id_lop';
				}else if($xo==4){ $custom_item = 'id_sirup';
				}else if($xo==5){ $custom_item = 'id_lelang';
				}else if($xo==6){ $custom_item = 'k_l_d_i';
				}else if($xo==7){ $custom_item = 'satker_lo';
				}else if($xo==8){ $custom_item = 'nama_am';
				}else if($xo==9){ $custom_item = 'nama_pkt';
				}else if($xo==10){ $custom_item = 'sustaining';
				}else if($xo==11){ $custom_item = 'hps';
				}else if($xo==12){ $custom_item = 'nilai_win';
				}else if($xo==13){ $custom_item = 'ach';
				}else if($xo==14){ $custom_item = 'ALIAS';
				}else if($xo==15){ $custom_item = 'sr';
				}else if($xo==16){ $custom_item = 'kode_raisa';
				}else if($xo==17){ $custom_item = 'status';
				}else if($xo==18){ $custom_item = 'portfolio';
				}else if($xo==19){ $custom_item = 'nama_subs';
				}else if($xo==20){ $custom_item = 'pemenang';
				}else if($xo==21){ $custom_item = 'metode';
				}else if($xo==22){ $custom_item = 'waktu';
				}else if($xo==23){ $custom_item = 'tanggal';
				}else if($xo==24){ $custom_item = 'kategori';
				}else if($xo==25){ $custom_item = 'nama_divisi';
				}else if($xo==26){ $custom_item = 'nama_segmen';
				}else if($xo==27){ $custom_item = 'treg';
				}else if($xo==28){ $custom_item = 'nama_witel';
				}else if($xo==29){ $custom_item = 'nomor_kontrak';
				}else if($xo==30){ $custom_item = 'tanggal_kb';
				}else if($xo==31){ $custom_item = 'file_kb';
				}else if($xo==32){ $custom_item = 'note';
				}else if($xo==33){ $custom_item = 'nomor_order';
				}else if($xo==34){ $custom_item = 'namapmlop';
				}else if($xo==35){ $custom_item = 'last_update';
				}else if($xo==36){ $custom_item = 'update_by';
				}
				$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
				$this->db->like($custom_item, $_POST['columns'][$xo]['search']['value']);
				$this->db->group_end(); //close bracket
			}
		}
        if(isset($_POST['order'])) // here order processing
        {
				$xos = $_POST['order']['0']['column'];
				if($xos==1){ $custom_order = 'tahun'; 
				}else if($xos==2){ $custom_order = 'id_lo';
				}else if($xos==3){ $custom_order = 'id_lop';
				}else if($xos==4){ $custom_order = 'id_sirup';
				}else if($xos==5){ $custom_order = 'id_lelang';
				}else if($xos==6){ $custom_order = 'k_l_d_i';
				}else if($xos==7){ $custom_order = 'satker_lo';
				}else if($xos==8){ $custom_order = 'nama_am';
				}else if($xos==9){ $custom_order = 'nama_pkt';
				}else if($xos==10){ $custom_order = 'sustaining';			
				}else if($xos==11){ $custom_order = 'hps';
				}else if($xos==12){ $custom_order = 'nilai_win';
				}else if($xos==13){ $custom_order = 'ach';
				}else if($xos==14){ $custom_order = 'ALIAS';
				}else if($xos==15){ $custom_order = 'sr';
				}else if($xos==16){ $custom_order = 'kode_raisa';
				}else if($xos==17){ $custom_order = 'status';
				}else if($xos==18){ $custom_order = 'portfolio';
				}else if($xos==19){ $custom_order = 'nama_subs';
				}else if($xos==20){ $custom_order = 'pemenang';
				}else if($xos==21){ $custom_order = 'metode';
				}else if($xos==22){ $custom_order = 'waktu';
				}else if($xos==23){ $custom_order = 'tanggal';
				}else if($xos==24){ $custom_order = 'kategori';
				}else if($xos==25){ $custom_order = 'nama_divisi';
				}else if($xos==26){ $custom_order = 'nama_segmen';
				}else if($xos==27){ $custom_order = 'treg';
				}else if($xos==28){ $custom_order = 'nama_witel';
				}else if($xos==29){ $custom_order = 'nomor_kontrak';
				}else if($xos==30){ $custom_order = 'tanggal_kb';
				}else if($xos==31){ $custom_order = 'file_kb';
				}else if($xos==32){ $custom_order = 'note';
				}else if($xos==33){ $custom_order = 'nomor_order';
				}else if($xos==34){ $custom_order = 'namapmlop';
				}else if($xos==35){ $custom_order = 'last_update';
				}else if($xos==36){ $custom_order = 'update_by';
				}
            $this->db->order_by($custom_order, $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
	
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
		//$this->output->enable_profiler(TRUE);
        return $query->result();
    }
 
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all()
    {
		$this->load->model('formula');
        $data_aksess		= $this->query->getAkses($this->profile,'panel/lop');
		$shift				= array_shift($data_aksess);
		$akses 				= $shift['akses'];
		$userdata 			= $this->session->userdata('sesselop'); 
		$userid 			= $userdata['userid'];
		$uam				= $this->formula->getUAM($userid);
		$session	 		= $this->session->userdata('sesselop'); 
		$profile 	 		= $session['id_role'];
		// echo $uam;
		// exit();
		
		$adjust_divisi = str_replace('id_divisi','c.id_divisi',$uam);
		$adjust_segmen = str_replace('id_segmen','c.id_segmen',$adjust_divisi);
		$adjust_witel = str_replace('id_witel','c.id_witel',$adjust_segmen);
		$adjust_am = str_replace('nik_am','c.nik_am',$adjust_witel);
		$adjust_treg = str_replace('treg','c.treg',$adjust_am);
		$final_adjust = $adjust_treg;
		
        $this->db->select('a.id_lo,a.id_lop,a.id_sirup,a.id_lelang,d.nama_gc as k_l_d_i,b.satker_lo,e.nama_am,a.nama_pkt,a.PPN,a.pagu_proj,a.nilai_win,a.kode_raisa,a.status,a.portfolio,f.nama_subs,a.id_pemenang,a.metode,a.tanggal,a.waktu,a.kategori,c.treg,h.nama_witel,a.nomor_kontrak,a.tanggal_kb,(SELECT GROUP_CONCAT(DISTINCT(xx.file))as file_kb FROM file_lop xx WHERE xx.id_lop = a.id_lop)as file_kb,a.ket as note,(SELECT GROUP_CONCAT(xxd.nomor_order)as nomor_order from  `order` xxd WHERE xxd.id_lop=a.id_lop)as nomor_order,a.last_update,(SELECT xxc.name FROM user xxc WHERE xxc.username=a.update_by)as update_by');
		$this->db->from('lop a');
		$this->db->join('lo b', 'a.id_lo=b.id_lo','left');
		$this->db->join('map c', 'b.id_map = c.id_map ','left');
		$this->db->join('gc d', 'd.nipnas = c.nipnas','left');
		$this->db->join('am e', 'c.nik_am = e.nik_am','left');
		$this->db->join('subs f', 'a.subs = f.id_subs','left');
		$this->db->join('witel h', 'c.id_witel=h.id_witel','left');
		$this->db->where('a.id_lop is not null '.$final_adjust);
        return $this->db->count_all_results();
    }
 
}
