<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: my_album.php 4412 2011-08-16 08:17:01Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'my_album');
class myAlbum extends uiBaseFrm
{
	private $mVideo;
	private $count;
	private $page;
	function __construct()
	{
		parent::__construct();	
		include_once (ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$this->load_lang('my_album');
		$this->count = 10;
		$this->page = (intval($this->input['pp'])?intval($this->input['pp']):0) / $this->count;;
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$id = $this->user['id']?$this->user['id']:0;
		if(!$id)
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
		
		$count = $this->count;
		$page = $this->page;
		$album_info = $this->mVideo->get_album_info($id,$page,$count);
		$album_list = $album_info;
		if($album_info['total'])
		{
			$album_total = $datas['totalpages'] = $album_info['total'];
			unset($album_info['total']);
			$datas['perpage'] = $count;
			$datas['curpage'] = $this->input['pp'];
			$showpages = hg_build_pagelinks($datas);
		}
		
		if($album_list['total'])
		{
			$data['totalpages'] = $album_list['total'];
			unset($album_list['total']);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['onclick'] = 'onclick="album_page_show(this,5);"';
			$showpage = hg_build_pagelinks($data);
		}
//		hg_pre($album_info);
		
		
		$this->page_title = $this->lang['pageTitle'];
		
		$this->settings['nav_menu'][3] = array("name" => "我的专辑", "url" => SNS_VIDEO.SCRIPTNAME, "last" => 1);
		
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'my.js');
		
		$this->tpl->addVar('head_line', $this->settings['nav_menu']);
		
		$this->tpl->addVar('album_info', $album_info);
		$this->tpl->addVar('showpage', $showpage);
		$this->tpl->addVar('album_list', $album_list);
		$this->tpl->addVar('album_total', $album_total);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('id', $id);	
		$this->tpl->addVar('user_id', $user_id);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('hot_station', $hot_station);
		$this->tpl->addVar('hot_video', $hot_video);
		$this->tpl->addVar('gScriptName', $gScriptName);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('my_album');
	}
	
	
	public function show_html()
	{
		$type = $this->input['type']?$this->input['type']:1;
		
		$html = '';
		switch ($type)
		{
			case 1:
					$html = $this->create_album_html();
				break;
			case 2:
					$html = $this->show_album_html();
				break;
			case 3:
					$html = $this->show_video_html();
				break;
			case 4:
					$html = $this->add_video_html();
				break;
			case 5:
					$html = $this->edit_album_html();
				break;
			default :
				break;
			
		}
		
		echo $html;
		exit;
	}
	
	
	public function create_album()
	{
		$name = $this->input['name'];
		$brief = $this->input['brief'];
		$sort_id = $this->input['sort_id'];
		$video_id = $this->input['video_id'];

		$ret = $this->mVideo->create_album($name,$brief,$sort_id,$video_id);
		
		/**
		 * 添加创建专辑积分
		 */
		$this->mVideo->add_credit_log(CREATE_SPECIAL);
			
		if($ret['id'])
		{
			$html = '<h3>创建专辑</h3><div class="show_info_tips show_info"><div class="album_result">专辑<a title="'.$ret['name'].'" style="color: #000000; cursor: pointer; float: none; padding: 0; text-decoration: none;"><b>'.hg_cutchars($ret['name'],8," ").'</b></a> 已经创建成功<ul class="result_list">			
			<li><a href="javascript:void(0);" onclick="create_album(1);">创建新专辑</a></li>
			<li><a href="javascript:void(0);" onclick="return_album();">查看其他专辑</a></li>
			<li><a href="javascript:void(0);"  onclick="manage_album_video('.$ret['id'].');">查看本专辑</a></li>
			<li><a href="user_album_video.php?id='.$ret['id'].'&user_id='.$ret['user_id'].'">播放本专辑</a></li>
			</ul></div></div>';
		}
		else 
		{
			$html = "";
		}
		echo ($html);
		exit;
	}
	
	/**
	* 删除专辑（包括关联表中的信息）
	* @param $album_id
	* @return $album_id 专辑ID
	*/
	public function del_album()
	{
		$album_id = $this->input['album_id']? $this->input['album_id']:0;
		$ret = $this->mVideo->del_album($album_id);
		if($ret)
		{
			echo $html = $this->show_album_html();
			exit;
		}
		else
		{
			echo $html = '';
			exit;
		}
	}
	
	
	/**
	 * 
	 * 创建专辑的模板页面
	 */
	private function create_album_html()
	{
		$album_name = $this->input['album_name']? $this->input['album_name']:'';
		$album_brief = $this->input['album_brief']? $this->input['album_brief']:'';
		$album_sort = $this->input['album_sort']? $this->input['album_sort']:0;
		$html ='<h3>我的专辑</h3>
		<div class="show_info_tips show_info">
		<div class="create_album">
		<ul>
					<li><span class="album_text"><span class="need">*</span>标题：</span><input id="album_name" type="text" value="'.$album_name.'"/></li>
					<li><span class="album_text">简介：</span><textarea id="album_brief" cols="35" rows="5">'.$album_brief.'</textarea></li>
					<li><span class="album_text">所属分类：</span>
						<div class="album_sort">';
		$ra = "";
		foreach($this->settings['album'] as $key => $value)
		{
			if($album_sort)
			{
				if($album_sort==$key)
				{
					$value['checked'] = 'checked';
				}
				else 
				{
					$value['checked'] = '';
				}
			}
			$ra .='<input type="radio" '.$value['checked'].' name="album_sort[]" id="r_'.$value['id'].'" value="'.$value['id'].'"/>'.$value['name'];
		}
		
		$html = $html.$ra.'</div>
					</li>
					<li class="album_bt clear"><input type="button" value="《 返回专辑列表" onclick="return_album();"/><input type="button" value="重填" onclick="album_reset();"/><input type="button" value="为专辑添加视频 》" onclick="select_video();"/></li>
				</ul>
			</div>
			</div>';
		
		return $html;
	}
	
	
	
	/**
	 * 
	 * 编辑专辑的模板页面
	 */
	private function edit_album_html()
	{
		$album_name = $this->input['album_name']? $this->input['album_name']:'';
		$album_brief = $this->input['album_brief']? $this->input['album_brief']:'';
		$album_sort = $this->input['album_sort']? $this->input['album_sort']:0;
		$html ='<h3>我的专辑</h3><div class="show_info_tips show_info">
		<div class="create_album">
		<ul>
					<li><span class="album_text"><span class="need">*</span>标题：</span><input id="album_name" type="text" value="'.$album_name.'"/></li>
					<li><span class="album_text">简介：</span><textarea id="album_brief" cols="35" rows="5">'.$album_brief.'</textarea></li>
					<li><span class="album_text">所属分类：</span>
						<div class="album_sort">';
		$ra = "";
		foreach($this->settings['album'] as $key => $value)
		{
			if($album_sort)
			{
				if($album_sort==$key)
				{
					$value['checked'] = 'checked';
				}
				else 
				{
					$value['checked'] = '';
				}
			}
			$ra .='<input type="radio" '.$value['checked'].' name="album_sort[]" id="r_'.$value['id'].'" value="'.$value['id'].'"/>'.$value['name'];
		}
		
		$html = $html.$ra.'</div>
					</li>
					<li class="album_bt clear">
					<input type="button" value="《 返回专辑列表" onclick="return_album();"/><input type="button" value="重填" onclick="album_reset();"/><input type="button" value="为专辑添加视频 》" onclick="edit_album_video();"/>
					</li></ul></div></div>';
		return $html;
	}	
	
	/**
	 * 
	 * 显示专辑列表的模板页面（包括数据）
	 */
	private function show_album_html()
	{
		$count = $this->count;
		$page = $this->page;
		
		$id = $this->user['id']?$this->user['id']:0;
		if(!$id)
		{
			$this->check_login();
		}
		$album_info = $this->mVideo->get_album_info($id,$page,$count);
		if($album_info['total'])
		{
				$data['totalpages'] = $album_info['total'];
				unset($album_info['total']);
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$showpages = hg_build_pagelinks($data);
		}
		$html = '<h3><a href="'.hg_build_link("upload.php").'">+上传视频</a><a href="javascript:void(0);"onclick="create_album(1);">+创建专辑</a>我的专辑</h3>
		<div class="show_info_tips show_info">
			<div class="album">
				<ul class="album_ul">';	
		$li = "";	
		if($album_info)	
		{
			foreach($album_info as $key => $value)
			{
				
				$li .='<li class="album_li" onmousemove="album_mouse('.$value['id'].',0)" onmouseout="album_mouse('.$value['id'].',1)">
							<a href="javascript:void(0);" onclick="manage_album_video('.$value['id'].');"><img src="'.$value['cover'].'"/></a>
							<div id="album_na_'.$value['id'].'" class="album_na">
								<a href="javascript:void(0);" title="'.$value['name'].'" onclick="manage_album_video('.$value['id'].');">'.hg_cutchars($value['name'],8," ").'('.$value['video_count'].')</a>
							</div>
							<div id="album_ma_'.$value['id'].'" class="album_ma" style="display:none;">
								<a href="javascript:void(0);" onclick="del_album('.$value['id'].');">删除</a>
								<a href="javascript:void(0);" onclick="edit_album_info('.$value['id'].');">编辑</a>
							</div>
						</li>';
			}
		}
		else 
		{
			$error = '<div class="error"><h2></h2><p><img align="absmiddle" title="" alt="" src="../vui/res/img/error.gif">暂未创建专辑</p></div>';
			$li = '<li>'.$error.'</li>';
		}
			
		$html .= $li.'</ul><div class="clear"></div>'.$showpages.'</div></div>';					
		
		return $html;
	}
	
	
	/**
	 * 
	 * 包含 我的视频，我的收藏，搜索，根据传递的类型，来判别返回数据类型
	 */
	private function show_video_html()
	{
		$count = $this->count;
		$page = $this->page;
		
		$id = $this->user['id'];
		if(!$id)
		{
			$this->check_login();
		}
		
		
		$upload_video = rtrim($this->input['upload_video'],',');
		$arr_upload = explode(',', $upload_video);
		foreach($arr_upload as $k1  => $v1)
		{
			$arr_upload[$v1] = $v1;
		}
		
		$favorite_video = rtrim($this->input['favorite_video'],',');
		$arr_favorite = explode(',', $favorite_video);
		foreach($arr_favorite as $k2  => $v2)
		{
			$arr_favorite[$v2] = $v2;
		}
		
		$search_video = rtrim($this->input['search_video'],',');
		$arr_search = explode(',', $search_video);
		foreach($arr_search as $k3  => $v3)
		{
			$arr_search[$v3] = $v3;
		}
		
		$html = '<h3>创建专辑</h3><div class="show_info_tips show_info">
				<ul class="video-select">
				<li class="clear">
					<span class="label1">从上传视频中选取：</span>
					<div>';
		$error = "";
		$upload_video = $this->mVideo->get_video_info($id,$page,$count,"");
		$upload_video['total'] = $upload_video[count($upload_video)-1];
		unset($upload_video[count($upload_video)-2]);
		$html .='<span>共上传了<a>'.($upload_video['total']?$upload_video['total']:0).'</a>个视频<a class="f_r" href="javascript:void(0);" onclick="select_upload();">选取</a></span>
		<div class="select-list" id="upload_list" style="display:none;">';
		if(!$upload_video['total'])
		{
			$html .= '暂无视频  <a target="_blank" href="upload.php">上传</a>';
		}
		else 
		{
			$data['totalpages'] = $upload_video['total'];
				unset($upload_video['total']);
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
				$data['onclick'] = 'onclick="album_page_show(this,1);"';
				$showpages = hg_build_pagelinks($data);
				$html .=$showpages.'<ul>';
				$li ="";
				foreach($upload_video as $key => $value)
				{
					$checked = '';
					if($arr_upload&&$value['id'] == $arr_upload[$value['id']])
					{
						$checked = 'checked';
					}
					$li .= '<li><input id="chu_'.$value['id'].'" type="checkbox" '.$checked.' name="up" value="'.$value['id'].'" onclick="check_list(this,1);"/><label for="chu_'.$value['id'].'">'.$value['title'].'</label></li>';
				}
				$html .=$li.'</ul>'.$showpages;
		}
		
		
		$error = "";
		$favorite_video = $this->mVideo->get_user_collect($id,0,$page,$count);
		$html .='</div>
					</div>
					</li>
				<li class="clear">
					<span class="label1">从我的收藏夹中选取：</span>
					<div>
					<span>共收藏了<a>'.$favorite_video['total'].'</a>个视频<a class="f_r" href="javascript:void(0);" onclick="select_favorite();">选取</a></span>
						<div class="select-list" id="favorite_list" style="display:none;">';
		if(!$favorite_video['total'])
		{
			$html .= '<div class="error"><h2></h2><p><img align="absmiddle" title="" alt="" src="'.RESOURCE_DIR.'img/error.gif">暂无收藏</p></div>';
		}
		else 
		{
			$data['totalpages'] = $favorite_video['total'];
			unset($favorite_video['total']);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
			$data['onclick'] = 'onclick="album_page_show(this,2);"';
			$showpages = hg_build_pagelinks($data);
			$html .=$showpages.'<ul>';
			$li ="";
			foreach($favorite_video as $key => $value)
			{
				$checked = '';
				if($arr_favorite && $value['id'] == $arr_favorite[$value['id']])
				{
					$checked = 'checked';
				}
				$li .= '<li><input id="chf_'.$value['id'].'" type="checkbox" '.$checked.' name="fa" value="'.$value['id'].'" onclick="check_list(this,2);"/><label for="chf_'.$value['id'].'">'.$value['title'].'</label></li>';
			}
			$html .=$li.'</ul>'.$showpages;
		}
		
		$html .='</div>					
					</div>
				</li>
			<li class="clear">
					<span class="label1">从搜索中选取：</span>
					<div id="search_result">';

		
		
		$name = $this->input['name']? $this->input['name']:"";
		$video_info = $this->mVideo->video_search($name,$page,$count);
		$video_info['total'] = $video_info[count($video_info)-1];
		unset($video_info[count($video_info)-2]);
		
		$html .= '
		<input type="text" id="album_video" value="'.$name.'"/><a style="border:1px solid #ccc;padding:2px;margin-left:5px;" href="javascript:void(0);" onclick="search_album_video();">GO</a>';
		if(!$video_info['total'])
		{
			$html .= '  <span>共检索<a style="color:red;">0</a>个视频</span>';
			$html .= '<div class="select-list" id="search_list" style="display:none;">
			</div>
			</div>';
		}
		else 
		{
			$html .= '  <span>共检索<a style="color:red;">'.$video_info['total'].'</a>个视频</span>';
			$data['totalpages'] = $video_info['total'];
			unset($video_info['total']);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
			$data['onclick'] = 'onclick="album_page_show(this,3);"';
			$showpages = hg_build_pagelinks($data);
			$html .= '<div class="select-list" id="search_list" style="display:block;">'.$showpages.'
								<ul>';
			$li = '';
			foreach($video_info as $key=>$value)
			{
				$checked = '';
				if($arr_search && $value['id'] == $arr_search[$value['id']])
				{
					$checked = 'checked';
				}
				$li .= '<li><input id="chs_'.$value['id'].'" type="checkbox" '.$checked.' name="se" onclick="check_list(this,3);" value="'.$value['id'].'"/><label for="chs_'.$value['id'].'">'.$value['title'].'</label></li>';
			}
			$html .=$li.'</ul>'.$showpages.'
						</div>';
		}
		$html .='							
					
				</li>
			</ul>
			<div class="video-select-bt"><input type="button" value="《   修改专辑信息" onclick="edit_album();"/><input type="button" value="完成   》" onclick="album_bt();"/></div>
			</div></div>';
		return $html;
	}
	
		
	/**
	 * 
	 * 针对已完成专辑直接增加视频
	 */
	public function add_video_html()
	{
		$count = $this->count;
		$page = $this->page;
		
		
		$album_name = $this->input['album_name']? $this->input['album_name']:'';
		$album_total = $this->input['album_total']? $this->input['album_total']:0;
		$album_id = $this->input['album_id']? $this->input['album_id']:0;
		$id = $this->user['id'];
		if(!$id&&!$album_name&&!$album_id)
		{
			$this->check_login();
		}
		
		$html = '<h3>创建/修改专辑</h3><div class="show_info_tips show_info">
			<div class="edit_ablum_video"><span>添加视频到专辑：<a title="'.$album_name.'" style="color: #000000; cursor: pointer; float: none; padding: 0; text-decoration: none;"><b>'.hg_cutchars($album_name,10," ").'</b></a>   视频数: '.$album_total.' </span></div>
			<ul class="video-select">
				<li class="clear">
					<span class="label1">从上传视频中选取：</span>
					<div>';
		$error = "";
		$upload_video = $this->mVideo->get_video_info($id,$page,$count,"");
		$upload_video['total'] = $upload_video[count($upload_video)-1];
		unset($upload_video[count($upload_video)-2]);
		$html .='<span>共上传了<a>'.($upload_video['total']?$upload_video['total']:0).'</a>个视频<a class="f_r" href="javascript:void(0);" onclick="select_upload();">选取</a></span>
		<div class="select-list" id="upload_list" style="display:none;">';
		if(!$upload_video['total'])
		{
			$html .= '暂无视频  <a target="_blank" href="upload.php">上传</a>';
		}
		else 
		{
			$data['totalpages'] = $upload_video['total'];
				unset($upload_video['total']);
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
				$data['onclick'] = 'onclick="album_page_show(this,1);"';
				$showpages = hg_build_pagelinks($data);
				$html .=$showpages.'<ul>';
				$li ="";
				foreach($upload_video as $key => $value)
				{
					$li .= '<li><input id="chu_'.$value['id'].'" type="checkbox" name="up" value="'.$value['id'].'" onclick="check_list(this,1);"/><label for="chu_'.$value['id'].'">'.$value['title'].'</label></li>';
				}
				$html .=$li.'</ul>'.$showpages;
		}
		
		
		$error = "";
		$favorite_video = $this->mVideo->get_user_collect($id,0,$page,$count);
		$html .='</div>
					</div>
					</li>
				<li class="clear">
					<span class="label1">从我的收藏夹中选取：</span>
					<div>
					<span>共收藏了<a>'.$favorite_video['total'].'</a>个视频<a class="f_r" href="javascript:void(0);" onclick="select_favorite();">选取</a></span>
						<div class="select-list" id="favorite_list" style="display:none;">';
		if(!$favorite_video['total'])
		{
			$html .= '<div class="error"><h2></h2><p><img align="absmiddle" title="" alt="" src="'.RESOURCE_DIR.'img/error.gif">暂无收藏</p></div>';
		}
		else 
		{
			$data['totalpages'] = $favorite_video['total'];
			unset($favorite_video['total']);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
			$data['onclick'] = 'onclick="album_page_show(this,2);"';
			$showpages = hg_build_pagelinks($data);
			$html .=$showpages.'<ul>';
			$li ="";
			foreach($favorite_video as $key => $value)
			{
				$li .= '<li><input id="chf_'.$value['id'].'" type="checkbox" name="fa" value="'.$value['id'].'" onclick="check_list(this,2);"/><label for="chf_'.$value['id'].'">'.$value['title'].'</label></li>';
			}
			$html .=$li.'</ul>'.$showpages;
		}
		
		$html .='</div>					
					</div>
				</li>
			<li class="clear">
					<span class="label1">从搜索中选取：</span>
					<div id="search_result">';

		
		
		$name = $this->input['name']? $this->input['name']:"";
		$video_info = $this->mVideo->video_search($name,$page,$count);
		$video_info['total'] = $video_info[count($video_info)-1];
		unset($video_info[count($video_info)-2]);
		
		$html .= '
		<input type="text" id="album_video" value="'.$name.'"/><a style="border:1px solid #ccc;padding:2px;margin-left:5px;" href="javascript:void(0);" onclick="search_album_video();">GO</a>';
		if(!$video_info['total'])
		{
			$html .= '  <span>共检索<a style="color:red;">0</a>个视频</span>';
			$html .= '<div class="select-list" id="search_list" style="display:none;">
			</div>
			</div>';
		}
		else 
		{
			$html .= '  <span>共检索<a style="color:red;">'.$video_info['total'].'</a>个视频</span>';
			$data['totalpages'] = $video_info['total'];
			unset($video_info['total']);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
			$data['onclick'] = 'onclick="album_page_show(this,3);"';
			$showpages = hg_build_pagelinks($data);
			$html .= '<div class="select-list" id="search_list" style="display:block;">'.$showpages.'
								<ul>';
			$li = '';
			foreach($video_info as $key=>$value)
			{
				$checked = '';
				if($value['id'] == $arr_search[$value['id']])
				{
					$checked = 'checked';
				}
				$li .= '<li><input id="chs_'.$value['id'].'" type="checkbox" '.$checked.' name="se" onclick="check_list(this,3);" value="'.$value['id'].'"/><label for="chs_'.$value['id'].'">'.$value['title'].'</label></li>';
			}
			$html .=$li.'</ul>'.$showpages.'
						</div>';
		}
		$html .='							
					
				</li>
			</ul>
			<div class="video-select-bt"><input type="button" value="完成   》" onclick="album_bt('.$album_id.');"/></div>
			</div></div>';
		return $html;
		
	}

	/**
	* 修改专辑
	* @param album_id
	* @param video_id
	* @param name
	* @param brief
	* @param sort_id
	* @return $ret 专辑信息
	*/
	public function edit_album()
	{
		$album_id = $this->input['album_id'];
		$video_id_n = $this->input['video_id'];
		$name =  $this->input['name'];
		$brief =  $this->input['brief'];
		$sort_id =  $this->input['sort_id'];
		$album_info = array(
			'album_id' => $album_id,
			'video_id_n' => $video_id_n,
			'name' => $name,
			'brief' => $brief,
			'sort_id' => $sort_id,
		);
		$ret = $this->mVideo->edit_album($album_info);
		$html = "";
		if($ret['id'])
		{
			$html = '<h3>修改专辑</h3><div class="show_info_tips show_info"><div class="album_result">专辑<a title="'.$ret['name'].'" style="color: #000000; cursor: pointer; float: none; padding: 0; text-decoration: none;"><b>'.hg_cutchars($ret['name'],8," ").'</b></a> 已经成功修改<ul class="result_list">			
			<li><a href="javascript:void(0);" onclick="create_album(1);">创建新专辑</a></li>
			<li><a href="javascript:void(0);" onclick="return_album();">查看其他专辑</a></li>
			<li><a href="javascript:void(0);" onclick="manage_album_video('.$album_info['album_id'].');">查看本专辑</a></li>
			<li><a href="user_album_video.php?id='.$album_info['album_id'].'&user_id='.$ret['user_id'].'">播放本专辑</a></li>
			</ul></div></div>';
		}
		echo ($html);
		exit;
	}
	
	
	/**
	* 修改封面
	* @param $album_id 
	* @param $video_id 
	* @return $ret 专辑信息
	*/
	public function edit_album_cover()
	{
		$album_id = $this->input['album_id']? $this->input['album_id']:0;
		$video_id = $this->input['video_id']? $this->input['video_id']:0;
		$ret = $this->mVideo->edit_album_cover($video_id,$album_id);
		if($ret)
		{
			echo $ret;
			exit;
		}
		else
		{
			echo "";
			exit;
		}
	}
	

	/**
	* 移除视频
	* @param $id 
	* @param $album_id 用于减去专辑表中是视频数
	* @return $id 关系ID
	*/
	public function del_album_video()
	{
		$id = $this->input['id']? $this->input['id']:0;
		$album_id = $this->input['album_id']? $this->input['album_id']:0;
		$ret = $this->mVideo->del_album_video($id,$album_id);
		if($ret)
		{
			echo $ret;
			exit;
		}
		else
		{
			echo "";
			exit;
		}
	}
	
	
	
	/**
	 * 
	 * 分页显示操作
	 */
	public function video_list()
	{
		$count = $this->count;
		$page = $this->page;
		
		$id = $this->user['id'];
		if(!$id)
		{
			$this->check_login();
		}
		$page_video = rtrim($this->input['page_video'],',');
		$arr_page = explode(',', $page_video);
		foreach($arr_page as $k1  => $v1)
		{
			$arr_page[$v1] = $v1;
		}
		
		$type = $this->input['type'];
		switch ($type) {
			case 1:
				$upload_video = $this->mVideo->get_video_info($id,$page,$count,"");
				$upload_video['total'] = $upload_video[count($upload_video)-1];
				unset($upload_video[count($upload_video)-2]);
				$html .='';
				if(!$upload_video['total'])
				{
					$html .= '暂无视频  <a target="_blank" href="upload.php">上传</a>';
				}
				else 
				{
					$data['totalpages'] = $upload_video['total'];
						unset($upload_video['total']);
						$data['perpage'] = $count;
						$data['curpage'] = $this->input['pp'];
						$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
						$data['onclick'] = 'onclick="album_page_show(this,'.$type.');"';
						$showpages = hg_build_pagelinks($data);
						$html .=$showpages.'<ul>';
						$li ="";
						foreach($upload_video as $key => $value)
						{
							$checked = '';
							if($value['id'] == $arr_page[$value['id']])
							{
								$checked = 'checked';
							}
							$li .= '<li><input id="chu_'.$value['id'].'" type="checkbox" '.$checked.' name="up" onclick="check_list(this,'.$type.');" value="'.$value['id'].'"/><label for="chu_'.$value['id'].'">'.$value['title'].'</label></li>';
						}
						$html .=$li.'</ul>'.$showpages;
				}
				break;
			case 2:
				$favorite_video = $this->mVideo->get_user_collect($id,0,$page,$count);
				$html .='';
				if(!$favorite_video['total'])
				{
					$html .= '<div class="error"><h2></h2><p><img align="absmiddle" title="" alt="" src="'.RESOURCE_DIR.'img/error.gif">暂无收藏</p></div>';
				}
				else 
				{
					$data['totalpages'] = $favorite_video['total'];
					unset($favorite_video['total']);
					$data['perpage'] = $count;
					$data['curpage'] = $this->input['pp'];
					$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
					$data['onclick'] = 'onclick="album_page_show(this,'.$type.');"';
					$showpages = hg_build_pagelinks($data);
					$html .=$showpages.'<ul>';
					$li ="";
					foreach($favorite_video as $key => $value)
					{
						$checked = '';
						if($value['id'] == $arr_page[$value['id']])
						{
							$checked = 'checked';
						}
						$li .= '<li><input id="chf_'.$value['id'].'" type="checkbox" '.$checked.' name="fa" onclick="check_list(this,'.$type.');" value="'.$value['id'].'"/><label for="chf_'.$value['id'].'">'.$value['title'].'</label></li>';
					}
					$html .=$li.'</ul>'.$showpages;
				}
				break;
			case 3:
				$name = $this->input['name']? $this->input['name']:"";
				$video_info = $this->mVideo->video_search($name,$page,$count);
				$video_info['total'] = $video_info[count($video_info)-1];
				unset($video_info[count($video_info)-2]);
				$html ='';
				if(!$video_info['total'])
				{
					$html .= '<div class="error"><h2></h2><p><img align="absmiddle" title="" alt="" src="../vui/res/img/error.gif">暂无数据</p></div>';
				}
				else 
				{
					$data['totalpages'] = $video_info['total'];
					unset($video_info['total']);
					$data['perpage'] = $count;
					$data['curpage'] = $this->input['pp'];
					$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
					$data['onclick'] = 'onclick="album_page_show(this,'.$type.');"';
					$showpages = hg_build_pagelinks($data);
					$html .=$showpages.'<ul>';
					$li ="";
					foreach($video_info as $key => $value)
					{
						$checked = '';
						if($value['id'] == $arr_page[$value['id']])
						{
							$checked = 'checked';
						}
						$li .= '<li><input id="chs_'.$value['id'].'" type="checkbox" '.$checked.' name="se" onclick="check_list(this,'.$type.');" value="'.$value['id'].'"/><label for="chs_'.$value['id'].'">'.$value['title'].'</label></li>';
					}
					$html .=$li.'</ul>'.$showpages;
				}
				break;
			case 4:
				$album_id = $this->input['album_id']?$this->input['album_id']:0;
				$disabled = $this->input['page_video']?'':'disabled';
				$html = '';
				if(!$album_id)
				{
					$html = '';
				}
				else 
				{
					$album_video = $this->mVideo->get_album_video($album_id,$page,$count);
					if($album_video)
					{
						$html ='<h3>
					<a href="javascript:void(0);" onclick="edit_album_info('.$album_video['id'].');">修改专辑信息</a>    <a target="_blank" href="user_album_video.php?id='.$album_video['id'].'&user_id='.$album_video['user_id'].'">播放专辑</a>
					<input type="hidden" id="album_id" value="'.$album_video['id'].'"/>
					<input type="hidden" id="album_name" value="'.$album_video['name'].'"/>
					<input type="hidden" id="album_brief" value="'.$album_video['brief'].'"/>
					<input type="hidden" id="album_sort" value="'.$album_video['sort_id'].'"/>
					<input type="hidden" id="album_total" value="'.$album_video['video']['total'].'"/>
					<span class="blod">专辑：<a title="'.$album_video['name'].'" style="color: #000000; cursor: pointer; float: none; padding: 0; text-decoration: none;">'.hg_cutchars($album_video['name'],8," ").'</a></span>
					<span style="margin-left:30px;">共'.$album_video['video']['total'].'个视频</span>
				</h3>';
						$data['totalpages'] = $album_video['video']['total'];  
						unset($album_video['video']['total']);
						$data['perpage'] = $count;
						$data['curpage'] = $this->input['pp'];
						$data['onclick'] = 'onclick="album_page_show(this,'.$type.');"';
						$showpages = hg_build_pagelinks($data);
						$html .= '<div class="video_cp clear"><input type="button" value="添加视频" onclick="add_album_video('.$album_id.');"/><input name="get" type="button" onclick="del_album_video();" value="删除视频" '.$disabled.'/><input name="get" onclick="move_album_show('.$album_id.');" type="button" value="移动到专辑" '.$disabled.'/>
						'.$showpages.'</div>
						<div class="video_list" id="video_list clear">
						<table width="100%" cellspacing=0 cellpadding=0>
						<tr><th width="100px">序号</th><th>视频名称</th><th>添加时间</th><th>会员</th><th>播放次数</th><th>管理</th></tr>
						';
					}
					
					$i = 1;
					foreach($album_video['video'] as $key => $value)
					{
						$checked = '';
						if($value['id'] == $arr_page[$value['id']])
						{
							$checked = 'checked';
						}
						$tr .= '<tr>
									<td width="100px"><input '.$checked.' name="vch" type="checkbox" value="'.$value['id'].'" onclick="check_list(this,4);"/>'.$i.'</td>
									<td><a target="_blank" title="'.$value['title'].'" href="'.hg_build_link('video_play.php', array('id'=>$value['id'])).'"><img src="'.$value['schematic'].'"/></a>
										<a target="_blank" title="'.$value['title'].'" href="'.hg_build_link('video_play.php', array('id'=>$value['id'])).'">'.hg_cutchars($value['title'],6," ").'</a>
									</td>
									<td>'.$value['create_time'].'</td>
									<td><a target="_blank" title="'.$value['user']['username'].'" href="'.hg_build_link('user.php', array('id'=>$value['user']['id'])).'">'.hg_cutchars($value['user']['username'],5," ").'</a></td>
									<td>'.$value['play_count'].'</td>
									<td>
										<a href="javascript:void(0);" onclick="edit_album_cover('.$value['id'].','.$album_video['id'].');">设为封面</a>|<a href="javascript:void(0);" onclick="del_album_video('.$value['id'].');">移除</a>
									</td>
								</tr>';
						$i++;
					}
					$html .= $tr.'</table>
						</div>
						<div><a class="f_r" onclick="return_album();" href="javascript:void(0);">返回列表</a></div>
						<div class="video_cp clear"><input type="button" value="添加视频" onclick="add_album_video('.$album_id.');"/><input name="get" type="button" onclick="del_album_video();" value="删除视频" '.$disabled.'/><input name="get" type="button" onclick="move_album_show('.$album_id.');" value="移动到专辑" '.$disabled.'/>'.$showpages.'</div>';
				}
				
				
				break;
			case 5:
				$html = "";
				$album_list = $this->mVideo->get_album_info($id,$page,$count);
				if($album_list['total'])
				{
					$data['totalpages'] = $album_list['total'];
					unset($album_list['total']);
					$data['perpage'] = $count;
					$data['curpage'] = $this->input['pp'];
					$data['onclick'] = 'onclick="album_page_show(this,5);"';
					$showpage = hg_build_pagelinks($data);
				}
				foreach ($album_list as $key => $value)
				{
					$html .='<li><input name="alb" type="radio" onclick="move_album_video(this);" value="'.$value['id'].'"/><a href="javascript:void(0);" onclick="move_album_video('.$value['id'].');">'.$value['name'].'</a><span>'.$value['create_time'].'</span></li>';
				}
				break;
			default:
				break;
		}
		echo $html;
		exit;
	}
	
	/**
	 * 
	 * 查询
	 */
	public function search_video(){
		$count = $this->count;
		$page = $this->page;
		
		$name = $this->input['name']? $this->input['name']:"";
		$video_info = $this->mVideo->video_search($name,$page,$count);
		$video_info['total'] = $video_info[count($video_info)-1];
		unset($video_info[count($video_info)-2]);
		
		$html = '
		<input type="text" id="album_video" value="'.$name.'"/><a style="border:1px solid #ccc;padding:2px;margin-left:5px;" href="javascript:void(0);" onclick="search_album_video();">GO</a>';
		if(!$video_info['total'])
		{
			$html .= '  <span>共检索<a style="color:red;">0</a>个视频</span>';
			$html .= '<div class="select-list" id="search_list" style="display:block;">
			<div class="error"><h2></h2>
			<p>
				<img align="absmiddle" title="" alt="" src="../vui/res/img/error.gif">没有检索出与关键词“<b style="color: red;">'.$name.'</b>”相符的视频
			</p>
			</div>
			</div>';
		}
		else 
		{
			$html .= '  <span>共检索<a style="color:red;">'.$video_info['total'].'</a>个视频</span>';
			$data['totalpages'] = $video_info['total'];
			unset($video_info['total']);
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
			$data['onclick'] = 'onclick="album_page_show(this,3);"';
			$showpages = hg_build_pagelinks($data);
			$html .= '<div class="select-list" id="search_list" style="display:block;">'.$showpages.'
								<ul>';
			$li = '';
			foreach($video_info as $key=>$value)
			{
				$checked = '';
				if($value['id'] == $arr_page[$value['id']])
				{
					$checked = 'checked';
				}
				$li .= '<li><input id="chs_'.$value['id'].'" type="checkbox" '.$checked.' name="se" onclick="check_list(this,3);" value="'.$value['id'].'"/><label for="chs_'.$value['id'].'">'.$value['title'].'</label></li>';
			}
			$html .=$li.'</ul>'.$showpages.'
						</div>';
		}
		
		echo ($html);
		exit;
	}
	
	
	/**
	* 获得专辑中的视频
	* @param $album_id
	* @param $page
	* @param $count
	* @return $ret 专辑信息
	*/
	public function get_album_video()
	{
		$count = $this->count;
		$page = $this->page;
		$album_id = $this->input['album_id']?$this->input['album_id']:0;
		$disabled = $this->input['album_video']?'':'disabled';
		$html = '';
		if(!$album_id)
		{
			$html = '';
		}
		else 
		{
			$album_video = $this->mVideo->get_album_video($album_id,$page,$count);
			$html = '<h3>
				<a href="javascript:void(0);" onclick="edit_album_info('.$album_video['id'].');">修改专辑信息</a>    <a target="_blank" href="user_album_video.php?id='.$album_video['id'].'&user_id='.$album_video['user_id'].'">播放专辑</a>
			<input type="hidden" id="album_id" value="'.$album_video['id'].'"/>
			<input type="hidden" id="album_name" value="'.$album_video['name'].'"/>
			<input type="hidden" id="album_brief" value="'.$album_video['brief'].'"/>
			<input type="hidden" id="album_sort" value="'.$album_video['sort_id'].'"/>
			<input type="hidden" id="album_total" value="'.$album_video['video']['total'].'"/>
			<span class="blod">专辑：<a title="'.$album_video['name'].'" style="color: #000000; cursor: pointer; float: none; padding: 0; text-decoration: none;">'.hg_cutchars($album_video['name'],8," ").'</a></span>
			<span style="margin-left:30px;">共'.($album_video['video']['total']?$album_video['video']['total']:0).'个视频</span>
		</h3><div class="show_info_tips show_info">';
			if($album_video)
			{
				$data['totalpages'] = $album_video['video']['total'];
				unset($album_video['video']['total']);
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$data['onclick'] = 'onclick="album_page_show(this,4);"';
				$showpages = hg_build_pagelinks($data);
			}
			$html .= '<div class="video_cp clear"><input type="button" value="添加视频" onclick="add_album_video('.$album_id.');"/><input name="get" type="button" onclick="del_album_video();" value="删除视频" '.$disabled.'/><input name="get" onclick="move_album_show('.$album_id.');" type="button" value="移动到专辑" '.$disabled.'/>
				'.$showpages.'</div>
				<div class="video_list" id="video_list clear">
				<table width="100%" cellspacing=0 cellpadding=0>
				<tr><th width="100px">序号</th><th>视频名称</th><th>添加时间</th><th>会员</th><th>播放次数</th><th>管理</th></tr>
				';
			if($album_video['video']&&is_array($album_video['video']))
			{
				$i = 1;
				foreach($album_video['video'] as $key => $value)
				{
					$tr .= '<tr>
								<td width="100px"><input name="vch" type="checkbox" value="'.$value['id'].'" onclick="check_list(this,4);"/>'.$i.'</td>
								<td><a target="_blank" title="'.$value['title'].'" href="'.hg_build_link('video_play.php', array('id'=>$value['id'])).'"><img src="'.$value['schematic'].'"/></a>
									<a target="_blank" title="'.$value['title'].'" href="'.hg_build_link('video_play.php', array('id'=>$value['id'])).'">'.hg_cutchars($value['title'],6," ").'</a>
								</td>
								<td>'.$value['create_time'].'</td>
								<td><a target="_blank" title="'.$value['user']['username'].'" href="'.hg_build_link('user.php', array('id'=>$value['user']['id'])).'">'.hg_cutchars($value['user']['username'],5," ").'</a></td>
								<td>'.$value['play_count'].'</td>
								<td>
									<a href="javascript:void(0);" onclick="edit_album_cover('.$value['id'].','.$album_video['id'].');">设为封面</a>|<a href="javascript:void(0);" onclick="del_album_video('.$value['id'].');">移除</a>
								</td>
							</tr>';
					$i++;
				}
			}
			$html .= $tr.'</table>
				</div>
				<div><a class="f_r" onclick="return_album();" href="javascript:void(0);">返回列表</a></div>
				<div class="video_cp clear"><input type="button" value="添加视频" onclick="add_album_video('.$album_id.');"/><input name="get" type="button" onclick="del_album_video();" value="删除视频" '.$disabled.'/><input name="get" type="button" onclick="move_album_show('.$album_id.');" value="移动到专辑" '.$disabled.'/>'.$showpages.'</div>
				</div>';
		}
		echo ($html);
		exit;
	}
	
	
	
	/**
	* 根据专辑ID获取专辑的详细信息，包括视频信息，用于修改视频信息
	* @param $album_id 
	* @return $ret 专辑信息
	*/
	public function get_album_info()
	{
		
		$album_id = $this->input['album_id']? $this->input['album_id']:0;
		$video_info = $this->mVideo->get_album_video($album_id);
		$video_info['total'] = count($video_info['video']);
		if($video_info['video'])
		{
			$video_id = "";
			$space = ",";
			foreach($video_info['video'] as $key=>$value)
			{
				$video_id .= $value["id"].$space;
			}
		}
		$video_info['video'] = $video_id;
		if($video_info)
		{
			echo json_encode($video_info);
			exit;
		}
		else
		{
			echo "";
			exit;
		}
	}
	
	/**
	* 转移专辑中的视频（包括关联表中的信息）
	* @param $album_id（是当前专辑ID）
	* @param $album_id_n （是转移之后的专辑ID）
	* @param $video_id（需要转移的视频ID）
	* @return $album_id 专辑ID
	*/
	public function move_album_video()
	{
		$album_id = $this->input['album_id'];
		$album_id_n = $this->input['album_id_n'];
		$video_id = $this->input['video_id'];
		$ret = $this->mVideo->move_album_video($album_id, $album_id_n, $video_id);
		if($ret)
		{
			echo $ret;
			exit;
		}
		
	}
	
}

$out = new myAlbum();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>