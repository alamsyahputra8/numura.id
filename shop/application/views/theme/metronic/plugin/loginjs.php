<script>
"use strict";

// var base_url = window.location.origin;

// Class Definition
var KTLoginGeneral = function() {

    var login = $('#bglogincek');

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

    // Private Functions
    var displaySignUpForm = function() {
        login.removeClass('kt-login--forgot');
        login.removeClass('kt-login--signin');

        login.addClass('kt-login--signup');
        KTUtil.animateClass(login.find('.kt-login__signup')[0], 'flipInX animated');
    }

    var displaySignInForm = function() {
        // login.removeClass('kt-login--forgot');
        // login.removeClass('kt-login--signup');

        // login.addClass('kt-login--signin');
        // KTUtil.animateClass(login.find('.kt-login__signin')[0], 'flipInX animated');
        //login.find('.kt-login__signin').animateClass('flipInX animated');

        $('.kt-login__forgot').fadeOut('fast');
        $('.kt-login__signin').fadeIn('fast');
    }

    var displayForgotForm = function() {
        // login.removeClass('kt-login--signin');
        // login.removeClass('kt-login--signup');

        // login.addClass('kt-login--forgot');
        // login.find('.kt-login--forgot').animateClass('flipInX animated');
        // KTUtil.animateClass(login.find('.kt-login__forgot')[0], 'flipInX animated');

        $('.kt-login__signin').fadeOut('fast');
        $('.kt-login__forgot').fadeIn('fast');
    }

    var handleFormSwitch = function() {
        $('#kt_login_forgot').click(function(e) {
            e.preventDefault();
            displayForgotForm();
        });

        $('#kt_login_forgot_cancel').click(function(e) {
            e.preventDefault();
            displaySignInForm();
        });

        $('#kt_login_signup').click(function(e) {
            e.preventDefault();
            displaySignUpForm();
        });

        $('#kt_login_signup_cancel').click(function(e) {
            e.preventDefault();
            displaySignInForm();
        });
    }

    var handleSignInFormSubmit = function() {
        $('#kt_login_signin_submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');           

            form.validate({
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>panel/ceklogin",
                type: "POST",
                success: function(data) {
                    if(data) {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            if (data=='needotp') {
                                var username    = $('#username').val();
                                var password    = $('#password').val();

                                $('#userotp').val(username);
                                $('#passotp').val(password);

                                showErrorMsg(form, 'warning', 'Mengirim kode OTP...');
                                setTimeout(function(){
                                    $('.kt-login__signin').fadeOut('fast');
                                    $('.kt-login__signup').fadeIn('fast');
                                } , 1500);    
                            } else {
                                showErrorMsg(form, 'success', 'Welcome back, <b>'+data+'</b>');
                                setTimeout(function(){
                                    //location.href="./"
                                    location.reload()
                                } , 1500);
                            }
                        }, 2000);
                    } else {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            showErrorMsg(form, 'danger', 'Incorrect username/password or activate your account. Please try again.');
                        }, 2000);
                    }
                }
            });
        });
    }

    var handleSignUpFormSubmit = function() {
        $('#kt_login_signup_submit').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');           

            form.validate({
                rules: {
                    username: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    otp: {
                        required: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>panel/loginotp",
                type: "POST",
                success: function(data) {
                    if(data) {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            showErrorMsg(form, 'success', 'Welcome back, <b>'+data+'</b>');
                            setTimeout(function(){
                                //location.href="./"
                                location.reload()
                            } , 1500);
                        }, 2000);
                    } else {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            showErrorMsg(form, 'danger', 'Invalid OTP.');
                        }, 2000);
                    }
                }
            });
        });
    }

    var resendOTPform = function() {
        $('#resendotp').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = $(this).closest('form');
            var username = $('#userotp').val();

            btn.html('Mengirim ulang kode OTP...');

            form.ajaxSubmit({
                url: "<?PHP echo base_url(); ?>login/resendOTP/"+username+"/"+10+"",
                type: "POST",
                success: function(data) {
                    if(data) {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.html('Kirim ulang kode OTP');
                            showErrorMsg(form, 'success', 'Kode OTP berhasil terkirim.');
                        }, 2000);
                    } else {
                        // similate 2s delay
                        setTimeout(function() {
                            btn.html('Kirim ulang kode OTP');
                            btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false);
                            showErrorMsg(form, 'danger', 'Resend OTP failed.');
                        }, 2000);
                    }
                }
            });
        });
    }

    var handleForgotFormSubmit = function() {
        $('#kt_login_forgot_submit').click(function(e) {
            e.preventDefault();

            var btn = $(this);
            var form = $(this).closest('form');

            form.validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                }
            });

            if (!form.valid()) {
                return;
            }

            btn.addClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', true);

            form.ajaxSubmit({
                url: '',
                success: function(response, status, xhr, $form) { 
                    // similate 2s delay
                    setTimeout(function() {
                        btn.removeClass('kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light').attr('disabled', false); // remove
                        form.clearForm(); // clear form
                        form.validate().resetForm(); // reset validation states

                        // display signup form
                        displaySignInForm();
                        var signInForm = login.find('.kt-login__signin form');
                        signInForm.clearForm();
                        signInForm.validate().resetForm();

                        showErrorMsg(signInForm, 'success', 'Cool! Password recovery instruction has been sent to your email.');
                    }, 2000);
                }
            });
        });
    }

    var viewPassword = function() {
    // function viewPassword() {
        $('#pass-status').click(function(e) {
            var x           = document.getElementById("password");
            var passStatus  = document.getElementById("pass-status");
            if (x.type === "password") {
                x.type = "text";
                passStatus.className='fa fa-eye-slash';
            } else {
                x.type = "password";
                passStatus.className='fa fa-eye';
            }
        });
    }

    // Public Functions
    return {
        // public functions
        init: function() {
            viewPassword();
            handleFormSwitch();
            handleSignInFormSubmit();
            handleSignUpFormSubmit();
            resendOTPform();
            handleForgotFormSubmit();
        }
    };
}();

// Class Initialization
jQuery(document).ready(function() {
    KTLoginGeneral.init();
});
</script>