<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: public_timeline.php 17941 2013-02-26 02:20:49Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
class timeline extends appCommonFrm
{
	var $trans = array();
	function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . "lib/mblog.class.php");
		$this->obj = new mblog();
		include_once(ROOT_DIR . '/lib/class/member.class.php');
		$this->member = new member(); 
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	/*获取最新的公共微薄消息*/
	public function getTimeline()
	{
		$userinfo = array();
		$media = array();
		//获取用户参数
		if(!$this->input['count'])
		{
			$this->input['count'] =  50;
		}
		elseif ($this->input['count'] > 200)
		{
			$this->input['count'] = 200;
		}
		$count = intval($this->input['count']);
		$page = intval($this->input['page']);
		$offset = $page * $count;
		
		//取最近的博客信息
		$this->end = "limit $offset , $count";
		$statusids = array(0 => 'recent');
		$all = $this->getblog($statusids,1);		
		//取得本人相关的媒体信息
		foreach($all as $key => $value)
		{
			$statusid .= $value['id'].","; 
		}
		$statusid = rtrim($statusid,",");	
		if($statusid)
		{
			$media = $this->obj->getMedia($statusid);
		}
		
		//取得转发我的点滴信息
		if(count($this->trans))
		{
			$alltran = $this->getblog($this->trans,2);
			$mediatran = $this->obj->getMedia($this->trans);
		}					
		//博客用户信息和转发用户信息合并
		$this->setXmlNode('statuses','status');
		foreach ($all as $key =>$values)
		{
			$alltran[$values['reply_status_id']]['medias'] = $mediatran[$values['reply_status_id']];
			$values['retweeted_status'] = $alltran[$values['reply_status_id']];
			$values['is_self'] = 0;
			if($this->user['user_id'] == $values['member_id'])
			{
				$values['is_self'] = 1;
			}
			$values['medias'] = $media[$values['id']];
			$this->addItem($values);
		}
		$this->output();		
	}
	
	public function getblog($ids,$flag)
	{
		include('getblog.php');
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
$out = new timeline();
$out->show();
?>