<?php

require('./global.php');
define('MOD_UNIQUEID','lbs_field');
include_once CUR_CONF_PATH . 'lib/field.class.php';
class lbsfieldUpdate extends adminUpdateBase
{
	private $manage;

	public function __construct()
	{
		parent::__construct();
		$this->field = new field();

	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->field);
	}
	public function create()
	{

		$validate = $this->filter_data();      //获取提交的数据
		$sort = $this->input['sort'];

		//验证名称是否重复
		$checkResult = $this->field->verify(array('zh_name' => $validate['zh_name']));
		if ($checkResult) $this->errorOutput('名称重复');

		//验证标识是否重复
		$checkResult = $this->field->verify(array('field' => $validate['field']));
		if ($checkResult) $this->errorOutput('标识重复');

		$data = array(
			'zh_name'    => $validate['zh_name'],
			'field'    => $validate['field'],
			'field_default'    => $validate['field_default'],
			'selected'    => $validate['selected'],
			'batch'    => $validate['batch'],
			'form_style' => $validate['form_style'],
			'remark' => $validate['remark'],
			'user_id' =>$this->user['user_id'],
			'user_name'=>$this->user['user_name'],
			'org_id' => $this->user['org_id'],
			'create_time'=>TIMENOW,
			'update_user_id' => $this->user['user_id'],
			'update_user_name'=>$this->user['user_name'],
		    'update_time'=>TIMENOW,
		);

		//插入管理表field
		$result = $this->field->create('field', $data);
		//$where .= ' WHERE 1 AND  id = '.$result['id'];
		//$sql = 'UPDATE ' . DB_PREFIX . 'field' . ' SET field = '.$result['id'].$where;
		//$this->db->query($sql);
		//插入编目所属应用表
		$field_id=$result['id'];//编目id
		if($sort && is_array($sort))
		{
			foreach ($sort as $key=>$val)
			{
				$sortarr = array('field_id'     => $field_id,
					             'sort_id' => $val);
				$result = $this->field->create('fieldbind',$sortarr);
			}
		}
		elseif($sort)
		{
			$sortarr = array('field_id'     => $field_id,
					         'sort_id' => $sort);
			$result = $this->field->create('fieldbind',$sortarr);
		}

		$this->addItem($result);
		$this->output();
	}

	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$sort = $this->input['sort'];
		$info = $this->field->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		$validate = array();
		if ($data['zh_name'] != $info['zh_name'])  //名字是否做了修改
		{
			//验证名称是否重复
			$checkResult = $this->field->verify(array('zh_name' => $data['zh_name']));
			if ($checkResult) $this->errorOutput(NAME_EXISTS);
			$validate['zh_name'] = $data['zh_name'];
		}
		/*
		 if ($data['field'] != $info['field'])  //验证标识是否做了修改
		 {
			//验证标识是否重复
			$checkResult = $this->field->verify(array('field' => $data['field']));
			if ($checkResult) $this->errorOutput(NAME_EXISTS);
			$validate['field'] = $data['field'];
			}
			*/
		if ($data['field'] != $info['field'])  //禁止修改标识
		{
			$this->errorOutput(FORBID_UPDATE);
		}
		$usefiled=$this->field->usefield($id);
		if (($data['form_style'] != $info['form_style'])&&(!(empty($usefiled)||empty($sort))))  //禁止修改类型
		{
			$this->errorOutput('已被使用,禁止修改类型');
		}
		if ($data['field_default'] != $info['field_default'])   //验证缺省值是否做了修改
		{
			$validate['field_default'] = $data['field_default'];
		}
		if ($data['selected'] != $info['selected'])   //验证选中值是否做了修改
		{
			$validate['selected'] = $data['selected'];
		}
		if ($data['remark'] != $info['remark'])   //验证备注是否做了修改
		{
			$validate['remark'] = trim($data['remark']);
		}

		if ($data['batch'] != $info['batch'])   
		{
			$validate['batch'] = intval($data['batch']);
		}

		if ($data['form_style'] != $info['form_style'])  //验证样式是否做了修改
		{
			$validate['form_style'] = intval($data['form_style']);
		}

		if ($data['sort_id'] != $info['sort_id'])  //验证分类是否做了修改
		{
			$validate['sort_id'] = intval($data['sort_id']);
		}
		$sort_info = $this->field->get_fieldbind($id);

		if($sort_info)
		{
			$result = $this->field->delete('fieldbind', array('field_id' => $id)); //删除绑定表属于该id的内容
		}
		//删除内容表不属于该附加信息的sort内容
		if($sort_info&&$sort)
		{
			$delcontent=array_diff($sort_info,$sort);
		}
		elseif($sort_info&&!$sort)
		{
			$delcontent=$sort_info;
		}
		if($delcontent)
		{
			$this->field->delete('fieldcontent', array('sort_id' => implode(',', $delcontent),'field'=>'\''.$info['field'].'\'')); //根据标识删除内容表
		}
		//删除内容表不属于该附加信息的sort内容结束
		if($sort && is_array($sort))
		{
			foreach ($sort as $key=>$val)
			{
				$sortarr = array('field_id'     => $id,
					             'sort_id' => $val);
				$result = $this->field->create('fieldbind',$sortarr);
			}
		}
		elseif($sort)
		{
			$sortarr = array('field_id'     => $id,
					         'sort_id' => $sort);
			$result = $this->field->create('fieldbind',$sortarr);
		}
		if ($validate || $result)
		{

			$validate['update_user_id'] = $this->user['user_id'];
			$validate['update_user_name'] = $this->user['user_name'];
			$validate['update_time'] = TIMENOW;

			$result = $this->field->update('field', $validate, array('id' => $id)); //更新管理表
		}
		$this->addItem($result);
		$this->output();

	}


	/**
	 * 删除信息
	 */
	public function delete()
	{
		$ids = isset($this->input['id']) ? trim($this->input['id']) : '';
		if (empty($ids))
		{
			$this->errorOutput('参数错误');
		}
		else
		{
			$delfield=$this->field->usefield($ids);
			$delfield=implode(' 、', $delfield);
			if (!empty($delfield))
			{
				$this->errorOutput($delfield.',已被使用,禁止删除');
			}
		}
		$this->field->delete('fieldbind', array('field_id' => $ids));

		$sql = "SELECT field FROM ". DB_PREFIX . "field WHERE id in (" . $ids .")";
		$q = $this->db->query($sql);
		while($ret = $this->db->fetch_array($q))
		{
			$field[] =  $ret['field']; //标识
		}

		if($field && count($field)>0)
		{
			$field_del="'".implode("','",$field)."'";
		}

		$field_del = isset($field_del) ? $field_del :'';
		if($field_del)
		{
			$this->field->delete('fieldcontent', array('field' => $field_del)); //根据标识删除内容表
		}
		$result = $this->field->delete('field', array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	//附加信息开关
	public function display()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$switch = intval($this->input['is_on']);
		$switch = ($switch ==1) ? $switch : 0;
		$data = $this->field->display($ids,$switch);
		$this->addItem($data);
		$this->output();
	}
	public function audit()
	{
		//
	}
	public function sort()
	{	
		$this->addLogs('更改lbs附加信息排序', '', '', '更改lbs附加信息排序');
		$ret = $this->drag_order('field', 'order_id');
		$this->addItem($ret);
		$this->output();
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
		$form_style = isset($this->input['form_style']) ? intval($this->input['form_style']) : '0';
		$field = isset($this->input['field']) ? trim(urldecode($this->input['field'])) : '';
		if(is_array($this->input['field_default'])&&!empty($this->input['field_default']))
		{
			$field_default=array_filter($this->input['field_default'],"clean_array_null");
		}
		else $field_default=$this->input['field_default'];
		if(is_array($this->input['selected'])&&!empty($this->input['selected']))
		{
			$selected=array_filter($this->input['selected'],"clean_array_null");
		}
		else $selected=$this->input['selected'];
		$field_default = $field_default ? trim(is_array($field_default)?implode(',', $field_default):trim(urldecode($field_default))) : '';
		$selected = $selected ? trim(is_array($selected)?implode(',', $selected):trim(urldecode($selected))) : '';
		$remark = isset($this->input['remark']) ? trim(urldecode($this->input['remark'])) : '';
		$batch = isset($this->input['batch']) ? intval($this->input['batch']) : 0;
		
		if(empty($field))
		{
			$this->errorOutput('标识禁止为空值');
		}
		elseif(is_numeric($field))
		{
			$this->errorOutput('标识禁止全数字');
		}
		elseif (preg_match("/([\x81-\xfe][\x40-\xfe])/", $field, $match))
		{
			$this->errorOutput('标识禁止使用或者含有汉字');
		}
 
		if(!empty($form_style))
		{
			$type=$this->field->get_styles($form_style,'datatype');
			foreach ($type as $types) //去key
			{
				if($types)
				{
					if(stripos('checkbox,option,radio', $types['datatype'])!== false)
					{
						if(empty($field_default))
						{
							$this->errorOutput('当类型为多选框,单选框,下拉框, 选项必填');
						}
					}
					elseif(stripos('img,text,textarea,phone', $types['datatype'])!== false)
					{
						if(!empty($field_default))
						{
							$this->errorOutput('当类型为文件上传,文本域,文本框,号码,选项值不允许填写');
						}
					}
					if(stripos('option,radio', $types['datatype'])!== false)
					{
						if(count(explode(',', $selected))>1)
						{
							$this->errorOutput('当类型为单选框,下拉框, 默认值仅填写一个');
						}
					}
					if((stripos('img,video', $types['datatype'])=== false)&&$batch)
					{
							$this->errorOutput('当类型不为图片或者视频,禁止选择支持批量');
					}
						
				}
			}
		}
		else {
			$this->errorOutput('数据类型为必选');
		}
		//验证选中值是否填写正确.
		if($selected&&$field_default)
		{
			$selecteds=explode(',',$selected);
			$field_defaults=explode(',',$field_default);
			$tmp=array_diff($selecteds,$field_defaults);
			if(!empty($tmp))
			{
				$this->errorOutput('默认值填写错误,必须是选项中所包含');
			}
		}
		if (empty($zh_name) || $form_style < 0 || $bak<0) $this->errorOutput(PARAM_WRONG);
		$data = array(
			'zh_name'    => $zh_name,
			'form_style' => $form_style,
			'field'=>$field,
			'field_default'=>$field_default,
			'selected'=>$selected,
			'batch'=>$batch,
			'remark' => $remark,
		);
		return $data;
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

$out = new lbsfieldUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>