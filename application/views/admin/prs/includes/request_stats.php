<div class="row m-t-10 m-b-10">

	<div class="col-md-4">
		<h4 class="text-bold">This Term: <?php echo $term; ?> Term</h4>
		<?php if ($pending_term_requests > 0) { ?>
			<p><i class="fa fa-spinner fa-spin text-primary"></i> Pending: <?php echo number_format($pending_term_requests); ?></p>
		<?php } else { ?>
			<p><i class="fa fa-spinner text-primary"></i> Pending: <?php echo number_format($pending_term_requests); ?></p>
		<?php } ?>

		<p><i class="fa fa-check-square-o text-success"></i> Approved: <?php echo number_format($approved_term_requests); ?></p>
		<p><i class="fa fa-ban text-danger"></i> Declined: <?php echo number_format($declined_term_requests); ?></p>
		<p><i class="fa fa-th-large"></i> All: <?php echo number_format($all_term_requests); ?></p>
		<p><i class="fa fa-money"></i> Total Amount Raised: <?php echo s_currency_symbol . number_format($total_term_amount_raised); ?></p>
		<p><i class="fa fa-money"></i> Total Amount Approved: <?php echo s_currency_symbol . number_format($total_term_amount_approved); ?></p>
	</div>


	<div class="col-md-4">
		<h4 class="text-bold">This Session: <?php echo $the_session; ?> Session</h4>
		<?php if ($pending_session_requests > 0) { ?>
			<p><i class="fa fa-spinner fa-spin text-primary"></i> Pending: <?php echo number_format($pending_session_requests); ?></p>
		<?php } else { ?>
			<p><i class="fa fa-spinner text-primary"></i> Pending: <?php echo number_format($pending_session_requests); ?></p>
		<?php } ?>

		<p><i class="fa fa-check-square-o text-success"></i> Approved: <?php echo number_format($approved_session_requests); ?></p>
		<p><i class="fa fa-ban text-danger"></i> Declined: <?php echo number_format($declined_session_requests); ?></p>
		<p><i class="fa fa-th-large"></i> All: <?php echo number_format($all_session_requests); ?></p>
		<p><i class="fa fa-money"></i> Total Amount Raised: <?php echo s_currency_symbol . number_format($total_session_amount_raised); ?></p>
		<p><i class="fa fa-money"></i> Total Amount Approved: <?php echo s_currency_symbol . number_format($total_session_amount_approved); ?></p>
	</div>


	<div class="col-md-4">
		<h4 class="text-bold">All Time: Since <?php echo year_installed; ?></h4>
		<?php if ($pending_requests > 0) { ?>
			<p><i class="fa fa-spinner fa-spin text-primary"></i> Pending: <?php echo number_format($pending_requests); ?></p>
		<?php } else { ?>
			<p><i class="fa fa-spinner text-primary"></i> Pending: <?php echo number_format($pending_requests); ?></p>
		<?php } ?>

		<p><i class="fa fa-check-square-o text-success"></i> Approved: <?php echo number_format($approved_requests); ?></p>
		<p><i class="fa fa-ban text-danger"></i> Declined: <?php echo number_format($declined_requests); ?></p>
		<p><i class="fa fa-th-large"></i> All: <?php echo number_format($all_requests); ?></p>
		<p><i class="fa fa-money"></i> Total Amount Raised: <?php echo s_currency_symbol . number_format($total_amount_raised); ?></p>
		<p><i class="fa fa-money"></i> Total Amount Approved: <?php echo s_currency_symbol . number_format($total_amount_approved); ?></p>
	</div>

</div>