
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>


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
				<div class="">Term GPA: <?php echo $gpa; ?></div>
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
            $form_attributes = array('id' => 'produce_end_term_report_form'); 
            echo form_open($produce_report_url, $form_attributes); ?>

				<h3>Achievement Scores (CA <?php echo $ca_max_score; ?>%, Exam <?php echo $exam_max_score; ?>%)</h3>

				<b class="f-s-18">Note</b>
				<ul style="margin-left: -20px">
					<li>Specify Previous Grade, Target Grade, CA score, Exam score, and Subject Teacher's Comment for the required subjects and click Submit.</li>
					<li>If Previous and Target Grades for the current term have been specified <a class="underline-link" href="<?php echo base_url($this->c_controller.'/target_grade/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id); ?>" target="_blank">here</a>, they will be populated into their respective fields and any changes made here will be updated accordingly.</li>
					<li>If there are multiple tests that aggregate to form Continuous Assessment (CA), condense the test scores into a single CA score.</li>
				</ul>

				<table class="table table-bordered cell-text-middle" style="text-align: left">
					<thead>
						<tr>
							<th class="w-3">S/N</th>
							<th class="min-w-150">Subject</th>
							<th class="min-w-70">Prev. Grade</th>
							<th class="min-w-70">Target Grade</th>
							<th class="min-w-70">CA Score</th>
							<th class="min-w-70">Exam Score</th>
							<th class="min-w-70">Total</th>
							<th class="min-w-70">Grade</th>
							<th class="min-w-70">GP</th>
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

							$target_grade_query = $this->students_report_model->check_target_grade_exists($session, $term, $class_id, $student_id, $subject_id);
							$test_score_query = $this->students_report_model->check_test_score_exists($session, $term, $class_id, $student_id, $subject_id); ?>
							
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
									$t = $this->students_report_model->get_target_grade_details($tg_id); ?>

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
											<input class="form-control numbers-only ca_field" type="text" name="ca_score[]" value="<?php echo set_value('ca_score[]'); ?>" maxlength="2" />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control numbers-only exam_field" type="text" name="exam_score[]" value="<?php echo set_value('exam_score[]'); ?>" maxlength="3" />
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
									$t = $this->students_report_model->get_test_score_details($test_score_id);
									$total_score = $t->ca_score + $t->exam_score;
									$grade = $this->students_report_model->get_subject_score_data($t->session, $t->term, $t->class_id, $t->student_id, $total_score, 'grade');
									$grade_point = $this->students_report_model->get_subject_score_data($t->session, $t->term, $t->class_id, $t->student_id, $total_score, 'gp').'.0'; ?>

									<td>
										<div class="form-group">
											<input class="form-control numbers-only ca_field" type="text" name="ca_score[]" value="<?php echo set_value('ca_score[]', $t->ca_score); ?>" maxlength="2" />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control numbers-only exam_field" type="text" name="exam_score[]" value="<?php echo set_value('exam_score[]', $t->exam_score); ?>" maxlength="3" />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" value="<?php echo $total_score; ?>" readonly />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" value="<?php echo $grade; ?>" readonly />
										</div>
									</td>

									<td>
										<div class="form-group">
											<input class="form-control" type="text" value="<?php echo $grade_point; ?>" readonly />
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
	$produce_aptitude_url = $this->c_controller.'/produce_aptitude_score_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
		            
	//check if admin or class teacher 
	if ($this->c_user_role == 'admin' || $this->c_user_role == 'class_teacher') { 

		//check if behavioural aptitude is enable
		if ($enable_aptitudes != 'true') { ?>

			<div class="row">
				<div class="col-md-8 p-b-30">

					<h3>Behavioural Aptitudes</h3>
					<p class="text-danger">Behavioural Aptitudes is not allowed for <?php echo $section ?> section. 

						<?php
						//check if admin or class teacher 
						if ($this->c_user_role == 'admin') { ?>

							To manage this setting for this section, <a href="<?php echo base_url('sections/edit_section/'.$section_id.'#report_card_settings'); ?>" target="_blank" class="underline-link">Click here</a>.

						<?php } else { ?>

							If you feel that it should be allowed, ask your school's account administrator to enable it.

						<?php } ?>	

					</p>

				</div>
			</div>


		<?php } else { ?>


			<div class="row">

				<div class="col-md-12 p-b-30 table-scroll">

					<h3>Behavioural Aptitudes</h3>

					<?php
					//check if admin or class teacher 
					if ($this->c_user_role == 'admin') { ?>

						<p class="text-danger">To disallow Behavioural Aptitudes for <?php echo $section ?> section, <a href="<?php echo base_url('sections/edit_section/'.$section_id.'#report_card_settings'); ?>" target="_blank" class="underline-link">Click here</a>.</p>

					<?php } ?>

					<?php
		            $form_attributes = array('id' => 'produce_end_term_aptitude_form'); 
		            echo form_open($produce_aptitude_url, $form_attributes); ?>

						<table class="table table-bordered cell-text-middle" style="text-align: left">
							<thead>
								<tr class="aptitude_scores">
									<th class="w-5">S/N</th>
									<th class="min-w-250">
										<div class="pull-right">
		                                    <input class="form-control report_radio" type="radio" name="bulk_select_items" id="deselect_all_items" />
		                                </div>
										Aptitude
									</th>
									<th class="min-w-8 text-center">
		                                Excellent (5)
		                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_5" />
		                            </th>
		                            <th class="min-w-8 text-center">
		                                Good (4)
		                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_4" />
		                            </th>
		                            <th class="min-w-8 text-center">
		                                Average (3)
		                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_3" />
		                            </th>
		                            <th class="min-w-8 text-center">
		                                Poor (2)
		                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_2" />
		                            </th>
		                            <th class="min-w-8 text-center">
		                                Very Poor (1)
		                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_1" />
		                            </th>
									<th class="min-w-150">Status</th>
									<th class="w-5">Action</th>
								</tr>
							</thead>
							<tbody class="aptitude_scores">

								<?php
								$count = 1;
								$i = 0;
								foreach ($aptitudes as $p) { 

									$aptitude_id = $p->id;
									$query = $this->students_report_model->check_aptitude_score_exists($session, $term, $class_id, $student_id, $aptitude_id); ?>

									<input type="hidden" name="aptitude_id[]" value="<?php echo $aptitude_id; ?>" />

									<tr>
										<td><?php echo $count; ?></td>
										<td><?php echo $p->aptitude; ?></td>
												
										<?php if ($query->num_rows() == 0) { ?>
												
											<td>
												<div class="form-group">
													<input class="form-control item_5_checkbox" type="radio" name="score[<?php echo $i; ?>]" value="5" />
												</div>
											</td>

											<td>
												<div class="form-group">
													<input class="form-control item_4_checkbox" type="radio" name="score[<?php echo $i; ?>]" value="4" />
												</div>
											</td>

											<td>
												<div class="form-group">
													<input class="form-control item_3_checkbox" type="radio" name="score[<?php echo $i; ?>]" value="3" />
												</div>
											</td>

											<td>
												<div class="form-group">
													<input class="form-control item_2_checkbox" type="radio" name="score[<?php echo $i; ?>]" value="2" />
												</div>
											</td>

											<td>
												<div class="form-group">
													<input class="form-control item_1_checkbox" type="radio" name="score[<?php echo $i; ?>]" value="1" />
												</div>
											</td>


										<?php } else { 

											$aptitude_score_id = $query->row()->id;
											$aptitude_score_details = $this->students_report_model->get_aptitude_score_details($aptitude_score_id); ?>

											<td>
												<div class="form-group">
													<input class="form-control item_5_checkbox" type="radio" name="score[<?php echo $i; ?>]" value="5" <?php echo set_radio( "score[<?php echo $i; ?>]", '5', radio_value($aptitude_score_details->score, '5') ); ?> />
												</div>
											</td>

											<td>
												<div class="form-group">
													<input class="form-control item_4_checkbox" type="radio" name="score[<?php echo $i; ?>]" value="4" <?php echo set_radio( "score[<?php echo $i; ?>]", '4', radio_value($aptitude_score_details->score, '4') ); ?> />
												</div>
											</td>

											<td>
												<div class="form-group">
													<input class="form-control item_3_checkbox" type="radio" name="score[<?php echo $i; ?>]" value="3" <?php echo set_radio( "score[<?php echo $i; ?>]", '3', radio_value($aptitude_score_details->score, '3') ); ?> />
												</div>
											</td>

											<td>
												<div class="form-group">
													<input class="form-control item_2_checkbox" type="radio" name="score[<?php echo $i; ?>]" value="2" <?php echo set_radio( "score[<?php echo $i; ?>]", '2', radio_value($aptitude_score_details->score, '2') ); ?> />
												</div>
											</td>

											<td>
												<div class="form-group">
													<input class="form-control item_1_checkbox" type="radio" name="score[<?php echo $i; ?>]" value="1" <?php echo set_radio( "score[<?php echo $i; ?>]", '1', radio_value($aptitude_score_details->score, '1') ); ?> />
												</div>
											</td>
												
										<?php } ?>
										
										<td>
											<?php
											if ($query->num_rows() == 0) { 
												echo '<b class="text-danger">Not submitted</b>';
											} else {
												echo '<b class="text-success">Submitted</b>';
											} ?>
										</td>

										<td>
											<?php if ($query->num_rows() == 0) { ?>
												<a type="button" class="btn btn-danger btn-sm" href="#!" disabled><i class="fa fa-trash"></i></a>
											<?php } else { 
												$aptitude_score_id = $query->row()->id; ?>
												<a type="button" class="btn btn-danger btn-sm" href="<?php echo base_url($this->c_controller.'/delete_aptitude_score/'.$aptitude_score_id); ?>"><i class="fa fa-trash"></i></a>
											<?php } ?>
										</td>

									</tr>

									<?php $count++; ?>
									<?php $i++; ?>
								<?php } ?>

							</tbody>
						</table>

						<?php if (count($aptitudes) > 0) { ?>

							<div id="aptitude_status_msg"></div>
							<div id="aptitude_extra_msg"></div>

							<div class="form-group">
								<button class="btn btn-success btn-lg">Submit</button>
								<span id="d_loader_aptitude" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
							</div>

						<?php } ?>

					<?php echo form_close(); //produce_aptitude_score ?>

				</div><!--/.col-->


			</div><!--/.row-->


		<?php } ?>

	<?php } //if admin or class teacher ?>




	<?php
    $produce_att_url = $this->c_controller.'/produce_att_scores_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
   
    if ($this->c_user_role == 'admin' || $this->c_user_role == 'class_teacher') { 

    	//check if result has been created, show class teacher comment box if true
    	if ($result_details) { ?>

	        <div class="row">
	            <div class="col-md-12 p-b-30 table-scroll">

	                <?php
	                $form_attributes = array('id' => 'produce_end_term_att_form'); 
	                echo form_open($produce_att_url, $form_attributes); ?>

	                    <div class="row">
	                        <div class="col-md-6 col-sm-12 col-xs-12">
	                            <h3>Attendance</h3>
	                            <div>
	                            	<?php
	                            	if ($result_details->att_present == NULL &&  $result_details->att_absent == NULL && $result_details->att_tardy == NULL) {
	                            		$status = '<b class="text-danger">Not Submitted</b>';
	                            	} else {
	                            		$status = '<b class="text-success">Submitted</b>';
	                            	} ?>
	                            	Status: <?php echo $status; ?>
	                            </div>
	                            <?php $checked = ($result_details->att_type == 'custom') ? 'checked' : NULL; ?>
	                            <input type="checkbox" name="customize_att" id="customize_att" <?php echo $checked; ?> /> Customize

	                            <div id="class_att_table">
	                                <table class="table table-bordered cell-text-middle">
	                                    <thead>
	                                        <tr>
	                                            <th>Days Present</th>
	                                            <th>Days Absent</th>
	                                            <th>Times Tardy</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                        <tr>
	                                            <td><?php echo $att_present; ?></td>
	                                            <td><?php echo $att_absent; ?></td>
	                                            <td>N/A</td>
	                                        </tr>
	                                    </tbody>
	                                </table>
	                            </div>

	                            <div id="custom_att_table">
	                                <table class="table table-bordered cell-text-middle">
	                                    <thead>
	                                        <tr>
	                                            <th>Days Present</th>
	                                            <th>Days Absent</th>
	                                            <th>Times Tardy</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody>
	                                        <tr>
	                                            <td class="form-group">
	                                                <input type="text" class="form-control numbers-only" name="att_present" value="<?php echo $result_details->att_present; ?>" />
	                                            </td>
	                                            <td class="form-group">
	                                                <input type="text" class="form-control numbers-only" name="att_absent" value="<?php echo $result_details->att_absent; ?>" />
	                                            </td>
	                                            <td class="form-group">
	                                                <input type="text" class="form-control numbers-only" name="att_tardy" value="<?php echo $result_details->att_tardy; ?>" />
	                                            </td>
	                                        </tr>
	                                    </tbody>
	                                </table>
	                            </div>

	                        </div>
	                    </div>
	                
	                    <div id="att_status_msg"></div>

	                    <div class="form-group">
	                        <button class="btn btn-success btn-lg">Submit</button>
	                        <span id="d_loader_att" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
	                    </div>

	                <?php echo form_close(); ?>

	            </div>
	        </div>

    	<?php } //if result_details ?>

	<?php } //if admin or class teacher ?>



	
	<?php
	$produce_ct_comment_url = $this->c_controller.'/produce_class_teacher_comment_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
                    
	//check if admin or class teacher 
	if ($this->c_user_role == 'admin' || $this->c_user_role == 'class_teacher') { 
	
		//check if result has been created, show class teacher comment box if true
		if ($result_details) { ?>

			<div class="row">
				<div class="col-md-8 m-b-30">
					
					<h3>Class Teacher's Comment</h3>

					<?php
                    $form_attributes = array('id' => 'produce_end_term_ct_comment_form'); 
                    echo form_open($produce_ct_comment_url, $form_attributes);

						$query = $this->students_report_model->check_class_teacher_comment_exists($session, $term, $class_id, $student_id); 

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
							$comment_details = $this->students_report_model->get_class_teacher_comment_details($comment_id); ?>

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

		<?php } ?>

	<?php } //if admin or class teacher ?>
		
	
	
	

	<?php
	$produce_ht_comment_url = $this->c_controller.'/produce_head_teacher_comment_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
                    
	//check if admin 
	if ($this->c_user == 'admin') { 

		//check if result has been created, show head teacher comment box if true
		if ($result_details) { ?>

			<div class="row">
				<div class="col-md-8">
					
					<h3>Head Teacher's Comment</h3>

					<?php
                    $form_attributes = array('id' => 'produce_end_term_ht_comment_form'); 
                    echo form_open($produce_ht_comment_url, $form_attributes); ?>
					
						<div class="form-group">
							<label class="form-control-label">
								Predefined Comment: <?php echo $predefined_head_teacher_comment; ?>
							</label>
						</div>

						<?php 
						$query = $this->students_report_model->check_head_teacher_comment_exists($session, $term, $class_id, $student_id); 

						if ( $query->num_rows() == 0 ) { ?>

							<div class="form-group">
								<label class="form-control-label">
									<input type="checkbox" name="p_comment" /> 
									Personalize Comment? <small>(Overrides predefined comment if checked)</small>
								</label>
								<br/>
								<textarea name="personalized_comment" class="form-control t100"><?php echo set_value('personalized_comment'); ?></textarea>
							</div>

							<div id="ht_comment_status_msg"></div>

							<div class="form-group">
								<button class="btn btn-success btn-lg">Submit</button>
								<span id="d_loader_ht_comment" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
							</div>

						<?php } else { 

							$comment_id = $query->row()->id;
							$comment_details = $this->students_report_model->get_head_teacher_comment_details($comment_id); ?>

							<div class="form-group">
								<label class="form-control-label">
									<input type="checkbox" name="p_comment" checked /> 
									Personalize Comment? <small>(Overrides predefined comment if checked)</small>
								</label>
								<br/>
								<textarea name="personalized_comment" class="form-control t100"><?php echo set_value('personalized_comment', $comment_details->comment); ?></textarea>
							</div>

							<div id="ht_comment_status_msg"></div>

							<div class="form-group">
								<button class="btn btn-success btn-lg">Update</button>
								<span id="d_loader_ht_comment" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
							</div>

						<?php } ?>

					<?php echo form_close(); ?>

				</div><!-- /.col-md-10 -->

			</div><!-- /.row -->

		<?php } ?>

	<?php } //if admin ?>


	<script>
		//pass parameters to js
		var ca_max_score = <?php echo $ca_max_score; ?>;
		var exam_max_score = <?php echo $exam_max_score; ?>;
		var produce_report_url = '<?php echo $produce_report_url; ?>';
		var produce_aptitude_url = '<?php echo $produce_aptitude_url; ?>';
		var produce_att_url = '<?php echo $produce_att_url; ?>';
        var produce_ct_comment_url = '<?php echo $produce_ct_comment_url; ?>';
        var produce_ht_comment_url = '<?php echo $produce_ht_comment_url; ?>';
	</script>


