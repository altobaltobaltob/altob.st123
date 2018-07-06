<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

class Update_pks_groups_model extends CI_Model {

    public function __construct() {
        parent::__construct();
		
		$this->db=$this->load->database();
		$this->db3=$this->load->database('local', TRUE);
	}

    public function sel_pks_groups_all() {
        
		$this->db3->select('*')
                 ->from('pks_groups')
                 ->where('group_type !=',0)
                 ->order_by('group_id asc');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }
	
    public function sel_pks_groups_floors_id($floors) {
        
		$this->db3->select('*')
                 ->from('pks_groups')
                 ->where('floors',$floors)
                 ->order_by('group_id asc');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }
	
    public function sel_pks_groups_floors() {
        
		$this->db3->select('*')
                 ->from('pks_groups')
                 ->where('floors',"B1")
                 ->or_where('floors',"B2")
                 ->order_by('floors asc');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }
	
    public function sel_pks_groups($station_no,$group_type) {
        
		$this->db3->select('*')
                 ->from('pks_groups')
                 ->where('station_no',$station_no)
                 ->where('group_type',$group_type)
                 ->order_by('group_id asc');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }
	
    public function sel_pks_groups_group_id($station_no,$group_id) {
        
		$this->db3->select('*')
                 ->from('pks_groups')
                 ->where('station_no',$station_no)
                 ->where('group_id',$group_id)
                 ->order_by('group_id asc');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }
	
    public function sel_pks_all($station_no, $fgroup_id) {
        
		$this->db3->select('*')
				  ->from('pks')
				  ->join('pks_group_member','pks_group_member.pksno=pks.pksno')
                  ->where('pks.station_no',$station_no)
                  ->where('pks_group_member.group_id',$fgroup_id)
                  ->order_by('cario_no asc');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }

    public function sel_pks($station_no, $fgroup_id) {
        
		$this->db3->select('*')
				  ->from('pks')
				  ->join('pks_group_member','pks_group_member.pksno=pks.pksno')
                  ->where('pks.station_no',$station_no)
                  ->where('pks.lpr !=','')
                  ->where('pks_group_member.group_id',$fgroup_id)
                  ->order_by('cario_no asc');
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }

    public function update_pks_groups($data, $fgroup_id) {
        
		$this->db3->where('group_id',$fgroup_id)
                 ->update('pks_groups',$data);

        $sqlString = $this->db3->last_query();
		return true;

    }

    public function sel_pks_group_member($pksno) {
        
		$this->db3->select('*')
				  ->from('pks_group_member')
                  ->where('pksno',$pksno);
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }
    public function sel_pks_group_member($pksno) {
        
		$this->db3->select('*')
				  ->from('pks_group_member')
                  ->where('pksno',$pksno);
				 
        $rows = $this->db3->get();
        return $rows->result_array();

    }


    public function update_pks_groupfunction($parms) {
        
		$pksno=$parms['pksno'];
		$sel_pks_group_member=$this->sel_pks_group_member($pksno);
		$group_id=$sel_pks_group_member[0]['group_id'];
		$sel_pks_group_member=$this->sel_pks_group_member($pksno);
		
		
		
    	if($parms['io']=="KI"){
			
		}elseif($parms['io']=="K0"){
			
		}else{
			
		}

		$this->db3->where('group_id',$fgroup_id)
                 ->update('pks_groups',$data);

        $sqlString = $this->db3->last_query();

    }

	
}
	
