<?php 

require('head.php');

if($_GET['num'] == "all")
	$invoices = $db->getAllInvoices();
elseif (isset($_SESSION['invoice_num']))
	$tran = $db->getInvoice($_SESSION['invoice_num']);
else
	$tran = $db->getInvoice($_GET['num']);
?>
	<!DOCTYPE html>
	<html>
	<head>
		<base href="https://unbeleadsable.com/app/">
		<meta charset="utf-8">
		<title>Unbeleadsable Receipt</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="assets/css/receipt.css" media="all" rel="stylesheet" type="text/css" />
	</head>
	<body class="financial_document">
		<div class="invoice">
			<div class="invoice__header">
				<div class="invoice__frenstreet-logo">
					<img class="frenstreet-logo--screen" src="assets/img/logo.png" />
					<img class="frenstreet-logo--print" src="assets/img/logo.png" />
				</div>

				<div class="invoice__document-title">Receipt</div>
			</div>

			<div class="invoice__details">
				<div class="invoice__column -span-50">
					<div class="invoice__date">
						<span class="invoice__details-label">Date:</span>
						<span class="invoice__details-content"><?php echo Functions::userFriendlyDate($tran[0]['invoice_date']) ?></span>
					</div>
					<div class="invoice__number">
						<span class="invoice__details-label">Receipt No:</span>
						<span class="invoice__details-content"><?php echo $tran[0]['invoice_num'] ?></span>
					</div>
				</div>
				<div class="invoice__column -span-50 -last" style="text-align: right">
					<div class="invoice__number">
						<span class="invoice__details-label">GST No:</span>
						<span class="invoice__details-content">83425 4377 RT0001</span>
					</div>
					<div class="invoice__number">
						<span class="invoice__details-label">PST No:</span>
						<span class="invoice__details-content">1220927110 TQ 0001</span>
					</div> 
				</div>
			</div>

			<div class="invoice__details">
				<div class="invoice__column -span-50">
					<div class="invoice__buyer">
						<strong>To:</strong><br/>
						<?php echo $tran[0]['agent_name']?>
						<br>
						<?php echo $tran[0]['agent_address'] ?>
						<br>
						<?php echo $tran[0]['agent_email'] ?>
						<br>
						<?php echo $tran[0]['agent_phone'] ?>
						<br>
						<?php echo $tran[0]['agent_agency'] ?>
					</div>
				</div>
			</div>

			<div class="invoice__lines">
				 <strong>Details</strong><br/>
				<div class="invoice__item-container">
					<table>
						<thead>
							<tr>
								<th>Description</th>
								<th class="invoice__th--price">Price</th>
							</tr>
						</thead>

						<tbody>
						<?php if($tran[0]['install'] != 0){ ?>
							<tr>
								<td class="invoice__td--description">Installation Fees</td>
								<td class="invoice__td--price">$ <?php echo number_format($tran[0]['install'],2) ?> USD</td>
							</tr>
							<?php } ?>
							<?php if($tran[0]['monthly'] != 0){ ?>
							<tr>
								<td class="invoice__td--description">Montly Subscription Fees</td>
								<td class="invoice__td--price">$ <?php echo number_format($tran[0]['monthly'],2) ?> USD</td>
							</tr>
							<?php } ?>
							<?php if($tran[0]['ads'] != 0){ ?>
							<tr>
								<td class="invoice__td--description">Social Media Ads Budget</td>
								<td class="invoice__td--price">$ <?php echo number_format($tran[0]['ads'],2) ?> USD</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>

			<div class="invoice__footer">
				<div class="invoice__column -span-40" style="float: right; text-align: right">
					<table>
						<tr>
							<th class="invoice__total-amount">Total:</th>
							<td class="invoice__total-amount">$ <?php echo number_format($tran[0]['install']+$tran[0]['monthly']+$tran[0]['ads'],2) ?> USD</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

	</body>
	</html>
