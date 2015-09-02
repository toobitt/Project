<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
*@public function delete|check_copy|copy_day
*
* $Id: channel_chg_plan_update.php 
***************************************************************************/
require('global.php');
class ChannelChgPlanUpdateApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 检验复制是否是同一天
	 * @name check_copy
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $dates string 日期
	 * @param $id string 要复制到的日期
	 * @param $show_id string 被复制的日期
	 * @return $tip array 日期数组
	 * @include tvie_api.php
	 */
	public function check_copy()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}
		
		$dates = urldecode($this->input['dates']);//源日期
		if(!$dates)
		{
			$this->errorOutput("未传入更新日期");
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan WHERE channel_id=" . $channel_id . " and dates='" . $dates . "'";
		$f = $this->db->query_first($sql);
		
		$tip = array('ret'=>1,'id'=>$this->input['id'],'show_id'=>$this->input['show_id']);
		if(!$f['id'])
		{
			$tip = array('ret'=>0,'id'=>$this->input['id'],'show_id'=>$this->input['show_id']);
		}
		$this->addItem($tip);
		$this->output();
	}

	/**
	 * 复制串联单
	 * @name copy_day
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_id int 频道ID
	 * @param $dates string 日期
	 * @param $copy_dates string 要复制的日期
	 * @return $tip array 复制成功后返回 1
	 * @include tvie_api.php
	 */
	public function copy_day()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}
		
		$dates = urldecode($this->input['dates']);//源日期
		if(!$dates)
		{
			$this->errorOutput("未传入更新日期");
		}

		$copy_dates = urldecode($this->input['copy_dates']);
		if(!$copy_dates)
		{
			$this->errorOutput("未传入要复制的日期");
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_chg_plan WHERE channel_id=" . $channel_id . " AND dates='" . $dates . "'";
		$q = $this->db->query($sql);
		$chg_info = array();
		while($row = $this->db->fetch_array($q))
		{
			$chg_info[] = $row;
		}
		$diff = strtotime($copy_dates) - strtotime($dates); 
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$tvie_api = new TVie_api($this->settings['tvie']['up_stream_server']);
		}
		$sql = "DELETE FROM  " . DB_PREFIX . "channel_chg_plan WHERE channel_id=" . $channel_id ." AND dates='" . $copy_dates . "'";
		$this->db->query($sql);
		if ($chg_info)
		{
			foreach ($chg_info AS $k => $v)
			{
				$start = $v['change_time'] + $diff;
				$end = $v['change_time'] + $v['toff'] + $diff;
				if($v['program_start_time'])
				{
					$program_start_time = $v['program_start_time']+$diff;
					$stream_uri = substr($v['stream_uri'], 0, -27) . $program_start_time . '000,' . ($program_start_time + $v['toff']) . '000';
				}
				else 
				{
					$program_start_time = 0;
					$stream_uri = $v['stream_uri'];
				}
				if($tvie_api)
				{
					$epg = $tvie_api->create_channel_epg($v['channel2_id'], $start, $end, $stream_uri, '播放' . $v['channel2_name'] . '的节目');
				}
				$info = array(
							    'channel_id' => $v['channel_id'],
							    'channel2_id' => $v['channel2_id'],
							    'channel2_name' => $v['channel2_name'],
							    'epg_id' => $epg['result']['id'],
							    'stream_uri' => $stream_uri,
							    'change_time' => $start,
							    'program_start_time' => $program_start_time,
							    'dates' => $copy_dates,
							    'toff' => $v['toff'],
							    'type' => $v['type'],
							    'create_time' => TIMENOW,
							    'update_time' => TIMENOW,
							    'ip' => hg_getip(),
							    'admin_name' => $this->user['user_name'],
							    'admin_id' => $this->user['user_id']
				  			);
				$createsql = "INSERT INTO " . DB_PREFIX . "channel_chg_plan SET ";
				$space = "";
				foreach($info as $key => $value)
				{
					$createsql .= $space . $key . "=" . "'" . $value . "'";
					$space = ",";
				}
				$this->db->query($createsql);
			}
		}
		$tip = array('ret'=>1);
		$this->addItem($tip);
		$this->output();

	}
	
	/**
	 * 删除串联单
	 * @name delete
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 串联单ID
	 * @return $ret int 被删除串联单ID
	 * @include tvie_api.php
	 */
	public function delete()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('为传入串联单ID');
		}
		else
		{
			$sql = "SELECT epg_id FROM " . DB_PREFIX . "channel_chg_plan WHERE id=" . $id;
			$epg = $this->db->query_first($sql);
			if($this->settings['tvie']['open'])
			{
				include(CUR_CONF_PATH . 'lib/tvie_api.php');
				$tvie_api = new TVie_api($this->settings['tvie']['up_stream_server']);
				$ret_del = $tvie_api->delete_channel_epg($epg['epg_id']);
				if(!$ret_del['result'])
				{
					$this->errorOutput('删除失败');
				}
			}
			$sql = "DELETE FROM " . DB_PREFIX . "channel_chg_plan WHERE id=" .$id;
			$this->db->query($sql);
		}
		$ret['id'] = $id;
		$this->addItem($ret);
		$this->output();
	}
}
$out = new ChannelChgPlanUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();
?>