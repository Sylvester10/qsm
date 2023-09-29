
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

	<div class="new-item m-b-20">

		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('incidents/new_incident/'.$student_id); ?>"><i class="fa fa-plus"></i> New Incident</a>
		
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('incidents/student_incidents/'.$student_id); ?>"><i class="fa fa-user"></i> Student's Incidents</a>
		
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('incidents'); ?>"><i class="fa fa-history"></i> All Incidents</a>
		
	</div>

	
		<div class="row">

			<div class="col-md-8 col-sm-12 col-xs-12 p-b-30">

				<h3>Incident Details</h3>
				<p>Student's Name: <?php echo $student_name; ?></p>
				<p>Student's Admission ID: <?php echo $admission_id; ?></p>
				<p>Student's Class: <?php echo $class; ?></p>
				<p>Session: <?php echo $the_session; ?></p>
				<p>Term: <?php echo $y->term; ?></p>
				<p>Incident Caption: <?php echo $y->caption; ?></p>
				<p>Date of Incident: <?php echo $y->incident_date; ?></p>
				<span class="text-bold">Incident Description </span> <br />
					<?php echo $y->description; ?>
				<p></p>
				<span class="text-bold">Actions Taken</span> <br />
					<?php echo $y->actions_taken; ?>

			</div>


		
			<div class="col-md-4 col-sm-12 col-xs-12">

				<?php echo form_open_multipart('incidents/upload_evidence/'.$y->id); ?>

					<div class="form-group">
						<h3>Upload Evidence</h3>
						<small>Only documents (PDF, Word) and image files allowed (max 2MB). Multiple files are allowed.</small>
						<input type="file" name="evidence[]" multiple class="form-control" required />
					</div>

					<div class="form-group">
						<button class="btn btn-primary">Upload</button>
					</div>

				<?php echo form_close(); ?>
				
			</div><!--/.col-->
			
		</div><!--/.row-->
		


	<div class="row m-t-15">
		<div class="col-md-8 col-sm-12 col-xs-12">

			<h3>Evidence Files (<?php echo $total_evidence; ?>)</h3>

			<?php 
			//select options bulk actions 
			$options_array = array(
				//'value' => 'Caption'
				'delete' => 'Delete'
			); 
			echo modal_bulk_actions('incidents/bulk_actions_evidence', $options_array); ?>
			
				<table id="evidence_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">

					<input type="hidden" id="incident_id" value="<?php echo $y->id; ?>" />
					
					<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
					
					<thead>
						<tr>
							<th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
							<th class="min-w-250"> Evidence </th>
							<th> Actions </th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				
			<?php echo form_close(); ?>

		</div>
	</div>

		