<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "
            select
                a.*,
                (select menu from menu_site where id_menu=a.id_menu) as menu,
                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Content' AND xa.data = a.id_content ORDER BY xa.date_time DESC limit 1)as update_by,
                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Content' AND xa.data = a.id_content ORDER BY xa.date_time DESC limit 1)as last_update
            from
            content a
            where id_menu='$idmenu'
            ";
$gPage      = $this->query->getDatabyQ($qPage);
$dataPage   = array_shift($gPage);
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

        <!-- Page title -->
        <!-- <section id="page-title" class="page-title-center text-light background-overlay-dark" style="background:url('<?PHP echo base_url(); ?>../images/content/<?PHP echo $background; ?>') no-repeat center; background-size: cover;"> -->
        <section id="page-title" data-bg-parallax="<?PHP echo base_url(); ?>../images/content/<?PHP echo $background; ?>">
            <div class="container">
                <div class="page-title">
                    <!--span class="post-meta-category"><a href="#"><?PHP echo $menu; ?></a></span-->
                    <h1>About Us</h1>
                    <div class="small m-b-20">&nbsp;</div>
                    <div class="align-center">
                        <?PHP if ($sitedata['facebook']!=='') { ?>
                        <a class="btn btn-xs btn-slide btn-facebook" href="<?PHP echo $sitedata['facebook']; ?>">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                        <?PHP } ?>

                        <?PHP if ($sitedata['twitter']!=='') { ?>
                        <a class="btn btn-xs btn-slide btn-twitter" href="<?PHP echo $sitedata['twitter']; ?>">
                            <i class="fab fa-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <?PHP } ?>
                        
                        <?PHP if ($sitedata['instagram']!=='') { ?>
                        <a class="btn btn-xs btn-slide btn-instagram" href="<?PHP echo $sitedata['instagram']; ?>">
                            <i class="fab fa-instagram"></i>
                            <span>Instagram</span>
                        </a>
                        <?PHP } ?>
                        
                        <?PHP if ($sitedata['youtube']!=='') { ?>
                        <a class="btn btn-xs btn-slide btn-googleplus" href="<?PHP echo $sitedata['youtube']; ?>">
                            <i class="fab fa-youtube"></i>
                            <span>Youtube</span>
                        </a>
                        <?PHP } ?>

                        <?PHP if ($sitedata['whatsapp_no']!=='') { ?>
                        <a class="btn btn-xs btn-slide btn-success" href="https://api.whatsapp.com/send?phone=<?PHP echo $sitedata['whatsapp_no']; ?>&text=<?PHP echo $sitedata['whatsapp_text']; ?>">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                        <?PHP } ?>
                    </div>

                </div>
            </div>
        </section>
        <!-- end: Page title -->

        <style>
        /*.post-item-description p, .post-item-description h1, .post-item-description h2, .post-item-description h3, .post-item-description h4, .post-item-description h5, .post-item-description h6 { color: #FFF!important; }*/
        </style>

        <!-- Page Content -->
        <section id="page-content" class="">
            <div class="container">
                <div id="blog" class="single-post col-lg-10 center">
                    <!-- Post single item-->
                    <div class="post-item">
                        <div class="post-item-wrap" style="background: transparent;">
                            <div class="post-item-description">
                                <?PHP echo $dataPage['content']; ?>
                            </div>
                            <!--div class="post-tags">
                                <a href="#">Life</a>
                                <a href="#">Sport</a>
                                <a href="#">Tech</a>
                                <a href="#">Travel</a>
                            </div-->
                        </div>
                    </div>
                    <!-- end: Post single item-->
                </div>
            </div>
        </section>
        <!-- end: Page Content -->

        <!-- CLIENTS -->
        <!-- <section class="p-t-60">
            <div class="container">
                <div class="heading-text heading-section text-center">
                    <h2 class="">CLIENTS</h2>
                    <span class=" lead">The awesome clients we've had the pleasure to work with! </span>
                </div>
                <div class="carousel" data-items="6" data-items-sm="4" data-items-xs="3" data-items-xxs="2" data-margin="20" data-arrows="false" data-autoplay="true" data-autoplay-timeout="3000" data-loop="true">
                    <?PHP
                    $qLink      = "select * from link order by id_link desc";
                    $getLink    = $this->query->getDatabyQ($qLink);
                    foreach ($getLink as $link) {
                    ?>
                    <div>
                        <a href="<?PHP echo $link['link']; ?>" title="<?PHP echo $link['title']; ?>" target="_blank"><img alt="" src="<?PHP echo base_url(); ?>../images/link/<?PHP echo $link['picture']; ?>"> </a>
                    </div>
                    <?PHP } ?>
                </div>
            </div>

        </section> -->
        <!-- end: CLIENTS -->

        <!-- TEAM >
        <section class="no-border">
            <div class="container">
                <div class="heading-text heading-section text-center">
                    <h2 class="">Work With Us</h2>
                    <p class="">
                        <a href="<?PHP echo base_url(); ?>page/contact">Drop us a call or an E-mail to start working on your creative projects.</a>
                    </p>
                </div>
            </div>
        </section>
        <!-- end: TEAM -->

        <?PHP $this->load->view('theme/polo/footer'); ?>