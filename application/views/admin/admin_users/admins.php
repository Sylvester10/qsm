
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('admin_users/new_admin'); ?>"><i class="fa fa-plus"></i> New Admin</a>
	</div>
	
	<p class="text-bold">Quick Note</p>
	<p>Level 1 (Chief Admin): Can manage all modules in the application. Suitable for school directors/proprietors/proprietresses.</p>
	<p>Level 2 (Staff/Surrogate Admin): Can manage all modules except Admins, Procurement Requisition, and Weekly Reports. Suitable for school administrative staff (Principals, Head Teachers) or staff delegated to deputize for the directors/proprietors/proprietresses.</p>
	<p>Newly added admins must set their password <a href="<?php echo base_url('admin_acc/set_password'); ?>" target="_blank" class="text-success text-bold">here</a> before they can access their admin account.</p>
	
	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('admin_users/delete_bulk_admins', $options_array); ?>
	
	<div class="table-scroll">
		<table id="admins_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th class="min-w-200"> Name </th>
					<th class="min-w-200"> Email Address </th>
					<th class="min-w-150"> Phone </th>					
					<th class="min-w-150"> Designation </th>
					<th class="min-w-200"> Section(s) Assigned </th>
					<th class="min-w-150"> Level </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	
<?php echo form_close(); ?>