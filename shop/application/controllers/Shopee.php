<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shopee extends CI_Controller {

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
			
		} else {
			
		}
	}

	public function getToken(){
		$t 		= time();
		// $t 	= 1651935496;

		// echo "18101b2aba67fc26b39447416eb3481e1d4b66c105d63ad5e89d22e131f43fc8<br>";

		$partner_id = "1651936228";
		$shop_id 	= "49307";
		$secret_key = "";
		$partner_key = "f8f6e1e0a57b388f";
		$path 		= "/api/v2/product/get_category";
		$token 		= "5776e8265acdcc79743e3109900a208c";
		$base_str 	= $partner_id.$path.$t;
		$sign 		= hash_hmac('sha256', $base_str, $partner_key);
		// echo $sign;exit();

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://partner.test-stable.shopeemobile.com/api/v2/auth/token/get?partner_id=1007368&language=zh-hans&shop_id=49307&timestamp='.$t.'&sign='.$sign.'',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}
	
	public function authorization(){
		$t 		= time();
		// $sig = hash_hmac('sha256', $string, $secret);
		$date 		= new DateTime();
		$timestamp 	= time();
		$partner_id = "1007368";
		$shop_id 	= "49307";
		$secret_key = "657050426748706a56494951595a564d";
		$path 		= "/api/v2/shop/auth_partner";
		$base_str 	= $shop_id . $path . $timestamp;
		$sign 		= hash_hmac('sha256', $base_str, $secret_key);
		echo $sign;exit();

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://partner.test-stable.shopeemobile.com/api/v2/shop/auth_partner?partner_id=1007368&redirect=https://www.numura.id/&timestamp='.$t.'&sign='.$sign.'',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;
	}

	public function getcategory(){
		// $t 		= time();
		// $t 	= 1651937943;
		$orgDate 	= '2022-05-07 15:39:03';
		$t 			= strtotime($orgDate);
		
		// echo "18101b2aba67fc26b39447416eb3481e1d4b66c105d63ad5e89d22e131f43fc8<br>";

		$partner_id = "1007368";
		$shop_id 	= "49307";
		$partner_key = "df1ad0927cd758d4679ce2d23d792b9efb08e28fed8a6f62831d3cd6c41c2690";
		$path 		= "/api/v2/product/get_category";

		$base_str 	= sprintf("%s%s%s",$partner_id,$path,$t);
		$sign 		= hash_hmac('sha256', $base_str, $partner_key);

		echo "bfc48e014c6ab7daa7e41b3cede7d08b531ce01da994b5df77679ffb5008a6ed<br>";
		echo $sign; exit();
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => 'https://partner.test-stable.shopeemobile.com/api/v2/product/get_category?access_token=5776e8265acdcc79743e3109900a208c&language=%22zh-hans%22&partner_id=1007368&shop_id=49307&sign='.$sign.'&timestamp='.$t.'',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'GET',
		  CURLOPT_HTTPHEADER => array(
		    'Content-Type: application/json'
		  ),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		echo $response;

	
	}
	
	public function exportstok(){
		if(checkingsessionpwt()){
			$now 	= date('Y-m-d');
			
			ini_set('max_execution_time', 123456);
			ini_set("memory_limit","1256M");

			header("Content-type: application/vnd.ms-excel; name='excel'");
			header("Content-Disposition: attachment; filename=Penyesuaian STOK NUMURA - ".$now.".xls");
			header("Pragma: no-cache");
			header("Expires: 0");

			$getMap 	= $this->db->query("
						SELECT a.*, (SELECT label from size where id_size=a.size)  sizelab,
						(SELECT detail from size where id_size=a.size)  sizedet,
						(SELECT label from color where id=a.color)  collab
						from mapping_shopee a order by 1
						")->result_array();

			echo '
			<table border=1>
				<thead>
					<tr>
						<th colspan="6">Penambahan/Pengurangan Stok Produk Aktif</th>
					</tr>
					<tr>
					 	<th colspan="5"><center>Informasi Produk Jangan Edit Kolom Ini</center></th>
					 	<th>Ubah Kolom Ini (Mohon pilih dan isi salah satu penyesuaian, berdasarkan jumlah perkiraan stok akhir atau jumlah penambahan/pengurangan stok)</th>
					</tr>
					<tr>
						<th>Nama Produk Gudang</th>
						<th>Nama Variasi Gudang</th>
						<th>Kode Produk Gudang</th>
						<th>Jumlah Pemetaan Produk Online</th>
						<th>Stok Produk Aktif yang Tersedia</th>
						<th>Jumlah Perkiraan Stok Akhir* (Produk yang tidak memiliki data atau perubahan dengan stok gudang saat ini tidak akan di-upload) Lakukan penyesuaian stok pada maks. 1000 SKU</th>
					</tr>
				</thead>
				<tbody>
			';
			foreach($getMap as $map) {
				$id 	= $map['id'];
				$sizeid	= $map['size'];
				$colid 	= $map['color'];

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

				$sisastokfin = ($jml-$cekJml)-$cekSend;
				if ($sisastokfin<1) {
					$colte	= 'style="color: #bdbcbc;"';	
				} else if ($sisastokfin>0 and $sisastokfin<5) {
					$colte	= 'style="color: #cf5555;"';	
				} else {
					$colte	= '';
				}

				echo '
				<tr>
					<td>KAOS ANAK CUSTOM KARAKTER + NAMA - NUMURA.ID</td>
					<td>'.$map['collab'].', '.$map['sizelab'].' '.$map['sizedet'].'</td>
					<td>'.$map['code'].'</td>
					<td>-</td>
					<td>-</td>
					<td>'.$sisastokfin.'</td>
				</tr>';
			}
			echo '
			</tbody></table>
			';
		}else{
            redirect('/login');
        }
	}

}
