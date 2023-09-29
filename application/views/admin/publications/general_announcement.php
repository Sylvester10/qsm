
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	
	<?php 
	//check if general announcement has been created for this school
	if ( ! $general_announcement ) { //has not been created ?> 

		<?php require("application/views/admin/publications/modals/create_general_announcement.php");  ?>

		<div class="new-item">
			<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#create_general_announcement"><i class="fa fa-pencil"></i> Create Announcement</button>
		</div>

		<h3>No announcement found! Click the button above to create one now.</h3>

	<?php } else { //has been created ?>

		<?php require("application/views/admin/publications/modals/update_general_announcement.php");  ?>


		<?php 
		//delete confirm
		$delete_confirm = modal_delete_confirm($general_announcement->id, 'General Announcement', 'announcement', 'publications_admin/delete_general_announcement');
		echo $delete_confirm;

		$published = $general_announcement->published;
		if ($published == 'true') {
			$status = '<b class="text-success">Published</b>';
			$action = 'unpublish_general_announcement';
			$button_text = 'Unpublish';
			$icon = 'fa fa-eye-slash';
		} else {
			$status = '<b class="text-danger">Unpublished</b>';
			$action = 'publish_general_announcement';
			$button_text = 'Publish';
			$icon = 'fa fa-eye';
		} ?>

		<div class="new-item">

			<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#update_general_announcement"><i class="fa fa-pencil"></i> Update</button>

			<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_admin/'.$action); ?>"><i class="<?php echo $icon; ?>"></i> <?php echo $button_text; ?></a>

			<button type="button" class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#delete<?php echo $general_announcement->id; ?>"><i class="fa fa-trash text-danger"></i> Delete</button>

		</div>

		<p><h4 class="f-s-22"><?php echo $general_announcement->announcement; ?></h4></p>
		<p><small>Last updated: <?php echo time_ago($general_announcement->date); ?></small></p>
		<p><small>Status: <?php echo $status; ?></small></p>

	<?php } ?>


	<p class="m-t-30">General announcement will be broadcasted to all stakeholders of the school (staff, students and parents).</p>


