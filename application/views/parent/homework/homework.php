

	<?php
	if ($total_records > 0) { 
		foreach ($homework as $y) {
			$subject = $this->common_model->get_subject_details($y->subject_id)->subject;
	 ?>
			
			<div class="row homework_list">

				<div class="col-md-11">
					<div>
						<div>Class: <?php echo $class; ?></div>
						<div>Subject: <?php echo $subject; ?></div>
						<div>Date Given: <?php echo x_date($y->date_added); ?></div>
						<div>Due Date: <?php echo x_date($y->submission_date); ?></div>
						<div>Has Material: <?php echo ($y->material != NULL) ? 'Yes' : 'No'; ?></div>
						<div class="text-bold m-t-20">Homework (snippet)</div> 
						<div><?php echo $y->snippet; ?></div>
					</div>

					<div class="m-t-20">
						<a type="button" class="btn btn-primary" href="<?php echo base_url('homework_parent/view_homework/'.$y->id); ?>" title="View homework"><i class="fa fa-eye"></i> View Homework</a>

						<?php if ($y->material != NULL) { ?>
							<a type="button" class="btn btn-primary" href="<?php echo base_url('assets/uploads/homework/'.$y->material); ?>" target="_blank"><i class="fa fa-download"></i> Download Material</a>
						<?php } ?>
						
					</div>
				</div>

			</div>

		<?php } 
		
	} else { ?>

		<h3 class="text-danger">No homework to show.</h3>

	<?php } ?>
	
		
	<!--Pagination Links-->
	<ul class="pagination">
		<?php foreach ($links as $link) {
			echo '<li>' . $link . '</li>';
		} ?>
	</ul>