<?PHP
$userdata 	= $this->session->userdata('sesspwt'); 
$userid 	= $userdata['userid'];
$role 		= $userdata['id_role'];

$activepage	= $this->uri->uri_string();
$q 			= "select title_page,icon from menu where url='$activepage'";
$getMenu 	= $this->query->getDatabyQ($q);
$dataMenu	= array_shift($getMenu);

$getUser 	= $this->db->query("
			SELECT * from user order by name
			")->result_array();

$getTheme 	= $this->dbw->query("
			SELECT * FROM theme where status=1 order by id asc
			")->result_array();
?>
<!-- <script src="<?PHP echo base_url(); ?>assets/zxcvbn.js"></script> -->
<style>
.dataTables_wrapper table.dataTable.dtr-inline.collapsed > tbody > tr[role="row"] > td:first-child:before {
	top: 25px!important;
}
table.table-bordered.dataTable tbody td {
	vertical-align: top!important;
	font-size: 11px!important;
}
table.table-bordered.dataTable tbody th {
	vertical-align: top!important;
	font-size: 12px!important;
}
meter {
    /* Reset the default appearance */
    -webkit-appearance: none;
       -moz-appearance: none;
            appearance: none;
            
    margin: 0 auto 1em;
    width: 100%;
    height: .5em;
    
    /* Applicable only to Firefox */
    background: none;
    background-color: rgba(0,0,0,0.1);
}

meter::-webkit-meter-bar {
    background: none;
    background-color: rgba(0,0,0,0.1);
}

meter[value="1"]::-webkit-meter-optimum-value { background: red; }
meter[value="2"]::-webkit-meter-optimum-value { background: yellow; }
meter[value="3"]::-webkit-meter-optimum-value { background: orange; }
meter[value="4"]::-webkit-meter-optimum-value { background: green; }

meter[value="1"]::-moz-meter-bar { background: red; }
meter[value="2"]::-moz-meter-bar { background: yellow; }
meter[value="3"]::-moz-meter-bar { background: orange; }
meter[value="4"]::-moz-meter-bar { background: green; }

.feedback {
    color: #9ab;
    font-size: 90%;
    padding: 0 .25em;
    padding-left: 0em;
    margin-top: 1em;
}
</style>
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div id="gagalinsert" class="alert alert-warning alert-elevate kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-warning"></i></div>
		<div class="alert-text">
			<strong>Gagal!</strong> silahkan periksa kembali data Anda. Hubungi Admin jika butuh bantuan lebih lanjut.
		</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div id="suksesinsert" class="alert alert-success fade show kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-black"></i></div>
		<div class="alert-text">Berhasil!</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div id="suksesdelete" class="alert alert-secondary fade show kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
		<div class="alert-text">Data berhasil dihapus!</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
					<?PHP echo $dataMenu['icon']; ?>
				</span>
				<h3 class="kt-portlet__head-title">
					<?PHP echo $dataMenu['title_page']; ?>
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions text-right">
						<!--a href="#" class="btn btn-default btn-icon-sm">
							<i class="la la-download"></i> Export
						</a-->
						&nbsp;
						<?PHP 
						// echo getBtnAction($akses,'print','Print Pesanan','print','btn-default btnPrint','la la-print',$userid);

						$cekprint 		= getRoleAction($akses,'print','print',$userid); 
						if ($cekprint=='ada') {
						?>
						<div class="dropdown dropdown-inline">
							<button type="button" class="btn btn-default btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="la la-print"></i> Print
							</button>
							<div class="dropdown-menu dropdown-menu-right" style="width: 25rem;">
								<ul class="kt-nav">
									<li class="kt-nav__section kt-nav__section--first">
										<span class="kt-nav__section-text">Print Pesanan</span>
									</li>
									<li class="kt-nav__item" data-toggle="kt-tooltip" title="" data-html="true">
										<a href="<?PHP echo base_url(); ?>printpesanan/pending" class="kt-nav__link" id="" target="_blank">
											<i class="kt-nav__link-icon flaticon-clock-1"></i>
											<span class="kt-nav__link-text">Pesanan Perlu Diproses</span>
										</a>
									</li>
									<li class="kt-nav__item" data-toggle="kt-tooltip" title="" data-html="true">
										<a href="<?PHP echo base_url(); ?>printpesanan/proses" class="kt-nav__link" id="" target="_blank">
											<i class="kt-nav__link-icon flaticon2-checkmark"></i>
											<span class="kt-nav__link-text">Pesanan Sedang Diproses</span>
										</a>
									</li>
									<li class="kt-nav__item" data-toggle="kt-tooltip" title="" data-html="true">
										<a href="<?PHP echo base_url(); ?>printpesanan/send" class="kt-nav__link" id="" target="_blank">
											<i class="kt-nav__link-icon flaticon-paper-plane"></i>
											<span class="kt-nav__link-text">Pesanan Dikirim</span>
										</a>
									</li>
									<li class="kt-nav__section kt-nav__section--first">
										<span class="kt-nav__section-text">Print Item</span>
									</li>
									<li class="kt-nav__item" data-toggle="kt-tooltip" title="" data-html="true">
										<a href="<?PHP echo base_url(); ?>printcard/proses" class="kt-nav__link" id="" target="_blank">
											<i class="kt-nav__link-icon flaticon2-browser"></i>
											<span class="kt-nav__link-text">Thankyou Card</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
						<?PHP } ?>

						<?PHP echo getRoleInsert($akses,'addnewfac','Buat Digital Invitation');?>
					</div>
				</div>
			</div>
		</div>

		<!-- MODAL INSERT -->
		<div class="modal fade" id="addnewfac" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<!--div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle"></h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						</button>
					</div-->

					<form class="kt-form kt-form--label-right" id="forminsert" enctype="multipart/form-data" method="POST">
						<div class="modal-body kt-portlet kt-portlet--tabs" style="margin-bottom: 0px;">
							<div class="kt-portlet__head">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">Buat Pesanan Baru</h3>
								</div>
								<div class="kt-portlet__head-toolbar">
								</div>
							</div>
							<div class="kt-portlet__body">
								<div class="tab-content">
									<ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-primary" role="tablist" style="margin-top: -15px!important; margin-bottom: 20px; border-bottom: 1px solid #f1f1f1!important;">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#general" role="tab">
												<i class="flaticon-time-1"></i> General Information
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#mempelai" role="tab">
												<i class="fa fa-heart"></i> Data Pernikahan
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#gallery" role="tab">
												<i class="fa fa-image"></i> Gallery
											</a>
										</li>
									</ul>
									
									<div class="tab-pane active" id="general" role="tabpanel">
										<div class="form-group row">
											<label for="orderid" class="col-lg-2 col-sm-12 col-form-label">OrderID</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<?PHP
													$nowdate	= date('Ym');
													$nowoid		= date('Ymd');
													$qoid 		= $this->dbw->query("
																SELECT count(*)+1 nexid FROM person_order where orderid like '$nowdate%'
																")->result_array();
													$goid 		= array_shift($qoid);
													$invID		= $goid['nexid'];
													$noid 		= str_pad($invID, 4, '0', STR_PAD_LEFT);
													$oid 		= $nowoid.$noid;
													?>
													<input type="text" name="orderid" class="form-control dp" id="orderid" placeholder="OrderID" value="<?PHP echo $oid; ?>" readonly required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="theme" class="col-lg-2 col-sm-12 col-form-label">Theme</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<select name="theme" class="form-control select2norm" id="theme" placeholder="Theme" style="width:100%;" required>
														<?PHP
														foreach ($getTheme as $th) {
														?>
														<option value="<?PHP echo $th['id']; ?>"><?PHP echo $th['name']; ?></option>
														<?PHP } ?>
													</select>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="link" class="col-lg-2 col-sm-12 col-form-label">Link</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="link" class="form-control" id="link" placeholder="Link" required>
												</div>
												<div id="alertlink" style="display: none;" class="text-danger"><i>* Link sudah terpakai. Mohon coba menggunakan link yang lain.</i></div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="wddatetext" class="col-lg-2 col-sm-12 col-form-label">Wedding Date Text</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="wddatetext" class="form-control" id="wddatetext" placeholder="Ex: Senin, 01 Januari 2021" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="wddate" class="col-lg-2 col-sm-12 col-form-label">Wedding Date Number</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="wddate" class="form-control dpt" id="wddate" placeholder="YYYY-MM-DD HH:II:SS" required>
												</div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="modlive" class="col-lg-2 col-sm-12 col-form-label">Live Info</label>
											<div class="col-lg-10 col-sm-12">
												<label class="kt-checkbox kt-checkbox--success">
													<input type="checkbox" checked name="modlive" value="1" id="modlive"> YES
													<span></span>
												</label>
											</div>
										</div>

										<div id="bgiglive">
											<div class="form-group row">
												<label for="acciglive" class="col-lg-2 col-sm-12 col-form-label">Account IG Live</label>
												<div class="col-lg-10 col-sm-12">
													<div class='input-group'>
														<input type="text" name="acciglive" class="form-control" id="acciglive" placeholder="Account IG Live">
													</div>
												</div>
											</div>

											<div class="form-group row">
												<label for="livetime" class="col-lg-2 col-sm-12 col-form-label">Time Live</label>
												<div class="col-lg-10 col-sm-12">
													<div class='input-group'>
														<input type="text" name="livetime" class="form-control" id="livetime" placeholder="Ex: 01 Januari 2021 (08:00 - 10:00 WIB)">
													</div>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="rsvpno" class="col-lg-2 col-sm-12 col-form-label">RSVP Number</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="rsvpno" class="form-control" id="rsvpno" placeholder="RSVP Number" required>
												</div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="bankampau" class="col-lg-2 col-sm-12 col-form-label">Bank Amplop</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="bankampau" class="form-control" id="bankampau" placeholder="Bank Amplop" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="banknorek" class="col-lg-2 col-sm-12 col-form-label">No. Rekening</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="banknorek" class="form-control" id="banknorek" placeholder="No. Rekening" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="bankan" class="col-lg-2 col-sm-12 col-form-label">Atas Nama</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="bankan" class="form-control" id="bankan" placeholder="Atas Nama" required>
												</div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="giftnama" class="col-lg-2 col-sm-12 col-form-label">Nama Penerima</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="giftnama" class="form-control" id="giftnama" placeholder="Nama Penerima" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="gifthp" class="col-lg-2 col-sm-12 col-form-label">HP Penerima</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="gifthp" class="form-control" id="gifthp" placeholder="HP Penerima" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="giftalamat" class="col-lg-2 col-sm-12 col-form-label">Alamat Penerima</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<textarea name="giftalamat" class="form-control" id="giftalamat" placeholder="Alamat Penerima" required></textarea>
												</div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="backsound" class="col-lg-2 col-sm-12 col-form-label">Backsound</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="file" name="backsound" class="form-control" id="backsound" placeholder="Backsound" required accept=".mp3,audio/*">
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="prokes" class="col-lg-2 col-sm-12 col-form-label">Text Prokes</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="prokes" class="form-control" id="prokes" placeholder="Text Prokes" required value="Jangan ragu untuk datang, kami sudah berkoordinasi dengan semua pihak terkait pencegahan penularan COVID-19. Acara kami akan mengikuti segala prosedur protokol kesehatan untuk mencegah penularan COVID-19. So, don't be panic, we look forward to seeing you there!">
												</div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="price" class="col-lg-2 col-sm-12 col-form-label">Price</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="price" class="form-control" id="price" placeholder="Price" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="status" class="col-lg-2 col-sm-12 col-form-label">Status</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<select name="status" class="form-control" id="status" placeholder="Status" required>
														<option value="0">NOT PAID</option>
														<option value="1">PAID</option>
													</select>
												</div>
											</div>
										</div>
									</div>

									<div class="tab-pane" id="mempelai" role="tabpanel">
										<div class="form-group row">
											<label for="modig" class="col-lg-2 col-sm-12 col-form-label">Use Instagram</label>
											<div class="col-lg-10 col-sm-12">
												<label class="kt-checkbox kt-checkbox--success">
													<input type="checkbox" checked name="modig" value="1" id="modig"> YES
													<span></span>
												</label>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="king" class="col-lg-2 col-sm-12 col-form-label">Mempelai Pria</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="king" class="form-control" id="king" placeholder="Nama Lengkap Pria" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="nickm" class="col-lg-2 col-sm-12 col-form-label">Nama Panggilan</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="nickm" class="form-control" id="nickm" placeholder="Nama Panggilan Pria" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="sonof" class="col-lg-2 col-sm-12 col-form-label">Putra dari</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="sonof" class="form-control" id="sonof" placeholder="Ex: Bpk. John Doe & Ibu Emily" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="pictm" class="col-lg-2 col-sm-12 col-form-label">Profile Picture</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="file" name="pictm" class="form-control" id="pictm" placeholder="Profile Picture" required accept="image/*">
												</div>
											</div>
										</div>

										<div class="form-group row" id="bgigm">
											<label for="igm" class="col-lg-2 col-sm-12 col-form-label">Instagram Account</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="igm" class="form-control" id="igm" placeholder="Instagram Account">
												</div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="queen" class="col-lg-2 col-sm-12 col-form-label">Mempelai Wanita</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="queen" class="form-control" id="queen" placeholder="Nama Lengkap Wanita" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="nickf" class="col-lg-2 col-sm-12 col-form-label">Nama Panggilan</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="nickf" class="form-control" id="nickf" placeholder="Nama Panggilan Wanita" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="daughterof" class="col-lg-2 col-sm-12 col-form-label">Putri dari</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="daughterof" class="form-control" id="daughterof" placeholder="Ex: Bpk. John Doe & Ibu Emily" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="pictf" class="col-lg-2 col-sm-12 col-form-label">Profile Picture</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="file" name="pictf" class="form-control" id="pictf" placeholder="Profile Picture" required accept="image/*">
												</div>
											</div>
										</div>

										<div class="form-group row" id="bgigf">
											<label for="igf" class="col-lg-2 col-sm-12 col-form-label">Instagram Account</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="igf" class="form-control" id="igf" placeholder="Instagram Account">
												</div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="akaddate" class="col-lg-2 col-sm-12 col-form-label">Akad</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="akaddate" class="form-control" id="akaddate" placeholder="Ex: Senin, 01 Januari 2021" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="akadstart" class="col-lg-2 col-sm-12 col-form-label">Mulai dari</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="akadstart" class="form-control" id="akadstart" placeholder="Ex: 08:00" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="akadto" class="col-lg-2 col-sm-12 col-form-label">Sampai dengan</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="akadto" class="form-control" id="akadto" placeholder="Ex: 10:00" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="akadat" class="col-lg-2 col-sm-12 col-form-label">Lokasi Akad</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<textarea name="akadat" class="form-control" id="akadat" placeholder="Lokasi Akad" required></textarea>
												</div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="resepsidate" class="col-lg-2 col-sm-12 col-form-label">Resepsi</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="resepsidate" class="form-control" id="resepsidate" placeholder="Ex: Senin, 01 Januari 2021" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="resepsistart" class="col-lg-2 col-sm-12 col-form-label">Mulai dari</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="resepsistart" class="form-control" id="resepsistart" placeholder="Ex: 11:00" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="resepsito" class="col-lg-2 col-sm-12 col-form-label">Sampai dengan</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="resepsito" class="form-control" id="resepsito" placeholder="Ex: 14:00" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="resepsiat" class="col-lg-2 col-sm-12 col-form-label">Lokasi Resepsi</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<textarea name="resepsiat" class="form-control" id="resepsiat" placeholder="Lokasi Resepsi" required></textarea>
												</div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="embedmap" class="col-lg-2 col-sm-12 col-form-label">Embed Map</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<textarea name="embedmap" class="form-control" id="embedmap" placeholder="Embed Map" required></textarea>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="linkmap" class="col-lg-2 col-sm-12 col-form-label">Link Map</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="linkmap" class="form-control" id="linkmap" placeholder="Link Map" required>
												</div>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="quotes" class="col-lg-2 col-sm-12 col-form-label">Quotes</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<textarea name="quotes" class="form-control" id="quotes" placeholder="Quotes" required></textarea>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="qby" class="col-lg-2 col-sm-12 col-form-label">Quotes by</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="text" name="qby" class="form-control" id="qby" placeholder="Quotes by" required>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label for="qbg" class="col-lg-2 col-sm-12 col-form-label">Background Quotes</label>
											<div class="col-lg-10 col-sm-12">
												<div class='input-group'>
													<input type="file" name="qbg" class="form-control" id="qbg" placeholder="Background Quotes" required accept="image/*">
												</div>
											</div>
										</div>
									</div>

									<div class="tab-pane" id="gallery" role="tabpanel">
										<div class="form-group row">
											<label for="banner" class="col-lg-2 col-sm-12 col-form-label">Banner</label>
											<div class="col-lg-10 col-sm-12">
												<div id="bgbanner">
													<div class="input-group">
									                    <input type="file" class="form-control" name="banner[]" id="banner" accept="image/*">
									                    <a href="#" class="col-lg-1">
									                    </a>
									                </div>
												</div>
												<br>
												<button type="button" class="btn btn-sm btn-default btnAddBanner">
													<i class="flaticon flaticon-plus"></i> Tambah Banner
												</button>
											</div>
										</div>
										<div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div>

										<div class="form-group row">
											<label for="gallery" class="col-lg-2 col-sm-12 col-form-label">Gallery</label>
											<div class="col-lg-10 col-sm-12">
												<div id="bggallery">
													<div class="input-group">
									                    <input type="file" class="form-control" name="gallery[]" id="gallery" accept="image/*">
									                    <a href="#" class="col-lg-1">
									                    </a>
									                </div>
												</div>
												<br>
												<button type="button" class="btn btn-sm btn-default btnAddGallery">
													<i class="flaticon flaticon-plus"></i> Tambah Gallery
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
							<!-- <button type="submit" id="saveandcreate" class="btn btn-primary">Simpan & Buat Pembayaran Baru</button> -->
							<button type="submit" id="saveinsert" class="btn btn-success" disabled>Simpan & Selesai</button>
						</div>
					</form>

				</div>
			</div>
		</div>
		<!-- END MODAL INSERT -->

		<div class="kt-portlet__body">
			<div class="tab-content">
				<ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-primary" role="tablist" style="margin-top: -15px!important;">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#dataorder" role="tab">
							<i class="flaticon-time-1"></i> List Order Digital Invitation
						</a>
					</li>
				</ul>
				<div class="tab-pane active" id="dataorder" role="tabpanel">
					<!--begin: Datatable -->
					<table class="table table-striped- table-bordered table-hover table-checkable" id="tabledata">
						<thead>
							<tr>
								<th>ORDERID</th>
								<th>LINK</th>
								<th>PRIA</th>
								<th>WANITA</th>
								<th>TANGGAL</th>
								<th>CREATEDDATE</th>
								<th>STATUS</th>
								<th>ACTIONS</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>ORDERID</th>
								<th>LINK</th>
								<th>PRIA</th>
								<th>WANITA</th>
								<th>TANGGAL</th>
								<th>CREATEDDATE</th>
								<th>STATUS</th>
								<th>ACTIONS</th>
							</tr>
						</tfoot>
					</table>
					<!--end: Datatable -->
				</div>
			</div>

			<!-- MODAL UPDATE -->
			<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<!--div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Update Data : <b id="nameroles"></b></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							</button>
						</div-->

						<form class="kt-form kt-form--label-left" id="formupdate" enctype="multipart/form-data" method="POST">
							<div class="modal-body kt-portlet kt-portlet--tabs" style="margin-bottom: 0px;">
								<div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">Update Data : <b id="namedata"></b></h3>
									</div>
									<div class="kt-portlet__head-toolbar">
									</div>
								</div>
								<div class="kt-portlet__body">

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Tgl. Transaksi</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<input type="hidden" name="ed_id" id="ed_id">
												<input type="text" name="ed_tgl" class="form-control dp" id="ed_tgl" placeholder="Tgl. Transaksi">
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Keterangan</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<div class='input-group'>
													<input type="text" name="ed_label" class="form-control" id="ed_label" placeholder="Keterangan">
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="kt-separator kt-separator--space-sm kt-separator--border-dashed"></div> -->
									
									<div id="bgdetailstok"></div>

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Total Barang</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<div class='input-group'>
													<input type="number" name="ed_totalpcs" class="form-control" id="ed_totalpcs" placeholder="0" readonly>
												</div>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Total Pembayaran</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<div class='input-group'>
													<input type="number" name="ed_total" class="form-control" id="ed_total" placeholder="0" readonly>
												</div>
											</div>
										</div>
									</div>

								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" id="saveupdate" class="btn btn-primary">Save</button>
							</div>
						</form>

					</div>
				</div>
			</div>
			<!-- END MODAL UPDATE -->

			<!-- MODAL DELETE -->
			<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Are you sure?</h2>
							</div>
							<div class="swal2-content">
								<div id="swal2-content" style="display: block;">You won't be able to revert this!</div>
							</div>
							<div class="swal2-actions" style="display: flex;">
								<form method="POST">
								<input type="hidden" name="iddel" id="iddel" value="">
								<center>
								<button type="button" id="deleteBtn" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
									Yes, delete it!
								</button>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Cancel</button>
								</center>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL DELETE -->

			<!-- MODAL PAYMENT -->
			<div class="modal fade" id="bayar" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Are you sure?</h2>
							</div>
							<div class="swal2-content">
								<div id="swal2-content" style="display: block;">You won't be able to revert this!</div>
							</div>
							<div class="swal2-actions" style="display: flex;">
								<form id="formbayar" method="POST">
								<input type="hidden" name="idorder" id="idorder" value="">
								<input type="text" name="tglbayar" class="form-control dp" id="tglbayar" placeholder="Tgl. Bayar">
								<input type="number" class="form-control" name="jmlbayar" id="jmlbayar" placeholder="Jumlah Bayar">
								<center>
								<button type="submit" id="paymentBtn" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214); float:left;">
									Bayar
								</button>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Cancel</button>
								</center>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL PAYMENT -->

			<!-- MODAL FINISH -->
			<div class="modal fade" id="finish" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Are you sure?</h2>
							</div>
							<div class="swal2-content">
								<div id="swal2-content" style="display: block;">You won't be able to revert this!</div>
							</div>
							<div class="swal2-actions" style="display: flex;">
								<form id="formfinish" method="POST">
								<input type="hidden" name="idorderf" id="idorderf" value="">
								<center>
								<button type="submit" id="finishBtn" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
									Selesai
								</button>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Cancel</button>
								</center>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL FINISH -->

			<!-- MODAL PRINT -->
			<div class="modal fade" id="print" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Print data Pesanan?</h2>
							</div>
							<div class="swal2-content">
								<!-- <div id="swal2-content" style="display: block;">You won't be able to revert this!</div> -->
							</div>
							<div class="swal2-actions" style="display: flex;">
								<center>
								<a href="<?PHP echo base_url(); ?>printpesanan/pending" target="_blank" type="button" id="printfile" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
									Ya, print pesanan!
								</a>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Cancel</button>
								</center>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL PRINT -->

			<!-- MODAL APPROVE -->
			<div class="modal fade" id="proses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Proses pesanan sekarang?</h2>
							</div>
							<div class="swal2-content">
								<div id="swal2-content" style="display: block;">You won't be able to revert this!</div>
							</div>
							<div class="swal2-actions" style="display: flex;">
								<form method="POST">
								<input type="hidden" name="idapp" id="idapp" value="">
								<center>
								<button type="button" id="prosesBtn" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
									Ya, proses sekarang!
								</button>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Tidak</button>
								</center>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL APPROVE -->
		</div>
	</div>
</div>
<!-- end:: Content -->