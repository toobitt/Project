<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 4569 2011-09-23 09:40:16Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'index');
require('./global.php');
require(ROOT_PATH . 'lib/class/status.class.php');
require_once(ROOT_PATH . 'lib/user/user.class.php');
require_once(ROOT_PATH . 'lib/class/relation.class.php');
class index extends uiBaseFrm
{	
	
	private $info;
	private $status;
	private $relation;
	
	function __construct()
	{
		parent::__construct();
		$this->load_lang['followers'];
		$this->load_lang['index'];
		$this->status = new status();
		$this->info = new user();
		$this->relation = new Relation();
		
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		if($this->user['id'] > 0)
		{
			$user_info = $this->info->getUserById($this->user['id']);
			$user_info = $user_info[0];
			$count = 50;
			$total = 'gettotal';
			$page = intval($this->input['pp']) / $count;	
			$statusline = $this->status->friends_timeline($this->user['id'],$total,$page,$count);
			if(is_array($statusline))
			{
				$data['totalpages'] = $statusline[0]['total'];
				unset($statusline[0]);
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$showpages = hg_build_pagelinks($data);
				
				if(!$page)
				{
					$onload = 'setTimeout("get_new_status('.$statusline[1]["id"].')",3000);';
					hg_set_cookie('since_id',$statusline[1]["id"], time()+ 31536000);
				}
				else 
				{
					$onload = 'setTimeout("get_new_status('.hg_get_cookie('since_id').')",3000);';
				}
			}
			
			$topic_follow = $this->status->getTopicFollow();
			
			/**
			 * 添加登录积分
			 */
			$this->info->add_credit_log(LOGIN);
			
			/**
			 * 取出会员
			 */
			$vipUser = $this->info->getVip(0,8);
			
			if(is_array($vipUser))
			{
				$vip_nums = $vipUser[count($vipUser)-1];
				
				$total = ceil($vip_nums/8);
				unset($vipUser[count($vipUser)-1]);
			}
			
			$search_friend_ids = array();
						
			foreach($vipUser as $k => $v)
			{
				$search_friend_ids[] = $v['id'];
			}
			
			$ids = implode(',' , $search_friend_ids);
			
			$relation = $this->relation->get_relation($this->user['id'] , $ids);
						
			$len = count($vipUser);
			
			for($i = 0 ; $i < $len ; $i++)
			{
				$vipUser[$i]['is_friend'] = $relation[$i];
			}

			//print_r($vipUser);						
		}
		else
		{
			$statusline = $this->status->public_timeline($page);
		}		
		
		$topic = $this->status->getTopic();



		$this->page_title = $this->lang['pageTitle'];
		$gScriptName = SCRIPTNAME;
		hg_add_foot_element("js-c", "\r\t\n".'window.onload=function(){'.$onload.' if(parseInt(now_uid,10)>0){setTimeout("check_new_msg()",5000);setTimeout("getnotify()",3000);}}');
		hg_add_head_element('js-c',"
			var re_back = 'index.php';
			var re_back_login = 'login.php';
			var PUBLISH_TO_MULTI_GROUPS = " . PUBLISH_TO_MULTI_GROUPS . ";
		");
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'index.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'jquery.form.js');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'dispose.js');//转发和关注话题
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'rotate.js');

		$this->tpl->addVar('gScriptName', $gScriptName);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('statusline', $statusline);
		$this->tpl->addVar('relation', $relation);
		$this->tpl->addVar('topic', $topic);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('vipUser', $vipUser);
		$this->tpl->addVar('topic_follow', $topic_follow);
		$this->tpl->addVar('total', $total);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('index');
		
	}
	
	public function new_status()
	{
		$since_id = $this->input['since_id']?$this->input['since_id']:0;
		if($since_id)
		{
			$statusline = $this->status->newlist_timeline($since_id,$this->user['id'],0,0,1);
			if(is_array($statusline))
			{
				$since_id = $statusline[0]['id'];
				hg_set_cookie('since_id',$since_id, time() + 31536000);
			}
		}
		echo $since_id;
		exit;
	}

	public function new_status_show()
	{
		$since_id = $this->input['since_id']?$this->input['since_id']:0;
		if($since_id)
		{
			hg_set_cookie('since_id',$since_id, time() + 31536000);
			$statusline = $this->status->newlist_timeline($since_id-1,$this->user['id'],0,0,10);
			if(is_array($statusline))
			{
				$this->tpl->addVar('statusline', $statusline);
				echo $this->tpl->outTemplate('statusline_new','hg_new_status');
				exit;
			}
		}
	}
	
	public function uploadpic(){
		if(!$this->input['media_id'] && !$this->input['status_id'])
		{
			$files = $_FILES['files'];
			$uploadedfile = $files['tmp_name'];	
			if((filesize($uploadedfile)/1024/1024) >= IMG_SIZE)
			{
				echo '<script>parent.endUploads("size_error")</script>';	
			}
			else 
			{
				$file = json_encode($this->status->uploadeImage($_FILES));
				echo '<script>parent.endUploads("' . addslashes($file) . '")</script>';	
			}
			
		}
		else
		{
			$info = $this->status->updateMedia($this->input['status_id'], $this->input['media_id']);
			echo json_encode($info);
		}
	}
	
	public function deletepic(){
		if($this->input['id'] && $this->input['url'])
		{
			$id = $this->input['id'];
			$url = $this->input['url'];
			$files = $this->status->deleteMedia($id, $url);
			echo json_encode($files);
		}		
	}
	
	public function uploadvideo(){
		$url = $this->input['url'];
		if(preg_match("((((f|ht){1}tp|ftp|gopher|news|telnet|rtsp|mms)://|www\.)[-a-zA-Z0-9@:%_\+.~#?&//=]+)" ,$url))
		{
			$ret = $this->status->uploadVideo($url);
			echo json_encode($ret);
		}
	}
	
	/**
	 * AJAX获取会员
	 */
	public function ajax_get_vip()
	{
		$page = $this->input['page'];
		$total = $this->input['total'];
		
		$vipUser = $this->info->getVip($page ,8 ,$total);
		unset($vipUser[count($vipUser)-1]);
		
		$search_friend_ids = array();
						
		foreach($vipUser as $k => $v)
		{
			$search_friend_ids[] = $v['id'];
		}
		
		$ids = implode(',' , $search_friend_ids);
		
		$relation = $this->relation->get_relation($this->user['id'] , $ids);
					
		$len = count($vipUser);
		
		for($i = 0 ; $i < $len ; $i++)
		{
			$vipUser[$i]['is_friend'] = $relation[$i];
		}

		//导入聊天信息模板
		$this->tpl->addVar('vipUser', $vipUser);
		$this->tpl->outTemplate('vip');
	}
	
	
	/**
	 * 添加关注
	 */
	public function add_friend()
	{
		$id = $this->input['id'];

		$this->info->create($id);
	}
	
	/**
	 * 发布微博时，显示的关注用户 
	 */
	public function get_friends()
	{
		$keywords = $this->input['keywords']?$this->input['keywords']:0;
		include_once(ROOT_PATH . 'lib/class/relation.class.php');
		$this->relation = new Relation();
		$ret = $this->relation->get_search_friend($keywords,0,6);
		if($keywords&&$ret)
		{
			unset($ret[count($ret)-1]);
			$html['count'] = count($ret);
			$ul = '<ul id="friends_name">';
			foreach($ret as $key => $value)
			{
				if(!$key)
				{
					$ul .='<li class="cur" id="cur_'.$key.'" onmousemove="change_css('.$html['count'].','.$key.')" onclick="insert_name('.$key.');">'.$value['username'].'</li>';
				}
				else 
				{
					$ul .='<li id="cur_'.$key.'" onmousemove="change_css('.$html['count'].','.$key.')" onclick="insert_name('.$key.');">'.$value['username'].'</li>';
				}
				
			}
			$ul .= '</ul>';
			$html['html'] = $ul;
			$html['count'] = count($ret);
			echo json_encode($html);
			exit;
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
$out = new index();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>