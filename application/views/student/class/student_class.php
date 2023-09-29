
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<h4 class="text-bold">Class Information</h4>
	<p>Class Population: <?php echo $this->common_model->get_class_population($y->id); ?></p>
	<p>Class Level: <?php echo $y->level; ?></p>
	<p>Class Teacher: <?php echo $this->common_model->get_class_teacher_name($y->id); ?></p>

	
	<div class="row m-t-30">
		<div class="col-md-8">
			<table id="student_class_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
				
				<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				
				<thead>
					<tr>
						<th class="w-5"> S/N </th>
						<th class="w-20"> Passport </th>
						<th class="w-75"> Name </th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>

