<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

<div class="new-item">
    <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('students_admin/student_profile/'.$y->id); ?>"><i class="fa fa-user"></i> View Profile</a>
    <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('students_admin/students'); ?>"><i class="fa fa-users"></i> All Students</a>
</div>


<p>Name: <?php echo $student_name; ?></p>
<p>Class: <?php echo $current_class; ?></p>
<p>First Parent: <?php echo $first_parent_name; ?></p>
<p>Second Parent: <?php echo $second_parent_name; ?></p>

<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">

                <h3>Matched Parents</h3>
                <?php
                $total_matched_parents = count($matched_parents);
                $inflect_verb = ($total_matched_parents === 1) ? 'is' : 'are';
                $inflect_parents = ($total_matched_parents === 1) ? 'parent' : 'parents'; ?>
                <p>Here <?php echo $inflect_verb . ' ' . $total_matched_parents . ' ' . $inflect_parents; ?> who may be related to <?php echo $student_name; ?>:</p>

                <table class="table table-no-border">
                    <tbody>
                        <?php
                        foreach ($matched_parents as $p) { 
                            $parent_id = $p->id; ?>
                            <tr>
                                <td><?php echo $p->name; ?></td>
                                <td><a class="btn btn-success btn-xs" href="<?php echo base_url($this->c_controller.'/associate_parent_action/'.$student_id.'/'.$parent_id); ?>">Associate</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <h3>Search Parent</h3>
                <p>If this student's parent is not in the list above, search for them below:</p>
                <div class="auto_complete">
                    <input type="hidden" id="student_id" value="<?php echo $y->id; ?>" />
                    <input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" /> 
                    <div class="form-group">
                        <label class="form-control-label">
                            Search parent by name or email
                            <span class="d_loader" style="display: none"><i class="fa fa-spinner fa-spin"></i></span>
                        </label>
                        <input type="text" id="associate_parent_search" autocomplete="off" class="form-control" placeholder="enter parent name or email" />
                        <ul class="dropdown-menu ajax_dropdown_items parentlist" role="menu" aria-labelledby="dropdownMenu" id="parentlist_dropdown"></ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>