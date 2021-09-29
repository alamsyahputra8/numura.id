<?PHP
$userdata 	= $this->session->userdata('sesspwt'); 
$userid 	= $userdata['userid'];
$myname 	= $userdata['name'];
$myphone 	= $userdata['phone'];
$role 		= $userdata['id_role'];

$activepage	= $this->uri->uri_string();
$q 			= "select title_page,icon from menu where url='$activepage'";
$getMenu 	= $this->query->getDatabyQ($q);
$dataMenu	= array_shift($getMenu);

$getSize 	= $this->db->query("
			SELECT * from size order by sort
			")->result_array();
$getColor 	= $this->db->query("
			SELECT * from color order by label
			")->result_array();
$getChar 	= $this->db->query("
			SELECT * from karakter order by kode
			")->result_array();

$getEks 	= $this->db->query("
			SELECT * from ekspedisi order by 1
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
					<div class="kt-portlet__head-actions">
						<!--a href="#" class="btn btn-default btn-icon-sm">
							<i class="la la-download"></i> Export
						</a-->
						&nbsp;
						<?PHP echo getBtnAction($akses,'print','Print Pengiriman','print','btn-default btnPrint','la la-print',$userid); ?>
						<?PHP echo getRoleInsert($akses,'addnewfac','Buat Pengiriman Baru');?>
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
									<h3 class="kt-portlet__head-title">Buat Pengiriman</h3>
								</div>
								<div class="kt-portlet__head-toolbar">
								</div>
							</div>
							<div class="kt-portlet__body">
								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Nama Pengirim</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<input type="text" name="pengirim" class="form-control" id="pengirim" value="<?PHP echo $myname; ?>" placeholder="Nama Pengirim">
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">No. HP</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<input type="text" name="phonepengirim" class="form-control" id="phonepengirim" value="<?PHP echo $myphone; ?>" placeholder="No. HP Pengirim">
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Nama Penerima</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<input type="text" name="name" class="form-control" id="name" placeholder="Nama Penerima">
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">No. HP</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<input type="text" name="phone" class="form-control" id="phone" placeholder="No. HP">
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">Alamat Lengkap</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<textarea name="alamat" class="form-control" id="alamat" placeholder="Alamat"></textarea>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">Ekspedisi</label>
									<div class="col-lg-10 col-sm-12">
										<div class='input-group'>
											<select name="ekspedisi" class="form-control" id="ekspedisi" placeholder="Ekspedisi">
												<?PHP foreach($getEks as $eks) { ?>
												<option value="<?PHP echo $eks['id']; ?>"><?PHP echo $eks['nama_ekspedisi']; ?></option>
												<?PHP } ?>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label for="role" class="col-lg-2 col-sm-12 col-form-label">Pilih Pesanan</label>
									<div class="col-lg-10 col-sm-12">
										<i><b>Untuk pengiriman kaos <span class="text-danger">LENGAN PANJANG</span>, harap hubungi Admin. Karena ada perbedaan jadwal produksi kaos <span class="text-danger">LENGAN PANJANG</span> untuk sementara waktu. Terimakasih.</b></i><br><br>
										<div id="bgdetailpesanan">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
							<button type="submit" id="saveandcreate" class="btn btn-primary">Simpan & Buat Pengiriman Baru</button>
							<button type="submit" id="saveinsert" class="btn btn-success">Simpan & Selesai</button>
						</div>
					</form>

				</div>
			</div>
		</div>
		<!-- END MODAL INSERT -->

		<div class="kt-portlet__body">
			<div>
				Perhatian! Jika ada kesalahan pada proses input data pengiriman, mohon hubungi Admin untuk info lebih lanjut, atau bisa klik
				<b><a href="https://api.whatsapp.com/send?phone=6281214290987&text=Hallo%20Numura%20.%20Saya%20melakukan%20kesalahan%20pada%20saat%20proses%20input%20pengiriman.%20Mohon%20bantuan%20nya%20untuk%20memperbaiki.%20Terimakasih." target="_blank">disini</a></b>.
			</div>
			<div class="kt-separator kt-separator--space-lg kt-separator--border-dashed"></div>
			
			<div class="tab-content">
				<ul class="nav nav-tabs  nav-tabs-line nav-tabs-line-primary" role="tablist" style="margin-top: -15px!important;">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#pending" role="tab"><i class="flaticon-time-1"></i> Menunggu Proses</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#onproses" role="tab"><i class="flaticon2-delivery-package"></i> Sedang Diproses</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#send" role="tab"><i class="la la-truck"></i> Sudah Dikirim</a>
					</li>
				</ul>
				<div class="tab-pane active" id="pending" role="tabpanel">
					<!--begin: Datatable -->
					<table class="table table-striped- table-bordered table-hover table-checkable" id="tabledata">
						<thead>
							<tr>
								<th>NAMA PENERIMA</th>
								<th>HP. PENERIMA</th>
								<th>ALAMAT</th>
								<th>ONGKIR</th>
								<th>STATUS</th>
								<th>TGL. DIBUAT</th>
								<th>JUMLAH</th>
								<th>EKSPEDISI</th>
								<?PHP
								if ($role==1) {
								?>
								<th>RESELLER</th>
								<?PHP } ?>
								<th>ACTIONS</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>NAMA PENERIMA</th>
								<th>HP. PENERIMA</th>
								<th>ALAMAT</th>
								<th>ONGKIR</th>
								<th>STATUS</th>
								<th>TGL. DIBUAT</th>
								<th>JML PESANAN</th>
								<th>EKSPEDISI</th>
								<?PHP
								if ($role==1) {
								?>
								<th>RESELLER</th>
								<?PHP } ?>
								<th>ACTIONS</th>
							</tr>
						</tfoot>
					</table>
					<!--end: Datatable -->
				</div>

				<div class="tab-pane active" id="onproses" role="tabpanel">
					<!--begin: Datatable -->
					<table class="table table-striped- table-bordered table-hover table-checkable" id="tabledataproses">
						<thead>
							<tr>
								<th>NAMA PENERIMA</th>
								<th>HP. PENERIMA</th>
								<th>ALAMAT</th>
								<th>ONGKIR</th>
								<th>STATUS</th>
								<th>TGL. DIBUAT</th>
								<th>JUMLAH</th>
								<th>EKSPEDISI</th>
								<?PHP
								if ($role==1) {
								?>
								<th>RESELLER</th>
								<?PHP } ?>
								<th>ACTIONS</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>NAMA PENERIMA</th>
								<th>HP. PENERIMA</th>
								<th>ALAMAT</th>
								<th>ONGKIR</th>
								<th>STATUS</th>
								<th>TGL. DIBUAT</th>
								<th>JML PESANAN</th>
								<th>EKSPEDISI</th>
								<?PHP
								if ($role==1) {
								?>
								<th>RESELLER</th>
								<?PHP } ?>
								<th>ACTIONS</th>
							</tr>
						</tfoot>
					</table>
					<!--end: Datatable -->
				</div>

				<div class="tab-pane active" id="send" role="tabpanel">
					<!--begin: Datatable -->
					<table class="table table-striped- table-bordered table-hover table-checkable" id="tabledatasend">
						<thead>
							<tr>
								<th>NAMA PENERIMA</th>
								<th>HP. PENERIMA</th>
								<th>ALAMAT</th>
								<th>ONGKIR</th>
								<th>STATUS</th>
								<th>TGL. DIBUAT</th>
								<th>JUMLAH</th>
								<th>EKSPEDISI</th>
								<?PHP
								if ($role==1) {
								?>
								<th>RESELLER</th>
								<?PHP } ?>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>NAMA PENERIMA</th>
								<th>HP. PENERIMA</th>
								<th>ALAMAT</th>
								<th>ONGKIR</th>
								<th>STATUS</th>
								<th>TGL. DIBUAT</th>
								<th>JML PESANAN</th>
								<th>EKSPEDISI</th>
								<?PHP
								if ($role==1) {
								?>
								<th>RESELLER</th>
								<?PHP } ?>
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
										<label class="col-form-label col-lg-2 col-sm-12">Nama Custom</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<input type="hidden" name="ed_id" id="ed_id">
												<input type="text" name="ed_name" class="form-control" id="ed_name" placeholder="Nama Custom">
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Ukuran</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<select name="ed_ukuran" class="form-control" id="ed_ukuran" placeholder="Ukuran">
													<?PHP foreach ($getSize as $size) { ?>
														<option value="<?PHP echo $size['id_size']; ?>"><?PHP echo $size['label']; ?></option>
													<?PHP } ?>
												</select>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Warna</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<select name="ed_warna" class="form-control" id="ed_warna" placeholder="Warna" style="width: 100%;">
													<?PHP foreach ($getColor as $color) { ?>
														<option value="<?PHP echo $color['id']; ?>"><?PHP echo $color['label']; ?></option>
													<?PHP } ?>
												</select>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label for="role" class="col-lg-2 col-sm-12 col-form-label">Karakter</label>
										<div class="col-lg-10 col-sm-12">
											<div class='input-group'>
												<select name="ed_karakter" class="form-control" id="ed_karakter" placeholder="Karakter" style="width: 100%;">
													<?PHP foreach ($getChar as $char) { ?>
														<option value="<?PHP echo $char['id_karakter']; ?>"><?PHP echo $char['kode']; ?> - <?PHP echo $char['nama']; ?></option>
													<?PHP } ?>
												</select>
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
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Print data Pengiriman?</h2>
							</div>
							<div class="swal2-content">
								<!-- <div id="swal2-content" style="display: block;">You won't be able to revert this!</div> -->
							</div>
							<div class="swal2-actions" style="display: flex;">
								<center>
								<a href="<?PHP echo base_url(); ?>printpengiriman/proses" target="_blank" type="button" id="printfile" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
									Ya, print pengiriman!
								</a>
								<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Cancel</button>
								</center>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END MODAL PRINT -->

			<!-- MODAL SEND -->
			<div class="modal fade" id="proses" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
							<div class="swal2-header">
								<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
								<h2 class="swal2-title" id="swal2-title" style="display: flex;">Proses pengiriman sekarang?</h2>
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
			<!-- END MODAL SEND -->

			<!-- MODAL ONGKIR -->
			<div class="modal fade" id="ongkir" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-sm" role="document">
					<div class="modal-content">
						<form id="formongkir" method="POST">
							<div aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-popup swal2-modal swal2-show" style="display: flex;">
								<div class="swal2-header">
									<div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"></div>
									<h2 class="swal2-title" id="swal2-title" style="display: flex;">Masukan Ongkir :</h2>
								</div>
								<div class="swal2-content">
									<input type="hidden" name="idpengongkir" id="idpengongkir" value="">
									<div id="swal2-content" style="display: block;">
										<input type="text" class="form-control" name="ongkirval" id="ongkirval">
									</div>
								</div>
								<div class="swal2-actions" style="display: flex;">
									<center>
									<button type="button" id="btnsetOngkir" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
										Update Ongkir
									</button>
									<button type="button" class="swal2-cancel swal2-styled" data-dismiss="modal">Batal</button>
									</center>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<!-- END MODAL ONGKIR -->
		</div>
	</div>
</div>
<!-- end:: Content -->