
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>


<div class="new-item">
    <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/manage_fees'); ?>"><i class="fa fa-cog"></i> Manage Fees</a>
</div>


<div class="row">
    <div class="col-md-6 col-sm-12">

        <?php 
        $form_attributes = array("id" => "edit_class_fees_form");
        echo form_open($this->c_controller.'/edit_class_fees/'.$y->id, $form_attributes); ?>

            <?php
            if ( count($fee_categories) == 0 ) { ?>

                <p class="text-danger">No fee category found! <a href="<?php echo base_url($this->c_controller.'/fee_categories'); ?>">Add</a>.</p>

            <?php } else { ?>

                <input type="hidden" id="fee_id" value="<?php echo $y->id; ?>" />

                <div class="form-group">
                    <label class="form-control-label">Class</label>
                    <select class="form-control selectpicker" name="class_id" required readonly>
                        <option selected value="<?php echo $y->class_id; ?>"><?php echo $class; ?></option>
                    </select>
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
                        foreach ($fee_categories as $c) { 

                            $fee_cat_id = $c->id;
                            $category = $c->category; 
                            $amount = $this->fees_model->get_term_fee_amount($y->id, $fee_cat_id); ?>

                            <input type="hidden" name="fee_cat_id[]" value="<?php echo $fee_cat_id; ?>" />

                            <tr>
                                <td><?php echo $category; ?></td>
                                <td>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="input-group-text"><?php echo s_currency_symbol; ?></span></div>
                                            <input type="text" name="amount[]" value="<?php echo set_value('amount[]', $amount); ?>" class="form-control fee_amount numbers-only" />
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
                    <button class="btn btn-primary btn-lg">Update</button>
                </div>

            <?php } ?>
            
        <?php echo form_close(); ?>

    </div>
</div>