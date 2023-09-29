<table class="table table-no-border">

	<tr>

		<td>
			<div class="">
				<img class="report_school_logo" src="<?php echo school_logo; ?>" />
			</div>
		</td>

		<td>
			<div class="text-center">
				<h3 class="text-bold"><?php echo strtoupper(school_name); ?></h3>
				<i class="fa fa-map-marker"></i> <?php echo school_location; ?>. <i class="fa fa-phone"></i> <?php echo telephone_line; ?>
				<div class="text-bold">
					Motto: <em><?php echo $school_info->school_motto; ?></em>
				</div>
				<div class="text-bold">
					BROAD SHEET REPORT 
				</div>
			</div><!--/.report_header-->
		</td>

	</tr>

</table>


<table class="table table-no-border">
	<tr>
		<td>Class: <?php echo $class; ?></td>
		<td>No in Class: <?php echo $class_population; ?></td>
		<td>Session: <?php echo $the_session; ?></td>
		<td>Term: <?php echo $term; ?></td>
	</tr>
</table>


<div class="table-scroll m-t-25-n">
	<table class="broadsheet_table table-bordered">
		
		<tr>
			<td> S/N </td>
			<td class="min-w-150"> Admission ID </td>
			<td class="min-w-300"> Name </td>

			<?php
			//get test subject ids as an array
			$test_subject_id_array = array();
			foreach ($class_test_scores as $t) { 
				$test_subject_id_array[] = $t->subject_id;
			}

			foreach ($subjects as $s) {
				$subject_id = $s->id; 
				$subject_details = $this->common_model->get_subject_details($subject_id);
				$subject = $subject_details->subject;
				$subject_short = $subject_details->subject_short;

				//ensure only subjects that have scores will be pulled
				if ( in_array($subject_id, $test_subject_id_array) ) { ?>
					<td><?php echo $subject_short; ?></td>
				<?php }

			} ?>
			
			<td class=""></td>
			<td> No. of Subjects </td>
			<td> Total Score </td>
			<td> % Score </td>
			<td> Pos </td>
		</tr>
		

		<tbody>
			
			<?php 
			$count = 1;
			foreach ($class_results as $r) { 
				
				$student_id = $r->student_id;
				$admission_id = $this->common_model->get_student_details_by_id($student_id)->admission_id;
				$student_name = $this->common_model->get_student_fullname($student_id); 
				$total_scored = $r->total_scored;
				$percentage_score = $this->students_report_model->get_overall_percentage_score($session, $term, $class_id, $student_id); 
				$position = $this->students_report_model->get_student_position($session, $term, $class_id, $student_id); ?>

				<tr>
					<td><?php echo $count; ?></td>
					<td><?php echo $admission_id; ?></td>
					<td><?php echo $student_name; ?></td>

					<?php
					$test_scores = $this->students_report_model->get_test_scores($session, $term, $class_id, $student_id);
					//get test subject ids as an array
					$test_score_subject_id_array = array();
					$total_subjects_registered = 0;
					$line_chart_data_array = array();
					foreach ($test_scores as $t) { 
						$test_score_subject_id_array[] = $t->subject_id;
						$total_subjects_registered++;
						$subject_short = $this->common_model->get_subject_details($t->subject_id)->subject_short;
						$class_average_score = $this->students_report_model->get_class_subject_average($t->session, $t->term, $t->class_id, $t->subject_id);
						$class_min_score = $this->students_report_model->get_class_subject_minimum_score($t->session, $t->term, $t->class_id, $t->subject_id);
						$class_max_score = $this->students_report_model->get_class_subject_maximum_score($t->session, $t->term, $t->class_id, $t->subject_id);
						$line_chart_data_array[] = "{ subject: '{$subject_short}', class_average: {$class_average_score}, class_min: {$class_min_score}, class_max: {$class_max_score} }";
					} ?>

					<?php
					foreach ($subjects as $s) {
						$subject_id = $s->id; 
						$total_test_score = $this->students_report_model->get_subject_total_score($session, $term, $class_id, $student_id, $subject_id);
						//ensure only subjects that have scores for at least 1 student will be pulled
						if ( in_array($subject_id, $test_subject_id_array) ) { 
							//ensure only subjects that have scores will display	
							if ( in_array($subject_id, $test_score_subject_id_array) ) { ?>
								<td><?php echo $total_test_score; ?></td>
							<?php } else { ?>
								<td class="empty"></td>
							<?php } 
						}

					} ?>

					<td class=""></td>
					<td><?php echo $total_subjects_registered; ?></td>
					<td><?php echo $total_scored; ?></td>
					<td><?php echo $percentage_score; ?></td>
					<td><?php echo $position; ?></td>

				</tr>

				<?php $count++;

			} //endforeach ($class_results as $t) ?>

		</tbody>

	</table>

</div>



<div class="broadsheet_extra m-t-30">
	
	<table class="table table-no-border">

		<?php 
		$class_percentage_average = $this->students_report_model->get_class_broadsheet_percentage_average($session, $term, $class_id, $total_subjects_registered); 
		$class_highest_percentage_score = $this->students_report_model->get_class_broadsheet_highest_percentage_score($session, $term, $class_id, $total_subjects_registered);
		$class_lowest_percentage_score = $this->students_report_model->get_class_broadsheet_lowest_percentage_score($session, $term, $class_id, $total_subjects_registered); ?>

		<tr>
			<td>
				No of Students: <?php echo $class_population; ?>
				<br />
				Highest Average in Class: <?php echo $class_highest_percentage_score; ?>
			</td>
			<td>

				Class Average: <?php echo $class_percentage_average; ?>
				<br />
				Lowest Average in Class: <?php echo $class_lowest_percentage_score; ?>
			</td>
		</tr>

	</table>


	<div class="table-scroll">

		<table class="table table-no-border">

			<?php
			$grade_range_array = array(); 
			$percentage_count_array = array(); 
			$chart_data_array = array(); 
			$chart_data_string = ""; 
			//sort in descending order according to key
			krsort($report_evaluation);
			foreach ($report_evaluation as $e) { 

				//get grade keys in the format: Grade - Evaluation StartRange% - EndRange%
				//Example: A - Excellent 90% - 100%
				$range = $e->range;
				$grade = $e->grade;
				$gp = $e->gp;
				$evaluation = $e->evaluation;
				//explode range into start and end ranges
				$range = explode("-", $range);
				$start_range = $range[0];
				$end_range = $range[1];

				//calculate percentage summary
				$percentage_summary = array();
				foreach ($class_results as $r) {
					$student_id = $r->student_id;
					$percentage_score = $this->students_report_model->the_percentage_score($session, $term, $class_id, $student_id); 
					$percentage_score = intval($percentage_score);
					//check range of total score, then increment array by 1
					if ( in_array($percentage_score, range($start_range, $end_range)) ) {
						$percentage_summary[]++;
					}
				} 

				$percentage_summary_count = count($percentage_summary);
				if ($percentage_summary_count > 0) {	
					//concatenate range and grade in the format B (70-80)
					$grade_range = "'{$grade} ({$e->range})'";
					$grade_range_array[] = $grade_range; 
					//total percentage summary
					$pie_chart_data_array[] = "{value: {$percentage_summary_count}, name: {$grade_range}}";
				}
			} //endforeach ($report_evaluation as $e) ?>


			<?php
			//send the string values to javascript
			//Pie Chart
			$grade_range_string = implode(", ", $grade_range_array);
			$pie_chart_data_string = implode(", ", $pie_chart_data_array); 
			//Line Chart
			$line_chart_data_string = implode(", ", $line_chart_data_array); ?>

			
			<tr style="margin: auto">

				<td>
					<div id="broadsheet_pie_chart" style="width: 350px; height:300px;"></div>
				</td>

				<td class="text-center">
					
					<h4 style="color: #000; font-weight: 700; font-size: 20px">Subjects Performance Analysis</h4>
					
					<?php 
					$legend_data_array = array(
						'Class Minimum Score' => '#E65A26',
						'Class Maximum Score' => '#26B99A',
						'Class Average Score' => '#373651',
					);
					echo morris_chart_legend($legend_data_array); ?>

					<div id="broadsheet_line_chart" style="width: 900px; overflow-x: auto; overflow-y: hidden; height: 250px"></div>
					
				</td>

			</tr>

		</table>

	</div>
	
</div>



<?php
//require chart graphs 
require "application/views/shared/students_report/end_term/real/includes/broadsheet/graph_charts.php"; ?>		

