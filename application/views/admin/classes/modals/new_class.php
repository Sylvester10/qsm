<div class="modal fade" id="new_class" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-form-sm">
			<div class="modal-header">
				<div class="pull-right">
					<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
				</div>
				<h4 class="modal-title">New Class</h4>
			</div><!--/.modal-header-->
			<div class="modal-body">

				<?php 
				$form_attributes = array("id" => "new_class_form");
				echo form_open('classes/add_new_class_ajax', $form_attributes); ?>
				
					<div class="form-group">
						<label class="form-control-label">Class <small>(e.g. Primary 1A, JSS 1B, SS 3C)</small></label>
						<input type="text" name="class" class="form-control" required />
					</div>

					<div class="form-group">
						<label class="form-control-label">Level <small>(e.g. Primary 1, JSS 1, SS 3)</small></label>
						<input type="text" name="level" class="form-control" required />
					</div>

					<div class="form-group">
						<label class="form-control-label">Order Level <small>(Required to help with ordering)</small></label>
						<select class="form-control selectpicker" name="order_level">
							<option value="">Select</option>
							<?php 
							for ($i = 1; $i <= 99; $i++) { 
								$selected = ($i == $next_level) ? 'selected' : NULL; ?>
								<option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
							<?php } ?>
						</select>
					</div>
					
					<div class="form-group">
						<label class="form-control-label">Section</label>
						<select class="form-control selectpicker" name="section_id" required>
							<?php 
							//check if any section has been created
							if ( count($sections) === 0 ) { ?>
							
								<option value="">No sections found!</option>
							
							<?php } else { ?>
							
								<option value="">Select Section</option>
								<?php
								//list the sections
								foreach ($sections as $s) { ?>
									<option value="<?php echo $s->id; ?>"><?php echo $s->section; ?></option>
								<?php } //endforeach ?>
								
							<?php } //endif ?>
						</select>
					</div>
					
					<div class="form-group">
						<label class="form-control-label">
							Class Teacher 
							<br />
							<small>Note: Only staff assigned the role of Class Teacher can be assigned a class.</small>
						</label>
						<select class="form-control selectpicker" name="class_teacher_id">
							<?php 
							//check if any section has been created
							if ( count($teachers) === 0 ) { ?>
							
								<option value="">No teachers found! Leave unassigned</option>
							
							<?php } else { ?>
							
								<option value="">Leave unassigned</option>
								
								<?php
								//list the teachers
								foreach ($teachers as $class_teacher_id) { 
									$name = $this->common_model->get_staff_details_by_id($class_teacher_id)->name; ?>
									<option value="<?php echo $class_teacher_id; ?>"><?php echo $name; ?></option>
								<?php } //endforeach ?>
								
							<?php } //endif ?>
						</select>
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