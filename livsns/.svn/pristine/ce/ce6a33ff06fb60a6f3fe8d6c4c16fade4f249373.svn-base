<?php
/**
 * 编目管理
 */
require('./global.php');
define('MOD_UNIQUEID','catalog_set');
include_once CUR_CONF_PATH . 'lib/manage.class.php';
include_once CUR_CONF_PATH . 'lib/catalog_sort.class.php';
include_once CUR_CONF_PATH . 'core/catalog.core.php';
class catalogSetUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->manage = new manage();
		$this->catalogsort = new catalogsort();
		$this->catalogcore = new catalogcore();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{    
		$validate = $this->filter_data();      //获取提交的数据
		$app_uniqueid = $this->input['app_uniqueid'];
		$identifier = $this->input['identifier'];
		
		//验证是否是叮当分类的
		if($validate['catalog_sort'] != 'dingdone')
		{    
    		if($app_uniqueid)
    		{
    			$app_uniqueids= implode(',', $app_uniqueid);
    		}
		    //验证名称是否重复
    		$checkResult = $this->manage->verify(array('zh_name' => $validate['zh_name']));
    		if ($checkResult) $this->errorOutput(NAME_EXISTS);
    
    		//验证标识是否重复
    		$checkResult = $this->manage->verify(array('catalog_field' => $validate['catalog_field'],'identifier'=>$validate['identifier']));
    		if ($checkResult) $this->errorOutput(NAME_EXISTS);    
		}
		else
		{
		    $app_uniqueids=$app_uniqueid;
		}

		$data = array(
			'zh_name'    => $validate['zh_name'],
			'catalog_field'    => $validate['catalog_field'],
			'catalog_default'    => $validate['catalog_default'],
			'remark'    => $validate['remark'],
			'selected'    => $validate['selected'],
			'form_style' => $validate['form_style'],
			'bak'        => $validate['bak'],
			'batch'        => $validate['batch'],
			'required'        => $validate['required'],
			'catalog_sort_id' => $validate['catalog_sort_id'],
		    'datatype'        => $validate['datatype'],
		    'unit'		=>   $validate['unit'],
		    'identifier'  =>  $validate['identifier'],
		    'use_type' => $validate['use_type'],
		    'status'  => $validate['status'],		
			'user_id' =>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'org_id' => $this->user['org_id'],
			'create_time'=>TIMENOW,
			'update_user_id' => $this->user['user_id'],
			'update_user_name'=>$this->user['user_name'],
		    'update_time'=>TIMENOW,
		);
		//插入编目管理表field
		$res = $this->manage->create('field', $data);
		//$where .= ' WHERE 1 AND  id = '.$result['id'];
		//$sql = 'UPDATE ' . DB_PREFIX . 'field' . ' SET field = '.$result['id'].$where;
		//$this->db->query($sql);
		//插入编目所属应用表
		$field_id=$res['id'];//编目id
		if($app_uniqueids)
		{
			$app_arr = array('field_id'     => $field_id,
					         'app_uniqueid' => $app_uniqueids);	
			$result = $this->manage->create('app_map',$app_arr);
		}

		$this->catalogcore->cache();//更新缓存
		$this->addItem($res);
		$this->addItem($result);
		$this->output();
	}
	/**
	 *
	 * 更新编目
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		$identifier = isset($this->input['identifier']) ? intval($this->input['identifier']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$app_uniqueid = $this->input['app_uniqueid'];
		$info = $this->manage->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		$validate = array();
		if ($data['zh_name'] != $info['zh_name'])  //编目名字是否做了修改
		{
			if($data['catalog_sort'] != 'dingdone')  //不是叮当的扩展字段的进行验证
			{
			    //验证名称是否重复
    			$checkResult = $this->manage->verify(array('zh_name' => $data['zh_name']));
    			if ($checkResult) $this->errorOutput(NAME_EXISTS);
			}
    		$validate['zh_name'] = $data['zh_name'];
		    
		}
		/*
		 if ($data['catalog_field'] != $info['catalog_field'])  //验证标识是否做了修改
		 {
			//验证标识是否重复
			$checkResult = $this->manage->verify(array('catalog_field' => $data['catalog_field']));
			if ($checkResult) $this->errorOutput(NAME_EXISTS);
			$validate['catalog_field'] = $data['catalog_field'];
			}
			*/
		if ($data['catalog_field'] != $info['catalog_field'])  //禁止修改编目标识
		{
			$this->errorOutput(FORBID_UPDATE);
		}
		
		$usefiled=$this->manage->usefield_content($id);
		if($data['catalog_default'] != $info['catalog_default'])
		{
			if($data['catalog_sort'] != 'dingdone') //不是叮当的扩展字段的进行验证
			{
    			if(!$usefiled||$app_uniqueid)
    			{
    				$d_catalog_defaults=explode(',',$data['catalog_default']);
    				$d_count_defaults = count($d_catalog_defaults);
    				$i_catalog_defaults=explode(',',$info['catalog_default']);
    				$i_count_defaults = count($i_catalog_defaults);
    				if($d_count_defaults>=$i_count_defaults)
    				{
    					$tmp=array_diff($d_catalog_defaults,$i_catalog_defaults);
    					$t_count_defaults = count($tmp);
    					$count_diff = $d_count_defaults - $i_count_defaults;//计算出新增了几个选项。
    					if($t_count_defaults>$count_diff)//如果原有选项数组差集和新修改选项数组的差集数组大于新增的选项数量的话，则认为是对其它原有选项也作出修改
    					{
    						$this->errorOutput('禁止修改原有选项，否则会导致历史数据错误');
    					}
    				}
    				else
    				{
    					$this->errorOutput('禁止删除预选项，否则会导致历史数据错误');
    				}
    			}
			}
			$validate['catalog_default'] = $data['catalog_default'];
		}
		if ($data['selected'] != $info['selected'])   //验证选中值是否做了修改
		{
			$validate['selected'] = $data['selected'];
		}
		if($data['remark']!=$info['remark'])
		{
			$validate['remark'] = $data['remark'];
		}
		if ($data['bak'] != $info['bak'])   //验证冗余是否做了修改
		{
			$validate['bak'] = intval($data['bak']);
		}
	    if ($data['datatype'] != $info['datatype'])   //验证数据类型是否做了修改
		{
			$validate['datatype'] = intval($data['datatype']);
		}
	    if ($data['unit'] != $info['unit'])   //验证单位是否做了修改
		{
			$validate['unit'] = intval($data['unit']);
		}
	    if ($data['identifier'] != $info['identifier'])   //验证用户应用是否做了修改
		{
			$validate['identifier'] = intval($data['identifier']);
		}
	    if ($data['use_type'] != $info['use_type'])   //验证使用类型是否做了修改
		{
			$validate['use_type'] = intval($data['use_type']);
		}
	    if ($data['status'] != $info['status'])   //验证使用类型是否做了修改
		{
			$validate['status'] = intval($data['status']);
		}
		if($data['batch'] != $info['batch'])
		if ($data['batch'] != $info['batch']&&($data['batch'] && empty($info['batch'])||$usefiled||empty($app_uniqueid)))   //验证冗余是否做了修改
		{
			$validate['batch'] = intval($data['batch']);
		}
		elseif($data['batch'] != $info['batch']&&(empty($data['batch']) && $info['batch'])) //由批量改成单张会造成图片丢失，所以作限制。但是单张改成批量是允许的。
		{
			$this->errorOutput('正在被使用，不可将批量设置为否！');
		}
		if ($data['required'] != $info['required'])   //验证冗余是否做了修改
		{
			$validate['required'] = intval($data['required']);
		}
		if ($data['form_style'] != $info['form_style']&&($usefiled||empty($app_uniqueid)))  //验证编目样式是否做了修改
		{
			$validate['form_style'] = intval($data['form_style']);
		}
		elseif($data['form_style'] != $info['form_style'])
		{
			$this->errorOutput('已被使用,禁止更改样式类型');
		}

		if ($data['catalog_sort_id'] != $info['catalog_sort_id'])  //验证编目分类是否做了修改
		{
			$validate['catalog_sort_id'] = intval($data['catalog_sort_id']);
		}
		$app_info = $this->manage->get_sort($id);
		if(is_array($app_uniqueid))
		{
    		$app_uniqueids= implode(',', $app_uniqueid);
		}
		else 
		{
		    $app_uniqueids = $app_uniqueid;
		}
		if(!$app_uniqueids && $app_info)
		{
			$result = $this->manage->delete('app_map', array('field_id' => $id)); //更新编目管理表
		}
		elseif ($app_uniqueids && !$app_info)
		{
			$app_arr = array('field_id'     => $id,
					         'app_uniqueid' => $app_uniqueids);	
			$result = $this->manage->create('app_map',$app_arr);
		}
		elseif ($app_uniqueids && $app_info)
		{
			$app_arr = array('app_uniqueid' => isset($app_uniqueids) ? $app_uniqueids : '');
			//更新编目app_map表
			$result = $this->manage->update('app_map', $app_arr, array('field_id' => $id)); //更新编目管理表
		}

		//删除内容表
		if($app_info&&$app_uniqueid)
		{
			$delcontent=array_diff($app_info,$app_uniqueid);
		}
		elseif($app_info&&!$app_uniqueid)
		{
			$delcontent=$app_info;
		}
		if($delcontent)
		{
			foreach ($delcontent as $val)
			{
				$exp=explode('@', $val);
				$del[]=$exp[0];
			}
			$this->manage->delete('content', array('app_uniqueid' => "'".implode("','",$del)."'",'catalog_field'=>'\''.$info['catalog_field'].'\'')); //根据标识删除内容表
		}
		//删除内容表结束
		if ($validate || $result)
		{

			$validate['update_user_id'] = $this->user['user_id'];
			$validate['update_user_name'] = $this->user['user_name'];
			$validate['update_time'] = TIMENOW;

			$result = $this->manage->update('field', $validate, array('id' => $id)); //更新编目管理表
		}
		$this->catalogcore->cache();//更新缓存
		$this->addItem($result);
		$this->output();

	}


	/**
	 * 删除编目信息
	 */
	public function delete()
	{
		$ids = isset($this->input['id']) ? trim($this->input['id']) : '';
		$identifier = isset($this->input['identifier']) ? intval($this->input['identifier']) : '';
		$catalog_sort = isset($this->input['catalog_sort']) ? trim($this->input['catalog_sort']) : '';
		//根据分类标识查询分类id
		$catalog_sort_info = $this->getFieldsort($catalog_sort);
		$catalog_sort_id = $catalog_sort_info['id'] ? $catalog_sort_info['id'] : intval($this->input['catalog_sort_id']);
		if (empty($ids))
		{
			$this->errorOutput(PARAM_WRONG);
		}
		else
		{
			if($catalog_sort != 'dingdone')
			{
			    $delfield=$this->manage->usefield($ids);
    			$delfield=implode(' 、', $delfield);
    			if (!empty($delfield))
    			{
    				$this->errorOutput($delfield.',已被使用,禁止删除');
    			}
			}
		   
		}
		$this->manage->delete('app_map', array('field_id' => $ids));

		$sql = "SELECT catalog_field FROM ". DB_PREFIX . "field WHERE id in (" . $ids .")";
		$q = $this->db->query($sql);
		while($ret_catalog_filed = $this->db->fetch_array($q))
		{
			$catalog_field_del_array[] =  $ret_catalog_filed['catalog_field']; //编目标识
		}

		if($catalog_field_del_array && count($catalog_field_del_array)>0)
		{
			$catalog_field_del="'".implode("','",$catalog_field_del_array)."'";
		}

		$catalog_field_del = isset($catalog_field_del) ? $catalog_field_del :'';
		$this->manage->delete('content', array('catalog_field' => $catalog_field_del)); //根据编目标识删除编目内容表
		$result = $this->manage->delete('field', array('id' => $ids,'identifier'=>$identifier));
		$this->catalogcore->cache();//更新缓存
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 只删除应用的扩展字段
	 */
	public function delete_field()
	{
		$ids = isset($this->input['id']) ? trim($this->input['id']) : '';
		$identifier = isset($this->input['identifier']) ? intval($this->input['identifier']) : '';
		if (empty($ids))
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$result = $this->manage->delete('field', array('id' => $ids,'identifier'=>$identifier));
		$this->catalogcore->cache();//更新缓存
		$this->addItem($result);
		$this->output();
	}


    /**
     * 根据id identifer查叮当扩展字段
     * @see adminReadBase::
     */
	public function select()
	{
	    $user_id = $this->user['user_id'];
		$identifier = isset($this->input['identifier']) ? intval($this->input['identifier']) : 0;
		$sql = "SELECT f.*,s.zh_name as type_name,s.formhtml FROM " . DB_PREFIX . "field as f JOIN " . DB_PREFIX . "style as s ON f.form_style=s.id WHERE f.user_id=".$user_id." 
													AND f.identifier=".$identifier."";
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
		    //$this->catalogcore->cache();//更新缓存
			$this->addItem($row);
		}
		$this->output();
	}
	
	//编目开关
	public function display()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$switch = intval($this->input['is_on']);
		$switch = ($switch ==1) ? $switch : 0;
		$data = $this->catalogcore->display($ids,$switch,'field');
		$this->catalogcore->cache();//更新缓存
		$this->addItem($data);
		$this->output();
	}

	/**
	 * 根据分类标识查询分类信息
	 */
	private function getFieldsort($catalog_sort)
	{
	    $res = array();
	    $info = array();
	    if($catalog_sort)
	    {
	        $condition = ' AND catalog_sort="'.$catalog_sort.'"';
	        $res = $this->catalogsort->detail($condition);
	    }
	    $info[] = $res;
	    foreach ($info as $k=>$v)
	    {
	        $info = array(
	                'id' => $v['id'],
	                'catalog_sort' => $v['catalog_sort'],
	                'catalog_sort_name' => $v['catalog_sort_name'],
	        );
	    }
	    return $info;
	}
	
	public function audit()
	{
		//
	}
	public function sort()
	{
		$this->addLogs('更改编目排序', '', '', '更改编目排序');
		$content_ids = explode(',', $this->input['content_id']);
		$order_ids   = explode(',', $this->input['order_id']);
		foreach ($content_ids as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX . "field  SET order_id = '" . $order_ids[$k] . "'  WHERE id = '" . $v . "'";
			$this->db->query($sql);
		}
		$this->catalogcore->cache();//更新缓存
		$this->addItem('success');
		$this->output();
		//$this->drag_order('field', 'order_id');
	}
	public function publish()
	{
		//
	}

	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
	    $zh_name = isset($this->input['zh_name']) ? trim(urldecode($this->input['zh_name'])) : '';
		$catalog_field = isset($this->input['catalog_field']) ? trim(urldecode($this->input['catalog_field'])) : '';
		if(is_array($this->input['catalog_default'])&&!empty($this->input['catalog_default']))
		{
			$catalog_default=array_filter($this->input['catalog_default'],"clean_array_null");
		}
		else $catalog_default=$this->input['catalog_default'];
		if(is_array($this->input['selected'])&&!empty($this->input['selected']))
		{
			$selected=array_filter($this->input['selected'],"clean_array_null");
		}
		else $selected=$this->input['selected'];
		$catalog_default = $catalog_default ? trim(is_array($catalog_default)?implode(',', $catalog_default):trim(urldecode($catalog_default))) : '';
		$selected = $selected ? trim(is_array($selected)?implode(',', $selected):trim(urldecode($selected))) : '';
		$remark = isset($this->input['remark']) ? trim(urldecode($this->input['remark'])) : '';
		$form_style=isset($this->input['form_style']) ? intval($this->input['form_style']) : '0';
		$catalog_sort = isset($this->input['catalog_sort']) ? trim($this->input['catalog_sort']) : ''; //分类标识
		//根据分类标识查询分类id
		$catalog_sort_info = $this->getFieldsort($catalog_sort);
		$catalog_sort_id = $catalog_sort_info['id'] ? $catalog_sort_info['id'] : intval($this->input['catalog_sort_id']);
		
	    $datatype=isset($this->input['datatype']) ? intval($this->input['datatype']) : '0';  //叮当扩展字段数据类型
	    $unit=isset($this->input['unit']) ? trim(urldecode($this->input['unit'])) : '';      //叮当扩展字段的自定义单位
	    $identifier=isset($this->input['identifier']) ? intval($this->input['identifier']) : '0';  //确定来自不同应用的账号唯一
	    $use_type=isset($this->input['use_type']) ? intval($this->input['use_type']) : '0';  //叮当扩展字段list ui和Content ui,0是list ui
	    $status=isset($this->input['status']) ? intval($this->input['status']) : '0';  //开启折扣 开启时间显示 0关 1开
		
	    $bak = isset($this->input['bak']) ? intval($this->input['bak']) : 0;
		$batch = isset($this->input['batch']) ? intval($this->input['batch']) : 0;
		$required = isset($this->input['required']) ? intval($this->input['required']) : 0;
		$this->field_verify($zh_name, '名称',0,0,1);
		$this->field_verify($catalog_field, '标识');
		if($form_style>0)
		{
			$type=$this->manage->get_styles($form_style,'type');
			foreach ($type as $types) //去key
			{
				if($types)
				{
					if(stripos('checkbox,option,radio', $types['type'])!== false)
					{
						if(empty($catalog_default))
						{
							$this->errorOutput('当类型为多选框,单选框,下拉框,选项值必填');
						}
					}
					elseif((stripos('img,text', $types['type'])!== false)||('textarea' == $types['type']))
					{
						if(!empty($catalog_default))
						{
							$this->errorOutput('当类型为图片上传,视频上传,文本域,文本框,选项值不允许填写');
						}
					}
					if(stripos('option,radio', $types['type'])!== false)
					{
						if(count(explode(',', $selected))>1)
						{
							$this->errorOutput('当类型为单选框,下拉框, 默认值仅能填写一个');
						}
					}
					if('textarea' == $types['type']||$types['type']=='img'&&$batch)
					{
						$bak = 0;
					}

				}
			}
		}
		else
		{
			$this->errorOutput('编目样式未选择，请选择目前已经支持的样式');
		}
		if($selected&&$catalog_default)
		{
			$catalog_defaults=explode(',',$catalog_default);
			$selecteds=explode(',',$selected);
			$tmp=array_diff($selecteds,$catalog_defaults);
			if(!empty($tmp))
			{
				$this->errorOutput('选中值填写错误,必须是缺省值中所包含');
			}
		}
		
		if ($bak<0) $this->errorOutput(PARAM_WRONG);
		$data = array(
			'zh_name'    => $zh_name,
			'form_style' => $form_style,
			'catalog_field'=>$catalog_field,
			'catalog_default'=>$catalog_default,
			'remark'=>$remark,
			'selected'=>$selected,
			'bak'        => $bak,
			'batch'	=>$batch,
			'required'=>$required,
			'catalog_sort'=>$catalog_sort,
			'catalog_sort_id'=>$catalog_sort_id,
		    'datatype'=>$datatype,
		    'unit'=>$unit,
		    'identifier'=>$identifier,
		    'use_type' =>$use_type,
		    'status' =>$status
		);
		return $data;
	}

	private function field_verify($verify,$verify_name,$is_num = 1,$is_china = 1,$is_null = 1)
	{
		if(is_array($verify))
		{
			foreach ($verify as $v)
			$this->field_verify($v,$is_num,$is_china,$is_null);
		}
		elseif($is_null&&empty($verify))
		{
			$this->errorOutput($verify_name.'禁止为空字符串');
		}
		elseif($is_num&&is_numeric($verify))
		{
			$this->errorOutput($verify_name.'禁止全数字');
		}
		elseif ($is_china&&preg_match("/([\x81-\xfe][\x40-\xfe])/", $verify, $match))
		{
			$this->errorOutput($verify_name.'禁止使用或者含有汉字');
		}
		elseif(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$verify))
		{
			$this->errorOutput($verify_name.'禁止使用特殊字符,请使用字母、字母和数字组合(可用下划线连接)!');
		}
		elseif(! CheckLengthBetween($verify,1,20))
		{
			$this->errorOutput($verify_name.'合法长度为1-20字符之间!');
		}
	}

	
	private function get_form_style($id)
	{
		$sql = "SELECT zh_name as form_style_name FROM ".DB_PREFIX. "style WHERE id = " .$id;
		$data = $this->db->query_first($sql);
		return $data;
	}

	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new catalogSetUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>