<script src="<?PHP echo base_url(); ?>assets/zxcvbn.js"></script>
<style>
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
											<label class="col-xl-3 col-lg-3 col-form-label">Avatar</label>
											<div class="col-lg-9 col-xl-6">
												<div class="kt-avatar kt-avatar--outline kt-avatar--circle-" id="useravatar">
													<div id="eksisava" class="kt-avatar__holder" style="background-image: url('<?PHP echo base_url(); ?>images/noimage.png');"></div>
													<label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
														<i class="fa fa-pen"></i>
														<input type="file" name="pict" id="pict" accept="image/*">
													</label>
													<span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
														<i class="fa fa-times"></i>
													</span>
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12"></label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<label class="kt-checkbox kt-checkbox--success">
													<input type="checkbox" name="ldap" value="1" id="ldap"> LDAP
													<span></span>
												</label>	
											</div>
										</div>

										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Username *</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<input type="text" name="user" class="form-control" id="user" placeholder="User">
												</div>
												<span id="erroruser" class="form-text text-danger" style="display: none;">NIK tidak ditemukan.</span>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Password</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<input type="password" name="pass" minlength="6" class="form-control" id="pass" placeholder="Password">
													<meter max="4" id="password-strength-meter"></meter>
													<p id="password-strength-text"></p>
												</div>
											</div>
										</div>

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

										<!-- <div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Photo</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<input type="file" name="pict" class="form-control" id="pict" placeholder="Photo">
												</div>
												<span class="form-text text-muted">Untuk tampilan lebih maksimal, gambar disarankan dengan bentuk kotak (100px x 100px)</span>
											</div>
										</div> -->

										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Email *</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<input type="email" name="email" class="form-control" id="email" placeholder="E-mail">
												</div>
											</div>
										</div>

										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12">Phone *</label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<input type="text" name="phone" class="form-control" id="phone" placeholder="Phone">
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
						<th>PHONE</th>
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
						<th>PHONE</th>
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
										<h3 class="kt-portlet__head-title">Update Data : <b id="namedata"></b></h3>
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
												<label class="col-xl-3 col-lg-3 col-form-label">Avatar</label>
												<div class="col-lg-9 col-xl-6">
													<div class="kt-avatar kt-avatar--outline kt-avatar--circle-" id="ed_useravatar">
														<div id="ed_eksisava" class="kt-avatar__holder" style="background-image: url('<?PHP echo base_url(); ?>images/noimage.png');"></div>
														<label class="kt-avatar__upload" data-toggle="kt-tooltip" title="" data-original-title="Change avatar">
															<i class="fa fa-pen"></i>
															<input type="file" name="upl" id="upl" accept="image/*">
														</label>
														<span class="kt-avatar__cancel" data-toggle="kt-tooltip" title="" data-original-title="Cancel avatar">
															<i class="fa fa-times"></i>
														</span>
													</div>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12"></label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<label class="kt-checkbox kt-checkbox--success">
														<input type="checkbox" name="ed_ldap" value="1" id="ed_ldap"> LDAP
														<span></span>
													</label>	
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Username *</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="text" name="ed_user" class="form-control" id="ed_user" placeholder="User" readonly>
													</div>
													<span id="ed_erroruser" class="form-text text-danger" style="display: none;">NIK tidak ditemukan.</span>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Password *</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="password" name="ed_pass" minlength="6" class="form-control" id="ed_pass" placeholder="Password">
														<meter max="4" id="ed_password-strength-meter"></meter>
														<p id="ed_password-strength-text"></p>
													</div>
													<span class="form-text text-muted">Kosongkan jika tidak akan merubah password</span>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Name *</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="hidden" name="ed_iduser" class="form-control" id="ed_iduser" placeholder="Name">
														<input type="text" name="ed_name" class="form-control" id="ed_name" placeholder="Name">
													</div>
												</div>
											</div>

											<!-- <div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Photo *</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="file" name="upl" class="form-control" id="upl" placeholder="Photo">
													</div>
													<span class="form-text text-muted">Untuk tampilan lebih maksimal, gambar disarankan dengan bentuk kotak (100px x 100px)</span>
												</div>
											</div> -->

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Email *</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="email" name="ed_email" class="form-control" id="ed_email" placeholder="E-mail">
													</div>
												</div>
											</div>

											<div class="form-group row">
												<label class="col-form-label col-lg-3 col-sm-12">Phone *</label>
												<div class="col-lg-4 col-md-9 col-sm-12">
													<div class='input-group'>
														<input type="text" name="ed_phone" class="form-control" id="ed_phone" placeholder="Phone">
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