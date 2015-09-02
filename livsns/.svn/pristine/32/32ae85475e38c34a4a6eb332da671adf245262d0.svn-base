<?php
class Reply extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	function show($condition,$orderby,$limit)
	{
		$sql = "SELECT r.id,r.content_reply,r.answerer,r.reply_time,r.ip,r.state,m.id as messageid,m.content,m.contentid,g.name as groupname FROM ".DB_PREFIX."message_reply r 
		LEFT OUTER JOIN ".DB_PREFIX."message m ON m.id = r.contentid 
		LEFT OUTER JOIN ".DB_PREFIX."message_node g ON m.groupid = g.id 
		WHERE 1 " .$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{   
			$r['reply_time'] = date('Y-m-d H:i:s',$r['reply_time']);
			$res[] = $r;
		}
		return $res;	
	}
	
	/*
	*添加留言
	*
	*$message:留言信息数组
	*/
	public function add_message($message)
	{
		if(!$message)
		{
			return false;
		}
		$data = array(
		'title'=>urldecode($message['title']),
		'username'=>urldecode($message['username']),
		'content'=>urldecode($message['content']),
		'pub_time'=>TIMENOW,
		'ip'=>hg_getip(),
		'groupid'=>$message['groupid'],
		'contentid'=>$message['contentid'],
		);
		
		$sql = 'INSERT INTO '.DB_PREFIX.'message SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		if($this->db->query($sql))
		{
			$data['id'] = $this->db->insert_id();
			return $data;
		}
		else
		{
			return false;
		}
	}
	/*
	*回复留言
	*$id：留言id
	*$content：回复内容
	*/
	public function reply_message($id,$data)
	{
		if(!$data)
		{
			return false;
		}
		//将最新一条回复插到留言表中
		$reply = serialize($data);
		$sql = 'UPDATE '.DB_PREFIX.'message SET last_reply='."'".$reply."'".' WHERE id = '.$id;
		$sql = rtrim($sql,',');
		$this->db->query($sql);

		//插入回复表
		$sql = 'INSERT INTO '.DB_PREFIX.'message_reply SET ';
		foreach($data as $k=>$v)
		{
			$sql .= '`'.$k . '`="' . $v . '",';
		}
		$sql = rtrim($sql,',');
		if($this->db->query($sql))
		{
			$data['id'] = $this->db->insert_id();
			return $data;
		}
		else
		{
			return false;
		}
	}
	/**
	*
	*查找具体某一个留言回复信息
	*
	**/
	public function detail($condition)
	{	
		$sql = "SELECT r.id,r.content_reply,r.answerer,r.reply_time,r.ip,m.content,m.contentid,g.groupname FROM ".DB_PREFIX."message_reply r 
		LEFT OUTER JOIN ".DB_PREFIX."message m ON m.id = r.contentid 
		LEFT OUTER JOIN ".DB_PREFIX."message_group g ON m.groupid = g.groupid 
		WHERE 1 " .$condition;
		$res = $this->db->query($sql);
		while($info = $this->db->fetch_array($res))
		{	
			$info['reply_time'] = date('Y-m-d H:i:s',$info['reply_time']);
			$return['info'][] = $info;
		}
		return $return;
	}
}

?>