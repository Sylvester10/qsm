<?php
defined('BASEPATH') or exit('Direct access to script not allowed');


/* ===== Documentation ===== */
/*
Name: Common_model
Role: Model
Description: Controls bespoke KAD Academy DB queries
Controller: All
Author: Nwankwo Ikemefuna
Date Created: 19th February, 2019
*/


class Bespoke_kad_model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }



    public function get_report_templates22333($school_id, $range) { 
        return $this->db->get_where('report_evaluation', array('school_id' => $school_id, 'range' => $range))->row();
    }


    public function get_report_evaluation($school_id) { 
        $this->db->order_by('range', 'desc'); //order by range
        return $this->db->get_where('report_evaluation', array('school_id' => $school_id))->result();
    }




}