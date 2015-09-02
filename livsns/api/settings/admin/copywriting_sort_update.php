<?php
require('global.php');
define('MOD_UNIQUEID','copywriting_sort');//模块标识
require_once CUR_CONF_PATH . 'lib/copywriting_sort.class.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
class copywriting_sortUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->copywriting_sort = new copywriting_sort();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据
		//验证名称是否重复
		$checkResult='';
		$checkResult = $this->copywriting_sort->verify('copywriting_sort',array('name' => $data['name']));
		if ($checkResult) $this->errorOutput('名称重复');
		//验证key是否重复
		$checkResult='';
		$checkResult = $this->copywriting_sort->verify('copywriting_sort',array('field' => $data['field']));
		if ($checkResult) $this->errorOutput('标识重复');
		if($data)
		{
			$result = $this->copywriting_sort->create('copywriting_sort',$data);
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

		$info=$this->copywriting_sort->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['name']!=$info['name'])
		{
			//验证名称是否重复
			$checkResult = $this->copywriting_sort->verify('copywriting_sort',array('name' => $data['name']));
			if ($checkResult) $this->errorOutput('名称重复');
		}
		if($data['field']!=$info['field']&&$data['field'])
		{
			$this->errorOutput('禁止修改标识');
		}
		if ($data)
		{
			$result = $this->copywriting_sort->update('copywriting_sort', $data, array('id' => intval($id)));
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
		$id_array = array_filter($id_array);
		if(empty($id_array)) return false;
		$this->copywriting_sort->delete('copywriting_sort',array('id'=>implode(',',$id_array)));//删除分组同时删除权限绑定表
		$this->addItem($id);
		$this->output();
	}

	public function audit()
	{
		//
	}
	public function sort()
	{
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
		$name = isset($this->input['name']) ? trim(urldecode($this->input['name'])) : '';
		$field = isset($this->input['field']) ? trim(urldecode($this->input['field'])) : '';
		$description = isset($this->input['description']) ? trim(urldecode($this->input['description'])) : '';
		if (empty($name)) $this->errorOutput('名称不能为空');
		if (empty($field)) $this->errorOutput('标识不能为空');
		$data = array(
			'name'    => $name,
			'field' => $field,
			'description' => $description,
		);
		return $data;
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new copywriting_sortUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>