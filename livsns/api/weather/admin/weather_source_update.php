<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/weather_source.class.php';
define('MOD_UNIQUEID','weather_source');//模块标识
class source_updateApi extends adminUpdateBase
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
	function create()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$data = array(
		'support_name'=>trim(urldecode($this->input['support_name'])),
		'official_site'=>trim(urldecode($this->input['official_site'])),
		'weather_api_url'=>trim(urldecode($this->input['weather_api_url'])),
		'weather_api_dir'=>trim(urldecode($this->input['weather_api_dir'])),
		'city_api_url'=>trim(urldecode($this->input['city_api_url'])),
		'city_api_dir'=>trim(urldecode($this->input['city_api_dir'])),
		'inner_func'=>trim(urldecode($this->input['inner_func'])),
		'is_open'=>intval($this->input['is_open']),
		'create_time'=>TIMENOW,
		'ip'=>hg_getip(),
		'user_name'=>$this->user['user_name'],
		'user_id'=>$this->user['id'],
		);
		$ret = $this->source->create($data);
		$this->addItem($ret);
		$this->output();
	}
	function update()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		if(!$this->input['id'])
		{
			$this->errorOutput();
		}
		$id = intval($this->input['id']);
		$data = array(
		'support_name'=>trim(urldecode($this->input['support_name'])),
		'official_site'=>trim(urldecode($this->input['official_site'])),
		'weather_api_url'=>trim(urldecode($this->input['weather_api_url'])),
		'weather_api_dir'=>trim(urldecode($this->input['weather_api_dir'])),
		'city_api_url'=>trim(urldecode($this->input['city_api_url'])),
		'city_api_dir'=>trim(urldecode($this->input['city_api_dir'])),
		'inner_func'=>trim(urldecode($this->input['inner_func'])),
		'is_open'=>intval($this->input['is_open']),
		'ip'=>hg_getip(),
		);
		$ret = $this->source->update($data,$id);
		$this->addItem($ret);
		$this->output();
	}
	function delete()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->source->delete($ids);
		$this->addItem($ret);
		$this->output();
	}
	function sort(){}
	function publish(){}
	function audit(){}
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