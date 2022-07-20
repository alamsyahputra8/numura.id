<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
<link href="https://parwatha.com/polo/css/style.css" rel="stylesheet">
<link href="https://parwatha.com/polo/css/responsive.css" rel="stylesheet">
<?PHP
$q 				= "
				select * from (
					select a.*, 
						(select count(*) from menu_site where parent=a.id_menu) as jmlsub 
					from menu_site a 
					where style='works' order by sort asc
				) as final
				where 1=1 and jmlsub<1 order by sort
				";
$getDataMenu	= $this->query->getDatabyQ($q);

$qServ 			= "
				select * from (
					select a.*, 
						(select count(*) from menu_site where parent=a.id_menu) as jmlsub 
					from menu_site a 
					where style='services' order by sort asc
				) as final
				where 1=1 and jmlsub<1 order by sort
				";
$getDataService	= $this->query->getDatabyQ($qServ);

$qAlbum 		= "
				select * from album where 1=1 order by id_album desc
				";
$getDataAlbum	= $this->query->getDatabyQ($qAlbum);
?>
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
	<div id="gagalinsert" class="alert alert-warning alert-elevate kt-hidden" role="alert" style="z-index: 1!important;">
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

	<div id="suksesinsert" class="alert alert-success fade show kt-hidden" role="alert" style="z-index: 1!important;">
		<div class="alert-icon"><i class="flaticon-black"></i></div>
		<div class="alert-text">Success!</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div id="suksesdelete" class="alert alert-secondary fade show kt-hidden" role="alert" style="z-index: 1!important;">
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
						<?PHP echo getRoleInsert($akses,'addnewfac','Add New Videos');?>
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
									<h3 class="kt-portlet__head-title">Add New Videos</h3>
								</div>
							</div>
							<div class="kt-portlet__body">
								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Album *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<select name="album" class="form-control select2norm" id="album" placeholder="Album" style="width: 100%;">
												<option value="">-- Choose Album --</option>
												<?PHP foreach ($getDataAlbum as $data) { ?>
												<option value="<?PHP echo $data['id_album']; ?>"><?PHP echo $data['title']; ?></option>
												<?PHP } ?>
											</select>
										</div>
									</div>
								</div>

								<!--div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Menu *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<select name="menu" class="form-control select2norm" id="menu" placeholder="Menu" style="width: 100%;">
												<option value="">-- Choose Menu --</option>
												<?PHP foreach ($getDataMenu as $data) { ?>
												<option value="<?PHP echo $data['id_menu']; ?>"><?PHP echo $data['menu']; ?></option>
												<?PHP } ?>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-3 col-sm-12">Service *</label>
									<div class="col-lg-4 col-md-9 col-sm-12">
										<div class='input-group'>
											<select name="service" class="form-control select2norm" id="service" placeholder="Service" style="width: 100%;">
												<option value="">-- Choose Service --</option>
												<?PHP foreach ($getDataService as $data) { ?>
												<option value="<?PHP echo $data['id_menu']; ?>"><?PHP echo $data['menu']; ?></option>
												<?PHP } ?>
											</select>
										</div>
									</div>
								</div-->
								
								<div class="kt-separator kt-separator--border-dashed"></div>
								<div id="p_scents">
									<div class="form-group row">
										<label class="col-form-label col-lg-3 col-sm-12">Link Youtube Video *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="text" name="videos[]" class="form-control" id="videos" placeholder="https://www.youtube.com/watch?v=_5VXNm_i8eE">
												<button type="button" class="btn btn-sm btn-secondary addScnt" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title="Add More File"><i class="fa fa-plus"></i></button>
											</div>
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

			<section id="page-content" class="" style="padding-top: 0px;">
	            <div class="container">
	            	<!-- Portfolio Filter -->
	                <nav class="grid-filter gf-outline" data-layout="#portfolio">
	                    <ul>
	                        <li class="active"><a href="#" data-category="*">Show All</a></li>
	                        <?PHP
	                        $gAlbum 	= $this->query->getDatabyQ($qAlbum);
	                        foreach ($gAlbum as $album) {
	                        	$jmlData= $this->query->getNumRows('videos','*',"where id_album='".$album['id_album']."'")->num_rows();
	                        ?>
	                        <li><a href="#" data-category=".ct-foto<?PHP echo $album['id_album']; ?>"><?PHP echo $album['title']; ?> - <?PHP echo $jmlData; ?> Video</a></li>
	                        <?PHP } ?>
	                    </ul>
	                </nav>
	                <!-- end: Portfolio Filter -->
	                <br><div class="kt-separator kt-separator--border-dashed"></div><br>

	                <!-- Portfolio -->
	                <div id="portfolio" class="grid-layout portfolio-4-columns" data-margin="20" style="min-height: 300px!important;">
		                <!--div id="bgportfolio">
		                	<div class="loaderport" style="min-height: 250px;"></div>
		                </div-->
		                <?PHP
		                $dataRolesAll		= $this->query->getData('videos','*',"ORDER BY id_video DESC");
						$x 					= 0;

						$cek				= $this->query->getNumRows('videos','*',"")->num_rows();

						if ($cek>0) {
							//echo '<div id="portfolio" class="grid-layout portfolio-4-columns" data-margin="20">';
							foreach($dataRolesAll as $data) { $x++;
								$id 		= $data['id_video'];
								$video 		= $data['video'];
								$embed 		= str_replace('https://www.youtube.com/watch?v=','https://www.youtube.com/embed/',$video);
								$fileembed	= str_replace('https://www.youtube.com/embed/','https://www.youtube.com/watch?v=',$video);

								echo '
				                    <div class="portfolio-item large-width img-zoom ct-video'.$data['id_album'].'">
				                       <div class="portfolio-item-wrap">
				                            <div class="portfolio-image">
				                                <a href="#">
				                                    <iframe width="1280" height="720" src="'.$embed.'?rel=0&amp;showinfo=0" allowfullscreen></iframe>
				                                </a>
				                            </div>
				                            <div class="portfolio-description">
				                                <a title="Video Youtube" data-lightbox="iframe" href="'.$fileembed.'"><i class="fa fa-play"></i></a>
				                                <a href="'.$video.'" target="_blank"><i class="fa fa-link"></i></a>
				                                <a title="Delete" class="btndeleteMenu" data-toggle="modal" data-target="#delete" data-id="'.$id.'">
				                                    <i class="fa fa-times"></i>
				                                </a>
				                            </div>
				                        </div>
				                    </div>
								';
							}
							//echo '</div>';
						} else {
							echo '
								<div class="row" style="padding: 20px;">
			                        <div class="col-sm-12">
			                            <div><center><img src="'.base_url().'images/icon/notfound.png"></center></div><br>
			                            <h5 class="text-center">Anda Belum Memiliki Data Tersimpan Di Website Anda</h5>
			                            </h6><center>Silahkan buat data baru</center></h6><br>
			                        </div>
			                    </div>
			                ';
						}
		                ?>
	            	</div>
	                
	            </div>
	        </section>
	        <!-- end: Content -->

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
										<label class="col-form-label col-lg-3 col-sm-12">Document Name *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<input type="hidden" name="ed_id" class="form-control" id="ed_id">
												<input type="text" name="ed_document" class="form-control" id="ed_document" placeholder="Document Name">
											</div>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-form-label col-lg-3 col-sm-12">Menu *</label>
										<div class="col-lg-4 col-md-9 col-sm-12">
											<div class='input-group'>
												<select name="ed_menu" class="form-control select2norm" id="ed_menu" placeholder="Menu" style="width: 100%;">
													<option value="">-- Choose Menu --</option>
													<?PHP foreach ($getDataMenu as $data) { ?>
													<option value="<?PHP echo $data['id_menu']; ?>"><?PHP echo $data['menu']; ?></option>
													<?PHP } ?>
												</select>
											</div>
										</div>
									</div>
									
									<div id="ed_p_scents">
										<div id="eksDoc"></div>

										<div class="kt-separator kt-separator--border-dashed"></div>
										<div class="form-group row">
											<label class="col-form-label col-lg-3 col-sm-12"></label>
											<div class="col-lg-4 col-md-9 col-sm-12">
												<div class='input-group'>
													<button type="button" class="btn col-lg-12 btn-sm btn-secondary ed_addScnt" data-toggle="kt-tooltip" title="" data-html="true" data-content="" data-original-title="Add More File Document"><i class="fa fa-plus"></i> Add More File Document</button>
												</div>
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
<!--Plugins-->
<!--script src="<?PHP echo base_url(); ?>assets/polo/js/jquery.js"></script-->
<script src="https://parwatha.com/polo/js/plugins.js"></script>

<!--Template functions-->
<script src="https://parwatha.com/polo/js/functions.js"></script>