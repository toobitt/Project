<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: create.php 2774 2011-03-15 06:58:54Z daixin $
***************************************************************************/
//define('ROOT_DIR', '../../');
require('global.php');
define('MOD_UNIQUEID','cp_thread_m');//模块标识

class addthreadApi extends BaseFrm
{
	private $pubfunc;
	private $codeparse;
	private $group;
	
	function __construct()
	{
		parent::__construct();
		require_once  'lib/thread.class.php';
		$this->pubfunc = new thread();
		require_once 'lib/class_codeparse.php';
		$this->codeparse = new class_codeparse();
		include_once 'lib/group.class.php';
		$this->group = new group();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{	
		$arr['user_id'] = intval($this->user['user_id']);
		$arr['action_id'] = $this->input['action_id'] ? trim($this->input['action_id']) :0;
		$arr['title'] = trim(urldecode($this->input['title']));
		if(strstr($arr['title'],'#') || strstr($arr['title'],'@'))
		{
			$this->errorOutput("含有非法字符");
		}
		$arr['group_id'] = trim($this->input['group_id']);
		if(!$arr['group_id'])
		{
			$this->errorOutput("缺少参数");
		}
		$arr['user_name'] = trim($this->user['user_name']);
		$arr['pub_time'] = TIMENOW;
		$arr['last_post_time'] = TIMENOW;
		$arr['update_time'] = TIMENOW;
		$arr['last_poster'] = trim($this->input['user_name']);
		$arr['poll_id'] = $this->input['poll_id'] ? trim($this->input['poll_id']) : 0;
		$arr['lat'] = trim($this->input['lat']);
		$arr['lng'] = trim($this->input['lng']);
		$arr['thread_type'] = $this->input['thread_type'] ? trim($this->input['thread_type']) : 0;
		$sql = "select t_typeid from ".DB_PREFIX."thread_type   WHERE t_typeid=".$arr['thread_type'];
		$systypeStus = $this->db->result_first($sql);
		if(!$systypeStus)
		{
			$this->errorOutput("帖子类型错误");
		}
		$arr['category_id'] = $this->input['category_id'] ? trim($this->input['category_id']) : 0;
		$sql = "select id from ".DB_PREFIX."thread_category WHERE group_id=".$arr['group_id']." AND id=".$arr['category_id'];
		$categoryStus = $this->db->result_first($sql);
		if(!$categoryStus)
		{
			$arr['category_id'] = 0;
		}
		$pagetext = trim(urldecode($this->input['text_c']));
		if(!$arr['user_id'] || !$arr['thread_type'] || !$arr['title'] 
				|| !$arr['group_id'] || !$arr['user_name'] || !$pagetext)
		{
			$this->errorOutput("参数为空");
		}
		//检测用户与数组的关系
		/*if(!$this->pubfunc->getUserAndGroup($arr['user_id'],$arr['group_id'],VISITOR_POST))
		{
			$this->errorOutput("用户权限不足");
		}*/
		//$pagetext = $this->pubfunc->html2bbcode($pagetext);
		//$pagetext = $this->pubfunc->cutchars($this->pubfunc->clean_value($pagetext),MAX_POST_LIMIT,'');
		//$pagetext = $this->pubfunc->clean_value($pagetext);
		//$pagetext = $this->pubfunc->getpagetext($pagetext);
		$pagetext = addslashes($pagetext);
		//插入数据
		$arr['thread_id'] = $this->pubfunc->thread($arr);
		$post['thread_id'] = $arr['thread_id'];
		$post['pagetext'] = $pagetext;
		$post['user_id'] = $arr['user_id'];
		$post['user_name'] = $arr['user_name'];
		$post['from_ip'] = hg_getip();
		$post['pub_time'] = $arr['pub_time'];
		$post['stair_num'] = 0;
		$post['floor'] = 0;
		$post['displayupt_log'] = 1;
		$post['post_id'] = $this->pubfunc->post($post);
		if (isset($this->input['material']))
		{
			$img_info = unserialize(urldecode($this->input['material']));
			$materialData = array(
				'user_id' => $arr['user_id'],
				'thread_id' => $arr['thread_id'],
				'group_id' => intval($this->input['group_id']),
			);
			if(is_array($img_info) && !empty($img_info))
			{
				$materialNum = $this->group->add_material($img_info, $materialData); //插入附件的个数
				//更新帖子对应字段
				$this->pubfunc->thread_material($materialNum, $arr['thread_id']);
			}
		}
		//更新圈子对应字段
		$this->pubfunc->thread_updating($arr);
		$this->pubfunc->sonUpdate($arr);
		//更新最后回复人
		$this->pubfunc->updatePostId($post['thread_id'],$post['post_id']);
		if($arr['category_id'] > 0)
		{
			$this->db->query('UPDATE '.DB_PREFIX.'thread_category SET thread_count = thread_count+1 WHERE id='.$arr['category_id']);
		}
		$this->db->query('UPDATE '.DB_PREFIX.'group_members SET thread_count = thread_count+1,post_count = post_count+1 WHERE group_id='.$arr['group_id'].' AND user_id='.$arr['user_id']);
		$this->db->query('UPDATE '.DB_PREFIX.'group SET thread_count = thread_count+1,post_count = post_count+1 WHERE group_id='.$arr['group_id']);
		
		// 		if($this->pubfunc->filterParams($post['pagetext']))
// 		{
// 			$this->db->query('update ' . DB_PREFIX . 'thread set state=2 where thread_id=' . $arr['thread_id']);
// 		}
		
		$this->setXmlNode('addthread' , 'post');
		$this->addItem($arr['thread_id']);
		$this->output();
	}
	
	public function update()
	{
		$thread_id = trim($this->input['thread_id']);
		if(!$thread_id)
		{
			$this->errorOutput("参数为空");
		}
		$thread_info =$this->pubfunc->get_thread_info($thread_id);
		if(!$thread_info)
		{
			$this->errorOutput("帖子不存在");
		}
		if($this->input['user_id'] != $thread_info['user_id'])
		{
			$this->errorOutput("用户没有权限编辑");
		}
		$arr['category_id'] = $this->input['category_id'] ? trim($this->input['category_id']) : 0;
		$sql = "select id from ".DB_PREFIX."thread_category   WHERE group_id=".$thread_info['group_id']." AND id=".$arr['category_id'];
		$categoryStus = $this->db->result_first($sql);
		if(!$categoryStus)
		{
			$arr['category_id'] = 0;
		}
		$arr['action_id'] = $this->input['action_id'] ? trim($this->input['action_id']) :0;
		$arr['title'] = trim(urldecode($this->input['title']));
		if(strstr($arr['title'],'#') || strstr($arr['title'],'@'))
		{
			$this->errorOutput("含有非法字符");
		}
		$arr['group_id'] = trim($this->input['group_id']);
		$arr['last_post_time'] = TIMENOW;
		$arr['update_time'] = TIMENOW;
		$arr['poll_id'] = $this->input['poll_id'] ? trim($this->input['poll_id']) : 0;
		$arr['lat'] = trim($this->input['lat']);
		$arr['lng'] = trim($this->input['lng']);
		$arr['thread_type'] = $this->input['thread_type'] ? trim($this->input['thread_type']) : 0;
		$sql = "select t_typeid from ".DB_PREFIX."thread_type   WHERE t_typeid=".$arr['thread_type'];
		$systypeStus = $this->db->result_first($sql);
		if(!$systypeStus)
		{
			$this->errorOutput("帖子类型错误");
		}
		$pagetext = trim(urldecode($this->input['text_c']));
		if(!$arr['user_id'] || !$arr['thread_type'] || !$arr['title'] 
				|| !$arr['group_id'] || !$arr['user_name'] || !$pagetext)
		{
			$this->errorOutput("参数为空");
		}
		$this->pubfunc->updatethread($arr,$thread_id);
		$arr['thread_id'] = $thread_id;
		$this->pubfunc->thread_updating($arr);
		$pagetext = $this->pubfunc->cutchars($this->pubfunc->clean_value($pagetext),MAX_POST_LIMIT,'');
		$pagetext = $this->pubfunc->getpagetext($pagetext);
		
		if($pagetext != $thread_info['pagetext'])
		{
			$post['pagetext'] = $pagetext;
			$post['update_user_id'] = $thread_info['user_id'];
			$post['update_uname'] = $thread_info['user_name'];
			$post['update_time'] = $arr['update_time'];
			$this->pubfunc->updatepost($post,$thread_info['first_post_id']);
		}
		if($arr['category_id'] > 0 && $arr['category_id'] != $thread_info['category_id'])
		{
			$this->db->query('UPDATE '.DB_PREFIX.'thread_category SET thread_count = thread_count+1 WHERE id='.$arr['category_id']);
			$this->db->query('UPDATE '.DB_PREFIX.'thread_category SET thread_count = thread_count-1 WHERE id='.$thread_info['category_id']);
		}
		if($this->pubfunc->filterParams($post['pagetext']))
		{
			$this->db->query('update ' . DB_PREFIX . 'thread set state=2 where thread_id=' . $thread_id);
		}
		$this->setXmlNode('updatethread' , 'success');
		$this->addItem(true);
		$this->output();
	}
	
	public function edit_post()
	{
		$post['thread_id'] = intval(trim($this->input['thread_id']));
		$post['post_id'] = intval(trim($this->input['post_id']));
		$post['user_id'] = intval(trim($this->input['user_id']));
		$post['user_name'] = trim(urldecode($this->input['user_name']));
		if(!$post['post_id'] || !$post['thread_id'] || !$post['user_id'] || !$post['user_name'])
		{
			$this->errorOutput("缺少参数");
		}
		$thread_info =$this->pubfunc->get_thread_info($post['thread_id']);
		if($thread_info['open'])
		{
			$this->errorOutput("帖子回复已经关闭");
		}
		$this->pubfunc->thread_updating($thread_info);
		$post['pagetext'] = $this->pubfunc->clean_value(trim(urldecode($this->input['text_c'])));
		//$post['pagetext'] = $this->codeparse->convert(array('text' => $thread_info['pagetext'],'allowsmilies' => 1,'allowcode' => 1,'usewysiwyg' => 1));
		
		$post['logtext'] = TIMENOW."-".$post['user_id']."-".$post['user_name'];
		$this->pubfunc->updatepost($post,$post['post_id']);
// 		if($this->pubfunc->filterParams($post['pagetext']))
// 		{
// 			$this->db->query('update ' . DB_PREFIX . 'post set state=2 where post_id='.$post['post_id']);
// 		}
		//$thread_info['pagetext'] = $this->codeparse->parse_smile($thread_info['pagetext']);
		$this->setXmlNode('edit_post' , 'success');
		$this->addItem(true);
		$this->output();
	}
	//回复
	public function replypost()
	{
		$post['thread_id'] = trim($this->input['thread_id']);
		$thread_info =$this->pubfunc->get_thread_info($post['thread_id']);
		if($thread_info['open'] == 0)
		{
			$this->errorOutput("帖子回复已经关闭");
		}
		$post['user_id'] = trim($this->user['user_id']);
		$post['user_name'] = trim(urldecode($this->user['user_name']));
		$pagetext = trim(urldecode($this->input['text_c']));
		if(!$post['thread_id'] ||  !$pagetext || !$post['user_id'] || !$post['user_name'])
		{
			$this->errorOutput("缺少参数");
		}
		//检测用户与数组的关系
		/*
		if(!$this->pubfunc->getUserAndGroup($post['user_id'],$thread_info['group_id'],VISITOR_POST))
		{
			$this->errorOutput("用户权限不足");
		}
		*/
		$last_post_time = $this->db->query_first('select pub_time from ' . DB_PREFIX . 'post where thread_id = ' . $post['thread_id'] . ' and user_id = ' . $post['user_id'] . ' order by pub_time desc ');
		$last_post_time = $last_post_time['pub_time'];
		if(TIMENOW-intval($last_post_time) < $thread_info['per_add_time'])
		{
			$this->errorOutput("你的发帖速度过快");
		}
		else 
		{
			//$post['post_id'] = trim($this->input['post_id']);
			$post['stair_num'] = $this->input['stair_num'] ? trim($this->input['stair_num']) : 0;
			$post['reply_user_name'] = trim(urldecode($this->input['reply_user_name']));
			$post['reply_user_id'] = trim($this->input['reply_user_id']);
			$post['reply_des'] = trim(urldecode($this->input['reply_des']));
			$pagetext = $this->pubfunc->cutchars($this->pubfunc->clean_value($pagetext),200,'');
			$pagetext = $this->pubfunc->getpagetext($pagetext);
			$post['pagetext'] = $pagetext;
			
			$post['floor'] = $this->pubfunc->getThreadFloor($post['thread_id']);
			$post['pub_time'] = TIMENOW;
			if($post['thread_id'] && $post['pagetext'])
			{
				$this->pubfunc->thread_updating($thread_info);
				$this->pubfunc->updateFatherParams($thread_info);
				$post['post_id'] = $this->pubfunc->post($post);
			}
			$this->pubfunc->updateLastPostId($post['thread_id'],$post['post_id']);
			$this->db->query('update '.DB_PREFIX.'thread set post_count = post_count+1,last_post_time='.TIMENOW.',last_poster = "'.$post['user_name'].'",last_post_id = '.$post['post_id'].' where thread_id = '.$post['thread_id'].'');
			$this->db->query('update '.DB_PREFIX.'group set post_count = post_count+1 where group_id = '.$thread_info['group_id'].'');
			$this->db->query('UPDATE '.DB_PREFIX.'group_members SET thread_count = thread_count+0,post_count = post_count+1 WHERE group_id='.$thread_info['group_id'].' AND user_id='.$post['user_id']);
// 			var_dump($post['pagetext']);exit;
// 			if($this->pubfunc->filterParams($post['pagetext']))
// 			{
// 				$this->db->query('update ' . DB_PREFIX . 'post set state=2 where post_id='.$post['post_id']);
// 			}
			$this->setXmlNode('replypost' , 'post');
			$this->addItem($post['post_id']);
			$this->output();
		}
	}
}
/**
 *  程序入口
 */
$out = new addthreadApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();

?>