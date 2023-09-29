	
	<div class="modal fade" id="edit_aptitude<?php echo $apt->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Edit: <?php echo $apt->aptitude; ?></h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
				
					<?php echo form_open('settings/edit_aptitude/'.$apt->id); ?>
					
						<div class="form-group">
							<label>Aptitude</label>
							<input type="text" class="form-control" name="aptitude" value="<?php echo $apt->aptitude; ?>"required />
						</div>

						<div class="form-group">
							<label>Domain</label>
							<select class="form-control selectpicker" name="domain" required>
								<option <?php echo ($apt->domain == 'Affective') ? 'selected' : NULL; ?> value="Affective">Affective</option>
								<option <?php echo ($apt->domain == 'Psychomotor') ? 'selected' : NULL; ?> value="Psychomotor">Psychomotor</option>
							</select>
						</div>
						
						<div id="apt_status_msg"></div>
						
						<div class="m-t-20">
							<button class="btn btn-primary">Update </button>
						</div>

					<?php echo form_close(); ?>
					
				</div>
			</div>
		</div>
	</div>