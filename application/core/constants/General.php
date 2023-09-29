<?php

/* ===== Documentation ===== 
Name: Constants::General
Role: Include
Description: Holds all the constants used by the app. Required in the construct of the core controller, MY_Controller, which makes it global to the entire application.
Author: Nwankwo Ikemefuna
Date Created: 17th May, 2022
*/


$const_software_name = 'Quick School Manager';
$const_software_initials = 'QSM';
$const_software_tagline = 'The Complete School Management System';
$const_software_sub_tagline = 'Easily manage your school processes and activities like enrollment, fee management, Homework, Library Management, Student reports etc. using our simple and simple to use school management software, saving time and money.';
$const_software_keywords = 'School Management System, School Management Software, Manager, School Management Software Solution, School Information System, School Management System in Nigeria, School Management System in Africa, Educational Software, Quick School Software, Atlas School Manager, School Manager ';
$const_software_description = "	
									{$const_software_initials} is a cloud based school management system that helps school owners and administrators manage school proceeses more easily and efficiently. 
									<br /><br />. Our simple yet intuitive dashboard for Admins, Teachers, Students and Parents makes it easiy to use.
								";


//Software Info
define('software_name', $const_software_name);
define('software_initials', $const_software_initials);
define('software_tagline', $const_software_tagline);
define('software_sub_tagline', $const_software_sub_tagline);
define('software_keywords', $const_software_keywords);
define('software_description', $const_software_description);
define('software_vendor', 'Kodebro');
define('software_team', software_name . ' Team');
define('software_website', base_url());
define('software_vendor_site', 'http://kodebro.com');
define('software_logo', base_url('assets/images/logo/logo.png'));

define('user_avatar', base_url('assets/images/user.png'));
define('male_student_avatar', base_url('assets/images/male_student_avatar.png'));
define('female_student_avatar', base_url('assets/images/female_student_avatar.png'));
define('report_passport', base_url('assets/images/report_passport.png'));
define('male_staff_avatar', base_url('assets/images/male_staff_avatar.png'));
define('female_staff_avatar', base_url('assets/images/female_staff_avatar.png'));
define('admin_signature', base_url('assets/images/admin_signature.png'));
define('staff_signature', base_url('assets/images/staff_signature.png'));
define('school_stamp', base_url('assets/images/stamp.jpg'));
define('school_logo_def', software_logo);
define('school_favicon_def', base_url('assets/images/logo/favicon.png'));
define('pdf_icon', base_url('assets/images/pdf_icon.png'));
define('qsm_admin_dash', base_url('assets/images/qsm_admin_dash.png'));


//Config: Web Mail
//Important: Update email (plus settings) in application/config/email
define('software_web_mail', 'notifications@quickschoolmanager.com'); 

//support mail
define('software_support_mail', 'support@quickschoolmanager.com'); 

//info mail
define('software_info_mail', 'info@quickschoolmanager.com'); 

//developer email
define('developer_email', 'developer@quickschoolmanager.com'); 



//Location from IP Address
define('visitor_country', ip_info_safe("Visitor", "Country"));
define('nigerian_currency_code', '8358'); //for storage
define('nigerian_currency', '&#8358;'); //for display


//MySQL-PHP server time difference. Change to zero if both are on same server
define('mysql_time_difference', -1); //if negative, write as -x, else, x
//login refresh time
define('login_refresh_time', 120); //refresh last login every 120 secs (2 mins) if the use is active

//free trial grace period (in days)
define('free_trial_period', 30); 
//annual subscription period (in days)
define('annual_subscription_period', 366); 
//renewal countdown period (in days)
define('renewal_countdown_period', 14); //14 days from date of next renewal


//Demo Account School ID: change as appropriate if school_info id of demo account changes
define('demo_school_id', 123);
//Demo user password
define('demo_user_password', 'is->demo?user->true');
//hash the demo user password
define('demo_user_hashed_password', hash('ripemd128', demo_user_password));


//bespoke school IDs
define('kad_school_id', 126);
//bespoke logo
define('kad_school_logo', base_url('assets/images/bespoke/kad_academy/logo.jpg'));
?>