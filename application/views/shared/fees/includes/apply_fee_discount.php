<div class="collapse m-b-20" id="apply_fee_discount">

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="auto_complete">
                        <input type="hidden" id="discount_cat_id" value="<?php echo $discount_cat_id; ?>" />
                        <input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" /> 
                        <div class="form-group">
                            <label class="form-control-label">
                                Search Student
                                <span class="d_loader" style="display: none"><i class="fa fa-spinner fa-spin"></i></span>
                            </label>
                            <input type="text" id="fee_discount_student_apply" autocomplete="off" class="form-control" placeholder="enter registration/admission ID or student name" />
                            <ul class="dropdown-menu ajax_dropdown_items studentlist" role="menu" aria-labelledby="dropdownMenu" id="studentlist_dropdown"></ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>