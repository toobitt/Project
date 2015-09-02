<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . '/lib/class/curl.class.php');

class createfollow extends appCommonFrm
{	
	private $curl; 
	function __construct()
	{
		parent::__construct();
		$this->curl = new curl();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$sql = "SELECT * FROM ".DB_PREFIX."new_member";
		$result=$this->db->query($sql);
		$info = array();
		while($row=$this->db->fetch_array($result))
		{
			echo $row['member_id'];
			$this->createFllow($row['member_id']);
			$this->copy_program($row['member_id']);
			$sql = "DELETE FROM ".DB_PREFIX."new_member WHERE member_id = " . $row['member_id'];
			$this->db->query($sql);
		}
		echo 'Done';
	}

	private function createFllow($self_id, $user_id = 6744)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('self_id',$self_id);		
		$this->curl->addRequestData('user_id',$user_id);		
		$this->curl->request('friendships/create.php');	
	}
	
	/**
	* 创建节目单信息
	* @param $user_id 用户ID
	* @return $ret 节目单信息
	*/
	public function copy_program($user_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'copys');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->request('video/program.php');
	}
	
}

$out = new createfollow();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();