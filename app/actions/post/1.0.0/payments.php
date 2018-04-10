<?php
	switch ($_POST['case']) {
		case "add":
			$_POST['agent_fk'] = $_SESSION['user']['agent_id'];
			$_POST['name'] = Functions::encode($_POST['name']);
			$_POST['num'] = Functions::encode($_POST['num']);

			$db->resetSelected($_POST['agent_fk']);
			$db->addCreditCard($_POST);

			$resultObj['refresh'] = true;
			$resultObj['after-success'] = "Credit Card added successfully";
			Tokenizer::delete(array('post-action-payments','post-case-payments-add'));
		break;

		case "update":
			$_POST['agent_fk'] = $_SESSION['user']['agent_id'];
			$_POST['name'] = Functions::encode($_POST['name']);
			$_POST['num'] = Functions::encode($_POST['num']);

			$db->resetSelected($_POST['card']);
			$db->selectCreditCard(1, $_POST['card']);
			$db->updateCreditCard($_POST);

			$resultObj['refresh'] = true;
			$resultObj['after-success'] = "Credit Card updated successfully";
			Tokenizer::delete(array('post-action-payments','post-case-payments-update'));

		break;
		case "select":
			$db->resetSelected($_POST['agent_fk']);
			/*if($_POST['secondary'] != "")
				$db->selectCreditCard(2, $_POST['secondary']);*/

			if($_POST['primary'] != "")
				$db->selectCreditCard(1, $_POST['primary']);

			$db->upadateAdBudget($_POST['ad_budget_payment']);

			$resultObj['success'] = "Payments Selection updated successfully";
		break;

		case "selectUpdateCard":
			$cc = $db->getSingleCreditCard($_POST['id']);

			$cc['name'] = Functions::decode($cc['name']);
			$cc['num'] = "#### #### #### " . substr(str_replace(array("-", " "), "", Functions::decode($cc['num'])), -4);

			$resultObj['callback-data'] = $cc;
			$resultObj['callback'] = "set_credit_card";
			$resultObj['no-message'] = true;
		break;
		case "delete-cc":
			$db->deleteCreditCard($_POST['id']);
			$resultObj['refresh'] = true;
			$resultObj['after-success'] = "Credit Card deleted successfully";
			Tokenizer::delete(array('post-action-payments','post-case-delete-cc'));

		break;
		case "mailTo":
			$to = $_SESSION['user']['agent_email'];
			$name = $_SESSION['user']['agent_name'];
			$from = "support@unbeleadsable.com";
			$subject = "Invoice requested on " . Functions::userFriendlyDate(time());
			$message = "Hello there $name,<br> You've requested to receive your invoice by email. <br><br>";
			$message .= "To view your receipt please <a href='".WEBSITE_URL."/app/receipt/".$_POST['id']."'>click here</a>";

			Functions::sendEmail($from,$to,$subject,$message);
			$resultObj['callback'] = "swal";
			$resultObj['callback-data'] = array("title" => "Invoice!",
					"message" => "Invoice sent by email successfully",
					"type" => "success");
			$resultObj['no-message'] = true;
			Tokenizer::delete(array('post-action-payments','post-case-mailTo'));
		break;
		case "any_payment":
			require("{$_SERVER['DOCUMENT_ROOT']}/app/models/paypal/config.php");
			require("{$_SERVER['DOCUMENT_ROOT']}/app/models/paypal/any_payment.php");

			if($mess != "")
				$resultObj['error'] = $mess;
		break;
	}
?>