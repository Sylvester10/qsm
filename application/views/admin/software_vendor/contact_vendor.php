

<div class="row">
	<div class="col-md-8">
			
		<?php echo software_name; ?> is a product of <a href="<?php echo software_vendor_site; ?>" target="_blank"><?php echo software_vendor; ?></a>.

		<br /><br />

		<p><i class="fa fa-envelope"></i>: <?php echo software_info_mail; ?></p>


		<h3 class="m-t-30">Drop a Message</h3>

		<?php 
		$form_attributes = array("id" => "contact_vendor_form");
		echo form_open('admin/contact_vendor_ajax', $form_attributes); ?>
			
			<div class="form-group">
				<textarea name="message" class="form-control t200" placeholder="Type your message here..." required><?php echo set_value('message'); ?></textarea>
				<div class="form-error"><?php echo form_error('message'); ?></div>
			</div>
			
			<div id="status_msg"></div>
			
			<div class="form-group">
				<button class="btn btn-primary btn-lg">Send</button>
			</div>
			
		<?php echo form_close(); ?>
		
	</div>
</div>
