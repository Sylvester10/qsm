
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<div class="row">
	<div class="col-md-12">

		<div class="new-item">
			<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_staff/create_newsletter'); ?>"><i class="fa fa-plus"></i> Create Newsletter</a>
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
		echo modal_bulk_actions_alt('publications_staff/bulk_actions_newsletters', $options_array); ?>


			<?php
			if ($total_records > 0) { 

				foreach ($newsletters as $y) { 

					$newsletter_download_path = base_url('assets/uploads/newsletters/'.$y->the_file); 

					$delete_confirm = modal_delete_confirm($y->id, $y->title, 'newsletter', 'publications_staff/delete_newsletter');
					echo $delete_confirm;

					if ($y->published == 'true') {
						$status = '<b class="text-success">Published</b>';
						$action = 'unpublish_newsletter/'.$y->id;
						$button_text = 'Unpublish';
						$icon = 'fa fa-eye-slash';
					} else {
						$status = '<b class="text-danger">Unpublished</b>';
						$action = 'publish_newsletter/'.$y->id;
						$button_text = 'Publish';
						$icon = 'fa fa-eye';
					} ?>


					<div class="row m-b-40">

						<div class="col-md-1">
							<?php echo checkbox_bulk_action($y->id); ?>
						</div>

						<div class="col-md-8">

							<h3><img class="pdf-icon" src="<?php echo pdf_icon; ?>" />

							<?php echo $y->title; ?></h3>

							<small>Posted on: <?php echo x_date($y->date); ?></small> <br />

							<small>Status: <?php echo $status; ?></small> <br />


							<div class="m-t-15">

								<a class="btn btn-primary" href="<?php echo $newsletter_download_path; ?>" target="_blank"><i class="fa fa-download"></i> View/Download</a>

								<a type="button" class="btn btn-primary" href="<?php echo base_url('publications_staff/'.$action); ?>"><i class="<?php echo $icon; ?>"></i> <?php echo $button_text; ?></a>

								<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"><i class="fa fa-trash"></i> Delete</button>
							
							</div>

						</div>

					</div>

				<?php } 
				
			} else { ?>

				<h3 class="text-danger">No newsletter to show.</h3>

			<?php } ?>
			
				
			<!--Pagination Links-->
			<ul class="pagination">
				<?php foreach ($links as $link) {
					echo '<li>' . $link . '</li>';
				} ?>
			</ul>
			

		<?php echo form_close(); ?>


	</div>
</div>
	