<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: topic_update.php 8430 2012-07-27 03:33:01Z hanwenbin $
***************************************************************************/
define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','mblog_topic_m');
require(ROOT_DIR.'global.php');
class topicUpdateApi extends outerUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		require_once(ROOT_PATH . 'lib/class/logs.class.php');
		$this->logs = new logs();
	}
	function __destruct()
	{
		parent::__destruct();
		$this->db->close();
	}
	
	public function create()
	{
		
	}
	
	/**
	 * 
	 * 更新话题的数据
	 */
	public function update()
	{
		$id = $this->input['id'] ? intval($this->input['id']) : -1;
		
		if($id <= 0)
		{
			$this->errorOutput('未传入更新话题的ID');	
		}
		
		//视频中需要更新的字段
		$update_field = array('title'       => urldecode(trim($this->input['title']))
		);
		
		$sql = "SELECT * FROM " . DB_PREFIX . "topic WHERE title='". $update_field['title'] ."'";
		$f = $this->db->query_first($sql);
		if(!$f)
		{
			$sql = "UPDATE " . DB_PREFIX . "topic SET ";
		
			$field = '';
			foreach($update_field as $db_field => $value )
			{
				if(trim($value))
				{
					$field .= $db_field . " = '" . $value . "' ,";
				}
			}
			
			$field = substr($field , 0 , (strlen($field)-1));		
			$condition = " WHERE id = " . $id;		
			$sql = $sql . $field . $condition;
					
			$this->db->query($sql);
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'update', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束	
			$num = $this->db->affected_rows();
	
			$this->setXmlNode('topic_info' , 'topic');
			if($num)
			{
				$this->addItem('话题更新成功');
			}
			else
			{
				$this->addItem('话题更新失败');	
			}
			$this->output(); 
		}
		else 
		{
			$this->setXmlNode('topic_info' , 'topic');
			$this->addItem('话题已存在');	
			$this->output(); 
		}
	}
	
	function delete()
	{
		$this->preFilterId();
		$sql = "select * from ".DB_PREFIX."topic where id in(".$this->input['id'].")";
		$r = $this->db->query($sql);
		while($row = $this->db->fetch_array($r))
		{
 			$data[$row['id']] = array(
				'title' => $row['title'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
			);
			$data[$row['id']]['content']['topic'] = $row;
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
			$sql = 'delete from '.DB_PREFIX.'topic where id in('.$this->input['id'].')';
			if($this->db->query($sql))
			{
				//记录日志
				$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
				//记录日志结束
				$this->addItem('success');
				$this->output();
			}	
		}
	}
	function delete_comp()
	{
		if(empty($this->input['cid']))
		{
			return false;
		}
		$cid = urldecode($this->input['cid']);
		$sql = 'delete from '.DB_PREFIX.'topic_member where topic_id in('.$cid.')';
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete_comp', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		$sql = 'delete from '.DB_PREFIX.'status_topic where topic_id in('.$cid.')';
		$this->db->query($sql);
		return true;
	}


	function audit()
	{
		$state = intval($this->input['audit'])?0:1;
		$this->preFilterId();
		$sql = 'UPDATE '.DB_PREFIX.'topic'.' SET status = '.$state.' WHERE id in('.$this->input['id'].')';
		//exit($sql);
		/*$this->db->query($sql);
		if($rows = $this->db->affected_rows())
		{
			$this->addItem('success');
		}
		else
		{
			$this->addItem('error');
		}*/
		if ($this->db->query($sql))
		{
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'audit', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
			$ids[] = explode(',', $this->input['id']);
		}
		$this->addItem($ids);
		$this->output();
	}
	private function preFilterId()
	{
		if(isset($this->input['id']) && !empty($this->input['id']))
		{
			$this->input['id'] = urldecode($this->input['id']);
			$ids = explode(',', $this->input['id']);
			//批量删除不能大于20个
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
			$this->errorOutput('参数不合法');
		}
	}
	public function unknow()
	{
		$this->errorOutput('方法不存在');
	}
}
	$topicUpdateApi = new topicUpdateApi();
	if(!method_exists($topicUpdateApi, $_INPUT['a']))
	{
		$a = 'unknow';
	}
	else
	{
		$a = $_INPUT['a'];
	}
	$topicUpdateApi->$a();
?>