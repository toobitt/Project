<?php
/***************************************************************************
* $Id: channels.class.php 19886 2013-04-08 02:01:25Z lijiaying $
***************************************************************************/
class channels extends InitFrm
{
	private $mLivemms;
	private $mMaterial;
	private $publish_column;
	private $mLive;
	private $mServerConfig;
	public function __construct()
	{
		parent::__construct();

		include_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();

		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
		
		$this->mLive = $this->settings['wowza']['live_output_server'];
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 所有频道信息
	 * @name channelsInfo
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $condition 检索条件
	 * @param $offset 分页参数
	 * @param $count 分页显示记录数
	 * @return $info array 所有频道内容信息
	 */
	public function show($condition, $offset, $count)
	{
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$orderby = " ORDER BY c.order_id DESC ";
		
		$sql = "SELECT c.*, s.other_info, s.s_status, s.type, cn.name AS node_name FROM " . DB_PREFIX . "channel c ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "stream s ON c.stream_id=s.id ";
		$sql.= " LEFT JOIN " . DB_PREFIX . "channel_node cn ON cn.id=c.node_id ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit; 
		$q = $this->db->query($sql);

		$info = $channel_stream = $server_id = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
		//	$row['snap'] = MMS_CONTROL_LIST_PREVIEWIMG_URL . '?stream=' . hg_streamUrl($this->settings['mms']['output']['wowzaip'], $row['code'], $row['main_stream_name'].$this->settings['mms']['output']['suffix']) . '&width=172&height=130&time=' . TIMENOW;
			
			$row['appuniqueid'] = APP_UNIQUEID;
			
			$row['logo_info'] 	= @unserialize($row['logo_info']);
			$row['column_id'] 	= @unserialize($row['column_id']);
			$row['column_url'] 	= @unserialize($row['column_url']);
			
			if ($row['logo_info'])
			{
				$row['logo_url'] = hg_material_link($row['logo_info']['host'], $row['logo_info']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename'],'112x43/');
			}
			
			$row['logo_mobile_info'] = @unserialize($row['logo_mobile_info']);
			
			if ($row['logo_mobile_info'])
			{
				$row['logo_mobile_url'] = hg_material_link($row['logo_mobile_info']['host'], $row['logo_mobile_info']['dir'], $row['logo_mobile_info']['filepath'], $row['logo_mobile_info']['filename'],'50x50/');
			}
			
			$row['stream_info_all'] = @unserialize($row['stream_info_all']);
			$row['other_info'] 		= @unserialize($row['other_info']);
			
			$channel_streams = array();
			if($row['other_info']['input'])
			{
				foreach($row['other_info']['input'] AS $k => $v)
				{
					$channel_streams[$k]['id'] 			= $v['id'];
					$channel_streams[$k]['name'] 		= $v['name'];
					$channel_streams[$k]['code'] 		= $row['code'];
					$channel_streams[$k]['bitrate'] 	= $v['bitrate'];
					$channel_streams[$k]['open_ts'] 	= $row['open_ts'];
					$channel_streams[$k]['server_id'] 	= $row['server_id'];
				}
			}
			$channel_stream[$row['id']] = $channel_streams;
			$server_id[] 				= $row['server_id'];
			$info[$row['id']] 			= $row;
		}
		
		//服务器配置
		if (!empty($server_id))
		{
			$server_id	   	= implode(',', @array_unique($server_id));
			$server_infos   = $this->mServerConfig->get_server_config($server_id);
		}
	
		if(!empty($info))
		{
			//基础流信息
			$stream = $this->channelStreams(@array_keys($info));
			$stream_info = array();
			foreach ($stream AS $k => $v)
			{
				foreach ($channel_stream AS $kk => $vv)
				{
					if ($k == $kk && $v['name'] == $vv['stream_name'])
					{
						for($i=0 ; $i < count($v) ; $i++)
						{
							$stream_info[$k]['streams'][$i]['id'] 				= $v[$i]['id'];
							$stream_info[$k]['streams'][$i]['input_stream_id'] 	= $vv[$i]['id'];
							$stream_info[$k]['streams'][$i]['delay_stream_id'] 	= $v[$i]['delay_stream_id'];
							$stream_info[$k]['streams'][$i]['chg_stream_id'] 	= $v[$i]['chg_stream_id'];
							$stream_info[$k]['streams'][$i]['out_stream_id'] 	= $v[$i]['out_stream_id'];
							$stream_info[$k]['streams'][$i]['name'] 			= $v[$i]['stream_name'];
							$stream_info[$k]['streams'][$i]['out_stream_name'] 	= $v[$i]['out_stream_name'];
							
							$server_info 	= $server_infos[$vv[$i]['server_id']];
						//	$server_output 	= $server_outputs[$vv[$i]['server_id']];

							if ($server_info['core_in_host'])
							{
								$wowzaip_input 	= $server_info['core_in_host']; 
								
								if ($server_info['is_dvr_output'])
								{
									$wowzaip_output = $server_info['dvr_in_host'] . ':' . $server_info['dvr_out_port'];
								}
								else 
								{
									$wowzaip_output = $server_info['core_in_host'] . ':' . $server_info['core_out_port'];
								}
							}
							else 
							{
								$wowzaip_input 	= $this->settings['wowza']['core_input_server']['host']; 
								
								if ($this->settings['wowza']['dvr_output_server'])
								{
									$wowzaip_output = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
								}
								else 
								{
									$wowzaip_output = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
								}
							}
							
							$app_name_input = $this->settings['wowza']['input']['app_name'];
							$suffix_input 	= !$info[$k]['type'] ? $this->settings['wowza']['input']['suffix'] : $this->settings['wowza']['list']['suffix'];
							$app_name_output= $this->settings['wowza']['dvr_output']['app_name'];
							$suffix_output 	= $this->settings['wowza']['dvr_output']['suffix'];
							$dvr_output = $wowzaip_output;
							//live
							if ($this->mLive)
							{
								if ($server_info['is_live_output'])
								{
									$wowzaip_output = $server_info['live_in_host'] . ':' . $server_info['live_out_port'];
								}
								else 
								{
									$wowzaip_output = $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
								}
								$suffix_output  = $this->settings['wowza']['live_output']['suffix'];
							}
							
							//频道输出流
							$stream_info[$k]['streams'][$i]['uri'] = hg_streamUrl($wowzaip_output, $vv[$i]['code'], $v[$i]['out_stream_name'] . $suffix_output, 'flv');
							
							//信号输出流
							$stream_info[$k]['streams'][$i]['stream_uri'] = hg_streamUrl($wowzaip_input, $app_name_input, $vv[$i]['id'] . $suffix_input);
			
							if ($vv[$i]['open_ts'])
							{
								//手机输出流
								$stream_info[$k]['streams'][$i]['m3u8'] = hg_streamUrl($wowzaip_output, $vv[$i]['code'], $v[$i]['out_stream_name'] . $suffix_output, 'm3u8');
								
								$stream_info[$k]['streams'][$i]['m3u8_dvr'] = hg_streamUrl($dvr_output, $vv[$i]['code'], $v[$i]['out_stream_name'] . $suffix_output, 'm3u8');
							}
							$stream_info[$k]['streams'][$i]['is_main'] = $v[$i]['is_main'];
							$stream_info[$k]['streams'][$i]['bitrate'] = $v[$i]['bitrate'];
						}
					}
				}
			}

			//频道内容信息
			$channel_info = array();
			foreach ($info AS $k => $v)
			{
				if ($stream_info[$k])
				{
					$channel_info[$k] = @array_merge($info[$k],$stream_info[$k]);
				}
				else
				{
					$channel_info[$k] = $info[$k];
				}
				
				$channel_info[$k]['server_name'] 		= $server_infos[$v['server_id']]['name'];
				$channel_info[$k]['dvr_output_port'] 	= $server_infos[$v['server_id']]['is_dvr_output'] ? $server_infos[$v['server_id']]['dvr_out_port'] : $server_infos[$v['server_id']]['core_out_port'];
				$channel_info[$k]['dvr_append_host'] 	= $server_infos[$v['server_id']]['dvr_append_host'];
				$channel_info[$k]['live_append_host'] 	= $server_infos[$v['server_id']]['live_append_host'];
			}
			return $channel_info;
		}
		
		return false;
	}
	/**
	 * 频道流信息
	 * @name channelStreams
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $channel_ids array 所有频道ID
	 * @return $return array 所有频道流信息
	 */
	public function channelStreams($channel_ids)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN(" . implode(',', $channel_ids) .") ORDER BY id ASC";
		$q = $this->db->query($sql);
		$return = array();
		while ($r = $this->db->fetch_array($q))
		{
			$return[$r['channel_id']][] = $r;
		}
		return $return;
	}

	public function getChannel($id, $f = '*')
	{
		if(!$id)
		{
			return array();
		}
		$condition = ' WHERE id IN (' . $id .')';
		if (!$f)
		{
			$f = '*';
		}
		$sql = "SELECT {$f} FROM " . DB_PREFIX . "channel " . $condition;
		$row = $this->db->query_first($sql);
		$row['logo_info'] = @unserialize($row['logo_info']);
		$row['logo_mobile_info'] = @unserialize($row['logo_mobile_info']);
		return $row;
	}
	
	function detail($id)
	{
		if(!$id)
		{
			$condition = ' ORDER BY id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE id IN (' . $id .')';
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel " . $condition;		
		$row = $this->db->query_first($sql);

		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			//串联单的模块id
			$row['relate_module_id'] = intval($this->input['relate_module_id']);
	
			$row['stream_info_all']  = @unserialize($row['stream_info_all']);
		//	$row['beibo'] 			 = @unserialize($row['beibo']);
			
			//信号流信息
			if($row['stream_id'])
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "stream WHERE id = " .$row['stream_id'];
				$stream = $this->db->query_first($sql);

				$other_info = @unserialize($stream['other_info']);

				if($other_info['input'])
				{
					$input_info = $row['stream_name_all'] = array();
					foreach($other_info['input'] AS $key => $value)
					{
						$row['stream_name_all'][] = $value['name'];
						$input_info[$value['name']] = $value;
					}
				}
				$row['type'] = $stream['type'];
			}
			
			if ($row['server_id'])
			{
				$server_info   = $this->mServerConfig->get_server_config_by_id($row['server_id']);
			}
			$row['server_name'] = $server_info['name'];
			//信号信息
			$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN(" . $id . ") ORDER BY id ASC";
			$f = $this->db->query($sql);

			$row['stream_uri'] = $row['out_streams'] = $row['out_streams_uri'] = $row['ts_uri'] = $row['out_stream_name'] = $row['stream_name'] = array();

			while($r = $this->db->fetch_array($f))
			{
				if ($server_info['core_in_host'])
				{
					$wowzaip_input 	= $server_info['core_in_host'];
				
					if ($server_info['is_dvr_output'])
					{
						$wowzaip_output = $server_info['dvr_in_host'] . ':' . $server_info['dvr_out_port'];
					}
					else 
					{
						$wowzaip_output = $server_info['core_in_host'] . ':' . $server_info['core_out_port'];
					}
				}
				else 
				{
					$wowzaip_input 	= $this->settings['wowza']['core_input_server']['host'];
					
					if ($this->settings['wowza']['dvr_output_server'])
					{
						$wowzaip_output = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
					else 
					{
						$wowzaip_output = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
				}
			
				$suffix_input 		= !$row['type'] ? $this->settings['wowza']['input']['suffix'] : $this->settings['wowza']['list']['suffix'];
				$app_name_input 	= $this->settings['wowza']['input']['app_name'];
				$suffix_output 		= $this->settings['wowza']['dvr_output']['suffix'];
				$app_name_output	= $this->settings['wowza']['dvr_output']['app_name'];
			
				//live
				if ($this->mLive)
				{
					if ($server_info['is_live_output'])
					{
						$wowzaip_output = $server_info['live_in_host'] . ':' . $server_info['live_out_port'];
					}
					else 
					{
						$wowzaip_output = $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
					$suffix_output  = $this->settings['wowza']['live_output']['suffix'];
				}
				
				//信号输出流
				$row['stream_uri'][$r['stream_name']] = hg_streamUrl($wowzaip_input, $app_name_input, $input_info[$r['stream_name']]['id'] . $suffix_input);
				
				//频道输出流(flv)
				$row['out_streams'][$r['out_stream_name']] =  hg_streamUrl($wowzaip_output, $row['code'], $r['out_stream_name'] . $suffix_output, 'flv');
	
				$row['out_streams_uri'][] = hg_streamUrl($wowzaip_output, $row['code'], $r['out_stream_name'] . $suffix_output, 'flv');
				
				//手机输出流
				$row['ts_uri'][$r['out_stream_name']] =  hg_streamUrl($wowzaip_output, $row['code'], $r['out_stream_name'] . $suffix_output, 'm3u8');
				
				$row['out_stream_name'][] 	= $r['out_stream_name'];
				$row['stream_name'][] 		= $r['stream_name'];
				$row['count'] 				= count($row['stream_name']);
			}
			
			//logo显示
			$row['logo_info'] 	= @unserialize($row['logo_info']);
			$row['logo'] 	 	= $row['logo_info']['filename'];
			if ($row['logo_info'])
			{
				$row['logo_url'] = hg_material_link($row['logo_info']['host'], $row['logo_info']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename'],'112x43/');
			}
		
			$row['logo_mobile_info'] 	= @unserialize($row['logo_mobile_info']);
			$row['logo_mobile'] 		= $row['logo_mobile_info']['filename'];
			if ($row['logo_mobile_info'])
			{
				$row['logo_mobile_url'] = hg_material_link($row['logo_mobile_info']['host'], $row['logo_mobile_info']['dir'], $row['logo_mobile_info']['filepath'], $row['logo_mobile_info']['filename'],'30x30/');
			}
			
			//发布开始
			$row['column_url'] 	= @unserialize($row['column_url']);
			$row['column_id'] 	= @unserialize($row['column_id']);
			if(is_array($row['column_id']))
			{
				$column_id = array();
				foreach($row['column_id'] as $k => $v)
				{
					$column_id[] = $k;
				}
				$column_id = implode(',',$column_id);
				$row['column_id'] = $column_id;
			}
			//发布结束
					
			return $row;
		}
		
		return false;	
	}

	public function create($add_info, $stream_info, $server)
	{
		$stream_id = $stream_info['id'];
		$server_info 	= $server['server_info'];
		//
		if ($server_info['core_in_host'])	//取数据库配置
		{
			//主控
			$core_host_input   = $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$core_apidir_input = $server_info['input_dir'];
			
			//时移
			if ($server_info['is_dvr_output'])
			{
				$dvr_host_output 	= $server_info['dvr_in_host'] . ':' . $server_info['dvr_in_port'];
			}
			else 
			{
				$dvr_host_output 	= $core_host_input;
			}
			$dvr_apidir_output    = $server_info['output_dir'];
		}
		else 	//去配置文件
		{
			//主控
			$core_host_input   = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$core_apidir_input = $this->settings['wowza']['core_input_server']['input_dir'];
			
			//时移
			if ($this->settings['wowza']['dvr_output_server'])
			{
				$dvr_host_output = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['dvr_output_server']['port'];
			}
			else 
			{
				$dvr_host_output = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			}
			
			$dvr_apidir_output = $this->settings['wowza']['core_input_server']['output_dir'];
		}
		
		if ($this->mLive)
		{
			if ($server_info['is_live_output'])
			{
				$live_host_output 	= $server_info['live_in_host'] . ':' . $server_info['live_in_port'];
				$live_apidir_output = $server_info['output_dir'];
			}
			else 
			{
				$live_host_output 	= $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['live_output_server']['port'];
				$live_apidir_output = $this->settings['wowza']['live_output_server']['output_dir'];
			}
		}
		//

		$ret_select = $this->mLivemms->outputApplicationSelect($dvr_host_output, $dvr_apidir_output);
		
		if (!$ret_select)
		{
			return -55;//媒体服务器未启动
		}
		
		$type = $stream_info['type']; //是否是文件流
		
		$stream_other_info = @unserialize($stream_info['other_info']);

		$input_info = $stream_other_info['input'];
		
		if (!empty($input_info))
		{
			$input_stream_name = array();
			foreach ($input_info AS $k=>$v)
			{
				$input_stream_name[] = $v['name'];
			}
		}

		$tpl_stream_name = $add_info['stream_name'];

		$intersect_stream_name = @array_intersect($input_stream_name, $tpl_stream_name);

		$outputType = 0;
		
		if ($add_info['open_ts'])
		{
			$outputType = 3;
		}
		else
		{
			$outputType = 1;
		}
		
		$length = $add_info['save_time'] * 3600;	//时移时间

		$delay 	= $add_info['live_delay'];			//延时时间
		
		$drm 	= $add_info['drm'];					//防盗链

		//创建输出层
		$appName_output = $add_info['code'];

		$ret_app_output = $this->mLivemms->outputApplicationInsert($dvr_host_output, $dvr_apidir_output, 0, $appName_output, $length, $drm, $outputType);

		if ($ret_app_output['head'])
		{
			return $ret_app_output;
		}
	
		if (!$ret_app_output['result'])
		{
			return -20; //输出层应用创建失败
		}
		
		$applicationId = $ret_app_output['application']['id'];
			
		//live
		if ($this->mLive)
		{
			$_ret_app_output = $this->mLivemms->outputApplicationInsert($live_host_output, $live_apidir_output, $applicationId, $appName_output, 0, $drm, $outputType);
			if (!$_ret_app_output['result'])
			{
				return -200; //直播输出层应用创建失败
			}
		}
		
		if (!empty($input_info))
		{
			$channel_stream_info = $ret_delay_id = $ret_chg_id = $ret_chg_input_id = $ret_chg_output_id = $ret_out_id = $_ret_out_id = array();

			foreach ($input_info AS $k=>$v)
			{
				if ($v['name'] = $intersect_stream_name[$k])
				{
					if ($delay)
					{
						//延时层流
						$ret_delay = $this->mLivemms->inputDelayInsert($core_host_input, $core_apidir_input, $v['id'], $delay);
						
						$ret_delay_id[$k] = $ret_delay['delay']['id'];

						if (!$ret_delay_id[$k])
						{
							continue;
						}
						
						$sourceId = $ret_delay_id[$k];
						
						$sourceType = 2;
					}
					else 
					{
						$sourceId = $v['id'];
						
						$sourceType = !$type ? 1 : 3;
					}
				
					//切播层
					$ret_chg = $this->mLivemms->inputChgStreamInsert($core_host_input, $core_apidir_input, $sourceId, $sourceType);
						
					$ret_chg_id[$k] = $ret_chg['output']['id'];

					if (!$ret_chg_id[$k])
					{
						continue;
					}
					
					//输出层流
					if ($server_info['core_in_host'])
					{
						$wowzaip_chg 	= $server_info['core_in_host'] . ':' . $server_info['core_out_port'];
					}
					else 
					{
						$wowzaip_chg 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
					
					$appName_chg 	= $this->settings['wowza']['chg']['app_name'];
					$streamName_chg = $ret_chg_id[$k] . $this->settings['wowza']['chg']['suffix'];
						
					$output_url = hg_streamUrl($wowzaip_chg, $appName_chg, $streamName_chg);
					
					$ret_output = $this->mLivemms->outputStreamInsert($dvr_host_output, $dvr_apidir_output, 0, $applicationId, $v['name'], $output_url);
					
					$ret_out_id[$k] = $ret_output['stream']['id'];

					if ($ret_output['result'])
					{
						$channel_stream_info[$k] =  array(
							'delay_stream_id' 	=> $ret_delay_id[$k],
							'chg_stream_id' 	=> $ret_chg_id[$k],
							'out_stream_id' 	=> $ret_out_id[$k],
							'stream_name' 		=> $v['name'],
							'out_stream_name' 	=> $v['name'],
							'is_main' 			=> ($k == 0) ? 1 : 0 ,
							'bitrate' 			=> $v['bitrate'],
						);
					}
					
					//live
					if ($this->mLive)
					{
						$_ret_output = $this->mLivemms->outputStreamInsert($live_host_output, $live_apidir_output, $ret_out_id[$k], $applicationId, $v['name'], $output_url);
						
						$_ret_out_id[$k] = $_ret_output['stream']['id'];
					}

				}
			}
			
			if ($delay && empty($ret_delay_id))
			{
				return -16; //延时层创建失败
			}
			
			if (!empty($ret_out_id))
			{
				$delete_back = '';

				foreach ($ret_out_id AS $k=>$v)
				{
					if (!$v)
					{	
						//延时层
						if ($delay && $ret_delay_id[$k])
						{
							$ret_delay_back = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'delete', $ret_delay_id[$k]);
						}
						
						//切播层
						$ret_chg_back = $this->mLivemms->inputChgStreamOperate($core_host_input, $core_apidir_input, 'delete', $ret_chg_id[$k]);
						
						//输出层
						$ret_output_back = $this->mLivemms->outputStreamOperate($dvr_host_output, $dvr_apidir_output, 'delete', $v);
						
						//live
						if ($this->mLive)
						{
							$_ret_output_back = $this->mLivemms->outputStreamOperate($live_host_output, $live_apidir_output, 'delete', $v);
						}
						
						$delete_back = 1;
					}
				}

				if ($delete_back)
				{
					if ($applicationId)
					{
						$this->mLivemms->outputApplicationOperate($core_host_input, $core_apidir_input, 'delete', $applicationId);
						
						//live
						if ($this->mLive)
						{
							$this->mLivemms->outputApplicationOperate($live_host_output, $live_apidir_output, 'delete', $applicationId);
						}
					}
					return -10;//流媒体服务器没能入库
				}
			}

		}

		$data = array(
			'ch_id' 			=> $applicationId,
			'user_id' 			=> $add_info['user_id'],
			'user_name' 		=> $add_info['user_name'],
			'appid' 			=> $add_info['appid'],
			'appname' 			=> $add_info['appname'],
			'code' 				=> $add_info['code'],
			'code_2' 			=> $add_info['code'],
			'name' 				=> $add_info['name'],
			'is_live' 			=> 1,
			'drm' 				=> $add_info['drm'],
			'open_ts' 			=> $add_info['open_ts'],
			'uri_in_num' 		=> count($intersect_stream_name),
			'uri_out_num' 		=> count($intersect_stream_name),
			'save_time' 		=> $add_info['save_time'],
			'live_delay' 		=> $add_info['live_delay'],
		//	'stream_display_name' => $stream['s_name'],
			'stream_mark' 		=> $stream_info['ch_name'],
		//	'beibo' 			=> serialize($beibo),
			'stream_id' 		=> $stream_info['id'],
			'main_stream_name'  => $intersect_stream_name[0],
			'stream_info_all' 	=> @serialize($intersect_stream_name),
			'audio_only' 		=> $input_info[0]['audio_only'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
			'server_id'			=> $server_info['id'],
		);
		
		//发布开始
		$data['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$add_info['column_id']);
		$data['column_id'] = serialize($data['column_id']);
		//发布结束
		
		$sql = "INSERT INTO " . DB_PREFIX . "channel SET ";
		$space = "";
		foreach($data as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
		
		$data['id'] = $this->db->insert_id();

		if ($_FILES['files']['tmp_name'])
		{
			$file['Filedata'] = $_FILES['files'];
			
			$material = $this->mMaterial->addMaterial($file, $data['id']);
			
			$logo_info['id'] 		= $material['id'];
			$logo_info['type'] 		= $material['type'];
			$logo_info['host'] 		= $material['host'];
			$logo_info['dir'] 		= $material['dir'];
			$logo_info['filepath'] 	= $material['filepath'];
			$logo_info['name'] 		= $material['name'];
			$logo_info['filename'] 	= $material['filename'];
			$logo_info['url'] 		= $material['url'];
		}
		
		$logo_infos = $logo_info ? serialize($logo_info) : '';
		
		if ($_FILES['files_mobile']['tmp_name'])
		{
			$file['Filedata'] = $_FILES['files_mobile'];
			
			$material_mobile = $this->mMaterial->addMaterial($file, $data['id']);
			
			$logo_mobile_info['id'] 		= $material_mobile['id'];
			$logo_mobile_info['type'] 		= $material_mobile['type'];
			$logo_mobile_info['host'] 		= $material_mobile['host'];
			$logo_mobile_info['dir'] 		= $material_mobile['dir'];
			$logo_mobile_info['filepath'] 	= $material_mobile['filepath'];
			$logo_mobile_info['name'] 		= $material_mobile['name'];
			$logo_mobile_info['filename'] 	= $material_mobile['filename'];
			$logo_mobile_info['url'] 		= $material_mobile['url'];
		}
		
		$logo_mobile_infos = $logo_mobile_info ? serialize($logo_mobile_info) : '';
		
		$sql = "UPDATE " . DB_PREFIX . "channel SET order_id = " . $data['id'] . ", logo_info = '" . $logo_infos . "', logo_mobile_info = '" . $logo_mobile_infos . "' WHERE id = " . $data['id'];	

		$this->db->query($sql);

		if (!empty($channel_stream_info))
		{
			foreach ($channel_stream_info AS $k=>$v)
			{
				$data_channel_stream= array(
					'channel_id' 		=> $data['id'],
					'stream_id' 		=> $stream_id,
					'delay_stream_id' 	=> $v['delay_stream_id'],
					'chg_stream_id' 	=> $v['chg_stream_id'],
					'out_stream_id' 	=> $v['out_stream_id'],
					'stream_name' 		=> $v['stream_name'],
					'out_stream_name' 	=> $v['out_stream_name'],
					'is_main' 			=> $v['is_main'] ,
					'bitrate' 			=> $v['bitrate'],
					'create_time' 		=> TIMENOW,
					'update_time' 		=> TIMENOW,
					'ip' 				=> hg_getip()
				);
				$sql = "INSERT INTO " . DB_PREFIX . "channel_stream SET ";
				$space = "";
				foreach($data_channel_stream AS $key => $value)
				{
					$sql .= $space . $key . "=" . "'" . $value . "'";
					$space = ",";
				}
				$this->db->query($sql);
			}
		}

		if ($data['id'])
		{
			//放入发布队列
			$sql = "SELECT stream_state, column_id FROM " . DB_PREFIX . "channel WHERE id = " . $data['id'];
			$r = $this->db->query_first($sql);
			if(!empty($r['column_id']))
			{
				$op = 'insert';
				$this->publish_insert_query($data['id'],$op,$add_info['user_name']);
			}
			
			$sql = "SELECT * FROM " . DB_PREFIX ."channel WHERE id = " . $data['id'];
			$ret = $this->db->query_first($sql);
			$this->addLogs('新增直播频道' , '' , $ret , '' , '',$ret['name']);
			return $data;
		}

		return false;
	}

	public function update($id, $add_info, $channel, $stream_info, $server)
	{
		$stream_id = $stream_info['id'];
		$server_info 	= $server['server_info'];
		//
		if ($server_info['core_in_host'])	//取数据库配置
		{
			//主控
			$core_host_input   = $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$core_apidir_input = $server_info['input_dir'];
			
			//时移
			if ($server_info['is_dvr_output'])
			{
				$dvr_host_output 	= $server_info['dvr_in_host'] . ':' . $server_info['dvr_in_port'];
			}
			else 
			{
				$dvr_host_output 	= $core_host_input;
			}
			$dvr_apidir_output    = $server_info['output_dir'];
		}
		else 	//去配置文件
		{
			//主控
			$core_host_input   = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$core_apidir_input = $this->settings['wowza']['core_input_server']['input_dir'];
			
			//时移
			if ($this->settings['wowza']['dvr_output_server'])
			{
				$dvr_host_output = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['dvr_output_server']['port'];
			}
			else 
			{
				$dvr_host_output = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			}
			
			$dvr_apidir_output = $this->settings['wowza']['core_input_server']['output_dir'];
		}
		
		if ($this->mLive)
		{
			if ($server_info['is_live_output'])
			{
				$live_host_output 	= $server_info['live_in_host'] . ':' . $server_info['live_in_port'];
				$live_apidir_output = $server_info['output_dir'];
			}
			else 
			{
				$live_host_output 	= $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['live_output_server']['port'];
				$live_apidir_output = $this->settings['wowza']['live_output_server']['output_dir'];
			}
		}
		//
		
		$ret_select = $this->mLivemms->outputApplicationSelect($dvr_host_output, $dvr_apidir_output);
		
		if (!$ret_select)
		{
			return -55;//媒体服务器未启动
		}
		
		$tpl_stream_name = $add_info['stream_name'];
		
		$tpl_stream_name_index = array();
		if (!empty($tpl_stream_name))
		{
			foreach ($tpl_stream_name AS $k=>$v)
			{
				$tpl_stream_name_index[$v] = $v;
			}
		}
		
		$stream_state = $channel['stream_state'];
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id = " . $id . " ORDER BY id ASC";
		$q = $this->db->query($sql);

		$channel_stream_name = $channel_stream_info = $channel_stream_info_index = $channel_stream_name_index = array();

		while ($row = $this->db->fetch_array($q))
		{
			$channel_stream_info[] = $row;
			$channel_stream_name[] = $row['stream_name'];
			$channel_stream_info_index[$row['stream_name']] = $row;
			$channel_stream_name_index[$row['stream_name']] = $row['stream_name'];
		}

		$type = $stream_info['type']; //是否是文件流
		
		$stream_other_info = @unserialize($stream_info['other_info']);

		$input_info = $stream_other_info['input'];
		
		if (!empty($input_info))
		{
			$input_stream_name = $input_stream_name_index = array();
			foreach ($input_info AS $k=>$v)
			{
				$input_stream_name[] = $v['name'];
				$input_stream_name_index[$v['name']] = $v['name'];
			}
		}
	
		//input_info 和 channel_stream 差集

		$diff_inputInfo_channelStream = @array_diff($input_stream_name, $channel_stream_name);
		
		//tpl_stream_name 和 input_info 差集
		
		$diff_tpl_inputInfo_name = @array_diff($input_stream_name, $tpl_stream_name);
		$diff_tpl_inputInfo_name_index = @array_diff($input_stream_name_index, $tpl_stream_name_index);
		
		//tpl_stream_name 和 channel_stream 差集
		
		$tpl_channelStream_name = @array_diff($tpl_stream_name, $channel_stream_name);
		$tpl_channelStream_name_index = @array_diff($tpl_stream_name_index, $channel_stream_name_index);

		if (empty($tpl_channelStream_name_index))
		{
			$tpl_channelStream_name_index = @array_diff($channel_stream_name_index, $tpl_stream_name_index);
		}

		$channelStream_tpl_name_index = @array_diff($channel_stream_name_index, $tpl_stream_name_index);
		
		if (!empty($input_info))
		{
			//信号流 频道信号 组合的数据
			$inputInfo_channelStream_data = array();
			$inputInfo_channelStream_tpl_data = array();
			foreach ($input_info AS $k=>$v)
			{
				if (!empty($channel_stream_info))
				{
					foreach ($channel_stream_info AS $kk=>$vv)
					{
						if ($v['name'] == $vv['stream_name'])
						{
							$inputInfo_channelStream_data[$k]['input'] = $v;
							$inputInfo_channelStream_data[$k]['channel_stream'] = $vv;
						}

						if ($v['name'] == $diff_inputInfo_channelStream[$k])
						{
							$inputInfo_channelStream_data[$k]['input'] = $v;
							$inputInfo_channelStream_data[$k]['channel_stream'] = array();
						}

						if ($vv['stream_name'] == $diff_tpl_inputInfo_name[$k])
						{
							$inputInfo_channelStream_tpl_data[$k]['input'] = $v;
							$inputInfo_channelStream_tpl_data[$k]['channel_stream'] = $vv;
						}
					}
				}
			}
		}
		
		if (!empty($inputInfo_channelStream_data))
		{
			//用于更新数据
			$update_data = array();
			foreach ($inputInfo_channelStream_data AS $k=>$v)
			{
				if ($tpl_stream_name[$k] == $v['input']['name'])
				{
					$update_data[$k] = $v;
				}
			}
		}

		if (empty($inputInfo_channelStream_data))
		{
			$update_data = array();
			if (!empty($input_info))
			{
				foreach ($input_info AS $k=>$v)
				{
					if ($tpl_stream_name[$k] == $v['name'])
					{
						$update_data[$k]['input'] = $v;
						$update_data[$k]['channel_stream'] = array();
					}
				}
			}
		}

		$outputType = 0;
		
		if ($add_info['open_ts'])
		{
			$outputType = 3;
		}
		else
		{
			$outputType = 1;
		}
		
		$length = $add_info['save_time'] * 3600;	//时移时间

		$delay  = $add_info['live_delay'];			//延时时间
		
		$drm    = $add_info['drm'];					//防盗链

		//输出层
//		$appName_output = $channel['code'];
		$appName_output = $add_info['code'];
		
		if ($appName_output != $channel['code'] || $add_info['save_time'] != $channel['save_time'] || $add_info['open_ts'] != $channel['open_ts'] || $channel['drm'] != $add_info['drm'])
		{
			$ret_app_output = $this->mLivemms->outputApplicationUpdate($dvr_host_output, $dvr_apidir_output, $channel['ch_id'], $appName_output, $length, $drm, $outputType);
		
			if ($ret_app_output['head'])
			{
				return $ret_app_output;
			}
			
			if (!$ret_app_output['result'])
			{
				return -20; //时移输出层应用更新失败
			}
			
			if ($channel['stream_state'])
			{
				$stream_state = 0; 
			}
			
			//live
			if ($this->mLive)
			{
				$_ret_app_output = $this->mLivemms->outputApplicationUpdate($live_host_output, $live_apidir_output, $channel['ch_id'], $appName_output, 0, $drm, $outputType);
				if (!$_ret_app_output['result'])
				{
					return -200; //直播输出层应用更新失败
				}
			}
		}
		//stream_id 不变的情况下
		if ($stream_id == $channel['stream_id'])
		{
			if (!empty($inputInfo_channelStream_tpl_data))	//delete
			{
				foreach ($inputInfo_channelStream_tpl_data AS $k=>$v)
				{
					//延时层
					if ($channel['live_delay'])
					{
						$ret_delay = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'delete', $v['channel_stream']['delay_stream_id']);
					}
					
					//切播层
					$ret_chg = $this->mLivemms->inputChgStreamOperate($core_host_input, $core_apidir_input, 'delete', $v['channel_stream']['chg_stream_id']);
					
					//输出层
					$ret_output = $this->mLivemms->outputStreamOperate($dvr_host_output, $dvr_apidir_output, 'delete', $v['channel_stream']['out_stream_id']);
					
					//live
					if ($this->mLive)
					{
						$_ret_output = $this->mLivemms->outputStreamOperate($live_host_output, $live_apidir_output, 'delete', $v['channel_stream']['out_stream_id']);
					}
					
					//删除channel_stream
					if ($ret_output['result'])
					{
						$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE id = " . $v['channel_stream']['id'];
						$this->db->query($sql);
					}
				}
			}
		}
		else //stream_id 变化的情况下
		{
			if (!empty($channel_stream_info_index))
			{
				foreach ($channel_stream_info_index AS $k=>$v)
				{
					if ($tpl_channelStream_name_index[$k] == $v['stream_name'] || $diff_tpl_inputInfo_name_index[$k] == $v['stream_name'] || $channelStream_tpl_name_index[$k] == $v['stream_name'])
					{
						//延时层
						if ($channel['live_delay'])
						{
							$ret_delay = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'delete', $v['delay_stream_id']);
						}
						
						//切播层
						$ret_chg = $this->mLivemms->inputChgStreamOperate($core_host_input, $core_apidir_input, 'delete', $v['chg_stream_id']);
						
						//输出层
						$ret_output = $this->mLivemms->outputStreamOperate($dvr_host_output, $dvr_apidir_output, 'delete', $v['out_stream_id']);
						
						//live
						if ($this->mLive)
						{
							$_ret_output = $this->mLivemms->outputStreamOperate($live_host_output, $live_apidir_output, 'delete', $v['out_stream_id']);
						}
					
						//删除channel_stream
						if ($ret_output['result'])
						{
							$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE id = " . $v['id'];
							$this->db->query($sql);
						}
					}
				}
				
			}
		}
		
		//更新数据
		if (!empty($update_data))
		{
			$data_channel_stream = $ret_delay_id = $ret_chg_id = $ret_out_id= array();

			foreach ($update_data AS $k=>$v)
			{
				if (empty($v['channel_stream']))	//create
				{
					if ($delay)
					{
						//延时层
						$ret_delay = $this->mLivemms->inputDelayInsert($core_host_input, $core_apidir_input, $v['input']['id'], $delay);
						
						$ret_delay_id[$k] = $ret_delay['delay']['id'];
						
						if (!$ret_delay_id[$k])
						{
							continue;
						}
						
						$sourceId = $ret_delay_id[$k];
						
						$sourceType = 2;
			/*			
						//重启流
						if (!$channel['stream_state'] && $ret_delay_id[$k])
						{
							$ret_stream_delay = $this->mLivmms->inputDelayOperate('stop', $ret_delay_id[$k]);
						}
						else
						{
							$ret_stream_delay = $this->mLivmms->inputDelayOperate('start', $ret_delay_id[$k]);
						}
						
						if (!$ret_stream_delay['result'])
						{
							continue;
						}
			*/	
					}
					else 
					{
						$sourceId = $v['input']['id'];
						
						$sourceType = !$type ? 1 : 3;
					}
					
					//切播层
					$ret_chg = $this->mLivemms->inputChgStreamInsert($core_host_input, $core_apidir_input, $sourceId, $sourceType);
					
					$ret_chg_id[$k] = $ret_chg['output']['id'];
					
					if (!$ret_chg_id[$k])
					{
						continue;
					}
			/*	
					//重启流
					if (!$channel['stream_state'] && $ret_chg_id[$k])
					{
						$ret_stream_chg = $this->mLivmms->inputChgStreamOperate('stop', $ret_chg_id[$k]);
					}
					else
					{
						$ret_stream_chg = $this->mLivmms->inputChgStreamOperate('start', $ret_chg_id[$k]);
					}
					
					if (!$ret_stream_chg['result'])
					{
						continue;
					}
			*/	
					//输出层
					if ($server_info['core_in_host'])
					{
						$wowzaip_chg 	= $server_info['core_in_host'] . ':' . $server_info['core_out_port'];
					}
					else 
					{
						$wowzaip_chg 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
					
					$appName_chg 	= $this->settings['wowza']['chg']['app_name'];
					$streamName_chg = $ret_chg_id[$k] . $this->settings['wowza']['chg']['suffix'];
					
					$chg_url = hg_streamUrl($wowzaip_chg, $appName_chg, $streamName_chg);
						
					$ret_output = $this->mLivemms->outputStreamInsert($dvr_host_output, $dvr_apidir_output, 0, $channel['ch_id'], $v['input']['name'], $chg_url);
					
					$ret_out_id[$k] = $ret_output['stream']['id'];
					
					//live
					if ($this->mLive)
					{
						$_ret_output = $this->mLivemms->outputStreamInsert($live_host_output, $live_apidir_output, $ret_out_id[$k], $channel['ch_id'], $v['input']['name'], $chg_url);
					}
					
					if ($ret_output['result'])
					{
				/*
						//重启流
						if (!$channel['stream_state'] && $ret_out_id[$k])
						{
							$ret_stream_output = $this->mLivmms->outputStreamOperate('stop', $ret_out_id[$k]);
						}
						else
						{
							$ret_stream_output = $this->mLivmms->outputStreamOperate('start', $ret_out_id[$k]);
						}
						
						if (!$ret_stream_output['result'])
						{
							continue;
						}
				*/
						$data_channel_stream[$k] = array(
							'channel_id' 		=> $id,
							'stream_id' 		=> $stream_id,
							'delay_stream_id' 	=> $ret_delay_id[$k],
							'chg_stream_id' 	=> $ret_chg_id[$k],
							'out_stream_id' 	=> $ret_out_id[$k],
							'stream_name' 		=> $v['input']['name'],
							'out_stream_name' 	=> $v['input']['name'],
							'is_main' 			=> ($k == 0) ? 1 : 0,
							'bitrate' 			=> $v['input']['bitrate'],
							'create_time' 		=> TIMENOW,
							'update_time' 		=> TIMENOW,
							'ip' 				=> hg_getip()
						);
						$sql = "INSERT INTO " . DB_PREFIX . "channel_stream SET ";
						$space = "";
						foreach($data_channel_stream[$k] AS $key => $value)
						{
							$sql .= $space . $key . "=" . "'" . $value . "'";
							$space = ",";
						}
						$this->db->query($sql);
					}
				}
				else	//update
				{
					if ($delay)
					{
						//延时层
						if ($delay == $channel['live_delay'] && $stream_id == $channel['stream_id'])
						{
							$ret_delay_id[$k] = $v['channel_stream']['delay_stream_id'];
						}
						else 
						{
							if ($v['channel_stream']['delay_stream_id'])
							{
								$ret_delay_delete = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'delete', $v['channel_stream']['delay_stream_id']);

								if (!$ret_delay_delete['result'])
								{
									continue;
								}
							}
							
							$ret_delay = $this->mLivemms->inputDelayInsert($core_host_input, $core_apidir_input, $v['input']['id'], $delay);
							$ret_delay_id[$k] = $ret_delay['delay']['id'];

							if (!$ret_delay_id[$k])
							{
								continue;
							}
					/*		
							//重启流
							if (!$channel['stream_state'] && $ret_delay_id[$k])
							{
								$ret_stream_delay = $this->mLivmms->inputDelayOperate('stop', $ret_delay_id[$k]);
							}
							else
							{
								$ret_stream_delay = $this->mLivmms->inputDelayOperate('start', $ret_delay_id[$k]);
							}
							
							if (!$ret_stream_delay['result'])
							{
								continue;
							}
					*/
						}
					/*	
						//重启流
						if (!$channel['stream_state'] && $ret_delay_id[$k])
						{
							$ret_stream_delay = $this->mLivmms->inputDelayOperate('stop', $ret_delay_id[$k]);
						}
						else
						{
							$ret_stream_delay = $this->mLivmms->inputDelayOperate('start', $ret_delay_id[$k]);
						}
						
						if (!$ret_stream_delay['result'])
						{
							continue;
						}
					*/	
						$sourceId 	= $ret_delay_id[$k];
						
						$sourceType = 2;
					}
					else
					{
						//删除延时层
						if ($channel['live_delay'])
						{
							if ($v['channel_stream']['delay_stream_id'])
							{
								$ret_delay_delete = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'delete', $v['channel_stream']['delay_stream_id']);

								if (!$ret_delay_delete['result'])
								{
									continue;
								}
							}
							
							$ret_delay_id[$k] = 0;
						}
						
						$sourceId 	= $v['input']['id'];
						
						$sourceType = !$type ? 1 : 3;
					}

					//切播层
					$chgId = $v['channel_stream']['chg_stream_id'];
					
					$ret_chg = $this->mLivemms->inputChgStreamUpdate($core_host_input, $core_apidir_input, $chgId, $sourceId, $sourceType);

					if (!$ret_chg['result'])
					{
						continue;
					}
				/*
					//重启流
					if (!$channel['stream_state'] && $chgId)
					{
						$ret_stream_chg = $this->mLivmms->inputChgStreamOperate('stop', $chgId);
					}
					else
					{
						$ret_stream_chg = $this->mLivmms->inputChgStreamOperate('start', $chgId);
					}
					
					if (!$ret_stream_chg['result'])
					{
						continue;
					}
				*/	
					//输出层
					$ret_chg_id[$k] = $chgId;
				
					//输出层
					if ($server_info['core_in_host'])
					{
						$wowzaip_chg 	= $server_info['core_in_host'] . ':' . $server_info['core_out_port'];
					}
					else 
					{
						$wowzaip_chg 	= $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['out_port'];
					}
					
					$appName_chg 	= $this->settings['wowza']['chg']['app_name'];
					$streamName_chg = $ret_chg_id[$k] . $this->settings['wowza']['chg']['suffix'];
					
					$chg_url = hg_streamUrl($wowzaip_chg, $appName_chg, $streamName_chg);
				
					$ret_output = $this->mLivemms->outputStreamUpdate($dvr_host_output, $dvr_apidir_output, $v['channel_stream']['out_stream_id'], $channel['ch_id'], $v['channel_stream']['stream_name'], $chg_url);
					
					//live
					if ($this->mLive)
					{
						$_ret_output = $this->mLivemms->outputStreamUpdate($live_host_output, $live_apidir_output, $v['channel_stream']['out_stream_id'], $channel['ch_id'], $v['channel_stream']['stream_name'], $chg_url);
					}

					if ($ret_output['result'])
					{
					/*	
						//重启流
						if (!$channel['stream_state'] && $v['channel_stream']['out_stream_id'])
						{
							$ret_stream_output = $this->mLivmms->outputStreamOperate('stop', $v['channel_stream']['out_stream_id']);
						}
						else
						{
							$ret_stream_output = $this->mLivmms->outputStreamOperate('start', $v['channel_stream']['out_stream_id']);
						}
						
						if (!$ret_stream_output['result'])
						{
							continue;
						}
					*/	
						$data_channel_stream[$k] = array(
										'stream_id' 		=> $stream_id,
										'delay_stream_id' 	=> $ret_delay_id[$k] ? $ret_delay_id[$k] : '00',
										'stream_name' 		=> $v['channel_stream']['stream_name'],
										'out_stream_name' 	=> $v['channel_stream']['stream_name'],
										'is_main' 			=> ($k == 0) ? 1 : 0,
										'bitrate' 			=> $v['channel_stream']['bitrate'],
										'update_time' 		=> TIMENOW
									);
									
						$sql = "UPDATE " . DB_PREFIX . "channel_stream SET ";
						$space = "";
						$sql_extra = "";
						foreach($data_channel_stream[$k] AS $key => $value)
						{
							if($value)
							{
								$sql_extra .= $space . $key . "=" . "'" . $value . "'";
								$space = ",";
							}
						}

						if($sql_extra)
						{
							$sql .= $sql_extra . " WHERE id=" . $v['channel_stream']['id'];
							$this->db->query($sql);
						}
						
					}
				}
			}
		}
		$data = array(
			'name' 				=> $add_info['name'],
			'code' 				=> $add_info['code'],
	//		'is_live' 			=> 1,
	//		'stream_state' 		=> $stream_state,
			'stream_state' 		=> 0,
			'drm' 				=> $add_info['drm'],
			'open_ts' 			=> $add_info['open_ts'],
			'uri_in_num' 		=> count($tpl_stream_name),
			'uri_out_num' 		=> count($tpl_stream_name),
			'save_time' 		=> $add_info['save_time'],
			'live_delay' 		=> $add_info['live_delay'],
	//		'stream_display_name' => $stream['s_name'],
			'stream_mark' 		=> $stream_info['ch_name'],
	//		'beibo' 			=> $beibo ? serialize($beibo) : array(),
			'stream_id' 		=> $stream_id,
			'main_stream_name' 	=> $tpl_stream_name[0],
			'stream_info_all' 	=> @serialize($tpl_stream_name),
			'audio_only' 		=> $input_info[0]['audio_only'],
			'update_time' 		=> TIMENOW,
		);
	
		//发布开始
		$channel['column_id'] = @unserialize($channel['column_id']);
		$ori_column_id = array();
		if(is_array($channel['column_id']))
		{
			$ori_column_id = @array_keys($channel['column_id']);
		}

		$data['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$add_info['column_id']);
		$data['column_id'] = serialize($data['column_id']);
		//发布结束
		//获取发布前数据
		$sql_ = "SELECT * FROM " . DB_PREFIX ."channel WHERE id = " . $id;
		$pre_data = $this->db->query_first($sql_);	
		
		$sql = "UPDATE " . DB_PREFIX . "channel SET ";
		$space = "";
		foreach($data AS $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id = " . $id; 
		$this->db->query($sql);
		
		$data['id'] = $id;
		
		if ($_FILES['files']['tmp_name'])
		{
			$file['Filedata'] = $_FILES['files'];
			
			$material = $this->mMaterial->addMaterial($file, $data['id']);
	
			$logo_info['id'] 		= $material['id'];
			$logo_info['type'] 		= $material['type'];
			$logo_info['host'] 		= $material['host'];
			$logo_info['dir'] 		= $material['dir'];
			$logo_info['filepath'] 	= $material['filepath'];
			$logo_info['name'] 		= $material['name'];
			$logo_info['filename'] 	= $material['filename'];
			$logo_info['url'] 		= $material['url'];
				
			$sql = "UPDATE " . DB_PREFIX . "channel SET logo_info = '" . serialize($logo_info) . "' WHERE id = " . $data['id'];	

			$this->db->query($sql);
		}
			
		if ($_FILES['files_mobile']['tmp_name'])
		{
			$file['Filedata'] = $_FILES['files_mobile'];
			
			$material_mobile = $this->mMaterial->addMaterial($file, $data['id']);
			
			$logo_mobile_info['id'] 		= $material_mobile['id'];
			$logo_mobile_info['type'] 		= $material_mobile['type'];
			$logo_mobile_info['host'] 		= $material_mobile['host'];
			$logo_mobile_info['dir'] 		= $material_mobile['dir'];
			$logo_mobile_info['filepath'] 	= $material_mobile['filepath'];
			$logo_mobile_info['name'] 		= $material_mobile['name'];
			$logo_mobile_info['filename'] 	= $material_mobile['filename'];
			$logo_mobile_info['url'] 		= $material_mobile['url'];
			
			$sql = "UPDATE " . DB_PREFIX . "channel SET logo_mobile_info = '" . serialize($logo_mobile_info) . "' WHERE id = " . $data['id'];	

			$this->db->query($sql);
		}
	/*	
		//重启application
		if (!$channel['stream_state'])
		{
			$this->mLivmms->outputApplicationOperate('stop', $channel['ch_id']);
		}
		else
		{
			$this->mLivmms->outputApplicationOperate('start', $channel['ch_id']);
		}
	*/	
		if ($data['id'])
		{
			//发布开始
			$sql = "SELECT * FROM " . DB_PREFIX ."channel WHERE id = " . $data['id'];
			$ret = $this->db->query_first($sql);
			//更改文章后发布的栏目
			$ret['column_id'] = unserialize($ret['column_id']);
			$new_column_id = array();
			if(is_array($ret['column_id']))
			{
				$new_column_id = array_keys($ret['column_id']);
			}
			
			if(!empty($ret['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = @array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->publish_insert_query($data['id'], 'delete',$del_column);
				}
				$add_column = @array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					$this->publish_insert_query($data['id'], 'insert',$add_column);
				}
				$same_column = @array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->publish_insert_query($data['id'], 'update',$same_column);
				}
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				$this->publish_insert_query($data['id'],$op);
			}
			//发布结束
			$this->addLogs('更新直播频道' , $pre_data , $data , '' , '',$data['name']);
			
			return $data;
		}
		return false;
	}

	public function delete($id)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);

		$channel = $server_id = array();
		while ($row = $this->db->fetch_array($q))
		{
			$server_id[] = $row['server_id'];
			$channel[$row['id']] = $row;
		}

		//服务器配置
		if (!empty($server_id))
		{
			$server_id 		= implode(',', @array_unique($server_id));
			$server_info	= $this->mServerConfig->get_server_config($server_id);
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . $id . ") ORDER BY id ASC";
		$q = $this->db->query($sql);
		
		$channel_stream = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_stream[$row['channel_id']]['channel_stream'][$row['id']] = $row;
		}
		
		if (!empty($channel))
		{
			foreach ($channel AS $k => $v)
			{
				if ($channel_stream[$k])
				{
					$channel_info[$k] = @array_merge($channel[$k], $channel_stream[$k]); 
				}
				else
				{
					$channel_info[$k] = $channel[$k];
				}
			}
		}

		if (!empty($channel_info))
		{
			foreach ($channel_info AS $v)
			{
				//
				if ($server_info[$v['server_id']]['core_in_host'])	//取数据库配置
				{
					//主控
					$core_host_input   = $server_info[$v['server_id']]['core_in_host'] . ':' . $server_info[$v['server_id']]['core_in_port'];
					$core_apidir_input = $server_info[$v['server_id']]['input_dir'];
					
					//时移
					if ($server_info['is_dvr_output'])
					{
						$dvr_host_output 	= $server_info[$v['server_id']]['dvr_in_host'] . ':' . $server_info[$v['server_id']]['dvr_in_port'];
					}
					else 
					{
						$dvr_host_output 	= $core_host_input;
					}
					$dvr_apidir_output    = $server_info[$v['server_id']]['output_dir'];
				}
				else 	//去配置文件
				{
					//主控
					$core_host_input   = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
					$core_apidir_input = $this->settings['wowza']['core_input_server']['input_dir'];
					
					//时移
					if ($this->settings['wowza']['dvr_output_server'])
					{
						$dvr_host_output = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['dvr_output_server']['port'];
					}
					else 
					{
						$dvr_host_output = $core_host_input;
					}
					
					$dvr_apidir_output = $this->settings['wowza']['core_input_server']['output_dir'];
				}
				//live
				if ($this->mLive)
				{
					if ($server_info['is_live_output'])
					{
						$live_host_output 	= $server_info[$v['server_id']]['live_in_host'] . ':' . $server_info[$v['server_id']]['live_in_port'];
						$live_apidir_output = $server_info[$v['server_id']]['output_dir'];
					}
					else 
					{
						$live_host_output 	= $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['live_output_server']['port'];
						$live_apidir_output = $this->settings['wowza']['live_output_server']['output_dir'];
					}
				}
				//
				//删除流媒体服务器信息
				if ($v['channel_stream'])
				{
					foreach ($v['channel_stream'] AS $kk => $vv)
					{
						if ($vv['delay_stream_id'])
						{
							$ret_delay = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'delete', $vv['delay_stream_id']);
						}
						
						$ret_chg = $this->mLivemms->inputChgStreamOperate($core_host_input, $core_apidir_input, 'delete', $vv['chg_stream_id']);
					}
				}
				
				$ret_output = $this->mLivemms->outputApplicationOperate($dvr_host_output, $dvr_apidir_output, 'delete', $v['ch_id']);
				
				//live
				if ($this->mLive)
				{
					$_ret_output = $this->mLivemms->outputApplicationOperate($live_host_output, $live_apidir_output, 'delete', $v['ch_id']);
				}
			}
		}
	
		if ($ret_output['head'])
		{
			return $ret_app_output;
		}
		
		if (!$ret_output['result'])
		{
			return -20;
		}
		
		if ($ret_output['result'])
		{
			//删除频道
			$sql = "DELETE FROM " . DB_PREFIX . "channel WHERE id IN (" . $id . ")";
			$this->db->query($sql);

			//删除节目单
			$sql = "DELETE FROM " . DB_PREFIX . "program WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);

			//删除自动收录
			$sql = "DELETE FROM " . DB_PREFIX . "program_record WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);

			//删除屏蔽节目
			$sql = "DELETE FROM " . DB_PREFIX . "program_shield WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
    	
			//删除信号流信息
			$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);

			//删除串联单
			$sql = "DELETE FROM " . DB_PREFIX . "channel_chg_plan WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
			
			$programPlanSql = "SELECT id FROM " . DB_PREFIX . "program_plan WHERE channel_id IN (" . $id . ")";
			$q = $this->db->query($programPlanSql);
			$program_plan_id = array();
			while($row = $this->db->fetch_array($q))
			{
				$program_plan_id[] = $row['id'];
			}

			//删除节目单周数
			if(is_array($program_plan_id) && $program_plan_id)
			{
				foreach($program_plan_id as $v)
				{
					$sql = "DELETE FROM " . DB_PREFIX . "program_plan_relation WHERE plan_id IN (" . $v . ")";
					$this->db->query($sql);
				}
			}
			
			//删除节目单计划
			$sql = "DELETE FROM " . DB_PREFIX . "program_plan WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
			
			$changePlanSql = "SELECT id FROM " . DB_PREFIX . "change_plan WHERE channel_id IN (" . $id . ")";
			$q = $this->db->query($changePlanSql);
			$plan_id = array();
			while($row = $this->db->fetch_array($q))
			{
				$plan_id[] = $row['id'];
			}

			//删除串联单周数
			if(is_array($plan_id) && $plan_id)
			{
				foreach($plan_id as $v)
				{
					$sql = "DELETE FROM " . DB_PREFIX . "change_plan_relation WHERE plan_id IN (" . $v . ")";
					$this->db->query($sql);
				}
			}
			
			//删除串联单计划
			$sql = "DELETE FROM " . DB_PREFIX . "change_plan WHERE channel_id IN (" . $id . ")";
			$this->db->query($sql);
			
			$this->addLogs('删除直播频道' , $channel , '', '', '','删除直播频道'.$id);
		}

	}

	public function getUriName($id)
	{
		$sql = "SELECT type, other_info FROM " . DB_PREFIX . "stream WHERE id=" . $id;
		$info = $this->db->query_first($sql);
		
		$stream_name = unserialize($info['other_info']);
		$uri_info = array();
		foreach($stream_name['input'] AS $key => $value)
		{
			$uri_info['stream_name'][] = $value['name'];
			$uri_info['type'] = $info['type'];
		}
		
		return $uri_info;
	}

	public function check_channelCode($code)
	{
		$sql = "SELECT code FROM " . DB_PREFIX . "channel WHERE code = '" . $code . "'";
		$info = $this->db->query_first($sql);
		if (!$info)
		{
			return true;	//验证通过
		}
		return false;		//验证不通过
	}

	public function count($condition)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "channel AS c WHERE 1" . $condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function streamState($id)
	{
		$sql = "SELECT live_delay, stream_state, server_id FROM " . DB_PREFIX . "channel WHERE id = " . $id;
		$channel = $this->db->query_first($sql);

		if (empty($channel))
		{
			return -1;
		}
		$channel_state = $channel['stream_state'];

		$sql = "SELECT id, delay_stream_id, chg_stream_id, out_stream_id FROM " . DB_PREFIX . "channel_stream WHERE channel_id = " . $id . " ORDER BY id ASC";
		$q = $this->db->query($sql);
		
		$channel_stream = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_stream[$row['id']] = $row;
		}
		
		if (empty($channel_stream))
		{
			return -2;
		}
	
		$server_id = $channel['server_id'];
		if ($server_id)
		{
			$server_info 	= $this->mServerConfig->get_server_config_by_id($server_id);
		}
		//
		if ($server_info['core_in_host'])	//取数据库配置
		{
			//主控
			$core_host_input   = $server_info['core_in_host'] . ':' . $server_info['core_in_port'];
			$core_apidir_input = $server_info['input_dir'];
			
			//时移
			if ($server_info['is_dvr_output'])
			{
				$dvr_host_output 	= $server_info['dvr_in_host'] . ':' . $server_info['dvr_in_port'];
			}
			else 
			{
				$dvr_host_output 	= $core_host_input;
			}
			$dvr_apidir_output    = $server_info['output_dir'];
		}
		else 	//去配置文件
		{
			//主控
			$core_host_input   = $this->settings['wowza']['core_input_server']['host'] . ':' . $this->settings['wowza']['core_input_server']['port'];
			$core_apidir_input = $this->settings['wowza']['core_input_server']['input_dir'];
			
			//时移
			if ($this->settings['wowza']['dvr_output_server'])
			{
				$dvr_host_output = $this->settings['wowza']['dvr_output_server']['host'] . ':' . $this->settings['wowza']['dvr_output_server']['port'];
			}
			else 
			{
				$dvr_host_output = $core_host_input;
			}
			
			$dvr_apidir_output = $this->settings['wowza']['core_input_server']['output_dir'];
		}
		//live
		if ($this->mLive)
		{
			if ($server_info['is_live_output'])
			{
				$live_host_output 	= $server_info['live_in_host'] . ':' . $server_info['live_in_port'];
				$live_apidir_output = $server_info['output_dir'];
			}
			else 
			{
				$live_host_output 	= $this->settings['wowza']['live_output_server']['host'] . ':' . $this->settings['wowza']['live_output_server']['port'];
				$live_apidir_output = $this->settings['wowza']['live_output_server']['output_dir'];
			}
		}
		//
		
		//切播层启动停止延时时间
		$seconds = $this->settings['chg_sleep_time'];
		
		$new_channel_state = 0; //操作失败
		
		if (!$channel_state)	//停止
		{
			//流媒体状态
			if (!empty($channel_stream))
			{
				$ret_output_id = array();
				foreach ($channel_stream AS $k => $v)
				{
					if ($channel['live_delay'])
					{
						$ret_delay = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'start', $v['delay_stream_id']);
						$ret_delay_id[$v['delay_stream_id']] = $ret_delay['result'];
					}

					$ret_chg = $this->mLivemms->inputChgStreamOperate($core_host_input, $core_apidir_input, 'start', $v['chg_stream_id']);
					$ret_chg_id[$v['chg_stream_id']] = $ret_chg['result'];
					
					sleep($seconds);

					$ret_output = $this->mLivemms->outputStreamOperate($dvr_host_output, $dvr_apidir_output, 'start', $v['out_stream_id']);
					$ret_output_id[$v['out_stream_id']] = $ret_output['result'];
					
					//live
					if ($this->mLive)
					{
						$_ret_output = $this->mLivemms->outputStreamOperate($live_host_output, $live_apidir_output, 'start', $v['out_stream_id']);
					}
				}
			}
			
			if (!empty($ret_delay_id))
			{
				$result_delay_flag = '';
				foreach ($ret_delay_id AS $k => $v)
				{
					if (!$v)
					{
						$ret_delay = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'stop', $k);
						$result_delay_flag = 1;
					}
				}
			}
		
			if (!empty($ret_chg_id))
			{
				$result_chg_flag = '';
				foreach ($ret_chg_id AS $k => $v)
				{
					if (!$v)
					{
						$ret_chg = $this->mLivemms->inputChgStreamOperate($core_host_input, $core_apidir_input, 'stop', $k);
						$result_chg_flag = 1;
					}
				}
			}
			
			if (!empty($ret_output_id))
			{
				$result_output_flag = '';
				foreach ($ret_output_id AS $k => $v)
				{
					if (!$v)
					{
						$ret_output = $this->mLivemms->outputStreamOperate($dvr_host_output, $dvr_apidir_output, 'stop', $k);
						$result_output_flag = 1;
						
						//live
						if ($this->mLive)
						{
							$_ret_output = $this->mLivemms->outputStreamOperate($live_host_output, $live_apidir_output, 'stop', $k);
						}
					}
				}
			}
		
			if (!$result_output_flag)
			{
				$sql = "UPDATE " . DB_PREFIX . "channel SET stream_state = 1 WHERE id = " . $id;
				$this->db->query($sql);
	
				$new_channel_state = 1;
			}
		}
		else			//启动
		{
			//流媒体状态
			if (!empty($channel_stream))
			{
				$ret_output_id = array();
				foreach ($channel_stream AS $k => $v)
				{
					if ($channel['live_delay'])
					{
						$ret_delay = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'stop', $v['delay_stream_id']);
						$ret_delay_id[$v['delay_stream_id']] = $ret_delay['result'];
					}
					$ret_chg = $this->mLivemms->inputChgStreamOperate($core_host_input, $core_apidir_input, 'stop', $v['chg_stream_id']);
					$ret_chg_id[$v['chg_stream_id']] = $ret_chg['result'];
					
					sleep($seconds);

					$ret_output = $this->mLivemms->outputStreamOperate($dvr_host_output, $dvr_apidir_output, 'stop', $v['out_stream_id']);
					$ret_output_id[$v['out_stream_id']] = $ret_output['result'];
					
					//live
					if ($this->mLive)
					{
						$_ret_output = $this->mLivemms->outputStreamOperate($live_host_output, $live_apidir_output, 'stop', $v['out_stream_id']);
					}
				}
			}
			
			if (!empty($ret_delay_id))
			{
				$result_delay_flag = '';
				foreach ($ret_delay_id AS $k => $v)
				{
					if (!$v)
					{
						$ret_delay = $this->mLivemms->inputDelayOperate($core_host_input, $core_apidir_input, 'start', $k);
						$result_delay_flag = 1;
					}
				}
			}
		
			if (!empty($ret_chg_id))
			{
				$result_chg_flag = '';
				foreach ($ret_chg_id AS $k => $v)
				{
					if (!$v)
					{
						$ret_chg = $this->mLivemms->inputChgStreamOperate($core_host_input, $core_apidir_input, 'start', $k);
						$result_chg_flag = 1;
					}
				}
			}
			
			if (!empty($ret_output_id))
			{
				$result_output_flag = '';
				foreach ($ret_output_id AS $k => $v)
				{
					if (!$v)
					{
						$ret_output = $this->mLivemms->outputStreamOperate($dvr_host_output, $dvr_apidir_output, 'start', $k);
						$result_output_flag = 1;
						
						//live
						if ($this->mLive)
						{
							$_ret_output = $this->mLivemms->outputStreamOperate($live_host_output, $live_apidir_output, 'start', $k);
						}
					}
				}
			}
			
			if (!$result_output_flag)
			{
				$sql = "UPDATE " . DB_PREFIX . "channel SET stream_state = 0 WHERE id = " . $id;
				$this->db->query($sql);
	
				$new_channel_state = 2;
			}
		}

		return $new_channel_state;
	}
	
	/**
	*  发布系统，将内容传入发布队列
	*  
	*/	
	public function publish_insert_query($id,$op,$user_name,$column_id = array(),$child_queue = 0)
	{
		$id = intval($id);
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE id = " . $id;
		$info = $this->db->query_first($sql);
		if(empty($column_id))
		{		
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = @array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}			
		}
		else 
		{
			$column_id = implode(',',$column_id);
		}

 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 			=> MEMBER_PLAN_SET_ID,
			'from_id'   		=> $info['id'],
			'class_id'			=> 0,
			'column_id' 		=> $column_id,
			'title'     		=> $info['name'],
			'action_type' 		=> $op,
			'publish_time'  	=> $info['pub_time'],
			'publish_people' 	=> $user_name,
			'ip'   				=> hg_getip(),
		);
		if($child_queue)
		{
			$data['set_id'] = MEMBER_PLAN_SET_ID;
		}
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	
	public function publish($id, $column_id)
	{
		$new_column_id = explode(',',$column_id);
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
	
		//查询修改文章之前已经发布到的栏目
		$sql = "SELECT * FROM " . DB_PREFIX ."channel WHERE id = " . $id;
		$q = $this->db->query_first($sql);
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = @array_keys($q['column_id']);
		}
		
		$sql = "UPDATE " . DB_PREFIX ."channel SET column_id = '". $column_id ."' WHERE id = " . $id;
		$this->db->query($sql);
		
		if(!empty($q['expand_id']))   //已经发布过，对比修改先后栏目
		{
			$del_column = @array_diff($ori_column_id,$new_column_id);
			if(!empty($del_column))
			{
				$this->publish_insert_query($id, 'delete',$del_column);
			}
			$add_column = @array_diff($new_column_id,$ori_column_id);
			if(!empty($add_column))
			{
				$this->publish_insert_query($id, 'insert',$add_column);
			}
			$same_column = array_intersect($ori_column_id,$new_column_id);
			if(!empty($same_column))
			{
				$this->publish_insert_query($id, 'update',$same_column);
			}
		}
		else 							//未发布，直接插入
		{
			$op = "insert";
			$this->publish_insert_query($id,$op);
		}
		
		return true;
	}

	public function get_channel_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "channel WHERE id = " . $id;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function get_stream_by_id($id, $field = ' * ')
	{
		$sql = "SELECT {$field} FROM " . DB_PREFIX . "stream WHERE id = " . $id;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function getChannelInfo($condition, $offset, $count, $width = '112', $height = '43')
	{
		$limit = " LIMIT " . $offset . " , " . $count;

		$orderby = " ORDER BY id DESC ";
		
		$sql = "SELECT id, name, code, server_id, logo_info, audio_only FROM " . DB_PREFIX . "channel ";
		$sql.= " WHERE 1 " . $condition . $orderby . $limit;
		$q = $this->db->query($sql);
		
		$return = array();
		while($row = $this->db->fetch_array($q))
		{
			$row['logo_info'] 	= @unserialize($row['logo_info']);
			if ($row['logo_info'])
			{
				$imgsize = $width . 'x' . $height . '/';
				$row['logo_url'] = hg_material_link($row['logo_info']['host'], $row['logo_info']['dir'], $row['logo_info']['filepath'], $row['logo_info']['filename'], $imgsize);
			}
			$return[$row['id']] = $row;
		}
		return $return;
	}
}
?>