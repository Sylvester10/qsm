

<div class="row">
	<div class="col-md-12 table-scroll">
		
		<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			<thead>
				<tr>
					<th class="w-5"> S/N </th>
					<th class="w-25"> Modules </th>
					<th class="w-10"> Lite </th>
					<th class="w-10"> Pro </th>
					<th class="w-10"> Pro Plus </th>
					<th class="w-40"> Features </th>
				</tr>
			</thead>
			<tbody>
				<?php
				$count = 1;
				foreach ($modules as $s) { ?>

					<tr>
						<td><?php echo $count; ?></td>
						<td><?php echo $s->module; ?></td>
						<td><?php echo ($s->plan_id == 1) ? '<i class="fa fa-check text-success"></i>' :  '<i class="fa fa-times text-danger"></i>'; ?></td>
						<td><?php echo ($s->plan_id == 1 || $s->plan_id == 2) ? '<i class="fa fa-check text-success"></i>' :  '<i class="fa fa-times text-danger"></i>'; ?></td>
						<td><?php echo ($s->plan_id == 1 || $s->plan_id == 2 || $s->plan_id == 3) ? '<i class="fa fa-check text-success"></i>' :  '<i class="fa fa-times text-danger"></i>'; ?></td>
						<td><?php echo $s->features; ?></td>
					</tr>

					<?php $count++; 
				} ?>
			</tbody>
		</table>

	</div>
</div>
