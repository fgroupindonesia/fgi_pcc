<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IPModel extends CI_Model {

	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		
	}
	
	private $dataClient;
	
	public function getMyCity(){
		if(isset($this->dataClient)){
				return	$this->dataClient['city'];
		}
		
		return "";
	}
	
	public function getMyCountry(){
		if(isset($this->dataClient)){
				return	$this->dataClient['country'];
		}
		
		return "";
	}
	
	public function getMyLocation(){
		if(isset($this->dataClient)){
				return	$this->dataClient['loc'];
		}
		
		return "";
	}
	
	public function getMyIP($usage){
		// API ACCESS for FREE
		// https://ipinfo.io/103.147.9.19?token=bdd372b62f27d7
		
		// switch this back when TESTING
		if($usage=='testing'){
			$ip = "103.147.9.19";
		}else {
			// real live
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		
		// switch this back when ONLINE
		//$ip = $_SERVER['REMOTE_ADDR'];
		
		// or 
		/*
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			  $IP = $_SERVER['HTTP_CLIENT_IP'];
			} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			  $IP = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
			  $IP = $_SERVER['REMOTE_ADDR']; 
			}
		*/
		
		
		// because the final
		// output will be :
		/*
		
		{
		"ip": "103.147.9.44",
		"hostname": "a103-147-9-44.bdo.starnet.net.id",
		"city": "Bandung",
		"region": "West Java",
		"country": "ID",
		"loc": "-6.9222,107.6069",
		"org": "AS55699 PT. Cemerlang Multimedia",
		"timezone": "Asia/Jakarta"
		}
		
		*/
		
		
		$details = json_decode(file_get_contents("https://ipinfo.io/{$ip}?token=bdd372b62f27d7"), true);
		//echo json_encode($details);
		
		// store it locally for next call
		$this->dataClient = $details;
		
		return $details;
	}
	
}