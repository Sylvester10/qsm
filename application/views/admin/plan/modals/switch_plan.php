	
	<div class="modal fade" id="switch_plan" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-width">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Switch Plan</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
				
					<?php 
					$switch_plan_form_attributes = array("id" => "switch_plan_form");
					echo form_open('plan/switch_plan_ajax', $switch_plan_form_attributes); ?>
					
						<div class="form-group">
							<label>Plan</label>
							<select class="form-control" name="plan_id" required>
										
								<?php 
								foreach ($plans as $p) {
									//check which plan was pre-selected and assign 'selected' attribute to it
									$selected = ($p->id == school_plan_id) ? 'selected' : NULL;
									$p_price = $this->common_model->get_plan_price_by_location($p->id); ?>
									<option <?php echo $selected; ?> value="<?php echo $p->id; ?>"><?php echo $p->plan; ?> (<?php echo $p_price; ?>)</option>
								<?php } ?>
								
							</select>
						</div>
						
						<div id="sp_status_msg"></div>
						
						<div class="m-t-20">
							<button class="btn btn-primary">Switch</button>
						</div>

					<?php echo form_close(); ?>
					
				</div>
			</div>
		</div>
	</div>