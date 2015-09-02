<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: follow.php 4569 2011-09-23 09:40:16Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'follow');
require('./global.php');

/**
 * 
 * 显示用户关注
 */
class showFriends extends uiBaseFrm
{
	private $curl;
	private $status;
	
	function __construct()
	{
		$user_id = intval($this->input['user_id']);
		/*
		if ($user_id)
		{
			header('Location:' . SNS_UCENTER . 'follow.php?user_id=' . $user_id);
		}
		else if ($this->input['name'])
		{
			$this->input['name'] = trim(urlencode($this->input['name']));
			header('Location:' . SNS_UCENTER . 'follow.php?name=' . $this->input['name']);
		}
		else
		{
			header('Location:' . SNS_UCENTER . 'follow.php');
		}
		*/
		parent::__construct();
		$this->check_login();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->curl = new curl();
		$this->status = new status();
		$this->load_lang('friends');
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * 显示用户关注界面
	 * 
	 * 
	 */
	
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
		
		if($this->input['search'] || $this->input['flag'] == 1)          //点击搜索功能
		{			
			$screen_name = trim($this->input['screen_name']);
			$friends = $this->get_search_result($screen_name);
			
			if(empty($friends))
			{
				$search_result = 1;	
			}
			else
			{
				$total_nums = $friends[count($friends)-1];                //搜索出关注用户的数据记录总数       			
				unset($friends[count($friends)-1]);						
				$data['pagelink'] = hg_build_link('' , array('flag' => 1 , 'screen_name' => $this->input['screen_name']));			
			}			
		}
		else
		{
			$total_nums = $user_info['attention_count'];                 //关注用户的数据记录总数

			$friends = $this->get_friends($user_id);      	             //获取该用户的关注对象
		}
								
		if(empty($friends))                        	     				 //该用户没有关注任何人
		{
			if($search_result == 1)
			{
				$no_result = true;
			}
			else
			{
				$have_friends = false;	
			} 			
		}
		else
		{
			$data['totalpages'] = $total_nums;		
			$data['perpage'] = 50;
			$data['curpage'] = $this->input['pp'];
			
			$showpages = hg_build_pagelinks($data);
			
			$friends_ids = array();
			
									
			foreach($friends as $k => $v)
			{
				$friends_ids[] = $v['id'];
			}

			$ids = implode(',' , $friends_ids);
			
			if($user_info['id'] == $this->user['id'])      						 			//查看当前用户的关注
			{
				$is_my_page = true;
				$relation = $this->get_relation_friend($this->user['id'] , $ids);//获取取得用户IDS与当前用户的关系(这批用户是否关注了我)				
			}
			else
			{
				$is_my_page = false;
				$relation = $this->get_relation($this->user['id'] , $ids);       //获取当前用户与取得用户的IDS的关系(当前用户是否关注了这批用户)
			}
			
			$len = count($friends);
				
			for($i = 0 ; $i < $len ; $i++)
			{
				$friends[$i]['is_mutual'] = $relation[$i];
			}
		}
		$gScriptName = SCRIPTNAME;
		/**
		 * 页面中的参数定义
		 */
		$user_param = array('user_id' => $user_info['id']);
		$this->page_title = $this->lang['pageTitle'];
		
		$topic = $this->status->getTopic();
		$topic_follow = $this->status->getTopicFollow();
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'topicfollow.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'follow.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		
		$this->tpl->addVar("user_info",$user_info);
		$this->tpl->addVar('is_my_page', $is_my_page);
		$this->tpl->addVar('screen_name', $screen_name);
		$this->tpl->addVar('friends', $friends);
		$this->tpl->addVar('no_result', $no_result);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('relation', $relation);
		$this->tpl->addVar('gScriptName', $gScriptName);
		$this->tpl->addVar('topic', $topic);
		$this->tpl->addVar('user_param', $user_param);
		$this->tpl->addVar('topic_follow', $topic_follow);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('follow');
		
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
		$user_info =  $this->curl->request('users/show.php');
		return $user_info[0];	
	}
	
	/**
	 * 
	 * 获取该用户关注用户
	 */
	public function get_friends($id)
	{
		$count = 20;
		$page = intval($this->input['pp']) / $count;
		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);		
		return $this->curl->request('friendships/friends.php');	
	}
	
	/**
	 * 获取当前用户和取出用户的关系：
	 * 当前用户是否关注了这些用户
	 */
	public function get_relation($id , $ids)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $id);
		$this->curl->addRequestData('ids', $ids);
		return $this->curl->request('friendships/user_relation.php');		
	}
	
	/**
	 * 获取当前用户和取出用户的关系：
	 * 取出用户是否关注了当前用户
	 */
	public function get_relation_friend($id , $ids)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $id);
		$this->curl->addRequestData('ids', $ids);
		return $this->curl->request('friendships/user_relation_friends.php');		
	}
	
	
	/**
	 * 添加关注
	 */	
	public function create()
	{
		include_once (ROOT_PATH . 'lib/class/friendships.class.php');
		$id = $this->input['id'];              //加关注的ID
		
		$add_obj = new friendShips();
		$result = $add_obj->create($id);			
	}
	
	/**
	 * 取消关注
	 */
	public function delete()
	{
		include_once (ROOT_PATH . 'lib/class/friendships.class.php');
		$user_id = $this->input['user_id'];    //用户ID
		
		$id = $this->input['id'];              //操作ID	

		$add_obj = new friendShips();	
		$result = $add_obj->destroy($id);		
	}
	
	/**
	 * 返回搜索结果
	 */
	public function get_search_result($screen_name)
	{
		$count = 20;
		$page = intval($this->input['pp']) / $count;
		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('screen_name', $screen_name);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('friendships/friend_search.php');
	}
	
}


$out = new showFriends();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>