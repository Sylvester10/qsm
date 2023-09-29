
<div class="row">
	
	<?php
	if ($total_records > 0) { 

		foreach ($news as $y) { 

			$featured_image_path = base_url('assets/uploads/news/'.$y->featured_image);
			$more_link = base_url('school_publications_staff/single_news/' . $y->id .'/'. $y->slug); ?>

			<div class="col-md-8 m-b-40">

				<a href="<?php echo $featured_image_path; ?>" target="_blank">
					<img class="latest_news_featured_image img-responsive" src="<?php echo $featured_image_path; ?>" alt="<?php echo $y->title; ?>" title="<?php echo $y->title; ?>" />
				</a>

				<h3><a href="<?php echo $more_link; ?>"><?php echo $y->title; ?></a></h3>

				<small>Posted on: <?php echo x_date($y->date); ?></small> <br />

				<p><?php echo $y->snippet; ?></p>

				<a href="<?php echo $more_link; ?>">Continue Reading &raquo; </a>

			</div>

		<?php } 
		
	} else { ?>

		<h3 class="text-danger">No article to show.</h3>

	<?php } ?>

		
	<!--Pagination Links-->
	<ul class="pagination">
		<?php foreach ($links as $link) {
			echo '<li>' . $link . '</li>';
		} ?>
	</ul>

</div>