
        <!-- Start Our Gallery Area -->
        <section class="" style="padding-bottom: 50px; padding-top: 50px;">
            <div class="container">
                <p class="text-center"><b> Double-click the video to view in full screen </b></p>
                <div class="row">

                    <?php 
                    foreach ($videos as $y) {?>
                        <!-- Start Single Gallery -->
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12">
                            <div class="gallery wow fadeInUp">
                                <div class="thumbnail">
                                    <h5><?php echo $y->title; ?></h5>
                                    <div class="gallery__thumb">
                                        <iframe width="350" height="200" src="<?php echo $y->url; ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>  
                        </div>  
                        <!-- End Single Gallery -->
                    <?php } ?>

                </div> 

                <!--Pagination Links-->
                <div class="m-t-30 m-l-45-n">
                    <?php echo pagination_links($links, 'pagination_round'); ?>
                </div>

            </div>

        </section>
        <!-- End Our Gallery Area -->
        


    