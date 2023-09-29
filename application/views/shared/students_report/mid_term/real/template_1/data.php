
		<?php 
		//require general template report header
		require "application/views/shared/students_report/mid_term/layout/report_header_{$template_id}.php"; ?>
			
		

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
						<div class="vheader">Max. Total (converted)</div>
					</th>
					<th rowspan="<?php echo $rowspan; ?>" class="rotate_extra">
						<div class="vheader">Total Scored (converted)</div>
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
					<td class="legend">Classwork</td>
					<td class="empty"></td><!--Empty column before subjects-->
					<td><?php echo $mt_classwork; ?></td><!--Max. Obtainable classwork % score: as set by admin -->
					
					<?php 
					//Test Scores: Classwork
					foreach ($test_scores as $t) { 
						$classwork = $t->classwork; ?>
						<td><?php echo $classwork; ?></td>
					<?php } ?>

					<td class="empty"></td><!--Empty column after subjects-->
					<td rowspan="6"><?php echo $total_possible_score; ?></td><!--All Total: spans 3 rows-->
					<td rowspan="6"><?php echo number_format($overall_total_score); ?></td><!--Total Scored: spans 3 rows-->
					<td rowspan="6"><?php echo $overall_percentage_score; ?></td><!--Percentage: spans 3 rows-->
					<td rowspan="6" class="sums"></td><!--Remarks: spans 3 rows-->
				</tr>
				
				<tr>
					<td class="legend">Homework</td>
					<td class="empty"></td><!--Empty column before subjects-->
					<td><?php echo $mt_homework; ?></td><!--Max. Obtainable Homework score: as set by admin -->
					
					<?php 
					//Test Scores: Homework
					foreach ($test_scores as $t) { 
						$homework = $t->homework; ?>
						<td><?php echo $homework; ?></td>
					<?php } ?>

					<td class="empty"></td><!--Empty column after subjects-->
				</tr>

				<tr>
					<td class="legend">Tests</td>
					<td class="empty"></td><!--Empty column before subjects-->
					<td><?php echo $mt_tests; ?></td><!--Max. Obtainable Tests score: as set by admin -->
					
					<?php 
					//Test Scores: Tests
					foreach ($test_scores as $t) { 
						$tests = $t->tests; ?>
						<td><?php echo $tests; ?></td>
					<?php } ?>

					<td class="empty"></td><!--Empty column after subjects-->
				</tr>
				
				<tr>
					<td class="legend">Total</td>
					<td class="empty"></td><!--Empty column before subject scores-->
					<td><?php echo $mt_total; ?></td><!--Max. Obtainable mid-term score :-->
					
					<?php 
					//Test Scores: Raw total scores
					foreach ($test_scores as $t) { 
						$classwork = $t->classwork; 
						$homework = $t->homework; 
						$tests = $t->tests; 
						$subject_total = $this->students_mid_term_report_model->get_subject_unconverted_total_score($classwork, $homework, $tests); ?>
						<td><?php echo $subject_total; ?></td>
					<?php } ?>

					<td class="empty"></td><!--Empty column after subject scores-->
				</tr>

				<tr>
					<td class="legend">Converted Total (%)</td>
					<td class="empty"></td><!--Empty column before subject scores-->
					<td>100</td><!--Max. Obtainable 100%:-->
					
					<?php 
					//Test Scores: Percentage total scores
					foreach ($test_scores as $t) { 
						$classwork = $t->classwork; 
						$homework = $t->homework; 
						$tests = $t->tests; 
						$subject_total = $this->students_mid_term_report_model->get_subject_unconverted_total_score($classwork, $homework, $tests);
						$percentage_score = $this->students_mid_term_report_model->get_subject_percentage_score($class_id, $subject_total); ?>
						<td><?php echo number_format($percentage_score, 1); ?></td>
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
						$classwork = $t->classwork; 
						$homework = $t->homework; 
						$tests = $t->tests; 
						$subject_total = $this->students_mid_term_report_model->get_subject_unconverted_total_score($classwork, $homework, $tests);
						$percentage_score = $this->students_mid_term_report_model->get_subject_percentage_score($class_id, $subject_total);
						$subject_grade = $this->students_mid_term_report_model->get_subject_score_data($percentage_score, 'grade'); ?>
						<td><?php echo $subject_grade; ?></td>
					<?php } ?>
 
					<td class="empty"></td><!--Empty column after subject grades-->
				</tr>
				
			</table>
			
			
			<?php 
			//require general template report footer
			require 'application/views/shared/students_report/mid_term/layout/report_footer.php'; ?>
			