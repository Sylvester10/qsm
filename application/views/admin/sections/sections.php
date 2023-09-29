
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('sections/new_section'); ?>"><i class="fa fa-plus"></i> New Section</a>
	</div>

	<table id="table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
		
		<thead>
			<tr>
				<th> Actions </th>
				<th class="min-w-150"> Section </th>
				<th> Level </th>
				<th> No. of Classes </th>
				<th> No. of Subjects </th>  
				<th class="min-w-200"> Mid-Term Report Settings</th>
				<th class="min-w-150"> Report Settings (1)</th>
				<th class="min-w-250"> Report Settings (2)</th>
				<th class="min-w-250"> Report Settings (3)</th>
				<th class="min-w-150"> Report Template</th>
			</tr>
		</thead>
		<tbody>

			<?php
			foreach ($sections as $y) {
				$total_classes = $this->common_model->count_section_classes(school_id, $y->id); 
				$total_subjects = $this->common_model->count_section_subjects(school_id, $y->id); 

				$ranking = ($y->ranking == 'true') ? 'Allowed' : 'Not allowed';
				$subject_grouping = ($y->enable_subject_grouping == 'true') ? 'Allowed' : 'Not allowed';
				$previous_grade = ($y->show_previous_grade == 'true') ? 'Allowed' : 'Not allowed';
				$target_grade = ($y->show_target_grade == 'true') ? 'Allowed' : 'Not allowed';
				$behavioural_aptitudes = ($y->enable_aptitudes == 'true') ? 'Allowed' : 'Not allowed';
				$aptitude_display_type = ucfirst($y->aptitude_display_type);

				$show_class_min = ($y->show_class_min == 'true') ? 'Yes' : 'No';
				$show_class_max = ($y->show_class_min == 'true') ? 'Yes' : 'No';
				$show_class_average = ($y->show_class_average == 'true') ? 'Yes' : 'No';
				$show_subject_position = ($y->show_subject_position == 'true') ? 'Yes' : 'No';
				$show_performance_summary = ($y->show_performance_summary == 'true') ? 'Yes' : 'No';
				$show_subject_teacher_name = ($y->show_subject_teacher_name == 'true') ? 'Yes' : 'No';
				$show_subject_teacher_comment = ($y->show_subject_teacher_comment == 'true') ? 'Yes' : 'No';
				$show_class_teacher_name = ($y->show_class_teacher_name == 'true') ? 'Yes' : 'No';
				$show_head_teacher_name = ($y->show_head_teacher_name == 'true') ? 'Yes' : 'No';
				$show_student_passport = ($y->show_student_passport == 'true') ? 'Yes' : 'No';

				$mt_report_settings = 'Max Classwork Score: ' . $y->mt_classwork . '<br />';
				$mt_report_settings .= 'Max Homework Score: ' . $y->mt_homework . '<br />';
				$mt_report_settings .= 'Max Tests Score: ' . $y->mt_tests . '<br />';
				$mt_report_settings .= 'Total: ' . $y->mt_total . '<br />';
				$mt_class_teacher_comment = ($y->mt_class_teacher_comment == 'true') ? 'Allowed' : 'Not Allowed';
				$mt_report_settings .= 'Class Teacher Comment: ' . $mt_class_teacher_comment;
				
				$report_card_settings1 = 'Max CA Score: ' . $y->ca_max_score . '<br />';
				$report_card_settings1 .= 'Max Exam Score: ' . $y->exam_max_score . '<br />';
				$report_card_settings1 .= 'Passmark: ' . $y->pass_mark;

				$report_card_settings2 = 'Ranking/Position: ' . $ranking . '<br />';
				$report_card_settings2 .= 'Subject Grouping: ' . $subject_grouping . '<br />';
				$report_card_settings2 .= 'Previous Grades: ' . $previous_grade . '<br />';
				$report_card_settings2 .= 'Target Grades: ' . $target_grade . '<br />';
				$report_card_settings2 .= 'Behavioural Aptitudes: ' . $behavioural_aptitudes . '<br />';
				$report_card_settings2 .= 'Aptitudes Layout Type: ' . $aptitude_display_type;

				$report_card_settings3 = 'Show Class Minimum: ' . $show_class_min . '<br />';
				$report_card_settings3 .= 'Show Class Maximum: ' . $show_class_max . '<br />';
				$report_card_settings3 .= 'Show Class Average: ' . $show_class_average . '<br />';
				$report_card_settings3 .= 'Show Subject Position: ' . $show_subject_position . '<br />';
				$report_card_settings3 .= 'Show Performance Summary: ' . $show_performance_summary . '<br />';
				$report_card_settings3 .= 'Show Subject Teacher\'s Name: ' . $show_subject_teacher_name . '<br />';
				$report_card_settings3 .= 'Show Subject Teacher\'s Comment: ' . $show_subject_teacher_comment . '<br />';
				$report_card_settings3 .= 'Show Class Teacher\'s Name: ' . $show_class_teacher_name . '<br />';
				$report_card_settings3 .= 'Show Head Teacher\'s Name: ' . $show_head_teacher_name . '<br />';
				$report_card_settings3 .= 'Show Student\'s Passport: ' . $show_student_passport;

				$template_url = 'report_templates/template_'.$y->template_id;

				require "application/views/admin/sections/modals/section_actions.php"; 
				
				echo modal_delete_confirm($y->id, $y->section, 'section', 'sections/delete_section');  ?>

				<tr>	
					<td class="w-15-p text-center"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#options<?php echo $y->id; ?>"><i class="fa fa-navicon"></i></button></td>
					<td><?php echo $y->section; ?></td>
					<td><?php echo $y->level; ?></td>
					<td><?php echo $total_classes; ?></td>
					<td><?php echo $total_subjects; ?></td>
					<td><?php echo $mt_report_settings; ?></td>
					<td><?php echo $report_card_settings1; ?></td>
					<td><?php echo $report_card_settings2; ?></td>
					<td><?php echo $report_card_settings3; ?></td>
					<td>
						<div class="pull-right">
							<a class="btn btn-default btn-sm" href="<?php echo base_url($template_url); ?>" target="_blank" title="View Sample"><i class="fa fa-eye"></i></a>
						</div>
						Template <?php echo $y->template_id; ?>
					</td>
				</tr>

			<?php } ?>

		</tbody>
	</table>