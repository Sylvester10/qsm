
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<div class="new-item">
    <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/reports/'.$session.'/'.$term.'/'.$class_id); ?>"><i class="fa fa-line-chart"></i> Manage Reports</a>
</div>

<div class="">
    <h4 class="text-bold">Class Information</h4>
    <p>Class Population: <?php echo $this->common_model->get_class_population($class_id); ?></p>
    <p>Class Level: <?php echo $level; ?></p>
    <p>Class Teacher: <?php echo $this->common_model->get_class_teacher_name($class_id); ?></p>
</div>

<?php 
if (count($class_results) == 0) { ?>

    <h3 class="text-danger">No report produced yet for this class.</h3>

<?php } else { ?>

   <div class="broadsheet_view_area">

        <?php require "application/views/shared/students_report/end_term/real/includes/broadsheet/data.php"; ?>

        <div class="m-t-20">
            <a class="btn btn-lg btn-primary" href="<?php echo base_url($controller.'/print_class_broadsheet/'.$session.'/'.$term.'/'.$class_id); ?>" target="_blank">
                <i class="fa fa-print"></i> Print
            </a>
        </div>

        <p><small>Note: It is best to print in landscape layout. Reduce scale size if content goes beyond the print screen.</small></p>

    </div>
    
<?php } ?>