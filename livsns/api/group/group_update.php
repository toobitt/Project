<?php
require './global.php';
require './lib/group.class.php';

class groupUpdateApi extends BaseFrm
{
	var $group;
	public function __construct()
	{
		parent::__construct();
		$this->group = new group();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 创建地盘的操作
	 */
	public function create()
	{
		$data = $this->check_data();  //检验提交的数据是否有效
		
		if (!$data['action_id'])
		{
			//检测地盘创建的个数
			$num = $this->group->check_creategroup_num($data['user_id']);
			
			if ($num >= $this->settings['user_grands_num'])
			{
				$this->errorOutput(SYSTEM_LIMIT);
			}
			//检测创建的地盘是否存在
			$group_name = $this->group->check_group_exists($data['name']);
			if ($group_name > 0)
			{
				$this->errorOutput(OBJECT_NULL);
			}
			//默认当前讨论区为末级，如果该讨论区有父级就将父级的is_last字段改成0
			if ($data['fatherid'] > 0)
			{
				$res = $this->group->update_father($data['fatherid']);
				$data['depth'] = $res['depth'] + 1;
				$data['parents'] = $res['parents'];
			}
			elseif ($data['fatherid'] == 0)
			{
				$data['depth'] = 0;
				$data['parents'] = ',';
			}
		}
		$result = $this->group->create($data);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 检验提交的数据
	 */
	protected function check_data()
	{
		$group_name = trim(urldecode($this->input['q_group_name']));
		$group_domain = trim(urldecode($this->input['q_group_domain']));
		$group_type = isset($this->input['q_group_type']) ? intval(trim($this->input['q_group_type'])) : 0;
		$fatherid = isset($this->input['fatherid']) ? intval(trim($this->input['fatherid'])) : 0;
		$group_desc = trim(urldecode($this->input['q_group_desc']));
		$group_logo = trim(urldecode($this->input['q_group_logo']));
		$group_bg = trim(urldecode($this->input['q_group_bg']));
		$permission = isset($this->input['q_permission']) ? intval($this->input['q_permission']) : 6;
		//$hid_lat = isset($this->input['q_hid_lat']) ? floatval(trim($this->input['q_hid_lat'])) : 0;  //纬度
		//$hid_lng = isset($this->input['q_hid_lng']) ? floatval(trim($this->input['q_hid_lng'])) : 0;  //经度
		//$addr = trim(urldecode($this->input['q_group_addr']));
		$user_id = isset($this->user['user_id']) ? intval($this->user['user_id']) : 0;
		$user_name = trim(urldecode($this->user['user_name']));
		$action_id = isset($this->input['action_id']) ? intval($this->input['action_id']) : 0;
		if (empty($group_name)) $this->errorOutput(PARAM_NO_FULL);
		$data = array(
			'name' => $group_name,
			'group_domain' => $group_domain,
			'group_type' => $group_type,
			'fatherid' => $fatherid,
			'action_id' => $action_id,
			'description' => $group_desc,
			'logo' => $group_logo,
			'background' => $group_bg,
			//'lat' => $hid_lat,
			//'lng' => $hid_lng,
			//'group_addr' => $addr,
			'user_id' => $user_id,
			'user_name' => $user_name,
			'permission' => $permission,
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'last_update' => TIMENOW,
			'is_last' => 1,
			'group_member_count' => 1,
		);
		if ($action_id) $data['state'] = 1;
		return $data;
	}
	
	//申请地主
	public function apply()
	{
		$group_id = isset($this->input['group_id']) ? intval(trim($this->input['group_id'])) : -1;
		$user_id = isset($this->user['user_id']) ? intval($this->user['user_id']) : -1;
		if ($group_id < 0 || $user_id < 0) $this->errorOutput(PARAM_WRONG);
		$res = $this->group->check_group_exists($group_id);
		if (!$res) $this->errorOutput(OBJECT_NULL);
		$data = array(
			'group_id' => $group_id,
			'user_id' => $user_id,
		);
		$result = $this->group->apply_creater($data);
		$this->addItem($result);
		$this->output();
	}
	
	//加入(关注)|退出(取消关注)操作
	public function attention_op()
	{
		if (!isset($this->user['user_id']) || !isset($this->input['group_id']))
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$user_id = is_numeric($this->user['user_id']) ? intval($this->user['user_id']) : -1;
		$group_id = is_numeric($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		$user_name = isset($this->user['user_name']) ? trim(urldecode($this->user['user_name'])) : '';
		$state = intval($this->input['state']);
		$res = $this->group->check_group_exists($group_id);
		if (!$res) $this->errorOutput(OBJECT_NULL);
		$type = isset($this->input['op_type']) ? intval($this->input['op_type']) : -1;
		if ($type < 0) $this->errorOutput(PARAM_NO_FULL);
		$data = array(
			'group_id' => $group_id,
			'user_id' => $user_id,
		);
		if ($type == 1)
		{
			$data['user_name'] = $user_name;
			$data['join_time'] = TIMENOW;
			$data['last_visit'] = TIMENOW;
			$data['state'] = $state;
			$result = $this->group->join_member($data);
		}
		elseif ($type == 0)
		{
			$result = $this->group->del_member($data);
		}
		$this->addItem($result);
		$this->output();
	}
	
	//审核圈子下得成员
	public function change_user_state()
	{
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		$uid = isset($this->input['uid']) ? trim(urldecode($this->input['uid'])) : -1;
		$state = isset($this->input['state']) ? intval($this->input['state']) : 1;
		if ($group_id < 0 || $uid < 0) $this->errorOutput(PARAM_WRONG);
		$result = $this->group->change_user_state($group_id, $uid, $state);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 地主权限设置
	 */
	public function setting()
	{
		$user_id = isset($this->user['user_id']) ? intval($this->user['user_id']) : -1;
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		if ($group_id < 0 || $user_id < 0) $this->errorOutput(PARAM_WRONG);
		$res = $this->group->check_group_exists($group_id);
		if (!$res) $this->errorOutput(OBJECT_NULL);
		$num = $this->group->is_creater($group_id, $user_id, true); //判断是否为地主
		if ($num == 0) $this->errorOutput(NO_PERMISSION);
		$group_name = trim(urldecode($this->input['name']));
		$group_desc = isset($this->input['description']) ? trim(urldecode($this->input['description'])) : '暂无描述...';
		$group_tag = trim(urldecode($this->input['group_tags']));
		$thread_list = intval($this->input['thread_list']);
		$permission = intval($this->input['permissions']);
		$per_add_time = intval($this->input['per_add_time']);
		$auto_delete_time = intval($this->input['auto_delete_time']);
		if (empty($group_name)) $this->errorOutput(PARAM_WRONG);
		$data = array(
			'name' => $group_name,
			'description' => $group_desc,
			'thread_list' => $thread_list,
			'permission' => $permission,
			'per_add_time' => $per_add_time,
			'auto_delete_time' => $auto_delete_time
		);
		$result = $this->group->setting($data, $group_id);
		if ($group_tag && $result)
		{
			include_once(ROOT_PATH . 'lib/class/mark.class.php');
			$mark = new mark();
			$mark->updateMarkByNames($group_tag, $group_id, 1);
		}
		$this->addItem($result);
		$this->output();
	}
	
	//开启|关闭圈子
	public function is_open()
	{
		$group_id = isset($this->input['group_id']) ? intval(urldecode($this->input['group_id'])) : -1;
		if ($group_id < 0) $this->errorOutput(PARAM_WRONG);
		$_type = !!urldecode($this->input['_type']);
		$result = $this->group->is_open($group_id, $_type);
		$this->addItem($result);
		$this->output();
	}
	
	//关联附件操作处理
	public function material()
	{
		$user_id = isset($this->user['user_id']) ? intval($this->user['user_id']) : -1;
		$user_name = isset($this->user['user_name']) ? trim(urldecode($this->user['user_name'])) : '';
		$is_top = !!urldecode($this->input['is_top']);
		$img_info = isset($this->input['img_info']) ? $this->input['img_info'] : '';
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : 0;
		$thread_id = isset($this->input['thread_id']) ? intval($this->input['thread_id']) : 0;
		if ($user_id < 0 || empty($user_name) || empty($img_info))
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$data = array(
			'user_id' => $user_id,
			'user_name' => $user_name,
			'is_top' => $is_top,
			'group_id' => $group_id,
			'thread_id' => $thread_id
		);
		$this->group->add_material($img_info, $data);
		$this->addItem($result);
		$this->output();
	}
	
	public function update_group_file()
	{
		$group_id = $this->input['group_id'];
		$content = urldecode($this->input['content']) ? urldecode($this->input['content']) : '';
		if (empty($group_id))
		{
			$this->errorOutput('未传入圈子ID');
		}
		$ret = $this->group->update_group_file($content, $group_id,$this->input['type']);
		if($ret)
		{
			$this->addItem(array($group_id));
			$this->output();
		}
		else
		{
			$this->errorOutput('更新失败');
		}
	}
	
	//创建圈子公告
	public function create_notice()
	{
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		$title = trim(urldecode($this->input['title']));
		$content = trim(urldecode($this->input['content']));
		$active = !!$this->input['active'];
		if ($group_id < 0 || empty($title) || empty($content))
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$data = array(
			'group_id' => $group_id,
			'title' => $title,
			'content' => $content,
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'start_date' => TIMENOW,
			'end_date' => TIMENOW,
			'pub_date' => TIMENOW,
			'active' => $active
		);
		$result = $this->group->add_notice($data);
		$this->addItem($result);
		$this->output();
	}
	
	//修改更新圈子公告
	public function update_notice()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : -1;
		$title = isset($this->input['title']) ? trim(urldecode($this->input['title'])) : '';
		$content = isset($this->input['content']) ? trim(urldecode($this->input['content'])) : '';
		$active = isset($this->input['active']) ? !!$this->input['active'] : -1;
		$views = isset($this->input['views']) ? intval($this->input['views']) : -1;
		if ($id < 0) $this->errorOutput(PARAM_WRONG);
		$data = array();
		if (!empty($title)) $data['title'] = $title;
		if (!empty($content)) $data['content'] = $content;
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		if (!is_int($active)) $data['active'] = $active;
		if ($views >= 0) $data['views'] = $views;
		$result = $this->group->save_notice($data, $id);
		$this->addItem($result);
		$this->output();
	}
	
	//删除圈子公告
	public function del_notice()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : -1;
		if ($id < 0) $this->errorOutput(PARAM_WRONG);
		$result = $this->group->del_notice($id);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
}

$out = new groupUpdateApi();

$action = $_INPUT['a'];

if (!method_exists($out, $action))
{
	$action = 'none';
}

$out->$action();

