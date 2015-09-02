<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.php 6044 2012-03-08 03:17:37Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'user');
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
				
		$user_info['type'] = $this->input['type'] ? 0 : $user_info['type'];
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
				
				$privacy = $user_info[privacy];
				$is_visit = $privacy{14};
				
				if($is_visit == 2)
				{
					$this->ReportError('你没有权限访问该用户!');	
				}
				else
				{
					if($is_visit == 1)
					{
						if($relation != 1 || $relation != 3)
						{
							$this->ReportError('你没有权限访问该用户!');		
						}
					}
				} 
			}
			else //未登录情况下
			{
				$privacy = $user_info[privacy];
				$is_visit = $privacy{14};
				
				if($is_visit == 2)
				{
					$this->ReportError('你没有权限访问该用户!');	
				}

				$is_my_page = true;	
			}									
		}

		$this->page_title = $user_info['username'];
		$id = $user_info['id'];
		include_once(ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		
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
			$showpages = hg_build_pagelinks($data,1);
		}

		$last_status = $this->status->show($user_info['last_status_id']);
		if(is_array($last_status))
		{
			$last_status = $last_status[0];
		}
		
		$concern = $this->mVideo->get_user_station_concern($id);
		
		include_once(ROOT_PATH . 'lib/class/groups.class.php');
		$this->group = new Group();
		$group = $this->group->get_my_groups($user_info['id']);
		if(is_array($group) && $group)
		{
			$group_nums = array_pop($group);
		}
		
		include_once(ROOT_PATH . 'lib/class/relation.class.php');
		$this->relation = new Relation();
		$fans = $this->relation->get_fans($id,0,9);
		$friends = $this->relation->get_friends($id,0,9);
		
		
				
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js-c',"
			var re_back = 'user.php';
			var re_back_login = 'login.php';
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'user.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'rotate.js');
		
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('is_my_page', $is_my_page);
		$this->tpl->addVar('relation', $relation);
		$this->tpl->addVar('id', $id);
		$this->tpl->addVar('statusline', $statusline);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('last_status', $last_status);
		
		$this->tpl->addVar('concern', $concern);
		$this->tpl->addVar('group', $group);
		$this->tpl->addVar('group_nums', $group_nums);
		$this->tpl->addVar('fans', $fans);
		$this->tpl->addVar('friends', $friends);
		
		$this->tpl->addVar('gScriptName', $gScriptName);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->lang['pageTitle']);		
		$this->tpl->outTemplate('user');
		
	}
	
	
	/**
	 * 获取用户相册信息
	 */
	public function albums()
	{
		$user_id = $this->input['user_id'];
		$content = $this->input['content'];
		
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
		
		/*
		ob_start();
		//导入相册模板文件
		include (ROOT_PATH . 'ucenter/tpl/albums.tpl.php');
		$albums_ui = ob_get_contents();
		ob_end_clean();
		
		echo $albums_ui;
		*/
		
		$this->tpl->addVar('albums_info', $albums_info);
		$this->tpl->outTemplate('albums','hg_getlist,'.$content);
		
	}
	
	
	/**
	 * 获取用户视频信息
	 */
	public function videos($pp=0)
	{
		$user_id = $this->input['user_id'];
		$content = $this->input['content'];
		include_once(ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$count = 40;
		$page = intval($pp) / $count;
		$video =  $this->mVideo->get_video_info($user_id, $page, $count ,"" , $show_video = 1);
		if(is_array($video))
		{
			$data['totalpages'] = $video[count($video)-1];
			unset($video[count($video)-1]);
			$data['perpage'] = $count;
			$data['curpage'] = $pp;
			$data['onclick'] = ' onclick="page_user_show(this,1,'.$user_id.');" ';
			$showpages = hg_build_pagelinks($data,1);
		}	
		
		/*
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
		*/
		
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('video', $video);
		$this->tpl->outTemplate('video_list','hg_getlist,'.$content);
		
		
	}
	
	/**
	 * 获取用户微博信息
	 */
	public function status($pp=0)
	{
		$user_id = $this->input['user_id'];
		$content = $this->input['content'];
		if ($user_id == $this->user['id'])
		{
			$is_my_page = true; 
		}
		else 
		{
			$is_my_page = false; 
		}
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
			$showpages = hg_build_pagelinks($data,1);
		}
		/*
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
		*/
		
		$this->tpl->addVar('statusline', $statusline);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->outTemplate('status_list','hg_getlist,'.$content);	
	
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
			$showpages = hg_build_pagelinks($data,1);
		}	
		/*
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
		*/
		
		$this->tpl->addVar('topic_list', $topic_list);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->outTemplate('thread_cell','hg_getlist,'.$content);
		
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
		$this->input['name'] = urldecode($this->input['name']);
		if(preg_match("/^[".chr(0xa1)."-".chr(0xff)."a-za-z0-9_]+$/",$this->input['name']))
		{
			$this->input['name'] = iconv('GBK', 'UTF-8', $this->input['name']);
		}
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
		echo json_encode($result[0]);
	}
	
	/**
	 * 取消关注
	 */
	public function destroy()
	{
		include_once (ROOT_PATH . 'lib/class/friendships.class.php');
		$id = $this->input['id'];
		$add_obj = new friendShips();
		$rets = $add_obj->destroy($id);
		$user_id = $this->user['id'];      //当前用户ID
		$ret = $this->get_relation($user_id , $id);
		$info['relation'] = $ret;
		if($rets && is_array($rets))
		{
			$info['cid'] = $rets[0]['sid'];
		}
		echo json_encode($info);
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
	
	public function show_notice()
	{
		$user_info = $this->check('all');
		$gScriptName = SCRIPTNAME;
		$n = intval($this->input['n']);
		$count = 50;
		$pp = intval($this->input['pp']) / $count;	
		$user_id = $this->user['id'];
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'user.js');
		switch($n)
		{
			case 0:
				$this->page_title = '消息';
				include_once(ROOT_PATH . 'lib/messages/messages.class.php');
				$mMessages = new messages(); 
				$msg_members = array(); 
				
				$from_who = $mMessages->get_members($user_id,'',$pp,$count); 
				
				$total = @array_shift($from_who);  
				//分页
				$data['totalpages'] = $total; 
				$data['perpage'] = $count;
				$data['curpage'] = $pp;
				$data['onclick'] = ' onclick="javascript:document.location.href=re_back+\'?a=show_notice&pp=\'+(parseInt(this.title,10) - 1)+\'&n=0\';$(this).addClass(\'pages_current\');" ';
				$showpages = hg_build_pagelinks($data,1);
				
				$this->tpl->addVar('msg_members', $msg_members);
				$this->tpl->addVar('mMessages', $mMessages);
				$this->tpl->addVar('from_who', $from_who);
				$this->tpl->addVar('total', $total);
				break;
			case 1:
				$this->page_title = '通知';
				include_once(ROOT_PATH . 'lib/class/notify.class.php'); 
				$mNotify = new notify();
				$un_notice = $notice = array();
				$un_notice =  $mNotify->notify_get_unread($user_id, -1, $pp, $count);
				$notice =  $mNotify->notify_get_read($user_id, -1, $pp, $count);
				/*if(is_array($un_notice))
				{
					$nids = $space = "";
					foreach($un_notice as $key => $value)
					{
						$mNotify->notify_send_read($value['id'] , $user_id, $value['type']);
					}
				}*/
				
				$total_count = $mNotify->notify_count($user_id, -1); 
				if(is_array($un_notice))
				{
					$data['totalpages'] = $total_count[0];
					$data['perpage'] = $count;
					$data['curpage'] = $this->input['pp'];
					$data['pagelink'] = '?a=show_notice&n=1';
					$showpages = hg_build_pagelinks($data);
				}
				
				//$a = $mNotify->notify_send_read($key,$user_id,$n['type']);//将本页未读通知插入到已读表中
				$this->tpl->addVar('un_notice', $un_notice);
				$this->tpl->addVar('notice', $notice);
				break; 
				
		}
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('n', $n);
		$this->tpl->addVar('gScriptName', $gScriptName);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->lang['pageTitle']);	
		$this->tpl->outTemplate('user_show_n');
	}
	
	public function do_showMessages()
	{
		
		$u_id = intval($this->input['u_id']);
		$id = intval($this->input['id']);
		$user_ = new user();
		$info = $user_->getUserById($u_id);
		$info = $info[0];
		$to_name = $info['username']; 
		
		include_once(ROOT_PATH . 'lib/messages/messages.class.php');
		$mMessages = new messages();
		$mm = $mMessages->get_one_msg($id); 
		$mm = $mm[0];
		
		$key = array_keys($mm);
		$salt_str = $key[0];
		$user_info = $this->user;
		$messages = $mm[$salt_str];
		ob_start(); 		
		$this->tpl->addVar('salt_str', $salt_str);
		$this->tpl->addVar('messages', $messages);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('key', $key);
		$this->tpl->addVar('mm', $mm);
		$this->tpl->addVar('to_name', $to_name);
		$this->tpl->addVar('info', $info);
		$this->tpl->addVar('id', $id);
		$this->tpl->addVar('u_id', $u_id);
		$this->tpl->outTemplate('user_dialog');
		$html = ob_get_contents();
		ob_end_clean();
		echo $html . '[[[------------]]]' . $salt_str . '[[[------------]]]' . $to_name; 
	}
	
//删除一条点滴记录
	public function destroy_blog()
	{
		include_once(ROOT_PATH . 'lib/class/status.class.php');
		$this->status = new status();
		if($this->user['id'])
		{
			//测试数据
			//$this->input['statu_id'] = 23048;
			//传递参数
			$status_id = $this->input['status_id'];
			
			//删除博客信息
			$ret = $this->status->destroy($status_id);
			//用户博客信息数减一
			if($ret['0']['id'])
			{
				$user = new user();	
				$ret = $user->destroy_attention_count();
				echo json_encode($ret);
			}
			else
			{
				echo json_encode('false');
			}
			
		}
		else
		{
			echo json_encode('false');
		}
		
	}
	
	public function get_face()
	{
		$face_con = $this->input['con'];
		$face_tab = $this->input['tab'];
		
		$this->tpl->addVar('face_con', $face_con);
		$this->tpl->addVar('face_tab', $face_tab);
		$this->tpl->outTemplate('face','hg_html_face,'.$face_tab);
	
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