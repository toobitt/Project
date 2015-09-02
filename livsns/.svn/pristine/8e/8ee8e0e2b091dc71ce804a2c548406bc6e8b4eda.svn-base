<?php
define('MOD_UNIQUEID','medal_manage');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/medal_manage.class.php';
class medal_manageUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->medalmanage = new medalmanage();
		$this->Members=new members();
		$this->membersql = new membersql();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据
		//验证名称是否重复
		$checkResult = $this->membersql->verify('medal',array('name' => $data['name']));
		if ($checkResult)
		{
			$this->errorOutput(MEDAL_NAME_EXISTS);
		}
		if(empty($data['image']))
		{
			$this->errorOutput(MEDAL_IMAGE_EMPTY);
		}
		if($data)
		{
			$result = $this->membersql->create('medal',$data,true);
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

		$info=$this->medalmanage->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		if(empty($info['image'])&&empty($data['image']))
		{
			$this->errorOutput(MEDAL_IMAGE_EMPTY);
		}
		$data = $this->filter_data(); //获取提交的数据
		if($info['name']!=$data['name'])
		{
			//验证名称是否重复
			$checkResult = $this->membersql->verify('medal',array('name' => $data['name']));
			if ($checkResult)
			{
				$this->errorOutput(MEDAL_NAME_EXISTS);
			}
		}
		if ($data)
		{
			$result = $this->membersql->update('medal', $data, array('id' => intval($id)));
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
		$data = $this->medalmanage->display($ids,$opened);
		$this->addItem($data);
		$this->output();
	}


	/**
	 * 删除
	 */
	public function delete()
	{
		$ids = isset($this->input['id']) ? trim($this->input['id']) : '';
		if (empty($ids))
		{
			$this->errorOutput(NO_DATA_ID);
		}
		$this->membersql->delete('medal', array('id' => $ids));
		$this->addItem($ids);
		$this->output();
	}

	public function audit()
	{
		//
	}
	public function sort()
	{
		$this->addLogs('勋章排序', '', '', '更改勋章排序');
		$ret = $this->drag_order('medal', 'order_id');
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
		$brief = isset($this->input['brief']) ? trim(urldecode($this->input['brief'])) : '';
		$is_award_time = isset($this->input['is_award_time']) ? intval($this->input['is_award_time']) : 0;
		$start_date = $is_award_time?(!empty($this->input['start_date']) ? strtotime(trim($this->input['start_date'])) : 0):0;
		$end_date = $is_award_time?(!empty($this->input['end_date']) ? strtotime(trim($this->input['end_date'])) : 0):0;
		$limit_num = isset($this->input['limit_num']) ? abs_num($this->input['limit_num']) : 0;
		$expiration = isset($this->input['expiration']) ? abs_num($this->input['expiration']) : 0;
		$medal_type = isset($this->input['medal_type']) ? intval($this->input['medal_type']) : 0;
		$data = array(
		'name'=>$name,
		'brief'=>$brief,
		'start_date'=>$start_date,
		'end_date'=>$end_date,
		'limit_num'=>$limit_num,
		'expiration'=>$expiration,
		'type'=>$medal_type,
		);
		if($_FILES['image'])//如果有图片,则添加图片数据
		{
			$data['image']=$this->img_upload($_FILES['image']);
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

$out = new medal_manageUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>