<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cron_runner {
	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}

	private function calculate_next_run($obj) {
		return (time() + $obj->interval_sec);
	}
	
	
	private function update_last_run($obj) {
		$data = array(
			'last_run_at' => date('Y-m-d H:i:s') //now
		);
		$this->CI->db->where('id', $obj->id);
		return $this->CI->db->update('cron_jobs', $data);
	}
	
	
	private function update_next_run($obj) {
		$this->CI->db->set('next_run_at', 'FROM_UNIXTIME('.$this->calculate_next_run($row).')', false);
		$this->CI->db->where('id', $obj->id);
		return $this->CI->db->update('cron_jobs');
	}


	public function run() {
		$this->CI->db->where('is_active', 'true');
		$this->CI->db->where('now() >= next_run_at OR next_run_at IS NULL', '', false);
		$this->CI->db->from('cron_jobs');
		$query = $this->CI->db->get();
		if ($query->num_rows() > 0) {
			$raw_command = array();
			foreach ($query->result() as $row) {
				//execute command
				$frequency = $row->frequency; // * * * * *
				$method = $row->method; //cron controller method
				$command = "{$frequency} /usr/local/bin/php /home/qschoolmanager/public_html/index.php cron {$method}";
				$output = shell_exec($command);
				//update last run
				$this->update_last_run($row); 
				//update last run
				$this->update_next_run($row); 
				//get raw data
				$raw_command[] = $command;
			}   
		} 
		return $raw_command;
	}
	
	
	
}