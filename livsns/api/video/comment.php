<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: comment.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class commentApi extends adminBase
{
	private $mVideo;
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
		include_once(ROOT_PATH . '/lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 显示评论
	* @param $user_id 判断该用户与发表评论用户的关系
	* @param $cid   评论对象
	* @param $type （0视频、1网台、2用户、3专辑）
	* @param $state
	* @param $page
	* @param $count
	* @return $ret 评论信息
	*/
	function show(){
		$reply = array();
		$comment = array();
		$mInfo = $this->mUser->verify_credentials();
		$mInfo['id'] = $mInfo['id']?$mInfo['id']:0;
		$user_id = intval($this->input['user_id']);
		$cid = urldecode($this->input['cid']?$this->input['cid']:2);
		$right = 0;
		if($user_id == $mInfo['id'])
		{
			$right = 1;
		}
		$type = $this->input['type']?$this->input['type']:0;//默认为视频
		$state = intval($this->input['state']?$this->input['state']:0);
		$page = intval($this->input['page']?$this->input['page']:0);
		$count = intval($this->input['count']?$this->input['count']:20);
		$offset = $page * $count;
		$end = " LIMIT $offset , $count";
		if(!$cid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$uid = "";
		$sql = "SELECT * FROM ".DB_PREFIX."comments WHERE type = ".$type." AND state = ".$state." AND reply_id !=0 AND cid IN (".$cid.") ORDER BY create_time DESC";
		$query = $this->db->query($sql);
		while($array = $this->db->fetch_array($query))
		{
			if($right)
			{
				$del_right = 1;
			}
			else 
			{
				if($mInfo['id']&&$mInfo['id']==$array['user_id'])
				{
					$del_right = 1;
				}
				else 
				{
					$del_right = 0;
				}
			}
			$array['relation'] = $del_right;
			$reply[$array['reply_id']][] = $array;
			$uid .= $array['user_id'].',';
		}
		$userinfo = $this->mVideo->getUserById($uid);
		foreach($reply as $key =>$value)
		{
			foreach($value as $k=>$v)
			{
				$reply[$key][$k]['user'] = $userinfo[$v['user_id']];
				unset($reply[$key][$k]['user_id']);
			}
		}
		$uid = "";
		$sql = "SELECT * FROM ".DB_PREFIX."comments WHERE type = ".$type." AND state = ".$state." AND reply_id=0 AND cid IN (".$cid.")"." ORDER BY create_time DESC ".$end;
		$query = $this->db->query($sql);
		while($array = $this->db->fetch_array($query))
		{
			if($right)
			{
				$del_right = 1;
			}
			else 
			{
				if($mInfo['id']&&$mInfo['id']==$array['user_id'])
				{
					$del_right = 1;
				}
				else 
				{
					$del_right = 0;
				}
			}
			$array['relation'] = $del_right;
			$array['reply'] = $reply[$array['id']];
			$comment[] = $array;
			$uid .= $array['user_id'].',';
		}
		$userinfo = $this->mVideo->getUserById($uid);
		foreach($comment as $key=>$value)
		{
			$comment[$key]['user'] = $userinfo[$value['user_id']];
			unset($comment[$key]['user_id']);
		}
		$sql = "SELECT count(*) total FROM ".DB_PREFIX."comments WHERE type = ".$type." AND state = ".$state." AND reply_id=0 AND cid IN (".$cid.")";
		$q = $this->db->query_first($sql);
		if($q['total'])
		{
			$comment['total'] = $q['total'];
		}
		$this->setXmlNode('comments','info');
		$this->addItem($comment);
		$this->output();
	}
	
	/**
	* 查询用户的评论
	* @param $user_id 
	* @param $type （0视频、1网台、2用户、3专辑）
	* @param $state
	* @param $page
	* @param $count
	* @return $ret 评论信息
	*/
	function getUserComments()
	{
		$reply = array();
		$comment = array();
		$mInfo = $this->mUser->verify_credentials();
		$mInfo['id'] = $mInfo['id']?$mInfo['id']:0;
		$user_id = intval($this->input['user_id']);
		$type = $this->input['type']?$this->input['type']:0;//默认为视频
		$state = intval($this->input['state']?$this->input['state']:0);
		$page = intval($this->input['page']?$this->input['page']:0);
		$count = intval($this->input['count']?$this->input['count']:20);
		$offset = $page * $count;
		$end = " LIMIT $offset , $count";
		if(!$user_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$right = 0;
		if($user_id == $mInfo['id'])
		{
			$right = 1;
		}
		$uid = "";
		$sql = "SELECT * FROM ".DB_PREFIX."comments WHERE type = ".$type." AND state = ".$state." AND reply_id !=0 AND user_id = ".$user_id." ORDER BY create_time DESC";
		$query = $this->db->query($sql);
		while($array = $this->db->fetch_array($query))
		{
			if($right)
			{
				$del_right = 1;
			}
			else 
			{
				if($mInfo['id']&&$mInfo['id']==$array['user_id'])
				{
					$del_right = 1;
				}
				else 
				{
					$del_right = 0;
				}
			}
			$array['relation'] = $del_right;
			$reply[$array['reply_id']][] = $array;
			$uid .= $array['user_id'].',';
		}
		$userinfo = $this->mVideo->getUserById($uid);
		foreach($reply as $key =>$value)
		{
			foreach($value as $k=>$v)
			{
				$reply[$key][$k]['user'] = $userinfo[$v['user_id']];
				unset($reply[$key][$k]['user_id']);
			}
		}
		$uid = "";
		$sql = "SELECT * FROM ".DB_PREFIX."comments WHERE type = ".$type." AND state = ".$state." AND reply_id=0 AND user_id = ".$user_id.$end;
		$query = $this->db->query($sql);
		while($array = $this->db->fetch_array($query))
		{
			if($right)
			{
				$del_right = 1;
			}
			else 
			{
				if($mInfo['id']&&$mInfo['id']==$array['user_id'])
				{
					$del_right = 1;
				}
				else 
				{
					$del_right = 0;
				}
			}
			$array['relation'] = $del_right;
			$array['reply'] = $reply[$array['id']];
			$comment[] = $array;
			$uid .= $array['user_id'].',';
		}
		$userinfo = $this->mVideo->getUserById($uid);
		foreach($comment as $key=>$value)
		{
			$comment[$key]['user'] = $userinfo[$value['user_id']];
			unset($comment[$key]['user_id']);
		}
		$sql = "SELECT count(*) total FROM ".DB_PREFIX."comments WHERE type = ".$type." AND state = ".$state." AND reply_id=0 AND user_id = ".$user_id;
		$q = $this->db->query_first($sql);
		if($q['total'])
		{
			$comment['total'] = $q['total'];
		}
		$this->setXmlNode('comments','info');
		$this->addItem($comment);
		$this->output();
	}
	
	
	
	/**
	* 添加评论
	* @param $cid 内容ID
	* @param $content 内容
	* @param $reply_id 回复内容ID
	* @param $reply_user_id 回复内容的用户ID
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 评论信息
	*/
	function create(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		include_once(ROOT_DIR . 'lib/class/banword.class.php');
		$banword = new banword();
		$status = 1;
		$banwords = $banword->banword(($this->input['content']));

		if($banwords && $banwords != 'null') //暂时先定义为没关键词
		{
		 	$status = 0;	
			$banwords = implode(',', $banwords);
		}
		else
		{
			$banwords = '';
		}
		$info = array(
			'id' => 0,
			'cid' => 0,
			'user_id' => $mInfo['id'],
			'content' => "",
			'ip' => hg_getip(),
			'reply_id' => 0,
			'reply_user_id' => 0,
			'create_time' => time(),
			'type' => 0,
			'state' => $status,
		);
		$info['cid'] = $this->input['cid'];
		$info['content'] = urldecode($this->input['content']);
		$info['type'] = $this->input['type'];//默认为视频
		$info['reply_id'] = $this->input['reply_id'];
		$info['reply_user_id'] = $this->input['reply_user_id'];
		if(!$info['cid'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql = "INSERT INTO ".DB_PREFIX."comments(
			user_id,
			cid,
			content,
			ip,
			reply_id,
			reply_user_id,
			create_time,
			type,
			state 
		) VALUES(
			".$info['user_id'].",
			".$info['cid'].",
			'".$info['content']."',
			'".$info['ip']."',
			".$info['reply_id'].",
			".$info['reply_user_id'].",
			".$info['create_time'].",
			".$info['type'].",
			".$info['state']."
		)";
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		$info['user']  = $this->mVideo->getUserById($info['user_id']);
		unset($info['user_id']);
		switch($info['type'])
		{
			case 0:
				$sql = "UPDATE ".DB_PREFIX."video SET comment_count= comment_count+1 
					WHERE id=".$info['cid'];
				$this->db->query($sql);
				break;
			case 1:
				$sql = "UPDATE ".DB_PREFIX."network_station SET comment_count= comment_count+1 
					WHERE id=".$info['cid'];
				$this->db->query($sql);
				break;
			case 2:
				$sql = "UPDATE ".DB_PREFIX."user SET comment_count= comment_count+1  
					WHERE id=".$info['cid'];
				$this->db->query($sql);
				break;
			case 3:
				$sql = "UPDATE ".DB_PREFIX."album SET comment_count= comment_count+1  
					WHERE id=".$info['cid'];
				$this->db->query($sql);
				break;
			default:
				break;
		}
		$this->setXmlNode('comments','info');
		$this->addItem($info);
		$this->output();
	}
	
	/**
	* 删除评论
	* @param $id 评论ID
	* @param $cid 评论对象ID
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 评论信息
	*/
	function del(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$id = $this->input['id']?$this->input['id']:0;
		$cid = $this->input['cid']?$this->input['cid']:0;
		$type = $this->input['type']?$this->input['type']:0;
		if(!$id&&!$cid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sqls = "UPDATE ".DB_PREFIX."comments SET state=0 WHERE id = ".$id;
		$this->db->query($sqls);
		switch($type)
			{
				case 0:
					$sql = "UPDATE ".DB_PREFIX."video SET comment_count= comment_count-1 
						WHERE id=".$cid;
					break;
				case 1:
						$sql = "UPDATE ".DB_PREFIX."network_station SET comment_count= comment_count-1 
							WHERE id=".$cid;
					break;
				case 2:
						$sql = "UPDATE ".DB_PREFIX."user SET comment_count= comment_count-1  
							WHERE id=".$cid;
					break;
				case 3:
						$sql = "UPDATE ".DB_PREFIX."album SET comment_count= comment_count-1  
							WHERE id=".$cid;
					break;
				default:
					break;
			}
		$this->db->query($sql);
		$this->setXmlNode('comments','info');
		$this->addItem($sqls);
		$this->output();
	}
	
	/**
	* 恢复评论
	* @param $id 评论ID
	* @param $cid 评论对象ID
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 评论信息
	*/
	function recover(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$id = $this->input['id']?$this->input['id']:0;
		$cid = $this->input['cid']?$this->input['cid']:0;
		$type = $this->input['type']?$this->input['type']:0;
		if(!$id&&!$cid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sqls = "UPDATE ".DB_PREFIX."comments SET state=1 WHERE id = ".$id;
		$this->db->query($sqls);
		switch($type)
			{
				case 0:
					$sql = "UPDATE ".DB_PREFIX."video SET comment_count= comment_count+1 
						WHERE id=".$cid;
					break;
				case 1:
						$sql = "UPDATE ".DB_PREFIX."network_station SET comment_count= comment_count+1 
							WHERE id=".$cid;
					break;
				case 2:
						$sql = "UPDATE ".DB_PREFIX."user SET comment_count= comment_count+1  
							WHERE id=".$cid;
					break;
				case 3:
						$sql = "UPDATE ".DB_PREFIX."album SET comment_count= comment_count+1  
							WHERE id=".$cid;
					break;
				default:
					break;
			}
		$this->db->query($sql);
		$this->setXmlNode('comments','info');
		$this->addItem($sqls);
		$this->output();
	}
	
	//获得全部评论
	public function commentAll()
	{
		$pp = $this->input['pp'];
		$perpage = $this->input['perpage'];
		$this->setXmlNode('comments','info');
		$sql = "select * from ".DB_PREFIX."comments where state=1 order by create_time desc limit $pp,$perpage ";
		$rt = $this->db->fetch_all($sql);
		$return['rt'] = $rt;
		$sql = "select count(*) as total from ".DB_PREFIX."comments where state=1 ";
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
		$sql = "update ".DB_PREFIX."comments set state=0 where id in ($ids)";
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

$out = new commentApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>