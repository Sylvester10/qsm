
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
            $method = 'produce_test_score_ajax';
            $produce_report_url = $this->c_controller.'/'.$method.'/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
            $form_attributes = array('id' => 'produce_end_term_report_form'); 
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
                                Mastered (+)
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_1" />
                            </th>
                            <th class="min-w-20">
                                Satisfactory (<i class="fa fa-check"></i>)
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_2" />
                            </th>
                            <th class="min-w-20">
                                Not Mastered (-)
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_3" />
                            </th>
                            <th class="min-w-20">
                                Not Evaluated (*)
                                <input class="form-control report_radio" type="radio" name="bulk_select_items" id="select_all_items_4" />
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
                                $test_score_query = $this->kad_students_end_term_report_model->check_test_score_exists($session, $term, $class_id, $student_id, $subject_id); ?>

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
                                            <div class="form-group">
                                                <input class="form-control report_radio item_4_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="4" />
                                            </div>
                                        </td>
                                           
                                        <td>
                                            <a type="button" class="btn btn-danger btn-sm" href="#!" disabled><i class="fa fa-trash"></i></a>
                                        </td>

                                    <?php } else { 

                                        $test_score_id = $test_score_query->row()->id;
                                        $t = $this->kad_students_end_term_report_model->get_test_score_details($test_score_id, $class_id); ?>

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
                                            <div class="form-group">
                                                <input class="form-control report_radio item_4_checkbox" type="radio" name="progress[<?php echo $i; ?>]" value="4" <?php echo set_radio( "progress[<?php echo $i; ?>]", '4', radio_value($t->progress, '4') ); ?> />
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
    $produce_misc_url = $this->c_controller.'/produce_misc_scores_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;

    if ($this->c_user_role == 'admin' || $this->c_user_role == 'class_teacher') {

        //check if result has been created
        if ($result_details) { ?>

            <hr />

            <div class="row">
                <div class="col-md-12 p-b-30 table-scroll">

                    <?php
                    $form_attributes = array('id' => 'produce_end_term_misc_form'); 
                    echo form_open($produce_misc_url, $form_attributes); ?>

                        <div class="row">
                            <div class="col-md-12">
                                <h3>Colour Identification</h3>
                                <?php
                                $colours = kad_academy_foundation_colours(); ?>
                                <table class="table table-bordered cell-text-middle">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <?php
                                            foreach ($colours as $colour) { ?>
                                                <th><?php echo $colour; ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="checkbox" id="select_all_colours" />
                                            </td>
                                            <?php
                                            $submitted_colours = explode(', ', $result_details->colours);
                                            foreach ($colours as $colour) { 
                                                $checked = (in_array($colour, $submitted_colours)) ? 'checked' : NULL; ?>
                                                <td>
                                                    <input type="checkbox" class="colour_checkbox" name="colours[]" value="<?php echo $colour; ?>" <?php echo $checked; ?> />
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h3>Shape Identification</h3>
                                <table class="table table-bordered cell-text-middle">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <?php
                                            foreach ($shapes as $shape) { ?>
                                                <th><?php echo $shape; ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="checkbox" id="select_all_shapes" />
                                            </td>
                                            <?php
                                            $submitted_shapes = explode(', ', $result_details->shapes);
                                            foreach ($shapes as $shape) {
                                                $checked = (in_array($shape, $submitted_shapes)) ? 'checked' : NULL; ?>
                                                <td>
                                                    <input type="checkbox" class="shape_checkbox" name="shapes[]" value="<?php echo $shape; ?>" <?php echo $checked; ?> />
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h3>Letter Name/Sound Assessment</h3>
                                <?php $letters = range('A', 'Z'); ?>
                                <table class="table table-bordered cell-text-middle">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <?php
                                            foreach ($letters as $letter) { ?>
                                                <th><?php echo $letter; ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Indentifies Uppercase</td>
                                            <td><input type="checkbox" id="select_all_letters_upper" /></td>
                                            <?php
                                            $submitted_letters_upper = explode(', ', $result_details->letters_upper);
                                            foreach ($letters as $letter) { 
                                                $checked = (in_array($letter, $submitted_letters_upper)) ? 'checked' : NULL; ?>
                                                <td>
                                                    <input type="checkbox" class="letter_upper_checkbox" name="letters_upper[]" value="<?php echo $letter; ?>" <?php echo $checked; ?> />
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td>Indentifies Lowercase</td>
                                            <td><input type="checkbox" id="select_all_letters_lower" /></td>
                                            <?php
                                            $submitted_letters_lower = explode(', ', $result_details->letters_lower);
                                            foreach ($letters as $letter) { 
                                                $checked = (in_array($letter, $submitted_letters_lower)) ? 'checked' : NULL; ?>
                                                <td>
                                                    <input type="checkbox" class="letter_lower_checkbox" name="letters_lower[]" value="<?php echo $letter; ?>" <?php echo $checked; ?> />
                                                </td>
                                            <?php } ?>
                                        </tr>
                                        <tr>
                                            <td>Indentifies Isolated Sound</td>
                                            <td><input type="checkbox" id="select_all_letters_isolated" /></td>
                                            <?php
                                            $submitted_letters_isolated = explode(', ', $result_details->letters_isolated);
                                            foreach ($letters as $letter) { 
                                                $checked = (in_array($letter, $submitted_letters_isolated)) ? 'checked' : NULL; ?>
                                                <td>
                                                    <input type="checkbox" class="letter_isolated_checkbox" name="letters_isolated[]" value="<?php echo $letter; ?>" <?php echo $checked; ?> />
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <h3>Number Recognition</h3>
                                <?php $numbers = range(1, 20); ?>
                                <table class="table table-bordered cell-text-middle">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <?php
                                            foreach ($numbers as $number) { ?>
                                                <th><?php echo $number; ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="checkbox" id="select_all_numbers" />
                                            </td>
                                            <?php
                                            $submitted_numbers = explode(', ', $result_details->numbers);
                                            foreach ($numbers as $number) { 
                                                $checked = (in_array($number, $submitted_numbers)) ? 'checked' : NULL; ?>
                                                <td>
                                                    <input type="checkbox" class="number_checkbox" name="numbers[]" value="<?php echo $number; ?>" <?php echo $checked; ?> />
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
                        <div id="misc_status_msg"></div>

                        <div class="form-group">
                            <button class="btn btn-success btn-lg">Submit</button>
                            <span id="d_loader_misc" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
                        </div>

                    <?php echo form_close(); ?>

                </div>
            </div>

        <?php } //if result_details ?>

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