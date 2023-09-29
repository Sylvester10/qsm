
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

    <div class="new-item">
        <button class="btn btn-default btn-sm button-adjust" data-toggle="collapse" data-target="#apply_fee_discount" title="Apply this discount category to a student"><i class="fa fa-plug"></i> Apply Discount</button>
        <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/fee_discount_categories'); ?>"><i class="fa fa-money"></i> All Fee Discounts</a>
    </div>

    <?php
    //apply discount category to student
    require "application/views/shared/fees/includes/apply_fee_discount.php"; ?>


    <p>Name: <?php echo $category; ?></p>
    <p>Percentage Discount: <?php echo $discount; ?>%</p>
    <p>Total Students (using discount): <?php echo count($students_using_discount); ?></p>


    <h3 class="m-t-20">Students currently using this fee discount</h3>

    <div class="row table-scroll">
        <div class="col-md-10 col-sm-12">

            <table id="table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
                <thead>
                    <tr>
                        <th class="w-10-p"> S/N </th>
                        <th class="min-w-150"> Student Name </th>
                        <th class="min-w-150"> Class </th>
                        <th class="min-w-100"> Actions </th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $count = 1;
                    foreach ($students_using_discount as $y) { 

                        $student_id = $y->id;
                        $student_name = $this->common_model->get_student_fullname($student_id);
                        $student_class = $this->common_model->get_class_details($y->class_id)->class;

                        //change discount category
                        require "application/views/shared/fees/modals/change_student_fee_discount_category.php"; 

                        //remove student from category
                        $modal_id = 'remove_student'.$student_id;
                        $title = 'Remove Student from Discount Category';
                        $custom_msg = 'Are you sure to remove ' . $student_name . ' from this discount category? If you continue, this student will be charged the full amount payable by their class';
                        $url = $this->c_controller.'/remove_student_from_fee_discount/'.$student_id;
                        echo modal_confirm_action($modal_id, $title, $custom_msg, $url); ?>
                        
                        <tr>    
                            <td><?php echo $count; ?></td>
                            <td><?php echo $student_name; ?></td>
                            <td><?php echo $student_class; ?></td>
                            <td class="w-15-p text-center">
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#change_discount<?php echo $y->id; ?>"><i class="fa fa-pencil" title="Change discount category for this student"></i></button>
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#remove_student<?php echo $y->id; ?>"><i class="fa fa-times" title="Remove student from discount"></i></button>
                            </td>
                        </tr>

                        <?php $count++;

                    } ?>

                </tbody>
            </table>

        </div><!-- /.col-md-8 -->
    </div><!-- /.row -->
