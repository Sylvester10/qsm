<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

<div class="new-item">
    <a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('parents'); ?>"><i class="fa fa-users"></i> All Parents</a>
</div>

<p>First Parent: <?php echo $y->name; ?></p>
<p>Second Parent: <?php echo $y->sec_parent_name; ?></p>

<div class="m-b-30">
    <h4><b>Child(ren) / Ward(s): <?php echo count($children); ?> </b></h4>
    <?php
    //List parents children
    $p_children = "";
    foreach ($children as $c) {
        $child_name = $this->common_model->get_student_fullname($c->id);
        $p_children .= '<div><i class="fa fa-child"></i> <a href="' . base_url('students_admin/edit_student/'.$c->id) . '" target="_blank" title="View/edit ' . $child_name . '">' . $child_name . '</a></div>';
    } 
    echo $p_children; ?>
</div>

<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">

                <h3>Matched Students</h3>
                <?php
                $total_matched_students = count($matched_students);
                $inflect_verb = ($total_matched_students === 1) ? 'is' : 'are';
                $inflect_students = ($total_matched_students === 1) ? 'student' : 'students'; ?>
                <p>Here <?php echo $inflect_verb . ' ' . $total_matched_students . ' ' . $inflect_students; ?> who may be related to <?php echo $y->name; ?>:</p>

                <table class="table table-no-border">
                    <tbody>
                        <?php
                        foreach ($matched_students as $s) { 
                            $student_id = $s->id;
                            $student_name = $this->common_model->get_student_fullname($student_id); ?>
                            <tr>
                                <td><?php echo $student_name; ?></td>
                                <td><a class="btn btn-success btn-xs" href="<?php echo base_url($this->c_controller.'/associate_student_action/'.$parent_id.'/'.$student_id); ?>">Associate</a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <h3>Search Student</h3>
                <p>If this parents's child/ward is not in the list above, search for them below:</p>
                <div class="auto_complete">
                    <input type="hidden" id="parent_id" value="<?php echo $parent_id; ?>" />
                    <input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" /> 
                    <div class="form-group">
                        <label class="form-control-label">
                            Search student by firstname, lastname, admission ID or Reg ID
                            <span class="d_loader" style="display: none"><i class="fa fa-spinner fa-spin"></i></span>
                        </label>
                        <input type="text" id="associate_student_search" autocomplete="off" class="form-control" placeholder="enter search keyword" />
                        <ul class="dropdown-menu ajax_dropdown_items studentlist" role="menu" aria-labelledby="dropdownMenu" id="studentlist_dropdown"></ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>