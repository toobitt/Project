<?php
/***************************************************************************
* $Id: member.php 31803 2013-11-22 02:43:15Z zhuld $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class memberApi extends outerReadBase
{
	private $mMember;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/member.class.php';
		$this->mMember = new member();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 取出会员列表
	 * Enter description here ...
	 */
	public function show()
	{	
		$condition = $this->get_condition();
		$condition .= " AND m.status=1 "; 
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$members = $this->mMember->getMemberInfo($condition, $offset, $count);

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

	public function count()
	{
		$condition = $this->get_condition();
		$condition .= " AND m.status=1 "; 
		$info = $this->mMember->count($condition);
		$this->addItem($info);
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$binary = '';//不区分大小些
			if(defined('DIFFER_SIZE') && !DIFFER_SIZE)//区分大小些
			{
				$binary = 'binary ';
			}	
			$condition .= ' AND ' . $binary . ' m.member_name like \'%'.trim($this->input['k']).'%\'';
		}
		
		if(isset($this->input['nick_name']) && !empty($this->input['nick_name']))
		{
			$condition .= ' AND m.nick_name like \'%'.trim($this->input['nick_name']).'%\'';
		}
		
		if (isset($this->input['not_id']) && $this->input['not_id'])
		{
			$condition .= " AND m.id NOT IN (" . $this->input['not_id'] . ")";
		}
		
		if (isset($this->input['member_id']) && $this->input['member_id'])
		{
			$condition .= " AND m.id IN (" . $this->input['member_id'] . ")";
		}
		
		return $condition;
	}
	
	/**
	 * 根据会员Id 获取会员信息
	 * 	
	 * 返回 (会员id, 昵称, 性别, 头像, 注册时间, 各种信息数目)
	 * Enter description here ...
	 */
	public function get_member_by_id()
	{
		$member_id = trim(urldecode($this->input['member_id']));

		if (!$member_id)
		{
			$this->errorOutput('未传入会员id');
		}
		
		$info = $this->mMember->getMemberInfoById($member_id);

		$this->addItem($info);
		$this->output();
	}

	public function get_member_by_nick_name()
	{
		$nick_name = trim(urldecode($this->input['nick_name']));
		if (!$nick_name)
		{
			$this->errorOutput('未传入会员昵称');
		}
		
		$info = $this->mMember->getMemberInfoById($nick_name, 'nick_name');

		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 获取会员的所有信息
	 * $member_id
	 * Enter description here ...
	 */
	public function getMemberInfoById()
	{
		$member_id = trim(urldecode($this->input['member_id']));
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}
		
		$info = $this->mMember->getMemberInfoDetail($member_id);
		unset($info[$member_id]['password']);
		$this->addItem($info);
		$this->output();
	}
	public function mobile_member_detail()
	{
		//$member_id = trim(urldecode($this->input['member_id']));
		$member_id = $this->user['user_id'];
		if (!$member_id)
		{
			$this->errorOutput('未传入会员ID');
		}
		
		$info = $this->mMember->getMemberInfoDetail($member_id);
		$output = $info[$member_id];
		$output['is_exist_password'] = 1;
		//$password = md5(md5("").$output['salt']);
		if (!$output['password'] && !$output['salt'])
		{
			$output['is_exist_password'] =  0;	//密码未设置
		}
		unset($output['password']);
		$this->addItem($output);
		$this->output();
	}
	///'verify_login'
	public function verify_login()
	{
		if($this->user['user_id'])
		{
			$this->addItem($this->user);
			$this->output();
		}
		else
		{
			$this->errorOutput('未登录');
		}
	}
	
	public function getBoundById()
	{
		$member_id = $this->user['user_id'] ? $this->user['user_id'] : 0;
		if(!$member_id)
		{
			$this->errorOutput('未登录');
		}
		$platform = $this->input['platform'] ? intval($this->input['platform']) : 0;
		if(empty($platform))
		{
			$this->errorOutput('请选择类型');
		}
		
		$ret = $this->mMember->getBoundById($member_id,$platform);
		
		if(empty($ret))
		{
			$this->errorOutput('未绑定');
		}
		else
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function getConstellation()
	{
		$ret = $this->mMember->getConstellation();
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 根据会员Id 获取会员信息
	 * 	
	 * 返回 (会员id, 昵称, 性别, 头像)
	 * Enter description here ...
	 */
	public function _get_member_by_id()
	{
		$member_id = trim($this->input['member_id']);

		if (!$member_id)
		{
			$this->errorOutput('未传入会员id');
		}
		
		$info = $this->mMember->_get_member_by_id($member_id);

		$this->addItem($info);
		$this->output();
	}

	public function _get_member_by_nick_name()
	{
		$nick_name = trim($this->input['nick_name']);
		if (!$nick_name)
		{
			$this->errorOutput('未传入会员昵称');
		}
		
		$info = $this->mMember->_get_member_by_id($nick_name, 'nick_name');

		$this->addItem($info);
		$this->output();
	}
	
	public function get_all_mark()
	{
		$con = '';
		if($this->input['member_id'])
		{
			$con .= " AND member_id IN(" . urldecode($this->input['member_id']) . ")";
		}
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		if($count > 0)
		{
			$con .= " LIMIT $offset,$count";
		}
		$ret = $this->mMember->get_all_mark($con);
		if(empty($ret))
		{
			$this->errorOutput('无内容！');
		}
		foreach($ret as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	public function detail()
	{
		
	}
	public function index()
	{
		
	}
	public function get_uc_user_num()
	{
		include_once UC_CLIENT_PATH . 'uc_client/client.php';
		$total = uc_get_total_num();
		exit(json_encode(array('total'=>$total)));
	}
	public function unknow()
	{
		$this->errorOutput('此方法不存在！');
	}
	
}

$out = new memberApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>