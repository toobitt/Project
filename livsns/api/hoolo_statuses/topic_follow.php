<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: topic_follow.php 1316 2010-12-28 04:56:18Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class topicFollowApi extends BaseFrm
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
	* 添加话题关注
	* @param $topic_id 传入话题ID
	* @return 关注信息
	*/	
	public function create()
	{
//		$this->input['topic']="团购在线";
		$info = array();
		if(!$this->input['topic'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else 
		{
			$topic = urldecode(trim($this->input['topic']));
			$sql = "SELECT * FROM ".DB_PREFIX."topic WHERE title = '".$topic."'";
			$first = $this->db->query_first($sql);
			if($first)
			{
				$topic_id = $first['id'];
			}
			else 
			{
				$sql = "INSERT INTO ".DB_PREFIX."topic(
				title,
				relate_count,
				status
				) 
				VALUES(
				'".$topic."',
				1,
				0
				)";
				$this->db->query($sql);
				$topic_id = $this->db->insert_id();			
			}
			$userinfo = $this->mUser->verify_credentials();
			if(!$userinfo['id'])
			{
				$this->errorOutput(USENAME_NOLOGIN);
			}
			$member_id = $userinfo['id'];
			$create_time = time();
			$sql = "INSERT IGNORE INTO ".DB_PREFIX."topic_member(
			member_id,
			topic_id,
			create_time
			) 
			VALUES(
			".$member_id.",
			".$topic_id.",
			".$create_time."
			)";
			$this->db->query($sql);
			$info = array(
				"member_id" => $member_id,
				"topic_id" => $topic_id,
				"create_time" => $create_time,
			);
			$this->setXmlNode('topicFollow','Topic');		
			$this->addItem($info);
			$this->output();			
		}
		 
	}
	
	/**
	* 获取话题关注
	* @return 关注信息()
	*/	
	public function show()
	{  
		$info = array();
		$userinfo = $this->mUser->verify_credentials();
		
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$member_id = $userinfo['id'];
		if(!$this->input['topic'])
		{
			$sql = "SELECT 
				member_id,
				topic_id,
				create_time,
				title, 
				relate_count
			FROM ".DB_PREFIX."topic_member m
			LEFT JOIN ".DB_PREFIX."topic t
			ON t.id = m.topic_id 
			WHERE status = 0 
				AND member_id = ".$member_id." 
			ORDER BY relate_count DESC";
			$query = $this->db->query($sql);
			while($array = $this->db->fetch_array($query))
			{
				$info[] = $array; 
			}
		}
		else 
		{
			$topic = urldecode(trim($this->input['topic']));
			$sql = "SELECT * FROM ".DB_PREFIX."topic WHERE title = '".$topic."'";
			$first = $this->db->query_first($sql);
			if($first)
			{
				$topic_id = $first['id'];
			}
			else 
			{
				$sql = "INSERT INTO ".DB_PREFIX."topic(
				title,
				relate_count,
				status
				) 
				VALUES(
				'".$topic."',
				1,
				0
				)";
				$this->db->query($sql);
				$topic_id = $this->db->insert_id();			
			}
			$sql = "SELECT 
				member_id,
				topic_id,
				create_time,
				title, 
				relate_count
			FROM ".DB_PREFIX."topic_member m
			LEFT JOIN ".DB_PREFIX."topic t
			ON t.id = m.topic_id 
			WHERE status = 0 
				AND member_id = ".$member_id." 
				AND  topic_id = ".$topic_id."
			ORDER BY relate_count DESC";
			$info = $this->db->query_first($sql);
		}
		$this->setXmlNode('topicFollow','Topic');		
		$this->addItem($info);
		$this->output();		 
	}
	
	/**
	* 删除话题关注
	* @param $topic_id 传入话题
	* @return 删除关注信息
	*/
	public function delete()
	{
		if(!$this->input['topic'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else 
		{
			$topic = urldecode(trim($this->input['topic']));
			$sql = "SELECT * FROM ".DB_PREFIX."topic WHERE title = '".$topic."'";
			$first = $this->db->query_first($sql);
			if($first)
			{
				$topic_id = $first['id'];
			}
			else 
			{
				$sql = "INSERT INTO ".DB_PREFIX."topic(
				title,
				relate_count,
				status
				) 
				VALUES(
				'".$topic."',
				1,
				0
				)";
				$this->db->query($sql);
				$topic_id = $this->db->insert_id();			
			}
			$sql = "SELECT * FROM ".DB_PREFIX."topic_member WHERE topic_id = ".$topic_id;
			$first = $this->db->query_first($sql);
			if(!$first)
			{
				$this->errorOutput(OBJECT_NULL);
			}
			else
			{
				$sql = "DELETE FROM ".DB_PREFIX."topic_member 
					WHERE topic_id = ".$topic_id;
				$this->db->query($sql);
				$this->setXmlNode('topicFollow','Topic');		
				$this->addItem($first);
				$this->output();
			}
			
		}
			
	}
}
$out = new topicFollowApi(); 
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	 $action = 'create';
}
$out->$action();
?>