<?php
define('MOD_UNIQUEID','member_verify_set');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_verify_set.class.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
class member_verifyUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->verifyset = new verifyset();
		$this->Members=new members();
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

		$info=$this->verifyset->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if ($data)
		{
			$result = $this->membersql->update('verify_set', $data, array('id' => intval($id)));
		}
		$this->addItem($result);
		$this->output();

	}

	//开关
	public function display()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		$opened = intval($this->input['is_on']);
		$opened = ($opened ==1) ? $opened : 0;
		$data = $this->verifyset->display($ids,$opened);
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
		$showicon = isset($this->input['showicon']) ? $this->input['showicon'] : 0;
		$field = isset($this->input['field'])&&is_array($this->input['field']) ? $this->input['field'] :array();
		$data = array('showicon'=>$showicon);
		$_field = array();
		foreach ($field as $v)
		{
			$_field[trim($v)] = trim($v);
		}
		if($_field)
		{
			$data['field'] = maybe_serialize($_field);
		}
		else {
			$data['field'] = '';
		}
		if($_FILES['icon'])//如果有图片,则添加图片数据
		{
			$data['icon']=$this->img_upload($_FILES['icon']);
		}
		elseif ($this->input['icondel'])//如果为真.则删除图标
		{
			$data['icon']='';
		}
		if($_FILES['unverifyicon'])//如果有图片,则添加图片数据
		{
			$data['unverifyicon']=$this->img_upload($_FILES['unverifyicon']);
		}
		elseif ($this->input['unverifyicondel'])//如果为真.则删除用户组图标
		{
			$data['unverifyicon']='';
		}
		return $data;
	}
	
	public function img_upload($img_file)
	{
			require_once(ROOT_PATH.'lib/class/material.class.php');
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

$out = new member_verifyUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>