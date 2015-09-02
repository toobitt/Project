<?php
require('global.php');
define('MOD_UNIQUEID','configSetSort');//模块标识
class configSet_SortUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->configSetSort = new configSetSort();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据
		//验证key是否重复
		if ($this->configSetSort->count(array('groupmark' => $data['groupmark']))){
			$this->errorOutput('标识重复');
		}
		if($data)
		{
			$result = $this->configSetSort->create($data);
		}
		if($result)
		{
			$this->updateApp($data['groupmark'], $data['app_uniqueid']);
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

		$info = $this->configSetSort->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['groupmark']!=$info['groupmark']&&$data['groupmark'])
		{
			$this->errorOutput('禁止修改标识');
		}
		if ($data)
		{
			$result = $this->configSetSort->update($data, array('id' => intval($id)));
		}
		if($result)
		{
			$this->updateApp($info['groupmark'], $data['app_uniqueid']);
		}
		$this->addItem($result);
		$this->output();

	}
	private function updateApp($groupmark,$app_uniqueid)
	{		
		if(!is_array($app_uniqueid))
		{
			$this->errorOutput('您提交的应用标识数据格式有误');
		}
		$settingrelation = new settingRelation();
		$relationInfo = $settingrelation->show(array('groupmark'=>$groupmark), 0, 0,'app_uniqueid','app_uniqueid',0);
		$delApp_uniqueid = array_diff($relationInfo, $app_uniqueid);
		if($delApp_uniqueid)
		{			
			$settingrelation->delete(array('app_uniqueid'=>$delApp_uniqueid,'groupmark'=>$groupmark));
		}
		$addApp_uniqueid = array_diff($app_uniqueid,$relationInfo);
		if($addApp_uniqueid)
		{
			foreach ($addApp_uniqueid as $v)
			{
				$settingrelation->create(array('app_uniqueid'=>$v,'groupmark'=>$groupmark));
			}
		}
		return true;
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
		$mySetInfo = $this->configSetSort->show(array('id'=>$id_array), 0, 0,'id,groupmark');
		if (empty($mySetInfo))
		{
			$this->errorOutput('待删除配置分类不存在');
		}
		else {
			$delete_id= array();
			foreach ($mySetInfo as $val)
			{
				$delete_id[]=$val['id'];
				$deleteGroupMark[] = $val['groupmark'];
			}
			if($delete_id)
			{
				$this->deleteVerify($deleteGroupMark);
				$this->configSetSort->delete(array('id'=>$delete_id));
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
	private function deleteVerify($deleteGroupMark)
	{
		$configSet = new configSet();
		$settingRelation = new settingRelation();
		if($configSet->count(array('groupmark'=>$deleteGroupMark)))
		{
			$this->errorOutput('待删除的分类已被配置绑定,请解绑后操作');
		}
		else if ($settingRelation->count(array('groupmark'=>$deleteGroupMark)))
		{
			$this->errorOutput('待删除的分类已被应用绑定,请解绑后操作');
		}
	}

	public function audit()
	{
		//
	}
	public function sort()
	{
		$this->addLogs('更改系统设置配置应用排序', '', '', '更改系统设置配置应用排序');
		$ret = $this->drag_order('settinggroup', 'order_id');
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
		$grouptitle = isset($this->input['grouptitle']) ? trim(urldecode($this->input['grouptitle'])) : '';
		$groupmark = isset($this->input['groupmark']) ? trim(urldecode($this->input['groupmark'])) : '';
		$description = isset($this->input['description']) ? trim(urldecode($this->input['description'])) : '';
		$app_uniqueid = isset($this->input['app_uniqueid']) ? $this->input['app_uniqueid'] : array();

		if (empty($grouptitle)) $this->errorOutput('名称不能为空');
		if (empty($groupmark)) $this->errorOutput('标识不能为空');
		if(is_numeric($groupmark))
		{
			$this->errorOutput('标识禁止全数字');
		}
		elseif (preg_match("/([\x81-\xfe][\x40-\xfe])/", $groupmark, $match))
		{
			$this->errorOutput('标识禁止使用汉字');
		}
		elseif(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$groupmark))
		{
			$this->errorOutput('标识禁止使用特殊字符,请使用字母、字母和数字组合(可用下划线连接)!');
		}
		$data = array(
			'grouptitle'    => $grouptitle,
			'groupmark' => $groupmark,
			'description' => $description,
			'app_uniqueid'=>$app_uniqueid,
		);
		return $data;
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new configSet_SortUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>