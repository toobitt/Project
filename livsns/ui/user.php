<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.php 4194 2011-07-26 05:26:45Z lijiaying $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'user');
require('./global.php');
//require(ROOT_PATH . 'lib/class/notify.class.php');
class userInfo extends uiBaseFrm
{	
	private $status;
	private $pagelink;
	function __construct()
	{
		parent::__construct();
		
		$user_id = intval($this->input['user_id']);
		/*if ($user_id)
		{
			header('Location:' . SNS_UCENTER . 'user.php?user_id=' . $user_id);
		}
		elseif($this->input['name'])
		{
			$this->input['name'] = trim(urlencode($this->input['name']));
			header('Location:' . SNS_UCENTER . 'user.php?name=' . $this->input['name']);
		}
		else
		{
			header('Location:' . SNS_UCENTER . 'user.php');
		}
		*/
		$this->load_lang('followers');
		$this->load_lang('user');
		$this->load_lang('info');
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl();		
		$this->status = new status();	
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{

		global $gScriptName,$gScriptNameArray;
		$is_my_page = false;
		$user_info = $this->check('all');
		if(empty($user_info))
		{
			$this->ReportError('用户不存在!');
		}
				
		$user_info = $user_info[0];

		//权限
		$authority = $user_info['privacy'];
		
		$this->user_info = $user_info;
				
		if ($user_info['id'] == $this->user['id'])
		{
			$is_my_page = true;
		}
		else
		{
			if($this->user['id'])
			{
				$relation = $this->get_relation($this->user['id'] , $user_info['id']);	
			}
			else
			{
				$is_my_page = false;	
			} 										
		}
		
		$id = $user_info['id'];
		$count = 50;
		$total = 'gettotal';
		$page = intval($this->input['pp']) / $count;		
		$statusline = $this->status->user_timeline($user_info['id'],$total,$page,$count);
		if(is_array($statusline))
		{
			$data['totalpages'] = $statusline[0]['total'];
			unset($statusline[0]);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = $this->pagelink;
			$showpages = hg_build_pagelinks($data);
		}
		$topic = $this->status->getTopic();
		$topic_follow = $this->status->getTopicFollow();
		/**
		 * 页面中的参数定义
		 */
		$user_param = array('user_id' => $user_info['id']);

		$this->page_title = $user_info['username'].$this->lang['pageTitle'];
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js-c',"
			var re_back = 'user.php';
			var re_back_login = 'login.php';
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'user.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'rotate.js');

		$this->tpl->addVar('gScriptName', $gScriptName);
		$this->tpl->addVar('is_my_page', $is_my_page);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('relation', $relation);
		$this->tpl->addVar('id', $id);
		$this->tpl->addVar('statusline', $statusline);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('topic', $topic);
		$this->tpl->addVar('user_param', $user_param);

		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('user');	
	}
	
	public function check($type="base")
	{
		$this->input['name'] = trim(urlencode($this->input['name']));
		$get_userinfo_func = 'getUserById';
		if (!$this->input['user_id'] &&  !$this->input['name'])
		{
			if (!$this->user['id'])
			{
				$this->check_login();
			}
			else
			{
				$user_id = intval($this->user['id']);
			}
		}
		elseif ($this->input['user_id'])
		{
			$user_id = intval($this->input['user_id']);
			$this->pagelink = hg_build_link('' , array('user_id' => $this->input['user_id']));
		}
		else
		{
			$user_id = $this->input['name'];
			$this->pagelink = hg_build_link('' , array('name' => $this->input['name']));
			$get_userinfo_func = 'getUserByName';
			
			
		}
		$info = new user();
		$user_info = $info->$get_userinfo_func($user_id,$type);
				
		return $user_info;
	}
	
	/**
	 * 获取两个用户的关系
	 */
	public function get_relation($user_id , $id)
	{
		include_once (ROOT_PATH . 'lib/class/friendships.class.php');
		$add_obj = new friendShips();	
		$result = $add_obj->show($user_id , $id);
		return $result;		
	}
	
	/**
	 * 添加关注
	 */
	public function create()
	{
		include_once (ROOT_PATH . 'lib/class/friendships.class.php');
		$id = $this->input['id'];
		
		$add_obj = new friendShips();
		$result = $add_obj->create($id);
	}
	
	/**
	 * 取消关注
	 */
	public function destroy()
	{
		include_once (ROOT_PATH . 'lib/class/friendships.class.php');
		$id = $this->input['id'];
		$add_obj = new friendShips();
		$add_obj->destroy($id);
		$user_id = $this->user['id'];      //当前用户ID
		echo $this->get_relation($user_id , $id);
	}
	
	/**
	 * 解除黑名单
	 */
	public function remove()
	{
		$id = $this->input['id'];          //将要取消的黑名单ID 
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $id);		
		return $this->curl->request('Blocks/destroy.php');
	}
	
}
$out = new userInfo();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>