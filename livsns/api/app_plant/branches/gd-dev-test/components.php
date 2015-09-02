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
 * @description 组件接口
 **************************************************************************/
define('MOD_UNIQUEID','components');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/components_mode.php');
require_once(CUR_CONF_PATH . 'lib/UpYunOp.class.php');
require_once(CUR_CONF_PATH . 'lib/user_interface_mode.php');
require_once(CUR_CONF_PATH . 'lib/app_info_mode.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');

class components extends outerUpdateBase
{
    private $mode;
    private $_upYunOp;
    private $ui_mode;
    private $app_mode;
    private $_app;
    public function __construct()
    {
        parent::__construct();
        $this->mode     = new components_mode();
        $this->_upYunOp = new UpYunOp();
        $this->ui_mode  = new user_interface_mode();
        $this->app_mode = new app_info_mode();
        $this->_app     = new app();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
    
    //显示某个人的
    public function show()
    {
        $offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
    }
    
    public function count()
    {
        $condition = $this->get_condition();
        $info = $this->mode->count($condition);
        echo json_encode($info);
    }
    
    public function get_condition()
	{
		$condition = '';
		
		if(!$this->user['user_id'])
		{
		    $this->errorOutput(NO_LOGIN);
		}
		else 
		{
		    $condition .= " AND user_id = '" .$this->user['user_id']. "' ";
		}

		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['is_open'])
		{
		    $condition .= " AND is_open = 1 ";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  name  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
    
    //创建一个组件
    public function create()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }

        /*****************************************组件自身的一些参数*******************************************/
	    $name = trim($this->input['name']);//组件的名称
	    if(!$name)
	    {
	        $this->errorOutput(NO_COMPONENT_NAME);
	    }
	    
        $ui_id = intval($this->input['ui_id']);
	    if(!$ui_id)
	    {
	        $this->errorOutput(YOU_SELECTED_LISTUI_ERROR);
	    }
	    
        $listui_mark = trim($this->input['listui_mark']);//绑定的listUI
	    if(!$listui_mark)
	    {
	        $this->errorOutput(YOU_SELECTED_LISTUI_ERROR);
	    }
	    
	    /*****************************************组件自身的一些参数*******************************************/
	    
	    /*****************************************数据源的一些参数********************************************/
	    $column_id   = intval($this->input['column_id']);//栏目id，不存在就是自动获取
	    $column_name = $this->input['column_name'];//栏目名称
	    $nums        = intval($this->input['nums']);//获取数据的条目
	    $start_weight= intval($this->input['start_weight']);//开始权重
	    $end_weight  = intval($this->input['end_weight']);//结束权重

	    if(!$column_id)
	    {
	        $column_name = '';
	    }
	    
	    if($start_weight > $end_weight)
	    {
	        $this->errorOutput(WEIGHT_ERROR);
	    }
	    
	    /*****************************************数据源的一些参数********************************************/
	    
	    //首先创建一条数据源
	    $sourceDataId = $this->mode->createDataSource(array(
	                'column_id'   => $column_id,
	                'column_name' => $column_name,
	                'start_weight'=> $start_weight,
	                'end_weight'  => $end_weight,
	                'nums'		  => $nums,
	                'user_id'     => $this->user['user_id'],
	                'user_name'	  => $this->user['user_name'],
	                'create_time' => TIMENOW,
	                'update_time' => TIMENOW,
	    ));
	    
	    if(!$sourceDataId)
	    {
	        $this->errorOutput(CREATE_SOURCE_DATA_ERROR);
	    }
	    
	    //创建好数据源之后创建组件
	    $data = array(
	        'name'			 => $name,
	        'ui_id'		     => $ui_id,
	        'listui_mark'	 => $listui_mark,
            'source_id'		 => $sourceDataId,
    	    'user_name'      => $this->user['user_name'],
    	    'user_id'        => $this->user['user_id'],
	        'create_time'	 => TIMENOW,
	        'update_time'	 => TIMENOW,
	    );
	    
	    //组件示意图
	    if(isset($_FILES['img_info']) && $_FILES['img_info'] && !$_FILES['img_info']['error'])
	    {
	        $img = $this->_upYunOp->uploadToBucket($_FILES['img_info'],'',$this->user['user_id']);
            if($img)
            {
                $img_info = array(
					'host' 		=> $img['host'],
					'dir' 		=> $img['dir'],
					'filepath' 	=> $img['filepath'],
					'filename' 	=> $img['filename'],	
					'imgwidth'	=> $img['imgwidth'],
					'imgheight'	=> $img['imgheight'],
                );
                $data['img_info'] = addslashes(serialize($img_info));
            }
	    }

	    $ret = $this->mode->create($data);
	    if($ret)
	    {
	        $this->addItem(array('return' => 1));
	        $this->output();
	    }
	    else 
	    {
	        $this->errorOutput(FAILED);
	    }
    }
    
    //更新某个组件
    public function update()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $id = intval($this->input['id']);//组件的id
        if(!$id)
        {
            $this->errorOutput(NOID);
        }
        
        //查询该用户存不存在这个组件，防止其他用户把别人的组件给修改了
        $_component = $this->mode->detail(''," AND user_id = '" .$user_id. "' AND id = '" .$id. "' ");
        if(!$_component)
        {
            $this->errorOutput(YOU_HAVE_NOT_THIS_COMP);
        }
        
        /*****************************************组件自身的一些参数*******************************************/
        $name = trim($this->input['name']);//组件的名称
	    if(!$name)
	    {
	        $this->errorOutput(NO_COMPONENT_NAME);
	    }
	    
        $ui_id = intval($this->input['ui_id']);
	    if(!$ui_id)
	    {
	        $this->errorOutput(YOU_SELECTED_LISTUI_ERROR);
	    }
	    
        $listui_mark = trim($this->input['listui_mark']);//绑定的listUI
	    if(!$listui_mark)
	    {
	        $this->errorOutput(YOU_SELECTED_LISTUI_ERROR);
	    }
	    
	    /*****************************************组件自身的一些参数*******************************************/
	    
	    /*****************************************数据源的一些参数********************************************/
	    $column_id   = intval($this->input['column_id']);//栏目id
	    $column_name = $this->input['column_name'];//栏目名称
	    $nums        = intval($this->input['nums']);//获取数据的条目
	    $start_weight= intval($this->input['start_weight']);//开始权重
	    $end_weight  = intval($this->input['end_weight']);//结束权重
	    
        if(!$column_id)
	    {
	        $column_name = '';
	    }
	    
        if($start_weight > $end_weight)
	    {
	        $this->errorOutput(WEIGHT_ERROR);
	    }
	    
	    /*****************************************数据源的一些参数********************************************/

	    $data = array(
	        'name'		   => $name,
	        'ui_id'		   => $ui_id,
	        'listui_mark'  => $listui_mark,
	        'update_time'  => TIMENOW,
	    );
	    
        //组件示意图
	    if(isset($_FILES['img_info']) && $_FILES['img_info'] && !$_FILES['img_info']['error'])
	    {
	        $img = $this->_upYunOp->uploadToBucket($_FILES['img_info'],'',$this->user['user_id']);
            if($img)
            {
                $img_info = array(
					'host' 		=> $img['host'],
					'dir' 		=> $img['dir'],
					'filepath' 	=> $img['filepath'],
					'filename' 	=> $img['filename'],	
					'imgwidth'	=> $img['imgwidth'],
					'imgheight'	=> $img['imgheight'],
                );
                $data['img_info'] = addslashes(serialize($img_info));
            }
	    }
	    
        $ret = $this->mode->update($id,$data);
	    if($ret)
	    {
	        //更新之后更新数据源
	        $this->mode->updateDataSource($_component['source_id'],array(
	                        'column_id'	   => $column_id,
	                        'column_name'  => $column_name,
	                        'start_weight' => $start_weight,
	                		'end_weight'   => $end_weight,
    	                    'nums'		   => $nums,
	                        'update_time'  => TIMENOW,
	        ));
	        
	        $this->addItem(array('return' => 1));
	        $this->output();
	    }
	    else 
	    {
	        $this->errorOutput(FAILED);
	    }
    }
    
    //获取某个组件的详情
    public function detail()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $id = intval($this->input['id']);
        if(!$id)
        {
            $this->errorOutput(NOID);
        }
        
        $_component = $this->mode->detail(''," AND user_id = '" .$user_id. "' AND id = '" .$id. "' ");
        if($_component)
        {
            //获取这个组件绑定的数据源
            $dataSource = $this->mode->detailCompSource($_component['source_id']);
            if($dataSource)
            {
                $_component['data_source'] = $dataSource;
            }
            $this->addItem($_component);
            $this->output();
        }
        else 
        {
            $this->errorOutput(YOU_HAVE_NOT_THIS_COMP);
        }
    }
    
    //用户删除某一个组件
    public function delete()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $id = intval($this->input['id']);
        if(!$id)
        {
            $this->errorOutput(NOID);
        }
        
        //首先查看该用户有没有此组件
        $_component = $this->mode->detail(''," AND user_id = '" .$user_id. "' AND id = '" .$id. "' ");
        if(!$_component)
        {
            $this->errorOutput(YOU_HAVE_NOT_THIS_COMP);
        }
        
        $ret = $this->mode->delete($id);
        if($ret)
        {
            //组件删除成功之后需要删除该组件对应的listUI的配置的值
            $this->mode->deleteCompUiListValue(" AND comp_id = '" . $id . "' ");
            $this->mode->deleteCompListValue(" AND comp_id = '" . $id . "' ");
            $this->mode->deleteModuleCompByCond(" AND comp_id = '" . $id . "' ");
            $this->addItem(array('return' => 1));
	        $this->output();
        }
        else 
        {
            $this->errorOutput(FAILED);
        }
    }
    
    //绑定listUI
    public function bindListUI()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }

        $id = intval($this->input['id']);//需要绑定的组件id
        if(!$id)
        {
            $this->errorOutput(NOID);
        }
        
        $listui_mark = trim($this->input['listui_mark']);
        if(!$listui_mark || !in_array($listui_mark,$this->settings['comp_list_ui']))
        {
            $this->errorOutput(YOU_SELECTED_LISTUI_ERROR);
        }
        
        //首先查看改用户有没有此组件
        $_component = $this->mode->detail(''," AND user_id = '" .$user_id. "' AND id = '" .$id. "' ");
        if(!$_component)
        {
            $this->errorOutput(YOU_HAVE_NOT_THIS_COMP);
        }
        
        $ret = $this->mode->update($id,array(
                'listui_mark' => $listui_mark,
        ));
        
        if($ret)
        {
            $this->addItem(array('return' => 1));
	        $this->output();
        }
        else 
        {
            $this->errorOutput(FAILED);
        }
    }
    
    //绑定数据源
    public function bindDataSource()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }

        $id = intval($this->input['id']);//需要绑定的组件id
        if(!$id)
        {
            $this->errorOutput(NOID);
        }
        
        $source_id = intval($this->input['source_id']);//数据源id
        if(!$source_id)
        {
            $this->errorOutput(NO_SELECT_DATA_SOURCE);//未选择数据源
        }
        
        //首先查看改用户有没有此组件
        $_component = $this->mode->detail(''," AND user_id = '" .$user_id. "' AND id = '" .$id. "' ");
        if(!$_component)
        {
            $this->errorOutput(YOU_HAVE_NOT_THIS_COMP);
        }
        
        //查看数据源是不是该用户自己创建，不能绑定别人的数据源
        $_source = $this->mode->detailCompSource(''," AND user_id = '" .$user_id. "' AND id = '" . $source_id . "' ");
        if(!$_source)
        {
            $this->errorOutput(YOU_HAVE_NOT_THIS_DATA_SOURCE);
        }
        
        //开始绑定
        $ret = $this->mode->update($id,array(
               'source_id' => $source_id,
        ));
        
        if($ret)
        {
            $this->addItem(array('return' => 1));
	        $this->output();
        }
        else 
        {
            $this->errorOutput(FAILED);
        }
    }
    
    //开启或者关闭某个组件
    public function doSwitchComponent()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }

        $id = intval($this->input['id']);//需要开启或者关闭的组建id
        if(!$id)
        {
            $this->errorOutput(NOID);
        }
        
        $is_open = intval($this->input['is_open']);//是否开启还是关闭组件
        
        //首先查看该用户有没有此组件
        $_component = $this->mode->detail(''," AND user_id = '" .$user_id. "' AND id = '" .$id. "' ");
        if(!$_component)
        {
            $this->errorOutput(YOU_HAVE_NOT_THIS_COMP);
        }
        
        //如果用户是要关闭组件，需要判断该组件有没有已经被某个模块使用
        if(!$is_open)
        {
            $mod_comp = $this->mode->getCompIdsByCond(" AND user_id = '" .$user_id. "' AND comp_id = '" . $id . "' ");
            if($mod_comp)
            {
                $this->errorOutput(THIS_COMP_HAS_USED);
            }
        }

        //开始更新
        $ret = $this->mode->update($id,array(
               'is_open' => $is_open,
        ));
        
        if($ret)
        {
            $this->addItem(array('return' => 1));
	        $this->output();
        }
        else 
        {
            $this->errorOutput(FAILED);
        }
    }
    
    //获取用于组件的listUI
    public function getListUIForComp()
    {
        $listUI = $this->ui_mode->show(" AND is_comp = 1 AND type = 2 ");
        if(!$listUI)
        {
            $this->errorOutput(NO_DATA);
        }
        $this->addItem($listUI);
        $this->output();
    }
    
    //删除某个组件的扩展字段样式设置的值
    public function deleteCompExtendFieldStyle()
    {
        $comp_id = $this->input['comp_id'];
        if(!$comp_id)
        {
            $this->errorOutput(NO_COMP_ID);
        }
        
        $ret = $this->mode->deleteCompExtendFieldStyle($comp_id);
        if($ret)
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
    }
    
    //删除某个模块的扩展字段样式设置的值
    public function createCompExtendFieldStyle()
    {
        $comp_id = $this->input['comp_id'];
        if(!$comp_id)
        {
            $this->errorOutput(NO_COMP_ID);
        }
        
        $data = array(
            'comp_id'   => $comp_id,
            'position'  => intval($this->input['position']),
            'field_type'=> $this->input['field_type'],
            'style_type'=> $this->input['style_type']?$this->input['style_type']:1,
            'text'		=> $this->input['text'],
            'icon'		=> $this->settings['extend_field_icon'][$this->input['field_type']],
            'is_price'  => $this->input['is_price']?1:0,
            'is_display'=> intval($this->input['is_display']),
        );
        
        $ret = $this->mode->createCompExtendFieldStyle($data);
        if($ret)
        {
            $this->addItem(array('return' => 1));
            $this->output();
        }
    }
    
    //设置组件角标的样式
    public function setCompCornerStyle()
    {
        $comp_id = $this->input['comp_id'];
        if(!comp_id)
        {
            $this->errorOutput(NO_COMP_ID);
        }
        
        $data = array(
	        'comp_id'        => $comp_id,
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
    
	    $ret = $this->mode->setCompCornerStyle($data);
	    if($ret)
	    {
	        $this->addItem($data);
            $this->output();
	    }
    }
    
    //根据组件获取设置的扩展字段的表现设置值
    public function getCompExtendFieldStyle()
    {
        $comp_id = $this->input['comp_id'];
        if(!$comp_id)
        {
            $this->errorOutput(NO_COMP_ID);
        }
        
        $fields = $this->mode->getCompExtendField($comp_id);
        if($fields)
        {
            $this->addItem($fields);
            $this->output();
        }
    }
    
    //获取某个组件角标的相关信息
    public function getCompCornerData()
    {
        $comp_id = $this->input['comp_id'];
        if(!$comp_id)
        {
            $this->errorOutput(NO_COMP_ID);
        }
        
        $corner = $this->mode->getCompCornerData($comp_id);
        if($corner)
        {
            $this->addItem($corner);
            $this->output();
        }
    }
    
    /******************************************数据源相关操作*****************************************************/
    //创建数据源
    public function createDataSource()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $column_id = intval($this->input['column_id']);//绑定的栏目id
        $nums = intval($this->input['nums']);//数据条数
        if(!$column_id)
        {
            $this->errorOutput(NO_COLUMN_ID);
        }
        
        $data = array(
            'column_id'   => $column_id,
            'nums'		  => $nums,
            'user_id'	  => $user_id,
            'user_name'   => $this->user['user_name'],
            'create_time' => TIMENOW,
            'update_time' => TIMENOW,
        );
        
        $ret = $this->mode->createDataSource($data);
        if($ret)
        {
            $this->addItem(array('return' => 1));
	        $this->output();
        }
        else 
        {
            $this->errorOutput(FAILED);
        }
    }
    
    //更新数据源
    public function updateDataSource()
    {
        
        
        
        
        
        
        
        
        
        
        
        
        
    }
    /******************************************数据源相关操作*****************************************************/
    
    
    /******************************************获取组件化的数据****************************************************/
    public function getListContentsByModuleId()
    {
        $guid = $this->input['guid'];
        $module_id = $this->input['module_id'];
        $ret = array();//保存最后返回的信息
        
        //首先查看存不存在这个应用
        $app_info = $this->app_mode->detail(''," AND guid = '" .$guid. "' ");
        if(!$app_info)
        {
            $this->errorOutput(APP_NOT_EXISTS);
        }
        
        //获取模块信息
        $module_info = $this->_app->detail('app_module', array(
                        'id'	 => $module_id,
                        'app_id' => $app_info['id'],
        ));
        
        if(!$module_info)
        {
            $this->errorOutput(THIS_MOUDLE_NOT_EXIST);
        }
        
        //如果该模块绑定了第三方数据，并且开启了第三方数据，需要查询出该模块绑定数据的类型
        if($module_info['bind_id'] && $module_info['bind_status'])
        {
            $bind_info = $this->_app->detail('data_bind', array('id' => $module_info['bind_id']));
            if($bind_info)
            {
                $module_info['bind_mark'] = $bind_info['mark'];
            }
        }
        
        //保存模块信息
        $ret['module_info'] = $module_info;
        
        //判断当前模块绑定的是否是LISTUI，只有ListUI才有必要取数据,并且还要判断当前的listUI是否是绑定的纯组件
        if(!$module_info['ui_id'])
        {
            $this->errorOutput(THIS_MODULE_NOT_BIND_LIST_UI);
        }
        
        //获取绑定的listUI详情信息
        $listUIInfo = $this->ui_mode->detail($module_info['ui_id']);
        if(!$listUIInfo)
        {
            $this->errorOutput(THIS_MODULE_NOT_BIND_LIST_UI);
        }
        
        //判断是否是纯组件列表
        $isPureComp = FALSE;//标识是否是纯组件
        if($listUIInfo['uniqueid'] == 'ListUI10')
        {
            $isPureComp = TRUE;
        }
        
        //保存模块是否是纯组件列表
        $ret['is_pure_comp'] = $isPureComp;
        
        //查询出该模块绑定的组件ID
        $modComp = $this->mode->getCompIdsByCond(" AND module_id = '" .$module_id. "' ",'comp_id');
        if($modComp)
        {
            $compIds = array_keys($modComp);
            //查询出组件以及数据源信息
            $_cond = " AND c.id IN (" .implode(',', $compIds). ") ";
            $compInfo = $this->mode->getCompWithSource($_cond);
            if($compInfo)
            {
                //保存组件信息
                $ret['comp_info'] = $compInfo;
            }
        }
        
        $this->addItem($ret);
        $this->output();
    }
    /******************************************获取组件化的数据****************************************************/
    
    public function checkCompIsUsed()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $comp_id = intval($this->input['comp_id']);
	    if(!$comp_id)
	    {
	        $this->errorOutput(NO_COMP_ID);
	    }
	    
	    //检测该
        if($this->mode->getCompIdsByCond(" AND comp_id = '" .$comp_id. "' "))
        {
            $this->addItem(array('is_used' => 1));
        }
        else 
        {
            $this->addItem(array('is_used' => 0));
        }
        $this->output();
    }
    
    public function unkow()
    {
        $this->errorOutput(UNKNOW);
    }
}

$out = new components();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'unkow';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();