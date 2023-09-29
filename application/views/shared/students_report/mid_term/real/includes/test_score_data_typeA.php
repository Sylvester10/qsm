						
						<tr>

							<?php
							$subject = $this->common_model->get_subject_details($t->subject_id)->subject; 
							$previous_grade = $this->students_mid_term_report_model->get_previous_grade($t->session, $t->term, $t->class_id, $t->student_id, $t->subject_id);
							$target_grade = $this->students_mid_term_report_model->get_target_grade($t->session, $t->term, $t->class_id, $t->student_id, $t->subject_id);
							$classwork = $t->classwork; 
							$homework = $t->homework;
							$tests = $t->tests;
							$subject_total = $this->students_mid_term_report_model->get_subject_unconverted_total_score($classwork, $homework, $tests);
							$percentage_score = $this->students_mid_term_report_model->get_subject_percentage_score($class_id, $subject_total);
							$subject_grade = $this->students_mid_term_report_model->get_subject_score_data($percentage_score, 'grade'); 
							$subject_comment = $t->subject_comment; 
							$subject_position = $this->students_mid_term_report_model->get_subject_position($t->session, $t->term, $t->class_id, $t->student_id, $t->subject_id);
							$subject_teacher = $this->students_mid_term_report_model->get_subject_teacher_name($t->id);
							?>


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