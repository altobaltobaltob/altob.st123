<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parking_inseat_api_post extends MY_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('reports/Update_pks_groups_model','update_pks_groups');

    }



	public function index()
	{
		$sel_pks_groups_all=$this->update_pks_groups->sel_pks_groups_floors();
		
		foreach($sel_pks_groups_all as $key0 => $value0){
			$floors=isset($value0['floors']) ? $value0['floors'] : '' ;
			$station_no=isset($value0['station_no']) ? $value0['station_no'] : '' ;
			$tot=isset($value0['tot']) ? $value0['tot'] : '' ;
			$parked=isset($value0['parked']) ? $value0['parked'] : '' ;
			$availables=isset($value0['availables']) ? $value0['availables'] : '' ;
			$datetime=date('Y-m-d H:i:s'); ;
		
			$data=array(
				"floors"	=>	$floors,
				"station_no"=>	$station_no,
				"tot"		=>	$tot,
				"parked"	=>	$parked,
				"spare"		=>	$availables,
				"type"		=>	9,
				"datetime"	=>	$datetime
			);
			
				$apijson= "http://59.124.122.113/api/Parking_inseat_api/"; //傳送回總公司
		
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