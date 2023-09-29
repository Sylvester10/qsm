
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<div class="new-item">
    <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('parents/new_parent'); ?>"><i class="fa fa-users"></i> New Parent</a>
</div>

	
<?php 
//select options bulk actions 
$options_array = array(
	//'value' => 'Caption'
	'delete' => 'Delete'
); 
echo modal_bulk_actions('parents/bulk_actions_parents', $options_array); ?>

	<table id="all_parents_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
		
		<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
		
		<thead>
			<tr>
				<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
				<th> Actions </th>
				<th class="min-w-200"> Name </th>
				<th class="min-w-150"> No. of Child(ren) / Ward(s) </th>
				<th class="min-w-200"> Child(ren) / Ward(s) </th>
				<th class="min-w-100"> Sex </th>
				<th class="min-w-250"> Relationship to Child(ren) </th>
				<th class="min-w-100"> Phone No. </th>
				<th class="min-w-150"> Email Address </th>
				<th class="min-w-250"> 2nd Parent Name </th>
				<th class="min-w-100"> 2nd Parent Sex </th>
				<th class="min-w-150"> 2nd Parent Relationship to Child(ren) </th>
				<th class="min-w-100"> 2nd Parent Phone No. </th>
				<th class="min-w-100"> 2nd Parent Email Address </th>
				<th class="min-w-150"> Date Registered </th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

<?php echo form_close(); ?>
