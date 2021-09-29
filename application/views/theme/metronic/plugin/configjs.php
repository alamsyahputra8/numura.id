<script>
"use strict";
$(document).ready(function() {
    var pic = '';

    $('#cancelupdate').click(function() {
        getProfile();
        $('#upl').val('');
    });
    
    getProfile();
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.avatar').attr('src', e.target.result);
            }
    
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(".file-upload").on('change', function(){
        readURL(this);
    });

    function getProfile(){
        var id = '1';
        $.ajax({
            url: '<?PHP echo base_url(); ?>core/getSiteConfig',
            type: 'POST',
            data: 'id='+id,
            dataType: 'json'
        })
        .done(function(data){
            $('#dynamic-content').hide(); // hide dynamic div
            $('#dynamic-content').show(); // show dynamic div

            // $('#nama_lengkap').html(data.name_site);
            $('#mailbase').html(data.mailbase);
            $('#alamatex').html(data.alamat);
            if(data.logo=='<?PHP echo base_url(); ?>images/'){
                pic = '<?PHP echo base_url();?>images/user/default.png';
            }else{
                pic = data.logo;
            }
            $('.avatar').attr('src',pic);
            
            $('#name').val(data.name_site);
            $('#phone').val(data.phone);
            $('#alamat').val(data.alamat);
            $('#facebook').val(data.facebook);
            $('#twitter').val(data.twitter);
            $('#youtube').val(data.youtube);
            $('#instagram').val(data.instagram);
            $('#showreel').val(data.showreel);

            $('#updateby').html(data.updateby);
            $('#updatedate').html(data.updatedate);
        })
        .fail(function(){
            
        });
    }
});

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
    
    var initUpdate = function () {
        $('#saveupdate').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');           

            form.validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    phone: {
                        required: true
                    },
                    alamat: {
                        required: true
                    },
                    maps: {
                        required: true
                    },
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>core/updateSiteConfig",
                type: "POST",
                beforeSend: function(){ 
                   KTApp.block('#update .kt-invoice__body', {
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
                            KTApp.unblock('#update .kt-invoice__body');

                            var id = '1';
                            $.ajax({
                                url: '<?PHP echo base_url(); ?>core/getSiteConfig',
                                type: 'POST',
                                data: 'id='+id,
                                dataType: 'json'
                            })
                            .done(function(data){
                                $('#dynamic-content').hide(); // hide dynamic div
                                $('#dynamic-content').show(); // show dynamic div

                                // $('#nama_lengkap').html(data.name_site);
                                $('#mailbase').html(data.mailbase);
                                $('#alamatex').html(data.alamat);
                                if(data.logo=='<?PHP echo base_url(); ?>images/'){
                                    pic = '<?PHP echo base_url();?>images/user/default.png';
                                }else{
                                    pic = data.logo;
                                }
                                $('.avatar').attr('src',pic);
                                
                                $('#name').val(data.name_site);
                                $('#phone').val(data.phone);
                                $('#alamat').val(data.alamat);
                                $('#facebook').val(data.facebook);
                                $('#twitter').val(data.twitter);
                                $('#youtube').val(data.youtube);
                                $('#instagram').val(data.instagram);
                                $('#showreel').val(data.showreel);

                                $('#updateby').html(data.updateby);
                                $('#updatedate').htmll(data.updatedate);
                            })
                            .fail(function(){
                                
                            });

                            var alert = $('#suksesinsert');
                            alert.removeClass('kt-hidden').show();
                        }, 2000);
                    } else {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            $('#edcontentrole').html('');
                            
                            KTApp.unblock('#update .kt-invoice__body');

                            showErrorMsg(form, 'danger', '<strong>Data Update Failed!</strong> Change a few things up and try submitting again.');
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
            initUpdate();
        }
    };
}();

jQuery(document).ready(function() {    
    KTFormWidgets.init();
});
</script>