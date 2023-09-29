				
				<?php
				$summary_colspan = 5;
				//add 8 to summary colspan, incase 
					//previous grade, 
					//target grade, 
					//GP, 
					//position
					//class min, 
					//class max, 
					//class average, 
					//subject teacher name,
					//subject teacher comment 
				//are enabled)
				$summary_colspan += 9; ?>


				<thead>

					<tr class="text-bold">
						<td colspan="<?php echo $summary_colspan; ?>">
							PERFORMANCE IN SUBJECTS
						</td>
					</tr>

					<tr class="">
						<th class="align_left min-w-100">Subject</th>
						<?php if ($show_previous_grade == 'true') { ?>
							<th>Prev. <br /> Grade</th>
						<?php } ?>
						<?php if ($show_target_grade == 'true') { ?>
							<th>Target <br /> Grade</th>
						<?php } ?>
						<th>CA <br /> (<?php echo $ca_max_score; ?>)</th>
						<th>Exam <br /> (<?php echo $exam_max_score; ?>)</th>
						<th>Total <br /> (100)</th>
						<th>Grade</th>
						<?php if ($show_gp == 'true') { ?>
							<th>GP</th>
						<?php } ?>
						<?php if ($show_subject_position == 'true') { ?>
							<th>Position</th>
						<?php } ?>
						<?php if ($show_class_min == 'true') { ?>
							<th>Class <br /> Min.</th>
						<?php } ?>
						<?php if ($show_class_max == 'true') { ?>
							<th>Class <br /> Max.</th>
						<?php } ?>
						<?php if ($show_class_average == 'true') { ?>
							<th>Class <br /> Average</th>
						<?php } ?>
						<?php if ($show_subject_teacher_name == 'true') { ?>
							<th class="align_left min-w-100">Subject Teacher</th>
						<?php } ?>
						<?php if ($show_subject_teacher_comment == 'true') { ?>
							<th class="align_left min-w-200">Teacher's Comment</th>
						<?php } ?>
					</tr>
				</thead>