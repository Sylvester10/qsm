<?php 
	//View Single Class
	echo modal_select_class(school_id, 'select_class_view', 'View Class', 'students_admin/view_single_class', 'View'); 


	//Attendance
	echo modal_select_class(school_id, 'select_class_attendance', 'Attendance', 'students_admin/select_class_attendance', 'View');
	//Attendance Archive
	echo modal_select_archived_class_data(school_id, year_installed, 'select_archived_class_attendance', 'Attendance: Archive', 'students_admin/select_archived_class_attendance', current_session, current_term, 'View');


	//Lesson Period
	echo modal_select_class(school_id, 'select_class_lesson_periods', 'Lesson Periods', 'timetable_admin/select_class_lesson_periods', 'View');
	//Test Schedules  
	echo modal_select_class(school_id, 'select_class_test_schedules', 'Test Schedules', 'timetable_admin/select_class_test_schedules', 'View'); 
	//Exam Schedules  
	echo modal_select_class(school_id, 'select_class_exam_schedules', 'Exam Schedules', 'timetable_admin/select_class_exam_schedules', 'View');  


	//New Homework
	echo modal_select_class(school_id, 'select_class_new_homework', 'New Homework', 'homework_admin/select_class_new_homework', 'Proceed');  
	//All Homework
	echo modal_select_class(school_id, 'select_class_view_homework', 'All Homework', 'homework_admin/select_class_view_homework', 'View');  
	//Clear Homework
	 echo modal_select_archived_data(year_installed, 'select_archive_clear_homework', 'Clear Homework', 'homework_admin/clear_homework', current_session, current_term, 'Clear');


	//Student Reports
	//KAD Academy Student Reports (Bespoke)
	if (school_id == kad_school_id) { 
	
		//mid-term reports
		echo modal_select_class(school_id, 'select_class_mid_term_reports', 'Mid-Term Reports', 'kad_student_mid_term_reports_admin/select_class_reports', 'View');  
		//Archive
		echo modal_select_archived_class_data(school_id, year_installed, 'select_archived_mid_term_reports', 'Mid-Term Reports: Archive', 'kad_student_mid_term_reports_admin/select_archived_reports', current_session, current_term, 'View'); 

		//end-of-term reports
		echo modal_select_class(school_id, 'select_class_end_term_reports', 'End-of-Term Reports', 'kad_student_end_term_reports_admin/select_class_reports', 'View');  
		//Archive
		echo modal_select_archived_class_data(school_id, year_installed, 'select_archived_end_term_reports', 'End-of-Term Reports: Archive', 'kad_student_end_term_reports_admin/select_archived_reports', current_session, current_term, 'View'); 

	} else {

		//mid-term reports
		echo modal_select_class(school_id, 'select_class_mid_term_reports', 'Mid-Term Reports', 'student_mid_term_reports_admin/select_class_reports', 'View');  
		//Archive
		echo modal_select_archived_class_data(school_id, year_installed, 'select_archived_mid_term_reports', 'Mid-Term Reports: Archive', 'student_mid_term_reports_admin/select_archived_reports', current_session, current_term, 'View'); 

		//end-of-term reports
		echo modal_select_class(school_id, 'select_class_end_term_reports', 'End-of-Term Reports', 'student_reports_admin/select_class_reports', 'View');  
		//Archive
		echo modal_select_archived_class_data(school_id, year_installed, 'select_archived_end_term_reports', 'End-of-Term Reports: Archive', 'student_reports_admin/select_archived_reports', current_session, current_term, 'View'); 

	}



	//Collect Fees
	echo modal_select_class(school_id, 'select_class_collect_fees', 'Collect Fees', 'fees_admin/select_class_collect_fees', 'View');  

	//Full Fee Payment
	echo modal_select_class(school_id, 'select_class_full_fee_payment', 'Full Fee Payment', 'fees_admin/select_class_fee_payment', 'View', 'full_payment');  

	//Partial Fee Payment
	echo modal_select_class(school_id, 'select_class_partial_fee_payment', 'Partial Fee Payment', 'fees_admin/select_class_fee_payment', 'View', 'partial_payment');  

	//No Fee Payment
	echo modal_select_class(school_id, 'select_class_no_fee_payment', 'No Payment', 'fees_admin/select_class_fee_payment', 'View', 'no_payment');  

	/* Archive */


	//Fee Payment Summary: Archive
	echo modal_select_archived_data(year_installed, 'select_archive_fee_payment_summary', 'Payment Summary: Archive', 'fees_admin/select_archive_payment_summary', current_session, current_term, 'View'); 

	//Full Fee Payment: Archive
	echo modal_select_archived_class_data(school_id, year_installed, 'select_archive_full_fee_payment', 'Full Payment: Archive', 'fees_admin/select_archive_class_fee_payment', current_session, current_term, 'View', 'full_payment'); 

	//Partial Fee Payment: Archive
	echo modal_select_archived_class_data(school_id, year_installed, 'select_archive_partial_fee_payment', 'Partial Payment: Archive', 'fees_admin/select_archive_class_fee_payment', current_session, current_term, 'View', 'partial_payment'); 

	//No Fee Payment: Archive
	echo modal_select_archived_class_data(school_id, year_installed, 'select_archive_no_fee_payment', 'No Payment: Archive', 'fees_admin/select_archive_class_fee_payment', current_session, current_term, 'View', 'no_payment'); 



	//Financial Requisitions: All Requests Archive
	echo modal_select_archived_data(year_installed, 'select_archive_all_requests', 'All Requests: Archive', 'prs_admin/select_archive_requests', current_session, current_term, 'View', 'requests'); 

	//Financial Requisitions: Pending Requests Archive
	echo modal_select_archived_data(year_installed, 'select_archive_pending_requests', 'Pending Requests: Archive', 'prs_admin/select_archive_requests', current_session, current_term, 'View', 'pending_requests'); 

	//Financial Requisitions: Approved Requests Archive
	echo modal_select_archived_data(year_installed, 'select_archive_approved_requests', 'Approved Requests: Archive', 'prs_admin/select_archive_requests', current_session, current_term, 'View', 'approved_requests'); 

	//Financial Requisitions: Declined Requests Archive
	echo modal_select_archived_data(year_installed, 'select_archive_declined_requests', 'Declined Requests: Archive', 'prs_admin/select_archive_requests', current_session, current_term, 'View', 'declined_requests'); 



	//Weekly Reports: Archive
	echo modal_select_archived_data(year_installed, 'select_archive_weekly_reports', 'All Reports: Archive', 'weekly_reports_admin/select_archive_weekly_reports', current_session, current_term, 'View'); 
										
	