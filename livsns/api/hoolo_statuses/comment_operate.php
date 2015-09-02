<?php
/* $Id: comment.php 2774 2011-03-15 06:58:54Z wang $*/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . '/lib/class/curl.class.php');

class commentOpe extends BaseFrm
{
	private $mUser;
	private $mStatus;
	private $curl;
	function __construct()
	{
		parent::__construct();
			
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		include_once(ROOT_DIR . 'lib/class/status.class.php');
		$this->mUser = new user();
		$this->mStatus = new status();
		$this->curl = new curl();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	//获得全部评论
	public function commentAll()
	{
		$pp = $this->input['pp'];
		$perpage = $this->input['perpage'];
		$this->setXmlNode('comments','info');
		$sql = "select * from ".DB_PREFIX."status_comments where flag=0 order by comment_time desc limit $pp,$perpage ";
		$rt = $this->db->fetch_all($sql);
		$return['rt'] = $rt;
		$sql = "select count(*) as total from ".DB_PREFIX."status_comments where flag=0 ";
		$rt = $this->db->query_first($sql);
		$return['total'] = $rt['total'];
		$this->addItem($return);
		$this->output();
	}
	//删除评论
	public function delete()
	{
		$ids = $this->input['id'];
		$ids = urldecode($ids);
		$this->setXmlNode('comments','info');
		$sql = "update ".DB_PREFIX."status_comments set flag=1 where id in ($ids)";
		try{
			$this->db->query($sql);
			$return =1;
		}catch(Exception $e)
		{
			$return =0;
		}
		
		$this->addItem($return);
		$this->output();
	}
}

$out = new commentOpe();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	 $action = 'commentAll';
}
$out->$action();
?>