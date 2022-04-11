<?PHP 
$getSiteData    = $this->query->getData('configsite','*',"");
$site           = array_shift($getSiteData); 

$qAbout         = "select a.id_menu,a.style,b.* from menu_site a 
                left join content b on a.id_menu=b.id_menu
                where a.style='about'";
$gAbout         = $this->query->getDatabyQ($qAbout);
$dataAbout      = array_shift($gAbout);
?>
        <style>
            .listserworks ul {
                padding-left: 2rem;
            }
            .listserworks ul li {
                list-style-type: circle;
            }
        </style>

        <!-- Footer -->
        <footer id="footer" class="">
            <div class="footer-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="widget">
                                
                                <div class="widget-title">
                                    <img src="<?PHP echo base_url(); ?>images/<?PHP echo $site['logo']; ?>" alt="SarangVisuell" style="margin-left: -1rem;">
                                </div>
                                <p class="mb-5">
                                    <?PHP echo $dataAbout['headline']; ?>
                                </p>
                                <div>
                                    <?PHP if ($site['facebook']!=='') { ?>
                                    <a class="btn btn-xs btn-slide btn-facebook" href="<?PHP echo $site['facebook']; ?>">
                                        <i class="fab fa-facebook-f"></i>
                                        <span>Facebook</span>
                                    </a>
                                    <?PHP } ?>

                                    <?PHP if ($site['twitter']!=='') { ?>
                                    <a class="btn btn-xs btn-slide btn-twitter" href="<?PHP echo $site['twitter']; ?>">
                                        <i class="fab fa-twitter"></i>
                                        <span>Twitter</span>
                                    </a>
                                    <?PHP } ?>
                                    
                                    <?PHP if ($site['instagram']!=='') { ?>
                                    <a class="btn btn-xs btn-slide btn-instagram" href="<?PHP echo $site['instagram']; ?>">
                                        <i class="fab fa-instagram"></i>
                                        <span>Instagram</span>
                                    </a>
                                    <?PHP } ?>
                                    
                                    <?PHP if ($site['youtube']!=='') { ?>
                                    <a class="btn btn-xs btn-slide btn-googleplus" href="<?PHP echo $site['youtube']; ?>">
                                        <i class="fab fa-youtube"></i>
                                        <span>Youtube</span>
                                    </a>
                                    <?PHP } ?>

                                    <?PHP if ($site['whatsapp_no']!=='') { ?>
                                    <a class="btn btn-xs btn-slide btn-success" href="https://api.whatsapp.com/send?phone=<?PHP echo $site['whatsapp_no']; ?>&text=<?PHP echo $site['whatsapp_text']; ?>">
                                        <i class="fab fa-whatsapp"></i>
                                        <span>Whatsapp</span>
                                    </a>
                                    <?PHP } ?>
                                </div>
                                <!--a href="" class="btn btn-inverted">Purchase Now</a-->
                            </div>
                        </div> 
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="widget">
                                        <div class="widget-title">Our Company</div>
                                        <ul class="list" style="padding-left: 0px;">
                                            <?PHP
                                            $getOC  = $this->query->getDatabyQ("select * from menu_site where style in ('about','blog','contact') order by sort");
                                            foreach ($getOC as $dataoc) {
                                            ?>
                                            <li><a href="<?PHP echo base_url().'page/'.$dataoc['link']; ?>"><?PHP echo $dataoc['menu']; ?></a></li>
                                            <?PHP } ?>
                                        </ul>
                                    </div>  
                                </div>

                                <div class="col-lg-6">
                                    <div class="widget">
                                        <div class="widget-title">Services</div>
                                        <ul class="list listserworks" style="padding-left: 0px;">
                                            <?PHP
                                            $qServices      = "
                                                            select * from (
                                                                select 'lev1', a.* from menu_site a where parent=0 and style='services'
                                                                UNION
                                                                select 'lev2', b.* from menu_site b where style='services' and parent=(select id_menu from menu_site where parent=0 and style='services')
                                                            ) as final
                                                            where parent!=0
                                                            order by parent, sort
                                                            ";
                                            $getServices    = $this->query->getDatabyQ($qServices);

                                            foreach ($getServices as $dataserv) {
                                            ?>
                                            <li><a href="<?PHP echo base_url().'page/'.$dataserv['link']; ?>"><?PHP echo $dataserv['menu']; ?></a></li>
                                                <?PHP
                                                $qSubServ   = "
                                                            select * from menu_site where parent=".$dataserv['id_menu']." and style='services'
                                                            order by parent, sort
                                                            ";
                                                $cekSubs    = $this->query->getNumRowsbyQ($qSubServ)->num_rows();
                                                
                                                if ($cekSubs>0) {
                                                    echo '<ul>';
                                                    $gSubServ   = $this->query->getDatabyQ($qSubServ);
                                                    foreach ($gSubServ as $datasubs) {
                                                ?>
                                                        <li>
                                                            <a href="<?PHP echo base_url().'page/'.$datasubs['link']; ?>"><?PHP echo $datasubs['menu']; ?></a>
                                                        </li>
                                                <?PHP
                                                    }
                                                    echo '</ul>';
                                                }
                                                ?>
                                            <?PHP } ?>
                                        </ul>
                                    </div>  
                                </div>

                                <!--div class="col-lg-4">
                                    <div class="widget">
                                        <div class="widget-title">Works</div>
                                        <ul class="list listserworks">
                                            <?PHP
                                            $qWorks      = "
                                                            select * from (
                                                                select 'lev1', a.* from menu_site a where parent=0 and style='works'
                                                                UNION
                                                                select 'lev2', b.* from menu_site b where style='works' and parent=(select id_menu from menu_site where parent=0 and style='works')
                                                            ) as final
                                                            where parent!=0
                                                            order by parent, sort
                                                            ";
                                            $getWorks    = $this->query->getDatabyQ($qWorks);

                                            foreach ($getWorks as $dataworks) {
                                            ?>
                                            <li><a href="<?PHP echo base_url().'page/'.$dataworks['link']; ?>"><?PHP echo $dataworks['menu']; ?></a></li>
                                                <?PHP
                                                $qSubWorks   = "
                                                            select * from menu_site where parent=".$dataworks['id_menu']." and style='works'
                                                            order by parent, sort
                                                            ";
                                                $cekSubs    = $this->query->getNumRowsbyQ($qSubWorks)->num_rows();
                                                
                                                if ($cekSubs>0) {
                                                    echo '<ul>';
                                                    $gSubWorks   = $this->query->getDatabyQ($qSubWorks);
                                                    foreach ($gSubWorks as $datasubs) {
                                                ?>
                                                        <li>
                                                            <a href="<?PHP echo base_url().'page/'.$datasubs['link']; ?>"><?PHP echo $datasubs['menu']; ?></a>
                                                        </li>
                                                <?PHP
                                                    }
                                                    echo '</ul>';
                                                }
                                                ?>
                                            <?PHP } ?>
                                        </ul>
                                    </div>  
                                </div-->
                            </div>
                        </div>
                

                </div>
                </div>
            </div>
            <div class="copyright-content">
                <div class="container">
                    <div class="copyright-text text-center ">
                        &copy; <?PHP echo date('Y'); ?> <?PHP echo $site['name_site']; ?>.<br>
                        <!-- Develop by <a href="http://www.parwatha.com" target="_blank"> PARWATHA</a> and Designed by <a href="http://www.inspiro-media.com" target="_blank"> INSPIRO</a>. All Rights Reserved. -->
                    </div>
                </div>
            </div>
        </footer>
        <!-- end: Footer -->

    </div>
    <!-- end: Body Inner -->

    <div id="bgcallwa">
        <div class="row">
            <div class="col-sm-6 text-center">
                <?PHP if ($site['whatsapp_no']!=='') { ?>
                <div class="bgwamobile row" style="padding-left:20px;">
                    <a class="text-success" href="https://api.whatsapp.com/send?phone=<?PHP echo $site['whatsapp_no']; ?>&text=<?PHP echo $site['whatsapp_text']; ?>">
                        <div class="col-sm-3"><i class="fab fa-whatsapp"></i></div>
                        <div class="col-sm-9 text-dark">Whatsapp</div>
                    </a>
                </div>
                <?PHP } ?>
            </div>
            <div class="col-sm-6 text-center">
                <?PHP if ($site['phone']!=='') { ?>
                <div class="bgphonemobile row">
                    <a class="text-primary" href="tel:<?PHP echo $site['phone']; ?>">
                        <div class="col-sm-3"><i class="fas fa-phone-square"></i></div>
                        <div class="col-sm-9 text-dark" style="padding-left: 30px;">Phone</div>
                    </a>
                </div>
                <?PHP } ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <!-- Scroll top -->
    <a id="scrollTop"><i class="icon-chevron-up1"></i><i class="icon-chevron-up1"></i></a>

    <!--Plugins-->
    <!--script src="<?PHP echo base_url(); ?>assets/polo/js/jquery.js"></script-->
    <script src="<?PHP echo base_url(); ?>assets/polo/js/plugins.js"></script>

    <!--Template functions-->
    <script src="<?PHP echo base_url(); ?>assets/polo/js/functions.js"></script>

    <script src="<?PHP echo base_url(); ?>assets/polo/js/pageloader.js"></script>

    <style>
        #bgcallwa { 
            display: none; 
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #FFF;
            padding: 0.5rem;
        }

        @media (max-width:991px) {
            #bgcallwa { 
                display: block; 
                z-index: 2;
            }
            #bgcallwa i { font-size: 3rem;}
            #bgcallwa .text-dark {
                font-size: 1.5rem;
                margin-top: 5%;
                text-align: left;
                padding-left: 20px;
            }
            .body-inner {margin-bottom: 40px!important;}
            #bgcallwa .col-sm-3 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 25%;
                flex: 0 0 25%;
                max-width: 25%;
                float: left;
                padding-left: 25px;
            }
            #bgcallwa .col-sm-9 {
                -webkit-box-flex: 0;
                -ms-flex: 0 0 75%;
                flex: 0 0 75%;
                float: left;
                max-width: 75%;
            }
            #bgcallwa .col-sm-6 {
                width: 50%!important;
                vertical-align: middle;
            }
        }
        .loader .mt-2.mb-0 {
            position: absolute;
            bottom: 0;
            color: #888;
        }
        .loaders {
            width: 100%;
            box-sizing: border-box;
            display: flex;
            flex: 0 1 auto;
            flex-direction: row;
            flex-wrap: wrap;
        }
        
        .loaders .loader {
            box-sizing: border-box;
            display: flex;
            flex: 0 1 auto;
            flex-direction: column;
            flex-grow: 1;
            flex-shrink: 0;
            flex-basis: 20%;
            max-width: 20%;
            height: 180px;
            align-items: center;
            justify-content: center;
        }
        
        .ball-grid-pulse > div {
            background: #D86199 !important;
        }
        
        .square-spin > div {
            background: #803314 !important;
        }
        
        .ball-rotate > div,
        .ball-rotate > div:after,
        .ball-rotate > div:before {
            background: #FB7302 !important;
        }
        
        .cube-transition > div {
            background: #475175 !important;
        }
        
        .ball-zig-zag > div {
            background: #E62220 !important;
        }
        
        .ball-triangle-path > div {
            background: #F68142 !important;
        }
        
        .line-scale > div {
            background: #00AC93 !important;
        }
        
        .ball-scale-multiple > div {
            background: #71B3D0 !important;
        }
        
        .ball-pulse-sync > div {
            background: #10345B !important;
        }
        
        .ball-beat > div {
            background: #CC3433 !important;
        }
        
        .line-scale-pulse-out-rapid > div {
            background: #999999 !important;
        }
        
        .ball-scale-ripple > div {
            background: #00349A !important;
        }
        
        .ball-scale-ripple-multiple > div {
            border: 2px solid #9ACCCD;
        }
        
        .ball-spin-fade-loader > div {
            background: #00349A !important;
        }
        
        .line-spin-fade-loader > div {
            background: #00639A !important;
        }
        
        .pacman > div:nth-child(3),
        .pacman > div:nth-child(4),
        .pacman > div:nth-child(5),
        .pacman > div:nth-child(6) {
            background-color: #2192B2;
        }
        
        .pacman > div:first-of-type,
        .pacman > div:nth-child(2) {
            border-color: #2192B2 transparent #2192B2 #2192B2;
        }
        
        .ball-grid-beat > div {
            background: #636FAB !important;
        }
    </style>
</body>

</html>
