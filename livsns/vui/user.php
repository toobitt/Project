<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.php 4448 2011-09-01 07:57:03Z develop_tong $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'user');
class users extends uiBaseFrm
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
		
		if ($this->user['id'] && $user_info['id'] == $this->user['id'])
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

				$is_my_page = false;	
			}									
		}
		$id = $user_info['id'];
		
		include_once(ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$station = $this->mVideo->get_user_station($id);
		$this->page_title = $station['web_station_name'] ? $station['web_station_name'] : $user_info['username'];
		if(!$station['id'])
		{
			//header('Location:' . SNS_UCENTER . 'user.php?user_id=' . $id);
		}
		$program = $this->mVideo->get_station_programe($station['id'],$id);
		
		
		if($station['state']!=1)
		{
			$user_info['type'] = 0;
		}
		
/*		$concern = $this->mVideo->get_user_station_concern($id);
				if (is_array($concern) && $concern['total'])
				{
					unset($concern['total']);
				}
				include_once(ROOT_PATH . 'lib/class/groups.class.php');
				$this->group = new Group();
				
				$group = $this->group->get_my_groups($user_info['id']);
				
				if(is_array($group) && $group)
				{
					$group_nums = array_pop($group);
				}

				include_once(ROOT_PATH . 'lib/class/relation.class.php');
				$this->relation = new Relation();
				$fans = $this->relation->get_fans($id,0,6);
				$friends = $this->relation->get_friends($id,0,6);*/
				
				$count = 25;
				$page = intval($this->input['pp']) / $count;
				
				$video =  $this->mVideo->get_video_info($id, $page, $count ,"" , $show_video = 1);
				if(is_array($video) && $video)
				{
					$data['totalpages'] = $video[count($video)-1];
					unset($video[count($video)-1]);
					$data['perpage'] = $count;
					$data['curpage'] = $this->input['pp'];
					$data['pagelink'] = 'user.php?user_id='.$id;
				//	$data['onclick'] = ' onclick="page_user_show(this,1,'.$id.');" ';
					$showpages = hg_build_pagelinks($data);
				}
				
				/*/**
				 * 默认取点滴
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
				}*/
		$this->settings['nav_menu'][3] = array("name" => "个人频道", "url" => SNS_VIDEO.SCRIPTNAME, "last" => 1);
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js-c',"
			var re_back = 'user.php';
			var re_back_login = 'login.php';
			var SNS_VIDEO = '".SNS_VIDEO."';
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'user.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');
		
		$this->tpl->addVar('head_line', 0);

		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('authority', $authority);
		$this->tpl->addVar('relation', $relation);
		$this->tpl->addVar('video', $video);
		
		$this->tpl->addVar('id', $id);
		$this->tpl->addVar('is_my_page', $is_my_page);
		$this->tpl->addVar('station', $station);
		$this->tpl->addVar('program', $program);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('gScriptName', $gScriptName);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('space');
		
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
		$gScriptName = SCRIPTNAME;
		$n = intval($this->input['n']);
		$pp = intval($this->input['pp']) ? intval($this->input['pp']) : 0;
		$count = 50;
		switch($n)
		{
			case 0:
				$this->page_title = '消息';
				include_once(ROOT_PATH . 'lib/messages/messages.class.php');
				$mMessages = new messages(); 
				$msg_members = array(); 
				
				$from_who = $mMessages->get_members($this->user['id'],'',$pp,$count); 
				
				$total = @array_shift($from_who);  
				//分页
				$data['totalpages'] = $total; 
				$data['perpage'] = $count;
				$data['curpage'] = $pp;
				$data['onclick'] = ' onclick="javascript:document.location.href=re_back+\'?a=show_notice&pp=\'+(parseInt(this.title,10) - 1)+\'&n=0\';$(this).addClass(\'pages_current\');" ';
				$showpages = hg_build_pagelinks($data);
		 		ob_start(); 
				include hg_load_template("user_msgs");
				$html = ob_get_contents();
				ob_end_clean();
				break;
			case 1:
				$this->page_title = '通知';
				include_once(ROOT_PATH . 'lib/class/notify.class.php'); 
				$mNotify = new notify();
				$notice_arr = $un_notice = $notice = array();
				$notice_arr = $mNotify->notify_get($this->user['id'],-1,$pp,$count);
				//分页,这个通知要区分一下，已读的通知和未知的通知要分开
				$notice_count = $mNotify->notify_count($this->user['id'],-1);
				$total_count = $notice_count[0]; 
				$total1 = array_sum($total_count); 
				$data['totalpages'] = $total1; 
				$data['perpage'] = $count;
				$data['curpage'] = $pp;
				$data['onclick'] = ' onclick="javascript:document.location.href=re_back+\'?a=show_notice&pp=\'+(parseInt(this.title,10) - 1)+\'&n=1\';$(this).addClass(\'pages_current\');" ';
				$showpages = hg_build_pagelinks($data);
				
				if($notice_arr)
				 {  $sp = ''; 
				 	foreach($notice_arr as $key => $n)
				 	{ 
				 		if($n['is_read'] == 0)
				 		{
				 			if(in_array($n['type'],array(0,1,2,3))){
								$un_notice[$n['type']]['idstr'] .= $sp . $n['id'];
								$un_notice[$n['type']]['content'] = unserialize($n['content']);
							}else{
								$un_notice[$n['type']][$n['id']]['content'] = unserialize($n['content']); 
								$un_notice[$n['type']][$n['id']]['content']['notify_time'] = hg_get_date($n['notify_time']);
							}
						 $a =	$mNotify->notify_send_read($key,$this->user['id'],$n['type']);//将本页未读通知插入到已读表中
							$sp = ','; 
				 		} 
				 		else
				 		{
				 			if($n['type']>3)
				 			{ 
				 				
								$notice[$n['type']][$n['id']]['content'] = unserialize($n['content']); 
								$notice[$n['type']][$n['id']]['content']['notify_time'] = hg_get_date($n['notify_time']);
							}  
				 		}  
				 	}
				 } 
				ob_start(); 
				include hg_load_template("user_notices");
				$html = ob_get_contents();
				ob_end_clean();
				
				break; 
				
		}
		 
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'user.js');
		include hg_load_template("user_show_n");
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
		include hg_load_template("user_dialog");
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

$out = new users();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>