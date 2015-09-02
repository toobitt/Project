<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.php 1627 2011-01-08 07:14:42Z yuna $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'more_profile');
require('./global.php');
class userInfo extends uiBaseFrm
{	
	private $status;
	private $pagelink;
	private $albums;
	
	function __construct()
	{		
		parent::__construct();
		$this->load_lang('user');
		$this->load_lang('profile_privacy');
		include_once(ROOT_PATH . 'lib/user/user.class.php');
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$user_info = $this->check('all');
		if(empty($user_info))
		{
			$this->ReportError('用户不存在!');
		}
		$user_info = $user_info[0];

		//权限
		$authority = $user_info['privacy'];
		
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
				$is_my_page = true;	
			} 										
		}
		
		$this->page_title = $user_info['username'];
		$gScriptName = SCRIPTNAME;

		$id = $user_info['id'];
		$count = 50;
		$total = 'gettotal';
		$page = intval($this->input['pp']) / $count;
		
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->status = new status();
		$statusline = $this->status->user_timeline($id,$total,$page,$count);
		if(is_array($statusline))
		{
			$data['totalpages'] = $statusline[0]['total'];
			unset($statusline[0]);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['onclick'] = ' onclick="page_user_show(this,2,'.$id.');" ';
			$showpages = hg_build_pagelinks($data);
		}
		
		$last_status = $this->status->show($user_info['last_status_id']);
		if(is_array($last_status))
		{
			$last_status = $last_status[0];
		}
		
		include_once(ROOT_PATH . 'lib/class/groups.class.php');
		$this->group = new Group();
		$group = $this->group->get_my_groups($user_info['id']);
		include_once(ROOT_PATH . 'lib/class/relation.class.php');
		$this->relation = new Relation();
		$fans = $this->relation->get_fans($id,0,9);
		$friends = $this->relation->get_friends($id,0,9);
		
		hg_add_head_element('js-c',"
			var re_back = 'user.php';
			var re_back_login = 'login.php';
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'user.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'rotate.js');
		
		$this->tpl->addVar('user_friends', $friends);
		$this->tpl->addVar('group', $group);
		$this->tpl->addVar('user_fans', $fans);
		$this->tpl->addVar('id', $id);
		$this->tpl->addVar('last_status', $last_status);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('is_my_page', $is_my_page);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title); 
		$this->tpl->outTemplate('more_profile');

	}
	
	/**
	 * 获取用户相册信息
	 */
	public function albums()
	{
		$user_id = $this->input['user_id'];
		
		if ($user_id == $this->user['id'])
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
				$is_my_page = true;	
			} 										
		}
		
		include_once(ROOT_PATH . 'lib/class/albums.class.php');
		$this->albums = new albums();
		$albums_info = $this->albums->get_user_albums($user_id);
		
		ob_start();
		//导入相册模板文件
		include (ROOT_PATH . 'ucenter/tpl/albums.tpl.php');
		$albums_ui = ob_get_contents();
		ob_end_clean();
		
		echo $albums_ui;

	}
	
	
	/**
	 * 获取用户视频信息
	 */
	public function videos($pp=0)
	{
		$user_id = $this->input['user_id'];
		include_once(ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$count = 10;
		$page = intval($pp) / $count;
		$video =  $this->mVideo->get_video_info($user_id, $page, $count ,"" , $show_video = 1);
		if(is_array($video))
		{
			$data['totalpages'] = $video[count($video)-1];
			unset($video[count($video)-1]);
			$data['perpage'] = $count;
			$data['curpage'] = $pp;
			$data['onclick'] = ' onclick="page_user_show(this,1,'.$user_id.');" ';
			$showpages = hg_build_pagelinks($data);
		}	
		ob_start();
		include('./tpl/video_list.tpl.php');
		$html = ob_get_contents();
		ob_end_clean(); 
		if($pp) 
		{
			return $html;
		}
		else 
		{
			echo $html;
		}
		
	}
	
	/**
	 * 获取用户微博信息
	 */
	public function status($pp=0)
	{
		$user_id = $this->input['user_id'];
		$count = 50;
		$total = 'gettotal';
		$page = intval($pp) / $count;
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->status = new status();
		$statusline = $this->status->user_timeline($user_id,$total,$page,$count);
		if(is_array($statusline))
		{
			$data['totalpages'] = $statusline[0]['total'];
			unset($statusline[0]);
			$data['perpage'] = $count;
			$data['curpage'] = $pp;
			$data['onclick'] = ' onclick="page_user_show(this,2,'.$user_id.');" ';
			$showpages = hg_build_pagelinks($data);
		}
		
		ob_start();
		include('./tpl/status_list.tpl.php');
		$html = ob_get_contents();
		ob_end_clean();
		if($pp) 
		{
			return $html;
		}
		else 
		{
			echo $html;
		}
		
	}
	
	/**
	 * 获取用户帖子信息
	 */	
	public function topic($pp=0)
	{
		$user_id = $this->input['user_id'];
		include_once(ROOT_PATH . 'lib/class/groups.class.php');
		$this->group = new Group();
		$count = 50;
		$page = intval($pp) / $count;
		 
		$topic_list =  $this->group->get_user_threads($user_id,$page,$count);
		 
		if(is_array($topic_list))
		{
			$data['totalpages'] = $topic_list['total'];
			unset($topic_list['total']);
			$data['perpage'] = $count;
			$data['curpage'] = $pp;
			$data['onclick'] = ' onclick="page_user_show(this,4,'.$user_id.');" ';
			$showpages = hg_build_pagelinks($data);
		}	
		
		ob_start();
		include('./tpl/thread_cell.tpl.php');
		$html = ob_get_contents();
		ob_end_clean();
		if($pp) 
		{
			return $html;
		}
		else 
		{
			echo $html;
		}
	}
	
	
	/**
	 * ajax分页
	 */
	public function page_show()
	{
		$type = $this->input['type']? $this->input['type']:0;
		switch($type)
		{
			case 1://视频
				echo $this->videos($this->input['pp']);
				break;
			case 2://微博
				echo $this->status($this->input['pp']);
				break;
			case 3://相册
				break;
			case 4://帖子
				echo $this->topic($this->input['pp']);
				break;
			case 5://消息
				echo $this->show_notice($this->input['pp']);
				break;
			case 6://通知
				echo $this->show_notice($this->input['pp'],$this->input['n']=1);
				break;
			default:
				break;
		}
		
		
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