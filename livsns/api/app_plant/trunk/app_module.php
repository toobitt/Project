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
 * @description 模块接口
 **************************************************************************/
define('MOD_UNIQUEID', 'app_plant');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/appModule.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/company.class.php');
require_once(ROOT_PATH . 'lib/class/seekhelp.class.php');
require_once(CUR_CONF_PATH . 'lib/company.class.php');
require_once(CUR_CONF_PATH . 'lib/UpYunOp.class.php');
require_once(CUR_CONF_PATH . 'lib/appMaterial.class.php');
require_once(CUR_CONF_PATH . 'lib/solidify.class.php');
require_once(ROOT_PATH     . 'lib/class/im.class.php');
require_once(CUR_CONF_PATH . 'lib/body_tpl_mode.php');
require_once(CUR_CONF_PATH . 'lib/seekhelp.class.php');
require_once(CUR_CONF_PATH . 'lib/components_mode.php');
require_once(CUR_CONF_PATH . 'lib/user_interface_mode.php');

class app_module extends appCommonFrm
{
    private $api;
    private $material;
    private $company;
    private $_upYunOp;
    private $companyApi;
    private $app_material;
    private $soli_mode;
    private $_im;
    private $body_tpl_mode;
    private $seekhelpApi;
    private $comp_mode;
    private $ui_mode;
    public function __construct()
    {
        parent::__construct();
        $this->api = new appModule();
        $this->material = new material();
        $this->company = new company();
        $this->_upYunOp = new UpYunOp();
        $this->companyApi = new CompanyApi();
        $this->app_material = new appMaterial();
        $this->soli_mode = new solidify();
        $this->_im = new im();
        $this->body_tpl_mode = new body_tpl_mode();
        $this->seekhelpApi = new SeekHelpApi();
        $this->seekhelp = new seekhelp();
        $this->comp_mode = new components_mode();
        $this->ui_mode = new user_interface_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
        unset($this->api);
        unset($this->material);
        unset($this->company);
        unset($this->_upYunOp);
    }

    /**
     * 获取站点
     *
     * @access private
     * @param  登陆状态，内部调用
     * @return array | BOOL
     */
    private function getSite()
    {
        if ($this->user['user_id'] <= 0)
        {
            return false;
        }

        $userInfo = $this->company->getSiteByUser($this->user['user_id']);
        if (!$userInfo)
        {
            return false;
        }
        return $userInfo;
    }

    /**
     * 获取模块的列表
     *
     * @access public
     * @param  offset | count
     * @return array
     */
    public function show()
    {
        $offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
        $count  = isset($this->input['count']) ? intval($this->input['count']) : 40;
        $flag = isset($this->input['flag']) ? !!$this->input['flag'] : TRUE;
        $data   = array(
			'offset'    => $offset,
			'count'     => $count,
			'condition' => $this->condition()
        );
        $appModule_info = $this->api->show($data);
        $this->setXmlNode('appModule_info', 'module');
        if ($appModule_info)
        {
            foreach ($appModule_info as $module)
            {
                $this->addItem($module);
            }
        }
        $this->output();
    }

    /**
     * 根据条件获取模块的个数
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
     * 获取某一个模块详情
     *
     * @access public
     * @param  id:模块的id
     * @return array
     */
    public function detail()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(NOID);
        }
        $data = array('id' => $id);
        $appModule_info = $this->api->detail('app_module', $data);
        if ($appModule_info)
        {
            //获取对应的应用信息
            $app_info = $this->api->detail('app_info', array('id' => $appModule_info['app_id']),'name');

            if (unserialize($appModule_info['pic']))
            {
                $appModule_info['pic'] = unserialize($appModule_info['pic']);
            }
            	
            if (unserialize($appModule_info['press_pic']))
            {
                $appModule_info['press_pic'] = unserialize($appModule_info['press_pic']);
            }
            if ($appModule_info['bind_id'] && unserialize($appModule_info['bind_params']))
            {
                $appModule_info['bind_params'] = unserialize($appModule_info['bind_params']);
            }
            
            /*************************************************组件部分********************************************************/
            //获取该模块已经选取的组件
            $compIdsArr = array();
            $compIds = $this->comp_mode->getCompIdsByCond(" AND module_id = '" . $id . "' ORDER BY order_id ASC ",'comp_id');
            if($compIds)
            {
                $appModule_info['comp_id_arr'] = $compIdsArr = array_keys($compIds);
            }
            else 
            {
                $appModule_info['comp_id_arr'] = array();
            }
            
            //获取某人的针对此模块的可用的组件
            $_cond = " AND user_id = '" .$this->user['user_id']. "' AND is_open = 1 ";
            $_orderby = ' ORDER BY order_id DESC ';
            $userComps = $this->comp_mode->show($_cond,$_orderby,'','id');
            if($userComps)
            {
                $userCompIds = array_diff(array_keys($userComps),$compIdsArr);
                //然后对已经选取的取差集
                if($userCompIds)
                {
                    $appModule_info['user_comp_arr'] = array_values($userCompIds);
                }
                else 
                {
                    $appModule_info['user_comp_arr'] = array();
                }
            }
            else
            {
                $appModule_info['user_comp_arr'] = array();
            }
            /*************************************************组件部分********************************************************/
          
            //获取正文的模板信息
            if ($appModule_info['body_tpl_id'])
            {
                $queryData = array(
			        'id' => $appModule_info['body_tpl_id']
                );
                $tpl_info = $this->api->detail('body_tpl', $queryData);
                if ($tpl_info)
                {
                    $tpl_info['html_str'] = html_entity_decode($tpl_info['body_html']);
                    if ($tpl_info['img_info'] && unserialize($tpl_info['img_info']))
                    {
                        $tpl_info['img_info'] = unserialize($tpl_info['img_info']);
                    }
                    $appModule_info['body_tpl'] = $tpl_info;
                }
            }
            //获取第三方接口的信息
            if ($appModule_info['bind_id'])
            {
                $queryData = array(
                    'id' => $appModule_info['bind_id']
                );
                $bind_info = $this->api->detail('data_bind', $queryData);
                if ($bind_info)
                {
                    $appModule_info['bind_mark'] = $bind_info['mark'];
                }
            }
            
            //就取默认值
            if(!$appModule_info['text_nor_bg'])
            {
                //$appModule_info['text_nor_bg'] = $this->settings['module_default']['text_nor_bg'];
            }
            
            if(!$appModule_info['text_pre_bg'])
            {
                //$appModule_info['text_pre_bg'] = $this->settings['module_default']['text_pre_bg'];
            }
            
            if(!$appModule_info['layout_nor_bg'])
            {
                //$appModule_info['layout_nor_bg'] = $this->settings['module_default']['layout_nor_bg'];
            }
            
            if(!$appModule_info['layout_pre_bg'])
            {
                //$appModule_info['layout_pre_bg'] = $this->settings['module_default']['layout_pre_bg'];
            }
            
            if(!$appModule_info['layout_nor_alpha'] && $appModule_info['layout_nor_alpha'] !== 0.00)
            {
                //$appModule_info['layout_nor_alpha'] = $this->settings['module_default']['layout_nor_alpha'];
            }
            
            if(!$appModule_info['layout_pre_alpha'] && $appModule_info['layout_pre_alpha'] !== 0.00)
            {
                //$appModule_info['layout_pre_alpha'] = $this->settings['module_default']['layout_pre_alpha'];
            }
            
            if(!$appModule_info['main_color'])
            {
                //$appModule_info['main_color'] = $this->settings['module_default']['main_color'];
            }

            //导航栏配置
            if($appModule_info['navbar'] && unserialize($appModule_info['navbar']))
            {
                $appModule_info['navbar'] = unserialize($appModule_info['navbar']);
                //处理导航栏背景
                if(isset($appModule_info['navbar']['bg']) && isset($appModule_info['navbar']['bg']['img']))
                {
                    $_img_info = $this->app_material->detail('app_material', array('id' => $appModule_info['navbar']['bg']['img']));
                    $appModule_info['navbar']['bg']['img'] = $_img_info;
                }
                
                //处理导航栏标题
                if(isset($appModule_info['navbar']['titleContent']) && isset($appModule_info['navbar']['titleContent']['img']))
                {
                    $_img_info = $this->app_material->detail('app_material', array('id' => $appModule_info['navbar']['titleContent']['img']));
                    $appModule_info['navbar']['titleContent']['img'] = $_img_info;
                }
                
                if(!isset($appModule_info['navbar']['bg']) || !is_array($appModule_info['navbar']['bg']))
                {
                    /*
                    $appModule_info['navbar']['bg'] = array(
                                'color' => $this->settings['module_default']['navbar']['bg'],
                                'alpha' => 1,
                    );
                    */
                }
                
                if(!isset($appModule_info['navbar']['titleContent']) || !is_array($appModule_info['navbar']['titleContent']))
                {
                    /*
                    $appModule_info['navbar']['titleContent'] = array(
                                'text' => $app_info['name'],
                    );
                    */
                }
            }
            else 
            {
                /*
                $appModule_info['navbar'] = array(
                       'bg'           => array('color' => $this->settings['module_default']['navbar']['bg'],'alpha' => 1),
                       'titleContent' => array('text'  => $app_info['name']),
                       'isBlur'		  => $this->settings['module_default']['navbar']['isBlur'],
                );
                */
            }
            
            //处理页面背景
            if($appModule_info['ui_bg'])
            {
                $_uiBg = explode('|', $appModule_info['ui_bg']);
                if(isset($_uiBg[0]) && $_uiBg[0])
                {
                    if($_uiBg[0] == 'img')
                    {
                        $_img_info = $this->app_material->detail('app_material', array('id' => $_uiBg[1]));
                        $appModule_info['ui_bg'] = array('img' => $_img_info,'is_tile' => $_uiBg[2]);
                    }
                    elseif ($_uiBg[0] == 'color')
                    {
                        $appModule_info['ui_bg'] = array('color' => $_uiBg[1],'alpha' => $_uiBg[2]);
                    }
                }
            }
            else 
            {
                //$appModule_info['ui_bg'] = array('color' => $this->settings['module_default']['ui_bg'],'alpha' => 1);
            }
        }
        $this->addItem($appModule_info);
        $this->output();
    }

    /**
     * 验证app模块创建的个数是否达到上限(内部调用)
     *
     * @access private
     * @param  $app_id:应用的id
     *
     * @return array
     */
    private function limitNum($app_id, $limitNum = MODULE_LIMIT_NUM)
    {
        $queryData = array(
	        'app_id' => $app_id
        );
        $nums = $this->api->count($queryData);
        return $nums['total'] >= $limitNum ? true : false;
    }

    /**
     * 创建数据
     *
     * @access public
     * @param  $app_id:应用的id
     *
     * @return array
     */
    public function create()
    {
        if(!$this->user['user_id'])
        {
            $this->errorOutput(NO_LOGIN);
        }

        $data = $this->filter_data();
        //验证是否为VIP用户
        /*
        if ($this->settings['vip_user'])
        {
            $is_vip = in_array($this->user['user_name'], $this->settings['vip_user']) ? true : false;
        }
        if ($is_vip && $this->limitNum($data['app_id'], 15))
        {
            $this->errorOutput(OVER_LIMIT);
        }
        */
        
        //获取当前用户的角色
        $user_info = $this->companyApi->getUserInfoByUserId($this->user['user_id']);
        if(!$user_info)
        {
            $this->errorOutput(USER_NOT_EXISTS);
        }

        //判断用户是否是VIP用户
        $is_vip = $user_info['is_vip']? TRUE : FALSE;
        
        if($is_vip && $this->limitNum($data['app_id'], 15))
        {
             $this->errorOutput(OVER_LIMIT);
        }

        //验证创建的个数是否达到上限
//         if (!$is_vip && $this->limitNum($data['app_id']))
//         {
//             $this->errorOutput(OVER_LIMIT);
//         }
        //验证APP是否存在
        $queryData = array(
		    'id' => $data['app_id'],
		    'user_id' => $this->user['user_id'],
		    'del' => 0
        );
        $app_info = $this->api->detail('app_info', $queryData);
        
        
        if (!$app_info)
        {
            $this->errorOutput(NO_APPID);
        }
        //验证创建的个数是否达到上限
       	$max_module_num = $app_info['max_module_num'];
       	//获取当前模块数目
       	$module_data_condition = array(
       		'app_id' => $app_info['id'],
       	);
       	$now_modules = $this->api->count($module_data_condition);
        if($now_modules['total'] >= $max_module_num)
        {
        	$this->errorOutput(OVER_LIMIT);
        }
        
        /*
         //是否重名
         $check = $this->api->verify(array('name' => $data['name'], 'app_id' => $data['app_id']));
         if ($check > 0) $this->errorOutput(NAME_EXISTS);
         if ($data['english_name'])
         {
         $check = $this->api->verify(array('english_name' => $data['english_name'], 'app_id' => $data['app_id']));
         if ($check > 0) $this->errorOutput(ENGLISH_EXISTS);
         }
         */
        //以模块名称创建站点栏目
        $userInfo = $this->getSite();
        if (!$userInfo)
        {
            $this->errorOutput(NO_USER_ID);
        }

        $publish = new publishconfig();
        $column_data = array(
		    'fast_add_column' => 1,
		    'column_name'     => $data['name'],
			'site_id'         => $userInfo['s_id']
        );
        $column_id = $publish->edit_column($column_data);
        if (!$column_id)
        {
            $this->errorOutput(FAILED);
        }

        if ($this->input['pic_id'])
        {
            $pic_id = intval($this->input['pic_id']);
            //$pic_info = $this->material->get_material_by_ids($pic_id);
            $pic_info = $this->_upYunOp->getPicInfoById(''," AND id IN (" .$pic_id. ") AND user_id = '" .$this->user['user_id']. "' ");
            if (!$pic_info || !isset($pic_info[0]) || !$pic_info[0])
            {
                $pic_info = $this->material->get_material_by_ids($pic_id);
                if (!$pic_info || !isset($pic_info[0]) || !$pic_info[0])
                {
                    $this->errorOutput(PIC_NOT_EXISTS);
                }
            }

            $data['pic'] = serialize($pic_info[0]);
            if ($this->input['press_id'])
            {
                $press_id = intval($this->input['press_id']);
                //$press_pic = $this->material->get_material_by_ids($press_id);
                $press_pic = $this->_upYunOp->getPicInfoById(''," AND id IN (" .$press_id. ") AND user_id = '" .$this->user['user_id']. "' ");
                if (!$press_pic || !isset($press_pic[0]) || !$press_pic[0])
                {
                    $press_pic = $this->material->get_material_by_ids($press_id);
                    if(!$press_pic || !isset($press_pic[0]) || !$press_pic[0])
                    {
                        $this->errorOutput(PIC_NOT_EXISTS);
                    }
                }
                $data['press_pic'] = serialize($press_pic[0]);
            }
        }
        elseif ($this->input['pic_url'])
        {
            $data['pic']        = trim(urldecode($this->input['pic_url']));
            $data['press_pic']  = str_replace('/normal/', '/press/', $data['pic']);
        }
        $data['user_id'] = $this->user['user_id'];
        $data['user_name'] = $this->user['user_name'];
        $data['org_id'] = $this->user['org_id'];
        $data['create_time'] = TIMENOW;
        $data['ip'] = hg_getip();
        $data['column_id'] = $column_id;
        $data['default_column_id'] = $this->input['default_column_id']?intval($this->input['default_column_id']):0;//记录默认演示数据的栏目id
        $result = $this->api->create('app_module', $data);
        if ($result['id'])
        {
            $this->api->update('app_module', array('sort_order' => $result['id']), array('id' => $result['id']));
            $result['sort_order'] = $result['id'];
            if ($pic_info && $pic_info[0])
            {
                $result['pic'] = $pic_info[0];
            }
            $this->addItem($result);
        }
        $this->output();
    }

    /**
     * 更新模块
     *
     * @access public
     * @param  $app_id:应用的id
     *
     * @return array
     */
    public function update()
    {
        $id = intval($this->input['id']);
        if ($id <= 0)
        {
            $this->errorOutput(NOID);
        }
         
        $data = $this->filter_data();
        //验证APP是否存在
        $queryData = array(
		    'id'          => $data['app_id'],
		    'user_id'     => $this->user['user_id'],
		    'del'         => 0
        );
        $app_info = $this->api->detail('app_info', $queryData);
        if (!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }

        $appModule_info = $this->api->detail('app_module', array('id' => $id, 'app_id' => $data['app_id']));
        if (!$appModule_info)
        {
            $this->errorOutput(MODULE_NOT_EXISTS);
        }

        $validate = array();
        if (isset($data['name']) && $appModule_info['name'] != $data['name'])
        {
            /*
             //是否重名
             $check = $this->api->verify(array('name' => $data['name'], 'app_id' => $data['app_id']));
             if ($check > 0) $this->errorOutput(NAME_EXISTS);
             */
            $validate['name'] = $data['name'];
            //编辑栏目
            $userInfo = $this->getSite();
            if (!$userInfo)
            {
                $this->errorOutput(NO_USER_ID);
            }

            $publish = new publishconfig();
            $column_data = array(
    		    'fast_add_column' => 1,
    		    'column_name'     => $data['name'],
    			'site_id'         => $userInfo['s_id'],
    		    'column_id'       => $appModule_info['column_id']
            );
            $column_id = $publish->edit_column($column_data);
            if (!$column_id) $this->errorOutput(FAILED);
        }
        
        if (isset($data['status']))
        {
            $validate['status'] = $data['status'];
        }
        
        if (isset($data['english_name']) && $appModule_info['english_name'] != $data['english_name'])
        {
            /*
             //是否重名
             $check = $this->api->verify(array('english_name' => $data['english_name'], 'app_id' => $data['app_id']));
             if ($check > 0) $this->errorOutput(ENGLISH_EXISTS);
             */
            $validate['english_name'] = $data['english_name'];
        }

        if ($this->input['pic_url'])
        {
            $pic_url = trim(urldecode($this->input['pic_url']));
            if ($appModule_info['pic'] != $pic_url)
            {
                $validate['pic']       = $pic_url;
                $validate['press_pic'] = str_replace('/normal/', '/press/', $pic_url);
            }
        }

        if (isset($this->input['pic_id']))
        {
            $pic_id = intval($this->input['pic_id']);
            //$pic_info = $this->material->get_material_by_ids($pic_id);
            $pic_info = $this->_upYunOp->getPicInfoById(''," AND id IN (" .$pic_id. ") AND user_id = '" .$this->user['user_id']. "' ");
            if (!$pic_info || !isset($pic_info[0]) || !$pic_info[0])
            {
                $pic_info = $this->material->get_material_by_ids($pic_id);
                if(!$pic_info || !isset($pic_info[0]) || !$pic_info[0])
                {
                    $this->errorOutput(PIC_NOT_EXISTS);
                }
            }

            $pic_info = serialize($pic_info[0]);
            if ($appModule_info['pic'] != $pic_info)
            {
                $validate['pic'] = $pic_info;
            }
        }

        if (isset($this->input['press_id']))
        {
            $press_id = intval($this->input['press_id']);
            //$press_pic = $this->material->get_material_by_ids($press_id);
            $press_pic = $this->_upYunOp->getPicInfoById(''," AND id IN (" .$press_id. ") AND user_id = '" .$this->user['user_id']. "' ");
            if (!$press_pic || !isset($press_pic[0]) || !$press_pic[0])
            {
                $press_pic = $this->material->get_material_by_ids($press_id);
                if(!$press_pic || !isset($press_pic[0]) || !$press_pic[0])
                {
                    $this->errorOutput(PIC_NOT_EXISTS);
                }
            }

            $press_pic = serialize($press_pic[0]);
            if ($appModule_info['press_pic'] != $press_pic)
            {
                $validate['press_pic'] = $press_pic;
            }
        }

        if ($data['body_tpl_id'] && $appModule_info['body_tpl_id'] != $data['body_tpl_id'])
        {
            $validate['body_tpl_id'] = $data['body_tpl_id'];
        }

        if (isset($data['is_sub']) && $appModule_info['is_sub'] != $data['is_sub'])
        {
            $validate['is_sub'] = $data['is_sub'];
        }

        if (isset($data['normal_color']) && $appModule_info['normal_color'] != $data['normal_color'])
        {
            $validate['normal_color'] = $data['normal_color'];
        }

        if (isset($data['normal_alpha']) && $appModule_info['normal_alpha'] != $data['normal_alpha'])
        {
            $validate['normal_alpha'] = $data['normal_alpha'];
        }

        if (isset($data['press_color']) && $appModule_info['press_color'] != $data['press_color'])
        {
            $validate['press_color'] = $data['press_color'];
        }

        if (isset($data['press_alpha']) && $appModule_info['press_alpha'] != $data['press_alpha'])
        {
            $validate['press_alpha'] = $data['press_alpha'];
        }
        if ($data['webview_id'] > 0 || $data['webview_url'])
        {
            if ($data['webview_id'] > 0 && $appModule_info['web_view'] != $data['webview_id'])
            {
                $webview_info = $this->api->detail('app_webview', array('id' => $data['webview_id']));
                if (!$webview_info)
                {
                    $this->errorOutput(WEBVIEW_NOT_EXISTS);
                }

                $validate['web_view'] = $data['webview_id'];
                $validate['web_url'] = $webview_info['url'];
            }
            elseif ($data['webview_url'] && $appModule_info['web_url'] != $data['webview_url'])
            {
                //判断url
//                 if (!filter_var($data['webview_url'], FILTER_VALIDATE_URL))
//                 {
//                     $this->errorOutput(URL_NOT_VALID);
//                 }
                $validate['web_url'] = $data['webview_url'];
                $validate['web_view'] = -1;
            }
            $validate['ui_id'] = 0;
            $validate['solidify_id'] = 0;
        }
        elseif ($data['solidify_id'] > 0 && $appModule_info['solidify_id'] != $data['solidify_id'])
        {
            $_soli_info = $this->soli_mode->detail('solidify_module', array('id' => $data['solidify_id']));
            if(!$_soli_info)
            {
                $this->errorOutput(SOLID_NOT_EXISTS);
            }
            
            if($_soli_info['mark'] == 'im')
            {
				//判断是否在黑名单
				$blackInfo = $this->_im->check_black($data['app_id']);
				if($blackInfo['is_black'])
				{
					$this->errorOutput(IM_IS_BLACK);
				}
            	
                //获取融云的 production_app_key
				//如果固话模块是即时通信的，需要验证该应用有没有容云key
                $rcAppKey = $this->_im->get_rckey($data['app_id']);
                if(!$rcAppKey)
                {
                    
//                    $this->addItem(YOU_SHOULD_CREATE_TEAM);
//                    $this->output();
                    $this->errorOutput(YOU_SHOULD_CREATE_TEAM);
                }
            }
            
            //微社区
            if(strpos($_soli_info['mark'],'seekhelp') !== FALSE)
            {
            	//限制绑定一个微社区
                $cond = " AND app_id = '" .$data['app_id']. "' AND user_id = '" .$this->user['user_id']. "' ";
                $ModulesData = $this->api->getModulesData($cond);
                foreach ($ModulesData as $k=>$v)
                {
                    $solidify_id_arr[$v['id']] = $v['solidify_id'];   
                }
                foreach ($solidify_id_arr as $k=>$v)
                {
                    $old_soli_info = $this->soli_mode->detail('solidify_module', array('id' => $v));
                    if(strpos($old_soli_info['mark'],'seekhelp') !== FALSE && $k != $this->input['id'])
                    {
                        $this->errorOutput(HAS_SEEKHELP);
                    }
                }
                
            	//判断是否在黑名单
            	$blackInfo = $this->seekhelp->check_black($data['app_id']);
            	if($blackInfo['is_black'])
            	{
            		$this->errorOutput(SEEKHELP_IS_BLACK);
            	}
            	
                //判断老用户
                if(!$app_info['seekhelp_sort_id'])
                {
                    //判断新用户
                    $newSeekhelp= $this->seekhelpApi->community_operate(array(
                    		'a'      => 'getSortByappId',
                    		'app_id' => $app_info['id'],
                    ));
                    
                	if(!$newSeekhelp)
                	{
                		$this->errorOutput(YOU_SHOULD_CREATE_COMMUNITY_FIRST);
                	}
                } 
            }

            $validate['solidify_id'] = $data['solidify_id'];
            $validate['web_view'] = 0;
            $validate['web_url'] = '';
            $validate['ui_id'] = 0;
        }
        elseif ($data['ui_id'] > 0 && $appModule_info['ui_id'] != $data['ui_id'])
        {
            $validate['ui_id'] = $data['ui_id'];
            $validate['web_view'] = 0;
            $validate['web_url'] = '';
            $validate['solidify_id'] = 0;
        }
        
        //后加的一些属性
        $validate['text_nor_bg']       = $data['text_nor_bg'];
        $validate['text_pre_bg']       = $data['text_pre_bg'];
        $validate['layout_pre_bg']     = $data['layout_pre_bg'];
        $validate['layout_pre_alpha']  = $data['layout_pre_alpha'];
        $validate['layout_nor_bg']     = $data['layout_nor_bg'];
        $validate['layout_nor_alpha']  = $data['layout_nor_alpha'];
        $validate['ui_bg']             = $data['ui_bg'];
        $validate['main_color']        = $data['main_color'];
        
        if($data['navbar'] && is_array($data['navbar']))
        {
            $validate['navbar'] = addslashes(serialize($data['navbar']));//导航栏设置
        }
        $validate['ui_padding_bottom'] = $data['ui_padding_bottom'];
        
        if ($validate)
        {
            $result = $this->api->update('app_module', $validate, array('id' => $id));
        }
        else
        {
            $result = true;
        }

        $this->addItem($result);
        $this->output();
    }
    
    /**
     * 绑定第三方数据
     */
    public function bind()
    {
        $moduleId = intval($this->input['id']);
        $bindId = intval($this->input['bind_id']);
        $params = $this->input['params'];
        $moduleInfo = $this->api->detail('app_module', array('id' => $moduleId));
        if (!$moduleInfo)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        if ($moduleInfo['web_view'] != 0 || $moduleInfo['solidify_id'] > 0)
        {
            $this->errorOutput(BIND_CONTENT_INVALID);
        }
        $bindInfo = $this->api->detail('data_bind', array('id' => $bindId));
        if (!$bindInfo)
        {
            $this->errorOutput(NO_BIND_ID);
        }
        $data = array(
            'bind_id' => $bindId,
            'bind_status' => 1,
            'bind_params' => serialize($params)
        );
        $condition = array(
            'id' => $moduleId
        );
        $result = $this->api->update('app_module', $data, $condition);
        $this->addItem($result);
        $this->output();
    }
    
    /**
     * 设置绑定状态
     */
    public function setBindStatus()
    {
        $moduleId = intval($this->input['id']);
        $status = intval($this->input['status']);
        $moduleInfo = $this->api->detail('app_module', array('id' => $moduleId));
        if (!$moduleInfo)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        if ($moduleInfo['web_view'] != 0 || $moduleInfo['solidify_id'] > 0)
        {
            $this->errorOutput(BIND_CONTENT_INVALID);
        }
        $data = array(
            'bind_status' => $status,
        );
        $condition = array(
            'id' => $moduleId
        );
        $result = $this->api->update('app_module', $data, $condition);
        $this->addItem($result);
        $this->output();
    }
    
    /**
     * 解除绑定
     */
    public function removeBind()
    {
        $moduleId = intval($this->input['id']);
        $moduleInfo = $this->api->detail('app_module', array('id' => $moduleId));
        if (!$moduleInfo)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        $data = array(
            'bind_id'     => 0,
            'bind_params' => '',
            'bind_status' => 0,
        	'bind_type'   => '',
        	'data_url'    => '',
        	'bind_collect'=> '',
        );
        $condition = array(
            'id' => $moduleId
        );
        $result = $this->api->update('app_module', $data, $condition);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 删除模块
     *
     * @access public
     * @param  id:模块的id
     *
     * @return array
     */
    public function delete()
    {
        $id = trim(urldecode($this->input['id']));
        $id_arr = explode(',', $id);
        $id_arr = array_filter($id_arr, 'filter_arr');
        if (!$id_arr)
        {
            $this->errorOutput(NOID);
        }

        $ids = implode(',', $id_arr);
        //检验是否为自己的APP的模块
        $queryData = array(
		    'count'         => -1,
		    'condition'     => array(
                		        'id'  => $ids,
                		        'uid' => $this->user['user_id']
        )
        );
        $module_info = $this->api->show($queryData);
        if (!$module_info)
        {
            $this->errorOutput(MODULE_NOT_EXISTS);
        }

        $validate_ids = $column_ids = array();
        foreach ($module_info as $module)
        {
            $validate_ids[] = $module['id'];
            $column_ids[]   = $module['column_id'];
        }
        $validate_ids = implode(',', $validate_ids);
        //删除绑定的界面对应的属性
        $this->api->delete('ui_value', array('module_id' => $validate_ids));
        //删除栏目
        if ($column_ids)
        {
            $parent_ids  = $column_ids; //顶级栏目id
            $column_ids  = implode(',', $column_ids);
            $publish     = new publishconfig();
            $column_info = $publish->get_column_by_ids('*', $column_ids);
            if ($column_info)
            {
                $childs_id = '';
                foreach ($column_info as $v)
                {
                    $childs_id .= ','.$v['childs'];
                }
                //去除顶级栏目id
                $childs_id = trim($childs_id, ',');
                $childs_id = explode(',', $childs_id);
                $all_childs = array_diff($childs_id, $parent_ids);
                //删除子集栏目
                if ($all_childs)
                {
                    $all_childs = implode(',', $all_childs);
                    $result = $publish->delete_column($all_childs);
                    if ($result != 'success')
                    {
                        $this->errorOutput(FAILED);
                    }
                }
                //删除顶级栏目
                $result = $publish->delete_column(implode(',', $parent_ids));
                if ($result != 'success')
                {
                    $this->errorOutput(FAILED);
                }
            }
        }
        //删除模块数据
        $result = $this->api->delete('app_module', array('id' => $validate_ids));
        $this->addItem($result);
        $this->output();
    }

    /**
     * 排序操作
     *
     * @access public
     * @param  sort:排序的id array
     *
     * @return array
     */
    public function sort()
    {
        $sort = $this->input['sort'];
        if (!$sort || !is_array($sort))
        {
            $this->errorOutput(ORDER_ERROR);
        }
        
        if(!$this->user['user_id'])
        {
            $this->errorOutput(NO_LOGIN);
        }
         
        //排序栏目
        $module_info = $this->api->show(array('count' => -1,'condition' => array('uid' => $this->user['user_id'])));
        if (!$module_info)
        {
            $this->errorOutput(MODULE_NOT_EXISTS);
        }
            
        $column_sort = array();
        foreach ($module_info as $v)
        {
            if ($sort[$v['id']])
            {
                $column_sort[$v['column_id']] = $sort[$v['id']];
            }
        }
        $publish = new publishconfig();
        $ret = $publish->column_sort(json_encode($column_sort));
        if ($ret != 'success')
        {
            $this->errorOutput(COLUMN_SORT_WRONG);
        }

        $condition = array('user_id' => $this->user['user_id']);
        foreach ($sort as $k => $v)
        {
            $condition['id']     = intval($k);
            $updateData          = array('sort_order' => intval($v));
            $result              = $this->api->update('app_module', $updateData, $condition);
        }
        $this->addItem($result);
        $this->output();
    }

    /**
     * 根据模块获取顶级栏目数据
     *
     * @access public
     * @param  app_id:应用id
     *
     * @return array
     */
    public function getColumnsByModule()
    {
        $app_id      = intval($this->input['app_id']);
        $column_info = $this->api->getColumnsByModule($app_id);
        if ($column_info)
        {
            foreach ($column_info as $column)
            {
                $this->addItem($column);
            }
        }
        $this->output();
    }

    /**
     * 根据顶级栏目获取对应模块数据
     *
     * @access public
     * @param  id:栏目id
     *
     * @return array
     */
    public function getModuleByColumnId()
    {
        $column_id = intval($this->input['id']);
        $queryData = array(
	        'column_id' => $column_id
        );
        $module_info = $this->api->detail('app_module', $queryData);
        $this->addItem($module_info);
        $this->output();
    }

    /**
     * 过滤数据
     *
     * @access private
     * @param  $this->input
     *
     * @return array
     */
    private function filter_data()
    {
        $module_name        = trim(urldecode($this->input['moduleName']));
        $english_name       = trim(urldecode($this->input['englishModuleName']));
        $app_id             = intval($this->input['appId']);
        $ui_id              = intval($this->input['uiId']);
        $subscribe          = isset($this->input['is_sub']) ? intval($this->input['is_sub']) : 1;
        $webview_id         = intval($this->input['webviewId']);
        $webview_url        = trim(urldecode($this->input['webviewUrl']));
        $body_tpl_id        = intval($this->input['body_tpl_id']);
        $solidify_id        = intval($this->input['solidifyId']);
        $status             = intval($this->input['status']);
        //新增的一些属性
        $text_nor_bg       = $this->input['text_nor_bg'];
        $text_pre_bg       = $this->input['text_pre_bg'];
        $layout_pre_bg     = $this->input['layout_pre_bg'];
        $layout_pre_alpha  = $this->input['layout_pre_alpha'];
        $layout_nor_bg     = $this->input['layout_nor_bg'];
        $layout_nor_alpha  = $this->input['layout_nor_alpha'];
        $ui_bg             = $this->input['ui_bg'];
        $navbar            = $this->input['navbar'];
        $ui_padding_bottom = $this->input['ui_padding_bottom'];
        $main_color        = $this->input['main_color'];
        
        if ($app_id <= 0)
        {
            $this->errorOutput(NO_APP_ID);
        }

        if (!$module_name)
        {
            $this->errorOutput(NO_MODULE_ID);
        }

        $data = array(
			'app_id'           => $app_id,
			'name'             => $module_name,
			'english_name'     => $english_name,
		    'is_sub'           => $subscribe,
            'status'           => $status,
            'text_nor_bg'      => $text_nor_bg,
            'text_pre_bg'      => $text_pre_bg,
            'layout_pre_bg'    => $layout_pre_bg,
            'layout_pre_alpha' => $layout_pre_alpha,
            'layout_nor_bg'    => $layout_nor_bg,
            'layout_nor_alpha' => $layout_nor_alpha,
            'ui_bg'            => $ui_bg,
            'navbar'           => $navbar,
            'ui_padding_bottom'=> $ui_padding_bottom,
            'main_color'	   => $main_color,
        );

        if ($body_tpl_id)
        {
            $data['body_tpl_id'] = $body_tpl_id;
        }
        elseif (defined('DEFAULT_BODY_TPL') && DEFAULT_BODY_TPL)
        {
            $data['body_tpl_id'] = DEFAULT_BODY_TPL;
        }
        if (isset($this->input['normal_color']))
        {
            $normal_color = trim(urldecode($this->input['normal_color']));
            if ($normal_color && !checkColor($normal_color))
            {
                $this->errorOutput(COLOR_ERROR);
            }
            $data['normal_color'] = $normal_color;
        }

        if (isset($this->input['normal_alpha']))
        {
            $normal_alpha = trim(urldecode($this->input['normal_alpha']));
            if ($normal_alpha != '')
            {
                $normal_alpha = floatval($normal_alpha);
                if ($normal_alpha < 0 || $normal_alpha > 1)
                {
                    $this->errorOutput(PROPERTY_AUTH_FAIL);
                }
            }
            $data['normal_alpha'] = $normal_alpha;
        }

        if (isset($this->input['press_color']))
        {
            $press_color = trim(urldecode($this->input['press_color']));
            if ($press_color && !checkColor($press_color))
            {
                $this->errorOutput(COLOR_ERROR);
            }
            $data['press_color'] = $press_color;
        }

        if (isset($this->input['press_alpha']))
        {
            $press_alpha = trim(urldecode($this->input['press_alpha']));
            if ($press_alpha != '')
            {
                $press_alpha = floatval($press_alpha);
                if ($press_alpha < 0 || $press_alpha > 1)
                {
                    $this->errorOutput(PROPERTY_AUTH_FAIL);
                }
            }
            $data['press_alpha'] = $press_alpha;
        }

        if ($webview_id <= 0 && empty($webview_url) && $solidify_id <= 0 && $ui_id <= 0)
        {
            $ui_id = DEFAULT_UI;
        }
        /*
         //判断名称字符限制
         if (MODULE_NAME_LIMIT)
         {
         $str = @iconv('', 'UTF-8', $module_name);
         $len = mb_strlen($str, 'UTF-8');
         if ($len > MODULE_NAME_LIMIT)
         {
         $this->errorOutput(CHAR_OVER);
         }
         }
         if (MODULE_ENGLISH_LIMIT)
         {
         $str = @iconv('', 'UTF-8', $english_name);
         $len = mb_strlen($str, 'UTF-8');
         if ($len > MODULE_ENGLISH_LIMIT)
         {
         $this->errorOutput(CHAR_OVER);
         }
         }
         */
        if ($ui_id > 0)
        {
            $data['ui_id'] = $ui_id;
        }
        elseif ($webview_id > 0 || !empty($webview_url))
        {
            $data['webview_id'] = $webview_id;
            $data['webview_url'] = $webview_url;
        }
        elseif ($solidify_id > 0)
        {
            $data['solidify_id'] = $solidify_id;
        }
        return $data;
    }

    /**
     * 获取查询条件
     *
     * @access private
     * @param  appId:应用id
     * @return array
     */
    private function condition()
    {
        $app_id = intval($this->input['appId']);
        $data   = array(
		    'uid' => $this->user['user_id']
        );

        if ($app_id)
        {
            $data['app_id'] = $app_id;
        }
        return $data;
    }
    
    /**
     * 自动采集数据时得到的url 存到对应表中
     * @authorjitao
     */
    public function saveBaixingUrl(){
    	if(!$this->user['user_id'])
    	{
    		$this->errorOutput(TOKEN_VALIDATE_FAIL);
    	}
    	//百姓网返回的data_url存到模块信息里
    	$data_url = trim($this->input['dataUrl']);
    	$app_id = intval($this->input['appId']);
    	$module_id = intval($this->input['moduleId']);
    	$type = $this->input['type'];
    	if($app_id=="")
    	{
    		$this->errorOutput(APP_ID_NULL);
    	}
    	if($module_id=="")
    	{
    		$this->errorOutput(MODULE_ID_NULL);
    	}	
    	if($type == "auto" || $type == 'manual')
    	{
    		if($type == 'auto' && $data_url == "")
    		{
    			$this->errorOutput(DATA_URL_NULL);
    		}
    		if($type == 'manual')
    		{
    			$data = array(
    				'bind_id' => '',
    				'bind_status' => 1,
    				'bind_type' => $type,
    				'bind_params' => '',
    				'data_url' => '',
    				'bind_collect'=> 1,
    			);
    		}
    		else if($type == 'auto')
    		{
    			$urlarr=parse_url($data_url);
    			parse_str($urlarr['query'],$parr);
    			$bind_params = serialize($parr);
    			$data = array(
    				'bind_id' => 1,
    				'bind_status' => 1,
    				'bind_type' => $type,
    				'bind_params' => $bind_params,
    				'data_url' => $data_url,
    				'bind_collect'=> '',
    			);
    		}
    		//需要更新的数据的集合
    		$condition = array(
    				'app_id' => $app_id,
    				'id' =>$module_id,
    		);
    		//先验证是modul是否存在 同时module与app对应
    		$validate = $this->api->getAppMopduleByAppidAndModuleId($app_id, $module_id);
    		if($validate)
    		{
    			$result = $this->api->update('app_module', $data, $condition);
    			if($result)
    			{
    				$this->addItem($result);
    			}
    		}
    		else
    		{
    			$this->errorOutput(BIND_UPDATE_FAIL);
    		}
    	}
    	else
    	{
    		$this->errorOutput(TYPE_WRONG);
    	}
    	$this->output();
    }
    
    //根据栏目id获取pagecontent模板
    public function getPageContentByColumnId()
    {
        $column_id = $this->input['column_id'];//此栏目一定要是主栏目
        if(!$column_id)
        {
            $this->errorOutput(NO_COLUMN_ID);
        }
        
        //通过主栏目查找对应的模块
        $moudle = $this->api->detail('app_module', array('column_id' => $column_id),'body_tpl_id');
        if(!$moudle || !is_array($moudle) || !$moudle['body_tpl_id'])
        {
            $this->errorOutput(THIS_MOUDLE_NOT_EXIST);
        }
        
        //通过正文模板id查找pagecontent
        $bodyTpl = $this->body_tpl_mode->detail($moudle['body_tpl_id']);
        if(!$bodyTpl)
        {
            $this->errorOutput(BODY_TPL_NOT_EXIST);    
        }
        
        //输出pageContent
        $this->addItem(array(
                'body_tpl_id' => $bodyTpl['id'],
        		'pageContent' => html_entity_decode($bodyTpl['page_content_html'],ENT_QUOTES),
                'body_html'    => html_entity_decode($bodyTpl['body_html'],ENT_QUOTES),
        ));
        $this->output();
    }

    //根据模板标识获取正文模板
    public function getPageContentByTplID()
    {
        $tplId = trim($this->input['tpl_uniqueid']);
        if(!$tplId)
        {
            $this->errorOutput(NO_TPL_UNIQUEID);
        }
        
        //通过正文模板id查找pagecontent
        $bodyTpl = $this->body_tpl_mode->getTplInfo(" AND uniqueid = '" .$tplId. "' ");
        if(!$bodyTpl)
        {
            $this->errorOutput(BODY_TPL_NOT_EXIST);    
        }
        
        //输出pageContent
        $this->addItem(array(
                'body_tpl_id' => $bodyTpl['id'],
        		'pageContent' => html_entity_decode($bodyTpl['page_content_html'],ENT_QUOTES),
                'body_html'    => html_entity_decode($bodyTpl['body_html'],ENT_QUOTES),
        ));
        $this->output();
    }
    
    //获取木块数据
    public function getModulesByAppId()
    {
         if(!$this->user['user_id'])
         {
             $this->errorOutput(NO_LOGIN);
         }   
         
         $app_id = intval($this->input['app_id']);
         if(!$app_id)
         {
             $this->errorOutput(NO_APP_ID);
         }
         
         $cond = " AND app_id = '" .$app_id. "' AND user_id = '" .$this->user['user_id']. "' ";
         $data = $this->api->getModulesData($cond);
         if($data)
         {
             $this->addItem($data);
             $this->output();
         }
         else 
         {
             $this->errorOutput(NO_DATA);
         }
    }
    
    /**
     * @根据所有的ID拿到所有的模块对应的类型
     * @param string  $ids 
     * 输出 array 栏目id与栏目类型的数组
     */
    public function getAllModuleByIds()
    {
    	if(isset($this->input['ids']))
    	{
    		$ids = $this->input['ids'];
    	}	
		$idsArray = explode(",",$ids);
		$idTypeArray = array();
    	if($idsArray && is_array($idsArray))
    	{
    		foreach ($idsArray as $key => $val)
    		{
    			$queryData = array(
	        		'column_id' => $val,
       			);
        		$moduleInfo = $this->api->detail('app_module', $queryData);
        		if ($moduleInfo['web_view'] != 0)
        		{
        			$moduleType = 'webview';
        		}
        		elseif ($moduleInfo['solidify_id'] > 0)
        		{
        			$moduleType = 'solidify';
        		}
        		elseif ($moduleInfo['bind_id'] > 0 && $moduleInfo['bind_status'])
        		{
        			$moduleType = 'bind';
        		}
        		else
        		{
        			$moduleType = 'content';
        		}
        		$idTypeArray[$val] = $moduleType;
    		}	
    	}
    	$this->addItem($idTypeArray);
    	$this->output();
    	
    }
    
    //选取或者取消组件
    public function selectComponent()
    {
        $user_id = intval($this->user['user_id']);
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //模块ID
        $module_id = intval($this->input['module_id']);
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        
        //组件ID，多个用逗号分隔
        $comp_ids = $this->input['comp_ids'];

        //首先查询出该用户有没有此模块
        $moduleInfo = $this->api->detail('app_module', array(
                    'id'      => $module_id,
                    'user_id' => $user_id,
        ));
        
        if(!$moduleInfo)
        {
            $this->errorOutput(THIS_MOUDLE_NOT_EXIST);
        }
        
        $compIdsArr = array();
        if($comp_ids)
        {
            $compIdsArr = explode(',', $comp_ids);
            //检查这些组件是不是这个人的
            $_cond = " AND user_id = '" . $this->user['user_id'] . "' AND id IN (" .$comp_ids. ") ";
            $compsInfo = $this->comp_mode->show($_cond,'','','id');
            if(!$compsInfo)
            {
                $this->errorOutput(COMP_NOT_EXTSTS);
            }
            
            $existsCompIdsArr = array_keys($compsInfo);
            //查看组件ID的合法性
            if(array_diff($compIdsArr, $existsCompIdsArr))
            {
                $this->errorOutput(COMP_ID_FEI_FA);
            }
    
            if(count($compIdsArr) !== count($existsCompIdsArr))
            {
                $this->errorOutput(COMP_ID_FEI_FA);
            }
        }

        //首先删除原来的关系数据
        if($this->comp_mode->deleteModuleCompByModuleId($module_id))
        {
            if($compIdsArr)
            {
                //创建每一条关系数据
                foreach($compIdsArr AS $k => $v)
                {
                    $this->comp_mode->createModuleComp(array(
                                'module_id'   => $module_id,
                                'comp_id'     => $v,
                                'user_id'     => $this->user['user_id'],
                                'user_name'   => $this->user['user_name'],
                                'create_time' => TIMENOW, 
                    ));
                }
            }
            
            $this->addItem(array('return' => 1));
            $this->output();
        }
        else 
        {
            $this->errorOutput(FAILED);
        }
    }
}

$out = new app_module();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();