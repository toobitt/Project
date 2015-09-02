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
define('MOD_UNIQUEID','recommond');//模块标识
require('global.php');
class columnApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/recommond.class.php');
		$this->obj = new recommond();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->obj);
	}
	
	public function index()
	{
		
	}
	
	public function show()
	{
		$ret = $this->obj->show_column();
		foreach($ret as $k => $v)
		{
			$this->addItem($v);
		}
		
		$this->output();
	}
	
	public function recommond_show()
	{
		$ret = $this->obj->show_column();
		$return['ret'] = $ret;
		$return['aid'] = $this->input['aid'];
		$return['source'] = $this->input['source'];
		$return['title'] = $this->input['title'];
		$this->addItem($return);
		$this->output();
	}
		
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count_column($condition);
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
			$data_limit = ' and id=' . intval($this->input['id']);
		}
		else
		{
			$data_limit = ' LIMIT 1';
		}		
		$ret = $this->obj->detail_column($data_limit);
		$this->addItem($ret);
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new columnApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>	