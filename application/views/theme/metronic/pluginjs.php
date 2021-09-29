<?PHP
$userdata		= $this->session->userdata('sesspwt'); 
$userid 		= $userdata['userid'];
?>

		<!-- begin:Quick Panel -->
		<?PHP $this->load->view('panel/quickpanel'); ?>
		<!-- end::Quick Panel -->

		<!-- begin::Scrolltop -->
		<div id="kt_scrolltop" class="kt-scrolltop">
			<i class="fa fa-arrow-up"></i>
		</div>

		<!-- end::Scrolltop -->

		<!-- begin::Sticky Toolbar -->
		<ul class="kt-sticky-toolbar" style="margin-top: 30px; display: none;">
			<li class="kt-sticky-toolbar__item kt-sticky-toolbar__item--success" id="kt_demo_panel_toggle" data-toggle="kt-tooltip" title="Check out more demos" data-placement="right">
				<a href="#" class=""><i class="flaticon2-drop"></i></a>
			</li>
			<li class="kt-sticky-toolbar__item kt-sticky-toolbar__item--brand" data-toggle="kt-tooltip" title="Layout Builder" data-placement="left">
				<a href="https://keenthemes.com/metronic/preview/default/builder.html" target="_blank"><i class="flaticon2-gear"></i></a>
			</li>
			<li class="kt-sticky-toolbar__item kt-sticky-toolbar__item--warning" data-toggle="kt-tooltip" title="Documentation" data-placement="left">
				<a href="https://keenthemes.com/metronic/?page=docs" target="_blank"><i class="flaticon2-telegram-logo"></i></a>
			</li>
		</ul>

		<!-- end::Sticky Toolbar -->

		<!-- begin::Demo Panel -->
		<div id="kt_demo_panel" class="kt-demo-panel" style="display: none;">
			<div class="kt-demo-panel__head">
				<h3 class="kt-demo-panel__title">
					Select A Demo

					<!--<small>5</small>-->
				</h3>
				<a href="#" class="kt-demo-panel__close" id="kt_demo_panel_close"><i class="flaticon2-delete"></i></a>
			</div>
			<div class="kt-demo-panel__body">
				<div class="kt-demo-panel__item kt-demo-panel__item--active">
					<div class="kt-demo-panel__item-title">
						Default
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-_Default.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../default/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 2
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-2.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo2/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 3
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-3.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo3/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 4
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-4.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo4/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 5
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-5.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo5/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 6
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-6.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo6/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 7
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-7.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo7/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 8
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-8.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo8/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 9
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-9.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo9/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 10
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-10.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo10/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 11
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-11.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo11/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 12
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-12.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="../demo12/index.html" class="btn btn-brand btn-elevate " target="_blank">Preview</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 13
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-13.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="#" class="btn btn-brand btn-elevate disabled">Coming soon</a>
						</div>
					</div>
				</div>
				<div class="kt-demo-panel__item ">
					<div class="kt-demo-panel__item-title">
						Demo 14
					</div>
					<div class="kt-demo-panel__item-preview">
						<img src="assets/media/demos/Demo-14.jpg" alt="" />
						<div class="kt-demo-panel__item-preview-overlay">
							<a href="#" class="btn btn-brand btn-elevate disabled">Coming soon</a>
						</div>
					</div>
				</div>
				<a href="" target="_blank" class="kt-demo-panel__purchase btn btn-brand btn-elevate btn-bold btn-upper">
					Buy Metronic Now!
				</a>
			</div>
		</div>

		<!-- end::Demo Panel -->

		<!-- begin::Global Config(global config for global JS sciprts) -->
		<script>
			var KTAppOptions = {
				"colors": {
					"state": {
						"brand": "#5d78ff",
						"dark": "#282a3c",
						"light": "#ffffff",
						"primary": "#5867dd",
						"success": "#34bfa3",
						"info": "#36a3f7",
						"warning": "#ffb822",
						"danger": "#fd3995"
					},
					"base": {
						"label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
						"shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
					}
				}
			};
		</script>

		<!-- end::Global Config -->
		<link href="<?PHP echo base_url(); ?>assets/theme/assets/app/custom/invoices/invoice-v1.default.css" rel="stylesheet" type="text/css" />

		<!--begin::Global Theme Bundle(used by all pages) -->
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>

		<!--end::Global Theme Bundle -->

		<!--begin::Page Vendors(used by this page) -->
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/vendors/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/vendors/custom/fullcalendar/fullcalendar.bundle.js" type="text/javascript"></script>
		<script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM" type="text/javascript"></script>
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/vendors/custom/gmaps/gmaps.js" type="text/javascript"></script>

		<script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
		<script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>

		<!--end::Page Vendors -->

		<!--begin::Page Scripts(used by this page) -->
		<?PHP
		$activepage	= $this->uri->uri_string();
		if ($activepage!='' and $activepage!='panel' and $activepage!='panel/userprofile') {
			$q 			= "select pluginjs from menu where url='$activepage'";
			$getMenu 	= $this->query->getDatabyQ($q);
			$dataMenu	= array_shift($getMenu);
			//$pluginjs	= '<script src="'.base_url().'assets/metronic/app/base/'.$dataMenu['pluginjs'].'" type="text/javascript"></script>';
			if ($dataMenu['pluginjs']=='') {
				$urlmenuact	= $this->uri->uri_string();
				if (strpos($urlmenuact, "docdetail")!== false) { $pluginjs 	= 'docdetailjs'; }
				else if (strpos($urlmenuact, "docanswerdetail")!== false) { $pluginjs 	= 'docansdetailjs'; }
				else if (strpos($urlmenuact, "akun")!== false) { $pluginjs 	= 'detailakunjs'; }
				else if (strpos($urlmenuact, "detailpengiriman")!== false) { $pluginjs 	= 'detailpengirimanjs'; }
				else { $pluginjs 	= ''; }
			} else {
				$pluginjs 	= $dataMenu['pluginjs'];
			}
			$this->load->view('theme/metronic/plugin/'.$pluginjs.'');
		} else if ($activepage=='panel/userprofile') {
			//echo '';
			$this->load->view('theme/metronic/plugin/userprofilejs');
		} else {
			//$pluginjs	= '<script src="'.base_url().'assets/metronic/app/base/dashboard.js" type="text/javascript"></script>';
			$this->load->view('theme/metronic/plugin/dashboard');
		}
		?>
		<!--end::Page Scripts -->

		<script>
			$(document).ready(function(){
				function loadnotif(){
					var uid = '<?PHP echo $userid; ?>';
				 	$.ajax({
				        url: '<?PHP echo base_url(); ?>notify/getjml',
				        type: 'POST',
				        data: 'id='+uid,
				        dataType: 'json'
				    })
				    .done(function(data){
				    	var jmldata = data.jmldata;
				    	if(jmldata>0) {
				    		$('#labelnotify').fadeIn('fast');
				    		$('#alertnotif').fadeIn('fast');
					        $('#valuenotif').fadeIn('fast');
					        $('#labelnotify').html(''+jmldata+'');
					        $('#valuenotif').html(''+jmldata+' new');
					        // $( "#bgnotifications" ).load( "<?PHP echo base_url(); ?>notify/detail", function() {});
				    	} else {
				    		$('#labelnotify').fadeOut('fast');
				    		$('#labelnotify').html('');
				    		$('#alertnotif').fadeOut('fast');
					        $('#valuenotif').fadeOut('fast');
					        // $('#bgnotifications').html(`
					        // 	<div class="kt-grid kt-grid--ver col-sm-12" style="min-height: 200px;">
						       //      <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
						       //          <div class="kt-grid__item kt-grid__item--middle kt-align-center">
						       //              All caught up!
						       //              <br>No new notifications.
						       //          </div>
						       //      </div>
						       //  </div>
					        // `);
				    	}
				    })
				    .fail(function(){
				    	$('#labelnotify').fadeOut('fast');
				    	$('#labelnotify').html('');
				        $('#alertnotif').fadeOut('fast');
				        $('#valuenotif').fadeOut('fast');
				        // $('#bgnotifications').html(`
				        // 	<div class="kt-grid kt-grid--ver col-sm-12" style="min-height: 200px;">
					       //      <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
					       //          <div class="kt-grid__item kt-grid__item--middle kt-align-center">
					       //              All caught up!
					       //              <br>No new notifications.
					       //          </div>
					       //      </div>
					       //  </div>
				        // `);
				    });	
				}

				function loaddetailnotif(){
					var uid = '<?PHP echo $userid; ?>';
				 	$.ajax({
				        url: '<?PHP echo base_url(); ?>notify/getjml',
				        type: 'POST',
				        data: 'id='+uid,
				        dataType: 'json',
				        beforeSend: function(){ 
		                   	KTApp.block('#bgnotif', {
		                        overlayColor: '#000000',
		                        type: 'v2',
		                        state: 'success',
		                        message: 'Please wait...'
		                   	});
		                },
				    })
				    .done(function(data){
				    	var jmldata = data.jmldata;
				    	KTApp.unblock('#bgnotif');

				    	if(jmldata>0) {
					        $( "#bgnotif" ).load( "<?PHP echo base_url(); ?>notify/detail", function() {});
				    	} else {
					        $('#bgnotif').html(`
					        	<div class="kt-grid kt-grid--ver col-sm-12" style="min-height: 200px;">
						            <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
						                <div class="kt-grid__item kt-grid__item--middle kt-align-center">
						                    All caught up!
						                    <br>No new notifications.
						                </div>
						            </div>
						        </div>
					        `);
				    	}
				    })
				    .fail(function(){
				    	$('#bgnotif').html(`
				        	<div class="kt-grid kt-grid--ver col-sm-12" style="min-height: 200px;">
					            <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
					                <div class="kt-grid__item kt-grid__item--middle kt-align-center">
					                    All caught up!
					                    <br>No new notifications.
					                </div>
					            </div>
					        </div>
				        `);
				    });	
				}

				// loadnotif();

				// $('#shownotif').click(function(){
				// 	loaddetailnotif();
				// });

				// setInterval(function(){
				// 	loadnotif();
				// }, 5000);
			});
		</script>

		<!--begin::Global App Bundle(used by all pages) -->
		<script src="<?PHP echo base_url(); ?>assets/theme/assets/app/bundle/app.bundle.js" type="text/javascript"></script>

		<!--end::Global App Bundle -->
	</body>

	<!-- end::Body -->
</html>