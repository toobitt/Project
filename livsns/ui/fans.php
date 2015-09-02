<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: fans.php 4194 2011-07-26 05:26:45Z lijiaying $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'fans');
require('./global.php');

/**
 * 
 * 显示用户粉丝
 */
class showFollowers extends uiBaseFrm
{
	private $curl;
	private $status;
	
	function __construct()
	{
		parent::__construct();
		$user_id = intval($this->input['user_id']);
	/*
		if ($user_id)
		{
			header('Location:' . SNS_UCENTER . 'fans.php?user_id=' . $user_id);
		}
		else if ($this->input['name'])
		{
			$this->input['name'] = trim(urlencode($this->input['name']));
			header('Location:' . SNS_UCENTER . 'fans.php?name=' . $this->input['name']);
		}
		else
		{
			header('Location:' . SNS_UCENTER . 'fans.php');
		}
	*/
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
	
	/**
	 * 
	 * 显示用户粉丝界面
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
			
			$followers = $this->get_search_result($screen_name);
			
			if(empty($followers))
			{
				$search_result = 1;
			}
			else
			{
				$total_nums = $followers[count($followers)-1];           //搜索出关注用户的数据记录总数 			
				unset($followers[count($followers)-1]);
				$data['pagelink'] = hg_build_link('' , array('flag' => 1 , 'screen_name' => $this->input['screen_name']));						
			}
		}
		else
		{
			$total_nums = $user_info['followers_count'];                 //粉丝的数据记录总数
			$followers = $this->getFans($user_info['id'] , $total_nums); //获取该用户的粉丝对象
		} 
				
		if(empty($followers))                  	 		 				 //该用户没有粉丝
		{
			if($search_result == 1)
			{
				$no_result = true;	
			}
			else
			{
				$have_followers = false;	
			} 			
		}
		else
		{		
			$data['totalpages'] = $total_nums;		
			$data['perpage'] = 50;
			$data['curpage'] = $this->input['pp'];
			
			$showpages = hg_build_pagelinks($data);
						
			$have_followers = true;
			$followers_ids = array();
			foreach($followers as $k => $v)
			{
				$followers_ids[] = $v['id'];
			}
			
			$ids = implode(',' , $followers_ids);
						
			$relation = $this->get_relation($this->user['id'] , $ids);  //获取当前用户与取得用户的IDS的关系
			
			$len = count($followers);
			
			for($i = 0 ; $i < $len ; $i++)
			{
				$followers[$i]['is_mutual'] = $relation[$i];
			}			
		}

		$topic = $this->status->getTopic();
		/**
		 * 页面中的参数定义
		 */
		$user_param = array('user_id' => $user_info['id']);
		$topic_follow = $this->status->getTopicFollow();
		$this->page_title =  $this->lang['pageTitle'];
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'fans.js');
		
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('is_my_page', $is_my_page);
		$this->tpl->addVar('screen_name', $screen_name);
		$this->tpl->addVar('followers', $followers);
		$this->tpl->addVar('no_result', $no_result);
		$this->tpl->addVar('have_followers', $have_followers);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('relation', $relation);
		$this->tpl->addVar('topic', $topic);
		$this->tpl->addVar('user_param', $user_param);
		$this->tpl->addVar('topic_follow', $topic_follow);
		$this->tpl->addVar('gScriptName', $gScriptName);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('fans');								//数据写入粉丝模板	
		
	}
	
	/**
	 * 添加关注
	 */
	
	public function create()
	{
		include_once (ROOT_PATH . 'lib/class/friendships.class.php');
		$id = $this->input['id'];              						  //加关注的ID
		$add_obj = new friendShips();
		$result = $add_obj->create($id);
	}
		
	/**
	 * 移除粉丝
	 */
	public function move()
	{
		include_once (ROOT_PATH . 'lib/class/friendships.class.php');
		$user_id = $this->input['id'];        						  //粉丝ID		
		$add_obj = new friendShips();
		$add_obj->move($user_id);

		if($this->input['is_block'] == 1)
		{
			$this->addBlock($user_id);	
		}
	}
		
	/**
	 * 
	 * 获取该用户粉丝
	 */
	public function getFans($id)
	{
		$count = 20;
		$page = intval($this->input['pp']) / $count;	
		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		
		return $this->curl->request('friendships/followers.php');	
	}
	

	/**
	 * 获取当前用户和取出用户的关系
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
	 * 将用户加入黑名单
	 */
	public function addBlock($user_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $user_id);
		return $this->curl->request('Blocks/create.php');	
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
		return $this->curl->request('friendships/follow_search.php');
	}
}

$out = new showFollowers();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>