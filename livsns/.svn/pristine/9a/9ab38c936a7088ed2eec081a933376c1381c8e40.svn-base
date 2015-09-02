<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/interview_admin.class.php');
define('MOD_UNIQUEID','interview_user');//模块标识
class interview_user extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->obj = new interview_admin();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function index()
	{
		
	}
	public  function show()
	{
		$interviewid = $this->input['interview_id'];

		if(empty($interviewid))
		{
			//$this->errorOutput('无效参数');
		}
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$orderby = ' ORDER BY order_id  DESC';
		$condition = $this->get_condition();
		$sql = 'SELECT iu.*,i.title,ug.id as gid,ug.group_name,ug.role
		FROM ' .DB_PREFIX .'interview_user as iu 
		LEFT JOIN ' .DB_PREFIX .'interview as i ON iu.interview_id=i.id
		LEFT JOIN '.DB_PREFIX.'user_group as ug ON iu.group = ug.id 
		WHERE interview_id='.$interviewid.$condition.$orderby.$limit;
		$q = $this->db->query($sql);		
		$arr = array();
		$user_id = array();
		while (!false==($row =$this->db->fetch_array($q)))
		{
			/*
			//通过用户ID获取用户名，如果用户不存在，删除此访谈用户
			$user_info = $this->obj->get_userInfo($row['user_id']);
			if (!$user_info)
			{
				$sql= 'DELETE FROM '.DB_PREFIX.'interview_user WHERE user_id = '.$row['user_id'];
				$this->db->query($sql);
				continue;
			}else {
				$row['name'] = $user_info['nick_name'];
				$row['avatar'] = $user_info['avatar'];
			}
			*/
			//获取用户组权限
			/*
			$sql = 'SELECT role FROM '.DB_PREFIX.'user_group WHERE id='.$row['group'];
			$auth = $this->db->query_first($sql);
			
			$row['authority'] = $auth['role'];
			*/
			$row['authority'] = $row['role'];
			$arr['list'][] = $row;
			$user_id[] = $row['user_id'];
			
		}
		$temp = array();
		if (!empty($user_id))
		{
			//通过用户ID获取用户名，如果用户不存在，删除此访谈用户
			$user_info = $this->obj->get_userInfo(implode(',', $user_id));
			foreach ($arr['list'] as $key=>$val)
			{
				if ($user_info[$val['user_id']])
				{
					$arr['list'][$key]['name'] = $user_info[$val['user_id']]['nick_name'];
					$arr['list'][$key]['avatar'] = $user_info[$val['user_id']]['avatar'];
				}else {
					$temp[] = $val['user_id']; 
					unset($arr['list'][$key]);
				}
			}
			if (!empty($temp))
			{
				$sql= 'DELETE FROM '.DB_PREFIX.'interview_user WHERE user_id IN ( '.implode(',', $temp).')';
				$this->db->query($sql);
			}
		}
		//获取所有分组信息
		$sql = 'SELECT id,group_name FROM '.DB_PREFIX.'user_group';
		$group = $this->db->query($sql);
		while ($row =$this->db->fetch_array($group))
		{
			$arr['group'][$row['id']] = $row['group_name'];
		}
		$this->addItem($arr);
		$this->output();
	}

	public function count()
	{
		
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'interview_user WHERE interview_id='.urldecode($this->input['interview_id']).$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$k = array();
			$arr = array();
			$k['k'] = addslashes(urldecode($this->input['k']));
			$res = $this->obj->get_user($k);
			if (!empty($res))
			{
				foreach ($res as $key=>$val)
				{
					$arr[] =$val['id']; 
				}
				$con = implode(',', $arr);
				$condition .= ' AND iu.user_id IN ('.$con.') ';
			}else {
				$condition .= ' AND iu.user_id="" ';
			}
			
		}
		if(isset($this->input['interview_group']) && $this->input['interview_group']!=-1)
		{
			$condition.= ' AND iu.group = '.urldecode($this->input['interview_group']).' ';
		}
		return $condition;
	}
	
	public function detail()
	{
		if (!$this->input['id'])
		{
			//$this->errorOutput(NOID);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'files WHERE id = '.urldecode($this->input['id']);
		$res = $this->db->query_first($sql);
		$this->addItem($res);
		$this->output();
	}
	public function showUser()
	{
		$interview_id = intval($this->input['id']);
		$condition = $this->condition();
		$offset = intval($this->input['offset']);
		$condition['offset'] = $offset ? $offset : 0;
		$count = intval($this->input['count']);
		$condition['count'] = $count ? $count : 10;
		//剔除重复用户
		$sql = 'SELECT user_id FROM '.DB_PREFIX.'interview_user WHERE interview_id ='.$interview_id;
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$k[] = $row['user_id'];
		}
		if (!empty($k) && is_array($k))
		{
			$condition['not_id'] = implode(',', $k);
		}	
		$arr = $this->obj->get_user($condition);
		$new_arr = array();
		foreach ($arr as $key=>$val)
		{
			if (!in_array($val['id'], $k))
			{
				$new_arr['userinfo'][$val['id']] = $val;
				$new_arr['userinfo'][$val['id']]['interview_id'] = $interview_id;
			}	
		}
		//获取用户总数
		$num = $this->obj->get_user_num($condition);
		$new_arr['page'] = array(
				'total'=>$num,
				'perpage'=>$condition['count'],
			);	
		$this->addItem($new_arr);
		$this->output();
		
	}
	public function condition()
	{
		$condition = array();
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition['k'] = addslashes(urldecode($this->input['k']));
		}
		
		return $condition;
	}
}
$ouput= new interview_user();

if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
