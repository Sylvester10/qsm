									<?php
									$subject = $this->common_model->get_subject_details($t->subject_id)->subject; 
									$previous_grade = $this->students_report_model->get_previous_grade($t->session, $t->term, $t->class_id, $t->student_id, $t->subject_id);
									$target_grade = $this->students_report_model->get_target_grade($t->session, $t->term, $t->class_id, $t->student_id, $t->subject_id);
									$ca_scores = $t->ca_score; 
									$exam_scores = $t->exam_score;
									$total_score = $t->ca_score + $t->exam_score;
									$subject_grade = $this->students_report_model->get_subject_score_data($t->session, $t->term, $t->class_id, $t->student_id, $total_score, 'grade');
									$grade_point = $this->students_report_model->get_subject_score_data($t->session, $t->term, $t->class_id, $t->student_id, $total_score, 'gp').'.0';
									$subject_position = $this->students_report_model->get_subject_position($t->session, $t->term, $t->class_id, $t->student_id, $t->subject_id);
									$class_min = $this->students_report_model->get_class_subject_minimum_score($t->session, $t->term, $t->class_id, $t->subject_id);
									$class_max = $this->students_report_model->get_class_subject_maximum_score($t->session, $t->term, $t->class_id, $t->subject_id);
									$class_average = $this->students_report_model->get_class_subject_average($t->session, $t->term, $t->class_id, $t->subject_id); 
									$subject_teacher = $this->students_report_model->get_subject_teacher_name($t->id);
									$subject_comment = $t->subject_comment; 
									?>


									<tr>

										<td class="align_left"><small><?php echo $subject; ?></small></td>
										<?php if ($show_previous_grade == 'true') { ?>
											<td><?php echo $previous_grade; ?></td>
										<?php } ?>
										<?php if ($show_target_grade == 'true') { ?>
											<td><?php echo $target_grade; ?></td>
										<?php } ?>
										<td><?php echo $ca_scores; ?></td>
										<td><?php echo $exam_scores; ?></td>
										<td><?php echo $total_score; ?></td>
										<td><?php echo $subject_grade; ?></td>
										<?php if ($show_gp == 'true') { ?>
											<td class="align_left"><small><?php echo $grade_point; ?></small></td>
										<?php } ?>
										<?php if ($show_subject_position == 'true') { ?>
											<td><?php echo $subject_position; ?></td>
										<?php } ?>
										<?php if ($show_class_min == 'true') { ?>
											<td><?php echo $class_min; ?></td>
										<?php } ?>
										<?php if ($show_class_min == 'true') { ?>
											<td><?php echo $class_max; ?></td>
										<?php } ?>
										<?php if ($show_class_average == 'true') { ?>
											<td><?php echo $class_average; ?></td>
										<?php } ?>
										<?php if ($show_subject_teacher_name == 'true') { ?>
											<td class="align_left"><small><?php echo $subject_teacher; ?></small></td>
										<?php } ?>
										<?php if ($show_subject_teacher_comment == 'true') { ?>
											<td class="align_left"><small><?php echo $subject_comment; ?></small></td>
										<?php } ?>

									</tr>