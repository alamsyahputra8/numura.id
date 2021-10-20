<?PHP 
if($userid=='all') {
	$userdata 	= $this->session->userdata('sesspwt'); 
} else {
	$getUserDt	= $this->db->query("
				SELECT * FROM user where userid='$userid'
				")->result_array();
	$userdata 	= array_shift($getUserDt); 
}
$userid 	= $userdata['userid'];
$name 		= $userdata['name'];
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

$qLPay 		= "
			SELECT 
				a.*,
				(SELECT label from payment_type where id=a.type) type_name,
				(SELECT name from user where userid=a.userid) myname
			from payment a where 1=1 $condpes
			";
$gLPay 		= $this->db->query($qLPay)->result_array();

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

$qpespen 	= "
			SELECT sum(harga) as price, sum(harga_normal) price2
			FROM pesanan a where 1=1 and status=1 $condpes
			";
$gpespen 	= $this->db->query($qpespen)->result_array();
$pespen 	= array_shift($gpespen);

$qPayS 		= "
			SELECT sum(masuk) as price
			FROM payment a where 1=1 $condpes
			";
$gPayS 		= $this->db->query($qPayS)->result_array();
$dPayS 		= array_shift($gPayS);
$paidsuc	= $this->formula->rupiah($dPayS['price']);

if ($userid==311) {
	$calchut 		= ($dPayP['price2']+$dOngP['price'])-$dPayS['price'];
	$calcpespen 	= (($dPayP['price2']+$dOngP['price'])-$dPayS['price'])-$pespen['price2'];
} else {
	$calchut 		= ($dPayP['price']+$dOngP['price'])-$dPayS['price'];
	$calcpespen 	= (($dPayP['price']+$dOngP['price'])-$dPayS['price'])-$pespen['price'];
}

if ($calcpespen<0) {
	$totalpending 		= $this->formula->rupiah(0);
} else {
	$totalpending 		= $this->formula->rupiah($calcpespen);
}
$totalhutang 		= $this->formula->rupiah($calchut);
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
</style>
<div class="row">
	<div class="col-12"><h2><?PHP echo $name; ?></h2></div>
</div>
<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

<div class="row">
	<div class="col-lg-6 col-sm-12">
		<div class="row">
			<div class="col-12"><h3>Ringkasan Pesanan</h3></div>
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
			<div class="col-6">
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
	</div>
	<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>
	<div class="col-lg-6">
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

			<div class="col-12">
				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered bg-warning">
					<div class="kt-portlet__body detbodypes">
						<div class="row">
							<div class="col-12">
								<div class="text-white"><b>Total Pesanan di Proses :</b></div>
								<h4><a href="#" class="text-white" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title=""><?PHP echo $totalpending; ?></a></h4>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>
	<div class="col-sm-12">
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
	</div>
	<div class="clearfix"></div>
</div>


<div class="row">
	
	<div class="row col-lg-6 col-sm-12" style="padding-right: 0px;">
		
	</div>
</div>
<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

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
<script type="text/javascript">
	$('#tablepay').DataTable({
            responsive: true,
            order: [[ 2, "desc" ]],
    });
</script>