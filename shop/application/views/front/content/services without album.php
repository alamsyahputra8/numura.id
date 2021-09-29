<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "
            select
                a.*,
                (select menu from menu_site where id_menu=a.id_menu) as menu,
                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Services' AND xa.data = a.id_services ORDER BY xa.date_time DESC limit 1)as update_by,
                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Services' AND xa.data = a.id_services ORDER BY xa.date_time DESC limit 1)as last_update
            from
            services a
            where id_menu='$idmenu'
            ";
$gPage      = $this->query->getDatabyQ($qPage);
$dataPage   = array_shift($gPage);

$qWork      = "
            select
                a.*,
                (select menu from menu_site where id_menu=a.id_menu) as menu,
                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu in ('Manage Photos','Manage Videos') AND xa.data = a.id_file ORDER BY xa.date_time DESC limit 1)as update_by,
                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu in ('Manage Photos','Manage Videos') AND xa.data = a.id_file ORDER BY xa.date_time DESC limit 1)as last_update
            from
            (
                select * from (
                    select id_video as id_file,id_menu, id_album, video as file, '2' as type from videos 
                    union 
                    select id_photo as id_file,id_menu, id_album, picture as file, '1' as type from photos
                ) as base
            ) as a
            where id_album='$idmenu'
            order by id_file desc
            limit 6
            ";
            //echo "<pre>".$qWork."</pre>";
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>


        <!-- Page title -->
        <section id="page-title" class="page-title-center text-light background-overlay-dark" style="background:url('<?PHP echo base_url(); ?>images/content/<?PHP echo $background; ?>') no-repeat center; background-size: 100% auto;">
            <div class="container">
                <div class="page-title">
                    <!--span class="post-meta-category"><a href="#"><?PHP echo $menu; ?></a></span-->
                    <h1><?PHP echo $dataPage['title']; ?></h1>
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

        <style>
        .post-item-description p, .post-item-description h1, .post-item-description h2, .post-item-description h3, .post-item-description h4, .post-item-description h5, .post-item-description h6 { color: #FFF!important; }
        .table-bordered, .table-bordered th, .table-bordered td {border: none!important;}
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
                        </div>
                    </div>
                    <!-- end: Post single item-->
                </div>
            </div>
        </section>
        <!-- end: Page Content -->

         <!-- WORKS -->
        <section class="p-t-60">
            <div class="container">
                <div class="heading-text heading-section text-center">
                    <h2 class="text-white">Latest Work</h2>
                    <!--span class="text-white lead"></span-->
                </div>
                <!-- Portfolio -->
                <?PHP
                $cekWork        = $this->query->getNumRowsbyQ($qWork)->num_rows();
                if ($cekWork>0) {
                ?>
                <div id="portfolio" class="grid-layout portfolio-3-columns" data-margin="20">
                    <?PHP
                    $gWork      = $this->query->getDatabyQ($qWork);
                    foreach($gWork as $data) {
                        $id         = $data['id_file'];
                        $file       = $data['file'];
                        $type       = $data['type'];

                        if ($type=='1') {
                            echo '
                                <div class="portfolio-item img-zoom ct-foto">
                                    <div class="portfolio-item-wrap">
                                        <div class="portfolio-image">
                                            <a href="#"><img src="'.base_url().'images/gallery/'.$file.'" alt=""></a>
                                        </div>
                                        <div class="portfolio-description">
                                            <a title="Sample Photo" data-lightbox="image" href="'.base_url().'images/gallery/'.$file.'">
                                                <i class="fa fa-expand"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            ';
                        } else {
                            $embed      = str_replace('https://www.youtube.com/watch?v=','https://www.youtube.com/embed/',$file);

                            echo '
                                <div class="portfolio-item img-zoom ct-video">
                                   <div class="portfolio-item-wrap">
                                        <div class="portfolio-image">
                                            <a href="#">
                                                <iframe width="1280" height="720" src="'.$embed.'?rel=0&amp;showinfo=0" allowfullscreen></iframe>
                                            </a>
                                        </div>
                                        <div class="portfolio-description">
                                            <a title="Video Youtube" data-lightbox="iframe" href="'.$file.'"><i class="fa fa-play"></i></a>
                                            <a href="'.$file.'" target="_blank"><i class="fa fa-link"></i></a>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                    }
                    $idworksmenu    = $data['id_menu'];
                    ?>
                </div>
                <?PHP
                } else {
                    echo '
                    <div><h3 class="text-center text-white">We are sorry, No data available.</h3></div>
                    ';
                }
                ?>
                <!-- end: Portfolio -->
                <?PHP if ($cekWork>3) { ?>
                <?PHP
                $gLinkW     = $this->query->getDatabyQ("select * from menu_site where style='works' and id_menu='$idworksmenu'");
                $dataLW     = array_shift($gLinkW);
                ?>
                <div>
                    <center><a href="<?PHP echo base_url(); ?>page/<?PHP echo $dataLW['link']; ?>" class="btn btn-rounded btn-dark">MORE WORKS</a></center>
                </div>
                <?PHP } ?>
            </div>

        </section>
        <!-- end: WORKS -->

        <!-- CLIENTS -->
        <section class="p-t-60">
            <div class="container">
                <div class="heading-text heading-section text-center">
                    <h2 class="text-white">Other Services</h2>
                    <!--span class="text-white lead"></span-->
                </div>
                <div class="carousel" data-items="6" data-items-sm="4" data-items-xs="3" data-items-xxs="2" data-margin="20" data-arrows="false" data-autoplay="true" data-autoplay-timeout="3000" data-loop="true">
                    <?PHP
                    $qLink      = "select * from link order by id_link desc limit 6";
                    $getLink    = $this->query->getDatabyQ($qLink);
                    foreach ($getLink as $link) {
                    ?>
                    <div>
                        <a href="<?PHP echo $link['link']; ?>" title="<?PHP echo $link['title']; ?>" target="_blank"><img alt="" src="<?PHP echo base_url(); ?>images/link/<?PHP echo $link['picture']; ?>"> </a>
                    </div>
                    <?PHP } ?>
                </div>
            </div>

        </section>
        <!-- end: CLIENTS -->

        <!-- TEAM -->
        <section class="no-border">
            <div class="container">
                <div class="heading-text heading-section text-center">
                    <h2 class="text-white">Work With Us</h2>
                    <p class="text-white">
                        <a href="<?PHP echo base_url(); ?>page/contact">Drop us a call or an E-mail to start working on your creative projects.</a>
                    </p>
                </div>
            </div>
        </section>
        <!-- end: TEAM -->

        <?PHP $this->load->view('theme/polo/footer'); ?>