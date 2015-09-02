<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: fans.php 2872 2011-03-16 13:34:30Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'station');
require('./global.php');

/**
 * 
 * 显示网台列表页
 * @author chengqing
 *
 */
class showStationList extends uiBaseFrm
{
	private $curl;
	private $status;
	
	function __construct()
	{
		parent::__construct();
		$this->check_login();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->curl = new curl();
		$this->status = new status();
		
		$this->load_lang('friends');
		$this->load_lang('user');
		$this->load_lang('followers');
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	public function show()
	{
		$this->input['name'] = trim($this->input['name']);
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
		}
		else
		{
			$user_id = $this->input['name'];
			$get_userinfo_func = 'getUserByName';
		}
		
		$user_info = $this->$get_userinfo_func($user_id , 'all');
						
		if(empty($user_info))
		{
			$this->ReportError('用户不存在!');
		}
		
		if ($user_info['id'] == $this->user['id'])
		{
			$is_my_page = true; 
		}
		else
		{
			if($this->user['id'])
			{
				$relation = $this->get_user_relation($this->user['id'] , $user_info['id']);
			}
			else
			{
				$is_my_page = true;	
			}									
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
		
		if(empty($concern))                  	 		//该用户没有网台
		{
			if($search_result == 1)
			{
				$no_result = true;	
			}
			else
			{
				$have_concern = false;	
			} 			
		}
		else
		{	
			$total_nums = array_pop($concern);	
			$data['totalpages'] = $total_nums;		
			$data['perpage'] = 50;
			$data['curpage'] = $this->input['pp'];			
			$showpages = hg_build_pagelinks($data,1);						
			$have_concern = true;			
		}
		
				
		include_once(ROOT_PATH . 'lib/class/relation.class.php');
		$this->relation = new Relation();	
		$user_fans = $this->relation->get_fans($user_id,0,9);
		$user_friends = $this->relation->get_friends($user_id,0,9);
		
		$this->page_title= $user_info['username'] . "关注的频道";
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'fans.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'user.js');	
		
		/**
		 * 页面中的参数定义
		 */
		$user_param = array('user_id' => $user_info['id']);
		
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('id', $user_info['id']);
		$this->tpl->addVar('user_friends', $user_friends);
		$this->tpl->addVar('user_fans', $user_fans);
		$this->tpl->addVar('group', $group);
	    $this->tpl->addVar('concern', $concern);
	    $this->tpl->addVar('relation', $relation);
	    $this->tpl->addVar('user_param', $user_param);
	    $this->tpl->addVar('is_my_page', $is_my_page);
	    $this->tpl->addVar('showpages', $showpages);
	    $this->tpl->addVar('have_concern', $have_concern);
	    $this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('station'); 		                   //数据写入粉丝模板	
		
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
		$user_info = $this->curl->request('users/show.php');
		
		return $user_info[0];	
	}
	
	/**
	 * 通过NAME获取该用户信息
	 */
	public function getUserByName($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('screen_name', $id);
		$user_info = $this->curl->request('users/show.php');
		return $user_info[0];	
	}
	
	/**
	 * 获取两个用户的关系
	 */
	public function get_user_relation($user_id , $id)
	{
		include_once (ROOT_PATH . 'lib/class/friendships.class.php');
		$add_obj = new friendShips();	
		$result = $add_obj->show($user_id , $id);
		return $result;		
	}
	
}

$out = new showStationList();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
