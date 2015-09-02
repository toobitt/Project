<?php
define('MOD_UNIQUEID','market_message');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/market_message_mode.php');
require_once(ROOT_PATH .'lib/class/recycle.class.php');
class market_message_update extends adminUpdateBase
{
	private $mode;
	private $recycle;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new market_message_mode();
		$this->recycle = new recycle();
		/******************************权限*************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$actions = (array)$this->user['prms']['app_prms']['supermarket']['action'];
			if(!in_array('manger',$actions))
			{
				$this->errorOutput('您没有权限访问此接口');
			}
		}
		/******************************权限*************************/
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		if(!$this->input['market_id'])
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->input['title'])
		{
			$this->errorOutput(NO_TITLE);
		}
		
		if(!$this->input['scope'])
		{
			$this->errorOutput('请选择发送的范围');
		}
		
		$add_data = array();
		//如果范围是指定用户，要去查询会员id
		if(intval($this->input['scope']) == 3)
		{
			$member_id = $this->input['member'];
			if(!$member_id)
			{
				$this->errorOutput('请指定一个或多个用户');
			}
			$member_id = $this->mode->getMemberIdByName($member_id,$this->input['market_id']);
			if($member_id)
			{
				$member_id = implode(',',$member_id);
			}
			else 
			{
				$this->errorOutput('您指定的用户不存在');
			}
			
			$add_data = array(
				'member_id' 		=> $member_id,
			);
		}
		
		//特定用户
		if(intval($this->input['scope']) == 2)
		{
			if(!$this->input['device'] && !$this->input['constellation'] && !$this->input['age_start'] && !$this->input['age_end'] && !$this->input['birthday_start'] && !$this->input['birthday_end'])
			{
				$this->errorOutput('请至少选择一个条件');
			}
			
			$add_data = array(
				'send_device' 		=> $this->input['device']?implode(',',$this->input['device']):'',
				'age_start' 		=> $this->input['age_start'],
				'age_end' 			=> $this->input['age_end'],	
				'birthday_start' 	=> $this->input['birthday_start'],	
				'birthday_end' 		=> $this->input['birthday_end'],	
				'constellation_id' 	=> $this->input['constellation']?implode(',',$this->input['constellation']):'',	
			);
		}

		$data = array(
			'title' 			=> $this->input['title'],
			'market_id' 		=> $this->input['market_id'],
			'content' 			=> $this->input['content'],
			'scope' 			=> $this->input['scope'],
			'expire_time' 		=> strtotime($this->input['expire_time']),
			'user_id' 			=> $this->user['user_id'],	
			'user_name' 		=> $this->user['user_name'],	
			'org_id' 			=> $this->user['org_id'],	
			'update_user_id' 	=> $this->user['user_id'],	
			'update_user_name' 	=> $this->user['user_name'],	
			'ip' 				=> hg_getip(),	
			'create_time' 		=> TIMENOW,	
			'update_time' 		=> TIMENOW,
		);
		
		$data = array_merge($add_data,$data);
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建消息通知',$ret,'','创建消息通知' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->input['title'])
		{
			$this->errorOutput(NO_TITLE);
		}
		
		if(!$this->input['scope'])
		{
			$this->errorOutput('请选择发送的范围');
		}
		
		$add_data = array();
		//如果范围是指定用户，要去查询会员id
		if(intval($this->input['scope']) == 3)
		{
			$member_id = $this->input['member'];
			if(!$member_id)
			{
				$this->errorOutput('请指定一个或多个用户');
			}
			$member_id = $this->mode->getMemberIdByName($member_id,$this->input['market_id']);
			if($member_id)
			{
				$member_id = implode(',',$member_id);
			}
			else 
			{
				$this->errorOutput('您指定的用户不存在');
			}
			
			$add_data = array(
				'member_id' 		=> $member_id,
			);
		}
		
		//特定用户
		if(intval($this->input['scope']) == 2)
		{
			if(!$this->input['device'] && !$this->input['constellation'] && !$this->input['age_start'] && !$this->input['age_end'] && !$this->input['birthday_start'] && !$this->input['birthday_end'])
			{
				$this->errorOutput('请至少选择一个条件');
			}
			
			$add_data = array(
				'send_device' 		=> $this->input['device']?implode(',',$this->input['device']):'',
				'age_start' 		=> $this->input['age_start'],
				'age_end' 			=> $this->input['age_end'],	
				'birthday_start' 	=> $this->input['birthday_start'],	
				'birthday_end' 		=> $this->input['birthday_end'],	
				'constellation_id' 	=> $this->input['constellation']?implode(',',$this->input['constellation']):'',	
			);
		}
		
		$update_data = array(
			'title' 			=> $this->input['title'],
			'content' 			=> $this->input['content'],	
			'member_id' 		=> $member_id,
			'scope' 			=> $this->input['scope'],
			'expire_time' 		=> strtotime($this->input['expire_time']),
			'update_user_id' 	=> $this->user['user_id'],	
			'update_user_name' 	=> $this->user['user_name'],
			'update_time' 		=> TIMENOW,
		);
		
		$update_data = array_merge($update_data,$add_data);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addLogs('更新消息通知',$ret,'','更新消息通知' . $this->input['id']);
			$this->addItem('success');
			$this->output();
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
			foreach ($ret AS $k => $v)
			{
				//记录回收站的数据
				$recycle[$v['id']] = array(
					'title' 		=> $v['title'],
					'delete_people' => $this->user['user_name'],
					'cid' 			=> $v['id'],
					'content'		=> array('market_message' => $v),
				);
			}
			
			/********************************回收站***********************************/
			if($recycle)
			{
				foreach($recycle as $key => $value)
				{
					$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				}
			}
			/********************************回收站***********************************/
			$this->addLogs('删除消息通知',$ret,'','删除消息通知' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			$this->addLogs('审核消息通知','',$ret,'审核消息通知' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	//发送消息
	public function sendMessage()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->sendMessage($this->input['id']);
		if($ret)
		{
			$this->addLogs('发送消息通知','',$ret,'发送消息通知' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}	

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new market_message_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>