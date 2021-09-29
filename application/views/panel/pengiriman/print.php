<link href="https://fonts.googleapis.com/css2?family=Grandstander&display=swap" rel="stylesheet">
<body style="margin: 0px; padding: 0px;">
<?PHP
if ($id=='proses') {
	$statuspesanan 	= 'Perlu Dikirim';
	$statusvar 		= '0';
} else if ($id=='send') {
	$statuspesanan 	= 'Dikirim';
	$statusvar 		= '1';
} else {
	$statuspesanan = '(All)';
	$statusvar 		= '0,1';
}
?>
<style>
	body {
		font-family: 'Grandstander', cursive;
		font-size: 13px;
	}
	.nametitle {
		font-size: 24px;
	}
	.nametitle span {
		font-size: 16px;
	}
	.bgkolom {
		/*background: url('<?PHP echo base_url(); ?>images/thankyoucard.jpg') center no-repeat;
		background-size: auto 100%;*/
		text-align: left;
	}
	.textlight {
		font-weight: 300;
		border-bottom: 1px dashed rgba(0,0,0,.1);
	}
	.table {
		width: 100%;
		font-size: 12px;
	}
	.table th {
		padding: 3px;
		font-size: 12px;
		border: 1px solid rgba(0,0,0,.3);
	}
	.table td {
		border-bottom: 1px solid rgba(0,0,0,.2);
		padding: 3px;
		font-size: 11px;
	}
	.ekspedisi {
		max-width: 100px;
	    position: absolute;
	    right: 0.5cm;
	    top: 1.2cm;
	}
</style>

<div style="width: 29.7cm; margin: 0 auto; border: 1px solid #000">
<?PHP
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
$qPen 		= "
			SELECT a.*,
			(SELECT logo from ekspedisi where id=a.ekspedisi) imgeks
			FROM pengiriman a where status in ($statusvar)
			and id_pengiriman in (
				SELECT id_pengiriman from pengiriman_detail x left join pesanan y on x.id_pesanan=y.id_pesanan 
				where y.status=2 and y.flag_restok=1
			)
			$condpes
			";
$getPen 	= $this->db->query($qPen)->result_array();
$cekPen 	= $this->db->query($qPen)->num_rows();
if ($cekPen>0) {

	foreach ($getPen as $pen) { 
		$id = $pen['id_pengiriman'];
		if($pen['imgeks']!='') {
			$imgeks 	= '<img class="ekspedisi" src="'.base_url().'images/eks/'.$pen['imgeks'].'">';
		} else {
			$imgeks 	= '';
		}
		echo '
		<div class="bgkolom" style="border: 1px solid rgba(0,0,0,.1); width: 14.7cm; height: 20.96cm; float:left;">
			<div style="padding:10px; position: relative;">
				<div>
					<div class="textlight">Pengirim:</div><br>
					<b class="nametitle">
						'.$pen['pengirim'].'<br>
						<span>'.$pen['hp_pengirim'].'</span>
					</b>
					'.$imgeks.'
				</div><br><br>
				<div>
					<div class="textlight">Penerima:</div><br>
					<b class="nametitle">
						'.$pen['nama_penerima'].'<br>
						<span>'.$pen['hp_penerima'].'</span>
					</b><br><br>
					<div style="width: 70%;">'.$pen['alamat'].'</div>
				</div>
				<hr style="border-top: 1px dashed rgba(0,0,0,.1);">
				<div>
					<table class="table" cellspacing="0" cellpadding="0">';
						$qPes 		= "
									SELECT a.*,
										(SELECT label from size where id_size=a.ukuran) label_size,
										(SELECT label from color where id=a.warna) label_color,
										(SELECT code_color from color where id=a.warna) codecolor,
										(SELECT kode from karakter where id_karakter=a.karakter) codechar,
										(SELECT nama from karakter where id_karakter=a.karakter) namachar
									FROM pengiriman_detail b left join pesanan a on b.id_pesanan=a.id_pesanan where b.id_pengiriman='$id'
									order by ukuran
									";
						$getPes 	= $this->db->query($qPes)->result_array();
						$cekPes 	= $this->db->query($qPes)->num_rows();

						echo '
						<tr>
							<th>Detail Produk <b>('.$cekPes.' PCS)</b></th>
						</tr>';
						if ($cekPes>0) {
							$i = 0;
							foreach ($getPes as $pes) { $i++;
								if ($cekPes>30 and $cekPes<70) {
									if ($i%2==0) { echo '</tr><tr>'; }
									echo '
										<td>
											<b>'.$pes['custom_nama'].'</b> | '.$pes['label_size'].' | '.$pes['label_color'].' | '.$pes['codechar'].'-'.$pes['namachar'].'
										</td>
									';
								} else if ($cekPes>70 and $cekPes<=150) {
									if ($i%3==0) { echo '</tr><tr>'; }
									echo '
										<td>
											<b>'.$pes['custom_nama'].'</b> | '.$pes['label_size'].' | '.$pes['label_color'].' | '.$pes['codechar'].'-'.$pes['namachar'].'
										</td>
									';
								} else if ($cekPes>150) {
									if ($i%4==0) { echo '</tr><tr>'; }
									echo '
										<td>
											<b>'.$pes['custom_nama'].'</b> | '.$pes['label_size'].' | '.$pes['label_color'].' | '.$pes['codechar'].'-'.$pes['namachar'].'
										</td>
									';
								} else {
									echo '
									<tr>
										<td>
											<b>'.$pes['custom_nama'].'</b> | '.$pes['label_size'].' | '.$pes['label_color'].' | '.$pes['codechar'].'-'.$pes['namachar'].'
										</td>
									</tr>
									';
								}
							}
						}
					echo '</table>
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
?>
<div style="clear:both;"></div>
</div>
</body>