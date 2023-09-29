	<div class="modal fade" id="import_fees" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Import Fees</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">

					<p>Import fee details from a previous session and term into the current session and term (<?php echo $the_session . ' session, ' . $term; ?> term). You can modify later.</p>

					<?php 
					$form_attributes = array("id" => "import_fees_form");
					echo form_open($this->c_controller.'/import_fees_ajax', $form_attributes); ?>
					
						<div class="form-group">
							<label class="form-control-label">Session</label>
							<select class="form-control" name="session" id="session" required>
								
								<?php if (count($fee_sessions) === 0) { ?>

									<option value="">No fee details found</option>

								<?php } else { ?>
								
									<?php
									foreach ($fee_sessions as $s) {
										$session = $s->session; 
										$the_session = get_the_session($session); ?>
										<option value="<?php echo $session; ?>"><?php echo $the_session; ?></option>
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

						<div id="import_status_msg"></div>
						
						<div>
							<button class="btn btn-primary">Import</button>
							<span class="d_loader" style="display: none"><i class="fa fa-spinner fa-spin text-success"></i></span>
						</div>

					<?php echo form_close(); ?>

				</div>
			</div>
		</div>
	</div>