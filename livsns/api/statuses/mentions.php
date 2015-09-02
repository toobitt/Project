<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: mentions.php 17941 2013-02-26 02:20:49Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require_once (ROOT_DIR . 'global.php');
class mentions extends appCommonFrm
{
	var $trans = array();
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
	public function mentions()
	{	
		$userinfo = array();
		$ids = array();
		$statusids = array();
		$total =array();
		$media = array();
		//验证用户是否登录
		require_once(ROOT_DIR.'lib/user/user.class.php');
		$this->user = new user();
		$userinfo = $this->user->verify_credentials(); 
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		//$userinfo['id'] = 1;
		//$this->input['gettoal']='gettotal';
		//获取用户参数
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		elseif ($this->input['count'] > 200)
		{
			$this->input['count'] = 200;
		}
		//取总数时需传入数值gettotal
		$gettotal = trim($this->input['gettoal']);
		$count = intval($this->input['count']);
		$page = intval($this->input['page']);
		$offset = $page * $count;
		//取总数
		if($gettotal == 'gettotal')
		{
			$sql = "SELECT count(status_id) as total FROM ".DB_PREFIX."status_member WHERE member_id =".$userinfo['id'];
			$total = $this->db->query_first($sql);
		}
		
		//取得提到我的这些用户的点滴id
		$sql = "SELECT status_id FROM ".DB_PREFIX."status_member where member_id =".$userinfo['id']." ORDER BY status_id DESC limit $offset , $count";
		$result = $this->db->query($sql);			
		while($row = $this->db->fetch_array($result))
		{		
			$ids[] = $row['status_id'];	
		}
		//取得提到我的这些用户的点滴信息					
		$all = $this->getblog($ids,1);
		//取得本人相关的媒体信息
		if($ids)
		{
			$media = $this->obj->getMedia($ids);
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
			$this->addItem($total);
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
		
		$this->mentions();
		
	}
}
$out = new mentions();
$out->show();
?>