<?php

require("functions.php");
define('PAYPAL_LOG_FILE', "{$_SERVER['DOCUMENT_ROOT']}/app/models/paypal/log");

//FEES
define("INSTALLATION","49.99");
define("SUBSCRIPTION","99.99");
define("PTP_CURRENCY_CODE","USD"); 

/*******************************************************************************************************
    GENERAL SCRIPT CONFIGURATION VARIABLES
********************************************************************************************************/

//OPTIONS
$admin_email = "support@unbeleadsable.com";
$redirect_non_https = true;
$enable_paypal = false; 
$liveMode = false;
//5147076288

if(!$liveMode){//TEST MODE
	//support-facilitator@unbeleadsable.com
	define('API_USERNAME', 'support-facilitator_api1.unbeleadsable.com');
	define('API_PASSWORD', 'U7L8JCUP6UGMG84U');
	define('API_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31AdfpeScoRNiTNXVSkS9uPMUod.zr');
	define('API_ENDPOINT', 'https://api-3t.sandbox.paypal.com/nvp');
	define('PAYPAL_URL', 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=');
}else{//LIVE MODE
	define('API_USERNAME', 'support_api1.unbeleadsable.com');
	define('API_PASSWORD', 'WBG5PLLGLUYR3URS');
	define('API_SIGNATURE', 'AFcWxV21C7fd0v3bYYYRCpSSRl31AGCxe2nt.Y-7C472sT-n2ZUKfjvG');
	define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp');
	define('PAYPAL_URL', 'https://www.paypal.com/webscr&cmd=_express-checkout&token=');
}

define('USE_PROXY',FALSE);
define('PROXY_HOST', '127.0.0.1');
define('PROXY_PORT', '808');
define('VERSION', '2.3');
define('ACK_SUCCESS', 'SUCCESS');
define('ACK_SUCCESS_WITH_WARNING', 'SUCCESSWITHWARNING');

if($redirect_non_https){
	if ($_SERVER['SERVER_PORT']!=443) {
		$sslport=443; //whatever your ssl port is
		$url = "https://". $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		header("Location: $url");
		exit();
	}
}

//service name   |   price  to charge   | Billing period  "Day", "Week", "SemiMonth", "Month", "Year"   |  how many periods of previous field per billing period
/****************************************************
//TEST CREDIT CARD CREDENTIALS for SANDBOX TESTING
Credit Card Number: 4214022343119424
Credit Card Type: VISA
Expiration Date: 09/2021
Security Code: 123
****************************************************/
?>

