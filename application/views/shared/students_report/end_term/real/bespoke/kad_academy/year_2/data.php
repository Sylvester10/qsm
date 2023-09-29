<?php
$first_col_rows = 6; //number of rows in first subject column


//header
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/report_header_year_1_2.php'; 

//main data
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/data_foundation_primary.php';

//attendance data
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/attendance_data_foundation_primary.php'; ?>


<table class="table report_table_extra m-t-10">
    <thead>
        <tr>
            <th colspan="5" class="text-center">LEARNER QUALITIES SCALE</th>
        </tr>
        <tr>
            <th class="text-center hide_border_bottom">E – Exemplary</th>
            <th class="text-center hide_border_bottom">C – Consistently</th>
            <th class="text-center hide_border_bottom">U – Usually</th>
            <th class="text-center hide_border_bottom">S – Sometimes</th>
            <th class="text-center hide_border_bottom">R – Rarely</th>
        </tr>
    </thead>
</table>
<?php
//misc data
$skills = $this->kad_students_end_term_report_model->get_year_2_skills(); 
$skill_sub_title = 'LEARNER QUALITIES';        
$skill_extra = '';        
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/misc_data_year_2_6.php';


//footer
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/report_footer_foundation_primary.php'; ?>