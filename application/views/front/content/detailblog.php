<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "select a.*, (select name from user where userid=a.create_by) as createby from blog a where link='$id'";
$gPage      = $this->query->getDatabyQ($qPage);
$dPage      = array_shift($gPage);

$qOther      = "
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
            where id_blog!='".$dPage['id_blog']."'
            order by id_blog desc
            limit 20
            ";
$gOther      = $this->query->getDatabyQ($qOther);
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

        <!-- Page Content -->
        <section id="page-content" class="sidebar-right no-border">
            <div class="container">
                <div class="row">
                    <!-- content -->
                    <div class="col-sm-2"></div>
                    <div class="content col-lg-8">
                        <!-- Blog -->
                        <div id="blog" class="single-post">
                            <!-- Post single item-->
                            <div class="post-item text-white">
                                <div class="post-item-wrap" style="background: transparent;">
                                    <div class="carousel dots-inside arrows-visible" data-items="1" data-lightbox="gallery">
                                        <a href="<?PHP echo base_url(); ?>images/content/<?PHP echo $dPage['picture']; ?>" data-lightbox="gallery-image">
                                            <img alt="image" src="<?PHP echo base_url(); ?>images/content/<?PHP echo $dPage['picture']; ?>">
                                        </a>
                                    </div>
                                    <div class="post-item-description">
                                        <h2><?PHP echo $dPage['title']; ?></h2>
                                        <div class="post-meta">

                                            <span class="post-meta-date text-white"><i class="fa fa-calendar-alt"></i> Posted on : <?PHP echo $this->formula->TanggalIndo($dPage['create_date']); ?></span>
                                            <span class="post-meta-comments"><a href=""><i class="fa fa-user"></i> Added by : <?PHP echo $dPage['createby']; ?></a></span>
                                            
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
                                        
                                        <div><?PHP echo $dPage['content']; ?></div>

                                    </div>
                                </div>
                            </div>
                            <!-- end: Post single item-->
                        </div>

                    </div>
                    <div class="col-sm-2"></div>
                    <!-- end: content -->

                    <!-- Sidebar-->
                    <div class="col-sm-2"></div>
                    <div class="sidebar sticky-sidebar col-lg-8">
                        <?PHP
                        $cekO    = $this->query->getNumRowsbyQ($qOther)->num_rows();

                        if ($cekO>4) {
                        ?>
                        <div class="widget ">
                            <h4 class="widget-title">Recent Posts</h4>

                            <?PHP
                            foreach ($gOther as $dataOther) {
                            ?>
                            <div class="post-thumbnail-list">
                                <div class="post-thumbnail-entry">
                                    <img alt="" src="<?PHP echo base_url(); ?>images/content/<?PHP echo $dataOther['picture']; ?>">
                                    <div class="post-thumbnail-content">
                                        <a href="<?PHP echo $dataOther['link']; ?>"><?PHP echo $dataOther['title']; ?></a>
                                        <span class="post-date"><i class="far fa-clock"></i> <?PHP echo $this->formula->TanggalIndo($dataOther['create_date']); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?PHP } ?>

                        </div>
                        <?PHP } ?>
                    </div>
                    <div class="col-sm-2"></div>
                    <!-- end: Sidebar-->
                </div>
            </div>
        </section>
        <!-- end: Page Content -->

        <?PHP $this->load->view('theme/polo/footer'); ?>