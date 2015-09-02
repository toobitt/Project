<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: news.class.php 13450 2012-11-02 02:45:59Z wangleyuan $
***************************************************************************/
class recommond extends InitFrm
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
		$sql = "SELECT * FROM " . DB_PREFIX . "recommend WHERE 1 " . $cond;
		$q = $this->db->query($sql);
		$info = array();
		$id_array = array();
		while($row = $this->db->fetch_array($q))
		{ 	
			$id_array[$row['source']][] = $row['cid'];
			$info[] = $row;
		}
	//	hg_pre($id_array);
		$user = array();
		if(!empty($id_array['user']))
		{
			include_once(ROOT_PATH . 'lib/class/member.class.php');
			$obj_member = new member();
	 		$data = $obj_member->getMemberById(implode(',',$id_array['user']));
	 		$user = $data[0];
	 	//	hg_pre($user);
		}
		
		$action = array();
		if(!empty($id_array['action']))
		{
			include_once(ROOT_PATH . 'lib/class/activity.class.php');
			$obj_activity = new activityCLass();
	 		$data = $obj_activity->show(implode(',',$id_array['action']));	
	 		$action = $data['data'];
	 		$team_id = $space = '';
	 		foreach($action as $k => $v)
	 		{
		 		$team_id .= $space . $v['team_id'];
		 		$space = ',';
	 		}
	 		if($team_id)
	 		{
		 		include_once(ROOT_PATH . 'lib/class/team.class.php');
				$obj_team = new team();
	 			$team_tmp = $obj_team->get_team_by_id($team_id);	
		 		$team_tmp = $team_tmp[0];
		 		foreach($action as $k => $v)
		 		{
			 		$action[$k]['team_name'] = $team_tmp[$v['team_id']]['team_name'];
		 		}
	 		}
		}
		
		$team = array();
		if(!empty($id_array['team']))
		{
			include_once(ROOT_PATH . 'lib/class/team.class.php');
			$obj_team = new team();
	 		$data = $obj_team->get_team_by_id(implode(',',$id_array['team']));	
	 		$team = $data[0];
		}
		
		$topic = array();
		if(!empty($id_array['topic']))
		{
			include_once(ROOT_PATH . 'lib/class/team.class.php');
			$obj_team = new team();
	 		$data = $obj_team->get_topic_by_id(implode(',',$id_array['topic']));
	 		$topic = $data[0];
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "column WHERE 1";
		$q = $this->db->query($sql);
		$column = array();
		while($row = $this->db->fetch_array($q))
		{
			$column[$row['id']] = $row['name'];
		}
		$ret = array();
		foreach($info as $k => $v)
		{
			$tmp = $$v['source'];
			$ret[$k] = array(
				'id' => $v['id'],
				'cid' => $v['cid'],
				'title' => $v['title'],
				'source' => $v['source'],
				'column_id' => $column[$v['column_id']],
				'pubtime' => $v['pubtime'],
				'data' => $tmp[$v['cid']],
			);
		}
		return $ret;
	}
	
	public function show_column()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "column WHERE 1";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		return $info;
	}
	
	public function create()
	{
		$column_id = $this->input['column_id'];
		if(is_string($column_id))
		{
			$column_id = explode(',',$column_id);
		}
		foreach($column_id as $k => $v)
		{
			$info = array(
				'cid' => intval($this->input[$this->input['source'].'_id']),
				'title' => $this->input['title'],
				'source' => $this->input['source'],
				'pubtime' => TIMENOW,
				'orderid' => 0,
				'admin_id' => $this->user['user_id'],
				'admin_name' => $this->user['user_name'],
			);
			$info['column_id'] = $v;
			$sql = "SELECT id,orderid FROM " . DB_PREFIX ."recommend WHERE cid= " . $info['cid'] . " AND source ='" . $info['source'] . "' AND column_id = " . $info['column_id'];
			$q = $this->db->query_first($sql);
			if(empty($q))
			{
				$sql = "SELECT id,orderid FROM " . DB_PREFIX ."recommend WHERE column_id = " . $info['column_id'] . " ORDER BY orderid DESC LIMIT 0,1";
				$f = $this->db->query_first($sql);
				if(!empty($f))
				{
					$info['orderid'] = $f['orderid']+1;					
				}
				$sql = "INSERT INTO " . DB_PREFIX . "recommend SET ";
				$space = "";
				foreach($info as $k => $v)
				{
					$sql .= $space . $k . "='" . $v . "'";
					$space = ',';
				}
				$this->db->query($sql);
				$info['id'] = $this->db->insert_id();			
			}
			else
			{
				return true;
			}
		}
		return true;
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		$id = $this->input['id'];
		$sql = "DELETE FROM " . DB_PREFIX . "recommend WHERE id IN(".$id.")";
		$this->db->query($sql);
		return $id;
	}
	
	
	public function add_column()
	{
		$info = array(
			'name' => $this->input['name'],
		);
		$sql = "INSERT INTO " . DB_PREFIX . "column(name,fid) VALUES('" . $info['name'] . "',-1) ";
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		return $info;
	}
	
	public function update_column()
	{
		$info = array(
			'name' => $this->input['name'],
			'id' => intval($this->input['id']),
		);
		$sql = "UPDATE " . DB_PREFIX . "column SET name='" . $info['name'] . "'  WHERE id=" . $info['id'];
		$this->db->query($sql);
		return $info;
	}
	
	public function delete_column()
	{
		$id = $this->input['id'];
		$sql = "DELETE FROM " . DB_PREFIX . "column WHERE id IN(".$id.")";
		$this->db->query($sql);
		return $id;
	}
	
	public function count($cond)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "recommend WHERE 1 " . $cond; 
		$f = $this->db->query_first($sql);
		return $f;
	}
	
	public function detail()
	{
		
	}

	public function count_column($cond)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "column WHERE 1 " . $cond; 
		$f = $this->db->query_first($sql);
		return $f;
	}
	
	public function detail_column($cond)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "column WHERE 1 " . $cond;
		return $this->db->query_first($sql);
	}
	
	public function order_update($fid,$tid)
	{
		if(empty($fid) || empty($tid))
		{
			return false;
		}
		$str = $fid . ',' . $tid;
		$sql = "SELECT id,orderid FROM " . DB_PREFIX . "column WHERE id IN(" . $str . ")";
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{
			$info[$row['id']] = $row['orderid'];
		}
		$fsql = "UPDATE " . DB_PREFIX . "column SET orderid=" . $info[$tid] . " WHERE id=" . $fid;
		$this->db->query($fsql);
		$tsql = "UPDATE " . DB_PREFIX . "column SET orderid=" . $info[$fid] . " WHERE id=" . $tid;
		$this->db->query($tsql);
		return true;		
	}
}

?>