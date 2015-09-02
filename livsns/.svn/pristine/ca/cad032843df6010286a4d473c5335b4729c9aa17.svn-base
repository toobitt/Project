<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 6066 2012-03-12 02:32:09Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','message');
require('./global.php');
require(CUR_CONF_PATH."lib/push_notify.class.php");
class PushMes extends adminReadBase
{
	function __construct()
	{
		$this->mPrmsMethods = array(
		'notice_manage'=>'消息管理',
		'_node'=>array(
			'name'=>'应用名称',
			'filename'=>'push_message_node.php',
			'node_uniqueid'=>'push_message_node',
			),
		);
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function index()
	{
		
	}
	public function show()
	{
		$this->verify_content_prms(array('_action'=>'notice_manage'));
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY create_time desc ';
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT * FROM ' . DB_PREFIX . "advices WHERE 1".$this->get_condition().$orderby.$limit;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if($r['create_time'])
			{
				$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			}
			if($r['send_time'])
			{
				$r['send_time'] = date('Y-m-d H:i',$r['send_time']);
			}
			if($r['device_create_time'])
			{
				$r['device_create_time'] = date('Y-m-d H:i',$r['device_create_time']);
			}
			else 
			{
				$r['device_create_time'] = '';
			}
			if($r['device_update_time'])
			{
				$r['device_update_time'] = date('Y-m-d H:i',$r['device_update_time']);
			}
			else 
			{
				$r['device_update_time'] = '';
			}
			
			if($r['state'] == '1')
			{
				$r['audit'] = '已审核';
			}
			else if($r['state'] == '2')
			{
				$r['audit'] = '已打回';
			} 
			else 
			{
				$r['audit'] = '待审核';
			}
			$this->addItem($r);
		}
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		//权限判断
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND org_id IN ('.$this->user['slave_org'].')';
			}
			
			$nodes = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($nodes)
			{
				$condition .= ' AND appid IN ('.implode(',', $nodes).')';
			}
		}
		if($this->input['k'])
		{
			$condition .= ' AND message LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval($this->input['id']);
		}
		
		if($this->input['_id'])
		{
			if($this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				$nodes = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
				if($nodes && in_array($this->input['_id'], $nodes))
				{
					$condition .= ' AND appid = '.intval($this->input['_id']);
				}
			}
			else 
			{
				$condition .= ' AND appid = '.intval($this->input['_id']);
			}
		}
		if($this->input['advice_status'] == 1)
		{
			$condition .= ' AND state = 0';
		}
		else if($this->input['advice_status'] == 2)
		{
			$condition .= ' AND state = 1';
		}
		else if($this->input['advice_status'] == 3)
		{
			$condition .= ' AND is_send = 1';
		}
		else if($this->input['advice_status'] == 4)
		{
			$condition .= ' AND is_send = 0';
		}
		
		if($this->input['user_name'])
		{
			$condition .= " AND username = '".$this->input['user_name']."'";
		}
		return $condition;
	}
	function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."advices WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	public function api_show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY create_time desc ';
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT * FROM ' . DB_PREFIX . "advices WHERE state = 1 AND id >".$this->input['id'].$orderby.$limit;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if($r['create_time'])
			{
				$r['create_time'] = date('Y-m-d H:i:s',$r['create_time']);
			}
			if($r['send_time'])
			{
				$r['send_time'] = date('Y-m-d H:i:s',$r['send_time']);
			}
			if($r['device_create_time'])
			{
				$r['device_create_time'] = date('Y-m-d H:i:s',$r['device_create_time']);
			}
			else 
			{
				$r['device_create_time'] = '';
			}
			if($r['device_update_time'])
			{
				$r['device_update_time'] = date('Y-m-d H:i:s',$r['device_update_time']);
			}
			else 
			{
				$r['device_update_time'] = '';
			}
			$this->addItem($r);
		}
		$this->output();
	}
	public function api_count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."advices WHERE state=1 AND id > ".$this->input['id'];
		echo json_encode($this->db->query_first($sql));
	}
	//拉取消息
	function device_message()
	{
		$device_token = $this->input['device_token'];
		if(!$device_token)
		{
			$this->errorOutput('没有设备标识');
		}
		$appid = intval($this->input['appid']);
		if(!$appid)
		{
			$this->errorOutput('没有应用id');
		}
		//查询设备上次拉取消息的时间
		$sql = 'SELECT push_time FROM '.DB_PREFIX.'device_advice_record WHERE appid='.$appid.' AND device_token="'.$device_token.'"';
		$time = $this->db->query_first($sql);
		$push_time = $time['push_time'];
		
		//消息状态为1，发送方式为拉的
		$cond .= ' AND a.state=1 AND a.send_way = 0 AND a.send_time <='.TIMENOW;
		//发送时间大于上条消息发送时间的消息
		if($push_time)
		{
			$cond .= ' AND a.send_time>'.$push_time;
		}
		
		//查询设备信息
		$sql = 'SELECT system,types,debug,program_name FROM '.DB_PREFIX.'device WHERE appid='.$appid.' AND device_token="'.$device_token.'"';
		$res = $this->db->query_first($sql);
		
		//查询满足设备条件的消息，发送时间升序排序
		$sql = 'SELECT a.id,a.message,a.device_token,a.content_id,a.sound,a.debug,a.device_os,a.device_type,a.client,a.send_time,m.module_id 
				FROM '.DB_PREFIX.'advices a 
				LEFT JOIN ' . DB_PREFIX . 'mobile_module m 
				ON a.link_module=m.id 
				WHERE 1 ' . $cond . ' ORDER BY a.send_time ASC';
		//echo $sql;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			//如果消息中有token记录，判断与设备token是否相等，不等跳过
			if ($r['device_token'])
			{
				if($r['device_token'] != $device_token)
				{
					continue;
				}
			}
			//如果消息有程序版本限制，判断设备程序版本是否符合要求，不符合跳过
			if($r['client'])
			{
				$client = explode(',', $r['client']);
				if(!in_array($res['program_name'], $client))
				{
					continue;
				}
			}
			//如果消息有系统限制，判断设备系统是否符合要求，不符合跳过
			if($r['device_os'])
			{
				$device_os = explode(',', $r['device_os']);
				if(!in_array($res['system'], $device_os))
				{
					continue;
				}
			}
			//如果消息有设备类型限制，判断设备设备类型是否符合要求，不符合跳过
			if($r['device_type'])
			{
				$device_type = explode(',', $r['device_type']);
				if(!in_array($res['types'], $device_type))
				{
					continue;
				}
			}
			//如果消息有对开发版，应用版限制，判断设备系统版本是否符合要求
			if($res['debug'] == $r['debug'] || $r['debug'] == -1)
			{
				//设备信息满足消息所有要求，返回消息，并将消息的发送时间存入记录表
				$sql = 'REPLACE INTO '.DB_PREFIX.'device_advice_record SET appid='.$appid.',device_token="'.$device_token.'",push_time='.$r['send_time'];
				$this->db->query($sql);
				$this->addItem($r);
				$this->output();
			}
		}
	}
	function detail()
	{
		$this->verify_content_prms(array('_action'=>'notice_manage'));
		
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$condition .= ' AND enabled = 1 ';
		$this->show();
	}
	
	//手动发布消息
	public function release_mes()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('id不存在!');
		}
		if(!$this->input['state'])
		{
			$this->errorOutput('需审核过才能发送');
		}
		if(!$this->input['message'])
		{
			$this->errorOutput('请设置要发送的信息');
		}
		$message = urldecode($this->input['message']);
		$config = array(
			'publish' => $this->input['publish'],
			'badge' => $this->input['badge'],
			'sound' => $this->input['sound'],
			'type' => $this->input['type'],
		);
		$pushNotify = new pushNotify($config);
		$pushNotify->connectToAPNS();
		$sql = 'SELECT * FROM ' . DB_PREFIX . "device WHERE enabled = 1";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$ret = $pushNotify->send($r['device_token'], $message);
			$this->addItem(array('deviceToken' => $r['device_token'], 'ret' => $ret));
		}
		//更新消息表中消息发送状态
		$up_sql = 'UPDATE '.DB_PREFIX.'advices SET is_send=1 WHERE id=' . $this->input['id'];
		$this->db->query($up_sql);
		$pushNotify->closeConnections();
		$this->output();
	}
	//获取模块信息
	public function append_module()
	{
		$sql = "SELECT module_id,name FROM " . DB_PREFIX . "mobile_module";
		$g = $this->db->query($sql);
		while($j = $this->db->fetch_array($g))
		{
			$this->addItem($j);
		}
		$this->output();
	}
	//推送客户端数据
	public function append_device()
	{
		$sql = "SELECT device_token FROM " . DB_PREFIX . "device WHERE enabled = 1";
		$g = $this->db->query($sql);
		while($j = $this->db->fetch_array($g))
		{
			$this->addItem($j);
		}
		$this->output();
	}
	//ajax获得app_id下的types和system
	function get_types_system()
	{
		$app_id = intval($this->input['app_id']);
		//types
		$sql = "SELECT id,device_name FROM " . DB_PREFIX . "device_library WHERE FIND_IN_SET(".$app_id.",app_id) ORDER BY device_name";
		$types = $this->db->query($sql);
		while($j = $this->db->fetch_array($types))
		{
			$arr['types'][] = array('id'=>$j['id'],'name'=>$j['device_name']);
		}
		
		//system
		$sql = "SELECT id,device_os FROM " . DB_PREFIX . "device_os WHERE FIND_IN_SET(".$app_id.",app_id) ORDER BY device_os";
		$g = $this->db->query($sql);
		while($r = $this->db->fetch_array($g))
		{
			$arr['system'][] = array('id'=>$r['id'],'name'=>$r['device_os']);
		}
		//send_way
		$sql = 'SELECT send_way FROM '.DB_PREFIX.'certificate WHERE appid='.$app_id;
		$res = $this->db->query_first($sql);
		$arr['send_way'] = $res['send_way'];
		
		$this->addItem($arr);
		$this->output();
	}
	
	public function get_publish_content()
	{	
		include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->puscont = new publishcontent();
		
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 10;
		$offset = intval(($pp - 1)*$count);			
		$data = array(
			'offset'	  		=> $offset,
			'count'		  		=> $count,
			'client_type'		=>	'2',
			'need_count'		=> '1',
		);
		if ($this->input['site_id'])
		{
			$data['site_id'] = intval($this->input['site_id']);
		}
		
		if($this->input['info'])
		{
			foreach($this->input['info'] as $k=>$v)
			{
				$info[$v['name']] = $v['value'];
			}
		}
		//查询
		if($this->input['link_module'])
		{
			
			$sql = "SELECT module_id FROM ".DB_PREFIX."mobile_module WHERE id = ".intval($this->input['link_module']);
			$res = $this->db->query_first($sql);
			
			$module = $res['module_id'];
			$data['bundle_id'] = $module;
		}

		//查询时间
		if($info['date_search'])
		{
			$data['date_search'] = $info['date_search'];
		}
		
		//查询标题
		if($info['k'])
		{
			$data['k'] = $info['k'];
		}
		
		//查询创建的起始时间
		if($info['start_time'])
		{
			$data['starttime'] = $info['start_time'];
		}
		
		//查询创建的结束时间
		if($info['end_time'])
		{
			$data['endtime'] = $info['end_time'];
		}
		
		//查询权重
		if(isset($info['start_weight']) && intval($info['start_weight'])>=0)
		{
			$data['start_weight'] = $info['start_weight'];
		}
		if(isset($info['end_weight']) && intval($info['end_weight'])>=0)
		{
			$data['end_weight'] = $info['end_weight'];
		}
		
		$re = $this->puscont->get_content($data);
		$return = $this->puscont->get_pub_content_type();
		if(is_array($return))
		{
			foreach($return as $k => $v)
			{
				$bundles[$v['bundle']] = $v['name'];
			}
		}
		
		$columns = $this->get_column();
		if(is_array($re['data']))
		{
			foreach($re['data'] as $k=>$v)
			{
				$co_names = array();
				if($v['column_id'])
				{
					$co_arr = explode(" ",$v['column_id']);
					foreach($co_arr as $ke=>$va)
					{
						$co_names[] = $columns[$va];
					}
				}
				$v['column_name'] = implode(" ",$co_names);
				//$v['app_name']	=	$apps[$v['bundle_id']];
				$v['module_name']	=	$bundles[$v['bundle_id']];
				$v['pic'] = json_encode($v['indexpic']);
				$ret[] = $v;
			}
		}
		
		$total_num =$re['total'];//总的记录数
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$return['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$return['total_page']    = intval($total_num/$count) + 1;
		}
		$return['total_num'] = $total_num;//总的记录数
		$return['page_num'] = $count;//每页显示的个数
		$return['current_page']  = $pp;//当前页码
		
		$retu['info'] = $ret;
		$retu['page_info'] = $return;
		
		$this->addItem($retu);
		$this->output();
	}
	
	//获取栏目
	public function get_column()	
	{	
		include_once(ROOT_PATH.'lib/class/publishconfig.class.php');		
		$this->pubconfig = new publishconfig();
		
		$publish_columns = $this->pubconfig->get_column();
		foreach($publish_columns as $k=>$v)
		{
			$columns[$v['id']]	= $v['name'];
		}
		return $columns;
	}	
}

$out = new PushMes();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'append_device';
}
$out->$action();
?>