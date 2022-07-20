<?php
$qconfig_web 				= "
				select * from config_web;
				";
$getconfig_web	= $this->query->getDatabyQ($qconfig_web);
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
						<?PHP echo getRoleInsert($akses,'addnewfac','Add New Banner');?>
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
									<h3 class="kt-portlet__head-title">Add New Banner</h3>
								</div>
							</div>
							<div class="kt-portlet__body">
								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Kategori Website *</label>
									<div class="col-lg-8 col-md-9 col-sm-12">
										<div class='input-group'>
											<select name="kategori_website" class="form-control" id="kategori_website" placeholder="Jenis Menu" readonly>
												<option value="">-- Pilih Kategori Menu --</option> 
												<?PHP foreach ($getconfig_web as $dataweb) { ?>
												<option value="<?PHP echo $dataweb['id_web'];?>"><?PHP echo $dataweb['nama'];?> </option> 
												<?PHP } ?>
											</select>
										</div>
									</div> 
								</div>
								<div class="form-group row" style="display:none">
									<label class="col-form-label col-lg-2 col-sm-12">Title *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="title" class="form-control" id="title" placeholder="Title">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
										</div> 
										<span class="form-text text-muted">Tambahkan <b><code>&ltbr&gt</code></b> jika ingin menambahkan "Enter" atau baris baru.</span>
									</div>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="title_en" class="form-control" id="title_en" placeholder="Title">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
										</div> 
									</div>
								</div>

								<div class="form-group row"  style="display:none">
									<label class="col-form-label col-lg-2 col-sm-12">Sub Title *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="subtitle" class="form-control" id="subtitle" placeholder="Sub Title">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
										</div>
									</div>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="subtitle_en" class="form-control" id="subtitle_en" placeholder="Sub Title">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Banner *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="file" name="pict" class="form-control" id="pict" placeholder="Cover">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
										</div>
										<span class="form-text text-muted">Untuk tampilan lebih maksimal, gambar disarankan dengan bentuk persegi panjang atau Landscape.</span>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-2 col-sm-12">Banner *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="file" name="pict_en" class="form-control" id="pict_en" placeholder="Cover">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
										</div>
										<span class="form-text text-muted">Untuk tampilan lebih maksimal, gambar disarankan dengan bentuk persegi panjang atau Landscape.</span>
									</div>
								</div>
								<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Link *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'> 
												<input type="text" name="link" class="form-control" id="link" placeholder="Link"> 
											</div>  
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
						<th>WEBSITE</th>
						<th>PICTURE ID</th>
						<th>PICTURE EN</th> 
						<th>LINK</th> 
						<th>UPDATE BY</th>
						<th>LAST UPDATE</th>
						<th>ACTIONS</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>WEBSITE</th>
						<th>PICTURE ID</th>
						<th>PICTURE EN</th>
						<th>LINK</th> 
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
													<option value="">-- Pilih Kategori Menu --</option> 
													<?PHP foreach ($getconfig_web as $dataweb) { ?>
													<option value="<?PHP echo $dataweb['id_web'];?>"><?PHP echo $dataweb['nama'];?> </option> 
													<?PHP } ?> 
												</select>
											</div>
										</div> 
									</div>
									<div class="form-group row" style="display:none">
										<label class="col-form-label col-lg-2 col-sm-12">Title *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="hidden" name="ed_id" class="form-control" id="ed_id">
												<input type="text" name="ed_title" class="form-control" id="ed_title" placeholder="Title">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
											</div> 
											<span class="form-text text-muted">Tambahkan <b><code>&ltbr&gt</code></b> jika ingin menambahkan "Enter" atau baris baru.</>
										</div>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'> 
												<input type="text" name="ed_title_en" class="form-control" id="ed_title_en" placeholder="Title">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
											</div>   
										</div>
									</div>

									<div class="form-group row" style="display:none">
										<label class="col-form-label col-lg-2 col-sm-12">Sub Title *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="ed_subtitle" class="form-control" id="ed_subtitle" placeholder="Sub Title">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
											</div>
										</div>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="ed_subtitle_en" class="form-control" id="ed_subtitle_en" placeholder="Sub Title">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Banner *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="file" name="upl" class="form-control" id="upl" placeholder="Cover">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
											</div>
											<span class="form-text text-muted">Kosongkan jika tidak akan merubah banner.</span>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Banner *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="file" name="upl_en" class="form-control" id="upl_en" placeholder="Cover">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
											</div>
											<span class="form-text text-muted">Kosongkan jika tidak akan merubah banner.</span>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-lg-2 col-sm-12">Link *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'> 
												<input type="text" name="ed_link" class="form-control" id="ed_link" placeholder="Link"> 
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