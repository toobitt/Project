<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: update.php 12433 2012-10-11 09:59:16Z repheal $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/comment.class.php';
define('MOD_UNIQUEID', 'comment'); //模块标识

class commentUpdateApi extends adminUpdateBase
{
	private $comment;
	
	public function __construct()
	{
		parent::__construct();
		$this->comment = new comment();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->comment);
	}
	
	/**
	* 发布一条评论
	*/
	public function create()
	{
		//处理传递的数据
		$data = $this->filter_data();
		if ($data['reply_comment_id'])
		{
			//获取回复的评论的信息
			$reply_comment_info = $this->comment->detail($data['reply_comment_id']);
			$data['reply_member_id'] = $reply_comment_info['member_id'];
		}
		$data['member_id'] = $this->user['user_id'];
		$data['create_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		$data['flag'] = 0;
		//创建数据
		$result = $this->comment->create($data);
		if ($result)
		{
			//更新统计数据
			$this->comment->update(
				'status_extra',
				array('comment_count' => 1),
				array('status_id' => $result['status_id']),
				true
			);
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 删除评论数据
	 */
	public function delete()
	{
		$flag = isset($this->input['flag']) ? intval($this->input['flag']) : 1;  //1逻辑删除  2物理删除
		$comment_id = isset($this->input['comment_id']) ? trim(urldecode($this->input['comment_id'])) : '';
		$status_id = isset($this->input['status_id']) ? trim(urldecode($this->input['status_id'])) : '';
		$member_id = isset($this->input['member_id']) ? trim(urldecode($this->input['member_id'])) : '';
		if (!empty($comment_id) && !strpos($comment_id, ',')) $comment_id = intval($comment_id);
		if (!empty($status_id) && !strpos($status_id, ',')) $status_id = intval($status_id);
		if (!empty($member_id) && !strpos($member_id, ',')) $member_id = intval($member_id);
		$data = array();
		$status = array();
		$condition = array();
		if ($comment_id)
		{
			$condition = array('id' => $comment_id, 'state' => 0);
		}
		else
		{
			if ($status_id && !$member_id)
			{
				$condition = array('status_id' => $status_id, 'state' => 0);
			}
			elseif (!$status_id && $member_id)
			{
				$condition = array('member_id' => $member_id, 'state' => 0);
			}
			elseif ($status_id && $member_id)
			{
				$condition = array('status_id' => $status_id, 'member_id' => $member_id, 'state' => 0);
			}
		}
		$ret = $this->check_validate($condition);
		if ($ret && $ret['ids'])
		{
			$data['id'] = $ret['ids'];
			$status = $ret['status'];
		}
		if ($flag === 1) //逻辑删除
		{
			if ($data)
			{
				$result = $this->comment->update('status_comments', array('flag' => 1), $data);
			}
			//更新统计数据
			if ($result && $status)
			{
				foreach ($status as $k => $v)
				{
					$this->comment->update(
						'status_extra',
						array('comment_count' => -intval($v)),
						array('status_id' => $k),
						true
					);
				}
			}
		}
		elseif ($flag === 2) //物理删除
		{
			if ($data)
			{
				$result = $this->comment->delete($data);
			}
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 处理数据的有效性
	 * @param Array $condition
	 */
	private function check_validate($condition)
	{
		if (!$condition || !is_array($condition)) return false;
		$status = array();
		$ids = array();
		$comment_info = $this->comment->check_exists($condition);
		if ($comment_info)
		{
			foreach ($comment_info as $v)
			{
				$ids[] = $v['id'];
				$status[$v['status_id']][] = $v['id'];
			}
			if ($ids) {
				$ids = implode(',', $ids);
				foreach ($status as $k => $v)
				{
					$status[$k] = count($v);
				}	
			}
		}
		return array(
			'ids' => $ids,
			'status' => $status
		);
	}
	
	public function update() {}
	
	public function audit() {}
	
	public function publish() {}
	
	public function sort() {}
	
	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		$status_id = isset($this->input['s_id']) ? intval($this->input['s_id']) : '';
		$content = isset($this->input['comment_con']) ? trim(urldecode($this->input['comment_con'])) : '';
		if (empty($status_id) || empty($content))
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$reply_c_id = isset($this->input['reply_cid']) ? intval($this->input['reply_cid']) : 0;
		$data = array(
			'status_id' => $status_id,
			'content' => $content,
			'reply_comment_id' => $reply_c_id
		);
		if (!$reply_c_id) $data['reply_member_id'] = 0;
		return $data;
	}
	
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
}
$out = new commentUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();
?>