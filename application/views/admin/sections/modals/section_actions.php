
<div class="modal fade" id="options<?php echo $y->id; ?>" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-width">
			<div class="modal-header">
				<div class="pull-right">
					<button class="btn btn-danger btn-sm modal_close_btn" data-dismiss="modal" class="close" title="Close"> &times;</button>
				</div>
				<h4 class="modal-title">Actions: <?php echo $y->section; ?></h4>
			</div><!--/.modal-header-->
			<div class="modal-body">
			
				<p><a type="button" href="<?php echo base_url('sections/edit_section/'.$y->id); ?>" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-pencil" style="color: green"></i> &nbsp; Edit Section </a></p>

				<p><a type="button" href="<?php echo base_url('sections/edit_section/'.$y->id); ?>#mt_report_card_settings" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-wrench" style="color: green"></i> &nbsp; Customize Mid-Term Report Card </a></p>

				<p><a type="button" href="<?php echo base_url('sections/edit_section/'.$y->id); ?>#report_card_settings" class="btn btn-default btn-sm btn-block action-btn"> <i class="fa fa-wrench" style="color: green"></i> &nbsp; Customize End of Term Report Card </a></p>

				<p><a type="button" href="#" class="btn btn-default btn-sm btn-block action-btn" data-toggle="modal" data-target="#delete<?php echo $y->id; ?>"> <i class="fa fa-trash" style="color: red"></i> &nbsp; Delete Section </a></p>
				
			</div>
		</div>
	</div>
</div>