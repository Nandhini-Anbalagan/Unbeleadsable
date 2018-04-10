<?php
	define('HE_LOG_FILE', "{$_SERVER['DOCUMENT_ROOT']}/home-evaluation/registration.log");
	include("../app/head.php");

	if(isset($_POST['action']) && $_POST['action'] == 'addAddress'){
		if($_POST['apt'] != "")
			$_POST['address'] = "#".$_POST['apt'] . " " . $_POST['address'];

		$frenchID = $db->getFunnelCatByTitle('Home Evaluation FR', $_POST['agent'])['id'];
		$englishID = $db->getFunnelCatByTitle('Home Evaluation EN', $_POST['agent'])['id'];

		if($_POST['lang'] == 'e')
			$funnelID = $englishID;
		else
			$funnelID = $frenchID;

		echo $db->addAddressLead($_POST['address'], $_POST['agent'], $_POST['src'], $_POST['lang'], $funnelID);
		exit();
	}else if(isset($_POST['action']) && $_POST['action'] == 'updateField'){
		$res = $db->updateHomeLeadsPartial($_POST['name'], $_POST['val'], $_POST['id']);
		echo json_encode($_POST['id']);
		if($res && !isset($_SESSION['emailSent'])){
			$agent = $db->getSellerLandingPage($_SESSION['got']['a']);
			$from = "support@unbeleadsable.com";
			$emails = array($agent['agent_email']);

			//add team on email list for notifications
			foreach ($db->getTeamUsers($agent['user_id']) as $value)
				$emails[] = $value['email'];

			$message = "<div style='padding:20px;text-align:justify;'>";

			if($agent['agent_lang'] == "EN"){
				$subject = "New Lead Registration";
				$message .= "Hello " . $agent['agent_name'] . ", you have a new lead registration.<br><br>";
				$message .= "<b>Name: </b>".$res['name']."
							<br><b>Email: </b>".$res['email']."
							<br><b>Phone: </b>".$res['phone']."
							<br><b>Address: </b>".$res['address']."
							<br><b>Selling In: </b>".Functions::getSellingIn($res['selling'], 'EN');
				$message .= "<br><br>To login in your account in order to view your leads <a href='".WEBSITE_URL."'>Click here</a>.<br>";
			}else if($agent['agent_lang'] == "FR"){
				$subject = "Nouveau prospect";
				$message .= "Bonjour " . $agent['agent_name'] . ", vous avez un nouveau prospect.<br><br>";
				$message .= "<b>Nom: </b>".$res['name']."
							<br><b>Courriel: </b>".$res['email']."
							<br><b>Téléphone: </b>".$res['phone']."
							<br><b>Adresse: </b>".$res['address']."
							<br><b>Date de vente: </b>".Functions::getSellingIn($res['selling'], 'FR');
				$message .= "<br><br>Pour vous connecter à votre compte afin de voir vos prospects <a href='".WEBSITE_URL."'>Cliquez ici</a>.<br>";
			}

			$message .= "</div>";

			if($agent['email_alert'] == 1)
				foreach ($emails as $value)	Functions::sendEmail($from,$value,$subject,$message);

			$id = "AC56ea9bbf60388f2c561911073dbbd132";
			$token = "69b120a331f8972f5071f294cf90ca2f";
			$url = "https://api.twilio.com/2010-04-01/Accounts/$id/Messages";
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

			if($agent['agent_lang'] == "EN")
				$db->addMessageHistory("Lead Generated", "Landing Page: Default", "This is a genrated message, no message was sent to the lead.", $res['id']);
			else if($agent['agent_lang'] == "FR")
				$db->addMessageHistory("Prospect généré", "Page d\\'accueil: Initial", "Il s'agit d'un message généré automatiquement lors de la création de ce prospect et non d'un message envoyé à ce dernier.", $res['id']);

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