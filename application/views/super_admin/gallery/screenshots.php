
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>


<div class="new-item">
	<button class="btn btn-default btn-sm button-adjust" data-toggle="collapse" data-target="#upload_screenshot" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-upload"></i> Upload New</button>
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('gallery/published_screenshots'); ?>"><i class="fa fa-image"></i> Published Screenshots</a>
</div>	


<div class="collapse m-b-50" id="upload_screenshot">
  	<div class="card card-body">
			
		<?php
		$form_attributes = array(
			"class" => "dropzone dropzone_form", 
			"id" 	=> "upload_screenshot_form"
		);
		echo form_open('gallery/upload_screenshot_ajax', $form_attributes); ?>

			<div class="dz-message" data-dz-message>
				<p>Click or drop files here to upload</p>
				<p><small>(Only image files allowed. Max size allowed is 1MB for each file)</small></p>
			</div>

		<?php echo form_close(); ?>

		<div class="text-center m-t-20">
			<a class="btn btn-primary" href="<?php echo base_url('gallery/update_screenshot_gallery'); ?>" title="Update screenshot gallery">Update Gallery</a>
		</div>

  	</div>
</div>



<div class="m-b-30">
	<p><i class="fa fa-eye text-success"></i> Published: <?php echo number_format($total_published); ?></p>
	<p><i class="fa fa-eye-slash text-primary"></i> Unpublished (Drafts): <?php echo number_format($total_unpublished); ?></p>
	<p><i class="fa fa-th-large"></i> All: <?php echo number_format($total_records); ?></p>

	<p>Note: Maximum of 30 published screenshots will be shown on the website. Default screenshots will be displayed on website if no published screenshot exists. </p>

</div>

			

<?php
if ( $total_records > 0 ) { ?>

	<?php 
	//select options bulk actions 
	$options_array = array(
		//'value' => 'Caption'
		'publish' => 'Publish',
		'unpublish' => 'Unpublish',
		'delete' => 'Delete'
	); 
	echo modal_bulk_actions_alt('gallery/bulk_actions_screenshots', $options_array); ?>

		<div class="row">

			<?php
			foreach ($screenshots as $p) { ?>

				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
				    <div class="thumbnail">
				      	<div class="image view view-first">
				        	<img style="width: 100%; display: block;" src="<?php echo base_url('assets/uploads/screenshots/'.$p->screenshot); ?>" alt="" />
					        <div class="mask">
					        	<p>Gallery</p>
					          	<div class="tools tools-bottom">
						            <a type="button" href="<?php echo base_url('assets/uploads/screenshots/'.$p->screenshot); ?>" title="View full image" data-lightbox="screenshots"><i class="fa fa-search-plus"></i></a>
						            <a type="button" href="<?php echo base_url('gallery/delete_screenshot/'.$p->id); ?>" title="Delete screenshot"><i class="fa fa-trash"></i></a>
					          	</div>
					        </div>
					    </div>
				      	<div class="text-center p-t-10">
							<div><?php echo checkbox_bulk_action($p->id); ?></div>
							<div>

								<a class="btn btn-primary btn-xs" type="button" href="<?php echo base_url('assets/uploads/screenshots/'.$p->screenshot); ?>" title="View full image" data-lightbox="screenshots">View</a>

								<?php if ($p->published == 'true') { ?>

									<a class="btn btn-warning btn-xs" type="button" href="<?php echo base_url('gallery/unpublish_screenshot/'.$p->id); ?>" title="Unpublish screenshot">Unpublish</a>

								<?php } else { ?>

									<a class="btn btn-success btn-xs" type="button" href="<?php echo base_url('gallery/publish_screenshot/'.$p->id); ?>" title="Publish screenshot">Publish</a>

								<?php } ?>

								<a class="btn btn-danger btn-xs" type="button" href="<?php echo base_url('gallery/delete_screenshot/'.$p->id); ?>" title="Delete screenshot">Delete</a>

							</div>
				      	</div>
				    </div>
				</div>

			<?php } //endforeach ?>

		</div>

	<?php echo form_close(); ?>


<?php } else { ?>

	<h3 class="text-danger">No screenshot to show.</h3>

<?php } //endif ?>


<!--Pagination Links-->
<?php echo pagination_links($links, 'pagination'); ?>
