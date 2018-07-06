<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('/home/bigbang/libs/phplibs/phpMQTT.php');

class Update_pks_groups extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('reports/Update_pks_groups_model','update_pks_groups');


    }



	public function index()
	{
		
		$station_no="40701";
		$group_type="2";
		
		$sel_pks_groups=$this->update_pks_groups->sel_pks_groups($station_no,$group_type);
		
		foreach($sel_pks_groups as $key0 => $value0){
			$station_no0=$value0['station_no'];
			$fgroup_id=$value0['group_id'];
					
			$sel_pks_groups=$this->update_pks_groups->sel_pks_groups_group_id($station_no,$fgroup_id);
			$sel_pks_all=$this->update_pks_groups->sel_pks_all($station_no, $fgroup_id);
			$sel_pks=$this->update_pks_groups->sel_pks($station_no, $fgroup_id);
			
			$data[$fgroup_id]=array(count($sel_pks_all),count($sel_pks),count($sel_pks_all)-count($sel_pks));
			
			$data0=array("tot"=>count($sel_pks_all),"parked"=>count($sel_pks),"availables"=>count($sel_pks_all)-count($sel_pks));
			
			$this->update_pks_groups->update_pks_groups($data0, $fgroup_id);
		}
		
		//第一立體停車場
		$group_id1="GB";
		$gb_data=array('A01F','A02F','A03F','A04F','ARF');
		$data1=array();
		$data1tot0=0;
		$data1tot1=0;
		$data1tot2=0;
		foreach($gb_data as $key1=>$value1){
			
			$data1=$data[$value1];
			$data10=$data1[0];
			$data11=$data1[1];
			$data12=$data1[2];
			$data1tot0=$data1tot0+$data10;
			$data1tot1=$data1tot1+$data11;
			$data1tot2=$data1tot2+$data12;
			
		}
			$data19=array("tot"=>$data1tot0,"parked"=>$data1tot1,"availables"=>$data1tot2);
			$this->update_pks_groups->update_pks_groups($data19, $group_id1);
		
		//第二立體停車場
		$group_id2="GC";
		$gc_data=array('BB01','B01F','B02F','B03F','B04F','BRF');
		$data2=array();
		$data2tot0=0;
		$data2tot1=0;
		$data2tot2=0;
		foreach($gc_data as $key2=>$value2){
			
			$data2=$data[$value2];
			$data20=$data2[0];
			$data21=$data2[1];
			$data22=$data2[2];
			$data2tot0=$data2tot0+$data20;
			$data2tot1=$data2tot1+$data21;
			$data2tot2=$data2tot2+$data22;
			
		}
			$data29=array("tot"=>$data2tot0,"parked"=>$data2tot1,"availables"=>$data2tot2);
			$this->update_pks_groups->update_pks_groups($data29, $group_id2);

		
		//門診地下停車場
		$group_id3="GD";
		$gd_data=array('CB01','CB02','CB03');

		$data3=array();
		$data3tot0=0;
		$data3tot1=0;
		$data3tot2=0;
		foreach($gd_data as $key3=>$value3){
			
			$data3=$data[$value3];
			$data30=$data3[0];
			$data31=$data3[1];
			$data32=$data3[2];
			$data3tot0=$data3tot0+$data30;
			$data3tot1=$data3tot1+$data31;
			$data3tot2=$data3tot2+$data32;
			
		}
			$data39=array("tot"=>$data3tot0,"parked"=>$data3tot1,"availables"=>$data3tot2);
			$this->update_pks_groups->update_pks_groups($data39, $group_id3);
		

		//院內平面停車場
		$group_id4="GA";
		$gd_data=array('D01F');

		$data4=array();
		$data4tot0=0;
		$data4tot1=0;
		$data4tot2=0;
		foreach($gd_data as $key4=>$value4){
			
			$data4=$data[$value4];
			$data40=$data4[0];
			$data41=$data4[1];
			$data42=$data4[2];
			$data4tot0=$data4tot0+$data40;
			$data4tot1=$data4tot1+$data41;
			$data4tot2=$data4tot2+$data42;
			
		}
			$data49=array("tot"=>$data4tot0,"parked"=>$data4tot1,"availables"=>$data4tot2);
			$this->update_pks_groups->update_pks_groups($data49, $group_id4);
		
		
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
