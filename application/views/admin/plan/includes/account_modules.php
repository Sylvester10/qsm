

<h3 class="m-t-30 text-bold">Account Modules</h3>
<p class="text-bold f-s-18">The following modules are covered under your current plan:</p>
<ul class="module_list">
	<?php
	$modules = $this->common_model->get_plan_modules(school_id)->result(); 
	foreach ($modules as $s) {
		echo '<li>' . $s->module . '</li>';
	} ?>
</ul>

<a class="btn btn-primary" href="<?php echo base_url('plan/plans'); ?>">View All Modules</a>