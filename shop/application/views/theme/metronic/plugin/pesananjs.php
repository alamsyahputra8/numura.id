<?PHP 
$userdata   = $this->session->userdata('sesspwt'); 
$userid     = $userdata['userid'];
$role       = $userdata['id_role'];
?>
<script>
"use strict";

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

$(document).on('click', '.btnupdateM', function(e){
    e.preventDefault();

    var uid = $(this).data('id'); // get ids of clicked row
    $('#dynamic-content').hide(); // hide dive for loader
    $('#modal-loader').show();  // load ajax loader
    
    $('.select2norm').val('');
    $.ajax({
        url: '<?PHP echo base_url(); ?>pesanan/modal',
        type: 'POST',
        data: 'id='+uid,
        dataType: 'json'
    })
    .done(function(data){
        console.log('data tidak ketemu',data);
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        $("#namedata").html(data.custom_nama);

        $("#ed_id").val(data.id_pesanan);
        $('#ed_name').val(data.custom_nama);
        $('#ed_type').val(data.kaos_type);
        $('#ed_ukuran').val(data.ukuran).trigger('change');
        $('#ed_typedesign').val(data.design_type).trigger('change');
        // $('#ed_warna').val(data.warna).trigger('change');
        // $('#ed_karakter').val(data.karakter).trigger('change');
        setTimeout(function() {
            $('#ed_warna').val(data.warna);
            $('#ed_karakter').val(data.karakter);
            $('#ed_karakter').trigger('change');
        }, 2000);

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
        url: '<?PHP echo base_url(); ?>pesanan/modal',
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
        url: '<?PHP echo base_url(); ?>pesanan/modal',
        type: 'POST',
        data: 'id='+id,
        dataType: 'json'
    })
    .done(function(data){
        console.log(data);
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        $('#iddel').val(data.id_pesanan);
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
        url: '<?PHP echo base_url(); ?>pesanan/modal',
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

$(document).on('change', '#typedesign', function(e){
    e.preventDefault();

    var uid = $(this).val();

     $.ajax({
        url: '<?PHP echo base_url(); ?>pesanan/getCharacter',
        type: 'POST',
        data: 'id='+uid,
    })
    .done(function(data){
        $('#karakter').html(data);
        $('#karakter').trigger('change');
    })
    .fail(function(){
        $('#karakter').html('<option value="">-- Pilih Type Design Terlebih dahulu --</option>');
        $('#karakter').trigger('change');
    });
});

$(document).on('change', '#ed_typedesign', function(e){
    e.preventDefault();

    var uid = $(this).val();

     $.ajax({
        url: '<?PHP echo base_url(); ?>pesanan/getCharacter',
        type: 'POST',
        data: 'id='+uid,
    })
    .done(function(data){
        $('#ed_karakter').html(data);
        $('#ed_karakter').trigger('change');
    })
    .fail(function(){
        $('#ed_karakter').html('<option value="">-- Pilih Type Design Terlebih dahulu --</option>');
        $('#ed_karakter').trigger('change');
    });
});

$(document).on('change', '#karakter', function(e){
    e.preventDefault();

    $('#imgkarakter').html('');

    var uid = $(this).val();

     $.ajax({
        url: '<?PHP echo base_url(); ?>pesanan/getImgChar',
        type: 'POST',
        data: 'id='+uid,
    })
    .done(function(data){
        $('#imgkarakter').html(data);
    })
    .fail(function(){
        $('#imgkarakter').html('');
    });
});

$(document).on('change', '#ed_karakter', function(e){
    e.preventDefault();

    $('#ed_imgkarakter').html('');

    var uid = $(this).val();

     $.ajax({
        url: '<?PHP echo base_url(); ?>pesanan/getImgChar',
        type: 'POST',
        data: 'id='+uid,
    })
    .done(function(data){
        $('#ed_imgkarakter').html(data);
    })
    .fail(function(){
        $('#ed_imgkarakter').html('');
    });
});

$(document).on('change', '#type', function(e){
    e.preventDefault();
    $('#ukuran').trigger('change');
});
$(document).on('change', '#ed_type', function(e){
    e.preventDefault();
    $('#ed_ukuran').trigger('change');
});

$(document).on('change', '#ukuran', function(e){
    e.preventDefault();

    var uid = $(this).val();
    var kt = $('#type').val();

     $.ajax({
        url: '<?PHP echo base_url(); ?>pesanan/getSizeAvail',
        type: 'POST',
        data: 'id='+uid+'&kt='+kt,
    })
    .done(function(data){
        $('#warna').html(data);
    })
    .fail(function(){
        $('#warna').html('<option value="">-- Pilih Ukuran Terlebih dahulu --</option>');
    });
});

$(document).on('change', '#ed_ukuran', function(e){
    e.preventDefault();

    var uid = $(this).val();
    var kt = $('#ed_type').val();

     $.ajax({
        url: '<?PHP echo base_url(); ?>pesanan/getSizeAvail',
        type: 'POST',
        data: 'id='+uid+'&kt='+kt,
    })
    .done(function(data){
        $('#ed_warna').html(data);
    })
    .fail(function(){
        $('#ed_warna').html('<option value="">-- Pilih Ukuran Terlebih dahulu --</option>');
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
            order: [[ 6, "desc" ]],
            ajax: {
                url: '<?PHP echo base_url(); ?>pesanan/getdata/pending',
                type: 'POST',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'char','name', 'size','color','price','typekaos','tglpesan','status',
                        <?PHP if ($role==1) { ?>
                        'createdby',
                        <?PHP } ?>
                        'actions'],
                },
            },
            columns: [
                {data: 'char', responsivePriority: -1},
                {data: 'name', responsivePriority: -1},
                {data: 'size'},
                {data: 'color'},
                {data: 'price'},
                {data: 'typekaos'},
                {data: 'tglpesan', responsivePriority: -1},
                {data: 'status', responsivePriority: -1},
                <?PHP if ($role==1) { ?>
                {data: 'createdby', responsivePriority: -1},
                <?PHP } ?>
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
                    orderable: false,
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

    var initTable2 = function() {
        //var table = $('#tabledata');

        // DATATABLE
        //table.DataTable({
        var table = $('#tabledataproses').DataTable({
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
            order: [[ 6, "desc" ]],
            ajax: {
                url: '<?PHP echo base_url(); ?>pesanan/getdata/proses',
                type: 'POST',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'char','name', 'size','color','price','typekaos','tglpesan','status',
                        <?PHP if ($role==1) { ?>
                        'createdby',
                        <?PHP } ?>
                        'actions'],
                },
            },
            columns: [
                {data: 'char', responsivePriority: -1},
                {data: 'name', responsivePriority: -1},
                {data: 'size'},
                {data: 'color'},
                {data: 'price'},
                {data: 'typekaos'},
                {data: 'tglpesan', responsivePriority: -1},
                {data: 'status', responsivePriority: -1},
                <?PHP if ($role==1) { ?>
                {data: 'createdby', responsivePriority: -1},
                <?PHP } ?>
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
                    orderable: false,
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

    var initTable3 = function() {
        //var table = $('#tabledata');

        // DATATABLE
        //table.DataTable({
        var table = $('#tabledatasend').DataTable({
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
            order: [[ 6, "desc" ]],
            ajax: {
                url: '<?PHP echo base_url(); ?>pesanan/getdata/send',
                type: 'POST',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'char','name', 'size','color','price','typekaos','tglpesan','status',
                        <?PHP if ($role==1) { ?>
                        'createdby',
                        <?PHP } ?>
                        'actions'],
                },
            },
            columns: [
                {data: 'char', responsivePriority: -1},
                {data: 'name', responsivePriority: -1},
                {data: 'size'},
                {data: 'color'},
                {data: 'price'},
                {data: 'typekaos'},
                {data: 'tglpesan', responsivePriority: -1},
                {data: 'status', responsivePriority: -1},
                <?PHP if ($role==1) { ?>
                {data: 'createdby', responsivePriority: -1},
                <?PHP } ?>
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
                    orderable: false,
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
            initTable2();
            initTable3();
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
                url: "<?PHP echo base_url(); ?>pesanan/listcustomer",
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
                    name: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    ukuran: {
                        required: true
                    },
                    warna: {
                        required: true
                    },
                    karakter: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>pesanan/insert",
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
                            $('#warna').html('<option value="">-- Pilih Ukuran Terlebih dahulu --</option>');
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

    var initInsertNew = function () {
        $('#saveandcreate').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');           

            form.validate({
                rules: {
                    name: {
                        required: true
                    },
                    type: {
                        required: true
                    },
                    ukuran: {
                        required: true
                    },
                    warna: {
                        required: true
                    },
                    karakter: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>pesanan/insert",
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
                            
                            // $('#addnewfac').modal('toggle');
                            $('#tabledata').DataTable().ajax.reload();
                            $('#forminsert')[0].reset();
                            $('#warna').html('<option value="">-- Pilih Ukuran Terlebih dahulu --</option>');
                            var alert = $('#suksesinsert');
                            // $('.select2norm').val(null).trigger('change');
                            document.getElementById("name").autofocus;
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
                    ed_id: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>pesanan/update",
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
                        console.log('jquerryupdate',data)
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            //showErrorMsg(form, 'success', '<strong>Data Insert Success!</strong>');
                            KTApp.unblock('#update .modal-content');
                            
                            $('#update').modal('toggle');

                            $('#tabledata').DataTable().ajax.reload();
                            $('#formupdate')[0].reset();
                            $('#ed_warna').html('<option value="">-- Pilih Ukuran Terlebih dahulu --</option>');
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
            console.log('ini id delete',id)
            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>pesanan/delete",
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

    var initProses = function () {
        $('#prosesBtn').click(function(e) {
            e.preventDefault();
            var btn     = $(this);
            var form    = $(this).closest('form');           
            var id      = $("#idapp").val();
            
            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>pesanan/approve",
                type: "POST",
                success: function(data) {
                    if(data) {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            //showErrorMsg(form, 'success', '<strong>Data Insert Success!</strong>');
                            
                            $('#proses').modal('toggle');
                            $('#tabledata').DataTable().ajax.reload();
                            var alert = $('#suksesinsert');
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
            initInsertNew();
            initUpdate();
            initDelete();
            initProses();
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