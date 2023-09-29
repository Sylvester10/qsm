
		<div id="about">

			<div class="container my-5 py-2">
				<h2 class="text-center font-weight-bold my-5">Easily automate everything, save time and money and gain insight for better and faster decisions</h2>
				<p style="text-align: center;">
					<?php echo software_initials; ?> is a cloud based school management system that makes managing educational institutions easy and efficient. 
					It provides useful insights for students, teachers, administrators and parents and helps them make better and faster decisions.
					<?php echo software_initials; ?> is very easy and intuitive to use. Anyone with basic skills of using a web email would find it easy to setup and use <?php echo software_initials; ?>.
					We offer a FREE 30-day trial for all our plans.
				 </p>
			</div>
			
			
			<!-- Start Video promo Section -->
			<section class="video-promo section m-b-80">
			  <div class="container">
				<div class="row justify-content-center">
				  <div class="col-lg-6">
					  <div class="video-promo-content">
						<h3 class="wow zoomIn" data-wow-duration="1000ms" data-wow-delay="100ms">Find out in 60 seconds how <?php echo software_name; ?> can add value to your school</h3>
					  </div>
				  </div>
				  <div class="col-lg-6 text-center">
					<video controls class="wow zoomIn" style="width: 100%;">
					  <source src="<?php echo base_url(); ?>assets/web/include/intro_video.mp4" type="video/mp4">
					  Your browser does not support HTML5 video.
					</video>
				  </div>
				</div>
			  </div>
			</section>
			<!-- End Video Promo Section -->

			
			<?php require "application/views/web/includes/core_features.php"; ?>


			<!-- feature (skew background) -->
			<div class="jumbotron jumbotron-fluid feature" id="feature-first" style="margin-top: -150px">
				<div class="container my-5">
					<div class="row justify-content-between text-center text-md-left">
						<div data-aos="fade-right" data-aos-duration="1000" data-aos-once="true" class="col-md-6">
							<h2 class="font-weight-bold"></h2>
							<ul style="list-style-type:disc; font-size: 20px;">
							  <li> Easy to use yet powerful school management software.</li>
							  <li> FREE 30 day trial.</li>
							  <li> Saves time and money by automating routine tasks.</li>
							</ul>
							<a href="<?php echo base_url('install/3'); ?>" class="btn my-4 font-weight-bold atlas-cta cta-blue">Start Free Trial</a>
							<a href="<?php echo base_url('demo/login'); ?>" class="btn my-4 font-weight-bold atlas-cta cta-blue">View Demo</a>
						</div>
						<div data-aos="fade-left" data-aos-duration="1000" data-aos-once="true" class="col-md-6 align-self-center">
							<img src="<?php echo base_url(); ?>assets/web/img/feature-12.png" alt="Take a look inside" class="mx-auto d-block">
						</div>
					</div>
				</div>
			</div>

			<!-- feature (green background) -->
			<div class="jumbotron jumbotron-fluid feature m-t-200-n" id="feature-last" style="margin-top: -200px">
				<div class="container">
					<div class="row justify-content-between text-center text-md-left">
						<div data-aos="fade-left" data-aos-duration="1000" data-aos-once="true" class="col-md-6 flex-md-last">
							<h2 class="font-weight-bold"></h2>
							<ul style="list-style-type:disc; font-size: 20px;">
							  <li> Manage all elements of school administration including students, teachers, parents, employees, subjects/courses, batches and more.</li>
							  <li> With daily backups, you will never lose your data.</li>
							</ul>
							<a href="<?php echo base_url('buy/3'); ?>" class="btn my-4 font-weight-bold atlas-cta cta-blue">Buy Now</a>
							<a href="<?php echo base_url('demo/login'); ?>" class="btn my-4 font-weight-bold atlas-cta cta-blue">View Demo</a>
						</div>
						<div data-aos="fade-right" data-aos-duration="1000" data-aos-once="true" class="col-md-6 align-self-center flex-md-first">
							<img src="<?php echo base_url(); ?>assets/web/img/tab.png" alt="Safe and reliable" class="mx-auto d-block">
						</div>
					</div>
				</div>
			</div>

		</div>


		<!-- price table -->
		<div class="container my-5 py-2" id="price-table">
			<h2 class="text-center font-weight-bold d-block mb-3">Check our Pricing</h2>
			<div class="row">
			
				<div data-aos="fade-right" data-aos-delay="200" data-aos-duration="1000" data-aos-once="true" class="col-md-4 text-center py-4 mt-5">
					<h4 class="my-4">LITE</h4>
					
					<p class="font-weight-bold"><?php echo $currency; ?> <span class="home-price font-weight-bold"><?php echo $price_lite; ?></span> / YR.</p>
					<ul class="list-unstyled">
						<li>Student Management</li>
						<li>Courses and Batches</li>
						<li>Student Attendance</li>
						<li>User Management</li>
						<li>Incident Reporting</li>
						<li>Staff reporting</li>
						<li>Financial Requisition</li>
						<li>Data Export</li>
					</ul>
					<a href="<?php echo base_url('install/1'); ?>" class="btn my-4 font-weight-bold atlas-cta cta-ghost">Free Trial</a>
					
					<center>
						<a href="<?php echo base_url('buy/1'); ?>">
							<img src="<?php echo base_url($paypal_buy_button); ?>" class="img-responsive" style="width: 220px; height: auto;" alt="Buy With PayPal" />
						</a>
					</center>
					
				</div>
				
				
				<div data-aos="fade-up" data-aos-duration="1000" data-aos-once="true" class="col-md-4 text-center py-4 mt-5 rounded" id="price-table__premium">
					<h4 class="my-4">PRO</h4>
					
					<p class="font-weight-bold"><?php echo $currency; ?> <span class="home-price font-weight-bold"><?php echo $price_pro; ?></span> / YR.</p>
					
					<ul class="list-unstyled">
						<li style="color: #00FFAD; font-size: 20px"><b>LITE MODULES</b></li>
						<li>+</li>
						<li>Employee/Teacher Login</li>
						<li>Student Reports</li>
						<li>Fee Management</li>
						<li>Library</li>
						<li>Messaging</li>
						<li>Student Bulk Data Import</li>
						<li>Staff Bulk Data Import</li>
					</ul>
					<a href="<?php echo base_url('install/2'); ?>" class="btn my-4 font-weight-bold atlas-cta cta-green">Free Trial</a>
					
					<center>
						<a href="<?php echo base_url('buy/2'); ?>">
							<img src="<?php echo base_url($paypal_buy_button); ?>" class="img-responsive" style="width: 220px; height: auto;" alt="Buy With PayPal" />
						</a>
					</center>
					
				</div>
				
				
				<div data-aos="fade-left" data-aos-delay="200" data-aos-duration="1000" data-aos-once="true" class="col-md-4 text-center py-4 mt-5">
					<h4 class="my-4">PRO PLUS</h4>
					
					<p class="font-weight-bold"><?php echo $currency; ?> <span class="home-price font-weight-bold"><?php echo $price_pro_plus; ?></span> / YR.</p>
					
					<ul class="list-unstyled">
						<li style="color: #121a2e; font-size: 20px"><b>PRO MODULES</b></li>
						<li>+</li>
						<li>Student Login</li>
						<li>Parent Login</li>
						<li>Assignment/Homework</li>
						<li>Time Table</li>
						<li>Events Calendar</li>
						<li>News</li> 
						<li>Newsletters</li>
						<li>Announcement</li>
					</ul>
					<a href="<?php echo base_url('install/3'); ?>" class="btn my-4 font-weight-bold atlas-cta cta-ghost">Free Trial</a>
					
					<center>
						<a href="<?php echo base_url('buy/3'); ?>">
							<img src="<?php echo base_url($paypal_buy_button); ?>" class="img-responsive" style="width: 220px; height: auto;" alt="Buy With PayPal" />
						</a>
					</center>
					
				</div>
				
			</div>
		</div>

		<div id="product_reviews">
			<?php require "application/views/web/includes/testimonials.php"; ?>
		</div>

		<div id="product_screenshots">
			<?php require "application/views/web/includes/screenshots.php"; ?>
		</div>


	</section>