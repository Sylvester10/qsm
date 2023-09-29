
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

    <div class="row">
        <div class="col-md-8">
            <div class="new-item">
                <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/report_card/'.$template_id.'/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id); ?>"><i class="fa fa-eye"></i> View Report Card</a>
                <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/reports/'.$session.'/'.$term.'/'.$class_id); ?>"><i class="fa fa-line-chart"></i> All Reports</a>
            </div>
            <div class="m-t-10 m-b-10">
                <h4 class="text-bold">Student Information</h4>
                <div>Student's Name: <?php echo $this->common_model->get_student_fullname($student_id); ?></div>
                <div>Student's ID: <?php echo $y->admission_id; ?></div>
                <div>Class: <?php echo $class_details->class; ?></div>
                <div class="">Class Population: <?php echo $this->common_model->get_class_population($class_id); ?>
                </div>
                <div>Session: <?php echo $the_session; ?></div>
                <div>Term: <?php echo $term; ?></div>
            </div>
        </div>
    </div>



    <?php
    //subject scores
    require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/produce_year_1_2_subject_scores.php'; ?>


    <?php
    //attendance
    require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/produce_att_score.php'; ?>


    <?php
    //class teacher comment
    require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/produce_class_teacher_comment.php'; ?>


    <?php
    //head teacher comment
    require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/shared/produce_head_teacher_comment.php'; ?>



    <script>
        //pass params to js
        var produce_report_url = '<?php echo $produce_report_url; ?>';
        var produce_att_url = '<?php echo $produce_att_url; ?>';
        var produce_ct_comment_url = '<?php echo $produce_ct_comment_url; ?>';
        var produce_ht_comment_url = '<?php echo $produce_ht_comment_url; ?>';
    </script>