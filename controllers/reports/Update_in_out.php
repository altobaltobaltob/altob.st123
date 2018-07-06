<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Update_in_out extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('reports/Erpapidb_model','erpapidb');

    }



	public function index()
	{
		
		$post_data= $this->input->post(NULL, TRUE);
		$get_data= $this->input->get(NULL, TRUE);

		if($post_data){
			$lpr= isset($post_data['lpr']) ? $post_data['lpr']:''; 
		}
		if($get_data){
			$lpr= isset($get_data['lpr']) ? $get_data['lpr']:''; 
		}
		
		$sel_cario_lpr=$this->erpapidb->sel_cario_lpr($lpr);
		
		$this->front->sel_cario_lpr=$sel_cario_lpr;
		$data=$this->front;
        $this->load->view('reports/update_in_out',$data);              

	}
	
	public function save()
	{
		
		$post_data= $this->input->post(NULL, TRUE);
		$get_data= $this->input->get(NULL, TRUE);

		if($post_data){
			$cario_no= isset($post_data['cario_no']) ? $post_data['cario_no']:''; 
			$obj_id= isset($post_data['obj_id']) ? $post_data['obj_id']:''; 
			$in_out= isset($post_data['in_out']) ? $post_data['in_out']:''; 
		}
		if($get_data){
			$cario_no= isset($get_data['cario_no']) ? $get_data['cario_no']:''; 
			$obj_id= isset($get_data['obj_id']) ? $get_data['obj_id']:''; 
			$in_out= isset($get_data['in_out']) ? $get_data['in_out']:''; 
		}
		
		$data=array("in_out"=>$in_out);
		$this->erpapidb->save_cario_lpr($cario_no, $data);
		
		header("Location:./");
		exit();

	}
	
}

