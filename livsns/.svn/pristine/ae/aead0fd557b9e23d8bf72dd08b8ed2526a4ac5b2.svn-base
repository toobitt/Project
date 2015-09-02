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
	 * 设置打包的客户端信息
	 */
	public function setting()
	{
		$client_id = $this->input['client'];
		$app_id = intval($this->input['app_id']);
		$id_arr = array_filter($client_id, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		$client_info = $this->api->show(array('count' => -1, 'condition' => array('id' => $ids)));
		if (!$client_info) $this->errorOutput(PARAM_WRONG);
		$client_ids = $clientInfo = array();
		foreach ($client_info as $v)
		{
		    $client_ids[$v['id']] = $v['id'];
		    $clientInfo[$v['id']] = $v;
		}
		$queryData = array(
		    'id' => $app_id,
		    'user_id' => $this->user['user_id'],
		    'del' => 0
		);
		$app_info = $this->api->detail('app_info', $queryData);
		if (!$app_info) $this->errorOutput(NO_APPID);
		$relation_info = $this->api->get_client_relation(array('app_id' => $app_id));
		if ($relation_info)
		{
		    $relation_ids = $relation_valid_ids = array();
		    foreach ($relation_info as $relation)
		    {
		        if ($relation['flag'] == 1)
		        {
		            $relation_valid_ids[$relation['client_id']] = $relation['client_id'];
		        }
		        $relation_ids[$relation['client_id']] = $relation['client_id'];
		    }
		    $delete_ids = array_diff($relation_valid_ids, $client_ids);
			$insert_ids = array_diff($client_ids, $relation_ids);
			$update_ids = array_diff($client_ids, $relation_valid_ids);
		}
		else
		{
		    $insert_ids = $client_ids;
		}
		if ($delete_ids)
		{
		    $delData = array(
		        'client_id' =>  implode(',', $delete_ids),
		        'app_id' => $app_id
		    );
		    $result = $this->api->update('client_relation', array('flag' => 0), $delData);
		}
		if ($insert_ids)
		{
    		foreach ($insert_ids as $v)
    		{
    		    $clientType = $clientInfo[$v]['mark'];
    		    $bundle_id = $this->settings['package'][$clientType] . $app_id;
    			$insertData = array(
    				'app_id' => $app_id,
    				'client_id' => $v,
    				'state' => 0,
    			    'package' => $bundle_id
    			);
    			$result = $this->api->create('client_relation', $insertData);
    		}
		}
		if ($update_ids)
		{
		    $updateData = array(
		        'client_id' =>  implode(',', $update_ids),
		        'app_id' => $app_id
		    );
		    $result = $this->api->update('client_relation', array('flag' => 1), $updateData);
		}
		if (!$result) $result = true;
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 检查更新
	 */
	public function checkUpdate()
	{
	    $app_id = intval($this->input['appId']);
	    $client_id = intval($this->input['clientId']);
	    if ($app_id <= 0 || $client_id <= 0)
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    //先检测APP是否存在
	    $app_info = $this->api->detail('app_info', array('id' => $app_id, 'del' => 0));
	    if (!$app_info) $this->errorOutput(NO_APPID);
	    $client_info = $this->api->detail('app_client', array('id' => $client_id));
	    if (!$client_info) $this->errorOutput(PARAM_WRONG);
	    $result = $this->api->check($app_id, $client_id);
	    $result['mark'] = $client_info['mark'];
	    if ($result['version_name'])
	    {
	        $result['version_name'] = getVersionName($result['version_name']);
	    }
	    $this->addItem($result);
		$this->output();
	}
	
	/**
	 * 更新打包回调的队列id
	 */
	public function updateClientRelation()
	{
	    $app_id = intval($this->input['app_id']);
	    $client_id = intval($this->input['client_id']);
	    $queue_id = intval($this->input['queue_id']);
	    if ($app_id <= 0 || $client_id <= 0 || $queue_id <= 0)
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    $data = array('queue_id' => $queue_id);
	    $condition = array(
	        'app_id' => $app_id,
	        'client_id' => $client_id
	    );
	    $result = $this->api->update('client_relation', $data, $condition);
	    $this->addItem($result);
	    $this->output();
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		return array();
	}
}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

/**
 * 转换版本信息
 * @param Integer $version  版本号
 * @param Boolean $flag  是否更新
 */
function getVersionName($version)
{
    $version = intval($version);
    $arr = array();
    for ($i = strlen($version); $i--;) {
    	$arr[$i] = substr($version, $i, 1);
    }
    ksort($arr);
    return implode('.', $arr);
}

$out = new app_client();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>