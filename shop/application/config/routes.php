<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_cofntroller/my_method
*/
$route['default_controller']	= 'viewpanel';


// ROUTE PANEL PARWATHA
$route['panel'] 				= 'viewpanel';
$route['panel/ceklogin'] 		= 'login/ceklogin';
$route['panel/loginotp'] 		= 'login/loginotp';
$route['panel/userprofile'] 	= 'viewpanel/userprofile';
$route['panel/roles'] 			= 'viewpanel/roles';
$route['panel/workflow'] 		= 'viewpanel/workflow';
$route['panel/user'] 			= 'viewpanel/user';
$route['panel/customer'] 		= 'viewpanel/customer';
$route['panel/kategori'] 		= 'viewpanel/kategori';
$route['panel/produk'] 		    = 'viewpanel/produk';
$route['panel/configparam'] 	= 'viewpanel/configparam';
$route['workgroup'] 			= 'viewpanel/workgroup';

// DATA PESANAN
$route['pesanan'] 				= 'viewpanel/pesanan';
$route['detailpesanan/(:any)'] 	= 'viewpanel/detailpesanan/$1';
$route['printpesanan/(:any)'] 	= 'viewpanel/printpesanan/$1';
$route['printcard/(:any)'] 		= 'viewpanel/printcard/$1';
$route['printkaos/(:any)'] 		= 'viewpanel/printkaos/$1';
$route['printpaper/(:any)'] 	= 'viewpanel/printpaper/$1';
$route['printkarakter/(:any)'] 	= 'viewpanel/printkarakter/$1';

// DATA PENGIRIMAN
$route['pengiriman'] 				= 'viewpanel/pengiriman';
$route['detailpengiriman/(:any)'] 	= 'viewpanel/detailpengiriman/$1';
$route['printpengiriman/(:any)'] 	= 'viewpanel/printpengiriman/$1';

// DATA PEMBAYARAN
$route['payment'] 				= 'viewpanel/payment';

// DATA PEMBAYARAN
$route['stok'] 					= 'viewpanel/stok';
$route['detailstok/(:any)'] 	= 'viewpanel/detailstok/$1';
$route['stokpjg'] 				= 'viewpanel/stokpjg';
$route['detailstokpjg/(:any)'] 	= 'viewpanel/detailstokpjg/$1';

// DATA DIGITAL INVITATION
$route['digitalinv'] 			= 'viewpanel/digitalinv';

$route['panel/config'] 			= 'viewpanel/config';
$route['panel/log'] 			= 'viewpanel/log';
$route['panel/logout'] 			= 'login/logout';

// SET PASSWORD
$route['setpassword'] 			= 'viewpanel/setpassword';
$route['widget/(:any)/(:any)/(:any)/(:any)'] = 'viewpanel/widget/$1/$2/$3/$4';

$route['resultadmin/(:any)'] 	= 'viewpanel/resultadmin/$1';

$route['cek'] 					= 'welcome/cekkk';

$route['404_override'] 			= 'viewpanel/error';
$route['translate_uri_dashes'] 	= FALSE;
