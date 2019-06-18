<?php
/*
file: cars.php  宏奇 API 查詢入場時間及費用
*/
class Query_in_fuzzy extends CI_Controller
{
        function __construct()
        {
                parent::__construct();
        }

        public function index() 
        {
			$post_data= $this->input->post(NULL, TRUE);
			$get_data= $this->input->get(NULL, TRUE);;
	
			if($post_data){
				$lpr=$post_data['lpr'];
			}elseif($get_data){
				$lpr=$get_data['lpr'];
			}else{
				$lpr='';
			}

			if($lpr!=''){
                $this->load->model('api/Master_db_model','master_db');
                $sel_cario_like=$this->master_db->sel_cario_like($lpr);

                if(count($sel_cario_like)>0){
					$i=0;
					foreach($sel_cario_like as $key0 => $value0){
						$lpr0=$value0['obj_id'];
						$chk_carioseat=$this->master_db->chk_carioseat($lpr0);
						$sel_carioseat=$this->master_db->sel_carioseat($lpr0);
						
						$in_date = new DateTime($sel_carioseat[0]['in_time']);
						$in_date= $in_date->format('Ymd');
						$in_pic_name = "http://192.168.10.201/carpic/".$in_date."/".$sel_carioseat[0]['in_pic_name'];
						
						$station_no=$sel_carioseat[0]['station_no'];
						
						$chk_in_time=isset($chk_carioseat[0]['in_time']) ? $chk_carioseat[0]['in_time']: "0000-00-00 00:00:00" ; //車輛最後出場之入場時間
						
						$in_time=$sel_carioseat[0]['in_time'];

						if($in_time>$chk_in_time){
							
							$end_time=$sel_carioseat[0]['station_no'];
	
							$Get_billing_fee= "http://altapi.altob.com.tw/fee_api/Get_billing_fee"; //檢查現場入場時間
	
							$start_time=$in_time;
							$end_time=date('Y-m-d H:i:s');
							
							$ch0 = curl_init();
	
							curl_setopt($ch0, CURLOPT_HEADER, 0);
							curl_setopt($ch0, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch0, CURLOPT_URL, $Get_billing_fee);
							curl_setopt($ch0, CURLOPT_POST, true);
							curl_setopt($ch0, CURLOPT_POSTFIELDS, http_build_query(array("station_no"=>$station_no, "start_time"=>$in_time, "end_time"=>$end_time)));
	
							$totalfee0 = curl_exec($ch0);
							
							curl_close($ch0);
	
							$totalfee=isset($totalfee0) ? $totalfee0:0 ;
	
							$dataarray[]=array(
								"lpr"			=> $lpr0,
								"in_time"		=> $in_time,
								"pay_time" 		=> $end_time,
								"fee"		 	=> $totalfee0,
								"in_pic_name" 	=> $in_pic_name,
								"success" 		=> true
								);
							$i++;
						}else{
							
						}
						
						if($i==0){
							$dataarray[]=array(
								"lpr"			=> $lpr,
								"in_time"		=> "0000-00-00 00:00:00",
								"pay_time" 		=> "0000-00-00 00:00:00",
								"fee"		 	=> 0,
								"in_pic_name" 	=> "",
								"success" 		=> false
								);
						}
							
					}
					
				}else{
					$dataarray[]=array(
						"lpr"			=> $lpr,
						"in_time"		=> "0000-00-00 00:00:00",
						"pay_time" 		=> "0000-00-00 00:00:00",
						"fee"		 	=> 0,
						"in_pic_name" 	=> "",
						"success" 		=> false
						);
                }

			}else{
				
				$dataarray[]=array(
					"lpr"			=> "",
					"in_time"		=> "0000-00-00 00:00:00",
					"pay_time" 		=> "0000-00-00 00:00:00",
					"fee"		 	=> 0,
					"in_pic_name" 	=> "",
					"success" 		=> false
					);
			}

			print_r(json_encode($dataarray,true));exit;
        }


}

