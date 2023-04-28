<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	// called by admin only
	function __construct() {
			parent::__construct();
			$this->load->model('TokenModel');
	}

	public function openAccess()
	{
		// access token is 25 Digit
		$token = $this->TokenModel->generateCode(25);
		$end =	$this->TokenModel->add('admin', $token);
	
		echo json_encode($end);
	}
	
	public function closedAccess()
	{
		$token = '';
		$end =	$this->TokenModel->delete('admin');
	
		echo json_encode($end);
	}
	
	public function index()
	{
		$this->load->view('welcome_message');
	}
	
}
