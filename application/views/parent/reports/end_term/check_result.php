
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>

<div class="row">
	<div class="col-md-6">

		<h4 class="text-bold f-s-18"><?php echo $child_name; ?></h4>
		<p>Select session, term and class whose result you seek.</p>

		<?php 
		$form_attributes = array("id" => "check_end_term_result_form");
		echo form_open($this->c_controller.'/check_result_ajax/'.$child_id, $form_attributes); ?>

			<input type="hidden" id="child_id" value="<?php echo $child_id; ?>" />

			<div class="form-group">
				<label class="form-control-label">Session</label>
				<select class="form-control" name="session" id="session" required>

					<?php if (count($result_sessions) == 0) { ?>

						<option value="">No result found</option>

					<?php } else { ?>
					
						<?php
						foreach ($result_sessions as $s) { 

							$session_slug = $s->session;
							$session = get_the_session($session_slug); 
							$selected = ($session_slug == current_session_slug) ? 'selected' : NULL; ?>

							<option <?php echo $selected; ?> value="<?php echo $session_slug; ?>"><?php echo $session; ?></option>

						<?php } ?>

					<?php } ?>
					
				</select>
			</div>
			
		
			<div class="form-group">
				<label class="form-control-label">Term</label>
				<select class="form-control" name="term" id="term" required>
					
					<?php $terms = array('1st', '2nd', '3rd'); 
					
					foreach ($terms as $term) { 
						$selected = ($term == current_term) ? 'selected' : NULL; ?>
						
						<option <?php echo $selected; ?> value="<?php echo $term; ?>" <?php echo set_select('term', $term); ?> ><?php echo $term; ?></option>
						
					<?php } ?>
					
				</select>
			</div>
			

			<div class="form-group">
				<label class="form-control-label">Class <small>(<?php echo $child_fname; ?>'s class in selected session and term)</small></label>
				<select class="form-control selectpicker" name="class_id" id="class_id" required>

					<?php
					foreach ($result_sessions as $s) { 

						$class = $this->common_model->get_class_details($s->class_id)->class;
						$selected = ($s->class_id == $current_class_id) ? 'selected' : NULL; ?>

						<option <?php echo $selected; ?> value="<?php echo $s->class_id; ?>"><?php echo $class; ?></option>

					<?php } ?>

				</select>
			</div>
			

			<div id="status_msg"></div>

			<div class="m-t-20">
				<button class="btn btn-primary btn-lg">Check</button>
				<span id="d_loader" style="display: none"><i class="fa fa-spinner fa-spin"></i> Checking...please wait.</span>
			</div>

		<?php echo form_close(); ?>

	</div>
</div>
