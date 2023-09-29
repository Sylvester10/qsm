
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

<?php require "application/views/super_admin/coupons/modals/new_free_trial_coupon.php";  ?>

	<div class="new-item">
		<button class="btn btn-default btn-sm button-adjust" data-toggle="modal" data-target="#new_coupon"><i class="fa fa-plus"></i> New Coupon</button>
	</div>


	<div class="table-scroll">
		<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
			<thead>
				<tr>
					<th> Actions </th>
					<th class="min-w-200"> Name </th>
					<th class="min-w-100"> Code </th>
					<th> Discount </th>
					<th class="min-w-150"> Price Naira </th>
					<th class="min-w-150"> Price Dollar </th>
					<th class="min-w-150"> Valid Until </th>
					<th> Status </th>
					<th> Schools Using </th>
				</tr>
			</thead>
			<tbody>

				<?php
				foreach ($coupons as $y) { 

					$coupon_id = $y->id;
					$discount = $y->discount;

					//get number of schools using coupon
					$coupon_users = $this->coupon_model->get_free_trial_coupon_users($coupon_id);

					//get current date
					$now = date('Y-m-d H:i:s');

					if ($y->valid_until >= $now) {
						$status = '<span class="text-success">Active</span>';
					} else {
						$status = '<span class="text-danger">Expired</span>';
					}
					
					require "application/views/super_admin/coupons/modals/edit_free_trial_coupon.php";
					require "application/views/super_admin/coupons/modals/free_trial_coupon_users.php";
					//delete confirm modal
					echo modal_delete_confirm($y->id, $y->name, 'coupon', 'coupon/delete_free_trial_coupon');  ?>
					
					<tr>	
						<td class="text-center">
							<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#edit<?php echo $coupon_id; ?>"><i class="fa fa-edit"></i></button>
							<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete<?php echo $coupon_id; ?>"><i class="fa fa-trash"></i></button>
						</td>
						<td><?php echo $y->name; ?></td>
						<td><?php echo $y->code; ?></td>
						<td><?php echo $y->discount; ?>%</td>

						<td>
							<?php
							foreach ($plans as $p) {
								$discounted_price_naira = $this->coupon_model->get_discounted_price_naira($p->id, $discount);
								echo $p->plan . ': ' . $discounted_price_naira . '<br />';
							} ?>
						</td>

						<td>
							<?php
							foreach ($plans as $p) {
								$discounted_price_dollar = $this->coupon_model->get_discounted_price_dollar($p->id, $discount);
								echo $p->plan . ': ' . $discounted_price_dollar . '<br />';
							} ?>
						</td>

						<td><?php echo x_date_full($y->valid_until); ?></td>
						<td><?php echo $status; ?></td>
						<td>
							
							<?php if (count($coupon_users) > 0) { ?>

								<div class="pull-right">
									<a class="btn btn-default btn-sm" data-toggle="modal" data-target="#coupon_user<?php echo $coupon_id; ?>" title="View schools using this voucher"><i class="fa fa-eye"></i></a>
								</div>

							<?php } ?>

							<?php echo count($coupon_users); ?>

						</td>
					</tr>

				<?php } ?>

			</tbody>
		</table>
	</div>
							
