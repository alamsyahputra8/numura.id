<?PHP 
$url		= $this->uri->uri_string();
$getImg 	= $this->query->getData('menu','*',"WHERE link='$url'");
$data 		= array_shift($getImg);
?>
<!--Breadcrumb Section-->
<section id="breadcrumb-section" data-bg-img="<?PHP echo base_url(); ?>images/page/<?PHP echo $data['background']; ?>">
	<div class="inner-container container">
		<div class="ravis-title">
			<div class="inner-box">
				<div class="title"><?PHP echo $data['menu']; ?></div>
				<div class="sub-title"><?PHP echo $data['description']; ?></div>
			</div>
		</div>

		<div class="breadcrumb">
			<ul class="list-inline">
				<li><a href="./">Home</a></li>
				<li class="current"><a href="#"><?PHP echo $data['menu']; ?></a></li>
			</ul>
		</div>
	</div>
</section>
<!--End of Breadcrumb Section-->