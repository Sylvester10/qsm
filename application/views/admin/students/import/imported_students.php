
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>


<?php 
if ($total_imported_students == 0) { ?>

	<h3 class="text-info">No outstanding imports</h3>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('student_import/import_students'); ?>"><i class="fa fa-plus"></i> Import New</a>
	</div>

<?php } else { ?>

	<h3 class="text-bold">Step 2 (Final): Complete Import</h3>

	<h3 class="m-t-30">Field Validation Check</h3>
	<p>Total Students Imported: <?php echo $total_imported_students; ?></p>

	<?php 
	if ($total_imported_students > 0) { ?>

		<p>Admission ID:<?php echo $this->student_import_model->required_field_title_message('admission_id', 'Admission ID'); ?></p>

		<p>Admission ID No Duplicate: <?php echo $this->student_import_model->admission_id_no_duplicate_message(); ?></p>
		
		<p>Admission ID No Conflict: <?php echo $this->student_import_model->admission_id_no_conflict_message(); ?></p>

		<p>Last Name: <?php echo $this->student_import_model->required_field_title_message('last_name', 'Last Name'); ?></p>

		<p>First Name: <?php echo $this->student_import_model->required_field_title_message('first_name', 'First Name'); ?></p>

		<p>Class ID: <?php echo $this->student_import_model->required_field_title_message('class_id', 'Class ID'); ?></p>
		
		<p>Matched Classes: <?php echo $this->student_import_model->valid_class_id_title_message(); ?></p>
	
		<p>Sex: <?php echo $this->student_import_model->required_field_title_message('sex', 'Sex'); ?></p>
		
		<p>Date of Birth: <?php echo $this->student_import_model->required_field_title_message('date_of_birth', 'Date of Birth'); ?></p>

		<p>Nationality: <?php echo $this->student_import_model->required_field_title_message('nationality', 'Nationality'); ?></p>

		<p>First Parent/Guardian Name: <?php echo $this->student_import_model->required_field_title_message('parent_name', 'First Parent Name'); ?></p>

		<p>First Parent Email No Conflict: <?php echo $this->student_import_model->parent_email_no_conflict_message(); ?></p>

	<?php } ?>

	
	<?php 
	//show action buttons if at least 1 student's result exists
	if ($total_imported_students > 0) { ?>

		<div class="row m-t-20 m-b-30">
			<div class="col-md-12">

				<h3>Note</h3>
				<ul class="adjust-list-left">
					<li>The imported students will not appear on your school's student list until you complete import.</li>
					<li>You are advised to review the imported data page by page before completing the import process.</li>
					<li>You may edit or delete a single record before completing import.</li>
					<li>You can only complete import if all validation rules are met.</li>
					<li>All class IDs must be validated. All valid class IDs will return their corresponding class name in the <b>Class Matched</b> column below. If this value is empty against any student's record, assign class to the student using the edit feature.</li>
					<li>If you are not OK with the imported records, you may cancel the process and retry the import.</li>
				</ul>

				<?php 
				echo modal_confirm_action('complete_import', 'Complete Import', 'Sure to continue?', 'student_import/complete_import'); 		
				echo modal_confirm_action('cancel_import', 'Cancel Import', 'Sure to continue?', 'student_import/cancel_import'); ?>

				<button class="btn btn-success btn-lg" data-toggle="modal" data-target="#complete_import" title="Complete import of students">Complete Import</button>

				<button class="btn btn-danger btn-lg" data-toggle="modal" data-target="#cancel_import" title="Cancel import action and clear imported students">Cancel Import</button>

			</div><!-- /.col-md-8 -->
		</div><!-- /.row -->

	<?php } ?>


<?php } ?>


	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('student_import/bulk_actions_students', $options_array); ?>

		<table id="imported_students_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th class="min-w-150"> Admission ID </th>
					<th class="min-w-100"> Last Name </th>
					<th class="min-w-100"> First Name </th>
					<th class="min-w-100"> Other Name </th>
					<th> Class ID </th>
					<th> Class Matched </th>
					<th> Sex </th>
					<th> Date of Birth </th>
					<th> Blood Group </th>
					<th class="min-w-150"> Place of Birth </th>
					<th> Nationality </th>
					<th class="min-w-100"> State of Origin </th>
					<th> LGA of Origin </th>
					<th class="min-w-100"> Home Town </th>
					<th class="min-w-200"> Home Address </th>
					<th> Religion </th>
					<th class="min-w-150"> Former School </th>
					<th class="min-w-200"> Former School Address </th>
					<th> Class in Former School </th>
					<th class="min-w-150"> First Parent Name </th>
					<th class="min-w-100"> First Parent Sex </th>
					<th class="min-w-100"> First Parent R/ship </th>
					<th class="min-w-100"> First Parent Phone No. </th>
					<th class="min-w-200"> First Parent Email </th>
					<th class="min-w-150"> Second Parent Name </th>
					<th class="min-w-100"> Second Parent Sex </th>
					<th class="min-w-100"> Second Parent R/ship </th>
					<th class="min-w-100"> Second Parent Phone No. </th>
					<th class="min-w-150"> Second Parent Email </th>
					<th class="min-w-150"> Admission Date </th>		
					<th class="min-w-150"> Additional Information </th>	
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	
	<?php echo form_close(); ?>
