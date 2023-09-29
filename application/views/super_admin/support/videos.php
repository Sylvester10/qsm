
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<?php require "application/views/super_admin/support/modals/add_video.php";  ?>

	<div class="new-item">
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_video"><i class="fa fa-plus"></i> New Video</button>
	</div>

	
	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('support/bulk_actions_videos', $options_array); ?>
	
	<div class="table-scroll">
		<table id="support_videos_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th class="min-w-200"> Title </th>
					<th class="min-w-350"> Description </th>
					<th class="min-w-250"> Video URL </th>
					<th class=""> Category </th>
					<th class="min-w-150"> Date Added </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	
<?php echo form_close(); ?>