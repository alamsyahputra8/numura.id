<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "
            select
                a.*
            from
            ourteam a
            where (department='$menu' or department_en='$menu')
            order by sort desc
            ";
$gPage      = $this->query->getDatabyQ($qPage);
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

        <style>
            .team-members.team-members-shadow .team-member .team-desc {
                padding: 10px;
            }
        </style>
        <!-- Page title -->
        <section id="page-title" class="page-title-center text-light background-overlay-dark" style="background:#bababa url('<?PHP echo base_url(); ?>images/content/<?PHP echo $background; ?>') no-repeat center; background-size: 100% auto;">
            <div class="container">
                <div class="page-title">
                    <!--span class="post-meta-category"><a href="#"><?PHP echo $menu; ?></a></span-->
                    <h1><?PHP echo $menu; ?></h1>
                    <!-- <div class="small m-b-20"><?PHP echo $dataPage['last_update']; ?> | <a href="#">by <?PHP echo $dataPage['update_by']; ?></a></div> -->
                    <!-- <div class="small m-b-20"><?PHP echo $dataPage['last_update']; ?> | <a href="#">by <?PHP echo $dataPage['update_by']; ?></a></div> -->
                    <!--div class="align-center">
                        <a class="btn btn-xs btn-slide btn-facebook" href="#">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                        <a class="btn btn-xs btn-slide btn-twitter" href="#" data-width="100">
                            <i class="fab fa-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <a class="btn btn-xs btn-slide btn-instagram" href="#" data-width="118">
                            <i class="fab fa-instagram"></i>
                            <span>Instagram</span>
                        </a>
                        <a class="btn btn-xs btn-slide btn-googleplus" href="mailto:#" data-width="80">
                            <i class="far fa-envelope"></i>
                            <span>Mail</span>
                        </a>
                    </div-->

                </div>
            </div>
        </section>
        <!-- end: Page title -->

        <section id="page-content">
            <div class="container">
                <div class="row team-members team-members-shadow m-b-40">
                    <?PHP
                    foreach ($gPage as $data) {
                        if(get_cookie('lang_is') === 'en'){
                            $pos    = $data['position_en'];
                        } else {
                            $pos    = $data['position'];
                        }
                    ?>
                    <div class="col-lg-3">
                        <div class="team-member equalheight">
                            <div class="team-image">
                                <img src="<?PHP echo base_url(); ?>images/ourteam/<?PHP echo $data['picture']; ?>">
                            </div>
                            <div class="team-desc">
                                <h3><?PHP echo $data['name']; ?></h3>
                                <span><?PHP echo $pos; ?></span><br>
                                <?PHP if ($data['email']!='' and $data['email']!='-' and $data['email']!=NULL) { ?>
                                <div class="align-center">
                                    <a class="btn btn-xs btn-light" href="mailto:<?PHP echo $data['email']; ?>">
                                        <i class="icon-mail" style="margin-top: -6px;"></i>
                                        <span><?PHP echo $data['email']; ?></span>
                                    </a>
                                </div>
                                <?PHP } ?>
                            </div>
                        </div>
                    </div>
                    <?PHP } ?>
                </div>
            </div>
        </section> 

        <?PHP $this->load->view('theme/polo/footer'); ?>
        <script>
            $(document).ready(function () {
                //EQUAL HEIGHT
                equalheight = function(container){

                var currentTallest = 0,
                     currentRowStart = 0,
                     rowDivs = new Array(),
                     $el,
                     topPosition = 0;
                 $(container).each(function() {

                   $el = $(this);
                   $($el).height('auto')
                   topPostion = $el.position().top;

                   if (currentRowStart != topPostion) {
                     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                       rowDivs[currentDiv].height(currentTallest);
                     }
                     rowDivs.length = 0; // empty the array
                     currentRowStart = topPostion;
                     currentTallest = $el.height();
                     rowDivs.push($el);
                   } else {
                     rowDivs.push($el);
                     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
                  }
                   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                     rowDivs[currentDiv].height(currentTallest);
                   }
                 });
                }

                $(window).load(function() {
                  equalheight('.equalheight');
                });


                $(window).resize(function(){
                  equalheight('.equalheight');
                });
            });
        </script>