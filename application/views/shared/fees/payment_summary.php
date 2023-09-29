
<?php echo flash_message_success('status_msg'); ?>
<?php echo flash_message_danger('status_msg_error'); ?>
<?php echo custom_validation_errors(); ?>

	<?php 
	if ($term == current_term) { ?>

		<div class="new-item">
			<a class="btn btn-default btn-sm button-adjust" href="<?php echo base_url($this->c_controller.'/manage_fees'); ?>" target="_blank"><i class="fa fa-cog"></i> Manage Fees</a>
		</div>

	<?php } ?>


	<div class="row m-t-10 m-b-10">
		<div class="col-md-12">
			<h4 class="text-bold">All Classes</h4>
			<p>School Population: <?php echo $school_population; ?></p>
			<p>Full Payment: <?php echo $total_full_payment; ?></p>
			<p>Partial Payment: <?php echo $total_partial_payment; ?></p>
			<p>No Payment: <?php echo $total_no_payment; ?></p>
			<p>Total Amount Collected: <?php echo $total_amount; ?></p>
		</div>
	</div>


	<?php if (count($classes) == 0) { ?>

		<h3 class="text-danger">No class found!</h3>

	<?php } else { ?>

		<div class="row table-scroll">
			<div class="col-md-12">
			  	<div class="panel-group" id="accordion">

			  		<?php
			  		$id = 1; //generate unique ids for the accordion tabs
					foreach ($sections as $s) { ?>
				    	<div class="panel panel-default">
				      		<div class="panel-heading">
				        		<h4 class="panel-title">
				          			<a data-toggle="collapse" data-parent="#accordion" href="#section<?php echo $id; ?>"><?php echo $s->section; ?> Section (<?php echo count($this->common_model->get_classes_by_section(school_id, $s->id)); ?>)</a>
				        		</h4>
				      		</div>
				      		<?php $default_tab = ($id == 1) ? 'in' : null; //if first tab, make default using the class ".in" ?>
				      		<div id="section<?php echo $id; ?>" class="panel-collapse collapse <?php echo $default_tab; ?>">
				        		<div class="panel-body">

									<div class="table-scroll">
										<table class="table table-bordered table-hover cell-text-middle" style="text-align: left">
											<thead>
												<tr>
													<th> Actions </th>
													<th class="min-w-100"> Class </th>
													<th class="min-w-50"> Full Payment </th>
													<th class="min-w-50"> Partial Payment </th>
													<th class="min-w-50"> No Payment </th>
													<th class="min-w-100"> Class Population </th>
													<th class="min-w-150"> Students on Discount </th>
													<th class="min-w-150"> Amount Payable </th>
													<th class="min-w-150"> Total Discounted Amount Expected </th>
													<th class="min-w-150"> Total Undiscounted Amount Expected </th>
													<th class="min-w-150"> Grand Total Amount Expected </th>
													<th class="min-w-150"> Total Amount Collected </th>
												</tr>
											</thead>
											<tbody>

												<?php
												$section_classes = $this->common_model->get_classes_by_section(school_id, $s->id);

												foreach ($section_classes as $y) { 

													$class_id = $y->id;
													$class_population = $this->common_model->get_class_population($class_id);
													$total_students_on_discount = count($this->fees_model->get_students_on_fee_discount_in_class($class_id));
													$full_payment = count($this->fees_model->get_class_full_payment($session, $term, $class_id));
													$partial_payment = count($this->fees_model->get_class_partial_payment($session, $term, $class_id));
													$no_payment = $this->fees_model->get_class_no_payment($session, $term, $class_id);
													$amount_payable = $this->fees_model->get_amount_payable_by_class($session, $term, $class_id);
													$total_discounted_amount_expected = $this->fees_model->get_class_total_discounted_amount_expected($session, $term, $class_id); 
													$total_undiscounted_amount_expected = $this->fees_model->get_class_total_undiscounted_amount_expected($session, $term, $class_id); 
													$total_amount_expected = $this->fees_model->get_class_total_amount_expected($session, $term, $class_id); 
													$total_amount_collected = $this->fees_model->get_class_total_amount_paid($session, $term, $class_id);

													require "application/views/shared/fees/modals/payment_summary_actions.php"; ?>

													<tr>	
														<td class="w-15-p text-center"><button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#details<?php echo $y->id; ?>"><i class="fa fa-navicon" title="View details"></i></button></td>
														<td><?php echo $y->class; ?></td>
														<td><?php echo $full_payment; ?></td>
														<td><?php echo $partial_payment; ?></td>
														<td><?php echo $no_payment; ?></td>
														<td><?php echo $class_population; ?></td>
														<td>
															<?php
															if ($total_students_on_discount > 0) { ?>
																<div class="pull-right">
							                                        <a href="<?php echo base_url($this->c_controller.'/fee_discount_categories'); ?>">View</a>
							                                    </div>
							                                <?php } ?>
															<?php echo $total_students_on_discount; ?>
														</td>
														<td><?php echo $amount_payable; ?></td>
														<td><?php echo $total_discounted_amount_expected; ?></td>
														<td><?php echo $total_undiscounted_amount_expected; ?></td>
														<td><?php echo $total_amount_expected; ?></td>
														<td><?php echo $total_amount_collected; ?></td>
													</tr>

												<?php } ?>

											</tbody>
										</table>
									</div>

		  						</div>
				      		</div>
				    	</div><!--/.panel-group-->
						
						<?php $id++; ?>
				    <?php } //endforeach ?>

			  	</div><!--/#accordion-->

	  		</div><!-- /.col-md-12 -->
		</div><!-- /.row -->

	<?php } //endif classes == 0 ?>

