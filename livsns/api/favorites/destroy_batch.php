<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: destroy_batch.php 17949 2013-02-26 03:08:00Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class destroy_batch extends appCommonFrm
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
	*批量删除收藏的点滴信息
	*/
	public function destroy_batch() 
	{	
		//$this->input['id'] = '11,12,13,14';
		//$userinfo['id'] =1 ;
		include_once(ROOT_DIR . 'lib/class/member.class.php');
		$this->member = new member();
		if(!$this->user['user_id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		//获取用户参数
		if(!$this->input['id'])
		{
			return ;
		}
		//一次性最多批量删除二十个点滴信息
		$sta = explode(',',$this->input['id']);
		if(count($sta)>20)
		{
			return ;
		}
		//查询出要删除的信息
		//$sql = "SELECT sta.* , mea.source FROM ".DB_PREFIX."status sta  LEFT JOIN ".DB_PREFIX."media mea ON sta.id = mea.status_id ORDER BY sta.id DESC  limit $offset , $count";
		$sql = "SELECT sta.* , exl.transmit_count,exl.reply_count,exl.comment_count FROM " . DB_PREFIX . "status sta LEFT JOIN " . DB_PREFIX . "status_extra exl ON sta.id = exl.status_id where sta.id in(" . $this->input['id'] . ") AND sta.status=0 ORDER BY sta.id DESC ";
		
		$result = $this->db->query($sql);
		while($row = $this->db->fetch_array($result))
		{		
			if($row['member_id'] != $userinfo['id'])
			{
				return ;
			}
			$members[] = $row['member_id'];
			//格式化时间
			$row['create_at'] = date("Y-m-d H:i:s",$row['create_at']);
			$blog[] = $row;
		}
		$members = implode(',',$members);
		$members = $this->member->getMemberById($members);
		
		//对应user的键值
		foreach ($members as $key => $values)
		{
			$mem[$values['id']] = $values;
		}
		//删除收藏表中的数据
		$sql = "delete  from " . DB_PREFIX . "status_favorites where status_id in  (" . $this->input['id'] . ") and member_id =" . $this->user['user_id'];
		$rowd = $this->db->query($sql);

		//如果删除成功则返回删除的数据
		if($rowd)
		{
			//博客信息和用户信息合并
			$this->setXmlNode('statuses','status');
			foreach ($blog as $key =>$values)
			{
				$values['user'] = $mem[$values['member_id']];
				$this->addItem($values);
			}			
			$this->output();
		}
		else 
		{
			$this -> errorOutput(DELETE_FALES);
		}			
	}
}
$out = new destroy_batch();
$out->destroy_batch();
?>