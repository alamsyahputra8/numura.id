<body style="margin: 0px; padding: 0px;">
<?PHP
if ($id=='pending') {
	$statuspesanan 	= '(Perlu Diproses)';
	$statusvar 		= '1';
} else if ($id=='proses') {
	$statuspesanan 	= '(Sedang di Diproses)';
	$statusvar 		= '2';
} else if ($id=='send') {
	$statuspesanan 	= '(Sudah di Kirim)';
	$statusvar 		= '3';
} else {
	$statuspesanan = '(All)';
	$statusvar 		= '1,2,3';
}
?>
<style>
	@font-face {
	  font-family: '/fonts/AmstirdamDemo';
	  src: url('AmstirdamDemo.ttf')  format('truetype'), 
	}
	.custname {
		font-family: Amstirdam Demo, AmstirdamDemo;
		color: #b43d32;
		font-size: 1.5cm;
		margin-top: 0.8cm;
		text-transform: capitalize!important;
	}
	.bgkolom {
		background: url('<?PHP echo base_url(); ?>images/thankyoucard.jpg') center no-repeat;
		background-size: auto 100%;
		text-align: center;
	}
</style>

<div style="width: 21cm; margin: 0 auto; border: 1px solid transparent">
<?PHP
$userdata 	= $this->session->userdata('sesspwt'); 
$userid 	= $userdata['userid'];
$role 		= $userdata['id_role'];

$userdata 	= $this->session->userdata('sesspwt'); 
$userid 	= $userdata['userid'];
$role 		= $userdata['id_role'];

if ($role==1) {
	$condpes 	= '';
} else {
	$condpes 	= "and userid='$userid'";
}
$qPes 		= "
			SELECT custom_nama
			FROM pesanan a where status in ($statusvar) $condpes
			order by userid, custom_nama asc
			";
$getPes 	= $this->db->query($qPes)->result_array();
$cekPes 	= $this->db->query($qPes)->num_rows();
if ($cekPes>0) {

	foreach ($getPes as $pes) { 
		echo '
		<div class="bgkolom" style="border: 1px solid rgba(0,0,0,.1); width: 9cm; height: 4.914cm; float:left;">
			<div class="custname"><b>'.$pes['custom_nama'].'</b></div>
		</div>';
	}

} else {
	echo '
	<div class="col-sm-12" style="padding: 10px;">
		Mohon maaf belum ada data pesanan.<br>
		Silahkan membuat pesanan terlebih dahulu di menu Pesanan, atau bisa klik 
		<b><a href="<?PHP echo base_url(); ?>pesanan">disini</a></b>.
	</div>
	';
}
?>
<div style="clear:both;"></div>
</div>
</body>