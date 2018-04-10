<?php

$cctype = (!empty($_POST['cctype']))?strip_tags(str_replace("'","`",strip_tags($_POST['cctype']))):'';
$ccname = (!empty($_POST['ccname']))?strip_tags(str_replace("'","`",strip_tags($_POST['ccname']))):'';
$ccn = (!empty($_POST['ccn']))?strip_tags(str_replace("'","`",strip_tags($_POST['ccn']))):'';
$exp1 = (!empty($_POST['exp1']))?strip_tags(str_replace("'","`",strip_tags($_POST['exp1']))):'';
$exp2 = (!empty($_POST['exp2']))?strip_tags(str_replace("'","`",strip_tags($_POST['exp2']))):'';
$cvv = (!empty($_POST['cvv']))?strip_tags(str_replace("'","`",strip_tags($_POST['cvv']))):'';

$adminError = "<ol>";
$adminSuccess = "<ol>";
$errorCode = 0;
$gatewayError = "<ol>";

$lname = $_POST['lname'];
$fname = $_POST['fname'];
$email = $_POST['email'];

$address = $_POST['address'];
$city = $_POST['city'];
$country = $_POST['country'];
$state = $_POST['state'];
$zip = $_POST['zip'];

$lang = $_POST['lang'];
$agent_id = $_POST['id'];
$description = $_POST['description'];
$amount = $_POST['amount'];

$mess = "";
$messTran = "";

if(verifyCC()){
	require_once 'models/paypal/paypal.callerservice.php';
	$error = true;

	$response = payment($amount, $description);

	if(!$error){
		$adminSuccess .= "<li>Payment for $fname $lname <br><b>Description: $description</b><br><b>Amount of:</b> $" . ($amount) . " USD.<br><b>Invoice:</b> <a href='".WEBSITE_URL."/app/receipt/".$response['transactionID']."'>".$response['transactionID']."</a><br><br></li>";

	ob_start();
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">

	<style>
	.invoice-box{
		max-width:800px;
		margin:auto;
		padding:30px;
		border:1px solid #eee;
		box-shadow:0 0 10px rgba(0, 0, 0, .15);
		font-size:16px;
		line-height:24px;
		font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
		color:#555;
	}

	.invoice-box table{
		width:100%;
		line-height:inherit;
		text-align:left;
	}

	.invoice-box table td{
		padding:5px;
		vertical-align:top;
	}

	.invoice-box table tr td:nth-child(2){
		text-align:right;
	}

	.invoice-box table tr.top table td{
		padding-bottom:20px;
	}

	.invoice-box table tr.top table td.title{
		font-size:45px;
		line-height:45px;
		color:#333;
	}

	.invoice-box table tr.information table td{
		padding-bottom:40px;
	}

	.invoice-box table tr.heading td{
		background:#eee;
		border-bottom:1px solid #ddd;
		font-weight:bold;
	}

	.invoice-box table tr.details td{
		padding-bottom:20px;
	}

	.invoice-box table tr.item td{
		border-bottom:1px solid #eee;
	}

	.invoice-box table tr.item.last td{
		border-bottom:none;
	}

	.invoice-box table tr.total td:nth-child(2){
		border-top:2px solid #eee;
		font-weight:bold;
	}

	</style>
</head>

<body>
	<div class="invoice-box">
		<table cellpadding="0" cellspacing="0">
			<tr class="top">
				<td colspan="2">
					<table>
						<tr>
							<td colspan="2" align="right">
								<?php echo $lang == "EN"?'Invoice #':'# Facture'; ?>: <a href='<?php echo WEBSITE_URL ?>/receipt/other/<?php echo $response['transactionID'] ?>'><?php echo $response['transactionID'] ?></a><br>
								Date: <?php echo date("Y-m-d") ?><br><br>
								<i>GST No: 83425 4377 RT0001</i><br>
								<i>PST No: 1220927110 TQ 0001</i>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr class="information">
				<td colspan="2">
					<table>
						<!-- <tr>
							<td colspan="2">
								<strong>Unbeleadsable</strong><br>
								12345 Sunny Road<br>
								Sunnyville, TX 12345<br><br>
							</td>
						</tr>
						<tr><td colspan="2"></td></tr> -->
						<tr>
							<td colspan="2">
								<strong><?php echo  $fname . " " . $lname ?></strong><br>
								<?php echo  $address ?><br>
								<?php echo  $city . ", " . $state . " " .  $zip . " " . $country?><br>
								<?php echo  $email . "<br>" ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>

			<tr class="heading">
				<td colspan="2">
					<?php echo $lang == "EN"?'Payment Method':'Méthode de paiement'; ?>
				</td>
			</tr>

			<tr class="details">
				<td colspan="2">
					<?php echo $cctype . " **** **** **** " . substr(str_replace(array("-", " "), "", $ccn), -4) ?>
				</td>
			</tr>

			<tr class="heading">
				<td>
					Description
				</td>

				<td>
					<?php echo $lang == "EN"?'Amount':'Montant'; ?>
				</td>
			</tr>

			<tr class="item last">
				<td>
					<?php echo $description ?>
				</td>

				<td>
					$<?php echo $amount . " USD" ?>
				</td>
			</tr>

			<tr class="item">
				<td align="right">
					<b>Total</b>
				</td>

				<td>
					$<?php echo $amount . " USD" ?>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>

	<?php
		$ob = ob_get_clean();
		$subj = $lang == "EN"?'Here\'s your invoice from Unbeleadsable on ':'Votre facture de la part d\'Unbeleasable en date du ';
		Functions::sendEmail("support@unbeleadsable.com",$email,$subj. date('Y-m-d'),$ob);

		//Add Invoice and Invoice details
		$invoiceData = array();
		$invoiceData[] = '';
		$invoiceData[] = $response['transactionID'];
		$invoiceData[] = date('Y-m-d H:i:s');
		$invoiceData[] = $fname . " " . $lname;
		$invoiceData[] = $address . ", " . $city . ", " . $state . " " .  $zip . " " . $country;
		$invoiceData[] = $email;
		$invoiceData[] = $description;
		$invoiceData[] = $amount;
		$invoiceData[] = $lang;

		$db->addAnyInvoice($invoiceData);

		$resultObj['success'] = "Payment Successfully";
		$resultObj['reset'] = true;
		$resultObj['refresh'] = true;
	}else{
		if($errorCode == 6)
			$gatewayError .= "<li><strong>$fname  $lname </strong><br><b>Description:</b> $description<br>$messTran</li>";

		$mess = $messTran;
	}
}

$adminSuccess .= "</ol>";
$gatewayError .= "</ol>";

if($adminSuccess != "<ol></ol>")
	Functions::sendEmail("support@unbeleadsable.com","support@unbeleadsable.com","Automatic payments for Unbeleadsable on ". date('Y-m-d'),$adminSuccess);

if($gatewayError != "<ol></ol>" AND $errorCode == 6)
		Functions::sendEmail("support@unbeleadsable.com","support@unbeleadsable.com","Gateway Fails for Unbeleadsable on ". date('Y-m-d'),$gatewayError);


/*Credit Card verification*/
function verifyCC(){
	$continue = false;

	if(!is_numeric($GLOBALS['cvv'])){
		$continue = false;
		//$GLOBALS['mess'] = $GLOBALS['lang']=='EN'?'Invalid CVV Number (numbers only).':'Le code de sécurité est invalid.';
		$GLOBALS['mess'] = 'Invalid CVV Number (numbers only).';
	}else
		$continue = true;

	if(!is_numeric($GLOBALS['ccn'])){
		$continue = false;
		//$GLOBALS['mess'] = $GLOBALS['lang']=='EN'?'Invalid Credit Card Number (numbers only).':'Le numéro de la carte de crédit est invalid';
		$GLOBALS['mess'] = 'Invalid Credit Card Number (numbers only).';
	}else
		$continue = true;

	if(date("Y-m-d", strtotime($GLOBALS['exp2']."-".$GLOBALS['exp1']."-01")) < date("Y-m-d")){
		$continue = false;
		//$GLOBALS['mess'] = $GLOBALS['lang']=='EN'?'Credit Card is expired.':'La carte de crédit est expirée.';
		$GLOBALS['mess'] = 'Credit Card expired.';
	}else
		$continue = true;

	if($continue){
		if(validateCC($GLOBALS['ccn'],switchCC($GLOBALS['cctype'])))
			$continue = true;
		else {
			$continue = false;
			//$GLOBALS['mess'] = $GLOBALS['lang']=='EN'?'The number you\'ve entered does not match the card type selected.':'Le numéro de la carte ne correspond pas à son type (VISA, MASTERCARD, etc).';
			$GLOBALS['mess'] = 'The number entered does not match the card type selected.';
		}
	}

	if($continue){
		if(luhn_check($GLOBALS['ccn']))
			$continue = true;
		else {
			$continue = false;
			//$GLOBALS['mess'] = $GLOBALS['lang']=='EN'?'Invalid credit card number.':'Numéro de carte de crédit invalide.';
			$GLOBALS['mess'] = 'Invalid credit card number.';
		}
	}

	return $continue;
}

function switchCC($type){
	$t = "";
	switch ($type) {
		case 'VISA':
			$t = "V";
			break;

		case 'MASTERCARD':
			$t = "M";
			break;

		case 'DINERS CLUB':
			$t = "DI";
			break;

		case 'DISCOVER':
			$t = "D";
			break;

		case 'AMEX':
			$t = "A";
			break;
	}

	return $t;
}

function payment($amount, $desc){
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
	}else{
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

			$GLOBALS['messTran'] .= "Merchant Gateway Response: " . $errorMessage . " (" . $errorCode . ")<br/>";
			$GLOBALS['error'] = true;
			$GLOBALS['errorCode'] = $errorCode;
		}else{
			$count=0;

			while(isset($resArray["L_SHORTMESSAGE".$count])) {
				$errorCode    = $resArray["L_ERRORCODE".$count];
				$shortMessage = $resArray["L_SHORTMESSAGE".$count];
				$longMessage  = $resArray["L_LONGMESSAGE".$count];
				$count=$count+1;

				$GLOBALS['messTran'] .= "Merchant Gateway Response: " . $longMessage . " (" . $errorCode . ")<br/>";
				$GLOBALS['errorCode'] = $errorCode;
			}

			$GLOBALS['error'] = true;
		}
	}else{
		$GLOBALS['error'] = false;

		$msg = "Recurring payment was successfully received through PayPal ";
		$msg .= "from " . $GLOBALS['fname'] . " " . $GLOBALS['lname'] . "  on " . date('m/d/Y') . " at " . date('g:i A') . ".<br />Payment total is: $" . number_format($amount, 2);
		$msg .= "<br />Payment was made for \"" . $desc . "\"";
		$msg .= "<br />Transaction Number: \"" . $resArray["TRANSACTIONID"] . "\"";
		$return['transactionID'] = $resArray["TRANSACTIONID"];

		logTransaction(str_replace("<br />", "\n", $msg));
	}

	return $return;
}

/*
	Function to log payments
	@text: text to save
*/
function logTransaction($text){
	$fp = fopen (PAYPAL_LOG_FILE , 'a');
	fwrite ($fp, $text . "\n\n\n");
	fclose ($fp );
	chmod (PAYPAL_LOG_FILE , 0600);
}

?>