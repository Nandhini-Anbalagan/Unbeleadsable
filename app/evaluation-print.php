<?php 
	require_once('head.php');
	require_once('header.php');

	if($_GET['id'] == 'all')
		$evaluations = $db->getEvaluations($_SESSION['user']['agent_id']);
	else
		$evaluations[] = $db->getEvaluation(IDObfuscator::decode($_GET['id']));

	if(empty($evaluations))
		Functions::redirect("evaluation");
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

<?php foreach ($evaluations as $value) {
	$noApt = explode(" ", $value['address']);

	if(strpos($noApt[0], "#") === 0)
		array_shift($noApt);

	$add = implode(" ", $noApt);
	$google = str_replace(" ", "+", $add);

 ?>
	<p><img src="http://unbeleadsable.com/assets/img/logo.png" alt="logo"></p>
	<p><strong>Name: </strong> <?php echo $value['name'] ?></p>
	<p><strong>Phone: </strong> <?php echo $value['phone'] ?></p>
	<p><strong>Email: </strong> <?php echo $value['email'] ?></p>
	<p><strong>Adresse:</strong> <?php echo $value['address'] ?></p>
	<p><img class="img-responsive img-thumbnail" src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo $google ?>&amp;zoom=16&amp;size=600x300&amp;maptype=roadmap&amp;markers=color:red|<?php echo $google ?>&amp;key=AIzaSyDSOaCXDTQy_VXFflgZg19OwFqLIUmZ1eM" alt=""></p>
	<p>&nbsp;</p>
	<table style="height: 62px;" width="408">
		<tbody>
			<tr>
				<th>Valeur basse</th>
				<td>:</td>
				<td>$<?php echo number_format($value['low'],2) ?></td>
			</tr>
			<tr>
				<th>Valeur élevée</th>
				<td>:</td>
				<td>$<?php echo number_format($value['high'],2) ?></td>
			</tr>
			<tr>
				<th>Valeur de la municipalité</th>
				<td>:</td>
				<td>$<?php echo number_format($value['municipality'],2) ?></td>
			</tr>
		</tbody>
	</table>
	<p>&nbsp;</p>
	<p><?php echo nl2br($value['com']) ?></p>
	<p><?php echo nl2br($_SESSION['user']['agent_signature']) ?></p>
	<p class="text-center"><em>Date: <?php echo date_format(date_create($value['date']), 'Y-m-d') ?></em></p>
<div style="clear:both!important;"/></div><div style="page-break-after:always"></div>
<?php } ?>
</body>

<script>
(function(){
	window.print();
})();
</script>
</html>