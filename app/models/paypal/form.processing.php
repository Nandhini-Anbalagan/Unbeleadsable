<?php

$cctype = (!empty($_POST['cctype']))?strip_tags(str_replace("'","`",strip_tags($_POST['cctype']))):'';
$ccname = (!empty($_POST['ccname']))?strip_tags(str_replace("'","`",strip_tags($_POST['ccname']))):'';
$ccn = (!empty($_POST['ccn']))?strip_tags(str_replace("'","`",strip_tags($_POST['ccn']))):'';
$exp1 = (!empty($_POST['exp1']))?strip_tags(str_replace("'","`",strip_tags($_POST['exp1']))):'';
$exp2 = (!empty($_POST['exp2']))?strip_tags(str_replace("'","`",strip_tags($_POST['exp2']))):'';
$cvv = (!empty($_POST['cvv']))?strip_tags(str_replace("'","`",strip_tags($_POST['cvv']))):'';
$user_id = IDObfuscator::decode($_POST['user_id']);

if(verifyCC()){
	require_once 'paypal.callerservice.php';
	switchCC($cctype);
	$error = true;

	/*$once = oneTime(INSTALLATION, "Setup Fees (One Time Payment)");*/

	if($amount < 150){
		if($lang == "EN")
			$mess = '<div class="error"><p><strong>Error!</strong> Sorry, the minimum Ad Budget for the first month is $150.00 </p></div><br />';
		else if($lang == "FR")
			$mess = '<div class="error"><p><strong>Erreur!</strong> Désolé, le paiement minimum pour le campagne publicitaire est de $150.00 pour le premier mois.</p></div><br />';
		
		return;
	}

	$response = payment((INSTALLATION+SUBSCRIPTION+$amount), "Setup Fees (One Time Payment) + Subscription Fee + Ad Campaign (Recurring Every Month)");

	if(!$error){
		$address .= ", " . $city . ", " . $state . " " . $zip . ", " . $country;
		$password = Functions::encode(rand(0,999999));
		
		//Convert lead to an agent
		$agent = $db->createAgent(IDObfuscator::decode($_POST['lead_id']), $address, $amount, $password);

		//Save Credit Card Info
		$data = array('name' => Functions::encode($ccname), 'type'=>$cctype, 'num' => Functions::encode($ccn), 'mm' => $exp1, 'year' => $exp2, 'cvv' => $cvv, 'payment' => 1, 'id' => $agent['agent_id']);
		$db->addDefaultCreditCard($data);

		//Add Invoice and Invoice details
		$db->addInvoice(array('invoice_num'=>$response['transactionID'],'install'=>INSTALLATION,'monthly'=>SUBSCRIPTION,'ads'=>$amount,'agent_id'=>$agent['agent_id']));
		$db->addReccuringProfile($agent['agent_id']);
		
		$to = $agent['agent_email'];
		$from = "support@unbeleadsable.com";

		if($agent['agent_lang'] == "EN"){
			$subject = "Congrats! You're in.";
			$message = "<h2>Congrats! You're in.</h2>";
			$message .= "Thank you for your registration ". $agent['agent_name'] . " and welcome to your Pro account!<br><br>";
			$message .= "We'll set up your landing page, follow-up funnel & your ad campaigns within 48 hours. <br><br>In the meantime, take a look around, explore the training videos and join a live training class!";
			$message2 = "<br><br>To view your receipt online please <a href='".WEBSITE_URL."/app/receipt/".$response['transactionID']."'>click here</a><br><br>";
			
			if($user_id == 0){
				$message2 .= "<h3>Your login is:</h3>";
				$message2 .= "<b>Login Page:</b> <a href='".WEBSITE_URL."/app'>Click here</a><br>";
				$message2 .= "<b>User: </b>" . str_replace(array(" ", "-"), "", strtolower($agent['agent_name'])) . $agent['agent_id'] ."<br>";
				$message2 .= "<b>Password: </b>" . $password ."<b><br><br>";
			}else{
				$message2 .= "<h3>Your login is the same as your initial account.</h3>";
				$message2 .= "<b>Login Page:</b> <a href='".WEBSITE_URL."/app'>Click here</a><br>";
			}
				
			$message3 = "<div style='text-align:center'><em>Reply to this email if you have any questions!</em></div><br>";
			$message3 .= "<div style='text-align:center'><em>If you can't click on the link please copy and paste it onto your address bar!</em></div>";

		}else if($agent['agent_lang'] == "FR"){
			$subject = "Félicitations! Vous êtes la bienvenue.";
			$message = "<h2>Félicitations! Vous êtes la bienvenue.</h2>";
			$message .= "Nous vous remercions de votre inscription ". $agent['agent_name'] . " et bienvenue à votre compte Pro!<br><br>";
			$message .= "Nous allons mettre en place votre page de destination, les courriels de suivi et vos campagnes publicitaires dans les 48 heures. <br><br>En attendant, jetez un coup d'oeil autour, explorer les vidéos de formation et de participer à un cours de formation en direct!";
			$message2 = "<br><br>Pour afficher votre reçu en ligne s'il vous plaît <a href='".WEBSITE_URL."/app/receipt/".$response['transactionID']."'>cliquez ici</a><br><br>";
			
			if($user_id == 0){
				$message2 .= "<h3>Pour vous connecter:</h3>";
				$message2 .= "<b>Page d'authentification:</b> <a href='".WEBSITE_URL."/app'>Click here</a><br>";
				$message2 .= "<b>Utilisateur: </b>" . str_replace(array(" ", "-"), "", strtolower($agent['agent_name'])) . $agent['agent_id'] ."<br>";
				$message2 .= "<b>Mot de passe: </b>" . $password ."<b><br><br>";
			}else{
				$message2 .= "<h3>Pour vous connecter, il suffit d'utiliser les mêmes authentifications que votre compte initial.</h3>";
				$message2 .= "<b>Page d'authentification:</b> <a href='".WEBSITE_URL."/app'>Click here</a><br>"; 
			}

			$message3 = "<div style='text-align:center'><em>Répondre à ce message si vous avez des questions!</em></div>";
			$message3 .= "<div style='text-align:center'><em>Si vous ne pouvez pas cliquer sur le lien s'il vous plaît copiez et collez-le dans votre barre d'adresse!</em></div>";

		}
		
		Functions::sendEmail($from,$to,$subject,$message.$message2.$message3);

		//Send Email to Admins
		$to = 'support@unbeleadsable.com';

		$from = "support@unbeleadsable.com";
		$subject = "Lead Payment";
		$message = "Hello there, just to let you know " . $agent['agent_name'] . " have paid his installation and subscription fee<br><br>";
		$message .= "Thank, you <br><br>----<br><br>";

		//confirmation email to admin(s)
		Functions::sendEmail($from,$to,$subject,$message.$message2);
	   
		if($lang == "EN"){
			$status ="<br/><div>Transaction Successful!<br/>";
			$status .="Thank you for your payment<br/><br/>";
			$status .= "You will receive a confirmation email within 5 minutes.";
		}else if($lang == "FR"){
			$status ="<br/><div>Transaction réussie!<br/>";
			$status .="Merci pour votre paiement<br/><br/>";
			$status .= "Vous recevrez un email de confirmation dans les 5 minutes.";
		}

		$mess = '<div class="alert alert-success fade in success">'.$status.'</div></div><br />';

		$from = "support@unbeleadsable.com";
		$subject = "New Payment Received";
		$message = $response['msgAdmin'] . "<br><br>" . $response['msgClient'];
	
		Functions::sendEmail($from,$admin_email,$subject,$message);
	}
}

function verifyCC(){
	$continue = false;
	if(empty($GLOBALS['ccn']) || empty($GLOBALS['cctype']) || empty($GLOBALS['exp1']) || empty($GLOBALS['exp2']) || empty($GLOBALS['ccname']) || empty($GLOBALS['cvv']) || empty($GLOBALS['address']) || empty($GLOBALS['state']) || empty($GLOBALS['city'])){
		$continue = false;

		if($GLOBALS['lang'] == "EN"){
			$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> Not all required fields were filled out.</p></div><br />';
		}else if($GLOBALS['lang'] == "FR"){
			$GLOBALS['mess'] = '<div class="error"><p><strong>Erreur!</strong> Tous les champs obligatoires n\'ont pas été remplis.</p></div><br />';
		}
	} else
		$continue = true;

	if(!is_numeric($GLOBALS['cvv'])){
		$continue = false;
		if($GLOBALS['lang'] == "EN"){
			$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> CVV number can contain numbers only.</p></div><br />';
		}else if($GLOBALS['lang'] == "FR"){
			$GLOBALS['mess'] = '<div class="error"><p><strong>Erreur!</strong> Numéro CVV peut contenir que des chiffres.</p></div><br />';
		}
	} else
		$continue = true;

	if(!is_numeric($GLOBALS['ccn'])){
		$continue = false;
		if($GLOBALS['lang'] == "EN"){
			$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> Credit Card number can contain numbers only.</p></div><br />';
		}else if($GLOBALS['lang'] == "FR"){
			$GLOBALS['mess'] = '<div class="error"><p><strong>Erreur!</strong> Le numéro de carte de crédit peut contenir que des chiffres.</p></div><br />';
		}
	} else
		$continue = true;

	if(date("Y-m-d", strtotime($GLOBALS['exp2']."-".$GLOBALS['exp1']."-01")) < date("Y-m-d")){
		$continue = false;
		if($GLOBALS['lang'] == "EN"){
			$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> Your credit card is expired.</p></div><br />';
		 }else if($GLOBALS['lang'] == "FR"){
			$GLOBALS['mess'] = '<div class="error"><p><strong>Erreur!</strong> Votre carte de crédit est expirée.</p></div><br />';
		}
	} else
		$continue = true;

	if($continue){
		if(validateCC($GLOBALS['ccn'],$GLOBALS['cctype']))
			$continue = true;
		else {
			$continue = false;
			if($GLOBALS['lang'] == "EN"){
				$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> The number you\'ve entered does not match the card type selected.</p></div><br />';
			}else if($GLOBALS['lang'] == "FR"){
				$GLOBALS['mess'] = '<div class="error"><p><strong>Erreur!</strong> Le numéro que vous avez saisi ne correspond pas au type de carte sélectionné.</p></div><br />';
			}
		}
	}

	if($continue){
		if(luhn_check($GLOBALS['ccn']))
			$continue = true;
		else {
			$continue = false;
			if($GLOBALS['lang'] == "EN"){
				$GLOBALS['mess'] = '<div class="error"><p><strong>Error!</strong> Invalid credit card number.</p></div><br />';
			}else if($GLOBALS['lang'] == "FR"){
				$GLOBALS['mess'] = '<div class="error"><p><strong>Erreur!</strong> Numéro de carte de crédit invalide.</p></div><br />';
			}
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

			if($GLOBALS['lang'] == "EN"){
				$my_status = "<div>Transaction Un-successful!<br/>";
				$my_status .= "There was an error with your credit card processing.<br/>";
				//$my_status .= "Merchant Gateway Response: " . $errorMessage . ", Error: " . $errorCode . "<br/>";
			}else if($GLOBALS['lang'] == "FR"){
				$my_status = "<div>Transaction non-réussie<br/>";
				$my_status .= "Il y avait une erreur avec votre traitement de carte de crédit.<br/>";
				//$my_status .= "Réponse Gateway Merchant: " . $errorMessage . ", Error: " . $errorCode . "<br/>";
			}

			$my_status .= "</div>";
			$GLOBALS['error'] = true;
			$GLOBALS['mess'] = '<div class="error">' . $my_status . '</div></div><br />';
		}else{
			$count=0;
			if($GLOBALS['lang'] == "EN"){
				$my_status="<div>Transaction Un-successful!<br/>";
				$my_text="There was an error with your credit card processing.<br/>";
			}else if($GLOBALS['lang'] == "FR"){
				$my_status="<div>Transaction non-réussie!<br/>";
				$my_text="Il y avait une erreur avec votre traitement de carte de crédit.<br/>";
			}

			/*while (isset($resArray["L_SHORTMESSAGE".$count])) {       
				$errorCode    = $resArray["L_ERRORCODE".$count];
				$shortMessage = $resArray["L_SHORTMESSAGE".$count];
				$longMessage  = $resArray["L_LONGMESSAGE".$count]; 
				$count=$count+1;                   
				$my_text.="Error Code: ".$errorCode."<br/>";
				$my_text.="Error Message: ".$longMessage."<br/>";
			}*/

			$my_status .= $my_text."</div>";
			$GLOBALS['error'] = true;
			$GLOBALS['mess'] = '<div class="error">'.$my_status.'</div><br />';
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
	fwrite ($fp, $text . "\n\n\n");
	fclose ($fp );
	chmod (PAYPAL_LOG_FILE , 0600);
}

?>