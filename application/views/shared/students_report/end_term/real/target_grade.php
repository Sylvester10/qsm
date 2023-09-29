<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="row">
		<div class="col-md-8">
			<div class="new-item">
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/produce_report/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id); ?>"><i class="fa fa-line-chart"></i> Produce Report</a>
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/reports/'.$session.'/'.$term.'/'.$class_id); ?>"><i class="fa fa-line-chart"></i> All Reports</a>
			</div>
			<div class="m-t-10 m-b-10">
				<h4 class="text-bold">Student Information</h4>
				<div>Student's Name: <?php echo $this->common_model->get_student_fullname($student_id); ?></div>
				<div>Student's ID: <?php echo $y->admission_id; ?></div>
				<div>Class: <?php echo $class_details->class; ?></div>
				<div>Session: <?php echo $the_session; ?></div>
				<div>Term: <?php echo $term; ?></div>
			</div>
		</div>
	</div>



	<div class="row">

		<div class="col-md-8 p-b-30 table-scroll">

			<?php
	        $produce_target_grade_url = $this->c_controller.'/produce_target_grade_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
	        $form_attributes = array('id' => 'produce_end_term_target_grade_form'); 
	        echo form_open($produce_target_grade_url, $form_attributes); ?>

				<table class="table table-bordered cell-text-middle" style="text-align: left">
					<thead>
						<tr>
							<th class="w-3">S/N</th>
							<th class="min-w-250">Subject</th>
							<th class="min-w-100">Previous Grade</th>
							<th class="min-w-100">Target Grade</th>
							<th class="w-5">Action</th>
						</tr>
					</thead>
					<tbody>
						
						<?php
						$count = 1;
						foreach ($subjects as $s) { 

							//get subject ID from result
							//for subject teacher, value of subjects is an array of subject_ids. For admin and staff, value of subjects is an object.
							$subject_id = ($this->c_user_role == 'subject_teacher') ? $s : $s->id;

							$subject_details = $this->common_model->get_subject_details($subject_id);
							$subject = $subject_details->subject;

							$query = $this->students_report_model->check_target_grade_exists($session, $term, $class_id, $student_id, $subject_id); ?>
							
								<input type="hidden" name="subject_id[]" value="<?php echo $subject_id; ?>" />
								<tr>
									<td><?php echo $count; ?></td>
									<td><?php echo $subject; ?></td>
											
									<?php if ($query->num_rows() == 0) { ?>

										<td>
											<div class="form-group">
												<input class="form-control" type="text" name="previous_grade[]" value="<?php echo set_value('previous_grade[]'); ?>" maxlength="2" />
											</div>
										</td>

										<td>
											<div class="form-group">
												<input class="form-control" type="text" name="target_grade[]" value="<?php echo set_value('target_grade[]'); ?>" maxlength="2" />
											</div>
										</td>
											
									<?php } else { 

										$tg_id = $query->row()->id;
										$t = $this->students_report_model->get_target_grade_details($tg_id);
										$previous_grade = $t->previous_grade;
										$target_grade = $t->target_grade; ?>

										<td>
											<div class="form-group">
												<input class="form-control" type="text" name="previous_grade[]" value="<?php echo set_value('previous_grade[]', $t->previous_grade); ?>" maxlength="2" />
											</div>
										</td>

										<td>
											<div class="form-group">
												<input class="form-control" type="text" name="target_grade[]" value="<?php echo set_value('target_grade[]', $t->target_grade); ?>" maxlength="2" />
											</div>
										</td>
			
									<?php } ?>

									<td>

										<?php if ($query->num_rows() == 0) { ?>
											
											<a type="button" class="btn btn-danger btn-sm" href="#!" disabled><i class="fa fa-trash"></i></a>

										<?php } else { ?>

											<?php
											$tg_id = $query->row()->id; 
											$t = $this->students_report_model->get_target_grade_details($tg_id); ?>
											<a type="button" class="btn btn-danger btn-sm" href="<?php echo base_url($this->c_controller.'/delete_target_grade/'.$tg_id); ?>" title="Delete grade for <?php echo $subject; ?>"><i class="fa fa-trash"></i></a>

										<?php } ?>

									</td>
								</tr>

							<?php $count++; ?>
							
						<?php } //end foreach?>

					</tbody>
				</table>

				<?php if (count($subjects) > 0) { ?>

					<div id="status_msg"></div>
		            <div id="extra_msg"></div>

					<div class="form-group">
						<button class="btn btn-success btn-lg">Submit</button>
						<span id="d_loader_target_grade" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
					</div>

				<?php } ?>

			<?php echo form_close(); //produce_test_score ?>

		</div><!--/.col-->

	</div><!--/.row-->


	<script>
		//pass parameters to js
        var produce_target_grade_url = '<?php echo $produce_target_grade_url; ?>';
	</script>