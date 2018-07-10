<?php
	session_start();

	if (!empty($_GET))
		$_SESSION['got'] = $_GET;

	if(!empty($_SESSION['got'])){
		include("../app/head.php");
		$agent = $db->getSellerLandingPage($_SESSION['got']['a']);

		$final_text_en = explode(" - ", $agent['final_text_en']);
		$final_text_fr = explode(" - ", $agent['final_text_fr']);
	}
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

	<?php if($_GET['l'] == 'e'){ ?>
	<title>What's your home really worth?</title>
	<meta name="DESCRIPTION" content="Find out the value of your home with this free home value calculator"/>

	<meta property="og:title" content="What's your home really worth?" />
	<meta property="og:site_name" content="Unbeleadsable.com" />
	<meta property="og:description" content="Find out the value of your home with this free home value calculator" />
	<meta property="og:image" content="https://unbeleadsable.com/cdn/img/default_en.jpg" />
	<?php }else if($_GET['l'] == 'f'){ ?>
	<title>Quelle est la vrai valeur de votre maison?</title>
	<meta name="DESCRIPTION" content="Découvrez la valeur de votre maison avec ce calculateur de valeur de maison gratuit"/>

	<meta property="og:title" content="Quelle est la vrai valeur de votre maison?" />
	<meta property="og:site_name" content="Unbeleadsable.com" />
	<meta property="og:description" content="Découvrez la valeur de votre maison avec ce calculateur de valeur de maison gratuit" />
	<meta property="og:image" content="https://unbeleadsable.com/cdn/img/default_fr.jpg" />
	<?php } ?>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">

</head>
<body style="background-image: url('../app/uploads/landings/<?php echo $agent['bg_img']?>')">

		<header>
			<div class="container">
				<div class="col-xs-12">
					<h1><i class="glyphicon glyphicon-home"></i>&nbsp;&nbsp;<span class="white"><?php echo $_SESSION['got']['l'] == 'e'?$agent['city_en']:$agent['city_fr'] ?> </span></h1>
				</div>
			</div>
		</header>
	<div class="page-wrap">

		<section class="homeEvalutation">
			<div class="container">
				<div class="col-xs-12 form-out">
					<div class="col-xs-12 form-wrap">
						<form id="homeEval" class="regform" method="POST">
							<input type="hidden" name="">
							<!-- Fieldsets -->
							<fieldset id="first">
								<h2><?php echo $_SESSION['got']['l'] == 'e'?$agent['title_en']:$agent['title_fr'] ?></h2>
								<div class="input-wrap col-md-8 col-xs-8">
									<input id="location" type="text" size="50" name="location" value="" placeholder="<?php echo $_SESSION['got']['l'] == 'e'?'Enter Address Here':'Entrez votre adresse' ?>" class="input-element" required="" autocomplete="off">
								</div>
								<div class="input-wrap col-md-2 col-xs-4">
									<input id="apt" type="text" size="50" name="apt" value="" placeholder="<?php echo $_SESSION['got']['l'] == 'e'?'APT #':'Apt #' ?>" class="input-element" required="">
								</div>
								<div class="input-wrap col-md-2 col-xs-12">
									<input class="btn btn-block red-btn" name="next" type="button" value="<?php echo $_SESSION['got']['l'] == 'e'?'Next':'Suivant' ?>">
								</div>
							</fieldset>
							<fieldset id="second">
								<h2><?php echo $_SESSION['got']['l'] == 'e'?$agent['sub_title_1_en']:$agent['sub_title_1_fr'] ?></h2>
								<div class="col-md-6 col-xs-12">
									<div id="map"></div>
								</div>
								<div class="col-md-6 col-xs-12 leadInfo">
									<div class="col-md-12 form-group">
										<input class="form-control" name="name" placeholder="<?php echo $_SESSION['got']['l'] == 'e'?'Your Name':'Votre nom' ?>" type="text">
									</div>
									<div class="col-md-12 form-group">
										<input class="form-control" name="email" placeholder="<?php echo $_SESSION['got']['l'] == 'e'?'Your Email':'Votre courriel' ?>" type="text">
									</div>
									<div class="col-md-12 form-group">
										<input class="form-control" name="phone" placeholder="<?php echo $_SESSION['got']['l'] == 'e'?'Your Phone':'Votre téléphone' ?>" type="text">
									</div>
									<div class="col-md-12 form-group">
										<select class="options form-control" name="selling">
											<option value=""><?php echo $_SESSION['got']['l'] == 'e'?'--Selling In--':'--Date de vente--' ?></option>
											<option value="1"><?php echo $_SESSION['got']['l'] == 'e'?'1-3 Months':'1-3 Mois' ?></option>
											<option value="2"><?php echo $_SESSION['got']['l'] == 'e'?'3-6 Months':'3-6 Mois' ?></option>
											<option value="3"><?php echo $_SESSION['got']['l'] == 'e'?'6-12 Months':'6-12 Mois' ?></option>
											<option value="4"><?php echo $_SESSION['got']['l'] == 'e'?'More than 12 Months':'Plus que 12 Mois' ?></option>
											<option value="5"><?php echo $_SESSION['got']['l'] == 'e'?'Just Curious':'Par curiosité' ?></option>
											<option value="6"><?php echo $_SESSION['got']['l'] == 'e'?'Refinancing':'Refinancement' ?></option>
										</select>
									</div>
									<div class="col-md-6">
										<input class="btn btn-block red-btn" name="previous" type="button" value="<?php echo $_SESSION['got']['l'] == 'e'?'Previous':'Retour' ?>">
									</div>
									<div class="col-md-6">
										<input class="btn btn-block red-btn" name="next" type="button" value="<?php echo $_SESSION['got']['l'] == 'e'?'Get Value':'Obtenir la valeur' ?>">
									</div>
								</div>


							</fieldset>
							<fieldset id="third">
								<h2><?php echo $_SESSION['got']['l'] == 'e'?$agent['sub_title_2_en']:$agent['sub_title_2_fr'] ?></h2>
								<div class="agent-profile">
									<?php if($agent['agent_avatar'] != "default.jpg" AND !empty($agent['agent_avatar'])){ ?>
									<div class="col-md-6 info">
										<div class="row">
											 <div class="col-md-4" style="margin: 0; padding: 0">
												<img src="../app/uploads/avatars/<?php echo $agent['agent_avatar'] ?>" alt="avatar" class="img-thumbnail img-responsive">
											</div>
											<div class="col-md-8 agentInfo">
												<p><?php echo $agent['agent_name'] ?></p>
												<p><em><?php echo $_SESSION['got']['l'] == 'e'?$agent['agent_title_en']:$agent['agent_title_fr'] ?></em></p>
												<p><em><?php echo $agent['agent_agency'] ?></em></p>
												<p><a href="mailTo:<?php echo $agent['agent_email'] ?>"><?php echo $agent['agent_email'] ?></a></p>
												<p><?php echo $agent['agent_phone'] ?></p>
											</div>
										</div>
									</div>
									<?php }else{ ?>
									<div class="col-md-6 agentInfo">
										<p><?php echo $agent['agent_name'] ?></p>
										<p><em><?php echo $_SESSION['got']['l'] == 'e'?$agent['agent_title_en']:$agent['agent_title_fr'] ?></em></p>
										<p><em><?php echo $agent['agent_agency'] ?></em></p>
										<p><a href="mailTo:<?php echo $agent['agent_email'] ?>"><?php echo $agent['agent_email'] ?></a></p>
										<p><?php echo $agent['agent_phone'] ?></p>
									</div>
									<?php } ?>
									<div class="col-md-6">

									<?php
										$p = "";
										$final_text = $_SESSION['got']['l'] == 'e'?$final_text_en:$final_text_fr;
										echo "<p>" . nl2br(implode("", $final_text)) . "</p>";
									?>
									</div>
								</div>
							</fieldset>
						</form>


					</div>
				</div>
			</div>
		</section>

	</div>
	<footer>
		<div class="container">
			<div class="col-md-12 text-center">
				<p>© copyright <span id="copyDate"></span> - <a href="http://unbeleadsable.com" style="color:#999999"> Unbeleadsable</a></p>
			</div>
		</div>
	</footer>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8hEM4oF88dSUvW3MidSqSlbDf4oxwRXI&amp;libraries=places"></script>

	<script>
		(function ($) {
			var d = new Date();
			var n = d.getFullYear();
			var map,marker;
			var lead_id = 1;
			$("#copyDate").html(n);

			var myLatlng = new google.maps.LatLng(45.5549753,-73.9491394);
			var mapOptions = {
				zoom: 1,
				center: myLatlng
			}

			map = new google.maps.Map(document.getElementById('map'), mapOptions);

			marker = new google.maps.Marker({
				position: myLatlng,
				map: map,
			});

			//Autocomplete variables
			var input = document.getElementById('location'),
			place, autocomplete = new google.maps.places.Autocomplete(input);

			//Add listener to detect autocomplete selection
			google.maps.event.addListener(autocomplete, 'place_changed', function () {
				place = autocomplete.getPlace();
			});

			//Add listener to search
			$("#first input[name='next']").on("click", function() {
				if ($('input[name="location"]').val() == ""){
					alert("Location field is necessary!");
					return false;
				}else{
					var newlatlong = new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());
					map.setCenter(newlatlong);
					marker.setPosition(newlatlong);
					map.setZoom(18);
					map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
					var apt = $('input[name="apt"]').val();

					$.post("core.php", {action: "addAddress", agent: <?php echo $agent['agent_fk'] ?>, src: "<?php echo $_SESSION['got']['s'] ?>", lang: "<?php echo $_SESSION['got']['l'] ?>", address: place.formatted_address, apt:apt})
						.done(function(data) {
							lead_id = data.trim();
						});

					$("#second").fadeIn('slow');
					$("#first").css({'display': 'none'});

					var center = map.getCenter();
					google.maps.event.trigger(map, 'resize');
					map.setCenter(center);

					return true;
				}
			});

			$(".leadInfo input, .leadInfo select").focusout(function() {
				var name = $(this).attr('name'),
				val = $(this).val(),
				arr = ['name', 'email', 'phone', 'selling'];

				if(arr.indexOf(name) != -1 && val != "")
					$.post("core.php", {action: "updateField", name: name, val: val, id: lead_id});
			});

			$("#second input[name='previous']").click(function() {
				$("#first").fadeIn('slow');
				$("#second").css({'display': 'none'});
			});

			$("#second input[name='next']").click(function() {
				var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				if ($('input[name="name"]').val() == ""){
					alert("<?php echo $_SESSION['got']['l'] == 'e'?'Name field is invalid!':'Le champ Nom est obligatoire' ?>");
					return false;
				}else if(!re.test($('input[name="email"]').val())){
					alert("<?php echo $_SESSION['got']['l'] == 'e'?'Email field is invalid!':'Le champ Courriel est obligatoire' ?>");
					return false;
				}else if ($('input[name="phone"]').val() == ""){
					alert("<?php echo $_SESSION['got']['l'] == 'e'?'Phone field is invalid!':'Le champ Téléphone est obligatoire' ?>");
					return false;
				}else if ($('select[name="selling"]').val() == ""){
					alert("<?php echo $_SESSION['got']['l'] == 'e'?'Selling In field is mandatory!':'Le champ \"Date de vente\" est obligatoire' ?>");
					return false;
				}else{
					$("#third").fadeIn('slow');
					$("#second").css({'display': 'none'});
				}
			});
		}(jQuery));

	</script>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-45266806-12', 'auto');
	  ga('send', 'pageview');

	</script>
</body>
</html>
