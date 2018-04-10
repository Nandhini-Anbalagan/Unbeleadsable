<?php

require_once 'app/models/phpMailer/vendor/autoload.php';

// Or, using an anonymous function as of PHP 5.3.0
spl_autoload_register(function ($class) {
	require_once("app/models/$class.class.php");
});

require_once("app/models/paypal/config.php");

date_default_timezone_set('America/Montreal');

$db = new DBManager();
$agents = $db->getReccuringProfile();

$adminError = "<ol>";
$adminSuccess = "<ol>";
$errorCode = 0;
$gatewayError = "<ol>";

foreach ($agents as $agent) {
	$name = explode(" ",$agent['agent_name']);
	$amount = $agent['ad_campaign'];
	$lname = array_pop($name);
	$fname = implode(" ", $name);
	$email = $agent['agent_email'];
	$agent_area = $agent['area_name'];
	$agent_id = IDObfuscator::encode($agent['agent_id']);
	$agent_id_clear = $agent['agent_id'];
	$add = explode(",",$agent['agent_address']);
	$prov = explode(" ",$add[2]);
	array_shift($prov);

	$address = $add[0];
	$city = $add[1];
	$country = $add[3];
	$state = array_shift($prov);
	$zip = implode(" ", $prov);

	$cctype = $agent['type'];
	$ccname = Functions::decode($agent['name']);
	$ccn = Functions::decode($agent['num']);
	$end = substr(str_replace(array("-", " "), "", $ccn), -4);
	$exp1 = $agent['mm'];
	$exp2 = $agent['year'];
	$cvv = $agent['cvv'];
	$lang = $agent['agent_lang'];

	$mess = "";
	$messTran = "";

	if($lang == "EN"){
		// Fail Message English
		$failMessage = "Hi $fname,<br><br>";
		$failMessage .= "Your payment method failed and we weren’t able to charge you for your Unbeleadsable subscription. To keep using Unbeleadsable, please update your billing info or add a new payment method.<br><br>";
		$failMessage .= "After " . date('Y-m-d', strtotime($agent['next_billing']. ' + 5 days')) . " if we are still unable to process your monthly payment, we'll be obligated to suspended  your account (#$agent_id) for the sector of $agent_area.<br><br>";
		$failMessage .= "If you have any questions, please contact <a href='mailTo:support@unbeleadsable.com'>Unbeleadsable</a> support.<br><br>";
		$failMessage .= "Sincerely,<br>The Unbeleadsable Team";

		// Final notice Message English
		$finalMessage = "Hi $fname,<br><br>";
		$finalMessage .= "Your account (#$agent_id) for the sector of $agent_area has been suspended due to 5 (five) consecutives payment failure.<br><br>";
		$finalMessage .= "The credit card we have on file ending with $end has either been declined, or there was an error with the transaction. Credit cards are usually declined due to insufficient funds, an expired expiration date, or an incorrect security code.<br><br>";
		$finalMessage .= "Please contact <a href='mailTo:support@unbeleadsable.com'>Unbeleadsable</a> support ASAP to resolve this issue.<br><br>";
		$finalMessage .= "Sincerely,<br>The Unbeleadsable Team";
	}else{
		// Fail message French
		$failMessage = "Bonjour $fname,<br><br>";
		$failMessage .= "Votre méthode de paiement a échoué et nous n'avons pas été pas en mesure de vous facturer pour votre abonnement mensuel de Unbeleadsable. Pour continuer à utiliser Unbeleadsable, s'il vous plaît mettre à jour vos informations de facturation ou d'ajouter un nouveau mode de paiement.<br><br>";
		$failMessage .= "Après le " . date('Y-m-d', strtotime($agent['next_billing']. ' + 5 days')) . " si nous sommes incapables de traiter votre paiement mensuel, nous serons obligés de suspendre votre compte (#$agent_id) pour le secteur de $agent_area.<br><br>";
		$failMessage .= "Si vous avez des questions, s'il vous plaît veuillez contacter l'équipe de support de <a href='mailTo:support@unbeleadsable.com'>Unbeleadsable</a>.<br><br>";
		$failMessage .= "Cordialement,<br>L'équipe de support de Unbeleadsable";

		// Final notice Message French
		$finalMessage = "Hi $fname,<br><br>";
		$finalMessage .= "Votre compte (#$agent_id) pour le secteur de $agent_area a été suspendu en raison de 5 (cinq) échec de paiement consécutifs.<br><br>";
		$finalMessage .= "La carte de crédit que nous avons au système se terminant par $end a été soit refusée, ou il y avait une erreur avec la transaction. Les cartes de crédit sont généralement refusées en raison de fonds insuffisants, d'une date d'expiration passée, ou un code de sécurité incorrect.<br><br>";
		$finalMessage .= "S'il vous plaît veuillez contacter l'équipe de support de <a href='mailTo:support@unbeleadsable.com'>Unbeleadsable</a> afin de resoudre se problème le plus rapidement que possible.<br><br>";
		$finalMessage .= "Cordialement,<br>L'équipe de support de Unbeleadsable";
	}

	$subFail = $lang == "EN"?"Payment failed. Please update your info on Unbeleadsable":"Paiement échoué. S'il vous plaît mettre à jour votre information sur Unbeleadsable";
	$finalFail = $lang == "EN"?"Final notice regarding your Unbeleadsable subscription payment":"Avis final concernant votre paiement d'abonnement d'Unbeleadsable";

	if(verifyCC()){
		require_once 'app/models/paypal/paypal.callerservice.php';
		$error = true;

		$response = payment((SUBSCRIPTION+$amount), "Subscription Fee + Ad Campaign ($agent_area)");

		if(!$error){
			$adminSuccess .= "<li>Automatic monthly payment for $fname $lname (#$agent_id_clear)<br><b>Sector:</b> $agent_area<br><b>Amount:</b> $" . (SUBSCRIPTION+$amount) . " USD.<br><b>Invoice:</b> <a href='".Config::WEBSITE_URL."/receipt/".$response['transactionID']."'>".$response['transactionID']."</a><br><br></li>";
		
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
									<?php echo $lang == "EN"?'Invoice #':'# Facture'; ?>: <a href='<?php echo Config::WEBSITE_URL ?>/receipt/<?php echo $response['transactionID'] ?>'><?php echo $response['transactionID'] ?></a><br>
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
									<?php echo $address ?><br>
									<?php echo $city . " " . $state . " " .  $zip . " " . $country?><br>
									<?php echo $email ?>
									<?php echo "<b>". ($lang == "EN"?'Account No.: ':'No. de compte: '). "</b>". $agent_id . "<br>"?>
									<?php echo "<b>". ($lang == "EN"?'Sector: ':'Secteur: ') . "</b>". $agent_area ?>
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
						<?php echo $lang == "EN"?'Monthly Subscription Fee':'Frais d\'abonnement mensuel'; ?>
					</td>
					
					<td>
						$<?php echo SUBSCRIPTION . " USD" ?>
					</td>
				</tr>
				
				<tr class="item">
					<td>
						<?php echo $lang == "EN"?'Monthly Ad Campaign Budget':'Budge mensuel de campagnes publicitaires'; ?>
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
						$<?php echo (SUBSCRIPTION + $amount) . " USD" ?>
					</td>
				</tr>
			</table>
		</div>
	</body>
	</html>

		<?php 
				
			$ob = ob_get_clean();
			$subj = $lang == "EN"?'Automatic payments for Unbeleadsable on ':'Paiements automatiques à Unbeleasable en date du ';
			Functions::sendEmail("support@unbeleadsable.com",$agent['agent_email'],$subj. date('Y-m-d'),$ob);
		   
			//Add Invoice and Invoice details
			$db->addInvoice(array('invoice_num'=>$response['transactionID'],'install'=>0,'monthly'=>SUBSCRIPTION,'ads'=>$amount,'agent_id'=>$agent['agent_id']));

			//update the date to next month
			$db->resetRecurringDate($agent['agent_id']);
		}else{
			
			//Gateway problem
			if($errorCode == 6)
				$gatewayError .= "<li><strong>$fname $lname</strong> (#$agent_id_clear)<br><b>Sector:</b> $agent_area<br><b>Tries:</b> " . ($agent['counter'] + 1) . "<br>$messTran</li>";
			else{//transaction error
				$adminError .= "<li><strong>$fname $lname</strong> (#$agent_id_clear)<br><b>Sector:</b> $agent_area<br><b>Tries:</b> " . ($agent['counter'] + 1) . "<br>$messTran</li>";

				if($agent['counter'] < 4)
					Functions::sendEmail("support@unbeleadsable.com",$agent['agent_email'],$subFail,$failMessage);
				else
					Functions::sendEmail("support@unbeleadsable.com",$agent['agent_email'],$finalFail,$finalMessage);

				$db->updateReccuringCount($agent['agent_id']);
				$db->updateAgentStatus($agent['agent_id'], 2);

				//fixed only to ban when no more accounts and also agent status to 3
				if($agent['counter'] == 4)
					$db->banIfOnlyOneAccountLeft($agent['user_id']);

		   }
		   
		}
	}else{
		//credit card error
		$adminError .= "<li><strong>$fname $lname</strong> (#$agent_id_clear)<br><b>Sector:</b> $agent_area<br>Tries: " . ($agent['counter'] + 1) . "<br>Error: $mess </li>";
		
		if($agent['counter'] < 4)
			Functions::sendEmail("support@unbeleadsable.com",$agent['agent_email'],$subFail,$failMessage);
		else
			Functions::sendEmail("support@unbeleadsable.com",$agent['agent_email'],$finalFail,$finalMessage);

		if($agent['counter'] == 4)
			$db->banUser($agent['user_id']);

		$db->updateReccuringCount($agent['agent_id']);
	}   
}

if($agents){
	$adminSuccess .= "</ol>";
	$adminError .= "</ol>";
	$gatewayError .= "</ol>";

	if($adminSuccess != "<ol></ol>"){
		Functions::sendEmail("support@unbeleadsable.com","support@unbeleadsable.com","Automatic payments for Unbeleadsable on ". date('Y-m-d'),$adminSuccess);
	}

	if($adminError != "<ol></ol>"){
		Functions::sendEmail("support@unbeleadsable.com","support@unbeleadsable.com","Automatic payments Fails for Unbeleadsable on ". date('Y-m-d'),$adminError);
	}

	if($gatewayError != "<ol></ol>" AND $errorCode == 6)
		Functions::sendEmail("support@unbeleadsable.com","support@unbeleadsable.com","Gateway Fails for Unbeleadsable on ". date('Y-m-d'),$gatewayError);
}/*else
	Functions::sendEmail("support@unbeleadsable.com","Recurring Cron for Unbeleadsable on ". date('Y-m-d'),"PIIIKACHUUU!!!");*/
	

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

			$GLOBALS['messTran'] .= "<b>Merchant Gateway Response:</b> " . $errorMessage . " (" . $errorCode . ")<br/>";
			$GLOBALS['error'] = true;
			$GLOBALS['errorCode'] = $errorCode;
		}else{
			$count=0;

			while(isset($resArray["L_SHORTMESSAGE".$count])) {       
				$errorCode    = $resArray["L_ERRORCODE".$count];
				$shortMessage = $resArray["L_SHORTMESSAGE".$count];
				$longMessage  = $resArray["L_LONGMESSAGE".$count]; 
				$count=$count+1;                   
				
				$GLOBALS['messTran'] .= "<b>Merchant Gateway Response:</b> " . $longMessage . " (" . $errorCode . ")<br/>";
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