<?PHP
$data 		= array_shift($getData);
$baseid 	= $data['id'];
$thedate 	= $data['weddingdate'];
$dirbase 	= $data['name'];

$getDetail 	= $this->dbw->query("SELECT * FROM detail_person where orderid=?", $baseid)->result_array();
$detail 	= array_shift($getDetail);
$queen 		= $detail['nicknamew'];
$king 		= $detail['nicknamem'];

$qBanner 	= "SELECT * FROM detail_banner where orderid=? order by sort desc";
$getBanner 	= $this->dbw->query($qBanner, $baseid)->result_array();
$jmlban 	= $this->dbw->query($qBanner, $baseid)->num_rows();
$getMeta 	= $this->dbw->query($qBanner, $baseid)->result_array();
$formeta 	= array_shift($getMeta);
$imgmeta 	= $formeta['pict']; 

if (@$_GET['to']!='') {
	$invto 	= 'To:<br><span>'.$_GET['to'].'</span>';
} else {
	$invto 	= '';
}
?>
<!DOCTYPE html>
<html lang="id-ID">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta charset="UTF-8">
		
	<title>The Wedding of <?PHP echo $queen; ?> &amp; <?PHP echo $king; ?> - Numura.id</title>
	<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
	<link rel="canonical" href="index.html" />
	<meta property="og:locale" content="id_ID" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="The Wedding of <?PHP echo $queen; ?> &amp; <?PHP echo $king; ?> - Numura.id" />
	<meta property="og:description" content="The Wedding <?PHP echo $queen; ?> &#038; <?PHP echo $king; ?> Special Invite To: <?PHP echo $invto; ?> | Open Invitation <?PHP echo $thedate; ?>" />
	<meta property="og:url" content="index.html" />
	<meta property="og:site_name" content="Numura.id" />
	<meta property="article:published_time" content="2021-01-25T07:53:03+00:00" />
	<meta property="article:modified_time" content="2021-01-25T13:52:28+00:00" />
	<meta property="og:image" content="<?PHP echo base_url(); ?>images/wedding/<?PHP echo $dirbase; ?>/<?PHP echo $imgmeta; ?>" />
	<meta property="og:image:width" content="1152" />
	<meta property="og:image:height" content="768" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:label1" content="Written by">
	<meta name="twitter:data1" content="Numura.id">

	<link href="<?PHP echo base_url(); ?>assets/wd-assets/marsha/stylesheet.css" rel="stylesheet">
	<link rel="alternate" type="application/rss+xml" title="Numura.id &raquo; Feed" href="<?PHP echo base_url(); ?>feed/" />
	<link rel="alternate" type="application/rss+xml" title="Numura.id &raquo; Umpan Komentar" href="<?PHP echo base_url(); ?>comments/feed/" />
	<link rel='stylesheet' id='bdt-uikit-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/bdthemes-element-pack/assets/css/bdt-uikita25a.css?ver=3.5.5' type='text/css' media='all' />
	<link rel='stylesheet' id='element-pack-site-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/bdthemes-element-pack/assets/css/element-pack-site76f3.css?ver=5.7.3' type='text/css' media='all' />
	<link rel='stylesheet' id='wp-block-library-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-includes/css/dist/block-library/style.min24b2.css?ver=5.5.5' type='text/css' media='all' />
	<link rel='stylesheet' id='wdp-centered-css-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/css/wdp-centered-timeline.min24b2.css?ver=5.5.5' type='text/css' media='all' />
	<link rel='stylesheet' id='wdp-horizontal-css-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/css/wdp-horizontal-styles.min24b2.css?ver=5.5.5' type='text/css' media='all' />
	<link rel='stylesheet' id='wdp-fontello-css-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/css/wdp-fontello24b2.css?ver=5.5.5' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-icons-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/eicons/css/elementor-icons.min74e5.css?ver=5.9.1' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-animations-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/animations/animations.min677a.css?ver=3.0.9' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-frontend-legacy-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/css/frontend-legacy.min677a.css?ver=3.0.9' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-frontend-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/css/frontend.min677a.css?ver=3.0.9' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-frontend-inline-css'  href='<?PHP echo base_url(); ?>assets/themepw1/basecss2.css' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-pro-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor-pro/assets/css/frontend.min677a.css?ver=3.0.9' type='text/css' media='all' />
	<link rel='stylesheet' id='weddingpress-wdp-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/css/wdp16b9.css?ver=2.5.2' type='text/css' media='all' />
	<link rel='stylesheet' id='landingpress-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/themes/landingpress-wp/style0226.css?ver=3.1.2' type='text/css' media='all' />
	<link rel='stylesheet' id='google-fonts-1-css'  href='https://fonts.googleapis.com/css?family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto+Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CClicker+Script%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CCroissant+One%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CDancing+Script%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CAmarante%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;ver=5.5.5' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-icons-shared-0-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/font-awesome/css/fontawesome.minb683.css?ver=5.12.0' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-icons-fa-solid-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/font-awesome/css/solid.minb683.css?ver=5.12.0' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-icons-fa-regular-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/font-awesome/css/regular.minb683.css?ver=5.12.0' type='text/css' media='all' />
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-includes/js/jquery/jquery4a5f.js?ver=1.12.4-wp' id='jquery-core-js'></script>
	<link rel='shortlink' href='https://numura.id/?p=24224' />
	
	<link rel='stylesheet'  href='<?PHP echo base_url(); ?>assets/wd-assets/glanz_style.css' type='text/css' media='all' />

	<link href="https://fonts.googleapis.com/css?family=Dosis:300,400,600,700%7COpen+Sans:300,400,700%7CPlayfair+Display:400,400i,700,700i" rel="stylesheet">
	
	<style type="text/css">
		@import url('https://fonts.cdnfonts.com/css/bunch-blossoms-personal-use');
		.elementor-widget {z-index: 2;}
		.elementor-section.elementor-section-boxed > .elementor-container {
			max-width: 100%!important;
		}
		.elementor-column-gap-default>.elementor-row>.elementor-column>.elementor-element-populated {padding:0px;}
		body {
			background: #FFF;
			font: 300 14px/25px Open Sans, Arial,sans-serif;
		}
		.bgtopleft {
			background: url('<?PHP echo base_url(); ?>assets/wd-assets/bg1.png') no-repeat top left;
			background-size: auto 100%;
			width: 360px;
			height:100%;
			position: absolute;
			left: 0px;
			top: 0px;
		}
		.bgtopleft img {
			max-width: 100%;
		}
		.bgbotright {
			background: url('<?PHP echo base_url(); ?>assets/wd-assets/bg2.png') no-repeat top right;
			background-size: auto 100%;
			width: 260px;
			height:100%;
			position: absolute;
			right: 0px;
			bottom: 0px;
			text-align: right;
		}
		.bgbotright img {
			max-width: 100%;
		}
		.elementor-24224 .elementor-element.elementor-element-79bda4b > .elementor-background-overlay {
			opacity:0;
		}
		.gla_flower2_name_l, .gla_flower2_name_r {
			color: #ca9078;
		}
		.gla_flower2_name_l b, .gla_flower2_name_r b {
			white-space: nowrap;
			color: #3f4231;
		}
		.gla_flower2:after {
			/*background: url('<?PHP echo base_url(); ?>assets/wd-assets/linear2.png');*/
			/*background-size: cover;*/
			/*background: none;*/
			background: #FFF;
			background-size: cover;
			width: 395px;
			height: 395px;
			margin: -13px 0 0 -12px;
			border: 3px solid #d0a089;
			border-radius: 100%;
			z-index: 1;
		}
		.elementor-24224 .elementor-element.elementor-element-79bda4b {
			padding: 0px 0px 0px 0px;
		}
		.img-ourwed {
			max-width: 220px!important;
		    margin-top: 0%;
		    margin-bottom: -5%;
		}
		.circprof {
			width: 370px!important;
		    height: 370px!important;
		    position: relative;
		    z-index: 2;
		}
		.circprof .forslide {
			width: 100%;
			height: 100%;
			border-radius: 100%;
		    overflow: hidden;
		}
		.circprof .forslide div {
			height:100%!important;
		}
		@media (max-width: 761px) {
			.img-ourwed {
				max-width: 180px!important;
				margin-top: -20px;
    			margin-bottom: -20px;
			}
		}
		svg { width: 1em; height: 1em; fill: currentColor; display: inline-block; vertical-align: middle; margin-top: -2px; } 
		.elementor-widget-divider--view-line_icon .elementor-divider-separator:after, .elementor-widget-divider--view-line_icon .elementor-divider-separator:before, .elementor-widget-divider--view-line_text .elementor-divider-separator:after, .elementor-widget-divider--view-line_text .elementor-divider-separator:before {
		    display: block;
		    content: "";
		    border-bottom: 0;
		    -webkit-box-flex: 1;
		    -ms-flex-positive: 1;
		    flex-grow: 1;
		    border-top: var(--divider-border-width) var(--divider-border-style) var(--divider-color);
		}
		.elementor-24224 .elementor-element.elementor-element-53053e1 {
			margin-top:-170px;
		}
		.elementor-24224 .elementor-element.elementor-element-53053e1 .elementor-heading-title {
			font: 400 16px/10px Dosis, Arial!important;
		    margin-top: 15px;
		    color: #3f4231;
		}
		.elementor-24224 .elementor-element.elementor-element-53053e1 .elementor-heading-title span {
			font: 400 45px/60px Marsha, Dosis!important;
			color: #818b95;
		}
		.elementor-24224 .elementor-element.elementor-element-860d850 .elementor-heading-title {
			font-family: 'Bunch Blossoms Personal Use', sans-serif;
			font-weight: normal;
		}
		.elementor-24224 .elementor-element.elementor-element-2467aaa .elementor-heading-title {
			font: normal 500 20px/30px Dosis, Arial!important;
		}
		.elementor-24224 .elementor-element.elementor-element-b8a5e50 .elementor-button {
			font: normal 600 16px/20px Dosis, Arial!important;
			margin-top: -20px;
			background: #ca9078;
			color: #FFF;
			text-shadow: none;
		}
		.elementor-24224 .elementor-element.elementor-element-96cf73b {
			margin-top: -20px!important;
		}
		.gla_flower2_name_mob {
			display: none;
		}
		.gla_flower2_desc_mob {display :none;}
		@media (min-width: 1240px) {
			.elementor-24224 .elementor-element.elementor-element-860d850 .elementor-heading-title {
				font-size: 50px;
    			margin-top: 30px;
			}
		}
		@media (max-width: 767px) {
			.gla_flower2_name_mob {
				display: block;
				font: 400 55px/60px marsha;
				margin-top:60px;
				color: #ca9078;
			}
			.gla_flower2_name_mob span {
				font-size: 25px;
			}
			.gla_flower2_desc_mob {
				display: block;
				color: #3f4231;
			}
			.gla_flower2:after {
				z-index: 1;
				width: 320px;
				height: 320px;
				margin: -13px 0 0 -12px;
				border: 3px solid #d0a089;
				border-radius: 100%;
			}
			.circprof {
				width: 295px!important;
			    height: 295px!important;
			}
			.elementor-24224 .elementor-element.elementor-element-d2576d5 .elementor-heading-title span {
				font-size: 42px!important;
			}
			.elementor-24224 .elementor-element.elementor-element-d2576d5 .elementor-heading-title {
				font-size: 67px!important;
			}
			.elementor-24224 .elementor-element.elementor-element-53053e1 .elementor-heading-title {
				font: 400 20px/25px Dosis, Arial!important;
				margin-top: -5px;
			}
			.elementor-24224 .elementor-element.elementor-element-53053e1 .elementor-heading-title span {
				font: 400 50px/60px Marsha, Dosis!important;
			}
		}
	</style>
	<link rel="icon" href="<?PHP echo base_url(); ?>images/favicosarvel.png" sizes="32x32" />
	<link rel="icon" href="<?PHP echo base_url(); ?>images/favicosarvel.png" sizes="192x192" />
	<link rel="apple-touch-icon" href="<?PHP echo base_url(); ?>images/favicosarvel.png" />
	
	<meta name="msapplication-TileImage" content="<?PHP echo base_url(); ?>images/favicosarvel.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
</head>

<body class="post-template post-template-elementor_canvas single single-post postid-24224 single-format-standard header-active footer-active elementor-default elementor-template-canvas elementor-kit-6 elementor-page elementor-page-24224">
	<div class="bgtopleft"></div>
	<div class="bgbotright"></div>

	<div data-elementor-type="wp-post" data-elementor-id="24224" class="elementor elementor-24224" data-elementor-settings="[]">
		<div class="elementor-inner">
			<div class="elementor-section-wrap">
				<section class="elementor-section elementor-top-section elementor-element elementor-element-79bda4b elementor-section-height-full elementor-section-boxed elementor-section-height-default elementor-section-items-middle">
					<div class="elementor-background-overlay"></div>
					<div class="elementor-container elementor-column-gap-default">
						<div class="elementor-row">
							<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-c755c53" data-id="c755c53" data-element_type="column">
								<div class="elementor-column-wrap elementor-element-populated">
									<div class="elementor-widget-wrap">

										<!-- Slider -->
									    <div class="gla_slider gla_image_bck  gla_wht_txt gla_fixed"  data-image="" data-stellar-background-ratio="0.8">
									        <!-- Over -->
									        <div class="gla_over" data-color="transparent" data-opacity="0.2"></div>
									        <div class="container">
									            <!-- Slider Texts -->
									            <div class="gla_slide_txt gla_slide_center_middle text-center">
									                 <div class="gla_flower gla_flower2">
									                    <div class="gla_flower2_name_l"><?PHP echo $queen; ?> <b>Save The Date</b></div>
									                    <div class="gla_flower2_name_r"><?PHP echo $king; ?> <b><?PHP echo $thedate; ?></b></div>
									                    <div class="circprof">
															<div class="forslide">
																<div class="elementor-section elementor-top-section elementor-element elementor-element-79bda4b elementor-section-height-full elementor-section-boxed elementor-section-height-default elementor-section-items-middle" data-id="79bda4b" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;slideshow&quot;,&quot;background_slideshow_gallery&quot;:[<?PHP $xb=0; foreach ($getBanner as $banner) { $xb++; ?>{&quot;id&quot;:<?PHP echo $banner['id']; ?>,&quot;url&quot;:&quot;<?PHP echo base_url(); ?>\/images\/wedding\/<?PHP echo $dirbase; ?>\/<?PHP echo $banner['pict']; ?>&quot;}<?PHP if ($xb<$jmlban) { echo ','; } ?><?PHP } ?>],&quot;background_slideshow_loop&quot;:&quot;yes&quot;,&quot;background_slideshow_slide_duration&quot;:5000,&quot;background_slideshow_slide_transition&quot;:&quot;fade&quot;,&quot;background_slideshow_transition_duration&quot;:500}"></div>
															</div>
														</div>
														<div class="gla_flower2_name_mob"><?PHP echo $queen; ?> <span>&</span> <?PHP echo $king; ?></div>
														<div class="gla_flower2_desc_mob"><?PHP echo $thedate; ?></div>
									                </div>
									            </div>
									            <!-- Slider Texts End -->
									        </div>
									        <!-- container end -->
									        <div class="elementor-element elementor-element-53053e1 elementor-widget elementor-widget-heading" data-id="53053e1" data-element_type="widget" data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<h3 class="elementor-heading-title elementor-size-default">
														Special Invite <?PHP echo $invto; ?>
													</h3>
												</div>
											</div>

											<div class="elementor-element elementor-element-b8a5e50 elementor-align-center animated-slow elementor-invisible elementor-widget elementor-widget-button" data-id="b8a5e50" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;zoomIn&quot;,&quot;_animation_mobile&quot;:&quot;zoomIn&quot;}" data-widget_type="button.default">
												<div class="elementor-widget-container">
													<div class="elementor-button-wrapper">
														<a href="<?PHP echo base_url(); ?>invitation/<?PHP echo $oidname; ?>/" rel="nofollow" class="elementor-button-link elementor-button elementor-size-sm" role="button">
															<span class="elementor-button-content-wrapper">
																<span class="elementor-button-icon elementor-align-icon-left">
																	<i aria-hidden="true" class="far fa-envelope" style="color: #FFF;"></i>
																</span>
																<span class="elementor-button-text">Open Invitation</span>
															</span>
														</a>
													</div>
												</div>
											</div>
									    </div>
									    <!-- Slider End -->
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
	</div>

	<div id="back-to-top">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M177 159.7l136 136c9.4 9.4 9.4 24.6 0 33.9l-22.6 22.6c-9.4 9.4-24.6 9.4-33.9 0L160 255.9l-96.4 96.4c-9.4 9.4-24.6 9.4-33.9 0L7 329.7c-9.4-9.4-9.4-24.6 0-33.9l136-136c9.4-9.5 24.6-9.5 34-.1z"/>
		</svg>
	</div>

	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/js/wdp-swiper.min.js' id='wdp-swiper-js-js'></script>
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/js/wdp-horizontal.js' id='wdp-horizontal-js-js'></script>


	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/bdthemes-element-pack/assets/js/bdt-uikit.mina25a.js?ver=3.5.5' id='bdt-uikit-js'></script>
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/js/frontend-modules.min677a.js?ver=3.0.9' id='elementor-frontend-modules-js'></script>
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-includes/js/jquery/ui/position.mine899.js?ver=1.11.4' id='jquery-ui-position-js'></script>
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/dialog/dialog.mina288.js?ver=4.8.1' id='elementor-dialog-js'></script>
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/waypoints/waypoints.min05da.js?ver=4.0.2' id='elementor-waypoints-js'></script>
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/swiper/swiper.min48f5.js?ver=5.3.6' id='swiper-js'></script>
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/share-link/share-link.min677a.js?ver=3.0.9' id='share-link-js'></script>

	<script type='text/javascript' id='elementor-frontend-js-before'>
		var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false},"i18n":{"shareOnFacebook":"Share on Facebook","shareOnTwitter":"Share on Twitter","pinIt":"Pin it","download":"Download","downloadImage":"Download image","fullscreen":"Fullscreen","zoom":"Zoom","share":"Share","playVideo":"Play Video","previous":"Previous","next":"Next","close":"Close"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"version":"3.0.9","is_static":false,"legacyMode":{"elementWrappers":true},"urls":{"assets":"https:\/\/numura.id\/wp-content\/plugins\/elementor\/assets\/"},"settings":{"page":[],"editorPreferences":[]},"kit":{"global_image_lightbox":"yes","lightbox_enable_counter":"yes","lightbox_enable_fullscreen":"yes","lightbox_enable_zoom":"yes","lightbox_enable_share":"yes","lightbox_title_src":"title","lightbox_description_src":"description"},"post":{"id":24224,"title":"The%20Wedding%20of%20<?PHP echo $queen; ?>%20%26%20<?PHP echo $king; ?>%20%E2%80%93%Numura%ID","excerpt":"","featuredImage":"https:\/\/numura.id\/wp-content\/uploads\/2021\/01\/MG_0065-Galipat-Story-570x320.jpg"}};
	</script>

	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/js/frontend.min677a.js?ver=3.0.9' id='elementor-frontend-js'></script>
	
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/bdthemes-element-pack/assets/js/element-pack-site.min76f3.js?ver=5.7.3' id='element-pack-site-js'></script>
	
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor-pro/assets/lib/sticky/jquery.sticky.min677a.js?ver=3.0.9' id='elementor-sticky-js'></script>

	<script type='text/javascript' id='elementor-pro-frontend-js-before'>
		var ElementorProFrontendConfig = {"ajaxurl":"https:\/\/numura.id\/wp-admin\/admin-ajax.php","nonce":"4d8100c514","i18n":{"toc_no_headings_found":"No headings were found on this page."},"shareButtonsNetworks":{"facebook":{"title":"Facebook","has_counter":true},"twitter":{"title":"Twitter"},"google":{"title":"Google+","has_counter":true},"linkedin":{"title":"LinkedIn","has_counter":true},"pinterest":{"title":"Pinterest","has_counter":true},"reddit":{"title":"Reddit","has_counter":true},"vk":{"title":"VK","has_counter":true},"odnoklassniki":{"title":"OK","has_counter":true},"tumblr":{"title":"Tumblr"},"digg":{"title":"Digg"},"skype":{"title":"Skype"},"stumbleupon":{"title":"StumbleUpon","has_counter":true},"mix":{"title":"Mix"},"telegram":{"title":"Telegram"},"pocket":{"title":"Pocket","has_counter":true},"xing":{"title":"XING","has_counter":true},"whatsapp":{"title":"WhatsApp"},"email":{"title":"Email"},"print":{"title":"Print"}},"facebook_sdk":{"lang":"id_ID","app_id":""},"lottie":{"defaultAnimationUrl":"https:\/\/numura.id\/wp-content\/plugins\/elementor-pro\/modules\/lottie\/assets\/animations\/default.json"}};
	</script>
	
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor-pro/assets/js/frontend.min677a.js?ver=3.0.9' id='elementor-pro-frontend-js'></script>

	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/js/wdp.min16b9.js?ver=2.5.2' id='weddingpress-wdp-js'></script>
	
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/js/dce-editor-copy16b9.js?ver=2.5.2' id='dce-clipboard-js-js'></script>
	
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/themes/landingpress-wp/assets/js/script.min0226.js?ver=3.1.2' id='landingpress-js'></script>

	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/wd-assets/glanz_library.js'></script>
	<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/wd-assets/glanz_script.js'></script>
	<!-- <script>
		$(function() {
		    $('#imgourwed').attr('src','<?PHP echo base_url(); ?>assets/wd-assets/ourwedding_wh.gif');
		});
	</script>	 -->
</body>

</html>
