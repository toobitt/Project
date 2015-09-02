<?php
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
class MagazineClass extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->publish_column = new publishconfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition='',$orderby='ORDER BY m.order_id DESC',$offset=0,$count=10)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT m.*,i.issue,i.total_issue,i.pub_date,i.create_time as issue_update_time,i.user_name as issue_user_name,i.state as issue_state,i.top,i.recommend,i.host,i.dir,i.file_path,i.file_name,s.name as sort_name FROM '.DB_PREFIX.'magazine m  
				LEFT  JOIN  '.DB_PREFIX.'magazine_node s 
				ON m.sort_id = s.id 
				LEFT JOIN '.DB_PREFIX.'issue i 
				ON m.issue_id = i.id WHERE 1 '.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			if ($r['update_time'])
			{
				$r['update_time'] = date('Y-m-d H:i',$r['update_time']);
			}
			else 
			{
				$r['update_time'] = '- -';
			}
			
			if($r['issue_update_time'])
			{
				$r['issue_update_time'] = date('Y-m-d H:i',$r['issue_update_time']);
			}
			
			if ($r['pub_date'])
			{
				$r['pub_date'] = date('Y-m-d',$r['pub_date']);
				$r['year'] = substr($r['pub_date'], 0,4);
			}
			else 
			{
				$r['pub_date'] = '- -';
				$r['year'] = substr($r['create_time'], 0,4);
			}
			switch ($r['state'])
			{
				case  1: $r['audit'] = '已审核';break;
				case  2: $r['audit'] = '已打回';break;
				default: $r['audit'] = '待审核';
			}
			if (!$r['user_name'])
			{
				$r['user_name'] = '匿名用户';
			}
			
			$r['sort_name'] = $r['sort_name'] ? $r['sort_name'] : '未分类';
			$res[] = $r;
		}
		return $res;
	} 
	
	public function detail($id)
	{
		if(!$id)
		{
			return false;
		}
		$sql = 'SELECT * FROM  '.DB_PREFIX.'magazine 
		WHERE id = '.$id;
		$r = $this->db->query_first($sql);
		$r['contract'] = unserialize($r['contract_way']);
		$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
		$r['update_time'] = date('Y-m-d h:i:s',$r['update_time']);
		//发布栏目
        $column_id = unserialize($r['column_id'])?unserialize($r['column_id']):array();
        if (is_array($column_id))
        {
        	$r['column_id'] = implode(',', array_keys($column_id));
        }	
		return $r;
	}
	public function append_sort()
	{
		$sql = "SELECT id,name as sort_name FROM " . DB_PREFIX . "magazine_node";
		
		$query = $this->db->query($sql);
		$return = array();
		while($j = $this->db->fetch_array($query))
		{
			$return[$j['id']] = $j['sort_name'];
		}
		return $return;
	}
	public function publish()
	{
		$id = intval($this->input['id']);
		//发布之前检测该条杂志的状态，只有审核通过的才可以发布
		$sql = "SELECT * FROM ".DB_PREFIX."magazine WHERE id = ".$id;
	    $ret = $this->db->query_first($sql);	
	    //获取栏目发布的id
		$column_id = urldecode($this->input['column_id']);
		$new_column_id = explode(',',$column_id);
		//通过id获取发布栏目名称
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
	    		
		$sql = "UPDATE " . DB_PREFIX ."magazine SET column_id = '". $column_id ."' WHERE id = " . $id;
		$this->db->query($sql);
		
		//查询该杂志是否发布，以及发布到哪个栏目下
		$ret['column_id'] = unserialize($ret['column_id']);
		
		//将之前的栏目id放入数组中，准备对比
		$old_column_id = array();
		if (is_array($ret['column_id']))
		{
			$old_column_id = array_keys($ret['column_id']);
		}
	 	
		if (!empty($ret['expand_id']))
		{
			$del_column = array_diff($old_column_id,$new_column_id);
			
			if (!empty($del_column))
			{
				$this->publish_insert_query($id, 'delete',$del_column);
			}		
			$add_column = array_diff($new_column_id,$old_column_id);
			if (!empty($add_column))
			{
				$this->publish_insert_query($id, 'insert',$add_column);
			}
			$same_column = array_intersect($old_column_id,$new_column_id);
			if(!empty($same_column))
			{
				$this->publish_insert_query($id, 'update',$same_column);
			}
		}
		else
		{
			$op = "insert";
			$this->publish_insert_query($id,$op);
		}
	 
		return true;	
	}
	/**
	*  发布系统，将内容传入发布队列
	*  
	*/	
	public function publish_insert_query($magazineId,$op,$column_id = array())
	{
		$id = intval($magazineId);
		if (empty($id) || empty($op))
		{
			return false;
		}
		$sql = "SELECT  *  FROM ".DB_PREFIX."magazine WHERE id = " . $id;
		$info = $this->db->query_first($sql);
		
		if (empty($column_id))
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
			'set_id' 	=>	MAGAZINE_PLAN_SET_ID,
			'from_id'   =>  $info['id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['name'],
			'action_type' => $op,
			'publish_time'  => TIMENOW,
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	}
}