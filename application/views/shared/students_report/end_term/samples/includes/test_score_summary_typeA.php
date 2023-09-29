					
					<tr class="summary">
						<td colspan="<?php echo $summary_colspan; ?>">
							<div class="pull-right">
								Final Percentage Score: <?php echo number_format($overall_percentage_score, 1); ?>%
								<?php if ($show_class_average == 'true') { ?>
									<br />
									Class Percentage Average: <?php echo $class_percentage_average; ?>
								<?php } ?>
								<?php if ($ranking == 'true') { ?>
									<br />
									Position: <?php echo $position; ?>
								<?php } ?>
							</div>
							<div class="">
								Final Total Score: <?php echo number_format($overall_total_score); ?>
								<br />
								Overall Grade: <?php echo $overall_grade; ?>
								<?php if ($show_gp == 'true') { ?>
									<br />
									Term GPA: <?php echo $gpa; ?>
								<?php } ?>
							</div>	
						</td>
					</tr>
					

					<tr class="summary">
						<td colspan="<?php echo $summary_colspan; ?>">

							<div class="">
								<span class="text-bold">Grading System</span>
								<?php
								$gs_gp = ($show_gp == 'true') ? ', GP' : NULL;
								$gs_ps = ($show_performance_summary == 'true') ? ', Summary of Performance in Subjects' : NULL; ?>
								(Grade, Range, Evaluation/Remark<?php echo $gs_gp; ?><?php echo $gs_ps; ?>)	
							</div>

							<div class="row">

								<?php
								//sort in descending order according to key 
								krsort($report_evaluation);
								foreach ($report_evaluation as $e) { ?>

									<div class="col-md-3">

										<?php 
										//get grade keys in the format: Grade - Evaluation StartRange% - EndRange%
										//Example: A - Excellent 90% - 100%
										$range = $e[0];
										$grade = $e[1];
										$gp = $e[2];
										$evaluation = $e[3];
										$performance_summary = $e[4];
										//explode range into start and end ranges
										$range = explode("-", $range);
										$start_range = $range[0];
										$end_range = $range[1];
										//append % to start and end ranges
										$range = $start_range . '% - ' . $end_range . '%';
										$grade_key = $grade . '. ' . $range . ' - ' . $evaluation;
										if ($show_gp == 'true') {
											$grade_key .= " - {$gp}.0";
										}
										if ($show_performance_summary == 'true') {
											$grade_key .= ' <span class="text-bold">(' . $performance_summary . ')</span>';
										}
										echo $grade_key; ?>

									</div>

								<?php } ?>
								
							</div>

						</td>
					</tr>