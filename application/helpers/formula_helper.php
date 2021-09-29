<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
	function rupiah($angka)
	{
		$jadi = number_format($angka,0,',','.');
		return $jadi;
	}
	
	function tanggal_indo($tanggal)
	{
		$bulan = array (1 =>   'Januari',
					'Februari',
					'Maret',
					'April',
					'Mei',
					'Juni',
					'Juli',
					'Agustus',
					'September',
					'Oktober',
					'November',
					'Desember'
				);
		$split = explode('-', $tanggal);
		return $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
	}
	// function checkingsession(){
		// $CI =& get_instance();
		// $session_sess		= $CI->session->userdata('sess');
		 
		  
		// if(($session_sess == FALSE))
		// {
			// $CI->load->view("login/login");
		// }
		// else
		// {   
			
			// $tmp['logsess'] = $session_sess;
			// $tmp['userid'] = $session_sess['USERID'];
			// $tmp['nama'] = $session_sess['NAMA'];
			// $tmp['username'] = $session_sess['USERNAME'];
			// $tmp['profile'] = $session_sess['ID_PROFILE'];
			
			// return $tmp ;
		// }
	// }
	function explode_fitur($val){
		$str = $val;
		$recah = (explode(",",$str));
		return $recah;
	}  
	
	function fix_date($val){
		if($val == NULL or $val == '0000-00-00 00:00:00' or $val == ''){
			$result = 'NULL';
		}else{
			$result = $val;
		}
		return $result;
	}
	function getRoleInsert($akses,$modal_name,$name_button){
		$a_array = explode(",",trim($akses));
		$clearbuton = '
					<button class="btnclearfilter btn btn-default btn-sm"><i class="fa fa-eraser p-r-5"></i> Clear Filter</button>
					<script>
					$(".btnclearfilter").click(function() {
						$(".table").find("input:text").val(""); 
						var table = $(".table").DataTable();
						table
						 .search("")
						 .columns().search("")
						 .draw();
					});
					$(window).bind("load", function() {
						$(".table tfoot th").each( function () {
							$(this).find("input:text").attr("placeholder" , "");
						});
					});
					</script>
					';
		if(in_array('input',$a_array))
		{
			$button = '
					<button class="btnaddnewdata btn btn-dark btn-elevate btn-icon-sm btn-sm" data-toggle="modal" data-target="#'.$modal_name.'">
						<i class="la la-plus"></i>
						'.$name_button.'
					</button>
					';
		} else {
			$button =  '';	
		}
		
		//return $clearbuton.$button;
		return $button;
	}

	function getRoleBtnAction($akses,$modal_name,$name_button,$actbtn,$classbtn,$iconbtn){
		$a_array = explode(",",trim($akses));
		if(in_array($actbtn,$a_array))
		{
			$button = '
					<button class="btn '.$classbtn.' btn-icon-sm" data-toggle="modal" data-target="#'.$modal_name.'">
						<i class="'.$iconbtn.'"></i>
						'.$name_button.'
					</button>
					';
		} else {
			$button =  '';	
		}
		
		//return $clearbuton.$button;
		return $button;
	}
	function getRoleBtnAction2($akses,$modal_name,$name_button,$actbtn,$classbtn,$iconbtn,$dataid){
		$a_array = explode(",",trim($akses));
		if(in_array($actbtn,$a_array))
		{
			$button = '
					<button class="btn '.$classbtn.' btn-icon-sm" data-toggle="modal" data-target="#'.$modal_name.'" data-id="'.$dataid.'">
						<i class="'.$iconbtn.'"></i>
						'.$name_button.'
					</button>
					';
		} else {
			$button =  '';	
		}
		
		//return $clearbuton.$button;
		return $button;
	}
	function getBtnAction3($akses,$modal_name,$name_button,$actbtn,$classbtn,$iconbtn,$type,$dataid){
		$a_array = explode(",",trim($akses));
		if(in_array($actbtn,$a_array))
		{
			$button = '
					<button class="btn '.$classbtn.' btn-icon-sm" data-toggle="modal" data-target="#'.$modal_name.'" data-type="'.$type.'" data-id="'.$dataid.'">
						<i class="'.$iconbtn.'"></i>
						'.$name_button.'
					</button>
					';
		} else {
			$button =  '';	
		}
		
		//return $clearbuton.$button;
		return $button;
	}
	function getBtnAction($akses,$modal_name,$name_button,$actbtn,$classbtn,$iconbtn,$dataid){
		$a_array = explode(",",trim($akses));
		if(in_array($actbtn,$a_array))
		{
			$button = '
					<button class="btn '.$classbtn.' btn-icon-sm" data-toggle="modal" data-target="#'.$modal_name.'" data-id="'.$dataid.'">
						<i class="'.$iconbtn.'"></i>
						'.$name_button.'
					</button>
					';
		} else {
			$button =  '';	
		}
		
		//return $clearbuton.$button;
		return $button;
	}

	function getRoleInsertCustom($akses,$modal_name,$name_button,$fitur,$btnclass,$icon){
		$a_array = explode(",",trim($akses));

		if(in_array($fitur,$a_array))
		{
			$button = '
					<button class="btn btn-brand '.$btnclass.' btn-icon-sm" data-toggle="modal" data-target="#'.$modal_name.'">
						'.$icon.'
						'.$name_button.'
					</button>
					';
		} else {
			$button =  '';	
		}
		
		//return $clearbuton.$button;
		return $button;
	}

	function getRoleInsertCustomID($akses,$modal_name,$name_button,$fitur,$btnclass,$icon,$id){
		$a_array = explode(",",trim($akses));

		if(in_array($fitur,$a_array))
		{
			$button = '
					<button class="btn btn-brand '.$btnclass.' btn-icon-sm" data-toggle="modal" data-id="'.$id.'" data-target="#'.$modal_name.'">
						'.$icon.'
						'.$name_button.'
					</button>
					';
		} else {
			$button =  '';	
		}
		
		//return $clearbuton.$button;
		return $button;
	}
	
	function getRoleUpdate($akses,$modal_name,$id){
		$a_array = explode(",",trim($akses));
		if(in_array('update',$a_array))
		{
			$button =  'ada';
		} else {
			$button =  '';	
		}
		return $button;
	}
	function getRoleAction($akses,$action,$modal_name,$id){
		$a_array = explode(",",trim($akses));
		if(in_array($action,$a_array))
		{
			$button =  'ada';
		} else {
			$button =  '';	
		}
		return $button;
	}
	function getRoleUpdate_Custom($akses,$modal_name,$id,$tahun){
		$button="";
		$a_array = explode(",",trim($akses));
		if($tahun == date('Y')){
			if(in_array('update',$a_array))
			{
				$button =  '
					<a class="btn btn-sm btn-clean btn-icon btn-icon-md btnupdateM" title="Edit" data-toggle="modal" data-target="#'.$modal_name.'" data-id="'.$id.'">
						<i data-toggle="tooltip" title="Update" class="la la-edit"></i>
                    </a>
					';
			} else {
				$button =  '';	
			}
		}else{
			
		}
		return $button;
	}		
	function getRoleDelete_Custom($akses,$modal_name,$id,$tahun){
		$button="";
		$a_array = explode(",",trim($akses));
		if($tahun == date('Y')){
			if(in_array('delete',$a_array))
			{
				$button =  '
						<button title="Delete" class="btn btn-sm btn-clean btn-icon btn-icon-md btndeleteMenu" data-toggle="modal" data-target="#'.$modal_name.'" data-id="'.$id.'">
							<i class="la la-trash"></i>
						</button>
						';
			} else {
				$button =  '';	
			}
		}else{
			
		}
		return $button;
	}
	function getRoleDelete2($akses,$modal_name,$id){
		$a_array = explode(",",trim($akses));
		if(in_array('delete',$a_array))
		{
			$button =  "<center><a class='btn btn-xs btn-success btnFilePreview' data-toggle='modal' data-target='#FilePreview' data-file='".$id."' data-ext='".pathinfo($id, PATHINFO_EXTENSION)."' data-nomor='".$id."' ><i data-toggle='tooltip' title='".$id."' class='glyphicon glyphicon-fullscreen'></i></a>&nbsp;<a class='btn btn-xs btn-danger btnDeleteImage' data-toggle='modal' data-target='#deleteImage' alt='Delete File Kontrak' data-id='".$id."'><i data-toggle='tooltip' title='Delete' class='glyphicon glyphicon-trash'></i></a></center>";
		} else {
			$button =  "<center><a class='btn btn-xs btn-success btnFilePreview' data-toggle='modal' data-target='#FilePreview' data-file='".$id."' data-ext='".pathinfo($id, PATHINFO_EXTENSION)."' data-nomor='".$id."' ><i data-toggle='tooltip' title='".$id."' class='glyphicon glyphicon-fullscreen'></i></a>&nbsp;</center>";
		}
		return $button;
	}

	function getRoleDelete($akses,$modal_name,$id){
		$a_array = explode(",",trim($akses));
		if(in_array('delete',$a_array))
		{
			$button =  'ada';
		} else {
			$button =  '';	
		}
		return $button;
	}

	function getRoleButton($akses,$modal_name,$id,$class,$fungsi,$title,$icon){
		$a_array = explode(",",trim($akses));
		if(in_array($fungsi,$a_array))
		{
			$button = '
			<a title="'.$title.'" class="btn btn-sm btn-clean btn-icon btn-icon-md '.$class.'" data-toggle="modal" data-target="#'.$modal_name.'" data-id="'.$id.'">
                '.$icon.'
            </a>
			';
		} else {
			$button =  '';	
		}
		return $button;
	}

	function fixURL($uri){
		$result = strtr($uri, array('%20'=>'', ' '=>''));
		$finalresult = strtoupper($result);
		return $finalresult;
	}
	function cleanstring($string) {
	   $string = str_replace(' ', ' ', $string); // Replaces all spaces with hyphens.

	   return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
	} 
	function cleancomma($string) {
	   $string = str_replace(',', ' ', $string); // Replaces all spaces with hyphens.

	   return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string); // Removes special chars.
	} 
/* End of file Template.php */
/* Location: ./application/libraries/Template.php */