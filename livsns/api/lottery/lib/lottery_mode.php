<?php
class lottery_mode extends InitFrm
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
		include_once(CUR_CONF_PATH . 'lib/Xdeode.php');
		$this->script = new XDeode();
		$sql = "SELECT l.*,s.name as sort_name FROM " . DB_PREFIX . "lottery l 
				LEFT JOIN " . DB_PREFIX ."sort s 
					ON l.sort_id = s.id
				WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			
			$dir_file = $this->script->encode($r['user_id']);
	    	$filename = $this->script->encode($r['id']);
			$filepath = '../data/' . $r['create_time'] . $r['id'].'/'.$r['id'].'.html';
			if(file_exists(DATA_DIR.$dir_file.'/'.$filename.'.html') && $this->input['encryption'])
			{
				$r['url'] = LOTTERY_DOMAIN.$dir_file.'/'.$filename.'.html';
			}elseif(file_exists($filepath))
			{
				$r['url'] = LOTTERY_DOMAIN . $r['create_time'] . $r['id'].'/'.$r['id'].'.html';
			}
			else 
			{
				$r['url'] = '';
			}
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			
			
			if($r['start_time'] <= TIMENOW && $r['end_time'] >= TIMENOW)
			{
				$r['activ_status'] = 1;
			}
			elseif ($r['start_time'] > TIMENOW)
			{
				$r['activ_status'] = 0;
			}
			elseif ($r['end_time'] < TIMENOW)
			{
				$r['activ_status'] = 2;
			}
			
			if($this->settings['lottery_type'][$r['type']])
			{
				$r['type_name'] = $this->settings['lottery_type'][$r['type']];
			}
			$r['start_time'] = date('Y.m.d',$r['start_time']);
			$r['end_time'] = date('Y.m.d',$r['end_time']);
			
			if($r['time_limit'])
			{
				$r['effective_time'] = $r['start_time'] . '-' . $r['end_time'];
			}
			else 
			{
				$r['effective_time'] = '永久有效';
				$r['activ_status'] = 1;
			}
			
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "lottery SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."lottery SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "lottery WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "lottery SET ";
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
		
		$sql = "SELECT l.*,s.name as sort_name FROM " . DB_PREFIX . "lottery l 
				LEFT JOIN ".DB_PREFIX."sort s 
					ON l.sort_id = s.id 
				WHERE l.id = " . $id;
		$info = $this->db->query_first($sql);
		
		if($info['time_limit'])
		{
			$info['effective_time'] = date('Y.m.d',$info['start_time']) . '-' . date('Y.m.d',$info['end_time']);
		}
		else 
		{
			$info['effective_time'] = '永久有效';
		}
		
			
		if($info['start_time'])
		{
			$info['start_times'] = $info['start_time'];
			$info['start_time'] = date('Y-m-d',$info['start_time']);
		}
		if($info['end_time'])
		{
			$info['end_times'] = $info['end_time'];
			$info['end_time'] = date('Y-m-d',$info['end_time']);
		}
		
		
		if(isset($info['start_hour']))
		{
			$info['start_hours'] = $info['start_hour'] ? $info['start_hour'] : '0';
			$info['start_hour'] = $info['start_hour'] ? date('H:i:s', strtotime($info['start_hour'])) : '00:00:00';
		}
		if(isset($info['end_hour']))
		{
			$info['end_hours'] = $info['end_hour'] ? $info['end_hour'] : '0';
			$info['end_hour'] = $info['end_hour'] ? date('H:i:s', strtotime($info['end_hour'])) : '00:00:00';
		}
		
		if($info['register_time'])
		{
			$info['register_time'] = date('Y-m-d',$info['register_time']);
		}
		
		if($info['feedback'])
		{
			$info['feedback'] = unserialize($info['feedback']);
		}
		//获取图片信息
		$sql = 'SELECT id,host,dir,filepath,filename FROM '.DB_PREFIX."materials  WHERE cid = {$id} ORDER BY id ASC";
		$q = $this->db->query($sql);
		
		$indexpic = array();
		while($row = $this->db->fetch_array($q))
		{			
			if($row['id'] == $info['indexpic_id'])
			{
				$indexpic = $row;
				//continue;
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
		
		
		//查询未中奖反馈
		/*$sql = 'SELECT * FROM '.DB_PREFIX."feedback  WHERE lottery_id = {$id} ORDER BY id DESC";
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{			
			$info['feedback'][] = $row;
		}*/
		
		//查询奖项
		$sql = 'SELECT p.*,m.host,m.dir,m.filepath,m.filename FROM '.DB_PREFIX."prize p  
				LEFT JOIN " . DB_PREFIX . "materials m 
					ON p.indexpic_id = m.id 
				WHERE p.lottery_id = {$id} ORDER BY id ASC";
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{			
			$info['prize'][$row['id']] = $row;
		}
		
		//查询背景图
		$res = array();
		$sql = "SELECT host,dir,filepath,filename,id FROM " . DB_PREFIX . "materials WHERE id = " . $info['lottery_bg'];
		$res = $this->db->query_first($sql);
		
		if(!empty($res))
		{
			$info['lottery_bg'] = $res;
		}
		else 
		{
			$info['lottery_bg'] = array();
		}
		
				
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "lottery l WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "lottery WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "lottery WHERE id IN (" . $id . ")";
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
			case 1:$status = 1;$audit_status = '已审核';break;//审核
			case 0:$status = 2;$audit_status = '已打回';break;//打回
		}
		
		$sql = "UPDATE " .DB_PREFIX. "lottery SET status = '" .$status. "' WHERE id IN (" .$id. ")";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id,'audit'=>$audit_status);
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
	public function update_material($id,$data)
	{
		if (!is_array($data) || !$data || !$id)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= " WHERE id = {$id}";
		$this->db->query($sql);
		return $id;
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
}
?>