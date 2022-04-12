<?PHP 
$sitedata   = array_shift($getSiteData); 

$qPage      = "select * from event where link='$id'";
$gPage      = $this->query->getDatabyQ($qPage);
$dPage      = array_shift($gPage);
$idevent    = $dPage['id_event'];
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

        <!-- Page title -->
        <section id="page-title" class="page-title-center text-light background-overlay-dark" style="background:url() no-repeat center; background-size: 100% auto;">
            <div class="container">
                <div class="page-title">
                    <!--span class="post-meta-category"><a href="#"><?PHP echo $menu; ?></a></span-->
                    <h1><?PHP echo $dPage['nama']; ?></h1>
                    <div class="small m-b-20"><?PHP echo $this->formula->TanggalIndo($dPage['tanggal']); ?></div>
                    <!-- <div class="small m-b-20"><?PHP echo $dataPage['last_update']; ?> | <a href="#">by <?PHP echo $dataPage['update_by']; ?></a></div> -->
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

        <section id="page-content" class="no-border">

            <div class="container">
                <div id="results" class="loader row">
                </div>
                <div>
                    <div id="loaderpage" class="row">
                        <div class="loader col-lg-12">
                            <div style="padding: 30px;"><div class="loader04"></div></div>
                        </div>
                    </div>
                    <center id="loadmorebtn"><button type="button" class="btn btn-rounded btn-dark btnloadmore">LOAD MORE</button></center>
                </div>
            </div>
        </section>

        <?PHP $this->load->view('theme/polo/footer'); ?>

        <script type="text/javascript">
        $(document).ready(function() {
            var total_record = 0;
            var total_groups = <?PHP echo $total_data; ?>;  
            var id           = <?PHP echo $idevent; ?>;
            $('#results').load("<?php echo base_url() ?>core/loadmoreEvent", {'id':id,'group_no':total_record}, function() {$('#loaderpage').fadeOut(); total_record++;});
            // $(window).scroll(function() {       
            //     if($(window).scrollTop() + $(window).height() == $(document).height()) {
            $(document).on('click', '.btnloadmore', function(e){
                    if(total_record <= total_groups) {
                      loading = true; 
                      $('#loaderpage').fadeIn(); 
                      $.post('<?PHP echo site_url() ?>core/loadmoreEvent',{'id':id,'group_no': total_record},
                        function(data){ 
                            if (data != "") {
                                $("#results").append(data);                 
                                $('#loaderpage').fadeOut();
                                total_record++;
                            } else {
                                $('#loaderpage').fadeOut();
                                $('#loadmorebtn').html('<button type="button" class="btn btn-rounded btn-default">NO MORE DATA</button>');
                            }
                        });     
                    }
            //     }
            });
        });
        </script>