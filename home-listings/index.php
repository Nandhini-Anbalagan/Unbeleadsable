<?php
session_start();

if (!empty($_GET))
	$_SESSION['got'] = $_GET;

if(!empty($_SESSION['got'])){
	include("../app/head.php");
	$agent = $db->getBuyerLandingPage(IDObfuscator::decode($_SESSION['got']['a']));
}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

	<?php if($_SESSION['got']['l'] == 'e'){ ?>
	<title>Free Listings of Homes in Laval</title>
	<meta name="DESCRIPTION" content="Find out the value of your home with this free home value calculator"/>

	<meta property="og:title" content="What's your home really worth?" />
	<meta property="og:site_name" content="Unbeleadsable.com" />
	<meta property="og:description" content="Find out the value of your home with this free home value calculator" />
	<meta property="og:image" content="../app/uploads/landings/default.jpg" />
	<?php }else if($_SESSION['got']['l'] == 'f'){ ?>
	<title>Quelle est la vrai valeur de votre maison?</title>
	<meta name="DESCRIPTION" content="Découvrez la valeur de votre maison avec ce calculateur de valeur de maison gratuit"/>

	<meta property="og:title" content="Quelle est la vrai valeur de votre maison?" />
	<meta property="og:site_name" content="Unbeleadsable.com" />
	<meta property="og:description" content="Découvrez la valeur de votre maison avec ce calculateur de valeur de maison gratuit" />
	<meta property="og:image" content="../app/uploads/landings/default.jpg" />
	<?php } ?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="style.css?v=<?php echo time() ?>">

</head>
<body style="background-image: url('../app/uploads/landings/<?php echo $agent['bg_img']?>')">

	<div class="page-wrap">
		<div class="overlay"></div>
		<div class="container-fluid">
			<h3 class="text-center tinyTitle">Home List</h3>
			<div class="row">
				<div class="form-box">
					<form role="form" class="registration-form" action="javascript:void(0);">
						<fieldset>
							<div class="form-top">
								<div class="form-top-left">
									<h1><?php echo $_SESSION['got']['l'] == 'e'?str_replace('[city]', $agent['city'], $agent['title_en']):str_replace('[city]', $agent['city'], $agent['title_fr'])?></h1>
									<h3><?php echo $_SESSION['got']['l'] == 'e'?$agent['sub_title_en']:$agent['sub_title_fr']?></h3>
								</div>
							</div>
							<div class="form-bottom">
								<div class="input-group">
									<input type="email" name="email" placeholder="<?php echo $_SESSION['got']['l'] == 'e'?$agent['email_field_en']:$agent['email_field_fr']?>" class="form-email form-control" required>
									<span class = "input-group-btn">
										<button type="button" class="btn btn-next" id="email"><?php echo $_SESSION['got']['l'] == 'e'?$agent['next_button_en']:$agent['next_button_fr']?></button>
									</span>
								</div>
							</div>
						</fieldset>
						<fieldset>
							<div class="form-top">
								<div class="form-top-left">
									<h1><?php echo $_SESSION['got']['l'] == 'e'?str_replace('[city]', $agent['city'], $agent['title_en']):str_replace('[city]', $agent['city'], $agent['title_fr'])?></h1>
									<h3><?php echo $_SESSION['got']['l'] == 'e'?$agent['sub_title_en']:$agent['sub_title_fr']?></h3>
								</div>
							</div>
							<div class="form-bottom">
								<label><span><i class="fa fa-bed" aria-hidden="true"></i></span> &nbsp;<?php echo $_SESSION['got']['l'] == 'e'?$agent['bedroom_label_en']:$agent['bedroom_label_fr']?></label><br>
								<button type="button" class="btn btn-sm btn-next updateBtn" data-name="bedrooms">1</button>
								<button type="button" class="btn btn-sm btn-next updateBtn" data-name="bedrooms">2</button>
								<button type="button" class="btn btn-sm btn-next updateBtn" data-name="bedrooms">3</button>
								<button type="button" class="btn btn-sm btn-next updateBtn" data-name="bedrooms">4</button>
								<button type="button" class="btn btn-sm btn-next updateBtn" data-name="bedrooms">5</button>
								<button type="button" class="btn btn-sm btn-next updateBtn" data-name="bedrooms">6+</button>
							</div>
						</fieldset>
						<fieldset>
							<div class="form-top">
								<div class="form-top-left">
									<h1><?php echo $_SESSION['got']['l'] == 'e'?str_replace('[city]', $agent['city'], $agent['title_en']):str_replace('[city]', $agent['city'], $agent['title_fr'])?></h1>
									<h3><?php echo $_SESSION['got']['l'] == 'e'?$agent['sub_title_en']:$agent['sub_title_fr']?></h3>
								</div>
							</div>
							<div class="form-bottom">
								<label><span><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>&nbsp; <?php echo $_SESSION['got']['l'] == 'e'?$agent['buying_frame_en']:$agent['buying_frame_fr']?></label><br>
								<button type="button" class="btn btn-next updateBtn" data-name="buying">1-3 months</button>
								<button type="button" class="btn btn-next updateBtn" data-name="buying">3-6 months</button>
								<button type="button" class="btn btn-next updateBtn" data-name="buying">6-12 months</button>
								<button type="button" class="btn btn-next updateBtn" data-name="buying">Not Sure</button>
							</div>
						</fieldset>
						<fieldset>
							<div class="form-top">
								<div class="form-top-left">
									<h1><?php echo $_SESSION['got']['l'] == 'e'?str_replace('[city]', $agent['city'], $agent['title_en']):str_replace('[city]', $agent['city'], $agent['title_fr'])?></h1>
									<h3><?php echo $_SESSION['got']['l'] == 'e'?$agent['sub_title_en']:$agent['sub_title_fr']?></h3>
								</div>
							</div>
							<div class="form-bottom">
								<label><span><i class="fa fa-user" aria-hidden="true"></i></span>&nbsp; </span>&nbsp; <?php echo $_SESSION['got']['l'] == 'e'?$agent['name_label_en']:$agent['name_label_fr']?></label>
								<div class="input-group">
									<input type="text" name="name" placeholder="<?php echo $_SESSION['got']['l'] == 'e'?$agent['name_field_en']:$agent['name_field_fr']?>" class="form-email form-control" required>
									<span class = "input-group-btn">
										<button type="button" id="name" class="btn btn-next"></span>&nbsp; <?php echo $_SESSION['got']['l'] == 'e'?$agent['next_button_en']:$agent['next_button_fr']?></button>
									</span>
								</div>
							</div>
						</fieldset>
						<fieldset>
							<div class="form-top">
								<div class="form-top-left">
									<h1><?php echo $_SESSION['got']['l'] == 'e'?str_replace('[city]', $agent['city'], $agent['title_en']):str_replace('[city]', $agent['city'], $agent['title_fr'])?></h1>
									<h3><?php echo $_SESSION['got']['l'] == 'e'?$agent['sub_title_en']:$agent['sub_title_fr']?></h3>
								</div>
							</div>
							<div class="form-bottom">
								<label><span><i class="fa fa-mobile" aria-hidden="true"></i></span></span>&nbsp;</span>&nbsp; <?php echo $_SESSION['got']['l'] == 'e'?$agent['phone_label_en']:$agent['phone_label_fr']?></label>
								<div class="input-group">
									<input type="text" name="phone" placeholder="<?php echo $_SESSION['got']['l'] == 'e'?$agent['phone_field_en']:$agent['phone_field_fr']?>" class="form-email form-control" required>
									<span class = "input-group-btn">
										<button type="button" id="phone" class="btn btn-next"></span>&nbsp; <?php echo $_SESSION['got']['l'] == 'e'?$agent['next_button_en']:$agent['next_button_fr']?></button>
									</span>
								</div>
							</div>
						</fieldset>
						<fieldset>
							<div class="form-top">
								<div class="form-top-left">
									<h1><?php echo $_SESSION['got']['l'] == 'e'?str_replace('[city]', $agent['city'], $agent['title_en']):str_replace('[city]', $agent['city'], $agent['title_fr'])?></h1>
									<h3><?php echo $_SESSION['got']['l'] == 'e'?$agent['sub_title_en']:$agent['sub_title_fr']?></h3>
								</div>
							</div>
							<div class="form-bottom">
								<label class="last"><span><i class="fa fa-check" aria-hidden="true"></i></span></span>&nbsp;</span>&nbsp; <?php echo $_SESSION['got']['l'] == 'e'?$agent['thank_you_en']:$agent['thank_you_fr']?></label>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
			<footer>
				<p>© copyright <span id="copyDate"></span> - <a href="http://unbeleadsable.com">Unbeleadsable</a></p>
			</footer>
		</div>

	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<script>
		$(document).ready(function () {
			var d = new Date();
			var n = d.getFullYear();
			var map,marker;
			var lead_id = 1;
			$("#copyDate").html(n);

			var next_step = false;
			var lead_id = -1;

			$('.registration-form fieldset:first-child').fadeIn('slow');

			$('.registration-form input[type="text"]').on('focus', function () {
				$(this).removeClass('input-error');
			});

			// next step
			$('.registration-form .btn-next').on('click', function () {
				var parent_fieldset = $(this).parents('fieldset');
				next_step = true;

				parent_fieldset.find('input[type="email"]').each(function () {
					var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
					if (!re.test($(this).val())) {
						$(this).addClass('input-error');
						next_step = false;
					} else
					$(this).removeClass('input-error');
				});

				parent_fieldset.find('input[type="text"]').each(function () {
					if ($(this).val() == "") {
						$(this).addClass('input-error');
						next_step = false;
					} else
					$(this).removeClass('input-error');
				});

				if (next_step) {
					parent_fieldset.fadeOut(400, function () {
						$(this).next().fadeIn();
					});
				}

			});

			$('#email').on('click', function () {
				var parent_fieldset = $(this).parents('fieldset');
				if(next_step){
					$.post("core.php", {action: "addBuyer", email: parent_fieldset.find('input[type="email"]').val(),agent: <?php echo $agent['agent_fk'] ?>, src: "<?php echo $_SESSION['got']['s'] ?>", lang: "<?php echo $_SESSION['got']['l'] ?>"}).done(function(data){
						lead_id = data.trim();
						console.log(lead_id);
					});
				}
			});

			$('.updateBtn').on('click', function () {
				var name = $(this).data('name'), val = $(this).text();

				if(next_step)
					$.post("core.php", {action: "updateField", name: name, val: val, id: lead_id});
			});

			$('#name, #phone').on('click', function () {
				var parent_fieldset = $(this).parents('fieldset');
				var name = parent_fieldset.find('input[type="text"]').attr('name'),
				val = parent_fieldset.find('input[type="text"]').val();

				if(next_step)
					$.post("core.php", {action: "updateField", name: name, val: val, id: lead_id});
			});

		});

	</script>

</body>
</html>