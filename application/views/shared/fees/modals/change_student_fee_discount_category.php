    
    <!--Edit Fees-->
    <div class="modal fade" id="change_discount<?php echo $student_id; ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content modal-form-sm">
                <div class="modal-header">
                    <div class="pull-right">
                        <button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
                    </div>
                    <h4 class="modal-title">Change Discount Category: <?php echo $student_name; ?></h4>
                </div><!--/.modal-header-->
                <div class="modal-body">

                    <?php 
                    echo form_open($this->c_controller.'/change_student_fee_discount_category/'.$student_id); ?>

                        <div class="form-group">
                            <label class="form-control-label">Discount Category</label>
                            <select class="form-control" name="fee_discount_cat_id" required>
                                
                                <?php 
                                foreach ($fee_discount_categories as $d) { 
                                    $selected = ($y->fee_discount_cat_id == $d->id) ? 'selected' : NULL; ?>
                                    <option <?php echo $selected; ?> value="<?php echo $d->id; ?>" <?php echo set_select('fee_discount_cat_id', $d->id); ?> ><?php echo $d->category; ?></option>
                                <?php } ?>

                            </select>
                            <div class="form-error"><?php echo form_error('fee_discount_cat_id'); ?></div>
                        </div>

                        <div>
                            <button class="btn btn-primary">Change</button>
                        </div>
                        
                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
    </div>
    
    