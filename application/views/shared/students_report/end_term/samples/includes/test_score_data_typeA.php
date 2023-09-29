						
						<tr>

							<?php
							$subject = $t[0]; 
							$previous_grade = $t[1]; 
							$target_grade = $t[2]; 
							$ca_score = $t[3]; 
							$exam_score = $t[4]; 
							$subject_position = $t[5]; 
							$class_min = $t[6]; 
							$class_max = $t[7]; 
							$class_average = $t[8]; 
							$subject_teacher = $t[9];
							$subject_total = $this->report_templates_model->get_subject_total_score($ca_score, $exam_score);
							$subject_grade = $this->report_templates_model->get_subject_score_data($subject_total, 'grade'); 
							$subject_grade_point = $this->report_templates_model->get_subject_score_data($subject_total, 'gp'); 
							$subject_comment = $this->report_templates_model->get_subject_score_data($subject_total, 'comment'); ?>

							<td class="align_left"><small><?php echo $subject; ?></small></td>
							<?php if ($show_previous_grade == 'true') { ?>
								<td><?php echo $previous_grade; ?></td>
							<?php } ?>
							<?php if ($show_target_grade == 'true') { ?>
								<td><?php echo $target_grade; ?></td>
							<?php } ?>
							<td><?php echo $ca_score; ?></td>
							<td><?php echo $exam_score; ?></td>
							<td><?php echo $subject_total; ?></td>
							<td><?php echo $subject_grade; ?></td>
							<?php if ($show_gp == 'true') { ?>
								<td class="align_left"><small><?php echo $subject_grade_point; ?>.0</small></td>
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