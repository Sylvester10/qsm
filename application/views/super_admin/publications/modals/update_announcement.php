    
    <div class="modal fade" id="update_announcement" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content modal-form">
                <div class="modal-header">
                    <div class="pull-right">
                        <button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
                    </div>
                    <h4 class="modal-title">Update Announcement</h4>
                </div><!--/.modal-header-->
                <div class="modal-body">

                    <?php 
                    $form_attributes = array("id" => "update_announcement_form");
                    echo form_open('publications_super_admin/update_announcement_ajax', $form_attributes); ?>
                    
                        <div class="form-group">
                            <label>Announcement</label>
                            <textarea class="form-control t150" name="announcement" required><?php echo $announcement->announcement; ?></textarea>
                        </div>
                        
                        <div id="update_status_msg"></div>
                        
                        <div>
                            <button class="btn btn-primary"> <i class="fa fa-arrow-circle-up"></i> Update </button>
                        </div>

                    <?php echo form_close(); ?>
                    
                </div>
            </div>
        </div>
    </div>