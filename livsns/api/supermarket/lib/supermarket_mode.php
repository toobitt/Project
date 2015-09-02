<?php
class supermarket_mode extends InitFrm
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
		$sql = "SELECT sm.*,m.img_info AS logo FROM " . DB_PREFIX . "supermarket sm LEFT JOIN " . DB_PREFIX . "material m ON m.id = sm.logo_id  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['logo'] = unserialize($r['logo']);
			$r['total_store'] = $this->get_total_store($r['id']);
			$r['create_time'] = date('Y-m-d H:m:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:m:s',$r['update_time']);
			$r['status_format'] = $this->settings['market_status'][$r['status']];
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "supermarket SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."supermarket SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
        $data['id'] = $vid;
        if($data['logo_id'])
        {
        	 $sql = " SELECT * FROM " . DB_PREFIX . "material WHERE id = '" .$data['logo_id']. "'";
        	 $img_info = $this->db->query_first($sql);
        	 $data['logo'] = unserialize($img_info['img_info']);
        }
       	$data['create_time'] = date('Y-m-d H:m:s',$data['create_time']);
		$data['update_time'] = date('Y-m-d H:m:s',$data['update_time']);
        return $data;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "supermarket WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		$old_logo = $pre_data['logo_id'];
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "supermarket SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		if($data['logo_id'] != $old_logo && $old_logo)		//删除更新前的旧的logo
		{
		    $sql = " DELETE FROM " .DB_PREFIX. "material WHERE id IN (" . $old_logo . ")"; 
		    $this->db->query($sql);			
		}
        $data = $this->detail($id);
		return $data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT s.*,m.img_info FROM " . DB_PREFIX . "supermarket s LEFT JOIN " .DB_PREFIX. "material m ON s.logo_id = m.id  WHERE s.id = '" .$id. "'";

		$info = $this->db->query_first($sql);
		$info['logo'] = hg_fetchimgurl(unserialize($info['img_info']),160);
		$info['create_time'] = date('Y-m-d H:m:s',$info['create_time']);
		$info['update_time'] = date('Y-m-d H:m:s',$info['update_time']);
		$info['status_format'] = $this->settings['market_status'][$info['status']];
		$info['total_store'] = $this->get_total_store($info['id']);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "supermarket sm WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "supermarket WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] = $r;
		}
		if(!$pre_data)
		{
			return false;
		}

		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "supermarket WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		//删除商店里的会员
		$sql = " DELETE FROM " .DB_PREFIX. "market_member WHERE market_id IN (" . $id . ")";
		$this->db->query($sql);
		//删除商店里的消息通知
		$sql = " DELETE FROM " .DB_PREFIX. "market_message WHERE market_id IN (" . $id . ")";
		$this->db->query($sql);
		//删除商店里的门户信息
		$sql = " DELETE FROM " .DB_PREFIX. "market_store WHERE market_id IN (" . $id . ")";
		$this->db->query($sql);
		//删除商店里的特惠活动
		$sql = " DELETE FROM " .DB_PREFIX. "special_offer_activity WHERE market_id IN (" . $id . ")";
		$this->db->query($sql);
		//删除商店里的特惠活动的产品
		$sql = " DELETE FROM " .DB_PREFIX. "special_offer_product WHERE market_id IN (" . $id . ")";
		$this->db->query($sql);
		
		return $pre_data;
	}
	
	public function audit($id = '',$op = '')
	{
		if(!$id)
		{
			return false;
		}
					
		//传过来的id必须是单个或者以逗号分隔
		$ids = explode(',',$id);
		if(!$ids)
		{
			return false;
		}

		//此处将多个与单个分开来处理(多个的情况下一定要传op指定是什么操作)
		if(count($ids) > 1)
		{
			if(!$op || !in_array($op,array(2,3)))
			{
				return false;
			}
			
			$sql = " UPDATE " .DB_PREFIX. "supermarket SET status = '" .$op. "' WHERE id IN (" .$id. ")";
			$this->db->query($sql);
			$status = $op;	
		}
		else 
		{
			//查询出原来
			$sql = " SELECT * FROM " .DB_PREFIX. "supermarket WHERE id = '" .$id. "'";
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
			
			$sql = " UPDATE " .DB_PREFIX. "supermarket SET status = '" .$status. "' WHERE id = '" .$id. "'";
			$this->db->query($sql);
		}
		return array('status' => $status,'id' => $id,'status_text' => $this->settings['market_status'][$status]);
	}
	
	public function get_logo($id)
	{
		if($id)
		{
		    $sql = " SELECT * FROM " .DB_PREFIX. "material WHERE id = " . $id ;
		    $data = $this->db->query_first($sql);
		    if(is_array($data))
		    {
		    	$ret = $data['img_info'];
		    }
		    $ret = hg_fetchimgurl(unserialize($ret),160);
		    return $ret;
		}
		else
		{
			return null;
		}
	}
	
	/**
	 * 
	 * 获取门店总数
	 * @param int $id
	 * @return $data['total']
	 */
	public function get_total_store($id){
		if($id)
		{
		    $sql = " SELECT count(*) as total FROM " .DB_PREFIX. "market_store WHERE market_id = " . $id ;
		    $data = $this->db->query_first($sql);
		    //exit($data['total']);
		    return $data['total'];
		}
		else
		{
			return null;
		}
	}

}
?>