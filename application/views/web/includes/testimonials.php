
<?php 
if ( count($published_testimonials) > 0 ) { ?>

	<section class="testimonials">
		<div class="container">
			<div class="row">

				<div class="col-md-12">
					<h2 class="text-center text-white m-t-50">What Clients Say</h2>
				</div>
			
				<div class="testimonial-slider owl-carousel owl-theme">

					<?php
					foreach ($published_testimonials as $t) { ?>

						<div class="item text-center">
							<p><i class="fa fa-quote-left quote-left"></i> 
								<?php echo $t->testimony; ?>
								<i class="fa fa-quote-right quote-right"></i>
							</p>
							<div class="client-info">
								<h3 class="client-name"><?php echo $t->name; ?></h3>
								<small><?php echo $t->designation; ?></small>
							</div>
						</div>

					<?php } ?>

				</div>
			</div>
		</div>
	</section>

<?php } ?>