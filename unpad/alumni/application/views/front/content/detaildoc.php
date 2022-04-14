<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "
            select
                a.*,
                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Document' AND xa.data = a.id_doc ORDER BY xa.date_time DESC limit 1)as update_by,
                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Document' AND xa.data = a.id_doc ORDER BY xa.date_time DESC limit 1)as last_update
            from
            file_doc a
            where id_doc='$id_doc'
            order by id_file desc
            ";
$gPage      = $this->query->getDatabyQ($qPage);
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

<!--script type="text/javascript">
    $( "#topbar" ).removeClass( "topbar-transparent" );
    $( "#header" ).removeAttr( "data-transparent" );
</script-->

<style>
.bolddesc span { font-size: 500; }
.team-image {width: 30%!important; padding: 10px;}
.team-members.team-members-left .team-member .team-desc { width: 70%!important; }
.team-image img { width: auto!important; max-height: 110px!important; }
</style>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

		<!-- Page title -->
        <section id="page-title" class="page-title-center text-light background-overlay-dark" style="background:url('<?PHP echo base_url(); ?>images/bgcover.png') no-repeat top center; background-size: 100% auto;">
            <div class="container">
                <div class="page-title">
                    <a href="<?PHP echo base_url(); ?>page/<?PHP echo $doclink; ?>"><span class="post-meta-category"><?PHP echo $menuname; ?></span></a>
                    <a href="<?PHP echo base_url(); ?>page/<?PHP echo $doclink; ?>"><h1><?PHP echo $folder; ?></h1></a>
                    <div class="small m-b-20"><?PHP echo $last_update; ?> | <a href="#">by <?PHP echo $update_by; ?></a></div>

                </div>
            </div>
        </section>
        <!-- end: Page title -->

        <section id="page-content" class="sidebar-right no-border">
            <div class="container">
                <div class="row">
                    <!-- post content -->
                    <div class="content col-lg-12">
                    	<div class="row team-members team-members-left team-members-shadow m-b-40">
		                    <?PHP
		                    $cek = $this->query->getNumRowsbyQ($qPage)->num_rows();

		                    if ($cek>0) {
		                    foreach($gPage as $data) {
		                    ?>
		                    <div class="col-lg-6">
		                    	<a href="<?PHP echo base_url(); ?>images/document/<?PHP echo $data['file_doc']; ?>" target="_blank" data-toggle="tooltip" data-placement="top" title="" data-original-title="View File <?PHP echo $data['name_doc']; ?>">
			                        <div class="team-member">
			                            <div class="team-image">
			                                <center><img src="<?PHP echo base_url(); ?>images/icon/docicon.png"></center>
			                            </div>
			                            <div class="team-desc bolddesc">
			                                <h3><?PHP echo $data['name_doc']; ?></h3>
                                            <span><i class="icon-user11"></i> <?PHP echo $data['update_by']; ?></span><br>
                                            <span><i class="icon-clock1"></i> <?PHP echo $data['last_update']; ?></span>
			                            </div>
			                        </div>
			                    </a>
		                    </div>
		                    <?PHP } } else { ?>
		                    	<h3 class="text-center">We are sorry, No data available.</h3>
		                    <?PHP } ?>
		                </div>
		                <!--END: Team members left-->
                    </div>

                </div>
            </div>
        </section>

        <?PHP $this->load->view('theme/polo/footer'); ?>