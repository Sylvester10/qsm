
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<?php
		//get current day
		$today = date('l'); 
		//set current day as active tab
		$active_mon = ($today == 'Monday') ? 'active' : null; 
		$active_tue = ($today == 'Tuesday') ? 'active' : null; 
		$active_wed = ($today == 'Wednesday') ? 'active' : null; 
		$active_thu = ($today == 'Thursday') ? 'active' : null; 
		$active_fri = ($today == 'Friday') ? 'active' : null; 
	?>

	<div class="panel with-nav-tabs panel-default">
		<div class="panel-heading">
			<ul class="nav nav-tabs">
				<li class="<?php echo $active_mon; ?>"><a href="#s_monday" data-toggle="tab">Monday</a></li>
				<li class="<?php echo $active_tue; ?>"><a href="#s_tuesday" data-toggle="tab">Tuesday</a></li>
				<li class="<?php echo $active_wed; ?>"><a href="#s_wednesday" data-toggle="tab">Wednesday</a></li>
				<li class="<?php echo $active_thu; ?>"><a href="#s_thursday" data-toggle="tab">Thursday</a></li>
				<li class="<?php echo $active_fri; ?>"><a href="#s_friday" data-toggle="tab">Friday</a></li>
			</ul>
		</div>
		<div class="panel-body">
			<div class="tab-content">
			
				<div class="tab-pane <?php echo $active_mon; ?>" id="s_monday">
					<h3>Monday</h3>	
					<div class="table-scroll">
						<table class="table table-bordered cell-text-middle" style="text-align: left">
							<thead>
								<tr>
									<th> Time </th>
									<th> Subject </th>
								</tr>
							</thead>
							<tbody>
							
								<?php 
								foreach ($monday_periods as $d) { 
								
									$period_type = $d->period_type;
									if ($period_type == 'Subject') {
										$subject = $this->common_model->get_subject_details($d->subject_id)->subject;
									} else {
										$subject = $d->activity;
									} ?>
									
									<tr>
										<td> <?php echo $d->start_time . ' - ' . $d->end_time; ?> </td>
										<td> <?php echo $subject; ?> </td>
									</tr>
									
								<?php } ?>
								
							</tbody>
						</table>
					</div>
				</div>



				<div class="tab-pane <?php echo $active_tue; ?>" id="s_tuesday">
					<h3>Tuesday</h3>	
					<div class="table-scroll">
						<table class="table table-bordered cell-text-middle" style="text-align: left">
							<thead>
								<tr>
									<th> Time </th>
									<th> Subject </th>
								</tr>
							</thead>
							<tbody>
							
								<?php 
								foreach ($tuesday_periods as $d) { 
								
									$period_type = $d->period_type;
									if ($period_type == 'Subject') {
										$subject = $this->common_model->get_subject_details($d->subject_id)->subject;
									} else {
										$subject = $d->activity;
									} ?>
									
									<tr>
										<td> <?php echo $d->start_time . ' - ' . $d->end_time; ?> </td>
										<td> <?php echo $subject; ?> </td>
									</tr>
									
								<?php } ?>
								
							</tbody>
						</table>
					</div>
				</div>



				<div class="tab-pane <?php echo $active_wed; ?>" id="s_wednesday">
					<h3>Wednesday</h3>	
					<div class="table-scroll">
						<table class="table table-bordered cell-text-middle" style="text-align: left">
							<thead>
								<tr>
									<th> Time </th>
									<th> Subject </th>
								</tr>
							</thead>
							<tbody>
							
								<?php 
								foreach ($wednesday_periods as $d) { 
								
									$period_type = $d->period_type;
									if ($period_type == 'Subject') {
										$subject = $this->common_model->get_subject_details($d->subject_id)->subject;
									} else {
										$subject = $d->activity;
									} ?>
									
									<tr>
										<td> <?php echo $d->start_time . ' - ' . $d->end_time; ?> </td>
										<td> <?php echo $subject; ?> </td>
									</tr>
									
								<?php } ?>
								
							</tbody>
						</table>
					</div>
				</div>



				<div class="tab-pane <?php echo $active_thu; ?>" id="s_thursday">
					<h3>Thursday</h3>	
					<div class="table-scroll">
						<table class="table table-bordered cell-text-middle" style="text-align: left">
							<thead>
								<tr>
									<th> Time </th>
									<th> Subject </th>
								</tr>
							</thead>
							<tbody>
							
								<?php 
								foreach ($thursday_periods as $d) { 
								
									$period_type = $d->period_type;
									if ($period_type == 'Subject') {
										$subject = $this->common_model->get_subject_details($d->subject_id)->subject;
									} else {
										$subject = $d->activity;
									} ?>
									
									<tr>
										<td> <?php echo $d->start_time . ' - ' . $d->end_time; ?> </td>
										<td> <?php echo $subject; ?> </td>
									</tr>
									
								<?php } ?>
								
							</tbody>
						</table>
					</div>
				</div>



				<div class="tab-pane <?php echo $active_fri; ?>" id="s_friday">
					<h3>Friday</h3>	
					<div class="table-scroll">
						<table class="table table-bordered cell-text-middle" style="text-align: left">
							<thead>
								<tr>
									<th> Time </th>
									<th> Subject </th>
								</tr>
							</thead>
							<tbody>
							
								<?php 
								foreach ($friday_periods as $d) { 
								
									$period_type = $d->period_type;
									if ($period_type == 'Subject') {
										$subject = $this->common_model->get_subject_details($d->subject_id)->subject;
									} else {
										$subject = $d->activity;
									} ?>
									
									<tr>
										<td> <?php echo $d->start_time . ' - ' . $d->end_time; ?> </td>
										<td> <?php echo $subject; ?> </td>
									</tr>
									
								<?php } ?>
								
							</tbody>
						</table>
					</div>
				</div>

				
			</div>
		</div>
	</div>


</div>


