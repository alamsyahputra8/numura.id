<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<title><?PHP $this->load->view('/panel/titlebar'); ?> | e-LOP RAISA 2.0</title>
	
	<link href="<?PHP echo base_url(); ?>assets/panel/datepicker/css/datepicker.css" rel="stylesheet" type="text/css"/>
	
	<!-- Favicon and touch icons -->
	<link rel="shortcut icon" href="<?PHP echo base_url(); ?>images/favicon.ico" type="image/x-icon">
	<link rel="apple-touch-icon" type="image/x-icon" href="<?PHP echo base_url(); ?>images/favicon.ico">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="<?PHP echo base_url(); ?>images/favicon.ico">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="<?PHP echo base_url(); ?>images/favicon.ico">
	<link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="<?PHP echo base_url(); ?>images/favicon.ico">
	
	<!-- Start Global Mandatory Style
	=====================================================================-->
	<!-- jQuery -->
	<script src="<?PHP echo base_url(); ?>assets/panel/plugins/jQuery/jquery-1.12.4.min.js" type="text/javascript"></script>
	<!-- jquery-ui --> 
	<script src="<?PHP echo base_url(); ?>assets/panel/plugins/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>
	<!-- jquery-ui css -->
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
	<!-- Bootstrap -->
	<link href="<?PHP echo base_url(); ?>assets/panel/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<!-- Bootstrap rtl -->
	<!--<link href="<?PHP echo base_url(); ?>assets/panel/bootstrap-rtl/bootstrap-rtl.min.css" rel="stylesheet" type="text/css"/>-->
	<!-- Lobipanel css -->
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/lobipanel/lobipanel.min.css" rel="stylesheet" type="text/css"/>
	<!-- Pace css -->
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/pace/flash.css" rel="stylesheet" type="text/css"/>
	<!-- Font Awesome -->
	<link href="<?PHP echo base_url(); ?>assets/panel/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
	<!-- Pe-icon -->
	<link href="<?PHP echo base_url(); ?>assets/panel/pe-icon-7-stroke/css/pe-icon-7-stroke.css" rel="stylesheet" type="text/css"/>
	<!-- Themify icons -->
	<link href="<?PHP echo base_url(); ?>assets/panel/themify-icons/themify-icons.css" rel="stylesheet" type="text/css"/>
	<!-- End Global Mandatory Style
	=====================================================================-->
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/datepicker/bootstrap-datepicker.css" rel="stylesheet">
    
	<!-- Start page Label Plugins 
	=====================================================================-->
	<!-- dataTables css -->
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/datatables/dataTables.min.css" rel="stylesheet" type="text/css"/>
	<!-- modals css -->
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/modals/component.css" rel="stylesheet" type="text/css"/>
	<!-- iCheck -->
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/icheck/skins/all.css" rel="stylesheet" type="text/css"/>
	<!-- Bootstrap toggle css -->
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/bootstrap-toggle/bootstrap-toggle.min.css" rel="stylesheet" type="text/css"/>
	<!-- Bootstrap-wysihtml5 css -->
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" rel="stylesheet" type="text/css"/>
	<!-- Bootstrap-wysihtml5 css -->
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/select2/select2.min.css" rel="stylesheet" type="text/css"/>
	<!-- UPLOAD PHOTOS 
	<link href="<?PHP echo base_url(); ?>assets/panel/plugins/uploadform/css/style.css" rel="stylesheet" />
	<script src="<?PHP echo base_url(); ?>assets/panel/plugins/uploadform/js/jquery.knob.js"></script>
	<script src="<?PHP echo base_url(); ?>assets/panel/plugins/uploadform/js/jquery.ui.widget.js"></script>
	<script src="<?PHP echo base_url(); ?>assets/panel/plugins/uploadform/js/jquery.iframe-transport.js"></script>
	<script src="<?PHP echo base_url(); ?>assets/panel/plugins/uploadform/js/jquery.fileupload.js"></script>
	<script src="<?PHP echo base_url(); ?>assets/panel/plugins/uploadform/js/script.js"></script>-->
	<!-- GALLERY -->
	<link rel="stylesheet" href="<?PHP echo base_url(); ?>assets/panel/plugins/gallery/baguetteBox.min.css">
    <link rel="stylesheet" href="<?PHP echo base_url(); ?>assets/panel/plugins/gallery/gallery-grid.css">
	<!-- End page Label Plugins 
	=====================================================================-->
	
	<!-- Start Theme Layout Style
	=====================================================================-->
	<!-- Theme style -->
	<link href="<?PHP echo base_url(); ?>assets/panel/dist/css/styleBD.css" rel="stylesheet" type="text/css"/>
	<!-- Theme style rtl -->
	<!--<link href="<?PHP echo base_url(); ?>assets/panel/dist/css/styleBD-rtl.css" rel="stylesheet" type="text/css"/>-->
	<!-- End Theme Layout Style
	=====================================================================-->
	
	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	
	<style>
	body {
		font-family: 'Roboto', sans-serif;
	}
	.titleprojectlist {
		color: #6d6e72; font-weight: bold; font-size: 26px; width: 100px; margin-left: 20px; margin-top: 60px;
	}
	.proj {
		font-size: 88px;
		padding: 40px 0px;
		margin: 0;
		margin-top: 0px;
		font-weight: bold;
		color: #1f2224;
		cursor: pointer;
	}
	.svreal {
		font-size: 56px;
	}
	.padding0px {padding:0px;}
	.topachtitle {
		font-size: 18px;
		line-height: normal!important;
		margin-top: 5px!important;
	}
	.titachbox {
		margin: 0px;
		font-weight: bold;
		font-size: 16px;
	}
	.achbig span { font-size: 43px; }
	.achbig {
		font-size: 83px;
		margin-bottom: -35px;
		margin-top: -35px;
		font-weight: bolder;
	}
	.achpopup {
		font-size: 51px;
		font-weight: bold;
		margin-top: 0px;
	}
	.achpopup span {
		font-size: 22px;
		font-weight: 500;
	}
	.achpopupsmall span {
		font-size: 18px;
		margin-top: 10px;
		font-weight: 400;
	}
	.achpopupsmall {
		font-size: 53px;
		text-align: center;
		line-height: 28px;
		font-weight: bold;
	}
	.titlesmall {font-size:13px;}
	.achbig2 span { font-size:16px; }
	.achbig2 {
		font-size: 29px;
		margin-bottom: -5px;
		margin-top: 0px;
	}
	.achtitle {
		margin-top: -5px;
		margin-bottom: 20px;
		font-size: 13px;
	}
	.achsmall {
		font-size: 22px;
		margin-bottom: 0px;
	}
	.achsmall span {
		font-size: 15px;
	}
	.achsmall2 {
		font-size: 15px;
		margin-bottom: 0px;
	}
	.achsmall2 span {
		font-size:11px;
	}
	.hidemobile {
		display:block;
	}
	.showmobile {
		display:none;
	}
	.detailachper {
		font-size:11px; 
		margin-bottom:23px;
	}
	.wowach {
		position: relative;
		clear: both;
		margin-top: 10px;
		right: -75%;
		margin-bottom: -55px;
		font-size: 12px;
	}
	.wowachsmall {
		font-size: 10px;
	}
	.hide {display:none;}
	#donutproject3 { display: none; }
	
	.h1projstatus {
		font-size: 22px;
		margin-top: 15px;
		margin-bottom: 15px;
	}
	.marginbot5 {
		margin-bottom: 5px;
	}
	.paddingcustab td a {
		color: #1e1e1e;
	}
	.paddingcustab th {
		padding-bottom: 15px!important;
		padding-top: 15px!important;
		font-size: 16px;
	}
	.projectlisttab li a {
		border-radius: 20px;
		padding: 6px 50px;
		margin-right: 10px;
	}
	.projectlisttab .blue a {
		color: #0071bd!important;
		border: 1.5px solid #0071bd!important;
	}
	.projectlisttab .active.blue a, .projectlisttab .active.blue a:hover, .projectlisttab .active.blue a:active, .projectlisttab .active.blue a:focus {
		background: #0071bd;
		color: #FFF!important;
	}
	
	.projectlisttab .red a {
		color: #ed1e1a!important;
		border: 1.5px solid #ed1e1a!important;
	}
	.projectlisttab .active.red a, .projectlisttab .active.red a:hover, .projectlisttab .active.red a:active, .projectlisttab .active.red a:focus {
		background: #ed1e1a;
		color: #FFF!important;
	}
	
	.projectlisttab .orange a {
		color: #f8911a!important;
		border: 1.5px solid #f8911a!important;
	}
	.projectlisttab .active.orange a, .projectlisttab .active.orange a:hover, .projectlisttab .active.orange a:active, .projectlisttab .active.orange a:focus {
		background: #f8911a;
		color: #FFF!important;
	}
	
	.projectlisttab .green a {
		color: #019038!important;
		border: 1.5px solid #019038!important;
	}
	.projectlisttab .active.green a, .projectlisttab .active.green a:hover, .projectlisttab .active.green a:active, .projectlisttab .active.green a:focus {
		background: #019038;
		color: #FFF!important;
	}
	
	.listprojstat {
		
	}
	.bulan_mapping .ed_bulan_mapping .ui-datepicker-calendar {
	    display: none;
	}​
	@media (max-width: 767px) {
		.hidemobile {display:none!important;}
		.showmobile {display:block!important;}
		.svreal {font-size: 26px!important; margin-top: -10px; margin-bottom: -10px;}
		.svrealsmall {margin-top: 20px; margin-bottom: 0px;}
		.proj {font-size: 58px;}
		.achbig {font-size:38px!important; margin-top: 20px; margin-bottom: 20px;}
		.achsmall {font-size: 19px!important;}
		.achsmall span {font-size: 11px!important;}
		.detailachper { font-size:9px; }
		.font-smallmobile {font-size: 11px!important; }
		.h4, h4 {font-size: 14px!important;}
	}
	
	@media (max-width: 320px) {
		.hidemobile {display:none!important;}
		.showmobile {display:block!important;}
		.svreal {font-size: 31px!important; margin-top: -10px; margin-bottom: -10px;}
		.svrealsmall { margin-top: 10px; margin-bottom: -5px; font-size: 11px; }
		.proj {font-size: 38px; margin-top: -15px!important;}
		.achbig { font-size: 24px!important; margin-top: -10px!important; margin-bottom: -10px; }
		.achsmall {font-size: 14px!important;}
		.main-header .logo .logo-lg img {height: 32px;}
		.main-header .logo { height: 50px; line-height: 50px;}
		.achsmall span {font-size: 11px!important;}
		.detailachper { font-size:9px; }
		#donutproject2 { display: none; }
		#donutproject3 { display: block; }
		.font-smallmobile {font-size: 7px!important; letter-spacing:0!important;}
		.h4, h4 {line-height: 14px!important; font-size: 10px!important;}
		.panel-footer {padding: 10px 0px;}
		.navbar-nav > li > a > i {
			border: 1px solid #be3c36;
			padding: 6px 3px;
			width: 26px;
			text-align: center;
			color: #fff;
			background-color: #b72e28;
			height: 26px;
			font-size: 15px;
		}
		.main-header .navbar {min-height: 40px;}
		.main-header .sidebar-toggle {
			float: left;
			padding: 16px 10px;
			font-family: fontAwesome;
			border-right: 1px solid #99231d;
			color: #f5f5f5;
			font-size: 16px;
			line-height: 6px;
		}
		.detailachper { font-size:6px;  margin-bottom:13px; }
		.bar {padding: 3px; font-size:10px;}
		.panel {margin-bottom: 10px;}
	}
	
	</style>
	
	<style>
	.hidemobile {
		display:block;
	}
	.showmobile {
		display:none;
	}
	@media (max-width: 480px) {
		.hidemobile {
			display:none;
		}
		.showmobile {
			display:block;
		}
	}
	</style>
</head>
<body class="hold-transition sidebar-collapse sidebar-mini">
	<!-- Site wrapper -->
	<div class="wrapper">
	
	<?PHP $this->load->view('/theme/panel/head'); ?>
	
	<?PHP $this->load->view('/theme/panel/sidebar'); ?>