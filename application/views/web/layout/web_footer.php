    
        <!-- contact -->
        <?php 
        $form_attributes = array("id" => "contact_us_form");
        echo form_open('web/contact_us_ajax', $form_attributes); ?>

            <div class="jumbotron jumbotron-fluid" id="contact" style="background-image: url(<?php echo base_url(); ?>assets/web/img/contact-bk.jpg);">
                <div class="container my-5">
                    <h2 class="font-weight-bold text-white m-b-20">Contact Us</h2>
                    <div class="row justify-content-between">
                        <div class="col-md-4 text-white p-b-50">
                            <p><b>United Kingdom</b>
                                <br>
                                140 Danbury Crescent<br> 
                                South Ockendon, Essex<br> 
                                RM15 5XE, United Kingdom.<br>
                                +44(0)7453794200
                            </p>   
                            <p>
                                <b>Nigeria</b>
                                <br>
                                15 Maitama Sule Street<br> 
                                Off Raymond Njoku Street<br> 
                                Ikoyi, Lagos, Nigeria.<br>
                                +234(0)8035004519
                            </p>                   
                        </div>

                        <div class="col-md-8">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="text-white">Your Name</label>
                                    <input type="name" class="form-control" name="name" value="<?php echo set_value('name'); ?>" required />
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="text-white">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo set_value('email'); ?>" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-white">Message</label>
                                <textarea class="form-control" rows="3" name="message" required><?php echo set_value('message'); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="text-white">Captcha</label>
                                    <input type="text" id="captcha_code" class="form-control" name="captcha" value="<?php echo set_value('captcha', $captcha_code); ?>" readonly />
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="text-white">Confirm Captcha</label>
                                    <input type="text" class="form-control" name="c_captcha" value="<?php echo set_value('c_captcha'); ?>" placeholder="Enter captcha code here" required />
                                </div>
                            </div>

                            <div id="status_msg"></div>
                            
                            <button type="submit" class="btn font-weight-bold atlas-cta atlas-cta-wide cta-green my-3">Submit</button>
                            
                        </div>
                    </div>
                </div>
            </div>

        <?php echo form_close(); ?>



        <!-- copyright -->
        <div class="jumbotron jumbotron-fluid" id="copyright">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-6 text-white align-self-center text-center text-md-left my-2">
                        Copyright © 2018 <?php echo software_initials; ?>. All rights reserved.
                    </div>
                    <div class="col-md-6 align-self-center text-center text-md-right my-2" id="social-media">
                        <a href="https://web.facebook.com/quickschoolmanager" target="_blank" class="d-inline-block text-center ml-2">
                            <i class="fa fa-facebook" aria-hidden="true"></i>
                        </a>
                        <a href="https://twitter.com/QuickSchoolMan" target="_blank" class="d-inline-block text-center ml-2">
                            <i class="fa fa-twitter" aria-hidden="true"></i>
                        </a>
                        <a href="#" class="d-inline-block text-center ml-2">
                            <i class="fa fa-linkedin" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        

        <?php require "application/views/web/layout/includes/footer_assets.php"; ?>
        <?php require "application/views/web/includes/apis/tawk.php"; ?>

    </div><!--/.body-->
    
</body>

</html>