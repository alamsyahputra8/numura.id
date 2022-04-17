<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "SELECT a.*,
            (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
            WHERE xa.menu='Manage Content' AND xa.data = a.id_content ORDER BY xa.date_time DESC limit 1)as createby,
            (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
            WHERE xa.menu='Manage Content' AND xa.data = a.id_content ORDER BY xa.date_time DESC limit 1)as create_date
            from content a where id_content='$id'";
$gPage      = $this->query->getDatabyQ($qPage);
$dPage      = array_shift($gPage);
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

        <!-- Page Content -->
        <section id="page-content" class="sidebar-right no-border">
            <div class="container">
                <div class="row">
                    <!-- content -->
                    <div class="content col-lg-12">
                        <!-- Blog -->
                        <div id="blog" class="single-post">
                            <!-- Post single item-->
                            <div class="post-item ">
                                <div class="post-item-wrap" style="background: transparent;">
                                    <div class="carousel dots-inside arrows-visible" data-items="1" data-lightbox="gallery">
                                        <a href="<?PHP echo base_url(); ?>images/content/<?PHP echo $dPage['cover']; ?>" data-lightbox="gallery-image">
                                            <img alt="image" src="<?PHP echo base_url(); ?>images/content/<?PHP echo $dPage['cover']; ?>">
                                        </a>
                                    </div>
                                    <div class="post-item-description">
                                        <h2><?PHP if(get_cookie('lang_is') === 'en'){ echo $dPage['title_en']; } else { echo $dPage['title']; } ?></h2>
                                        <div class="post-meta">

                                            <span class="post-meta-date "><i class="fa fa-calendar-alt"></i> <?PHP echo $dPage['create_date']; ?></span>
                                            <span class="post-meta-comments"><a href=""><i class="fa fa-user"></i> <?PHP echo $dPage['createby']; ?></a></span>
                                            
                                            <!--div class="post-meta-share">
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
                                        
                                        <div><?PHP if(get_cookie('lang_is') === 'en'){ echo $dPage['content_en']; } else { echo $dPage['content']; } ?></div>

                                    </div>
                                </div>
                            </div>
                            <!-- end: Post single item-->
                        </div>

                    </div>
                    <!-- end: content -->
                </div>
            </div>
        </section>
        <!-- end: Page Content -->

        <?PHP $this->load->view('theme/polo/footer'); ?>