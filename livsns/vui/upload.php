<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: upload.php 6644 2012-05-04 02:24:58Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'my_video');
set_time_limit(0);
class upload extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();	
		include_once (ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();				
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		if(!$this->user['id'])
		{
			$this->check_login();
		}	
		$user_id = $this->user['id'];
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$info = new user();		
		$user_info = $info->getUserById($user_id,"all");
		$user_info = $user_info[0];
		
		$id = $user_info['id'];
		$hot_station = $this->mVideo->get_station(0,0,0,10);
		if(is_array($hot_station))
		{
			unset($hot_station[count($hot_station)-1]);
		}
		$hot_video = $this->mVideo->get_video_info(0,0,6,'',2);
		
		
		$this->load_lang('upload');		
		hg_add_head_element("js" , RESOURCE_DIR . 'scripts/swfupload.js');
		
		//视频上传格式
		$video_layout = $this->settings['video_layout'];
		
		$lay_out = '/';
		$upload_limit = '';
		foreach($video_layout as $k => $v)
		{
			$lay_out .= $v . '|';
			$upload_limit .= '*.'.$v . ';';	
		}
		$lay_out = substr($lay_out , 0 , strlen($lay_out)-1);
		$lay_out .= '/i';
		
		$upload_limit = substr($upload_limit , 0 , strlen($upload_limit)-1);
				
		$gScriptName = SCRIPTNAME;
		$this->page_title = $this->lang['pageTitle'];
		
		$this->settings['nav_menu'][3] = array("name" => "上传视频", "url" => SNS_VIDEO.SCRIPTNAME, "last" => 1);
		
		$gScriptName = SCRIPTNAME;
		
		$this->tpl->addVar('head_line', $this->settings['nav_menu']);
		
		$this->tpl->addVar('video_layout', $video_layout);
		$this->tpl->addVar('lay_out', $lay_out);
		$this->tpl->addVar('upload_limit', $upload_limit);
		
		$this->tpl->addVar('id', $id);	
		$this->tpl->addVar('user_id', $user_id);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('hot_station', $hot_station);
		$this->tpl->addVar('hot_video', $hot_video);
		$this->tpl->addVar('gScriptName', $gScriptName);
					
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('upload');
	}
		
	/**
	 * 上传处理
	 */
	public function deal_upload()
	{		
		include_once(ROOT_DIR . 'lib/class/settings.class.php');
		$setting = new settings();
		$result_setttings = $setting->getMark('video');
		if(!empty($result_setttings) && $result_setttings['state'])
		{
			echo '视频发布已关闭';	
		}

		$video_path = $_FILES['videofile']['tmp_name']; 			//视频的在本地的目录      
		$file_name = basename($_FILES['videofile']['name']);		//视频的文件
		$file_size = $_FILES['videofile']['size'];				//视频的大小
		
		$video_name = trim($this->input['video_name']); 		//视频名称
		$video_brief = trim($this->input['video_brief']);   	//视频简介
		$video_tags = trim($this->input['video_tags']);     	//视频标签
		$video_sort = $this->input['video_sort'];     			//视频分类
		$video_copyright = $this->input['video_copyright']; 	//视频版权
				
		include_once (ROOT_PATH . 'lib/class/curl.class.php');

		$this->curl = new curl();
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'deal_upload');
		$this->curl->addRequestData('video_path', $video_path);
		
		$this->curl->addFile($_FILES);
		$this->curl->addRequestData('file_name', $file_name);
		$this->curl->addRequestData('file_size', $file_size);		
		$this->curl->addRequestData('video_name', $video_name);
		$this->curl->addRequestData('video_brief', $video_brief);
		$this->curl->addRequestData('video_tags', $video_tags);
		$this->curl->addRequestData('video_sort', $video_sort);
		$this->curl->addRequestData('video_copyright', $video_copyright);
		$r = $this->curl->request('video/upload_video.php');
		echo $r;		
	}
}

$out = new upload();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>