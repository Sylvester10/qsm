
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

                <div class="m-t-20"></div>
                <b>Performance So Far</b>
                <div class="">Total Score: <?php echo $overall_total_score; ?></div>
                <div class="">Percentage Score: <?php echo $overall_percentage_score; ?></div>
                <div class="">Position: <?php echo $position; ?></div>
            </div>
        </div>
    </div>


    <div>
        <p>Note: If <b>Mark Score</b> is not provided for a given subject, the result for that subject will not be submitted.</p>

        <b>Effort Key</b> <br />
        1: Works to the best of his/her ability <br />
        2: Sometimes works to the best of his/her ability <br />
        3: Rarely works to the best of his/her ability <br />
    </div>

    <div class="row">

        <div class="col-md-12 p-b-30 table-scroll">

            <?php
            $produce_report_url = $this->c_controller.'/produce_secondary_test_score_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
            $form_attributes = array('id' => 'produce_end_term_report_form'); 
            echo form_open($produce_report_url, $form_attributes); ?>

                <table class="table table-bordered cell-text-middle" style="text-align: left">
                    <thead>
                        <tr>
                            <th class="w-3">S/N</th>
                            <th class="min-w-200">Subject</th>
                            <th class="min-w-30">Mark (100)</th>
                            <th class="min-w-30">Effort (3)</th>
                            <th class="min-w-30">Standard</th>
                            <th class="min-w-350">Subject Comment <br /><small>(400 characters max)</small></th>
                            <th class="w-5">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php
                        $count = 1;
                        foreach ($subjects as $s) {

                            //get subject ID from result
                            //for subject teacher, value of subjects is an array of subject_ids. For admin and staff, value of subjects is an object.
                            $subject_id = ($this->c_user_role == 'subject_teacher') ? $s : $s->id;

                            $subject_details = $this->common_model->get_subject_details($subject_id);
                            $subject = $subject_details->subject;
                            $test_score_query = $this->kad_students_end_term_report_model->check_test_score_exists($session, $term, $class_id, $student_id, $subject_id); ?>
                            
                            <input type="hidden" name="subject_id[]" value="<?php echo $subject_id; ?>" />

                            <tr>

                                <td><?php echo $count; ?></td>
                                <td><?php echo $subject; ?></td>
                                        
                                <?php if ($test_score_query->num_rows() == 0) { ?>

                                    <td>
                                        <div class="form-group">
                                            <input class="form-control numbers-only assessment_field" type="text" name="assessment[]" value="<?php echo set_value('assessment[]'); ?>" maxlength="3" />
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <input class="form-control numbers-only effort_field" type="text" name="effort[]" value="<?php echo set_value('effort[]'); ?>" maxlength="1" />
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <input class="form-control" type="text" value="" readonly />
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <textarea class="form-control" name="subject_comment[]" maxlength="400"><?php echo set_value('subject_comment[]'); ?></textarea>
                                        </div>
                                    </td>

                                    <td>
                                        <a type="button" class="btn btn-danger btn-sm" href="#!" disabled><i class="fa fa-trash"></i></a>
                                    </td>


                                <?php } else { 

                                    $test_score_id = $test_score_query->row()->id;
                                    $t = $this->kad_students_end_term_report_model->get_test_score_details($test_score_id, $class_id);
                                    $grade = $this->kad_students_end_term_report_model->get_secondary_subject_score_data($t->session, $t->term, $t->class_id, $t->student_id, $t->assessment, 'grade'); ?>

                                    <td>
                                        <div class="form-group">
                                            <input class="form-control numbers-only assessment_field" type="text" name="assessment[]" value="<?php echo set_value('assessment[]', $t->assessment); ?>" maxlength="3" />
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <input class="form-control numbers-only effort_field" type="text" name="effort[]" value="<?php echo set_value('effort[]', $t->effort); ?>" maxlength="1" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <input class="form-control" type="text" value="<?php echo $grade; ?>" readonly />
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <textarea class="form-control" name="subject_comment[]" maxlength="400"><?php echo set_value('subject_comment[]', $t->subject_comment); ?></textarea>
                                        </div>
                                    </td>

                                    <td>
                                        <a type="button" class="btn btn-danger btn-sm" href="<?php echo base_url($this->c_controller.'/delete_test_score/'.$test_score_id.'/'.$class_id); ?>" title="Delete scores for <?php echo $subject; ?>"><i class="fa fa-trash"></i></a>
                                    </td>
                                        
                                <?php } ?>

                            </tr>

                            <?php $count++; ?>
                            
                        <?php } //end foreach?>

                    </tbody>
                </table>

                <?php if (count($subjects) > 0) { ?>

                    <div id="achievement_status_msg"></div>
                    <div id="extra_msg"></div>

                    <div class="form-group">
                        <button class="btn btn-success btn-lg">Submit</button>
                        <span id="d_loader_achievement" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
                    </div>

                <?php } ?>

            <?php echo form_close(); //produce_test_score ?>

        </div><!--/.col-->

    </div><!--/.row-->



    
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