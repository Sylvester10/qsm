					<tr class="summary">
						<td colspan="<?php echo $summary_colspan; ?>">
							<div class="pull-right">
								Final Percentage Score: <?php echo $overall_percentage_score; ?>
							</div>
							<div class="">
								Final Total Score (converted): <?php echo number_format($overall_total_score); ?>
							</div>	
						</td>
					</tr>


					<tr class="summary">
						<td colspan="<?php echo $summary_colspan; ?>">

							<div class="">
								<span class="text-bold">Grading System</span>
								<?php
								$gs_ps = ($show_performance_summary == 'true') ? ', Summary of Performance in Subjects' : NULL; ?>
								(Grade, Range, Evaluation/Remark<?php echo $gs_ps; ?>)	
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
										$evaluation = $e[2];
										$performance_summary = $e[3];
										//explode range into start and end ranges
										$range = explode("-", $range);
										$start_range = $range[0];
										$end_range = $range[1];
										//append % to start and end ranges
										$range = $start_range . '% - ' . $end_range . '%';
										$grade_key = $grade . '. ' . $range . ' - ' . $evaluation;
										if ($show_performance_summary == 'true') {
											$grade_key .= ' <span class="text-bold">(' . $performance_summary . ')</span>';
										}
										echo $grade_key; ?>

									</div>

								<?php } ?>
								
							</div>

						</td>
					</tr>