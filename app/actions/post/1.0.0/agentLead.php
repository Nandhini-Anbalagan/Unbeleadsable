<?php
switch ($_POST['case']) {
	case "comments":
		$db->addCommentsHomeLeads($_POST);
		$resultObj['success'] = "Comment  added successfully";
	break;

	case "task":
		$_POST['date'] = date("Y-m-d H:i:s", strtotime($_POST['date'] . " " . $_POST['time']));
		$db->addTask($_POST);
		$resultObj['refresh'] = true;
		$resultObj['after-success'] = "Task  added successfully";
	break;

	case "selling":
		$db->updateSelling($_POST);
		$resultObj['no-message'] = true;
		$resultObj['refresh'] = true;
	break;

	case "selectFunnel":
	$db->updateLeadFunnel($_POST['id'], $_POST['funnel']);
	$resultObj['after-success'] = "Funnel updated successfully";
	$resultObj['refresh'] = true;
	break;

	case "status":
		$db->updateStatus($_POST);
		$resultObj['no-message'] = true;
		$resultObj['refresh'] = true;
	break;

	case "type":
		$db->updateType($_POST);
		$resultObj['no-message'] = true;
		$resultObj['refresh'] = true;
	break;

	case "funnel":
		if($_POST['switch'] == 'true')
			$_POST['switch'] = 1;
		else
			$_POST['switch'] = 0;

		$db->updateFunnelSwitch($_POST);
		$resultObj['no-message'] = true;
	break;

	case "taskDone":
		if($_POST['done'] == 'true')
			$_POST['done'] = 2;
		else
			$_POST['done'] = 1;

		$db->updateTask($_POST);
		$resultObj['no-message'] = true;
	break;

	case "taskDelete":
		$db->deleteTask($_POST['id']);
		$resultObj['no-message'] = true;
		$resultObj['refresh'] = true;
	break;

	case "delete-bulk":
		$db->deleteBulkLeads($_POST['ids'], $_SESSION['user']['agent_slug']);
		$resultObj['after-success'] = "Leads deleted successfully";
		$resultObj['refresh'] = true;
	break;

	case "delete-bulk-address":
		$db->deleteBulkLeadsAddress($_POST['ids']);
		$resultObj['after-success'] = "Addresses deleted successfully";
		$resultObj['refresh'] = true;
	break;

	case "edit":
		if(isset($_POST['id'])){
			$db->updateHomeLead($_POST);
			$resultObj['after-success'] = "Lead edited successfully ";
			$resultObj['refresh'] = true;
		}else
			$resultObj['error'] = "Invalid Lead.";
	break;

	case "recover":
		if(isset($_POST['id'])){
			$db->recoverHomeLead($_POST['id'], $_SESSION['user']['agent_slug']);
			$resultObj['after-success'] = "Lead recovered successfully";
			$resultObj['refresh'] = true;
			Tokenizer::delete(array('post-action-agentLead', 'post-case-agentLead-recover'));
		}else
			$resultObj['error'] = "Invalid Lead.";
	break;

	case "delete-forever":
		if(isset($_POST['id'])){
			$db->DeleteForeverHomeLead($_POST['id'], $_SESSION['user']['agent_slug']);
			$resultObj['after-success'] = "Lead deleted successfully";
			$resultObj['refresh'] = true;
			Tokenizer::delete(array('post-action-agentLead', 'post-case-agentLead-delete-forever'));
		}else
			$resultObj['error'] = "Invalid Lead.";
	break;

	case "delete-forever-bulk":
		if(isset($_POST['ids'])){
			$db->DeleteForeverBulkHomeLead($_POST['ids'], $_SESSION['user']['agent_slug']);
			$resultObj['after-success'] = "Leads deleted successfully";
			$resultObj['refresh'] = true;
			Tokenizer::delete(array('post-action-agentLead', 'post-case-agentLead-delete-forever-bulk'));
		}else
			$resultObj['error'] = "Invalid Lead.";
	break;

	case "recover-bulk":
		if(isset($_POST['ids'])){
			$db->recoverBulkHomeLead($_POST['ids'], $_SESSION['user']['agent_slug']);
			$resultObj['after-success'] = "Leads deleted successfully";
			$resultObj['refresh'] = true;
			Tokenizer::delete(array('post-action-agentLead', 'post-case-agentLead-recover-bulk'));
		}else
			$resultObj['error'] = "Invalid Lead.";
	break;

	case "delete":
		$resultObj['callback'] = "swal";
		$resultObj['no-message'] = true;

		if(isset($_POST['id'])){
			$resultObj['callback-data'] = array("title" => "Deleted!",
				"message" => $_POST['name'] . " has been deleted.",
				"type" => "success",
				"next-action" => "reload-status");
			Tokenizer::delete(array('post-action-status', 'post-case-delete'));
			$resultObj['delete'] = $db->deleteStatus($_POST['id']);
		}else
			$resultObj['error'] = "Unknown Lead.";

		if($resultObj['error'] != "-1"){
			$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...",
				"type" => "error",
				"next-action" => "reload-leads");
		}
	break;

	case 'selectDropdownFunnel':
		if(isset($_POST['id'])){
			$funnel = $db->getFunnel($_POST['id']);
			if($funnel){
				$resultObj['callback-data'] = $funnel;
				$resultObj['no-message'] = true;
				$resultObj['callback'] = 'select-single-funnel';
			}else
			$resultObj['error'] = "Invalid funnel.";
		}else
			$resultObj['error'] = "Unknown funnel.";
	break;

	case 'singlePartial':
		if(isset($_POST['id'])){
			$lead = $db->getSingleAgentsLead($_POST['id']);
			if($lead){
				$resultObj['callback-data'] = $lead;
				$resultObj['no-message'] = true;
				$resultObj['callback'] = 'get-single-edit-partial-lead';
			}else
			$resultObj['error'] = "Invalid Partial Lead.";
		}else
			$resultObj['error'] = "Unknown Partial Lead.";
	break;

	case "partial-edit":
		if(isset($_POST['delete'])){
			if(!$db->deleteSingleLead($_POST['lead_id']))
				$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
			else
				$resultObj['success'] = "Partial lead deleted successfully.";
		}else{
			$lead = $db->getSingleAgentsLead($_POST['lead_id']);
			if($lead){
				if(!$db->updateLeadPartial($_POST))
					$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
				else{
					if($_POST['name'] != "" AND $_POST['email'] != "" AND $_POST['phone'] != "" AND $_POST['address'] != "")
						$db->updateStatus(array('status'=>1, 'id'=>$_POST['lead_id']));
				}
			}else
				$resultObj['error'] = "Invalid Lead.";
		}

		if($resultObj['error'] == "-1"){
			$resultObj['after-success'] = "Lead edited successfully.";
			$resultObj['refresh'] = true;
			Tokenizer::delete(array('post-action-agentLead','post-case-partial-edit'));
		}
	break;

	case "pause":
		$resultObj['no-message'] = true;
		if(isset($_POST['id'])){
			$lead = $db->getSingleAgentsLead($_POST['id']);
			if($lead){
				if($_POST['switch'] == 'true'){
					$db->removeBlacklist($lead['email']);
					$resultObj['success'] = "not checked";
				}else{
					$db->addBlacklist($lead['email']);
					$resultObj['success'] = "checked";
				}
			}else
				$resultObj['error'] = "Invalid Lead";
		}else
			$resultObj['error'] = "Unknown Lead";
	break;

	case "evaluation-preview":
		if($_POST['id'] != ""){
			$lead = $db->getSingleAgentsLead($_POST['id']);
			$noApt = explode(" ", $lead['address']);

			if(strpos($noApt[0], "#") === 0)
				array_shift($noApt);

			$add = implode(" ", $noApt);
			$google = str_replace(" ", "+", $add);

			if($lead['lang'] == 'f')
				$text = $db->getTemplateBySlug("agents-evaluation-fr")['content'];
			else
				$text = $db->getTemplateBySlug("agents-evaluation-en")['content'];

			$text = str_replace(
				array('[name]', '[address]', '[google]', '[low]', '[high]', '[muni]', '[comments]', '[signature]'),
				array($lead['name'], $lead['address'], $google, number_format($_POST['low'],2), number_format($_POST['high'],2), number_format($_POST['muni'],2), nl2br($_POST['comments']),nl2br($_SESSION['user']['agent_signature'])),
				$text
				);
			$resultObj['callback'] = "show-evaluation-preview";
			$resultObj['callback-data'] = html_entity_decode($text);
			$resultObj['no-message'] = true;
		}else
			$resultObj['error'] = "Sorry Please select a lead or an archieve";

	break;

	case "evaluation-archieve":
		if($_POST['id'] != ""){
			$resultObj['callback'] = "show-evaluation-archieve";
			$resultObj['callback-data'] = $db->getEvaluation(IDObfuscator::decode($_POST['id']));
		}
		$resultObj['no-message'] = true;
	break;

	case "email-evaluation":
		if($_POST['lead'] != ""){
			$lead = $db->getSingleAgentsLead($_POST['lead']);
			$noApt = explode(" ", $lead['address']);

			if(strpos($noApt[0], "#") === 0)
				array_shift($noApt);

			$add = implode(" ", $noApt);
			$google = str_replace(" ", "+", $add);

			if($lead['lang'] == 'e'){
				$text = $db->getTemplateBySlug("agents-evaluation-en")['content'];
				$subject = "Home Evaluation";
			}else{
				$text = $db->getTemplateBySlug("agents-evaluation-fr")['content'];
				$subject = "Évaluation de votre maison";
			}

			$text = str_replace(
				array('[name]', '[address]', '[google]', '[low]', '[high]', '[muni]', '[comments]', '[signature]'),
				array($lead['name'], $lead['address'], $google, number_format($_POST['low'],2), number_format($_POST['high'],2), number_format($_POST['muni'],2), nl2br($_POST['comments']),nl2br($_SESSION['user']['agent_signature'])),
				$text
				);
			if(!$db->getBlacklistEmail($lead['email'])){
				$msg = '<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
				<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
					<td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
						<meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
						<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
								<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
									' . html_entity_decode($text) . '
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>';

				if(Functions::sendEmail($_SESSION['user']['agent_email'], $lead['email'], $subject, $msg, $_SESSION['user']['agent_email'], false)){
					$db->addMessageHistory("Manual Email Sent", $subject, $msg, $lead['id']);
					$db->addEvaluation($lead['id'], $_POST['low'], $_POST['high'], $_POST['muni'], $_POST['comments']);
					$resultObj['success'] = "Email sent successfully";
				}else
					$resultObj['error'] = "There was a problem while sending the email. Please try again later.";
			}else
				$resultObj['error'] = "Sorry this lead is on the unsubscribed list!";
		}else
			$resultObj['error'] = "Sorry Please select a lead.";
	break;

	case "file":
		$ext = pathinfo(basename($_FILES["file"]["name"]), PATHINFO_EXTENSION);
		$fileName = "leadImport_" . $_SESSION['user']['agent_id'] . ".$ext";
		move_uploaded_file($_FILES['file']['tmp_name'], "temp/$fileName");
		$resultObj['no-message'] = true;
		exit($fileName);
	break;

	case "avatar":
		$fileName = "avatar_" . $_SESSION['user']['agent_id'];
		$avatar = new Upload($_FILES['file']);
		if($avatar->uploaded) {
			$avatar->file_new_name_body = $fileName;
			$avatar->image_resize = true;
			$avatar->image_convert = "jpg";
			$avatar->image_x = 250;
			$avatar->image_ratio_y = true;
			$avatar->Process('uploads/avatars/');

			if ($avatar->processed) {
				$resultObj['no-message'] = true;
				$avatar->Clean();
				exit($fileName. ".jpg");
			} else
				$resultObj['error'] = $avatar->error ;
		}
	break;

	case "upload_leads":
		if($_POST['uploadedFile'] != ""){
			$csv = array_map('str_getcsv', file($_SERVER["DOCUMENT_ROOT"] . '/app/temp/' . trim($_POST['uploadedFile'])));
			$no = 0;

			$th = array_shift($csv);
			if(empty($th) || COUNT($th) < 4)
				$th = array_shift($csv);

			foreach ($csv as $value) {
				if(COUNT($value) < COUNT($th))
					continue;

				$value[1000] = "";
				$no++;
				$data = array();
				$data['id'] = NULL;
				$data['agent_fk'] = $_POST['id'];

				$address = "";
				if($_POST['address'] != 1000)
					$address = str_replace("'", "\\'",$value[$_POST['address']]);
				else{
					if($_POST['apt'] != 1000)
						$address .= "#".str_replace("'", "\\'",$value[$_POST['apt']]) . " ";

					if($_POST['civic'] != 1000)
						$address .= str_replace("'", "\\'",$value[$_POST['civic']]) . " ";

					$address .= str_replace("'", "\\'",$value[$_POST['street']]) . ", ";
					$address .= str_replace("'", "\\'",$value[$_POST['city']]) . ", ";
					$address .= str_replace("'", "\\'",$value[$_POST['province']]) . " ";
					$address .= str_replace("'", "\\'",$value[$_POST['postal']]) . ", ";

					if($_POST['country'] != 1000)
						$address .= str_replace("'", "\\'",$value[$_POST['country']]);
					else{
						$arr = explode(",", $_SESSION['user']['area_name']);
						$address .= array_pop($arr);
					}
				}

				$data['address'] = $address;
				$data['name'] = str_replace("'", "\\'", $value[$_POST['name']]);
				$data['phone'] = str_replace("'", "\\'", $value[$_POST['phone']]);
				$data['email'] = str_replace("'", "\\'", $value[$_POST['email']]);
				$data['funnels'] = NULL;
				$data['funnel_switch'] = 0; //funnesl set to off by default
				$data['selling'] = str_replace("'", "\\'", $value[$_POST['selling']]);
				$data['source'] = 'm'; //source is manual by default
				$data['type'] = str_replace("'", "\\'", $value[$_POST['type']]);
				$data['comments'] = str_replace("'", "\\'", $value[$_POST['comments']]);
				$data['status'] = 1;
				$data['lang'] = $_POST['lang'] != 1000?strtolower(substr($value[$_POST['lang']], 0,1)):strtolower(substr($_SESSION['user']['agent_lang'], 0, 1));
				$data['dateAdded'] = ($_POST['date']!= 1000 || $value[$_POST['date']] == '')?date('Y-m-d H:i:s', strtotime($value[$_POST['date']])):date('Y-m-d H:i:s');

				$meta = array();
				$meta['value_range'] = str_replace("'", "\\'", $value[$_POST['value_range']]);
				$meta['value_epp'] = str_replace("'", "\\'", $value[$_POST['value_epp']]);
				$meta['beds'] = str_replace("'", "\\'", $value[$_POST['beds']]);
				$meta['baths'] = str_replace("'", "\\'", $value[$_POST['baths']]);
				$meta['sqft'] = str_replace("'", "\\'", $value[$_POST['sqft']]);
				$meta['buying_frame'] = str_replace("'", "\\'", $value[$_POST['buying_frame']]);
				$meta['price_range'] = str_replace("'", "\\'", $value[$_POST['price_range']]);
				$meta['neighborhood'] = str_replace("'", "\\'", $value[$_POST['neighborhood']]);
				$meta['prequalified'] = str_replace("'", "\\'", $value[$_POST['prequalified']]);
				$meta['lender'] = str_replace("'", "\\'", $value[$_POST['lender']]);
				$meta['lender_phone'] = str_replace("'", "\\'", $value[$_POST['lender_phone']]);
				$meta['lender_email'] = str_replace("'", "\\'", $value[$_POST['lender_email']]);
				$meta['loan_type'] = str_replace("'", "\\'", $value[$_POST['loan_type']]);
				$meta['credit'] = str_replace("'", "\\'", $value[$_POST['credit']]);
				$meta['planning_sell'] = str_replace("'", "\\'", $value[$_POST['planning_sell']]);
				$meta['alert_setup'] = false; //no alert in the system
				$meta['other_contact'] = str_replace("'", "\\'", $value[$_POST['other_contact']]);
				$meta['other_contact_phone'] = str_replace("'", "\\'", $value[$_POST['other_contact_phone']]);
				$meta['other_contact_email'] = str_replace("'", "\\'", $value[$_POST['other_contact_email']]);

				$db->addImportLeads($data, $meta);
			}
			$resultObj['refresh'] = true;
			$resultObj['after-success'] = "Leads imported successfully. Total of " . $no . " imported" ;
		}else
		$resultObj['error'] = "No file selected" ;
	break;

	case 'requestNewArea':
		$data = array();
		$data['buyer_option'] = $_POST['buyer'] == 'buyer' || $_POST['buyer'] == 'both' ? 1 : 0;
		$data['seller_option'] = $_POST['buyer'] == 'seller' || $_POST['buyer'] == 'both' ? 1 : 0;
		$data['name'] = $_SESSION['user']['agent_name'];
		$data['email'] = $_SESSION['user']['agent_email'];
		$data['phone'] = $_SESSION['user']['agent_phone'];
		$data['areas'] = $_POST['desiredArea'];
		$data['agency'] = $_SESSION['user']['agent_agency'];
		$data['lang'] = $_SESSION['user']['agent_lang'];
		$data['ref'] = 'INTERNAL';
		$data['country'] = explode(",", $_POST['desiredState'])[1];
		$data['state'] = explode(",", $_POST['desiredState'])[0];

		$db->addToAgentLeads($data, $_SESSION['user']['user_id']);

		//confirmation email to admin(s)
		$to = 'sales@unbeleadsable.com';

		$from = "support@unbeleadsable.com";
		$subject = "New Lead Registration";
		$message = "Hello there, just to let you know an agent has requested a new Area<br><br>";
		$message .= "Name: " . $_SESSION['user']['agent_name'] . "<br>";
		$message .= "Email: " . $_SESSION['user']['agent_email'] . "<br>";
		$message .= "Phone: " . $_SESSION['user']['agent_phone'] . "<br>";
		$message .= "Area: " . $_POST['desiredArea'] . "<br>";
		$message .= "Province: " . $_POST['desiredState'] . "<br>";
		$message .= "Agency: " . $_SESSION['user']['agent_agency'] . "<br>";
		$message .= "Language: " . $_SESSION['user']['agent_lang'] . "<br>";
		$message .= "Reference Code: INTERNAL<br>";

		$message .= "<br>Thank, you";

		Functions::sendEmail($from,$to,$subject,$message);

		$resultObj['callback'] = "hide-modal";
		$resultObj['success'] = "Request made successfully";
	break;

	case 'manual-add':
		$db->addManualLead($_POST);
		$resultObj['after-success'] = "Leads added successfully";
		$resultObj['refresh'] = true;
	break;

	case "viewMsg":
		$msg = $db->getSingleMessageHistory($_POST['id']);
		$resultObj['callback-data'] = $msg;
		$resultObj['no-message'] = true;
		$resultObj['callback'] = 'get-single-msg';
	break;
}
?>