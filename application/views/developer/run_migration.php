
	
		<!--Page content-->
		<div class="container d-flex align-items-center">
			<div class="form-holder has-shadow">
				<div class="row">
					<!-- Logo & Information Panel-->
					<div class="col-lg-6 login-background">
						<div class="info d-flex align-items-center">
							<div class="content">
								<div class="logo">
									<h1><?php echo software_initials; ?></h1>
								</div>
								<p><?php echo software_tagline; ?></p>
							</div>
						</div>
					</div>
					<!-- Form Panel    -->
					<div class="col-lg-6 bg-white switch-user">
					
						<div class="m-l-15 m-r-20 m-b-20">
							<div class="p-l-10 p-r-10">
								<div class="p-l-10 p-r-10 m-t-20">
									
								</div>
							</div>
						</div>
						<h3 class="text-center"><i class="fa fa-database"></i> Developer::Run Migration</h3>
						<div class="form d-flex align-items-center">
							<div class="content">

								<?php echo flash_message_success('status_msg'); ?>
								<?php echo flash_message_danger('status_msg_error'); ?>
							
								<?php echo form_open('db_migrations/run_migration'); ?>

									<div class="form-group">
										<label>Developer Email</label>
										<input type="email" name="email" class="form-control p-l-10" required>	
									</div>

									<div class="form-group">
										<label>Developer Password</label>
										<input type="password" name="password" class="form-control p-l-10" value="" required>
									</div>
									
									<button class="btn btn-primary btn-block">Run Migration</button>

								<?php echo form_close(); ?>

								<div>
									<ul class="home_links_super_admin">
										<li><a href="<?php echo base_url(); ?>">Home</a></li>
										<li><a href="<?php echo base_url('admin'); ?>">Client Panel</a></li>
									</ul>
								</div>
								
							</div><!--/.content-->
						</div><!--/.form d-flex-->

					</div><!--/.col-lg-6 switch-user-->
					
				</div><!--/.row-->
			</div><!--/.form-holder-->
		</div><!--/.container-->
		<!--/Page content-->
		
		
      
