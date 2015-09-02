<?php
require('global.php');
define('MOD_UNIQUEID','activity');
class createApi extends adminBase
{
	function __construct()
	{
		parent::__construct();
		require_once  ROOT_PATH.'lib/class/mark.class.php';
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->mark = new mark();
		$this->publish_column = new publishconfig();
		require_once  '../lib/activity.class.php';
		$this->libactivity = new activityLib();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	//审核
	public function audit()
	{
		$activity_id = trim($this->input['id']);
		if(strpos ($activity_id, ','))
		{
			$sqla = " and id in(".$activity_id.")";
		}
		else 
		{
			$sqla = " and id =".$activity_id."";
		}
		//清除创建者
		$sql = "select user_id,user_name,action_id,team_id,state from " . DB_PREFIX . "activity where 1 ".$sqla;
		$result = $this->db->fetch_all($sql);
		//更新数据
		if($result)
		{
			foreach ($result as $k=>$v)
			{
				//更新字段，更新数据
				$this->db->query("update " . DB_PREFIX . "activity set state=1 where id=".$v['id']);
				//更新小组数据
				//TODO
				$ret = $this->db->query_first("select state from " . DB_PREFIX . "activity_apply  where action_id in(".$v['id'].") and user_id =".$v['user_id']);
				if(!$ret)
				{
					if($this->db->query("insert into " . DB_PREFIX . "activity_apply set user_name='".$v['user_name']."',user_id=".$v['user_id'].",action_id=".$v['id'].",apply_time=".TIMENOW.",apply_status=0,levl=1"))
					{
						$this->db->query("update " . DB_PREFIX . "activity set yet_join=yet_join+1,apply_num=apply_num+1 where id=".$v['id']);			
					}
				}
				else 
				{
					$this->db->query("update " . DB_PREFIX . "activity_apply set state=1 where action_id in(".$v['id'].")");
				}
			}
			
			$this->addItem(true);
		}
		else 
		{
			$this->addItem(false);
		}
		$this->output();
	}
	//打回
	public function back()
	{
		$activity_id = trim($this->input['id']);
		if(strpos ($activity_id, ','))
		{
			$sqla = " and id in(".$activity_id.")";
		}
		else 
		{
			$sqla = " and id =".$activity_id."";
		}
		//清除创建者
		$sql = "select user_id,user_name,id,team_id,state,expand_id from " . DB_PREFIX . "activity where id in( ".$activity_id.")";
		$result = $this->db->fetch_all($sql);
		//更新数据
		if($result)
		{
			foreach ($result as $k=>$v)
			{
				//更新字段，更新数据
				$this->db->query("update " . DB_PREFIX . "activity set state=3 where id=".$v['id']);
			}
			$this->addItem(true);
		}
		else 
		{
			$this->addItem(false);
		}
		$this->output();
	}
	//关闭
	public function del()
	{
		$activity_id = trim($this->input['id']);
		if(strpos ($activity_id, ','))
		{
			$sqla = " and id in(".$activity_id.")";
		}
		else 
		{
			$sqla = " and id =".$activity_id."";
		}
		//清除创建者
		$sql = "select user_id,user_name,id,type_id,state from " . DB_PREFIX . "activity where id in( ".$activity_id.")";
		$result = $this->db->fetch_all($sql);
		//更新数据
		if($result)
		{
			foreach ($result as $k=>$v)
			{
				//更新字段，更新数据
				$this->db->query("update " . DB_PREFIX . "activity set state=2 where id=".$v['id']);		
			}
			$this->db->query("update " . DB_PREFIX . "activity_apply set is_del=1 where action_id in(".$activity_id.")");
			$this->addItem(true);
		}
		else 
		{
			$this->addItem(false);
		}
		$this->output();
	}
	//
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = "select * from ".DB_PREFIX . "activity  where 1 ORDER BY `create_time` DESC ".$data_limit;
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	//返回活动的信息
	public function detail()
	{
		$limit_member = isset($this->input['limit_member']) ? intval($this->input['limit_member']) : 8;
		$data_limit_member = ' LIMIT '  . $limit_member;
		$limit_actions = isset($this->input['limit_actions']) ? intval($this->input['limit_actions']) : 4;
		$data_limit_actions = ' LIMIT '  . $limit_actions;
		$action_id = trim(urldecode($this->input['action_id']));
		if(!$action_id)
		{
			$this->errorOutput("缺少参数");
		}
		$sql = "select * from " . DB_PREFIX . "activity where  action_id =".$action_id;
		$activity_info = $this->db->fetch_all($sql);
		if(!$activity_info)
		{
			$this->errorOutput("不存在该行动");
		}
		foreach ($activity_info['0'] as $akey => $val)
		{
			if($akey == 'action_img')
			{
				$val = unserialize($val);
			}
			if($akey == 'start_time' || $akey == 'end_time' || $akey == 'create_time')
			{
				$val = date('Y-m-d H:i:s' , $val);
			}
			if($akey == 'column_id')
			{
				$val = unserialize($val);
				if(is_array($val))
				{
					$column_id = array();
					foreach($val as $k => $v)
					{
						$column_id[] = $k;
					}
					$column_id = implode(',',$column_id);
					$val = $column_id;
				}
			}
			if($akey == 'sign')
			{
				$signs = $this->mark->getInfoById('mark_name',$val);
				$name = '';$sp = '';
				foreach ($signs as $sign)
				{
					$name .= $sp.$sign['mark_name'];
					$sp = ',';
				}
				$this->addItem_withkey('sign',$name);
			}
			else 
			{
				$this->addItem_withkey($akey,$val);
			}
		}
		$this->output();
	}
	//获取圈子总数
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "activity  WHERE 1 ";
		echo json_encode( $this->db->query_first($sql));
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
		
		$sql = "select * from " . DB_PREFIX ."activity where id = " . $id;
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
			'set_id' 	=>	ACTIVITY_PLAN_SET_ID,
			'from_id'   =>  $info['id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['action_name'],
			'action_type' => $op,
			'publish_time'  => $info['pub_time'],
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
		);
		if($child_queue)
		{
			$data['set_id'] = ACTIVITY_PLAN_SET_ID;
		}
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	
	/**
	 * 即时发布
	 * @param id  int   文章id
	 * @param column_id string  发布的栏目id
	 */
	public function publish()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空');
		}
		
		$id = intval($this->input['id']);
		$column_id = urldecode($this->input['column_id']);
		$new_column_id = explode(',',$column_id);
		
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
	
		//查询修改文章之前已经发布到的栏目
		$sql = "select * from " . DB_PREFIX ."activity where id = " . $id;
		$q = $this->db->query_first($sql);
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		
		$sql = "UPDATE " . DB_PREFIX ."activity SET column_id = '". $column_id ."' WHERE id = " . $id;
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
		
		$this->addItem($ret);
		$this->output();
	}	

}

$out = new createApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>