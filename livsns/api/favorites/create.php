<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: create.php 17949 2013-02-26 03:08:00Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class create extends appCommonFrm
{
	private $mUser;
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 收藏一条点滴信息
	*/
	public function create() 
	{	
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		//$userinfo['id'] =36 ;
		//获取用户参数
		if(!$this->input['id'])
		{
			return ;
		}
		//查询出要收藏的信息
		//$sql = "SELECT sta.* , mea.source FROM ".DB_PREFIX."status sta  LEFT JOIN ".DB_PREFIX."media mea ON sta.id = mea.status_id ORDER BY sta.id DESC  limit $offset , $count";
		$sql = "SELECT sta.* , exl.transmit_count,exl.reply_count,exl.comment_count FROM " . DB_PREFIX . "status sta  LEFT JOIN " . DB_PREFIX . "status_extra exl ON sta.id = exl.status_id where sta.id=" . $this->input['id'] . " and sta.status=0";
		$row = $this->db->query_first($sql);
		if(!$row)
		{
			$this -> errorOutput(INSERT_NOTEXIT);
		}
		include_once(ROOT_DIR . 'lib/class/member.class.php');
		$this->member = new member();
		$members = $this->member->getMemberById($row['member_id']);
		//对应user的键值
		foreach ($members as $key => $values)
		{
			$mem[$values['id']] = $values;
		}
		
		//查询表中是否已经存在这样一条记录
		$sql = "SELECT *  FROM " . DB_PREFIX . "status_favorites WHERE status_id =" . $this->input['id'] . " and member_id =" . $this->user['user_id'];
		$rowf = $this->db->query_first($sql);
		if($rowf)
		{
			$this -> errorOutput(INSERT_EXIT);
		}
		
		//收藏表中记录一条数据
		$sql = "INSERT " . DB_PREFIX . "status_favorites(
					status_id,
					member_id,
					favorite_time
					)
					VALUES(
					".$this->input['id'].",
					'" . $this->user['user_id'] . "',
					" . TIMENOW . "				
					)
					";
		
		$rowd = $this->db->query($sql);
		
		//如果删除成功则返回删除的数据
		if($rowd)
		{
			//博客信息和用户信息合并
			$this->setXmlNode('statuses','status');
			$row['user'] = $mem[$row['member_id']];
			$this->addItem($row);
			$this->output();
		}
		else 
		{
			$this -> errorOutput(INSERT_FASLE);
		}			
	}
}
$out = new create();
$out->create();
?>