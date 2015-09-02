<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: output_stream.class.php 5093 2011-11-16 09:50:56Z repheal $
***************************************************************************/
class outputStream extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show()
	{
		$condition = $this->get_condition();
		$sql = "SELECT * FROM " . DB_PREFIX . "output_stream WHERE 1 " . $condition . " ORDER BY id DESC";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row ;
		}
		return $info;
	}

	function create()
	{
		$info = array(
			'encode_id' => trim($this->input['encode_id'])?trim($this->input['encode_id']):0,
			'name' => trim(urldecode($this->input['name']))?trim(urldecode($this->input['name'])):'',
			'stream' => trim(urldecode($this->input['stream']))? trim(urldecode($this->input['stream'])):'',
			'port' => trim(urldecode($this->input['port']))?trim(urldecode($this->input['port'])):'',
			'is_used' => trim($this->input['is_used'])?1:0,
		);
		$sql_extra = $space = "";
		foreach($info as $key => $value)
		{
			if($value)
			{
				$sql_extra .= $space . $key . "='" . $value . "'";
				$space = ",";
			}
		}
		if($sql_extra)
		{
			$sql = "INSERT INTO " . DB_PREFIX . "output_stream SET ".$sql_extra;
			$this->db->query($sql);
			$info['id'] = $this->db->insert_id();
			return $info;
		}
		return false;
	}
	
	function update()
	{
		$info = array(
		//	'encode_id' => trim($this->input['encode_id'])?trim($this->input['encode_id']):0,
			'name' => trim(urldecode($this->input['name']))?trim(urldecode($this->input['name'])):'',
			'stream' => trim(urldecode($this->input['stream']))? trim(urldecode($this->input['stream'])):'',
			'port' => trim(urldecode($this->input['port']))?trim(urldecode($this->input['port'])):'',
			'is_used' => trim($this->input['is_used'])?1:0,	
		);		
		$sql_extra = $space = "";
		foreach($info as $key => $value)
		{
			if($value)
			{
				$sql_extra .= $space . $key ."='" . $value . "'";
				$space = ",";
			}
		}

		if($sql_extra)
		{
			
			$sql_ =  "SELECT * FROM " . DB_PREFIX . "output_stream WHERE id = " . trim($this->input['id']);
			$pre_data = $this->db->query_first($sql_);
		
			$sql = "UPDATE " . DB_PREFIX . "output_stream SET " . $sql_extra . " WHERE id=" . trim($this->input['id']);
			$this->db->query($sql);
			
			$this->addLogs('update' , $pre_data , $info , '' , '');
			return $info;
		}
		return false;
	}
	
	/**
	 * 获取单条信息
	 */
	public function detail()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = " ORDER BY id DESC LIMIT 1";
		}
		else 
		{
			$condition = " WHERE id IN(" . $id . ")";
		}
		$sql ="SELECT * FROM " . DB_PREFIX . "output_stream " . $condition;
		$row = $this->db->query_first($sql);
		return $info;
	}

	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "output_stream WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		return $r;
	}

	public function verify($data)
	{
		if(is_array($data))
		{
			foreach($data as $key => $value)
			{
				$sql_extra = $key . "='" . $value . "'";
			}
			$sql = "SELECT * FROM " . DB_PREFIX . "output_stream WHERE 1 encode_id=" . trim($this->input['encode_id']) . " AND " . $sql_extra;
			$r = $this->db->query_first($sql);
			if($r['id'])
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';
		if($this->input['encode_id']>0)
		{
			$condition .= ' AND encode_id=' . $this->input['encode_id'];
		}
		return $condition;
	}
}

?>