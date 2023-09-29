<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
// Paypal library configuration
// ------------------------------------------------------------------------

// PayPal environment, Sandbox or Live
$config['sandbox'] = FALSE; // FALSE for live environment


//check which environment is in use
if ( $config['sandbox'] == TRUE ) { 
	
	//PayPal URL
	$config['paypal_url'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	
	//PayPal Host (for headers)
	$config['paypal_host'] = 'www.sandbox.paypal.com';
	
	// PayPal business email
	$config['business'] = 'developer@qschoolmanager.com';
	 
} else {
	
	//PayPal URL
	$config['paypal_url'] = 'https://www.paypal.com/cgi-bin/webscr';
	
	//PayPal Host (for headers)
	$config['paypal_host'] = 'www.paypal.com';
	
	// PayPal business email
	$config['business'] = 'info@qschoolmanager.com';
	
} 


// Where is the button located at?
$config['paypal_lib_currency_code'] = 'USD'; 	
	
// Where is the button located at?
$config['paypal_lib_button_path'] = 'assets/images/paypal/'; 

// Where is the button located at?
$config['paypal_lib_logo'] = 'assets/images/paypal/paypal-logo.png'; 

// Where is the button located at?
$config['paypal_lib_buy_button'] = 'assets/images/paypal/paypal-buy-now-button.png'; 

// If (and where) to log ipn response in a file
$config['paypal_lib_ipn_log'] = TRUE;
$config['paypal_lib_ipn_log_file'] = $_SERVER['DOCUMENT_ROOT'] . '/paypal_error_log';
