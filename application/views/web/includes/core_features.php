
<section class="core_features m-b-100">
	<div class="container">
		<div id="features-slider" class="owl-carousel owl-theme">    

			<?php 
            foreach ($modules as $m) {  
                $icon = get_module_icon($m->module); ?>
                <div class="item">
                	<center>
	                    <div class="icon"><i class="<?php echo $icon; ?>"></i></div>
	                    <h3 class="title"> <?php echo $m->module; ?> </h3>
	                </center>
                </div>
            <?php } ?>

		</div>

		<div class="text-center">
			<a href="<?php echo base_url('web/features'); ?>" data-aos="fade" data-aos-easing="linear" data-aos-duration="1000" data-aos-once="true" class="btn my-4 font-weight-bold atlas-cta cta-green">More Features</a>
		</div>

	</div>
</section>
