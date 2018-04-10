<?php

include("head.php");
//Add Invoice and Invoice details
$invoiceData = array();
$invoiceData[]	= '';
$invoiceData[]	= "7Y115214YB875892V";
$invoiceData[]	= date('Y-m-d H:i:s');
//$invoiceData[]	= "George Boursiquot";
$invoiceData[]	= "Matteo Fiorilli";
$invoiceData[]	= "1130 Av Drapeau, Montreal, Qc J2J 4J4 Canada";
//$invoiceData[]	= "bgeorgealdly@gmail.com";
$invoiceData[]	= "support@unbeleadsable.com";
$invoiceData[]	= "Testing any payments with new credit card";
$invoiceData[]	= "1.00";
$invoiceData[]	= "EN";

echo "lol";
echo "wowo"

//$db->addAnyInvoice($invoiceData);
//die();
/*foreach ($db->getAgents() as $agent) {
	echo "Agent: " . $agent['agent_id'] . "<br>&nbsp;&nbsp;&nbsp;&nbsp;";
	$frenchID = $db->getFunnelCatByTitle('Home Evaluation FR', $agent['agent_id'])['id'];
	$englishID = $db->getFunnelCatByTitle('Home Evaluation EN', $agent['agent_id'])['id'];

	foreach ($db->getAgentsLead($agent['agent_id']) as $lead) {
		if($lead['lang'] == 'e')
			$funnelID = $englishID;
		else
			$funnelID = $frenchID;

		$db->updateLeadFunnel($lead['id'], $funnelID);
		echo $lead['id'] . " " . $funnelID . "<br>";
	}
}*/

//die();
/*foreach ($db->getAgents() as $value) {
	echo "Agent: " . $value['agent_id'] . "<br>&nbsp;&nbsp;&nbsp;&nbsp;";
	$db->funnelPerAgent($value['agent_id']);
	break;

}*/

?>