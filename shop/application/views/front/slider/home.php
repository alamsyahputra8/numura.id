<div class="slider-available-sec">
	<!-- Main Slider -->
	<section id="main-slider">
		<?PHP 
		$getSlide = $this->query->getData('banner','*',"order by id_banner Desc");
		foreach($getSlide as $slide) { 
		?>
		<div class="items">
			<div class="img-container" data-bg-img="<?PHP echo base_url(); ?>images/slider/<?PHP echo $slide['img']; ?>"></div>
			<!-- Change the URL section based on your image\'s name -->
			<div class="slide-caption">
				<div class="inner-container clearfix">
					<div class="up-sec"><?PHP echo $slide['sub']; ?></div>
					<div class="down-sec"><?PHP echo $slide['title']; ?></div>
				</div>
			</div>
		</div>
		<?PHP } ?>
	</section>
	<!-- End of Main Slider -->

	<?PHP $this->load->view('front/booking/home'); ?>
</div>