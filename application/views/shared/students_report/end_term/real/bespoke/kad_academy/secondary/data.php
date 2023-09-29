      
<div class="sec_school_logo text-center">
    <img src="<?php echo kad_school_logo; ?>" />
</div>

<div class="text-center m-t-30">
    <h3 class="f-w-600"><?php echo school_location; ?></h3>
    <h3 class="f-w-600">Tel: <?php echo telephone_line; ?></h3>
    <h3 class="f-w-600">Email: <span class="report_data_email"><?php echo official_mail; ?></span></h3>
</div>

<div class="text-center m-t-50">
    <h1 class="f-w-500"><?php echo $student_name; ?></h1>
    <h3 class="f-w-500 m-t-15"><?php echo $class; ?></h3>
</div>

<div class="text-center m-t-50">
    <h2 class="f-w-500">Report</h2>
    <h3 class="f-w-500">for</h3>
    <h3 class="f-w-500"><?php echo $term; ?> Term <?php echo $the_session; ?> Session</h3>
</div>


<div class="m-t-50">
    <h3 class="f-w-600">Form Teacher's Comment</h3>
    <div class="sec_teacher_comment_box">
        <?php echo $class_teacher_comment; ?>
    </div>
</div>

        
<div class="watermark_logo_area">

    <img class="watermark_logo" src="<?php echo base_url('assets/images/bespoke/kad_academy/watermark_logo.jpg'); ?>" />

    <div class="watermark_text_over align_left">

        <div class="m-t-30">
            <h3 class="f-w-600">Form Master's Signature: 
                <span class="report_data">
                    <img class="report_signature" src="<?php echo $class_teacher_signature; ?>" /> 
                </span>
            </h3>
        </div>

        <div class="m-t-15">
            <h3 class="f-w-600">Principal's Signature: 
                <span class="report_data">
                    <img class="report_signature" src="<?php echo $class_teacher_signature; ?>" /> 
                </span>
            </h3>
        </div>

        <div class="m-t-15">
            <h3 class="f-w-600">Attendance</h3>
            <h3>Days Present: <?php echo $att_present; ?></h3>
            <h3>Days Absent: <?php echo $att_absent; ?></h3>
            <h3><span class="text-bold">Resumption Date</span>: <?php echo x_date_full($term_info->next_term_start_date); ?></h3>
        </div>


        <div class="m-t-35">
            <table class="table table-no-border">
                <tr>
                    <td class="align_left">
                        <h3 class="f-w-500">Effort Descriptors</h3>
                        <div>1: Works to the best of his/her ability</div>
                        <div>2: Sometimes works to the best of his/her ability</div>
                        <div>3: Rarely works to the best of his/her ability</div>
                    </td>
                    <td class="align_left">
                        <div class="m-l-120">
                            <h3 class="f-w-500">Standard descriptors for work during term</h3>
                            <div><b>A*</b> Outstanding 90 – 100</div>
                            <div><b>A</b> Excellent 80 – 89 </div>
                            <div><b>B</b> Good 70 - 79</div>
                            <div><b>C</b> Satisfactory 60 - 69</div>
                            <div><b>D</b> Weak 50 - 59</div>
                            <div><b>E</b> Cause for Concern 40 - 49</div>
                            <div><b>U</b> Ungraded</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

    </div>

</div>


<table class="subject_scores_table m-t-100">

    <tr class="vheader">
        <th class="text-center rotate">Subject</th>
        <th class="text-center rotate">Mark</th>
        <th class="text-center rotate">Standard</th>
        <th class="text-center rotate">Effort</th>
        <th class="text-center">Comment</th>
    </tr>


    <?php
    foreach ($test_scores as $t) {
        $subject = $this->common_model->get_subject_details($t->subject_id)->subject; 
        $assessment = $t->assessment;
        $effort = $t->effort;
        $grade = $this->kad_students_end_term_report_model->get_secondary_subject_score_data($t->session, $t->term, $t->class_id, $t->student_id, $t->assessment, 'grade');
        $subject_comment = $t->subject_comment;
        $subject_teacher_name = $this->kad_students_end_term_report_model->get_subject_teacher_name($t->id, $class_id); ?>

        <tr>
            <td class="cell_data align_left text-bold"><?php echo $subject; ?></td>
            <td class="cell_data"><?php echo $assessment; ?></td>
            <td class="cell_data"><?php echo $grade; ?></td>
            <td class="cell_data"><?php echo $effort; ?></td>
            <td class="cell_data sec_st_comment align_left">
                <?php echo $subject_comment; ?>
                <span class="pull-right"><?php echo $subject_teacher_name; ?></span>
            </td>
        </tr>

    <?php } ?>

</table>

