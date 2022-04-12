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

        <style>
            .post-item .post-item-wrap { background: transparent!important; }
            .team-members .team-member .team-image { max-height: 330px; overflow: hidden; }
        </style>

        <!-- Page title -->
        <section id="page-title" class="page-title-center text-light background-overlay-dark" style="background:#bababa url('<?PHP echo base_url(); ?>images/content/<?PHP echo $background; ?>') no-repeat center; background-size: cover;">
            <div class="container">
                <div class="page-title">
                    <!--span class="post-meta-category"><a href="#"><?PHP echo $menu; ?></a></span-->
                    <?PHP if(get_cookie('lang_is') === 'en'){ ?>
                    <h1><?PHP echo $dataPage['title_en']; ?></h1>
                    <?PHP } else { ?>
                    <h1><?PHP echo $dataPage['title']; ?></h1>
                    <?PHP } ?>
                    <div class="small m-b-20"><?PHP echo $dataPage['last_update']; ?> | <a href="#">by <?PHP echo $dataPage['update_by']; ?></a></div>
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

        <!-- Page Content -->
        <section id="page-content" class="sidebar-right no-border">
            <div class="container">
                <div id="blog" class="single-post col-lg-10 center">
                    <!-- Post single item-->
                    <div class="post-item">
                        <div class="post-item-wrap">
                            <div class="post-item-description">
                                <?PHP
                                if(get_cookie('lang_is') === 'en'){ 
                                    echo $dataPage['content_en']; 
                                } else {
                                    echo $dataPage['content']; 
                                }
                                ?>
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

        <?PHP $this->load->view('theme/polo/footer'); ?>