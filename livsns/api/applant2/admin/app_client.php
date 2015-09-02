<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/appClient.class.php';
define('MOD_UNIQUEID', 'app_plant');

class app_client extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new appClient();
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
		$appClient_info = $this->api->show($data);
		$this->setXmlNode('appClient_info', 'client');
		if ($appClient_info)
		{
			foreach ($appClient_info as $client)
			{
				$this->addItem($client);
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
		$appClient_info = $this->api->detail('app_client', $data);
		$this->addItem($appClient_info);
		$this->output();
	}
	
	/**
	 * 创建数据
	 */
	public function create()
	{
		$data = $this->filter_data();
		//名称是否重复
		$check = $this->api->verify(array('name' => $data['name']));
		if ($check > 0) $this->errorOutput(NAME_REPEAT);
		//标识是否重复
		$check = $this->api->verify(array('mark' => $data['mark']));
		if ($check > 0) $this->errorOutput(MARK_EXISTS);
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		$data['org_id'] = $this->user['org_id'];
		$data['create_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		$result = $this->api->create('app_client', $data);
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
		$appClient_info = $this->api->detail('app_client', array('id' => $id));
		if (!$appClient_info) $this->errorOutput(PARAM_WRONG);
		$data = $this->filter_data();
		$validate = array();
		if ($appClient_info['name'] != $data['name'])
		{
			//名称是否重复
			$check = $this->api->verify(array('name' => $data['name']));
			if ($check > 0) $this->errorOutput(NAME_REPEAT);
			$validate['name'] = $data['name'];
		}
		if ($appClient_info['mark'] != $data['mark'])
		{
			//标识是否重复
			$check = $this->api->verify(array('mark' => $data['mark']));
			if ($check > 0) $this->errorOutput(MARK_EXISTS);
			$validate['mark'] = $data['mark'];
		}
		if ($appClient_info['url'] != $data['url'])
		{
			$validate['url'] = $data['url'];
		}
		if ($validate)
		{
			$result = $this->api->update('app_client', $validate, array('id' => $id));
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
		$relation_info = $this->api->detail('client_relation', array('client_id' => $ids));
		if ($relation_info) $this->errorOutput(PARAM_WRONG);
		$result = $this->api->delete('app_client', array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$client_name = trim(urldecode($this->input['client_name']));
		$client_mark = trim(urldecode($this->input['client_mark']));
		$client_url = trim(urldecode($this->input['client_url']));
		if (empty($client_name) || empty($client_mark))
		{
		    $this->errorOutput(PARAM_WRONG);
		}
		$data = array(
			'name' => $client_name,
		    'mark' => $client_mark,
			'url' => $client_url
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

$out = new app_client();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>