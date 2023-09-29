	
	<div class="report_extra">

		<div class="split_row">

			<b>Class/Form Teacher</b>

			<div class="">
				<div class="m-r-30">
					Comment: <span class="report_data"><span><em><?php echo $class_teacher_comment; ?></em></span></span>
				</div>

				<?php 
				if ($show_class_teacher_name == 'true') { ?>

					<span class="m-r-30">
						Name: <span class="report_data"><span><?php echo $class_teacher_name; ?></span></span>
					</span>

				<?php } ?>

				Signature: <img class="report_signature" src="<?php echo $class_teacher_signature; ?>" /> 
			</div>

		</div>

	
		<div class="split_row">

			<b><?php echo $head_teacher_designation; ?></b>

			<div class="pull-right">
				<img class="report_stamp" src="<?php echo $report_stamp; ?>" /> 
			</div>

			<div class="">
				Comment: 
				<?php 
				//check if result has been approved
				if ($date_approved != NULL) { ?> 
					<span class="report_data"><span><em><?php echo $head_teacher_comment; ?></em></span></span>
				<?php } else { ?>
					<span class="text-normal">____________________________________</span>
				<?php } ?>
			</div>


			<div class="">

				<?php 
				if ($show_head_teacher_name == 'true') { ?>

					<span class="m-r-30">
						Name: <span class="report_data"><span><?php echo $head_teacher_name; ?></span></span>
					</span>

				<?php } ?>

				Signature: <img class="report_signature" src="<?php echo $head_teacher_signature; ?>" />

				<span class="m-l-30">Date:</span>
				<?php 
				//check if result has been approved
				if ($date_approved != NULL) { ?> 
					<span class="report_data"><span><?php echo $date_approved; ?></span></span>
				<?php } else { ?>
					________________
				<?php } ?>

			</div>


			<?php
			//compare current clas and resumption class to know whether student is to be promoted or not 
			if ($class_id != $resumption_class_id) { ?>

				<div class="">
					Promoted to: <span class="report_data text-primary text-bold"><span><?php echo $resumption_class; ?></span></span>
				</div>

			<?php } ?>

		</div>


		<div class="p-5">

			<?php 
			$next_term_start_date = x_date($term_info->next_term_start_date);
			$next_term_fees_due_date = x_date($term_info->next_term_fees_due_date); ?>

			<span class="m-r-30">
				Next Term Starts: <span class="report_data"><span><?php echo $next_term_start_date; ?></span></span>
			</span>
			<span class="m-r-30">
				Next Term Fees: <span class="report_data"><span><?php echo $next_term_fees; ?></span></span>
			</span>
			<span class="">
				Next Term Fees Due Date: <span class="report_data"><span><?php echo $next_term_fees_due_date; ?></span></span>
			</span>

		</div>

		
	</div><!--/.report_extra-->


</div><!--/.report_body-->