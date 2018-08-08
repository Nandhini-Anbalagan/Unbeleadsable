<?php
	switch($_POST['case']){
		case "add":
			//$keys = array('name', 'slug', 'content');
			//$types = array('template-name', 'template-slug', 'template-content');
			//if(Functions::isValidFields($_POST, $keys, $types, $resultObj['error'])){
				if($db->getTemplateBySlug($_POST['slug']))
					$resultObj['error'] = "The template slug <b>{$_POST['slug']}</b> is already in use.";
				else{
					$db->addTemplate($_POST);
					$resultObj['callback'] = "add-template";
					$resultObj['success'] = "The template <b>{$_POST['slug']}</b> has been added.";
				}
			//}
			break;

		case "add-group":
			if(!isset($_POST['name']) || strlen(trim($_POST['name'])) < 1)
				$resultObj['error'] = "The group name cannot be empty.";
			else if(strlen(trim($_POST['name'])) < 5 || strlen(trim($_POST['name'])) > 150)
				$resultObj['error'] = "The group name must be between 5 and 150 characters.";
			else if(!isset($_POST['ids']) || strlen(trim($_POST['ids'])) < 1)
				$resultObj['error'] = "The ids provided are invalid.";
			else if($db->getGroupByName(ucfirst(trim($_POST['name']))))
				$resultObj['error'] = "The group <b>{$_POST['name']}</b> already exist.";
			else{
				$ids = explode(',', $_POST['ids']);
				$emails = array();

				foreach($ids as $id){
					$student = $db->getStudent($id);
					if($student && !in_array($student['email'], $emails) && $student['email'] != "" && !$db->getBlacklistEmail($student['email'])){
						array_push($emails, $student['email']);
					}
				}

				if(count($emails) > 0){
					$db->createEmailGroup(ucfirst(trim($_POST['name'])), implode(',', $emails));
					$resultObj['after-success'] = "The group <b>{$_POST['name']}</b> has been created.";
					$resultObj['refresh'] = true;
					$resultObj['delay'] = 0;
				}else
					$resultObj['error'] = "The group cannot be created because there was not one valid email provided.";
			}
			break;

		case "add-to-group":
			if(!isset($_POST['group_id']))
				$resultObj['error'] = "Unknown group.";
			else if(!isset($_POST['ids']) || strlen(trim($_POST['ids'])) < 1)
				$resultObj['error'] = "The ids provided are invalid.";
			else{
				$group = $db->getGroup($_POST['group_id']);
				if($group){
					$emails = explode(',', $group['group_emails']);
					$ids = explode(',', $_POST['ids']);

					foreach($ids as $id){
						$student = $db->getStudent($id);
						if($student && !in_array($student['email'], $emails) && $student['email'] != "" && !$db->getBlacklistEmail($student['email'])){
							array_push($emails, $student['email']);
						}
					}

					$db->updateEmailGroup($group['email_group_id'], implode(',', $emails));
					$resultObj['after-success'] = "The emails has been added to the group.";
					$resultObj['refresh'] = true;
					$resultObj['delay'] = 0;
				}else
					$resultObj['error'] = "Invalid group.";
			}
			break;

		case "delete":
			$resultObj['callback'] = "swal";
			$resultObj['no-message'] = true;
			if(isset($_POST['id'])){
				$template = $db->getTemplate($_POST['id']);
				if($template)
					$db->deleteTemplate($_POST['id']);
				else
					$resultObj['error'] = "Invalid template.";
			}else
				$resultObj['error'] = "Unknown template.";

			if($resultObj['error'] == "-1"){
				$resultObj['callback-data'] = array("message" => "Template " . $template['slug'] . " has been deleted.",
					"type" => "success",
					"next-action" => "add-template");
			}else{
				$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...",
					"type" => "error",
					"next-action" => "add-template");
			}
			break;

		case "delete-group":
			$resultObj['callback'] = "swal";
			$resultObj['no-message'] = true;
			if(isset($_POST['id'])){
				$group = $db->getGroup($_POST['id']);
				if($group)
					$db->deleteGroup($_POST['id']);
				else
					$resultObj['error'] = "Invalid group.";
			}else
				$resultObj['error'] = "Unknown group.";

			if($resultObj['error'] == "-1"){
				$resultObj['callback-data'] = array("message" => "The group " . $group['group_name'] . " has been deleted.",
					"type" => "success",
					"next-action" => "reload-groups");
			}else{
				$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...",
					"type" => "error",
					"next-action" => "reload-groups");
			}
			break;

		case "edit":
			if(isset($_POST['id'])){
				$template = $db->getTemplate($_POST['id']);
				if($template){
					$keys = array('name', 'slug', 'content');
					$types = array(5, 5, 20);
					//$types = array('template-name', 'template-slug', 'template-content');
					if(Functions::isValidFields($_POST, $keys, $types, $resultObj['error'])){
						if($_POST['slug'] != $template['slug'] && $db->getTemplateBySlug($_POST['slug']))
							$resultObj['error'] = "The template slug <b>{$_POST['slug']}</b> is already in use.";
						else{
							$db->updateTemplate($_POST['id'], $_POST);
							$resultObj['callback'] = "add-template";
							$resultObj['success'] = "The template <b>" . $template['slug'] . "</b> has been updated.";
						}
					}
				}else
					$resultObj['error'] = "Invalid template.";
			}else
				$resultObj['error'] = "Unknown template.";
			break;

		case "get-group":
			if(isset($_POST['id'])){
				$group = $db->getGroup($_POST['id']);
				if($group){
					$resultObj['no-message'] = true;
					$resultObj['callback'] = "get-group";
					$resultObj['callback-data'] = array(
						'id' => $group['email_group_id'],
						'name' => $group['group_name'],
						'emails' => explode(',', $group['group_emails'])
					);
				}else
					$resultObj['error'] = "Invalid group.";
			}else
				$resultObj['error'] = "Unknown group.";
			break;

		case "update-group":
			if(isset($_POST['id'])){
				$group = $db->getGroup($_POST['id']);
				if(!isset($_POST['emails']) || strlen(trim($_POST['emails'])) < 1)
					$resultObj['error'] = "The group must have at least one email.";
				else if($group){
					$postEmails = explode(',', $_POST['emails']);
					$emails = array();
					foreach($postEmails as $email){
						if(!$db->getBlacklistEmail($email) && !in_array($email, $emails) && filter_var($email, FILTER_VALIDATE_EMAIL) !== false)
							array_push($emails, $email);
					}
					if(count($emails) < 1)
						$resultObj['error'] = "The group must have at least one valid email.";
					else{
						$db->updateEmailGroup($group['email_group_id'], implode(',', $emails));
						$resultObj['success'] = "The group <b>" . $group['group_name'] . "</b> has been updated.";
						$resultObj['callback'] = 'reload-groups';
					}
				}else
					$resultObj['error'] = "Invalid group.";
			}else
				$resultObj['error'] = "Unknown group.";
			break;

		case "preview":
			if(isset($_POST['id'])){
				$email = $db->getEmail($_POST['id']);
				if($email){
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'preview-email';
					$resultObj['callback-data'] = array(
						'title' => 'To: ' . $email['to'],
						'content' => nl2br(strip_tags(html_entity_decode($email['content'])))
					);
				}else
					$resultObj['error'] = "Invalid email.";
			}else
				$resultObj['error'] = "Unknown email.";
			break;
		case "send":
			if(strlen(strip_tags(html_entity_decode($_POST['content']))) < 1)
				$resultObj['error'] = "The email cannot be empty.";
			else{
				$content = str_replace("&lt;p&gt;&nbsp;&lt;/p&gt;", "", $_POST['content']);
				$content = str_replace("../cdn/attached/", "https://unbeleadsable.com/cdn/attached/", $content);

				$to = array();

				// Either a group id, or group of student emails
				if(is_numeric($_POST['to']) || strpos($_POST['to'], ',') !== false){
					if(is_numeric($_POST['to'])){
						$group = $db->getGroup($_POST['to']);
						$groupEmails = explode(',', $group['group_emails']);
						foreach($groupEmails as $_email){
							if(!in_array($_email, $to) && !$db->getBlacklistEmail($_email))
								array_push($to, $_email);
						}
					}else{
						$_emails = explode(',', $_POST['to']);
						foreach($_emails as $_email){
							if(!in_array($_email, $to) && !$db->getBlacklistEmail($_email))
								array_push($to, $_email);
						}
					}
				}else
					array_push($to, $_POST['to']);


				$sentEmails = 0;
				foreach($to as $receiver){
					$_POST['content'] = $content;
					$agent = $db->getAgentLeadsByEmail($receiver);

					if($agent){
						$_POST['content'] = str_replace(
							array('[lead_name]', '[lead_email]', '[lead_phone]', '[lead_areas]', '[id]'),
							array($agent['lead_name'], $agent['lead_email'], $agent['lead_phone'], $agent['lead_areas'], IDObfuscator::encode($agent['lead_id'])),
							$_POST['content']
						);
					}

					$msg = '<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
						<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
								<meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
								<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
										<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
											' . html_entity_decode($_POST['content']) . '
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';


					if(isset($_POST['subject']))
						$from = $_POST['subject'];
					else
						$from = "Message ". ($agent['lead_lang'] == "EN"?"from ":"de la part d'") . Config::WEBSITE_TITLE;

					if(Functions::sendEmail("support@unbeleadsable.com", $receiver, $from , $msg)){
						$sentEmails++;
						$db->addEmail($receiver, $_POST['content']);
					}
				}

				if($sentEmails > 0){
					$resultObj['after-success'] = "The email" . ($sentEmails > 1 ? "s" : "") . " has been sent.";
					$resultObj['refresh'] = true;
					$resultObj['delay'] = 0;
				}else
					$resultObj['error'] = "There was a problem while sending the email. Please try again later.";
			}
			break;
		case "emailAllAgent":
			if(strlen(strip_tags(html_entity_decode($_POST['content']))) < 1)
				$resultObj['error'] = "Content be empty.";
			else{
				$content = str_replace("&lt;p&gt;&nbsp;&lt;/p&gt;", "", $_POST['content']);
				//$content = Functions::save_images("attached", $_POST['content']);
				$content = str_replace("../cdn/attached/", "https://unbeleadsable.com/cdn/attached/", $content);

				$to = explode(",", $_POST['to']);
				$sentEmails = 0;

				foreach($to as $receiver){
					$agent = $db->getAgentByEmail($receiver);

					if($agent)
						$_POST['content'] = str_replace('[agent_name]', $agent['agent_name'], $content);

					$msg = '<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
						<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
								<meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
								<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
										<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
											' . html_entity_decode($content) . '
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';

					$from = $agent['agent_lang'] == "EN"?"from ":"de la part d'";

					if(Functions::sendEmail("support@unbeleadsable.com", $receiver, "Message $from" . Config::WEBSITE_TITLE, $msg))
						$sentEmails++;
				}

				if($sentEmails > 0){
					$db->addEmail("All Agents", $_POST['content']);
					$resultObj['success'] = "The email" . ($sentEmails > 1 ? "s" : "") . " has been sent.";
					$resultObj['callback'] = "hide-modal";
					$resultObj['reset'] = true;
				}else
					$resultObj['error'] = "There was a problem while sending the email. Please try again later.";
			}
			break;
		case "sendLead":
			if(strlen(strip_tags(html_entity_decode($_POST['content']))) < 1)
				$resultObj['error'] = "The email cannot be empty.";
			else{
				$content = str_replace("&lt;p&gt;&nbsp;&lt;/p&gt;", "", $_POST['content']);
				$content = str_replace("../cdn/attached/", "https://unbeleadsable.com/cdn/attached/", $content);

				$lead = $db->getSingleAgentsLead((int)trim(IDObfuscator::decode($_POST['leadID'])));
				$content = str_replace(array('[NAME]', '[FIRSTNAME]', '[SHORTADDRESS]'), array($lead['name'], $lead['name'], $lead['address']), $content);

				if(!$db->getBlacklistEmail($lead['email'])){
					$msg = '<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
						<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
							<td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
								<meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
								<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
									<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
										<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
											' . html_entity_decode($content) . $_SESSION['user']['agent_signature'] . '
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>';

					if(Functions::sendEmail($_SESSION['user']['agent_email'], $lead['email'], $_POST['subject'], $msg, $_SESSION['user']['agent_name'], false)){
						$db->addMessageHistory("Manual Email Sent", $_POST['subject'], $msg, $lead['id']);
						$resultObj['after-success'] = "The email has been sent.";
						$resultObj['refresh'] = true;
						$resultObj['delay'] = 0;
					}else
						$resultObj['error'] = "There was a problem while sending the email. Please try again later.";
				}else
					$resultObj['error'] = "Sorry this lead is on the unsubscribed list!";
			}
			break;
		case "sendMultipleLead":
			if(strlen(strip_tags(html_entity_decode($_POST['content']))) < 1)
				$resultObj['error'] = "The email cannot be empty.";
			else{
				$content = str_replace("&lt;p&gt;&nbsp;&lt;/p&gt;", "", $_POST['content']);
				$content = str_replace("../cdn/attached/", "https://unbeleadsable.com/cdn/attached/", $content);

				$to = explode(",", $_POST['to']);
				$ids = explode(",", $_POST['ids']);
				$sentEmails = 0;

				foreach($to as $key => $receiver){
					if(!$db->getBlacklistEmail($receiver)){
						$sentEmails++;
						$msg = '<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff">
							<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
								<td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
									<meta itemprop="name" content="Confirm Email" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" />
									<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
										<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
											<td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">
												' . html_entity_decode($content) . $_SESSION['user']['agent_signature'] . '
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>';

						if(Functions::sendEmail($_SESSION['user']['agent_email'], $receiver, $_POST['subject'], $msg, $_SESSION['user']['agent_name'], false)){
							$db->addMessageHistory("Manual Email Sent", $_POST['subject'], $msg, $ids[$key]);
							$sentEmails++;
						}
					}else
						$resultObj['error'] = "Sorry this lead is on the unsubscribed list!";
				}

				if($sentEmails > 0){
					$resultObj['success'] = "The email" . ($sentEmails > 1 ? "s" : "") . " has been sent.";
					$resultObj['callback'] = "hide-modal";
					$resultObj['reset'] = true;
				}else
					$resultObj['error'] = "There was a problem while sending the email. Please try again later.";
			}
		break;
		case "single":
			if(isset($_POST['id'])){
				$template = $db->getTemplate($_POST['id']);
				if($template){
					$resultObj['callback-data'] = $template;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-edit-template';
				}else
					$resultObj['error'] = "Invalid template.";
			}else
				$resultObj['error'] = "Unknown template.";
			break;
	}
?>