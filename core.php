<?php
	include("app/head.php");

	if(isset($_POST['signUp'])){
		$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

		if(trim($_POST['name']) == "" || trim($_POST['email']) == "" || trim($_POST['phone']) == "" || trim($_POST['areas']) == "" || trim($_POST['state']) == "" || trim($_POST['agency']) == ""){
			$msg = $_POST['lang'] == 'EN'?'Sorry, all fields are required!':'Désolé, tous les champs sont nécessaires!';
			header('Content-type: text/json');
			echo json_encode(array("error"=>'1', "msg"=>$msg));
			exit();
		}

		if(!preg_match($email_exp,$_POST['email'])) {
			$msg = $_POST['lang'] == 'EN'?'Sorry, email field is invalid!!':'Désolé, le champs courriel est invalid!';
			header('Content-type: text/json');
			echo json_encode(array("error"=>'1', "msg"=>$msg));
			exit();
		}

		if(!$db->getAgentLeadsByEmail($_POST['email'])){

			$_POST['country'] = explode(",", $_POST['state'])[1];
			$_POST['state'] = explode(",", $_POST['state'])[0];

			if($db->addToAgentLeads($_POST)){
				$to = $_POST['email'];
				$from = "support@unbeleadsable.com";

				if($_POST['lang'] == 'EN'){
					$subject = "Registration Confirmation";
					$template = $db->getTemplateBySlug('registration-confirmation-en');
				}else{
					$subject = "Confirmation d'inscription";
					$template = $db->getTemplateBySlug('registration-confirmation-fr');
				}

				//confirmation email to lead
				$message = html_entity_decode($template['content']);
				Functions::sendEmail($from,$to,$subject,$message);

				//confirmation email to admin(s)
				$to = 'sales@unbeleadsable.com';
				$to2 = 'matteofiorilli@gmail.com';

				$from = "support@unbeleadsable.com";
				$subject = "New Lead Registration";
				$message = "Hello there, just to let you know you have new lead registration.<br><br>";
				$message .= "Name: " . $_POST['name'] . "<br>";
				$message .= "Email: " . $_POST['email'] . "<br>";
				$message .= "Phone: " . $_POST['phone'] . "<br>";
				$message .= "Area: " . $_POST['areas'] . "<br>";
				$message .= "Province: " . $_POST['state'] . "<br>";
				$message .= "Agency: " . $_POST['agency'] . "<br>";
				$message .= "Language: " . $_POST['lang'] . "<br>";

				if($_POST['ref'] != "")
					$message .= "Reference Code: " . $_POST['ref'] . "<br>";

				$message .= "<br>Thank, you";

				Functions::sendEmail($from,$to,$subject,$message);
				Functions::sendEmail($from,$to2,$subject,$message);

				/*SMS to Matteo*/
				$id 	= "AC56ea9bbf60388f2c561911073dbbd132";
				$token 	= "69b120a331f8972f5071f294cf90ca2f";
				$url 	= "https://api.twilio.com/2010-04-01/Accounts/$id/Messages";
				$from 	= "+14387938975";
				$to 	= "+15147076288";

				$body = "New lead registration\nName: ".$_POST['name']."\nPhone: ".$_POST['phone']."\nEmail: ".$_POST['email']."\nReference Code: ".$_POST['ref'];

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
				curl_exec($x);
				curl_close($x);$msg = "";

      }else
        $msg = "";

    }else
      $msg = $_POST['lang'] == 'EN'?'Sorry, email already in use!':'Désolé, ce courriel est déjà existant!';


	header('Content-type: text/json');


    if($msg == "")
      echo json_encode(array("success"=>'1', "msg"=>$msg, "lang"=>$_POST['lang']));
    else
      echo json_encode(array("success"=>'0', "msg"=>$msg));

    die();
  }

  if(isset($_POST['contactUs'])){
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

    if(trim($_POST['name']) == "" || trim($_POST['email']) == "" || trim($_POST['phone']) == "" || trim($_POST['message']) == ""){
      $msg = $_POST['lang'] == 'EN'?'Sorry, all fields are required!':'Désolé, tous les champs sont nécessaires!';
      header('Content-type: text/json');
      echo json_encode(array("error"=>'1', "msg"=>$msg));
      exit();
    }

		if(!preg_match($email_exp,$_POST['email'])) {
			$msg = $_POST['lang'] == 'EN'?'Sorry, email field is invalid!!':'Désolé, le champs courriel est invalid!';
			header('Content-type: text/json');
			echo json_encode(array("error"=>'1', "msg"=>$msg));
			exit();
		}

    //confirmation email to admin(s)
		$to = 'support@unbeleadsable.com';
		$to2 = 'barbara@unbeleadsable.com';
		$to3 = 'matteo@unbeleadsable.com';

		$from = "support@unbeleadsable.com";

		$subject = "Contact request from Unbeleadsable";
		$message = "Hello there, You have a new message from your website.<br><br>";
		$message .= "Name: " . $_POST['name'] . "<br>";
		$message .= "Email: " . $_POST['email'] . "<br>";
		$message .= "Phone: " . $_POST['phone'] . "<br>";
		$message .= "Message: <br>" . $_POST['message'] . "<br>";
		$message .= "<br>Thank, you";

		Functions::sendEmail($from, $to, $subject, $message);
		Functions::sendEmail($from, $to2, $subject, $message);
		Functions::sendEmail($from, $to3, $subject, $message);

		$msg = "";

		header('Content-type: text/json');
		echo json_encode(array("success"=>'1', "msg"=>$msg));

		die();
	}

?>
