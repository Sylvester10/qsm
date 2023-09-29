
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	
	<div class="new-item m-b-20">

		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('incidents/new_incident/'.$student_id); ?>"><i class="fa fa-plus"></i> New Incident</a>
		
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('incidents'); ?>"><i class="fa fa-history"></i> All Incidents</a>

	</div>


	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('incidents/bulk_actions_incidents', $options_array); ?>
	
	<table id="student_incidents_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
		
		<input type="hidden" id="student_id" value="<?php echo $student_id; ?>" />
		<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
		
		<thead>
			<tr>
				<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
				<th> Actions </th>
				<th class="min-w-150"> Student Class <small>(when recorded)</small></th>
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