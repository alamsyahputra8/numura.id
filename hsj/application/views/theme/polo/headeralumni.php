<?PHP 
$getSiteData    = $this->query->getData('configsite','*',"");
$site           = array_shift($getSiteData); 

$activepage     = $this->uri->uri_string();
//if ($activepage=='page/blog') {
if (strpos( $activepage, 'blog' ) !== false) {
    $transparent    = '';
} else {
    $transparent    = '';
    // $transparent    = 'data-transparent="true"';
}
?>
<style>
#footer .copyright-content { background-color: #f2a138; }
.padding0 {padding:0px!important;}
.p20{
    padding: 20px 0 20px 0!important;
}
#header {
    height: 50px!important;
    line-height: 50px!important;
}
#header .header-inner {
    height: 50px!important;
}
#header.header-colored .header-inner {
    background-color: #f2a138!important;
    border-bottom:4px solid #6a4734;
}
#header.header-sticky.dark .header-inner {
    background-color: #f2a138!important;
}
.dark:not(.submenu-light) #mainMenu nav > ul > li .dropdown-menu {
    background: #FFF;
    border-color: #FFF;
}
@media (max-width: 991px) {
    #header #mainMenu-trigger { height: 55px; }
    #logo { text-align: left!important; }
    .mainMenu-open #header #mainMenu { background: #FFF; }
    #header .header-inner #logo a > img, #header #header-wrap #logo a > img { max-width: 82%; height: auto; margin-top: 1rem;}
    #header[data-transparent="true"] .header-inner .lines, #header[data-transparent="true"] .header-inner .lines:before, #header[data-transparent="true"] .header-inner .lines:after, #header[data-transparent="true"] #header-wrap .lines, #header[data-transparent="true"] #header-wrap .lines:before, #header[data-transparent="true"] #header-wrap .lines:after, #header.dark[data-transparent="true"] .header-inner .lines, #header.dark[data-transparent="true"] .header-inner .lines:before, #header.dark[data-transparent="true"] .header-inner .lines:after, #header.dark[data-transparent="true"] #header-wrap .lines, #header.dark[data-transparent="true"] #header-wrap .lines:before, #header.dark[data-transparent="true"] #header-wrap .lines:after, #header.dark.header-colored .header-inner .lines, #header.dark.header-colored .header-inner .lines:before, #header.dark.header-colored .header-inner .lines:after, #header.dark.header-colored #header-wrap .lines, #header.dark.header-colored #header-wrap .lines:before, #header.dark.header-colored #header-wrap .lines:after {
        background: #FFF!important;
    }
    /*#header[data-transparent="true"] .header-inner,#header.dark[data-transparent="true"] .header-inner {
        background-color: #1e1e1e!important;
    }
    #header[data-transparent="true"] .header-inner .lines, #header.dark[data-transparent="true"] .header-inner .lines {
        background-color: #FFF!important;
    }*/
}
</style>
<!-- Header -->
<?PHP
$header   = '<header id="header" data-fullwidth="true" class="header-colored">';
$logo     = $site['logo'];
echo $header;
?>
    <div class="header-inner">
        <div class="container"> <!--Logo-->
            <!-- <div id="logo">
                <a href="<?PHP echo base_url(); ?>" class="logo" data-src-dark="<?PHP echo base_url(); ?>images/<?PHP echo $logo; ?>">
                    <img src="<?PHP echo base_url(); ?>images/<?PHP echo $logo; ?>" alt="<?PHP echo $site['name_site']; ?>">
                </a>
            </div> -->
            <!--End: Logo-->

            <!-- Search -->
            <div id="search">
                <div id="search-logo"><img src="<?PHP echo base_url(); ?>images/<?PHP echo $logo; ?>" alt="<?PHP echo $site['name_site']; ?>"></div>
                <button id="btn-search-close" class="btn-search-close" aria-label="Close search form"><i
                        class="icon-x"></i>
                </button>
                <form class="search-form" action="search-results-page.html" method="get">
                    <input class="form-control" name="q" type="search" placeholder="Search..."
                        autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" />
                    <span class="text-muted">Start typing & press "Enter" or "ESC" to close</span>
                </form>
            </div>
            <!-- end: search -->

            <!--Header Extras -->
            <div class="header-extras">
                <ul>
                    <li>
                        <div class="p-dropdown">
                            <?PHP if (get_cookie('lang_is')=='en') { ?>
                                <a href="#"><img src="<?PHP echo base_url(); ?>images/en.png" style="max-height: 20px; margin-top: -5px;"> <span>EN</span></a>
                            <?PHP } else { ?>
                                <a href="#"><img src="<?PHP echo base_url(); ?>images/id.png" style="max-height: 20px; margin-top: -5px;"> <span>ID</span></a>
                            <?PHP } ?>
                            <ul class="p-dropdown-content">
                                <li>
                                    <a href="<?php echo site_url('panel/set_to/indonesia');?>">
                                        <img src="<?PHP echo base_url(); ?>images/id.png" style="max-height: 20px; margin-top: -5px;"> Indonesia
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('panel/set_to/english');?>">
                                        <img src="<?PHP echo base_url(); ?>images/en.png" style="max-height: 20px; margin-top: -5px;"> English
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- end: Header Extras-->
            
            <!--Navigation Resposnive Trigger-->
            <div id="mainMenu-trigger">
                <button class="lines-button x"> <span class="lines"></span> </button>
            </div>
            <!--end: Navigation Resposnive Trigger-->

            <!--Navigation-->
            <div id="mainMenu" class="menu-left">
                <div class="container">
					<?PHP if(get_cookie('lang_is') === 'en'){ 
					?>
					 <nav>
                        <ul>
                            <?PHP
                            $qMenu      = "select * from menu_site where parent='0' and flag_website='$coreid' order by sort asc";
                            $getMenu    = $this->query->getDatabyQ($qMenu);
                            foreach ($getMenu as $datamenu) {
                                $idmenu     = $datamenu['id_menu'];
                                $cekChild   = $this->query->getNumRows('menu_site','*',"where parent='$idmenu'")->num_rows();
                                if ($cekChild>0) {
                                    $classChild = 'class="dropdown"';
                                } else {
                                    $classChild = '';
                                }

                                if ($datamenu['link']=='#' or $datamenu['link']=='' or $datamenu['style']=='link') { $page   = ''; } else { $page   = 'page/'; }
                            ?>
                            <li <?PHP echo $classChild; ?>>
                                <a href="<?PHP echo base_url(); ?><?PHP echo $page; ?><?PHP echo $datamenu['link']; ?>"><?PHP echo $datamenu['menu_en']; ?></a>
                                <?PHP if ($cekChild>0) { ?>
                                    <ul class="dropdown-menu">
                                        <?PHP
                                        $qChild     = "select * from menu_site where parent='$idmenu' order by sort asc";
                                        $getChild   = $this->query->getDatabyQ($qChild);
                                        foreach ($getChild as $datachild) {
                                            $idmenuC     = $datachild['id_menu'];
                                            $cekChild2   = $this->query->getNumRows('menu_site','*',"where parent='$idmenuC'")->num_rows();
                                            if ($cekChild2>0) {
                                                $classChild2 = 'class="dropdown-submenu"';
                                            } else {
                                                $classChild2 = '';
                                            }
                                            if ($datachild['link']=='#' or $datachild['link']=='' or $datachild['style']=='link') { $page   = ''; } else { $page   = 'page/'; }
                                        ?>
                                        <li <?PHP echo $classChild2; ?>><a href="<?PHP echo base_url(); ?><?PHP echo $page; ?><?PHP echo $datachild['link']; ?>"><?PHP echo $datachild['menu_en']; ?></a>
                                            <?PHP if ($cekChild2>0) { ?>
                                            <ul class="dropdown-menu">
                                                <?PHP
                                                $qChild2     = "select * from menu_site where parent='$idmenuC' order by sort asc";
                                                $getChild2   = $this->query->getDatabyQ($qChild2);
                                                foreach ($getChild2 as $datachild2) {
                                                    $idmenuC2     = $datachild2['id_menu'];
                                                    $cekChild3   = $this->query->getNumRows('menu_site','*',"where parent='$idmenuC2'")->num_rows();
                                                    if ($cekChild3>0) {
                                                        $classChild3 = 'class="dropdown-submenu"';
                                                    } else {
                                                        $classChild3 = '';
                                                    }
                                                    if ($datachild2['link']=='#' or $datachild2['link']=='' or $datachild2['style']=='link') { $page   = ''; } else { $page   = 'page/'; }
                                                ?>
                                                <li <?PHP echo $classChild3; ?>><a href="<?PHP echo base_url(); ?><?PHP echo $page; ?><?PHP echo $datachild2['link']; ?>"><?PHP echo $datachild2['menu_en']; ?></a>
                                                    <?PHP if ($cekChild3>0) { ?>
                                                    <ul class="dropdown-menu">
                                                        <?PHP
                                                        $qChild3     = "select * from menu_site where parent='$idmenuC2' order by sort asc";
                                                        $getChild3   = $this->query->getDatabyQ($qChild3);
                                                        foreach ($getChild3 as $datachild3) {
                                                            $idmenuC3     = $datachild3['id_menu'];
                                                            if ($datachild3['link']=='#' or $datachild3['link']=='' or $datachild3['style']=='link') { $page   = ''; } else { $page   = 'page/'; }
                                                        ?>
                                                        <li><a href="<?PHP echo base_url(); ?><?PHP echo $page; ?><?PHP echo $datachild3['link']; ?>"><?PHP echo $datachild3['menu_en']; ?></a></li>
                                                        <?PHP } ?>
                                                    </ul>
                                                    <?PHP } ?>
                                                </li>
                                                <?PHP } ?>
                                            </ul>
                                            <?PHP } ?>
                                        </li>
                                        <?PHP } ?>
                                    </ul>
                                <?PHP } ?>
                            </li>
                            <?PHP } ?>
                        </ul>
                    </nav>
					<?PHP
					}else{
					?>
					<nav>
                        <ul>
                            <?PHP
                            $qMenu      = "select * from menu_site where parent='0' and flag_website='$coreid' order by sort asc";
                            $getMenu    = $this->query->getDatabyQ($qMenu);
                            foreach ($getMenu as $datamenu) {
                                $idmenu     = $datamenu['id_menu'];
                                $cekChild   = $this->query->getNumRows('menu_site','*',"where parent='$idmenu'")->num_rows();
                                if ($cekChild>0) {
                                    $classChild = 'class="dropdown"';
                                } else {
                                    $classChild = '';
                                }

                                if ($datamenu['link']=='#' or $datamenu['link']=='' or $datamenu['style']=='link') { $page   = ''; } else { $page   = 'page/'; }
                            ?>
                            <li <?PHP echo $classChild; ?>>
                                <a href="<?PHP echo base_url(); ?><?PHP echo $page; ?><?PHP echo $datamenu['link']; ?>"><?PHP echo $datamenu['menu']; ?></a>
                                <?PHP if ($cekChild>0) { ?>
                                    <ul class="dropdown-menu">
                                        <?PHP
                                        $qChild     = "select * from menu_site where parent='$idmenu' order by sort asc";
                                        $getChild   = $this->query->getDatabyQ($qChild);
                                        foreach ($getChild as $datachild) {
                                            $idmenuC     = $datachild['id_menu'];
                                            $cekChild2   = $this->query->getNumRows('menu_site','*',"where parent='$idmenuC'")->num_rows();
                                            if ($cekChild2>0) {
                                                $classChild2 = 'class="dropdown-submenu"';
                                            } else {
                                                $classChild2 = '';
                                            }
                                            if ($datachild['link']=='#' or $datachild['link']=='' or $datachild['style']=='link') { $page   = ''; } else { $page   = 'page/'; }
                                        ?>
                                        <li <?PHP echo $classChild2; ?>><a href="<?PHP echo base_url(); ?><?PHP echo $page; ?><?PHP echo $datachild['link']; ?>"><?PHP echo $datachild['menu']; ?></a>
                                            <?PHP if ($cekChild2>0) { ?>
                                            <ul class="dropdown-menu">
                                                <?PHP
                                                $qChild2     = "select * from menu_site where parent='$idmenuC' order by sort asc";
                                                $getChild2   = $this->query->getDatabyQ($qChild2);
                                                foreach ($getChild2 as $datachild2) {
                                                    $idmenuC2     = $datachild2['id_menu'];
                                                    $cekChild3   = $this->query->getNumRows('menu_site','*',"where parent='$idmenuC2'")->num_rows();
                                                    if ($cekChild3>0) {
                                                        $classChild3 = 'class="dropdown-submenu"';
                                                    } else {
                                                        $classChild3 = '';
                                                    }
                                                    if ($datachild2['link']=='#' or $datachild2['link']=='' or $datachild2['style']=='link') { $page   = ''; } else { $page   = 'page/'; }
                                                ?>
                                                <li <?PHP echo $classChild3; ?>><a href="<?PHP echo base_url(); ?><?PHP echo $page; ?><?PHP echo $datachild2['link']; ?>"><?PHP echo $datachild2['menu']; ?></a>
                                                    <?PHP if ($cekChild3>0) { ?>
                                                    <ul class="dropdown-menu">
                                                        <?PHP
                                                        $qChild3     = "select * from menu_site where parent='$idmenuC2' order by sort asc";
                                                        $getChild3   = $this->query->getDatabyQ($qChild3);
                                                        foreach ($getChild3 as $datachild3) {
                                                            $idmenuC3     = $datachild3['id_menu'];
                                                            if ($datachild3['link']=='#' or $datachild3['link']=='' or $datachild3['style']=='link') { $page   = ''; } else { $page   = 'page/'; }
                                                        ?>
                                                        <li><a href="<?PHP echo base_url(); ?><?PHP echo $page; ?><?PHP echo $datachild3['link']; ?>"><?PHP echo $datachild3['menu']; ?></a></li>
                                                        <?PHP } ?>
                                                    </ul>
                                                    <?PHP } ?>
                                                </li>
                                                <?PHP } ?>
                                            </ul>
                                            <?PHP } ?>
                                        </li>
                                        <?PHP } ?>
                                    </ul>
                                <?PHP } ?>
                            </li>
                            <?PHP } ?>
                        </ul>
                    </nav>
					<?PHP
					}
					?>
                   
                </div>
            </div>
            <!--END: NAVIGATION-->
        </div>
    </div>
</header>
<!-- end: Header -->