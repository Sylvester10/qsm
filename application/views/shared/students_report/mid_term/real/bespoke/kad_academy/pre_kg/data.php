        
        <div class="report_header_prekg">

            <div class="report_head text-center">
                <span>Mid-Term Progress Report</span>
            </div>

            <table class="table table-no-border">
                <tr>
                    <td class="align_left">
                        <div class="">
                            Name: <span class="text-bold"><?php echo $student_name; ?></span>
                        </div>
                    </td>
                    <td class="align_left">
                        <div class="">
                            Class: <span class="text-bold"><?php echo $class; ?></span>
                        </div>
                    </td>
                    <td>
                         <div class="school-logo pull-right">
                            <img src="<?php echo kad_school_logo; ?>" />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="align_left">
                        <div class="">
                            D.O.B: <?php echo $student_dob; ?>
                        </div>
                    </td>
                    <td class="align_left">
                        <div>
                            Enrolment Date: <?php echo $student_admission_date; ?>
                        </div>
                    </td>
                    <td class="">
                        <div class="pull-right">
                            Teacher: <?php echo $class_teacher_name; ?>
                        </div>
                    </td>
                </tr> 
            </table>

        </div>

        
        
        <table class="subject_scores_table">
            <thead>
                <tr>
                    <td colspan="2" class="hide_border_top"></td>
                    <td class="w-100-p hide_border_top"> Beginning </td>
                    <td class="w-100-p hide_border_top"> Developing </td>
                    <td class="w-100-p hide_border_top"> Secure</td>
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

                        <tr>

                            <?php if ($item_count === 1) { ?>
                                <td rowspan="<?php echo $total_items; ?>" class="text-bold"><?php echo $subject_category; ?></td>
                            <?php } ?>
                            <td class="cell_data align_left"><?php echo $subject_item; ?></td>

                            <?php
                            if ($test_score_query->num_rows() === 0) { ?> 
                                <td class=""></td>
                                <td class=""></td>
                                <td class=""></td>
                            <?php } else { 
                                $test_score_id = $test_score_query->row()->id;
                                $t = $this->kad_students_mid_term_report_model->get_test_score_details($test_score_id, $class_id);
                                $progress = $t->progress; ?>
                                <td class="cell_data"><?php echo ($progress == 1) ? 'X' : NULL; ?></td>
                                <td class="cell_data"><?php echo ($progress == 2) ? 'X' : NULL; ?></td>
                                <td class="cell_data"><?php echo ($progress == 3) ? 'X' : NULL; ?></td>
                            <?php } ?>
                            
                        </tr>

                        <?php $item_count++; ?>
                    <?php } ?>
                <?php } ?>

            </tbody>
        </table>


        <div class="report_footer_prekg">

            <div class="p-l-100">
                <span class="report_data">TEACHER COMMENTS:</span>
            </div>

            <table class="table table-no-border">
                <tr>
                    <td>
                        <div class="child_logo">
                            <img src="<?php echo base_url(); ?>assets/images/bespoke/kad_academy/child.png" />
                        </div>
                    </td>
                    <td>
                        <div class="report_comment align_left" style="padding: 10px">
                            <?php echo $class_teacher_comment; ?>
                            <span class="pull-right">
                                <img class="report_signature" src="<?php echo $class_teacher_signature; ?>" /> 
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="info_comment"> 
                            <ul class="list_style_none">
                                <li><b>BEGINNING:</b> Sometimes completes the task correctly with  assistance.</li>
                                <li><b>DEVELOPING:</b> Often completes the task correctly.</li>
                                <li><b>SECURE:</b> Usually completes the task correctly and Independently.</li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </table>

        </div>