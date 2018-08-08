<?php require_once('header.php'); ?>
<?php require_once('load/top-menu.php'); ?>
<?php require_once('load/misc/dynamic-form.php'); ?>

<?php 
$agents = $db->getAgents();



# Tokenizer container
$postActionLanding = Tokenizer::add('post-action-landing', 20, 'landing');

if($_SESSION['user']['agent_slug'] == "home_sellers")
	$postCaseLandingView = Tokenizer::add('post-case-landing-view-seller', 30, 'view-seller');
else if($_SESSION['user']['agent_slug'] == "home_buyers")
	$postCaseLandingView = Tokenizer::add('post-case-landing-view-buyer', 30, 'view-buyer');

$postCaseLandingPic = Tokenizer::add('post-case-landing-picture', 30, 'picture');

?>
<div class="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 template" style="display: none">
				<div class="card-box">
					<div id="landings-wrapper">
						<?php
							if($_SESSION['user']['agent_slug'] == "home_sellers")
								require_once('load/seller_landings.php');
							else if($_SESSION['user']['agent_slug'] == "home_buyers")
								require_once('load/buyer_landings.php');
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		var id = "<?php echo $_SESSION['user']['agent_id']; ?>";
		$('.template').show();
		$('#<?php echo $dynamicFormId; ?>').append('<input type="hidden" name="action" value="<?php echo $postActionLanding; ?>">'
			+ '<input type="hidden" name="case" value="<?php echo $postCaseLandingView; ?>">'
			+ '<input type="hidden" name="id" value="' + id + '">');
		$('#<?php echo $dynamicFormId; ?>').submit();
		$('#<?php echo $dynamicFormId; ?>').empty();
	});
</script>

 <script src="assets/plugins/bootstrap-filestyle/src/bootstrap-filestyle.min.js" type="text/javascript"></script>
<?php require_once('footer.php'); ?>
<?php require_once('foot.php'); ?>