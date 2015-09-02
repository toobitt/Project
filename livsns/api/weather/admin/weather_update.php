<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/weather.class.php';
define('MOD_UNIQUEID','weather');//模块标识
class source_updateApi extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		$this->weather = new weather();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		
	}
	public function update()
	{
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'manage'));
		/*********权限验证结束*********/
		$id = $this->input['id'];
		$ret = $this->weather->update($id);
		$this->addItem($ret);
		$this->output(); 			
	}
public function updatepm25()
	{
		$fileds = array(
		'aqi',
		'co',
		'co_24h',
		'no2',
		'no2_24h',
		'o3',
		'o3_24h',
		'o3_8h',
		'o3_8h_24h',
		'pm10',
		'pm10_24h',
		'pm2_5',
		'pm2_5_24h',
		'primary_pollutant',
		'quality',
		'so2',
		'so2_24h',
		);
		/*********权限验证开始*********/
		$this->verify_content_prms(array('_action'=>'manage'));
		/*********权限验证结束*********/
		$data['id'] = $this->input['pm25_id'];
		foreach ($fileds as $key)
		{
			$data[$key]=$this->input["$key"];
			
		}
		$ret = $this->weather->update_pm25_data_admin($data);
		$this->addItem($ret);
		$this->output(); 			
	}
	
	public function delete()
	{
		
	}
	public function audit()
	{
		
	}
	public function sort()
	{
		$this->verify_content_prms(array('_action'=>'manage'));
		$ret = $this->drag_order('weather', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	public function publish()
	{
		
	}
	public function config()
	{
		$user_desc = $this->input['user_desc'];
		$user_desc = array_filter($user_desc);
		$user_field = $this->input['user_field'];
		$source_field = $this->input['source_field'];
		$source_select = $this->input['source_select'];
		$user_data = $this->input['user_data'];
		$notice_time = $this->input['notice_time'];
		$k_user_field = array();
		if (is_array($user_desc))
		{
			foreach ($user_desc as $key=>$val)
			{
				$k_user_field[$key] = $user_field[$key]?trim(strtolower($user_field[$key])):$this->errorOutput('用户自定义字段不能为空');
				if (!ereg("^[A-Za-z0-9_]+$", $user_field[$key]))
				{
					$this->errorOutput($user_desc[$key].'字段名不合法');
				}
			}
			
			$k_user_field_num = array_count_values($k_user_field);
			foreach ($k_user_field_num as $key=>$val)
			{
				if ($val>1)
				{
					$this->errorOutput('用户自定义字段不能重复,大小写视为相同');
				}	
			}
			foreach ($k_user_field as $key=>$val)
			{
				if ($source_select[$key])
				{
					if ($source_field[$key][''])
					{
						
					}
				}
				
			}
				
		}
		
		exit();
	}
	//字段过滤
	private function field_filter($value)
	{
		
	} 
}
$ouput= new source_updateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
} else{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>