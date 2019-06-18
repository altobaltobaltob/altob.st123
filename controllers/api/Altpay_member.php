<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Altpay_member extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->library('session');
		$this->load->model('api/Altpay_model','Altpay');

		// ----- 回傳訊息 -----
		define('ALLPA_GO_RESULT_CODE_OK', 0);
		define('ALLPA_GO_RESULT_MSG_OK', "成功");		
		define('ALLPA_GO_RESULT_CODE_CK_ERROR', 10);
		define('ALLPA_GO_RESULT_MSG_CK_ERROR', "CK ERROR");
		define('ALLPA_GO_RESULT_CODE_USER_NOT_FOUND', 11);
		define('ALLPA_GO_RESULT_MSG_USER_NOT_FOUND', "查無歐Pa卡用戶");	
		define('local_ip', 'http://221.120.36.144');	
		// ----- 回傳訊息 (END) -----
    }

	public function index() {		
		$station_info = array();
		$station_info = $this->Altpay->sel_info();
		$result = $station_info;
		$key = md5("Alt@b80682490".$station_info[0]['station_no']);
		$acarps_ip="altapi.altob.com.tw";		
		$this->get_member_data($acarps_ip,$key);			
	}

	//call總公司API取得歐Pa卡會員資料
	public function get_member_data($acarps_ip,$key){
		$apijson= "http://".$acarps_ip."/alp_pay_api/Altpay_members_api/index/".$key;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $apijson);		
		$output = curl_exec($ch);
		if (curl_errno($ch)) {
        	print "Error: " . curl_error($ch);
        } else {
			curl_close($ch);
			$this->save_member_json($output);
			print_r(json_encode($output));
      	}				
	}

	public function save_member_json($data){
    	$myfile = fopen("/home/data/json/altpay_member.json", "w") or die("Unable to open file!");
		fwrite($myfile, $data);
		fclose($myfile);
	}

	public function read_member_json(){
    	$myfile = fopen("/home/data/json/altpay_member.json", "r") or die("Unable to open file!");	
		$result = array();	
		$result = fread($myfile, filesize("/home/data/json/altpay_member.json"));		
		fclose($myfile);
		return $result;
	}

	// 歐Pa卡 - 判斷有效用戶 (限制存取)
	public function get_allpa_valid_user($lpr,$check_mac){
		$data = json_decode($this->read_member_json(),true);			
		$result = array();
		$flag = 0;				
		for($i=0;$i<count($data);$i++){
			if($data[$i]["car_id"]==$lpr){
				if(empty($check_mac) || (md5($data[$i]["car_id"]) != $check_mac)){
					// check mac fail					
					$result["result_code"] = ALLPA_GO_RESULT_CODE_CK_ERROR;
					$result["result_msg"] = ALLPA_GO_RESULT_MSG_CK_ERROR;
					//print_r("1|".$data[$i]["car_id"]."|".$check_mac."|".$flag."|".$result["result_msg"]);
					return $result; // CK ERROR
				} else if(empty($data)){					
					$result["result_code"] = ALLPA_GO_RESULT_CODE_USER_NOT_FOUND;
					$result["result_msg"] = ALLPA_GO_RESULT_MSG_USER_NOT_FOUND;
					//print_r("2|".$data[$i]["car_id"]."|".$check_mac."|".$flag."|".$result["result_msg"]);
					return $result; // USER NOT FOUND
				} else {
					$flag = 1;
					$result["result_code"] = ALLPA_GO_RESULT_CODE_OK;
					$result["result_msg"] = ALLPA_GO_RESULT_MSG_OK;
					$result["lpr"] = $data[$i]["car_id"];
					//$result["barcode"] = $user["barcode"];
					$result["balance"] = $data[$i]["balance"];
					//$result["bonus"] = $user["bonus"];
					//print_r("3|".$data[$i]["car_id"]."|".$check_mac."|".$flag."|".$result["result_msg"]);
					return $result;
				}
			}
		}
		if($flag==0){			
			$result["result_code"] = ALLPA_GO_RESULT_CODE_USER_NOT_FOUND;
			$result["result_msg"] = ALLPA_GO_RESULT_MSG_USER_NOT_FOUND;
			print_r("4|".$lpr."|".$check_mac."|".$flag."|".$result["result_msg"]);
			return $result; // USER NOT FOUND
		}		
		//print_r($lpr."|".$check_mac."|".$flag);				
	}

	// 遠端歐PA卡流程
	public function allpa_go_remote($in_time,$lpr,$station_no){
		/*
		$in_time = $this->uri->segment(3); 		// 進場時間
        $lpr = $this->uri->segment(4); 			// 車牌號碼
        $station_no = $this->uri->segment(5);	// 場站編號
		$check_mac = $this->uri->segment(6);	// 驗証欄位
		*/
		// 驗証欄位
		/*
		if($check_mac != md5($in_time. $lpr . $station_no)){
			echo 'ck_error';
			exit;
		}
		*/
		// 先檢查本地端是否為歐PA會員
		$valid_user_ck = md5($lpr);
		$valid_user_result = $this->get_allpa_valid_user($lpr, $valid_user_ck); // check user
		if(!isset($valid_user_result['result_code']) || $valid_user_result['result_code'] != 0){
			echo json_encode($valid_user_result, JSON_UNESCAPED_UNICODE);
		} else {
			$res = $this->get_total_fee($lpr);			
			$tmp = explode('|',$res);
			$total_fee = $tmp[0];
			$station_no = $tmp[1];
			$cario_no = $tmp[2];
			$in_time = $tmp[3];
			$in_lane = $tmp[4];
			$out_lane = $tmp[5];
			$pay_time = strtotime(date('Y-m-d H:i:s'));	// 結帳時間
			$data=array(
				"station_no"=>$station_no,
				"cario_no"=>$cario_no,
				"in_time"=>$in_time,
				"out_time"=>'',
				"in_lane"=>$in_lane,
				"out_lane"=>$out_lane,
				"pay_time"=>$pay_time,
				"fee"=>$total_fee,
				"pre_status_code"=>'01',
				"pre_balance"=>$valid_user_result['balance'],
				"pre_bonus"=>'',
				"status_code"=>'01',
				"balance"=>$valid_user_result['balance']-$total_fee,
				"bonus"=>'',
				"in_pic_name"=>'',
				"in_pic_down"=>0,
				"out_pic_name"=>'',
				"out_pic_down"=>0,
				"create_time"=>strtotime(date('Y-m-d H:i:s')),
				"update_time"=>strtotime(date('Y-m-d H:i:s'))
			);
			print_r($data);
		}
	}

	public function get_total_fee($lpr){
		$this->load->model('api/Master_db_model','master_db');
        $sel_cario=$this->master_db->sel_cario($lpr);
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
			return $totalfee.'|'.$sel_cario[0]['station_no'].'|'.$sel_cario[0]['cario_no'].'|'.$sel_cario[0]['in_time'].'|'.$sel_cario[0]['in_lane'].'|'.$sel_cario[0]['out_lane'];
		}	
	}

	public function test(){
		//$lpr = "ABC123";
		$lpr = "AQB2211";
		$ck = md5($lpr);
		$this->allpa_go_remote("2018-06-27 21:20:20",$lpr,"40671");exit;
		//print_r($lpr."|".$ck."|");

		ob_end_clean();
		ignore_user_abort();
		ob_start();
		$data = $this->get_allpa_valid_user($lpr, $ck); // check user
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		header('Connection: close');
		header('Content-Length: ' . ob_get_length()); 
		ob_end_flush();
		flush();   
		/*
		$jdata = file_get_contents("http://localhost/api.html/Altpay_member/get_allpa_valid_user/{$lpr}/{$ck}");
		$results = json_decode($jdata, true);
		print_r($results);
		*/
	}
}
?>

