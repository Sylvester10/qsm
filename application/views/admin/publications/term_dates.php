
<?php require "application/views/admin/publications/modals/term_date_actions.php";  ?>
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="new-item">
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#create_term_date"><i class="fa fa-plus"></i> New Term Date</button>
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#clear_term_dates"><i class="fa fa-trash"></i> Clear All</button>
	</div>


	<div class="">
		<p><i class="fa fa-eye text-success"></i> Published: <?php echo number_format($total_published); ?></p>
		<p><i class="fa fa-eye-slash text-primary"></i> Unpublished (Drafts): <?php echo number_format($total_unpublished); ?></p>
		<p><i class="fa fa-th-large"></i> All: <?php echo number_format($total_records); ?></p>
	</div>

	
	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'publish' => 'Publish',
		'unpublish' => 'Unpublish',
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions('publications_admin/bulk_actions_term_dates', $options_array); ?>

		<table id="term_dates_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
		
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />	
			<thead>
				<tr>
					<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
					<th> Actions </th>
					<th class="min-w-200"> Activity </th>
					<th> Date </th>
					<th> Status </th>
				</tr>
			</thead>
			<tbody>	
			</tbody>
			
		</table>
		
	<?php echo form_close(); ?>
	