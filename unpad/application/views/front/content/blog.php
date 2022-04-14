<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "
            select
                a.*,
                (select menu from menu_site where id_menu=a.id_menu) as menu,
                (select name from user where userid=a.create_by) as createby,
                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Berita' AND xa.data = a.id_blog ORDER BY xa.date_time DESC limit 1)as update_by,
                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Berita' AND xa.data = a.id_blog ORDER BY xa.date_time DESC limit 1)as last_update
            from
            blog a
            where id_menu='$idmenu'
            order by id_blog desc
            ";
$gPage      = $this->query->getDatabyQ($qPage);
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

        <!-- Page title -->
        <!-- <section id="page-title" class="page-title-center text-light background-overlay-dark" style="background:#bababa url('<?PHP echo base_url(); ?>images/content/<?PHP echo $background; ?>') no-repeat center; background-size: 100% auto;"> -->
        <section id="page-title" data-bg-parallax="<?PHP echo base_url(); ?>images/content/<?PHP echo $background; ?>">
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

        <section id="page-content" class="no-border">

            <div class="container">
                <!-- Blog -->
                <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">

                    <?PHP
                    $cek    = $this->query->getNumRowsbyQ($qPage)->num_rows();

                    if ($cek>0) {
                    foreach ($gPage as $dataPage) {
                    ?>
                        <!-- Post item-->
                        <div class="post-item border">
                            <div class="post-item-wrap">
                                <div class="post-image">
                                    <a href="<?PHP echo base_url(); ?>blog/<?PHP echo $dataPage['link']; ?>">
                                        <img alt="" src="<?PHP echo base_url(); ?>images/content/<?PHP echo $dataPage['picture']; ?>">
                                    </a>
                                    <!--span class="post-meta-category"><a href="">Lifestyle</a></span-->
                                </div>
                                <div class="post-item-description">
                                    <span class="post-meta-date">
                                       <i class="fa fa-calendar-alt"></i><?PHP echo $this->formula->TanggalIndo($dataPage['create_date']); ?>
                                    </span>
                                    <span class="post-meta-comments"><a href="#"><i class="fa fa-user"></i>
                                        <?PHP if(get_cookie('lang_is') === 'en'){ echo 'Added by :'; } else { echo 'Dibuat oleh :'; } echo $dataPage['createby']; ?></a></span>
                                    <h2><a href="<?PHP echo base_url(); ?>blog/<?PHP echo $dataPage['link']; ?>"><?PHP echo $dataPage['title']; ?></a></h2>
                                    <p><?PHP echo $dataPage['headline']; ?></p>

                                    <a href="<?PHP echo base_url(); ?>blog/<?PHP echo $dataPage['link']; ?>" class="item-link">
                                        <?PHP if(get_cookie('lang_is') === 'en'){ echo 'Read More'; } else { echo 'Selengkapnya'; } ?> <i class="fa fa-arrow-right"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <!-- end: Post item-->
                    <?PHP } } else { ?>
                    <h3>We are sorry, No data available.</h3>
                    <?PHP } ?>
                </div>

                <?PHP if ($cek>20) { ?>
                <!--div id="pagination" class="infinite-scroll">
                    <a href="blog-masonry-infinite-scroll-2.html"></a>
                </div>
                
                <div id="showMore">
                    <a href="#" class="btn btn-rounded btn-light"><i class="icon-refresh-cw"></i>  Load More Posts</a>
                </div-->
                <?PHP } ?>
                </div>
            </div>
        </section>

        <?PHP $this->load->view('theme/polo/footer'); ?>