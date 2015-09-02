<?php
require('global.php');
define('MOD_UNIQUEID','configSet');//模块标识
class configSet_Update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->configSet = new configSet();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据
		//验证key是否重复
		if ($this->configSet->count(array('groupmark'=>$data['groupmark'],'setname' => $data['setname']))){
			$this->errorOutput('标识重复');
		}
		if($data)
		{
			$result = $this->configSet->create($data);
			if($result)
			{
				$this->configSet->setCount($data['groupmark']);
				$this->configSet->updateConfigAfterProcess(array($result['id']));
			}
		}
		$this->addItem($result);
		$this->output();
	}

	/**
	 *
	 * 更新
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);

		$info = $this->configSet->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['setname']!=$info['setname']&&$data['setname'])
		{
			$this->errorOutput('禁止修改标识');
		}
		if ($data)
		{
			$result = $this->configSet->update($data, array('id' => intval($id)));
			if($result)
			{
				$this->configSet->setCount($data['groupmark']);
				$this->configSet->setCount($info['groupmark']);
				$this->configSet->updateConfigAfterProcess(array($id));
			}
		}
		$this->addItem($result);
		$this->output();

	}
	/**
	 *
	 * 配置列表更新函数...
	 */
	public function updatelistset()
	{
		$reUpdate = array();
		$settings = isset($this->input['settings'])?$this->input['settings']:array();
		if($_files = fileRever($_FILES['settings']))
		{
			foreach ($_files as $id => $img_file)
			{
				$settings[$id] = $this->configSet->img_upload($img_file);
			}
		}
		if(is_array($settings)&&$settings)
		{
			$settingsId = array_keys($settings);
			$settingsInfo = $this->configSet->show(array('id'=>$settingsId), 0, 0,'id','id','',array(),0);
			if($settingsInfo)
			{
				$reUpdate =  $this->configSet->updatelist($settings,$settingsInfo);
				if($reUpdate)
				{
					$this->configSet->updateConfigAfterProcess(array_keys($reUpdate));
				}
			}
		}
		$this->addItem($reUpdate);
		$this->output();
	}


	public function delete()
	{
		$id = trim($this->input['id']);
		$ids = str_replace('，' , ',' , $id);
		$id_array = explode(',' , $ids);
		//过滤数组中的空值
		$id_array=array_filter($id_array);
		if(empty($id_array)) return false;
		$mySetInfo = $this->configSet->show(array('id'=>$id_array), 0, 0,'id');
		if (empty($mySetInfo))
		{
			$this->errorOutput('待删除配置不存在');
		}
		else {
			$delete_id= array();
			foreach ($mySetInfo as $val)
			{
				$delete_id[]=$val['id'];
			}
			if($delete_id)
			{
				$this->configSet->delete(array('id'=>$delete_id));
				$this->configSet->updateConfigAfterProcess($delete_id);
			}
		}
		$this->addItem($id);
		$this->output();
	}

	public function restoreset()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$info = $this->configSet->detail($id,'defaultvalue as value');
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$result = $this->configSet->update($info, array('id' => intval($id)));
		if($result)
		{
			$this->configSet->updateConfigAfterProcess(array($id));
		}
		$this->addItem($result);
		$this->output();
	}

	public function audit()
	{
		//
	}
	public function sort()
	{
		$this->addLogs('更改系统设置配置应用排序', '', '', '更改系统设置配置应用排序');
		$ret = $this->drag_order('setting', 'order_id','id',1);
		if($ret)
		{
			$this->configSet->updateConfigAfterProcess($ret);
		}
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
		$settitle = isset($this->input['settitle']) ? trim(urldecode($this->input['settitle'])) : '';
		$setname = isset($this->input['setname']) ? trim(urldecode($this->input['setname'])) : '';
		$description = isset($this->input['description']) ? trim(urldecode($this->input['description'])) : '';
		$groupmark = isset($this->input['groupmark']) ? trim(urldecode($this->input['groupmark'])) : '';
		$type = isset($this->input['type']) ? trim(urldecode($this->input['type'])) : '';
		$dropextra = isset($this->input['dropextra']) ? array_filter($this->input['dropextra'],"clean_array_null") : array();
		$defaultvalue = isset($this->input['defaultvalue']) ? trim(urldecode($this->input['defaultvalue'])) : '';
		$value = isset($this->input['value']) ? trim(urldecode($this->input['value'])) : '';
		$islimits = isset($this->input['islimits']) ? intval($this->input['islimits']) : 0;
		if($islimits)
		{
			$limitapps = isset($this->input['limitapps']) ? $this->input['limitapps'] : array();
		}
		else $limitapps = array();
		if($value===""&&$defaultvalue!=="")
		{
			$value = $defaultvalue;
		}
		if (empty($settitle)) $this->errorOutput('名称不能为空');
		if (empty($setname)) $this->errorOutput('配置标识不能为空');
		if(is_numeric($setname))
		{
			$this->errorOutput('标识禁止全数字');
		}
		elseif (preg_match("/([\x81-\xfe][\x40-\xfe])/", $setname, $match))
		{
			$this->errorOutput('标识禁止使用汉字');
		}
		elseif(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$setname))
		{
			$this->errorOutput('标识禁止使用特殊字符,请使用字母、字母和数字组合(可用下划线连接)!');
		}
		if (empty($groupmark)) $this->errorOutput('请选择需要绑定的分类');
		if (empty($type)) $this->errorOutput('配置类型不能为空');

		if($type)
		{
			if(in_array($type, array('option','radio')))
			{
				if(empty($dropextra))
				{
					$this->errorOutput('当配置类型为单选按钮,下拉列表,预选项为必填');
				}
			}
			elseif(in_array($type, array('text','textarea')))
			{
				if(!empty($dropextra))
				{
					$this->errorOutput('当类型为文本域,输入框,预选项值不允许填写');
				}
			}
			if(in_array($type, array('option','radio')))
			{
				if(count(explode("\n", $defaultvalue))>1)
				{
					$this->errorOutput('当类型为单选框,下拉框,默认值仅能填写一个');
				}
			}
		}
		if($dropextra&&is_array($dropextra))
		{
			foreach ($dropextra as $v)
			{
				$_exp = array();
				$_exp = explode('==',$v);
				$_dropextra[] =$_exp[0];
			}
			if($defaultvalue)
			{
				$_defaultvalue=explode("\n",$defaultvalue);
				$no_default = array();
				$no_default = array_diff($_defaultvalue,$_dropextra);
				if($no_default)
				{
					$this->errorOutput('默认值填写错误,必须是预选项值中所包含');
				}
			}
			elseif ($value)
			{
				$_value=explode("\n",$value);
				$no_default = array();
				$no_default = array_diff($_value,$_dropextra);
				if($no_default)
				{
					$this->errorOutput('当前值填写错误,必须是预选项值中所包含');
				}
			}
		}

		$data = array(
			'settitle'    => $settitle,
			'groupmark' => $groupmark,
			'limitapps' => $limitapps?implode("\n", $limitapps):'',
			'setname' => $setname,
			'description' => $description,
			'type' => $type,
			'value' => $value,
			'defaultvalue'=>$defaultvalue,
			'dropextra'=>implode("\n", $dropextra),
		);
		return $data;
	}



	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new configSet_Update();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>