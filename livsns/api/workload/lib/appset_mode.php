<?php
class appset_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "appset  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$info[] = $r;
		}
		return $info;
	}
	
	public function create($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "appset SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."appset SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "appset WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "appset SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "appset  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "appset WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "appset WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "appset WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function state($id = '',$state)
	{
		if(!$id)
		{
			return false;
		}
		$sql = " UPDATE " .DB_PREFIX. "appset SET state = '" .$state. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('state' => $state,'id' => $id ? explode(',',$id) : array());
	}
	
	//检查相关应用中是否存在统计接口
	public function check_exist($app_uniqueid,$file,$func)
	{
		if(!$app_uniqueid)
		{
			return false;
		}
		if(!$this->settings['App_'.$app_uniqueid])
		{
			return false;
		}
		$file = $file ? $file : $app_uniqueid.'.php';
		$func = $func ? $func : 'get_workload';
		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$app_set = $this->settings['App_'.$app_uniqueid];
		$curl = new curl($app_set['host'],$app_set['dir']);
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a',$func);
		$ret = $curl->request('admin/'.$file);
		$ret = $ret[0];
		if(!$ret['static'])
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function check_name_exist($name)
	{
		if(!$name)
		{
			return false;
		}
		$sql = 'SELECT id FROM '.DB_PREFIX.'appset WHERE app_uniqueid="'.$name.'"';
		$query = $this->db->query_first($sql);
		if($query['id'])
		{
			return false;
		}
		return true;
	}
}
?>