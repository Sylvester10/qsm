
<div class="modal fade" id="modal_mt_report_template_image" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content modal-form">
            <div class="modal-header">
                <div class="pull-right">
                    <button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
                </div>
                <h4 class="modal-title">Mid-Term Report: Template <?php echo $y->mt_template_id; ?></h4>
            </div><!--/.modal-header-->
            <div class="modal-body">

                <?php 
                $image = $this->settings_model->get_mid_term_report_template_details($y->mt_template_id)->image; 
                $image_src = base_url('assets/images/mid_term_report_templates/'.$image); 
                $template_url = base_url() . 'mid_term_report_templates/template_' . $y->mt_template_id; ?>

                <img class="img-responsive" src="<?php echo $image_src; ?>" />

                <div class="m-t-30 text-center">
                    <a class="btn btn-primary" href="<?php echo base_url('settings/mid_term_report_settings'); ?>#report_templates" target="_blank">Change</a>
                    <a class="btn btn-primary" href="<?php echo $template_url; ?>" target="_blank">View Sample</a>
                </div>

            </div>
        </div>
    </div>
</div>