
<?php

    //general user panel scripts
    echo general_user_panel_scripts();


    //admin module-level scripts
    foreach ($this->admin_module_scripts as $script) {
        echo admin_module_level_scripts($script) . "\r\n"; 
    } 


    //staff module-level scripts
    //echo staff_module_level_scripts('s_student_search') . "\r\n"; //student search in header
    foreach ($this->staff_module_scripts as $script) {
        echo staff_module_level_scripts($script) . "\r\n"; 
    } 


    //student module-level scripts
    foreach ($this->student_module_scripts as $script) {
        echo student_module_level_scripts($script) . "\r\n"; 
    } 


    //student module-level scripts
    foreach ($this->parent_module_scripts as $script) {
        echo parent_module_level_scripts($script) . "\r\n"; 
    } 


    //student module-level scripts
    foreach ($this->super_admin_module_scripts as $script) {
        echo super_admin_module_level_scripts($script) . "\r\n"; 
    } 


    //shared module-level scripts
    foreach ($this->shared_module_scripts as $script) {
        echo shared_module_level_scripts($script) . "\r\n"; 
    } 

?>
	
	