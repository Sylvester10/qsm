
<section class="screenshots m-b-100">
	<div class="container">
		<h2 class="text-center font-weight-bold d-block mb-3">Screenshots</h2>
		<div id="screenshots-slider" class="owl-carousel owl-theme">    

			<?php
			//if no published  screenshots, use 13 default screenshots
			if ( $total_screenshots === 0 ) { 
				
				for ($i = 1; $i <= 13; $i++) { 

					$df_screenshot = 'image'.$i.'.jpg'; ?>

					<div class="item">
						<a data-lightbox="screenshots" href="<?php echo base_url('assets/images/default_screenshots/'.$df_screenshot); ?>">
							<img src="<?php echo base_url('assets/images/default_screenshots/'.$df_screenshot); ?>" alt="Screenshot">
						</a>
					</div>

				<?php }

			} else { 

				foreach ($screenshots as $s) { ?>

					<div class="item">
						<a data-lightbox="screenshots" href="<?php echo base_url('assets/uploads/screenshots/'.$s->screenshot); ?>">
							<img src="<?php echo base_url('assets/uploads/screenshots/'.$s->screenshot); ?>" alt="Screenshot">
						</a>
					</div>

				<?php } 

			} ?>

		</div>
	</div>
</section>