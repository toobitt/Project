<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: favorites.php 2774 2011-03-15 06:58:54Z wang $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require_once (ROOT_DIR . 'global.php');
class favoritesApi extends appCommonFrm
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
	
	public function show()
	{	
		$ids = array();
		$statusids = array();
		
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$page = $this->input['page'] ? intval($this->input['page']) : 0;
		$offset = $page * $count;

		//取得我收藏的点滴id
		$sql = "SELECT status_id FROM ".DB_PREFIX."status_favorites where member_id =" . $this->user['user_id'] . " ORDER BY status_id DESC limit $offset , $count";
		$result = $this->db->query($sql);			
		while($row = $this->db->fetch_array($result))
		{		
			$ids[] = $row['status_id'];	
		}
		
		//取得当前用户收藏到点滴信息			
		$all = $this->getblog($ids);
		//取得本人相关的媒体信息
		$media = $this->getMedia($statusids);
		//取得转发我的点滴信息					
		if(count($this->trans))
		{
			$alltran = $this->getblog($this->trans);
			$mediatran = $this->getMedia($this->trans);
		}
		
		//博客用户信息和转发用户信息合并
		$this->setXmlNode('statuses','status');
		foreach ($all as $key =>$values)
		{		
			$alltran[$values['reply_status_id']]['medias'] = $mediatran[$values['reply_status_id']];
			$values['retweeted_status'] = $alltran[$values['reply_status_id']];
			$values['medias'] = $media[$values['id']];
			$this->addItem($values);
		}
		$this->output();
	}
	
	public function count()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$sql = "SELECT count(status_id) as total FROM ".DB_PREFIX."status_favorites where member_id =" . $this->user['user_id'];
		$total = $this->db->query_first($sql);
		$this->addItem($total);
		$this->output();
	}
	
	private function getblog($ids)
	{
		if(!$ids)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sta = implode(',',$ids);
		//取得我收藏点滴信息
		//$sql = "SELECT * FROM ".DB_PREFIX."status where id in($sta) AND status=0";
		$sql = "SELECT sta.* , exl.transmit_count,exl.reply_count,exl.comment_count FROM " . DB_PREFIX . 
		"status sta  LEFT JOIN ".DB_PREFIX."status_extra exl ON sta.id = exl.status_id where sta.id in($sta) AND sta.status=0 ORDER BY sta.id DESC";
		$result = $this->db->query($sql);
		if (!$this->db->num_rows($result))
		{
			return false;
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
		include_once(ROOT_DIR . 'lib/class/member.class.php');
		$this->member = new member();
		$members = $this->member->getMemberById($members);
		
		//对应user的键值
		foreach ($members as $key => $values)
		{
			$mem[$values['id']] = $values;
		}

		foreach ($blog as $key =>$values)
		{
			$values['user'] = $mem[$values['member_id']];		
			$all[$values['id']]=$values;
		}
		return $all;
	}
	
	private function getMedia($id)
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
}

$out = new favoritesApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>