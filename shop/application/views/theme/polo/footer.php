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
        <footer id="footer" class="inverted">
            <div class="footer-content">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="widget">
                                
                                <div class="widget-title">
                                    <img src="<?PHP echo base_url(); ?>images/<?PHP echo $site['logo']; ?>" alt="SarangVisuell" style="margin-left: -2rem;">
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
                                </div>
                                <!--a href="" class="btn btn-inverted">Purchase Now</a-->
                            </div>
                        </div> 
                        <div class="col-lg-7">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="widget">
                                        <div class="widget-title">Our Company</div>
                                        <ul class="list">
                                            <?PHP
                                            $getOC  = $this->query->getDatabyQ("select * from menu_site where style in ('about','blog','contact') order by sort");
                                            foreach ($getOC as $dataoc) {
                                            ?>
                                            <li><a href="<?PHP echo base_url().'page/'.$dataoc['link']; ?>"><?PHP echo $dataoc['menu']; ?></a></li>
                                            <?PHP } ?>
                                        </ul>
                                    </div>  
                                </div>

                                <div class="col-lg-4">
                                    <div class="widget">
                                        <div class="widget-title">Services</div>
                                        <ul class="list listserworks">
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

                                <div class="col-lg-4">
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
                                </div>
                            </div>
                        </div>
                

                </div>
                </div>
            </div>
            <div class="copyright-content">
                <div class="container">
                    <div class="copyright-text text-center text-white">
                        &copy; <?PHP echo date('Y'); ?> <?PHP echo $site['name_site']; ?>.<br>
                        Develop by <a href="http://www.parwatha.com" target="_blank"> PARWATHA</a> and Designed by <a href="http://www.inspiro-media.com" target="_blank"> INSPIRO</a>. All Rights Reserved.
                    </div>
                </div>
            </div>
        </footer>
        <!-- end: Footer -->

    </div>
    <!-- end: Body Inner -->

    <!-- Scroll top -->
    <a id="scrollTop"><i class="icon-chevron-up1"></i><i class="icon-chevron-up1"></i></a>

    <!--Plugins-->
    <!--script src="https://parwatha.com/polo/js/jquery.js"></script-->
    <script src="https://parwatha.com/polo/js/plugins.js"></script>

    <!--Template functions-->
    <script src="https://parwatha.com/polo/js/functions.js"></script>
</body>

</html>
