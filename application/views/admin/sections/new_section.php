
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

<div class="new-item">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('sections'); ?>"><i class="fa fa-cubes"></i> All Sections</a>
</div>

<div class="row">
	<div class="col-md-6">

		<?php 
		$form_attributes = array("id" => "new_section_form");
		echo form_open('sections/add_new_section_ajax', $form_attributes); ?>

			<section id="section_settings">

				<div class="form-group">
					<label class="form-control-label">Section</label>
					<input type="text" name="section" value="<?php echo set_value('section'); ?>" class="form-control" required />
				</div>

				<div class="form-group">
					<label class="form-control-label">Level <small>(Required to help with ordering)</small></label>
					<select class="form-control selectpicker" name="level">
						<option value="">Select Level</option>
						<?php
						for ($i = 1; $i <= 12; $i++) { 
							$selected = ($i == $next_level) ? 'selected' : NULL; ?>
							<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div>

			</section>



			<!-- Mid-term Report Card Settins -->
			<section id="mt_report_card_settings" class="p-t-30">

				<h3 class="m-t-20">Mid-term Report Card Settings</h3>

				<div class="form-group">
					<label class="form-control-label">Max. Classwork Score</label>
					<input type="text" name="mt_classwork" class="form-control numbers-only" value="<?php echo set_value('mt_classwork', 5); ?>" required />
					<div class="form-error"><?php echo form_error('mt_classwork'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Max. Homework Score</label>
					<input type="text" name="mt_homework" class="form-control numbers-only" value="<?php echo set_value('mt_homework', 5); ?>" required />
					<div class="form-error"><?php echo form_error('mt_homework'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Max. Tests Score</label>
					<input type="text" name="mt_tests" class="form-control numbers-only" value="<?php echo set_value('mt_tests', 10); ?>" required />
					<div class="form-error"><?php echo form_error('mt_tests'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Show Class Teacher's Comment on Report Card</label> <br />
					<label>
						<input type="radio" name="mt_class_teacher_comment" value="true" <?php echo set_radio( 'mt_class_teacher_comment', 'true'); ?> /> Yes
					</label>
					<label class="m-l-10">
						<input type="radio" name="mt_class_teacher_comment" value="false" <?php echo set_radio( 'mt_class_teacher_comment', 'false'); ?> checked /> No
					</label>
					<div class="form-error"><?php echo form_error('mt_class_teacher_comment'); ?></div>
				</div>

			</section>



			<!-- End of Term Report Card Settins -->
			<section id="report_card_settings" class="p-t-30">

				<h3 class="m-t-20">End-of-Term Report Card Settings</h3>

				<div class="form-group">
					<label class="form-control-label">Max. Continuous Assessment (CA) Score</label>
					<input type="text" name="ca_max_score" class="form-control numbers-only" value="<?php echo set_value('ca_max_score', 30); ?>" required />
					<div class="form-error"><?php echo form_error('ca_max_score'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Max. Exam Score</label>
					<input type="text" name="exam_max_score" class="form-control numbers-only" value="<?php echo set_value('exam_max_score', 70); ?>" required />
					<div class="form-error"><?php echo form_error('exam_max_score'); ?></div>
				</div>
				
				<div class="form-group">
					<label class="form-control-label">Pass Mark</label>
					<input type="text" name="pass_mark" class="form-control numbers-only" value="<?php echo set_value('pass_mark', 40); ?>" required />
					<div class="form-error"><?php echo form_error('pass_mark'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Ranking/Position <small>(whether to show student's position on report card or not)</small></label> <br />
					<label>
						<input type="radio" name="ranking" value="true" <?php echo set_radio( 'ranking', 'true'); ?> checked /> Allowed
					</label>
					<label class="m-l-10"><input type="radio" name="ranking" value="false" <?php echo set_radio( 'ranking', 'false'); ?> /> 
						Not Allowed
					</label>
					<div class="form-error"><?php echo form_error('ranking'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Subject Grouping 
						<small>
							(whether to organise subjects by subject groups on report card or not). <a class="underline-link" href="<?php echo base_url('report_templates/template_4'); ?>" target="_blank">See Template 4</a>
						</small>
					</label> <br />
					<label>
						<input type="radio" name="enable_subject_grouping" value="true" <?php echo set_radio( 'enable_subject_grouping', 'true'); ?> /> Allowed
					</label>
					<label class="m-l-10"><input type="radio" name="enable_subject_grouping" value="false" <?php echo set_radio( 'enable_subject_grouping', 'false'); ?> checked /> 
						Not Allowed
					</label>
					<div class="form-error"><?php echo form_error('enable_subject_grouping'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Behavioural Aptitudes <small>(whether to allow production and display of behavioural aptitudes on report card or not)</small></label> <br />
					<label>
						<input type="radio" name="enable_aptitudes" value="true" <?php echo set_radio( 'enable_aptitudes', 'true'); ?> checked /> Allowed
					</label>
					<label class="m-l-10"><input type="radio" name="enable_aptitudes" value="false" <?php echo set_radio( 'enable_aptitudes', 'false'); ?> /> 
						Not Allowed
					</label>
					<div class="form-error"><?php echo form_error('enable_aptitudes'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Behavioural Aptitudes Layout Type <br />
						<small>
							Grid: display by checking scores against corresponding keys.
							<a class="underline-link" href="<?php echo base_url('report_templates/template_3'); ?>" target="_blank">See Template 3</a>
							<br />
							List: display scores as list.
							<a class="underline-link" href="<?php echo base_url('report_templates/template_4'); ?>" target="_blank">See Template 4</a>
						</small>
					</label> <br />
					<label>
						<input type="radio" name="aptitude_display_type" value="true" <?php echo set_radio( 'aptitude_display_type', 'true'); ?> checked /> Grid 
					</label>
					<label class="m-l-10"><input type="radio" name="aptitude_display_type" value="false" <?php echo set_radio( 'aptitude_display_type', 'false'); ?> /> List 
					</label>
					<div class="form-error"><?php echo form_error('aptitude_display_type'); ?></div>
				</div>

			</section>



			<!-- General Report Card Settins -->
			<section id="general_report_card_settings" class="p-t-30">

				<h3 class="m-t-20">General Report Card Settings</h3>
				
				<div class="form-group">
					<label class="form-control-label">Previous Grades <small>(whether to allow production and display of previous grades on report card or not)</small></label> <br />
					<label>
						<input type="radio" name="show_previous_grade" value="true" <?php echo set_radio( 'show_previous_grade', 'true'); ?> checked /> Allowed
					</label>
					<label class="m-l-10"><input type="radio" name="show_previous_grade" value="false" <?php echo set_radio( 'show_previous_grade', 'false'); ?> /> 
						Not Allowed
					</label>
					<div class="form-error"><?php echo form_error('show_previous_grade'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Target Grades <small>(whether to allow production and display of target grades on report card or not)</small></label> <br />
					<label>
						<input type="radio" name="show_target_grade" value="true" <?php echo set_radio( 'show_target_grade', 'true'); ?> checked /> Allowed
					</label>
					<label class="m-l-10"><input type="radio" name="show_target_grade" value="false" <?php echo set_radio( 'show_target_grade', 'false'); ?> /> 
						Not Allowed
					</label>
					<div class="form-error"><?php echo form_error('show_target_grade'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show GP</label> <br />
					<label>
						<input type="radio" name="show_gp" value="true" <?php echo set_radio( 'show_gp', 'true'); ?> checked /> Yes
					</label>
					<label class="m-l-10"><input type="radio" name="show_gp" value="false" <?php echo set_radio( 'show_gp', 'false'); ?> /> 
						No
					</label>
					<div class="form-error"><?php echo form_error('show_gp'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show Subject Position <small>(whether to rank and display student scores per subject on report card or not)</small></label> <br />
					<label>
						<input type="radio" name="show_subject_position" value="true" <?php echo set_radio( 'show_subject_position', 'true'); ?> checked /> Yes
					</label>
					<label class="m-l-10"><input type="radio" name="show_subject_position" value="false" <?php echo set_radio( 'show_subject_position', 'false'); ?> /> 
						No
					</label>
					<div class="form-error"><?php echo form_error('show_subject_position'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show Class Minimum<small></small></label> <br />
					<label>
						<input type="radio" name="show_class_min" value="true" <?php echo set_radio( 'show_class_min', 'true'); ?> checked /> Yes
					</label>
					<label class="m-l-10"><input type="radio" name="show_class_min" value="false" <?php echo set_radio( 'show_class_min', 'false'); ?> /> 
						No
					</label>
					<div class="form-error"><?php echo form_error('show_class_min'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show Class Maximum<small></small></label> <br />
					<label>
						<input type="radio" name="show_class_max" value="true" <?php echo set_radio( 'show_class_max', 'true'); ?> checked /> Yes
					</label>
					<label class="m-l-10"><input type="radio" name="show_class_max" value="false" <?php echo set_radio( 'show_class_max', 'false'); ?> /> 
						No
					</label>
					<div class="form-error"><?php echo form_error('show_class_max'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show Class Average<small></small></label> <br />
					<label>
						<input type="radio" name="show_class_average" value="true" <?php echo set_radio( 'show_class_average', 'true'); ?> checked /> Yes
					</label>
					<label class="m-l-10"><input type="radio" name="show_class_average" value="false" <?php echo set_radio( 'show_class_average', 'false'); ?> /> 
						No
					</label>
					<div class="form-error"><?php echo form_error('show_class_average'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show Performance Summary <small>(whether to show summary of performances in subjects on report card or not)</small></label> <br />
					<label>
						<input type="radio" name="show_performance_summary" value="true" <?php echo set_radio( 'show_performance_summary', 'true'); ?> checked /> Yes
					</label>
					<label class="m-l-10"><input type="radio" name="show_performance_summary" value="false" <?php echo set_radio( 'show_performance_summary', 'false'); ?> /> 
						No
					</label>
					<div class="form-error"><?php echo form_error('show_performance_summary'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show Subject Teacher's Name on Report Card</label> <br />
					<label>
						<input type="radio" name="show_subject_teacher_name" value="true" <?php echo set_radio( 'show_subject_teacher_name', 'true'); ?> checked /> Yes
					</label>
					<label class="m-l-10">
						<input type="radio" name="show_subject_teacher_name" value="false" <?php echo set_radio( 'show_subject_teacher_name', 'false'); ?> /> No
					</label>
					<div class="form-error"><?php echo form_error('show_subject_teacher_name'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show Subject Teacher's Comment on Report Card</label> <br />
					<label>
						<input type="radio" name="show_subject_teacher_comment" value="true" <?php echo set_radio( 'show_subject_teacher_comment', 'true'); ?> checked /> Yes
					</label>
					<label class="m-l-10">
						<input type="radio" name="show_subject_teacher_comment" value="false" <?php echo set_radio( 'show_subject_teacher_comment', 'false'); ?> /> No
					</label>
					<div class="form-error"><?php echo form_error('show_subject_teacher_comment'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show Class Teacher's name on Report Card</label> <br />
					<label>
						<input type="radio" name="show_class_teacher_name" value="true" <?php echo set_radio( 'show_class_teacher_name', 'true'); ?> /> Yes
					</label>
					<label class="m-l-10">
						<input type="radio" name="show_class_teacher_name" value="false" <?php echo set_radio( 'show_class_teacher_name', 'false'); ?> checked /> No
					</label>
					<div class="form-error"><?php echo form_error('show_class_teacher_name'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show Head Teacher's Name on Report Card</label> <br />
					<label>
						<input type="radio" name="show_head_teacher_name" value="true" <?php echo set_radio( 'show_head_teacher_name', 'true'); ?> /> Yes
					</label>
					<label class="m-l-10">
						<input type="radio" name="show_head_teacher_name" value="false" <?php echo set_radio( 'show_head_teacher_name', 'false'); ?> checked /> No
					</label>
					<div class="form-error"><?php echo form_error('show_head_teacher_name'); ?></div>
				</div>

				<div class="form-group">
					<label class="form-control-label">Show Student's Passport on Report Card</label> <br />
					<label>
						<input type="radio" name="show_student_passport" value="true" <?php echo set_radio( 'show_student_passport', 'true'); ?> checked /> Yes
					</label>
					<label class="m-l-10">
						<input type="radio" name="show_student_passport" value="false" <?php echo set_radio( 'show_student_passport', 'false'); ?> /> No
					</label>
					<div class="form-error"><?php echo form_error('show_student_passport'); ?></div>
				</div>

			</section>


			
			<div id="status_msg"></div>
			
			<div>
				<button class="btn btn-primary btn-lg">Submit </button>
			</div>

		<?php echo form_close(); ?>

	</div>
</div>
