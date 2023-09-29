
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

    <?php require "application/views/shared/fees/modals/new_fee_category.php";  ?>

    <div class="new-item">
        <button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_fee_category"><i class="fa fa-plus"></i> New Category</button>
    </div>

    <?php if (count($fee_categories) == 0) { ?>

        <h3 class="text-danger">No fee category found!</h3>

    <?php } else { ?>

        <div class="row table-scroll">
            <div class="col-md-8 col-sm-12">

                <table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
                    <thead>
                        <tr>
                            <th class="w-10-p"> S/N </th>
                            <th class="min-w-100"> Fee Category </th>
                            <th class="min-w-100"> Actions </th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $count = 1;
                        foreach ($fee_categories as $y) { 

                            echo modal_delete_confirm($y->id, "Fee Category: {$y->category}", 'fee category', "{$this->c_controller}/delete_fee_category");

                            require "application/views/shared/fees/modals/edit_fee_category.php"; ?>
                            
                            <tr>    
                                <td><?php echo $count; ?></td>
                                <td><?php echo $y->category; ?></td>
                                <td class="w-15-p text-center">
                                    <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit<?php echo $y->id; ?>"><i class="fa fa-pencil" title="Edit fees"></i></button>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"><i class="fa fa-trash" title="Delete fee category"></i></button>
                                </td>
                            </tr>

                            <?php $count++;

                        } ?>

                    </tbody>
                </table>

            </div><!-- /.col-md-8 -->
        </div><!-- /.row -->

    <?php } ?>
