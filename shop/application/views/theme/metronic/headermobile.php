<style>
	@media (max-width: 1024px) {
		.kt-header-mobile .kt-header-mobile__toolbar .kt-header-mobile__topbar-toggler i {
		    color: rgba(47, 47, 47)!important;
		}
	}
	@font-face {
	  font-family: '<?PHP echo base_url(); ?>fonts/AmstirdamDemo';
	  src: url('AmstirdamDemo.ttf')  format('truetype'), /* Safari, Android, iOS */
	}
	.linklogo {
		/*font-family: 'AmstirdamDemo', Fallback, sans-serif;*/
	}
</style>
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
	<div class="kt-header-mobile__logo">
		<a href="<?PHP echo base_url(); ?>" class="text-dark linklogo">
			Numura.id
		</a>
	</div>
	<div class="kt-header-mobile__toolbar">
		<button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler"><span></span></button>
		<!-- <button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button> -->
		<button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i class="flaticon-grid-menu"></i></button>
	</div>
</div>