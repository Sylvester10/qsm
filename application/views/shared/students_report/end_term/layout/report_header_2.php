
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
				<div class="sections_list">
					(<?php echo $sections; ?>)
				</div>
				<i class="fa fa-map-marker"></i> <?php echo school_location; ?>. <i class="fa fa-phone"></i> <?php echo telephone_line; ?>
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


	<div class="pull-right text-bold">
		Admission ID: <span class="report_data"><span><?php echo $admission_id; ?></span></span>
	</div>
	<div class="text-bold">Name: <span class="report_data"><span><?php echo $student_name; ?></span></span></div>

	<div class="pull-right text-bold">
		No. In Class: <span class="report_data"><span><?php echo $class_population; ?></span></span>
	</div>
	<div class="text-bold">Class: <span class="report_data"><span><?php echo $class; ?></span></span></div>

	<div class="pull-right text-bold">
		Term: <span class="report_data"><span><?php echo $term; ?></span></span>
	</div>
	<div class="text-bold">Session: <span class="report_data"><span><?php echo $the_session; ?></span></span></div>

	<div class="pull-right text-bold">
		Attendance Remark: <span class="report_data"><span><?php echo $attendance_remark; ?></span></span>
	</div>
	<div class="text-bold">
		<span class="m-r-120">
			No of Times School Held: <span class="report_data"><span><?php echo $att_total; ?></span></span>
		</span>
		<span class="">
			No of Times Present: <span class="report_data"><span><?php echo $att_present; ?></span></span>
		</span>
	</div>


</div>