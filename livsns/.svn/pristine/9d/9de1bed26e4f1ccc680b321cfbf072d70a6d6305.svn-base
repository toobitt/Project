<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/seekhelp_comment.class.php';
define('MOD_UNIQUEID','seekhelp_comment');//模块标识
class seekhelpComment extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->comment = new ClassSeekhelpComment();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;
		$orderArr = $this->input['order'];
		if ($orderArr && is_array($orderArr))
		{
		    $orderby = ' ORDER BY';
		    foreach ($orderArr as $k => $v)
		    {
		        $orderby .= ' c.' . $k . ' ' . strtoupper($v) . ',';
		    }
		    $orderby = rtrim($orderby, ',');
		}
		else
		{
		    $orderby = ' ORDER BY c.create_time DESC';
		}
		$ret = $this->comment->show($this->get_condition(),$orderby,$offset,$count);
		foreach ($ret as $k=>$v)
		{
			//处理回复数据
			$orderby = ' ORDER BY create_time  ASC';
			$condition = " AND cid='".intval($this->input['cid'])."' AND comment_type='vice' AND comment_fid=".$v['id']."";
			$reply_data = $this->comment->show($condition, $orderby, $offset, $count);
			
			$ret[$k]['reply'] = $reply_data;
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$sql = 'SELECT id FROM '.DB_PREFIX.'seekhelp WHERE title LIKE "%'.trim($this->input['k']).'%"';
			$query = $this->db->query($sql);
			$ids = array();
			while ($row = $this->db->fetch_array($query))
			{
				$ids[] = $row['id'];
			}
			if (!empty($ids))
			{
				$cids = implode(',', $ids);
				$condition .= ' AND c.cid IN ('.$cids.')';
			}
			else 
			{
				$condition .= ' AND c.cid = -1';
			}
		}
		if ($this->input['cid'])
		{
			$condition .= ' AND c.cid = '.intval($this->input['cid']);
		}
		if ($this->input['comment_type'])
		{
			$condition .= ' AND c.comment_type = "'.trim($this->input['comment_type']).'"';
		}
		else 
		{
			$condition .= ' AND (c.comment_type = "main" or c.comment_type = "")';
		}
		if ($this->input['member_id'])
		{
		    $condition .= ' AND c.member_id = ' . intval($this->input['member_id']);
		}
		if ($this->input['status'])
		{
			$condition .= ' AND c.status = '.intval($this->input['status']);
		}
		if ($this->input['is_recommend'])
		{
			$condition .= ' AND c.is_recommend = '.intval($this->input['is_recommend']);
		}
		
		if ($this->input['_id'])
		{
			$sql = " SELECT childs FROM ".DB_PREFIX."sort WHERE  id = '".$this->input['_id']."'";
			$arr = $this->db->query_first($sql);
			if($arr)
			{
				$condition .= ' AND c.sort_id IN ('.$arr['childs'].')';
			}
		}
		return $condition;
	}
	
	public function count()
	{
		$ret = $this->comment->count($this->get_condition());
		echo json_encode($ret);
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->comment->detail($id);
		$this->addItem($data);
		$this->output();
	}
	
	public function sort()
	{
		
	}
}
$ouput = new seekhelpComment();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>