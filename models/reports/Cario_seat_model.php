<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cario_seat_model extends CI_Model {

    public function __construct() {
        parent::__construct();
		
		$this->db=$this->load->database();
		$this->db3=$this->load->database('local', TRUE);
	}

    public function sel_cario($sin_time,$ein_time) {
        
		$this->db3->select('cario_no, station_no, obj_id as lpr, in_out, in_lane, in_time as in_lane_datetime, out_time as out_lane_datetime, out_lane')
                 ->from('cario')
                 ->where('in_time>=',$sin_time)
                 ->where('in_time<',$ein_time)
                 ->order_by('cario_no asc');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }
	
    public function sel_cario_seat_log($cario_no) {
        
		$this->db3->from('cario_seat_log')
                  ->where('cario_no',$cario_no)
                  ->order_by('cario_no asc');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }
	
    public function insert_cario_seat_log($data) {
        
		$this->db3->insert('cario_seat_log',$data);
				 
        $sqlString = $this->db3->last_query();

    }
	
    public function update_cario_seat_log($cario_no,$data) {
        
		$this->db3->where('cario_no',$cario_no)
				  ->update('cario_seat_log',$data);
				 
        $sqlString = $this->db3->last_query();

    }
	
    public function sel_cario_seat($sin_time,$ein_time) {
        
		$this->db3->select('*')
                  ->from('cario_seat_log')
                  ->where('in_lane_datetime>=',$sin_time)
                  ->where('in_lane_datetime<',$ein_time)
                  ->order_by('cario_no asc');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }
	
    public function sel_cario_lpr($obj_id) {

                $this->db3->select('*')
                 ->from('cario')
                 ->where('obj_id',$obj_id)
                 ->order_by('in_time desc')
                 ->limit('1');

        $rows = $this->db3->get();
        return $rows->result_array();

    }

	
	
}