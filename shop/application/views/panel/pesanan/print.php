<!--begin::Global Theme Bundle(used by all pages) -->
<link href="https://numura.id/shop/assets/theme/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />

<script src="<?PHP echo base_url(); ?>assets/theme/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
<script src="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
<!--end::Layout Skins -->
<link rel="shortcut icon" href="<?PHP echo base_url(); ?>images/favnumura.png" />

<style>
	body {font-size: 14px;}
	.table {
		border: 1px solid black;
	}
	.table td {
		border: 1px solid black;
	}
	.bgsuccess {
		background: #86c3aa;
	}
	.labtypekaos {
		/*font-size: 13px;*/
	    font-weight: bold;
	    white-space: nowrap;
	}
	.bgpanjang {
		background: ;
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
			FROM pesanan a where flag_restok='0' and status in ($statusvar) $condpes
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
<div style="width: 21cm; margin: 0 auto; border: 1px solid #000">

<!-- <h1 style="text-align: center;">Print Pesanan <?PHP echo $statuspesanan; ?> : <?PHP echo $cekTot; ?></h1><hr> -->
<?PHP
$qgrpSize 	= "
			SELECT base.*,
				jml/paper_size as paper,
			    ceiling(jml/paper_size) jmlkertas
			FROM (
			    SELECT a.*, 
			        (SELECT count(*) from pesanan where ukuran=a.id_size and status in ($statusvar)) jml
			    FROM `size` a order by sort asc
			) as base
			order by sort asc
			";
$ggroupS 	= $this->db->query($qgrpSize)->result_array();

foreach ($ggroupS as $groupsize) {
	$idsize 	= $groupsize['id_size'];

	$qPes 		= "
				SELECT a.*,
					a.custom_nama custom_namalower,
					(SELECT label from size where id_size=a.ukuran) label_size,
					(SELECT detail from size where id_size=a.ukuran) detail_size,
					(SELECT label from color where id=a.warna) label_color,
					(SELECT code_color from color where id=a.warna) codecolor,
					(SELECT label from kaos_type where id=a.kaos_type) label_type_kaos,
					(SELECT file from karakter where id_karakter=a.karakter) pictchar,
					(SELECT name from user where userid=a.userid) reseller
				FROM pesanan a where flag_restok='0' and ukuran='$idsize' and status in ($statusvar) $condpes
				order by karakter
				";
	$getPes 	= $this->db->query($qPes)->result_array();
	$cekPes 	= $this->db->query($qPes)->num_rows();
	if ($cekPes>0) {
		echo '
		<div style="clear:both;"></div>
		<div style="height: 4.1cm; margin: 0px; font-size: 26px; vertical-align: bottom; text-align: center; padding-top:2cm; margin-bottom:-2cm;">
			UKURAN : '.$groupsize['label'].'
		</div>';

		foreach ($getPes as $pes) { 
			$idpes 	= $pes['id_pesanan'];
			if ($pes['flag_print']=='1') {
				$done 	= 'bgsuccess';
			} else {
				$done 	= '';
			}
			if ($pes['kaos_type']=='1') {
				$clkt 	= '';
			} else {
				$clkt 	= 'bgpanjang';
			}

			if ($pes['userid']==319) {
				$gpen 	= $this->db->query("
							SELECT * FROM pengiriman 
							where id_pengiriman in (select id_pengiriman from pengiriman_detail where id_pesanan='$idpes')
						")->result_array();
				$dpen 	= array_shift($gpen);
				$labpen = 'SP ('.$dpen['nama_penerima'].')';
			} else {
				$labpen = $pes['reseller'];
			}
			echo '
			<div id="bgkol'.$idpes.'" class="bgkolom '.$done.' '.$clkt.'" style="border: 1px solid rgba(0,0,0,.1); width: 5.19cm; height: 4.1cm; float:left;">
				<div style="text-align: center; font-size: 14px!important; margin-bottom: 10px; border-bottom: 1px solid rgba(0,0,0,.1); padding: 10px 2px;">
					<b>'.$labpen.'</b>
				</div>
				<div class="clear:both;"></div>
				<div style="width: 45%; float: left;">
					<img src="'.base_url().'images/char/'.$pes['pictchar'].'" style="max-width: 100%; max-height: 2.6cm;">
				</div>
				<div style="width: 53%; float: left; padding-top: 1rem%">
					<b style="font-size: 16px!important; text-transform: capitalize;">'.str_replace("`","'",$pes['custom_namalower']).'</b><br>
					'.$pes['label_size'].' <span style="font-size:11px;">'.$pes['detail_size'].'</span><br>
					<div style="float:left; width: 15px; height: 15px; background-color: '.$pes['codecolor'].'; margin-right: 5px;"></div> '.$pes['label_color'].'
					<!--br>
					<div class="labtypekaos">('.str_replace('LENGAN ','',$pes['label_type_kaos']).')</div-->
					<button type="button" class="btnSelesai" data-id="'.$idpes.'">Selesai</button>
				</div>
			</div>';
		}

	} else {
		// echo '
		// <div class="col-sm-12" style="padding: 10px;">
		// 	Mohon maaf belum ada data pesanan.<br>
		// 	Silahkan membuat pesanan terlebih dahulu di menu Pesanan, atau bisa klik 
		// 	<b><a href="'.base_url().'pesanan">disini</a></b>.
		// </div>';
		echo '';
	}
	// echo '<div style="clear: both;"></div><hr style="border-color: #bdbcbc;">';
}
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
	<!-- <h4 style="text-align: center;">TOTAL KAOS : <?PHP echo $cekPes; ?></h4>
	<table class="table" style="width: 100%;" cellpadding="0" cellspacing="0">
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
	</table>

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

				$qJml 		= "SELECT * FROM pesanan where status in ($statusvar) and ukuran='$sizeres' and warna='$colorres'";
				$cekJml 	= $this->db->query($qJml)->num_rows();
				@$totalpersize[$icol] += $cekJml;
			?>
				<td bgcolor="<?PHP echo $color['code_color']; ?>" style="text-align: center;"><b><?PHP echo $cekJml; ?></b></td>
			<?PHP } ?>
			<td style="text-align: center;"><?PHP echo $totalpersize[$icol]; ?></td>
		</tr>
		<?PHP } ?>
	</table> -->
</div>
</body>
<script>
	$(document).on('click', '.btnSelesai', function(e){
	    e.preventDefault();

	    var uid = $(this).data('id'); // get ids of clicked row

	    $('#dynamic-content').hide(); // hide dive for loader
	    $('#modal-loader').show();  // load ajax loader
	    
	    $.ajax({
	        url: '<?PHP echo base_url(); ?>pesanan/updateprint',
	        type: 'POST',
	        data: 'id='+uid,
	        dataType: 'json'
	    })
	    .done(function(data){
	        $('#bgkol'+uid).addClass('bgsuccess');
	    })
	    .fail(function(){
	        
	    });
	});
</script>