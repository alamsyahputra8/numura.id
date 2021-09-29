<?PHP 
$this->load->view('/theme/panel/plugin'); 
error_reporting(0);
?>
		
<div class="content-wrapper" id="pageContent">
	<!-- Main content -->
	<div class="content"><br>
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div class="panel panel-bd">
					<div class="panel-heading panelredbg">
						<div class="panel-title">
							<h4 class="text-white">E-Mail Notification Weekly | Configuration</h4>
						</div>
						<div class="pull-right" style="margin-top:-27px;">
							<?PHP echo getRoleInsert($akses,'addnewmenu','Add New GC');?>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<div class="panel-body">
						<form id="forminsert"  enctype="multipart/form-data">
						<div id="suksesinsert" style="display:none;">
							<div class="alert alert-success alert-dismissible" role="alert">
								<strong>Data Insert Success!</strong>
							</div>
						</div>
						<div id="gagalinsert" style="display:none;">
							<div class="alert alert-danger alert-dismissible" role="alert">
								<strong>Data Insert Failed!</strong> Change a few things up and try submitting again.
							</div>
						</div>
						<div id="gagal_insert" style="display:none;">
							<div class="alert alert-warning alert-dismissible" role="alert">
								<strong>User Exist!</strong> Change a few things up and try submitting again.
							</div>
						</div>
						
						<div class="form-group row" style="padding-top:30px;padding-bottom:30px;">
							<label for="user" class="col-sm-1 col-form-label">List User</label>
							<div class="col-sm-9">
								<select class="form-control select_lo" placeholder="User" id='user' name='user' style='width:100% !important;' required />
									<option value=''>- Pilih User -</option>
									<?PHP
									$q_user = $this->db->query("SELECT userid,username,name FROM user WHERE email!='' ORDER BY username")->result_array();
									foreach($q_user as $row_user) {
										echo "<option value='".$row_user['username']."'>".$row_user['username']." - ".$row_user['name']."</option>";
									}
									?>
								</select>
							</div>
							<div class="col-sm-1">
								<?PHP 
								$a_array = explode(",",trim($akses));
								if(in_array('insert',$a_array))
								{
								?>
								<button class="btnaddnewdata btn btn-cbtn-sm" id="saveinsert" type="submit" ><i class="fa fa-plus p-r-5"></i> Add User</button>
								<?PHP 
								}
								?>
							</div>
							<div class="col-sm-12">
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small># Data User yang muncul hanya yang memiliki E-Mail</small>
							</div>
						</div>
						</form>
						<script>
						$('#forminsert').submit(function() {
							var formdata = new FormData(this);
							console.log();
							$.ajax({
								url: "<?PHP echo base_url(); ?>lop/insertmail",
								type: "POST",
								data: formdata,
								beforeSend: function(){ 
									$("#saveinsert").html('Saving...');
									$("#progresloader").fadeIn('fast');
								},
								success: function(data) {
									var myJSON = JSON.parse(data);
									if(myJSON.total==1) {
										// alert(data);
										$("#saveinsert").html('Save');
										$('#suksesinsert').fadeIn('fast');
										$('#gagalinsert').hide('fast');
										$("#progresloader").fadeOut('fast');
										$('#forminsert')[0].reset();
										setTimeout(function(){
											$('#addnewmenu').modal('toggle');
											$('#tblmail').DataTable().ajax.reload();
											$('#suksesinsert').fadeOut('fast');
										} , 1000);
									} else if(myJSON.total==3){ 
										$('#suksesinsert').hide('fast');
										$('#gagal_insert').fadeIn('fast');
										$("#saveinsert").html('Save');
										$("#progresloader").fadeOut('fast');
									}else{
										$('#suksesinsert').hide('fast');
										$('#gagalinsert').fadeIn('fast');
										$("#saveinsert").html('Save');
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
							return false;
						});
						</script>
						<hr>
						<div class="table-responsive">
							<table id="tblmail" class="table dataTableExample2 nowrap table-hover" style="width: 100%;">
								<thead class="bg-gray-dark text-white">
									<tr>
										<th>No</th>
										<th class="text-center">USERNAME</th>
										<th class="text-center">NAMA</th>
										<th class="text-center">E-Mail</th>
										<th class="text-center" style="width: 10%;">Remove</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>No</th>
										<th class="text-center">USERNAME</th>
										<th class="text-center">NAMA</th>
										<th class="text-center">E-Mail</th>
										<th class="text-center" style="width: 10%;">Remove</th>
									</tr>
								</tfoot>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<!-- MODAL DELETE -->
					<div class="modal fade" id="delete" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document" style="top:25%;">
							<div class="modal-content">
								<div class="modal-header paneldarkbg">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title text-white">Delete Data : <b id="namedelfac"></b></h4>
								</div>
								<div class="modal-body">
									Are you sure you want to delete data?
									<div id="suksesdelete" style="display:none; margin-top: 40px;">
										<div class="alert alert-success alert-dismissible" role="alert">
											<strong>Data Delete Success!</strong>
										</div>
									</div>
									<div id="gagaldelete" style="display:none; margin-top: 40px;">
										<div class="alert alert-danger alert-dismissible" role="alert">
											<strong>Data Delete Failed!</strong>
										</div>
									</div>
								</div>
								<div class="modal-footer text-center">
									<form method="POST">
									<input type="hidden" name="iddelfac" id="iddelfac" value="">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="button" id="deleteFac" class="btn btn-danger">Yes</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<script>
					$('#deleteFac').click(function() {
						var id=$("#iddelfac").val();
						var dataString = 'id='+id;
						$.ajax({
							type: "POST",
							url: "<?PHP echo base_url(); ?>lop/deletemail",
							data: dataString,
							beforeSend: function(){ 
								$("#deleteFac").html('Deleting...');
								$("#progresloader").fadeIn('fast');
							},
							success: function(data) {
								if(data) {
									$("#deleteFac").html('Delete');
									$('#suksesdelete').fadeIn('fast');
									$('#gagaldelete').hide('fast');
									$("#progresloader").fadeOut('fast');
									$('#iddelfac').load(document.URL +  ' #iddelfac');
									$('#namedelfac').load(document.URL +  ' #namedelfac');
									setTimeout(function(){
										$('#delete').modal('toggle');
										$('#tblmail').DataTable().ajax.reload();
										$('#suksesdelete').fadeOut('fast');
									} , 1000);
								} else { 
									$('#suksesdelete').hide('fast');
									$('#gagaldelete').fadeIn('fast');
									$("#progresloader").fadeOut('fast');
									$("#deleteFac").html('Delete');
								}
							}
						});
						return false;
					});
					</script>
					<!-- END MODAL DELETE -->
				</div>
				<div class="clearfix"></div>
				<div class="md-overlay"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<script>
$(document).on('click', '.btndeleteMenu', function(e){
	// alert('data');
	e.preventDefault();
	
	var id = $(this).data('id'); // get id of clicked row
	
	$('#dynamic-content').hide(); // hide dive for loader
	$('#modal-loader').show();  // load ajax loader
	
	$.ajax({
		url: '<?PHP echo base_url(); ?>lop/modalmail',
		type: 'POST',
		data: 'id='+id,
		dataType: 'json'
	})
	.done(function(data){
		// console.log(data);
		$('#dynamic-content').hide(); // hide dynamic div
		$('#dynamic-content').show(); // show dynamic div
		$('#namedelfac').html(data.username);
		$('#iddelfac').val(data.id_mailweekly);
		$('#iddelfac').val(data.id_mailweekly);
		$('#modal-loader').hide();    // hide ajax loader
	})
	.fail(function(){
		$('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
	});
	
});
</script>
<?PHP $this->load->view('/theme/panel/plugin_js'); ?>