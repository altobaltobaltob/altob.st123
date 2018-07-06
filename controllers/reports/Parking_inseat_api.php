<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('/home/bigbang/libs/phplibs/phpMQTT.php');

class Parking_inseat_api extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('reports/Update_pks_groups_model','update_pks_groups');
    }

	public function index()
	{
		
		$station_no="40705";
		$group_id="P1";

		$sel_pks_groups_group_id=$this->update_pks_groups->sel_pks_groups_group_id($station_no,$group_id);
		$floors=isset($sel_pks_groups_group_id[0]['floors']) ? $sel_pks_groups_group_id[0]['floors'] : '' ;
		$station_no=isset($sel_pks_groups_group_id[0]['station_no']) ? $sel_pks_groups_group_id[0]['station_no'] : '' ;
		$tot=isset($sel_pks_groups_group_id[0]['tot']) ? $sel_pks_groups_group_id[0]['tot'] : '' ;
		$parked=isset($sel_pks_groups_group_id[0]['parked']) ? $sel_pks_groups_group_id[0]['parked'] : '' ;
		$spare=isset($sel_pks_groups_group_id[0]['availables']) ? $sel_pks_groups_group_id[0]['availables'] : '' ;
		$type=isset($sel_pks_groups_group_id[0]['type']) ? $sel_pks_groups_group_id[0]['type'] : '' ;
		$datetime=date('Y-m-d H:i:s'); ;

		$data=array(
				"floors"	=>	$floors,
				"station_no"	=>	$station_no,
				"tot"	=>	$tot,
				"parked"	=>	$parked,
				"spare"	=>	$spare,
				"type"	=>	$type,
				"datetime"	=>	$datetime
			);		

			
				$apijson= "http://altapi.altob.com.tw/api/Parking_inseat_api";
		
				$ch = curl_init();
		
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_URL, $apijson);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); 
		
				$output = curl_exec($ch);
				curl_close($ch);
				
				print_r($output);exit;
			
	}
	

	
}
