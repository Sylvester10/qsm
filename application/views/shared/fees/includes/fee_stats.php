	

	<div class="new-item">
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/manage_fees'); ?>" target="_blank"><i class="fa fa-cog"></i> Manage Fees</a>
		<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/fee_discount_categories'); ?>" target="_blank"><i class="fa fa-gift"></i> Manage Discounts</a>
	</div>



	<div class="row m-t-10 m-b-10">
		<div class="col-md-12">
			<h4 class="text-bold">General Information</h4>
			<p>Amount Payable: <?php echo $amount_payable; ?></p>
			<?php 
			//show fees due date if selected term is current term
			if ($term == current_term) { ?>
				<p>Fee Due Date: <?php echo $fees_due_date; ?></p>
			<?php } ?>		
			<p>Session: <?php echo $the_session; ?></p>
			<p>Term: <?php echo $term; ?></p>
		</div>
	</div>

	<?php 
	//show fees overdue warning message if selected term is current term
	if ($term == current_term) { 
		if ($fees_overdue) { ?>
			<div class="alert alert-danger alert-dismissable text-center">
				<a href="#" class="close" data-dismiss="alert" aria-label="Close" title="Close"> &times; </a>
				Fee payment for current term is overdue.
			</div>
		<?php } 
	} ?>


	<hr />

	<div class="row m-t-10 m-b-10">

		<div class="col-md-5">
			<h4 class="text-bold">This Class: <?php echo $class; ?></h4>
			<p>Class Population: <?php echo $class_population; ?></p>
			<p>Full Payment: <?php echo $total_class_full_payment; ?> (<?php echo $total_class_validated_full_payment; ?> validated)</p>
			<p>Partial Payment: <?php echo $total_class_partial_payment; ?> (<?php echo $total_class_validated_partial_payment; ?> validated)</p>
			<p>No Payment: <?php echo $total_class_no_payment; ?></p>
			<p>Total Amount Collected: <?php echo $total_class_amount; ?></p>
		</div>

		<div class="col-md-5 col-md-offset-2">
			<h4 class="text-bold">All Classes</h4>
			<p>School Population: <?php echo $school_population; ?></p>
			<p>Full Payment: <?php echo $total_full_payment; ?> (<?php echo $total_validated_full_payment; ?> validated)</p>
			<p>Partial Payment: <?php echo $total_partial_payment; ?> (<?php echo $total_validated_partial_payment; ?> validated)</p>
			<p>No Payment: <?php echo $total_no_payment; ?></p>
			<p>Total Amount Collected: <?php echo $total_amount; ?></p>
		</div>

	</div>