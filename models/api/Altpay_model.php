<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Altpay_model extends CI_Model {

    public function __construct() {
        parent::__construct();
		
		 $this->db=$this->load->database('local',true);

	}

	public function sel_info() {
		$this->db->from('info');
				 
        $rows = $this->db->get();
        return $rows->result_array();
	}
}
