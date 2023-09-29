<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Search Engine Indexing -->
    <?php echo prevent_sandbox_indexing(); ?>
	
	<!-- No Javascript -->
	<?php echo check_javascript_enabled(); ?>
	
	<link rel="icon" href="<?php echo school_favicon; ?>" type="image/png" />

    <title><?php echo $title; ?> | <?php echo software_name; ?> </title>

    <?php 
	//require header assets files
	require "application/views/shared/assets/header_assets.php"; ?>

	<!-- Print Stylesheet -->
	<link href="<?php echo base_url(); ?>assets/custom/css/print_style.css" rel="stylesheet" media="print">
	
</head>

<body class="print_page" onload="window.print();">
	
	<div class="print_container">