<?php
class special_offer_product_mode extends InitFrm
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
		$sql = "SELECT so.*,m.img_info AS index_img,sa.start_time,sa.end_time FROM " . DB_PREFIX . "special_offer_product so LEFT JOIN " .DB_PREFIX. "material m ON so.index_img_id = m.id LEFT JOIN " . DB_PREFIX . "special_offer_activity sa ON sa.id = so.activity_id  WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			if($r['index_img'])
			{
				$r['index_pic'] = unserialize($r['index_img']);
			}
			else 
			{
				$r['index_pic'] = '';
			}
			$r['status_format'] = $this->settings['product_status'][$r['status']];
		    if($r['img_id'])
		    {
		    	$r['pics'] = $this->get_img_arr($r['img_id']);
		    }
		    else 
		    {
		    	$r['pics'] = '';
		    }
		    unset($r['index_img']);
		    $r['create_time'] = date('Y-m-d H:m:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:m:s',$r['update_time']);
			$r['start_time'] = date('Y-m-d H:m:s',$r['start_time']);
			$r['end_time'] = date('Y-m-d H:m:s',$r['end_time']);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "special_offer_product SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."special_offer_product SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		//每创建一个特惠商品,超市的特惠商品总数要加1
		$sql = " UPDATE " .DB_PREFIX. "supermarket SET total_product = total_product + 1 WHERE id = '" .$data['market_id']. "'";
		$this->db->query($sql);
		//每创建一个特惠商品，活动页面商品总数要加1
		$sql = " UPDATE " .DB_PREFIX. "special_offer_activity SET product_num = product_num + 1 WHERE id = '" .$data['activity_id']. "'";
		$this->db->query($sql);
		$data['id'] = $vid ;
		return $data;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "special_offer_product WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "special_offer_product SET ";
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
		
		$sql = "SELECT so.*,m.img_info AS index_img,ms.name AS product_sort_name FROM " . DB_PREFIX . "special_offer_product so LEFT JOIN " .DB_PREFIX. "material m ON so.index_img_id = m.id LEFT JOIN " .DB_PREFIX."market_product_sort ms ON ms.id = so.product_sort_id  WHERE so.id = '" .$id. "'";
		$r = $this->db->query_first($sql);
		$info = array();
		$r['index_img'] = hg_fetchimgurl(unserialize($r['index_img']),160);
		$r['status_format'] = $this->settings['product_status'][$r['status']];
		if($r['img_id'])
		{
			$r['img'] = $this->get_upload_img($r['img_id']);
		}
		$info[] = $r;
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "special_offer_product so WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "special_offer_product WHERE id IN (" . $id . ")";
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
		$recommend_num = 0;    //删除掉得推荐商品的总数
		if(is_array($pre_data))
		{
			foreach ($pre_data as $del_data)
			{
				$market_id = $del_data['market_id'];
				$activity_id = $del_data['activity_id'];
				if($del_data['is_recommend'] == 1)
				{
					$recommend_num ++; //如果是推荐商品，则推荐商品总数加1
				}
			}
		}
		else
		{
			$img_id = 0;
			$market_id = 0;
            $activity_ids = 0;
		}
		//删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "special_offer_product WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		//每删除一个特惠商品,超市的特惠商品总数要减1
		$del_num = count($pre_data);  //多个商品删除时，删除商品的总数量		
		$sql = " UPDATE " .DB_PREFIX. "supermarket SET total_product = total_product - " . $del_num . " , featured_product = featured_product - " . $recommend_num . " WHERE id in ( " . $market_id . ")";
		$this->db->query($sql);
		//每删除一个特惠商品，活动页面商品总数要减1
		$sql = " UPDATE " .DB_PREFIX. "special_offer_activity SET product_num = product_num - " . $del_num . "  WHERE id in (" . $activity_id. ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "special_offer_product WHERE id = '" .$id. "'";
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
		
		$sql = " UPDATE " .DB_PREFIX. "special_offer_product SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id,'status_format' => $this->settings['product_status'][$status]);
	}

	/**
	 * 获取多图的图片
	 * @param unknown_type $img_ids
	 */
	public function get_upload_img($img_ids)
	{
		if($img_ids)
		{
		    $sql = "SELECT img_info FROM " .DB_PREFIX. "material WHERE id in (" . $img_ids .')';
		    $query = $this->db->query($sql);
		    while($data = $this->db->fetch_array($query))
		    {
		    	$imgs[] = hg_fetchimgurl(unserialize($data['img_info']));
		    }
		    return $imgs;
		}
		else
		{
			return null;
		}
	}
	
	public function get_img_arr($ids = '')
	{
		$img_info = array();
		if(!$ids)
		{
			return $img_info;
		}
	 	$sql = " SELECT * FROM " .DB_PREFIX. "material WHERE id IN (" . $ids . ")";
	    $q = $this->db->query($sql);
	    
        while ($r = $this->db->fetch_array($q))
	    {
		    $img_info[] = unserialize($r['img_info']);
	    }
	    return $img_info;
	}
	
	public function recommendProduct($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "special_offer_product WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		switch (intval($pre_data['is_recommend']))
		{
			case 0:$recommend = 1;break;
			case 1:$recommend = 0;break;
		}
		
		$sql = " UPDATE " .DB_PREFIX. "special_offer_product SET is_recommend = '" .$recommend. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		//更新超市推荐商品的数目
		if($recommend)
		{
			$sql = "UPDATE " .DB_PREFIX. "supermarket SET featured_product = featured_product + 1 WHERE id = '" .$pre_data['market_id']. "'";
		}
		else 
		{
			$sql = "UPDATE " .DB_PREFIX. "supermarket SET featured_product = featured_product - 1 WHERE id = '" .$pre_data['market_id']. "'";
		}
		$this->db->query($sql);
		return array('is_recommend' => $recommend);
	}
}
?>