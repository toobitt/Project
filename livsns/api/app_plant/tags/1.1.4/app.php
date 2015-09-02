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
 * @description app业务类接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . 'lib/app_version_mode.php');
require_once(CUR_CONF_PATH . '/lib/UpYunOp.class.php');
require_once(CUR_CONF_PATH . 'lib/appTemplate.class.php');

class apps extends appCommonFrm
{
    private $api;
    private $material;
    private $version_mode;
    private $_upYunOp;
    private $_apptemp;
    public function __construct()
    {
        parent::__construct();
        $this->api          = new app();
        $this->material     = new material();
        $this->version_mode = new app_version_mode();
        $this->_upYunOp     = new UpYunOp();
        $this->_apptemp     = new appTemplate();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
        unset($this->material);
    }

    /**
     * 获取App的列表
     *
     * @access public
     * @param  offset | count
     * @return array
     */
    public function show()
    {
        $offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
        $count = isset($this->input['count']) ? intval($this->input['count']) : 20;
        $data = array(
			'offset'     => $offset,
			'count'      => $count,
			'condition'  => $this->condition()
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
     * 根据条件获取App的个数
     *
     * @access public
     * @param  无
     * @return array 例如：array('total' => 20)
     */
    public function count()
    {
        $condition = $this->condition();
        $info      = $this->api->count($condition);
        echo json_encode($info);
    }

    /**
     * 获取某个APP详情
     *
     * @access public
     * @param  id
     * @return array
     */
    public function detail()
    {
        $id = intval($this->input['id']);
        if ( $id <= 0)
        {
            $this->errorOutput(NO_APPID);
        }

        $flag = intval($this->input['flag']);
        $queryData = array('id' => $id, 'del' => 0);
        if ( !$flag)
        {
            $queryData['user_id'] = $this->user['user_id'];
        }

        $app_info = $this->api->detail('app_info', $queryData);
        if ( $app_info)
        {
            if ($app_info['icon'] && unserialize($app_info['icon']))
            {
                $app_info['icon'] = unserialize($app_info['icon']);
            }
            	
            if ($app_info['startup_pic'] && unserialize($app_info['startup_pic']))
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
            	
            if ($app_info['share_plant'] && unserialize($app_info['share_plant']))
            {
                $app_info['share_plant'] = unserialize($app_info['share_plant']);
            }
            	
            //获取APP引导图
            $queryData = array(
			    'app_id' => $app_info['id']
            );
            $guide_pic = $this->api->app_pic($queryData, true);
            if ($guide_pic)
            {
                $app_info['guide_pic'] = $guide_pic;
            }
        }
        $this->addItem($app_info);
        $this->output();
    }

    /**
     * 根据UUID获取应用的信息
     *
     * @access public
     * @param  id :就是UUID
     *
     * @return array
     */
    public function getAppByUUID()
    {
        $uuid = trim($this->input['id']);
        if (empty($uuid))
        {
            $this->errorOutput(NO_UUID);
        }
         
        $queryData = array(
	        'uuid' => $uuid,
	        'del'  => 0
        );
         
        //获取应用的信息
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
     * 获取应用客户端信息，主要用于二维码扫描下载APP的打包文件
     *
     * @access public
     * @param  uuid:就是UUID
     * 		   client_type:客户端类型
     *
     * @return array
     */
    public function getAppClientInfo()
    {
        $uuid = trim($this->input['uuid']);
        $type = trim($this->input['client_type']);
        if (empty($uuid))
        {
            $this->errorOutput(NO_UUID);
        }
         
        if(empty($type))
        {
            $this->errorOutput(NO_CLIENT_TYPE);
        }
         
        //根据客户端标识获取客户端信息
        $result = $this->api->detail('app_client', array('mark' => $type));
        if (!$result)
        {
            $this->errorOutput(CLIENT_INFO_NOT_EXISTS);
        }
         
        //验证APP是否存在
        $queryData = array(
	        'uuid' => $uuid,
	        'del'  => 0
        );
        $app_info = $this->api->detail('app_info', $queryData);
        if (!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }
         
        //获取对应客户端的信息
        $queryData = array(
	        'app_id'     => $app_info['id'],
	        'client_id'  => $result['id']
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

    /**
     * 获取全局配置
     *
     * @access public
     * @param  无
     * @return array
     */
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
	        'icon_size'            => $this->settings['icon_size']['max_size'],
	        'startup_size'         => $this->settings['startup_size']['max_size'],
	        'guide_size'		   => $this->settings['guide_size']['max_size'],
	        'module_size'          => $this->settings['module_size']['max_size'],
	        'navBarTitle_size'     => $this->settings['navBarTitle_size']['max_size'],
	        'magazine_size'        => $this->settings['magazine_size']['max_size'],
	       	'data_url'             => $this->settings['data_url'],
	        'app_effect'           => $this->settings['app_effect'],
	        'text_size'            => $this->settings['cpTextSize'],
	        'text_color'           => $this->settings['cpTextColor'],
	        'guide_effect'         => $this->settings['guideEffect'],
	        'guide_animation'      => $this->settings['guideAnimation'],
	        'shape_sign'           => $this->settings['shapeSign'],
	        'sign_default_color'   => $this->settings['signDefaultColor'],
	        'sign_select_color'    => $this->settings['signSelectedColor'],
	        'image_type'           => $image_type,
	        'guide_limit'          => GUIDE_LIMIT,
	        'module_name_limit'    => MODULE_NAME_LIMIT,
	        'is_replace'           => IS_REPLACE,
	        'replace_img_domain'   => REPLACE_IMG_DOMAIN,
	        'module_limit'         => MODULE_LIMIT_NUM,
	        'is_bag_server_ok'     => IS_BAG_SERVER_OK,
	    	'default_body_tpl'     => DEFAULT_BODY_TPL,	
        );
         
        if (isset($this->settings['vip_user']))
        {
            if (in_array($this->user['user_name'], $this->settings['vip_user']))
            {
                $data['module_limit'] = 15;
                $data['vip'] = 1;
            }
            else
            {
                $data['vip'] = 0;
            }
        }
         
        $this->addItem($data);
        $this->output();
    }

    /**
     * 根据队列id获取打包数据和APP名
     *
     * @access public
     * @param  queue_id:队列id
     * @return array
     */
    public function getInfoByQueueId()
    {
        $queue_id = intval($this->input['queue_id']);
        $clientRelation = $this->api->detail('client_relation', array('queue_id' => $queue_id, 'flag' => 1));
        if (!$clientRelation)
        {
            $this->errorOutput(NO_VERSION_INFO);
        }
         
        $clientInfo = $this->api->detail('app_client', array('id' => $clientRelation['client_id']));
        if (!$clientInfo)
        {
            $this->errorOutput(CLIENT_INFO_NOT_EXISTS);
        }
         
        $appInfo = $this->api->detail('app_info', array('id' => $clientRelation['app_id'], 'del' => 0));
        if (!$appInfo)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }
         
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
     * @param $data:array 如果data存在是内部调用，否则外部调用
     *        appId:应用id
     *        clientId:客户端id
     * @return array
     */
    public function app_client($data = array())
    {
        $app_id     = isset($data['app_id']) ? intval($data['app_id']) : intval($this->input['appId']);
        $client_id  = isset($data['client_id']) ? intval($data['client_id']) : intval($this->input['clientId']);
        if ($app_id <= 0)
        {
            $this->errorOutput(NO_APP_ID);
        }

        if($client_id <= 0)
        {
            $this->errorOutput(NO_CLIENT_TYPE);
        }

        //获取应用信息
        $ret = $this->api->detail('app_info', array('id' => $app_id, 'del' => 0));
        if (!$ret)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }

        $where = array(
			'app_id'     => $app_id,
			'client_id'  => $client_id,
		    'flag'       => 1
        );

        //获取版本信息
        $info = $this->api->detail('client_relation', $where);
        if (!$info)
        {
            $this->errorOutput(NO_VERSION_INFO);
        }

        //获取客户端信息
        $clientInfo = $this->api->detail('app_client', array('id' => $client_id));
        if (!$clientInfo)
        {
            $this->errorOutput(CLIENT_INFO_NOT_EXISTS);
        }

        $info['app_name'] = $ret['name'];
        $info['user_id']  = $ret['user_id'];
        $info['mark']     = $clientInfo['mark'];

        //如果是内部调用就返回
        if ($data)
        {
            return $info;
        }

        //如果是外部调用从这边输出
        if ($info['version_name'])
        {
            $info['version_name'] = getVersionName($info['version_name']);
        }

        $this->addItem($info);
        $this->output();
    }

    /**
     * 更新APP对应的打包客户端数据
     * @param id: 版本id
     *        queueId:队列id
     *
     * @return array
     */
    public function update_client()
    {
        $version_id 	= intval($this->input['id']);
        $queue_id 	    = intval($this->input['queueId']);

        if($version_id)
        {
            $cond = " AND a.id = '" .$version_id. "' ";
        }
        else if($queue_id)
        {
            $cond = " AND a.queue_id = '" .$queue_id. "' ";
        }
        else
        {
            $this->errorOutput(NO_VERSION_ID_OR_QUEUE_ID);
        }

        //查询出最新版本信息
        $versionInfo = $this->version_mode->getNewestVersion($cond);
        if(!$versionInfo)
        {
            $this->errorOutput(NO_VERSION_INFO);
        }

        $status 		= intval($this->input['status']);
        $download_url 	= trim($this->input['downloadUrl']);
        $file_size 		= intval($this->input['fileSize']);
        $isPush			= intval($this->input['push']);

        $data = array();//构建需要更新的数据
        if ($status && $versionInfo['status'] != $status)
        {
            $data['status'] = $status;
        }

        if ($download_url && $versionInfo['download_url'] != $download_url)
        {
            $data['download_url'] = $download_url;
        }

        if ($file_size && $info['file_size'] != $file_size)
        {
            $data['file_size'] = $file_size;
        }

        if($isPush)
        {
            $data['is_push_msg'] = 1;
        }

        if ($data)
        {
            $this->version_mode->update($versionInfo['id'],$data);
        }

        $this->addItem(array('return' => 1));
        $this->output();
    }

    /**
     * 重新打包重置状态
     * @param id: 版本id
     *        queueId:队列id
     *        这二者取一作为条件
     * @return array 成功array('return' => 1) 失败 array('return' => 0)
     */
    public function rebuildBag()
    {
        $id         = $this->input['id'];
        $queue_id   = $this->input['queue_id'];

        if(!$id && !$queue_id)
        {
            $this->errorOutput(NO_ID_OR_QUEUE_ID);
        }

        //重置的字段
        $data = array(
			'status' 		=> 0,
			'download_url' 	=> '',
			'file_size' 	=> 0,
			'is_push_msg' 	=> 0,
        );

        if($id)
        {
            $ret = $this->version_mode->rebuildBag($id, '', $data);
        }
        else
        {
            $ret = $this->version_mode->rebuildBag('', ' AND queue_id IN (' . $queue_id . ')', $data);
        }

        if($ret)
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
     * 更新推送状态
     * @param queue_id: 队列id
     * @return array
     */
    public function updatePushStatus()
    {
        if (isset($this->input['queue_id']))
        {
            $queue_id = intval($this->input['queue_id']);
            if ($queue_id <= 0)
            {
                $this->errorOutput(NO_QUEUE_ID);
            }
             
            $queryData = array(
	        	'queue_id' => $queue_id
            );
        }
        else
        {
            $app_id     = intval($this->input['app_id']);
            $client_id  = intval($this->input['client_id']);
            if ($app_id <= 0)
            {
                $this->errorOutput(NO_APP_ID);
            }
            	
            if($client_id <= 0)
            {
                $this->errorOutput(NO_CLIENT_ID);
            }
            	
            $queryData = array(
	        	'app_id'     => $app_id,
    	        'client_id'  => $client_id
            );
        }
        $queryData['flag'] = 1;
        $info = $this->api->detail('client_relation', $queryData);
        if (!$info)
        {
            $this->errorOutput(NO_VERSION_INFO);
        }
         
        $result = $this->api->update('client_relation', array('push' => 1), $queryData);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 检测是否超过限定APP创建个数
     *
     * @access public
     * @param access_token:登陆令牌
     * @return BOOL
     */
    public function limitNum()
    {
        $result = $this->getLimit();
        $this->addItem($result);
        $this->output();
    }

    /**
     * 检测是否超过限定APP创建个数（内部调用）
     *
     * @access private
     * @param  access_token:登陆令牌
     * @return BOOL
     */
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
     *
     * @access public
     * @param  很多：具体看filter_data方法
     * @return array
     */
    public function create()
    {
        //过滤数据
        $data = $this->filter_data();
        //验证创建的个数是否达到上限
        if ($this->getLimit())
        {
            $this->errorOutput(OVER_LIMIT);
        }

        //APP图标
        //$app_icon_info = $this->material->get_material_by_ids($data['icon']);
        $app_icon_info = $this->_upYunOp->getPicInfoById($data['icon']);
        //兼容原来的附件
        if(!$app_icon_info)
        {
            $app_icon_info = $this->material->get_material_by_ids($data['icon']);
        }
        
        if (!$app_icon_info || !isset($app_icon_info[0]) || !$app_icon_info[0])
        {
            $this->errorOutput(APP_ICON_ERROR);
        }

        $data['icon'] = @serialize($app_icon_info[0]);

        //APP启动画面
        if ($data['startup_pic'] <= 0)
        {
            $this->errorOutput(APP_STARTPIC_ERROR);
        }

        //$app_start_pic_info = $this->material->get_material_by_ids($data['startup_pic']);
        $app_start_pic_info = $this->_upYunOp->getPicInfoById($data['startup_pic']);
        //兼容原来的附件
        if(!$app_start_pic_info)
        {
            $app_start_pic_info = $this->material->get_material_by_ids($data['startup_pic']);
        }
        
        if (!$app_start_pic_info || !isset($app_start_pic_info[0]) || !$app_start_pic_info[0])
        {
            $this->errorOutput(APP_STARTPIC_ERROR);
        }
         
        $data['startup_pic'] = @serialize($app_start_pic_info[0]);
         
        //启动图(iOS两种尺寸图)
        if ($data['startup_pic2'] > 0)
        {
            //$start_pic2_info = $this->material->get_material_by_ids($data['startup_pic2']);
            $start_pic2_info = $this->_upYunOp->getPicInfoById($data['startup_pic2']);
            //兼容原来的附件
            if(!$start_pic2_info)
            {
                $start_pic2_info = $this->material->get_material_by_ids($data['startup_pic2']);
            }
            
            if (!$start_pic2_info || !isset($start_pic2_info[0]) || !$start_pic2_info[0])
            {
                $this->errorOutput(APP_STARTPIC_ERROR);
            }
            	
            $data['startup_pic2'] = @serialize($start_pic2_info[0]);
        }
         
        if ($data['startup_pic3'] > 0)
        {
            //$start_pic3_info = $this->material->get_material_by_ids($data['startup_pic3']);
            $start_pic3_info = $this->_upYunOp->getPicInfoById($data['startup_pic3']);
            //兼容原来的附件
            if(!$start_pic3_info)
            {
                $start_pic3_info = $this->material->get_material_by_ids($data['startup_pic3']);
            }
            
            if (!$start_pic3_info || !isset($start_pic3_info[0]) || !$start_pic3_info[0])
            {
                $this->errorOutput(APP_STARTPIC_ERROR);
            }
            	
            $data['startup_pic3'] = @serialize($start_pic3_info[0]);
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
                if (count($id_arr) > GUIDE_LIMIT)
                {
                    $this->errorOutput(OVER_LIMIT);
                }

                $pic_id = implode(',', $id_arr);
                //判断是否存在并没有被使用
                $info = $this->api->app_pic(array('app_id' => 0, 'id' => $pic_id));
                if (!$info)
                {
                    $this->errorOutput(GUIDE_PIC_ERROR);
                }

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
                if (count($id_arr) > GUIDE_LIMIT)
                {
                    $this->errorOutput(OVER_LIMIT);
                }

                $pic_id = implode(',', $id_arr);
                //判断是否存在并没有被使用
                $info = $this->api->app_pic(array('app_id' => 0, 'id' => $pic_id));
                if (!$info)
                {
                    $this->errorOutput(GUIDE_PIC_ERROR);
                }

                $bg_ids = array();
                foreach ($info as $v)
                {
                    $bg_ids[$v['id']] = $v['id'];
                }
                $bg_ids = implode(',', $bg_ids);
            }
        }

        $data['user_id']     = $this->user['user_id'];
        $data['user_name']   = $this->user['user_name'];
        $data['org_id']      = $this->user['org_id'];
        $data['create_time'] = TIMENOW;
        $data['update_time'] = TIMENOW;
        $data['ip']          = hg_getip();
        $data['uuid']        = 'uuid';
        $result = $this->api->create('app_info', $data);
        if ($fg_ids && $result['id'])
        {
            $updateData = array(
		    	'app_id' => $result['id'],
		        'type'   => 1,
		        'effect' => $data['guide_effect']
            );
            $this->api->update('app_pic', $updateData, array('id' => $fg_ids));
        }

        if ($bg_ids && $result['id'])
        {
            $updateData = array(
		    	'app_id' => $result['id'],
		        'type'   => 2,
		        'effect' => $data['guide_effect']
            );
            $this->api->update('app_pic', $updateData, array('id' => $bg_ids));
        }
        $this->addItem($result);
        $this->output();
    }

    /**
     * 更新APP
     *
     * @access public
     * @param  很多，一大推，具体看input,这里就不一一列举了
     * @return array
     */
    public function update()
    {
        $app_id = intval($this->input['id']);
        if ($app_id <= 0)
        {
            $this->errorOutput(NO_APP_ID);
        }

        $queryData = array(
			'id'      => $app_id,
			'user_id' => $this->user['user_id'],
		    'del'     => 0
        );

        $app_info = $this->api->detail('app_info', $queryData);
        if (!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }

        //过滤数据
        $data = $this->filter_data();
        $validate = array();
        if ($app_info['name'] != $data['name'])
        {
            $validate['name'] = $data['name'];
        }

        if ($app_info['is_show_guide'] != $data['is_show_guide'])
        {
            $validate['is_show_guide'] = $data['is_show_guide'];
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

        if ($app_info['seekhelp_sort_id'] != $data['seekhelp_sort_id'])
        {
            $validate['seekhelp_sort_id'] = $data['seekhelp_sort_id'];
        }

        //APP图标
        if ($data['icon'] > 0)
        {
            //$app_icon_info = $this->material->get_material_by_ids($data['icon']);
            $app_icon_info = $this->_upYunOp->getPicInfoById($data['icon']);
            //此处主要是为了兼容原来的附件
            if(!$app_icon_info)
            {
                $app_icon_info = $this->material->get_material_by_ids($data['icon']);
            }
            
            if (!$app_icon_info || !isset($app_icon_info[0]) || !$app_icon_info[0])
            {
                $this->errorOutput(APP_ICON_ERROR);
            }

            $app_icon_info = serialize($app_icon_info[0]);
            if ($app_icon_info != $app_info['icon'])
            {
                $validate['icon'] = $app_icon_info;
            }
        }

        //APP启动画面
        if ($data['startup_pic'] > 0)
        {
            //$app_start_pic_info = $this->material->get_material_by_ids($data['startup_pic']);
            $app_start_pic_info = $this->_upYunOp->getPicInfoById($data['startup_pic']);
            //此处主要是为了兼容原来的附件
            if(!$app_start_pic_info)
            {
                $app_start_pic_info = $this->material->get_material_by_ids($data['startup_pic']);
            }
            
            if ( !$app_start_pic_info || !isset($app_start_pic_info[0]) || !$app_start_pic_info[0])
            {
                $this->errorOutput(APP_STARTPIC_ERROR);
            }

            $data['startup_pic'] = serialize($app_start_pic_info[0]);
            if ($data['startup_pic'] != $app_info['startup_pic'])
            {
                $validate['startup_pic'] = $data['startup_pic'];
            }
        }

        //启动图(iOS两种尺寸图)
        if ($data['startup_pic2'] > 0)
        {
            //$start_pic2_info = $this->material->get_material_by_ids($data['startup_pic2']);
            $start_pic2_info = $this->_upYunOp->getPicInfoById($data['startup_pic2']);
            //此处主要是为了兼容原来的附件
            if(!$start_pic2_info)
            {
                $start_pic2_info = $this->material->get_material_by_ids($data['startup_pic2']);
            }
            
            if (!$start_pic2_info || !isset($start_pic2_info[0]) || !$start_pic2_info[0])
            {
                $this->errorOutput(APP_STARTPIC_ERROR);
            }
            	
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
            //$start_pic3_info = $this->material->get_material_by_ids($data['startup_pic3']);
            $start_pic3_info = $this->_upYunOp->getPicInfoById($data['startup_pic3']);
            //此处主要是为了兼容原来的附件
            if(!$start_pic3_info)
            {
                $start_pic3_info = $this->material->get_material_by_ids($data['startup_pic3']);
            }
            
            if ( !$start_pic3_info || !isset($start_pic3_info[0]) || !$start_pic3_info[0])
            {
                $this->errorOutput(APP_STARTPIC_ERROR);
            }
            	
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
    		            'type'   => 1,
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
    		            'type'   => 2,
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
     *
     * @access public
     * @param  id:应用id，多个用逗号分隔
     * @return array
     */
    public function delete()
    {
        $id     = trim(urldecode($this->input['id']));
        $id_arr = explode(',', $id);
        $id_arr = array_filter($id_arr, 'filter_arr');
        if (!$id_arr)
        {
            $this->errorOutput(NO_APP_ID);
        }

        $ids = implode(',', $id_arr);
        $condition = array(
		    'uid' => $this->user['user_id'],
		    'id'  => $ids
        );
        //是否为自己创建的
        $info = $this->api->show(array('count' => -1, 'condition' => $condition));
        if (!$info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }

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
     *
     * @access public
     * @param  id:应用id，多个用逗号分隔
     * @return array
     */
    public function drop()
    {
        $id     = trim(urldecode($this->input['id']));
        $id_arr = explode(',', $id);
        $id_arr = array_filter($id_arr, 'filter_arr');
        if (!$id_arr)
        {
            $this->errorOutput(NO_APP_ID);
        }

        $ids = implode(',', $id_arr);
        $condition = array(
		    'user_id' => $this->user['user_id'],
		    'id'      => $ids
        );
        $result = $this->api->update('app_info', array('del' => 1), $condition);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 设置APP的模板
     *
     * @access public
     * @param  id:应用id
     * 		   t_id:模板id
     * @return array
     */
    public function set_template()
    {
        $app_id     = intval($this->input['id']);
        $temp_id    = intval($this->input['t_id']);
        if ($app_id <= 0)
        {
            $this->errorOutput(NO_APP_ID);
        }

        if($temp_id <= 0)
        {
            $this->errorOutput(NO_TPL_ID);
        }

        $condition = array(
	        'id'      => $app_id,
	        'user_id' => $this->user['user_id'],
	        'del'     => 0
        );
         
        $result = $this->api->update('app_info', array('temp_id' => $temp_id), $condition);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 上传引导图
     *
     * @access public
     * @param  FILES['name']:guide_pic
     *
     * @return array
     */
    public function upload_guide_pic()
    {
        if (!$_FILES['guide_pic'])
        {
            $this->errorOutput(GUIDE_PIC_ERROR);
        }
        
        /*
        $material = new material();
        $_FILES['Filedata'] = $_FILES['guide_pic'];
        unset($_FILES['guide_pic']);
        $pic_info = $material->addMaterial($_FILES, '', '', '', '', 'png');
        */
        $pic_info = $this->_upYunOp->uploadToBucket($_FILES['guide_pic'],'',$this->user['user_id']);
        if ( FALSE === $pic_info)
        {
            $this->errorOutput(FAIL_UPLOAD_TO_MATARIAL);
        }
         
        $data = array(
	        'info'        => serialize($pic_info),
	        'user_id'     => $this->user['user_id'],
	        'user_name'   => $this->user['user_name'],
	        'org_id'      => $this->user['org_id'],
	        'create_time' => TIMENOW,
	        'ip'          => hg_getip()
        );
         
        $result = $this->api->create('app_pic', $data);
        if ($result['id'])
        {
            $this->api->update('app_pic', array('sort_order' => $result['id']), array('id' => $result['id']));
            $ret = array(
	            'id'         => $result['id'],
	            'host'       => $pic_info['host'],
	            'dir'        => $pic_info['dir'],
	            'filepath'   => $pic_info['filepath'],
	            'filename'   => $pic_info['filename'],
	            'sort_order' => $result['id']
            );
            $this->addItem($ret);
        }
        $this->output();
    }

    /**
     * 删除引导图
     *
     * @access public
     * @param  pic_ids:引导图图片id,多个用逗号分隔
     *
     * @return array
     */
    public function drop_guide_pic()
    {
        $pic_id = trim(urldecode($this->input['pic_ids']));
        $id_arr = explode(',', $pic_id);
        $id_arr = array_filter($id_arr, 'filter_arr');
        if (!$id_arr)
        {
            $this->errorOutput(NO_PIC_ID);
        }

        $ids = implode(',', $id_arr);
        $condition = array(
		    'id'      => $ids,
		    'user_id' => $this->user['user_id']
        );
        $result = $this->api->delete('app_pic', $condition);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 引导图排序
     *
     * @access public
     * @param  sort:引导图排序id,多个用逗号分隔
     *
     * @return array
     */
    public function sort_guide_pic()
    {
        $sort = $this->input['sort'];
        if (!$sort || !is_array($sort))
        {
            $this->errorOutput(ORDER_ERROR);
        }
         
        $condition = array('user_id' => $this->user['user_id']);
        foreach ($sort as $k => $v)
        {
            $condition['id'] = intval($k);
            $result          = $this->api->update('app_pic', array('sort_order' => intval($v)), $condition);
        }
        $this->addItem($result);
        $this->output();
    }

    /**
     * 更新互助id
     *
     * @access public
     * @param  id:应用id
     * 		   seekhelp_sort_id:互助排序id
     * @return array
     */
    public function updateSeekHelp()
    {
        $app_id      = intval($this->input['id']);
        $seekhelp_id = intval($this->input['seekhelp_sort_id']);
         
        if ($app_id <= 0)
        {
            $this->errorOutput(NO_APP_ID);
        }
         
        if($seekhelp_id <= 0)
        {
            $this->errorOutput(NO_SEEKHELP_ID);
        }
         
        $updateData = array('seekhelp_sort_id' => $seekhelp_id);
        $condition  = array('id' => $app_id);
        $result = $this->api->update('app_info', $updateData, $condition);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 过滤数据
     *
     * @access private
     * @param  参数一大推，具体看input
     *
     * @return array
     */
    private function filter_data()
    {
        $app_name     = trim(urldecode($this->input['appName']));
        $app_brief    = trim(urldecode($this->input['appBrief']));
        $app_icon     = intval($this->input['app_icon']);
        $icon_type    = trim($this->input['icon_type']);
        $temp_id      = intval($this->input['temp_id']);
        $copyright    = trim(urldecode($this->input['copyright']));
        $effect       = trim(urldecode($this->input['effect']));
        $textSize     = intval($this->input['cpTextSize']);
        if (isset($this->input['cpTextColor']))
        {
            $textColor = trim(urldecode($this->input['cpTextColor']));
        }
        elseif (isset($this->settings['cpTextColor']))
        {
            $textColor = $this->settings['cpTextColor'];
        }
        $app_start_pic   = intval($this->input['app_start_pic']);
        $app_start_pic2  = intval($this->input['startup_ios1']);
        $app_start_pic3  = intval($this->input['startup_ios2']);
        $startup_type    = trim($this->input['startup_type']);
        $guide_effect    = trim(urldecode($this->input['guideEffect']));
        $guide_animation = trim(urldecode($this->input['guideAnimation']));
        $guide_sign      = trim(urldecode($this->input['guideSign']));
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

        if (empty($app_name))
        {
            $this->errorOutput(NO_APP_NAME);
        }

        if($temp_id <= 0)
        {
            $this->errorOutput(NO_TPL_ID);
        }

        if($app_icon <= 0)
        {
            $this->errorOutput(NO_APP_ICON_ID);
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
                $this->errorOutput(TEXT_SIZE_ERROR);
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
			'name'                => $app_name,
		    'icon'           	  => $app_icon,
		    'icon_type'           => $icon_type,
		    'temp_id'             => $temp_id,
		    'brief'               => $app_brief,
		    'copyright'           => $copyright,
		    'effect'              => $effect,
		    'text_size'           => $textSize,
		    'text_color'          => $textColor,
		    'startup_pic'         => $app_start_pic,
		    'startup_pic2'        => $app_start_pic2,
		    'startup_pic3'        => $app_start_pic3,
		    'startup_type'        => $startup_type,
		    'guide_effect'        => $guide_effect,
		    'guide_animation'     => $guide_animation,
		    'guide_sign'          => $guide_sign,
		    'guide_default_color' => $guide_default_color,
		    'guide_select_color'  => $guide_select_color,
			'is_show_guide'	      => intval($this->input['is_show_guide']),
        );

        if (isset($this->input['seekhelp_sort_id']))
        {
            $data['seekhelp_sort_id'] = intval($this->input['seekhelp_sort_id']);
        }
        return $data;
    }

    /**
     * 获取查询条件
     *
     * @access private
     * @param  参数一大推，具体看input
     *
     * @return array
     */
    private function condition()
    {
        $name       = isset($this->input['k']) ? trim(urldecode($this->input['k'])) : '';
        $time       = isset($this->input['date_search']) ? intval($this->input['date_search']) : '';
        $start_time = trim($this->input['start_time']);
        $end_time   = trim($this->input['end_time']);
        $client_id  = intval($this->input['c_id']);
        $data       = array('del' => 0);

        if ($this->user['user_id'] > 0)
        {
            $data['uid'] = $this->user['user_id'];
        }

        if (!empty($name))    $data['keyword'] = $name;
        if ($start_time)   $data['start_time'] = $start_time;
        if ($end_time)       $data['end_time'] = $end_time;
        if ($time)        $data['date_search'] = $time;
        if ($client_id > 0) $data['client_id'] = $client_id;

        return $data;
    }

    /**
     * 获取APP的打包客户端
     *
     * @access public
     * @param  app_id:应用id
     *
     * @return array
     */
    public function get_client()
    {
        $app_id = intval($this->input['app_id']);
        if ($info = $this->api->get_version_info($app_id))
        {
            foreach ($info as $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    /**
     * 获取杂志风格首页背景
     *
     * @access public
     * @param  id:应用id
     *
     * @return array
     */
    public function getHomeBackground()
    {
        $app_id = intval($this->input['id']);
        if ($app_id <= 0)
        {
            $this->errorOutput(NO_APP_ID);
        }

        //获取APP信息
        $app_info = $this->api->detail('app_info', array('id' => $app_id, 'del' => 0));
        if (!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }

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
                //去掉已经选中的默认图
                if($v['is_selected'])
                {
                    continue;
                }
                $this->addItem($v);
            }
        }
        $this->output();
    }

    /**
     * 根据app_id获取相关信息
     *
     * @access public
     * @param  id:应用id
     *
     * @return array
     */
    public function config()
    {
        $app_id = intval($this->input['id']);
        if (!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }

        //获取APP信息
        $app_info = $this->api->detail('app_info', array('id' => $app_id, 'del' => 0));
        if (!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }

        //分享平台数据
        if(isset($app_info['share_plant']) && $app_info['share_plant'])
        {
            $app_info['share_plant'] = @unserialize($app_info['share_plant']);
        }
        else
        {
            $app_info['share_plant'] = array();
        }

        if ($app_info['icon'] && unserialize($app_info['icon']))
        {
            $app_info['icon'] = unserialize($app_info['icon']);
            if (IS_REPLACE && REPLACE_IMG_DOMAIN)
            {
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
                        //兼容原来的附件,dir存在就说明是原来的附件
                        if($app_info['icon']['dir'])
                        {
                            $size = $android['width'] . 'x' . $android['height'];
                            $url  = $path . $size . '/' . $file;
                        }
                        else 
                        {
                            $url  = $path . $file . '!' .  $android['thumb'];
                        }
                        $app_icon['android'][$android['key']] = $url;

                        /*
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
                        */
                        
                    }
                }
                
                if ($this->settings['icon_size']['ios'])
                {
                    foreach ($this->settings['icon_size']['ios'] as $ios)
                    {
                        //兼容原来的附件,dir存在就说明是原来的附件
                        if($app_info['icon']['dir'])
                        {
                            $size = $ios['width'] . 'x' . $ios['height'];
                            $url  = $path . $size . '/' . $file;
                        }
                        else 
                        {
                            $url  = $path . $file . '!' . $ios['thumb'] ;
                        }
                        $app_icon['ios'][$ios['key']] = $url;
                        
                        /*
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
                        */
                    }
                }
            }
            
            if ($app_icon) 
            {
                $app_info['icon'] = $app_icon;
            }
        }

        if ($app_info['startup_pic'] && unserialize($app_info['startup_pic']))
        {
            $app_info['startup_pic'] = unserialize($app_info['startup_pic']);
            if (IS_REPLACE && REPLACE_IMG_DOMAIN)
            {
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
                        /*
                        $size = $android['width'] . 'x' . $android['height'];
                        $url = $path . $size . '/' . $file;
                        $startup_pic['android'] = $url;
                        */
                        //兼容原来的附件,dir存在就说明是原来的附件
                        if($app_info['startup_pic']['dir'])
                        {
                            $size = $android['width'] . 'x' . $android['height'];
                            $url  = $path . $size . '/' . $file;
                        }
                        else 
                        {
                            $url  = $path . $file . '!' . $android['thumb'] ;
                        }
                        $startup_pic['android'] = $url;//此处因为安卓只有一个
                    }
                }
                
                if ($this->settings['startup_size']['ios'])
                {
                    foreach ($this->settings['startup_size']['ios'] as $ios)
                    {
                        /*
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
                        */
                        
                        //兼容原来的附件,dir存在就说明是原来的附件
                        if($app_info['startup_pic']['dir'])
                        {
                            $size = $ios['width'] . 'x' . $ios['height'];
                            $url  = $path . $size . '/' . $file;
                        }
                        else 
                        {
                            $url  = $path . $file . '!' . $ios['thumb'] ;
                        }
                        $startup_pic['ios'][$ios['key']] = $url;
                    }
                }
                
                //iOS有上传图片则覆盖
                if ($app_info['startup_pic2'] && unserialize($app_info['startup_pic2']))
                {
                    $startup_pic2 = unserialize($app_info['startup_pic2']);
                    $path = $startup_pic2['host'] . $startup_pic2['dir'];
                    $file = $startup_pic2['filepath'] . $startup_pic2['filename'];
                    //兼容原来的附件,dir存在就说明是原来的附件
                    if($startup_pic2['dir'])
                    {
                        $startup_pic['ios']['Default-568h@2x'] = $path . '640x1136/' . $file;
                    }
                    else 
                    {
                        $startup_pic['ios']['Default-568h@2x'] = $path .  $file . '!default568x2';
                    }
                }
                
                if ($app_info['startup_pic3'] && unserialize($app_info['startup_pic3']))
                {
                    $startup_pic3 = unserialize($app_info['startup_pic3']);
                    $path = $startup_pic3['host'] . $startup_pic3['dir'];
                    $file = $startup_pic3['filepath'] . $startup_pic3['filename'];
                    //兼容原来的附件,dir存在就说明是原来的附件
                    if($startup_pic3['dir'])
                    {
                        $startup_pic['ios']['Default@2x'] = $path . '640x960/' . $file;
                    }
                    else 
                    {
                        $startup_pic['ios']['Default@2x'] = $path .  $file . '!defaultx2';
                    }
                }
            }
            
            if ($startup_pic) 
            {
                $app_info['startup_pic'] = $startup_pic;
            }
        }
        
        if ($app_info['is_show_guide'])
        {
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
                                //兼容原来的附件
                                if($guide['info']['dir'])
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
                                }
                                else 
                                {
                                    if ($guide['type'] == 2 && $app_info['guide_effect'] == 'effect2')
                                    {
                                        $thumb = $android['effect2'];
                                    }
                                    else
                                    {
                                        $thumb = $android['thumb'];
                                    }
                                    $url = $path . $file  . '!' .  $thumb;
                                }
                                $guide_pic_arr[$guide_type][$k]['android'] = $url;
                            }
                        }
                        
                        if ($this->settings['guide_size']['ios'])
                        {
                            foreach ($this->settings['guide_size']['ios'] as $ios)
                            {
                                //兼容原来的附件
                                if($guide['info']['dir'])
                                {
                                    if ($guide['type'] == 2 && $app_info['guide_effect'] == 'effect2')
                                    {
                                        $size = 'x800';
                                    }
                                    else
                                    {
                                        $size = $ios['width'] . 'x' . $ios['height'];
                                    }
                                    $url = $path . $size . '/' . $file;
                                }
                                else 
                                {
                                    if ($guide['type'] == 2 && $app_info['guide_effect'] == 'effect2')
                                    {
                                        $thumb = $ios['effect2'];
                                    }
                                    else
                                    {
                                        $thumb = $ios['thumb'];
                                    }
                                    $url = $path . $file . '!' . $thumb;
                                }
                                $guide_pic_arr[$guide_type][$k]['ios'] = $url;
                                /*
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
                                */
                            }
                        }
                    }
                }
                if (!$guide_pic_arr)
                {
                    $guide_pic_arr = $guide_pic;
                }

                $app_info['guide_pic'] = $guide_pic_arr;
            }
        }
        if ($this->settings['data_url'])
        {
            $app_info['base_url'] = trim($this->settings['data_url']['path'], '/').'/'.$app_info['user_id'].'/';
        }
        //天气接口
        if (WEATHER_API)
        {
            $app_info['weather_api'] = WEATHER_API;
        }
        //统计接口
        if (STATISTICS_API)
        {
            $app_info['statistics_api'] = STATISTICS_API;
        }
        //会员接口
        if (MEMBER_API)
        {
            $app_info['member_api'] = MEMBER_API;
        }
        //互助接口
        if (SEEKHELP_API)
        {
            $app_info['seekhelp_api'] = SEEKHELP_API;
        }
        //获取推送id和key
        include_once ROOT_PATH . 'lib/class/company.class.php';
        $companyApi = new company();
        $push_api = $companyApi->getPushApi($this->user['user_id']);
        if ($push_api)
        {
            $app_info['push_api'] = $push_api;
            $app_info['prov_id'] = $push_api['prov_id'];
        }
        //获取APP打包程序数据
        $app_info['client'] = $this->api->get_version_info($app_id);
        //获取APP模板信息
        $temp_info = $this->api->detail('app_template', array('id' => $app_info['temp_id']));
        if ($temp_info)
        {
            if (unserialize($temp_info['pic']))
            {
                $temp_info['pic'] = unserialize($temp_info['pic']);
                if (IS_REPLACE && REPLACE_IMG_DOMAIN)
                {
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
                $homeBacground = array();//保存首页背景节点数据
                $temp_attr = $temp_attr[$app_info['temp_id']];
                foreach ($temp_attr as $k => $attr)
                {
                    //首页背景做特殊处理
                    if ($attr['mark'] == 'homeBackground')
                    {
                        if($attr['attr_value'] && is_array($attr['attr_value']))
                        {
                            foreach ($attr['attr_value'] AS $_kk => $_vv)
                            {
                                if($_vv['is_selected'])
                                {
                                    //原来附件的图
                                    if($_vv['dir'])
                                    {
                                        $homeBacground = hg_fetchimgurl($_vv,640,1136); 
                                    }
                                    else 
                                    {
                                        $homeBacground = $_vv['host'] . $_vv['filepath'] . $_vv['filename'] . '!default568x2';
                                    }
                                    break;
                                }
                            }
                        }
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
                
                if($homeBacground)
                {
                    $app_info['template']['home_background'] = $homeBacground;
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
                                //此处为了兼容原来的附件
                                if($module['pic']['dir'])
                                {
                                    $size = $android['width'] . 'x' . $android['height'];
                                    $url  = $path . $size . '/' . $file;
                                }
                                else 
                                {
                                    $url  = $path . $file . '!' .  $android['thumb'];
                                }
                                $module_icon['android'] = $url;
                                /*
                                $size = $android['width'] . 'x' . $android['height'];
                                $url = $path . $size . '/' . $file;
                                $module_icon['android'] = $url;
                                */
                            }
                        }
                        
                        if ($this->settings['module_size']['ios'])
                        {
                            foreach ($this->settings['module_size']['ios'] as $ios)
                            {
                                //此处为了兼容原来的附件
                                if($module['pic']['dir'])
                                {
                                    $size = $ios['width'] . 'x' . $ios['height'];
                                    $url  = $path . $size . '/' . $file;
                                }
                                else 
                                {
                                    $url  = $path . $file . '!' .  $ios['thumb'];
                                }
                                $module_icon['ios'] = $url;
                                /*
                                $size = $ios['width'] . 'x' . $ios['height'];
                                $url = $path . $size . '/' . $file;
                                $module_icon['ios'] = $url;
                                */
                            }
                        }
                    }
                    if ($module_icon) $module_info[$k]['pic'] = $module_icon;
                }
                
                if ($module['press_pic'] && is_array($module['press_pic']) && $this->settings['module_size'])
                {
                    if (IS_REPLACE && REPLACE_IMG_DOMAIN)
                    {
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
                                //此处为了兼容原来的附件
                                if($module['press_pic']['dir'])
                                {
                                    $size = $android['width'] . 'x' . $android['height'];
                                    $url  = $path . $size . '/' . $file;
                                }
                                else 
                                {
                                    $url  = $path . $file . '!' .  $android['thumb'];
                                }
                                $module_icon['android'] = $url;
                                /*
                                $size = $android['width'] . 'x' . $android['height'];
                                $url = $path . $size . '/' . $file;
                                $module_icon['android'] = $url;
                                */
                            }
                        }
                        
                        if ($this->settings['module_size']['ios'])
                        {
                            foreach ($this->settings['module_size']['ios'] as $ios)
                            {
                                //此处为了兼容原来的附件
                                if($module['press_pic']['dir'])
                                {
                                    $size = $ios['width'] . 'x' . $ios['height'];
                                    $url  = $path . $size . '/' . $file;
                                }
                                else 
                                {
                                    $url  = $path . $file . '!' .  $ios['thumb'];
                                }
                                $module_icon['ios'] = $url;
                                /*
                                $size = $ios['width'] . 'x' . $ios['height'];
                                $url = $path . $size . '/' . $file;
                                $module_icon['ios'] = $url;
                                */
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
                        if ($ui_attr && $ui_attr[$v['ui_id']])
                        {
                            $ui_info[$v['ui_id']]['attr'] = $ui_attr[$v['ui_id']];
                        }
                        if ($ui_info[$v['ui_id']])
                        {
                            $module_info[$k]['ui'] = $ui_info[$v['ui_id']];
                        }
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
                    //暂时去除验证设置是否改动
                    //if ($this->compare_data($app_info, $app_cache_data))
                    //{
                    //    $app_info['unpack'] = 0;
                    //}
                    //else
                    //{
                    $this->api->update('app_cache', array('data' => serialize($app_info)), array('app_id' => $app_id));
                    if ($app_info['client'])
                    {
                        /*
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
                         */
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
     *
     * @access private
     * @param Array $info
     * @param Array $cache
     * @return array
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
     *
     * @access public
     * @param app_id:应用id
     * @param content:内容
     * @return array
     */
    public function about()
    {
        $app_id = intval($this->input['app_id']);
        $content = trim($this->input['content']);
        if ($app_id <= 0)
        {
            $this->errorOutput(NO_APP_ID);
        }
         
        $condition = array(
	        'id'      => $app_id,
	        'user_id' => $this->user['user_id']
        );
         
        $result = $this->api->update('app_info', array('about_us' => $content), $condition);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 获取关于我们的数据
     *
     * @access public
     * @param id:应用id
     *
     * @return array
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

    /**
     * 选择所有的发布的应用
     *
     * @access public
     * @param 无
     *
     * @return array
     */
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

    /**
     * 删除指定的发布的应用
     *
     * @access public
     * @param id:应用id
     *
     * @return array
     */
    public function deleteAllPublish()
    {
        if (!($id = trim($this->input['id'])))
        {
            $this->errorOutput(NO_APP_ID);
        }
        $sql = 'DELETE FROM ' . DB_PREFIX . 'publish_log WHERE id IN (' . $id . ')';
        $result = $this->db->query($sql);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 根据id查询版本信息(可以是队列id也可以是版本自增id)
     *
     * @access public
     * @param id:应用id
     * @param queue_id:队列id
     * @return array
     */
    public function getVersionById()
    {
        $versionId = intval($this->input['id']);
        $queueId = intval($this->input['queue_id']);
        if($versionId)
        {
            $cond = " AND av.id = '" .$versionId. "' ";
        }
        elseif ($queueId)
        {
            $cond = " AND av.queue_id = '" .$queueId. "' ";
        }
        else
        {
            $this->errorOutput(NO_VERSION_ID_OR_QUEUE_ID);
        }

        $version = $this->version_mode->getVersion($cond);
        if(!$version)
        {
            $this->errorOutput(NO_VERSION_INFO);
        }
        $app_info = $this->api->detail('app_info', array('id' => $version['app_id']));
        if (!$app_info)
        {
            $this->errorOutput(NO_APPID);
        }
        $version['app_name'] = $app_info['name'];
        //输出
        foreach ($version AS $k => $v)
        {
            $this->addItem_withkey($k, $v);
        }
        $this->output();
    }

    /**
     * 更新分享平台
     *
     * @access public
     * @param  app_id:应用id
     * @param  share_info:分享信息
     * @return array
     */
    public function updateSharePlant()
    {
        $app_id = $this->input['app_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }

        $data = $this->input['share_info'];
        if(!$data || !is_array($data))
        {
            $this->errorOutput(ERR_SHARE_DATA);
        }

        $ret = $this->api->updateAppInfo($app_id,array(
			'share_plant' => addslashes(serialize($data)),
        ));

        if($ret)
        {
            $this->addItem(array('return' => 1));
        }
        else
        {
            $this->addItem(array('return' => 0));
        }
    }

    /**
     * 获取当前已经打包的版本
     *
     * @access public
     * @param  app_id:应用id
     *
     * @return array
     */
    public function getCurrentVersion()
    {
        $app_id = intval($this->input['app_id']);
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }

        $ret = $this->api->getVersionByClient(array($app_id));
        if($ret && isset($ret[$app_id]))
        {
            foreach ($ret[$app_id] AS $k => $v)
            {
                $this->addItem_withkey($k, $v);
            }
            $this->output();
        }
    }

    /**
     * 获取历史版本说明
     *
     * @access public
     * @param  offset:偏移量
     * 		   count:获取个数
     *
     * @return array
     */
    public function getHistoryVersionLog()
    {
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit = ' LIMIT ' . $offset . ' , ' . $count;
        $orderby = " ORDER BY create_time DESC ";

        $app_id = $this->input['app_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }

        $cond = " AND app_id = '" .$app_id. "' AND is_release = 1 AND client_type = 1 ";
        $data = $this->version_mode->show($cond,$orderby,$limit);
        $total = $this->version_mode->count($cond);
        $this->addItem_withkey('data',$data);
        $this->addItem_withkey('count',$total['total']);
        $this->output();
    }

    /**
     * 根据uuid获取最新版本
     *
     * @access public
     * @param  uuid:uuid
     * 		   mark:客户端标识
     * 		   is_release:是否是发布版本
     * @return array
     */
    public function getNewstVersionByUUID()
    {
        $uuid = $this->input['uuid'];
        $mark = $this->input['mark'];
        $is_release = $this->input['is_release'];
        if(!uuid)
        {
            $this->errorOutput(NO_UUID);
        }
        $ret = $this->version_mode->getNewstVersionByUUID($uuid,$mark,$is_release);
        if($ret)
        {
            $this->addItem($ret);
            $this->output();
        }
    }

    /**
     * 根据用户id查询应用信息
     *
     * @access public
     * @param  user_id:用户id
     *
     * @return array
     */
    public function getAppInfoByUserId()
    {
        $user_id = $this->input['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_USER_ID);
        }

        $ret = $this->api->getAppInfoByUserId($user_id);
        if($ret)
        {
            foreach ($ret AS $k => $v)
            {
                $this->addItem_withkey($k, $v);
            }
            $this->output();
        }
    }

    /**
     * 更新系统图标下载地址
     *
     * @access public
     * @param  app_id:应用id
     *         system_url:系统图标下载地址
     * @return array
     */
    public function updateSystemIconUrl()
    {
        $app_id = $this->input['app_id'];
        $systemIconUrl = $this->input['system_url'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }

        if(!$systemIconUrl)
        {
            $this->errorOutput(NO_SYSTEM_ICON_URL);
        }

        $ret = $this->api->updateAppInfo($app_id,array(
				'system_icon_url' => $systemIconUrl,
        ));

        if($ret)
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
    }

    /**
     * 根据队列id获取所有的版本
     *
     * @access public
     * @param  queue_id:队列id
     *
     * @return array
     */
    public function getVersionByQueueId()
    {
        $queue_id = $this->input['queue_id'];//queue_id可能是多个
        if(!$queue_id)
        {
            $this->errorOutput(NO_QUEUE_ID);
        }

        $ret = $this->version_mode->getVersionInfo(" AND av.queue_id IN (" .$queue_id. ") ");
        if($ret)
        {
            foreach ($ret AS $v)
            {
                $this->addItem($v);
            }
            $this->output();
        }
    }

    /**
     * 根据user_id获取该用户对应应用的ios与安卓的版本信息以及应用信息
     *
     * @access public
     * @param  user_id:用户id
     *
     * @return array
     */
    public function getAppByUserId()
    {
        $user_id = $this->input['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_USER_ID);
        }

        $ret = $this->version_mode->getAppByUserId($user_id);
        if($ret)
        {
            $this->addItem($ret);
            $this->output();
        }
    }
}

$out = new apps();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();