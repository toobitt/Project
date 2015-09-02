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
class videoApi extends outerReadBase
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

	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;	
				
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->obj->show($condition . $data_limit);
		foreach($ret as $k => $v)
		{
			$this->addItem($v);
		}
		
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 检索条件 关键字，时间，状态,标题，发布时间，图片，附件，视频
	 * @name get_condition
	 * @access private
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		if($this->input['source'])
		{
			$condition .= " AND source='" . trim($this->input['source']) . "'";
		}
		if($this->input['sid'])
		{
			$condition .= " AND sid IN(" . trim($this->input['sid']) . ")";
		}
		return $condition;	
	}


	/**
	 * 显示单篇文章 文章ID不存在默认为最新第一条
	 * @name detail
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param int $id 文章ID
	 * @return $info array 新闻内容
	 */
	public function detail()
	{
		if($this->input['id'])
		{
			$data_limit = ' AND id=' . intval($this->input['id']);
		}
		else
		{
			$data_limit = ' LIMIT 1';
		}		
		$ret = $this->obj->detail($data_limit);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('查询失败');
		}
	}
	
	/**
	 * 获取视频信息
	 */
	public function video_info()
	{
		$sid = trim(urldecode($this->input['sid']));
		$source = trim(urldecode($this->input['source']));
		$video_info = $this->obj->get_video_info($source, $sid);
		foreach ($video_info as $video)
		{
			$this->addItem_withkey($video['sid'], $video);
		}
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new videoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>	