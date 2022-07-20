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
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/datatables/fnReloadAjax.js"></script>

<!-- Select2 js -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/select2/select2.min.js" type="text/javascript"></script>
<!-- MultiSelect js -->

<script src="<?PHP echo base_url(); ?>assets/panel/plugins/multiselect/js/jquery.multi-select.js" type="text/javascript"></script>
<!-- Modal js -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/modals/classie.js" type="text/javascript"></script>
<!-- iCheck js -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/icheck/icheck.min.js" type="text/javascript"></script>
<!-- bootstrap-wysihtml5 js -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/bootstrap-wysihtml5/wysihtml5.js" type="text/javascript"></script>
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js" type="text/javascript"></script>
<!-- gallery -->
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/gallery/baguetteBox.min.js"></script>
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?PHP echo base_url(); ?>assets/panel/plugins/datetimepicker/js/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<!-- End Page Lavel Plugins
=====================================================================-->

<!-- Start Theme label Script
=====================================================================-->
<!-- Dashboard js -->
<script src="<?PHP echo base_url(); ?>assets/panel/dist/js/dashboard.js" type="text/javascript"></script>
<!--script src="<?PHP echo base_url(); ?>assets/panel/datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script-->
<!-- End Theme label Script
=====================================================================-->
<style>
	tfoot {
		display: table-header-group;
	}
	tfoot input {
		width:100%;
	}
	.close {
		color:#fff!important;
	}
</style>
<script>
	$(document).ready(function () {
		
		$('.month_year').datetimepicker({
			format: 'yyyy-mm-dd hh:ii:ss',
			autoclose:true
		});
		
		$('.month_yearonly').datepicker({
			//format: 'yyyy-mm',
			minViewMode: 1,
			format: 'yyyy-mm',			
			autoclose:true
		});
		$('.map_yearonly').datepicker({
			//format: 'yyyy-mm',
			minViewMode: 1,
			format: 'yyyy-mm',			
			autoclose:true
		});
		$('.bulan_mapping').datepicker({
			format: 'mm-yyyy',
			autoClose:true
		});
		$('.bulan_mappingym').datepicker({
			format: 'yyyy-mm',
			autoClose:true
		});
		$('.ymd_mapping').datepicker({
			format: 'yyyy-mm-dd',
			autoClose:true
		});
		$('.tes').datepicker({
			 changeMonth: true,
			 changeYear: true,
			 dateFormat: 'mm-yy',

			 onClose: function() {
				var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(iYear, iMonth, 1));
			 },

			 beforeShow: function() {
			   if ((selDate = $(this).val()).length > 0) 
			   {
				  iYear = selDate.substring(selDate.length - 4, selDate.length);
				  iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5), 
						   $(this).datepicker('option', 'monthNames'));
				  $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
				  $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
			   }
			}
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
		
		// setInterval(function() {
		// $('#topmsg').load('<?PHP echo base_url(); ?>core/notifmsg').click("");
		// }, 3000); // the "3000" here refers to the time to refresh the div.  it is in milliseconds. 
		
		// setInterval(function() {
		// $('#topnotif').load('<?PHP echo base_url(); ?>core/notification').click("");
		// }, 3000); // the "3000" here refers to the time to refresh the div.  it is in milliseconds. 
		
		// setInterval(function() {
		// $('#updatetocancel').load('<?PHP echo base_url(); ?>core/updatetocancel').click("");
		// }, 3000); // the "3000" here refers to the time to refresh the div.  it is in milliseconds. 
		
		$('#tblcat').dataTable({
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatacat",
			"columnDefs":[ {
						"targets": 0, "searchable": true,
					},{
						"targets": 1, "searchable": true,
					},{
						"targets": 2, "searchable": true,
					}]
		});
		
		var tbldivisi = $('#tbldivisi').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatadivisi",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tbldivisi.on( 'order.dt search.dt', function () {
			tbldivisi.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tbldivisi tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tbldivisi.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
	
		var tblwitel = $('#tblwitel').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatawitel",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblwitel.on( 'order.dt search.dt', function () {
			tblwitel.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblwitel tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblwitel.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		var tblsegmen = $('#tblsegmen').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatasegmen",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblsegmen.on( 'order.dt search.dt', function () {
			tblsegmen.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblsegmen tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblsegmen.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		var tblorder = $('#tblorder').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdataorder",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblorder.on( 'order.dt search.dt', function () {
			tblorder.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblorder tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblorder.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		var tblsr = $('#tblsr').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>lop/getdatasr",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblsr.on( 'order.dt search.dt', function () {
			tblsr.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblsr tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblsr.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		
		var tblpemenang = $('#tblpemenang').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatapemenang",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblpemenang.on( 'order.dt search.dt', function () {
			tblpemenang.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblpemenang tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblpemenang.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		var tblpmlop = $('#tblpmlop').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>pm/getdatapmlop",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblpmlop.on( 'order.dt search.dt', function () {
			tblpmlop.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblpmlop tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblpmlop.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		var tblpenyedia = $('#tblpenyedia').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatapenyedia",
		});
		$('#tblpenyedia tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblpenyedia.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
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
		
		var tblroles = $('#tblroles').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdataroles",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblroles.on( 'order.dt search.dt', function () {
			tblroles.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblroles tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblroles.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		var tbluser = $('#tbluser').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatauser",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tbluser.on( 'order.dt search.dt', function () {
			tbluser.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tbluser tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='pt' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tbluser.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
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
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>dashboard/dataexcel",
		});
		
		var tblop = $('#tbllop_new').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"processing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatalopv2",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"language": {
				"decimal": ",",
				"thousands": "."
			},
			"order": [[ 9, 'desc' ]]
		});
		tblop.on( 'order.dt search.dt', function () {
			tblop.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$(document).on('click', '#filter_lop', function(e){
			var divisi 	= $("#divisi").val();
			var segmen 	= $("#segment").val();
			var treg 	= $("#treg").val();
			var witel 	= $("#witel").val();
			var am 		= $("#am").val();
			var newUrl = "<?PHP echo base_url(); ?>core/getdatalopv2?divisi="+divisi+"&segmen="+segmen+"&treg="+treg+"&witel="+witel+"&am="+am;
			//alert(newUrl);
			tblop.ajax.url(newUrl).load();
			//tblop.fnReloadAjax(newUrl);
		});	
		
		$('#tbllop_new tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblop.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		$('#lop_serverside tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" class="btn-filter" name="'+title+'" id="'+title+'" placeholder="Search" />' );				
			}
		} );	
		
		var tblop2 = $('#lop_serverside').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"processing": true,
			"serverSide": true,
			"scrollX": true,
			"ajax": {
				"url": "<?PHP echo base_url();?>lop/getDataLop",
				"type": "POST"
			},
			"language": {
				"decimal": ",",
				"thousands": "."
			},	
			"columnDefs": [ {
								"searchable": false,
								"orderable": false,
								"targets": 0,
								"orderable": false
							},
							{
								"targets": 16,
								"orderable": false
							},
							{
								"targets": 25,
								"orderable": false
							},
							{
								"targets":27, 
								"orderable": false
							},
							{
								"targets":10, 
								render:$.fn.dataTable.render.number( ',', '.', 0 ),
								"orderable": true
							},
							
							{
								"targets":11, 
								render:$.fn.dataTable.render.number( ',', '.', 0 )
							}
							,{
								"targets":12, 
								render:$.fn.dataTable.render.number( ',', '.', 0 )
							},{
								"targets":13, 
								render:$.fn.dataTable.render.number( ',', '.', 0 )
							}
							
			],
			"order": [[ 1, 'desc' ]],
			"initComplete": function(settings, json) {
				$( ".dataTables_scrollFoot" ).insertBefore( ".dataTables_scrollBody" );
			}
		});
		tblop2.on( 'order.dt search.dt', function () {
			tblop2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		
		tblop2.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		// tblop =  $('#tbllop').DataTable( {
			// processing:true,
			// serverSide: true,
			// "ajax": {
				// "url": "<?PHP echo base_url(); ?>core/filter_lop2",
				// "type": "POST",
				// "data":function(data) {
					// data.s_segment = $('#s_segment').val();
					// data.s_treg = $('#s_treg').val();
					// data.s_witel = $('#s_witel').val();
					// data.s_am = $('#s_am').val();
				// }
			// },
			
		// } );
		// $('#filter_lop').on( 'click change', function (event) {
			// event.preventDefault();
			// tblop.draw();
		// } );
		// $('#tbllo').dataTable({
			// "bProcessing": true,
			// "sAjaxSource": "<?PHP echo base_url(); ?>core/getdatalo",
		// });
		
		// tblo =  $('#tbllo').DataTable( {
			// processing:true,
			// "searching": false,
			// "bLengthChange": false,
			// serverSide: true,
			// "ajax": {
				// "url": "<?PHP echo base_url(); ?>core/filter_lo2",
				// "type": "POST",
				// "data":function(data) {
					// data.s_segment = $('#s_segment').val();
					// data.s_treg = $('#s_treg').val();
					// data.s_witel = $('#s_witel').val();
					// data.s_am = $('#s_am').val();
				// }
			// },
			
		// } );
		// $('#filter_lo').on( 'click change', function (event) {
			// event.preventDefault();
			// tblo.draw();
		// } );
		var tblo = $('#tbllo').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"processing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatalo",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"language": {
				"decimal": ",",
				"thousands": "."
			},
			"order": [[ 6, 'desc' ]]
		});
		tblo.on( 'order.dt search.dt', function () {
			tblo.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$(document).on('click', '#filter_lo', function(e){
			var divisi 	= $('#divisi').val();
			var segmen 	= $('#segment').val();
			var treg 	= $('#treg').val();
			var witel 	= $('#witel').val();
			var am 		= $('#am').val();
			var newUrl = "<?PHP echo base_url(); ?>core/getdatalo?divisi="+divisi+"&segmen="+segmen+"&treg="+treg+"&witel="+witel+"&am="+am;
			//alert(newUrl);
			//tblo.fnReloadAjax(newUrl);
			tblo.ajax.url(newUrl).load();
		});
		$('#tbllo tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblo.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		var tblo2 = $('#tblo2').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?PHP echo base_url();?>lo/getDatalo",
				"type": "POST"
			},
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			},{
				"targets":7, 
				render:$.fn.dataTable.render.number( ',', '.', 0 ),
				"orderable": true
			}],
			"language": {
				"decimal": ",",
				"thousands": "."
			},
			"order": [[ 6, 'desc' ]]
		});
		tblo2.on( 'order.dt search.dt', function () {
			tblo2.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$(document).on('click', '#filter_lo', function(e){
			var divisi 	= $('#divisi').val();
			var segmen 	= $('#segment').val();
			var treg 	= $('#treg').val();
			var witel 	= $('#witel').val();
			var am 		= $('#am').val();
			var newUrl = "<?PHP echo base_url(); ?>core/getdatalo?divisi="+divisi+"&segmen="+segmen+"&treg="+treg+"&witel="+witel+"&am="+am;
			//alert(newUrl);
			//tblo.fnReloadAjax(newUrl);
			tblo2.ajax.url(newUrl).load();
		});
		$('#tblo2 tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" class="btn-filter" name="'+title+'" id="'+title+'" placeholder="Search" />' );				
			}
		} );
		tblo2.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		var tblam = $('#tblam').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdataam",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblam.on( 'order.dt search.dt', function () {
			tblam.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblam tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblam.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		var tblproduktivitas = $('#tblproduktivitas').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdataproduktivitasM",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ]
		});
		tblproduktivitas.on( 'order.dt search.dt', function () {
			tblproduktivitas.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblproduktivitas tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblproduktivitas.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		
		var tblpots = $('#tblpots').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatapots",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ]
		});
		tblpots.on( 'order.dt search.dt', function () {
			tblpots.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblpots tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblpots.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		var tblpots_rev = $('#tblpots_rev').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatapots_rev",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ]
		});
		tblpots_rev.on( 'order.dt search.dt', function () {
			tblpots_rev.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblpots_rev tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblpots_rev.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		var tblproduktivitasOVV = $('#tblproduktivitasOVV').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdataproduktivitasMOVV",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ]
		});
		tblproduktivitasOVV.on( 'order.dt search.dt', function () {
			tblproduktivitasOVV.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblproduktivitasOVV tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblproduktivitasOVV.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		var tblmap = $('#tblmap').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatamapv2",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblmap.on( 'order.dt search.dt', function () {
			tblmap.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblmap tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblmap.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		$(document).on('click', '#filter_map', function(e){
			var divisi 	= $("#divisi").val();
			var segmen 	= $("#segment").val();
			var treg 	= $("#treg").val();
			var witel 	= $("#witel").val();
			var am 		= $("#am").val();
			var newUrl = "<?PHP echo base_url(); ?>core/getdatamapv2?divisi="+divisi+"&segmen="+segmen+"&treg="+treg+"&witel="+witel+"&am="+am;
			//alert(newUrl);
			tblmap.ajax.url(newUrl).load();
			//tblop.fnReloadAjax(newUrl);
		});
		var tblgc = $('#tblgc').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatagc",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblgc.on( 'order.dt search.dt', function () {
			tblgc.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblgc tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblgc.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		
		var tbllt = $('#tbllt').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>lop/getdatalt",
			"columnDefs": [ 
			{
			   'targets': 0,
			   'searchable':false,
			   'orderable':false,
			   'className': 'dt-body-center',
			   'render': function (data, type, full, meta){
				   return '<input type="checkbox" name="id[]" value="' + $('<div/>').text(data).html() + '">';
			   }
			},
			{
				"orderable": true,
				"targets": 1
			},{
				"targets":4, 
				render:$.fn.dataTable.render.number( ',', '.', 0 ),
				"orderable": true
			} ],
			"select": {
				style: 'os',
				selector: 'td:first-child'
			},
			"order": [[ 1, "asc" ]]
		});
		// Handle click on "Select all" control
		$('#tbllt .select_all').on('click', function(){
			var rows = tbllt.rows({ 'search': 'applied' }).nodes();
			$('input[type="checkbox"]', rows).prop('checked', this.checked);
		});
		tbllt.on( 'order.dt search.dt', function () {
			tbllt.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tbllt tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tbllt.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		$('#tbllt tbody').on('change', 'input[type="checkbox"]', function(){
		   if(!this.checked){
			  var el = $('#tbllt .select_all').get(0);
			  if(el && el.checked && ('indeterminate' in el)){
				 el.indeterminate = true;
			  }
		   }
		});
		$('#frm-del').on('submit', function(e){
		  var form = this;
		  // Iterate over all checkboxes in the table
		  tbllt.$('input[type="checkbox"]').each(function(){
			 // If checkbox doesn't exist in DOM
			 if(!$.contains(document, this)){
				// If checkbox is checked
				if(this.checked){
				   // Create a hidden element 
				   $(form).append(
					  $('<input>')
						 .attr('type', 'hidden')
						 .attr('name', this.name)
						 .val(this.value)
				   );
				}
			 } 
		  });
		  var data = $(form).serialize(); 
			  $.ajax({
				url: '<?PHP echo base_url(); ?>lop/delall',
				type: 'POST',
				data: data
			})
			.done(function(data){
				setTimeout(function(){
					tbllt.ajax.reload();
				} , 1000);
			})
			.fail(function(){
				
			});
		  // Prevent actual form submission
		  e.preventDefault();
		});
		var tblmail = $('#tblmail').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>lop/getdatamail",
		});
		tblmail.on( 'order.dt search.dt', function () {
			tblmail.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblmail tfoot th').each( function () {
			var title = $(this).text();
			if(title=='Remove' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblmail.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		// $('#tblmap').dataTable({
			// "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			// "bProcessing": true,
			// "sAjaxSource": "<?PHP echo base_url(); ?>core/getdatamap",
		// });
		
		var tblsubsidaries = $('#tblsubsidaries').DataTable({
			"lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			"bProcessing": true,
			"sAjaxSource": "<?PHP echo base_url(); ?>core/getdatasubsidaries",
			"columnDefs": [ {
				"searchable": false,
				"orderable": false,
				"targets": 0
			} ],
			"order": [[ 1, 'asc' ]]
		});
		tblsubsidaries.on( 'order.dt search.dt', function () {
			tblsubsidaries.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		} ).draw();
		$('#tblsubsidaries tfoot th').each( function () {
			var title = $(this).text();
			if(title=='act' || title=='No'){
				$(this).html('');				
			}else{
				$(this).html( '<input type="text" placeholder="" />' );				
			}
		} );
		tblsubsidaries.columns().every( function () {
			 var that = this;
			 $( 'input', this.footer() ).on( 'keyup change', function () {
				 if ( that.search() !== this.value ) {
					 that
						 .search( this.value )
						 .draw();
				 }
			 } );
		} );
		// $('#tbllog').dataTable({
			// "lengthMenu": [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],
			// "bProcessing": true,
			// "sAjaxSource": "<?PHP echo base_url(); ?>core/getdatalog",
		// });
		
		$('.skin-square .i-check input').iCheck({
			checkboxClass: 'icheckbox_square-red',
			radioClass: 'iradio_square-red'
		});
		$('.selectnorm').select2();
		$('.select_map').select2();
		$('.select_nipnas').select2();
		$('.select_nipnas2').select2();
		$('.select_am').select2();
		$('.select_am2').select2();
		$('.select_lo').select2();
		$('.select_lo2').select2();
		
		$('.multipleselect2').select2();

		$(".select_loajax").select2({
			closeOnSelect:true,
			ajax: {
				url: '<?PHP echo base_url(); ?>core/getdataloselect2',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						q: params.term, // search term
						page: params.page
					};
				},
				processResults: function (data, params) {
					// parse the results into the format expected by Select2
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data, except to indicate that infinite
					// scrolling can be used
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			},
			placeholder: 'Search ID LO',
			escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			minimumInputLength: 3,
			templateResult: formatRepo,
			templateSelection: formatRepoSelection
		});

		$(".multipleselect2ajax").select2({
			closeOnSelect:true,
			ajax: {
				url: '<?PHP echo base_url(); ?>core/getdatasustain',
				dataType: 'json',
				delay: 250,
				data: function (params) {
					return {
						q: params.term, // search term
						page: params.page
					};
				},
				processResults: function (data, params) {
					// parse the results into the format expected by Select2
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data, except to indicate that infinite
					// scrolling can be used
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			},
			placeholder: 'Search LOP',
			escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
			minimumInputLength: 3,
			templateResult: formatRepo,
			templateSelection: formatRepoSelection
		});
		
		function formatRepo (repo) {
		  if (repo.loading) {
			return repo.text;
		  }

		  var markup = "<div>"+ repo.id +" - " + repo.text + "</div>";

		  return markup;
		}

		function formatRepoSelection (repo) {
		  return repo.id +' - '+ repo.text;
		}
		
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