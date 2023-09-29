    <div class="modal fade" id="new_fee_category" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content modal-form-sm">
                <div class="modal-header">
                    <div class="pull-right">
                        <button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
                    </div>
                    <h4 class="modal-title">New Fee Category</h4>
                </div><!--/.modal-header-->
                <div class="modal-body">

                    <?php 
                    $form_attributes = array("id" => "new_fee_category_form");
                    echo form_open($this->c_controller.'/new_fee_category_ajax', $form_attributes); ?>
                    
                        <div class="form-group">
                            <label class="form-control-label">Category <small>(click comma or Enter key to add a new category)</small> </label>
                            <br />
                            <input type="text" class="jq_input_tags tags form-control" name="category" value="<?php echo set_value('category', 'Tuition, PTA, Sports'); ?>" required />
                            <div class="form-error"><?php echo form_error('category'); ?></div>
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