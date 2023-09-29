<?php 
	//View Child's Profile
	echo $this->school_parent_model->modal_select_child('select_child_profile', 'View Child\'s Profile', 'View', 'school_parent/child_profile', 'student');


	//View Child's Attendance
	echo $this->school_parent_model->modal_select_child('select_child_attendance', 'View Child\'s Attendance', 'View', 'school_parent/child_attendance', 'student');


	//Child's Homework
	echo $this->school_parent_model->modal_select_child('select_child_homework', 'View Child\'s Homework', 'View', 'homework_parent/homework', 'class');


	//Lesson Period
	echo $this->school_parent_model->modal_select_child('select_child_lesson_periods', 'Lesson Periods', 'View', 'timetable_parent/lesson_periods', 'class');
	//Test Schedules  
	echo $this->school_parent_model->modal_select_child('select_child_test_schedules', 'Test Schedules', 'View', 'timetable_parent/test_schedules', 'class'); 
	//Exam Schedules  
	echo $this->school_parent_model->modal_select_child('select_child_exam_schedules', 'Exam Schedules', 'View', 'timetable_parent/exam_schedules', 'class');  


	//Child's Result
	//bespoke for kad academy
	if (school_id == kad_school_id) {
		$mt_reports_controller = 'kad_student_mid_term_reports_parent';
		$et_reports_controller = 'kad_student_end_term_reports_parent';
	} else {
		$mt_reports_controller = 'student_mid_term_reports_parent';
		$et_reports_controller = 'student_reports_parent';
	} 
	//Mid-Term
	echo $this->school_parent_model->modal_select_child('select_child_mid_term_result', 'Check Mid-Term Result', 'Proceed', $mt_reports_controller.'/check_result', 'student');
	//End-of-Term
	echo $this->school_parent_model->modal_select_child('select_child_end_term_result', 'Check End-of-Term Result', 'Proceed', $et_reports_controller.'/check_result', 'student');


?>