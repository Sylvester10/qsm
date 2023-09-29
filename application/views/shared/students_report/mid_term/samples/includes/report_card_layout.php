
<div class="view_report_card">


	<div class="report_template">

		<?php 
		require 'application/views/shared/students_report/mid_term/samples/'.$template_folder.'/data.php'; ?>
		
		<div class="m-t-30">
			<a class="btn btn-lg btn-primary" href="<?php echo base_url($print_url); ?>" target="_blank">
				<i class="fa fa-print"></i> Print
			</a>
		</div>

		<?php 
		//require other template links
		require "application/views/shared/students_report/mid_term/samples/includes/other_template_links.php"; ?>
	
	</div><!--/.report_template-->

	
</div><!--/.view_report_card-->

<div class="not_desktop"><!--Show on mobile and tablets-->
	<h3 class="text-danger">For best presentation, please view this page on a computer.</h3>
</div>