<?php
	switch ($_POST['case']) {
		case "singleView":
			if(isset($_POST['id'])){
				$agent = $db->getAgentByID($_POST['id']);
				if($agent){
					$resultObj['callback-data'] = $agent;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-view-agent';
				}else
					$resultObj['error'] = "Invalid Agent.";
			}else
				$resultObj['error'] = "Unknown Agent.";
		break;

		case "singleEdit":
			if(isset($_POST['id'])){
				$agent = $db->getAgentByID($_POST['id']);
				if($agent){
					$resultObj['callback-data'] = $agent;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-edit-agent';
				}else
					$resultObj['error'] = "Invalid Agent.";
			}else
				$resultObj['error'] = "Unknown Agent.";
		break;

		case "singleUser":
			if(isset($_POST['id'])){
				$user = $db->getSingleUser($_POST['id']);
				if($user){
					$resultObj['callback-data'] = $user;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-edit-agent-user';
				}else
					$resultObj['error'] = "Invalid User.";
			}else
				$resultObj['error'] = "Unknown User.";
		break;

		case "delete":
			$resultObj['callback'] = "swal";
			$resultObj['no-message'] = true;
			if(isset($_POST['id'])){
				$agent = $db->deleteAgent($_POST['id']);
				if($agent){
					$resultObj['callback-data'] = array("title" => "Deleted!",
					"message" => $agent['lead_name'] . " has been deleted.",
					"type" => "success",
					"next-action" => "reload-agents");
					Tokenizer::delete(array('post-action-agent', 'post-case-agent-delete'));
					$resultObj['delete'] = $db->deleteAgentLeads($_POST['id']);
				}else
					$resultObj['error'] = "Invalid Agent.";
			}else
				$resultObj['error'] = "Unknown Agent.";

			if($resultObj['error'] != "-1"){
				$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...",
					"type" => "error",
					"next-action" => "reload-agents");
			}
		break;

		case "lock":
			$db->enableDisableAgent($_POST['agent_id'], $_POST['user_id'], "lock");
			$resultObj['callback'] = 'swal';
			$resultObj['callback-data'] = array("title" => "Lock Agent!",
				"message" => $_POST['name'] . " Agent has been locked",
				"type" => "success",
				"next-action" => "reload-agents");
			$resultObj['no-message'] = true;
		break;

		case "unlock":
			$db->enableDisableAgent($_POST['agent_id'], $_POST['user_id'], "unlock");
			$resultObj['callback'] = 'swal';
			$resultObj['callback-data'] = array("title" => "Unlock Agent!",
				"message" => $_POST['name'] . " Agent has been unlocked",
				"type" => "success",
				"next-action" => "reload-agents");
			$resultObj['no-message'] = true;
		break;

		case "edit":
			if(isset($_POST['deleteAgent'])){
				if(!$db->deleteAgent($_POST['id']))
					$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
				else
					$resultObj['success'] = $_POST['name'] . " deleted successfully.";
			}else if(Functions::isValidFields($_POST,
				array('name', 'email', 'phone', 'areas', 'agency'),
				array(5, 'email', 9, 'empty', 2),
				$resultObj['error'])){

				$agent = $db->getAgentByID($_POST['id']);
				if($agent){
					/*if($_POST['email'] != $agent['agent_email'] && $db->getAgentByEmail($_POST['email']))
						$resultObj['error'] = "The email address <b>" . $_POST['email'] . "</b> is already in use.";
					else */if(!$db->editAgent($_POST))
						$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
					else
						$resultObj['success'] = $_POST['name'] . " edited successfully.";
				}else
					$resultObj['error'] = "Invalid Lead.";
			}

			if($resultObj['error'] == "-1"){
				$resultObj['callback'] = "reload-agents";
				Tokenizer::delete(array('post-action-lead','post-action-lead-edit'));
			}
		break;
		case "user":
			if(!$db->editAgentLogin($_POST))
				$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
			else{
				$resultObj['success'] = $_POST['username'] . " edited successfully.";
				$resultObj['callback'] = "reload-agents";
				Tokenizer::delete(array('post-action-agent','post-case-agent-user'));
			}
		break;
		case "feedback":

			$from = $_POST['email'];
			$subject = "Unbeleadsable feedback from " . $_POST['name'] . " on " . Functions::userFriendlyDate(time());
			$message = "<h3>". $_POST['subject'] ."</h3><br>";
			$message .= $_POST['message'];

			Functions::sendEmail($from,"support@unbeleadsable.com",$subject,$message);

			$resultObj['callback'] = "hide-modal";
			$resultObj['reset'] = true;
			$resultObj['success'] = "Feedback sent successfully";
		break;
		case "stats":
			$stats = $db->agentLeadStats($_POST['id'], $_POST['type'], $_POST['range']);

			$data = array(
				'name' => $_POST['name'],
				'range' => $_POST['range'],
				'completed' => $stats['completed'],
				'partial' => $stats['partial']
			);

			$data['address']  = $_POST['type'] == "home_sellers"?$stats['address']:'N/A';

			if($_POST['campaign'] != ''){
				$rangeSplit = explode(" - ", $_POST['range']);
				$rangeSplit[1] = COUNT($rangeSplit)== 2?$rangeSplit[1]:$rangeSplit[0];
				$cId = $_POST['campaign'];
				$fields = "fields=clicks,reach,spend,cpc,ctr";

				$time_range = "";
				if($_POST['range'] != "All")
					$time_range = "&time_range[since]=". $rangeSplit[0] ."&time_range[until]=". $rangeSplit[1];

				$url = "https://graph.facebook.com/v2.8/$cId/insights?access_token=EAAElyDlIGywBAGKC1pMwQLLHa7x6eHVFvKuvYLZBV2PXp33GzVS2KbudOypig27uxZBBFFLDGHZCn9ewStziSZAq98FW6l6w4P4DIZBo9IZCMKYiZACqZBBc93q2P3SQ9xZBIEfziutvM3gtAEbVg9Vzfaf2uWW1R1r4JL3eouAz6ZCAZDZD&appsecret_proof=ef07d0ac9ca718882e882d0cd02938b1f5daea3556e86d133d538d0fd31c3b06&$fields$time_range";
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
				$result = curl_exec($ch);
				curl_close($ch);

				if($result){
					$result_obj = json_decode($result);
					$data['spent'] = $result_obj->data[0]->spend;
					$data['reach'] = $result_obj->data[0]->reach;
					$data['clicks'] = $result_obj->data[0]->clicks;
					$data['cpc'] = $result_obj->data[0]->cpc;
					$data['ctr'] = $result_obj->data[0]->ctr;
				}
			}

			$resultObj['callback'] = "agent-stats";
			$resultObj['callback-data'] = $data;
			$resultObj['no-message'] = true;
		break;

		case "agent-subscription":
			if($_POST['id']){
				$agent = $db->getAgentByID(IDObfuscator::decode($_POST['id']));
				$cc = $db->getCreditCard($agent['agent_id']);

				$addArr = explode(",", $agent['agent_address']);
				$ProCode = explode(" ", $addArr[2]);

				$name = explode(" ",$agent['agent_name']);
				$last = array_pop($name);
				$first = implode(" ", $name);

				$data = array(
					'id' => $agent['agent_id'],
					'user' => $agent['user_id'],
					'lang' => $agent['agent_lang'],
					'fname' => $first,
					'lname' => $last,
					'email' => $agent['agent_email'],
					'street' => $addArr[0],
					'city' => $addArr[1],
					'state' => (isset($ProCode[1])?$ProCode[1]:''),
					'zip' => (isset($ProCode[2])?$ProCode[2]:''),
					'country' => trim(strtoupper($addArr[3])),
					'cc_name' => Functions::decode($cc['name']),
					'cc_num' => Functions::decode($cc['num']),
					'cc_mm' => $cc['mm'],
					'cc_yy' => $cc['year'],
					'cc_cvv' => $cc['cvv'],
					'cc_type' => $cc['type'],
					'amount' => $agent['ad_campaign'],
					'area' => $agent['assigned_area']
				);

				$resultObj['callback'] = "agent-subscription";
				$resultObj['callback-data'] = $data;
			}
			$resultObj['no-message'] = true;
		break;
		case "submit-subscription":
			require("{$_SERVER['DOCUMENT_ROOT']}/app/models/paypal/config.php");
			require("{$_SERVER['DOCUMENT_ROOT']}/app/models/paypal/subscription.php");

			if($mess != "")
				$resultObj['error'] = $mess;
		break;
	}
?>