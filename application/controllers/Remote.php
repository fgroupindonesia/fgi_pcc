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
			$this->load->model('LicenseModel');
	}

	public function test(){
		echo "-remote-";
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	private function escapedString($val){
		
		$db = get_instance()->db->conn_id;
		$val = mysqli_real_escape_string($db, $val);
		return $val;
	
	}
	
	public function initialize(){
		
		// just say 'valid' if exist that's all!
		// instead of 'invalid' :D
		
		$uuid 			= $this->input->post('uuid');
		
		$respond = $this->UserModel->initialize($uuid);
		echo json_encode($respond);
		
	}
	
	public function login(){
		
		// return back the login cridentials
		// otherwise say -> 'invalid'
		
		$email 			= $this->input->post('email');
		$wa 			= $this->input->post('whatsapp');
		$uuid 			= $this->input->post('uuid');

		
		$respond = $this->UserModel->verify($email, $wa, $uuid);
		
		
		if($respond['status'] == 'valid'){
			$oldUUID = $respond['multi_data']['uuid'];
			// update the next tables needed for this uuid
			$remoteUUID = $uuid;
			$this->CommandModel->updateUUID($oldUUID, $remoteUUID);
			$this->LicenseModel->updateUUID($oldUUID, $remoteUUID);
		}
		
		echo json_encode($respond);
		
	}
	
	public function sendCommand(){
		
		
		$client_uuid 		= $this->input->post('uuid_client');
		$remote_uuid 		= $this->input->post('uuid_remote');
		
		// command in as json object
		
		$command 		= $this->input->post('command');
		
		// make it as string json
		$command = json_encode($command);
		// and make it applicable for non-injection SQL attack
		//$command = $this->escapedString($command);
		
		// with status of 0 : pending
		$applied 		= '0';
		
		// this command will be updated to the existing data
		// as array even if the data has / hasn't been executed
		
		$respond = $this->CommandModel->edit($client_uuid, $remote_uuid, $command, $applied);
	
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
		$message .= "UUID aplikasi : *" . $uuid . "* " .urlencode("\n\n");
		$message .= "saya ingin membeli Akses *Membership Premium Serial*" . urlencode("\n");
		$message .= "untuk aplikasi Android *Parent Control*. Apakah masih tersedia?";
		
		}else{
			$message = "Hello *Admin of FGroupIndonesia*!" . urlencode("\n");
			$message .= "My name : .....(write your name) " . urlencode("\n");
			$message .= "with email : .... (write your email) " . urlencode("\n");
			$message .= "UUID app : *" . $uuid . "* " .urlencode("\n\n");
			$message .= "I want to purchase a Serial Number for *Premium Membership*" . urlencode("\n");
			$message .= "for *Parent Control* - Android App. Is it still available?";
		}
		
		$waBeliSerial = "https://wa.me/6285795569337?text=" . $message;
		
		redirect($waBeliSerial, 'refresh');
	}
	
	public function askhelp(){
		// making whatsapp link call
		//https://wa.me/6285735501035?text=Isi Pesan
		$lang = $this->input->get('language');
		$uuid = $this->input->get('uuid');
		
		$message = "";
		
		if($lang == 'id'){
		
		$message = "Hello *Admin FGroupIndonesia*!" . urlencode("\n");
		$message .= "saya : .....(tulis nama) " . urlencode("\n");
		$message .= "dengan UUID aplikasi : *" . $uuid . "* " .urlencode("\n\n");
		$message .= " saya ini baru saja menggunakan aplikasi Android *Parent Control*. Dan butuh bantuan...";
		
		}else{
			$message = "Hello *Admin of FGroupIndonesia*!" . urlencode("\n");
			$message .= "My name : .....(write your name) " . urlencode("\n");
			$message .= "with UUID app : *" . $uuid . "* " .urlencode("\n\n");
			$message .= " I just used the *Parent Control* - Android App recently. And now, I need some help...";
		}
		
		$waAdmin = "https://wa.me/6285795569337?text=" . $message;
		
		redirect($waAdmin, 'refresh');
	}
	
	// the client is already registered by itself
	// once the desktop is clicked ON (mode : GLOBAL)
	// thus remote only call here the command for preparation
	public function registerClient(){
		
		// taken from Camera of QRCode
		$client_uuid 		= $this->input->post('uuid_client');
		$ip_address 		= $this->input->post('ip_address');
		
		// taken from Remote Device (android)
		$remote_uuid 		= $this->input->post('uuid_remote');
	
		// empty command for first registration
		// later will be added accordingly
		$command = '[]'; 
		// command will be vary but using JSON format : 
		// check the documentation for more INFO
		
		$applied = '0'; // values are 0 - pending, 1 - executed
		
		//$tglMasuk = date('Y-m-d H:i:s');
		
		$respond = $this->CommandModel->add($client_uuid, $remote_uuid, $command, $applied);
		
		echo json_encode($respond);
	}
	
	public function read(){
		
		$uuid = $this->input->post('uuid');
		
		$respond =	$this->CommandModel->get($uuid);
		
		echo json_encode($respond);
		
	}
}
