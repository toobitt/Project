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
require_once(CUR_CONF_PATH . 'lib/new_extend.class.php');
require_once(CUR_CONF_PATH . 'lib/attribute_value_mode.php');

class components extends outerUpdateBase
{
    private $mode;
    private $_upYunOp;
    private $ui_mode;
    private $app_mode;
    private $_app;
    private $new_extend;
    private $attr_mode;
    public function __construct()
    {
        parent::__construct();
        $this->mode     = new components_mode();
        $this->_upYunOp = new UpYunOp();
        $this->ui_mode  = new user_interface_mode();
        $this->app_mode = new app_info_mode();
        $this->_app     = new app();
        $this->new_extend = new new_extend();
        $this->attr_mode = new attribute_value_mode();
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
			foreach($ret as $k => &$v)
			{
				//获取数据源
				$dataSource = $this->mode->detailCompSource($v['source_id']);
				if($dataSource)
				{
					$v['data_source'] = $dataSource;
				}
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
	    	'is_open'		 => 1,
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
    
    /***********************组件新扩展字段*******************************************/
    
    /**
     * 设置组件的扩展区域属性 
     */
    public function setNewExtendAreaPosition()
    {
    	$comp_id = intval($this->input['comp_id']);
    	$extend_area_position = intval($this->input['extend_area_position']);
    	$user_id = intval($this->user['user_id']);
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMP_ID);
    	}
    	if(!in_array($extend_area_position, $this->settings['new_extend']['extend_area_position']))
    	{
    		$this->errorOutput(AREA_POSITION_WRONG);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	//如果设置成固定高度 则要验证此=此组件是否支持固定高度
    	if($extend_area_position == $this->settings['new_extend']['extend_area_position']['fixed'])
    	{
    		//获取组件信息
    		$comp = $this->mode->detail($comp_id);
    		if($comp['listui_mark'] != $this->settings['new_extend']['need_set_list_ui_uniqueid'])
    		{
    			$this->errorOutput(MODULE_LISTUI_WORING);
    		}
    	}
    	//更新
    	$data = array(
    			'extend_area_position' => $extend_area_position,
    		
    	);
    	$ids_arr = array(
    			'id' 		=> $comp_id,
    			'user_id'	=> $user_id,
    	);
    	$this->new_extend->update('components', $data, $ids_arr);
    	
    	//更新此组件内所有的行的位置
    	//待写。。。
    	$all_lines = $this->new_extend->getInfo('comp_extend_line',array(
    			'user_id'	=> $user_id,
    			'comp_id'	=> $comp_id,
    	));
    	if($all_lines)
    	{
    		//位置全部换成up
    		$up_data = array(
    				'line_position'	=>	$this->settings['new_extend']['line_position']['up'],
    		);
    		$up_condition = array(
    				'user_id'	=>	$user_id,
    				'comp_id'	=>	$comp_id,
    		);
    		$this->new_extend->update('comp_extend_line', $up_data, $up_condition);
    	}
    	$this->addItem(array('return' => 1));
    	$this->output();
    }
    
    /**
     * 获取组件内所有的行
     */
    public function getAllLineInfo()
    {
    	$comp_id = intval($this->input['comp_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMP_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$ret = $this->mode->getALLLineInfoByCompId($user_id,$comp_id);
    	$up_num = 0;
    	$down_num = 0;
    	//处理名字
    	if($ret['up'])
    	{
    		$up_num = count($ret['up']);
    		foreach ($ret['up'] as $k => &$v)
    		{
    			$v['show_name'] = "扩展行".($k+1);
    		}
    	}
    	if($ret['down'])
    	{
    		$down_num = count($ret['down']);
    		foreach ($ret['down'] as $_k => &$_v)
    		{
    			$_v['show_name'] = "扩展行".($down_num+$up_num-$_k);
    		}
    	}
    	
    	$info = array();
    	$info['lines'] = $ret;
    	//获取当前组件信息，是否需要设置扩展区域属性
    	$comp_info = $this->new_extend->detail('components', array(
    			'id'		=> $comp_id,
    			'user_id'	=> $user_id,
    	));
    	$list_ui_info = $this->ui_mode->detail($comp_info['ui_id']);
    	$info['need_set_position'] = ($list_ui_info['uniqueid'] == $this->settings['new_extend']['need_set_list_ui_uniqueid']) ? 1 : 0;
    	$info['now_position'] = intval($comp_info['extend_area_position']);
    	$this->addItem($info);
    	$this->output();
    }
    
    /**
     * 设置扩展行信息
     */
    public function setNewExtendLineInfo()
    {
    	$user_id = intval($this->user['user_id']);
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$comp_id = intval($this->input['comp_id']);
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMP_ID);
    	}
    	//验证这个组件是不是这个人的
    	$isExistsComp = $this->new_extend->detail('components', array(
    			'user_id'	=> $user_id,
    			'id'		=> $comp_id,
    	));
    	if(!$isExistsComp)
    	{
    		$this->errorOutput(YOU_HAVE_NOT_THIS_COMP);
    	}
    	$attr_value = $this->input['attr_value'];
    	$line_num = intval($this->input['line_num']);
    	$line_type = intval($this->input['line_type']);
    	if($line_type == $this->settings['new_extend']['line_type']['one'])
    	{
    		$line_num = 1;
    	}
    	$line_position = intval($this->input['line_position']);
    	//获取line_id 有就是编辑 如果没有则是添加
    	$line_id = intval($this->input['line_id']);
    	if($line_id)
    	{
    		//有line_id 则进行编辑
    		//先获取当前line_info 判断是否需要对扩展行下的单元进行处理
    		$old_line_info = $this->new_extend->detail('comp_extend_line',array(
    				'comp_id'	=> $comp_id,
    				'user_id'	=> $user_id,
    				'id'		=> $line_id,
    		));
    		//如果type变了,对行里的单元进行处理
    		if($old_line_info['line_type'] != $line_type)
    		{
    			//获取目前这个行里所有的单元
    			$all_fields = $this->new_extend->getInfo('comp_extend_field',array(
    					'comp_id'	=> $comp_id,
    					'line_id'	=> $line_id,
    					'user_id'	=> $user_id,
    			));
    			if($all_fields)
    			{
    				//两种情况，1、原本单行现在多行
    				if($line_type == 2)
    				{
    					$left_fields = $this->new_extend->getInfo('comp_extend_field',array(
    							'field_position' => $this->settings['new_extend']['field_position']['left'],
    							'line_id'		 => $line_id,
    							'user_id'	     => $user_id,
    							'comp_id'		 => $comp_id,
    					));
    					//又有两种情况，1、设了左边
    					if($left_fields)
    					{
    						//最左的不变，将剩下的field_position 改为右
    						$temp_left_est_field = $this->mode->getOne($comp_id,$line_id,'asc',$this->settings['new_extend']['field_position']['left']);
    						$left_est_field = $temp_left_est_field[0];
    						foreach ($all_fields as $___k => $___v)
    						{
    							if($___v['id'] != $left_est_field['id'])
    							{
    								$this->new_extend->update('comp_extend_field', array(
    										'field_position'	=> $this->settings['new_extend']['field_position']['right'],
    								), array(
    										'id'		=> $___v['id'],
    										'line_id'	=> $line_id,
    										'comp_id'	=> $comp_id,
    										'user_id'	=> $user_id,
    								));
    							}
    						}
    					}
    					else//2、原本只设了右边，没有左边
    					{
    						//最右的一个field_position改为左，其他不变
    						$temp_right_est_field = $this->mode->getAllFields($comp_id,$line_id,'asc');
    						$right_est_field = $temp_right_est_field[0];
    						$this->new_extend->update('new_extend_field', array(
    								'field_position'	=> $this->settings['new_extend']['field_position']['left'],
    						), array(
    								'id'		=> $___v['id'],
    								'line_id'	=> $line_id,
    								'comp_id'	=> $comp_id,
    								'user_id'	=> $user_id,
    						));
    					}
    				}
    				elseif ($line_type == 1)//2、原本多行 现在单行
    				{
    					//将所有扩展单元设置居左
    					$left_data = array(
    							'field_position'	=> $this->settings['new_extend']['field_position']['left'],
    					);
    					$left_condition = array(
    							'user_id'	=> $user_id,
    							'comp_id'	=> $comp_id,
    							'line_id'	=> $line_id,
    					);
    					$this->new_extend->update('comp_extend_field', $left_data, $left_condition);
    				}
    			}    		
    		}
    		//先更新line_info的基本信息
    		$update_line_infodata = array(
    				'line_num'		=> $line_num,
    				'line_type'		=> $line_type,
    				'line_position'	=> $line_position,
    		);
    		$idsArr = array(
    				'id'		=> $line_id,
    				'comp_id'	=> $comp_id,
    				'user_id'	=> $user_id,
    		);
    		$this->new_extend->update('comp_extend_line', $update_line_infodata, $idsArr);
    		//更新属性
    		$set_type = 'update';
    		$return = $this->setLineValue($attr_value,$comp_id,$line_id,$set_type,$user_id);
    		$this->addItem(array('line_id'=>$line_id));
    	}
    	else//创建line
    	{
    		//如果没有line_id，添加新扩展行,判断当前模块有多少扩展行了，最多XX个
    		$all_line_info = $this->new_extend->getInfo('comp_extend_line',array(
    				'user_id'		=> $user_id,
    				'comp_id'		=> $comp_id,
    			)
    		);
    		if(count($all_line_info) >= $this->settings['new_extend']['extend_line_max_num'])
    		{
    			$this->errorOutput(EXTEND_LINE_NUM_MAX);
    		}
    		//先创建一条extend_line的信息
    		$new_line_info = $create_line = $this->new_extend->create('comp_extend_line', array(
    				'create_time'	=> TIMENOW,
    				'comp_id'		=> $comp_id,
    				'line_num'		=> $line_num,
    				'line_type'		=> $line_type,
    				'line_position'	=> $line_position,
    				'user_id'		=> $user_id,
    			)
    		);
    		//将order_id更新进去
    		$up_order_arr = array(
    				'comp_id'	=> $comp_id,
    				'id'		=> $new_line_info['id'],
    		);
    		$this->new_extend->update('comp_extend_line', array('order_id' => $new_line_info['id']),$up_order_arr);
    		$set_type = 'create';
    		
    		$return = $this->setLineValue($attr_value,$comp_id,$new_line_info['id'],$set_type,$user_id);
    		$this->addItem(array('line_id'	=> $new_line_info['id']));	
    	}
    	$this->output();
    }
    
    /**
     * 保存行的属性
     * @param unknown $attr_value
     * @param unknown $comp_id
     */
    private function setLineValue($attr_value, $comp_id = 0 , $line_id = 0 , $set_type = '' , $user_id = 0)
    {
    	if($attr_value && is_array($attr_value))
    	{
    		$ids = array_keys($attr_value);
    		$attr_data = $this->attr_mode->getFrontAttrByIds($ids);
    		if($attr_data)
    		{
    			foreach ($attr_data as $__k => $__v)
    			{
    				if(!isset($attr_value[$__v['id']]))
    				{
    					continue;
    				}
    		
    				$_value = '';
    				$_front_value = $attr_value[$__v['id']];
    				switch ($__v['attr_type_name'])
    				{
    					//取值范围
    					case 'span':$_value = trim($_front_value);break;
    				}
    				if($set_type == 'create')
    				{
    					$this->mode->setNewExtendLineUiValue(array(
    							'line_id' 		=> $line_id,
    							'comp_id' 		=> $comp_id,
    							'ui_attr_id' 	=> $__v['id'],
    							'attr_value' 	=> $_value,
    							'user_id'		=> $user_id,
    						)
    					);
    				}
    				if($set_type == 'update')
    				{
    					//先编辑前台的值
    					$updata_data = array(
    							'attr_value'	=> $_value,
    					);
    					$ids_arr = array(
    							'line_id'		=> $line_id,
    							'ui_attr_id'	=> $__v['id'],
    							'comp_id'		=> $comp_id,
    							'user_id'		=> $user_id,
    					);
    					$this->new_extend->update('comp_extend_line_ui_attr_value', $updata_data, $ids_arr);
    				}
    				
    				//编辑完前台属性值还要根据关系编辑后台属性的值   				
    				//对关联的后台属性统一编辑值
    				if($__v['set_value_type'] == 1)
    				{
    					$this->mode->setNewExtendAttrSameToRelate($__v['id'] , $_value , $line_id , $set_type);
    				}
    			}
    		}	
    	}
    	return true;
    }
    
    
    
    /**
     * 获取扩展行信息
     * 扩展行属性
     */
    public function getNewExtendLineInfo()
    {
    	$user_id = intval($this->user['user_id']);
    	$role_id = intval($this->input['role_id']);
    	$line_id = ($this->input['line_id']) ? intval($this->input['line_id']) : 0;
    	$comp_id = intval($this->input['comp_id']);
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMP_ID);
    	}
    	//先拿去comp的信息
    	$comp_info = $this->new_extend->detail('components', array(
    			'id'		=> $comp_id,
    			'user_id'	=> $user_id,
    	));
    	$listui_mark = $comp_info['listui_mark'];
    	$listui_info = $this->ui_mode->detail($comp_info['ui_id']);
    	if(!$listui_info)
    	{
    		$this->errorOutput(NOT_EXISTS_UI);
    	}
    	$info = array();
    	//判断是否需要设置固定高度选项，，，只有listui1需要
    	if($listui_info['uniqueid'] == $this->settings['new_extend']['need_set_list_ui_uniqueid'] && $comp_info['extend_area_position'] == 1)
    	{
    		$info['need_set_position'] = 1;
    	}
    	else
    	{
    		$info['need_set_position'] = 0;
    	}
    	
    	//获取后天属性组里对应
    	$ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$this->settings['new_extend']['new_extend_list_ui']. "' AND is_extend = 1 ");          
    	
    	//获取对应的前端属性
    	$attrData = $this->mode->getFrontExtendAttributeData($line_id,$comp_id,$role_id,$ui_info['id']);
    	$frontAttr = array();
    	$groupData = $this->attr_mode->getFrontGroupData();
    	foreach ($groupData as $_k => $_v)
    	{
    		foreach ($attrData as $__k => $__v)
    		{
    			if($__v['group_id'] == $_v['id'] && $_v['uniqueid'] == $this->settings['new_extend']['new_extend_line_group_uniqueid'])
    			{
    				$frontAttr[] = $__v;
    			}
    		}
    	}
    	$info['attr'] = $frontAttr;
    	$line_info= array();
    	if($line_id)
    	{
    		$line_info = $this->new_extend->detail('comp_extend_line', array(
    				'id' 	  => $line_id,
    				'comp_id' => $comp_id,
    				'user_id' => $user_id,
    			)
    		);
    	}
    	$info['line_info'] = $line_info;
    	$this->addItem($info);
    	$this->output();    	
    }
    
    /**
     * 删除扩展行
     */
    public function deleteExtendLine()
    {
    	$comp_id = intval($this->input['comp_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMP_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$line_id = intval($this->input['line_id']);
    	if(!$line_id)
    	{
    		$this->errorOutput(NO_EXTEND_LINE_ID);
    	}
    	//判断是否存在此line
    	$line_info = $this->new_extend->detail('comp_extend_line', array(
    			'id'		=> $line_id,
    			'comp_id' 	=> $comp_id,
    			'user_id'	=> $user_id,
    	));
    	if(!$line_info)
    	{
    		$this->errorOutput(NO_THIS_LINE_INFO);
    	}
    	$delete_line  = $this->new_extend->delete('comp_extend_line', array(
    			'id'		=> $line_id,
    			'comp_id'	=> $comp_id,
    			'user_id'	=> $user_id,
    	));
    	$delete_attr = $this->new_extend->delete('comp_extend_line_attr_value', array(
    			'line_id'	=> $line_id
    	));
    	$delete_ui = $this->new_extend->delete('comp_extend_line_ui_attr_value', array(
    			'line_id'	=> $line_id,
    			'comp_id'	=> $comp_id,
    			'user_id'	=> $user_id,
    	));
    	 
    	//删除这个行下的单元信息
    	$delete_field = $this->new_extend->delete('comp_extend_field', array(
    			'comp_id'	=> $comp_id,
    			'user_id'	=> $user_id,
    			'line_id'	=> $line_id,
    	));
    	 
    	$delete_field_attr = $this->new_extend->delete('comp_extend_field_attr_value', array(
    			'line_id'	=> $line_id,
    	));
    	 
    	$delete_field_ui = $this->new_extend->delete('comp_extend_field_ui_attr_value', array(
    			'line_id'	=> $line_id,
    	));
    	 
    	$this->addItem(array('return' => 1));
    	$this->output();
    }
    
    /**
     * 获取扩展行下所有的扩展单元的信息
     */
    public function getExtendFieldsInByLineId()
    {
    	$comp_id = intval($this->input['comp_id']);
    	$line_id = intval($this->input['line_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMP_ID);
    	}
    	if(!$line_id)
    	{
    		$this->errorOutput(NO_LINE_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	 
    	$ret_module = $this->mode->getAllFieldsInModule($comp_id,$user_id);
    	$ret_line = $this->mode->getAllFieldsInLine($comp_id,$line_id,$user_id);
    	$line_info = $this->new_extend->detail('comp_extend_line', array(
    			'id' 		=> 	$line_id,
    			'user_id'	=>	$user_id,
    	));
    	$this->addItem(array(
    			'ret_module'	=> $ret_module,
    			'ret_line'		=> $ret_line,
    			'line_info'		=> $line_info,
    	));
    	$this->output();
    }
    
    /**
     * 扩展行排序
     */
    public function extendLineOrder()
    {
    	$up_ids = trim($this->input['up_ids']);
    	$down_ids = trim($this->input['down_ids']);
    	$comp_id = intval($this->input['comp_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$comp_id)
    	{
    		$this->errorOutput();
    	}
    	 
    	//获取当下所有的行按照升序排列
    	$all_asc_lines = $this->mode->getAllLines($user_id,$comp_id,'asc');
    	 
    	//获取当下所有的行按照order降序排列
    	$all_desc_lines = $this->mode->getAllLines($user_id,$comp_id,'desc');
    	 
    	//先处理up部分
    	if($up_ids)
    	{
    		$up_arr = explode(',', $up_ids);
    		foreach ($up_arr as $k => $v)
    		{
    			//update最新的up部分的line的order_id
    			$up_data = array(
    					'order_id'		=> $all_asc_lines[$k]['order_id'],
    					'line_position'	=> 1,
    			);
    			$up_condtion = array(
    					'id'		=> $v,
    					'comp_id'	=> $comp_id,
    					'user_id'	=> $user_id,
    			);
    			$this->new_extend->update('comp_extend_line', $up_data, $up_condtion);
    		}
    
    	}
    	//down部分
    	if($down_ids)
    	{
    		$down_arr = explode(',', $down_ids);
    		foreach ($down_arr as $_k => $_v)
    		{
    			//update最新的down部分的line的order_id
    			$down_data = array(
    					'order_id'		=> $all_desc_lines[$_k]['order_id'],
    					'line_position'	=> 2,
    			);
    			$down_condtion = array(
    					'id'		=> $_v,
    					'comp_id'	=> $comp_id,
    					'user_id'	=> $user_id,
    			);
    			$this->new_extend->update('comp_extend_line', $down_data, $down_condtion);
    		}
    	}
    	$this->addItem(array('return' => 1));
    	$this->output();
    }
    
    
    /**
     * 创建扩展单元同时保存位置
     */
    public function setFieldPosition()
    {
    	$comp_id = intval($this->input['comp_id']);
    	$line_id = intval($this->input['line_id']);
    	$user_id = intval($this->user['user_id']);
    	$uniqueid = trim($this->input['uniqueid']);
    	$field_type = intval($this->input['field_type']);
    	$field_position = intval($this->input['field_position']);
    	$uni_name = trim($this->input['uni_name']);
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMP_ID);
    	}
    	if(!$line_id)
    	{
    		$this->errorOutput(NO_EXTEND_LINE_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	if(!in_array($field_position, $this->settings['new_extend']['field_position']))
    	{
    		$this->errorOutput(EXTEND_FIELD_POSTTION_WRONG);
    	}
    	//验证此uniqueid是否已经添加过
    	$is_add = $this->new_extend->detail('comp_extend_field', array(
    			'comp_id'	=>	$comp_id,
    			'line_id'	=>	$line_id,
    			'user_id'	=>	$user_id,
    			'uniqueid'	=>	$uniqueid,
    	));
    	if($is_add)
    	{
    		$this->errorOutput(UNIQUEID_HAS_EXISTS);
    	}
    	//验证field_type与uniqued
    	$special_type = $this->settings['new_extend']['special'];
    	foreach ($special_type as $_k => $_v)
    	{
    		if($_v['uniqueid'] == $uniqueid)
    		{
    			if($_v['field_type'] != $field_type)
    			{
    				$this->errorOutput(UNIQUEID_WORNG);
    			}
    		}
    	}
    	//验证此行是否已经超过个数
    	$now_field_num = $this->new_extend->getInfo('comp_extend_field',array(
    			'line_id' 	=> $line_id,
    			'comp_id'	=> $comp_id,
    			'user_id'	=> $user_id,
    	));
    	if($now_field_num && is_array($now_field_num) && count($now_field_num) >= $this->settings['new_extend']['extend_field_max_num'])
    	{
    		$this->errorOutput(MAX_FIELD_NUM);
    	}
    	 
    	//多行情况下，左边只能添加1个
    	$line_info = $this->new_extend->detail('new_extend_line', array(
    			'id'	=> $line_id,
    	));
    	if($line_info['line_type'] == $this->settings['new_extend']['line_type']['much'] && $field_position == $this->settings['new_extend']['field_position']['left'])
    	{
    		//获取当前行下居左的field的数量
    		$temp_left_fields = $this->new_extend->getInfo('new_extend_field',array(
    				'comp_id'	=> $comp_id,
    				'line_id'	=> $line_id,
    				'field_position'	=> $this->settings['new_extend']['field_position']['left'],
    				'user_id'	=> $user_id,
    		));
    		if(count($temp_left_fields) >= $this->settings['new_extend']['much_lines_left_field_num'])
    		{
    			$this->errorOutput(MUCH_LINE_LEFT_MAX_NUM);
    		}
    	}
    	 
    	//添加
    	$add_arr = array(
    			'field_type'		=> $field_type,
    			'comp_id'			=> $comp_id,
    			'user_id'			=> $user_id,
    			'line_id'			=> $line_id,
    			'uniqueid'			=> $uniqueid,
    			'field_position'	=> $field_position,
    			'create_time'		=> TIMENOW,
    			'uni_name'			=> $uni_name,
    			'style_type'		=> 1,
    	);
    	$new_field = $this->new_extend->create('comp_extend_field', $add_arr);
    	$anthor_new_field = $this->new_extend->detail('comp_extend_field', array(
    			'comp_id' 	=> $comp_id,
    			'uniqueid'  => $uniqueid,
    			'line_id'	=> $line_id,
    	));
    	if($anthor_new_field['id'] == $new_field['id'])
    	{
    		$insert_field_id = $new_field['id'];
    	}
    	else
    	{
    		$insert_field_id = $anthor_new_field['id'];
    	}
    	 
    	//更新orderid
    	$up_order_arr = array(
    			'id'		=> $insert_field_id,
    			'comp_id'	=> $comp_id,
    			'line_id'	=> $line_id,
    			'user_id'	=> $user_id,
    	);
    	$this->new_extend->update('comp_extend_field', array('order_id' => $insert_field_id), $up_order_arr);
    	/*********添加默认属性*********/
    	$role_id = intval($this->input['role_id']);
    	$ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$this->settings['new_extend']['new_extend_list_ui']. "' AND is_extend = 1 ");
    	//获取对应的前端属性
    	$attrData = $this->mode->getFrontExtendFieldAttributeData(0,$line_id,$comp_id,$role_id,$ui_info['id']);
    	$frontAttr = array();
    	$groupData = $this->attr_mode->getFrontGroupData();
    	foreach ($groupData as $_k => $_v)
    	{
    		foreach ($attrData as $__k => $__v)
    		{
    			if($__v['group_id'] == $_v['id'] && $_v['uniqueid'] == $this->settings['new_extend']['new_extent_field_group_uniqueid'])
    			{
    				$frontAttr[] = $__v;
    			}
    		}
    	}
    	//添加进去
    	//处理数据
    	$set_type = 'create';
    	$attr_value = array();
    	foreach ($frontAttr as $_k => $_v)
    	{
    		$attr_value[$_v['id']] = $_v['attr_default_value'];
    	}
    	$ids = array_keys($attr_value);
    	$attr_data = $this->attr_mode->getFrontAttrByIds($ids);
    	$this->setExtendFieldAttr($attr_data,$attr_value,$insert_field_id,$comp_id,$line_id,$user_id,$set_type);
    	/*********添加默认属性end*********/  	 
    	$this->addItem(array('field_id'	=> $new_field['id']));
    	$this->output();
    }
    
   
    
    /**
     * 扩展单元排序
     */
    public function extendFieldOrder()
    {
    	$comp_id = intval($this->input['comp_id']);
    	$line_id = intval($this->input['line_id']);
    	$user_id = intval($this->user['user_id']);
    	$left_ids = trim($this->input['left_ids']);
    	$right_ids = trim($this->input['right_ids']);
    	//先获取当下行内所有的单元asc
    	$all_fields_asc = $this->mode->getAllFields($comp_id,$line_id,'asc');
    	//先获取当下行内所有的单元desc
    	$all_fields_desc = $this->mode->getAllFields($comp_id,$line_id,'desc');
    	if($left_ids)
    	{
    		$left_arr = explode(',', $left_ids);
    		foreach ($left_arr as $k => $v)
    		{
    			$left_data = array(
    					'order_id'			=> $all_fields_asc[$k]['order_id'],
    					'field_position'	=> 1,
    			);
    			$left_condition = array(
    					'id'		=> $v,
    					'line_id'	=> $line_id,
    					'comp_id'	=> $comp_id,
    			);
    			$this->new_extend->update('comp_extend_field', $left_data, $left_condition);
    		}
    	}
    	if ($right_ids)
    	{
    		$right_arr = explode(',', $right_ids);
    		foreach ($right_arr as $_k => $_v)
    		{
    			$right_data = array(
    					'order_id'			=>	$all_fields_desc[$_k]['order_id'],
    					'field_position'	=> 2,
    			);
    			$right_condition = array(
    					'id'		=> $_v,
    					'line_id'	=> $line_id,
    					'comp_id'	=> $comp_id,
    			);
    			$this->new_extend->update('comp_extend_field', $right_data, $right_condition);
    		}
    	}
    	$this->addItem(array('return' => 1));
    	$this->output();
    }
    
    /**
     * 获取扩展单元的信息
     */
    public function getNewExtendFieldInfo()
    {
    	$comp_id = intval($this->input['comp_id']);
    	$line_id = intval($this->input['line_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMP_ID);
    	}
    	if(!$line_id)
    	{
    		$this->errorOutput(NO_EXTEND_LINE_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$role_id = intval($this->input['role_id']);
    	$field_id = intval($this->input['field_id']);
    	 
    	$ui_info = $this->ui_mode->detail(''," AND uniqueid = '" .$this->settings['new_extend']['new_extend_list_ui']. "' AND is_extend = 1 ");
    	//获取对应的前端属性
    	$attrData = $this->mode->getFrontExtendFieldAttributeData($field_id,$line_id,$comp_id,$role_id,$ui_info['id']);
    	$frontAttr = array();
    	$groupData = $this->attr_mode->getFrontGroupData();
    	foreach ($groupData as $_k => $_v)
    	{
    		foreach ($attrData as $__k => $__v)
    		{
    			if($__v['group_id'] == $_v['id'] && $_v['uniqueid'] == $this->settings['new_extend']['new_extent_field_group_uniqueid'])
    			{
    				$frontAttr[] = $__v;
    			}
    		}
    	}
    	$info['attr'] = $frontAttr;
    	//如果field_id存在 获取对应的field的info
    	$field_info = array();
    	if($field_id)
    	{
    		$field_info = $this->new_extend->detail('comp_extend_field', array(
    				'id' 		=> $field_id,
    				'comp_id' 	=> $comp_id,
    				'user_id' 	=> $user_id,
    				'line_id'	=> $line_id,
    			)
    		);
    	}
    	$info['field_info'] = $field_info;
    	$this->addItem($info);
    	$this->output();
    }
    
    
    /**
     * 保存扩展单元其实属性
     */
    public function setNewExtendFieldInfo()
    {
    	$comp_id = intval($this->input['comp_id']);
    	$line_id = intval($this->input['line_id']);
    	$user_id = intval($this->user['user_id']);
    	$img_info = $this->input['img_info'];
    	$style_type = intval($this->input['style_type']);
    	$field_id = intval($this->input['field_id']);
    	$attr_value = $this->input['attr_value'];
    	 
    	//先更新field的基本信息
    	$idsArr = array(
    			'comp_id'	=> $comp_id,
    			'line_id'	=> $line_id,
    			'user_id'	=> $user_id,
    			'id'		=> $field_id,
    	);
    	$update_arr = array(
    			'style_type'	=> $style_type,
    	);
    	$this->new_extend->update('comp_extend_field', $update_arr, $idsArr);
    	//更新或者插入属性
    	//先要判断当前是否有插入过属性
    	$temp = $this->new_extend->getInfo('comp_extend_field_ui_attr_value',array(
    			'line_id'	=>	$line_id,
    			'field_id'	=>	$field_id,
    			'comp_id'	=>	$comp_id,
    			'user_id'	=>	$user_id,
    	));
    	$set_type = '';
    	if($temp)//已经插入属性，update
    	{
    		$set_type = 'update';
    	}
    	else//还未插入，insert
    	{
    		$set_type = 'create';
    	}
    	 
    	$ids = array_keys($attr_value);
    	$attr_data = $this->attr_mode->getFrontAttrByIds($ids); 	
    	$this->setExtendFieldAttr($attr_data,$attr_value,$field_id,$comp_id,$line_id,$user_id,$set_type);   	 
    	$this->addItem(array('return' => 1));
    	$this->output();
    }
    
    /**
     * deleteExtendField
     * 删除扩展单元
     */
    public function deleteExtendField()
    {
    	$line_id = intval($this->input['line_id']);
    	$comp_id = intval($this->input['comp_id']);
    	$field_id = intval($this->input['field_id']);
    	$user_id = intval($this->user['user_id']);
    	if(!$field_id)
    	{
    		$this->errorOutput(NO_FIELD_ID);
    	}
    	if(!$line_id)
    	{
    		$this->errorOutput(NO_LINE_ID);
    	}
    	if(!$comp_id)
    	{
    		$this->errorOutput(NO_COMP_ID);
    	}
    	if(!$user_id)
    	{
    		$this->errorOutput(NO_LOGIN);
    	}
    	$delete_arr = array(
    			'comp_id'	=> $comp_id,
    			'line_id'	=> $line_id,
    			'id'		=> $field_id,
    			'user_id'	=> $user_id,
    	);
    	//先删除comp_extend_field
    	$this->new_extend->delete('comp_extend_field', $delete_arr);
    	 
    	//然后删comp_extend_field_attr_value
    	$delete_arr_value = array(
    			'line_id'	=> $line_id,
    			'field_id'	=> $field_id,
    	);
    	$this->new_extend->delete('comp_extend_field_attr_value', $delete_arr_value);
    	 
    	//最后删comp_extend_field_ui_attr_value
    	$this->new_extend->delete('comp_extend_field_ui_attr_value', $delete_arr_value);
    	 
    	$this->addItem(array('return' => 1));
    	$this->output();
    }
    
    
    
    private function setExtendFieldAttr($attr_data,$attr_value,$field_id = 0,$comp_id = 0,$line_id = 0,$user_id = 0,$set_type = '')
    {
    	if($attr_data)
    	{
    		foreach ($attr_data as $k => $v)
    		{
    			if(!isset($attr_value[$v['id']]))
    			{
    				continue;
    			}
    			$_value = '';
    			$_front_value = $attr_value[$v['id']];
    			switch ($v['attr_type_name'])
    			{
    				//取值范围
    				case 'span':$_value = trim($_front_value);break;
    				//单选
    				case 'single_choice':$_value = trim($_front_value);break;
    				//拾色器
    				case 'color_picker':$_value = trim($_front_value);break;
    			}
    			if($set_type == 'create')
    			{
    				//设置前台的值
    				$this->mode->setNewExtendFieldUiAttrValue(array(
    						'field_id' 		=> $field_id,
    						'comp_id' 		=> $comp_id,
    						'ui_attr_id' 	=> $v['id'],
    						'attr_value' 	=> $_value,
    						'user_id'		=> $user_id,
    						'line_id'		=> $line_id,
    					)
    				);
    			}
    			if($set_type == 'update')
    			{
    				$up_arr = array(
    						'attr_value' 	=> $_value,
    				);
    				$up_condition = array(
    						'field_id' 		=> $field_id,
    						'comp_id' 		=> $comp_id,
    						'ui_attr_id' 	=> $v['id'],
    						'user_id'		=> $user_id,
    						'line_id'		=> $line_id,
    				);
    				$this->new_extend->update('comp_extend_field_ui_attr_value', $up_arr , $up_condition);
    			}
    			
    			//设置完前台属性值还要根据关系设置后台属性的值
    			//对关联的后台属性统一设值
    			if($v['set_value_type'] == 1)
    			{
    				$this->mode->setNewExtendFieldAttrSameToRelate($v['id'] , $_value , $field_id , $line_id , $set_type);
    			}
    		}
    	}
    }
    
    
    
    
    /***********************组件新扩展字段end*********************************************/
    
    
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