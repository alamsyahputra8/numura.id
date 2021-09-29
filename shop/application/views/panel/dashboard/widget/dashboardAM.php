<?PHP 
	if(isset($pengaju) and $pengaju !='all' and $noreq !='all'){ $filterpengaju = " and created_by = '".$pengaju."'"; }else{ $filterpengaju='';}
	if(isset($noreq) and $noreq !='null' and $noreq !='all'){ $filternoreq = " and no_request='".$noreq."'"; }else{ $filternoreq='';}

	

	if(isset($status) and $status !='null' and $status !='all'){ 
		if($status =='approve'){
			$filterstatus = ' and status in (4)';
		}else if($status =='pending'){
			$filterstatus = ' and status not in (0,4,5)';
		}else if($status =='reject'){
			$filterstatus = ' and status in (5)';
		}
	}else{
		$filterstatus = ' and status not in (0)';
	}	
	$filter = $filterpengaju.$filternoreq.$filterstatus;

	$getDataSBR = $this->db->query("
			SELECT * FROM (
				select 
					*,
					(select name from sbronline.user where userid=a.created_by)as name, 
					(select picture from sbronline.user where userid=a.created_by)as picture 
				from 
				sbrdoc a 
			)master where 1=1 ".$filter."")->result_array();
?>
<!--begin::Portlet-->
<div class="kt-portlet kt-portlet--responsive-mobile">
	<div class="kt-portlet__body" style='max-height:285px !important;min-height:285px;overflow-y:scroll !important;'>
			<!--begin::Portlet-->
			<div class="kt-widget4">
				<?PHP foreach($getDataSBR as $data) { 
					   if($data['status'] ==4){
					   		$color = 'success';
					   		$Kstatus = 'Approved';
					   }else if($data['status']==5){
					   		$color = 'danger';
					   		$Kstatus = 'Rejected';
					   }else if($data['status']==0){
					   		$color = 'brand';
					   		$Kstatus = 'Draft';
					   }else{
					   		$color = 'warning';
					   		$Kstatus = 'Pending';
					   }
				?>
				<div class="kt-widget4__item">
					<div class="kt-widget4__pic kt-widget4__pic--pic">
						<img src="<?PHP echo base_url();?>images/user/<?PHP echo $data['picture'];?>" alt="">
					</div>
					<div class="kt-widget4__info">
						<a href="#" class="kt-widget4__username">
							<?PHP echo $data['name'];?>
						</a>
						<p class="kt-widget4__text">
							<?PHP echo $data['nama_project'];?>
						</p>
					</div>
					<a href="#" class="btn btn-sm btn-label-<?PHP echo $color; ?> btn-bold"><?PHP echo $Kstatus;?></a>
				</div>
				<?PHP } ?>
			</div>
		
	</div>
</div>
