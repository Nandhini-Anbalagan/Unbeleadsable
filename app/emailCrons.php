<?php

# Displaying the errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');
date_default_timezone_set('America/Montreal');
setlocale(LC_MONETARY, 'ca_CA');

# Function to auload an object class
require_once 'models/phpMailer/vendor/autoload.php';

// Or, using an anonymous function as of PHP 5.3.0
spl_autoload_register(function ($class) {
	require_once("models/$class.class.php");
});

$db = new DBManager();
$leads = $db->getLeadsForCron("home_sellers");
$test = 0;

foreach ($leads as $l) {
	$title = $l['agent_lang'] == "EN"?"Automatic Funnel Sent":"Courriel envoyé automatiquement";
	if(!$db->getBlacklistEmail($l['email'])){
		$funnel = $db->getNextFunnel($l['dTime'], $l['funnels'], $l['agent_id']);
		if($funnel){
			$funnelSent = $db->getFunnelSent($l['id']);
			$content = str_replace("&lt;p&gt;&nbsp;&lt;/p&gt;", "", $funnel['content']);
			$message = str_replace(array('[FIRSTNAME]', '[SHORTADDRESS]'), array($l['name'], $l['address']),$content);
			$message .= "<br><br>" . nl2br($l['agent_signature']);
			$message = html_entity_decode($message);

			if($test)
				echo $message . "<br><hr>";
			else{
				if($funnelSent && !$test){
					$array = unserialize($funnelSent['funnels']);
					if(!in_array($funnel['funnel_id'], $array)){
						array_push($array, $funnel['funnel_id']);
						Functions::sendEmail($l['agent_email'],$l['email'],$funnel['name'],$message, $l['agent_name'], false);
						$db->addMessageHistory($title, $funnel['name'], $message, $l['id']);
						$db->addUpdateFunnelSent($l['id'], serialize($array));
					}
				}else{
					$db->addUpdateFunnelSent($l['id'], serialize(array($funnel['funnel_id'])));
					Functions::sendEmail($l['agent_email'],$l['email'],$funnel['name'],$message, $l['agent_name'], false);
					$db->addMessageHistory($title, $funnel['name'], $message, $l['id']);
				}
			}
		}
	}
}

$leads = $db->getLeadsForCron("home_buyers");

foreach ($leads as $l) {
	if(!$db->getBlacklistEmail($l['email'])){
		$funnel = $db->getNextFunnel($l['dTime'], $l['funnels'], $l['agent_id']);

		if($funnel){
			$funnelSent = $db->getFunnelSent($l['id']);
			$content = str_replace("&lt;p&gt;&nbsp;&lt;/p&gt;", "", $funnel['content']);
			$message = str_replace(array('[FIRSTNAME]', '[SHORTADDRESS]'), array($l['name'], $l['address']),$content);
			$message .= "<br><br>" . nl2br($l['agent_signature']);
			$message = html_entity_decode($message);

			if($test)
				echo $message . "<br><hr>";
			else{
				if($funnelSent && !$test){
					$array = unserialize($funnelSent['funnels']);
					if(!in_array($funnel['funnel_id'], $array)){
						array_push($array, $funnel['funnel_id']);
						Functions::sendEmail($l['agent_email'],$l['email'],$funnel['name'],$message, $l['agent_name'], false);
						$db->addMessageHistory("Automatic Funnel Sent", $funnel['name'], $message, $l['id']);
						$db->addUpdateFunnelSent($l['id'], serialize($array));
					}
				}else{
					$db->addUpdateFunnelSent($l['id'], serialize(array($funnel['funnel_id'])));
					Functions::sendEmail($l['agent_email'],$l['email'],$funnel['name'],$message, $l['agent_name'], false);
					$db->addMessageHistory("Automatic Funnel Sent", $funnel['name'], $message, $l['id']);
				}
			}
		}
	}
}

?>