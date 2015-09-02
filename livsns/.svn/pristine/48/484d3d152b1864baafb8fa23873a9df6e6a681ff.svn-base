<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: blacklist.php 3071 2011-03-28 02:38:58Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'blacklist');
require('./global.php');

/**
 * 
 * 显示用户黑名单列表
 */

class showBlackList extends uiBaseFrm
{
	private $curl;
	
	function __construct()
	{
		parent::__construct();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->check_login();
		$this->curl = new curl();
		$this->status = new status();
		$this->load_lang('followers');
		$this->load_lang('blacklist');
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * 显示用户黑名单列表界面
	 */
	public function show()
	{			
		$user_info = $this->getUserById($this->user['id'] , 'all');         //获取用户信息
		
		$user_info = $user_info[0];
		
				
		$is_my_page = true;
		$hava_blocks = true;
		
		$black_list = $this->get_black_list($this->user['id']);       //获取黑名单列表
				
		if(empty($black_list))
		{
			$hava_blocks = false;
		}
		
		$last_status = $this->status->show($user_info['last_status_id']);
		if(is_array($last_status))
		{
			$last_status = $last_status[0];
		}
		
		include_once(ROOT_PATH . 'lib/class/groups.class.php');
		$this->group = new Group();
		$group = $this->group->get_my_groups($user_info['id']);
		if(is_array($group) && $group)
		{
			$group_nums = array_pop($group);
		}
						
		include_once(ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$concern = $this->mVideo->get_user_station_concern($user_id);
		
		include_once(ROOT_PATH . 'lib/class/relation.class.php');
		$this->relation = new Relation();
		$user_fans = $this->relation->get_fans($this->user['id'],0,9);
		$user_friends = $this->relation->get_friends($this->user['id'],0,9);
		$gScriptName = SCRIPTNAME;
		$this->page_title= $user_info['username'] . '的黑名单';
		$show_list_message = $this->lang['black_explain'];
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'topicfollow.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'blacklist.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'user.js');
		
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('id', $user_info['id']);
		$this->tpl->addVar('concern', $concern);
		$this->tpl->addVar('user_fans', $user_fans);
		$this->tpl->addVar('hava_blocks', $hava_blocks);
		$this->tpl->addVar('user_friends', $user_friends);
		$this->tpl->addVar('is_my_page', $is_my_page);
		$this->tpl->addVar('user_fans', $user_fans);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('blacklist');   		            //数据写入粉丝模板			
	}

	/**
	 * 获取黑名单信息
	 */
	public function get_black_list($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $id);
		return $this->curl->request('Blocks/blocking.php');
	}
	
	/**
	 * 通过ID获取该用户信息
	 */
	public function getUserById($id , $type="base")
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $id);
		$this->curl->addRequestData('type', $type);
		return $this->curl->request('users/show.php');	
	}
	
	/**
	 * 通过NAME获取该用户信息
	 */
	public function getUserByName($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('screen_name', $id);
		return $this->curl->request('users/show.php');	
	}
	
	/**
	 * 解除黑名单
	 */
	public function destroy()
	{
		$id = $this->input['id'];  //将要取消的黑名单ID  
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $id);
		return $this->curl->request('Blocks/destroy.php');		
	}
}

$out = new showBlackList();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();


?>