<?php
define('MOD_UNIQUEID','member_group');//模块标识
require('./global.php');
class membergroupApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->group = new group();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 取出会员组信息
	 *
	 */
	public function show()
	{
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->group->show($condition,$offset,$count);
		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}
	/**
	 * 取出单个会员组信息
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput(NO_GID);
		}
		$info = $this->group->detail($id,true);
		$this->addItem($info);
		$this->output();
	}
	/**
	 *
	 *	取会员组下的拥有的会员,无会员的组不输出 ...
	 */
	public function showmember()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		$limit 	 = " LIMIT " . $offset . " , " . $count;
		$orderby = " ORDER BY g.order_id DESC ";
		$sql='SELECT g.id as gid,m.member_id,m.member_name,m.type FROM '.DB_PREFIX.'group as g RIGHT JOIN '.DB_PREFIX.'member as m ON g.id=m.gid WHERE 1 ';
		if($this->input['gid'])
		{
			$sql .=' AND g.id IN ('.$this->input['gid'].')';
		}
		$sql.= $orderby . $limit;
		$query=$this->db->query($sql);
		$ret=array();
		while ($row=$this->db->fetch_array($query))
		{
			$gid=$row['gid'];
			unset($row['gid']);
			$ret[$gid][]=$row;
		}
		foreach ($ret as $key=>$val)
		$this->addItem_withkey($key, $val);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "group WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}
	
	private function get_condition()
	{
		$condition = "";

		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$binary = '';//不区分大小些
			if(defined('IS_BINARY') && !IS_BINARY)//区分大小些
			{
				$binary = 'binary ';
			}
			$condition .= ' AND ' . $binary . ' name like \'%'.trim($this->input['k']).'%\'';
		}

		if ($this->input['id'])
		{
			$condition .= " AND id IN (" . trim($this->input['id']) . ")";
		}

		return $condition;
	}

}

$out = new membergroupApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>