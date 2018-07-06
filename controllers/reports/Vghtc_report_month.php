<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vghtc_report_month extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('reports/Erpapidb_model','erpapidb');

    }



	public function index()
	{
		
		$post_data= $this->input->post(NULL, TRUE);
		$get_data= $this->input->get(NULL, TRUE);

		if($post_data){
			$month= isset($post_data['month']) ? $post_data['month']:''; 
		}
		if($get_data){
			$month= isset($get_data['month']) ? $get_data['month']:''; 
		}
		
		$dd=date('d',mktime(0,0,0,($month+1),0,date('Y')));
		
		$sel_in_lane=$this->erpapidb->sel_in_lane();

				if($month<10){
					$month0="0".$month;
				}else{
					$month0=$month;
				}
		
		
		for($i=1; $i<=$dd; $i++){
			$monthba=0;
			$dateba=0;
			$hourba=0;
			$alltatle=0;
				$dateba_lane_0=0;
				$dateba_lane_1=0;
				$dateba_lane_2=0;
				$dateba_lane_3=0;
				$dateba_lane_4=0;
				$dateba_lane_5=0;
				$dateba_lane_6=0;
				$dateba_lane_7=0;
				$dateba_lane_8=0;
				$dateba_lane_9=0;
				$dateba_lane_10=0;
				$dateba_lane_11=0;
				$dateba_lane_12=0;
				$dateba_lane_tatol000=0;
				if($i<10){
					$i0="0".$i;
				}else{
					$i0=$i;
				}
				$dateba_lane00[]=array();
				$start0=date('Y-'.$month0.'-'.$i0);
			for($j=-8; $j<=23-8; $j++){
				$stime0=date('H:i:s',($j*3600));
				$etime0=date('H:i:s',(($j+1)*3600));
				$stime=date('H:i',($j*3600));
				$etime=date('H:i',(($j+1)*3600));
				$start=date('Y-'.$month.'-'.$i.' '.$stime0);
				$end=date('Y-'.$month.'-'.$i.' '.$etime0);
				$sel_cario[]=array();
				$dateba0=0;
				$dateba_lane_tatol=0;

				foreach($sel_in_lane as $key => $value){
					$in_lane=$value['in_lane'];
					$name=$value['name'];
					$note=$value['note'];
					
					//if($in_lane==2 or $in_lane==3 or $in_lane==8 or $in_lane==9 or $in_lane==10 or $in_lane==11){
					if($in_lane==0 or $in_lane==1){
						$sel_cario=$this->erpapidb->sel_cario($in_lane,$start,$end);
					}else{
						$sel_cario=$this->erpapidb->sel_cario_out($in_lane,$start,$end);
					}
					
					$sel_cario_count[$key]=count($sel_cario);
					$alltatle=$alltatle+$sel_cario_count[$key];
					$dateba0=$dateba0+$sel_cario_count[$key];
					$dateba_lane_tatol=$dateba_lane_tatol+$sel_cario_count[$key];
					if($in_lane==0){
						$dateba_lane_0=$dateba_lane_0+$sel_cario_count[$key];
					}elseif($in_lane==1){
						$dateba_lane_1=$dateba_lane_1+$sel_cario_count[$key];
					}elseif($in_lane==2){
						$dateba_lane_2=$dateba_lane_2+$sel_cario_count[$key];
					}elseif($in_lane==3){
						$dateba_lane_3=$dateba_lane_3+$sel_cario_count[$key];
					}elseif($in_lane==4){
						$dateba_lane_4=$dateba_lane_4+$sel_cario_count[$key];
					}elseif($in_lane==5){
						$dateba_lane_5=$dateba_lane_5+$sel_cario_count[$key];
					}elseif($in_lane==6){
						$dateba_lane_6=$dateba_lane_6+$sel_cario_count[$key];
					}elseif($in_lane==7){
						$dateba_lane_7=$dateba_lane_7+$sel_cario_count[$key];
					}elseif($in_lane==8){
						$dateba_lane_8=$dateba_lane_8+$sel_cario_count[$key];
					}elseif($in_lane==9){
						$dateba_lane_9=$dateba_lane_9+$sel_cario_count[$key];
					}elseif($in_lane==10){
						$dateba_lane_10=$dateba_lane_10+$sel_cario_count[$key];
					}elseif($in_lane==11){
						$dateba_lane_11=$dateba_lane_11+$sel_cario_count[$key];
					}elseif($in_lane==12){
						$dateba_lane_12=$dateba_lane_12+$sel_cario_count[$key];
					}
				}

				$sel_cario_tatal[$stime]=array(
						"stime"		=>	$stime,
						"time"		=>	$stime.'~'.$etime,
						"dateba0"		=>	$dateba0,
						"sel_cario"	=>	$sel_cario_count
				);
				
				
			}
				$dateba_lane00=array(
						"dateba_lane_0"		=>	$dateba_lane_0,
						"dateba_lane_1"		=>	$dateba_lane_1,
						"dateba_lane_2"		=>	$dateba_lane_2,
						"dateba_lane_3"		=>	$dateba_lane_3,
					/*	"dateba_lane_4"		=>	$dateba_lane_4,
						"dateba_lane_5"		=>	$dateba_lane_5,
						"dateba_lane_6"		=>	$dateba_lane_6,
						"dateba_lane_8"		=>	$dateba_lane_8,
						"dateba_lane_9"		=>	$dateba_lane_9,
						"dateba_lane_10"	=>	$dateba_lane_10,
						"dateba_lane_11"	=>	$dateba_lane_11,
						"dateba_lane_12"	=>	$dateba_lane_12,*/
				);
				//		"sel_cario_tatal"	=>	$sel_cario_tatal
				//		"dateba"			=>	$alltatle,
					//	"hourba"			=>	$alltatle/24
			$this->front->sel_cario_tatal[$start0]=$sel_cario_tatal;
			$this->front->dateba_lane[$start0]=$dateba_lane00;
			$this->front->sel_in_lane[$start0]=$sel_in_lane;
			
			$dateba=$dateba+$dateba_lane_tatol;
			$dateba_lane[]=array_sum($dateba_lane00);
			$dateba_lane_tot_0[]=$dateba_lane_0;
			$dateba_lane_tot_1[]=$dateba_lane_1;
			$dateba_lane_tot_2[]=$dateba_lane_2;
			$dateba_lane_tot_3[]=$dateba_lane_3;
			//$dateba_lane_tot_4[]=$dateba_lane_4;
			//$dateba_lane_tot_5[]=$dateba_lane_5;
			//$dateba_lane_tot_6[]=$dateba_lane_6;
			//$dateba_lane_tot_8[]=$dateba_lane_8;
			//$dateba_lane_tot_9[]=$dateba_lane_9;
			//$dateba_lane_tot_10[]=$dateba_lane_10;
			//$dateba_lane_tot_11[]=$dateba_lane_11;
			//$dateba_lane_tot_12[]=$dateba_lane_12;
		}
		
		//$this->front->dateba_lane_tot=array(array_sum($dateba_lane_tot_0),array_sum($dateba_lane_tot_1),array_sum($dateba_lane_tot_2),array_sum($dateba_lane_tot_3),array_sum($dateba_lane_tot_4),array_sum($dateba_lane_tot_5),array_sum($dateba_lane_tot_6),array_sum($dateba_lane_tot_8),array_sum($dateba_lane_tot_9),array_sum($dateba_lane_tot_10),array_sum($dateba_lane_tot_11),array_sum($dateba_lane_tot_12));
		$this->front->dateba_lane_tot=array(array_sum($dateba_lane_tot_0),array_sum($dateba_lane_tot_1),array_sum($dateba_lane_tot_2),array_sum($dateba_lane_tot_3));
		$this->front->monthba=array_sum($dateba_lane);
		$this->front->dateba=array_sum($dateba_lane)/$dd;
		$this->front->hourba=array_sum($dateba_lane)/$dd/24;
		$this->front->month=$month;
		$this->front->dd=$dd;
		$data=$this->front;
        $this->load->view('reports/vghtc_report_month',$data);              
		
	}
	
	
	
	
}
