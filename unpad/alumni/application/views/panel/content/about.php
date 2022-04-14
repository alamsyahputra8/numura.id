<?PHP 
$this->load->view('/theme/panel/plugin'); 
error_reporting(0);

foreach ($getData as $data) {
	$title		 = $data['title'];
	$sub		 = $data['sub'];
	$pict		 = $data['picture'];
	$content	 = $data['content'];
	$by			 = $data['name'];
	$update_date = $data['update_date'];
}
?>
		
<div class="content-wrapper" id="pageContent">
	<!-- Main content -->
	<div class="content"><br>
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div class="panel panel-bd">
					<div class="panel-heading">
						<div class="panel-title">
							<h4 class="text-center">About Us</h4>
						</div>
						<div class="clearfix"></div>
					</div>
					
					<form id="formupdate"  enctype="multipart/form-data">
					<div class="panel-body">
						<div class="col-md-10">
							<div class="form-group row">
								<label for="title" class="col-sm-2 col-form-label">Title</label>
								<div class="col-sm-10">
									<input type="text" name="title" class="form-control" id="title" placeholder="Title" required value="<?PHP echo $title; ?>">
								</div>
							</div>
							
							<div class="form-group row">
								<label for="sub" class="col-sm-2 col-form-label">Sub Title</label>
								<div class="col-sm-10">
									<input type="text" name="sub" class="form-control" id="sub" placeholder="Sub Title" required value="<?PHP echo $sub; ?>">
								</div>
							</div>
							
							<div class="form-group row">
								<label for="pict" class="col-sm-2 col-form-label">Picture</label>
								<div class="col-sm-10">
									<div id="picteksis" style="background:rgba(0,0,0,0.8); padding:10px;">
										<center><img src="<?PHP echo base_url(); ?>images/content/<?PHP echo $pict; ?>" style="width: 100%;"></center>
									</div><br>
									<input type="file" name="pict" class="form-control" id="pict" placeholder="Picture" >
									<i style="font-size:12px;">* Kosongkan jika tidak akan merubah gambar</i>
								</div>
							</div>
							
							<div class="form-group row">
								<label for="content" class="col-sm-2 col-form-label">Content</label>
								<div class="col-sm-10">
									<textarea name="content" class="some-textarea form-control" id="content" placeholder="Content" style="min-height: 150px;"><?PHP echo $content; ?></textarea>
								</div>
							</div>
							
							<div class="form-group row text-right">
								<strong><i>Last updated by <?PHP echo $by; ?> - <?PHP echo $update_date; ?></strong>
							</div>
						</div>
						<div class="clearfix"></div>
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
					<div class="panel-footer text-right">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button id="saveupdate" type="submit" class="btn btn-primary">Save</button>
					</div>
					</form>
					
					<script>
					$('#formupdate').submit(function() {
						
						var formdata = new FormData(this);
						
						$.ajax({
							url: "<?PHP echo base_url(); ?>core/updateabout",
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
									setTimeout(function(){location.href="about"} , 1000);
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
				<div class="clearfix"></div>
				<div class="md-overlay"></div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<?PHP $this->load->view('/theme/panel/plugin_js'); ?>