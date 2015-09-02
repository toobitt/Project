<?php
require('global.php');
define('MOD_UNIQUEID','copywriting');//模块标识
require_once CUR_CONF_PATH . 'lib/copywriting.class.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
class copywritingUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->copywriting = new copywriting();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据
		//验证key是否重复
		$checkResult='';
		$checkResult = $this->copywriting->verify('copywriting',array('operate' => $data['operate']));
		if ($checkResult) $this->errorOutput('标识重复');
		if($data)
		{
			$result = $this->copywriting->create('copywriting',$data);
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

		$info=$this->copywriting->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['operate']!=$info['operate']&&$data['operate'])
		{
			$this->errorOutput('禁止修改标识');
		}
		if ($data)
		{
			$result = $this->copywriting->update('copywriting', $data, array('id' => intval($id)));
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
		$this->copywriting->delete('copywriting',array('id'=>implode(',',$id_array)));//删除
		$this->addItem($id);
		$this->output();
	}

	public function audit()
	{
		//
	}
	public function sort()
	{
		$this->addLogs('更改事件排序', '', '', '更改事件排序');
		$ret = $this->drag_order('copywriting', 'order_id');
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
		$name = isset($this->input['name']) ? trim(urldecode($this->input['name'])) : '';
		$name = preg_replace("/[\r\n]+/", '<br/>', $name);
		$operate = isset($this->input['operate']) ? trim(urldecode($this->input['operate'])) : '';
		$field = isset($this->input['field']) ? trim(urldecode($this->input['field'])) : '';
		$value = isset($this->input['value']) ? trim(urldecode($this->input['value'])) : '';
		$value = preg_replace("/[\r\n]+/", '<br/>', $value);
		if (empty($name)) $this->errorOutput('名称不能为空');
		if (empty($operate)) $this->errorOutput('标识不能为空');
		$data = array(
			'name'    => $name,
			'operate' => $operate,
			'field'=>$field,
			'value' => $value,
		);
		if($_FILES['icon'])//如果有图片,则添加图片数据
		{
			$img['Filedata']=$_FILES['icon'];
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装！');
			}
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($img);
			$img_data = array(
				'host' 			=> $img_info['host'],
				'dir' 			=> $img_info['dir'],
				'filepath' 		=> $img_info['filepath'],
				'filename' 		=> $img_info['filename'],
			);
			$data['icon']=serialize($img_data);
		}
		elseif ($this->input['icondel'])//如果为真.则删除用户组图标
		{
			$data['icon']='';
		}
		return $data;
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new copywritingUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>