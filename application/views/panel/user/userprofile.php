<style>
.sizemidle {
	max-width: 140px!important;
    max-height: 140px!important;
}
.kt-invoice-1 .kt-invoice__wrapper .kt-invoice__head .kt-invoice__container .kt-invoice__logo {
	padding-top: 5rem!important;
}
.bghead {
    background: url('<?PHP echo base_url(); ?>images/sidebar.png') no-repeat!important;
    background-size: 100% auto!important;
    background-position: 0px 30%!important;
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
					<div class="kt-invoice__head bghead">
						<div class="kt-invoice__container kt-invoice__container--centered">
							<div class="kt-invoice__logo">
								<a href="#">
									<div class="kt-user-card-v2">
		                                <div class="kt-user-card-v2__pic" style="margin-bottom: 1rem;">
		                                    <center><img src="<?PHP echo base_url(); ?>images/user/" class="avatar m-img-rounded kt-marginless sizemidle" alt="photo"></center>
		                                </div>
		                            </div>
									<h1 id="nama_lengkap"></h1>
								</a>
							</div>
							<span class="kt-invoice__desc text-left">
								<span><i class="fa fa-envelope-square"></i> Email : <span id="email"></span></span>
								<span><i class="fa fa-user-circle"></i> Userid : <span id="uid"></span></span>
							</span>
						</div>
					</div>
					<div class="kt-invoice__body kt-invoice__body--centered">
						<form class="kt-form kt-form--label-right" id="forminsert" enctype="multipart/form-data">
							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Name *</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type='hidden' name='userid' id='userid' value=''>
										<input type="text" name="name" class="form-control" id="name" placeholder="Full Name">
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Email *</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="email" name="email" class="form-control" id="emailinput" placeholder="your@email.com">
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Username *</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="text" name="username" class="form-control" id="username" placeholder="Username">
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Password</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="password" name="password" class="form-control" id="password" placeholder="Password">
									</div>
									<span class="form-text text-muted">Kosongkan jika tidak akan merubah password.</span>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-form-label col-lg-3 col-sm-12">Upload New Photo</label>
								<div class="col-lg-7 col-md-9 col-sm-12">
									<div class='input-group'>
										<input type="file" name='upl' id='upl' class="file-upload">
									</div>
									<span class="form-text text-muted">Kosongkan jika tidak akan merubah foto.</span>
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