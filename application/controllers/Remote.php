<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Remote extends CI_Controller {

	// called by client
	function __construct() {
			parent::__construct();
			$this->load->model('UserModel');
			$this->load->model('TrackerModel');
			$this->load->model('CommandModel');
	}

	public function test(){
		echo "-remote-";
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function initialize(){
		
		$uuid 			= $this->input->post('uuid');
		$ip_address 	= $this->input->post('ip_address');
		$membership 	= $this->input->post('membership');
		$code 			= $this->input->post('code');
		
		$respond = $this->UserModel->add($uuid, $membership, $code, $ip_address);
		echo json_encode($respond);
		
	}
	
	public function registerClient(){
		
		$uuid 		= $this->input->post('uuid');
		$membership = $this->input->post('membership');
		$code 		= $this->input->post('code');
		$ip_address = $this->input->post('ip_address');
		
		$respond = $this->UserModel->add($uuid, $membership, $code, $ip_address);
		
		if($respond['status'] == 'valid'){
			
		}
		
		echo json_encode($respond);
	}
	
	public function read(){
		
		$uuid = $this->input->post('uuid');
		
		$respond =	$this->CommandModel->get($uuid);
		
		echo json_encode($respond);
		
	}
}
