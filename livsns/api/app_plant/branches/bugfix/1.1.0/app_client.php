<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/appClient.class.php');
require_once(CUR_CONF_PATH . 'lib/app_version_mode.php');
define('MOD_UNIQUEID', 'app_plant');

class app_client extends appCommonFrm
{
	private $api;
	private $version_mode;
	public function __construct()
	{
		parent::__construct();
		$this->api = new appClient();
		$this->version_mode = new app_version_mode();
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
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
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
		$client_id 			= $this->input['client'];//可以传多个,直接是数组
		$app_id 			= intval($this->input['app_id']);
		$majorVersionNum 	= intval($this->input['major_version_num']);//主版本
		$minor_version_num 	= intval($this->input['minor_version_num']);//子版本
		$changeLog 			= $this->input['change_log'];//版本说明
		$isRelease 			= $this->input['is_release'];//是否是发布版本
		$isDisplayBuild		= $this->input['is_display_build'];//是否显示修正版本

		if(!$client_id)
		{
			$this->errorOutput(NO_CLIENT_TYPE);
		}
		
		if(!$app_id)
		{
			$this->errorOutput(NO_APP_ID);
		}
		
		//如果没有传主版本，默认是1
		if(!$majorVersionNum)
		{
			$majorVersionNum = 1;
		}
		
		//如果主版本大于99就置为99
		if($majorVersionNum > 99)
		{
			$majorVersionNum = 99;
		}
		
		//如果子版本大于99就置为99
		if($minor_version_num > 99)
		{
			$minor_version_num = 99;
		}
		
		//如果显示修正版本 前两位判断可以等于前一个版本，如果不显示则必须大于前一个版本
		$curNum = $majorVersionNum * 10 + $minor_version_num;
		
		//查询出所有客户端信息
		$clientInfo = $this->api->show(array('count' => -1));
		if(!$clientInfo)
		{
			$this->errorOutput(CLIENT_INFO_NOT_EXISTS);
		}
		
		$clientMark = array();
		foreach ($clientInfo AS $k => $v)
		{
			$clientMark[$v['id']] = $v['mark'];
		}

		$clientArr = $client_id;
		$newVersion = array();//存放当前应该更新的版本
		$output = array();//存放输出信息
		
		foreach ($clientArr AS $k => $v)
		{
			//查询出该应用前一个最新的版本
			$cond = " AND app_id = '" .$app_id. "' AND client_type = '" . $v . "' ";
			$preVersion = $this->version_mode->getNewestVersion($cond);
			$buildVersion = 0;
			$versionCode = 1;
			if($preVersion)
			{
				$buildVersion = $preVersion['build_num'] + 1;
				$versionCode = $preVersion['version_code'] + 1;
			}
			
			//判断的前提是有前一个版本
			if($preVersion)
			{
				$preNum = $preVersion['major_version_num'] * 10 + $preVersion['minor_version_num'];
				//如果显示修正版本 前两位判断可以等于前一个版本，如果不显示则必须大于前一个版本
				if($isDisplayBuild)
				{
					if($curNum < $preNum)
					{
						$this->errorOutput(CUR_VERSION_TOO_LOW);
						break;
					}
				}
				else 
				{
					if($curNum <= $preNum)
					{
						$this->errorOutput(CUR_VERSION_TOO_LOW);
						break;
					}
				}
			}

			$newVersion[$v] = array(
				'build' 		=> $buildVersion,
				'version_code' 	=> $versionCode,
			);
		}

		//创建一个版本信息
		if($newVersion)
		{
			foreach ($clientArr AS $k => $v)
			{
				$versionData = array(
					'app_id' 				=> $app_id,
					'client_type' 			=> $v,
					'major_version_num' 	=> $majorVersionNum,
					'minor_version_num' 	=> $minor_version_num,
					'build_num' 			=> $newVersion[$v]['build'],
					'version_code' 			=> $newVersion[$v]['version_code'],
					'change_log' 			=> $changeLog,
					'is_release' 			=> $isRelease,
					'package_name' 			=> $this->settings['package'][$clientMark[$v]] . $app_id,
				    'is_display_build'      => $isDisplayBuild,
					'create_time'			=> TIMENOW,
				);
				$this->version_mode->create($versionData);
			}
		}
		
		$this->addItem(array('return' => 1));
		$this->output();
	}
	
	/**
	 * 检查更新
	 */
	public function checkUpdate()
	{
		$app_id 		= intval($this->input['app_id']);
		$client_type 	= intval($this->input['client_type']);
		$version_name	= $this->input['version_name'];
		$is_release		= intval($this->input['is_release']);
		
		if(!$app_id)
		{
			$this->errorOutput(NO_APP_ID);
		}
		
		if(!$client_type)
		{
			$this->errorOutput(NO_VERSION_ID);
		}
		
		if(!$version_name)
		{
			$this->errorOutput(NO_VERSION_NUM);
		}
		
		//查询出当前的最新版本
		$cond = " AND a.app_id = '" .$app_id. "' AND a.client_type = '" .$client_type. "' AND a.is_release = '" .$is_release. "' ";
		$new_version = $this->version_mode->getNewestVersion($cond);
		if(!$new_version)
		{
			$this->errorOutput(NO_VERSION_INFO);
		}
		
		$version_name_arr = explode('.',$version_name);
		$version_num = count($version_name_arr);
		$new_version_num = $new_version['major_version_num'] .'.'.$new_version['minor_version_num'];
		$ret = false;
		if($version_num < 2 || $version_num >3)
		{
			$this->errorOutput(VERSION_NUM_ERROR);
		}
		else if($version_num == 2)
		{
			$ret = version_compare($version_name,$new_version_num,'<');
		}
		else
		{
			$ret = version_compare($version_name,$new_version_num .'.'.$new_version['build_num'] ,'<');
		}
		
		if($ret)
		{
			foreach ($new_version AS $k => $v)
			{
				$this->addItem_withkey($k, $v);
			}
		}
		else
		{
			$this->addItem_withkey('return', 0);
		}
		$this->output();
	}
	
	/**
	 * 更新打包回调的队列id
	 */
	public function updateClientRelation()
	{
	    $id = intval($this->input['id']);
	    $queue_id = intval($this->input['queue_id']);
	    if(!$id)
	    {
	    	$this->errorOutput(NO_VERSION_ID);
	    }
	    
	    if(!$queue_id)
	    {
	    	$this->errorOutput(NO_QUEUE_ID);
	    }

	    $data = array('queue_id' => $queue_id);
	    $result = $this->version_mode->update($id, $data);
	    if($result)
	    {
	    	$this->addItem(array('return' => 1));
	    }
	    else 
	    {
	    	$this->addItem(array('return' => 0));
	    }
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

$out = new app_client();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>