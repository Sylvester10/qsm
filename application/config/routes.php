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
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'web';
$route['translate_uri_dashes'] = FALSE;


/* ===== General ===== */
$route['404_override'] = 'errors/error404';
$route['error404'] = 'errors/error404';
$route['no_js'] = 'errors/no_js';
$route['print_report/template'] = 'misc/print_report_template';  
$route['refresh_page'] = 'misc/refresh_page';


/* ===== Installation ===== */
$route['install/(:num)'] = 'installation/free_trial_install/$1'; //$1 is Plan ID
$route['install'] = 'installation/free_trial_install';
$route['buy/(:num)'] = 'installation/buy_install/$1'; //$1 is Plan ID
$route['buy'] = 'installation/buy_install';
$route['buy_other/(:num)'] = 'installation/buy_other_install/$1'; //$1 is Plan ID
$route['buy_other'] = 'installation/buy_other_install';
$route['email_confirmation/(:num)/(:any)'] = 'installation/email_confirmation/$1/$2'; //$1 = school_id, $2 = confirmation code



/* ===== Shared: Admin & Staff ===== */
$route['login'] = 'site/admin_staff_login'; //default method, both staff and admin
$route['recover_password'] = 'site/admin_staff_recover_password'; //default method, both staff and admin

/* ===== Admin ===== */
$route['admin_logout'] = 'admin_acc/logout';

/* ===== Staff ===== */
$route['staff_logout'] = 'staff_acc/logout';



/* ===== Shared: Student & Parent ===== */
$route['user_login'] = 'site/student_parent_login';
$route['user_recover_password'] = 'site/student_parent_recover_password';

/* ===== Student ===== */
$route['student_logout'] = 'student_acc/logout';

/* ===== Parent ===== */
$route['parent_logout'] = 'parent_acc/logout';



/* ===== Super Admin ===== */
$route['super_admin_login'] = 'super_admin_acc/login'; 
$route['super_admin_logout'] = 'super_admin_acc/logout';


