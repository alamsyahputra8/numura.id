<?PHP $this->load->view('/theme/metronic/plugin'); ?>
		<!-- begin:: Page -->

		<!-- begin:: Header Mobile -->
		<?PHP $this->load->view('/theme/metronic/headermobile'); ?>
		<!-- end:: Header Mobile -->

		<div class="kt-grid kt-grid--hor kt-grid--root">
			<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">

				<!-- begin:: Aside -->
				<?PHP $this->load->view('/theme/metronic/sidebar'); ?>
				<!-- end:: Aside -->
				
				<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">

					<!-- begin:: Header -->
					<?PHP $this->load->view('/theme/metronic/headerpage'); ?>
					<!-- end:: Header -->

					<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">

						<!-- begin:: Content Head -->
						<?PHP $this->load->view('/theme/metronic/contenthead'); ?>
						<!-- end:: Content Head -->

						<!-- begin:: Content -->