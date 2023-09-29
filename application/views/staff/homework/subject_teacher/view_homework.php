
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<div class="new-item m-b-20">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('homework_subject_teacher/edit_homework/'.$y->id); ?>"><i class="fa fa-pencil"></i> Edit Homework</a>
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('homework_subject_teacher/homework/'.$class_id); ?>"><i class="fa fa-tasks"></i> All Homework</a>
</div>


<div>
	<div>Class: <?php echo $class; ?></div>
	<div>Subject: <?php echo $subject; ?></div>
	<div>Date Given: <?php echo x_date($y->date_added); ?></div>
	<div>Due Date: <?php echo x_date($y->submission_date); ?></div>
	<div>Has Material: <?php echo ($y->material != NULL) ? 'Yes' : 'No'; ?></div>

	<h3 class="m-t-20 text-bold">Homework</h3> 
	<div class="the_homework">

		<?php echo $y->the_homework; ?>

		<?php if ($y->material != NULL) { ?>
			<div class="m-t-20">
				<a type="button" class="btn btn-primary" href="<?php echo base_url('assets/uploads/homework/'.$y->material); ?>" target="_blank"><i class="fa fa-download"></i> Download Material</a>
			</div>
		<?php } ?>

	</div>
	
	<h3 class="m-t-20 text-bold">Additional Message</h3> 
	<div><?php echo $y->additional_message; ?></div>

</div>

