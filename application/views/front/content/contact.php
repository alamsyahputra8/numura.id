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
        <section id="page-title" class="page-title-center text-light background-overlay-dark" style="background:url('<?PHP echo base_url(); ?>images/content/<?PHP echo $background; ?>') no-repeat center; background-size: 100% auto;">
            <div class="container">
                <div class="page-title">
                    <!--span class="post-meta-category"><a href="#"><?PHP echo $menu; ?></a></span-->
                    <h1>Contact Us</h1>
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
                    </div>

                </div>
            </div>
        </section>
        <!-- end: Page title -->

        <!-- Page Content -->
        <section id="page-content" class="sidebar-right no-border">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <h3 class="text-white text-uppercase">Get In Touch</h3>
                        <p class="text-white">Please contact us via the form below or at the contact details provided for further information about our business and services.</p>
                        <div class="m-t-30">
                            <form class="widget-contact-form" action="<?PHP echo base_url(); ?>core/insertinbox" role="form" method="post">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="text-white" for="name">Nama</label>
                                        <input type="text" aria-required="true" name="widget-contact-form-name" class="form-control required name" placeholder="Enter your Name">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="text-white" for="email">Email</label>
                                        <input type="email" aria-required="true" name="widget-contact-form-email" class="form-control required email" placeholder="Enter your Email">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label class="text-white" for="subject">Judul Pesan</label>
                                        <input type="text" name="widget-contact-form-subject" class="form-control required" placeholder="Subject...">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="text-white" for="message">Pesan</label>
                                    <textarea type="text" name="widget-contact-form-message" rows="5" class="form-control required" placeholder="Enter your Message"></textarea>
                                </div>

                                <button class="btn" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Kirim Pesan</button>
                            </form>

                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h3 class="text-uppercase text-white">Address & Map</h3>
                        <div class="row">
                            <div class="col-lg-12 text-white">
                                <address>
                                  <strong><?PHP echo $sitedata['name_site']; ?></strong><br>
                                  <?PHP echo $sitedata['alamat']; ?><br>
                                  <abbr title="Phone">P:</h4> <?PHP echo $sitedata['phone']; ?>
                                  </address>
                            </div>
                            <div class="col-lg-12">
                                
                            </div>
                        </div>
                        <!-- Google map sensor -->
                        <div class="m-t-30">
                            <?PHP echo $sitedata['maps']; ?>
                        </div>
                        <!-- Google map sensor -->

                    </div>
                </div>
            </div>
        </section>
        <!-- end: Page Content -->

        <?PHP $this->load->view('theme/polo/footer'); ?>