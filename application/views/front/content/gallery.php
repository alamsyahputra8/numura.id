<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "
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
                    select id_video as id_file,id_menu,video as file, '2' as type from videos 
                    union 
                    select id_photo as id_file,id_menu, picture as file, '1' as type from photos
                ) as base
            ) as a
            where id_menu='$idmenu'
            order by rand()
            ";
$gPage      = $this->query->getDatabyQ($qPage);
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

        <script type="text/javascript">
            $( "#topbar" ).removeClass( "topbar-transparent" );
            $( "#header" ).removeAttr( "data-transparent" );
        </script>

        <section id="page-content" class="background-grey">
            <div class="container">
                <div class="heading-text heading-section ">
                    <h3>Foto & Video Terbaru</h3>
                </div>
                <!-- Portfolio Filter -->
                <nav class="grid-filter gf-outline" data-layout="#portfolio">
                    <ul>
                        <li class="active"><a href="#" data-category="*">Show All</a></li>
                        <li><a href="#" data-category=".ct-foto">Foto</a></li>
                        <li><a href="#" data-category=".ct-video">Video</a></li>
                    </ul>
                    <div class="grid-active-title">Show All</div>
                </nav>
                <!-- end: Portfolio Filter -->

                <!-- Portfolio -->
                <div id="portfolio" class="grid-layout portfolio-4-columns" data-margin="20">
                    <?PHP
                    foreach($gPage as $data) {
                        $id         = $data['id_file'];
                        $file      = $data['file'];
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
                                <div class="portfolio-item large-width img-zoom ct-video">
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
                    ?>
                </div>
                <!-- end: Portfolio -->
            </div>
        </section>
        <!-- end: Content -->

        <?PHP $this->load->view('theme/polo/footer'); ?>