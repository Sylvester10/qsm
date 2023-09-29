	
	<!--Class Options-->
	<div class="modal fade" id="options<?php echo $y->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-width">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Actions: <?php echo $y->title; ?></h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					
					<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#edit<?php echo $y->id; ?>"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Video </a></p>
					
					<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete Class </a></p>
					
				</div>
			</div>
		</div>
	</div>
	
	
	
	<!--Edit Video-->
	<div class="modal fade" id="edit<?php echo $y->id; ?>" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content modal-form-sm">
				<div class="modal-header">
					<div class="pull-right">
						<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
					</div>
					<h4 class="modal-title">Edit Video: <?php echo $y->title; ?></h4>
				</div><!--/.modal-header-->
				<div class="modal-body">
					<?php 
					echo form_open('super_admin/edit_video/'.$y->id); ?>
					
						<div class="form-group">
							<label class="form-control-label">Title</label>
							<input type="text" name="title" value="<?php echo $y->title; ?>" class="form-control" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">Description</label>
							<input type="text" name="description" value="<?php echo $y->description; ?>" class="form-control" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">Url</label>
							<input type="text" name="url" value="<?php echo $y->url; ?>" class="form-control" required />
						</div>

						<div class="form-group">
							<label class="form-control-label">Category</label>
							<input type="text" name="category" value="<?php echo $y->category; ?>" class="form-control" required />
						</div>
						
						<div>
							<button class="btn btn-primary">Update</button>
						</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
	
	