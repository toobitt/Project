<?php
class verify_code_mode extends InitFrm
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
		//$sql = "SELECT * FROM " . DB_PREFIX . "verify  WHERE 1 " . $condition . $orderby . $limit;
		$sql = "SELECT v.*,n.name AS type FROM " . DB_PREFIX . "verify v LEFT JOIN " . DB_PREFIX . "verify_node n ON n.id=v.type_id WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "verify SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."verify SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "verify WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "verify SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		$row = $this->db->affected_rows();
		if($row)
		{
			if($pre_data['bgpicture_id'] != $data['bgpicture_id'])//背景图片换掉了
			{
				$sql = "SELECT * FROM " .DB_PREFIX. "verify WHERE bgpicture_id=".$pre_data['bgpicture_id'];
				$r = $this->db->query_first($sql);
				if($r) //原来的图片仍然被其他验证码使用
				{
					$sql = "UPDATE " .DB_PREFIX. "bgpicture SET is_using=1 WHERE id=" .$data['bgpicture_id'];
					$this->db->query($sql);
				}
				else //原来的图片不被使用了
				{
					$sql_one = "UPDATE " .DB_PREFIX. "bgpicture SET is_using=1 WHERE id=" .$data['bgpicture_id'];
					$sql_two = "UPDATE " .DB_PREFIX. "bgpicture SET is_using=0 WHERE id=" .$pre_data['bgpicture_id'];
					$this->db->query($sql_one);
					$this->db->query($sql_two);
				}
			}
			if($pre_data['fontface_id'] != $data['fontface_id'])//字体换掉了
			{
				$sql = "SELECT * FROM " .DB_PREFIX. "verify WHERE fontface_id=".$pre_data['fontface_id'];
				$r = $this->db->query_first($sql);
				if($r) //原来的字体仍然被其他验证码使用
				{
					$sql = "UPDATE " .DB_PREFIX. "font SET is_using=1 WHERE id=" .$data['fontface_id'];
					$this->db->query($sql);
				}
				else //原来的字体不被使用了
				{
					$sql_one = "UPDATE " .DB_PREFIX. "font SET is_using=1 WHERE id=" .$data['fontface_id'];
					$sql_two = "UPDATE " .DB_PREFIX. "font SET is_using=0 WHERE id=" .$pre_data['fontface_id'];
					$this->db->query($sql_one);
					$this->db->query($sql_two);
				}
			}
			
		}
		return $row;
	}
	
	public function detail($id = '',$condition= '')
	{
		if(!$id)
		{
			return false;
		}
		
		//$sql = "SELECT * FROM " . DB_PREFIX . "verify  WHERE id = '" .$id. "'";
		
		$sql = "SELECT v.*,f.name AS font_name,p.name AS bg_pic,p.type AS pic_type FROM " . DB_PREFIX . "verify v 
		 	LEFT JOIN " . DB_PREFIX . "font f ON v.fontface_id=f.id 
		 	LEFT JOIN " . DB_PREFIX . "bgpicture p ON v.bgpicture_id=p.id 
		 	WHERE 1 ". $condition;//.id = '". $id ."'";
		$info = $this->db->query_first($sql);
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "verify v WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "verify WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			if($r['is_default'])
			{
				return 'is_default';
			}
			$pre_data[] 	= $r;
			if($r['bgpicture_id'])
			{
				$bgpicture_id[] = $r['bgpicture_id']; //所有要删除的验证码所使用的背景图片id
			}
			$fontface_id[] = $r['fontface_id']; //被删除的验证码所使用的字体id 
		}
		if(!$pre_data)
		{
			return false;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "verify WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		$sql = "SELECT bgpicture_id,fontface_id FROM " .DB_PREFIX. "verify";// WHERE bgpicture_id=1";//=".$pre_data['bgpicture_id'];
		$q = $this->db->query($sql);
		$bgpicture_id_now = array();
		while ($r = $this->db->fetch_array($q))
		{
			if($r['bgpicture_id'])
			{
				$bgpicture_id_now[] 	= $r['bgpicture_id'];
			}
			$fontface_id_now[] = $r['fontface_id'];
		}
		$bgpicture_id = array_unique($bgpicture_id); //要删除的背景图片id
		$bgpicture_id_now = array_unique($bgpicture_id_now); //目前正在使用的背景图片id
		$fontface_id = array_unique($fontface_id); //要删除的字体id
		$fontface_id_now = array_unique($fontface_id_now); //目前正在使用的字体id
		
		if($fontface_id && $fontface_id_now)
		{
			$fontface_id = implode(',', $fontface_id);
			$fontface_id_now = implode(',', $fontface_id_now);
			$sql_one = "UPDATE " .DB_PREFIX. "font SET is_using=0 WHERE id IN (" . $fontface_id . ")";
			$sql_two = "UPDATE " .DB_PREFIX. "font SET is_using=1 WHERE id IN (" . $fontface_id_now . ")";//=" .$pre_data['bgpicture_id'];
			$this->db->query($sql_one);
			$this->db->query($sql_two);
		}
		if($bgpicture_id)
		{
			$bgpicture_id = implode(',', $bgpicture_id);
			$bgpicture_id_now = implode(',', $bgpicture_id_now);
			$sql_one = "UPDATE " .DB_PREFIX. "bgpicture SET is_using=0 WHERE id IN (" . $bgpicture_id . ")";
			$this->db->query($sql_one);
			if($bgpicture_id_now)
			{
				$sql_two = "UPDATE " .DB_PREFIX. "bgpicture SET is_using=1 WHERE id IN (" . $bgpicture_id_now . ")";//=" .$pre_data['bgpicture_id'];
				$this->db->query($sql_two);
			}
		}
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "verify WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		/**********************************以下状态只是示例，根据情况而定************************************/
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "verify SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
}
?>