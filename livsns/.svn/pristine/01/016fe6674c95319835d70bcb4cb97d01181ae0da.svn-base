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
require_once(ROOT_PATH     . 'lib/class/material.class.php');
require_once(CUR_CONF_PATH . 'lib/app_version_mode.php');
require_once(CUR_CONF_PATH . 'lib/UpYunOp.class.php');
require_once(CUR_CONF_PATH . 'lib/appTemplate.class.php');
require_once(CUR_CONF_PATH . 'lib/company.class.php');
require_once(CUR_CONF_PATH . 'lib/attribute_value_mode.php');
require_once(CUR_CONF_PATH . 'lib/user_interface_mode.php');
require_once(CUR_CONF_PATH . 'lib/appMaterial.class.php');
require_once(CUR_CONF_PATH . 'lib/extend_field_mode.php');
require_once(CUR_CONF_PATH . 'lib/app_info_mode.php');
require_once(ROOT_PATH     . 'lib/class/company.class.php');
require_once(ROOT_PATH     . 'lib/class/im.class.php');
require_once(ROOT_PATH     . 'lib/class/seekhelp.class.php');
require_once(CUR_CONF_PATH . 'lib/components_mode.php');
require_once(CUR_CONF_PATH . 'lib/superscript_mode.php');
require_once(CUR_CONF_PATH . 'lib/superscript_comp.php');
require_once(CUR_CONF_PATH . 'lib/new_extend.class.php');

class apps extends appCommonFrm
{
    private $api;
    private $material;
    private $version_mode;
    private $_upYunOp;
    private $_apptemp;
    private $companyApi;
    private $ui_mode;
    private $ui_value_mode;
    private $app_material;
    private $extend_mode;
    private $app_info_mode;
    private $_company;
    private $_im;
    private $_comp_mode;
    private $_corner_mode;
    private $_super_comp;
    private $new_extend;

    public function __construct()
    {
        parent::__construct();
        $this->api           = new app();
        $this->material      = new material();
        $this->version_mode  = new app_version_mode();
        $this->_upYunOp      = new UpYunOp();
        $this->_apptemp      = new appTemplate();
        $this->companyApi    = new CompanyApi();
        $this->ui_mode       = new user_interface_mode();
        $this->ui_value_mode = new attribute_value_mode();
        $this->app_material  = new appMaterial();
        $this->extend_mode   = new extend_field_mode();
        $this->app_info_mode = new app_info_mode();
        $this->_company      = new company();
        $this->_im           = new im();
        $this->seekhelp      = new seekhelp();
        $this->_comp_mode    = new components_mode();
        $this->_corner_mode  = new superscript_mode();
        $this->_super_comp   = new superscript_comp();
        $this->new_extend    = new new_extend();
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
        if (! $this->user['user_id'])
        {
            $this->errorOutput(NO_LOGIN);
        }
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
    
    //根据多个应用id获取多个应用信息
    public function getAppsByIds()
    {
        $ids = $this->input['id'];
        if(!$ids)
        {
            $this->errorOutput(NOID);
        }
        
        $offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
        $count = isset($this->input['count']) ? intval($this->input['count']) : 20;
        $data = array(
			'offset'     => $offset,
			'count'      => $count,
			'condition'  => array('id' => $ids),
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
         
        /*
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
        */
        
        //获取当前用户的信息
        
        if($this->user['user_id'])
        {
            $user_info = $this->companyApi->getUserInfoByUserId($this->user['user_id']);
            if(!$user_info)
            {
                $this->errorOutput(USER_NOT_EXISTS);
            }
    
            //判断用户是否是VIP用户
            if ($user_info['is_vip'])
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
        $app_icon_info = $this->_upYunOp->getPicInfoById(''," AND id IN (" . $data['icon'] . ") AND user_id = '" .$this->user['user_id']. "' ");
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
        $app_start_pic_info = $this->_upYunOp->getPicInfoById(''," AND id IN (" . $data['startup_pic'] . ") AND user_id = '" .$this->user['user_id']. "' ");
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
            $start_pic2_info = $this->_upYunOp->getPicInfoById(''," AND id IN (" . $data['startup_pic2'] . ") AND user_id = '" .$this->user['user_id']. "' ");
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
            $start_pic3_info = $this->_upYunOp->getPicInfoById(''," AND id IN (" . $data['startup_pic3'] . ") AND user_id = '" .$this->user['user_id']. "' ");
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
        $guidIsExist = true;
        while($guidIsExist)
        {
        	$guid = Common::getRandString(10);
        	$guidIsExist = $this->api->validataGuidIsExist($guid);
        }
        $data['guid']        = $guid;
        $data['uuid']        = 'uuid';
        
        //设置客户端包名
        $lower_guid = strtolower($guid);  
        $data['package_name'] = $this->settings['package_prefix'].$lower_guid;
        
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
        
        if($app_info['city'] != $data['city'])
        {
            $validate['city'] = $data['city'];//默认城市
        }

        if ($app_info['is_show_guide'] != $data['is_show_guide'])
        {
            $validate['is_show_guide'] = $data['is_show_guide'];
        }

        if ($app_info['brief'] != $data['brief'])
        {
            $validate['brief'] = $data['brief'];
        }
		
        if ($app_info['user_copy_right'] != $data['user_copy_right'])
        {
        	$validate['user_copy_right'] = $data['user_copy_right'];
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
            $app_icon_info = $this->_upYunOp->getPicInfoById(''," AND id IN (" . $data['icon'] . ") AND user_id = '" .$this->user['user_id']. "' ");
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
            $app_start_pic_info = $this->_upYunOp->getPicInfoById(''," AND id IN (" . $data['startup_pic'] . ") AND user_id = '" .$this->user['user_id']. "' ");
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
            $start_pic2_info = $this->_upYunOp->getPicInfoById(''," AND id IN (" . $data['startup_pic2'] . ") AND user_id = '" .$this->user['user_id']. "' ");
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
            $start_pic3_info = $this->_upYunOp->getPicInfoById(''," AND id IN (" . $data['startup_pic3'] . ") AND user_id = '" .$this->user['user_id']. "' ");
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
            
            //更新缓存表的app_name
            if($validate['name'])
            {
            	$this->updateAppName($app_id, $validate['name']);
            }
            
            //请求二维码接口，清缓存
            foreach (array('debug','release') AS $version)
            {
                $_qrcode_url = $this->settings['qrcode_url'] . '?id=' . $app_info['uuid'] . '&type=' . $version . '&try_cache=0';
                file_get_contents($_qrcode_url);
            }
        }
        else
        {
            $result = true;
        }
        $this->addItem($result);
        $this->output();
    }

    /**
     * 更新app字段
     */
    public function updateAppInfo()
    {
        $app_id = intval($this->input['id']);
        if ($app_id <= 0)
        {
            $this->errorOutput(NO_APP_ID);
        }

        $app_info = $this->api->detail('app_info',array('id'=> $app_id,'del'=> 0));
        if (!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }

        if (isset($this->input['open_mobile_register']))
        {
            $validate['open_mobile_register'] = $this->input['open_mobile_register'];
        }

        if (isset($this->input['android_package_name']) && $this->input['android_package_name'])
        {
            //检测包名是否存在
            $app_info = $this->api->detail('app_info',array('android_package_name'=> $this->input['android_package_name'],'del'=> 0));
            if($app_info)
            {
                $this->errorOutput(PACKAGE_NAME_EXIST);
            }
            $validate['android_package_name'] = $this->input['android_package_name'];
        }

        if ($validate)
        {
            $validate['update_time'] = TIMENOW;
            $result = $this->api->update('app_info', $validate, array('id' => $app_id));

            //更新缓存表的app_name
            if($validate['name'])
            {
                $this->updateAppName($app_id, $validate['name']);
            }

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
        //$this->api->delete('app_cache', array('app_id' => $validate_ids));
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
     * 更新缓存表的app_name
     */
    private function updateAppName($app_id,$app_name)
    {
    	//更新社区sort表的app_name
    	$seekhelp = $this->seekhelp->updateSort_AppName($app_id, $app_name);
    	
    	//更新群聊rongcloud_info表的app_name
    	$imInfo = $this->_im->updateRcInfo($app_id, $app_name);
    	
    	if($seekhelp && $imInfo)
    	{
    		return true;
    	}
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
        $user_copy_right = trim(urldecode($this->input['user_copy_right']));
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
        	'user_copy_right'     => $user_copy_right,
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
            'city'				  => $this->input['city'],//默认城市
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
     * 获取首页背景图
     *
     * @access public
     * @param  id:应用id
     *
     * @return array
     */
    
    public function getHomeBgImages()
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
        
        //首先判断存不存在该UI
        if(!$app_info['temp_id'] || !$uiArr = $this->ui_mode->detail($app_info['temp_id']))
        {
            $this->errorOutput(NOT_EXISTS_UI);
        }
        
        //保存首页背景数据
        $homeBgImages = array();
        
        //获取该应用绑定的
        $attrData = $this->ui_value_mode->getAttributeData($app_info['temp_id'],FALSE,$app_id,0);
        if($attrData)
        {
            foreach ($attrData AS $k => $v)
            {
                if($v['uniqueid'] == 'homeBg' && isset($v['attr_default_value']) && is_array($v['attr_default_value']))
                {
                    $homeBgImages = $v['attr_default_value'];
                    break;
                } 
            }
        }
        
        if($homeBgImages && isset($homeBgImages['img']) && is_array($homeBgImages['img']))
        {
            foreach ($homeBgImages['img'] AS $k => $v)
            {
                $_home_bg = $v;
                $_home_bg['is_selected'] = ($v['id'] == $homeBgImages['selected'])?1:0;
                $this->addItem($_home_bg);
            }
            $this->output();
        }
        else 
        {
            $this->errorOutput(NO_HOME_BG);
        }
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
        	$app_info['unpack'] = 1;
        	/* 
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
                
            }*/
        }
        $this->addItem($app_info);
        $this->output();
    }
    
    //获取某个应用的所有相关信息
    public function getAppAllInfo()
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
        
        $app_info['is_open_weather'] = 1;//天气默认都开启

        // 获取融云的 production_app_key
        $rcAppKey = $this->_im->get_rckey($app_id);
        if ($rcAppKey)
        {
            $app_info['rcAppKey'] = $rcAppKey;
        }
        else
        {
            //创建融云应用
            $rc_info = $this->_im->apply_signature($app_id,$app_info['name'],$app_info['brief']);
            $app_info['rcAppKey'] = $rc_info['rc_key'];
        }

        if($this->user['user_id'])
        {
            $user_info = $this->companyApi->getUserInfoByUserId($this->user['user_id']);
        }
        elseif($app_info['user_id'])
        {
            $user_info = $this->companyApi->getUserInfoByUserId($app_info['user_id']);
        }

        if(!$user_info)
        {
            $this->errorOutput(USER_NOT_EXISTS);
        }
        
        $app_info['user_info'] = $user_info;

        if($app_info['guide_effect'])
        {
            $app_info['guide_effect_num'] = $this->settings['guide_effect_setting'][$app_info['guide_effect']];
        }
        
        //处理默认城市
        if(!$app_info['city'])
        {
            $app_info['city'] = $this->settings['default_city']['city'];
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
        
        //额外配置数据
        if(isset($app_info['extras']) && $app_info['extras'])
        {
            $app_info['extras'] = @unserialize($app_info['extras']);
        }
        else
        {
            $app_info['extras'] = $this->settings['app_extras'];
        }
        
        //获取推送id和key
        
        $pushInfo = $this->_company->getPushApi($this->user['user_id']);
        if ($pushInfo && is_array($pushInfo))
        {
            $app_info['push_info'] = $pushInfo;
        }

        //应用图标
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
                    }
                }
            }
            
            if ($app_icon) 
            {
                $app_info['icon'] = $app_icon;
            }
        }

        //启动图
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
        
        //是否显示引导图
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
        
        /*****************************************获取MAIN_UI信息********************************************/
        $appUIInfo = $this->ui_mode->detail($app_info['temp_id']);
        if($appUIInfo)
        {
            if (IS_REPLACE && REPLACE_IMG_DOMAIN)
            {
                if($appUIInfo['img_info'])
                {
                    $appUIInfo['img_info']['host'] = REPLACE_IMG_DOMAIN;
                }
            }
            
            //查询出该UI下对应的属性
            $attrData = $this->ui_value_mode->getAttributeData($app_info['temp_id'],TRUE,$app_id,0,1,$user_info['dingdone_role_id']);
            if($attrData)
            {
                //获取分组数据
                $groupData = $this->ui_value_mode->getGroupData();
                //输出配置型
                $uiConfig = $this->buildTreeDataConfig(0, $groupData,$attrData);
                foreach ($uiConfig AS $kk => $vv)
                {
                    foreach ($vv AS $_kk => $_vv)
                    {
                        if($_kk == 'main_ui')
                        {
                            $appUIInfo['attr'] = $_vv;

                            //判断全局导航栏标题有没有值
                            if(!$appUIInfo['attr']['navBar']['titleContent']['text'] && !$appUIInfo['attr']['navBar']['titleContent']['drawable'])
                            {
                                $appUIInfo['attr']['navBar']['titleContent']['text'] = $app_info['name'];//如果全局导航栏标题没有设置，就取应用名称
                            }
                            
                            //经典风格做的特殊处理（只有经典风格才有more节点）
                            if($appUIInfo['uniqueid'] == 'MainUI1')
                            {
                                if(isset($_vv['more']) && $_vv['more'] && is_array($_vv['more']))
                                {
                                    //more里面的文字默认色取外面的文字默认色
                                    if(isset($_vv['textNorBg']) && $_vv['textNorBg'])
                                    {
                                        $appUIInfo['attr']['more']['textNorBg'] = $_vv['textNorBg'];
                                    }
                                    
                                    //more里面的文字点击色取外面的文字点击色
                                    if(isset($_vv['textPreBg']) && $_vv['textPreBg'])
                                    {
                                        $appUIInfo['attr']['more']['textPreBg'] = $_vv['textPreBg'];
                                    }
                                    
                                    //more里面的模块默认色透明
                                    $appUIInfo['attr']['more']['layoutNorBg'] = Common::convertColor('#ffffff',0);
                                    
                                    //more里面的模块点击色取外面的遮罩色
                                    if(isset($_vv['tabedBg']) && $_vv['tabedBg'])
                                    {
                                        $appUIInfo['attr']['more']['layoutPreBg'] = $_vv['tabedBg'];
                                    }

                                    //more里面的导航栏相关设置
                                    if(isset($_vv['more']['navBar']))
                                    {
                                        $appUIInfo['attr']['more']['navBar']['height'] = 50;//所有导航栏的高度固定值50，不用用户配置
                                        //根据是否开启导航栏天气
                                        if($app_info['is_open_navBar_weather'])
                                        {
                                            $appUIInfo['attr']['more']['navBar']['navLeftComponent'] = 1;
                                        }
                                        else 
                                        {
                                            $appUIInfo['attr']['more']['navBar']['navLeftComponent'] = 0;
                                        }
                                        
                                        //右边控件的判断，是否开启会员
                                        if ($app_info['is_open_member'])
                                        {
                                            $appUIInfo['attr']['more']['navBar']['navRightComponent'] = 1;
                                        }
                                        else
                                        {
                                            $appUIInfo['attr']['more']['navBar']['navRightComponent'] = 2;
                                        }
                                        
                                        //更多模块的导航栏高斯模糊根据全局的来
                                        if(isset($appUIInfo['attr']['navBar']['isBlur']))
                                        {
                                            $appUIInfo['attr']['more']['navBar']['isBlur'] = (bool)$appUIInfo['attr']['navBar']['isBlur'];
                                        }
                                        else 
                                        {
                                            $appUIInfo['attr']['more']['navBar']['isBlur'] = $this->settings['module_default']['isBlur'];
                                        }

                                        //如果more里面没有设置导航栏标题就取全局的
                                        if(!$appUIInfo['attr']['more']['navBar']['titleContent']['text'] && !$appUIInfo['attr']['more']['navBar']['titleContent']['drawable'])
                                        {
                                            if(isset($appUIInfo['attr']['navBar']['titleContent']['text']))
                                            {
                                                $appUIInfo['attr']['more']['navBar']['titleContent']['text'] = $appUIInfo['attr']['navBar']['titleContent']['text'];
                                            }
                                            else 
                                            {
                                                $appUIInfo['attr']['more']['navBar']['titleContent']['text'] = $app_info['name'];
                                            }
                                        }
                                        
                                        //more里面的导航栏背景取全局的导航栏背景
                                        if($_vv['navBar']['bg'])
                                        {
                                            $appUIInfo['attr']['more']['navBar']['bg'] = $_vv['navBar']['bg'];
                                        }
                                    }
                                    
                                    //经典风格里面模块的页面距下面的距离后台取tabHeight的高度+10
                                    if(isset($_vv['tabHeight']))
                                    {
                                        $appUIInfo['attr']['more']['uiPaddingBottom'] = (int)$_vv['tabHeight'] + (int)$_vv['dividerSpace'];
                                    }
                                    
                                    //如果more里面到模块主色没有设置
                                    if(isset($_vv['more']['mainColor']['noColor']) && $_vv['more']['mainColor']['noColor'])
                                    {
                                        if(isset($appUIInfo['attr']['mainColor']) && is_array($appUIInfo['attr']['mainColor']) && !isset($appUIInfo['attr']['mainColor']['noColor']))
                                        {
                                            $appUIInfo['attr']['more']['main_color'] = $appUIInfo['attr']['mainColor']['color'];
                                        }
                                        else 
                                        {
                                            $appUIInfo['attr']['more']['main_color'] =  Common::convertColor($this->settings['module_default']['main_color']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $app_info['main_ui'] = $appUIInfo;
        }
        /*****************************************获取MAIN_UI信息********************************************/

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
                            }
                        }
                    }
                    if ($module_icon) $module_info[$k]['press_pic'] = $module_icon;
                }
                
                //经典风格的时候，所有模块里面的模块点击色，文字点击色，模块默认色，文字默认色此处做特殊处理
                if($appUIInfo['uniqueid'] == 'MainUI1')
                {
                    //模块里面的文字默认色取外面的文字默认色
                    if(isset($appUIInfo['attr']['textNorBg']))
                    {
                        $module_info[$k]['text_nor_bg'] = $appUIInfo['attr']['textNorBg'];
                    }
                    elseif ($module['text_nor_bg'])
                    {
                        $module_info[$k]['text_nor_bg'] = Common::convertColor($module['text_nor_bg']);
                    }
                    else 
                    {
                        $module_info[$k]['text_nor_bg'] = Common::convertColor($this->settings['module_default']['text_nor_bg']);
                    }
                    
                    //模块里面的文字点击色取外面的文字点击色
                    if(isset($appUIInfo['attr']['textPreBg']))
                    {
                        $module_info[$k]['text_pre_bg'] = $appUIInfo['attr']['textPreBg'];
                    }
                    elseif ($module['text_pre_bg'])
                    {
                        $module_info[$k]['text_pre_bg'] = Common::convertColor($module['text_pre_bg']);
                    }
                    else 
                    {
                        $module_info[$k]['text_pre_bg'] = Common::convertColor($this->settings['module_default']['text_pre_bg']);
                    }
                    
                    //模块里面的模块默认色透明
                    $module_info[$k]['layout_nor_bg'] = Common::convertColor('#ffffff',0);
                    
                    //模块里面的模块点击色取外面的遮罩色
                    if(isset($appUIInfo['attr']['tabedBg']))
                    {
                        $module_info[$k]['layout_pre_bg'] = $appUIInfo['attr']['tabedBg'];
                    }
                    elseif ($module['layout_pre_bg'])
                    {
                        $module_info[$k]['layout_pre_bg'] = Common::convertColor($module['layout_pre_bg'],$module['layout_pre_alpha']);
                    }
                    else 
                    {
                        $module_info[$k]['layout_pre_bg'] = Common::convertColor($this->settings['module_default']['layout_pre_bg'],$this->settings['module_default']['layout_pre_alpha']);
                    }
                }
                else 
                {
                    //其他风格如果用户没有设置值，就取默认值
                    //文字默认背景
                    if(!$module['text_nor_bg'])
                    {
                        if(isset($appUIInfo['attr']['textNorBg']) && is_array($appUIInfo['attr']['textNorBg']))
                        {
                            $module_info[$k]['text_nor_bg'] = $appUIInfo['attr']['textNorBg']['color'];
                        }
                        else 
                        {
                            $module_info[$k]['text_nor_bg'] = $this->settings['module_default']['text_nor_bg'];
                        }
                    }
                    
                    //文字点击背景
                    if(!$module['text_pre_bg'])
                    {
                        if(isset($appUIInfo['attr']['textPreBg']) && is_array($appUIInfo['attr']['textPreBg']))
                        {
                            $module_info[$k]['text_pre_bg'] = $appUIInfo['attr']['textPreBg']['color'];
                        }
                        else 
                        {
                            $module_info[$k]['text_pre_bg'] = $this->settings['module_default']['text_pre_bg'];
                        }
                    }
                    
                    //模块默认背景
                    $is_global_nor = 0;//用于标识是否取得是全局的默认背景
                    if(!$module['layout_nor_bg'])
                    {
                        if(isset($appUIInfo['attr']['layoutNorBg']) && is_array($appUIInfo['attr']['layoutNorBg']))
                        {
                            $module_info[$k]['layout_nor_bg']    = $appUIInfo['attr']['layoutNorBg']['color'];
                            $module_info[$k]['layout_nor_alpha'] = $appUIInfo['attr']['layoutNorBg']['alpha'];
                            $is_global_nor = 1;
                        }
                        else 
                        {
                            $module_info[$k]['layout_nor_bg'] = $this->settings['module_default']['layout_nor_bg'];
                        }
                    }
                    
                    //模块点击背景
                    $is_global_pre = 0;//用于标识是否取得是全局的模块点击背景
                    if(!$module['layout_pre_bg'])
                    {
                        if(isset($appUIInfo['attr']['layoutPreBg']) && is_array($appUIInfo['attr']['layoutPreBg']))
                        {
                            $module_info[$k]['layout_pre_bg']    = $appUIInfo['attr']['layoutPreBg']['color'];
                            $module_info[$k]['layout_pre_alpha'] = $appUIInfo['attr']['layoutPreBg']['alpha'];
                            $is_global_pre = 1;
                        }
                        else 
                        {
                            $module_info[$k]['layout_pre_bg'] = $this->settings['module_default']['layout_pre_bg'];
                        }
                    }

                    if(!$is_global_nor && !$module['layout_nor_alpha'] && $module['layout_nor_alpha'] !== 0.00)
                    {
                        $module_info[$k]['layout_nor_alpha'] = $this->settings['module_default']['layout_nor_alpha'];
                    }
                    
                    if(!$is_global_pre && !$module['layout_pre_alpha'] && $module['layout_pre_alpha'] !== 0.00)
                    {
                        $module_info[$k]['layout_pre_alpha'] = $this->settings['module_default']['layout_pre_alpha'];
                    }
                }
                
                //处理导航栏配置
                if($module['navbar'] && unserialize($module['navbar']))
                {
                    $navBar = unserialize($module['navbar']);
                    //处理导航栏背景
                    if(isset($navBar['bg']) && isset($navBar['bg']['img']))
                    {
                        $_img_info = $this->app_material->detail('app_material', array('id' => $navBar['bg']['img']));
                        $navBar['bg']['img'] = $_img_info;
                    }
                    
                    //处理导航栏标题
                    if(isset($navBar['titleContent']) && isset($navBar['titleContent']['img']))
                    {
                        $_img_info = $this->app_material->detail('app_material', array('id' => $navBar['titleContent']['img']));
                        $navBar['titleContent']['img'] = $_img_info;
                    }
                    
                    //如果模块本身没有设置导航栏背景的值
                    if(!isset($navBar['bg']) || !is_array($navBar['bg']))
                    {
                        //全局导航栏背景
                        if(isset($appUIInfo['attr']['navBar']['bg']) && is_array($appUIInfo['attr']['navBar']['bg']))
                        {
                            //如果全局首页背景设置的是颜色
                            if($appUIInfo['attr']['navBar']['bg']['color'])
                            {
                                $navBar['bg'] = array('color' => $appUIInfo['attr']['navBar']['bg']['color'],'alpha' => $appUIInfo['attr']['navBar']['bg']['alpha']);
                            }
                            elseif ($appUIInfo['attr']['navBar']['bg']['img_id'])
                            {
                                $_img_info = $this->app_material->detail('app_material', array('id' => $appUIInfo['attr']['navBar']['bg']['img_id']));
                                $navBar['bg'] = array('img' => $_img_info,'is_tile' => $appUIInfo['attr']['navBar']['bg']['isRepeat']);
                            }
                        }
                        
                        if(!isset($navBar['bg']) || !$navBar['bg'])
                        {
                            $navBar['bg'] = array(
                                'color' => $this->settings['module_default']['navbar']['bg'],
                                'alpha' => 1,
                            );
                        }
                    }
                    
                    //如果模块本身没有设置导航栏标题内容
                    if(!isset($navBar['titleContent']) || !is_array($navBar['titleContent']))
                    {
                        //全局导航栏标题内容
                        if(isset($appUIInfo['attr']['navBar']['titleContent']) && is_array($appUIInfo['attr']['navBar']['titleContent']))
                        {
                            //如果全局导航栏标题是文字
                            if($appUIInfo['attr']['navBar']['titleContent']['text'])
                            {
                                $navBar['titleContent'] = array('text' => $appUIInfo['attr']['navBar']['titleContent']['text']);
                            }
                            elseif ($appUIInfo['attr']['navBar']['titleContent']['img_id'])
                            {
                                $_img_info = $this->app_material->detail('app_material', array('id' => $appUIInfo['attr']['navBar']['titleContent']['img_id']));
                                $navBar['titleContent'] = array('img' => $_img_info);
                            }
                        }
                        
                        if(!isset($navBar['titleContent']) || !$navBar['titleContent'])
                        {
                            //导航栏标题取应用的名称
                            $navBar['titleContent']['text'] = $app_info['name'];
                        }
                    }
                    
                    //导航栏高斯模糊根据全局的来
                    if(isset($appUIInfo['attr']['navBar']['isBlur']))
                    {
                        $navBar['isBlur'] = (bool)$appUIInfo['attr']['navBar']['isBlur'];
                    }
                    else 
                    {
                        $navBar['isBlur'] = $this->settings['module_default']['isBlur'];
                    }

                    $module_info[$k]['navbar'] = $navBar;
                }
                else 
                {
                    $navBar = array();
                    //全局导航栏背景
                    if(isset($appUIInfo['attr']['navBar']['bg']) && is_array($appUIInfo['attr']['navBar']['bg']))
                    {
                        //如果全局首页背景设置的是颜色
                        if($appUIInfo['attr']['navBar']['bg']['color'])
                        {
                            $navBar['bg'] = array('color' => $appUIInfo['attr']['navBar']['bg']['color'],'alpha' => $appUIInfo['attr']['navBar']['bg']['alpha']);
                        }
                        elseif ($appUIInfo['attr']['navBar']['bg']['img_id'])
                        {
                            $_img_info = $this->app_material->detail('app_material', array('id' => $appUIInfo['attr']['navBar']['bg']['img_id']));
                            $navBar['bg'] = array('img' => $_img_info,'is_tile' => $appUIInfo['attr']['navBar']['bg']['isRepeat']);
                        }
                    }

                    //全局导航栏标题内容
                    if(isset($appUIInfo['attr']['navBar']['titleContent']) && is_array($appUIInfo['attr']['navBar']['titleContent']))
                    {
                        //如果全局导航栏标题是文字
                        if($appUIInfo['attr']['navBar']['titleContent']['text'])
                        {
                            $navBar['titleContent'] = array('text' => $appUIInfo['attr']['navBar']['titleContent']['text']);
                        }
                        elseif ($appUIInfo['attr']['navBar']['titleContent']['img_id'])
                        {
                            $_img_info = $this->app_material->detail('app_material', array('id' => $appUIInfo['attr']['navBar']['titleContent']['img_id']));
                            $navBar['titleContent'] = array('img' => $_img_info);
                        }
                    }
                    
                    if(!isset($navBar['bg']) || !$navBar['bg'])
                    {
                        $navBar['bg'] = array(
                            'color' => $this->settings['module_default']['navbar']['bg'],
                            'alpha' => 1,
                        );
                    }
                    
                    if(!isset($navBar['titleContent']) || !$navBar['titleContent'])
                    {
                        //导航栏标题取应用的名称
                        $navBar['titleContent']['text'] = $app_info['name'];
                    }
                    
                    //导航栏高斯模糊根据全局的来
                    if(isset($appUIInfo['attr']['navBar']['isBlur']))
                    {
                        $navBar['isBlur'] = (bool)$appUIInfo['attr']['navBar']['isBlur'];
                    }
                    else 
                    {
                        $navBar['isBlur'] = $this->settings['module_default']['isBlur'];
                    }
                    $module_info[$k]['navbar'] = $navBar;
                }
                
                //处理页面背景
                if($module['ui_bg'])
                {
                    $_uiBg = explode('|', $module['ui_bg']);
                    if(isset($_uiBg[0]) && $_uiBg[0])
                    {
                        if($_uiBg[0] == 'img')
                        {
                            $_img_info = $this->app_material->detail('app_material', array('id' => $_uiBg[1]));
                            $module_info[$k]['ui_bg'] = array('img' => $_img_info,'is_tile' => $_uiBg[2]);
                        }
                        elseif ($_uiBg[0] == 'color')
                        {
                            $module_info[$k]['ui_bg'] = array('color' => $_uiBg[1],'alpha' => $_uiBg[2]);
                        }
                    }
                }
                else 
                {
                    //全局的页面背景
                    if(isset($appUIInfo['attr']['uiBg']) && is_array($appUIInfo['attr']['uiBg']))
                    {
                        //如果全局首页背景设置的是颜色
                        if($appUIInfo['attr']['uiBg']['color'])
                        {
                            $module_info[$k]['ui_bg'] = array('color' => $appUIInfo['attr']['uiBg']['color'],'alpha' => $appUIInfo['attr']['uiBg']['alpha']);
                        }
                        elseif ($appUIInfo['attr']['uiBg']['img_id'])
                        {
                            $_img_info = $this->app_material->detail('app_material', array('id' => $appUIInfo['attr']['uiBg']['img_id']));
                            $module_info[$k]['ui_bg'] = array('img' => $_img_info,'is_tile' => $appUIInfo['attr']['uiBg']['isRepeat']);
                        }
                    }
                    
                    //经过上面的洗礼，还是没有值的话，那就只能取默认的了
                    if(!$module_info[$k]['ui_bg'])
                    {
                        $module_info[$k]['ui_bg'] = array('color' => $this->settings['module_default']['ui_bg'],'alpha' => 1);
                    }
                }
                
                //如果用户没有在module_list里面设置,就取外面到main_ui里面到main_color
                if(!$module['main_color'])
                {
                    if(isset($appUIInfo['attr']['mainColor']) && is_array($appUIInfo['attr']['mainColor']) && !isset($appUIInfo['attr']['mainColor']['noColor']))
                    {
                        $module_info[$k]['main_color'] = $appUIInfo['attr']['mainColor']['color'];
                    }
                    else 
                    {
                        $module_info[$k]['main_color'] = $this->settings['module_default']['main_color'];
                    }
                }
                
                //if ($module['ui_id']) $ui_id[$module['ui_id']] = $module['ui_id'];
                if($module['ui_id'])
                {
                    //获取该LIST_UI的信息
                    $listUIInfo = $this->ui_mode->detail($module['ui_id']);
                    if($listUIInfo)
                    {
                        //获取分组数据
                        $groupData = $this->ui_value_mode->getGroupData();
                        //如果用户选择的时自由组合
                        if($listUIInfo['uniqueid'] == 'ListUI10')
                        {
                            $_comp_ui = $this->createCompConfig($module['id']);
                            if($_comp_ui)
                            {
                                $listUIInfo['attr']['components'] = $_comp_ui;
                            }
                            else 
                            {
                                $listUIInfo['attr']['components'] = array();
                            }
                        }
                        else
                        {
                            //获取属性
                            $listAttrData = $this->ui_value_mode->getAttributeData($module['ui_id'],TRUE,0,$module['id'],1,$user_info['dingdone_role_id']);
                            if($listAttrData)
                            {
                                $listUIConfig = $this->buildTreeDataConfig(0, $groupData,$listAttrData);
                                foreach ($listUIConfig AS $kk => $vv)
                                {
                                    foreach ($vv AS $_kk => $_vv)
                                    {
                                        if($_kk == 'list_ui')
                                        {
                                            $listUIInfo['attr'] = $_vv;
                                            //看看该有没有组件插入到该listUI
                                            if(in_array($listUIInfo['uniqueid'], array('ListUI1','ListUI2','ListUI7')))
                                            {
                                                $_comp_ui = $this->createCompConfig($module['id']);
                                                if($_comp_ui)
                                                {
                                                    $listUIInfo['attr']['components'] = $_comp_ui;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                            //获取模块扩展字段
                            $extend_fields = $this->extend_mode->getExtendFieldByModuleId($module['id']," AND is_display = 1 ");
                            if($extend_fields)
                            {
                                $listUIInfo['extend_fields'] = $extend_fields;
                            }
                            
                            
                            
                            
                            //获取新扩展字段
                            $new_extends = array();
                            //先获取这个模块扩展区域的基本信息 //自适应还是固定高度
                            $new_extends['showType'] = $module['extend_area_position'];
                            //获取上下部分区域   $module['extend_area_position'] =2时 只取UP部分
                            $up_area = array();
                            $up_area_lines = $this->new_extend->getInfos('new_extend_line',array(
                            		'module_id'		=> $module['id'],
                            		'line_position'	=> $this->settings['new_extend']['line_position']['up'],
                            ),'order by order_id asc');
                            if($up_area_lines)
                            {
                            	foreach ($up_area_lines as $___k => $___v)
                            	{
                            		$one_line = array();
                            		//获取这个行的后台属性
                            		$line_attr = $this->new_extend->getExtendLineAttributeData($___v['id']);
                            		if($line_attr)
                            		{
                            			$lineAttrConfig = $this->buildTreeDataConfig(0, $groupData,$line_attr);
                            			if($lineAttrConfig && $lineAttrConfig[0]['extendLine'])
                            			{
                            				$one_line = $lineAttrConfig[0]['extendLine'];
                            			}
                            		}
                            		$one_line['lineNum'] = $___v['line_num'];
                            		//取对应扩展行里的扩展单元 
                            		//单行 多行区分
                            		
                            		if($___v['line_type'] == 1)//单行模式
                            		{
                            			$left_line_fields = $this->new_extend->getInfos('new_extend_field',array(
                            					'field_position' => $this->settings['new_extend']['field_position']['left'],
                            					'line_id'		 => $___v['id'],
                            					'module_id'		 => $module['id'],
                            			),'order by order_id asc');
                            			if($left_line_fields)
                            			{
                            				$left_fields = array();
                            				foreach ($left_line_fields as $f_k => $f_v)
                            				{
                            					$one_left_field = array();
                            					//对左边单元进行处理，取出属性
                            					//取出对应单元的后台属性
                            					$left_field_attr = $this->new_extend->getExtendFieldAttributeData($f_v['id']);
                            					if($left_field_attr)
                            					{
                            						$left_field_config = $this->buildTreeDataConfig(0, $groupData, $left_field_attr);
                            						$one_left_field = $left_field_config[0]['extendLine']['extendField'];         						
                            					}
                            					$one_left_field['type'] = $f_v['field_type'];
                            					$one_left_field['style'] = $this->settings['new_extend']['price_style'];
                            					//处理显示样式
                            					$display_arr = $this->processFieldStyle($f_v['id'],$f_v['style_type']);
                            					if($display_arr && is_array($display_arr))
                            					{
                            						foreach ($display_arr as $kk => $vv)
                            						{
                            							$one_left_field[$kk] = $vv;
                            						}
                            					}
                            					//处理颜色
                            					if(array_key_exists('textColor', $one_left_field))
                            					{
                            						
                            						$color_arr = $this->processExtendColor($one_left_field['textColor']);
                            						unset($one_left_field['textColor']);
                            						foreach ($color_arr as $ck => $cv)
                            						{
                            							$one_left_field['textColor'][$ck] = $cv;
                            						}
                            						
                            					}
                            					//处理indexContent
                            					if($one_left_field['indexContent'] == '空格')
                            					{
                            						$one_left_field['indexContent'] = ' ';
                            					}
                            					if($f_v['style_type'] == 2 || $f_v['style_type'] == 4)
                            					{
                            						$one_left_field['indexContent'] = ''; 
                            					}
                            					
                            					if($one_left_field['marginLeft'] !== '')
                            					{
                            						$left_fields[] = $one_left_field;
                            					}
                            					
                            				}
                            				$one_line['left'] = $left_fields;
                            			}                          			
                            			$right_line_fields = $this->new_extend->getInfos('new_extend_field',array(
                            					'field_position' => $this->settings['new_extend']['field_position']['right'],
                            					'line_id'		 => $___v['id'],
                            					'module_id'		 => $module['id'],
                            			),'order by order_id asc');
                            			if($right_line_fields)
                            			{
                            				$right_fields = array();
                            				foreach ($right_line_fields as $r_k => $r_v)
                            				{
                            					$one_right_field = array();
                            					//对左边单元进行处理，取出属性
                            					//取出对应单元的后台属性
                            					$right_field_attr = $this->new_extend->getExtendFieldAttributeData($r_v['id']);
                            					if($right_field_attr)
                            					{
                            						$right_field_config = $this->buildTreeDataConfig(0, $groupData, $right_field_attr);
                            						$one_right_field = $right_field_config[0]['extendLine']['extendField'];
                            					}
                            					$one_right_field['type'] = $r_v['field_type'];
                            					//处理显示样式
                            					$display_arr = $this->processFieldStyle($r_v['id'],$r_v['style_type']);
                            					if($display_arr && is_array($display_arr))
                            					{
                            						foreach ($display_arr as $kk => $vv)
                            						{
                            							$one_right_field[$kk] = $vv;
                            						}
                            					}
                            					//处理颜色
                            					if(array_key_exists('textColor', $one_right_field))
                            					{
                            						
                            						$color_arr = $this->processExtendColor($one_right_field['textColor']);
                            						unset($one_right_field['textColor']);
                            						foreach ($color_arr as $ck => $cv)
                            						{
                            							$one_right_field['textColor'][$ck] = $cv;
                            						}
                            						
                            					}
                            					//处理indexContent
                            					if($one_right_field['indexContent'] == '空格')
                            					{
                            						$one_right_field['indexContent'] = ' ';
                            					}
                            					if($r_v['style_type'] == 2 || $r_v['style_type'] == 4)
                            					{
                            						$one_right_field['indexContent'] = '';
                            					}
                            					
                            					$one_right_field['style'] = $this->settings['new_extend']['price_style'];
                            					if($one_right_field['marginLeft'] !== '')
                            					{
                            						$right_fields[] = $one_right_field;	
                            					}
                            				}                           				
                            				$one_line['right'] = $right_fields;                         				
                            			}
                            		}
                            		elseif ($___v['line_type'] == 2)//多行模式
                            		{
                            			//左边一个为left 取出
                            			$left_line_fields = $this->new_extend->getInfos('new_extend_field',array(
                            					'field_position' => $this->settings['new_extend']['field_position']['left'],
                            					'line_id'		 => $___v['id'],
                            					'module_id'		 => $module['id'],
                            			),'order by order_id asc');
                            			if($left_line_fields)
                            			{
                            				$left_fields = array();
                            				$one_left_field = array();
                            				//对左边单元进行处理，取出属性
                            				//取出对应单元的后台属性
                            				$left_field_attr = $this->new_extend->getExtendFieldAttributeData($left_line_fields[0]['id']);
                            				if($left_field_attr)
                            				{
                            					$left_field_config = $this->buildTreeDataConfig(0, $groupData, $left_field_attr);
                            					$one_left_field = $left_field_config[0]['extendLine']['extendField'];
                            				}
                            				$one_left_field['type'] = $left_line_fields[0]['field_type'];
                            				//处理显示样式
                            				$display_arr = $this->processFieldStyle($left_line_fields[0]['id'],$left_line_fields[0]['style_type']);
                            				if($display_arr && is_array($display_arr))
                            				{
                            					foreach ($display_arr as $kk => $vv)
                            					{
                            						$one_left_field[$kk] = $vv;
                            					}
                            				}
                            				//处理颜色
                            				if(array_key_exists('textColor', $one_left_field))
                            				{
                            					
                            					$color_arr = $this->processExtendColor($one_left_field['textColor']);
                            					unset($one_left_field['textColor']);
                            					foreach ($color_arr as $ck => $cv)
                            					{
                            						$one_left_field['textColor'][$ck] = $cv;
                            					}
                            					
                            				}
                            				//处理indexContent
                            				if($one_left_field['indexContent'] == '空格')
                            				{
                            					$one_left_field['indexContent'] = ' ';
                            				}
                            				if($left_line_fields[0]['style_type'] == 2 || $left_line_fields[0]['style_type'] == 4)
                            				{
                            					$one_left_field['indexContent'] = '';
                            				}
                            				
                            				
                            				$one_left_field['style'] = $this->settings['new_extend']['price_style'];
                            				if($one_left_field['marginLeft'] !== '')
                            				{
                            					$left_fields[] = $one_left_field;
                            				}
                            				$one_line['left'] = $left_fields;                         				                 				
                            			}
                            			//其他都为right
                            			$right_line_fields = $this->new_extend->getInfos('new_extend_field',array(
                            					'field_position' => $this->settings['new_extend']['field_position']['right'],
                            					'line_id'		 => $___v['id'],
                            					'module_id'		 => $module['id'],
                            			),'order by order_id asc');
                            			if($right_line_fields)
                            			{
                            				$right_fields = array();
                            				foreach ($right_line_fields as $r__k => $r__v)
                            				{
                            					$one_right_field = array();
                            					//对左边单元进行处理，取出属性
                            					//取出对应单元的后台属性
                            					$right_field_attr = $this->new_extend->getExtendFieldAttributeData($r__v['id']);
                            					if($right_field_attr)
                            					{
                            						$right_field_config = $this->buildTreeDataConfig(0, $groupData, $right_field_attr);
                            						$one_right_field = $right_field_config[0]['extendLine']['extendField'];
                            					}
                            					$one_right_field['type'] = $r__v['field_type'];
                            					//处理显示样式
                            					$display_arr = $this->processFieldStyle($r__v['id'],$r__v['style_type']);
                            					if($display_arr && is_array($display_arr))
                            					{
                            						foreach ($display_arr as $kk => $vv)
                            						{
                            							$one_right_field[$kk] = $vv;
                            						}
                            					}
                            					//处理颜色
                            					if(array_key_exists('textColor', $one_right_field))
                            					{
                            						
                            						$color_arr = $this->processExtendColor($one_right_field['textColor']);
                            						unset($one_right_field['textColor']);
                            						foreach ($color_arr as $ck => $cv)
                            						{
                            							$one_right_field['textColor'][$ck] = $cv;
                            						}
                            						
                            					}
                            					//处理indexContent
                            					if($one_right_field['indexContent'] == '空格')
                            					{
                            						$one_right_field['indexContent'] = ' ';
                            					}
                            					if($r__v['style_type'] == 2 || $r__v['style_type'] ==4)
                            					{
                            						$one_right_field['indexContent'] = '';
                            					}
                            					$one_right_field['style'] = $this->settings['new_extend']['price_style'];
                            					if($one_right_field['marginLeft'] !== '')
                            					{
                            						$right_fields[] = $one_right_field;
                            					} 
                            				}
                            				
                            				$one_line['right'] = $right_fields;
                            				                       				
                            			}		
                            		}          		
                            		$up_area[] = $one_line;
                            	}
                            	$new_extends['up'] = $up_area;
                            }                     
                            //如果固定高度情况下，考虑下部区域
                            if($module['extend_area_position'] == 1)
                            {
                            	$down_area = array();
                            	//获取down部分
                            	$down_area_lines = $this->new_extend->getInfos('new_extend_line',array(
                            			'module_id'		=> $module['id'],
                            			'line_position'	=> $this->settings['new_extend']['line_position']['down'],
                            	),'order by order_id asc');
                            	if($down_area_lines)
                            	{
                            		foreach ($down_area_lines as $____k => $____v)
                            		{
                            			$one_line = array();
                            			//获取这个行的后台属性
                            			$down_line_attr = $this->new_extend->getExtendLineAttributeData($____v['id']);
                            			if($down_line_attr)
                            			{
                            				$downLineAttrConfig = $this->buildTreeDataConfig(0, $groupData,$down_line_attr);
                            				if($downLineAttrConfig && $downLineAttrConfig[0]['extendLine'])
                            				{
                            					$one_line = $downLineAttrConfig[0]['extendLine'];
                            				}
                            			}
                            			$one_line['lineNum'] = $____v['line_num'];
                            			//处理扩展单元
                            			//单行模式
                            			if($____v['line_type'] == 1)
                            			{
                            				$left_line_fields = $this->new_extend->getInfos('new_extend_field',array(
                            						'field_position' => $this->settings['new_extend']['field_position']['left'],
                            						'line_id'		 => $____v['id'],
                            						'module_id'		 => $module['id'],
                            				),'order by order_id asc');
                            				if($left_line_fields)
                            				{
                            					$left_fields = array();
                            					foreach ($left_line_fields as $f__k => $f__v)
                            					{
                            						$one_left_field = array();
                            						//对左边单元进行处理，取出属性
                            						//取出对应单元的后台属性
                            						$left_field_attr = $this->new_extend->getExtendFieldAttributeData($f__v['id']);
                            						if($left_field_attr)
                            						{
                            							$left_field_config = $this->buildTreeDataConfig(0, $groupData, $left_field_attr);
                            							$one_left_field = $left_field_config[0]['extendLine']['extendField'];
                            						}
                            						$one_left_field['type'] = $f__v['field_type'];
                            						$one_left_field['style'] = $this->settings['new_extend']['price_style'];
                            						//处理显示样式
                            						$display_arr = $this->processFieldStyle($f__v['id'],$f__v['style_type']);
                            						if($display_arr && is_array($display_arr))
                            						{
                            							foreach ($display_arr as $kkk => $vvv)
                            							{
                            								$one_left_field[$kkk] = $vvv;
                            							}
                            						}        
                            						//处理颜色
                            						if(array_key_exists('textColor', $one_left_field))
                            						{
                            							
                            							$color_arr = $this->processExtendColor($one_left_field['textColor']);
                            							unset($one_left_field['textColor']);
                            							foreach ($color_arr as $ck => $cv)
                            							{
                            								$one_left_field['textColor'][$ck] = $cv;
                            							}
                            							
                            						}	
                            						//处理indexContent
                            						if($one_left_field['indexContent'] == '空格')
                            						{
                            							$one_left_field['indexContent'] = ' ';
                            						}		
                            						if($f__v['style_type'] == 2 || $f__v['style_type'] == 4)
                            						{
                            							$one_left_field['indexContent'] = '';
                            						}	
                            						if($one_left_field['marginLeft'] !== '')
                            						{
                            							$left_fields[] = $one_left_field;
                            						}		 
                            						
                            					}
                            					$one_line['left'] = $left_fields;
                            				}
                            				$right_line_fields = $this->new_extend->getInfos('new_extend_field',array(
                            						'field_position' => $this->settings['new_extend']['field_position']['right'],
                            						'line_id'		 => $____v['id'],
                            						'module_id'		 => $module['id'],
                            				),'order by order_id asc');
                            				if($right_line_fields)
                            				{
                            					$right_fields = array();
                            					foreach ($right_line_fields as $r__k => $r__v)
                            					{
                            						$one_right_field = array();
                            						//对左边单元进行处理，取出属性
                            						//取出对应单元的后台属性
                            						$right_field_attr = $this->new_extend->getExtendFieldAttributeData($r__v['id']);
                            						if($right_field_attr)
                            						{
                            							$right_field_config = $this->buildTreeDataConfig(0, $groupData, $right_field_attr);
                            							$one_right_field = $right_field_config[0]['extendLine']['extendField'];
                            						}
                            						$one_right_field['type'] = $r__v['field_type'];
                            						//处理显示样式
                            						$display_arr = $this->processFieldStyle($r__v['id'],$r__v['style_type']);
                            						if($display_arr && is_array($display_arr))
                            						{
                            							foreach ($display_arr as $kkkk => $vvvv)
                            							{
                            								$one_right_field[$kkkk] = $vvvv;
                            							}
                            						}
                            						//处理颜色
                            						if(array_key_exists('textColor', $one_right_field))
                            						{
                            							
                            							$color_arr = $this->processExtendColor($one_right_field['textColor']);
                            							unset($one_right_field['textColor']);
                            							foreach ($color_arr as $ck => $cv)
                            							{
                            								$one_right_field['textColor'][$ck] = $cv;
                            							}
                            							
                            						} 
                            						//处理indexContent
                            						if($one_right_field['indexContent'] == '空格')
                            						{
                            							$one_right_field['indexContent'] = ' ';
                            						}
                            						if($r__v['style_type'] == 2 || $r__v['style_type'] == 4)
                            						{
                            							$one_right_field['indexContent'] = '';
                            						}
                            						$one_right_field['style'] = $this->settings['new_extend']['price_style'];
                            						if($one_right_field['marginLeft'] !== '')
                            						{
                            							$right_fields[] = $one_right_field;
                            						}
                            						
                            					}
                            					$one_line['right'] = $right_fields;
                            				}
                            			}
                            			elseif($____v['line_type'] == 2)//多行模式
                            			{
                            				//左边一个为left 取出
                            				$left_line_fields = $this->new_extend->getInfos('new_extend_field',array(
                            						'field_position' => $this->settings['new_extend']['field_position']['left'],
                            						'line_id'		 => $____v['id'],
                            						'module_id'		 => $module['id'],
                            				),'order by order_id asc');
                            				if($left_line_fields)
                            				{
                            					$left_fields = array();
                            					$one_left_field = array();
                            					//对左边单元进行处理，取出属性
                            					//取出对应单元的后台属性
                            					$left_field_attr = $this->new_extend->getExtendFieldAttributeData($left_line_fields[0]['id']);
                            					if($left_field_attr)
                            					{
                            						$left_field_config = $this->buildTreeDataConfig(0, $groupData, $left_field_attr);
                            						$one_left_field = $left_field_config[0]['extendLine']['extendField'];
                            					}
                            					$one_left_field['type'] = $left_line_fields[0]['field_type'];
                            					//处理显示样式
                            					$display_arr = $this->processFieldStyle($left_line_fields[0]['id'],$left_line_fields[0]['style_type']);
                            					if($display_arr && is_array($display_arr))
                            					{
                            						foreach ($display_arr as $_kk => $_vv)
                            						{
                            							$one_left_field[$_kk] = $_vv;
                            						}
                            					}			
                            					//处理颜色
                            					if(array_key_exists('textColor', $one_left_field))
                            					{
                            						
                            						$color_arr = $this->processExtendColor($one_left_field['textColor']);
                            						unset($one_left_field['textColor']);
                            						foreach ($color_arr as $ck => $cv)
                            						{
                            							$one_left_field['textColor'][$ck] = $cv;
                            						}
                            						
                            					}
                            					//处理indexContent
                            					if($one_left_field['indexContent'] == '空格')
                            					{
                            						$one_left_field['indexContent'] = ' ';
                            					}
                            					if($left_line_fields[0]['style_type'] == 2 || $left_line_fields[0]['style_type'] == 4)
                            					{
                            						$one_left_field['indexContent'] = '';
                            					}
                            					$one_left_field['style'] = $this->settings['new_extend']['price_style'];
                            					if($one_left_field['marginLeft'] !== '')
                            					{
                            						$left_fields[] = $one_left_field;
                            					}
                            					
                            					$one_line['left'] = $left_fields;
                            				}
                            				//其他都为right
                            				$right_line_fields = $this->new_extend->getInfos('new_extend_field',array(
                            						'field_position' => $this->settings['new_extend']['field_position']['right'],
                            						'line_id'		 => $____v['id'],
                            						'module_id'		 => $module['id'],
                            				),'order by order_id asc');
                            				if($right_line_fields)
                            				{
                            					$right_fields = array();
                            					foreach ($right_line_fields as $r__k => $r__v)
                            					{
                            						$one_right_field = array();
                            						//对左边单元进行处理，取出属性
                            						//取出对应单元的后台属性
                            						$right_field_attr = $this->new_extend->getExtendFieldAttributeData($r__v['id']);
                            						if($right_field_attr)
                            						{
                            							$right_field_config = $this->buildTreeDataConfig(0, $groupData, $right_field_attr);
                            							$one_right_field = $right_field_config[0]['extendLine']['extendField'];
                            						}
                            						$one_right_field['type'] = $r__v['field_type'];
                            						//处理显示样式
                            						$display_arr = $this->processFieldStyle($r__v['id'],$r__v['style_type']);
                            						if($display_arr && is_array($display_arr))
                            						{
                            							foreach ($display_arr as $kk => $vv)
                            							{
                            								$one_right_field[$kk] = $vv;
                            							}
                            						}
                            						//处理颜色
                            						if(array_key_exists('textColor', $one_right_field))
                            						{
                            							
                            							$color_arr = $this->processExtendColor($one_right_field['textColor']);
                            							unset($one_right_field['textColor']);
                            							foreach ($color_arr as $ck => $cv)
                            							{
                            								$one_right_field['textColor'][$ck] = $cv;
                            							}
                            							
                            						}
                            						//处理indexContent
                            						if($one_right_field['indexContent'] == '空格')
                            						{
                            							$one_right_field['indexContent'] = ' ';
                            						}
                            						if($r__v['style_type'] == 2 || $r__v['style_type'] == 4)
                            						{
                            							$one_right_field['indexContent'] = '';
                            						}
                            						$one_right_field['style'] = $this->settings['new_extend']['price_style'];
                            						if($one_right_field['marginLeft'] !== '')
                            						{
                            							$right_fields[] = $one_right_field;
                            						}
                            						
                            					}
                            					$one_line['right'] = $right_fields;
                            				}
                            			}
                            			$down_area[] = $one_line;
                            		}
                            	}
                            	$new_extends['down'] = $down_area;
                            }
                            $listUIInfo['extendLayout'] = $new_extends;  
                            //获取角标样式
                            $cornerStyle = $this->extend_mode->getCornerData($module['id']);
                            if($cornerStyle)
                            {
                                $listUIInfo['corner_style'] = $cornerStyle;
                            }
                            
                            //构建新角标的配置
                            //首先获取当前模块使用角标的情况
                            $cornerUseInfo = $this->_corner_mode->getUseCornerInfoByModId($module['id']);
                            if($cornerUseInfo && !array_key_exists('ErrorCode', $cornerUseInfo))
                            {
                                $corners = array('showType' => (int)$module['corner_show_type']);
                                foreach($cornerUseInfo AS $_key => $_value)
                                {
                                    //获取该用户这次使用的角标对应的属性
                                    $_corner_attr = $this->ui_value_mode->getCornerAttributeData($_value['mod_corner_id'],MODULE_USE_SUPERSCRIPT);
                                    if($_corner_attr)
                                    {
                                        //对数据整一下型
                                        $cornerAttrConfig = $this->buildTreeDataConfig(0, $groupData,$_corner_attr);
                                        if($cornerAttrConfig && isset($cornerAttrConfig[0]['corner']))
                                        {
                                            $_itemCornerAttr = $cornerAttrConfig[0]['corner'];
                                            $_itemCornerAttr['key']  = $_value['field_type'];
                                            $_itemCornerAttr['text'] = $_value['text'];
                                            $_itemCornerAttr['mod_corner_id'] = $_value['mod_corner_id'];
                                            //增加superscript_id
                                            $_itemCornerAttr['superscript_id'] = $_value['id'];
                                            
                                            //系统图标
                                            if(intval($_value['img_type']) == 1)
                                            {
                                                $_icon_arr = explode('.', $_value['img_info']);
                                                $_itemCornerAttr['icon'] = 'dd_' . $_icon_arr[0];
                                                $_itemCornerAttr['url'] = $this->settings['base_url'] . $this->settings['superscript']['save_path'] . $_value['img_info'];
                                            }
                                            elseif(intval($_value['img_type']) == 2)//用户自定义上传的
                                            {
                                                if($_value['img_info'] && is_array($_value['img_info']))
                                                {
                                                    $_icon_arr = explode('.', $_value['img_info']['filename']);
                                                    $_itemCornerAttr['icon'] = 'dd_' . $_icon_arr[0];
                                                    //此处记录url，以便后期提交打包服务器是把图片下载到工程里面
                                                    $_itemCornerAttr['url'] = $_value['img_info']['host'] . $_value['img_info']['filepath'] . $_value['img_info']['filename'];
                                                }
                                                else 
                                                {
                                                    $_itemCornerAttr['icon'] = '';
                                                    $_itemCornerAttr['url'] = '';
                                                }
                                            }
                                            else
                                            {
                                                $_itemCornerAttr['icon'] = '';
                                                $_itemCornerAttr['url'] = '';
                                            }
                                            //计算图片宽高比
                                            $_itemCornerAttr['hwScale'] = Common::getImageAspect($_itemCornerAttr['url']);
                                            $corners['corner' . ($_key + 1)] = $_itemCornerAttr;
                                        }
                                    }
                                }
                                $listUIInfo['attr']['corners'] = $corners;
                            }
                        }
                        
                        $module_info[$k]['ui'] = $listUIInfo;
                    }
                }
            }
            $app_info['module'] = $module_info;
        }

        if ($this->input['flag'])
        {
        	$app_info['unpack'] = 1;
        	/*
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
            */
        }
        $this->addItem($app_info);
        $this->output();
    }
    
    //生成组件的相关配置
    private function createCompConfig($module_id)
    {
        //保存组件listUI配置
        $_comp_ui = array();
        //保存绑定的组件的信息
        $_compInfo = array();
        //获取该模块绑定的组件
        $compIdsArr = $this->_comp_mode->getCompIdsByCond(" AND module_id = '" . $module_id . "' ORDER BY order_id ASC ",'comp_id');
        if($compIdsArr)
        {
            $_comp_ids = array_keys($compIdsArr);
            if($_comp_ids)
            {
                $_compInfo = $this->_comp_mode->getCompWithSource(" AND c.id IN (" .implode(',',$_comp_ids). ") ",'','',1); 
            }
        }
        if($_compInfo)
        {
            //获取分组数据
            $groupData = $this->ui_value_mode->getGroupData();
            //遍历获取组件的相关配置的值
            foreach($compIdsArr AS $_item => $_comp_v)
            {
                $complistAttrData = $this->ui_value_mode->getCompAttributeData($_compInfo[$_item]['ui_id'],$_item);
                if($complistAttrData)
                {
                    $listUIConfig = $this->buildTreeDataConfig(0, $groupData,$complistAttrData);
                    foreach ($listUIConfig AS $kk => $vv)
                    {
                        foreach ($vv AS $_kk => $_vv)
                        {
                            if($_kk == 'list_ui')
                            {
                                $_vv['id']        = $_item;//组件id
                                $_vv['name']      = $_compInfo[$_item]['name'];//组件名称
                                $_vv['itemName']  = $_compInfo[$_item]['listui_mark'];//组件来源标识
                                $_vv['itemCount'] = $_compInfo[$_item]['nums'];//数据个数

                                //获取该组件的扩展字段
                                $extend_fields = $this->_comp_mode->getCompExtendField($_item," AND is_display = 1 ");
                                if($extend_fields)
                                {
                                    $_format_extend = Common::getFormatExtendConfig($extend_fields);
                                    if($_format_extend)
                                    {
                                        if(array_key_exists('advanced', $_format_extend))
                                        {
                                            $_vv['advanced'] = $_format_extend['advanced'];
                                        }
                                        
                                        if(array_key_exists('extend', $_format_extend))
                                        {
                                            $_vv['extend'] = $_format_extend['extend'];
                                        }
                                    }
                                }
                                
                                //获取该组件对应的角标
//                                 $cornerStyle = $this->_comp_mode->getCompCornerData($_item);
//                                 if($cornerStyle)
//                                 {
//                                     $_format_corner = Common::getFormatCornerConfig($cornerStyle);
//                                     if($_format_corner)
//                                     {
//                                         $_vv['corner'] = $_format_corner;
//                                     }
//                                 }
                                
                                /*********组件新扩展字段配置****************/
                               	//先看此组件有没有设置新扩展字段
                               	$extendLayout = array();
                                $_one_comp = $this->new_extend->detail('components', array('id' => $_item));
                                $extend_area_position = $_one_comp['extend_area_position'];
                                $user_id = $this->user['user_id'];
                                $up = array();
                                $down = array();
                                $extendLayout['showType'] = $_one_comp['extend_area_position']; 
                               	
                                //自适应，，取出上所有的行
                                $up_lines = $this->new_extend->getInfos('comp_extend_line',array(
                                		'comp_id' => $_item,
                                		'user_id'	=> $user_id,
                                		'line_position' => $this->settings['new_extend']['line_position']['up'],
                                ),'order by order_id asc');
                                if($up_lines)
                                {
	                                foreach ($up_lines as $k => $v)
	                                {
	                                	$one_line = array();
	                                	//取行的属性
	                                	//获取这个行的后台属性
	                                	$line_attr = $this->_comp_mode->getExtendLineAttributeData($v['id']);
	                                	if($line_attr)
	                                	{
	                                		$lineAttrConfig = $this->buildTreeDataConfig(0, $groupData,$line_attr);
	                                		if($lineAttrConfig && $lineAttrConfig[0]['extendLine'])
	                                		{
	                                			$one_line = $lineAttrConfig[0]['extendLine'];
	                                		}
	                                	}
	                                	$one_line['lineNum'] = $v['line_num'];                          	
	                                	//单行模式
	                                	//先取出左
	                                	$left_all_fields = $this->new_extend->getInfos('comp_extend_field',array(
	                                			'comp_id'	=> $_item,
	                                			'line_id'	=> $v['id'],
	                                			'field_position'	=> $this->settings['new_extend']['field_position']['left'],
	                                			'user_id'	=> $user_id,
	                                	),'order by order_id asc');     
	                                	$left_fields = array();
	                                	if($left_all_fields)
	                                	{              
	                                		//单行模式
	                                		if($v['line_type'] == $this->settings['new_extend']['line_type']['one'])
	                                		{
	                                			foreach ($left_all_fields as $l_k => $l_v)
	                                			{
	                                				$one_left_field = array();
	                                					 
	                                				//对左边单元进行处理，取出属性
	                                				//取出对应单元的后台属性
	                                				$left_field_attr = $this->_comp_mode->getExtendFieldAttributeData($l_v['id']);
	                                				if($left_field_attr)
	                                				{
	                                					$left_field_config = $this->buildTreeDataConfig(0, $groupData, $left_field_attr);
	                                					$one_left_field = $left_field_config[0]['extendLine']['extendField'];
	                                				}
	                                				$one_left_field['type'] = $l_v['field_type'];
	                                				$one_left_field['style'] = $this->settings['new_extend']['price_style'];
	                                				//处理显示样式
	                                				$display_arr = $this->processFieldStyle($l_v['id'],$l_v['style_type'],'comp_extend_field');
	                                				if($display_arr && is_array($display_arr))
	                                				{
	                                					foreach ($display_arr as $kk => $vv)
	                                					{
	                                						$one_left_field[$kk] = $vv;
	                                					}
	                                				}
	                                				//处理颜色
	                                				if(array_key_exists('textColor', $one_left_field))
	                                				{
	                                				
	                                					$color_arr = $this->processExtendColor($one_left_field['textColor']);
	                                					unset($one_left_field['textColor']);
	                                					foreach ($color_arr as $ck => $cv)
	                                					{
	                                						$one_left_field['textColor'][$ck] = $cv;
	                                					}
	                                				
	                                				}
	                                				//处理indexContent
	                                				if($one_left_field['indexContent'] == '空格')
	                                				{
	                                					$one_left_field['indexContent'] = ' ';
	                                				}
	                                				if($l_v['style_type'] == 2 || $l_v['style_type'] == 4)
	                                				{
	                                					$one_left_field['indexContent'] = '';
	                                				}
	                                				 
	                                				if($one_left_field['marginLeft'] !== '')
	                                				{
	                                					$left_fields[] = $one_left_field;
	                                				}
	                                			}
	                                		}    
	                                		//多行模式         				
	                                		elseif ($v['line_type'] == $this->settings['new_extend']['line_type']['much'])
	                                		{
	                                			$one_left_field = array();      			
	                                			//对左边单元进行处理，取出属性
	                                			//取出对应单元的后台属性
	                                			$left_field_attr = $this->_comp_mode->getExtendFieldAttributeData($left_all_fields[0]['id']);
	                                			if($left_field_attr)
	                                			{
	                                				$left_field_config = $this->buildTreeDataConfig(0, $groupData, $left_field_attr);
	                                				$one_left_field = $left_field_config[0]['extendLine']['extendField'];
	                                			}
	                                			$one_left_field['type'] = $left_all_fields[0]['field_type'];
	                                			$one_left_field['style'] = $this->settings['new_extend']['price_style'];
	                                			//处理显示样式
	                                			$display_arr = $this->processFieldStyle($left_all_fields[0]['id'],$left_all_fields[0]['style_type'],'comp_extend_field');
	                                			if($display_arr && is_array($display_arr))
	                                			{
	                                				foreach ($display_arr as $kk => $vv)
	                                				{
	                                					$one_left_field[$kk] = $vv;
	                                				}
	                                			}
	                                			//处理颜色
	                                			if(array_key_exists('textColor', $one_left_field))
	                                			{
	                                				 
	                                				$color_arr = $this->processExtendColor($one_left_field['textColor']);
	                                				unset($one_left_field['textColor']);
	                                				foreach ($color_arr as $ck => $cv)
	                                				{
	                                					$one_left_field['textColor'][$ck] = $cv;
	                                				}
	                                				 
	                                			}
	                                			//处理indexContent
	                                			if($one_left_field['indexContent'] == '空格')
	                                			{
	                                				$one_left_field['indexContent'] = ' ';
	                                			}
	                                			if($left_all_fields[0]['style_type'] == 2 || $left_all_fields[0]['style_type'] == 4)
	                                			{
	                                				$one_left_field['indexContent'] = '';
	                                			}
	                                			
	                                			if($one_left_field['marginLeft'] !== '')
	                                			{
	                                				$left_fields[] = $one_left_field;
	                                			}
	                                		}
	                                	}                         			
	                                	$one_line['left'] = $left_fields;
	                                			
	                                	//取右
	                                	$right_all_fields = $this->new_extend->getInfos('comp_extend_field',array(
	                                			'comp_id'	=> $_item,
	                                			'line_id'	=> $v['id'],
	                                			'field_position'	=> $this->settings['new_extend']['field_position']['right'],
	                                			'user_id'	=> $user_id,
	                                	));   
	                                	$right_fields = array();
	                                	if($right_all_fields)
	                                	{                              				
	                                		foreach ($right_all_fields as $r_k => $r_v)
	                                		{
	                                			$one_right_field = array();
	                                					
	                                			//对左边单元进行处理，取出属性
	                                			//取出对应单元的后台属性
	                                			$left_field_attr = $this->_comp_mode->getExtendFieldAttributeData($r_v['id']);
	                                			if($left_field_attr)
	                                			{
	                                				$left_field_config = $this->buildTreeDataConfig(0, $groupData, $left_field_attr);
	                                				$one_right_field = $left_field_config[0]['extendLine']['extendField'];
	                                			}
	                                			$one_right_field['type'] = $r_v['field_type'];
	                                			$one_right_field['style'] = $this->settings['new_extend']['price_style'];
	                                			//处理显示样式
	                                			$display_arr = $this->processFieldStyle($r_v['id'],$r_v['style_type'],'comp_extend_field');
	                                			if($display_arr && is_array($display_arr))
	                                			{
	                                				foreach ($display_arr as $kk => $vv)
	                                				{
	                                					$one_right_field[$kk] = $vv;
	                                				}
	                                			}
	                                			//处理颜色
	                                			if(array_key_exists('textColor', $one_right_field))
	                                			{
	                                				 
	                                				$color_arr = $this->processExtendColor($one_right_field['textColor']);
	                                				unset($one_right_field['textColor']);
	                                				foreach ($color_arr as $ck => $cv)
	                                				{
	                                					$one_right_field['textColor'][$ck] = $cv;
	                                				}
	                                				 
	                                			}
	                                			//处理indexContent
	                                			if($one_right_field['indexContent'] == '空格')
	                                			{
	                                				$one_right_field['indexContent'] = ' ';
	                                			}
	                                			if($l_v['style_type'] == 2 || $l_v['style_type'] == 4)
	                                			{
	                                				$one_right_field['indexContent'] = '';
	                                			}
	                                			
	                                			if($one_right_field['marginLeft'] !== '')
	                                			{
	                                				$right_fields[] = $one_right_field;
	                                			}
	                                		}
	                                	}
	                                	$one_line['right'] = $right_fields;                 		                           		
	                                	$up[] = $one_line;
	                                } 
                                }	
                                $extendLayout['up'] = $up;

                                //如果是固定高度 则添加down部分
                                if($extend_area_position == $this->settings['new_extend']['extend_area_position']['fixed'])
                                {
                                	$down_lines = $this->new_extend->getInfos('comp_extend_line',array(
                                			'comp_id' => $_item,
                                			'user_id'	=> $user_id,
                                			'line_position' => $this->settings['new_extend']['line_position']['down'],
                                	),'order by order_id asc');
                                	if($down_lines)
                                	{
                                		foreach ($down_lines as $__kk => $__vv)
                                		{
                                			$one_line = array();
                                			
                                			//获取这个行的后台属性
                                			$line_attr = $this->_comp_mode->getExtendLineAttributeData($__vv['id']);
                                			if($line_attr)
                                			{
                                				$lineAttrConfig = $this->buildTreeDataConfig(0, $groupData,$line_attr);
                                				if($lineAttrConfig && $lineAttrConfig[0]['extendLine'])
                                				{
                                					$one_line = $lineAttrConfig[0]['extendLine'];
                                				}
                                			}
                                			$one_line['lineNum'] = $__vv['line_num'];
                                			
                                			//单行模式
                                			//先取出左
                                			$left_all_fields = $this->new_extend->getInfos('comp_extend_field',array(
                                					'comp_id'	=> $_item,
                                					'line_id'	=> $__vv['id'],
                                					'field_position'	=> $this->settings['new_extend']['field_position']['left'],
                                					'user_id'	=> $user_id,
                                			),'order by order_id asc');
                                			$left_fields = array();
                                			if($left_all_fields)
                                			{
                                				//单行模式
                                				if($__vv['line_type'] == $this->settings['new_extend']['line_type']['one'])
                                				{
	                                				foreach ($left_all_fields as $l_k => $l_v)
		                                			{
		                                				$one_left_field = array();
		                                					 
		                                				//对左边单元进行处理，取出属性
		                                				//取出对应单元的后台属性
		                                				$left_field_attr = $this->_comp_mode->getExtendFieldAttributeData($l_v['id']);
		                                				if($left_field_attr)
		                                				{
		                                					$left_field_config = $this->buildTreeDataConfig(0, $groupData, $left_field_attr);
		                                					$one_left_field = $left_field_config[0]['extendLine']['extendField'];
		                                				}
		                                				$one_left_field['type'] = $l_v['field_type'];
		                                				$one_left_field['style'] = $this->settings['new_extend']['price_style'];
		                                				//处理显示样式
		                                				$display_arr = $this->processFieldStyle($l_v['id'],$l_v['style_type'],'comp_extend_field');
		                                				if($display_arr && is_array($display_arr))
		                                				{
		                                					foreach ($display_arr as $kk => $vv)
		                                					{
		                                						$one_left_field[$kk] = $vv;
		                                					}
		                                				}
		                                				//处理颜色
		                                				if(array_key_exists('textColor', $one_left_field))
		                                				{
		                                				
		                                					$color_arr = $this->processExtendColor($one_left_field['textColor']);
		                                					unset($one_left_field['textColor']);
		                                					foreach ($color_arr as $ck => $cv)
		                                					{
		                                						$one_left_field['textColor'][$ck] = $cv;
		                                					}
		                                				
		                                				}
		                                				//处理indexContent
		                                				if($one_left_field['indexContent'] == '空格')
		                                				{
		                                					$one_left_field['indexContent'] = ' ';
		                                				}
		                                				if($l_v['style_type'] == 2 || $l_v['style_type'] == 4)
		                                				{
		                                					$one_left_field['indexContent'] = '';
		                                				}
		                                				 
		                                				if($one_left_field['marginLeft'] !== '')
		                                				{
		                                					$left_fields[] = $one_left_field;
		                                				}
		                                			}
                                				}
                                				//多行模式
                                				elseif ($_vv['line_type'] == $this->settings['new_extend']['line_type']['much'])
                                				{
	                                				$one_left_field = array();      			
		                                			//对左边单元进行处理，取出属性
		                                			//取出对应单元的后台属性
		                                			$left_field_attr = $this->_comp_mode->getExtendFieldAttributeData($left_all_fields[0]['id']);
		                                			if($left_field_attr)
		                                			{
		                                				$left_field_config = $this->buildTreeDataConfig(0, $groupData, $left_field_attr);
		                                				$one_left_field = $left_field_config[0]['extendLine']['extendField'];
		                                			}
		                                			$one_left_field['type'] = $left_all_fields[0]['field_type'];
		                                			$one_left_field['style'] = $this->settings['new_extend']['price_style'];
		                                			//处理显示样式
		                                			$display_arr = $this->processFieldStyle($left_all_fields[0]['id'],$left_all_fields[0]['style_type'],'comp_extend_field');
		                                			if($display_arr && is_array($display_arr))
		                                			{
		                                				foreach ($display_arr as $kk => $vv)
		                                				{
		                                					$one_left_field[$kk] = $vv;
		                                				}
		                                			}
		                                			//处理颜色
		                                			if(array_key_exists('textColor', $one_left_field))
		                                			{
		                                				 
		                                				$color_arr = $this->processExtendColor($one_left_field['textColor']);
		                                				unset($one_left_field['textColor']);
		                                				foreach ($color_arr as $ck => $cv)
		                                				{
		                                					$one_left_field['textColor'][$ck] = $cv;
		                                				}
		                                				 
		                                			}
		                                			//处理indexContent
		                                			if($one_left_field['indexContent'] == '空格')
		                                			{
		                                				$one_left_field['indexContent'] = ' ';
		                                			}
		                                			if($left_all_fields[0]['style_type'] == 2 || $left_all_fields[0]['style_type'] == 4)
		                                			{
		                                				$one_left_field['indexContent'] = '';
		                                			}
		                                			
		                                			if($one_left_field['marginLeft'] !== '')
		                                			{
		                                				$left_fields[] = $one_left_field;
		                                			}
                                				}
                                			}
                                			$one_line['left'] = $left_fields;
                                			
                                			//取右
                                			$right_all_fields = $this->new_extend->getInfos('comp_extend_field',array(
                                					'comp_id'	=> $_item,
                                					'line_id'	=> $__vv['id'],
                                					'field_position'	=> $this->settings['new_extend']['field_position']['right'],
                                					'user_id'	=> $user_id,
                                			));
                                			$right_fields = array();
                                			if($right_all_fields)
                                			{
	                                			foreach ($right_all_fields as $r_k => $r_v)
		                                		{
		                                			$one_right_field = array();
		                                					
		                                			//对左边单元进行处理，取出属性
		                                			//取出对应单元的后台属性
		                                			$left_field_attr = $this->_comp_mode->getExtendFieldAttributeData($r_v['id']);
		                                			if($left_field_attr)
		                                			{
		                                				$left_field_config = $this->buildTreeDataConfig(0, $groupData, $left_field_attr);
		                                				$one_right_field = $left_field_config[0]['extendLine']['extendField'];
		                                			}
		                                			$one_right_field['type'] = $r_v['field_type'];
		                                			$one_right_field['style'] = $this->settings['new_extend']['price_style'];
		                                			//处理显示样式
		                                			$display_arr = $this->processFieldStyle($r_v['id'],$r_v['style_type'],'comp_extend_field');
		                                			if($display_arr && is_array($display_arr))
		                                			{
		                                				foreach ($display_arr as $kk => $vv)
		                                				{
		                                					$one_right_field[$kk] = $vv;
		                                				}
		                                			}
		                                			//处理颜色
		                                			if(array_key_exists('textColor', $one_right_field))
		                                			{
		                                				 
		                                				$color_arr = $this->processExtendColor($one_right_field['textColor']);
		                                				unset($one_right_field['textColor']);
		                                				foreach ($color_arr as $ck => $cv)
		                                				{
		                                					$one_right_field['textColor'][$ck] = $cv;
		                                				}
		                                				 
		                                			}
		                                			//处理indexContent
		                                			if($one_right_field['indexContent'] == '空格')
		                                			{
		                                				$one_right_field['indexContent'] = ' ';
		                                			}
		                                			if($l_v['style_type'] == 2 || $l_v['style_type'] == 4)
		                                			{
		                                				$one_right_field['indexContent'] = '';
		                                			}
		                                			
		                                			if($one_right_field['marginLeft'] !== '')
		                                			{
		                                				$right_fields[] = $one_right_field;
		                                			}
		                                		}
                                			}
                                			$one_line['right'] = $right_fields;
                                			
                                			$down[] = $one_line;
                                		}
                                	}   	
                                }
                                $extendLayout['down'] = $down;
                                
                                
                                
                                $_vv['extendLayout'] = $extendLayout;
                                /*********组件新扩展字段配置end****************/
                                
                                //新角标配置
                               	$_comp_id = $_vv['id'];
                               	$corners = array();
                                //先看此组件有没有加入新角标
                               	$compCornerInfo = $this->_super_comp->getUseCornerInfoByCompId($_comp_id);                            	
	                            if($compCornerInfo && is_array($compCornerInfo) && !array_key_exists('ErrorCode', $compCornerInfo))
	                            {
	                            	//有角标 获取该组件的角标show_type
	                            	$one_comp = $this->_comp_mode->detail($_comp_id);
	                            	$corners['showType'] = $one_comp['corner_show_type'] ;                           	
	                            	foreach($compCornerInfo AS $_key => $_value)
	                            	{
	                            		//获取该用户这次使用的角标对应的属性
	                            		$_corner_attr = $this->ui_value_mode->getCornerAttributeData($_value['mod_corner_id'],COMPONENTS_USE_SUPERSCRIPT);
	                            		if($_corner_attr)
	                            		{
	                            			//对数据整一下型
	                            			$cornerAttrConfig = $this->buildTreeDataConfig(0, $groupData,$_corner_attr);
	                            			if($cornerAttrConfig && isset($cornerAttrConfig[0]['corner']))
	                            			{
	                            				$_itemCornerAttr = $cornerAttrConfig[0]['corner'];
	                            				$_itemCornerAttr['key']  = $_value['field_type'];
	                            				$_itemCornerAttr['text'] = $_value['text'];
// 	                            				$_itemCornerAttr['mod_corner_id'] = $_value['mod_corner_id'];
	                            				//增加角标的id
	                            				$_itemCornerAttr['superscript_id'] = $_value['id'];
	                            				
	                            				//系统图标
	                            				if(intval($_value['img_type']) == 1)
	                            				{
	                            					$_icon_arr = explode('.', $_value['img_info']);
	                            					$_itemCornerAttr['icon'] = 'dd_' . $_icon_arr[0];
	                            					$_itemCornerAttr['url'] = $this->settings['base_url'] . $this->settings['superscript']['save_path'] . $_value['img_info'];
	                            				}
	                            				elseif(intval($_value['img_type']) == 2)//用户自定义上传的
	                            				{
	                            					if($_value['img_info'] && is_array($_value['img_info']))
	                            					{
	                            						$_icon_arr = explode('.', $_value['img_info']['filename']);
	                            						$_itemCornerAttr['icon'] = 'dd_' . $_icon_arr[0];
	                            						//此处记录url，以便后期提交打包服务器是把图片下载到工程里面
	                            						$_itemCornerAttr['url'] = $_value['img_info']['host'] . $_value['img_info']['filepath'] . $_value['img_info']['filename'];
	                            					}
	                            					else
	                            					{
	                            						$_itemCornerAttr['icon'] = '';
	                            						$_itemCornerAttr['url'] = '';
	                            					}
	                            				}
	                            				else
	                            				{
	                            					$_itemCornerAttr['icon'] = '';
	                            					$_itemCornerAttr['url'] = '';
	                            				}
	                            				//计算图片宽高比
	                            				$_itemCornerAttr['hwScale'] = Common::getImageAspect($_itemCornerAttr['url']);
	                            				$corners['corner' . ($_key + 1)] = $_itemCornerAttr;
	                            			}
	                            		}
	                            	}
	                            }
	                            if($corners && $compCornerInfo)
	                            {
	                            	$_vv['corners'] = $corners;   
	                            }           
                                $_comp_ui[] = $_vv;
                            }
                        }
                    }
                }
            }
        }
        return $_comp_ui;
    }
    
    //按照分组构建树形数据结构共打包使用
    private function buildTreeDataConfig($fid,$groupData,$attrData)
    {
        $output = array();
        foreach($groupData AS $k => $v)
    	{
    	    $_data = array();
    		if($v['fid'] == $fid)
    		{
    		    $_data[$v['name']] = array();
    		    if(isset($attrData[$v['id']]) && $attrData[$v['id']])
    		    {
        		    foreach ($attrData[$v['id']] AS $_k => $_v)
        		    {
        		         $_data[$v['name']][$_v['uniqueid']] = $_v['attr_default_value'];
        		    }      		    
    		    }
    		    
    		    $childs = $this->buildTreeDataConfig($v['id'],$groupData,$attrData);
    		    if($childs)
    		    {
    		        foreach ($childs AS $kk => $vv)
    		        {
    		            foreach($vv AS $_kk => $_vv)
    		            {
    		                $_data[$v['name']][$_kk] = $_vv;
    		            }
    		        }
    		    }
    		    
    		    //做一下特殊处理，如果此节点没有子级内容就不输出此节点
    		    if(empty($_data[$v['name']]))
    		    {
    		        continue;
    		    }
    		    $output[] = $_data;
    		}
    	}
    	return $output;
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
        $clientType = $this->input['clientType'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        if(!$clientType)
        {
        	$this->errorOutput(NO_CLIENT_TYPE);
        }
        if($clientType == 'android')
        {
        	$clientId = 1;
        }
        elseif ($clientType == 'ios')
        {
        	$clientId = 2;	
        }

        $cond = " AND app_id = '" .$app_id. "' AND is_release = 1 AND client_type=".$clientId."";
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
            $systemIconUrl = '';//如果没有传系统图标就置空
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
    
    //获取扩展字段类型
    public function getExtendFieldType()
    {
        $this->addItem($this->settings['extend_field_type']);
        $this->output();
    }
    
    //根据模块获取某人设置的扩展字段的表现设置值
    public function getExtendFieldStyle()
    {
        $module_id = $this->input['module_id'];
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        
        $fields = $this->extend_mode->getExtendFieldByModuleId($module_id);
        if($fields)
        {
            $this->addItem($fields);
            $this->output();
        }
    }
    
    //删除某个模块的扩展字段样式设置的值
    public function deleteExtendFieldStyle()
    {
        $module_id = $this->input['module_id'];
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        
        $ret = $this->extend_mode->deleteExtendFieldStyle($module_id);
        if($ret)
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
    }
    
    //删除某个模块的扩展字段样式设置的值
    public function createExtendFieldStyle()
    {
        $module_id = $this->input['module_id'];
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        
        $data = array(
            'module_id' => $module_id,
            'position'  => intval($this->input['position']),
            'field_type'=> $this->input['field_type'],
            'style_type'=> $this->input['style_type']?$this->input['style_type']:1,
            'text'		=> $this->input['text'],
            'icon'		=> $this->settings['extend_field_icon'][$this->input['field_type']],
            'is_price'  => $this->input['is_price']?1:0,
            'is_display'=> intval($this->input['is_display']),
        );
        
        $ret = $this->extend_mode->createExtendFieldStyle($data);
        if($ret)
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
    }
    
    //获取某个模块角标的相关信息
    public function getCornerData()
    {
        $module_id = $this->input['module_id'];
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        
        $corner = $this->extend_mode->getCornerData($module_id);
        if($corner)
        {
            $this->addItem($corner);
            $this->output();
        }
    }
    
    //获取角标相关配置
    public function getCornerConfig()
    {
         $config = array(
             'corner_pos'     => $this->settings['corner_pos'],
             'text_direction' => $this->settings['corner_text_direction'],
         );
         $this->addItem($config);
         $this->output();
    }
    
    //设置角标的样式
    public function setCornerStyle()
    {
        $module_id = $this->input['module_id'];
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        
        $data = array(
	        'module_id'      => $module_id,
	        'text_direction' => intval($this->input['text_direction']),
	        'position'	     => intval($this->input['position']),
	        'margin_left'    => $this->input['margin_left'],
	        'margin_right'   => $this->input['margin_right'],
	        'margin_top'     => $this->input['margin_top'],
	        'margin_bottom'  => $this->input['margin_bottom'],
	        'field_type'     => $this->input['field_type'],
	        'icon'           => $this->input['icon'],
            'is_visiable'	 => $this->input['is_visiable']?1:0,
	    );
    
	    $ret = $this->extend_mode->setCornerStyle($data);
	    if($ret)
	    {
	        $this->addItem($data);
            $this->output();
	    }
    }
    
    //选取某个MainUI
    public function selectMainUI()
    {
        $app_id  = $this->input['app_id'];
        $temp_id = $this->input['temp_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        
        if(!$temp_id)
        {
            $this->errorOutput(NO_MAIN_UI_ID);
        }
        
        //首先判断该应用的存在性
        $cond = " AND id = '" .$app_id. "' AND user_id = '" .$this->user['user_id']. "' AND del = 0 ";
        $app_info = $this->app_info_mode->detail('',$cond);
        if(!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }
        
        //更新(需要把额外的几个开启项关闭)
        $ret = $this->app_info_mode->update($app_id,array(
                'temp_id'                => $temp_id,
                /*
                'is_show_collect'        => 0,
		        'is_open_member'         => 0,
		        'is_open_navBar_weather' => 0,
		        'is_open_weather'        => 0,
		        'is_show_bg_manger'      => 0,
		        */
        ));
        
        if($ret)
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
    }
    
    //设置app额外参数
    public function setAppExtraConfig()
    {
        $app_id = intval($this->input['app_id']);
	    if(!$app_id)
	    {
	        $this->errorOutput(NO_APP_ID);
	    }
	    
	    $data = array(
	        'is_show_collect'         => intval($this->input['is_show_collect']),
    	    'is_open_member'          => intval($this->input['is_open_member']),
    	    'is_open_navBar_weather'  => intval($this->input['is_open_navBar_weather']),
    	    'is_open_weather'         => intval($this->input['is_open_weather']),
    	    'is_show_bg_manger'       => intval($this->input['is_show_bg_manger']),
	    	'is_show_publish'		  => intval($this->input['is_show_publish']),
	    );
 
	    $ret = $this->app_info_mode->update($app_id,$data);
        if($ret)
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
    }
    
    //创建新栏目与演示栏目关联关系
    public function createColumnRelate()
    {
        $column_ids = $this->input['column_ids'];
        if(!$column_ids || !is_array($column_ids))
        {
            $this->errorOutput(DATA_ERROR);
        }
        
        foreach($column_ids AS $k => $v)
        {
            $this->app_info_mode->createColumnRelate(array(
                    'user_id'			=> $this->user['user_id'],//记录用户id方便查询
                    'site_id'			=> $this->input['site_id'],//记录站点id方便查询
                    'default_column_id' => $k,//演示的栏目id
                    'column_id'         => $v,//新的栏目id
            ));
        }
        
        $this->addItem(array('return' => 1));
        $this->output();
    }
    
    //获取某个人有没有默认演示数据
    public function getIsHasDefaultData()
    {
        //如果根据栏目id来查
        if($this->input['column_id'])
        {
            $cond = " AND column_id IN (" .$this->input['column_id']. ") ";
        }
        else if($this->input['site_id'])
        {
            $cond = " AND site_id = '" .$this->input['site_id']. "' ";
        }
        
        if(!$cond)
        {
            $this->errorOutput(NO_COND);
        }

        $ret = $this->app_info_mode->getColumnRelateData($cond);
        if($ret)
        {
            $column_ids = array();
            foreach($ret AS $k => $v)
            {
                $column_ids[] = $v['default_column_id'];
            }
            $this->addItem(array('default_column_id' => implode(',',$column_ids)));
        }
        else 
        {
            $this->addItem(array('default_column_id' => 0));
        }

        $this->output();
    }
    
    //删除栏目与演示数据栏目关联表数据
    public function deleteDemoColumns()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $cond = " AND user_id = '" .$user_id. "' ";
        //此column_id是新产生的栏目id
        if($this->input['column_id'])
        {
            $cond .= " AND column_id IN (" .$this->input['column_id']. ") "; 
        }
        
        $ret = $this->app_info_mode->deleteDemoColumns($cond); 
        if($ret)
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
        else 
        {
            $this->errorOutput(DELETE_FAIL);
        }
    }
    
    //获取演示数据栏目关联数据
    public function getColumnRelateData()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $ret = $this->app_info_mode->getColumnRelateData(" AND user_id = '" .$user_id. "' ");
        if($ret)
        {
            $this->addItem($ret);
            $this->output();
        }
        else 
        {
            $this->errorOutput(NO_DATA);
        }
    }
    
    //获取某个应用当天已经打包的次数
    public function getVersionNumsByDay()
    {
        $app_id = $this->input['app_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        
        $today = strtotime(date('Y-m-d'));
        $tomorrow = $today + 24*3600;
        $cond = " AND app_id = '" .$app_id. "' AND client_type = 1 AND create_time > '" . $today . "' AND create_time < '" .$tomorrow. "' ";
        $Android = $this->version_mode->count($cond);
        $cond = " AND app_id = '" .$app_id. "' AND client_type = 2 AND create_time > '" . $today . "' AND create_time < '" .$tomorrow. "' ";
        $IOS = $this->version_mode->count($cond);
        $this->addItem_withkey('total', array(
        		'android' => intval($Android['total']),
        		'ios' => intval($IOS['total'])
        ));
        $this->output();
    }
    
    //获取应用的基本信息
    public function getAppBaseInfoById()
    {
        $app_id = $this->input['app_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        $cond = " AND id = '" . $app_id . "' AND del = 0 ";
        $app_info = $this->app_info_mode->detail('',$cond);
        if(!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }
        $this->addItem($app_info);
        $this->output();
    }
    
    /**
     * 更新应用的iOS端的测试版与发布版的使用数量
     */
    public function updateIosCounts()
    {
    	$app_id = intval($this->input['app_id']);
    	$debug = intval($this->input['debug']);
    	
    	$cond = " AND id = '" . $app_id . "' AND del = 0 ";
    	$app_info = $this->app_info_mode->detail('',$cond);  	
    	$table = "app_info";
    	$data = array();
    	//$debug 0为发布版 1为测试版
    	if($debug == 0)
    	{
    		$data['iosReleaseCounts'] = (intval($app_info['iosReleaseCounts'])+1);
    	}
    	elseif($debug == 1)
    	{
    		$data['iosDebugCounts'] = (intval($app_info['iosDebugCounts'])+1);
    	}    	
    	$idsArr = array(
    		'id' => $app_id,
    	);
    	
    	$ret = $this->api->update($table, $data, $idsArr);
    	if($ret)
    	{
    		$this->addItem($ret);
    	}
    	$this->output();
    }
    
    //根据应用id检测该应用的创建者是否商业授权
    public function checkBusinessByAppId()
    {
        $app_id = $this->input['app_id'];
        if(!$app_id)
        {
            $this->errorOutput(NO_APP_ID);
        }
        $cond = " AND id = '" . $app_id . "' AND del = 0 ";
        $app_info = $this->app_info_mode->detail('',$cond);
        if(!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }
        
        //获取用户信息
        $user_info = $this->companyApi->getUserInfoByUserId($app_info['user_id']);
        if($user_info)
        {
            $this->addItem(array('is_business' => ($user_info['is_business'] && $user_info['is_remove_dd'])?1:0));
            $this->output();
        }
        else 
        {
            $this->errorOutput(THIS_USER_NOT_EXISTS);
        }
    }
    
    /**
     * 根据Guid
     * @return array $app_info:
     */
    public function getAPPInfoByGuid()
    {
    	$guid = trim($this->input['guid']);
    	if(!$guid)
    	{
    		$this->errorOutput(NO_GUID);
    	}
    	$queryData = array('guid' => $guid, 'del' => 0);
    	$app_info = $this->api->detail('app_info', $queryData);
    	if ($app_info)
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
     * 更新hogecloud相关信息
     */
    public function updateHogeCloudInfo()
    {
    	$hoge_cloud_access_token = trim($this->input['hoge_cloud_access_token']);
    	$app_id = intval($this->input['appId']);
    	$updateArray = array(
    		'hoge_cloud_access_token' => $hoge_cloud_access_token,
    	);
    	$idsArr = array(
    		'id' => $app_id,
    	);
    	$ret = $this->api->update('app_info',$updateArray, $idsArr);
    	if($ret)
    	{
    		$this->addItem('success');
    	}	
    	$this->output();
    }
    
    /**
     * 获取所有应用的所有类型的打包次数
     */
    public function getALLTypePackageNums()
    {
    	$app_id = intval($this->input['app_id']);   	
    	$info = $this->version_mode->getPackageNums($app_id);
    	if($info)
    	{
    		$this->addItem($info);
    	}
    	$this->output();
    }
    
    /**
     * 处理新扩展字段显示样式
     * @param number $field_style
     */
    private function processFieldStyle($field_id,$field_style = 0,$table = 'new_extend_field')
    {
    	//根据field获取对应的key，text
    	$user_id = $this->user['user_id'];
    	$field_info = $this->new_extend->detail($table, array(
    			'id'	=> $field_id,
    			'user_id'	=> $user_id,
    	));
    	$key = $field_info['uniqueid'];
    	$text = $field_info['uni_name'];
    	$icon_arr = $this->settings['new_extend']['icons'];
    	if(array_key_exists($key, $icon_arr))
    	{
    		$icon = $icon_arr[$key];
    	}
    	else
    	{
    		$icon = "";
    	}
    	switch ($field_style)
    	{
    		case 1://图标+名称+数值
    			$ret = array(
    				'key' 	=> $key,
    				'text'	=> $text,
    				'icon'	=> $icon, 				
    			);
    			break;   			
    		case 2://图标+数值
    			$ret = array(
	    			'key' 	=> $key,
	    			'text'	=> '',
	    			'icon'	=> $icon,
    			);
    			break;
    		case 3://名称+数值
    			$ret = array(
	    			'key' 	=> $key,
	    			'text'	=> $text,
	    			'icon'	=> '',
    			);
    			break;
    		case 4://数值
    			$ret = array(
    				'key' 	=> $key,
    				'text'	=> '',
    				'icon'	=> '',
    			);
    			break;
    		default:
    			$ret = array(
    					'key' 	=> $key,
    					'text'	=> $text,
    					'icon'	=> $icon,
    			);
    	}
    	
    	return $ret;
    }
    
    /**
     * 处理颜色
     */
    private function processExtendColor($value = '')
    {   	
    	$ret = array(
    		'aColor'	=> "#ff".substr($value, 1),
    		'color'		=> $value,
    		'alpha'		=> $this->settings['new_extend']['field_text_color']['alpha'],	
    	);
    	return $ret;
    }
    
    /**
     * 获取扩展字段相关数量限制
     */
    public function getCatalogNumLimit()
    {
    	$user_id = intval($this->input['user_id']);
    	$app_info = $this->api->getAppInfoByUserId($user_id);
    	$catalog_num = $this->api->detail('app_catalog_num', array(
    		'app_id'	=> $app_info['id'],
    		'user_id'	=> $user_id,
    	));
    	$this->addItem($catalog_num);	
    	$this->output();
    }
}

$out = new apps();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();