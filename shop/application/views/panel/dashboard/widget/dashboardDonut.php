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
<!--begin::Portlet-->
<div class="kt-portlet kt-portlet--responsive-mobile">
	<div class="kt-portlet__body">
		<div id="circlebg"><?PHP echo $getTotal; ?><span><br>Special Request</span></div>
		<?PHP if($getTotal > 0){ ?>
			<div id="chartdonut" style="width: 450px; height: 310px; margin: -3rem auto;"></div>
		<?PHP } else { ?>
			<div id="empty" style="width: 450px; height: 310px; margin: -3rem auto;"></div>
		<?PHP } ?>
	</div>
</div>
<script>
var data = [{
    "id": "idData",
    "name": "Data",
    "data":[
    	<?PHP if($getApproved > 0) { ?>
        {name: 'Approved', y: <?PHP echo $getApproved; ?>, color: '#0abb87', sliced: true, 
            dataLabels: {
                format: '<span style="font-weight: 500">{point.percentage:.1f}%<br><span style="font-size: 1rem; color: #999; font-weight: 300;">Approved</span></span>',
                style: {
                    fontSize: "2rem",
                }
            }
        },
        <?PHP } ?>
       	<?PHP if($getRejected > 0) { ?>
        {name: 'Rejected', y: <?PHP echo $getRejected; ?>, color: '#c0392b', sliced: true,
            dataLabels: {
                format: '<span style="font-weight: 500">{point.percentage:.1f}%<br><span style="font-size: 1rem; color: #999; font-weight: 300;">Rejected</span></span>',
                style: {
                    fontSize: "2rem",
                }
            }
        },
        <?PHP } ?>
       	<?PHP if($getPending > 0) { ?>
        {name: 'Pending', y: <?PHP echo $getPending; ?>, color: '#f39c12', sliced: true,
            dataLabels: {
                format: '<span style="font-weight: 500">{point.percentage:.1f}%<br><span style="font-size: 1rem; color: #999; font-weight: 300;">Pending</span></span>',
                style: {
                    fontSize: "2rem",
                }
            }
        },
	    <?PHP } ?>
      ]
}];
Highcharts.chart('chartdonut', {
    chart: {
        type: 'pie',
        backgroundColor: 'transparent',
    	height: 310,
        style: {
            fontFamily: 'Poppins'
        },
        plotShadow: false,
    },
  credits: {
      enabled: false
  },
    plotOptions: {
        pie: {
            center: ['50%', '50%'],
            innerSize: '100%',
            borderWidth: 20,
            borderColor: null,
            slicedOffset: 0,
            dataLabels: {
                connectorWidth: 3,
                color: '#555'
            }
        }
    },
  title: {
        verticalAlign: 'middle',
        floating: true,
        text: ''
  },
  legend: {
  },
    series: data,
});

</script>
						