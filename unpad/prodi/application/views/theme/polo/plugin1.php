<?PHP 
$getSiteData    = $this->query->getData('configsite','*',"");
$site           = array_shift($getSiteData); 

$urlmenuact     = str_replace('page/','',$this->uri->uri_string());
$qSEO           = "select meta_desc,meta_key,menu,id_menu from menu_site where link='$urlmenuact'";
$getMetaSEO     = $this->query->getDatabyQ($qSEO);
$dataSEO        = array_shift($getMetaSEO);
if ($dataSEO['id_menu']==1) {
    $menutitle = '';
} else {
    if ($dataSEO['menu']!='') {
        $menutitle = $dataSEO['menu'].' | ';
    } else {
        $menutitle = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    
    <meta name="description"content="<?PHP echo $dataSEO['meta_desc']; ?>">
    <meta name="keywords"content="<?PHP echo $dataSEO['meta_key']; ?>">
    <meta name="author" content="Parwatha" />
    <meta property="og:title" content="<?PHP echo $site['name_site']; ?>">
    <meta property="og:description" content="<?PHP echo $dataSEO['meta_desc']; ?>">
    <meta property="og:url" content="<?PHP echo base_url(); ?>">
    <meta property="og:image" content="<?PHP echo base_url().''.$site['logo']; ?>">

    <!-- Document title -->
    <title><?PHP echo $menutitle; ?><?PHP echo $site['name_site']; ?></title>
    <!-- Stylesheets & Fonts --><link href="<?PHP echo base_url(); ?>assets/plugins.css" rel="stylesheet">
    <link href="<?PHP echo base_url(); ?>assets/polo/css/style.css" rel="stylesheet">
    <link href="<?PHP echo base_url(); ?>assets/polo/css/responsive.css" rel="stylesheet">
    <link href="<?PHP echo base_url(); ?>assets/polo/css/pageloader.css" rel="stylesheet">

    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="<?PHP echo base_url(); ?>../images/<?PHP echo $site['favicon']; ?>" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="<?PHP echo base_url(); ?>../images/<?PHP echo $site['favicon']; ?>">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="<?PHP echo base_url(); ?>../images/<?PHP echo $site['favicon']; ?>">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="<?PHP echo base_url(); ?>../images/<?PHP echo $site['favicon']; ?>">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="<?PHP echo base_url(); ?>../images/<?PHP echo $site['favicon']; ?>">

    <!-- LOAD JQUERY LIBRARY -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    
    <!-- LOADING FONTS AND ICONS -->
    <link href="https://fonts.googleapis.com/css?family=Rubik:500%2C400%2C700" rel="stylesheet" property="stylesheet" type="text/css" media="all">
    
    <!-- <link rel="stylesheet" type="text/css" href="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css">
    <link rel="stylesheet" type="text/css" href="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/fonts/font-awesome/css/font-awesome.css"> -->
    
    <!-- REVOLUTION STYLE SHEETS -->
    <!-- <link rel="stylesheet" type="text/css" href="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/css/settings.css"> -->
    
    
    
    <style type="text/css">.tiny_bullet_slider .tp-bullet:before{content:" ";  position:absolute;  width:100%;  height:25px;  top:-12px;  left:0px;  background:transparent}</style>
    <style type="text/css">.bullet-bar.tp-bullets{}.bullet-bar.tp-bullets:before{content:" ";position:absolute;width:100%;height:100%;background:transparent;padding:10px;margin-left:-10px;margin-top:-10px;box-sizing:content-box}.bullet-bar .tp-bullet{width:60px;height:3px;position:absolute;background:#aaa;  background:rgba(204,204,204,0.5);cursor:pointer;box-sizing:content-box}.bullet-bar .tp-bullet:hover,.bullet-bar .tp-bullet.selected{background:rgba(204,204,204,1)}.bullet-bar .tp-bullet-image{}.bullet-bar .tp-bullet-title{}</style>
    
    <!-- REVOLUTION JS FILES -->
    <!-- <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/jquery.themepunch.revolution.min.js"></script> -->
    
    <!-- SLICEY ADD-ON FILES -->
    <!-- <script type='text/javascript' src='<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/revolution-addons/slicey/js/revolution.addon.slicey.min.js?ver=1.0.0'></script> -->

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->    
    <!-- <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
    <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
    <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/extensions/revolution.extension.migration.min.js"></script>
    <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
    <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
    <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
    <script type="text/javascript" src="<?PHP echo base_url(); ?>assets/polo/js/plugins/revolution/js/extensions/revolution.extension.video.min.js"></script> -->

    <style>
    /*section { background: #429ed6 url('<?PHP echo base_url(); ?>../images/divider.png') repeat-x center bottom; }*/
    /*body {
        background: url('<?PHP echo base_url(); ?>../images/bgamr.jpeg') fixed no-repeat center;
        background-size: 70% auto;
    }*/
    section { 
        background: rgba(255,255,255,.9);
        border-bottom: 1px solid #e5e5e5;
    }
    #header .header-inner #logo a > img {
        /*height: 65px;*/
        margin-top: -2px;
    }
    .no-border {
        border: none!important;
        /*background: #f4f4f4!important;*/
    }
    .background-dark {
        background-color: #333!important;
    }
    .heading-text.heading-section > h2 { margin-bottom: 0px!important; }
    .heading-text.heading-section > h2:before {
        content: "";
        display: none!important;
        position: absolute;
        height: 2px;
        width: 100px;
        background-color: #FFF;
        bottom: -30px;
        left: 0;
        right: 0;
    }
    .background-overlay-dark:before {
        background: rgba(0, 0, 0, 0.6)!important;
    }
    @media (max-width: 991px) {
        #sarangvisuel #logo {
            text-align: left!important;
            background: #FFF!important;
        }
    }
    </style>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-196480878-1">
    </script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-196480878-1');
    </script>
</head>

<body id="sarangvisuel">
    <!-- Body Inner -->    
    <div class="body-inner">