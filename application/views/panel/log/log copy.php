<?PHP 
$this->load->view('/theme/panel/plugin'); 
error_reporting(0);
?>
<style>
.user {
	display: inline-block;
	width: 40px;
	height: 40px;
	border-radius: 50%;

	background-repeat: no-repeat;
	background-position: center center;
	background-size: cover;
}
</style>	
<div class="content-wrapper" id="pageContent">
	<!-- Main content -->
	<div class="content"><br>
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div class="panel panel-bd">
					<div class="panel-heading panelredbg">
						<div class="panel-title">
							<h4 class="text-white">Log Activity RAISA</h4>
						</div>
						<!--div class="pull-right" style="margin-top:-27px;">
							<button class="btn btn-warning" data-toggle="modal" data-target="#addnewmenu">Add New AM</button>
						</div-->
						<div class="clearfix"></div>
					</div>
					<div style='padding-left:10px;'>
						<form id="formfilter_lop">
							<div class="col-sm-12 padding0">
								<br>
							</div>
							<div class="col-sm-12">
								<div class="col-sm-4">
									<div class="input-group input-daterange">
										<input type="text" name='start' id='start' class="form-control" value="<?PHP echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd">
										<div class="input-group-addon">to</div>
										<input type="text" name='end' id='end' class="form-control" value="<?PHP echo date('Y-m-d');?>" data-date-format="yyyy-mm-dd">
									</div>
								</div>
								<div class="col-sm-8">
									<div class="col-sm-5">
									<select class="form-control select_map" placeholder="TREG" id='s_treg' name='s_treg'>
										<option value=''>All TREG</option>
										<option value='TREG-1'>TREG-1</option>
										<option value='TREG-2'>TREG-2</option>
										<option value='TREG-3'>TREG-3</option>
										<option value='TREG-4'>TREG-4</option>
										<option value='TREG-5'>TREG-5</option>
										<option value='TREG-6'>TREG-6</option>
										<option value='TREG-7'>TREG-7</option>
									
									</select>
									</div>
									
									<div class="col-sm-1 ">
										<button type="button" name="filter" id="filter_log" class="btn btn-primary btn-sm">Filter</button>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
							<div class="col-sm-12" style='padding-top:10px;'>
								<!--div class="col-sm-2 ">
									<select class="form-control select_map" placeholder="TREG" id='s_treg' name='s_treg'>
										<option value=''>All TREG</option>
										<option value='TREG-1'>TREG-1</option>
										<option value='TREG-2'>TREG-2</option>
										<option value='TREG-3'>TREG-3</option>
										<option value='TREG-4'>TREG-4</option>
										<option value='TREG-5'>TREG-5</option>
										<option value='TREG-6'>TREG-6</option>
										<option value='TREG-7'>TREG-7</option>
									
									</select>
								</div>
								<div class="col-sm-3 ">
									<select class="form-control select_map" placeholder="Witel" id='s_witel' name='s_witel'>
										<option value=''>All Witel</option>
										<?PHP
										$getWitel		= $this->query->getData('map',"DISTINCT id_witel as witel","");
										foreach($getWitel as $witel) {
										?>
										<option value="<?PHP echo $witel['witel']; ?>"><?PHP echo $witel['witel']; ?></option>
										<?PHP } ?>
									</select>
								</div>
								<div class="col-sm-1 ">
									<button type="button" name="filter" id="filter_lop_new" class="btn btn-primary btn-sm">Filter</button>
								</div-->
							</div>
							<div class="clearfix"></div>
						</form>
						<script>
						
							$("#s_segment").on('change', function(e) {
								// Access to full data
								$('#s_am').html('');
								var datas = $(this).select2('data');
								var getidsegmen = datas[0].text;
								var output = "";
								$.ajax({
									url: "<?PHP echo base_url(); ?>core/getselect2value?id="+getidsegmen,
									type: 'Get',
									dataType: "json",

									success: function (result) {
											$("#s_am").select2({ data: result });
									},
									error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
										alert("An error occurred while processing your request. Please try again.");
									}
								});
							});
						</script>
					</div>
					<div class="panel-body">
						<div class="table-responsive">
							<table id="tbllog" class="table nowrap table-hover" style="width: 120%!important;">
								<thead class="bg-gray-dark text-white">
									<tr>
										<th>No</th>
										<th class="text-center">TANGGAL</th>
										<th class="text-center">PHOTO</th>
										<th class="text-center">USER</th>
										<th class="text-center">ACTIVITY</th>
										<th class="text-center">ID</th>
										<th class="text-center" style='width:200px'>DATA</th>
										<th class="text-center" style='width:100px'>SATKER LO</th>
										<th class="text-center" style='width:100px'>K/L/D/I</th>
										<th class="text-center" style='width:100px'>TREG</th>
										<th class="text-center">MENU</th>
										<th class="text-center">IP ADDRESS</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>No</th>
										<th class="text-center"></th>
										<th class="text-center"></th>
										<th class="text-center"></th>
										<th class="text-center"></th>
										<th class="text-center"></th>
										<th class="text-center" style='width:200px'></th>
										<th class="text-center" style='width:100px'></th>
										<th class="text-center" style='width:100px'></th>
										<th class="text-center" style='width:100px'></th>
										<th class="text-center"></th>
										<th class="text-center"></th>
									</tr>
								</tfoot>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
					<!-- MODAL FORM ADD NEW -->
					<div class="modal fade" id="addnewmenu" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header bg-blue">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="text-white modal-title">Add New AM</b></h4>
								</div>
								<form id="forminsert"  enctype="multipart/form-data">
								<div class="modal-body">
									<div class="col-md-10">
										<div class="form-group row">
											<label for="id_lo" class="col-sm-2 col-form-label">NIK AM</label>
											<div class="col-sm-10">
												<?PHP
												$qmax = $this->db->query("SELECT max(id_am)as max_id_am FROM am")->result_array();
												foreach($qmax as $rowmax) {
													if($rowmax['max_id_am'] ==''){
														$id='0';
													}else{
														$id = $rowmax['max_id_am']+1;
													}
												?>
													<input type="hidden" name="id_am" class="form-control" id="id_am" placeholder="ID AM" value="<?PHP echo $id;?>">
												<?PHP
												}
												?>
												<input type="text" name="nik_am" class="form-control" id="nik_am" placeholder="NIK AM" value="">
											</div>
										</div>
										<div class="form-group row">
											<label for="nama_am" class="col-sm-2 col-form-label">Nama AM</label>
											<div class="col-sm-10">
												<input type="text" name="nama_am" class="form-control" id="nama_am" placeholder="Nama AM" value="">
											</div>
										</div>
										<div class="form-group row">
											<label for="posisi" class="col-sm-2 col-form-label">Posisi</label>
											<div class="col-sm-10">
												<input type="text" name="posisi" class="form-control" id="posisi" placeholder="Posisi" value="">
											</div>
										</div>
										<div class="form-group row">
											<label for="band" class="col-sm-2 col-form-label">Band</label>
											<div class="col-sm-10">
												<input type="text" name="band" class="form-control" id="band" placeholder="Band" value="">
											</div>
										</div>
										
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
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button id="saveinsert" type="submit" class="btn btn-primary">Save</button>
								</div>
								</form>
							</div>
						</div>
						<script>
						$('#forminsert').submit(function() {
							
							var formdata = new FormData(this);
							console.log();
							$.ajax({
								url: "<?PHP echo base_url(); ?>core/insertam",
								type: "POST",
								data: formdata,
								beforeSend: function(){ 
									$("#saveinsert").html('Saving...');
									$("#progresloader").fadeIn('fast');
								},
								success: function(data) {
									if(data) {
										// alert(data);
										$("#saveinsert").html('Save');
										$('#suksesinsert').fadeIn('fast');
										$('#gagalinsert').hide('fast');
										$("#progresloader").fadeOut('fast');
										setTimeout(function(){location.href="am"} , 1000);
									} else { 
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
					</div>
					<!-- END MODAL FORM ADD NEW -->
					<!-- MODAL UPDATE -->
					<div class="modal fade" id="update" tabindex="-1" role="dialog">
						<div class="modal-dialog modal-lg" role="document">
							<div class="modal-content">
								<div class="modal-header bg-blue">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="text-white modal-title">Update Data : <b id="ed_nik_am2"></b></h4>
								</div>
								<form id="formupdate" enctype="multipart/form-data">
								<div class="modal-body">
									<div class="col-md-10">
										<div class="form-group row">
											<label for="ed_nik_am" class="col-sm-2 col-form-label">NIK AM</label>
											<div class="col-sm-10">
												<input type="hidden" name="ed_id_am" class="form-control" id="ed_id_am" >
												<input type="text" name="ed_nik_am" class="form-control" id="ed_nik_am" placeholder="NIK AM" value="">
											</div>
										</div>
										<div class="form-group row">
											<label for="ed_nama_am" class="col-sm-2 col-form-label">Nama AM</label>
											<div class="col-sm-10">
												<input type="text" name="ed_nama_am" class="form-control" id="ed_nama_am" placeholder="Nama AM" value="">
											</div>
										</div>
										<div class="form-group row">
											<label for="ed_posisi" class="col-sm-2 col-form-label">Posisi</label>
											<div class="col-sm-10">
												<input type="text" name="ed_posisi" class="form-control" id="ed_posisi" placeholder="Posisi" value="">
											</div>
										</div>
										<div class="form-group row">
											<label for="ed_band" class="col-sm-2 col-form-label">Band</label>
											<div class="col-sm-10">
												<input type="text" name="ed_band" class="form-control" id="ed_band" placeholder="Band" value="">
											</div>
										</div>
										
										<div id="sukses" style="display:none;">
											<div class="alert alert-success alert-dismissible" role="alert">
												<strong>Data Update Success!</strong>
											</div>
										</div>
										<div id="gagal" style="display:none;">
											<div class="alert alert-danger alert-dismissible" role="alert">
												<strong>Data Update Failed!</strong> Change a few things up and try submitting again.
											</div>
										</div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button id="saveupdate" type="submit" class="btn btn-primary">Save</button>
								</div>
								</form>
								<script>
								$('#formupdate').submit(function() {
									
									var formdata = new FormData(this);
									
									$.ajax({
										url: "<?PHP echo base_url(); ?>core/updateam",
										type: "POST",
										data: formdata,
										beforeSend: function(){ 
											$("#saveupdate").html('Saving...');
											$("#progresloader").fadeIn('fast');
										},
										success: function(data) {
											if(data) {
												// alert(data);
												$("#saveupdate").html('Save');
												$('#sukses').fadeIn('fast');
												$('#gagal').hide('fast');
												$("#progresloader").fadeOut('fast');
												setTimeout(function(){location.href="am"} , 1000);
											} else { 
												$('#sukses').hide('fast');
												$('#gagal').fadeIn('fast');
												$("#saveupdate").html('Save');
												$("#progresloader").fadeOut('fast');
											}
										},
										error: function (error) {
											alert('error; ' + eval(error));
										},
										contentType: false,
										processData: false
									});
									return false;
								});
								</script>
							</div>
						</div>
					</div>
					<!-- END MODAL UPDATE -->
					
					<!-- MODAL DELETE -->
					<div class="modal fade" id="delete" tabindex="-1" role="dialog">
						<div class="modal-dialog" role="document" style="top:25%;">
							<div class="modal-content">
								<div class="modal-header bg-blue">
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
							url: "<?PHP echo base_url(); ?>core/deleteam",
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
									setTimeout(function(){location.href="am"} , 1000);
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
$(document).ready(function() {
	$('.input-daterange input').each(function() {
		$(this).datepicker();
	});
	var tblog = $('#tbllog').DataTable({
		"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
		"bProcessing": true,
		"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatalog",
		"columnDefs": [ {
            "searchable": false,
            "orderable": false,
            "targets": 0
        } ],
        "order": [[ 1, 'desc' ]]
	});
	tblog.on( 'order.dt search.dt', function () {
        tblog.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();
	$(document).on('click', '#filter_log', function(e){
		var start 	= $('#start').val();
		var end 	= $('#end').val();
		var witel 	= $('#select2-s_witel-container').html();
		var treg 		= $('#select2-s_treg-container').html();
		var newUrl = "<?PHP echo base_url(); ?>core/getdatalog?start="+start+"&treg="+treg+"&end="+end;
		tblmap.ajax.url(newUrl).load();
		//alert(newUrl);
		//tblog.fnReloadAjax(newUrl);
	});	
	$('#tbllog tfoot th').each( function () {
		var title = $(this).text();
		if(title=='act' || title=='No'){
			$(this).html('');				
		}else{
			$(this).html( '<input type="text" placeholder="Search '+title+'" />' );				
		}
	} );
	tblog.columns().every( function () {
		 var that = this;
		 $( 'input', this.footer() ).on( 'keyup change', function () {
			 if ( that.search() !== this.value ) {
				 that
					 .search( this.value )
					 .draw();
			 }
		 } );
	} );
	$(document).on('click', '.btnupdateM', function(e){
		// alert('data');
		e.preventDefault();
		
		var uid = $(this).data('id'); // get id of clicked row
		
		$('#dynamic-content').hide(); // hide dive for loader
		$('#modal-loader').show();  // load ajax loader
		
		// alert(uid);
		
		$.ajax({
			url: '<?PHP echo base_url(); ?>core/modalam',
			type: 'POST',
			data: 'id='+uid,
			dataType: 'json'
		})
		.done(function(data){
			console.log(data);
			$('#dynamic-content').hide(); // hide dynamic div
			$('#dynamic-content').show(); // show dynamic div
			$('#ed_nik_am2').html(data.nik_am);
			$('#ed_id_am').val(data.id_am);
			$('#ed_nik_am').val(data.nik_am);
			$('#ed_nama_am').val(data.nama_am);
			$('#ed_posisi').val(data.posisi);
			$('#ed_band').val(data.band);
			$('#modal-loader').hide();    // hide ajax loader
		})
		.fail(function(){
			$('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please refresh page...');
		});
		
	});
});

$(document).on('click', '.btndeleteMenu', function(e){
	// alert('data');
	e.preventDefault();
	
	var id = $(this).data('id'); // get id of clicked row
	
	$('#dynamic-content').hide(); // hide dive for loader
	$('#modal-loader').show();  // load ajax loader
	
	$.ajax({
		url: '<?PHP echo base_url(); ?>core/modalam',
		type: 'POST',
		data: 'id='+id,
		dataType: 'json'
	})
	.done(function(data){
		// console.log(data);
		$('#dynamic-content').hide(); // hide dynamic div
		$('#dynamic-content').show(); // show dynamic div
		$('#namedelfac').html(data.nama_am);
		$('#iddelfac').val(data.id_am);
		$('#iddelfac').val(data.id_am);
		$('#modal-loader').hide();    // hide ajax loader
	})
	.fail(function(){
		$('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
	});
	
});
</script>
<?PHP $this->load->view('/theme/panel/plugin_js'); ?>