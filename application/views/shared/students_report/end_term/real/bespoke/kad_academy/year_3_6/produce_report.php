
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


    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12 table-scroll">
            <b>Legend</b>
            <table class="table table-bordered cell-text-middle">
                <thead>
                    <tr class="text-bold text-center">
                        <td colspan="13">
                            <strong>Grade Criteria</strong>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <?php
                        $legend = kad_academy_year_3_6_report_legend();
                        foreach ($legend as $grade => $range) { ?>
                            <td><?php echo $grade; ?><br><?php echo $range; ?></td>
                        <?php } ?>
                    </tr>
                    <tr class="text-center">
                        <td colspan="13"><strong>Student Performance Level</strong></td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="3"> 3 – Outstanding </td>
                        <td colspan="3"> 2 – Satisfactory </td>
                        <td colspan="4"> 1 – Unsatisfactory </td>
                        <td colspan="4"> * - Not Assessed </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>



    <div class="row">

        <div class="col-md-12 p-b-30 table-scroll">

            <?php
            $produce_report_url = $this->c_controller.'/produce_year_3_6_test_score_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
            $form_attributes = array('id' => 'produce_end_term_report_form'); 
            echo form_open($produce_report_url, $form_attributes); ?>

                <table class="table table-bordered cell-text-middle" style="text-align: left">
                    <thead>
                        <tr>
                            <th class="w-3">S/N</th>
                            <th class="min-w-300">Subject</th>
                            <th class="min-w-30">Score (100)</th>
                            <th class="min-w-30">Grade</th>
                            <th class="w-5">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php
                        $count = 1;
                        $subjects = $this->kad_students_end_term_report_model->get_subject_categories($template_id); 
                        foreach ($subjects as $s) {

                            $subject_id = $s->id;
                            $subject = $s->category;
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
                                            <input class="form-control" type="text" value="" readonly />
                                        </div>
                                    </td>

                                    <td>
                                        <a type="button" class="btn btn-danger btn-sm" href="#!" disabled><i class="fa fa-trash"></i></a>
                                    </td>


                                <?php } else { 

                                    $test_score_id = $test_score_query->row()->id;
                                    $t = $this->kad_students_end_term_report_model->get_test_score_details($test_score_id, $class_id);
                                    $grade = $this->kad_students_end_term_report_model->get_year_3_6_subject_score_data($t->session, $t->term, $t->class_id, $t->student_id, $t->assessment, 'grade'); ?>

                                    <td>
                                        <div class="form-group">
                                            <input class="form-control numbers-only assessment_field" type="text" name="assessment[]" value="<?php echo set_value('assessment[]', $t->assessment); ?>" maxlength="3" />
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <input class="form-control" type="text" value="<?php echo $grade; ?>" readonly />
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

                <div id="achievement_status_msg"></div>
                <div id="extra_msg"></div>

                <div class="form-group">
                    <button class="btn btn-success btn-lg">Submit</button>
                    <span id="d_loader_achievement" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
                </div>

            <?php echo form_close(); //produce_test_score ?>

        </div><!--/.col-->

    </div><!--/.row-->



    <div class="row">

        <div class="col-md-12 p-b-30 table-scroll">

            <?php
            $method = 'produce_year_3_6_progress_score_ajax';
            $produce_year_3_6_progress_url = $this->c_controller.'/'.$method.'/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
            $form_attributes = array('id' => 'produce_end_term_year_3_6_progress_form'); 
            echo form_open($produce_year_3_6_progress_url, $form_attributes); ?>

                <table class="table table-bordered cell-text-middle" style="text-align: left">
                    <thead>
                        <tr>
                            <th class="min-w-200">Subject</th>
                            <th class="min-w-200">
                                <div class="pull-right">
                                    <input class="form-control report_radio" type="radio" name="bulk_select_items" id="deselect_all_items" />
                                </div>
                                Subject Items
                            </th>
                            <th class="min-w-20">
                                Outstanding 
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_3" />
                            </th>
                            <th class="min-w-20">
                                Satisfactory
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_2" />
                            </th>
                            <th class="min-w-20">
                                Unsatisfactory
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_1" />
                            </th>
                            <th class="min-w-20">
                                Not Accessed
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_0" />
                            </th>
                            <th class="w-5">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php
                        $i = 0;
                        $subject_categories = $this->kad_students_end_term_report_model->get_subject_categories($template_id); 
                        foreach ($subject_categories as $c) { 
                            
                            $subject_cat_id = $c->id;
                            $subject_cat_details = $this->kad_students_end_term_report_model->get_subject_category_details($subject_cat_id);
                            $subject_category = $subject_cat_details->category;
                            $subject_category_items = $this->kad_students_end_term_report_model->get_subject_category_items($subject_cat_id);
                            $total_items = count($subject_category_items); 

                            $item_count = 1;
                            foreach ($subject_category_items as $s) { 
                                $subject_id = $s->id;
                                $subject_details = $this->kad_students_end_term_report_model->get_subject_category_item_details($subject_id);
                                $subject_item = $subject_details->item;
                                $progress_score_query = $this->kad_students_end_term_report_model->check_year_3_6_progress_score_exists($session, $term, $class_id, $student_id, $subject_id); ?>

                                <input type="hidden" name="subject_id[]" value="<?php echo $subject_id; ?>" />

                                <tr>

                                    <?php if ($item_count == 1) { ?>
                                        <td rowspan="<?php echo $total_items; ?>"><?php echo $subject_category; ?></td>
                                    <?php } ?>

                                    <td><?php echo $subject_item; ?></td>

                                    <?php if ($progress_score_query->num_rows() == 0) { ?>
                                    
                                        <td> 
                                            <div class="form-group">
                                                <input class="form-control report_radio item_3_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="3" />
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_2_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="2" />
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_1_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="1" />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_0_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="0" />
                                            </div>
                                        </td>
                                           
                                        <td>
                                            <a type="button" class="btn btn-danger btn-sm" href="#!" disabled><i class="fa fa-trash"></i></a>
                                        </td>

                                    <?php } else { 

                                        $progress_score_id = $progress_score_query->row()->id;
                                        $t = $this->kad_students_end_term_report_model->get_year_3_6_progress_score_details($progress_score_id); ?>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_3_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="3" <?php echo set_radio( "progress[<?php echo $i; ?>]", '3', radio_value($t->progress, '3') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_2_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="2" <?php echo set_radio( "progress[<?php echo $i; ?>]", '2', radio_value($t->progress, '2') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_1_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="1" <?php echo set_radio( "progress[<?php echo $i; ?>]", '1', radio_value($t->progress, '1') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_0_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="0" <?php echo set_radio( "progress[<?php echo $i; ?>]", '0', radio_value($t->progress, '0') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <a type="button" class="btn btn-danger btn-sm" href="<?php echo base_url($this->c_controller.'/delete_year_3_6_progress_score/'.$progress_score_id); ?>" title="Delete score for this item"><i class="fa fa-trash"></i></a>
                                        </td>
                                            
                                    <?php } //endif test_score_query query ?>

                                    <?php $i++; ?>
                                    <?php $item_count++; ?>

                                </tr>

                            <?php } //end foreach subject_category_items ?>
  
                        <?php } //end foreach subject_categories ?>

                    </tbody>
                </table>

                <div id="year_3_6_progress_status_msg"></div>
                <div id="year_3_6_extra_msg"></div>

                <div class="form-group">
                    <button class="btn btn-success btn-lg">Submit</button>
                    <span id="d_loader_year_3_6_progress" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
                </div>

            <?php echo form_close(); //produce_test_score ?>

        </div><!--/.col-->

    </div><!--/.row-->


    
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
                                    Skills for Life (Scale)
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
                                <th class="w-5">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php
                            $count = 1;
                            $i = 0;
                            $skills = $this->kad_students_end_term_report_model->get_year_3_6_skills();
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
        var produce_year_3_6_progress_url = '<?php echo $produce_year_3_6_progress_url; ?>';
        var produce_misc_url = '<?php echo $produce_misc_url; ?>';
        var produce_att_url = '<?php echo $produce_att_url; ?>';
        var produce_ct_comment_url = '<?php echo $produce_ct_comment_url; ?>';
        var produce_ht_comment_url = '<?php echo $produce_ht_comment_url; ?>';
    </script>