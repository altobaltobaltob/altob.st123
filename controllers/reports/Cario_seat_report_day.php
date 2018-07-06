<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cario_seat_report_day extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->library('session');
		$this->load->model('reports/Cario_seat_model','cario_seat');

    }



	public function index()
	{
		
		$post_data= $this->input->post(NULL, TRUE);
		$get_data= $this->input->get(NULL, TRUE);
		$sin_time="";
		$ein_time="";
		$sin_time0="";
		$ein_time0="";
		$sin_time1="";
		$ein_time1="";
		$sin_tim2e="";
		$in_lane_seat_tot=0;
		$in_lane_seat_avg=array();
		
		if(isset($post_data['sin_time'])){
			$sin_time0= isset($post_data['sin_time']) ? $post_data['sin_time']:'0000-00-00'; 
			$ein_time0= isset($post_data['ein_time']) ? $post_data['ein_time']:'0000-00-00'; 
		}elseif(isset($get_data['sin_time'])){
			$sin_time0= isset($get_data['sin_time']) ? $get_data['sin_time']:'0000-00-00'; 
			$ein_time0= isset($get_data['ein_time']) ? $get_data['ein_time']:'0000-00-00'; 
		}

			$sin_time1= strtotime($sin_time0); 
			$ein_time1= strtotime($ein_time0); 
			
		for($i=0; $i<=(($ein_time1-$sin_time1)/86400); $i++){
			$sin_time= date('Y-m-d H:i:s',$sin_time1+(86400*$i)); 
			$sin_tim2e= date('Y-m-d',$sin_time1+(86400*$i)); 
			$ein_time= date('Y-m-d H:i:s',$sin_time1+(86400*$i)+86400); 
			
			$sel_cario_seat=$this->cario_seat->sel_cario_seat($sin_time,$ein_time);
			$in_lane_seat_tot=0;
			foreach($sel_cario_seat as $key0 => $val0){
				$in_lane_datetime=strtotime($val0['in_lane_datetime']);
				$in_seat_datetime=strtotime($val0['in_seat_datetime']);
				$in_lane_seat=($in_seat_datetime-$in_lane_datetime)/60;
				$in_lane_seat_tot=$in_lane_seat_tot + $in_lane_seat;
			}
			if(count($sel_cario_seat) > 0 ){
				$in_lane_seat_avg[]=array(
							"in_date"	=>	$sin_tim2e,
							"countlpr"	=>	count($sel_cario_seat),
							"tot"		=>	floor($in_lane_seat_tot),
							"avg"		=>	floor($in_lane_seat_tot/count($sel_cario_seat))
				);
			}
		}

		$this->front = new stdClass();

		$this->front->sin_time = $sin_time0;
		$this->front->ein_time = $ein_time0;
		$this->front->in_lane_seat_avg=$in_lane_seat_avg;

		$data=$this->front;
        $this->load->view('reports/cario_seat_report_day',$data);              
		
		
	}
	
	
	
	
}
