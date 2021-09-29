<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "
            select
                a.*,
                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Menus' AND xa.data = a.id_menu ORDER BY xa.date_time DESC limit 1)as update_by,
                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu='Manage Menus' AND xa.data = a.id_menu ORDER BY xa.date_time DESC limit 1)as last_update
            from
            menu_site a
            where id_menu='$idmenu'
            ";
$gPage      = $this->query->getDatabyQ($qPage);
$dataPage   = array_shift($gPage);

$qWork      = "
            select
                a.*,
                (select menu from menu_site where id_menu=a.id_menu) as menu,
                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu in ('Manage Album') AND xa.data = a.id_album ORDER BY xa.date_time DESC limit 1)as update_by,
                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu in ('Manage Album') AND xa.data = a.id_album ORDER BY xa.date_time DESC limit 1)as last_update
            from
            (
                select base.*, 1 as type from (
                    select * from album
                ) as base
            ) as a
            where id_menu='$idmenu'
            order by id_album desc
            ";
            //echo "<pre>".$qWork."</pre>";
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>


        <!-- Page title -->
        <section id="page-title" class="page-title-center text-light background-overlay-dark" style="background:url('<?PHP echo base_url(); ?>images/content/<?PHP echo $background; ?>') no-repeat center; background-size: 100% auto;">
            <div class="container">
                <div class="page-title">
                    <!--span class="post-meta-category"><a href="#"><?PHP echo $menu; ?></a></span-->
                    <h1><?PHP echo $dataPage['menu']; ?></h1>
                    <div class="small m-b-20"><?PHP echo $dataPage['last_update']; ?> | <a href="#">by <?PHP echo $dataPage['update_by']; ?></a></div>
                    <!--div class="align-center">
                        <a class="btn btn-xs btn-slide btn-facebook" href="#">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                        <a class="btn btn-xs btn-slide btn-twitter" href="#" data-width="100">
                            <i class="fab fa-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <a class="btn btn-xs btn-slide btn-instagram" href="#" data-width="118">
                            <i class="fab fa-instagram"></i>
                            <span>Instagram</span>
                        </a>
                        <a class="btn btn-xs btn-slide btn-googleplus" href="mailto:#" data-width="80">
                            <i class="far fa-envelope"></i>
                            <span>Mail</span>
                        </a>
                    </div-->

                </div>
            </div>
        </section>
        <!-- end: Page title -->

        <style>
        .post-item-description p, .post-item-description h1, .post-item-description h2, .post-item-description h3, .post-item-description h4, .post-item-description h5, .post-item-description h6 { color: #FFF!important; }
        .table-bordered, .table-bordered th, .table-bordered td {border: none!important;}
        </style>

         <!-- WORKS -->
        <section class="p-t-60">
            <div class="container">
                <div class="heading-text heading-section text-center">
                    <h2 class="text-white">Latest Work</h2>
                    <!--span class="text-white lead"></span-->
                </div>
                <!-- Portfolio -->
                <?PHP
                $cekWork        = $this->query->getNumRowsbyQ($qWork)->num_rows();
                if ($cekWork>0) {
                ?>
                <div id="portfolio" class="grid-layout portfolio-3-columns" data-margin="20">
                    <?PHP
                    $gWork      = $this->query->getDatabyQ($qWork);
                    foreach($gWork as $data) {
                        $id         = $data['id_album'];
                        $file       = $data['icon'];
                        $type       = $data['type'];
                        $link       = $data['link'];

                        if ($type=='1') {
                            echo '
                                <div class="portfolio-item img-zoom ct-foto">
                                    <div class="portfolio-item-wrap">
                                        <div class="portfolio-image">
                                            <a href="#"><img src="'.base_url().'images/gallery/'.$file.'" alt=""></a>
                                        </div>
                                        <div class="portfolio-description">
                                            <!--a title="Preview" data-lightbox="image" href="'.base_url().'images/gallery/'.$file.'">
                                                <i class="fa fa-search"></i>
                                            </a-->
                                            <a data-id="'.$id.'" data-target="#detailworks" data-toggle="modal" class="btndetailworks" href="#">
                                                <i class="fa fa-search"></i>
                                            </a>
                                            <!--a href="'.base_url().'works/'.$link.'" data-lightbox="ajax">
                                            	<i class="fa fa-search"></i>
                                            </a-->
                                        </div>
                                    </div>
                                </div>
                            ';
                        } else {
                            $embed      = str_replace('https://www.youtube.com/watch?v=','https://www.youtube.com/embed/',$file);

                            echo '
                                <div class="portfolio-item img-zoom ct-video">
                                   <div class="portfolio-item-wrap">
                                        <div class="portfolio-image">
                                            <a href="#">
                                                <iframe width="1280" height="720" src="'.$embed.'?rel=0&amp;showinfo=0" allowfullscreen></iframe>
                                            </a>
                                        </div>
                                        <div class="portfolio-description">
                                            <a title="Video Youtube" data-lightbox="iframe" href="'.$file.'"><i class="fa fa-play"></i></a>
                                            <a href="'.$file.'" target="_blank"><i class="fa fa-link"></i></a>
                                        </div>
                                    </div>
                                </div>
                            ';
                        }
                    }
                    ?>
                </div>
                <?PHP
                } else {
                    echo '
                    <div><h3 class="text-center text-white">We are sorry, No data available.</h3></div>
                    ';
                }
                ?>
                <!-- end: Portfolio -->
                <?PHP if ($cekWork>3) { ?>
                <div>
                    <center><button type="button" class="btn btn-rounded btn-dark">MORE WORKS</button></center>
                </div>
                <?PHP } ?>
            </div>

        </section>
        <!-- end: WORKS -->

        <style>
        	.relativeposition {
        		position: relative!important;
        	}
        </style>
        <div class="modal fade show" id="detailworks" tabindex="-2" role="modal" aria-labelledby="modal-label-3" style="z-index: 1041;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="titledetail" class="modal-title"></h4>
                        <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    </div>
                    <div class="modal-body">
                    	<div class="container" id="datadetailworks">
                    		<div class="loaderdetailworks"><center>Please Wait...</center></div>
	                    </div>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-b" type="button">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TEAM -->
        <section class="no-border">
            <div class="container">
                <div class="heading-text heading-section text-center">
                    <h2 class="text-white">Work With Us</h2>
                    <p class="text-white">
                        <a href="<?PHP echo base_url(); ?>page/contact">Drop us a call or an E-mail to start working on your creative projects.</a>
                    </p>
                </div>
            </div>
        </section>
        <!-- end: TEAM -->

        <?PHP $this->load->view('theme/polo/footer'); ?>

        <script>
        	$(document).on('click', '.btndetailworks', function(e){
			    e.preventDefault();

			    var uid = $(this).data('id'); // get id of clicked row

			    $('#dynamic-content').hide(); // hide dive for loader
			    $('#modal-loader').show();  // load ajax loader

			    $('#datadetailworks').html('<div class="loaderdetailworks"><center>Please Wait...</center></div>');
			    
			    $.ajax({
			        url: '<?PHP echo base_url(); ?>core/modaldetailworks',
			        type: 'POST',
			        data: 'id='+uid,
			        dataType: 'json'
			    })
			    .done(function(data){
			        $('#dynamic-content').hide(); // hide dynamic div
			        $('#dynamic-content').show(); // show dynamic div
			        
			        $('#titledetail').html(data.title);

			        $("#datadetailworks").load( "<?PHP echo base_url(); ?>works/"+data.id_album+"", function() {
						$(".loaderdetailworks").fadeOut('slow');
					});

			        $('#modal-loader').hide();    // hide ajax loader
			    })
			    .fail(function(){
			        $('.modal-body').html('<i class="glyphicon glyphicon-info-sign"></i> Something went wrong, Please refresh page...');
			    });
			});
        </script>