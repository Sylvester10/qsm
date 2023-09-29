
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>


<?php 
//check if admin
if ($this->c_user == 'admin' || $this->c_user == 'staff') { ?>

    <div class="new-item">
        <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/produce_report/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id); ?>"><i class="fa fa-pencil"></i> Edit Report</a>
        <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/reports/'.$session.'/'.$term.'/'.$class_id); ?>"><i class="fa fa-line-chart"></i> All Reports</a>
    </div>

<?php } ?>

<div class="view_report_card">

    <div class="kad_report_card">

        <?php 
        $template_folder = $category;
        require 'application/views/shared/students_report/end_term/real/bespoke/kad_academy/'.$template_folder.'/data.php'; ?>
        
        <div class="m-t-30">
            <a class="btn btn-lg btn-primary" href="<?php echo base_url($this->c_controller.'/print_report/'.$template_id.'/'.$session.'/'.$term.'/'.$class_id.'/'.$student_id); ?>" target="_blank">
                <i class="fa fa-print"></i> Print
            </a>
        </div>
        
    </div><!--/.kad_report_card-->
    
</div><!--/.view_report_card-->

<div class="not_desktop"><!--Show on mobile and tablets-->
    <h3 class="text-danger">For best presentation, please view this page on a computer.</h3>
</div>