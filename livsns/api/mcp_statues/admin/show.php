<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: show.php 12433 2012-10-11 09:59:16Z repheal $
***************************************************************************/
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/status.class.php';
define('MOD_UNIQUEID', 'statusShow'); //模块标识

class showApi extends adminReadBase
{
	private $status;
	
	public function __construct()
	{
		parent::__construct();
		$this->status = new status();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->status);
	}
	
	/**
	 * 获取微博数据
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition = $this->filter_data();
		$status_info = $this->status->show($offset, $count, $condition);
		$this->setXmlNode('status_info', 'status');
		if ($status_info) {
			foreach ($status_info as $status)
			{
				$this->addItem($status);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取微博总数
	 */
	public function count()
	{
		$condition = $this->filter_data();
		$info = $this->status->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 获取当前用户未使用的图片素材
	 */
	public function get_material()
	{
		$user_id = intval($this->user['user_id']);
		$pic_info = $this->status->getNotUsedMaterial($user_id);
		$this->setXmlNode('pic_info', 'pic');
		if ($pic_info) {
			foreach ($pic_info as $pic)
			{
				$this->addItem($pic);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取当前用户未使用的视频
	 */
	public function get_video()
	{
		$user_id = intval($this->user['user_id']);
		$video_info = $this->status->getNotUsedVideo($user_id);
		$this->setXmlNode('video_info', 'video');
		if ($video_info) {
			foreach ($video_info as $video)
			{
				$this->addItem($video);
			}
		}
		$this->output();
	}
	
	public function index() {}
	public function detail() {}
	
	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		$depart_id = isset($this->input['depart_id']) ? intval($this->input['depart_id']) : '';
		$mentions_id = isset($this->input['mentions_id']) ? intval($this->input['mentions_id']) : '';
		$commentMe_id = isset($this->input['commentMe_id']) ? intval($this->input['commentMe_id']) : '';
		$keywords = isset($this->input['keywords']) ? trim(urldecode($this->input['keywords'])) : '';
		$noreply = isset($this->input['noreply']) ? !!$this->input['noreply'] : '';
		$state = isset($this->input['state']) ? intval($this->input['state']) : 1;
		$status_id = isset($this->input['status_id']) ? trim(urldecode($this->input['status_id'])) : '';
		$member_id = isset($this->input['member_id']) ? trim(urldecode($this->input['member_id'])) : '';
		return array(
			'depart_id' => $depart_id,
			'mentions_id' => $mentions_id,
			'commentMe_id' => $commentMe_id,
			'keywords' => $keywords,
			'noreply' => $noreply,
			'state' => $state,
			'status_id' => $status_id,
			'member_id' => $member_id,
		);
	}
}
$out = new showApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>