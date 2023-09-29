    <div class="modal fade" id="new_fee_discount_category" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content modal-form-sm">
                <div class="modal-header">
                    <div class="pull-right">
                        <button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
                    </div>
                    <h4 class="modal-title">New Discount Category</h4>
                </div><!--/.modal-header-->
                <div class="modal-body">

                    <?php 
                    $form_attributes = array("id" => "new_fee_discount_category_form");
                    echo form_open($this->c_controller.'/new_fee_discount_category_ajax', $form_attributes); ?>
                    
                        <div class="form-group">
                            <label class="form-control-label">Category <small>(e.g. staff children, scholarship, etc)</small></label>
                            <br />
                            <input type="text" class="form-control" name="category" value="<?php echo set_value('category'); ?>" required />
                            <div class="form-error"><?php echo form_error('category'); ?></div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Percentage Discount</label>
                            <select class="form-control" name="discount" required>
                                <option value="">Select Discount</option>   
                                <?php 
                                for ($percent = 1; $percent <= 100; $percent++) { ?>
                                    <option value="<?php echo $percent; ?>" <?php echo set_select('discount', $percent); ?> ><?php echo $percent; ?>%</option>
                                <?php } ?>
                            </select>
                            <div class="form-error"><?php echo form_error('discount'); ?></div>
                        </div>

                        <div id="status_msg"></div>
                        
                        <div>
                            <button class="btn btn-primary">Submit </button>
                        </div>

                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
    </div>