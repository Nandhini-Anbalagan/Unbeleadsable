<?php
	$page = rtrim(basename($_SERVER['PHP_SELF']), ".php");
	require_once('app/models/Config.class.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?php echo substr(Config::WEBSITE_URL, 0, -3)  ?>">
	<title>Generate Real Estate Leads | Unbeleadsable</title>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="A smart and easy way to generate real estate leads online, Unbeleadsable.com has become a must-have tool for so many agents and we are very proud to be an integral part of their success.">
	<meta name="keywords" content="...">
	<meta name="author" content="...">
	<link href="assets/css/video-js.css" rel="stylesheet">
	<link href="assets/css/colorbox.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/flatbook_custom.css">
	<link rel="stylesheet" href="assets/css/featherlight.min.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="home-page">

	<!-- Preloader -->
	<div id="preloader">
		<div class="aligner">
			<div class="spinner">
				<div class="bar1"></div>
				<div class="bar2"></div>
				<div class="bar3"></div>
				<div class="bar4"></div>
				<div class="bar5"></div>
			</div>
		</div>
	</div>

	<!-- NAVBAR
	============================================== -->
	<nav class="navbar navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#onepage-nav">
					<i class="fa fa-bars"></i>
				</button>
				<a class="navbar-brand" href="/" title="Unbeleadsable">
					<img class="logo hidden-sm" alt="Unbeleadsable" src="assets/img/logo.png">
					<img class="logo-sm hidden-lg hidden-md hidden-xs" alt="Unbeleadsable" src="assets/img/logo.png">
				</a>
			</div>
			<div class="collapse navbar-collapse" id="onepage-nav">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="/#video-reviews" data-target="#video-reviews">Testimonials</a></li>
					<li><a href="/#book-features" data-target="#book-features">Features</a></li>
					<li><a href="/#free-samples" data-target="#free-samples">Sneak Peek</a></li>
					<li><a href="/#pricing-offers" data-target="#pricing-offers">Pricing</a></li>
					<li><a href="/#about-author" data-target="#about-author">About</a></li>
					<li><a href="" data-toggle="modal" data-target="#contactUsModal">Contact Us</a></li>
					<li><a href="/app">Login</a></li>
					<li><a href="/fr">Français</a></li>
				</ul>
			</div>
		</div>
	</nav>


<?php include("contactUsPopup.php") ?>
