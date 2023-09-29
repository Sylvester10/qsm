
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<div class="row">
	<div class="col-md-12">

		<div class="new-item">
			<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_staff/create_news'); ?>"><i class="fa fa-plus"></i> Create News</a>
		</div>


		<div class="">
			<p><i class="fa fa-eye text-success"></i> Published: <?php echo number_format($total_published); ?></p>
			<p><i class="fa fa-eye-slash text-primary"></i> Unpublished (Drafts): <?php echo number_format($total_unpublished); ?></p>
			<p><i class="fa fa-th-large"></i> All: <?php echo number_format($total_records); ?></p>
		</div>

		
		<?php
		if ($total_records > 0) { ?>

			<?php 
			//select options bulk actions 
			$options_array = array(
				//'value' => 'Caption'
				'publish' => 'Publish',
				'unpublish' => 'Unpublish',
				'delete' => 'Delete'
			); 
			echo modal_bulk_actions_alt('publications_staff/bulk_actions_news', $options_array); ?>
		

			<?php 
			foreach ($news as $y) { ?>

				<?php
				$featured_image_path = base_url('assets/uploads/news/'.$y->featured_image);
				$more_link = base_url('publications_staff/single_news/' . $y->id .'/'. $y->slug);

				$delete_confirm = modal_delete_confirm($y->id, $y->title, 'news article', 'publications_staff/delete_news');
				echo $delete_confirm;

				if ($y->published == 'true') {
					$status = '<b class="text-success">Published</b>';
					$action = 'unpublish_news/'.$y->id;
					$button_text = 'Unpublish';
					$icon = 'fa fa-eye-slash';
				} else {
					$status = '<b class="text-danger">Unpublished</b>';
					$action = 'publish_news/'.$y->id;
					$button_text = 'Publish';
					$icon = 'fa fa-eye';
				} ?>

				<div class="row m-b-40">

					<div class="col-md-1">
						<?php echo checkbox_bulk_action($y->id); ?>
					</div>

					<div class="col-md-8">

						<a href="<?php echo $featured_image_path; ?>" target="_blank">
							<img class="latest_news_featured_image img-responsive" src="<?php echo $featured_image_path; ?>" alt="<?php echo $y->title; ?>" title="<?php echo $y->title; ?>" />
						</a>

						<h3><a href="<?php echo $more_link; ?>"><?php echo $y->title; ?></a></h3>

						<small>Posted on: <?php echo x_date($y->date); ?></small> <br />

						<small>Status: <?php echo $status; ?></small> <br />

						<p><?php echo $y->snippet; ?></p>

						<a href="<?php echo $more_link; ?>">Continue Reading &raquo; </a>

						<div class="m-t-15">

							<a type="button" class="btn btn-primary" href="<?php echo base_url('publications_staff/edit_news/'.$y->id); ?>"><i class="fa fa-pencil"></i> Edit</a>

							<a type="button" class="btn btn-primary" href="<?php echo base_url('publications_staff/'.$action); ?>"><i class="<?php echo $icon; ?>"></i> <?php echo $button_text; ?></a>

							<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"><i class="fa fa-trash"></i> Delete</button>
						
						</div>

					</div>

				</div>


			<?php } 
			
		} else { ?>

			<h3 class="text-danger">No news article to show.</h3>

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
	