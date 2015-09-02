<?php
class cinema_mode extends InitFrm
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
		$sql = "SELECT c.*,co.*,m.img_info FROM " . DB_PREFIX . "cinema c 
				LEFT JOIN " .DB_PREFIX. "content co 
					ON c.id=co.cinema_id  
				LEFT JOIN " . DB_PREFIX . "material m
					ON c.indexpic = m.id 
				WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			switch ($r['status'])
			{
				case 0:$r['audit'] = '待审核';break;//审核
				case 1:$r['audit'] = '已审核';break;//审核
				case 2:$r['audit'] = '已打回';break;//打回
			}
			if($r['img_info'])
			{
				$r['img_info'] = hg_fetchimgurl(unserialize($r['img_info']),'128','128');
			}
			
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
			$r['stime'] = date('H:i',$r['stime']);
			$r['etime'] = date('H:i',$r['etime']);
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			//$r['release_time'] = date('Y-m-d H:i:s',$r['release_time']);
			$r['content'] = stripslashes($r['content']);
			$r['tel'] = @unserialize($r['tel']);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "cinema SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."cinema SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "cinema WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "cinema SET ";
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
		
		$sql = "SELECT c.*,co.*,m.img_info FROM " . DB_PREFIX . "cinema c 
				LEFT JOIN " .DB_PREFIX. "content co 
					ON c.id=co.cinema_id 
				LEFT JOIN " . DB_PREFIX . "material m
					ON c.indexpic = m.id 
				WHERE c.id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		if($info)
		{
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
			$info['stime'] = date('H:i',$info['stime']);
			$info['etime'] = date('H:i',$info['etime']);
			//['create_time'] = date('Y-m-d H:i:s',$info['create_time']);
			//$info['release_time'] = date('Y-m-d H:i:s',$info['release_time']);
			$info['content'] = stripslashes($info['content']);
			if($info['img_info'])
			{
				$tmp = unserialize($info['img_info']);
				$info['img_info'] = $tmp['host'].$tmp['dir'].$tmp['filepath'].$tmp['filename'];
			}
			if($info['tel'])
			{
				$info['tel'] = unserialize($info['tel']);
			}
		}
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "cinema WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "cinema WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "cinema WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '',$audit)
	{
		if(!$id)
		{
			return false;
		}
		
		switch ($audit)
		{
			case 0:$status = 1;$audit_status = '已审核';break;//审核
			case 1:$status = 2;$audit_status = '已打回';break;//打回
			case 2:$status = 1;$audit_status = '已审核';break;//打回
		}
		
		$sql = " UPDATE " .DB_PREFIX. "cinema SET status = '" .$status. "' WHERE id IN (" .$id. ")";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id,'audit'=>$audit_status);
	}
	
	//百度坐标转换为GPS坐标
	public function FromBaiduToGpsXY($x,$y)
	{
	    $Baidu_Server = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
	    $result = @file_get_contents($Baidu_Server);
	    $json = json_decode($result);  
	    if($json->error == 0)
	    {
	        $bx = base64_decode($json->x);     
	        $by = base64_decode($json->y);  
	        $GPS_x = 2 * $x - $bx;  
	        $GPS_y = 2 * $y - $by;
	        return array('GPS_x' => $GPS_x,'GPS_y' => $GPS_y);//经度,纬度
	    }
	    else
	    {
	    	return false;//转换失败
	    }
	}
	
	//GPS坐标转换为百度坐标
	public function FromGpsToBaiduXY($x,$y)
	{
		$url = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$info = json_decode($response,1);
		if($info && !$info['error'])
		{
			unset($info['error']);
			$info['x'] = base64_decode($info['x']);
			$info['y'] = base64_decode($info['y']);
			return $info;
		}
	}
	
	/*
	 * 获取排片信息
	 */
	public function get_project_info($cinema_id = '', $movie_id = '')
	{
		if(!$movie_id)
		{
			return false;
		}
		if(!$cinema_id)
		{
			return false;
		}
		//查出影院信息
		//$sql = "SELECT * FROM " .DB_PREFIX. "cinema WHERE id = " .$cinema_id;
		$sql = "SELECT c.*,co.* FROM " . DB_PREFIX . "cinema c LEFT JOIN " .DB_PREFIX. "content co ON c.id=co.cinema_id  WHERE c.id = " .$cinema_id;
		$cimema_info = $this->db->query_first($sql);
		$cimema_info['stime'] = date('H:i',$cimema_info['stime']);
		$cimema_info['etime'] = date('H:i',$cimema_info['etime']);
		$cimema_info['create_time'] = date('Y-m-d H:i:s',$cimema_info['create_time']);
		$cimema_info['release_time'] = date('Y-m-d H:i:s',$cimema_info['release_time']);
		$cimema_info['content'] = stripslashes($cimema_info['content']);
		//查出影片信息
		$sql = "SELECT * FROM " .DB_PREFIX. "movie WHERE id = " .$movie_id;
		$movie_info = $this->db->query_first($sql);
		$movie_info['create_time'] = date('Y-m-d H:i:s',$movie_info['create_time']);
		$movie_info['release_time'] = date('Y-m-d',$movie_info['release_time']);
		$movie_info['update_time'] = date('Y-m-d H:i:s',$movie_info['update_time']);
		//查出排片信息
		$sql = "SELECT * FROM " .DB_PREFIX. "project_list WHERE cinema_id = " .$cinema_id. " AND movie_id = " .$movie_id. " ORDER BY project_time ASC";
		$query = $this->db->query($sql);	
		while($row = $this->db->fetch_array($query))
		{
			$row['create_time'] = date('Y-m-d',$row['create_time']);
			$row['update_time'] = date('Y-m-d',$row['update_time']);
			$row['project_time'] = date('H:i',$row['project_time']);
			$info['project_info'][$row['dates']][] = $row;
		}
		$info['cimema_info'] = $cimema_info;
		$info['movie_info'] = $movie_info;
		
		return $info;
	}
	
	
	
	
}
?>