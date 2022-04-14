<?PHP 
$sitedata   = array_shift($getSiteData); 
?>

<?PHP $this->load->view('theme/polo/plugin1'); ?>
<link href='<?PHP echo base_url(); ?>assets/polo/js/plugins/components/fullcalendar/fullcalendar.min.css' rel='stylesheet' />

        <?PHP $this->load->view('theme/polo/topbar'); ?>

        <?PHP $this->load->view('theme/polo/header'); ?>

        <!-- Page title -->
        <section id="page-title" class="page-title-center text-light background-overlay-dark" style="background:url('<?PHP echo base_url(); ?>images/content/<?PHP echo $background; ?>') no-repeat center; background-size: 100% auto;">
            <div class="container">
                <div class="page-title">
                    <!--span class="post-meta-category"><a href="#"><?PHP echo $menu; ?></a></span-->
                    <h1>Download</h1>
                    <!-- <div class="small m-b-20"><?PHP echo $dataPage['last_update']; ?> | <a href="#">by <?PHP echo $dataPage['update_by']; ?></a></div> -->
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
                <div class="row">
                    <div class="col-lg-12">
                        <div id="calendar"></div>
                    </div>
                </div>

                <!-- <div class="col-md-12">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="tabledata">
                        <thead>
                            <tr>
                                <th>EVENT</th>
                                <th>TANGGAL</th>
                                <th>LOKASI</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>EVENT</th>
                                <th>TANGGAL</th>
                                <th>LOKASI</th>
                            </tr>
                        </tfoot>
                    </table> -->
                    <!-- <div id="portfolio" class="grid-layout portfolio-3-columns" data-margin="20"> -->
                        <?PHP //$this->load->view('front/content/downloadmore', $posts); ?>
                    <!-- </div> -->
                <!-- </div> -->

               <!--  <div id="showMore">
                    <a class="loadmoredata btn btn-rounded btn-light"><i class="icon-refresh-cw"></i>  Load More Posts</a>
                </div> -->
            </div>
        </section>

        <?PHP $this->load->view('theme/polo/footer'); ?>
        <script src='<?PHP echo base_url(); ?>assets/polo/js/plugins/components/moment.min.js'></script>
        <script src='<?PHP echo base_url(); ?>assets/polo/js/plugins/components/fullcalendar/fullcalendar.min.js'></script>
        <script>
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,basicDay'
                },
                defaultDate: '<?PHP echo date('Y-m-d'); ?>',
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: [
                    <?PHP
                    $getData   = $this->query->getDatabyQ("select * from event order by 1");
                    foreach ($getData as $data) {
                    ?>
                    {
                        title: '<?PHP echo $data['nama']; ?>',
                        url: '<?PHP echo base_url(); ?>event/<?PHP echo $data['link']; ?>',
                        start: '<?PHP echo $data['tanggal']; ?>',
                        className: 'fc-event-info'
                    },
                    <?PHP } ?>
                ]
            });
        </script>

        <!-- <script type="text/javascript">
        var page = 1;
        // $(window).scroll(function() {
        //     if($(window).scrollTop() + $(window).height() >= $(document).height()) {
        //         page++;
        //         loadMoreData(page);
        //     }
        // });
        $(document).on('click', '.loadmoredata', function(e){
            page++;
            loadMoreData(page);
        });

        function loadMoreData(page){
            $.ajax({
                url: '?page=' + page,
                type: "get",
                beforeSend: function(){
                    $('.showMore').fadeIn();
                }
            })
            .done(function(data)
            {
                if(data == " "){
                    $('.showMore').html("No more records found");
                    return;
                }
                $('.showMore').fadeOut();
                $("#portfolio").append(data);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                  alert('server not responding...');
            });
        }
        </script>

        <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

        <script>
        $('#tabledata tfoot th').each( function () {
            var title = $(this).text();
            if(title=='ACTIONS' || title=='No'){
                $(this).html('');               
            }else{
                $(this).html( '<input type="text" class="btn-filter" name="'+title+'" id="'+title+'" placeholder="Search" />' );                
            }
        } );    
        var tabledata = $('#tabledata').DataTable({
            //responsive: true,

            // Pagination settings
            
            // read more: https://datatables.net/examples/basic_init/dom.html

            lengthMenu: [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, "All"]],

            pageLength: 10,

            language: {
                'lengthMenu': 'Display _MENU_',
                'emptyTable': `
                            <div class="row" style="padding: 20px;">
                                <div class="col-sm-12">
                                    <div><img src="<?PHP echo base_url(); ?>images/icon/notfound.png"></div><br>
                                </div>
                            </div>`
            },
            searchDelay: 500,
            processing: true,
            serverSide: true,
            order: [[ 1, "desc" ]],
            ajax: {
                url: '<?PHP echo base_url(); ?>core/getdatadownloadfront',
                type: 'POST',
                data: {
                    // parameters for custom backend script demo
                    columnsDef: [
                        'event', 'lokasi', 'tanggal',],
                },
            },
            columns: [
                {data: 'event'},
                {data: 'lokasi'},
                {data: 'tanggal'},
            ],
            columnDefs: [
                {
                    targets: 2,
                    orderable: false,
                },
            ],
        });
        tabledata.on( 'order.dt search.dt', function () {
            tabledata.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();

        tabledata.columns().every( function () {
             var that = this;
             $( 'input', this.footer() ).keyup(delay(function (e) {
                if ( that.search() !== this.value ) {
                     that
                         .search( this.value )
                         .draw();
                 }
            }, 1000));
        } );
        </script> -->