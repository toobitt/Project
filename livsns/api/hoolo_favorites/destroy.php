<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: destroy.php 2774 2011-03-15 06:58:54Z wang $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class destroy extends BaseFrm
{
	private $mUser;
	function __construct()
	{ 
		parent::__construct();
			
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 删除一条收藏的点滴信息
	*/
	public function destroy() 
	{	
		//$this->input['id'] = 461;
		//$userinfo['id'] =36 ;
		//验证用户是否登录
		require_once(ROOT_DIR.'lib/user/user.class.php');
		$this->user = new user();
		
		$userinfo = $this->user->verify_credentials(); 
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		//获取用户参数
		if(!$this->input['id'])
		{
			return ;
		}
		//查询出要删除的信息
		//$sql = "SELECT sta.* , mea.source FROM ".DB_PREFIX."status sta  LEFT JOIN ".DB_PREFIX."media mea ON sta.id = mea.status_id ORDER BY sta.id DESC  limit $offset , $count";
		$sql = "SELECT sta.* , exl.transmit_count,exl.reply_count,exl.comment_count FROM ".DB_PREFIX.
		"status sta  LEFT JOIN ".DB_PREFIX."status_extra exl ON sta.id = exl.status_id where sta.id=".$this->input['id']." and sta.status=0";
		$row = $this->db->query_first($sql);
		if(!$row)
		{
			$this -> errorOutput(DELETE_FALES);
		}
		$members = $this->user->getUserById($row['member_id']);
		//对应user的键值
		foreach ($members as $key => $values)
		{
			$mem[$values['id']] = $values;
		}
		
		//查询表中是否已经存在这样一条记录
		$sql = "SELECT *  FROM ".DB_PREFIX."status_favorites WHERE status_id =".$this->input['id']." and member_id =".$userinfo['id'];

		$rowf = $this->db->query_first($sql);
		if(!$rowf)
		{
			$this -> errorOutput('要删除的数据不存在');
		}
		
		//删除收藏表中的数据
		$sql = "delete  from ".DB_PREFIX."status_favorites where status_id=".$this->input['id']." and member_id =".$userinfo['id'];
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
			$this -> errorOutput(DELETE_FALES);
		}			
	}
}
$out = new destroy();
$out->destroy();
?>