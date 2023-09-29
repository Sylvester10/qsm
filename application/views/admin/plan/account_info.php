
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<p>School Name: <?php echo school_name; ?></p>
<p>
	Account Plan: <?php echo school_plan; ?>
	<?php 
	//show upgrade call-to-action if plan is not Pro Plus
	if (school_plan_id != 3) { ?>
		<span class="text-bold text-primary m-l-5 hide"><a href="<?php echo  base_url('plan/upgrade'); ?>">Upgrade</a></span>
	<?php } ?>
</p>
<p>
	Account Mode: <?php echo (school_account_activation_status != 'false') ? 'Paid' : school_account_mode; ?>
	<?php 
	if (school_account_activation_status == 'false' && school_account_mode == 'Free Trial') { 
		echo ' (' . $free_trial_remaining_time . ')'; 
	} ?>
</p>

<?php 
if (school_account_activation_status == 'false') {
	$activation_link = '<span class="text-bold text-primary m-l-5"><a href="' . base_url('activate') . '">Activate Now</a></span>';
	$school_account_activation_status = '<span class="text-danger text-bold">No</span>' . $activation_link;
} else {
	$school_account_activation_status = '<span class="text-success text-bold">Yes</span>';
} ?>
<p>Installed By: <?php echo chief_admin_name; ?></p>	
<p>Date Installed: <?php echo x_date_full(date_installed); ?> (<?php echo time_ago(date_installed); ?>)</p>	
<p>Activated: <?php echo $school_account_activation_status; ?></p>
<p>Date Activated: <?php echo account_activation_date; ?></p>	
<p>Date of Last Upgrade: <?php echo last_upgraded; ?></p>	
<p>Date of Next Renewal: <?php echo account_renewal_date; ?></p>	


<?php require "application/views/admin/plan/includes/account_modules.php"; ?>
	