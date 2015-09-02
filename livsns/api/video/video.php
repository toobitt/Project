<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: video.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class getVideosInfoApi extends adminBase
{
	private $mVideo;

	private $mUser;

	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
		include_once(ROOT_PATH . '/lib/user/user.class.php');
		$this->mUser = new user();	

	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 显示视频信息
	 */
	public function show()
	{
		$info_type = intval($this->input['info_type']);
		
		switch($info_type)
		{
			case 0 : $this->video();break;          //已发布视频
			case 1 : $this->album();break;			//专辑
			case 2 : $this->album_video();break;	//专辑中的视频
			case 3 : $this->all_video();break;		//所有视频
			case 4 : $this->single_video();break;   //单个视频
			case 5 : $this->search();break;			//视频检索
			case 6 : $this->verify_video();break;   //待审核视频
			case 7 : $this->transcode_video();break;//转码中视频
			case 8 : $this->unpass_video();break;   //未通过审核视频
			case 9 : $this->recommend_video();break;//推荐视频
			case 10 : $this->verify_delete_video();break;//推荐视频
			default: $this->video();
		}
	}
	 
	/**
	 * 标记推荐
	 */
	public function mark_recommend()
	{
		$rid = $this->input['rid'];
		$sql = "UPDATE " . DB_PREFIX . "video SET is_recommend = 1 WHERE id = " . $rid;
		$this->db->query($sql);
	}
	
	/**
	 * 取消推荐标记
	 */
	public function cancel_recommend_mark()
	{
		$rid = $this->input['rid'];
		$sql = "UPDATE " . DB_PREFIX . "video SET is_recommend = 0 WHERE id = " . $rid;
		$this->db->query($sql);
	}
	
	/**
	 * 检测该视频是否已被推荐
	 */
	public function check_recommend()
	{
		$rid = $this->input['rid'];
		$sql = "SELECT id FROM " . DB_PREFIX . "video WHERE id = " . $rid . " AND is_recommend = 1";
		
		
		$r = $this->db->query_first($sql);
		if($r)
		{
			echo 1; //已推荐
		}
		else
		{
			echo 0; //未被推荐
		} 			
	}
		
	/**
	 * 获取用户所有的视频(包括更新视频的状态)
	 */
	public function all_video()
	{
		$user_info = $this->mUser->verify_credentials();		

		if(!$user_info)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}

		/**
		 * 更新视频状态
		include_once(ROOT_PATH . 'api/video/update_video_state.php');		
		$update_obj = new updateUserVideoState();	
		$update_obj->updata_video_state();
		
		 */
		$page = $this->input['page'] ? $this->input['page'] : 0;		
		if(!$this->input['count'])
		{
			$this->input['count'] =  10;
		}
		
		$count = intval($this->input['count']);		
		$offset = $page * $count;
		
		//查询用户所有的视频数
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video WHERE user_id = " . $user_info['id'] ;		
		$r = $this->db->query_first($sql);	
			
		$total_nums = $r['total_nums'];

		//查询用户所有的视频
		$sql  = "SELECT * FROM " . DB_PREFIX . "video WHERE user_id = " . $user_info['id'] . " ORDER BY create_time DESC";		
		$condition = ' LIMIT ' . $offset . ',' . $count;
		
		$sql = $sql . $condition;
		
		$r = $this->db->query($sql);
			
		$all_video = array();
		$this->setXmlNode('video_info' , 'video');
		while($row = $this->db->fetch_array($r))
		{
			$row = hg_video_image($row['id'], $row);
			$all_video[] = $row;
			$this->addItem($row);
		}
		
		$this->addItem($total_nums);		
		$this->output();		
	}
	
	/**
	 * 获取单个视频信息
	 */
	public function single_video()
	{
		$video_id = intval(trim($this->input['id']));
		$mInfo = $this->mUser->verify_credentials();
		$mInfo['id'] = $mInfo['id']?$mInfo['id']:1;
		if($video_id)
		{
			$sql = "SELECT * 
					FROM " . DB_PREFIX . "video 
					WHERE id = " . $video_id ." AND state = 1 AND (is_show = 2 OR is_show = 3) ";
			
			$r = $this->db->query_first($sql);
		
			$r = hg_video_image($r['id'], $r);
			
			//获取用户信息
			$user_info = $this->mVideo->getUserById($r['user_id']);
			if($mInfo['id'])
			{
				$re = $this->mVideo->get_collect_relevance($mInfo['id'],$video_id,0);
				$r['relation'] = $re[$r['id']]['relation'];
			}
			else 
			{
				$r['relation'] = 0;
			}
			$r['user'] = $user_info;
			$this->setXmlNode('video_info' , 'video');
			$this->addItem($r);
			$this->output();
		}
		else
		{
			$this->errorOutput(UNKNOW);
		}	
	}
	
	
	public function show_video()
	{
		$video_id = urldecode(trim($this->input['id'])?trim($this->input['id']):0);
		$mInfo = $this->mUser->verify_credentials();
		$mInfo['id'] = $mInfo['id']?$mInfo['id']:1;
		if($video_id)
		{
			$sql = "SELECT * 
					FROM " . DB_PREFIX . "video 
					WHERE is_show=2 AND id IN(" .$video_id.")";
			
			$q = $this->db->query($sql);
			$user_id = "";
			
			while($row = $this->db->fetch_array($q))
			{
				$row = hg_video_image($row['id'], $row);
				$video[] = $row;
				$user_id .= $row['user_id'].",";
			}
			
			//获取用户信息
			$user_info = $this->mVideo->getUserById($user_id);
			if($mInfo['id']&&$video)
			{
				$re = $this->mVideo->get_collect_relevance($mInfo['id'],$video_id,0);
				foreach($video as $key =>$value)
				{
					$video[$key]['relation'] = $re[$value['id']]['relation'];
					$video[$key]['user'] = $user_info[$value['user_id']];
					unset($video[$key]['user_id']);
				}
			}
			
			$this->setXmlNode('video_info' , 'video');
			foreach($video as $key =>$value)
			{
				$this->addItem($value);
			}
			$this->output();
		}
		else
		{
			$this->errorOutput(UNKNOW);
		}
	}
	
		
	/**
	 * 获取专辑中的视频
	 */
	public function album_video()
	{
		$user_info = $this->mUser->verify_credentials();
		
		if(!$user_info)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		
		$count = intval($this->input['count']);		
		$offset = $page * $count;
		
		$album_id = $this->input['album_id'] ? intval($this->input['album_id']) : 0;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "video ";
		
		$condition = " WHERE user_id  = " . $user_info['id'] . "
					   AND album_id = " . $album_id . "
					   ORDER BY create_time DESC
				       LIMIT " . $offset . ',' . $count;
		
		$sql = $sql . $condition;		
		$q = $this->db->query($sql);
		$album_video = array();
		$this->setXmlNode('video_info' , 'video');
		while($row  = $this->db->fetch_array($q))
		{
			$row = hg_video_image($row['id'], $row);
			$album_video[] = $row;
			$this->addItem($row);
		}
		
		$this->output();		
	}
	
	/**
	 * 获取专辑
	 */
	public function album()
	{
		$user_info = $this->mUser->verify_credentials();
		
		if(!$user_info)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		if($this->input['user_id'])
		{
			$user_id = intval(trim($this->input['user_id']));	
		}
		else
		{
			$user_id = $user_info['id'];	
		} 
				
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		
		$count = intval($this->input['count']);		
		$offset = $page * $count;
				
		$sql = "SELECT * FROM " . DB_PREFIX . "album WHERE user_id = " . $user_id;
		
		$q = $this->db->query($sql);
		
		$album_array = array();
		$this->setXmlNode('album_info' , 'album');
		while($row  = $this->db->fetch_array($q))
		{
			$album_video[] = $row;
			$this->addItem($row);
		}
		
		$this->output();
	}
	
	/**
	 * 获取视频(已发布)
	 */
	public function video()
	{
		$user_info = $this->mUser->verify_credentials();

		if($this->input['user_id'])
		{
			$user_id = trim($this->input['user_id']);	
		}

						
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		$count = intval($this->input['count']?$this->input['count']:0);		
		$offset = $page * $count;
		
		$condition = '';
		
		$condition .= ' AND state = 1 AND is_show IN(2,3) ';
		
		if($user_id)
		{
			$condition .= ' AND user_id='.$user_id;
		}
		
		if(unserialize(urldecode($this->input['condition'])))
		{
			$search_condition = unserialize(urldecode($this->input['condition']));
			
			if($search_condition['keywords'])
			{
				$condition .= ' AND title like "%' . $search_condition['keywords'] . '%"';
			}
			
			if($search_condition['start_time'])
			{
				$start_time = explode('-' , $search_condition['start_time']);
				$start_time = mktime(0 , 0 , 0 , $start_time[1] , $start_time[2] , $start_time[0]);
				$condition .= ' AND create_time > ' . $start_time;
			}
			
			if($search_condition['end_time'])
			{
				$end_time = explode('-' , $search_condition['end_time']);
				$end_time = mktime(0 , 0 , 0 , $end_time[1] , $end_time[2] , $end_time[0]);
				$condition .= ' AND create_time < ' . $end_time;
			}			
		}
				
		//获取视频数目
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video WHERE 1 " . $condition;
		
		$r = $this->db->query_first($sql);
		
		$total_nums = $r['total_nums'];
						
		//显示类型
		switch(intval($this->input['show_type']))
		{
			case 1 : $condition .= ' ORDER BY update_time DESC '; break;
			case 2 : $condition .= ' ORDER BY play_count DESC '; break;
			case 3 : $condition .= ' ORDER BY comment_count DESC '; break;
			case 4 : $condition .= ' ORDER BY create_time DESC '; break;
			default: $condition .= ' ORDER BY update_time DESC ';			
		}
		
		if($count)
		{
			$condition .= ' LIMIT ' . $offset . ',' . $count;
		}
		$sql = "SELECT v.* , u.username FROM " . DB_PREFIX . "video AS v LEFT JOIN " . DB_PREFIX . "user AS u ON v.user_id = u.id WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		$my_video = array();
		$this->setXmlNode('video_info' , 'video');
		$id = "";
		$space = " ";
		
		while($row  = $this->db->fetch_array($q))
		{
			$row = hg_video_image($row['id'], $row);
			$id .= $space.$row['id'];
			$space = ',';
			$my_video[] = $row;
		}
		$re = $this->mVideo->get_collect_relevance($user_info['id'],$id,0);
		foreach($my_video as $key=>$value)
		{
			$value['relation'] = $re[$value['id']]['relation'];
			$this->addItem($value);
		}
		$this->addItem($total_nums);
		$this->output();		
	}

	
	/**
	 * 待审核视频
	 */
	public function verify_video()
	{
		/**
		 * 此处要加入管理员验证
		 */
		
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		
		$count = intval($this->input['count']);		
		$offset = $page * $count;
		
		$condition = '';
		
		$condition .= ' AND state = 1 AND is_show = 0';
		
		if(unserialize(urldecode($this->input['condition'])))
		{
			$search_condition = unserialize(urldecode($this->input['condition']));
			
			if($search_condition['keywords'])
			{
				$condition .= ' AND title like "%' . $search_condition['keywords'] . '%"';
			}
			
			if($search_condition['start_time'])
			{
				$start_time = explode('-' , $search_condition['start_time']);
				$start_time = mktime(0 , 0 , 0 , $start_time[1] , $start_time[2] , $start_time[0]);
				$condition .= ' AND create_time > ' . $start_time;
			}
			
			if($search_condition['end_time'])
			{
				$end_time = explode('-' , $search_condition['end_time']);
				$end_time = mktime(0 , 0 , 0 , $end_time[1] , $end_time[2] , $end_time[0]);
				$condition .= ' AND create_time < ' . $end_time;
			}			
		}

		//获取视频数目
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video WHERE 1 " . $condition;
				
		$r = $this->db->query_first($sql);
		
		$total_nums = $r['total_nums'];

		//显示类型
		switch(intval($this->input['show_type']))
		{
			case 1 : $condition .= ' ORDER BY update_time DESC '; break;
			case 2 : $condition .= ' ORDER BY play_count DESC '; break;
			case 3 : $condition .= ' ORDER BY comment_count DESC '; break;
			default: $condition .= ' ORDER BY update_time DESC ';			
		}
		
		$condition .= ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT v.* , u.username FROM " . DB_PREFIX . "video AS v LEFT JOIN " . DB_PREFIX . "user AS u ON v.user_id = u.id WHERE 1 " . $condition;
		
		$q = $this->db->query($sql);

		$this->setXmlNode('video_info' , 'video');
		
		while($row  = $this->db->fetch_array($q))
		{
			$row = hg_video_image($row['id'], $row);
			$this->addItem($row);			
		}
		
		$this->addItem($total_nums);
		$this->output();
	}
		
	/**
	 * 未通过审核的视频
	 */
	public function unpass_video()
	{
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		
		$count = intval($this->input['count']);		
		$offset = $page * $count;
		
		$condition = '';
		
		$condition .= ' AND state = 1 AND is_show = 1';

		if(unserialize(urldecode($this->input['condition'])))
		{
			$search_condition = unserialize(urldecode($this->input['condition']));
			
			if($search_condition['keywords'])
			{
				$condition .= ' AND title like "%' . $search_condition['keywords'] . '%"';
			}
			
			if($search_condition['start_time'])
			{
				$start_time = explode('-' , $search_condition['start_time']);
				$start_time = mktime(0 , 0 , 0 , $start_time[1] , $start_time[2] , $start_time[0]);
				$condition .= ' AND create_time > ' . $start_time;
			}
			
			if($search_condition['end_time'])
			{
				$end_time = explode('-' , $search_condition['end_time']);
				$end_time = mktime(0 , 0 , 0 , $end_time[1] , $end_time[2] , $end_time[0]);
				$condition .= ' AND create_time < ' . $end_time;
			}			
		}

		//获取视频数目
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video WHERE 1 " . $condition;
		
		$r = $this->db->query_first($sql);
		
		$total_nums = $r['total_nums'];
				
		
		//显示类型
		switch(intval($this->input['show_type']))
		{
			case 1 : $condition .= ' ORDER BY update_time DESC '; break;
			case 2 : $condition .= ' ORDER BY play_count DESC '; break;
			case 3 : $condition .= ' ORDER BY comment_count DESC '; break;
			default: $condition .= ' ORDER BY update_time DESC ';			
		}
		
		$condition .= ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT v.* , u.username FROM " . DB_PREFIX . "video AS v LEFT JOIN " . DB_PREFIX . "user AS u ON v.user_id = u.id WHERE 1 " . $condition;

		$q = $this->db->query($sql);

		$this->setXmlNode('video_info' , 'video');
		
		while($row  = $this->db->fetch_array($q))
		{
			$row = hg_video_image($row['id'], $row);
			$this->addItem($row);			
		}
		
		$this->addItem($total_nums);
		$this->output();
	}
	
	
	/**
	 * 推荐中的视频
	 */
	public function recommend_video()
	{
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		
		$count = intval($this->input['count']);	
			
		$offset = $page * $count;
		
		$condition = '';
		
		$condition .= ' AND is_recommend = 1';	
		
		if(unserialize(urldecode($this->input['condition'])))
		{
			$search_condition = unserialize(urldecode($this->input['condition']));
			
			if($search_condition['keywords'])
			{
				$condition .= ' AND title like "%' . $search_condition['keywords'] . '%"';
			}
			
			if($search_condition['start_time'])
			{
				$start_time = explode('-' , $search_condition['start_time']);
				$start_time = mktime(0 , 0 , 0 , $start_time[1] , $start_time[2] , $start_time[0]);
				$condition .= ' AND create_time > ' . $start_time;
			}
			
			if($search_condition['end_time'])
			{
				$end_time = explode('-' , $search_condition['end_time']);
				$end_time = mktime(0 , 0 , 0 , $end_time[1] , $end_time[2] , $end_time[0]);
				$condition .= ' AND create_time < ' . $end_time;
			}			
		}
		
		//获取视频数目
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video WHERE 1 " . $condition;
		
		$r = $this->db->query_first($sql);
		
		$total_nums = $r['total_nums'];
				
		
		//显示类型
		switch(intval($this->input['show_type']))
		{
			case 1 : $condition .= ' ORDER BY update_time DESC '; break;
			case 2 : $condition .= ' ORDER BY play_count DESC '; break;
			case 3 : $condition .= ' ORDER BY comment_count DESC '; break;
			default: $condition .= ' ORDER BY update_time DESC ';			
		}
		
		$condition .= ' LIMIT ' . $offset . ',' . $count;

		$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE 1 " . $condition;
			
		$q = $this->db->query($sql);

		$this->setXmlNode('video_info' , 'video');
		
		while($row  = $this->db->fetch_array($q))
		{
			$row = hg_video_image($row['id'], $row);
			$this->addItem($row);			
		}
		
		$this->addItem($total_nums);
		$this->output();
	}
	
	
	/**
	 * 需审核才能删除的视频(推荐的视频)
	 */
	public function verify_delete_video()
	{
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		
		$count = intval($this->input['count']);	
			
		$offset = $page * $count;
		
		$condition = '';
		
		$condition .= ' AND state = 1 AND is_show = 3';

		if(unserialize(urldecode($this->input['condition'])))
		{
			$search_condition = unserialize(urldecode($this->input['condition']));
			
			if($search_condition['keywords'])
			{
				$condition .= ' AND title like "%' . $search_condition['keywords'] . '%"';
			}
			
			if($search_condition['start_time'])
			{
				$start_time = explode('-' , $search_condition['start_time']);
				$start_time = mktime(0 , 0 , 0 , $start_time[1] , $start_time[2] , $start_time[0]);
				$condition .= ' AND create_time > ' . $start_time;
			}
			
			if($search_condition['end_time'])
			{
				$end_time = explode('-' , $search_condition['end_time']);
				$end_time = mktime(0 , 0 , 0 , $end_time[1] , $end_time[2] , $end_time[0]);
				$condition .= ' AND create_time < ' . $end_time;
			}			
		}
		
		//获取视频数目
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video WHERE 1 " . $condition;
		
		$r = $this->db->query_first($sql);
		
		$total_nums = $r['total_nums'];
				
		
		//显示类型
		switch(intval($this->input['show_type']))
		{
			case 1 : $condition .= ' ORDER BY update_time DESC '; break;
			case 2 : $condition .= ' ORDER BY play_count DESC '; break;
			case 3 : $condition .= ' ORDER BY comment_count DESC '; break;
			default: $condition .= ' ORDER BY update_time DESC ';			
		}
		
		$condition .= ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE 1 " . $condition;
		
		$q = $this->db->query($sql);

		$this->setXmlNode('video_info' , 'video');
		
		while($row  = $this->db->fetch_array($q))
		{
			$row = hg_video_image($row['id'], $row);
			$this->addItem($row);			
		}
		
		$this->addItem($total_nums);
		$this->output();
	}
	
	/**
	 * 转码中的视频
	 */
	public function transcode_video()
	{
		/**
		 * 此处要加入管理员验证
		 */
		
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		
		$count = intval($this->input['count']);		
		$offset = $page * $count;

		//获取视频数目
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video WHERE state = 0 ";
		
		$r = $this->db->query_first($sql);
		
		$total_nums = $r['total_nums'];
				
		$sql = "SELECT v.* , u.username FROM " . DB_PREFIX . "video AS v LEFT JOIN " . DB_PREFIX . "user AS u ON v.user_id = u.id ";
		
		//用户前台显示的发布的视频
		$condition = " WHERE state = 0 ";
		
		//显示类型
		switch(intval($this->input['show_type']))
		{
			case 1 : $condition .= ' ORDER BY update_time DESC '; break;
			case 2 : $condition .= ' ORDER BY play_count DESC '; break;
			case 3 : $condition .= ' ORDER BY comment_count DESC '; break;
			default: $condition .= ' ORDER BY update_time DESC ';			
		}
		
		$condition .= ' LIMIT ' . $offset . ',' . $count;
		
		$sql = $sql . $condition;		
		$q = $this->db->query($sql);

		$this->setXmlNode('video_info' , 'video');
		
		while($row  = $this->db->fetch_array($q))
		{
			$row = hg_video_image($row['id'], $row);
			$this->addItem($row);			
		}
		
		$this->addItem($total_nums);
		$this->output();
	}
	
			
	/**
	* 视频检索
	* @param $title
	* @param $page
	* @param $count
	* @return $ret 视频信息
	*/
	public function search()
	{
		$title = urldecode($this->input['title']? $this->input['title']:"");
		$page = $this->input['page'];
		$count = $this->input['count'];
		$offset = $page * $count;
		$end = " LIMIT $offset , $count";
		if(!$title)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video 
					WHERE concat(title,brief,tags) LIKE '%".$title."%' AND state = 1 AND (is_show = 2 OR is_show = 3) ";		
		$r = $this->db->query_first($sql);
		$total_nums = $r['total_nums'];
		
		$sql = "SELECT * 
					FROM " . DB_PREFIX . "video 
					WHERE concat(title,brief,tags) LIKE '%".$title."%' AND state = 1 AND (is_show = 2 OR is_show = 3) ORDER BY create_time DESC ".$end;
		$query = $this->db->query($sql);
		
		$video_info = array();
		$this->setXmlNode('video' , 'video_info');
		while($row  = $this->db->fetch_array($query))
		{
			$row = hg_video_image($row['id'], $row);
			$video_info[] = $row;
			$this->addItem($row);
		}	
		$this->addItem($total_nums);
		$this->output();		
	}
	
	
	/**
	 * 搜索条件处理
	 */
	public function deal_search_condition()
	{
		
	}
	

	/**
	 * 修改视频的收藏数目
	 * @param $video_id
	 * @param $type(1=+1,0=-1)
	 * @return $video_id 
	 */
	public function favorite_count()
	{
		$mInfo = $this->mUser->verify_credentials();
		$video_id = $this->input['video_id']? $this->input['video_id']:1;
		$type = $this->input['type']? $this->input['type']:1;//默认增加
		
		if(!$mInfo&&!$video_id)
		{
			$this->errorOutput(OBJECT_NULL);	
		}
		
		$sql = "UPDATE " . DB_PREFIX . "video SET collect_count=";
		if($type)
		{
			$sql .="collect_count+1";
		}
		else 
		{
			$sql .="collect_count-1";
		}
		
		$sql .= " WHERE id = ".$video_id;
		$this->db->query($sql);
		$this->setXmlNode('video' , 'video_info');
		$this->addItem($video_id);
		$this->output();
	}
	
			
	/**
	* 获取相关视频（标签）
	* @param $video_id
	* @param $page
	* @param $count
	* @return $ret 视频信息
	*/
	public function tags_search()
	{
		$video_id = $this->input['video_id']? $this->input['video_id']:0;
		
		$page = $this->input['page']?$this->input['page']:0;
		$count = $this->input['count']?$this->input['count']:6;
		$offset = $page * $count;
		$end = " ORDER BY update_time DESC ";
		if($count)
		{
			$end .= " LIMIT $offset , $count ";
		}
		if(!$video_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE id=".$video_id;
		$first = $this->db->query_first($sql);
		
		if(!$first['tags'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$tag = explode(",", $first['tags']);
		
		$tags = "";
		$space = "";
		foreach($tag as $k => $v)
		{
			$tags .= $space."'".$v."'";
			$space =",";
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE tagname IN(".$tags.")";
		$q = $this->db->query($sql);
		$tags_id = "";
		$space = "";
		while($row = $this->db->fetch_array($q))
		{
			$tags_id .= $space.$row['id'];
			$space =",";
		}
		
		$sql =  "SELECT * FROM " . DB_PREFIX . "video_tags WHERE tag_id IN(".$tags_id.")";
		$q = $this->db->query($sql);
		$videos_id = "";
		$space = "";
		while($row = $this->db->fetch_array($q))
		{
			if($row['video_id'] != $video_id)
			{
				$videos_id .= $space.$row['video_id'];
				$space =",";
			}
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE id IN(".$videos_id.") AND state = 1 AND (is_show = 2 OR is_show = 3)".$end;
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{
			$row = hg_video_image($row['id'], $row);
			$video[]= $row;
		}
		
		if($count)
		{
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "video WHERE id IN(".$videos_id.")";
			$q = $this->db->query_first($sql);
			$video['total'] = $q['total'];
		}
		
		$this->setXmlNode('video' , 'video_info');
		$this->addItem($video);
		$this->output();		
	}

	/**
	* 获取相关视频（标签）（视频播放版）
	* @param $video_id
	* @param $page
	* @param $count
	* @return $ret 视频信息
	*/
	public function tags_search_video()
	{
		$video_id = $this->input['video_id']? $this->input['video_id']:0;
		$count = 6;
		$end = " ORDER BY update_time DESC ";
		if($count)
		{
			$end .= " LIMIT 6";
		}
		if(!$video_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE id=".$video_id;
		$first = $this->db->query_first($sql);
		
		if(!$first['tags'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$tag = explode(",", $first['tags']);
		
		$tags = "";
		$space = "";
		foreach($tag as $k => $v)
		{
			$tags .= $space."'".$v."'";
			$space =",";
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE tagname IN(".$tags.")";
		$q = $this->db->query($sql);
		$tags_id = "";
		$space = "";
		while($row = $this->db->fetch_array($q))
		{
			$tags_id .= $space.$row['id'];
			$space =",";
		}
		
		$sql =  "SELECT * FROM " . DB_PREFIX . "video_tags WHERE tag_id IN(".$tags_id.")";
		$q = $this->db->query($sql);
		$videos_id = "";
		$space = "";
		while($row = $this->db->fetch_array($q))
		{
			if($row['video_id'] != $video_id)
			{
				$videos_id .= $space.$row['video_id'];
				$space =",";
			}
		}

		if($videos_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE id IN(".$videos_id.") AND state = 1 AND (is_show = 2 OR is_show = 3)".$end;
			$q = $this->db->query($sql);
			$string = "";
			while($row = $this->db->fetch_array($q))
			{
				if($this->settings['rewrite'])
				{
					$vurl = SNS_VIDEO . "video-" . $row['id'] .".html";	
				}
				else 
				{
					$vurl = SNS_VIDEO . "video_play.php?id=" . $row['id'];	
				}
				$info[] = array('image'=>stripslashes($row['schematic']),'url'=> $vurl,'text'=>$row['title']);
				$string .= ',{"image":"' . $row['schematic'] . '","url":"' . $vurl . '","text":"' . $row['title']. '"}';
			}

			$result = array("result"=>$info);
			$string = '{"result":[' . substr($string, 1) . ']}';
			echo json_encode($result);
		}
		return false;
	}
	
	public function video_threads()
	{
		
		$mInfo = $this->mUser->verify_credentials();		
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$video_id = $this->input['video_id']? $this->input['video_id']:0;
		if(!$video_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql = "UPDATE " . DB_PREFIX . "video SET is_thread = 1 WHERE id = ".$video_id;
		$this->db->query($sql);
		$this->setXmlNode('video' , 'video_info');
		$this->addItem($video_id);
		$this->output();
	}
	
	public function get_video_num()
	{
		$sql = "SELECT COUNT(*) as nums FROM " . DB_PREFIX . "video";
		$r = $this->db->query_first($sql);
		echo $r['nums'];
	}
	
	/**
	 * 获取需要修复的视频
	 */
	public function get_repair_video()
	{
		$page = $this->input['page'] ? $this->input['page'] : 0;
		
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		
		$count = intval($this->input['count']);		
		$offset = $page * $count;

		//获取视频数目
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video WHERE state = 1 AND (schematic = '' OR bschematic = '' OR streaming_media = '' OR toff = '')";
		$r = $this->db->query_first($sql);		
		$total_nums = $r['total_nums'];
		
		$condition = ' LIMIT ' . $offset . ',' . $count;
		$sql = "SELECT  v.* , u.username FROM " . DB_PREFIX . "video AS v LEFT JOIN " . DB_PREFIX . "user AS u ON v.user_id = u.id WHERE v.state = 1 AND (v.schematic = '' OR v.bschematic = '' OR v.streaming_media = '' OR v.toff = '')" ;
		
		$sql = $sql . $condition;		
		$q = $this->db->query($sql);
		
		$this->setXmlNode('video_info' , 'video');
		while($row = $this->db->fetch_array($q))
		{
			$this->addItem($row);
		}
		$this->addItem($total_nums);
		
		$this->output();
	}
	
	/**
	 * 获取用户已发布的视频（包括多个用户）
	 * @param $user_id  用户ID，用,隔开
	 * @param $show_type 排序依据  1:更新时间 2：播放次数 3：评论次数  默认为创建时间
	 * @param $action  排序方式 升序 /降序  1为升序 0为降序
	 * @param $page 
	 * @param $count 
	 */
	public function user_video()
	{
		$page = $this->input['page'] ? $this->input['page'] : 0;
		$count = intval($this->input['count']?$this->input['count']:50);	
		if($count > 200)
		{
			$count = 200;
		}
		$offset = $page * $count;
		$condition = ' AND state = 1 AND is_show IN(2,3) ';
		if($this->input['user_id'])
		{
			$condition .= ' AND user_id IN ('.urldecode($this->input['user_id']).')';
		}
				
		//获取视频数目
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video WHERE 1 " . $condition;
		
		$r = $this->db->query_first($sql);
		
		$total_nums = $r['total_nums'];

		if($this->input['action'])
		{
			$action = " ASC ";
		}
		else
		{
			$action = " DESC ";
		}
		//显示类型
		switch(intval($this->input['show_type']))
		{
			case 1 : $condition .= ' ORDER BY update_time '.$action; break; //更新时间
			case 2 : $condition .= ' ORDER BY play_count '.$action; break; //播放次数
			case 3 : $condition .= ' ORDER BY comment_count '.$action; break; //评论次数
			default: $condition .= ' ORDER BY create_time '.$action;//创建时间	
		}
		
		if($count)
		{
			$condition .= ' LIMIT ' . $offset . ',' . $count;
		}
		$sql = "SELECT v.* , u.username FROM " . DB_PREFIX . "video AS v LEFT JOIN " . DB_PREFIX . "user AS u ON v.user_id = u.id WHERE 1 " . $condition;
		$q = $this->db->query($sql);
		$my_video = array();
		$this->setXmlNode('video_info' , 'video');
		$id = "";
		$space = " ";
		
		while($row  = $this->db->fetch_array($q))
		{
			$row = hg_video_image($row['id'], $row);
			$id .= $space.$row['id'];
			$space = ',';
			$my_video[] = $row;
		}
		$re = $this->mVideo->get_collect_relevance($user_info['id'],$id,0);
		foreach($my_video as $key=>$value)
		{
			$value['relation'] = $re[$value['id']]['relation'];
			$this->addItem($value);
		}
		$this->addItem($total_nums);
		$this->output();	
	}
}

$out = new getVideosInfoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'tags_search_video';//不能动
}
$out->$action();
?>