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
									<li class="m-r-30"><a href="<?php echo base_url('buy/3'); ?>">Buy</a></li>
									<li><a href="<?php echo base_url('login'); ?>">Login</a></li>
								</ul>
								
								<h2><?php echo software_name; ?> &raquo; Install</h2>
								<img class="install-banner" src="<?php echo base_url('assets/images/qsm-banner.png'); ?>" />
							</div>

							<div class="m-t-20">
								Fill and submit this form to install <?php echo software_name; ?> for your school.
							</div>
							
							
							<?php 
							$form_attributes = array("id" => "free_trial_install_form"); 
							echo form_open('installation/free_trial_install_ajax', $form_attributes); ?>

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
									<label>Mode <small><span class="text-success">(Try free for 30 days)</span></small></label>
									<select class="form-control" name="mode" readonly>
										<option selected value="Free Trial">Free Trial</option>
									</select>
									<div style="color: red; margin-top: 10px;"><?php echo form_error('mode'); ?></div>
								</div>

								<div class="form-group m-t-20">
									<label>School Name</label>
									<input type="text" class="form-control capitalize-words" name="school_name" id="school_name" value="<?php echo set_value('school_name'); ?>" required />
									<div style="color: red; margin-top: 10px;"><?php echo form_error('school_name'); ?></div>
								</div>
								
								<div class="form-group m-t-20">
									<label>School Address</label>
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
									<label>School Website <small>(if any)</small></label>
									<input type="text" class="form-control" name="school_website" placeholder="http://example.com" value="<?php echo set_value('school_website'); ?>" />
									<div style="color: red; margin-top: 10px;"><?php echo form_error('school_website'); ?></div>
								</div>

								<div class="form-group m-t-20">
									<label>School Motto</small></label>
									<input type="text" class="form-control" name="school_motto" placeholder="Knowledge is Power" value="<?php echo set_value('school_motto'); ?>" />
									<div style="color: red; margin-top: 10px;"><?php echo form_error('school_motto'); ?></div>
								</div>
								
								<div class="form-group m-t-20">
									<label>Your Name <small>(as administrator)</small></label>
									<input type="text" class="form-control capitalize-words" name="admin_name" value="<?php echo set_value('admin_name'); ?>" required />
									<div style="color: red; margin-top: 10px;"><?php echo form_error('admin_name'); ?></div>
								</div>
								
								<div class="form-group m-t-20">
									<label>Your Email <small>(personal)</small></label>
									<input type="text" class="form-control" name="admin_email" id="admin_email" value="<?php echo set_value('admin_email'); ?>"  required />
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
								
								<div id="processing_msg"></div>
								<div id="status_msg"></div>
								
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-lg">Install</button>
								</div>

							<?php echo form_close(); ?>
			
						</div>
						
					</div>	
				</div>
			</div>
		</div>
		<!--/Page content-->
			