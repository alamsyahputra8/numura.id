<?PHP $logo = array_shift($getSiteData); ?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbaralumni'); ?>

        <?PHP $this->load->view('theme/polo/headeralumni'); ?>

       <style>
            .owl-carousel.arrows-large .owl-nav [class*="owl-"] {
                width: 48px!important;
                height: 48px!important;
                line-height: 48px!important;
            }
            .owl-carousel.arrows-large .owl-nav [class*="owl-"] i {
                line-height: 48px;
                font-size: 18px;
            }
            .owl-carousel .bgcontent {
                background: rgba(24,49,130,0.7);
                padding: 10px;
                position: absolute;
                bottom: 0;
                width: 100%;
            }
            .inspiro-slider .slide-captions h2 {
                font-size: 18px!important;
                line-height: 16px!important;
                margin-bottom: 10px!important;
            }
            .inspiro-slider .slide-captions .strong {
                font-size: 9px !important;
                letter-spacing: 1px!important;
                line-height: 9px!important;
                margin-bottom: 20px!important;
            }
            .inspiro-slider .slide-captions .strong::after {
                border-top: 3px solid transparent!important;
            }
            .tabs .nav-tabs .nav-link.active {
                color: #FFF!important;
                background-color: #f2a138!important;
                border-bottom: 2px solid #6a4734!important;
            }
            .tabs .nav-tabs .nav-link {
                background: #d2d2d2;
                padding: 10px 20px!important;
            }
            .homepost .post-thumbnail-entry {
                border-bottom: 1px solid #eaeaea;
                float: left;
                margin-bottom: 7px;
                width: 100%;
            }
            .tabs.hometabs .nav-tabs {
                margin-bottom: 15px;
            }
            .post-thumbnail-list.homepost .post-thumbnail-entry .post-thumbnail-content a {
                font-size: 14px;
                font-weight: bold;
                color: #6a4734;
            }
            .homepost .post-thumbnail-entry .post-thumbnail-content .post-date, .post-thumbnail-entry .post-thumbnail-content .post-category {
                font-size: 12px;
            }
            #clientslogo .owl-carousel .owl-item img {
                display: block;
                width: auto;
                max-width: 100%;
                max-height: 100px;
            }
            #autoslide {
                padding: 0;
                background: #f5b83c;
                width: 100%;
                height: auto!important;
            }
            #autoslide li {
                padding: 0 15px 0 15px;
                list-style: none;
            }
            #autoslide .news-item.light {
                background: rgba(255,255,255,0.2);
            }
            .imgkomoditi {
                max-height: 45px;
                padding-top: 6px;
            }
            .titlekomoditi {
                font-size: 15px;
                font-weight: bold;
                padding-top: 5px;
                margin-bottom: -5px;
            }
            .subtitlekomoditi {
                font-size: 11px;
                padding-bottom: 5px;
            }
            .galhome {
                height: 254px!important;
                width: auto!important;
            }
            .homecounter .counter {
                margin-bottom: -8px;
            }
            .homecounter .icon-box {
                margin-bottom: -10px;
                width: 100%;
            }
            .homecounter .icon-box.effect.small:not(.center) > .counter span {
                font-size: 25px !important;
            }
            .homecounter .icon {
                border-color: #f5b83c!important;
            }
            .homecounter .icon i {
                color: #f5b83c!important;
            }
            #gmapsembed iframe {
                width: 100%!important;
            }
            .linkbanner {
                width: 100%;
                height: 100%;
                position: absolute!important;
                z-index: 1;
                top: 0;
                left: 0;
            }
        </style>

        <section id="page-content" class="p20">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Inspiro Slider -->
                        <div id="slider" class="inspiro-slider slider-fullscreen arrows-large arrows-creative dots-creative" data-height="440" data-height-xs="360" >
                            <?PHP
                            $qSlides    = "select * from banner where flag_website='$coreid' order by 1 desc";
                            $getSlides  = $this->query->getDatabyQ($qSlides);
                            foreach ($getSlides as $dataslides) {
                                if(get_cookie('lang_is') === 'en'){
                                    $imgs   = $dataslides['img_en'];
                                } else {
                                    $imgs   = $dataslides['img'];
                                }
                            ?>
                            <!-- Slide 1 -->
                            <div class="slide kenburns" style="background-image:url('<?PHP echo base_url(); ?>images/slides/<?PHP echo $imgs; ?>');">
                                <div class="bgcontent">
                                    <a href="<?PHP echo $dataslides['link']; ?>">
                                        <div class="slide-captions text-center text-light">
                                            <!-- Captions -->
                                            <!-- <h2 class="text-dark"><?PHP echo $dataslides['title']; ?></h2>
                                            <span class="strong"><?PHP echo $dataslides['sub']; ?></span> -->
                                            <!-- <a class="btn" href="#">Purchase Now</a>
                                            <a class="btn btn-light">Purchase</a> -->
                                            <!-- end: Captions -->
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <!-- end: Slide 1 -->
                            <?PHP } ?>
                        </div>
                        <!--end: Inspiro Slider -->
                    </div>
                    <div class="col-md-6 padding0">
                        <div class="tabs hometabs">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <?PHP
                                $qBC    = "select * from category_blog order by id asc";
                                $gBC    = $this->query->getDatabyQ($qBC);
                                $xb     = 0;
                                foreach ($gBC as $data) { $xb++;
                                    $id = $data['id'];
                                    if($xb==1) { $active='active'; } else { $active=''; }
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link <?PHP echo $active; ?>" id="homeblog<?PHP echo $id; ?>-tab" data-toggle="tab" href="#homeblog<?PHP echo $id; ?>" role="tab" aria-controls="homeblog<?PHP echo $id; ?>" aria-selected="true">
                                        <?PHP if(get_cookie('lang_is') === 'en'){ echo $data['category_en']; } else { echo $data['category']; } ?>
                                    </a>
                                </li>
                                <?PHP } ?>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <?PHP
                                $qBC    = "select * from category_blog order by id asc";
                                $gBC    = $this->query->getDatabyQ($qBC);
                                $xbD    = 0;
                                foreach ($gBC as $dataD) { $xbD++;
                                    $id = $dataD['id'];
                                    if($xbD==1) { $active='show active'; } else { $active=''; }
                                ?>
                                <div class="tab-pane fade <?PHP echo $active; ?>" id="homeblog<?PHP echo $id; ?>" role="tabpanel" aria-labelledby="homeblog<?PHP echo $id; ?>-tab">
                                    <div class="widget " style="margin-bottom:0px;">
                                        <div class="post-thumbnail-list homepost">
                                            <?PHP
                                            $qBlog      = "select a.* from blog a where a.id_category='$id' order by id_blog desc limit 5";
                                            $getBlog    = $this->query->getDatabyQ($qBlog);
                                            if ($getBlog) {
                                            foreach ($getBlog as $blog) {
                                            ?>
                                            <div class="post-thumbnail-entry">
                                                <img alt="" src="<?PHP echo base_url(); ?>images/content/<?PHP echo $blog['picture']; ?>">
                                                <div class="post-thumbnail-content">
                                                    <a href="<?PHP echo base_url(); ?>blog/<?PHP echo $blog['link']; ?>">
                                                        <?PHP if(get_cookie('lang_is') === 'en'){ echo $blog['title_en']; } else { echo $blog['title']; } ?>
                                                    </a>
                                                    <span class="post-date"><i class="far fa-clock"></i> <?PHP echo $this->formula->TanggalIndo($blog['create_date']); ?></span>
                                                </div>
                                            </div>
                                            <?PHP } ?>
                                            <?PHP } else { ?>
                                            <center>
                                                Mohon maaf, belum tersedia data pada kolom ini.
                                            </center>
                                            <?PHP } ?>
                                        </div>
                                    </div>
                                </div>
                                <?PHP } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?PHP if ($cbase==1) { ?>
            <?PHP
            $qCH    = "SELECT * FROM content where id_menu='$idmenu' order by 1 asc";
            $cCH    = $this->db->query($qCH)->num_rows();
            if ($cCH>0) {
                $gCH= $this->db->query($qCH)->result_array();
                $noc =0;
                foreach($gCH as $dch) { $noc++;
                    if ($noc % 2==0) {
                        $colcon = '#f8f9fa';
                    } else {
                        $colcon = '#FFF';
                    }
            ?>
                    <section id="page-content" style="background: <?PHP echo $colcon; ?>">
                        <div class="container">
                            <div class="row">
                                <?PHP if ($noc % 2==0) { ?>
                                    <div class="col-lg-7">
                                        <div class="heading-text heading-section text-left">
                                            <h4><?PHP if(get_cookie('lang_is') === 'en'){ echo $dch['title_en']; } else { echo $dch['title']; } ?></h4>
                                        </div>
                                        <div>
                                            <?PHP if(get_cookie('lang_is') === 'en'){ echo $dch['headline_en']; } else { echo $dch['headline']; } ?>
                                        </div><br>
                                        <a href="<?PHP echo base_url(); ?>content/<?PHP echo $dch['id_content']; ?>" class="btn btn-sm btn-warning">
                                            <?PHP if(get_cookie('lang_is') === 'en'){ echo 'Read More'; } else { echo 'Selengkapnya'; } ?> <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="post-image">
                                            <img src="<?PHP echo base_url(); ?>images/content/<?PHP echo $dch['cover']; ?>">
                                        </div>
                                    </div>
                                <?PHP } else { ?>
                                    <div class="col-lg-5">
                                        <div class="post-image">
                                            <img src="<?PHP echo base_url(); ?>images/content/<?PHP echo $dch['cover']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-7">
                                        <div class="heading-text heading-section text-left">
                                            <h4><?PHP if(get_cookie('lang_is') === 'en'){ echo $dch['title_en']; } else { echo $dch['title']; } ?></h4>
                                        </div>
                                        <div>
                                            <?PHP if(get_cookie('lang_is') === 'en'){ echo $dch['headline_en']; } else { echo $dch['headline']; } ?>
                                        </div><br>
                                        <a href="<?PHP echo base_url(); ?>content/<?PHP echo $dch['id_content']; ?>" class="btn btn-sm btn-warning">
                                            <?PHP if(get_cookie('lang_is') === 'en'){ echo 'Read More'; } else { echo 'Selengkapnya'; } ?> <i class="fa fa-arrow-right"></i>
                                        </a>
                                    </div>
                                <?PHP } ?>
                            </div>
                        </div>
                    </section>
                <?PHP } ?>
            <?PHP } ?>
        <?PHP } ?>
        
        <?PHP if ($cblog==1) { ?>
            <section id="page-content">
                <div class="container">

                    <div class="heading-text heading-section  text-center text-center">
                        <h4><?PHP if(get_cookie('lang_is') === 'en'){ echo 'Latest News'; } else { echo 'Berita Terkini'; } ?></h4>
                    </div>

                    <div id="blog" class="grid-layout post-4-columns m-b-30" data-item="post-item">
                        <?PHP
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
                                    order by id_blog desc
                                    limit 8
                                    ";
                        $gPage      = $this->query->getDatabyQ($qPage);
                        $cek    = $this->query->getNumRowsbyQ($qPage)->num_rows();

                        if ($cek>0) {
                        foreach ($gPage as $dataPage) {
                        ?>
                        <div class="post-item border">
                            <div class="post-item-wrap">
                                <div class="post-image">
                                    <a href="<?PHP echo base_url(); ?>blog/<?PHP echo $dataPage['link']; ?>">
                                        <img alt="" src="<?PHP echo base_url(); ?>images/content/<?PHP echo $dataPage['picture']; ?>">
                                    </a>
                                </div>
                                <div class="post-item-description">
                                    <span class="post-meta-date">
                                        <i class="fa fa-calendar-alt"></i><?PHP echo $this->formula->TanggalIndo($dataPage['create_date']); ?>
                                    </span>
                                    <span class="post-meta-comments"><a href="#"><i class="fa fa-user"></i>
                                        <?PHP if(get_cookie('lang_is') === 'en'){ echo 'Added by :'; } else { echo 'Dibuat oleh :'; } echo $dataPage['createby']; ?></a>
                                    </span>
                                    <h2>
                                        <a href="<?PHP echo base_url(); ?>blog/<?PHP echo $dataPage['link']; ?>"><?PHP echo $dataPage['title']; ?></a>
                                    </h2>
                                    <p><?PHP echo $dataPage['headline']; ?></p>
                                    <a href="<?PHP echo base_url(); ?>blog/<?PHP echo $dataPage['link']; ?>" class="item-link">
                                        <?PHP if(get_cookie('lang_is') === 'en'){ echo 'Read More'; } else { echo 'Selengkapnya'; } ?> <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?PHP } } else { ?>
                        <h3>We are sorry, No data available.</h3>
                        <?PHP } ?>

                </div>

            </section>
        <?PHP } ?>

        <?PHP if ($cour==1) { ?>
            <style>
                .team-members.team-members-shadow .team-member .team-desc {
                    padding: 10px;
                }
            </style>
            <?PHP
            $gOTH       = $this->db->query("
                        SELECT * FROM menu_site where id_menu in ($otidm)
                        ")->result_array();
            foreach($gOTH as $doth) {
                $othmenu    = $doth['id_menu'];
                $qOT        = "
                            select
                                a.*
                            from
                            ourteam a
                            where id_menu=$othmenu
                            order by sort asc
                            ";
                $gOT      = $this->query->getDatabyQ($qOT);
                $cOT      = $this->db->query($qOT)->num_rows();
                if ($cOT>0) {
                ?>
                    <section id="page-content">
                        <div class="container">
                            <div class="heading-text heading-line text-center">
                                <h4><?PHP if(get_cookie('lang_is') === 'en'){ echo $doth['menu_en']; } else { echo $doth['menu']; } ?></h4>
                            </div>

                            <div class="carousel team-members team-members-shadow" data-arrows="true" data-margin="20" data-items="4">
                                 <?PHP
                                foreach ($gOT as $data) {
                                    if(get_cookie('lang_is') === 'en'){
                                        $pos    = $data['position_en'];
                                    } else {
                                        $pos    = $data['position'];
                                    }
                                ?>
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
                                <?PHP } ?>
                            </div>
                        </div>
                    </section> 
                <?PHP } ?>
            <?PHP } ?>
        <?PHP } ?>
        
        <?PHP if ($ccont==1) { ?>
		<section class="" id="services" style="background: #f8f9fa;">
            <div class="container">
    			<div class="row">
    				<div class="col-lg-6">
                        <div><img src="<?PHP echo base_url(); ?>images/cropped-unnamed.png" alt="UNPAD" style="max-height: 80px;"></div><br>
    					<h3 class=" text-uppercase">
                            <?PHP if(get_cookie('lang_is') === 'en'){ echo 'Get In Touch'; } else { echo 'Hubungi Kami'; } ?>
                        </h3>
    					<p class="">
                            <?PHP if(get_cookie('lang_is') === 'en'){
                            echo '
                            Please contact us via the form below or at the contact details provided for further information about our business and services.';
                            } else {
                            echo '
                            Silakan hubungi kami melalui formulir di bawah ini atau di detail kontak yang disediakan untuk informasi lebih lanjut tentang kami.';
                            }
                            ?>
                        </p>
    					<div class="m-t-30">
    						<form class="widget-contact-form" action="<?PHP echo base_url(); ?>core/insertinbox" role="form" method="post">
    							<div class="row">
    								<div class="form-group col-md-6">
                                        <?PHP if(get_cookie('lang_is') === 'en'){ ?>
    									<label class="" for="name">Name</label>
    									<input type="text" aria-required="true" name="widget-contact-form-name" class="form-control required name" placeholder="Enter your Name">
                                        <?PHP } else { ?>
                                        <label class="" for="name">Nama</label>
                                        <input type="text" aria-required="true" name="widget-contact-form-name" class="form-control required name" placeholder="Masukan Nama Anda">
                                        <?PHP } ?>
    								</div>
    								<div class="form-group col-md-6">
                                        <?PHP if(get_cookie('lang_is') === 'en'){ ?>
    									<label class="" for="email">Email</label>
    									<input type="email" aria-required="true" name="widget-contact-form-email" class="form-control required email" placeholder="Enter your Email">
                                        <?PHP } else { ?>
                                        <label class="" for="email">Email</label>
                                        <input type="email" aria-required="true" name="widget-contact-form-email" class="form-control required email" placeholder="Masukan Email Anda">
                                        <?PHP } ?>
    								</div>
    							</div>
    							<div class="row">
    								<div class="form-group col-md-12">
                                        <?PHP if(get_cookie('lang_is') === 'en'){ ?>
    									<label class="" for="subject">Subject</label>
    									<input type="text" name="widget-contact-form-subject" class="form-control required" placeholder="Subject...">
                                        <?PHP } else { ?>
                                        <label class="" for="subject">Judul Pesan</label>
                                        <input type="text" name="widget-contact-form-subject" class="form-control required" placeholder="Judul...">
                                        <?PHP } ?>
    								</div>
    							</div>
    							<div class="form-group">
                                    <?PHP if(get_cookie('lang_is') === 'en'){ ?>
    								<label class="" for="message">Message</label>
    								<textarea type="text" name="widget-contact-form-message" rows="5" class="form-control required" placeholder="Enter your Message"></textarea>
                                    <?PHP } else { ?>
                                    <label class="" for="message">Pesan</label>
                                    <textarea type="text" name="widget-contact-form-message" rows="5" class="form-control required" placeholder="Masukan Pesan Anda"></textarea>
                                    <?PHP } ?>
    							</div>
                                <?PHP if(get_cookie('lang_is') === 'en'){ ?>
    							<button class="btn btn-warning" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Send</button>
                                <?PHP } else { ?>
                                <button class="btn btn-warning" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Kirim</button>
                                <?PHP } ?>
    						</form>

    					</div>
    				</div>
    				<div class="col-lg-6">
    					<h3 class="text-uppercase "><?PHP if(get_cookie('lang_is') === 'en'){ echo 'Address & Map'; } else { echo 'Alamat & Lokasi'; } ?></h3>
    					<div class="row">
    						<div class="col-lg-12 ">
    							<address>
    							  <strong><?PHP echo $logo['name_site']; ?></strong><br>
    							  <?PHP echo $logo['alamat']; ?><br>
    							  <abbr title="Phone">P:</h4> <?PHP echo $logo['phone']; ?>
    							  </address>
    						</div>
    						<div class="col-lg-12">
    							
    						</div>
    					</div>
    					<!-- Google map sensor -->
    					<div class="m-t-30">
    						<?PHP echo $logo['maps']; ?>
    					</div>
    					<!-- Google map sensor -->

    				</div>
    			</div>
    		</div>  
		</section>
        <?PHP } ?>

        <style>
        .headershowreel {
            background: transparent;
            padding: 10px;
            border: none;
            z-index: 2;
        }
        .bodyshowreel {
            padding: 0px;
        }
        </style>

       <?PHP $this->load->view('theme/polo/footer'); ?>

       <script>
            $(document).on('click', '.btndetailworks', function(e){
                e.preventDefault();

                var uid = $(this).data('id'); // get id of clicked row

                $('#dynamic-content').hide(); // hide dive for loader
                $('#modal-loader').show();  // load ajax loader

                $('#datadetailworks').html('<div class="loaderdetailworks"><center>Please Wait...</center></div>');
                
                $.ajax({
                    url: '<?PHP echo base_url(); ?>core/modaldetailworks',
                    type: 'POST',
                    data: 'id='+uid,
                    dataType: 'json'
                })
                .done(function(data){
                    $('#dynamic-content').hide(); // hide dynamic div
                    $('#dynamic-content').show(); // show dynamic div
                    
                    $('#titledetail').html(data.title);

                    $("#datadetailworks").load( "<?PHP echo base_url(); ?>works/"+data.id_album+"", function() {
                        $(".loaderdetailworks").fadeOut('slow');
                    });

                    $('#modal-loader').hide();    // hide ajax loader
                })
                .fail(function(){
                    $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please refresh page...');
                });
            });
        </script>

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