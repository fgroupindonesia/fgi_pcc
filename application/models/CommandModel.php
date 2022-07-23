<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommandModel extends CI_Model {

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
		
		$query = $this->db->get('data_commands');
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			
			$data = array(
				'id' 			=> $row->id,
				'client_uuid'	=> $row->client_uuid,
				'remote_uuid'	=> $row->remote_uuid,
				'command'		=> $row->command,
				'applied'		=> $row->applied,
				'date_created'	=> $row->date_created
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
		$this->db->where('client_uuid', $uuid);
		
		$query = $this->db->get('data_commands');
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			
			$data = array(
				'id' 			=> $row->id,
				'client_uuid'	=> $row->client_uuid,
				'remote_uuid'	=> $row->remote_uuid,
				'command'		=> $row->command,
				'applied'		=> $row->applied,
				'date_created'	=> $row->date_created
			);
			
			$endResult['multi_data'] = $data;
		}
		
		if($endResult['status'] == 'invalid'){
			unset($endResult['multi_data']);
		}
		
		return $endResult;
		
	}
	
	public function add($client_uuid, $remote_uuid, $command, $applied){
		
		$stat = 'invalid';
		
			$data = array(
				'client_uuid'	=> $row->client_uuid,
				'remote_uuid'	=> $row->remote_uuid,
				'command'		=> $row->command,
				'applied'		=> $row->applied
			);
		
		
		$this->db->insert('data_commands', $data);
		$stat = 'valid';
		
		return $this->generateRespond($stat);
	}

	public function edit($id, $client_uuid, $remote_uuid, $command, $applied){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'client_uuid'	=> $client_uuid,
				'remote_uuid'	=> $remote_uuid,
				'command'		=> $command,
				'applied'		=> $applied
			);
		
		
		$this->db->where('id', $id);
		$this->db->update('data_commands', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
	}
	
}