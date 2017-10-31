<?php             
/*
file: Parkingquery_model.php 停車管理系統(提供資策會使用)
*/                   

class Parkingquery_model extends CI_Model 
{             
    
	function __construct()
	{
		parent::__construct(); 
		$this->load->database(); 
    }   
     
	public function init($vars)
	{
		// do nothing
    } 
    
    
    
    // 查詢各樓層剩餘車位 
    // http://203.75.167.89/parkingquery.html/check_space/12345
	public function check_space($seqno, $group_type=1) 
	{           
    	$data = array();         
    	$results = $this->db->select('group_id, availables, tot')
        		->from('pks_groups')
                ->where('group_type', $group_type)	
                ->get()  
                ->result_array();  
                         
        foreach($results as $idx => $rows)
        {
          	$data['result']['floor'][$idx] = array
            (
            	'floor_name' => $rows['group_id'], 
            	'valid_count' => $rows['availables'], 
            	'total_count' => $rows['tot'] 
            );
        }
        return $data; 
    }   
    
    // 停車位置查詢(板橋好停車) 
    // http://203.75.167.89/parkingquery.html/check_location/ABC1234
	public function check_location($lpr) 
	{                 
    	$lpr = strtoupper($lpr);	// 一律轉大寫
    	$data = array();         
    	$rows = $this->db->select('pksno, pic_name')
        		->from('pks')
                ->where('lpr', $lpr)	
        		->limit(1)
                ->get()  
                ->row_array();  
        if (!empty($rows['pksno']))
        {
        	$data['result']['num'] = $lpr;
        	$data['result']['location_no'] = "{$rows['pksno']}";     
        	$data['result_code'] = 'OK';  
        	$data['result']['pic_name'] = $rows['pic_name'];
        }    
        else	// 查無資料, 啟用模糊比對
        {     
			// 讀取最近一筆入場資料
        	$rows_cario = $this->db
							->select('cario_no')
        					->from('cario')
                			->where(array('in_out' => 'CI', 'obj_id' => $lpr, 'finished' => 0, 'err' => 0, 'out_time IS NULL' => null))
                  			->order_by('cario_no', 'desc')
                  			->limit(1)
                			->get()
                			->row_array();
							
			// 有入場記錄, 直接猜在頂樓
			if (!empty($rows_cario['cario_no']))
            {
				$data['result']['num'] = $lpr;
				$data['result']['location_no'] = "7000";     
				$data['result_code'] = 'OK';  
			}
			else
			{
				$data['result']['num'] = $lpr;
				$data['result']['location_no'] = '0';
				$data['result_code'] = 'FAIL';
			}  
        }      
        return $data; 
    }          
    
	/*
    // 空車位導引
    // http://203.75.167.89/parkingquery.html/get_valid_seat 
    // 註記現在時間, 並保留10分鐘
	public function get_valid_seat($pksno)
	{           
    	$data = array();   
        $this->db->trans_start(); 
        if ($pksno > 0)	// 限制從某一個車位開始指派車位
        {   
        	$sql = "select pksno from pks where status = 'VA' and pksno >= {$pksno} and prioritys != 0 and (book_time is null or book_time <= now()) order by prioritys asc limit 1 for update;"; 
        }   
        else
        {
        	$sql = "select pksno from pks where status = 'VA' and prioritys != 0 and (book_time is null or book_time <= now()) order by prioritys asc limit 1 for update;"; 
        }
        
        $rows = $this->db->query($sql)->row_array(); 
        if (!empty($rows['pksno']))
        {
        	$data['result']['location_no'] = "{$rows['pksno']}";
        	$data['result_code'] = 'OK';  
            $sql = "update pks set book_time = addtime(now(), '00:10:00') where pksno = {$rows['pksno']};";
            $this->db->query($sql);
        }      
        else   
        {
        	$data['result']['location_no'] = '0';
        	$data['result_code'] = 'FAIL';
        }      
        $this->db->trans_complete(); 
        return $data; 
    }  
	*/
	
	// 空車位導引
    // http://203.75.167.89/parkingquery.html/get_valid_seat 
    // 註記現在時間, 並保留10分鐘
	public function get_valid_seat($pksno, $group_type=1)
	{           
    	$data = array();   
        $this->db->trans_start(); 
        if ($pksno > 0)	// 限制從某一個車位開始指派車位
        {   
        	//$sql = "select pksno from pks where status = 'VA' and pksno >= {$pksno} and prioritys != 0 and (book_time is null or book_time <= now()) order by prioritys asc limit 1 for update;"; 
			
			// 2017/04/12 調整為支援找最近
			//$sql_xy = "select posx, posy from pks where pksno = {$pksno}";
			$sql_xy = "
						select pks.posx, pks.posy, RIGHT(pks_group_member.group_id, 1 ) as group_idx
							from pks
							left join pks_group_member on (pks_group_member.pksno = pks.pksno)
							left join pks_groups on (pks_groups.group_id = pks_group_member.group_id)
						where pks.pksno = {$pksno} AND pks_groups.group_type = {$group_type}
						";
			
			$rows_xy = $this->db->query($sql_xy)->row_array(); 
			if(!empty($rows_xy['posx']) && !empty($rows_xy['posy']))
			{
				// 找最近
				$sql = "
						select pks.pksno, pks.posx, pks.posy, pks_group_member.group_id, 
							( 
								ABS(cast(pks.pksno as signed) - {$pksno}) +
								ABS(cast(pks.posx as signed) - {$rows_xy['posx']}) + 
								ABS(cast(pks.posy as signed) - {$rows_xy['posy']}) +
								ABS(RIGHT(pks_group_member.group_id, 1 ) - {$rows_xy['group_idx']}) * 1000
							) AS v
							from pks 
							left join pks_group_member on (pks_group_member.pksno = pks.pksno)
							left join pks_groups on (pks_groups.group_id = pks_group_member.group_id)
						where 
							pks.status = 'VA' and prioritys != 0 and (pks.book_time is null or pks.book_time <= now()) 
							and pks_groups.group_type = 1
						order by v asc limit 10 for update;
						";
				
				/*
				$sql = "select pksno, 
						( ABS(cast(pksno as signed) - {$pksno}) + ABS(cast(posx as signed) - {$rows_xy['posx']}) + ABS(cast(posy as signed) - {$rows_xy['posy']}) ) AS v
						from pks where status = 'VA' and prioritys != 0 and (book_time is null or book_time <= now()) 
						order by v asc limit 1 for update;"; 
				*/
			}
			else
			{
				// 依順序
				$sql = "select pksno from pks where status = 'VA' and pksno >= {$pksno} and prioritys != 0 and (book_time is null or book_time <= now()) order by prioritys asc limit 1 for update;"; 
			}
        }   
        else
        {
        	$sql = "select pksno from pks where status = 'VA' and prioritys != 0 and (book_time is null or book_time <= now()) order by prioritys asc limit 1 for update;"; 
        }
        
        $rows = $this->db->query($sql)->row_array(); 
        if (!empty($rows['pksno']))
        {
        	$data['result']['location_no'] = "{$rows['pksno']}";
        	$data['result_code'] = 'OK';  
            $sql = "update pks set book_time = addtime(now(), '00:10:00') where pksno = {$rows['pksno']};";
            $this->db->query($sql);
			
			trigger_error(__FUNCTION__ . "[{$pksno}]:" .  print_r($rows, true));
        }      
        else   
        {
        	$data['result']['location_no'] = '0';
        	$data['result_code'] = 'FAIL';
        }      
        $this->db->trans_complete(); 
        return $data; 
    } 
    
    
    // 緊急求救
    // http://203.75.167.89/parkingquery.html/send_sos/B2/111/123
	public function send_sos($floor, $x, $y)
	{           
    	$data = array
        (
        	'result' => array('send_from' => array('floor' => $floor, 'x' => $x, 'y' => $y)),
            'result_code' => 'OK'
        );  
        return $data; 
    }   
    
    
    // 防盜鎖車
    // http://203.75.167.89/parkingquery.html/security_action/ABC1234/pswd/2
	public function security_action($lpr, $pswd, $action)
	{                      
    	$data = array();    
        /*
    	$rows = $this->db->select('member_no, passwd, locked')
        		->from('members')
                ->where(array('lpr' => $lpr, 'passwd' => $pswd))	     
                ->limit(1)
                ->get()  
                ->row_array(); 
        trigger_error('防盜鎖車:'.$this->db->last_query());
                                                        
        // 無資料或密碼錯誤
        if (empty($rows['member_no']))
        {
          	$data['result_code'] = 'FAIL';
            return($data);
        } 
        */
          
    	$rows = $this->db->select('member_no, passwd, locked')
        		->from('members')
                ->where(array('lpr' => $lpr))	     
                ->limit(1)
                ->get()  
                ->row_array(); 
        trigger_error('防盜鎖車:'.$this->db->last_query());
                                                        
        // 無資料或密碼錯誤
        if (empty($rows['member_no']) || md5($rows['passwd']) != $pswd)
        {
          	$data['result_code'] = 'FAIL';
            return($data);
        }
        
        $data['result_code'] = 'OK';
    	// 查詢防盜狀態                 
    	if ($action == 2)
        {      
        	$data['result']['action'] = 'CHECK_SECURITY';
        	$data['result'][0]['num'] = $lpr;
        	$data['result'][0]['result'] = $rows['locked'] ? 'ON' : 'OFF';
            return $data;
        }     
                
        $this->db
        	->where('member_no', $rows['member_no'])
        	->update('members', array('locked' => $action)); 
        
    	$data['result']['action'] = $action == 1 ? 'ON' : 'OFF';  
        return $data; 
    } 
}
