<?php
class special_offer_mode extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "special_offer_activity  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			//根据开始时间与结束时间计算出显示状态
			$diffWithStart = intval(TIMENOW - $r['start_time']);
			$diffWithEnd   = intval(TIMENOW - $r['end_time'] - 24 * 3600);
			
			//如果小于0说明还没开始
			if($diffWithStart <= 0)
			{
				$status = 1;//即将开始
			}
			
			if($diffWithEnd >= 0)
			{
				$status = 3;//已经结束
			}
			
			if($diffWithStart > 0 && $diffWithEnd < 0)
			{
				$status = 2;//活动中
			}
			$r['status'] = $status;
			$r['status_format'] = $this->settings['activity_status'][$status];
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "special_offer_activity SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."special_offer_activity SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "special_offer_activity WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "special_offer_activity SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "special_offer_activity  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		$info['start_time'] = date('Y-m-d',$info['start_time']);
		$info['end_time'] 	= date('Y-m-d',$info['end_time']);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "special_offer_activity WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "special_offer_activity WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "special_offer_activity WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		//获取删除活动里的商品数量
		$sql  = "SELECT count(*) AS tot FROM ".DB_PREFIX. "special_offer_product  WHERE activity_id IN (" . $id . ")";
		$tot  = $this->db->query_first($sql);
		$sql .= " AND is_recommend = 1 ";
		$ftot = $this->db->query_first($sql);
		$product_total = $tot['tot'];          //删除活动里的商品数量
		$featured_total = $ftot['tot'];        //删除活动里的推荐商品数量
		
		//删除活动里的所有商品
		$sql = " DELETE FROM " .DB_PREFIX. "special_offer_product WHERE activity_id IN (" . $id . ")";
		$this->db->query($sql);
		
		$sql = " UPDATE " .DB_PREFIX. "supermarket SET total_product = total_product - " . $product_total . " , featured_product = featured_product - " . $featured_total . " WHERE id in ( " . $pre_data[0]['market_id'] . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	/*******************************************扩展操作***************************************************/
	public function get_market_store($market_id = '')
	{
		if(!$market_id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "market_store WHERE market_id = '" .$market_id. "'";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$info[] = $r;
		}
		return $info;
	}
	/*******************************************扩展操作***************************************************/
}
?>