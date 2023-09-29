<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Misc
Role: Controller
Description: General Class controls miscellaneous pages such as refresh page
Model: 
Author: Nwankwo Ikemefuna
Date Created: 21st January, 2019
*/


class Misc extends MY_Controller {
    public function __construct() {
        parent::__construct();
    }

    
    
    public function refresh_page() { 
        redirect($this->agent->referrer());
    }
    
    
    
    
    
}