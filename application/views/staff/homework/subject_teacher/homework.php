
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<div class="new-item m-b-30">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('homework_subject_teacher/new_homework/'.$class_id); ?>" ><i class="fa fa-plus"></i> New Homework</a>
</div>


<?php
	if ($total_records > 0) {  
		//select options bulk actions 
		$options_array = array(
			//'value' => 'Caption'
			'delete' => 'Delete'
		); 
		echo modal_bulk_actions_alt('homework_subject_teacher/bulk_actions_homework', $options_array); 
	} ?>


	<?php
	if ($total_records > 0) { 

		foreach ($homework as $y) {
			$subject = $this->common_model->get_subject_details($y->subject_id)->subject; 
			//delete confirm modal
			echo modal_delete_confirm($y->id, $subject, 'homework', 'homework_subject_teacher/delete_homework'); ?>
			
			<div class="row homework_list">
				<div class="col-md-1">
					<?php echo checkbox_bulk_action($y->id); ?>
				</div>
				<div class="col-md-11">
					<div>
						<div>Subject: <?php echo $subject; ?></div>
						<div>Date Given: <?php echo x_date($y->date_added); ?></div>
						<div>Due Date: <?php echo x_date($y->submission_date); ?></div>
						<div>Has Material: <?php echo ($y->material != NULL) ? 'Yes' : 'No'; ?></div>
						<div class="text-bold m-t-20">Homework (snippet)</div> 
						<div><?php echo $y->snippet; ?></div>
					</div>

					<div class="m-t-20">
						<a type="button" class="btn btn-primary" href="<?php echo base_url('homework_subject_teacher/view_homework/'.$y->id); ?>" title="View homework"><i class="fa fa-eye"></i></a>

						<?php if ($y->material != NULL) { ?>
							<a type="button" class="btn btn-primary" href="<?php echo base_url('assets/uploads/homework/'.$y->material); ?>" target="_blank" title="Download material"><i class="fa fa-download"></i></a>
						<?php } ?>

						<a type="button" class="btn btn-primary" href="<?php echo base_url('homework_subject_teacher/edit_homework/'.$y->id); ?>" title="Edit homework"><i class="fa fa-pencil"></i></a>
						<a type="button" href="#" class="btn btn-danger" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>" title="Delete homework"><i class="fa fa-trash"></i></a>
					</div>
					
				</div>
			</div>

		<?php } 
		
	} else { ?>

		<h3 class="text-danger">No homework to show.</h3>
		
	<?php } ?>
	
		
	<!--Pagination Links-->
	<ul class="pagination">
		<?php foreach ($links as $link) {
			echo '<li>' . $link . '</li>';
		} ?>
	</ul>

<?php echo form_close(); //bulk action form ?>