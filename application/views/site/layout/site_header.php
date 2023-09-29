<!DOCTYPE html>
<html>
  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="all,follow">

    <!-- Search Engine Indexing -->
    <?php echo prevent_sandbox_indexing(); ?>
	
	<!-- No Javascript -->
	<?php echo check_javascript_enabled(); ?>
	
	<title><?php echo $title; ?> | <?php echo software_name; ?></title>

    <?php require "application/views/site/layout/includes/header_assets.php"; ?>
	
</head>
<body>

  
    <div class="page login-page">