<!--Page content-->

	<div class="container d-flex align-items-center">
		<div class="form-holder">
			<div class="row">
				<!-- Form Panel    -->
				<div class="col-lg-6 offset-md-3 p-30 b-r-5 bg-white">
					
					<div class="content">
						<div class="text-center p-15">
						
							<ul class="home_links">
								<li class="m-r-30"><a href="<?php echo base_url(); ?>">Home</a></li>
								<li class="m-r-30"><a href="<?php echo base_url('install/3'); ?>">Try Free</a></li>
								<li><a href="<?php echo base_url('login'); ?>">Login</a></li>
							</ul>
							
							<h2><?php echo software_name; ?> &raquo; Buy</h2>
							<img class="install-banner" src="<?php echo base_url('assets/images/qsm-banner.png'); ?>" />
						</div>

						<div class="m-t-20">
							When you click the <b>Buy</b> button below this form, you will be prompted to enter your debit/credit card details in order to complete the installation of <?php echo software_name; ?> for your school. Debit/credit card payment attracts instant activation upon confirmation of payment.

							<h4 class="text-bold f-s-17 m-t-20">For purchase using other modes of payment, click <a href="<?php echo base_url('buy_other'); ?>" target="_blank">here</a></h4>
						</div>
						
						<?php 
						$form_attributes = array("id" => "buy_application_with_rave_form"); 
						echo form_open('buy_rave/buy_application_ajax', $form_attributes); ?>
						
							<script src="<?php echo $script_url; ?>"></script>
							
							<input type="hidden" id="csrf_hash" value="<?php echo $this->security->get_csrf_hash(); ?>" />

							<div class="form-group m-t-20">
								<label>Plan</label>
								<select class="form-control" name="plan_id" id="plan_id" required>
								
									<?php 
									foreach ($plans as $p) {
										//check which plan was pre-selected and assign 'selected' attribute to it
										$selected = ($p->id == $plan_id) ? 'selected' : NULL;
										$p_price = $this->common_model->get_plan_price_by_location($p->id); ?>
										<option <?php echo $selected; ?> value="<?php echo $p->id; ?>"><?php echo $p->plan; ?> (<?php echo $p_price; ?>)</option>
									<?php } ?>
									
								</select>
								<div style="color: red; margin-top: 10px;"><?php echo form_error('plan_id'); ?></div>
							</div>

							<div class="form-group m-t-20">
								<label>Mode <small><span class="text-success">(Purchase now to enjoy unlimited features)</span></small></label>
								<select class="form-control" name="mode" readonly>
									<option selected value="Paid">Paid</option>
								</select>
								<div style="color: red; margin-top: 10px;"><?php echo form_error('mode'); ?></div>
							</div>

							<div class="form-group m-t-20">
								<label>School Name</label>
								<input type="text" class="form-control capitalize-words" name="school_name" id="school_name" value="<?php echo set_value('school_name'); ?>" required />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('school_name'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>School Location</label>
								<input type="text" class="form-control" name="school_location" value="<?php echo set_value('school_location'); ?>" required />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('school_location'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>Country</label>
								<select class="form-control" name="country" required>								
									<option selected value="<?php echo visitor_country; ?>"><?php echo visitor_country; ?></option>
														
									<?php 
									$countries = countries();
									foreach ($countries as $index => $country) { ?>
										<option value="<?php echo $country; ?>" <?php echo set_select('country', $country); ?> ><?php echo $country; ?></option>
									<?php } ?> 
									
								</select>
								<div style="color: red; margin-top: 10px;"><?php echo form_error('country'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>Currency</label>
								<select class="form-control" name="currency" required>
								
									<?php
									if (visitor_country == 'Nigeria') { 
										$nigerian_currency = 'Nigerian Naira (' . nigerian_currency . ')'; ?>
										<option selected value="<?php echo nigerian_currency_code; ?>"><?php echo $nigerian_currency; ?></option>
									<?php } else { ?>
										<option value="">Select Currency</option>
									<?php } 
									$currencies = currency_codes();
									foreach ($currencies as $name => $code) { 
										$d_currency = $name. ' (' . get_currency_symbol($code). ')'; ?>
										<option value="<?php echo $code; ?>" <?php echo set_select('currency', $code); ?> ><?php echo $d_currency; ?></option>
									<?php } ?>
									
								</select>
								<div style="color: red; margin-top: 10px;"><?php echo form_error('currency'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>Official Email Address <small>(School's)</small></label>
								<input type="email" class="form-control" name="official_mail" value="<?php echo set_value('official_mail'); ?>" required />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('official_mail'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>Official Telephone Line <small>(School's)</small></label>
								<input type="text" class="form-control phone-num" name="telephone_line" value="<?php echo set_value('telephone_line'); ?>" required />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('telephone_line'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>School Website <small>(if any, must have http:// or https:// prefix)</small></label>
								<input type="text" class="form-control" name="school_website" placeholder="http://example.com" value="<?php echo set_value('school_website'); ?>" />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('school_website'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>Your Name <small>(as administrator)</small></label>
								<input type="text" class="form-control capitalize-words" name="admin_name" value="<?php echo set_value('admin_name'); ?>" required />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('admin_name'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>Your Email <small>(personal)</small></label>
								<input type="text" class="form-control" name="admin_email" value="<?php echo set_value('admin_email'); ?>"  required />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('admin_email'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>Your Phone Number <small>(personal)</small></label>
								<input type="text" class="form-control phone-num" name="admin_phone" value="<?php echo set_value('admin_phone'); ?>" required />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('admin_phone'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>Password</label>
								<input type="password" class="form-control" name="password" value="<?php echo set_value('password'); ?>" required />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('password'); ?></div>
							</div>
							
							<div class="form-group m-t-20">
								<label>Confirm Password</label>
								<input type="password" class="form-control" name="c_password" placeholder="Re-enter password" required />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('c_password'); ?></div>
							</div>	

							<div class="form-group m-t-20">
								<label>How did you hear about us?</label>
								<select class="form-control" name="referrer" required>								
									<option value="">Select</option>
														
									<?php 
									$referrers = get_referrers();
									foreach ($referrers as $referrer) { ?>
										<option value="<?php echo $referrer; ?>" <?php echo set_select('referrer', $referrer); ?> ><?php echo $referrer; ?></option>
									<?php } ?> 
									
								</select>
								<div style="color: red; margin-top: 10px;"><?php echo form_error('referrer'); ?></div>
							</div>

							<div class="form-group m-t-20">
								<label>Captcha Code</label>
								<input type="text" class="captcha" name="captcha" value="<?php echo $captcha_code; ?>" readonly />
								<input type="text" class="form-control" name="c_captcha" id="captcha" placeholder="Enter above code here to prove you're human" maxlength="6" required />
								<div style="color: red; margin-top: 10px;"><?php echo form_error('c_captcha'); ?></div>
							</div>	
							
							<div id="status_msg"></div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-lg">Buy</button>
							</div>

							<p>Our payment system supports Visa card, Master card, Verve card, American Express card. We accept payment in US Dollars (USD) and Nigerian Naira (NGN).</p>

							<div class="">
								<img class="payment_logo img-responsive" src="<?php echo base_url('assets/images/debit_card.jpg'); ?>" />
								<img class="payment_logo img-responsive" src="<?php echo base_url('assets/images/express_card.jpg'); ?>" />
							</div>

						<?php echo form_close(); ?>
		
					</div>
					
				</div>	
			</div>
		</div>
	</div>
	
<!--/Page content-->
			