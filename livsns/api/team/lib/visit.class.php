<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: news.class.php 13450 2012-11-02 02:45:59Z wangleyuan $
***************************************************************************/
class visit extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($cond)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "visit WHERE 1 " . $cond; 
		$q = $this->db->query($sql);
		$member_id = $action_id = $team_id = $topic_id = '';
		$member_space = $action_space = $team_space = $topic_space = '';
		$info = array();
		while($row = $this->db->fetch_array($q))
		{ 	
			switch($row['source'])
			{
				case 'user':
						$member_id .= $member_space . $row['cid'];
						$member_space = ",";						
					break;
				case 'action':
						$action_id .= $action_space . $row['cid'];
						$action_space = ",";
					break;
				case 'team':
						$team_id .= $team_space . $row['cid'];
						$team_space = ",";
					break;
				case 'topic':
						$topic_id .= $topic_space . $row['cid'];
						$topic_space = ",";
					break;
				default:
				break;
			}
			$info[] = array(
				'id' => $row['id'],
				'source' => $row['source'],
				'cid' => $row['cid'],
				'visit_time' => $row['visit_time'],
			);
		}
		$member_info = $action_info = $team_info = $topic_info = array();
		if(!empty($member_id))
		{
			include_once(ROOT_PATH . 'lib/class/member.class.php');
			$obj_member = new member();
	 		$data = $obj_member->getMemberById($member_id);
	 		$member_info = $data[0];
		}
		
		if(!empty($action_id))
		{
			include_once(ROOT_PATH . 'lib/class/activity.class.php');
			$obj_activity = new activityCLass();
	 		$data = $obj_activity->show($action_id);
	 		$action_info = $data['data'];
		}
		
		
		if(!empty($team_id))
		{
			include_once(ROOT_PATH . 'lib/class/team.class.php');
			$obj_team = new team();
	 		$data = $obj_team->get_team_by_id($team_id);	
	 		$team_info = $data[0];
		}
		
		
		if(!empty($topic_id))
		{
			include_once(ROOT_PATH . 'lib/class/team.class.php');
			$obj_team = new team();
	 		$data = $obj_team->get_topic_by_id($topic_id);
	 		$topic_info = $data[0];
		}
		//
		foreach($info as $k => $v)
		{
			switch($v['source'])
			{
				case 'user':
						if(empty($member_info[$v['cid']]))
						{
							unset($info[$k]);
						}
						else
						{
							$info[$k]['data'] = $member_info[$v['cid']];
						}
					break;
				case 'action':
						if(empty($action_info[$v['cid']]))
						{
							unset($info[$k]);
						}
						else
						{
							$info[$k]['data'] = $action_info[$v['cid']];
						}
					break;
				case 'team':
						if(empty($team_info[$v['cid']]))
						{
							unset($info[$k]);
						}
						else
						{
							$info[$k]['data'] = $team_info[$v['cid']];
						}
					break;
				case 'topic':
						if(empty($topic_info[$v['cid']]))
						{
							unset($info[$k]);
						}
						else
						{
							$info[$k]['data'] = $topic_info[$v['cid']];
						}
					break;
				default:
				break;
			}
		}
		return $info;
	}
	
	/*
	*获取某用户经常去的小组
	*
	*/
	public function getOftenById($member_id,$source="",$con = "")
	{
		if(empty($member_id))
		{
			return false;		
		}
		$cond = '';
		if($source)
		{
			$cond = " AND source='" . $source . "' ";
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "visit WHERE 1 " . $cond . " AND user_id=" . $member_id . " ORDER BY num DESC" . $con;
		$q = $this->db->query($sql);
		$member_id = $action_id = $team_id = $topic_id = '';
		$member_space = $action_space = $team_space = $topic_space = '';
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			switch($row['source'])
			{
				case 'user':
						$member_id .= $member_space . $row['cid'];
						$member_space = ",";						
					break;
				case 'action':
						$action_id .= $action_space . $row['cid'];
						$action_space = ",";
					break;
				case 'team':
						$team_id .= $team_space . $row['cid'];
						$team_space = ",";
					break;
				case 'topic':
						$topic_id .= $topic_space . $row['cid'];
						$topic_space = ",";
					break;
				default:
				break;
			}
			$info[] = array(
				'id' => $row['id'],
				'source' => $row['source'],
				'cid' => $row['cid'],
				'visit_time' => $row['visit_time'],
			);
		}
		$member_info = $action_info = $team_info = $topic_info = array();
		if(!empty($member_id))
		{
			include_once(ROOT_PATH . 'lib/class/member.class.php');
			$obj_member = new member();
	 		$data = $obj_member->getMemberById($member_id);
	 		$member_info = $data[0];
		}
		
		if(!empty($action_id))
		{
			include_once(ROOT_PATH . 'lib/class/activity.class.php');
			$obj_activity = new activityCLass();
	 		$data = $obj_activity->show($action_id);
	 		$action_info = $data['data'];
		}
		
		
		if(!empty($team_id))
		{
			include_once(ROOT_PATH . 'lib/class/team.class.php');
			$obj_team = new team();
	 		$data = $obj_team->get_team_by_id($team_id);	
	 		$team_info = $data[0];
		}
		
		
		if(!empty($topic_id))
		{
			include_once(ROOT_PATH . 'lib/class/team.class.php');
			$obj_team = new team();
	 		$data = $obj_team->get_topic_by_id($topic_id);
	 		$topic_info = $data[0];
		}
		//
		foreach($info as $k => $v)
		{
			switch($v['source'])
			{
				case 'user':
						if(empty($member_info[$v['cid']]))
						{
							unset($info[$k]);
						}
						else
						{
							$info[$k]['data'] = $member_info[$v['cid']];
						}
					break;
				case 'action':
						if(empty($action_info[$v['cid']]))
						{
							unset($info[$k]);
						}
						else
						{
							$info[$k]['data'] = $action_info[$v['cid']];
						}
					break;
				case 'team':
						if(empty($team_info[$v['cid']]))
						{
							unset($info[$k]);
						}
						else
						{
							$info[$k]['data'] = $team_info[$v['cid']];
						}
					break;
				case 'topic':
						if(empty($topic_info[$v['cid']]))
						{
							unset($info[$k]);
						}
						else
						{
							$info[$k]['data'] = $topic_info[$v['cid']];
						}
					break;
				default:
				break;
			}
		}
		return $info;
	}
	
	public function create()
	{
		if(empty($this->user['user_id']))
		{
			return false;
		}
		$info = array(
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'cid' => intval($this->input['cid']),
			'source' => trim($this->input['source']), //user,action,team
			'visit_time' => TIMENOW,
			'ip' => hg_getip(),
		);
		
		if(empty($info['cid']) || empty($info['source']))
		{
			return false;
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "visit WHERE user_id=" . $info['user_id'] . " AND cid=" . $info['cid'] . " AND source='" . $info['source'] . "'";
		$f = $this->db->query_first($sql);
		$flag = false;
		if(empty($f))//为空，插入一条新纪录
		{
			$info['num'] = 1;
			$sql = "INSERT INTO " . DB_PREFIX . "visit SET ";
			$space = "";
			foreach($info as $k => $v)
			{
				$sql .= $space . $k . "='" . $v . "'";
				$space = ',';
			}
			$this->db->query($sql);
			unset($info['num']);
			$sql = "INSERT INTO " . DB_PREFIX . "visit_queue SET ";
			$space = "";
			foreach($info as $k => $v)
			{
				$sql .= $space . $k . "='" . $v . "'";
				$space = ',';
			}
			$this->db->query($sql);
			$flag = true;
		}
		else//不为空，验证
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "visit WHERE user_id=" . $info['user_id'] . " AND source='" . $info['source'] . "' ORDER BY visit_time DESC ";
			$sen = $this->db->query_first($sql);
			if($sen && $sen['cid'] == $info['cid'])//相同更新时间
			{
				$sql = "UPDATE " . DB_PREFIX . "visit SET visit_time=" . TIMENOW . " WHERE id=" . $sen['id'];
				$this->db->query($sql);
				$flag = false;
			}//不同，更新时间，插入纪录，更新次数
			else
			{
				$sql = "UPDATE " . DB_PREFIX . "visit SET num=num+1 WHERE user_id=" . $info['user_id'] . " AND cid=" . $info['cid'] . " AND source='" . $info['source'] . "'";
				$this->db->query($sql);
				$sql = "INSERT INTO " . DB_PREFIX . "visit_queue SET ";
				$space = "";
				foreach($info as $k => $v)
				{
					$sql .= $space . $k . "='" . $v . "'";
					$space = ',';
				}
				$this->db->query($sql);
				$flag = true;
			}
		}
		if($flag)
		{
			switch($info['source'])
			{
				case 'user':
						include_once(ROOT_PATH . 'lib/class/member.class.php');
						$obj_member = new member();
				 		$obj_member->add_visit($info['cid']);
					break;
				case 'action':
						include_once(ROOT_PATH . 'lib/class/activity.class.php');
						$obj_activity = new activityCLass();
				 		$obj_activity->updateAddData(array('scan_num' => 1,'action_id'=>$info['cid']));
					break;
				case 'team':
						include_once(ROOT_PATH . 'lib/class/team.class.php');
						$obj_team = new team();
				 		$obj_team->update_total(array('visit_num' => 1,'team_id'=>$info['cid']));
					break;
				case 'topic':
						include_once(ROOT_PATH . 'lib/class/team.class.php');
						$obj_team = new team();
				 		$obj_team->update_topic_views(array('view_num' => 1,'topic_id'=>$info['cid']));
					break;
				default:
				break;
			}
		}
		return true;		
	}
	
	public function update()
	{
		
	}
	
	public function delete($cid,$source)
	{
		$info = array(
			'cid' => $cid,
			'source' => $source, //user,action,team
		);
		if(empty($info['cid']) && $info['source'])
		{
			return false;
		}
		$sql = "DELETE FROM " . DB_PREFIX . "visit WHERE cid IN(" . $info['cid'] . ") AND source='" . $info['source'] . "'";
		$this->db->query($sql);
		$sql = "DELETE FROM " . DB_PREFIX . "visit_queue WHERE cid IN(" . $info['cid'] . ") AND source='" . $info['source'] . "'";
		$this->db->query($sql);
		switch($info['source'])
		{
			case 'user':
					include_once(ROOT_PATH . 'lib/class/member.class.php');
					$obj_member = new member();
			 		$obj_member->add_visit($info['cid'],-1);
				break;
			case 'action':
					include_once(ROOT_PATH . 'lib/class/activity.class.php');
					$obj_activity = new activityCLass();
			 		$obj_activity->updateAddData(array('scan_num' => -1,'action_id'=>$info['cid']));
				break;
			case 'team':
					include_once(ROOT_PATH . 'lib/class/team.class.php');
					$obj_team = new team();
			 		$obj_team->update_total(array('visit_num' => -1,'team_id'=>$info['cid']));
				break;
			case 'topic':
					include_once(ROOT_PATH . 'lib/class/team.class.php');
					$obj_team = new team();
			 		$obj_team->update_topic_views(array('view_num' => -1,'topic_id'=>$info['cid']));
				break;
			default:
			break;
		}
		return true;
	}
	
	public function count($cond)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "visit WHERE 1 " . $cond; 
		$f = $this->db->query_first($sql);
		return $f;
	}
	
	public function detail()
	{
		
	}
}

?>