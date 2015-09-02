<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_shield_update.php 38126 2014-07-10 02:12:38Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','program_shield');
require('global.php');
class programShieldUpdateApi extends adminUpdateBase
{
	private $mProgramShield;
	private $mLivemms;
	private $mServerConfig;
	private $mChannel;
	public function __construct()
	{
		parent::__construct();
		
		require_once (CUR_CONF_PATH . 'lib/program_shield.class.php');
		$this->mProgramShield = new programShield();
		
		require_once (CUR_CONF_PATH . 'lib/livemms.class.php');
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
		
		require_once (CUR_CONF_PATH . 'lib/channel.class.php');
		$this->mChannel = new channel();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function edit()
	{
		$this->verify_setting_prms();
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
		$field = 'id, server_id, code';
		$channel_info = $this->mChannel->get_channel_by_id($channel_id, $field);
		
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
		$this->mProgramShield->cache_program_shield($channel_id, $dates, $channel_info['code']);
		//生成屏蔽节目缓存文件
		//$program_shield_dir = $this->settings['program_shield_dir'] ? $this->settings['program_shield_dir'] : 'program_shield';
		//$this->get_shield_info($channel_id, $dates, $program_shield_dir);
		
		$this->addItem($return);
		$this->output();
	}
	
	public function delete()
	{
		$this->verify_setting_prms();
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		$shield = $this->mProgramShield->get_shield_by_id($id, 'channel_id,dates,dvr_delete');
		if ($shield['dvr_delete'])
		{
			$this->errorOutput('DVR_NOT_EXISTS');
		}
		$channel_info = $this->mChannel->get_channel_by_id($shield['channel_id'], 'code');
		$ret = $this->mProgramShield->delete($id);
		$this->mProgramShield->cache_program_shield($shield['channel_id'], $shield['dates'], $channel_info['code']);
		
		if (!$ret)
		{
			$this->errorOutput('DELETE_FAIL');
		}
		
		$this->addItem($id);
		$this->output();
	}

	public function dvr_delete()
	{
		$this->verify_setting_prms();
		$channel_id = intval($this->input['channel_id']);
		$id = intval($this->input['id']);
		
		if (!$id)
		{
			$this->errorOutput('未传入id');
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
		
		$channel_id = $shield['channel_id'];		
		$sql  = "SELECT cs.stream_name, cs.bitrate, c.id,c.code,c.name,c.time_shift,c.status,sc.ts_host FROM " . DB_PREFIX . "channel_stream cs LEFT JOIN " . DB_PREFIX . "channel c ON cs.channel_id = c.id LEFT JOIN " . DB_PREFIX . "server_config sc ON sc.id=c.server_id WHERE c.id = $channel_id AND c.status=1 AND sc.type='nginx' ORDER BY cs.order_id ASC";
		$q = $this->db->query($sql);
		
		$start_time = $shield['start_time'] . '000';
		$end_time = ($shield['start_time'] + $shield['toff']) . '000';
		while($r = $this->db->fetch_array($q))
		{
			$channel_stream = $r['code'] . '_' .$r['stream_name'];
			$sql = 'DELETE FROM ' . DB_PREFIX . "dvr WHERE stream_name='$channel_stream' AND start_time>=" . $start_time . ' AND start_time<=' . $end_time;
			$this->db->query($sql);
			$sql = 'DELETE FROM ' . DB_PREFIX . "dvr1 WHERE stream_name='$channel_stream' AND start_time>=" . $start_time . ' AND start_time<=' . $end_time;
			$this->db->query($sql);
		}
		$sql = 'UPDATE ' . DB_PREFIX . "program_shield SET dvr_delete=1 WHERE id=" . $id;
		$this->db->query($sql);
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
	
	private function get_shield_info($channel_id, $dates, $program_shield_dir = 'program_shield', $field = ' * ')
	{
		if (!$channel_id || !$program_shield_dir || !$dates)
		{
			return false;
		}
		$times = strtotime($dates);
		$year  = date('Y', $times);
		$month = date('m', $times);
		$day   = date('d', $times);
		
		$dir 	  = CACHE_DIR . $program_shield_dir . '/' . $year . '/' . $month . '/' . $day;
		$filename = $channel_id . '.php';
		
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "program_shield ";
		$sql.= " WHERE channel_id = " . $channel_id;
		$sql.= " AND dates = '" . $dates . "'";
		$q = $this->db->query($sql);
		
		$return = array();
		while ($row = $this->db->fetch_array($q))
		{
			$return[] = $row;
		}
		
		if (!is_dir($dir))
		{
			hg_mkdir($dir);
		}
		
		$content = '<?php
			$program_shield = ' . var_export($return, 1) . ';
		?>';
		hg_file_write($dir . '/' . $filename, $content);
		
		return $return;
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