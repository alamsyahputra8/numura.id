<style>
	@import url('https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap');
	body {
		font-family: 'Indie Flower', cursive;
	}
	#penerima {
		position: absolute;
		/*transform: scaleY(-1) scaleX(-1);*/
	    /*left: 0.5cm;*/
	    /*bottom: 0.5cm;*/
	    top: 0.5cm;
	    width: 100%;
	    text-align: center;
	}
	.table {
		border: 1px solid black;
	}
	.table td {
		border: 1px solid black;
	}
	.bgbox {
		/*position: absolute;*/
		/*transform: scaleY(-1) scaleX(-1);*/
		/*border: 1px solid black;*/
		position: absolute;
	    /*top: 0.5cm;*/
	    bottom: 0.5cm;
	    right: 0.5cm;
	    height: 15.5cm;
	    /*border: 1px solid red;*/
	}
	.bgimage {
		/*position: absolute;
	    bottom: 0.5cm;
	    left: 0.5cm;*/
	    width: 9cm;
	    text-align: center;
	}
	.bgimage img {
		max-width: 7cm;
		max-height: 7cm;
	}
	.bgtext {
		/*position: absolute;*/
	    bottom: 0.5cm;
	    width: 10.5cm;
	    left: 5.5cm;
	    height: 4cm;
	}
	.bgname {
		font-size: 1cm;
	    white-space: nowrap;
	}
	.bgtextdetail {
		font-size: 14px;
	}
	.bgpesanan {
		font-size: 18px;
		padding-top:0.3cm;
	}
	.bgperawatan {
		font-size: 14px;
	}
	.bgperawatan ul {
		padding-left: 20px;
		margin-top: 0px;
	}
	.bgperawatan h3 {
		margin-bottom: 0px;
	}
</style>
<body style="margin: 0px; padding: 0px;">
<?PHP
$statuspesanan 	= '(Diproses)';
$statusvar 		= '2';

$userid 	= $id;

$getuser 	= $this->db->query("
			SELECT * from user where userid=$id
			")->result_array();
$datuser 	= array_shift($getuser);
$userid 	= $datuser['userid'];
$role 		= $datuser['id_role'];

if ($role==1) {
	$condpes 	= '';
} else {
	$condpes 	= "and userid='$userid'";
}
$qTot 		= "
			SELECT a.*,
				(SELECT label from size where id_size=a.ukuran) label_size,
				(SELECT label from color where id=a.warna) label_color,
				(SELECT code_color from color where id=a.warna) codecolor,
				(SELECT file from karakter where id_karakter=a.karakter) pictchar,
				(SELECT name from user where userid=a.userid) reseller
			FROM pesanan a where status in ($statusvar) $condpes
			";
$cekTot 	= $this->db->query($qTot)->num_rows();

$qTotSize 	= "
			SELECT base.*,
				jml/paper_size as paper,
			    ceiling(jml/paper_size) jmlkertas
			FROM (
			    SELECT a.*, 
			        (SELECT count(*) from pesanan where ukuran=a.id_size and status in ($statusvar)) jml
			    FROM `size` a order by sort asc
			) as base
			";
$gTS 		= $this->db->query($qTotSize)->result_array();
?>
<div style="width: 18cm; margin: 0 auto; border: 0px solid transparent">

<!-- <h1 style="text-align: center;">Print Pesanan <?PHP echo $statuspesanan; ?> : <?PHP echo $cekTot; ?></h1><hr> -->
<?PHP
$qPes 		= "
			SELECT * FROM (
				SELECT a.*,
					(SELECT label from size where id_size=a.ukuran) label_size,
					(SELECT detail from size where id_size=a.ukuran) detail_size,
					(SELECT label from color where id=a.warna) label_color,
					(SELECT code_color from color where id=a.warna) codecolor,
					(SELECT file from karakter where id_karakter=a.karakter) pictchar,
					(SELECT nama from karakter where id_karakter=a.karakter) namachar,
					(SELECT kode from karakter where id_karakter=a.karakter) codechar,
					(SELECT name from user where userid=a.userid) reseller,
					(select nama_penerima from pengiriman x left join pengiriman_detail xb on x.id_pengiriman=xb.id_pengiriman where xb.id_pesanan=a.id_pesanan) penerima,
					(select x.id_pengiriman from pengiriman x left join pengiriman_detail xb on x.id_pengiriman=xb.id_pengiriman where xb.id_pesanan=a.id_pesanan) id_pengiriman
				FROM pesanan a where flag_restok=1  and status in ($statusvar) $condpes
				order by karakter
			) AS final
			order by id_pengiriman,karakter
			";
			// KONDISI RESTOK ADA, DAN SUDAH ADA PENGIRIMAN
			// where flag_restok=1 and flag_pengiriman=1 and status in ($statusvar) $condpes

$getPes 	= $this->db->query($qPes)->result_array();
$cekPes 	= $this->db->query($qPes)->num_rows();
if ($cekPes>0) {

	foreach ($getPes as $pes) { 
		$idpes 	= $pes['id_pesanan'];
		echo '
		<div class="bgkolom" style="border: 0px solid rgba(0,0,0,.1); width: 18cm; height: 29.95cm; position:relative">
			<div id="penerima">'.$pes['penerima'].'</div>
			<div class="bgbox">
				<div class="bgimage">
					<img src="'.base_url().'images/charprint/'.$pes['pictchar'].'">
				</div>
				<div class="bgtext">
					<div class="bgname"><b>Halo, '.$pes['custom_nama'].'</b></div>
					<div class="bgtextdetail">
						Terimakasih telah membeli kaos Numura.id yaaa !!<br>
						Berikut ini adalah pesanan kamu :
					</div>
					<div class="bgpesanan">
						<b>'.$pes['label_color'].' | '.$pes['label_size'].' '.$pes['detail_size'].' | '.$pes['codechar'].'-'.$pes['namachar'].'</b>
					</div>
					<div class="bgperawatan">
						<h3><b>PETUNJUK PERAWATAN :</b></h3>
						<ul>
							<li>Disarankan mencuci kaos secara manual dan jangan menggunakan mesin pengering otomatis.</li>
							<li>Balik terlebih dahulu kaos yang akan disetrika sehingga bagian sablon tidak terkena panas langsung.</li>
							<li>Jangan gunakan detergen atau pemutih yang keras.</li>
							<li>Cuci dengan air di bawah temperatur 30 C.</li>
						</ul>
					</div>
				</div>
			</div>
		</div>';
	}

} else {
	echo '
	<div class="col-sm-12" style="padding: 10px;">
		Mohon maaf belum ada data pesanan.<br>
		Silahkan membuat pesanan terlebih dahulu di menu Pesanan, atau bisa klik 
		<b><a href="<?PHP echo base_url(); ?>pesanan">disini</a></b>.
	</div>
	';
}
// echo '<div style="clear: both;"></div><hr style="border-color: #bdbcbc;">';
echo '<div style="clear: both;"></div>';
?>
</div>
</body>