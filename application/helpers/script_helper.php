<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* ===== Documentation ===== 
Name: script_helper
Role: Helper
Description: Scripts helper for pages
Author: Nwankwo Ikemefuna
Date Created: 20th January, 2019
Date Modified: 21st January, 2019
*/



/* ============= Web =============== */

function general_web_scripts() {
    ?>
    <script src="<?php echo base_url(); ?>assets/web/js/aos.js"></script>
    <!-- AOS -->
    <script>
      AOS.init({
      });
    </script>
    <!-- JavaScript Libraries -->
    <script src="<?php echo base_url(); ?>assets/web/lib/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/web/lib/jquery/jquery.cookie.js"></script>
    <script src="<?php echo base_url(); ?>assets/web/lib/jquery/jquery-migrate.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/web/lib/bootstrap/js/bootstrap.min.js"></script>

    <script src="<?php echo base_url(); ?>assets/web/lib/easing/easing.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/web/lib/wow/wow.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/web/lib/superfish/hoverIntent.js"></script>
    <script src="<?php echo base_url(); ?>assets/web/lib/superfish/superfish.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/web/lib/magnific-popup/magnific-popup.min.js"></script>

    <!-- Owl Carousel -->
    <script type="text/javascript" src="assets/web/lib/owl-carousel/js/owl.carousel.min.js"></script>

    <!-- Lightbox -->
    <script src="<?php echo base_url(); ?>assets/vendors/lightbox/dist/js/lightbox.min.js"></script>

    <!-- Custom script -->
    <script src="<?php echo base_url(); ?>assets/web/js/custom.js"></script>
    <?php
}


function web_module_level_scripts($module) { 
    //module scripts folder
    $script_folder = base_url() . 'assets/custom/js/web/';
    $module_scripts = [];
    $script_file = $module_scripts[$module] . '.js'; //add .js extension to script name
    $full_script_path = $script_folder . $script_file;
    return '<script src="' . $full_script_path . '"></script>'; 
}






/* ============= Site =============== */

function general_site_scripts() {
    ?>
    <!-- JavaScript files-->
    <script src="<?php echo base_url(); ?>assets/site/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/site/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/site/vendor/popper.js/umd/popper.min.js"> </script>
    <script src="<?php echo base_url(); ?>assets/site/vendor/jquery-validation/jquery.validate.min.js"></script>
    
    <!-- Main File-->
    <script src="<?php echo base_url(); ?>assets/site/js/front.js"></script>
    <script src="<?php echo base_url(); ?>assets/site/js/script.js"></script>
    <?php
}


function site_module_level_scripts($module) { 
    //module scripts folder
    $script_folder = base_url() . 'assets/site/js/';
    $module_scripts = [
        's_admin_acc' => 'admin_acc',
        's_demo' => 'demo',
        's_installation' => 'installation',
        's_installation_rave' => 'installation_rave',
        's_parent_acc' => 'parent_acc',
        's_staff_acc' => 'staff_acc',
        's_student_acc' => 'student_acc',
        's_super_admin_acc' => 'super_admin_acc',
    ];
    $script_file = $module_scripts[$module] . '.js'; //add .js extension to script name
    $full_script_path = $script_folder . $script_file;
    return '<script src="' . $full_script_path . '"></script>'; 
}






/* ============= User Panels =============== */

function general_user_panel_scripts() {
    ?>
    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>assets/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/jquery-marquee/jquery.marquee.min.js"></script>
    
    <!-- Bootstrap -->
    <script src="<?php echo base_url(); ?>assets/vendors/selectpicker/dist/js/bootstrap-select.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/timepicker/js/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/toggle/js/bootstrap-toggle.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/vendors/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>

    <!-- Bootstrap TagsInput
    <script src="<?php echo base_url(); ?>assets/vendors/bootstrap-tagsinput/src/bootstrap-tagsinput.js"></script> -->
    
    <!--DataTables-->
    <script src="<?php echo base_url(); ?>assets/vendors/DataTables/datatables.min.js"></script>    
    
    <!-- jQuery Autocomplete -->
    <script src="<?php echo base_url(); ?>assets/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    
    <!-- jQuery Autocomplete -->
    <script src="<?php echo base_url(); ?>assets/vendors/select2/dist/js/select2.min.js"></script>

    <!-- jQuery Radio Buttons -->
    <script src="<?php echo base_url(); ?>assets/vendors/jquery-radio/jquery.radiosforbuttons.js"></script>

    <!-- jQuery TagsInput -->
    <script src="<?php echo base_url(); ?>assets/vendors/jquery-tagsinput/src/jquery.tagsinput.js"></script>
    
    <!--ClipboardJS-->  
    <script src="<?php echo base_url(); ?>assets/vendors/clipboard/clipboard.min.js"></script>
    
    <!--Tiny MCE Editor-->  
    <script src="<?php echo base_url(); ?>assets/vendors/tinymce/tinymce.min.js"></script>

    <!-- Dropzone.js -->
    <script src="<?php echo base_url(); ?>assets/vendors/dropzone/min/dropzone.min.js"></script>

    <!-- Lightbox -->
    <script src="<?php echo base_url(); ?>assets/vendors/lightbox/dist/js/lightbox.min.js"></script>

    
    <!-- Template Scripts -->
    <script src="<?php echo base_url(); ?>assets/build/js/custom.js"></script>


    <!-- Custom Scripts -->
    <script src="<?php echo base_url(); ?>assets/custom/js/general/num2words.js"></script>
    <script src="<?php echo base_url(); ?>assets/custom/js/general/nigerian_lgas_ajax.js"></script>
    <script src="<?php echo base_url(); ?>assets/custom/js/general/general.js"></script>
    <script src="<?php echo base_url(); ?>assets/custom/js/general/dt_export.js"></script>
    <?php
}


function admin_module_level_scripts($module) { 
    //module scripts folder
    $script_folder = base_url() . 'assets/custom/js/admin/';
    $module_scripts = [
        's_admin' => 'admin',
        's_admin_users' => 'admin_users',
        's_classes' => 'classes',
        's_incidents' => 'incidents',
        's_parent_management' => 'parent_management',
        's_plan' => 'plan', //plan, activate, upgrade 
        's_plan_rave' => 'plan_rave', //plan, activate, upgrade (for rave)
        's_prs' => 'prs',
        's_publications' => 'publications',
        's_school_library' => 'school_library',
        's_sections' => 'sections',
        's_settings' => 'settings',
        's_staff_management' => 'staff_management',
        's_student_management' => 'student_management',
        's_student_search' => 'student_search',
        's_subjects' => 'subjects',
        's_timetable' => 'timetable',
        's_weekly_reports' => 'weekly_reports',
    ];
    $script_file = $module_scripts[$module] . '.js'; //add .js extension to script name
    $full_script_path = $script_folder . $script_file;
    return '<script src="' . $full_script_path . '"></script>'; 
}


function staff_module_level_scripts($module) { 
    //module scripts folder
    $script_folder = base_url() . 'assets/custom/js/staff/';
    $module_scripts = [
        's_class_teacher' => 'class_teacher',
        's_prs' => 'prs',
        's_publications' => 'publications',
        's_school_library' => 'school_library',
        's_staff' => 'staff',
        's_student_search' => 'student_search',
        's_subject_teacher' => 'subject_teacher',
        's_weekly_reports' => 'weekly_reports',
    ];
    $script_file = $module_scripts[$module] . '.js'; //add .js extension to script name
    $full_script_path = $script_folder . $script_file;
    return '<script src="' . $full_script_path . '"></script>'; 
}


function student_module_level_scripts($module) { 
    //module scripts folder
    $script_folder = base_url() . 'assets/custom/js/student/';
    $module_scripts = [
        's_student' => 'student',
        's_student_reports' => 'student_reports',
    ];
    $script_file = $module_scripts[$module] . '.js'; //add .js extension to script name
    $full_script_path = $script_folder . $script_file;
    return '<script src="' . $full_script_path . '"></script>'; 
}


function parent_module_level_scripts($module) { 
    //module scripts folder
    $script_folder = base_url() . 'assets/custom/js/parent/';
    $module_scripts = [
        's_parent' => 'parent',
        's_student_reports' => 'student_reports',
    ];
    $script_file = $module_scripts[$module] . '.js'; //add .js extension to script name
    $full_script_path = $script_folder . $script_file;
    return '<script src="' . $full_script_path . '"></script>'; 
}


function super_admin_module_level_scripts($module) { 
    //module scripts folder
    $script_folder = base_url() . 'assets/custom/js/super_admin/';
    $module_scripts = [
        's_coupon' => 'coupon',
        's_cron_jobs' => 'cron_jobs',
        's_demo_accounts' => 'demo_accounts',
        's_publications' => 'publications',
        's_school_accounts' => 'school_accounts',
        's_super_admin' => 'super_admin',
        's_support' => 'support',
        's_testimonial' => 'testimonial',
    ];
    $script_file = $module_scripts[$module] . '.js'; //add .js extension to script name
    $full_script_path = $script_folder . $script_file;
    return '<script src="' . $full_script_path . '"></script>'; 
}


function shared_module_level_scripts($module) { 
    //module scripts folder
    $script_folder = base_url() . 'assets/custom/js/shared/';
    $module_scripts = [
        's_fees' => 'fees',
        's_student_reports' => 'student_reports',
        's_kad_student_reports' => 'bespoke/kad_academy/student_reports',
    ];
    $script_file = $module_scripts[$module] . '.js'; //add .js extension to script name
    $full_script_path = $script_folder . $script_file;
    return '<script src="' . $full_script_path . '"></script>'; 
}