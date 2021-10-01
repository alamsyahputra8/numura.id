<?PHP
$data 		= array_shift($getData);
$baseid 	= $data['id'];
$thedate 	= $data['weddingdate'];
$dirbase 	= $data['name'];
$audio 		= $data['music'];
$theday 	= $data['theday'];
$prokes 	= $data['prokes'];
$cekmodig	= $data['module_ig'];
$rsvpnum	= $data['rsvp_number'];
$acciglive 	= $data['account_ig'];
$timelive 	= $data['time_live'];
$modlive 	= $data['module_live'];
$bgquotes 	= $data['img_quotes'];
$angpaubank	= $data['angpau_bank'];
$angpaunorek= $data['angpau_norek'];
$angpauan	= $data['angpau_an'];
$giftpen 	= $data['gift_penerima'];
$giftal 	= $data['gift_alamat'];
$giftphone 	= $data['gift_hp'];

$getDetail 	= $this->db->query("SELECT * FROM detail_person where orderid=?", $baseid)->result_array();
$detail 	= array_shift($getDetail);
$queen 		= $detail['nicknamew'];
$igqueen	= $detail['igw'];
$fullqueen 	= $detail['woman'];
$parentq 	= $detail['daughterof'];
$pictw 		= $detail['pictw'];
$king 		= $detail['nicknamem'];
$igking		= $detail['igm'];
$fullking 	= $detail['man'];
$parentk 	= $detail['sonof'];
$pictm 		= $detail['pictm'];

$akaddate 	= $detail['akaddate'];
$akadstart 	= $detail['akadtime'];
$akadto 	= $detail['akadto'];
$akadat 	= $detail['akadat'];

$reseptiondate 	= $detail['reseptiondate'];
$reseptionstart = $detail['reseptiontime'];
$reseptionto  	= $detail['reseptionto'];
$reseptionat 	= $detail['reseptionat'];

$maplocation= $detail['maps'];
$maplink 	= $detail['maplink'];

$quotes 	= $detail['quotes'];
$quotesby 	= $detail['quotesby'];

$qBanner 	= "SELECT * FROM detail_banner where orderid=? order by sort desc";
$qBanner2 	= "SELECT * FROM detail_banner where orderid=? and flag_bg=1";
$getBanner 	= $this->db->query($qBanner, $baseid)->result_array();
$getBanner2 = $this->db->query($qBanner2, $baseid)->result_array();
$jmlban 	= $this->db->query($qBanner, $baseid)->num_rows();
$getMeta 	= $this->db->query($qBanner, $baseid)->result_array();
$formeta 	= array_shift($getMeta);
$forbg 		= array_shift($getBanner2);
$imgmeta 	= $formeta['pict']; 	
$imgfirst 	= $forbg['pict'];

$qGal 		= "SELECT * FROM detail_gallery where orderid=? order by sort desc";
$getGal 	= $this->db->query($qGal, $baseid)->result_array();
?>
<!DOCTYPE html>
<html lang="id-ID">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta charset="UTF-8">
		
	<title>Invitation <?PHP echo $queen; ?> &amp; <?PHP echo $king; ?> - Numura.id</title>
	<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
	<link rel="canonical" href="index.html" />
	<meta property="og:locale" content="id_ID" />
	<meta property="og:type" content="article" />
	<meta property="og:title" content="Invitation <?PHP echo $queen; ?> &amp; <?PHP echo $king; ?> - Numura.id" />
	<meta property="og:description" content="The Wedding <?PHP echo $queen; ?> &#038; <?PHP echo $king; ?> <?PHP echo $thedate; ?> Tentang Kami PASANGAN MEMPELAI <?PHP echo $fullqueen; ?> Putri dari <?PHP echo $parentq; ?> <?PHP echo $king; ?> <?PHP echo $fullking; ?> Putra dari <?PHP echo $parentk; ?> CERITA KITA &#8221; <?PHP echo $quotes; ?> &hellip;" />
	<meta property="og:url" content="index.html" />
	<meta property="og:site_name" content="Numura.id" />
	<meta property="article:published_time" content="2021-01-25T07:30:47+00:00" />
	<meta property="article:modified_time" content="2021-01-25T13:50:00+00:00" />
	<meta property="og:image" content="<?PHP echo base_url(); ?>assets/themepw1/wp-content/uploads/2021/01/Picture3-39.png" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:label1" content="Written by">
	<meta name="twitter:data1" content="">
	<meta name="twitter:label2" content="">
	<meta name="twitter:data2" content="">

	<link href="<?PHP echo base_url(); ?>assets/wd-assets/marsha/stylesheet.css" rel="stylesheet">
	<link href="<?PHP echo base_url(); ?>shop/assets/theme/assets/demo/default/base/style.bundle.css" rel="stylesheet">

	<link rel="alternate" type="application/rss+xml" title="Numura.id &raquo; Feed" href="https://numura.id/feed/" />
	<link rel="alternate" type="application/rss+xml" title="Numura.id &raquo; Umpan Komentar" href="https://numura.id/comments/feed/" />
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
	<link rel='stylesheet' id='elementor-frontend-inline-css'  href='<?PHP echo base_url(); ?>assets/themepw1/basecss.css' type='text/css' media='all' />
	<style id='elementor-frontend-inline-css' type='text/css'>
		.elementor-24192 .elementor-element.elementor-element-2b45caa1:not(.elementor-motion-effects-element-type-background), .elementor-24192 .elementor-element.elementor-element-2b45caa1 > .elementor-motion-effects-container > .elementor-motion-effects-layer{background-image:url("<?PHP echo base_url(); ?>images/wedding/<?PHP echo $dirbase; ?>/<?PHP echo $bgquotes; ?>");background-position:center center;background-repeat:no-repeat;background-size:cover;}
		.elementor-element.elementor-element-642a72e .twae-wrapper .twae-timeline-centered .twae-icon {
		    border-color: #D6D6D6;
		}
		.elementor-element.elementor-element-642a72e .twae-wrapper .twae-icon {
		    background-color: #D8AD83;
		    color: #fff9ed;
		}
		.elementor-element.elementor-element-642a72e .twae-wrapper span.twae-label {
		    font-size: 20px;
		    font-weight: 600;
		    color: #D8AD83;
		}
		.elementor-element.elementor-element-642a72e .twae-wrapper:after {
		    background-color: #D6D6D6;
		}
		.elementor-element.elementor-element-642a72e .twae-wrapper .twae-line::before {
		    background-color: #D6D6D6;
		}
		.elementor-element.elementor-element-642a72e .twae-wrapper:before {
		    background-color: #D6D6D6;
		}
		.elementor-element.elementor-element-642a72e .twae-wrapper .twae-data-container:after {
		    border-right-color: #D8AD83;
		}
		.elementor-element.elementor-element-642a72e .twae-wrapper .twae-description {
		    font-size: 16px;
		    font-weight: normal;
		    color: #333333;
		}
		.elementor-element.elementor-element-642a72e .twae-wrapper .twae-data-container span.twae-title {
		    color: #D8AD83;
		}
		.twae-timeline-centered .twae-timeline-entry .twae-timeline-entry-inner .twae-data-container.twae-bg-orange:after {
		    border-color: transparent #e9662c transparent transparent;
		}
		.twae-timeline-centered .twae-timeline-entry .twae-timeline-entry-inner .twae-data-container.twae-bg-orange:after {
		    border-right-color: #D8AD83;
		}
		.elementor-element.elementor-element-642a72e .twae-wrapper .twae-right-aligned .twae-data-container {
		    border-left-color: #D8AD83;
		}
		.elementor-element.elementor-element-642a72e .twae-wrapper .twae-data-container {
		    background-color: #fff9ed;
		}
	</style>
	<link rel='stylesheet' id='elementor-pro-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor-pro/assets/css/frontend.min677a.css?ver=3.0.9' type='text/css' media='all' />
	<link rel='stylesheet' id='weddingpress-wdp-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/css/wdp16b9.css?ver=2.5.2' type='text/css' media='all' />
	<link rel='stylesheet' id='landingpress-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/themes/landingpress-wp/style0226.css?ver=3.1.2' type='text/css' media='all' />
	<link rel='stylesheet' id='google-fonts-1-css'  href='https://fonts.googleapis.com/css?family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto+Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CClicker+Script%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CDancing+Script%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CPlayfair+Display%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CAbril+Fatface%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CABeeZee%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CMedula+One%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;ver=5.5.5' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-icons-shared-0-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/font-awesome/css/fontawesome.minb683.css?ver=5.12.0' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-icons-fa-solid-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/font-awesome/css/solid.minb683.css?ver=5.12.0' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-icons-fa-regular-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/font-awesome/css/regular.minb683.css?ver=5.12.0' type='text/css' media='all' />
	<link rel='stylesheet' id='elementor-icons-fa-brands-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/font-awesome/css/brands.minb683.css?ver=5.12.0' type='text/css' media='all' />

	<link href="https://fonts.googleapis.com/css?family=Dosis:300,400,600,700%7COpen+Sans:300,400,700%7CPlayfair+Display:400,400i,700,700i" rel="stylesheet">
	
	<link rel='shortlink' href='https://numura.id/?p=24192' />
	<style type="text/css">
		@import url('https://fonts.cdnfonts.com/css/bunch-blossoms-personal-use');

		.bgangpaurek {
			margin: 30px auto;
		    background: #fdf5ed;
		    padding: 10px;
		    width: 80%;
		    border-radius: 10px;
		}
		.bgangpaurek .tbig {
			font: italic 600 20px/30px Playfair Display, Arial;
		}
		.bgangpaurek .tbig span {
			font-size: 14px;
		}
		.bgangpaurek .tmid {
			font: 600 16px/26px Dosis, Arial;
		}
		.bgangpaurek .tsmall {
			font: 400 14px/22px Dosis, Arial;
		}
		.kt-portlet__head-title {
			font-family: marsha, Arial;
			color: #D8AD83;
			font-size: 4.5rem;
			margin: 10px auto;
		}
		.descmodal {
			font-family: Dosis, Arial;
		}
		.elementor-widget-weddingpress-guestbook .guestbook-message {
		    font-family: Dosis, Arial;
		    font-weight: 600;
		}
		.elementor-widget-weddingpress-guestbook .guestbook-name {
		    font-family: Dosis, Arial;
		    font-weight: bold;
		    color: #d8ad83;
		}
		.elementor-24192 .elementor-element.elementor-element-52da08f6 > .elementor-element-populated{
			margin: 5% 0% 0% 0%;
		}
		.elementor-24192 .elementor-element.elementor-element-104b980b .elementor-heading-title, .elementor-24192 .elementor-element.elementor-element-65ac96dc .elementor-image-box-description, .elementor-24192 .elementor-element.elementor-element-5f389a91 .elementor-image-box-description, .elementor-24192 .elementor-element.elementor-element-77c63747 .elementor-image-box-description, .elementor-24192 .elementor-element.elementor-element-705ece2 .elementor-image-box-description, .elementor-24192 .elementor-element.elementor-element-31df6edd .elementor-heading-title {
			color: #3D3D3D;
		    font-family: Dosis, Arial;
		    font-size: 20px;
		}
		.elementor-24192 .elementor-element.elementor-element-5a026908:not(.elementor-motion-effects-element-type-background), .elementor-24192 .elementor-element.elementor-element-5a026908 > .elementor-motion-effects-container > .elementor-motion-effects-layer {
			background-color: #343434;
		}
		.elementor-24192 .elementor-element.elementor-element-452e86e0:not(.elementor-motion-effects-element-type-background), .elementor-24192 .elementor-element.elementor-element-452e86e0 > .elementor-motion-effects-container > .elementor-motion-effects-layer {
			background-color: #343434;
		}
		.elementor-element.elementor-element-3d2384d1 .elementor-button, .elementor-widget-button .elementor-button {
		    background-color: #D8AD83;
		    font-family: Dosis, Arial;
		}
		.elementor-element.elementor-element-f11f7a4 .elementor-heading-title {
		    color: #FFFFFF;
		    font-family: Dosis, Arial;
		    font-size: 20px;
		    font-weight: 500;
		    text-transform: uppercase;
		    line-height: 1em;
		    letter-spacing: 0px;
		    text-align: center;
		}
		.elementor-element.elementor-element-495f4f10 .elementor-heading-title {
		    color: #FFFFFF;
		    font-family: Dosis, Arial;
		    font-size: 18px;
		    font-weight: 500;
		    font-style: italic;
		    line-height: 1em;
		    letter-spacing: 0px;
		    text-align: center;
		}
		.elementor-24192 .elementor-element.elementor-element-2ca1d2b9 .wpkoi-elements-countdown-label {
			font-family: Playfair Display, Dosis, Arial;
			font-style: italic;
		}
		.elementor-24192 .elementor-element.elementor-element-2ca1d2b9 .wpkoi-elements-countdown-item > div {
			border-radius: 100%;
			width: 100px;
			height: 100px;
		}
		.lokasistyle {
			font: 400 18px/25px Dosis, Arial
		}
		.lokasistyle b {
			font: italic 400 30px/40px Playfair Display, Arial;
		}
		.elementor-24192 .elementor-element.elementor-element-511adc5d .elementor-icon-box-description, .elementor-24192 .elementor-element.elementor-element-2b09609f .elementor-icon-box-description, .elementor-24192 .elementor-element.elementor-element-796f30e8 .elementor-heading-title, .elementor-24192 .elementor-element.elementor-element-831d65e .elementor-heading-title {
			font-family: Dosis, Arial;
		}
		.elementor-24192 .elementor-element.elementor-element-4c157348 > .elementor-element-populated, .elementor-24192 .elementor-element.elementor-element-323848f6 > .elementor-element-populated {
			padding: 20px 0px 20px 0px;
			border: 0px;
		}
		.elementor-24192 .elementor-element.elementor-element-1742ff63 > .elementor-element-populated {
			padding: 0px 30px 0px 30px;
		}
		.elementor-24192 .elementor-element.elementor-element-511adc5d .elementor-icon-box-title, .elementor-24192 .elementor-element.elementor-element-7b890f23 .elementor-icon-box-title, .elementor-24192 .elementor-element.elementor-element-2b09609f .elementor-icon-box-title {
			font-family: marsha, Arial;
			font-size: 36px;
		}
		#back-to-top {
			bottom: 15px;
		}
		.elementor-24192 .elementor-element.elementor-element-4e6ab9b4 > .elementor-widget-container {
			margin : 80px 0px -40px 0px;
			position: relative;
			z-index: 1;
		}
		.elementor-24192 .elementor-element.elementor-element-4e6ab9b4 .elementor-heading-title {
			font-family: marsha, Arial;
		}
		.elementor-24192 .elementor-element.elementor-element-cb44a05 .elementor-heading-title {
			color: #d8ad83;
			font-family: marsha, Arial;
    		font-size: 63px;
		}
		.elementor-24192 .elementor-element.elementor-element-79fbcdd0 .elementor-heading-title {
			color: #54595f;
		    font-family: Dosis,Arial;
		    font-size: 21px;
		}
		.elementor-24192 .elementor-element.elementor-element-1f6ec9de .elementor-heading-title {
			color: #d8ad83;
		    font-family: marsha, Arial;
		    font-size: 64px;
		    padding-top: 10px;
		}
		.img-savethedate {
			max-width: 250px!important;
		    margin-top: -70px;
		    margin-bottom: -60px;
		    position: relative;
		    z-index: 2;
		}
		.img-rsvp {
			max-width: 290px!important;
    		margin: 4% auto;
		}
		.img-thnk {
			max-width: 210px!important;
    		margin: 20px auto;
		}
		.img-justmar {
			max-width: 250px!important;
		    margin-top: 25px;
			margin-bottom: -80px;
		}
		.img-ourwed {
			max-width: 220px!important;
		    margin-top: -10%;
		    margin-bottom: -5%;
		}
		.elementor-24192 .elementor-element.elementor-element-1b62ac7c .elementor-image-box-title, .elementor-24192 .elementor-element.elementor-element-6d611096 .elementor-image-box-title {
			font-family: marsha, Arial!important;
		}

		.elementor-24192 .elementor-element.elementor-element-1b62ac7c .elementor-image-box-description, .elementor-24192 .elementor-element.elementor-element-6d611096 .elementor-image-box-description {
			font-family: Dosis, Arial!important;
		}

		svg { width: 1em; height: 1em; fill: currentColor; display: inline-block; vertical-align: middle; margin-top: -2px; } 
		.elementor-24192 .elementor-element.elementor-element-3688c961 .elementor-heading-title {
			font-family: marsha, Arial!important;
			font-weight: normal;
			font-size: 145px;
		}
		.elementor-24192 .elementor-element.elementor-element-3688c961 .elementor-heading-title span {
			font-size: 60px;
		}
		.elementor-24192 .elementor-element.elementor-element-12e9168b .elementor-heading-title {
			font-family: 'Bunch Blossoms Personal Use', sans-serif;
			font-weight: normal;
		}

		.elementor-24192 .elementor-element.elementor-element-7fc1ec99 .elementor-heading-title {
			font-family: marsha, Arial;
			font-size: 65px;
			margin-top: 5%;
		}

		.elementor-widget-button .elementor-button {
			font-family: Dosis, Arial;
		}

		.borderprofile {
			width: 280px;
		    height: 280px;
		    margin-bottom: -276px;
		    z-index: 1;
		    position: relative;
		    margin-left: auto;
		    margin-right: auto;
		    margin-top: 10px;
		    background: url('<?PHP echo base_url(); ?>assets/wd-assets/borderprof.png') no-repeat center;
		    background-size: 100% auto;
		}
		.elementor-24192 .elementor-element.elementor-element-1b62ac7c .elementor-image-box-wrapper .elementor-image-box-img {
			width: 250px;
		    border-radius: 100%;
		    height: 250px;
		    margin-bottom: 10px;
		    background: url('<?PHP echo base_url(); ?>images/wedding/<?PHP echo $dirbase; ?>/<?PHP echo $pictw; ?>') no-repeat center;
		    background-size: 105% auto;
		    margin-top: 15px;
		    position: relative;
    		z-index: 2;
    		border: 10px solid #fcfcfc;
		}
		.elementor-24192 .elementor-element.elementor-element-44ca98a7 .elementor-heading-title {
			color: #ffffff;
			font: italic 400 25px/35px Playfair Display, Arial;
		}
		.elementor-24192 .elementor-element.elementor-element-2b45caa1 > .elementor-background-overlay {
		    background-color: #2E2E2E;
		    opacity: 0.59;
		    /*mix-blend-mode: multiply;*/
		    mix-blend-mode: normal;
		    transition: background 0.3s, border-radius 0.3s, opacity 0.3s;
		}
		.elementor-24192 .elementor-element.elementor-element-71e01087 > .elementor-element-populated {
		    transition: background 0.3s, border 0.3s, border-radius 0.3s, box-shadow 0.3s;
		    padding: 30px;
		    background-color: transparent!important;
		    border: none;
		}
		.elementor-24192 .elementor-element.elementor-element-a370ef0 .elementor-heading-title {
		    color: #ffffff;
		    font: 400 20px/30px Dosis, Arial;
		    letter-spacing: 2px;
		}
		.elementor-24192 .elementor-element.elementor-element-6d611096 .elementor-image-box-wrapper .elementor-image-box-img {
			width: 250px;
		    border-radius: 100%;
		    height: 250px;
		    margin-bottom: 10px;
		    background: url('<?PHP echo base_url(); ?>images/wedding/<?PHP echo $dirbase; ?>/<?PHP echo $pictm; ?>') no-repeat center;
		    background-size: 105% auto;
		    margin-top: 15px;
		    position: relative;
    		z-index: 2;
    		border: 10px solid #fcfcfc;
		}
		.elementor-24192 .elementor-element.elementor-element-c1e288d .elementor-heading-title {
			font-family: Dosis, Arial;
		    font-size: 16px;
		    letter-spacing: 2px;
		    font-weight: 400;
		}

		@media (min-width: 1240px) {
			.elementor-24192 .elementor-element.elementor-element-12e9168b .elementor-heading-title {
				margin-bottom: -40px;
	    		margin-top: 20px;
	    		font-size: 39px;
			}
			.elementor-24192 .elementor-element.elementor-element-5875a54e .elementor-heading-title {
				font: 400 23px/30px Dosis, Arial!important;
			}
		}
		@media (max-width: 767px) {
			.kt-portlet__head-title {
				font-size: 3rem;
			}
			.elementor-24192 .elementor-element.elementor-element-7fc1ec99 .elementor-heading-title {
				font-family: marsha, Arial;
				font-size: 45px;
			}
			.elementor-24192 .elementor-element.elementor-element-c1e288d .elementor-heading-title {
				font-family: Dosis, Arial;
			    font-size: 16px;
			    letter-spacing: 2px;
			    font-weight: 400;
			}
			.elementor-24192 .elementor-element.elementor-element-2ca1d2b9 .wpkoi-elements-countdown-item > div {
				width: 80px;
			    height: 80px;
			    padding-top: 6px;
			}
			.elementor-24192 .elementor-element.elementor-element-4e6ab9b4 > .elementor-widget-container {
				margin: 50px 0px -30px 0px;
			}
			.elementor-24192 .elementor-element.elementor-element-4e6ab9b4 .elementor-heading-title {
				font-size: 32px;
			}
			.elementor-24192 .elementor-element.elementor-element-44ca98a7 .elementor-heading-title {
				font: italic 400 18px/22px Playfair Display, Arial;
			}
			.elementor-24192 .elementor-element.elementor-element-a370ef0 .elementor-heading-title {
				font: 400 15px/20px Dosis, Arial;
			}
			.elementor-24192 .elementor-element.elementor-element-1f6ec9de .elementor-heading-title {
				font-size: 34px;
    			padding-top: 0px;
			}
			.elementor-24192 .elementor-element.elementor-element-cb44a05 .elementor-heading-title {
				font-size: 33px;
			}
			.img-savethedate {
				max-width: 200px!important;
			    margin-top: -110px;
			    margin-bottom: -40px;
			}
			.img-rsvp {
				max-width: 200px!important;
    			margin: 10% auto;
			}
			.img-thnk {
			    max-width: 180px!important;
    			margin: 10px auto;
			}
			.img-justmar {
			    max-width: 200px!important;
			    margin-top: 15px;
    			margin-bottom: -50px;
			}
			.img-ourwed {
				max-width: 140px!important;
			    margin-top: -90px;
			    margin-bottom: 50px;
			}
			.elementor-24192 .elementor-element.elementor-element-12e9168b .elementor-heading-title {
			    margin-bottom: -30px;
			    margin-top: 90px;
			    font-size: 29px;
			}
			.elementor-24192 .elementor-element.elementor-element-5875a54e .elementor-heading-title {
				font: 400 18px/25px Dosis, Arial!important;
			}
			.elementor-24192 .elementor-element.elementor-element-3688c961 .elementor-heading-title {
			    font-size: 69px;
			}
				.elementor-24192 .elementor-element.elementor-element-3688c961 .elementor-heading-title span {
				font-size: 40px;
			}
		}
	</style>
	<link rel="icon" href="<?PHP echo base_url(); ?>images/favicosarvel.png" sizes="32x32" />
	<link rel="icon" href="<?PHP echo base_url(); ?>images/favicosarvel.png" sizes="192x192" />
	<link rel="apple-touch-icon" href="<?PHP echo base_url(); ?>images/favicosarvel.png" />
	<meta name="msapplication-TileImage" content="<?PHP echo base_url(); ?>images/favicosarvel.png" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
</head>

<body class="post-template post-template-elementor_canvas single single-post postid-24192 single-format-standard header-active footer-active elementor-default elementor-template-canvas elementor-kit-6 elementor-page elementor-page-24192">
	<div data-elementor-type="wp-post" data-elementor-id="24192" class="elementor elementor-24192" data-elementor-settings="[]">
		<div class="elementor-inner">
			<div class="elementor-section-wrap">
				<section class="elementor-section elementor-top-section elementor-element elementor-element-757a8e72 elementor-section-height-full elementor-section-boxed elementor-section-height-default elementor-section-items-middle" data-id="757a8e72" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;slideshow&quot;,&quot;background_slideshow_gallery&quot;:[<?PHP $xb=0; foreach ($getBanner as $banner) { $xb++; ?>{&quot;id&quot;:<?PHP echo $banner['id']; ?>,&quot;url&quot;:&quot;https:\/\/numura.id\/images\/wedding\/<?PHP echo $dirbase; ?>\/<?PHP echo $banner['pict']; ?>&quot;}<?PHP if ($xb<$jmlban) { echo ','; } ?><?PHP } ?>],&quot;background_slideshow_loop&quot;:&quot;yes&quot;,&quot;background_slideshow_slide_duration&quot;:5000,&quot;background_slideshow_slide_transition&quot;:&quot;fade&quot;,&quot;background_slideshow_transition_duration&quot;:500}">
					<div class="elementor-background-overlay"></div>
					<div class="elementor-container elementor-column-gap-default">
						<div class="elementor-row">
							<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-4fd472fb" data-id="4fd472fb" data-element_type="column">
								<div class="elementor-column-wrap elementor-element-populated">
									<div class="elementor-widget-wrap">
										<div class="elementor-element elementor-element-289bbc90 elementor-widget-divider--separator-type-pattern elementor-widget elementor-widget-divider" data-id="289bbc90" data-element_type="widget" data-widget_type="divider.default">
											<div class="elementor-widget-container">
												<div class="elementor-divider" style="--divider-pattern-url: url(_data_image/svg%2bxml%2c_svg%20xmlns%3dhttp_/www.w3.org/2000/svg%20preserveAspectRatio%3dnone%20overflow%3dvisible%20h/__/svg_.html);">
													<span class="elementor-divider-separator"></span>
												</div>
											</div>
										</div>

										<div class="elementor-element elementor-element-d642ce3 elementor-widget elementor-widget-spacer" data-id="d642ce3" data-element_type="widget" data-widget_type="spacer.default">
											<div class="elementor-widget-container">
												<div class="elementor-spacer">
													<div class="elementor-spacer-inner"></div>
												</div>
											</div>
										</div>

										<div class="elementor-element elementor-element-12e9168b elementor-widget elementor-widget-heading" data-id="12e9168b" data-element_type="widget" data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<!-- <p class="elementor-heading-title elementor-size-default">The Wedding </p> -->
												<img id="imgourwed" src="<?PHP echo base_url(); ?>assets/wd-assets/ourwedding_wh.gif" data-top-bottom="@src:<?PHP echo base_url(); ?>assets/wd-assets/ourwedding_wh.gif" class="img-ourwed skrollable skrollable-before">
											</div>
										</div>

										<div class="elementor-element elementor-element-3501fe5 elementor-widget elementor-widget-spacer" data-id="3501fe5" data-element_type="widget" data-widget_type="spacer.default">
											<div class="elementor-widget-container">
												<div class="elementor-spacer">
													<div class="elementor-spacer-inner"></div>
												</div>
											</div>
										</div>

										<div class="elementor-element elementor-element-3688c961 elementor-widget elementor-widget-heading" data-id="3688c961" data-element_type="widget" data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<span class="elementor-heading-title elementor-size-default"><?PHP echo $queen; ?> <span>&</span> <?PHP echo $king; ?></span>
											</div>
										</div>

										<div class="elementor-element elementor-element-5875a54e elementor-widget elementor-widget-heading" data-id="5875a54e" data-element_type="widget" data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<span class="elementor-heading-title elementor-size-default"><?PHP echo $thedate; ?></span>
											</div>
										</div>

										<div class="elementor-element elementor-element-117979b5 elementor-widget-divider--separator-type-pattern elementor-widget elementor-widget-divider" data-id="117979b5" data-element_type="widget" data-widget_type="divider.default">
											<div class="elementor-widget-container">
												<div class="elementor-divider" style="--divider-pattern-url: url(_data_image/svg%2bxml%2c_svg%20xmlns%3dhttp_/www.w3.org/2000/svg%20preserveAspectRatio%3dnone%20overflow%3dvisible%20h/__/svg_.html);">
													<span class="elementor-divider-separator"></span>
												</div>
											</div>
										</div>

										<section class="elementor-section elementor-inner-section elementor-element elementor-element-12843932 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="12843932" data-element_type="section" data-settings="{&quot;sticky&quot;:&quot;top&quot;,&quot;sticky_offset&quot;:100,&quot;sticky_on&quot;:[&quot;desktop&quot;,&quot;tablet&quot;,&quot;mobile&quot;],&quot;sticky_effects_offset&quot;:0}" style="right: 0px; bottom: 40px; position: fixed; z-index: 99;">
											<div class="elementor-container elementor-column-gap-default">
												<div class="elementor-row">
													<div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-31e953f2" data-id="31e953f2" data-element_type="column">
														<div class="elementor-column-wrap">
															<div class="elementor-widget-wrap"></div>
														</div>
													</div>

													<div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-7604b409" data-id="7604b409" data-element_type="column">
														<div class="elementor-column-wrap">
															<div class="elementor-widget-wrap"></div>
														</div>
													</div>

													<div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-137c6645" data-id="137c6645" data-element_type="column">
														<div class="elementor-column-wrap elementor-element-populated">
															<div class="elementor-widget-wrap">
																<div class="elementor-element elementor-element-17fc9d32 elementor-view-stacked elementor-shape-circle elementor-invisible elementor-widget elementor-widget-weddingpress-audio" data-id="17fc9d32" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;none&quot;,&quot;sticky&quot;:&quot;bottom&quot;,&quot;sticky_offset&quot;:60,&quot;sticky_on&quot;:[&quot;desktop&quot;,&quot;tablet&quot;,&quot;mobile&quot;],&quot;sticky_effects_offset&quot;:0}" data-widget_type="weddingpress-audio.default">
																	<div class="elementor-widget-container">
																		<script>
																			var settingAutoplay = 'yes';
																			window.settingAutoplay = settingAutoplay === 'disable' ? false : true;
																		</script>

																		<div id="audio-container" class="audio-box">
																			<audio id="song" loop>
																				<source src="<?PHP echo base_url(); ?>/images/wedding/<?PHP echo $dirbase; ?>/<?PHP echo $audio; ?>" type="audio/mp3">
																			</audio>

																			<div class="elementor-icon-wrapper" id="unmute-sound" style="display: none;">
																				<div class="elementor-icon">
																					<i aria-hidden="true" class="fas fa-play-circle"></i>
																				</div>
																			</div> 

																			<div class="elementor-icon-wrapper" id="mute-sound" style="display: none;">
																				<div class="elementor-icon">
																					<i aria-hidden="true" class="fas fa-pause-circle"></i>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</section>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				
				<section class="elementor-section elementor-top-section elementor-element elementor-element-669e9d2c elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="669e9d2c" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="elementor-container elementor-column-gap-default">
						<div class="elementor-row">
							<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-39197983" data-id="39197983" data-element_type="column">
								<div class="elementor-column-wrap elementor-element-populated">
									<div class="elementor-widget-wrap">
										<!-- <div class="elementor-element elementor-element-79fbcdd0 elementor-widget elementor-widget-heading" data-id="79fbcdd0" data-element_type="widget" data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default">Tentang Kami</p>
											</div>
										</div> -->

										<div class="elementor-element elementor-element-1f6ec9de elementor-widget elementor-widget-heading" data-id="1f6ec9de" data-element_type="widget" data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default">- Pasangan Mempelai -</p>
											</div>
										</div>

										<section class="elementor-section elementor-inner-section elementor-element elementor-element-414e92d7 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="414e92d7" data-element_type="section">
											<div class="elementor-container elementor-column-gap-default">
												<div class="elementor-row">
													<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-3e5dc7b7 elementor-invisible" data-id="3e5dc7b7" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;fadeInUp&quot;}">
														<div class="elementor-column-wrap elementor-element-populated">
															<div class="elementor-widget-wrap">
																<div class="elementor-element elementor-element-1b62ac7c elementor-position-top elementor-vertical-align-top elementor-widget elementor-widget-image-box" data-id="1b62ac7c" data-element_type="widget" data-widget_type="image-box.default">
																	<div class="elementor-widget-container">
																		<div class="elementor-image-box-wrapper">
																			<div class="borderprofile"></div>
																			<figure class="elementor-image-box-img">
																				<!-- <img width="508" height="447" src="<?PHP echo base_url(); ?>images/wedding/<?PHP echo $dirbase; ?>/<?PHP echo $pictw; ?>" class="attachment-full size-full" alt="" loading="lazy" /> -->
																			</figure>
																			<div class="elementor-image-box-content">
																				<p class="elementor-image-box-title"><?PHP echo $fullqueen; ?></p>
																				<?PHP if ($cekmodig==1) { ?>
																				<p class="elementor-image-box-description">
																					<a href="https://instagram.com/<?PHP echo $igqueen; ?>" target="_blank"><i class="fab fa-instagram"></i> <?PHP echo $igqueen; ?></a>
																				</p>
																				<?PHP } ?>
																				<p class="elementor-image-box-description"><b>Putri dari<br></b>
																					<?PHP echo $parentq; ?>
																				</p>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>

													<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-5b3375a1 elementor-invisible" data-id="5b3375a1" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;fadeInUp&quot;,&quot;animation_delay&quot;:300}">
														<div class="elementor-column-wrap elementor-element-populated">
															<div class="elementor-widget-wrap">
																<div class="elementor-element elementor-element-6d611096 elementor-position-top elementor-vertical-align-top elementor-widget elementor-widget-image-box" data-id="6d611096" data-element_type="widget" data-widget_type="image-box.default">
																	<div class="elementor-widget-container">
																		<div class="elementor-image-box-wrapper">
																			<div class="borderprofile"></div>
																			<figure class="elementor-image-box-img">
																				<!-- <img width="508" height="447" src="<?PHP echo base_url(); ?>images/wedding/<?PHP echo $dirbase; ?>/<?PHP echo $pictm; ?>" class="attachment-full size-full" alt="" loading="lazy" /> -->
																			</figure>
																			<div class="elementor-image-box-content">
																				<p class="elementor-image-box-title"><?PHP echo $fullking; ?></p>
																				<?PHP if ($cekmodig==1) { ?>
																				<p class="elementor-image-box-description">
																					<a href="https://instagram.com/<?PHP echo $igking; ?>" target="_blank"><i class="fab fa-instagram"></i> <?PHP echo $igking; ?></a>
																				</p>
																				<?PHP } ?>
																				<p class="elementor-image-box-description"><b>Putra dari
																					<br></b><?PHP echo $parentk; ?>
																				</p>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</section>

										<div class="elementor-element elementor-element-62c2f24c elementor-widget-divider--separator-type-pattern elementor-widget elementor-widget-divider" data-id="62c2f24c" data-element_type="widget" data-widget_type="divider.default">
											<div class="elementor-widget-container">
												<div class="elementor-divider" style="--divider-pattern-url: url(_data_image/svg%2bxml%2c_svg%20xmlns%3dhttp_/www.w3.org/2000/svg%20preserveAspectRatio%3dnone%20overflow%3dvisible%20h/__/svg_-2.html);">
													<span class="elementor-divider-separator"></span>
												</div>
											</div>
										</div>

										<div class="elementor-element elementor-element-cb44a05 elementor-widget elementor-widget-heading" data-id="cb44a05" data-element_type="widget" data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default">- Cerita Kita -</p>
											</div>
										</div>

										<div class="elementor-element elementor-element-7ee7dc46 elementor-widget elementor-widget-gallery" data-id="7ee7dc46" data-element_type="widget" data-settings="{&quot;columns_mobile&quot;:2,&quot;aspect_ratio&quot;:&quot;1:1&quot;,&quot;lazyload&quot;:&quot;yes&quot;,&quot;gallery_layout&quot;:&quot;grid&quot;,&quot;columns&quot;:4,&quot;columns_tablet&quot;:2,&quot;gap&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:10,&quot;sizes&quot;:[]},&quot;gap_tablet&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:10,&quot;sizes&quot;:[]},&quot;gap_mobile&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:10,&quot;sizes&quot;:[]},&quot;link_to&quot;:&quot;file&quot;,&quot;overlay_background&quot;:&quot;yes&quot;,&quot;content_hover_animation&quot;:&quot;fade-in&quot;}" data-widget_type="gallery.default">
											<div class="elementor-widget-container">
												<div class="elementor-gallery__container">
													<?PHP
													$ng = 0;
													foreach ($getGal as $gal) {
													?>
													<a class="e-gallery-item elementor-gallery-item elementor-animated-content" href="<?PHP echo base_url(); ?>images/wedding/<?PHP echo $dirbase; ?>/<?PHP echo $gal['pict']; ?>" data-elementor-open-lightbox="yes" data-elementor-lightbox-slideshow="all-7ee7dc46" data-elementor-lightbox-title="<?PHP echo $gal['pict']; ?> (<?PHP echo $ng; ?>) Numura.id">
														<div class="e-gallery-image elementor-gallery-item__image" data-thumbnail="<?PHP echo base_url(); ?>images/wedding/<?PHP echo $dirbase; ?>/<?PHP echo $gal['pict']; ?>" data-width="750" data-height="500" alt="" ></div>
														<div class="elementor-gallery-item__overlay"></div>
													</a>
													<?PHP } ?>
												</div>
											</div>
										</div>

										<div class="elementor-element elementor-element-642a72e wdp-sticky-section-no elementor-widget elementor-widget-weddingpress-timeline" data-id="642a72e" data-element_type="widget" data-widget_type="weddingpress-timeline.default" style="margin-top: 20px; display: none;">
											<div class="elementor-widget-container">
												<div class="twae-vertical twae-wrapper twae-centered">    
													<div class="twae-timeline-centered twae-timeline-sm twae-line ">
														<article class="twae-timeline-entry twae-right-aligned">
															<div class="twae-timeline-entry-inner">
																<time class="twae-label-extra-label">
																	<span class="twae-label">Jul 1994</span>
																	<span class="twae-extra-label"></span>
																</time>
																<div class="twae-bg-orange twae-icon"><i aria-hidden="true" class="fas fa-heart"></i></div>
																<div class="twae-bg-orange twae-data-container">
																	<span class="twae-title">Awal Bertemu</span>
																	<div class="twae-description">
																		Lorem ipsum dolor sit amet, consectetur adipiscing elit. Erat enim res aperta. Ne discipulum abducam, times. Primum quid tu dicis breve? An haec ab eo non dicuntur?
																	</div>
																</div>
															</div>
														</article>

														<article class="twae-timeline-entry twae-left-aligned">
															<div class="twae-timeline-entry-inner">
																<time class="twae-label-extra-label">
																	<span class="twae-label">Jul 1994</span>
																	<span class="twae-extra-label"></span>
																</time>
																<div class="twae-bg-orange twae-icon"><i aria-hidden="true" class="fas fa-heart"></i></div>
																<div class="twae-bg-orange twae-data-container">
																	<span class="twae-title">Awal Bertemu</span>
																	<div class="twae-description">
																		Lorem ipsum dolor sit amet, consectetur adipiscing elit. Erat enim res aperta. Ne discipulum abducam, times. Primum quid tu dicis breve? An haec ab eo non dicuntur?
																	</div>
																</div>
															</div>
														</article>

														<article class="twae-timeline-entry twae-right-aligned">
															<div class="twae-timeline-entry-inner">
																<time class="twae-label-extra-label">
																	<span class="twae-label">Jul 1994</span>
																	<span class="twae-extra-label"></span>
																</time>
																<div class="twae-bg-orange twae-icon"><i aria-hidden="true" class="fas fa-heart"></i></div>
																<div class="twae-bg-orange twae-data-container">
																	<span class="twae-title">Awal Bertemu</span>
																	<div class="twae-description">
																		Lorem ipsum dolor sit amet, consectetur adipiscing elit. Erat enim res aperta. Ne discipulum abducam, times. Primum quid tu dicis breve? An haec ab eo non dicuntur?
																	</div>
																</div>
															</div>
														</article>
													</div>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				
				<section class="elementor-section elementor-top-section elementor-element elementor-element-2b45caa1 elementor-section-height-min-height elementor-section-boxed elementor-section-height-default elementor-section-items-middle" data-id="2b45caa1" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="elementor-background-overlay"></div>
					
					<div class="elementor-element elementor-element-12e9168b elementor-widget elementor-widget-heading" data-id="12e9168b" data-element_type="widget" data-widget_type="heading.default">
						<div class="elementor-widget-container">
							<!-- <p class="elementor-heading-title elementor-size-default">The Wedding </p> -->
							<img src="<?PHP echo base_url(); ?>assets/wd-assets/just_wh.gif" data-top-bottom="@src:<?PHP echo base_url(); ?>assets/wd-assets/just_wh.gif" class="img-justmar skrollable skrollable-before">
						</div>
					</div>

					<div class="elementor-container elementor-column-gap-default">
						<div class="elementor-row">
							<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-71e01087" data-id="71e01087" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
								<div class="elementor-column-wrap elementor-element-populated">
									<div class="elementor-widget-wrap">
										<div class="elementor-element elementor-element-44ca98a7 elementor-widget elementor-widget-heading" data-id="44ca98a7" data-element_type="widget" data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default">" <?PHP echo $quotes; ?> "</p>
											</div>
										</div>
										<div class="elementor-element elementor-element-a370ef0 elementor-widget elementor-widget-heading" data-id="a370ef0" data-element_type="widget" data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default">- <?PHP echo $quotesby; ?> -</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				
				<section class="elementor-section elementor-top-section elementor-element elementor-element-64f3080c elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="64f3080c" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="elementor-container elementor-column-gap-default">
						<div class="elementor-row">
							<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-47353213" data-id="47353213" data-element_type="column">
								<div class="elementor-column-wrap elementor-element-populated">
									<div class="elementor-widget-wrap">
										<div class="elementor-element elementor-element-655b6036 elementor-widget elementor-widget-heading" data-id="655b6036" data-element_type="widget" data-widget_type="heading.default">
											<div class="elementor-widget-container">
												<!-- <p class="elementor-heading-title elementor-size-default">Acara Spesial</p> -->
											</div>
										</div>

										<div class="elementor-element elementor-element-4e6ab9b4 elementor-widget elementor-widget-heading" data-id="4e6ab9b4" data-element_type="widget" data-widget_type="heading.default">
											<img id="imgsavethedate" src="<?PHP echo base_url(); ?>assets/wd-assets/savethedate.gif" data-top-bottom="@src:<?PHP echo base_url(); ?>assets/wd-assets/savethedate.gif" class="img-savethedate skrollable skrollable-before">
											<div class="elementor-widget-container">
												<p class="elementor-heading-title elementor-size-default"><?PHP echo $thedate; ?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>
				
				<section class="elementor-section elementor-top-section elementor-element elementor-element-6bdfb21 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="6bdfb21" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
					<div class="elementor-container elementor-column-gap-default">
						<div class="elementor-row">
							<div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-1742ff63 elementor-invisible" data-id="1742ff63" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;slideInUp&quot;}">
								<div class="elementor-column-wrap elementor-element-populated">
									<div class="elementor-widget-wrap">
										<section class="elementor-section elementor-inner-section elementor-element elementor-element-7abed6a elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="7abed6a" data-element_type="section">
											<div class="elementor-container elementor-column-gap-default">
												<div class="elementor-row">
													<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-4c157348" data-id="4c157348" data-element_type="column" style="width: 100%;">
														<div class="elementor-column-wrap elementor-element-populated">
															<div class="elementor-widget-wrap">
																<div class="elementor-element elementor-element-511adc5d elementor-view-default elementor-position-top elementor-vertical-align-top elementor-widget elementor-widget-icon-box" data-id="511adc5d" data-element_type="widget" data-widget_type="icon-box.default">
																	<div class="elementor-widget-container">
																		<div class="elementor-icon-box-content">
																			<p class="elementor-icon-box-title" style="text-align: center;">
																				<span >Akad Nikah</span>
																			</p>
																		</div>
																		<div class="elementor-icon-box-wrapper">
																			<div class="elementor-icon-box-content">
																				<p class="elementor-heading-title elementor-size-default">
																					<b>Tempat</b>:<br><span class="lokasistyle"><?PHP echo $akadat; ?></span><br><br>
																				</p>
																				<p class="elementor-icon-box-description" style="font-size: 18px; font-weight: 600;">
																					<i aria-hidden="true" class="far fa-calendar-alt"></i> <?PHP echo $akaddate; ?>
																				</p>
																				<p class="elementor-icon-box-description">
																					<i aria-hidden="true" class="far fa-clock"></i> <?PHP echo $akadstart; ?> WIB - <?PHP echo $akadto; ?> WIB
																				</p>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</section>
										</div>
									</div>
								</div>

								<div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-5052e735 elementor-invisible" data-id="5052e735" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;slideInUp&quot;,&quot;animation_delay&quot;:300}">
									<div class="elementor-column-wrap elementor-element-populated">
										<div class="elementor-widget-wrap">

											<section class="elementor-section elementor-inner-section elementor-element elementor-element-2bca82c4 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="2bca82c4" data-element_type="section">
												<div class="elementor-container elementor-column-gap-default">
													<div class="elementor-row">
														<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-323848f6" data-id="323848f6" data-element_type="column" style="width: 100%;">
															<div class="elementor-column-wrap elementor-element-populated" style="padding-top: 0px; margin-top:0px;">
																<div class="elementor-widget-wrap">
																	<div class="elementor-element elementor-element-2b09609f elementor-view-default elementor-position-top elementor-vertical-align-top elementor-widget elementor-widget-icon-box" data-id="2b09609f" data-element_type="widget" data-widget_type="icon-box.default">
																		<div class="elementor-widget-container">
																			<div class="elementor-icon-box-content">
																				<p class="elementor-icon-box-title" style="text-align: center;">
																					<span >Resepsi Pernikahan</span>
																				</p>
																			</div>
																			<div class="elementor-icon-box-wrapper">
																				<div class="elementor-icon-box-content">
																					<p class="elementor-heading-title elementor-size-default">
																						<b>Tempat</b>:<br><span class="lokasistyle"><?PHP echo $reseptionat; ?></span><br><br>
																					</p>
																					<p class="elementor-icon-box-description" style="font-size: 18px; font-weight: 600;">
																						<i aria-hidden="true" class="far fa-calendar-alt"></i> <?PHP echo $reseptiondate; ?>
																					</p>
																					<p class="elementor-icon-box-description">
																						<i aria-hidden="true" class="far fa-clock"></i> <?PHP echo $reseptionstart; ?> WIB - <?PHP echo $reseptionto; ?> WIB
																					</p>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</section>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
				
					<section class="elementor-section elementor-top-section elementor-element elementor-element-7bbe1e2 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="7bbe1e2" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
						<div class="elementor-container elementor-column-gap-default">
							<div class="elementor-row">
								<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-399c4534" data-id="399c4534" data-element_type="column">
									<div class="elementor-column-wrap elementor-element-populated">
										<div class="elementor-widget-wrap">
											<div class="elementor-element elementor-element-2ca1d2b9 elementor-widget elementor-widget-weddingpress-countdown" data-id="2ca1d2b9" data-element_type="widget" data-widget_type="weddingpress-countdown.default">
												<div class="elementor-widget-container">
													<div class="wpkoi-elements-countdown-wrapper">
														<div class="wpkoi-elements-countdown-container wpkoi-elements-countdown-label-block ">		
															<ul id="wpkoi-elements-countdown-2ca1d2b9" class="wpkoi-elements-countdown-items" data-date="<?PHP echo $theday; ?>">
																<li class="wpkoi-elements-countdown-item">
																	<div class="wpkoi-elements-countdown-days">
																		<span data-days class="wpkoi-elements-countdown-digits" id="harih">00</span>
																		<span class="wpkoi-elements-countdown-label">Hari</span>
																	</div>
																</li>
																<li class="wpkoi-elements-countdown-item">
																	<div class="wpkoi-elements-countdown-hours">
																		<span data-hours class="wpkoi-elements-countdown-digits" id="jamh">00</span>
																		<span class="wpkoi-elements-countdown-label">Jam</span>
																	</div>
																</li>
																<li class="wpkoi-elements-countdown-item">
																	<div class="wpkoi-elements-countdown-minutes">
																		<span data-minutes class="wpkoi-elements-countdown-digits" id="menith">00</span>
																		<span class="wpkoi-elements-countdown-label">Menit</span>
																	</div>
																</li>
																<li class="wpkoi-elements-countdown-item">
																	<div class="wpkoi-elements-countdown-seconds">
																		<span data-seconds class="wpkoi-elements-countdown-digits" id="detikh">00</span>
																		<span class="wpkoi-elements-countdown-label">Detik</span>
																	</div>
																</li>
															</ul>
															<div class="clearfix"></div>
														</div>
													</div>
													<script type="text/javascript">
													jQuery(document).ready(function($) {
														'use strict';
														$("#wpkoi-elements-countdown-2ca1d2b9").countdown();
													});
													</script>
												</div>
											</div>

											<div class="elementor-element elementor-element-5366a54f elementor-invisible elementor-widget elementor-widget-google_maps" data-id="5366a54f" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="google_maps.default" style="margin-top: 40px;">
												<div class="elementor-widget-container">
													<div class="elementor-custom-embed">
														<?PHP echo $maplocation; ?>
													</div>
												</div>
											</div>

											<div class="elementor-element elementor-element-5cece26d elementor-align-center elementor-widget elementor-widget-button" data-id="5cece26d" data-element_type="widget" data-widget_type="button.default">
												<div class="elementor-widget-container">
													<div class="elementor-button-wrapper">
														<a href="<?PHP echo $maplink; ?>" class="elementor-button-link elementor-button elementor-size-sm" role="button">
															<span class="elementor-button-content-wrapper">
																<span class="elementor-button-icon elementor-align-icon-left">
																	<i aria-hidden="true" class="fas fa-map-marked-alt"></i>
																</span>
																<span class="elementor-button-text">Open Map</span>
															</span>
														</a>
													</div>
												</div>
											</div>
											<br><br><br>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
					
					<section class="elementor-section elementor-top-section elementor-element elementor-element-5a026908 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="5a026908" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
						<div class="elementor-container elementor-column-gap-default">
							<div class="elementor-row">
								<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-ae61cf" data-id="ae61cf" data-element_type="column">
									<div class="elementor-column-wrap elementor-element-populated">
										<div class="elementor-widget-wrap">

											<div class="elementor-element elementor-element-7fc1ec99 elementor-widget elementor-widget-heading" data-id="7fc1ec99" data-element_type="widget" data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<img src="<?PHP echo base_url(); ?>assets/wd-assets/rsvp_wh.gif" data-top-bottom="@src:<?PHP echo base_url(); ?>assets/wd-assets/rsvp_wh.gif" class="img-rsvp skrollable skrollable-before">
												</div>
											</div>

											<div class="elementor-element elementor-element-388c5ffd elementor-widget elementor-widget-weddingpress-guestbook" data-id="388c5ffd" data-element_type="widget" data-widget_type="weddingpress-guestbook.default">
												<div class="elementor-widget-container">
													<div class="guestbook-box-content elementor-comment-box-wrapper" data-id="<?PHP echo $queen; ?><?PHP echo $king; ?>">
														<div class="comment-form-container">
															<form id="rsvp-box" method="post">
																<div class="guestbook-label">
																	<label class="elementor-screen-only">Nama</label>
																</div>
																<input type="hidden" name="roid" id="roid" value="<?PHP echo $baseid; ?>">
																<input class="form-control" type="text" name="rname" id="rname" placeholder="Isikan Nama" required >

																<div class="guestbook-label">
																	<label class="elementor-screen-only">Alamat</label>
																</div>
																<input class="form-control" type="text" name="raddr" id="raddr" placeholder="Isikan Alamat" required >

																<div class="wdp-form-field-option wdp-option-type-radio" style="color: #FFF; font-family: Dosis, Arial;">
																	<div>
																		<label for="wdp-form-option-32499ed6 wdp-form-option-32499ed6" class="">
																		Konfirmasi Kehadiran :
																		</label>
																	</div>

																	<div>
																		<label class="to-select-option"><input type="radio" name="konfhadir" class="wdp-form-option wdp-form-option" required="1" id="konfhadir" value="Iya, Saya akan Hadir"> Iya, Saya akan Hadir</label>
																	</div>
																	<div>
																		<label class="to-select-option"><input type="radio" name="konfhadir" class="wdp-form-option wdp-form-option" required="1" id="konfhadir" value="Saya Masih Ragu"> Saya Masih Ragu</label>
																	</div>
																	<div>
																		<label class="to-select-option"><input type="radio" name="konfhadir" class="wdp-form-option wdp-form-option" required="1" id="konfhadir" value="Maaf, Saya Tidak Bisa Hadir"> Maaf, Saya Tidak Bisa Hadir</label>
																	</div>
																</div>
																<div class="elementor-button-wrapper">
																	<button type="submit" id="rsvpviawa" class="elementor-button-link elementor-button elementor-size-sm">
																		Reservasi via Whatsapp
																	</button>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>

											<div class="elementor-element elementor-element-27aa024b elementor-widget-divider--separator-type-pattern elementor-widget elementor-widget-divider" data-id="27aa024b" data-element_type="widget" data-widget_type="divider.default">
												<div class="elementor-widget-container">
													<div class="elementor-divider" style="--divider-pattern-url: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' preserveAspectRatio='none' overflow='visible' height='100%' viewBox='0 0 24 24' stroke='%23d8ad83' stroke-width='3' fill='none' stroke-linecap='square' stroke-miterlimit='10'%3E%3Cpolyline points='0,18 12,6 24,18 '/%3E%3C/svg%3E&quot;);">
														<span class="elementor-divider-separator"></span>
													</div>
												</div>
											</div>

											<div class="elementor-element elementor-element-7fc1ec99 elementor-widget elementor-widget-heading" data-id="7fc1ec99" data-element_type="widget" data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">- Kirimkan Pesan -</p>
												</div>
											</div>

											<div class="elementor-element elementor-element-c1e288d elementor-widget elementor-widget-heading" data-id="c1e288d" data-element_type="widget" data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">UNTUK KAMI BERDUA</p>
												</div>
											</div>

											<div class="elementor-element elementor-element-388c5ffd elementor-widget elementor-widget-weddingpress-guestbook" data-id="388c5ffd" data-element_type="widget" data-widget_type="weddingpress-guestbook.default">
												<div class="elementor-widget-container">
													<div class="guestbook-box-content elementor-comment-box-wrapper" data-id="<?PHP echo $queen; ?><?PHP echo $king; ?>">
														<div class="comment-form-container">
															<form id="post-guestbook-box" method="post">
																<div class="guestbook-label">
																	<label class="elementor-screen-only">Nama</label>
																</div>
																<input type="hidden" name="oid" id="oid" value="<?PHP echo $baseid; ?>">
																<input class="form-control" type="text" name="gsname" id="gsname" placeholder="Isikan Nama" required >

																<div class="guestbook-label">
																	<label class="elementor-screen-only">Pesan</label>
																</div>

																<textarea class="form-control" rows="3" name="gsmsg" id="gsmsg" placeholder="Tuliskan Pesan dan Doa" required ></textarea>

																<div class="elementor-button-wrapper">
																	<button type="submit" id="kirimucapan" class="elementor-button-link elementor-button elementor-size-sm">
																		Kirim Ucapan
																	</button>
																</div>
															</form>
														</div>

														<div class="guestbook-list" id="bgguest">
															<?PHP
															$qUcapan 	= "
																		SELECT * FROM detail_ucapan where orderid=? order by id desc
																		";
															$getUcapan 	= $this->db->query($qUcapan, $baseid)->result_array();
															foreach ($getUcapan as $ucp) {
															?>
															<div class="user-guestbook">
																<div>
																	<img src="<?PHP echo base_url(); ?>assets/wd-assets/07-2.png" title="07.png" alt="07.png" />
																</div>

																<div class="guestbook">
																	<div class="guestbook-name"><?PHP echo $ucp['name']; ?></div>
																	<div class="guestbook-message"><?PHP echo str_replace('?','.',$ucp['msg']); ?></div>
																</div>
															</div>
															<?PHP } ?>
														</div>
													</div>
												</div>
											</div>

											<div class="elementor-element elementor-element-2a30d6c elementor-widget elementor-widget-divider" data-id="2a30d6c" data-element_type="widget" data-widget_type="divider.default">
												<div class="elementor-widget-container">
													<div class="elementor-divider">
														<span class="elementor-divider-separator"></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>

					<section class="elementor-section elementor-top-section elementor-element elementor-element-7b2b2ab2 elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-invisible" data-id="7b2b2ab2" data-element_type="section" data-settings="{&quot;shape_divider_top&quot;:&quot;clouds&quot;,&quot;animation&quot;:&quot;fadeInUp&quot;}">

						<div class="elementor-container elementor-column-gap-default">
							<div class="elementor-row">
								<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-52da08f6" data-id="52da08f6" data-element_type="column">
									<div class="elementor-column-wrap elementor-element-populated">
										<div class="elementor-widget-wrap">
											<div class="elementor-element elementor-element-104b980b elementor-widget elementor-widget-heading" data-id="104b980b" data-element_type="widget" data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default"><?PHP echo $prokes; ?></p>
												</div>
											</div>

											<section class="elementor-section elementor-inner-section elementor-element elementor-element-52ec3d77 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="52ec3d77" data-element_type="section">
												<div class="elementor-container elementor-column-gap-default">
													<div class="elementor-row">
														<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-49d2ead7" data-id="49d2ead7" data-element_type="column">
															<div class="elementor-column-wrap elementor-element-populated">
																<div class="elementor-widget-wrap">
																	<div class="elementor-element elementor-element-65ac96dc elementor-position-right elementor-vertical-align-middle elementor-widget elementor-widget-image-box" data-id="65ac96dc" data-element_type="widget" data-widget_type="image-box.default">
																		<div class="elementor-widget-container">
																			<div class="elementor-image-box-wrapper">
																				<figure class="elementor-image-box-img">
																					<img width="350" height="350" src="<?PHP echo base_url(); ?>assets/themepw1/wp-content/uploads/2020/12/wdp-ikon_covid_03.png" class="attachment-full size-full" alt="" loading="lazy" />
																				</figure>

																				<div class="elementor-image-box-content">
																					<p class="elementor-image-box-description">Tamu undangan wajib<br>menggunakan masker.</p>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="elementor-element elementor-element-77c63747 elementor-position-right elementor-vertical-align-middle elementor-widget elementor-widget-image-box" data-id="77c63747" data-element_type="widget" data-widget_type="image-box.default">
																		<div class="elementor-widget-container">
																			<div class="elementor-image-box-wrapper">
																				<figure class="elementor-image-box-img">
																					<img width="350" height="350" src="<?PHP echo base_url(); ?>assets/themepw1/wp-content/uploads/2020/12/wdp-ikon_covid_04.png" class="attachment-full size-full" alt="" loading="lazy" />
																				</figure>

																				<div class="elementor-image-box-content">
																					<p class="elementor-image-box-description">Jaga jarak antar orang <br>minimal sekitar 1 meter.</p>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>

														<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-5937443c" data-id="5937443c" data-element_type="column">
															<div class="elementor-column-wrap elementor-element-populated">
																<div class="elementor-widget-wrap">
																	<div class="elementor-element elementor-element-5f389a91 elementor-position-left elementor-vertical-align-middle elementor-widget elementor-widget-image-box" data-id="5f389a91" data-element_type="widget" data-widget_type="image-box.default">
																		<div class="elementor-widget-container">
																			<div class="elementor-image-box-wrapper">
																				<figure class="elementor-image-box-img">
																					<img width="350" height="350" src="<?PHP echo base_url(); ?>assets/themepw1/wp-content/uploads/2020/12/wdp-ikon_covid_01.png" class="attachment-full size-full" alt="" loading="lazy" />
																				</figure>

																				<div class="elementor-image-box-content">
																					<p class="elementor-image-box-description">Suhu tubuh normal<br>(dibawah 37,5°C)</p>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="elementor-element elementor-element-705ece2 elementor-position-left elementor-vertical-align-middle elementor-widget elementor-widget-image-box" data-id="705ece2" data-element_type="widget" data-widget_type="image-box.default">
																		<div class="elementor-widget-container">
																			<div class="elementor-image-box-wrapper">
																				<figure class="elementor-image-box-img">
																					<img width="350" height="350" src="<?PHP echo base_url(); ?>assets/themepw1/wp-content/uploads/2020/12/wdp-ikon_covid_02.png" class="attachment-full size-full" alt="" loading="lazy" />
																				</figure>

																				<div class="elementor-image-box-content">
																					<p class="elementor-image-box-description">Cuci tangan menggunakan air dan sabun atau menggunakan hand sanitizer.</p>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</section>

											<div class="elementor-element elementor-element-31df6edd elementor-widget elementor-widget-heading" data-id="31df6edd" data-element_type="widget" data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">Bagi para tamu undangan diharapkan mengikuti protokol pencegahan COVID-19.</p><br><br>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</section>
					
					<section class="elementor-section elementor-top-section elementor-element elementor-element-452e86e0 elementor-section-full_width elementor-section-height-default elementor-section-height-default" data-id="452e86e0" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
						<div class="elementor-container elementor-column-gap-default">
							<div class="elementor-row">
								<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-7ea1c31e" data-id="7ea1c31e" data-element_type="column">
									<div class="elementor-column-wrap">
										<div class="elementor-widget-wrap">
										</div>
									</div>
								</div>

								<div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-402fddb3" data-id="402fddb3" data-element_type="column">
									<div class="elementor-column-wrap elementor-element-populated">
										<div class="elementor-widget-wrap">
											<img src="<?PHP echo base_url(); ?>assets/wd-assets/thnyou_wh.gif" data-top-bottom="@src:<?PHP echo base_url(); ?>assets/wd-assets/thnyou_wh.gif" class="img-thnk skrollable skrollable-before">

											<section class="elementor-section elementor-inner-section elementor-element elementor-element-e7355ef elementor-section-boxed elementor-section-height-default elementor-section-height-default wdp-sticky-section-no" data-id="e7355ef" data-element_type="section">
												<div class="elementor-container elementor-column-gap-default">
													<div class="elementor-row">
														<div class="elementor-column elementor-col-100 elementor-inner-column elementor-element elementor-element-2058462 wdp-sticky-section-no" data-id="2058462" data-element_type="column">
															<div class="elementor-column-wrap elementor-element-populated">
																<div class="elementor-widget-wrap">
																	<div class="elementor-element elementor-element-f11f7a4 wdp-sticky-section-no elementor-widget elementor-widget-heading" data-id="f11f7a4" data-element_type="widget" data-widget_type="heading.default">
																		<div class="elementor-widget-container">
																			<p class="elementor-heading-title elementor-size-default">
																			untuk yang berhalangan hadir, kami tambahkan fitur dibawah ini
																			</p>
																		</div>
																	</div>
																
																	<div class="elementor-element elementor-element-3d2384d1 elementor-align-center animated-slow wdp-sticky-section-no elementor-widget elementor-widget-button animated fadeInUp" data-id="3d2384d1" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}" data-widget_type="button.default">
																		<div class="elementor-widget-container">
																			<div class="elementor-button-wrapper">
																				<a href="#" data-toggle="modal" data-target="#modalangpau" class="elementor-button-link elementor-button elementor-size-sm" role="button">
																					<span class="elementor-button-content-wrapper">
																						<span class="elementor-button-icon elementor-align-icon-left">
																							<i aria-hidden="true" class="fas fa-money-bill-wave"></i>
																						</span>
																						<span class="elementor-button-text">Amplop Digital</span>
																					</span>
																				</a>
																				<?PHP if ($modlive==1) { ?>
																				<a href="https://instagram.com/<?PHP echo $acciglive; ?>" class="elementor-button elementor-size-sm" role="button">
																					<span class="elementor-button-content-wrapper">
																						<span class="elementor-button-icon elementor-align-icon-left">
																							<i aria-hidden="true" class="fab fa-instagram"></i>
																						</span>
																						<span class="elementor-button-text">Live</span>
																					</span>
																				</a>
																				<?PHP } ?>
																				<a href="#" data-toggle="modal" data-target="#modalgift" class="elementor-button-link elementor-button elementor-size-sm" role="button">
																					<span class="elementor-button-content-wrapper">
																						<span class="elementor-button-icon elementor-align-icon-left">
																							<i aria-hidden="true" class="fas fa-gift"></i>
																						</span>
																						<span class="elementor-button-text">Send a gift</span>
																					</span>
																				</a>
																			</div>
																		</div>
																	</div>

																	<!-- MODAL ANGPAU -->
																	<div class="modal fade" id="modalangpau" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
																		<div class="modal-dialog modal-lg" role="document">
																			<div class="modal-content">
																				<div class="modal-body kt-portlet kt-portlet--tabs" style="margin-bottom: 0px;">
																					<div class="kt-portlet__head" style="border: none;">
																						<div class="" style="width:100%;">
																							<h1 class="kt-portlet__head-title text-center">- Amplop Digital -</h1>
																						</div>
																						<div class="kt-portlet__head-toolbar">
																							<a href="#" data-dismiss="modal" class="text-dark"><i class="fa fa-times"></i></a>
																						</div>
																					</div>
																					<div class="kt-portlet__body">
																						<div class="descmodal text-center">Bagi yang ingin memberikan amplop sebagai hadiah, bisa dilakukan dengan cara transfer ke rekening berikut :</div>

																						<div class="bgangpaurek text-center">
																							<div class="tbig"><?PHP echo $angpaubank; ?></div>
																							<div class="tsmall"><?PHP echo $angpaunorek; ?></div>
																							<div class="tmid"><?PHP echo $angpauan; ?></div>
																						</div>

																						<div class="descmodal text-center">Silahkan copy nomor rekening <?PHP echo $angpaubank; ?> mempelai dibawah ini:</div>
																						<div class="descmodal text-center">
																							<input type="text" readonly name="norek" id="norek" value="<?PHP echo $angpaunorek; ?>">
																							<a href="#" class="btn btn-dark text-white" id="copynorek">Copy Nomor Rekening</a>
																						</div><br>

																						<div class="descmodal text-center">Untuk konfirmasi pengiriman Amplop Digital boleh konfirmasi ke nomor Whatsapp di bawah ini.<br>Terimakasih 😊🙏</div>
																					</div>
																				</div>
																				<div class="modal-footer" style="display: block; text-align: center;">
																					<a href="https://api.whatsapp.com/send?phone=<?PHP echo $rsvpnum; ?>" target="_blank" class="btn btn-dark text-white"><i class="fa fa-paper-plane"></i> Kirim Whatsapp</a>
																				</div>
																			</div>
																		</div>
																	</div>
																	<!-- END MODAL ANGPAU -->

																	<!-- MODAL ANGPAU -->
																	<div class="modal fade" id="modalgift" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
																		<div class="modal-dialog modal-lg" role="document">
																			<div class="modal-content">
																				<div class="modal-body kt-portlet kt-portlet--tabs" style="margin-bottom: 0px;">
																					<div class="kt-portlet__head" style="border: none;">
																						<div class="" style="width:100%;">
																							<h1 class="kt-portlet__head-title text-center">- Kirim Kado -</h1>
																						</div>
																						<div class="kt-portlet__head-toolbar">
																							<a href="#" data-dismiss="modal" class="text-dark"><i class="fa fa-times"></i></a>
																						</div>
																					</div>
																					<div class="kt-portlet__body">
																						<div class="descmodal text-center">Bagi yang ingin memberikan kado sebagai hadiah, bisa dikirim ke alamat berikut :</div>

																						<div class="bgangpaurek text-center">
																							<div class="tbig">
																								<span>Penerima :</span><br>
																								<?PHP echo $giftpen; ?>
																							</div>
																							<div class="tmid"><?PHP echo $giftal; ?></div>
																							<div class="tsmall">Phone : <?PHP echo $giftphone; ?></div>
																						</div>

																						<div class="descmodal text-center">Untuk konfirmasi pemberian Kado, boleh konfirmasi ke nomor Whatsapp di bawah ini.<br>Terimakasih 😊🙏</div>
																					</div>
																				</div>
																				<div class="modal-footer" style="display: block; text-align: center;">
																					<a href="https://api.whatsapp.com/send?phone=<?PHP echo $rsvpnum; ?>" target="_blank" class="btn btn-dark text-white"><i class="fa fa-paper-plane"></i> Kirim Whatsapp</a>
																				</div>
																			</div>
																		</div>
																	</div>
																	<!-- END MODAL ANGPAU -->

																	<?PHP if ($modlive==1) { ?>
																	<div class="elementor-element elementor-element-495f4f10 animated-slow wdp-sticky-section-no elementor-widget elementor-widget-heading animated fadeInDown" data-id="495f4f10" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}" data-widget_type="heading.default">
																		<div class="elementor-widget-container">
																			<p class="elementor-heading-title elementor-size-default">
																				Live streaming dapat disaksikan  pada <?PHP echo $timelive; ?>
																			</p>
																		</div>
																	</div>
																	<?PHP } ?>
																</div>
															</div>
														</div>
													</div>
												</div>
											</section>

											<div class="elementor-element elementor-element-27aa024b elementor-widget-divider--separator-type-pattern elementor-widget elementor-widget-divider" data-id="27aa024b" data-element_type="widget" data-widget_type="divider.default">
												<div class="elementor-widget-container">
													<div class="elementor-divider" style="--divider-pattern-url: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' preserveAspectRatio='none' overflow='visible' height='100%' viewBox='0 0 24 24' stroke='%23d8ad83' stroke-width='3' fill='none' stroke-linecap='square' stroke-miterlimit='10'%3E%3Cpolyline points='0,18 12,6 24,18 '/%3E%3C/svg%3E&quot;); padding-top: 20px; padding-bottom: 0px;">
														<span class="elementor-divider-separator"></span>
													</div>
												</div>
											</div>

											<div class="elementor-element elementor-element-55c5b54a elementor-widget elementor-widget-heading" data-id="55c5b54a" data-element_type="widget" data-widget_type="heading.default">
												<div class="elementor-widget-container">
													<p class="elementor-heading-title elementor-size-default">
														<span style="color: #ffffff;">
															<a style="color: #ffffff; font-family: Dosis, Arial; position: relative; z-index: 99;" href="https://numura.id/" target="_blank" rel="noopener">&copy; Numura.id - <?PHP echo date('Y'); ?></a>
														</span>
													</p>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-4a2a4fab" data-id="4a2a4fab" data-element_type="column">
									<div class="elementor-column-wrap">
										<div class="elementor-widget-wrap"></div>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
		
		<div id="back-to-top">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
				<path d="M177 159.7l136 136c9.4 9.4 9.4 24.6 0 33.9l-22.6 22.6c-9.4 9.4-24.6 9.4-33.9 0L160 255.9l-96.4 96.4c-9.4 9.4-24.6 9.4-33.9 0L7 329.7c-9.4-9.4-9.4-24.6 0-33.9l136-136c9.4-9.5 24.6-9.5 34-.1z"/>
			</svg>
		</div>

		<script src="<?PHP echo base_url(); ?>assets/theme/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-includes/js/jquery/jquery4a5f.js?ver=1.12.4-wp' id='jquery-core-js'></script>

		<link rel='stylesheet' id='elementor-gallery-css'  href='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/e-gallery/css/e-gallery.min7359.css?ver=1.2.0' type='text/css' media='all' />
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/js/wdp-swiper.min.js' id='wdp-swiper-js-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/js/wdp-horizontal.js' id='wdp-horizontal-js-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/e-gallery/js/e-gallery.min7359.js?ver=1.2.0' id='elementor-gallery-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/js/wdp-wp.min16b9.js?ver=2.5.2' id='weddingpress-js'></script>
		<script type='text/javascript' id='bdt-uikit-js-extra'>
			/* <![CDATA[ */
			// var element_pack_ajax_login_config = {"ajaxurl":"https:\/\/numura.id\/wp-admin\/admin-ajax.php","loadingmessage":"Sending user info, please wait...","unknownerror":"Unknown error, make sure access is correct!"};
			// var ElementPackConfig = {"ajaxurl":"https:\/\/numura.id\/wp-admin\/admin-ajax.php","nonce":"6a01ff47c2","data_table":{"language":{"lengthMenu":"Show _MENU_ Entries","info":"Showing _START_ to _END_ of _TOTAL_ entries","search":"Search :","paginate":{"previous":"Previous","next":"Next"}}},"contact_form":{"sending_msg":"Sending message please wait...","captcha_nd":"Invisible captcha not defined!","captcha_nr":"Could not get invisible captcha response!"},"mailchimp":{"subscribing":"Subscribing you please wait..."},"elements_data":{"sections":[],"columns":[],"widgets":[]}};
			/* ]]> */
		</script>

		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/bdthemes-element-pack/assets/js/bdt-uikit.mina25a.js?ver=3.5.5' id='bdt-uikit-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/js/frontend-modules.min677a.js?ver=3.0.9' id='elementor-frontend-modules-js'></script>
		<!-- <script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-includes/js/jquery/ui/position.mine899.js?ver=1.11.4' id='jquery-ui-position-js'></script> -->

		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/dialog/dialog.mina288.js?ver=4.8.1' id='elementor-dialog-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/waypoints/waypoints.min05da.js?ver=4.0.2' id='elementor-waypoints-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/swiper/swiper.min48f5.js?ver=5.3.6' id='swiper-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/lib/share-link/share-link.min677a.js?ver=3.0.9' id='share-link-js'></script>
		<script type='text/javascript' id='elementor-frontend-js-before'>
			var elementorFrontendConfig = {"environmentMode":{"edit":false,"wpPreview":false},"i18n":{"shareOnFacebook":"Share on Facebook","shareOnTwitter":"Share on Twitter","pinIt":"Pin it","download":"Download","downloadImage":"Download image","fullscreen":"Fullscreen","zoom":"Zoom","share":"Share","playVideo":"Play Video","previous":"Previous","next":"Next","close":"Close"},"is_rtl":false,"breakpoints":{"xs":0,"sm":480,"md":768,"lg":1025,"xl":1440,"xxl":1600},"version":"3.0.9","is_static":false,"legacyMode":{"elementWrappers":true},"urls":{"assets":"https:\/\/numura.id\/wp-content\/plugins\/elementor\/assets\/"},"settings":{"page":[],"editorPreferences":[]},"kit":{"global_image_lightbox":"yes","lightbox_enable_counter":"yes","lightbox_enable_fullscreen":"yes","lightbox_enable_zoom":"yes","lightbox_enable_share":"yes","lightbox_title_src":"title","lightbox_description_src":"description"},"post":{"id":24192,"title":"Invitation%20<?PHP echo $queen; ?>%20%26%20<?PHP echo $king; ?>%20%E2%80%93","excerpt":"","featuredImage":false}};
		</script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor/assets/js/frontend.min677a.js?ver=3.0.9' id='elementor-frontend-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/bdthemes-element-pack/assets/js/element-pack-site.min76f3.js?ver=5.7.3' id='element-pack-site-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor-pro/assets/lib/sticky/jquery.sticky.min677a.js?ver=3.0.9' id='elementor-sticky-js'></script>
		<script type='text/javascript' id='elementor-pro-frontend-js-before'>
		var ElementorProFrontendConfig = {"nonce":"4d8100c514","i18n":{"toc_no_headings_found":"No headings were found on this page."},"shareButtonsNetworks":{"facebook":{"title":"Facebook","has_counter":true},"twitter":{"title":"Twitter"},"google":{"title":"Google+","has_counter":true},"linkedin":{"title":"LinkedIn","has_counter":true},"pinterest":{"title":"Pinterest","has_counter":true},"reddit":{"title":"Reddit","has_counter":true},"vk":{"title":"VK","has_counter":true},"odnoklassniki":{"title":"OK","has_counter":true},"tumblr":{"title":"Tumblr"},"digg":{"title":"Digg"},"skype":{"title":"Skype"},"stumbleupon":{"title":"StumbleUpon","has_counter":true},"mix":{"title":"Mix"},"telegram":{"title":"Telegram"},"pocket":{"title":"Pocket","has_counter":true},"xing":{"title":"XING","has_counter":true},"whatsapp":{"title":"WhatsApp"},"email":{"title":"Email"},"print":{"title":"Print"}},"facebook_sdk":{"lang":"id_ID","app_id":""},"lottie":{"defaultAnimationUrl":"https:\/\/numura.id\/wp-content\/plugins\/elementor-pro\/modules\/lottie\/assets\/animations\/default.json"}};
		</script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/elementor-pro/assets/js/frontend.min677a.js?ver=3.0.9' id='elementor-pro-frontend-js'></script>
		<script type='text/javascript' id='weddingpress-wdp-js-extra'>
		/* <![CDATA[ */
		var cevar = {"plugin_url":"https:\/\/numura.id\/wp-content\/plugins\/weddingpress\/"};
		/* ]]> */
		</script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/js/wdp.min16b9.js?ver=2.5.2' id='weddingpress-wdp-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/plugins/weddingpress/assets/js/dce-editor-copy16b9.js?ver=2.5.2' id='dce-clipboard-js-js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/themepw1/wp-content/themes/landingpress-wp/assets/js/script.min0226.js?ver=3.1.2' id='landingpress-js'></script>

		<script type="text/javascript">
			// Set the date we're counting down to
			var countDownDate = new Date("<?PHP echo $theday; ?>").getTime();

			// Update the count down every 1 second
			var x = setInterval(function() {

			  // Get today's date and time
			  var now = new Date().getTime();

			  // Find the distance between now and the count down date
			  var distance = countDownDate - now;

			  // Time calculations for days, hours, minutes and seconds
			  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
			  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

			  // Display the result in the element with id="demo"
			  // document.getElementById("demo").innerHTML = days + "d " + hours + "h "
			  // + minutes + "m " + seconds + "s ";

			  document.getElementById("harih").innerHTML = days;
			  document.getElementById("jamh").innerHTML = hours;
			  document.getElementById("menith").innerHTML = minutes;
			  document.getElementById("detikh").innerHTML = seconds;

			  // If the count down is finished, write some text
			  if (distance < 0) {
			    clearInterval(x);
			    document.getElementById("demo").innerHTML = "EXPIRED";
			  }
			}, 1000);

			$('#rsvpviawa').click(function(e) {
	            e.preventDefault();
	            // alert('cek');

	            var btn = $(this);
	            var form = $(this).closest('form');           

	            form.validate({
	                rules: {
	                    rname: 		{ required: true},
	                    raddr: 		{ required: true},
	                }
	            });

	            if (!form.valid()) {
	                return;
	            }

	            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

	            form.ajaxSubmit({
	                url: "<?PHP echo base_url(); ?>wedinv/confirm",
	                type: "POST",
	                dataType: "json",
	                beforeSend: function(){ 
	                   KTApp.block('#post-guestbook-box', {
	                        overlayColor: '#000000',
	                        type: 'v2',
	                        state: 'success',
	                        message: 'Please wait...'
	                    });
	                },
	                success: function(data) {
	                    if(data) {
	                        // similate 2s delay
	                        console.log(data);
	                        setTimeout(function() {
	                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
	                            //showErrorMsg(form, 'success', '<strong>Data Insert Success!</strong>');

	                            KTApp.unblock('#post-guestbook-box');
	                            var url 	= 'https://api.whatsapp.com/send?phone=<?PHP echo $rsvpnum; ?>&text=Halo%20<?PHP echo $queen; ?>%20%26%20<?PHP echo $king; ?>%2C%20saya%20'+data.name+'%20dari%20'+data.address+'%20ingin%20konfirmasi%20kehadiran%20undangan%20pernikahan%20kalian%20pada%20<?PHP echo $thedate; ?>%20bahwa%20'+data.confirm+'%0A%20%0ATerimakasih.';
	            				window.open(url, '_blank').focus();
	                            
	                            var alert = $('#suksesinsert');
	                			alert.removeClass('kt-hidden').show();
	                        }, 2000);
	                    } else {
	                        // similate 2s delay
	                        setTimeout(function() {
	                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
	                            showErrorMsg(form, 'danger', '<strong>Data Insert Failed!</strong> Change a few things up and try submitting again.');
	                            
	                            KTApp.unblock('#post-guestbook-box');
	                            
	                            var alert = $('#gagalinsert');
	                			alert.removeClass('kt-hidden').show();
	                        }, 2000);
	                    }
	                }
	            });
	        });     

			$('#copynorek').click(function(e) {
	            e.preventDefault();
		        /* Get the text field */
				var copyText = document.getElementById("norek");

				/* Select the text field */
				copyText.select();
				copyText.setSelectionRange(0, 99999); /* For mobile devices */

				/* Copy the text inside the text field */
				navigator.clipboard.writeText(copyText.value);

				/* Alert the copied text */
				alert("Nomor Rekening: " + copyText.value + " berhasil di copy.");
			});

			$('#kirimucapan').click(function(e) {
	            e.preventDefault();
	            // alert('cek');

	            var btn = $(this);
	            var form = $(this).closest('form');           

	            form.validate({
	                rules: {
	                    gsname: { required: true},
	                    gsmsg: { required: true},
	                }
	            });

	            if (!form.valid()) {
	                return;
	            }

	            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

	            form.ajaxSubmit({
	                url: "<?PHP echo base_url(); ?>wedinv/insert",
	                type: "POST",
	                beforeSend: function(){ 
	                   KTApp.block('#post-guestbook-box', {
	                        overlayColor: '#000000',
	                        type: 'v2',
	                        state: 'success',
	                        message: 'Please wait...'
	                    });
	                },
	                success: function(data) {
	                    if(data) {
	                        // similate 2s delay
	                        setTimeout(function() {
	                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
	                            //showErrorMsg(form, 'success', '<strong>Data Insert Success!</strong>');

	                            KTApp.unblock('#post-guestbook-box');
	                            
	                            $("#bgguest").load("<?PHP echo base_url(); ?>wedinv/reload/<?PHP echo $baseid; ?>");
	                            $('#post-guestbook-box')[0].reset();
	                            var alert = $('#suksesinsert');
	                			alert.removeClass('kt-hidden').show();
	                        }, 2000);
	                    } else {
	                        // similate 2s delay
	                        setTimeout(function() {
	                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
	                            showErrorMsg(form, 'danger', '<strong>Data Insert Failed!</strong> Change a few things up and try submitting again.');
	                            
	                            KTApp.unblock('#post-guestbook-box');
	                            
	                            var alert = $('#gagalinsert');
	                			alert.removeClass('kt-hidden').show();
	                        }, 2000);
	                    }
	                }
	            });
	        });     
		</script>

		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/wd-assets/glanz_library.js'></script>
		<script type='text/javascript' src='<?PHP echo base_url(); ?>assets/wd-assets/glanz_script.js'></script>
	</body>
</html>
