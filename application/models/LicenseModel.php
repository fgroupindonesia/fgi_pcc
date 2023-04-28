<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LicenseModel extends CI_Model {

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
	
	public function updateUUID($olduuid, $uuid){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'uuid'		=> $uuid
		);
		
		$this->db->where('uuid', $olduuid);
		$this->db->update('data_licenses', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
	}
	
	public function getAll(){
		
		$endResult = $this->generateRespond('invalid');
		
		$this->db->order_by('id', 'DESC');
		
		$query = $this->db->get('data_licenses');
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			
			$data = array(
				'id' 			=> $row->id,
				'uuid'			=> $row->uuid,
				'serial_number'	=> $row->serial_number,
				'email'			=> $row->email,
				'whatsapp'		=> $row->whatsapp,
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
	
	public function get($uuid){
		
		$endResult = $this->generateRespond('invalid');
		
		$this->db->order_by('id', 'DESC');
		$this->db->where('uuid', $uuid);
		
		$query = $this->db->get('data_licenses');
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			
			$data = array(
				'id' 			=> $row->id,
				'uuid'			=> $row->uuid,
				'serial_number'	=> $row->serial_number,
				'email'			=> $row->email,
				'whatsapp'		=> $row->whatsapp,
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
	
	
	// used internally
	private function checkDuplicate($uuid){
		
		$stat = false;

		$this->db->where('uuid', $uuid);
		$query = $this->db->get('data_licenses');
		
		foreach ($query->result() as $row)
		{
			$stat = true;
			break;
		}
		
		
		return $stat;
	}
	
	public function add($uuid, $serial_number, $email, $whatsapp){
		
		$stat = 'invalid';
		$tgl = date('Y-m-d H:i:s');
		
			$data = array(
				'uuid'			=> $uuid,
				'serial_number'	=> $serial_number,
				'email'			=> $email,
				'whatsapp'		=> $whatsapp,
				'date_created'	=> $tgl
			);
		
		$found = $this->checkDuplicate($uuid);
		
		if($found === FALSE){
		
		$this->db->insert('data_licenses', $data);
		$stat = 'valid';
		
		}
		
		
		return $this->generateRespond($stat);
	}

	public function update($serial_number, $email, $whatsapp){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'serial_number'	=> $serial_number
		);		
		
		$matching = array(
			'email'			=> $email,
			'whatsapp'		=> $whatsapp
		);
		
		$this->db->where($matching);
		$this->db->update('data_licenses', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
				
				$endRes['multi_data'] = $data;
				
		}
		
		return $endRes;
		
	}
	
	public function validate($uuid, $serial_number){
		
		$endRes = $this->generateRespond('invalid');
		
		$this->db->where('uuid', $uuid);
		
		$query = $this->db->get('data_licenses');
		
		foreach ($query->result() as $row)
		{
			
			if($row->serial_number == $serial_number){
					$endRes['status'] = 'valid';
			}
			
		}
		
		return $endRes;
		
	}
	
}