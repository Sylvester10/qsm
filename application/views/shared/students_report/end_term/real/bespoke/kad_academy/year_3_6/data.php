<?php
$first_col_rows = 5; //number of rows in first subject column


//header
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/report_header_year_3_6.php'; ?>



<table class="table table-no-border m-t-8-n">
    <tbody>
        <tr>

            <?php
            $i = 0;
            $count = 1;
            $subject_categories = $this->kad_students_end_term_report_model->get_subject_categories($template_id);
            $total_subject_categories = count($subject_categories); 
            foreach ($subject_categories as $c) { 
                $subject_cat_id = $c->id;
                $subject_cat_details = $this->kad_students_end_term_report_model->get_subject_category_details($subject_cat_id);
                $subject_category = $subject_cat_details->category;
                $subject_category_items = $this->kad_students_end_term_report_model->get_subject_category_items($subject_cat_id);
                $total_items = count($subject_category_items); 
                //Table margin class: left (t_left) and right (t_right)
                $t_class = (in_array($count, range(1, $first_col_rows))) ? 't_left' : 't_right';
                //Column: create new opening td tag
                $column_open = ($count == 1 || $count == $first_col_rows + 1) ? '<td>' : NULL; 
                //Column: create closing td tag
                $column_close = ($count == $first_col_rows || $count == $total_subject_categories) ? '</td>' : NULL; ?>

                <?php echo $column_open; ?>

                    <table class="table y_3_6_subject_scores_table <?php echo $t_class; ?>">
                    
                        <?php
                        if ($count == 1 || $count == $first_col_rows + 1) { ?>

                            <thead>
                                <tr>
                                    <th class="hide_border_top_left"></th>
                                    <th colspan="3" class="text-center">Terms</th>
                                </tr>
                                <tr>
                                    <th class="hide_border_top_left"></th>
                                    <?php
                                    foreach ($terms as $key => $term_value) { ?> 
                                        <th class="text-center term_data_cell"><?php echo $key; ?><sup><?php echo get_ordinal_value($key); ?></sup></th>
                                    <?php } ?>
                                </tr>
                            </thead>

                        <?php } ?>

                        <thead>
                            <tr>
                                <th class="subject_name"><?php echo $subject_category; ?></th>
                                <?php
                                foreach ($terms as $key => $term_value) {
                                    //if score exixts for this subject item, indicate against current term
                                    $test_score_query = $this->kad_students_end_term_report_model->check_test_score_exists($session, $term_value, $class_id, $student_id, $subject_cat_id);
                                    if ($test_score_query->num_rows() > 0) {
                                        $test_score_id = $test_score_query->row()->id;
                                        $t = $this->kad_students_end_term_report_model->get_test_score_details($test_score_id, $class_id);
                                        $grade = $this->kad_students_end_term_report_model->get_year_3_6_subject_score_data($t->session, $term_value, $t->class_id, $t->student_id, $t->assessment, 'grade'); ?>
                                        <th class="text-center term_data_cell"><?php echo $grade; ?></th>
                                    <?php } else { ?>
                                         <th class="term_data_cell"></th>
                                    <?php }
                                } ?>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            foreach ($subject_category_items as $s) { 
                                $subject_id = $s->id;
                                $subject_details = $this->kad_students_end_term_report_model->get_subject_category_item_details($subject_id);
                                $subject_item = $subject_details->item; ?>

                                <tr>
                                    <td class="subject_name"><?php echo $subject_item; ?></td>
                                    <?php
                                    foreach ($terms as $key => $term_value) {
                                        //if score exixts for this subject item, indicate against current term
                                        $progress_score_query = $this->kad_students_end_term_report_model->check_year_3_6_progress_score_exists($session, $term_value, $class_id, $student_id, $subject_id);
                                        if ($progress_score_query->num_rows() > 0) {
                                            $progress_score_id = $progress_score_query->row()->id;
                                            $progress = $this->kad_students_end_term_report_model->get_year_3_6_progress_score_details($progress_score_id)->progress;
                                            //show * if progress is zero (as produced)
                                            $progress = ($progress == 0) ? '*' : $progress; ?>
                                            <td class="term_data_cell"><?php echo $progress; ?></td>
                                        <?php } else { ?>
                                            <td class="term_data_cell"></td>
                                        <?php }
                                    } ?>
                                </tr>

                            <?php } ?>
                                   
                        </tbody>
                    </table>

                <?php echo $column_close; ?>

                <?php $count++; ?>

            <?php } //endforeach subject_categories ?>

        </tr>
    </tbody>
</table>



<?php
//attendance data
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/attendance_data_foundation_primary.php'; ?>


<table class="table report_table_extra m-t-10">
    <thead>
        <tr>
            <th colspan="5" class="text-center">SKILLS FOR LIFE (SCALE)</th>
        </tr>
        <tr>
            <th class="text-center hide_border_bottom">E – Exemplary</th>
            <th class="text-center hide_border_bottom">C – Consistently</th>
            <th class="text-center hide_border_bottom">U – Usually</th>
        </tr>
    </thead>
</table>
<?php
//misc data
$skills = $this->kad_students_end_term_report_model->get_year_3_6_skills(); 
$skill_sub_title = '';   
$skill_extra = 'Demonstrates:';   
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/misc_data_year_2_6.php';


//footer
require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/report_footer_foundation_primary.php'; ?>