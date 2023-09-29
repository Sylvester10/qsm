	
	<div class="report_extra">

		<div class="split_row">

			<b>Class/Form Teacher</b>

			<div class="">
			
				<?php 
				if ($mt_class_teacher_comment == 'true') { ?>
				
					<div class="m-r-30">
						Comment: <span class="report_data"><span><em><?php echo $class_teacher_comment; ?></em></span></span>
					</div>
					
				<?php } ?>

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

		</div>

		
	</div><!--/.report_extra-->


</div><!--/.report_body-->