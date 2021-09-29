<?php
$con = mysqli_connect("localhost","R41sa","b1sm1llah","R41s4v2019");
// CEK CONNECT
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}else{
  echo "";	  
}

//EXPORT
$sql = "SELECT * FROM weekly";
$result = $con->query($sql);
$file = '/var/www/html/raisa/v3/cron/Excel_Weekly_CUTOFFDATE('.date('d-m-Y').').csv';
$fp = fopen($file, 'w');
chmod($file, 0777);
$datas = "id_lo".","."id_lop".","."id_sirup".","."id_map".","."id_lelang".","."nama_k_l_d_i".","."satker_lo".","."nama_am".","."nama_pkt".","."pagu_proj".","."nilai_win".","."kode_raisa".","."status".","."portofolio".","."subsidaries".","."pemenang".","."metode_pemilihan".","."waktu_pelaksanaan".","."tanggal_transaksi".","."kategori".","."treg".","."witel".","."nomor_kb".","."tanggal_kb".","."file_kb".","."note".","."nomor_order".","."last_update".","."update_by".","."cutoff_date".","."sustain_dari"."\n";
fwrite($fp, $datas);
if ($result->num_rows > 0) {
    //output data of each row
	
	while($row = $result->fetch_assoc()) {
		$data = $row['id_lo'].','.$row['id_lop'].','.$row['id_sirup'].','.$row['id_map'].','.$row['id_lelang'].','.$row['nama_k_l_d_i'].','.str_replace(","," ",$row['satker_lo']).','.$row['nama_am'].','.str_replace(","," ",$row['nama_pkt']).','.$row['pagu_proj'].','.$row['nilai_win'].','.$row['kode_raisa'].','.$row['status'].','.$row['portfolio'].','.$row['subsidaries'].','.str_replace(","," | ",$row['pemenang']).','.$row['metode_pemilihan`'].','.$row['waktu_pelaksanaan'].','.$row['tanggal_transaksi'].','.$row['kategori'].','.$row['treg'].','.$row['witel'].','.$row['nomor_kb'].','.$row['tanggal_kb'].','.str_replace(","," | ",$row['nomor_order']).','.$row['note'].','.str_replace(","," | ",$row['nomor_order']).','.$row['last_update'].','.$row['update_by'].','.$row['cutoff_date'].','.$row['sustain_dari']."\n";
		fwrite($fp, $data);
    }
	fclose($fp);
	
	mysqli_query($con,"DELETE FROM weekly");
	mysqli_query($con,"DELETE FROM lop_temp");
	
} else {
    echo "";
}
//require 'PHPMailer/PHPMailerAutoload.php';
// $mail = new PHPMailer;
// $mail->isSMTP();
// $mail->Host = 'smtp.gmail.com';
// $mail->SMTPAuth = true;
// $mail->Username = 'info.appraisatelkom@gmail.com';
// $mail->Password = 't3lk0mindonesia';
// $mail->SMTPSecure = 'tls';
// $mail->Port = 587;
// $mail->setFrom('info@13.67.54.108', 'Data Cutoff Weekly '.date('d-m-Y').'');
// $mail->addReplyTo('info@13.67.54.108', 'Data Cutoff Weekly '.date('d-m-Y').'');

// $sql_mail = "SELECT a.*,b.email FROM `mail_weekly` a LEFT JOIN user b ON a.username=b.username";
// $result_mail = $con->query($sql_mail);
// if ($result_mail->num_rows > 0) {
	// while($row_mail = $result_mail->fetch_assoc()) {
		// $mail->addAddress($row_mail['email']);
	// }
// }
// $mail->Subject = 'Informasi Data Weekly | RAISA';
// $mail->isHTML(true);
// $mailContent = "
	// Raisa Versi 2 - 2019 
    // <p>Data Weekly Cutoff tanggal ".date('d-m-Y').".</p>
	// ";
// $mail->Body = $mailContent;
// $mail->addAttachment($file);
// if(!$mail->send()){
    // echo 'Pesan tidak dapat dikirim.';
    // echo 'Mailer Error: ' . $mail->ErrorInfo;
// }else{
    // echo 'Pesan telah terkirim';
// }
// INSERT PERFORM
$q = "INSERT IGNORE INTO `weekly` (`id_lo`, `id_lop`, `id_sirup`, `id_map`, `id_lelang`, `nama_k_l_d_i`, `satker_lo`, `nama_am`, `nama_pkt`, `pagu_proj`, `nilai_win`, `kode_raisa`, `status`, `portofolio`, `subsidaries`, `pemenang`, `metode_pemilihan`, `waktu_pelaksanaan`, `tanggal_transaksi`, `kategori`, `treg`, `witel`, `nomor_kb`, `tanggal_kb`,`file_kb`,`note`, `nomor_order`, `last_update`, `update_by`, `cutoff_date`, `sustain_dari` , `id_sr`)
					select * FROM(
					SELECT 
						a.id_lo,
						a.id_lop,
						a.id_sirup,
						b.id_map,
						a.id_lelang,
						d.nama_gc as nama_k_l_d_i,
						b.satker_lo,
						e.nama_am,
						a.nama_pkt,
						a.pagu_proj,
						a.nilai_win,
						a.kode_raisa,
						a.status,
						a.portfolio,
						f.nama_subs as subsidaries,
						(SELECT GROUP_CONCAT(pemenang) FROM pemenang WHERE id_pemenang = a.id_pemenang) as pemenang,
						a.metode,
						a.waktu as waktu_pelaksanaan,
						a.tanggal as tanggal_transaksi,
						a.kategori,
						c.treg,
						g.nama_witel as witel,
						a.nomor_kontrak as nomor_kb,
						a.tanggal_kb,
						 (SELECT GROUP_CONCAT(file) FROM file_lop WHERE id_lop = a.id_lop) as file_kb,
						 a.ket as note,
						 (SELECT GROUP_CONCAT(nomor_order) FROM `order` WHERE id_lop = a.id_lop) as nomor_order,
						 a.last_update,
						 a.update_by,
						 '".date('Y-m-d h:i:s')."' as cutoff_date,
						 a.sustain_dari,
						 a.id_sr
					FROM lop a LEFT JOIN lo b ON a.id_lo = b.id_lo LEFT JOIN map c ON b.id_map = c.id_map LEFT JOIN gc d ON c.nipnas = d.nipnas LEFT JOIN am e ON c.nik_am = e.nik_am LEFT JOIN subs f ON a.subs = f.id_subs LEFT JOIN witel g ON c.id_witel = g.id_witel )as master";
					
mysqli_query($con,$q);


// INSERT PERFORM
$xs = "INSERT IGNORE INTO lop_temp (id_proj,kode_raisa,status,nipnas,id_segmen,nik_am,id_witel,pagu_proj,nilai_win,treg,cutoff_date,id_divisi,sustain_dari,id_sr)
					select * FROM(
					select a.id_lop,a.kode_raisa,a.status,c.nipnas,c.id_segmen,c.nik_am,c.id_witel,a.pagu_proj,a.nilai_win,c.treg,NOW() as cutoff_date,c.id_divisi,a.sustain_dari,a.id_sr from lop a left join lo b ON b.id_lo = a.id_lo left join map c on b.id_map = c.id_map WHERE c.tahun = ".date('Y')." )as master";
mysqli_query($con,$xs);
mysqli_close($con);
?>