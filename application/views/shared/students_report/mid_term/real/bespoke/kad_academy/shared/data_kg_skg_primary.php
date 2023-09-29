
        <?php require 'application/views/shared/students_report/mid_term/real/bespoke/kad_academy/shared/report_header.php'; ?>


        <table class="subject_scores_table">
            <tr class="vheader">
                <th class="subjects hide_border_left hide_border_top"></th>
                <?php if ($show_scores_field) { ?>
                    <th class="rotate_scores"> Scores</th>
                <?php } ?>
                <th class="rotate_satisfatory"> Satisfactory </th>
                <th class="rotate_improvement"> Needs Improvement</th>
                <th class="rotate_first"> Not Satisfactory </th>
                <th class="comment text-center"> Comments/Concerns </th>
            </tr>


            <?php
            foreach ($test_scores as $t) {
                $subject = $this->common_model->get_subject_details($t->subject_id)->subject; 
                $assessment = $t->assessment;
                $subject_comment = $t->subject_comment;
                $remark_value = $this->kad_students_mid_term_report_model->get_kg_primary_test_score_rubric($session, $term, $class_id, $student_id, $t->assessment, 'value'); ?>

                <tr>
                    <td class="cell_data align_left text-bold"><?php echo $subject; ?></td>
                    <?php if ($show_scores_field) { ?>
                         <td class="cell_data"><?php echo $assessment; ?></td>
                    <?php } ?>
                    <td class="cell_data"><?php echo ($remark_value == 1) ? 'X' : NULL; ?></td>
                    <td class="cell_data"><?php echo ($remark_value == 2) ? 'X' : NULL; ?></td>
                    <td class="cell_data"><?php echo ($remark_value == 3) ? 'X' : NULL; ?></td>
                    <td class="cell_data align_left"><?php echo $subject_comment; ?></td>
                </tr>

            <?php } ?>

        </table>

        <table class="table table-no-border">
            <tr>
                <td class="p-l-10 p-t-50">
                    <div class="text-bold">
                        <span>
                            Signature: 
                            <img class="report_signature" src="<?php echo $class_teacher_signature; ?>" /> 
                        </span>
                    </div>
                </td> 

                <td class="p-r-100 p-t-50">
                    <div class="text-bold">
                        <span>
                            Date: <span class="report_data"><?php echo $date_approved; ?></span>
                        </span>
                    </div>
                </td>
            </tr>
        </table>
