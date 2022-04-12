<?PHP
$activepage	= $this->uri->uri_string();
if ($activepage!='panel') {
	$q 			= "select menu from menu where url='$activepage'";
	$getMenu 	= $this->query->getDatabyQ($q);
	$dataMenu	= array_shift($getMenu);
	$menuname	= $dataMenu['menu'];
} else {
	$menuname	= 'Dashboard';
}
?>
<!DOCTYPE html>

<html lang="en">

	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
		<title><?PHP echo $menuname; ?> | Parwatha Panel</title>
		<meta name="description" content="Updates and statistics">
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
		<style>
			@font-face {
			    font-family:Flaticon; src: url(<?PHP echo base_url(); ?>assets/metronic/Flaticon.woff) format("woff");
			    font-family:Flaticon2; src: url(<?PHP echo base_url(); ?>assets/metronic/Flaticon2.woff) format("woff");
			    font-family:FontAwesome; src: url(<?PHP echo base_url(); ?>assets/metronic/line-awesome.woff) format("woff");
			    font-family:FontAwesome; src: url(<?PHP echo base_url(); ?>assets/metronic/line-awesome.woff2) format("woff2");
			    font-family:Flaticon; src: url(<?PHP echo base_url(); ?>assets/metronic/Flaticon.ttf) format("truetype");
			    font-family:Flaticon2; src: url(<?PHP echo base_url(); ?>assets/metronic/Flaticon2.ttf) format("truetype");
			    font-family:FontAwesome; src: url(<?PHP echo base_url(); ?>assets/metronic/line-awesome.ttf) format("truetype");
			}
		</style>

		<!--end::Fonts -->

		<!--begin::Page Vendors Styles(used by this page) -->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/vendors/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/vendors/custom/datatables/datatables.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/vendors/custom/fullcalendar/fullcalendar.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

		<!--end::Page Vendors Styles -->

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

		<style>
			.modal-lg, .modal-xl {
			    max-width: 80%!important;
			}
		</style>
	</head>

	<!-- end::Head -->
	
	<!-- begin::Body -->
	<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">