
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="row">
		<div class="col-md-8">
			<div class="new-item">
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/report_card/'.$template_id.'/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id); ?>"><i class="fa fa-eye"></i> View Report Card</a>
				<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/reports/'.$session.'/'.$term.'/'.$class_id); ?>"><i class="fa fa-line-chart"></i> All Reports</a>
			</div>
			<div class="m-t-10 m-b-10">
				<h4 class="text-bold">Student Information</h4>
				<div>Student's Name: <?php echo $this->common_model->get_student_fullname($student_id); ?></div>
				<div>Student's ID: <?php echo $y->admission_id; ?></div>
				<div>Class: <?php echo $class_details->class; ?></div>
				<div>Session: <?php echo $the_session; ?></div>
				<div>Term: <?php echo $term; ?></div>

				<div class="m-t-20"></div>
				<b>Performance So Far</b>
				<div class="">Total Score: <?php echo $overall_total_score; ?></div>
				<div class="">Percentage Score: <?php echo $overall_percentage_score; ?></div>
				<div class="">Overall Grade: <?php echo $overall_grade; ?></div>
				<div class="">Position: <?php echo $position; ?></div>
				<div class="">Class Population: <?php echo $this->common_model->get_class_population($class_id); ?>
				</div>
			</div>
		</div>
	</div>



	<div class="row">

		<div class="col-md-12 p-b-30 table-scroll">
			
			<?php
            $produce_report_url = $this->c_controller.'/produce_test_score_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
            $form_attributes = array('id' => 'produce_mid_term_report_form'); 
            echo form_open($produce_report_url, $form_attributes); ?>

				<h3>Achievement Scores (Classwork <?php echo $mt_classwork; ?>, Homework <?php echo $mt_homework; ?>, Tests <?php echo $mt_tests; ?>)</h3>

				<b class="f-s-18">Note</b>
				<ul style="margin-left: -20px">
					<li>Specify Previous Grade, Target Grade, Classwork score, Homework score, Tests score, and Subject Teacher's Comment for the required subjects and click Submit.</li>
					<li>If Previous and Target Grades for the current term have been specified <a class="underline-link" href="<?php echo base_url($this->c_controller.'/target_grade/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id); ?>" target="_blank">here</a>, they will be populated into their respective fields and any changes made here will be updated accordingly.</li>
					<li>If there are multiple tests that aggregate to form Tests score, condense them into a single test score.</li>
				</ul>

				<table class="table table-bordered cell-text-middle" style="text-align: left">
					<thead>
						<tr>
							<th class="w-3">S/N</th>
							<th class="min-w-150">Subject</th>
							<th class="min-w-70">Prev. Grade</th>
							<th class="min-w-60">Target Grade</th>
							<th class="min-w-60">Classwork Score</th>
							<th class="min-w-60">Homework Score</th>
							<th class="min-w-60">Tests Score</th>
							<th class="min-w-70">Raw Total</th>
							<th class="min-w-70">Conv. Total %</th>
							<th class="min-w-70">Grade</th>
							<th class="min-w-250">Subject Comment <br /><small>(45 characters max)</small></th>
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

							$target_grade_query = $this->students_mid_term_report_model->check_target_grade_exists($session, $term, $class_id, $student_id, $subject_id);
							$test_score_query = $this->students_mid_term_report_model->check_test_score_exists($session, $term, $class_id, $student_id, $subject_id); ?>
							
							<input type="hidden" name="subject_id[]" value="<?php echo $subject_id; ?>" />

							<tr>

								<td><?php echo $count; ?></td>
								<td><?php echo $subject; ?></td>


								<?php if ($target_grade_query->num_rows() == 0) { ?>

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

									$tg_id = $target_grade_query->row()->id;
									$t = $this->students_mid_term_report_model->get_target_grade_details($tg_id); ?>

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


										
								<?php if ($test_score_query->num_rows() == 0) { ?>

									<td>
										<div class="form-group">
											<input class="form-control numbers-only classwork_field" type="text" name="classwork[]" value="<?php echo set_value('classwork[]'); ?>" maxlength="2" />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control numbers-only homework_field" type="text" name="homework[]" value="<?php echo set_value('homework[]'); ?>" maxlength="2" />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control numbers-only tests_field" type="text" name="tests[]" value="<?php echo set_value('tests[]'); ?>" maxlength="3" />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" value="" readonly />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" value="" readonly />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" value="" readonly />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" name="subject_comment[]" value="<?php echo set_value('subject_comment[]'); ?>" maxlength="45" />
										</div>
									</td>

									<td>
										<a type="button" class="btn btn-danger btn-sm" href="#!" disabled><i class="fa fa-trash"></i></a>
									</td>


								<?php } else { 

									$test_score_id = $test_score_query->row()->id;
									$t = $this->students_mid_term_report_model->get_test_score_details($test_score_id);
									$raw_total = $t->classwork + $t->homework + $t->tests;
									$percentage_total = $this->students_mid_term_report_model->get_subject_percentage_score($class_id, $raw_total);
									$grade = $this->students_mid_term_report_model->get_subject_score_data(intval($percentage_total), 'grade'); ?>

									<td>
										<div class="form-group">
											<input class="form-control numbers-only classwork_field" type="text" name="classwork[]" value="<?php echo set_value('classwork[]', $t->classwork); ?>" maxlength="2" />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control numbers-only homework_field" type="text" name="homework[]" value="<?php echo set_value('homework[]', $t->homework); ?>" maxlength="2" />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control numbers-only tests_field" type="text" name="tests[]" value="<?php echo set_value('tests[]', $t->tests); ?>" maxlength="3" />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" value="<?php echo $raw_total; ?>" readonly />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" value="<?php echo $percentage_total; ?>" readonly />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" value="<?php echo $grade; ?>" readonly />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" name="subject_comment[]" value="<?php echo set_value('subject_comment[]', $t->subject_comment); ?>" maxlength="45" />
										</div>
									</td>

									<td>
										<a type="button" class="btn btn-danger btn-sm" href="<?php echo base_url($this->c_controller.'/delete_test_score/'.$test_score_id); ?>" title="Delete scores for <?php echo $subject; ?>"><i class="fa fa-trash"></i></a>
									</td>
										
								<?php } ?>

							</tr>

							<?php $count++; ?>
						<?php } //end foreach?>

					</tbody>
				</table>

				<?php if (count($subjects) > 0) { ?>

                    <div id="achievement_status_msg"></div>
                    <div id="extra_msg"></div>

                    <div class="form-group">
                        <button class="btn btn-success btn-lg">Submit</button>
                        <span id="d_loader_achievement" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
                    </div>

                <?php } ?>

			<?php echo form_close(); //produce_test_score ?>

		</div><!--/.col-->

	</div><!--/.row-->



	<?php
    $produce_ct_comment_url = $this->c_controller.'/produce_class_teacher_comment_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;

	//check if class teacher comment is allowed
	if ($mt_class_teacher_comment == 'true') { 

		//check if admin or class teacher 
		if ($this->c_user_role == 'admin' || $this->c_user_role == 'class_teacher') { 

			//check if result has been created, show class teacher comment box if true
			if ($result_details) { ?>

				<div class="row">
					<div class="col-md-8 m-b-30">
						
						<h3>Class Teacher's Comment</h3>

						<?php
                        $form_attributes = array('id' => 'produce_mid_term_ct_comment_form'); 
                        echo form_open($produce_ct_comment_url, $form_attributes);

							$query = $this->students_mid_term_report_model->check_class_teacher_comment_exists($session, $term, $class_id, $student_id); 

							if ($query->num_rows() == 0) { ?>
							
								<div class="form-group">
									<label class="form-control-label">Comment: <b class="text-danger">Not submitted</b></label>
									<br/>
									<select name="comment" class="form-control">
										<option value="">Select appropriate comment</option>
										<?php echo predefined_class_teacher_comment_options(); ?>
									</select>
								</div>

								<div class="form-group">
									<label class="form-control-label">
										<input type="checkbox" name="p_comment" /> 
										Personalize Comment? <small>(Overrides comment select options above if checked)</small>
									</label>
									<br/>
									<textarea name="personalized_comment" class="form-control t100"><?php echo set_value('personalized_comment'); ?></textarea>
								</div>

								<div id="ct_comment_status_msg"></div>

								<div class="form-group">
									<button class="btn btn-success btn-lg">Submit</button>
									<span id="d_loader_ct_comment" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
								</div>

							<?php } else { 

								$comment_id = $query->row()->id;
								$comment_details = $this->students_mid_term_report_model->get_class_teacher_comment_details($comment_id); ?>

								<div class="form-group">
									<label class="form-control-label">Comment: <b class="text-success">Submitted</b></label>
									<br/>
									<select name="comment" class="form-control">
										<option selected value="<?php echo $comment_details->comment; ?>"><?php echo $comment_details->comment; ?></option>
										<?php echo predefined_class_teacher_comment_options(); ?>
									</select>
								</div>

								<div class="form-group">
									<label class="form-control-label">
										<?php
										if ($comment_details->personalized != 'true') { ?>
											<input type="checkbox" name="p_comment" /> 
										<?php } else { ?>
											<input type="checkbox" name="p_comment" checked /> 
										<?php } ?>
										Personalize Comment? <small>(Overrides comment select options above if checked)</small>
									</label>
									<br/>
									<textarea name="personalized_comment" class="form-control t100"><?php echo set_value('personalized_comment', $comment_details->comment); ?></textarea>
								</div>

								<div id="ct_comment_status_msg"></div>

								<div class="form-group">
									<button class="btn btn-success btn-lg">Update</button>
									<span id="d_loader_ct_comment" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
								</div>

							<?php } ?>

						<?php echo form_close(); ?>

					</div><!-- /.col-md-10 -->

				</div><!-- /.row -->

			<?php } //if result exists

		} //if admin or class teacher 
		
	} //if class teacher comment is allowed ?>



	<script>
		//pass parameters to js
		var mt_classwork = <?php echo $mt_classwork; ?>;
		var mt_homework = <?php echo $mt_homework; ?>;
		var mt_tests = <?php echo $mt_tests; ?>;
		var produce_report_url = '<?php echo $produce_report_url; ?>';
        var produce_ct_comment_url = '<?php echo $produce_ct_comment_url; ?>';
	</script>