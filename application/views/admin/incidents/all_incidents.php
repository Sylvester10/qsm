
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


	<p>Quick Note: To record new incident, go to <a class="text-bold" href="<?php echo base_url('students_admin/students'); ?>" target="_blank">students page</a> and select <b>Record Incident</b> against desired student. The same can also be achieved using the <b>Search Student</b> button above.</p>


	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('incidents/bulk_actions_incidents', $options_array); ?>
	
	<table id="incidents_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
		
		<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
		
		<thead>
			<tr>
				<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
				<th> Actions </th>
				<th class="min-w-150"> Admission ID </th>
				<th class="min-w-150"> Student Name </th>
				<th class="min-w-150"> Student Class <small>(when recored)</small> </th>
				<th class="min-w-200"> Caption </th>
				<th class="min-w-150"> Date of Incident </th>
				<th class="min-w-150"> Session </th>
				<th class="min-w-150"> Term </th>
				<th class="min-w-150"> Evidence Count </th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
	
<?php echo form_close(); ?>