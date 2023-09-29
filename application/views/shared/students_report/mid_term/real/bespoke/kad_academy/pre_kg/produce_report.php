
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


    <div class="row">

        <div class="col-md-12 p-b-30 table-scroll">

            <?php
            $method = 'produce_test_score_pre_kg_ajax';
            $produce_report_url = $this->c_controller.'/'.$method.'/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
            $form_attributes = array('id' => 'produce_mid_term_report_form'); 
            echo form_open($produce_report_url, $form_attributes); ?>

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
                                Beginning
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_1" />
                            </th>
                            <th class="min-w-20">
                                Developing
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_2" />
                            </th>
                            <th class="min-w-20">
                                Secure
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_3" />
                            </th>
                            <th class="w-5">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <?php
                        $i = 0;
                        $subject_categories = $this->kad_students_mid_term_report_model->get_pre_kg_subject_categories(); 
                        foreach ($subject_categories as $c) { 
                            
                            $subject_cat_id = $c->id;
                            $subject_cat_details = $this->kad_students_mid_term_report_model->get_pre_kg_subject_category_details($subject_cat_id);
                            $subject_category = $subject_cat_details->category;
                            $subject_category_items = $this->kad_students_mid_term_report_model->get_pre_kg_subject_category_items($subject_cat_id);
                            $total_items = count($subject_category_items); 

                            $item_count = 1;
                            foreach ($subject_category_items as $s) { 
                                $subject_id = $s->id;
                                $subject_details = $this->kad_students_mid_term_report_model->get_pre_kg_subject_category_item_details($subject_id);
                                $subject_item = $subject_details->item;
                                $test_score_query = $this->kad_students_mid_term_report_model->check_test_score_exists($session, $term, $class_id, $student_id, $subject_id); ?>

                                <input type="hidden" name="subject_id[]" value="<?php echo $subject_id; ?>" />

                                <tr>

                                    <?php if ($item_count == 1) { ?>
                                        <td rowspan="<?php echo $total_items; ?>"><?php echo $subject_category; ?></td>
                                    <?php } ?>

                                    <td><?php echo $subject_item; ?></td>

                                    <?php if ($test_score_query->num_rows() == 0) { ?>
                                    
                                        <td> 
                                            <div class="form-group">
                                                <input class="form-control report_radio item_1_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="1" />
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_2_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="2" />
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_3_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="3" />
                                            </div>
                                        </td>
                                           
                                        <td>
                                            <a type="button" class="btn btn-danger btn-sm" href="#!" disabled><i class="fa fa-trash"></i></a>
                                        </td>

                                    <?php } else { 

                                        $test_score_id = $test_score_query->row()->id;
                                        $t = $this->kad_students_mid_term_report_model->get_test_score_details($test_score_id, $class_id); ?>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_1_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="1" <?php echo set_radio( "progress[<?php echo $i; ?>]", '1', radio_value($t->progress, '1') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_2_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="2" <?php echo set_radio( "progress[<?php echo $i; ?>]", '2', radio_value($t->progress, '2') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <div class="form-group">
                                                <input class="form-control report_radio item_3_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="3" <?php echo set_radio( "progress[<?php echo $i; ?>]", '3', radio_value($t->progress, '3') ); ?> />
                                            </div>
                                        </td>

                                        <td>
                                            <a type="button" class="btn btn-danger btn-sm" href="<?php echo base_url($this->c_controller.'/delete_test_score/'.$test_score_id.'/'.$class_id); ?>" title="Delete score for this item"><i class="fa fa-trash"></i></a>
                                        </td>
                                            
                                    <?php } //endif test_score_query query ?>

                                    <?php $i++; ?>
                                    <?php $item_count++; ?>

                                </tr>

                            <?php } //end foreach subject_category_items ?>
  
                        <?php } //end foreach subject_categories ?>

                    </tbody>
                </table>

                <?php if (count($subject_categories) > 0) { ?>

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
    //class teacher comment
    require 'application/views/shared/students_report/mid_term/real/bespoke/kad_academy/shared/produce_class_teacher_comment.php'; ?>



    <script>
        //pass params to js
        var produce_report_url = '<?php echo $produce_report_url; ?>';
        var produce_ct_comment_url = '<?php echo $produce_ct_comment_url; ?>';
    </script>