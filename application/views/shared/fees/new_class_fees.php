
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<?php require "application/views/shared/fees/modals/import_fees.php";  ?>
    
<div class="new-item">
    <button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#import_fees" title="Import fees from a previous session and term to current session and term"><i class="fa fa-upload"></i> Import Fees</button>
    <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/manage_fees'); ?>"><i class="fa fa-cog"></i> Manage Fees</a>
</div>


<div class="row">
    <div class="col-md-6 col-sm-12">

        <?php 
        $form_attributes = array("id" => "new_class_fees_form");
        echo form_open($this->c_controller.'/new_class_fees_ajax', $form_attributes); ?>
        
            <?php
            if ( count($fee_categories) == 0 ) { ?>

                <p class="text-danger">No fee category found! <a href="<?php echo base_url($this->c_controller.'/fee_categories'); ?>">Add</a>.</p>

            <?php } else { ?>

                <div class="form-group">
                    <label class="form-control-label">Select Class</label>
                    <select class="form-control selectpicker" name="class_id" required>
                        <option value="">Select Class</option>
                        <?php echo $this->common_model->classes_option_by_section_group(school_id); ?>
                    </select>
                    <div class="form-error"><?php echo form_error('class_id'); ?></div>
                </div>

                <label class="form-control-label">Fees <small>(Leave the amount field blank for fee categories that do not apply to the current class)</small></label>
                <table class="table table-bordered cell-text-middle" style="text-align: left">
                    <thead>
                        <tr>
                            <th class="w-60">Fee Category</th>
                            <th class="w-40">Amount</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $count = 1;
                        foreach ($fee_categories as $c) { 

                            $fee_cat_id = $c->id;
                            $category = $c->category; ?>

                            <input type="hidden" name="fee_cat_id[]" value="<?php echo $fee_cat_id; ?>" />

                            <tr>
                                <td><?php echo $category; ?></td>
                                <td>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="input-group-text"><?php echo s_currency_symbol; ?></span></div>
                                            <input type="text" name="amount[]" value="<?php echo set_value('amount[]'); ?>" class="form-control fee_amount numbers-only" />
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        <?php } ?>

                        <tr>
                            <td>Total</td>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span class="input-group-text"><?php echo s_currency_symbol; ?></span></div>
                                        <input type="text" id="total_fees_payable" class="form-control" readonly />
                                    </div>
                                </div>
                            </td>
                        </tr>

                    </tbody>

                </table>

                <div id="status_msg"></div>
                
                <div>
                    <button class="btn btn-primary btn-lg">Submit</button>
                </div>

            <?php } ?>

        <?php echo form_close(); ?>

    </div>
</div>