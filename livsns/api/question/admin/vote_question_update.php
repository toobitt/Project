<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function create|update|delete|delQuestionOption|unknow
* 
* $Id: vote_question_update.php 17934 2013-02-26 01:52:15Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','question');//模块标识
class voteQuestionUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		require_once(ROOT_PATH . 'lib/class/logs.class.php');
		$this->logs = new logs();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 删除投票
	 * @name delete
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 投票ID
	 * @return $id int 所删除投票ID
	 */
	public function delete()
	{
		$id = urldecode($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		$sql = "select * from " . DB_PREFIX . "vote_question where id in(" . $id .")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['title'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['vote_question'] = $row;
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
			$sql = "DELETE FROM " . DB_PREFIX . "vote_question WHERE id IN (" . $id . ")";
			$this->db->query($sql);
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
			$this->addItem($id);
			$this->output();
		}
		
	}
	//彻底删除
	public function delete_comp()
	{
		if(empty($this->input['cid']))
		{
			return false;
		}
		$cid = urldecode($this->input['cid']);
		//删除操作
		$sql = "DELETE FROM " . DB_PREFIX . "question_option WHERE vote_question_id IN (" . $cid . ")";
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete_comp', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		return $cid;
	}
		//还原
	/*public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		//还原投票管理表
		if(!empty($content['vote_question']))
		{
			$sql = "insert into " . DB_PREFIX . "vote_question set ";
			$space='';
			foreach($content['vote_question'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
		}
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'recover', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		return $data;
	}*/
	
	/**
	 * 删除投票选项
	 * @name delQuestionOption
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 投票ID
	 * @return $id int 所删除投票ID
	 */
	public function delQuestionOption()
	{
		$id = urldecode($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "question_option WHERE id IN (" . $id . ")";
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delQuestionOption', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		$this->addItem($id);
		$this->output();
	}
	
	public function create()
	{
		
	}
	public function update()
	{
		
	}
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
			
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	
}

$out = new voteQuestionUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>