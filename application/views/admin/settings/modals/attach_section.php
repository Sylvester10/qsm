
<div class="modal fade" id="attach_section<?php echo $template_id; ?>" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-width">
			<div class="modal-header">
				<div class="pull-right">
					<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
				</div>
				<h4 class="modal-title">Attach Section: Template <?php echo $template_id; ?></h4>
			</div><!--/.modal-header-->
			<div class="modal-body">

				<div class="m-b-15">
					<b>Attached Sections</b> <br />
					<?php
					$i = 0;
					foreach ($template_sections as $ts) { 
						$i++;
						echo ($i == count($template_sections)) ?  $ts->section : ($ts->section. ', ');
					} ?>
				</div>

				<?php echo form_open('settings/attach_report_template/'.$template_id); ?>
					
					<div class="form-group">
						<label class="form-control-label">Section</label>
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
					
					<div>
						<button class="btn btn-primary">Attach</button>
					</div>

				<?php echo form_close(); ?>

			</div>
		</div>
	</div>
</div>