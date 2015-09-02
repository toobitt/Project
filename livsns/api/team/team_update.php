<?phprequire_once './global.php';require_once './lib/team.class.php';include_once ROOT_PATH . '/lib/class/option.class.php';include_once ROOT_PATH . '/lib/class/member.class.php';include_once ROOT_PATH . '/lib/class/notify.class.php';include_once ROOT_PATH . '/lib/class/mark.class.php';include_once ROOT_PATH . '/lib/class/team.class.php';class teamUpdateApi extends appCommonFrm{	private $team;	private $notify;	private $teamApi;		public function __construct()	{		parent::__construct();		$this->team = new teamClass();		$this->notify = new notify();		$this->teamApi = new team();	}		public function __destruct()	{		parent::__destruct();		unset($this->team);		unset($this->notify);		unset($this->teamApi);	}		/**	 * 创建小组操作	 */	public function create()	{		//处理传递的数据		$data = $this->filter_data();				//检测小组类型是否存在		if (!isset($data['team_type'])) $this->errorOutput(PARAM_WRONG);		$result_type = $this->team->check_team_type($data['team_type']);		if (!$result_type) $this->errorOutput(PARAM_WRONG);				//检测小组分类是否存在		$result_category = $this->team->check_team_category($data['team_category']);		if (!$result_category) $this->errorOutput(PARAM_WRONG);				//检测创建的小组名称是否存在		$result_name = $this->team->check_team_name($data['team_name']);		if ($result_name) $this->errorOutput(TEAM_EXISTS);				//检测用户创建的小组的个数		$result_num = $this->team->check_create_team_num($this->user['user_id']);		if ($result_num >= $this->settings['create_team_num'])		{			$this->errorOutput(SYSTEM_LIMIT);		}				$data['creater_id'] = $this->user['user_id'];		$data['creater_name'] = $this->user['user_name'];		$data['pub_time'] = TIMENOW;		$data['app_name'] = $this->user['display_name'];				//创建小组		$result = $this->team->create($data);				//标签创建		if (isset($data['tags']) && $data['tags'] && $result)		{			$mark = new mark();			$mark_data = array(				'source' => 'team',				'source_id' => $result['team_id'],				'parent_id' => $result['team_id'],				'action' => 'team_tag',				'user_id' => $result['creater_id'],				'name' => $data['tags']			);			$result_mark = $mark->create_source_id_mark($mark_data);			if ($result_mark)			{				$result['team_mark'] = $data['tags'];				$this->team->update(array('tags' => $data['tags']), $result['team_id']);			}			else			{				$this->errorOutput(FAIL_OP);			}		}		$this->addItem($result);		$this->output();	}		/**	 * 设置小组信息操作	 */	public function setting()	{		$team_id = intval($this->input['team_id']);				//检测对应的小组是否存在		$team_info = $this->team->detail($team_id, 1);		if (!$team_info) $this->errorOutput(TEAM_NO_EXISTS);				if ($team_info['creater_id'] != $this->user['user_id'])		{			$this->errorOutput(NO_PERMISSION);		}		$data = $this->filter_data();		$verify_data = array();		//检测小组类型是否存在		if (isset($data['team_type']) && $data['team_type'])		{			$result_type = $this->team->check_team_type($data['team_type']);			if (!$result_type) $this->errorOutput(PARAM_WRONG);			$verify_data['team_type'] = $data['team_type'];		}		if ($team_info['team_category'] != $data['team_category'])		{			//检测小组分类是否存在			$result_category = $this->team->check_team_category($data['team_category']);			if (!$result_category) $this->errorOutput(PARAM_WRONG);			$verify_data['team_category'] = $data['team_category'];		}				if ($team_info['team_name'] != $data['team_name'])		{			//检测更新的小组名称是否存在			$result_name = $this->team->check_team_name($data['team_name'], $team_id);			if ($result_name) $this->errorOutput(TEAM_EXISTS);			$verify_data['team_name'] = $data['team_name'];		}		if ($team_info['introduction'] != $data['introduction'])		{			$verify_data['introduction'] = $data['introduction'];		}		if (isset($data['team_logo']) && $data['team_logo'])		{			$verify_data['team_logo'] = $data['team_logo'];		}		if (isset($data['notice']))		{			if ($team_info['notice'] != $data['notice'])			{				$verify_data['notice'] = $data['notice'];			}		}		$verify_data['update_time'] = TIMENOW;		$result = $this->team->update($verify_data, $team_id);				//标签更新		if ($team_info['tags'] != $data['tags'])		{			$team_mark = $data['tags'];		}		if (isset($team_mark) && $result)		{			$mark = new mark();			$data = array(				'source' => 'team',				'source_id' => $team_id,				'parent_id' => $team_id,				'action' => 'team_tag',				'user_id' => $team_info['creater_id']			);			if ($team_mark)			{				$data['name'] = $team_mark;			}			$result_mark = $mark->update_source_id_mark($data);			if ($result_mark)			{								$this->team->update(array('tags' => $team_mark), $team_id);			}			else			{				$this->errorOutput(FAIL_OP);			}		}		if ($verify_data['notice'] && $result)		{			//获取关注小组的用户信息			$option = new option();			$member = $option->members('team', $team_id, 'attention', 0, -1);			//发送通知			$send_arr = array();			if ($member['data']['attention'])			{				$send_con = array();				foreach ($member['data']['attention']['infos'] as $v)				{					$send_con['from_id'] = 0;					$send_con['to_id'] = $v['user_id'];					$send_con['content'] = '您关注的"' . $team_info['team_name'] . '小组"有新公告！';					$send_con['page_link'] = 'team.php?team_id=' . $team_id;					$send_con['link_text'] = '点击查看';					$send_arr[] = $send_con;				}			}			if ($send_arr)			{				$this->notify->notify_send(json_encode($send_arr), 0);			}		}		//更新搜索		$this->teamApi->update_search($team_id, 'team');		$this->addItem($result);		$this->output();	}		/**	 * 申请小组	 */	public function create_team_apply()	{		$type = intval($this->input['type']);		$unit_name = trim(urldecode($this->input['unit_name']));		$contact_person = trim(urldecode($this->input['contact_person']));		$contact_way = trim(urldecode($this->input['contact_way']));		$application_note = trim(urldecode($this->input['application_note']));		if (empty($unit_name) || empty($contact_person) || empty($contact_way) || empty($application_note))		{			$this->errorOutput(PARAM_WRONG);		}		//检测小组类型是否存在		$result_type = $this->team->check_team_type($type);		if (!$result_type) $this->errorOutput(PARAM_WRONG);		$data = array(			'type' => $type,			'user_id' => $this->user['user_id'],			'user_name' => $this->user['user_name'],			'unit_name' => $unit_name,			'contact_person' => $contact_person,			'contact_way' => $contact_way,			'application_note' => $application_note,			'app_name' => $this->user['display_name']		);		$result = $this->team->create_team_apply($data);		$this->addItem($result);		$this->output();	}		/**	 * 加入黑名单|取消黑名单操作	 */	public function black_list()	{		$team_id = intval($this->input['team_id']);		//检测对应的小组是否存在		$team_info = $this->team->detail($team_id, 1);		if (!$team_info) $this->errorOutput(TEAM_NO_EXISTS);				$type = isset($this->input['op_type']) ? intval($this->input['op_type']) : -1;		if ($type < 0) $this->errorOutput(PARAM_WRONG);				$user_id = intval($this->input['user_id']);		$member = new member();		$member_info = $member->getMemberById($user_id);		if (!$member_info) $this->errorOutput(PARAM_WRONG);		if ($team_info['creater_id'] == $user_id)		{			$this->errorOutput(PARAM_WRONG);		}		$data = array(			'team_id' => $team_id,			'user_id' => $user_id,		);		if ($type == 1)		{			//获取用户类型			$user_type = $this->team->check_user_type($user_id, $team_id);			//关注用户			if ($user_type['level'] == 2)			{				//删除关注				$option = new option();				$option->drop_relation($data['user_id'], 'team', $data['team_id'], 'attention');			}			$data['join_time'] = TIMENOW;			$result = $this->team->join_black_list($data);			$result['join_time'] = $data['join_time'];			$result['avatar'] = $member_info[0][$user_id]['avatar'];			$result['user_name'] = $member_info[0][$user_id]['nick_name'];		}		elseif ($type == 0)		{			$result = $this->team->quit_black_list($data);		}		$this->addItem($result);		$this->output();	}		/**	 * (加入|退出)关注	 */	public function attention_op()	{		$team_id = intval($this->input['team_id']);		//检测对应的小组是否存在		$team_info = $this->team->detail($team_id, 1);		if (!$team_info) $this->errorOutput(TEAM_NO_EXISTS);		$type = isset($this->input['op_type']) ? intval($this->input['op_type']) : -1;		$user_id = intval($this->user['user_id']);		if ($type < 0) $this->errorOutput(PARAM_WRONG);		$option = new option();		$data = array(			'user_id' => $user_id,			'source' => 'team',			'source_id' => $team_id,			'action' => 'attention'		);		$out = $option->get_relation($data);		$result = array('team_id' => $team_id);		if ($type == 1)		{			if ($out['state'] == 0)			{				$option->add_relation($user_id, 'team', $team_id, 'attention');				$this->team->update(array('attention_num' => 1), $team_id, true);				//更新搜索				$this->teamApi->update_search($team_id, 'team');				$result['num'] = $team_info['attention_num'] + 1;			}		}		if ($type == 0)		{			if ($out['state'] > 0)			{				$option->drop_relation($user_id, 'team', $team_id, 'attention');				$this->team->update(array('attention_num' => -1), $team_id, true);				//更新搜索				$this->teamApi->update_search($team_id, 'team');				$result['num'] = $team_info['attention_num'] - 1;			}		}		$this->addItem($result);		$this->output();	}		/**	 * 更新小组统计信息	 */	public function update_total()	{		$action_num = intval($this->input['action_num']);		$topic_num = intval($this->input['topic_num']);		$attention_num = intval($this->input['attention_num']);		$visit_num = intval($this->input['visit_num']);		$data = array();		if ($action_num > 0) $data['action_num'] = $action_num;		if ($topic_num > 0) $data['topic_num'] = $topic_num;		if ($attention_num > 0) $data['attention_num'] = $attention_num;		if ($visit_num > 0) $data['visit_num'] = $visit_num;		$team_id = intval($this->input['team_id']);				//检测对应的小组是否存在		$team_info = $this->team->detail($team_id, 1);		if ($team_info && $data)		{			$result = $this->team->update($data, $team_id, true);			//更新搜索			$this->teamApi->update_search($team_id, 'team');			$this->addItem($result);			$this->output();		}	}		/**	 * 申请活动的召集者	 */	public function apply()	{		$user_id = isset($this->user['user_id']) ? intval($this->user['user_id']) : -1;		$team_id = isset($this->input['team_id']) ? intval($this->input['team_id']) : -1;		$reason_info = trim(urldecode($this->input['reason']));		if ($user_id < 0 || $team_id < 0 || empty($reason_info)) $this->errorOutput(PARAM_WRONG);		$team_info = $this->team->detail($team_id, 1);		if (!$team_info) $this->errorOutput(TEAM_NO_EXISTS);		//判断是否有权限申请		$permission = $this->team->get_permission($user_id, $team_info);		if (!$permission['permission']['SUPPLY_CREATER']) $this->errorOutput(NO_PERMISSION);		//判断是否申请过		$result = $this->team->is_apply($team_id, $user_id);		if ($result > 0) $this->errorOutput(APPLY_HAS);		$apply_data = array(			'user_id' => $user_id,			'user_name' => $this->user['user_name'],			'team_id' => $team_id,			'reason' => $reason_info,			'apply_time' => TIMENOW,			'app_name' => $this->user['display_name']		);		$result = $this->team->add_apply($apply_data);		if ($result)		{			//发送通知			$send_arr = array();			$send_con = array();			$send_con['from_id'] = 0;			$send_con['to_id'] = $team_info['creater_id'];			$send_con['content'] = '您的小组有新的申请行动召集者信息！';			$send_con['page_link'] = 'manage.php?a=apply_user&team_id=' . $team_id;			$send_con['link_text'] = '点击查看';			$send_arr[] = $send_con;			$this->notify->notify_send(json_encode($send_arr), 0);		}		$this->addItem($result);		$this->output();	}		/**	 * 处理申请活动召集者	 */	public function check_apply()	{		$apply_id = intval($this->input['apply_id']);		$apply_info = $this->team->get_one_apply($apply_id);		if (!$apply_info) $this->errorOutput(PARAM_WRONG);		$team_info = $this->team->detail($apply_info['team_id'], 1);		$op = intval($this->input['op']);		if ($op != -1 && $op != 1)		{			$this->errorOutput(PARAM_WRONG);		}		if ($apply_info['state'] != $op)		{			if ($apply_info['state'] == 0)			{					if ($op == 1) //审核通过				{					$data = array(						'state' => 1,						'accept_time' => TIMENOW,					);					$result = $this->team->update_apply($data, $apply_id);					$this->team->update(array('apply_num' => 1), $apply_info['team_id'], true);					if ($result)					{						//发送通知						$send_arr = array();						$send_con = array();						$send_con['from_id'] = $team_info['creater_id'];						$send_con['to_id'] = $apply_info['user_id'];						$send_con['content'] = '您申请的"'.$team_info['team_name'].'小组"的行动召集者已通过审核！';						$send_con['page_link'] = 'action.php?a=create&team_id=' . $team_info['team_id'];						$send_con['link_text'] = '快去发布您的行动吧！';						$send_arr[] = $send_con;						$this->notify->notify_send(json_encode($send_arr), 'team');					}				}				if ($op == -1) //拒绝通过				{					$data = array(						'state' => -1,						'accept_time' => TIMENOW,					);					$result = $this->team->update_apply($data, $apply_id);				}			}			elseif ($apply_info['state'] == 1)			{				if ($op == -1) //解除权限				{					$data = array(						'state' => -1,						'accept_time' => TIMENOW,					);					$result = $this->team->update_apply($data, $apply_id);					$this->team->update(array('apply_num' => -1), $apply_info['team_id'], true);				}			}			$this->addItem($result);		}		$this->output();	}		/*	//创建小组公告	public function create_notice()	{		$team_id = isset($this->input['team_id']) ? intval($this->input['team_id']) : -1;		$title = trim(urldecode($this->input['title']));		$content = trim(urldecode($this->input['content']));		$state = !!$this->input['state'];		if ($team_id < 0 || empty($title) || empty($content))		{			$this->errorOutput(PARAM_WRONG);		}		$team_info = $this->team->detail($team_id, 1);		if ($team_info) $this->errorOutput(TEAM_NO_EXISTS);		$data = array(			'team_id' => $team_id,			'title' => $title,			'content' => $content,			'author_id' => $this->user['user_id'],			'author_name' => $this->user['user_name'],			'pub_time' => TIMENOW,			'state' => $state		);		$result = $this->team->add_notice($data);		$this->addItem($result);		$this->output();	}		//更新小组公告	public function update_notice()	{		$id = isset($this->input['id']) ? intval($this->input['id']) : -1;		$title = trim(urldecode($this->input['title']));		$content = trim(urldecode($this->input['content']));		$state = !!$this->input['state'];		if ($id < 0 || empty($title) || empty($content))		{			$this->errorOutput(PARAM_WRONG);		}		$notice_info = $this->team->get_one_notice($id);		if (!$notice_info) $this->errorOutput(PARAM_WRONG);		$data = array(			'title' => $title,			'content' => $content,			'author_id' => $this->user['user_id'],			'author_name' => $this->user['user_name'],			'update_time' => TIMENOW,			'state' => $state		);		$result = $this->team->save_notice($data, $id);		$this->addItem($result);		$this->output();	}		//删除小组公告	public function del_notice()	{		$id = isset($this->input['id']) ? intval($this->input['id']) : -1;		if ($id < 0) $this->errorOutput(PARAM_WRONG);		$notice_info = $this->team->get_one_notice($id);		if (!$notice_info) $this->errorOutput(PARAM_WRONG);		$result = $this->team->del_notice($id);		$this->addItem($result);		$this->output();	}	*/		/**	 * 处理传递的参数	 */	private function filter_data()	{		$team_name = trim(urldecode($this->input['name']));		$tags = trim(urldecode($this->input['tags']));		$team_type = intval($this->input['type']);		$team_category = intval($this->input['category']);		$introduction = isset($this->input['intro']) ? trim(urldecode($this->input['intro'])) : '';		$notice = isset($this->input['notice']) ? trim(urldecode($this->input['notice'])) : '';		$team_logo = isset($this->input['logo']) ? trim(urldecode($this->input['logo'])) : '';		$permission = isset($this->input['permission']) ? intval($this->input['permission']) : PERMISSION_ALL;		if (empty($team_name) || !$team_category || empty($introduction) || $permission < 0)		{			$this->errorOutput(PARAM_WRONG);		}		$data = array(			'team_name' => $team_name,			'team_category' => $team_category,			'introduction' => $introduction,			'tags' => $tags,			'notice' => $notice,			'permission' => $permission,		);		if ($team_logo)		{			$data['team_logo'] = $team_logo;		}		if ($team_type)		{			$data['team_type'] = $team_type;		}		return $data;	}		/**	 * 方法不存在的时候调用的方法	 */	public function none()	{		$this->errorOutput('调用的方法不存在');	}}$out = new teamUpdateApi();$action = $_INPUT['a'];if (!method_exists($out,$action)){	$action = 'none';}$out->$action();