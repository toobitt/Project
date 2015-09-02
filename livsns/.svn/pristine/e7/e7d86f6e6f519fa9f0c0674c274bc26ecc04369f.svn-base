<?php
class carpark_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$limit)
	{
		//查询出所有的服务时间
		$sql = "SELECT * FROM " .DB_PREFIX. "server_time";
		$q = $this->db->query($sql);
		$server_time = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['server_type_name'] = $this->settings['server_time_type'][$row['server_type']];
			$server_time[$row['carpark_id']][] = $row;
		}
		
		//查询出收费标准
		$sql = "SELECT * FROM " .DB_PREFIX. "collect_fees";
		$q = $this->db->query($sql);
		$collect_fees = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['fees_type_name'] 		= $this->settings['collect_fees_type'][$row['fees_type']];
			$row['car_type_name']  		= $this->settings['car_type'][$row['car_type']];
			$row['charge_unit_name']  	= $this->settings['charge_unit'][$row['charge_unit']];
			$collect_fees[$row['carpark_id']][] = $row;
		}
		
		//查询出停车场
		$sql = "SELECT c.*,cd.name AS district_name,ct.map_marker,ct.name AS type_name FROM " . DB_PREFIX . "carpark c LEFT JOIN " .DB_PREFIX. "carpark_district cd ON cd.id = c.district_id LEFT JOIN " .DB_PREFIX. "carpark_type ct ON ct.id = c.type_id WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['map_marker'] = hg_fetchimgurl(unserialize($r['map_marker']),40,30);
			$r['status'] = $this->settings['carpark_status'][$r['status']];
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			if($server_time[$r['id']])
			{
				$r['server_time'] = $server_time[$r['id']];
			}
			else 
			{
				$r['server_time'] = '';
			}
			if($collect_fees[$r['id']])
			{
				$r['fees'] = $collect_fees[$r['id']];
			}
			else 
			{
				$r['fees'] = '';
			}
			$r['business_time'] = $r['business_time']?@unserialize($r['business_time']):array();
			
			//算出当前时间的星期(判断当前的停车场是否在营业中)
			$r['is_business'] = 0;
			$current_date = intval(date('w',TIMENOW));
			$dateArr = array();
			foreach ($r['business_time'] AS $_kk => $_vv)
			{
				$r['business_time'][$_kk]['week'] = $this->settings['week'][$_vv['date']];
				if($current_date == intval($_vv['date']))
				{
					$bb_stime = strtotime(date('2013-01-01 ' . $_vv['stime'] . ':00'));
					$bb_etime = strtotime(date('2013-01-01 ' . $_vv['etime'] . ':00'));
					$c_time = strtotime(date('2013-01-01 ' . date('H:i',TIMENOW) . ':00'));
					if($c_time >= $bb_stime && $c_time <= $bb_etime)
					{
						$r['is_business'] = 1;
					}
				}
			}
			$info[] = $r;
		}
		
		//如果查询的是单条信息就将实景图片输出来
		if($info && count($info) == 1)
		{
			if($info[0]['id'])
			{
				//查询出实景图片
				$sql = "SELECT * FROM " . DB_PREFIX . "real_picture  WHERE carpark_id = '" .$info[0]['id']. "'";
				$q = $this->db->query($sql);
				$real_picture = array();
				while ($r = $this->db->fetch_array($q))
				{
					$real_picture[] = unserialize($r['img']);
				}
				$info[0]['real_pic'] = $real_picture;
				
				//将结构形式输出来
				$struct_type = $info[0]['struct_type'];
				 $info[0]['struct_type_name'] = '';
				if($struct_type)
				{
					$struct_type_arr = explode(',',$struct_type);
					$space = '';
					foreach ($struct_type_arr AS $k => $v)
					{
						 $info[0]['struct_type_name'] .= $space . $this->settings['struct_type'][$v];
						 $space = ',';
					}
				}
				
				//格式化一些数据
				$info[0]['entrance_num'] 		=  '入口' . $info[0]['entrance_num'] . '个';
				$info[0]['exitus_num'] 	 		=  '出口' . $info[0]['exitus_num'] . '个';
				$info[0]['limited_height'] 	 	=  '出入口限高' . $info[0]['limited_height'] . '米';
				$info[0]['building_storey'] 	=  '共' . $info[0]['building_storey'] . '层';
				$info[0]['other_struct_type'] 	=  '其他结构' . $info[0]['other_struct_type'];
			}
		}
		return $info;
	}
	
	public function create($data = array(),$server_time_data = array(),$collect_fees = array(),$photo = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "carpark SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."carpark SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		
		//更新区域停车场的数量
		if($data['district_id'])
		{
			$sql = " UPDATE " .DB_PREFIX. "carpark_district SET carpark_num = carpark_num + 1 WHERE id = '" .$data['district_id']. "'";
			$this->db->query($sql);
		}
		
		//服务时间
		if($server_time_data)
		{
			foreach($server_time_data AS $k => $v)
			{
				$v['carpark_id'] = $vid;
				$sql = " INSERT INTO " . DB_PREFIX . "server_time SET ";
				foreach ($v AS $kk => $vv)
				{
					$sql .= " {$kk} = '{$vv}',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
			}
		}
		
		//收费信息
		if($collect_fees)
		{
			foreach($collect_fees AS $k => $v)
			{
				$v['carpark_id'] = $vid;
				$sql = " INSERT INTO " . DB_PREFIX . "collect_fees SET ";
				foreach ($v AS $kk => $vv)
				{
					$sql .= " {$kk} = '{$vv}',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
			}
		}
		
		if($photo)
		{
			foreach($photo AS $k => $v)
			{
				$sql = "UPDATE " . DB_PREFIX .  "real_picture SET carpark_id = '" .$vid. "' WHERE id = '" .$v. "'";
				$this->db->query($sql);
			}
		}
		return true;
	}
	
	public function update($id,$data = array(),$server_time_data = array(),$collect_fees = array(),$photo = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "carpark WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新主表数据
		$sql = " UPDATE " . DB_PREFIX . "carpark SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		
		/****************************更新区域停车场的数量*****************************/
		if($pre_data['district_id'] != $data['district_id'])
		{
			if($pre_data['district_id'])
			{
				$sql = " UPDATE " .DB_PREFIX. "carpark_district SET carpark_num = carpark_num - 1 WHERE id = '" .$pre_data['district_id']. "'";
				$this->db->query($sql);
			}
			
			if($data['district_id'])
			{
				$sql = " UPDATE " .DB_PREFIX. "carpark_district SET carpark_num = carpark_num + 1 WHERE id = '" .$data['district_id']. "'";
				$this->db->query($sql);
			}
		}
		
		/****************************更新$server_time_data*************************/
		//先把原来的给删掉
		$sql = " DELETE FROM " .DB_PREFIX. "server_time WHERE carpark_id = '" .$id. "'";
		$this->db->query($sql);
		if($server_time_data)
		{
			//再插数据
			foreach($server_time_data AS $k => $v)
			{
				$v['carpark_id'] = $id;
				$sql = " INSERT INTO " . DB_PREFIX . "server_time SET ";
				foreach ($v AS $kk => $vv)
				{
					$sql .= " {$kk} = '{$vv}',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
			}
		}
		
		/****************************更新收费标准*************************/
		//先把原来的给删掉
		$sql = " DELETE FROM " .DB_PREFIX. "collect_fees WHERE carpark_id = '" .$id. "'";
		$this->db->query($sql);
		if($collect_fees)
		{
			foreach($collect_fees AS $k => $v)
			{
				$v['carpark_id'] = $id;
				$sql = " INSERT INTO " . DB_PREFIX . "collect_fees SET ";
				foreach ($v AS $kk => $vv)
				{
					$sql .= " {$kk} = '{$vv}',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
			}
		}
		
		if($photo)
		{
			$sql = " SELECT * FROM " .DB_PREFIX. "real_picture WHERE id IN (" .implode(',',$photo). ") AND carpark_id = 0 ";
			$need_update_ids = array();
			$q = $this->db->query($sql);
			while($r = $this->db->fetch_array($q))
			{
				$need_update_ids[] = $r['id'];
			}
			
			if($need_update_ids)
			{
				foreach($need_update_ids AS $k => $v)
				{
					$sql = "UPDATE " . DB_PREFIX .  "real_picture SET carpark_id = '" .$id. "' WHERE id = '" .$v. "'";
					$this->db->query($sql);
				}
			}
		}
		
		return $pre_data;
	}
	
	public function detail($id = '')
	{
		if(!$id)
		{
			return false;
		}
		//先查询出服务时间
		$sql = "SELECT * FROM " . DB_PREFIX . "server_time  WHERE carpark_id = '" .$id. "'  ORDER BY id ASC";
		$q = $this->db->query($sql);
		$server_time = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['name'] = $this->settings['server_time_type'][$r['server_type']];
			$server_time[] = $r;
		}
		
		//查出收费标准
		$sql = "SELECT * FROM " . DB_PREFIX . "collect_fees  WHERE carpark_id = '" .$id. "' ORDER BY id ASC";
		$q = $this->db->query($sql);
		$collect_fees = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['name'] = $this->settings['collect_fees_type'][$r['fees_type']];
			$collect_fees[] = $r;
		}
		
		//查询出停车场信息
		$sql = "SELECT c.*,ct.city AS city_name FROM " . DB_PREFIX . "carpark c LEFT JOIN " .DB_PREFIX. "city ct ON ct.id = c.city_id WHERE c.id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		if(!$info)
		{
			return false;
		}
		
		//查询出实景图片
		$sql = "SELECT * FROM " . DB_PREFIX . "real_picture  WHERE carpark_id = '" .$id. "'";
		$q = $this->db->query($sql);
		$real_picture = array();
		while ($r = $this->db->fetch_array($q))
		{
			$real_picture[] = array('img' => hg_fetchimgurl(unserialize($r['img']),400),'id' => $r['id']);
		}
		//营业时间
		$info['business_time'] = $info['business_time']?@unserialize($info['business_time']):array();

		//算出当前时间的星期(判断当前的停车场是否在营业中)
		$info['is_business'] = 0;
		$current_date = intval(date('w',TIMENOW));
		$dateArr = array();
		$info['business_time_arr'] = array();
		foreach ($info['business_time'] AS $_kk => $_vv)
		{
			$info['business_time_arr'][$_vv['date']] = $_vv;
			if($current_date == intval($_vv['date']))
			{
				$bb_stime = strtotime(date('2013-01-01 ' . $_vv['stime'] . ':00'));
				$bb_etime = strtotime(date('2013-01-01 ' . $_vv['etime'] . ':00'));
				$c_time = strtotime(date('2013-01-01 ' . date('H:i',TIMENOW) . ':00'));
				if($c_time >= $bb_stime && $c_time <= $bb_etime)
				{
					$info['is_business'] = 1;
				}
			}
		}

		$info['photo'] = $real_picture;
		$info['now_city_data'] = $this->show_city($info['province_id']);
		$info['now_area_data'] = $this->show_area($info['city_id']);
		$info['server_time'] = $server_time;
		$info['collect_fees'] = $collect_fees;
		$info['struct_type_arr'] = explode(',',$info['struct_type']);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "carpark c WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id)
	{
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "carpark WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		$ids = array();
		$district_ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
			$ids[] 			= $r['id'];
			$district_ids[] = $r['district_id'];
		}
		if(!$pre_data)
		{
			return false;
		}
		//先删除主表
		$sql = " DELETE FROM " .DB_PREFIX. "carpark WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		$sql = " DELETE FROM " .DB_PREFIX. "server_time WHERE carpark_id IN (" . $id . ")";
		$this->db->query($sql);
		$sql = " DELETE FROM " .DB_PREFIX. "collect_fees WHERE carpark_id IN (" . $id . ")";
		$this->db->query($sql);
		//删除该停车场对应的公告
		$sql = " DELETE FROM " .DB_PREFIX. "announcement WHERE carpark_id IN (" . $id . ")";
		$this->db->query($sql);
		//更新该停车场所属区域的停车场数量
		foreach($district_ids AS $k => $v)
		{
			$sql = " UPDATE " .DB_PREFIX. "carpark_district SET carpark_num = carpark_num - 1 WHERE id = '" .$v. "'";
			$this->db->query($sql);
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
		$sql = " SELECT * FROM " .DB_PREFIX. "carpark WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		switch (intval($pre_data['status']))
		{
			case 1:$status = 2;break;//审核
			case 2:$status = 3;break;//打回
			case 3:$status = 2;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "carpark SET status = '" .$status. "' WHERE id = '" .$id. "'";
		$this->db->query($sql);
		return array('status' => $this->settings['carpark_status'][$status],'id' => $id);
	}
	
	public function show_province()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "province";
		$q = $this->db->query($sql);
		$province = array();
		while ($r = $this->db->fetch_array($q))
		{
			$province[] = $r;
		}
		return $province;
	}
	
	public function show_city($province_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "city WHERE province_id = '" .$province_id. "'";
		$q = $this->db->query($sql);
		$city = array();
		while ($r = $this->db->fetch_array($q))
		{
			$city[] = $r;
		}
		return $city;
	}
	
	public function show_area($city_id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "area WHERE city_id = '" .$city_id. "'";
		$q = $this->db->query($sql);
		$area = array();
		while ($r = $this->db->fetch_array($q))
		{
			$area[] = $r;
		}
		return $area;
	}
	
	//插入图片数据
	public function insert_img($data)
	{
		$sql = " INSERT INTO " . DB_PREFIX . "real_picture SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	//删除实景图片
	public function delete_real_img($id)
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = " DELETE FROM " .DB_PREFIX. "real_picture WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return true;
	}
	
	//按区域输出停车场列表
	public function get_carpark_by_district($city_name,$wd = '',$jd = '',$type_id='')
	{
		//查询出城市id
		$sql = "SELECT * FROM " .DB_PREFIX. "carpark_district WHERE name = '" .$city_name. "'";
		$city_arr = $this->db->query_first($sql);
		if(!$city_arr)
		{
			return false;
		}

		//查询出该城市里面的区域
		$sql = "SELECT * FROM " .DB_PREFIX. "carpark_district WHERE fid = '" .$city_arr['id']. "'";
		$q = $this->db->query($sql);
		$district = array();
		$district_ids = array();
		while($r = $this->db->fetch_array($q))
		{
			$district[$r['id']] = $r;
			$district_ids[] = $r['id'];
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "carpark WHERE status=2 AND district_id IN (" .implode(',',$district_ids). ")";
		
		//如果类型id存在，连上类型id
		if($type_id)
		{
			$sql .= " AND type_id = " . $type_id;
		}
		
		$q = $this->db->query($sql);
		$carpark = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['district_name'] = $district[$r['district_id']]['name'];
			$r['carpark_num'] 	= $district[$r['district_id']]['carpark_num'];
			if($wd && $jd)
			{
				$r['distance'] 	= GetDistance($r['GPS_y'],$r['GPS_x'],$wd,$jd);
			}
			$carpark[$r['district_id']][$r['id']] = $r;
		}
		
		//如果存在当前的经纬度（GPS）,算出某个区域内最近的停车场
		$carpark_nearest = array();
		if($wd && $jd)
		{
			foreach($carpark AS $k => $v)
			{
				$_k = 0;//记录最近的停车场id
				$flag = 1;
				$_distance_tmp = 0;
				foreach ($v AS $kk => $vv)
				{
					if(intval($vv['distance']) < $_distance_tmp || $flag)
					{
						$_distance_tmp = intval($vv['distance']);
						$_k = $vv['id'];
					}
					$flag = 0;
				}
				$v[$_k]['distance_format'] = distance_change_unit($v[$_k]['distance']);
				$carpark_nearest[] = $v[$_k];
			}
		}
		//如果存在返回给个区域最近的停车场，不存在就返回所有区里面所有的停车场
		if($carpark_nearest)
		{
			return $carpark_nearest;
		}
		else 
		{
			return $carpark;
		}
	}
}
?>