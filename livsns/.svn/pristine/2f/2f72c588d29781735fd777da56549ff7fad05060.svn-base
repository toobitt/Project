<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|get_current_program_item|update_mms_list|detail|count|getNowChange
* @private function get_condition|live_program
*
* $Id: mms_control.php 4689 2011-10-11 08:36:41Z lijiaying $
***************************************************************************/
require('global.php');
class mmsControlApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include channel.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/channel.class.php');
		$this->obj = new channels();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 直播列表显示 (包括当前播放节目信息)
	 * @name show
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $offset 分页参数
	 * @param $count 分页显示记录数
	 * @param $id int 频道ID
	 * @param $return array 频道的核心数据 
	 * @return $v array 所有频道直播内容信息
	 */
	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$return = $this->obj->channelsInfo($condition,$offset,$count);
		
		//获取备播流地址
		$id = trim(urldecode($this->input['id']));
		if($id)
		{
			if (!$return[$id]['is_live'])
			{
				$this->errorOutput('对不起， 该频道不支持播控！');
			}
			$beibo_ids = @array_keys($return[$id]['beibo']);
			if($beibo_ids)
			{
				$sql = "SELECT id,ch_name,other_info FROM " . DB_PREFIX . "stream WHERE id IN (" . implode(',', $beibo_ids) .')';
				$q = $this->db->query($sql);
			}
			else 
			{
				$this->errorOutput('该频道没有备播信号');
			}
			$beibo_stream_urls = array();
			while($r = $this->db->fetch_array($q))
			{
				$r['other_info'] = unserialize($r['other_info']);
				$beibo_stream_urls[$r['id']] = $r;
			}
			
			foreach ($beibo_stream_urls AS $k=>$v)
			{
				foreach ($v['other_info'] AS $vv)
				{
					$urls = hg_get_stream_url($this->settings['tvie']['up_stream_server'], array('channel' => $v['ch_name'], 'stream_name' => $vv['name']));
					$all_beibo_stream_urls[$k][] = $urls;							
				}
				$return[$id]['beibo_stream_urls'] = $all_beibo_stream_urls;
			}
		}
		$channel_id = @array_keys($return);
		$current_program = $this->get_current_program_item($channel_id, true);
		$live_program = $this->live_program($channel_id, TIMENOW);
		if($return)
		{
			foreach ($return AS $v)
			{
				if(is_array($v['streams']))
				{
					$this->settings['tvie']['stream_server']['rand'] = 1;  //$this->input['rand_stream']  暂时这样处理
					$v['primary_stream_url'] = $v['down_stream_url'] = array();
					foreach ($v['streams'] AS $vv)
					{
						$v['primary_stream_url'][] = $vv['stream_uri'];
						$v['down_stream_url'][] = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' =>$v['code'], 'stream_name' => $vv['out_stream_name']));
					}
				}
				//获取当前频道正在播放的节目
				if ($current_program[$v['id']]['pro'])
				{
					$v['current'] = $current_program[$v['id']]['pro'];
					$v['start_time'] = date('H:i:s',$current_program[$v['id']]['start_time']);
					$v['end_time'] = date('H:i:s',$current_program[$v['id']]['start_time'] + $current_program[$v['id']]['toff']);
				}
				else if ($live_program[$v['id']]['current'])
				{
					$v['current'] = $live_program[$v['id']]['current'];
					$v['start_time'] = $live_program[$v['id']]['start_time'];
					$v['end_time'] = $live_program[$v['id']]['end_time'];
				}
				else 
				{
					$v['current'] = '精彩节目';
					$v['start_time'] = date('H:i:s');
					$v['end_time'] = date('H:i:s',TIMENOW+1800);
				}
				$v['all_channel_ids'] = implode(',', $channel_id);
				$v['_snap'] = $current_program[$v['id']]['img'];
				$this->addItem($v);
		//		hg_pre($v);
			}
		}
		$this->output();
	}

	/**
	 * 根据播放时间，频道ID，取当前播放的节目
	 * @name live_program
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id string 所有频道ID(用,隔开)
	 * @param $play_time int 播放时间
	 * @return $channel_info array 所传频道的播放时间的节目
	 */
	private function live_program($channelId, $play_time)
	{
		$channel_id = implode(',', $channelId);
		if(!$channel_id || !$play_time)
		{
			$this->output(OBJECT_NULL);
		}
		$tmp = @array_unique($channelId);
		$channel_info = array();
		if ($tmp)
		{
			foreach($tmp as $k => $v)
			{
				$channel_info[$v] = 0;
			}
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "program WHERE channel_id IN(" . $channel_id . ") AND  " . $play_time . ">= start_time AND " . $play_time . " <= (start_time+toff)";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$channel_info[$row['channel_id']] = array(
					'current' => $row['theme'],
					'start_time' => date('H:i:s',$row['start_time']), 
					'end_time' => date('H:i:s',$row['start_time']+$row['toff'])
					);
		}
		$channel_id_tmp = $space = '';
		foreach($channel_info as $k => $v)
		{
			if(!$v)
			{
				$channel_id_tmp .= $space . $k;
				$space = ',';
			}
		}
		if($channel_id_tmp)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE 1 and p.channel_id IN (" . $channel_id_tmp . ") AND r.week_num=" . date("N",$play_time) . " ORDER BY p.start_time ASC";
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$start = strtotime(date('Y-m-d ',$play_time) . date('H:i:s',$row['start_time']));
				$end = strtotime(date('Y-m-d ',$play_time) . date('H:i:s',$row['start_time']+$row['toff']));
				if($play_time >= $start && $play_time <= $end)
				{
					$channel_info[$row['channel_id']] = array(
					'current' => $row['program_name'],
					'start_time' => date('H:i:s',$row['start_time']), 
					'end_time' => date('H:i:s',$row['start_time']+$row['toff'])
					);
				}
			}
		}
		return $channel_info;
	}
	
	/**
	 * 获取当前播放节目信息以及频道预览图片
	 * @name get_current_program_item
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id array 所有频道ID
	 * @return $item array 所有频道当前播放节目信息以及频道预览图片
	 */
	public function get_current_program_item($channel_id, $img=false)
	{
		
		//初始化数组
		$item = array();
		if(!$channel_id)
		{
			return false;
		}
		if(is_array($channel_id))
		{
			$channel_id = implode(',', $channel_id);
		}
		else
		{
			$channel_id = intval($channel_id);
		}
		$sql = "SELECT * FROM ".DB_PREFIX.'program  WHERE channel_id IN(' . $channel_id . ') ';
		$sql .= ' AND ' . TIMENOW . ' BETWEEN start_time AND start_time + toff';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$item[$r['channel_id']]['pro'] = $r['theme'];
			$item[$r['channel_id']]['start_time'] = $r['start_time'];
			$item[$r['channel_id']]['toff'] = $r['toff'];
		}
		if($img)
		{
			//取出频道上游名称 用于获得频道预览图
			$condition = $this->get_condition();
			$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
			$count = $this->input['count'] ? intval($this->input['count']) : 20;
			$return = $this->obj->channelsInfo($condition,$offset,$count);
			
			if ($return)
			{
				foreach ($return AS $r)
				{
					$item[$r['id']]['pro'] = $item[$r['id']]['pro'] ? $item[$r['id']]['pro'] : '精彩节目';
					$item[$r['id']]['img'] = MMS_CONTROL_LIST_PREVIEWIMG_URL . $r['ch_id'] . '/' . $r['main_stream_name'] . '/' . (TIMENOW*1000) . '/172x130.png';
				
					if (!$r['s_status'] || !$r['stream_state_tag'])
					{
						$item[$r['id']]['img'] = '';
					}
				}
			}
		}
		return $item;
	}
	
	/**
	 * 获取当前播放节目信息以及频道预览图片
	 * @name get_current_program_item
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_ids array 所有频道ID
	 * @return $items array 所有频道当前播放节目信息以及频道预览图片
	 */
	public function update_mms_list()
	{
		if(!$this->input['channel_ids'])
		{
			return;
		}
		$channel_ids = trim(urldecode($this->input['channel_ids']));
		$channel_ids = explode(',', $channel_ids);
		$items = $this->get_current_program_item($channel_ids, true);
		$this->addItem($items);
		$this->output();
	}
		
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $ret string 总数，json串
	 */
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "channel AS v WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$ret = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($ret);
	}
	
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' and name like \'%'.urldecode($this->input['k']).'%\'';
		}
		if($this->input['id'])
		{
			$condition .= ' AND c.id IN('.trim(urldecode($this->input['id'])).')';
		}
		if(isset($this->input['stream_state']))
		{
			$condition .= ' AND stream_state=' . intval($this->input['stream_state']);
		}
		return $condition;
	}
	
	/**
	 * 播控
	 * @name detail
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道ID
	 * @return $v array 单条频道信息
	 */
	public function detail()
	{
		if(!$this->input['id'])
		{
			return;
		}
		$this->show();
	}

	/**
	 * 获取所有备播文件
	 * @name get_all_beibo_files
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $r array 所有备播文件信息
	 */
	public function get_all_beibo_files()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . 'backup WHERE 1';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			if($r['vodinfo_id'])
			{
				$r['beibo_file_url'] = $this->settings['vod_url'].$r['filepath'] . $r['newname'];
			}
			else 
			{
				$r['beibo_file_url'] = UPLOAD_BACKUP_MMS_URL . hg_num2dir($r['id']) . $r['newname'];
			}
			if($r['toff'])
			{
				if(intval($r['toff']/1000/60))
				{
					$r['toff'] = intval($r['toff']/1000/60) . "'" . intval(($r['toff']/1000/60-intval($r['toff']/1000/60))*60) .'"' ;
				}
				else 
				{
					$r['toff'] = intval($r['toff']/1000) . '"' ;
				}
			}
			$this->addItem($r);
		}
		$this->output();
	}
	
	/**
	 * 获取当前正在播放的串联单
	 * @name getNowChange
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @return $name string 当前正在播放的串联单
	 */
	public function getNowChange()
	{
		$channel_id = intval($this->input['channel_id']);
		if(!$channel_id)
		{
			$this->errorOutput('未传入频道ID');
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan WHERE channel_id=" . $channel_id;
		$sql .= " AND " . TIMENOW . " BETWEEN change_time AND change_time + toff";
		$channel_chg_plan = $this->db->query_first($sql);
		
		$channel2_id = $channel_chg_plan['channel2_id'];
		if($channel_chg_plan)
		{
			if($channel_chg_plan['type'] == 3)
			{
				$programSql = "SELECT * FROM " . DB_PREFIX . "program WHERE channel_id=" . $channel2_id;
				$programSql .= " AND start_time=" . $channel_chg_plan['program_start_time'];
			}
			else if($channel_chg_plan['type'] == 2)
			{
				$programSql = "SELECT * FROM " . DB_PREFIX . "backup WHERE id=" . $channel2_id;
			}
			else
			{
				$programSql = "SELECT * FROM " . DB_PREFIX . "program WHERE channel_id=" . $channel2_id;
				$programSql .= " AND " . TIMENOW . " BETWEEN start_time AND start_time + toff";
			}
			$program = $this->db->query_first($programSql);
		}
		else 
		{
			return;
		}
		if($channel_chg_plan['type'] == 2)
		{
			$name = $program['title'];
		}
		else 
		{
			$name = $program['theme'];
		}
		$this->addItem($name);
		$this->output();
	}
}
$out = new mmsControlApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>