<?php
/*
file: cars.php	宏奇 API 車輛進出場處理
*/
class Alt_acarps extends CI_Controller
{          
	function __construct() 
	{                            
		parent::__construct();
	}

	public function index() //出場車辨 送 API 至宏奇
	{
		$post_data= $this->input->get(NULL, TRUE);
		$lpr="ALT6222";//$post_data["lpr"];LT6222
//print_r($lpr);exit;
                $this->load->model('api/Master_db_model','master_db');
                $sel_cario=$this->master_db->sel_cario($lpr);
//print_r($sel_cario);exit;
                if(count($sel_cario)>0){

                        $cario_no=$sel_cario[0]['cario_no'];            //入場流水號
                        $station_no=$sel_cario[0]['station_no'];        //場站代碼
                        $member_no=$sel_cario[0]['member_no'];          //會員代碼
                        $obj_id=$sel_cario[0]['obj_id'];                        //會員代碼
                        $in_out=$sel_cario[0]['in_out'];

                        $in_time=$sel_cario[0]['in_time']; //入場時間

                        $Get_billing_fee= "http://altapi.altob.com.tw/fee_api/Get_billing_fee"; //檢查現場入場時間

                        $start_time=$in_time;
                        $end_time=date('Y-m-d H:i:s');
                        $ch0 = curl_init();

                        curl_setopt($ch0, CURLOPT_HEADER, 0);
                        curl_setopt($ch0, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch0, CURLOPT_URL, $Get_billing_fee);
                        curl_setopt($ch0, CURLOPT_POST, true);
                        curl_setopt($ch0, CURLOPT_POSTFIELDS, http_build_query(array("station_no"=>$station_no, "start_time"=>$start_time, "end_time"=>$end_time)));

                        $totalfee0 = curl_exec($ch0);
                        curl_close($ch0);

                        $totalfee=isset($totalfee0) ? $totalfee0:0 ;
                        $acarps_ip="192.168.10.82";
                        $acarps_port=8081;
                        $apijson= "http://".$acarps_ip.":".$acarps_port."/lprpayout"; //送宏奇API
                        
						$i=1;
                        $jsonarray=array("lpr"=>$obj_id, "start_time"=>$start_time, "end_time"=>$end_time, "totalfee"=>$totalfee);
                        $json=json_encode($jsonarray,true);
//print_r($json);
                        while(true){

                                $ch = curl_init();

                                curl_setopt($ch, CURLOPT_HEADER, 0);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_URL, $apijson);
                                curl_setopt($ch, CURLOPT_POST, true);
                                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Content-Length: ' . strlen($json)));

                                $output = curl_exec($ch);

//print_r($output);exit;

                                if($i==10 or $output=="OK"){
                                	if($output=="OK"){
										$data=date('Y-m-d H:i:s')." Alt_acarps OK ".$obj_id."\n";
										$this->save_setting($data);
									}
                                        break;
                                }
                                $i++;
                        }
                }


	}

	
	public function save_setting($data){
    	$myfile = fopen("/home/data/alt_acarps_log.json", "a+") or die("Unable to open file!");
		fwrite($myfile, $data);
		fclose($myfile);
	}

}
