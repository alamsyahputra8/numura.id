<style>
    .sort-section ul li {
        margin: 5px 10px;
    }
    .hidegal { display: none!important; }
</style>
<!-- Gallery -->
<section id="gallery">
	<div class="inner-container container">

		<div class="ravis-title">
			<div class="inner-box">
				<div class="title">Atrium Premiere Gallery</div>
				<div class="sub-title">Our facilities dedicated to enhancing your well-being</div>
			</div>
		</div>

		<!-- Gallery Container -->
		<div class="gallery-container">
			<div class="sort-section">
				<div class="sort-section-container">
					<div class="sort-handle">Filters</div>
					<ul class="list-inline">
						<!--li><a href="#" data-filter="*" class="active">All</a></li-->
						<?PHP 
					    $getAlbum = $this->query->getData('album','*',"order by album asc");
						$a=0;
						foreach($getAlbum as $album) { $a++;
						if ($a==1) { $act='active'; } else { $act= '';}
						?>
						<li><a href="#" data-filter=".album<?PHP echo $album['id_album']; ?>" class="ravis-btn btn-type-2 <?PHP echo $act; ?>"><?PHP echo $album['album']; ?></a></li>
						<?PHP } ?>
					</ul>
				</div>
			</div>
			<ul class="image-main-box clearfix">
				<?PHP 
				$getPhoto = $this->query->getData('gallery','*',"order by id_img Desc");
				foreach($getPhoto as $photo) { 
				if ($photo['id_album']==5) { $none=''; } else { $none= 'style="display: none;"'; }
				?>
				<li class="item col-xs-6 col-md-4 album<?PHP echo $photo['id_album']; ?>">
					<figure>
						<img src="<?PHP echo base_url(); ?>images/gallery/<?PHP echo $photo['img']; ?>" alt="11"/>
						<a href="<?PHP echo base_url(); ?>images/gallery/<?PHP echo $photo['img']; ?>" class="more-details" data-title="<?PHP echo $photo['caption']; ?>">Enlarge</a>
						<figcaption>
							<h4><?PHP echo $photo['caption']; ?></h4>
						</figcaption>
					</figure>
				</li>
				<?PHP } ?>
			</ul>

			<!--a href="pages/gallery-masonry.html" class="gallery-more-btn ravis-btn btn-type-2">More ...</a-->
		</div>
	</div>
</section>
<!-- End of Gallery -->