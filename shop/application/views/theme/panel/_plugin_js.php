	<div id="progresloader" style="display: none;"></div>
	<!--footer class="main-footer">
		<div><strong>Supported by Parwatha - All rights reserved &copy; <?PHP echo date('Y'); ?></div>
	</footer-->
</div>

<!-- Start Core Plugins
=====================================================================-->
<!-- Bootstrap -->
<script src="<?PHP echo base_url(); ?>assets/panel/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- lobipanel -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/lobipanel/lobipanel.min.js" type="text/javascript"></script>
<!-- Pace js -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/pace/pace.min.js" type="text/javascript"></script>
<!-- SlimScroll -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/fastclick/fastclick.min.js" type="text/javascript"></script>
<!-- AdminBD frame -->
<script src="<?PHP echo base_url(); ?>assets/panel/dist/js/frame.js" type="text/javascript"></script>
<!-- End Core Plugins
=====================================================================-->

<!-- Start Page Lavel Plugins
=====================================================================-->
<!-- dataTables js -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/datatables/dataTables.min.js" type="text/javascript"></script>
<!-- Modal js -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/modals/classie.js" type="text/javascript"></script>
<!-- iCheck js -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/icheck/icheck.min.js" type="text/javascript"></script>
<!-- bootstrap-wysihtml5 js -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/bootstrap-wysihtml5/wysihtml5.js" type="text/javascript"></script>
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
<!-- gallery -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/gallery/baguetteBox.min.js"></script>
<!-- End Page Lavel Plugins
=====================================================================-->

<!-- Start Theme label Script
=====================================================================-->
<!-- Dashboard js -->
<script src="<?PHP echo base_url(); ?>assets/panel/dist/js/dashboard.js" type="text/javascript"></script>
<!--script src="<?PHP echo base_url(); ?>assets/panel/datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script-->
<!-- End Theme label Script
=====================================================================-->
<script>
	$(document).ready(function () {
		
		$('.month_year').datepicker({
			format: 'dd-mm-yyyy',
			autoClose:true
		});
		
		$('.datepicker').datepicker({
			dateFormat: 'yy-mm-dd',
			autoClose:true
		});
		
		baguetteBox.run('.tz-gallery');
		
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
		

		"use strict"; // Start of use strict
		
		$('.some-textarea').wysihtml5();
		
		$.ajaxSetup({ cache: false }); // This part addresses an IE bug.  without it, IE will only load the first number and will never refresh
		setInterval(function() {
		$('#topmsg').load('<?PHP echo base_url(); ?>core/notifmsg').click("");
		}, 3000); // the "3000" here refers to the time to refresh the div.  it is in milliseconds. 
		
		setInterval(function() {
		$('#topnotif').load('<?PHP echo base_url(); ?>core/notification').click("");
		}, 3000); // the "3000" here refers to the time to refresh the div.  it is in milliseconds. 
		
		setInterval(function() {
		$('#updatetocancel').load('<?PHP echo base_url(); ?>core/updatetocancel').click("");
		}, 3000); // the "3000" here refers to the time to refresh the div.  it is in milliseconds. 
		
		$('#tblcat').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatacat",
		});
		
		$('#tblfac').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatafac",
		});
		
		$('#tblpromo').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatapromo",
		});
		
		$('#tblmenu').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatamenu",
		});
		
		$('#tbltesti').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatatesti",
		});
		
		$('#tblreserv').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatareserv",
		});
		
		$('#tblpayment').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatapayment",
		});
		
		$('#tblrating').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatarating",
		});
		
		$('#tblroom').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdataroom",
		});
		
		$('#tblevent').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdataevent",
		});
		
		$('#tblroles').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdataroles",
		});
		
		$('#tbluser').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatauser",
		});
		
		$('#tblmember').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatamember",
		});
		
		$('#tblmailsite').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatamailsite",
		});
		
		$('#tblsubs').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatasubs",
		});
		
		$('#tblalbum').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdataalbum",
		});

		// Message
		$('.message_inner').slimScroll({
			size: '3px',
			height: '320px'
			// position: 'left'
		});
		
		$('#tblexcel').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>dashboard/dataexcel",
		});
		
		$('.skin-square .i-check input').iCheck({
			checkboxClass: 'icheckbox_square-red',
			radioClass: 'iradio_square-red'
		});
		
		var checkLDAP = $('input.ldap');
		var checkAll = $('input.all');
		var checkAll2 = $('input.all2');
		var checkboxes = $('input.checkItem');
		var checkboxes2 = $('input.checkItem2');
		
		checkAll.on('ifChecked ifUnchecked', function(event) {        
			if (event.type == 'ifChecked') {
				checkboxes.iCheck('check');
			} else {
				checkboxes.iCheck('uncheck');
			}
		});
		
		checkLDAP.on('ifChecked ifUnchecked', function(event) {        
			if (event.type == 'ifChecked') {
				$('#nonldap').hide();
				// $('#usingldap').show();
			} else {
				$('#nonldap').show();
				// $('#usingldap').hide();
			}
		});
		
		checkboxes.on('ifChanged', function(event){
			if(checkboxes.filter(':checked').length == checkboxes.length) {
				checkAll.prop('checked', 'checked');
			} else {
				checkAll.removeProp('checked');
			}
			checkAll.iCheck('update');
		});
		
		checkAll2.on('ifChecked ifUnchecked', function(event) {        
			if (event.type == 'ifChecked') {
				checkboxes2.iCheck('check');
			} else {
				checkboxes2.iCheck('uncheck');
			}
		});
		
		checkboxes2.on('ifChanged', function(event){
			if(checkboxes2.filter(':checked').length == checkboxes2.length) {
				checkAll2.prop('checked', 'checked');
			} else {
				checkAll2.removeProp('checked');
			}
			checkAll2.iCheck('update');
		});
	});
</script>
</body>
</html>