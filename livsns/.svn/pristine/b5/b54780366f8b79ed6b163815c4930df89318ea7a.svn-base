<?php
require 'global.php';
define('MOD_UNIQUEID','notice');
include ROOT_PATH.'/lib/class/members.class.php';
class noticeUpdateApi extends adminUpdateBase
{
	private $members;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/notice.class.php';
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->mode = new notice();
		$this->members = new members();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 创建并发送一个通知
	 * @param title  通知标题
	 * @param content 通知内容
	 * @param type  通知类型：1单人通知 2会员 3m2o角色 4m2o部门 5所有人
	 * @param send_uid 发送人id 不填默认获取user_id
	 * @param send_uname 发送人name 不填默认获取user_name
	 * @param status 创建通知之后的审核状态
	 * @param owner_uid 接收人id 数组或者逗号隔开
	 * @param owner_uname 接收人name 数组或者逗号隔开
	 * @param member_type 接收会员的类型 m2o sina qq
	 * @param owner_utype 接收人类型 会员 or 管理员
	 * @param send_time 发送时间 默认当前时间
	 * @param from_time 开始时间
	 * @param to_time 结束时间
	 */
	public function create()
	{
		$this->verify_content_prms(array('_action'=>'manage'));
		$status = $this->get_status_setting('create');
		$owner_uid = array();
		$owner_uname = array();

		if(!trim($this->input['title']))
		{
			$this->errorOutput(NO_TITLE);
		}
		$type = intval($this->input['type']);
		if(!$type)
		{
			$this->errorOutput('对不起,您未选择发送类型');
		}
		$member_type = $this->input['member_type'];
		$owner_utype = intval($this->input['owner_utype']);
		/**
		 * 发送人用户名
		 */
		$input_name = $this->input['owner_uname'];
		if ($input_name&&is_string($input_name)&&!is_array($input_name))
		{
			$owner_uname = explode(',',$input_name);
		}
		elseif (is_array($input_name))
		{
			$owner_uname = $input_name;
		}
		if(is_array($owner_uname)){
			$owner_uname = array_filter($owner_uname,"clean_array_null");
		}
		/**
		 * 发送人ID
		 */
		$this->input['owner_uid']&&$input_uid = $this->input['owner_uid'];
		(!$this->input['owner_uid']&&$this->input['recipient'])&&$input_uid = $this->input['recipient'];
		if(($input_uid&&is_string($input_uid)&&(stripos($input_uid, ',')!==false))||is_numeric($input_uid)&&$input_uid>0&&!is_array($input_uid))
		{
			$owner_uid = explode(',', $input_uid);
		}
		else if (is_array($input_uid))
		{
			$owner_uid = $input_uid;
		}
		if(is_array($owner_uid))
		{
			$owner_uid = array_filter($owner_uid,"clean_array_null");
			$owner_uid = array_filter($owner_uid,"clean_array_num_max0");
		}
		if(is_array($owner_uname)&&is_array($owner_uid)&&$owner_utype == 1)
		{
			if (empty($owner_uname))
			{
				$this->errorOutput('对不起，您未选择用户');
			}			
			if(empty($owner_uid))
			{
				$this->errorOutput('对不起，您未选择用户');
			}
			if(count($owner_uname) != count($owner_uid))
			{
				$this->errorOutput('对不起，会员数据不合法');
			}
		}
		elseif(is_array($owner_uname)&&$owner_utype == 2)
		{
			if (empty($owner_uname))
			{
				$this->errorOutput('对不起，您未选择用户');
			}
		}
		elseif ($owner_utype != 0)
		{
			$this->errorOutput('对不起，您未选择用户');
		}
		
		if($type == 2 ) $owner_utype = 1;
		if($type == 3 or $type == 4 ) $owner_utype =  2;
		$data = array(
		    'send_uid'    => $this->input['send_uid'] ? intval($this->input['send_uid']) : $this->user['user_id'],
		    'send_uname'  => $this->input['send_uname'] ? trim($this->input['send_uname']) : $this->user['user_name'],
			'title'       => trim($this->input['title']),
		    'content'     => addslashes(htmlspecialchars($this->input['content'])),
		    'type'        => $type,
			//'status'      => intval($this->input['status']) ? intval($this->input['status']) : 0,
		    'status'		  => $status ? $status : 0,
		    'send_time'   => $this->input['send_time'] ? strtotime($this->input['send_time']) : TIMENOW ,
		    'from_time'   => $this->input['from_time'] ? strtotime($this->input['from_time']) : TIMENOW ,
		    'to_time'     => strtotime($this->input['to_time']),
			'org_id'      => $this->user['org_id'],
		    'user_id'     => $this->user['user_id'],
			'user_name'   => $this->user['user_name'],
			'create_time' => TIMENOW,
			'update_user_id'   => $this->user['user_id'],
			'update_user_name' => $this->user['user_name'],
			'update_time'      => TIMENOW,
			'ip'          => hg_getip(),
		);
		if($type == 5)//如果是全局通知
		{
			$data['owner_uid'] = 0;
			$data['owner_uname'] = 0;
			$data['owner_utype'] = 0;
			$return[] = $this->mode->create($data,'notice');
		}
		else
		{
			if(!$owner_uname)
			{
				$this->errorOutput(NO_NOTICE_PERSON);
			}
			foreach ($owner_uname as $k=>$v)
			{
				if(!$owner_uid[$k] && $v)
				{
					$o_uname[] = $v;//输入没有id的发送人，然后去请求id
				}
			}
			if($o_uname && $owner_utype ==1 && $type == 1) //如果是单一会员用户
			{
				$ret = $this->members->get_member_infoByuname($o_uname, $member_type);
				if(empty($ret))
				{
					$this->errorOutput(NO_MEMBER);
				}
				foreach ($owner_uname as $k=>$v)
				{
					$owner_uid[$k] = $ret[$v]['member_id'];
				}
			}
			if($o_uname && $owner_utype ==2 && $type == 1) //如果是单一管理员用户
			{
				$owner_unames = implode("','",$o_uname);
				include_once (ROOT_PATH . 'lib/class/auth.class.php');
				$this->auth = new Auth();
				$ret = $this->auth->getMemberByName($owner_unames);
				if(!$ret)
				{
					$this->errorOutput(NO_MEMBER);
				}
				foreach ($ret as $k=>$v)
				{
					$mms[$v['user_name']] = $v['id'];
				}
				foreach ($o_uname as $kk=>$vv)
				{
					$owner_uid[$kk] = $mms[$vv];
				}
			}
			foreach ($owner_uname as $k=>$v)
			{
				$data['owner_uid'] = $owner_uid[$k];
				$data['owner_uname'] = $v;
				$data['owner_utype'] = $owner_utype;
				$return[] = $this->mode->create($data,'notice');
			}
		}
		if($return)
		{
			foreach ($return as $k=>$v)
			{
				$this->addLogs('创建新通知', '', $v,$v['title'].'-'.$v['owner_uname'],$v['id']);
			}
		}
		$this->addItem($return);
		$this->output();
	}

	/**
	 * 更新一个通知
	 * @param title  通知标题
	 * @param content 通知内容
	 * @param type  通知类型：1单人通知 2会员 3m2o角色 4m2o部门 5所有人
	 * @param send_uid 发送人id 不填默认获取user_id
	 * @param send_uname 发送人name 不填默认获取user_name
	 * @param owner_uid 接收人id 数组或者逗号隔开
	 * @param owner_uname 接收人name 数组或者逗号隔开
	 * @param owner_utype 接收人类型 会员 or 管理员
	 * @param send_time 发送时间 默认当前时间
	 * @param from_time 开始时间
	 * @param to_time 结束时间
	 */
	public function update()
	{
		$owner_uid = array();
		$owner_uname = array();
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		/**************更新数据权限判断***************/
		$sql = "SELECT * FROM " . DB_PREFIX ."notice WHERE id = " . $this->input['id'];
		$q = $this->db->query_first($sql);
		$info['id'] = $q['id'];
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'manage';
		$s = $q['status'];
		$this->verify_content_prms($info);
		/*********************************************/
		###获取默认数据状态
		$status = $this->get_status_setting('update_audit',$s);
		if(!trim($this->input['title']))
		{
			$this->errorOutput(NO_TITLE);
		}
		$type = intval($this->input['type']);
		if(!$type)
		{
			$this->errorOutput('对不起,您未选择发送类型');
		}
		
		$owner_utype = intval($this->input['owner_utype']);
		/**
		 * 发送人用户名
		 */
		$input_name = $this->input['owner_uname'];
		if ($input_name&&is_string($input_name)&&!is_array($input_name))
		{
			$owner_uname = explode(',',$input_name);
		}
		elseif (is_array($input_name))
		{
			$owner_uname = $input_name;
		}
		if(is_array($owner_uname)){
			$owner_uname = array_filter($owner_uname,"clean_array_null");
		}
		/**
		 * 发送人ID
		 */
		$this->input['owner_uid']&&$input_uid = $this->input['owner_uid'];
		(!$this->input['owner_uid']&&$this->input['recipient'])&&$input_uid = $this->input['recipient'];
		if(($input_uid&&is_string($input_uid)&&(stripos($input_uid, ',')!==false))||is_numeric($input_uid)&&$input_uid>0&&!is_array($input_uid))
		{
			$owner_uid = explode(',', $input_uid);
		}
		else if (is_array($input_uid))
		{
			$owner_uid = $input_uid;
		}
		if(is_array($owner_uid))
		{
			$owner_uid = array_filter($owner_uid,"clean_array_null");
			$owner_uid = array_filter($owner_uid,"clean_array_num_max0");
		}
		
		if(is_array($owner_uname)&&is_array($owner_uid)&&$owner_utype == 1)
		{
			if (empty($owner_uname))
			{
				$this->errorOutput('对不起，您未选择用户');
			}
			if(empty($owner_uid))
			{
				$this->errorOutput('对不起，您未选择用户');
			}

			if(count($owner_uname) != count($owner_uid))
			{
				$this->errorOutput('对不起，会员数据不合法');
			}
		}
		elseif(is_array($owner_uname)&&$owner_utype == 2)
		{
			if (empty($owner_uname))
			{
				$this->errorOutput('对不起，您未选择用户');
			}
		}
		elseif ($owner_utype != 0)
		{
			$this->errorOutput('对不起，您未选择用户');
		}

		if($type == 2 ) $owner_utype = 1;
		if($type == 3 or $type == 4 ) $owner_utype =  2;

		$data = array(
		    'send_uid'    => $this->input['send_uid'] ? intval($this->input['send_uid']) : $this->user['user_id'],
		    'send_uname'  => $this->input['send_uname'] ? trim($this->input['send_uname']) : $this->user['user_name'],
			'title'       => trim($this->input['title']),
		    'content'     => addslashes(htmlspecialchars($this->input['content'])),
		    'type'        => $type,
		    'send_time'   => $this->input['send_time'] ? strtotime($this->input['send_time']) : TIMENOW ,
		    'from_time'   => $this->input['from_time'] ? strtotime($this->input['from_time']) : TIMENOW ,
		    'to_time'     => strtotime($this->input['to_time']),
			'status'		  => $status ? $status : 0,
		);
		if($type == 5)//如果是全局通知
		{
			$data['owner_uid'] = 0;
			$data['owner_uname'] = 0;
			$data['owner_utype'] = 0;
			$rets = $this->mode->update($id,'notice',$data);
			if($rets['affected_rows'])
			{
				$update_user = array(
				    'update_user_id'   => $this->user['user_id'],
				    'update_user_name' => $this->user['user_name'],
				    'update_time'      => TIMENOW,
				);
				$rets = $this->mode->update($id,'notice',$update_user);
				$return[] = $rets;
				$this->addLogs('更新通知', $_notice, $data,$data['title'],$id);
			}
		}
		else
		{
			if(!$owner_uname)
			{
				$this->errorOutput(NO_NOTICE_PERSON);
			}
			foreach ($owner_uname as $k=>$v)
			{
				if(!$owner_uid[$k] && $v)
				{
					$o_uname[] = $v;//输入没有id的发送人，然后去请求id
				}
			}
			if($o_uname && $owner_utype ==1 && $type == 1) //如果是单一会员用户
			{
				$ret = $this->members->get_member_infoByuname($o_uname, $member_type);
				if(empty($ret))
				{
					$this->errorOutput(NO_MEMBER);
				}
				foreach ($owner_uname as $k=>$v)
				{
					$owner_uid[$k] = $ret[$v]['member_id'];
				}
			}
			if($o_uname && $owner_utype ==2 && $type == 1) //如果是单一管理员用户
			{
				$owner_unames = implode("','",$o_uname);
				include_once (ROOT_PATH . 'lib/class/auth.class.php');
				$this->auth = new Auth();
				$ret = $this->auth->getMemberByName($owner_unames);
				if(!$ret)
				{
					$this->errorOutput(NO_MEMBER);
				}
				foreach ($ret as $k=>$v)
				{
					$mms[$v['user_name']] = $v['id'];
				}
				foreach ($o_uname as $kk=>$vv)
				{
					$owner_uid[$kk] = $mms[$vv];
				}
			}
			$sql = 'SELECT * FROM '.DB_PREFIX.'notice WHERE id = '. $id ;
			$_notice = $this->db->query_first($sql);
			foreach ($owner_uname as $k=>$v)
			{
				//$data[$owner_uid[$k]] = $data;
				$data['owner_uid'] = $owner_uid[$k];
				$data['owner_uname'] = $v;
				$data['owner_utype'] = $owner_utype;
				if(($_notice['owner_uid'] == $owner_uid[$k] && $_notice['type'] == $type) || count($owner_uid) == 1)
				{
					$rets = $this->mode->update($id,'notice', $data);
					if($rets['affected_rows'])
					{
						$update_user = array(
				        'update_user_id'   => $this->user['user_id'],
				        'update_user_name' => $this->user['user_name'],
				        'update_time'      => TIMENOW,
						);
						$rets  = $this->mode->update($id,'notice',$update_user);
					}
					$return[] = $rets;
					$this->addLogs('更新通知', $_notice, $data,$data['title'].'-'.$v,$id);
				}
				else
				{
					$data['org_id'] = $this->user['org_id'];
					$data['user_id'] = $this->user['user_id'];
					$data['user_name'] = $this->user['user_name'];
					$data['create_time'] = TIMENOW;
					$data['update_user_id'] = $this->user['user_id'];
					$data['update_user_name'] = $this->user['user_name'];
					$data['update_time'] = TIMENOW;
					$data['ip'] = hg_getip();
					$return[] = $this->mode->create($data,'notice');
					$this->addLogs('创建新通知', '', $data,$data['title'].'-'.$v,$data['id']);
				}
			}
			if(!in_array($_notice['owner_uid'],$owner_uid) && count($owner_uid) >1)//如果重新勾选了其他接收人，则删掉当前接收人
			{
				$sql = 'DELETE FROM ' .DB_PREFIX.'notice WHERE id IN('.$_notice['id'].')';
				$this->db->query($sql);
				$sql = 'DELETE FROM ' .DB_PREFIX.'notice_log WHERE notice_id IN('.$_notice['id'].')';
				$this->db->query($sql);
				$this->addLogs('删除通知', '', '','删除通知'.$id,$id);
			}
		}
		$this->addItem($return);
		$this->output();
	}
	/**
	 * 删除通知
	 * @param id  支持多个（逗号隔开）
	 */
	public function delete()
	{
		$id = trim($this->input['id'],',');
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		/**************删除权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'notice WHERE id IN ('.$this->input['id'].')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
		}
		/*********************************************/	
		$sql = 'DELETE FROM ' .DB_PREFIX.'notice WHERE id IN('.$id.')';
		$this->db->query($sql);
		$sql = 'DELETE FROM ' .DB_PREFIX.'notice_log WHERE notice_id IN('.$id.')';
		$this->db->query($sql);
		$this->addLogs('删除通知', '', '','删除通知'.$id,$id);
		$this->addItem($id);
		$this->output();
	}
	/**
	 * 审核通知
	 * @param id  支持多个（逗号隔开）
	 * @param audit 审核状态
	 */
	public function audit()
	{
		$id = trim($this->input['id'],',');
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		/**************审核权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'notice WHERE id IN ('. $id .')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'audit'));
			}
		}
		/*********************************************/
		$audit = intval($this->input['audit']);
		switch (intval($audit))
		{
			case 0:$status = 2;break;//打回
			case 1:$status = 1;break;//审核
		}
		$data = array(
		    'status'           => $status,
		    'audit_user_id'    => $this->user['user_id'],
		    'audit_user_name'  => $this->user['user_name'],
		    'audit_time'       => TIMENOW,
		);
		$sql = " UPDATE " . DB_PREFIX . "notice SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id IN ("  .$id. ")";
		$this->db->query($sql);
		$ret = array('id' => $id,'status' => $status);

		$this->addLogs('审核通知', '', '','审核通知'.$id,$id);
		$this->addItem($ret);
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

		$ret = $this->drag_order('notice', 'order_id');

		$this->addItem($ret);
		$this->output();
	}
	public function publish()
	{}

	public function unknow()
	{
		$this->errorOutput('此方法不存在');
	}

}
$out = new noticeUpdateApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>