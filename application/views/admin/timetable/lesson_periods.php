
<?php require("application/views/admin/timetable/modals/lesson_periods_actions.php");  ?>
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="new-item">
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_period"><i class="fa fa-plus"></i> New Period</button>
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#clear"><i class="fa fa-trash"></i> Clear All</button>
	</div>


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
									<th style="width: 150px"> Actions </th>
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
										<td> 
											<a type="button" href="<?php echo base_url('timetable_admin/edit_lesson_period/'.$d->id); ?>" class="btn btn-primary btn-sm" title="Edit period"> <i class="fa fa-pencil"></i></a>
											<a type="button" href="#" class="btn btn-danger btn-sm clickable" data-toggle="modal" data-target="#delete<?php echo $d->id; ?>" title="Delete period"> <i class="fa fa-trash"></i></a>
										</td>
									</tr>
									
									<?php 
									//delete confirm modal
									echo modal_delete_confirm($d->id, $subject, 'lesson period', 'timetable_admin/delete_lesson_period'); ?>
								<?php } ?>
								
							</tbody>
						</table>
					</div>
					<?php if (count($monday_periods) > 0) { ?>
						<div class="pull-right" style="margin-top: -20px">
							<button class="btn btn-danger btn-sm button-adjust" data-toggle="modal" data-target="#delete_mon"><i class="fa fa-trash"></i> Delete All (Monday)</button>
							<?php echo $this->timetable_model->modal_delete_day_periods($class_id, 'Monday', 'delete_mon'); ?>
						</div>
					<?php } ?>
				</div>

				
				

				<div class="tab-pane <?php echo $active_tue; ?> fade in" id="s_tuesday">
					<h3>Tuesday</h3>	
					<div class="table-scroll">
						<table class="table table-bordered cell-text-middle" style="text-align: left">
							<thead>
								<tr>
									<th> Time </th>
									<th> Subject </th>
									<th style="width: 100px"> Actions </th>
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
										<td> 
											<a type="button" href="<?php echo base_url('timetable_admin/edit_lesson_period/'.$d->id); ?>" class="btn btn-primary btn-sm" title="Edit period"> <i class="fa fa-pencil"></i></a>
											<a type="button" href="#" class="btn btn-danger btn-sm clickable" data-toggle="modal" data-target="#delete<?php echo $d->id; ?>" title="Delete period"> <i class="fa fa-trash"></i></a>
										</td>
									</tr>
									
									<?php 
									//delete confirm modal
									echo modal_delete_confirm($d->id, $subject, 'lesson period', 'timetable_admin/delete_lesson_period'); ?>
								<?php } ?>
								
							</tbody>
						</table>
					</div>
					<?php if (count($tuesday_periods) > 0) { ?>
						<div class="pull-right" style="margin-top: -20px">
							<button class="btn btn-danger btn-sm button-adjust" data-toggle="modal" data-target="#delete_tue"><i class="fa fa-trash"></i> Delete All (Tuesday)</button>
							<?php echo $this->timetable_model->modal_delete_day_periods($class_id, 'Tuesday', 'delete_tue'); ?>
						</div>
					<?php } ?>
				</div>

				
				
				

				<div class="tab-pane <?php echo $active_wed; ?> fade in" id="s_wednesday">
					<h3>Wednesday</h3>	
					<div class="table-scroll">
						<table class="table table-bordered cell-text-middle" style="text-align: left">
							<thead>
								<tr>
									<th> Time </th>
									<th> Subject </th>
									<th style="width: 100px"> Actions </th>
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
										<td> 
											<a type="button" href="<?php echo base_url('timetable_admin/edit_lesson_period/'.$d->id); ?>" class="btn btn-primary btn-sm" title="Edit period"> <i class="fa fa-pencil"></i></a>
											<a type="button" href="#" class="btn btn-danger btn-sm clickable" data-toggle="modal" data-target="#delete<?php echo $d->id; ?>" title="Delete period"> <i class="fa fa-trash"></i></a>
										</td>
									</tr>
									
									<?php 
									//delete confirm modal
									echo modal_delete_confirm($d->id, $subject, 'lesson period', 'timetable_admin/delete_lesson_period'); ?>
								<?php } ?>
								
							</tbody>
						</table>
					</div>
					<?php if (count($wednesday_periods) > 0) { ?>
						<div class="pull-right" style="margin-top: -20px">
							<button class="btn btn-danger btn-sm button-adjust" data-toggle="modal" data-target="#delete_wed"><i class="fa fa-trash"></i> Delete All (Wednesday)</button>
							<?php echo $this->timetable_model->modal_delete_day_periods($class_id, 'Wednesday', 'delete_wed'); ?>
						</div>
					<?php } ?>
				</div>

				
				
				

				<div class="tab-pane <?php echo $active_thu; ?> fade in" id="s_thursday">
					<h3>Thursday</h3>	
					<div class="table-scroll">
						<table class="table table-bordered cell-text-middle" style="text-align: left">
							<thead>
								<tr>
									<th> Time </th>
									<th> Subject </th>
									<th style="width: 100px"> Actions </th>
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
										<td> 
											<a type="button" href="<?php echo base_url('timetable_admin/edit_lesson_period/'.$d->id); ?>" class="btn btn-primary btn-sm" title="Edit period"> <i class="fa fa-pencil"></i></a>
											<a type="button" href="#" class="btn btn-danger btn-sm clickable" data-toggle="modal" data-target="#delete<?php echo $d->id; ?>" title="Delete period"> <i class="fa fa-trash"></i></a>
										</td>
									</tr>
									
									<?php 
									//delete confirm modal
									echo modal_delete_confirm($d->id, $subject, 'lesson period', 'timetable_admin/delete_lesson_period'); ?>
								<?php } ?>
								
							</tbody>
						</table>
					</div>
					<?php if (count($thursday_periods) > 0) { ?>
						<div class="pull-right" style="margin-top: -20px">
							<button class="btn btn-danger btn-sm button-adjust" data-toggle="modal" data-target="#delete_thu"><i class="fa fa-trash"></i> Delete All (Thursday)</button>
							<?php echo $this->timetable_model->modal_delete_day_periods($class_id, 'Thursday', 'delete_thu'); ?>
						</div>
					<?php } ?>
				</div>

				
				
				

				<div class="tab-pane <?php echo $active_fri; ?> fade in" id="s_friday">
					<h3>Friday</h3>	
					<div class="table-scroll">
						<table class="table table-bordered cell-text-middle" style="text-align: left">
							<thead>
								<tr>
									<th> Time </th>
									<th> Subject </th>
									<th style="width: 100px"> Actions </th>
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
										<td> 
											<a type="button" href="<?php echo base_url('timetable_admin/edit_lesson_period/'.$d->id); ?>" class="btn btn-primary btn-sm" title="Edit period"> <i class="fa fa-pencil"></i></a>
											<a type="button" href="#" class="btn btn-danger btn-sm clickable" data-toggle="modal" data-target="#delete<?php echo $d->id; ?>" title="Delete period"> <i class="fa fa-trash"></i></a>
										</td>
									</tr>
									
									<?php 
									//delete confirm modal
									echo modal_delete_confirm($d->id, $subject, 'lesson period', 'timetable_admin/delete_lesson_period'); ?>
								<?php } ?>
								
							</tbody>
						</table>
					</div>
					<?php if (count($friday_periods) > 0) { ?>
						<div class="pull-right" style="margin-top: -20px">
							<button class="btn btn-danger btn-sm button-adjust" data-toggle="modal" data-target="#delete_fri"><i class="fa fa-trash"></i> Delete All (Friday)</button>
							<?php echo $this->timetable_model->modal_delete_day_periods($class_id, 'Friday', 'delete_fri'); ?>
						</div>
					<?php } ?>
				</div>
				
			</div>
		</div>
	</div>


</div>


