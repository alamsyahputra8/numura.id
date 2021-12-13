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
$getType 	= $this->db->query("
			SELECT * from design_type where status='1' order by 1
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

						<?PHP echo getRoleInsert($akses,'addnewfac','Buat Karakter Baru');?>
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
									<h3 class="kt-portlet__head-title">Buat Karakter Baru</h3>
								</div>
								<div class="kt-portlet__head-toolbar">
								</div>
							</div>
							<div class="kt-portlet__body">
								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">Kategori</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<div class='input-group'>
												<select name="kat" class="form-control" id="kat" placeholder="Kategori">
													<?PHP foreach ($getType as $type) { ?>
													<option value="<?PHP echo $type['id']; ?>"><?PHP echo $type['label']; ?></option>
													<?PHP } ?>
												</select>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Nama</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<input type="text" name="nama" class="form-control" id="nama" placeholder="Nama">
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">Kode Karakter</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<div class='input-group'>
												<input type="text" name="kode" class="form-control" id="kode" placeholder="Kode Karakter">
											</div>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">File</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<input type="file" name="file" class="form-control" id="file" placeholder="File">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
							<button type="submit" id="saveinsert" class="btn btn-success">Simpan & Selesai</button>
						</div>
					</form>

				</div>
			</div>
		</div>
		<!-- END MODAL INSERT -->

		<div class="kt-portlet__body">
			<div id="bgdatakar" class="row">
				<div style="padding: 70px;"></div>
			</div>
			<!--end::Body-->

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
										<label class="col-form-label col-lg-2 col-sm-12">Stok Bayi</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<input type="hidden" name="ed_id" id="ed_id">
												<input type="number" name="ed_b_now" class="form-control" id="ed_b_now" placeholder="Stok Bayi" readonly>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Stok Anak</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<input type="number" name="ed_a_now" class="form-control" id="ed_a_now" placeholder="Stok Anak" readonly>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Tambah Stok Bayi</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<input type="number" name="ed_b_plus" class="form-control" id="ed_b_plus" placeholder="Tambah Stok Bayi" value="0">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Tambah Stok Anak</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<input type="number" name="ed_a_plus" class="form-control" id="ed_a_plus" placeholder="Tambah Stok Anak" value="0">
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