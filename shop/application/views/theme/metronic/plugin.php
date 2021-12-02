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
		<title><?PHP echo $menuname; ?> | Numura.id</title>
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
		<link href="<?PHP echo base_url(); ?>assets/theme/style.bundle.css?v=7.2.9" type="text/css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/base/style.bundle.rtl.css" rel="stylesheet" type="text/css" />-->

		<!--end::Global Theme Styles -->

		<!--begin::Layout Skins(used by all pages) -->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/header/base/light.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/header/base/light.rtl.css" rel="stylesheet" type="text/css" />-->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/header/menu/light.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/header/menu/light.rtl.css" rel="stylesheet" type="text/css" />-->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/brand/light.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/brand/dark.rtl.css" rel="stylesheet" type="text/css" />-->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/aside/light.css" rel="stylesheet" type="text/css" />

		<!--RTL version:<link href="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/skins/aside/dark.rtl.css" rel="stylesheet" type="text/css" />-->


		<!--end::Layout Skins -->
		<link rel="shortcut icon" href="<?PHP echo base_url(); ?>images/favnumura.png" />

		<style>
			@media (min-width: 1025px) {
				.kt-header--fixed.kt-subheader--fixed.kt-subheader--enabled .kt-wrapper {
				    padding-top: 59px;
				}
				.kt-aside__brand .kt-aside__brand-tools svg g [fill] {
				    fill: #FFF;
				}
				.kt-aside--minimize .kt-aside-menu .kt-menu__nav {
				    padding-top: 0px;
				}
			}
			body {background: #EEF0F8;}
			.modal-lg, .modal-xl {
			    max-width: 80%!important;
			}
			.form-control[readonly] {
			    background-color: #f7f8fa!important;
			}
			@media (max-width: 1024px) {
				.kt-header-mobile {
				    background-color: #FFF;
				    -webkit-box-shadow: 0px 1px 9px -3px rgba(0, 0, 0, 0.1);
				    box-shadow: 0px 1px 9px -3px rgba(0, 0, 0, 0.1);
				}
				.kt-header-mobile .kt-header-mobile__toolbar .kt-header-mobile__toggler span {
				    background: rgba(0, 0, 0, 0.5);
				}
				.kt-header-mobile .kt-header-mobile__toolbar .kt-header-mobile__toggler span::before, .kt-header-mobile .kt-header-mobile__toolbar .kt-header-mobile__toggler span::after {
				    background: rgba(0, 0, 0, 0.5);
				}

			}
		</style>
	</head>

	<!-- end::Head -->
	
	<!-- begin::Body -->
	<!-- <body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading"> -->
	<body class="kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-aside--minimize">