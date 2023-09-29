    <?php
    $produce_ct_comment_url = $this->c_controller.'/produce_class_teacher_comment_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
                        
    //check if class teacher comment is allowed
    if ($allow_class_teacher_comment == 'true') { 

        //check if admin or class teacher 
        if ($this->c_user_role == 'admin' || $this->c_user_role == 'class_teacher') { 

            //check if result has been created, show class teacher comment box if true
            if ($result_details) { ?>

                <hr />

                <div class="row">
                    <div class="col-md-8 col-sm-12 col-xs-12 m-b-30">
                        
                        <h3>Class Teacher's Comment</h3>

                        <?php
                        $form_attributes = array('id' => 'produce_end_term_ct_comment_form'); 
                        echo form_open($produce_ct_comment_url, $form_attributes);

                            $query = $this->kad_students_end_term_report_model->check_class_teacher_comment_exists($session, $term, $class_id, $student_id); 

                            if ($query->num_rows() == 0) { ?>

                                <div class="form-group">
                                    <textarea name="comment" class="form-control t100"><?php echo set_value('comment'); ?></textarea>
                                </div>

                                <div id="ct_comment_status_msg"></div>

                                <div class="form-group">
                                    <button class="btn btn-success btn-lg">Submit</button>
                                    <span id="d_loader_ct_comment" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
                                </div>

                            <?php } else { 

                                $comment_id = $query->row()->id;
                                $comment_details = $this->kad_students_end_term_report_model->get_class_teacher_comment_details($comment_id); ?>

                                <div class="form-group">
                                    <textarea name="comment" class="form-control t100"><?php echo set_value('comment', $comment_details->comment); ?></textarea>
                                </div>

                                <div id="ct_comment_status_msg"></div>

                                <div class="form-group">
                                    <button class="btn btn-success btn-lg">Update</button>
                                    <span id="d_loader_ct_comment" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
                                </div>

                            <?php } ?>

                        <?php echo form_close(); ?>

                    </div><!-- /.col-md-10 -->

                </div><!-- /.row -->

            <?php } //if result exists

        } //if admin or class teacher 
        
    } //if class teacher comment is allowed ?>


    