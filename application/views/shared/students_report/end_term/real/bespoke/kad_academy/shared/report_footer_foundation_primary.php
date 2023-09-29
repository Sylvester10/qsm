<table class="table report_table_extra m-t-15 cell-text-middle">
    <tbody>

        <!-- 1st Term -->
        <tr>
            <td colspan="5" class="align_left">
                <span class="text-bold">TERM 1</span>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="align_left">
                <span class="text-bold">TEACHER COMMENT:</span>
                <?php $d_class_teacher_comment = $this->kad_students_end_term_report_model->get_class_teacher_comment($session, '1st', $class_id, $student_id);
                echo $d_class_teacher_comment; ?>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="align_left">
                <span class="text-bold">PRINCIPAL COMMENT:</span>
                <?php $d_head_teacher_comment = $this->kad_students_end_term_report_model->get_head_teacher_comment($session, '1st', $class_id, $student_id);
                echo $d_head_teacher_comment; ?>
            </td>
        </tr>
        <tr>
            <td style="width: 500px;">
                <?php $d_head_teacher_signature = $this->kad_students_end_term_report_model->get_head_teacher_signature($session, '1st', $class_id, $student_id); ?>
                <img class="report_signature_sm" src="<?php echo $d_head_teacher_signature; ?>" />
            </td>
            <td style="width: 100px;">DATE</td>
            <?php
            //check if result has been approved
            $d_result_details = $this->kad_students_end_term_report_model->get_result_details($session, '1st', $class_id, $student_id);
            if ($d_result_details && $d_result_details->status == 'Approved') { 
                $date_entered = $result_details->date_approved;
                $d_day = date('d', strtotime($date_entered));
                $d_month = date('F', strtotime($date_entered));
                $d_year = date('Y', strtotime($date_entered)); ?>                 
                <td class="text-center"><?php echo $d_day; ?></td>
                <td class="text-center"><?php echo $d_month; ?></td>
                <td class="text-center"><?php echo $d_year; ?></td>
            <?php } else { ?>
                <td></td>
                <td></td>
                <td></td>
            <?php } ?>
        </tr>


        <!-- 2nd Term -->
        <tr>
            <td colspan="5" class="align_left">
                <span class="text-bold">TERM 2</span>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="align_left">
                <span class="text-bold">TEACHER COMMENT:</span>
                <?php $d_class_teacher_comment = $this->kad_students_end_term_report_model->get_class_teacher_comment($session, '2nd', $class_id, $student_id);
                echo $d_class_teacher_comment; ?>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="align_left">
                <span class="text-bold">PRINCIPAL COMMENT:</span>
                <?php $d_head_teacher_comment = $this->kad_students_end_term_report_model->get_head_teacher_comment($session, '2nd', $class_id, $student_id);
                echo $d_head_teacher_comment; ?>
            </td>
        </tr>
        <tr>
            <td style="width: 500px;">
                <?php $d_head_teacher_signature = $this->kad_students_end_term_report_model->get_head_teacher_signature($session, '2nd', $class_id, $student_id); ?>
                <img class="report_signature_sm" src="<?php echo $d_head_teacher_signature; ?>" />
            </td>
            <td style="width: 100px;">DATE</td>
            <?php
            //check if result has been approved
            $d_result_details = $this->kad_students_end_term_report_model->get_result_details($session, '2nd', $class_id, $student_id);
            if ($d_result_details && $d_result_details->status == 'Approved') { 
                $date_entered = $result_details->date_approved;
                $d_day = date('d', strtotime($date_entered));
                $d_month = date('F', strtotime($date_entered));
                $d_year = date('Y', strtotime($date_entered)); ?>                 
                <td class="text-center"><?php echo $d_day; ?></td>
                <td class="text-center"><?php echo $d_month; ?></td>
                <td class="text-center"><?php echo $d_year; ?></td>
            <?php } else { ?>
                <td></td>
                <td></td>
                <td></td>
            <?php } ?>
        </tr>


        <!-- 3rd Term -->
        <tr>
            <td colspan="5" class="align_left">
                <span class="text-bold">TERM 2</span>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="align_left">
                <span class="text-bold">TEACHER COMMENT:</span>
                <?php $d_class_teacher_comment = $this->kad_students_end_term_report_model->get_class_teacher_comment($session, '3rd', $class_id, $student_id);
                echo $d_class_teacher_comment; ?>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="align_left">
                <span class="text-bold">PRINCIPAL COMMENT:</span>
                <?php $d_head_teacher_comment = $this->kad_students_end_term_report_model->get_head_teacher_comment($session, '3rd', $class_id, $student_id);
                echo $d_head_teacher_comment; ?>
            </td>
        </tr>
        <tr>
            <td style="width: 500px;">
                <?php $d_head_teacher_signature = $this->kad_students_end_term_report_model->get_head_teacher_signature($session, '3rd', $class_id, $student_id); ?>
                <img class="report_signature_sm" src="<?php echo $d_head_teacher_signature; ?>" />
            </td>
            <td style="width: 100px;">DATE</td>
            <?php
            //check if result has been approved
            $d_result_details = $this->kad_students_end_term_report_model->get_result_details($session, '3rd', $class_id, $student_id);
            if ($d_result_details && $d_result_details->status == 'Approved') { 
                $date_entered = $result_details->date_approved;
                $d_day = date('d', strtotime($date_entered));
                $d_month = date('F', strtotime($date_entered));
                $d_year = date('Y', strtotime($date_entered)); ?>                 
                <td class="text-center"><?php echo $d_day; ?></td>
                <td class="text-center"><?php echo $d_month; ?></td>
                <td class="text-center"><?php echo $d_year; ?></td>
            <?php } else { ?>
                <td></td>
                <td></td>
                <td></td>
            <?php } ?>
        </tr>

    </tbody>
</table>

