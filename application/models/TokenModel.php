<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TokenModel extends CI_Model {

	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		
	}
	
	public function generateCode($length){
		
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		
		return $randomString;

	}
	
	public function generateRespond($statusIn){
		
		$stat = array(
			'status' => $statusIn
		);
		
		return $stat;
	}
	
	// used internally
	private function checkDuplicate($username){
		
		$stat = false;

		$this->db->where('username', $username);
		$query = $this->db->get('data_tokens');
		
		foreach ($query->result() as $row)
		{
			$stat = true;
			break;
		}
		
		
		return $stat;
	}
	
	public function verify($token){
		
		$res = $this->generateRespond('invalid');

		$this->db->where('token', $token);
		$query = $this->db->get('data_tokens');
		
		foreach ($query->result() as $row)
		{
			$res = $this->generateRespond('valid');
			break;
		}
		
		
		return $res;
	}
	
	public function add($username, $token){
		
		$tgl = date('Y-m-d H:i:s');
		$stat =  $this->generateRespond('invalid');
	
			$data = array(
				'username' 	=> $username,
				'token' 	=> $token,
				'date_created'	=> $tgl
			);
		
		$found = $this->checkDuplicate($username);
		
		if($found === FALSE){
		
		$this->db->insert('data_tokens', $data);
		
		$stat['status'] = 'valid';
		$stat['multi_data'] = $data;
		
		}else{
			$stat = $this->update($username, $token);
		}
		
		
		return $stat;
	}

	public function update($username, $token){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'username' 	=> $username,
				'token' 	=> $token
		);
		
		$this->db->where('username', $username);
		$this->db->update('data_tokens', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes['status'] = 'valid';
				$endRes['multi_data'] = $data;
		}
		
		return $endRes;
		
	}
	
	public function getBy($col, $val){
		
		$endResult = $this->generateRespond('invalid');
		
		$this->db->order_by('id', 'DESC');
		
		$data = array(
			$col => $val
		);
		
		$this->db->where($data);
		$query = $this->db->get('data_tokens');
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			
			$data = array(
				'id' 			=> $row->id,
				'username'			=> $row->username,
				'token'			=> $row->token,	
				'date_created'	=> $row->date_created,				
				'date_modified'	=> $row->date_modified
			);
			
			$endResult['multi_data'] = $data;
		}
		
		if($endResult['status'] == 'invalid'){
			unset($endResult['multi_data']);
		}
		
		return $endResult;
		
	}
	
	public function delete($username){
		
		$endRes = $this->generateRespond('invalid');
		
		
		
		$this->db->where('username', $username);
		$this->db->delete('data_tokens');
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
	}
	
}