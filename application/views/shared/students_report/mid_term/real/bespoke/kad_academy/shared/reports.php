
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<div class="panel with-nav-tabs panel-default">
    <div class="panel-heading">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#manage_reports" data-toggle="tab">Manage Reports</a></li>
            <li><a href="#new_report" data-toggle="tab">New Report</a></li>
        </ul>
    </div>
    <div class="panel-body">
        <div class="tab-content">
        
            <div class="tab-pane active" id="manage_reports">

                <div class="m-b-20">
                    <h4 class="text-bold">Class Information</h4>
                    <p>Class Population: <?php echo $this->common_model->get_class_population($class_id); ?></p>
                    <p>Class Level: <?php echo $level; ?></p>
                    <p>Class Teacher: <?php echo $this->common_model->get_class_teacher_name($class_id); ?></p>
                    <p>Session: <?php echo $the_session; ?></p>
                    <p>Term: <?php echo $term; ?></p>
                </div>


                <?php
                //check if admin
                if ($this->c_user == 'admin') { 

                    //show action buttons if at least 1 student's result exists
                    if (count($class_results) > 0) { ?>

                        <div class="row m-t-20 m-b-30">
                            <div class="col-md-12">

                                <a class="btn btn-success" href="<?php echo base_url($this->c_controller.'/approve_all_results/'.$session.'/'.$term.'/'.$class_id); ?>" title="Approve all results for <?php echo $class; ?>">Approve All</a>

                                <a class="btn btn-danger" href="<?php echo base_url($this->c_controller.'/reject_all_results/'.$session.'/'.$term.'/'.$class_id); ?>" title="Reject all results for <?php echo $class; ?>">Reject All</a>

                                <a class="btn btn-warning" href="<?php echo base_url($this->c_controller.'/mark_all_results_pending/'.$session.'/'.$term.'/'.$class_id); ?>" title="Mark all results pending for <?php echo $class; ?>">Mark All Pending</a>

                            </div><!-- /.col-md-8 -->
                        </div><!-- /.row -->

                    <?php } ?>

                <?php } //if admin ?>


                <?php
                //check if admin
                if ($this->c_user == 'admin') { 
                    require "application/views/shared/students_report/mid_term/real/modals/bulk_actions_students_report.php";
                } //if admin ?>

                    <div class="table-scroll">
                        <table id="students_report_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
                            
                            <input type="hidden" id="session" value="<?php echo $session; ?>" />
                            <input type="hidden" id="term" value="<?php echo $term; ?>" />
                            <input type="hidden" id="class_id" value="<?php echo $class_id; ?>" />
                            <input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                            
                            <thead>
                                <tr>
                                    <th class="w-15-p"> <input type="checkbox" class="radio-box select_all" /> </th>
                                    <th> Actions </th>
                                    <th class="min-w-150"> Admission ID </th>
                                    <th class="min-w-200"> Name </th>
                                    <th> Subjects Registered </th>
                                    <th> Total Score  </th>
                                    <th> Percentage Average </th>
                                    <th> Position </th>
                                    <th> Decision </th>
                                    <th> Approval Status </th>
                                    <th class="min-w-350"> Class Teacher's Comment </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                <?php
                //check if admin
                if ($this->c_user == 'admin') {    
                    echo form_close(); //bulk action form close
                } //if admin ?>


            </div><!--/#manage_reports-->


            <div class="tab-pane" id="new_report">
                <?php echo get_students_new_report($session, $term, $class_id); ?>
            </div><!--/#new_report-->


        </div><!--/.tab-content-->
    </div><!--/.panel-body-->
</div><!--/.panel-->
