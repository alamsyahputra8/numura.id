<script>
"use strict";

/*KTApp.block('#bgportfolio .loaderport', {
    overlayColor: '#000000',
    type: 'v2',
    state: 'success',
    message: 'Please wait...'
});*/

/*$(window).bind("load", function() {
    $("#bgportfolio").load( "<?PHP echo base_url(); ?>core/getdataEksPhotos", function() {
        KTApp.unblock('#bgportfolio .loaderport');
    });
});*/

/*$('#service').change(function() {
    $('#menu').html('');
    var id_service = $(this).val();

    if(id_service == ""){

    }else{
        $.ajax({
            url: "<?PHP echo base_url(); ?>core/getMenubyServices?id="+id_service,
            type: 'Get',
            dataType: "json",

            success: function (result) {
                    //$("#menu").select2({ data: result });
                    $("#menu").val(result.id_menu);
            },
            error: function failCallBk(XMLHttpRequest, textStatus, errorThrown) {
                alert("An error occurred while processing your request. Please try again.");
            }
        });
    }
});*/

$(document).on('click', '.btnupdateM', function(e){
    e.preventDefault();

    var uid = $(this).data('id'); // get id of clicked row

    $('.bgeditnew').remove();

    $('#dynamic-content').hide(); // hide dive for loader
    $('#modal-loader').show();  // load ajax loader
    
    $.ajax({
        url: '<?PHP echo base_url(); ?>core/modalphotos',
        type: 'POST',
        data: 'id='+uid,
        dataType: 'json'
    })
    .done(function(data){
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        
        $('#namedata').html(data.title);
        $('#ed_id').val(data.id_doc);
        $('#ed_document').val(data.title);
        $('#ed_menu').val(data.id_menu);
        $('#ed_menu').trigger('change.select2');

        $('#eksDoc').load("<?PHP echo base_url(); ?>core/getdataEksDoc/"+data.id_doc+"", function(){});

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
        url: '<?PHP echo base_url(); ?>core/modalphotos',
        type: 'POST',
        data: 'id='+id,
        dataType: 'json'
    })
    .done(function(data){
        // console.log(data);
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        //$('#namedel').html(data.name);
        $('#iddel').val(data.id_photo);
        $('#modal-loader').hide();    // hide ajax loader
    })
    .fail(function(){
        $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
    });
});

var KTDatatablesSearchOptionsColumnSearch = function() {

    $.fn.dataTable.Api.register('column().title()', function() {
        return $(this.header()).text().trim();
    });

	var initTable1 = function() {
		//var table = $('#tabledata');

		// DATATABLE
		//table.DataTable({
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
			//ajax: '<?PHP echo base_url(); ?>core/getdatauser',
            ajax: {
                url: '<?PHP echo base_url(); ?>core/getdatadoc',
                type: 'POST',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'document', 'menu', 'updateby', 'lastupdate', 'actions',],
                },
            },
			columns: [
                {data: 'document'},
                {data: 'menu'},
                {data: 'updateby'},
                {data: 'lastupdate'},
                {data: 'actions', responsivePriority: -1},
            ],
            initComplete: function() {
                var thisTable = this;
                var rowFilter = $('<tr class="filter"></tr>').appendTo($(table.table().header()));

                this.api().columns().every(function() {
                    var column = this;
                    var input;

                    switch (column.title()) {
                        case 'DOCUMENT':
                        case 'MENU':
                        case 'UPDATE BY':
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
			],
		});
	};

    var select2 = function() {
        // loading remote data

        function formatRepo(repo) {
            if (repo.loading) return repo.text;
            var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";
            if (repo.description) {
                markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
            }
            markup += "<div class='select2-result-repository__statistics'>" +
                "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.forks_count + " Forks</div>" +
                "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
                "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
                "</div>" +
                "</div></div>";
            return markup;
        }

        function formatRepoSelection(repo) {
            return repo.full_name || repo.text;
        }

        $("#kt_select2_6").select2({
            placeholder: "Search for git repositories",
            allowClear: true,
            ajax: {
                url: "https://api.github.com/search/repositories",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function(data, params) {
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
            escapeMarkup: function(markup) {
                return markup;
            }, // let our custom formatter work
            minimumInputLength: 1,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });

        $('.select2grup').select2({
            placeholder: "Pilih...",
        });

        $('.select2norm').select2({
        });
    }

    var multipleRow = function() {
        var wrappers_addprod    = $("#p_scents");
        var add_button_addprod  = $(".addScnt");
        var rmv_button_addprod  = $(".deleterow");
        var x = 0; 

        $(add_button_addprod).click(function(e){
            e.preventDefault();
            x++; 
            var addMore_addprod =`
                                <div id="newrow`+x+`">
                                    <div class="kt-separator kt-separator--border-dashed"></div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">File Photos *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class='input-group'>
                                                <input type="file" name="upl[]" class="form-control" id="upl" placeholder="File Photo" accept="image/gif, image/jpeg, image/png, image/jpg, image/bitmap">
                                                <button type="button" class="btn btn-sm btn-danger text-white deleterow" title="Remove Photo" data-id="#newrow`+x+`">
                                                    <i class="fa fa-times text-white"></i>
                                                </button>
                                            </div>
                                            <span class="form-text text-muted">File yang disarankan untuk file foto adalah .jpg, .jpeg, dan .png</span>
                                        </div>
                                    </div>
                                </div>
                                `;
            $(wrappers_addprod).append(addMore_addprod); //add input box
        });

        $(document).on('click', '.deleterow', function(e){
            e.preventDefault();

            var id = $(this).data('id'); // get id of clicked row
            $('#p_scents '+id+'').remove();
        });
    }

    var multipleRowEd = function() {
        var wrappers_addprod    = $("#ed_p_scents");
        var add_button_addprod  = $(".ed_addScnt");
        var rmv_button_addprod  = $(".ed_deleterow");
        var x = 0; 

        $(add_button_addprod).click(function(e){
            e.preventDefault();
            x++; 
            var addMore_addprod =`
                                <div class="bgeditnew" id="ed_newrow`+x+`">
                                    <div class="kt-separator kt-separator--border-dashed"></div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">File Name *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class='input-group'>
                                                <input type="text" name="ed_fdname[]" class="form-control" id="ed_fdname" placeholder="File Name">
                                                <button type="button" class="btn btn-sm btn-danger text-white ed_deleterowN" title="Remove Email" data-id="#ed_newrow`+x+`">
                                                    <i class="fa fa-times text-white"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-lg-3 col-sm-12">File Document *</label>
                                        <div class="col-lg-4 col-md-9 col-sm-12">
                                            <div class='input-group'>
                                                <input type="file" name="ed_upl[]" class="form-control" id="ed_upl[]" placeholder="File Doc" accept="application/msword,application/pdf">
                                            </div>
                                            <span class="form-text text-muted">File yang disarankan untuk file document adalah .doc, .docx, dan .pdf</span>
                                        </div>
                                    </div>
                                </div>
                                `;
            $(wrappers_addprod).append(addMore_addprod); //add input box
        });

        $(document).on('click', '.ed_deleterow', function(e){
            e.preventDefault();

            var id      = $(this).data('id'); // get id of clicked row
            var iddel   = $(this).data('idfile'); // get id of clicked row

            $('#dynamic-content').hide(); // hide dive for loader
            $('#modal-loader').show();  // load ajax loader

            $.ajax({
                url: '<?PHP echo base_url(); ?>core/deletefiledoc',
                type: 'POST',
                data: 'id='+iddel,
                dataType: 'json'
            })
            .done(function(data){
                 $(''+id+'').remove();
            })
            .fail(function(){
                $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
            });
            
        });

        $(document).on('click', '.ed_deleterowN', function(e){
            e.preventDefault();

            var id      = $(this).data('id'); // get id of clicked row
            $(''+id+'').remove();
            
        });
    }

	return {

		//main function to initiate the module
		init: function() {
			initTable1();
            select2();
            multipleRow();
            multipleRowEd();
		},

	};

}();

// Class definition

var KTFormWidgets = function () {
    // Private functions
    var validator;

    var initWidgets = function() {
        $('.headline').maxlength({
            warningClass: "kt-badge kt-badge--warning kt-badge--rounded kt-badge--inline",
            limitReachedClass: "kt-badge kt-badge--success kt-badge--rounded kt-badge--inline"
        });

        $('.summernote').summernote({
            height: 150
        });

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
                    menu: {
                        required: true
                    },
                    upl: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>core/insertphoto",
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
                            
                            //$('#addnewfac').modal('toggle');
                            
                            //$('#tabledata').DataTable().ajax.reload();

                            /*$("#bgportfolio").load( "<?PHP echo base_url(); ?>core/getdataEksPhotos", function() {
                                KTApp.unblock('#bgportfolio .loaderport');
                            });*/
                            
                            $('#forminsert')[0].reset();
                            var alert = $('#suksesinsert');
                			alert.removeClass('kt-hidden').show('fast', function() {
                                setTimeout(function(){
                                    //location.href="./"
                                    location.reload()
                                } , 1500);
                            });
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
                    ed_document: {
                        required: true
                    },
                    ed_menu: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>core/updatedoc",
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
                            $('#edcontentrole').html('');
                            $('#tabledata').DataTable().ajax.reload();
                            $('#formupdate')[0].reset();
                            $('.bgeditnew').remove();
                            var alert = $('#suksesinsert');
                            alert.removeClass('kt-hidden').show();
                        }, 2000);
                    } else {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            $('#edcontentrole').html('');
                            
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
    	$('#deleteBtn').click(function(e) {
            e.preventDefault();
            var btn 	= $(this);
            var form 	= $(this).closest('form');           
            var id 		= $("#iddel").val();

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>core/deletephoto",
                type: "POST",
                success: function(data) {
                    if(data) {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            //showErrorMsg(form, 'success', '<strong>Data Insert Success!</strong>');
                            
                            $('#delete').modal('toggle');
                            //$('#tabledata').DataTable().ajax.reload();

                            $("#bgportfolio").load( "<?PHP echo base_url(); ?>core/getdataEksPhotos", function() {
                                KTApp.unblock('#bgportfolio .loaderport');
                            });

                            var alert = $('#suksesdelete');
                			//alert.removeClass('kt-hidden').show();
                            alert.removeClass('kt-hidden').show('fast', function() {
                                setTimeout(function(){
                                    //location.href="./"
                                    location.reload()
                                } , 1500);
                            });
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
    KTDatatablesSearchOptionsColumnSearch.init();
    KTFormWidgets.init();
});
</script>