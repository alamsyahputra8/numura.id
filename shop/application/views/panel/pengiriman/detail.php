<?PHP
$getDetail		= $this->db->query("
				SELECT 
					a.*,
					(SELECT sum(harga) from pesanan xa left join pengiriman_detail xb on xa.id_pesanan=xb.id_pesanan where xb.id_pengiriman=a.id_pengiriman) total,
					(SELECT count(id_pesanan) from pengiriman_detail where id_pengiriman=a.id_pengiriman) jmlpesanan,
					(SELECT sum(harga_normal-harga) from pesanan xa left join pengiriman_detail xb on xa.id_pesanan=xb.id_pesanan where xb.id_pengiriman=a.id_pengiriman) komisi
				from pengiriman a where a.id_pengiriman ='$id'
				")->result_array();
$detail 		= array_shift($getDetail);
$total 			= $detail['total'];
$jmlpes 		= $detail['jmlpesanan'];
$ongkir 		= $detail['ongkir'];
$komisi 		= $detail['komisi'];
$resel			= $detail['userid'];
?>
<style>
.kt-invoice-1 .kt-invoice__wrapper .kt-invoice__head .kt-invoice__container .kt-invoice__logo > a > h1 {
	margin-top: -20px;
    margin-bottom: 20px;
}
@media (max-width: 768px){
	.kt-invoice-1 .kt-invoice__wrapper .kt-invoice__body.kt-invoice__body--centered {
    	padding: 3rem 2rem 2rem 2rem;
	}
}
.sizemidle {
	max-width: 140px!important;
    max-height: 140px!important;
}
.kt-invoice-1 .kt-invoice__wrapper .kt-invoice__head .kt-invoice__container .kt-invoice__logo {
	padding-top: 5rem!important;
}
.bghead {
    background-image: linear-gradient(270deg, #1e1e1e, #333);
}
.kt-invoice-1 .kt-invoice__wrapper .kt-invoice__head .kt-invoice__container .kt-invoice__logo {
    padding-top: 0rem!important;
    margin-top: -2.5rem!important;
    margin-bottom: -1rem!important;
}
</style>
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div id="gagalinsert" class="alert alert-warning alert-elevate kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-warning"></i></div>
		<div class="alert-text">
			<strong>Failed!</strong> Change a few things up and try submitting again.
		</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div id="suksesinsert" class="alert alert-success fade show kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-black"></i></div>
		<div class="alert-text">Success!</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div id="suksesdelete" class="alert alert-secondary fade show kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
		<div class="alert-text">Your data has been deleted!</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div class="kt-portlet">
		<div class="kt-portlet__head kt-portlet__head--lg bghead" style="border-bottom: 0px; margin-bottom: -1px;">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
				</span>
				<h3 class="kt-portlet__head-title">
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						<a href="<?PHP echo base_url(); ?>pengiriman" class="btn btn-light btn-sm">
							<i class="la la-arrow-circle-left"></i> Kembali ke data Pengiriman
						</a>
					</div>
				</div>
			</div>
		</div>

		<div class="kt-portlet__body kt-portlet__body--fit">
			<div class="kt-invoice-1" id="update">
				<div class="kt-invoice__wrapper">
					<div class="kt-invoice__head bghead">
						<div class="kt-invoice__container kt-invoice__container--centered">
							<span class="kt-invoice__desc text-left text-white">
								<span>Pengirim :</span>
							</span>
							<div class="kt-invoice__logo">
								<a href="#">
									<h1 id="nama_lengkap"><?PHP echo $detail['pengirim']; ?></h1>
								</a>
							</div>
							<span class="kt-invoice__desc text-left text-white" style="padding-bottom: 1rem; padding-top: 0;">
								<span><i class="la la-mobile-phone"></i> Phone : <span><?PHP echo $detail['hp_pengirim']; ?></span></span>
							</span>

							<span class="kt-invoice__desc text-left text-white">
								<span>Dikirim ke :</span>
							</span>
							<div class="kt-invoice__logo">
								<a href="#">
									<h1 id="nama_lengkap"><?PHP echo $detail['nama_penerima']; ?></h1>
								</a>
							</div>
							<span class="kt-invoice__desc text-left text-white">
								<span><i class="la la-mobile-phone"></i> Phone : <span><?PHP echo $detail['hp_penerima']; ?></span></span>
								<span><i class="la la-map-marker"></i> Address : <span><?PHP echo $detail['alamat']; ?></span></span>
							</span>
						</div>
					</div>
					<div class="kt-invoice__body kt-invoice__body--centered">
						<div><h4>Detail Pesanan (<?PHP echo $jmlpes; ?> PCS):</h4></div>
						<div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
						<div id="bgdetailpesanan"></div>
						<div class="kt-separator kt-separator--space-md kt-separator--border-dashed"></div>
						<div id="payment">
							<div class="row">
								<div class="col-lg-9 col-7 text-right">
									<h5>Pesanan :</h5>
								</div>
								<div class="col-lg-3 col-5 text-right">
									<?PHP if ($resel==311) { ?>
									<h5><?PHP echo $this->formula->rupiah($total+$komisi); ?></h5>
									<?PHP } else { ?>
									<h5><?PHP echo $this->formula->rupiah($total); ?></h5>
									<?PHP } ?>
								</div>
							</div>
<!-- 
							<div class="row">
								<div class="col-lg-9 col-7 text-right">
									<h5>Komisi :</h5>
								</div>
								<div class="col-lg-3 col-5 text-right">
									<h5><?PHP echo $this->formula->rupiah($komisi); ?></h5>
								</div>
							</div> -->

							<div class="row">
								<div class="col-lg-9 col-7 text-right">
									<h5>Ongkir :</h5>
									<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>
								</div>
								<div class="col-lg-3 col-5 text-right">
									<h5><?PHP echo $this->formula->rupiah($ongkir); ?></h5>
									<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-9 col-7 text-right">
									<h3>Total :</h3>
								</div>
								<div class="col-lg-3 col-5 text-right">
									<?PHP if ($resel==311) { ?>
									<h3><?PHP echo $this->formula->rupiah($total+$komisi+$ongkir); ?></h3>	
									<?PHP } else { ?>
									<h3><?PHP echo $this->formula->rupiah($total+$ongkir); ?></h3>
									<?PHP } ?>
								</div>
							</div>
						</div>
					</div>
					<br><br>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end:: Content -->