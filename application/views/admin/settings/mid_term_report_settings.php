<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">


        <h3>Achievement Test Settings</h3>
        <div class="row">
            <div class="col-md-8">
                <a href="<?php echo base_url('sections'); ?>" class="underline-link">Go to sections</a> to configure Achievement Test parameters such as classwork, homework and tests.
            </div>
        </div>



        <hr class="m-b-45">


            
        <h3>Report Evaluation</h3>
        <!--Report Remarks-->
        <div class="row">
            <div class="col-md-10 table-scroll">

                <?php 
                $form_attributes_eval = array("id" => "mt_report_evaluation_form");
                echo form_open('settings/update_mid_term_report_evaluation_ajax', $form_attributes_eval); ?>

                    <table class="table table-no-border cell-text-middle" style="text-align: left">
                        
                        <thead>
                            <tr>
                                <th class="w-5"> S/N </th>
                                <th class="w-10"> Range (% conversion) </th>
                                <th class="w-5"> Letter Grade </th>
                                <th class="w-25"> Evaluation </th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $count = 1;
                            foreach ($evaluation as $eval) { ?>

                                <input type="hidden" class="form-control" name="evaluation_id[]" value="<?php echo $eval->id; ?>" />

                                <tr>    

                                    <td><?php echo $count; ?></td>

                                    <td><?php echo $eval->range; ?></td>

                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="grade[]" value="<?php echo $eval->grade; ?>" maxlength="2" required />
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="evaluation[]" value="<?php echo $eval->evaluation; ?>" required />
                                        </div>
                                    </td>

                                </tr>

                                <?php $count++; ?>
                            <?php } ?>

                        </tbody>
                    </table>

                    <div id="status_msg_evaluation"></div>

                    <div class="form-group">
                        <button class="btn btn-success btn-lg">Update</button>
                    </div>

                <?php echo form_close(); ?>

            </div>
        </div>


        
        <hr class="m-b-45">

        
        <section id="report_templates">

            <h3>Report Templates</h3>
                    
            <?php
            foreach ($report_templates as $t) { 
            
                $template_id = $t->template_id; 
                $template_url = 'mid_term_report_templates/template_'.$template_id;
                $active = $t->active;
                $template_sections = $this->settings_model->get_sections_by_mid_term_report_template(school_id, $template_id);
                $image_src = ($t->image != NULL) ? ('assets/images/mid_term_report_templates/'.$t->image) : ('assets/images/qsm.jpg');

                require "application/views/admin/settings/modals/attach_section_mid_term_report.php";  ?>

                <div class="row report_template_sample_row">

                    <div class="col-md-6">

                        <img class="report_template_image img-responsive" src="<?php echo base_url($image_src); ?>" alt="Template <?php echo $template_id; ?>" />

                    </div>

                    <div class="col-md-6">
                    
                        <div class="m-t-10 m-b-10">
                            <h3 class="text-bold">Template <?php echo $template_id; ?></h3>
                        </div>

                        <div class="">

                            <p class="">
                                <b>Description</b> <br />
                                <?php echo $t->description; ?>  
                            </p>
                            <p class="m-b-10">
                                <b>Support</b> <br />
                                <?php echo $t->support; ?>  
                            </p>

                            <a class="btn btn-default" href="<?php echo base_url($template_url); ?>" target="_blank"><i class="fa fa-eye"></i> View Sample</a>

                            <?php 
                            if ($active == 'true') { ?>

                                <button class="btn btn-default" data-toggle="modal" data-target="#attach_section<?php echo $template_id; ?>"><i class="fa fa-plug"></i> Attach to Section</button>

                            <?php } else { ?>

                                <button class="btn btn-default" disabled><i class="fa fa-plug"></i> Attach to Section</button>

                            <?php } ?>

                        </div>

                    </div>  
                    
                    <?php if ($active != 'true') { ?>
                        <div class="overlay">
                            <h3 class="text-center">Template still in development. <br /> Please check back later.</h3>
                        </div>
                    <?php } ?>
                    
                </div>

                <hr class="m-t-30 m-b-15">

            <?php } ?>

        </section>


                    
    </div>
</div>
