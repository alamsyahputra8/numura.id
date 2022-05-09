<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "
            select
                a.*,
                (select menu from menu_site where id_menu=a.id_menu) as menu,
                (select count(*) from file_doc where id_doc=a.id_doc) as jmldoc,
                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Document' AND xa.data = a.id_doc ORDER BY xa.date_time DESC limit 1)as update_by,
                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Document' AND xa.data = a.id_doc ORDER BY xa.date_time DESC limit 1)as last_update
            from
            document a
            where id_menu='$idmenu'
            order by id_doc desc
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
.team-image img { max-height: 150px!important; }
</style>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

		<!-- Page title -->
        <section id="page-title" class="page-title-center background-overlay-dark" data-bg-parallax="<?PHP echo base_url(); ?>images/content/<?PHP echo $background; ?>">
            <div class="container">
                <div class="page-title">
                    <span class="post-meta-category">&nbsp;</span>
                    <h1><?PHP echo $menu; ?></h1>
                    <div class="small m-b-20">&nbsp;</div>

                </div>
            </div>
        </section>
        <!-- end: Page title -->

        <section id="page-content" class="sidebar-right">
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
		                    	<a href="<?PHP echo base_url(); ?>doc/<?PHP echo $data['link']; ?>">
			                        <div class="team-member">
			                            <div class="team-image">
			                                <img src="<?PHP echo base_url(); ?>images/icon/iconfolder2.svg">
			                            </div>
			                            <div class="team-desc bolddesc">
			                                <h3><?PHP echo $data['title']; ?></h3>
			                                <hr>
			                                <span><i class="icon-documents"></i> <?PHP echo $data['jmldoc']; ?> Documents</span><br>
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