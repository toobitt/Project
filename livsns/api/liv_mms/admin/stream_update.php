<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
*@public function update|DelChannelStreamName|delete|goBackTip|channel_stream_reset|stream_status
*
* $Id: stream_update.php 9828 2012-08-24 05:31:21Z lijiaying $
***************************************************************************/
require('global.php');
class streamUpdateApi extends BaseFrm
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
	 * 更新信号流信息
	 * @name update
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道信号ID
	 * @param $s_name string 信号名称
	 * @param $ch_name string 信号标识
	 * @param $save_time int 回看时间 (小时)
	 * @param $live_delay int 延时时间 (分钟)
	 * @param $uri string 信号流地址
	 * @param $name string 流名称
	 * @param $bitrate int 码流
	 * @param $backstore array 支持格式(flv,ts)
	 * @param $audio_only tinyint 是否音频 (1-是 0-否)
	 * @param $wait_relay tinyint 是否推送 (1-是 0-否)
	 * @param $other_info string 流信息
	 * @param $update_time int 更新时间
	 * @param $audio_temp tinyint 临时变量
	 * @param $audio_only tinyint 同步频道音频设置参数
	 * @return $info['id'] int 频道信号ID
	 * @include tvie_api.php
	 */
	function update()
	{
		$id = $stream_id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('未传入频道信号ID');
		}

		$s_name = urldecode($this->input['s_name']);
		if(!$s_name)
		{
			$this->errorOutput('信号名称不能为空');
		}
		
		$save_time = intval($this->input['save_time']);
		$live_delay = intval($this->input['live_delay']);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "stream WHERE id = " . $id;
		$stream = $this->db->query_first($sql);
		
		$stream_other_info = unserialize($stream['other_info']);
		
		$input_info = $stream_other_info;
		
		if (!empty($input_info))
		{
			$input_name = $input_name_index = $input_info_index = array();

			foreach ($input_info AS $k=>$v)
			{
				$input_name[] = $v['name'];
				$input_name_index[$v['name']] = $v['name'];
				$input_info_index[$v['name']] = $v;
			}
		}
		
		$ch_id = $stream['ch_id'];
		$s_status = $stream['s_status'];
		
		if(is_array($this->input['counts']) && $this->input['counts'])
		{
			$streams_info = $tpl_uri = $tpl_name = $tpl_name_index = array();
			
			for($i = 0;$i< count($this->input['counts']);$i++)
			{
				$streams_info[$i]['id'] = urldecode($this->input['id_'.$i]);
				
				$streams_info[$i]['name'] = urldecode($this->input['name_'.$i]);
				if(!$streams_info[$i]['name'])
				{
					$this->errorOutput('输出标识不能为空');
				}
				$streams_info[$i]['ch_name'] = urldecode($this->input['ch_name_hidden']);
				
				$streams_info[$i]['uri'] = urldecode($this->input['uri_'.$i]);
				if(!$streams_info[$i]['uri'])
				{
					$this->errorOutput('来源地址不能为空');
				}
				$streams_info[$i]['bitrate'] = $this->input['bitrate_'.$i];
			
				$streams_info[$i]['backstore'][] = 'flv';
			
				$streams_info[$i]['audio_only'] = $this->input['audio_only'] ? intval($this->input['audio_only']) : 0;
				$streams_info[$i]['wait_relay'] = $this->input['wait_relay'] ? intval($this->input['wait_relay']) : 0;
				$streams_info[$i]['recover_cache'] = 1;
				$streams_info[$i]['drm'] = 0;
				$streams_info[$i]['source_name'] = 'tvie-live-encoder';
			
				$tpl_uri[] = urldecode($this->input['uri_'.$i]);
				$tpl_name[] = urldecode($this->input['name_'.$i]);
				$tpl_name_index[urldecode($this->input['name_'.$i])] = urldecode($this->input['name_'.$i]);
			}
		}

		if (empty($streams_info))
		{
			$this->errorOutput('数据不完整，请重新填写！');
		}
		
		//新增信号
		$add_diff = @array_diff($tpl_name, $input_name);
		//删除信号
		$del_diff = @array_diff($input_name, $tpl_name);
		$del_diff_index = @array_diff($input_name_index, $tpl_name_index);
		//更新信号
		$update_inter = @array_intersect($tpl_name, $input_name);
		
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$up_tvie = new TVie_api($this->settings['tvie']['up_stream_server']);
		
			$ret_channel = $up_tvie->update_channel(
	    								$s_name,
	    								$save_time ,
	    								$live_delay,
	    								$ch_id
									);

			if (!$ret_channel['message'] == 'Updated')
			{
				$this->errorOutput('媒体服务器更新失败');
			}
			
			foreach ($streams_info AS $k => $v)
			{
				if ($add_diff[$k] == $v['name'])
				{
					//添加信号流
					if (is_array($v['backstore']))
					{
						$backstore = implode(',', $v['backstore']);
					}
					else 
					{
						$backstore = $v['backstore'];
					}
					
					$ret_stream = $up_tvie->create_channel_stream(
												$v['name'],
			    								$v['recover_cache'],
			    								$v['source_name'],
			    								$v['uri'],
			    								$v['drm'],
			    								'flv',
			    								$v['wait_relay'],
			    								$v['audio_only'],
			    								$v['bitrate'],
			    								$ch_id
		    								);

		    		if (!$ret_stream['stream_id'])
		    		{
		    			$this->errorOutput('媒体服务器添加信号流失败');
		    		}
		    		
		    		$other_info[$k] = array(
									'id' => $ret_stream['stream_id'],
									'name' =>  $v['name'],
									'ch_name' =>  $v['ch_name'],
									'uri' => $v['uri'],
									'recover_cache' =>  $v['recover_cache'],
									'source_name' =>  $v['source_name'],
									'drm' =>  0,
									'backstore' =>  $v['backstore'],
									'wait_relay' =>  $v['wait_relay'],
									'audio_only' =>  $v['audio_only'],
									'bitrate' =>  $v['bitrate']
								);
					
					if($ret_stream['stream_id'])
					{
						if($s_status)
						{
							$up_tvie->stop_stream($ret_stream['stream_id']);
							$up_tvie->start_stream($ret_stream['stream_id']);
						}
						else 
						{
							$up_tvie->start_stream($ret_stream['stream_id']);
							$up_tvie->stop_stream($ret_stream['stream_id']);
						}
					}
					
				}
				else
				{
					//更新信号流
					if (is_array($v['backstore']))
					{
						$backstore = implode(',', $v['backstore']);
					}
					else 
					{
						$backstore = $v['backstore'];
					}
				
					$ret_stream = $up_tvie->update_channel_stream(
															$v['id'],
						    								0,
						    								'flv',
						    								1,
						    								$v['uri'],
						    								$v['audio_only'],
						    								$v['wait_relay'],
						    								$v['source_name']
														);
														
					if ($ret_stream['message'] != 'Modified')
					{
						$this->errorOutput('媒体服务器更新信号流失败');
					}
					
					if ($ret_stream['message'] == 'Modified')
					{
						$other_info[$k] = array(
							'id' => $v['id'],
							'name' => $v['name'],
							'ch_name' => $v['ch_name'],
							'uri' => $v['uri'],
							'recover_cache' =>  $v['recover_cache'],
							'source_name' =>  $v['source_name'],
							'drm' =>  $v['drm'],
							'backstore' =>  $v['backstore'],
							'wait_relay' =>  $v['wait_relay'],
							'audio_only' =>  $v['audio_only'],
							'bitrate' =>  $v['bitrate']
						);
					}
					else
					{
						if ($update_inter[$k] == $v['name'])
						{
							$other_info[$k] = array(
								'id' => $input_info_index[$v['name']]['id'],
								'name' => $input_info_index[$v['name']]['name'],
								'ch_name' => $input_info_index[$v['name']]['ch_name'],
								'uri' => $input_info_index[$v['name']]['uri'],
								'recover_cache' =>  $input_info_index[$v['name']]['recover_cache'],
								'source_name' =>  $input_info_index[$v['name']]['source_name'],
								'drm' =>  $input_info_index[$v['name']]['drm'],
								'backstore' =>  $input_info_index[$v['name']]['backstore'],
								'wait_relay' =>  $input_info_index[$v['name']]['wait_relay'],
								'audio_only' =>  $input_info_index[$v['name']]['audio_only'],
								'bitrate' =>  $input_info_index[$v['name']]['bitrate']
							);
						}
					}
				}
			}
			
			//删除输入输出流
			if (!empty($input_info))
			{
				foreach ($input_info AS $k=>$v)
				{
					if ($del_diff[$k] == $v['name'])
					{
						$ret_del = $up_tvie->delete_stream($v['id']);			//删除流信息
					}
				}
			}
		}
		
		$info = array(
			's_name' => $s_name,
			'uri' => serialize($tpl_uri),
			's_status' => $s_status,
			'other_info' => serialize($other_info),
			'update_time' => TIMENOW
		);
		
		$sql = "UPDATE " . DB_PREFIX . "stream SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " WHERE id=" . $id; 
		$this->db->query($sql);
		//同步频道音频设置
		if($this->input['audio_temp'])
		{
			if($this->input['audio_only'])
			{
				$channelSql = "UPDATE " . DB_PREFIX . "channel SET audio_only=1 WHERE stream_id IN(" . $id . ")";
				$this->db->query($channelSql);
			}
			else 
			{
				$channelSql = "UPDATE " . DB_PREFIX . "channel SET audio_only=0 WHERE stream_id IN(" . $id . ")";
				$this->db->query($channelSql);
			}
		}
		
		$info['id'] = $id;
		
		//start
		//获取频道相关信息
		$channelStreamInfo = $this->getChannelStreamInfo($stream_id);
	
		if (!empty($channelStreamInfo))
		{
			$out_tvie = new TVie_api($this->settings['tvie']['stream_server']);
			
			foreach ($channelStreamInfo AS $k=>$channel)
			{
				//channel_stream
				if (!empty($channel['channel_stream']))
				{
					foreach ($channel['channel_stream'] AS $kk=>$vv)
					{
						if ($del_diff_index[$kk] == $kk)
						{
							//延时层
							$ret_delay = $up_tvie->delete_stream($vv['delay_stream_id']);
							
							//切播层
							if($ret_delay['message'] == 'Handled')
							{
								$ret_chg = $up_tvie->delete_stream($vv['chg_stream_id']);
							}
							
							//输出层
							if($ret_chg['message'] == 'Handled')
							{
								$ret_out = $out_tvie->delete_stream($vv['out_stream_id']);
							}
							
							//本地
							if ($ret_out['message'] == 'Handled')
							{
								$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE id = " . $vv['id'];
								$this->db->query($sql);
							}
						}
					}
				}
				//channel

				$channel_id[$k] = '';
				if ($channel['stream_info_all_index'])
				{
					foreach ($channel['stream_info_all_index'] AS $kk=>$vv)
					{
						if ($del_diff_index[$kk] == $kk)
						{
							$channel_id[$k] = $channel['id'];
							unset($channel['stream_info_all_index'][$kk]);
						}

						if ($del_diff_index[$kk] == $channel['main_stream_name'])
						{
							$main_stream_name_flag = 1;
						}
					}
					
					if (!empty($channel['stream_info_all_index']))
					{
						$stream_info_all = array();
						foreach ($channel['stream_info_all_index'] AS $v)
						{
							$stream_info_all[] = $v;
						}
					}

					if ($main_stream_name_flag)
					{
						$main_stream_name = '';
					}
					else
					{
						$main_stream_name = $channel['main_stream_name'];
					}
				}

				if ($channel_id[$k])
				{
					$sql = "UPDATE " . DB_PREFIX . "channel SET stream_info_all = '".serialize($stream_info_all)."', main_stream_name = '".$main_stream_name."' WHERE id = " . $channel_id[$k];
					$this->db->query($sql);
				}
			}
		}
		//end
		
		$this->setXmlNode('stream','info');
		$this->addItem($info['id']);
		$this->output();
	}
	
	/**
	 * 获取信号被频道所占用信息
	 * Enter description here ...
	 * @param unknown_type $stream_id
	 */
	public function getChannelStreamInfo($stream_id)
	{
		if (!$stream_id)
		{
			return false;
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "channel WHERE stream_id IN (" . $stream_id . ") ORDER BY id ASC";
		$q = $this->db->query($sql);

		$channel = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['stream_info_all'] = unserialize($row['stream_info_all']);
			
			$row['stream_info_all_index'] = array();
			if (!empty($row['stream_info_all']))
			{
				foreach ($row['stream_info_all'] AS $v)
				{
					$row['stream_info_all_index'][$v] = $v;
				}
			}

			$row['beibo'] = unserialize($row['beibo']);

			$channel[$row['id']] = $row;
		}
		
		$channel_ids = @array_keys($channel);

		if (!empty($channel))
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . implode(',', $channel_ids) . ") ORDER BY id ASC";
			$q = $this->db->query($sql);

			$channel_stream = array();
			while ($row = $this->db->fetch_array($q))
			{
				$channel_stream[$row['channel_id']]['channel_stream'][$row['stream_name']] = $row;
			}
			
			$info = array();
			foreach ($channel AS $k=>$v)
			{
				if ($channel_stream[$k])
				{
					$info[$k] = @array_merge($channel[$k], $channel_stream[$k]);
				}
				else
				{
					$info[$k] = $channel[$k];
				}
			}
		}

		if (!empty($info))
		{
			return $info;
		}

		return false;
	}
	
	/**
	 * 删除信号流数据
	 * @name delete
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道信号ID
	 * @return $ret array 频道信号ID
	 */
	function delete()
	{
		$id = $stream_id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('该信号不存在或已被删除');
		}
		
		$sql = "SELECT id,ch_id,other_info FROM " . DB_PREFIX . "stream WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		
		$stream = array();
		while($row = $this->db->fetch_array($q))
		{
			$ch_id_all[] = $row['ch_id'];
			$row['other_info'] = unserialize($row['other_info']);
			$stream[$row['id']] = $row;
		}
	
		if (empty($stream))
		{
			$this->errorOutput('该信号不存在或已被删除');
		}

		if($this->settings['tvie']['open'])
		{	
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$up_tvie = new TVie_api($this->settings['tvie']['up_stream_server']);
			$out_tvie = new TVie_api($this->settings['tvie']['stream_server']);
			
			foreach ($stream AS $k=>$v)
			{
				if (!$v['ch_id'])
				{
					$this->errorOutput('媒体服务器频道id不存在');
				}
				
				//删除频道
				$ret_channel = $up_tvie->delete_channel($v['ch_id']);
				
				if ($ret_channel['message'] != 'Handled')
				{
					$this->errorOutput('媒体服务器频道删除失败');
				}
			}
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "stream WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		
		$ret['id'] = $id;
		//start
	
		$channelStreamInfo = $this->getChannelStreamInfo($stream_id);

		if (!empty($channelStreamInfo))
		{
			foreach ($channelStreamInfo AS $k=>$channel)
			{
				//channel_stream
				if (!empty($channel['channel_stream']))
				{
					foreach ($channel['channel_stream'] AS $kk=>$vv)
					{
						//延时层
						$ret_delay = $up_tvie->delete_stream($vv['delay_stream_id']);
						
						//切播层
						if($ret_delay['message'] == 'Handled')
						{
							$ret_chg = $up_tvie->delete_stream($vv['chg_stream_id']);
						}
						
						//输出层
						if($ret_chg['message'] == 'Handled')
						{
							$ret_out = $out_tvie->delete_stream($vv['out_stream_id']);
						}
						//暂时这样处理
						if ($ret_out['result'])
						{
							$sql = "DELETE FROM " . DB_PREFIX . "channel_stream WHERE id = " . $vv['id'];
							$this->db->query($sql);
						}
					}
				}

				//channel 暂时这样处理
				if ($ret_out['result'])
				{
					$sql = "UPDATE " . DB_PREFIX . "channel SET stream_id=0,is_live=0, stream_state=0, uri_in_num=0, uri_out_num=0, level=0, stream_info_all='', stream_display_name='', stream_mark='', main_stream_name=0, beibo='' WHERE id IN(" . $channel['id'] . ")";

				//	$sql = "UPDATE " . DB_PREFIX . "channel SET stream_info_all = '', stream_id='', stream_display_name = '', stream_mark = '', main_stream_name = '', beibo = '' WHERE id IN (" . $channel['id'] . ")";
					$this->db->query($sql);
				}
			}
		}
		//end
		$this->setXmlNode('channel','info');
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 删除信号时，检测此信号是否已被频道所用
	 * @name goBackTip
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道信号ID
	 * @return $names string 所涉及频道名称 (0-表示没有涉及频道)
	 */
	public function goBackTip()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('此信号不存在');
		}
		$sql = "SELECT * FROM ".DB_PREFIX."channel WHERE stream_id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$return = array();
		while($row = $this->db->fetch_array($q))
		{
			$return[$row['id']]['id'] = $row['id'];
			$return[$row['id']]['name'] = $row['name'];
		}
		
		$info = array();
		foreach ($return AS $v)
		{
			$info[] = $v['name'];
		}
		
		$names = implode(',',$info);
		if($return)
		{
			$this->addItem($names);
		}
		else 
		{
			$this->addItem(0);
		}
		
		$this->output();
	}
		
	/**
	 * 信号流状态 (1-启动 0-停止)
	 * @name stream_status
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 频道ID
	 * @return $tip array (stream_status：1-->启动, 2-->停止, 0-->操作失败)
	 * @include tvie_api.php
	 */
	public function stream_status()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('未传入频道信号ID');
		}
		$sql = "SELECT ch_id,s_status FROM " . DB_PREFIX . "stream WHERE id=" . $id;
		$ret = $this->db->query_first($sql);
		$ch_id = $ret['ch_id'];
		$status = $ret['s_status'];
		
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$tvie_api = new TVie_api($this->settings['tvie']['up_stream_server']);
			$channel_info = $tvie_api->get_channel_by_id($ch_id);				//查询的频道信息
			$stream_info = $channel_info['channel']['streams'];
		}
		
		$new_status = "";
		
		if(!$status)
		{
			$sql = "UPDATE " . DB_PREFIX . "stream SET s_status = 1 WHERE id=" . $id;
			if($stream_info)
			{
				foreach($stream_info as $key => $value)
				{
					$ret_stream = $tvie_api->start_stream($value['id']);
				}
				if($ret_stream['message'] == 'Handled')
				{
					$new_status = 1;
				}
				else 
				{
					$new_status = 0;
				}
			}
		}
		else 
		{
			$sql = "UPDATE " . DB_PREFIX . "stream SET s_status = 0 WHERE id=" . $id;
			if($stream_info)
			{
				foreach($stream_info as $key => $value)
				{
					$ret_stream = $tvie_api->stop_stream($value['id']);
				}
				if($ret_stream['message'] == 'Handled')
				{
					$new_status = 2;
				}
				else 
				{
					$new_status = 0;
				}
			}
		}
		if($this->db->query($sql))
		{
			$tip = array('stream_status'=>$new_status);
			$this->addItem($tip);
			$this->output();
		}
		else
		{
			$this->errorOutput();
		}
	}
	
}
$out = new streamUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();
?>