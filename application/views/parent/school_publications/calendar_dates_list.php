
<div class="new-item">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('school_publications_parent/calendar_dates'); ?>"><i class="fa fa-calendar"></i> Calendar Grid</a>
</div>



	<?php
	if ($total_records > 0) { 

		foreach ($calendar_list as $y) { ?>

			<div class="row calendar_list_item m-b-30">
				<div class="col-md-2">
					<div class="calendar_list_date">
						<h2><?php echo strtoupper(get_month_value_short($y->month)); ?> <?php echo $y->day; ?></h2>
						<p><?php echo $y->year; ?></p>
					</div>
				</div>
				<div class="col-md-10">
					<div class="calendar_list_content">
						<h3><?php echo $y->caption; ?></h3>
						<p><?php echo $y->description; ?></p>
					</div>
				</div>
			</div>
		<?php } 
		
	} else { ?>

		<h3 class="text-danger">No event to show.</h3>
		
	<?php } ?>
	
		
	<!--Pagination Links-->
	<ul class="pagination">
		<?php foreach ($links as $link) {
			echo '<li>' . $link . '</li>';
		} ?>
	</ul>