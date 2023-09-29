
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<?php require("application/views/staff/profile/modals/change_password.php");  ?>

<div class="new-item">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('staff/edit_profile'); ?>"><i class="fa fa-pencil"></i> Update Profile</a>
	<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#change_password"><i class="fa fa-lock"></i> Change Password</button>
</div>


<div class="row">

	<div class="col-md-4">
		<h3 class="text-bold"><i class="fa fa-user f-s-30"></i> Bio Information</h3>
		<p><b>Name:</b> <?php echo $y->name; ?></p>
		<p><b>Sex:</b> <?php echo $y->sex; ?></p>
		<p><b>Date of Birth:</b> <?php echo x_date_full($y->date_of_birth); ?></p>
	</div>


	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-globe f-s-30"></i> Place Information</h3>
		<p><b>Nationality:</b> <?php echo $y->nationality; ?></p>
		<?php if (s_country == 'Nigeria') { ?>
			<p><b>State of Origin:</b> <?php echo $y->state_of_origin; ?></p>
			<p><b>LGA of Origin:</b> <?php echo $y->local_gov; ?></p>
		<?php } ?>
	</div>

	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-institution f-s-30"></i> Employment Information</h3>
		<p><b>Qualification:</b> <?php echo $y->qualification; ?></p>
		<p><b>Designation:</b> <?php echo $y->designation; ?></p>
		<p><b>Date of Employment:</b> <?php echo $y->employment_date; ?></p>
	</div>
	
</div>
	 
	 
<div class="row">

	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-phone f-s-30"></i> Contact Information</h3>
		<p><b>Phone Number:</b> <?php echo $y->phone; ?></p>
		<p><b>Email Address:</b> <?php echo $y->email; ?></p>
		<p><b>Home Address:</b> <?php echo $y->home_address; ?></p>
	</div>

	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-user f-s-30"></i> Next of Kin Information</h3>
		<p><b>Name:</b> <?php echo $y->name_of_kin; ?></p>
		<p><b>Phone Number:</b> <?php echo $y->mobile_of_kin; ?></p>
		<p><b>Email Address:</b> <?php echo $y->email_of_kin; ?></p>
		<p><b>Home Address:</b> <?php echo $y->address_of_kin; ?></p>
	</div>
	
	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-user f-s-30"></i> Account Information</h3>
		<p><b>Account Number:</b> <?php echo $y->acc_number; ?></p>
		<p><b>Bank Name:</b> <?php echo $y->bank_name; ?></p>
	</div>

</div>


<div class="row">

	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-lightbulb-o f-s-30"></i> Other Information</h3>
		<p><b>Religion:</b> <?php echo $y->religion; ?></p>
		<p><b>Role(s):</b> <?php echo $y->role; ?></p>
		<p><b>Additional Information:</b> <?php echo $y->additional_info; ?></p>
	</div>
	
</div>