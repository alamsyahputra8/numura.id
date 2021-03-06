<?PHP
$q 				= "
				select a.*, 
					(select count(*) from content where id_menu=a.id_menu) as jmlcontent 
				from menu_site a 
				where style='blog' order by sort asc
				";
$getDataMenu	= $this->query->getDatabyQ($q);

$qm 				= "
				select * from menu_site where style='ourteam' order by sort asc
				";
$getDataMenum	= $this->query->getDatabyQ($qm);
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
						<?PHP echo getRoleInsert($akses,'addnewfac','Add New Team');?>
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
									<h3 class="kt-portlet__head-title">Add New Team</h3>
								</div>
							</div>
							<div class="kt-portlet__body">
								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Name *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="name" class="form-control" id="name" placeholder="Name">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Email *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="email" class="form-control" id="email" placeholder="Email">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Position *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="position" class="form-control" id="position" placeholder="Position">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
										</div>
									</div>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="position_en" class="form-control" id="position_en" placeholder="Position">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Department *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="department" class="form-control" id="department" placeholder="Department">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
										</div>
									</div>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="department_en" class="form-control" id="department_en" placeholder="Department">
											<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
										</div>
									</div>
								</div> 

								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Picture *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="file" name="pict" class="form-control" id="pict" placeholder="Logo">
										</div>
										<span class="form-text text-muted">Untuk tampilan lebih maksimal, gambar disarankan dengan bentuk kotak (150px x 150px)</span>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Sort *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="number" name="sort" class="form-control" id="sort" placeholder="Sort / Urutan" min="0">
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Menu *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											 <select name="menu_x" class="form-control" id="menu_x" placeholder="Menu" readonly> 
												<option value="">-- Pilih Menu --</option> 
												<?PHP foreach ($getDataMenum as $datamenux) { ?>
												<option value="<?PHP echo $datamenux['id_menu'];?>"><?PHP echo $datamenux['menu'];?> </option> 
												<?PHP } ?>
											</select>
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
						<th>PICTURE</th>
						<th>NAME</th>
						<th>EMAIL</th>
						<th>POSITION</th>
						<th>DEPARTMENT</th>
						<th>UPDATE BY</th>
						<th>LAST UPDATE</th>
						<th>ACTIONS</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>PICTURE</th>
						<th>NAME</th>
						<th>EMAIL</th>
						<th>POSITION</th>
						<th>DEPARTMENT</th>
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
										<label class="col-form-label col-lg-3 col-sm-12">Name *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="hidden" name="ed_id" class="form-control" id="ed_id" placeholder="ID">
												<input type="text" name="ed_name" class="form-control" id="ed_name" placeholder="Name">
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-3 col-sm-12">Email *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="ed_email" class="form-control" id="ed_email" placeholder="Email">
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-lg-3 col-sm-12">Position *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="ed_position" class="form-control" id="ed_position" placeholder="Position">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
											</div>
										</div>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="ed_position_en" class="form-control" id="ed_position_en" placeholder="Position">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-form-label col-lg-3 col-sm-12">Department *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="ed_department" class="form-control" id="ed_department" placeholder="Department">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">ID</span></div>
											</div>
										</div>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="ed_department_en" class="form-control" id="ed_department_en" placeholder="Department">
												<div class="input-group-append"><span class="input-group-text" id="basic-addon2">EN</span></div>
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-3 col-sm-12">Picture *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="file" name="upl" class="form-control" id="upl" placeholder="Logo">
											</div>
											<span class="form-text text-muted">Kosongkan apabila tidak akan merubah logo.</span>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-3 col-sm-12">Sort *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="number" name="ed_sort" class="form-control" id="ed_sort" placeholder="Sort / Urutan" min="0">
											</div>
										</div>
									</div>
									
									<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Menu *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											 <select name="ed_menu_x" class="form-control" id="ed_menu_x" placeholder="Menu" readonly> 
												<option value="">-- Pilih Menu --</option> 
												<?PHP foreach ($getDataMenum as $datamenux) { ?>
												<option value="<?PHP echo $datamenux['id_menu'];?>"><?PHP echo $datamenux['menu'];?> </option> 
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
		</div>
	</div>
</div>
<!-- end:: Content -->