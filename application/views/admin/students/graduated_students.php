
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('students_admin/student_registration'); ?>"><i class="fa fa-plus"></i> New Student</a>
	</div>

	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('students_admin/bulk_actions_students', $options_array); ?>
	
	<div class="table-scroll">
		<table id="graduated_students_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th class="min-w-100"> Passport </th>
					<th class="min-w-150"> Registration ID </th>
					<th class="min-w-150"> Admission ID </th>
					<th class="min-w-200"> Name </th>
					<th class="min-w-150"> Graduated From </th>
					<th class="min-w-150"> Date Graduated </th>
					<th class="min-w-200"> Bio Information </th>
					<th class="min-w-100"> Password Reset Code </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	
<?php echo form_close(); ?>