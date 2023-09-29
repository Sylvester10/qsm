
<div class="new-item">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_staff/create_news'); ?>"><i class="fa fa-plus"></i> Create News</a>
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('publications_staff/news'); ?>"><i class="fa fa-book"></i> News List</a>
</div>

	<div class="row m-b-30">

		<div class="col-md-8">

			<?php $featured_image_path = base_url('assets/uploads/news/'.$y->featured_image); ?>

			<a href="<?php echo $featured_image_path; ?>" target="_blank">
				<img class="latest_news_featured_image img-responsive" src="<?php echo $featured_image_path; ?>" alt="<?php echo $y->title; ?>" title="<?php echo $y->title; ?>" />
			</a>
			
			<h3><?php echo $y->title; ?></h3>
			
			<small>Posted on: <?php echo x_date($y->date); ?></small> <br />

			<p><?php echo $y->body; ?></p>

		</div>

	</div>
	