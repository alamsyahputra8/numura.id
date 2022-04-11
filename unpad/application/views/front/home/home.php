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
            <div class="slide kenburns" style="background-image:url('<?PHP echo base_url(); ?>images/slides/<?PHP echo $dataslides['img']; ?>');">
                <div class="container">
                    <div class="slide-captions text-center text-light">
                        <!-- Captions -->
                        <h2 class="text-dark"><?PHP echo $dataslides['title']; ?></h2>
                        <span class="strong"><?PHP echo $dataslides['sub']; ?></span>
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
 
        
        <style>
            .relativeposition {
                position: relative!important;
            }
			#contact {
			}
        </style>
        <div class="modal fade show" id="detailworks" tabindex="-2" role="modal" aria-labelledby="modal-label-3" style="z-index: 1041;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="titledetail" class="modal-title"></h4>
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="container" id="datadetailworks">
                            <div class="loaderdetailworks"><center>Please Wait...</center></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-b" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Our Services Carousel -->
        <style>
            
        </style>
        <section class="" id="services">
            <div class="container">
                <div class="heading-text heading-section  text-center text-center">
                    <h2>Our Services</h2>
                </div>
                <div class="carousel arrows-visibile testimonial testimonial-single testimonial-left" data-items="1" data-autoplay="true" data-loop="true" data-autoplay-timeout="3500">
                    <?PHP
                    $qServ  = "
                            select final.*, s.title, s.picture, s.headline, (case when parentid=0 then 0 else (select menu from menu_site where id_menu=final.parent) end) parent_name from (
                                select a.*, (select count(*) from menu_site where parent=a.id_menu) as jmlsub,
                                (select parent from menu_site where id_menu=a.parent) parentid
                                from menu_site a where parent!=0 and style='services'
                            ) as final 
                            left join services s
                            on final.id_menu=s.id_menu
                            where jmlsub<1
                            order by parent,sort
                            ";
                    $gServ  = $this->query->getDatabyQ($qServ);
                    foreach($gServ as $dataserv) {
                        if ($dataserv['parent_name']=='0') { $titleserv = $dataserv['menu']; } else { $titleserv = $dataserv['parent_name'].' - '.$dataserv['menu']; }
                    ?>
                    <!-- Item -->
                    <div class="testimonial-item">
                        <div class="row">
                            <div class="col-md-6 ">
                                <h3><?PHP echo $titleserv; ?></h3>
                                <p><?PHP echo $dataserv['headline']; ?>...</p>
                                <a href="<?PHP echo base_url().'page/'.$dataserv['link']; ?>" class="btn btn-inverted">Read More</a>
                            </div>
                            <div class="col-md-6">
                                <img src="<?PHP echo base_url(); ?>images/content/<?PHP echo $dataserv['picture']; ?>" alt="">
                            </div>
                        </div>
                    </div>
                    <!-- end: Item-->
                    <?PHP } ?>

                </div>
            </div>
        </section>
        <!-- end: Our Services Carousel -->
		 <!-- Our About -->
		<?PHP  
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
					where id_menu='2'
					"; 
		$gPage      = $this->query->getDatabyQ($qPage);
		$dataPage   = array_shift($gPage);
		?> 
        <section class="" id="services">
            <div class="container">
                <div class="heading-text heading-section  text-center text-center">
                    <h2>ABOUT US</h2>
                </div>
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
        <!-- end: Our About --> 
		<section class="" id="services">
        <div class="container">
			<div class="row">
				<div class="col-lg-6">
					<h3 class=" text-uppercase">Get In Touch</h3>
					<p class="">Please contact us via the form below or at the contact details provided for further information about our business and services.</p>
					<div class="m-t-30">
						<form class="widget-contact-form" action="<?PHP echo base_url(); ?>core/insertinbox" role="form" method="post">
							<div class="row">
								<div class="form-group col-md-6">
									<label class="" for="name">Nama</label>
									<input type="text" aria-required="true" name="widget-contact-form-name" class="form-control required name" placeholder="Enter your Name">
								</div>
								<div class="form-group col-md-6">
									<label class="" for="email">Email</label>
									<input type="email" aria-required="true" name="widget-contact-form-email" class="form-control required email" placeholder="Enter your Email">
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12">
									<label class="" for="subject">Judul Pesan</label>
									<input type="text" name="widget-contact-form-subject" class="form-control required" placeholder="Subject...">
								</div>
							</div>
							<div class="form-group">
								<label class="" for="message">Pesan</label>
								<textarea type="text" name="widget-contact-form-message" rows="5" class="form-control required" placeholder="Enter your Message"></textarea>
							</div>

							<button class="btn" type="submit" id="form-submit"><i class="fa fa-paper-plane"></i>&nbsp;Kirim Pesan</button>
						</form>

					</div>
				</div>
				<div class="col-lg-6">
					<h3 class="text-uppercase ">Address & Map</h3>
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