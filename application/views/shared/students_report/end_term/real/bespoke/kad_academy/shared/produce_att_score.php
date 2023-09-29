    <?php
    $produce_att_url = $this->c_controller.'/produce_att_scores_ajax/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id;
   
    if ($this->c_user_role == 'admin' || $this->c_user_role == 'class_teacher') { 

        //check if result has been created, show class teacher comment box if true
        if ($result_details) { ?>

            <hr />

            <div class="row">
                <div class="col-md-12 p-b-30 table-scroll">

                    <?php
                    $form_attributes = array('id' => 'produce_end_term_att_form'); 
                    echo form_open($produce_att_url, $form_attributes); ?>

                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h3>Attendance</h3>
                                <div>
                                    <?php
                                    if ($result_details->att_present == NULL &&  $result_details->att_absent == NULL && $result_details->att_tardy == NULL) {
                                        $status = '<b class="text-danger">Not Submitted</b>';
                                    } else {
                                        $status = '<b class="text-success">Submitted</b>';
                                    } ?>
                                    Status: <?php echo $status; ?>
                                </div>
                                <?php $checked = ($result_details->att_type == 'custom') ? 'checked' : NULL; ?>
                                <input type="checkbox" name="customize_att" id="customize_att" <?php echo $checked; ?> /> Customize

                                <div id="class_att_table">
                                    <table class="table table-bordered cell-text-middle">
                                        <thead>
                                            <tr>
                                                <th>Days Present</th>
                                                <th>Days Absent</th>
                                                <th>Times Tardy</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo $att_present; ?></td>
                                                <td><?php echo $att_absent; ?></td>
                                                <td>N/A</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="custom_att_table">
                                    <table class="table table-bordered cell-text-middle">
                                        <thead>
                                            <tr>
                                                <th>Days Present</th>
                                                <th>Days Absent</th>
                                                <th>Times Tardy</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="form-group">
                                                    <input type="text" class="form-control numbers-only" name="att_present" value="<?php echo $result_details->att_present; ?>" />
                                                </td>
                                                <td class="form-group">
                                                    <input type="text" class="form-control numbers-only" name="att_absent" value="<?php echo $result_details->att_absent; ?>" />
                                                </td>
                                                <td class="form-group">
                                                    <input type="text" class="form-control numbers-only" name="att_tardy" value="<?php echo $result_details->att_tardy; ?>" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div id="att_status_msg"></div>

                                <div class="form-group">
                                    <button class="btn btn-success btn-lg">Submit</button>
                                    <span id="d_loader_att" style="display: none"><i class="fa fa-spinner fa-spin"></i> Updating...please wait.</span>
                                </div>

                            </div>
                        </div>
                    
                    <?php echo form_close(); ?>

                </div>
            </div>

        <?php } //if result_details ?>

    <?php } //if admin or class teacher ?>