<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: encode.class.php 5093 2011-11-16 09:50:56Z repheal $
***************************************************************************/
class encode extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function show($condition)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "output_stream WHERE 1 ORDER BY id DESC";
		$q = $this->db->query($sql);
		$stream = array();
		while($row = $this->db->fetch_array($q))
		{
			$stream[$row['encode_id']][$row['num']] = $row ;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "coding WHERE 1 " . $condition . " ORDER BY id DESC";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['stream'] = $stream[$row['id']];
			/*$count = count($this->settings['stream_port']);
			if(count($row['stream']) != $count)
			{
				foreach($this->settings['stream_port'] as $key => $value)
				{
					if(!$row['stream'][$key])
					{
						$row['stream'][$key] = '';
					}
				}
			}*/
			$info[$row['id']] = $row;
		}
		return $info;
	}

	function create($info)
	{
		$stream = $info['stream'];
		unset($info['stream']);
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
			$sql = "INSERT INTO " . DB_PREFIX . "coding SET ".$sql_extra;
			$this->db->query($sql);
			$info['id'] = $this->db->insert_id();
			
			$sql = "INSERT INTO " . DB_PREFIX . "output_stream(num,encode_id,name,stream,port) VALUES";
			$sql_extra = $space = "";
			$num = count($this->settings['stream_port']);
			for($i = 1;$i <= $num; $i++)
			{
				if($stream['num'][$i])
				{
					$sql_extra .= $space."('" . $stream['num'][$i] . "'," . $info['id'] . ",'" . $stream['name'][$i] . "','" . $stream['stream'][$i] . "','" . $stream['port'][$i] . "')";
					$space = ",";
				}
			}

			if($sql_extra)
			{
				$this->db->query($sql.$sql_extra);
			}

			return $info;
		}
		return false;
	}

	function update($info,$id)
	{	
		$stream = $info['stream'];
		unset($info['stream']);
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
			$sql_ =  "SELECT * FROM " . DB_PREFIX . "coding WHERE id = " . $id;
			$pre_data = $this->db->query_first($sql_);
		
			$sql = "UPDATE " . DB_PREFIX . "coding SET " . $sql_extra . " WHERE id=" . $id;
			$this->db->query($sql);

			$sql = "";
		//	$sql_extra = $space = "";
			$num = count($this->settings['stream_port']);
			for($i = 1;$i <= $num; $i++)
			{
				if($stream['id'][$i])
				{
					if($stream['num'][$i])
					{
						$sql = "UPDATE " . DB_PREFIX . "output_stream SET num='" . $stream['num'][$i] ."',encode_id='" . $id ."',name='" . $stream['name'][$i] ."',stream='" . $stream['stream'][$i] ."',port='" . $stream['port'][$i] ."' WHERE id=" . $stream['id'][$i];
					}
					else
					{
						$sql = "DELETE FROM " . DB_PREFIX . "output_stream WHERE id=" . $stream['id'][$i];
					}
				}
				elseif($stream['name'][$i])
				{
					$sql = "INSERT INTO " . DB_PREFIX . "output_stream SET num='" . $stream['num'][$i] ."',encode_id='" . $id ."',name='" . $stream['name'][$i] ."',stream='" . $stream['stream'][$i] ."',port='" . $stream['port'][$i] ."'";
				}
				$this->db->query($sql);
			}
			$this->addLogs('update' , $pre_data , $info , '' , '');
			return $info;
		}
		return false;
	}

	public function delete($id)
	{
		if(!$id)
		{
			return false;
		}
		
		$sql_ =  "SELECT * FROM " . DB_PREFIX . "coding WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql_);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$ret[] = $row;
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "coding WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX . "output_stream WHERE encode_id IN (" . $id . ")";
		$this->db->query($sql);
		
		$this->addLogs('delete' , $ret , '' , '' , '');
		return true;
	}
	
	public function detail($id)
	{
		if(!$id)
		{
			$condition = " ORDER BY id DESC LIMIT 1";
		}
		else 
		{
			$condition = " WHERE id IN(" . $id . ")";
		}
		$sql ="SELECT * FROM " . DB_PREFIX . "coding " . $condition;
		$info = $this->db->query_first($sql);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "output_stream WHERE 1 AND encode_id=" . $info['id'] . " ORDER BY id DESC";
		$q = $this->db->query($sql);
		$stream = array();
		while($row = $this->db->fetch_array($q))
		{
			$stream[$row['num']] = $row ;
		}
		$info['stream'] = $stream;
		return $info;
	}
	
	public function verify($data)
	{
		if(is_array($data))
		{
			foreach($data as $key => $value)
			{
				$sql_extra = $key . "='" . $value . "'";
			}
			$sql = "SELECT * FROM " . DB_PREFIX . "coding WHERE 1 AND " . $sql_extra;
			$r = $this->db->query_first($sql);
			if($r['id'])
			{
				return $r;
			}
		}
		return false;
	}

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "coding WHERE 1 ";
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		return $r;
	}
}

?>