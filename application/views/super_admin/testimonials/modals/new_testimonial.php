	<div class="modal fade" id="new_testimonial" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">New Testimonial</h4>
				</div><!--/.modal-header-->
				<div class="modal-body">

					<?php 
					$form_attributes = array("id" => "new_testimonial_form");
					echo form_open('testimonial/add_new_testimonial_ajax', $form_attributes); ?>
					
						<div class="form-group">
							<label class="form-control-label">Name</label>
							<input type="text" name="name" class="form-control" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">
								Designation 
								<small>(eg Head Teacher, QSM Demo School)</small>
							</label>
							<input type="text" name="designation" class="form-control" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">Rating</label>
							<select class="form-control" name="rating" required>
								<option value="">Select Rating</option>	
								<?php 
								//assumes there are at most 30 weeks in a term
								for ($rating = 1; $rating <= 5; $rating++) { ?>
									<option value="<?php echo $rating; ?>" <?php echo set_select('rating', $rating); ?> ><?php echo $rating; ?></option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<label class="form-control-label">Testimonial</label>
							<textarea name="testimony" class="form-control t100" required></textarea>
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