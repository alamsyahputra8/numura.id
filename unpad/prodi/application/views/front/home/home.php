<?PHP $logo = array_shift($getSiteData); ?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

        <!-- Inspiro Slider -->
        <div id="slider" class="inspiro-slider slider-fullscreen arrows-large arrows-creative dots-creative" data-height-xs="360">
            <?PHP
            $qSlides    = "select * from banner order by 1 desc";
            $getSlides  = $this->query->getDatabyQ($qSlides);
            foreach ($getSlides as $dataslides) {
            ?>
            <!-- Slide 1 -->
            <div class="slide kenburns" style="background-image:url('<?PHP echo base_url(); ?>../images/slides/<?PHP echo $dataslides['img']; ?>');">
                <div class="container">
                    <div class="slide-captions text-center text-light">
                        <!-- Captions -->
                        <!-- <h2 class="text-dark"><?PHP echo $dataslides['title']; ?></h2>
                        <span class="strong"><?PHP echo $dataslides['sub']; ?></span> -->
                        <!-- <a class="btn" href="#">Purchase Now</a>
                        <a class="btn btn-light">Purchase</a> -->
                        <!-- end: Captions -->
                    </div>
                </div>
            </div>
            <!-- end: Slide 1 -->
            <?PHP } ?>
        </div>
        <!--end: Inspiro Slider -->
        
        <section id="page-content">
            <div class="container">

            <div class="heading-text heading-section  text-center text-center">
                <h2><?PHP if(get_cookie('lang_is') === 'en'){ echo 'Latest News'; } else { echo 'Berita Terkini'; } ?></h2>
            </div>

            <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
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
                                <img alt="" src="<?PHP echo base_url(); ?>../images/content/<?PHP echo $dataPage['picture']; ?>">
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
            <!-- <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#"><i class="fa fa-angle-left"></i></a></li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item active"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">4</a></li>
            <li class="page-item"><a class="page-link" href="#">5</a></li>
            <li class="page-item"><a class="page-link" href="#"><i class="fa fa-angle-right"></i></a></li>
            </ul> -->

            </div>

        </section>
        
		<section class="" id="services" style="background: #f8f9fa;">
        <div class="container">
			<div class="row">
				<div class="col-lg-6">
                    <div><img src="<?PHP echo base_url(); ?>../images/cropped-unnamed.png" alt="UNPAD" style="max-height: 80px;"></div><br>
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

        <div class="modal fade show" id="modalshowreel" tabindex="-1" role="modal" aria-labelledby="modal-label-3">
            <div class="modal-dialog modal-lg" style="max-width: 80%;">
                <div class="modal-content">
                    <div class="modal-body bodyshowreel">
                        <div class="row">
                            <?PHP
                            $showreel   = str_replace('https://www.youtube.com/watch?v=','https://www.youtube.com/embed/',$logo['showreel']);
                            ?>
                            <iframe width="1280" height="720" src="<?PHP echo $showreel; ?>?rel=0&amp;showinfo=0" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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