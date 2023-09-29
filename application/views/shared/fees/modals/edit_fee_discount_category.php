    
    <!--Edit Fees-->
    <div class="modal fade" id="edit<?php echo $y->id; ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content modal-form-sm">
                <div class="modal-header">
                    <div class="pull-right">
                        <button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
                    </div>
                    <h4 class="modal-title">Edit Discount Category: <?php echo $y->category; ?></h4>
                </div><!--/.modal-header-->
                <div class="modal-body">

                    <?php 
                    echo form_open($this->c_controller.'/edit_fee_discount_category/'.$y->id); ?>

                        <div class="form-group">
                            <label class="form-control-label">Category</label>
                            <input type="text" name="category" value="<?php echo set_value('category', $y->category); ?>" class="form-control" required />
                            <div class="form-error"><?php echo form_error('category'); ?></div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Percentage Discount</label>
                            <select class="form-control" name="discount" required>
                                <?php 
                                for ($percent = 1; $percent <= 100; $percent++) { 
                                    $selected = ($y->discount == $percent) ? 'selected' : NULL; ?>
                                    <option <?php echo $selected; ?> value="<?php echo $percent; ?>" <?php echo set_select('discount', $percent); ?> ><?php echo $percent; ?>%</option>
                                <?php } ?>
                            </select>
                            <div class="form-error"><?php echo form_error('discount'); ?></div>
                        </div>

                        <div>
                            <button class="btn btn-primary">Update</button>
                        </div>
                        
                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
    </div>
    
    