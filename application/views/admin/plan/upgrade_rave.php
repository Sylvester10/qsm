<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<div class="row">

	<div class="col-md-8">
		<h3>Current Plan: <?php echo school_plan; ?></h3>
		<h3>Price: <?php echo $price; ?></h3>
		<h3>Activated: <?php echo account_activation_date; ?></h3>
	</div>
	
</div>

<?php 
//check if account has been activated and check if plan is the highest (ie 3)
if (school_account_activation_status == 'false') { ?>

	<?php require "application/views/admin/plan/modals/switch_plan.php"; ?>
			
	Your school account has not been activated, if you'd like to change your plan, click the button below to switch.
	<br />
	<button class="btn btn-primary" data-toggle="modal" data-target="#switch_plan" title="Switch to a new plan to access features in that plan">Switch Plan</button>		
	
<?php } else { //account not activated ?>

	<?php	
	//check if plan is the highest (ie 3)
	if (school_plan_id == 3) { ?>

		You are currently on the highest plan. Renewal is due on <?php echo account_renewal_date; ?>.
		
	<?php } else { //plan not highest ?>

		<h4 class="text-bold f-s-17">Upgrade your account to unlock more features/modules.</h4>

		<?php echo software_name; ?> operates a fairness policy. We handle upgrades on a prorated basis. Rather than pay the full price for upgrading to a new plan, you will only be charged the price difference between your current plan and the desired plan. Your account will be due for renewal 1 year from the day it was activated. The table below shows exactly how much you are required to pay to upgrade to your new plan. 

		<div class="table-scroll m-t-15">
			<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
				<thead>
					<tr>
						<th class="w-5"> S/N </th>
						<th class="w-15"> Plan </th>
						<th class="w-25"> Original Price </th>
						<th class="w-25"> Upgrade Price </th>
					</tr>
				</thead>
				<tbody>
				
					<?php
					$count = 1;
					foreach ($upgrade_plans as $p) { 

						$plan_id = $p->id;
						$price = $this->common_model->get_plan_price_by_location($plan_id); //naira or dollar 
						//get upgrade amount
						$additional_amount = $this->plan_model->get_additional_amount($plan_id);
						$upgrade_amount = number_format($additional_amount, 2);
						$upgrade_price = $currency_code . $upgrade_amount; ?>

						<tr class="<?php echo ($plan_id == 1) ? 'bg-red' : NULL; ?>">
							<td><?php echo $count; ?></td>
							<td><?php echo $p->plan; ?></td>
							<td><?php echo $price; ?></td>
							<td><?php echo ($plan_id == 1) ? ' - ' : $upgrade_price; ?></td>
						</tr>

						<?php $count++; 
					} ?>
					
				</tbody>
			</table>
		</div>



		<div class="row m-t-15">

			<div class="col-md-5 p-b-30">

				<div class="x_panel">
					<div class="x_title">
						<h4 class="page_title text-bold f-s-17">Debit/Credit Card Payment</h4>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">

						<div class="text-center">
							<img class="payment_logo img-responsive" src="<?php echo base_url('assets/images/debit_card.jpg'); ?>" />
							<img class="payment_logo img-responsive" src="<?php echo base_url('assets/images/express_card.jpg'); ?>" />
						</div>

						Make payment using your debit/credit card. Our payment system supports Visa card, Master card, Verve card, American Express card. We accept payment in US Dollars (USD) and Nigerian Naira (NGN).  
						
						<div class="m-t-15"></div>
						Debit/Credit card payment attracts instant upgrade upon confirmation of payment.
				
						<div class="m-t-15"></div>
							
						<?php 
						$form_attributes = array("id" => "initiate_upgrade_with_rave_form"); 
						echo form_open('upgrade_rave/initiate_upgrade', $form_attributes); ?>

							<script src="<?php echo $script_url; ?>"></script>
							
							<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
							<input type="hidden" name="currency" id="currency" value="<?php echo $currency; ?>" />
							<input type="hidden" name="email" id="email" value="<?php echo $email; ?>" />
							<input type="hidden" name="first_name" id="first_name" value="<?php echo $first_name; ?>" />
							<input type="hidden" name="phone" id="phone" value="<?php echo $phone; ?>" />
							<input type="hidden" name="school_id" id="school_id" value="<?php echo $school_id; ?>" />
							<input type="hidden" id="school_name" value="<?php echo $school_name; ?>" />

							<div class="form-group">
								<label>Upgrade to:</label>
								<select class="form-control" name="plan_id" required>
									<option value="">Select Plan</option>
						
									<?php 
									foreach ($upgrade_plans as $p) {
										$plan_id = $p->id;
										//get upgrade amount
										$additional_amount = $this->plan_model->get_additional_amount($plan_id);
										$upgrade_amount = number_format($additional_amount, 2);
										$upgrade_price = $currency_code . $upgrade_amount; ?>
										<option class="<?php echo ($plan_id == 1) ? 'hide' : NULL; ?>" value="<?php echo $p->id; ?>"><?php echo $p->plan; ?> (<?php echo $upgrade_price; ?>)</option>
									<?php } ?>
									
								</select>
							</div>
								
							<div id="status_msg"></div>
							
							<button type="submit" class="btn btn-success btn-lg">Pay & Upgrade</button>
							
						<?php echo form_close(); ?>

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

						<div class="text-center">
							<img class="payment_logo_other img-responsive m-b-10" src="<?php echo base_url('assets/images/payment_other.jpg'); ?>" />
						</div>

						Make payment using other modes of payment such as POS/ATM/wire transfer, internet/mobile app transaction, bank deposit, cheque, mobile wallet, etc. 
						<br />
						Pay into any of the following accounts:

						<div class="text-bold m-t-15">Zenith Bank, Nigeria</div>
						Account Name: Quest to Reality Ltd. <br />
						Account Number: 1015142864

						<div class="text-bold m-t-15">First Bank, Nigeria</div>
						Account Name: Quest to Reality Ltd. <br />
						Account Number: 2031452089
						
						<div class="text-bold m-t-15">GTBank, Nigeria</div>
						Account Name: Quest to Reality Ltd. <br />
						Account Number: 0170793321
						
						<div class="text-bold m-t-15">Diamond Bank, Nigeria</div>
						Account Name: Quest to Reality Ltd. <br />
						Account Number: 0091033465

						<div class="m-t-15"></div>
						After payment, send mail containing your payment information (school name, account plan, amount paid, transaction reference/ID, date paid) to <b><?php echo software_support_mail; ?>.</b> 
						
						<div class="m-t-15"></div>
						Upon confirmation of payment, you will receive a code which you will use to complete your account upgrade. Your code will be shown below if confirmed.

						<div class="m-t-15"></div>

						<?php 
						if ( ! $upgrade_request) { ?>


							<?php 
							echo form_open('upgrade/initiate_upgrade_other'); ?>

								<div class="form-group">
									<label>Upgrade to:</label>
									<select class="form-control" name="plan_id" required>
										<option value="">Select Plan</option>
							
										<?php 
										foreach ($upgrade_plans as $p) {
											$plan_id = $p->id;
											//get upgrade amount
											$additional_amount = $this->plan_model->get_additional_amount($plan_id);
											$upgrade_amount = number_format($additional_amount, 2);
											$upgrade_price = $currency_code . $upgrade_amount; ?>
											<option class="<?php echo ($plan_id == 1) ? 'hide' : NULL; ?>" value="<?php echo $p->id; ?>"><?php echo $p->plan; ?> (<?php echo $upgrade_price; ?>)</option>
										<?php } ?>
										
									</select>
								</div>
									
								<button type="submit" class="btn btn-success btn-lg" title="Payment not confirmed. Pay now to receive confirmation code">Upgrade</button> 

							<?php echo form_close(); ?>


						<?php } else { ?>


							Upgrading to: <?php echo $this->common_model->get_plan_details($upgrade_request->upgrade_plan_id)->plan; ?>

							<?php 
							//show change plan form only if confirmation has not been sent
							if ($upgrade_request->show_upgrade_code != 'true') { ?>

								<?php require "application/views/admin/plan/modals/change_upgrade_plan.php"; ?>

								<a class="text-bold underline-link" style="cursor: pointer" data-toggle="modal" data-target="#change_upgrade_plan">Change</a>	

							<?php } ?>


							<?php
							//get upgrade amount
							$additional_amount = $this->plan_model->get_additional_amount($upgrade_request->upgrade_plan_id);
							$upgrade_amount = number_format($additional_amount, 2);
							$upgrade_price = $currency_code . $upgrade_amount; ?>

							<br />
							Upgrade Price: <?php echo $upgrade_price; ?>

							<br />
							Confirmation Status: <?php echo ($upgrade_request->show_upgrade_code == 'true') ? '<span class="text-bold text-success">Confirmed</span>' : '<span class="text-bold text-danger">Unconfirmed</span>'; ?>

							<br />
							Upgrade Code: <?php echo ($upgrade_request->show_upgrade_code == 'true') ? $upgrade_request->upgrade_code : NULL; ?>

							<div class="m-t-15"></div>		

							<?php 
							if ($upgrade_request->show_upgrade_code == 'true') { ?>

								<a type="submit" class="btn btn-success btn-lg" href="<?php echo base_url('upgrade/upgrade_school_account_other/'.$upgrade_request->upgrade_plan_id); ?>" title="Payment confirmed. You may now complete your account upgrade by clicking this button">Complete Upgrade</a> 

							<?php } else { ?>

								<button type="submit" class="btn btn-success btn-lg" title="Payment not confirmed. Pay now to receive confirmation code" disabled>Complete Upgrade</button> 

							<?php } ?>

						<?php } ?>

					</div>
				</div>
			</div>

		</div>

		
	<?php } //if plan is highest ?>

<?php } // if activated ?>
