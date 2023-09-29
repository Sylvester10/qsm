
<div class="modal fade" id="new_subject" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-form-sm">
			<div class="modal-header">
				<div class="pull-right">
					<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
				</div>
				<h4 class="modal-title">New Subject</h4>
			</div><!--/.modal-header-->
			<div class="modal-body">

				<?php 
				$form_attributes = array("id" => "new_subject_form");
				echo form_open('subjects/add_new_subject_ajax', $form_attributes); ?>
				
					<div class="form-group">
						<label class="form-control-label">Subject</label>
						<input type="text" name="subject" id="the_subject" value="<?php echo set_value('subject'); ?>" class="form-control" required />
					</div>

					<div class="form-group">
						<label class="form-control-label">Short Name 
							<br />
							<small>(E.g. Math for Mathematics, Eng for English Language, CRK for Christian Religious Knowledge, etc. Max of 6 characters).</small> 
						</label>
						<input type="text" name="subject_short" id="subject_short" class="form-control" maxlength="6" required />
					</div>

					<div class="form-group">
						<label class="form-control-label">Subject Group <small>(e.g. English Studies encompassing English Language, Phonics, Diction, Verbal Reasoning, etc)</small></label>
						<select class="form-control selectpicker" name="subject_group_id">
							
							<?php 
							//check if any subject group has been created
							if ( count($subject_groups) === 0 ) { ?>
							
								<option value="">No subject groups found! Leave Ungrouped</option>
							
							<?php } else { ?>

								<option value="">Ungrouped</option>
							
								<?php echo $subject_group_options; ?>
								
							<?php } //endif ?>

						</select>
					</div>
					
					<div class="form-group">
						<label class="form-control-label">Section(s)</label>
						<select class="form-control selectpicker" name="section_id[]" multiple required>
							
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
					
					<div id="status_msg"></div>
					
					<div>
						<button class="btn btn-primary">Submit </button>
					</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>
</div>