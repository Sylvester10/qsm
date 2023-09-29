
<div class="modal fade" id="modal_report_template_image" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <div class="pull-right">
                    <button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
                </div>
                <h4 class="modal-title">End of Term Report: Template <?php echo $y->template_id; ?></h4>
            </div><!--/.modal-header-->
            <div class="modal-body">

                <?php 
                $image = $this->settings_model->get_report_template_details($y->template_id)->image; 
                $image_src = base_url('assets/images/report_templates/'.$image); 
                $template_url = base_url() . 'report_templates/template_' . $y->template_id; ?>

                <img class="img-responsive" src="<?php echo $image_src; ?>" />

                <div class="m-t-30 text-center">
                    <a class="btn btn-primary" href="<?php echo base_url('settings/report_settings'); ?>#report_templates" target="_blank">Change</a>
                    <a class="btn btn-primary" href="<?php echo $template_url; ?>" target="_blank">View Sample</a>
                </div>

            </div>
        </div>
    </div>
</div>