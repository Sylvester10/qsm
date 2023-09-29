
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>


<?php 
if ($total_imported_staff == 0) { ?>

	<h3 class="text-info">No outstanding imports</h3>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('staff_import/import_staff'); ?>"><i class="fa fa-plus"></i> Import New</a>
	</div>

<?php } else { ?>

	<h3 class="text-bold">Step 2 (Final): Complete Import</h3>

	<h3 class="m-t-30">Field Validation Check</h3>
	<p>Total staff Imported: <?php echo $total_imported_staff; ?></p>

	<?php 
	if ($total_imported_staff > 0) { ?>

		<p>Name: <?php echo $this->staff_import_model->required_field_title_message('name', 'Name'); ?></p>

		<p>Email:<?php echo $this->staff_import_model->required_field_title_message('email', 'Email'); ?></p>

		<p>Email No Duplicate: <?php echo $this->staff_import_model->email_no_duplicate_message(); ?></p>

		<p>Email No Conflict: <?php echo $this->staff_import_model->email_no_conflict_message(); ?></p>
		
		<p>Mobile No.: <?php echo $this->staff_import_model->required_field_title_message('phone', 'Mobile No.'); ?></p>
		
		<p>Designation: <?php echo $this->staff_import_model->required_field_title_message('designation', 'Designation'); ?></p>

		<p>Date of Birth: <?php echo $this->staff_import_model->required_field_title_message('date_of_birth', 'Date of Birth'); ?></p>

		<p>Sex: <?php echo $this->staff_import_model->required_field_title_message('sex', 'Sex'); ?></p>

	<?php } ?>

	
	<?php 
	//show action buttons if at least 1 staff's result exists
	if ($total_imported_staff > 0) { ?>

		<div class="row m-t-20 m-b-30">
			<div class="col-md-12">

				<h3>Note</h3>
				<ul class="adjust-list-left">
					<li>The imported staff will not appear on your school's staff list until you complete import.</li>
					<li>You are advised to review the imported data page by page before completing the import process.</li>
					<li>You may edit or delete a single record before completing import.</li>
					<li>You can only complete import if all validation rules are met.</li>
					<li>If you are not OK with the imported records, you may cancel the process and retry the import.</li>
				</ul>

				<?php 
				echo modal_confirm_action('complete_import', 'Complete Import', 'Sure to continue?', 'staff_import/complete_import'); 		
				echo modal_confirm_action('cancel_import', 'Cancel Import', 'Sure to continue?', 'staff_import/cancel_import'); ?>

				<button class="btn btn-success btn-lg" data-toggle="modal" data-target="#complete_import" title="Complete import of staff">Complete Import</button>

				<button class="btn btn-danger btn-lg" data-toggle="modal" data-target="#cancel_import" title="Cancel import action and clear imported staff">Cancel Import</button>

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
	echo modal_bulk_actions('staff_import/bulk_actions_staff', $options_array); ?>

		<table id="imported_staff_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th> Title </th>
					<th class="min-w-150"> Name </th>
					<th class="min-w-200"> Email </th>
					<th> Mobile No </th>
					<th class="min-w-150"> Nationality </th>
					<th class="min-w-150"> State of Origin </th>
					<th class="min-w-150"> LGA of Origin </th>
					<th class="min-w-100"> Designation </th>
					<th> Sex </th>
					<th> Date of Birth </th>
					<th class="min-w-200"> Home Address </th>
					<th class="min-w-100"> Religion </th>
					<th> Qualification </th>
					<th class="min-w-100"> Employment Date </th>
					<th class="min-w-150"> Next of Kin </th>
					<th class="min-w-150"> Next of Kin Email </th>
					<th class="min-w-150"> Next of Kin Mobile No. </th>	
					<th class="min-w-200"> Next of Kin Home Address </th>	
					<th class="min-w-100"> Account No. </th>	
					<th class="min-w-100"> Bank Name </th>	
					<th class="min-w-250"> Additional Information </th>	
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	
	<?php echo form_close(); ?>
