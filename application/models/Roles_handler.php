<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles_handler extends CI_Model {
	private $profile;

	public function __construct(){
		parent::__construct();
		// $this->db2 = $this->load->database('dblicense', TRUE);
		// $this->db2->get('siswa');
	} 
	function data(){
		$data_aksess = $this->query->getAkses($this->profile,'panel/roles');
		$shift = array_shift($data_aksess);
		$akses = $shift['akses'];

		$qRole 	= "
					select
						a.*,
						(SELECT xb.name as  update_by FROM data_log xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu='Manage Role' AND cast(xa.data as integer) = a.id_role ORDER BY xa.date_time DESC limit 1)as update_by,
						(SELECT xa.date_time as last_update FROM data_log xa LEFT JOIN user xb ON xa.userid=xb.userid WHERE xa.menu='Manage Role' AND cast(xa.data as integer) = a.id_role ORDER BY xa.date_time DESC limit 1)as last_update
					from
					role a
					ORDER BY a.nama_role ASC
				";
				// echo $qRole;
		$datarole			= $this->query->getDatabyQ($qRole);
		
		$no=0;
		header('Content-type: application/json; charset=UTF-8');

		$cek 	= $this->query->getNumRowsbyQ($qRole)->num_rows();

		if ($cek>0) {

			foreach($datarole as $data) {
				$no++;
				$id = $data['id_role'];
				
				$buttonupdate = getRoleUpdate($akses,'update',$id);
				$buttondelete = getRoleDelete($akses,'delete',$id);

				$row = array(
					"nama_role"		=> $data['nama_role'],
					"desc_role"		=> $data['desc_role'],
					"update_by"		=> $data['update_by'],
					"last_update"	=> $data['last_update'],
					"actions"		=> $id
					);
				$json[] = $row;
			}
			return json_encode($json);
		} else {
			$json ='';
			return json_encode($json);
		}
	}
}
