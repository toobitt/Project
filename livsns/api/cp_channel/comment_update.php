<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: comment_update.php 8427 2012-07-27 03:12:02Z hanwenbin $
***************************************************************************/

require_once './global.php';
require_once(ROOT_PATH . 'lib/class/logs.class.php');
define('MOD_UNIQUEID','comment_m');//模块标识

class commentUpdateApi extends outerUpdateBase
{	
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		$this->logs = new logs();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$this->errorOutput("222");
	}
	
	function delete()
	{
		$this->preFilterId();
		
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "comments WHERE id IN (" . $this->input['id']. ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['content'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['comments'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$sql = 'delete from '.DB_PREFIX.'comments where id in('.$this->input['id'].')';
			//hg_pre($sql);
			$r = $this->db->query($sql);
			if($r)
			{
				//记录日志
				$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
				//记录日志结束	
				$this->addItem('Success');
			}
			else
			{
				$this->errorOutput('删除失败！');
			}
		}
		else 
		{
			$this->errorOutput('删除失败！');
		}
		
		$this->output();
	}

	//还原
	/*public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		//还原聊天记录表
		if(!empty($content['comments']))
		{
			$sql = "insert into " . DB_PREFIX . "comments set ";
			$space='';
			foreach($content['comments'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'recover', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
		}
		return $data;
	}*/	
	
	
	
	//编辑留言
	function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$data = array();
		$data = array(
		'user_id'=>urldecode($this->input['userid']),
		'content'=>urldecode($this->input['content']),
		);
		$sql = 'UPDATE '.DB_PREFIX.'comments SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$sql = $sql.' WHERE id = '.$this->input['id'];
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'update', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		$this->addItem('success');
		$this->output();
		
	}
	
	/**
	 * 视频评论审核方法 将数据库中state字段设置为0 屏蔽
	 */
	public function audit()
	{
		$this->preFilterId();
		$sql = 'UPDATE '.DB_PREFIX.'comments'.' SET state = 1 WHERE id in('.$this->input['id'].')';
		//exit($sql);
		$this->db->query($sql);
		if($rows = $this->db->affected_rows())
		{
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'audit', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束		
			$this->addItem('success');
		}
		else
		{
			$this->addItem('error');
		}
		$this->output();
	}
	
	/**
	 * 预处理参数ID 格式必须为id = 1,2,3 或者单个id = 1
	 */
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
	public function none()
	{
		$this->errorOutput('方法不存在');
	}
}

/**
 *  程序入口
 */
$out = new commentUpdateApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();

?>