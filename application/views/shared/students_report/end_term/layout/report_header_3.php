<table class="table table-no-border">

	<tr>

		<td>
			<div class="">
				<img class="report_school_logo" src="<?php echo school_logo; ?>" />
			</div>
		</td>

		<td>
			<div class="report_header">
				<h3 class="text-bold"><?php echo strtoupper(school_name); ?></h3>
				<div class="text-bold">
					<i class="fa fa-map-marker"></i> <?php echo strtoupper(school_location); ?>. <i class="fa fa-phone"></i> <?php echo telephone_line; ?>
				</div>
				<div class="text-bold">
					Motto: <em><?php echo $school_info->school_motto; ?></em>
				</div>
				<div class="text-bold m-t-10">
					END-OF-TERM REPORT
				</div>
			</div><!--/.report_header-->
		</td>

		<td>
			<?php if ($show_student_passport == 'true') { ?>
				<div class="pull-right">
					<img class="report_passport_square" src="<?php echo $student_passport; ?>" />
				</div>
			<?php } ?>
		</td>

	</tr>

</table>


<div class="report_body">

	<table class="table table-no-border">
		<tbody>

			<tr>
				<td>
					<div class="report_rec_box">
						Name: <?php echo $student_name; ?>
					</div>
				</td>
				<td>
					<div class="report_rec_box">
						Sex: <?php echo $student_sex; ?>
					</div>
				</td>
				<td>
					<div class="report_rec_box">
						Admission No: <?php echo $admission_id; ?>
					</div>
				</td>
				<td>
					<div class="report_rec_box">
						Class: <?php echo $class; ?>
					</div>
				</td>
			</tr>

		
			<tr>
				<td>
					<div class="report_rec_box">
						Session: <?php echo $the_session; ?>
					</div>
				</td>
				<td>
					<div class="report_rec_box">
						Term: <?php echo $term; ?>
					</div>
				</td>
				<td>
					<div class="report_rec_box">
						No of Times School Held: <?php echo $att_total; ?>
					</div>
				</td>
				<td>
					<div class="report_rec_box">
						No of Times Present: <?php echo $att_present; ?>
					</div>
				</td>
			</tr>


			<tr>
				<td>
					<div class="report_rec_box">
						Closing Date: <?php echo x_date($term_info->term_closing_date); ?>
					</div>
				</td>
				<td>
					<div class="report_rec_box">
						Re-opening Date: <?php echo x_date($term_info->next_term_start_date); ?>
					</div>
				</td>
			</tr>

		</tbody>
	</table>

</div>