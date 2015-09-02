<?php
class movie_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$this->material = new material();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		global $gGlobalConfig;
		$sql = "SELECT * FROM " . DB_PREFIX . "movie  WHERE 1 " . $condition . $orderby . $limit;
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
		
			//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
			$tmp = unserialize($r['index_pic']);
			$r['index_pic'] = $tmp;//['host'].$tmp['dir'].$tmp['filepath'].$tmp['filename'];
			$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			$r['release_time'] = date('Y-m-d',$r['release_time']);
			$type_tmp = explode(',', $r['type']);
			$type = '';
			foreach((array)$type_tmp as $k => $v)
			{
				$type .= $gGlobalConfig['movie_type'][$v].'/';
			}
			$r['type'] = trim($type,'/');//str_replace(',','/',$r['type']);
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "movie SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."movie SET order_id = {$vid}  WHERE id = {$vid}";
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
		$sql = " SELECT * FROM " .DB_PREFIX. "movie WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "movie SET ";
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
		
		$sql = "SELECT * FROM " . DB_PREFIX . "movie  WHERE id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		//获取剧照和预告片信息
		if($info)
		{
			$con = '';
			if($info['still_id'])
			{
				$con = ' id IN (';
				$con .= $info['still_id'];
				$con  = rtrim($con,',');
			}
			
			if($info['prevue_id'])
			{
				$info['prevue_id']  = rtrim($info['prevue_id'],',');
				if($info['still_id'])
				{
					$con .= ',' . $info['prevue_id'] . ')';
				}
				else 
				{
					$con = ' id IN (' . $info['prevue_id'] . ')';
				}
				
			}
			elseif ($info['still_id'])
			{
				$con .= ')';
			}
			
			if($con)
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "material  WHERE 1 AND " . $con;
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					if($row['img_info'])
					$info['still'][] = array(
						'still_id' 	=> $row['id'],
						'img_info' 	=> unserialize($row['img_info']),
						'img_url'	=> hg_fetchimgurl(unserialize($row['img_info']),'55','55'),
					);
					if($row['video_info'])
					$info['prevue'][] = array(
						'prevue_id' 	=> $row['id'],
						'prevue_info' 	=> unserialize($row['video_info']),
					);
				}
			}
			$tmp = unserialize($info['index_pic']);
			$info['index_pic'] = $tmp['host'].$tmp['dir'].$tmp['filepath'].$tmp['filename'];
			$info['create_time'] = date('Y-m-d H:i:s',$info['create_time']);
			$info['release_time'] = date('Y-m-d',$info['release_time']);
			$info['update_time'] = date('Y-m-d H:i:s',$info['update_time']);
		}
		//此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "movie WHERE 1 " . $condition;
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
		$sql = " SELECT * FROM " .DB_PREFIX. "movie WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "movie WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		return $pre_data;
	}
	
	public function audit($id = '',$audit)
	{
		if(!$id || !$audit)
		{
			return false;
		}
		
		switch ($audit)
		{
			case 1:$status = 1;$audit_status = '已审核';break;//审核
			case 2:$status = 2;$audit_status = '已打回';break;//打回
		}
		
		$sql = " UPDATE " .DB_PREFIX. "movie SET status = '" .$status. "' WHERE id IN (" .$id. ")";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id,'audit'=>$audit_status);
	}
	
	/**
	 * 上传图片 
	 */
	public function add_material($file)
	{	
		$files['Filedata'] = $file;
		$material = $this->material->addMaterial($files);			
		$return = array();
		if (!empty($material))
		{
			$return = array(
				'mid'      => $material['id'],
				'name'     => $material['name'],
				'host'     => $material['host'],
				'dir'      => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
				'type'     => $material['type'],
				'imgwidth' => $material['imgwidth'],
				'imgheight'=> $material['imgheight'],
				'filesize' => $material['filesize'],
			);
		}
		
		return $return;
	}
	
	/**
	 * 
	 * @Description 视频上传
	 * @author Kin
	 * @date 2013-4-13 下午04:34:29
	 */
	public function uploadToVideoServer($file)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addFile($file);
		$curl->addRequestData('vod_leixing',1);
		$ret = $curl->request('create.php');
		return $ret[0];
	}
	
	/**
	 * 
	 * @Description  获取视频的配置
	 * @author Kin
	 * @date 2013-4-13 下午04:48:54
	 */
	public function getVideoConfig()
	{
		$videoConfig = array();
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','__getConfig');
		$ret = $curl->request('index.php');
		if (empty($ret))
		{
			return false;
		}
		$temp = explode(',', $ret[0]['video_type']['allow_type']);
		$videoConfig['type'] = $temp;
		if (is_array($temp) && !empty($temp))
		{
			foreach ($temp as $val)
			{
				$videoType[] = ltrim($val,'.');
			}
			$videoConfig['hit'] = implode(',', $videoType);
			
		}
		return $videoConfig;
	}
	
	/*
	 * 获取排片信息
	 */
	public function get_project_info($cinema_id = '', $movie_id_arr = '', $dates = '')
	{
		global $gGlobalConfig;
		if(!$movie_id_arr)
		{
			return false;
		}
		if(!$cinema_id)
		{
			return false;
		}
		if(!$dates)
		{
			return false;
		}
		//查出影院信息
		$sql = "SELECT c.*,co.* FROM " . DB_PREFIX . "cinema c LEFT JOIN " .DB_PREFIX. "content co ON c.id=co.cinema_id  WHERE c.id = " .$cinema_id;
		$cimema_info = $this->db->query_first($sql);
		if($cimema_info['status'] != 1)
		{
			return false;
		}
		$cimema_info['stime'] = date('H:i',$cimema_info['stime']);
		$cimema_info['etime'] = date('H:i',$cimema_info['etime']);
		$cimema_info['create_time'] = date('Y-m-d H:i:s',$cimema_info['create_time']);
		$cimema_info['release_time'] = date('Y-m-d H:i:s',$cimema_info['release_time']);
		$cimema_info['content'] = stripslashes($cimema_info['content']);
		//查出影片信息
		$movie_id_arr = array_flip(array_flip($movie_id_arr));
		$movie_ids = implode(',',$movie_id_arr);
		$sql = "SELECT * FROM " .DB_PREFIX. "movie WHERE status = 1 AND id IN( " .$movie_ids. " )";
		$movie_query = $this->db->query($sql);
		$movie_ids = '';
		while($row = $this->db->fetch_array($movie_query))
		{
			if($row['status'] == 1)
			{
				$movie_ids .= $row['id'].',';
				$tmp = unserialize($row['index_pic']);
				$row['index_pic'] = $tmp;//['host'].$tmp['dir'].$tmp['filepath'].$tmp['filename'];
				$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
				$row['release_time'] = date('Y-m-d',$row['release_time']);
				$row['update_time'] = date('Y-m-d H:i:s',$row['update_time']);
				$type_tmp = explode(',', $row['type']);
				$type = '';
				foreach((array)$type_tmp as $k => $v)
				{
					$type .= $gGlobalConfig['movie_type'][$v].'/';
				}
				$row['type'] = trim($type,'/');
				$movie_info[$row['id']] = $row;
			}
		}
		//hg_pre($movie_info);exit;
		if(!$movie_info)
		{
			return false;
		}
		//查出排片信息
		$sql = "SELECT id,movie_id FROM " .DB_PREFIX. "project WHERE status = 1 AND cinema_id = " .$cinema_id. " AND movie_id IN(" .trim($movie_ids,','). ") AND dates = '" .$dates. "'";
		$project_query = $this->db->query($sql);
		while($row = $this->db->fetch_array($project_query))
		{
			$map[$row['movie_id']] = $row['id'];
		}
		//hg_pre($map);exit;
		if(!$map)
		{
			return false;
			/*
			$ret = array(
				'list' => $movie_info,
				'cinema_info' => $cimema_info,
			);
			return $ret;
			*/
		}
		$project_ids = implode(',', $map);
		$sql = "SELECT * FROM " . DB_PREFIX . "project_list WHERE project_id IN(" .$project_ids. ") ORDER BY project_time ASC";
		$project_list_query = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($project_list_query))
		{
			$project_list[] = $row; 
		}
		foreach((array)$movie_id_arr as $k => $v)
		{
			foreach((array)$project_list as $kk => $vv)
			{
				$vv['create_time'] = date('Y-m-d',$vv['create_time']);
				$vv['update_time'] = date('Y-m-d',$vv['update_time']);
				$vv['project_time'] = date('H:i',$vv['project_time']);
				if($vv['project_id'] == $map[$v])
				{
					$ret['list'][$k]['movie_info'] = $movie_info[$v];
					$ret['list'][$k]['project_info'][] = $vv;
				}
			}
		}
		if(!$ret['list'])
		{
			return false;
		}
		else 
		{
			$ret['list'] = array_values($ret['list']);
			$ret['cimema_info'] = $cimema_info;
		}
		return $ret;
	}
}
?>