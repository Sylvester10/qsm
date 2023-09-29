
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	
	<div class="row">

		<?php 
		foreach ($children as $c) { 

			$child_name = $this->common_model->get_student_fullname($c->id); 
			$class_details = $this->common_model->get_class_details($c->class_id); 
			$class_teacher_id = $class_details->class_teacher_id; 
			//check if class is assigned to a teacher
			if ($class_teacher_id != NULL) {
				$class_teacher_details = $this->common_model->get_staff_details_by_id($class_teacher_id); 
				$class_teacher_email = $class_teacher_details->email; 
				$class_teacher_phone = $class_teacher_details->phone;
			} else {
				$class_teacher_email = NULL;
				$class_teacher_phone = NULL;
			} ?>

			<div class="col-md-6 col-sm-12 col-xs-12">

				<div class="x_panel">
					<div class="x_title">
						<h4 class="page_title text-bold f-s-20"><?php echo $child_name; ?></h4>
						<div class="clearfix"></div>
					</div>
					<div class="x_content">
						
						<h4 class="text-bold">Class Details</h4>
						<p>Class: <?php echo $class_details->class; ?></p> 
						<p>Level: <?php echo $class_details->level; ?></p>
						<p>Population: <?php echo $this->common_model->get_class_population($class_details->id); ?></p>
						<p>Class Teacher: <?php echo $this->common_model->get_class_teacher_name($class_details->id); ?></p>

					</div>
				</div>

			</div>

		<?php } ?>

	</div>