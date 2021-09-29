<?php defined('BASEPATH') OR exit('No direct script access allowed.');
	$config['useragent']        = 'PHPMailer';             
	$config['protocol']         = 'smtp';                
	$config['mailpath']         = '/usr/sbin/sendmail';
	$config['smtp_host']        = 'smtp.telkom.co.id';
	$config['smtp_user']        = 'survey_online';
	$config['smtp_pass']        = 'telkomsurvey';
	$config['smtp_port']        = 25;
	$config['smtp_timeout']     = 30;                      
	$config['smtp_crypto']      = 'tls';                    
	$config['smtp_debug']       = 0;                       
	$config['debug_output']     = 'html';
	$config['smtp_auto_tls']    = true;  
	$config['smtp_conn_options'] = array();
	$config['wordwrap']         = true;
	$config['wrapchars']        = 76;
	$config['mailtype']         = 'html';             
	$config['charset']          = null;               
	$config['validate']         = false;
	$config['priority']         = 3;
	$config['crlf']             = "\n";
	$config['newline']          = "\n";
	$config['bcc_batch_mode']   = false;
	$config['bcc_batch_size']   = 200;
	$config['encoding']         = '8bit';
	// DKIM Signing
	// See https://yomotherboard.com/how-to-setup-email-server-dkim-keys/
	// See http://stackoverflow.com/questions/24463425/send-mail-in-phpmailer-using-dkim-keys
	// See https://github.com/PHPMailer/PHPMailer/blob/v5.2.14/test/phpmailerTest.php#L1708
	$config['dkim_domain']      = '';  		// DKIM signing domain name, for exmple 'example.com'.
	$config['dkim_private']     = '';       // DKIM private key, set as a file path.
	$config['dkim_private_string'] = '';    // DKIM private key, set directly from a string.
	$config['dkim_selector']    = '';       // DKIM selector.
	$config['dkim_passphrase']  = '';       // DKIM passphrase, used if your key is encrypted.
	$config['dkim_identity']    = '';       // DKIM Identity, usually the email address used as the source of the email.
?>