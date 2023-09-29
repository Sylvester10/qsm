
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<p>Demo School ID: <?php echo demo_school_id; ?></p>

	
	<div class="table-scroll">
		<table id="demo_students_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th> Actions </th>
					<th class="min-w-200"> Name </th>
					<th class="min-w-200"> Reg ID </th>
					<th class="min-w-200"> Class </th>
					<th class="min-w-100"> Sex </th>
					<th class="min-w-150"> Demo Role </th>
					<th class="min-w-100"> Last Login </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	