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
				'whatsapp'		=> $row->whatsapp,
				'code'			=> $row->email,
				'fullname'		=> $row->fullname,
				'country'		=> $row->country,
				'email'			=> $row->email,
				'date_created'	=> $row->date_created,
				'date_modified'	=> $row->date_modified
			);
			
			$endResult['multi_data'][] = $data;
		}
		
		if($endResult['status'] == 'invalid'){
			unset($endResult['multi_data']);
		}
		
		return $endResult;
		
	}
	
	public function getBy($col, $val){
		
		$endResult = $this->generateRespond('invalid');
		
		$this->db->order_by('id', 'DESC');
		
		$data = array(
			$col => $val
		);
		
		$this->db->where($data);
		$query = $this->db->get('data_users');
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			
			$data = array(
				'id' 			=> $row->id,
				'uuid'			=> $row->uuid,
				'ip_address'	=> $row->ip_address,
				'membership'	=> $row->membership,
				'whatsapp'		=> $row->whatsapp,
				'code'			=> $row->email,
				'fullname'		=> $row->fullname,
				'country'		=> $row->country,
				'email'			=> $row->email,
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
	
	public function verify($email, $wa, $uuid){
		
		$stat = 'invalid';
		$endResult = $this->generateRespond($stat);

		$cridential = array(
			'whatsapp' => $wa,
			'email'		=> $email
		);

		$this->db->where($cridential);
		$query = $this->db->get('data_users');
		$id = -1;
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			
			$id = $row->id;
			
			$data = array(
				'id' 			=> $row->id,
				'fullname'		=> $row->fullname,
				'email'			=> $row->email,
				'uuid'			=> $row->uuid,
				'whatsapp'		=> $row->whatsapp	
			);
			
			$endResult['multi_data'] = $data;
			break;
		}
		
		$this->updateUUID($id, $uuid);
		
		return 	$endResult;
	}
	
	private function updateUUID($id, $uuid){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'uuid'		=> $uuid
		);
		
		$this->db->where('id', $id);
		$this->db->update('data_users', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
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
	
	public function add($uuid, $membership, $code, $ip_address, $country, $fullname, $wa, $email, $status, $tglMasuk){
		
		$stat = 'invalid';
		
		if(!isset($tglMasuk)){
		$tgl = date('Y-m-d H:i:s');
		}else{
			$tgl = $tglMasuk;
		}
		
		$country = strtolower($country);
		
			$data = array(
				'uuid' 				=> $uuid,
				'membership' 		=> $membership,
				'code' 				=> $code,
				'country'			=> $country,
				'fullname'			=> $fullname,
				'whatsapp'				=> $wa,
				'email'				=> $email,
				'status'			=> $status,
				'ip_address'		=> $ip_address,
				'date_created'		=> $tgl
			);
		
		$found = $this->checkDuplicate($uuid);
		
		if($found === FALSE){
		
		$this->db->insert('data_users', $data);
		$stat = 'valid';
		
		}
		
		
		return $this->generateRespond($stat);
	}

	public function updateStatus($uuid, $status){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'status'		=> $status
		);
		
		$this->db->where('uuid', $uuid);
		$this->db->update('data_users', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
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
	
	public function editByUUID($uuid, $fullname, $whatsapp, $email){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'fullname' 				=> $fullname,
				'email' 				=> $email,
				'whatsapp' 				=> $whatsapp
			);
		
		
		$this->db->where('uuid', $uuid);
		$this->db->update('data_users', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
	}
	
	public function initialize($uuid){
		
		$endRes = $this->generateRespond('invalid');
		
		// we can switch the status but 
		// at the moment nothing to do
		$endRes = $this->checkDuplicate($uuid);
	
		return $endRes;
		
	}
	
}