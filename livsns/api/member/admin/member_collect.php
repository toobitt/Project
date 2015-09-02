<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: member_collect.php 19006 2013-03-21 09:07:30Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member_collect');//模块标识
require('global.php');
class memberCollectApi extends adminReadBase
{
	private $mMemberCollect;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/member_collect.class.php';
		$this->mMemberCollect = new memberCollect();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{	
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$members = $this->mMemberCollect->show($condition, $offset, $count);

		$this->setXmlNode('member','info');
		if (!empty($members))
		{
			foreach ($members AS $member)
			{
				$this->addItem($member);
			}
		}
	
		$this->output();
	}
	
	public function detail()
	{
		$info = $this->mMemberCollect->detail(trim($this->input['id']));
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mMemberCollect->count($condition);
		echo json_encode($info);
	}
	
	public function index()
	{
		
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
	
	private function get_condition()
	{
		$condition = $this->mMemberCollect->get_condition();
		return $condition;
	}

}

$out = new memberCollectApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>