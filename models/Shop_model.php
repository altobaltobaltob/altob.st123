<?php             
/*
file: Shop_model.php 購物
*/                   

class Shop_model extends CI_Model 
{             
	var $vars = array();

	function __construct()
	{
		parent::__construct(); 
		$this->load->database(); 
		
		// product code
		define('PRODUCT_CODE_COFFEE_SHOP', 	'coffee_shop');	// 咖啡產品包
		define('PRODUCT_CODE_COFFEE', 		'coffee');		// 咖啡
    }   
     
	public function init($vars)
	{
		$this->vars = $vars;
    } 
	
	// 取得產品資訊
	public function q_product($product_id=0, $product_code=PRODUCT_CODE_COFFEE_SHOP)
	{
		$now = date('Y/m/d H:i:s');
		$where_arr = array('start_time <= ' => $now, 'valid_time > ' => $now);
		$where_arr['product_id'] = $product_id;		// 指定產品流水號
		$where_arr['product_code'] = $product_code;	// 指定產品包
		
    	$data = array();
    	$result = $this->db->select('product_id, product_code, product_name, product_desc, amt, remarks, product_plan')
        		->from('products')
                ->where($where_arr)
				->limit(1)
                ->get()
                ->row_array();
        return $result;
    }
	
	// 產生交易序號
	private function gen_trx_no()
	{
		return time().rand(10000,99999);
	}
	
	// 建立產品訂單
	public function create_product_bill($product_id, $product_code)
	{
		// 取得商品資訊
		$product_info = $this->q_product($product_id, $product_code);
		
		if(!isset($product_info['product_plan']))
		{
			return 'unknown_product';	// 中斷
		}
		
		$data = array();
		$data['order_no'] = $this->gen_trx_no();
		$data['product_id'] = $product_info["product_id"];
		$data['product_code'] = $product_info["product_code"];
		$data['product_plan'] = $product_info["product_plan"];
		$data['invoice_remark'] = $product_info["product_name"];
		$data['amt'] = $product_info["amt"];
		$data['valid_time'] = date('Y-m-d H:i:s', strtotime(" + 15 minutes")); // 15 min 內有效
		$this->db->insert('product_bill', $data);
		
		$affect_rows = $this->db->affected_rows();

		if ($affect_rows <= 0)
			return 'fail';
		
		trigger_error(__FUNCTION__ . '..' . print_r($data, true));
		return $data;
	}
	
	// 處理產品訂單
	public function proceed_product_bill($parms, $tx_type=0)
	{
		$order_no = $parms['order_no'];
		$invoice_receiver = $parms['invoice_receiver'];
		$company_no = $parms['company_no'];
		$email = $parms['email'];
		$mobile = $parms['mobile'];
		
		$product_info = $this->db->select('valid_time, product_plan')
				->from('product_bill')
				->where(array('order_no' => $order_no))
				->limit(1)
				->get()
				->row_array();
				
		if(!isset($product_info['product_plan']))
		{
			trigger_error(__FUNCTION__ . "|{$order_no}|unknown_order");
			return 'unknown_order';			// 中斷
		}
		
		if(!isset($product_info['valid_time']))
		{
			trigger_error(__FUNCTION__ . "|{$order_no}|valid_time_not_found");
			return 'valid_time_not_found';	// 中斷
		}
		
		$data = array();
		$data['tx_type'] = $tx_type;		// 交易種類: 0:未定義, 1:現金, 40:博辰人工模組, 41:博辰自動繳費機, 50:歐付寶轉址刷卡, 51:歐付寶APP, 52:歐付寶轉址WebATM, 60:中國信託刷卡轉址
		
		if(strlen($company_no) >= 8)
		{
			$data['company_no'] = $company_no;														// 電子發票：公司統編
			$data['company_receiver'] = "公司名稱";													// 電子發票：公司名稱
			$data['company_address'] = "公司地址";													// 電子發票：公司地址
		}
		
		$data['invoice_receiver'] = (strlen($invoice_receiver) >= 7) ? $invoice_receiver : '';		// 電子發票：載具編號
		$data['email'] = (strlen($email) >= 5) ? $email : '';										// 電子發票：email
		$data['mobile'] = (strlen($mobile) >= 10) ? $mobile : '';									// 電子發票：手機
		
		// 交易時間
		$tx_time = time();
		$data['tx_time'] = date('Y/m/d H:i:s', $tx_time);
		
		if(strtotime($product_info['valid_time']) < $tx_time)
		{
			$data['status'] = 99; 								//狀態: 99:訂單逾期作廢
			$this->db->update('product_bill', $data, array('order_no' => $order_no));
			trigger_error(__FUNCTION__ . "|{$order_no}| 99 gg");
			return 'gg';
		}
		
		// 完成
		$data['status'] = 100; 									// 狀態: 100:交易進行中
		$this->db->update('product_bill', $data, array('order_no' => $order_no));
		
		$affect_rows = $this->db->affected_rows();

		if ($affect_rows <= 0)
			return 'fail';

		$data['order_no'] = $order_no;
		trigger_error(__FUNCTION__ . ".." . print_r($data, true));
		return $data;
    }
}
