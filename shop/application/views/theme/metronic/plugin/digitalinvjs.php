<?PHP 
$userdata   = $this->session->userdata('sesspwt'); 
$userid     = $userdata['userid'];
$role       = $userdata['id_role'];
?>
<script>
"use strict";

$('.dp').datepicker({
    todayHighlight: true,
    autoclose: true,
    format: 'yyyy-mm-dd'
});

$('.dpt').datetimepicker({
    todayHighlight: true,
    autoclose: true,
    format: 'yyyy-mm-dd hh:ii:00'
});

$(document).ready(function() {
    var max_fields      = 20; //maximum input boxes allowed
    var wrapper         = $("#bgbanner"); //Fields wrapper
    var add_button      = $(".btnAddBanner"); //Add button ID
    
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append(`
                <div class="input-group">
                    <input type="file" class="form-control" name="banner[]">
                    <a href="#" class="remove_field col-lg-1">
                        <button class="btn btn-danger btn-sm btn-icon btn-icon-md kt-btn btn-sm">
                            <i class="la la-close"></i>
                        </button>
                    </a>
                </div>
                `); //add input box
        }
    });

    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
        recalc();
    })

    var max_fieldsG     = 20; //maximum input boxes allowed
    var wrapperG        = $("#bggallery"); //Fields wrapper
    var add_buttonG     = $(".btnAddGallery"); //Add button ID
    
    var xG = 1; //initlal text box count
    $(add_buttonG).click(function(e){ //on add input button click
        e.preventDefault();
        if(xG < max_fieldsG){ //max input box allowed
            xG++; //text box increment
            $(wrapperG).append(`
                <div class="input-group">
                    <input type="file" class="form-control" name="gallery[]">
                    <a href="#" class="remove_field col-lg-1">
                        <button class="btn btn-danger btn-sm btn-icon btn-icon-md kt-btn btn-sm">
                            <i class="la la-close"></i>
                        </button>
                    </a>
                </div>
                `); //add input box
        }
    });

    $(wrapperG).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
        recalc();
    })
});

$('#modlive').click(function(){
    if($(this).prop("checked") == true){
        $('#bgiglive').fadeIn('fast'); 
    }
    else if($(this).prop("checked") == false){
        $('#bgiglive').fadeOut('fast');
    }
});

$('#modig').click(function(){
    if($(this).prop("checked") == true){
        $('#bgigm').fadeIn('fast'); 
        $('#bgigf').fadeIn('fast'); 
    }
    else if($(this).prop("checked") == false){
        $('#bgigm').fadeOut('fast');
        $('#bgigf').fadeOut('fast');
    }
});

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      callback.apply(context, args);
    }, ms || 0);
  };
}
$('.select2norm').select2({
    placeholder: "Pilih...",
});

$(document).on('change', '#link', function(e){
    e.preventDefault();
    
    var ceklink = $(this).val(); // get ids of clicked row

    KTApp.block('#addnewfac .modal-content', {
        overlayColor: '#000000',
        type: 'v2',
        state: 'success',
        message: 'Please wait...'
    });

    $.ajax({
        url: '<?PHP echo base_url(); ?>einvit/ceklink',
        type: 'POST',
        data: 'ceklink='+ceklink,
        dataType: 'json'
    })
    .done(function(data){
        KTApp.unblock('#addnewfac .modal-content');
        var isavail     = data.isavail;
        if (isavail=='1') {
            $('#alertlink').fadeIn('fast');
            $('#saveinsert').attr('disabled', true);
        } else {
            $('#alertlink').fadeOut('fast');
            $('#saveinsert').attr('disabled', false);
        }
    })
    .fail(function(){
        KTApp.unblock('#addnewfac .modal-content');
        $('#alertlink').fadeIn('fast');
        $('#saveinsert').attr('disabled', true);
    });
});

$(document).on('keyup', '.pcsval', function(e){
    e.preventDefault();

    var form = $(this).closest('form');

    form.ajaxSubmit({
        url: "<?PHP echo base_url(); ?>einvit/cektotal",
        type: "POST",
        dataType: 'json',
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
                    // btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                    KTApp.unblock('#addnewfac .modal-content');
                    
                    $('#total').val(data.total);
                    $('#totalpcs').val(data.jml);
                }, 2000);
            } else {
                // similate 2s delay
                setTimeout(function() {
                    // btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                    // showErrorMsg(form, 'danger', '<strong>Data Insert Failed!</strong> Change a few things up and try submitting again.');
                    
                    KTApp.unblock('#addnewfac .modal-content');
                    
                    $('#total').val('0');
                    $('#totalpcs').val('0');
                }, 2000);
            }
        }
    });
});

$(document).on('keyup', '.ed_pcsval', function(e){
    e.preventDefault();

    var form = $(this).closest('form');

    form.ajaxSubmit({
        url: "<?PHP echo base_url(); ?>einvit/cektotaledit",
        type: "POST",
        dataType: 'json',
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
                    // btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                    KTApp.unblock('#update .modal-content');
                    
                    $('#ed_total').val(data.total);
                    $('#ed_totalpcs').val(data.jml);
                }, 2000);
            } else {
                // similate 2s delay
                setTimeout(function() {
                    // btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                    // showErrorMsg(form, 'danger', '<strong>Data Insert Failed!</strong> Change a few things up and try submitting again.');
                    
                    KTApp.unblock('#update .modal-content');
                    
                    $('#ed_total').val('0');
                    $('#ed_totalpcs').val('0');
                }, 2000);
            }
        }
    });
});

$(document).on('click', '.btnupdateM', function(e){
    e.preventDefault();

    var uid = $(this).data('id'); // get ids of clicked row
    $('#dynamic-content').hide(); // hide dive for loader
    $('#modal-loader').show();  // load ajax loader
    
    $('.select2norm').val('');
    $.ajax({
        url: '<?PHP echo base_url(); ?>einvit/modal',
        type: 'POST',
        data: 'id='+uid,
        dataType: 'json'
    })
    .done(function(data){

        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        $("#namedata").html(data.label);

        $("#ed_id").val(data.id_order);
        $('#ed_tgl').val(data.createddate);
        $('#ed_label').val(data.label);
        $('#ed_totalpcs').val(data.jml);
        $('#ed_total').val(data.total_harga);

        $('#bgdetailstok').html('Mengambil data pesanan...');
        KTApp.block('#bgdetailstok', {
            overlayColor: '#000000',
            type: 'v2',
            state: 'success',
            message: 'Please wait...'
        });

        $.ajax({
            url: '<?PHP echo base_url(); ?>einvit/getorder',
            data: 'id='+data.id_order,
            type: 'POST',
        })
        .done(function(datay){
            KTApp.unblock('#bgdetailstok');

            $('#dynamic-content').hide(); // hide dynamic div
            $('#dynamic-content').show(); // show dynamic div
            
            $('#bgdetailstok').html(datay);

            $('#modal-loader').hide();    // hide ajax loader
        })
        .fail(function(){
            $('#bgdetailstok').html('');
        });

        $('#modal-loader').hide();    // hide ajax loader
    })
    .fail(function(){
        $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please refresh page...');
    });
});

$(document).on('click', '.btnPrint', function(e){
    e.preventDefault();

    var uid = $(this).data('id'); // get ids of clicked row
    $('#dynamic-content').hide(); // hide dive for loader
    $('#modal-loader').show();  // load ajax loader
    
    $.ajax({
        url: '<?PHP echo base_url(); ?>einvit/modal',
        type: 'POST',
        data: 'id='+uid,
        dataType: 'json'
    })
    .done(function(data){
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        
        
        $('#modal-loader').hide();    // hide ajax loader
    })
    .fail(function(){
        
    });
});

$(document).on('click', '.btndeleteMenu', function(e){
    e.preventDefault();

    var id = $(this).data('id'); // get id of clicked row
    //console.log('id modal',id)
    $('#dynamic-content').hide(); // hide dive for loader
    $('#modal-loader').show();  // load ajax loader

    $.ajax({
        url: '<?PHP echo base_url(); ?>einvit/modal',
        type: 'POST',
        data: 'id='+id,
        dataType: 'json'
    })
    .done(function(data){
        console.log(data);
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        $('#iddel').val(data.id);
        $('#modal-loader').hide();    // hide ajax loader
    })
    .fail(function(){
        $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
    });
});

$(document).on('click', '.btnbayarM', function(e){
    e.preventDefault();

    var id = $(this).data('id'); // get id of clicked row
    //console.log('id modal',id)
    $('#dynamic-content').hide(); // hide dive for loader
    $('#modal-loader').show();  // load ajax loader

    $.ajax({
        url: '<?PHP echo base_url(); ?>einvit/modal',
        type: 'POST',
        data: 'id='+id,
        dataType: 'json'
    })
    .done(function(data){
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        $('#idorder').val(data.id_order);
        $('#modal-loader').hide();    // hide ajax loader
    })
    .fail(function(){
        $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please try again...');
    });
});

$(document).on('click', '.btnProses', function(e){
    e.preventDefault();

    var id = $(this).data('id'); // get id of clicked row
    //console.log('id modal',id)
    $('#dynamic-content').hide(); // hide dive for loader
    $('#modal-loader').show();  // load ajax loader

    $.ajax({
        url: '<?PHP echo base_url(); ?>einvit/modal',
        type: 'POST',
        data: 'id='+id,
        dataType: 'json'
    })
    .done(function(data){
        console.log(data);
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        $('#idapp').val(data.id_pesanan);
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
            // dom: `<'row'<'col-sm-12'tr>>
            // <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
            // read more: https://datatables.net/examples/basic_init/dom.html

            dom:
                "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // read more: https://datatables.net/examples/basic_init/dom.html

            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],

            pageLength: 10,
            buttons: [
                { extend: 'print', title: 'Impression', exportOptions: { columns: ':visible' } },
                'copyHtml5',
                {
                    extend: 'excelHtml5',
                    exportOptions : {
                        modifier : {
                            // DataTables core
                            order : 'index',  // 'current', 'applied', 'index',  'original'
                            page : 'all',      // 'all',     'current'
                            search : 'none'     // 'none',    'applied', 'removed'
                        }
                    }
                },
                'csvHtml5',
                { extend: 'pdfHtml5', title: 'PDF', download: 'open', exportOptions: { columns: ':visible' } },
            ],

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
            order: [[ 5, "desc" ]],
            ajax: {
                url: '<?PHP echo base_url(); ?>einvit/getdata',
                type: 'POST',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'orderid', 'link', 'pria', 'wanita', 'tgl', 'createddate', 'status', 'actions'],
                },
            },
            columns: [
                {data: 'orderid', responsivePriority: -1},
                {data: 'link', responsivePriority: -1},
                {data: 'pria'},
                {data: 'wanita'},
                {data: 'tgl'},
                {data: 'createddate'},
                {data: 'status'},
                {data: 'actions', responsivePriority: -1},
            ],
            columnDefs: [
                {
                    targets: -1,
                    title: 'ACTIONS',
                    orderable: false,
                },
                {
                    targets: 0,
                    orderable: true,
                },
            ],
        });

        $('#export_print').on('click', function(e) {
            e.preventDefault();
            table.button(0).trigger();
        });

        $('#export_copy').on('click', function(e) {
            e.preventDefault();
            table.button(1).trigger();
        });

        $('#export_excel').on('click', function(e) {
            e.preventDefault();
            table.button(2).trigger();
        });

        $('#export_csv').on('click', function(e) {
            e.preventDefault();
            table.button(3).trigger();
        });

        $('#export_pdf').on('click', function(e) {
            e.preventDefault();
            table.button(4).trigger();
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
        $('.kt_select2norm').select2({
            placeholder: "Pilih...",
        });
        $('#kt_select2').select2({
            placeholder: "Select a state",
        });
        $('#kt_select2').on('select2:change', function(){
            validator.element($(this)); // validate elementa
        });

        $(".getPenanggung").select2({
            closeOnSelect:true,
            placeholder: "Pilih...",
            allowClear: true,
            ajax: {
                url: "<?PHP echo base_url(); ?>einvit/listcustomer",
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
            minimumInputLength: 0,
            templateResult: formatRepo, // omitted for brevity, see the source of this page
            templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
        });

         function formatRepo (repo) {
          if (repo.loading) {
            return repo.text;
          }

          var markup = "<div>"+ repo.username +" - " + repo.text + "</div>";

          return markup;
        }

        function formatRepoSelection (repo) {
          return repo.username +' - '+ repo.text;
        }
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
                    orderid: { required: true },
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>einvit/insert",
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
                            // $('.select2norm').val(null).trigger('change');
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
                    ed_orderid: {
                        required: true
                    },
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>einvit/update",
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
            var btn     = $(this);
            var form    = $(this).closest('form');           
            var id      = $("#iddel").val();

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>einvit/delete",
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

var KTUserEdit = function () {
    // Base elements
    var avatar;
     
    var initUserForm = function() {
        avatar = new KTAvatar('useravatar');
    }   
    var initUserFormed = function() {
        avatar = new KTAvatar('ed_useravatar');
    }   

    return {
        // public functions
        init: function() {
            initUserForm(); 
            initUserFormed(); 
        }
    };
}();

jQuery(document).ready(function() {    
    KTDatatablesSearchOptionsColumnSearch.init();
    KTFormWidgets.init();
    KTUserEdit.init();
});
</script>