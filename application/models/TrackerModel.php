<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TrackerModel extends CI_Model {

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
		
		$query = $this->db->get('data_trackers');
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			
			$data = array(
				'id' 			=> $row->id,
				'uuid'			=> $row->uuid,
				'location_lat'	=> $row->location_lat,
				'location_long'	=> $row->location_long,
				'city'			=> $row->city,
				'status_device'	=> $row->status_device,
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
		
		$query = $this->db->get('data_trackers');
		
		foreach ($query->result() as $row)
		{
			$endResult['status'] = 'valid';
			
			$data = array(
				'id' 			=> $row->id,
				'uuid'			=> $row->uuid,
				'location_lat'	=> $row->location_lat,
				'location_long'	=> $row->location_long,
				'city'			=> $row->city,
				'status_device'	=> $row->status_device,
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
	
	public function add($uuid, $location_lat, $location_long, $city, $status_device){
		
		$stat = 'invalid';
		$tgl = date('Y-m-d H:i:s');
		
			$data = array(
				'uuid'			=> $uuid,
				'location_lat'	=> $location_lat,
				'location_long'	=> $location_long,
				'city'			=> $city,
				'status_device'	=> $status_device,
				'date_created'	=> $tgl
			);
		
		
		$this->db->insert('data_trackers', $data);
		$stat = 'valid';
		
		return $this->generateRespond($stat);
	}

	public function edit($id, $uuid, $location_lat, $location_long, $city, $status_device){
		
		$endRes = $this->generateRespond('invalid');
		
		$data = array(
				'uuid'			=> $uuid,
				'location_lat'	=> $location_lat,
				'location_long'	=> $location_long,
				'city'			=> $city,
				'status_device'	=> $status_device
		);
		
		if(isset($id)){
		$this->db->where('id', $id);
		} else {
		$this->db->where('uuid', $uuid);
			
		}
		$this->db->update('data_trackers', $data);
		
		if($this->db->affected_rows() > 0){
				$endRes = $this->generateRespond('valid');
		}
		
		return $endRes;
		
	}
	
}