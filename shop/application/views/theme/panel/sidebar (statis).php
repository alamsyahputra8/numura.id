<?PHP 
$userdata 	= $this->session->userdata('sess'); 
$getMenu 	= $this->query->getData('roles_detail a LEFT JOIN roles b on a.id_profile=b.id_profile LEFT JOIN menu_panel c on a.id_menu=c.id_menu','b.*, c.*',"WHERE b.id_profile='".$userdata['id_profile']."' and type='0' order by sort asc");
?>
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
	<!-- sidebar -->
	<div class="sidebar">
		<div class="user-panel text-center">
			<div class="image">
				<img src="<?PHP echo base_url(); ?>images/user/<?PHP echo $userdata['picture']; ?>" class="img-circle" alt="User Image">
			</div>
			<div class="info">
				<p><?PHP echo $userdata['name']; ?></p>
				<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
			</div>
		</div>
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			
			<?PHP
			foreach($getMenu as $datamenu) { 
				$CekSub	= $this->query->getNumRows('menu_panel','*',"WHERE parent='".$datamenu['id_menu']."'")->num_rows();
				if ($CekSub>0) { $classub = 'treeview'; } else { $classub = ''; }
			?>
				<li class="<?PHP echo $classub; ?> <?php if($this->uri->uri_string() == $datamenu['url']) { echo 'active'; } ?>">
					<a href="<?PHP echo base_url().''.$datamenu['url']; ?>">
						<i class="<?PHP echo $datamenu['icon']; ?>"></i> <span><?PHP echo $datamenu['menu']; ?></span>
						<?PHP if ($CekSub>0) { ?>
							<span class="pull-right-container">
								<span class="pull-right"><i class="ti-angle-down"></i></span>
							</span>
						<?PHP } ?>
					</a>
					<?PHP 
					if ($CekSub>0) { 
					$getSubMenu 	= $this->query->getData('menu_panel','*',"WHERE parent='".$datamenu['id_menu']."' order by sort asc");
					?>
						<ul class="treeview-menu">
							<?PHP foreach ($getSubMenu as $datasub) { ?>
							<li class=""><a href="<?PHP echo base_url().''.$datasub['url']; ?>"><?PHP echo $datasub['menu']; ?></a></li>
							<?PHP } ?>
						</ul>
					<?PHP } ?>
				</li>
			<?PHP } ?>
			
			<li class="<?php if($this->uri->uri_string() == 'panel') { echo 'active'; } ?>">
				<a href="<?PHP echo base_url(); ?>panel"><i class="ti-dashboard"></i> <span>Dashboard</span></a>
			</li>
			<li class="<?php if($this->uri->uri_string() == 'panel/about') { echo 'active'; } ?>">
				<a href="<?PHP echo base_url(); ?>panel"><i class="ti-heart"></i> <span>About Company</span></a>
			</li>
			<li class="treeview <?php if($this->uri->uri_string() == 'panel/categoryroom' or $this->uri->uri_string() == 'panel/rooms' or $this->uri->uri_string() == 'panel/facility' or $this->uri->uri_string() == 'panel/availability') { echo 'active'; } ?>">
				<a href="#">
					<i class="ti-layout-grid2-alt"></i> <span>Rooms</span> 
					<span class="pull-right-container">
						<span class="pull-right"><i class="ti-angle-down"></i></span>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?PHP echo base_url(); ?>panel/categoryroom">Category Room</a></li>
					<li class=""><a href="<?PHP echo base_url(); ?>panel/rooms">Rooms</a></li>
					<li class=""><a href="<?PHP echo base_url(); ?>panel/facility">Rooms Facilities</a></li>
					<!--li class=""><a href="<?PHP echo base_url(); ?>panel/availability">Availability Room</a></li-->
				</ul>
			</li>
			<li class="<?php if($this->uri->uri_string() == 'panel/promo') { echo 'active'; } ?>">
				<a href="<?PHP echo base_url(); ?>panel"><i class="ti-gift"></i> <span>Promotion</span></a>
			</li>
			<li class="<?php if($this->uri->uri_string() == 'panel/event') { echo 'active'; } ?>">
				<a href="<?PHP echo base_url(); ?>panel/event"><i class="ti-calendar"></i> <span>Event</span></a>
			</li>
			<!--li class="<?php if($this->uri->uri_string() == 'panel/gallery') { echo 'active'; } ?>">
				<a href="<?PHP echo base_url(); ?>panel"><i class="ti-image"></i> <span>Gallery</span></a>
			</li-->
			<li class="<?php if($this->uri->uri_string() == 'panel/testi') { echo 'active'; } ?>">
				<a href="<?PHP echo base_url(); ?>panel"><i class="ti-heart"></i> <span>Testimonial</span></a>
			</li>
			<li class="<?php if($this->uri->uri_string() == 'panel/member') { echo 'active'; } ?>">
				<a href="<?PHP echo base_url(); ?>panel"><i class="ti-user"></i> <span>Member</span></a>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="ti-money"></i> <span>Transaction</span>
					<span class="pull-right-container">
						<span class="pull-right"><i class="ti-angle-down"></i></span>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class="<?php if($this->uri->uri_string() == 'mappingreport') { echo 'mappingreport'; } ?>"><a href="<?PHP echo base_url(); ?>mappingreport">Reservation</a></li>
					<li class="<?php if($this->uri->uri_string() == 'manageuser') { echo 'manageuser'; } ?>"><a href="<?PHP echo base_url(); ?>manageuser">Payment</a></li>
				</ul>
			</li>
			<li class="treeview">
				<a href="#">
					<i class="ti-email"></i> <span>Email Blast</span>
					<span class="pull-right-container">
						<span class="pull-right"><i class="ti-angle-down"></i></span>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class="<?php if($this->uri->uri_string() == 'mappingreport') { echo 'mappingreport'; } ?>"><a href="<?PHP echo base_url(); ?>mappingreport">List Email</a></li>
					<li class="<?php if($this->uri->uri_string() == 'manageuser') { echo 'manageuser'; } ?>"><a href="<?PHP echo base_url(); ?>manageuser">Send Mail</a></li>
				</ul>
			</li>
			
			
			<li class="header">CONFIGURATION</li>
			<li class="treeview <?php if($this->uri->uri_string() == 'panel/user' or $this->uri->uri_string() == 'panel/roles') { echo 'active'; } ?>">
				<a href="#">
					<i class="ti-user"></i> <span>Management User</span>
					<span class="pull-right-container">
						<span class="pull-right"><i class="ti-angle-down"></i></span>
					</span>
				</a>
				<ul class="treeview-menu">
					<li class=""><a href="<?PHP echo base_url(); ?>panel/user">Data User</a></li>
					<li class=""><a href="<?PHP echo base_url(); ?>panel/roles">Manage Roles</a></li>
				</ul>
			</li>
			<li class="<?php if($this->uri->uri_string() == 'panel/menus') { echo 'active'; } ?>">
				<a href="<?PHP echo base_url(); ?>panel"><i class="ti-menu"></i> <span>Manage Menus</span></a>
			</li>
			<li class="<?php if($this->uri->uri_string() == 'panel/slider') { echo 'active'; } ?>">
				<a href="<?PHP echo base_url(); ?>panel/slider"><i class="ti-image"></i> <span>Slider</span></a>
			</li>
			<li class="<?php if($this->uri->uri_string() == 'panel/config') { echo 'active'; } ?>">
				<a href="<?PHP echo base_url(); ?>panel"><i class="ti-world"></i> <span>Site Configuration</span></a>
			</li>
		</ul>
	</div> <!-- /.sidebar -->
</aside>