						
						<tr>

							<?php
							$subject = $t[0]; 
							$previous_grade = $t[1]; 
							$target_grade = $t[2]; 
							$classwork = $t[3]; 
							$homework = $t[4]; 
							$tests = $t[5]; 
							$subject_position = $t[6]; 
							$subject_teacher = $t[7]; 
							$subject_total = $this->mid_term_report_templates_model->get_subject_total_score($classwork, $homework, $tests);
							$percentage_score = $this->mid_term_report_templates_model->get_subject_percentage_score($subject_total);
							$subject_grade = $this->mid_term_report_templates_model->get_subject_score_data($percentage_score, 'grade'); 
							$subject_comment = $this->mid_term_report_templates_model->get_subject_score_data($percentage_score, 'comment'); ?>	

							<td class="align_left"><small><?php echo $subject; ?></small></td>
							<?php if ($show_previous_grade == 'true') { ?>
								<td><?php echo $previous_grade; ?></td>
							<?php } ?>
							<?php if ($show_target_grade == 'true') { ?>
								<td><?php echo $target_grade; ?></td>
							<?php } ?>
							<td><?php echo $classwork; ?></td>
							<td><?php echo $homework; ?></td>
							<td><?php echo $tests; ?></td>
							<td><?php echo $subject_total; ?></td>
							<td><?php echo number_format($percentage_score, 1); ?></td>
							<td><?php echo $subject_grade; ?></td>
							<?php if ($show_subject_position == 'true') { ?>
								<td><?php echo $subject_position; ?></td>
							<?php } ?>
							<?php if ($show_subject_teacher_name == 'true') { ?>
								<td class="align_left"><small><?php echo $subject_teacher; ?></small></td>
							<?php } ?>
							<?php if ($show_subject_teacher_comment == 'true') { ?>
								<td class="align_left"><small><?php echo $subject_comment; ?></small></td>
							<?php } ?>

						</tr>