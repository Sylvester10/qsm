<?php 

	if ($timetable_module) { 
		//Lesson Periods
		echo modal_select_class(school_id, 'select_class_lesson_periods', 'Lesson Periods', 'timetable_staff/select_class_lesson_periods', 'View');  
		//Test Schedules  
		echo modal_select_class(school_id, 'select_class_test_schedules', 'Test Schedules', 'timetable_staff/select_class_test_schedules', 'View'); 
		//Exam Schedules  
		echo modal_select_class(school_id, 'select_class_exam_schedules', 'Exam Schedules', 'timetable_staff/select_class_exam_schedules', 'View');  
	}



	//Student Classes for Subject Teacher
	if ($student_management_module) { 
		//View Class
		echo modal_select_subject_teacher_class($staff_id, 'select_class_st_single_class', 'View Class', 'subject_teacher/view_single_class', 'View');  
	}



	//Homework for Subject Teacher
	if ($homework_module) { 
		//New Homework
		echo modal_select_subject_teacher_class($staff_id, 'select_class_st_new_homework', 'New Homework', 'homework_subject_teacher/select_class_new_homework', 'View');  
		//All Homework
		echo modal_select_subject_teacher_class($staff_id, 'select_class_st_all_homework', 'All Homework', 'homework_subject_teacher/select_class_all_homework', 'View');  
	}



	//Student Reports for Subject Teacher
	if ($student_reports_module) {
		//bespoke for kad academy
		if (school_id == kad_school_id) {
			$mt_reports_controller = 'kad_student_mid_term_reports_subject_teacher';
			$et_reports_controller = 'kad_student_end_term_reports_subject_teacher';
		} else {
			$mt_reports_controller = 'student_mid_term_reports_subject_teacher';
			$et_reports_controller = 'student_reports_subject_teacher';
		} 
		//Mid-Term Reports
		echo modal_select_subject_teacher_class($staff_id, 'select_class_st_mid_term_reports', 'Mid-Term Reports', $mt_reports_controller.'/select_class_reports', 'View');  
		//End-of-Term Reports
		echo modal_select_subject_teacher_class($staff_id, 'select_class_st_end_term_reports', 'End-of-Term Reports', $et_reports_controller.'/select_class_reports', 'View');  
	}




	if ($fee_management_module) { 

		//Collect Fees
		echo modal_select_class(school_id, 'select_class_collect_fees', 'Collect Fees', 'fees_staff/select_class_collect_fees', 'View');  

		//Full Fee Payment
		echo modal_select_class(school_id, 'select_class_full_fee_payment', 'Full Fee Payment', 'fees_staff/select_class_fee_payment', 'View', 'full_payment');  

		//Partial Fee Payment
		echo modal_select_class(school_id, 'select_class_partial_fee_payment', 'Partial Fee Payment', 'fees_staff/select_class_fee_payment', 'View', 'partial_payment');  

		//No Fee Payment
		echo modal_select_class(school_id, 'select_class_no_fee_payment', 'No Payment', 'fees_staff/select_class_fee_payment', 'View', 'no_payment');  

		/* Archive */
	

		//Fee Payment Summary: Archive
		echo modal_select_archived_data(year_installed, 'select_archive_fee_payment_summary', 'Payment Summary: Archive', 'fees_staff/select_archive_payment_summary', current_session, current_term, 'View'); 

		//Full Fee Payment: Archive
		echo modal_select_archived_class_data(school_id, year_installed, 'select_archive_full_fee_payment', 'Full Payment: Archive', 'fees_staff/select_archive_class_fee_payment', current_session, current_term, 'View', 'full_payment'); 

		//Partial Fee Payment: Archive
		echo modal_select_archived_class_data(school_id, year_installed, 'select_archive_partial_fee_payment', 'Partial Payment: Archive', 'fees_staff/select_archive_class_fee_payment', current_session, current_term, 'View', 'partial_payment'); 

		//No Fee Payment: Archive
		echo modal_select_archived_class_data(school_id, year_installed, 'select_archive_no_fee_payment', 'No Payment: Archive', 'fees_staff/select_archive_class_fee_payment', current_session, current_term, 'View', 'no_payment'); 

	}



	if ($requisitions_module) { 

		//Financial Requisitions: All Requests
		echo modal_select_archived_data(year_installed, 'select_archive_all_requests', 'All Requests: Archive', 'prs_staff/select_archive_requests', current_session, current_term, 'View', 'requests'); 

		//Financial Requisitions: Pending Requests
		echo modal_select_archived_data(year_installed, 'select_archive_pending_requests', 'Pending Requests: Archive', 'prs_staff/select_archive_requests', current_session, current_term, 'View', 'pending_requests'); 

		//Financial Requisitions: Approved Requests
		echo modal_select_archived_data(year_installed, 'select_archive_approved_requests', 'Approved Requests: Archive', 'prs_staff/select_archive_requests', current_session, current_term, 'View', 'approved_requests'); 

		//Financial Requisitions: Declined Requests
		echo modal_select_archived_data(year_installed, 'select_archive_declined_requests', 'Declined Requests: Archive', 'prs_staff/select_archive_requests', current_session, current_term, 'View', 'declined_requests'); 

	}




	if ($weekly_reports_module) { 

		//Weekly Reports: Archive
		echo modal_select_archived_data(year_installed, 'select_archive_weekly_reports', 'All Reports: Archive', 'weekly_reports_staff/select_archive_weekly_reports', current_session, current_term, 'View'); 

	}

?>
										
	