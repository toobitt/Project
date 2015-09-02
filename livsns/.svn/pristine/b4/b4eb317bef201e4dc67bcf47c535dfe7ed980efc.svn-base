<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 13202 2012-10-27 12:32:11Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','video');//模块标识
require('global.php');
class videoUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/video.class.php');
		$this->obj = new video();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->obj);
	}

	public function create()
	{	
		if(empty($this->input['url']))
		{
			$this->errorOutput("请传入视频url！");
		}
		$ret = $this->obj->create();
		if(empty($ret))
		{
			$this->errorOutput("视频上传有误！");
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		if(empty($this->input['url']))
		{
			$this->errorOutput("请传入视频url！");
		}
		if(empty($this->input['id']))
		{
			$this->errorOutput("请传入视频id！");
		}
		$ret = $this->obj->update();
		if(empty($ret))
		{
			$this->errorOutput("视频更新有误！");
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function delete()
	{
		$id = $this->input['sid'] ? intval($this->input['sid']) : 0 ;
		$source = $this->input['source'] ? trim($this->input['source']) : 0 ;
		if(empty($id))
		{
			$this->errorOutput("请传入视频id！");
		}
		if(empty($source))
		{
			$this->errorOutput("请传入来源！");
		}
		$ret = $this->obj->delete($id,$source);
		$this->addItem($ret);
		$this->output();
	}
	
	public function audit()
	{
		
	}
	
	public function publish()
	{
		
	}
	
	public function sort()
	{
		
	}
	
	public function video_parseurl()
	{
		include_once(ROOT_PATH . 'lib/class/videoUrlParser.class.php');
		$video = new VideoUrlParser();
		$url = urldecode($this->input['url']);
		$result = $video->parse($url);
		$this->addItem($result);
		$this->output();		
	}

	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new videoUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>	