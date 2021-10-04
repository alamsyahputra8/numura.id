<!DOCTYPE html>
<html lang="en">

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Numura" />    
	<meta name="description" content="Numura.id">

	<script data-ad-client="ca-pub-5881511926422255" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	
    <link rel="icon" type="image/png" href="<?PHP echo base_url(); ?>images/favicosarvel.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- Document title -->
	<title>NUMURA.ID</title>
	<!-- Stylesheets & Fonts -->
	<link href="<?PHP echo base_url(); ?>assets/polo/css/plugins.css" rel="stylesheet">
	<link href="<?PHP echo base_url(); ?>assets/polo/css/style.css" rel="stylesheet">

	<style>
		body {background: #152737!important;}
		/* #########################################################

		HOW TO CREATE A RESPONSIVE IMAGE SLIDER [TUTORIAL]

		"How to create a Responsive Image Slider [Tutorial]" was specially made for DesignModo by our friend Valeriu Timbuc.

		Links:
		http://vtimbuc.net
		http://designmodo.com
		http://vladimirkudinov.com

		######################################################### */



		/* Browser Resets */
		.flex-container a:active,
		.flexslider a:active,
		.flex-container a:focus,
		.flexslider a:focus  { outline: none; }

		.slides,
		.flex-control-nav,
		.flex-direction-nav {
			margin: 0;
			padding: 0;
			list-style: none;
		}

		.flexslider a img { outline: none; border: none; }

		.flexslider {
			margin: 0;
			padding: 0;
		}

		/* Hide the slides before the JS is loaded. Avoids image jumping */
		.flexslider .slides > li {
			display: none;
			-webkit-backface-visibility: hidden;
		}

		.flexslider .slides img {
			width: 100%;
			display: block;

			-webkit-border-radius: 2px;
			-moz-border-radius: 2px;
			border-radius: 2px;
		}

		/* Clearfix for the .slides element */
		.slides:after {
			content: ".";
			display: block;
			clear: both;
			visibility: hidden;
			line-height: 0;
			height: 0;
		}

		html[xmlns] .slides { display: block; }
		* html .slides { height: 1%; }



		/* Theme Styles */
		.flexslider {
			position: relative;
			zoom: 1;
			padding: 10px;
			background: #ffffff;

			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;

			-webkit-box-shadow: 0px 1px 1px rgba(0,0,0, .2);
			-moz-box-shadow: 0px 1px 1px rgba(0,0,0, .2);
			box-shadow: 0px 1px 1px rgba(0,0,0, .2);
		}

		/* Edit it if you want */
		.flex-container {
			min-width: 150px;
			max-width: 600px;
			margin: 0 auto;
		}

		.flexslider .slides { zoom: 1; }



		/* Direction Nav */
		.flex-direction-nav a {
			display: block;
			position: absolute;
			margin: -17px 0 0 0;
			width: 35px;
			height: 35px;
			top: 50%;
			cursor: pointer;
			text-indent: -9999px;
			z-index: 9999;

			background-color: #82d344;
			background-image: -webkit-gradient(linear, left top, left bottom, from(#82d344), to(#51af34));
			background-image: -webkit-linear-gradient(top, #82d344, #51af34);
			background-image: -moz-linear-gradient(top, #82d344, #51af34);
			background-image: -o-linear-gradient(top, #82d344, #51af34);
			background-image: linear-gradient(to bottom, #82d344, #51af34);
		}

		.flex-direction-nav a:before {
			display: block;
			position: absolute;
			content: '';
			width: 9px;
			height: 13px;
			top: 11px;
			left: 11px;
			background: url(https://designmodo.com/demo/responsiveslider/img/arrows.png) no-repeat;
		}

		.flex-direction-nav a:after {
			display: block;
			position: absolute;
			content: '';
			width: 0;
			height: 0;
			top: 35px;
		}

		.flex-direction-nav .flex-next {
			right: -5px;

			-webkit-border-radius: 3px 0 0 3px;
			-moz-border-radius: 3px 0 0 3px;
			border-radius: 3px 0 0 3px;
		}

		.flex-direction-nav .flex-prev {
			left: -5px;

			-webkit-border-radius: 0 3px 3px 0;
			-moz-border-radius: 0 3px 3px 0;
			border-radius: 0 3px 3px 0;
		}

		.flex-direction-nav .flex-next:before { background-position: -9px 0; left: 15px; }
		.flex-direction-nav .flex-prev:before { background-position: 0 0; }

		.flex-direction-nav .flex-next:after {
			right: 0;
			border-bottom: 5px solid transparent;
			border-left: 5px solid #31611e;
		}

		.flex-direction-nav .flex-prev:after {
			left: 0;
			border-bottom: 5px solid transparent;
			border-right: 5px solid #31611e;
		}



		/* Control Nav */
		.flexslider .flex-control-nav {
			position: absolute;
			width: 100%;
			bottom: -40px;
			text-align: center;
			margin: 0 0 0 -10px;
		}

		.flex-control-nav li {
			display: inline-block;
			zoom: 1;
		}

		.flex-control-paging li a {
			display: block;
			cursor: pointer;
			text-indent: -9999px;
			width: 12px;
			height: 12px;
			margin: 0 3px;
			background-color: #b6b6b6 \9;

			-webkit-border-radius: 12px;
			-moz-border-radius: 12px;
			border-radius: 12px;

			-webkit-box-shadow: inset 0 0 0 2px #b6b6b6;
			-moz-box-shadow: inset 0 0 0 2px #b6b6b6;
			box-shadow: inset 0 0 0 2px #b6b6b6;
		}

		.flex-control-paging li a.flex-active {
			background-color: #82d344;
			background-image: -webkit-gradient(linear, left top, left bottom, from(#82d344), to(#51af34));
			background-image: -webkit-linear-gradient(top, #82d344, #51af34);
			background-image: -moz-linear-gradient(top, #82d344, #51af34);
			background-image: -o-linear-gradient(top, #82d344, #51af34);
			background-image: linear-gradient(to bottom, #82d344, #51af34);

			-webkit-box-shadow: none;
			-moz-box-shadow: none;
			box-shadow: none;
		}



		/* Captions */
		.flexslider .slides p {
			display: block;
			position: absolute;
			left: 0;
			bottom: 100px;
			padding: 0 5px;
			margin: 0;

			font-family: Helvetica, Arial, sans-serif;
			font-size: 12px;
			font-weight: bold;
			text-transform: uppercase;
			line-height: 20px;
			color: white;

			background-color: #222222;
			background: rgba(0,0,0, .9);

			-webkit-border-radius: 2px;
			-moz-border-radius: 2px;
			border-radius: 2px;
		}
	</style>
</head>

<body>
	<!-- Body Inner -->
	<img src="<?PHP echo base_url(); ?>images/web-numura.jpg" style="width: 100%;">
	<center><button class="btn btn-warning" data-toggle="modal" data-target="#modalkarakter" style="margin-top:-21vw;">Klik disini untuk melihat Pilihan Karakter</button></center>

	<div class="modal fade" id="modalkarakter" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document" style="max-width: 620px;">
			<div class="modal-content">
				<!--div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle"></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					</button>
				</div-->

				<div class="modal-body kt-portlet kt-portlet--tabs" style="margin-bottom: 0px;">
					<div class="kt-portlet__head">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title text-center">Pilih Karakter dengan Nama Kamu Sendiri !</h3>
						</div>
						<!-- <div class="kt-portlet__head-toolbar">

							<img src="<?PHP echo base_url(); ?>images/kodekarakter_numura_new.jpg" style="width: 100%;">
						</div> -->
					</div>
					<div class="kt-portlet__body">
						<div class="flex-container">
						    <div class="flexslider">
						        <ul class="slides">
						            <li><img src="<?PHP echo base_url(); ?>images/new/01.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/02.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/03.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/04.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/05.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/06.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/07.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/08.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/09.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/10.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/11.jpg" /></li>
						            <li><img src="<?PHP echo base_url(); ?>images/new/12.jpg" /></li>
						        </ul>
						    </div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- <div class="body-inner">
		<section class="fullscreen text-center">
			<div class="container container-fullscreen">
				<div class="text-middle text-center">
					<i class="fa fa-exclamation-triangle fa-5x" style="color: #ffd530;"></i>
					<h1 class="text-uppercase text-lg">NUMURA.ID</h1>
					<p class="lead">We are currently working on our website, we'll be back soon!</p>
				</div>
			</div>
			<div class="p-progress-bar-container title-up small">
				<div class="p-progress-bar" data-percent="95" data-delay="100" data-type="%" style="background-color:#ffd530">
					<div class="progress-title">DEVELOPMENT PROGRESS</div>
				</div>
			</div>
		</section>
	</div>  -->

	<!-- end: Body Inner -->
	<!-- Scroll top -->
	<a id="scrollTop"><i class="icon-chevron-up"></i><i class="icon-chevron-up"></i></a>
	<!--Plugins-->
	<script src="<?PHP echo base_url(); ?>assets/polo/js/jquery.js"></script>
	<script src="<?PHP echo base_url(); ?>assets/polo/js/plugins.js"></script>
	<!--Template functions-->
	<script src="<?PHP echo base_url(); ?>assets/polo/js/functions.js"></script>
	<script src="https://designmodo.com/demo/responsiveslider/js/jquery.flexslider-min.js"></script>
	<script>
		$(document).ready(function () {
	        $('.flexslider').flexslider({
	            animation: 'fade',
	            controlsContainer: '.flexslider'
	        });
	    });
	</script>
</body>

</html>