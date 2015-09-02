<?php
require('global.php');
define('MOD_UNIQUEID','configApp');//模块标识
class configAppUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->configApp = new configApp();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据
		//验证key是否重复
		if ($this->configApp->count(array('app_uniqueid' => $data['app_uniqueid']))){
			$this->errorOutput('应用标识重复');
		}
		if($data)
		{
			$result = $this->configApp->create($data);
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

		$info = $this->configApp->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['app_uniqueid']!=$info['app_uniqueid']&&$data['app_uniqueid'])
		{
			$this->errorOutput('禁止修改标识');
		}
		if ($data)
		{
			$result = $this->configApp->update($data, array('id' => intval($id)));
		}
		$this->addItem($result);
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
		//验证 是系统禁止删除。
		$mySetInfo = $this->configApp->show(array('id'=>$id_array), 0, 0,'id,app_uniqueid');
		if (empty($mySetInfo))
		{
			$this->errorOutput('待删除的应用配置不存在');
		}
		else {
			$delete_id= array();
			foreach ($mySetInfo as $val)
			{
				$delete_id[] = $val['id'];
				$app_uniqueid[] = $val['app_uniqueid'];
			}
			if($delete_id)
			{
				$this->deleteVerify($app_uniqueid);
				$this->configApp->delete(array('id'=>$delete_id));
			}
		}
		$this->addItem($id);
		$this->output();
	}
	/**
	 * 
	 * 删除验证是否被使用等 ...
	 * @param unknown_type $delete_id
	 */
	private function deleteVerify($app_uniqueid)
	{
		$settingRelation = new settingRelation();
		if($settingRelation->count(array('app_uniqueid'=>$app_uniqueid)))
		{
			$this->errorOutput('待删除的应用已被配置分类绑定,请解绑后操作');
		}
	}

	public function audit()
	{
		//
	}
	public function sort()
	{
		$this->addLogs('更改系统设置配置应用排序', '', '', '更改系统设置配置应用排序');
		$ret = $this->drag_order('appconfig', 'order_id');
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
		$appname = isset($this->input['appname']) ? trim(urldecode($this->input['appname'])) : '';
		$app_uniqueid = isset($this->input['app_uniqueid']) ? trim(urldecode($this->input['app_uniqueid'])) : '';
		$callurl = isset($this->input['callurl']) ? trim(urldecode($this->input['callurl'])) : '';
		$brief = isset($this->input['brief']) ? trim(urldecode($this->input['brief'])) : '';
		if (empty($appname)) $this->errorOutput('名称不能为空');
		if (empty($app_uniqueid)) $this->errorOutput('标识不能为空');
		if(is_numeric($app_uniqueid))
		{
			$this->errorOutput('标识禁止全数字');
		}
		elseif (preg_match("/([\x81-\xfe][\x40-\xfe])/", $app_uniqueid, $match))
		{
			$this->errorOutput('标识禁止使用汉字');
		}
		elseif(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$app_uniqueid))
		{
			$this->errorOutput('标识禁止使用特殊字符,请使用字母、字母和数字组合(可用下划线连接)!');
		}
		if (empty($callurl)) $this->errorOutput('调用地址不能为空');
		
			    //参数处理
		if($this->input['argument_name'] || $this->input['ident'] || $this->input['value'])
		{
			if(is_array($this->input['argument_name']))
			{
				foreach($this->input['argument_name'] as $k=>$v)
				{
					$argument['argument_name'][$k] = urldecode($this->input['argument_name'][$k]);
				}
			}
			
			$argument['ident'] = $this->input['ident'];
			$argument['argument_type'] = $this->input['argument_type'];
			if(is_array($this->input['value']))
			{
				foreach($this->input['value'] as $k=>$v)
				{
					$argument['value'][$k] = urldecode($this->input['value'][$k]);
				}
			}
			
			$argument = serialize($argument);
		}
		$argument = $argument ? $argument : '';
		
		$data = array(
			'appname'    => $appname,
			'app_uniqueid' => $app_uniqueid,
			'brief' => $brief,
			'callurl' => $callurl,
			'argument' => $argument,
		);
		return $data;
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new configAppUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>