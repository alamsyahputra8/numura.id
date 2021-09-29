<?PHP
$token 	= @$_GET['token'];
$pbs 	= @$_GET['pbs'];
?>
<!DOCTYPE html>

<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>ISR-M2M | Set Password</title>
		<meta name="description" content="Set Password | ISR M2M">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!--begin::Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700">

		<!--end::Fonts -->

		<!--begin::Page Custom Styles(used by this page) -->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/css/pages/login/login-2.css" rel="stylesheet" type="text/css" />

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
		<link rel="shortcut icon" href="<?PHP echo base_url(); ?>images/favicon.ico" />

		<style>
			.kt-login.kt-login--v2 .kt-login__wrapper .kt-login__container .kt-form .kt-login__actions .kt-login__btn-primary {
			    border-color: #942017;
			    background: #b63127;
			}
			.kt-login.kt-login--v2 .kt-login__wrapper .kt-login__container .kt-form .kt-login__actions .kt-login__btn-primary:hover {
			    border-color: #c73f35;
			}
			.kt-login.kt-login--v2 .kt-login__wrapper .kt-login__container .kt-form .form-control {
				background: rgba(29, 29, 29, 0.4);
			}
		</style>
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Page -->
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v2 kt-login--signin" id="kt_login">
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">
					<div class="kt-grid__item kt-grid__item--fluid kt-login__wrapper">
						<div class="kt-login__container">
							<div class="kt-login__logo">
								<a href="#">
									<img src="<?PHP echo base_url(); ?>images/logoisrm2m-b.png">
								</a>
							</div>
							<div class="kt-login__signin">
								<div class="kt-login__head">
									<h3 class="kt-login__title text-dark">Set Password</h3>
									<div class="kt-login__desc text-dark">Enter your username and password to set your new password:</div>
								</div>
								<form method="post" class="kt-form">
									<div class="input-group">
										<input type="hidden" name="token" id="token" value="<?PHP echo $token; ?>">
										<input type="hidden" name="pbs" id="pbs" value="<?PHP echo $pbs; ?>">
										<input class="form-control" type="text" placeholder="Username" name="username" id="kt_username" autofocus autocomplete="off">
									</div>
									<div class="input-group">
										<input class="form-control" type="password" placeholder="Password" name="password" id="kt_password">
									</div>
									<div class="kt-login__actions">
										<button id="kt_login_forgot_submit" class="btn btn-pill kt-login__btn-primary">Save</button>
									</div>
								</form>
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
						"label": [
							"#c5cbe3",
							"#a1a8c3",
							"#3d4465",
							"#3e4466"
						],
						"shape": [
							"#f0f3ff",
							"#d9dffa",
							"#afb4d4",
							"#646c9a"
						]
					}
				}
			};
		</script>

		<!-- end::Global Config -->

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/plugins/global/plugins.bundle.js" type="text/javascript"></script>
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/js/scripts.bundle.js" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Scripts(used by this page) -->
		<!-- <script src="<?PHP echo base_url(); ?>assets/theme/assets/js/pages/custom/login/login-general.js" type="text/javascript"></script> -->
		<?PHP $this->load->view('theme/metronic/plugin/setpasswordjs'); ?>

		<!--end::Page Scripts -->
	</body>

	<!-- end::Body -->
</html>