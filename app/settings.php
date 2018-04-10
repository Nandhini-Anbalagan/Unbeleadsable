<?php
	require_once('header.php');
	require_once('load/top-menu.php');
	require_once('load/misc/dynamic-form.php');
?>

<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-4">
				<div class="p-20">
					<h4 class="page-title"><?php echo $tr['settings'] ?></h4>
					<div class="list-group mail-list  m-t-20">
						<a href="settings" class="list-group-item b-0 <?php echo !isset($_GET['section'])?'active':'' ?>"><b><i class="fa fa-download m-r-10"></i><?php echo $tr['account_settings'] ?></b></a>
						<a href="settings/lead_statues" class="list-group-item b-0 <?php echo (isset($_GET['section']) AND $_GET['section'] == "lead_statues")?'active':'' ?>"><b><i class="fa fa-star-o m-r-10"></i><?php echo $tr['lead_statues'] ?></a>
						<?php if(!isset($_SESSION['teammate'])){ ?>
						<a href="settings/subscription_payment" class="list-group-item b-0 <?php echo (isset($_GET['section']) AND $_GET['section'] == "subscription_payment")?'active':'' ?>"><b><i class="fa fa-money m-r-10"></i><?php echo $tr['subscription_payment'] ?></b></a>
						<a href="settings/payment_history" class="list-group-item b-0 <?php echo (isset($_GET['section']) AND $_GET['section'] == "payment_history")?'active':'' ?>"><b><i class="fa fa-trash-o m-r-10"></i><?php echo $tr['payment_history'] ?></b></a>
						<a href="settings/team" class="list-group-item b-0 <?php echo (isset($_GET['section']) AND $_GET['section'] == "team")?'active':'' ?>"><b><i class="fa fa-users m-r-10"></i><?php echo $tr['team'] ?></b></a>
						<?php } ?>
						<a href="settings/upload_leads" class="list-group-item b-0 <?php echo (isset($_GET['section']) AND $_GET['section'] == "upload_leads")?'active':'' ?>"><b><i class="fa fa-upload m-r-10"></i><?php echo $tr['upload_leads'] ?></b></a>
						<a href="settings/trainings" class="list-group-item b-0 <?php echo (isset($_GET['section']) AND $_GET['section'] == "trainings")?'active':'' ?>"><i class="fa fa-book m-r-10"></i><?php echo $tr['trainings'] ?></b></a>
					</div>
				</div>
			</div>

			<div class="col-md-8">
				<div class="panel panel-default m-t-40">
					<div class="panel-body" id="settingBody">
						<?php
							if (isset($_GET['section'])){
								if($_GET['section'] == "lead_statues")
									require_once("load/lead_statues.php");
								else if($_GET['section'] == "subscription_payment")
									require_once("load/subscription_payment.php");
								else if($_GET['section'] == "ad_payment")
									require_once("load/ad_payment.php");
								else if($_GET['section'] == "payment_history")
									require_once("load/payment_history.php");
								else if($_GET['section'] == "upload_leads")
									require_once("load/upload_leads.php");
								else if($_GET['section'] == "team")
									require_once("load/team.php");
								else if($_GET['section'] == "trainings")
									require_once("load/trainings.php");
							}else
								require_once("load/account_settings.php");
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>