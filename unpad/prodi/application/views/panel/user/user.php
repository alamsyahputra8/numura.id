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
						<?PHP echo getRoleInsert($akses,'addnewfac','Add New User');?>
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
									<h3 class="kt-portlet__head-title">Add New User</h3>
								</div>
								<div class="kt-portlet__head-toolbar">
									<ul class="nav nav-tabs nav-tabs-line nav-tabs-line-right" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#kt_portlet_base_demo_1_tab_content" role="tab">
												<i class="flaticon-user"></i> User Data
											</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#kt_portlet_base_demo_2_tab_content" role="tab">
												<i class="flaticon-interface-1"></i> Roles
											</a>
										</li>
									</ul>
								</div>
							</div>
							<div class="kt-portlet__body">
								<div class="tab-content">
									<div class="tab-pane active" id="kt_portlet_base_demo_1_tab_content" role="tabpanel">
										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Name *</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<?PHP
													$q_lo = $this->db->query("SELECT max(userid)as max_id_user FROM user")->result_array();
													foreach($q_lo as $row_lo) {
													?>
													<input type="hidden" name="id_user" class="form-control" id="id_user" value="<?PHP echo $row_lo['max_id_user']+1;?>" readonly>
													<?PHP
													}
													?>
													<input type="text" name="name" class="form-control" id="name" placeholder="Name">
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Photo *</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<input type="file" name="pict" class="form-control" id="pict" placeholder="Photo">
												</div>
												<span class="form-text text-muted">Untuk tampilan lebih maksimal, gambar disarankan dengan bentuk kotak (100px x 100px)</span>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Username *</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<input type="text" name="user" class="form-control" id="user" placeholder="User">
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Password *</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<input type="password" name="pass" class="form-control" id="pass" placeholder="Password">
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Email</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<input type="email" name="email" class="form-control" id="email" placeholder="E-mail">
												</div>
											</div>
										</div>
									</div>

									<div class="tab-pane" id="kt_portlet_base_demo_2_tab_content" role="tabpanel">
										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Role</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<select name="role" class="form-control" id="role" placeholder="Role">
														<option value="">-- Pilih Role --</option>
														<?PHP foreach ($getDataRole as $data) { ?>
														<option value="<?PHP echo $data['id_role']; ?>"><?PHP echo $data['nama_role']; ?></option>
														<?PHP } ?>
													</select>
												</div>
											</div>
										</div>
										<div id='contentrole'></div>
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
						<th>PHOTO</th>
						<th>NAME</th>
						<th>USERNAME</th>
						<th>EMAIL</th>
						<th>ROLE</th>
						<th>ACTIONS</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>PHOTO</th>
						<th>NAME</th>
						<th>USERNAME</th>
						<th>EMAIL</th>
						<th>ROLE</th>
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
										<h3 class="kt-portlet__head-title">Update Data : <b id="nameroles"></b></h3>
									</div>
									<div class="kt-portlet__head-toolbar">
										<ul class="nav nav-tabs nav-tabs-line nav-tabs-line-right" role="tablist">
											<li class="nav-item">
												<a class="nav-link active" data-toggle="tab" href="#ed_kt_portlet_base_demo_1_tab_content" role="tab">
													<i class="flaticon-user"></i> User Data
												</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" data-toggle="tab" href="#ed_kt_portlet_base_demo_2_tab_content" role="tab">
													<i class="flaticon-interface-1"></i> Roles
												</a>
											</li>
										</ul>
									</div>
								</div>
								<div class="kt-portlet__body">
									<div class="tab-content">
										<div class="tab-pane active" id="ed_kt_portlet_base_demo_1_tab_content" role="tabpanel">
											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Name *</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="hidden" name="ed_iduser" class="form-control" id="ed_iduser" placeholder="Name">
														<input type="text" name="ed_name" class="form-control" id="ed_name" placeholder="Name">
													</div>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Photo *</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="file" name="upl" class="form-control" id="upl" placeholder="Photo">
													</div>
													<span class="form-text text-muted">Untuk tampilan lebih maksimal, gambar disarankan dengan bentuk kotak (100px x 100px)</span>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Username *</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="text" name="ed_user" class="form-control" id="ed_user" placeholder="User">
													</div>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Password *</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="password" name="ed_pass" class="form-control" id="ed_pass" placeholder="Password">
													</div>
													<span class="form-text text-muted">Kosongkan jika tidak akan merubah password</span>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Email</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="email" name="ed_email" class="form-control" id="ed_email" placeholder="E-mail">
													</div>
												</div>
											</div>
										</div>

										<div class="tab-pane" id="ed_kt_portlet_base_demo_2_tab_content" role="tabpanel">
											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Role</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<select name="ed_role" class="form-control" id="ed_role" placeholder="Role">
															<option value="">-- Pilih Role --</option>
															<?PHP foreach ($getDataRole as $data) { ?>
															<option value="<?PHP echo $data['id_role']; ?>"><?PHP echo $data['nama_role']; ?></option>
															<?PHP } ?>
														</select>
													</div>
												</div>
											</div>
											<div id='edcontentrole'></div>
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