
<div class="row">
	<div class="col-md-12 table-scroll">
		
		<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			<thead>
				<tr>
					<th class="w-5"> S/N </th>
					<th class="w-20"> Period </th>
					<th class="w-15"> Admin </th>
					<th class="w-15"> Staff </th>
					<th class="w-15"> Students </th>
					<th class="w-15"> Parents </th>
					<th class="w-15"> Super Admins </th>
				</tr>
			</thead>
			<tbody>

				<?php
				$periods = array(
					//period =>		period type
					'2M' 		=>		'minute',	//online	
					'1H' 		=>		'hour',		
					'6H' 		=>		'hour',		
					'1D' 		=>		'day',		
					'3D'		=>		'day',		
					'7D'		=>		'day',		
					'1M'		=>		'month',		
					'1Y'		=>		'year',		
				);

				$count = 1;
				foreach ($periods as $period => $period_type) { 

					//remove letter character from period
					$period = $period[0];

					if ($period == '2' && $period_type == 'minute') {
						$the_period = '<span class="text-success text-bold">Online<sup><i class="fa fa-dot-circle-o fa-pulse"></i></sup></span>';
					} else {
						$affix = ($period == 1) ? '' : 's'; 
						$the_period = 'Last ' . $period . ' ' . ucfirst($period_type).$affix;
					} ?>

					<tr>
						<td><?php echo $count; ?></td>
						<td><?php echo $the_period; ?></td>
						<td><?php echo $this->common_model->get_last_login_stats($period, $period_type, 'admins'); ?></td>
						<td><?php echo $this->common_model->get_last_login_stats($period, $period_type, 'staff'); ?></td>
						<td><?php echo $this->common_model->get_last_login_stats($period, $period_type, 'students'); ?></td>
						<td><?php echo $this->common_model->get_last_login_stats($period, $period_type, 'parents'); ?></td>
						<td><?php echo $this->common_model->get_last_login_stats($period, $period_type, 'super_admins'); ?></td>
					</tr>

					<?php $count++; 
				
				} ?>

			</tbody>
		</table>

	</div>
</div>
