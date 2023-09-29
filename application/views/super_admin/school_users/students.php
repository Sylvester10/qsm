
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<h3 class="text-bold"><?php echo $y->school_name; ?></h3>
<p>School ID: <?php echo $school_id; ?></p>
<p>Number of Students: <?php echo $total_users; ?></p>


<div class="m-b-20">
	<a class="btn btn-default btn-sm" href="<?php echo base_url('school_users/admins/'.$school_id); ?>" title="View Admins"><i class="fa fa-users"></i> Admins</a>
	<a class="btn btn-default btn-sm" href="<?php echo base_url('school_users/staff/'.$school_id); ?>" title="View Staff"><i class="fa fa-users"></i> Staff</a>
	<a class="btn btn-default btn-sm" href="<?php echo base_url('school_users/students/'.$school_id); ?>" title="View Students"><i class="fa fa-users"></i> Students</a>
	<a class="btn btn-default btn-sm" href="<?php echo base_url('school_users/parents/'.$school_id); ?>" title="View Parents"><i class="fa fa-users"></i> Parents</a>
</div>

	
	<div class="table-scroll">
		<table id="school_students_table" class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			
			<input type="hidden" id="school_id" value="<?php echo $school_id; ?>" />
			<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />
			
			<thead>
				<tr>
					<th> Actions </th>
					<th class="min-w-200"> Name </th>
					<th> Sex </th>
					<th class="min-w-100"> <?php echo software_initials; ?> Reg ID </th>
					<th class="min-w-100"> Admission ID </th>
					<th class="min-w-100"> Class </th>
					<th class="min-w-100"> Graduated </th>
					<th class="min-w-100"> Revoked </th>
					<th class="min-w-100"> Password Reset Code </th>
					<th class="min-w-100"> Last Login </th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	