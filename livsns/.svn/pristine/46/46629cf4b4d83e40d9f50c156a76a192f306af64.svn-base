<?php
class TVPlayMode extends InitFrm
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
		$sql = "SELECT t.*,p.name AS play_sort_name FROM " .DB_PREFIX. "tv_play t LEFT JOIN " .DB_PREFIX. "play_sort p ON p.id = t.play_sort_id WHERE 1 " . $condition . $orderby . $limit;
		$q 	 = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['img'] = hg_fetchimgurl(unserialize($r['img']),160);
			$r['status_format'] = $this->settings['play_status'][$r['status']];
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			if($r['copyright_limit'])
			{
				$r['copyright_limit'] = date('Y-m-d',$r['copyright_limit']);
			}
			else 
			{
				$r['copyright_limit'] = 0;
			}
			$r['playcount_format'] = $r['playcount'] . '集';
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
		
		$sql = " INSERT INTO " . DB_PREFIX . "tv_play SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."tv_play SET order_id = {$vid}  WHERE id = {$vid}";
		$this->db->query($sql);
		
		//首字母表电视剧的个数加1
		if($data['initial'])
		{
			$sql = " UPDATE ".DB_PREFIX."tv_initial SET total_num =  total_num + 1 WHERE name = '" .$data['initial']. "'";
			$this->db->query($sql);
		}
		return $vid;
	}
	
	public function update($id,$data = array())
	{
		if(!$data || !$id)
		{
			return false;
		}
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "tv_play WHERE id = '" .$id. "'";
		$pre_data = $this->db->query_first($sql);
		if(!$pre_data)
		{
			return false;
		}
		
		//更新数据
		$sql = " UPDATE " . DB_PREFIX . "tv_play SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '"  .$id. "'";
		$this->db->query($sql);
		
		//如果修改后的电视剧标题与原来不同就要更新首字母个数字段
		if($pre_data['initial'] != $data['initial'])
		{
			//原来的减1
			if($pre_data['initial'])
			{
				$sql = " UPDATE ".DB_PREFIX."tv_initial SET total_num =  total_num - 1 WHERE name = '" .$pre_data['initial']. "'";
				$this->db->query($sql);
			}
			
			//后来的加1
			if($data['initial'])
			{
				$sql = " UPDATE ".DB_PREFIX."tv_initial SET total_num =  total_num + 1 WHERE name = '" .$data['initial']. "'";
				$this->db->query($sql);
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
		
		//查询出电视剧的数据
		$sql = "SELECT t.*,p.name AS play_sort_name FROM " .DB_PREFIX. "tv_play t LEFT JOIN " .DB_PREFIX. "play_sort p ON p.id = t.play_sort_id WHERE t.id = '" .$id. "'";
		$ret = $this->db->query_first($sql);
		if(!$ret)
		{
			return false;
		}
		$ret['img'] = hg_fetchimgurl(unserialize($ret['img']),160);
		if($ret['copyright_limit'])
		{
			$ret['copyright_limit'] = date('Y-m-d',$ret['copyright_limit']);
		}
		else 
		{
			$ret['copyright_limit'] = 0;//永久有效
		}
		
		$ret['column_id'] = unserialize($ret['column_id']);
		if ( is_array($ret['column_id']) && $ret['column_id'] )
		{
			$ret['column_id'] = implode(',',array_keys($ret['column_id']));
		}
		$ret['pub_time'] = $ret['pub_time'] ? date("Y-m-d H:i", $ret['pub_time']) : '';
		
		//查询出该电视剧里面的剧集
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_episode WHERE tv_play_id = '" .$id. "' ORDER BY index_num ASC ";
		$q 	 = $this->db->query($sql);
		$episode = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['img_index'] = hg_fetchimgurl(unserialize($r['img']),215);
			$episode[] = $r;
		}
		$ret['episode'] =  $episode;
		$ret['app_uniqueid'] = APP_UNIQUEID;
		$ret['mod_uniqueid'] = MOD_UNIQUEID;
		return $ret;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tv_play t WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		return $total;
	}
	
	public function delete($id)
	{
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "tv_play WHERE id IN (" . $id . ")";
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
		$sql = " DELETE FROM " .DB_PREFIX. "tv_play WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		//更新首字母表
		foreach($pre_data AS $k => $v)
		{
			if($v['initial'])
			{
				$sql = " UPDATE ".DB_PREFIX."tv_initial SET total_num =  total_num - 1 WHERE name = '" .$v['initial']. "'";
				$this->db->query($sql);
			}
		}
		
		//查询出电视剧里面所有的剧集
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_episode WHERE tv_play_id IN (" .$id. ")";
		$q = $this->db->query($sql);
		$video_ids = array();
		while ($r = $this->db->fetch_array($q))
		{
			$video_ids[] = $r['video_id'];
		}
		
		//删除剧集
		$sql = " DELETE FROM " .DB_PREFIX. "tv_episode WHERE tv_play_id IN (" . $id . ")";
		$this->db->query($sql);
		
		return array(
			'pre_data' => $pre_data,
			'video_ids' => $video_ids,
		);
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
		//这个是批量操作
		if($op && in_array($op,array(2,3)))
		{
			$sql = " UPDATE " .DB_PREFIX. "tv_play SET status = '" .$op. "' WHERE id IN (" .$id. ")";
			$this->db->query($sql);
			$status = $op;	
		}
		else if(count($ids) == 1)
		{
			//查询出原来
			$sql = " SELECT * FROM " .DB_PREFIX. "tv_play WHERE id = '" .$id. "'";
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
			
			$sql = " UPDATE " .DB_PREFIX. "tv_play SET status = '" .$status. "' WHERE id = '" .$id. "'";
			$this->db->query($sql);
		}
		return array('status' => $status,'id' => $id,'status_text' => $this->settings['play_status'][$status]);
	}

	/****************************************一些扩展的操作***********************************************/
	
	//获取电视剧类型编目
	public function get_tv_play_type($id='')
	{
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_play_type WHERE 1 ";
                if($id)
                {
                    $sql .= "AND id=".$id;
                }
		$q	 = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	//获取电视剧语言编目
	public function get_tv_play_lang()
	{
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_play_lang";
		$q	 = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	//获取电视剧年份编目
	public function get_tv_play_year()
	{
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_play_year";
		$q	 = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	//获取电视剧地区编目
	public function get_tv_play_district()
	{
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_play_district";
		$q	 = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	//获取电视剧版权商编目
	public function get_tv_play_publisher()
	{
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_play_publisher";
		$q	 = $this->db->query($sql);
		$ret = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ret[] = $r;
		}
		return $ret;
	}
	
	//新增剧集
	public function createEpisode($data = array())
	{
		if(!$data)
		{
			return false;
		}
		
		$sql = " INSERT INTO " . DB_PREFIX . "tv_episode SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		
		//新增完之后查询改电视剧下当前已经有多少集了，然后更新index_num
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tv_episode  WHERE  tv_play_id = '" .$data['tv_play_id']. "'";
		$total = $this->db->query_first($sql);
		
		if(!$data['index_num'])
		{
			$sql = "UPDATE " .DB_PREFIX. "tv_episode SET index_num = '" . $total['total'] . "' WHERE id = '" .$vid. "'";
			$this->db->query($sql);
			$data['index_num'] = $total['total'];
		}
		
		if(!$data['title'])
		{
			$title = '第' .$data['index_num'] . '集';
			$sql = "UPDATE " .DB_PREFIX. "tv_episode SET  title = '" .$title. "' WHERE id = '" .$vid. "'";
			$this->db->query($sql);
			$data['title'] = $title;
		}

		//每创建一集就要更新电视剧里面的更新状态的字段
		$sql = "UPDATE " .DB_PREFIX. "tv_play SET update_status = update_status + 1 WHERE id = '" .$data['tv_play_id']. "'";
		$this->db->query($sql);
		$ret = array(
			'id' => $vid,
			'index_num' => $data['index_num'],
			'title' => $data['title'],
		);
		return $ret;
	}
	
	public function deleteEpisode($id)
	{
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "tv_episode WHERE id IN (" . $id . ")";
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
		
		//获取所属的电视剧的id
		$tv_play_id = $pre_data[0]['tv_play_id'];
		$sql = " DELETE FROM " .DB_PREFIX. "tv_episode WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		//更新剧集的索引
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_episode WHERE tv_play_id = '" .$tv_play_id. "' ORDER BY index_num ASC ";
		$q = $this->db->query($sql);
		$episode = array();
		while($r = $this->db->fetch_array($q))
		{
			$episode[] = $r['id'];
		}
		
		//如果说里面还有剧集话就更新索引
		/*
		if($episode)
		{
			foreach ($episode AS $k => $v)
			{
				$title = '第'  .intval($k + 1). '集';
				$sql = "UPDATE " .DB_PREFIX. "tv_episode SET index_num = '" .intval($k + 1). "',title = '" .$title. "' WHERE id = '" .$v. "'";
				$sql = "UPDATE " .DB_PREFIX. "tv_episode SET index_num = '" .intval($k + 1). "' WHERE id = '" .$v. "'";
				$this->db->query($sql);
			}
		}
		*/
		//更新电视剧里面的更新状态的字段
		$sql = "UPDATE " .DB_PREFIX. "tv_play SET update_status = '" .count($episode). "' WHERE id = '" .$tv_play_id. "'";
		$this->db->query($sql);
		return $pre_data;
	}
	
	//更新剧集存储视频库的发布的链接
	public function updateEpisodeUrl($video_id = '',$url = '')
	{
		if(!$url || !$video_id)
		{
			return false;
		}
		
		$sql = "UPDATE " .DB_PREFIX. "tv_episode SET episode_url = '" .$url. "' WHERE video_id = '" .$video_id. "'";
		$this->db->query($sql);
		return true;
	}
	
	//获取剧集的信息
	public function get_episode_info($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_episode WHERE id IN (" .$id. ")";
		$q = $this->db->query($sql);
		$episode = array();
		while ($r = $this->db->fetch_array($q))
		{
			$episode[] = $r;
		}
		return $episode;
	} 
	
	//获取电视剧信息
	public function get_tv_play_info($id = '')
	{
		if(!$id)
		{
			return false;
		}
		
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_play WHERE id IN (" .$id. ")";
		$q = $this->db->query($sql);
		$tv_play = array();
		while ($r = $this->db->fetch_array($q))
		{
			$tv_play[] = $r;
		}
		return $tv_play;
	}
	
	//获取首字母表的内容
	public  function get_initial()
	{
		$sql = " SELECT * FROM " .DB_PREFIX. "tv_initial ";
		$q = $this->db->query($sql);
		$initial = array();
		while ($r = $this->db->fetch_array($q))
		{
			$initial[] = $r['name'];
		}
		return $initial;
	}
	
	//获取首字母表的信息
	public  function get_initial_info()
	{
		$sql = " SELECT * FROM " .DB_PREFIX. "tv_initial ";
		$q = $this->db->query($sql);
		$initial = array();
		while ($r = $this->db->fetch_array($q))
		{
			$initial[] = $r;
		}
		return $initial;
	}
	
	public function updateTvImage($tv_play_id = '',$img = array())
	{
		if(!$tv_play_id || !$img)
		{
			return false;
		}
		
		$sql = "UPDATE " .DB_PREFIX. "tv_play SET img = '" .serialize($img). "' WHERE id = '" .$tv_play_id. "'";
		$this->db->query($sql);
		return true;
	}
}
?>