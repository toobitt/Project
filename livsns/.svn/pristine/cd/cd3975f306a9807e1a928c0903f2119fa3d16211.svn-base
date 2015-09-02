<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 应用客户端接口
 **************************************************************************/
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/appClient.class.php');
require_once(CUR_CONF_PATH . 'lib/app_version_mode.php');
require_once(CUR_CONF_PATH . 'lib/app_info_mode.php');
define('MOD_UNIQUEID', 'app_plant');

class app_client extends appCommonFrm
{
    private $api;
    private $version_mode;
    private $app_info_mode;
    public function __construct()
    {
        parent::__construct();
        $this->api = new appClient();
        $this->version_mode = new app_version_mode();
        $this->app_info_mode = new app_info_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
    }

    /**
     * 获取应用客户端类型的列表
     *
     * @access public
     * @param  offset | count
     * @return array
     */
    public function show()
    {
        $offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
        $count  = isset($this->input['count']) ? intval($this->input['count']) : 20;
        $data   = array(
			'offset'    => $offset,
			'count'     => $count,
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
     * 根据条件获取客户端类型的个数
     *
     * @access public
     * @param  无
     * @return array 例如：array('total' => 20)
     */
    public function count()
    {
        $condition = $this->condition();
        $info = $this->api->count($condition);
        echo json_encode($info);
    }

    /**
     * 根据某一个客户端类型详情
     *
     * @access public
     * @param  id:客户端类型id
     * @return array
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
     *
     * @access public
     * @param  $this->input
     * @return array
     */
    public function setting()
    {
        $client_id 			= $this->input['client'];//可以传多个,直接是数组
        $app_id 			= intval($this->input['app_id']);
        $android_majorVersionNum 	= intval($this->input['android_major_version_num']);//安卓主版本
        $android_minor_version_num 	= intval($this->input['android_minor_version_num']);//安卓子版本
        $ios_majorVersionNum 	= intval($this->input['ios_major_version_num']);//ios主版本
        $ios_minor_version_num 	= intval($this->input['ios_minor_version_num']);//ios子版本
        $changeLog 			= $this->input['change_log'];//版本说明
        $isRelease 			= $this->input['is_release'];//是否是发布版本
        $android_isDisplayBuild		= $this->input['android_is_display_build'];//安卓是否显示修正版本
        $ios_isDisplayBuild		= $this->input['ios_is_display_build'];//ios是否显示修正版本
        
        $appInfo = $this->app_info_mode->detail($app_id);
        
        //测试版安卓ios 都默认显示修正版本号
       	if(!$isRelease)
       	{
       		$android_isDisplayBuild = 1;
       		$ios_isDisplayBuild = 1;
       	}
        if(!$client_id)
        {
            $this->errorOutput(NO_CLIENT_TYPE);
        }

        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }

        //如果没有传主版本，默认是1
        if(!$android_majorVersionNum)
        {
            $android_majorVersionNum = 1;
        }
        if(!$ios_majorVersionNum)
        {
        	$ios_majorVersionNum = 1;
        }

        //如果主版本大于99就置为99
        if($android_majorVersionNum > 99)
        {
            $android_majorVersionNum = 99;
        }
        if($ios_majorVersionNum > 99)
        {
        	$ios_majorVersionNum = 99;
        }

        //如果子版本大于99就置为99
        if($android_minor_version_num > 99)
        {
            $android_minor_version_num = 99;
        }
        if($ios_minor_version_num > 99)
        {
        	$ios_minor_version_num = 99;
        }

        //如果显示修正版本 前两位判断可以等于前一个版本，如果不显示则必须大于前一个版本
        $android_curNum = $android_majorVersionNum * 100 + $android_minor_version_num;
        $ios_curNum = $ios_majorVersionNum * 100 + $ios_minor_version_num;

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
            if($v == 1)
            {
            	$curNum = $android_curNum; 
            	$isDisplayBuild = $android_isDisplayBuild;
            }
            elseif ($v == 2)
            {
            	$curNum = $ios_curNum;
            	$isDisplayBuild = $ios_isDisplayBuild;
            }
            if($preVersion)
            {
                $buildVersion = $preVersion['build_num'] + 1;
                $versionCode = $preVersion['version_code'] + 1;
            }
            	
            //判断的前提是有前一个版本
            if($preVersion)
            {
                $preNum = $preVersion['major_version_num'] * 100 + $preVersion['minor_version_num'];
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
            $package_name = '';
            foreach ($clientArr AS $k => $v)
            {
                if($v == 1)
                {
                	$majorVersionNum = $android_majorVersionNum;
                	$minor_version_num = $android_minor_version_num;
                	$package_name = $appInfo['android_package_name'];
                }
                elseif ($v == 2)
                {
                	$majorVersionNum = $ios_majorVersionNum;
                	$minor_version_num = $ios_minor_version_num;
                	$package_name = $appInfo['ios_package_name'];
                }
            	$versionData = array(
					'app_id' 				=> $app_id,
					'client_type' 			=> $v,
					'major_version_num' 	=> $majorVersionNum,
					'minor_version_num' 	=> $minor_version_num,
					'build_num' 			=> $newVersion[$v]['build'],
					'version_code' 			=> $newVersion[$v]['version_code'],
					'change_log' 			=> $changeLog,
					'is_release' 			=> $isRelease,
					'package_name' 			=> $package_name,
				    'is_display_build'      => $isDisplayBuild,
					'create_time'			=> TIMENOW,
                );
                $this->version_mode->create($versionData);
            }
            
            //记录app_info表中的打包时间
            $this->app_info_mode->update($app_id,array('pack_time' => TIMENOW));
        }

        $this->addItem(array('return' => 1));
        $this->output();
    }

    /**
     * 检查更新
     *
     * @access public
     * @param  app_id:应用id
     * 		   client_type:客户端类型 INT
     * 		   version_name:版本名称
     *         is_release:是否是发布版本
     *
     * @return array
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
        $new_version_name_arr = explode('.',$new_version['version_name']);
        $new_version_count = count($new_version_name_arr);
        $new_version_num = $new_version['major_version_num'] .'.'.$new_version['minor_version_num'];
        $ret = false;
        if($version_num < 2 || $version_num >3)
        {
            $this->errorOutput(VERSION_NUM_ERROR);
        }
        else if($version_num == 2 && $new_version_count == 2)
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
     *
     * @access public
     * @param  id:版本id
     * 		   queue_id:队列id
     *
     * @return array
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
     * 获取查询条件
     *
     * @access private
     * @param  无
     * @return array
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