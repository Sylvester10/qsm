
<div class="row">
	<div class="col-md-6 table-scroll">
		
		<h3 class="text-bold">Plans and Pricing</h3>
		<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			<thead>
				<tr>
					<th class="w-5"> S/N </th>
					<th class="w-25"> Plan </th>
					<th class="w-40"> Price </th>
				</tr>
			</thead>
			<tbody>
			
				<?php
				$count = 1;
				foreach ($plans as $p) { 
					$plan_id = $p->id;
					$price = $this->common_model->get_plan_price_by_location($plan_id); //naira or dollar ?>
		
					<tr>
						<td><?php echo $count; ?></td>
						<td><?php echo $p->plan; ?></td>
						<td><?php echo $price; ?></td>
					</tr>

					<?php $count++; 
				} ?>
				
			</tbody>
		</table>

	</div>
	
	<div class="col-md-4 col-md-offset-2">
		<h3>Current Plan: <?php echo school_plan; ?></h3>
	</div>
	
</div>




<div class="row">
	<div class="col-md-12 table-scroll">
		
		<h3 class="text-bold">Modules, Features and Plan Support</h3>
		<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			<thead>
				<tr>
					<th class="w-5"> S/N </th>
					<th class="w-25"> Module </th>
					<th class="w-40"> Features </th>
					<th class="w-10"> Lite </th>
					<th class="w-10"> Pro </th>
					<th class="w-10"> Pro Plus </th>	
				</tr>
			</thead>
			<tbody>
			
				<?php
				$count = 1;
				foreach ($modules as $s) { ?>

					<tr>
						<td><?php echo $count; ?></td>
						<td><?php echo $s->module; ?></td>
						<td><?php echo $s->features; ?></td>
						<td><?php echo ($s->plan_id == 1) ? '<i class="fa fa-check text-success"></i>' :  '<i class="fa fa-times text-danger"></i>'; ?></td>
						<td><?php echo ($s->plan_id == 1 || $s->plan_id == 2) ? '<i class="fa fa-check text-success"></i>' :  '<i class="fa fa-times text-danger"></i>'; ?></td>
						<td><?php echo ($s->plan_id == 1 || $s->plan_id == 2 || $s->plan_id == 3) ? '<i class="fa fa-check text-success"></i>' :  '<i class="fa fa-times text-danger"></i>'; ?></td>
					</tr>

					<?php $count++; 
				} ?>
				
			</tbody>
		</table>

	</div>
</div>
