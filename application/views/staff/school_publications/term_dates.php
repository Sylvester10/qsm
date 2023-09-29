
	<?php 
	if ($total_records == 0) { ?>
	
		<h3 class="text-danger">No Term Dates found!</h3>
		
	<?php } else { ?>
		
		
		<div class="row">
			
			<div class="col-md-12 table-scroll">

				<table class="table table-bordered cell-text-middle" style="text-align: left">
					<thead>
						<tr>
							<th class="w-5"> S/N </th>
							<th class="w-45"> Activity </th>
							<th class="w-50"> Date </th>
						</tr>
					</thead>
					<tbody>

						<?php 
						$count = 1;
						foreach ($term_dates as $y) { ?>

							<tr>
								<td><?php echo $count; ?></td>
								<td><?php echo $y->activity; ?></td>
								<td><?php echo $this->publications_model->check_term_date($y->id); ?></td>
							</tr>

							<?php $count++;

						} ?>

					</tbody>
				</table>
				
			</div><!--/.table-scroll-->	
		
		</div>
		
	<?php } ?>

