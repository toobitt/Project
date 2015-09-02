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
define('MOD_UNIQUEID','visit');//模块标识
require('global.php');
class visitApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/visit.class.php');
		$this->obj = new visit();
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
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;	
				
		$data_limit = ' ORDER BY visit_time DESC LIMIT ' . $offset . ' , ' . $count;
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
		if($this->input['cid'])
		{
			$condition .= " AND cid=" . intval($this->input['cid']);
		}
		$user_id = $this->input['user_id'] ? intval($this->input['user_id']) : $this->user['user_id'];
		if($user_id)
		{
			$condition .= " AND user_id=" . $user_id;
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
			$data_limit = ' and id=' . intval($this->input['id']);
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
	
	public function getOftenById()
	{
		$member_id = intval($this->input['member_id']);
		if(empty($member_id))
		{
			$this->errorOutput('未传入用户ID');
		}
		$source = $this->input['source'];
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;	
				
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->obj->getOftenById($member_id,$source,$data_limit);
		if(!empty($ret))
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new visitApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>	