<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('/home/bigbang/libs/phplibs/phpMQTT.php');

class Update_localhost_pks_groups extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('reports/Update_pks_groups_model','update_pks_groups');
    }

	public function index()
	{
		
		$station_no="40705";
		
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://192.168.10.80:5477/parktron/ipms/services/areaCount/findAll');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5); //timeout in seconds
            curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));  
            $result = curl_exec($ch);
                        
                        $parktron_result = json_decode($result);
                        
            curl_close($ch);	
			
			$result0=json_decode($result,true);
			$data0=array("tot"=>$result0['areaCountList']['spaceCount'],"parked"=>$result0['areaCountList']['parkingCount'],"availables"=>$result0['areaCountList']['blankingCount']);
			print_r($data0);exit;	
			
			$this->update_pks_groups->update_pks_groups($data0, $fgroup_id);
		
		$sel_pks_groups_all=$this->update_pks_groups->sel_pks_groups_all();
		
		foreach($sel_pks_groups_all as $key99 => $value99){
			$topic="SUBLEVEL";
			$mode=$value99['floors'];
			$floors=$value99['floors'];
			$availables=$value99['tot']-$value99['parked']-$value99['renum'];
			$mqtt_string=$floors.','.$availables;
			
			$this->mq_send($topic, $mqtt_string);
			
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
