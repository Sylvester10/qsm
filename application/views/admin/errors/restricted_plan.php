<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>



<h3 class="text-danger">You tried to access a feature/module your current plan does not cover!</h3>

<div>
	To unlock this feature and more, upgrade your account.
	<br />
	<a class="btn btn-success btn-lg" href="<?php echo base_url('upgrade'); ?>">Upgrade Now</a>
</div>


<h3>Current Plan: <?php echo school_plan; ?></h3>

<?php require "application/views/admin/plan/includes/account_modules.php"; ?>

<p class="m-t-20">If you think you shouldn't be getting this error, contact the chief administrator of your school account, <?php echo chief_admin_name; ?> (<?php echo chief_admin_phone; ?>), or contact <?php echo software_team; ?>.</p>
