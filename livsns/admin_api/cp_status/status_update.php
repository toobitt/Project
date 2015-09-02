<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: status_update.php 12443 2012-10-12 02:00:56Z wangleyuan $
***************************************************************************/
define('ROOT_DIR', '../../');
define('SCREEN', 1);
define('MOD_UNIQUEID','mblog_status');
require_once ROOT_DIR.'global.php';
class statusUpdateApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		//调用user类 获取用户信息时用到
		include_once(ROOT_DIR.'lib/user/user.class.php');
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		//$this->user = new user();
		$this->recycle = new recycle();
		require_once(ROOT_PATH . 'lib/class/logs.class.php');
		$this->logs = new logs();
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
	}
	public function __destruct()
	{
		parent::__destruct();
		$this->db->close;
	}
	/**
	 * 微博屏蔽方法 将数据库中status字段设置为1 屏蔽
	 */
	public function audit()
	{
		$this->preFilterId();
		$state = intval($this->input['audit']) == 0 ? 1 : 0; //0 － 为审核通过， 1－ 为屏蔽
		$sql = 'UPDATE ' . DB_PREFIX . 'status' . ' SET status = ' . intval($state) . ' WHERE id IN (' . $this->input['id'] . ')';
		//exit($sql);
		$sql = "SELECT * FROM " . DB_PREFIX ."status WHERE id IN(" . $this->input['id'] . ")";
		$ret = $this->db->query($sql);
		while($info = $this->db->fetch_array($ret))
		{
			if($info['status'] ==0)
			{
				if(!empty($info['expand_id']))
				{
					$op = "update";			
				}
				else
				{
					$op = "insert";
				}
			}
			else 
			{
				if(!empty($info['expand_id']))
				{
					$op = "delete";
				}
				else
				{
					$op = "";
				}
			}
			$this->publish_insert_query($info['id'], $op);
		}		
		if ($this->db->query($sql))
		{
			$ids[] = explode(',', $this->input['id']);
		}
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'audit', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		
		$this->addItem($ids);
		$this->output();
	}
	
	public function stateAudit()
	{
		$state = intval($this->input['audit']) == 0 ? 1 : 0; //0 － 为审核通过， 1－ 为屏蔽
		
		$new_state = '';
		
		if ($state)
		{
			$sql = 'UPDATE '. DB_PREFIX . 'status' . ' SET status = ' . intval($state) . ' WHERE id IN (' . urldecode($this->input['id']) . ')';
			$this->db->query($sql);
			$new_state = 1;
		}
		else 
		{
			$sql = 'UPDATE '. DB_PREFIX . 'status' . ' SET status = ' . intval($state) . ' WHERE id IN (' . urldecode($this->input['id']) . ')';
			$this->db->query($sql);
			$new_state = 0;
		}
		$sql = "SELECT * FROM " . DB_PREFIX ."status WHERE id IN(" . urldecode($this->input['id']) . ")";
		$ret = $this->db->query($sql);
		while($info = $this->db->fetch_array($ret))
		{
			if($info['status'] ==0)
			{
				if(!empty($info['expand_id']))
				{
					$op = "update";			
				}
				else
				{
					$op = "insert";
				}
			}
			else 
			{
				if(!empty($info['expand_id']))
				{
					$op = "delete";
				}
				else
				{
					$op = "";
				}
			}
			$this->publish_insert_query($info['id'], $op);
		}
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'stateAudit', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		$this->addItem($new_state);
		$this->output();
	}
	public function update()
	{
		$id = intval($this->input['id']);	
		$state = intval($this->input['status']); 
		$text = addslashes(trim($this->input['text']));
		$create_at = strtotime($this->input['create_at']);
		if ($text)
		{
			$set = ",text='{$text}'";
		}
		if ($create_at > 0)
		{
			$set .= ",create_at='{$create_at}'";
		}
		$sql = 'UPDATE '.DB_PREFIX.'status SET status = ' . intval($state) . $set . ' WHERE id=' . $id;
		$this->db->query($sql);
		if($rows = $this->db->affected_rows())
		{
			$this->addItem('success');
		}
		else
		{
			$this->addItem('error');
		}
		$this->output();
	}
	/**
	 * 删除微博 同时更新用户最新的微博记录
	 */
	public function delete()
	{
		$this->preFilterId();
		$sql = "select * from ".DB_PREFIX."status where id in(".$this->input['id'].")";
		$r = $this->db->query($sql);
		while($row = $this->db->fetch_array($r))
		{
			if(intval($row['status']) == 1 && $row['expand_id'])
			{
				$op = "delete";
				$this->publish_insert_query($row['id'],$op);
			}
			$data[$row['id']] = array(
				'title' => $row['text'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
			);
			$data[$row['id']]['content']['status'] = $row;
		}
		$sql = "select * from ".DB_PREFIX."status_comments where status_id in(".$this->input['id'].")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$data[$row['status_id']]['content']['status_comments'][]= $row;
		}
		if(!empty($data))
		{
			foreach($data as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
		}
		if($res['sucess'])
		{
			$sql = "delete from ".DB_PREFIX."status where id in(".$this->input['id'].")";
			//file_put_contents('111.php',$sql);
			$this->db->query($sql);
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
			$sql = "delete from ".DB_PREFIX."status_comments where status_id in(".$this->input['id'].")";
			$this->db->query($sql);
			$this->addItem('success');
		}
		else
		{
			$this->errorOutput('删除失败！');
		}
		
		$this->output();
	}

	public function delete_comp()
	{
		if(empty($this->input['cid']))
		{
			return false;
		}
		$cid = urldecode($this->input['cid']);

		$sql = "delete ".DB_PREFIX."status_member where status_id in(".$this->input['id'].")";
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete_comp', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		$sql = "delete ".DB_PREFIX."status_topic where status_id in(".$this->input['id'].")";
		$this->db->query($sql);
		$sql = "delete ".DB_PREFIX."status_favorites where status_id in(".$this->input['id'].")";
		$this->db->query($sql);
		$sql = "delete ".DB_PREFIX."status_extra where status_id in(".$this->input['id'].")";
		$this->db->query($sql);

		return true;
	}

	/*public function delete()
	{
		//传入参数的检测
		$this->preFilterId();
		//取出地址栏中的所有传入的微博ID
		$ids_in_url = explode(',',$this->input['id']);
		//获取数据库中的与之对应的ID和member_id(用于获取用户信息)
		$ids_in_db = $this->select_status_id();
		if(!$ids_in_db)
		{
			$this->errorOutput(DELETE_FALES);
		}
		//如果存在差集则删除失败
		$diff = array_diff($ids_in_url, $ids_in_db['id']);
		if($diff)
		{
			$this->errorOutput(DELETE_FALES);
		}
		//hg_pre($ids_in_db);
		//删除m_blog_push数据库中的中的数据
		include_once(ROOT_DIR.'lib/class/push.class.php');
		$push = new push();
		//由于删除数据执行的delete操作返回是个资源 所以此处恒为true
		$flag = $push->delete($this->input['id']);
		if($flag)
		{
			//hg_pre($flag);
			//删除微博数据 取消此方法 效率较低
			$sql = "delete ".DB_PREFIX."status ,".DB_PREFIX."status_extra , ".DB_PREFIX."status_comments ,
			".DB_PREFIX."status_member , ".DB_PREFIX."status_topic , ".DB_PREFIX."status_favorites from ".DB_PREFIX."status
			left join ".DB_PREFIX."status_extra on ".DB_PREFIX."status.id = ".DB_PREFIX."status_extra.status_id 
			left join ".DB_PREFIX."status_comments on ".DB_PREFIX."status.id = ".DB_PREFIX."status_comments.status_id 
			left join ".DB_PREFIX."status_member on ".DB_PREFIX."status.id = ".DB_PREFIX."status_member.status_id
			left join ".DB_PREFIX."status_topic on ".DB_PREFIX."status.id = ".DB_PREFIX."status_topic.status_id
			left join ".DB_PREFIX."status_favorites on ".DB_PREFIX."status.id = ".DB_PREFIX."status_favorites.status_id  
			where ".DB_PREFIX."status.id in(".urldecode($this->input['id']).")";
			$sql = 'delete '.DB_PREFIX.'status,'.DB_PREFIX.'status_extra from '.DB_PREFIX.'status left join '.DB_PREFIX.'status_extra
			on '.DB_PREFIX.'status.id='.DB_PREFIX.'status_extra.status_id where '.DB_PREFIX.'status.id in('.$this->input['id'].')';
			//exit($sql);
			$delete_rows = @$this->db->query($sql);
			$reply = $this->db->query($sql);
		}
		else
		{
			$this->errorOutput(DELETE_FALES);
		}
		if($reply)
		{
			//删除转发的微博数据
			$sql = "delete  from ".DB_PREFIX."status where reply_status_id in(".urldecode($this->input['id']).")";
			$reply =@$this->db->query($sql);
		}
		else
		{
			$this->errorOutput(DELETE_FALES);
		}
		if($reply)
		{
			$this->setXmlNode('statuses','status');
			$this->addItem('sucess');
			$this->output();
		}
		else
		{
			$this->errorOutput(DELETE_FALES);
		}
	}*/
	/**
	 * 预处理参数ID 格式必须为id = 1,2,3 或者单个id = 1
	 */
	private function preFilterId()
	{
		if(isset($this->input['id']) && !empty($this->input['id']))
		{
			$this->input['id'] = urldecode($this->input['id']);
			$ids = array_unique(explode(',', $this->input['id']));
			//批量删除审核不能大于20个
			if(count($ids)>20)
			{
				$this->errorOutput('批处理上限');
			}
			foreach ($ids as $id)
			{
				
				if(!preg_match('/^\d+$/', $id))
				{
					$this->errorOutput('参数不合法');
				}
			}
			$this->input['id'] = implode(',', array_unique($ids));
		}
		else
		{
			$this->errorOutput('缺少参数');	
		}
	}
	/**
	 * 数据库中的ID和传入参数的ID是否匹配
	 */
	/*private function select_status_id()
	{
		$ids_memberids = array();
		$sql = 'select id,member_id from '.DB_PREFIX.'status where id in('.$this->input['id'].')';
		$query = $this->db->query($sql);
		while($result = $this->db->fetch_array($query))
		{
			$ids_memberids['id'][] = $result['id'];
			$ids_memberids['member_id'][] = $result['member_id'];
			//$member_ids[] = $result['member_id'];
		}
		return $ids_memberids;
	}*/
	public function unknow()
	{
		$this->errorOutput('方法不存在');
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
		
		$sql = "select * from " . DB_PREFIX ."status where id = " . $id;
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
			'set_id' 	=>	STATUS_PLAN_SET_ID,
			'from_id'   =>  $info['id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['text'],
			'action_type' => $op,
			'publish_time'  => $info['pub_time'],
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
		);
		if($child_queue)
		{
			$data['set_id'] = STATUS_PLAN_SET_ID;
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
		$sql = "select * from " . DB_PREFIX ."status where id = " . $id;
		$q = $this->db->query_first($sql);
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		
		$sql = "UPDATE " . DB_PREFIX ."status SET column_id = '". $column_id ."' WHERE id = " . $id;
		$this->db->query($sql);
		
		if(!$q['status'])
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
	$object = new statusUpdateApi();
	if(!method_exists($object, $_INPUT['a']))
	{
		$a = 'unknow';
	}
	else
	{
		$a = $_INPUT['a'];
	}
	$object->$a();
?>