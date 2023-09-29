
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<?php require "application/views/admin/weekly_reports/modals/new_report_type.php"; ?>

<div class="new-item">
	<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_report_type"><i class="fa fa-plus"></i> New Type</button>
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('weekly_reports_admin/weekly_reports/'.$session.'/'.$term); ?>"><i class="fa fa-eye"></i> View Reports</a>
</div>


<div class="row">	
	<div class="col-md-8 table-scroll">	
		<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			<thead>
				<tr>
					<th class="min-w-5"> S/N </th>
					<th class="min-w-150"> Type </th>
					<th class="min-w-150"> Number of Reports </th>
					<th class="min-w-150"> Actions </th>
				</tr>
			</thead>
			<tbody>

				<?php
				$count = 1;
				foreach ($weekly_report_types as $y) {

					$total_reports = count($this->weekly_reports_model->get_weekly_reports_by_type($y->id)); 

					//edit modal
					require "application/views/admin/weekly_reports/modals/edit_report_type.php";

					//delete confirm modal
					echo modal_delete_confirm($y->id, $y->type, 'report type', 'weekly_reports_admin/delete_report_type');  ?>

					<tr>	
						<td><?php echo $count; ?></td>
						<td><?php echo $y->type; ?></td>
						<td><?php echo $total_reports; ?></td>
						<td class="w-15-p text-center">
							<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit_report_type<?php echo $y->id; ?>"><i class="fa fa-pencil"></i></button>
							<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"><i class="fa fa-trash"></i></button>
						</td>
					</tr>

					<?php $count++;

				} ?>

			</tbody>
		</table>
	</div>
</div>
