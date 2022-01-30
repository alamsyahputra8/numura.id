<?PHP 
$userdata 	= $this->session->userdata('sesspwt'); 
$userid 	= $userdata['userid'];
$nameuser 	= $userdata['name'];
$role 		= $userdata['id_role'];

$getReseller= $this->db->query("
			SELECT * FROM user where id_role not in (1)
			order by name asc
			")->result_array();

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

$qSisa 		= "SELECT resel, CONCAT('IDR. ', FORMAT(sisa, 0)) jumlah from (
				select resel, ((total_pesanan+ongkir)-payment) sisa from (
				    select 
				        userid, (select name from user where userid=a.userid) resel, sum(harga) total_pesanan, 
				        (select sum(ongkir) from pengiriman where userid=a.userid and id_pengiriman in 
				        		(
				        		select id_pengiriman from pengiriman_detail where id_pesanan in (
				        			select id_pesanan from pesanan where status not in (1)
				        		)
				        	)
				        ) ongkir,
				        (select sum(masuk) from payment where userid=a.userid) payment
				    from pesanan a where status not in (1) and flag_restok=1 group by userid
				) as base
			) as master
			where sisa>0
			order by sisa desc";
$getSisa 	= $this->db->query($qSisa)->result_array();
$cekSisa 	= $this->db->query($qSisa)->num_rows();

$qSisaT 	= "SELECT resel, CONCAT('IDR. ', FORMAT(sisa, 0)) jumlah from (
				select resel, ((total_pesanan+ongkir)-payment) sisa from (
				    select 
				        userid, (select name from user where userid=a.userid) resel, sum(harga) total_pesanan, 
				        (select sum(ongkir) from pengiriman where userid=a.userid) ongkir,
				        (select sum(masuk) from payment where userid=a.userid) payment
				    from pesanan a where 1=1 group by userid
				) as base
			) as master
			where sisa>0
			order by sisa desc";
$getSisaT 	= $this->db->query($qSisaT)->result_array();

$qPesPeng 	= "
			SELECT * FROM (
				SELECT 
					(SELECT name from user where userid=a.userid) resel,
					count(id_pesanan) jumlah
				FROM pesanan a where status=2 and flag_pengiriman=0 and flag_restok=1
				group by userid
			) as cek
			order by jumlah desc
			";
$cekPesPeng = $this->db->query($qPesPeng)->num_rows();
$getPesPeng = $this->db->query($qPesPeng)->result_array();

$qPesPeng2 	= "
			SELECT * FROM (
				SELECT 
					(SELECT name from user where userid=a.userid) resel,
					count(id_pesanan) jumlah
				FROM pesanan a where status=2 and flag_pengiriman=0 and kaos_type=2 and flag_restok=1
				group by userid
			) as cek
			order by jumlah desc
			";
$cekPesPeng2 = $this->db->query($qPesPeng2)->num_rows();
$getPesPeng2 = $this->db->query($qPesPeng2)->result_array();
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
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

	<div class="row">
		<div class="col-lg-12">
			<div class="form-group row">
				<div class='input-group'>
					<select class="form-control m-select2" id="reseller" name="Reseller">
						<option value='all'>Semua Reseller</option>
						<?PHP 
						foreach($getReseller as $seller){
							echo "<option value='".$seller['userid']."'>".$seller['name']."</option>";
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div id="bgresult">
			<div id="loadermini" id="loaderstat"></div> 
		</div>

		<?PHP if ($cekPesPeng>0 or $cekPesPeng2>0) { ?>
		<div class="row col-12">
			<div class="col-12"><h3>Pesanan Proses Belum ada Pengiriman</h3></div>	
			<div class="col-lg-6 col-sm-6">
				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
					<div class="kt-portlet__body">
						<h4>LENGAN PENDEK</h4>
						<table class="table nowrap" style="width: 100%;">
							<thead>
								<tr>
									<th>RESELLER</th>
									<th class="text-right">JUMLAH PESANAN</th>
								</tr>
							</thead>
							<tbody>
								<?PHP
								foreach ($getPesPeng as $pp) {
								?>
								<tr>
									<td><b><?PHP echo $pp['resel']; ?></b></td>
									<td class="text-right"><?PHP echo $pp['jumlah']; ?></td>
								</tr>
								<?PHP } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-sm-6">
				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
					<div class="kt-portlet__body">
						<h4>LENGAN PANJANG</h4>
						<table class="table nowrap" style="width: 100%;">
							<thead>
								<tr>
									<th>RESELLER</th>
									<th class="text-right">JUMLAH PESANAN</th>
								</tr>
							</thead>
							<tbody>
								<?PHP
								foreach ($getPesPeng2 as $pp2) {
								?>
								<tr>
									<td><b><?PHP echo $pp2['resel']; ?></b></td>
									<td class="text-right"><?PHP echo $pp2['jumlah']; ?></td>
								</tr>
								<?PHP } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<?PHP } ?>
		
		<div class="row col-12">
			<div class="col-12"><h3>Sisa Pembayaran</h3></div>	
			<div class="col-lg-6 col-sm-12">
				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
					<div class="kt-portlet__body">
						<h5 class="text-danger">Pesanan Keseluruhan</h5>
						<table class="table nowrap" style="width: 100%;">
							<thead>
								<tr>
									<th>RESELLER</th>
									<th class="text-right">SISA PEMBAYARAN</th>
								</tr>
							</thead>
							<tbody>
								<?PHP
								foreach ($getSisaT as $sisaT) {
								?>
								<tr>
									<td><b><?PHP echo $sisaT['resel']; ?></b></td>
									<td class="text-right"><?PHP echo $sisaT['jumlah']; ?></td>
								</tr>
								<?PHP } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<div class="col-lg-6 col-sm-12">
				<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered">
					<div class="kt-portlet__body">
						<h5 class="text-warning">Pesanan Dalam Proses</h5>
						<table class="table nowrap" style="width: 100%;">
							<thead>
								<tr>
									<th>RESELLER</th>
									<th class="text-right">SISA PEMBAYARAN</th>
								</tr>
							</thead>
							<tbody>
								<?PHP
								foreach ($getSisa as $sisa) {
								?>
								<tr>
									<td><b><?PHP echo $sisa['resel']; ?></b></td>
									<td class="text-right"><?PHP echo $sisa['jumlah']; ?></td>
								</tr>
								<?PHP } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>