<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: station_play.php 6176 2012-03-23 06:54:28Z repheal $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'channel');
class stationPlay extends uiBaseFrm
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
	
	public function show()
	{		
		$sta_id  = intval(trim($this->input['sta_id']));
		$n_user_id = intval(trim($this->user['id']));
		$n_station = $this->mVideo->get_user_station($n_user_id);
		$n_sta_id  = intval(trim($n_station['id']));
		$station = $this->mVideo->get_station($sta_id);
		$station = $station[0];
		
		$user_id = intval(trim($station['user_id']));
		$username = $station['user']['username'];
		$vip_url = $station['user']['vip_url'] ? $station['user']['vip_url'] : ''; 
		
		if($sta_id&&$user_id)
		{
			$program = $this->mVideo->get_station_programe($sta_id,$user_id);
//			hg_pre($program);
			$info_id = array();
			$info = array();
			$js = 'var gProgramIds = new Array();
				var gIndexs = new Array();
				var gVideoIds = new Array();
				var gTitles = new Array();
				var gMedias = new Array();
				var gSchematic = new Array();
				var gToffs = new Array();
				var gBriefs = new Array();
				var gRelations = new Array();
				var user_id = '.$this->user['id'].';
				var sta_id = '.$n_sta_id.';
				var gLastProgram = 0;
				';
			if($program && is_array($program))
			{
				$skey = 0;
				foreach($program as $key => $value)
				{
					if(!$key)
					{
						$js .= "var gFirstProgram = ".$value['id'].";";
					}
					$js .= 'gIndexs[' . $value['id'] . '] = ' . $key . ';';
					$js .= 'gProgramIds[' . $key . '] = ' . $value['id'] . ';';
					
					$js .= "gVideoIds[{$value['id']}] = '{$value['video']['id']}';";
					$js .= "gTitles[{$value['id']}] = \"".addslashes($value['video']['title'])."\";";
					$js .= "gMedias[{$value['id']}] = '{$value['video']['streaming_media']}';";
					$js .= "gSchematic[{$value['id']}] = '{$value['video']['schematic']}';";
					$js .= "gToffs[{$value['id']}] = '{$value['video']['toff']}';";
					//$js .= "gBriefs[{$value['id']}] = '{$value['video']['brief']}';";
					$js .= "gRelations[{$value['id']}] = '{$value['video']['relation']}';";
					$skey = $key;
				}
				$js .= "gLastProgram = ".$skey.";";
			}
		
			/*include_once(ROOT_PATH . 'lib/class/groups.class.php');
			$this->group = new Group();
			$group = $this->group->get_my_groups($user_id);*/
			
			if($this->user['id']==$user_id)
			{
				$is_my_page = 1;
			}
			else 
			{
				$is_my_page = 0;
			}
	
			
			$this->mVideo->update_click_count($sta_id);
			if(!$is_my_page&&$this->user['id'])
			{
				$this->mVideo->create_visit_history($user_id,$sta_id,2);
			}
			$visit = $this->mVideo->get_visit_history($sta_id,$type=2,$page=0,$count=10);
			
			$type = 1;
			$state = 1; //评论状态，0-待审核，1-已审核通过
			$cid = $sta_id;
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
				$data['pagelink'] = hg_build_link('' , array('user_id' => $this->input['user_id'],'sta_id' => $this->input['sta_id']));
				$showpages = hg_build_pagelinks($data);
			}
			
			$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			include_once(ROOT_PATH . 'lib/class/shorturl.class.php');
			$shorturl = new shorturl($url);
			$url = $shorturl->shorturl($url);
			$url = urldecode($url);
			
			$this->page_title = $station['web_station_name'] . '视频_原创视频';
			$this->keywords = $station['web_station_name'];
			$this->description = $station['web_station_name'];
			hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'comment.js');
//			hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'tvieplayer_new.js');
//			hg_add_head_element('js', 'http://liveapi.hcrt.cn/flash-player_r9510/swfobject.js');
//			hg_add_head_element('js', 'http://liveapi.hcrt.cn/flash-player_r9510/tvieplayer.js');
			
			hg_add_head_element('js', 'http://video.hcrt.cn/flash-player/swfobject.js');
			hg_add_head_element('js', 'http://video.hcrt.cn/flash-player/tvieplayer.js');
			hg_add_head_element('js',RESOURCE_DIR . 'scripts/' .  'share.js');
			hg_add_head_element('js',RESOURCE_DIR . 'scripts/' .  'station.js');
			hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'my.js');
			hg_add_head_element('css',RESOURCE_DIR . 'css/' .  'color_open.css','id="lamp" ');
			
			hg_add_head_element('js-c',"{$js}");
			hg_add_head_element('js-c','var video_address="";video_title="";');
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
			}
			/*end-----视频广告*/
			hg_add_head_element('js-c','
				REQUEST_URI = "'.$_SERVER['REQUEST_URI'].'";
				REQUEST_URL = "'.$url.'";
				U_REQUEST_URI = "'.urlencode($_SERVER['REQUEST_URI']).'";
				WB_AKEY = "'.WB_AKEY.'";			
				WEB_SITE_NAME = "'.WEB_SITE_NAME.'";
          		var prePlayAd = "'.$prePlayAd.'";
          		var prePlayAd_url = "'.$prePlayAd_url.'";
          		var onPauseAd = "'.$onPauseAd.'";
          		var onPauseAd_url = "'.$onPauseAd_url.'";
          		var onEndAd = "'.$onEndAd.'";
          		var onEndAd_url = "'.$onEndAd_url.'";
			');
			

			$_mBodyCode = ' onload="timing();"';
			$gScriptName = SCRIPTNAME;
			$this->tpl->addVar('head_line', 0);
			$this->tpl->addVar('_mBodyCode', $_mBodyCode);
			$this->tpl->addVar('station', $station);
			$this->tpl->addVar('n_sta_id', $n_sta_id);
			$this->tpl->addVar('n_station', $n_station);
			$this->tpl->addVar('n_user_id', $n_user_id);
			$this->tpl->addVar('sta_id', $sta_id);
			$this->tpl->addVar('username', $username);
			$this->tpl->addVar('vip_url', $vip_url);
			$this->tpl->addVar('program', $program);
			$this->tpl->addVar('is_my_page', $is_my_page);
			$this->tpl->addVar('visit', $visit);
			$this->tpl->addVar('user_id', $user_id);
			$this->tpl->addVar('comment_list', $comment_list);
			$this->tpl->addVar('total_nums', $total_nums);
			$this->tpl->addVar('showpages', $showpages);
			$this->tpl->addVar('url', $url);	
			$this->tpl->addVar('gScriptName', $gScriptName);
			$this->tpl->addVar('cid', $cid);
			$this->tpl->addVar('video_id', 0);
			$this->tpl->addVar('toff', 0);		
			$this->tpl->addVar('type', $type);
			$this->tpl->addVar('play', 1);
			$this->tpl->addVar('gKeywords', $this->keywords);
			$this->tpl->addVar('gDescription', $this->description);
			
			$this->tpl->addHeaderCode(hg_add_head_element('echo'));
			$this->tpl->setTemplateTitle($this->page_title);
			$this->tpl->outTemplate('station_play');	
		}
		else
		{
			header("Location:index.php");
		}			
	}

	
	public function play_list()
	{
		$sta_id  = intval(trim($this->input['sta_id']));
		$user_id = intval(trim($this->input['user_id']));
		$n_user_id = intval(trim($this->user['id']));
		$station = $this->mVideo->get_user_station($n_user_id);
		$n_sta_id  = intval(trim($station['id']));
		
		$num = intval(trim($this->input['num']));
		$info = array(
			"video" => 0,
			"u_video" => 0,
			"video_id" => 0,
			"video_toff" => 0,
			"video_name" => 0,
			"schematic" => 0,
			"sta_id" => $sta_id,
			"n_sta_id" => $n_sta_id,
			"user_id" => $user_id,
			"relation" => 0,
			"uid"=>0,
		);
		if($sta_id&&$user_id)
		{
			$program = $this->mVideo->get_station_program($sta_id,$user_id);
			$info['total'] =  count($program);
			if(is_array($program))
			{
				if($num < count($program))
				{
					$info['video'] =  $program[$num]['video']['streaming_media'];
					$info['u_video'] =  urlencode($program[$num]['video']['streaming_media']);
					$info['video_id'] = $program[$num]['video']['id'];
					$info['video_toff'] = $program[$num]['video']['toff'];
					$info['video_name'] = $program[$num]['video']['title'];
					$info['video_briefs'] = $program[$num]['video']['brief'];
					$info['schematic'] = $program[$num]['video']['schematic'];
					$info['relation'] = $program[$num]['video']['relation'];
					$info['uid'] = $this->user['id'];
					$info['num'] = $num + 1;
				}
			}
		}
		
		ob_end_clean();
		echo json_encode($info);
		exit;
	}
	
}

$out = new stationPlay();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();


?>