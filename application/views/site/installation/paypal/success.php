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
							<div class="card-header bg-blue">
								<i class="fa fa-paypal"></i> Transaction Status
							</div> 
							
							<div class="card-body">
					
								<p class="success"> Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you. A summary of your payment is given below: </hp>
								
								<p>School Name: <span><?php echo $school_name; ?></span></p> 
								<p>Payment for: <span><?php echo $payment_for; ?></span></p> 
								<p>Transaction ID: <span><?php echo $txn_id; ?></span></p>
								<p>Amount Paid: <span>$<?php echo $amount.' '.$currency_code; ?></span></p>
								<p>Payment Status: <span><?php echo $status; ?></span></p>
								
								<p class="success"> You may log into your account at <a href="https://www.paypal.com" target="_blank">www.paypal.com</a> to view details of this transaction. </p> 
								
								
								<?php
								//check if transaction was completed
								if ($status == 'Completed') { ?>
								
									<div class="alert alert-info text-center text-bold f-s-17">
										<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
										Before you can begin using your account, we have to ensure your email address is valid. We have sent a message to your email with further instructions to confirm your account. Please check your inbox or spam folder for this message. 
									</div>             
									
								<?php } ?>
								
							</div>
						</div>
		
					</div>
					
				</div>	
			</div>
		</div>
	</div>
	
<!--/Page content-->
			