<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class License extends CI_Controller {

	// called by client
	function __construct() {
			parent::__construct();
			$this->load->model('UserModel');
			$this->load->model('LicenseModel');
			$this->load->model('TokenModel');
	}

	public function test(){
		echo "-license-";
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function initialize(){
		
		$uuid 			= $this->input->post('uuid');
		$serial_number 	= $this->input->post('serial_number');
		$email 			= $this->input->post('email');
		$whatsapp 		= $this->input->post('whatsapp');
		
		$respond = $this->LicenseModel->add($uuid, $serial_number, $email, $whatsapp);
		echo json_encode($respond);
		
	}
	
	
	
	// this will be called by admin (root)
	public function generateNew(){
		
		$token = $this->input->post('token');
		
		$respond = $this->TokenModel->verify($token);
		
		if($respond['status'] == 'valid'){
			$email 			= $this->input->post('email');
			$whatsapp 		= $this->input->post('whatsapp');

			$serial_number = $this->TokenModel->generateCode(3) . "-" . $this->TokenModel->generateCode(6);
		
			$respond = $this->LicenseModel->update($serial_number, $email, $whatsapp);
			
			if(isset($email)){
				$this->EmailModel->success_purchase_serial($lang, $fullname, $serial_number, $purchase_date, $email);
			}
			
		}
		
		echo json_encode($respond);
		
	}
	
	// this will be called by remote
	public function input(){
		
		$uuid 			= $this->input->post('uuid');
		$serial_number 	= $this->input->post('serial_number');
		
		$respond = $this->LicenseModel->validate($uuid, $serial_number);
		
		echo json_encode($respond);
	}
	
	public function read(){
		
		// if this person has a license previously
		// he will got the complete detail
		// otherwise invalid -> if error
		
		$uuid = $this->input->post('uuid');
		
		$respond =	$this->LicenseModel->get($uuid);
		
		echo json_encode($respond);
		
	}
}
