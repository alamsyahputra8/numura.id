<script>
"use strict";

$(document).on('click', '.btnupdateM', function(e){
    e.preventDefault();

    var uid = $(this).data('id'); // get id of clicked row

    $('#dynamic-content').hide(); // hide dive for loader
    $('#modal-loader').show();  // load ajax loader
    
    $.ajax({
        url: '<?PHP echo base_url(); ?>roles/modal',
        type: 'POST',
        data: 'id='+uid,
        dataType: 'json'
    })
    .done(function(data){
        console.log(data);
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        $('#nameroles').html(data.nama_role);
        $('#ed_roles').val(data.nama_role);
        $('#ed_role_description').val(data.desc_role);
        $('#ed_idroles').val(data.id_role);
        $('#exmod').load("<?PHP echo base_url(); ?>roles/getdataEksMod/"+data.id_role+"", function(){});
        $('#modal-loader').hide();    // hide ajax loader
    })
    .fail(function(){
        $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please refresh page...');
    });
});

$(document).on('click', '.btndeleteMenu', function(e){
    e.preventDefault();

    var id = $(this).data('id'); // get id of clicked row

    $('#dynamic-content').hide(); // hide dive for loader
    $('#modal-loader').show();  // load ajax loader

    $.ajax({
        url: '<?PHP echo base_url(); ?>roles/modal',
        type: 'POST',
        data: 'id='+id,
        dataType: 'json'
    })
    .done(function(data){
        // console.log(data);
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        $('#namedelroles').html(data.nama_role);
        $('#iddelroles').val(data.id_role);
        $('#modal-loader').hide();    // hide ajax loader
    })
    .fail(function(){
        $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
    });
});

var KTDatatablesDataSourceAjaxServer = function() {

    $.fn.dataTable.Api.register('column().title()', function() {
        return $(this.header()).text().trim();
    });

	var initTable1 = function() {
		//var table = $('#tabledata');

		var table = $('#tabledata').DataTable({
            responsive: true,

            // Pagination settings
            dom: `<'row'<'col-sm-12'tr>>
            <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            // read more: https://datatables.net/examples/basic_init/dom.html

            lengthMenu: [5, 10, 25, 50],

            pageLength: 10,

            language: {
                'lengthMenu': 'Display _MENU_',
                'emptyTable': `
                            <div class="row" style="padding: 20px;">
                                <div class="col-sm-12">
                                    <div><img src="<?PHP echo base_url(); ?>images/icon/notfound.png"></div><br>
                                    <h5 class="text-center">Anda Belum Memiliki Data Tersimpan Di Website Anda</h5>
                                    </h6>Silahkan buat data baru</h6><br>
                                </div>
                            </div>`
            },
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[ 3, "desc" ]],
            ajax: {
                url: '<?PHP echo base_url(); ?>roles/getdata',
                type: 'POST',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'nama_role', 'desc_role', 'update_by', 'last_update', 'actions',],
                },
            },
            columns: [
                {data: 'nama_role'},
                {data: 'desc_role'},
                {data: 'update_by'},
                {data: 'last_update'},
                {data: 'actions', responsivePriority: -1},
            ],
            initComplete: function() {
                var thisTable = this;
                var rowFilter = $('<tr class="filter"></tr>').appendTo($(table.table().header()));

                this.api().columns().every(function() {
                    var column = this;
                    var input;

                    switch (column.title()) {
                        //case 'PHOTO':
                        case 'ROLE NAME':
                        case 'ROLE DESCRIPTION':
                        case 'UPDATE BY':
                        //case 'LAST UPDATE':
                            input = $(`<input type="text" class="form-control form-control-sm form-filter kt-input" data-col-index="` + column.index() + `"/>`);
                            break;

                        
                        case 'ACTIONS':
                            var search = $(`
                                <button class="btn btn-brand btn-sm btn-icon btn-icon-md kt-btn btn-sm" title="Search">
                                    <i class="la la-search"></i>
                                </button>`);

                            var reset = $(`<button class="btn btn-secondary btn-sm btn-icon btn-icon-md kt-btn btn-sm" title="Reset">
                                <i class="la la-close"></i>
                            </button>`);

                            $('<th>').append(search).append(reset).appendTo(rowFilter);

                            $(search).on('click', function(e) {
                                e.preventDefault();
                                var params = {};
                                $(rowFilter).find('.kt-input').each(function() {
                                    var i = $(this).data('col-index');
                                    if (params[i]) {
                                        params[i] += '|' + $(this).val();
                                    }
                                    else {
                                        params[i] = $(this).val();
                                    }
                                });
                                $.each(params, function(i, val) {
                                    // apply search params to datatable
                                    table.column(i).search(val ? val : '', false, false);
                                });
                                table.table().draw();
                            });

                            $(reset).on('click', function(e) {
                                e.preventDefault();
                                $(rowFilter).find('.kt-input').each(function(i) {
                                    $(this).val('');
                                    table.column($(this).data('col-index')).search('', false, false);
                                });
                                table.table().draw();
                            });
                            break;
                    }

                    if (column.title() !== 'ACTIONS') {
                        $(input).appendTo($('<th>').appendTo(rowFilter));
                    }
                });

                 // hide search column for responsive table
                var hideSearchColumnResponsive = function () {
                    thisTable.api().columns().every(function () {
                        var column = this
                        if(column.responsiveHidden()) {
                           $(rowFilter).find('th').eq(column.index()).show();
                        } else {
                           $(rowFilter).find('th').eq(column.index()).hide();
                        }
                    })
                };

                // init on datatable load
                hideSearchColumnResponsive();
                // recheck on window resize
                window.onresize = hideSearchColumnResponsive;

                //$('#kt_datepicker_1,#kt_datepicker_2').datepicker();
            },
            columnDefs: [
                {
                    targets: -1,
                    title: 'ACTIONS',
                    orderable: false,
                    render: function(data, type, full, meta) {
                        if (aksesUpdate=='ada') {
                            var z = `
                            <a class="btn btn-sm btn-clean btn-icon btn-icon-md btnupdateM" title="Edit" data-toggle="modal" data-target="#update" data-id="`+data+`">
                                <i data-toggle="tooltip" title="Update" class="la la-edit"></i>
                            </a>`;
                        } else {
                            var z = ``;
                        }

                        if (aksesDelete=='ada') {
                            var x = `
                            <a title="Delete" class="btn btn-sm btn-clean btn-icon btn-icon-md btndeleteMenu" data-toggle="modal" data-target="#delete" data-id="`+data+`">
                                <i class="la la-trash"></i>
                            </a>`;
                        } else {
                            var x = ``;
                        }

                        return z + x;
                        
                    },
                },
                {
                    targets: 0,
                    orderable: false,
                },
            ],
        });
	};

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
		},

	};

}();

// Class definition

var KTFormWidgets = function () {
    // Private functions
    var validator;

    var initWidgets = function() {
        // datepicker
        $('#kt_datepicker').datepicker({
            todayHighlight: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        });

        // datetimepicker
        $('#kt_datetimepicker').datetimepicker({
            pickerPosition: 'bottom-left',
            todayHighlight: true,
            autoclose: true,
            format: 'yyyy.mm.dd hh:ii'
        });

        $('#kt_datetimepicker').change(function() {
            validator.element($(this));
        });

        // timepicker
        $('#kt_timepicker').timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: true
        });

        // daterangepicker
        $('#kt_daterangepicker').daterangepicker({
            buttonClasses: ' btn',
            applyClass: 'btn-primary',
            cancelClass: 'btn-secondary'
        }, function(start, end, label) {
            var input = $('#kt_daterangepicker').find('.form-control');
            
            input.val( start.format('YYYY/MM/DD') + ' / ' + end.format('YYYY/MM/DD'));
            validator.element(input); // validate element
        });

        // bootstrap switch
        $('[data-switch=true]').bootstrapSwitch();
        $('[data-switch=true]').on('switchChange.bootstrapSwitch', function() {
            validator.element($(this)); // validate element
        });

        // bootstrap select
        $('#kt_bootstrap_select').selectpicker();
        $('#kt_bootstrap_select').on('changed.bs.select', function() {
            validator.element($(this)); // validate element
        });

        // select2
        $('#kt_select2').select2({
            placeholder: "Select a state",
        });
        $('#kt_select2').on('select2:change', function(){
            validator.element($(this)); // validate element
        });

        // typeahead
        /*var countries = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: 'https://keenthemes.com/metronic/themes/themes/metronic/dist/preview/inc/api/typeahead/countries.json'
        });

        $('#kt_typeahead').typeahead(null, {
            name: 'countries',
            source: countries
        });
        $('#kt_typeahead').bind('typeahead:select', function(ev, suggestion) {
            validator.element($('#kt_typeahead')); // validate element
        });*/
    }

    var showErrorMsg = function(form, type, msg) {
        var alert = $('<div class="kt-alert kt-alert--outline alert alert-' + type + ' alert-dismissible" role="alert">\
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>\
			<span></span>\
		</div>');

        form.find('.alert').remove();
        alert.prependTo(form);
        //alert.animateClass('fadeIn animated');
        KTUtil.animateClass(alert[0], 'fadeIn animated');
        alert.find('span').html(msg);
    }
    
    var initInsert = function () {
    	$('#saveinsert').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');           

            form.validate({
                rules: {
                    role: {
                        required: true
                    },
                    role_description: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>roles/insert",
                type: "POST",
                beforeSend: function(){ 
                   KTApp.block('#addnewfac .modal-content', {
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'Please wait...'
                    });
                },
                success: function(data) {
                    if(data) {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            //showErrorMsg(form, 'success', '<strong>Data Insert Success!</strong>');

                            KTApp.unblock('#addnewfac .modal-content');
                            
                            $('#addnewfac').modal('toggle');
                            $('#tabledata').DataTable().ajax.reload();
                            $('#forminsert')[0].reset();
                            var alert = $('#suksesinsert');
                			alert.removeClass('kt-hidden').show();
                        }, 2000);
                    } else {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            showErrorMsg(form, 'danger', '<strong>Data Insert Failed!</strong> Change a few things up and try submitting again.');
                            KTApp.unblock('#addnewfac .modal-content');
                            var alert = $('#gagalinsert');
                			alert.removeClass('kt-hidden').show();
                        }, 2000);
                    }
                }
            });
        });     
    }

    var initUpdate = function () {
        $('#saveupdate').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');           

            form.validate({
                rules: {
                    ed_role: {
                        required: true
                    },
                    ed_role_description: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>roles/update",
                type: "POST",
                beforeSend: function(){ 
                   KTApp.block('#update .modal-content', {
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'Please wait...'
                    });
                },
                success: function(data) {
                    if(data) {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            //showErrorMsg(form, 'success', '<strong>Data Insert Success!</strong>');
                            KTApp.unblock('#update .modal-content');
                            
                            $('#update').modal('toggle');
                            $('#tabledata').DataTable().ajax.reload();
                            $('#formupdate')[0].reset();
                            var alert = $('#suksesinsert');
                            alert.removeClass('kt-hidden').show();
                        }, 2000);
                    } else {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            KTApp.unblock('#update .modal-content');
                            showErrorMsg(form, 'danger', '<strong>Data Update Failed!</strong> Change a few things up and try submitting again.');
                            var alert = $('#gagalinsert');
                            alert.removeClass('kt-hidden').show();
                        }, 2000);
                    }
                }
            });
        });     
    }

    var initDelete = function () {
    	$('#deleteRoles').click(function(e) {
            e.preventDefault();
            var btn 	= $(this);
            var form 	= $(this).closest('form');           
            var id 		= $("#iddelroles").val();

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>roles/delete",
                type: "POST",
                success: function(data) {
                    if(data) {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            //showErrorMsg(form, 'success', '<strong>Data Insert Success!</strong>');
                            
                            $('#delete').modal('toggle');
                            $('#tabledata').DataTable().ajax.reload();
                            var alert = $('#suksesdelete');
                			alert.removeClass('kt-hidden').show();
                        }, 2000);
                    } else {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            //showErrorMsg(form, 'danger', '<strong>Data Insert Failed!</strong> Change a few things up and try submitting again.');
                            var alert = $('#gagalinsert');
                			alert.removeClass('kt-hidden').show();
                        }, 2000);
                    }
                }
            });
        });     
    }

    return {
        // public functions
        init: function() {
            initWidgets(); 
            initInsert();
            initUpdate();
            initDelete();
        }
    };
}();

jQuery(document).ready(function() {    
    KTFormWidgets.init();
    KTDatatablesDataSourceAjaxServer.init();
});
</script>