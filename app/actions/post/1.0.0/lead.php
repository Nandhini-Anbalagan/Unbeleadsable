<?php
	switch ($_POST['case']) {
		case "add":
			$db->addLeadStatus($_POST);
			$resultObj['refresh'] = true;
			$resultObj['after-success'] = "Status added successfully";
		case "singleView":
			if(isset($_POST['id'])){
				$agent = $db->getAgentLeadsByID($_POST['id']);
				if($agent){
					$resultObj['callback-data'] = $agent;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-view-agent-lead';
				}else
					$resultObj['error'] = "Invalid Lead.";
			}else
				$resultObj['error'] = "Unknown Lead.";
		break;

		case "singleEdit":
			if(isset($_POST['id'])){
				$agent = $db->getAgentLeadsByID($_POST['id']);
				if($agent){
					$resultObj['callback-data'] = $agent;
					$resultObj['no-message'] = true;
					$resultObj['callback'] = 'get-single-edit-agent-lead';
				}else
					$resultObj['error'] = "Invalid Lead.";
			}else
				$resultObj['error'] = "Unknown Lead.";
		break;

		case "delete":
			$resultObj['callback'] = "swal";
			$resultObj['no-message'] = true;
			if(isset($_POST['id'])){
				$lead = $db->getAgentLeadsByID($_POST['id']);
				if($lead){
					$resultObj['callback-data'] = array("title" => "Deleted!",
					"message" => $lead['lead_name'] . " has been deleted.",
					"type" => "success",
					"next-action" => "reload-leads");
					Tokenizer::delete(array('post-action-lead', 'post-case-lead-delete'));
					$resultObj['delete'] = $db->deleteAgentLeads($_POST['id']);
				}else
					$resultObj['error'] = "Invalid Lead.";
			}else
				$resultObj['error'] = "Unknown Lead.";
				
			if($resultObj['error'] != "-1"){
				$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...", 
					"type" => "error",
					"next-action" => "reload-leads");
			}
		break;

		case "convert":
			$resultObj['callback'] = "swal";
			$resultObj['no-message'] = true;
			if(isset($_POST['id'])){

				$lead = $db->getAgentLeadsByID($_POST['id']);
				if($lead){
					
					$pass = Functions::encode(strtolower($lead['lead_name']));
					$agent = $db->createAgent($_POST['id'], "", 0, $pass);

					if($agent){

						$to = $agent['agent_email'];
						$from = "support@unbeleadsable.com";

						if($lead['user_id'] == 0){
							$subject = "Congrats! You're in.";
							$message = "<h2>Congrats! You're in.</h2>";
							$message .= "Thank you for your registration ". $agent['agent_name'] . " and welcome to your Pro account!<br><br>";
							$message .= "We'll set up your landing page, follow-up funnel & your ad campaigns within 48 hours. <br>In the meantime, take a look around, explore the training videos and join a live training class!";
							$message .= "<h3>Your login is:</h3>";
							$message .= "<b>Login Page:</b> <a href='".WEBSITE_URL."/app'>Click here</a><br>";
							$message .= "<b>User: </b>" . str_replace(" ", "", strtolower($agent['agent_name'])) ."<br>";
							$message .= "<b>Password: </b>" . $pass ."<b><br><br>";
							$message .= "<div style='text-align:center'><em>Reply to this email if you have any questions!</em></div>";
						}else{
							$subject = "Congrats! You're in.";
							$message = "<h2>Congrats! You're in.</h2>";
							$message .= "Thank you for your registration ". $agent['agent_name'] . " and welcome to your Pro account!<br><br>";
							$message .= "We'll set up your landing page, follow-up funnel & your ad campaigns within 48 hours. <br>In the meantime, take a look around, explore the training videos and join a live training class!";
							$message .= "<h3>Your login is the same as your previous account.</h3>";
							$message .= "<div style='text-align:center'><em>Reply to this email if you have any questions!</em></div>";
						}

						Functions::sendEmail($from,$to,$subject,$message);

						$resultObj['callback-data'] = array("title" => "Converted!",
						"message" => $lead['lead_name'] . " has been converted to agent.",
						"type" => "success",
						"next-action" => "reload-leads");
						
						Tokenizer::delete(array('post-action-lead', 'post-case-lead-convert'));

					}
				}else
					$resultObj['error'] = "Invalid Lead.";
			}else
				$resultObj['error'] = "Unknown Lead.";
				
			if($resultObj['error'] != "-1"){
				$resultObj['callback-data'] = array("message" => $resultObj['error'] . " Updating list...", 
					"type" => "error",
					"next-action" => "reload-leads");
			}
		break;

		case "edit":
			if(isset($_POST['deleteLead'])){
				if(!$db->deleteAgentLeads($_POST['id']))
					$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
				else
					$resultObj['success'] = $_POST['name'] . " deleted successfully.";
			}else if(Functions::isValidFields($_POST, 
				array('name', 'email', 'phone', 'areas'),
				array(5, 'email', 9, 'empty'),
				$resultObj['error'])){
				
				$lead = $db->getAgentLeadsByID($_POST['id']);
				if($lead){
					if(!$db->editAgentLeads($_POST))
						$resultObj['error'] = Config::UNEXPECTED_DB_ERROR;
					else
						$resultObj['success'] = $_POST['name'] . " edited successfully.";
				}else
					$resultObj['error'] = "Invalid Lead.";
			}
			
			if($resultObj['error'] == "-1"){
				$resultObj['callback'] = "reload-leads";
				Tokenizer::delete(array('post-action-lead','post-action-lead-edit'));
			}
		break;

		case "comments":
			$db->addLeadComment($_POST);
			$resultObj['success'] = "Comment  added successfully";
		break;

	}
?>