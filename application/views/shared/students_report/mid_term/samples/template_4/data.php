			
			<?php 
			//require general template report header
			require "application/views/shared/students_report/mid_term/layout/report_header_{$template_id}.php"; ?>

			
	
			<table class="report_table template2">

				<?php 
				//require test score header
				require "application/views/shared/students_report/mid_term/samples/includes/test_score_header_typeA.php"; ?>


				<tbody>


					<?php
					//check if subject grouping was enabled
					if ($enable_subject_grouping == 'true') { 

					
						//Subject groups
						foreach ($subject_groups as $sg_id => $subject_group) { 

							//check if any subject exists for the current subject group ?>

							<tr>
								<td colspan="<?php echo $summary_colspan; ?>" class="align_left text-bold"><?php echo $subject_group; ?></td>
							</tr>

							<?php 
							//Test Scores
							foreach ($test_scores as $t) { 

								$subject_group_id = $t[8]; 

								//check if subject is under the current subject group
								if ($sg_id == $subject_group_id) {

									//require test score variables
									require "application/views/shared/students_report/mid_term/samples/includes/test_score_data_typeA.php"; 				

								} //endif $sg_id == $subject_group_id

							} //endforeach test scores

						} //endforeach subject groups


					} else { //subject grouping is disabled


						//Test Scores
						foreach ($test_scores as $t) {

							//require test score variables
							require "application/views/shared/students_report/mid_term/samples/includes/test_score_data_typeA.php"; 			
						} //endforeach test scores


					} //end of subject grouping check ?>


					
					<?php 
					//require test score summary
					require "application/views/shared/students_report/mid_term/samples/includes/test_score_summary_typeA.php"; ?>
					

				</tbody>
			
			</table>
			
			

			<?php 
			//require general template report footer
			require 'application/views/shared/students_report/mid_term/layout/report_footer.php'; ?>

		