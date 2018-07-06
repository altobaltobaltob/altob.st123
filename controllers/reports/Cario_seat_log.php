<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cario_seat_log extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('reports/Cario_seat_model','cario_seat');

    }



	public function cario_seat_log_all()
	{
		
		$get_data= $this->input->get(NULL, TRUE);
		$sec=0;
		if(isset($post_data['sin_time'])){
			$sin_time0= isset($post_data['sin_time']) ? $post_data['sin_time']:'0000-00-00'; 
			$ein_time0= isset($post_data['ein_time']) ? $post_data['ein_time']:'0000-00-00'; 
			$sec= isset($post_data['sec']) ? $post_data['sec']:'0000-00-00'; 
		}elseif(isset($get_data['sin_time'])){
			$sin_time0= isset($get_data['sin_time']) ? $get_data['sin_time']:'0000-00-00'; 
			$ein_time0= isset($get_data['ein_time']) ? $get_data['ein_time']:'0000-00-00'; 
			$sec= isset($get_data['sec']) ? $get_data['sec']:'0000-00-00'; 
		}

			$sin_time= $sin_time0.' 00:00:00'; 
			$ein_time= $ein_time0.' 00:00:00'; 

		$sel_cario_all=$this->cario_seat->sel_cario($sin_time,$ein_time);
		
		//print_r($sel_cario_all.' '.$ein_time);exit;
		foreach($sel_cario_all as $key0 => $value0){
			
			$cario_no=$value0['cario_no'];
			$station_no=$value0['station_no'];
			$lpr=$value0['lpr'];
			$in_lane=$value0['in_lane'];
			$in_lane_datetime=$value0['in_lane_datetime'];
			//$in_seat=$value0['in_seat'];
			//$in_seat_datetime=$value0['in_seat_datetime'];
			//$out_seat_datetime=$value0['out_seat_datetime'];
			$out_lane=$value0['out_lane'];
			$out_lane_datetime=$value0['out_lane_datetime'];

			if($in_lane_datetime>$out_lane_datetime){
				$status=1;
			}else{
				$status=4;
			}
				if((date('s')%10)==1){
					$sss=390;
				}elseif((date('s')%10)==2){
					$sss=275;
				}elseif((date('s')%10)==3){
					$sss=778;
				}elseif((date('s')%10)==4){
					$sss=713;
				}elseif((date('s')%10)==5){
					$sss=633;
				}elseif((date('s')%10)==6){
					$sss=487;
				}elseif((date('s')%10)==7){
					$sss=470;
				}elseif((date('s')%10)==9){
					$sss=404;
				}else{
					$sss=342;
				}
				$in_seat_strtotime=strtotime($in_lane_datetime)+$sss+$sec;
				
				if((date('s')%10)==1){
					$osss=125;
				}elseif((date('s')%10)==2){
					$osss=222;
				}elseif((date('s')%10)==3){
					$osss=321;
				}elseif((date('s')%10)==4){
					$osss=335;
				}elseif((date('s')%10)==5){
					$osss=111;
				}elseif((date('s')%10)==6){
					$osss=99;
				}elseif((date('s')%10)==7){
					$osss=210;
				}elseif((date('s')%10)==9){
					$osss=284;
				}else{
					$osss=165;
				}
				$out_lane_strtotime=strtotime($out_lane_datetime)-$osss;
				
			//print_r($data);exit;
			$sel_cario_seat_log=$this->cario_seat->sel_cario_seat_log($cario_no);

			if(count($sel_cario_seat_log)==0 and $lpr!="NONE" and $status==4){
			$data=array(
				"cario_no"			=>	$cario_no,
				"station_no"		=>	$station_no,
				"lpr"				=>	$lpr,
				"in_lane"			=>	$in_lane,
				"in_lane_datetime"	=>	$in_lane_datetime,
				"in_seat"			=>	"",
				"in_seat_datetime"	=>	date('Y-m-d H:i:s',$in_seat_strtotime),
				"out_seat_datetime"	=>	date('Y-m-d H:i:s',$out_lane_strtotime),
				"out_lane"			=>	$out_lane,
				"out_lane_datetime"	=>	$out_lane_datetime,
				"status"			=>	$status
			);
			
				$this->cario_seat->insert_cario_seat_log($data);
			}else{
			$data=array(
				"in_seat_datetime"	=>	date('Y-m-d H:i:s',$in_seat_strtotime),
			);
			
				$this->cario_seat->update_cario_seat_log($cario_no,$data);
			}
			
		}
		
		
	}
	
	
	
	
}
