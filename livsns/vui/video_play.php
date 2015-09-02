<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: upload.php 1880 2011-01-26 10:12:54Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'channel');
class videoPlay extends uiBaseFrm
{
	private $mVideo;
	
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
	
	public function play()
	{		
		$video_id = intval(trim($this->input['id']));
		if($video_id)
		{
			
			/**
			 * 获取单个已发布的的视频
			 */
			$single_video_info = $this->mVideo->get_single_video($video_id);
			if(!$single_video_info['id'])
			{
				if($this->user['id'])
				{
					header('Location:' . SNS_VIDEO . 'user.php?user_id=' . $this->user['id']);
				}
				else 
				{
					header('Location:' . SNS_VIDEO );
				}
			}
			
			/**
			 * 更新这部视频的播放数
			 */
			
			$this->mVideo->update_play_count($video_id);
			
			
			if(is_array($single_video_info))
			{
				$user_id = $single_video_info['user_id'];
				$username = $single_video_info['user']['username'];
				
				$vip_url = $single_video_info['user']['vip_url'] ? $single_video_info['user']['vip_url'] : '';
				$visit_total = $single_video_info['click_count'];
			}
			
			if($this->user['id']==$user_id)
			{
				$relation = 1;
			}
			else 
			{
				$relation = 0;
			}
			
			if(!$relation&&$this->user['id'])
			{
				$this->mVideo->create_visit_history($user_id,$video_id);
			}
			
			$visit = $this->mVideo->get_visit_history($video_id,$type=1,$page=0,$count=10);
			
			$type = 0;
			$state = 1; //评论状态，0-待审核，1-已审核通过
			$cid = $video_id;
			$user_id = $user_id;
			$count = 10;
			$page = intval($this->input['pp']) / $count;
			
			$comment_list = $this->mVideo->get_comment_list($user_id,$cid,$type,$state,$page,$count);
			if(is_array($comment_list))
			{
				$total_nums = $comment_list['total'];
				unset($comment_list['total']);
				$data['totalpages'] = $total_nums;
				$data['perpage'] = $count;
				$data['curpage'] = $this->input['pp'];
				$data['onclick'] = ' onclick="comment_page(this,'.$cid.','.$user_id.','.$type.','.$count.');"';
				$data['pagelink'] = $this->input['id']?hg_build_link('' , array('id' => $this->input['id'])):'';
				$showpages = hg_build_pagelinks($data);
			}
			
			$station = $this->mVideo->get_user_station($user_id);
			
			$program = $this->mVideo->video_program($video_id,0,3);
			if(is_array($program))
			{
				$program_total = $program['total'];
				unset($program['total']);
			}
			
			$video =  $this->mVideo->video_tags_search($video_id, 0, 8);
			if(is_array($video))
			{
				unset($video['total']);
			}
			
			include_once(ROOT_PATH . 'lib/class/groups.class.php');
			$this->group = new Group();
			$group = $this->group->get_my_groups($user_id);
			
			$n_user_id = intval(trim($this->user['id']));
			$station = $this->mVideo->get_user_station($n_user_id);
			$n_sta_id  = intval(trim($station['id']));
			$toff  = $single_video_info['toff'];
			$program_all = $this->mVideo->get_station_program($n_sta_id,$n_user_id);
			$start_time = 0;
			if(is_array($program_all))
			{
				$start_time = $program_all[count($program_all)-1]['end_time'];
			}

			$this->page_title = $single_video_info['title']."视频_视频信息";
			$this->keywords = $single_video_info['title'];
			$this->description = $single_video_info['title'];
			hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'comment.js');
//			hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'tvieplayer_new.js');
			hg_add_head_element('js', 'http://video.hcrt.cn/flash-player/swfobject.js');
			hg_add_head_element('js', 'http://video.hcrt.cn/flash-player/tvieplayer.js');

			/*start---视频广告*/
			if(defined('ADVERT_XML'))
			{
				$xml = simplexml_load_file(ADVERT_XML,null,LIBXML_NOCDATA); 
				$prePlayAd = $xml->item->prePlayAd;
				$prePlayAd_url = $xml->item->prePlayAd_url;
				$onPauseAd = $xml->item->onPauseAd;
				$onPauseAd_url = $xml->item->onPauseAd_url;
				$onEndAd = $xml->item->onEndAd;
				$onEndAd_url = $xml->item->onEndAd_url;
				$watermark = $xml->item->watermark;
			}
			/*end-----视频广告*/
			$js = '
           video_address="' . $single_video_info['streaming_media'] . '";
           video_title="' . $single_video_info['title'] . '";
           video_schematic="' . $single_video_info['schematic'] . '";
			function lightOnHanle() { lamps(2,1);}
           function lightOffHandle() { lamps(2,0);}
            function playComplete() {}
            var resource = "http://video.hcrt.cn/flash-player/";
            var width = 530;
            var height = 462;
            var tvie = new TViePlayer("player", resource+"CBNVodSkin.swf", width, height);
            tvie.videoScaleMode = "original";
			tvie.bgcolor="#000";
            //tvie.forceBase64 = true;
            tvie.loader = resource+"Loader.swf";
            tvie.player = resource+"Player.swf";
            tvie.setTVieVod("video.hcrt.cn", "' . $single_video_info['streaming_media'] . '");
            tvie.setJSCallback("playComplete", "lightOnHanle", "lightOffHandle");
            var adLoader = resource+"MockADS/TVieADLoader.swf";
            var layout = {layout:"float",vertical:"middle",horizen:"center"};
            
            tvie.addPlugin(resource+"TVieNotice.swf", { mode: "isolate" }, {layout:"float",x:105,y:420}, ["api","http://www.hoolo.tv/dealfunc/notice.php","width",300,"height",20,"refreshInterval",3]);
            tvie.addPlugin(resource+"TVieVideoList2.swf",{mode:"embeded",type:"onEndAd"}, {layout:"float",x:2,y:0}, ["api","http://www.hoolo.tv/dealfunc/videoList.php","id","any"],null,true,false);
            tvie.addPlugin(resource+"MockADS/TVieADLoader.swf",{mode:"isolate"},{layout:"float",vertical:"top",horizen:"right",x:-20,y:10},["url","' . $watermark . '","width",115,"height",57],null,true,null,null);
			tvie.addPlugin(resource+"TVieFacade.swf", { mode: "embeded",type:"facade" },{layout:"float",x:0,y:0},["adapter","yi_chuan_mei","ad_player","http://static.acs86.com/FrameWork/AFP/AFP_1031.swf","width",540,"height",455,"id",2460,"keywords",""]);			
 			tvie.run();
			';
//tvie.addPlugin(resource+"TVieFacade.swf", { mode: "embeded",type:"facade" },{layout:"float",x:0,y:0},["adapter","yi_chuan_mei","ad_player","http://static.acs86.com/FrameWork/AFP/AFP_1031.swf","width",540,"height",455,"id",2460,"keywords",""]);
			hg_add_head_element('js-c', $js);
			hg_add_head_element('js',RESOURCE_DIR . 'scripts/' .  'share.js');
			hg_add_head_element('js',RESOURCE_DIR . 'scripts/' .  'my.js');
			hg_add_head_element('css',RESOURCE_DIR . 'css/' .  'color_open.css','id="lamp" ');
			$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			include_once(ROOT_PATH . 'lib/class/shorturl.class.php');
			$shorturl = new shorturl($url);
			$url = $shorturl->shorturl($url);
			$url = urldecode($url); 
			$gScriptName = SCRIPTNAME;
			
			$this->tpl->addVar('head_line', 0);
		
			$this->tpl->addVar('single_video_info', $single_video_info);
			$this->tpl->addVar('user_id', $user_id);
			$this->tpl->addVar('username', $username);
			$this->tpl->addVar('cid', $cid);
			$this->tpl->addVar('vip_url', $vip_url);
			$this->tpl->addVar('visit_total', $visit_total);
			$this->tpl->addVar('video_id', $video_id);			
			$this->tpl->addVar('relation', $relation);
			$this->tpl->addVar('total_nums', $total_nums);
			$this->tpl->addVar('comment_list', $comment_list);
			$this->tpl->addVar('visit', $visit);
			$this->tpl->addVar('program', $program);
			$this->tpl->addVar('program_total', $program_total);
			$this->tpl->addVar('video', $video);
			$this->tpl->addVar('station', $station);
			$this->tpl->addVar('n_sta_id', $n_sta_id);
			$this->tpl->addVar('n_user_id', $n_user_id);
			$this->tpl->addVar('toff', $toff);
			$this->tpl->addVar('showpages', $showpages);
			$this->tpl->addVar('url', $url);	
			$this->tpl->addVar('gScriptName', $gScriptName);
			$this->tpl->addVar('play', 1);
			$this->tpl->addVar('gKeywords', $this->keywords);
			$this->tpl->addVar('gDescription', $this->description);
			
			$this->tpl->addHeaderCode(hg_add_head_element('echo'));
			$this->tpl->setTemplateTitle($this->page_title);
			$this->tpl->outTemplate('video_play');
		}
		else
		{
			//报错
		} 			
	}
}

$out = new videoPlay();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'play';
}
$out->$action();


?>