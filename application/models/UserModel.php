<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {

	function __construct() {
		parent::__construct();
		date_default_timezone_set('Asia/Jakarta');
		
	}
	
	public function generateRespond($statusIn){
		
		$stat = array(
			'status' => $statusIn
		);
		
		return $stat;
	}
	
	public function getAll(){
		
		$endResult = $this->generateRespond('invalid');
		
		$this->db->order_by('id', 'DESC');
		
		$query = $this->db->get('data_users');
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			
			$data = array(
				'id' 			=> $row->id,
				'uuid'			=> $row->uuid,
				'ip_address'	=> $row->ip_address,
				'membership'	=> $row->membership,
				'code'			=> $row->code,
				'date_created'	=> $row->date_created
			);
			
			$endResult['multi_data'][] = $data;
		}
		
		if($endResult['status'] == 'invalid'){
			unset($endResult['multi_data']);
		}
		
		return $endResult;
		
	}
	
	
	// used internally
	private function checkDuplicate($uuid){
		
		$stat = false;

		$this->db->where('uuid', $uuid);
		$query = $this->db->get('data_users');
		
		foreach ($query->result() as $row)
		{
			$stat = true;
			break;
		}
		
		
		return $stat;
	}
	
	public function add($uuid, $membership, $code, $ip_address){
		
		$stat = 'invalid';
		
			$data = array(
				'uuid' 				=> $uuid,
				'membership' 		=> $membership,
				'code' 				=> $code,
				'ip_address'		=> $ip_address
			);
		
		$found = $this->checkDuplicate($uuid);
		
		if($found === FALSE){
		
		$this->db->insert('data_users', $data);
		$stat = 'valid';
		
		}
		
		
		return $this->generateRespond($stat);
	}

	public function edit($id, $uuid, $membership, $code, $ip_address){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'uuid' 				=> $uuid,
				'membership' 		=> $membership,
				'code' 				=> $code,
				'ip_address'		=> $ip_address
			);
		
		
		$this->db->where('id', $id);
		$this->db->update('data_users', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
	}
	
}