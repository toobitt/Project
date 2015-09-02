<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 13202 2012-10-27 12:32:11Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','searchQueue');//模块标识
require('global.php');
class searchQueueApi extends adminBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "search_queue WHERE 1";//新插入
		$q = $this->db->query($sql);
		$id_array = array();
		while($row = $this->db->fetch_array($q))
		{
			if($row['state'] == 0)
			{
				$id_array[$row['source']]['create'][$row['id']] = $row['cid'];
			}
			
			if($row['state'] == 1)
			{
				$id_array[$row['source']]['update'][$row['id']] = $row['cid'];
			}
			
			if($row['state'] == 2)
			{
				$id_array[$row['source']]['delete'][$row['id']] = $row['cid'];
			}
		}
		if(!empty($id_array))
		{
			$this->xs_index(array(),'search_config_team','clean');
			if(!empty($id_array['user']))
			{
				include_once(ROOT_PATH . 'lib/class/member.class.php');
				$obj_member = new member();
				if($id_array['user']['create'])
				{
					$user = array();
					$tmp = $id_array['user']['create'];
					$ids = implode(',',$tmp);
					$tmp = array_flip($tmp);
			 		$user = $obj_member->getMemberInfoById($ids);
			 		//hg_pre($user);
			 		if(!empty($user))
			 		{
				 		foreach($user as $k => $v)
				 		{
					 		$data = array(
					 			'id' => $tmp[$v['id']],
					 			'title' => $v['nick_name'],
					 			'content' => $v['talk']['content'],
					 			'source' => 'user',
					 			'user_id' => $v['id'],
					 			'user_name' => $v['nick_name'],
					 			'address' => '',
					 			'province' => $v['home_prov'],
					 			'city' => $v['home_city'],
					 			'area' => $v['home_dist'],
					 			'sex' => $v['sex'] ? ($v['sex'] == 1 ? '男' : '女') : '保密',
					 			'bloodtype' => $v['bloodtype_name'],
					 			'constellation' => $v['constellation_name'],
					 			'talk' => $v['talk']['content'],
					 			'img' => !empty($v['indexpic']) ? serialize($v['indexpic']) : '',
					 			'create_time' => TIMENOW,
					 			'update_time' => TIMENOW,
					 			'ip' => $v['ip'],
					 		);
					 	//	hg_pre($data);
					 	file_put_contents('../cache/user.php',var_export($v,1));
					 		$reback = $this->xs_index($data, 'search_config_team','add');
							$this->update_state($ids,'user',-1);
				 		}
			 		}
				}
				if($id_array['user']['update'])
				{
					$user = array();
					$tmp = $id_array['user']['update'];
					$ids = implode(',',$tmp);
					$tmp = array_flip($tmp);
			 		$user = $obj_member->getMemberInfoById($ids);
			 		//hg_pre($user);
			 		if(!empty($user))
			 		{
				 		foreach($user as $k => $v)
				 		{
					 		$data = array(
					 			'id' => $tmp[$v['id']],
					 			'title' => $v['nick_name'],
					 			'content' => $v['talk']['content'],
					 			'source' => 'user',
					 			'user_id' => $v['id'],
					 			'user_name' => $v['nick_name'],
					 			'address' => '',
					 			'province' => $v['home_prov'],
					 			'city' => $v['home_city'],
					 			'area' => $v['home_dist'],
					 			'sex' => $v['sex'] ? ($v['sex'] == 1 ? '男' : '女') : '保密',
					 			'bloodtype' => $v['bloodtype_name'],
					 			'constellation' => $v['constellation_name'],
					 			'talk' => $v['talk']['content'],
					 			'img' => !empty($v['indexpic']) ? serialize($v['indexpic']) : '',
					 			'create_time' => TIMENOW,
					 			'update_time' => TIMENOW,
					 			'ip' => $v['ip'],
					 		);
					 	//	hg_pre($data);
					 		$reback = $this->xs_index($data, 'search_config_team','update');
							$this->update_state($ids,'user',-1);
				 		}
			 		}
				}
				
				if($id_array['user']['delete'])
				{
					$data = array();
					foreach($id_array['user']['delete'] as $k => $v)
					{
						$data[] = $k;
					}
					if($data)
					{
						 $reback = $this->xs_index($data, 'search_config_team','del');
						 $this->update_state(implode(',',$id_array['user']['delete']),'user',-1);
					}		 		
				}
			}
				
			if(!empty($id_array['team']))
			{
				include_once(ROOT_PATH . 'lib/class/team.class.php');
				$obj_team = new team();
				if($id_array['team']['create'])
				{
					$team = array();
					$tmp = $id_array['team']['create'];
					$ids = implode(',',$tmp);
					$tmp = array_flip($tmp);
					$data = $obj_team->get_team_by_id($ids);	
					$team = $data[0];
					if(!empty($team))
			 		{
				 		foreach($team as $k => $v)
				 		{
					 		$data = array(
					 			'id' => $tmp[$v['team_id']],
					 			'title' => $v['team_name'],
					 			'content' => $v['introduction'],
					 			'source' => 'team',
					 			'team_id' => $v['team_id'],
					 			'team_name' => $v['team_name'],
					 			'img' => !empty($v['team_logo']) ? serialize($v['team_logo']) : '',
					 			'introduction' => $v['introduction'],
					 			'user_id' => $v['creater_id'],
					 			'user_name' => $v['creater_name'],
					 			'team_type' => $v['team_type'],
					 			'category' => $v['team_category'],
					 			'attention_num' => $v['attention_num'],
					 			'topic_num' => $v['topic_num'],
					 			'action_num' => $v['action_num'],
					 			'apply_num' => $v['apply_num'],
					 			'visit_num' => $v['visit_num'],
					 			'notice' => $v['notice'],
					 			'type_name' => $v['type_name'],
					 			'category_name' => $v['category_name'],
					 			'create_time' => TIMENOW,
					 			'update_time' => TIMENOW,
					 			'ip' => $v['team_logo']['ip'],
					 		); 		
					 	//	hg_pre($data);
					 	file_put_contents('../cache/team.php',var_export($v,1));
					 		$reback = $this->xs_index($data, 'search_config_team','add');
							$this->update_state($ids,'team',-1);
				 		}
			 		}
				}
				if($id_array['team']['update'])
				{
					$team = array();
					$tmp = $id_array['team']['update'];
					$ids = implode(',',$tmp);
					$tmp = array_flip($tmp);
					$data = $obj_team->get_team_by_id($ids);	
					$team = $data[0];
					if(!empty($team))
			 		{
				 		foreach($team as $k => $v)
				 		{
					 		$data = array(
					 			'id' => $tmp[$v['team_id']],
					 			'title' => $v['team_name'],
					 			'content' => $v['introduction'],
					 			'source' => 'team',
					 			'team_id' => $v['team_id'],
					 			'team_name' => $v['team_name'],
					 			'img' => !empty($v['team_logo']) ? serialize($v['team_logo']) : '',
					 			'introduction' => $v['introduction'],
					 			'user_id' => $v['creater_id'],
					 			'user_name' => $v['creater_name'],
					 			'team_type' => $v['team_type'],
					 			'category' => $v['team_category'],
					 			'attention_num' => $v['attention_num'],
					 			'topic_num' => $v['topic_num'],
					 			'action_num' => $v['action_num'],
					 			'apply_num' => $v['apply_num'],
					 			'visit_num' => $v['visit_num'],
					 			'notice' => $v['notice'],
					 			'type_name' => $v['type_name'],
					 			'category_name' => $v['category_name'],
					 			'create_time' => TIMENOW,
					 			'update_time' => TIMENOW,
					 			'ip' => $v['team_logo']['ip'],
					 		); 		
					 	//	hg_pre($data);
					 		$reback = $this->xs_index($data, 'search_config_team','update');
							$this->update_state($ids,'team',-1);
				 		}
			 		}
				}
				if($id_array['team']['delete'])
				{
					$data = array();
					foreach($id_array['team']['delete'] as $k => $v)
					{
						$data[] = $k;
					}
					if($data)
					{
						 $reback = $this->xs_index($data, 'search_config_team','del');
						 $this->update_state(implode(',',$id_array['team']['delete']),'team',-1);
					}
				}
			}
			
			if(!empty($id_array['action']))
			{
				include_once(ROOT_PATH . 'lib/class/activity.class.php');
				$obj_activity = new activityCLass();
		 		include_once(ROOT_PATH . 'lib/class/team.class.php');
				$obj_team = new team();
				
				if($id_array['action']['create'])
				{
					$tmp = $id_array['action']['create'];
					$ids = implode(',',$tmp);
					$tmp = array_flip($tmp);
			 		$data = $obj_activity->show($ids);
			 		$action = $data['data'];
			 		$team_id = $space = '';
			 		foreach($action as $k => $v)
			 		{ 
				 		$team_id .= $space . $v['team_id'];
				 		$space = ',';
			 		}
			 		if($team_id)
			 		{
			 			$team_tmp = $obj_team->get_team_by_id($team_id);	
				 		$team_tmp = $team_tmp[0];
				 		foreach($action as $k => $v)
				 		{
					 		$action[$k]['team_name'] = $team_tmp[$v['team_id']]['team_name'];
				 		}
			 		}
			 		if(!empty($action))
			 		{
				 		foreach($action as $k => $v)
				 		{
					 		$data = array(
					 			'id' => $tmp[$v['action_id']],
					 			'title' => $v['action_name'],
					 			'content' => $v['summary'],
					 			'source' => 'action',
					 			'action_id' => $v['action_id'],
					 			'action_name' => $v['action_name'],
					 			'user_id' => $v['user_id'],
					 			'user_name' => $v['user_name'],
					 			'img' => !empty($v['action_img']) ? serialize($v['action_img']) : '',
					 			'category' => $team_tmp[$v['team_id']]['team_category'],
					 			'team_id' => $v['team_id'],		
					 			'team_name' => $v['team_name'],
					 			'slogan' => $v['slogan'],
					 			'register_time' => $v['register_time'],
					 			'start_time' => $v['start_time'],
					 			'end_time' => $v['end_time'],
					 			'address' => $v['address'],
					 			'province' => $v['province'],
					 			'city' => $v['city'],
					 			'area' => $v['area'],
					 			'yet_join' => $v['yet_join'],
					 			'apply_num' => $v['apply_num'],
					 			'collect_num' => $v['collect_num'],
					 			'thread_num' => $v['thread_num'],
					 			'reply_num' => $v['reply_num'],
					 			'scan_num' => $v['scan_num'],
					 			'share_num' => $v['share_num'],
					 			'praise_num' => $v['praise_num'],
					 			'isopen' => $v['isopen'],
					 			'create_time' => TIMENOW,
					 			'update_time' => TIMENOW,
					 			'ip' => $v['from_ip'],
					 		); 		
					 	//	hg_pre($data);
					 	file_put_contents('../cache/action.php',var_export($v,1));
					 		$reback = $this->xs_index($data, 'search_config_team','add');
							$this->update_state($ids,'action',-1);
				 		}
			 		}			 		
				}
				
				if($id_array['action']['update'])
				{
			 		$tmp = $id_array['action']['update'];
					$ids = implode(',',$tmp);
					$tmp = array_flip($tmp);
			 		$data = $obj_activity->show($ids);
			 		$action = $data['data'];
			 		$team_id = $space = '';
			 		foreach($action as $k => $v)
			 		{ 
				 		$team_id .= $space . $v['team_id'];
				 		$space = ',';
			 		}
			 		if($team_id)
			 		{
			 			$team_tmp = $obj_team->get_team_by_id($team_id);	
				 		$team_tmp = $team_tmp[0];
				 		foreach($action as $k => $v)
				 		{
					 		$action[$k]['team_name'] = $team_tmp[$v['team_id']]['team_name'];
				 		}
			 		}
			 		if(!empty($action))
			 		{
				 		foreach($action as $k => $v)
				 		{
					 		$data = array(
					 			'id' => $tmp[$v['action_id']],
					 			'title' => $v['action_name'],
					 			'content' => $v['summary'],
					 			'source' => 'action',
					 			'action_id' => $v['action_id'],
					 			'action_name' => $v['action_name'],
					 			'user_id' => $v['user_id'],
					 			'user_name' => $v['user_name'],
					 			'img' => !empty($v['action_img']) ? serialize($v['action_img']) : '',
					 			'category' => $team_tmp[$v['team_id']]['team_category'],
					 			'team_id' => $v['team_id'],		
					 			'team_name' => $v['team_name'],
					 			'slogan' => $v['slogan'],
					 			'register_time' => $v['register_time'],
					 			'start_time' => $v['start_time'],
					 			'end_time' => $v['end_time'],
					 			'address' => $v['address'],
					 			'province' => $v['province'],
					 			'city' => $v['city'],
					 			'area' => $v['area'],
					 			'yet_join' => $v['yet_join'],
					 			'apply_num' => $v['apply_num'],
					 			'collect_num' => $v['collect_num'],
					 			'thread_num' => $v['thread_num'],
					 			'reply_num' => $v['reply_num'],
					 			'scan_num' => $v['scan_num'],
					 			'share_num' => $v['share_num'],
					 			'praise_num' => $v['praise_num'],
					 			'isopen' => $v['isopen'],
					 			'create_time' => TIMENOW,
					 			'update_time' => TIMENOW,
					 			'ip' => $v['from_ip'],
					 		); 		
					 	//	hg_pre($data);
					 		$reback = $this->xs_index($data, 'search_config_team','update');
							$this->update_state($ids,'action',-1);
				 		}
			 		}
				}
				
				if($id_array['action']['delete'])
				{
					$data = array();
					foreach($id_array['action']['delete'] as $k => $v)
					{
						$data[] = $k;
					}
					if($data)
					{
						$reback = $this->xs_index($data, 'search_config_team','del');
						$this->update_state(implode(',',$id_array['action']['delete']),'action',-1);
					}
				}
			}
			
			if(!empty($id_array['topic']))
			{
				include_once(ROOT_PATH . 'lib/class/team.class.php');
				$obj_team = new team();
				
				if($id_array['topic']['create'])
				{
					$topic = array();
					$tmp = $id_array['topic']['create'];
					$ids = implode(',',$tmp);
					$tmp = array_flip($tmp);
					$data = $obj_team->get_topic_by_id($ids);
					$topic = $data[0];
					if(!empty($topic))
			 		{			 		
				 		$team_id = $space = "";
				 		foreach($topic as $k => $v)
				 		{ 
					 		$team_id .= $space . $v['source_id'];
					 		$space = ',';
				 		}
			 			$team_tmp = $obj_team->get_team_by_id($team_id);	
				 		$team_tmp = $team_tmp[0];				 		
				 		foreach($topic as $k => $v)
				 		{
					 		$data = array(
					 			'id' => $tmp[$v['topic_id']],
					 			'title' => $v['subject'],
					 			'content' => $v['content'],
					 			'source' => 'topic',
					 			'topic_id' => $v['topic_id'],
					 			'team_id' => $v['source_id'],
					 			'team_name' => $v['source_name'],
					 			'category' => $team_tmp[$v['source_id']]['team_category'],	 			
					 			'user_id' => $v['creater_id'],
					 			'user_name' => $v['creater_name'],
					 			'topic_type' => $v['topic_type'],
					 			'material_num' => $v['material_num'],
					 			'img' => !empty($v['data']) ? serialize($v['data']) : '',
					 			'views' => $v['views'],
					 			'replies' => $v['replies'],		
					 			'favor_num' => $v['favor_num'],
					 			'is_essence' => $v['is_essence'],
					 			'is_sticky' => $v['is_sticky'],
					 			'create_time' => TIMENOW,
					 			'update_time' => TIMENOW,
					 			'ip' => $v['from_ip'],
					 		);
					 	//	hg_pre($data);
					 	file_put_contents('../cache/topic.php',var_export($v,1));
					 		$reback = $this->xs_index($data, 'search_config_team','add');
						 	$this->update_state($ids,'topic',-1);
				 		}
			 		}
				}			
				
				if($id_array['topic']['update'])
				{
					$topic = array();
					$tmp = $id_array['topic']['update'];
					$ids = implode(',',$tmp);
					$tmp = array_flip($tmp);
					$data = $obj_team->get_topic_by_id($ids);
					$topic = $data[0];
					if(!empty($topic))
			 		{	 		
			 			$team_id = $space = "";
				 		foreach($topic as $k => $v)
				 		{ 
					 		$team_id .= $space . $v['source_id'];
					 		$space = ',';
				 		}
			 			$team_tmp = $obj_team->get_team_by_id($team_id);	
				 		$team_tmp = $team_tmp[0];
				 		foreach($topic as $k => $v)
				 		{
					 		$data = array(
					 			'id' => $tmp[$v['topic_id']],
					 			'title' => $v['subject'],
					 			'content' => $v['content'],
					 			'source' => 'topic',
					 			'topic_id' => $v['topic_id'],
					 			'team_id' => $v['source_id'],
					 			'team_name' => $v['source_name'],
					 			'category' => $team_tmp[$v['source_id']]['team_category'],					 			
					 			'user_id' => $v['creater_id'],
					 			'user_name' => $v['creater_name'],
					 			'topic_type' => $v['topic_type'],
					 			'material_num' => $v['material_num'],
					 			'img' => !empty($v['data']) ? serialize($v['data']) : '',
					 			'views' => $v['views'],
					 			'replies' => $v['replies'],		
					 			'favor_num' => $v['favor_num'],
					 			'is_essence' => $v['is_essence'],
					 			'is_sticky' => $v['is_sticky'],
					 			'create_time' => TIMENOW,
					 			'update_time' => TIMENOW,
					 			'ip' => $v['from_ip'],
					 		);
					 	//	hg_pre($data);
					 		$reback = $this->xs_index($data, 'search_config_team','update');
						 	$this->update_state($ids,'topic',-1);
				 		}
			 		}
				}
				
				if($id_array['topic']['delete'])
				{
					$data = array();
					foreach($id_array['topic']['delete'] as $k => $v)
					{
						$data[] = $k;
					}
					if($data)
					{
						 //	$reback = $this->xs_index($data, 'search_config_team','del');
						 $this->update_state(implode(',',$id_array['topic']['delete']),'topic',-1);
					}			
				}
			}
		}
	}
	
	public function import()
	{
		$source = $this->input["source"] ? trim($this->input["source"]) : 'user';
		switch($source)
		{
			case 'user':
				require_once ROOT_PATH . 'lib/class/member.class.php';
				$obj_member = new member();
				$ret = $obj_member->show(-1);
				if(!empty($ret))
				{
					$sql = "DELETE FROM " . DB_PREFIX . "search_queue WHERE source='" . $source . "'";
					$this->db->query($sql);
					$sql = "INSERT IGNORE INTO " . DB_PREFIX . "search_queue(cid,source,state) VALUES";
					$space = "";
					foreach($ret as $v)
					{
						$sql .= $space . "(" . $v['id'] . ",'" . $source . "',0)";
						$space = ',';
					}
					$this->db->query($sql);
					echo "用户完成插入队列";
				}
				else
				{
					echo "暂无用户数据";
				}
			break;
			case 'team':
				require_once ROOT_PATH . 'lib/class/team.class.php';
				$obj_team = new team();
				$ret = $obj_team->show(-1);
				if(!empty($ret))
				{
					$sql = "DELETE FROM " . DB_PREFIX . "search_queue WHERE source='" . $source . "'";
					$this->db->query($sql);
					$sql = "INSERT IGNORE INTO " . DB_PREFIX . "search_queue(cid,source,state) VALUES";
					$space = "";
					foreach($ret as $v)
					{
						$sql .= $space . "(" . $v['team_id'] . ",'" . $source . "',0)";
						$space = ',';
					}
					$this->db->query($sql);
					echo "小组完成插入队列";
				}	
				else
				{
					echo "暂无小组数据";
				}			
			break;
			case 'action':
				require_once ROOT_PATH . 'lib/class/activity.class.php';
				$obj_activity = new activityCLass();
				$ret_tmp = $obj_activity->show_all();
				$ret = $ret_tmp['data'];
				if(!empty($ret))
				{
					$sql = "DELETE FROM " . DB_PREFIX . "search_queue WHERE source='" . $source . "'";
					$this->db->query($sql);
					$sql = "INSERT IGNORE INTO " . DB_PREFIX . "search_queue(cid,source,state) VALUES";
					$space = "";
					foreach($ret as $v)
					{
						$sql .= $space . "(" . $v['action_id'] . ",'" . $source . "',0)";
						$space = ',';
					}
					$this->db->query($sql);
					echo "行动完成插入队列";
				}
				else
				{
					echo "暂无行动数据";
				}
			break;
			case 'topic':
				require_once ROOT_PATH . 'lib/class/team.class.php';
				$obj_team = new team();
				$ret = $obj_team->show_topic(-1);
				if(!empty($ret))
				{
					$sql = "DELETE FROM " . DB_PREFIX . "search_queue WHERE source='" . $source . "'";
					$this->db->query($sql);
					$sql = "INSERT IGNORE INTO " . DB_PREFIX . "search_queue(cid,source,state) VALUES";
					$space = "";
					foreach($ret as $v)
					{
						$sql .= $space . "(" . $v['topic_id'] . ",'" . $source . "',0)";
						$space = ',';
					}
					$this->db->query($sql);
					echo "讨论完成插入队列";
				}
				else
				{
					echo "暂无讨论数据";
				}	
			break;
			default:
			break;
		}
	}
	
	public function update_state($id,$source,$state)
	{
		$sql = "UPDATE " . DB_PREFIX . "search_queue SET state=" . $state . " WHERE cid IN(" . $id . ") AND source='" . $source . "'";
		$this->db->query($sql);
	}
	
	public function create()
	{
		$state = 0;
		$this->dispose($state);
		$this->addItem(true);
		$this->output();
	}
	
	public function update()
	{
		$state = 1;
		$this->dispose($state);
		$this->addItem(true);
		$this->output();
	}
	
	public function delete()
	{
		$state = 2;
		$this->dispose($state);
		$this->addItem(true);
		$this->output();
	}
	
	private function dispose($state)
	{
		$id = $this->input['id'] ?  trim($this->input['id']) : 0;
		$source = $this->input['source'] ?  trim($this->input['source']) : '';
		if(!$id || !$source)
		{
			return false;
		}
		$sql = "SELECT * FROM  " . DB_PREFIX . "search_queue WHERE cid IN(" . $id . ") AND source='" . $source . "'";
		$q = $this->db->query($sql);
		$update_id = $space = "";
		$all = $update = $insert = array();
		$all = explode(',',$id);
		while($row = $this->db->fetch_array($q))
		{
			$update_id .= $space . $row['cid'];
			$update[] = $row['cid'];
			$space = ',';
		}		
		if($update_id)
		{
			$sql = "UPDATE " . DB_PREFIX . "search_queue SET state=" . $state . " WHERE cid=" . $update_id . " AND source='" . $source . "'";
			$this->db->query($sql);
		}		
		$insert = array_diff($all,$update);
		if(!empty($insert))
		{
			$sql = "INSERT IGNORE INTO " . DB_PREFIX . "search_queue(cid,source,state) VALUES";
			$space = "";
			foreach($insert as $k => $v)
			{
				$sql .= $space . "(" . $v . ",'" . $source . "'," . $state . ")";
				$space = ",";
			}
			$this->db->query($sql);
		}
	}
	
	/**
	 *  $array_field      数组的字段
	 *  $highlight_field  需要高亮的字段
	 *  搜索语句最大支持长度为 80 字节（每一个汉字占 3 字节）
	 * 	addDB($name) - 用于多库搜索，添加数据库名称
		addRange($field, $from, $to) - 添加搜索过滤区间或范围
		addWeight($field, $term) - 添加权重索引词
		setCharset($charset) - 设置字符集
		setCollapse($field, $num = 1) - 设置搜索结果按字段值折叠
		setDb($name) - 设置搜索库名称，默认则为 db
		setFuzzy() - 设置开启模糊搜索, 传入参数 false 可关闭模糊搜索
		setLimit($limit, $offset = 0) - 设置搜索结果返回的数量和偏移
		setQuery() - 设置搜索语句
		setSort($field, $asc = false) - 设置搜索结果按字段值排序
		setFacets 第一参数为要分面的字段名称（多个字段请用数组作参数）， 第二参数是可选的布尔类型，true 表示需要准确统计，默认 false 则为估算
		getFacets 返回数组，以 fid 为键，匹配数量为值
		
		$searchdata = array(
			'charset'      =>      'utf-8',  //设置返回的字符编码
			'query'        =>      'bundle_id:(tuji) AND client_type:(2) OR column_id:(4) NOT site_id:(1) XOR 西湖', //查询语句
			'fuzzy'        =>       1为true 0为false,//模糊查询为true否则false
			'limit'        =>       'count,offset',
			'range'		   =>       array('publish_time'=>'1343432345,1463784752','create_time'=>'1343432345,1463784752'),//值的范围
			'sort'         =>        array('id'=>true,'weight'=>false),  //k为字段，false表示降序，true表示升序
			'weight'	   =>       array('title'=>'你好','brief'=>'好'),// 增加附加条件：提升标题中包含 'xunsearch' 的记录的权重
			'autosynonyms' =>		1为true 0为false,  //设为 true 表示开启同义词功能, 设为 false 关闭同义词功能
			'setfacets_fields' =>   array('field'=>array('fid','year'),'count_type'=>0或1), 该方法接受两个参数，第一参数为要分面的字段名称（多个字段请用数组作参数）， 第二参数是可选的布尔类型，1 表示需要准确统计，默认 0 则为估算
			'hotquery'     =>       array('limit'=>10,'type'=>'total'or'lastnum'or'currnum'),$limit 整数值，设置要返回的词数量上限，默认为 6，最大值为 50;$type 指定排序类型，默认为 total(总量)，可选值还有：lastnum(上周) 和 currnum(本周)
		);
	 * */
	public function search()
	{
		$count = $this->input['count'] ? intval($this->input['count']) : 50;
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$query = $this->get_condition();
		$searchdata = array(
			'charset' => 'utf-8',
			'fuzzy' => 1,
			'query' => $query,
			'limit' => $count . ',' . $offset,
			'range' => '',
			'sort' => array('create_time' => false),
		);
	//	hg_pre($searchdata);
		
		switch($this->input['source'])
		{
			case 'team':
				if($this->input['order'] == 'hot')
				{
					$searchdata['sort']['action_num'] = false;
					$searchdata['sort']['attention_num'] = false;
				}
				if($this->input['order'] == 'new')
				{
					$searchdata['sort']['create_time'] = false;
				}
			break;
			case 'action':
				if($this->input['order'] == 'hot')
				{
					$searchdata['sort']['apply_num'] = false;
					$searchdata['sort']['praise_num'] = false;
				}
				if($this->input['order'] == 'new')
				{
					$searchdata['sort']['create_time'] = false;
				}
				switch(intval($this->input['time_type']))//即将，正在，结束
				{
					case 1:
						$searchdata['range'] = array('start_time' => TIMENOW . ',' );
					break;
					case 2:
						$searchdata['range'] = array('start_time' => ',' . TIMENOW ,'end_time' => TIMENOW . ',');					
					break;
					case 3:
						$searchdata['range'] = array('end_time' => ',' . TIMENOW);
					break;
					default:
						//$searchdata['range'] = array('start_time' => TIMENOW . ',' );
					break;
				}
			break;
			case 'topic':
				if($this->input['order'] == 'hot')
				{
					$searchdata['sort']['replies'] = false;
				}
				if($this->input['order'] == 'new')
				{
					$searchdata['sort']['create_time'] = false;
				}
			break;
			default:
			break;
		}
		if($this->input['hot'])
		{
			$data .= $tag . " team_type:(" . trim($this->input['team_type']) . ") ";
			$tag = " AND ";	
		}
//		file_put_contents('../cache/1.php',var_export($searchdata,1));
		$ret = $this->xs_search($searchdata,"search_config_team",array('img'),array('title','content','introduction','slogan'));
//		hg_pre($ret);
		$this->addItem($ret);
		$this->output();
	}
	
	public function count()
	{
		$query = $this->get_condition();
		$searchdata = array(
			'charset' => 'utf-8',
			'query' => $query,
			'fuzzy' => 1,
		);
		$ret = $this->xs_search($searchdata,"search_config_team");
		$this->addItem(array('total'=>$ret['count']));
		$this->output();
	}
	
	private function get_condition()
	{
		$tag = $data = '';
		if($this->input['source'])
		{
			$data .= $tag . " source:(" . trim($this->input['source']) . ") ";
			$tag = " AND ";
			if($this->input['category'] > 0)
			{
				$data .= $tag . " category:(" . trim($this->input['category']) . ") ";
				$tag = " AND ";
			}
			
			if($this->input['team_type'] > 0)
			{
				$data .= $tag . " team_type:(" . trim($this->input['team_type']) . ") ";
				$tag = " AND ";
			}
		}
		if($this->input['title'])
		{			
			$data .= $tag . trim($this->input['title']);
			//$tag = ' OR ';
			//$data .= $tag . " content:(" . ($this->input['title'] ? trim($this->input['title']) : '') . ") ";
			$tag = " AND ";
		}
		return $data;
		
	}
	
	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new searchQueueApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>	