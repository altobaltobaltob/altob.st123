<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Erpapidb_model extends CI_Model {

    public function __construct() {
        parent::__construct();
		
		$this->db=$this->load->database();
		$this->db3=$this->load->database('local', TRUE);
	}

    public function sel_cario($in_lane,$start,$end) {
        
		$this->db3->select('obj_id')
                 ->from('cario')
                 ->where('in_lane',$in_lane)
                 ->where('in_time>=',$start)
                 ->where('in_time<=',$end);
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }

    public function sel_in_lane() {
        
		$this->db3->select('in_lane,name,note')
                 ->from('in_lane');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }

    public function sel_cario_out($in_lane,$start,$end) {
        
		$this->db3->select('obj_id')
                 ->from('cario')
                 ->where('out_lane',$in_lane)
                 ->where('out_time>=',$start)
                 ->where('out_time<=',$end);
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }

    public function sel_cario_api() {
        
		$this->db3->select('*')
                 ->from('cario')
                 ->where('in_out','CI')
                 ->where('member_no',0)
                 ->order_by('in_time desc')
                 ->limit('100');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }



}