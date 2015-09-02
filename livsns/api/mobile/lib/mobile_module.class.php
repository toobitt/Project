<?php 
/***************************************************************************

* $Id: mobile_module.class.php 11744 2012-09-22 09:24:58Z lijiaying $

***************************************************************************/
class mobileModule extends InitFrm
{
	private $mMaterial;
	public function __construct()
	{
		parent::__construct();
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	function show($condition, $offset, $count, $order = 'ASC')
	{
		$offset = $offset ? $offset : 0;
		$count = $count ? $count : 25;
		$orderby = " ORDER BY order_id {$order} ";
		$limit = " LIMIT " . $offset . " , " . $count;
		
		$sql = "SELECT m.*,s.name as sort_name FROM " . DB_PREFIX . "mobile_module m 
					LEFT JOIN ".DB_PREFIX."module_sort s 
				ON m.sort_id = s.id ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['url'] = html_entity_decode($row['url']);
			$row['create_time'] 	= date('Y-m-d H:i', $row['create_time']);
			$row['update_time'] 	= date('Y-m-d H:i', $row['update_time']);
			$row['type_name'] 		= $this->settings['module_type'][$row['type']];
			//版本对应的url
			if($row['version_url'])
			{
				$row['version_url']	= unserialize($row['version_url']);
			}
			//事件
			if($row['event'])
			{
				$row['event']	= unserialize($row['event']);
			}
			//
			if($row['icon1'])
			{
				$row['icon1'] = unserialize($row['icon1']);
			}
			
			if($row['icon2'])
			{
				$row['icon2'] = unserialize($row['icon2']);
			}
			
			if($row['icon3'])
			{
				$row['icon3'] = unserialize($row['icon3']);
			}
			
			if($row['icon4'])
			{
				$row['icon4'] = unserialize($row['icon4']);
			}
			$info[] = $row;
		}
		return $info;
	}

	public function detail($id)
	{
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN (' . $id .')';
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "mobile_module " . $condition;		
		$row = $this->db->query_first($sql);
		
			
		$sql = "SELECT * FROM " . DB_PREFIX . "mobile_module_confine WHERE module_id=".$id;
		$q = $this->db->query($sql);
		while($res = $this->db->fetch_array($q))
		{
			$row['confine'][$res['app_id']]['version'] = $res['version'];
			$row['confine'][$res['app_id']]['version_max'] = $res['version_max'];
			
			if($res['icon1'])
			{
				$res['icon1'] = unserialize($res['icon1']);
				$res['icon1'] = $res['icon1']['host'].$res['icon1']['dir'].$res['icon1']['filepath'].$res['icon1']['filename'];
				$row['confine'][$res['app_id']]['app_icon1'] = $res['icon1'];
			}
			elseif ($res['host'])
			{
				$url_icon = $res['host'].$res['dir'].$res['filepath'].$res['filename'];
					
				$row['confine'][$res['app_id']]['app_icon1'] = $url_icon;
			}
			if($res['icon2'])
			{
				$res['icon2'] = unserialize($res['icon2']);
				$res['icon2'] = $res['icon2']['host'].$res['icon2']['dir'].$res['icon2']['filepath'].$res['icon2']['filename'];
				$row['confine'][$res['app_id']]['app_icon2'] = $res['icon2'];
			}
			if($res['icon3'])
			{
				$res['icon3'] = unserialize($res['icon3']);
				$res['icon3'] = $res['icon3']['host'].$res['icon3']['dir'].$res['icon3']['filepath'].$res['icon3']['filename'];
				$row['confine'][$res['app_id']]['app_icon3'] = $res['icon3'];
			}
			if($res['icon4'])
			{
				$res['icon4'] = unserialize($res['icon4']);
				$res['icon4'] = $res['icon4']['host'].$res['icon4']['dir'].$res['icon4']['filepath'].$res['icon4']['filename'];
				$row['confine'][$res['app_id']]['app_icon4'] = $res['icon4'];
			}
			/*if($res['host'])
			{
				$url_icon = $res['host'].$res['dir'].$res['filepath'].$res['filename'];
					
				$row['confine'][$res['app_id']]['icon'] = $url_icon;
			}*/
		}
		
		if(is_array($row) && $row)
		{
			//版本对应的url
			if($row['version_url'])
			{
				$row['version_url'] = unserialize($row['version_url']);
			}
			
			//事件
			if($row['event'])
			{
				$row['event'] = unserialize($row['event']);
			}
			$row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s', $row['update_time']);
			$row['img_url'] = $row['host'].$row['dir'].$row['filepath'].$row['filename'];
			
			//
			if($row['icon1'])
			{
				$row['icon1'] = unserialize($row['icon1']);
				$row['icon1'] = $row['icon1']['host'].$row['icon1']['dir'].$row['icon1']['filepath'].$row['icon1']['filename'];
			}
			else 
			{
				$row['icon1'] = $row['img_url'];
			}
			
			if($row['icon2'])
			{
				$row['icon2'] = unserialize($row['icon2']);
				$row['icon2'] = $row['icon2']['host'].$row['icon2']['dir'].$row['icon2']['filepath'].$row['icon2']['filename'];
			}
			
			if($row['icon3'])
			{
				$row['icon3'] = unserialize($row['icon3']);
				$row['icon3'] = $row['icon3']['host'].$row['icon3']['dir'].$row['icon3']['filepath'].$row['icon3']['filename'];
			}
			
			if($row['icon4'])
			{
				$row['icon4'] = unserialize($row['icon4']);
				$row['icon4'] = $row['icon4']['host'].$row['icon4']['dir'].$row['icon4']['filepath'].$row['icon4']['filename'];
			}
			return $row;
		}

		return false;
	}
	
	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "mobile_module WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}

	public function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND m.name LIKE \'%' . urldecode($this->input['k']) . '%\'';
		}
		
		if(isset($this->input['status']) && urldecode($this->input['status'])!= -1)
		{
			$condition .= " AND m.status = '".urldecode($this->input['status'])."'";
		}
		else if(urldecode($this->input['status']) == '0')
		{
			$condition .= " AND m.status = 0 ";
		}
		
		if(isset($this->input['start_time']) && !empty($this->input['start_time']))
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND m.create_time >= '".$start_time."'";
		}
		
		if(isset($this->input['end_time']) && !empty($this->input['end_time']))
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND m.create_time <= '".$end_time."'";
		}
		
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  m.create_time > '".$yesterday."' AND m.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  m.create_time > '".$today."' AND m.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  m.create_time > '".$last_threeday."' AND m.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  m.create_time > '".$last_sevenday."' AND m.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}
	
	public function create($input_info, $files = array())
	{
		$data = array(
			'name' 			=> $input_info['name'],
			'type' 			=> $input_info['type'],
			'url' 			=> $input_info['url'],
			'version_url'	=> $input_info['version_url'],
			'appid' 		=> $input_info['appid'],
			'appname'		=> $input_info['appname'],
			'module_id' 	=> $input_info['module_id'],
			'user_id' 		=> $input_info['user_id'],
			'user_name' 	=> $input_info['user_name'],
			'create_time' 	=> TIMENOW,
			'update_time' 	=> TIMENOW,
			'ip' 			=> $input_info['ip'],
			'sort_id'		=> $input_info['sort_id'],
			'brief'			=> $input_info['brief'],
			'event'			=> $input_info['event'],
		);

		$sql = "INSERT INTO " . DB_PREFIX . "mobile_module SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);

		$data['id'] = $this->db->insert_id();
		
		if (!$data['id'])
		{
			return false;
		}
		
		//更新排序
		$update_sql = 'UPDATE '.DB_PREFIX.'mobile_module SET order_id = ' . $data['id'] . ' WHERE id = '.$data['id'];
		$this->db->query($update_sql);
		/*if($files['file'])
		{
			$input_file = $files['file'];
			unset($files['file']);
			if ($input_file['tmp_name'])
			{
				$filepath = date('Y') . '/' . date('m');
				
				$file['Filedata'] = $input_file;
				
				$material = $this->mMaterial->addMaterialNodb($file, 2, IMG_DIR . $filepath);
			
				if (!empty($material))
				{
					$file_info = ", host = '". $material['host'] . "', dir = '" . IMG_DIR . "', filepath = '" . $filepath . '/' . "', filename = '" . $input_file['name'] . "' "; 
				}
			}
			
			$sql = "UPDATE " . DB_PREFIX . "mobile_module SET order_id = " . $data['id'] . $file_info;
			$sql .= " WHERE id = " . $data['id'];
			
			$this->db->query($sql);
		}*/
		if($files['file'])
		{
			$file = $files['file'];
			unset($files['file']);
							
			$count = 4;
			for($i = 0; $i <= $count; $i++)
			{
				if ($file['name'][$i])
				{
					foreach($file AS $k =>$v)
					{
						$photo['Filedata'][$k] = $file[$k][$i];
					}
					$photos[$i] = $photo;
				}			
			}
			if(!empty($photos))
			{
				$filepath = date('Y') . '/' . date('m') . '/';
				foreach ($photos as $key => $val)
				{
					if($val)
					{
						$material = $this->mMaterial->addMaterialNodb($val, 2, IMG_DIR . $filepath);
		
						$img_info = array();
						$img_info = array(
							'host'			=> $material['host'],
							'dir'			=> IMG_DIR,
							'filepath'		=> $filepath,
							'filename'		=> $material['filename'],
						);
						
						$img_info = serialize($img_info);
						
						$file_info = '';
						$file_info = "icon" . $key . " = '" . $img_info."'";
						$sql = "UPDATE " . DB_PREFIX . "mobile_module SET " . $file_info;
						$sql .= " WHERE id = " . $data['id'];
						$this->db->query($sql);
					}
				}
			}
		}
		
		if($this->input['app_id'])
		{
			//版本限制
			$version_arr = array();
			foreach ($this->input['app_id'] as $k => $v)
			{
				$version_arr[$v]['version'] 	= $this->input['version'][$k]; 
				$version_arr[$v]['version_max'] = $this->input['version_max'][$k]; 
			}
			
			$val = '';
			$sql = "INSERT INTO ".DB_PREFIX."mobile_module_confine (module_id,app_id,version,version_max) VALUES";
			foreach ($this->input['app_id'] as $k=>$v)
			{
				$val.= "(".$data['id'].",".$v.",'" . $version_arr[$v]['version'] . "','" . $version_arr[$v]['version_max'] . "'),";
			}
			$val = rtrim($val,',');
			$sql .= $val;
			$this->db->query($sql);
			
			
			//上传每个应用设置的logo
			/*if($files)
			{
				$filepath = date('Y') . '/' . date('m');
				$arr = array();
				foreach ($files as $key => $value)
				{
					if ($value['tmp_name'])
					{
						$file_app['Filedata'] = $value;
						$material = $this->mMaterial->addMaterialNodb($file_app, 2, IMG_DIR . $filepath);
						$arr[$key] = $material;
					}
				}
				if(!empty($arr))
				{
					foreach ($this->input['app_id'] as $kk => $app_id)
					{
						$app_file_key = 'app_file_'.$app_id;
						if($arr[$app_file_key])
						{
							$file_info = " host = '". $arr[$app_file_key]['host'] . "', dir = '" . IMG_DIR . "', filepath = '" . $filepath . '/' . "', filename = '" . $arr[$app_file_key]['filename'] . "' "; 
					
							$sql = "UPDATE " . DB_PREFIX . "mobile_module_confine SET " . $file_info;
							$sql .= " WHERE module_id = " . $data['id'] . " AND app_id = " . $app_id;
							$this->db->query($sql);
						}
					}
				}
			}*/
			
			
			//上传每个应用设置的logo
			if($files)
			{
				$filepath = date('Y') . '/' . date('m') . '/';
				
				$photos = array();
				foreach ($files as $key => $value)
				{
					$count = 4;
					for($i = 0; $i <= $count; $i++)
					{
						if ($value['name'][$i])
						{
							foreach($value AS $k =>$v)
							{
								$photo['Filedata'][$k] = $value[$k][$i];
							}
							$photos[$key][$i] = $photo;
						}			
					}
				}
				
				if (!empty($photos))
				{
					$arr = array();
					foreach ($photos as $key => $val)
					{
						foreach ($val as $k => $v)
						{
							if(!$v)
							{
								continue;
							}
							$material = $this->mMaterial->addMaterialNodb($v, 2, IMG_DIR . $filepath);
							$arr[$key][$k] = $material;
						}
					}
				}
				
				if(!empty($arr))
				{
					foreach ($this->input['app_id'] as $kk => $app_id)
					{
						$app_file_key = 'app_file_'.$app_id;
						if($arr[$app_file_key])
						{
							$app_file_info = $arr[$app_file_key];
							foreach ($app_file_info as $k => $v)
							{
								if(!$v)
								{
									continue;
								}
								$img_info = array();
								$img_info = array(
									'host'			=> $v['host'],
									'dir'			=> IMG_DIR,
									'filepath'		=> $filepath,
									'filename'		=> $v['filename'],
								);
								
								$img_info = serialize($img_info);
								$file_info = '';
								$file_info = "icon" . $k . " = '" . $img_info."'";
								
								$sql = "UPDATE " . DB_PREFIX . "mobile_module_confine SET " . $file_info;
								$sql .= " WHERE module_id = " . $data['id'] . " AND app_id = " . $app_id;
								$this->db->query($sql);
							}
						}
					}
				}
			}
		}
		return $data;
	}
	
	public function update($input_info, $id, $files = array())
	{
		
		if(!$input_info || !$id)
		{
			return false;
		}
		
		$data = array(
			'name' 			=> $input_info['name'],
			'type' 			=> $input_info['type'],
			'url' 			=> $input_info['url'],
			'version_url'	=> $input_info['version_url'],
			'module_id' 	=> $input_info['module_id'],
			'update_time' 	=> TIMENOW,
			'sort_id'		=> $input_info['sort_id'],
			'brief'			=> $input_info['brief'],
			'event'			=> $input_info['event'],
		);

		$sql = "UPDATE " . DB_PREFIX . "mobile_module SET ";
		$space = "";
		foreach ($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id = " . $id;
		
		$this->db->query($sql);

		$data['id'] = $id;
		
		if (!$data['id'])
		{	
			return false;
		}
		if($files['file'])
		{
			$file = $files['file'];
			unset($files['file']);
							
			$count = 4;
			for($i = 0; $i <= $count; $i++)
			{
				if ($file['name'][$i])
				{
					foreach($file AS $k =>$v)
					{
						$photo['Filedata'][$k] = $file[$k][$i];
					}
					$photos[$i] = $photo;
				}			
			}
			if(!empty($photos))
			{
				$filepath = date('Y') . '/' . date('m') . '/';
				foreach ($photos as $key => $val)
				{
					if($val)
					{
						$material = $this->mMaterial->addMaterialNodb($val, 2, IMG_DIR . $filepath);
						$img_info = array();
						$img_info = array(
							'host'			=> $material['host'],
							'dir'			=> IMG_DIR,
							'filepath'		=> $filepath,
							'filename'		=> $material['filename'],
						);
						
						$img_info = serialize($img_info);
						
						$file_info = '';
						$file_info = "icon" . $key . " = '" . $img_info."'";
						$sql = "UPDATE " . DB_PREFIX . "mobile_module SET " . $file_info;
						$sql .= " WHERE id = " . $data['id'];
						$this->db->query($sql);
					}
				}
			}
		}
		
		//更新版本对模块的限制
		if($this->input['app_id'])
		{
			$limit_version = array();
			foreach ($this->input['app_id'] as $k => $v)
			{
				$limit_version[$v]['min'] = $this->input['version'][$k]; 
				$limit_version[$v]['max'] = $this->input['version_max'][$k];
			}

			//查询此模块下限制的所有应用
			$sql = 'SELECT app_id FROM '.DB_PREFIX.'mobile_module_confine WHERE module_id='.$id;
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$old_info[$r['app_id']] = 1;
			}
			if(!$old_info)
			{
				$sql = "INSERT INTO ".DB_PREFIX."mobile_module_confine (module_id,app_id,version,version_max) VALUES";
				if($limit_version)
				{
					$val = '';
					foreach ($limit_version as $app_id=>$version)
					{
						//$old_info[$app_id] = 1;
						$val.= "(".$id.",".$app_id.",'".$version['min']."','".$version['max']."'),";
					}
					$val = rtrim($val,',');
					$sql .= $val;
					$this->db->query($sql);
				}
			}
			else 
			{
				$old_app_id = array_keys($old_info);
				$new_app_id = $this->input['app_id'];
				
				//删除
				$del_app_id = array_diff($old_app_id, $new_app_id);
				if(!empty($del_app_id))
				{
					if(is_array($del_app_id))
					{
						$del_app_id = implode(',',$del_app_id);
					}		
					$sql = 'DELETE FROM '.DB_PREFIX.'mobile_module_confine WHERE module_id='.$id.' AND app_id IN ('.$del_app_id.')';
					$this->db->query($sql);
				}
				
				//新增
				$ins_app_id = array_diff($new_app_id, $old_app_id);
				if(!empty($ins_app_id))
				{
					$val = '';
					$sql = "INSERT INTO ".DB_PREFIX."mobile_module_confine (module_id,app_id,version,version_max) VALUES";
					foreach ($ins_app_id as $k=>$v)
					{
						$val.= "(" . $id . "," . $v . ",'" . $limit_version[$v]['min'] . "','" . $limit_version[$v]['max'] . "'),";
					}
					$val = rtrim($val,',');
					$sql .= $val;
					$this->db->query($sql);
				}
				
				//更新
				$up_app_id = array_intersect($old_app_id,$new_app_id);
				if(!empty($up_app_id))
				{
					foreach ($up_app_id as $k=>$v)
					{
						$sql = "UPDATE " . DB_PREFIX . "mobile_module_confine 
						SET version = '" . $limit_version[$v]['min'] . "',version_max = '" . $limit_version[$v]['max'] . "' 
						WHERE module_id = " . $id . " AND app_id = ".$v;
						$this->db->query($sql);
					}
				}
			}
			
			
			//上传每个应用设置的logo
			if($files)
			{
				$filepath = date('Y') . '/' . date('m') . '/';
				
				$photos = array();
				foreach ($files as $key => $value)
				{
					$count = 4;
					for($i = 0; $i <= $count; $i++)
					{
						if ($value['name'][$i])
						{
							foreach($value AS $k =>$v)
							{
								$photo['Filedata'][$k] = $value[$k][$i];
							}
							$photos[$key][$i] = $photo;
						}			
					}
				}
				
				if (!empty($photos))
				{
					$arr = array();
					foreach ($photos as $key => $val)
					{
						foreach ($val as $k => $v)
						{
							if(!$v)
							{
								continue;
							}
							$material = $this->mMaterial->addMaterialNodb($v, 2, IMG_DIR . $filepath);
							$arr[$key][$k] = $material;
						}
					}
				}
				
				if(!empty($arr))
				{
					foreach ($this->input['app_id'] as $kk => $app_id)
					{
						$app_file_key = 'app_file_'.$app_id;
						if($arr[$app_file_key])
						{
							$app_file_info = $arr[$app_file_key];
							foreach ($app_file_info as $k => $v)
							{
								if(!$v)
								{
									continue;
								}
								$img_info = array();
								$img_info = array(
									'host'			=> $v['host'],
									'dir'			=> IMG_DIR,
									'filepath'		=> $filepath,
									'filename'		=> $v['filename'],
								);
								
								$img_info = serialize($img_info);
								$file_info = '';
								$file_info = "icon" . $k . " = '" . $img_info."'";
								
								$sql = "UPDATE " . DB_PREFIX . "mobile_module_confine SET " . $file_info;
								$sql .= " WHERE module_id = " . $id . " AND app_id = " . $app_id;
								$this->db->query($sql);
							}
						}
					}
				}
			}
		}
		else 
		{
			$sql = 'DELETE FROM '.DB_PREFIX.'mobile_module_confine WHERE module_id='.$id;
			$this->db->query($sql);
		}
		return $data;
	}
	
	public function delete($id)
	{
		$sql = "DELETE FROM " . DB_PREFIX . "mobile_module WHERE id IN (" . $id . ")";
		if ($this->db->query($sql))
		{
			$sql = "DELETE FROM ".DB_PREFIX."mobile_module_confine WHERE module_id IN (" . $id .")";
			$this->db->query($sql);
			return true;
		}
		return false;
	}

	public function audit($table, $id, $type)
	{
		$sql = "SELECT " . $type . " FROM " . DB_PREFIX . $table . " WHERE id = " . $id;
		$member = $this->db->query_first($sql);

		$status = $member[$type];
		
		$new_status = 0; //操作失败
		
		if (!$status)	//已审核
		{
			$sql = "UPDATE " . DB_PREFIX . $table . " SET ".$type." = 1 WHERE id = " . $id;
			$this->db->query($sql);

			$new_status = 1;
		}
		else			//待审核
		{
			$sql = "UPDATE " . DB_PREFIX . $table . " SET ".$type." = 0 WHERE id = " . $id;
			$this->db->query($sql);

			$new_status = 2;
		}

		return $new_status;
	}
}

?>