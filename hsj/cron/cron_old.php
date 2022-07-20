<?php
$con = mysqli_connect("localhost","R41sa","b1sm1llah","raisa2");
// CEK CONNECT
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}else{
  echo "";	  
}
//EXPORT
$sql = "SELECT * FROM lop_temp";
$result = $con->query($sql);
$file = '/var/www/html/raisa/cron/'.date('Ymd-his').'.csv';
$fp = fopen($file, 'w');
chmod($file, 0777);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$data = $row['id_proj'].','.$row['kode_raisa'].','.$row['status'].','.$row['nipnas'].','.$row['segmen'].','.$row['nik_am'].','.$row['witel'].','.$row['pagu_proj'].','.$row['nilai_win'].','.$row['treg'].','.$row['cutoff_date']."\n";
		fwrite($fp, $data);
    }
	fclose($fp);
	
	mysqli_query($con,"DELETE FROM lop_temp");
	
} else {
    echo "";
}
// INSERT PERFORM
mysqli_query($con,"INSERT INTO lop_temp (id_proj,kode_raisa,status,nipnas,segmen,nik_am,witel,pagu_proj,nilai_win,treg,cutoff_date)
					select * FROM(
					select a.id_proj,a.kode_raisa,a.status,c.nipnas,c.segmen,c.nik_am,c.witel,a.pagu_proj,a.nilai_win,c.treg,NOW() as cutoff_date from lop a left join lo b ON b.id_lo = a.id_lo left join map c on b.id_map = c.id_map )as master");
mysqli_close($con);
?>