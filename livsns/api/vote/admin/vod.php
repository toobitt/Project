<?php
/***************************************************************************
* $Id: vod.php 23128 2013-06-04 06:41:40Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','vod');
require('global.php');
class vodApi extends adminReadBase
{
	private $mLivMedia;
	public function __construct()
	{
		parent::__construct();
		require_once ROOT_PATH . 'lib/class/livmedia.class.php';
		$this->mLivMedia = new livmedia();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index()
	{
	}

	public function show()
	{
	}
	
	public function detail()
	{
		
	}
	
	public function count()
	{
	}
	
	/**
	 * 取视频库信息
	 * $offset 分页参数
	 * $count 分页参数
	 * $vod_sort_id 视频分类
	 * $pp 分页参数
	 * $title 标题
	 * $date_search 日期
	 * Enter description here ...
	 */
	public function get_vod_info()
	{
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['counts'] ? intval($this->input['counts']) : 20;
		$offset = intval(($pp - 1)*$count);			
		$vod_sort_id = intval($this->input['vod_sort_id']);
		
		$vod_data = array(
			'offset'	  => $offset,
			'count'		  => $count,
			'vod_sort_id' => $vod_sort_id,
			'pp'		  => $pp,
			'k'	      	  => trim($this->input['title']),
			'date_search' => trim($this->input['date_search']),
		);
		
		$return = array();
		$ret_vod = $this->mLivMedia->getVodInfo($vod_data);
		$return['video'] = $ret_vod;
		
		$ret_page = $this->mLivMedia->getPageData($vod_data);
		
		$return['page'] = $ret_page;
		$return['date_search'] = $this->settings['date_search'];
		
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 取视频库节点
	 * $fid 父级id
	 * Enter description here ...
	 */
	public function get_vod_node()
	{
		$fid = intval($this->input['fid']);
		
		$return = $this->mLivMedia->getVodNode($fid);
		
		$this->addItem($return);
		$this->output();
	}
	
}

$out = new vodApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>