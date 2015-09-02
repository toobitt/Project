<?php
require_once './global.php';
define('MOD_UNIQUEID','seekhelp_selfcomment');//模块标识
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
class seekhelpSelfCommentApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->sh = new ClassSeekhelp();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;
		$orderby = ' ORDER BY order_id  DESC';
		$res = $this->sh->show($this->get_condition(),$orderby,$offset,$count);
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		$member_id = intval($this->user['user_id']);
		$member_id = $member_id ? $member_id : '-1';
		$sql = 'SELECT DISTINCT cid FROM ' .DB_PREFIX. 'comment WHERE 1 AND status=1 AND member_id = '.$member_id;
		$query = $this->db->query($sql);
		$cids = array();
		while ($row = $this->db->fetch_array($query))
		{
			$cids[] = $row['cid'];
		}
		$cids = empty($cids) ? '-1' : implode(',', $cids);
		$condition .= ' AND sh.id IN ('.$cids.')  AND sh.status = 1' ;
		//为叮当多添加一个分类约束
		$sort_id = is_array($this->input['sort_id']) ? implode(',', $this->input['sort_id']) : trim($this->input['sort_id']);
		if ($sort_id)
		{
			$condition .= ' AND sh.sort_id IN ('.$sort_id.')';
		}
		return $condition;
	}
	
	public function count()
	{
		$ret = $this->sh->count($this->get_condition());
		echo json_encode($ret);
	}
	
	public function detail()
	{
		;
	}
}
$ouput = new seekhelpSelfCommentApi();
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