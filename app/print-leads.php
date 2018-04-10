<?php
require_once('head.php');
require_once('header.php');

function allCompleted(){
	$leads = $GLOBALS['db']->getAgentsLead($_SESSION['user']['agent_id'],$_SESSION['user']['agent_slug']);
	$table = '<h1 class="text-uppercase text-center">'. $GLOBALS['tr']['completed_leads'] .'</h1>
	<table class="table table-striped table-bordered" style="padding-bottom: 50px;">
		<thead>
			<tr>
				<th class="text-uppercase">'. $GLOBALS['tr']['status'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['type'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['name'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['phone'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['email'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['notes'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['address'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['selling_in'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['source'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['date'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['language'] .'</th>
			</tr>
		</thead>
		<tbody>';
			
		foreach ($leads as $l) { 
			$sta = $GLOBALS['db']->getStatus($l['status'])['name_en'];
			$lang = $l['lang']=="e"?"English":"Français";

			$table .= '<tr>
					<td>'. $sta .'</td>
					<td>'. $l['type'] .'</td>
					<td>'. $l['name'] .'</td>
					<td>'. $l['phone'] .'</td>
					<td>'. $l['email'] .'</td>
					<td>'. $l['comments'] .'</td>
					<td>'. $l['address'] .'</td>
					<td>'. $l['selling'] .'</td>
					<td>'. Functions::getSource($l['source']) .'</td>
					<td>'. date_format(date_create($l['date']), 'F jS Y') .'</td>
					<td>'. $lang .'</td>
				</tr>';
		}
		$table .= '</tbody></table>';

	return $table;
}

function allPartial(){
	$leads = $GLOBALS['db']->getAgentsPartialLead($_SESSION['user']['agent_id']);
	$table = '<h1 class="text-uppercase text-center">'. $GLOBALS['tr']['partial_leads'] .'</h1>
	<table class="table table-striped table-bordered" style="padding-bottom: 50px;">
		<thead>
			<tr>
				<th class="text-uppercase">'. $GLOBALS['tr']['name'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['phone'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['email'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['status'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['address'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['selling_in'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['source'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['date'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['language'] .'</th>
			</tr>
		</thead>
		<tbody>';
			
		foreach ($leads as $l) { 
			$lang = $l['lang']=="e"?"English":"Français";

			$table .= '<tr>
					<td>'. $l['name'] .'</td>
					<td>'. $l['phone'] .'</td>
					<td>'. $l['email'] .'</td>
					<td>'. $l['comments'] .'</td>
					<td>'. $l['address'] .'</td>
					<td>'. $l['selling'] .'</td>
					<td>'. Functions::getSource($l['source']) .'</td>
					<td>'. date_format(date_create($l['date']), 'F jS Y') .'</td>
					<td>'. $lang .'</td>
				</tr>';
		}
		$table .= '</tbody></table>';

	return $table;
}

function allAddresses(){
	$leads = $GLOBALS['db']->getAgentsLeadAddresses($_SESSION['user']['agent_id']);
	$table = '<h1 class="text-uppercase text-center">'. $GLOBALS['tr']['address_capture'] .'</h1>
	<table class="table table-striped table-bordered" style="padding-bottom: 50px;">
		<thead>
			<tr>
				<th class="text-uppercase">'. $GLOBALS['tr']['address'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['source'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['date'] .'</th>
				<th class="text-uppercase">'. $GLOBALS['tr']['language'] .'</th>
			</tr>
		</thead>
		<tbody>';
			
		foreach ($leads as $l) { 
			$lang = $l['lang']=="e"?"English":"Français";
			$table .= '<tr>
					<td>'. $l['address'] .'</td>
					<td>'. Functions::getSource($l['source']) .'</td>
					<td>'. date_format(date_create($l['date']), 'F jS Y') .'</td>
					<td>'. $lang .'</td>
				</tr>';
		}
		$table .= '</tbody></table>';

	return $table;
}

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
	<base href="<?php echo WEBSITE_URL ?>/">
	<meta charset="utf-8">
	<title>Unbeleadsable</title>

	<!-- Print CSS -->
	<link rel="stylesheet" type="text/css" href="assets/css/print.css">
</head>
<body style="padding: 20px;">
<p class="text-center"><img src="http://unbeleadsable.com/assets/img/logo.png" alt="logo"></p>

<?php 
	if ($_GET['type'] == "completed") { 
		if($_GET['amt'] == "single")
			echo allCompleted();
		else{
			echo allCompleted();
			echo allPartial();
			echo allAddresses();
		}
	}else if ($_GET['type'] == "partial") { 
		if($_GET['amt'] == "single")
			echo allPartial();
		else{
			echo allCompleted();
			echo allPartial();
			echo allAddresses();
		}
	}else if ($_GET['type'] == "address") { 
		if($_GET['amt'] == "single")
			echo allAddresses();
		else{
			echo allCompleted();
			echo allPartial();
			echo allAddresses();
		}
	}  
?>

<div style="clear:both!important;"/></div><div style="page-break-after:always"></div><div style="clear:both!important;"/></div>

</body>

<script>
(function(){
	window.print();
})();
</script>
</html>