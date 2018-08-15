<?php
	require_once('head.php');
	$p = explode("/", $_SERVER['REQUEST_URI']);
 ?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo Config::WEBSITE_TITLE . " ::. " . ucwords(str_replace(array("-", "_"), " ", $p[COUNT($p) - 1]))?></title>
		<base href="<?php echo Config::WEBSITE_URL; ?>/">

		<!-- Morris Chart CSS -->
		<link rel="stylesheet" href="assets/plugins/morris/morris.css">

		<?php if(in_array($page, $datatablePages)){ ?>
			<!-- DataTables -->
			<link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
			<link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />

			<link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
			<link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
			<link href="assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />

			<link href="assets/plugins/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
		<?php }else if($page == "agent_lead"){ ?>
			<link href="assets/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">
			<link href="assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
		<?php } ?>

		<!-- Select2 -->
		<link href="assets/plugins/select2/select2.css" rel="stylesheet" type="text/css" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
		<link href="assets/css/core.css?v=<?php echo time() ?>" rel="stylesheet" type="text/css" />
		<link href="assets/css/components.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/menu.css" rel="stylesheet" type="text/css" />

		<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->

		<!-- Sweet Alert -->
		<link href="assets/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">

		<!-- Style CSS -->
		<link rel="stylesheet" type="text/css" href="assets/css/style.css?v=<?php echo time(); ?>">

		<!-- Font Awesome -->
		<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">

		<!-- Toastr -->
		<link rel="stylesheet" type="text/css" href="assets/css/toastr.min.css">

		<!-- switchery css-->
		<link href="assets/plugins/switchery/dist/switchery.min.css" rel="stylesheet" />

		<!-- favicon -->
		<link href="favicon.png" type="image/x-icon" rel="shortcut icon">

		<!-- Modernizr -->
		<script src="assets/js/modernizr.min.js"></script>

		<!-- jQuery CDN -->
		<script src="assets/js/jquery.js"></script>
	</head>
	<body>
