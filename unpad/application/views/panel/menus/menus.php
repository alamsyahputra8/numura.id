<?PHP
$q 				= "
				select * from menu_site order by sort asc
				";
$getDataMenu	= $this->query->getDatabyQ($q);
$cekAbout 		= $this->query->getNumRowsbyQ("select * from menu_site where style='about'")->num_rows();
$cekContact 	= $this->query->getNumRowsbyQ("select * from menu_site where style='contact'")->num_rows();
$cekEvent 		= $this->query->getNumRowsbyQ("select * from menu_site where style='evet'")->num_rows();
?>
 
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

	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
				</span>
				<h3 class="kt-portlet__head-title">
				</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						<!--a href="#" class="btn btn-default btn-icon-sm">
							<i class="la la-download"></i> Export
						</a-->
						&nbsp;
						<?PHP echo getRoleInsert($akses,'addnewfac','Add New Menu');?>
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

					<form class="kt-form kt-form--label-right" id="forminsert" enctype="multipart/form-data">
						<div class="modal-body kt-portlet kt-portlet--tabs" style="margin-bottom: 0px;">
							<div class="kt-portlet__head">
								<div class="kt-portlet__head-label">
									<h3 class="kt-portlet__head-title">Add New Menu</h3>
								</div>
							</div>
							<div class="kt-portlet__body">
								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Kategori Website *</label>
									<div class="col-lg-8 col-md-9 col-sm-12">
										<div class='input-group'>
											<select name="kategori_website" class="form-control" id="kategori_website" placeholder="Jenis Menu" readonly>
												<option value="">-- Pilih Kategori --</option>
												<option value="1">Web Fakultas</option>
												<option value="2">Web Prodi S1 & S2</option> 
												<option value="3">Web Alumni & Mitra</option> 
											</select>
										</div>
									</div> 
								</div>
								
								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Type Content *</label>
									<div class="col-lg-8 col-md-9 col-sm-12">
										<div class='input-group'>
											<select name="menutype" class="form-control" id="menutype" placeholder="Menu Type">
												<option value="">-- Pilih Type Content --</option>
												<?PHP if ($cekAbout<1) { ?><option value="about">About</option><?PHP } ?> 
												<?PHP if ($cekContact<1) { ?><option value="contact">Contact</option><?PHP } ?>
												<!-- <?PHP // if ($cekEvent<1) { ?><option value="Event">Download</option><?PHP //} ?> -->
												<option value="blog">Blog</option>
												<option value="basic">Basic Content</option>
												<!-- <option value="gallery">Gallery</option> -->
												<!--option value="document">Document</option-->
												<option value="services">Services</option>
												<option value="link">Link</option>
												<!-- <option value="works">Works</option> -->
											</select>
										</div>
									</div>
								</div>
								
								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Nama Menu *</label>
									<div class="col-lg-4 col-md-9 col-sm-12"> 
										<div class='input-group'> 
											<input type="text" name="menu" class="form-control" id="menu" placeholder="Nama Menu Bahasa Indonesia">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
										</div>
									</div>
									<div class="col-lg-4 col-md-9 col-sm-12"> 
										<div class='input-group'> 
											<input type="text" name="menu_en" class="form-control" id="menu_en" placeholder="Nama Menu Bahasa Inggris">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Deskripsi</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="desc" class="form-control" id="desc" placeholder="Deskripsi Bahasa Indonesia">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
										</div>
									</div>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="desc_en" class="form-control" id="desc_en" placeholder="Deskripsi Bahasa Inggris">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
										</div>
									</div>
								</div>

								<div class="form-group row content-type">
									<label class="col-form-label col-lg-2 col-sm-12">Background *</label>
									<div class="col-lg-8 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="file" name="pict" class="form-control" id="pict" placeholder="Background">
										</div>
										<span class="form-text text-muted">Untuk tampilan lebih maksimal, gambar disarankan dengan bentuk kotak (1920 x 1280px)</span>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Parent Menu *</label>
									<div class="col-lg-8 col-md-9 col-sm-12">
										<div class='input-group'>
											<select name="parent" class="form-control select2norm" id="parent" placeholder="Parent Menu" style="width: 100%;">
												<option value="">-- Choose Parent --</option>
												<option value="0">Set as Parent</option>
												<?PHP 
												foreach ($getDataMenu as $data) { 
													if ($data['style']=='services' or $data['style']=='works') {
														$styletype	= ' ('.$data['style'].')';
													} else {
														$styletype	= '';
													}
												?>
												<option value="<?PHP echo $data['id_menu']; ?>"><?PHP echo $data['menu']; ?><?PHP echo $styletype; ?></option>
												<?PHP } ?>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Link</label>
									<div class="col-lg-8 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="link" class="form-control" id="link" placeholder="Link">
										</div>
										<span class="form-text text-muted">Mohon isi dengan huruf kecil dan tanpa spasi ataupun spesial karakter</span>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Add Data After *</label>
									<div class="col-lg-8 col-md-9 col-sm-12">
										<div class='input-group'>
											<select name="sort" class="form-control" id="sort" placeholder="Parent Menu">
												<option value="">-- Choose Menu --</option>
											</select>
										</div>
										<span class="form-text text-muted">Choose Parent Menu First</span>
									</div>
								</div>
								
								
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" id="saveinsert" class="btn btn-primary">Save</button>
						</div>
					</form>

				</div>
			</div>
		</div>
		<!-- END MODAL INSERT -->

		<div class="kt-portlet__body">

			<!--begin: Datatable -->
			<table class="table table-striped- table-bordered table-hover table-checkable" id="tabledata">
				<thead>
					<tr> 
						<th>KATEGORI</th>
						<th>MENU</th> 
						<th>PARENT</th>
						<th>SORT</th>
						<th>LINK</th>
						<th>MENU TYPE</th>
						<th>UPDATE BY</th>
						<th>LAST UPDATE</th>
						<th>ACTIONS</th>
					</tr>
				</thead>
				<tfoot>
					<tr> 
						<th>KATEGORI</th>
						<th>MENU</th> 
						<th>PARENT</th>
						<th>SORT</th>
						<th>LINK</th>
						<th>MENU TYPE</th>
						<th>UPDATE BY</th>
						<th>LAST UPDATE</th>
						<th>ACTIONS</th>
					</tr>
				</tfoot>
			</table>
			<!--end: Datatable -->

			<!-- MODAL UPDATE -->
			<div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<!--div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Update Data : <b id="nameroles"></b></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							</button>
						</div-->

						<form class="kt-form kt-form--label-left" id="formupdate" enctype="multipart/form-data">
							<div class="modal-body kt-portlet kt-portlet--tabs" style="margin-bottom: 0px;">
								<div class="kt-portlet__head">
									<div class="kt-portlet__head-label">
										<h3 class="kt-portlet__head-title">Update Data : <b id="namedata"></b></h3>
									</div>
								</div>
								
								<div class="kt-portlet__body">
									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Kategori Website *</label>
										<div class="col-lg-8 col-md-9 col-sm-12">
											<div class='input-group'>
												<select name="ed_kategori_website" class="form-control" id="ed_kategori_website" placeholder="Jenis Menu" readonly>
													<option value="">-- Pilih Kategori --</option>
													<option value="1">Web Fakultas</option>
													<option value="2">Web Prodi S1 & S2</option> 
													<option value="3">Web Alumni & Mitra</option> 
												</select>
											</div>
										</div> 
									</div>
									
									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Menu Type *</label>
										<div class="col-lg-8 col-md-9 col-sm-12">
											<div class='input-group'>
												<select name="ed_menutype" class="form-control" id="ed_menutype" placeholder="Menu Type" readonly>
													<option value="">-- Pilih Type Content --</option>
													<option value="about">About</option>
													<option value="contact">Contact</option>
													<option value="blog">Blog</option>
													<!-- <option value="event">Download</option> -->
													<option value="basic">Basic Content</option>
													<!-- <option value="gallery">Gallery</option> -->
													<!--option value="document">Document</option-->
													<option value="services">Services</option>
													<option value="link">Link</option>
													<!-- <option value="works">Works</option> -->
												</select>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Menu Name *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="hidden" name="ed_idmenu" class="form-control" id="ed_idmenu" placeholder="Menu Name">
												<input type="text" name="ed_menu" class="form-control" id="ed_menu" placeholder="Menu Name">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
											</div>
										</div>
										<div class="col-lg-4 col-md-9 col-sm-12"> 
											<div class='input-group'> 
												<input type="text" name="ed_menu_en" class="form-control" id="ed_menu_en" placeholder="Nama Menu Bahasa Inggris">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Description</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="ed_desc" class="form-control" id="ed_desc" placeholder="Menu Description">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
											</div>
										</div>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="ed_desc_en" class="form-control" id="ed_desc_en" placeholder="Deskripsi Bahasa Inggris">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Background</label>
										<div class="col-lg-8 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="file" name="upl" class="form-control" id="upl" placeholder="Background">
											</div>
											<span class="form-text text-muted">Kosongkan jika tidak akan merubah background</span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Parent Menu *</label>
										<div class="col-lg-8 col-md-9 col-sm-12">
											<div class='input-group'>
												<select name="ed_parent" class="form-control select2norm" id="ed_parent" placeholder="Parent Menu" style="width: 100%;">
													<option value="">-- Choose Parent --</option>
													<option value="0">Parent Menu</option>
													<?PHP foreach ($getDataMenu as $data) { ?>
													<option value="<?PHP echo $data['id_menu']; ?>"><?PHP echo $data['menu']; ?></option>
													<?PHP } ?>
												</select>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Link</label>
										<div class="col-lg-8 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="ed_link" class="form-control" id="ed_link" placeholder="Link">
											</div>
											<span class="form-text text-muted">Mohon isi dengan huruf kecil dan tanpa spasi ataupun spesial karakter</span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Add Data After *</label>
										<div class="col-lg-8 col-md-9 col-sm-12">
											<div class='input-group'>
												<select name="ed_sort" class="form-control" id="ed_sort" placeholder="Parent Menu">
													<option value="">-- Choose Menu --</option>
												</select>
											</div>
											<span class="form-text text-muted">Choose Parent Menu First</span>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Kategori Menu *</label>
										<div class="col-lg-8 col-md-9 col-sm-12">
											<div class='input-group'>
												<select name="ed_kategori_menu" class="form-control" id="ed_kategori_menu" placeholder="Kategori Menu" readonly>
													<option value="1">Content</option>
													<option value="2">Linking</option> 
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
		</div>
	</div>
</div>
<!-- end:: Content -->