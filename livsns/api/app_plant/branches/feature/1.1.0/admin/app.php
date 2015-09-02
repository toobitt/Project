<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/app.class.php';
include_once ROOT_PATH . 'lib/class/material.class.php';
define('MOD_UNIQUEID', 'app_plant');

class apps extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new app();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	/**
	 * 获取APP列表
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'condition' => $this->condition()
		);
		$app_info = $this->api->show($data);
		$app_client = $this->api->client();
		$app = array('info' => $app_info, 'client' => $app_client);
		$this->addItem($app);
		$this->output();
	}
	
	/**
	 * APP总数
	 */
	public function count()
	{
		$condition = $this->condition();
		$info = $this->api->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 修改APP状态
	 */
	public function update()
	{
		//TODO
	}
	
	/**
	 * 删除APP
	 */
	public function delete()
	{
		$id = trim(urldecode($this->input['id']));
		$id_arr = explode(',', $id);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		$app_info = $this->api->show(array('count' => -1, 'condition' => array('id' => $ids)));
		if (!$app_info) $this->errorOutput(PARAM_WRONG);
		$validate_ids = array();
		foreach ($app_info as $app)
		{
			$validate_ids[$app['id']] = $app['id'];
		}
		$validate_ids = implode(',', $validate_ids);
		//删除APP对应的引导图
		$this->api->delete('app_pic', array('app_id' => $validate_ids));
		//删除APP对应的模板属性值
		$this->api->delete('temp_value', array('app_id' => $validate_ids));
		//删除打包客户端关系
		$this->api->delete('client_relation', array('app_id' => $validate_ids));
		//删除打包记录
		$this->api->delete('publish_log', array('app_id' => $validate_ids));
		//删除缓存数据
		$this->api->delete('app_cache', array('app_id' => $validate_ids));
		//删除意见反馈
		$this->api->deleteFeedback($validate_ids);
		//删除模块
		include_once CUR_CONF_PATH . 'lib/appModule.class.php';
		$mod_api = new appModule();
		$mod_info = $mod_api->show(array('count' => -1, 'condition' => array('app_id' => $validate_ids)));
		if ($mod_info)
		{
			$mod_ids = array();
			foreach ($mod_info as $mod)
			{
				$mod_ids[$mod['id']] = $mod['id'];
			}
			$mod_ids = implode(',', $mod_ids);
			//删除模块对应的界面属性值
			$this->api->delete('ui_value', array('module_id' => $mod_ids));
			//删除关联模块
			$this->api->delete('app_module', array('app_id' => $validate_ids));
		}
		//删除APP
		$result = $this->api->delete('app_info', array('id' => $validate_ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 还原已废弃的APP
	 */
	public function recover()
	{
	    $id = trim(urldecode($this->input['id']));
		$id_arr = explode(',', $id);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		$result = $this->api->update('app_info', array('del' => 0), array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		$name = isset($this->input['k']) ? trim(urldecode($this->input['k'])) : '';
		$time = isset($this->input['date_search']) ? intval($this->input['date_search']) : '';
		$start_time = trim($this->input['start_time']);
		$end_time = trim($this->input['end_time']);
		$client_id = intval($this->input['c_id']);
		$data = array();
		if (!empty($name)) $data['keyword'] = $name;
		if ($start_time) $data['start_time'] = $start_time;
		if ($end_time) $data['end_time'] = $end_time;
		if ($time) $data['date_search'] = $time;
		if ($client_id > 0) $data['client_id'] = $client_id;
		return $data;
	}
}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

$out = new apps();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>