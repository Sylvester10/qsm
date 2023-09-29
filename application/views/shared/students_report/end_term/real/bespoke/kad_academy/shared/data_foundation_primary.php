
<table class="table table-no-border m-t-10">
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

                    <table class="table f_p_subject_scores_table <?php echo $t_class; ?>">
                        <thead>
                            <tr>
                                <th class="subject_name"><?php echo $subject_category; ?></th>
                                <?php
                                foreach ($terms as $key => $term_value) { ?> 
                                    <th class="text-center"><?php echo $key; ?></th>
                                <?php } ?>
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
                                        $test_score_query = $this->kad_students_end_term_report_model->check_test_score_exists($session, $term_value, $class_id, $student_id, $subject_id); 
                                        if ($test_score_query->num_rows() > 0) {
                                            $test_score_id = $test_score_query->row()->id;
                                            $progress = $this->kad_students_end_term_report_model->get_test_score_details($test_score_id, $class_id)->progress;
                                            $legend = kad_academy_foundation_primary_progress_legend($progress, $category); ?>
                                            <td class="text-bold"><?php echo $legend; ?></td>
                                        <?php } else { ?>
                                            <td></td>
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

