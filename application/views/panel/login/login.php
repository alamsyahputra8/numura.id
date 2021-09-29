<!DOCTYPE html>
<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>Numura.id | Login Page</title>
		<meta name="description" content="Numura.id | Kaos Anak Custom">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
		</script>

		<!--end::Fonts -->

		<!--begin::Page Custom Styles(used by this page) -->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/app/custom/login/login-v6.default.css" rel="stylesheet" type="text/css" />

		<!--end::Page Custom Styles -->

		<!--begin::Global Theme Styles(used by all pages) -->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/vendors/base/vendors.bundle.rtl.css" rel="stylesheet" type="text/css" />-->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/header/base/light.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/header/base/light.rtl.css" rel="stylesheet" type="text/css" />-->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/header/menu/light.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/header/menu/light.rtl.css" rel="stylesheet" type="text/css" />-->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/brand/dark.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/brand/dark.rtl.css" rel="stylesheet" type="text/css" />-->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/aside/dark.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/aside/dark.rtl.css" rel="stylesheet" type="text/css" />-->

		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="<?PHP echo base_url(); ?>images/favnumura.png" />

		<style>
			.bgvideo {
				position: fixed;
			    z-index: 1;
			    opacity: 0.5;
			    left:0;
			    bottom:0;
			    width: 100%;
			}
			.kt-login.kt-login--v6 .kt-login__aside {
			    z-index: 2;
			    position: relative;
			}
			.kt-login__logo img {
			    max-height: 3.5vw;
			    margin-top: 2vw;
			    opacity: 0.7;
			}
			.titlelog {
				font-weight: bold;
				margin-bottom: -0.5vw;
				color: #505050;
				text-align: left;
			}
			.titlelog span { font-weight: 300; }
			.logotitle {margin-top: 6.5vw;}
			.kt-login__title {
			    color: #9a9a9a!important;
			    font-size: 1.2vw!important;
			    letter-spacing: 0.1vw;
			}
			.kt-login.kt-login--v6 {
			    background: transparent;
			}
			.kt-login.kt-login--v6 .kt-login__aside { background: transparent; }
			body {
				/*background: url('<?PHP echo base_url(); ?>images/bglogin.png') no-repeat center right; background-size: 50% auto;*/
				background: #FFF;
			}
			.btn-success { background: #037482; border-color: #037482; }
			.btn-success:hover { background: #025863; border-color: #025863; }
			.alert.alert-danger { background: #ad3838; }
			/*.kt-login.kt-login--v6 .kt-login__aside .kt-login__wrapper .kt-login__container .kt-login__form .form-control { background: transparent; color: #FFF; }*/
			.kt-login.kt-login--v6 .kt-login__aside .kt-login__wrapper .kt-login__container .kt-login__body {margin-bottom: -6.5vw;}
			@media (max-width: 1024px) {
				html, body {
				    /*background: #FFF url('<?PHP echo base_url(); ?>images/bgsbr.png') no-repeat center!important;*/
				    background: #FFF;
				    background-size: cover!important;
				    background-position: 70% 0vw!important;
				}
				.titlelog { 
					font-size: 3.7rem;
					margin-bottom: -1rem;
				}
				.kt-login.kt-login--v6 .kt-login__aside .kt-login__wrapper .kt-login__container { width: 360px; }
				.kt-login.kt-login--v6 { background:rgba(255,255,255,.6); }
				.kt-login__title {font-size: 1.3rem!important; }
				.kt-login__logo img { max-height: 3rem; margin-top:5rem;}
			}
			#pass-status {
				position: absolute;
			    top: 1.5vw;
			    right: 0px;
			    cursor: pointer;
			}
			.kt-login.kt-login--v6 .kt-login__aside .kt-login__wrapper .kt-login__container {
				width: 100%;
			}
			/*@font-face {
			  font-family: '<?PHP echo base_url(); ?>fonts/AmstirdamDemo';
			  src: url('AmstirdamDemo.ttf')  format('truetype'), 
			}*/
		</style>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Page -->
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v6 kt-login--signin" id="kt_login">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
					<div class="kt-grid__item  kt-grid__item--order-tablet-and-mobile-2  kt-grid kt-grid--hor kt-login__aside">
						<div class="kt-login__wrapper">
							<div class="kt-login__container">
								<div id="bglogincek" class="kt-login__body">
									<div class="text-center logotitle">
										<!-- <a href="#"> -->
											<h1 class="titlelog"><span>Numura.id</span></h1>
										<!-- </a> -->
									</div>
									<div class="kt-login__signin">
										<div class="kt-login__head">
											<h3 class="kt-login__title text-left"><i>Sign into your account</i></h3>
										</div>
										<div class="kt-login__form">
											<form class="sycerdaslogin kt-form" action="">
												<div class="form-group">
													<input class="form-control" type="text" placeholder="Username" name="username" autofocus autocomplete="off" id="username">
												</div>
												<div class="form-group">
													<input class="form-control" type="password" placeholder="Password" name="password" id="password">
													<i id="pass-status" class="fa fa-eye" aria-hidden="true"></i>
												</div>
												<div class="kt-login__actions text-left">
													<button id="kt_login_signin_submit" class="btn btn-dark btn-sm btn-elevate">Sign In</button>
													<br><br>
													<!-- <div class="kt-login__desc">
														<a href="javascript:;" id="kt_login_forgot" class="text-success">Activate account using telegram</a>
													</div> -->
												</div>
											</form>
										</div>
									</div>

									<div class="kt-login__signup">
										<div class="kt-login__head">
											<h3 class="kt-login__title text-left"><i>Two Factor Authentication</i></h3>
											<div class="kt-login__desc text-left">Untuk melanjutkan proses login, kami mengirimkan Kode OTP ke akun Telegram Anda. Silakan masukkan kode OTP</div>
										</div>
										<div class="kt-login__form">
											<form class="kt-form" id="sendotp">
												<div class="form-group">
													<input type="hidden" name="username" id="userotp">
													<input type="hidden" name="password" id="passotp">
													<input class="form-control" type="text" placeholder="Masukan OTP" id="otp" name="otp" autocomplete="off">
													<br>
													<div class="kt-login__desc">
														<a href="javascript:;" id="resendotp">Kirim ulang kode OTP</a>
													</div>
												</div>
												<div class="kt-login__actions text-left">
													<button id="kt_login_signup_submit" class="btn btn-success btn-elevate">Submit OTP</button>
													<!-- <button id="kt_login_signup_cancel" class="btn btn-outline-brand btn-pill">Cancel</button> -->
												</div>
											</form>
										</div>
									</div>

									<div class="kt-login__forgot">
										<div class="kt-login__head">
											<h3 class="kt-login__title text-left"><i>Activate account using telegram</i></h3>
											<!-- <div class="kt-login__desc">Enter your email to reset your password:</div> -->
										</div>
										<div class="kt-login__form">
											<div class="col-lg-12">
												<center class="text-left"><b>Registrasi ID ke TFA NITS</b></center><br>
												<li>Add akun bot <b>TFA NITS</b> melalui Telegram Anda dengan username <b>@TFA_NITS_Bot</b> atau bisa melalui link <a href="https://t.me/TFA_NITS_Bot" target="_blank" class="text-success"><b>disini</b></a>.</li>
												<li>Setelah itu, klik atau ketik <span class="text-success"><b>/start</b></span></li>
												<li>Lalu, klik atau ketik <span class="text-success"><b>/register</b></span></li>
												<li>Masukan username Anda.</li>
												<li>Selesai</li>
											</div>
											<form class="kt-form" action="">
												<div class="kt-login__actions text-left">
													<!-- <button id="kt_login_forgot_submit" class="btn btn-brand btn-pill btn-elevate">Request</button> -->
													<button id="kt_login_forgot_cancel" class="btn btn-outline-success">Back to Sign In</button>
												</div>
											</form>
										</div>
									</div>

									<div class="kt-login__logo">
										<!-- <a href="#">
											<img src="<?PHP echo base_url(); ?>images/logotel-w.png">
										</a> -->
									</div>
								</div>
							</div>
							<div class="kt-login__account">
								<!--span class="kt-login__account-msg">
									Don't have an account yet ?
								</span>&nbsp;&nbsp;
								<a href="javascript:;" id="kt_login_signup" class="kt-login__account-link">Sign Up!</a><br-->
								<!-- <a href="javascript:;" id="kt_login_forgot">Forget Password ?</a> -->
							</div>
						</div>
					</div>
					<div class="kt-grid__item kt-grid__item--fluid kt-grid__item--center kt-grid kt-grid--ver kt-login__content" style="background: transparent; overflow:hidden;">
						<div class="kt-login__section" style="z-index: 2;">
							<div class="kt-login__block">
								<h3 class="kt-login__title"></h3>
								<div class="kt-login__desc">
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- end:: Page -->

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5d78ff",
						"dark": "#282a3c",
						"light": "#ffffff",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
						"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Scripts(used by this page) -->
		<!-- <script src="<?PHP echo base_url(); ?>assets/metronic/app/base/login-general.js" type="text/javascript"></script> -->
		<!--script src="<?PHP echo base_url(); ?>assets/metronic/app/base/login.js" type="text/javascript"></script-->
		<?PHP $this->load->view('theme/metronic/plugin/loginjs'); ?>

		<!--end::Page Scripts -->

		<!--begin::Global App Bundle(used by all pages) -->
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/app/bundle/app.bundle.js" type="text/javascript"></script>

		<!--end::Global App Bundle -->
	</body>

	<!-- end::Body -->
</html>