<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Remote extends CI_Controller {

	// called by client
	function __construct() {
			parent::__construct();
			$this->load->model('UserModel');
			$this->load->model('TrackerModel');
			$this->load->model('CommandModel');
			$this->load->model('IPModel');
			$this->load->model('EmailModel');
			$this->load->model('TokenModel');
	}

	public function test(){
		echo "-remoteXXss-";
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
	
	public function sendCommand(){
		
		$id 		= $this->input->post('id');
		$client_uuid 		= $this->input->post('uuid_client');
		$remote_uuid 		= $this->input->post('uuid_remote');
		
		$command 		= $this->input->post('command');
		
		// with status of 0 : pending
		$applied 		= '0';
		
		$respond = $this->CommandModel->edit($id, $client_uuid, $remote_uuid, $command, $applied);
	
		echo json_encode($respond);
		
	}
	
	public function getMyCountry(){
			$res = $this->IPModel->getMyIP();
			
			$id = "";
			
			if(isset($res)){
				$id =	$res['country'];
			}
			
			return $id;
	}
	
	public function getMyIP(){
			// switch from 'testing' to 'live'
			
			$res = $this->IPModel->getMyIP('testing');
			
			$ip = "";
			
			if(isset($res)){
				$id =	$res['ip'];
			}
			
			return $ip;
	}
	
	// this is coming first time from Remote
	public function registerMe(){
		
		$uuid 		= $this->input->post('uuid');
		$fullname 	= $this->input->post('fullname');
		$email 		= $this->input->post('email');
		
		$wa 		= $this->input->post('whatsapp');
		
		$country  	= $this->getMyCountry();
		$country	= strtolower($country);
		
		// switch either 1 of these 2 option for IP Address
		//$ip 		= $this->getMyIP();
		$ip 		= $this->input->post('ip_address');
		
		$status = 'pending';
		// status has 3 condition:
		// pending 		-> waiting for activation
		// active  		-> normal
		// disabled 	-> locked by admin
		
		$membership = 0;
		$code		= 1;
		
		// Membership is limited to : 
		// (0) Free, (1) Premium
		// Code : 
		// (0) client, (1) remote
	
		$tgl = date('Y-m-d H:i:s');
		
		if(isset($email)){
			$email		= strtolower($email);
		}
		
		
		$end = $this->UserModel->add($uuid, $membership, $code, $ip, $country, $fullname, $wa, $email, $status, $tgl);
		
		if(isset($email) && $country == "id"){
			$token = $this->TokenModel->generateCode(25);
			
			$this->TokenModel->add($uuid, $token);
			
			$tgl = date('l, d-F-Y H:i:s', strtotime($tgl));
			$this->EmailModel->success_account_created($country, $fullname, $tgl, $email, $token);
		}
		
		echo json_encode($end);
		
	}
	
	public function updateMe(){
		
		$uuid 		= $this->input->post('uuid');
		$fullname 	= $this->input->post('fullname');
		$email 		= $this->input->post('email');
		
		$wa 		= $this->input->post('whatsapp');
		
		if(isset($email)){
			$email		= strtolower($email);
		}
		
		$end = $this->UserModel->editByUUID($uuid, $fullname, $wa, $email);
		
		echo json_encode($end);
		
	}
	
	public function purchaseSerial(){
		// making whatsapp link call
		//https://wa.me/6285735501035?text=Isi Pesan
		$lang = $this->input->get('language');
		$uuid = $this->input->get('uuid');
		
		$message = "";
		
		if($lang == 'id'){
		
		$message = "Hello *Admin FGroupIndonesia*!" . urlencode("\n");
		$message .= "saya : .....(tulis nama) " . urlencode("\n");
		$message .= "dengan email : .... (tulis email) " . urlencode("\n");
		$message .= "UUID aplikasi : *" . $uuid . "* " .urlencode("\n");
		$message .= "ingin membeli Akses *Membership Premium Serial*" . urlencode("\n");
		$message .= "untuk aplikasi Android *Parent Control*. Apakah masih tersedia?";
		
		}else{
			$message = "Hello *Admin of FGroupIndonesia*!" . urlencode("\n");
			$message .= "My name : .....(write your name) " . urlencode("\n");
			$message .= "with email : .... (write your email) " . urlencode("\n");
			$message .= "UUID app : *" . $uuid . "* " .urlencode("\n");
			$message .= "want to purchase a Serial Number for *Premium Membership*" . urlencode("\n");
			$message .= "for *Parent Control* - Android App. Is it still available?";
		}
		
		$waBeliSerial = "https://wa.me/6285795569337?text=" . $message;
		
		redirect($waBeliSerial, 'refresh');
	}
	
	public function registerClient(){
		
		$client_uuid 		= $this->input->post('uuid_client');
		$remote_uuid 		= $this->input->post('uuid_remote');
		
		$membership = $this->input->post('membership');
		$code 		= $this->input->post('code');
		$ip_address = $this->input->post('ip_address');
		
		$country		= $this->input->post('country');
		$location_lat 	= $this->input->post('location_lat');
		$location_long 	= $this->input->post('location_long');
		$city			= $this->input->post('city');
		
		// status :
		// - active
		// - pending
		// - disabled
		
		$status_device = 'active';
		
		// empty command for first registration
		$command = ''; // command will be vary but using csv format : kill-app, restart, etc
		$applied = '0'; // values are 0 - pending, 1 - executed
		
		$tglMasuk = date('Y-m-d H:i:s');
		
		$fullname = "";
		$wa = "";
		$email = "";
		
		$respond = $this->UserModel->add($client_uuid, $membership, $code, $ip_address, $country, $fullname, $wa, $email, $status_device, $tglMasuk);
		
		if($respond['status'] == 'valid'){
		
		$this->CommandModel->add($client_uuid, $remote_uuid, $command, $applied);
		
		// and also the coordinates of the device at the moment
		$respond = $this->TrackerModel->add($client_uuid, $location_lat, $location_long, $city, $status_device);
		
		}
		
		echo json_encode($respond);
	}
	
	public function read(){
		
		$uuid = $this->input->post('uuid');
		
		$respond =	$this->CommandModel->get($uuid);
		
		echo json_encode($respond);
		
	}
}
