<?php
require_once('head.php');
include "simple_html_dom.php";

header('Content-Type: text/html; charset=utf-8');
header('Content-type: application/ms-excel');
header('Content-Disposition: attachment; filename='.$_GET['type'].'-leads-'.time().'.csv');

$data = '<tr>
	<td>'. $tr['status'] .'</td>
	<td>'. $tr['type'] .'</td>
	<td>'. $tr['name'] .'</td>
	<td>'. $tr['phone'] .'</td>
	<td>'. $tr['email'] .'</td>
	<td>'. $tr['notes'] .'</td>
	<td>'. $tr['address'] .'</td>
	<td>'. $tr['selling_in'] .'</td>
	<td>'. $tr['source'] .'</td>
	<td>'. $tr['date'] .'</td>
	<td>'. $tr['language'] .'</td>
	</tr>';

if ($_GET['type'] == "completed" || $_GET['type'] == "all"){ 
	$leads = $db->getAgentsLead($_SESSION['user']['agent_id'],$_SESSION['user']['agent_slug']);
	$data .= "<tr><td>Completed Leads</td></tr>";

	foreach ($leads as $l) { 
		$address = explode(",", $l['address']);
		$street = $address[0];
		array_shift($address);
		$sta = $db->getStatus($l['status'])['name_en'];
		$lang = $l['lang']=="e"?"English":"Français";

		$data .= "<tr>";
		$data .= "<td>" . $sta . "</td>";
		$data .= "<td>" . $l['type'] . "</td>";
		$data .= "<td>" . $l['name'] . "</td>";
		$data .= "<td>" . $l['phone'] . "</td>";
		$data .= "<td>" . $l['email'] . "</td>";
		$data .= "<td>" . $l['comments'] . "</td>";
		$data .= "<td>" . $l['address'] . "</td>";
		$data .= "<td>" . $l['selling'] . "</td>";
		$data .= "<td>" . Functions::getSource($l['source']) . "</td>";
		$data .= "<td>" . date_format(date_create($l['date']), 'F jS Y') . "</td>";
		$data .= "<td>" . $lang . "</td>";
		$data .= "</tr>";
	}

	$data .= "<tr></tr>";

}

if ($_GET['type'] == "partial" || $_GET['type'] == "all"){ 
	$leads = $db->getAgentsPartialLead($_SESSION['user']['agent_id'],$_SESSION['user']['agent_slug']);
	$data .= "<tr><td>Partial Leads</td></tr>";

	foreach ($leads as $l) { 
		$address = explode(",", $l['address']);
		$street = $address[0];
		array_shift($address);
		$sta = $db->getStatus($l['status'])['name_en'];
		$lang = $l['lang']=="e"?"English":"Français";

		$data .= "<tr>";
		$data .= "<td>" . $sta . "</td>";
		$data .= "<td>" . $l['type'] . "</td>";
		$data .= "<td>" . $l['name'] . "</td>";
		$data .= "<td>" . $l['phone'] . "</td>";
		$data .= "<td>" . $l['email'] . "</td>";
		$data .= "<td>" . $l['comments'] . "</td>";
		$data .= "<td>" . $l['address'] . "</td>";
		$data .= "<td>" . $l['selling'] . "</td>";
		$data .= "<td>" . Functions::getSource($l['source']) . "</td>";
		$data .= "<td>" . date_format(date_create($l['date']), 'F jS Y') . "</td>";
		$data .= "<td>" . $lang . "</td>";
		$data .= "</tr>";
	}
	$data .= "<tr></tr>";
}

if ($_GET['type'] == "addresses" || $_GET['type'] == "all"){ 
	$leads = $db->getAgentsLeadAddresses($_SESSION['user']['agent_id']);
	$data .= "<tr><td>Addresses</td></tr>";

	foreach ($leads as $l) { 
		$address = explode(",", $l['address']);
		$street = $address[0];
		array_shift($address);
		$lang = $l['lang']=="e"?"English":"Français";

		$data .= "<tr>";
		$data .= "<td></td>";
		$data .= "<td></td>";
		$data .= "<td></td>";
		$data .= "<td></td>";
		$data .= "<td></td>";
		$data .= "<td></td>";
		$data .= "<td>" . $l['address'] . "</td>";
		$data .= "<td></td>";
		$data .= "<td>" . Functions::getSource($l['source']) . "</td>";
		$data .= "<td>" . date_format(date_create($l['date']), 'F jS Y') . "</td>";
		$data .= "<td>" . $lang . "</td>";
		$data .= "</tr>";
	}
}


$html = str_get_html($data);
$fp = fopen("php://output", "w");

foreach($html->find('tr') as $element){
	$td = array();
	foreach($element->find('td') as $row){
		$td [] = mb_convert_encoding($row->plaintext, 'WINDOWS-1252', 'UTF-8');
	}
	fputcsv($fp, $td);
}

fclose($fp);
?>