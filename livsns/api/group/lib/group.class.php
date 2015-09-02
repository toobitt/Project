<?php
class group extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	//----------------------------------------查询数据操作----------------------------------------------
	
	//获取圈子数据
	public function show($offset, $count, $data = array())
	{
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$sql = "SELECT g.*,g.permission+0 as permission,t.type_name FROM " . DB_PREFIX . "group g 
				LEFT JOIN " . DB_PREFIX . "group_type t 
				ON g.group_type = t.typeid WHERE g.action_id = 0"; 
		
		//获取查询条件
		$condition = $this->get_condition($data);
		$sql = $sql . $condition . $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($query))
		{
			$row['id'] = $row['group_id'];
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['audit'] = $row['state'];
			if (unserialize($row['logo']))
			{
				$row['logo'] = unserialize($row['logo']);
			}
			if (unserialize($row['background']))
			{
				$row['background'] = unserialize($row['background']);
			}
			if (!$row['user_name'])
			{
				$row['user_name'] = '';
			}
//			$row['state_tags'] = $this->settings['state'][$row['state']];
//			if($this->settings['rewrite'])
//			{
//				$row['link'] = SNS_TOPIC."group-".$row['group_id'].".html";
//			}
//			else
//			{
//				$row['link'] = SNS_TOPIC."?m=thread&group_id=".$row['group_id']."&";
//			}
			$info[] = $row;
		}
		return $info;
	}
	
	//根据圈子ID获取圈子信息
	public function group_by_id($ids)
	{
		$sql = "SELECT g.*,g.permission+0 as permission,t.type_name FROM " . DB_PREFIX . "group g 
				LEFT JOIN " . DB_PREFIX . "group_type t 
				ON g.group_type = t.typeid WHERE g.action_id = 0  AND g.group_id in (" . $ids . ")";
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['id'] = $row['group_id'];
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['audit'] = $row['state'];
			if (unserialize($row['logo']))
			{
				$row['logo'] = unserialize($row['logo']);
			}
			if (unserialize($row['background']))
			{
				$row['background'] = unserialize($row['background']);
			}
			if (!$row['user_name'])
			{
				$row['user_name'] = '';
			}
			$info[] = $row;
		}
		return $info;
	}
	
	//获取圈子总数
	public function count($data = array())
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "group g WHERE g.action_id = 0";
		$condition = $this->get_condition($data);				
		$sql = $sql . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	//获取关联行动的圈子ID
	public function group_related_action($action_ids)
	{
		$sql = 'SELECT group_id FROM ' . DB_PREFIX . 'group WHERE action_id in (' . $action_ids . ')';
		$query = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	//检查用户创建圈子的个数
	public function check_creategroup_num($user_id)
	{
		$sql = 'SELECT COUNT(gm.group_members_id) AS num 
		FROM ' . DB_PREFIX . 'group_members gm, ' . DB_PREFIX . 'group g 
		WHERE gm.group_id = g.group_id AND gm.user_level = 2 
		AND g.action_id = 0 AND gm.user_id = ' . $user_id;
		$result = $this->db->query_first($sql);
		return $result['num'];
	}
	
	//检查圈子是否已存在
	public function check_group_exists($data)
	{
		$sql = 'SELECT COUNT(group_id) AS num FROM ' . DB_PREFIX . 'group WHERE state = 1 AND action_id = 0';
		if (is_string($data))
		{
			$sql .= ' AND name = "' . $data . '"';
		}
		elseif (is_int($data))
		{
			$sql .= ' AND group_id = ' . $data;
		}
		$result = $this->db->query_first($sql);
		return $result['num'];
	}
	
	//获取加入(关注)的圈子
	public function group_atten($data)
	{
		$data_limit = ' LIMIT ' . $data['offset'] . ' , ' . $data['count'];
		$sql = 'SELECT g.name,g.group_id,g.logo 
		FROM ' . DB_PREFIX . 'group g,' . DB_PREFIX . 'group_members gm 
		WHERE g.group_id = gm.group_id AND g.action_id = 0 
		AND gm.state = 1 
		AND gm.user_id = ' . $data['user_id'] . ' AND g.state = 1 
		AND gm.user_level != 2 ORDER BY g.group_id DESC';
		if ($data['flag'])
		{
			$nums = $this->db->fetch_all($sql);
			return count($nums);
		}
		$sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($query))
		{
			$row['logo'] = unserialize($row['logo']);
			$info[] = $row;
		}
		return $info;
	}
	
	//获取创建者的圈子
	public function group_host($data)
	{
		$data_limit = ' LIMIT ' . $data['offset'] . ' , ' . $data['count'];
		$sql = 'SELECT g.group_id,g.name,g.logo 
		FROM ' . DB_PREFIX . 'group_members gm,' . DB_PREFIX . 'group g 
		WHERE g.group_id = gm.group_id AND g.action_id = 0 
		AND gm.state = 1
		AND gm.user_level = 2 
		AND gm.user_id = ' . $data['user_id'] . ' ORDER BY g.group_id DESC';
		if ($data['flag'])
		{
			$nums = $this->db->fetch_all($sql);
			return count($nums);
		}
		$sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($query))
		{
			$row['logo'] = unserialize($row['logo']);
			$info[] = $row;
		}
		return $info;
	}
	
	//获取上级圈子fatherid为0的圈子
	public function top_group()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'group 
		WHERE fatherid = 0 AND state != 2 AND action_id = 0';
		$query = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	//获取圈子的类型
	public function group_type()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'group_type';
		$query = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	//获取圈子的帖子分类
	public function get_category($group_id)
	{
		$sql = 'SELECT id,category_name,path,CONCAT(path, "-", id) bpath 
		FROM ' . DB_PREFIX . 'thread_category WHERE group_id = ' . $group_id . ' ORDER BY bpath';
		$query = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($query))
		{
			$result['id'] = $row['id'];
			$result['category_name'] = $row['category_name'];
			$result['count'] = count(explode('-', $row['path'])) - 1;
			$info[] = $result;
		}
		return $info;
	}
	
	//获取附件相关信息
	public function get_material_info($type = '',$data_limit, $condition =' ORDER BY create_time DESC ')
	{	
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'material_contact WHERE 1 ';
		$sql .= $type . $condition  . $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['img_info'] = unserialize($row['img_info']);
			$info[] = $row;
//			$this->addItem_withkey($row['m_id'],unserialize($row['img_info']));
		}
//		return $this->output();
		return $info;
	}
	
	public function get_material_num($condition = '')
	{
		$sql = 'SELECT COUNT(m_id) AS num FROM ' . DB_PREFIX . 'material_contact WHERE 1 ';
		$sql .= $condition;
		$result = $this->db->query_first($sql);
		return $result;
	}
	
	//获取申请地盘总数
	public function count_apply()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "user_apply WHERE 1 ";

		//获取查询条件
		//$condition = $this->get_condition();			
		//$sql = $sql . $condition;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	//获取所有申请地盘数据
	public function show_apply($page, $count)
	{
		$offset = $page * $count;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$sql = 'SELECT g.name as group_name ,g.group_id,a.user_id,a.apply_time,a.accept_time,a.is_agree,m.member_name 
				FROM ' . DB_PREFIX . 'user_apply a 
				LEFT JOIN ' . DB_PREFIX . 'group g ON a.group_id = g.group_id 
				LEFT JOIN dev_member.' . DB_PREFIX . 'member m ON a.user_id = m.id 
				ORDER BY a.apply_time DESC ' . $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($query))
		{
			$row['apply_time'] = date("Y-m-d H:i:s", $row['apply_time']);
			$info[$row['group_id']][$row['user_id']] = $row;
		}
		return $info;
	}
	
	//检测是否关注过该圈子或是该圈子的创建者
	public function is_creater($group_id, $user_id, $level = false)
	{
		$sql = 'SELECT COUNT(group_members_id) AS num FROM ' . DB_PREFIX . 'group_members 
		WHERE group_id = ' . $group_id . ' AND user_id = ' . $user_id;
		if ($level) $sql .= ' AND user_level = 2';
		$result = $this->db->query_first($sql);
		return $result['num'];
	}
	
	//获取该圈子的创建者信息或者成员信息
	public function get_member($group_id, $user_id = false, $data = array(), $offset = 0, $count = 8)
	{
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = 'SELECT user_id,user_name,user_level,state 
		FROM ' . DB_PREFIX . 'group_members 
		WHERE group_id = ' . $group_id;
		if ($user_id)
		{
			$sql .= ' AND user_id = ' . $user_id;
		}
		elseif ($data)
		{
			$conditions = '';
			if (isset($data['level']))
			{
				$conditions .= ' AND user_level' . $data['level'];
			}
			if (isset($data['state']))
			{
				$conditions .= ' AND state = ' . $data['state'];
			}
			$sql .= $conditions;
			$sql .= ' ORDER BY group_members_id DESC' . $data_limit;
		}
		$query = $this->db->query($sql);
		$result = array();
		while($row = $this->db->fetch_array($query))
		{
			$result[] = $row;
		}
		return $result;
	}
	
	//获取对应圈子下的成员总数
	public function get_member_num($group_id, $data = array())
	{
		$sql = 'SELECT COUNT(group_members_id) AS num FROM ' . DB_PREFIX . 'group_members 
		WHERE group_id = ' . $group_id;
		if ($data)
		{
			$conditions = '';
			if (isset($data['level']))
			{
				$conditions .= ' AND user_level' . $data['level'];
			}
			if (isset($data['state']))
			{
				$conditions .= ' AND state = ' . $data['state'];
			}
			$sql .= $conditions;
		}
		$result = $this->db->query_first($sql);
		return $result['num'];
	}
	
	//获取单个圈子信息
	public function detail($group_id, $flag = false, $action_id = 0)
	{
		if(!$group_id)
		{
			if ($flag)
			{
				$condition = ' ORDER BY g.group_id DESC LIMIT 1';
			}
			else
			{
				$condition = ' AND g.state = 1 ORDER BY g.group_id DESC LIMIT 1';	
			}
		}
		else
		{
			if ($flag)
			{
				$condition = ' AND g.group_id = ' . $group_id;
			}
			else
			{
				$condition = ' AND g.group_id = ' . $group_id .' AND g.state = 1';
			}
		}
		$sql = "SELECT g.*,g.permission+0 as permission FROM " . DB_PREFIX."group g 
				LEFT JOIN " .DB_PREFIX ."group_type t 
				ON g.group_type = t.typeid WHERE action_id = " . $action_id . $condition;
		$r = $this->db->query_first($sql);
		$r['create_time'] = date('Y-m-d H:i:s' , $r['create_time']);
		$r['update_time'] = date('Y-m-d H:i:s' , $r['update_time']);
		$r['last_update'] = date('Y-m-d H:i:s' , $r['last_update']);
		$r['column_id'] = unserialize($r['column_id']);
		if(is_array($r['column_id']))
		{
			$column_id = array();
			foreach($r['column_id'] as $k => $v)
			{
				$column_id[] = $k;
			}
			$column_id = implode(',',$column_id);
			$r['column_id'] = $column_id;
		}
		if (unserialize($r['logo']))
		{
			$r['logo'] = unserialize($r['logo']);
		}
		if (unserialize($r['background']))
		{
			$r['background'] = unserialize($r['background']);
		}
//		if($this->settings['rewrite'])
//		{
//			$r['link'] = SNS_TOPIC."group-".$r['group_id'].".html";	
//		}
//		else 
//		{
//			$r['link'] = SNS_TOPIC."?m=thread&group_id=".$r['group_id']."&";
//		}
		$r['status'] = $r['state'] ? 2 : 0;
		$r['pubstatus'] = $r['status'] ? 1 : 0;
		return $r;
	}
	
	//返回某个圈子下的对应操作的权限
	public function get_permission($group, $user_id)
	{
		if ($user_id)
		{
			$level = $this->get_member($group['group_id'], $user_id);
			$level = $level[0];
		}
		$result = array();
		$result['id'] = $user_id;
		$result['level'] = $level ? $level['user_level'] : -1;
		$permissions = array(
			'MEMBER_JOIN' => MEMBER_JOIN,
			'NON_MEMBER_VIEW' => NON_MEMBER_VIEW,
			'VISITOR_POST' => VISITOR_POST,
			'UPLOAD_PICTURE' => UPLOAD_PICTURE,
			'STICKY' => STICKY,
			'QUINTESSENCE' => QUINTESSENCE,
			'MOVE' => MOVE,
			'OPEN' => OPEN,
			'CREATE_ALBUMS' => CREATE_ALBUMS,
			'DEL_ALBUMS' => DEL_ALBUMS,
			'DEL_PICTURE' => DEL_PICTURE,
			'VERYFY_MEMBER' => VERYFY_MEMBER,
			'CREATE_CATEGORY' => CREATE_CATEGORY,
			'DEL_CATEGORY' => DEL_CATEGORY,
			'THREAD_DEL' => THREAD_DEL,
			'THREAD_COMPLETE_DEL' => THREAD_COMPLETE_DEL,
			'BLACKLIST' => BLACKLIST,	
		);
		foreach($permissions as $k=>$v)
		{
			$result['permissions'][$k] = $group['permission'] & $v ? true : false;
		}
		return $result;
	}
	
	//获取某个圈子的公告
	public function get_notice($group_id, $offset, $count)
	{
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'announcement WHERE group_id = ' . $group_id;
		$sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($query))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	//获取具体的圈子公告信息
	public function get_one_notice($id)
	{
		$sql = 'SELECT a.*,g.name FROM ' . DB_PREFIX . 'announcement a, ' . DB_PREFIX . 'group g WHERE g.group_id = a.group_id AND a.id = ' . $id;
		return $this->db->query_first($sql);
	}
	
	//-----------------------------创建更新删除操作-----------------------------------
	
	//创建圈子操作
	public function create($data)
	{
		$sqlArr = array();
		foreach($data as $k=>$v)
		{
			if ($k == 'permission')
			{
				$sqlArr[] = $k . "=b'" . decbin($v) . "'";
			}
			else
			{
				if (is_string($v))
				{
					$sqlArr[] = $k . "='" . $v . "'";
				}
				elseif (is_int($v))
				{
					$sqlArr[] = $k . '=' . $v;
				}
			}
		}
		$sql = 'INSERT INTO ' . DB_PREFIX . 'group SET ' . implode(',', $sqlArr);
		
		$this->db->query($sql);
		
		$group_id = $this->db->insert_id();
		
		$data['parents'] .= $group_id . ',';
		
		$updateSql = 'UPDATE ' . DB_PREFIX . 'group SET parents = "' . $data['parents'] . '" WHERE group_id = ' . $group_id;
		
		$this->db->query($updateSql);
		
		$data_members = array(
			'group_id' => $group_id,
			'user_id' => $data['user_id'],
			'user_name' => $data['user_name'],
			'user_level' => 2,
			'join_time' => TIMENOW,
			'last_visit' => TIMENOW,
			'state' => 1,
		);
		return $this->join_member($data_members);
	}
	
	//加入(关注)圈子操作
	public function join_member($data)
	{	
		$num = $this->is_creater($data['group_id'], $data['user_id']); //检测是否关注过该地盘
		if ($num > 0) return true;
		
		$sqlArr = array();
		foreach($data as $k=>$v)
		{
			if (is_string($v))
			{
				$sqlArr[] = $k . '="' . $v . '"';
			}
			elseif (is_int($v))
			{
				$sqlArr[] = $k . '=' . $v;
			}
		}
		$sql = 'INSERT INTO ' . DB_PREFIX . 'group_members SET ' . implode(',', $sqlArr);
		
		$this->db->query($sql);
		
		return $data['group_id'];
	}
	
	//退出(取消关注)圈子操作
	public function del_member($data)
	{
		$num = $this->is_creater($data['group_id'], $data['user_id']);
		if ($num == 0) return true;
		$sql = 'DELETE FROM ' . DB_PREFIX . 'group_members 
		WHERE group_id = ' . $data['group_id'] . ' AND user_id = ' . $data['user_id'];
		return $this->db->query($sql);
	}
	
	//将圈子有上级的is_last字段设置为 0
	public function update_father($fatherid)
	{
		$sql = 'SELECT depth,parents,is_last FROM ' . DB_PREFIX . 'group WHERE group_id = ' . $fatherid;
		$result = $this->db->query_first($sql);
		if ($result['is_last'] > 0)
		{
			$this->db->query('UPDATE ' . DB_PREFIX . 'group SET is_last = 0 WHERE group_id = ' . $fatherid);
		}
		return $result;
	}
	
	//开启|关闭圈子
	public function is_open($group_id, $_type)
	{
		$state = $_type ? 1 : -1;
		$sql = 'UPDATE ' . DB_PREFIX . 'group SET state = ' . $state . ' WHERE group_id = ' . $group_id;
		$result = $this->db->query($sql);
		return $result;
	}
	
	//审核|打回圈子成员
	public function change_user_state($group_id, $uid, $state)
	{
		$sql = 'UPDATE ' . DB_PREFIX . 'group_members SET state = ' . $state . ' WHERE group_id = ' . $group_id . ' AND user_id in (' . $uid . ')';
		return $this->db->query($sql);
	}
	
	//更新圈子数据
	public function update($data, $group_id)
	{
		//查询修改文章之前已经发布到的栏目
		$sql = "select column_id from " . DB_PREFIX ."group where group_id = " . $group_id;
		$q = $this->db->query_first($sql);
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}

		$data['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$data['column_id']);
		$data['column_id'] = serialize($data['column_id']);
		
		
		$sql = "UPDATE " . DB_PREFIX . "group SET ";		
		$field = '';
		foreach($data as $db_field => $value)
		{
			if (is_int($value))
			{
				$field .= $db_field . " = " . $value . " ,";
			}
			else
			{
				$field .= $db_field . " = '" . trim($value) . "' ,";
			}
		}
		$field = substr($field , 0 , (strlen($field)-1));		
		$condition = " WHERE group_id = " . $group_id;		
		$sql = $sql . $field . $condition;	
		$this->db->query($sql);
		
		//发布系统
		$sql = "select * from " . DB_PREFIX ."group where group_id = " . $group_id;
		$ret = $this->db->query_first($sql);
		//更改文章后发布的栏目
		$ret['column_id'] = unserialize($ret['column_id']);
		$new_column_id = array();
		if(is_array($ret['column_id']))
		{
			$new_column_id = array_keys($ret['column_id']);
		}
		
		if(intval($ret['state']) == 1)
		{
			if(!empty($ret['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->publish_insert_query($group_id, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					$this->publish_insert_query($group_id, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->publish_insert_query($group_id, 'update',$same_column);
				}
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				$this->publish_insert_query($group_id,$op);
			}
		}
		else    //打回
		{
			if(!empty($ret['expand_id']))
			{
				$op = "delete";
				$this->publish_insert_query($group_id,$op);
			}
		}
		return true;
	}
	
	//圈子数据批量审核
	public function audit($id)
	{
		$ids = str_replace('，' , ',' , $id);
			
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array)) return false;
		
		$verify_id = implode(',' , $id_array);
		
		$sql_update = "UPDATE " . DB_PREFIX . "group SET state = 1 WHERE group_id IN (" . $verify_id . ")";

		$sql = "SELECT * FROM " . DB_PREFIX ."group WHERE group_id IN(" . $verify_id . ")";
		$ret = $this->db->query($sql);
		while($info = $this->db->fetch_array($ret))
		{
			if(!empty($info['expand_id']))
			{
				$op = "update";			
			}
			else
			{
				$op = "insert";
			}
			$this->publish_insert_query($info['group_id'], $op);
		}
		
		return $this->db->query($sql_update);
	}
	
	//圈子数据批量打回
	public function back($id)
	{
		$ids = str_replace('，' , ',' , $id);
			
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array)) return false;
		
		$verify_id = implode(',' , $id_array);
				
		$sql_update = "UPDATE " . DB_PREFIX . "group SET state = 0 WHERE group_id IN (" . $verify_id . ")";
		
		$sql = "SELECT * FROM " . DB_PREFIX ."group WHERE group_id IN(" . $verify_id .")";
		$ret = $this->db->query($sql);
		while($info = $this->db->fetch_array($ret))
		{
			if(!empty($info['expand_id']))
			{
				$op= "delete";  		//expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
			}		
			else 
			{
				$op = "";
			}
			$this->publish_insert_query($info['group_id'], $op);
		}
		return $this->db->query($sql_update);
	}
	
	//圈子数据批量删除
	public function delete($id)
	{
		$ids = str_replace('，' , ',' , $id);
		
		$id_array = explode(',' , $ids);

		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array)) return false;
		
		$delete_id = implode(',' , $id_array);

		//放入回收箱开始
		//查找地盘信息
		$sql = "SELECT * FROM " . DB_PREFIX . "group WHERE group_id IN (" . $delete_id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if(intval($row['state']) == 1 && $row['expand_id'])
			{
				$op = "delete";
				$this->publish_insert_query($row['group_id'],$op);
			}
			
			$data2[$row['group_id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['name'],
					'cid' => $row['group_id'],
			);
			$data2[$row['group_id']]['content']['group'] = $row;
		}
		//查找地盘成员信息
		$sql = "SELECT * FROM " . DB_PREFIX . "group_members WHERE group_id IN (" . $delete_id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['group_id']]['content']['group_members'][] = $row;
		}
		
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		$sql = "DELETE FROM " . DB_PREFIX . "group WHERE group_id IN (" . $delete_id . ")";

		$this->db->query($sql);

		$sql = "DELETE FROM " . DB_PREFIX . "group_members WHERE group_id IN (" . $delete_id . ")";
		
		return $this->db->query($sql);
	}
	
	//圈子管理者权限设置
	public function setting($data, $group_id)
	{
		$sqlArr = array();
		foreach($data as $k=>$v)
		{
			if ($k == 'permission')
			{
				$sqlArr[] = $k . "=b'" . decbin($v) . "'";
			}
			else
			{
				if (is_string($v))
				{
					$sqlArr[] = $k . '="' . $v . '"';
				}
				elseif (is_int($v))
				{
					$sqlArr[] = $k . '=' . $v;
				}
			}
		}
		$sql = 'UPDATE ' . DB_PREFIX . 'group SET ' . implode(',', $sqlArr) . ' WHERE group_id = ' . $group_id;
		return $this->db->query($sql);
	}
	
	public function update_group_file($content, $group_id,$type)
	{
		if(empty($group_id))
		{
			return false;
		}
		$data = json_decode($content,true);
		if($type == 'background')
		{
			$sql = "UPDATE " . DB_PREFIX . "group SET  background='" . serialize($data) . "',group_background='" . $data['id'] . "' where group_id=" . $group_id;
			$this->db->query($sql);
		}
		
		if($type == 'logo')
		{
			$sql = "UPDATE " . DB_PREFIX . "group SET  logo='" . serialize($data) . "',group_logo='" . $data['id'] . "' where group_id=" . $group_id;
			$this->db->query($sql);
		}
		return true;
	}
	
	//关联附件操作处理
	public function add_material($img_info, $data)
	{
		if(is_array($img_info) && !empty($img_info))
		{
			$counter = 0;
			foreach($img_info as $img)
			{
				$sql = 'INSERT INTO ' . DB_PREFIX . 'material_contact (user_id,img_info,group_id,thread_id, create_time) 
				VALUES('.$data['user_id'].",'".$img."',".$data['group_id'].','.$data['thread_id'].','.TIMENOW.')';
				$result = $this->db->query($sql);
				if ($result) ++$counter;
			}
			return $counter;
		}
		else
		{
			return false;
		}
	}
	
	//审核申请圈子管理者的数据
	public function check_creater($group_id, $user_id, $type)
	{
		$info = array();
		
		if (!$type) return $this->del_creater($group_id);
		
		$result = $this->is_creater($group_id, $user_id, true); //检测是否是地主
		
		if ($result > 0) return array('msg' => IS_GRAND, 'tips' => 0);
		
		$num = $this->check_creategroup_num($user_id); //检测做地主的个数

		//如果用户目前不是该地盘的地主，就判断当前用户做的地主数目是否达到系统设置上限
		if ($num >= $this->settings['user_grands_num'])
		{
			$info['msg'] = SYSTEM_LIMIT;
			$info['tips'] = 0;
		} 
		else 
		{
			$info = $this->do_agree($user_id, $group_id, $user);
		}
		return $info;
	}
	
	//删除地主操作
	public function del_creater($group_id)
	{
		$query = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'user_apply WHERE group_id = ' . $group_id);
		
		if(!$query)
		{
			$qq = $this->db->query_first('SELECT user_id,user_name FROM ' . DB_PREFIX . 'group WHERE group_id = ' . $group_id);
			if($qq)
			{
				$this->db->query('UPDATE ' . DB_PREFIX . 'group SET user_id = 0,user_name = "0" WHERE group_id = ' . $group_id);
				$member_sql = 'UPDATE ' . DB_PREFIX . 'group_members SET user_level = 0 
				WHERE group_id = ' . $group_id . ' AND user_id = ' . $qq['user_id'];
				$this->db->query($member_sql);
			}
			$info['msg'] = SUCCESS_OP;
			$info['tips'] = 1;
		}
		else
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'user_apply SET is_agree = 0, accept_time = ' . TIMENOW . ' WHERE group_id = ' . $group_id;
			$this->db->query($sql);
			
			$member_sql = 'UPDATE ' . DB_PREFIX . 'group_members SET user_level = 0 
			WHERE group_id = ' . $group_id . ' AND user_id = ' . $query['user_id'];
			$this->db->query($member_sql);

			$group_sql = 'UPDATE ' . DB_PREFIX . 'group SET user_id = 0,user_name = "" WHERE group_id = ' . $group_id;
			$this->db->query($group_sql);
			
			$info['msg'] = SUCCESS_OP;
			$info['tips'] = 1;
		}
		return $info;
	}
	
	//审核通过操作
	public function do_agree($user_id, $group_id, $user)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'group_members 
		WHERE group_id=' . $group_id . ' AND user_id = ' . $user_id;
		$query = $this->db->query_first($sql);
		if($query)
		{
			$sql = 'UPDATE ' . DB_PREFIX . 'group_members SET user_level = CASE WHEN user_id = ' . $user_id . ' THEN 2 ELSE 0 END 
			WHERE group_id = ' . $group_id ;
		}
		else
		{
			//如果申请地主的人不是该讨论区的会员，就将他的数据插入group_members表中，并更新group表中人数字段
			$sql = 'INSERT INTO ' . DB_PREFIX . 'group_members (group_id,user_id,user_name,join_time,user_level) 
			VALUES("' . $group_id . '","' . $user_id . '","' . $user['member_name'] . '","' . TIMENOW . '",2)';
			$this->db->query('UPDATE ' . DB_PREFIX . 'group SET group_member_count = group_member_count + 1 WHERE group_id = ' . $group_id);
		}
		
		$this->db->query($sql);
		$this->db->query('UPDATE ' . DB_PREFIX . 'user_apply SET is_agree= CASE WHEN user_id = '.$user_id.' THEN 1 ELSE 0 END ,accept_time ="' . TIMENOW . '" 
		WHERE group_id=' . $group_id);
		$this->db->query('UPDATE ' . DB_PREFIX . 'group SET user_id = ' . $user_id . ' , user_name = "' . $user['member_name'] . '" 
		WHERE group_id = ' . $group_id);
		
		return array('msg' => SUCCESS_OP, 'tips' => 1);
	}
	
	//申请成为圈子的管理者
	public function apply_creater($data)
	{
		$result = $this->is_creater($data['group_id'], $data['user_id'], true); //检测是否已是地主
		if ($result > 0)
		{
			return IS_GRAND;
		}
		$num = $this->check_creategroup_num($data['user_id']); //检测做地主的个数
		
		if ($num >= $this->settings['user_grands_num'])
		{
			return SYSTEM_LIMIT;
		}
		
		$sql = 'SELECT COUNT(id) AS num FROM ' . DB_PREFIX . 'user_apply 
		WHERE group_id = ' . $data['group_id'] . ' AND user_id = ' . $data['user_id'];
		
		$result = $this->db->query_first($sql);
		
		if ($result['num'] > 0)
		{
			return POST_APPLY;
		}
		
		$sql = 'INSERT INTO ' . DB_PREFIX . 'user_apply (user_id, group_id, apply_time) 
		VALUES('. $data['user_id'] . ', ' . $data['group_id'] . ', ' . TIMENOW . ')';
		
		if ($this->db->query($sql))
		{
			return SUCCESS_APPLY;
		}
	}
	
	//插入圈子公告信息
	public function add_notice($data)
	{
		$sqlArr = array();
		foreach($data as $k=>$v)
		{
			if (is_string($v))
			{
				$sqlArr[] = $k . '="' . $v . '"';
			}
			elseif (is_int($v))
			{
				$sqlArr[] = $k . '=' . $v;
			}
		}
		$sql = 'INSERT INTO ' . DB_PREFIX . 'announcement SET ' . implode(',', $sqlArr);
		return $this->db->query($sql);
	}
	
	//修改圈子公告信息
	public function save_notice($data, $id)
	{
		$sqlArr = array();
		foreach($data as $k=>$v)
		{
			if (is_string($v))
			{
				$sqlArr[] = $k . '="' . $v . '"';
			}
			elseif (is_int($v))
			{
				$sqlArr[] = $k . '=' . $v;
			}
		}
		$sql = 'UPDATE ' . DB_PREFIX . 'announcement SET ' . implode(',', $sqlArr) . ' WHERE id = ' . $id;
		return $this->db->query($sql);
	}
	
	//删除圈子公告信息
	public function del_notice($id)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'announcement WHERE id = ' . $id;
		return $this->db->query($sql);
	}
	
	//获取查询条件
	public function get_condition($data = array())
	{
		$condition = '';
		
		//查询的关键字
		if($data['key'])
		{
			$condition .= " AND g.name LIKE '%" . $data['key'] . "%' ";
		}
			
		//查询的起始时间
		if($data['start_time'])
		{
			$condition .= " AND g.create_time >= " . $data['start_time'];
		}
		
		//查询的结束时间
		if($data['end_time'])
		{
			$condition .= " AND g.create_time <= " . $data['end_time'];	
		}

        //查询发布的时间
        if(is_numeric($data['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
			switch($data['date_search'])
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  g.create_time > '".$yesterday."' AND g.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  g.create_time > '".$today."' AND g.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  g.create_time > '".$last_threeday."' AND g.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  g.create_time > '".$last_sevenday."' AND g.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		//查询地盘的状态
		if (is_numeric($data['state']))
		{
			switch ($data['state'])
			{
				case 1://所有状态
					$condition .= " ";
					break;
				case 2: //待审核
					$condition .= " AND g.state = 0";
					break;
				case 3://已审核
					$condition .= " AND g.state = 1";
					break;
				case 4://已关闭
					$condition .= " AND g.state = -1";
					break;
				default:
					break;
			}
		}
		//查询地盘的类型
		if(is_numeric($data['group_type']))
		{
			if($data['group_type'] == -1)
			{
				$condition .= " ";
			}
			else
			{
				$condition .= " AND g.group_type = " . $data['group_type'];
			}
		}
		
		//查询的子地盘
		if(is_numeric($data['fatherid']))
		{
			$condition .= " AND g.fatherid = " . $data['fatherid'];		
		}
		
		$group_type_hgupdn = array(
			1 => 'update_time',
			2 => 'post_count',
			3 => 'total_visit',
			4 => 'group_member_count',
		);
		
		$data['hgupdn'] = strtoupper($data['hgupdn']);
		
		if ($data['hgupdn'] != 'ASC')
		{
			$data['hgupdn'] = 'DESC';
		}
		if (!in_array($data['hgorder'], $group_type_hgupdn))
		{
			$data['hgorder'] = 'create_time';
		}
		if(is_numeric($data['_type']))
		{
			$data['hgorder'] = $group_type_hgupdn[$data['_type']];
		}
		
		$orderby = ' ORDER BY g.' . $data['hgorder']  . ' ' . $data['hgupdn'] ;

		
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $orderby;
		
		return $condition;	
	}
	
	
	//using
	/**
	*  发布系统，将内容传入发布队列
	*  
	*/	
	public function publish_insert_query($id,$op,$column_id = array(),$child_queue = 0)
	{
		$id = intval($id);
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		
		$sql = "select * from " . DB_PREFIX ."group where group_id = " . $id;
		$info = $this->db->query_first($sql);
		if(empty($column_id))
		{		
			$info['column_id'] = unserialize($info['column_id']);
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
			'set_id' 	=>	GROUP_PLAN_SET_ID,
			'from_id'   =>  $info['group_id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['name'],
			'action_type' => $op,
			'publish_time'  => $info['pub_time'],
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
		);
		if($child_queue)
		{
			$data['set_id'] = GROUP_PLAN_SET_ID;
		}
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	
	//using
	public function publish()
	{
		$id = intval($this->input['id']);
		$column_id = urldecode($this->input['column_id']);
		$new_column_id = explode(',',$column_id);
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
	
		//查询修改文章之前已经发布到的栏目
		$sql = "select * from " . DB_PREFIX ."group where group_id = " . $id;
		$q = $this->db->query_first($sql);
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		
		$sql = "update " . DB_PREFIX ."group set column_id = '". $column_id ."' where group_id = " . $id;
		$this->db->query($sql);
		
		if(intval($q['state']) == 1)
		{
			if(!empty($q['expand_id']))   //已经发布过，对比修改先后栏目
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
		}
		else    //打回
		{
			if(!empty($q['expand_id']))
			{
				$op = "delete";
				$this->publish_insert_query($id,$op);
			}
		}
		
		return true;
		
	}
}