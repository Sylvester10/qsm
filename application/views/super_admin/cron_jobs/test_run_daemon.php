<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<div class="">
	<h1><?php echo $d->title; ?></h1>
	<p>Description: <?php echo $d->description; ?></p>
	<p>Frequency: <?php echo $d->frequency; ?></p>
	<p>Total Emails: <?php echo $d->total_mails; ?></p>
</div>


<div class="row">
	<div class="col-md-9 col-sm-12  table-scroll">

		<h3>Email Sample</h3>
		<table class="table table-bordered cron_test_email_message">
			<tr>
				<td>
					<?php echo email_header_default($email_subject); ?>
					<?php echo $email_message; ?>
				 	<?php echo email_footer_default(); ?>
				</td>
			</tr>
		</table>


		<?php if ($d->category == 'school_data') { ?>

			<div class="m-t-30">
				<h3>Schools that meet the query criteria (<?php echo $total_schools; ?>) </h3>
				<?php
				//data records compare daemons
				if ( in_array($name, $records_compare_daemons) ) { ?>
					<p>Note: Schools in green background have more than <?php echo $this->initial_school_records; ?> records, hence, are excluded from the query.</p>
				<?php } ?>

				<?php
				if ($total_schools === 0) { ?>

					<h4 class="text-info">None</h4>

				<?php } else { ?>

					
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>S/N</th>
								<th>School Name</th>
								<th>Installed</th>
								<th>Chief Admin</th>
								<th>Data Records</th>
							</tr>
						</thead>
						<tbody>

							<?php
							//start count from 1 on first page, add 1 to offset on subsequent pages
							$count = ($offset === 0) ? 1 : ($offset + 1);
							foreach ($schools as $s) {
								
								$school_id = $s->id;
								$y = $this->common_model->get_school_info($school_id);
								$school_name = $y->school_name;
								$chief_admin_details = $this->common_model->get_admin_details_by_id($y->chief_admin_id);
								$chief_admin_name = $chief_admin_details->name;
								$email = $chief_admin_details->email;
								$date_installed = $y->date_installed;
								$date_installed_ago = time_ago_alt($date_installed);
								$date_installed = x_date_full($date_installed) . ' (' . $date_installed_ago . ')';
								$school_records = $this->common_model->get_school_data_records($school_id); 

								//data records compare daemons
								if ( in_array($name, $records_compare_daemons) ) {

									//check the data records
									$school_records = $this->common_model->get_school_data_records($school_id);
									//compare current school records with initial records (records inserted during registration)
									//change row background if data > initial records
									if ($school_records > $this->initial_school_records) { 
										$tr_bg = 'bg-success'; 
									} else {
										$tr_bg = NULL; 
									} 

								} else {
									$tr_bg = NULL; 
								} ?>

								<tr class="<?php echo $tr_bg; ?>">
									<td><?php echo $count; ?></td>
									<td><?php echo $school_name; ?></td>
									<td><?php echo $date_installed; ?></td>
									<td><?php echo $chief_admin_name; ?></td>
									<td><?php echo $school_records; ?></td>
								</tr>

								<?php $count++;

							} ?>
							
						</tbody>
					</table>
					

					<!--Pagination Links-->
					<?php echo pagination_links($links, 'pagination'); ?>

				<?php } ?>


			</div>

		<?php } ?>

	</div><!--/.col-md-9-->


	<div class="col-md-3 col-sm-12">
		
		<h3><a href="<?php echo base_url('cron_jobs'); ?>">Cron Daemons (<?php echo count($cron_daemons); ?>)</a></h3>
		<?php 
		foreach ($cron_daemons as $cd) {
			if ($cd->name != $name) {
				$link = '<p><a href="' . base_url('cron_jobs/test_run/'.$cd->name) .'">' . $cd->title . '</a></p>';
			} else { //make current page link unclickable
				$link = '<p>' . $cd->title . '</p>';
			}
			echo $link;
		} ?>

	</div>

</div><!--/.row-->

