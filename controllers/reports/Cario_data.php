<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cario_data extends CI_Controller {

    public function __construct() {
        parent::__construct();
		$this->load->model('reports/Erpapidb_model','erpapidb');

    }



	public function index()
	{
		
		$sel_cario_api=$this->erpapidb->sel_cario_api();

		print_r(json_encode($sel_cario_api)) ;		
		
		
	}
	
	
	
	
}
