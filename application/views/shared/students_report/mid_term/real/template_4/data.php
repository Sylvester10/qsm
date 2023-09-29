			
			<?php 
			//require general template report header
			require "application/views/shared/students_report/mid_term/layout/report_header_{$template_id}.php"; ?>

			
	
			<table class="report_table template2">

				<?php 
				//require test score header
				require "application/views/shared/students_report/mid_term/real/includes/test_score_header_typeA.php"; ?>


				<tbody>


					<?php
					//check if subject grouping was enabled
					if ($enable_subject_grouping == 'true') { 

					
						//Subject groups
						foreach ($subject_groups as $sg) { 

							$sg_id = $sg->id;
							$subject_group = $sg->subject_group;

							//check if any subject exists for the current subject group. This is to prevent subject groups that do not have subject with scores from being shown
							//get subject group ids as an array
							$subject_group_id_array = array();
							foreach ($test_scores as $t) { 
								$subject_group_id_array[] = $this->common_model->get_subject_details($t->subject_id)->subject_group_id;
							}

							if ( in_array($sg_id, $subject_group_id_array) ) { ?>

								<tr>
									<td colspan="<?php echo $summary_colspan; ?>" class="align_left text-bold"><?php echo $subject_group; ?></td>
								</tr>

							<?php } 

						
							//Test Scores
							foreach ($test_scores as $t) {

								$subject_group_id = $this->common_model->get_subject_details($t->subject_id)->subject_group_id;

								//check if subject is under the current subject group
								if ($sg_id == $subject_group_id) { 

									//require general template report header
									require "application/views/shared/students_report/mid_term/real/includes/test_score_data_typeA.php";

								} //endif ($sg_id == $subject_group_id) 

							} //endforeach test scores

						} //end foreach subject groups ?>


						<!-- Ungrouped Subjects -->
						<tr>
							<td colspan="<?php echo $summary_colspan; ?>" class="align_left text-bold">Ungrouped</td>
						</tr>

						<?php 
						//Ungrouped Test Scores
						foreach ($test_scores as $t) { 

							$subject_group_id = $this->common_model->get_subject_details($t->subject_id)->subject_group_id;
 
							if ($subject_group_id == NULL) { 

								//require test score variables
								require "application/views/shared/students_report/mid_term/real/includes/test_score_data_typeA.php"; 

							} //end if subject_group_id == NULL 

						} //endforeach (ungrouped) test scores


					} else { //subject grouping is disabled


						//Test Scores
						foreach ($test_scores as $t) {

							//require test score variables
							require "application/views/shared/students_report/mid_term/real/includes/test_score_data_typeA.php";

						} //endforeach test scores


					} //end of subject grouping check ?>


					
					<?php 
					//require test score summary
					require "application/views/shared/students_report/mid_term/real/includes/test_score_summary_typeA.php"; ?>


				</tbody>
			
			</table>

			
			
			<?php 
			//require general template report footer
			require 'application/views/shared/students_report/mid_term/layout/report_footer.php'; ?>

		