
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<div class="row">

		<?php
		if ($total_records > 0) { 

			foreach ($newsletters as $y) { 

				$newsletter_download_path = base_url('assets/uploads/newsletters/'.$y->the_file); ?>

					<div class="col-md-8 m-b-40">

						<h3><img class="pdf-icon" src="<?php echo pdf_icon; ?>" />

						<?php echo $y->title; ?></h3>

						<small>Posted on: <?php echo x_date($y->date); ?></small> <br />

						<div class="m-t-15">
							<a class="btn btn-primary" href="<?php echo $newsletter_download_path; ?>" target="_blank"><i class="fa fa-download"></i> View/Download</a>
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

</div>
	