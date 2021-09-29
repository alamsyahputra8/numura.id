<?PHP
$qWork      = "
            select
                a.*,
                (select menu from menu_site where id_menu=a.id_menu) as menu,
                (SELECT xb.name as  update_by FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu in ('Manage Photos','Manage Videos') AND xa.data = a.id_file ORDER BY xa.date_time DESC limit 1)as update_by,
                (SELECT DATE_FORMAT(xa.date_time, '%d-%b-%y %H:%i:%s') as last_update FROM `data_log` xa LEFT JOIN user xb ON xa.userid=xb.userid 
                WHERE xa.menu in ('Manage Photos','Manage Videos') AND xa.data = a.id_file ORDER BY xa.date_time DESC limit 1)as last_update
            from
            (
                select * from (
                    select id_video as id_file,id_menu, id_album, video as file, '2' as type from videos 
                    union 
                    select id_photo as id_file,id_menu, id_album, picture as file, '1' as type from photos
                ) as base
            ) as a
            where id_album='$id'
            order by id_file desc
            ";
            //echo "<pre>".$qWork."</pre>";
?>
<?PHP $this->load->view('theme/polo/plugin1'); ?>
<style>
	#datadetailworks #scrollTop, #datadetailworks #footer { display: none; }
</style>
<div class="container">
<?PHP
$cekWork        = $this->query->getNumRowsbyQ($qWork)->num_rows();
if ($cekWork>0) {
?>
<div id="portfolio" class="grid-layout portfolio-3-columns" data-margin="20">
   	<?PHP
    $gWork      = $this->query->getDatabyQ($qWork);
    foreach($gWork as $data) {
        $id         = $data['id_file'];
        $file       = $data['file'];
        $type       = $data['type'];

        if ($type==2) { $size = ''; } else { $size = ''; }

        if ($type=='1') {
            echo '
                <div class="portfolio-item '.$size.' img-zoom ct-foto">
                    <div class="portfolio-item-wrap">
                        <div class="portfolio-image">
                            <a href="#"><img src="'.base_url().'images/gallery/'.$file.'" alt=""></a>
                        </div>
                        <div class="portfolio-description">
                            <a title="Sample Photo" data-lightbox="image" href="'.base_url().'images/gallery/'.$file.'">
                                <i class="fa fa-expand"></i>
                            </a>
                        </div>
                    </div>
                </div>
            ';
        } else {
            $embed      = str_replace('https://www.youtube.com/watch?v=','https://www.youtube.com/embed/',$file);

            echo '
                <div class="portfolio-item '.$size.' img-zoom ct-video">
                   <div class="portfolio-item-wrap">
                        <div class="portfolio-image">
                            <a href="#">
                                <iframe width="1280" height="720" src="'.$embed.'?rel=0&amp;showinfo=0" allowfullscreen></iframe>
                            </a>
                        </div>
                        <div class="portfolio-description">
                            <a title="Video Youtube" data-lightbox="iframe" href="'.$file.'"><i class="fa fa-play"></i></a>
                            <a href="'.$file.'" target="_blank"><i class="fa fa-link"></i></a>
                            </a>
                        </div>
                    </div>
                </div>
            ';
        }
    }
    ?>
</div>
<div class="clearfix"></div>
<?PHP
} else {
    echo '
    <div><h3 class="text-center text-white">We are sorry, No data available.</h3></div>
    ';
}
?>
</div>
<?PHP $this->load->view('theme/polo/footer'); ?>