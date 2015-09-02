<?php
/***************************************************************************
* $Id: member_praise.php 19006 2013-03-21 09:07:30Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class memberPraiseApi extends appCommonFrm
{
	private $mMemberPraise;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/member_praise.class.php';
		$this->mMemberPraise = new memberPraise();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function getMemberPraiseCountsByContentId()
	{
		$content_id = trim($this->input['content_id']);
		if (!$content_id)
		{
			$this->errorOutput('未传入内容ID');
		}
		
		$type = trim($this->input['_type']);
		if (!$type)
		{
			$this->errorOutput('未传入称赞类型');
		}
		
		$ret_info = $this->mMemberPraise->getMemberPraiseCountsByContentId($content_id, $type);
		
		$info = array();
		if (!empty($ret_info))
		{
			foreach ($ret_info AS $k => $v)
			{
				$info[$k]['counts'] = count($ret_info[$k]);
				$info[$k]['info'] = $v;
			}
		}

		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 称赞
	 * $member_id int 会员id
	 * $content_id int 内容id
	 * $type string 称赞类型
	 */
	public function memberPraiseAdd()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}
		
		$content_id = intval($this->input['content_id']);
		if (!$content_id)
		{
			$this->errorOutput('未传入内容ID');
		}
		
		$type = trim($this->input['_type']);
		if (!$type)
		{
			$this->errorOutput('未传入称赞类型');
		}
		
		$ret_praise_exists = $this->mMemberPraise->checkMemberPraiseExists($member_id, $content_id, $type);
		
		if (!empty($ret_praise_exists))
		{
			$this->errorOutput('已称赞过该内容');
		}
		
		$ret_info = $this->mMemberPraise->memberPraiseAdd($member_id, $content_id, $type);
		
		if (!$ret_info)
		{
			$this->errorOutput('称赞失败');
		}
		
		$this->addItem($ret_info);
		$this->output();
	}

	/**
	 * 取消称赞
	 * $member_id int 会员id
	 * $content_id int 内容id
	 * $type string 称赞类型
	 * Enter description here ...
	 */
	public function memberPraiseDelete()
	{
		$member_id = intval($this->input['member_id']);
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}
		
		$content_id = intval($this->input['content_id']);
		if (!$content_id)
		{
			$this->errorOutput('未传入内容ID');
		}
		
		$type = trim($this->input['_type']);
		if (!$type)
		{
			$this->errorOutput('未传入称赞类型');
		}
	
		$ret_praise_exists = $this->mMemberPraise->checkMemberPraiseExists($member_id, $content_id, $type);
		
		if (empty($ret_praise_exists))
		{
			$this->errorOutput('该赞不存在');
		}
		
		$ret_info = $this->mMemberPraise->memberPraiseDelete($member_id, $content_id, $type);
		
		if (!$ret_info)
		{
			$this->errorOutput('取消称赞失败');
		}
		
		$this->addItem($ret_info);
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	

}

$out = new memberPraiseApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>