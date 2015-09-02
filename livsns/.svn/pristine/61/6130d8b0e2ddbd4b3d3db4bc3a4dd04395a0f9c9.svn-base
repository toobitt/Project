<?php
require './global.php';
require './lib/group.class.php';
require './lib/thread.class.php';

class groupApi extends BaseFrm
{
	var $group;
	var $thread;
	
	public function __construct()
	{
		parent::__construct();
		$this->group = new group(); //圈子类
		$this->thread = new thread(); //帖子类
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	

	//获取圈子数据
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition = array('state' => 3);
		$_type = isset($this->input['_type']) ? intval($this->input['_type']) : 0;
		if ($_type)
		{
			$condition['_type'] = $_type;
		}
		$groups = $this->group->show($offset, $count, $condition);
		$this->setXmlNode('group_info' , 'group');
		foreach($groups as $group)
		{
			$this->addItem($group);
		}
		$this->output();
	}
	
	//根据圈子得ID获取圈子
	public function get_group_by_id()
	{
		$group_ids = trim(urldecode($this->input['group_ids']));
		$groups = $this->group->group_by_id($group_ids);
		$this->setXmlNode('group_info' , 'group');
		foreach($groups as $group)
		{
			$this->addItem($group);
		}
		$this->output();
	}
	
	//根据ID检查对应圈子是否存在
	public function check_group_exists()
	{
		$group_ids = trim(urldecode($this->input['group_ids']));
		$group_ids = explode(',', $group_ids);
		$group_effective_ids = array_filter($group_ids);
		$result = array();
		foreach ($group_effective_ids as $v)
		{
			$result[$v] = $this->group->check_group_exists(intval($v));
		}
		$this->addItem($result);
		$this->output();
	}
	
	//获取圈子总数
	public function count()
	{
		$data = array(
			'key' => trim(urldecode($this->input['k'])),
			'start_time' => strtotime(trim(urldecode($this->input['start_time']))),
			'end_time' => strtotime(trim(urldecode($this->input['end_time']))),
			'date_search' => $this->input['date_search'],
			'state' => 3,
			'group_type' => $this->input['group_type'],
			'fatherid' => $this->input['fatherid'],
			'hgupdn' => trim(urldecode($this->input['hgupdn'])),
			'hgorder' => trim(urldecode($this->input['hgorder'])),
			'_type' => $this->input['_type'],
		);
		$info = $this->group->count($data);
		echo json_encode($info);
	}
	
	//获取关注的圈子
	public function group_atten()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$user_id = isset($this->input['user_id']) ? intval($this->input['user_id']) : -1;
		if ($user_id < 0) $this->errorOutput(PARAM_WRONG);
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'user_id' => $user_id,
			'flag' => 0
		);
		$groups = $this->group->group_atten($data);
		$this->setXmlNode('group_info' , 'group');
		foreach($groups as $group)
		{
			$this->addItem($group);
		}
		$this->output();
	}
	
	//获取我是创建者的圈子
	public function group_host()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$user_id = isset($this->input['user_id']) ? intval($this->input['user_id']) : -1;
		if($user_id < 0) $this->errorOutput(PARAM_WRONG);
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'user_id' => $user_id,
			'flag' => 0
		);
		$groups = $this->group->group_host($data);
		$this->setXmlNode('group_info' , 'group');
		foreach($groups as $group)
		{
			$this->addItem($group);
		}
		$this->output();
	}
	
	//获取某个用户创建和关注的圈子数
	public function get_group_nums()
	{
		$user_id = isset($this->input['user_id']) ? intval($this->input['user_id']) : -1;
		$method = isset($this->input['method']) ? trim(urldecode($this->input['method'])) : '';
		if($user_id < 0) $this->errorOutput(PARAM_WRONG);
		$data = array(
			'user_id' => $user_id,
			'flag' => 1
		);
		$params = array('group_atten', 'group_host');
		if (in_array($method, $params))
		{
			$num = $this->group->$method($data);
			$this->addItem_withkey($method, $num);
			$this->output();
		}
		$group_join_num = $this->group->group_atten($data);
		$group_create_num = $this->group->group_host($data);
		$this->addItem_withkey('group_atten', $group_join_num);
		$this->addItem_withkey('group_host', $group_create_num);
		$this->output();
	}
	
	//获取关联行动的圈子ID
	public function group_related_action()
	{
		$action_ids = isset($this->input['action_ids']) ? trim(urldecode($this->input['action_ids'])) : false;
		if (!$action_ids) $this->errorOutput(PARAM_WRONG);
		$groups = $this->group->group_related_action($action_ids);
		foreach($groups as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	//获取附件信息
	public function get_material_info()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$user_id = isset($this->input['user_id']) ? intval($this->input['user_id']) : -1;
		$group_ids = isset($this->input['group_id']) ? trim(urldecode($this->input['group_id'])) : false;
		if($user_id < 0 || !$group_ids) $this->errorOutput(PARAM_WRONG);
		$condition = ' AND user_id = ' . $user_id . ' AND group_id in (' . $group_ids . ')';
		$limit = 'LIMIT ' . $offset . ', ' . $count;
		$info = $this->group->get_material_info($condition, $limit);
		foreach($info as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	public function get_material_num()
	{
		$user_id = isset($this->input['user_id']) ? intval($this->input['user_id']) : -1;
		$group_ids = isset($this->input['group_id']) ? trim(urldecode($this->input['group_id'])) : false;
		if($user_id < 0 || !$group_ids) $this->errorOutput(PARAM_WRONG);
		$condition = ' AND user_id = ' . $user_id . ' AND group_id in (' . $group_ids . ')';
		$result = $this->group->get_material_num($condition);
		$this->addItem($result['num']);
		$this->output();
	}
	
	//获取单个圈子信息 
	public function detail()
	{
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		$action_id = isset($this->input['action_id']) ? intval($this->input['action_id']) : 0;
		$flag = !!$this->input['flag'];
 		if ($group_id < 0 && $action_id <= 0) $this->errorOutput(PARAM_WRONG);
 		if ($action_id > 0)
 		{
 			$sql = "SELECT group_id FROM " . DB_PREFIX . "group WHERE action_id = " . $action_id;
 			$group = $this->db->query_first($sql);
 			$group_id = $group['group_id'];
 		}
		$this->setXmlNode('group_info' , 'group');
		$group = $this->group->detail($group_id, $flag, $action_id);
		if (!$flag)
		{
			if (!$group['status']) $this->errorOutput(OBJECT_NULL);
		}
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$threads = $this->thread->show($offset, $count, array('group_id' => $group_id, 'state' => 2));
		$material_num = isset($this->input['material_num']) ? intval($this->input['material_num']) : 3;
		$data_limit = 'LIMIT ' . $material_num;
		foreach($threads as $k=>$v)
		{
			$type = ' AND thread_id = ' . $v['thread_id'];
			$threads[$k]['material'] = $this->group->get_material_info($type, $data_limit);
		}
		$group['threads'] = $threads;
		$members = $this->group->get_member($group_id, false, array('state' => 1));
		$group['members'] = $members;
		$group['members_count'] = $this->group->get_member_num($group_id, array('state' => 1));
		include_once ROOT_PATH . 'lib/class/mark.class.php';
		$mark = new mark();
		$tags = $mark->getInfoByType($group_id, 1);
		$group['tag'] = $tags;
		$this->addItem($group);
		$this->output();
	}
	
	//获取相应权限
	public function get_permission()
	{
		$user_id = isset($this->user['user_id']) ? intval($this->user['user_id']) : -1;
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		$action_id = isset($this->input['action_id']) ? intval($this->input['action_id']) : 0;
		if ($group_id < 0 || $user_id < 0) $this->errorOutput(PARAM_WRONG);
		$group = $this->group->detail($group_id, true, $action_id);
		if (!$group['group_id']) $this->errorOutput(OBJECT_NULL);
		$result = $this->group->get_permission($group, $user_id);
		$this->addItem($result);
		$this->output();
	}
	
	//判断是否是圈子的创建者
	public function is_creater()
	{
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		if ($group_id < 0) $this->errorOutput(PARAM_WRONG);
		$result = $this->group->is_creater($group_id, $this->user['user_id'], true);
		$this->addItem($result);
		$this->output();
	}
	
	//圈子公告信息
	public function notice()
	{
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		if ($group_id < 0) $this->errorOutput(PARAM_WRONG);
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$notices = $this->group->get_notice($group_id, $offset, $count);
		foreach($notices as $notice)
		{
			$this->addItem($notice);
		}
		$this->output();
	}
	
	//某个具体圈子信息
	public function notice_detail()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : -1;
		if ($id < 0) $this->errorOutput(PARAM_WRONG);
		$notice = $this->group->get_one_notice($id);
		$this->addItem($notice);
		$this->output();
	}
	
	//获取某个圈子的普通成员信息
	public function get_member_info()
	{
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		if ($group_id < 0) $this->errorOutput(PARAM_WRONG);
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$state = isset($this->input['state']) ? intval($this->input['state']) : -1;
		$data = array();
		if ($state >= 0) $data['state'] = $state;
		$data['level'] = '=0';
		$group_members = $this->group->get_member($group_id, false, $data, $offset, $count);
		foreach($group_members as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	//获取所有圈子成员
	public function get_all_member()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = 'SELECT user_id,user_name,user_level,state 
		FROM ' . DB_PREFIX . 'group_members ORDER BY group_members_id DESC';
		$sql .= $data_limit;
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	
	//获取某个圈子的普通成员总数
	public function get_member_count()
	{
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		if ($group_id < 0) $this->errorOutput(PARAM_WRONG);
		$state = isset($this->input['state']) ? intval($this->input['state']) : -1;
		$data = array();
		if ($state >= 0) $data['state'] = $state;
		$data['level'] = '=0';
		$num = $this->group->get_member_num($group_id, $data);
		$this->addItem($num);
		$this->output();
	}
	
	//获取圈子创建者和管理员的信息
	public function get_admin_member()
	{
		$group_id = isset($this->input['group_id']) ? intval($this->input['group_id']) : -1;
		if ($group_id < 0) $this->errorOutput(PARAM_WRONG);
		$result = $this->group->get_member($group_id, false, array('level' => '!=0'));
		foreach($result as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
}

$out = new groupApi();

$action = $_INPUT['a'];

if (!method_exists($out, $action))
{
	$action = 'show';
}

$out->$action();

