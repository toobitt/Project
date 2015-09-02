<?php
define('MOD_UNIQUEID','member_credit_type');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_credit_type.class.php';
class member_credit_typeUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->credittype = new credittype();
		$this->membersql = new membersql();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{

	}
	/**
	 *
	 * 更新
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$info=$this->credittype->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['title']!=$info['title'])
		{
			//验证名称是否重复
			$checkResult =$this->membersql->verify('credit_type',array('title' => $data['title']));
			if ($checkResult) $this->errorOutput('名称重复');
		}
		if ($data)
		{
			$result = $this->membersql->update('credit_type', $data, array('id' => intval($id)));
		}
		$this->addItem($result);
		$this->output();

	}

	//积分类型配置开关
	//此功能已经取消
	public function display()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		$opened = intval($this->input['is_on']);
		$field = trim($this->input['field']);
		$opened = $opened ? 0 : 1;
		$data = $this->credittype->display($ids,$opened,$field);
		$this->addItem($data);
		$this->output();
	}

	/**
	 * 删除
	 */
	public function delete()
	{

	}

	public function audit()
	{
		//
	}
	public function sort()
	{
		$this->addLogs('更改积分类型排序', '', '', '更改积分类型排序');
		$ret = $this->drag_order('credit_type', 'order_id');
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
		$title = isset($this->input['title']) ? trim(urldecode($this->input['title'])) : '';

		if (empty($title)) $this->errorOutput('积分名填不能为空');
		$data = array(
			'title'    => $title,
		);
		if($_FILES['img'])//如果有图片,则添加图片数据
		{
			$data['img']=$this->img_upload($_FILES['img']);
		}
		elseif ($this->input['icondel'])//如果为真.则删除图标
		{
			$data['img']='';
		}
		return $data;
	}
	/**
	 *
	 * 图片上传,内调私有函数
	 * @param _FILES $img_file
	 */
	private function img_upload($img_file)
	{
		include(ROOT_PATH.'lib/class/material.class.php');
		$img['Filedata']=$img_file;
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
		return maybe_serialize($img_data);
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new member_credit_typeUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>