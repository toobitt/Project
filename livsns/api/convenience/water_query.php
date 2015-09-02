<?php
define('MOD_UNIQUEID','water_query');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/water_query.class.php');
class water_query_api extends outerReadBase
{
	private $water;
    public function __construct()
	{
		parent::__construct();
		$this->water = new water();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function count(){}
	public function detail(){}

	//根据用户号输出水费信息
	public function show()
	{
		if(!$this->input['user_number'])
		{
			$this->errorOutput(NO_USER_NUMBER);//没有户号
		}
		
		if(!$this->input['month'])
		{
			$this->errorOutput(NO_QUERY_MONTH);//没有查询最近几个月
		}
		else if(intval($this->input['month']) > 12 || intval($this->input['month']) < 0) 
		{
			$this->errorOutput(MONTH_ERROR);
		}
		
		$data = $this->water->query($this->input['user_number'],$this->input['month']);
		if(!$data)
		{
			$this->errorOutput(NO_DATA);	
		}
		
		$data_arr = xml2Array($data);
		if(!$data_arr)
		{
			$this->errorOutput(DATA_ERROR);
		}
		
		foreach ($data_arr['record'] AS $k => $v)
		{
			if($v['status'] == '已销')
			{
				$data_arr['record'][$k]['state'] = 1;
			}
			else 
			{
				$data_arr['record'][$k]['state'] = 0;
			}
		}

		$this->addItem($data_arr);
		$this->output();
	}
}

$out = new water_query_api();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>