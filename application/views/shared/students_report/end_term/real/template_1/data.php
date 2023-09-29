
			<?php 
			//require general template report header
			require "application/views/shared/students_report/end_term/layout/report_header_{$template_id}.php"; ?>
			

			<div class="report_categories">
				<div class="cat_header">
					<div class="cat_letter">A</div>
					<div class="cat_title">Performance in Subjects</div>
				</div>
			</div><!--/.report_categories-->
			
			<table class="report_table">
				
				<tr class="report_subjects">
					<th class="legend">Grade Key</th>
					<th class="empty"></th>

					<?php //Note: rowspan should be total no of grade keys + 1 (1 is for the GRADE KEY title) ?>
					
					<th rowspan="<?php echo $rowspan; ?>" class="rotate_first">
						<div class="vheader">Max. Obtainable %</div>
					</th>

					<?php 
					//Test Scores: Subjects
					foreach ($test_scores as $t) { ?>
						<th rowspan="<?php echo $rowspan; ?>" class="rotate">
							<?php $subject = $this->common_model->get_subject_details($t->subject_id)->subject; ?>
							<div class="vheader"><span><?php echo $subject; ?></span></div>
						</th>
					<?php } ?>

					<th rowspan="<?php echo $rowspan; ?>" class="empty"></th>
					
					<th rowspan="<?php echo $rowspan; ?>" class="rotate_extra">
						<div class="vheader">Max. Total</div>
					</th>
					<th rowspan="<?php echo $rowspan; ?>" class="rotate_extra">
						<div class="vheader">Total Scored</div>
					</th>
					<th rowspan="<?php echo $rowspan; ?>" class="rotate_percent">
						<div class="vheader">Percentage</div>
					</th>
					<th rowspan="<?php echo $rowspan; ?>" class="rotate_extra">
						<div class="vheader">Remark</div>
					</th>	
				</tr>
			

				<?php 
				foreach ($report_evaluation as $g) { ?>
					<tr>
						<?php 
						//get grade keys in the format: Grade - Evaluation StartRange% - EndRange%
						//Example: A - Excellent 90% - 100%
						$grade_range = $g->range;
						//explode range into start and end ranges
						$grade_range = explode("-", $grade_range);
						$start_range = $grade_range[0];
						$end_range = $grade_range[1];
						//append % to start and end ranges
						$range = $start_range . '% - ' . $end_range . '%';
						$grade_key = $g->grade . ' - ' . $g->evaluation . ' ' . $range; ?>
						<td class="legend"><?php echo $grade_key; ?></td>
						<td></td>
					</tr>
				<?php } ?>


				<tr>
					<td class="legend">Continuous Assessment</td><!--Grade Key-->
					<td class="empty"></td><!--Empty column before subjects-->
					<td><?php echo $ca_max_score; ?></td><!--Max. Obtainable CA % score: as set by admin -->
					
					<?php 
					//Test Scores: CA scores
					foreach ($test_scores as $t) { ?>
						<td><?php echo $t->ca_score; ?></td>
					<?php } ?>

					<td class="empty"></td><!--Empty column after subjects-->
					<td rowspan="5"><?php echo $total_possible_score; ?></td><!--All Total: spans 3 rows-->
					<td rowspan="5"><?php echo $overall_total_score; ?></td><!--Total Scored: spans 3 rows-->
					<td rowspan="5"><?php echo $overall_percentage_score; ?></td><!--Percentage: spans 3 rows-->
					<td rowspan="5" class="sums"></td><!--Remarks: spans 3 rows-->
				</tr>
				
				<tr>
					<td class="legend">Examination Scores</td><!--Grade Key-->
					<td class="empty"></td><!--Empty column before subjects-->
					<td><?php echo $exam_max_score; ?></td><!--Max. Obtainable Exam % score: as set by admin -->
					
					<?php 
					//Test Scores: Exam scores
					foreach ($test_scores as $t) { ?>
						<td><?php echo $t->exam_score; ?></td>
					<?php } ?>

					<td class="empty"></td><!--Empty column after subjects-->
				</tr>
				
				<tr>
					<td class="legend">Total</td><!--Total (CA + Exam)-->
					<td class="empty"></td><!--Empty column before subject scores-->
					<td>100</td><!--Max. Obtainable 100%:-->
					
					<?php 
					//Test Scores: Total scores
					foreach ($test_scores as $t) { 
						$total_test_scores = $t->ca_score + $t->exam_score; ?>
						<td><?php echo $total_test_scores; ?></td>
					<?php } ?>

					<td class="empty"></td><!--Empty column after subject scores-->
				</tr>

				<tr>
					<td class="legend">Subject Grade</td>
					<td class="empty"></td><!--Empty column before subject grades-->
					<td>*</td>
					
					<?php 
					//Subject grade score
					foreach ($test_scores as $t) { 
						$total_test_scores = $t->ca_score + $t->exam_score;
						$subject_grade = $this->students_report_model->get_subject_score_data($t->session, $t->term, $t->class_id, $t->student_id, $total_test_scores, 'grade'); ?> 
						<td><?php echo $subject_grade; ?></td>
					<?php } ?>
 
					<td class="empty"></td><!--Empty column after subject grades-->
				</tr>
				
				<tr>
					<td class="legend">Class Average</td>
					<td class="empty"></td><!--Empty column before subject scores-->
					<td>100</td><!--Max. Class Average:-->
					
					<?php 
					//Class Average
					foreach ($test_scores as $t) { 
						$class_average = $this->students_report_model->get_class_subject_average($t->session, $t->term, $t->class_id, $t->subject_id); ?> 
						<td><?php echo $class_average; ?></td>
					<?php } ?>
 
					<td class="empty"></td><!--Empty column after subject scores-->
				</tr>
			
			
			</table>
			
			
			<div class="report_categories">
				<div class="cat_header">
					<div class="cat_letter">B</div>
					<div class="cat_title">Attendance</div>
				</div>
			</div><!--/.report_categories-->
			
			<table class="report_table">
					
				<tr class="report_attendance">
					<th class="legend">Attendance</th>
					<th class="text-center">School</th>
					<th class="text-center">Sports</th>
					<th class="text-center">Organised Activities</th>
					<th class="text-center">Other</th>	
				</tr>
				
				<tr class="report_attendance">
					<th class="legend">No. of times held</th>
					<td><?php echo $att_total; ?></td>
					<td class="empty"></td>
					<td class="empty"></td>
					<td class="empty"></td>
				</tr>

				<tr class="report_attendance">
					<td class="legend">No. of times present</td>
					<td><?php echo $att_present; ?></td>
					<td class="empty"></td>
					<td class="empty"></td>
					<td class="empty"></td>
				</tr>
					
				<tr class="report_attendance">
					<td class="legend">Remarks</td>
					<td><?php echo $attendance_remark; ?></td>
					<td class="empty"></td>
					<td class="empty"></td>
					<td class="empty"></td>
				</tr>
					
			</table>
			
			
			
			<?php 
			//check if behavioural aptitude is enable and display if true
			if ($enable_aptitudes == 'true') { ?>

				<div class="report_categories">
					<div class="cat_header">
						<div class="cat_letter">C</div>
						<div class="cat_title">Behavioural Aptitude</div>
					</div>
				</div><!--/.report_categories-->
				
				<table class="report_table">
					
					<tr class="report_behaviours">
						<th class="legend">Rating Key</th>
						<?php 
						//Aptitude Scores: Aptitudes
						foreach ($aptitude_scores as $p) { ?>
							<th class="rotate">
								<?php $aptitude = $this->common_model->get_aptitude_details($p->aptitude_id)->aptitude; ?>
								<div class="vheader"><span><?php echo $aptitude; ?></span></div>
							</th>
						<?php } ?>
						
						<th class="rotate">
							<div class="vheader">Remarks</div>
						</th>
					</tr>

					
					<tr>
						<td class="legend">Excellent 5</td>
						<?php 
						//Aptitude Scores: Scores
						foreach ($aptitude_scores as $p) { 
							//if score is 5, check fields
							if ($p->score == 5) { ?>
								<td><i class="fa fa-check"></i></td>
							<?php } else { ?>
								<td class="empty"></td>
							<?php } ?>
						<?php } ?>
						<td rowspan="5" class="empty"></td>
					</tr>


					<tr>
						<td class="legend">Good 4</td>
						<?php 
						//Aptitude Scores: Scores
						foreach ($aptitude_scores as $p) { 
							//if score is 4, check fields
							if ($p->score == 4) { ?>
								<td><i class="fa fa-check"></i></td>
							<?php } else { ?>
								<td class="empty"></td>
							<?php } ?>
						<?php } ?>
					</tr>

						
					<tr>
						<td class="legend">Average 3</td>
						<?php 
						//Aptitude Scores: Scores
						foreach ($aptitude_scores as $p) { 
							//if score is 3, check fields
							if ($p->score == 3) { ?>
								<td><i class="fa fa-check"></i></td>
							<?php } else { ?>
								<td class="empty"></td>
							<?php } ?>
						<?php } ?>
					</tr>


					<tr>
						<td class="legend">Poor 2</td>
						<?php 
						//Aptitude Scores: Scores
						foreach ($aptitude_scores as $p) { 
							//if score is 2, check fields
							if ($p->score == 2) { ?>
								<td><i class="fa fa-check"></i></td>
							<?php } else { ?>
								<td class="empty"></td>
							<?php } ?>
						<?php } ?>
					</tr>


					<tr>
						<td class="legend">Very Poor 1</td>
						<?php 
						//Aptitude Scores: Scores
						foreach ($aptitude_scores as $p) { 
							//if score is 1, check fields
							if ($p->score == 1) { ?>
								<td><i class="fa fa-check"></i></td>
							<?php } else { ?>
								<td class="empty"></td>
							<?php } ?>
						<?php } ?>
					</tr>

					
				</table>

			<?php } ?>
			
			
			<?php 
			//require general template report footer
			require 'application/views/shared/students_report/end_term/layout/report_footer.php'; ?>
		