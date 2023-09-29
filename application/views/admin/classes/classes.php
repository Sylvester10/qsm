
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<?php require "application/views/admin/classes/modals/new_class.php";  ?>

	<div class="new-item">
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_class"><i class="fa fa-plus"></i> New Class</button>
	</div>

	<div class="row">
		<div class="col-md-10 col-sm-12 col-xs-12">
		  	<div class="panel-group" id="accordion">

		  		<?php
		  		$id = 1; //generate unique ids for the accordion tabs
				foreach ($sections as $s) { ?>

			    	<div class="panel panel-default">
			      		<div class="panel-heading">
			        		<h4 class="panel-title">
			          			<a data-toggle="collapse" data-parent="#accordion" href="#section<?php echo $id; ?>"><?php echo $s->section; ?> Section (<?php echo $this->common_model->count_section_classes(school_id, $s->id); ?>)</a>
			        		</h4>
			      		</div>

			      		<?php $default_tab = ($id == 1) ? 'in' : null; //if first tab, make default using the class ".in" ?>
			      		
			      		<div id="section<?php echo $id; ?>" class="panel-collapse collapse <?php echo $default_tab; ?>">
			        		<div class="panel-body table-scroll">

								<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
									<thead>
										<tr>
											<th> Actions </th>
											<th class="min-w-150"> Class </th>
											<th class="min-w-150"> Class ID </th>
											<th class="min-w-150"> Level </th>
											<th> Order Level </th>
											<th> No. of Students </th>
											<th class="min-w-200"> Class Teacher </th>
											<th class="min-w-200"> Current Term Fees </th>
										</tr>
									</thead>
									<tbody>

										<?php
										$classes = $this->common_model->get_classes_by_section(school_id, $s->id);

										foreach ($classes as $y) { ?>

											<?php 
											$amount_payable = $this->fees_model->get_amount_payable_by_class(current_session_slug, current_term, $y->id);
											require "application/views/admin/classes/modals/class_actions.php";
											//delete confirm modal
											echo modal_delete_confirm($y->id, $y->class, 'class', 'classes/delete_class');  ?>

											<tr>	
												<td class="w-15-p text-center"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#options<?php echo $y->id; ?>"><i class="fa fa-navicon"></i></button></td>
												<td><?php echo $y->class; ?></td>
												<td><?php echo $y->id; ?></td>
												<td><?php echo $y->level; ?></td>
												<td><?php echo $y->order_level; ?></td>
												<td><?php echo $this->common_model->get_class_population($y->id); ?></td>
												<td><?php echo $this->common_model->get_class_teacher_name($y->id); ?></td>
												<td><?php echo $amount_payable; ?>
												</td>
											</tr>

										<?php } ?>

									</tbody>
								</table>
									
			        		</div>
			      		</div>
			    	</div><!--/.panel-group-->
					
					<?php $id++; ?>
			    <?php } //endforeach ?>

		  	</div><!--/#accordion-->

  		</div><!-- /.col-md-12 -->
	</div><!-- /.row -->
