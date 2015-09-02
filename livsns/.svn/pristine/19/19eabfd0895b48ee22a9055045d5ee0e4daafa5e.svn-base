<?php
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/content.class.php';
define('MOD_UNIQUEID', 'news');  //模块标识
define('APP_UNIQUEID','company');
class hogeCloud extends appCommonFrm
{
	private $api;
	public function __construct()
	{
		parent::__construct();
		$this->api = new content();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}

	public function detail()
	{
		$video_id = intval($this->input['video_id']);
		$data_arr = array(
			'video_id' => $video_id,
		);
		$info = $this->api->detail('hogecloud_callback', $data_arr);
		if($info)
		{
			$this->addItem($info);
		}
		$this->output();
	}
	
	/**
	 * 刚上传视频时，将得到的video_id和title加入到数据中
	 */
	public function addVideoId()
	{
		$title = trim($this->input['title']);
		$video_id = intval($this->input['video_id']);
		$data = array(
				'title' => $title,
				'video_id' => $video_id,
		);
		$ret = $this->api->create('hogecloud_callback', $data);
		$this->addItem($ret);
		$this->output();
	}
	
	
	/**
	 * 厚建云视频上传后记录回调数据
	 */
	public function create()
	{
		$title = trim($this->input['title']);
		$subtitle = trim($this->input['subtitle']);
		$chain_m3u8 = trim($this->input['chain_m3u8']);
		$keywords = trim($this->input['keywords']);
		$index_pic = trim($this->input['index_pic']);
		$comment = trim($this->input['comment']);
		$author = trim($this->input['author']);
		$vod_sort_id = trim($this->input['vod_sort_id']);		
		$duration = trim($this->input['duration']);
		$bitrate = intval($this->input['bitrate']);
		$video_id = intval($this->input['video_id']);
		
		//先判断有没有此数据
		$temp_arr = array(
			'video_id' => $video_id,		
		);
		$one_detail = $this->api->detail('hogecloud_callback', $temp_arr);
		
		if(is_array($one_detail) && $one_detail)
		{
			$data = array(
					'subtitle' => $subtitle,
					'chain_m3u8' => $chain_m3u8,
					'keywords' => $keywords,
					'index_pic' => $index_pic,
					'comment' => $comment,
					'author' => $author,
					'vod_sort_id' => $vod_sort_id,
					'duration' => $duration,
					'bitrate' => $bitrate,
			);
			$idsarr = array(
				'video_id' => $video_id,	
			);
			$this->api->update('hogecloud_callback', $data, $idsarr);
		}
		else
		{
			$data = array(
					'title' => $title,
					'subtitle' => $subtitle,
					'chain_m3u8' => $chain_m3u8,
					'keywords' => $keywords,
					'index_pic' => $index_pic,
					'comment' => $comment,
					'author' => $author,
					'vod_sort_id' => $vod_sort_id,
					'duration' => $duration,
					'bitrate' => $bitrate,
					'video_id' => $video_id,
			);
			$ret = $this->api->create('hogecloud_callback', $data);
		}	
		$this->addItem($ret);
		$this->output();		
	}
}
$out = new hogeCloud();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();