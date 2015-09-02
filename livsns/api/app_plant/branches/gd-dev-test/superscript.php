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
 * @description 角标接口
 **************************************************************************/
define('MOD_UNIQUEID','superscript');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/superscript_mode.php');
require_once(CUR_CONF_PATH . 'lib/UpYunOp.class.php');

class superscript extends outerUpdateBase
{
    private $mode;
    private $_upYunOp;
    public function __construct()
    {
        parent::__construct();
        $this->mode     = new superscript_mode();
        $this->_upYunOp = new UpYunOp();
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
    
    //创建一个角标
    public function create()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        /***********************************角标相关参数***************************************/
        //角标名称
        $name = trim($this->input['name']);
        if(!$name)
        {
            $this->errorOutput(NO_SUPERSCRIPT_NAME);
        }
        
        //接受用户传过来图片，可能是系统的图标（图片名称），也有可能是用户自己上传的（图片的ID）
	    $img_info = $this->input['img_info'];
	    if(!$img_info)
	    {
	        $this->errorOutput(NO_SELECT_IMG);
	    }
	    
	    //标识图片选择方式1：系统 2：自定义
	    $img_type = intval($this->input['img_type']);
	    if(!$img_type || !in_array($img_type, array(1,2)))
	    {
	        $this->errorOutput(NO_SELECT_IMG_TYPE);
	    }
        
        //满足条件的方式
	    $cond_type = intval($this->input['cond_type']);
	    if(!$cond_type)
	    {
	        $this->errorOutput(NO_COND_TYPE);
	    }
	    
	    //是否显示文字
	    $is_show_text = intval($this->input['is_show_text']);
	    
	    //显示的文字与文字的字段类型
	    $text       = $this->input['text'];//自定义显示的文字
	    $field_type = $this->input['field_type'];//字段类型
	    
	    //准备好数据
	    $data = array(
	        'name'     	    => $name,
	        'cond_type'     => $cond_type,
	        'img_type'      => $img_type,
	        'is_show_text'  => $is_show_text,
	        'field_type'    => $field_type,
	        'text'          => $text,
	        'user_id'       => $user_id,
	        'user_name'     => $this->user['user_name'],
	        'create_time'   => TIMENOW,
	        'update_time'   => TIMENOW,
	    );
	    
	    //系统图标
	    if($img_type == 1)
	    {
	        $data['img_info'] = $img_info;
	    }
	    elseif($img_type == 2)//用户自定义上传
	    {
	        $img = $this->_upYunOp->getPicInfoById(''," AND id = '" .intval($img_info). "' AND user_id = '" .$user_id. "' ");
            if($img && isset($img[0]))
            {
                $img = $img[0];
                $img_arr = array(
                    'id'		=> $img['id'],
					'host' 		=> $img['host'],
					'dir' 		=> $img['dir'],
					'filepath' 	=> $img['filepath'],
					'filename' 	=> $img['filename'],	
					'imgwidth'	=> $img['imgwidth'],
					'imgheight'	=> $img['imgheight'],
                );
                $data['img_info'] = addslashes(serialize($img_arr));
            }
            else 
            {
                $this->errorOutput(IMG_ERROR);
            }
	    }
	    /***********************************角标相关参数***************************************/
	    
	    //开始创建角标
	    $superscript_id = $this->mode->create($data);
	    if($superscript_id)
	    {
	        //角标创建成功之后就需要保存条件
    	    $cond_key_arr    = $this->input['cond_key'];//这是一个数组
    	    $filter_type_arr = $this->input['filter_type'];//过滤条件数据
    	    $cond_value_arr  = $this->input['cond_value'];//条件的值
    	    
    	    //如果有date类型，对时间处理，转换成时间戳
//     	    if(in_array('date1', $cond_key_arr))
//     	    {
//     	    	$keys_array = array_keys($cond_key_arr,'date1');
//     	    	foreach ($cond_value_arr as $kk => &$vv)
//     	    	{
//     	    		if(in_array($kk, $keys_array))
//     	    		{
//     	    			$time_arr = explode(',', $vv);
//     	    			$start_time = strtotime($time_arr[0]);
//     	    			$end_time = strtotime($time_arr[1])+24*3600;
//     	    			$vv = $start_time.",".$end_time;
//     	    		}
//     	    	}
//     	    }
    	    
    	    if($cond_key_arr && is_array($cond_key_arr))
    	    {
    	        foreach($cond_key_arr AS $k => $v)
    	        {
    	            $this->mode->createSuperScriptCond(array(
    	                        'superscript_id'  => $superscript_id,
    	                        'cond_key'	      => $v,
    	                        'filter_type'     => $filter_type_arr[$k],
    	                        'cond_value'      => $cond_value_arr[$k],
    	                        'user_id'         => $user_id,
                    	        'user_name'  	  => $this->user['user_name'],
                    	        'create_time'     => TIMENOW,
                    	        'update_time'     => TIMENOW,
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
    
    //编辑某个角标
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
        
        //首先判断改角标是不是这个人的
        $superscript_info = $this->mode->detail(''," AND id = '" .$id. "' AND user_id = '" .$user_id. "' ");
        if(!$superscript_info)
        {
            $this->errorOutput(SUPERSCRIPT_ID_FEI_FA);
        }
        
        /***********************************角标相关参数***************************************/
        //角标名称
        $name = trim($this->input['name']);
        if(!$name)
        {
            $this->errorOutput(NO_SUPERSCRIPT_NAME);
        }
        
        //接受用户传过来图片，可能是系统的图标（图片名称），也有可能是用户自己上传的（图片的ID）
	    $img_info = $this->input['img_info'];
	    if(!$img_info)
	    {
	        $this->errorOutput(NO_SELECT_IMG);
	    }
	    
	    //标识图片选择方式1：系统 2：自定义
	    $img_type = intval($this->input['img_type']);
	    if(!$img_type || !in_array($img_type, array(1,2)))
	    {
	        $this->errorOutput(NO_SELECT_IMG_TYPE);
	    }
        
        //满足条件的方式
	    $cond_type = intval($this->input['cond_type']);
	    if(!$cond_type)
	    {
	        $this->errorOutput(NO_COND_TYPE);
	    }
	    
	    //是否显示文字
	    $is_show_text = intval($this->input['is_show_text']);
	    
	    //显示的文字与文字的字段类型
	    $text       = $this->input['text'];//自定义显示的文字
	    $field_type = $this->input['field_type'];//字段类型
	    
	    //准备好数据
	    $data = array(
	        'name'     	    => $name,
	        'cond_type'     => $cond_type,
	        'img_type'      => $img_type,
	        'is_show_text'  => $is_show_text,
	        'field_type'    => $field_type,
	        'text'          => $text,
	        'update_time'   => TIMENOW,
	    );
	    
        //系统图标
	    if($img_type == 1)
	    {
	        $data['img_info'] = $img_info;
	    }
	    elseif($img_type == 2)//用户自定义上传
	    {
	        $img = $this->_upYunOp->getPicInfoById(''," AND id = '" .intval($img_info). "' AND user_id = '" .$user_id. "' ");
            if($img && isset($img[0]))
            {
                $img = $img[0];
                $img_arr = array(
                    'id'		=> $img['id'],
					'host' 		=> $img['host'],
					'dir' 		=> $img['dir'],
					'filepath' 	=> $img['filepath'],
					'filename' 	=> $img['filename'],	
					'imgwidth'	=> $img['imgwidth'],
					'imgheight'	=> $img['imgheight'],
                );
                $data['img_info'] = addslashes(serialize($img_arr));
            }
	        else
            {
                $this->errorOutput(IMG_ERROR);
            }
	    }
	    /***********************************角标相关参数***************************************/
	    
	    //开始创建角标
	    $update_ret = $this->mode->update($id,$data);
	    if($update_ret)
	    {
	        //首先删除原来这个角标对应的条件
	        $this->mode->deleteSuperscriptCond(" AND superscript_id = '" .$id. "' ");
	        //角标创建成功之后就需要保存条件
    	    $cond_key_arr    = $this->input['cond_key'];//这是一个数组
    	    $filter_type_arr = $this->input['filter_type'];//过滤条件数据
    	    $cond_value_arr  = $this->input['cond_value'];//条件的值
    	    
    	    if($cond_key_arr && is_array($cond_key_arr))
    	    {
    	        foreach($cond_key_arr AS $k => $v)
    	        {
    	            $this->mode->createSuperScriptCond(array(
    	                        'superscript_id'  => $id,
    	                        'cond_key'	      => $v,
    	                        'filter_type'     => $filter_type_arr[$k],
    	                        'cond_value'      => $cond_value_arr[$k],
    	                        'user_id'         => $user_id,
                    	        'user_name'  	  => $this->user['user_name'],
                    	        'create_time'     => TIMENOW,
                    	        'update_time'     => TIMENOW,
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
    
    //获取某个角标的详情
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
        
        //查询出这个人的角标的详情信息
        $ret = $this->mode->detail(''," AND id = '" .$id. "' AND user_id = '" .$user_id. "' ");
        if($ret)
        {
            //查询出条件
            $condArr = $this->mode->getSuperscriptByCond(" AND superscript_id = '" .$id. "' ");
            if($condArr)
            {
                $ret['cond'] = $condArr;
            }
            
            $this->addItem($ret);
            $this->output();
        }
        else 
        {
            $this->errorOutput(NO_DATA);
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
        
        //首先判断该角标是不是这个人的
        $superscript_info = $this->mode->detail(''," AND id = '" .$id. "' AND user_id = '" .$user_id. "' ");
        if(!$superscript_info)
        {
            $this->errorOutput(SUPERSCRIPT_ID_FEI_FA);
        }
        
        //看这个角标是否有在使用
        $use_info = $this->mode->cornerIsUse($id,$user_id);
        if($use_info)
        {
        	$this->errorOutput(CORNER_IS_USE);
        }
        //然后删除该角标并且删除该角标对应的条件
        $del_ret = $this->mode->delete($id);
        if($del_ret)
        {
            //删除对应的条件
            $this->mode->deleteSuperscriptCond(" AND superscript_id = '" .$id. "' ");
            $this->addItem(array('return' => 1));
            $this->output();  
        }
        else 
        {
            $this->errorOutput(FAILED);
        }
    }
    
    //设置角标的属性值
    public function set_corner_attr_value()
    {
        $user_id = intval($this->user['user_id']);
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //角标ID
        $superscript_id = intval($this->input['superscript_id']);
        if(!$superscript_id)
        {
            $this->errorOutput(NO_SUPERSCRIPT_ID);
        }
        
        //查询出角标的信息
        $corner_info = $this->mode->detail(''," AND id = '" .$superscript_id. "' AND user_id = '" .$user_id. "' ");
        if(!$corner_info)
        {
            $this->errorOutput(SUPERSCRIPT_NOT_EXIST);
        }
        
        //获取角标的前台属性
        if(!$attr_data = $this->mode->getFrontAttrByCond(" AND role_type_id = '" .intval($this->input['dingdone_role_id']). "' "))
        {
            $this->errorOutput(ATTR_NOT_EXISTS);
        }
        
        $attr_value = $this->input['corner_attr_value'];
        if($attr_value)
        {
            foreach ($attr_data AS $k => $v)
            {
                if(!isset($attr_value[$v['id']]))
                {
                    continue;
                }
                
                $_value = '';
                $_front_value = $attr_value[$v['id']];
                switch ($v['attr_type_name'])
                {
                    //文本框
	                case 'textbox':$_value = $_front_value;break;
                    //文本域
        	        case 'textfield':$_value = $_front_value;break;
        	        //单选
        	        case 'single_choice':$_value = $_front_value;break;
        	        //勾选
        	        case 'check':$_value = intval($_front_value);break;
        	        //取值范围
        	        case 'span':$_value = $_front_value;break;
        	        //图片单选
        	        case 'pic_radio':$_value = $_front_value;break;
        	        //图片上传+单选
        	        case 'pic_upload_radio':
        	                                $_img_ids = explode('|', $_front_value);
        	                                if($_img_ids)
        	                                {
            	                                 $_value = serialize(array(
            	                                         'img_ids'  => $_img_ids[1],
            	                                         'selected' => $_img_ids[0],
            	                                 ));
        	                                }
        	                                break;
        	        //多选
        	        case 'multiple_choice':$_value = $_front_value;break;
        	        //拾色器
        	        case 'color_picker':$_value = $_front_value;break;
        	        //高级拾色器
        	        case 'advanced_color_picker':
        	                                    $_color = explode('|', $_front_value);
        	                                    if($_color)
        	                                    {
        	                                        $_value = serialize(array(
        	                                                'color' => $_color[0],
        	                                                'alpha' => $_color[1],
        	                                        ));
        	                                    }
        	                                    break;
        	        //配色方案
        	        case 'color_schemes':$_value = $_front_value;break;
        	        //高级配色方案
        	        case 'advanced_color_schemes':
        	                                    $_color = explode('|', $_front_value);
        	                                    if($_color)
        	                                    {
        	                                        $_value = array();
        	                                        foreach ($_color AS $_kk => $_vv)
        	                                        {
        	                                            $_tmp = explode(':', $_vv);
        	                                            $_value[$_tmp[0]] = $_tmp[1];
        	                                        }
        	                                        $_value = serialize($_value);
        	                                    }
        	                                    break;
        	        //高级背景设置
        	        case 'advanced_background_set':
        	                                   $_bg = explode('|',$_front_value);
        	                                   if($_bg)
        	                                   {
        	                                       if($_bg[0] == 'img')
        	                                       {
                                                       $_value = serialize(array('img_id' => $_bg[1],'is_tile' => $_bg[2]));
        	                                       }
        	                                       elseif($_bg[0] == 'color')
        	                                       {
        	                                           $_value = serialize(array('color' => $_bg[1],'alpha' => $_bg[2]));
        	                                       }
        	                                   }
        	                                   break;                  
        	        //高级文字设置
        	        case 'advanced_character_set':
        	                                   $_text = explode('|',$_front_value);
        	                                   if($_text)
        	                                   {
        	                                       if($_text[0] == 'img')
        	                                       {
                                                       $_value = serialize(array('img_id' => $_text[1]));
        	                                       }
        	                                       elseif($_text[0] == 'text')
        	                                       {
        	                                           $_value = serialize(array('text' => $_text[1]));
        	                                       }
        	                                   }
        	                                   
                                               if(!$_value)
        	                                   {
        	                                       $_value = serialize(array('text' => ''));
        	                                   }
        	                                   break;                   
                }
                

                //设置前台的值
                $this->mode->setFrontCornerAttrValue(array(
                        'superscript_id'    => $superscript_id,
                        'ui_attr_id'        => $v['id'],
                        'attr_value'        => $_value,
                ));
                
                /*
                
                //设置完前台属性值还要根据关系设置后台属性的值
                
                //对关联的后台属性统一设值
                if($v['set_value_type'] == 1)
                {
                    $this->mode->setFrontCompAttrSameToRelate($v['id'],$_value,$comp_id);
                }
                elseif($v['set_value_type'] == 2)//对关联属性分别设置
                {
                    $this->mode->setFrontCompAttrEachToRelate($v['id'],$_value,$comp_id);
                }
                */
            }
        }
        
        $this->addItem(array('return' => 1));
        $this->output();
    }
    
    //获取角标对应前台的listUI的配置的值
    public function get_corner_attr_value()
    {
        $user_id = intval($this->user['user_id']);
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //角标ID
        $superscript_id = intval($this->input['superscript_id']);
        if(!$superscript_id)
        {
            $this->errorOutput(NO_SUPERSCRIPT_ID);
        }
        
        //查询出角标的信息
        $corner_info = $this->mode->detail(''," AND id = '" .$superscript_id. "' AND user_id = '" .$user_id. "' ");
        if(!$corner_info)
        {
            $this->errorOutput(SUPERSCRIPT_NOT_EXIST);
        }

        //获取该UI的属性
        $attrData = $this->mode->getFrontCornerAttrData($superscript_id,$this->input['dingdone_role_id']);
        if(!$attrData)
        {
            $this->errorOutput(NOT_EXISTS_ATTR_IN_UI);
        }
        
        $this->addItem($attrData);
        $this->output();
    }
    
    //获取某个人可用的角标
    public function getSuperscriptWithUser()
    {
        $user_id = intval($this->user['user_id']);
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $_cond = " AND user_id = '" . $this->user['user_id'] . "' ";
        $_order = " ORDER BY order_id DESC ";
        $data = $this->mode->show($_cond,$_order);
        $this->addItem($data);
        $this->output();
    }
    
    //获取某个模块使用了哪些角标，并且连带着返回角标的详细信息
    public function getUseCornerInfoByModId()
    {
        $module_id = intval($this->input['module_id']);
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        
        $data = $this->mode->getUseCornerInfoByModId($module_id);
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
    
    //用户用户自己上传的角标
    public function saveUserCornerIcon()
    {
        $user_id = intval($this->user['user_id']);
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $add_ids = $this->input['add_ids'];
        $del_ids = $this->input['del_ids'];
        
        if($add_ids)
        {
            //先判断新增的角标的id是不是已经存在了
            $existImgIdsArr = array();
            $existImgIds = $this->mode->getUserCornerIcon(" AND img_id IN (" .$add_ids. ") AND user_id = '" .$user_id. "' ",'img_id','img_id');
            if($existImgIds)
            {
                $existImgIdsArr = array_keys($existImgIds);
            }
            
            $add_ids_arr = explode(',',$add_ids);
            if($add_ids_arr)
            {
                foreach($add_ids_arr AS $k => $v)
                {
                    //先要判断
                    if(in_array($v, $existImgIdsArr))
                    {
                        continue;
                    }
                    
                    $this->mode->createUserCornerIcon(array(
                            'img_id'     => $v,
                            'user_id'    => $this->user['user_id'],
                            'user_name'  => $this->user['user_name'],
                            'create_time'=> TIMENOW,
                            'update_time'=> TIMENOW,
                    ));
                }
            }
        }
        
        //删除的部分
        if($del_ids)
        {
            //首先查看这些需要删除的图片是不是这个人的
            $delImgIds = $this->mode->getUserCornerIcon(" AND img_id IN (" .$del_ids. ") AND user_id = '" .$user_id. "' ",'img_id','img_id');
            if($delImgIds)
            {
                $delImgIdsStr = implode(',', array_keys($delImgIds));
                //开始删除
                $this->mode->deleteUserCornerIconByCond(" AND img_id IN (" .$delImgIdsStr. ") ");
            }
        }
        
        $this->addItem(array('return' => 1));
        $this->output();
    }
    
    //获取用户角标
    public function getUserCornerIcons()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $data = $this->mode->getUserCornerIcon(" AND user_id = '" .$user_id. "' ",'img_id','img_id');
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
    
    //根据模块ID获取角标的条件
    public function getCornerCondByModuleId()
    {
        $module_id = intval($this->input['module_id']);
        if(!$module_id)
        {
            $this->errorOutput(NO_MODULE_ID);
        }
        
        //首先查询该模块有没有使用角标
        $mod_corner = $this->mode->getModCornerByCond(" AND module_id = '" .$module_id. "' ");
        if(!$mod_corner)
        {
            $this->errorOutput(NO_USE_CORNER);
        }
        
        //查询出对应角标的条件，每个角标可能会有多个条件
        foreach($mod_corner AS $k => $v)
        {
            //获取对应角标的条件
            $corner_cond = $this->mode->getSuperscriptWithCondById($v['superscript_id'],'id,cond_type,is_show_text,field_type,text');
            if($corner_cond)
            {
                $mod_corner[$k]['corner_info'] = $corner_cond;
            }
            else 
            {
                $mod_corner[$k]['corner_info'] = array();
            }
        }
        
        $this->addItem($mod_corner);
        $this->output();
    }
    
    public function unkow()
    {
        $this->errorOutput(UNKNOW);
    }
}

$out = new superscript();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'unkow';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();