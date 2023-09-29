

        <?php require 'application/views/shared/students_report/mid_term/real/bespoke/kad_academy/shared/report_header.php'; ?>


        <table class="subject_scores_table">
            <tr>
                <th colspan="3" class="text-bold text-center">
                    Progress in learning is shown as:
                </th>
            </tr>
            <tr>
                <th class="text-center hide_border_bottom" style="width: 33%">
                    1 = Very Good
                    <div class="m-t-10">100 - 75</div>
                </th>
                <th class="text-center hide_border_bottom" style="width: 34%">
                    2 = Good
                    <div class="m-t-10">74 - 50</div>
                </th>
                <th class="text-center hide_border_bottom" style="width: 33%">
                    3 = Below Expectation
                    <div class="m-t-10">49 - 0</div>
                </th>
            </tr>
        </table>


        <table class="subject_scores_table">

            <tr class="vheader">
                <th class="text-center">SUBJECT</th>
                <th class="rotate" style="padding: 0 3px">CURRENT <br /> PROGRESS</th>
                <th class="rotate">EFFORT</th>
                <th class="rotate">BEHAVIOUR</th>
                <th class="rotate_assessment">ASSESSMENT <br /> SCORES</th>
                <th class="comment text-center"> COMMENT </th>
            </tr>


            <?php
            foreach ($test_scores as $t) {
                $subject = $this->common_model->get_subject_details($t->subject_id)->subject; 
                $effort = $t->effort;
                $behaviour = $t->behaviour;
                $assessment = $t->assessment; 
                $subject_comment = $t->subject_comment;
                $progress = $this->kad_students_mid_term_report_model->get_secondary_test_score_rubric($session, $term, $class_id, $student_id, $assessment, 'value'); ?>

                <tr>
                    <td class="cell_data align_left text-bold"><?php echo $subject; ?></td>
                    <td class="cell_data"><?php echo $progress; ?></td>
                    <td class="cell_data"><?php echo $effort; ?></td>
                    <td class="cell_data"><?php echo $behaviour; ?></td>
                    <td class="cell_data"><?php echo $assessment; ?></td>
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

