 	

  <link rel="icon" href="<?php echo base_url(); ?>assets/web/img/favicon1.ico" sizes="32x32" type="image/png">

    <!-- bootstrap.min.css -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/web/css/bootstrap.min.css">

	<!-- font-awesome -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/web/font-awesome-4.7.0/css/font-awesome.min.css">
    
    <!-- AOS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/web/css/aos.css">


    <!-- Bootstrap CSS File -->
  <link href="<?php echo base_url(); ?>assets/web/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="<?php echo base_url(); ?>assets/web/lib/animate/animate.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/web/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/web/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets/web/lib/magnific-popup/magnific-popup.css" rel="stylesheet">

  <!-- Owl Carousel -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/web/lib/owl-carousel/css/owl.carousel.css" type="text/css">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/web/lib/owl-carousel/css/owl.theme.css" type="text/css">

  <!-- Lightbox -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/lightbox/dist/css/lightbox.min.css" />

  <!-- Custom Css -->
  <link href="<?php echo base_url(); ?>assets/custom/css/helper.css" rel="stylesheet" media="all">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/web/css/custom.css">

    
  

  
  <?php
  $show_popup_ad = false;
  if ($show_popup_ad == true) { ?>
	  
	  <div class="modal fade" id="popup_ad" role="dialog">
		<div class="modal-dialog">
		  <div class="modal-content modal-form">
			<div class="modal-header">
			  <h4 class="modal-title">Hello there!</h4>
			</div><!--/.modal-header-->
			<div class="modal-body">

			  <a href="<?php echo base_url('buy/3'); ?>">
				<img class="img-responsive" src="<?php echo base_url('assets/web/img/advert/academix_offer.jpeg'); ?>" />
			  </a>
			  
			</div>
			<div class="modal-footer">
			  <button class="btn btn-danger" data-dismiss="modal" class="close" title="Close dialog">Close</button>
			</div>
		  </div>
		</div>
	  </div>
	  
  <?php } ?>


