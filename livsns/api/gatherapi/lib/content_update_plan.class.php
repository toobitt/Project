<?php
class contentUpdatePlan extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	    
	}
	
	function __destruct()
	{
		
	}
	
	public function update($data,$id){
		if(!$data || !$id || !is_array($data)){
			return false;
		}
		$sql='update '.DB_PREFIX.'plan set ';
		foreach ($data as $key => $value) {
			$sql .= $key.'='."'".$value."',"; 		
		}
		$sql=rtrim($sql,',');
		$sql .= ' where id ='.$id;
		
		$this->db->query($sql);
		return TRUE;
	}
	
	public function create($data)
	{
		if(!$data || !is_array($data)){
			return false;
		}
		$sql='insert into '.DB_PREFIX.'plan set ';
		foreach ($data as $key => $value) {
			$sql .= $key.'='."'".$value."',";
		}
		$sql=rtrim($sql,',');
		$this->db->query($sql);	
	}
	
	public function updategather($data,$id){
		if(!$data || !$id || !is_array($data)){
			return false;
		}
		$sql='update '.DB_PREFIX.'gather set ';
		foreach ($data as $key => $value) {
			$sql .= $key.'='."'".$value."',";
		}
		$sql=rtrim($sql,',');
		$sql .= ' where id ='.$id;
		$this->db->query($sql);
		return TRUE;
	}
	
	public function creategather($data)
	{
		if(!$data || !is_array($data)){
			return false;
		}
		$sql='insert into '.DB_PREFIX.'gather set ';
		foreach ($data as $key => $value) {
			$sql .= $key.'='."'".$value."',";
		}
		$sql=rtrim($sql,',');
		$this->db->query($sql);
	}
}
?>