	
	<?php echo flash_message_success('status_msg'); ?>
	<?php echo flash_message_danger('status_msg_error'); ?>

	<?php require "application/views/super_admin/testimonials/modals/new_testimonial.php"; ?>

	<div class="new-item">
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_testimonial"><i class="fa fa-plus"></i> New Testimonial</button>
	</div>


	<div class="m-b-30">
		<p><i class="fa fa-eye text-success"></i> Published: <?php echo number_format($total_published); ?></p>
		<p><i class="fa fa-eye-slash text-primary"></i> Unpublished (Drafts): <?php echo number_format($total_unpublished); ?></p>
		<p><i class="fa fa-th-large"></i> All: <?php echo number_format($total_records); ?></p>
		<p>Note: Maximum of 12 published testimonials will be shown on the website.</p>
	</div>


	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'publish' => 'Publish',
		'unpublish' => 'Unpublish',
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions_alt('testimonial/bulk_actions_testimonials', $options_array); ?>
		
		<div class="table-scroll">
			<table id="testimonials_table" class="table table_single_column table-borderless table-striped cell-text-middle" style="text-align: left">
				
				<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
				
				<thead>
					<tr>
						<th class="dt-hide-column"> The Testimonials </th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		
	<?php echo form_close(); ?>
