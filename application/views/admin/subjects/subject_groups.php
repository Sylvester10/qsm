
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<?php require "application/views/admin/subjects/modals/new_subject_group.php";  ?>

<div class="new-item">
	<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_subject_group"><i class="fa fa-plus"></i> New Group</button>
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('subjects'); ?>"><i class="fa fa-plus"></i> New Subject</a>
</div>


<div class="row">	
	<div class="col-md-8 table-scroll">	
		<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			<thead>
				<tr>
					<th class="min-w-150"> Subject Group </th>
					<th class="min-w-150"> Number of Subjects </th>
					<th class="min-w-150"> Actions </th>
				</tr>
			</thead>
			<tbody>

				<?php
				foreach ($subject_groups as $y) {

					$total_subjects = count($this->common_model->get_subjects_by_subject_group(school_id, $y->id)); 

					//edit modal
					require "application/views/admin/subjects/modals/edit_subject_group.php";

					//delete confirm modal
					echo modal_delete_confirm($y->id, $y->subject_group, 'subject group', 'subjects/delete_subject_group');  ?>

					<tr>	
						<td><?php echo $y->subject_group; ?></td>
						<td><?php echo $total_subjects; ?></td>
						<td class="w-15-p text-center">
							<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_subject_group<?php echo $y->id; ?>"><i class="fa fa-pencil"></i></button>
							<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"><i class="fa fa-trash"></i></button>
						</td>
					</tr>

				<?php } ?>

				<tr>
					<td class="text-danger">Ungrouped</td>
					<td><?php echo $total_ungrouped_subjects; ?></td>
					<td></td>
				</tr>

			</tbody>
		</table>
	</div>
</div>
