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
						<?PHP echo getRoleInsert($akses,'addnewfac','Add New Roles');?>
					</div>
				</div>
			</div>
		</div>

		<!-- MODAL INSERT -->
		<div class="modal fade" id="addnewfac" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Add New Roles</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						</button>
					</div>

					<form class="kt-form kt-form--label-left" id="forminsert" enctype="multipart/form-data">
						<div class="modal-body">
							<div class="kt-form__content">
								<div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="kt_form_1_msg">
									<div class="kt-alert__icon">
										<i class="la la-warning"></i>
									</div>
									<div class="kt-alert__text">
										Oh snap! Change a few things up and try submitting again.
									</div>
									<div class="kt-alert__close">
										<button type="button" class="close" data-close="alert" aria-label="Close">
										</button>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Roles Name *</label>
								<div class="col-lg-4 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="text" name="role" class="form-control" placeholder="Role Name" id="role" value="">
									</div>
									<span class="form-text text-muted">Type a Role Name</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Roles Description *</label>
								<div class="col-lg-4 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="text" name="role_description" class="form-control" placeholder="Role Description" id="role_description" value="">
									</div>
									<span class="form-text text-muted">Type a Role Description</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Module *</label>
								<div class="col-sm-12" style="padding-top:10px;">
									<table class='smalltable nowrap table' width=100%>
										<thead class='bg-gray-dark'>
											<th>
												<b>Menu Name</b>
												<!--label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
													<input id="selectAll" type="checkbox"> <b>Menu Name</b>
													<span></span>
												</label-->
											</th>
											<th class="text-right"><b>Fitur</b></th>
										</thead>
										<tbody>
											<?PHP 
											foreach($getMenus as $data) { 
												$CekSub	= $this->query->getNumRows('menu','*',"WHERE parent='".$data['id_menu']."'")->num_rows();
											?>
												<tr>
													<td>
														<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
															<input value="<?PHP echo $data['id_menu']; ?>" id="checkbox<?PHP echo $data['id_menu']; ?>" type="checkbox" name="menu[]"> <?PHP echo $data['menu']; ?>
															<span></span>
														</label>
													</td>
													<td class='text-right kt-checkbox-inline'>
													<?PHP 
													$data_fitur = explode_fitur($data['fitur']);
													for($x=0;$x<count($data_fitur);$x++)
													{
													?>
													<?PHP if ($CekSub>0) { echo ''; } else { ?>
														<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
															<input value="<?PHP echo $data_fitur[$x]; ?>" id="checkbox<?PHP echo $data['id_menu'].$data_fitur[$x]; ?>" type="checkbox" name="fitur[<?PHP echo $data['id_menu']; ?>][]"> <?PHP echo $data_fitur[$x]; ?>
															<span></span>
														</label>
													<?PHP } ?>
													<script>
														$("#checkbox<?PHP echo $data['id_menu']; ?>").change(function() {
															if($("#checkbox<?PHP echo $data['id_menu']; ?>").prop( "checked" )){
																$("#checkbox<?PHP echo $data['id_menu'].$data_fitur[$x]; ?>").prop("checked", true);	
															}else{
																$("#checkbox<?PHP echo $data['id_menu'].$data_fitur[$x]; ?>").prop("checked", false);
															}
														});
													</script>
													<?PHP 
													} 
													?>
													<script>
														$("#selectAll").change(function() {
															if($("#selectAll").prop( "checked" )){
																$("#checkbox<?PHP echo $data['id_menu']; ?>").prop("checked", true);
																$( "#checkbox<?PHP echo $data['id_menu']; ?>" ).trigger( "change" );
															}else{
																$("#checkbox<?PHP echo $data['id_menu']; ?>").prop("checked", false);
																$( "#checkbox<?PHP echo $data['id_menu']; ?>" ).trigger( "change" );
															}
														});
													</script>
													</td>
												</tr>
												
												<?PHP
												// GET SUBMENU
												$getSubMenu 	= $this->query->getData('menu','*',"WHERE parent='".$data['id_menu']."' order by sort asc");
												foreach ($getSubMenu as $data) {
												?>
													<tr style="background: rgba(0,0,0,.04)!important;">
														<td style="padding-left: 30px!important;">
															<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
																<input value="<?PHP echo $data['id_menu']; ?>" id="checkbox<?PHP echo $data['id_menu']; ?>" type="checkbox" name="menu[]"> <?PHP echo $data['menu']; ?>
																<span></span>
															</label>
														</td>
														<td class='text-right kt-checkbox-inline'>
														<?PHP 
														$data_fitur = explode_fitur($data['fitur']);
														for($x=0;$x<count($data_fitur);$x++)
														{
														?>

														<label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand">
															<input value="<?PHP echo $data_fitur[$x]; ?>" id="checkbox<?PHP echo $data['id_menu'].$data_fitur[$x]; ?>" type="checkbox" name="fitur[<?PHP echo $data['id_menu']; ?>][]"> <?PHP echo $data_fitur[$x]; ?>
															<span></span>
														</label> 
														<script>
															$("#checkbox<?PHP echo $data['id_menu']; ?>").change(function() {
																if($("#checkbox<?PHP echo $data['id_menu']; ?>").prop( "checked" )){
																	$("#checkbox<?PHP echo $data['id_menu'].$data_fitur[$x]; ?>").prop("checked", true);	
																}else{
																	$("#checkbox<?PHP echo $data['id_menu'].$data_fitur[$x]; ?>").prop("checked", false);
																}
															});
														</script>
														<?PHP 
														} 
														?>
														<script>
															$("#selectAll").change(function() {
																if($("#selectAll").prop( "checked" )){
																	$("#checkbox<?PHP echo $data['id_menu']; ?>").prop("checked", true);
																	$( "#checkbox<?PHP echo $data['id_menu']; ?>" ).trigger( "change" );
																}else{
																	$("#checkbox<?PHP echo $data['id_menu']; ?>").prop("checked", false);
																	$( "#checkbox<?PHP echo $data['id_menu']; ?>" ).trigger( "change" );
																}
															});
														</script>
														</td>
													</tr>
												<?PHP } ?>
											<?PHP } ?>
										</tbody>
									</table>													
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
						<th>ROLE NAME</th>
						<th>ROLE DESCRIPTION</th>
						<th>UPDATE BY</th>
						<th>LAST UPDATE</th>
						<th>ACTIONS</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th>ROLE NAME</th>
						<th>ROLE DESCRIPTION</th>
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
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLongTitle">Update Data : <b id="nameroles"></b></h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							</button>
						</div>

						<form class="kt-form kt-form--label-left" id="formupdate" enctype="multipart/form-data">
							<div class="modal-body">
								<div class="kt-form__content">
									<div class="kt-alert m-alert--icon alert alert-danger kt-hidden" role="alert" id="kt_form_1_msg">
										<div class="kt-alert__icon">
											<i class="la la-warning"></i>
										</div>
										<div class="kt-alert__text">
											Oh snap! Change a few things up and try submitting again.
										</div>
										<div class="kt-alert__close">
											<button type="button" class="close" data-close="alert" aria-label="Close">
											</button>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Roles Name *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="hidden" value="" name="ed_idroles" id="ed_idroles">
											<input type="text" name="ed_roles" class="form-control" placeholder="Role Name" id="ed_roles" value="">
										</div>
										<span class="form-text text-muted">Type a Role Name</span>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Roles Description *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<input type="text" name="ed_role_description" class="form-control" placeholder="Role Description" id="ed_role_description" value="">
										</div>
										<span class="form-text text-muted">Type a Role Description</span>
									</div>
								</div>
								<div id="exmod"></div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" id="saveupdate" class="btn btn-primary">Update</button>
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
								<input type="hidden" name="iddelroles" id="iddelroles" value="">
								<center>
								<button type="button" id="deleteRoles" class="swal2-confirm swal2-styled" aria-label="" style="border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">
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