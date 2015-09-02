<?php
/**
 *获取停车场对外输出接口(按照区域输出)
 */
require ('./global.php');
require_once(CUR_CONF_PATH . 'lib/carpark_mode.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
define('MOD_UNIQUEID','get_carpark_by_district');
define('SCRIPT_NAME', 'get_carpark_by_district');
class get_carpark_by_district extends outerReadBase
{
	private $mode;
	public function __construct()
	{
		parent::__construct();
		$this->mode = new carpark_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}	
	
	public function show()
	{
		if(!$this->input['city_name'])
		{
			$this->errorOutput('请传递城市名称');
		}
		
		//类型
		$type_id = intval($this->input['type_id']);
		
		$ret = $this->mode->get_carpark_by_district($this->input['city_name'],$this->input['wd'],$this->input['jd'],$type_id);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
		}
		else 
		{
			$this->addItem(array());
		}
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>