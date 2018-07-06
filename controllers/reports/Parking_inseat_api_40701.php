<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('/home/bigbang/libs/phplibs/phpMQTT.php');

class Parking_inseat_api_40701 extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('reports/Update_pks_groups_model','update_pks_groups');
    }

	public function index()
	{
		
		$sel_pks_groups_all=$this->update_pks_groups->sel_pks_groups_all();
		
		foreach($sel_pks_groups_all as $key0 => $value0){
		$group_id="P1";

		$floors=isset($value0['floors']) ? $value0['floors'] : '' ;
		$station_no=isset($value0['station_no']) ? $value0['station_no'] : '' ;
		$tot=isset($value0['tot']) ? $value0['tot'] : '' ;
		$parked=isset($value0['parked']) ? $value0['parked'] : '' ;
		$spare=isset($value0['availables']) ? $value0['availables'] : '' ;
		$type=isset($value0['type']) ? $value0['type'] : '' ;
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
				
		}
			
	}
	

	
}
