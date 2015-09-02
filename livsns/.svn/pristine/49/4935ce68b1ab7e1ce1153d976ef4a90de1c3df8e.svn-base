<?php
class style extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function show($condition)
	{
		$sites = $this->show_site();
		$sql = "SELECT * FROM ".DB_PREFIX."template_style WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		$ret = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['site_name'] = $sites[$row['site_id']]['site_name'];
			$row['create_time'] = date('Y-m-d H:i',$row['create_time']);
			$row['update_time'] = date('Y-m-d H:i',$row['update_time']);
			if($row['pic'])
			{
				$row['pic'] = json_decode($row['pic'],1);
			}
			$ret[] = $row;
		}
		return $ret;
	}
	public function detail($condition,$field = "*")
	{
		$sql = "SELECT " . $field . " FROM " . DB_PREFIX . "template_style WHERE 1 " . $condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."template_style WHERE 1 " . $condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	public function create($data,$tableName = 'template_style')
	{
		$field = '';
		if(is_string($data) && $data != '')
		{
			$field = $data;
		}
		else if(is_array($data) && count($data) > 0)
		{
			$field = array();
			foreach($data as $k => $v)
			{
				$field[] = $k ."='".$v."'";
			}
			$field = implode(',',$field);
		}
		$sql = "INSERT INTO " . DB_PREFIX . $tableName . " SET " . $field;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function update($data, $condition, $tableName = 'template_style') 
	{
		if($tableName == '' or $condition == '') 
		{
			return false;
		}
		$where = ' WHERE '.$condition;
		$field = '';
		if(is_string($data) && $data != '') 
		{
			$field = $data;
		} 
		elseif (is_array($data) && count($data) > 0) 
		{
			$fields = array();
			foreach($data as $k=>$v) 
			{
				$fields[] = $k."='".$v . "'";
			}
			$field = implode(',', $fields);
		} 
		else 
		{
			return false;
		}
		$sql = 'UPDATE '. DB_PREFIX . $tableName . ' SET '.$field. " WHERE 1 " . $condition;
		return $this->db->query($sql);
	}	
	public function delete($condition,$tableName = 'template_style') 
	{
		if($tableName == '' || $condition == '') 
		{
			return false;
		}
		$where = ' WHERE 1 '.$condition;
		$sql = 'DELETE FROM ' . DB_PREFIX . $tableName . " WHERE 1 ". $condition;
		return $this->db->query($sql);
	}	
	public function audit($ids, $audit)
	{
		if(!$ids)
		{
			return false;
		}
		$idArr = explode(',',$ids);
		$condition = " AND id IN(" . $ids . ")";
		if($audit == 1)   //启用
		{
			$this->update(array('state' =>1), $condition);
			$ret = array('state' => 1, 'id' => $idArr);
		}
		else if($audit == 0)
		{
			$this->update(array('state' =>0), $condition);
			$ret = array('state' => 0, 'id' => $idArr);
		}
		return $ret;
	}		
	public function update_using($id,$updateSelf = 1)
	{
		if(!$id)
		{
			return false;
		}
		if($updateSelf)
		{
			$condition = " AND id =" . $id;
			$this->update(array('isusing' => 1), $condition);
		}		
		$condition = " AND id !=" . $id;
		$info = $this->detail($condition,'site_id');
		$condition = " AND site_id = " . $info['site_id'] . " AND site_id !=0 ";
		$this->update(array('isusing' => 0), $condition);
		return true;
	}	
	public function show_site()
	{
	    include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
	    $publish = new publishconfig();
	    $sites = $publish->get_site('id,site_name');
	    $ret = array();
	    if(is_array($sites) && count($sites) > 0)
	    {
	    	foreach($sites as $k => $v)
	    	{
	    		$ret[$v['id']] = $v;
	    	}
	    }
	    return $ret;		
	}
}
?>