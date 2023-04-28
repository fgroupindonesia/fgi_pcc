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
		
	
		$uuid 		= $this->input->post('uuid');
		$membership = $this->input->post('membership');
		$code 		= $this->input->post('code');
		$ip_address = $this->input->post('ip_address');
		
		
		$dataIP = $this->IPModel->getMyIP('testing');
		
		$city = $this->IPModel->getMyCity();
		
		$status_device = 1;
		$status_user = "active";
		
		$loc = $this->IPModel->getMyLocation();
		$location_lat = explode("," , $loc)[0];
		$location_long = explode("," , $loc)[1];
		
		$country = $this->IPModel->getMyCountry();
		
		$fullname = "";
		$wa 	= "";
		$email  = "";
		
		$respond  = $this->UserModel->add($uuid, $membership, $code, $ip_address, $country, $fullname, $wa, $email, $status_user, null);
		
		// continue to post the tracking position
		$respond = $this->TrackerModel->add($uuid, $location_lat, $location_long, $city, $status_device);
		
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
		
		// we need complete one or not?
		// 1 = COMPLETE -> default
		// 0 = SIMPLE
		$mode = $this->input->post('mode');
		
		// only get the 0 applied status
		$applied = 0;
 		$respond =	$this->CommandModel->get($uuid, $applied, $mode);
		
		
		
		// THIS IS COMPLETE {"status":"valid","multi_data":{"id":"5","client_uuid":"4B435451-394A-3043-4631-14DAE9AD8243","remote_uuid":"2222","command":{"restart":"true"},"applied":"0","date_created":"2023-04-20 10:59:29","date_modified":"2023-04-20 11:08:25"}}

		// THIS IS SIMPLE
		// {"status":"valid","multi_data":"command":{"restart":"true"}}

		
		// we show the client for the message
		echo json_encode($respond);
		
		// and continue 
		// if($respond['status'] == 'valid'){
		// we assume this client executing the command properly
		//	$id = $respond['multi_data']['id'];
		//	$respond =	$this->CommandModel->executed($id, $uuid);
		// }
		
	}
	
	public function updateCommand(){
		
		$uuid = $this->input->post('uuid');
		$id = $this->input->post('id');
		
			// we assume this client executing the command properly
			// so the applied will become 1 inside model
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
