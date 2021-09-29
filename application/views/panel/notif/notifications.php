<?PHP
$userdata		= $this->session->userdata('sesspwt'); 
$userid			= $userdata['userid'];

$qCek 			= "
				SELECT * from sbrhistory where is_read=0 and send_to='$userid'
				";
$cek			= $this->db->query($qCek)->num_rows();

if ($cek>0) {
?>

	<?PHP
	$q 			= "
					SELECT a.*,
						(SELECT id from sbrdoc where no_request=a.no_request) idsbrdoc,
						(SELECT name from sbrteknis.user where userid=a.created_by) nameuser,
						(SELECT picture from sbrteknis.user where userid=a.created_by) pict
					from sbrhistory a where is_read=0 and send_to='$userid' order by 1 desc
				";
	$getData 	= $this->db->query($q)->result_array();
	foreach ($getData as $data) {
		$id 		= $data['idsbrdoc'];
		$noreq 		= str_replace('/','-',$data['no_request']);
		$genValue 	= $data['nameuser'];
		$nickname 	= explode(' ',trim($genValue));

		if ($data['pict']=='') {
			$hidden 	= 'kt-hidden';
		} else {
			$hidden 	= '';
		}
		
		if ($data['action']=='new') {
			$notiftext 	= 'Pengajuan baru dari';
		} else if ($data['action']=='republish') {
			$notiftext 	= 'Perbaikan Pengajuan dari';
		} else if ($data['action']=='escalation') {
			$notiftext 	= 'Pengajuan disetujui oleh';
		} else if ($data['action']=='reject') {
			$notiftext 	= 'Pengajuan tidak disetujui oleh';
		} else if ($data['action']=='approve') {
			$notiftext 	= 'Pengajuan disetujui oleh';
		} else if ($data['action']=='return') {
			$notiftext 	= 'Pengajuan dikembalikan oleh';
		} else if ($data['action']=='update') {
			$notiftext 	= 'Perbaikan Pengajuan oleh';
		} else if ($data['action']=='dispatch') {
			$notiftext 	= 'Dispatch Pengajuan oleh';
		}
	?>
		<a href="<?PHP echo base_url(); ?>docdetail/<?PHP echo $noreq; ?>" class="kt-notification-v2__item kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x">
			<div class="kt-notification-v2__item-icon">
		        <div class="kt-user-card__avatar">
		        	<img class="<?PHP echo $hidden; ?>" alt="Pic" src="<?PHP echo base_url(); ?>images/user/<?PHP echo $data['pict']; ?>" />

					<?PHP if ($data['pict']=='') { ?>
					<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
					<span class="kt-badge kt-badge--username kt-badge--unified-success kt-badge--lg kt-badge--rounded kt-badge--bold"><?PHP echo substr($data['nameuser'],0,1); ?></span>
					<?PHP } ?>
				</div>
			</div>
			<div class="kt-notification-v2__itek-wrapper">
				<div class="kt-notification-v2__item-title">
					<?PHP echo $notiftext; ?> <?PHP echo $nickname[0]; ?>
				</div>
				<div class="kt-notification-v2__item-desc">
					<i class="la la-file"></i> <?PHP echo $data['no_request']; ?>
				</div>
				<div class="kt-notification-v2__item-desc">
					<?PHP echo $this->formula->nicetime($data['created_at']); ?>
				</div>
			</div>
		</a>
	<?PHP } ?>
</div>
<?PHP } else { ?>
	<div class="kt-grid kt-grid--ver col-sm-12" style="min-height: 200px;">
        <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
            <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                All caught up!
                <br>No new notifications.
            </div>
        </div>
    </div>
<?PHP } ?>
<!-- 
<a href="#" class="kt-notification-v2__item kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x">
	<div class="kt-notification-v2__item-icon">
        <div class="kt-user-card__avatar">
			<img src="<?PHP echo base_url(); ?>images/default.png" alt="image">
		</div>
	</div>
	<div class="kt-notification-v2__itek-wrapper">
		<div class="kt-notification-v2__item-title">
			Pengajuan Baru Dari AM
		</div>
		<div class="kt-notification-v2__item-desc">
			2 hours ago
		</div>
	</div>
</a>

<a href="#" class="kt-notification-v2__item kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x">
	<div class="kt-notification-v2__item-icon">
        <div class="kt-user-card__avatar">
			<img src="<?PHP echo base_url(); ?>images/default.png" alt="image">
		</div>
	</div>
	<div class="kt-notification-v2__itek-wrapper">
		<div class="kt-notification-v2__item-title">
			Return Pengajuan Dari IMA
		</div>
		<div class="kt-notification-v2__item-desc">
			2 hours ago
		</div>
	</div>
</a>

<a href="#" class="kt-notification-v2__item kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x">
	<div class="kt-notification-v2__item-icon">
        <div class="kt-user-card__avatar">
			<img src="<?PHP echo base_url(); ?>images/default.png" alt="image">
		</div>
	</div>
	<div class="kt-notification-v2__itek-wrapper">
		<div class="kt-notification-v2__item-title">
			Pengajuan Disetujui Oleh
		</div>
		<div class="kt-notification-v2__item-desc">
			2 hours ago
		</div>
	</div>
</a>

<a href="#" class="kt-notification-v2__item kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x">
	<div class="kt-notification-v2__item-icon">
        <div class="kt-user-card__avatar">
			<img src="<?PHP echo base_url(); ?>images/default.png" alt="image">
		</div>
	</div>
	<div class="kt-notification-v2__itek-wrapper">
		<div class="kt-notification-v2__item-title">
			Pengajuan Tidak Disetujui Oleh
		</div>
		<div class="kt-notification-v2__item-desc">
			2 hours ago
		</div>
	</div>
</a>

<a href="#" class="kt-notification-v2__item kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x">
	<div class="kt-notification-v2__item-icon">
        <div class="kt-user-card__avatar">
			<img src="<?PHP echo base_url(); ?>images/default.png" alt="image">
		</div>
	</div>
	<div class="kt-notification-v2__itek-wrapper">
		<div class="kt-notification-v2__item-title">
			Pengajuan di Eskalasi ke
		</div>
		<div class="kt-notification-v2__item-desc">
			2 hours ago
		</div>
	</div>
</a> -->