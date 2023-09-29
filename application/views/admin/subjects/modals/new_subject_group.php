
<div class="modal fade" id="new_subject_group" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-form-sm">
			<div class="modal-header">
				<div class="pull-right">
					<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
				</div>
				<h4 class="modal-title">New Subject Group</h4>
			</div><!--/.modal-header-->
			<div class="modal-body">

				<?php 
				$form_attributes = array("id" => "new_subject_group_form");
				echo form_open('subjects/add_new_subject_group_ajax', $form_attributes); ?>
				
					<div class="form-group">
                        <label class="form-control-label">Subject Group <small>(click comma or Enter key to add a new subject group)</small> </label>
                        <br />
                        <input type="text" class="jq_input_tags tags form-control" name="subject_group" value="<?php echo set_value('subject_group', 'English Studies, Arithmetics'); ?>" required />
                    </div>

					
					<div id="status_msg"></div>
					
					<div>
						<button class="btn btn-primary">Submit </button>
					</div>

				<?php echo form_close(); ?>
				
			</div>
		</div>
	</div>
</div>