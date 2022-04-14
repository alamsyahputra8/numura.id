<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formula extends CI_Model {
	public function rupiah($val){
		$result = "Rp " . number_format($val,0,',','.');
		return $result;
	}
	
	public function imgUserCek($image){
		$path = FCPATH. 'images/user/';
		if(file_exists(dirname(FCPATH)."/images/user/".$image)) {
		// if(file_exists($path . $image) === FALSE || $image == null){
			return $path . "no_image.png";
        }
		// return $path . $image;
	}
	
	public function rupiah2($val){
		$result = number_format($val,1,',','.');
		return $result;
	}

	public function clean($string) {
	   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
	
	public function rupiah3($val){
		$result = number_format($val,0,',','.');
		return $result;
	}
	
	public function rupiah4($val){
		$result = number_format($val,0,',','');
		return $result;
	}
	
	public function rupiah5($val){
		$result = number_format($val,2,'.','');
		return $result;
	}
	
	public function rupiahM($val){
		$valM	= $val/1000000000;
		$result = number_format($valM,2,',','');
		return $result;
	}
	
	public function getNamaDivisi($id) {
		$this->load->model('query'); 
		$getData	= $this->query->getData('divisi','nama_divisi',"WHERE id_divisi='$id'");
		$data		= array_shift($getData);
		return $data['nama_divisi'];
	}
	
	public function getNamaSegmen($id) {
		$this->load->model('query'); 
		$getData	= $this->query->getData('segmen','nama_segmen',"WHERE id_segmen='$id'");
		$data		= array_shift($getData);
		return $data['nama_segmen'];
	}
	
	public function getNamaWitel($id) {
		$this->load->model('query'); 
		$getData	= $this->query->getData('witel','nama_witel',"WHERE id_witel='$id'");
		$data		= array_shift($getData);
		return $data['nama_witel'];
	}
	
	public function TanggalIndo($date){
		$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl   = substr($date, 8, 2);

		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;		
		return($result);
	}
	
	public function TanggalIndoMY($date){
		$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		// $tgl   = substr($date, 8, 2);

		$result = $BulanIndo[(int)$bulan-1] . " ". $tahun;
		return($result);
	}
	
	public function TanggalIndoMY2($date){
		$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");

		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		// $tgl   = substr($date, 8, 2);

		$result = $BulanIndo[(int)$bulan-1] . " ". $tahun;
		return($result);
	}
	
	public function TanggalIndoM($date){
		$BulanIndo = array("Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des");

		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		// $tgl   = substr($date, 8, 2);

		$result = $BulanIndo[(int)$bulan-1];
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
	
	public function indonesian_date($timestamp = '', $date_format = 'l, j F Y | H:i', $suffix = 'WIB') {
		if (trim ($timestamp) == '')
		{
				$timestamp = time ();
		}
		elseif (!ctype_digit ($timestamp))
		{
			$timestamp = strtotime ($timestamp);
		}
		# remove S (st,nd,rd,th) there are no such things in indonesia :p
		$date_format = preg_replace ("/S/", "", $date_format);
		$pattern = array (
			'/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
			'/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
			'/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
			'/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
			'/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
			'/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
			'/April/','/June/','/July/','/August/','/September/','/October/',
			'/November/','/December/',
		);
		$replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
			'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
			'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des',
			'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
			'Oktober','November','Desember',
		);
		$date = date ($date_format, $timestamp);
		$date = preg_replace ($pattern, $replace, $date);
		$date = "{$date} {$suffix}";
		return $date;
	} 
	
	public function nicetime($date){
		if(empty($date)) {
			return "No date provided";
		}
		 
		$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		 
		$now = time();
		$unix_date = strtotime($date);
		 
		// check validity of date
		if(empty($unix_date)) {
		return "Bad date";
		}
		 
		// is it future date or past date
		if($now > $unix_date) {
		$difference = $now - $unix_date;
		$tense = "ago";
		 
		} else {
		$difference = $unix_date - $now;
		$tense = "from now";
		}
		 
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
		$difference /= $lengths[$j];
		}
		 
		$difference = round($difference);
		 
		if($difference != 1) {
		$periods[$j].= "s";
		}
		 
		return "$difference $periods[$j] {$tense}";
	}
	
	function getUAM($userid){
		$this->load->model('query'); 
		$getAksesUAM		= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
		$dataAksesUAM		= array_shift($getAksesUAM);
		
		$divisiUAM 			= str_replace(",","','",$dataAksesUAM['akses_divisi']);
		$segmenUAM 			= str_replace(",","','",$dataAksesUAM['akses_segmen']);
		$tregUAM 			= str_replace(",","','",$dataAksesUAM['akses_treg']);
		$witelUAM 			= str_replace(",","','",$dataAksesUAM['akses_witel']);
		$amUAM 				= str_replace(",","','",$dataAksesUAM['akses_am']);
		
		if ($dataAksesUAM['akses_divisi']=='all') { $condDiv = ''; } else { $condDiv = "and id_divisi in ('$divisiUAM')"; }
		if ($dataAksesUAM['akses_segmen']=='all') { $condSeg = ''; } else { $condSeg = "and id_segmen in ('$segmenUAM')"; }
		if ($dataAksesUAM['akses_treg']=='all')   { $condTreg = ''; } else { $condTreg = "and treg in ('$tregUAM')"; }
		if ($dataAksesUAM['akses_witel']=='all')  { $condWit = ''; } else { $condWit = "and id_witel in ('$witelUAM')"; }
		if ($dataAksesUAM['akses_am']=='all') 	  { $condAm = ''; } else { $condAm = "and nik_am in ('$amUAM')"; }
		
		$uam				= "$condDiv $condSeg $condTreg $condWit $condAm";
		
		return $uam;
	}
	
	function getUAMPOTS($userid){
		$this->load->model('query'); 
		$getAksesUAM		= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
		$dataAksesUAM		= array_shift($getAksesUAM);
		
		$segmenUAM 			= str_replace(",","','",$dataAksesUAM['akses_segmen']);
		$tregUAM 			= str_replace(",","','",$dataAksesUAM['akses_treg']);
		$witelUAM 			= str_replace(",","','",$dataAksesUAM['akses_witel']);
		
		if ($dataAksesUAM['akses_segmen']=='all') { $condSeg = ''; } else { $condSeg = "and id_segmen in ('$segmenUAM')"; }
		if ($dataAksesUAM['akses_treg']=='all')   { $condTreg = ''; } else { $condTreg = "and treg in ('$tregUAM')"; }
		if ($dataAksesUAM['akses_witel']=='all')  { $condWit = ''; } else { $condWit = "and id_witel in ('$witelUAM')"; }
		
		$uam				= "$condSeg $condTreg $condWit";
		
		return $uam;
	}
	
	function getUAMprod($userid){
		$this->load->model('query'); 
		$getAksesUAM		= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
		$dataAksesUAM		= array_shift($getAksesUAM);
		
		$divisiUAM 			= str_replace(",","','",$dataAksesUAM['akses_divisi']);
		$segmenUAM 			= str_replace(",","','",$dataAksesUAM['akses_segmen']);
		$tregUAM 			= str_replace(",","','",$dataAksesUAM['akses_treg']);
		$witelUAM 			= str_replace(",","','",$dataAksesUAM['akses_witel']);
		$amUAM 				= str_replace(",","','",$dataAksesUAM['akses_am']);
		
		if ($dataAksesUAM['akses_divisi']=='all') { $condDiv = ''; } else { $condDiv = "and EXISTS (select id_divisi from map where nik_am=a.nik and id_divisi in ('$divisiUAM'))"; }
		if ($dataAksesUAM['akses_segmen']=='all') { $condSeg = ''; } else { $condSeg = "and EXISTS (select id_segmen from map where nik_am=a.nik and id_segmen in ('$segmenUAM'))"; }
		if ($dataAksesUAM['akses_treg']=='all')   { $condTreg = ''; } else { $condTreg = "and EXISTS (select treg from map where nik_am=a.nik and treg in ('$tregUAM'))"; }
		if ($dataAksesUAM['akses_witel']=='all')  { $condWit = ''; } else { $condWit = "and EXISTS (select id_witel from map where nik_am=a.nik and id_witel in ('$witelUAM'))"; }
		// if ($dataAksesUAM['akses_am']=='all') 	  { $condAm = ''; } else { $condAm = "and nik_am in ('$amUAM')"; }
		
		// $uam				= "$condDiv $condSeg $condTreg $condWit $condAm";
		$uam				= "$condDiv $condTreg $condWit";
		
		return $uam;
	}
	
	public function circleimg($img){
		$sImage = 'http://13.67.54.108/raisa/v2//images/user/1536058858Nadia_Maharani.jpg';
		// error_reporting(E_ALL);
		// ini_set('display_errors', '1');

		$filename = $img;


		$ext = pathinfo($filename, PATHINFO_EXTENSION);

		if ($ext=="jpg" || $ext=="jpeg") {
		$image_s = imagecreatefromjpeg($filename);
		} else if ($ext=="png") {
		$image_s = imagecreatefrompng($filename);
		}

		$width = imagesx($image_s);
		$height = imagesy($image_s);


		$newwidth = 285;
		$newheight = 232;

		$image = imagecreatetruecolor($newwidth, $newheight);
		imagealphablending($image,true);
		imagecopyresampled($image,$image_s,0,0,0,0,$newwidth,$newheight,$width,$height);

		// create masking
		$mask = imagecreatetruecolor($width, $height);
		$mask = imagecreatetruecolor($newwidth, $newheight);



		$transparent = imagecolorallocate($mask, 255, 0, 0);
		imagecolortransparent($mask, $transparent);



		imagefilledellipse($mask, $newwidth/2, $newheight/2, $newwidth, $newheight, $transparent);



		$red = imagecolorallocate($mask, 0, 0, 0);
		imagecopy($image, $mask, 0, 0, 0, 0, $newwidth, $newheight);
		imagecolortransparent($image, $red);
		imagefill($image,0,0, $red);

		// output and free memory
		// header('Content-type: image/png');
		imagepng($image);
		// imagedestroy($image);
		// imagedestroy($mask);
	}
}
