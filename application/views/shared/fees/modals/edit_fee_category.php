    
    <!--Edit Fees-->
    <div class="modal fade" id="edit<?php echo $y->id; ?>" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content modal-form-sm">
                <div class="modal-header">
                    <div class="pull-right">
                        <button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
                    </div>
                    <h4 class="modal-title">Edit Fee Category: <?php echo $y->category; ?></h4>
                </div><!--/.modal-header-->
                <div class="modal-body">

                    <?php 
                    echo form_open($this->c_controller.'/edit_fee_category/'.$y->id); ?>

                        <div class="form-group">
                            <label class="form-control-label">Category <small>(E.g. Tuition, PTA, Sports, etc)</small></label>
                            <input type="text" name="category" value="<?php echo set_value('category', $y->category); ?>" class="form-control" required />
                            <div class="form-error"><?php echo form_error('category'); ?></div>
                        </div>

                        <div>
                            <button class="btn btn-primary">Update</button>
                        </div>
                        
                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
    </div>
    
    