
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
    $produce_misc_url = $this->c_controller.'/produce_skill_scores_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id; 

    if ($this->c_user_role == 'admin' || $this->c_user_role == 'class_teacher') { ?>

        <div class="row">

            <div class="col-md-12 p-b-30 table-scroll">

                <?php
                $form_attributes = array('id' => 'produce_end_term_misc_form'); 
                echo form_open($produce_misc_url, $form_attributes); ?>

                    <table class="table table-bordered cell-text-middle" style="text-align: left">
                        <thead>
                            <tr>
                                <th class="w-3">S/N</th>
                                <th class="min-w-200">
                                    <div class="pull-right">
                                        <input class="form-control report_radio" type="radio" name="bulk_select_skills" id="deselect_all_skills" />
                                    </div>
                                    Learner Qualities
                                </th>
                                <th class="min-w-20">
                                    Exemplary (E)
                                    <input class="form-control report_radio" type="radio" name="bulk_select_skills" id="select_all_skill_E" />
                                </th>
                                <th class="min-w-20">
                                    Consistently (C)
                                    <input class="form-control report_radio" type="radio" name="bulk_select_skills" id="select_all_skill_C" />
                                </th>
                                <th class="min-w-20">
                                    Usually (U)
                                    <input class="form-control report_radio" type="radio" name="bulk_select_skills" id="select_all_skill_U" />
                                </th>
                                <th class="min-w-20">
                                    Sometimes (S)
                                    <input class="form-control report_radio" type="radio" name="bulk_select_skills" id="select_all_skill_S" />
                                </th>
                                <th class="min-w-20">
                                    Rarely (R)
                                    <input class="form-control report_radio" type="radio" name="bulk_select_skills" id="select_all_skill_R" />
                                </th>
                                <th class="w-5">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                            $count = 1;
                            $i = 0;
                            $skills = $this->kad_students_end_term_report_model->get_year_2_skills();
                            foreach ($skills as $s) { 
                                
                                $skill_id = $s->id;
                                $skill = $s->skill;

                                $skill_score_query = $this->kad_students_end_term_report_model->check_skill_score_exists($session, $term, $class_id, $student_id, $skill_id); ?>
                                
                                <input type="hidden" name="skill_id[]" value="<?php echo $skill_id; ?>" />

                                <tr>

                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $skill; ?></td>
         
                                    <?php if ($skill_score_query->num_rows() == 0) { ?>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio skill_E_checkbox" type="radio" name="skill[<?php echo $i; ?>]" value="E" />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio skill_C_checkbox" type="radio" name="skill[<?php echo $i; ?>]" value="C" />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio skill_U_checkbox" type="radio" name="skill[<?php echo $i; ?>]" value="U" />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio skill_S_checkbox" type="radio" name="skill[<?php echo $i; ?>]" value="S" />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio skill_R_checkbox" type="radio" name="skill[<?php echo $i; ?>]" value="R" />
                                            </div>
                                        </td>

                                        <td>
                                            <a type="button" class="btn btn-danger btn-sm" href="#!" disabled><i class="fa fa-trash"></i></a>
                                        </td>


                                    <?php } else { 

                                        $skill_score_id = $skill_score_query->row()->id;
                                        $t = $this->kad_students_end_term_report_model->get_skill_score_details($skill_score_id); ?>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio skill_E_checkbox" type="radio" name="skill[<?php echo $i; ?>]" value="E" <?php echo set_radio( "skill[<?php echo $i; ?>]", 'E', radio_value($t->skill, 'E') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio skill_C_checkbox" type="radio" name="skill[<?php echo $i; ?>]" value="C" <?php echo set_radio( "skill[<?php echo $i; ?>]", 'C', radio_value($t->skill, 'C') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio skill_U_checkbox" type="radio" name="skill[<?php echo $i; ?>]" value="U" <?php echo set_radio( "skill[<?php echo $i; ?>]", 'U', radio_value($t->skill, 'U') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio skill_S_checkbox" type="radio" name="skill[<?php echo $i; ?>]" value="S" <?php echo set_radio( "skill[<?php echo $i; ?>]", 'S', radio_value($t->skill, 'S') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio skill_R_checkbox" type="radio" name="skill[<?php echo $i; ?>]" value="R" <?php echo set_radio( "skill[<?php echo $i; ?>]", 'R', radio_value($t->skill, 'R') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <a type="button" class="btn btn-danger btn-sm" href="<?php echo base_url($this->c_controller.'/delete_skill_score/'.$skill_score_id); ?>" title="Delete score for this item"><i class="fa fa-trash"></i></a>
                                        </td>
                                            
                                    <?php } ?>

                                </tr>

                                <?php $i++; ?>
                                <?php $count++; ?>
                            <?php } //end foreach?>

                        </tbody>
                    </table>

                    <div id="misc_status_msg"></div>
        
                    <div class="form-group">
                        <button class="btn btn-success btn-lg">Submit</button>
                        <span id="d_loader_misc" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
                    </div>

                <?php echo form_close();  ?>

            </div><!--/.col-->

        </div><!--/.row-->

    <?php } //if admin or class teacher ?>



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
        var produce_misc_url = '<?php echo $produce_misc_url; ?>';
        var produce_att_url = '<?php echo $produce_att_url; ?>';
        var produce_ct_comment_url = '<?php echo $produce_ct_comment_url; ?>';
        var produce_ht_comment_url = '<?php echo $produce_ht_comment_url; ?>';
    </script>