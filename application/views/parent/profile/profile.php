	
<div class="row">

	<div class="col-md-8 col-sm-12 col-xs-12 p-b-15">

		<h3 class="text-bold">First Parent Information</h3>
		<p><b>Name:</b> <?php echo $p->name; ?></p>
		<p><b>Sex:</b> <?php echo $p->sex; ?></p>
		<p><b>Relationship to <?php echo $child_inflect; ?>:</b> <?php echo $p->relationship; ?></p>
		<p><b>Phone Number:</b> <?php echo $p->phone; ?></p>
		<p><b>Email Address:</b> <?php echo $p->email; ?></p>

		<h3 class="text-bold m-t-30">Second Parent Information</h3>
		<p><b>Name:</b> <?php echo $p->sec_parent_name; ?></p>
		<p><b>Sex:</b> <?php echo $p->sec_parent_sex; ?></p>
		<p><b>Relationship to <?php echo $child_inflect; ?>:</b> <?php echo $p->sec_parent_relationship; ?></p>
		<p><b>Phone Number:</b> <?php echo $p->sec_parent_phone; ?></p>
		<p><b>Email Address:</b> <?php echo $p->sec_parent_email; ?></p>

	</div>


	<div class="col-md-4 col-sm-12 col-xs-12">

		<h3 class="text-bold">Children (<?php echo count($children); ?>)</h3>
		
		<?php 
		foreach ($children as $c) { 

			$child_name = $this->common_model->get_student_fullname($c->id); 
			$child_passport = $this->common_model->student_passport_alt($c->id); ?>

			<p class="m-t-20"><?php echo $child_passport; ?> <a href="<?php echo base_url('school_parent/child_profile/'.$c->id); ?>" title="View <?php echo $c->first_name; ?>'s profile"><?php echo $child_name; ?></a></p>

		<?php } ?>

	</div>

</div>