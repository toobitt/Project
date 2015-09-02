<?php
class ftplog extends InitFrm
{
	protected $field;
	function __construct()
	{
		parent::__construct();
		$this->filed = array(
		'id'=>false,
		'sync_id'=>true,
		'server_id'=>true,
		'app'=>true,
		'sfile'=>false,
		'dfile'=>false,
		'status'=>false,
		'message'=>false,
		'create_time'=>TIMENOW,
		);
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function create($data = array(), $table='ftplog')
	{
		$store_data = array();
		foreach($this->filed as $field=>$check)
		{
			if(is_bool($check))
			{
				if($check && !$data[$field])
				{
					return false;
				}
				else
				{
					$store_data[$field] = $data[$field];
				}
			}
			else
			{
				$store_data[$field] = $check;
			}
		}
		if(!empty($store_data))
		{
			$sql = 'INSERT INTO ' . DB_PREFIX . $table . '('.implode(',', array_keys($store_data)).')';
			$sql .= ' VALUES("'.implode('","', $store_data).'")';
			$this->db->query($sql);
			$id = $this->db->insert_id();
			$store_data['id'] = $id;
			return $store_data;
		}	
		return false;
	}
	function create_error($data = array())
	{
		$this->create($data, 'ftplog_error');
	}
	function select()
	{
		
	}
	function delete($id)
	{
		$id = is_array($id) ? implode(',', $id) : $id;
		if(!id)
		{
			return false;
		}
		$sql = 'DELETE FROM ' . DB_PREFIX . 'ftplog WHERE id IN('.$id.')';
		$this->db->query($sql);
		return true;
	}
}
?>