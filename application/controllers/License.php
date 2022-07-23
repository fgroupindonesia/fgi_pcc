<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class License extends CI_Controller {

	// called by client
	function __construct() {
			parent::__construct();
			$this->load->model('UserModel');
			$this->load->model('LicenseModel');
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
	
	public function read(){
		
		// if this person has a license previously
		// he will got the complete detail
		// otherwise invalid -> if error
		
		$uuid = $this->input->post('uuid');
		
		$respond =	$this->LicenseModel->get($uuid);
		
		echo json_encode($respond);
		
	}
}
