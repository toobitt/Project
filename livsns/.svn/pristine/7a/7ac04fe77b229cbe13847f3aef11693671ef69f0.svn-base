<?php
define('MOD_UNIQUEID','member_myset');//模块标识
require('./global.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class member_mysetUpdate extends adminUpdateBase
{
	private $memberMySet;
	public function __construct()
	{
		parent::__construct();
		$this->memberMySet = new memberMySet();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据
		//验证名称是否重复
		$checkResult = $this->memberMySet->count(array('title' => $data['title']));
		if ($checkResult)
		{
			$this->errorOutput('模块名称重复');
		}
		//验证标识是否重复
		$checkResult = $this->memberMySet->count(array('mark' => $data['mark']));
		if ($checkResult)
		{
			$this->errorOutput('模块标识重复');
		}
		if($data)
		{
			$result = $this->memberMySet->create($data);
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

		$info=$this->memberMySet->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['title']!=$info['title'])
		{
			//验证名称是否重复
			$checkResult = $this->memberMySet->count(array('title' => $data['title']));
			if ($checkResult) {
				$this->errorOutput('模块名称重复');
			}
		}
		if($data['mark']!=$info['mark'])
		{
			$this->errorOutput('禁止修改标识');
		}
		if ($data)
		{
			$result = $this->memberMySet->update($data, array('id' => intval($id)));
		}
		$this->addItem($result);
		$this->output();

	}


	/**
	 * 删除
	 */
	public function delete()
	{
		$id = trim($this->input['id']);
		$ids = str_replace('，' , ',' , $id);
		$id_array = explode(',' , $ids);
		//过滤数组中的空值
		$id_array=array_filter($id_array,"clean_array_null");
		$id_array=array_filter($id_array,"clean_array_num_max0");
		if(empty($id_array)) return false;
		//验证 是系统禁止删除。
		$mySetInfo = $this->memberMySet->show(array('id'=>$id_array), 0, 0,'id');
		if (empty($mySetInfo))
		{
			$this->errorOutput('待删除的模块不存在');
		}
		else {
			$delete_id= array();
			foreach ($mySetInfo as $val)
			{
				$delete_id[]=$val['id'];
			}
			if($delete_id)
			{
				$this->memberMySet->delete(array('id'=>$delete_id));
			}
		}
		$this->addItem($id);
		$this->output();
	}

	public function audit()
	{
		//
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
		$data = array('display'=>$opened);
		if($this->memberMySet->update(array('display'=>$opened), array('id'=>$ids)))
		{
			$data['id'] = $ids;
		}
		$this->addItem($data);
		$this->output();
	}

	public function sort()
	{
		$this->addLogs('更改会员我的模块排序', '', '', '更改会员我的模块排序');
		$ret = $this->drag_order('member_myset', 'order_id');
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
		$mark = isset($this->input['mark']) ? trim(urldecode($this->input['mark'])) : '';
		$url = isset($this->input['url']) ? trim(urldecode($this->input['url'])) : '';
		$usesource = isset($this->input['usesource']) ? intval($this->input['usesource']) : 0;
		$uniquetype = in_array($this->input['uniquetype'], array('1','2','3')) ? intval($this->input['uniquetype']) : 0;
		$display = isset($this->input['display']) ? intval($this->input['display']) : 0;
		if (empty($title)) $this->errorOutput('模块名不能为空');
		if (empty($mark)) $this->errorOutput('模块标识不能为空');
		if(is_numeric($mark))
		{
			$this->errorOutput('标识禁止全数字');
		}
		elseif (preg_match("/([\x81-\xfe][\x40-\xfe])/", $mark))
		{
			$this->errorOutput('标识禁止使用汉字');
		}
		else if(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$mark))
		{
			$this->errorOutput('标识禁止使用特殊字符,请使用字母、字母和数字组合(可用下划线连接)!');
		}
		$data = array(
			'title'    => $title,
			'mark'    => $mark,
			'url' => $url,
			'display' => $display,
			'usesource'=>$usesource,
			'uniquetype' => $uniquetype,
		);
		if($_FILES['img'])//如果有图片,则添加图片数据
		{
			$img['Filedata']=$_FILES['img'];
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
			$data['icon']=maybe_serialize($img_data);
		}
		elseif($this->input['icondel'])//如果为真.则删除图标
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

$out = new member_mysetUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>