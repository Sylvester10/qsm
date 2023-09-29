
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	
	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('class_teacher/attendance'); ?>"><i class="fa fa-refresh"></i> Mark Attendance</a>

		<h4 class="text-bold">Class Information</h4>
		<p>Class Population: <?php echo $this->common_model->get_class_population($y->id); ?></p>
		<p>Class Level: <?php echo $y->level; ?></p>
		<p>Class Teacher: <?php echo $this->common_model->get_class_teacher_name($y->id); ?></p>
	</div>
		


	<div class="table-scroll">
		<table id="single_class_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th> Actions </th>
					<th class="min-w-100"> Passport </th>
					<th class="min-w-150"> Admission ID </th>
					<th class="min-w-200"> Name </th>
					<th> Sex </th>
					<th class="min-w-200"> Attendance </th>
					<th> Suspended </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>