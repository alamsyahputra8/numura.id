<?PHP
$q 			= "
				select a.*, 
				(select name from user where userid=a.replyby) as replyby,
				(select messages from outbox where id_inbox=a.id_inbox) as replymsg 
				from inbox a where id_inbox='$idinbox'
				";
$getData	= $this->query->getDatabyQ($q);
$data 		= array_shift($getData);

$subject 	= $data['subject'];
$from 		= $data['name'];
$emailto 	= $data['email'];
$messages 	= $data['message'];
$balas 		= $data['reply'];
$replyby 	= $data['replyby'];
$replydate 	= $this->formula->nicetime($data['replydate']);
$replymsg 	= $data['replymsg'];
?>

<style>
.kt-subheader {display: none}
@media (min-width: 1025px) {
	.kt-header--fixed.kt-subheader--fixed.kt-subheader--enabled .kt-wrapper {
	    padding-top: 69px;
	}
}
</style>
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
				</span>
				<h3 class="kt-portlet__head-title"><i class="flaticon-email-black-circular-button"></i> Messages</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
						<?PHP if ($balas=='N') { ?>
						<button class="btn btn-warning btn-icon-sm"><i class="flaticon-warning-sign"></i> Belum di Balas</button>
						<?PHP } else { ?>
						<a href="<?PHP echo base_url(); ?>panel" class="btn btn-default btn-icon-sm">
							<i class="flaticon-envelope"></i> Back to Dashboard
						</a>
						&nbsp;
						<button class="btn btn-success btn-icon-sm"><i class="flaticon2-checkmark"></i> Sudah di Balas oleh : <?PHP echo $replyby; ?></button>
						<?PHP } ?>
					</div>
				</div>
			</div>
		</div>

		<div class="kt-portlet__body">
			<div class="row">
				<div class="col-lg-2 col-sm-12">From</div>
				<div class="col-lg-10 col-md-10 col-sm-12">
					<b>: <?PHP echo $from; ?></b>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-2 col-sm-12">Email</div>
				<div class="col-lg-10 col-md-10 col-sm-12">
					: <?PHP echo $emailto; ?>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-2 col-sm-12">Subject</div>
				<div class="col-lg-10 col-md-10 col-sm-12">
					: <?PHP echo $subject; ?>
				</div>
			</div>
			<div class="kt-separator kt-separator--space-sm  kt-separator--border-dashed"></div>

			<div class="row">
				<div class="col-lg-2 col-sm-12">Messages</div>
				<div class="col-lg-10 col-md-10 col-sm-12">
					: <?PHP echo $messages; ?>
				</div>
			</div>
		</div>
	</div>

	<div id="gagalinsert" class="alert alert-warning alert-elevate kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-warning"></i></div>
		<div class="alert-text">
			<strong>Failed!</strong> Change a few things up and try submitting again.
		</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div id="suksesinsert" class="alert alert-success fade show kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-black"></i></div>
		<div class="alert-text">Success!</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<div id="suksesdelete" class="alert alert-secondary fade show kt-hidden" role="alert">
		<div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
		<div class="alert-text">Your data has been deleted!</div>
		<div class="alert-close">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true"><i class="la la-close"></i></span>
			</button>
		</div>
	</div>

	<?PHP if ($balas=='Y') { ?>
	<div class="kt-portlet kt-portlet--mobile">
		<div class="kt-portlet__head kt-portlet__head--lg">
			<div class="kt-portlet__head-label">
				<span class="kt-portlet__head-icon">
				</span>
				<h3 class="kt-portlet__head-title"><i class="flaticon-chat"></i> Reply Messages</h3>
			</div>
			<div class="kt-portlet__head-toolbar">
				<div class="kt-portlet__head-wrapper">
					<div class="kt-portlet__head-actions">
					</div>
				</div>
			</div>
		</div>

		<div class="kt-portlet__body">
			<div class="row">
				<div class="col-lg-2 col-sm-12">Reply By</div>
				<div class="col-lg-10 col-md-10 col-sm-12">
					<b>: <?PHP echo $replyby; ?></b>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-2 col-sm-12">Reply Date</div>
				<div class="col-lg-10 col-md-10 col-sm-12">
					: <?PHP echo $replydate; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-sm-12">Messages :</div>
				<div class="col-lg-12 col-md-10 col-sm-12">
					<?PHP echo $replymsg; ?>
				</div>
			</div>
		</div>
	</div>
	<?PHP } ?>

	<?PHP if ($balas=='N') { ?>
	<div class="kt-portlet kt-portlet--mobile" id="replymsgtouser">
		<form class="kt-form kt-form--label-right" id="forminsert" enctype="multipart/form-data">
			<div class="kt-portlet__head kt-portlet__head--lg">
				<div class="kt-portlet__head-label">
					<span class="kt-portlet__head-icon">
					</span>
					<h3 class="kt-portlet__head-title"><i class="flaticon-chat"></i> Reply Messages</h3>
				</div>
				<div class="kt-portlet__head-toolbar">
					<div class="kt-portlet__head-wrapper">
						<div class="kt-portlet__head-actions">
						</div>
					</div>
				</div>
			</div>

			<div class="kt-portlet__body">
				<div class="row">
					<label class="col-form-label col-lg-2 col-sm-12">Content *</label>
					<div class="col-lg-10 col-md-9 col-sm-12">
						<div class='input-group'>
							<input type="hidden" name="idinbox" id="idinbox" value="<?PHP echo $idinbox; ?>">
							<textarea name="content" class="form-control summernote" id="content"></textarea>
						</div>
					</div>
				</div>
			</div>

			<div class="kt-portlet__foot">
				<div class="kt-form__actions text-right">
					<a href="<?PHP echo base_url(); ?>panel" class="btn btn-default btn-icon-sm">
						<i class="flaticon-envelope"></i> Back to Dashboard
					</a>
					&nbsp;
					<button type="submit" class=" btn btn-brand btn-elevate btn-icon-sm" id="saveinsert">
						<i class="flaticon2-telegram-logo"></i>
						Send Messages
					</button>
				</div>
			</div>
		</form>
	</div>
	<?PHP } ?>
</div>
<!-- end:: Content -->