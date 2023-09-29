<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo software_sub_tagline; ?>"> 
    <meta name="keyword" content="<?php echo software_keywords; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Search Engine Indexing -->
    <?php echo prevent_sandbox_indexing(); ?>
	
  	<!-- No Javascript -->
  	<?php echo check_javascript_enabled(); ?>
    
    <title><?php echo $title; ?> | <?php echo software_name; ?></title>
    <?php require("application/views/web/layout/includes/header_assets.php"); ?> 

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-135557001-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-135557001-1');
    </script>

</head>

 

<!--==========================
    Header
  ============================-->
  <header id="header">
    <div class="container">

      <div id="logo" class="pull-left">
         <a href="#intro"><img src="<?php echo base_url(); ?>assets/web/img/logo.png" alt="" title=""></a>
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">

          <li class="menu-active"><a href="<?php echo base_url(); ?>">Home</a></li>

          <li class="menu-has-children"><a class="text-white">About</a>
            <ul>
              <li><a href="<?php echo base_url(); ?>#about">About <?php echo software_initials; ?></a></li>
              <li><a href="<?php echo base_url('web/features'); ?>">Features</a></li>
              <li><a href="<?php echo base_url(); ?>#price-table">Pricing</a></li> 
              <li><a href="<?php echo base_url(); ?>#product_reviews">Reviews</a></li> 
              <li><a href="<?php echo base_url(); ?>#product_screenshots">Screenshots</a></li> 
            </ul>
          </li>

          <li class="menu-has-children"><a class="text-white">Demo</a>
            <ul>
              <li><a href="<?php echo base_url('demo/login'); ?>">Admin/Staff</a></li>
              <li><a href="<?php echo base_url('demo/user_login'); ?>">Student/Parent</a></li>
            </ul>
          </li> 

          <li class="menu-has-children"><a class="text-white">Register</a>
            <ul>
              <li><a href="<?php echo base_url('install'); ?>">Try Free</a></li>
              <li><a href="<?php echo base_url('buy'); ?>">Buy</a></li>
            </ul>
          </li>

          <li class="menu-has-children"><a class="text-white">Login</a>
            <ul>
              <li><a href="<?php echo base_url('user_login'); ?>">Student/Parent Login</a></li>
              <li><a href="<?php echo base_url('login'); ?>">Admin/Staff Login</a></li>
            </ul>
          </li>

          <li><a href="#contact">Contact</a></li>

          <li class="menu-has-children"><a class="text-white">Support</a>
            <ul>
				<li><a href="<?php echo base_url('web/faq'); ?>">FAQs</a></li>
				<li><a href="<?php echo base_url('web/admin_videos'); ?>">Admin Videos</a></li>
				<!--
				<li><a href="<?php echo base_url('web/staff_videos'); ?>">Staff Videos</a></li>
				<li><a href="<?php echo base_url('web/student_videos'); ?>">Student Videos</a></li>
				<li><a href="<?php echo base_url('web/parent_videos'); ?>">Parent Videos</a></li>
				-->
            </ul>
          </li>

        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- #header -->


  <body>
    <div class="body">

        <!-- banner -->
        <div class="jumbotron jumbotron-fluid" id="banner" style="background-image: url(<?php echo base_url(); ?>assets/web/img/background.jpg);">
            <div class="container text-center text-md-left">

                <h1 data-aos="fade" data-aos-easing="linear" data-aos-duration="1000" data-aos-once="true" class="display-3 text-white text-center font-weight-bold my-5">
                    <?php echo software_name; ?>
                </h1>
                <p data-aos="fade" data-aos-easing="linear" data-aos-duration="1000" data-aos-once="true" class="lead text-center text-white my-4" style="font-size: 50px;">
                     <?php echo $title; ?>
                </p>
            </div>
        </div>