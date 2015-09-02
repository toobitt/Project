<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/seekhelp_joint.class.php';
define('MOD_UNIQUEID','seekhelp_joint');//模块标识
class seekhelpJointApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->joint = new ClassSeekhelpJoint();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 20;
		$orderby = ' ORDER BY create_time  DESC';
		$res = $this->joint->show($this->get_condition(),$orderby,$offset,$count);
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
			$condition .= ' AND cid = '.intval($this->input['cid']);
		}
		return $condition;
	}
	
	public function count()
	{
		$ret = $this->joint->count($this->get_condition());
		echo json_encode($ret);
	}
	
	public function detail()
	{
		
	}
	
}
$ouput = new seekhelpJointApi();
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