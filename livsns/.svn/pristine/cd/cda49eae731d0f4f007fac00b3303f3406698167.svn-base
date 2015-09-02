<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/seekhelp_comment.class.php';
define('MOD_UNIQUEID','seekhelp_comment');//模块标识
class seekhelpCommentApi extends outerReadBase
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
	
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;
		$orderby = ' ORDER BY c.create_time DESC';
		$res = $this->comment->show($this->get_condition(),$orderby,$offset,$count);
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
		if ($this->input['cid'])
		{
			$condition .= ' AND c.cid = '.intval($this->input['cid']);
		}
		if ($this->input['status'])
		{
			$condition .= ' AND c.status = '.intval($this->input['status']);
		}
		if (isset($this->input['is_recommend']))
		{
			$condition .= ' AND c.is_recommend = '.intval($this->input['is_recommend']);
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
		
	}
	
}
$ouput = new seekhelpCommentApi();
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