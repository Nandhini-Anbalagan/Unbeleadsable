<?php
	/******************************************************
	*   Author          Unbeleadsable <support@unbeleasable.com>
	*   Version         2.1.17
	*   Last modified   February 2nd, 2016
	*   Web             http://
	*******************************************************
	*	@after-error:		Error message after the next page load. Default null
	*	@after-duration:	Duration of the message after the next page load. Default: 2500
	*	@after-success:		Success message after the next page load. Default null
	*	@callback:			Name of the callback action for the javascript. Default: null
	*	@callback-data:		Array/Value required for the callback. Default: null
	*	@delay:				Delay before the redirection of the URL in miliseconds. Default: 1500
	*	@destination:		URL to be redirected to. Default: null
	*	@duration:			Duration of the success amd error message. Default: 2000
	*	@error:				Error message. Default: -1
	*	@killer:			True to clear other notifications, otherwise false. Default: true
	*	@layout:			Layout for the notification. Default: top-full-width (top-full-width | top-left | top-center | top-right | bottom-left | bottom-center | bottom-right | bottom-full-width)
	*	@no-message:		True to not display any message, otherwise false. Default: false
	*	@redirect:			True to redirect to @destination. Default: false (True if destination is not null)
	*	@refresh:			True to refresh the page. Default: false
	*	@remove-disable:	True to remove the disable on form input after the validation. Default true.
	*	@reset:				True to reset the ajax form, otherwise false. Default: false
	*	@session-expired:	True to redirect user to home page and use session expired error message. Default: null
	*	@success:			Success message. Default: -1
	*	@toast-callback:	Name of the callback action after the user click on the notification. Default: null
	*	@toast-button:		True to show the close button on the notification. Default: false
	******************************************************/
	
	# Require the header for access to the database, functions and session.
	require_once('head.php');
	
	# The main object we're returning at the end
	$resultObj = array("error" => -1, "success" => -1);
	
	# Function to set the error to expired tokens
	function expiredTokens(&$resultObj){
		$resultObj['error'] = "Your token has expired. The page will now refresh...";
		$resultObj['refresh'] = true;
	}
	
	# Check if it's a post request
	if($_SERVER['REQUEST_METHOD'] == "POST"){
		# Check if there's a post action
		if(isset($_POST['action'])){
			# Set the post action version if not set by default
			if(Config::INIT_POST_VERSION_CONTROL && !isset($_POST['version'])){
				if(Config::INIT_TOKENIZER)
					$_POST['version'] = Tokenizer::add('default-core-post-version', 10, Config::DEFAULT_CORE_POST_VERSION);
				else
					$_POST['version'] = Config::DEFAULT_CORE_POST_VERSION;
			}
			
			# Set the action case
			if(Config::INIT_ACTION_CASE && Config::INIT_TOKENIZER){
				if(isset($_POST['case'])){
					$_POST['case'] = Tokenizer::get($_POST['case'], Tokenizer::GET_TOKEN_VALUE);
					
					# Make sure the case token has not been modified or expired
					if($_POST['case'] == NULL)
						expiredTokens($resultObj);
				}
			}
			
			# Get the Tokenizer value for the action if user is using Tokenizer
			if(Config::INIT_TOKENIZER){
				$_POST['action'] = Tokenizer::get($_POST['action'], Tokenizer::GET_TOKEN_VALUE);
				
				# Get the Tokenizer value for the version
				if(Config::INIT_POST_VERSION_CONTROL && isset($_POST['version'])){
					$_POST['version'] = Tokenizer::get($_POST['version'], Tokenizer::GET_TOKEN_VALUE);
					
					# Make sure the version token has not been modified or expired
					if($_POST['version'] == NULL)
						expiredTokens($resultObj);
				}
				
				# Make sure the action token has not been modified or expired
				if($_POST['action'] == NULL){
					expiredTokens($resultObj);
				}
			}
			
			# Generate the action path and check if the post action exist if there was no error
			if($resultObj['error'] == "-1"){
				$actionPath = Config::POST_ACTION_PATH . "/" . (Config::INIT_POST_VERSION_CONTROL ? "{$_POST['version']}/" : "") . "{$_POST['action']}.php";
				if(file_exists($actionPath))
					require_once($actionPath);
				else if(Config::DEBUG_CORE)
					$resultObj['error'] = "Unknown post action: {$_POST['action']}" . (Config::INIT_POST_VERSION_CONTROL ? " (Version: {$_POST['version']})" : "");
				else
					exit("Permission denied.");
			}
		}else if(Config::DEBUG_CORE)
			$resultObj['error'] = "Unspecified post action."; 
		else
			echo "Permission denied."; 
		
		#------------------------- Properties Management -------------------------#
		# Stop if forgot to specify a callback action
		if(isset($resultObj['callback-data']) && !isset($resultObj['callback']))
			exit("Callback data given but no callback action specified.");
		
		# Set the default success properties
		if($resultObj['success'] != -1){
			if(!isset($resultObj['duration']))
				$resultObj['duration'] = 2000;
		}
		
		# Set the default killer property to true
		if(!isset($resultObj['killer']))
			$resultObj['killer'] = true;
		
		# Set the default layout to bottom-right
		if(!isset($resultObj['layout']))
			$resultObj['layout'] = "bottom-right";
		
		if(isset($resultObj['refresh'])){
			if(!isset($resultObj['redirect']))
				$resultObj['redirect'] = true;
		}else if(isset($resultObj['destination'])){
			# Set the default redirect and delay if a destination is specified (Redirect: true | Delay: 1500).
			if(!preg_match("/^(https?:\/\/|www.)/", $resultObj['destination']))
				$resultObj['destination'] = Config::WEBSITE_URL . "/" . $resultObj['destination'];
			
			if(!isset($resultObj['redirect']))
				$resultObj['redirect'] = true;
		}else
			$resultObj['redirect'] = false;
		
		# Set the default @remove-disable
		if(!isset($resultObj['remove-disable']))
			$resultObj['remove-disable'] = true;
		
		# Set the default delay
		if($resultObj['redirect'] === true){
			if(!isset($resultObj['delay']))
				$resultObj['delay'] = 1500;
		}
		
		# Set the cookie message for @after-success
		if(isset($resultObj['after-success'])){
			if(!isset($resultObj['no-message']))
				$resultObj['no-message'] = true;
			setcookie('success-message', $resultObj['after-success'], time() + 60 * 60, "/");
		}else if(isset($resultObj['after-error'])){
			if(!isset($resultObj['no-message']))
				$resultObj['no-message'] = true;
			setcookie('error-message', $resultObj['after-error'], time() + 60 * 60, "/");
		}
		
		# Set the default @reset to false
		if(!isset($resultObj['reset']))
			$resultObj['reset'] = false;
		
		# Check if the @session-expired is set to true
		if(isset($resultObj['session-expired']) && $resultObj['session-expired'] == "true"){
			if(!isset($_SESSION['user'])){
				# Set the default @session-expired error
				if($resultObj['error'] == "-1")
					$resultObj['error'] = "Your session has expired. Please sign in again.";
				
				# Set the default destination for @session-expired
				if(!isset($resultObj['destination']))
					$resultObj['destination'] = "";
				
				# Set the default delay for @session-expired
				if(!isset($resultObj['delay']))
					$resultObj['delay'] = 1000;
			}
		}
			
		# A dead path result
		if($resultObj['error'] == "-1" && $resultObj['success'] == "-1" && !isset($resultObj['after-success']) && !isset($resultObj['after-error']) && (!isset($resultObj['no-message']) || $resultObj['no-message'] == false)){
			$resultObj['error'] = "Oops...Did you change something?";
		}
		
		# Return the object for the Ajax call
		echo json_encode($resultObj);
	}else{
		# Check if there's a get action
		if(isset($_GET['action'])){			
			# Set the get action version if not set by default
			if(Config::INIT_GET_VERSION_CONTROL && !isset($_GET['version'])){
				$_GET['version'] = Config::DEFAULT_CORE_GET_VERSION;
			}
			
			# Generate the action path and check if the get action exist
			$actionPath = Config::GET_ACTION_PATH . "/" . (Config::INIT_GET_VERSION_CONTROL ? "{$_GET['version']}/" : "") . "{$_GET['action']}.php";
			
			if(file_exists($actionPath))
				require_once($actionPath);
			else if(Config::DEBUG_CORE)
				exit("Unknown get action: {$_GET['action']}" . (Config::INIT_GET_VERSION_CONTROL ? " (Version: {$_GET['version']})" : ""));
			else
				exit("Permission denied."); 
		}else{ exit("Permission denied."); }
	}
?>