<?php
defined('BASEPATH') or die('Direct access not allowed');


/* ===== Documentation ===== 
Name: Db_migrations
Role: Controller
Description: Db_migrations helps to manage database changes from development server to production server while maintaining data integrity. All database changes are made here using CI's DB Forge class. 
How it Works: 
* Before deploying updated code to production server, copy this file and its associated view file (developer/db_updates) to the production server. 
* Open the page via URL: base_url/db_migrations
* Enter developer pass and click Update Database button
* If all is successfull, continue with deploying update code.
Model: (None)
Author: Nwankwo Ikemefuna
Date Created: 8th November, 2018
*/


class Db_migrations extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->dbforge(); //database forge class (for making database changes)
		$this->success = array();
		$this->error = array();
		$this->enabled = FALSE;
		$this->developer_emails = array('valkaycelestino@gmail.com', 'sylvester@gmail.com');
		$this->developer_password = 'superdev?valkay255';
	}


	
	public function index() { 
		//check that migration is enabled
		$this->check_migration_enabled();
		$this->login_header('Database Migrations');
		$data['new_changes'] = $this->new_changes();
		$data['last_changes'] = $this->last_changes_made();
		$this->load->view('developer/run_migration', $data);
		$this->login_footer();
	}	


	private function new_changes() {
		//State changes to be made here
		$changes = array(
			'Update sections table',
			'Update test_scores table',
			'Update report_evaluation table',
		);
		return $changes;
	}


	private function last_changes_made() {
		//State changes to be made here
		$changes = array();
		return $changes;
	}


	function run_migration() {
		$this->form_validation->set_rules('email', 'Developer Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Developer Password', 'trim|required');

		$email = $this->input->post('email', TRUE);
		$password = $this->input->post('password', TRUE);

		if ($this->form_validation->run()) {

			if ( in_array($email, $this->developer_emails) && $password == $this->developer_password ) {

				//the changes
				$this->the_changes();

				//Success message
				$this->success_message();
				//Error message
				$this->error_message();

			} else {
				$this->session->set_flashdata('status_msg_error', 'Invalid developer details!');	
			}
			redirect('db_migrations');

		} else {
			$this->index(); //reload page with validation errors
		}
	}


	private function the_changes() {
		//List all migrations to run here by calling the required functions

		//update report evaluation table
		$this->update_report_evaluation();

	}




	private function update_report_evaluation() {
		// Will place the new column after the `another_field` column:
		//'first' => TRUE will place it at the begininning
		$fields = array(
	        'gp' => array(
	                'type' => 'INT',
					'after' => 'grade',
	        ),
		);
		$run = $this->dbforge->add_column('report_evaluation', $fields);

		if ($run) {
			$message = 'Report Evaluation table updated successfully!';
			array_push($this->success, $message);
		} else {
			$message = 'Error updating Report Evaluation table!';
			array_push($this->error, $message);
		}
	}


	private function update_sections() {
		// Will place the new column after the `another_field` column:
		//'first' => TRUE will place it at the begininning
		$fields = array(
	        'show_previous_grade' => array(
	                'type' => 'INT',
					'after' => 'grade',
	        ),
		);
		$run = $this->dbforge->add_column('report_evaluation', $fields);

		if ($run) {
			$message = 'Report Evaluation table updated successfully!';
			array_push($this->success, $message);
		} else {
			$message = 'Error updating Report Evaluation table!';
			array_push($this->error, $message);
		}
	}






	//Success Message
	private function success_message() {
		if ( ! empty($this->success) ) {
			$success_messages_string = implode("<br />", $this->success);
			$success_messages_string = '<b>The following migrations were successful:</b> <br />' . $success_messages_string; 
			return $this->session->set_flashdata('status_msg', $success_messages_string);	
		}
	}


	//Error Message
	private function error_message() {
		if ( ! empty($this->error) ) {
			$error_messages_string = implode("<br />", $this->error);
			$error_messages_string = '<b>The following errors were encountered:</b> <br />' . $error_messages_string; 
			return $this->session->set_flashdata('status_msg_error', $error_messages_string);	
		}
	}


	private function check_migration_enabled() {
		if ($this->enabled) {
			return TRUE;	
		} else {
			$this->session->set_flashdata('status_msg_error', 'Migration disabled!');
			redirect(base_url());	
		}
	}



	








	/* ============== SAMPLES ============= */


	private function create_table() {
		$fields = array(
	        'blog_id' => array(
	                'type' => 'INT',
	                'constraint' => 5,
	                'unsigned' => TRUE,
	                'auto_increment' => TRUE
	        ),
	        'blog_title' => array(
	                'type' => 'VARCHAR',
	                'constraint' => '100',
	                'unique' => TRUE,
	        ),
	        'blog_author' => array(
	                'type' =>'VARCHAR',
	                'constraint' => '100',
	                'default' => 'King of Town',
	        ),
	        'blog_description' => array(
	                'type' => 'TEXT',
	                'null' => TRUE,
	        ),
		);
		//add the fields
		$this->dbforge->add_field($fields);
		//create the table with attributes
		$attributes = array('ENGINE' => 'InnoDB');
		$this->dbforge->create_table('table_name', TRUE, $attributes);
		// produces: CREATE TABLE IF NOT EXISTS `table_name` (...) ENGINE = InnoDB DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci
	}


	private function drop_table() {
		// Produces: DROP TABLE IF EXISTS table_name
		$this->dbforge->drop_table('table_name',TRUE);
	}


	private function rename_table() {
		// produces: ALTER TABLE old_table_name RENAME TO new_table_name
		$this->dbforge->rename_table('old_table_name', 'new_table_name');
	}


	private function add_column() {
		// 'after' will place the new column after the `another_field` column:
		//'first' => TRUE will place it at the begininning
		$fields = array(
		        'preferences' => array('type' => 'TEXT', 'after' => 'another_field')
		);
		$this->dbforge->add_column('table_name', $fields);
		// Executes: ALTER TABLE table_name ADD preferences TEXT
	}


	private function modify_column() {
		$fields = array(
	        'old_name' => array(
	                'name' => 'new_name',
	                'type' => 'TEXT',
	        ),
		);
		$this->dbforge->modify_column('table_name', $fields);
		// gives ALTER TABLE table_name CHANGE old_name new_name TEXT
	}


	private function drop_column() {
		$this->dbforge->drop_column('table_name', 'column_to_drop');
	}




}