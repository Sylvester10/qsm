<!--Page content-->

	<div class="container d-flex align-items-center">
		<div class="form-holder">
			<div class="row">
				<!-- Form Panel    -->
				<div class="col-lg-6 offset-md-3 p-30 b-r-5 bg-white">
					
					<div class="content">
						<div class="text-center p-15">
						
							<ul class="home_links">
								<li class="m-r-30"><a href="<?php echo base_url(); ?>">Home</a></li>
								<li class="m-r-30"><a href="<?php echo base_url('install/3'); ?>">Try Free</a></li>
								<li><a href="<?php echo base_url('login'); ?>">Login</a></li>
							</ul>
							
							<h2><?php echo software_name; ?> &raquo; Buy</h2>
							<img class="install-banner" src="<?php echo base_url('assets/images/qsm-banner.png'); ?>" />
						</div>

						<div class="card m-t-30">
							<div class="card-header bg-red">
								<i class="fa fa-paypal"></i> Transaction Status
							</div> 
							
							<div class="card-body">
					
								<h4 class="error">We are sorry! Your transaction was canceled.</h4> 
								<p><a class="btn btn-primary" href="<?php echo base_url($action_url); ?>">Try Again</a></p>
								
							</div>
						</div>
		
					</div>
					
				</div>	
			</div>
		</div>
	</div>
	
<!--/Page content-->
			