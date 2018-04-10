<?php
	//OMG Online syntax checker : http://phpcodechecker.com/
	# Starting the session

	require_once 'models/phpMailer/vendor/autoload.php';

	// Or, using an anonymous function as of PHP 5.3.0
	spl_autoload_register(function ($class) {
		require_once("models/$class.class.php");
	});

	ob_start();
	if(session_status() == PHP_SESSION_NONE)
		session_start();

	# Displaying the errors
	if(isset($_SESSION['user']) AND $_SESSION['user']['level'] == 100){
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
	}

	date_default_timezone_set('America/Montreal');
	setlocale(LC_MONETARY, 'ca_CA');

	define('WEBSITE_URL', Config::WEBSITE_URL);

	# Creating the db and page variables
	$page = rtrim(basename($_SERVER['PHP_SELF']), ".php");
	$db = new DBManager();
	$datatablePages = array("emails",
							"templates",
							"completed-leads",
							"partial-leads",
							"address-capture",
							"invoices",
							"other_invoices",
							"leads",
							"agents",
							"areas",
							"agent_buyer_budget",
							"agent_seller_budget",
							"buyer_landings",
							"seller_landings",
							"settings",
							"call-center",
							"reccurent_overview",
							"tasks",
							"archived-leads");

	# Check for autologin
	if(!isset($_SESSION['user'])){
		if(isset($_COOKIE['remember_me'])){
			$key = $db->getUserKey($_COOKIE['remember_me'], 'AUTO_LOGIN');
			if($key){
				$user = $db->reloadProfile($key['user_fk']);

				if($user){
					$_SESSION['user'] = $user;
					setcookie("remember_me", $_COOKIE['remember_me'], time() + 7 * 24 * 60 * 60, '/');
					$db->updateUserKeyUsage($key['user_key_id']);
				}
			}else
				setcookie("remember_me", "", time() - 54, "/");
		}
	}

	# Check if the user has the right to view the page
	if($page != "core"){
		if(!isset($_SESSION['user'])){
			if(!Functions::isPublicPage($page)){
				$_SESSION['destination'] = $page;
				Functions::generateErrorMessage("Please log in to continue.");
				Functions::redirect("login");
			}
		}else{
			/*if($_SESSION['user']['changed_password'] == 0 AND $page != "create-password")
				Functions::redirect("create-password");*/

			//TODO: back to admin area when mockUser is dead

			if(isset($_GET['mockUser'])){
				$_SESSION['admin'] = $_SESSION['user']['user_id'];
				$_SESSION['user'] = $db->reloadAgentProfile(IDObfuscator::decode($_GET['mockUser']));
				unset($_SESSION['teammate']);
			}else if(isset($_GET['backAdmin'])){
				unset($_SESSION['user']);
				$_SESSION['user'] = $db->reloadProfile($_SESSION['admin']);
				unset($_SESSION['admin']);
				unset($_SESSION['teammate']);
				header('location: /app/agents');
			}else if(isset($_GET['switchAccount'])){
				$_SESSION['user'] = $db->reloadAgentProfile(IDObfuscator::decode($_GET['switchAccount']));
			}

			if($_SESSION['user']['level'] == 10 && Functions::isAdminPage($page)){
				unset($_SESSION['user']);
				Functions::generateErrorMessage("Please log in to continue.");
				Functions::redirect("login");
			}

			if($_SESSION['user']['level'] != 10)
				require_once("english_translation.php");

			if($_SESSION['user']['level'] == 10){
				$agent = $db->getAgentByID($_SESSION['user']['agent_id']);
				$stats = $db->agentLeadStats($_SESSION['user']['agent_id'], $_SESSION['user']['agent_slug']);

				if($_SESSION['user']['agent_lang'] == "FR")
					require_once("french_translation.php");
				else
					require_once("english_translation.php");
			}
		}
	}
?>