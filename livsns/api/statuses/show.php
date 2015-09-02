<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: show.php 17941 2013-02-26 02:20:49Z repheal $
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
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	/*根据ID获取单条点滴信息内容 */
	public function getTimeline()
	{
		//$this->input['id'] = 358;
		
		require_once(ROOT_DIR.'lib/class/member.class.php');
		$this->member = new member();
		//验证用户是否登录
		//$userinfo = $this->user->verify_credentials();
		if(!$this->user['user_id'])
		{
			//$this->errorOutput(USENAME_NOLOGIN);
		}
		
		//获取用户参数
		if(!$this->input['id'])
		{
			return ;
		}
		//取得单条的点滴信息	
		//$statusids[] = $this->input['id'];
		//取单条或者取多条点滴信息
		$statusids = explode(',',urldecode($this->input['id']));		
		$all = $this->getblog($statusids,1);
		//取得转发我的点滴信息
		if(count($this->trans))
		{
			$alltran = $this->getblog($this->trans,2);
			$mediatran = $this->obj->getMedia($this->trans);
		}					
		//博客用户信息和转发用户信息合并
		$this->setXmlNode('statuses','status');
		$media = array();
		$media = $this->obj->getMedia($statusids);
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

	public function show()
	{
		$this->getTimeline($this->input['count']);
		
	}
}
$out = new timeline();
$out->show();
?>