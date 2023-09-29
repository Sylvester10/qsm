<?php
/* ===== Documentation ===== */
/*
Name: constants/Rave
Role: Include
Description: Holds Flutterwave rave data
Author: Nwankwo Ikemefuna
Date Created: 18th August, 2018
*/


//change to switch between test/sandbox/development and live/production environments)
$environment = 'test'; //test, live

//test/sandbox environment account
$test_environment = 1; //1: Valkay Concepts (valkaycelestino@gmail.com), 2: Valkay Devs (nwankwoikemefuna23@gmail.com)

	if ($environment == 'live') { //live 
	
		define('public_key', 'FLWPUBK-fa9ebd8bf639bef6693f65e643915b6b-X'); 
		define('secret_key', 'FLWSECK-7b893eb3dfaa85160361fca02efb5dcb-X');
		define('script_url', 'https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js');
		define('verify_url', 'https://api.ravepay.co/flwv3-pug/getpaidx/api/xrequery');
		
	} else { //test/sandbox
	
		if ($test_environment == 1) { //1: Valkay Concepts 		
			define('public_key', 'FLWPUBK-3966e40b75be8fb27daf70a8143acc8c-X');
			define('secret_key', 'FLWSECK-1466e307a8dd8563eb90079676c324e1-X');		
		} else { //2: Valkay Devs		
			define('public_key', 'FLWPUBK-298279d8b3fe90695515cea3f0109c55-X');
			define('secret_key', 'FLWSECK-78b574bae20e4df71ba555132a21d8da-X');		
		}		
		define('script_url', 'https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/flwpbf-inline.js');
		define('verify_url', 'https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/xrequery');
		
	}

define('environment', $environment);


/* 
Rave Test Accounts
Mastercard PIN Authentication:
Card No: 5399838383838381
Expiry Date: 10/22
CVV: 470
Pi:n 3310
OTP: 12345
*/

?>		