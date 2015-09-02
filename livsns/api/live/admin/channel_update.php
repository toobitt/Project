<?php
/***************************************************************************
* $Id: channel_update.php 39658 2014-08-21 07:23:24Z zhuld $
* export_var(get_filename()."_1111.txt",$ret,__LINE__,__FILE__);
***************************************************************************/
define('MOD_UNIQUEID','live');
require('global.php');
class channelUpdateApi extends adminUpdateBase
{
	private $mLivemms;
	private $mChannel;
	private $mServerConfig;
	private $mMaterial;
	/*
	private $mPublishColumn;
	private $mTvie;
	private $mApiToken;
	*/
	function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
		
		require_once CUR_CONF_PATH . 'lib/channel.class.php';
		$this->mChannel = new channel();
		
		require_once CUR_CONF_PATH . 'lib/server_config.class.php';
		$this->mServerConfig = new serverConfig();
		
		require_once ROOT_PATH . 'lib/class/material.class.php';
		$this->mMaterial = new material();
		/*
		require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->mPublishColumn = new publishconfig();
		
		require_once CUR_CONF_PATH . 'lib/tvie.class.php';
		$this->mTvie = new tvie();
		$this->mApiToken = '';
		*/
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'channel_node WHERE id IN('.$this->input['node_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$nodes['column_id'] = $this->input['column_id'];
		$nodes['published_column_id'] = '';
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		$name 				= trim($this->input['name']);
		$code 				= trim($this->input['code']);
		$server_id 			= intval($this->input['server_id']);
		//$main_stream_name 	= $this->input['main_stream_name'];
		$stream_name 		= $this->input['stream_name'];
		$url 	 			= $this->input['url'];
		$bitrate 			= $this->input['bitrate'];
		$is_control			= intval($this->input['is_control']);
		//$is_mobile_phone	= intval($this->input['is_mobile_phone']);
		$is_audio			= intval($this->input['is_audio']);
		$is_push			= intval($this->input['is_push']);
		//$drm				= intval($this->input['drm']);
		$time_shift			= intval($this->input['time_shift']);
		//$delay				= intval($this->input['delay']);
		//$record_time_diff	= intval($this->input['record_time_diff']);
		$is_default	= intval($this->input['is_default']);
		//$mobile_default	= intval($this->input['mobile_default']);
		//$column_id 			= trim($this->input['column_id']);
		
		$_appid				= $this->input['_appid'];
		$_appname			= $this->input['_appname'];
		
		if (!$name)
		{
			$this->errorOutput('频道名称不能为空');
		}
		
		if (!$code)
		{
			$this->errorOutput('台号不能为空');
		}
	
		if (!hg_check_string($code))
		{
			$this->errorOutput('台号只能包含字母数字');
		}
		
		$ret_code = $this->mChannel->check_channel_code($code);
		
		if (!empty($ret_code))
		{
			$this->errorOutput('['.$code.'] 台号已存在！');
		}
		
		//录制时间
		/*
		if ($time_shift > $this->settings['max_time_shift'])
		{
			$time_shift = $this->settings['max_time_shift'];
		}
		
		//延时时间
		if ($delay > $this->settings['max_delay'])
		{
			$delay = $this->settings['max_delay'];
		}
		*/
		$channel_stream = array();
		$core_count = count($stream_name);
		for ($i = 0; $i < $core_count; $i ++)
		{
			if (!trim($stream_name[$i]))
			{
				$this->errorOutput('输出标识不能为空');
			}

			if ($i > 0 && $stream_name[0] == $stream_name[$i])
			{
				$this->errorOutput('输出标识不能重复');
			}
			if (!hg_check_string($stream_name[$i]))
			{
				$this->errorOutput('输出标识只能包含字母数字');
			}
			
			if (!trim($url[$i]))
			{
				$this->errorOutput('信号流地址不能为空');
			}
			if (!intval($bitrate[$i]))
			{
				$this->errorOutput('码流不可以为空');
			}
			$channel_stream[$i] = array(
				'stream_name'	=> $stream_name[$i],
				'url'			=> $url[$i],
				//'input_id'		=> $ret_input_id[$i],
				//'delay_id'		=> $ret_delay_id[$i],
				//'change_id'		=> $ret_change_id[$i],
				//'output_id'		=> $ret_output_id[$i],
				'bitrate'		=> $bitrate[$i],
				'is_main'		=> ($is_default == $i) ? 1 : 0,
				'order_id'		=> $i,
			);			
		}
		
		//$main_stream_name = $main_stream_name ? $main_stream_name : $stream_name[0];
		$main_stream_name = $stream_name[$is_default];
		$main_stream_url = $url[$is_default];
		$stream_count = $core_count;
		
		$level = 1;
		/*
		$level = 1;
		
		if ($delay)
		{
			$stream_count = $stream_count + $core_count;
			$level = $level + 1;
		}
		
		if ($is_control)
		{
			$stream_count = $stream_count + $core_count;
			$level = $level + 1;
		}
		
		if ($delay || $is_control)
		{
			$stream_count = $stream_count + $core_count;
			$level = $level + 1;
		}
		*/
		$server_info = $this->mServerConfig->get_server_config_by_id($server_id);
	
		if (empty($server_info))
		{
			$this->errorOutput('该直播服务器不存在或已被删除');
		}
		
		$type 		 = $server_info['type'] ? $server_info['type'] : 'wowza';
		
		
		//$super_token = $server_info['super_token'];
		
		//服务器状态
		if (!$server_info['status'])
		{
			$this->errorOutput('该服务器未审核');
		}
		
		//服务器最大信号数目
		$counts 	   = $server_info['counts'] ? $server_info['counts'] : $this->settings['wowza']['counts'];
		//已用信号数目
		//$_stream_count = $this->mChannel->get_stream_count($server_id, $id);
		$_stream_count = $this->mChannel->get_stream_count($server_id);
		
		//剩余信号数目
		$over_count    = $counts - ($_stream_count + $stream_count);
	
		if ($over_count < 0)
		{
			$this->errorOutput('信号数目已超过该服务器数目限制, 已超出 ' . abs($over_count) . ' 条');
		}
		
		
		/*
		$server_info = $this->mChannel->get_server_info($server_info);
		
		
		
		$host			= $server_info['host'];
		$input_dir  	= $server_info['input_dir'];
		$output_dir 	= $server_info['output_dir'];
		$wowzaip_input 	= $server_info['wowzaip_input'];
		
		$live_host		= $server_info['live_host'];
		$record_host	= $server_info['record_host'];
		
		$is_live 	= $live_host ? 1 : 0;
		$is_record 	= $record_host ? 1 : 0;
		
		
		//检测流媒体服务器是否正常
		$function = 'media_server_' . $type;
		//如果type是nginx则返回true
		$ret_media_server = $this->$function($server_info, $super_token);
		
		
		if (!$ret_media_server['core_server'])
		{
			$this->errorOutput($type . '主控服务器未启动');
		}
		
		if ($live_host && !$ret_media_server['live_server'])
		{
			$this->errorOutput($type . '直播服务器未启动');
		}
		
		if ($record_host && !$ret_media_server['record_server'])
		{
			$this->errorOutput($type . '录制服务器未启动');
		}
		
		if ($type == 'wowza')
		{
			$applicationType = 1;
			if ($is_mobile_phone)
			{
				$applicationType = 3;
			}
			
			$application_data = array(
				'action'	=> 'insert',
				'id'		=> 0,
				'name'		=> $code,
				'length'	=> $time_shift * 3600,
				'drm'		=> $drm,
				'type'		=> $applicationType,
			);
			
			$ret_application = $this->mLivemms->outputApplicationOperate($host, $output_dir, $application_data);

			
			
			if (!$ret_application['result'])
			{
				$this->errorOutput('主控输出层应用创建失败');
			}
			
			$applicationId = intval($ret_application['application']['id']);
			
			//直播
			if ($live_host)
			{
				$application_data['id'] 	= $applicationId;
				$application_data['length'] = 0;
				$ret_application = $this->mLivemms->outputApplicationOperate($live_host, $output_dir, $application_data);
				
				if (!$ret_application['result'])
				{
					$this->errorOutput('直播输出层应用创建失败');
				}
			}
			
			//录制
			if ($record_host)
			{
				$application_data['id'] 	= $applicationId;
				$application_data['length'] = 0;
				$ret_application = $this->mLivemms->outputApplicationOperate($record_host, $output_dir, $application_data);
				
				if (!$ret_application['result'])
				{
					$this->errorOutput('录制输出层应用创建失败');
				}
			}
		}
		
		$ret_input_id = $ret_delay_id = $ret_change_id = $ret_output_id = $channel_stream = array();
		$ret_live_output_id = $ret_record_output_id = array();
		
		
		$main_stream_url = '';
		foreach ($stream_name AS $k => $v)
		{
			$_channel_stream = array(
				'url'				=> $url[$k],
				'delay'				=> $delay,
				'is_control'		=> $is_control,
				'is_push'			=> $is_push,
				'applicationId'		=> $applicationId,
				'stream_name'		=> $v,
				'code'				=> $code,
				'time_shift'		=> $time_shift,
			);
			
			$function = 'set_stream_' . $type;
			$set_stream = $this->$function($server_info, $_channel_stream);
			
			$ret_input_id[$k]  = $set_stream['ret_input_id'];
			$ret_delay_id[$k]  = $set_stream['ret_delay_id'];
			$ret_change_id[$k] = $set_stream['ret_change_id'];
			$ret_output_id[$k] = $set_stream['ret_output_id'];
			
			$ret_live_output_id[$k]   = $set_stream['ret_live_output_id'];
			$ret_record_output_id[$k] = $set_stream['ret_record_output_id'];
			
			$channel_stream[$k] = array(
				'stream_name'	=> $v,
				'url'			=> $url[$k],
				'input_id'		=> $ret_input_id[$k],
				'delay_id'		=> $ret_delay_id[$k],
				'change_id'		=> $ret_change_id[$k],
				'output_id'		=> $ret_output_id[$k],
				'bitrate'		=> $bitrate[$k],
				'is_main'		=> ($main_stream_name == $v) ? 1 : 0,
				'order_id'		=> $k,
			);
			if($main_stream_name == $v)
			{
				$main_stream_url = $url[$k];
			}
		}
		
		//如果创建过程中有失败，则删除以创建好的
		$tmp_delete = 0;
		for ($j = 0; $j < $core_count; $j ++)
		{
			if (!$ret_output_id[$j])
			{
				$tmp_delete = 1;
			}
			
			//直播
			if ($live_host && !$ret_live_output_id[$j])
			{
				$tmp_delete = 1;
			}
			
			//录制
			if ($record_host && !$ret_record_output_id[$j])
			{
				$tmp_delete = 1;
			}
		}
		
		if ($tmp_delete)
		{
			for ($i = 0; $i < $core_count; $i ++)
			{
				$_channel_stream = $channel_stream[$i];
				$_channel_stream['code'] 	   	= $code;
				$_channel_stream['is_control'] 	= $is_control;
				$_channel_stream['delay'] 		= $delay;
				$_channel_stream['is_live'] 	= $is_live;
				$_channel_stream['is_record'] 	= $is_record;
				
				$function = 'stream_operate_' . $type;
				$this->$function($server_info, $_channel_stream, 'delete');
			}
			
			//删除应用
			if ($applicationId)
			{
				$application_data = array(
					'action'	=> 'delete',
					'id'		=> $applicationId,
				);
				$this->mLivemms->outputApplicationOperate($host, $output_dir, $application_data);
				
				//直播
				if ($live_host)
				{
					$this->mLivemms->outputApplicationOperate($live_host, $output_dir, $application_data);
				}
				
				//录制
				if ($record_host)
				{
					$this->mLivemms->outputApplicationOperate($live_host, $output_dir, $application_data);
				}
			}
			
			$this->errorOutput('流媒体服务器创建失败');
		}
		*/
		$data = array(
			'name'				=> $name,
			'code'				=> $code,
			'main_stream_name'	=> $main_stream_name,
			'stream_name'		=> serialize($stream_name),
			'stream_count'		=> $stream_count,
			//'level'				=> $level,
			'core_count'		=> $core_count,
			'application_id'	=> $applicationId,
			'time_shift'		=> $time_shift,
			//'delay'				=> $delay,
			'is_audio'			=> $is_audio,
			'is_push'			=> $is_push,
			//'drm'				=> $drm,
			'is_control'		=> $is_control,
			//'is_mobile_phone'	=> $is_mobile_phone,
			//'record_time_diff'	=> $record_time_diff,
			'server_id'			=> $server_id,
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'ip'				=> hg_getip(),
			//'is_live'			=> $live_host ? 1 : 0,
			//'is_record'			=> $record_host ? 1 : 0,
			'can_record'			=> 1,
			'record_uri'			=> $this->input['record_uri'],
		);
		
		//发布开始
		//$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		//$data['column_id'] = $data['column_id'] ? serialize($data['column_id']) : '';
		//发布结束
		
		//创建播控和串联单两层输出
		$from_control_stream = '';
		if($data['is_control'] && $this->settings['schedule_control_wowza']['is_wowza'])
		{
			if(count($channel_stream) > 1)
			{
				$this->errorOutput("多码流不支持播空和串联单");
			}
			$_schedule_control = array();
			$_schedule_control_data = array(
				'url'  => $main_stream_url,
				'type' => $is_push,
				'action'=>'insert',
			);
			if($this->settings['schedule_control_wowza']['is_wowza'] && !($_schedule_control = $this->create_schedule_control($_schedule_control_data)))
			{
				$this->errorOutput("创建播控层[或串联单层]失败");
			}
			if($_schedule_control)
			{
				$data['schedule_control'] = serialize($_schedule_control);
			}
			$from_control_stream = "rtmp://".str_replace(':8086', '', $this->settings['schedule_control_wowza']['host'])."/input/".$_schedule_control['control']['output_id'].".output";;
		}
		
		/******************** 检查TS目录存储 *************************/
		include(CUR_CONF_PATH . 'lib/' . $type . '.live.php');
		$server = new m2oLive();
		//查出所有使用该直播配置的信号流占用的存储
		$sql = "SELECT c_s.bitrate,c.time_shift FROM " .DB_PREFIX. "channel_stream c_s 
				LEFT JOIN " .DB_PREFIX. "channel c ON c_s.channel_id = c.id 
				WHERE c.server_id = " .$server_id;
		$q = $this->db->query($sql);
		$used_size = 0;
		while($row = $this->db->fetch_array($q))
		{
			$used_size += $row['bitrate']*$row['time_shift']*3600;
		}
		//新添加的信号流所需的存储
		$bitrate = 0;
		if(is_array($channel_stream))
		{
			foreach($channel_stream as $k => $v)
			{
				$bitrate += $v['bitrate'];
			}
		}
		//所需存储总数 (码流单位kbps, 存储单位b)
		$needsize = (3600*$time_shift*$bitrate+$used_size)*1024;
		//取主、备配置
		$sql = "SELECT host,input_dir,hls_path,fid FROM " .DB_PREFIX. "server_config WHERE id = " .$server_id. " OR fid = " . $server_id;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$row_b[] = $row;
			$init_data = array(
				'host'=>$row['host'],
				'dir' =>$row['input_dir'],
			);
			$server->init_env($init_data);
			if(!$server->check_ts_path(array('needsize'=>$needsize,'hls_path'=>$row['hls_path'])))
			{
				$this->errorOutput('主机'.$init_data['host'].'所设置的TS目录存储不足');
			}
		}
		/**************************************************************/
		
		//入本地库
		$ret = $this->mChannel->create($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput('添加失败');
		}
	
		/*************** 设置dvr表(按照n个频道共用一组表的机制为该频道自动选择dvr表) *********
		$sql = "SELECT COUNT(DISTINCT table_name) AS total FROM " .DB_PREFIX. "channel WHERE id != " .$ret['id'];
		$r = $this->db->query_first($sql);
		$table_count = $r['total']; //已有dvr表个数
		if($table_count == 1)
		{
			$table_name_tmp = DB_PREFIX . 'dvr';
		}
		else
		{
			$table_name_tmp = DB_PREFIX . 'dvr' . $table_count;
		}
		$sql2 = "SELECT COUNT(*) AS total2 FROM " .DB_PREFIX. "channel WHERE table_name = '" .$table_name_tmp. "'";
		$r2 = $this->db->query_first($sql2);
		$channel_num = $r2['total2'];
		if($channel_num < $this->settings['channel_table_limit'])
		{
			$table_name = $table_name_tmp;
		}
		else
		{
			//如果使用该表的频道数超过限制,就按照表名+1的规则新建一张表
			$table_count += 1;
			$table_name = DB_PREFIX . 'dvr' . $table_count;
			$sql = "CREATE TABLE " .$table_name. " LIKE " .DB_PREFIX. "dvr";
			$sql2 = "CREATE TABLE " .$table_name. "_1 LIKE " .DB_PREFIX. "dvr";
			$this->db->query($sql);
			$this->db->query($sql2); //创建直播时移备份表
		}
		//更新表名字段
		$sql = "UPDATE " .DB_PREFIX. "channel SET table_name = '" .$table_name. "' WHERE id = " .$ret['id'];
		$this->db->query($sql);
		***************************************************************************/
		/*************** 设置dvr表(按照n个频道共用一组表的机制为该频道自动选择dvr表) *********/
		//查出目前每个表所占用的频道数
		$sql = "SELECT table_name, count(*) AS total FROM " .DB_PREFIX. "channel WHERE 1 group by table_name";
		$que = $this->db->query($sql);
		while($row = $this->db->fetch_array($que))
		{
			$channel_table[] = $row['table_name'];
			if($row['total'] < $this->settings['channel_table_limit'])
			{
				$useable[] = $row['table_name']; //找出可用的dvr表
			}
		}
		//如果没有可用的dvr表,就给该频道挑选一张合适的dvr表
		if(!$useable)
		{
			$tablename = select_table($channel_table);
		}
		else
		{
			$tablename = $useable[0];
		}
		$tablename_1 = $tablename.'_1'; //构建备份表
		//如果选定的表不存在,就创建
		$query = $this->db->query_first("SHOW TABLES LIKE '" .DB_PREFIX.$tablename. "'");
		if(!$query)
		{
			$sql = "CREATE TABLE " .DB_PREFIX.$tablename. " LIKE " .DB_PREFIX. "dvr";
			$sql2 = "CREATE TABLE " .DB_PREFIX.$tablename_1. " LIKE " .DB_PREFIX. "dvr";
			$this->db->query($sql);
			$this->db->query($sql2); //创建备份表
		}
		//更新表名字段
		$sql = "UPDATE " .DB_PREFIX. "channel SET table_name = '" .$tablename. "' WHERE id = " .$ret['id'];
		$this->db->query($sql);
		/*****************************************************************************/
		
		//同步数据至串联单的channel_server
		$this->schedule_syn_channel_server($_schedule_control,$ret['id']);
		//同步结束
		
		$id = $ret['id'];
		
		$data['id'] = $id;
		
		//频道信号
		/*
		foreach ($channel_stream AS $k => $v)
		{
			$v['channel_id'] = $id;
			
			if ($k == $is_default)
			{
				$v['is_default'] = 1;
			}
			$ret_channel_stream = $this->mChannel->channel_stream_create($v);
			
			if (!$ret_channel_stream)
			{
				continue;
			}
		}
		*/
		//长方形logo
		if ($_FILES['logo_rectangle']['tmp_name'])
		{
			$logo_rectangle = $this->mChannel->add_material($_FILES['logo_rectangle'], $id);
		}
		
		$data['logo_rectangle'] = $logo_rectangle ? serialize($logo_rectangle) : '';
		
		//方形logo
		if ($_FILES['logo_square']['tmp_name'])
		{
			$logo_square = $this->mChannel->add_material($_FILES['logo_square'], $id);
		}
		
		$data['logo_square'] = $logo_square ? serialize($logo_square) : '';
		/*
		//音频logo
		if ($_FILES['logo_audio']['tmp_name'])
		{
			$logo_audio = $this->mChannel->add_material($_FILES['logo_audio'], $id);
		}
		
		$data['logo_audio'] = $logo_audio ? serialize($logo_audio) : '';
		*/
		if (!empty($_FILES['client_logo']))
		{
			$client_logo = array();
			foreach ($_FILES['client_logo'] AS $k => $v)
			{
				$$k = $v;
				foreach ($$k AS $kk => $vv)
				{
					$client_logo[$kk][$k] = $vv;
				}
			}
			
			$_client_logo = array();
			foreach ($client_logo AS $appid => $logo)
			{
				$_client_logo[$appid] = $this->mChannel->add_material($logo, $id);
				$_client_logo[$appid]['appid'] 	 = $_appid[$appid];
				$_client_logo[$appid]['appname'] = $_appname[$appid];
			}
		}
		
		$data['client_logo'] = $_client_logo ? serialize($_client_logo) : ''; 
		
		$data['order_id']	 = $id;
		
		//更新 排序id、长方形logo、方形logo
		$update_data = array(
			'id'			 => $id,
			'order_id'		 => $data['order_id'],
			'logo_rectangle' => $data['logo_rectangle'],
			'logo_square'	 => $data['logo_square'],
			'client_logo'	 => $data['client_logo'],
			'logo_audio'	 => $data['logo_audio'],
		);
		//更新数据
		$ret = $this->mChannel->update($update_data);
		$this->mChannel->cache_channel($data['code']);
		/*
		if ($id)
		{
			//放入发布队列
			if(!empty($column_id))
			{
				$op = 'insert';
				$this->publish_insert_query($id, $op, $data['user_name']);
			}
			
			//日志
			$this->addLogs('新增直播频道' , '' , $data , $data['name'], $data['id']) ;
		}
		*/
		
		//向直播服务器发送数据库信息数据以及设置流、设置时移目录
		foreach($row_b as $key => $val)
		{
			$init_data = array(
				'host'=>$val['host'],
				'dir' =>$val['input_dir'],
			);
			$server->init_env($init_data);
			
			//发送数据库信息
			global $gDBconfig;
			$db_data = array(
				'mysql_host' 		=> $gDBconfig['host'],
				'mysql_user' 		=> $gDBconfig['user'],
				'mysql_password'		=> $gDBconfig['pass'],
				'mysql_name'			=> $gDBconfig['database'],
				//'mysql_table'		=> $val['fid'] ? DB_PREFIX.$tablename_1 : DB_PREFIX.$tablename,
				'mysql_table' 		=> 'liv_dvr',
				'post_streamname_url'	=> $this->settings['App_live']['protocol'].$this->settings['App_live']['host'].'/'.$this->settings['App_live']['dir'].'build_m3u8.php?m2o_ckey='.CUSTOM_APPKEY,
			);
			if(!$server->set_database($db_data))
			{
				$this->errorOutput('数据库信息发送失败');
			}
			//多码流频道创建
			if($channel_stream)
			{
				foreach ($channel_stream AS $k => $v)
				{
					$v['channel_id'] = $id;
					
					if ($k == $is_default)
					{
						$v['is_default'] = 1;
					}
					if(!$val['fid']) //防止产生重复记录
					{
						$this->mChannel->channel_stream_create($v);
					}
					
					$create_data = array(
					'url'=>$from_control_stream ? $from_control_stream : $v['url'],
					'name'=>build_nginx_stream_name($code, $v['stream_name']),
					'playlen'=>$time_shift,
					'mysql_table'=>$val['fid'] ? DB_PREFIX.$tablename_1 : DB_PREFIX.$tablename);
					if(!$is_push)
					{
						//对接nginx创建流
						if(!$server->create($create_data))
						{
							$this->errorOutput("服务器新增流失败，请稍后更新重试");
						}
					}
					//设置时移
					if(!$server->set_timeshift_length($create_data))
					{
						$this->errorOutput("设置时移失败");
					}
					//设置时移目录
					$path_data = array(
						'app' => 'live',
						'name' => $create_data['name'],
						'hls_path' => $val['hls_path'],
					);
					if(!$server->set_timeshift_path($path_data))
					{
						$this->errorOutput('设置时移目录失败');
					}
				}
			}
		}
		
		$this->addItem($data);
		$this->output();
	}
	
	
	
	public function update()
	{
		$id					= intval($this->input['id']);
		$name 				= trim($this->input['name']);
		$code 				= trim($this->input['code']);
	//	$server_id 			= intval($this->input['server_id']);
	//	$main_stream_name 	= $this->input['main_stream_name'];
		$stream_name 		= $this->input['stream_name'];
		$stream_id 			= $this->input['stream_id'];
		$url 	 			= $this->input['url'];
		$bitrate 			= $this->input['bitrate'];
		$is_control			= intval($this->input['is_control']);
		//$is_mobile_phone	= intval($this->input['is_mobile_phone']);
		$is_audio			= intval($this->input['is_audio']);
		$is_push			= intval($this->input['is_push']);
	//	$drm				= intval($this->input['drm']);
		$time_shift			= intval($this->input['time_shift']);
	//	$delay				= intval($this->input['delay']);
	//	$record_time_diff	= intval($this->input['record_time_diff']);
		$is_default	= intval($this->input['is_default']);
	//	$column_id 			= trim($this->input['column_id']);
		$_appid				= $this->input['_appid'];
		$_appname			= $this->input['_appname'];
		
		if (!$id)
		{
			$this->errorOutput('ID不能为空');
		}
		
		if (!$name)
		{
			$this->errorOutput('频道名称不能为空');
		}
		
		if (!$code)
		{
			$this->errorOutput('台号不能为空');
		}
	
		if (!hg_check_string($code))
		{
			$this->errorOutput('台号只能包含字母数字');
		}
		
		$ret_code = $this->mChannel->check_channel_code($code, $id);
		
		if (!empty($ret_code))
		{
			$this->errorOutput('['.$code.'] 台号已存在！');
		}
		
		//录制时间
		/*
		if ($time_shift > $this->settings['max_time_shift'])
		{
			$time_shift = $this->settings['max_time_shift'];
		}
		
		//延时时间
		if ($delay > $this->settings['max_delay'])
		{
			$delay = $this->settings['max_delay'];
		}
		*/
		//关联输出标识的来源地址
		//$url_index = $stream_url = array();
		$channel_stream = array();
		$core_count = count($stream_name);
		//$bitrates = array();
		
		for ($i = 0; $i < $core_count; $i ++)
		{
			if (!trim($stream_name[$i]))
			{
				$this->errorOutput('输出标识不能为空');
			}
		
			if ($i > 0 && $stream_name[0] == $stream_name[$i])
			{
				$this->errorOutput('输出标识不能重复');
			}
			
			if (!hg_check_string($stream_name[$i]))
			{
				$this->errorOutput('输出标识只能包含字母数字');
			}
			if (!trim($url[$i]))
			{
				$this->errorOutput('信号流地址不能为空');
			}
			if (!trim($bitrate[$i]))
			{
				$this->errorOutput('码流不可以为空');
			}
			/*
			if ($i == $is_default)
			{
				$is_default_stream = $stream_name[$i];
			}
			$url_index[$stream_name[$i]] 	= trim($url[$i]);
			$stream_url[$i]['stream_name'] 	= trim($stream_name[$i]);
			if($main_stream_name == $stream_url[$i]['stream_name'])
			{
				$main_stream_url = $url[$i];
			}
			$stream_url[$i]['url'] 			= trim($url[$i]);
			$bitrates[$stream_name[$i]]		= intval($bitrate[$i]);
			*/
			$channel_stream[$i] = array(
				'stream_name'	=> $stream_name[$i],
				'url'			=> $url[$i],
				//'input_id'		=> $ret_input_id[$i],
				//'delay_id'		=> $ret_delay_id[$i],
				//'change_id'		=> $ret_change_id[$i],
				//'output_id'		=> $ret_output_id[$i],
				'bitrate'		=> intval($bitrate[$i]),
				'is_main'		=> ($is_default == $i) ? 1 : 0,
				'order_id'		=> $i,
			);	
		}
		
		$main_stream_name = $stream_name[$is_default];
		$main_stream_url = $url[$is_default];
		$stream_count = $core_count;
		
		$level = 1;
		/*
		if ($delay)
		{
			$stream_count = $stream_count + $core_count;
			$level = $level + 1;
		}
		
		if ($is_control)
		{
			$stream_count = $stream_count + $core_count;
			$level = $level + 1;
		}
		
		if ($delay || $is_control)
		{
			$stream_count = $stream_count + $core_count;
			$level = $level + 1;
		}
		*/
		$channel_info = $this->mChannel->get_channel_by_id($id, 1);
		if($channel_info['code']!=$code)
		{
			$this->errorOutput("频道号无法修改");
		}
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		$schedule_control = unserialize($channel_info['schedule_control']);
		#####节点权限检测数据收集
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_node_ids = '';
			if($channel_info['node_id'])
			{
				$_node_ids = $channel_info['node_id'];
			}
			if($this->input['node_id'])
			{
				$_node_ids  = $_node_ids ? $_node_ids . ',' . $this->input['node_id'] : $this->input['node_id'];
			}
			if($_node_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'channel_node WHERE id IN('.$_node_ids.')';
				$query = $this->db->query($sql);
				$nodes = array();
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
			}
		}
		#####节点权限
		$nodes['id'] 		= $id;
		$nodes['user_id'] 	= $channel_info['user_id'];
		$nodes['org_id'] 	= $channel_info['org_id'];
		$nodes['column_id'] = $this->input['column_id'];
		
		$nodes['published_column_id'] = '';
	//	$nodes['weight'] = $nodes['weight'];
	
		$ori_column_id = array();
		if(is_array($channel_info['column_id']))
		{
			$ori_column_id = array_keys($channel_info['column_id']);
			$nodes['published_column_id'] = implode(',', $ori_column_id);
		}
		$this->verify_content_prms($nodes);
		########权限#########
		
		$server_id 		= $channel_info['server_id'];
		
		/*
		$channel_stream = $channel_info['channel_stream'];
		$applicationId	= $channel_info['application_id'];
		$stream_name_db	= $channel_info['stream_name'];
		
		$is_live 	= $channel_info['is_live'];
		$is_record  = $channel_info['is_record'];
		$is_push	= $channel_info['is_push'];
		
		//信号数据重新组合
		
		//模板stream_name 、 数据库stream_name 差集
		
		//delete
		$stream_name_diff_db_tpl = array_diff($stream_name_db, $stream_name);
		
		//create
		$stream_name_diff_tpl_db = array_diff($stream_name, $stream_name_db);
		
		//update
		$stream_name_inter_db_tpl = array_intersect($stream_name_db, $stream_name);
		
		$channel_stream_delete = $channel_stream_create = $channel_stream_update = array();
		
		if (!empty($channel_stream))
		{
			foreach ($channel_stream AS $v)
			{
				foreach ($stream_name_diff_db_tpl AS $vv)
				{
					if ($vv == $v['stream_name'])
					{
						$channel_stream_delete[] = $v;
					}
				}
				
				foreach ($stream_name_inter_db_tpl AS $kk => $vv)
				{
					if ($vv == $v['stream_name'])
					{
						if ($vv == $is_default_stream)
						{
							$is_default = 1;
						}
						else
						{
							$is_default = 0;
						}
						$v['is_default'] = $is_default;
						$v['url'] = $url_index[$v['stream_name']];
						$v['bitrate'] = $bitrates[$v['stream_name']];
						$channel_stream_update[] = $v;
					}
				}
			}
		}
		foreach ($stream_name_diff_tpl_db AS $kk => $vv)
		{
			if ($vv == $stream_name[$kk])
			{
				if ($vv == $is_default_stream)
				{
					$is_default = 1;
				}
				else
				{
					$is_default = 0;
				}
				$channel_stream_create[] = array(
					'channel_id'	=> $id,
					'stream_name'	=> $vv,
					'is_default'	=> $is_default,
					'url'			=> $url_index[$vv],
					'bitrate'		=> $bitrates[$vv],
				);
			}
		}

		//检查是否需要更新wowza服务器数据
		$is_update_wowza = 0;
		if ($channel_info['code'] != $code || $channel_info['is_audio'] != $is_audio || $channel_info['is_control'] != $is_control
			|| $channel_info['delay'] != $delay || $channel_info['is_mobile_phone'] != $is_mobile_phone || $channel_info['time_shift'] != $time_shift
		)
		{
			$is_update_wowza = 1;
		}
		
		foreach ($stream_url AS $k => $v)
		{
			if ($channel_stream[$k]['stream_name'] != $v['stream_name'] || $channel_stream[$k]['url'] != $v['url'] || $channel_stream[$k]['bitrate'] != $v['bitrate'] )
			{
				$is_update_wowza = 1;
			}
		}

		//服务器配置
		 * 
		 */
		$server_info = $this->mServerConfig->get_server_config_by_id($server_id);
	
		
		if (empty($server_info))
		{
			$this->errorOutput('该直播服务器不存在或已被删除');
		}
		
		//服务器状态
		if (!$server_info['status'])
		{
			$this->errorOutput('该服务器未审核');
		}
		
		$type 		 = $server_info['type'] ? $server_info['type'] : 'wowza';
		//$super_token = $server_info['super_token'];
		/*
		if ($is_update_wowza)
		{
			//服务器最大信号数目
			$counts 	  = $server_info['counts'] ? $server_info['counts'] : $this->settings['wowza']['counts'];
			//已用信号数目
			$_stream_count = $this->mChannel->get_stream_count($server_id, $id);
			//剩余信号数目
			$over_count   = $counts - ($_stream_count + $stream_count);
			
			if ($over_count < 0)
			{
				$this->errorOutput('信号数目已超过该服务器数目限制, 已超出 ' . abs($over_count) . ' 条');
			}
			
			$server_info = $this->mChannel->get_server_info($server_info);
			
			$host			= $server_info['host'];
			$input_dir  	= $server_info['input_dir'];
			$output_dir 	= $server_info['output_dir'];
			$wowzaip_input 	= $server_info['wowzaip_input'];
	
			$live_host		= $server_info['live_host'];
			$record_host	= $server_info['record_host'];
			
			//检测流媒体服务器是否正常
			$function = 'media_server_' . $type;
			$ret_media_server = $this->$function($server_info, $super_token);
			
			if (!$ret_media_server['core_server'])
			{
				$this->errorOutput($type . '主控服务器未启动');
			}
			
			if ($live_host && !$ret_media_server['live_server'])
			{
				$this->errorOutput($type . '直播服务器未启动');
			}
			
			if ($record_host && !$ret_media_server['record_server'])
			{
				$this->errorOutput($type . '录制服务器未启动');
			}
			
			if ($type == 'wowza')
			{	
				$applicationType = 1;
				if ($is_mobile_phone)
				{
					$applicationType = 3;
				}
				
				$application_data = array(
					'action'	=> 'update',
					'id'		=> $applicationId,
					'name'		=> $code,
					'length'	=> $time_shift * 3600,
					'drm'		=> $drm,
					'type'		=> $applicationType,
				);
				
				$ret_application = $this->mLivemms->outputApplicationOperate($host, $output_dir, $application_data);
				
				if (!$ret_application['result'])
				{
					$this->errorOutput('主控输出层应用更新失败');
				}
				
				//直播
				if ($live_host)
				{
					$application_data['length'] = 0;
					
					if (!$is_live)
					{
						$application_data['action'] = 'insert';
					}
					
					$ret_application = $this->mLivemms->outputApplicationOperate($live_host, $output_dir, $application_data);
						
					if (!$ret_application['result'])
					{
						$this->errorOutput('直播输出层应用更新失败');
					}
				}
				
				//录制
				if ($record_host)
				{
					$application_data['length'] = 0;
					
					if (!$is_record)
					{
						$application_data['action'] = 'insert';
					}
					
					$ret_application = $this->mLivemms->outputApplicationOperate($record_host, $output_dir, $application_data);
					
					if (!$ret_application['result'])
					{
						$this->errorOutput('录制输出层应用更新失败');
					}
				}
			}
			
			//delete
			foreach ($channel_stream_delete AS $v)
			{
				$v['code'] 			= $channel_info['code'];
				$v['delay'] 		= $channel_info['delay'];
				$v['is_control'] 	= $channel_info['is_control'];
				$v['is_live'] 		= $channel_info['is_live'];
				$v['is_record'] 	= $channel_info['is_record'];
				
				$function = 'stream_operate_' . $type;
				$this->$function($server_info, $v, 'delete');
			}
		
			//update
			$ret_input_id_update = $ret_delay_id_update = $ret_change_id_update = $ret_output_id_update = $ret_live_output_id_update = $ret_record_output_id_update = $channel_stream_info_update = array();
			
			foreach ($channel_stream_update AS $k => $v)
			{
				$v['code'] 			= $channel_info['code'];
				$v['db_delay'] 		= $channel_info['delay'];
				$v['db_is_control'] = $channel_info['is_control'];
				$v['delay'] 		= $delay;
				$v['is_control'] 	= $is_control;
				$v['is_live'] 		= $channel_info['is_live'];
				$v['is_record'] 	= $channel_info['is_record'];
				$v['status'] 		= $channel_info['status'];
				$v['applicationId'] 		= $channel_info['application_id'];
				$v['time_shift'] 	= $time_shift;
				
				$function = 'edit_stream_' . $type;
				$edit_stream = $this->$function($server_info, $v);
				
				$ret_input_id_update[$k]  = $edit_stream['ret_input_id'];
				$ret_delay_id_update[$k]  = $edit_stream['ret_delay_id'];
				$ret_change_id_update[$k] = $edit_stream['ret_change_id'];
				$ret_output_id_update[$k] = $edit_stream['ret_output_id'];
				
				$ret_live_output_id_update[$k]   = $edit_stream['ret_live_output_id'];
				$ret_record_output_id_update[$k] = $edit_stream['ret_record_output_id'];
				
				if (!$ret_output_id_update)
				{
					continue;
				}
				
				//直播
				if ($live_host && !$ret_live_output_id_update)
				{
					continue;
				}
				
				//录制
				if ($record_host && !$ret_record_output_id_update)
				{
					continue;
				}
				
				$channel_stream_info_update[$k] = array(
					'id'			=> $v['id'],
					'stream_name'	=> $v['stream_name'],
					'url'			=> $v['url'],
					'is_default'			=> $v['is_default'],
					'input_id'		=> $ret_input_id_update[$k],
					'delay_id'		=> $ret_delay_id_update[$k],
					'change_id'		=> $ret_change_id_update[$k],
					'output_id'		=> $ret_output_id_update[$k],
					'bitrate'		=> $v['bitrate'],
					'is_main'		=> ($main_stream_name == $v['stream_name']) ? 1 : 0,
				);
			}
			
			//create
			$ret_input_id = $ret_delay_id = $ret_change_id = $ret_output_id = $channel_stream_info = array();
			$ret_live_output_id = $ret_record_output_id = array();
			foreach ($channel_stream_create AS $k => $v)
			{
				$_channel_stream = array(
					'url'				=> $v['url'],
					'delay'				=> $delay,
					'is_control'		=> $is_control,
					'is_push'			=> $is_push,
					'applicationId'		=> $applicationId,
					'stream_name'		=> $v['stream_name'],
					'is_default'		=> $v['is_default'],
					'code'				=> $code,
					'time_shift'		=> $time_shift,
					'status'			=> $channel_info['status'],
				);
				
				$function = 'set_stream_' . $type;
				$set_stream = $this->$function($server_info, $_channel_stream, true);
					
				$ret_input_id[$k]  = $set_stream['ret_input_id'];
				$ret_delay_id[$k]  = $set_stream['ret_delay_id'];
				$ret_change_id[$k] = $set_stream['ret_change_id'];
				$ret_output_id[$k] = $set_stream['ret_output_id'];
				
				$ret_live_output_id[$k]   = $set_stream['ret_live_output_id'];
				$ret_record_output_id[$k] = $set_stream['ret_record_output_id'];
				
				$channel_stream_info[$k] = array(
					'stream_name'	=> $v['stream_name'],
					'url'			=> $v['url'],
					'input_id'		=> $ret_input_id[$k],
					'delay_id'		=> $ret_delay_id[$k],
					'change_id'		=> $ret_change_id[$k],
					'output_id'		=> $ret_output_id[$k],
					'bitrate'		=> $v['bitrate'],
					'is_main'		=> ($main_stream_name == $v['stream_name']) ? 1 : 0,
				);
			}
			
			//如果创建过程中有失败，则删除以创建好的
			$tmp_delete = 0;
			for ($j = 0; $j < count($channel_stream_create); $j ++)
			{
				if (!$ret_output_id[$j])
				{
					$tmp_delete = 1;
				}
				
				//直播
				if ($live_host && !$ret_live_output_id[$j])
				{
					$tmp_delete = 1;
				}
				
				//录制
				if ($record_host && !$ret_record_output_id[$j])
				{
					$tmp_delete = 1;
				}
			}
			
			if ($tmp_delete)
			{
				for ($i = 0; $i < count($channel_stream_create); $i ++)
				{
					$_channel_stream = $channel_stream_info[$i];
					$_channel_stream['code'] 	   	= $code;
					$_channel_stream['is_control'] 	= $is_control;
					$_channel_stream['delay'] 		= $delay;
					$_channel_stream['is_live'] 	= $is_live;
					$_channel_stream['is_record'] 	= $is_record;
					
					$function = 'stream_operate_' . $type;
					$this->$function($server_info, $_channel_stream, 'delete');
				}
				
				$this->errorOutput('流媒体服务器新增信号创建失败');
			}
			
			if ($live_host)
			{
				$is_live = 1;
				foreach ($ret_live_output_id_update AS $v)
				{
					if (!$v)
					{
						$is_live = 0;
						$this->errorOutput('更新直播服务器信号失败');
					}
				}
			}
			
			if ($record_host)
			{
				$is_record = 1;
				foreach ($ret_record_output_id_update AS $v)
				{
					if (!$v)
					{
						$is_record = 0;
						$this->errorOutput('更新录制服务器信号失败');
					}
				}
			}
		}
		*/
		$data = array(
			'id'				=> $id,
			'name'				=> $name,
	//		'code'				=> $code,
			'main_stream_name'	=> $main_stream_name,
			'stream_name'		=> serialize($stream_name),
			'stream_count'		=> $stream_count,
	//	'level'				=> $level,
			'core_count'		=> $core_count,
			'application_id'	=> $applicationId,
			'time_shift'		=> $time_shift,
	//		'delay'				=> $delay,
			'is_audio'			=> $is_audio,
			'is_push'			=> $is_push,
	//		'drm'				=> $drm,
			'is_control'		=> $is_control,
	//		'is_mobile_phone'	=> $is_mobile_phone,
			'record_time_diff'	=> $record_time_diff,
			'server_id'			=> $server_id,
			'status'			=> ($type == 'wowza') ? ($is_update_wowza ? 0 : 1) : $channel_info['status'],
	//		'is_live'			=> $is_live,
	//		'is_record'			=> $is_record,
			'can_record'			=> 1,
			'record_uri'			=> $this->input['record_uri'],
		);
		
		//发布开始
		//$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		//$data['column_id'] = $data['column_id'] ? serialize($data['column_id']) : '';
		//发布结束
		
		//去除播控
		if(!$is_control && $this->settings['schedule_control_wowza']['is_wowza'] && $schedule_control)
		{
			//本需要删除wowza中的流和数据 现只对nginx操作
			
		}
		//如果不存在创建播控和串联单两层输出
		if($is_control && $this->settings['schedule_control_wowza']['is_wowza'] && !$schedule_control)
		{
			$schedule_control = array();
			$_schedule_control_data = array(
			'url'  => $main_stream_url,
			'type' => $is_push,
			'action'=>'insert',
			);
			if(!($schedule_control = $this->create_schedule_control($_schedule_control_data)))
			{
				$this->errorOutput("创建播控层[或串联单层]失败");
			}
			//同步数据至串联单的channel_server
			$this->schedule_syn_channel_server($schedule_control,$id);
			$data['schedule_control'] = serialize($schedule_control);
		}
		//如果修改了流地址 只需修改底流
		$from_control_stream = '';
		if($is_control && $this->settings['schedule_control_wowza']['is_wowza'] && $schedule_control)
		{
			if(count($channel_stream) > 1)
			{
				$this->errorOutput("多码流不支持播空和串联单");
			}
			$_schedule_control_data = array(
			'url'  => $main_stream_url,
			'type' => $is_push,
			'action'=>'update',
			'id'=>$schedule_control['schedule']['stream_id'],
			);
			$host = $this->settings['schedule_control_wowza']['host'];
			$inputdir = $outputdir = $this->settings['schedule_control_wowza']['inputdir'];
			$ret = $this->mLivemms->inputStreamOperate($host,$inputdir,$_schedule_control_data);
			if(!$ret['result'])
			{
				$this->errorOutput("wowza更新失败");
			}
			$ret = $this->mLivemms->inputStreamOperate($host,$inputdir,array('action'=>'start', 'id'=>$_schedule_control_data['id']));
			if(!$ret['result'])
			{
				$this->errorOutput("流启动失败");
			}
			
			$from_control_stream = "rtmp://".str_replace(':8086', '', $this->settings['schedule_control_wowza']['host'])."/input/".$schedule_control['control']['output_id'].".output";;
		}
		
		/******************** 检查TS目录存储 *************************/
		include(CUR_CONF_PATH . 'lib/' . $type . '.live.php');
		$server = new m2oLive();
		//查出所有使用该直播配置的信号流占用的存储
		$sql = "SELECT c_s.bitrate,c_s.channel_id,c.time_shift FROM " .DB_PREFIX. "channel_stream c_s 
				LEFT JOIN " .DB_PREFIX. "channel c ON c_s.channel_id = c.id 
				WHERE c.server_id = " .$server_id;
		$q = $this->db->query($sql);
		$used_size = 0;
		while($row = $this->db->fetch_array($q))
		{
			if($row['channel_id'] != $id) //除正在编辑的频道之外
			{
				$used_size += $row['bitrate']*$row['time_shift']*3600;
			}
		}
		//新添加的信号流所需的存储
		$bitrate = 0;
		if(is_array($channel_stream))
		{
			foreach($channel_stream as $k => $v)
			{
				$bitrate += $v['bitrate'];
			}
		}
		//所需存储总数 (存储单位b, 码流单位kbps)
		$needsize = (3600*$time_shift*$bitrate+$used_size)*1024;
		//取主、备配置
		$sql = "SELECT host,input_dir,hls_path,fid FROM " .DB_PREFIX. "server_config WHERE id = " .$server_id. " OR fid = " . $server_id;
		$query = $this->db->query($sql);
		$query_b = $query;
		while($row = $this->db->fetch_array($query))
		{
			$row_b[] = $row;
			$init_data = array(
				'host'=>$row['host'],
				'dir' =>$row['input_dir'],
			);
			$server->init_env($init_data);
			if(!$server->check_ts_path(array('needsize'=>$needsize,'hls_path'=>$row['hls_path'])))
			{
				$this->errorOutput('主机'.$init_data['host'].'所设置的TS目录存储不足');
			}
		}
		/**************************************************************/
		
		//入本地库
		$ret = $this->mChannel->update($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput('更新失败');
		}
		$init_data = array(
			'host'=>$server_info['host'],
			'dir' =>$server_info['input_dir'],
		);
		$main_stream_url_db = '';
		if(is_array($channel_info['channel_stream']))
		{
			foreach ($channel_info['channel_stream'] as $val)
			{
				if($val['is_main'])
				{
					$main_stream_url_db = $val['url'];
				}
			}
		}
		//$this->errorOutput(var_export($main_stream_url_db,1));
		//include(CUR_CONF_PATH . 'lib/' . $type . '.live.php');
		
		//$server = new m2oLive();
		
		//$update_data = array('url'=>$main_stream_url,'name'=>$code . '_' . $main_stream_name);
		//$db_data = array('url'=>$main_stream_url_db, 'name'=>$code . '_' . $main_stream_name);
		//$this->errorOutput(var_export(array_diff($update_data, $db_data),1).'a');
		/*
		$is_update_server = false;
		if($channel_stream)
		{
			$streams_in_db = array();
			$streams_in_form = array();
			foreach($channel_stream as $k=>$v)
			{
				$streams_in_db[] = $code . '_' . $v['stream_name'];
			}
			foreach ($channel_info['channel_stream'] as $val)
			{
				$streams_in_form[] = $code . '_' . $val['stream_name'];
			}
			if(array_diff($streams_in_db, $streams_in_form) || array_diff($streams_in_form, $streams_in_db))
			{
				$is_update_server = true;
			}
		}
		*/
		//向直播服务器发送数据库信息数据以及设置流、设置时移目录
		foreach($row_b as $kee => $vaa)
		{
			$init_data = array(
				'host'=>$vaa['host'],
				'dir' =>$vaa['input_dir'],
			);
			$server->init_env($init_data);
			if(is_array($channel_info['channel_stream']) && $channel_info['channel_stream'])
			{
				foreach ($channel_info['channel_stream'] as $val)
				{
					$server->delete(array(
					'url'=>$from_control_stream ? $from_control_stream : $v['url'],
					'name'=>build_nginx_stream_name($code, $val['stream_name']),
					));
				}
			}
			$this->db->query("DELETE FROM " . DB_PREFIX . "channel_stream WHERE channel_id IN (" . $id . ")");
			if(is_array($channel_stream) && $channel_stream)
			{
				foreach ($channel_stream AS $k=>$v)
				{
					$v['channel_id'] = $id;
					$v['is_default'] = 0;
					if($k==$is_default)
					{
						$v['is_default'] = 1;
					}
					$create_data = array(
						'url'=>$from_control_stream ? $from_control_stream  : $v['url'],
						'name'=>build_nginx_stream_name($code, $v['stream_name']),
						'playlen'=>$time_shift,
						'mysql_table'=>$vaa['fid'] ? DB_PREFIX.$channel_info['table_name'].'_1' : DB_PREFIX.$channel_info['table_name'],
					);
					if(!$is_push)
					{
						if(!$server->create($create_data))
						{
							$this->errorOutput("服务器更新流失败，请稍后更新重试");
						}
					}
					$this->mChannel->channel_stream_create($v);
					//设置时移
					if(!$server->set_timeshift_length($create_data))
					{
						$this->errorOutput("设置时移失败");
					}
					//设置时移目录
					$path_data = array(
						'app' => 'live',
						'name' => $create_data['name'],
						'hls_path' => $vaa['hls_path'],
					);
					if(!$server->set_timeshift_path($path_data))
					{
						$this->errorOutput('设置时移目录失败');
					}
				}
			}
		}
		
		/*
		//更新标记
		$affected_rows = $ret['affected_rows'];
		
		//频道信号delete
		foreach ($channel_stream_delete AS $v)
		{
			$ret_channel_stream = $this->mChannel->channel_stream_delete($v['id']);
			
			if (!$ret_channel_stream)
			{
				continue;
			}
			
			if ($ret_channel_stream['affected_rows'])
			{
				$affected_rows = $ret_channel_stream['affected_rows'];
			}
		}
		//频道信号update
		foreach ($channel_stream_info_update AS $v)
		{
			$ret_channel_stream = $this->mChannel->channel_stream_update($v);
			
			if (!$ret_channel_stream)
			{
				continue;
			}
		
			if ($ret_channel_stream['affected_rows'])
			{
				$affected_rows = $ret_channel_stream['affected_rows'];
			}
		}
		*/
		//频道信号create
		/*
		//更新排序id
		if (!empty($channel_stream_info))
		{
			$channel_stream = $this->mChannel->get_channel_stream_by_channel_id($id);
		}

		$update_order = array();
		foreach ($stream_name AS $k => $v)
		{
			foreach ($channel_stream AS $kk => $vv)
			{
				if ($v == $vv['stream_name'])
				{
					$update_order[$k]['id'] 	  = $vv['id'];
					$update_order[$k]['order_id'] = $k;
				}
			}
		}
		
		foreach ($update_order AS $v)
		{
			$ret_channel_stream = $this->mChannel->channel_stream_update($v);
			
			if (!$ret_channel_stream)
			{
				continue;
			}
		
			if ($ret_channel_stream['affected_rows'])
			{
				$affected_rows = $ret_channel_stream['affected_rows'];
			}
		}
		*/
		//长方形logo
		if ($_FILES['logo_rectangle']['tmp_name'])
		{
			$logo_rectangle = $this->mChannel->add_material($_FILES['logo_rectangle'], $id);
		}
		
		$data['logo_rectangle'] = $logo_rectangle ? serialize($logo_rectangle) : '';
		
		//方形logo
		if ($_FILES['logo_square']['tmp_name'])
		{
			$logo_square = $this->mChannel->add_material($_FILES['logo_square'], $id);
		}
		
		$data['logo_square'] = $logo_square ? serialize($logo_square) : '';
		/*
		//音频logo
		if ($_FILES['logo_audio']['tmp_name'])
		{
			$logo_audio = $this->mChannel->add_material($_FILES['logo_audio'], $id);
		}
		
		$data['logo_audio'] = $logo_audio ? serialize($logo_audio) : '';
		*/
		//多客户端logo
		if (!empty($_FILES['client_logo']) || $channel_info['client_logo'])
		{
			$client_logo = array();
			foreach ($_FILES['client_logo'] AS $k => $v)
			{
				$$k = $v;
				foreach ($$k AS $kk => $vv)
				{
					$client_logo[$kk][$k] = $vv;
				}
			}

			$_client_logo = array();
			foreach ($_appid AS $appid)
			{
				if ($client_logo[$appid])
				{
					$_client_logo[$appid] = $this->mChannel->add_material($client_logo[$appid], $id);
					$_client_logo[$appid]['appid'] 	 = $_appid[$appid];
					$_client_logo[$appid]['appname'] = $_appname[$appid];
				}
				else
				{
					$_client_logo[$appid] = $channel_info['client_logo'][$appid];
					if (!$channel_info['client_logo'][$appid])
					{
						unset($_client_logo[$appid]);
					}
				}
			}
		}

		$data['client_logo'] = $_client_logo ? serialize($_client_logo) : '';
		
		//更新 长方形logo、方形logo、多客户端logo、音频logo
		$update_data = array(
			'id' => $id,
		);
		
		if ($data['logo_rectangle'])
		{
			$update_data['logo_rectangle'] = $data['logo_rectangle'];
		}
		
		if ($data['logo_square'])
		{
			$update_data['logo_square'] = $data['logo_square'];
		}
		/*
		if ($data['logo_audio'])
		{
			$update_data['logo_audio'] = $data['logo_audio'];
		}
		*/
		if ($data['client_logo'])
		{
			$update_data['client_logo'] = $data['client_logo'];
		}
	
		if ($channel_info['client_logo'] && empty($_appid))
		{
			$data['client_logo'] = $channel_info['client_logo'];
			$update_data['client_logo'] = '';
		}
		
		if ($channel_info['logo_audio'] && !$is_audio)
		{
			$data['logo_audio'] = $channel_info['logo_audio'];
			$update_data['logo_audio'] = '';
		}
		
		//更新数据
		if ($data['logo_rectangle'] || $data['logo_square'] || $data['client_logo'] || $data['logo_audio'])
		{
			$ret_logo = $this->mChannel->update($update_data);
			
			$affected_rows = $ret_logo['affected_rows'];
		}
		/*
		//发布开始
		if ($ret['id'])
		{
			//更改文章后发布的栏目
			$ret['column_id'] = unserialize($ret['column_id']);
			$new_column_id = array();
			if(is_array($ret['column_id']))
			{
				$new_column_id = array_keys($ret['column_id']);
			}
			
			if(!empty($channel_info['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->publish_insert_query($data['id'], 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					$this->publish_insert_query($data['id'], 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
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
		}
		*/
		$m3u8file = DATA_DIR . $data['code'] . '/playlist.m3u8';
		@unlink($m3u8file);
		//记录日志
		if (true)
		{
			$user_data = array(
				'id'				=> $id,
				'update_time'		=> TIMENOW,
				'update_org_id' 	=> $this->user['org_id'],
				'update_user_id' 	=> $this->user['user_id'],
				'update_user_name' 	=> $this->user['user_name'],
				'update_appid' 		=> $this->user['appid'],
				'update_appname' 	=> $this->user['display_name'],
				'update_ip' 		=> hg_getip(),
			);
			
			$ret_user = $this->mChannel->update($user_data);
			
			$this->mChannel->cache_channel($data['code']);


			if (!empty($ret_user))
			{
				unset($ret_user['id']);
				foreach ($ret_user AS $k => $v)
				{
					$data[$k] = $v;
				}
			}
			
			$pre_data = $channel_info;
			$this->addLogs('更新直播频道' , $pre_data , $data , $data['name'], $data['id']);
		}
		
		$this->addItem($ret);
		$this->output();
	}

	public function delete()
	{
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
	//	$filed = 'id, application_id, server_id, node_id, user_id';
		$channel_info = $this->mChannel->get_channel_info_by_id($id);
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		#####整合数据进行权限
		$nodes = $node_id = array();
		foreach ($channel_info AS $v)
		{
			$node_id[] = $v['node_id'];
			$nodes[] = array(
				'title' 		=> $v['name'],
				'delete_people' => $this->user['user_name'],
				'cid' 			=> $v['id'],
				'catid' 		=> $v['node_id'],
				'user_id'		=> $v['user_id'],
				'org_id'		=> $v['org_id'],
				'id'			=> $v['id'],
			);
		}
		
		if($node_id)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'channel_node WHERE id IN('.implode(',',$node_id).')';
			$query = $this->db->query($sql);
			$node_ids = array();
			while($row = $this->db->fetch_array($query))
			{
				$node_ids[$row['id']] = $row['parents'];
			}
		}
		
		if(!empty($nodes))
		{
			foreach ($nodes AS $node)
			{
				if($node['catid'])
				{
					$node['nodes'][$node['catid']] = $node_ids[$node['catid']];
				}
				$this->verify_content_prms($node);
			}
		}
		#####整合数据进行权限结束
		
		$server_id = array();
		foreach ($channel_info AS $v)
		{
			$server_id[] = $v['server_id'];
		}
		
		if (!empty($server_id))
		{
			$server_id = implode(',', array_unique($server_id));
			$server_infos 	= $this->mServerConfig->get_server_config($server_id);
		}
		foreach ($channel_info AS $channel)
		{
			if(!empty($server_infos[$channel['server_id']]))
			{
			$server_type = $server_infos[$channel['server_id']]['type'];
			$server_host = $server_infos[$channel['server_id']]['host'];
			$server_dir	 = $server_infos[$channel['server_id']]['input_dir'];
			include_once(CUR_CONF_PATH . 'lib/' . $server_type . '.live.php');
			$server = new m2oLive();
			$server->init_env(array('host'=>$server_host, 'dir'=>$server_dir));
			$server->delete(array('name'=>$channel['code'] . '_' . $channel['main_stream_name']));
			}
			$this->mChannel->delete($channel['id']);
			$this->addLogs('删除直播频道' , $channel , '' , $channel['name'] ,$channel['id']);
		}
		/*
		$ret_output_tmp = 0;
		
		foreach ($channel_info AS $channel)
		{
			$server_info = $this->mChannel->get_server_info($server_infos[$channel['server_id']]);
			
			
			$host			= $server_info['host'];
			$input_dir  	= $server_info['input_dir'];
			$output_dir 	= $server_info['output_dir'];
			
			$live_host		= $server_info['live_host'];
			$record_host	= $server_info['record_host'];
			
			$code	  	 = $channel['code'];
			$delay  	 = $channel['delay'];
			$is_control  = $channel['is_control'];
			$is_live   	 = $channel['is_live'];
			$is_record   = $channel['is_record'];
			$type 		 = $server_infos[$channel['server_id']]['type'] ? $server_infos[$channel['server_id']]['type'] : 'wowza';
			$super_token = $server_infos[$channel['server_id']]['super_token'];
			
			//检测流媒体服务器是否正常
			$function = 'media_server_' . $type;
			$ret_media_server = $this->$function($server_info, $super_token);
			
			if (!$ret_media_server['core_server'])
			{
				$this->errorOutput($type . '主控服务器未启动');
			}
			
			if ($live_host && !$ret_media_server['live_server'])
			{
				$this->errorOutput($type . '直播服务器未启动');
			}
			
			if ($record_host && !$ret_media_server['record_server'])
			{
				$this->errorOutput($type . '录制服务器未启动');
			}
			
			$return = array();
			if (!empty($channel['channel_stream']))
			{
				foreach ($channel['channel_stream'] AS $kk => $channel_stream)
				{
					$channel_stream['code']		  = $code;
					$channel_stream['is_control'] = $is_control;
					$channel_stream['delay'] 	  = $delay;
					$channel_stream['is_live']	  = $is_live;
					$channel_stream['is_record']  = $is_record;
					
					//调用流媒体服务器函数
					$function 	= 'stream_operate_' . $type;
					$return[$kk] = $this->$function($server_info, $channel_stream, 'delete');
				}
			}
			
			$flag = 0;
			foreach ($return AS $v)
			{
				if (!$v['ret_output_id'])
				{
					$flag = 1;
				}
				
				//直播
				if ($live_host && !$v['ret_live_output_id'])
				{
					$flag = 1;
				}
				
				//录制
				if ($record_host && !$v['ret_record_output_id'])
				{
					$flag = 1;
				}
			}
			
			if (!$flag)
			{
				$tmp_delete = 0;
				if ($channel['application_id'])
				{
					$application_data = array(
						'action'	=> 'delete',
						'id'		=> $channel['application_id'],
					);
					
					$ret_application = $this->mLivemms->outputApplicationOperate($host, $output_dir, $application_data);
					if (!$ret_application['result'])
					{
						$tmp_delete = 1;
						continue;
					}
					
					//直播
					if ($live_host && $is_live)
					{
						$ret_application = $this->mLivemms->outputApplicationOperate($live_host, $output_dir, $application_data);
						
						if (!$ret_application['result'])
						{
							$tmp_delete = 1;
							continue;
						}
					}
					
					//录制
					if ($record_host && $is_record)
					{
						$ret_application = $this->mLivemms->outputApplicationOperate($record_host, $output_dir, $application_data);
						
						if (!$ret_application['result'])
						{
							$tmp_delete = 1;
							continue;
						}
					}
				}
				
				if (!$tmp_delete)
				{
					$ret_delete = $this->mChannel->delete($channel['id']);
						
					//记录日志
					$pre_data = $channel;
					$this->addLogs('删除直播频道' , $pre_data , '' , $channel['name'] ,$channel['id']);
				}
			}
			else 
			{
				$this->errorOutput($channel['name'] . '频道删除失败');
			}
		}
		*/
		//删除播控和串联层
		if($this->settings['schedule_control_wowza']['is_wowza'] && $channel_info)
		{
			$host = $this->settings['schedule_control_wowza']['host'];
			$apidir = $this->settings['schedule_control_wowza']['inputdir'];
			foreach ($channel_info AS $v)
			{
				$schedule_control = unserialize($v['schedule_control']);
				//file_put_contents(CACHE_DIR . 'debug.txt', var_export($schedule_control,1));
				if($schedule_control)
				{
					foreach($schedule_control as $app=>$value)
					{
						$this->mLivemms->inputStreamOperate($host, $apidir, array('action'=>'delete','id'=>$value['stream_id']));
						$this->mLivemms->inputOutputStreamOperate($host, $apidir, array('action'=>'delete','id'=>$value['output_id']));
					}
				}
				
			}
		}
		//同步删除串联的的channel_server
		if($this->settings['App_schedule'] && $this->settings['schedule_control_wowza']['is_wowza'])
		{
			require_once(ROOT_PATH . 'lib/class/curl.class.php');
			$schedule_api = new curl($this->settings['App_schedule']['host'],$this->settings['App_schedule']['dir']);
			$schedule_api->initPostData();
			$schedule_api->addRequestData('channel_id', $id);
			foreach($_schedule_control['schedule'] as $key=>$val)
			{
				$schedule_api->addRequestData($key, $val);
			}
			$schedule_api->addRequestData('a', 'cancell_wowza_schedule');
			$is_syn = $schedule_api->request('schedule.php');
			if($is_syn[0]!=='success')
			{
				file_put_contents(CACHE_DIR .  'debug.txt', var_export($is_syn,1));
			}
		}
		$this->addItem($id);
		$this->output();
	}
	
	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('channel', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function audit()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$field = 'id, name, core_count, status, delay, is_control, server_id, node_id, user_id, is_live, is_record, org_id, code';
		
		$channel_info = $this->mChannel->get_channel_by_id($id, 1, $field);
		
		if (empty($channel_info))
		{
			$this->errorOutput('该频道不存在或已被删除');
		}
		
		#####整合数据进行权限
		$nodes = array(
			'title' 		=> $channel_info['name'],
			'delete_people' => $this->user['user_name'],
			'cid' 			=> $channel_info['id'],
			'catid' 		=> $channel_info['node_id'],
			'user_id'		=> $channel_info['user_id'],
			'org_id'		=> $channel_info['org_id'],
			'id'			=> $channel_info['id'],
		);
		$node_id = $channel_info['node_id'];
		if($node_id)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'channel_node WHERE id IN('.$node_id.')';
			$query = $this->db->query($sql);
			$node_ids = array();
			while($row = $this->db->fetch_array($query))
			{
				$node_ids[$row['id']] = $row['parents'];
			}
		}
		if($nodes['catid'])
		{
			$nodes['nodes'][$nodes['catid']] = $node_ids[$nodes['catid']];
		}
		$this->verify_content_prms($nodes);
		#####整合数据进行权限结束
		
		$core_count 	= $channel_info['core_count'];
		$status 		= $channel_info['status'];
		$delay 			= $channel_info['delay'];
		$is_control 	= $channel_info['is_control'];
		$server_id		= $channel_info['server_id'];
		
		$channel_stream	= $channel_info['channel_stream'];
		
		$code			= $channel_info['code'];
		$is_live		= $channel_info['is_live'];
		$is_record		= $channel_info['is_record'];
		
		if (empty($channel_stream))
		{
			$this->errorOutput('该频道信号不存在或已被删除');
		}
		
		//服务器配置
		if ($server_id)
		{
			$server_info = $this->mServerConfig->get_server_config_by_id($server_id);
		}
	
		if (empty($server_info))
		{
			$this->errorOutput('该直播服务器不存在或已被删除');
		}
		
		//服务器状态
		if (!$server_info['status'])
		{
			$this->errorOutput('该服务器未审核');
		}
		
		$type 		 = $server_info['type'] ? $server_info['type'] : 'wowza';
		$super_token = $server_info['super_token'];
		
		$server_info = $this->mChannel->get_server_info($server_info);
		
		$host			= $server_info['host'];
		$input_dir  	= $server_info['input_dir'];
		$output_dir 	= $server_info['output_dir'];
		
		$live_host		= $server_info['live_host'];
		$record_host	= $server_info['record_host'];
		
		//检测流媒体服务器是否正常
		$function = 'media_server_' . $type;
		$ret_media_server = $this->$function($server_info, $super_token);
		
		if (!$ret_media_server['core_server'])
		{
			$this->errorOutput($type . '主控服务器未启动');
		}
		
		if ($live_host && $is_live  && !$ret_media_server['live_server'])
		{
			$this->errorOutput($type . '直播服务器未启动');
		}
		
		if ($record_host && $is_record  && !$ret_media_server['record_server'])
		{
			$this->errorOutput($type . '录制服务器未启动');
		}
		
		$return = array();
		
		$ret = 0;
		
		//切播层启动停止延时时间
		$seconds = $this->settings['change_sleep_time'];
		
		if (!$status)	//启动
		{
			foreach ($channel_stream AS $k => $v)
			{
				$v['code']		= $code;
				$v['is_live']	= $is_live;
				$v['is_record'] = $is_record;
				
				//调用流媒体服务器函数
				$function 	= 'stream_operate_' . $type;
				$return[$k] = $this->$function($server_info, $v, 'start');
			}
			
			//如果启动有失败的, 则停止已经启动的
			$flag = 0;
			for ($i = 0; $i < $core_count; $i ++)
			{
				if (!$return[$i]['ret_output_id'])
				{
					$flag = 1;
				}
				
				//直播
				if ($live_host && $is_live && !$return[$i]['ret_live_output_id'])
				{
					$flag = 1;
				}

				//录制
				if ($record_host && $is_record && !$return[$i]['ret_record_output_id'])
				{
					$flag = 1;
				}
			}
			
			if (!$flag)	//更新频道状态
			{
				$update_data = array(
					'id'	 => $id,
					'status' => 1,
				);
				
				$this->mChannel->update($update_data);
				
				$ret = 1;
			}
			else 
			{
				foreach ($channel_stream AS $k => $v)
				{
					$v['code']		= $code;
					$v['is_live']	= $is_live;
					$v['is_record'] = $is_record;
					
					//调用流媒体服务器函数
					$function 	= 'stream_operate_' . $type;
					$return[$k] = $this->$function($server_info, $v, 'stop');
				}
			}
		}
		else //停止
		{
			foreach ($channel_stream AS $k => $v)
			{
				$v['code']		= $code;
				$v['is_live']	= $is_live;
				$v['is_record'] = $is_record;
				
				//调用流媒体服务器函数
				$function 	= 'stream_operate_' . $type;
				$return[$k] = $this->$function($server_info, $v, 'stop');
			}
			
			//如果停止有失败的, 则启动已经停止的
			$flag = 0;
			for ($i = 0; $i < $core_count; $i ++)
			{
				if (!$return[$i]['ret_output_id'])
				{
					$flag = 1;
				}
				
				//直播
				if ($live_host && $is_live && !$return[$i]['ret_live_output_id'])
				{
					$flag = 1;
				}

				//录制
				if ($record_host && $is_record && !$return[$i]['ret_record_output_id'])
				{
					$flag = 1;
				}
			}
			
			if (!$flag)	//更新频道状态
			{
				$update_data = array(
					'id'	 => $id,
					'status' => 0,
				);
				
				$this->mChannel->update($update_data);
				
				$ret = 2;
			}
			else 
			{
				foreach ($channel_stream AS $v)
				{
					$v['code']		= $code;
					$v['is_live']	= $is_live;
					$v['is_record'] = $is_record;
					
					//调用流媒体服务器函数
					$function 	= 'stream_operate_' . $type;
					$return[$k] = $this->$function($server_info, $v, 'start');
				}
			}
		}
		
		$m3u8file = DATA_DIR . $code . '/playlist.m3u8';
		@unlink($m3u8file);
		if ($ret)
		{
			//记录日志
			$pre_data = $channel_info;
			$up_data  = $channel_info;
			if ($ret == 1)
			{
				$up_data['status'] = 1;
			}
			else if ($ret == 2)
			{
				$up_data['status'] = 0;
			}
			$this->mChannel->cache_channel($code);
			
			$this->addLogs('直播频道状态' , $pre_data , $up_data , $channel_info['name'] ,$channel_info['id']);
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 检测wowza服务器是否通路
	 * Enter description here ...
	 * @param unknown_type $server_info
	 * @param unknown_type $super_token
	 */
	private function media_server_wowza($server_info, $super_token)
	{
		$host			= $server_info['host'];
		$input_dir  	= $server_info['input_dir'];
		$output_dir 	= $server_info['output_dir'];
		
		$live_host		= $server_info['live_host'];
		$record_host	= $server_info['record_host'];
		
		$return = array(
			'core_server'	=> 0,
			'live_server'	=> 0,
			'record_server'	=> 0,
		);
		
		$application_data = array(
			'action'	=> 'select',
		);
		
		$ret_select = $this->mLivemms->outputApplicationOperate($host, $output_dir, $application_data);
		
		if (!$ret_select)
		{
			return $return;
		}
		
		$return['core_server'] = 1;
		
		//直播
		if ($live_host)
		{
			$ret_select = $this->mLivemms->outputApplicationOperate($live_host, $output_dir, $application_data);
			
			if (!$ret_select)
			{
				return $return;
			}
			
			$return['live_server'] = 1;
		}
		//录制
		if ($record_host)
		{
			$ret_select = $this->mLivemms->outputApplicationOperate($record_host, $output_dir, $application_data);
			
			if (!$ret_select)
			{
				return $return;
			}
			
			$return['record_server'] = 1;
		}
		
		return $return;
	}
	
	/**
	 * 检测tvie服务器是否通路
	 * Enter description here ...
	 * @param unknown_type $server_info
	 * @param unknown_type $super_token
	 */
	private function media_server_tvie($server_info, $super_token)
	{
		$host			= $server_info['host'];
		$input_dir  	= $server_info['input_dir'];
		$output_dir 	= $server_info['output_dir'];
		
		$live_host		= $server_info['live_host'];
		$record_host	= $server_info['record_host'];
		
		$return = array(
			'core_server'	=> 0,
			'live_server'	=> 0,
			'record_server'	=> 0,
		);
		
		$ret_service 	= $this->getServiceInfo($host, $output_dir, $super_token);
		if (!$ret_service)
		{
			return $return;
		}
		
		$return['core_server'] = 1;
		
		//直播
		if ($live_host)
		{
			$ret_service = $this->getServiceInfo($live_host, $output_dir, $super_token);
			if (!$ret_service)
			{
				return $return;
			}
			
			$return['live_server'] = 1;
		}
		
		//录制
		if ($record_host)
		{
			$ret_service = $this->getServiceInfo($record_host, $output_dir, $super_token);
			if (!$ret_service)
			{
				return $return;
			}
			
			$return['record_server'] = 1;
		}
		
		return $return;
	}
	
	//2013.08.01 scala nginx 
	/*
	 * 检测tvie服务器是否通路
	 * 
	 */
	 private function media_server_nginx($server_info,$super_token)
	 {
	 	$return = array(
			'core_server'	=> 1,
			'live_server'	=> 1,
			'record_server'	=> 1,
		);
		return $return;
	 }
	 //2013.08.01 scala nginx  end
	 
	
	
	/**
	 * wowza启动、停止 流地址
	 * Enter description here ...
	 * @param unknown_type $server_info
	 * @param unknown_type $channel_stream
	 * @param unknown_type $action
	 */
	private function stream_operate_wowza($server_info, $channel_stream, $action)
	{
		$seconds = $this->settings['change_sleep_time'];
		
		$host			= $server_info['host'];
		$input_dir  	= $server_info['input_dir'];
		$output_dir 	= $server_info['output_dir'];
		
		$live_host		= $server_info['live_host'];
		$record_host	= $server_info['record_host'];
		
		$is_live   = $channel_stream['is_live'];
		$is_record = $channel_stream['is_record'];
		
		$return = array(
			'ret_input_id'  		=> 0,
			'ret_delay_id'  		=> 0,
			'ret_change_id' 		=> 0,
			'ret_output_id' 		=> 0,
			'ret_live_output_id' 	=> 0,
			'ret_record_output_id' 	=> 0,
		);
		
		//输入层
		if ($channel_stream['input_id'])
		{
			$input_data = array(
				'action'	=> $action,
				'id'		=> $channel_stream['input_id'],
			);
			
			$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
			
			if (!$ret_input['result'])
			{
				return $return;
			}
			
			$return['ret_input_id'] = $channel_stream['input_id'];
		}
		//延时层
		if ($channel_stream['delay_id'])
		{
			$delay_data = array(
				'action'	=> $action,
				'id'		=> $channel_stream['delay_id'],
			);
			
			$ret_delay = $this->mLivemms->inputDelayOperate($host, $input_dir, $delay_data);
			
			if (!$ret_delay['result'])
			{
				return $return;
			}
			
			$return['ret_delay_id'] = $channel_stream['delay_id'];
		}
		//切播层
		if ($channel_stream['change_id'])
		{
			$change_data = array(
				'action'	=> $action,
				'id'		=> $channel_stream['change_id'],
			);
			
			$ret_change = $this->mLivemms->inputChangeOperate($host, $input_dir, $change_data);
			
			if (!$ret_change['result'])
			{
				return $return;
			}
			
			$return['ret_change_id'] = $channel_stream['change_id'];
			
			if ($action == 'start')
			{
				sleep($seconds);
			}
		}
		//输出层
		if ($channel_stream['output_id'])
		{
			$output_data = array(
				'action'	=> $action,
				'id'		=> $channel_stream['output_id'],
			);
			
			$ret_output = $this->mLivemms->outputStreamOperate($host, $output_dir, $output_data);
			
			if (!$ret_output['result'])
			{
				return $return;
			}
			
			$return['ret_output_id'] = $channel_stream['output_id'];
			
			//直播
			if ($live_host && $is_live)
			{
				$ret_output = $this->mLivemms->outputStreamOperate($live_host, $output_dir, $output_data);
				if (!$ret_output['result'])
				{
					return $return;
				}
				
				$return['ret_live_output_id'] = $channel_stream['output_id'];
			}
			
			//录制
			if ($record_host && $is_record)
			{
				$ret_output = $this->mLivemms->outputStreamOperate($record_host, $output_dir, $output_data);
				if (!$ret_output['result'])
				{
					return $return;
				}
				
				$return['ret_record_output_id'] = $channel_stream['output_id'];
			}
		}
		
		return $return;
	}
	
	/**
	 * tvie启动、停止 流地址 (将flv流地址记录本地库)
	 * Enter description here ...
	 * @param unknown_type $server_info
	 * @param unknown_type $channel_stream
	 * @param unknown_type $action
	 */
	private function stream_operate_tvie($server_info, $channel_stream, $action)
	{
		$seconds = $this->settings['change_sleep_time'];
		
		$host			= $server_info['host'];
		$input_dir  	= $server_info['input_dir'];
		$output_dir 	= $server_info['output_dir'];
		
		$live_host		= $server_info['live_host'];
		$record_host	= $server_info['record_host'];
		
		$is_control   	= $channel_stream['is_control'];
		$delay 			= $channel_stream['delay'];
		$is_live   		= $channel_stream['is_live'];
		$is_record 		= $channel_stream['is_record'];
		
		$return = array(
			'ret_input_id'  		=> 0,
			'ret_delay_id'  		=> 0,
			'ret_change_id' 		=> 0,
			'ret_output_id' 		=> 0,
			'ret_live_output_id' 	=> 0,
			'ret_record_output_id' 	=> 0,
		);
		
		$stream_name = $channel_stream['code'] . '_' . $channel_stream['stream_name'];
		//流名称后缀
		$stream_name_suffix = $stream_name . '/';
		$tvie_data = array(
			'api_token'	=> $this->mApiToken,
		);
		
		//输入层
		if ($is_control || $delay)
		{
			if ($action == 'delete')
			{
				$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . 'input_' . $stream_name_suffix, 'stop', $tvie_data);
			}
			
			$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . 'input_' . $stream_name_suffix, $action, $tvie_data);
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
			$return['ret_input_id'] = $channel_stream['stream_name'];
		}
		//延时层
		if ($delay)
		{
			if ($action == 'delete')
			{
				$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . 'delay_' . $stream_name_suffix, 'stop', $tvie_data);
			}
			
			$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . 'delay_' . $stream_name_suffix, $action, $tvie_data);
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
			$return['ret_delay_id'] = $channel_stream['stream_name'];
		}
		//切播层
		if ($is_control)
		{
			if ($action == 'delete')
			{
				$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . 'change_' . $stream_name_suffix, 'stop', $tvie_data);
			}
			
			$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . 'change_' . $stream_name_suffix, $action, $tvie_data);
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
			$return['ret_change_id'] = $channel_stream['stream_name'];
		}
		//输出层
		if ($action == 'delete')
		{
			$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . 'output_' . $stream_name_suffix, 'stop', $tvie_data);
		}
			
		$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . 'output_' . $stream_name_suffix, $action, $tvie_data);
		if ($ret_tvie['result'] != 'ok')
		{
			return $return;
		}
		$return['ret_output_id'] = $channel_stream['stream_name'];
	
		//取TVIE直播数据 记录flv流地址
		if ($action == 'start')
		{	
			sleep($seconds);
			
			$serach_data = array(
				'name'		=> 'name',
				'value'		=> 'output_' . $stream_name,
			);
			
			$ret_tvie_info = $this->getTvieLiveInfo($host, $input_dir, $serach_data);
			
			$ret_flv_url = $ret_tvie_info['streams'][0]['urls']['flv']['single_bitrate'][0];
			
			if (!empty($ret_flv_url))
			{
				$update_data = array(
					'id'	  => $channel_stream['id'],
					'flv_url' => $ret_flv_url,
				);
				
				$this->mChannel->channel_stream_update($update_data);
			}
		}
		
		//直播
		if ($live_host && $is_live)
		{
			if ($action == 'delete')
			{
				$ret_tvie = $this->mTvie->liveOperate($live_host, $input_dir . 'output_' . $stream_name_suffix, 'stop', $tvie_data);
			}
			
			$ret_tvie = $this->mTvie->liveOperate($live_host, $input_dir . 'output_' . $stream_name_suffix, $action, $tvie_data);
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
			$return['ret_live_output_id'] = $channel_stream['stream_name'];
		}
		
		//录制
		if ($record_host && $is_record)
		{
			if ($action == 'delete')
			{
				$ret_tvie = $this->mTvie->liveOperate($record_host, $input_dir . 'output_' . $stream_name_suffix, 'stop', $tvie_data);
			}
			
			$ret_tvie = $this->mTvie->liveOperate($record_host, $input_dir . 'output_' . $stream_name_suffix, $action, $tvie_data);
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
			$return['ret_record_output_id'] = $channel_stream['stream_name'];
		}

		return $return;
	}
	private function stream_operate_nginx($server_info, $channel_stream, $action)
	{
		$return = array(
			'ret_input_id'	=> 'nginx',
			'ret_delay_id'	=> 'nginx',
			'ret_change_id'	=> 'nginx',
			'ret_output_id'	=> 'nginx',
			'ret_live_output_id'	=> 'nginx',
			'ret_record_output_id'	=> 'nginx',
		);
		$init_data = array(
			'host'=>$server_info['host'],
			'dir' =>$server_info['input_dir'],
		);

		include_once(CUR_CONF_PATH . 'lib/nginx.live.php');
		$postdata = array(
		'app'=>$server_info['output_dir'],
		'name'=>build_nginx_stream_name($channel_stream['code'], $channel_stream['stream_name']),
		);
		$server = new m2oLive();
		$server->init_env($init_data);
		switch($action)
		{
			case 'start':
				{
					$server->start($postdata);
					break;
				}
			case 'stop':
				{
					$server->stop($postdata);
					break;
				}
			case 'restart':
				{
					$server->restart($postdata);
					break;
				}
			default:
				break;
		}
		return $return;
	}
	
	private function set_stream_wowza($server_info, $channel_stream, $is_start = false)
	{
		$host			= $server_info['host'];
		$input_dir  	= $server_info['input_dir'];
		$output_dir 	= $server_info['output_dir'];
		$wowzaip_input 	= $server_info['wowzaip_input'];
		$wowzaip_output	= $server_info['wowzaip_output'];
		
		$live_host		= $server_info['live_host'];
		$record_host	= $server_info['record_host'];
		
		$delay 			= $channel_stream['delay'];
		$is_control 	= $channel_stream['is_control'];
		$is_push 		= $channel_stream['is_push'];
		$url 			= $channel_stream['url'];
		$output_url 	= $url;
		$applicationId 	= $channel_stream['applicationId'];
		$stream_name 	= $channel_stream['stream_name'];
		$code		 	= $channel_stream['code'];
		
		$return = array(
			'ret_input_id'	=> 0,
			'ret_delay_id'	=> 0,
			'ret_change_id'	=> 0,
			'ret_output_id'	=> 0,
			'ret_live_output_id'	=> 0,
			'ret_record_output_id'	=> 0,
		);
		
		//输入层
		if ($delay || $is_control)
		{
			$input_data = array(
				'action'	=> 'insert',
				'url'		=> $url,
				'type'		=> $is_push,
			);
				
			$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
			
			$ret_input_id = $ret_input['input']['id'];
			
			if (!$ret_input['result'])
			{
				return $return;
			}
			
			$return['ret_input_id'] = $ret_input_id;
		}
		//延时层
		if ($delay)
		{
			$delay_data = array(
				'action'	=> 'insert',
				'inputId'	=> $ret_input_id,
				'length'	=> $delay,
			);
			
			$ret_delay = $this->mLivemms->inputDelayOperate($host, $input_dir, $delay_data);
					
			$ret_delay_id = $ret_delay['delay']['id'];

			if (!$ret_delay['result'])
			{
				return $return;
			}
			
			$return['ret_delay_id'] = $ret_delay_id;
			
			$delay_url 	= hg_set_stream_url($wowzaip_input, $this->settings['wowza']['delay']['app_name'], $ret_input_id[$k] . $this->settings['wowza']['delay']['suffix']);
		
			$output_url = $delay_url;
		}
		//切播层
		if ($is_control)
		{
			if ($ret_delay_id)
			{
				$sourceId   = $ret_delay_id;
				$sourceType = 2;
			}
			else 
			{
				$sourceId   = $ret_input_id;
				$sourceType = 1;
			}
			
			$change_data = array(
				'action'		=> 'insert',
				'sourceId'		=> $sourceId,
				'sourceType'	=> $sourceType,
			);

			$ret_change = $this->mLivemms->inputChangeOperate($host, $input_dir, $change_data);

			$ret_change_id = $ret_change['output']['id'];

			if (!$ret_change['result'])
			{
				return $return;
			}
			
			$return['ret_change_id'] = $ret_change_id;
			
			$change_url = hg_set_stream_url($wowzaip_input, $this->settings['wowza']['change']['app_name'], $ret_change_id . $this->settings['wowza']['change']['suffix']);
			
			$output_url = $change_url;
		}
		//输出层
		$output_data = array(
			'action'		=> 'insert',
			'id'			=> 0,
			'applicationId'	=> $applicationId,
			'name'			=> $stream_name,
			'url'			=> $output_url,
		);

		$ret_output = $this->mLivemms->outputStreamOperate($host, $output_dir, $output_data);

		$ret_output_id = $ret_output['stream']['id'];
		
		if (!$ret_output['result'])
		{
			return $return;
		}
		
		$return['ret_output_id'] = $ret_output_id;
		
		//直播、录制 输入流地址
		if ($is_control)
		{
			$output_url_rtmp = hg_set_stream_url($wowzaip_output, $code, $stream_name . $this->settings['wowza']['output']['suffix']);
		}
		else 
		{
			$output_url_rtmp = $url;
		}
		
		//直播
		if ($live_host)
		{
			$output_data['id'] 	= $ret_output_id;
			$output_data['url'] = $output_url_rtmp;
			$ret_live_output = $this->mLivemms->outputStreamOperate($live_host, $output_dir, $output_data);

			$ret_live_output_id = $ret_live_output['stream']['id'];
			
			if (!$ret_live_output['result'])
			{
				return $return;
			}
			
			$return['ret_live_output_id'] = $ret_live_output_id;
		}
		
		//录制
		if ($record_host)
		{
			$output_data['id'] 	= $ret_output_id;
			$output_data['url'] = $output_url_rtmp;
			$ret_record_output = $this->mLivemms->outputStreamOperate($record_host, $output_dir, $output_data);

			$ret_record_output_id = $ret_record_output['stream']['id'];
			
			if (!$ret_record_output['result'])
			{
				return $return;
			}
			
			$return['ret_record_output_id'] = $ret_record_output_id;
		}
		
		return $return;
	}
	
	private function set_stream_tvie($server_info, $channel_stream, $is_start = false)
	{
		$host			= $server_info['host'];
		$input_dir  	= $server_info['input_dir'];
		$output_dir 	= $server_info['output_dir'];
		$wowzaip_input 	= $server_info['wowzaip_input'];
		
		$live_host		= $server_info['live_host'];
		$record_host	= $server_info['record_host'];
		
		$delay 			= $channel_stream['delay'];
		$is_control 	= $channel_stream['is_control'];
		$is_push 		= $channel_stream['is_push'];
		$url 			= $channel_stream['url'];
		$output_url 	= $url;
		$stream_name 	= $channel_stream['stream_name'];
		$status 		= $channel_stream['status'];
		
		$stream_type = $is_push ? 'push' : 'pull';
		$app_name 	 = $this->settings['tvie']['app_name'] ? $this->settings['tvie']['app_name'] : 'live';
		$file 		 = 'add';
		$code		 = $channel_stream['code'];
		$time_shift	 = $channel_stream['time_shift'];
		
		$return = array(
			'ret_input_id'	=> 0,
			'ret_delay_id'	=> 0,
			'ret_change_id'	=> 0,
			'ret_output_id'	=> 0,
			'ret_live_output_id'	=> 0,
			'ret_record_output_id'	=> 0,
		);
		
		$tvie_data = array(
			'type'			=> $stream_type,
			'code'			=> $code,
			'save_time'		=> $time_shift,
			'is_audio'		=> $channel_stream['is_audio'],
		);
		//流名称后缀
		$stream_name_suffix = $code . '_' . $stream_name;
		
		//输入层
		if ($delay || $is_control)
		{
			$tvie_data['stream_name'] = 'input_' . $stream_name_suffix;
			$tvie_data['url'] 		  = $output_url;
			
			$ret_tvie = $this->tvieLiveEdit($host, $input_dir, $file, $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
		
			if ($status && $is_start)
			{
				$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . $tvie_data['stream_name'] . '/', 'start', $tvie_data);
				if ($ret_tvie['result'] != 'ok')
				{
					return $return;
				}
			}
			
			$return['ret_input_id'] = $tvie_data['stream_name'];
			
			$input_url  = hg_set_stream_url($wowzaip_input, $app_name, $tvie_data['stream_name'], 'flv');
			$output_url = $input_url;
		}
		//延时层
		if ($delay)
		{
			$tvie_data['stream_name'] = 'delay_' . $stream_name_suffix;
			$tvie_data['url'] 		  = $output_url;
			
			$ret_tvie = $this->tvieLiveEdit($host, $input_dir, $file, $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
		
			if ($status && $is_start)
			{
				$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . $tvie_data['stream_name'] . '/', 'start', $tvie_data);
				if ($ret_tvie['result'] != 'ok')
				{
					return $return;
				}
			}
			
			$return['ret_delay_id'] = $tvie_data['stream_name'];
			
			$delay_url  = hg_set_stream_url($wowzaip_input, $app_name, $tvie_data['stream_name'], 'flv');
			$output_url = $delay_url;
		}
		//切播层
		if ($is_control)
		{
			$tvie_data['stream_name'] = 'change_' . $stream_name_suffix;
			$tvie_data['url'] 		  = $output_url;
			
			$ret_tvie = $this->tvieLiveEdit($host, $input_dir, $file, $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
		
			if ($status && $is_start)
			{
				$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . $tvie_data['stream_name'] . '/', 'start', $tvie_data);
				if ($ret_tvie['result'] != 'ok')
				{
					return $return;
				}
			}
			
			$return['ret_change_id'] = $tvie_data['stream_name'];
			
			$change_url = hg_set_stream_url($wowzaip_input, $app_name, $tvie_data['stream_name'], 'flv');
			$output_url = $change_url;
		}
		//输出层
		$tvie_data['stream_name'] = 'output_' . $stream_name_suffix;
		$tvie_data['url'] 		  = $output_url;
			
		$ret_tvie = $this->tvieLiveEdit($host, $input_dir, $file, $tvie_data);
			
		if ($ret_tvie['result'] != 'ok')
		{
			return $return;
		}
	
		if ($status && $is_start)
		{
			$tvie_data['api_token'] = $this->mApiToken;
			$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . $tvie_data['stream_name'] . '/', 'start', $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
		}
		
		$return['ret_output_id'] = $tvie_data['stream_name'];
		
		//直播
		if ($live_host)
		{
			//输出层
			$tvie_data = array(
				'type'			=> $stream_type,
				'code'			=> $code,
				'save_time'		=> 0,
				'is_audio'		=> $channel_stream['is_audio'],
			);
			$tvie_data['stream_name'] = 'output_' . $stream_name_suffix;
			$tvie_data['url'] 		  = $url;
			
			$ret_tvie = $this->tvieLiveEdit($live_host, $input_dir, $file, $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
		
			if ($status && $is_start)
			{
				$ret_tvie = $this->mTvie->liveOperate($live_host, $input_dir . $tvie_data['stream_name'] . '/', 'start', $tvie_data);
				if ($ret_tvie['result'] != 'ok')
				{
					return $return;
				}
			}
			
			$return['ret_live_output_id'] = $tvie_data['stream_name'];
		}
		
		//录制
		if ($record_host)
		{
			//输出层
			$tvie_data = array(
				'type'			=> $stream_type,
				'code'			=> $code,
				'save_time'		=> 0,
				'is_audio'		=> $channel_stream['is_audio'],
			);
			$tvie_data['stream_name'] = 'output_' . $stream_name_suffix;
			$tvie_data['url'] 		  = $url;
			
			$ret_tvie = $this->tvieLiveEdit($record_host, $input_dir, $file, $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
		
			if ($status && $is_start)
			{
				$ret_tvie = $this->mTvie->liveOperate($record_host, $input_dir . $tvie_data['stream_name'] . '/', 'start', $tvie_data);
				if ($ret_tvie['result'] != 'ok')
				{
					return $return;
				}
			}
			
			$return['ret_record_output_id'] = $tvie_data['stream_name'];
		}
		
		return $return;
	}
	
	
	
	//2013.08.01 scala set_stream_nginx
	private function set_stream_nginx($server_info, $channel_stream, $is_start = false)
	{
		$return = array(
			'ret_input_id'	=> 'nginx',
			'ret_delay_id'	=> 'nginx',
			'ret_change_id'	=> 'nginx',
			'ret_output_id'	=> 'nginx',
			'ret_live_output_id'	=> 'nginx',
			'ret_record_output_id'	=> 'nginx',
		);
		return $return;
	}
	//2013.08.01 scala set_stream_nginx end 
	
	
	
	private function edit_stream_wowza($server_info, $channel_stream)
	{
		$host			= $server_info['host'];
		$input_dir  	= $server_info['input_dir'];
		$output_dir 	= $server_info['output_dir'];
		$wowzaip_input 	= $server_info['wowzaip_input'];
		
		$live_host		= $server_info['live_host'];
		$record_host	= $server_info['record_host'];
		
		$delay 			= $channel_stream['delay'];
		$is_control 	= $channel_stream['is_control'];
		$is_push 		= $channel_stream['is_push'];
		$url 			= $channel_stream['url'];
		$output_url 	= $url;
		$applicationId 	= $channel_stream['applicationId'];
		$stream_name 	= $channel_stream['stream_name'];
		$is_live 		= $channel_stream['is_live'];
		$is_record 		= $channel_stream['is_record'];
		
		$return = array(
			'ret_input_id'	=> 0,
			'ret_delay_id'	=> 0,
			'ret_change_id'	=> 0,
			'ret_output_id'	=> 0,
			'ret_live_output_id'	=> 0,
			'ret_record_output_id'	=> 0,
		);
		
		//输入层
		if ($is_control || $delay)
		{
			$input_data = array(
				'url'		=> $url,
				'type'		=> $is_push,
			);
			
			if ($channel_stream['input_id'])
			{
				$input_data['action'] = 'update';
				$input_data['id'] 	  = $channel_stream['input_id'];
			}
			else 
			{
				$input_data['action'] = 'insert';
			}
			
			$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
			
			if ($ret_input['input']['id'])
			{
				$ret_input_id_update = $ret_input['input']['id'];
			}
			else 
			{
				$ret_input_id_update = $channel_stream['input_id'];
			}
					
			if (!$ret_input['result'])
			{
				return $return;
			}
			
			$return['ret_input_id'] = $ret_input_id_update;
		}
		else 
		{
			if ($channel_stream['input_id'])
			{
				$input_data = array(
					'action'	=> 'delete',
					'id'		=> $channel_stream['input_id'],
				);
				
				$ret_input = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
						
				if (!$ret_input['result'])
				{
					return $return;
				}
			}
		}
		//延时层
		if ($delay)
		{
			$delay_data = array(
				'inputId'	=> $ret_input_id_update,
				'length'	=> $delay,
			);
			
			if ($channel_stream['delay_id'] && $channel_stream['db_delay'] != $delay)
			{
				$delay_data['action'] = 'delete';
				$delay_data['id'] 	  = $channel_stream['delay_id'];
				
				$ret_delay_delete = $this->mLivemms->inputDelayOperate($host, $input_dir, $delay_data);
				
				if (!$ret_delay_delete['result'])
				{
					return $return;
				}
			}
			
			if ($channel_stream['db_delay'] != $delay)
			{
				$delay_data['action'] = 'insert';
				
				$ret_delay = $this->mLivemms->inputDelayOperate($host, $input_dir, $delay_data);
			
				if ($ret_delay['delay']['id'])
				{
					$ret_delay_id_update = $ret_delay['delay']['id'];
				}
				else 
				{
					$ret_delay_id_update = $channel_stream['delay_id'];
				}

				if (!$ret_delay['result'])
				{
					return $return;
				}
				
				$return['ret_delay_id'] = $ret_delay_id_update;
				
				$delay_url 	= hg_set_stream_url($wowzaip_input, $this->settings['wowza']['delay']['app_name'], $ret_input_id_update . $this->settings['wowza']['delay']['suffix']);
			
				$output_url = $delay_url;
			}
		/*	
			if ($channel_stream['delay_id'])
			{
				$delay_data = array(
					'action'	=> 'stop',
					'id'		=> $channel_stream['delay_id'],
				);
				$ret_delay = $this->mLivemms->inputDelayOperate($host, $input_dir, $delay_data);
			}
		*/
		}
		else 
		{
			if ($channel_stream['delay_id'])
			{
				$delay_data = array(
					'action'	=> 'delete',
					'id'		=> $channel_stream['delay_id'],
				);
				
				$ret_delay = $this->mLivemms->inputDelayOperate($host, $input_dir, $delay_data);
				
				if (!$ret_delay['result'])
				{
					return $return;
				}
			}
		}
		//切播层
		if ($is_control)
		{
			if ($ret_delay_id_update)
			{
				$sourceId   = $ret_delay_id_update;
				$sourceType = 2;
			}
			else 
			{
				$sourceId   = $ret_input_id_update;
				$sourceType = 1;
			}
			
			$change_data = array(
				'sourceId'		=> $sourceId,
				'sourceType'	=> $sourceType,
			);

			if ($channel_stream['change_id'])
			{
				$change_data['action'] = 'update';
				$change_data['id'] 	   = $channel_stream['change_id'];
			}
			else 
			{
				$change_data['action'] = 'insert';
			}
			
			$ret_change = $this->mLivemms->inputChangeOperate($host, $input_dir, $change_data);

			if ($ret_change['output']['id'])
			{
				$ret_change_id_update = $ret_change['output']['id'];
			}
			else 
			{
				$ret_change_id_update = $channel_stream['change_id'];
			}

			if (!$ret_change['result'])
			{
				return $return;
			}
			
			$return['ret_change_id'] = $ret_change_id_update;
			
			$change_url = hg_set_stream_url($wowzaip_input, $this->settings['wowza']['change']['app_name'], $ret_change_id_update . $this->settings['wowza']['change']['suffix']);
			
			$output_url = $change_url;
			
			if ($channel_stream['status'])
			{
				$change_data = array(
					'action'	=> 'stop',
					'id'		=> $ret_change_id_update,
				);
				$ret_change = $this->mLivemms->inputChangeOperate($host, $input_dir, $change_data);
			}
		}
		else 
		{
			if ($channel_stream['change_id'])
			{
				$change_data = array(
					'action'	=> 'delete',
					'id'		=> $channel_stream['change_id'],
				);
				
				$ret_change = $this->mLivemms->inputChangeOperate($host, $input_dir, $change_data);

				if (!$ret_change['result'])
				{
					return $return;
				}
			}
		}
		//输出层
		$output_data = array(
			'action'		=> 'update',
			'id'			=> $channel_stream['output_id'],
			'applicationId'	=> $applicationId,
			'name'			=> $channel_stream['stream_name'],
			'url'			=> $output_url,
		);

		$ret_output = $this->mLivemms->outputStreamOperate($host, $output_dir, $output_data);

		$ret_output_id_update = $channel_stream['output_id'];
		
		if (!$ret_output['result'])
		{
			return $return;
		}
		
		$return['ret_output_id'] = $ret_output_id_update;
		
		//直播
		if ($live_host)
		{
			$output_data['url'] = $url;
			
			if (!$is_live)
			{
				$output_data['action'] = 'insert';
			}
			
			$ret_live_output = $this->mLivemms->outputStreamOperate($live_host, $output_dir, $output_data);

			$ret_live_output_id_update = $channel_stream['output_id'];
			
			if (!$ret_live_output['result'])
			{
				return $return;
			}
			
			$return['ret_live_output_id'] = $ret_live_output_id_update;
		}
		
		//录制
		if ($record_host)
		{
			$output_data['url'] = $url;
		
			if (!$is_record)
			{
				$output_data['action'] = 'insert';
			}
			
			$ret_record_output = $this->mLivemms->outputStreamOperate($record_host, $output_dir, $output_data);

			$ret_record_output_id_update = $channel_stream['output_id'];
			
			if (!$ret_record_output['result'])
			{
				return $return;
			}
			
			$return['ret_record_output_id'] = $ret_record_output_id_update;
		}
	
		return $return;
	}
	
	private function edit_stream_tvie($server_info, $channel_stream)
	{
		$host			= $server_info['host'];
		$input_dir  	= $server_info['input_dir'];
		$output_dir 	= $server_info['output_dir'];
		$wowzaip_input 	= $server_info['wowzaip_input'];
		
		$live_host		= $server_info['live_host'];
		$record_host	= $server_info['record_host'];
		
		$code			= $channel_stream['code'];
		$delay 			= $channel_stream['delay'];
		$is_control 	= $channel_stream['is_control'];
		$is_push 		= $channel_stream['is_push'];
		$url 			= $channel_stream['url'];
		$output_url 	= $url;
		$applicationId 	= $channel_stream['applicationId'];
		$stream_name 	= $channel_stream['stream_name'];
		$is_live 		= $channel_stream['is_live'];
		$is_record 		= $channel_stream['is_record'];
		$time_shift 	= $channel_stream['time_shift'];
		$status		 	= $channel_stream['status'];
		
		$stream_type 	= $is_push ? 'push' : 'pull';
		$app_name 	 	= $this->settings['tvie']['app_name'] ? $this->settings['tvie']['app_name'] : 'live';
		
		$return = array(
			'ret_input_id'	=> 0,
			'ret_delay_id'	=> 0,
			'ret_change_id'	=> 0,
			'ret_output_id'	=> 0,
			'ret_live_output_id'	=> 0,
			'ret_record_output_id'	=> 0,
		);
		
		$tvie_data = array(
			'type'			=> $stream_type,
			'code'			=> $code,
			'save_time'		=> $time_shift,
			'is_audio'		=> $channel_stream['is_audio'],
		);
		
		//流名称后缀
		$stream_name_suffix = $code . '_' . $stream_name;
		
		$file = 'edit';
		
		//输入层
		if ($delay || $is_control)
		{
			$tvie_data['stream_name'] = 'input_' . $stream_name_suffix;
			$tvie_data['url'] 		  = $output_url;
			
			$ret_tvie = $this->tvieLiveEdit($host, $input_dir, $file, $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
			
			$return['ret_input_id'] = $tvie_data['stream_name'];
			
			$input_url  = hg_set_stream_url($wowzaip_input, $app_name, $tvie_data['stream_name'], 'flv');
			$output_url = $input_url;
		}
		//延时层
		if ($delay)
		{
			$tvie_data['stream_name'] = 'delay_' . $stream_name_suffix;
			$tvie_data['url'] 		  = $output_url;
			
			$ret_tvie = $this->tvieLiveEdit($host, $input_dir, $file, $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
			
			$return['ret_delay_id'] = $tvie_data['stream_name'];
			
			$delay_url  = hg_set_stream_url($wowzaip_input, $app_name, $tvie_data['stream_name'], 'flv');
			$output_url = $delay_url;
		}
		//切播层
		if ($is_control)
		{
			$tvie_data['stream_name'] = 'change_' . $stream_name_suffix;
			$tvie_data['url'] 		  = $output_url;
			
			$ret_tvie = $this->tvieLiveEdit($host, $input_dir, $file, $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
			
			$return['ret_change_id'] = $tvie_data['stream_name'];
			
			$change_url = hg_set_stream_url($wowzaip_input, $app_name, $tvie_data['stream_name'], 'flv');
			$output_url = $change_url;
		}
		//输出层
		$tvie_data['stream_name'] = 'output_' . $stream_name_suffix;
		$tvie_data['url'] 		  = $output_url;
		
		$ret_tvie = $this->tvieLiveEdit($host, $input_dir, $file, $tvie_data);
		
		if ($ret_tvie['result'] != 'ok')
		{
			return $return;
		}
		
		$return['ret_output_id'] = $tvie_data['stream_name'];
		
		if ($status)
		{
			$tvie_data['api_token'] = $this->mApiToken;
			$ret_tvie = $this->mTvie->liveOperate($host, $input_dir . $tvie_data['stream_name'] . '/', 'restart', $tvie_data);
		}
		
		//直播
		if ($live_host)
		{
			//输出层
			$tvie_data = array(
				'type'			=> $stream_type,
				'code'			=> $code,
				'save_time'		=> 0,
				'is_audio'		=> $channel_stream['is_audio'],
			);
			$tvie_data['stream_name'] = 'output_' . $stream_name_suffix;
			$tvie_data['url'] 		  = $url;
			
			if (!$is_live)
			{
				$file = 'add';
			}
			
			$ret_tvie = $this->tvieLiveEdit($live_host, $input_dir, $file, $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
			
			$return['ret_live_output_id'] = $tvie_data['stream_name'];
		}
		
		//录制
		if ($record_host)
		{
			//输出层
			$tvie_data = array(
				'type'			=> $stream_type,
				'code'			=> $code,
				'save_time'		=> 0,
				'is_audio'		=> $channel_stream['is_audio'],
			);
			$tvie_data['stream_name'] = 'output_' . $stream_name_suffix;
			$tvie_data['url'] 		  = $url;
			
			if (!$is_record)
			{
				$file = 'add';
			}
			
			$ret_tvie = $this->tvieLiveEdit($record_host, $input_dir, $file, $tvie_data);
			
			if ($ret_tvie['result'] != 'ok')
			{
				return $return;
			}
			
			$return['ret_record_output_id'] = $tvie_data['stream_name'];
		}
	
		return $return;
	}
	
	private function edit_stream_nginx($server_info, $channel_stream)
	{
		$return = array(
			'ret_input_id'	=> 'nginx',
			'ret_delay_id'	=> 'nginx',
			'ret_change_id'	=> 'nginx',
			'ret_output_id'	=> 'nginx',
			'ret_live_output_id'	=> 'nginx',
			'ret_record_output_id'	=> 'nginx',
		);
		return $return;
	}
	/**
	*  发布系统，将内容传入发布队列
	*  
	*/	
	public function publish_insert_query($id,$op,$user_name,$column_id = array(),$child_queue = 0)
	{
		$info = $this->mChannel->get_channel_by_id($id);
		if(empty($column_id))
		{		
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
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
			'publish_time'  	=> TIMENOW,
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
	
	/**
	 * 即时发布
	 * param id  int   
	 * param column_id string  发布的栏目id
	 */
	public function publish()
	{
		$id = intval($this->input['id']);
		$column_id = trim($this->input['column_id']);
		
		if(!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		$new_column_id = explode(',',$column_id);
		$column_id = $this->mPublishColumn->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
	
		//查询修改文章之前已经发布到的栏目
		$info = $this->mChannel->get_channel_by_id($id);

		$ori_column_id = array();
		if(is_array($info['column_id']))
		{
			$ori_column_id = array_keys($info['column_id']);
		}
		
		$update_data = array(
			'id'			 => $id,
			'column_id'		 => $column_id,
		);
		//更新数据
		$ret = $this->mChannel->update($update_data);
		
		if(!empty($info['expand_id']))   //已经发布过，对比修改先后栏目
		{
			$del_column = array_diff($ori_column_id,$new_column_id);
			if(!empty($del_column))
			{
				$this->publish_insert_query($id, 'delete',$del_column);
			}
			$add_column = array_diff($new_column_id,$ori_column_id);
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
		
		if(empty($ret))
		{
			$this->errorOutput('发布失败');
		}
	
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 调用分类数据
	 */
	function channel_node_show()
	{
		$id		 	= intval($this->input['id']);
		$node_id 	= intval($this->input['node_id']);
		
		$sql = "SELECT id, name FROM " . DB_PREFIX . "channel_node WHERE 1 ORDER BY order_id ASC ";
		$q = $this->db->query($sql);
		$channel_node = array();
		while ($row = $this->db->fetch_array($q))
		{
			$channel_node[] = $row;
		}
		
		$ret = array(
			'id' 	  		=> $id,
			'node_id' 		=> $node_id,
			'channel_node'	=> $channel_node,
		);

		$this->addItem($ret);
		$this->output();
	}
	/**
	 * 分类更新
	 * Enter description here ...
	 */
	public function channel_node_edit()
	{
		$prmsmap = array(
		'_action'=>'update',
		);
		$this->verify_content_prms($prmsmap);
		$id		 	= intval($this->input['id']);
		$node_id 	= intval($this->input['node_id']);
		$node_name 	= trim(urldecode($this->input['node_name']));
		
		if (!$id)
		{
			$this->errorOutput('未传入频道id');
		}
		
		if (!$node_id)
		{
			$this->errorOutput('未传入分类id');
		}
		
		$sql = "UPDATE " . DB_PREFIX . "channel SET node_id = " . $node_id . " WHERE id = " . $id;
		if ($this->db->query($sql))
		{	
			$ret = array(
				'result'	=> 1,
				'id' 	  	=> $id,
				'node_id' 	=> $node_id,
				'node_name'	=> $node_name,
			);
		}
		else 
		{
			$sql = "SELECT c.node_id, cn.name AS node_name FROM " . DB_PREFIX . "channel c ";
			$sql.= " LEFT JOIN " . DB_PREFIX . "channel_node cn ON c.node_id=cn.id ";
			$sql.= " WHERE c.id = " . $id;
			$channel_info = $this->db->query_first($sql);
			
			$ret = array(
				'result'	=> 0,
				'id' 	  	=> $id,
				'node_id' 	=> $channel_info['node_id'],
				'node_name'	=> $channel_info['node_name'],
			);
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_stream_count()
	{
		$server_id = intval($this->input['server_id']);
		if ($server_id)
		{
			$server_field = 'id, counts, type';
			$server_info = $this->mServerConfig->get_server_config_by_id($server_id, $server_field);
		}
		
		//服务器最大信号数目
		$counts 	  = $server_info['counts'] ? $server_info['counts'] : $this->settings['wowza']['counts'];
		//已用信号数目
		$stream_count = $this->mChannel->get_stream_count($server_id);
		//剩余信号数目
		$over_count   = $counts - $stream_count;
	
		$return = array(
			'over_count' => $over_count,
			'type' 		 => $server_info['type'],
		);
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 获取TVIE直播流信息 (支持 display、name)
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $dir
	 * @param unknown_type $tvie_data
	 * @param unknown_type $output_urls
	 * @param unknown_type $exact_match
	 * @param unknown_type $exact_match
	 */
	public function getTvieLiveInfo($host, $dir, $tvie_data, $output_urls = true, $exact_match = true)
	{
		$page_size 	= $tvie_data['page_size'] ? $tvie_data['page_size'] : 1;
		$offset 	= $tvie_data['offset'] ? $tvie_data['offset'] : 0;
		$name		= $tvie_data['name'] ? $tvie_data['name'] : 'name';
		$value		= $tvie_data['value'];
		
		$data = array(
			'api_token' 	=> $this->mApiToken,
			'page_size' 	=> $page_size,
			'offset' 		=> $offset,
			'output_urls' 	=> $output_urls,
			'query' 		=> array(
				'type'	=> 'item',
				'item'	=> array(
					'name'		  => $name,
					'value'		  => $value,
					'exact_match' => $exact_match,
				),
			),
			'order_by'		=> 'name',
			'order_type'	=> 'desc',
		);
		
		$return = $this->mTvie->getLiveSearch($host, $dir, $data);
		return $return;
	}
	
	/**
	 * tvie直播流编辑 (add、edit)
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $input_port
	 * @param unknown_type $dir
	 * @param unknown_type $file
	 * @param unknown_type $tvie_data
	 */
	public function tvieLiveEdit($host, $dir, $file, $tvie_data)
	{
		$type 		  = $tvie_data['type'];
		$code 		  = $tvie_data['code'];
		$name 		  = $tvie_data['stream_name'];
		$url 		  = ($type == 'push') ? '' : $tvie_data['url'];
		$save_time	  = intval($tvie_data['save_time']);
		$emulate_rate = intval($tvie_data['emulate_rate']);
		
		$pubpoints_protocol = $this->settings['tvie']['pubpoints']['protocol'] ? $this->settings['tvie']['pubpoints']['protocol'] : 'http://';
		$pubpoints_host 	= $this->settings['tvie']['pubpoints']['host'] ? $this->settings['tvie']['pubpoints']['host'] : '127.0.0.1';
		$pubpoints_port 	= $this->settings['tvie']['pubpoints']['port'] ? $this->settings['tvie']['pubpoints']['port'] : '10080';
		$pubpoints_dir 		= $this->settings['tvie']['pubpoints']['dir'] ? $this->settings['tvie']['pubpoints']['dir'] : 'live/';
		$pubpoints_suffix 	= $this->settings['tvie']['pubpoints']['suffix'] ? $this->settings['tvie']['pubpoints']['suffix'] : 'live.ismv';
		
		$pubpoints 	= array($pubpoints_protocol . $pubpoints_host . ':' . $pubpoints_port . '/' . $pubpoints_dir . $name . '/' . $pubpoints_suffix);
		$upstreams 	= array($url);
		
		if ($file == 'edit')
		{
			$dir = $dir . $name . '/';
		}
		
		$data = array(
			'api_token' 	=> $this->mApiToken,
			'type' 			=> $type,//流类型, 推送或拉取 push, pull
			'display_name' 	=> $code,//显示名称
			'auto_recover' 	=> false,//是否自动恢复
			'name' 			=> $name,//流名称
			'pubpoints' 	=> $pubpoints,//发布点
			'emulate_rate' 	=> !$emulate_rate ? false : true,//模拟码率 true=>文件流, false=>直播流
			'save_time' 	=> $save_time * 3600,//时移数据保存时间 秒
			'stream_map' 	=> '0:0:0;0:1:1',//0:0:0;0:1:1
			'upstreams' 	=> $upstreams,//上游地址
			'username' 		=> '',
			'audio_only' 		=> $tvie_data['is_audio'] ? true : false,
			'password' 		=> '',
			'start_on_server_startup' => false,//是否在推送流服务启动时启动
		);
		
		$return = $this->mTvie->liveEdit($host, $dir, $file, $data);
		return $return;
	}
	
	/**
	 * 检测TVIE直播服务器是否开启
	 * 返回 $api_token
	 * Enter description here ...
	 * @param unknown_type $host
	 * @param unknown_type $dir
	 * @param unknown_type $super_token
	 */
	public function getServiceInfo($host, $dir, $super_token)
	{
		//$tvie_dir		= $v['output_dir'] ? $v['output_dir'] : 'mediaserver/service/';
		$api_token = $this->getApiToken($host, $super_token);
		
		$this->mApiToken = $api_token;
		
		$tvie_data = array(
			'api_token'	=> $this->mApiToken,
		);
		
		$ret_tvie_server = $this->mTvie->getServiceInfo($host, $dir, $tvie_data);
		
		if ($ret_tvie_server['info']['media_server']['live'] == 'enabled')
		{
			return true;
		}
		else 
		{
			return false;
		}
	}

	private function getApiToken($host, $super_token)
	{
		$api_token_dir = $this->settings['tvie']['api_token_dir'] ? $this->settings['tvie']['api_token_dir'] : 'server/api_token/';
		$api_token = $this->mTvie->getApiToken($host, $api_token_dir, $super_token);
		$api_token = $api_token['api_token'];
		return $api_token;
	}
	private function create_schedule_control($data = array())
	{
		if(empty($data))
		{
			return false;
		}
		$application_data = array(
			'action'		=> 'insert',
			'sourceId'		=> '',
			'sourceType'	=> 1,
		);
		//file_put_contents(CACHE_DIR . 'debug2.txt', var_export($data, 1));
		$host = $this->settings['schedule_control_wowza']['host'];
		$inputdir = $outputdir = $this->settings['schedule_control_wowza']['inputdir'];
		//file_put_contents(CACHE_DIR . 'debug3.txt', $host . $inputdir);
		
		$in1  = $this->mLivemms->inputStreamOperate($host,$inputdir,$data);
		$stream_id1  = $application_data['sourceId'] = $in1['input']['id'];
		$this->mLivemms->inputStreamOperate($host,$inputdir,array('action'=>'start', 'id'=>$stream_id1));
		
		$out1 = $this->mLivemms->inputOutputStreamOperate($host,$outputdir,$application_data);
		$output_id1 = $out1['output']['id'];
		$this->mLivemms->inputOutputStreamOperate($host,$outputdir,array('action'=>'start', 'id'=>$output_id1));
		
		$data['url'] = "rtmp://".str_replace(':8086', '', $host)."/input/".$output_id1.".output";
		
		$in2  = $this->mLivemms->inputStreamOperate($host,$inputdir,$data);
		$stream_id2 = $application_data['sourceId'] = $in2['input']['id'];
		$this->mLivemms->inputStreamOperate($host,$inputdir,array('action'=>'start', 'id'=>$stream_id2));
		
		$out2 = $this->mLivemms->inputOutputStreamOperate($host,$outputdir,$application_data);
		$output_id2 = $out2['output']['id'];
		$this->mLivemms->inputOutputStreamOperate($host,$outputdir,array('action'=>'start', 'id'=>$output_id2));
		
		$return =  array(
			'schedule' =>array('stream_id'=>$stream_id1,'output_id'=>$output_id1),
			'control'=>array('stream_id'=>$stream_id2,'output_id'=>$output_id2),
		);
		//file_put_contents(CACHE_DIR . 'debug1.txt', var_export($return, 1));
		return $return;
	}
	public function schedule_syn_channel_server($_schedule_control, $channel_id)
	{
		if($this->settings['App_schedule'] && $this->settings['schedule_control_wowza']['is_wowza'])
		{
			if(!$_schedule_control['schedule'])
			{
				return;
			}
			require_once(ROOT_PATH . 'lib/class/curl.class.php');
			$schedule_api = new curl($this->settings['App_schedule']['host'],$this->settings['App_schedule']['dir']);
			$schedule_api->initPostData();
			$schedule_api->addRequestData('channel_id', $channel_id);
			foreach($_schedule_control['schedule'] as $key=>$val)
			{
				$schedule_api->addRequestData($key, $val);
			}
			$schedule_api->addRequestData('a', 'build_wowza_schedule');
			$is_syn = $schedule_api->request('schedule.php');
			if($is_syn[0]!=='success')
			{
				file_put_contents(CACHE_DIR .  'debug.txt', var_export($is_syn,1));
			}
		}
	}
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
	
}
$out = new channelUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>