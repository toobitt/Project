<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: favorites.php 2774 2011-03-15 06:58:54Z wang $
***************************************************************************/
define('ROOT_DIR', '../../');
require_once (ROOT_DIR . 'global.php');
class favorites extends BaseFrm
{
	var $total = array();
	var $trans = array();
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	/**
	 * @param count  每次返回的记录数   缺省值20，最大值200 
	 * @return xml/json 用户信息
	 */
	public function favorites()
	{	
		$userinfo = array();
		$this->total = array();
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
		//根据权限查看别人收藏信息
		/*if($this->input['id'])
		{
			$userinfo['id'] = $this->input['id'];
		}*/
		//$userinfo['id'] = 3;
		$this->input['gettoal']='gettotal';
		//获取用户参数
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
		//取总数
		if($gettotal == 'gettotal')
		{
			//取得我收藏的点滴id
			$sql = "SELECT count(status_id) as total FROM ".DB_PREFIX."status_favorites where member_id =".$userinfo['id'];
			$this->total = $this->db->query_first($sql);
		}
		//取得我收藏的点滴id
		$sql = "SELECT status_id FROM ".DB_PREFIX."status_favorites where member_id =".$userinfo['id']." ORDER BY status_id DESC limit $offset , $count";
		$result = $this->db->query($sql);			
		while($row = $this->db->fetch_array($result))
		{		
			$ids[] = $row['status_id'];	
		}
		//取得当前用户收藏到点滴信息			
		$all = $this->getblog($ids);
		//取得本人相关的媒体信息
		$media = $this ->getMedia($statusids);
		//取得转发我的点滴信息					
		if(count($this->trans))
		{
			$alltran = $this->getblog($this->trans);
			$mediatran = $this ->getMedia($this->trans);
		}
		

		//博客用户信息和转发用户信息合并
		$this->setXmlNode('statuses','status');
		if($this->total)
		{
			$this->addItem($this->total);
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
	public function getblog($ids)
	{
		if(!$ids)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sta = implode(',',$ids);
		//取得我收藏点滴信息
		//$sql = "SELECT * FROM ".DB_PREFIX."status where id in($sta) AND status=0";
		$sql = "SELECT sta.* , exl.transmit_count,exl.reply_count,exl.comment_count FROM ".DB_PREFIX.
		"status sta  LEFT JOIN ".DB_PREFIX."status_extra exl ON sta.id = exl.status_id where sta.id in($sta) AND sta.status=0 ORDER BY sta.id DESC";
		$result = $this->db->query($sql);
		if (!$this->db->num_rows($result))
		{
			//退出
			return ;
		}
		$members = $trans = array();
		while($row = $this->db->fetch_array($result))
		{		
			$members[] = $row['member_id'];
			if($row['reply_status_id'])
			{
				$this->trans[] = $row['reply_status_id'];
			}
			//格式化时间
			$row['create_at'] = date("Y-m-d H:i:s",$row['create_at']);
			$blog[] = $row;
		}
		//取得对应的用户信息
		$members = implode(',',$members);
		$members = $this->user->getUserById($members);
		//对应user的键值
		foreach ($members as $key => $values)
		{
			$mem[$values['id']] = $values;
		}
		//博客信息和用户信息合并
		$this->setXmlNode('statuses','status');
		foreach ($blog as $key =>$values)
		{
			$values['user'] = $mem[$values['member_id']];		
			$all[$values['id']]=$values;
		}
		return $all;

	}
	
	public function getMedia($id)
	{
		include_once(ROOT_DIR . 'lib/class/status.class.php');
		$status = new status();
		if(is_array($id))
		{
			$ids = implode(",", $id);
		}
		else 
		{
			$ids = $id;			
		}	
		return $status->getMediaByStatusId($ids);
	}
	/**
	 * 入口
	 */
	public function show()
	{
		
		$this->favorites();
		
	}
	
	
}
$out = new favorites();
$out->show();
?>