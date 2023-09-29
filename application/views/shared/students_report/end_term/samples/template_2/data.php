			
			<?php 
			//require general template report header
			require "application/views/shared/students_report/end_term/layout/report_header_{$template_id}.php"; ?>


	
			<table class="report_table template2">

				<?php 
				//require test score header
				require "application/views/shared/students_report/end_term/samples/includes/test_score_header_typeA.php"; ?>


				<tbody>
					
					<?php 
					//Test Scores
					foreach ($test_scores as $t) {

						//require test score variables
						require "application/views/shared/students_report/end_term/samples/includes/test_score_data_typeA.php"; 

					} //endforeach test scores ?>


					<?php 
					//require test score summary
					require "application/views/shared/students_report/end_term/samples/includes/test_score_summary_typeA.php"; ?>
					

				</tbody>
			
			</table>
			
			
			
			<?php 
			//require aptitude score data type A (combined domains)
			require "application/views/shared/students_report/end_term/samples/includes/aptitude_score_data_typeA.php"; ?>
			

			
			<?php 
			//require general template report footer
			require 'application/views/shared/students_report/end_term/layout/report_footer.php'; ?>

		