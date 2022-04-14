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
