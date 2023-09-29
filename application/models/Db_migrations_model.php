<?php
defined('BASEPATH') or exit('Direct access to script not allowed');

/* ===== Documentation ===== 
Name: Report_templates_model
Role: Model
Description: Controls the DB processes of report templates
Controller: Report_templates
Author: Nwankwo Ikemefuna
Date Created: 1st November, 2018
*/


class Db_migrations_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('fees_model');
	}





}