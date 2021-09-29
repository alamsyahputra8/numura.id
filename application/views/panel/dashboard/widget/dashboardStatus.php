<style>
	#jmlDraft span {
		font-size: 14px;
	}
</style>
<?PHP 
if(isset($pengaju) and $pengaju !='all' and $noreq !='all'){ $filterpengaju = " and created_by = '".$pengaju."'"; }else{ $filterpengaju='';}
if(isset($noreq) and $noreq !='null' and $noreq !='all'){ $filternoreq = " and no_request='".$noreq."'"; }else{ $filternoreq='';}

$filter = $filterpengaju.$filternoreq;

if(isset($status) and $status !='null' and $status !='all'){ 
	if($status =='approve'){
		$getTotal 		= $this->db->query("SELECT * from sbrdoc where status in (4)".$filter."")->num_rows();
		$getPending 	= 0;
		$getApproved 	= $this->db->query("SELECT * from sbrdoc where status in (4)".$filter."")->num_rows();
		$getRejected 	= 0;
	}else if($status =='pending'){
		$getTotal 		= $this->db->query("SELECT * from sbrdoc where status not in (0,4,5)".$filter."")->num_rows();
		$getPending 	= $this->db->query("SELECT * from sbrdoc where status not in (0,4,5)".$filter."")->num_rows();
		$getApproved 	= 0;
		$getRejected 	= 0;
	}else if($status =='reject'){
		$getTotal 		= $this->db->query("SELECT * from sbrdoc where status in (5)".$filter."")->num_rows();
		$getPending 	= 0;
		$getApproved 	= 0;
		$getRejected 	= $this->db->query("SELECT * from sbrdoc where status in (5)".$filter."")->num_rows();
	}	
}else{ 
	$getTotal 		= $this->db->query("SELECT * from sbrdoc where status not in (0)".$filter."")->num_rows();
	$getPending 	= $this->db->query("SELECT * from sbrdoc where status not in (0,4,5)".$filter."")->num_rows();
	$getApproved 	= $this->db->query("SELECT * from sbrdoc where status in (4)".$filter."")->num_rows();
	$getRejected 	= $this->db->query("SELECT * from sbrdoc where status in (5)".$filter."")->num_rows();
}
?>
<div class="row">
	<div class="col-lg-4">
		<div class="kt-portlet kt-portlet--solid-default kt-portlet--height-fluid kt-portlet--bordered"> 
			<div class="kt-portlet__body">
				<div class="row">
					<div class="col-sm-3 text-center">
						<img src="<?PHP echo base_url(); ?>images/icon_check.svg" class="img-responsive" style="max-width: 90%">
					</div>
					<div class="col-sm-9">
						<div class="text-success">Approved</div>
						<h4 class="valuebgdash">
							<a class="text-success" id="jmlDraft"><?PHP echo $getApproved; ?> <span>Record</span></a>
						</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="kt-portlet kt-portlet--solid-default kt-portlet--height-fluid kt-portlet--bordered">
			<div class="kt-portlet__body">
				<div class="row">
					<div class="col-sm-3 text-center">
						<img src="<?PHP echo base_url(); ?>images/icon_pending.svg" class="img-responsive" style="max-width: 90%">
					</div>
					<div class="col-sm-9">
						<div class="text-warning">Pending</div>
						<h4 class="valuebgdash">
							<a class="text-warning" id="jmlDraft"><?PHP echo $getPending; ?> <span>Record</span></a>
						</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4">
		<div class="kt-portlet kt-portlet--solid-default kt-portlet--height-fluid kt-portlet--bordered">
			<div class="kt-portlet__body">
				<div class="row">
					<div class="col-sm-3 text-center">
						<img src="<?PHP echo base_url(); ?>images/icon_reject.svg" class="img-responsive" style="max-width: 90%">
					</div>
					<div class="col-sm-9">
						<div class="text-danger">Rejected</div>
						<h4 class="valuebgdash">
							<a class="text-danger" id="jmlDraft"><?PHP echo $getRejected; ?> <span>Record</span></a>
						</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
						