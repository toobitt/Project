<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_shield_update.php 18704 2013-03-13 07:29:18Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','program_shield');
require('global.php');
class programShieldUpdateApi extends adminUpdateBase
{
	private $mProgramShield;
	private $mLivemms;
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();
		
		require_once (CUR_CONF_PATH . 'lib/program_shield.class.php');
		$this->mProgramShield = new programShield();
		
		require_once (CUR_CONF_PATH . 'lib/livemms.class.php');
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function edit()
	{
		$channel_id = intval($this->input['channel_id']);
		$dates 		= urldecode($this->input['dates']);
		
		$ids		= $this->input['ids'];
		$start_time = $this->input['start_time'];
		$end_time	= $this->input['end_time'];
		$theme		= $this->input['theme'];
		$flag 		= $this->input['flag'];
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$dates)
		{
			$this->errorOutput('未传入日期');
		}
		
		for ($i = 0; $i < count($start_time); $i++)
		{
			if (!$start_time[$i])
			{
				$this->errorOutput('开始时间不能为空');
			}
			
			if (!$end_time[$i])
			{
				$this->errorOutput('结束时间不能为空');
			}
		}
		
		$ret_verify_timeline = $this->verify_timeline($start_time, $end_time, $dates);
		
		if (!$ret_verify_timeline)
		{
			$this->errorOutput('时间设置存在重复');
		}
		
		//频道信息
		$field = 'id, server_id';
		$channel_info = $this->mProgramShield->get_channel_by_id($channel_id, $field);
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		$ret_ids = $ret_themes = array();
		foreach ($start_time AS $k => $v)
		{
			$ret_themes[$k] = $theme[$k] ? $theme[$k] : '屏蔽节目';
			
			$add_input[$k] = array(
				'channel_id'	=> $channel_id,
				'theme'			=> $ret_themes[$k],
				'start_time'	=> strtotime($dates . ' ' .$start_time[$k]),
				'toff'			=> strtotime($dates . ' ' .$end_time[$k]) - strtotime($dates . ' ' .$start_time[$k]),
				'dates'			=> $dates,
				'weeks'			=> date('W', strtotime($dates)),
				'server_id'		=> $channel_info['server_id'],
				'update_time'	=> TIMENOW,
			);
			
			if (!$ids[$k])
			{
				$add_input[$k]['appid'] 		= $this->user['appid'];
				$add_input[$k]['appname'] 		= $this->user['display_name'];
				$add_input[$k]['user_id'] 		= $this->user['user_id'];
				$add_input[$k]['user_name'] 	= $this->user['user_name'];
				$add_input[$k]['create_time'] 	= TIMENOW;
				$add_input[$k]['ip'] 			= hg_getip();
				
				$ret[$k] = $this->mProgramShield->create($add_input[$k]);
			}
			else if ($flag[$k])
			{
				$ret[$k] = $this->mProgramShield->update($add_input[$k], $ids[$k]);
			}
			else 
			{
				$ret[$k]['id'] = $ids[$k];
			}
			
			$ret_ids[$k] = $ret[$k]['id'];
		}
		
		$return = array(
			'id'			=> $ret_ids,
			'theme'			=> $ret_themes,
			'channel_id'	=> $channel_id,
		);
		
		$this->addItem($return);
		$this->output();
	}
	
	public function delete()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		$ret = $this->mProgramShield->delete($id);
		
		if (!$ret)
		{
			$this->errorOutput('DELETE_FAIL');
		}
		
		$this->addItem($id);
		$this->output();
	}

	public function dvr_delete()
	{
		$channel_id = intval($this->input['channel_id']);
		$id = intval($this->input['id']);
		
		if (!$channel_id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$channel_stream = $this->mProgramShield->get_channel_stream_by_id($channel_id);
		$channel_stream = $channel_stream[0];
		if (empty($channel_stream))
		{
			$this->errorOutput('该频道信号不存在或已被删除');
		}
		
		$out_stream_id = $channel_stream['out_stream_id'];
		
		if (!$out_stream_id)
		{
			$this->errorOutput('该频道信号流不存在或已被删除');
		}
		
		$shield = $this->mProgramShield->get_shield_by_id($id);
		
		if (empty($shield))
		{
			$this->errorOutput('该屏蔽节目不存在或已被删除');
		}
		
		if (!$shield['dates'] || !$shield['start_time'])
		{
			$this->errorOutput('开始时间不存在或已被删除');
		}
		
		$start_time = $shield['start_time'] . '000';
		$toff		= $shield['toff'] . '000';
		$callback 	= $this->settings['App_live']['protocol'].$this->settings['App_live']['host'].'/'.$this->settings['App_live']['dir'] . 'admin/callback.php?a=dvr_delete_callback&id=' . $id . '&access_token=' . $this->user['token'];
		
		$server_id  = $shield['server_id'];
		if ($server_id)
		{
			$server_info = $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		if ($server_info['in_host'])
		{
			$host	= $server_info['in_host'] . ':' . $server_info['in_port'];
			$apidir	= $server_info['output_dir'];
		}
		else 
		{
			$host	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$apidir	= $this->settings['wowza']['core_input_server']['output_dir'];
		}
		
		$ret_delete = $this->mLivemms->dvrOperate($host, $apidir, $out_stream_id, $start_time, $toff, urlencode($callback));

		if (!$ret_delete['result'])
		{
			$this->errorOutput('时移删除失败');
		}
		$ret = array(
			'id' => $id,
		);
		$this->addItem($ret);
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
	
	public function create()
	{
		
	}
	public function update()
	{
		
	}

	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new programShieldUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>