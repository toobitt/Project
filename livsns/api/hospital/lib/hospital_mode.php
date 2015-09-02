<?php
class hospital_mode extends InitFrm
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
		$sql = "SELECT t1.id,t1.name,t1.level,t1.status,t1.indexpic_id,t1.logo,t1.yibao_point,t1.update_time,t1.user_name,t1.order_id,t2.host,t2.dir,t2.filename,t2.filepath FROM " . DB_PREFIX . "hospital t1 
				LEFT JOIN " . DB_PREFIX ."materials t2 
					ON t1.indexpic_id = t2.id 
				 WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['status'] == 1)
			{
				$r['status_name'] = '已审核';
			}
			else if($r['status'] == 2)
			{
				$r['status_name'] = '已打回';
			}
			elseif ($r['status'] == 3)
			{
				$r['status_name'] = '已停用';
			}
			else 
			{
				$r['status_name'] = '待审核';
			}
			$r['update_time'] = date('Y-m-d H:i',$r['update_time']);
			$r['level'] = $this->settings['hospital_level'][$r['level']];
			$r['yibao_point'] = $r['yibao_point'] ? '医保定点' : '';
			
			if($r['logo'])
			{
				$r['logo'] = unserialize($r['logo']);
			}
			$r['pic'] = array(
				'host' 		=> $r['host'],
				'dir'		=> $r['dir'],
				'filepath'	=> $r['filepath'],
				'filename'	=> $r['filename'],
			);
			unset($r['host'],$r['dir'],$r['filepath'],$r['filename']);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "hospital SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."hospital SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "hospital WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "hospital SET ";
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
		
		$sql = "SELECT t1.*,t2.content FROM " . DB_PREFIX . "hospital t1 
				LEFT JOIN " . DB_PREFIX . "content t2
					ON t1.id = t2.cid 
				WHERE t1.id = {$id}";
		$info = $this->db->query_first($sql);
		
		//获取图片信息
		$sql = 'SELECT id,host,dir,filepath,filename,brief FROM '.DB_PREFIX."materials  WHERE 1 AND ctype=1 AND cid = {$id} ORDER BY id ASC";
		$q = $this->db->query($sql);
		
		$indexpic = array();
		while($row = $this->db->fetch_array($q))
		{			
			if($row['id'] == $info['indexpic_id'])
			{
				$indexpic = $row;
				continue;
			}	
			$info['pic_info'][] = $row;
		}
		
		//判断索引图
		if($indexpic)
		{
			$info['indexpic'] = $indexpic;
		}
		else
		{
			$info['indexpic'] = array();
		}
		
		if($info['telephone'])
		{
			$info['telephone'] = unserialize($info['telephone']);
		}
		
		if($info['logo'])
		{
			$info['logo'] = unserialize($info['logo']);
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "hospital WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "hospital WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $id;
	}
	
	
	public function audit($id = '',$audit)
	{
		if(!$id)
		{
			return false;
		}
		
		switch ($audit)
		{
			case 1:$status = 1;$audit_status = '已审核';break;//审核
			case 0:$status = 2;$audit_status = '已打回';break;//打回
		}
		
		$sql = " UPDATE " .DB_PREFIX. "hospital SET status = '" .$status. "' WHERE id IN ({$id})";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//插入素材表
	public function insert_material($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	
	//更新素材表
	public function update_material($cid,$data)
	{
		if (!is_array($data) || !$data || !$cid)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= " WHERE id = {$cid}";
		$this->db->query($sql);
	}
}
?>