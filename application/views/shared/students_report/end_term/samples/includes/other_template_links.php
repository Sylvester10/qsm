<div class="text-center">
	<h3>Other Templates</h3>

	<?php 
	foreach ($report_templates as $t) { 

		$r_template_id = $t->template_id; 
		$active = $t->active;
		$template_url = 'report_templates/template_'.$r_template_id; 

		if ($active == 'true') { 

			if ($r_template_id != $template_id) { ?>

				<a class="btn btn-default btn-sm" href="<?php echo base_url($template_url); ?>" title="View sample of this template"><i class="fa fa-eye"></i> Template <?php echo $r_template_id; ?></a>

			<?php } else { ?>

				<a class="btn btn-default btn-sm" title="Current template" disabled><i class="fa fa-eye"></i> Template <?php echo $r_template_id; ?></a>

			<?php } ?>

		<?php } ?>

	<?php } ?>

</div>