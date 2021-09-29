<?PHP 
$userdata 	= $this->session->userdata('sesspwt'); 
$getTypeM	= $this->query->getDatabyQ('select * from menu_type order by sort asc');

$userid 	= $userdata['userid'];
$q 			= "select * from user where userid='$userid'";
$getUser 	= $this->query->getDatabyQ($q);
$dataUser	= array_shift($getUser);

if ($dataUser['picture']=='') {
	$hidden 	= 'kt-hidden';
} else {
	$hidden 	= '';
}
?>

<style>
.kt-aside-menu .kt-menu__nav > .kt-menu__section { margin : 0px; }
.kt-aside .kt-aside-menu { margin-top: 0px; }
#profile-sidebar .kt-user-card .kt-user-card__name {font-size: 1rem;}
#profile-sidebar .kt-user-card .kt-user-card__avatar img {
    border-radius: 100%;
}
/*#profile-sidebar .kt-user-card .kt-user-card__avatar img {
    width: 60px;
    height: 60px;
    border-radius: 4px;
}*/
.kt-aside--minimize #profile-sidebar .kt-notification-item-padding-x {
    padding-left: 0.3rem !important;
    padding-right: 0.3rem !important;
}
.bgbackblue {
	object-fit: contain;
	background-image: linear-gradient(235deg, #1e1e1e, #333);
}
.bgbackblue2 {
    object-fit: contain;
    background-image: linear-gradient(323deg, #1e1e1e, #333);
}
.kt-aside-menu .kt-menu__nav {
    padding-top: 0px;
}
</style>

<button class="kt-aside-close " id="kt_aside_close_btn"><i class="la la-close"></i></button>
<div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" id="kt_aside">

	<!-- begin:: Aside -->
	<div class="kt-aside__brand kt-grid__item bgbackblue2 " id="kt_aside_brand">
		<div class="kt-aside__brand-logo">
			<!-- <a href="<?PHP echo base_url(); ?>">
				<img alt="Logo" src="<?PHP echo base_url(); ?>images/logotel.png" style="max-height: 45px;" />
			</a> -->
		</div>
		<div class="kt-aside__brand-tools">
			<button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler">
				<span><!--begin::Svg Icon | path:/home/keenthemes/www/metronic/themes/metronic/theme/html/demo1/dist/../src/media/svg/icons/General/Other1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
				    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
				        <rect x="0" y="0" width="24" height="24"/>
				        <circle fill="#000000" cx="12" cy="5" r="2"/>
				        <circle fill="#000000" cx="12" cy="12" r="2"/>
				        <circle fill="#000000" cx="12" cy="19" r="2"/>
				    </g>
				</svg><!--end::Svg Icon--></span>

				<span><!--begin::Svg Icon | path:/home/keenthemes/www/metronic/themes/metronic/theme/html/demo1/dist/../src/media/svg/icons/Layout/Layout-left-panel-2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
				    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
				        <rect x="0" y="0" width="24" height="24"/>
				        <path d="M10,4 L21,4 C21.5522847,4 22,4.44771525 22,5 L22,7 C22,7.55228475 21.5522847,8 21,8 L10,8 C9.44771525,8 9,7.55228475 9,7 L9,5 C9,4.44771525 9.44771525,4 10,4 Z M10,10 L21,10 C21.5522847,10 22,10.4477153 22,11 L22,13 C22,13.5522847 21.5522847,14 21,14 L10,14 C9.44771525,14 9,13.5522847 9,13 L9,11 C9,10.4477153 9.44771525,10 10,10 Z M10,16 L21,16 C21.5522847,16 22,16.4477153 22,17 L22,19 C22,19.5522847 21.5522847,20 21,20 L10,20 C9.44771525,20 9,19.5522847 9,19 L9,17 C9,16.4477153 9.44771525,16 10,16 Z" fill="#000000"/>
				        <rect fill="#000000" opacity="0.3" x="2" y="4" width="5" height="16" rx="1"/>
				    </g>
				</svg><!--end::Svg Icon--></span>
			</button>

			<!--
<button class="kt-aside__brand-aside-toggler kt-aside__brand-aside-toggler--left" id="kt_aside_toggler"><span></span></button>
-->
		</div>
	</div>

	<!-- end:: Aside -->

	<!-- begin:: Aside Menu -->
	<div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
		<div id="kt_aside_menu" class="kt-aside-menu " data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500">
			<ul class="kt-menu__nav ">
				<li class="kt-menu__section " id="profile-sidebar" style="padding:0px; height: auto;">
					<!-- <div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x col-sm-12" style="background-image: url('<?PHP echo base_url(); ?>images/sidebar.png')"> -->
					<div class="kt-user-card kt-user-card--skin-dark kt-notification-item-padding-x col-sm-12 bgbackblue" style="">
						<div class="kt-user-card__avatar">
							<img class="<?PHP echo $hidden; ?>" alt="Pic" src="<?PHP echo base_url(); ?>images/user/<?PHP echo $dataUser['picture']; ?>" />
							<?PHP if ($dataUser['picture']=='') { ?>
							<!--use below badge element instead the user avatar to display username's first letter(remove kt-hidden class to display it) -->
							<span class="kt-badge kt-badge--lg kt-badge--rounded kt-badge--bold kt-font-success"><?PHP echo substr($dataUser['name'],0,1); ?></span>
							<?PHP } ?>
						</div>
						<div class="kt-user-card__name">
							<b><?PHP echo $dataUser['name']; ?></b><br>
							<span><?PHP echo $dataUser['username']; ?></span>
						</div>
					</div>
				</li>
				<?PHP
				foreach ($getTypeM as $typemenu) {
					$idtypeM	= $typemenu['id_type'];
					$getMenu 	= $this->query->getData('role_menu a LEFT JOIN role b on a.id_role=b.id_role LEFT JOIN menu c on a.id_menu=c.id_menu','b.*, c.*',"WHERE b.id_role='".$userdata['id_role']."' and type='".$idtypeM."' and parent='0' order by type,sort asc");
					$cekTitle 	= $this->query->getNumRows('role_menu a LEFT JOIN role b on a.id_role=b.id_role LEFT JOIN menu c on a.id_menu=c.id_menu','b.*, c.*',"WHERE b.id_role='".$userdata['id_role']."' and type='".$idtypeM."' and parent='0' order by type,sort asc")->num_rows();
				?>
					<?PHP if ($cekTitle>0) { ?>
					<li class="kt-menu__section ">
						<h4 class="kt-menu__section-text"><?PHP echo $typemenu['type_name']; ?></h4>
						<i class="kt-menu__section-icon flaticon-more-v2"></i>
					</li>
					<?PHP } ?>
					<?PHP
					foreach($getMenu as $datamenu) { 
						$CekSub	= $this->query->getNumRows('menu','*',"WHERE parent='".$datamenu['id_menu']."'")->num_rows();
						if ($CekSub>0) { $classub = 'kt-menu__item--submenu'; $dhover = 'data-ktmenu-submenu-toggle="hover"'; } else { $classub = ''; $dhover = ''; }

						$galactive 	= $datamenu['active_menu'];
						$urlmenuact	= $this->uri->uri_string();
						
						if ($urlmenuact !='panel') {
							if( @strpos( $galactive, $urlmenuact ) !== false) { $active = 'kt-menu__item--active kt-menu__item  kt-menu__item--submenu kt-menu__item--open kt-menu__item--here'; } else { $active = ''; }
						} else {
							if ($this->uri->uri_string()==$galactive) { $active = 'kt-menu__item--active'; } else { $active = ''; }
						}
					?>
					<li class="kt-menu__item  <?PHP echo $classub; ?> <?PHP echo $active; ?>" aria-haspopup="true" <?PHP echo $dhover; ?>>
						<a href="<?PHP echo base_url().''.$datamenu['url']; ?>" class="kt-menu__link kt-menu__toggle"><span class="kt-menu__link-icon">
							<?PHP echo $datamenu['icon']; ?>
							</span><span class="kt-menu__link-text"><?PHP echo $datamenu['menu']; ?></span>
							<?PHP if ($CekSub>0) { ?> <i class="kt-menu__ver-arrow la la-angle-right"></i> <?PHP } ?>
						</a>
						<?PHP 
						if ($CekSub>0) { 
						$getSubMenu 	= $this->query->getData('menu','*',"WHERE parent='".$datamenu['id_menu']."' and id_menu in (select id_menu from role_menu where id_role='".$userdata['id_role']."') order by sort asc");
						?>
						<div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
							<ul class="kt-menu__subnav">
								<li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"><span class="kt-menu__link"><span class="kt-menu__link-text"><?PHP echo $datamenu['menu']; ?></span></span></li>
								<?PHP foreach ($getSubMenu as $datasub) { ?>
								<li class="kt-menu__item " aria-haspopup="true"><a href="<?PHP echo base_url().''.$datasub['url']; ?>" class="kt-menu__link "><i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i><span class="kt-menu__link-text"><?PHP echo $datasub['menu']; ?></span></a></li>
								<?PHP } ?>
							</ul>
						</div>
						<?PHP } ?>
					</li>
					<?PHP } ?>
				<?PHP } ?>
			</ul>
		</div>
	</div>

	<!-- end:: Aside Menu -->
</div>