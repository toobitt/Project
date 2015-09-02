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

class commentApi extends adminReadBase
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
	
	public function index() {}
	
	public function detail() {}
	
	/**
	 * 获取评论信息
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 5;
		$condition = $this->filter_data();
		$comments = $this->comment->show($offset, $count, $condition);
		$this->setXmlNode('comment_info', 'comment');
		if ($comments) {
			foreach ($comments as $comment)
			{
				$this->addItem($comment);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取评论总数
	 */
	public function count()
	{
		$condition = $this->filter_data();
		$info = $this->comment->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$status_id = isset($this->input['sid']) ? trim(urldecode($this->input['sid'])) : '';
		$member_id = isset($this->input['mid']) ? trim(urldecode($this->input['mid'])) : '';
		$reply_member_id = isset($this->input['rmid']) ? trim(urldecode($this->input['rmid'])) : '';
		if (!empty($status_id) && !strpos($status_id, ',')) $status_id = intval($status_id);
		if (!empty($member_id) && !strpos($member_id, ',')) $member_id = intval($member_id);
		if (!empty($reply_member_id) && !strpos($reply_member_id, ',')) $reply_member_id = intval($reply_member_id);
		$state = isset($this->input['state']) ? intval($this->input['state']) : 0;
		return array(
			'status_id' => $status_id,
			'member_id' => $member_id,
			'reply_member_id' => $reply_member_id,
			'state' => $state
		);
	}
}
$out = new commentApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>