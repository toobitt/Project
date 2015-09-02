<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID','message');
require('./global.php');
class MesPushUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function create()
	{
		
	}
	function sort()
	{
		
	}
	function publish()
	{
		
	}
	//添加通知
	function add_advice()
	{
		if(!$this->input['send_time'])
		{
			$this->errorOutput('请输入推送时间');
		}
		$message = trim($this->input['message']);
		if(!$message)
		{
			$this->errorOutput('请输入消息内容!');
		}
		/**************权限控制开始**************/
		$app_id = intval($this->input['app_id']);
		//节点权限
		if($app_id && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$nodes['nodes'][$app_id] = $app_id;
		}
		$nodes['_action'] = 'notice_manage';
		
		$this->verify_content_prms($nodes);
		/**************权限控制结束**************/	
		
		$send_time = strtotime(trim(urldecode($this->input['send_time'])));
		$device_create_time = strtotime(trim($this->input['device_create_time']));
		$device_update_time = strtotime($this->input['device_update_time']);

		$user_name = $this->user['user_name'];
		
		if(!intval($this->input['amount']))
		{
			$amount = 1;
		}
		else 
		{
			$amount = intval($this->input['amount']);
		}
		//根据appid查询消息发送方式
		$sql = 'SELECT send_way FROM '.DB_PREFIX.'certificate WHERE appid='.$this->input['app_id'];
		$res = $this->db->query_first($sql);
		$send_way = $res['send_way'];
		
		//exit;
		$data = array(
			'org_id' 				=> 	$this->user['org_id'],
			'user_id'				=>	$this->user['user_id'],
			'message'				=>	$message,
			'username'				=>	$user_name,
			'send_time'				=>	$send_time,
			'create_time'			=>	TIMENOW,
			'is_global'				=>	1,
			'ip'					=>	hg_getip(),
			'link_module' 			=> 	trim($this->input['link_module']),
			'content_id' 			=> 	$this->input['content_id'],
			'amount' 				=> 	$amount,
			'sound' 				=> 	$this->input['sound'],
			'appid' 				=> 	$app_id,
			'send_way'				=>	$send_way,
			'client' 				=> 	$this->input['client'],
			'device_type' 			=> 	$this->input['type'],
			'device_os' 			=> 	$this->input['system'],
			'debug' 				=> 	intval($this->input['debug']),
			'device_create_time' 	=> 	$device_create_time,
			'device_update_time' 	=> 	$device_update_time,
			'state'					=> 	$this->get_status_setting('create'),
			'expiry_time'			=>  intval($this->input['expiry_time']),
		);
		$sql = 'INSERT INTO '.DB_PREFIX.'advices SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$inert_id = $this->db->query($sql);
		$this->addItem($inert_id);
		/*if($this->db->query($sql))
		{
			$id = $this->db->insert_id();
			$client = $this->input['device'];
			if(is_array($client) && count($client)>0)
			{
				foreach($client as $value)
				{
					$val .= "('".$id."','".$value."')".',';
				}
				$val = substr($val,0,(strlen($val)-1));
				$push_sql = 'INSERT INTO '.DB_PREFIX.'publish (id,device) values'.$val;
				$this->db->query($push_sql);
			}
			$data['id'] = $this->db->insert_id();
			$this->addItem($data);
		}*/
		$this->output();
	}
	function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ids = trim(urldecode($this->input['id']));
		$sql = "SELECT * FROM " . DB_PREFIX ."advices WHERE id IN(" . $ids .")";
		$r = $this->db->query($sql);
		while($row = $this->db->fetch_array($r))
		{
			$app_id = $row['app_id'];
			$sorts[] = $row['app_id'];
			$advInfor[$row['id']] = $row;
			
			$data[$row['id']] = array(
				'title' => $row['message'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
				);
			$data[$row['id']]['content']['advices'] = $row;
		}
		/**************权限控制开始**************/
		//节点验证
		if($sorts && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$sorts = array_filter($sorts);
			if (!empty($sorts))
			{
				$nodes['nodes'][$app_id] = implode(',', $sorts);
				
				if (!empty($nodes))
				{
					$nodes['_action'] = 'notice_manage';
					$this->verify_content_prms($nodes);
				}
			}
			
		}
		//能否修改他人数据
		if (!empty($advInfor) && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			foreach ($advInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'notice_manage'));
			}
		}
		/**************权限控制结束**************/
		
		if(!empty($data))
		{
			foreach($data as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
		}
		if($res['sucess'])
		{
			//删除消息
			$sql = 'DELETE FROM '.DB_PREFIX.'advices WHERE id in('.$ids.')';
			$this->db->query($sql);
			$this->addItem('success');
			$this->output();
		}
		
	}
	public function delete_comp()
	{
		return true;
	}
	/*public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		if(!empty($content['advices']))
		{
			$sql = "insert into " . DB_PREFIX . "advices set ";
			$space='';
			foreach($content['advices'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
		}
	}*/
	//编辑留言
	function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		//权限检测开始
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查询修改通知之前信息
			$sql = "SELECT appid,org_id,user_id,state FROM " . DB_PREFIX ."advices WHERE id = " . $id;
			$q = $this->db->query_first($sql);
		
			//节点权限
			$_sort_ids = '';
			if($q['appid'])
			{
				$_sort_ids = $q['appid'];
			}
			if($this->input['app_id'])
			{
				$_sort_ids  = $_sort_ids ? $_sort_ids . ',' . $this->input['app_id'] : $this->input['app_id'];
			}
			$appid = $q['appid'] ? $q['appid'] : $this->input['app_id'];
			if($_sort_ids)
			{
				$data['nodes'][$appid] = $_sort_ids;
			}
		
			
			//修改他人数据
			$data['id'] = $id;
			$data['user_id'] 	= $q['user_id'];
			$data['org_id'] 	= $q['org_id'];
			$data['_action'] 	= 'notice_manage';
			
			$this->verify_content_prms($data);
			
			//修改已审核内容	
			if(intval($q['state']) == 1)
			{
				$this->input['state'] = $this->get_status_setting('update_audit', $q['state']);
			}
		}
		//权限检测结束
		
		if(!$this->input['amount'])
		{
			$amount = 1;
		}
		else 
		{
			$amount = intval($this->input['amount']);
		}
		
		$send_time = strtotime(trim($this->input['send_time']));
		$device_create_time = strtotime(trim($this->input['device_create_time']));
		$device_update_time = strtotime($this->input['device_update_time']);
		
		//根据appid查询消息推送方式
		$sql = 'SELECT send_way FROM '.DB_PREFIX.'certificate WHERE appid='.$this->input['app_id'];
		$res = $this->db->query_first($sql);
		$send_way = $res['send_way'];
		
		
		$data = array();
		$data = array(
			'message'				=> $this->input['message'],
			'send_time'				=> $send_time,
			'link_module' 			=> trim($this->input['link_module']),
			'content_id' 			=> $this->input['content_id'],
			'amount' 				=> $amount,
			'sound' 				=> $this->input['sound'],
			'appid' 				=> $this->input['app_id'],
			'client' 				=> $this->input['client'],
			'device_type' 			=> $this->input['type'],
			'device_os' 			=> $this->input['system'],
			'debug' 				=> intval($this->input['debug']),
			'device_create_time' 	=> $device_create_time,
			'device_update_time' 	=> $device_update_time,
			'send_way' 				=> $send_way,
			'expiry_time'			=> intval($this->input['expiry_time']),
		);
		$sql = 'UPDATE '.DB_PREFIX.'advices SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$sql = $sql.' WHERE id = '.$id;
		
		$this->db->query($sql);
		
		if($this->db->affected_rows())
		{
			$sql = "UPDATE " . DB_PREFIX . "advices SET 
				org_id_update ='" . $this->user['org_id'] . "',
				user_id_update = '".$this->user['user_id']."',
				user_name_update = '".$this->user['user_name']."',
				ip_update = '" . $this->user['ip'] . "', 
				update_time = '". TIMENOW . "',
				state = " . intval($this->input['state']) . " WHERE id=" . $id;
			$this->db->query($sql);
		}

		$this->addItem('success');
		$this->output();
	}
	
	/**
	 * 审核，打回
	 * audit 操作状态  1审核  2打回
	 * status 回调状态标识
	 */
	
	public function audit()
	{
		$ids = $this->input['id'];
		if(!$ids)
		{
			return false;
		}
		
		//判断是否是管理员
		$admin_tag = true;
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$admin_tag = false;
		}
		
		//查询被审核中未发送的消息，已发送消息不做处理
		$sql = 'SELECT id,appid,send_way FROM '.DB_PREFIX.'advices WHERE id IN('.$ids.') AND is_send = 0';
		$query = $this->db->query($sql);
		
		$sort_ids = array();
		while($row = $this->db->fetch_array($query))
		{
			//所有未发送消息ids
			$unSend[] = $row['id'];
			
			//所有发送方式为拉取的ids
			if(!$row['send_way'])
			{
				$up_ids[] = $row['id'];
			}	
				
			//权限判断数据	
			if($admin_tag)
			{
				$sort_ids[] 		= $row['app_id'];
				$app_id 			= $row['app_id'];
			}
		}
		
		//节点权限判断
		if($sort_ids && !$admin_tag)
		{
			$nodes['nodes'][$app_id] = implode(',', $sort_ids);
			$nodes['_action'] = 'notice_manage';
			
			$this->verify_content_prms($nodes);
		}
		//权限判断结束
		if($unSend)
		{
			$arr_id = $unSend;
			$ids = implode(',', $unSend);
			$audit = $this->input['audit'];
			if($audit == 1)
			{
				$sql = "UPDATE " . DB_PREFIX ."advices SET state = 1 WHERE id IN (".$ids.")";
				$this->db->query($sql);
				
				foreach ($arr_id as $k => $v)
				{
					if(in_array($v, array($up_ids)))
					{
						$info[] = array('id'=>$v,'send_way' => 1);//拉取
					}
					else 
					{
						$info[] = array('id'=>$v,'send_way' => 2);//推送
					}
				}
				
				$arr = array('id'=>$info,'status' => 1);
				if($up_ids)
				{
					$up_ids = implode(',', $up_ids);
					$sql = 'UPDATE '.DB_PREFIX.'advices SET is_send =1 WHERE id IN ('.$up_ids.')';
					
					$this->db->query($sql);
				}
			}
			else if($audit == 2) 
	 		{
				$sql = "UPDATE " . DB_PREFIX ."advices SET state = 2 WHERE id IN (".$ids.")";
				$this->db->query($sql);
				$arr = array('id' => $arr_id,'status' => 2);
			}
			$this->addItem($arr);
		}
		else
		{
			$this->addItem('sucess');
		}
		$this->output();
	}
}
$ouput= new MesPushUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>