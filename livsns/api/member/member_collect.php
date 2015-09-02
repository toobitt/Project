<?php
/***************************************************************************
* $Id: member_collect.php 19006 2013-03-21 09:07:30Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member_collect');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class memberCollectApi extends appCommonFrm
{
	private $mMemberCollect;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/member_collect.class.php';
		$this->mMemberCollect = new memberCollect();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 会员收藏
	 * Enter description here ...
	 */
	public function member_collect()
	{
		if (!$this->input['member_id'])
		{
			$this->errorOutput('未传入用户ID');
		}
		
		if (!$this->input['title'])
		{
			$this->errorOutput('标题不能为空');
		}
		
		$info = array(
			'member_id' 	=> intval($this->input['member_id']),
			'appuniqueid' 	=> trim($this->input['appuniqueid']),
			'content_id' 	=> intval($this->input['content_id']),
			'title' 		=> trim($this->input['title']),
			'brief' 		=> trim($this->input['brief']),
			'imgurl' 		=> trim($this->input['imgurl']),
			'url' 			=> trim($this->input['url']),
		);
		
		$data = $this->mMemberCollect->create($info,$this->user);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 获取会员收藏信息
	 * $member_id 会员ID
	 * $offset 分页参数
	 * $count 分页参数
	 * Enter description here ...
	 */
	public function getMemberCollectByMemberId()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$condition = $this->mMemberCollect->get_condition();
		$offset = intval($this->input['offset']);
		$count = intval($this->input['count']);
		
		$info = $this->mMemberCollect->getMemberCollectByMemberId($member_id, $condition, $offset, $count);

		$this->addItem($info);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	

}

$out = new memberCollectApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>