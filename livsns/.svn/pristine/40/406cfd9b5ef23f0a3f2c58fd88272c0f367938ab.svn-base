<?php
require('global.php');
define('MOD_UNIQUEID','live_time_shift');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/live.class.php');
require_once(CUR_CONF_PATH . 'lib/SelectTimeShiftServer.php');
require_once(CUR_CONF_PATH . 'lib/live_time_shift_mode.php');
class live_time_shift_update extends adminUpdateBase
{
	private $mLive;
	private $curl;
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mLive = new live();
		$this->mode = new live_time_shift_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		/*************权限控制***********************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$prms['_action'] = 'create';
			if(!$this->input['item'] || intval($this->input['item']) == -1)
			{
				$this->input['item'] = 3;//不存在默认给的分类是直播归档
			}
			$prms['node'] = $this->input['item'];
			$this->verify_self_prms($prms);
		}
		/*************权限控制***********************************************/
		
		if(!$this->input['channel_id'])
		{
			$this->errorOutput(NOID);
		}
		
		/*************选择时移服务器******************************************/
		$shiftServer = new SelectTimeShiftServer();
		$serverSelected = $shiftServer->select();
		if(!$serverSelected)
		{
			$this->errorOutput('没有可选择的时移服务器');
		}
		/*************选择时移服务器******************************************/
		
		/*************检测频道流信息******************************************/
		$condition['id'] = $this->input['channel_id'];
		$condition['fetch_live'] = 1;
		$channel = $this->mLive->getChannelInfo($condition);
		$channel = $channel[0];
		if(!$channel)
		{
			$this->errorOutput('该频道已经不存在！');
		}
		
		if(!$channel['status'])
		{
			$this->errorOutput('该频道流未开启');
		}
		
		if(!$channel['is_mobile_phone'])
		{
			$this->errorOutput('该频道未启动手机流，无法获取时移数据！');
		}
		/*************监测频道流信息******************************************/
		
		/*************时移时间正确性监测***************************************/	
		$ret = array();
		$ret['title'] 		= $this->input['title']?$this->input['title']:'精彩节目';
		$ret['starttime'] 	= strtotime(trim($this->input['start_time']));
		$ret['endtime'] 	= strtotime(trim($this->input['end_time']));
		$ret['duration']	= $ret['endtime'] - $ret['starttime'];
		if($ret['starttime'] >= $ret['endtime'])
		{
			$this->errorOutput('时间设置不正确！');
		}
		
		$save_time = TIMENOW-(($channel['time_shift']*3600)-($channel['delay']));
		if($ret['starttime'] < $save_time)
		{
			$this->errorOutput('此条时移已超过回看时间！');
		}
		
		if($ret['endtime'] > TIMENOW)
		{
			$this->errorOutput('时移节目的结束时间必须小于当前时间！');
		}
		/*************时移时间正确性监测***************************************/
		
		/*************默认类别***********************************************/
		if(intval($this->input['item']) == -1)
		{
			$this->input['item'] = 3;//如果没有传类别默认给直播归档的类别
		}
		/*************默认类别***********************************************/
		
		/*********************记录一条时移日志*******************************/
		$shift_log = array(
			'title' 		=> $ret['title'],
			'channel_id'	=> $this->input['channel_id'],
			'starttime' 	=> $ret['starttime'],
			'endtime'		=> $ret['endtime'],
			'live_split_callback' => (int)$this->input['live_split_callback'],
			'status'		=> 2,//时移中
			'create_time' 	=> TIMENOW,
			'user_id' 		=> $this->user['user_id'],
			'user_name' 	=> $this->user['user_name'],
		);
		$shiftLogInfo = $this->mode->create($shift_log);
		$this->addLogs('记录时移日志', '', $shiftLogInfo,$shiftLogInfo['title']);
		/*********************记录一条时移日志*******************************/
		
		/*********************向时移服务器提交数据****************************/
		$duration = $ret['duration'] .'000';
		$starttime = $ret['starttime'] . '000';
		if($channel['server_type'] != 'nginx')
		{
			if (strstr($channel['channel_stream'][0]['m3u8'], '?'))
			{
				$sp = '&';
			}
			else
			{
				$sp = '?';
			}
			$url = $channel['channel_stream'][0]['m3u8'] . $sp . 'dvr&duration='.$duration . '&starttime='.$starttime;
		}
		else 
		{
			$pathinfo = pathinfo($channel['channel_stream'][0]['live_m3u8']);
			$url = $pathinfo['dirname'] . '/' . $channel['main_stream_name'] . '/' . $starttime . ',' . $duration . '.m3u8' ;
		}

		$callback = $this->input['callback_url'] ? trim($this->input['callback_url']) : $this->settings['App_live_time_shift']['protocol'] . $this->settings['App_live_time_shift']['host'] .'/'. $this->settings['App_live_time_shift']['dir'] . 'admin/live_time_shift_callback.php';
		//构建提交的数据
		$data = array(
			'id'     			 => $shiftLogInfo['id'],
			'time_shift'         => '1',
			'action'			 => 'TIMESHIFT',
			'url'   	 		 => urlencode($url),
			'callback' 			 => $callback,
			'uploadFile'		 => '0',
			'appid'				 => $this->input['appid'],
			'appkey'			 => $this->input['appkey'],
			'access_token'		 => $this->user['token'],
			'vod_sort_id'   	 => $this->input['item'],
		);
		
		//额外传递的参数
		$extend_data = $this->input['dataextend'] ? $this->input['dataextend'] :array(
			'_user_id'  	 	 => $this->user['user_id'],
			'_user_name' 		 => $this->user['user_name'],
			'audit_auto' 		 => $this->input['audit_auto'],//时移之后视频的状态
		    'column_id' 		 => $this->input['column_id'],//发布的栏目
		    'channel_id' 		 => $this->input['channel_id'],//频道id
			'force_recodec' 	 => $this->input['force_codec'],//是否强制转码
			'is_mark' 			 => !$this->input['is_mark'],//是否允许拆条
			'starttime'     	 => $this->input['start_time'],
			'delay_time'    	 => $channel['time_shift'],
			'title'  			 => $ret['title'],
			'program'			 => $ret['title'],
		);
		$data['extend'] = base64_encode(json_encode($extend_data));
		//开始提交
		$this->curl = new curl($serverSelected['host'] . ':' .$serverSelected['port']);
		foreach($data AS $k => $v)
		{
			$this->curl->addRequestData($k,$v);
		}
		$shift_xml = $this->curl->request('');
		$xmlobj = @simplexml_load_string($shift_xml);
		if(!$xmlobj || !$xmlobj->attributes()->result)
		{
			$this->mode->update($shiftLogInfo['id'],array('status' => 0));//失败了更新状态
			writeErrorLog("提交时移服务器失败:\n" . var_export($xmlobj,1));
			$this->errorOutput('提交时移服务器失败');
		}
		/*********************向时移服务器提交数据****************************/
		if($this->input['outputtype'] == 1)
		{
			$this->addItem($shiftLogInfo);
		}
		else if (! $this->input['outputtype'])
		{
			$this->addItem('success');
		} 
		$this->output();
	}
	
	//获取权限允许的节点
	private function get_childs_nodes()
	{
		$prms_nodes = implode(',',$this->user['prms']['app_prms']['livmedia']['nodes']);
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
    	$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$prms_nodes);
		$curl->addRequestData('a','get_childs_nodes');
		$nodes = $curl->request('vod.php');
		return $nodes[0];
	}
	
	private function verify_self_prms($data = array())
	{
		$action  = $data['_action'] ? $data['_action'] : $this->input['a'];
		if ($this->user['user_id'] < 1)
		{
			$this->errorOutput(USER_NOT_LOGIN);
		}
		
		if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			return;
		}
		
		if(!in_array($action,(array)$this->user['prms']['app_prms']['livmedia']['action']))
		{
			$this->errorOutput(NO_PRIVILEGE);
		}
		
		if($data['id'])
		{
			$manage_other_data = $this->user['prms']['default_setting']['manage_other_data'];
			if(!$manage_other_data)
			{
				if($this->user['user_id'] != $data['user_id'])
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
			//1 代表组织机构以内
			if($manage_other_data == 1 && $this->user['slave_org'])
			{
				if(!in_array($data['org_id'], explode(',', $this->user['slave_org'])))
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
		}
		
		if($data['node'])
		{
			$auth_prms_nodes = $this->get_childs_nodes();
			if(!in_array($data['node'],$auth_prms_nodes))
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除时移日志',$ret,'','删除时移日志' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new live_time_shift_update();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>