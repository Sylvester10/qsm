	
	<?php echo flash_message_success('status_msg'); ?>
	<?php echo flash_message_danger('status_msg_error'); ?>

	
	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions_alt('admin/delete_bulk_notifs', $options_array); ?>

	<table id="notifs_table" class="table table-no-border cell-text-middle" style="text-align: left">
		
		<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
		
		<thead>
			<tr>
				<th class="dt-hide-column"> The Notif </th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
		
	<?php echo form_close(); ?>
