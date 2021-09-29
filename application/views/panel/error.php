<!DOCTYPE html>

<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title>Error</title>
		<meta name="description" content="">
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
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/app/custom/error/error-v6.default.css" rel="stylesheet" type="text/css" />

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
		<link rel="shortcut icon" href="<?PHP echo base_url(); ?>images/favico.png" />
	</head>

	<!-- end::Head -->

	<!-- begin::Body -->
	<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

		<!-- begin:: Page -->
		<div class="kt-grid kt-grid--ver kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid  kt-error-v6" style="background-image: url(<?PHP echo base_url(); ?>assets/theme/assets/media//error/bg6.jpg);">
				<div class="kt-error_container">
					<div class="kt-error_subtitle kt-font-light">
						<h1>Oops...</h1>
					</div>
					<p class="kt-error_description kt-font-light">
						Looks like something went wrong.<br>
						We're working on it
					</p>
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

		<!--begin::Global App Bundle(used by all pages) -->
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/app/bundle/app.bundle.js" type="text/javascript"></script>

		<!--end::Global App Bundle -->
	</body>

	<!-- end::Body -->
</html>