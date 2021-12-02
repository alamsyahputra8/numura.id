<?PHP 
$userdata 	= $this->session->userdata('sesspwt'); 
$userid 	= $userdata['userid'];
$nameuser 	= $userdata['name'];
$role 		= $userdata['id_role'];

if ($role==1) {
	$condpes 	= '';
	$texthutang	= 'hutang Reseller';
	$textongkir	= 'belum dibayar';
	$textkom	= 'Komisi Reseller';
} else {
	$condpes 	= "and userid='$userid'";
	$texthutang	= 'harus dibayar';
	$textongkir	= 'harus dibayar';
	$textkom	= 'Komisi Anda';
}
$qPesT 		= "
			SELECT *
			FROM pesanan a where 1=1 $condpes
			";
$cekPesT 	= $this->db->query($qPesT)->num_rows();

$qPesP 		= "
			SELECT *
			FROM pesanan a where 1=1 and status in (1) $condpes
			";
$cekPesP 	= $this->db->query($qPesP)->num_rows();

$qLPay 		= "
			SELECT 
				a.*,
				(SELECT label from payment_type where id=a.type) type_name,
				(SELECT name from user where userid=a.userid) myname
			from payment a where 1=1 $condpes
			";
$gLPay 		= $this->db->query($qLPay)->result_array();

$qPesPr 	= "
			SELECT *
			FROM pesanan a where 1=1 and status in (2) $condpes
			";
$cekPesPr 	= $this->db->query($qPesPr)->num_rows();

$qPesS 		= "
			SELECT *
			FROM pesanan a where 1=1 and status in (3) $condpes
			";
$cekPesS 	= $this->db->query($qPesS)->num_rows();

$qOngP 		= "
			SELECT sum(ongkir) as price
			FROM pengiriman a where 1=1 $condpes
			";
$gOngP 		= $this->db->query($qOngP)->result_array();
$dOngP 		= array_shift($gOngP);
$ongkirpen	= $this->formula->rupiah($dOngP['price']);

$qPayP 		= "
			SELECT sum(harga) as price, sum(harga_normal) price2
			FROM pesanan a where 1=1 $condpes
			";
$gPayP 		= $this->db->query($qPayP)->result_array();
$dPayP 		= array_shift($gPayP);
if ($userid==311) {
	$paidpen	= $this->formula->rupiah($dPayP['price2']+$dOngP['price']);
} else {
	$paidpen	= $this->formula->rupiah($dPayP['price']+$dOngP['price']);
}

$qPayS 		= "
			SELECT sum(masuk) as price
			FROM payment a where 1=1 $condpes
			";
$gPayS 		= $this->db->query($qPayS)->result_array();
$dPayS 		= array_shift($gPayS);
$paidsuc	= $this->formula->rupiah($dPayS['price']);

if ($userid==311) {
	$calchut 	= ($dPayP['price2']+$dOngP['price'])-$dPayS['price'];
} else {
	$calchut 	= ($dPayP['price']+$dOngP['price'])-$dPayS['price'];
}
$totalhutang= $this->formula->rupiah($calchut);
if($calchut>0) {
	$bghutang 	= 'bg-danger';
} else {
	$bghutang 	= 'bg-success';
}

$qKomS 		= "
			SELECT sum(keluar) as price
			FROM payment a where 1=1 $condpes
			";
$gKomS 		= $this->db->query($qKomS)->result_array();
$dKomS 		= array_shift($gKomS);
$komsuc		= $this->formula->rupiah($dKomS['price']);

$qKomP 		= "
			SELECT sum(harga_normal-harga) as price
			FROM pesanan a where 1=1 $condpes
			";
$gKomP 		= $this->db->query($qKomP)->result_array();
$dKomP 		= array_shift($gKomP);
$saldopen 	= $dKomP['price'];
$kompen		= $this->formula->rupiah($saldopen);

$qKom 		= "
			SELECT sum(harga_normal-harga) as price
			FROM pesanan a where 1=1 $condpes
			";
$gKom 		= $this->db->query($qKom)->result_array();
$dKom 		= array_shift($gKom);
$komisi		= $this->formula->rupiah($dKom['price']);

$sisakom 	= $this->formula->rupiah($dKom['price']-$dKomS['price']);

$getColor 	= $this->db->query("
			SELECT * from color where type=1 order by 1
			")->result_array();

$getColor2 	= $this->db->query("
			SELECT * from color where type!=1 order by 1
			")->result_array();

$getSize 	= $this->db->query("
			SELECT * from size order by sort
			")->result_array();
?>
<style>
	.mt2rem {margin-top: 2rem;}
	.valuebgdash {margin-top: 0.5rem; margin-bottom: -1rem;}
	.kt-portlet--solid-danger { background: #c0392b!important; }
	#circlebg {
		font-size: 2rem;
	    line-height: 1rem;
	    text-align: center;
	    box-shadow: 0px 3px 10px rgba(0,0,0,.3);
	    z-index: 2;
	    width: 150px;
	    height: 150px;
	    border-radius: 100%;
	    position: absolute;
	    left: 36%;
    	top: 21%;
	    padding-top: 10%;
	}
	#circlebg span {font-size: 1rem;}
	#loadermini {
		width: 100%;
		height: calc(100% - 20px);
		background: #FFF url('<?PHP echo base_url(); ?>images/loadermini.gif') center no-repeat;
		background-size: 80px auto;
	}
	.halotext {
	    font-size: 14px;
    	padding-top: 5px;
	}
	.detbodypes {
		padding: 10px 15px!important;
	}
	.bgbigpanel {
		height: 210px;
	}
	.bgmidpanel {
		height: 140px;
	}
	.bigpan1title {
		margin-top: 60%;
	}
	.imgsvgshirt {
		width: 200px;
	    z-index: 0;
	    position: relative;
	    left: -100px;
	    opacity: 0.3;
	}
	.bgmidpanel .imgsvgshirt {
		width: 200px;
	    z-index: 0;
	    position: relative;
	    left: -100px;
	    opacity: 0.1;
	}
	.overflowhide { overflow: hidden; }
	.clearfix {clear: both;}
	/*#availstock .rotate { transform: rotate(-90deg); white-space: nowrap; }*/
	/*#availstock th { height: 120px; }*/
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

	
	<div class="row">
		<div class="col-6">
			<div class="halotext">Halo! <?PHP echo $nameuser; ?></div>
		</div>
		<div class="col-6 text-right">
			<a href="<?PHP echo base_url(); ?>pesanan" class="btn btn-sm btn-primary">Buat Pemesanan Baru</a>
		</div>
	</div>
	<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

	<div class="row">
		<div class="col-12"><h3>Ringkasan Pesanan</h3></div>
		<div class="row col-lg-6 col-sm-12" style="padding-right: 0px;">
			<div class="col-6">
				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered bgbigpanel bg-dark">
					<div class="kt-portlet__body overflowhide">
						<div class="row">
							<div class="col-4">
								<img class="imgsvgshirt" src="<?PHP echo base_url(); ?>assets/theme/assets/media/icons/svg/Clothes/T-Shirt.svg"/>
							</div>
							<div class="col-8">
								<h4 class="bigpan1title"><a class="text-white" href="#" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $cekPesT; ?> pcs</a></h4>
								<div class="text-white">Total Pesanan</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-6" style="padding: 0px;">
				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
					<div class="kt-portlet__body detbodypes">
						<div class="row">
							<div class="col-12">
								<h5><a href="#" class="text-danger" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $cekPesP; ?> pcs</a></h5>
								<div>Pesanan Pending</div>
							</div>
						</div>
					</div>
				</div>

				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
					<div class="kt-portlet__body detbodypes">
						<div class="row">
							<div class="col-12">
								<h5><a href="#" class="text-warning" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $cekPesPr; ?> pcs</a></h5>
								<div>Pesanan Diproses</div>
							</div>
						</div>
					</div>
				</div>

				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
					<div class="kt-portlet__body detbodypes">
						<div class="row">
							<div class="col-12">
								<h5><a href="#" class="text-success" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $cekPesS; ?> pcs</a></h5>
								<div>Pesanan Dikirim</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

	<div class="row">
		<div class="col-12"><h3>Ringkasan Pembayaran</h3></div>	
		<div class="col-6">
			<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
				<div class="kt-portlet__body detbodypes">
					<div class="row">
						<div class="col-12">
							<div>Total Pesanan :</div>
							<h5><a href="#" class="text-warning" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $paidpen; ?></a></h5>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
				<div class="kt-portlet__body detbodypes">
					<div class="row">
						<div class="col-12">
							<div>Total Pembayaran :</div>
							<h5><a href="#" class="text-success" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $paidsuc; ?></a></h5>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-12">
			<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered <?PHP echo $bghutang; ?>">
				<div class="kt-portlet__body detbodypes">
					<div class="row">
						<div class="col-12">
							<div class="text-white"><b>Total yang harus dibayar :</b></div>
							<h4><a href="#" class="text-white" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $totalhutang; ?></a></h4>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

	<div class="row">
		<div class="col-12"><h3>History Pembayaran</h3></div>	
		<div class="col-12">
			<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
				<div class="kt-portlet__body">
					<table class="table table-striped- table-bordered table-hover table-checkable" id="tablepay">
						<thead>
							<tr>
								<th>DARI</th>
								<th>JUMLAH</th>
								<th>TGL. BAYAR</th>
								<th>KE</th>
							</tr>
						</thead>
						<tbody>
							<?PHP foreach ($gLPay as $dpay) { ?>
							<tr>
								<td><?PHP echo $dpay['myname']; ?></td>
								<td><?PHP echo $this->formula->rupiah($dpay['masuk']); ?></td>
								<td><?PHP echo $dpay['tgl_paid']; ?></td>
								<td><?PHP echo $dpay['type_name']; ?></td>
							</tr>
							<?PHP } ?>
						</tbody>
						<tfoot>
							<tr>
								<th>DARI</th>
								<th>JUMLAH</th>
								<th>TGL. BAYAR</th>
								<th>KE</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div><br>

	<div class="row" id="availstock">
		<div class="col-12"><h3>Available Stock (WARNA UTAMA)</h3></div>
		<div class="col-12">* Refresh halaman untuk meilhat update stok yang tersedia.</div>
		<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
			<div class="col-12" style="padding: 0px;">
				<div class="kt-portlet__body" style="padding: 0px;">
					<table class="table table-striped- table-bordered table-hover table-checkable">
						<thead>
							<tr>
								<th style="width: 150px!important;">UKURAN</th>
								<?PHP foreach($getColor as $color) { ?>
								<th class="text-center">
									<div class="rotate">
										<i class="fa fa-circle" style="color: <?PHP echo $color['code_color']; ?>;"></i>
										<?PHP echo $color['label']; ?>
									</div>
								</th>
								<?PHP } ?>
							</tr>
						</thead>
						<tbody>
							<?PHP
							foreach ($getSize as $size) {
								$sizeid = $size['id_size'];
							?>
								<tr>
									<td><?PHP echo $size['label']; ?></td>
									<?PHP
									foreach($getColor as $color) {
										$colid 	= $color['id']; 
										
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
										} else {
											$colte	= '';
										}
									?>
									<td class="text-center" <?PHP echo $colte; ?>>
										<b><?PHP echo $sisastokfin; ?></b> pcs
									</td>
									<?PHP } ?>
								</tr>
							<?PHP } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div><br>

	<div class="row" id="availstock">
		<div class="col-12"><h3>Available Stock (WARNA LAMA)</h3></div>
		<div class="col-12">* Refresh halaman untuk meilhat update stok yang tersedia.</div>
		<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
			<div class="col-12" style="padding: 0px;">
				<div class="kt-portlet__body" style="padding: 0px;">
					<table class="table table-striped- table-bordered table-hover table-checkable">
						<thead>
							<tr>
								<th style="width: 150px!important;">UKURAN</th>
								<?PHP foreach($getColor2 as $color) { ?>
								<th class="text-center">
									<div class="rotate">
										<i class="fa fa-circle" style="color: <?PHP echo $color['code_color']; ?>;"></i>
										<?PHP echo $color['label']; ?>
									</div>
								</th>
								<?PHP } ?>
							</tr>
						</thead>
						<tbody>
							<?PHP
							foreach ($getSize as $size) {
								$sizeid = $size['id_size'];
							?>
								<tr>
									<td><?PHP echo $size['label']; ?></td>
									<?PHP
									foreach($getColor2 as $color) {
										$colid 	= $color['id']; 
										
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
										} else {
											$colte	= '';
										}
									?>
									<td class="text-center" <?PHP echo $colte; ?>>
										<b><?PHP echo $sisastokfin; ?></b> pcs
									</td>
									<?PHP } ?>
								</tr>
							<?PHP } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<?PHP if ($userid==311) { ?>
	<div class="row">
		<div class="col-12"><h3>Ringkasan Pendapatan</h3></div>
		<div class="row col-lg-7 col-sm-12" style="padding-right: 0px;">
			<div class="col-6">
				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered bgmidpanel">
					<div class="kt-portlet__body overflowhide">
						<div class="row">
							<div class="col-1">
								<img class="imgsvgshirt" src="<?PHP echo base_url(); ?>assets/theme/assets/media/icons/svg/Shopping/Wallet.svg"/>
							</div>
							<div class="col-10">
								<div class="text-right">Sisa Komisi</div>
								<h4 class="text-right"><a class="text-success" href="#" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $sisakom; ?></a></h4>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-6" style="padding: 0px;">
				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
					<div class="kt-portlet__body detbodypes">
						<div class="row">
							<div class="col-12">
								<div>Total komisi:</div>
								<h5><a href="#" class="text-warning" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $komisi; ?></a></h5>
							</div>
						</div>
					</div>
				</div>

				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
					<div class="kt-portlet__body detbodypes">
						<div class="row">
							<div class="col-12">
								<div>Komisi dicairkan:</div>
								<h5><a href="#" class="text-danger" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $komsuc; ?></a></h5>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
	<?PHP } ?>
</div>
<script type="text/javascript">
	$('#tablepay').DataTable({
            responsive: true,
            order: [[ 2, "desc" ]],
    });
</script>