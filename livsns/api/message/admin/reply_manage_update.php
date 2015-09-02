<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
require_once('../lib/functions.php');
define('MOD_UNIQUEID','reply_manage');//模块标识
class ReplyUpdate extends adminUpdateBase
{
	private $curl;
	function __construct()
	{
		parent::__construct();
		
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function create()
	{
		
	}
	function sort()
	{
		
	}
	function publish()
	{
		
	}
	function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		$ids = trim(urldecode($this->input['id']));
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "message_reply WHERE id IN (" . $ids . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['content_reply'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['message_reply'] = $row;
		}
		if($data2)
		{
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
			//放入回收站结束
		}
		if($res['sucess'])
		{
			//删除留言回复内容
			$sql_del  = 'DELETE FROM '.DB_PREFIX.'message_reply WHERE id in('.$ids.')';
			$this->db->query($sql_del);
			$this->addItem('success');
			$this->output();
		}
	}
	//编辑留言
	function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$data = array();
		$data = array(
		'answerer'=>urldecode($this->input['answerer']),
		'content_reply'=>urldecode($this->input['content_reply']),
		'reply_time'=>TIMENOW,
		'ip'=>hg_getip(),
		);
		$sql = 'UPDATE '.DB_PREFIX.'message_reply SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$sql = $sql.' WHERE id = '.$this->input['id'];
		$this->db->query($sql);

		$this->addItem('success');
		$this->output();
		
	}
	//留言审核
	function message_state()
	{
		$id = intval($this->input['id']);
		$state = intval($this->input['state']);
		if(!$id)
		{
			$this->errorOutput('留言id不存在！');
		}
		if(!isset($state))
		{
			$this->errorOutput('缺少状态参数！');
		}
		$sql = "UPDATE " . DB_PREFIX . "message SET state =" . $state . " WHERE id=" . $id;
		if($this->db->query($sql))
		{
			$tip = '1';
		}
		else
		{
			$tip = '0';
		}
		$this->addItem($tip);
		$this->output();
	}
	//审核
	function audit()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('回复id不存在！');
		}

		$sql = "UPDATE " . DB_PREFIX . "message_reply SET state = 1 WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		$arr = explode(',', $id);
		$this->addItem($arr);
		$this->output();
	}
	//打回
	function back()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('回复id不存在！');
		}

		$sql = "UPDATE " . DB_PREFIX . "message_reply SET state = 2 WHERE id IN(" . $id . ")";
		$this->db->query($sql);
		$arr = explode(',', $id);
		$this->addItem($arr);
		$this->output();
	}
	function drag_order()
	{
		$ids = explode(',',urldecode($this->input['video_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX ."message_reply SET order_id = '" . $order_ids[$k] . "' WHERE id = '" . $v . "'";
			$this->db->query($sql);
		}
		$this->addItem($ids);
		$this->output();
	}
}
$ouput= new ReplyUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>