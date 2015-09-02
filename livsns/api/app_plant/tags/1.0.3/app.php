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
	private $material;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new app();
		$this->material = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
		unset($this->material);
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
		$this->setXmlNode('app_info', 'app');
		if ($app_info)
		{
			foreach ($app_info as $app)
			{
				$this->addItem($app);
			}
		}
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
	 * 获取单个APP信息
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$flag = intval($this->input['flag']);
		$queryData = array('id' => $id, 'del' => 0);
		if (!$flag) $queryData['user_id'] = $this->user['user_id'];
		$app_info = $this->api->detail('app_info', $queryData);
		if ($app_info)
		{
			if (unserialize($app_info['icon']))
			{
				$app_info['icon'] = unserialize($app_info['icon']);
			}
			if (unserialize($app_info['startup_pic']))
			{
				$app_info['startup_pic'] = unserialize($app_info['startup_pic']);
			}
			if ($app_info['startup_pic2'] && unserialize($app_info['startup_pic2']))
			{
				$app_info['startup_pic2'] = unserialize($app_info['startup_pic2']);
			}
			if ($app_info['startup_pic3'] && unserialize($app_info['startup_pic3']))
			{
				$app_info['startup_pic3'] = unserialize($app_info['startup_pic3']);
			}
			//获取APP引导图
			$queryData = array(
			    'app_id' => $app_info['id']
			);
			$guide_pic = $this->api->app_pic($queryData, true);
			if ($guide_pic) $app_info['guide_pic'] = $guide_pic;
		}
		$this->addItem($app_info);
		$this->output();
	}
	
	public function getAppByUUID()
	{
	    $uuid = trim($this->input['id']);
	    if (empty($uuid))
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    $queryData = array(
	        'uuid' => $uuid,
	        'del' => 0
	    );
	    $app_info = $this->api->detail('app_info', $queryData);
	    if ($app_info)
		{
			if (unserialize($app_info['icon']))
			{
				$app_info['icon'] = unserialize($app_info['icon']);
			}
			if (unserialize($app_info['startup_pic']))
			{
				$app_info['startup_pic'] = unserialize($app_info['startup_pic']);
			}
		}
		$this->addItem($app_info);
		$this->output();
	}
	
	/**
	 * 二维码扫描下载APP的打包文件
	 */
	public function getAppClientInfo()
	{
	    $uuid = trim($this->input['uuid']);
	    $type = trim($this->input['client_type']);
	    if (empty($uuid) || empty($type))
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    $result = $this->api->detail('app_client', array('mark' => $type));
	    if (!$result) $this->errorOutput(PARAM_WRONG);
	    //验证APP是否存在
	    $queryData = array(
	        'uuid' => $uuid,
	        'del' => 0
	    );
	    $app_info = $this->api->detail('app_info', $queryData);
	    if (!$app_info) $this->errorOutput(NO_APPID);
	    //获取对应客户端的信息
	    $queryData = array(
	        'app_id' => $app_info['id'],
	        'client_id' => $result['id']
	    );
	    $client_info = $this->api->detail('client_relation', $queryData);
	    if ($client_info && $client_info['state'] <= 0)
	    {
	        //获取上次历史版本信息
	        $history = $this->api->lastVersion($app_info['id'], $result['id']);
	        if ($history) $client_info['history'] = $history;
	    }
	    $this->addItem($client_info);
	    $this->output();
	}
	
	public function global_config()
	{
	    if ($this->settings['pic_type'])
	    {
	        $image_type = array();
	        foreach ($this->settings['pic_type'] as $v)
	        {
	            $image_type[] = $this->settings['image_type'][$v];
	        }
	    }
	    $data = array(
	        'icon_size' => $this->settings['icon_size']['max_size'],
	        'startup_size' => $this->settings['startup_size']['max_size'],
	        'guide_size' => $this->settings['guide_size']['max_size'],
	        'module_size' => $this->settings['module_size']['max_size'],
	        'navBarTitle_size' => $this->settings['navBarTitle_size']['max_size'],
	        'magazine_size' => $this->settings['magazine_size']['max_size'],
	        'image_type' => $image_type,
	        'guide_limit' => GUIDE_LIMIT,
	        'module_name_limit' => MODULE_NAME_LIMIT,
	        'data_url' => $this->settings['data_url'],
	        'is_replace' => IS_REPLACE,
	        'replace_img_domain' => REPLACE_IMG_DOMAIN,
	        'module_limit' => MODULE_LIMIT_NUM,
	        'app_effect' => $this->settings['app_effect'],
	        'text_size' => $this->settings['cpTextSize'],
	        'text_color' => $this->settings['cpTextColor'],
	        'guide_effect' => $this->settings['guideEffect'],
	        'guide_animation' => $this->settings['guideAnimation'],
	        'shape_sign' => $this->settings['shapeSign'],
	        'sign_default_color' => $this->settings['signDefaultColor'],
	        'sign_select_color' => $this->settings['signSelectedColor']
	    );
	    if (isset($this->settings['vip_user']))
	    {
	        if (in_array($this->user['user_name'], $this->settings['vip_user']))
	        {
	            $data['module_limit'] = 15;
	        }
	    }
	    $this->addItem($data);
	    $this->output();
	}
	
	/**
	 * 根据队列id获取打包数据和APP名
	 */
	public function getInfoByQueueId()
	{
	    $queue_id = intval($this->input['queue_id']);
	    $clientRelation = $this->api->detail('client_relation', array('queue_id' => $queue_id, 'flag' => 1));
	    if (!$clientRelation) $this->errorOutput(PARAM_WRONG);
	    $clientInfo = $this->api->detail('app_client', array('id' => $clientRelation['client_id']));
	    if (!$clientInfo) $this->errorOutput(PARAM_WRONG);
	    $appInfo = $this->api->detail('app_info', array('id' => $clientRelation['app_id'], 'del' => 0));
	    if (!$appInfo) $this->errorOutput(NO_APPID);
	    $clientRelation['app_name'] = $appInfo['name'];
	    if ($clientRelation['version_name'])
		{
		    $clientRelation['version_name'] = getVersionName($clientRelation['version_name']);
		}
		$clientRelation['mark'] = $clientInfo['mark'];
	    $this->addItem($clientRelation);
	    $this->output();
	}
	
	/**
	 * 获取APP与客户端的对应信息
	 * @param Array $data
	 */
	public function app_client($data = array())
	{
	    $app_id = isset($data['app_id']) ? intval($data['app_id']) : intval($this->input['appId']);
		$client_id = isset($data['client_id']) ? intval($data['client_id']) : intval($this->input['clientId']);
		if ($app_id <=0 || $client_id <= 0)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$ret = $this->api->detail('app_info', array('id' => $app_id, 'del' => 0));
		if (!$ret) $this->errorOutput(NO_APPID);
		$where = array(
			'app_id' => $app_id,
			'client_id' => $client_id,
		    'flag' => 1
		);
		$info = $this->api->detail('client_relation', $where);
		if (!$info) $this->errorOutput(PARAM_WRONG);
		$clientInfo = $this->api->detail('app_client', array('id' => $client_id));
		if (!$clientInfo) $this->errorOutput(PARAM_WRONG);
		$info['app_name'] = $ret['name'];
		$info['mark'] = $clientInfo['mark'];
		$info['user_id'] = $ret['user_id'];
		if ($data) return $info;
		if ($info['version_name'])
		{
		    $info['version_name'] = getVersionName($info['version_name']);
		}
	    $this->addItem($info);
	    $this->output();
	}
	
	/**
	 * 更新APP对应的打包客户端数据
	 */
	public function update_client()
	{
		$app_id = intval($this->input['appId']);
		$client_id = intval($this->input['clientId']);
		$queue_id = intval($this->input['queueId']);
		if ($queue_id > 0)
		{
		    $queryData = array(
    		    'queue_id' => $queue_id,
    		    'flag' => 1
    		);
    		$info = $this->api->detail('client_relation', $queryData);
		}
		else
		{
		    $info = $this->app_client(array('app_id' => $app_id, 'client_id' => $client_id));
		}
		if (!$info) $this->errorOutput(PARAM_WRONG);
		$state = intval($this->input['status']);
		$file_url = trim($this->input['fileUrl']);
		$download_url = trim($this->input['downloadUrl']);
		$file_size = intval($this->input['fileSize']);
		$data = array();
		if ($info['state'] != $state) $data['state'] = $state;
		if ($file_url && $info['file_url'] != $file_url)
		{
		    $data['file_url'] = $file_url;
		}
		if ($download_url && $info['download_url'] != $download_url)
		{
		    $data['download_url'] = $download_url;
		}
		if ($file_size && $info['file_size'] != $file_size)
		{
		    $data['file_size'] = $file_size;
		}
		if ($queue_id > 0)
		{
		    $where = array('queue_id' => $queue_id);
		}
		else
		{
    		$where = array(
    			'app_id' => $app_id,
    			'client_id' => $client_id
    		);
		}
		if ($data)
		{
		    $data['publish_time'] = TIMENOW;
		    $result = $this->api->update('client_relation', $data, $where);
		}
		else
		{
		    $result = true;
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 更新推送状态
	 */
	public function updatePushStatus()
	{
	    if (isset($this->input['queue_id']))
	    {
	        $queue_id = intval($this->input['queue_id']);
	        if ($queue_id <= 0) $this->errorOutput(PARAM_WRONG);
	        $queryData = array(
	        	'queue_id' => $queue_id
	        );
	    }
	    else
	    {
	        $app_id = intval($this->input['app_id']);
	        $client_id = intval($this->input['client_id']);
    	    if ($app_id <= 0 || $client_id <= 0)
    	    {
    	        $this->errorOutput(PARAM_WRONG);
    	    }
    	    $queryData = array(
	        	'app_id' => $app_id,
    	        'client_id' => $client_id
	        );
	    }
	    $queryData['flag'] = 1;
    	$info = $this->api->detail('client_relation', $queryData);
    	if (!$info) $this->errorOutput(PARAM_WRONG);
    	$result = $this->api->update('client_relation', array('push' => 1), $queryData);
    	$this->addItem($result);
    	$this->output();
	}
	
	/**
	 * 检测是否超过限定APP创建个数
	 */
	public function limitNum()
	{
	    $result = $this->getLimit();
	    $this->addItem($result);
	    $this->output();
	}
	
	private function getLimit()
	{
	    $queryData = array(
	        'uid' => $this->user['user_id'],
	        'del' => 0
	    );
	    $nums = $this->api->count($queryData);
	    return $nums['total'] >= APP_LIMIT_NUM ? true : false;
	}
	
	/**
	 * 创建APP
	 */
	public function create()
	{
		$data = $this->filter_data();
		//验证创建的个数是否达到上限
	    if ($this->getLimit())
	    {
	        $this->errorOutput(OVER_LIMIT);
	    }
		/*
		$check = $this->api->verify(array('name' => $data['name'],'del'=> 0));
		if ($check > 0) $this->errorOutput(NAME_EXISTS);
		*/
		//APP图标
		$app_icon_info = $this->material->get_material_by_ids($data['icon']);
		if (!$app_icon_info[0]) $this->errorOutput(APP_ICON_ERROR);
		$data['icon'] = serialize($app_icon_info[0]);
		
		//APP启动画面
		if ($data['startup_pic'] <= 0)
		{
		    $this->errorOutput(APP_STARTPIC_ERROR);
		}
	    $app_start_pic_info = $this->material->get_material_by_ids($data['startup_pic']);
	    if (!$app_start_pic_info[0]) $this->errorOutput(APP_STARTPIC_ERROR);
	    $data['startup_pic'] = serialize($app_start_pic_info[0]);
	    
	    //启动图(iOS两种尺寸图)
	    if ($data['startup_pic2'] > 0)
	    {
	        $start_pic2_info = $this->material->get_material_by_ids($data['startup_pic2']);
    	    if (!$start_pic2_info[0]) $this->errorOutput(APP_STARTPIC_ERROR);
    	    $data['startup_pic2'] = serialize($start_pic2_info[0]);
    	}
	    if ($data['startup_pic3'] > 0)
	    {
	        $start_pic3_info = $this->material->get_material_by_ids($data['startup_pic3']);
    	    if (!$start_pic3_info[0]) $this->errorOutput(APP_STARTPIC_ERROR);
    	    $data['startup_pic3'] = serialize($start_pic3_info[0]);
	    }
	    
		//引导前景图
		if ($this->input['guideForwardground'])
		{
		    $pic_id = trim(urldecode($this->input['guideForwardground']));
    		$id_arr = explode(',', $pic_id);
    		$id_arr = array_filter($id_arr, 'filter_arr');
    		if ($id_arr)
    		{
    		    //判断是否超过上限
    		    if (count($id_arr) > GUIDE_LIMIT) $this->errorOutput(OVER_LIMIT);
    		    $pic_id = implode(',', $id_arr);
    		    //判断是否存在并没有被使用
    		    $info = $this->api->app_pic(array('app_id' => 0, 'id' => $pic_id, 'type' => 1));
    		    if (!$info) $this->errorOutput(PARAM_WRONG);
		        $fg_ids = array();
		        foreach ($info as $v)
		        {
		            $fg_ids[$v['id']] = $v['id'];
		        }
		        $fg_ids = implode(',', $fg_ids);
    		}
		}
		
		//引导背景图
		if ($this->input['guideBackground'])
		{
		    $pic_id = trim(urldecode($this->input['guideBackground']));
    		$id_arr = explode(',', $pic_id);
    		$id_arr = array_filter($id_arr, 'filter_arr');
    		if ($id_arr)
    		{
    		    //判断是否超过上限
    		    if (count($id_arr) > GUIDE_LIMIT) $this->errorOutput(OVER_LIMIT);
    		    $pic_id = implode(',', $id_arr);
    		    //判断是否存在并没有被使用
    		    $info = $this->api->app_pic(array('app_id' => 0, 'id' => $pic_id, 'type' => 2));
    		    if (!$info) $this->errorOutput(PARAM_WRONG);
		        $bg_ids = array();
		        foreach ($info as $v)
		        {
		            $bg_ids[$v['id']] = $v['id'];
		        }
		        $bg_ids = implode(',', $bg_ids);
    		}
		}
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		$data['org_id'] = $this->user['org_id'];
		$data['create_time'] = TIMENOW;
		$data['update_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		$data['uuid'] = 'uuid';
		$result = $this->api->create('app_info', $data);
		if ($fg_ids)
		{
		    $updateData = array(
		    	'app_id' => $result['id'],
		        'type' => 1,
		        'effect' => $data['guide_effect']
		    );
		    $this->api->update('app_pic', $updateData, array('id' => $fg_ids));
		}
		if ($bg_ids)
		{
		    $updateData = array(
		    	'app_id' => $result['id'],
		        'type' => 2,
		        'effect' => $data['guide_effect']
		    );
		    $this->api->update('app_pic', $updateData, array('id' => $bg_ids));
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 更新APP
	 */
	public function update()
	{
		$app_id = intval($this->input['id']);
		if ($app_id <= 0) $this->errorOutput(PARAM_WRONG);
		$queryData = array(
			'id' => $app_id,
			'user_id' => $this->user['user_id'],
		    'del' => 0
		);
		$app_info = $this->api->detail('app_info', $queryData);
		if (!$app_info) $this->errorOutput(NO_APPID);
		$data = $this->filter_data();
		$validate = array();
		if ($app_info['name'] != $data['name'])
		{
		    /*
		    $check = $this->api->verify(array('name' => $data['name']));
		    if ($check > 0) $this->errorOutput(NAME_EXISTS);
		    */
			$validate['name'] = $data['name'];
		}
		if ($app_info['brief'] != $data['brief'])
		{
		    $validate['brief'] = $data['brief'];
		}
		if ($data['temp_id'] > 0 && $app_info['temp_id'] != $data['temp_id'])
		{
			$validate['temp_id'] = $data['temp_id'];
		}
		if ($app_info['copyright'] != $data['copyright'])
		{
		    $validate['copyright'] = $data['copyright'];
		}
		if ($app_info['effect'] != $data['effect'])
		{
		    $validate['effect'] = $data['effect'];
		}
		if ($app_info['text_size'] != $data['text_size'])
		{
		    $validate['text_size'] = $data['text_size'];
		}
		if ($app_info['text_color'] != $data['text_color'])
		{
		    $validate['text_color'] = $data['text_color'];
		}
		if ($app_info['guide_effect'] != $data['guide_effect'])
		{
		    $validate['guide_effect'] = $data['guide_effect'];
		}
		if ($app_info['guide_animation'] != $data['guide_animation'])
		{
		    $validate['guide_animation'] = $data['guide_animation'];
		}
		if ($app_info['guide_sign'] != $data['guide_sign'])
		{
		    $validate['guide_sign'] = $data['guide_sign'];
		}
		if ($app_info['guide_default_color'] != $data['guide_default_color'])
		{
		    $validate['guide_default_color'] = $data['guide_default_color'];
		}
		if ($app_info['guide_select_color'] != $data['guide_select_color'])
		{
		    $validate['guide_select_color'] = $data['guide_select_color'];
		}
		if ($app_info['icon_type'] != $data['icon_type'])
		{
		    $validate['icon_type'] = $data['icon_type'];
		}
		if ($app_info['startup_type'] != $data['startup_type'])
		{
		    $validate['startup_type'] = $data['startup_type'];
		}
		//APP图标
		if ($data['icon'] > 0)
		{
    		$app_icon_info = $this->material->get_material_by_ids($data['icon']);
    		if (!$app_icon_info[0]) $this->errorOutput(APP_ICON_ERROR);
    		$app_icon_info = serialize($app_icon_info[0]);
    		if ($app_icon_info != $app_info['icon'])
    		{
    		    $validate['icon'] = $app_icon_info;
    		}
		}
		//APP启动画面
		if ($data['startup_pic'] > 0)
		{
    		$app_start_pic_info = $this->material->get_material_by_ids($data['startup_pic']);
    		if (!$app_start_pic_info[0]) $this->errorOutput(APP_STARTPIC_ERROR);
    		$data['startup_pic'] = serialize($app_start_pic_info[0]);
    		if ($data['startup_pic'] != $app_info['startup_pic'])
    		{
    		    $validate['startup_pic'] = $data['startup_pic'];
    		}
		}
		//启动图(iOS两种尺寸图)
	    if ($data['startup_pic2'] > 0)
	    {
	        $start_pic2_info = $this->material->get_material_by_ids($data['startup_pic2']);
    	    if (!$start_pic2_info[0]) $this->errorOutput(APP_STARTPIC_ERROR);
    	    $data['startup_pic2'] = serialize($start_pic2_info[0]);
    	    if ($data['startup_pic2'] != $app_info['startup_pic2'])
    		{
    		    $validate['startup_pic2'] = $data['startup_pic2'];
    		}
    	}
    	else
    	{
    	    $validate['startup_pic2'] = '';
    	}
	    if ($data['startup_pic3'] > 0)
	    {
	        $start_pic3_info = $this->material->get_material_by_ids($data['startup_pic3']);
    	    if (!$start_pic3_info[0]) $this->errorOutput(APP_STARTPIC_ERROR);
    	    $data['startup_pic3'] = serialize($start_pic3_info[0]);
    	    if ($data['startup_pic3'] != $app_info['startup_pic3'])
    		{
    		    $validate['startup_pic3'] = $data['startup_pic3'];
    		}
	    }
	    else
	    {
	        $validate['startup_pic3'] = '';
	    }
	    $guide_effect = $validate['guide_effect'] ? $validate['guide_effect'] : $app_info['guide_effect'];
		//更新引导图前景图
		if ($this->input['guideForwardground'])
		{
		    $pic_id = trim(urldecode($this->input['guideForwardground']));
    		$id_arr = explode(',', $pic_id);
    		$id_arr = array_filter($id_arr, 'filter_arr');
    		if ($id_arr)
    		{
    		    if (count($id_arr) > GUIDE_LIMIT)
    		    {
    		        $this->errorOutput(OVER_LIMIT);
    		    }
    		    $pic_id = implode(',', $id_arr);
    		    //判断是否存在并没有被使用
    		    $info = $this->api->app_pic(array('id' => $pic_id));
    		    if ($info)
    		    {
    		        $validate_ids = array();
    		        foreach ($info as $v)
    		        {
    		            if ($v['app_id'] == 0 || $v['app_id'] == $app_id)
    		            {
    		                $validate_ids[$v['id']] = $v['id'];
    		            }
    		        }
    		        $validate_ids = implode(',', $validate_ids);
    		        
    		        $updateData = array(
    		            'app_id' => $app_id,
    		            'type' => 1,
    		            'effect' => $guide_effect
    		        );
    		        $this->api->update('app_pic', $updateData, array('id' => $validate_ids));
    		    }
    		}
		}
		//更新引导图背景图
		if ($this->input['guideBackground'])
		{
		    $pic_id = trim(urldecode($this->input['guideBackground']));
    		$id_arr = explode(',', $pic_id);
    		$id_arr = array_filter($id_arr, 'filter_arr');
    		if ($id_arr)
    		{
    		    if (count($id_arr) > GUIDE_LIMIT)
    		    {
    		        $this->errorOutput(OVER_LIMIT);
    		    }
    		    $pic_id = implode(',', $id_arr);
    		    //判断是否存在并没有被使用
    		    $info = $this->api->app_pic(array('id' => $pic_id));
    		    if ($info)
    		    {
    		        $validate_ids = array();
    		        foreach ($info as $v)
    		        {
    		            if ($v['app_id'] == 0 || $v['app_id'] == $app_id)
    		            {
    		                $validate_ids[$v['id']] = $v['id'];
    		            }
    		        }
    		        $validate_ids = implode(',', $validate_ids);
    		        $updateData = array(
    		            'app_id' => $app_id,
    		            'type' => 2,
    		            'effect' => $guide_effect
    		        );
    		        $this->api->update('app_pic', $updateData, array('id' => $validate_ids));
    		    }
    		}
		}
		if ($validate)
		{
		    $validate['update_time'] = TIMENOW;
			$result = $this->api->update('app_info', $validate, array('id' => $app_id));
		}
		else
		{
			$result = true;
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 物理删除APP
	 */
	public function delete()
	{
		$id = trim(urldecode($this->input['id']));
		$id_arr = explode(',', $id);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		$condition = array(
		    'uid' => $this->user['user_id'],
		    'id' => $ids
		);
		//是否为自己创建的
		$info = $this->api->show(array('count' => -1, 'condition' => $condition));
		if (!$info) $this->errorOutput(PARAM_WRONG);
		$validate_ids = array();
		foreach ($info as $app)
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
	 * 逻辑删除APP
	 */
	public function drop()
	{
	    $id = trim(urldecode($this->input['id']));
		$id_arr = explode(',', $id);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		$condition = array(
		    'user_id' => $this->user['user_id'],
		    'id' => $ids
		);
		$result = $this->api->update('app_info', array('del' => 1), $condition);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 设置APP的模板
	 */
	public function set_template()
	{
		$app_id = intval($this->input['id']);
		$temp_id = intval($this->input['t_id']);
		if ($app_id <= 0 || $temp_id <= 0)
		{
			$this->errorOutput(PARAM_WRONG);
		}
	    $condition = array(
	        'id' => $app_id,
	        'user_id' => $this->user['user_id'],
	        'del' => 0
	    );
	    $result = $this->api->update('app_info', array('temp_id' => $temp_id), $condition);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 上传引导图
	 */
	public function upload_guide_pic()
	{
	    if (!$_FILES['guide_pic'])
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    $material = new material();
	    $_FILES['Filedata'] = $_FILES['guide_pic'];
	    unset($_FILES['guide_pic']);
	    $pic_info = $material->addMaterial($_FILES, '', '', '', '', 'png');
	    if (!$pic_info) $this->errorOutput(FAILED);
	    $data = array(
	        'info' => serialize($pic_info),
	        'user_id' => $this->user['user_id'],
	        'user_name' => $this->user['user_name'],
	        'org_id' => $this->user['org_id'],
	        'create_time' => TIMENOW,
	        'ip' => hg_getip()
	    );
	    $result = $this->api->create('app_pic', $data);
	    if ($result['id'])
	    {
	        $this->api->update('app_pic', array('sort_order' => $result['id']), array('id' => $result['id']));
	        $ret = array(
	            'id' => $result['id'],
	            'host' => $pic_info['host'],
	            'dir' => $pic_info['dir'],
	            'filepath' => $pic_info['filepath'],
	            'filename' => $pic_info['filename'],
	            'sort_order' => $result['id']
	        );
	        $this->addItem($ret);
	    }
	    $this->output();
	}
	
	/**
	 * 删除引导图
	 */
	public function drop_guide_pic()
	{
	    $pic_id = trim(urldecode($this->input['pic_ids']));
		$id_arr = explode(',', $pic_id);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		$condition = array(
		    'id' => $ids,
		    'user_id' => $this->user['user_id']
		);
		$result = $this->api->delete('app_pic', $condition);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 引导图排序
	 */
	public function sort_guide_pic()
	{
	    $sort = $this->input['sort'];
	    if (!$sort || !is_array($sort)) $this->errorOutput(PARAM_WRONG);
	    $condition = array('user_id' => $this->user['user_id']);
	    foreach ($sort as $k => $v)
	    {
	        $condition['id'] = intval($k);
	        $result = $this->api->update('app_pic', array('sort_order' => intval($v)), $condition);
	    }
	    $this->addItem($result);
	    $this->output();
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$app_name = trim(urldecode($this->input['appName']));
		$app_brief = trim(urldecode($this->input['appBrief']));
		$app_icon = intval($this->input['app_icon']);
		$icon_type = trim($this->input['icon_type']);
		$temp_id = intval($this->input['temp_id']);
		$copyright = trim(urldecode($this->input['copyright']));
		$effect = trim(urldecode($this->input['effect']));
		$textSize = intval($this->input['cpTextSize']);
		if (isset($this->input['cpTextColor']))
		{
		    $textColor = trim(urldecode($this->input['cpTextColor']));
		}
		elseif (isset($this->settings['cpTextColor']))
		{
		    $textColor = $this->settings['cpTextColor'];
		}
		$app_start_pic = intval($this->input['app_start_pic']);
		$app_start_pic2 = intval($this->input['startup_ios1']);
		$app_start_pic3 = intval($this->input['startup_ios2']);
		$startup_type = trim($this->input['startup_type']);
		$guide_effect = trim(urldecode($this->input['guideEffect']));
		$guide_animation = trim(urldecode($this->input['guideAnimation']));
		$guide_sign = trim(urldecode($this->input['guideSign']));
		if (isset($this->input['guideDefaultColor']))
		{
		    $guide_default_color = trim(urldecode($this->input['guideDefaultColor']));
		}
		elseif (isset($this->settings['signDefaultColor']))
		{
		    $guide_default_color = $this->settings['signDefaultColor'];
		}
		if (isset($this->input['guideSelectColor']))
		{
		    $guide_select_color = trim(urldecode($this->input['guideSelectColor']));
		}
		elseif (isset($this->settings['signSelectedColor']))
		{
		    $guide_select_color = $this->settings['signSelectedColor'];
		}
		if (empty($app_name) || $temp_id <= 0 || $app_icon <= 0)
		{
		    $this->errorOutput(PARAM_WRONG);
		}
		//验证文字大小
		if ($this->settings['cpTextSize'])
		{
		    $sizeArr = array();
		    foreach ($this->settings['cpTextSize'] as $v)
		    {
		        $sizeArr[] = $v['value'];
		    }
		    if ($textSize && !in_array($textSize, $sizeArr))
		    {
		        $this->errorOutput(PARAM_WRONG);
		    }
		}
		//验证文字颜色值
		if ($textColor != '' && !checkColor($textColor))
		{
		    $this->errorOutput(COLOR_ERROR);
		}
		if ($guide_default_color != '' && !checkColor($guide_default_color))
		{
		    $this->errorOutput(COLOR_ERROR);
		}
		if ($guide_select_color != '' && !checkColor($guide_select_color))
		{
		    $this->errorOutput(COLOR_ERROR);
		}
		$data = array(
			'name' => $app_name,
		    'icon' => $app_icon,
		    'icon_type' => $icon_type,
		    'temp_id' => $temp_id,
		    'brief' => $app_brief,
		    'copyright' => $copyright,
		    'effect' => $effect,
		    'text_size' => $textSize,
		    'text_color' => $textColor,
		    'startup_pic' => $app_start_pic,
		    'startup_pic2' => $app_start_pic2,
		    'startup_pic3' => $app_start_pic3,
		    'startup_type' => $startup_type,
		    'guide_effect' => $guide_effect,
		    'guide_animation' => $guide_animation,
		    'guide_sign' => $guide_sign,
		    'guide_default_color' => $guide_default_color,
		    'guide_select_color' => $guide_select_color
		);
		return $data;
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
		$data = array('del' => 0);
		if ($this->user['user_id'] > 0)
		{
		    $data['uid'] = $this->user['user_id'];
		}
		if (!empty($name)) $data['keyword'] = $name;
		if ($start_time) $data['start_time'] = $start_time;
		if ($end_time) $data['end_time'] = $end_time;
		if ($time) $data['date_search'] = $time;
		if ($client_id > 0) $data['client_id'] = $client_id;
		return $data;
	}
	
	/**
	 * 获取APP的打包客户端
	 */
	public function get_client()
	{
		$app_id = intval($this->input['app_id']);
		if ($info = $this->api->get_client($app_id, false, true))
		{
			foreach ($info as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	/**
	 * 设置打包状态为初始状态
	 */
	public function set_original()
	{
	    $app_id = intval($this->input['app_id']);
	    $client_id = trim(urldecode($this->input['client_id']));
	    if (!$client_id || $app_id <= 0)
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    $updateData = array(
            'file_url' => '',
            'download_url' => '',
            'file_size' => 0,
            'state' => 0,
            'publish_time' => 0
        );
	    $condition = array('app_id' => $app_id, 'client_id' => $client_id);
	    $result = $this->api->update('client_relation', $updateData, $condition);
	    $this->addItem($result);
	    $this->output();
	}
	
	/**
	 * 获取杂志风格首页背景
	 */
	public function getHomeBackground()
	{
	    $app_id = intval($this->input['id']);
		if ($app_id <= 0) $this->errorOutput(PARAM_WRONG);
		//获取APP信息
		$app_info = $this->api->detail('app_info', array('id' => $app_id, 'del' => 0));
		if (!$app_info) $this->errorOutput(NO_APPID);
		//获取APP模板信息
		$temp_info = $this->api->detail('app_template', array('id' => $app_info['temp_id']));
		if ($temp_info)
		{
			//获取APP模板的属性
			include_once CUR_CONF_PATH . 'lib/appTemplate.class.php';
			$tempApi = new appTemplate();
			$temp_attr = $tempApi->get_attribute($app_info['temp_id'], $app_id, true);
			if ($temp_attr)
			{
				$temp_attr = $temp_attr[$app_info['temp_id']];
				$homeBackgroundInfo = array();
				foreach ($temp_attr as $k => $attr)
				{
				    if ($attr['mark'] == 'homeBackground')
				    {
				        $homeBackgroundInfo = $attr['attr_value'];
				    }
				}
			}
		}
		if ($homeBackgroundInfo)
		{
		    foreach ($homeBackgroundInfo as $v)
		    {
		        $this->addItem($v);
		    }
		}
		$this->output();
	}
	
	/**
	 * 根据app_id获取相关信息
	 */
	public function config()
	{
		$app_id = intval($this->input['id']);
		if ($app_id <= 0) $this->errorOutput(PARAM_WRONG);
		//获取APP信息
		$queryData = array('id' => $app_id, 'del' => 0);
		$app_info = $this->api->detail('app_info', $queryData);
		if (!$app_info) $this->errorOutput(NO_APPID);
		//更新APP修改时间
		$this->api->update('app_info', array('update_time' => TIMENOW), $queryData);
		
		if ($app_info['icon'] && unserialize($app_info['icon']))
		{
			$app_info['icon'] = unserialize($app_info['icon']);
			if (IS_REPLACE && REPLACE_IMG_DOMAIN)
			{
			    //XXX
			    $app_info['icon']['host'] = REPLACE_IMG_DOMAIN;
			}
			$app_icon = array('web' => $app_info['icon']);
			if ($this->settings['icon_size'])
			{
				$path = $app_info['icon']['host'] . $app_info['icon']['dir'];
				$file = $app_info['icon']['filepath'] . $app_info['icon']['filename'];
			    if ($this->settings['icon_size']['android'])
				{
				    foreach ($this->settings['icon_size']['android'] as $android)
				    {
				        $size = $android['width'] . 'x' . $android['height'];
				        $url = $path . $size . '/' . $file;
				        switch ($android['width'])
				        {
				            case '36':
				                $key = 'drawable-ldpi';
				            break;
				            case '48':
				                $key = 'drawable-mdpi';
				            break;
				            case '72':
				                $key = 'drawable-hdpi';
				            break;
				            case '96':
				                $key = 'drawable-xhdpi';
				            break;
				            case '144':
				                $key = 'drawable-xxhdpi';
				            break;
				        }
				        if ($key) $app_icon['android'][$key] = $url;
				    }
				}
				if ($this->settings['icon_size']['ios'])
				{
				    foreach ($this->settings['icon_size']['ios'] as $ios)
				    {
				        $size = $ios['width'] . 'x' . $ios['height'];
				        $url = $path . $size . '/' . $file;
				        switch ($ios['width'])
				        {
				            case '57':
				                $key = 'Icon';
				            break;
				            case '114':
				                $key = 'Icon@2x';
				            break;
				            case '120':
				                $key = 'Icon-60@2x';
				            break;
				        }
				        if ($key) $app_icon['ios'][$key] = $url;
				    }
				}
			}
			if ($app_icon) $app_info['icon'] = $app_icon;
		}
		if ($app_info['startup_pic'] && unserialize($app_info['startup_pic']))
		{
			$app_info['startup_pic'] = unserialize($app_info['startup_pic']);
			if (IS_REPLACE && REPLACE_IMG_DOMAIN)
			{
			    //XXX
			    $app_info['startup_pic']['host'] = REPLACE_IMG_DOMAIN;
			}
			$startup_pic = array('web' => $app_info['startup_pic']);
			if ($this->settings['startup_size'])
			{
			    $path = $app_info['startup_pic']['host'] . $app_info['startup_pic']['dir'];
				$file = $app_info['startup_pic']['filepath'] . $app_info['startup_pic']['filename'];
			    if ($this->settings['startup_size']['android'])
				{
				    foreach ($this->settings['startup_size']['android'] as $android)
				    {
				        $size = $android['width'] . 'x' . $android['height'];
				        $url = $path . $size . '/' . $file;
				        $startup_pic['android'] = $url;
				    }
				}
				if ($this->settings['startup_size']['ios'])
				{
				    foreach ($this->settings['startup_size']['ios'] as $ios)
				    {
				        $size = $ios['width'] . 'x' . $ios['height'];
				        $url = $path . $size . '/' . $file;
				        switch ($size)
				        {
				            case '640x960':
				                $key = 'Default@2x';
				            break;
				            case '640x1136':
				                $key = 'Default-568h@2x';
				            break;
				        }
				        if ($key) $startup_pic['ios'][$key] = $url;
				    }
				}
				//iOS有上传图片则覆盖
				if ($app_info['startup_pic2'] && unserialize($app_info['startup_pic2']))
				{
				    $startup_pic2 = unserialize($app_info['startup_pic2']);
				    $path = $startup_pic2['host'] . $startup_pic2['dir'];
				    $file = $startup_pic2['filepath'] . $startup_pic2['filename'];
				    $startup_pic['ios']['Default-568h@2x'] = $path . '640x1136/' . $file;
				}
				if ($app_info['startup_pic3'] && unserialize($app_info['startup_pic3']))
				{
				    $startup_pic3 = unserialize($app_info['startup_pic3']);
				    $path = $startup_pic3['host'] . $startup_pic3['dir'];
				    $file = $startup_pic3['filepath'] . $startup_pic3['filename'];
				    $startup_pic['ios']['Default@2x'] = $path . '640x960/' . $file;
				}
			}
			if ($startup_pic) $app_info['startup_pic'] = $startup_pic;
		}
		//获取APP引导图
		$guideCondition = array(
		    'app_id' => $app_id,
		    'effect' => $app_info['guide_effect']
		);
		$guide_pic = $this->api->app_pic($guideCondition);
		if ($guide_pic)
		{
		    $guide_pic_arr = array();
		    foreach ($guide_pic as $k => $guide)
		    {
		        if ($guide['type'] == 1)
		        {
		            $guide_type = 'fg';
		        }
		        elseif ($guide['type'] == 2)
		        {
		            $guide_type = 'bg';
		        }
		        if (IS_REPLACE && REPLACE_IMG_DOMAIN)
		        {
		            //XXX
		            $guide['info']['host'] = REPLACE_IMG_DOMAIN;
		        }
		        $guide_pic_arr[$guide_type][$k]['web'] = $guide['info'];
    		    if ($this->settings['guide_size'])
    			{
    			    $path = $guide['info']['host'] . $guide['info']['dir'];
    				$file = $guide['info']['filepath'] . $guide['info']['filename'];
    			    if ($this->settings['guide_size']['android'])
    				{
    				    foreach ($this->settings['guide_size']['android'] as $android)
    				    {
    				        if ($guide['type'] == 2 && $app_info['guide_effect'] == 'effect2')
    				        {
    				            $size = 'x800';
    				        }
    				        else
    				        {
    				            $size = $android['width'] . 'x' . $android['height'];
    				        }
    				        $url = $path . $size . '/' . $file;
    				        $guide_pic_arr[$guide_type][$k]['android'] = $url;
    				    }
    				}
    				if ($this->settings['guide_size']['ios'])
    				{
    				    foreach ($this->settings['guide_size']['ios'] as $ios)
    				    {
    				        if ($guide['type'] == 2 && $app_info['guide_effect'] == 'effect2')
    				        {
    				            $size = 'x1136';
    				        }
    				        else
    				        {
    				            $size = $ios['width'] . 'x' . $ios['height'];
    				        }
    				        $url = $path . $size . '/' . $file;
    				        $guide_pic_arr[$guide_type][$k]['ios'] = $url;
    				    }
    				}
    			}
		    }
		    if (!$guide_pic_arr) $guide_pic_arr = $guide_pic;
		    $app_info['guide_pic'] = $guide_pic_arr;
		}
		if ($this->settings['data_url'])
		{
		    $app_info['base_url'] = trim($this->settings['data_url']['path'], '/').'/'.$app_info['user_id'].'/';
		}
		if (WEATHER_API)
		{
		    $app_info['weather_api'] = WEATHER_API;
		}
		if (STATISTICS_API)
		{
		    $app_info['statistics_api'] = STATISTICS_API;
		}
		//获取APP打包程序数据
		$app_info['client'] = $this->api->get_client($app_id, false, true);
		//获取APP模板信息
		$temp_info = $this->api->detail('app_template', array('id' => $app_info['temp_id']));
		if ($temp_info)
		{
		    if (unserialize($temp_info['pic']))
		    {
		        $temp_info['pic'] = unserialize($temp_info['pic']);
		        if (IS_REPLACE && REPLACE_IMG_DOMAIN)
		        {
    		        //XXX
    		        $temp_info['pic']['host'] = REPLACE_IMG_DOMAIN;
		        }
		    }
			$app_info['template'] = $temp_info;
			//获取APP模板的属性
			include_once CUR_CONF_PATH . 'lib/appTemplate.class.php';
			$tempApi = new appTemplate();
			$temp_attr = $tempApi->get_attribute($app_info['temp_id'], $app_id, true);
			if ($temp_attr)
			{
				$temp_attr = $temp_attr[$app_info['temp_id']];
				foreach ($temp_attr as $k => $attr)
				{
				    if ($attr['mark'] == 'homeBackground')
				    {
				        unset($temp_attr[$k]);
				        continue;
				    }
					if (is_string($attr['attr_value']) && strpos($attr['attr_value'], '|'))
					{
						$arr = explode('|', $attr['attr_value']);
						$arr_list = array();
						foreach ($arr as $value)
					    {
					    	$vv = explode(':', $value);
					    	$arr_list[$vv[0]] = $vv[1];
					    }
					    $temp_attr[$k]['attr_value'] = $arr_list;
					}
				}
				$app_info['template']['attrs'] = $temp_attr;
			}
		}
		//获取APP模块信息
		include_once CUR_CONF_PATH . 'lib/appModule.class.php';
		$moduleApi = new appModule();
		include_once CUR_CONF_PATH . 'lib/appInterface.class.php';
		$ui_api = new appInterface();
		$condition = array('app_id' => $app_id);
		if ($this->user['user_id'])
		{
		    $condition['uid'] = $this->user['user_id'];
		}
		$module_info = $moduleApi->show(array('count' => -1, 'condition' => $condition));
		if ($module_info)
		{
			$ui_id = array();
			foreach ($module_info as $k => $module)
			{
				if ($module['pic'] && is_array($module['pic']) && $this->settings['module_size'])
				{
				    if (IS_REPLACE && REPLACE_IMG_DOMAIN)
				    {
    				    //XXX
    				    $module['pic']['host'] = REPLACE_IMG_DOMAIN;
				    }
				    $module_icon = array('web' => $module['pic']);
    				if ($this->settings['module_size'])
        			{
        			    $path = $module['pic']['host'].$module['pic']['dir'];
        				$file = $module['pic']['filepath'].$module['pic']['filename'];
        			    if ($this->settings['module_size']['android'])
        				{
        				    foreach ($this->settings['module_size']['android'] as $android)
        				    {
        				        $size = $android['width'] . 'x' . $android['height'];
        				        $url = $path . $size . '/' . $file;
        				        $module_icon['android'] = $url;
        				    }
        				}
        				if ($this->settings['module_size']['ios'])
        				{
        				    foreach ($this->settings['module_size']['ios'] as $ios)
        				    {
        				        $size = $ios['width'] . 'x' . $ios['height'];
        				        $url = $path . $size . '/' . $file;
        				        $module_icon['ios'] = $url;
        				    }
        				}
        			}
        			if ($module_icon) $module_info[$k]['pic'] = $module_icon;
				}
				if ($module['press_pic'] && is_array($module['press_pic']) && $this->settings['module_size'])
				{
				    if (IS_REPLACE && REPLACE_IMG_DOMAIN)
				    {
    				    //XXX
    				    $module['press_pic']['host'] = REPLACE_IMG_DOMAIN;
				    }
				    $module_icon = array('web' => $module['press_pic']);
    				if ($this->settings['module_size'])
        			{
        			    $path = $module['press_pic']['host'].$module['press_pic']['dir'];
        				$file = $module['press_pic']['filepath'].$module['press_pic']['filename'];
        			    if ($this->settings['module_size']['android'])
        				{
        				    foreach ($this->settings['module_size']['android'] as $android)
        				    {
        				        $size = $android['width'] . 'x' . $android['height'];
        				        $url = $path . $size . '/' . $file;
        				        $module_icon['android'] = $url;
        				    }
        				}
        				if ($this->settings['module_size']['ios'])
        				{
        				    foreach ($this->settings['module_size']['ios'] as $ios)
        				    {
        				        $size = $ios['width'] . 'x' . $ios['height'];
        				        $url = $path . $size . '/' . $file;
        				        $module_icon['ios'] = $url;
        				    }
        				}
        			}
        			if ($module_icon) $module_info[$k]['press_pic'] = $module_icon;
				}
				if ($module['ui_id']) $ui_id[$module['ui_id']] = $module['ui_id'];
			}
			
			if ($ui_id)
			{
				$ui_id = implode(',', $ui_id);
				$ui_info = $ui_api->show(array('count' => -1, 'condition' => array('id' => $ui_id)));
				if ($ui_info)
				{
					foreach ($module_info as $k => $v)
					{
						//获取界面对应的属性
						$ui_attr = $ui_api->get_attribute($v['ui_id'], $v['id'], true);
						if ($ui_attr) $ui_info[$v['ui_id']]['attr'] = $ui_attr[$v['ui_id']];
						$module_info[$k]['ui'] = $ui_info[$v['ui_id']];
					}
				}
			}
			$app_info['module'] = $module_info;
		}
		if ($this->input['flag'])
		{
    		$app_cache = $this->api->detail('app_cache', array('app_id' => $app_id));
    		if (!$app_cache)
    		{
    		    $insertData = array(
    		        'app_id' => $app_id,
    		        'data' => serialize($app_info)
    		    );
    		    $this->api->create('app_cache', $insertData);
    		    $app_info['unpack'] = 1;
    		}
    		else
    		{
    		    if ($app_cache && unserialize($app_cache['data']))
    		    {
    		        $app_cache_data = unserialize($app_cache['data']);
    		        //TODO 暂时去除验证设置是否改动
        		    //if ($this->compare_data($app_info, $app_cache_data))
        		    //{
        		    //    $app_info['unpack'] = 0;
        		    //}
        		    //else
        		    //{
        		        $this->api->update('app_cache', array('data' => serialize($app_info)), array('app_id' => $app_id));
        		        if ($app_info['client'])
        		        {
        		            $updateData = array(
        		                'file_url' => '',
        		                'download_url' => '',
        		                'file_size' => 0,
        		                'state' => 0,
        		                'publish_time' => 0,
        		                'push' => 0
        		            );
        		            foreach ($app_info['client'] as $k => $client)
        		            {
        		                $updateData['version_name'] = $client['version_name'] + 1;
        		                $updateData['version_code'] = $client['version_code'] + 1;
        		                $updateCondition = array(
            		                'app_id' => $app_id,
            		                'client_id' => $client['client_id']
            		            );
            		            if ($this->api->update('client_relation', $updateData, $updateCondition))
            		            {
            		                $app_info['client'][$k]['version_name'] = $updateData['version_name'];
            		                $app_info['client'][$k]['version_code'] = $updateData['version_code'];
            		            }
        		            }
        		        }
        		        $app_info['unpack'] = 2;
        		    //}
    		    }
    		    else
    		    {
    		        $app_info['unpack'] = 0;
    		    }
    		}
		}
		$this->addItem($app_info);
		$this->output();
	}
	
	/**
	 * 判断是否更改过配置信息
	 * @param Array $info
	 * @param Array $cache
	 */
	private function compare_data($info, $cache)
	{
	    if ($info['name'] != $cache['name']) return false;
	    if ($info['icon'] != $cache['icon']) return false;
	    if ($info['startup_pic'] != $cache['startup_pic']) return false;
	    if ($info['guide_pic'] !== $cache['guide_pic']) return false;
	    if ($info['temp_id'] != $cache['temp_id']) return false;
	    if ($info['template']['attrs'] != $cache['template']['attrs'])
	    {
	        $attrs = array();
	        foreach ($cache['template']['attrs'] as $cacheAttr)
	        {
	            $attrs[$cacheAttr['id']] = $cacheAttr;
	        }
	        $attr_ids = array_keys($attrs);
	        foreach ($info['template']['attrs'] as $infoAttr)
	        {
	            if (!in_array($infoAttr['id'], $attr_ids)) return false;
	            if ($infoAttr['mark'] != 'homeBackground')
	            {
	                if ($infoAttr['attr_value'] != $attrs[$infoAttr['id']]['attr_value']) return false;
	            }
	        }
	    }
	    $client_id = $cache_client_id = array();
	    if ($cache['client'])
	    {
	        foreach ($cache['client'] as $cacheClient)
	        {
	            $cache_client_id[] = $cacheClient['client_id'];
	        }
	    }
	    if ($info['client'])
	    {
	        foreach ($info['client'] as $client)
	        {
	            $client_id[] = $client['client_id'];
	        }
	    }
	    if (array_diff($client_id, $cache_client_id)) return false;
	    if ($info['module'] != $cache['module'])
	    {
	        $old_order = $new_order = array();
	        $modules = array();
	        foreach ($cache['module'] as $cacheModule)
	        {
	            $modules[$cacheModule['id']] = $cacheModule;
	            $old_order[$cacheModule['id']] = $cacheModule['sort_order'];
	        }
	        $module_ids = array_keys($modules);
	        foreach ($info['module'] as $infoModule)
	        {
	            $new_order[$infoModule['id']] = $infoModule['sort_order'];
	            if (!in_array($infoModule['id'], $module_ids)) return false;
	            if ($infoModule['name'] != $modules[$infoModule['id']]['name']) return false;
	            if ($infoModule['english_name'] != $modules[$infoModule['id']]['english_name']) return false;
	            if ($infoModule['pic'] != $modules[$infoModule['id']]['pic']) return false;
	            if ($infoModule['press_pic'] != $modules[$infoModule['id']]['press_pic']) return false;
	            if ($infoModule['ui_id'] != $modules[$infoModule['id']]['ui_id'])
	            {
	                return false;
	            }
	            else
	            {
	                if ($infoModule['ui']['mark'] != $modules[$infoModule['id']]['ui']['mark']) return false;
	                if ($infoModule['ui']['attr'] != $modules[$infoModule['id']]['ui']['attr'])
	                {
    	                $attrs = array();
            	        foreach ($modules[$infoModule['id']]['ui']['attr'] as $uiAttr)
            	        {
            	            $attrs[$uiAttr['id']] = $uiAttr;
            	        }
            	        $attr_ids = array_keys($attrs);
            	        foreach ($infoModule['ui']['attr'] as $infoAttr)
            	        {
            	            if (!in_array($infoAttr['id'], $attr_ids)) return false;
            	            if ($infoAttr['mark'] != $attrs[$infoAttr['id']]['mark']) return false;
            	            if ($infoAttr['attr_value'] != $attrs[$infoAttr['id']]['attr_value']) return false;
            	        }
	                }
	            }
	        }
	        if ($old_order !== $new_order) return false;
	    }
	    return true;
	}
	
	/**
	 * 添加关于我们
	 */
	public function about()
	{
	    $app_id = intval($this->input['app_id']);
	    $content = trim($this->input['content']);
	    if ($app_id <= 0)
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    $condition = array(
	        'id' => $app_id,
	        'user_id' => $this->user['user_id']
	    );
	    $result = $this->api->update('app_info', array('about_us' => $content), $condition);
	    $this->addItem($result);
	    $this->output();
	}
	
	/**
	 * 获取关于我们的数据
	 */
	public function getAbout()
	{
	    $app_id = intval($this->input['id']);
	    $app_info = $this->api->detail('app_info', array('id' => $app_id, 'del' => 0));
	    if ($app_info)
	    {
	        $this->addItem($app_info['about_us']);
	    }
	    $this->output();
	}
	
	public function selectAllPublish()
	{
	    $sql = 'SELECT pl.*, ac.mark FROM ' . DB_PREFIX . 'publish_log pl 
	    INNER JOIN ' . DB_PREFIX . 'client_relation cr ON pl.app_id = cr.app_id 
	    AND pl.client_id = cr.client_id AND cr.version_name > pl.version_name 
	    LEFT JOIN ' . DB_PREFIX . 'app_client ac ON pl.client_id = ac.id';
	    $query = $this->db->query($sql);
	    while ($rows = $this->db->fetch_array($query))
	    {
	        $this->addItem($rows);
	    }
	    $this->output();
	}
	
	public function deleteAllPublish()
	{
	    if (!($id = trim($this->input['id'])))
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    $sql = 'DELETE FROM ' . DB_PREFIX . 'publish_log WHERE id IN (' . $id . ')';
	    $result = $this->db->query($sql);
	    $this->addItem($result);
	    $this->output();
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

/**
 * 检测颜色值的有效性
 */
function checkColor($val)
{
    if (empty($val)) return false;
    if (strpos($val, '#') === false) $val = '#' . $val;
    if (!preg_match('/^#[0-9a-f]{6}|[0-9a-f]{3}$/i', $val)) return false;
    if (strlen($val) == 4)
    {
        $newStr = substr($val, 1);
        $len = strlen($newStr);
        $out = '#';
        for ($i = 0; $i < $len; $i++)
        {
            $color = substr($newStr, $i, 1);
            $out .= str_repeat($color, 2);
        }
        $val = $out;
    }
    return $val;
}

$out = new apps();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>