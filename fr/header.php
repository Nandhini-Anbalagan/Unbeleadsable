<?php
  $page = rtrim(basename($_SERVER['PHP_SELF']), ".php");
  require_once('../app/models/Config.class.php')
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <base href="<?php echo substr(Config::WEBSITE_URL, 0, -3)  ?>">
  <title>Real Estate Seller Leads CRM | Unbeleadsable</title>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="...">
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
        <a class="navbar-brand" href="/fr" title="Unbeleadsable">
          <img class="logo hidden-sm" alt="Unbeleadsable" src="assets/img/logo.png">
          <img class="logo-sm hidden-lg hidden-md hidden-xs" alt="Unbeleadsable" src="assets/img/logo.png">
        </a>
      </div>
      <div class="collapse navbar-collapse" id="onepage-nav">
        <ul class="nav navbar-nav navbar-right">
          <li><a href="fr/#video-reviews" data-target="#video-reviews">Témoignages</a></li>
          <li><a href="fr/#book-features" data-target="#book-features">Caractéristiques</a></li>
          <li><a href="fr/#free-samples" data-target="#free-samples">Aperçu</a></li>
          <li><a href="fr/#pricing-offers" data-target="#pricing-offers">Tarification</a></li>
          <li><a href="fr/#about-author" data-target="#about-author">À propos</a></li>
          <li><a href="" data-toggle="modal" data-target="#contactUsModal">Contactez nous</a></li>
          <li><a href="/app">Connexion</a></li>
          <li><a href="/">English</a></li>
        </ul>
      </div>
    </div>
  </nav>

<?php include("contactUsPopup.php") ?>
