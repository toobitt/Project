<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');

class product_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition = '',$orderby = '',$limit = '')
	{
		$sql = "SELECT t1.*,m.host,m.dir,m.filepath,m.filename,c.name as company_name,s.name as sort_name,b.name as type_name FROM " . DB_PREFIX . "product t1 
				LEFT JOIN ".DB_PREFIX."materials m 
					ON t1.indexpic_id = m.id 
				LEFT JOIN ".DB_PREFIX."company c
					ON t1.company_id = c.id
				LEFT JOIN ".DB_PREFIX."sort s
					ON t1.sort_id = s.id
				LEFT JOIN ".DB_PREFIX."buy_type b
					ON t1.type_id = b.id
				WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['type']	= $r['type_name'];
			
			switch ($r['status'])
			{
				case 0:
					$r['audit'] = '待审核';
					break;
				case 1:
					$r['audit'] = '已审核';
					break;
				case 2:
					$r['audit']	= '已打回';
					break;
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "product SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$data['id'] = $id;
		
		$sql = " UPDATE ".DB_PREFIX."product SET order_id = {$id}  WHERE id = {$id}";
		$this->db->query($sql);
		
		return $data;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "product WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "product SET ";
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
		
		$sql = "SELECT p.*,s.name as sort_name,m.host,m.dir,m.filepath,m.filename,c.contract_way FROM " . DB_PREFIX . "product p 
				LEFT JOIN ".DB_PREFIX."sort s 
					ON p.sort_id = s.id 
				LEFT JOIN ".DB_PREFIX."materials m
					ON p.indexpic_id = m.id
				LEFT JOIN ".DB_PREFIX."company c
					ON p.company_id = c.id
				WHERE p.id = '" .$id. "'";
		$info = $this->db->query_first($sql);
		
		//直播判断
		if($info['channel_id'] && $info['live_start_time'] <= TIMENOW && $info['live_end_time'] >= TIMENOW)
		{
			$live_info = $this->get_live($info['channel_id']);
			$live_time = $info['live_end_time'] - TIMENOW;
			if($live_info)
			{
				$info['live_info'] = $live_info[0]['channel_stream'][0];
				$info['live_info']['live_time'] = $live_time;
			}
			else 
			{
				$info['live_info'] = array();
			}
			
		}
		
		//判断索引图
		if($info['host'] && $info['dir'] && $info['filepath'] && $info['filename'])
		{
			$info['img_info'] = array(
				'host'=>$info['host'],
				'dir'=>$info['dir'],
				'filepath'=>$info['filepath'],
				'filename'=>$info['filename'],
			);
		}
		else
		{
			$info['img_info'] = array();
		}
		
		//联系方式
		if($info['contract_way'])
		{
			$info['contract_way'] = unserialize($info['contract_way']);
		}

		$info['start_time2'] = $info['start_time'];
		$info['end_time2']	= $info['end_time'];	
		$info['start_time'] = date('Y-m-d H:i',$info['start_time']);
		$info['end_time']	= date('Y-m-d H:i',$info['end_time']);
		
		
		//获取图片信息
		$sql = 'SELECT id,host,dir,filepath,filename FROM '.DB_PREFIX.'materials  WHERE cid = '.$id.' AND vodid ="" ORDER BY id DESC';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{				
			$info['pic_info'][] = $row;
		}	
		
		//获取视频信息
		$sql = "SELECT id,vodid FROM ".DB_PREFIX.'materials WHERE cid = '.$id.' AND vodid!=""';
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$vodid_arr[$r['vodid']] = $r['id'];
		}
		
		if(!empty($vodid_arr))
		{
			
			$vodid = array_keys($vodid_arr);
			$vids = implode(',', $vodid);
			//视频地址
			$res = $this->get_video($vids);
			if($res)
			{
				foreach ($res[0] as $k => $v)
				{
					$video_info[$k]['source_img'] 		= $v['source_img'];
					$video_info[$k]['material_id'] 		= $vodid_arr[$v['id']];
					$video_info[$k]['video_url'] 		= $v['video_url'];
					$video_info[$k]['video_url_m3u8']	= $v['video_url_m3u8'];
				}
			}
			krsort($video_info);
			$info['video_info'] = $video_info;
		}
		
		return $info;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product t1 WHERE 1 " . $condition;
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
		$sql = " DELETE FROM " .DB_PREFIX. "product WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		$sql = "DELETE FROM " . DB_PREFIX . "materials WHERE cid IN (" . $id . ")";
		$this->db->query($sql);
		
		return $id;
	}
	
	public function audit($id = '',$audit = '')
	{
		if(!$id)
		{
			return false;
		}
		
		switch ($audit)
		{
			case 0:$status = 2;break;//打回
			case 1:$status = 1;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "product SET status = '" .$status. "' WHERE id IN ('" .$id. "')";
		$this->db->query($sql);
		return array('status' => $status,'id' => $id);
	}
	
	//获取上传图片的类型
	public function getPhotoConfig()
	{
		$ret = $this->material->get_allow_type();
		if (!$ret) {
			return false;
		}
		$photoConfig = array();
		if (is_array($ret['img']) && !empty($ret['img']))
		{
			foreach ($ret['img'] as $type)
			{
				$photoConfig['type'][] =  'image/'.$type;
			}
			$photoConfig['hint'] = implode(',', $ret['img']);
		}
		return $photoConfig;	
	}
	
	//上传图片服务器
	public function uploadToPicServer($file)
	{
		$material = $this->material->addMaterial($file); //插入图片服务器
		return $material;
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
	
	//获取视频上传配置
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
	
	//上传视频
	public function uploadToVideoServer($file)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addFile($file);
		$curl->addRequestData('vod_leixing',2);
		$ret = $curl->request('create.php');
		return $ret[0];
	}
	//根据url上传图片
	public function localMaterial($url,$cid)
	{
		$material = $this->material->localMaterial($url,$cid);
		return $material[0];
	}
	
	//根据内容id获取所有视频信息
	public function get_video($vodid)
	{
		if(!$vodid)
		{
			return false;
		}
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','get_videos');
		$curl->addRequestData('id',$vodid);
		$ret = $curl->request('vod.php');
		
		return $ret;
	}
	/**
	 * 获取频道信息
	 * Enter description here ...
	 * @param string $channel_ids
	 */
	public function get_live($channel_ids)
	{
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel_tmp = $newLive->getChannelInfoById(array('id' => $channel_ids,'is_stream' => 1, 'live' => 1, 'is_sys' => -1));
		return $channel_tmp;
	}
	
	/**
	 *创建收录计划 
	 * Enter description here ...
	 * @param array $data
	 */
	public function create_program_record($data)
	{
		include_once(ROOT_PATH . 'lib/class/program_record.class.php');
		$newPro = new programRecord();
		$pro_rec = $newPro->create($data);
		return $pro_rec;
	}
	
	/**
	 *更新收录计划 
	 * Enter description here ...
	 * @param array $data
	 */
	public function update_program_record($data)
	{
		include_once(ROOT_PATH . 'lib/class/program_record.class.php');
		$newPro = new programRecord();
		$pro_rec = $newPro->update($data);
		return $pro_rec;
	}
	
}
?>