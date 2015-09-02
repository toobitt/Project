<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function create
*
* $Id: stream_mms_update.php 8253 2012-07-23 07:56:55Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','stream');
require('global.php');
class streamUpdateApi extends adminUpdateBase
{
	private $mStream;
	private $mServerConfig;
	function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/stream.class.php';
		$this->mStream = new stream();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	function sort()
	{
		
	}
	function publish()
	{
		
	}
	function audit()
	{
		
	}
	function create()
	{
	/*
		if (!$this->input['s_name'])
		{
			$this->errorOutput('信号名称不能为空');
		}
	*/
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		
		$ch_name = trim($this->input['ch_name']);
		if (!$ch_name)
		{
			$this->errorOutput('信号标识不能为空');
		}
		
		if (!hg_check_string($ch_name))
		{
			$this->errorOutput('信号标识只包含字母数字下划线');
		}
		
		//是否是文件形成的流 1-是 0-否
		$type = intval($this->input['type']);
		$stream_count = count($this->input['counts']);
		
		//备播文件快速添加文件流
		if ($type == 1 && $this->input['flag'] == 'fastAddStream')
		{
			$tmp_flag = 0;
			$_server_id = $this->input['server_id_0'];
			$tmp_server_id = $_server_id[0];
			for ($j = 0; $j < count($this->input['source_name_0']); $j ++)
			{
				if ($tmp_server_id != $_server_id[$j])
				{
					$tmp_flag = 1;
				}
			}

			if ($tmp_flag)
			{
				$this->errorOutput('请选择同一台服务器的备播文件');
			}
			$this->input['server_id'] = $tmp_server_id;
		}

		if (!$this->mStream->check_streamChName($ch_name))
		{
			$this->errorOutput('['.$ch_name.'] 信号已存在！');
		}
		
		//服务器信息
		$server_id = intval($this->input['server_id']);
		if ($server_id)
		{
			$server_info = $this->mServerConfig->get_server_info($server_id);
			
			if ($server_info == -1)
			{
				$this->errorOutput('该服务器信息不存在或已被删除');
			}
			else if ($server_info == -2)
			{
				$this->errorOutput('该服务器已经无法再添加信号，请选择其他服务器');
			}
			
			if ($stream_count > $server_info['over_count'])
			{
				$offset_count = $stream_count - $server_info['over_count'];
				
				$this->errorOutput('已经超过该服务器信号的最大数目 [ ' . $offset_count . ' ] 条,请选择其他服务器！');
			}
		}
		
		if(is_array($this->input['counts']) && $this->input['counts'])
		{
			$streams_info = $tpl_name = $uri_arr = $source_name = array();

			for($i = 0;$i< $stream_count;$i++)
			{
				$streams_info[$i]['name'] = trim($this->input['name_'.$i]);
				$streams_info[$i]['uri'] = trim($this->input['uri_'.$i]);
			
				if (!trim($this->input['name_'.$i]))
				{
					$this->errorOutput('输出标识不能为空');
				}
			
				if (!hg_check_string(trim($this->input['name_'.$i])))
				{
					$this->errorOutput('输出标识只包含字母数字下划线');
				}
				
				if (trim($this->input['uri_'.$i]))
				{
					$type = 0;
					unset($this->input['source_name_'.$i]);
					unset($this->input['backup_title_'.$i]);
				}
				
				if (!trim($this->input['uri_'.$i]) && !isset($this->input['source_name_'.$i]) && !$type)
				{
					$this->errorOutput('来源地址不能为空');
				}
				
				//文件流名称
				$streams_info[$i]['source_name'] = $this->input['source_name_'.$i] ? $this->input['source_name_'.$i] : '';
				
				//备播文件名称
				$streams_info[$i]['backup_title'] = $this->input['backup_title_'.$i] ? $this->input['backup_title_'.$i] : '';
				
				$streams_info[$i]['bitrate'] = $this->input['bitrate_'.$i];
			//	$streams_info[$i]['backstore'][] = $this->input['flv_'.$i];
			//	$streams_info[$i]['backstore'][] = $this->input['ts_'.$i];
				$streams_info[$i]['audio_only'] = intval($this->input['audio_only']);
				$streams_info[$i]['wait_relay'] = intval($this->input['wait_relay']);
				
				$uri_arr[] = trim($this->input['uri_'.$i]);
				
				if ($type && !$this->input['source_name_'.$i])
				{
					$this->errorOutput('请为输出标识 ['.trim($this->input['name_'.$i]).'] 选择备播文件');
				}
				
				if ($this->input['source_name_'.$i])
				{
					$source_name[] = $this->input['source_name_'.$i];
				}
				
				$tpl_name[$i] = $streams_info[$i]['name'];
			}
		}
		
		if ($server_id && !empty($streams_info[0]['source_name']))
		{
			$backup_id = implode(',', @array_unique($streams_info[0]['source_name']));
			$ret_backup = $this->mStream->checked_backup_by_server_id($backup_id, $server_id, ' id ');
			if (empty($ret_backup))
			{
				$this->errorOutput('该服务器下暂无备播文件');
			}
		}

		$tpl_name_counts = @array_count_values($tpl_name);
		if (!empty($tpl_name_counts))
		{
			foreach ($tpl_name_counts AS $v)
			{
				if ($v > 1)
				{
					$this->errorOutput('输出标识不能重复');
				}
			}
		}
		
		$info = $this->mStream->create($ch_name, $streams_info, $uri_arr, $type, $stream_count, $server_info['server_info']);
		
		switch ($info)
		{
			case -55 :
				$this->errorOutput('媒体服务器未启动');
				break;
			case -17 :
				$this->errorOutput('媒体服务器数据添加失败');
				break;
			case 0 :
				$this->errorOutput('创建失败');
				break;
			default :
				$this->addItem($info);
				break;
		}
		$this->output();
	}
	
	function update()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$field  = 'id, s_name, ch_name, uri, s_status, other_info, type, stream_count';
		$stream = $this->mStream->get_stream_by_id($id, $field);
		if (empty($stream))
		{
			$this->errorOutput('该条信号不存在或已被删除');
		}
		$stream_count = count($this->input['counts']);
		//服务器信息
		$server_id = intval($this->input['server_id']);
		if ($server_id)
		{
			$self_count	  = $stream['stream_count'];
			
			$server_info  = $this->mServerConfig->get_server_info($server_id, $id);
			
			if ($server_info == -1)
			{
				$this->errorOutput('该服务器信息不存在或已被删除');
			}
			else if ($server_info == -2)
			{
				$this->errorOutput('该服务器已经无法再添加信号，请选择其他服务器');
			}
		
			if ($stream_count > $server_info['over_count'] && $server_info != 1)
			{
				$offset_count = $stream_count - $server_info['over_count'];
				
				$this->errorOutput('已经超过该服务器信号的最大数目 [ ' . $offset_count . ' ] 条,请选择其他服务器！');
			}
		}
		
		//是否是文件形成的流 1-是 0-否
		$type = intval($this->input['type']);

		if(is_array($this->input['counts']) && $this->input['counts'])
		{
			$streams_info = $tpl_uri = $tpl_name = $tpl_name_index = $source_name = array();

			for($i = 0;$i< $stream_count;$i++)
			{
				if (!$type)
				{
					unset($this->input['source_name_'.$i]);
					unset($this->input['backup_title_'.$i]);
				}
				else 
				{
					unset($this->input['uri_'.$i]);
				}
				
				$streams_info[$i]['id'] = trim($this->input['id_'.$i]);
				$streams_info[$i]['name'] = trim($this->input['name_'.$i]);
			
				if (!hg_check_string(trim($this->input['name_'.$i])))
				{
					$this->errorOutput('输出标识只包含字母数字下划线');
				}
				
				$streams_info[$i]['ch_name'] = trim($this->input['ch_name_hidden']);
				$streams_info[$i]['uri'] = trim($this->input['uri_'.$i]);
				
				if (!trim($this->input['uri_'.$i]) && !isset($this->input['source_name_'.$i]) && !$type)
				{
					$this->errorOutput('来源地址不能为空');
				}
				
				$streams_info[$i]['bitrate'] = $this->input['bitrate_'.$i];
			
				$streams_info[$i]['backstore'] = '';
			
				$streams_info[$i]['audio_only'] = intval($this->input['audio_only']);
				$streams_info[$i]['wait_relay'] = intval($this->input['wait_relay']);
				$streams_info[$i]['recover_cache'] = '';
				$streams_info[$i]['drm'] = '';
				$streams_info[$i]['source_name'] = $this->input['source_name_'.$i] ? $this->input['source_name_'.$i] : '';
				$streams_info[$i]['source_id'] = $this->input['source_id_'.$i] ? $this->input['source_id_'.$i] : '';
				$streams_info[$i]['chg_stream_id'] = $this->input['chg_stream_id_'.$i] ? $this->input['chg_stream_id_'.$i] : '';
				$streams_info[$i]['backup_title'] = $this->input['backup_title_'.$i] ? $this->input['backup_title_'.$i] : '';
				$tpl_uri[] = trim($this->input['uri_'.$i]);
				$tpl_name[] = trim($this->input['name_'.$i]);
				$tpl_name_index[trim($this->input['name_'.$i])] = trim($this->input['name_'.$i]);
				
				if ($type && !$this->input['source_name_'.$i])
				{
					$this->errorOutput('请为输出标识 ['.trim($this->input['name_'.$i]).'] 选择备播文件');
				}
				
				if ($this->input['source_name_'.$i])
				{
					$source_name[] = $this->input['source_name_'.$i];
				}
			}
		}

		$tpl_name_counts = @array_count_values($tpl_name);
		if (!empty($tpl_name_counts))
		{
			foreach ($tpl_name_counts AS $v)
			{
				if ($v > 1)
				{
					$this->errorOutput('输出标识不能重复');
				}
			}
		}
		
		$info = $this->mStream->update($id, $streams_info, $tpl_uri, $tpl_name, $tpl_name_index, $source_name, $type, $stream_count, $server_info['server_info'], $stream);
		
		switch ($info)
		{
			case -55 :
				$this->errorOutput('媒体服务器未启动');
				break;
			case -17 :
				$this->errorOutput('媒体服务器数据添加失败');
				break;
			case 0 :
				$this->errorOutput('创建失败');
				break;
			default :
				break;
		}
		$this->addItem($info);
		$this->output();
	}

	function delete()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$info = $this->mStream->delete($id);
		
		if (!$info)
		{
			$this->errorOutput('删除失败');
		}
		
		$this->addItem($id);
		$this->output();
	}

	function check_channel()
	{
		$stream_id = trim($this->input['id']);
		if (!$stream_id)
		{
			$this->errorOutput('未传入ID');
		}

		$channel = $this->mStream->check_channel($stream_id);

		if (!empty($channel))
		{
			$channel_name = array();
			foreach ($channel AS $v)
			{
				$channel_name[] = $v['name'];
			}	
		}

		if (!$channel_name)
		{
			$channel_name = -10;
		}
		echo json_encode($channel_name);
	}
	
	function streamStatus()
	{
		if($this->mNeedCheckIn && !$this->prms['manage'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
		
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入信号ID');
		}
		
		$server_id = intval($this->input['server_id']);
		if ($server_id)
		{
			$server_info = $this->mServerConfig->get_server_config_by_id($server_id);
		}
		
		$info = $this->mStream->streamStatus($id, $server_info);
		$this->addItem($info);
		$this->output();
	}

	function getBitrate()
	{
		$uri = trim($this->input['uri']);
		$stream_id = intval($this->input['stream_id']);
		$info = $this->mStream->getBitrate($uri, $stream_id);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 检测备播信号是否正常 (已启动的直播信号)
	 * Enter description here ...
	 */
	function getIsPlay()
	{
		$info = $this->mStream->getIsPlay();
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 服务器信息及剩余流信号条数
	 * Enter description here ...
	 */
	public function checked_server_stream_count()
	{
		$server_id = intval($this->input['id']);
		if (!$server_id)
		{
			$this->errorOutput('未传入服务器id');
		}
		$server_info = $this->mServerConfig->get_server_info($server_id);
		
		switch ($info)
		{
			case -1 :
				$this->errorOutput('该服务器信息不存在或已被删除');
				break;
			case -2 :
				$this->errorOutput('该服务器已经无法再添加信号，请选择其他服务器');
				break;
			default :
				break;
		}
		
		$ret = intval($server_info['over_count']);
		
		$this->addItem($ret);
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput('未传入的空方法');
	}
}
$out = new streamUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>