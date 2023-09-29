<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Data_management
Role: Controller
Description: Data_management Class controls data mgt functions
Model: Data_management_model
Author: Nwankwo Ikemefuna
Date Created: 23rd August, 2018
*/



class Data_management extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->super_admin_restricted(); //allow only logged in super admins to access this class
		$this->load->model('super_admin_model');
		$this->load->model('data_management_model');
		$this->super_admin_details = $this->common_model->get_super_admin_details($this->session->super_admin_email);

		//module-level scripts
		$this->super_admin_module_scripts = array();
	}




	/* ====== Dashboard ====== */
	
	public function data_backup() { 
		$this->super_admin_header('Data Backup', 'Data Backup');
		$backup_info = $this->data_management_model->get_database_backup_info();
		$id = $backup_info->last_backup_by;
		$last_backup_by = $this->common_model->get_super_admin_details_by_id($id)->name;
		$data['last_backup_date'] = $backup_info->last_backup_date;
		$data['last_backup_by'] = $last_backup_by;
		$this->load->view('super_admin/data_management/data_backup', $data);
		$this->super_admin_footer();
	}
	
	
	public function backup_database() { 
	
		$prefs = array(
			'tables'        => array(),   					// All tables
			'ignore'        => array(),                     // List of tables to omit from the backup
			'format'        => 'zip',                       // gzip, zip, txt
			'filename'      => 'qsm_backup.sql',            // File name - NEEDED ONLY WITH ZIP FILES
			'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
			'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
			'newline'       => "\n"                         // Newline character used in backup file
		);
		
		$backup = $this->dbutil->backup($prefs);
		
		//name this backup using today's date
		$date = date('d-m-Y');
		$backup_name = $date . '-backup.zip';
		
		$file_path = 'assets/data/database_backups/' . $backup_name;
		
		//write file to server
		write_file($file_path, $backup_name);
		
		//Update backup info
		$this->data_management_model->update_database_backup_info();

		//send file to desktop
		force_download($backup_name, $backup);
	}
	
	
	public function database_metadata() { 
		$this->super_admin_header('Database Metadata', 'Database Metadata');
		$this->load->view('super_admin/data_management/database_metadata');
		$this->super_admin_footer();
	}
	
	
	
}