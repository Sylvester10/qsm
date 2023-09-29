
<table class="table table-no-border">

	<tr>

		<td class="<?php echo ($show_student_passport == 'true') ? 'p-l-140' : NULL; ?>">
			<div class="report_header">
				<div class="">
					<img class="report_school_logo" src="<?php echo school_logo; ?>" />
				</div>
				<h3 class="text-bold"><?php echo school_name; ?></h3>
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
		<span class="report_data"><span><?php echo $term; ?> Term, <?php echo $the_session; ?></span></span> Academic Session
	</div>
	<div class="text-bold"><?php echo $section; ?> Section</div>


	<div class="text-bold">
		<span class="m-r-30">
			Name: <span class="report_data"><span><?php echo $student_name; ?></span></span>
		</span>
		<span class="">
			Admission ID: <span class="report_data"><span><?php echo $admission_id; ?></span></span>
		</span>
	</div>


	<?php if ($show_class_average == 'true') { ?>						
		<div class="pull-right text-bold">
			Class Percentage Average: <span class="report_data"><span><?php echo $class_percentage_average; ?></span></span>
		</div>
	<?php } ?>

	<div class="text-bold">
		<span class="m-r-30">
			Class: <span class="report_data"><span><?php echo $class; ?></span></span>
		</span>
		<span class="">
			No. In Class: <span class="report_data"><span><?php echo $class_population; ?></span></span>
		</span>
	</div>


	<div class="pull-right text-bold">
		<span class="m-r-30">
			Percentage Score: <span class="report_data"><span><?php echo $overall_percentage_score; ?></span></span>
		</span>

		<?php 
		if ($ranking == 'true') { ?>
			<span class="">
				Position: <span class="report_data text-primary"><span><?php echo $position; ?></span></span>
			</span>
		<?php } else { ?>
			<span class="">
				Overall Grade: <span class="report_data text-primary"><span><?php echo $overall_grade; ?></span></span>
			</span>
		<?php } ?>

	</div>
	<div class="text-bold">Evaluation: <span class="report_data"><span><?php echo $evaluation; ?></span></span> </div>




