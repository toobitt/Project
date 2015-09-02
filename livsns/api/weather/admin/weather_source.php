<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/weather_source.class.php';
define('MOD_UNIQUEID','weather_source');//模块标识
class sourceApi extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->source = new weather_source();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
	
	}
	function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$condition = $this->get_condition();
		$orderby = ' ORDER BY id ASC';
		$ret = $this->source->show($condition, $orderby, $offset, $count);
		if (!empty($ret) && is_array($ret))
		{
			foreach ($ret as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	function detail()
	{
		$id = intval($this->input['id']);
		$ret = $this->source->detail($id);
		$this->addItem($ret);
		$this->output();
	}
	function get_condition()
	{
		$conditions = '';
		return $conditions;
	}
	function count()
	{
	
	}
	
}
$ouput= new sourceApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();