<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
   
	function checkingsessionpwt(){
		$CI =& get_instance();
		$session_sess		= $CI->session->userdata('sesspwt');
		 
		  
		if(($session_sess == FALSE))
		{
			$urlmenuact	= $CI->uri->uri_string();
			if ($urlmenuact=='setpassword') {
				echo '';
			} else {
				$CI->load->view("panel/login/login");
			}
		}
		else
		{   
			
			$tmp['logsess'] 	= $session_sess;
			$tmp['userid'] 		= $session_sess['userid'];
			$tmp['nama'] 		= $session_sess['name'];
			$tmp['username'] 	= $session_sess['username'];
			$tmp['picture'] 	= $session_sess['picture'];
			$tmp['profile'] 	= $session_sess['id_role'];
			// $tmp['level_user'] 	= $session_sess['level_user'];
			
			return $tmp ;
		}
	}
	
	function getnik($am){
		$recah_am = explode('-',$am);
		$valid_am_1 = str_replace("%20", "", $recah_am[0]);
		$valid_am_2 = str_replace(" ", "", $valid_am_1);
		return $valid_am_2;
	}
	
 
/* End of file Template.php */
/* Location: ./application/libraries/Template.php */