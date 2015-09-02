<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
*@public function create
*@private function verify_timeline|array_group|set_chg_uris
*
* $Id: channel_chg_plan_create.php 
***************************************************************************/
require('global.php');
class ChannelChgPlanCreateApi extends BaseFrm
{
	private $mChgUris = array();
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 创建、更新 串联单
	 * @name create
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $date string 日期
	 * @param $chg_plan_ids array 串联单ID
	 * @param $start_time array 开始时间
	 * @param $end_time array 结束时间
	 * @param $type array 来源类型
	 * @param $channel2_id array 来源类型ID
	 * @param $channel2_name array 来源类型名称
	 * @param $program_start_time array 时移开始时间
	 * @param $epg_id array 32接口返回串联单ID
	 * @param $hidden_temp array 标记该条串联单是否被修改过 (1-是 0-否)
	 * @param $toff array 时长
	 * @param $uri array 流地址
	 * @param $create_time array 创建时间
	 * @param $update_time array 更新时间
	 * @param $admin_id array 用户ID
	 * @param $admin_name array 用户名
	 * @param $ip array 创建者IP
	 * @return $ids array 创建成功串联单IDD
	 * @include tvie_api.php
	 */
	function create()
	{
		$channel_id = intval($this->input['channel_id']);
		if (!$channel_id)
		{
			$this->errorOutput('请选择频道');
		}
		$date = urldecode($this->input['chg_date']);
		if ($date < date('Y-m-d'))
		{
			$this->errorOutput('此日期已过，无法设置串联单');
		}
		$sql = "SELECT chg_id, is_live FROM " . DB_PREFIX . "channel WHERE id=" . $channel_id;
		$channel_info = $this->db->query_first($sql);
		if (!$channel_info['is_live'] && empty($channel_info))
		{
			$this->errorOutput('该频道不支持播控或频道不存在');
		}
		$chg_plan_ids = $this->input['ids'];
		$start_time = $this->input['start_times'];
		$end_time = $this->input['end_times'];
		$type = $this->input['type'];
		$channel2_id = $this->input['channel2_ids'];
		$channel2_name = $this->input['channel2_name'];
		$program_start_time = $this->input['program_start_time'];
		$epg_id = $this->input['epg_id'];
		$hidden_temp = $this->input['hidden_temp'];
		
		if ($start_time)
		{
			for ($j = 0; $j < count($this->input['start_times']); $j++)
			{
				if ($date == date('Y-m-d') && strtotime(date('Y-m-d') . ' ' . $start_time[$j]) < TIMENOW)
				{
					$this->errorOutput('此刻不能添加串联单');
				}
				
				if (!$start_time[$j])
				{
					$this->errorOutput('开始时间不能为空');
				}
				
				if (!$end_time[$j])
				{
					$this->errorOutput('结束时间不能为空');
				}
				
				if (!$channel2_id[$j])
				{
					$this->errorOutput('请选择频道或者备播文件或者时移节目');
				}
				
				if (!$channel2_name[$j])
				{
					$this->errorOutput('请选择频道或者备播文件或者时移节目');
				}
			}
		}
		
		if (!$start_time)
		{
			$this->errorOutput('未设置任何串联单');
		}
		if (!$this->verify_timeline($this->input['start_times'], $this->input['end_times'], $this->input['chg_date']))
		{
			$this->errorOutput('时间设置存在重复');
		}
		$this->set_chg_uris($channel2_id, $type);
		
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$tvie_api = new TVie_api($this->settings['tvie']['up_stream_server']);

			if (!$tvie_api)
			{
				$this->errorOutput('媒体服务器未启动');
			}
		}
		
		$ids = $epg_ids = array();
		foreach ($chg_plan_ids AS $i => $id)
		{
			if(strlen($id) >= 12)
			{
				$id = '';
			}
			$uri = $this->mChgUris[$i];
			if (!$uri)
			{
				continue;
			}
			$start = strtotime($date . ' ' . urldecode($start_time[$i]));
			$end = strtotime($date . ' ' . urldecode($end_time[$i]));
			$toff = $end - $start;
			if ($program_start_time[$i])
			{
				$program_start = strtotime(urldecode($program_start_time[$i]));
				$uri .= $program_start . '000,' . ($program_start + $toff) . '000';
			}
			$epg = array();
			
			$data = array(
				'channel_id' => $channel_id,
				'channel2_id' => $channel2_id[$i],
				'channel2_name' => urldecode($channel2_name[$i]),
				'change_time' => $start,
				'toff' => $toff,
				'type' => $type[$i],
				'stream_uri' => $uri,
				'program_start_time' => $program_start,
			);

			if ($id)
			{
				$epg_ids[$i] = $epg_id[$i];
				
				if($hidden_temp[$i])
				{
					$epg = $tvie_api->update_channel_epg($channel_info['chg_id'], $epg_id[$i], $start, $end, $uri, '播放' . urldecode($channel2_name[$i]) . '的节目');

					if (!$epg['result']['id'])
					{
						$this->errorOutput('媒体服务器数据出现异常');
					}
					
					$data['update_time'] = TIMENOW;
					$sql = "UPDATE " . DB_PREFIX . "channel_chg_plan SET ";
					$space = "";
					$sql_extra = "";
					foreach($data as $key => $value)
					{
						if($value)
						{
							$sql_extra .= $space . $key . "=" . "'" . $value . "'";
							$space = ",";
						}
					}
					if($sql_extra)
					{
						$sql .= $sql_extra . " WHERE id=" . $id;
						$this->db->query($sql);
					}
				}
				$ids[$i] = $id;
			}
			else 
			{
				$epg = $tvie_api->create_channel_epg($channel_info['chg_id'], $start, $end, $uri, '播放' . urldecode($channel2_name[$i]) . '的节目');
				
				if (!$epg['result']['id'])
				{
					$this->errorOutput('媒体服务器数据出现异常');
				}
				
				$data['epg_id'] = $epg['result']['id'];
				$data['dates'] = $date;
				$data['create_time'] = TIMENOW;
				$data['update_time'] = TIMENOW;
				$data['admin_name'] = $this->user['user_name'];
				$data['admin_id'] = $this->user['user_id'];
				$data['ip'] = hg_getip();
				
				$createsql = "INSERT INTO " . DB_PREFIX . "channel_chg_plan SET ";
				$space = "";
				foreach($data as $key => $value)
				{
					$createsql .= $space . $key . "=" . "'" . $value . "'";
					$space = ",";
				}
			
				$this->db->query($createsql);
				$data['id'] = $this->db->insert_id();
				
				$ids[$i] = $data['id'];
				$epg_ids[$i] = $epg['result']['id'];
			}
		}
		$this->setXmlNode('channel_chg_plan' ,'data');
		$return = array(
			'ids' => $ids,
			'epg_ids' => $epg_ids,
		);
		$this->addItem($return);
		$this->output();
	}
		
	/**
	 * 时间验证
	 * @name verify_timeline
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $starttime array 开始时间
	 * @param $endtime array 结束时间
	 * @return true or false 
	 */
	private function verify_timeline($starttime, $endtime , $date)
	{
		$return = $this->array_group($starttime, $endtime , $date);
		for ($i = 1, $c = count($return); $i < $c; $i++)
		{
			if (($return[$i] - $return[$i - 1]) < 0)
			{
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 开始时间和结束时间组成新数组
	 * @name array_group
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $arr1 array 开始时间
	 * @param $arr2 array 结束时间
	 * @param $temp array 日期
	 * @return $array array 时间线
	 */
	private function array_group($arr1, $arr2, $temp)
	{
		$num = count($arr1);
		$array = array();
		$i = 0;
		$j = 0;
		while($j < $num)
		{
		   $array[$i] = strtotime(urldecode($temp. ' ' .$arr1[$j]));
		   $array[$i+1] = strtotime(urldecode($temp. ' ' .$arr2[$j]));
		   $i= $i + 2;
		   $j++;
		}
		return $array;
	}
	/**
	 * 
	 * 设置提交数据的切播地址
	 * @name set_chg_uris
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel2_id array 来源类型ID
	 * @param $type array 来源类型 (1-直播 2-文件 3-时移)
	 * @global $mChgUris array 流地址
	 * @return true
	 */
	private function set_chg_uris($channel2_id, $type)
	{
		$channel_id = $file_id = array();
		foreach($channel2_id AS $i => $id)
		{	
			if ($type[$i] == 2)
			{
				$file_id[] = $id;
			}
			else 
			{
				$channel_id[] = $id;
			}
		}
		if ($channel_id && $type[$i]) //取频道流地址
		{
			$condition = " WHERE id IN(" . implode(',', $channel_id) .")";	
			$sql = "SELECT id,code,main_stream_name FROM " . DB_PREFIX . "channel " . $condition;
			$q = $this->db->query($sql);
			$stream_uri = array();
			while($row = $this->db->fetch_array($q))
			{
				$stream_uri[$row['id']] = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $row['code'], 'stream_name' => $row['main_stream_name']));
			}
		}
		if ($file_id) //取文件流地址
		{
			$sql = "SELECT id,vodinfo_id,filepath,newname FROM " . DB_PREFIX . "backup WHERE id IN(" . implode(',', $file_id) .")";
			$f = $this->db->query($sql);
			$file_stream_uri = array();	
			while($r = $this->db->fetch_array($f))
			{
				if($r['vodinfo_id'])
				{
					$file_stream_uri[$r['id']] = $this->settings['vod_url'] . $r['filepath'] . $r['newname'];
				}
				else 
				{
					$file_stream_uri[$r['id']] = UPLOAD_BACKUP_MMS_URL . $r['newname'];
				}
			}		
		}
		foreach($channel2_id AS $i => $id)
		{
			if ($type[$i] == 2)
			{
				$this->mChgUris[$i] = $file_stream_uri[$id];
			}
			else
			{
				$this->mChgUris[$i] = $stream_uri[$id];
			}
		}
		return true;
	}
}
$out = new ChannelChgPlanCreateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>