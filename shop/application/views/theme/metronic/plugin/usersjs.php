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

$('#user').keyup(delay(function (e) {
    if($('#ldap').prop("checked") == true){
        var username  = $(this).val();

        $.ajax({
            url: '<?PHP echo base_url(); ?>user/getprofileLDAP',
            type: 'POST',
            data: 'username='+username,
            dataType: 'json',
            beforeSend: function(){ 
                $('#erroruser').fadeOut('fast');
                KTApp.block('#addnewfac .modal-content', {
                    overlayColor: '#000000',
                    type: 'v2',
                    state: 'success',
                    message: 'Please wait...'
                });
            },
        })
        .done(function(data){
            $('#erroruser').fadeOut('fast');
            KTApp.unblock('#addnewfac .modal-content');

            $('#name').val(data.name);
            $('#email').val(data.email);
            document.getElementById("eksisava").style = "background: url('<?PHP echo base_url(); ?>images/user/"+data.username+".jpg') no-repeat center; background-size: 100% 100%;";

            var cekname = data.name;
            if (cekname===null) {
                $('#erroruser').fadeIn('fast');
            }
        })
        .fail(function(){
            $('#erroruser').fadeIn('fast');
            KTApp.unblock('#addnewfac .modal-content');
        });
    }
}, 500));

 $('#ed_user').keyup(delay(function (e) {
    if($('#ed_ldap').prop("checked") == true){
        var username  = $(this).val();

        $.ajax({
            url: '<?PHP echo base_url(); ?>user/getprofileLDAP',
            type: 'POST',
            data: 'username='+username,
            dataType: 'json',
            beforeSend: function(){ 
                $('#ed_erroruser').fadeOut('fast');
                KTApp.block('#update .modal-content', {
                    overlayColor: '#000000',
                    type: 'v2',
                    state: 'success',
                    message: 'Please wait...'
                });
            },
        })
        .done(function(data){
            $('#ed_erroruser').fadeOut('fast');
            KTApp.unblock('#update .modal-content');

            $('#ed_name').val(data.name);
            $('#ed_email').val(data.email);
            document.getElementById("ed_eksisava").style = "background: url('<?PHP echo base_url(); ?>images/user/"+data.username+".jpg') no-repeat center; background-size: 100% 100%;";
            var cekname = data.name;
            if (cekname===null) {
                $('#ed_erroruser').fadeIn('fast');
            }
        })
        .fail(function(){
            $('#ed_erroruser').fadeIn('fast');
            KTApp.unblock('#update .modal-content');
        });
    }
}, 500));

$('#role').change(function() {
    var id_role = $(this).val();
    if(id_role == ""){
        $('#contentrole').html('');
    }else{
        $('#contentrole').load("<?PHP echo base_url(); ?>user/getDataRole/"+id_role+"", function(){});          
    }
});

$('#ed_role').change(function() {
    var id_role = $(this).val();
    if(id_role == ""){
        $('#edcontentrole').html('');
    }else{
        $('#edcontentrole').load("<?PHP echo base_url(); ?>user/getDataRole/"+id_role+"", function(){});            
    }
});

$('#ldap').click(function(){
    var foo = document.getElementById("pass");

    if($(this).prop("checked") == true){
        $('#name').attr('readonly', true); 
        $('#pass').attr('readonly', true); 
        $('#email').attr('readonly', true); 
        $('#pict').attr('disabled', true); 
        foo.removeAttribute('minlength');
    }
    else if($(this).prop("checked") == false){
        $('#name').attr('readonly', false); 
        $('#pass').attr('readonly', false); 
        $('#email').attr('readonly', false); 
        $('#pict').attr('disabled', false);    
        foo.setAttribute('minlength', '6');
    }
});

$('#ed_ldap').click(function(){
    var foo = document.getElementById("ed_pass");

    if($(this).prop("checked") == true){
        $('#ed_name').attr('readonly', true); 
        $('#ed_pass').attr('readonly', true); 
        $('#ed_email').attr('readonly', true); 
        $('#upl').attr('disabled', true); 
        foo.removeAttribute('minlength');
    }
    else if($(this).prop("checked") == false){
        $('#ed_name').attr('readonly', false); 
        $('#ed_pass').attr('readonly', false); 
        $('#ed_email').attr('readonly', false); 
        $('#upl').attr('disabled', false);    
        foo.setAttribute('minlength', '6');
    }
});

$(document).on('click', '.btnupdateM', function(e){
    e.preventDefault();

    var uid = $(this).data('id'); // get id of clicked row

    $('#dynamic-content').hide(); // hide dive for loader
    $('#modal-loader').show();  // load ajax loader
    document.getElementById("ed_ldap").checked = false;
    
    $.ajax({
        url: '<?PHP echo base_url(); ?>user/modal',
        type: 'POST',
        data: 'id='+uid,
        dataType: 'json'
    })
    .done(function(data){
        // console.log(data);
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        
        var ldap   = data.ldap;
        var foo = document.getElementById("ed_pass");

        if(ldap==1) {
            document.getElementById("ed_ldap").checked = true;
            $('#ed_name').attr('readonly', true); 
            $('#ed_pass').attr('readonly', true); 
            $('#ed_email').attr('readonly', true); 
            $('#upl').attr('disabled', true); 
            foo.removeAttribute('minlength');
        } else {
            document.getElementById("ed_ldap").checked = false;
            $('#ed_name').attr('readonly', false); 
            $('#ed_pass').attr('readonly', false); 
            $('#ed_email').attr('readonly', false); 
            $('#upl').attr('disabled', false); 
            foo.setAttribute('minlength', '6'); 
        }

        document.getElementById("ed_eksisava").style = "background: url('<?PHP echo base_url(); ?>images/user/"+data.picture+"') no-repeat center; background-size: 100% 100%;";

        $('#namedata').html(data.name);
        $('#ed_iduser').val(data.userid);
        $('#ed_name').val(data.name);
        $('#ed_email').val(data.email);
        $('#ed_user').val(data.username);
        $('#ed_phone').val(data.phone);

        $('#ed_role').val(data.id_role);
        $( "#ed_role" ).trigger( "change" );

        // $('#ed_clientid').val(data.authid);
        // $( "#ed_clientid" ).trigger( "change" );

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
        url: '<?PHP echo base_url(); ?>user/modal',
        type: 'POST',
        data: 'id='+id,
        dataType: 'json'
    })
    .done(function(data){
        // console.log(data);
        $('#dynamic-content').hide(); // hide dynamic div
        $('#dynamic-content').show(); // show dynamic div
        //$('#namedel').html(data.name);
        $('#iddel').val(data.userid);
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
            order: [1,'asc'],
            ajax: {
                url: '<?PHP echo base_url(); ?>user/getdata',
                type: 'POST',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'picture', 'name', 'username', 'email', 'role', 'phone', 'actions',],
                },
            },
			columns: [
				{data: 'picture'},
				{data: 'name'},
				{data: 'username'},
                {data: 'email'},
                {data: 'role'},
                {data: 'phone'},
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
                        case 'NAME':
                        case 'USERNAME':
                        case 'EMAIL':
                        case 'ROLE':
                        case 'PHONE':
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
        $('.kt_select2norm').select2({
            placeholder: "Pilih...",
        });
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
                    id_user: {
                        required: true
                    },
                    name: {
                        required: true
                    },
                    user: {
                        required: true
                    },
                    phone: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    role: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>user/insert",
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
                    ed_user: {
                        required: true
                    },
                    ed_email: {
                        required: true,
                        email: true
                    },
                    ed_phone: {
                        required: true
                    },
                    ed_role: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>user/update",
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
                url: "<?PHP echo base_url(); ?>user/delete",
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

    var passMeter = function () {
        var strength = {
                0: "<span class='text-danger'>Worst</span>",
                1: "<span class='text-warning'>Bad</span>",
                2: "<span class='text-warning'>Weak</span>",
                3: "<span class='text-success'>Good</span>",
                4: "<span class='text-success'>Strong</span>"
        }

        var password = document.getElementById('pass');
        var meter = document.getElementById('password-strength-meter');
        var text = document.getElementById('password-strength-text');

        password.addEventListener('input', function()
        {
            var val = password.value;
            var result = zxcvbn(val);
            
            // Update the password strength meter
            meter.value = result.score;
           
            // Update the text indicator
            if(val !== "") {
                text.innerHTML = "Strength: " + "<strong>" + strength[result.score] + "</strong>" + "<br><span class='feedback'>" + result.feedback.warning + " " + result.feedback.suggestions + "</span"; 
            }
            else {
                text.innerHTML = "";
            }
        });
    }

    var ed_passMeter = function () {
        var strength = {
                0: "<span class='text-danger'>Worst</span>",
                1: "<span class='text-warning'>Bad</span>",
                2: "<span class='text-warning'>Weak</span>",
                3: "<span class='text-success'>Good</span>",
                4: "<span class='text-success'>Strong</span>"
        }

        var password = document.getElementById('ed_pass');
        var meter = document.getElementById('ed_password-strength-meter');
        var text = document.getElementById('ed_password-strength-text');

        password.addEventListener('input', function()
        {
            var val = password.value;
            var result = zxcvbn(val);
            
            // Update the password strength meter
            meter.value = result.score;
           
            // Update the text indicator
            if(val !== "") {
                text.innerHTML = "Strength: " + "<strong>" + strength[result.score] + "</strong>" + "<br><span class='feedback'>" + result.feedback.warning + " " + result.feedback.suggestions + "</span"; 
            }
            else {
                text.innerHTML = "";
            }
        });
    }

    return {
        // public functions
        init: function() {
            passMeter();
            ed_passMeter();
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