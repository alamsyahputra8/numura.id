<?PHP 
$getPengaju		= $this->db->query("SELECT * from user")->result_array();
?>
<script src="http://code.highcharts.com/highcharts.js"></script>
<style>
	.mt2rem {margin-top: 2rem;}
	.valuebgdash {margin-top: 0.5rem; margin-bottom: -1rem;}
	.kt-portlet--solid-danger { background: #c0392b!important; }
	#circlebg {
		font-size: 2rem;
	    line-height: 1rem;
	    text-align: center;
	    box-shadow: 0px 3px 10px rgba(0,0,0,.3);
	    z-index: 2;
	    width: 150px;
	    height: 150px;
	    border-radius: 100%;
	    position: absolute;
	    left: 36%;
    	top: 21%;
	    padding-top: 10%;
	}
	#circlebg span {font-size: 1rem;}
	#loadermini {
		width: 100%;
		height: calc(100% - 20px);
		background: #FFF url('<?PHP echo base_url(); ?>images/loadermini.gif') center no-repeat;
		background-size: 80px auto;
	}
</style>
<!-- <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

	
	<div class="row">
		<div class="col-lg-12">
			<div class="form-group row">
				<div class="col-lg-4 col-md-4 col-sm-12">
					<div class='input-group'>
						<select class="form-control m-select2" id="kt_select2_1" name="Nama Pengaju">
							<option value='all'>All AM</option>
						<?PHP 
						foreach($getPengaju as $rowPengaju){
							echo "<option value='".$rowPengaju['userid']."'>".$rowPengaju['name']."</option>";
						}
						?>			
						</select>
					</div>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12">
					<div class='input-group'>
						<input type="hidden" name="noreq" class="form-control" id="noreq" placeholder="No Request">
						<button type="button" id="filterall" class="btn btn-label-dark btn-pill">All</button>
						<button type="button" id="filterapprove" class="btn btn-label-success btn-pill">Approve</button>
						<button type="button" id="filterpending"class="btn btn-label-warning btn-pill">Pending</button>
						<button type="button" id="filterreject" class="btn btn-label-danger btn-pill">Reject</button>
					</div>
				</div>
			</div>

			<div class="" id="dashboardStatus">
				<div id="loadermini" id="loaderstat"></div> 
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-6" id="dashboardDonut">
			<div id="loadermini" id="loaderdonut"></div> 			
		</div>
		<div class="col-md-6" id="dashboardAM">
			<div id="loadermini" id="loaderam"></div> 			
		</div>
		
	</div>
</div> -->