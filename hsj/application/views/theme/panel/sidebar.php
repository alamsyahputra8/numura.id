<?PHP 
$userdata 	= $this->session->userdata('sesselop'); 
$getMenu 	= $this->query->getData('role_menu a LEFT JOIN role b on a.id_role=b.id_role LEFT JOIN menu c on a.id_menu=c.id_menu','b.*, c.*',"WHERE b.id_role='".$userdata['id_role']."' and type='0' and parent='0' order by sort asc");
$getMenuC 	= $this->query->getData('role_menu a LEFT JOIN role b on a.id_role=b.id_role LEFT JOIN menu c on a.id_menu=c.id_menu','b.*, c.*',"WHERE b.id_role='".$userdata['id_role']."' and type='1' and parent='0' order by sort asc");
?>
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
	<!-- sidebar -->
	<div class="sidebar">
		
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			
			<?PHP
			foreach($getMenu as $datamenu) { 
				$CekSub	= $this->query->getNumRows('menu','*',"WHERE parent='".$datamenu['id_menu']."'")->num_rows();
				if ($CekSub>0) { $classub = 'treeview'; } else { $classub = ''; }
			?>
				<li class="<?PHP echo $classub; ?> <?php if($this->uri->uri_string() == $datamenu['url']) { echo ''; } ?>">
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
					$getSubMenu 	= $this->query->getData('menu','*',"WHERE parent='".$datamenu['id_menu']."' and id_menu in (select id_menu from role_menu where id_role='".$userdata['id_role']."') order by sort asc");
					?>
						<ul class="treeview-menu">
							<?PHP foreach ($getSubMenu as $datasub) { ?>
							<li class=""><a href="<?PHP echo base_url().''.$datasub['url']; ?>"><?PHP echo $datasub['menu']; ?></a></li>
							<?PHP } ?>
						</ul>
					<?PHP } ?>
				</li>
			<?PHP } ?>
			
			<li class="header">CONFIGURATION</li>
			<?PHP
			foreach($getMenuC as $datamenu) { 
				$CekSub	= $this->query->getNumRows('menu','*',"WHERE parent='".$datamenu['id_menu']."'")->num_rows();
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
					$getSubMenu 	= $this->query->getData('menu','*',"WHERE parent='".$datamenu['id_menu']."' and id_menu in (select id_menu from role_menu where id_role='".$userdata['id_role']."') order by sort asc");
					?>
						<ul class="treeview-menu">
							<?PHP foreach ($getSubMenu as $datasub) { ?>
							<li class=""><a href="<?PHP echo base_url().''.$datasub['url']; ?>"><?PHP echo $datasub['menu']; ?></a></li>
							<?PHP } ?>
						</ul>
					<?PHP } ?>
				</li>
			<?PHP } ?>
			
		</ul>
	</div> <!-- /.sidebar -->
</aside>