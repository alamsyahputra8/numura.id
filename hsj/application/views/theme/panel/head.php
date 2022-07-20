<?PHP
// error_reporting(0);
$userdata 			= $this->session->userdata('sesselop'); 
$url 				= uri_string();
$userid 	 		= $userdata['userid'];
$getAksesUAM		= $this->query->getData('user_organization',"*","WHERE userid='$userid'");
$dataAksesUAM		= array_shift($getAksesUAM);

$getPict			= $this->query->getData('user','picture',"WHERE userid='".$userid."'");
$dPict				= array_shift($getPict);

$divisiUAM 			= str_replace(",","','",$dataAksesUAM['akses_divisi']);
$segmenUAM 			= str_replace(",","','",$dataAksesUAM['akses_segmen']);
$tregUAM 			= str_replace(",","','",$dataAksesUAM['akses_treg']);
$witelUAM 			= str_replace(",","','",$dataAksesUAM['akses_witel']);
$amUAM 				= str_replace(",","','",$dataAksesUAM['akses_am']);

if ($dataAksesUAM['akses_divisi']=='all') { $condDiv = ''; $uamdiv=""; } else { $uamdiv=" AND c.id_divisi in ('$divisiUAM') ";$condDiv = "WHERE id_divisi in ('$divisiUAM')"; }
if ($dataAksesUAM['akses_segmen']=='all') { $condSeg = "WHERE id_divisi =1"; $uamseg="";} else { $uamseg=" AND c.id_segmen in ('$segmenUAM') ";$condSeg = "WHERE id_segmen in ('$segmenUAM')"; }
if ($dataAksesUAM['akses_treg']=='all')   { $condTreg = ''; $uamtreg="";} else { $uamtreg=" AND c.treg in ('$tregUAM') ";$condTreg = "WHERE treg in ('$tregUAM')"; }
if ($dataAksesUAM['akses_witel']=='all')  { $condWit = ''; $uamwit="";} else { $uamwit=" AND c.id_witel in ('$witelUAM') ";$condWit = "WHERE id_witel in ('$witelUAM')"; }
if ($dataAksesUAM['akses_am']=='all') 	  { $condAm = 'WHERE raisa=1'; $uamam="";} else { $uamam=" AND c.nik_am in ($amUAM) and raisa=1";$condAm = "WHERE nik_am in ('$amUAM') AND raisa=1"; }

$exseg		= explode(",",$segmenUAM);
if ($tregUAM=='all') {
	$sendsegmen	= 'ALL SEGMEN';
} else {
	$getnameSeg	= $this->query->getData('segmen','group_concat(nama_segmen) as nama_segmen',"WHERE id_segmen in ('$segmenUAM')");
	$dNameSeg	= array_shift($getnameSeg);
	// $countsegmen	= count($exseg);
	$sendsegmen	= $dNameSeg['nama_segmen'];
}

$extreg		= explode(",",$tregUAM);
if ($tregUAM=='all') {
	$sendtreg	= 'ALL TREG';
} else {
	$counttreg	= count($extreg);
	if ($counttreg>1) {
		$sendtreg	= 'ALL TREG';
	} else {
		$sendtreg	= $tregUAM;
	}
}

$exwitel	= explode(",",$witelUAM);
if ($witelUAM=='all') {
	$sendwitel	= 'ALL WITEL';
} else {
	$countwitel	= count($exwitel);
	if ($countwitel>1) {
		$sendwitel	= 'ALL WITEL';
	} else {
		$getWitel	= $this->query->getData('witel','nama_witel',"WHERE id_witel='$witelUAM'");
		$dWitel		= array_shift($getWitel);
		$sendwitel	= $dWitel['nama_witel'];
	}
}
$q_baru = "
			select 
			id_segmen,
				nama_segmen as segmen,
				sum(DISTINCT(target_rev))as target_segmen,
				IFNULL(100/110 * sum(nilai_win)/sum(DISTINCT(target_rev)),0)as ach,
				count(id_lo)as jumlah_lo,
				count(pagu_proj)as target_jumlah_lo,
				count(pagu_proj)*1.5 as jumlah_poin_maksimal,
				IFNULL(SUM(countWin),0)as JumlahR2Win,
				IFNULL(SUM(poin),0) agregat_poin,
				IFNULL(sum(nilai_win),0)as rev_win
			from(
			select a.id_lo,d.nama_segmen,a.nilai_win,a.pagu_proj,a.kode_raisa,
				CASE 
					  when a.id_sr ='12' THEN 0 
					  when a.id_sr ='13' THEN 0 
					  when a.id_sr ='14' THEN 1
					  when a.id_sr ='15' THEN 1
					  when a.id_sr ='15' AND a.kategori NOT IN ('Scaling') THEN 1.5 
					  when a.id_sr ='15' AND a.kategori IN ('Scaling') THEN 2
					  ELSE 0
				END poin,
				CASE
					when a.id_sr='15' then 1
				END countWin,
				a.status,
				a.kategori,
				c.* 
				from lop a left join lo b ON a.id_lo = b.id_lo left join map c ON b.id_map = c.id_map left join segmen d ON c.id_segmen = d.id_segmen  
				WHERE 1=1 AND c.id_divisi =1 AND c.tahun=2018
				) 
				as table2 group by id_segmen,segmen
";
$exdata_handicap = $this->query->getDatabyQ("select *,(ach_segmen * index_kuantitas_project * allowance)as handicap from(
												select *,JumlahR2Win/jumlah_lo as allowance,IFNULL(1/ach,0) ach_segmen,1/index_kuantitas as index_kuantitas_project from (
													select 
														id_segmen,
														segmen,target_segmen,ach,
														jumlah_lo,
														target_jumlah_lo,
														jumlah_poin_maksimal,
														agregat_poin,
														agregat_poin/jumlah_poin_maksimal as index_kuantitas,
														rev_win,JumlahR2Win from(
													select 
														id_segmen,
														nama_segmen as segmen,
														sum(DISTINCT(target_rev))as target_segmen,
														IFNULL(100/110 * sum(nilai_win)/sum(DISTINCT(target_rev)),0)as ach,
														count(id_lo)as jumlah_lo,
														count(pagu_proj)as target_jumlah_lo,
														count(pagu_proj)*1.5 as jumlah_poin_maksimal,
														SUM(countWin)as JumlahR2Win,
														SUM(poin) agregat_poin,
														sum(nilai_win)as rev_win
													from(
													select a.id_lo,d.nama_segmen,a.nilai_win,a.pagu_proj,a.kode_raisa,
														CASE 
															when a.kode_raisa ='R0' THEN 0 
															  when a.kode_raisa ='R1+' THEN 0 
															  when a.kode_raisa ='R1++' THEN 1
															  when a.kode_raisa ='R2' AND a.status NOT IN ('WIN','NEW-GTMA') THEN 1
															  when a.kode_raisa ='R2' AND a.status IN ('WIN','NEW-GTMA') AND a.kategori NOT IN ('Scaling') THEN 1.5 
															  when a.kode_raisa ='R2' AND a.status IN ('WIN','NEW-GTMA') AND a.kategori IN ('Scaling') THEN 2
															  ELSE 0
														END poin,
														CASE
															when a.kode_raisa = 'R2' AND a.status='WIN' then 1
														END countWin,
														a.status,
														a.kategori,
														c.* 
														from lop a left join lo b ON a.id_lo = b.id_lo left join map c ON b.id_map = c.id_map left join segmen d ON c.id_segmen = d.id_segmen  
														WHERE 1=1 AND c.id_divisi =1 $uamdiv $uamseg $uamtreg $uamwit
														) 
														as table2 group by id_segmen,segmen
													)as table3
												)as table4  where id_segmen !=''
											)as table5");
					
?>
<style>
.hideifopen {
	display: none;
	-webkit-transition: 2s; /* Safari */
	transition: 2s;
}
.sidebar-collapse .hideifopen {
	display: block;
}
@media (max-width: 769px) {
	.bghandicap { display: none!important; }
}
#handicap .col-xs-4, #handicap .col-xs-3 { width: auto!important; }
#filternya .select2-container--default .select2-selection--single {
	border-radius: 0px;
}
#filternya .select2-container {
	width: 100%!important;
}
.nameuser {
	margin: 0px;
    margin-bottom: -5px;
    margin-top: 3px;
	color: #FFF;
}
.imgctop {
	max-height: 40px;
    border: 1px solid rgba(0,0,0,0.2);
    padding: 5px;
    background: rgba(0,0,0,0.1);
}
@media (max-width:600px) {
	.divname {
		font-size: 14px;
		margin: 0 auto;
		display: none;
	}
	.divname span {
		font-size: 11px;
	}
}
@media (min-width:601px) {
	.divname {
		font-size: 18px;
		margin: 0 auto;
	}
	.divname span {
		font-size: 12px;
	}
}
</style>

<header class="main-header"> 
	<a href="#" class="logo hidemobile">
		<span class="logo-mini">
			<img src="<?PHP echo base_url(); ?>images/logor.png" alt="">
		</span>
		<span class="logo-lg">
			<img src="<?PHP echo base_url(); ?>images/logotextv2elop.png" alt="">
		</span>
	</a>
	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top">
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <!-- Sidebar toggle button-->
			<span class="sr-only">Toggle navigation</span>
			<span class="pe-7s-menu"></span>
		</a>
		<div class="profileuser">
			<div class="nameprofileuser" style="padding:0px;">
				<h3 class="divname text-white">
					DIVISI GOVERMENT SERVICE
					<br><span><?PHP echo $sendsegmen; ?> (<?PHP echo $sendtreg; ?> / <?PHP echo $sendwitel; ?>)</span>
				</h3>
			</div>
		</div>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<!-- Inbox -->
				<?PHP if ($url =='' or $url == 'panel') { ?>
				<?PHP foreach($exdata_handicap as $handicap) { ?>
						<?PHP 
							if($handicap['segmen']=='MPS'){
								$nik = "800039";
							}else if($handicap['segmen']=='LGS'){	
								$nik = "790039";
							}else if($handicap['segmen']=='CGS'){	
								$nik = "845512";
							}else if($handicap['segmen']=='GAS'){	
								$nik = "720296";
							}else {
								$nik = "999999999";
							}
							$getUser	= $this->query->getData('user','*',"WHERE username='$nik'");
							$dUser		= array_shift($getUser);
							// GET TARGET PERSEGMEN
							$queryTseg	= "
							SELECT sum(target_rev) as totaltarget 
							from (
								select * from (
									select id_map, id_divisi, target_rev, id_segmen, treg, nik_am, id_witel from map
								) as basic
								where id_segmen='".$handicap['id_segmen']."'
							) as master
							";
							$qdataSeg		= $this->query->getDatabyQ($queryTseg);
							$dataSeg		= array_shift($qdataSeg);
							$target			= $dataSeg['totaltarget'];

							$qAchPS			= $this->query->getDatabyQ("
												select
												sum(nilai_win) achieved, sum(case when kode_raisa='R2' and status='NEW-GTMA' then nilai_win end) as achgtma
												from ( 
													select * from (
														select 
														a.id_lop,a.pagu_proj,a.kode_raisa,a.status,c.nik_am, c.id_divisi, c.id_segmen, c.id_witel,c.treg, case when a.PPN='1' then (100/110)*a.nilai_win else a.nilai_win end as nilai_win,c.target_rev
														from lop a 
														left join lo b 
														on a.id_lo=b.id_lo 
														left join map c 
														on c.id_map=b.id_map
													) as basic
													where kode_raisa='R2' and status in ('WIN','NEW-GTMA') and id_segmen='".$handicap['id_segmen']."'
												) as achseg
											");
							$dataAchPS		= array_shift($qAchPS);
							$achievement	= $dataAchPS['achieved'];
							
							// GET ACH SEGMEN PERSEN
							$achsegpersen	= ($achievement/$target);
							$satu_achseg = 1/$achsegpersen;
							$satu_idx_kuan = 1/$handicap['index_kuantitas'];
							$satu_allow = $handicap['allowance'];
							$final = round($satu_achseg * $satu_idx_kuan * $satu_allow,2);
							if($handicap['handicap']==''){
								
							}else{
						?>
						
						<li class="dropdown allhcnameshowhide segmenhcshowhide<?PHP echo $handicap['segmen'];?> messages-menu bghandicap" id="handicap" style="padding: 0 5px;">
							<div class="image col-xs-3" style="padding: 10px 5px 10px 0px;">
								<?PHP 
								if($dUser['picture']==''){
									$pic= 'default.png';
								} else {
									$pic= $dUser['picture'];
								}
								?>
								<img src="<?PHP echo base_url(); ?>images/user/<?PHP echo $pic; ?>" class="img-circle imgctop" alt="User Image" style="padding:0px;">
							</div>
							<div class="info col-xs-4 padding0" style="text-align:left; padding: 10px 10px 10px 10px;">
								<p class="hideifopen nameuser" style="margin-top: -4px;"><?PHP echo $dUser['name']; ?></p>
								<div class="hideifopen"><span style="font-size:10px; color: rgba(255,255,255,.55);"><i class="fa fa-envelope"></i> <?PHP echo $dUser['email']; ?></div>
								<div style="margin-top: -4px; margin-bottom: -10px;"><span style="font-size:10px; color: rgba(255,255,255,.55);">HC = <b><?PHP echo number_format($final,2)?></b></span></div>
							</div>
							<div class="clearfix"></div>
						</li>
				<?PHP 		}
					  } ?>
				<?PHP } ?>
				
				<li class="dropdown dropdown-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-keypad"></i></a>
					<ul class="dropdown-menu" style="width: 300px;">
						<li style="margin-bottom: 6px;">
							<div class="col-xs-12" style="margin-bottom: 10px; color: rgba(255,255,255,.85); font-size: 1.2rem; border-bottom: 1px solid rgba(255,255,255,.155); padding-bottom: 15px;">
								<p class="nameuser text-white">Applications</p>
							</div>
							<div class="clearfix"></div>
							
							<div class="col-xs-4">
								<a href="http://13.67.54.108/raisa/v2/2019_dev/" target="_blank"><img src="http://13.67.54.108/tamara/images/apps/logoraisa_new.png" class="img-responsive" alt="User Image" style="padding:10px"></a>
							</div>
							<div class="col-xs-4">
								<a href="http://13.67.54.108:81/anggun/v2/" target="_blank"><img src="http://13.67.54.108/tamara/images/apps/logo_anggun.png" class="img-responsive" alt="User Image" style="padding:10px"></a>
							</div>
							<div class="col-xs-4">
								<a href="http://13.67.54.108/tamara/" target="_blank"><img src="http://13.67.54.108/tamara/images/apps/logo_tamara-w2.png" class="img-responsive" alt="User Image" style="padding:10px"></a>
							</div>
							<div class="clearfix"></div>
						</li>
					</ul>
				</li>
				
				<?PHP 
				// if ($url=='panel/lo' OR $url=='panel/lop' OR $url=='panel/map' OR $url=='panel/am' OR $url=='panel/gc') { $folder = '../';}else{$folder='';}
				// if ($url =='' OR $url=='panel/lo' OR $url=='panel/lop' OR $url=='panel/map' OR $url=='panel/am' OR $url=='panel/gc') { 
				$folder='';
				if ($url =='' or $url=='panel') { 
				?>
				<!-- Notifications -->
				<li class="dropdown messages-menu" id="topnotif">
					<a href="#" id="icobutfilter" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-filter" data-toggle="tooltip" title="Show Filter" data-placement="bottom"></i></a>
					<ul class="dropdown-menu" style="width: 310px;">
						<li>
							<div id="filternya" class="" style="padding: 20px;">
								<div class="col-xs-2 padding0">
									<img src="<?PHP base_url(); ?><?PHP echo $folder;?>images/logotelkom.png" class="img-responsive">
								</div>
								<div class="col-xs-10 padding0">
									<h1 class="text-white" style="font-size:12px; margin: 0px; font-weight: bold; margin-top: 5px;">PROGRESS PENGAWALAN RAISA 2.0</h1>
									<small class="text-white" style="font-size:11px;">Summary Pengawalan RAISA 2.0 overall</small>
								</div>
								<div class="clearfix"></div>
								
								<!-- FILTER -->
								<div style="margin-top: 20px;">
									<form id="formfilter">
										<div class="col-sm-12 m-b-5 padding0">
											<select class="form-control select_map" placeholder="Periode" id='periode' name='periode'>
												<?PHP
												$yNow	= date('Y');
												for($y = 2018;$y<=date('Y')+1;$y++){
												?>
												<option <?PHP if ($y==$yNow) { echo 'selected'; } ?> value="<?PHP echo $y; ?>"><?PHP echo $y; ?></option>
												<?PHP
												}
												?>
											</select>
										</div>
										<div class="col-sm-12 m-b-5 padding0">
											<select class="form-control select_map" placeholder="Divisi" id='divisi' name='divisi'>
												<option value='ALL DIVISI'>ALL DIVISI</option>
												<?PHP
												$getDivisi		= $this->query->getData('divisi',"*","$condDiv order by id_divisi asc");
												foreach($getDivisi as $divisi) {
													if ($divisi['id_divisi']=='1') { $selected='selected'; } else { $selected=''; }
												?>
												<option <?PHP echo $selected; ?> value="<?PHP echo $divisi['id_divisi']; ?>"><?PHP echo $divisi['nama_divisi']; ?></option>
												<?PHP } ?>
											</select>
										</div>
										<div class="col-sm-12 m-b-5 padding0">
											<select class="form-control select_map" placeholder="Segmen" id='segment' name='segment'>
												<option value='ALL SEGMEN'>ALL SEGMEN</option>
												<?PHP
												$getSegmen		= $this->query->getData('segmen',"*","$condSeg order by nama_segmen asc");
												foreach($getSegmen as $segmen) {
												?>
												<option value="<?PHP echo $segmen['id_segmen']; ?>"><?PHP echo $segmen['nama_segmen']; ?></option>
												<?PHP } ?>
											</select>
										</div>
										<div class="col-sm-12 m-b-5 padding0">
											<select class="form-control select_map" placeholder="TREG" id='treg' name='treg'>
												<option value='ALL TREG'>ALL TREG</option>
												<?PHP
												$getTreg		= $this->query->getData('map',"treg","$condTreg group by treg order by treg asc");
												foreach($getTreg as $treg) {
												?>
												<option value="<?PHP echo $treg['treg']; ?>"><?PHP echo $treg['treg']; ?></option>
												<?PHP } ?>
											</select>
										</div>
										<div class="col-sm-12 m-b-5 padding0">
											<select class="form-control select_map" placeholder="Witel" id='witel' name='witel'>
												<option value='ALL Witel'>ALL Witel</option>
												<?PHP
												$getWitel		= $this->query->getData('map',"DISTINCT id_witel as witel, (select nama_witel from witel where id_witel=map.id_witel) as nama_witel","$condWit ORDER BY witel asc");
												foreach($getWitel as $witel) {
												?>
												<option value="<?PHP echo $witel['witel']; ?>"><?PHP echo $witel['nama_witel']; ?></option>
												<?PHP } ?>
											</select>
										</div>
										<div class="col-sm-12 m-b-5 padding0">
											<select class="form-control select_map" placeholder="NIK" id='am' name='am'>
												<option value='ALL AM'>ALL AM</option>
												<?PHP
												$getAm			= $this->query->getData('am',"DISTINCT nik_am as am, nama_am","$condAm order by nik_am asc");
												foreach($getAm as $am) {
												?>
												<option value="<?PHP echo $am['am']; ?>"><?PHP echo $am['am']; ?> - <?PHP echo $am['nama_am']; ?></option>
												<?PHP } ?>
											</select>
										</div>
										<div class="col-sm-12 padding0">
											<?PHP if ($url =='' or $url == 'panel') { ?>
												<button type="submit" name="filter" id="filter" class="btn btn-primary btn-sm col-xs-12">Filter</button>
											<?PHP }else if ($url =='panel/lop'){?>	
												<button type="button" name="filter" id="filter_lop" class="btn btn-primary btn-sm col-xs-12">Filter</button>
											<?PHP }else if ($url =='panel/lo'){?>	
												<button type="button" name="filter" id="filter_lo" class="btn btn-primary btn-sm col-xs-12">Filter</button>
											<?PHP }else if ($url =='panel/map'){?>	
												<button type="button" name="filter" id="filter_map" class="btn btn-primary btn-sm col-xs-12">Filter</button>
											<?PHP }else if ($url =='panel/gc'){?>	
												<button type="button" name="filter" id="filter_gc" class="btn btn-primary btn-sm col-xs-12">Filter</button>
											<?PHP }else if ($url =='panel/am'){?>	
												<button type="button" name="filter" id="filter_am" class="btn btn-primary btn-sm col-xs-12">Filter</button>
											
											<?PHP } ?>
										</div>
										<div class="clearfix"></div>
									</form>
									<script>
										$("#divisi").on('change', function(e) {
											$('#segment').html('');
											$('#treg').html('');
											$('#witel').html('');
											$('#am').html('');
											
											var datas 		= $(this).select2('data');
											var getid 		= datas[0].id;
											var output 		= "";
											
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/getSelect2SegmenD?id="+getid,
												type: 'Get',
												dataType: "json",

												success: function (result) {
														$("#segment").select2({ data: result });
												},
												error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
													alert("An error occurred while processing your request. Please try again.");
												}
											});
											
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/getSelect2TregD?id="+getid+"&idsegmen=",
												type: 'Get',
												dataType: "json",

												success: function (result) {
														$("#treg").select2({ data: result });
												},
												error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
													alert("An error occurred while processing your request. Please try again.");
												}
											});
											
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/getSelect2WitelD?id="+getid+"&idsegmen=&treg=",
												type: 'Get',
												dataType: "json",

												success: function (result) {
														$("#witel").select2({ data: result });
												},
												error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
													alert("An error occurred while processing your request. Please try again.");
												}
											});
											
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/getSelect2AmD?id="+getid+"&idsegmen=&treg=&idwitel=",
												type: 'Get',
												dataType: "json",

												success: function (result) {
														$("#am").select2({ data: result });
												},
												error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
													alert("An error occurred while processing your request. Please try again.");
												}
											});
											
										});
										
										$("#segment").on('change', function(e) {
											// Access to full data
											$('#treg').html('');
											$('#witel').html('');
											$('#am').html('');
											
											var datas 		= $('#divisi').select2('data');
											var getid 		= datas[0].id;
											var datasSegmen = $(this).select2('data');
											var getidSegmen = datasSegmen[0].id;
											var output = "";
											
											// alert(getid);
											
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/getSelect2TregD?id="+getid+"&idsegmen="+getidSegmen+"",
												type: 'Get',
												dataType: "json",

												success: function (result) {
														$("#treg").select2({ data: result });
												},
												error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
													alert("An error occurred while processing your request. Please try again.");
												}
											});
											
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/getSelect2WitelD?id="+getid+"&idsegmen="+getidSegmen+"&treg=",
												type: 'Get',
												dataType: "json",

												success: function (result) {
														$("#witel").select2({ data: result });
												},
												error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
													alert("An error occurred while processing your request. Please try again.");
												}
											});
											
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/getSelect2AmD?id="+getid+"&idsegmen="+getidSegmen+"&treg=&idwitel=",
												type: 'Get',
												dataType: "json",

												success: function (result) {
														$("#am").select2({ data: result });
												},
												error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
													alert("An error occurred while processing your request. Please try again.");
												}
											});
										});
										
										$("#treg").on('change', function(e) {
											// Access to full data
											$('#witel').html('');
											$('#am').html('');
											
											var datas 		= $('#divisi').select2('data');
											var getid 		= datas[0].id;
											var datasSegmen = $('#segment').select2('data');
											var getidSegmen = datasSegmen[0].id;
											var datasTreg 	= $(this).select2('data');
											var getidTreg 	= datasTreg[0].id;
											var output = "";
											
											// alert(getid);
											
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/getSelect2WitelD?id="+getid+"&idsegmen="+getidSegmen+"&treg="+getidTreg+"",
												type: 'Get',
												dataType: "json",

												success: function (result) {
														$("#witel").select2({ data: result });
												},
												error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
													alert("An error occurred while processing your request. Please try again.");
												}
											});
											
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/getSelect2AmD?id="+getid+"&idsegmen="+getidSegmen+"&treg="+getidTreg+"&idwitel=",
												type: 'Get',
												dataType: "json",

												success: function (result) {
														$("#am").select2({ data: result });
												},
												error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
													alert("An error occurred while processing your request. Please try again.");
												}
											});
										});
										
										$("#witel").on('change', function(e) {
											// Access to full data
											$('#am').html('');
											
											var datas 		= $('#divisi').select2('data');
											var getid 		= datas[0].id;
											var datasSegmen = $('#segment').select2('data');
											var getidSegmen = datasSegmen[0].id;
											var datasTreg 	= $('#treg').select2('data');
											var getidTreg 	= datasTreg[0].id;
											var datasWitel 	= $(this).select2('data');
											var getidWitel 	= datasWitel[0].id;
											var output = "";
											
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/getSelect2AmD?id="+getid+"&idsegmen="+getidSegmen+"&treg="+getidTreg+"&idwitel="+getidWitel+"",
												type: 'Get',
												dataType: "json",

												success: function (result) {
														$("#am").select2({ data: result });
												},
												error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
													alert("An error occurred while processing your request. Please try again.");
												}
											});
										});
									</script>
									<script>
										$('#formfilter').submit(function() {
												
											var formdata = new FormData(this);
											
											var getYear 	= $('#periode').val();
											// if(getYear!=='' && getYear < 2019){
												// setTimeout(function(){location.href="../2019"} , 1000);
											// }else{
												
											// }
											
											var getsegmen 	= $('#select2-segment-container').html();
											var getdivisi 	= $('#select2-divisi-container').html();
											
											// if (getsegmen === 'LGS') {
												// $('#viewlatesttrans').fadeIn('slow');
											// } else {
												// $('#viewlatesttrans').fadeOut('fast');
											// }
											
											if (getdivisi === 'DGS') {
												$('.allhcnameshowhide').fadeIn('slow');
											} else {
												$('.allhcnameshowhide').fadeOut('fast');
											}

											
											if (getsegmen === 'LGS') {
												$('.segmenhcshowhideMPS').hide();
												$('.segmenhcshowhideCGS').hide();
												$('.segmenhcshowhideGAS').hide();
											}else if(getsegmen === 'MPS') {
												$('.segmenhcshowhideLGS').hide();
												$('.segmenhcshowhideCGS').hide();
												$('.segmenhcshowhideGAS').hide();
											}else if(getsegmen === 'CGS') {
												$('.segmenhcshowhideLGS').hide();
												$('.segmenhcshowhideMPS').hide();
												$('.segmenhcshowhideGAS').hide();
											}else if(getsegmen === 'GAS') {
												$('.segmenhcshowhideLGS').hide();
												$('.segmenhcshowhideMPS').hide();
												$('.segmenhcshowhideCGS').hide();
											}
											
											// DIVISI FILTER NAME
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/filterdivtitlename",
												type: "POST",
												data: formdata,
												beforeSend: function(){ 
													$("#filter").html('Filtering...');
												},
												success: function(data) {
													if(data) {
														$("#filter").html('Filter');
														$(".profileuser").html(data);
													} else { 
														$("#filter").html('Filter');
													}
												},
												error: function (error) {
													console.log(error);
													alert('error; ' + eval(error));
												},
												contentType: false,
												processData: false
											});
											// return false;
											
											// DATA TABLE
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/filtertable",
												type: "POST",
												data: formdata,
												beforeSend: function(){ 
													$("#filter").html('Filtering...');
													$("#progresloader").fadeIn('fast');
												},
												success: function(data) {
													if(data) {
														$("#filter").html('Filter');
														$("#tabledashboard").html(data);
														$("#progresloader").fadeOut('fast');
													} else { 
														$("#filter").html('Filter');
														$("#progresloader").fadeOut('fast');
													}
												},
												error: function (error) {
													console.log(error);
													alert('error; ' + eval(error));
												},
												contentType: false,
												processData: false
											});
											// return false;
											
											// DATA SISA ANGGARAN
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/filterSisaAnggaran",
												type: "POST",
												data: formdata,
												beforeSend: function(){ 
													$("#filter").html('Filtering...');
													$("#progresloader").fadeIn('fast');
												},
												success: function(data) {
													if(data) {
														// alert(data);
														$("#filter").html('Filter');
														$("#viewsisaanggaran").html(data);
														$("#progresloader").fadeOut('fast');
													} else { 
														$("#filter").html('Filter');
														$("#progresloader").fadeOut('fast');
													}
												},
												error: function (error) {
													console.log(error);
													alert('error; ' + eval(error));
												},
												contentType: false,
												processData: false
											});
											
											// DATA ACH SEGMEN
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/filterAchSeg",
												type: "POST",
												data: formdata,
												beforeSend: function(){ 
													$("#filter").html('Filtering...');
													$("#progresloader").fadeIn('fast');
												},
												success: function(data) {
													if(data) {
														// alert(data);
														$("#filter").html('Filter');
														$("#viewachseg").html(data);
														$("#progresloader").fadeOut('fast');
													} else { 
														$("#filter").html('Filter');
														$("#progresloader").fadeOut('fast');
													}
												},
												error: function (error) {
													console.log(error);
													alert('error; ' + eval(error));
												},
												contentType: false,
												processData: false
											});
											
											// DATA AM ACH
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/filteramach",
												type: "POST",
												data: formdata,
												beforeSend: function(){ 
													$("#filter").html('Filtering...');
													$("#progresloader").fadeIn('fast');
												},
												success: function(data) {
													if(data) {
														// alert(data);
														$("#filter").html('Filter');
														$("#viewamach").html(data);
														$("#progresloader").fadeOut('fast');
													} else { 
														$("#filter").html('Filter');
														$("#progresloader").fadeOut('fast');
													}
												},
												error: function (error) {
													console.log(error);
													alert('error; ' + eval(error));
												},
												contentType: false,
												processData: false
											});
											
											<?PHP if($this->uri->uri_string() == 'v2') {  ?>
											// $.ajax({
												// url: "<?PHP echo base_url(); ?>core/filterlatest",
												// type: "POST",
												// data: formdata,
												// beforeSend: function(){ 
													// $("#filter").html('Filtering...');
													// $("#progresloader").fadeIn('fast');
												// },
												// success: function(data) {
													// if(data) {
														// $("#filter").html('Filter');
														// $("#viewlatesttrans").html(data);
														// $("#progresloader").fadeOut('fast');
													// } else { 
														// $("#filter").html('Filter');
														// $("#progresloader").fadeOut('fast');
													// }
												// },
												// error: function (error) {
													// console.log(error);
													// alert('error; ' + eval(error));
												// },
												// contentType: false,
												// processData: false
											// });
											<?PHP } ?>
											
											// DATA SISA LO
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/filtersisalo",
												type: "POST",
												data: formdata,
												beforeSend: function(){ 
													$("#filter").html('Filtering...');
													$("#progresloader").fadeIn('fast');
												},
												success: function(data) {
													if(data) {
														// alert(data);
														$("#filter").html('Filter');
														$("#viewsisalo").html(data);
														$("#progresloader").fadeOut('fast');
													} else { 
														$("#filter").html('Filter');
														$("#progresloader").fadeOut('fast');
													}
												},
												error: function (error) {
													// alert(data);
													console.log(error);
													alert('error; ' + eval(error));
												},
												contentType: false,
												processData: false
											});
											
											// DATA LATEST TRANSACTION
											$.ajax({
												url: "<?PHP echo base_url(); ?>core/filterlatesttrans",
												type: "POST",
												data: formdata,
												beforeSend: function(){ 
													$("#filter").html('Filtering...');
													$("#progresloader").fadeIn('fast');
												},
												success: function(data) {
													if(data) {
														$("#viewlatesttrans").fadeIn('fast');
														// alert(data);
														$("#filter").html('Filter');
														$("#viewlatesttrans").html(data);
														$("#progresloader").fadeOut('fast');
													} else { 
														$("#filter").html('Filter');
														$("#viewlatesttrans").fadeOut('fast');
														$("#progresloader").fadeOut('fast');
													}
												},
												error: function (error) {
													// alert(data);
													console.log(error);
													alert('error; ' + eval(error));
												},
												contentType: false,
												processData: false
											});
											return false;
										});
										
										// $('.select_map').select2();
									</script>
								</div>
							</div>
						</li>
					</ul>
				</li>
				<?PHP } ?>
				
				<!-- settings -->
				<li class="dropdown dropdown-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="pe-7s-settings"></i></a>
					<ul class="dropdown-menu">
						<li style="border-bottom: 1px dashed rgba(0,0,0,0.2); margin-bottom: 6px;">
							<div class="image col-xs-3" style="padding: 10px 5px 10px 10px;">
								<?PHP 
								if($dPict['picture']==''){
									$pic= 'default.png';
								}else{
									$pic= $dPict['picture'];
								}
								?>
								<img src="<?PHP echo base_url(); ?>images/user/<?PHP echo $pic; ?>" class="img-circle imgctop" alt="User Image">
							</div>
							<div class="info col-xs-8 padding0" style="text-align:left; padding: 10px 5px 10px 10px;">
								<p class="nameuser"><?PHP echo $userdata['name']; ?></p>
								<span style="font-size:10px;" class="text-success"><i class="fa fa-circle"></i> Online</span>
							</div>
							<div class="clearfix"></div>
						</li>
						<li><a href="<?php echo base_url(); ?>panel/userprofile"><i class="pe-7s-users"></i> User Profile</a></li>
						<!--li><a href="#"><i class="pe-7s-settings"></i> Settings</a></li-->
						<li><a href="#" data-toggle="modal" data-target="#logout"><i class="pe-7s-key"></i> Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</nav>
</header>

<div class="modal fade" id="logout" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document"style="margin-top: 15%; width: 320px;">
		<div class="modal-content bg-yellow">
			<div class="modal-body text-white">
				<h4 style="font-weight: bold; color: #1e1e1e; margin-top: 10px; margin-bottom: 10px;" class="modal-title">Are you sure you want to logout?</h4>
			</div>
			<div class="modal-footer text-center" style="border: none; text-align: center;">
				<button type="button" class="btn btn-default" style="width: 30%;" data-dismiss="modal">Cancel</button>
				<a href="<?PHP echo base_url(); ?>panel/logout"><button id="logout" type="button" style="width: 30%;" class="btn btn-danger">Yes</button></a>
			</div>
		</div>
	</div>
</div>
<div id="updatetocancel"></div>