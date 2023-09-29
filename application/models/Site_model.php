<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== 
Name: Site_model
Role: Model
Description: Controls the DB processes of the site 
Controller: Site
Author: Nwankwo Ikemefuna
Date Created: 17th April, 2018
*/


class Site_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('cron_model');
	}


	
}