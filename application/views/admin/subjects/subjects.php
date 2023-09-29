
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<?php require "application/views/admin/subjects/modals/new_subject.php";  ?>

	<div class="new-item">
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_subject"><i class="fa fa-plus"></i> New Subject</button>
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('subjects/subject_groups'); ?>"><i class="fa fa-plus"></i> New Group</a>

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
			          			<a data-toggle="collapse" data-parent="#accordion" href="#section<?php echo $id; ?>"><?php echo $s->section; ?> Section (<?php echo $this->common_model->count_section_subjects(school_id, $s->id); ?>)</a>
			        		</h4>
			      		</div>
			      		<?php $default_tab = ($id == 1) ? 'in' : null; //if first tab, make default using the class ".in" ?>
			      		<div id="section<?php echo $id; ?>" class="panel-collapse collapse <?php echo $default_tab; ?>">
			        		<div class="panel-body table-scroll">
			        			
			        			<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
									<thead>
										<tr>
											<th class=""> S/N </th>
											<th class="min-w-150"> Subject </th>
											<th class="min-w-150"> Short Name </th>
											<th class="min-w-150"> Subject Group </th>
											<th class="min-w-150"> Actions </th>
										</tr>
									</thead>
									<tbody>

										<?php
										$subjects = $this->common_model->get_subjects_by_section(school_id, $s->id);
										
										$count = 1;
										foreach ($subjects as $y) { 

											if ($y->subject_group_id != NULL) {
												$subject_group = $this->common_model->get_subject_group_details($y->subject_group_id)->subject_group;
											} else {
												$subject_group = 'Ungrouped';
											}
											
											//edit modal
											require "application/views/admin/subjects/modals/edit_subject.php";

											//delete confirm modal
											echo modal_delete_confirm($y->id, $y->subject, 'subject', 'subjects/delete_subject');  ?>

											<tr>	
												<td><?php echo $count; ?></td>
												<td><?php echo $y->subject; ?></td>
												<td><?php echo $y->subject_short; ?></td>
												<td><?php echo $subject_group; ?></td>
												<td class="w-15-p text-center">
													<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_subject<?php echo $y->id; ?>"><i class="fa fa-pencil"></i></button>
													<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"><i class="fa fa-trash"></i></button>
												</td>
											</tr>

											<?php $count++;

										} ?>

									</tbody>
								</table>
									
			        		</div>
			      		</div>
			    	</div><!--/.panel-group-->
					
					<?php $id++; ?>
			    <?php } //endforeach ?>

		  	</div><!--/#accordion-->

  		</div><!-- /.col-md-8 -->
	</div><!-- /.row -->
