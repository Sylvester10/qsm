
				</div><!--/.x_content-->
			</div><!--/.x_panel-->
		</div><!-- /right_col -->

        <!-- footer content -->
        <footer>
			<div class="pull-right">
				Powered by <a href="<?php echo software_vendor_site; ?>"><?php echo software_vendor; ?></a>
			</div>
			Copyright &copy; <?php echo date('Y'); ?>. <a href="<?php echo base_url(); ?>" target="_blank"><?php echo software_initials; ?></a>
			<div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
		
      </div><!--/.main_container-->
    </div><!--/.container body-->


    <?php 
    //require footer scripts
    require "application/views/shared/assets/footer_assets.php"; ?>
	
	
	<script>
        //pass base_url, current date and current controller to javascript
        var base_url ="<?php echo base_url(); ?>";
        var date_today ="<?php echo date('Y/m/d'); ?>";
        var c_controller = "<?php echo $this->c_controller; ?>";
    </script>
	
</body>
</html>
