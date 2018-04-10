<?php
	define('HE_LOG_FILE', "{$_SERVER['DOCUMENT_ROOT']}/home-listings/registration.log");
	include("../app/head.php");

	if(isset($_POST['action']) && $_POST['action'] == 'addBuyer'){
		$id = $db->addBuyerLead($_POST['email'], $_POST['agent'], $_POST['src'], $_POST['lang'], 12);
		$_SESSION['current_lead'] = $id;
		echo $id;
		exit();
	}else if(isset($_POST['action']) && $_POST['action'] == 'updateField'){
		$res = $db->updateBuyerLeadPartial($_POST['name'], $_POST['val'], $_POST['id']);

		if($res && !isset($_SESSION['emailSent'])){
			$agent = $db->getAgentLandingPage($_SESSION['got']['a']);

			$to = $agent['agent_email'];
			$from = "support@unbeleadsable.com";

			$message = "<div style='padding:20px;text-align:justify;'>";

			if($agent['agent_lang'] == "EN"){
				$subject = "New Lead Registration (Buyer)";
				$message .= "Hello " . $agent['agent_name'] . ", you have a new buyer lead registration.<br><br>";
				$message .= "<b>Name: </b>".$res['name']."
							<br><b>Email: </b>".$res['email']."
							<br><b>Phone: </b>".$res['phone']."
							<br><b>Bed(s): </b>".$res['address']."
							<br><b>Buying In: </b>".Functions::getSellingIn($res['buying'], 'EN');
				$message .= "<br><br>To login in your account in order to view your leads <a href='".WEBSITE_URL."'>Click here</a>.<br>";
			}else if($agent['agent_lang'] == "FR"){
				$subject = "Nouveau prospect (Acheteur)";
				$message .= "Bonjour " . $agent['agent_name'] . ", vous avez un nouveau prospect.<br><br>";
				$message .= "<b>Nom: </b>".$res['name']."
							<br><b>Courriel: </b>".$res['email']."
							<br><b>Téléphone: </b>".$res['phone']."
							<br><b>Chambre(s): </b>".$res['address']."
							<br><b>Date d'achat: </b>".Functions::getSellingIn($res['selling'], 'FR');
				$message .= "<br><br>Pour vous connecter à votre compte afin de voir vos prospects <a href='".WEBSITE_URL."'>Cliquez ici</a>.<br>";
			}

			$message .= "</div>";

			if($agent['email_alert'] == 1)
				Functions::sendEmail($from,$to,$subject,$message);

			$id = "AC56ea9bbf60388f2c561911073dbbd132";
			$token = "69b120a331f8972f5071f294cf90ca2f";
			$url = "https://api.twilio.com/2010-04-01/Accounts/$id/SMS/Messages";
			$from = "+14387938975";
			$to = "+1".str_replace(array(" ", "(", ")", "-", "."), array("", "", "", "", ""), $agent['agent_phone']);

			if($agent['agent_lang'] == "EN") 
				$body = "New leads registration\nName: ".$res['name']."\nPhone: ".$res['phone']."\nEmail: ".$res['email']."\nAddress: ".$res['address'] ."\nSelling In: " .Functions::getSellingIn($res['selling'], 'EN'). "\n\nUnbeleadsable";
			else
				$body = "Un nouveau prospect\nNom: ".$res['name']."\nTéléphone: ".$res['phone']."\nCourriel: ".$res['email']."\nAdresse: ".$res['address'] ."\nDate de vente: ".Functions::getSellingIn($res['selling'], 'FR'). "\n\nUnbeleadsable";

			$data = array (
				'From' => $from,
				'To' => $to,
				'Body' => $body,
			);

			$post = http_build_query($data);
			$x = curl_init($url );
			curl_setopt($x, CURLOPT_POST, true);
			curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($x, CURLOPT_USERPWD, "$id:$token");
			curl_setopt($x, CURLOPT_POSTFIELDS, $post);

			if($agent['phone_alert'] == 1)
				curl_exec($x);
			
			curl_close($x);

			$_SESSION['emailSent'] = true;
			$db->addMessageHistory("Lead Generated", "Landing Page: Default", "N/A", $res['id']);

			//Log entry
			$text = '[' . date ('m/d/Y g:i A') . '] - '. $res['name'] . "; " . $res['email'] . "; " . $res['phone'] . "; " . $res['address'] . " :: (" . $agent['agent_name'] . ")";

			// Write to log
			$fp = fopen (HE_LOG_FILE , 'a');
			fwrite ($fp, $text . "\n");
			fclose ($fp );
			chmod (HE_LOG_FILE , 0600);
		}
	}
?>