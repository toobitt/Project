<?php
/***************************************************************************
* $Id: sys.php 21381 2013-05-02 02:12:09Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','sys');
require('global.php');
class sysApi extends appCommonFrm
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
	 * 取直播配置信息 (如果数据库没有记录,则取config文件直播配置)
	 * 返回 直播配置
	 * 缓存一份数据到 cache 目录
	 * Enter description here ...
	 */
	public function get_old_server_config()
	{
		$server_config = $this->get_server_config();
		
		$timenow = $this->input['timenow'] ? intval($this->input['timenow']) : TIMENOW;
		
		if ($this->input['is_cache'])
		{
			$dir = CACHE_DIR . 'sys';
			$filename = $timenow . '_server_config.php';
			if (!is_dir($dir))
			{
				hg_mkdir($dir);
			}
			
			$content = '<?php
				if (!IS_READ)
				{		
					exit();
				}
				$server_config = ' . var_export($server_config, 1) . ';
			?>';
			hg_file_write($dir . '/' . $filename, $content);
		}
		
		$this->addItem($server_config);
		$this->output();
	}
	
	/**
	 * 取频道、频道流、信号
	 * 返回 三者合并后的信息
	 * 缓存一份数据到 cache 目录
	 * Enter description here ...
	 */
	public function get_old_live_info()
	{
		$timenow = $this->input['timenow'] ? intval($this->input['timenow']) : TIMENOW;
		
		//频道信息
		$sql = "SELECT * FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE 1 ORDER BY id ASC ";
		
		$q = $this->db->query($sql);
		
		$channel = $channel_id = $stream_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['stream_info_all'] 	= @unserialize($row['stream_info_all']);
			$row['logo_info'] 			= @unserialize($row['logo_info']);
			$row['logo_mobile_info'] 	= @unserialize($row['logo_mobile_info']);
			$row['column_id'] 			= @unserialize($row['column_id']);
			$row['column_url'] 			= @unserialize($row['column_url']);
			
			$channel_id[] = $row['id'];
			$stream_id[]  = $row['stream_id'];
			$channel[] 	  = $row;
		}
		
		//频道流
		if (!empty($channel_id))
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream ";
			$sql.= " WHERE channel_id IN (" . implode(',', $channel_id) . ") ORDER BY id ASC ";
			
			$q = $this->db->query($sql);
			
			$channel_stream = array();
			while ($row = $this->db->fetch_array($q))
			{
				$channel_stream[$row['channel_id']][] = $row;
			}
		}
		
		//信号
		if (!empty($stream_id))
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "stream ";
			$sql.= " WHERE id IN (" . implode(',', $stream_id) . ") ORDER BY id ASC ";
			
			$q = $this->db->query($sql);
			
			$stream = array();
			while ($row = $this->db->fetch_array($q))
			{
				$row['uri'] 		= @unserialize($row['uri']);
				$row['other_info'] 	= @unserialize($row['other_info']);
				
				$stream[$row['id']] = $row;
			}
		}
		
		//频道流合并信号
		$channel_stream_info = array();
		if (!empty($channel_stream))
		{
			foreach ($channel_stream AS $k => $v)
			{
				foreach ($v AS $kk => $vv)
				{
					$v[$kk]['input_id'] = 0;
					$v[$kk]['url'] = '';
					$v[$kk]['bitrate'] = '';
					foreach ($stream[$vv['stream_id']]['other_info']['input'] AS $kkk => $vvv)
					{
						if ($vv['stream_name'] == $vvv['name'])
						{
							$v[$kk]['url'] 		= $vvv['uri'];
							$v[$kk]['bitrate'] 	= $vvv['bitrate'];
							$v[$kk]['input_id'] = $vvv['id'];
						}
					}
				}
				$channel_stream_info[$k] = $v;
			}
		}
		
		//取直播配置
		$config = $this->get_server_config();

		$server_config = array();
		if (!empty($config))
		{
			foreach ($config AS $k => $v)
			{
				$tmp = array(
					'protocol'			=> 'http://',
					'host' 				=> $v['core_in_host'] ? $v['core_in_host'] : $v['host'],
					'input_port'		=> $v['core_in_port'] ? $v['core_in_port'] : $v['input_port'],
					'output_port'		=> $v['core_out_port'] ? $v['core_out_port'] : $v['output_port'],
					'input_dir'			=> $v['input_dir'],
					'output_dir'		=> $v['output_dir'],
				);
				$server_config[$v['id']] = $tmp;
			}
		}
		
		//频道合并频道流
		$return =array();
		if (!empty($channel))
		{
			foreach ($channel AS $k => $v)
			{
				$v['channel_stream'] = $channel_stream_info[$v['id']];
				$v['server_config']	 = $server_config[$v['server_id']];
				$v['is_push']		 = $stream[$v['stream_id']]['wait_relay'];
				$return[] = $v;
			}
		}
	
		//缓存数据到cache目录
		if ($this->input['is_cache'])
		{
			$dir = CACHE_DIR . 'sys';
			$filename = $timenow . '_live.php';
			if (!is_dir($dir))
			{
				hg_mkdir($dir);
			}
			
			$content = '<?php
				if (!IS_READ)
				{		
					exit();
				}
				$channel = ' . var_export($channel, 1) . ';
				$channel_stream = ' . var_export($channel_stream, 1) . ';
				$stream = ' . var_export($stream, 1) . ';
				$return = ' . var_export($return, 1) . ';
			?>';
			hg_file_write($dir . '/' . $filename, $content);
		}
		//缓存数据到cache目录
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 取直播服务器配置
	 * Enter description here ...
	 */
	private function get_server_config()
	{
		//直播服务器配置信息
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config ";
		$sql.= " WHERE 1 ORDER BY id ASC ";
		
		$q = $this->db->query($sql);
		
		$server_config = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['dvr_append_host']  = @unserialize($row['dvr_append_host']);
			$row['live_append_host'] = @unserialize($row['live_append_host']);
			$row['output_append_host'] = @unserialize($row['output_append_host']);
			$server_config[] 	  = $row;
		}
		
		if (empty($server_config))
		{
			$live_config = $this->settings['_wowza'];
			$server_config[0] = array(
				'id' 				=> '0',
				'name' 				=> '直播配置',
				'brief'				=> '取配置文件里的直播配置同步添加',
				'protocol'			=> 'http://',
				'core_in_host' 		=> $live_config['core_input_server']['host'],
				'core_in_port'		=> $live_config['in_port'],
				'core_out_port'		=> $live_config['out_port'],
				'input_dir'			=> $live_config['core_input_server']['input_dir'],
				'output_dir'		=> $live_config['core_input_server']['output_dir'],
				'counts'			=> $live_config['counts'],
				'dvr_append_host'	=> $live_config['dvr_append_host'],
			);
		}
		return $server_config;
	}
	
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
	
}
$out = new sysApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>