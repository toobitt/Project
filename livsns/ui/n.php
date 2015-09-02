<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: n.php 4194 2011-07-26 05:26:45Z lijiaying $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
require_once(ROOT_PATH . 'lib/user/user.class.php');

/**
 * 搜索用户
 *
 */
class searchFriends extends uiBaseFrm
{
	private $curl;
	
	function __construct()
	{
		parent::__construct();
		$this->check_login();
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl();
		$this->info = new user();		
		$this->load_lang('followers');
		$this->load_lang('n');
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 显示搜索结果
	 */
	public function show()
	{
		$user_info = $this->info->getUserById($this->user['id']);
		$user_info = $user_info[0];
		
		$have_result = true;
		$this->input['search_name'] = trim($this->input['search_name']);
		/*if(preg_match("/^[".chr(0xa1)."-".chr(0xff)."a-za-z0-9_]+$/",$this->input['search_name']))
		{
			$this->input['search_name'] = iconv('GBK', 'UTF-8', $this->input['search_name']);
		}
		*/
		$screen_name = $this->input['search_name'];
		$search_friend = $this->get_friend_info($screen_name);
		if(empty($search_friend))
		{
			$have_result = false;
		}
		else
		{
			$total_nums = $search_friend[count($search_friend)-1];
			
			unset($search_friend[count($search_friend)-1]);
			
			$data['totalpages'] = $total_nums;		
			$data['perpage'] = 50;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = '?search_name=' . urlencode($this->input['search_name']);
			$showpages = hg_build_pagelinks($data);
		
			$search_friend_ids = array();
						
			foreach($search_friend as $k => $v)
			{
				$search_friend_ids[] = $v['id'];
			}
			
			$ids = implode(',' , $search_friend_ids);
			
			$relation = $this->get_relation($this->user['id'] , $ids);
						
			$len = count($search_friend);
			
			for($i = 0 ; $i < $len ; $i++)
			{
				$search_friend[$i]['is_friend'] = $relation[$i];
			}			
		}
		
		$this->page_title =  $this->lang['pageTitle'];
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'n.js');
		//include hg_load_template('n');   		                   //数据写入粉丝模板
		
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('have_result', $have_result);
		$this->tpl->addVar('screen_name', $screen_name);
		$this->tpl->addVar('search_friend', $search_friend);
		$this->tpl->addVar('total_nums', $total_nums);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('relation', $relation);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('n');

	}
	
	/**
	 * 返回搜索到的用户信息
	 */
	public function get_friend_info($screen_name)
	{
		$count = 10;
		$page = intval($this->input['pp']) / $count;
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('screen_name', $screen_name);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('users/search.php');	
	}
	
	/**
	 * 返回搜索的用户是否关注了我
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
	 * 添加关注
	 */
	public function create()
	{
		$user_id = $this->input['id'];

		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id',$user_id);		
		$this->curl->request('friendships/create.php');	
	}
}

$out = new searchFriends();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>