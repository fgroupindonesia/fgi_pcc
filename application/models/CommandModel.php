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
			
			$dataCMD = $this->clearStrip($row->commands);
			
			$data = array(
				'id' 			=> $row->id,
				'client_uuid'	=> $row->client_uuid,
				'remote_uuid'	=> $row->remote_uuid,
				'commands'		=> $dataCMD,
				'applied'		=> $row->applied,
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
	
	public function updateUUID($olduuid, $uuid){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'remote_uuid'		=> $uuid
		);
		
		$this->db->where('remote_uuid', $olduuid);
		$this->db->update('data_commands', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
	}
	
	public function get($uuid, $appliedStatus, $modeCompleteness){
		
		$endResult = $this->generateRespond('invalid');
		
		
		// 1 is COMPLETE
		// 0 is SIMPLE
		
		// THIS IS COMPLETE {"status":"valid","multi_data":{"id":"5","client_uuid":"4B435451-394A-3043-4631-14DAE9AD8243","remote_uuid":"2222","command":{"restart":"true"},"applied":"0","date_created":"2023-04-20 10:59:29","date_modified":"2023-04-20 11:08:25"}}

		// THIS IS SIMPLE
		// {"status":"valid","multi_data":"command":{"restart":"true"}}
		
		$mode = 1;
		
		if(isset($modeCompleteness)){
			$mode = $modeCompleteness;
		}
		
		$this->db->order_by('id', 'DESC');
		$this->db->where('client_uuid', $uuid);
		
		$query = $this->db->get('data_commands');
		
		foreach ($query->result() as $row)
		{
			
			$dataCMD = $this->clearStrip($row->commands);
			
			if($mode == 1){
			
			$data = array(
				'id' 			=> $row->id,
				'client_uuid'	=> $row->client_uuid,
				'remote_uuid'	=> $row->remote_uuid,
				'commands'		=> $dataCMD,
				'applied'		=> $row->applied,
				'date_created'	=> $row->date_created,
				'date_modified'	=> $row->date_modified
			);
			
			}else {
				
				$data = array(
				'commands'		=> $dataCMD
				);
				
			}
			
			// take the same status 1 or 0
			if($row->applied == $appliedStatus){
				$endResult['status'] = 'valid';
				$endResult['multi_data'] = $data;
			}
		}
		
		if($endResult['status'] == 'invalid'){
			unset($endResult['multi_data']);
		}
		
		return $endResult;
		
	}
	
	private function checkDuplicate($uuid_remote, $uuid_client){
		
		$stat = false;

		$dataSelective = array(
			'client_uuid' 	=> $uuid_client,
			'remote_uuid'	=> $uuid_remote
		);

		$this->db->where($dataSelective);
		$query = $this->db->get('data_commands');
		
		foreach ($query->result() as $row)
		{
			$stat = true;
			break;
		}
		
		
		return $stat;
	}
	
	public function add($client_uuid, $remote_uuid, $command, $applied){
		
		$stat = 'invalid';
		$tgl = date('Y-m-d H:i:s');
		
			$data = array(
				'client_uuid'	=> $client_uuid,
				'remote_uuid'	=> $remote_uuid,
				'commands'		=> $command,
				'applied'		=> $applied,
				'date_created'	=> $tgl
			);
		
		if(!$this->checkDuplicate($remote_uuid, $client_uuid)){
			$this->db->insert('data_commands', $data);
			$stat = 'valid';
		}
		
		return $this->generateRespond($stat);
	}

	public function executed($id, $uuid){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'applied'		=> 1
			);
		
		if(isset($id)){
			$this->db->where('id', $id);
		} else {
			$this->db->where('client_uuid', $uuid);
		}
		
		$this->db->update('data_commands', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
	}

	private function isCommandExecuted($clientUUID, $remoteUUID){
		
		$stat = false;

		$data = array(
				'client_uuid'	=> $clientUUID,
				'remote_uuid'	=> $remoteUUID,
				'applied'		=> 1
		);

		$this->db->where($data);
		$query = $this->db->get('data_commands');
		
		foreach ($query->result() as $row)
		{
			$stat = true;
			break;
		}
		
		
		return $stat;
		
		
	}

	private function getCommand($clientUUID, $remoteUUID){
		
		$cmd = "";

		$data = array(
				'client_uuid'	=> $clientUUID,
				'remote_uuid'	=> $remoteUUID
		);

		$this->db->where($data);
		$query = $this->db->get('data_commands');
		
		foreach ($query->result() as $row)
		{
			$cmd = $row->commands;
			break;
		}
		
		return $cmd;
		
		
	}

	public function edit($client_uuid, $remote_uuid, $command, $applied){
		
		$endRes = $this->generateRespond('invalid');
		
		$currStatus = $this->isCommandExecuted($client_uuid, $remote_uuid);
		
		$curCommand = "";
		
		if($currStatus == false){
			// if it is has't been executed...
			$curCommand = $this->getCommand($client_uuid, $remote_uuid);
			$curCommand = $this->clearStrip($curCommand);
		}else{
			$curCommand = array();
		}
		
		$curCommand [] = $command;
		$curCommand = json_encode($curCommand);
		
		
		$data = array(
				'commands'		=> $curCommand,
				'applied'		=> $applied
		);
		
		$lookFor = array(
				'client_uuid'	=> $client_uuid,
				'remote_uuid'	=> $remote_uuid
		);
		
		$this->db->where($lookFor);
		$this->db->update('data_commands', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
	}
	
	
	function reverse_mysqli_real_escape_string($str) {
		return strtr($str, [
			'\0'   => "\x00",
			'\n'   => "\n",
			'\r'   => "\r",
			'\\\\' => "\\",
			"\'"   => "'",
			'\"'   => '"',
			'\Z' => "\x1a"
		]);
	}
	
	private function clearStrip($data){
		// we clear everything so it's safer to be consumed by
			// the next generation
			$dt = $this->reverse_mysqli_real_escape_string($data);
			$dt = stripslashes($dt);
			$dt = json_decode($dt);
			
			return $dt;
	}
	
}