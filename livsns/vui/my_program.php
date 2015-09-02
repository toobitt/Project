<?php

/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: my_program.php 4412 2011-08-16 08:17:01Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'my_program');
class myProgram extends uiBaseFrm
{
	private $mVideo;
	function __construct()
	{
		parent::__construct();	
		if(!ALLOW_PROGRAME)
		{
			header("Location:" . SNS_VIDEO . "my_video.php");
		}
		include_once (ROOT_PATH . 'lib/video/video.class.php');
		$this->mVideo = new video();
		$this->load_lang('my_station');
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$id = $this->user['id'];
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
		
		
		$station = $this->mVideo->get_user_station($id);
		$sta_id = $this->input['sta_id']? $this->input['sta_id']:($station['id']?$station['id']:0);
		
		
		$stationInfo = $this->mVideo->get_station($sta_id,$id,1);
		$stationInfo = $stationInfo[0];
		$count = 15;
		$page = (intval($this->input['pp'])?intval($this->input['pp']):0) / $count;
		$video_info = $this->mVideo->get_video_info($user_id,$page,$count);
		
		$cnt = count($video_info) - 1;

		if($video_info)
		{
			$data['totalpages'] = $video_info[$cnt];
			if (is_array($video_info))
			{
				unset($video_info[$cnt]);
			}
			$data['perpage'] = $count;
			$data['curpage'] = $this->input['pp'];
			$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
			$data['onclick'] = 'onclick="page_show(this,1);"';
			$showpages = hg_build_pagelinks($data);
		}
		
		$program_info = $this->mVideo->get_station_programe($sta_id,$id);
		$this->page_title = $this->lang['pageTitle'];
		$this->settings['nav_menu'][3] = array("name" => "编辑节目单", "url" => SNS_VIDEO.SCRIPTNAME, "last" => 1);
		$gScriptName = SCRIPTNAME;
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'my.js');
		
		$this->tpl->addVar('head_line', $this->settings['nav_menu']);
		
		$this->tpl->addVar('station', $station);	
		$this->tpl->addVar('sta_id', $sta_id);
		$this->tpl->addVar('video_info', $video_info);
		$this->tpl->addVar('showpages', $showpages);
		$this->tpl->addVar('program_info', $program_info);		
		$this->tpl->addVar('id', $id);	
		$this->tpl->addVar('user_id', $user_id);
		$this->tpl->addVar('user_info', $user_info);
		$this->tpl->addVar('hot_station', $hot_station);
		$this->tpl->addVar('hot_video', $hot_video);
		$this->tpl->addVar('stationInfo', $stationInfo);	
		$this->tpl->addVar('gScriptName', $gScriptName);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('my_program');
	}
	
	public function create()
	{
		$sta_id = $this->input['sta_id']?$this->input['sta_id']:0;
		$video_id = $this->input['video_id']? $this->input['video_id']:0;
		$program_name = $this->input['program_name']?$this->input['program_name']:"";
		$brief = $this->input['brief']?$this->input['brief']:"";
		$start = $this->input['start_time']?$this->input['start_time']:0;
		$end = $this->input['end_time']?$this->input['end_time']:0;
		if(!$end && !$sta_id && !$video_id)
		{
			echo json_encode('');
			exit;
		}
		else 
		{
			$ret = $this->mVideo->create_station_program($sta_id, $video_id, $program_name, $brief, $start, $end);
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
	}
	
	public function show_one_html()
	{
		$toff = $this->input['toff'];
		$sta_id = $this->input['sta_id']?$this->input['sta_id']:0;
		$video_id = $this->input['video_id']? $this->input['video_id']:0;
		$video_name = $this->input['video_name']? $this->input['video_name']:0;
		$brief = $this->input['video_brief']? $this->input['video_brief']:"";
		if(!$end && !$sta_id && !$video_id)
		{
			echo json_encode('');
			exit;
		}
		else 
		{
			
			$html = '<div class="interacDisable"></div>
			<div class="interactArea" style="width:550px;">
				<div class="panels">
					<div id="panel_share" class="panel panelShare" style="display: block;">
						<div class="p2">
							<span class="close_share" style="float:right;margin-right:5px;margin-top:3px;" ><a  title="关闭" onclick="close_program_one()" href="javascript:void(0)">&nbsp;</a></span>
							<h4 style="padding:5px;margin-bottom:5px;">添加节目单</h4>
							<ul>
								<li style="float:left;">'.$this->lang['video_name'].'<input id="v_name" style="width:180px;;" disabled type="text" value="'.$video_name.'"/></li>
								<li style="float:left;padding-left: 10px;">'.$this->lang['program_name'].'<input id="p_name" style="width:165px;" type="text" value="'.$video_name.'"/><span id="p_name_tip" style="font-size:12px;color:red;padding-left:5px;display:none;">*</span></li>
								<li class="clear">'.$this->lang['program_brief'].'<textarea id="p_brief" rows="3" cols="62">'.$brief.'</textarea></li>
								<li><span style="width: 60px; text-align: right;display:inline-block">时长：</span><a href="javascript:void(0);" id="s_time">'.hg_toff_time(0,$toff).'</a></li>
								<li class="program_bt"><input type="button" value="提交" onclick="add_program_one();"/><input type="button" value="清空" onclick="reset_programe();"/></li>
							</ul>
							<input type="hidden" id="v_id" value="'.$video_id.'"/>
							<input type="hidden" id="s_id" value="'.$sta_id.'"/>
							<input type="hidden" id="v_toff" value="'.$toff.'"/>							
							<input type="hidden" id="start_time" value="0"/>
						</div>
						<div class="clear"></div>
					</div>  
				</div>
			</div> ';
			ob_end_clean();
			echo ($html);
			exit;
		}
			
		
	}
	
	public function edit()
	{
		$program_id = $this->input['program_id']?$this->input['program_id']:0;
		$program_name = $this->input['program_name']?$this->input['program_name']:'';
		$brief = $this->input['brief']?$this->input['brief']:'';
		if(!$program_id)
		{
			echo json_encode('');
			exit;
		}
		else 
		{
			$ret = $this->mVideo->edit_station_program($program_id, $program_name, $brief);
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
	}
	
	public function delete()
	{		
		$program_id = $this->input['program_id']?$this->input['program_id']:0;
		$sta_id = $this->input['sta_id']?$this->input['sta_id']:0;
		$gap = $this->input['gap']?$this->input['gap']:5;
		if(!$program_id && !$sta_id)
		{
			echo json_encode('');
			exit;
		}
		else 
		{
			$ret = $this->mVideo->del_station_program($program_id, $sta_id, $gap);
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
	}
	
	//0为up 1为down
	public function move()
	{		
		$program_id = $this->input['program_id']?$this->input['program_id']:0;
		$sta_id = $this->input['sta_id']?$this->input['sta_id']:0;
		$action = $this->input['action']?$this->input['action']:0;
		$gap = $this->input['gap']?$this->input['gap']:10;
		if(!$program_id && !$sta_id)
		{
			echo json_encode('');
			exit;
		}
		else 
		{
			$ret = $this->mVideo->move_station_program($program_id, $sta_id, $action, $gap);
			ob_end_clean();
			echo json_encode($ret);
			exit;
		}
	}
	
	public function videolist()
	{
		$id = $this->user['id'];
		if(!$id)
		{
			$this->check_login();
		}
		$station = $this->mVideo->get_user_station($id);
		$sta_id = $this->input['sta_id']? $this->input['sta_id']:($station['id']?$station['id']:0);
		$count = 15;
		$page = (intval($this->input['pp'])?intval($this->input['pp']):0) / $count;
		$type = $this->input['type']? $this->input['type']:1;
		$html = '<ul class="video_title">';
		$error = "";
		switch ($type)
		{
			case 1:
				$video_info = $this->mVideo->get_video_info($id,$page,$count);
				$html .= '<li class="video_title_now"><a href="javascript:void(0);">我的视频</a></li>
						<li><a href="javascript:void(0);" onclick="tab_video(2);">我的收藏</a></li>
						<li><a href="javascript:void(0);" onclick="tab_video(3);">搜索</a></li>';
				if(!$video_info[count($video_info)-1])
				{
					$error = '<li>暂无视频  <a target="_blank" href="upload.php">上传</a></li>';
				}
				if(count($video_info)>1)
				{
					$video_info['total'] = $video_info[count($video_info)-1];
					unset($video_info[count($video_info)-2]);
				}
				break;
			case 2:
				$video_info = $this->mVideo->get_user_collect($id,0,$page,$count);
				$html .= '<li><a href="javascript:void(0);" onclick="tab_video(1);">我的视频</a></li>
						<li class="video_title_now"><a href="javascript:void(0);">我的收藏</a></li>
						<li><a href="javascript:void(0);" onclick="tab_video(3);">搜索</a></li>';
				if(!$video_info['total'])
				{
					$error = '<li><div class="error"><h2> </h2><p><img align="absmiddle" title="" alt="" src="'.RESOURCE_DIR.'img/error.gif">暂无收藏</p></div></li>';
				}
				break;
			case 3:
				$title = $this->input['title']? $this->input['title']:"";
				$video_info = $this->mVideo->video_search($title,$page,$count);
				$html .= '<li><a href="javascript:void(0);" onclick="tab_video(1);">我的视频</a></li>
						<li><a href="javascript:void(0);" onclick="tab_video(2);">我的收藏</a></li>
						<li class="video_title_now"><a href="javascript:void(0);">搜索</a></li>
						<li style="width: auto; margin: 0pt; padding-top: 6px;"><input type="text" id="video_search" style="width: 110px; float: left;" value="'.$title.'"/>
						<input type="button" value="GO" id="bt_0" style="float: left;" onclick="search_video(this);"/></li>';
				$video_info['total'] = $video_info[count($video_info)-1];
				unset($video_info[count($video_info)-2]);
				break;
			default:
				break;
		}
		
		$html .= '</ul>
					<ul class="video-list">'.$error;
		$li = "";
		if($video_info)
			{
				$data['totalpages'] = $video_info['total'];
				unset($video_info['total']);
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
				$data['onclick'] = 'onclick="page_show(this,'.$type.');"';
				$showpages = hg_build_pagelinks($data);
				foreach($video_info as $key =>$value)
				{
				   $li .='<li><span>·</span>
				   			<a href="javascript:void(0);" onclick="add_program('.$value['id'].','.$sta_id.','.$value['toff'].')">'.hg_cutchars($value['title'],10,"..").'</a><span id="v_'.$value['id'].'" style="display:none;">'.$value['title'].'</span><img src="'.RESOURCE_DIR.'img/play_bt.jpg"/>
				   		</li>';
				}
			}
		$html .= $li."</ul>".$showpages;
		echo ($html);
		exit;
	}
	
	public function search_video()
	{
		$id = $this->user['id'];
		if(!$id)
		{
			$this->check_login();
		}
		$station = $this->mVideo->get_user_station($id);
		$sta_id = $this->input['sta_id']? $this->input['sta_id']:($station['id']?$station['id']:0);
		$count = 15;
		$page = (intval($this->input['pp'])?intval($this->input['pp']):0) / $count;
		$type = $this->input['type']? $this->input['type']:1;
		$html = '<ul class="video_title">';
		$error = "";
		$title = $this->input['title']? $this->input['title']:"";
		$video_info = $this->mVideo->video_search($title,$page,$count);
		if(!$video_info)
		{
			$error = '<li><img align="absmiddle" src="'.RESOURCE_DIR.'img/error.gif" alt="" title="">关键字不为空</li>';
		}
		if(count($video_info)==1)
		{
			$error = '<li><img align="absmiddle" src="'.RESOURCE_DIR.'img/error.gif" alt="" title="">关键字“<b style="color:red;">'.$title.'</b>”没有相关视频</li>';
		}
		$html .= '<li><a href="javascript:void(0);" onclick="tab_video(1);">我的视频</a></li>
				<li><a href="javascript:void(0);" onclick="tab_video(2);">我的收藏</a></li>
				<li class="video_title_now"><a href="javascript:void(0);">搜索</a></li>
				<li style="width: auto; margin: 0pt; padding-top: 6px;"><input type="text" id="video_search" style="width: 110px; float: left;" value="'.$title.'"/>
					<input type="button" value="GO" id="bt_0" style="float: left;" onclick="search_video(this);"/></li>';
		$video_info['total'] = $video_info[count($video_info)-1];
		$html .= '</ul>
					<ul class="video-list">'.$error;
		$li = "";
		if($video_info)
			{
				$data['totalpages'] = $video_info['total'];
				unset($video_info['total']);
				unset($video_info[count($video_info)-1]);
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$data['pagelink'] = hg_build_link('' , array('sta_id' => $sta_id));
				$data['onclick'] = 'onclick="page_show(this,3);"';
				$showpages = hg_build_pagelinks($data);
				foreach($video_info as $key =>$value)
				{
				   $li .='<li><span>·</span>
				   			<a href="javascript:void(0);" onclick="add_program('.$value['id'].','.$sta_id.','.$value['toff'].')">'.hg_cutchars($value['title'],10,"..").'</a><span id="v_'.$value['id'].'" style="display:none;">'.$value['title'].'</span><img src="'.RESOURCE_DIR.'img/play_bt.jpg"/>
				   		</li>';
				}
			}
		$html .= $li."</ul>".$showpages;
		echo ($html);
		exit;
	}
}

$out = new myProgram();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>