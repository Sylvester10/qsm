
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

    <?php require "application/views/shared/fees/modals/new_fee_discount_category.php";  ?>

    <div class="new-item">
        <button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_fee_discount_category"><i class="fa fa-plus"></i> New Discount Category</button>
    </div>

    <?php if (count($fee_discount_categories) == 0) { ?>

        <h3 class="text-danger">No discount category found!</h3>

    <?php } else { ?>

        <div class="row table-scroll">
            <div class="col-md-10 col-sm-12">

                <table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
                    <thead>
                        <tr>
                            <th class="w-10-p"> S/N </th>
                            <th class="min-w-150"> Fee Category </th>
                            <th class="min-w-150"> Percentage Discount </th>
                            <th class="min-w-150"> Number of Students </th>
                            <th class="min-w-150"> Actions </th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $count = 1;
                        foreach ($fee_discount_categories as $y) { 

                            $total_students_using_discount = count($this->fees_model->get_all_students_on_fee_discount($y->id));

                            echo modal_delete_confirm($y->id, "Fee Category: {$y->category}", 'discount category', "{$this->c_controller}/delete_fee_discount_category");

                            require "application/views/shared/fees/modals/edit_fee_discount_category.php"; ?>
                            
                            <tr>    
                                <td><?php echo $count; ?></td>
                                <td><?php echo $y->category; ?></td>
                                <td><?php echo $y->discount; ?>%</td>
                                <td>

                                    <?php if ($total_students_using_discount > 0) { ?>
                                        <div class="pull-right">
                                            <a href="<?php echo base_url($this->c_controller.'/fee_discount_category/'.$y->id); ?>">View</a>
                                        </div>
                                    <?php } ?>
                                    <?php echo $total_students_using_discount; ?>

                                </td>
                                <td class="text-center">
                                    <a class="btn btn-success btn-sm" href="<?php echo base_url($this->c_controller.'/fee_discount_category/'.$y->id); ?>"><i class="fa fa-eye" title="View discount category and manage application"></i></a>
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit<?php echo $y->id; ?>"><i class="fa fa-pencil" title="Edit fees"></i></button>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"><i class="fa fa-trash" title="Delete discount category"></i></button>
                                </td>
                            </tr>

                            <?php $count++;

                        } ?>

                    </tbody>
                </table>

            </div><!-- /.col-md-8 -->
        </div><!-- /.row -->

    <?php } ?>
