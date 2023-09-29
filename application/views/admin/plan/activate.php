<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<div class="row">

	<div class="col-md-8">
		<h3>Current Plan: <?php echo school_plan; ?></h3>
		<h3>Price: <?php echo $original_price; ?></h3>

		
		<?php 
		//check if account has been activated
		if (school_account_activation_status == 'false') { 
		
			//check coupon status
			if ( ! $using_free_trial_coupon ) { ?>
				
				<?php require "application/views/admin/plan/modals/activate_coupon.php"; ?>

				<div class="p-b-30">
					Activate Discount Voucher to get discounted price! 
					<br />
					<button class="btn btn-default" data-toggle="modal" data-target="#activate_coupon">Get Discount Now</button>
				</div>

			<?php } else { 

				$coupon_id = $using_free_trial_coupon->coupon_id;
				$coupon_details = $this->coupon_model->get_free_trial_coupon_details($coupon_id);
				$coupon_name = $coupon_details->name;		
				$discount = $coupon_details->discount;		
				$valid_until = $coupon_details->valid_until; ?>

				<div class="bg-success" style="padding: 10px"> 
					You have successfully activated a Discount Voucher. Details of the voucher are given below: <br />
					Voucher Name: <?php echo $coupon_name; ?> <br />
					Discount: <?php echo $discount; ?>% <br />
					Discounted Price: <span class="text-bold"><?php echo $price; ?></span> <br />
					Valid Until: <?php echo x_date_full($valid_until); ?> 
					<small>(Price will revert to original after this date)</small>. <br />
				</div>	

			<?php } 
			
		} ?>
		

	</div>
	
	<div class="col-md-4">	
		<div class="pull-right">
	
		<?php 
		//check if account has been activated
		if (school_account_activation_status == 'false') { ?>
				
			<?php require "application/views/admin/plan/modals/switch_plan.php"; ?>
			
			<button class="btn btn-primary" data-toggle="modal" data-target="#switch_plan" title="Switch to a new plan to access features in that plan">Switch Plan</button>		
			
		<?php } else { 
		
			if (school_plan_id != 3) { ?>
		
				<a class="btn btn-primary" href="<?php echo base_url('upgrade'); ?>">Upgrade Plan</a>
				
			<?php } 
			
		} ?>
		
		</div>				
	</div>
	
</div>

<?php 
//check if account has been activated
if (school_account_activation_status == 'true') { ?>

	Your school account has been activated. Renewal is due on <?php echo account_renewal_date; ?> (<?php echo $subscription_remaining_time; ?> away).
	
<?php } else { //account not activated ?>

	
	<div class="row m-t-15">

		<div class="col-md-5 p-b-30">

			<div class="x_panel">
				<div class="x_title">
					<h4 class="page_title text-bold f-s-17">PayPal, Debit/Credit Card Payment</h4>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">

					<div style="margin-left: -15px">

						<img src="<?php echo base_url($paypal_logo); ?>" class="img-responsive" style="width: 250px; height: auto;" />
						
						<ul style="margin-left: -7px">

							<h3>Price: <?php echo $price; ?></h3>

							<li>Make your payment using PayPal. If you do not have a PayPal account, click on <b>Pay with Debit or Credit Card</b> on the PayPal Checkout page.</li>
							
							<?php
							if ($location == 'Nigeria') { ?>
								
								<li>You'll be charged the dollar equivalent of the amount above on the PayPal Checkout page.</li>
								
							<?php } ?>
							
							<li>PayPal, Debit/Credit card payment attracts instant activation upon confirmation of payment.</li>
						
						</ul>
					
					</div>
			
					<div class="m-t-15"></div>
					
					<a class="btn btn-success btn-lg" href="<?php echo base_url('activate/pay_with_paypal'); ?>"><i class="fa fa-paypal"></i> Pay Now</a>

				</div>
			</div>
		</div>


		<div class="col-md-6 col-md-offset-1">

			<div class="x_panel">
				<div class="x_title">
					<h4 class="page_title text-bold f-s-17">Other Modes of Payment</h4>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">

					<center>
						<img class="payment_logo_other img-responsive m-b-10" src="<?php echo base_url('assets/images/payment_other.jpg'); ?>" />
					</center>

					Make payment using other modes of payment such as POS/ATM/wire transfer, internet/mobile app transaction, bank deposit, cheque, mobile wallet, etc. 
					<br />
					Pay into any of the following accounts:

					<div class="text-bold m-t-15">Firstbank of Nigeria</div>
					Account Name: Sylvester Esso Nmakwe <br />
					Account Number: 3073381601 <br />
					Price: <?php echo $price; ?>

					<div class="m-t-15"></div>
					After payment, send mail containing your payment information (school name, account plan, amount paid, transaction reference/ID, date paid) to <b><?php echo software_support_mail; ?>.</b> 
					
					<div class="m-t-15"></div>
					Upon confirmation of payment, you will receive a code which you will use to activate your account. Your code will be shown below if confirmed.

					<div class="m-t-10"></div>
					Confirmation Status: <?php echo (show_activation_code == 'true') ? '<span class="text-bold text-success">Confirmed</span>' : '<span class="text-bold text-danger">Unconfirmed</span>'; ?>
					<br />
					Activation Code: <?php echo (show_activation_code == 'true') ? activation_code : NULL; ?>

					<div class="m-t-15"></div>

					<?php 
					if (show_activation_code == 'true') { ?>

						<a type="submit" class="btn btn-success btn-lg" href="<?php echo base_url('activate/activate_school_account_other'); ?>" title="Payment confirmed. You may now activate your account by clicking this button">Activate</a> 

					<?php } else { ?>

						<button type="submit" class="btn btn-success btn-lg" title="Payment not confirmed. Pay now to receive confirmation code" disabled>Activate</button> 

					<?php } ?>
						
				</div>
			</div>
		</div>

	</div>

	
<?php } ?>