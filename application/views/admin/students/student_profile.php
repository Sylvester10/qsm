	
<div class="new-item">
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('students_admin/edit_student/'.$y->id); ?>"><i class="fa fa-pencil"></i> Edit Student</a>
	<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url('students_admin/students'); ?>"><i class="fa fa-users"></i> All Students</a>
</div>

<div class="row">

	<div class="col-md-4">
		<h3 class="text-bold"><i class="fa fa-graduation-cap f-s-30"></i> Student Information</h3>
		<p><b>Name:</b> <?php echo $student_name; ?></p>
		<p><b>Registration ID:</b> <?php echo $y->reg_id; ?></p>
		<p><b>Admission ID:</b> <?php echo $y->admission_id; ?></p>
		<p><b>Class:</b> <?php echo $class; ?></p>
	</div>

	<div class="col-md-4">
		<h3 class="text-bold"><i class="fa fa-user f-s-30"></i> Bio Information</h3>
		<p><b>Sex:</b> <?php echo $y->sex; ?></p>
		<p><b>Date of Birth:</b> <?php echo x_date_full($y->date_of_birth); ?></p>
		<p><b>Blood Group:</b> <?php echo $y->blood_group; ?></p>
		<p><b>Place of Birth:</b> <?php echo $y->place_of_birth; ?></p>
	</div>


	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-globe f-s-30"></i> Place Information</h3>
		<p><b>Nationality:</b> <?php echo $y->nationality; ?></p>

		<?php if (s_country == 'Nigeria') { ?>
			<p><b>State of Origin:</b> <?php echo $y->state_of_origin; ?></p>
			<p><b>LGA of Origin:</b> <?php echo $y->local_gov; ?></p>
		<?php } ?>

		<p><b>Hometown:</b> <?php echo $y->home_town; ?></p>
		<p><b>Home Address:</b> <?php echo $y->home_address; ?></p>
	</div>


	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-institution f-s-30"></i> Previous School Information</h3>
		<p><b>Name:</b> <?php echo $y->present_school; ?></p>
		<p><b>Address:</b> <?php echo $y->present_school_address; ?></p>
		<p><b>Last Class:</b> <?php echo $y->present_class; ?></p>
	</div>
	
	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-user f-s-30"></i> First Parent Information</h3>
		<p><b>Name:</b> <?php echo $p->name; ?></p>
		<p><b>Sex:</b> <?php echo $p->sex; ?></p>
		<p><b>Relationship:</b> <?php echo $p->relationship; ?></p>
		<p><b>Phone Number:</b> <?php echo $p->phone; ?></p>
		<p><b>Email Address:</b> <?php echo $p->email; ?></p>
	</div>

	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-user f-s-30"></i> Second Parent Information</h3>
		<p><b>Name:</b> <?php echo $p->sec_parent_name; ?></p>
		<p><b>Sex:</b> <?php echo $p->sec_parent_sex; ?></p>
		<p><b>Relationship:</b> <?php echo $p->sec_parent_relationship; ?></p>
		<p><b>Phone Number:</b> <?php echo $p->sec_parent_phone; ?></p>
		<p><b>Email Address:</b> <?php echo $p->sec_parent_email; ?></p>
	</div>

	<div class="col-md-4">
		<h3 class="text-bold m-t-20"><i class="fa fa-lightbulb-o f-s-30"></i> Other Information</h3>
		<p><b>Religion:</b> <?php echo $y->religion; ?></p>
		<p><b>Suspended:</b> <?php echo ($y->suspended == 'true') ? 'Yes' : 'No'; ?></p>
		<p><b>Revoked:</b> <?php echo ($y->revoked == 'true') ? 'Yes' : 'No'; ?></p>
		<p><b>Graduated:</b> <?php echo ($y->graduated == 'true') ? 'Yes' : 'No'; ?></p>
		<p><b>Password Reset Code:</b> <?php echo $y->pass_reset_code; ?> <small>(Use this to reset your password should you forget it. Copy and keep in a safe place)</small></p>
	</div>
	

</div>