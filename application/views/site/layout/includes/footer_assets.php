<?php

    //general user panel scripts
    echo general_site_scripts();

    //site module-level scripts
    foreach ($this->site_module_scripts as $script) {
        echo site_module_level_scripts($script) . "\r\n"; 
    } 

?>


<script>
    //pass base_url, current date and current controller to javascript
    var base_url ="<?php echo base_url(); ?>";
    var date_today ="<?php echo date('Y/m/d'); ?>";
    var c_controller = "<?php echo $this->c_controller; ?>";
</script>
