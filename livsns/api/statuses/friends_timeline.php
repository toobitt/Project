<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: friends_timeline.php 17941 2013-02-26 02:20:49Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
class friends_timeline extends appCommonFrm
{
	var $trans = array();
	var $total = array();
	function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . "lib/mblog.class.php");
		$this->obj = new mblog();
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	/**
	 * 返回最新的20条公共点滴
	 * @param count  每次返回的记录数   缺省值20，最大值200 
	 * @return xml/json 用户信息
	 */
	public function getTimeline()
	{	
		$userinfo = array();
		$usrinfoall = array();
		$ids = array();
		$statusids = array();
		//验证用户是否登录
		require_once(ROOT_DIR.'lib/user/user.class.php');
		$this->user = new user();
		$userinfo = $this->user->verify_credentials(); 
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		//$userinfo['id'] = 2;
		//$this->input['gettoal']='gettotal';
		//获取用户参数
		//判断是否需要取总数
		
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		elseif ($this->input['count'] > 200)
		{
			$this->input['count'] = 200;
		}
		$count = intval($this->input['count']);
		$page = intval($this->input['page']);
		//取总数时需传入数值gettotal
		$gettotal = trim($this->input['gettoal']);
		$offset = $page * $count;
		
		//取得本人和所关注人的点滴id
		include_once(ROOT_DIR . 'lib/class/push.class.php');
		$push = new push();
		//取得本人和所关注人的点滴总数
		if($gettotal)
		{
			$total = $push->getuserinbox($userinfo['id'],$page,$count,$gettotal);
		}

		$stat = $push->getuserinbox($userinfo['id'],$page,$count,'');
		if (!$stat)
		{
			//退出
			return false;
		}
		foreach ($stat as $key =>$values)
		{
			$statusids[] = $values ['status_id'];
		}
		
		//取得本人和所关注人的点滴信息					
		$all = $this->getblog($statusids,1);
		//取得本人相关的媒体信息
		if($statusids)
		{
			$media = $this->obj->getMedia($statusids);
		}
		//取得转发我的点滴信息					
		if(count($this->trans))
		{
			$alltran = $this->getblog($this->trans,2);
			$mediatran = $this->obj->getMedia($this->trans);
		}
		//博客用户信息和转发用户信息合并
		$this->setXmlNode('statuses','status');
		if($total)
		{
			$this->addItem($total['0']);
		}
		foreach ($all as $key =>$values)
		{		
			$alltran[$values['reply_status_id']]['medias'] = $mediatran[$values['reply_status_id']];
			$values['retweeted_status'] = $alltran[$values['reply_status_id']];
			$values['medias'] = $media[$values['id']];
			$this->addItem($values);
		}
		$this->output();
	}
	
	public function getblog($ids,$flag)
	{
		include ('getblog.php');
		return $all;
	}
	
	/**
	 * 入口
	 */
	public function show()
	{
		$this->getTimeline();		
	}
}
$out = new friends_timeline();
$out->show();
?>