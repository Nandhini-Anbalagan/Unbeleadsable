<?php

$cctype = (!empty($_POST['cctype']))?strip_tags(str_replace("'","`",strip_tags($_POST['cctype']))):'';
$ccname = (!empty($_POST['ccname']))?strip_tags(str_replace("'","`",strip_tags($_POST['ccname']))):'';
$ccn = (!empty($_POST['ccn']))?strip_tags(str_replace("'","`",strip_tags($_POST['ccn']))):'';
$exp1 = (!empty($_POST['exp1']))?strip_tags(str_replace("'","`",strip_tags($_POST['exp1']))):'';
$exp2 = (!empty($_POST['exp2']))?strip_tags(str_replace("'","`",strip_tags($_POST['exp2']))):'';
$cvv = (!empty($_POST['cvv']))?strip_tags(str_replace("'","`",strip_tags($_POST['cvv']))):'';

if(verifyCC()){
	require_once 'paypal.callerservice.php';
	switchCC($cctype);
	$error = true;

	$once = oneTime($amount, $_POST['desc']);

	if(!$error){
		$db->addInvoice(array('invoice_num'=>$once['transactionID'],'install'=>0,'monthly'=>SUBSCRIPTION,'ads'=>($amount-SUBSCRIPTION),'agent_id'=>$agent['agent_id']));
	  
		$status ="<br/><div>Transaction Successful!<br/>";
		$status .="Thank you for your payment<br/><br/>";
		$status .= "You will receive confirmation email within 5 minutes.";
		$mess = '<div class="success">'.$status.'</div></div><br />';

		$from = "support@unbeleadsable.com";
		$subject = "New Payment Received";
		$message = $once['msgAdmin'] . "<br><br>" . $once['msgClient'] . "<br><br>" . $reccuring['msgAdmin'] . "<br><br>" . $reccuring['msgClient'];
	
		Functions::sendEmail($from,$admin_email,$subject,$message);
	}
}

function verifyCC(){
	$continue = false;
	if(empty($GLOBALS['ccn']) || empty($GLOBALS['cctype']) || empty($GLOBALS['exp1']) || empty($GLOBALS['exp2']) || empty($GLOBALS['ccname']) || empty($GLOBALS['cvv']) || empty($GLOBALS['address']) || empty($GLOBALS['state']) || empty($GLOBALS['city'])){
		$continue = false;
		$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> Not all required fields were filled out.</p></div><br />';
	} else
	$continue = true;

	if(!is_numeric($GLOBALS['cvv'])){
		$continue = false;
		$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> CVV number can contain numbers only.</p></div><br />';
	} else
	$continue = true;

	if(!is_numeric($GLOBALS['ccn'])){
		$continue = false;
		$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> Credit Card number can contain numbers only.</p></div><br />';
	} else
	$continue = true;

	if(date("Y-m-d", strtotime($GLOBALS['exp2']."-".$GLOBALS['exp1']."-01")) < date("Y-m-d")){
		$continue = false;
		$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> Your credit card is expired.</p></div><br />';
	} else
	$continue = true;

	if($continue){
		if(validateCC($GLOBALS['ccn'],$GLOBALS['cctype']))
			$continue = true;
		else {
			$continue = false;
			$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> The number you\'ve entered does not match the card type selected.</p></div><br />';
		}
	}

	if($continue){
		if(luhn_check($GLOBALS['ccn']))
			$continue = true;
		else {
			$continue = false;
			$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> Invalid credit card number.</p></div><br />';
		}
	}

	return $continue;
} 

/*
* Function to get the Credit Card type shorthanded
*/
function switchCC(&$cctype){
 switch ($cctype) {
	case "V":
	$cctype = "VISA";
	break;
	case "M":
	$cctype = "MASTERCARD";
	break;
	case "DI":
	$cctype = "DINERS CLUB";
	break;
	case "D":
	$cctype = "DISCOVER";
	break;
	case "A":
	$cctype = "AMEX";
	break;
	case "PP":
	$cctype = "PAYPAL";
	break;
} 
}

/*******************************************************************************************************
										RECURRING PROCESSING
*******************************************************************************************************/
function reccuringPayment($amount, $desc){
	$return = array();
	$token = urlencode("");
	$paymentAmount = urlencode($amount);
	$currencyID = urlencode(PTP_CURRENCY_CODE);
	$startDate = urlencode(date("Y-m-d")."T".date("G:i:s")); 
	$billingPeriod = urlencode('Day');   // or "Day", "Week", "Month", "SemiMonth", "Year"
	$billingFreq = urlencode(1);     // combination of this and billingPeriod must be at most a year
	
	$cartType = urlencode($GLOBALS['cctype']);
	$cartNumber = urlencode($GLOBALS['ccn']);
	$expDate = urlencode($GLOBALS['exp1'].$GLOBALS['exp2']);
	$fname = urlencode($GLOBALS['fname']);
	$lname = urlencode($GLOBALS['lname']);
	$desc = urlencode($desc);
	
	$nvpStr="&TOKEN=$token&AMT={$paymentAmount}&CURRENCYCODE={$currencyID}&PROFILESTARTDATE={$startDate}";
	$nvpStr .= "&BILLINGPERIOD={$billingPeriod}&BILLINGFREQUENCY={$billingFreq}&DESC={$desc}";
	$nvpStr .= "&CREDITCARDTYPE={$cartType}&ACCT={$cartNumber}&EXPDATE={$expDate}&FIRSTNAME={$fname}&LASTNAME={$lname}&EMAIL={$email}";
	
	$httpParsedResponseAr = PPHttpPost('CreateRecurringPaymentsProfile', $nvpStr);
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
		$GLOBALS['error'] = false;

		//Admin Message Body
		$return['msgAdmin'] = "Paypal Recurring Payment Profile was successfully Created for: ";              
		$return['msgAdmin'] .= $GLOBALS['fname']." ".$GLOBALS['lname']." on ".date('m/d/Y')." at ".date('g:i A').".<br />Payment total is: $".number_format($amount,2) . " (Subscription Fees: $". number_format(SUBSCRIPTION,2) . " and Ad Campaign Budget: $" . number_format(($amount - SUBSCRIPTION),2).")";
		$return['msgAdmin'] .= "<br />Payment description: \"".urldecode($desc)."\"";
		$return['msgAdmin'] .= "<br />Start Date: ".date("Y-m-d")."<br />";
		$return['msgAdmin'] .= "Period: ".$billingPeriod."<br />";
		$return['msgAdmin'] .= "Billing Frequency: ".$billingFreq."<br />";
		$return['msgAdmin'] .= "Profile ID: ".urldecode($httpParsedResponseAr['PROFILEID'])."<br />";
		$return['transactionID'] = urldecode($httpParsedResponseAr['PROFILEID']);
		
		logTransaction(str_replace("<br />", "\n", $return['msgAdmin']));

		//Client Message Body
		$return['msgClient'] = "Payment was made for \"".urldecode($desc)."\"";
		$return['msgClient'] .=".<br /> Payment total is: $".number_format($amount,2) . " - (Subscription Fees: $". number_format(SUBSCRIPTION,2) . " and Ad Campaign Budget: $" . number_format(($amount - SUBSCRIPTION),2). ")";
		$return['msgClient'] .= "<br/>Start Date: ".date("Y-m-d")."<br />";
		$return['msgClient'] .= "Period: ".$billingPeriod."<br />";
		$return['msgClient'] .= "Billing Frequency: ".$billingFreq."<br />";
	}else{
		$my_status ="<div>Transaction Un-successful!<br/>";
		$my_status.="There was an error with your credit card processing:<br/>";
		$my_status .="Error Message: ". urldecode($httpParsedResponseAr['L_LONGMESSAGE0'])."</div>";
		$GLOBALS['error'] = true;
		$GLOBALS['mess'] = '<div class="error">'.$my_status.'</div><br />';
	}

	return $return;
}

/*******************************************************************************************************
										ONE TIME PROCESSING
*******************************************************************************************************/
function oneTime($amount, $desc){
	$paymentType =urlencode("Sale");
	$API_UserName=API_USERNAME;
	$API_Password=API_PASSWORD;
	$API_Signature=API_SIGNATURE;
	$API_Endpoint =API_ENDPOINT;
	$paymentType =urlencode("Sale");
	$tt = explode(" ",trim($GLOBALS['ccname']));
	$return = array();
	
	if(is_array($tt)){
		$firstName =urlencode( $tt[0]);
		
		if(isset($tt[2]))
			$temp = $tt[1]." ".$tt[2]; 
		else
			if(isset($tt[1]))
				$temp = $tt[1]; 
			else 
				$temp = "";
		$lastName =urlencode($temp);
	} else{
		$firstName =urlencode($GLOBALS['ccname']);
		$lastName =urlencode("");
	}

	$creditCardType =urlencode($GLOBALS['cctype']);
	$creditCardNumber = urlencode(trim($GLOBALS['ccn']));
	$expDateMonth =urlencode($GLOBALS['exp1']);
	$padDateMonth = str_pad($GLOBALS['exp1'], 2, '0', STR_PAD_LEFT);
	$expDateYear =urlencode($GLOBALS['exp2']);
	$cvv2Number = urlencode(trim($GLOBALS['cvv']));

	//CUSTOMER INFO
	$address1 = urlencode($GLOBALS['address']);
	$countryCode = urlencode($GLOBALS['country']);
	$city = urlencode($GLOBALS['city']);
	$state =urlencode($GLOBALS['state']);
	$zip = urlencode($GLOBALS['zip']);

	$amount = urlencode(number_format($amount,2));
	$currencyCode=PTP_CURRENCY_CODE;
	//Construct the request string that will be sent to PayPal. The variable $nvpstr contains all the variables and is a name value pair string with & as a delimiter
	$nvpstr="&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber&EXPDATE=".$padDateMonth.$expDateYear."&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$countryCode&CURRENCYCODE=$currencyCode";
	$getAuthModeFromConstantFile = true;
	$nvpHeader = "";

	if(!$getAuthModeFromConstantFile)
		$AuthMode = "3TOKEN";
	else 
		if(!empty($API_UserName) && !empty($API_Password) && !empty($API_Signature) && !empty($subject))
			$AuthMode = "THIRDPARTY";
		else if(!empty($API_UserName) && !empty($API_Password) && !empty($API_Signature))
			$AuthMode = "3TOKEN";
		else if(!empty($subject))
			$AuthMode = "FIRSTPARTY";

	switch($AuthMode) {
		case "3TOKEN" : 
			$nvpHeader = "&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature);
			break;
		case "FIRSTPARTY" :
			$nvpHeader = "&SUBJECT=".urlencode($subject);
			break;
		case "THIRDPARTY" :
			$nvpHeader = "&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature)."&SUBJECT=".urlencode($subject);
			break;     
	}

	$nvpstr = $nvpHeader.$nvpstr;

	/* Make the API call to PayPal, using API signature. The API response is stored in an associative array called $resArray */
	$resArray=hash_call("doDirectPayment",$nvpstr);

	/* Display the API response back to the browser.
	   If the response from PayPal was a success, display the response parameters'
	   If the response was an error, display the errors received using APIError.php.*/
	$ack = strtoupper($resArray["ACK"]);

	if($ack!="SUCCESS" && $ack!="SUCCESSWITHWARNING")  {
		$_SESSION['reshash']=$resArray;
		$resArray=$_SESSION['reshash']; 
		if(isset($_SESSION['curl_error_no'])) { 
			$errorCode= $_SESSION['curl_error_no'] ;
			$errorMessage=$_SESSION['curl_error_msg'] ;
			$my_status = "<div>Transaction Un-successful!<br/>";
			$my_status .= "There was an error with your credit card processing:<br/>";
			$my_status .= "Merchant Gateway Response: " . $errorMessage . ", Error: " . $errorCode . "<br/>";
			$my_status .= "</div>";
			$GLOBALS['error'] = true;
			$GLOBALS['mess'] = '<div class="error">' . $my_status . '</div></div><br />';
		}else{
		  $count=0;
		  $my_status="<div>Transaction Un-successful!<br/>";
		  $my_text="There was an error with your credit card processing:<br/>";

			while (isset($resArray["L_SHORTMESSAGE".$count])) {       
				$errorCode    = $resArray["L_ERRORCODE".$count];
				$shortMessage = $resArray["L_SHORTMESSAGE".$count];
				$longMessage  = $resArray["L_LONGMESSAGE".$count]; 
				$count=$count+1;                   
				$my_text.="Error Code: ".$errorCode."<br/>";
				$my_text.="Error Message: ".$longMessage."<br/>";
			}

			$my_status .= $my_text."</div>";
			$GLOBALS['error'] = true;
			$GLOBALS['mess'] = '<div class="error">'.$my_status.'<br />';
		}
	}else{ 
		$GLOBALS['error'] = false;

		$return['msgAdmin'] = "One time payment was successfully received through PayPal ";
		$return['msgAdmin'] .= "from " . $GLOBALS['fname'] . " " . $GLOBALS['lname'] . "  on " . date('m/d/Y') . " at " . date('g:i A') . ".<br />Payment total is: $" . number_format($amount, 2);
		$return['msgAdmin'] .= "<br />Payment was made for \"" . $desc . "\"";   
		$return['msgAdmin'] .= "<br />Transaction Number: \"" . $resArray["TRANSACTIONID"] . "\"";
		$return['transactionID'] = $resArray["TRANSACTIONID"];

		logTransaction(str_replace("<br />", "\n", $return['msgAdmin']));

		$return['msgClient'] = "Payment was made for \"" . $desc . "\"";
		$return['msgClient'] .= "<br />Payment amount: $" . number_format($amount, 2);
	}

	return $return;
}

/*
	Function to log payments
	@text: text to save
*/
function logTransaction($text){
	$fp = fopen (PAYPAL_LOG_FILE , 'a');
	fwrite ($fp, $text . "\n");
	fclose ($fp );
	chmod (PAYPAL_LOG_FILE , 0600);
}

?>