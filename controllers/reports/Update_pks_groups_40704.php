<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('/home/bigbang/libs/phplibs/phpMQTT.php');

class Update_pks_groups extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('reports/Update_pks_groups_model','update_pks_groups');


    }



	public function index()
	{
		
		$station_no="40703";
		$group_type="0";
		
		$sel_pks_groups=$this->update_pks_groups->sel_pks_groups($station_no,$group_type);
		
		foreach($sel_pks_groups as $key0 => $value0){
			$station_no0=$value0['station_no'];
			$fgroup_id=$value0['group_id'];
					
			$sel_pks_all=$this->update_pks_groups->sel_pks_all($station_no, $fgroup_id);
			$sel_pks=$this->update_pks_groups->sel_pks($station_no, $fgroup_id);
			
			$data[$fgroup_id]=array(count($sel_pks_all),count($sel_pks),count($sel_pks_all)-count($sel_pks));
			
			$data0=array("tot"=>count($sel_pks_all),"parked"=>count($sel_pks),"availables"=>count($sel_pks_all)-count($sel_pks),"renum"=>count($sel_pks_all)-count($sel_pks));
			
			$this->update_pks_groups->update_pks_groups($data0, $fgroup_id);
		}
		
		
		$sel_pks_groups_all=$this->update_pks_groups->sel_pks_groups_all();
		
		foreach($sel_pks_groups_all as $key99 => $value99){
			$topic="SUBLEVEL";
			$mode=$value99['floors'];
			$floors=$value99['floors'];
			$availables=$value99['availables'];
			$mqtt_string=$floors.','.$availables;
			
//			$this->mq_send($topic, $mqtt_string);
			
		}
		
		

	}
	

	public function mq_send($topic, $msg)
	{
		// 取得 mqtt 設定
		$mqtt_ip = 'localhost';
		$mqtt_port = '1883';
		trigger_error("mqtt: {$mqtt_ip}:{$mqtt_port}");
		
		// mqtt subscribe
		$mqtt = new phpMQTT($mqtt_ip, $mqtt_port, uniqid());
		if($mqtt->connect()){ 

			$mqtt->publish($topic, $msg, 0);
		}
    	trigger_error("mqtt:{$topic}|{$msg}");
    }

	
	
}
