<style>
	.table {
		border: 1px solid black;
	}
	.table td {
		border: 1px solid black;
	}
</style>
<body style="margin: 0px; padding: 0px;">
<?PHP
if ($id=='pending') {
	$statuspesanan 	= '(Perlu Diproses)';
	$statusvar 		= '1';
} else if ($id=='proses') {
	$statuspesanan 	= '(Sedang di Diproses)';
	$statusvar 		= '2';
} else if ($id=='send') {
	$statuspesanan 	= '(Sudah di Kirim)';
	$statusvar 		= '3';
} else {
	$statuspesanan = '(All)';
	$statusvar 		= '1,2,3';
}

$userdata 	= $this->session->userdata('sesspwt'); 
$userid 	= $userdata['userid'];
$role 		= $userdata['id_role'];

$userdata 	= $this->session->userdata('sesspwt'); 
$userid 	= $userdata['userid'];
$role 		= $userdata['id_role'];

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
			FROM pesanan a where flag_restok=0 and status in ($statusvar) $condpes
			";
$cekTot 	= $this->db->query($qTot)->num_rows();

$qTotSize 	= "
			SELECT base.*,
				jml/paper_size as paper,
			    ceiling(jml/paper_size) jmlkertas
			FROM (
			    SELECT a.*, 
			        (SELECT count(*) from pesanan where ukuran=a.id_size and flag_restok=0 and status in ($statusvar)) jml
			    FROM `size` a order by sort asc
			) as base
			";
$gTS 		= $this->db->query($qTotSize)->result_array();
?>
<div style="width: 21cm; margin: 0 auto; border: 1px solid #000">

<!-- <h1 style="text-align: center;">Print Pesanan <?PHP echo $statuspesanan; ?> : <?PHP echo $cekTot; ?></h1><hr> -->
<div style="padding-left: 20px;">
	<h4 style="margin:0px; margin-bottom: 5px;">Pesanan perukuran</h4>
	<table cellspacing="0" cellpadding="0" style="min-width: 200px;">
		<?PHP foreach($gTS as $tsize) { ?>
		<tr>
			<td><?PHP echo $tsize['label']; ?></td>
			<td>:</td>
			<td><?PHP echo $tsize['jml']; ?> (<?PHP echo $tsize['jmlkertas']; ?> Lembar)</td>
		</tr>
		<?PHP } ?>
	</table>
</div>
<hr>
<?PHP

// echo '
// 	<div style="clear:both;"></div>
// 	<div style="height: 4.1cm; margin: 0px; font-size: 26px; vertical-align: bottom; text-align: center; padding-top:2cm; margin-bottom:-2cm;">
// 		UKURAN : '.$groupsize['label'].'
// 	</div>';
$qPes 		= "
			SELECT a.*,
				(SELECT label from size where id_size=a.ukuran) label_size,
				(SELECT label from color where id=a.warna) label_color,
				(SELECT code_color from color where id=a.warna) codecolor,
				(SELECT file from karakter where id_karakter=a.karakter) pictchar,
				(SELECT name from user where userid=a.userid) reseller
			FROM pesanan a where flag_restok=0 and status in ($statusvar) $condpes
			";
$getPes 	= $this->db->query($qPes)->result_array();
$cekPes 	= $this->db->query($qPes)->num_rows();
if ($cekPes>0) {

	foreach ($getPes as $pes) { 
		$idpes 	= $pes['id_pesanan'];
		// echo '
		// <div class="bgkolom" style="border: 1px solid rgba(0,0,0,.1); width: 5.19cm; height: 4.1cm; float:left;">
		// 	<div style="text-align: center; font-size: 18px; margin-bottom: 10px; border-bottom: 1px solid rgba(0,0,0,.1); padding: 10px;">
		// 		<b>'.$pes['reseller'].'</b>
		// 	</div>
		// 	<div class="clear:both;"></div>
		// 	<div style="width: 48%; float: left;">
		// 		<img src="'.base_url().'images/char/'.$pes['pictchar'].'" style="width: 100%;">
		// 	</div>
		// 	<div style="width: 50%; float: left; padding-top: 1rem%">
		// 		<b>'.$pes['custom_nama'].'</b><br>
		// 		Size : '.$pes['label_size'].'<br>
		// 		<div style="float:left; width: 15px; height: 15px; background-color: '.$pes['codecolor'].'; margin-right: 5px;"></div> '.$pes['label_color'].'
		// 	</div>
		// </div>';
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
?>	
	<div style="clear: both;"></div>
	<!-- <hr style="border-color: #bdbcbc;"> -->

	<style>
		.table, .table td { border: 1px solid black; }
		.table td { padding: 2px; }
	</style>

	<?PHP 
	$qSize 		= "SELECT * FROM size order by sort asc";
	$getSize 	= $this->db->query($qSize)->result_array();
	$cekSize 	= $this->db->query($qSize)->num_rows();

	$qColor 	= "SELECT * FROM color order by 1 asc";
	$getColor 	= $this->db->query($qColor)->result_array();
	$cekColor 	= $this->db->query($qColor)->num_rows();
	?>
	<h4 style="text-align: center;">TOTAL KAOS : <?PHP echo $cekPes; ?></h4>
	<!-- <table class="table" style="width: 100%;" cellpadding="0" cellspacing="0">
		<tr>
			<td rowspan="3" style="width: 3cm; text-align: center;">RESELLER</td>
			<td colspan="<?PHP echo $cekColor*$cekSize; ?>" style="text-align: center;">KAOS</td>
		</tr>
		<tr>
			<?PHP
			foreach ($getColor as $color) {
			?>
				<td colspan="<?PHP echo $cekSize; ?>" bgcolor="<?PHP echo $color['code_color']; ?>" style="text-align: center;"><?PHP echo $color['label']; ?></td>
			<?PHP } ?>
		</tr>
		<tr>
			<?PHP
			foreach ($getColor as $color) {
				foreach ($getSize as $size) {
			?>
					<td style="text-align: center;" bgcolor="<?PHP echo $color['code_color']; ?>"><?PHP echo $size['label']; ?></td>
				<?PHP } ?>
			<?PHP } ?>
		</tr>

		<?PHP 
		$getReseller = $this->db->query("
					SELECT DISTINCT(userid) userid,
						(SELECT name from user where userid=a.userid) reseller
					FROM pesanan a where status in ($statusvar)
					")->result_array();
		foreach ($getReseller as $seller) {
		?>
		<tr>
			<td><?PHP echo $seller['reseller']; ?></td>
			<?PHP
			foreach ($getColor as $color) {
				foreach ($getSize as $size) {
					$colorres 	= $color['id'];
					$sizeres 	= $size['id_size'];
					$userres	= $seller['userid'];

					$qJml 		= "SELECT * FROM pesanan where status in ($statusvar) and ukuran='$sizeres' and warna='$colorres' and userid='$userres'";
					$cekJml 	= $this->db->query($qJml)->num_rows();
			?>
					<td style="text-align: center;"><?PHP echo $cekJml; ?></td>
				<?PHP } ?>
			<?PHP } ?>
		</tr>
		<?PHP } ?>
	</table> -->

	<h4 style="text-align: center;">TOTAL KAOS PER UKURAN</h4>
	<table class="table" style="width: 100%;" cellpadding="0" cellspacing="0">
		<tr>
			<td>WARNA</td>
			<?PHP 
			foreach ($getSize as $size) {
			?>
					<td><?PHP echo $size['label']; ?></td>
			<?PHP 
			}
			?>
			<td>TOTAL</td>
		</tr>
		<?PHP
		foreach ($getColor as $color) {
			$icol = $color['id'];
		?>
		<tr>
			<td><?PHP echo $color['label']; ?></td>
			<?PHP
			foreach ($getSize as $size) {
				$colorres 	= $color['id'];
				$sizeres 	= $size['id_size'];

				$qJml 		= "SELECT * FROM pesanan where flag_restok=0 and status in ($statusvar) and ukuran='$sizeres' and warna='$colorres'";
				$cekJml 	= $this->db->query($qJml)->num_rows();
				@$totalpersize[$icol] += $cekJml;
			?>
				<td bgcolor="" style="text-align: center;"><b><?PHP echo $cekJml; ?></b></td>
			<?PHP } ?>
			<td style="text-align: center;"><?PHP echo $totalpersize[$icol]; ?></td>
		</tr>
		<?PHP } ?>
	</table>

	<h4 style="text-align: center;">TOTAL BIAYA BELANJA</h4>
	<table class="table" style="width: 100%;" cellpadding="0" cellspacing="0">
		<tr>
			<td>UKURAN</td>
			<td>KAOS</td>
			<td>HARGA</td>
		</tr>
		<?PHP
		$getHpp 	= $this->db->query("
					SELECT ukuran, count(*) pcs, sum(hpp) total, sortby from (
					    select (select label from size where id_size=a.ukuran) ukuran, 
						(select sort from size where id_size=a.ukuran) sortby, 
					    (select hpp from size where id_size=a.ukuran) hpp from pesanan a 
					    where flag_restok=0 and status in ($statusvar) $condpes
					) as base
					group by ukuran
					order by sortby asc
					")->result_array();
		foreach ($getHpp as $hpp) {
			@$totalhpp += $hpp['total'];
			@$totalpcs += $hpp['pcs'];
		?>
		<tr>
			<td><?PHP echo $hpp['ukuran']; ?></td>
			<td style="text-align: center;"><?PHP echo $hpp['pcs']; ?> pcs</td>
			<td style="text-align: right;"><?PHP echo $this->formula->rupiah($hpp['total']); ?></td>
		</tr>
		<?PHP } ?>
		<tr>
			<td><b>TOTAL</b></td>
			<td style="text-align: center;"><b><?PHP echo @$totalpcs; ?> pcs</b></td>
			<td style="text-align: right;"><b><?PHP echo $this->formula->rupiah(@$totalhpp); ?></b></td>
		</tr>
	</table>
</div>
</body>