<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {

	// called by client
	function __construct() {
			parent::__construct();
			$this->load->model('UserModel');
			$this->load->model('TrackerModel');
			$this->load->model('CommandModel');
	}

	public function test(){
		echo "X";
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}
	
	public function myip(){
		$ip = $_SERVER['REMOTE_ADDR'];
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		echo json_encode($details);
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
	
	public function read(){
		
		$uuid = $this->input->post('uuid');
		
		$respond =	$this->CommandModel->get($uuid);
		
		echo json_encode($respond);
		
	}
}
