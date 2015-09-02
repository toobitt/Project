<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: report.php 17941 2013-02-26 02:20:49Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class reportApi extends appCommonFrm
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
	* 检索举报
	* @param $type 类型
	* @param $state 状态  0--所有的  1 存在
	* @param $page 页码
	* @param $count 数量每页
	* @return $info 举报信息
	*/
	public function show()
	{
		$count = $this->input['count']? $this->input['count']:10;
		$page = $this->input['page']? $this->input['page']:0;
		$offset = $page;
		$end = " order by create_time desc LIMIT $offset,$count";
		
		$wherecond = ' WHERE 1';
		
		$type = $this->input['type']?$this->input['type']:0;
		if($type && $type != 'all')
		{
			$wherecond .= ' AND type='.$type;
		}
		
		$state = $this->input['state']?$this->input['state']:0;
		if($state)
		{
			$wherecond .= ' AND state=1';
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "report ".$wherecond.$end;
		$query = $this->db->query($sql);
		$repeat = array();
		$index =0;
		while($row = $this->db->fetch_array($query))
		{
			if(!isset($repeat[$row['cid']]))
			{
				$repeat[$row['cid']] = $index;
				$row['repeat'] = 1;
				$report[$index] = $row;
			}
			else
			{
				$tempIndex = $repeat[$row['cid']];
				$report[$tempIndex]['repeat'] = $report[$tempIndex]['repeat'] + 1;
				$row['repeat'] = 0;
				$report[$index] = $row;
			}
			$index++;
		}
		$sql = "SELECT count(*) as total FROM  ".DB_PREFIX."report ".$wherecond;
		$q = $this->db->query_first($sql);
		if($count)
		{
			$report['total'] = $q['total'];
		}
		$this->setXmlNode('report','info');
		$this->addItem($report);
		$this->output();
	}

	/**
	* 创建举报
	* @param $cid 对象ID
	* @param $uid 被举报人
	* @param $type 类型
	* @param $url 地址 
	* @param $content 举报内容
	* @return $info 举报信息
	*/
	public function create()
	{
		$userinfo = $this->mUser->verify_credentials();
		
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$info = array(
			'cid' => $this->input['cid']?$this->input['cid']:0,
			'uid' => $this->input['uid']?$this->input['uid']:0,
			'user_id' => $userinfo['id'],
			'type' => $this->input['type'],
			'url' => urldecode($this->input['url']?$this->input['url']:""),
			'content' => urldecode($this->input['content']?$this->input['content']:""),
			'create_time' => time(),
			'ip' => hg_getip(),
			'state' => 1,
		);
		
		if(!$info['cid']||!$info['uid'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "SELECT * FROM  ".DB_PREFIX."report WHERE cid = ".$info['cid']." 
		AND uid = ".$info['uid']." 
		AND user_id = ".$info['user_id']." 
		AND type = ".$info['type'];
		$check = $this->db->query_first($sql);
		if($check)
		{
			$sql = "UPDATE ".DB_PREFIX."report SET
			url='".$info['url']."',content='".$info['content']."',create_time=".$info['create_time']." WHERE id = ".$check['id'];
			$this->db->query($sql);
			$info['id'] = $check['id'];
		}
		else 
		{
			$sql = "INSERT INTO ".DB_PREFIX."report(cid,uid,user_id,type,url,content,create_time,ip,state) 
			VALUES(
				".$info['cid'].",
				".$info['uid'].",
				".$info['user_id'].",
				".$info['type'].",
				'".$info['url']."',
				'".$info['content']."',
				".$info['create_time'].",
				'".$info['ip']."',
				1
				)";
			$this->db->query($sql);
			$info['id'] = $this->db->insert_id();
		}
		
		$this->setXmlNode('report','info');
		$this->addItem($info);
		$this->output();
	}
	
	/**
	* 删除举报
	* @param $id 
	* @return $info 举报信息
	*/
	public function del()
	{
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$id =  $this->input['id']?$this->input['id']:0;
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "UPDATE ".DB_PREFIX."report SET
			state=0 WHERE id in (".$id.")";

		$this->db->query($sql);
		
		$this->setXmlNode('report','info');
		$this->addItem(1);
		$this->output();
	}

	/**
	* 恢复举报
	* @param $id 
	* @return $info 举报信息
	*/
	public function recover()
	{
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$id =  $this->input['id']?$this->input['id']:0;
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "UPDATE ".DB_PREFIX."report SET
			state=1 WHERE id = ".$id;
		$this->db->query($sql);
		
		$this->setXmlNode('report','info');
		$this->addItem($id);
		$this->output();
	}
	
}
$out = new reportApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>