
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

$curmonth   = date('m');

$qColor 	= $this->db->query("
			SELECT * FROM color where id IN (
				SELECT warna from (
					select warna, (select label from color where id=a.warna) labcol, count(*) jmlpesan 
					from pesanan a 
					where warna is not null and warna!='' $condpes
					group by warna
				) as base
				order by jmlpesan desc
			)
			and type='1'
			")->result_array();
$qSize 		= $this->db->query("
			SELECT * from (
				select ukuran, (select label from size where id_size=a.ukuran) labsize, (select sort from size where id_size=a.ukuran) sort, count(*) jmlpesan 
				from pesanan a 
				where ukuran is not null and ukuran!='' $condpes
				group by ukuran
			) as base
			order by sort asc
			")->result_array();

$cekTrans 	= $this->db->query("
			SELECT * from (
					select userid, (select name from user where userid=a.userid) resel, tanggal_pesan, count(*) jmlpesan 
					from pesanan a 
					where year(tanggal_pesan)=year(now()) and month(tanggal_pesan)='$curmonth' $condpes
					group by userid, tanggal_pesan
				) as base
			")->num_rows();

$qResel     = $this->db->query("
            SELECT * from user where userid in (
				select userid from (
					select userid, (select name from user where userid=a.userid) resel, tanggal_pesan, count(*) jmlpesan 
					from pesanan a 
					where year(tanggal_pesan)=year(now()) and month(tanggal_pesan)='$curmonth' $condpes
					group by userid, tanggal_pesan
				) as base
			) 
            ")->result_array();

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

$qDeliv		= "
			SELECT * FROM pengiriman where 1=1 $condpes
			";
$cekDeliv	= $this->db->query($qDeliv)->num_rows();

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
			SELECT * from size where flag=1 order by sort
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
	.card-spacer {
	    padding: 2rem 1rem !important;
	}
	/*#availstock .rotate { transform: rotate(-90deg); white-space: nowrap; }*/
	/*#availstock th { height: 120px; }*/
</style>
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div class="row">
		<div class="col-lg-5 col-sm-12">
			<!--begin::Mixed Widget 1-->
			<div class="card card-custom card-stretch gutter-b">
				<!--begin::Header-->
				<div class="card-header border-0 bg-danger py-5" style="background: #b5c3aa!important;">
					<h3 class="card-title font-weight-bolder text-white">Halo, <?PHP echo $nameuser; ?></h3>
					<div class="card-toolbar">
						<a href="<?PHP echo base_url(); ?>pesanan" class="btn btn-transparent-white btn-sm font-weight-bolder ">
							Buat Pemesanan Baru
						</a>
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body p-0 position-relative overflow-hidden">
					<!--begin::Chart-->
					<div id="" class="card-header card-rounded-bottom bg-danger" style="height: 150px; background: #b5c3aa!important; padding-top:0px!important; border-radius: 0px;">
						<h1 class="text-white"><?PHP echo $cekPesT; ?> pcs</h1>
						<div class="text-white"><b>Total Pesanan</b></div>
					</div>
					<!--end::Chart-->
					<!--begin::Stats-->
					<div class="card-spacer mt-n25">
						<!--begin::Row-->
						<div class="row m-0">
							<div class="col bg-light-danger px-6 py-8 rounded-xl mr-7 mb-7">
								<span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" style="margin-top:-10px;">
									    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									        <rect x="0" y="0" width="24" height="24"/>
									        <path d="M18.1446364,11.84388 L17.4471627,16.0287218 C17.4463569,16.0335568 17.4455155,16.0383857 17.4446387,16.0432083 C17.345843,16.5865846 16.8252597,16.9469884 16.2818833,16.8481927 L4.91303792,14.7811299 C4.53842737,14.7130189 4.23500006,14.4380834 4.13039941,14.0719812 L2.30560137,7.68518803 C2.28007524,7.59584656 2.26712532,7.50338343 2.26712532,7.4104669 C2.26712532,6.85818215 2.71484057,6.4104669 3.26712532,6.4104669 L16.9929851,6.4104669 L17.606173,3.78251876 C17.7307772,3.24850086 18.2068633,2.87071314 18.7552257,2.87071314 L20.8200821,2.87071314 C21.4717328,2.87071314 22,3.39898039 22,4.05063106 C22,4.70228173 21.4717328,5.23054898 20.8200821,5.23054898 L19.6915238,5.23054898 L18.1446364,11.84388 Z" fill="#000000" opacity="0.3"/>
									        <path d="M6.5,21 C5.67157288,21 5,20.3284271 5,19.5 C5,18.6715729 5.67157288,18 6.5,18 C7.32842712,18 8,18.6715729 8,19.5 C8,20.3284271 7.32842712,21 6.5,21 Z M15.5,21 C14.6715729,21 14,20.3284271 14,19.5 C14,18.6715729 14.6715729,18 15.5,18 C16.3284271,18 17,18.6715729 17,19.5 C17,20.3284271 16.3284271,21 15.5,21 Z" fill="#000000"/>
									    </g>
									</svg>
									<!--end::Svg Icon-->
									<b class="text-danger font-weight-bold font-size-h2" style="padding-left: 5px;">
										<?PHP echo $cekPesP; ?> <span class="font-size-h6">pcs</span>
									</b>
								</span>
								<a href="#" class="text-danger font-weight-bold font-size-h7">Pesanan Pending</a>
							</div>
							<div class="col bg-light-success px-6 py-8 rounded-xl mb-7">
								<span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" style="margin-top:-10px;">
									    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									        <rect x="0" y="0" width="24" height="24"/>
									        <path d="M7.83498136,4 C8.22876115,5.21244017 9.94385174,6.125 11.999966,6.125 C14.0560802,6.125 15.7711708,5.21244017 16.1649506,4 L17.2723671,4 C17.3446978,3.99203791 17.4181234,3.99191839 17.4913059,4 L17.5,4 C17.8012164,4 18.0713275,4.1331782 18.2546625,4.34386406 L22.5900048,6.8468751 C23.0682974,7.12301748 23.2321726,7.73460788 22.9560302,8.21290051 L21.2997802,11.0816097 C21.0236378,11.5599023 20.4120474,11.7237774 19.9337548,11.4476351 L18.5,10.6198563 L18.5,20 C18.5,20.5522847 18.0522847,21 17.5,21 L6.5,21 C5.94771525,21 5.5,20.5522847 5.5,20 L5.5,10.6204852 L4.0673344,11.4476351 C3.58904177,11.7237774 2.97745137,11.5599023 2.70130899,11.0816097 L1.04505899,8.21290051 C0.768916618,7.73460788 0.932791773,7.12301748 1.4110844,6.8468751 L5.74424153,4.34512566 C5.92759515,4.13371 6.19818276,4 6.5,4 L6.50978325,4 C6.58296578,3.99191839 6.65639143,3.99203791 6.72872211,4 L7.83498136,4 Z" fill="#000000"/>
									    </g>
									</svg>
									<!--end::Svg Icon-->
									<b class="text-success font-weight-bold font-size-h2" style="padding-left: 5px;">
										<?PHP echo $cekPesS; ?> <span class="font-size-h6">pcs</span>
									</b>
								</span>
								<a href="#" class="text-success font-weight-bold font-size-h7 mt-2">Pesanan Dikirim</a>
							</div>
						</div>
						<!--end::Row-->
						<!--begin::Row-->
						<div class="row m-0">
							<div class="col bg-light-warning px-6 py-8 rounded-xl mr-7">
								<span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" style="margin-top: -10px;">
									    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									        <polygon points="0 0 24 0 24 24 0 24"/>
									        <path d="M16.5,4.5 C14.8905,4.5 13.00825,6.32463215 12,7.5 C10.99175,6.32463215 9.1095,4.5 7.5,4.5 C4.651,4.5 3,6.72217984 3,9.55040872 C3,12.6834696 6,16 12,19.5 C18,16 21,12.75 21,9.75 C21,6.92177112 19.349,4.5 16.5,4.5 Z" fill="#000000" fill-rule="nonzero"/>
									    </g>
									</svg>
									<!--end::Svg Icon-->
									<b class="text-warning font-weight-bold font-size-h2" style="padding-left: 5px;">
										<?PHP echo $cekPesPr; ?> <span class="font-size-h6">pcs</span>
									</b>
								</span>
								<a href="#" class="text-warning font-weight-bold font-size-h7 mt-2">Pesanan Diproses</a>
							</div>
							<div class="col bg-light-primary px-6 py-8 rounded-xl">
								<span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" style="margin-top:-10px;">
									    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									        <rect x="0" y="0" width="24" height="24"/>
									        <path d="M20.4061385,6.73606154 C20.7672665,6.89656288 21,7.25468437 21,7.64987309 L21,16.4115967 C21,16.7747638 20.8031081,17.1093844 20.4856429,17.2857539 L12.4856429,21.7301984 C12.1836204,21.8979887 11.8163796,21.8979887 11.5143571,21.7301984 L3.51435707,17.2857539 C3.19689188,17.1093844 3,16.7747638 3,16.4115967 L3,7.64987309 C3,7.25468437 3.23273352,6.89656288 3.59386153,6.73606154 L11.5938615,3.18050598 C11.8524269,3.06558805 12.1475731,3.06558805 12.4061385,3.18050598 L20.4061385,6.73606154 Z" fill="#000000" opacity="0.3"/>
									        <polygon fill="#000000" points="14.9671522 4.22441676 7.5999999 8.31727912 7.5999999 12.9056825 9.5999999 13.9056825 9.5999999 9.49408582 17.25507 5.24126912"/>
									    </g>
									</svg>
									<!--end::Svg Icon-->
									<b class="text-primary font-weight-bold font-size-h2" style="padding-left: 5px; white-space: nowrap;">
										<?PHP echo $cekDeliv; ?> <span class="font-size-h6">paket</span>
									</b>
								</span>
								<a href="#" class="text-primary font-weight-bold font-size-h7 mt-2">Total Pengiriman</a>
							</div>
						</div>
						<!--end::Row-->
					</div>
					<!--end::Stats-->

					<div class="card-body card-spacer d-flex flex-column" style="padding-top:0px!important; padding-bottom:0px!important;">
						<div class="mb-5">
							<div class="row row-paddingless mb-10">
								<!--begin::Item-->
								<div class="col">
									<div class="d-flex align-items-center mr-2">
										<!--begin::Symbol-->
										<div class="symbol symbol-45 symbol-light-warning mr-2 flex-shrink-0">
											<div class="symbol-label">
												<span class="svg-icon svg-icon-lg svg-icon-warning">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													        <polygon points="0 0 24 0 24 24 0 24"/>
													        <path d="M3.52270623,14.028695 C2.82576459,13.3275941 2.82576459,12.19529 3.52270623,11.4941891 L11.6127629,3.54050571 C11.9489429,3.20999263 12.401513,3.0247814 12.8729533,3.0247814 L19.3274172,3.0247814 C20.3201611,3.0247814 21.124939,3.82955935 21.124939,4.82230326 L21.124939,11.2583059 C21.124939,11.7406659 20.9310733,12.2027862 20.5869271,12.5407722 L12.5103155,20.4728108 C12.1731575,20.8103442 11.7156477,21 11.2385688,21 C10.7614899,21 10.3039801,20.8103442 9.9668221,20.4728108 L3.52270623,14.028695 Z M16.9307214,9.01652093 C17.9234653,9.01652093 18.7282432,8.21174298 18.7282432,7.21899907 C18.7282432,6.22625516 17.9234653,5.42147721 16.9307214,5.42147721 C15.9379775,5.42147721 15.1331995,6.22625516 15.1331995,7.21899907 C15.1331995,8.21174298 15.9379775,9.01652093 16.9307214,9.01652093 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
													    </g>
													</svg>
												</span>
											</div>
										</div>
										<!--end::Symbol-->
										<!--begin::Title-->
										<div>
											<div class="font-size-h6 text-warning font-weight-bolder"><?PHP echo $paidpen; ?></div>
											<div class="font-size-sm text-muted font-weight-bold mt-1">Total Pesanan</div>
										</div>
										<!--end::Title-->
									</div>
								</div>
								<!--end::Item-->
								<!--begin::Item-->
								<div class="col">
									<div class="d-flex align-items-center mr-2">
										<!--begin::Symbol-->
										<div class="symbol symbol-45 symbol-light-success mr-2 flex-shrink-0">
											<div class="symbol-label">
												<span class="svg-icon svg-icon-lg svg-icon-success">
													<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													        <rect x="0" y="0" width="24" height="24"/>
													        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
													        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
													        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
													    </g>
													</svg>
												</span>
											</div>
										</div>
										<!--end::Symbol-->
										<!--begin::Title-->
										<div>
											<div class="font-size-h6 text-success font-weight-bolder"><?PHP echo $paidsuc; ?></div>
											<div class="font-size-sm text-muted font-weight-bold mt-1">Total Pembayaran</div>
										</div>
										<!--end::Title-->
									</div>
								</div>
								<!--end::Item-->
							</div>
							<div class="row row-paddingless">
								<!--begin::Item-->
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
						</div>
						<!--end::Items-->
					</div>
				</div>
				<!--end::Body-->
			</div>
			<!--end::Mixed Widget 1-->
		</div>

		<div class="col-lg-7 col-sm-12">
			<!--begin::List Widget 3-->
			<div class="card card-custom gutter-b">
				<!--begin::Header-->
				<div class="card-header border-0">
					<h3 class="card-title font-weight-bolder text-dark">Penjualan Bulan Ini</h3>
				</div>
				<!--end::Header-->
				<!--begin::Body-->
				<div class="card-body pt-2">
					<?PHP if ($cekTrans>0) { ?>
					<div id="demo_2"></div>
					<?PHP } else { ?>
						<div style="padding: 50px;"><center>Belum ada Data transaksi pada Bulan ini.</center></div>
					<?PHP } ?>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-6 col-sm-12">
					<div class="card card-custom card-stretch gutter-b">
						<!--begin::Header-->
						<div class="card-header border-0">
							<h3 class="card-title font-weight-bolder text-dark">Statistik Warna</h3>
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-2">
							<div id="chart_11" class="d-flex justify-content-center"></div>
						</div>
					</div>
				</div>
				<div class="col-lg-6 col-sm-12">
					<div class="card card-custom card-stretch gutter-b">
						<!--begin::Header-->
						<div class="card-header border-0">
							<h3 class="card-title font-weight-bolder text-dark">Statistik Ukuran</h3>
						</div>
						<!--end::Header-->
						<!--begin::Body-->
						<div class="card-body pt-2">
							<div id="chart_12" class="d-flex justify-content-center"></div>
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
		<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered" style="overflow: scroll;">
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
		<div class="kt-portlet kt-portlet--solid-default kt-portlet--bordered" style="overflow: scroll;">
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
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="text/javascript">
	// Shared Colors Definition
	var primary = '#6993FF';
	var success = '#1BC5BD';
	var info = '#8950FC';
	var warning = '#FFA800';
	var danger = '#F64E60';

	<?PHP if ($cekTrans>0) { ?>
	var grafikpes = function() {
		var options = {
	        series: [
	            <?PHP foreach ($qResel as $resel) { ?>
	            {
	                name: '<?PHP echo $resel['name']; ?>',
	                data: [
	                    <?PHP
	                    $idresel    = $resel['userid'];
	                    $qPerform   = $this->db->query("
	                                SELECT * from (
	                                    select userid, (select name from user where userid=a.userid) resel, tanggal_pesan, count(*) jmlpesan 
	                                    from pesanan a 
	                                    where year(tanggal_pesan)=year(now()) and month(tanggal_pesan)='$curmonth' and userid='$idresel'
	                                    group by userid, tanggal_pesan
	                                ) as base
	                                order by tanggal_pesan asc, jmlpesan desc
	                                ")->result_array();
	                    foreach ($qPerform as $perf) {
	                        echo "{ x: new Date('".$perf['tanggal_pesan']."').getTime(), y: ".$perf['jmlpesan']."},";
	                    }
	                    ?>
	                ]
	            },
	            <?PHP } ?>
	        ],
	        chart: {
	            height: 350,
	            type: 'area'
	        },
	        dataLabels: {
	            enabled: false
	        },
	        stroke: {
	            curve: 'smooth'
	        },
	        xaxis: {
	            type: 'datetime',
	        },
	        tooltip: {
	            x: {
	                format: 'dd/MM/yyyyy'
	            },
	        }
	    };

	    var chart = new ApexCharts(document.querySelector('#demo_2'), options);
	    chart.render();
   	}
   	<?PHP } ?>

   	var warnapes = function() {
	    var options = {
			series: [
            <?PHP foreach ($qColor as $color) {
	            $idcolor    = $color['id'];
	            $qSCol   	= $this->db->query("
	                        SELECT * from (
								select warna, (select label from color where id=a.warna) labcol, count(*) jmlpesan 
								from pesanan a 
								where warna = '$idcolor' $condpes
								group by warna
							) as base
							order by jmlpesan desc
	                        ")->result_array();
        		foreach ($qSCol as $scol) {echo $scol['jmlpesan'].',';}
            }
            ?>
            ],
			labels: [
				<?PHP foreach ($qColor as $color) {
		            $idcolor    = $color['id'];
		            $qSCol   	= $this->db->query("
		                        SELECT * from (
									select warna, (select label from color where id=a.warna) labcol, count(*) jmlpesan 
									from pesanan a 
									where warna = '$idcolor' $condpes
									group by warna
								) as base
								order by jmlpesan desc
		                        ")->result_array();
	        		foreach ($qSCol as $scol) {echo "'".$scol['labcol']."',";}
            	}
            	?>
			],
			chart: {
				width: 350,
				type: 'donut',
			},
			responsive: [{
				breakpoint: 480,
				options: {
					chart: {
						width: 300
					},
					legend: {
						position: 'bottom'
					}
				}
			}],
			colors: [
				<?PHP foreach ($qColor as $color) {
		            $idcolor    = $color['id'];
		            $qSCol   	= $this->db->query("
		                        SELECT * from (
									select warna, (select code_color from color where id=a.warna) codcol, count(*) jmlpesan 
									from pesanan a 
									where warna = '$idcolor' $condpes
									group by warna
								) as base
								order by jmlpesan desc
		                        ")->result_array();
	        		foreach ($qSCol as $scol) {echo "'".$scol['codcol']."',";}
            	}
            	?>
			]
		};

		var chart = new ApexCharts(document.querySelector('#chart_11'), options);
		chart.render();
	}

	var ukpes = function () {
		var apexChart = "#chart_12";
		var options = {
			series: [
			<?PHP foreach ($qSize as $spes) {
				echo $spes['jmlpesan'].',';
			}
			?>
			],
			chart: {
				width: 350,
				type: 'pie',
			},
			labels: [
				<?PHP foreach ($qSize as $spes) {
					echo "'".$spes['labsize']."',";
				}
				?>
			],
			responsive: [{
				breakpoint: 480,
				options: {
					chart: {
						width: 200
					},
					legend: {
						position: 'bottom'
					}
				}
			}],
			colors: [primary, success, warning, danger, info]
		};

		var chart = new ApexCharts(document.querySelector(apexChart), options);
		chart.render();
	}

	<?PHP if ($cekTrans>0) { ?>
	grafikpes();
	<?PHP } ?>

	warnapes();
	ukpes();

	$('#tablepay').DataTable({
            responsive: true,
            order: [[ 2, "desc" ]],
    });
</script>