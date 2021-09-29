<?PHP
$data		= array_shift($getDataSite);
$name		 = $data['name_site'];
$logo		 = $data['logo'];
$mail		 = $data['mail_site'];
$alamat		 = $data['alamat'];
$phone		 = $data['phone'];
$maps		 = $data['maps'];
?>
<style>
.mr1rem { margin-left: -1rem; }
.sizemidle {
	max-width: auto!important;
    max-height: 140px!important;
}
.kt-invoice-1 .kt-invoice__wrapper .kt-invoice__head .kt-invoice__container .kt-invoice__logo {
	padding-top: 5rem!important;
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

	<div class="kt-portlet">
		<div class="kt-portlet__body kt-portlet__body--fit">
			<div class="kt-invoice-1" id="update">
				<div class="kt-invoice__wrapper">
					<div class="kt-invoice__head" style="background-image: url('https://parwatha.com/theme/assets/media/bg/bg-2.jpg');">
						<div class="kt-invoice__container kt-invoice__container--centered">
							<div class="kt-invoice__logo">
								<a href="#">
                                    <div class="mr1rem"><img src="<?PHP echo base_url(); ?>images/" class="avatar kt-marginless sizemidle" alt="photo"></div>
									<!--h1 id="nama_lengkap"></h1-->
								</a>
								<a href="#">
									<img src="<?PHP echo base_url(); ?>images/logo-pwt.png" style="max-height: 50px;">
								</a>
							</div>
							<span class="kt-invoice__desc text-left">
								<span><i class="fa fa-envelope"></i> Email : <span id="mailbase"></span></span>
								<span><i class="fa fa-map-marker-alt"></i> Alamat : <span id="alamatex"></span></span>
							</span>
						</div>
					</div>
					<div class="kt-invoice__body kt-invoice__body--centered">
						<form class="kt-form kt-form--label-right" id="forminsert" enctype="multipart/form-data">
							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Site Name *</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="text" name="name" class="form-control" id="name" placeholder="Site Name" value="<?PHP echo $name; ?>">
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Logo</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="file" name="upl" class="form-control" id="logo">
									</div>
									<span class="form-text text-muted">Kosongkan jika tidak akan merubah Logo.</span>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Default Email *</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<select name="mail" class="form-control" id="mail" placeholder="Default Email">
											<?PHP foreach ($getDataEmail as $email) { ?>
												<option <?PHP if($mail==$email['id_email']) { echo "selected"; } ?> value="<?PHP echo $email['id_email']; ?>"><?PHP echo $email['email']; ?></option>
											<?PHP } ?>
										</select>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Phone *</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="text" name="phone" class="form-control" id="phone" placeholder="Phone" value="<?PHP echo $phone; ?>">
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Alamat *</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<textarea name="alamat" class="form-control" id="alamat" placeholder="Alamat" required style="min-height: 150px;"><?PHP echo $alamat; ?></textarea>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Embed Google Maps *</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<textarea name="maps" class="form-control" id="maps" placeholder="Embed Google Maps" style="min-height: 150px;"><?PHP echo $maps; ?></textarea>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Facebook</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="text" name="facebook" class="form-control" id="facebook" placeholder="facebook.com/youraccount">
									</div>
									<span class="form-text text-muted">Kosongkan jika tidak ingin menampilkan link akun Facebook.</span>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Twitter</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="text" name="twitter" class="form-control" id="twitter" placeholder="twitter.com/youraccount">
									</div>
									<span class="form-text text-muted">Kosongkan jika tidak ingin menampilkan link akun Twitter.</span>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Youtube</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="text" name="youtube" class="form-control" id="youtube" placeholder="https://www.youtube.com/channel/yourchannel">
									</div>
									<span class="form-text text-muted">Kosongkan jika tidak ingin menampilkan link akun Youtube.</span>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Instagram</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="text" name="instagram" class="form-control" id="instagram" placeholder="instagram.com/youraccount">
									</div>
									<span class="form-text text-muted">Kosongkan jika tidak ingin menampilkan link akun Instagram.</span>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Showreel</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="text" name="showreel" class="form-control" id="showreel" placeholder="https://www.youtube.com/yourvideocode">
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12"></label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<strong><i>Last updated by <span id="updateby"></span> <br> <span id="updatedate"></span></strong>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12"></label>
								<div class="col-lg-7 col-md-9 col-sm-12 text-right">
									<button type="button" class="btn btn-secondary" id='cancelupdate'>Cancel</button>
									<button type="submit" id="saveupdate" class="btn btn-primary">Save</button>
								</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end:: Content -->