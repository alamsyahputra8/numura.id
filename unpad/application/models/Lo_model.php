<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lo_model extends CI_Model {
	var $table = 'lo';
	private $profile;
    var $column_order = array(null,'id_lo','id_map','nama_gc','satker_lo','nama_keg','pagu_lo','ta','sumber_dana','nama_divisi','nama_segmen','treg','nama_witel','tahun','last_update','update_by',null); //set column field database for datatable orderable
	var $column_search = array('id_lo','id_map','nama_gc','satker_lo','nama_keg','pagu_lo','ta','sumber_dana','nama_divisi','nama_segmen','treg','nama_witel','tahun','last_update','update_by'); //set column field database for datatable searchable 
    var $order = array('pagu_lo' => 'desc'); // default order 
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    private function _get_datatables_query()
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
		$this->db->select('* FROM (SELECT a.*,(SELECT SUM(pagu_proj)as pagu_lo FROM lop WHERE id_lo=a.id_lo)as pagu_lo,b.id_segmen,(select nama_segmen from segmen where id_segmen = b.id_segmen)as nama_segmen,b.id_witel,(select nama_witel from witel WHERE id_witel=b.id_witel)as nama_witel,b.treg,b.nipnas,b.nik_am,c.nama_gc,b.id_divisi,(select nama_divisi from divisi where id_divisi=b.id_divisi)as nama_divisi,(SELECT DATE_FORMAT(date_time, "%d-%b-%y %H:%i:%s")  FROM data_log WHERE data = a.id_lo AND menu="Manage Lo" ORDER BY date_time DESC limit 1)as last_update,(SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu="Manage Lo" AND xa.data = a.id_lo ORDER BY xa.date_time DESC limit 1)as update_by,(SELECT tahun from map where id_map=a.id_map group by tahun) tahun');
		$this->db->from('lo a');
		$this->db->join('map b', 'a.id_map=b.id_map','left');
		$this->db->join('gc c', 'b.nipnas = c.nipnas ','left');
		$this->db->where('a.id_lo!="")as master WHERE id_lo!="" '.$cond_where.$uam);
        $i = 0;
		
        foreach ($this->column_search as $item) // loop column 
        {
            if(isset($_POST['search']['value'])) // if datatable send POST for search
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
			if (isset($_POST['columns'][$xo]['search']['value']) AND $_POST['columns'][$xo]['search']['value'] !='') {
				if($xo==1){ $custom_item = 'tahun';
				}else if($xo==2){ $custom_item = 'id_lo'; 
				}else if($xo==3){ $custom_item = 'id_map';
				}else if($xo==4){ $custom_item = 'nama_gc';
				}else if($xo==5){ $custom_item = 'satker_lo';
				}else if($xo==6){ $custom_item = 'nama_keg';
				}else if($xo==7){ $custom_item = 'pagu_lo';
				}else if($xo==8){ $custom_item = 'ta';
				}else if($xo==9){ $custom_item = 'sumber_dana';
				}else if($xo==10){ $custom_item = 'nama_divisi';
				}else if($xo==11){ $custom_item = 'nama_segmen';
				}else if($xo==12){ $custom_item = 'treg';
				}else if($xo==13){ $custom_item = 'nama_witel';
				}else if($xo==14){ $custom_item = 'last_update';
				}else if($xo==15){ $custom_item = 'update_by';
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
				}else if($xos==3){ $custom_order = 'id_map';
				}else if($xos==4){ $custom_order = 'nama_gc';
				}else if($xos==5){ $custom_order = 'satker_lo';
				}else if($xos==6){ $custom_order = 'nama_keg';
				}else if($xos==7){ $custom_order = 'pagu_lo';
				}else if($xos==8){ $custom_order = 'ta';
				}else if($xos==9){ $custom_order = 'sumber_dana';
				}else if($xos==10){ $custom_order = 'nama_divisi';
				}else if($xos==11){ $custom_order = 'nama_segmen';
				}else if($xos==12){ $custom_order = 'treg';
				}else if($xos==13){ $custom_order = 'nama_witel';
				}else if($xos==14){ $custom_order = 'last_update';
				}else if($xos==15){ $custom_order = 'update_by';
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
		
		$userdata 			= $this->session->userdata('sesselop'); 
		$userid 			= $userdata['userid'];
		$uam				= $this->formula->getUAM($userid);
		// echo $uam;
		// exit();
		
		$adjust_divisi 		= str_replace('id_divisi','b.id_divisi',$uam);
		$adjust_segmen 		= str_replace('id_segmen','b.id_segmen',$adjust_divisi);
		$adjust_witel 		= str_replace('id_witel','b.id_witel',$adjust_segmen);
		$adjust_am 			= str_replace('nik_am','b.nik_am',$adjust_witel);
		$adjust_treg 		= str_replace('treg','b.treg',$adjust_am);
		$final_adjust 		= $adjust_treg;
		
        $this->db->select('a.*,b.id_segmen,b.id_witel,b.treg,b.nipnas,b.nik_am,c.nama_gc,b.id_divisi');
		$this->db->from('lo a');
		$this->db->join('map b', 'a.id_map=b.id_map','left');
		$this->db->join('gc c', 'b.nipnas = c.nipnas ','left');
		$this->db->where('a.id_lo !="" '.$final_adjust);
        return $this->db->count_all_results();
    }
 
}
