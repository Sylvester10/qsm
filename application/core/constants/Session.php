<?php

/* ===== Documentation ===== 
Name: Constants::Session
Role: Include
Description: Holds all the session-related constants used by the app. Used in admin, staff, student and parent panels
Author: Nwankwo Ikemefuna
Date Created: 17th May, 2018
*/


//Note: The argument variable $this->school_id is defined in every class constructor where this file is required.  

//School Info
define('school_id', $this->common_model->get_school_info($this->school_id)->id);
define('school_name', $this->common_model->get_school_info($this->school_id)->school_name);
define('school_location', $this->common_model->get_school_info($this->school_id)->school_location);
define('s_country', $this->common_model->get_school_info($this->school_id)->country);

$currency_code = $this->common_model->get_school_info($this->school_id)->currency_code;
define('s_currency_symbol', get_currency_symbol($currency_code));
define('official_mail', $this->common_model->get_school_info($this->school_id)->official_mail);
define('telephone_line', $this->common_model->get_school_info($this->school_id)->telephone_line);

$school_website = $this->common_model->get_school_info($this->school_id)->school_website;
if ($school_website == NULL || $school_website == '') { //school website not specified, use software website
	$software_website = software_website;
	define('school_website', $software_website);
} else {
	define('school_website', $this->common_model->get_school_info($this->school_id)->school_website);
}
define('year_installed', $this->common_model->get_school_info($this->school_id)->year_installed);
define('date_installed', $this->common_model->get_school_info($this->school_id)->date_installed);
define('date_activated', $this->common_model->get_school_info($this->school_id)->date_activated);
define('chief_admin_id', $this->common_model->get_school_info($this->school_id)->chief_admin_id);
define('chief_admin_name', $this->common_model->get_admin_details_by_id(chief_admin_id)->name);
define('chief_admin_email', $this->common_model->get_admin_details_by_id(chief_admin_id)->email);
define('chief_admin_phone', $this->common_model->get_admin_details_by_id(chief_admin_id)->phone);


//check if school logo and favicon have been updated
$the_school_logo = $this->common_model->get_school_info($this->school_id)->school_logo;
$the_school_favicon = $this->common_model->get_school_info($this->school_id)->school_favicon;
if ($the_school_logo == NULL) { //school logo has not been updated, use default logo and favicon
	define('school_logo', school_logo_def);
	define('school_favicon', school_favicon_def);
} else { //school logo has been updated
	define('school_logo', base_url('assets/uploads/logo/'.$the_school_logo));
	define('school_favicon', base_url('assets/uploads/logo/'.$the_school_favicon));
}


//School Plan Details 
define('school_plan_id', $this->common_model->get_school_info($this->school_id)->plan_id);
define('school_plan', $this->common_model->get_plan_details(school_plan_id)->plan);
define('school_account_mode', $this->common_model->get_school_info($this->school_id)->mode);

//account activation
define('free_trial_activation_status', $this->common_model->get_school_info($this->school_id)->activated);
//check account activation status
$expired_annual_subscription = $this->common_model->get_expired_annual_subscription($this->school_id);
if ( free_trial_activation_status == 'false' || (free_trial_activation_status == 'true' && $expired_annual_subscription) ) {
	$school_account_activation_status = 'false';
} else {
	$school_account_activation_status = 'true';
}
define('school_account_activation_status', $school_account_activation_status);

define('activation_code', $this->common_model->get_school_info($this->school_id)->activation_code);
define('show_activation_code', $this->common_model->get_school_info($this->school_id)->show_activation_code);

//check if account has been activated 
if (school_account_activation_status == 'true' && date_activated != NULL) { //yes sir
	$account_activation_date = x_date_full(date_activated) . ' (' . time_ago(date_activated) . ')';
	define('account_activation_date', $account_activation_date);
} else { //no sir
	$account_activation_date = NULL;
	define('account_activation_date', $account_activation_date);
}
$account_renewal_date = get_renewal_date(school_account_activation_status, date_activated);
define('account_renewal_date', $account_renewal_date);


//account upgrade
$last_upgrade = $this->common_model->get_school_info($this->school_id)->last_upgrade;
define('last_upgrade', $last_upgrade);
if ($last_upgrade != NULL) { //yes sir
	$last_upgraded = x_date_full($last_upgrade) . ' (' . time_ago($last_upgrade) . ')';
	define('last_upgraded', $last_upgraded);
} else { //no sir
	$last_upgraded = NULL;
	define('last_upgraded', $last_upgraded);
}


//free trial expiration
$account_expiration_date = get_expiration_date(date_installed);
define('account_expiration_date', $account_expiration_date);


//session and term info
define('current_session', $this->common_model->get_term_info($this->school_id)->session);
define('current_session_slug', get_session_slug(current_session));
define('current_term', $this->common_model->get_term_info($this->school_id)->term);
?>