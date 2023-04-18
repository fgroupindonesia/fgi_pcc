<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

	// called by client
	function __construct() {
			parent::__construct();
			$this->load->model('UserModel');
			$this->load->model('TrackerModel');
			$this->load->model('TokenModel');
			$this->load->model('CommandModel');
			$this->load->model('IPModel');
	}

	public function test(){
		echo "-client-";
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function myip(){
		$n = $this->IPModel->getMyIP();
		echo json_encode($n);
	}
	
	public function initialize(){
		
		// we got the posted string from client
		// such as uuid combined with date
		// thus we saved them into db
		//echo "Y";
		$uuid 		= $this->input->post('uuid');
		$membership = $this->input->post('membership');
		$code 		= $this->input->post('code');
		$ip_address = $this->input->post('ip_address');
		
		$respond = $this->UserModel->add($uuid, $membership, $code, $ip_address);
		echo json_encode($respond);
	}
	
	public function activation(){
		
		$token 	= $this->input->get('token');
		$endResult = "";
		
		if(isset($token)){
				$endResult = $this->TokenModel->getBy('token', $token);
				
				if(isset($endResult)){
					$data = $endResult;
					
					if($data['status'] == 'valid'){
					$uuid = $data['multi_data']['username'];
					$endResult = $this->UserModel->updateStatus($uuid, 'active');
					}
				}
				
		}
		
		echo json_encode($endResult);
		
	}
	
	public function read(){
		
		$uuid = $this->input->post('uuid');
		
		// only get the 0 applied status
		$applied = 0;
 		$respond =	$this->CommandModel->get($uuid, $applied);
		
		if($respond['status'] == 'valid'){
			// we assume this client executing the command properly
			$id = $respond['multi_data']['id'];
			$respond =	$this->CommandModel->executed($id, $uuid);
		}
		
		echo json_encode($respond);
		
	}
	
	public function updateCommand(){
		
		$uuid = $this->input->post('uuid');
		$id = $this->input->post('id');
		
			// we assume this client executing the command properly
			
			$respond =	$this->CommandModel->executed($id, $uuid);
		
		
		echo json_encode($respond);
		
	}
	
	public function updateTracker(){
		
		$uuid 	= $this->input->post('uuid');
		$id 	= $this->input->post('id');
		$city 	= $this->input->post('city');
		$location_long 	= $this->input->post('location_long');
		$location_lat 	= $this->input->post('location_lat');
		$status_device 	= $this->input->post('status_device');
		
		// we assume this client already has the data tracker previously
			
		$respond =	$this->TrackerModel->edit($id, $uuid, $location_lat, $location_long, $city, $status_device);
		
		echo json_encode($respond);
		
	}
	
}
