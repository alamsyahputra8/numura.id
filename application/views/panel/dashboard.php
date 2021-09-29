<?PHP 
$this->load->view('/theme/panel/plugin'); 
error_reporting(0);
?>
<script src="<?PHP base_url(); ?>assets/panel/highcharts/highcharts.js"></script>
<script src="<?PHP base_url(); ?>assets/panel/highcharts/highcharts-3d.js"></script>

<style>
.bgfiltercol {
	box-shadow: 0 0px 20px rgba(0,0,0,0.2) inset;
	background-color:rgba(0,0,0,0.1);
}
#loadermini {
	width: 100%;
	height: 290px;
	background: #FFF url('<?PHP echo base_url(); ?>images/loadermini.gif') center no-repeat;
	background-size: 80px auto;
}

@media (max-width:600px) {
	.forresponh4 { text-align:center; }
	.itemresponsaal {
		padding-right: 0px;
		margin-top: 20px;
		padding-left: 0px
	}
	#bgdashboard { padding: 10px; }
	.equalheight {
		min-height: auto!important;
		height: auto!important;
	}
	.mtcont {
		margin-top: 10px;
	}
	.headerfilter {
		margin-top:0px; margin-bottom: auto; padding:0px; background: transparent; border: none;
	}
}
@media (min-width:601px) {
	.equalheight {
        min-height: 290px;
    }
	.mtcont {
		margin-top: 80px;
	}
	.headerfilter {
		margin-top:60px; margin-bottom: -60px; padding:0px; background: transparent; border: none;
	}
}
.trcol1 {
	background: rgba(255,255,255,0.33);
}
.trcol2 {
	background: #cbcbcb;
}
.boxblue {
	background: #3f7a74;
}
.boxred {
	background: #a74844;
}
.boxlblue {
	background: #44546b;
}
#boxcont {
	color: #FFF;
	border-radius: 10px;
	text-align: center;
	padding:5px;
	margin-top: 20px;
}
#boxcont h3 {
	font-size: 14px;
    font-weight: 400;
}
#boxcont h1 {
	margin-top: 0px;
    font-weight: bold;
}
#boxcont h1 span {
	font-size: 21px;
}
.t-blue {
	background: #3f7a74;
}
.t-black {
	background: #1e1e1e;
}
.t-red {
	background: #a74844;
}
.t-green {
	background: #03a34b;
}
.t-l-blue {
	background: #1e4e76;
}
.t-kecukupan {
	padding: 5px;
	text-align: center;
	color: #FFF;
	font-weight: bold;
	font-size: 13px;
	cursor: pointer;
}
.c-kecukupan {
	// background: #FFF;
	text-align: center;
	// border-top: 2px solid #FFF;
	font-weight: bold;
	margin-bottom: 10px;
	cursor: pointer;
}
.titlebox {
	font-size: 14px;
	font-weight: bold;
	color: #50919a;
}
.padding0 {padding:0px 5px;}
.textcenterth { margin-bottom: 0px; }
.textcenterth th { 
	text-align: center; 
	background: #2e2e2e;
    color: #FFF;
	border-top: 1px solid #5d5353!important;
}
.textcenterth td {
	padding: 5px!important;
	text-align:center;
}
.titletd { color: #222; text-align:left!important; }
.titletd.grey { background: #d0cece; }
.titletd.orange { background: #fc922e; }
.titletd.blue { background: #05accb; }
.titletd.yellow { background: #fac402; }
.titletd.purple { background: #8b5aa4; }
.titletd.red { background: #f92d2d; }
.titletd.darkred { background: #bf2d2d; }
.titletd.green { background: #16b35c; }
.barwar.grey { background-color: #d0cece; }
.barwar.orange { background-color: #fc922e; }
.barwar.blue { background-color: #05accb; }
.barwar.yellow { background-color: #fac402; }
.barwar.purple { background-color: #8b5aa4; }
.barwar.red { background-color: #f92d2d; }
.barwar.darkred { background-color: #bf2d2d; }
.barwar.green { background-color: #16b35c; }

.progress-bar-rev { background-color: #39BF30; }
.progress-bar-rev .tooltip-arrow { border-top: 5px solid #39BF30!important; }
.progress-bar-rev .tooltip-inner { background-color: #39BF30!important; }

.progress-bar-st { background-color: #FFC100; }
.progress-bar-st .tooltip-arrow { border-top: 5px solid #FFC100!important; }
.progress-bar-st .tooltip-inner { background-color: #FFC100!important; }

.progress-bar-nonpots { background-color: #50754C; }
.progress-bar-nonpots .tooltip-arrow { border-top: 5px solid #50754C!important; }
.progress-bar-nonpots .tooltip-inner { background-color: #50754C!important; }

.progress-bar-pots { background-color: #CFB85C; }
.progress-bar-pots .tooltip-arrow { border-top: 5px solid #CFB85C!important; }
.progress-bar-pots .tooltip-inner { background-color: #CFB85C!important; }

.progress-bar-sustain { background-color: #539A32; }
.progress-bar-sustain .tooltip-arrow { border-top: 5px solid #539A32!important; }
.progress-bar-sustain .tooltip-inner { background-color: #539A32!important; }

.progress-bar-scal { background-color: #8D7C55; }
.progress-bar-scal .tooltip-arrow { border-top: 5px solid #8D7C55!important; }
.progress-bar-scal .tooltip-inner { background-color: #8D7C55!important; }

.tabletooltipstyle .tooltip-arrow { border-top: 5px solid #FFF!important; }
.tabletooltipstyle .tooltip-inner { background-color: #FFF!important; color: #1e1e1e!important; }

.tooltip.right {top: -4px!important;}
.min290 {
	min-height: 290px;
}
.min145 {
	min-height:140px;
}
.min100 { min-height: 100px; }
.bgwhite {background: #FFF;}
.no-padding { padding: 0px; }
.tiles.red { background:#f92d2d; }
.tiles.green { background: #16b35c; }
#titledetr2 {
	font-size: 24px;
    font-weight: bold;
    position: absolute;
    bottom: 15px;
    left: 10px;
	color: rgba(0,0,0,0.5)!important;
}
#subtitledetr2 {
	position: absolute;
    bottom: 0px;
    left: 10px;
    font-weight: bold;
    letter-spacing: 1px;
	font-size: 10px;
	color: rgba(0,0,0,0.5)!important;
}
#valr2det {
    font-weight: bold;
    font-size: 22px;
    position: absolute;
    top: 0px;
    right: 15px;
}
#valr2det span {
	font-size:12px;
}
#subvalr2det{
	position: absolute;
    top: 44px;
    right: 15px;
    color: #FFF;
	text-transform: uppercase;
	font-size:11px;
}
.text-green { color: #16b35c; }
.text-red { color: #f35958; }

#newtitlepercolr2 #titledetr2 {
	bottom: 5px;
	font-size: 22px;
}
#newtitlepercolr2 #subtitledetr2 {
	bottom: 22px;
}
</style>

<div class="content-wrapper" id="pageContent">
	<!-- Main content -->
	<div class="content mtcont">
		<div class="row">
			<!-- KOLOM 1 -->
			<div id="bgdashboard" style="position:relative;">
				<div class="col-sm-5 col-md-5 padding0">
					<div class="panel panel-bd equalheight" id="tabledashboard">
						<div id="loadermini" class="loadertable"></div>
					</div>
				</div>
				
				<div class="col-sm-7 col-md-7 padding0">
					<div class="panel panel-bd equalheight" id="viewsisaanggaran" style="background:transparent;">
						<div id="loadermini"  class="loadersa"></div>
					</div>
				</div>
				
				<div class="clearfix"></div>
				
				<!-- KOLOM 2 -->
				<div class="col-sm-6 col-md-6 padding0">
					<div class="panel panel-bd equalheight forboxshadow" id="viewachseg">
						<div id="loadermini" class="loaderachseg"></div>
					</div>
				</div>
				
				<div class="col-sm-2 col-md-2 padding0">
					<div class="panel panel-bd equalheight" id="viewsisalo" style="background:transparent;">
						<div id="loadermini" class="loadersisalo"></div>
					</div>
				</div>
				
				<div class="col-sm-4 col-md-4 padding0">
					<div class="panel panel-bd forboxshadow equalheight" id="viewamach">
						<div id="loadermini"  class="loaderamach"></div>
					</div>
				</div>
				
				<div class="clearfix"></div>
				<div class="col-sm-12 col-md-12" id="viewlatesttrans" style="display: ;">
					<div id="loaderviewlatesttrans"  class="loaderviewlatesttrans"></div>
				</div>
				
			</div>
			<?PHP $this->load->view('panel/dashboard/detail'); ?>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<script>
$(window).bind("load", function() {
	$("#tabledashboard").load( "dashboard/table", function() {
		$(".loadertable").fadeOut('slow');
	});
	
	$("#viewsisaanggaran").load( "dashboard/sisaanggaran", function() {
		$(".loadersa").fadeOut('slow');
	});
	
	$("#viewachseg").load( "dashboard/achseg", function() {
		$(".loaderachseg").fadeOut('slow');
	});
	
	$("#viewamach").load( "dashboard/acham", function() {
		$(".loaderamach").fadeOut('slow');
	});
	
	$("#viewsisalo").load( "dashboard/sisalo", function() {
		$(".loadersisalo").fadeOut('slow');
	});
	
	$("#viewlatesttrans").load( "dashboard/latesttrans", function() {
		$(".loaderviewlatesttrans").fadeOut('slow');
	});
});
</script>
<?PHP $this->load->view('/theme/panel/plugin_js'); ?>
