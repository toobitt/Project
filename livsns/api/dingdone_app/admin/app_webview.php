<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/webView.class.php';
define('MOD_UNIQUEID', 'dingdone_app');

class app_webview extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new webView();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	/**
	 * 显示数据
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
		$webview_info = $this->api->show($data);
		$this->setXmlNode('webview_info', 'webview');
		if ($webview_info)
		{
			foreach ($webview_info as $webview)
			{
				$this->addItem($webview);
			}
		}
		$this->output();
	}
	
	/**
	 * 数据总数
	 */
	public function count()
	{
		$condition = $this->condition();
		$info = $this->api->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 单个数据
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$data = array('id' => $id);
		$webview_info = $this->api->detail('app_webview', $data);
		$this->addItem($webview_info);
		$this->output();
	}
	
	/**
	 * 创建数据
	 */
	public function create()
	{
		$data = $this->filter_data();
		//是否重名
		$check = $this->api->verify(array('name' => $data['name']));
		if ($check > 0) $this->errorOutput(NAME_EXISTS);
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		$data['org_id'] = $this->user['org_id'];
		$data['create_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		$result = $this->api->create('app_webview', $data);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 更新数据
	 */
	public function update()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$webview_info = $this->api->detail('app_webview', array('id' => $id));
		if (!$webview_info) $this->errorOutput(PARAM_WRONG);
		$data = $this->filter_data();
		$validate = array();
		if ($webview_info['name'] != $data['name'])
		{
			//是否重名
			$check = $this->api->verify(array('name' => $data['name']));
			if ($check > 0) $this->errorOutput(NAME_EXISTS);
			$validate['name'] = $data['name'];
		}
		if ($webview_info['brief'] != $data['brief'])
		{
		    $validate['brief'] = $data['brief'];
		}
		if ($webview_info['url'] != $data['url'])
		{
			$validate['url'] = $data['url'];
		}
		if ($validate)
		{
			$result = $this->api->update('app_webview', $validate, array('id' => $id));
		}
		else
		{
		    $result = true;
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 删除数据
	 */
	public function delete()
	{
		$id = trim(urldecode($this->input['id']));
		$id_arr = explode(',', $id);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		$this->api->update('app_module', array('web_url' => ''), array('web_view' => $ids));
		$result = $this->api->delete('app_webview', array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$module_name = trim(urldecode($this->input['module_name']));
		$module_brief = trim(urldecode($this->input['module_brief']));
		$module_url = trim(urldecode($this->input['module_url']));
		if (empty($module_name) || empty($module_url))
		{
		    $this->errorOutput(PARAM_WRONG);
		}
		$data = array(
			'name' => $module_name,
		    'brief' => $module_brief,
			'url' => $module_url
		);
		return $data;
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		$keyword = trim(urldecode($this->input['k']));
		return array(
			'keyword' => $keyword
		);
	}
}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

$out = new app_webview();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>