<?php
$f_class = 'PRE-KINDERGARTEN';
$shapes = kad_academy_pre_kg_shapes();
$letter_assessment_type = 'name';
$letter_types = kad_academy_pre_kg_letter_types();
$first_col_rows = 4; //number of rows in first subject column

//header
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/report_header_foundation.php'; 

//main data
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/data_foundation_primary.php';

//attendance data
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/attendance_data_foundation_primary.php';

//misc data
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/misc_data_foundation.php';

//footer
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/report_footer_foundation_primary.php'; ?>