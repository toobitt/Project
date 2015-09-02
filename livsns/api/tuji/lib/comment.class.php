<?php
class comment
{
	protected $db;
	protected $user;
	public $table;
	function __construct($table = 'comment',$user = array())
	{
		$this->db = $gDB;
		if(!$user)
		{
			exit('缺少用户信息，无法完成评论');
		}
		$this->user = $user;
		$this->table = $table;
	}
	function __destruct()
	{
		
	}
	function docomment($content)
	{
		$sql = "INSERT INTO {$this->table} SET user_name = ".$this->user['username'].','.
		'content = '.$content;
		if($this->input['tuji_id'])
		{
			$sql .= ', tuji_id = '.$this->input['tuji_id'];
		}
		$sql .= ', time'.TIMENOW;
		if($this->input['source'])
		{
			$sql .= ', source = '.$this->input['source'];
		}
		$this->db->query($sql);
		return 	$this->db->affected_rows();
	}
	function delete($id=0)
	{
		if($id = 0)
		{
			return false;
		}
		$sql = "DELETE FROM {$this->table} WHERE id = ".intval($id);
		if($this->db->query($sql))
		{
			return $this->db->affected_rows();
		}
	}
	function update()
	{
		
	}
	function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$sql = "SELECT * FROM {$this->table}".$condition.' ORDER BY time DESC'.$limit;
		$q = $this->db->query($sql);
		while($r  = $this->db->fetch_array($r))
		{
			$r['time'] = date('Y-m-d h:i:s',$r['time']);
			$this->addItem($r);
		}
		$this->output();
	}
	function detail()
	{
		
	}
	function count()
	{
		return json_encode($this->db->query("SELECT COUNT(*) AS total FROM {$this->table} WHERE 1 ".$this->get_condition()));
	}
	function get_condition()
	{
		$condition = '';
		if($this->input['tuji_id'])
		{
			$condition .= ' AND tuji_id = '.intval(urldecode($this->input['tuji_id']));
		}
		//时间格式Y-m-d
		if($this->input['time'])
		{
			$condition .= ' AND time > '.intval(urldecode(strtotime($this->input['time'])));
		}
		if($this->input['content'])
		{
			$condition .= ' AND content LIKE "%'.urldecode($this->input['content']).'%"';
		}
		if($this->input['username'])
		{
			$condition .= ' AND user_name = '.urldecode($this->input['user_name']);
		}
		
	}
	function audit($id = 0)
	{
		if($id= 0)
		{
			return false;
		}
		$sql = "UPDATE {$this->table} SET status = 1 WHERE".intval($id);
		if($this->db->query($sql))
		{
			return true;
		}
	}
}
?>