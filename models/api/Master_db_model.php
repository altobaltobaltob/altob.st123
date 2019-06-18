<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_db_model extends CI_Model {

    public function __construct() {
        parent::__construct();

                 $this->db=$this->load->database('local',true);

        }

    public function sel_cario($lpr) {
                $fee_time=date('Y-m-d H:i:s',mktime(0,0,0,date('m'),date('d'),date('Y'))+(date('H')*3600)+((date('i')-30)*60)+date('s')) ;
//print_r($fee_time);exit;
                $this->db->select('*')
                                 ->from('cario')
                                 ->where('in_out','CI')
                                 ->where('finished','0')
                                 ->where('payed','0')
                                 ->where('obj_id',$lpr)
                                 ->order_by('in_time desc')
                                 ->limit(1);

        $rows = $this->db->get();
        return $rows->result_array();

        }

    public function sel_cario_all($lpr) {

                $this->db->from('cario')
                                 ->where('in_out','CI')
                                 ->where('lpr',$lpr)
                                 ->order_by('in_time desc');

        $rows = $this->db->get();
        return $rows->result_array();

        }

    public function sel_cario_like($lpr) {

		$this->db->from('cario')
				  ->where('in_out','CI')
				  ->where('finished','0')
				  ->where('payed','0')
				  ->like('obj_id',$lpr)
				  ->group_by('obj_id')
				  ->order_by('in_time desc');

        $rows = $this->db->get();
        return $rows->result_array();

        }

    public function sel_carioseat($lpr) {
		
		$this->db->select('*')
				  ->from('cario')
				  ->where('in_out','CI')
				  ->where('finished','0')
				  ->where('payed','0')
				  ->where('obj_id',$lpr)
				  ->order_by('in_time desc')
				  ->limit(1);

        $rows = $this->db->get();
        return $rows->result_array();

	}

    public function chk_carioseat($lpr) {

		$this->db->select('*')
				  ->from('cario')
				  ->where('in_out','CO')
				  ->where('obj_id',$lpr)
				  ->where('(finished=1 or payed=1)')
				  ->order_by('in_time desc')
				  ->limit(1);

        $rows = $this->db->get();
        return $rows->result_array();

	}

    public function chk_pks_group_member($pksno,$group_id) {

		$this->db->select('group_id')
				  ->from('pks_group_member')
				  ->where('pksno',$pksno);
		if($group_id!=''){
			$this->db->where('group_id',$group_id);
		}

        $rows = $this->db->get();
        return $rows->result_array();

	}



}
